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

if( ! function_exists('is_plugin_active') ) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php');
}

if ( ! class_exists( 'Schema' ) ) {
	final class Schema {

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		const version = '1.0.0';

		/**
		 * Class constructor
		 */
		public function __construct() {
			$this->define_constants();

            register_activation_hook( __FILE__, [ $this, 'activate' ] );

            add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
		}

        /**
         * Initialize a singleton instance
         *
         * @return false|self
         */
        public static function init() {
            static $instance    = false;

            if ( ! $instance ) {
                $instance       = new self();
            }
            return $instance;
        }

		/**
		 * Define the required plugin constants
		 *
		 * @return void
		 */
		public function define_constants() {
			define( 'SCHEMAX_VERSION', self::version );
			define( 'SCHEMAX_PATH', __FILE__ );
		}

		/**
		 * Do stuff upon plugin activation
		 *
		 * @return void
		 */
		public function activate() {

            $installed = get_option( 'schemax_installed' );

            if ( ! $installed ) {
                update_option( 'schemax_installed', time() );
            }

            update_option( 'schemax_version', SCHEMAX_VERSION );


			add_action( 'init', [ $this, 'init_plugin' ] );
		}

		/**
		 * Initialize the plugin
		 *
		 * @return void
		 */
		public function init_plugin() {
            new Schema\Init();
		}

	}
}

/**
 * Initialize the main plugin
 *
 * @return \Schemax
 */
function schemax() {
    return Schema::init();
}
schemax();