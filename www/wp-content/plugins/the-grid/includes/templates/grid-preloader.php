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

if (!empty($tg_grid_data['preloader_style'])) {
	
	$grid_ID = esc_attr($tg_grid_data['ID']);
	
	// retrieve the preloader skin
	$preloader_base   = new The_Grid_Preloader_Skin();
	$preloader_name   = esc_attr($tg_grid_data['preloader_style']);
	$preloader_color  = esc_attr($tg_grid_data['preloader_color']);
	$preloader_size   = esc_attr($tg_grid_data['preloader_size']);
		
	$preloader_border = ($preloader_name == 'pacman' || $preloader_name == 'ball-clip-rotate') ? '#'.$grid_ID.' .tg-grid-preloader-inner>div{border-color:'.$preloader_color.'}' : null;
	$preloader_color  = '#'.$grid_ID.' .tg-grid-preloader-inner>div{background:'.$preloader_color.'}';
	$preloader_color  = $preloader_color.$preloader_border;
	$preloader_size   = '#'.$grid_ID.' .tg-grid-preloader-scale{transform:scale('.$preloader_size.')}';
	$preloader_skin   = $preloader_base->$preloader_name();
	// build preloader
	$preloader  = '<div class="tg-grid-preloader">';
		$preloader .= '<style class="tg-grid-preloader-styles" type="text/css" scoped>'.$preloader_skin['css'].$preloader_color.$preloader_size.'</style>';
		$preloader .= '<div class="tg-grid-preloader-holder">';	
			$preloader .= '<div class="tg-grid-preloader-scale">';
				$preloader .= '<div class="tg-grid-preloader-inner '.esc_attr($preloader_name).'">';	
					$preloader .= $preloader_skin['html'];
				$preloader .= '</div>';
			$preloader .= '</div>';
		$preloader .= '</div>';
	$preloader .= '</div>';	
	
	echo $preloader;

}