<?php

namespace Schema\Inc;

class Support {

    protected $content;

    public function __construct( $content ) {
        $this->content      = $content;
    }

    public function get_support_schema() {
        $class_methods = get_class_methods($this);

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
        $patterns = [
            '/https?:\/\/(?:www\.)?youtube\.com\/watch\?v=[^&\s]+/',
            '/https?:\/\/youtu\.be\/[^&\s]+/',
            '/https?:\/\/(?:www\.)?vimeo\.com\/\d+/',
            '/https?:\/\/(?:www\.)?instagram\.com\/p\/[^&\s]+/',
            '/https?:\/\/(?:www\.)?twitter\.com\/[^&\s]+\/status\/\d+/',
            '/https?:\/\/(?:www\.)?facebook\.com\/[^&\s]+\/videos\/\d+/'
        ];

        $videoLinks = [];

        foreach ( $patterns as $pattern ) {
            if ( preg_match_all( $pattern, $content, $matches ) ) {
                $videoLinks = array_merge( $videoLinks, $matches[0] );
            }
        }

        return !empty( $videoLinks ) ? $videoLinks : [];
    }


    protected function audio( $content ) {
        // Patterns for common audio file URLs
        $patterns = [
            '/https?:\/\/(?:www\.)?[\w\-\.]+\/[\w\-\.]+\/[\w\-\.]+\/[\w\-\.]+\.mp3/',
            '/https?:\/\/(?:www\.)?[\w\-\.]+\/[\w\-\.]+\/[\w\-\.]+\/[\w\-\.]+\.wav/',
            '/https?:\/\/(?:www\.)?[\w\-\.]+\/[\w\-\.]+\/[\w\-\.]+\/[\w\-\.]+\.ogg/',
            '/\.(mp3|wav|ogg)/i'
        ];

        $audioLinks = [];

        foreach ( $patterns as $pattern ) {
            if ( preg_match_all( $pattern, $content, $matches ) ) {
                $audioLinks = array_merge( $audioLinks, $matches[0] );
            }
        }

        return !empty( $audioLinks ) ? $audioLinks : [];
    }


}