<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Doha
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$image  = $tg_el->get_attachment_url();
$format = $tg_el->get_item_format();

$output  = $tg_el->get_media_wrapper_start();
	$output .= $tg_el->get_media();
	if ($image || in_array($format, array('gallery', 'video'))) {
		$output .= $tg_el->get_overlay();
		$output .= $tg_el->get_media_button();
	}
$output .= $tg_el->get_media_wrapper_end();
		
return $output;