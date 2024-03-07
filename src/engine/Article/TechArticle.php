<?php

namespace Schema\Engine\Article;

class TechArticle extends Article {

    /**
     * Class __construct
     *
     * @param $post_id
     */
    public function __construct( $post_id = null )
    {
        parent::__construct($post_id);

        $this->schema_type = 'TechArticle';
    }

    /**
     * Update TechArticle keys
     *
     * @param $article_arr
     * @return mixed|null
     */
    protected function single_article( $article_arr )
    {
        $modify_array = parent::single_article( $article_arr );

        /**
         * Unset key articleSection
         */
        if ( isset( $modify_array['articleSection'] ) ) {
            unset( $modify_array['articleSection'] );
        }

        /**
         * Unset key commentCount
         */
        if ( isset( $modify_array['commentCount'] ) ) {
            unset( $modify_array['commentCount'] );
        }

        /**
         * Unset key wordCount
         */
        if ( isset( $modify_array['wordCount'] ) ) {
            unset( $modify_array['wordCount'] );
        }

        /**
         * Unset key isAccessibleForFree
         */
        if ( isset( $modify_array['isAccessibleForFree'] ) ) {
            unset($modify_array['isAccessibleForFree']);
        }

        /**
         * Unset key copyrightHolder
         */
        if ( isset( $modify_array['copyrightHolder'] ) ) {
            unset($modify_array['copyrightHolder']);
        }

        /**
         * Unset key potentialAction
         */
        if ( isset( $modify_array['potentialAction'] ) ) {
            unset($modify_array['potentialAction']);
        }

        /**
         * Unset key isPartOf
         */
        if ( isset( $modify_array['isPartOf'] ) ) {
            unset($modify_array['isPartOf']);
        }

        /**
         * Unset key mentions
         */
        if ( isset( $modify_array['mentions'] ) ) {
            unset($modify_array['mentions']);
        }

        /**
         * Unset key publisherImprint
         */
        if ( isset( $modify_array['publisherImprint'] ) ) {
            unset($modify_array['publisherImprint']);
        }

        /**
         * Unset key alternateName
         */
        if ( isset( $modify_array['alternateName'] ) ) {
            unset($modify_array['alternateName']);
        }

        /**
         * Unset key dateCreated
         */
        if ( isset( $modify_array['dateCreated'] ) ) {
            unset($modify_array['dateCreated']);
        }

        /**
         * Unset key comment
         */
        if ( isset( $modify_array['comment'] ) ) {
            unset($modify_array['comment']);
        }

        /**
         * Unset key interactionStatistic
         */
        if ( isset( $modify_array['interactionStatistic'] ) ) {
            unset($modify_array['interactionStatistic']);
        }

        /**
         * Unset key blogPost
         */
        if ( isset( $modify_array['blogPost'] ) ) {
            unset($modify_array['blogPost']);
        }

        /**
         * Unset key isBasedOn
         */
        if ( isset( $modify_array['isBasedOn'] ) ) {
            unset($modify_array['isBasedOn']);
        }

        /**
         * Unset key genre
         */
        if ( isset( $modify_array['genre'] ) ) {
            unset($modify_array['genre']);
        }

        /**
         * Unset key educationalUse
         */
        if ( isset( $modify_array['educationalUse'] ) ) {
            unset($modify_array['educationalUse']);
        }

        /**
         * Unset key about
         */
        if ( isset( $modify_array['about'] ) ) {
            unset($modify_array['about']);
        }

        return apply_filters("schemax_{$this->schema_type}_single_article", $modify_array );
    }

    /**
     * Type key
     *
     * @return string
     */
    protected function type() {
        return 'TechArticle';
    }
}