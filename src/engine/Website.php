<?php

namespace Schema\Website;

use Schema\Inc\BaseEngine;


class Website extends BaseEngine {

    public function __construct() {
        parent::__construct();
    }

    protected function update_schema() {
        $schema_arr                     = $this->schema_service->read_schema( $this->schema_name );


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
}