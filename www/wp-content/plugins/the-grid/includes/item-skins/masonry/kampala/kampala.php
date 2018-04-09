<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Kampala
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

$terms_args = array(
	'color' => 'color',
	'separator' => ', '
);

$media_args = array(
	'icons' => array(
		'image' => '<i class="tg-icon-add"></i>'
	)
);

if ($format == 'quote' || $format == 'link') {
	
	$output  = ($image) ? '<div class="tg-item-image" style="background-image: url('.esc_url($image).')"></div>' : null;
	$output .= $tg_el->get_content_wrapper_start();
		$output .= $tg_el->get_the_date();
		$output .= ($format == 'quote') ? $tg_el->get_the_quote_format() : $tg_el->get_the_link_format();
		$output .= '<div class="tg-item-footer">';
			$output .= '<i class="tg-'.$format.'-icon tg-icon-'.$format.'" style="color:'.$colors['content']['title'].'"></i>';
			$output .= $tg_el->get_the_likes_number();
		$output .= '</div>';
	$output .= $tg_el->get_content_wrapper_end();
	
	return $output;
		
} else {
	
	$output = null;
	$media_content = $tg_el->get_media();
	$social = $tg_el->get_social_share_links();
	
	$social_button  = null;
	if (!empty($social)) {
		$social_button = '<div class="tg-item-share-holder">';
			$social_button .= '<div class="triangle-up-left" style="border-color:'.$colors['overlay']['background'].'"></div>';
			$social_button .= '<i class="tg-icon-reply" style="color:'.$colors['overlay']['title'].'"></i>';
			$social_button .= '<div class="tg-share-icons">';
				foreach ($social as $url) {
					$social_button .= $url;
				}
			$social_button .= '</div>';
		$social_button .= '</div>';
	}
	
	$decoration  = '<div class="tg-item-decoration">';
		$decoration .= '<div class="triangle-down-right" style="border-color:'.$colors['content']['background'].'"></div>';
	$decoration .= '</div>';
	
	if ($media_content) {
		$link_button = $tg_el->get_link_button();
		$output .= $tg_el->get_media_wrapper_start();
			$output .= $media_content;
			if ($image || in_array($format, array('gallery', 'video'))) {
				$output .= $tg_el->get_center_wrapper_start();
				$output .= (in_array($format, array('video', 'audio'))) ? $tg_el->get_overlay().$tg_el->get_media_button($media_args) : null;
				$output .= (!in_array($format, array('video', 'audio')) && $link_button) ? $tg_el->get_overlay().$link_button : null;
				$output .= $tg_el->get_center_wrapper_end();
				$output .= $decoration;
				$output .= $social_button;
			}
		$output .= $tg_el->get_media_wrapper_end();
	}
	
	$output .= $tg_el->get_content_wrapper_start();
		$output .= $tg_el->get_the_date();
		$output .= $tg_el->get_the_title();
		$output .= $tg_el->get_the_terms($terms_args);
		$output .= $tg_el->get_the_excerpt();
		$output .= '<div class="tg-item-footer">';
			$output .= $tg_el->get_the_comments_number();
			$output .= $tg_el->get_the_likes_number();
		$output .= '</div>';
	$output .= $tg_el->get_content_wrapper_end();

	return $output;

}