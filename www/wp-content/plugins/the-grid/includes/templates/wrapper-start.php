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

$wrapper_start  = '<!-- The Grid Wrapper Start -->';
$wrapper_start .= '<div class="tg-grid-wrapper '.esc_attr($tg_grid_data['wrapper_css_class']).'" id="'.esc_attr($tg_grid_data['ID']).'" data-version="'.TG_VERSION.'">';

	if (!empty($tg_grid_data['grid_css'])) {
		$custom_css = wp_kses($tg_grid_data['grid_css'], array( '\'', '\"' ));
		$custom_css = str_replace('&gt;' , '>' , $custom_css);
		$wrapper_start .= ($tg_grid_data['grid_fonts']) ? '<!-- The Grid Fonts -->' : null;
		$wrapper_start .= $tg_grid_data['grid_fonts'];
		$wrapper_start .= '<!-- The Grid Styles -->';
		$wrapper_start .= '<style class="tg-grid-styles" type="text/css" scoped>'.$custom_css.'</style>';
	}
	
	$wrapper_start .= '<!-- The Grid Item Sizer -->';
	$wrapper_start .= '<div class="tg-grid-sizer"></div>';
	$wrapper_start .= '<!-- The Grid Gutter Sizer -->';
	$wrapper_start .= '<div class="tg-gutter-sizer"></div>';

echo $wrapper_start;
