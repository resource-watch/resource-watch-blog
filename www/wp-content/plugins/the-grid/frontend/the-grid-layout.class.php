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

class The_Grid_Layout {
		
	/**
	* Grid Data
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	public $grid_data;	
	
	/**
	* Grid Data (from grid settings)
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	public $grid_items;	
	
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
	* _construct
	* @since 1.0.0
	*/
	public function __construct($grid_data, $grid_items) {	

		$this->grid_data  = $grid_data;
		$this->grid_items = $grid_items;
		$this->data_processing();
	}
	
	/**
	* Data processing
	* @since 1.0.0
	*/
	public function data_processing() {

		// retrieve main css classes
		$this->grid_classes();
		// retrieve grid areas	
		$this->grid_areas();
		// generate html attribute for javascript
		$this->grid_attr();
	
	}
	
	/**
	* Generate main css class for the grid
	* @since 1.0.0
	*/
	public function grid_classes() {
		
		// main settings for the grid wrapper
		$grid_ID     = $this->grid_data['ID'];
		$grid_layout = $this->grid_data['layout'];
		$grid_style  = $this->grid_data['style'];
		$fullHeight  = $this->grid_data['full_height'];
		$preloader   = $this->grid_data['preloader'];
		$css_class   = $this->grid_data['css_class'];
		$nav_class   = $this->grid_data['navigation_style'];
		
		// set main wrapper classes
		$fullHeight  = ($grid_style == 'grid' && $fullHeight) ? 'full-height' : null;
		$load_class  = ($preloader) ? 'tg-grid-loading' : null;
		$css_classes = trim($css_class.' '.$load_class.' '.$nav_class.' '.$fullHeight);
		
		// set wrapper css class for template
		$this->grid_data['wrapper_css_class'] = $css_classes;
		
	}
	
	/**
	* Get & set main options for the grid data (For JS script)
	* @since 1.0.0
	*/
	public function grid_attr() {
			
		global $pagenow;

		// grid name
		$grid_name = $this->grid_data['name'];
		
		// grid type (masonry/grid)
		$grid_style = $this->grid_data['style'];
		
		// grid layout (horizontal/vertical)
		$grid_layout = $this->grid_data['layout'];
		
		// grid layout RTL
		$grid_rtl = $this->grid_data['rtl'];
		
		// item fitrow (Masonry)
		$item_fitrows = ($grid_layout == 'vertical' && $grid_style == 'masonry') ? $this->grid_data['item_fitrows'] : null;
		
		// grid filter combination
		$filter_comb = $this->grid_data['filter_combination'];
		
		// grid filter logic
		$filter_logic = $this->grid_data['filter_logic'];
		
		// grid filter on load
		$filters        = $this->grid_data['filter_onload'];
		$filters_sep    = ($filter_logic === 'OR') ? ',' : '';
		$active_filters = array();
		$filter_onload  = null;
		foreach ($filters as $filter=>$value) {
			$filter    = explode(':', $value);
			$filter_id = (is_numeric($filter[1])) ? '.f'.$filter[1] : $filter[1];
			$filter_onload .= $filter_id.$filters_sep;
		}
		$filter_onload = rtrim($filter_onload, ',');
		
		// grid sortby on load
		$sortby_onload    = $this->grid_data['sort_by_onload'];
		$sortby_meta_data = get_option('the_grid_custom_meta_data', '');
		if (isset($meta_data) && !empty($meta_data) && json_decode($meta_data) != null) {
			$sortby_meta_data = json_decode($sortby_meta_data, true);
			foreach($sortby_meta_data as $value) {
				if (in_array($sortby_onload, $value) != false) {
					$sortby_onload = strtolower($sortby_onload[0] == '_') ? substr($sortby_onload, 1) : $sortby_onload;
				}
			}
		}

		// grid sort order on load
		$order_onload = $this->grid_data['sort_order_onload'];
		
		// grid full width mode
		$grid_full_w = $this->grid_data['full_width'];
		
		// grid full height mode (horizontal)
		$grid_full_h = $this->grid_data['full_height'];
		
		// slider row number (only horizontal mode)
		$grid_row_nb = $this->grid_data['row_nb'];

		// grid items gutter (masonry/grid/justified)
		$item_gutters = json_encode($this->grid_data['gutters'], true);
		
		// grid items ratio
		$item_x_ratio = $this->grid_data['item_x_ratio'];
		$item_y_ratio = $this->grid_data['item_y_ratio'];
		$item_ratio   = number_format((float)$item_x_ratio/$item_y_ratio, 2, '.', '');
		
		// slider attribute
		$swingSpeed = $this->grid_data['slider_swingSpeed'];
		$itemNav    = $this->grid_data['slider_itemNav'];
		$autoplay   = $this->grid_data['slider_autoplay'];
		$cycle      = $this->grid_data['slider_cycleInterval'];
		$cycleBy    = $this->grid_data['slider_cycleBy'];		
		$startAt    = $this->grid_data['slider_startAt'];

		// grid columns settings
		$grid_cols = json_encode($this->grid_data['columns'], true);
		// grid rows height settings
		$grid_rows = json_encode($this->grid_data['rows_height'], true);
		
		// Get item animation style
		$anim_name = new The_Grid_Item_Animation();
		$anim_arr  = $anim_name->get_animation_name();
		$animation = $this->grid_data['animation'];
		$animation = (isset($anim_arr[$animation])) ? $animation : 'none';
		// Get animation duration (item transition)
		$transition = $this->grid_data['transition'];
		$transition = ($transition == 0 || $animation == 'none') ? 0 : $transition.'ms';
		$animation  = json_encode($anim_arr[$animation]);
		
		// ajax functionnality
		$posts_per_page = $this->grid_data['item_number'];
		$ajax_method    = $this->grid_data['ajax_method'];
		$ajax_delay     = $this->grid_data['ajax_item_delay'];
		
		// preloader functionnality
		$preloader  = $this->grid_data['preloader'];
		$item_delay = $this->grid_data['item_delay'];
		
		// Gallery SlideShow
		$gallery = $this->grid_data['gallery_slide_show'];
		
		// check layout values
		$data_attr  = ' data-name="'.esc_attr($grid_name).'" ';
		$data_attr .= ' data-style="'.esc_attr($grid_style).'"';
		$data_attr .= ' data-row="'.esc_attr($grid_row_nb).'"';
		$data_attr .= ' data-layout="'.esc_attr($grid_layout).'"';
		$data_attr .= ' data-rtl="'.esc_attr($grid_rtl).'"';
		$data_attr .= ' data-fitrows="'.esc_attr($item_fitrows).'"';
		$data_attr .= ' data-filtercomb="'.esc_attr($filter_comb).'"';
		$data_attr .= ' data-filterlogic="'.esc_attr($filter_logic).'"';
		$data_attr .= ' data-filterload ="'.esc_attr($filter_onload).'"';
		$data_attr .= ' data-sortbyload ="'.esc_attr($sortby_onload).'"';
		$data_attr .= ' data-orderload ="'.esc_attr($order_onload).'"';
		$data_attr .= ' data-fullwidth="'.esc_attr($grid_full_w).'"';
		$data_attr .= ' data-fullheight="'.esc_attr($grid_full_h).'"';
		$data_attr .= ' data-gutters="'.esc_attr($item_gutters).'"';
		$data_attr .= ' data-slider=\'{"itemNav":"'.esc_attr($itemNav).'","swingSpeed":'.esc_attr($swingSpeed).',"cycleBy":"'.esc_attr($cycleBy).'","cycle":'.esc_attr($cycle).',"startAt":'.esc_attr($startAt).'}\'';
		$data_attr .= ' data-ratio="'.esc_attr($item_ratio).'"';
		$data_attr .= ' data-cols="'.esc_attr($grid_cols).'"';
		$data_attr .= ' data-rows="'.esc_attr($grid_rows).'"';
		$data_attr .= ' data-animation=\''.esc_attr($animation).'\'';
		$data_attr .= ' data-transition="'.esc_attr($transition).'"';
		$data_attr .= ' data-ajaxmethod="'.esc_attr($ajax_method).'"';
		$data_attr .= ' data-ajaxdelay="'.esc_attr($ajax_delay).'"';
		$data_attr .= ' data-preloader="'.esc_attr($preloader).'"';
		$data_attr .= ' data-itemdelay="'.esc_attr($item_delay).'"';
		$data_attr .= ' data-gallery="'.esc_attr($gallery).'"';
		$data_attr .= ' data-ajax="'.$this->grid_data['ajax_data'].'"';

		// add data attribute for js plugin
		$this->grid_data['layout_data'] = $data_attr;
		
	}

	/**
	* Retrieve all elements added in each grid area (layout tab - Grid Settings)
	* @since 1.0.0
	*/
	public function grid_areas() {

		// retrieve all registered areas
		$data  = array_keys($this->grid_data);
		$areas = preg_grep('/area_/i', $data);
		
		// set args to extract for template part
		$args  = $this->grid_data;
		
		// loop through each area
		foreach($areas as $area) {
			
			$area_content = array();
			$data = $this->grid_data[$area];
			$data = json_decode($data, true);
			
			if (isset($data['functions']) && !empty($data['functions'])) {
				// build each area content
				foreach($data['functions'] as $function) {
					
					$index    = substr($function, -1);
					$function = str_replace('the_grid_', '', $function);
					$function = (strrpos($function, 'get_filters')!== false) ? 'get_filters' : $function;
					$param    = (strrpos($function, 'get_filters')!== false) ? $index : $args;
					
					ob_start();
					(method_exists($this, $function)) ? $this->$function($param) : null;
					$content = ob_get_contents();
					ob_end_clean();
					
					// push area content
					if ($content) {
						array_push($area_content, $content);
					}
				}
			}
			// set area content data
			$this->grid_data[$area.'_elements'] = $area_content;
		}
	
	}
	
	/**
	* Build filter buttons/dropdown list
	* @since 1.0.0
	*/
	public function get_filters($index) {
		
		$filters = $this->grid_data['filters_'.$index];
		$order   = $this->grid_data['filters_order_'.$index];
		$type    = $this->grid_data['filter_type_'.$index];

		if ($this->grid_data['source_type'] == 'post_type') {
			$filters = $this->get_post_terms($filters, $order);
		} else {
			$filters = null;
		}

		if (!empty($filters) && is_array($filters)) {
			
			// set main data to build filter template
			$this->grid_data['filter_all_text'] = $this->grid_data['filter_all_text_'.$index];
			$this->grid_data['filter_count']    = $this->grid_data['filter_count_'.$index];
			$this->grid_data['filters']         = $this->sort_array($filters, $order);
			$this->grid_data['filter_dropdown_title'] = $this->grid_data['filter_dropdown_title_'.$index];
			
			// set args to extract for template part
			$args = $this->grid_data;
			
			switch ($type) {
				case 'button':
					$filters = $this->get_filter_buttons($args);

					break;
				case 'dropdown':
					$filters = $this->get_filter_dropdown_list($args);
					break;
			}

		}
		
		return $filters;
		
	}
	
	/**
	* Retrieve all post type terms
	* @since 1.0.0
	*/
	public function get_post_terms($filters, $order) {

		$terms   = array();
		$filters = json_decode($filters, true);

		// get all terms ids
		$filters = array_map(function($filter) {
			return $filter['id'];
		}, $filters);
		
		// process term ids
		if ($term_ids = array_filter($filters, 'is_int')) {
			$terms = array_merge($terms, get_terms(
				get_taxonomies(),
				array(
					'orderby'    => 'include',
					'hide_empty' => false,
					'include'    => $term_ids
				)
			));
		}
		
		// process taxonomy slugs
		if ($taxonomies = array_filter($filters, 'is_string')) {
			$terms = array_merge($terms, get_terms(
				$taxonomies,
				array(
					'hide_empty' => false
				)
			));
		}
		
		// build filters array
		$filters = array();
		foreach ($terms as $term) {
			$filters[] = array(
				'id'    => (int) $term->term_id,
				'name'  => (string) $term->name,
				'taxo'  => (string) $term->taxonomy,
				'count' => (int) $term->count
			);	
		}

		return array_map('unserialize', array_unique(array_map('serialize', (array) $filters)));
		
	}
	
	/**
	* Sort filters array
	* @since 1.0.0
	*/
	public function sort_array($array, $order) {
		
		switch ($order) {
			case 'number_asc':
				usort($array, function ($a,$b){ return intval($a['count']) - intval($b['count']); });
				break;
			case 'number_desc':
				usort($array, function ($a,$b){ return intval($b['count']) - intval($a['count']); });
				break;
			case 'alphabetical_asc':
				usort($array, function ($a,$b){ return strcasecmp($a['name'], $b['name']); });
				break;
			case 'alphabetical_desc':
				usort($array, function ($a,$b){ return strcasecmp($b['name'], $a['name']); });
				break;
		}
		
		return $array;

	}
	
	/**
	* Output The Grid Markup (Main core function)
	* @since 1.0.0
	*/
	public function output() {
		
		// retrieve whole grid content
		ob_start();
		$this->get_content();
		$content = ob_get_contents();
		ob_end_clean();
		
		// return the grid
		return $content;
		
	}
		
	/**
	* Retrieve main template to generate The Grid markup & content
	* @since 1.0.0
	*/
	public function get_content() {

		$grid_layout = $this->grid_data['layout'];
		$grid_preloader = $this->grid_data['preloader'];
		
		$args = $this->grid_data;
			
		// Start grid wrapper
		tg_get_template_part('wrapper', 'start', true, $args);
		
		// apply filter after wrapper start
		apply_filters('tg_after_grid_wrapper_start', '', $args);
		
		// Top Areas
		tg_get_template_part('area', 'top1', true, $args);
		tg_get_template_part('area', 'top2', true, $args);

		// Open slider wrapper
		if ($grid_layout == 'horizontal') {
			tg_get_template_part('slider', 'start', true, $args);
		}
		
		// Grid item holder start
		tg_get_template_part('grid', 'holder-start', true, $args);

		// Grid items
		The_Grid_Loop($this->grid_data, $this->grid_items);
		
		// Onscroll ajax massage
		if ($grid_layout == 'vertical') {
			tg_get_template_part('grid', 'ajax-message', true, $args);
		}
		
		// Grid item holder end
		tg_get_template_part('grid', 'holder-end', true, $args);	
		
		// close slider wrapper	
		if ($grid_layout == 'horizontal') {	
			tg_get_template_part('area', 'left', true, $args);
			tg_get_template_part('area', 'right', true, $args);
			tg_get_template_part('slider', 'end', true, $args);
		}
			
		// Bottom Areas
		tg_get_template_part('area', 'bottom1', true, $args);
		tg_get_template_part('area', 'bottom2', true, $args);
			
		// Grid custom script
		tg_get_template_part('grid', 'jquery', true, $args);
			
		// Grid preloader
		if ($grid_preloader) {
			tg_get_template_part('grid', 'preloader', true, $args);
		}
		
		// apply filter before wrapper end
		apply_filters('tg_before_grid_wrapper_end', '', $args);
			
		// Close grid wrapper
		tg_get_template_part('wrapper', 'end', true, $args);

	}
	
	/**
	* Filter button template
	* @since 1.0.0
	*/
	public function get_filter_buttons($args) {
		tg_get_template_part('filter', 'buttons', true, $args);	
	}
	
	/**
	* Filter dropdown list template
	* @since 1.0.0
	*/
	public function get_filter_dropdown_list($args) {
		tg_get_template_part('filter', 'dropdown-list', true, $args);	
	}
	
	/**
	* Sorter dropdown list template
	* @since 1.0.0
	*/
	public function get_sorters($args) {
		tg_get_template_part('grid', 'sorter', true, $args);	
	}
	
	/**
	* Search field
	* @since 1.0.0
	*/
	public function get_search_field($args) {
		tg_get_template_part('grid', 'search-field', true, $args);	
	}
	
	/**
	* Ajax button template
	* @since 1.0.0
	*/
	public function get_ajax_button($args) {
		tg_get_template_part('grid', 'load-more', true, $args);	
	}
	
	/**
	* Pagination template
	* @since 1.0.0
	*/
	public function get_pagination($args) {
		tg_get_template_part('grid', 'pagination', true, $args);	
	}
	
	/**
	* Slider bullets template
	* @since 1.0.0
	*/
	public function get_slider_bullets($args) {
		tg_get_template_part('slider', 'bullets', true, $args);	
	}
	
	/**
	* Slider left arrow template
	* @since 1.0.0
	*/
	public function get_left_arrow($args) {
		tg_get_template_part('slider', 'left-arrow', true, $args);	
	}
	
	/**
	* Slider right arrow template
	* @since 1.0.0
	*/
	public function get_right_arrow($args) {
		tg_get_template_part('slider', 'right-arrow', true, $args);	
	}
	
	/**
	* Instagram header template
	* @since 1.0.0
	*/
	public function get_instagram_header($args) {
		tg_get_template_part('header', 'instagram', true, $args);	
	}
	
	/**
	* Youtube header template
	* @since 1.0.0
	*/
	public function get_youtube_header($args) {
		tg_get_template_part('header', 'youtube', true, $args);	
	}
	
	/**
	* Vimeo header template
	* @since 1.0.0
	*/
	public function get_vimeo_header($args) {
		tg_get_template_part('header', 'vimeo', true, $args);	
	}

}