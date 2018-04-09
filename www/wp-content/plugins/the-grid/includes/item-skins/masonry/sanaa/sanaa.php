<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Skin: Sanaa
 *
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$tg_el = The_Grid_Elements();

// main data
$colors       = $tg_el->get_colors();
$image        = $tg_el->get_attachment_url();
$permalink    = $tg_el->get_the_permalink();
$target       = $tg_el->get_the_permalink_target();
$background   = 'style="background:'.$colors['overlay']['title'].'"';
$cart_loader  = '<div class="tg-woo-loading"><span class="dot1" '.$background.'></span><span class="dot2" '.$background.'></span></div>';
// product data
$product_cart     = str_replace('</div>',$cart_loader.'</div>',$tg_el->get_product_cart_button());
$product_wishlist = $tg_el->get_product_wishlist();

$media_content = $tg_el->get_media();

$output = null;

if ($media_content) {
	
	$output .= $tg_el->get_media_wrapper_start();
		$output .= $media_content;
		$output .= ($image && $permalink ) ? '<a class="tg-woo-link" href="'.$permalink .'" target="'.$target.'"></a>' : null;
		$output .= ($image) ? $tg_el->get_product_on_sale() : null;
		$output .= ($image) ? $tg_el->get_product_rating() : null;
		$output .= ($image && $product_cart) ? '<div class="tg-item-cart-holder">' : null;
			$output .= ($image && $product_cart) ? $tg_el->get_overlay() : null;
			$output .= ($image && $product_cart) ? preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'.$colors['overlay']['title'].'">', $product_cart) : null;
		$output .= ($image && $product_cart) ? '</div>' : null;
	$output .= $tg_el->get_media_wrapper_end();

}
	
$output .= $tg_el->get_content_wrapper_start();
	$output .= $tg_el->get_the_title();
	$output .= $tg_el->get_product_full_price();
	$output .= preg_replace('/(<a\b[^><]*)>/i', '$1 style="color:'.$colors['content']['span'].'">', $product_wishlist);
$output .= $tg_el->get_content_wrapper_end();

return $output;