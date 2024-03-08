<?php

namespace Schema\Inc;

class Support {

    protected $post, $content, $title;

    /**
     * Support class __construct
     *
     * @param $post_id
     */
    public function __construct( $post_id ) {

        $this->post         = get_post( $post_id );

        $this->title        = get_the_title( $post_id );

        $this->content      = $this->post->post_content;
    }

    /**
     * Get all support schema
     *
     * @return array
     */
    public function get_support_schema() {
        $class_methods      = get_class_methods( $this );

        $support_schemas    = [];

        foreach ( $class_methods as $method ) {
            if ( $method !== '__construct' && $method !== '__destruct' && $method !== 'get_support_schema' ) {
                if ( ! empty( $this->$method() ) ) {
                    $support_schemas[$method] = $this->$method();
                }
            }
        }

        return $support_schemas;
    }

    /**
     * Check if video exist or not
     *
     * @return array
     */
    protected function video() {

        $video_links = [];

        // Regular expression pattern to match video embed blocks
        $patterns = [
            '/<video[^>]+src="([^"]+)"/',
            '/<!-- wp:embed {"url":"([^"]+)"[^>]*?} -->/'
        ];

        foreach ( $patterns as $key => $pattern ) {
            // Match video embed blocks in the content
            if (preg_match_all($pattern, $this->content, $matches)) {
                if (isset($matches[1])) {
                    $links = $matches[1];
                    foreach ($links as $link) {
                        // Add the extracted video link to the list
                        $video_links[] = $link;
                    }
                }
            }
        }

        return !empty( $video_links ) ? $video_links : [];
    }


    /**
     * Check if audio exist or not
     *
     * @return array|string[]
     */
    protected function audio() {

        $audio_links = [];

        $pattern = '/<audio[^>]+src="([^"]+)"/';

        if ( preg_match_all( $pattern, $this->content, $matches ) ) {
            if ( isset( $matches[1] ) ) {
                $links = $matches[1];
                foreach ( $links as $key => $link ) {
                    $audio_links = $links;
                }
            }
        }
        return !empty( $audio_links ) ? $audio_links : [];
    }

    /**
     * Check How To content exist or not
     *
     * @return true|null
     */
    protected function howto() {

        $search_for = "how to";
        $content_lower = strtolower( $this->title );

        if ( strpos( $content_lower, $search_for ) !== false ) {
            return true;
        }

        return null;
    }

    /**
     * Check if FAQ exist or not
     *
     * @return true|null
     */
    protected function faq() {

        $search_for = 'faq';

        $content_lower = strtolower( $this->title );

        if ( strpos( $content_lower, $search_for ) !== false ) {
            return true;
        }

        return null;
    }
}