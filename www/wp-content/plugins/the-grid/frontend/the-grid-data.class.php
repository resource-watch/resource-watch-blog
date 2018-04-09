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

class The_Grid_Data {
	
	/**
	* Grid Name
	*
	* @since 1.0.0
	* @access public
	*
	* @var string
	*/
	public $grid_name;
	
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
	public function __construct($grid_name, $template = false) {
		
		$this->grid_name = $grid_name;
		$this->grid_data['is_template'] = $template;
			
	}
	
	/**
	* Retrieve current grid data
	* @since 1.0.0
	*/
	public function get_data() {
		
		$this->check_grid_name();
		
		$grid_info = get_page_by_title(html_entity_decode($this->grid_name), 'OBJECT', 'the_grid');
		
		$this->check_grid_ID($grid_info);

		$grid_id = $grid_info->ID;
		$this->grid_data['ID'] = 'grid-'.$grid_id;
		
		$meta_keys = get_metadata('post', $grid_id);
		
		$this->check_grid_meta_data($meta_keys);
			
		foreach ($meta_keys as $key => $val) {
			if (strrpos($key, TG_SLUG) !== false) {
				$val = (is_array($val)) ? $val[0] : $val;
				$this->grid_data[str_replace('the_grid_', '', $key)] = maybe_unserialize($val);
			}
		}
			
		$this->normalize_data($this->grid_data);

		return $this->grid_data;

	}
	
	/**
	* Check grid name
	* @since 1.0.0
	*/
	public function check_grid_name() {
		
		if (empty($this->grid_name)) {
			$error_msg = __('The shortcode doesn\'t contain any grid name.', 'tg-text-domain' );
			throw new Exception($error_msg);
		}
		
	}
	
	/**
	* Check grid info
	* @since 1.0.0
	*/
	public function check_grid_ID($grid_info) {
		
		if (!isset($grid_info->ID)) {
			$error_msg = __('No grid was found for:', 'tg-text-domain' ).' '.$this->grid_name.'.';
			throw new Exception($error_msg);
		}
		
	}
	
	/**
	* Check grid meta data
	* @since 1.0.0
	*/
	public function check_grid_meta_data($meta_keys) {
		
		if (empty($meta_keys)) {
			$error_msg = __('No data was found for the current grid.', 'tg-text-domain' );
			throw new Exception($error_msg);
		}
		
	}
	
	/**
	* Check grid data
	* @since 1.0.0
	*/
	public function check_grid_data() {
		
		if (empty($this->grid_data)) {
			$error_msg = __('Sorry, an unknown error occurred while retrieving the grid settings.', 'tg-text-domain' );
			throw new Exception($error_msg);
		}
		
	}
	
	/**
	* Normalize all data 
	* @since 1.0.0
	*/
	public function normalize_data($data) {
		
		$base = new The_Grid_Base();

		// Default general settings
		$options[] = array(
			'ID'        => '',
			'name'      => '',
			'css_class' => ''
		);
		
		// Default source settings
		$options[] = array(
			'source_type'      => 'post_type',
			'item_number'      => 0,
			'offset'           => 0,
			'post_type'        => array('post'),
			'gallery'          => '',
			'post_status'      => array('publish'),
			'categories'       => array(),
			'categories_child' => '',
			'pages_id'         => array(),
			'author'           => array(''),
			'post_not_in'      => '',
			'order'            => '',
			'orderby'          => array(),
			'orderby_id'       => '',
			'meta_key'         => '',
			'meta_query'       => ''
		);
		
		// Social media attribute default settings
		$options[] = array(
			'ajax_data' => ''
		);
		
		// Instagram default settings
		$options[] = array(
			'instagram_username' => '',
			'instagram_hashtag'  => ''
		);
		
		// Youtube default settings
		$options[] = array(
			'youtube_order'    => 'date',
			'youtube_source'   => 'channel',
			'youtube_channel'  => '',
			'youtube_playlist' => '',
			'youtube_videos'   => ''
		);
		
		// Vimeo default settings
		$options[] = array(
			'vimeo_sort'    => '',
			'vimeo_order'   => 'desc',
			'vimeo_source'  => 'user',
			'vimeo_user'    => '',
			'vimeo_album'   => '',
			'vimeo_group'   => '',
			'vimeo_channel' => ''
		);
		
		// Facebook default settings
		$options[] = array(
			'facebook_source'   => 'page_timeline',
			'facebook_page'     => '',
			'facebook_album_id' => '',
			'facebook_group_id' => ''
		);
		
		// Twitter default settings
		$options[] = array(
			'twitter_source'    => 'user_timeline',
			'twitter_username'  => '',
			'twitter_listname'  => '',
			'twitter_searchkey' => '',
			'twitter_include'   => array()
		);
		
		// Flickr default settings
		$options[] = array(
			'flickr_source'      => 'public_photos',
			'flickr_user_url'    => '',
			'flickr_group_url'   => '',
			'flickr_gallery_url' => '',
			'flickr_photoset_id' => ''
		);
		
		// NexGen default settings
		$options[] = array(
			'nextgen_source'       => 'gallery',
			'nextgen_gallery_id'   => '',
			'nextgen_album_id'     => '',
			'nextgen_image_ids'    => '',
			'nextgen_search_query' => ''
		);
		
		// RSS feed default settings
		$options[] = array(
			'rss_feed_url' => '',
		);
		
		// Default media settings
		$options[] = array(
			'default_image'      => '',
			'aqua_resizer'       => '',
			'image_size'         => 'full',
			'items_format'       => array(),
			'gallery_slide_show' => '',
			'video_lightbox'     => ''
		);
		
		// Default grid settings
		$options[] = array(
			'style'                => 'grid',
			'item_x_ratio'         => 4,
			'item_y_ratio'         => 3,
			'item_fitrows'         => '',
			'item_force_size'      => '',
			'items_col'            => 1,
			'items_row'            => 1,
			// grid/masonry style
			'desktop_large'        => 6,
			'desktop_medium'       => 5,
			'desktop_small'        => 4,
			'tablet'               => 3,
			'tablet_small'         => 2,
			'mobile'               => 1,
			// justified style
			'desktop_large_row'    => 240,
			'desktop_medium_row'   => 240,
			'desktop_small_row'    => 220,
			'tablet_row'           => 220,
			'tablet_small_row'     => 200,
			'mobile_row'           => 200,
			// items gutters
			'gutter'               => 0,
			'desktop_medium_gutter'=> -1,
			'desktop_small_gutter' => -1,
			'tablet_gutter'        => -1,
			'tablet_small_gutter'  => -1,
			'mobile_gutter'        => -1,
			// browser window widths
			'desktop_medium_width' => 1200,
			'desktop_small_width'  => 980,
			'tablet_width'         => 768,
			'tablet_small_width'   => 480,
			'mobile_width'         => 320
		);
		
		// Default filter/sort settings
		$options[] = array(
			'filter_onload'           => array(),
			'filter_combination'      => '',
			'filter_logic'            => 'AND',
			'sort_by'                 => array(),
			'sort_by_onload'          => '',
			'sort_order_onload'       => '',
			'sort_by_text'            => __('Sort By', 'tg-text-domain'),
			'search_text'             => ''
		);
		
		// handle dynamic generated filter area options
		$grid_data_key = array_keys($data);
		$filter_areas  = preg_grep('/filters_\d/i', $grid_data_key);
		$filter_number = count($filter_areas);
		for ($i = 1; $i <= $filter_number; $i++) {
			$options[] = array(
				'filters_order_'.$i         => '',
				'filter_type_'.$i           => 'button',
				'filter_dropdown_title_'.$i => __('Filter Categories', 'tg-text-domain'),
				'filter_all_text_'.$i       => '',
				'filter_count_'.$i          => 'none',
				'filters_'.$i               => '',
			);
		}	
		
		// Default pagination settings
		$options[] = array(
			'ajax_pagination'      => '',
			'pagination_type'      => 'number',
			'pagination_prev_next' => '',
			'pagination_show_all'  => '',
			'pagination_mid_size'  => 2,
			'pagination_end_size'  => 1,
			'pagination_prev'      => __('&#171; Prev', 'tg-text-domain'),
			'pagination_next'      => __('Next &#187;', 'tg-text-domain')
		);
		
		// Default layout settings
		$options[] = array(
			'area_top1'        => '',
			'area_top2'        => '',
			'area_left'        => '',
			'area_right'       => '',
			'area_bottom1'     => '',
			'area_bottom2'     => '',
			'layout'           => 'vertical',
			'rtl'              => '',
			'wrap_marg_left'   => 0,
			'wrap_marg_top'    => 0,
			'wrap_marg_right'  => 0,
			'wrap_marg_bottom' => 0,
			'grid_background'  => '',
			'full_width'       => '',
			'full_height'      => ''	
		);
		
		// Default slider settings
		$options[] = array(
			'row_nb'               => 1,
			'slider_swingSpeed'    => 0.1,
			'slider_itemNav'       => 'null',
			'slider_startAt'       => 1,
			'slider_autoplay'      => '',
			'slider_cycleInterval' => 5000
		);
		
		// Default skin settings
		$options[] = array(
			'skins'                   => array(),
			'social_skin'             => '',
			'skin_content_background' => '',
			'skin_content_color'      => 'dark',
			'skin_overlay_background' => '',
			'skin_overlay_color'      => 'light',
			'navigation_style'        => 'tg-txt',
			'navigation_color'        => '#999999',
			'navigation_accent_color' => '#ff6863',
			'navigation_bg'           => '#999999',
			'navigation_accent_bg'    => '#ff6863',
			'dropdown_color'          => '#777777',
			'dropdown_bg'             => '',
			'dropdown_hover_color'    => '#444444',
			'dropdown_hover_bg'       => '',
			'navigation_arrows_color' => '',
			'navigation_arrows_bg'    => '',
			'navigation_bullets_color' => '#dddddd',
			'navigation_bullets_color_active' => '#59585b'
		);
		
		// Default animations settings
		$options[] = array(
			'animation'  => 'fade_in',
			'transition' => 0
		);
		
		// Default load/ajax settings
		$options[] = array(
			'ajax_method'         => '',
			'ajax_button_text'    => __( 'Load More', 'tg-text-domain' ),
			'ajax_button_loading' => __('Loading...', 'tg-text-domain'),
			'ajax_button_no_more' => __( 'No more item', 'tg-text-domain' ),
			'ajax_items_remain'   => '',
			'ajax_item_number'    => 4,
			'ajax_item_delay'     => 0,
			'preloader'           => '',
			'preloader_style'     => 'square-grid-pulse',
			'preloader_color'     => '#34495e',
			'preloader_size'      => '1',
			'item_delay'          => 0,
			'custom_css'          => ''
		);
		
		// Default css/js settings
		$options[] = array(
			'custom_js'  => '',
			'custom_css' => ''
		);
		
		// General Options
		$options[] = array(
			'lightbox_type' => get_option('the_grid_lightbox', 'the_grid'),
			'date_format'   => get_option('date_format'),
			'grid_colors'   => array(
				'light' => array(
					'title' => get_option('the_grid_light_title'),
					'text'  => get_option('the_grid_light_text'),
					'span'  => get_option('the_grid_light_span')
				),
				'dark' => array(
					'title' => get_option('the_grid_dark_title'),
					'text'  => get_option('the_grid_dark_text'),
					'span'  => get_option('the_grid_dark_span')
				)
			)
		);
		
		// retrieve registered grid skins
		$item_base  = new The_Grid_Item_Skin();
		$grid_skins = $item_base->get_skin_names();
		// Grid skins
		$options[] = array(
			'grid_skins' => $grid_skins
		);
		
		// Options created on the fly
		$options[] = array(
			'cache_date'        => '',
			'is_template'       => false,
			'wrapper_css_class' => '',
		    'layout_data'       => '',
			'grid_items'        => '',
			'item_total'        => '',
			'item_classes'      => '',
			'item_attributes'   => '',
			'item_skins'        => ''
		);
		
		// merge all options array
		$options = call_user_func_array('array_merge', $options);

		// loop through each settings and assign default value if missing
		foreach($options as $option => $value) {
			$this->grid_data[$option] = $base->getVar($data, $option, $value);
		}

		// check current settings to reassign right values depending of current settings
		$this->check_data();
		
		return $this->grid_data;

	}
	
	/**
	* Check important data and re-assign default values
	* @since 1.0.0
	*/
	public function check_data() {
		
		// set default blog item number if item number is null
		$this->grid_data['item_number'] = (int)$this->grid_data['item_number'] == 0 ? get_option('posts_per_page') : $this->grid_data['item_number'];
		$this->grid_data['item_number'] = ($this->grid_data['source_type'] != 'post_type' && (int)$this->grid_data['item_number'] < 0)  ? 10 : $this->grid_data['item_number'];
		
		// media image size
		$aqua_resizer       = $this->grid_data['aqua_resizer'];
		// get the grid main data
		$grid_style         = $this->grid_data['style'];
		$grid_layout        = $this->grid_data['layout'];
		$item_number        = $this->grid_data['item_number'];
		$items_format       = $this->grid_data['items_format'];
		$full_height        = $this->grid_data['full_height'];
		$slider_rownb       = $this->grid_data['row_nb'];
		$slider_itemNav     = $this->grid_data['slider_itemNav'];
		$slider_startAt     = $this->grid_data['slider_startAt'];
		$slider_autoplay    = $this->grid_data['slider_autoplay'];
		$ajax_method        = $this->grid_data['ajax_method'];
		// grid filter on load
		$filter_onload      = $this->grid_data['filter_onload'];
		// columns number
		$col_desktop_large  = (int) $this->grid_data['desktop_large'];
		$col_desktop_medium = (int) $this->grid_data['desktop_medium'];
		$col_desktop_small  = (int) $this->grid_data['desktop_small'];
		$col_tablet         = (int) $this->grid_data['tablet'];
		$col_tablet_small   = (int) $this->grid_data['tablet_small'];
		$col_mobile         = (int) $this->grid_data['mobile'];
		// rows height
		$row_desktop_large  = (int) $this->grid_data['desktop_large_row'];
		$row_desktop_medium = (int) $this->grid_data['desktop_medium_row'];
		$row_desktop_small  = (int) $this->grid_data['desktop_small_row'];
		$row_tablet         = (int) $this->grid_data['tablet_row'];
		$row_tablet_small   = (int) $this->grid_data['tablet_small_row'];
		$row_mobile         = (int) $this->grid_data['mobile_row'];
		// items gutter
		$gutter_desktop_large  = (int) $this->grid_data['gutter'];
		$gutter_desktop_medium = (int) $this->grid_data['desktop_medium_gutter'];
		$gutter_desktop_small  = (int) $this->grid_data['desktop_small_gutter'];
		$gutter_tablet         = (int) $this->grid_data['tablet_gutter'];
		$gutter_tablet_small   = (int) $this->grid_data['tablet_small_gutter'];
		$gutter_mobile         = (int) $this->grid_data['mobile_gutter'];
		// smart gutter (to match previous gutter value if empty or <= -1 values, preserve 0 value)
		$gutter_desktop_medium = ((empty($gutter_desktop_medium) || $gutter_desktop_medium <= -1) && $gutter_desktop_medium != 0) ? $gutter_desktop_large : $gutter_desktop_medium;
		$gutter_desktop_small  = ((empty($gutter_desktop_small) || $gutter_desktop_small <= -1) && $gutter_desktop_small != 0) ? $gutter_desktop_medium : $gutter_desktop_small;
		$gutter_tablet         = ((empty($gutter_tablet) || $gutter_tablet <= -1) && $gutter_tablet != 0) ? $gutter_desktop_small : $gutter_tablet;
		$gutter_tablet_small   = ((empty($gutter_tablet_small) || $gutter_tablet_small <= -1) && $gutter_tablet_small != 0) ? $gutter_tablet : $gutter_tablet_small;
		$gutter_mobile         = ((empty($gutter_mobile) || $gutter_mobile <= -1) && $gutter_mobile != 0) ? $gutter_tablet_small :  $gutter_mobile;
		
		// columns/rows window widths
		$ww_desktop_medium  = (int) $this->grid_data['desktop_medium_width'];
		$ww_desktop_small   = (int) $this->grid_data['desktop_small_width'];
		$ww_tablet          = (int) $this->grid_data['tablet_width'];
		$ww_tablet_small    = (int) $this->grid_data['tablet_small_width'];
		$ww_mobile          = (int) $this->grid_data['mobile_width'];
		
		// ajax item animation delay
		$ajax_item_delay = $this->grid_data['ajax_item_delay'];
		// set image as default in allowed formats;
		$this->grid_data['items_format'] = array_merge($items_format, array('image'));
		// filter on load (set new param)
		$active_filters = array();
		foreach ($filter_onload as $filter => $value) {
			$filter = explode(':', $value);
			array_push($active_filters, $filter[1]);
		}
		$this->grid_data['active_filters'] = $active_filters;
		// Remove random orderby if load more or ajax pagination
		array_filter($this->grid_data, array($this, 'find_random_orderby'));
		// media image size disabled aqua resizer if justified layout
		$this->grid_data['aqua_resizer'] = ($grid_style != 'justified') ? $aqua_resizer : false;	
		// grid full height mode (horizontal)
		$this->grid_data['full_height'] = ($grid_layout == 'horizontal' && $grid_style == 'grid') ? $full_height : 'null';
		// redefined row number
		$this->grid_data['row_nb']  = ($grid_style == 'masonry') ? 1 : $slider_rownb;
		// slider start position
		$this->grid_data['slider_startAt'] = ($slider_itemNav != 'null') ? $slider_startAt : 1;
		// slider cycle by (new created option based on autoplay option)
		$this->grid_data['slider_cycleBy'] = (!empty($slider_autoplay)) ? 'pages'  : 'null';
		// redfined ajax method
		$this->grid_data['ajax_method']   = ($item_number != '-1' && $grid_layout != 'horizontal') ? $ajax_method : '';
		// build columns/widths array
		$this->grid_data['columns'] = array(
			array($ww_mobile, $col_mobile),
			array($ww_tablet_small, $col_tablet_small),
			array($ww_tablet, $col_tablet),
			array($ww_desktop_small, $col_desktop_small),
			array($ww_desktop_medium, $col_desktop_medium),
			array(9999, $col_desktop_large)
		);
		// build gutters array
		$this->grid_data['gutters'] = array(
			array($ww_mobile, $gutter_mobile),
			array($ww_tablet_small, $gutter_tablet_small),
			array($ww_tablet, $gutter_tablet),
			array($ww_desktop_small, $gutter_desktop_small),
			array($ww_desktop_medium, $gutter_desktop_medium),
			array(9999, $gutter_desktop_large)
		);
		// build rows/widths array
		$this->grid_data['rows_height'] = array(
			array($ww_mobile, $row_mobile),
			array($ww_tablet_small, $row_tablet_small),
			array($ww_tablet, $row_tablet),
			array($ww_desktop_small, $row_desktop_small),
			array($ww_desktop_medium, $row_desktop_medium),
			array(9999, $row_desktop_large)
		);
		
		// retirve main global of The Grid
		global $tg_is_ajax, $tg_grid_preview;
		
		// for preview mode
		if ($tg_grid_preview) {
			// Show all posts
			$this->grid_data['post_not_in']     = null;
			// Force lightbox
			$this->grid_data['video_lightbox']  = true;
			// Force ajax pagination
			$this->grid_data['ajax_pagination'] = true;
		}
		
		// if ajax request then redefined item number and offset
		if ($tg_is_ajax) {
			// check if ajax pagination to keep post per page instead of nb of post to load with ajax (load more button or on scroll)
			$pagination    = array_filter($this->grid_data, function($s){ return (is_string($s)) ? strpos($s, 'get_pagination') : false;});
			$grid_page     = $_POST['grid_page'];
			$ajax_page_nav = $this->grid_data['ajax_pagination'];
			$ajax_item_nb  = $this->grid_data['ajax_item_number'];
			$post_per_page = ($ajax_page_nav && !empty($pagination)) ? $this->grid_data['item_number'] : $ajax_item_nb;
			// redefined offset, item number on ajax call and current item number
			$this->grid_data['offset'] = $this->grid_data['item_number']+$post_per_page*($grid_page-1);
			$this->grid_data['item_number'] = $post_per_page;
			// disable preloader
			$this->grid_data['preloader'] = null;
		}

	}
	
	/**
	* Find if random value for orderBy exists
	* @since 1.4.5
	*/
	public function find_random_orderby($s) {
		
		// if load more or pagination exists then unset random order to preserve correct post order when load more
		if (is_string($s) && (strpos($s, 'get_ajax_button') !== false || strpos($s, 'get_pagination') !== false || $this->grid_data['ajax_method'] == 'on_scroll')) {
			if (($key = array_search('rand', $this->grid_data['orderby'])) !== false) {
				unset($this->grid_data['orderby'][$key]);
			}
		}
		
	}

}