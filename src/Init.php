<?php

namespace Schema;

use Schema\Engine\Product;
use Schema\Engine\Article;
use Schema\Engine\Video;
use Schema\Engine\Audio;
use Schema\Inc\Support;

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
        global $post;

        /**
         * Product Schema
         */
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            if ( is_product() ) {
                new Product( $post->ID );
            }
        }

        /**
         * Website Schema
         */
//        if ( ! is_admin() && ! defined( 'DOING_AJAX' ) ) {
//            $website_object = new Website();
//            $website_object->attach_schema();
//        }

        /**
         * Article Schema
         */
        if ( 'post' == get_post_type() && ( is_single() || is_singular() ) && ! is_product() ) {
            new Article( $post->ID );
        }

        /**
         * Support Schemas
         */
        $support_schema = null;
        if ( ! empty( $post ) ) {
            $support_schema = new Support( $post->post_content );

            $support_schema_arr = $support_schema->get_support_schema();

            if ( is_array( $support_schema_arr ) ) {

                /**
                 * Video Schema
                 */
                if ( isset( $support_schema_arr['video'] ) ) {
                    new Video( $support_schema_arr['video'], $post->ID );
                }

                /**
                 * Audio Schema
                 */
                if ( isset( $support_schema_arr['audio'] ) ) {
                    new Audio( $support_schema_arr['audio'], $post->ID );
                }
            }

        }

	}
}