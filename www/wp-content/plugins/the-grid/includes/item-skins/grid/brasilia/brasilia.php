<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Brasilia
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$format    = $tg_el->get_item_format();
$colors    = $tg_el->get_colors();
$permalink = $tg_el->get_the_permalink();
$target    = $tg_el->get_the_permalink_target();

$terms_args = array(
	'color' => 'color',
	'separator' => ', '
);

$output  = $tg_el->get_media_wrapper_start();
	$output .= $tg_el->get_media();
	$output .= $tg_el->get_overlay();
	$output .= '<div class="tg-item-content">';
		$output .= ($permalink && !in_array($format, array('video', 'audio'))) ? '<a class="tg-item-link" href="'.$permalink .'" target="'.$target.'"></a>' : null;
		$output .= $tg_el->get_the_terms($terms_args);
		$output .= $tg_el->get_the_title();
		$output .= '<div class="tg-item-footer">';
		$output .= preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'.$colors['overlay']['title'].'">', $tg_el->get_the_author());
		$output .= $tg_el->get_media_button();
		$output .= '</div>';
	$output .= '</div>';
$output .= $tg_el->get_media_wrapper_end();
		
return $output;