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
        error_log( print_r( 'this is a test message', true ) );
    }

    /**
     * Init Class run method for run the program
     *
     * @return void
     */
	public function run() {

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

        if ( 'post' == get_post_type() && is_single() || is_singular() ) {
            global $post;
            new Article( $post->ID );
        }
	}
}

//protected function video( $content ) {
//
//    $patterns = [
//        '/https?:\/\/(?:www\.)?youtube\.com\/watch\?v=[^&\s]+/',
//        '/https?:\/\/youtu\.be\/[^&\s]+/',
//        '/https?:\/\/(?:www\.)?vimeo\.com\/\d+/',
//        '/https?:\/\/(?:www\.)?instagram\.com\/p\/[^&\s]+/',
//        '/https?:\/\/(?:www\.)?twitter\.com\/[^&\s]+\/status\/\d+/',
//        '/https?:\/\/(?:www\.)?facebook\.com\/[^&\s]+\/videos\/\d+/'
//    ];
//
//    $videoLinks = [];
//
//    foreach ($patterns as $pattern) {
//        if (preg_match_all($pattern, $content, $matches)) {
//            $videoLinks = array_merge($videoLinks, $matches[0]);
//        }
//    }
//
//    return !empty($videoLinks) ? $videoLinks : false;
//}