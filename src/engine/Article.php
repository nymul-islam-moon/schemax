<?php

namespace Schema\Engine;

use Schema\Inc\Service;

class Article {

    private $schema_name, $schema_type, $schema_service, $post_id;

    public function __construct( $post_id = null ) {
        $this->schema_name      = 'article.json';
        $this->schema_type      = 'article';
        $this->post_id          = $post_id;

        // creating new object of the service schema
        $this->schema_service   = new Service();
    }

    /**
     * Update the Article schema with real time data
     *
     * @return mixed|null
     */
    protected function update_schema() {
        $this->schema_structure             = $this->schema_service->read_schema( $this->schema_name );

        if ( is_single() ) {
            $updated_schema_data            = json_encode( $this->single_article( $this->schema_structure, 'NewsArticle' ) );
        }

        return apply_filters( "schemax_{$this->schema_type}_update_schema", $updated_schema_data );
    }

    /**
     * Update the single article data
     *
     * @param $article_arr
     * @param $type
     * @return mixed
     */
    protected function single_article( $article_arr, $type ) {

        if ( isset( $article_arr['@type'] ) ) {
            $article_arr['@type'] = $type;
        }else {
            unset( $article_arr['@type'] );
        }

        /**
         * article schema mainEntityOfPage key
         */
        $mainEntityOfPage = $this->mainEntityOfPage( $article_arr['mainEntityOfPage'] );
        if ( isset( $article_arr['mainEntityOfPage'] ) && ! empty( $mainEntityOfPage ) ) {
            $article_arr['mainEntityOfPage'] = $mainEntityOfPage;
        } else {
            unset( $article_arr['mainEntityOfPage'] );
        }

        /**
         * article schema headline key
         */
        $headline = get_the_title( $this->post_id );
        if ( isset( $article_arr['headline'] ) && ! empty( $headline ) ) {
            $article_arr['headline']        = $headline;
        }else {
            unset( $article_arr['headline'] );
        }

        /**
         * article schema description key
         */
        $description = get_the_excerpt( $this->post_id );
        if( isset( $article_arr['description'] ) && ! empty( $description ) ) {
            $article_arr['description']     = $description;
        }

        /**
         * article schema image key
         */
        $images = $this->image();
        if ( isset( $article_arr['image'] ) && ! empty( $images ) ) {
            $article_arr['image']          = $images;
        }else {
            unset( $article_arr['image'] );
        }

        /**
         * article schema datePublished key
         */
        $datePublished = $this->datePublished();
        if ( isset( $article_arr['datePublished'] ) && ! empty( $datePublished ) ) {
            $article_arr['datePublished']   = $datePublished;
        } else {
            unset( $datePublished );
        }

        $dateModified = $this->dateModified();
        if ( isset(  $article_arr['dateModified'] ) && !empty( $dateModified ) ) {
            $article_arr['dateModified']    = $dateModified;
        } else {
            unset( $article_arr['dateModified'] );
        }

        return apply_filters("schemax_{$this->schema_type}_single_article", $article_arr );
    }

    /**
     * Get the mainEntityOfPage array data
     *
     * @param $mainEntityOfPage
     * @return array
     */
    protected function mainEntityOfPage( $mainEntityOfPage ) {

        if ( isset( $mainEntityOfPage['@id'] ) ) {
            $mainEntityOfPage['@id']       = get_permalink($this->post_id);
        }else {
            unset( $mainEntityOfPage['@id'] );
        }

        if ( ! empty( $mainEntityOfPage['@id'] ) ) {
            return apply_filters("schemax_{$this->schema_type}_mainEntityOfPage", $mainEntityOfPage );
        }

        return [];
    }

    /**
     * Get images
     *
     * @return array
     */
    protected function image() {
        $images = get_attached_media( 'image', $this->post_id );

        $images_arr = [];

        foreach ($images as $image) {

            $images_arr[] = wp_get_attachment_image($image->ID, 'full');
        }

        if ( ! empty( $images_arr ) ) {
            return $images_arr;
        }

        return [];
    }

    /**
     * Get datePublished
     *
     * @return int|string|null
     */
    protected function datePublished() {

        $datePublished = get_the_date('F j, Y', $this->post_id);

        if ( ! empty( $datePublished ) ) {
            return apply_filters("schemax_{$this->schema_type}_datePublished", $datePublished );
        }

        return null;
    }

    /**
     * Get $dateModified
     *
     * @return mixed|null
     */
    protected function dateModified() {

        $dateModified = get_the_modified_date('F j, Y', $this->post_id);

        if ( ! empty( $dateModified ) ) {
            return apply_filters("schemax_{$this->schema_type}_dateModified", $dateModified );
        }

        return null;
    }


    /**
     * Show the Schema in meta tag
     *
     * @return void
     */
    public function attach_schema() {
        $updated_data = $this->update_schema();
        echo "<script src='schemax-$this->schema_type' type='application/ld+json'>$updated_data</script>";
    }
}