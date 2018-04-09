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

class The_Grid_Shortcode {
	
 	protected $shortcode_tag = TG_SLUG;
	
	/**
	* Initialization
	* @since 1.0.0
	*/
	function __construct(){
		
		// remove empty <p></p> tags
		add_filter('the_content', array($this, 'the_content_filter'));
		// register shortcode 
		add_shortcode('the_grid', array($this, 'register_shortcode'));
		// add tinymce button and popup
		if (is_admin()){
			add_action('admin_head', array($this, 'admin_head'));
			add_action('admin_enqueue_scripts', array($this , 'admin_enqueue_scripts'));
		}
		
	}
	
	/**
	* Filter the content to remove empty p tags from the_grid shortcode
	* BitFade Method To clean shortcode (http://themeforest.net/forums/thread/how-to-add-shortcodes-in-wp-themes-without-being-rejected/98804?page=4#996848)
	* @since 1.0.0
	*/
	public function the_content_filter($content) {
		
		$block = array('the_grid');

		if (count($block) === 0) {
			return $content;
		}

		$block = join("|",$block);
		// opening tag
		$rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
		// closing tag
		$rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);
		
		return $rep;
		
	}
	
	/**
	* Register Shortcode
	* @since 1.0.0
	*/
	public function register_shortcode($atts, $content = null){

		extract(shortcode_atts(array(
			'name' => '',
	    ), $atts));
		
		return The_Grid($name);
				
	}

	/**
	* Register Shortcode button
	* @since 1.0.0
	* @modified 1.1.0
	*/
	function admin_head() {
		
		// check user permissions
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
			return;
		}
		
		// check if WYSIWYG is enabled
		if (get_user_option('rich_editing') == 'true') {
			
			$base = new The_Grid_Base();
			$grid_list = $base->get_grid_shortcode_list();
			
			echo '<script type="text/javascript">';
				echo 'var tg_sc_title     = "'.__( 'The Grid - Shortcode Generator', 'tg-text-domain').'";';
				echo 'var tg_sc_tooltip   = "'.__( 'The Grid - Shortcode Generator', 'tg-text-domain').'";';
				echo 'var tg_list_label   = "'.__( 'Select a predefined Grid', 'tg-text-domain').'";';
				echo 'var tg_list_tooltip = "'.__( 'Select the grid you want', 'tg-text-domain').'";';
				echo 'var tg_but_label    = "'.__( 'Currently, you don\'t have any grid!', 'tg-text-domain').'";';
				echo 'var tg_but_tooltip  = "'.__( 'Select the grid you want', 'tg-text-domain').'";';
				echo 'var tg_but_text     = "'.__( 'Create a Grid', 'tg-text-domain').'";';
				echo 'var tg_but_url      = "'.admin_url( 'post-new.php?post_type=the_grid').'";';
				echo 'var tg_names      = \''.$grid_list.'\'';
			echo '</script>';
		
			add_action( 'admin_enqueue_scripts', array( $this, 'localize_script' ) );
			add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
			add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
		}
		
	}

	/**
	* mce_external_plugins / Tinymce plugin
	* @since 1.0.0
	*/
	function mce_external_plugins($plugin_array) {
		
		$plugin_array[$this->shortcode_tag] = plugins_url( 'assets/js/admin-shortcode.js' , __FILE__ );
		return $plugin_array;
		
	}

	/**
	* mce_external_plugins / Tinymce button
	* @since 1.0.0
	*/
	function mce_buttons($buttons) {
		
		array_push( $buttons, $this->shortcode_tag);
		return $buttons;
		
	}

	/**
	* Admin_enqueue_scripts
	* @since 1.0.0
	* @modified 1.1.0
	*/
	function admin_enqueue_scripts(){
		
		wp_enqueue_style('tg_shortcode_css', TG_PLUGIN_URL .'backend/assets/css/admin-shortcode.css' );
		wp_enqueue_script('tg_shortcode_js', TG_PLUGIN_URL . 'backend/assets/js/admin-grid-list.js', array('jquery'), TG_VERSION);
		
	}
	
}

new The_Grid_Shortcode();

// Register The Grid for Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
	
	// if vc_add_shortcode_param exists (>= v4.7.4)
	if (function_exists('vc_add_shortcode_param')) {
		
		// create grid list selector shortcode name
		vc_add_shortcode_param( 'grid_list', 'grid_list_settings_field');
		function grid_list_settings_field($settings, $value) {
			
			$base = new The_Grid_Base();
			echo $base->get_grid_shortcode_list($value);
			
			echo '<script type="text/javascript">';
				echo '
				var value = jQuery(".vc_ui-panel-content-container .tg-grid-shortcode-value").val();
				if (value) {
					jQuery(".vc_ui-panel-content-container .tg-list-item-holder .tg-list-item[data-name=\'"+value+"\']").addClass("selected");
				} else {
					var $grid = jQuery(".vc_ui-panel-content-container .tg-list-item-holder .tg-list-item:first-child");
					$grid.addClass("selected");
					jQuery(".vc_ui-panel-content-container .tg-grid-shortcode-value").val($grid.data("name"));
				}
				';
			echo '</script>';
			
		}

		// add Visual Composer element to VC Popup List Elements
		add_action('vc_before_init', 'the_grid_VC');
		function the_grid_VC() {
			
			vc_map( array(
				'name' => __('The Grid', 'tg-text-domain'),
				'description' => __( 'Add a predefined Grid', 'tg-text-domain'),
				'base' => 'the_grid',
				'icon' => 'the-grid-vc-icon',
				'category' => __('The Grid', 'tg-text-domain'),
				'show_settings_on_create' => true,
				'js_view' => 'VcTheGrid',
				'front_enqueue_js' => TG_PLUGIN_URL.'/backend/assets/js/admin-vc.js',
				'params' => array(
					array(
						'type' => 'grid_list',
						'holder' => '',
						'heading' => 'name',
						'param_name' => 'name',
						'admin_label' => true,
						'value' => '',
						'save_always' => true,
					)
				)
			));	
			
		}
	
	}
	
}

// Register The Grid in Cornerstone element
add_action( 'cornerstone_register_elements', 'the_grid_register_element' );
function the_grid_register_element() {
	cornerstone_register_element( 'The_Grid_Element', 'the-grid', TG_PLUGIN_PATH . '/includes/cornerstone' );
}

// map Cornerstone icon
add_filter( 'cornerstone_icon_map', 'the_grid_cornerstone_icon' );
function the_grid_cornerstone_icon( $icon_map ) {
	$icon_map['the-grid'] = TG_PLUGIN_URL . 'includes/cornerstone/icon.svg';
	return $icon_map;
}
