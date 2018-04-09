<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Oslo
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$terms_args = array(
	'color' => 'color',
	'separator' => ', '
);

$media_args = array(
	'icons' => array(
		'image' => '<i class="tg-icon-arrows-diagonal"></i>'
	)
);

$permalink = $tg_el->get_the_permalink();
$target    = $tg_el->get_the_permalink_target();
$colors    = $tg_el->get_colors();
$media_button = $tg_el->get_media_button($media_args);

$output = $tg_el->get_media_wrapper_start();
	$output .= $tg_el->get_media();
$output .= $tg_el->get_media_wrapper_end();
$output .= '<div class="tg-item-content-holder '.$colors['overlay']['class'].'">';	
	$output .= $tg_el->get_overlay();
	$output .= $tg_el->get_center_wrapper_start();	
		$output .= ($permalink && $media_button) ? '<a class="tg-item-link" href="'.$permalink.'" target="'.$target.'"></a>' : null;
		$output .= $tg_el->get_the_title();	
		$output .= $tg_el->get_the_terms($terms_args);
	$output .= $tg_el->get_center_wrapper_end();
	$output .= $media_button;
	$output .= $tg_el->get_the_likes_number();
$output .= '</div>';
		
return $output;