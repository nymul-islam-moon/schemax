<?php

namespace Schema\Engine;

use Schema\Inc\Service;

class Website {

    private $schema_type, $schema_name, $schema_service, $product;

    public function __construct() {

        global $product;

        $this->schema_service   = new Service();
        $this->schema_name      = 'webSite.json';
        $this->schema_type      = 'website';

//        error_log( print_r( get_bloginfo(), true ) );

    }

    protected function update_schema() {
        $schema_arr                     = $this->schema_service->read_schema( $this->schema_name );

        $schema_arr['@id']              = home_url( add_query_arg( null, null ) );
        $schema_arr['name']             = 'working progress';
        $schema_arr['url']              = home_url( add_query_arg( null, null ) );
        $schema_arr['headline']         = 'working progress';
        $schema_arr['description']      = 'working progress';
        $schema_arr['image']            = 'working progress';
        $schema_arr['inLanguage']       = 'working progress';
        $schema_arr['author']           = ! empty( $this->author()['name'] ) ? $this->author() : '' ;
        $schema_arr['publisher']        = ! empty( $this->publisher()['name'] ) ? $this->publisher() : '';
        $schema_arr['mainEntity']       = ! empty( $this->mainEntity()['name'] ) ? $this->mainEntity() : '';
        $schema_arr['potentialAction']  = ! empty( $this->potentialAction()['target'] ) ? $this->potentialAction() : '';
        $schema_arr['keywords']         = ! empty( $this->keywords() ) ? $this->keywords() : '';
        $schema_arr['hasPart']          = ! empty( $this->hasPart()[0]['headline'] ) ? $this->hasPart() : '';

        $updated_schema_data            = json_encode( $schema_arr );
        return apply_filters( "schemax_{$this->schema_type}_offers_width", $updated_schema_data );
    }

    /**
     * Website Name
     *
     * @return void
     */
    protected function name() {
        return 'website';
    }

    protected function author() {

        $author = [
            "@type"             => "Person",
            "name"              => "",
        ];

        return $author;
    }

    protected function publisher() {
        $publisher = [
            "@type"             => "Organization",
            "name"              => "",
            "logo"              => [
                "@type"                 => "ImageObject",
                "url"                   => "",
                "height"                => "",
                "width"                 => ""
            ]
        ];
        return $publisher;
    }

    protected function mainEntity() {
        $mainEntity = [
            "@type"             => "LocalBusiness",
            "name"              => "",
            "image"             => "",
            "priceRange"        => "",
            "telephone"         => "",
            "address"           => [
                "@type"                 => "PostalAddress",
                "streetAddress"         => "",
                "addressLocality"       => "",
                "addressRegion"         => "",
                "addressCountry"        => "",
                "postalCode"            => ""
            ],
            "aggregateRating"   => [
                "@type"                 => "AggregateRating",
                "ratingValue"           => "",
                "reviewCount"           => ""
            ]
        ];
    }

    protected function potentialAction() {
        $potentialAction = [
            "@type"             => "SearchAction",
            "target"            => "",
            "query-input"       => ""
        ];

        return $potentialAction;
    }

    protected function keywords() {
        $keywords = [

        ];

        return $keywords;
    }

    protected function hasPart() {

        $hasPart = [
            [
                "@type"     => "WebPageElement",
                "@id"       => "#header",
                "headline"  => "",
                "potentialAction"   => [
                    "@type"                 => "Action",
                    "name"                  => "Contact",
                    "target"                => "http://google.ca/"
                ]
            ]
        ];

        return $hasPart;
    }

    public function attach_schema() {
        $updated_data = $this->update_schema();
        echo "<script src='schemax' type='application/ld+json' schema_type='website'>$updated_data</script>";
    }

}