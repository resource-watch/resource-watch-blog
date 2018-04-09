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

// main filter global var settings
$options = $tg_grid_data['filters'];
$all_txt = $tg_grid_data['filter_all_text'];
$count   = $tg_grid_data['filter_count'];
$onload  = $tg_grid_data['active_filters'];
$dropdown_title = $tg_grid_data['filter_dropdown_title'];

$counter    = ($count == 'inline') ? ' (<span class="tg-filter-count"></span>)' : null;
$data_count = ($count == 'tooltip') ? ' data-count="1"' : null;
					
// mobile detection (preserve natural dropdown list on mobile devices)
$mobile = (wp_is_mobile()) ? ' is-mobile' : null;
			
$output = '<div class="tg-filters-holder">';
	$output .= '<div class="tg-dropdown-holder tg-nav-border tg-nav-font">';
		$output .= '<span class="tg-dropdown-title tg-nav-color tg-nav-font">'.esc_attr($dropdown_title).'</span>';
		$output .= '<i class="tg-icon-dropdown-open tg-nav-color tg-nav-font"></i>';
		
		if ($mobile != null) {
			
			$multi_op = ($tg_grid_data['filter_combination']) ? ' multiple' : null;
			
			$output .= '<select class="tg-dropdown-list'.esc_attr($mobile).'" '.$multi_op.'>';
			
				if (!empty($all_txt)) {
					$active_all = (empty($onload)) ? 'tg-filter-active' : null;
					$selected   = ($active_all) ? ' selected' : null;
					$output .= '<option class="tg-dropdown-item tg-filter '.esc_attr($active_all).'" data-filter="*"'.esc_attr($selected).'>';
					$output .= esc_attr($all_txt);
					$output .= '</option>';
				}
				
				foreach ($options as $option) {
					$taxonomy = $option['taxo'];
					$filter   = $option['id'];
					$name     = $option['name'];
					$class    = (in_array($filter, $onload)) ? ' tg-filter-active' : null;
					$selected = ($class) ? ' selected' : null;
					$filter   = (is_numeric($filter)) ? '.f'.$filter : $filter;
					$filter   = ' data-filter="'. esc_attr($filter) .'"';
					$taxonomy = (isset($taxonomy) && !empty($taxonomy)) ?  ' data-taxo="'. esc_attr($taxonomy) .'"' : null;

					$output .= '<option class="tg-dropdown-item tg-filter'.esc_attr($class).'"'.$taxonomy.$filter.esc_attr($selected).'>';
					$output .= esc_attr($name);
					$output .= '</option>';
				}
				
			$output .= '</select>';
			
		} else {
			
			$output .= '<ul class="tg-dropdown-list '.$tg_grid_data['ID'].'">';
			
				if (!empty($all_txt)) {
					
					$active_all = (empty($onload)) ? 'tg-filter-active' : null;
					
					$output .= '<li class="tg-dropdown-item tg-filter '.esc_attr($active_all).'" data-filter="*">';
						$output .= '<span class="tg-filter-name">'.esc_attr($all_txt).'</span>';
						if ($count != 'none') {
							$output .= '&nbsp;<span>(</span><span class="tg-filter-count"></span><span>)</span>';
						}
					$output .= '</li>';
					
				}
				foreach ($options as $option) {
					
					$taxonomy = $option['taxo'];
					$filter   = $option['id'];
					$name     = $option['name'];
					$class    = (in_array($filter, $onload)) ? ' tg-filter-active' : null;
					$filter   = (is_numeric($filter)) ? '.f'.$filter : $filter;
					$filter   = ' data-filter="'. esc_attr($filter) .'"';
					$taxonomy = (isset($taxonomy) && !empty($taxonomy)) ?  ' data-taxo="'. esc_attr($taxonomy) .'"' : null;

					$output .= '<li class="tg-dropdown-item tg-filter'.esc_attr($class).'"'.$taxonomy.$filter.'>';
						$output .= '<span class="tg-filter-name">'.esc_attr($name).'</span>';
						if ($count != 'none') {
							$output .= '&nbsp;<span>(</span><span class="tg-filter-count"></span><span>)</span>';
						}
					$output .= '</li>';
					
				}
				
			$output .= '</ul>';
			
		}
		
	$output .= '</div>';
	
$output .= '</div>';
		
echo $output;