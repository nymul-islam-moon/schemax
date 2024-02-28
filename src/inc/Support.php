<?php

namespace Schema\Inc;

class Support {

    protected $content;

    public function __construct( $content ) {
        $this->content      = $content;
    }

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
}