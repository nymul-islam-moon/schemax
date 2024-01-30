<?php
/**
 * Schema
 *
 * @package           Schema
 * @author            Nymul Islam
 * @copyright         2024 Nymul Islam
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Schema
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Description of the plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Nymul Islam
 * Author URI:        https://example.com/plugin-name
 * Text Domain:       Schema
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 */

/*
{Schema} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Schema} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Schema}. If not, see {URI to Plugin License}.
*/

defined('ABSPATH') or die('Hay, You can not access the area');

require_once ''

const SCHEMA_PLUGIN_PATH = __FILE__;

if ( ! class_exists( 'Schema' ) ) {  // Check if the 'Schema' class does not exist
	class Schema {
		function __construct() {
			add_action('wp_head', array( 'Schema', 'run') );
		}

		public static function run() {

			$filePath = dirname( SCHEMA_PLUGIN_PATH ) . '/templates';

		    if ( is_product() ) {
		        $filePath = $filePath . '/product.json';

		        if ( file_exists( $filePath ) ) {
			        $fileData = file_get_contents( $filePath );

			        echo "<script type='application/ld+json'>$fileData</script>";
		        }
		    }
		}
	}
	Schema::run();
}





//function schema() {

//    $filePath = dirname( SCHEMA_PLUGIN_PATH ) . '/templates';
//
//    if ( is_product() ) {
//        $filePath = $filePath . '/product.json';
//
//        if ( file_exists( $filePath ) ) {
//
//        }
//
//    }


//    $fileData = null;
//
//    $filePath = dirname(SCHEMA_PLUGIN_PATH ) . '/templates/product.json';
//
//    if ( ! file_exists( $filePath ) ) {
//        $fileData = 'file not found';
//    } else {
//        $fileData = file_get_contents( $filePath );
//    }
//    if (is_product()) {
//        echo "<script type='application/ld+json'>$fileData</script>";
//    }else {
//        echo "<script type='application/ld+json'>not product</script>";
//    }
//    echo "<script type='application/ld+json'>schema-plugin</script>";
//}

//add_action('wp_head', 'schema');