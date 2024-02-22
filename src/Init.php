<?php

namespace Schema;

use Schema\Engine\Product;
use Schema\Engine\Article;
use Schema\Engine\Video;
use Schema\Engine\Website;

class Init {

    /**
     * Init class constructor for attach the schema in wp_head tag
     */
	public function __construct() {
		add_action('wp_head', [ $this, 'run' ] );
	}

    /**
     * Init Class run method for run the program
     *
     * @return void
     */
	public function run() {
        if( ! function_exists('is_plugin_active') ) {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php');
        }
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            if ( is_product() ) {
                global $post;
                new Product( $post->ID );
            }
        }

        if ( ! is_admin() && ! defined( 'DOING_AJAX' ) ) {
            $website_object = new Website();
            $website_object->attach_schema();
        }

        if ( is_single() || is_singular() ) {
            global $post;

            new Article( $post->ID );

            $post_content = get_post_field('post_content', $post->ID);

            $video_links = $this->contains_video_link( $post_content );

            if ( $video_links ) {
                new Video( $post->ID, $video_links );
            }

        }
	}

    /**
     * Check if any video link exist or not
     *
     * @param $string
     * @return bool
     */
    private function contains_video_link( $string ) {
        // Patterns to match YouTube, Vimeo, Instagram, Twitter, and Facebook video URLs
        $patterns = [
            '/https?:\/\/(?:www\.)?youtube\.com\/watch\?v=[^&\s]+/', // YouTube full URL
            '/https?:\/\/youtu\.be\/[^&\s]+/', // YouTube shortened URL
            '/https?:\/\/(?:www\.)?vimeo\.com\/\d+/', // Vimeo URL
            '/https?:\/\/(?:www\.)?instagram\.com\/p\/[^&\s]+/', // Instagram video posts (short URL)
            '/https?:\/\/(?:www\.)?twitter\.com\/[^&\s]+\/status\/\d+/', // Twitter status (may include videos)
            '/https?:\/\/(?:www\.)?facebook\.com\/[^&\s]+\/videos\/\d+/' // Facebook video URLs
        ];

        $videoLinks = []; // Initialize an empty array to store video links

        // Check each pattern to see if it matches anywhere in the string
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $string, $matches)) {
                // Merge found video links into the videoLinks array
                $videoLinks = array_merge($videoLinks, $matches[0]);
            }
        }

        // Return the array of video links, or false if none were found
        return !empty($videoLinks) ? $videoLinks : false;
    }


}