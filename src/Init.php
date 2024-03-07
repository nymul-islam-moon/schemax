<?php

namespace Schema;

use Schema\Engine\Article\Article;
use Schema\Engine\Article\TechArticle;
use Schema\Engine\Audio;
use Schema\Engine\HowTo;
use Schema\Engine\Product;
use Schema\Engine\Video;
use Schema\Engine\Website;
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
        if ( ! is_admin() ) {
            new Website( $post->ID );
        }

        /**
         * Article Schema
         */
        if ( ( 'docs' === get_post_type( $post->ID ) || 'post' == get_post_type( $post->ID ) ) && ( is_single() || is_singular() ) && ! is_product() ) {
            if ( 'docs' === get_post_type( $post->ID ) ) {
                new TechArticle( $post->ID );
            } else {
                new Article( $post->ID );
            }
        }

        /**
         * Support Schemas
         */
        $support_schema = null;
        if ( ! empty( $post ) ) {
            $support_schema = new Support( $post->post_content );

            $support_schema_arr = $support_schema->get_support_schema();

            /**
             * Check if the support schema array empty or not
             */
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

                /**
                 * HowTo Schema
                 */
                if ( isset( $support_schema_arr['howto'] ) ) {
                    new HowTo( $post->ID );
                }
            }
        }
	}
}