<?php
/**
 * Schemax
 *
 * @package           Schemax
 * @author            Nymul Islam
 * @copyright         2024 Nymul Islam
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Schemax
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Description of the plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Nymul Islam
 * Author URI:        https://example.com/plugin-name
 * Text Domain:       Schemax
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/my-plugin/
 */

/**
{Schemax} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Schemax} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Schemax}. If not, see {URI to Plugin License}.
*/

defined('ABSPATH') or die('Hay, You can not access the area');

require_once __DIR__ . '/vendor/autoload.php';

use Schema\Src;

if ( ! class_exists( 'Schema' ) ) {  // Check if the 'Schema' class does not exist
	final class Schema {

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		const version = '1.0.0';

		/**
		 * Class construcotr
		 */
		public function __construct() {
			$this->define_constants();
            add_action( 'plugins_loaded', [ $this, 'activate' ] );
//			register_activation_hook( __FILE__, [ $this, 'activate' ] );
		}


		/**
		 * Define the required plugin constants
		 *
		 * @return void
		 */
		public function define_constants() {
			define( 'SCHEMA_PLUGIN_PATH', __FILE__ );
		}
		/**
		 * Do stuff upon plugin activation
		 *
		 * @return void
		 */
		public function activate() {

			flush_rewrite_rules();

			add_action( 'init', [ $this, 'init_plugin' ] );
		}

		/**
		 * Initialize the plugin
		 *
		 * @return void
		 */
		public function init_plugin() {

			$installer = new Schema\Init();
            $installer->run();
		}

	}
	function schema() {
		return new Schema();
	}
	schema();
}