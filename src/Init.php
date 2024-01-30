<?php

namespace Schema;
//use Schema\Engine;

class Init {

	public function __construct() {
		add_action('wp_head', [ $this, 'run' ] );
	}

	public function run() {

        if ( is_product() ) {
            Engine\Product::register();
        }

//		$schemaPath = dirname( SCHEMA_PLUGIN_PATH ) . 'templates/product.json';
//		if ( file_exists( $schemaPath ) ) {
//            $fileData = file_get_contents( $schemaPath );
//            echo "<script type='application/ld+json'>$fileData</script>";
//		}

	}
}