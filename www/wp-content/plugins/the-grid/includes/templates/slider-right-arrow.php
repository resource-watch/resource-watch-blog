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
	
	$arrow = '<div class="tg-right-arrow tg-nav-color tg-nav-font">';
		$arrow .= '<i class="tg-icon-right-arrow tg-nav-color tg-nav-border tg-nav-font"></i>';
	$arrow .= '</div>';
	
	echo $arrow;
	
}