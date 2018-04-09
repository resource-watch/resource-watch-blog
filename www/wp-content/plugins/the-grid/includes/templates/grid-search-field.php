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
	
$search  = '<!-- The Grid Search holder -->';
$search .= '<div class="tg-search-holder">';
	$search .= '<div class="tg-search-inner tg-nav-border">';
		$search .= '<span class="tg-search-icon tg-nav-color tg-nav-font"></span>';
		$search .= '<input type="text" class="tg-search tg-nav-color tg-nav-font" autocomplete="off" placeholder="'. esc_attr($tg_grid_data['search_text']) .'" />';
		$search .= '<span class="tg-search-clear tg-nav-color tg-nav-border tg-nav-font"></span>';
	$search .= '</div>';
$search .= '</div>';
		
echo $search;