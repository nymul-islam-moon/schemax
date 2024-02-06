<?php

namespace Schema\Engine;

use Schema\Inc\Service;

class Website {

    private $schema_type, $schema_name, $schema_service;

    public function __construct() {
        $this->schema_service   = new Service();
        $this->schema_name      = 'webSite.json';
        $this->schema_type      = 'website';
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
        $schema_arr['publisher']        = $this->publisher();
        $schema_arr['mainEntity']       = $this->mainEntity();
        $schema_arr['potentialAction']  = $this->potentialAction();
        $schema_arr['keywords']         = $this->keywords(());
        $schema_arr['hasPart']          = 'working progress';

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
            "@type"             => "",
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

    public function attach_schema() {
        $updated_data = $this->update_schema();
        echo "<script src='schemax' type='application/ld+json' schema_type='website'>$updated_data</script>";
    }

}