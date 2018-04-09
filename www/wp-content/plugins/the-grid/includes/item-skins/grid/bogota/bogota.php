<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Bogota
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$format = $tg_el->get_item_format();
$colors = $tg_el->get_colors();

$com_args = array(
	'icon' => '<i class="tg-icon-comment-4"></i>'
);

$media_args = array(
	'icons' => array(
		'image' => ' ',
		'audio' => ' ',
		'video' => ' ',
	)
);

$like_button = preg_replace('/(<span\b[^><]*)>/i', '$1 style="color:'.$colors['overlay']['title'].'">', $tg_el->get_the_likes_number());
$like_button = preg_replace('/(<path\b[^><]*)>/i', '$1 style="stroke:'.$colors['overlay']['title'].' !important;fill:'.$colors['overlay']['title'].' !important">', $like_button);
$comments = preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'.$colors['overlay']['title'].'">', $tg_el->get_the_comments_number($com_args));
$comments = preg_replace('/(<i\b[^><]*)>/i', '$1 style="color:'.$colors['overlay']['title'].'">', $comments);

$output  = $tg_el->get_media_wrapper_start();
	$output .= $tg_el->get_media();
	$output .= ($format == 'video' || $format == 'audio') ? '<i class="tg-icon-play"></i>' : null;
	$output .= $tg_el->get_overlay();
	$output .= $tg_el->get_center_wrapper_start();
		$output .= $like_button;
		$output .= $comments;
	$output .= $tg_el->get_center_wrapper_end();
	$output .= $tg_el->get_media_button($media_args);
$output .= $tg_el->get_media_wrapper_end();
		
return $output;