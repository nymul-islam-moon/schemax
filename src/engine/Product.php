<?php

namespace Schema\Engine;

use Schema\Inc\Service;


class Product
{

    public $product;
    private $schema_type, $schema_name, $schema_service, $product_type, $schema_structure;

    /**
     * Construct for the Product Class
     *
     * @param $product_id
     */
    public function __construct( $product_id = null )
    {
        global $product;
        if ( $product instanceof \WC_Product ) {
            $this->product      = $product;
        } else {
            $this->product      = wc_get_product($product_id);
        }
        $this->product_type     = $this->product->get_type();

        $this->schema_service   = new Service();
        $this->schema_name      = 'product.json';
        $this->schema_type      = 'product';

    }

    /**
     * Update Schema
     *
     * @return string
     */
    protected function update_schema() {
        $this->schema_structure             = $this->schema_service->read_schema( $this->schema_name );

        if ($this->product_type == 'simple') {
            $updated_schema_data            = json_encode( $this->single_product( $this->schema_structure ) );
        }

        if ($this->product_type == 'variable') {
            $children                       = $this->product->get_children();
            $variable_product_arr           = [];
            foreach ($children as $variation_id) {
                $this->product              = wc_get_product( $variation_id );
                $variable_product_arr[]     = $this->single_product( $this->schema_structure );
            }
            $updated_schema_data            = json_encode( $variable_product_arr );
        }

        if ( $this->product_type = 'grouped' ) {
            $children                       = $this->product->get_children();
            $grouped_product_arr            = [];
            foreach ($children as $grouped_id) {
                $this->product              = wc_get_product( $grouped_id );
                $grouped_product_arr[]      = $this->single_product( $this->schema_structure );
            }
            $updated_schema_data            = json_encode( $grouped_product_arr );
        }

        if ( $this->product->is_virtual() ) {
            $updated_schema_data            = json_encode( $this->single_product( $this->schema_structure ) );
        }

        if ( $this->product->is_downloadable() ) {
            $updated_schema_data            = json_encode( $this->single_product( $this->schema_structure ) );
        }

        if ( $this->product->get_type() === 'external' ) {
            $updated_schema_data            = json_encode( $this->single_product( $this->schema_structure ) );
        }

//        error_log( print_r( 'here', true ) );

        return apply_filters("schemax_{$this->schema_type}_update_schema", $updated_schema_data, $this->product);
    }

    /**
     * Get specific product information
     *
     * @param array $product_arr
     * @return array
     */
    protected function single_product( $product_arr ) {

        if ( isset( $product_arr['name'] ) ) {
            $product_arr['name']                = !empty( $this->name() ) ? $this->name() : '';
        }

        if ( isset( $product_arr['description'] ) ) {
            $product_arr['description']         = !empty( $this->description() ) ? $this->description() : '';
        }

        $review = $this->review( $product_arr['review'] );
        if ( isset( $product_arr['review'] ) && ! empty ( $review ) ) {
            $product_arr['review']              = $review;
        } else {
            unset( $product_arr['review'] );
        }

        $aggregateRating = $this->aggregateRating( $product_arr['aggregateRating'] );
        if ( isset( $product_arr['aggregateRating'] ) && isset( $review ) && !empty( $review ) && !empty( $aggregateRating ) ) {
            $product_arr['aggregateRating']     = $aggregateRating;
        } else {
            unset( $product_arr['aggregateRating'] );
        }

        $images = $this->image();
        if ( isset( $product_arr['image'] )  && !empty( $images ) ) {
            $product_arr['image']               = $images;
        } else {
            unset( $product_arr['image'] );
        }

        $brand = $this->brand( $product_arr['brand'] );
        if ( isset( $product_arr['brand'] ) && !empty( $brand ) ) {
            $product_arr['brand']               = $brand;
        } else {
            unset( $product_arr['brand'] );
        }

        if ( isset( $product_arr['offers'] ) ) {
            $product_arr['offers']              = $this->offers( $product_arr['offers'] );
        } else {
            unset( $product_arr['offers'] );
        }

        return apply_filters("schemax_{$this->schema_type}_single_product", $product_arr, $this->product);
    }

    /**
     * Get Product Name
     *
     * @return string
     */
    protected function name() {
        return apply_filters("schemax_{$this->schema_type}", $this->product->get_name(), $this->product);
    }

    /**
     * Product Description
     *
     * @return string
     */
    protected function description() {
        return apply_filters("schemax_{$this->schema_type}", $this->product->get_description(), $this->product);
    }

    /**
     * Get the review information
     *
     * @param array $review    | Review Schema Structure
     * @return array
     */
    protected function review( $review ) {

        $args = array(
            'post_id'   => $this->product ? $this->product->get_id() : '',
            'status'    => 'approve'
        );

        $review_arr     = get_comments( $args );

        $review_data    = [];

        if ( ! empty( $review_arr ) ) {

            foreach ( $review_arr as $key => $item ) {
                $review_structure   = $review[0];

                $review_reviewRating = $this->review_reviewRating( $review_structure['reviewRating'], $item->comment_ID );
                if ( isset( $review_structure['reviewRating'] ) && !empty( $review_reviewRating ) ) {
                    $review_structure['reviewRating']       = $review_reviewRating;
                } else {
                    continue;
                }

                $author_name = $item->comment_author;
                if ( isset( $review_structure['author'] ) && !empty( $author_name ) ) {
                    $review_structure['author']['name']     = $author_name;
                } else {
                    unset( $review_structure['author'] );
                }

                $comment = $item->comment_content;
                if ( isset( $review_structure['comment'] ) && !empty( $comment ) ) {
                    $review_structure['comment']            = $comment;
                } else {
                    unset( $review_structure['comment'] );
                }

                $review_data[]                              = $review_structure;
            }

            if ( empty( $review_data ) ) {
                return [];
            }

            return apply_filters("schemax_{$this->schema_type}_review", $review_data, $this->product);
        }

        return [];
    }

    /**
     * Get the ReviewRating information
     *
     * @param array $reviewRating
     * @param int $commentId
     * @return array
     */
    protected function review_reviewRating( $reviewRating, $commentId ) {

        if ( isset( $reviewRating['ratingValue'] ) && get_comment_meta( $commentId , 'rating', true) ) {
            $reviewRating['ratingValue'] = (int) get_comment_meta( $commentId, 'rating', true );
        }

        if ( ! empty( $reviewRating['ratingValue'] ) ) {
            return apply_filters("schemax_{$this->schema_type}_review_reviewRating", $reviewRating, $this->product);
        }
        return [];
    }

    /**
     * Get the Aggregate Rating
     *
     * @param array $aggregateRating
     * @return array
     */
    protected function aggregateRating( $aggregateRating ) {

        $args         = array(
            'post_id'   => $this->product ? $this->product->get_id() : '',
            'count'     => true
        );
        $review_count = get_comments($args);

        $average_rating = (float) $this->product->get_average_rating();
        if ( isset( $aggregateRating['ratingValue'] ) && !empty( $average_rating ) ) {
            $aggregateRating["ratingValue"]         = $average_rating;
        }

        if ( isset( $aggregateRating['reviewCount'] ) && !empty( $review_count ) ) {
            $aggregateRating['reviewCount']         =  (int) $review_count;
        }

        
        if ( !empty( $aggregateRating['ratingValue'] ) && !empty( $aggregateRating['reviewCount'] ) ) {
            return apply_filters("schemax_{$this->schema_type}_aggregateRating", $aggregateRating, $this->product);
        }

        return [];
    }

    /**
     * @return array
     */
    protected function image( $images = [] ) {

        $gallery_image_ids = $this->product->get_gallery_image_ids();

        $feature_image = wp_get_attachment_url($this->product->get_image_id());
        if( ! empty( $feature_image ) ) {
            $images[] = $feature_image;
        }

        foreach ($gallery_image_ids as $image_id) {
            $gallery_image = wp_get_attachment_url( $image_id );
            if ( ! empty( $gallery_image ) ) {
                $images[] = $gallery_image;
            }
        }

        return apply_filters("schemax_{$this->schema_type}_image", $images, $this->product);
    }

    /**
     * Brand name for Update method
     *
     * @return string[]
     */
    protected function brand( $brand ) {

        $brand['name']  = get_bloginfo('name');

        if ( empty ( $brand['name'] ) ) {
            return [];
        }
        return apply_filters("schemax_{$this->schema_type}_brand", $brand, $this->product);
    }

    /**
     * Get offers information
     *
     * @param array $offers_arr
     * @return array
     */
    protected function offers( $offers_arr ) {

        if ( isset( $offers_arr['price'] ) ) {
            $offers_arr['price']                        = $this->product->get_regular_price() ?? '';
        }

        if ( isset( $offers_arr['priceCurrency'] ) ) {
            $offers_arr['priceCurrency']                = get_woocommerce_currency() ?? '';
        }

        if ( isset( $offers_arr['priceSpecification'] ) ) {
            if ( !empty( $this->offers_priceSpecification( $offers_arr['priceSpecification'] ) ) ) {
                $offers_arr['priceSpecification']       = $this->offers_priceSpecification( $offers_arr['priceSpecification'] );
            } else {
                unset( $offers_arr['priceSpecification'] );
            }
        } else {
            unset( $offers_arr['priceSpecification'] );
        }

        if ( isset( $offers_arr['hasMerchantReturnPolicy'] ) && !empty( $this->offers_hasMerchantReturnPolicy( $offers_arr['hasMerchantReturnPolicy'] ) ) ) {
            $offers_arr['hasMerchantReturnPolicy']      = $this->offers_hasMerchantReturnPolicy( $offers_arr['hasMerchantReturnPolicy'] );
        } else {
            unset( $offers_arr['hasMerchantReturnPolicy'] );
        }

        if ( isset( $offers_arr['priceValidUntil'] ) && !empty( $this->offers_priceValidUntil() ) ) {
            $offers_arr['priceValidUntil']              = $this->offers_priceValidUntil();
        } else {
            unset( $offers_arr['priceValidUntil'] );
        }

        if ( isset( $offers_arr['availability'] ) && $this->product->get_stock_status() ) {
            $offers_arr['availability']                 = $this->product->get_stock_status();
        } else {
            unset( $offers_arr['availability'] );
        }

        if ( isset( $offers_arr['quantity'] ) && !empty( $this->product->get_stock_quantity() ) ) {
            $offers_arr['quantity']                     = $this->product->get_stock_quantity();
        } else {
            unset( $offers_arr['quantity'] );
        }

        if ( isset( $offers_arr['url'] ) && !empty( $this->product->get_permalink() ) ){
            $offers_arr['url']                          = $this->product->get_permalink();
        } else {
            unset( $offers_arr['url'] );
        }

        if ( isset( $offers_arr['seller'] ) && !empty( $this->offers_seller( $offers_arr['seller'] ) ) ) {
            $offers_arr['seller']                       = $this->offers_seller( $offers_arr['seller'] );
        } else {
            unset( $offers_arr['seller'] );
        }

        if ( isset( $offers_arr['itemCondition'] ) && !empty( $this->offers_itemCondition() ) ) {
            $offers_arr['itemCondition']                = $this->offers_itemCondition();
        } else {
            unset( $offers_arr['itemCondition'] );
        }

        if ( isset( $offers_arr['category'] ) && !empty( $this->offers_category() ) ) {
            $offers_arr['category']                     = $this->offers_category()['name'];
        } else {
            unset( $offers_arr['category'] );
        }

        if ( isset( $offers_arr['mpn'] ) && !empty( $this->offers_mpn() ) ) {
            $offers_arr['mpn']                          = '';
        } else {
            unset( $offers_arr['mpn'] );
        }

        if ( isset( $offers_arr['gtin8'] ) && !empty( $this->offers_gtin8() ) ) {
            $offers_arr['gtin8']                        = '';
        } else {
            unset( $offers_arr['gtin8'] );
        }

        if ( isset( $offers_arr['gtin13'] ) && !empty( $this->offers_gtin13() ) ) {
            $offers_arr['gtin13']                       = '';
        } else {
            unset( $offers_arr['gtin13'] );
        }

        if ( isset( $offers_arr['gtin14'] ) && !empty( $this->offers_gtin14() ) ) {
            $offers_arr['gtin14']                       = '';
        } else {
            unset( $offers_arr['gtin14'] );
        }

        if ( isset( $offers_arr['weight'] ) && ! empty( $this->offers_weight() ) ) {
            $offers_arr['weight']                       = $this->offers_weight();
        } else {
            unset( $offers_arr['weight'] );
        }

        if ( isset( $offers_arr['depth'] ) && !empty( $this->offers_depth() ) ) {
            $offers_arr['depth']                        = $this->offers_depth();
        } else {
            unset( $offers_arr['depth'] );
        }

        if ( isset( $offers_arr['width'] ) && !empty( $this->offers_width() ) ) {
            $offers_arr['width']                        = $this->offers_width();
        } else {
            unset( $offers_arr['width'] );
        }

        if ( isset( $offers_arr['height'] ) && !empty( $this->offers_height() ) ) {
            $offers_arr['height']                       = $this->offers_height();
        } else {
            unset( $offers_arr['height'] );
        }

        if ( isset( $offers_arr['shippingDetails'] ) && !empty( $this->product->needs_shipping() ) && !empty( $this->offers_shippingDetails( $offers_arr['shippingDetails'] ) ) ) {
            $offers_arr['shippingDetails'] = $this->offers_shippingDetails( $offers_arr['shippingDetails'] );
        } else {
            unset( $offers_arr['shippingDetails'] );
        }

        return $offers_arr;
    }

    /**
     * Get the PriceSpecification information
     *
     * @param array $priceSpecification
     * @return array
     */
    protected function offers_priceSpecification( $priceSpecification ) {

        $priceSpecification = [];

        $priceSpecification['price'] = ! empty( $this->product->get_sale_price() ) ? $this->product->get_sale_price() : '';

        if ( empty( $priceSpecification['price'] ) ) {
            return [];
        }

        $priceSpecification['valueAddedTaxIncluded'] = $this->product->get_tax_status() == 'taxable' ? true : '';

        if ( !empty( $priceSpecification['valueAddedTaxIncluded'] ) ) {

            $priceSpecification["taxPercentage"]  = !empty($this->tax()['tax_rates'][1]['rate']) ? $this->tax()['tax_rates'][1]['rate'] : '';
            $priceSpecification["taxFixedAmount"]  = !empty($this->tax()['tax_rates'][1]['rate']) ? ($this->tax()['tax_rates'][1]['rate'] / 100) * $this->tax()['price'] : '';
        } else {
            unset( $priceSpecification['valueAddedTaxIncluded'], $priceSpecification['taxPercentage'], $priceSpecification['taxFixedAmount'] );
        }

        return $priceSpecification;
    }

    protected function offers_hasMerchantReturnPolicy ( $hasMerchantReturnPolicy ) { // TODO this method is incomplete for lake of information

        $privacy_policy_page_id = (int) get_option('wp_page_for_privacy_policy');

        if ( $privacy_policy_page_id ) {
            $privacy_policy_page = get_post( $privacy_policy_page_id );
            $privacy_policy_content = apply_filters('the_content', $privacy_policy_page->post_content);
            $privacy_policy_title = get_the_title($privacy_policy_page_id);

        } else {
            echo 'No Privacy Policy page set.';
        }

        return [];
    }

    /**
     * Get the Tax information
     *
     * @return array
     */
    protected function tax() {
        $wc_tax     = new \WC_Tax();

        $tax_rates  = $wc_tax::get_rates( $this->product->get_tax_class() );
        $price      = $this->product->get_price();
        $taxes      = $wc_tax::calc_tax( $price, $tax_rates, false );
        $tax_total  = $wc_tax::get_tax_total( $taxes );

        return [
            "tax_rates"     => $tax_rates,
            "price"         => $price,
            "taxes"         => $taxes,
            "tax_total"     => $tax_total
        ];
    }

    /**
     * Get the PriceValidUntil Date
     *
     * @return String
     */
    protected function offers_priceValidUntil() {
        return $this->product->get_date_on_sale_to();
    }

    /**
     * Get priceValidFrom information for Offers method
     *
     * @return string
     */
    protected function offers_priceValidFrom() {
        return $this->product->get_date_on_sale_from();
    }

    /**
     * Get the information of Offers Seller
     *
     * @param array $seller
     * @return array
     */
    protected function offers_seller(  $seller ) {

        if ( isset( $seller['name'] ) && !empty( get_bloginfo('name') ) ) {
            $seller['name'] = get_bloginfo('name');
        }else {
            unset( $seller['name'] );
        }

        if ( isset( $seller['url'] ) && !empty( home_url() ) ) {
            $seller['url'] = home_url();
        }else {
            unset( $seller['url'] );
        }

        if ( isset( $seller['contactPoint'] ) && !empty( $this->offers_seller_ContactPoint( $seller['contactPoint'] ) ) ) {
            $seller['contactPoint']  = $this->offers_seller_ContactPoint( $seller['contactPoint'] );
        } else {
            unset( $seller['contactPoint'] );
        }

        if ( empty( $seller['name'] ) ) {
            return [];
        }

        return $seller;
    }

    protected function offers_itemCondition() {
        $itemCondition = get_post_meta($this->product->get_id(), 'item_condition', true);

        if ( !empty( $itemCondition ) ) {
            return $itemCondition;
        }

        return '';
    }

    /**
     * Get the information of Seller Contact Point
     *
     * @param array $contactPoint
     * @return array
     */
    protected function offers_seller_ContactPoint( $contactPoint ) {
        $contactPoint['telephone']          = "";
        $contactPoint['url']                = "";

        if ( empty( $contactPoint['telephone'] ) ) {
            return [];
        }
        return $contactPoint;
    }

    /**
     * Get The Product Category information
     *
     * @return array
     */
    protected function offers_category() {
        $categoryObj = wp_get_post_terms($this->product->get_id(), 'product_cat');

        $category = [
            "term_id"               => $categoryObj[0]->term_id,
            "name"                  => $categoryObj[0]->name,
            "slug"                  => $categoryObj[0]->slug,
            "term_group"            => $categoryObj[0]->term_group,
            "term_taxonomy_id"      => $categoryObj[0]->term_taxonomy_id,
            "taxonomy"              => $categoryObj[0]->taxonomy,
            "description"           => $categoryObj[0]->description,
            "parent"                => $categoryObj[0]->parent,
            "count"                 => $categoryObj[0]->count,
        ];

        if ( empty( $category['name'] ) ) {
            return [];
        }
        return $category;
    }

    /**
     * Get Offers mpn value
     *
     * @return string
     */
    protected function offers_mpn() { // TODO Operation not implemented
        return '';
    }

    protected function offers_gtin8() { // TODO Operation not implemented
        return '';
    }

    protected function offers_gtin13() { // TODO Operation not implemented
        return '';
    }

    protected function offers_gtin14() { // TODO Operation not implemented
        return '';
    }

    protected function offers_weight() {

        $weight = [
            "@type"         => "QuantitativeValue",
            "value"         => $this->product->get_weight(),
            "unitCode"      => get_option('woocommerce_weight_unit')
        ];

        if ( empty( $weight['value'] ) ) {
            return [];
        }

        return $weight;
    }

    protected function offers_depth()
    {

        $depth = [
            "@type"         => "QuantitativeValue",
            "value"         => $this->product->get_length(),
            "unitCode"      => get_option('woocommerce_dimension_unit')
        ];

        if ( empty( $depth['value'] ) ) {
            return [];
        }

        return apply_filters("schemax_{$this->schema_type}_offers_depth", $depth, $this->product);
    }

    protected function offers_width()
    {
        $width = [
            "@type"         => "QuantitativeValue",
            "value"         => $this->product->get_width(),
            "unitCode"      => get_option('woocommerce_dimension_unit')
        ];

        if ( empty( $width['value'] ) ) {
            return [];
        }

        return apply_filters("schemax_{$this->schema_type}_offers_width", $width, $this->product);
    }

    protected function offers_height() {
        $height = [
            "@type"         => "QuantitativeValue",
            "value"         => $this->product->get_height(),
            "unitCode"      => get_option('woocommerce_dimension_unit')
        ];

        if ( empty( $height['value'] ) ) {
            return [];
        }

        return apply_filters("schemax_{$this->schema_type}_offers_width", $height, $this->product);
    }

    protected function offers_shippingDetails( $shippingDetails ) { // TODO this method is incomplete for lacking of necessary information
        return [];
        $shipping_class_id  = $this->product->get_shipping_class_id();

        $shipping_zones     = \WC_Shipping_Zones::get_zones();

        foreach ($shipping_zones as $zone_id => $zone_data) {

            $zone               = new \WC_Shipping_Zone($zone_id);
            $shipping_methods   = $zone->get_shipping_methods(true );

           

//            $data = [
//                "@type"                 => "OfferShippingDetails",
//                "shippingRate"          => '',//$this->shippingDetails_shippingRate(),
//                "shippingDestination"   => $this->shippingDetails_shippingDestination($zone_data['zone_name']),
//                "deliveryTime"          => '',//$this->shippingDetails_deliveryTime(),
//                "taxShippingDetails"    => '', //$this->shippingDetails_taxShippingDetails()
//            ];
//            $shippingDetails[]  = $data;
        }

        return $shippingDetails;
    }

    protected function shippingDetails_shippingRate()
    {
        $shippingRate = [
            "@type"         => "MonetaryAmount",
            "currency"      => "",
            "value"         => ""
        ];

        return $shippingRate;
    }

    protected function shippingDetails_shippingDestination( $zone_location )
    {

        $shippingDestination = [
            "@type"             => "DefinedRegion",
            "addressCountry"    => $zone_location
        ];
        return $shippingDestination;
    }

    protected function shippingDetails_deliveryTime()
    {

        $deliveryTime = [
            "@type"             => "ShippingDeliveryTime",
            "handlingTime"      => $this->shippingDetails_deliveryTime_handlingTime(),
            "transitTime"       => $this->shippingDetails_deliveryTime_transitTime()
        ];

        return $deliveryTime;
    }

    protected function shippingDetails_taxShippingDetails()
    {
        $taxShippingDetails = [
            "@type"             => "OfferShippingDetails",
            "shippingRate"      => $this->shippingDetails_taxShippingDetails_shippingRate(),
        ];

        return $taxShippingDetails;
    }

    protected function shippingDetails_taxShippingDetails_shippingRate()
    {
        $shippingRate = [
            "@type"         => "MonetaryAmount",
            "currency"      => "",
            "value"         => ""
        ];
        return $shippingRate;
    }

    protected function shippingDetails_deliveryTime_handlingTime()
    {
        $handlingTime = [
            "@type"         => "QuantitativeValue",
            "minValue"      => "",
            "maxValue"      => "",
            "unitCode"      => "DAY"
        ];
        return $handlingTime;
    }

    protected function shippingDetails_deliveryTime_transitTime()
    {
        $transitTime = [
            "@type"             => "QuantitativeValue",
            "minValue"          => "",
            "maxValue"          => "",
            "unitCode"          => ""
        ];

        return $transitTime;
    }

    /**
     * Show the Schema in meta tag
     *
     * @return void
     */
    public function attach_schema(): void {
        $updated_data = $this->update_schema();
        echo "<script src='schemax-$this->schema_type' type='application/ld+json'>$updated_data</script>";
    }
}