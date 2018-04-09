<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Lisboa
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

$terms_args = array(
	'separator' => ', '
);

$excerpt_args = array(
	'length' => 100
);

$output = $tg_el->get_media_wrapper_start();
	$output .= $tg_el->get_media();
	$output .= $tg_el->get_center_wrapper_start();
		$output .= $tg_el->get_overlay();	
		$output .= $tg_el->get_the_title();
		$output .= $tg_el->get_the_excerpt($excerpt_args);
		$output .= $tg_el->get_the_terms($terms_args);
	$output .= $tg_el->get_center_wrapper_end();
	$output .= $tg_el->get_media_button();
$output .= $tg_el->get_media_wrapper_end();
		
return $output;