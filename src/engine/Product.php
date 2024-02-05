<?php

namespace Schema\Engine;
use Schema\Engine\Service;

class Product {

    public $product, $schema_name, $schema_service;

    public function __construct( $product_id = null ) {
        global $product;
        if( $product instanceof \WC_Product ) {
            $this->product = $product;
        }else{
            $this->product = wc_get_product( $product_id );
        }

//        error_log( print_r( wp_get_post_terms($this->product->get_id(), 'product_cat'), true ) );
//        error_log( print_r( \WC()->cart->get_shipping_packages(), true ) );

//        error_log( print_r( \WC_Data_Store::load( 'shipping-zone' )->WC_Coupon_Data_Store_CPT(), true ) );
//        error_log( print_r( $this->product, true ) );
        $this->schema_service = new Service();
        $this->schema_name = 'product.json';
    }

    public function update_schema() {
        $schema_arr                     = $this->schema_service->read_schema( $this->schema_name );
        $schema_arr['name']             = $this->name();
        $schema_arr['description']      = $this->description();
        $schema_arr['review']           = $this->review();
        $schema_arr['aggregateRating']  = $this->aggregateRating();
        $schema_arr['image']            = $this->image();
        $schema_arr['brand']            = $this->brand();
        $schema_arr['offers']           = $this->offers();

        $updated_schema_data            = json_encode( $schema_arr );
        return $updated_schema_data;
    }

    /**
     * Product Name
     *
     * @return string
     */
    public function name() {
        return $this->product ? $this->product->get_name() : '';
    }

    /**
     * Product Description
     *
     * @return string
     */
    public function description() {
        return $this->product ? $this->product->get_description() : '';
    }


    /**
     * Return the review array
     *
     * @return array
     */
    public function review() {

        $args = array(
            'post_id'   => $this->product ? $this->product->get_id() : '',
            'status'    => 'approve'
        );

        $review_arr = get_comments( $args );
        $review_data[] = array();
        foreach ( $review_arr as $key => $review ) {
            $singleReviewData = [
                '@type'             => 'Review',
                'reviewRating'      => [
                    '@type'         => 'Rating',
                    'ratingValue'   => get_comment_meta( $review->comment_ID, 'rating', true ),
                    'bestRating'    => 5
                ],
                'author'    => [
                    '@type' => 'Person',
                    'name'  => $review->comment_author ?? ''
                ],
                'comment'   => $review->comment_content ?? ''
            ];
            $review_data[ $key ] = $singleReviewData;
        }

        return $review_data;
    }

    public function aggregateRating() {

        $args = array(
            'post_id' => $this->product ? $this->product->get_id() : '',
            'count'   => true
        );
        $review_count       = get_comments( $args );

        $aggregate_rating   = [
            "@type"             => "AggregateRating",
            "ratingValue"       => $this->product->get_average_rating(),
            "reviewCount"       => $review_count
        ];

        return $aggregate_rating;
    }

    public function image() {
        if ( $this->product ) {
            $image_id = $this->product->get_image_id();
            return wp_get_attachment_image_url( $image_id, 'full' );
        }
        return '';
    }

    public function brand() {
        $brand = [
            "@type" => "Thing",
            "name"  => ""
        ];
        return $brand;
    }

    public function offers() {

        $offers = [
            "@type"                 => "Offer",
            "price"                 => $this->product->get_regular_price() ? $this->product->get_regular_price() : '',
            "priceCurrency"         => get_woocommerce_currency() ? get_woocommerce_currency() : '' ,
            "priceSpecification"    => $this->offers_priceSpecification(),
            "priceValidUntil"       => $this->offers_priceValidUntil(),
            "priceValidFrom"        => $this->offers_priceValidFrom(),
            "availability"          => $this->product->get_stock_status() ? $this->product->get_stock_status() : '',
            "quantity"              => $this->product->get_stock_quantity() != null ? $this->product->get_stock_quantity() : '',
            "url"                   => $this->product->get_permalink() ? $this->product->get_permalink() : '',
            "seller"                => $this->offers_saller(),
            "itemCondition"         => $this->offers_itemCondition(),
            "category"              => ! empty( $this->offers_category()['name'] ) ? $this->offers_category()['name'] : '',
            "mpn"                   => "working in progress",
            "gtin8"                 => "working in progress",
            "gtin13"                => "working in progress",
            "gtin14"                => "working in progress",
            "weight"                => $this->product->has_weight() ? $this->offers_weight() : '',
            "depth"                 => ! empty( $this->offers_depth()['value'] ) ? $this->offers_depth() : '',
            "width"                 => ! empty( $this->offers_width()['value'] ) ? $this->offers_width() : '',
            "height"                => ! empty( $this->offers_height()['value'] ) ? $this->offers_height() : '',
            "shippingDetails"       => $this->offers_shippingDetails()
        ];

        return $offers;
    }

    public function offers_priceSpecification() {

        $priceSpecification = [
            "@type"                 => "PriceSpecification",
            "price"                 => $this->product->get_sale_price() ? $this->product->get_sale_price() : '',
            "valueAddedTaxIncluded" => $this->product->get_tax_status() ? $this->product->get_tax_status() : '',
            "taxPercentage"         => ! empty( $this->tax()['tax_rates'][1]['rate'] ) ? $this->tax()['tax_rates'][1]['rate'] : '',
            "taxFixedAmount"        => ! empty( $this->tax()['tax_rates'][1]['rate'] ) ? ( $this->tax()['tax_rates'][1]['rate'] / 100 ) * $this->tax()['price'] : ''
        ];
        return $priceSpecification;
    }

    public function tax() {
        $wc_tax     = new \WC_Tax();

        $tax_rates  = $wc_tax::get_rates( $this->product->get_tax_class() );
        $price      = $this->product->get_price();
        $taxes      = $wc_tax::calc_tax( $price, $tax_rates, false );
        $tax_total  = $wc_tax::get_tax_total( $taxes );

        $tax = [
            "tax_rates" => $tax_rates,
            "price"     => $price,
            "taxes"     => $taxes,
            "tax_total" => $tax_total
        ];

        return $tax;
    }

    public function offers_priceValidUntil() {
        return 'working in progress';
    }

    public function offers_priceValidFrom() {
        return 'working in progress';
    }

    public function offers_saller() {
        $saller = [
            "@type"         => 'Organization',
            "name"          => '',
            "url"           => '',
            "contactPoint"  => $this->ContactPoint(),
        ];
        return $saller;
    }

    public function offers_itemCondition() {
        $itemCondition = get_post_meta( $this->product->get_id(), 'item_condition', true );

        if ( ! empty( $itemCondition ) ) {
            return $itemCondition;
        }

        return '';
    }

    /**
     * Saller ContactPoint information
     *
     * @return string[]
     */
    public function ContactPoint() {
        $contactPoint = [
            "@type"             => "ContactPoint",
            "contactType"       => "",
            "telephone"         => "",
            "availableLanguage" => "",
            "url"               => ""
        ];
        return $contactPoint;
    }

    public function offers_category() {

        $categoryObj = wp_get_post_terms($this->product->get_id(), 'product_cat');

        $category = [
            "term_id"           => $categoryObj[0]->term_id,
            "name"              => $categoryObj[0]->name,
            "slug"              => $categoryObj[0]->slug,
            "term_group"        => $categoryObj[0]->term_group,
            "term_taxonomy_id"  => $categoryObj[0]->term_taxonomy_id,
            "taxonomy"          => $categoryObj[0]->taxonomy,
            "description"       => $categoryObj[0]->description,
            "parent"            => $categoryObj[0]->parent,
            "count"             => $categoryObj[0]->count,
        ];

        return $category;
    }

    public function offers_weight() {

        $weight = [
            "@type"     => "QuantitativeValue",
            "value"     => $this->product->get_weight(),
            "unitCode"  => get_option('woocommerce_weight_unit')
        ];

        return $weight;
    }

    public function offers_depth() {

        $depth = [
            "@type"     => "QuantitativeValue",
            "value"     => $this->product->get_length(),
            "unitCode"  => get_option('woocommerce_dimension_unit')
        ];

        return $depth;
    }

    public function offers_width() {
        $width = [
            "@type"     => "QuantitativeValue",
            "value"     => $this->product->get_width(),
            "unitCode"  => get_option('woocommerce_dimension_unit')
        ];

        return $width;
    }

    public function offers_height() {
        $height = [
            "@type"     => "QuantitativeValue",
            "value"     => $this->product->get_height(),
            "unitCode"  => get_option('woocommerce_dimension_unit')
        ];

        return $height;
    }

    public function offers_shippingDetails() {

        $shippingDetails[]  = array();

        $data_store         = \WC_Data_Store::load( 'shipping-zone' );
        $raw_zones          = $data_store->get_zones();
        foreach ( $raw_zones as $raw_zone ) {
            $zones[] = new \WC_Shipping_Zone( $raw_zone );
        }
        $zones[] = new \WC_Shipping_Zone( 0 ); // ADD ZONE "0" MANUALLY

        foreach ( $zones as $key => $zone ) {
            $data = [
                    "@type"                 => "OfferShippingDetails",
                    "shippingRate"          => $this->shippingDetails_shippingRate(),
                    "shippingDestination"   => $this->shippingDetails_shippingDestination( $zone->get_zone_name() ),
                    "deliveryTime"          => $this->shippingDetails_deliveryTime(),
                    "taxShippingDetails"    => $this->shippingDetails_taxShippingDetails()
            ];
            $shippingDetails[ $key ] = $data;
        }



        return $shippingDetails;
    }

    public function shippingDetails_shippingRate() {
        $shippingRate = [
            "@type"     => "MonetaryAmount",
            "currency"  => "",
            "value"     => ""
        ];

        return $shippingRate;
    }

    public function shippingDetails_shippingDestination( $zone_location ) {

        $shippingDestination = [
            "@type"             => "DefinedRegion",
            "addressCountry"    => $zone_location
        ];

        return $shippingDestination;
    }

    public function shippingDetails_deliveryTime() {

        $deliveryTime = [
            "@type"         => "ShippingDeliveryTime",
            "handlingTime"  => $this->shippingDetails_deliveryTime_handlingTime(),
            "transitTime"   => $this->shippingDetails_deliveryTime_transitTime()
        ];

        return $deliveryTime;
    }

    public function shippingDetails_taxShippingDetails() {
        $taxShippingDetails = [
            "@type"         => "OfferShippingDetails",
            "shippingRate"  => $this->shippingDetails_taxShippingDetails_shippingRate(),
        ];

        return $taxShippingDetails;
    }

    public function shippingDetails_taxShippingDetails_shippingRate() {
        $shippingRate = [
            "@type"     => "MonetaryAmount",
            "currency"  => "",
            "value"     => ""
        ];
        return $shippingRate;
    }

    public function shippingDetails_deliveryTime_handlingTime() {
        $handlingTime = [
            "@type"     => "QuantitativeValue",
            "minValue"  => "",
            "maxValue"  => "",
            "unitCode"  => "DAY"
        ];
        return $handlingTime;
    }

    public function shippingDetails_deliveryTime_transitTime() {
        $transitTime = [
            "@type"     => "QuantitativeValue",
            "minValue"  => "",
            "maxValue"  => "",
            "unitCode"  => ""
        ];

        return $transitTime;
    }

    public function attach_schema() {
        $updated_data = $this->update_schema();
        echo "<script src='schemax' type='application/ld+json'>$updated_data</script>";
    }
}