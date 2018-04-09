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

class The_Grid_preview extends The_Grid {

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
	* _construct disabled
	* @since 1.0.0
	*/
	public function __construct($settings) {
		
		$this->grid_data = $settings;
				
	}

	/**
	* Grid preview ajax
	* @since 1.0.0
	*/
	public function grid_preview_callback() {
		
		global $tg_grid_preview;
		
		// set preview mode
		$tg_grid_preview = true;
		
		// get grid preview settings
		$this->get_preview_data();
		// normalize grid data
		$this->normalize_data();
		// get grid items
		$this->get_items();
		// get grid styles
		$this->get_styles();
		// get grid layout
		$output = $this->get_layout();
		
		// unset grid preview mode
		$tg_grid_preview = false;
		
		// return the grid
		return $output;
		
	}
	
	/**
	* Retrieve grid data
	* @since 1.0.0
	*/
	public function get_preview_data() {
		
		$post_ID   = $this->grid_data['post_ID'];
		$meta_data = $this->grid_data['meta_data'];

		if (!isset($post_ID) || empty($post_ID)) {
			throw new Exception(__( 'Sorry, an unexpected errors occurred while parsing data.', 'tg-text-domain'));
		}
		
		// assign dynamic grid ID generated on admin page
		$meta_data['ID'] = 'grid-'.$post_ID;

		foreach ($meta_data as $data => $val) {
			$data = str_replace('the_grid_', '', $data);
			$this->grid_data[$data] = wp_unslash($val);
		}
		
	}
	
	/**
	* Normalize grid data
	* @since 1.0.0
	*/
	public function normalize_data() {
		
		try {
			
			// get grid data
			$data_class = new The_Grid_Data($this->grid_data['name']);
			$this->grid_data = $data_class->normalize_data($this->grid_data);
			
		} catch (Exception $e) {
			
			// show error message if throw
			throw new Exception($e->getMessage());
			
		}
		
	}
	
	/**
	* Retrieve grid items
	* @since 1.0.0
	*/
	public function get_items() {
		
		try {
			
			// get grid items
			$source_class = new The_Grid_Source($this->grid_data);
			$this->grid_items = $source_class->get_items();
			$this->grid_data  = $source_class->get_data();
			
		} catch (Exception $e) {
			
			// show error message if throw
			throw new Exception($e->getMessage());
			
		}

	}
	
	/**
	* Retrieve grid styles
	* @since 1.0.0
	*/
	public function get_styles() {
		
		try {
			
			// get grid styles
			$styles_class = new The_Grid_Styles($this->grid_data);
			$this->grid_data = $styles_class->styles_processing();
			
		} catch (Exception $e) {
			
			// show error message if throw
			throw new Exception($e->getMessage());
			
		}
		
	}
	
	/**
	* Retrieve grid layout
	* @since 1.0.0
	*/
	public function get_layout() {
		
		try {
			
			// retrive entire grid layout
			$layout_class = new The_Grid_Layout($this->grid_data, $this->grid_items);
			return $layout_class->output();
		
		} catch (Exception $e) {
			
			// show error message if throw
			throw new Exception($e->getMessage());
			
		}
	
	}
	
}

// add filter to add item settings button on preview mode
add_filter('tg_before_grid_item_end', 'add_item_settings', 10, 2);

function add_item_settings($output, $args) {
	
	global $tg_grid_preview;
	
	// if source type is post type
	if ($tg_grid_preview == true && $args['grid_data']['source_type'] == 'post_type') {
		
		$post_ID = $args['ID'];
			
		// retrieve WPML query lang to retrieve right metadata
		$WPML = new The_Grid_WPML();
			
		// build markup for item setting and hide item buttons
		$output  = '<div class="tg-item-hidden-overlay"></div>';
		$output .= '<div class="tg-item-settings" data-id="'.esc_attr($post_ID).'" data-action="'.admin_url( 'post.php?post='.esc_attr($post_ID).'&action=edit'.$WPML->WPML_post_query_lang($post_ID)).'">';
			$output .= '<span>'.__( 'Loading', 'tg-text-domain' ).'</span>';
		$output .= '</div>';
		$output .= '<div class="tg-item-exclude" data-id="'.esc_attr($post_ID).'">';
			$output .= '<span class="tg-item-hide">'.__( 'Hide item', 'tg-text-domain' ).'</span>';
			$output .= '<span class="tg-item-show">'.__( 'Show item', 'tg-text-domain' ).'</span>';
		$output .= '</div>';

	}
		
	return $output;
	
}