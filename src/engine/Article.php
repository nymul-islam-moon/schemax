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

        /**
         * article schema type key
         */
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
            $article_arr['mainEntityOfPage']    = $mainEntityOfPage;
        } else {
            unset( $article_arr['mainEntityOfPage'] );
        }

        /**
         * article schema headline key
         */
        $headline = get_the_title( $this->post_id );
        if ( isset( $article_arr['headline'] ) && ! empty( $headline ) ) {
            $article_arr['headline']            = $headline;
        }else {
            unset( $article_arr['headline'] );
        }

        /**
         * article schema description key
         */
        $description = get_the_excerpt( $this->post_id );
        if( isset( $article_arr['description'] ) && ! empty( $description ) ) {
            $article_arr['description']         = $description;
        }

        /**
         * article schema image key
         */
        $images = $this->image();
        if ( isset( $article_arr['image'] ) && ! empty( $images ) ) {
            $article_arr['image']               = $images;
        }else {
            unset( $article_arr['image'] );
        }

        /**
         * article schema datePublished key
         */
        $datePublished = $this->datePublished();
        if ( isset( $article_arr['datePublished'] ) && ! empty( $datePublished ) ) {
            $article_arr['datePublished']       = $datePublished;
        } else {
            unset( $datePublished );
        }

        /**
         * article schema dateModified key
         */
        $dateModified = $this->dateModified();
        if ( isset(  $article_arr['dateModified'] ) && !empty( $dateModified ) ) {
            $article_arr['dateModified']        = $dateModified;
        } else {
            unset( $article_arr['dateModified'] );
        }

        /**
         * article schema author key
         */
        $author = $this->author( $article_arr['author'] );
        if( isset( $article_arr['author'] ) && ! empty( $author ) ) {
            $article_arr['author']              = $author;
        } else {
            unset( $article_arr['author'] );
        }

        /**
         * article schema publisher key
         */
        $publisher = $this->publisher( $article_arr['publisher'] );
        if ( isset( $article_arr['publisher'] )  && ! empty( $publisher ) ) {
            $article_arr['publisher']           = $publisher;
        } else {
            unset( $article_arr['publisher'] );
        }

        /**
         * article schema articleBody key
         */
        $articleBody = $this->articleBody();
        if ( isset( $article_arr['articleBody'] ) && !empty( $articleBody ) ) {
            $article_arr['articleBody']         = $articleBody;
        } else {
            unset( $article_arr['articleBody'] );
        }

        /**
         * article schema keywords key
         */
        $keywords = $this->keywords();
        if ( isset( $article_arr['keywords'] ) && ! empty( $keywords ) ) {
            $article_arr['keywords']            = $keywords;
        } else {
            unset( $article_arr['keywords'] );
        }

        /**
         * article schema articleSection key
         */
        $articleSection = $this->articleSection();
        if ( isset( $article_arr['articleSection'] ) && ! empty( $articleSection ) ) {
            $article_arr['articleSection']      = $articleSection;
        } else {
            unset( $article_arr['articleSection'] );
        }

        /**
         * article schema commentCount key
         */
        $commentCount = null;
        if (isset( $article_arr['commentCount'] ) && ! empty( $commentCount ) ) {
            $article_arr['commentCount']        = $commentCount;
        }

        /**
         * article schema wordCount key
         */
        $wordCount = null;
        if ( isset( $article_arr['wordCount'] ) && ! empty( $wordCount ) ) {
            $article_arr['wordCount']           = $wordCount;
        }

        /**
         * article schema thumbnailUrl key
         */
        $thumbnailUrl = null;
        if ( isset( $article_arr['thumbnailUrl'] ) && ! empty( $thumbnailUrl ) ) {
            $article_arr['thumbnailUrl']        = $thumbnailUrl;
        }


        /**
         * article schema isAccessibleForFree key
         */
        $isAccessibleForFree = null;
        if (isset($article_arr['isAccessibleForFree']) && !empty($isAccessibleForFree)) {
            $article_arr['isAccessibleForFree'] = $isAccessibleForFree;
        }

        /**
         * article schema copyrightHolder key
         */
        $copyrightHolder = null;
        if (isset($article_arr['copyrightHolder']) && !empty($copyrightHolder)) {
            $article_arr['copyrightHolder'] = $copyrightHolder;
        }

        /**
         * article schema potentialAction key
         */
        $potentialAction = null;
        if (isset($article_arr['potentialAction']) && !empty($potentialAction)) {
            $article_arr['potentialAction'] = $potentialAction;
        }

        /**
         * article schema isPartOf key
         */
        $isPartOf = null;
        if (isset($article_arr['isPartOf']) && !empty($isPartOf)) {
            $article_arr['isPartOf'] = $isPartOf;
        }

        /**
         * article schema mentions key
         */
        $mentions = null;
        if (isset($article_arr['mentions']) && !empty($mentions)) {
            $article_arr['mentions'] = $mentions;
        }

        /**
         * article schema publisherImprint key
         */
        $publisherImprint = null;
        if (isset($article_arr['publisherImprint']) && !empty($publisherImprint)) {
            $article_arr['publisherImprint'] = $publisherImprint;
        }

        /**
         * article schema alternateName key
         */
        $alternateName = null;
        if (isset($article_arr['alternateName']) && !empty($alternateName)) {
            $article_arr['alternateName'] = $alternateName;
        }

        /**
         * article schema dateCreated key
         */
        $dateCreated = null;
        if (isset($article_arr['dateCreated']) && !empty($dateCreated)) {
            $article_arr['dateCreated'] = $dateCreated;
        }

        /**
         * article schema comment key
         */
        $comment = null;
        if (isset($article_arr['comment']) && !empty($comment)) {
            $article_arr['comment'] = $comment;
        }

        /**
         * article schema interactionStatistic key
         */
        $interactionStatistic = null;
        if (isset($article_arr['interactionStatistic']) && !empty($interactionStatistic)) {
            $article_arr['interactionStatistic'] = $interactionStatistic;
        }

        /**
         * article schema blogPost key
         */
        $blogPost = null;
        if (isset($article_arr['blogPost']) && !empty($blogPost)) {
            $article_arr['blogPost'] = $blogPost;
        }

        /**
         * article schema isBasedOn key
         */
        $isBasedOn = null;
        if (isset($article_arr['isBasedOn']) && !empty($isBasedOn)) {
            $article_arr['isBasedOn'] = $isBasedOn;
        }

        /**
         * article schema genre key
         */
        $genre = null;
        if (isset($article_arr['genre']) && !empty($genre)) {
            $article_arr['genre'] = $genre;
        }

        /**
         * article schema educationalUse key
         */
        $educationalUse = null;
        if (isset($article_arr['educationalUse']) && !empty($educationalUse)) {
            $article_arr['educationalUse'] = $educationalUse;
        }

        /**
         * article schema about key
         */
        $about = null;
        if (isset($article_arr['about']) && !empty($about)) {
            $article_arr['about'] = $about;
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
     * Get dateModified
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

    protected function author( $author ) {  // TODO this author part is incomplete for some problem

        $author_ids = get_post_field( 'post_author', $this->post_id );

//        error_log( print_r( var_dump( $author_ids ), true ) );

//        $author_data = $author[0];
//
//        if ( is_array( $author_ids ) ) {
//
//        } else {
//            $author_name = get_the_author_meta( 'display_name', $author_ids );
//            if ( isset( $author_data['name'] ) && ! empty( $author_name ) ) {
//                $author_data['name'] = $author_name;
//            }
//        }
//
//        if (  ) {
//
//        }


        return [];
    }

    public function publisher( $publisher ) {

        return [];
    }

    /**
     * Get articleBody
     *
     * @return mixed|null
     */
    protected function articleBody() {

        $post = get_post( $this->post_id );
        $articleBody = strip_tags($post->post_content);

        if ( ! empty( $articleBody ) ) {
            return apply_filters("schemax_{$this->schema_type}_articleBody", $articleBody );
        }

        return null;
    }

    /**
     * Get the keywords
     *
     * @return mixed|null
     */
    protected function keywords() {

        $post_tags = get_the_tags( $this->post_id );

        $keywords = null;

        if ( $post_tags ) {
            foreach($post_tags as $tag) {
                $keywords =  $tag->name . ' '; // Display the tag name
            }
        } else {
            $keywords = the_tags();
        }

        if ( ! empty( $keywords ) ) {
            return apply_filters("schemax_{$this->schema_type}_keywords", $keywords );
        }

        return null;
    }

    /**
     * Get articleSection
     *
     * @return string | null
     */
    protected function articleSection() {

        return null;
    }


    /**
     * Article Schema
     *
     * @return void
     */
    public function article() {
        $updated_data = $this->update_schema();
        $this->schema_service->attach_schema( $updated_data, $this->schema_type );
    }
}