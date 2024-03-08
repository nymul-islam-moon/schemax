<?php

namespace Schema\Engine;

use Schema\Inc\BaseEngine;

class Faq extends BaseEngine {

    private $post_id;

    /**
     * __construct of the class
     *
     * @param $post_id
     */
    public function __construct( $post_id = null ) {
        $this->schema_file      = 'faq.json';

        parent::__construct();

        $this->schema_type      = 'FAQ';
        $this->post_id          = $post_id;

    }

    protected function update_schema()
    {
        $this->schema            = json_encode( $this->faq( $this->schema_structure ) );

        return apply_filters( "schemax_{$this->schema_type}_update_schema", $this->schema );
    }

    /**
     * Update the faq schema
     *
     * @param $faq_arr
     * @return mixed|null
     */
    protected function faq( $faq_arr ) {

        /**
         * headline property
         */
        if ( isset( $faq_arr['headline'] ) ) {
            $headline   = $this->headline();
            if ( ! empty( $headline ) ) {
                $faq_arr['headline'] = $headline;
            } else {
                unset( $faq_arr['headline'] );
            }
        }

        /**
         * datePublished property
         */
        if ( isset( $faq_arr['datePublished'] ) ) {
            $datePublished = $this->datePublished();
            if ( ! empty( $datePublished ) ) {
                $faq_arr['datePublished'] = $datePublished;
            } else {
                unset( $faq_arr['datePublished'] );
            }
        }

        /**
         * dateModified property
         */
        if ( isset( $faq_arr['dateModified'] ) ) {
            $dateModified = $this->dateModified();
            if ( ! empty( $dateModified ) ) {
                $faq_arr['dateModified'] = $dateModified;
            } else {
                unset( $faq_arr['dateModified'] );
            }
        }

        /**
         * dateCreated property
         */
        if ( isset( $faq_arr['dateCreated'] ) ) {
            $dateCreated    = $this->dateCreated();
            if ( !empty( $dateCreated ) ) {
                $faq_arr['dateCreated'] = $dateCreated;
            } else {
                unset( $dateCreated );
            }
        }

        /**
         * Get author
         */
        if ( isset( $faq_arr['author'] ) ) {
            $author         = $this->author( $faq_arr['author'] );

            if ( !empty( $author ) ) {
                $faq_arr['author'] = $author;
            } else {
                unset( $author );
            }

        }

        /**
         * mainEntity property
         */
        if ( isset( $faq_arr['mainEntity'] ) ) {
            $mainEntity = $this->mainEntity( $faq_arr['mainEntity'] );
            if ( ! empty( $mainEntity ) ) {
                $faq_arr['mainEntity'] = $mainEntity;
            }
        }

        return apply_filters("schemax_{$this->schema_type}_faq", $faq_arr );
    }

    /**
     * Get headline
     *
     * @return string|null
     */
    protected function headline() {
        $headline           = get_the_title( $this->post_id );

        if ( ! empty( $headline ) ) {
            return $headline;
        }

        return null;
    }

    /**
     * Get datePublished
     *
     * @return int|string|null
     */
    protected function datePublished() {
        $publish_date = get_the_date( 'Y-m-d', $this->post_id );

        if ( ! empty( $publish_date ) ) {
            return $publish_date;
        }

        return null;
    }

    /**
     * Get dateModified
     *
     * @return int|string|null
     */
    protected function dateModified() {

        $dateModified   = get_the_modified_date('Y-m-d', $this->post_id );

        if ( ! empty( $dateModified ) ) {
            return $dateModified;
        }

        return null;
    }

    /**
     * Get dateCreated
     *
     * @return array|int|string|null
     */
    protected function dateCreated() {

        $dateCreated = get_post_field( 'post_date', $this->post_id );

        if ( ! empty( $dateCreated ) ) {
            return $dateCreated;
        }

        return null;
    }

    protected function author( $author ) {
        $author_id = get_post_field( 'post_author', $this->post_id );

        $author_info = get_userdata( $author_id );

        /**
         * Get name
         */
        if ( isset( $author['name'] ) ) {
            $name = $author_info->display_name;
            if ( ! empty( $name ) ) {
                $author['name'] = $name;
            } else {
                return null;
            }
        }

        /**
         * Get url
         */
        if ( isset( $author['url'] ) ) {
            $url = get_author_posts_url( $author_id );
            if ( ! empty( $url ) ) {
                $author['url'] = $url;
            } else {
                unset( $author['url'] );
            }
        }

        /**
         * Get sameAs
         */
        if ( isset( $author['sameAs'] ) ) {
            $home_url = home_url();
            if ( ! empty( $home_url ) ) {
                 $author['sameAs'][] = $home_url;
            }

            if ( ! empty( $url ) ) {
                $author['sameAs'][] = $url;
            }

            if ( empty( $home_url ) && empty( $url ) ) {
                unset( $author['sameAs'] );
            }
        }

        /**
         * Get image url
         */
        if ( isset( $author['image']['url'] ) ) {

            $image_data = get_avatar( $author_id );

            $pattern = '/<img.*?src=["\'](.*?)["\']/i';

            // Match the pattern in the HTML code
            preg_match($pattern, $image_data, $matches);

            if ( isset($matches[1]) ) {
                $author['image']['url'] = $matches[1];
            } else {
                unset( $author['image'] );
            }
        }

        /**
         * Get image height
         */
        if ( isset( $author['image']['height'] ) ) {
            $image_height = null;
            if ( ! empty( $image_height ) ) {
                $author['image']['height'] = $image_height;
            } else {
                unset( $author['image']['height'] );
            }
        }

        /**
         * Get image width
         */
        if ( isset( $author['image']['width'] ) ) {
            $image_width = null;
            if ( ! empty( $image_width ) ) {
                $author['image']['width'] = $image_width;
            } else {
                unset( $author['image']['width'] );
            }
        }

        return apply_filters( "schemax_{$this->schema_type}_author", $author );
    }

    protected function mainEntity( $mainEntity ) {

        $result = $this->extractTitlesAndAnswers();

        return null;
    }


    private function extractTitlesAndAnswers() {

        $htmlContent = get_the_content( $this->post_id );

        // Load HTML content into a DOMDocument
        $dom = new \DOMDocument();
        @$dom->loadHTML($htmlContent); // Suppress warnings

        // Initialize arrays to store titles and answers
        $titles = array();
        $answers = array();

        // Get all <h> tags (h1 to h6) and blockquote tags
        $headings = $dom->getElementsByTagName('h1');
        $headings = iterator_to_array($headings);
        $headings = array_merge($headings, iterator_to_array($dom->getElementsByTagName('h2')));
        $headings = array_merge($headings, iterator_to_array($dom->getElementsByTagName('h3')));
        $headings = array_merge($headings, iterator_to_array($dom->getElementsByTagName('h4')));
        $headings = array_merge($headings, iterator_to_array($dom->getElementsByTagName('h5')));
        $headings = array_merge($headings, iterator_to_array($dom->getElementsByTagName('h6')));
        $blockquotes = iterator_to_array($dom->getElementsByTagName('blockquote'));

        // Extract text from <h> tags and blockquote tags for titles
        foreach ($headings as $heading) {
            $titles[] = trim($heading->nodeValue);
        }
        foreach ($blockquotes as $blockquote) {
            $titles[] = trim($blockquote->nodeValue);
        }

        // Get all other tags text (excluding <h> and <blockquote> tags) for answers
        $allTags = $dom->getElementsByTagName('*');
        foreach ($allTags as $tag) {
            if (!in_array($tag->nodeName, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote'))) {
                $answers[] = $tag->nodeValue;
            }
        }

        // Return titles and answers as an associative array
        return array('titles' => $titles, 'answers' => $answers);
    }
}