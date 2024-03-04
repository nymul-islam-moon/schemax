<?php

namespace Schema\Engine;

use Schema\Inc\BaseEngine;


class Website extends BaseEngine {

    /**
     * Website schema constructor
     *
     * @param $post_id
     */
    public function __construct( $post_id = null ) {


        $this->schema_file      = 'webSite.json';

        parent::__construct();

        $this->schema_type      = 'Website';
        $this->post_id          = $post_id;
    }

    protected function update_schema() {
        $this->schema           = json_encode( $this->single_site( $this->schema_structure ) );
    }

    protected function single_site( $website_arr ) {

        /**
         * WebSite schema name key
         */
        if ( isset( $website_arr['name'] ) ) {
            $name                       = $this->name();
            if ( ! empty( $name ) ) {
                $website_arr['name']    = $name;
            } else {
                unset($website_arr['name']);
            }
        }

        /**
         * WebSite schema url key
         */
        if ( isset( $website_arr['url'] ) ) {
            $url = $this->url();
            if (!empty($url)) {
                $website_arr['url'] = $url;
            } else {
                unset($website_arr['url']);
            }
        }

        /**
         * WebSite schema headline key
         */
        if ( isset( $website_arr['headline'] ) ) {
            $headline = $this->headline();
            if (!empty($headline)) {
                $website_arr['headline'] = $headline;
            } else {
                unset($website_arr['headline']);
            }
        }

        /**
         * WebSite schema description key
         */
        if ( isset( $website_arr['description'] ) ) {
            $description = $this->description();
            if (!empty($description)) {
                $website_arr['description'] = $description;
            } else {
                unset($website_arr['description']);
            }
        }

        /**
         * WebSite schema image key
         */
        if ( isset( $website_arr['image'] ) ) {
            $image = $this->image();
            if (!empty($image)) {
                $website_arr['image'] = $image;
            } else {
                unset($website_arr['image']);
            }
        }

        /**
         * WebSite schema inLanguage key
         */
        if ( isset( $website_arr['inLanguage'] ) ) {
            $inLanguage = $this->inLanguage();
            if (!empty($inLanguage)) {
                $website_arr['inLanguage'] = $inLanguage;
            } else {
                unset($website_arr['inLanguage']);
            }
        }

        /**
         * WebSite schema author key
         */
        if ( isset( $website_arr['author'] ) ) {
            $author = $this->author( $website_arr['author'] );
            if ( ! empty( $author ) ) {
                $website_arr['author'] = $author;
            } else {
                unset( $website_arr['author'] );
            }
        }

        /**
         * WebSite schema publisher key
         */
        if ( isset( $website_arr['publisher'] ) ) {
            $publisher = $this->publisher( $website_arr['publisher'] );
            if (!empty($publisher)) {
                $website_arr['publisher'] = $publisher;
            } else {
                unset($website_arr['publisher']);
            }
        }

        /**
         * WebSite schema mainEntity key
         */
        if ( isset( $website_arr['mainEntity'] ) ) {
            $mainEntity = $this->mainEntity( $website_arr['mainEntity'] );
            if (!empty($mainEntity)) {
                $website_arr['mainEntity'] = $mainEntity;
            } else {
                unset($website_arr['mainEntity']);
            }
        }

        /**
         * WebSite schema potentialAction key
         */
        if (isset($website_arr['potentialAction'])) {
            $potentialAction = $this->potentialAction( $website_arr['potentialAction'] );
            if (!empty($potentialAction)) {
                $website_arr['potentialAction'] = $potentialAction;
            } else {
                unset($website_arr['potentialAction']);
            }
        }

        /**
         * WebSite schema keywords key
         */
        if (isset($website_arr['keywords'])) {
            $keywords = $this->keywords();
            if (!empty($keywords)) {
                $website_arr['keywords'] = $keywords;
            } else {
                unset($website_arr['keywords']);
            }
        }

        /**
         * WebSite schema hasPart key
         */
        if (isset($website_arr['hasPart'])) {
            $hasPart = $this->hasPart();
            if (!empty($hasPart)) {
                $website_arr['hasPart'] = $hasPart;
            } else {
                unset($website_arr['hasPart']);
            }
        }

        return $website_arr;
    }

    /**
     * Get Name
     *
     * @return mixed|void|null
     */
    protected function name() {
        $name = get_bloginfo('name');

        if ( ! empty( $name ) ) {
            return apply_filters("schemax_{this->schema_type}_name", $name);
        }

        return null;
    }

    /**
     * Get Url
     *
     * @return mixed|void|null
     */
    protected function url() {
        $url = home_url();

        if (!empty($url)) {
            return apply_filters("schemax_{this->schema_type}_url", $url);
        }

        return null;
    }

    /**
     * Get Headline
     *
     * @return mixed|void|null
     */
    protected function headline() {
        $headline = get_the_title($this->post_id);

        if (!empty($headline)) {
            return apply_filters("schemax_{this->schema_type}_headline", $headline);
        }

        return null;
    }

    /**
     * Get Description
     *
     * @return mixed|void|null
     */
    protected function description() {
        $description = get_post_field( 'post_content', $this->post_id );

        if (!empty($description)) {
            return apply_filters("schemax_{this->schema_type}_description", $description);
        }

        return null;
    }

    /**
     * Get Image
     *
     * @return mixed|void|null
     */
    protected function image() {
//        error_log( print_r( wc_get_product( $this->post_id->get_image_url() ), true ) );
        $image = null;

        if ( ! empty( $image ) ) {
            return apply_filters("schemax_{this->schema_type}_image", $image);
        }

        return null;
    }

    /**
     * Get InLanguage
     *
     * @return mixed|void|null
     */
    protected function inLanguage() {
        $inLanguage = get_locale();

        if (!empty($inLanguage)) {
            return apply_filters("schemax_{this->schema_type}_inLanguage", $inLanguage);
        }

        return null;
    }

    /**
     * Get Author
     *
     * @return mixed|void|null
     */
    protected function author( $author ) {
        $author_id = get_post_field('post_author', $this->post_id );

        $author_info = get_userdata($author_id);

        $author['name'] = $author_info->display_name;

        if ( ! empty( $author['name'] ) ) {
            return apply_filters("schemax_{this->schema_type}_author", $author);
        }

        return null;
    }

    /**
     * Get Publisher
     *
     * @return mixed|void|null
     */
    protected function publisher( $publisher ) {

        $name = get_bloginfo('name');
        if ( ! empty( $publisher['name'] ) && ! empty( $name ) ) {
            $publisher['name']  = $name;
        } else {
            unset( $publisher['name'] );
        }

        $logo = null;
        if ( isset( $publisher['logo'] ) && ! empty( $logo ) ) {
            $publisher['logo'] = $logo;
        } else {
            unset( $publisher['logo'] );
        }

        return apply_filters("schemax_{this->schema_type}_publisher", $publisher);

    }

    /**
     * Get MainEntity
     *
     * @return mixed|void|null
     */
    protected function mainEntity( $mainEntity ) {

        $name   = get_the_title( $this->post_id );
        if ( isset( $mainEntity['name'] ) && ! empty( $name ) ) {
            $mainEntity['name']         = $name;
        } else {
            return null;
        }

        $image = $this->mainEntity_image();
        if ( isset( $mainEntity['image'] ) && ! empty( $image ) ) {
            $mainEntity['image'] = $image;
        } else {
            unset( $mainEntity['image'] );
        }

        $priceRange = null;
        if ( isset( $mainEntity['priceRange'] ) && ! empty( $priceRange ) ) {
            $mainEntity['priceRange'] = $priceRange;
        } else {
            unset( $mainEntity['priceRange'] );
        }

        $telephone = null;
        if ( isset( $mainEntity['telephone'] ) && ! empty( $telephone ) ) {
            $mainEntity['telephone'] = $telephone;
        } else {
            unset( $mainEntity['telephone'] );
        }

        $address = [];
        if ( isset( $mainEntity['address'] ) && ! empty( $address ) ) {
            $mainEntity['address'] = $address;
        } else {
            unset( $mainEntity['address'] );
        }

        $aggregateRating = null;
        if ( isset( $mainEntity['aggregateRating'] ) && ! empty( $aggregateRating ) ) {
            $mainEntity['aggregateRating'] = $aggregateRating;
        } else {
            unset( $mainEntity['aggregateRating'] );
        }

        return apply_filters("schemax_{this->schema_type}_mainEntity", $mainEntity);
    }

    protected function mainEntity_image() {

        return null;
    }

    /**
     * Get PotentialAction
     *
     * @return mixed|void|null
     */
    protected function potentialAction( $potentialAction ) {
        $potentialAction = null;

        if (!empty($potentialAction)) {
            return apply_filters("schemax_{this->schema_type}_potentialAction", $potentialAction);
        }

        return null;
    }

    /**
     * Get Keywords
     *
     * @return mixed|void|null
     */
    protected function keywords() {
        $keywords = [];

        if (!empty($keywords)) {
            return apply_filters("schemax_{this->schema_type}_keywords", $keywords);
        }

        return null;
    }

    /**
     * Get HasPart
     *
     * @return mixed|void|null
     */
    protected function hasPart() {
        $hasPart = null;

        if (!empty($hasPart)) {
            return apply_filters("schemax_{this->schema_type}_hasPart", $hasPart);
        }

        return null;
    }
}