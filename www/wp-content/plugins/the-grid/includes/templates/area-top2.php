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

$tg_area_elements = $tg_grid_data['area_top2_elements'];

if (!empty($tg_area_elements)) {

	$area  = '<!-- The Grid Area Top 2 -->';
	$area .= '<div class="tg-grid-area-top2">';
		foreach($tg_area_elements as $tg_area_element) {
			$area .= $tg_area_element;
		}
	$area .= '</div>';
	
	echo $area;

}
