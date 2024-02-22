<?php

namespace Schema;

use Schema\Engine\Product;
use Schema\Engine\Article;
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
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            if ( is_product() ) {
                global $post;
                $product_object = new Product( $post->ID );
                $product_object->attach_schema();
            }
        }

        if ( ! is_admin() && ! defined( 'DOING_AJAX' ) ) {
            $website_object = new Website();
            $website_object->attach_schema();
        }

        if ( is_single() && 'post' == get_post_type() ) {
            global $post;
            new Article( $post->ID );
        }

	}
}