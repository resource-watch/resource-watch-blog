<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Quito
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

$excerpt_args = array(
	'length' => 200,
);

$readmore_args = array(
	'text' => __( 'Read More', 'tg-text-domain' ),
);

$link_arg = array(
	'icon' => __( 'Read More', 'tg-text-domain' )
);

$terms_args = array(
	'color'     => 'background',
	'separator' => ''
);

$media_args = array(
	'icons' => array(
		'audio' => __( 'Play Song', 'tg-text-domain' ),
		'video' => __( 'Play Video', 'tg-text-domain' ),
	)
);

$comments = preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'.$colors['content']['span'].'">', $tg_el->get_the_comments_number());

if ($format == 'quote' || $format == 'link') {

	$output  = ($image) ? '<div class="tg-item-image" style="background-image: url('.esc_url($image).')"></div>' : null;
	$output .= $tg_el->get_content_wrapper_start();
		$output .= '<i class="tg-'.$format.'-icon tg-icon-'.$format.'" style="color:'.$colors['content']['title'].'"></i>';
		$output .= ($format == 'quote') ? $tg_el->get_the_quote_format() : $tg_el->get_the_link_format();
		$output .= '<div class="tg-item-footer">';
			$output .= $tg_el->get_the_date();
			$output .= ($comments) ? '<span>/</span>' : null;
			$output .= $comments;
		$output .= '</div>';
	$output .= $tg_el->get_content_wrapper_end();
	
	return $output;
	
} else {
	
	$output = null;
	$media_content = $tg_el->get_media();
	
	if ($media_content) {
		$output .= $tg_el->get_media_wrapper_start();
			$output .= $media_content;
			if ($image || in_array($format, array('gallery', 'video'))) {
				$output .= $tg_el->get_overlay();
				$output .= $tg_el->get_center_wrapper_start();
					$output .= (in_array($format, array('video', 'audio'))) ? $tg_el->get_media_button($media_args) : null;
					$output .= (!in_array($format, array('video', 'audio'))) ? $tg_el->get_link_button($link_arg) : null;
				$output .= $tg_el->get_center_wrapper_end();
			}
		$output .= $tg_el->get_media_wrapper_end();
	}
	
	$output .= $tg_el->get_content_wrapper_start();
		$output .= $tg_el->get_the_terms($terms_args);
		$output .= $tg_el->get_the_title();
		$output .= $tg_el->get_the_excerpt($excerpt_args);
		$output .= $tg_el->get_read_more_button($readmore_args);
		$output .= '<div class="tg-item-footer">';
			$output .= $tg_el->get_the_date();
			$output .= ($comments) ? '<span>/</span>' : null;
			$output .= $comments;
		$output .= '</div>';
	$output .= $tg_el->get_content_wrapper_end();

	return $output;
		
}