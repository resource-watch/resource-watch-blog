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

$holder_start  = '<!-- The Grid Items Holder -->';
$holder_start .= '<div class="tg-grid-holder tg-layout-'.esc_attr($tg_grid_data['style']).'" '.$tg_grid_data['layout_data'].'>';

echo $holder_start;
