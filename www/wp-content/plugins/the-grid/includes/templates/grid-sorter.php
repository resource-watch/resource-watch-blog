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

$base    = new The_Grid_Base();
$sorting = $base->grid_sorting();

$sortBy       = $tg_grid_data['sort_by'];
$sort_txt     = $tg_grid_data['sort_by_text'];
$sort_onload  = $tg_grid_data['sort_by_onload'];
$sort_onload  = (in_array($sort_onload,$sortBy) && isset($sorting[$sort_onload])) ? $sorting[$sort_onload] : '';
$order_onload = $tg_grid_data['sort_order_onload'];
		
if (isset($sortBy) && !empty($sortBy)) {
			
	// mobile detection (preserve natural dropdown list on mobile devices)
	$mobile = (wp_is_mobile()) ? ' is-mobile' : null;
			
	$sorter  = '<!-- The Grid sorters holder -->';
	$sorter .= '<div class="tg-sorters-holder">';
		$sorter .= '<div class="tg-dropdown-holder tg-nav-border tg-nav-font'.esc_attr($mobile).'">';
			$sorter .= '<span class="tg-dropdown-title tg-nav-color tg-nav-font">'.esc_attr($sort_txt).'</span>';
			$sorter .= '&nbsp;<span class="tg-dropdown-value tg-nav-color tg-nav-font">'.ucfirst(esc_attr($sort_onload)).'</span>';
			if ($mobile != null) {
				$sorter .= '<select class="tg-dropdown-list tg-sorter'.esc_attr($mobile).'">';
					foreach ($sortBy as $sort) {
						if (isset($sorting[$sort])) {
							$name = strtolower($sort[0] == '-') ? substr($sort, 1) : $sort;
							$name = strtolower($name[0] == '_') ? substr($name, 1) : $name;
							$sorter .= '<option class="tg-dropdown-item" data-value="'.esc_attr(str_replace('woo_','', $name)).'">'.ucfirst(esc_attr($sorting[$sort])).'</option>';
						}
					}
				$sorter .= '</select>';
			} else {
				$sorter .= '<ul class="tg-dropdown-list tg-sorter '.$tg_grid_data['ID'].'">';
					foreach ($sortBy as $sort) {
						if (isset($sorting[$sort])) {
							$name = strtolower($sort[0] == '-') ? substr($sort, 1) : $sort;
							$name = strtolower($name[0] == '_') ? substr($name, 1) : $name;
							$sorter .= '<li class="tg-dropdown-item"  data-value="'.esc_attr(str_replace('woo_','', $name)).'">'.ucfirst(esc_attr($sorting[$sort])).'</li>';
						}
					}
				$sorter .= '</ul>';
			}
		$sorter .= '</div>';
		$sorter .= '<div class="tg-sorter-order tg-nav-color tg-nav-border" data-asc="'.esc_attr($order_onload).'">';
			$sorter .= '<i class="tg-icon-sorter-down tg-nav-color tg-nav-font"></i>';
			$sorter .= '<i class="tg-icon-sorter-up tg-nav-color tg-nav-font"></i>';
		$sorter .= '</div>';
	$sorter .= '</div>';

	echo $sorter;
	
}