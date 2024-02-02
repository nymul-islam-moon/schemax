<?php

namespace Schema;
use Schema\Engine\Product;

class Init {

	public function __construct() {
		add_action('wp_head', [ $this, 'run' ] );
	}

	public function run() {
        if ( is_product() ) {
            global $post;
            $product_object = new Product( $post->ID );
            $product_object->attach_schema();
        }
	}
}