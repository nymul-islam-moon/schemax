<?php

namespace Schema;

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

use Schema\Engine\Product;
use Schema\Engine\Website;


class Init {

	public function __construct() {
		add_action('wp_head', [ $this, 'run' ] );
	}

	public function run() {

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

	}
}