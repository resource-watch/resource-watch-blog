<?php
/**
 * Author:      Themeone
 * Author URI:  https://theme-one.com
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

if (!class_exists('TOMB_Class')) {
	
	class TOMB_Class {
		
		/**
		* Cloning disabled
		* @since 1.0.0
		*/
		private function __clone() {
		}
	
		/**
		* Serialization disabled
		* @since 1.0.0
		*/
		public function __sleep() {
		}
	
		/**
		* De-serialization disabled
		* @since 1.0.0
		 */
		private function __wakeup() {
		}
		
		/**
	 	* Themeone Metabox Constructor
		* @since 1.0.0
	 	*/
		public function __construct() {
			
			if (is_admin()) {
				$this->define_constants();
				$this->localize_plugin();
				$this->includes();
				$this->init_hooks();
			}
				
		}

		/**
		* Define Constants
		* @since 1.0.0
		*/
		private function define_constants() {
			
			// Plugin version
			if (!defined('TOMB_VERSION')) {
				define('TOMB_VERSION', '1.0');
			}
			
		}
		
		/**
		* Localize_plugin
		* @since 1.0.0
		*/
		public function localize_plugin() {
			
			load_plugin_textdomain(
				'tomb-text-domain',
				FALSE,
				TOMB_DIR . '/langs'
			);
			
		}

		/**
		* Include required core files for Backend.
		* @since 1.0.0
		*/
		public function includes() {
			
			// Load fields and processing functions
			require_once( TOMB_DIR . 'classes/tomb-taxonomy.php' );
			require_once( TOMB_DIR . 'classes/tomb-metabox.php' );
			require_once( TOMB_DIR . 'classes/tomb-fields.php' );
			
			// Load all fields files into the "fields" folder
			foreach (glob(TOMB_DIR . 'fields/*.php') as $file) {
				require_once $file;
			}
			
		}
		
		/**
		* Hook into actions and filters
		* @since 1.0.0
		* @modified 1.3.0
		*/
		public function init_hooks() {
			
			// load metaboxe framework assets css and scripts
			add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
			
		}

		/**
		* Enqueue main JS/CSS scripts
		* @since 1.0.0
		*/
		public function enqueue_scripts() {
			
			// Main Script
			wp_register_script('tomb-js', TOMB_URL . 'assets/js/tomb.js', array('jquery', 'media-upload', 'thickbox'), TOMB_VERSION);
			wp_enqueue_script('tomb-js');
			
			// jQuery UI (Wordpress core)
			wp_enqueue_script('jquery-effects-core');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('jquery-ui-droppable');
			
			// Color Picker (Wordpress core)
			wp_enqueue_script('wp-color-picker');
			
			// Styles
			wp_enqueue_style('tomb-css', TOMB_URL . 'assets/css/tomb.css');
			
			// Enqueue styles
			wp_enqueue_style('tomb-css');
			wp_enqueue_style('wp-color-picker');
			
		}
		
	}
	
	// Initialize Metabox framework
	new TOMB_Class;

}