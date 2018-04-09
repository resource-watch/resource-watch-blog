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

$tg_area_elements = $tg_grid_data['area_left_elements'];

if (!empty($tg_area_elements)) {

	$area  = '<!-- The Grid Area Left -->';
	$area .= '<div class="tg-grid-area-left">';
		$area .= '<div class="tg-grid-area-inner">';
			$area .= '<div class="tg-grid-area-wrapper">';
				foreach($tg_area_elements as $tg_area_element) {
					$area .= $tg_area_element;
				}
			$area  .= '</div>';
		$area  .= '</div>';
	$area .= '</div>';
	
	echo $area;

}