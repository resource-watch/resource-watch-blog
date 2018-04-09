<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Camberra
 *
 */
 
// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$format    = $tg_el->get_item_format();
$permalink = $tg_el->get_the_permalink();
$target    = $tg_el->get_the_permalink_target();

$media_args = array(
	'icons' => array(
		'image' => '<i class="tg-icon-arrows-out"></i>'
	)
);

$terms_args = array(
	'color' => 'color',
	'separator' => ', '
);

$output  = $tg_el->get_media_wrapper_start();
	$output .= $tg_el->get_media();
	$output .= '<div class="tg-item-content">';
		$output .= $tg_el->get_overlay();
		$output .= $tg_el->get_center_wrapper_start();
			$output .= ($permalink && !in_array($format, array('video', 'audio'))) ? '<a class="tg-item-link" href="'.$permalink .'" target="'.$target.'"></a>' : null;
			$output .= $tg_el->get_the_title();
			$output .= $tg_el->get_the_terms($terms_args);
		$output .= $tg_el->get_center_wrapper_end();
		$output .= $tg_el->get_media_button($media_args);
	$output .= '</div>';
$output .= $tg_el->get_media_wrapper_end();
		
return $output;