<?php

namespace Schema\Engine;
use Schema\Engine\Service;

class Product extends BaseEngine {

    public $product, $schema_name, $schema_service;

    public function __construct( $product_id = null ) {
        global $product;
        if( $product instanceof \WC_Product ) {
            $this->product = $product;
        }else{
            $this->product = wc_get_product( $product_id );
        }

//        error_log( print_r( wp_get_post_terms($this->product->get_id(), 'product_cat'), true ) );
//        error_log( print_r( $this->product->get_weight(), true ) );
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
        $schema_arr['brand']            = $this->brand();
        $schema_arr['image']            = $this->image();
        $schema_arr['offers']           = $this->offers();
        $updated_schema_data            = json_encode( $schema_arr );

        return $updated_schema_data;
    }

    public function name() {
        return $this->product ? $this->product->get_name() : '';
    }

    public function description() {
        return $this->product ? $this->product->get_description() : '';
    }

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
            "@type"         => "AggregateRating",
            "ratingValue"   => $this->product->get_average_rating(),
            "reviewCount"   => $review_count
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
            "priceSpecification"    => $this->priceSpecification(),
            "priceValidUntil"       => $this->priceSpecification(),
            "priceValidFrom"        => $this->priceValidFrom(),
            "availability"          => $this->product->get_stock_status() ? $this->product->get_stock_status() : '',
            "quantity"              => 'work_in_progress',
            "url"                   => $this->product->get_permalink() ? $this->product->get_permalink() : '',
            "seller"                => $this->saller(),
            "itemCondition"         => '',
            "category"              => $this->category()['name'],
            "mpn"                   => '',
            "gtin8"                 => "",
            "gtin13"                => "",
            "gtin14"                => "",
            "weight"                => $this->product->has_weight() ? $this->weight() : '',
            "depth"                 => $this->depth(),
            "width"                 => isset( $this->width()['value'] ) ? $this->width() : '',
            "height"                => isset( $this->height()['value'] ) ? $this->height() : '',
            "shippingDetails"       => $this->shippingDetails()
        ];

        return $offers;
    }

    public function priceSpecification() {

        $priceSpecification = [
            "@type"                 => "PriceSpecification",
            "price"                 => $this->product->get_sale_price() ? $this->product->get_sale_price() : '',
            "valueAddedTaxIncluded" => $this->product->get_tax_status() ? $this->product->get_tax_status() : '',
            "taxPercentage"         => $this->tax()['tax_rates'][1]['rate'],
            "taxFixedAmount"        => ( $this->tax()['tax_rates'][1]['rate'] / 100 ) * $this->tax()['price']
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

    public function priceValidUntil() {
        return '';
    }

    public function priceValidFrom() {
        return '';
    }

    public function saller() {
        $saller = [
            "@type"         => 'Organization',
            "name"          => '',
            "url"           => '',
            "contactPoint"  => $this->ContactPoint(),
        ];
        return $saller;
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

    public function category() {

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

    public function weight() {

        $weight = [
            "@type"     => "QuantitativeValue",
            "value"     => $this->product->get_weight(),
            "unitCode"  => get_option('woocommerce_weight_unit')
        ];

        return $weight;
    }

    public function depth() {

        $depth = [
            "@type"     => "QuantitativeValue",
            "value"     => "",
            "unitCode"  => ""
        ];

        return $depth;
    }

    public function width() {
        $width = [
            "@type" => "QuantitativeValue",
            "value" => $this->product->get_width(),
            "unitCode" => get_option('woocommerce_dimension_unit')
        ];

        return $width;
    }

    public function height() {
        $height = [
            "@type" => "QuantitativeValue",
            "value" => $this->product->get_height(),
            "unitCode" => get_option('woocommerce_dimension_unit')
        ];

        return $height;
    }

    public function shippingDetails() {
//        error_log( print_r( \WC()->shipping->get_shipping_methods(), true ) );
        // if shipping Details array found just put the data array  and assigned part info the foreach loop
        $data = [
            "@type"         => "OfferShippingDetails",
            "shippingRate"  => $this->shippingDetails_shippingRate(),
            "shippingDestination" => $this->shippingDetails_shippingDestination(),
            "deliveryTime"      => $this->shippingDetails_deliveryTime(),
            "taxShippingDetails"    => $this->shippingDetails_taxShippingDetails()
        ];

        $shippingDetails[] = $data;

        return $shippingDetails;
    }

    public function shippingDetails_shippingRate() {
        $shippingRate = [
            "@type" => "MonetaryAmount",
            "currency" => "",
            "value" => ""
        ];

        return $shippingRate;
    }

    public function shippingDetails_shippingDestination() {
        $shippingDestination = [
            "@type" => "DefinedRegion",
            "addressCountry" => ""
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
            "@type" => "OfferShippingDetails",
            "shippingRate" => $this->shippingDetails_taxShippingDetails_shippingRate(),
        ];

        return $taxShippingDetails;
    }

    public function shippingDetails_taxShippingDetails_shippingRate() {
        $shippingRate = [
            "@type" => "MonetaryAmount",
            "currency" => "",
            "value" => ""
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
            "@type" => "QuantitativeValue",
            "minValue" => "",
            "maxValue" => "",
            "unitCode" => ""
        ];

        return $transitTime;
    }

    public function attach_schema() {
        $updated_data = $this->update_schema();
        echo "<script src='schemax' type='application/ld+json'>$updated_data</script>";
    }
}