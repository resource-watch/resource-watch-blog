<?php
/*
	Plugin Name: Maintenance
	Plugin URI: http://wordpress.org/plugins/maintenance/
	Description: Take your website for maintenance away from public view. Use maintenance plugin if your website is in development or you need to change a few things, run an upgrade. Make it only accessible by login and password. Plugin has a options to add a logo, background, headline, message, colors, login, etc. Extended PRO with more features version is available for purchase.
	Version: 3.6.1
	Author: fruitfulcode
	Author URI: http://fruitfulcode.com
	License: GPL2
*/
/*  Copyright 2013  Fruitful Code  (email : mail@fruitfulcode.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class maintenance {
		function __construct() {
			global	$maintenance_variable;
			$maintenance_variable = new stdClass;

			add_action( 'plugins_loaded', array( &$this, 'constants'), 	1);
			add_action( 'plugins_loaded', array( &$this, 'lang'),		2);
			add_action( 'plugins_loaded', array( &$this, 'includes'), 	3);
			add_action( 'plugins_loaded', array( &$this, 'admin'),	 	4);

			
			register_activation_hook  ( __FILE__, array( &$this,  'mt_activation' ));
			register_deactivation_hook( __FILE__, array( &$this, 'mt_deactivation') );
			
			add_action('template_include', array( &$this, 'mt_template_include'), 999999);
			add_action('wp_logout',	array( &$this, 'mt_user_logout'));
			add_action('init', 		array( &$this, 'mt_admin_bar'));
			add_action('init', 		array( &$this, 'mt_set_global_options'), 1);
		}
		
		function constants() {
			define( 'MAINTENANCE_VERSION', '3.4.1' );
			define( 'MAINTENANCE_DB_VERSION', 1 );
			define( 'MAINTENANCE_WP_VERSION', get_bloginfo( 'version' ));
			define( 'MAINTENANCE_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
			define( 'MAINTENANCE_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
			define( 'MAINTENANCE_INCLUDES', MAINTENANCE_DIR . trailingslashit( 'includes' ) );
			define( 'MAINTENANCE_LOAD',     MAINTENANCE_DIR . trailingslashit( 'load' ) );
		}
		
		function mt_set_global_options() {
			global $mt_options;
			$mt_options =  mt_get_plugin_options(true);		
		}
		
		function lang() {
			load_plugin_textdomain( 'maintenance', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );		
		}	
		
		function includes() {
			require_once( MAINTENANCE_INCLUDES . 'functions.php' ); 
			require_once( MAINTENANCE_INCLUDES . 'update.php' ); 
			require_once( MAINTENANCE_DIR 	   . 'load/functions.php' ); 
		}
		
		function admin() {
			if ( is_admin() ) {
				require_once( MAINTENANCE_INCLUDES . 'admin.php' );
			}	
		}
		
		function mt_activation() {
			/*Activation Plugin*/
			self::mt_clear_cache();
		}
		
		function mt_deactivation() {
			/*Deactivation Plugin*/
			self::mt_clear_cache();
		}
		
		public static function mt_clear_cache() {
			global $file_prefix;
			if ( function_exists( 'w3tc_pgcache_flush' ) ) w3tc_pgcache_flush(); 
			if ( function_exists( 'wp_cache_clean_cache' ) ) wp_cache_clean_cache( $file_prefix, true );
		}	
		
		function mt_user_logout() { 
			wp_safe_redirect(get_bloginfo('url'));
			exit; 
		}

		function mt_template_include($original_template) {
            $original_template = load_maintenance_page($original_template);
            return $original_template;
		}
		
		function mt_admin_bar() {
			add_action('admin_bar_menu', 'maintenance_add_toolbar_items', 100);
			if (!is_super_admin() ) {
				$mt_options = mt_get_plugin_options(true);
				if (isset($mt_options['admin_bar_enabled']) && is_user_logged_in()) { 
					add_filter('show_admin_bar', '__return_true');  																	 
				} else {
					add_filter('show_admin_bar', '__return_false');  																	 
				}
			}	
		}
}

$maintenance = new maintenance();

?>
