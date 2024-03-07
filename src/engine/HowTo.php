<?php

namespace Schema\Engine;

use Schema\Inc\BaseEngine;

class HowTo extends BaseEngine {

    /**
     * @var mixed|null
     */
    private $post_id, $content;

    /**
     * __construct method
     *
     * @param $post_id
     */
    public function __construct( $post_id = null ) {
        $this->schema_file          = 'howTo.json';

        parent::__construct();

        $this->schema_type          = 'HowTo';
        $this->post_id              = $post_id;
        $this->content              = get_the_content( $this->post_id );
    }

    /**
     * Read schema data
     *
     * @return void
     */
    protected function update_schema() {
        $this->schema           = json_encode( $this->how_to( $this->schema_structure ) );
    }


    /**
     * Update the schema properties
     *
     * @param $howTo_arr
     * @return mixed|null
     */
    protected function how_to( $howTo_arr ) {

        /**
         * name property
         */
        $name                               = $this->name();
        if ( isset( $howTo_arr['name'] ) && ! empty( $name ) ) {
            $howTo_arr['name']              = $name;
        } else {
            unset( $howTo_arr['name'] );
        }

        /**
         * description property
         */
        $description                        = $this->description();
        if ( isset( $howTo_arr['description'] ) && !empty( $description ) ) {
            $howTo_arr['description']       = $description;
        } else {
            unset( $howTo_arr['description'] );
        }

        /**
         * image property
         */
        $image                              = $this->image( $howTo_arr['image'] );
        if ( isset( $howTo_arr['image'] ) && ! empty( $image ) ) {
            $howTo_arr['image']             = $image;
        } else {
            unset( $howTo_arr['image'] );
        }

        /**
         * estimatedCost property
         */
        $estimatedCost                      = $this->estimatedCost( $howTo_arr['estimatedCost'] );
        if ( isset( $howTo_arr['estimatedCost'] ) && ! empty( $estimatedCost ) ) {
            $howTo_arr['estimatedCost']     = $estimatedCost;
        } else {
            unset( $howTo_arr['estimatedCost'] );
        }

        /**
         * supply property
         */
        $supply                             = $this->supply( $howTo_arr['supply'] );
        if ( isset( $howTo_arr['supply'] ) && ! empty( $supply ) ) {
            $howTo_arr['supply']            = $supply;
        } else {
            unset( $howTo_arr['supply'] );
        }

        /**
         * tool property
         */
        $tool                               = $this->tool( $howTo_arr['tool'] );
        if ( isset( $howTo_arr['tool'] ) && ! empty( $tool ) ) {
            $howTo_arr['tool']              = $tool;
        } else {
            unset( $howTo_arr['tool'] );
        }

        /**
         * step property
         */
        $step                               = $this->step( $howTo_arr['step'] );
        if ( isset( $howTo_arr['step'] ) && ! empty( $step ) ) {
            $howTo_arr['step']              = $step;
        } else {
            unset( $howTo_arr['step'] );
        }

        /**
         * totalTime property
         */
        $totalTime                          = $this->totalTime();
        if ( isset( $howTo_arr['totalTime'] ) && ! empty( $totalTime ) ) {
            $howTo_arr['totalTime']         = $totalTime;
        } else {
            unset( $howTo_arr['totalTime'] );
        }

        return apply_filters("schemax_{$this->schema_type}_how_to", $howTo_arr );
    }

    /**
     * Get name
     *
     * @return mixed|null
     */
    protected function name() {

        $name = get_the_title( $this->post_id );

        if ( !empty( $name ) ) {
            return apply_filters("schemax_{$this->schema_type}_name", $name );
        }

        return null;
    }

    /**
     * Get description
     *
     * @return string|null
     */
    protected function description() {

        $post = get_post( $this->post_id );
        $description = strip_tags( $post->post_content );

        if ( ! empty( $description ) ) {
            return apply_filters( "schemax_{$this->schema_type}_description", $description );
        }

        return null;
    }

    /**
     * Get image
     *
     * @param $image
     * @return mixed|null
     */
    protected function image( $image ) {

        $image_arr = $this->get_images( $this->content );

        if ( isset( $image['url'] ) && ! empty( $image_arr ) ) {
            $image['url'] = $image_arr;
        } else {
            return null;
        }

        $height = null;
        if ( isset( $image['height'] ) && ! empty( $height ) ) {
            $image['height'] = $height;
        } else {
            unset( $image['height'] );
        }

        $width = null;
        if ( isset( $image['width'] ) && ! empty( $width ) ) {
            $image['width'] = $width;
        } else {
            unset( $image['width'] );
        }

        return apply_filters( "schemax_{$this->schema_type}_image", $image );
    }

    protected function estimatedCost( $estimatedCost ) {
        return null;
    }

    protected function supply( $supply ) {
        return null;
    }

    protected function tool( $tool ) {
        return null;
    }

    protected function step( $step ) {
//        error_log( print_r( $this->content, true ) );
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding( $this->content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

        $data = [];

        $xpath = new \DOMXPath( $dom );
        $query = "//li/a";
        $results = $xpath->query( $query );

        if ( empty( $results ) ) {
            return null;
        }

        foreach ( $results as $result ) {

            /**
             * url property
             */
            $url                            = $result->getAttribute('href');
            if ( isset( $step['url'] ) && ! empty( $url ) ) {
                $step['url']                = $url;
            } else {
                unset( $step['url'] );
            }

            /**
             * name property
             */
            $name                           = $result->nodeValue;
            if ( isset( $step['name'] ) && ! empty( $name ) ) {
                $step['name']               = $name;
            } else {
                unset( $step['name'] );
            }

            /**
             * itemListElement property
             */
            $itemListElement                = null;
            if ( isset( $step['itemListElement'] ) && ! empty( $itemListElement ) ) {
                $step['itemListElement']    = $itemListElement;
            } else {
                unset( $step['itemListElement'] );
            }

            /**
             * image property
             */
            $image                          = null;
            if ( isset( $step['image'] ) && ! empty( $image ) ) {
                $step['image']              = $image;
            } else {
                unset( $step['image'] );
            }

            $data = $step;
        }

        return apply_filters( "schemax_{$this->schema_type}_step", $data );
    }

    protected function totalTime() {
        return null;
    }
}