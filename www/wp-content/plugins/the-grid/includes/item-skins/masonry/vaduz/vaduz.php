<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Vaduz
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$format = $tg_el->get_item_format();
$colors = $tg_el->get_colors();
$image  = $tg_el->get_attachment_url();

$com_args = array(
	'icon' => '<i class="tg-icon-chat"></i>'
);

$excerpt_args = array(
	'length' => 140
);
	
$media_args = array(
	'icons' => array(
		'image' => '<i class="tg-icon-arrows-out"></i>'
	)
);

if ($format == 'quote' || $format == 'link') {
		
	$output  = ($image) ? '<div class="tg-item-image" style="background-image: url('.esc_url($image).')"></div>' : null;
	$output .= $tg_el->get_content_wrapper_start();
		$output .= '<i class="tg-'.$format.'-icon tg-icon-'.$format.'" style="color:'.$colors['content']['title'].'"></i>';
		$output .= ($format == 'quote') ? $tg_el->get_the_quote_format() : $tg_el->get_the_link_format();
		$output .= '<div class="tg-item-footer">';
			$output .= $tg_el->get_the_views_number();
			$output .= $tg_el->get_the_comments_number($com_args);
			$output .= $tg_el->get_the_likes_number();
		$output .= '</div>';
	$output .= $tg_el->get_content_wrapper_end();
	
	return $output;
		
} else {
	
	$output = null;
	$media_content = $tg_el->get_media();

	if ($media_content) {
		
		$media_button  = preg_replace('/(<i\b[^><]*)>/i', '$1 style="color:'.$colors['overlay']['background'].'">', $tg_el->get_media_button($media_args));
	
		$output .= $tg_el->get_media_wrapper_start();
			$output .= $media_content;
			$output .= ($image || in_array($format, array('gallery', 'video'))) ? $media_button : null;
			$output .= $tg_el->get_the_duration();
		$output .= $tg_el->get_media_wrapper_end();
	
	}
	
	$output .= $tg_el->get_content_wrapper_start();
		$output .= $tg_el->get_the_title();
		$output .= $tg_el->get_the_date();
		$output .= $tg_el->get_the_excerpt($excerpt_args);
		$output .= '<div class="tg-item-footer">';
			$output .= $tg_el->get_the_views_number();
			$output .= $tg_el->get_the_comments_number($com_args);
			$output .= $tg_el->get_the_likes_number();
		$output .= '</div>';
	$output .= $tg_el->get_content_wrapper_end();
	
	return $output;

}