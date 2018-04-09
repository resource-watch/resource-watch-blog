<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

class The_Grid_Init {
	
	/**
	* Grid js var
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	protected $grid_options;
	
	/**
	* Debug mode option
	*
	* @since 1.0.0
	* @access public
	*
	* @var boolean
	*/
	protected $debug_mode = false;
	
	/**
	* The Grid Init Constructor
	* @since 1.0.0
	*/
	public function __construct() {
		
		$this->debug_mode = get_option('the_grid_debug', false);
		$this->includes();
		$this->init_hooks();
		
	}
	
	/**
	* Include required files for Backend/Frontend.
	* @since 1.0.0
	*/
	public function includes() {
		
		// Grid Frontend class
		require_once(TG_PLUGIN_PATH . '/frontend/the-grid.class.php');
		require_once(TG_PLUGIN_PATH . '/frontend/the-grid-data.class.php');
		require_once(TG_PLUGIN_PATH . '/frontend/the-grid-source.class.php');
		require_once(TG_PLUGIN_PATH . '/frontend/the-grid-loop.class.php');
		require_once(TG_PLUGIN_PATH . '/frontend/the-grid-style.class.php');
		require_once(TG_PLUGIN_PATH . '/frontend/the-grid-layout.class.php');
		require_once(TG_PLUGIN_PATH . '/frontend/the-grid-item.class.php');
		require_once(TG_PLUGIN_PATH . '/frontend/the-grid-element.class.php');
		require_once(TG_PLUGIN_PATH . '/frontend/the-grid-ajax.class.php');
		
		// Source types
		require_once(TG_PLUGIN_PATH . '/includes/source-type/post-type.class.php');
		require_once(TG_PLUGIN_PATH . '/includes/source-type/instagram.class.php');
		require_once(TG_PLUGIN_PATH . '/includes/source-type/youtube.class.php');
		require_once(TG_PLUGIN_PATH . '/includes/source-type/vimeo.class.php');
		require_once(TG_PLUGIN_PATH . '/includes/source-type/facebook.class.php');
		require_once(TG_PLUGIN_PATH . '/includes/source-type/twitter.class.php');
		require_once(TG_PLUGIN_PATH . '/includes/source-type/flickr.class.php');
		require_once(TG_PLUGIN_PATH . '/includes/source-type/rss.class.php');
		
		// if NextGen plugin activated
		/*if (class_exists('nggdb')) {
			require_once(TG_PLUGIN_PATH . '/includes/source-type/nextgen.class.php');
		}*/
		
	}
	
	/**
	* Register main hooks
	* @since 1.0.0
	*/
	public function init_hooks() {
		
		// Load admin style sheet and JavaScript.
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 100);
		// for grid preview
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 100);
		// dequeue Mediaelement & register new styles
		add_action('wp_enqueue_scripts', array($this, 'mediaelement_styles'), 100);
		add_action('admin_enqueue_scripts', array($this, 'mediaelement_styles'), 100);

	}
		
	/**
	* Set main var from global settings panel
	* @since 1.0.0
	*/
	public function global_settings() {
		
		// check if it's a mobile
		$is_mobile = (wp_is_mobile()) ? true : null;
		
		// set Medialement options
		$mediaelement = (!$is_mobile) ? get_option('the_grid_mediaelement', '') : null;
		$mediaelement_ex = get_option('the_grid_mediaelement_css', '');
		
		// retrieve custom meta key to sort
		$meta_data = get_option('the_grid_custom_meta_data', array());
		$meta_data = (isset($meta_data) && !empty($meta_data) && json_decode($meta_data) != null) ? json_decode($meta_data, true) : null;
		
		// store array of option to localize
		$this->grid_options = array(
			'url'               => admin_url('admin-ajax.php'),
			'nonce'             => wp_create_nonce('the_grid_load_more'),
			'is_mobile'         => $is_mobile,
			'mediaelement'      => $mediaelement,
			'mediaelement_ex'   => ($mediaelement && $mediaelement_ex) ? true : null,
			'lightbox_autoplay' => get_option('the_grid_ligthbox_autoplay', ''),
			'debounce'          => get_option('the_grid_debounce', ''),
			'meta_data'         => $meta_data,
			'main_query'        => $this->get_main_wp_query()
		);
		
	}
	
	/**
	* Retrieve main WP Query
	* @since 1.5.0
	*/
	public function get_main_wp_query() {
		
		global $wp_query;
		
		if ($wp_query->is_main_query() && !is_admin()) {
			return $wp_query->query_vars;
		}
		
	}
		
	/**
	* Enqueue scripts on front-end
	* @since 1.0.0
	*/
	public function enqueue_scripts($options) {
		
		global $post;
		$global_library = get_option('the_grid_global_library', true);

    	if($global_library || ($post && has_shortcode($post->post_content, 'the_grid'))) {
			$this->global_scripts();
			$this->global_styles();
		}
		
	}
	
	/**
	* Enqueue scripts in Admin
	* @since 1.0.0
	*/
	public function admin_enqueue_scripts() {

		$screen = get_current_screen();

		if (strpos($screen->id, 'admin_page_the_grid_settings') !== false || strpos($screen->id, 'the_grid_skins_overview') !== false) {
			$this->global_scripts();
			$this->global_styles();
		}
		
	}
	
	/**
	* Register Globlad style and inline css from Global Settings
	* @since 1.0.0
	*/
	public function global_styles() {

		if ($this->debug_mode) {
			wp_enqueue_style('the-grid', TG_PLUGIN_URL . 'frontend/assets/css/the-grid.css', array(), TG_VERSION);
		} else {
			wp_enqueue_style('the-grid', TG_PLUGIN_URL . 'frontend/assets/css/the-grid.min.css', array(), TG_VERSION);
		}
		
		// then add inline styles (from global settings panel)
		$base      = new The_Grid_Base();
		$bg_color  = get_option('the_grid_ligthbox_background', 'rgba(0,0,0,0.8)');
		$txt_color = get_option('the_grid_ligthbox_color', '#ffffff');
		$custom_css = '
        	.tolb-holder {
            	background: '.$bg_color.';
			}
			.tolb-holder .tolb-close,
			.tolb-holder .tolb-title,
			.tolb-holder .tolb-counter,
			.tolb-holder .tolb-next i,
			.tolb-holder .tolb-prev i {
            	color: '.$txt_color.';
			}
			.tolb-holder .tolb-load {
			    border-color: '.$base->HEX2RGB($txt_color,0.2).';
    			border-left: 3px solid '.$txt_color.';
			}
        ';

        wp_add_inline_style('the-grid', $base->compress_css($custom_css));
		
	}
	
	/**
	* Enqueue main JS scripts
	* @since 1.0.0
	*/
	public function global_scripts() {
		
		// retrieve global js var (from global settings panel)
		$this->global_settings();
		
		// enqueue easing in case if missing in a theme
		wp_enqueue_script('jquery-effects-core');
		
		// enqueue mediaelement if enable (to be sure in case)
		if ($this->grid_options['mediaelement']) {
			wp_enqueue_script('mediaelement');
		}
		
		// to debug script with native Wordpress functionnality (SCRIPT_DEBUG)
		if ($this->debug_mode) {
			wp_enqueue_script('the-grid-layout', TG_PLUGIN_URL . 'frontend/assets/js/the-grid-layout.js', 'jquery', TG_VERSION, TRUE );
			wp_enqueue_script('the-grid-slider', TG_PLUGIN_URL . 'frontend/assets/js/the-grid-slider.js', 'jquery', TG_VERSION, TRUE );
			wp_enqueue_script('the-grid', TG_PLUGIN_URL . 'frontend/assets/js/the-grid.js', 'jquery', TG_VERSION, TRUE );
		} else {
			wp_enqueue_script('the-grid', TG_PLUGIN_URL . 'frontend/assets/js/the-grid.min.js', 'jquery', TG_VERSION, TRUE );		
		}
		// localize from main var
		wp_localize_script('the-grid', 'tg_global_var', $this->grid_options);
		
	}
	
	/**
	* Add medialement if set in Global settings
	* @since 1.0.0
	*/
	public function mediaelement_styles() {

		global $wp_version;

		if ($this->grid_options['mediaelement'] && $this->grid_options['mediaelement_ex']) {
			
			wp_dequeue_style('wp-mediaelement');       // native Wordpress
			wp_deregister_style('wp-mediaelement');    // native Wordpress
			wp_dequeue_style('mediaelementplayer');    // alternative Theme
			wp_deregister_style('mediaelementplayer'); // alternative Theme	
				
			if ($this->debug_mode) {
				if ( version_compare( $wp_version, '4.9', '>=' ) ) {
					wp_enqueue_style('wp-mediaelement', TG_PLUGIN_URL . 'frontend/assets/css/wp-mediaelement.css', array(), TG_VERSION);
				} else {
					wp_enqueue_style('wp-mediaelement', TG_PLUGIN_URL . 'frontend/assets/css/wp-mediaelement-old.css', array(), TG_VERSION);
				}
				
			} else {
				if ( version_compare( $wp_version, '4.9', '>=' ) ) {
					wp_enqueue_style('wp-mediaelement', TG_PLUGIN_URL . 'frontend/assets/css/wp-mediaelement.min.css', array(), TG_VERSION);
				} else {
					wp_enqueue_style('wp-mediaelement', TG_PLUGIN_URL . 'frontend/assets/css/wp-mediaelement-old.min.css', array(), TG_VERSION);
				}
			}
			
		}
		
	}
	
}

new The_Grid_Init();