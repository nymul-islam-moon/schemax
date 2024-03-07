<?php

namespace Schema\Inc;

class Support {

    protected $content;

    /**
     * Support class __construct
     *
     * @param $content
     */
    public function __construct( $content ) {

        $this->content      = $content;
    }

    /**
     * Get all support schema
     *
     * @return array
     */
    public function get_support_schema() {
        $class_methods = get_class_methods( $this );

        $support_schemas = [];

        foreach ($class_methods as $method) {
            if ( $method !== '__construct' && $method !== '__destruct' && $method !== 'get_support_schema' ) {
                if ( ! empty( $this->$method( $this->content ) ) ) {
                    $support_schemas[$method] = $this->$method( $this->content );
                }
            }
        }

        return $support_schemas;
    }

    /**
     * Check if video file exist or not
     *
     * @param $content
     * @return array
     */
    protected function video( $content ) {

        $video_links = [];

        // Regular expression pattern to match video embed blocks
        $patterns = [
            '/<video[^>]+src="([^"]+)"/',
            '/<!-- wp:embed {"url":"([^"]+)"[^>]*?} -->/'
        ];

        foreach ( $patterns as $key => $pattern ) {
            // Match video embed blocks in the content
            if (preg_match_all($pattern, $content, $matches)) {
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
     * Check if audio file exist or not
     *
     * @param $content
     * @return array|string[]
     */
    protected function audio( $content ) {

        $audio_links = [];

        $pattern = '/<audio[^>]+src="([^"]+)"/';

        if ( preg_match_all( $pattern, $content, $matches ) ) {
            if ( isset( $matches[1] ) ) {
                $links = $matches[1];
                foreach ( $links as $key => $link ) {
                    $audio_links = $links;
                }
            }
        }
        return !empty( $audio_links ) ? $audio_links : [];
    }

    protected function howto( $content ) {
        error_log( print_r( 'here how to', true ) );
        $search_for = 'how to';
        $content_lower = strtolower( $content );

        if ( strpos( $content_lower, $search_for ) ) {

            return true;
        }

        return false;
    }

    protected function faq( $content ) {
        $search_for = 'FAQ';

        $content_lower = strtolower( $content );

        if ( strpos( $content_lower, $search_for ) ) {

            return true;
        }

        return false;
    }
}