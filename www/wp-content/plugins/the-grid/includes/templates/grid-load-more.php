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

if ($tg_grid_data['source_type'] == 'post_type') {

	$item_total   = $tg_grid_data['item_total'];
	$item_loaded  = $tg_grid_data['item_number'];
	$item_remain  = $tg_grid_data['ajax_items_remain'];		
	$item_to_load = $item_total - $item_loaded;
	$button_text  = $tg_grid_data['ajax_button_text'];
	$button_count = ($item_remain) ? ' ('.$item_to_load.')' : null;

	if ($item_to_load > 0 && $item_loaded != -1) {
			
		$text      = ' data-button="'.esc_attr($button_text).'"';
		$loading   = ' data-loading="'.esc_attr($tg_grid_data['ajax_button_loading']).'"';
		$no_more   = ' data-no-more="'.esc_attr($tg_grid_data['ajax_button_no_more']).'"';
		$remain    = ' data-remain="'.esc_attr($item_remain).'"';
		$attribute = $text.$loading.$no_more.$remain;
	
		$ajax_button  = '<!-- The Grid Ajax Button -->';
		$ajax_button .= '<div class="tg-ajax-button-holder">';
			$ajax_button .= '<div class="tg-ajax-button tg-nav-color tg-nav-border tg-nav-font" data-item-tt="'.esc_attr($item_total).'"'.$attribute.'>';
				$ajax_button .= '<span class="tg-nav-color">'.esc_html($button_text.$button_count).'</span>';
			$ajax_button .= '</div>';
		$ajax_button .= '</div>';
		
		echo $ajax_button;
		
	}

} else {
	
	$text      = ' data-button="'.esc_attr($tg_grid_data['ajax_button_text']).'"';
	$loading   = ' data-loading="'.esc_attr($tg_grid_data['ajax_button_loading']).'"';
	$no_more   = ' data-no-more="'.esc_attr($tg_grid_data['ajax_button_no_more']).'"';
	$attribute = $text.$loading.$no_more;
		
	$ajax_button  = '<!-- The Grid Ajax Button -->';
	$ajax_button .= '<div class="tg-ajax-button-holder">';
		$ajax_button .= '<div class="tg-ajax-button tg-nav-color tg-nav-border tg-nav-font"'.$attribute.'>';
			$ajax_button .= '<span class="tg-nav-color">'.esc_attr($tg_grid_data['ajax_button_text']).'</span>';
		$ajax_button .= '</div>';
	$ajax_button .= '</div>';
			
	echo $ajax_button;

}