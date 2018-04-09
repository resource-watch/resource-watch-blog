<?php
/**
 * Featured image meta checkbox to inline SVG
 *
 * Allow users to select whether featured images should contain the SVG Support class
 * Check if the featured image is SVG first, then display meta box for SVG only.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Add checkbox to the featured image metabox
 */
function bodhi_svgs_featured_image_meta( $content ) {

	global $post;

	// check if featured image is set and has extension of .svg or .svgz
	// need to make this check on the moment that the thumbnail shows up in the meta box.
	if ( strpos( get_the_post_thumbnail(), '.svg' ) ) {

		$text 	= __( 'Render this SVG inline (advanced)', 'svg-support' );
		$id 	= 'inline_featured_image';
		$value 	= esc_attr( get_post_meta( $post->ID, $id, true ) );
		$label 	= '<label for="' . $id . '" class="selectit"><input name="' . $id . '" type="checkbox" id="' . $id . '" value="' . $value . ' "'. checked( $value, 1, false) .'> ' . $text .'</label>';

		return $content .= $label;

	} else {

		return $content;

	}

}
if ( bodhi_svgs_advanced_mode() ) {
	add_filter( 'admin_post_thumbnail_html', 'bodhi_svgs_featured_image_meta' );
}

/**
 * Save featured image meta data when saved
 */
function bodhi_svgs_save_featured_image_meta( $post_id, $post, $update ) {

	$value = 0;
	if ( isset( $_REQUEST['inline_featured_image'] ) ) {
		$value = 1;
	}

	// Check if post type supports 'thumbnail' (Featured Image)
	if ( post_type_supports( get_post_type( $post_id ), 'thumbnail' ) ) {

		// set meta value to either 1 or 0
		update_post_meta( $post_id, 'inline_featured_image', $value );

	}

}
add_action( 'save_post', 'bodhi_svgs_save_featured_image_meta', 10, 3 );

/**
 * Add class to the featured image output on front end
 */
function bodhi_svgs_add_class_to_thumbnail( $thumb ) {

	$inline_featured_image = get_post_meta( get_the_ID(), 'inline_featured_image' );

	if ( is_array( $inline_featured_image ) && in_array( 1, $inline_featured_image ) ) {

		global $bodhi_svgs_options;

		if ( ! empty( $bodhi_svgs_options['css_target'] ) ) {

			$target_class = $bodhi_svgs_options['css_target'];

		} else {

			$target_class = 'style-svg';

		}

		if ( is_single() ) {

			$thumb = str_replace( 'attachment-', $target_class . ' attachment-', $thumb );

		}

	}

	return $thumb;

}
if ( bodhi_svgs_advanced_mode() ) {
	add_filter( 'post_thumbnail_html', 'bodhi_svgs_add_class_to_thumbnail' );
}