<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Sofia
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$format = $tg_el->get_item_format();
$colors = $tg_el->get_colors();

$link_arg = array(
	'icon' => '<i class="tg-icon-add"></i>'.__( 'Read More', 'tg-text-domain' )
);

$media_args = array(
	'icons' => array(
		'image' => '<i class="tg-icon-add"></i>',
		'audio' => '<i class="tg-icon-play"></i>'.__( 'Play Song', 'tg-text-domain' ),
		'video' => '<i class="tg-icon-play"></i>'.__( 'Play Video', 'tg-text-domain' ),
	)
);

$base  = new The_Grid_Base();
$color = $colors['overlay']['background'];
$gradient = null;
if (!empty($color)) {
	$color = str_replace(array('#','(',')','rgba','rgb'), array('','','','',''), $color);
	if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{6})$/i",$color)) {
		$color3 = $color;
		$color  = $base->HEX2RGB($color,$alpha=1);
		$color1 = $color['red'].','.$color['green'].','.$color['blue'];
		$color2 = $color1.',1';	
	} else {
		$color = explode(',', $color);
		$alpha = (isset($color[3])) ? $color[3] : 1;
		$color1 = $color[0].','.$color[1].','.$color[2];
		$color2 = $color1.','.$alpha;
		$color3 = $base->RGB2HEX($color);	
	}
	$gradient = 'style="background:transparent;background: linear-gradient(top, rgba('.$color1.',0) 0%, rgba('.$color2.') 100%);background: -moz-linear-gradient(top, rgba('.$color1.',0) 0%, rgba('.$color2.') 100%);background: -ms-linear-gradient(top, rgba('.$color1.',0) 0%, rgba('.$color2.') 100%);background: -o-linear-gradient( top, rgba('.$color1.',0) 0%, rgba('.$color2.') 100%);background: -webkit-linear-gradient( top, rgba('.$color1.',0) 0%, rgba('.$color2.') 100%);-ms-filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#00'.$color3.', endColorstr=#ff'.$color3.');filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#00'.$color3.', endColorstr=#ff'.$color3.');"';
}

$content_wrapper = str_replace(array('tg-light', 'tg-dark'), $colors['overlay']['class'], $tg_el->get_content_wrapper_start());

$output = '<div class="tg-panZ">';
	
	$output .= $tg_el->get_media_wrapper_start();
		$output .= $tg_el->get_media();
	$output .= $tg_el->get_media_wrapper_end();
	$output .= '<div class="tg-item-overlay" '.$gradient.'></div>';
		
	$output .= $content_wrapper;
		$output .= '<div class="tg-item-content-inner">';
			$output .= $tg_el->get_the_title();
			$output .= (in_array($format, array('video', 'audio'))) ? $tg_el->get_media_button($media_args) : null;
			$output .= (!in_array($format, array('video', 'audio'))) ? $tg_el->get_link_button($link_arg) : null;
		$output .= '</div>';
	$output .= $tg_el->get_content_wrapper_end();
		
$output .= '</div>';

return $output;