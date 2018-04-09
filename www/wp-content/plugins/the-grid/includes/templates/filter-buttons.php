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
$buttons = $tg_grid_data['filters'];
$all_txt = $tg_grid_data['filter_all_text'];
$count   = $tg_grid_data['filter_count'];
$onload  = $tg_grid_data['active_filters'];

$counter    = ($count == 'inline') ? ' (<span class="tg-filter-count"></span>)' : null;
$data_count = ($count == 'tooltip') ? ' data-count="1"' : null;

$output = '<div class="tg-filters-holder">';
		
if (!empty($all_txt)) {
				
	$active_all = (empty($onload)) ? 'tg-filter-active' : null;
				
	$output .= '<div class="tg-filter '.esc_attr($active_all).' tg-nav-color tg-nav-border tg-nav-font" data-filter="*">';
		$output .= '<span class="tg-filter-name tg-nav-color tg-nav-font"'.$data_count.'>'.esc_attr($all_txt).$counter.'</span>';
	$output .= '</div>';
				
}

foreach ($buttons as $button) {

	$taxonomy = $button['taxo'];
	$filter   = $button['id'];
	$name     = $button['name'];
	$class    = (in_array($filter, $onload)) ? ' tg-filter-active' : null;
	$filter   = (is_numeric($filter)) ? '.f'.$filter : $filter;
	$filter   = ' data-filter="'. esc_attr($filter) .'"';
				
	$taxonomy = (isset($taxonomy) && !empty($taxonomy)) ?  ' data-taxo="'. esc_attr($taxonomy) .'"' : null;
				
	$output .= '<div class="tg-filter tg-nav-color tg-nav-border tg-nav-font'.esc_attr($class).'"'.$taxonomy.$filter.'>';
		$output .= '<span class="tg-filter-name tg-nav-color tg-nav-font"'.$data_count.'>'.esc_attr($name).$counter.'</span>';
	$output .= '</div>';
				
}
		
$output .= '</div>';

echo $output;