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
		
if ($tg_grid_data['ajax_method'] == 'on_scroll') {
		
	if (!empty($tg_grid_data['ajax_button_loading'])) {
		
		$ajax_scroll  = '<!-- The Grid Ajax Scroll -->';
		$ajax_scroll .= '<div class="tg-ajax-scroll-holder">';
		$ajax_scroll .= '<div class="tg-ajax-scroll" data-no-more="'.esc_attr($tg_grid_data['ajax_button_no_more']).'">'.esc_html($tg_grid_data['ajax_button_loading']).'</div>';
		$ajax_scroll .= '</div>';
		
		echo $ajax_scroll;
	}
			
}