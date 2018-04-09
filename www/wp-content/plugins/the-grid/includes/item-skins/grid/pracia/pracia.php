<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Pracia
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

$permalink    = $tg_el->get_the_permalink();
$target       = $tg_el->get_the_permalink_target();
$media_button = $tg_el->get_media_button();
$link_button  = $tg_el->get_link_button();

$output = $tg_el->get_media_wrapper_start();
	$output .= $tg_el->get_media();
	$output .= $tg_el->get_overlay();
	$output .= $tg_el->get_center_wrapper_start();	
		$output .= ($link_button && $media_button) ? '<a class="tg-item-link" href="'.$permalink .'" target="'.$target.'"></a>' : null;
		$output .= $media_button;
		$output .= $link_button;
	$output .= $tg_el->get_center_wrapper_end();
$output .= $tg_el->get_media_wrapper_end();
$output .= $tg_el->get_content_wrapper_start();
	$output .= $tg_el->get_the_title();	
	$output .= $tg_el->get_the_terms($terms_args);
	$output .= $tg_el->get_the_likes_number();
$output .= $tg_el->get_content_wrapper_end();	
		
return $output;