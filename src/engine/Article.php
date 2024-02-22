<?php

namespace Schema\Engine;

use Schema\Inc\BaseEngine;
use Schema\Inc\Service;

class Article extends BaseEngine {

    private $post_id;

    public function __construct( $post_id = null ) {

        $this->schema_file      = 'article.json';
        parent::__construct();

        $this->schema_type      = 'article';
        $this->post_id          = $post_id;
    }

    /**
     * Update the Article schema with real time data
     *
     * @return mixed|null
     */
    protected function update_schema() {

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
            $article_arr['@type']                       = $type;
        }else {
            unset( $article_arr['@type'] );
        }

        /**
         * article schema mainEntityOfPage key
         */
        if ( isset( $article_arr['mainEntityOfPage'] ) ) {
            $mainEntityOfPage                           = $this->mainEntityOfPage( $article_arr['mainEntityOfPage'] );
            if ( ! empty( $mainEntityOfPage ) ) {
                $article_arr['mainEntityOfPage']        = $mainEntityOfPage;
            } else {
                unset( $article_arr['mainEntityOfPage'] );
            }
        }

        /**
         * article schema headline key
         */
        if( isset( $article_arr['headline'] ) ) {
            $headline                                   = get_the_title( $this->post_id );
            if ( ! empty( $headline ) ) {
                $article_arr['headline']                = $headline;
            } else {
                unset( $article_arr['headline'] );
            }
        }

        /**
         * article schema description key
         */
        if ( isset( $article_arr['description'] ) ) {
            $description                                = get_the_excerpt($this->post_id);
            if ( !empty( $description ) ) {
                $article_arr['description']             = $description;
            } else {
                unset( $article_arr['description'] );
            }
        }

        /**
         * article schema image key
         */
        if ( isset( $article_arr['image'] ) ) {
            $images                                     = $this->image();
            if ( ! empty( $images ) ) {
                $article_arr['image']                   = $images;
            }else {
                unset( $article_arr['image'] );
            }
        }

        /**
         * article schema datePublished key
         */
        if ( isset( $article_arr['datePublished'] ) ) {
            $datePublished                              = $this->datePublished();
            if ( !empty( $datePublished ) ) {
                $article_arr['datePublished']           = $datePublished;
            } else {
                unset( $datePublished );
            }
        }

        /**
         * article schema dateModified key
         */
        if ( isset( $article_arr['dateModified'] ) ) {
            $dateModified                               = $this->dateModified();
            if ( !empty( $dateModified ) ) {
                $article_arr['dateModified']            = $dateModified;
            } else {
                unset( $article_arr['dateModified'] );
            }
        }

        /**
         * article schema author key
         */
        if ( isset( $article_arr['author'] ) ) {
            $author                                     = $this->author( $article_arr['author'] );
            if ( !empty( $author ) ) {
                $article_arr['author']                  = $author;
            } else {
                unset( $article_arr['author'] );
            }
        }

        /**
         * article schema publisher key
         */
        if ( isset( $article_arr['publisher'] ) ) {
            $publisher                                  = $this->publisher( $article_arr['publisher'] );
            if ( ! empty( $publisher ) ) {
                $article_arr['publisher']               = $publisher;
            } else {
                unset( $article_arr['publisher'] );
            }
        }

        /**
         * article schema articleBody key
         */
        if ( isset($article_arr['articleBody']) ) {
            $articleBody                                = $this->articleBody();
            if ( !empty($articleBody)) {
                $article_arr['articleBody']             = $articleBody;
            } else {
                unset( $article_arr['articleBody'] );
            }
        }

        /**
         * article schema keywords key
         */
        if ( isset( $article_arr['keywords'] ) ) {
            $keywords                                   = $this->keywords();
            if ( ! empty( $keywords ) ) {
                $article_arr['keywords']                = $keywords;
            } else {
                unset( $article_arr['keywords'] );
            }
        }

        /**
         * article schema articleSection key
         */
        if ( isset( $article_arr['articleSection'] ) ) {
            $articleSection                             = $this->articleSection();
            if ( !empty( $articleSection ) ) {
                $article_arr['articleSection']          = $articleSection;
            } else {
                unset( $article_arr['articleSection'] );
            }
        }

        /**
         * article schema commentCount key
         */
        if ( isset( $article_arr['commentCount'] ) ) {
            $commentCount                               = $this->commentCount();
            if ( !empty( $commentCount ) ) {
                $article_arr['commentCount']            = $commentCount;
            } else {
                unset( $article_arr['commentCount'] );
            }
        }

        /**
         * article schema wordCount key
         */
        if ( isset( $article_arr['wordCount'] ) ) {
            $wordCount                                  = $this->wordCount();
            if ( ! empty( $wordCount ) ) {
                $article_arr['wordCount']               = $wordCount;
            } else {
                unset( $article_arr['wordCount'] );
            }
        }

        /**
         * article schema thumbnailUrl key
         */
        if ( isset( $article_arr['thumbnailUrl'] ) ) {
            $thumbnailUrl                               = $this->thumbnailUrl();
            if ( ! empty( $thumbnailUrl ) ) {
                $article_arr['thumbnailUrl']            = $thumbnailUrl;
            } else {
                unset( $article_arr['thumbnailUrl'] );
            }
        }


        /**
         * article schema isAccessibleForFree key
         */
        if ( isset( $article_arr['isAccessibleForFree'] ) ) {
            $article_arr['isAccessibleForFree']         = $this->isAccessibleForFree();
        }

        /**
         * article schema copyrightHolder key
         */
        if ( isset( $article_arr['copyrightHolder'] ) ) {
            $copyrightHolder                            = $this->copyrightHolder( $article_arr['copyrightHolder'] );
            if ( ! empty( $copyrightHolder ) ) {
                $article_arr['copyrightHolder']         = $copyrightHolder;
            } else {
                unset( $article_arr['copyrightHolder'] );
            }
        }

        /**
         * article schema potentialAction key
         */
        if ( isset( $article_arr['potentialAction'] ) ) {
            $potentialAction                            = $this->potentialAction( $article_arr['potentialAction'] );
            if ( ! empty( $potentialAction ) ) {
                $article_arr['potentialAction']         = $potentialAction;
            } else {
                unset( $article_arr['potentialAction'] );
            }
        }

        /**
         * article schema isPartOf key
         */
        if ( isset( $article_arr['isPartOf'] ) ) {
            $isPartOf                                   = $this->isPartOf( $article_arr['isPartOf'] );
            if ( !empty( $isPartOf ) ) {
                $article_arr['isPartOf']                = $isPartOf;
            } else {
                unset( $article_arr['isPartOf'] );
            }
        }

        /**
         * article schema mentions key
         */
        if ( isset( $article_arr['mentions'] ) ) {
            $mentions                                   = $this->mentions( $article_arr['mentions'] );
            if ( ! empty( $mentions ) ) {
                $article_arr['mentions']                = $mentions;
            } else {
                unset( $article_arr['mentions'] );
            }
        }

        /**
         * article schema publisherImprint key
         */
        if ( isset( $article_arr['publisherImprint'] ) ) {
            $publisherImprint                           = $this->publisherImprint( $article_arr['publisherImprint'] );
            if ( ! empty( $publisherImprint ) ) {
                $article_arr['publisherImprint']        = $publisherImprint;
            } else {
                unset( $article_arr['publisherImprint'] );
            }
        }

        /**
         * article schema alternateName key
         */
        if ( isset( $article_arr['alternateName'] ) ) {
            $alternateName                              = $this->alternateName();
            if ( ! empty( $alternateName ) ) {
                $article_arr['alternateName']           = $alternateName;
            } else {
                unset( $article_arr['alternateName'] );
            }
        }

        /**
         * article schema dateCreated key
         */
        if ( isset( $article_arr['dateCreated'] ) ) {
            $dateCreated                                = $this->dateCreated();
            if ( ! empty( $dateCreated ) ) {
                $article_arr['dateCreated']             = $dateCreated;
            } else {
                unset( $article_arr['dateCreated'] );
            }
        }

        /**
         * article schema comment key
         */
        if ( isset( $article_arr['comment'] ) ) {
            $comment                                    = $this->comment();
            if ( !empty( $comment ) ) {
                $article_arr['comment']                 = $comment;
            } else {
                unset( $article_arr['comment'] );
            }
        }

        /**
         * article schema interactionStatistic key
         */
        if ( isset( $article_arr['interactionStatistic'] ) ) {
            $interactionStatistic                       = $this->interactionStatistic( $article_arr['interactionStatistic'] );
            if ( !empty( $interactionStatistic ) ) {
                $article_arr['interactionStatistic']    = $interactionStatistic;
            } else {
                unset( $article_arr['interactionStatistic'] );
            }
        }

        /**
         * article schema blogPost key
         */
        if ( isset( $article_arr['blogPost'] ) ) {
            $blogPost                                   = $this->blogPost( $article_arr['blogPost'] );
            if ( ! empty( $blogPost ) ) {
                $article_arr['blogPost']                = $blogPost;
            } else {
                unset( $article_arr['blogPost'] );
            }
        }

        /**
         * article schema isBasedOn key
         */
        if ( isset( $article_arr['isBasedOn'] ) ) {
            $isBasedOn                                  = null;
            if ( ! empty( $isBasedOn ) ) {
                $article_arr['isBasedOn']               = $isBasedOn;
            } else {
                unset( $article_arr['isBasedOn'] );
            }
        }

        /**
         * article schema genre key
         */
        if ( isset( $article_arr['genre'] ) ) {
            $genre                                      = null;
            if ( !empty( $genre ) ) {
                $article_arr['genre']                   = $genre;
            } else {
                unset( $article_arr['genre'] );
            }
        }

        /**
         * article schema educationalUse key
         */
        if ( isset( $article_arr['educationalUse'] ) ) {
            $educationalUse                             = null;
            if ( ! empty( $educationalUse ) ) {
                $article_arr['educationalUse']          = $educationalUse;
            } else {
                unset( $article_arr['educationalUse'] );
            }
        }

        /**
         * article schema about key
         */
        if ( isset( $article_arr['about'] ) ) {
            $about                                      = null;
            if ( ! empty( $about ) ) {
                $article_arr['about']                   = $about;
            } else {
                unset( $article_arr['about'] );
            }
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

    /**
     * Get author
     *
     * @param $author
     * @return array
     */
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

    /**
     * Get publisher
     *
     * @param $publisher
     * @return array
     */
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
        $articleBody = strip_tags( $post->post_content );

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
     * Get commentCount
     *
     * @return mixed|null
     */
    protected function commentCount() {

        $commentCount = get_comments_number( $this->post_id );

        if ( ! empty( $commentCount ) ) {
            return apply_filters( "schemax_{ $this->schema_type }_commentCount", $commentCount );
        }

        return null;
    }

    /**
     * Get wordCount
     *
     * @return mixed|null
     */
    protected function wordCount() {

        $content = get_post_field('post_content', $this->post_id);
        $wordCount = str_word_count(strip_tags( $content ) );

        if ( ! empty( $wordCount ) ) {
            return apply_filters( "schemax_{ $this->schema_type }_wordCount", $wordCount );
        }

        return null;
    }

    /**
     * Get thumbnailUrl
     *
     * @return mixed|null
     */
    protected function thumbnailUrl() {
        $thumbnailUrl = get_the_post_thumbnail_url( $this->post_id );

        if ( ! empty( $thumbnailUrl ) ) {
            return apply_filters( "schemax_{ $this->schema_type }_thumbnailUrl", $thumbnailUrl );
        }

        return null;
    }

    /**
     * Get isAccessibleForFree
     *
     * @return boolean
     */
    protected function isAccessibleForFree() {

        return false;
    }

    /**
     * Get copyrightHolder
     *
     * @return mixed|null
     */
    protected function copyrightHolder( $copyrightHolder ) {

        $name = null;
        if ( isset( $copyrightHolder['name'] ) && ! empty( $name ) ) {
            $copyrightHolder['name'] = $name;
        } else {
            return null;
        }

        $url = null;
        if ( isset( $copyrightHolder['logo']['url'] ) && ! empty( $url ) ) {
            $copyrightHolder['logo']['url'] = $url;

        } else {
            unset( $copyrightHolder['logo'] );
        }

        return apply_filters( "schemax_{ $this->schema_type }_copyrightHolder", $copyrightHolder );
    }

    /**
     * Get potentialAction
     *
     * @param $potentialAction
     * @return mixed|null
     */
    protected function potentialAction( $potentialAction ) {

        if ( isset($potentialAction['target'] ) ) {
            $potentialAction['target']      = [];
        }

        if ( ! empty( $potentialAction['target'] ) ) {
            return apply_filters( "schemax_{ $this->schema_type }_potentialAction", $potentialAction );
        }

        return null;
    }

    /**
     * Get isPartOf
     *
     * @param $isPartOf
     * @return mixed|null
     */
    protected function isPartOf( $isPartOf ) {

        if ( isset( $isPartOf['name'] ) ) {
            $isPartOf['name']       = null;
        }

        $url = null;
        if( isset( $isPartOf['url'] ) && ! empty( $url ) ) {
            $isPartOf['url']        = $url;
        } else {
            unset( $isPartOf['url'] );
        }

        if ( ! empty( $isPartOf['name'] ) ) {
            return apply_filters( "schemax_{ $this->schema_type }_isPartOf", $isPartOf );
        }

        return null;
    }

    protected function mentions( $mentions ) {

        return [];
    }

    protected function publisherImprint( $publisherImprint ) {

        return null;
    }

    protected function alternateName() {

        return null;
    }

    /**
     * Get dateCreated
     *
     * @return mixed|null
     */
    protected function dateCreated() {

        $datePublished = get_the_date('F j, Y', $this->post_id);

        if ( ! empty( $datePublished ) ) {
            return apply_filters("schemax_{$this->schema_type}_datePublished", $datePublished );
        }

        return null;
    }

    /**
     * Get comment
     *
     * @return mixed|null
     */
    protected function comment() {

        $comment = get_comments_link( $this->post_id );

        if ( ! empty( $comment ) ) {
            return apply_filters("schemax_{$this->schema_type}_comment", $comment );
        }

        return null;
    }

    /**
     * Get interactionStatistic
     *
     * @param $interactionStatistic
     * @return mixed|null
     */
    protected function interactionStatistic( $interactionStatistic ) {

//        $userInteractionCount = (int) 0;
//
//        if ( isset( $interactionStatistic['interactionType']['userInteractionCount'] ) && $userInteractionCount > 0 ) {
//            $interactionStatistic['interactionType']['userInteractionCount'] = $userInteractionCount;
//        }
//
//        if ( $interactionStatistic['interactionType']['userInteractionCount'] > 0 ) {
//            return apply_filters("schemax_{$this->schema_type}_interactionStatistic", $interactionStatistic );
//        }

        return null;
    }

    protected function blogPost( $blogPost ) {
        return null;
    }

    /**
     * Show the Schema in meta tag
     *
     * @return void
     */
    public function article() {
        $this->schema = $this->update_schema();
        echo "<script src='schemax-$this->schema_type' type='application/ld+json'>$this->schema</script>";
    }
}