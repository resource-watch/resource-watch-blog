<?php
/**
 * Add SVG mime types to WordPress
 *
 * Allows you to upload SVG files to the media library like any other image.
 * Additionally provides a fix for WP 4.7.1 - 4.7.2 upload issues.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Add Mime Types
 */
function bodhi_svgs_upload_mimes( $mimes = array() ) {

	global $bodhi_svgs_options;

	if ( empty( $bodhi_svgs_options['restrict'] ) || current_user_can( 'administrator' ) ) {

		// allow SVG file upload
		$mimes['svg'] = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';

		return $mimes;

	} else {

		return $mimes;

	}

}
add_filter( 'upload_mimes', 'bodhi_svgs_upload_mimes' );


/**
 * Mime Check fix for WP 4.7.1 / 4.7.2
 *
 * Fixes uploads for these 2 version of WordPress.
 * Issue was fixed in 4.7.3 core.
 */
global $wp_version;
if ( $wp_version == '4.7.1' || $wp_version == '4.7.2' ) {
	add_filter( 'wp_check_filetype_and_ext', 'bodhi_svgs_disable_real_mime_check', 10, 4 );
}
function bodhi_svgs_disable_real_mime_check( $data, $file, $filename, $mimes ) {

		$wp_filetype = wp_check_filetype( $filename, $mimes );

		$ext = $wp_filetype['ext'];
		$type = $wp_filetype['type'];
		$proper_filename = $data['proper_filename'];

		return compact( 'ext', 'type', 'proper_filename' );

}
