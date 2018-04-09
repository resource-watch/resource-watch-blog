<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @link      https://codecanyon.net/item/the-grid-responsive-wordpress-grid-plugin/13306812
 * @copyright 2015 Themeone
 *
 * @wordpress-plugin
 * Plugin Name:  The Grid
 * Plugin URI:   http://www.theme-one.com/the-grid/
 * Description:  The Grid - Create advanced grids for any post type with endless possibilities (no programming knowledge required)
 * Version:      2.6.0
 * Author:       Themeone
 * Author URI:   http://www.theme-one.com/
 * Text Domain:  tg-text-domain
 * Domain Path:  /langs
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

// Initialize if The Grid Plugin does not exist
if (!class_exists('The_Grid_Plugin')) {

	class The_Grid_Plugin {
		
		/**
		* Plugin Version
		*
		* @since 1.0.0
		* @access public
		*
		* @var string
		*/
		public $plugin_version = '2.6.0';
		
		/**
		* Plugin Slug
		*
		* @since 1.0.0
		* @access public
		*
		* @var string
		*/
		public $plugin_slug = 'the_grid';
		
		/**
		* Plugin Prefix
		*
		* @since 1.0.0
		* @access public
		*
		* @var string
		*/
		public $plugin_prefix = 'the_grid_';
		
		/**
		* Cloning disabled
		* @since 1.0.0
		*/
		private function __clone() {
		}
	
		/**
		* De-serialization disabled
		* @since 1.0.0
		*/
		private function __wakeup() {
		}
	
		/**
	 	* The Grid Constructor
		* @since 1.0.0
	 	*/
		public function __construct() {
			
			$this->define_constants();
			$this->includes();
			$this->init_hooks();
			
		}

		/**
		* Define The Grid Constants
		* @since 1.0.0
		*/
		public function define_constants() {
			
			define('TG_PLUGIN', __FILE__ );
			define('TG_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
			define('TG_PLUGIN_URL', str_replace('index.php','',plugins_url( 'index.php', __FILE__ )));
			define('TG_VERSION', $this->plugin_version);
			define('TG_SLUG',$this->plugin_slug);
			define('TG_PREFIX', $this->plugin_prefix);
			
			// For Themeone metabox framework (TOMB)
			if (!defined('TOMB_DIR')) {
				define('TOMB_DIR', TG_PLUGIN_PATH . 'includes/metabox/');
			}
			if (!defined('TOMB_URL')) {
				define('TOMB_URL', TG_PLUGIN_URL . 'includes/metabox/');
			}
			
		}
		
		/**
		* Include required core files for Backend/Frontend.
		* @since 1.0.0
		* @modified 1.7.0
		*/
		public function includes() {

			// Aqua Resizer Class
			require_once(TG_PLUGIN_PATH . '/includes/aqua-resizer.class.php');
			
			// Attachment taxonomy
			require_once(TG_PLUGIN_PATH . '/includes/media-taxonomies.php');
			
			// Grid base Class (main functionnalities)
			require_once(TG_PLUGIN_PATH . '/includes/the-grid-base.class.php');
			
			// Load skins classes
			require_once(TG_PLUGIN_PATH . '/includes/item-skin.class.php');
			require_once(TG_PLUGIN_PATH . '/includes/preloader-skin.class.php');
			require_once(TG_PLUGIN_PATH . '/includes/navigation-skin.class.php');
			require_once(TG_PLUGIN_PATH . '/includes/item-animation.class.php');
			
			// Grid custom table Class
			require_once(TG_PLUGIN_PATH . '/includes/custom-table.class.php');
			
			// Post like class
			require_once(TG_PLUGIN_PATH . '/includes/post-like/post-like.php');
			
			// Deprecated class to retrieve item element
			require_once(TG_PLUGIN_PATH . '/includes/deprecated/the-grid-element.class.php');
			
			// Load frontend classes
			require_once(TG_PLUGIN_PATH . '/frontend/the-grid-init.class.php');
			require_once(TG_PLUGIN_PATH . '/includes/first-media.class.php');
			
			// Load backend classes
			if (is_admin()) {
				require_once(TG_PLUGIN_PATH . '/includes/element-animation.class.php');
				require_once(TG_PLUGIN_PATH . '/includes/envato-api.class.php');
				require_once(TG_PLUGIN_PATH . '/includes/update-plugin.class.php');
				require_once(TG_PLUGIN_PATH . '/includes/custom-fields.class.php');
				require_once(TG_PLUGIN_PATH . '/backend/admin-init.php');
				require_once(TG_PLUGIN_PATH . '/includes/wpml.class.php');	
			}
			
			// Register shortcode & add Tinymce button/popup & add Visual Composer element
			require_once(TG_PLUGIN_PATH . '/backend/admin-shortcode.php');

		}
		
		/**
		* Hook into actions and filters
		* @since 1.0.0
		* @modified 1.9.0
		*/
		public function init_hooks() {

			// Load plugin text domain
			add_action( 'plugins_loaded', array( &$this, 'localize_plugin' ) );
			// Register The Grid post type
			add_action( 'init', array( &$this, 'register_post_type' ) );
			// Add post format for any kind of post type
			add_action( 'init', array( &$this, 'post_formats' ) );
			// Register The Grid additionnal image sizes
			add_action( 'after_setup_theme', array( &$this, 'add_image_size' ) );
			
			// Allow new css properties for wp_kses
			add_filter( 'safe_style_css', array( &$this, 'allowed_css_rules' ) );
			// Add plugin edit button in plugin list page
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( &$this, 'action_links' ), 10, 4 );
			
			// Make changes on plugin activation
			register_activation_hook( __FILE__, array( &$this, 'plugin_activated' ) );
			// Make changes on plugin deactivation
			register_deactivation_hook( __FILE__, array( &$this, 'plugin_deactivated' ) );

		}
		
		/**
		* Localize_plugin
		* @since 1.0.0
		*/
		public function localize_plugin() {
			
			load_plugin_textdomain(
				'tg-text-domain',
				false,
				plugin_basename( dirname( __FILE__ ) ) . '/langs'
			);
			
		}
		
		/**
		* Register post type
		* @since 1.0.0
		* @modified 1.5.0
		*/
		public function register_post_type() {	
			
			// Set labels for The_Grid post type
			$labels = array(
				'name'          => __( 'The Grid', 'taxonomy general name', 'tg-text-domain'),
				'singular_name' => __( 'The_Grid', 'tg-text-domain'),
				'search_items'  => __( 'Search The_Grid', 'tg-text-domain'),
				'all_items'     => __( 'All The_Grid', 'tg-text-domain'),
				'parent_item'   => __( 'Parent The_Grid', 'tg-text-domain'),
				'edit_item'     => false,
				'update_item'   => false,
				'add_new_item'  => false,
				'menu_name'     => __( 'The Grid', 'tg-text-domain')
			 );
			 
			 // Set main arguments for The_Grid post type 
			 $args = array(
					'labels'          => $labels,
					'singular_label'  => __('The Grid', 'tg-text-domain'),
					'public'          => false,
					'capability_type' => 'post',
					'query_var'       => false,
					'rewrite'         => false,
					'show_ui'         => false,
					'show_in_menu'    => false,
					'hierarchical'    => false,
					'menu_position'   => 10,
					'menu_icon'       => 'dashicons-slides',
					'supports'        => false,
					'rewrite'         => array(
						'slug' => $this->plugin_slug,
						'with_front' => false
					),
			);
			
			// Register The_Grid post type
			register_post_type( $this->plugin_slug, $args );
			
			// Remove unecessary post type field
			remove_post_type_support( $this->plugin_slug, 'title' );
			remove_post_type_support( $this->plugin_slug, 'editor' );
			
		}
		
		/**
		* Add post formats to any post types
		* @since 1.0.5
		*/
		public function post_formats() {
			
			$post_format = get_option('the_grid_post_formats', false);
			
			// Add post formats support if option enable in global settings
			if ( $post_format ) {
				
				// Post formats supported by The Grid Plugin
				add_theme_support('post-formats', array('gallery', 'video', 'audio', 'quote', 'link'));
				
				// Retireve all post types
				$post_types = The_Grid_Base::get_all_post_types();
				
				// Remove post format for attachment post type
				unset($post_types['attachment']);
				
				foreach ($post_types as $slug => $name) {

					add_post_type_support( $slug, 'post-formats' );
					register_taxonomy_for_object_type( 'post_format', $slug );

				}
				
			}
			
		}

		/**
		* Add image sizes to Wordpress
		* @since 1.0.0
		* @modified 1.0.7
		*/
		public function add_image_size() {
			
			// Default image sizes
			$def = array(
				'w' => array(500, 500, 1000, 1000, 500),
				'h' => array(500, 1000, 500, 1000, 99999),
				'c' => array(true, true, true, true, '')
			);
			
			// Add image sizes with values from global settings
			for ($i = 0; $i <= 4; $i++) {
				
				$w = get_option('the_grid_size'. ($i+1) .'_width', $def['w'][$i]);
				$h = get_option('the_grid_size'. ($i+1) .'_height', $def['h'][$i]);
				$c = get_option('the_grid_size'. ($i+1) .'_crop', $def['c'][$i]);
				
				if ($w > 0 || $h > 0) {
					add_image_size('the_grid_size'. ($i+1), $w, $h, $c);
				}
				
			}
			
		}
		
		/**
		* Allow new css rules for wp_kses (for custom css/html attr in skin builder)
		* @since 1.9.0
		*/
		public function allowed_css_rules($allowed_attr) {

			if (!is_array($allowed_attr)) {
				$allowed_attr = array();
			}

			$allowed_attr[] = 'box-shadow';
		
			return $allowed_attr;
			
		}

		/**
		* Add edit link on plugin activation
		* @since 1.0.0
		* @modified 1.1.0
		*/
		public function action_links($links) {
			
			// Unset default edit button
			unset($links['edit']);
			
			// Add custom edit button
			$mylinks = array(
 				'<a href="' . admin_url( 'admin.php?page=the_grid' ) . '">'. __('Edit', 'tg-text-domain') .'</a>',
 			);
			
			// Return new adit action
			return array_merge($links, $mylinks);
			
		}

		/**
		* Make changes after important update on plugin activation
		* @since 1.2.0
		* @modified 1.7.0
		*/
		public function plugin_activated() {

			// Delete The Grid cache to prevent any issues due to changes
			The_Grid_Base::delete_transient('tg_grid');
			// Create custom table for skin builder
			The_Grid_Custom_Table::create_tables(false, true);

		}
		
		/**
		* Make changes after important update on plugin activation
		* @since 1.2.0
		* @modified 1.7.0
		*/
		public function plugin_deactivated() {

			// Delete The Grid cache to prevent any issues due to changes
			The_Grid_Base::delete_transient('tg_grid');

		}

	}
	
	// Initialize The Grid Plugin
	new The_Grid_Plugin;

}