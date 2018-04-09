<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Lusaka
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$permalink  = $tg_el->get_the_permalink();
$url_target = $tg_el->get_the_permalink_target();
$media      = $tg_el->get_media();

$output  = $tg_el->get_media_wrapper_start();
	$output .= $media;
	$output .= ($media) ? '<a class="tg-item-link" href="'.$permalink.'" target="'.$url_target.'"></a>' : null;
$output .= $tg_el->get_media_wrapper_end();
		
return $output;