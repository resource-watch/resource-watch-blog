<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Apia
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$output  = $tg_el->get_media_wrapper_start();
	$output .= $tg_el->get_media();
	$output .= $tg_el->get_overlay();
	$output .= $tg_el->get_media_button();
$output .= $tg_el->get_media_wrapper_end();
		
return $output;