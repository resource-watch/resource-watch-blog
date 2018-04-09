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

class The_Grid_Custom_Fields {
	
	/**
	* Get post ID
	* @since 1.0.0
	*/
	public static function get_post_ID() {
		$post_ID = isset($_GET['id']) ? $_GET['id'] : -1;
		return $post_ID;
	}
	
	/**
	* Build preloader preview for grid settings
	* @since 1.0.0
	*/
	public static function preloader_config() {
		
		$post_ID = self::get_post_ID();
		
		$prefix = TG_PREFIX;
		
		$preloader_base  = new the_grid_preloader_skin();
		
		$preloader_skins = $preloader_base->get_preloader_name();
		$preloader_curr  = get_post_meta($post_ID, $prefix.'preloader_style', true);
		$preloader_curr  = (!empty($preloader_curr)) ? $preloader_curr : 'square-grid-pulse';
		$preloader_color = get_post_meta($post_ID, $prefix.'preloader_color', true);
		$preloader_color = (!empty($preloader_color)) ? $preloader_color : '#34495e';
		$preloader_color = '.the_grid_preloader_preview .tg-grid-preloader-inner>div{background:'.$preloader_color.';border-color:'.$preloader_color.';}';
		$preloader_size  = get_post_meta($post_ID, $prefix.'preloader_size', true);
		$preloader_size  = (!empty($preloader_size)) ? $preloader_size : 1;
		$preloader_size  = '.the_grid_preloader_preview .tg-grid-preloader-scale{transform:scale('.$preloader_size.')}';
		
		$preloader = null;
		$preloader_css  = null;
		foreach ($preloader_skins as $preloader_skin => $param) {
			$preloader_name  = $preloader_skin;
			$preloader_skin  = $preloader_base->$preloader_name();
			$preloader_css  .= $preloader_skin['css'];
			$preloader_show = ($preloader_name == $preloader_curr) ? ' show' : ' hide';
			$preloader .= '<div class="tg-grid-preloader-inner '.$preloader_name.$preloader_show.'">';	
			$preloader .= $preloader_skin['html'];
			$preloader .= '</div>';
		}
		$preloader_html  = '<style class="preloader-styles" type="text/css" scoped>'.$preloader_css.$preloader_color.$preloader_size.'</style>';
		$preloader_html .= '<div class="tg-grid-preloader ">';
			$preloader_html .= '<div class="tg-grid-preloader-holder">';
				$preloader_html .= '<div class="tg-grid-preloader-scale">';
					$preloader_html .= $preloader;
				$preloader_html .= '</div>';
			$preloader_html .= '</div>';
		$preloader_html .= '</div>';
		
		return $preloader_html;
	}
	
	/**
	* Build filter section for grid settings
	* @since 1.0.0
	*/
	public static function available_filters_config() {
		
		$post_ID = self::get_post_ID();
		
		$prefix = TG_PREFIX;
		
		$number = get_post_meta($post_ID, $prefix.'filters_number', true);
		
		$filters = '<div id="tg-available-filters-holder" data-delete-msg="'. __( 'Are you sure you want to delete this filter?', 'tg-text-domain' ) .'">';
			$filters .= '<h3 class="tg-filter-title">'. __( 'Available Filter item(s)', 'tg-text-domain' ) .'</h3>';
			$filters .= '<div id="tg-available-filters">';
				$filters .= '<ul id="tg-filter-sort1" class="connectedSortable"></ul>';
			$filters .= '</div>';
		$filters .= '</div>';
		$filters .= '<input type="hidden" name="the_grid_filters_number" value=\''.$number.'\'>';
		
		$filters .= '<div class="tg-filter-holder-number">';
		for ($i = 2; $i <= $number; $i++) {
			$filters .= '<div class="tg-filter-holder-number-'.$i.'">';
				$filters .= '<input type="hidden" data-input="the_grid_filters_order_'.$i.'" value=\''.get_post_meta($post_ID, $prefix.'filters_order_'.$i, true).'\'>';
				$filters .= '<input type="hidden" data-input="the_grid_filter_type_'.$i.'" value=\''.get_post_meta($post_ID, $prefix.'filter_type_'.$i, true).'\'>';
				$filters .= '<input type="hidden" data-input="the_grid_filter_dropdown_title_'.$i.'" value=\''.get_post_meta($post_ID, $prefix.'filter_dropdown_title_'.$i, true).'\'>';
				$filters .= '<input type="hidden" data-input="the_grid_filter_all_text_'.$i.'" value=\''.get_post_meta($post_ID, $prefix.'filter_all_text_'.$i, true).'\'>';
				$filters .= '<input type="hidden" data-input="the_grid_filter_count_'.$i.'" value=\''.get_post_meta($post_ID, $prefix.'filter_count_'.$i, true).'\'>';
				$filters .= '<input type="hidden" data-input="the_grid_filters_'.$i.'" value=\''.get_post_meta($post_ID, $prefix.'filters_'.$i, true).'\'>';
			$filters .= '</div>';
		}
		$filters .= '</div>';
		
		return $filters;
	}
	
	/**
	* Build active filter section for grid settings
	* @since 1.0.0
	*/
	public static function active_filters_config() {
		
		$post_ID = self::get_post_ID();
		
		$prefix = TG_PREFIX;
		
		$filters = '<div id="tg-active-filters">';
			$filters .= '<label class="tomb-label">'.__( 'Active Filter(s)', 'tg-text-domain' ).'</label>';
			$filters .= '<ul class="tg-filter-sort2 connectedSortable"></ul>';
			$filters .= '<span class="tg-filter-button-remove">'.__( 'Remove all', 'tg-text-domain' ).'</span> / ';
			$filters .= '<span class="tg-filter-button-add">'.__( 'Add all', 'tg-text-domain' ).'</span>';
		$filters .= '</div>';
		$filters .= '<input type="hidden" name="the_grid_filters_1" value=\''.get_post_meta($post_ID, $prefix.'filters_1', true).'\'>';
		
		return $filters;
	}
	
	/**
	* Build drag/drop layout builder
	* @since 1.0.0
	*/
	public static function grid_layout_config() {
		
		$post_ID = self::get_post_ID();
		
		$prefix = TG_PREFIX;
	
		$meta_area_top1    = get_post_meta($post_ID, $prefix.'area_top1', true);
		$meta_area_top2    = get_post_meta($post_ID, $prefix.'area_top2', true);
		$meta_area_left    = get_post_meta($post_ID, $prefix.'area_left', true);
		$meta_area_right   = get_post_meta($post_ID, $prefix.'area_right', true);
		$meta_area_bottom1 = get_post_meta($post_ID, $prefix.'area_bottom1', true);
		$meta_area_bottom2 = get_post_meta($post_ID, $prefix.'area_bottom2', true);
		
		$grid_layout_buts  = '<div class="tg-layout-setting-buttons">';
			$grid_layout_buts .= '<div class="tg-layout-align-left dashicons dashicons-editor-alignleft" data-align="txt-left" data-val="left"></div>';
			$grid_layout_buts .= '<div class="tg-layout-align-center dashicons dashicons-editor-aligncenter" data-align="txt-center" data-val="center"></div>';
			$grid_layout_buts .= '<div class="tg-layout-align-right dashicons dashicons-editor-alignright" data-align="txt-right" data-val="right"></div>';
			$grid_layout_buts .= '<div class="tg-layout-area-settings dashicons dashicons-admin-generic"></div>';
		$grid_layout_buts .= '</div>';
		
		$grid_layout_buts2  = '<div class="tg-layout-setting-buttons tg-simple-setting">';
			$grid_layout_buts2 .= '<div class="tg-layout-area-settings dashicons dashicons-admin-generic"></div>';
		$grid_layout_buts2 .= '</div>';
		
		$grid_layout  = '<div id="tg-layout-wrapper">';
		
			$grid_layout .= '<div class="tg-layout-blocs-wrapper">';
				$grid_layout .= '<label class="tomb-label">'.__( 'Available blocs', 'tg-text-domain' ).'</label>';
				$grid_layout .= '<ul id="tg-layout-blocs-holder" class="tg-layout-connected">';
					$grid_layout .= '<li class="tg-layout-bloc tg-layout-search dashicons-search" data-func="the_grid_get_search_field">'.__( 'Search', 'tg-text-domain' ).'</li>';
					$grid_layout .= '<li class="tg-layout-bloc tg-layout-filter dashicons-admin-settings" data-func="the_grid_get_filters_1">'.__( 'Filter', 'tg-text-domain' ).' - <span class="tg-filter-func-nb">1</span></li>';
					$grid_layout .= '<li class="tg-layout-bloc tg-layout-sorter dashicons-randomize" data-func="the_grid_get_sorters">'.__( 'Sort', 'tg-text-domain' ).'</li>';
					$grid_layout .= '<li class="tg-layout-bloc tg-layout-load-more dashicons-update" data-func="the_grid_get_ajax_button">'.__( 'Load more', 'tg-text-domain' ).'</li>';
					$grid_layout .= '<li class="tg-layout-bloc tg-layout-pagination dashicons-media-default" data-func="the_grid_get_pagination">'.__( 'Pagination', 'tg-text-domain' ).'</li>';
					$grid_layout .= '<li class="tg-layout-bloc tg-layout-arrow-left dashicons-arrow-left-alt2 tg-bloc-center" data-func="the_grid_get_left_arrow"></li>';
					$grid_layout .= '<li class="tg-layout-bloc tg-layout-arrow-right dashicons-arrow-right-alt2 tg-bloc-center" data-func="the_grid_get_right_arrow"></li>';
					$grid_layout .= '<li class="tg-layout-bloc tg-layout-slider-bullets dashicons-marker" data-func="the_grid_get_slider_bullets">'.__( 'Slider bullets', 'tg-text-domain' ).'</li>';
					$grid_layout .= '<li class="tg-layout-bloc tg-layout-instagram-header dashicons-camera" data-func="the_grid_get_instagram_header">'.__( 'Instagram user', 'tg-text-domain' ).'</li>';
					$grid_layout .= '<li class="tg-layout-bloc tg-layout-youtube-header dashicons-video-alt3" data-func="the_grid_get_youtube_header">'.__( 'Youtube banner', 'tg-text-domain' ).'</li>';
					$grid_layout .= '<li class="tg-layout-bloc tg-layout-vimeo-header dashicons-admin-users" data-func="the_grid_get_vimeo_header">'.__( 'Vimeo user', 'tg-text-domain' ).'</li>';
				$grid_layout .= '</ul>';
			$grid_layout .= '</div>';
			
			$grid_layout .= '<div class="tg-layout-blocs-wrapper">';
			
				$grid_layout .= '<label class="tomb-label">'.__( 'Grid Layout', 'tg-text-domain' ).'</label>';
				
				$grid_layout .= '<div id="tg-layout-blocs-entries">';
				
					$grid_layout .= '<div id="tg-layout-top-area-1" class="tg-layout-area">';
						$grid_layout .= $grid_layout_buts;
						$grid_layout .= '<span class="tg-area-name">'.__( 'Top Area 1', 'tg-text-domain' ).'</span>';
						$grid_layout .= '<ul id="tg-layout-top-area-1-holder" class="tg-layout-connected"></ul>';
						$grid_layout .= '<div class="tomb-row" id="the_grid_area_top1">';
							$grid_layout .= '<input type="hidden" name="the_grid_area_top1" data-value=\''.$meta_area_top1.'\' value=\''.$meta_area_top1.'\'>';
						$grid_layout .= '</div>';
					$grid_layout .= '</div>';
					
					$grid_layout .= '<div id="tg-layout-top-area-2" class="tg-layout-area">';
						$grid_layout .= $grid_layout_buts;
						$grid_layout .= '<span class="tg-area-name">'.__( 'Top Area 2', 'tg-text-domain' ).'</span>';
						$grid_layout .= '<ul id="tg-layout-top-area-2-holder" class="tg-layout-connected"></ul>';
						$grid_layout .= '<div class="tomb-row" id="the_grid_area_top2">';
							$grid_layout .= '<input type="hidden" name="the_grid_area_top2" data-value=\''.$meta_area_top2.'\' value=\''.$meta_area_top2.'\'>';
						$grid_layout .= '</div>';
					$grid_layout .= '</div>';
					
					$grid_layout .= '<div id="tg-layout-center-area">';
					
						$grid_layout .= '<div id="tg-layout-center-bloc-holder">';
							$grid_layout .= '<div class="tg-layout-center-bloc"></div>';
							$grid_layout .= '<div class="tg-layout-center-bloc"></div>';
							$grid_layout .= '<div class="tg-layout-center-bloc"></div>';
							$grid_layout .= '<div class="tg-layout-center-bloc"></div>';
							$grid_layout .= '<div class="tg-layout-center-bloc"></div>';
							$grid_layout .= '<div class="tg-layout-center-bloc"></div>';
						$grid_layout .= '</div>';
						
						$grid_layout .= '<div id="tg-layout-center-left" class="tg-layout-area">';
							$grid_layout .= $grid_layout_buts2;
							$grid_layout .= '<span class="tg-area-name">'.__( 'Left Area', 'tg-text-domain' ).'</span>';
							$grid_layout .= '<ul id="tg-layout-center-left-holder" class="tg-layout-connected tg-exclude"></ul>';
							$grid_layout .= '<div class="tomb-row" id="the_grid_area_left">';
								$grid_layout .= '<input type="hidden" name="the_grid_area_left" data-value=\''.$meta_area_left.'\' value=\''.$meta_area_left.'\'>';
							$grid_layout .= '</div>';
						$grid_layout .= '</div>';
						
						$grid_layout .= '<div id="tg-layout-center-right" class="tg-layout-area">';
							$grid_layout .= $grid_layout_buts2;
							$grid_layout .= '<span class="tg-area-name">'.__( 'Right Area', 'tg-text-domain' ).'</span>';
							$grid_layout .= '<ul id="tg-layout-center-right-holder" class="tg-layout-connected tg-exclude"></ul>';
							$grid_layout .= '<div class="tomb-row" id="the_grid_area_right">';
								$grid_layout .= '<input type="hidden" name="the_grid_area_right" data-value=\''.$meta_area_right.'\' value=\''.$meta_area_right.'\'>';
							$grid_layout .= '</div>';
						$grid_layout .= '</div>';
	
					$grid_layout .= '</div>';
				
					$grid_layout .= '<div id="tg-layout-bottom-area-1" class="tg-layout-area">';
						$grid_layout .= $grid_layout_buts;
						$grid_layout .= '<span class="tg-area-name">'.__( 'Bottom Area 1', 'tg-text-domain' ).'</span>';
						$grid_layout .= '<ul id="tg-layout-bottom-area-1-holder" class="tg-layout-connected"></ul>';
						$grid_layout .= '<div class="tomb-row" id="the_grid_area_bottom1">';
							$grid_layout .= '<input type="hidden" name="the_grid_area_bottom1" data-value=\''.$meta_area_bottom1.'\' value=\''.$meta_area_bottom1.'\'>';
						$grid_layout .= '</div>';
					$grid_layout .= '</div>';
					
					$grid_layout .= '<div id="tg-layout-bottom-area-2" class="tg-layout-area">';
						$grid_layout .= $grid_layout_buts;
						$grid_layout .= '<span class="tg-area-name">'.__( 'Bottom Area 2', 'tg-text-domain' ).'</span>';
						$grid_layout .= '<ul id="tg-layout-bottom-area-2-holder" class="tg-layout-connected"></ul>';
						$grid_layout .= '<div class="tomb-row" id="the_grid_area_bottom2">';
							$grid_layout .= '<input type="hidden" name="the_grid_area_bottom2" data-value=\''.$meta_area_bottom2.'\' value=\''.$meta_area_bottom2.'\'>';
						$grid_layout .= '</div>';
					$grid_layout .= '</div>';
				
				$grid_layout .= '</div>';
				
			$grid_layout .= '</div>';
		$grid_layout .= '</div>';
		
		$grid_layout .= '<div id="tg-layout-styles-box">';
			$grid_layout .= '<h3 id="tg-layout-styles-header"><i class="dashicons dashicons-art"></i>'.__('Styles', 'tg-text-domain' ).'<i class="dashicons dashicons-no-alt"></i></h3>';
			$grid_layout .= '<label class="tomb-label">'.__( 'Margin top:', 'tg-text-domain' ).'</label>';
			$grid_layout .= '<input type="number" class="tomb-text number mini" id="the-grid-area-margintop" data-name="margin-top" value="0" step="1" min="-200">';
			$grid_layout .= '<label class="tomb-number-label">px</label>';
			$grid_layout .= '<label class="tomb-label">'.__( 'Margin bottom:', 'tg-text-domain' ).'</label>';
			$grid_layout .= '<input type="number" class="tomb-text number mini" id="the-grid-area-marginbot" data-name="margin-bottom" value="0" step="1" min="-200">';
			$grid_layout .= '<label class="tomb-number-label">px</label>';
			$grid_layout .= '<label class="tomb-label">'.__( 'Margin left:', 'tg-text-domain' ).'</label>';
			$grid_layout .= '<input type="number" class="tomb-text number mini" id="the-grid-area-marginleft" data-name="margin-left" value="0" step="1" min="-200">';
			$grid_layout .= '<label class="tomb-number-label">px</label>';
			$grid_layout .= '<label class="tomb-label">'.__( 'Margin right:', 'tg-text-domain' ).'</label>';
			$grid_layout .= '<input type="number" class="tomb-text number mini" id="the-grid-area-marginright" data-name="margin-right" value="0" step="1" min="-200">';
			$grid_layout .= '<label class="tomb-number-label">px</label>';
			$grid_layout .= '<label class="tomb-label">'.__( 'Padding top:', 'tg-text-domain' ).'</label>';
			$grid_layout .= '<input type="number" class="tomb-text number mini" id="the-grid-area-paddingtop" data-name="padding-top" value="0" step="1" min="0">';
			$grid_layout .= '<label class="tomb-number-label">px</label>';
			$grid_layout .= '<label class="tomb-label">'.__( 'Padding bottom:', 'tg-text-domain' ).'</label>';
			$grid_layout .= '<input type="number" class="tomb-text number mini" id="the-grid-area-paddingbot" data-name="padding-bottom" value="0" step="1" min="0">';
			$grid_layout .= '<label class="tomb-number-label">px</label>';
			$grid_layout .= '<label class="tomb-label">'.__( 'Padding left:', 'tg-text-domain' ).'</label>';
			$grid_layout .= '<input type="number" class="tomb-text number mini" id="the-grid-area-paddingleft" data-name="padding-left" value="0" step="1" min="0">';
			$grid_layout .= '<label class="tomb-number-label">px</label>';
			$grid_layout .= '<label class="tomb-label">'.__( 'Padding right:', 'tg-text-domain' ).'</label>';
			$grid_layout .= '<input type="number" class="tomb-text number mini" id="the-grid-area-paddingright" data-name="padding-right" value="0" step="1" min="0">';
			$grid_layout .= '<label class="tomb-number-label">px</label>';
			$grid_layout .= '<label class="tomb-label">'.__( 'Background color:', 'tg-text-domain' ).'</label>';
			$grid_layout .= '<input class="tomb-colorpicker" data-alpha="1" id="the-grid-area-background" data-name="background" value="">';
			$grid_layout .= '<div id="tg-layout-styles-footer">';
				$grid_layout .= '<div class="tg-button" id="tg-button-save-styles"><i class="dashicons dashicons-yes"></i>'.__( 'Save Changes', 'tg-text-domain' ).'</div>';
			$grid_layout .= '</div>';
		$grid_layout .= '</div>';
		
		return $grid_layout;
	}
	
	/**
	* Build drag/drop meta query
	* @since 1.0.0
	*/
	public static function grid_meta_key_config() {
		
		$post_ID = self::get_post_ID();
		
		$prefix = TG_PREFIX;

		$meta_query  = '<div class="tg-button" id="tg-add-metakey"><i class="dashicons dashicons-plus"></i>'.__( 'Add a Meta Key', 'tg-text-domain' ).'</div>';
		$meta_query .= '<div class="tg-button" id="tg-add-relation"><i class="dashicons dashicons-plus"></i>'.__( 'Add relation', 'tg-text-domain' ).'</div>';
		$meta_query .= '<div class="tomb-row" id="the_grid_meta_query">';
			$meta_query .= '<input class="tg-hidden-meta-key" name="the_grid_meta_query" data-metakey=\''.get_post_meta($post_ID, $prefix.'meta_query', true).'\' value=\''.get_post_meta($post_ID, $prefix.'meta_query', true).'\'>';
		$meta_query .= '</div>';
		
		return $meta_query;
	}
	
	/**
	* Build Select Post Type for skin
	* @since 1.0.0
	*/
	public static function grid_skin_post_type() {
		
		$post_ID = self::get_post_ID();
		$prefix  = TG_PREFIX;
		
		$post_types = get_post_meta($post_ID, $prefix.'post_type', true);
		$post_types = (isset($post_types) && is_array($post_types)) ? $post_types : array('post' => 'post');
		
		$options = null;
		foreach ($post_types as $post_type) {
			if (post_type_exists($post_type)) {
				$obj  = get_post_type_object($post_type);
				$name = $obj->labels->name;
				$options .= '<option value="'.$post_type.'" data-name="'.$name.'">'.$name.'</option>';
			}
		}
		
		$html  = '<label class="tomb-label">'.__( 'Select a skin for:', 'tg-text-domain' ).'</label>';
		$html .= '<div class="tomb-spacer" style="height: 5px"></div>';
		
		
		$html .= '<div class="tomb-select-holder" data-clear="" style="width:180px">';
			
			$html .= '<div class="tomb-select-fake">';
				$html .= '<span class="tomb-select-value"></span>';
				$html .= '<span class="tomb-select-arrow"><i></i></span>';
			$html .= '</div>';	
		
			$html .= '<select class="tomb-select tomb-post-type-skin" data-width="180">';
			$html .= $options;
			$html .= '</select>';
		
		$html .= '</div>';	
		
		$skins  = get_post_meta($post_ID, $prefix.'skins', true);
		$html .= '<div class="tomb-row" id="the_grid_skins">';
		$html .= '<input type="hidden" class="tomb-grid-skins" name="the_grid_skins" value=\''.$skins.'\'>';
		$html .= '</div>';
		
		return $html;
		
	}
	
	/**
	* Get all skins
	* @since 1.0.0
	*/
	public static function get_all_grid_skins() {
			
		$item_base  = new The_Grid_Item_Skin();
		$item_skins = $item_base->get_skin_names();
		
		foreach($item_skins as $item_skin => $skin) {
			$skin_name = str_replace('-', ' ', $skin['name']);
			$skin_name = str_replace('_', ' ', $skin_name);
			$skin_name = ucwords(preg_replace('/[^A-Za-z0-9\-]/', ' ', $skin_name));
			$skins[$skin['type']][esc_attr($skin['slug'])] = esc_attr($skin_name);
		}
		
		$skins['grid'] = (isset($skins['grid']) && is_array($skins['grid'])) ? $skins['grid'] : array();
		$skins['masonry'] = (isset($skins['masonry']) && is_array($skins['masonry'])) ? $skins['masonry'] : array();
		$grid = array('grid-disabled' =>  __( 'Grid/Justified Skins', 'tg-text-domain' ));
		$maso = array('masonry-disabled' => __( 'Masonry Skins', 'tg-text-domain' ));
		$skin_list = array_merge($grid,$skins['grid'],$maso,$skins['masonry']);
		
		return $skin_list;
	}
	
}