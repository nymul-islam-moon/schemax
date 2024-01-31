<?php

namespace Schema;
//use Schema\Engine;

class Init {

	public function __construct() {
		add_action('wp_head', [ $this, 'run' ] );
	}

	public function run() {
        if ( is_product() ) {
            Engine\Product::attach_schema();
        }
	}
}