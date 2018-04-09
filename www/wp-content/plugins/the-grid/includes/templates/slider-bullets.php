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
		
if ($tg_grid_data['layout'] == 'horizontal') {
	
	$bullets  = '<!-- The Grid Slider Bullets -->';
	$bullets .= '<div class="tg-slider-bullets-holder">';
		$bullets .= '<div class="tg-slider-bullets"></div>';
	$bullets .= '</div>';
		
	echo $bullets;
	
}