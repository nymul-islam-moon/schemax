<?php

namespace Schema\Engine;

use Schema\Inc\Service;


class Product
{

    public $product;
    private $schema_type, $schema_name, $schema_service;
    private $product_type;

    public function __construct($product_id = null)
    {
        global $product;
        if ($product instanceof \WC_Product) {
            $this->product = $product;
        } else {
            $this->product = wc_get_product($product_id);
        }
        $this->product_type = $this->product->get_type();
//        error_log( print_r( $this->product_type, true ) );

        $this->schema_service = new Service();
        $this->schema_name    = 'product.json';
        $this->schema_type    = 'product';
    }

    /**
     * Update the product schema using the realtime data
     *
     * @return mixed|null
     */
    protected function update_schema(): string {
        $updated_schema_data = null;
        $schema_arr                         = $this->schema_service->read_schema($this->schema_name);

        if ($this->product_type == 'simple') {

            $updated_schema_data            = json_encode( $this->single_product( $schema_arr ) );

        } else if ($this->product_type == 'variable') {
            $children                       = $this->product->get_children();
            $variable_product_arr           = [];
            foreach ($children as $variation_id) {

                $this->product              = wc_get_product( $variation_id );

                $variable_product_arr[]     = $this->single_product( $schema_arr );
            }

            $updated_schema_data = json_encode( $variable_product_arr );
        } else if ( $this->product_type = 'grouped' ) {
            $children                       = $this->product->get_children();
            $grouped_product_arr            = [];
            foreach ($children as $grouped_id) {

                $this->product              = wc_get_product( $grouped_id );

                $grouped_product_arr[]      = $this->single_product( $schema_arr );
            }

            $updated_schema_data            = json_encode( $grouped_product_arr );
        }


        return apply_filters("schemax_{$this->schema_type}_update_schema", $updated_schema_data, $this->product);
    }

    /**
     * Get specific product information
     *
     * @param array $product_arr
     * @return array
     */
    protected function single_product( array $product_arr ): array {

        $product_arr['name']             = !empty($this->name()) ? $this->name() : '';
        $product_arr['description']      = !empty($this->description()) ? $this->description() : '';
        $product_arr['review']           = $this->review();
        $product_arr['aggregateRating']  = $this->aggregateRating();
        $product_arr['image']            = $this->image();
        $product_arr['brand']            = $this->brand();
        $product_arr['offers']           = $this->offers();

        return $product_arr;
    }

    /**
     * Get Product Name
     *
     * @return string
     */
    protected function name(): string {
        return apply_filters("schemax_{$this->schema_type}", $this->product->get_name(), $this->product);
    }

    /**
     * Product Description
     *
     * @return string
     */
    protected function description(): string {
        return apply_filters("schemax_{$this->schema_type}", $this->product->get_description(), $this->product);
    }

    /**
     * Return the review array
     *
     * @return array
     */
    protected function review(): array {
        $args = array(
            'post_id' => $this->product ? $this->product->get_id() : '',
            'status' => 'approve'
        );

        $review_arr    = get_comments($args);
        $review_data[] = array();
        foreach ($review_arr as $key => $review) {
            $singleReviewData  = [
                '@type' => 'Review',
                'reviewRating' => [
                    '@type' => 'Rating',
                    'ratingValue' => get_comment_meta($review->comment_ID, 'rating', true),
                    'bestRating' => 5
                ],
                'author' => [
                    '@type' => 'Person',
                    'name' => $review->comment_author ?? ''
                ],
                'comment' => $review->comment_content ?? ''
            ];
            $review_data[$key] = $singleReviewData;
        }

        return $review_data;
    }

    /**
     * Get the Aggregate Rating
     *
     * @return array
     */
    protected function aggregateRating(): array {

        $args         = array(
            'post_id' => $this->product ? $this->product->get_id() : '',
            'count' => true
        );
        $review_count = get_comments($args);

        $aggregate_rating = [
            "@type" => "AggregateRating",
            "ratingValue" => $this->product->get_average_rating(),
            "reviewCount" => $review_count
        ];

        return $aggregate_rating;
    }

    /**
     * Image for Update method
     *
     * @return false|string
     */
    protected function image()
    {
        if ($this->product) {
            $image_id = $this->product->get_image_id();
            return wp_get_attachment_image_url($image_id, 'full');
        }
        return '';
    }

    /**
     * Brand name for Update method
     *
     * @return string[]
     */
    protected function brand()
    {
        $brand = [
            "@type" => "Thing",
            "name" => ""
        ];
        return $brand;
    }

    /**
     * Offer data for update method
     *
     * @return array
     */
    protected function offers(): array {

        $offers = [
            "@type" => "Offer",
            "price" => $this->product->get_regular_price() ? $this->product->get_regular_price() : '',
            "priceCurrency" => get_woocommerce_currency() ? get_woocommerce_currency() : '',
            "priceSpecification" => $this->offers_priceSpecification(),
            "priceValidUntil" => $this->offers_priceValidUntil(),
            "priceValidFrom" => $this->offers_priceValidFrom(),
            "availability" => $this->product->get_stock_status() ? $this->product->get_stock_status() : '',
            "quantity" => $this->product->get_stock_quantity() != null ? $this->product->get_stock_quantity() : '',
            "url" => $this->product->get_permalink() ? $this->product->get_permalink() : '',
            "seller" => $this->offers_saller(),
            "itemCondition" => $this->offers_itemCondition(),
            "category" => !empty($this->offers_category()['name']) ? $this->offers_category()['name'] : '',
            "mpn" => "working in progress",
            "gtin8" => "working in progress",
            "gtin13" => "working in progress",
            "gtin14" => "working in progress",
            "weight" => $this->product->has_weight() ? $this->offers_weight() : '',
            "depth" => !empty($this->offers_depth()['value']) ? $this->offers_depth() : '',
            "width" => !empty($this->offers_width()['value']) ? $this->offers_width() : '',
            "height" => !empty($this->offers_height()['value']) ? $this->offers_height() : '',
            "shippingDetails" => $this->offers_shippingDetails()
        ];

        return $offers;
    }

    /**
     * Get the priceSpecification information for the Offers method
     *
     * @return array
     */
    protected function offers_priceSpecification()
    {

        $priceSpecification = [
            "@type" => "PriceSpecification",
            "price" => $this->product->get_sale_price() ? $this->product->get_sale_price() : '',
            "valueAddedTaxIncluded" => $this->product->get_tax_status() ? $this->product->get_tax_status() : '',
            "taxPercentage" => !empty($this->tax()['tax_rates'][1]['rate']) ? $this->tax()['tax_rates'][1]['rate'] : '',
            "taxFixedAmount" => !empty($this->tax()['tax_rates'][1]['rate']) ? ($this->tax()['tax_rates'][1]['rate'] / 100) * $this->tax()['price'] : ''
        ];
        return $priceSpecification;
    }

    /**
     * Get the Tax information
     *
     * @return array
     */
    protected function tax()
    {
        $wc_tax = new \WC_Tax();

        $tax_rates = $wc_tax::get_rates($this->product->get_tax_class());
        $price     = $this->product->get_price();
        $taxes     = $wc_tax::calc_tax($price, $tax_rates, false);
        $tax_total = $wc_tax::get_tax_total($taxes);

        $tax = [
            "tax_rates" => $tax_rates,
            "price" => $price,
            "taxes" => $taxes,
            "tax_total" => $tax_total
        ];

        return $tax;
    }

    /**
     * Get priceValidUntil for Offers method
     *
     * @return string
     */
    protected function offers_priceValidUntil()
    {
        return 'working in progress';
    }

    /**
     * Get priceValidFrom informatin for Offers method
     *
     * @return string
     */
    protected function offers_priceValidFrom()
    {
        return 'working in progress';
    }

    protected function offers_saller()
    {
        $saller = [
            "@type" => 'Organization',
            "name" => '',
            "url" => '',
            "contactPoint" => $this->ContactPoint(),
        ];
        return $saller;
    }

    protected function offers_itemCondition()
    {
        $itemCondition = get_post_meta($this->product->get_id(), 'item_condition', true);

        if (!empty($itemCondition)) {
            return $itemCondition;
        }

        return '';
    }

    /**
     * Saller ContactPoint information
     *
     * @return string[]
     */
    protected function ContactPoint()
    {
        $contactPoint = [
            "@type" => "ContactPoint",
            "contactType" => "",
            "telephone" => "",
            "availableLanguage" => "",
            "url" => ""
        ];
        return $contactPoint;
    }

    protected function offers_category()
    {

        $categoryObj = wp_get_post_terms($this->product->get_id(), 'product_cat');

        $category = [
            "term_id" => $categoryObj[0]->term_id,
            "name" => $categoryObj[0]->name,
            "slug" => $categoryObj[0]->slug,
            "term_group" => $categoryObj[0]->term_group,
            "term_taxonomy_id" => $categoryObj[0]->term_taxonomy_id,
            "taxonomy" => $categoryObj[0]->taxonomy,
            "description" => $categoryObj[0]->description,
            "parent" => $categoryObj[0]->parent,
            "count" => $categoryObj[0]->count,
        ];

        return $category;
    }

    protected function offers_weight()
    {

        $weight = [
            "@type" => "QuantitativeValue",
            "value" => $this->product->get_weight(),
            "unitCode" => get_option('woocommerce_weight_unit')
        ];

        return $weight;
    }

    protected function offers_depth()
    {

        $depth = [
            "@type" => "QuantitativeValue",
            "value" => $this->product->get_length(),
            "unitCode" => get_option('woocommerce_dimension_unit')
        ];

        return apply_filters("schemax_{$this->schema_type}_offers_depth", $depth, $this->product);
    }

    protected function offers_width()
    {
        $width = [
            "@type" => "QuantitativeValue",
            "value" => $this->product->get_width(),
            "unitCode" => get_option('woocommerce_dimension_unit')
        ];

        return apply_filters("schemax_{$this->schema_type}_offers_width", $width, $this->product);
    }

    protected function offers_height()
    {
        $height = [
            "@type" => "QuantitativeValue",
            "value" => $this->product->get_height(),
            "unitCode" => get_option('woocommerce_dimension_unit')
        ];

        return apply_filters("schemax_{$this->schema_type}_offers_width", $height, $this->product);
    }

    protected function offers_shippingDetails()
    {

        $shippingDetails = array();

        $shipping_class_id = $this->product->get_shipping_class_id();

        $shipping_zones = \WC_Shipping_Zones::get_zones();

        foreach ($shipping_zones as $zone_id => $zone_data) {

            $zone             = new \WC_Shipping_Zone($zone_id);
            $shipping_methods = $zone->get_shipping_methods(true);

            $data              = [
                "@type" => "OfferShippingDetails",
                "shippingRate" => '',//$this->shippingDetails_shippingRate(),
                "shippingDestination" => $this->shippingDetails_shippingDestination($zone_data['zone_name']),
                "deliveryTime" => '',//$this->shippingDetails_deliveryTime(),
                "taxShippingDetails" => '', //$this->shippingDetails_taxShippingDetails()
            ];
            $shippingDetails[] = $data;
        }

        return $shippingDetails;
    }

    protected function shippingDetails_shippingRate()
    {
        $shippingRate = [
            "@type" => "MonetaryAmount",
            "currency" => "",
            "value" => ""
        ];

        return $shippingRate;
    }

    protected function shippingDetails_shippingDestination($zone_location)
    {

        $shippingDestination = [
            "@type" => "DefinedRegion",
            "addressCountry" => $zone_location
        ];
        return $shippingDestination;
    }

    protected function shippingDetails_deliveryTime()
    {

        $deliveryTime = [
            "@type" => "ShippingDeliveryTime",
            "handlingTime" => $this->shippingDetails_deliveryTime_handlingTime(),
            "transitTime" => $this->shippingDetails_deliveryTime_transitTime()
        ];

        return $deliveryTime;
    }

    protected function shippingDetails_taxShippingDetails()
    {
        $taxShippingDetails = [
            "@type" => "OfferShippingDetails",
            "shippingRate" => $this->shippingDetails_taxShippingDetails_shippingRate(),
        ];

        return $taxShippingDetails;
    }

    protected function shippingDetails_taxShippingDetails_shippingRate()
    {
        $shippingRate = [
            "@type" => "MonetaryAmount",
            "currency" => "",
            "value" => ""
        ];
        return $shippingRate;
    }

    protected function shippingDetails_deliveryTime_handlingTime()
    {
        $handlingTime = [
            "@type" => "QuantitativeValue",
            "minValue" => "",
            "maxValue" => "",
            "unitCode" => "DAY"
        ];
        return $handlingTime;
    }

    protected function shippingDetails_deliveryTime_transitTime()
    {
        $transitTime = [
            "@type" => "QuantitativeValue",
            "minValue" => "",
            "maxValue" => "",
            "unitCode" => ""
        ];

        return $transitTime;
    }

    public function attach_schema()
    {
        $updated_data = $this->update_schema();
        echo "<script src='schemax-$this->schema_type' type='application/ld+json'>$updated_data</script>";
    }
}