<?php
/**
 * Plugin Name:  WP Avatar
 * Description: Allows you to use any photos uploaded into your Media Library as an avatar instead of using Gravatar.
 * Plugin URI: https://wordpress.org/plugins/wp-avatar/
 * Version: 0.1.3
 * Author: Tomiup
 * Author URI: http://tomiup.com/
 * Requires at least: 4.8
 * Tested up to: 4.9
 * License: GPLv3
 *
 * Text Domain: wp-avatar
 * Domain Path: /languages/
 */
if ( ! class_exists( 'WPA' ) ) {
	class WPA {

		/**
		 * WPA constructor.
		 */
		function __construct() {

			if ( ! defined( 'WPA_PATH' ) ) {
				define( 'WPA_PATH', plugin_dir_path( __FILE__ ) );
			}
			if ( ! defined( 'WPA_URL' ) ) {
				define( 'WPA_URL', plugin_dir_url( __FILE__ ) );
			}

			add_action( 'init', array( $this, 'load_textdomain' ) );

			$this->libs();
			$this->includes();

		}

		/**
		 * Load plugin textdomain.
		 */

		function load_textdomain() {
			load_plugin_textdomain( 'wp-avatar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}


		/**
		 * Inclide libraries
		 */
		function libs() {
			include_once WPA_PATH . 'inc/libs/aq_resize.php';
		}

		/**
		 * Include features
		 */
		function includes() {
			include_once WPA_PATH . 'inc/plugin-core.php';
			include_once WPA_PATH . 'inc/plugin-functions.php';
		}

	}

	new WPA();
}