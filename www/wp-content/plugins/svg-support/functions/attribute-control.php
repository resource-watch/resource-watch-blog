<?php
/**
 * Attribute Control
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * If in Advanced Mode
 */
if ( bodhi_svgs_advanced_mode() ) {

	/**
	 * Strip HTML of all attributes and add custom class if the file is .svg
	 */
	function bodhi_svgs_auto_insert_class( $html, $alt='' ) {

		global $bodhi_svgs_options;

		if ( ! empty( $bodhi_svgs_options['css_target'] ) ) {

			// if custom class is set, use it
			$class = $bodhi_svgs_options['css_target'];

		} else {

			// if no custom class set, use default
			$class = 'style-svg';

		}

		// check if the src file has .svg extension
		if ( strpos( $html, '.svg' ) !== FALSE ) {

			// strip html for svg files
			$html = preg_replace( '/(width|height|title|alt|class)=".*"\s/', 'class="' . $class . '"', $html );;

		} else {

			// leave html intact for non-svg
			$html = $html;

		}

		return $html;

	}

	/**
	 * Fire auto insert class
	 */
	if ( ! empty( $bodhi_svgs_options['auto_insert_class'] ) ) {
		add_filter( 'image_send_to_editor', 'bodhi_svgs_auto_insert_class', 10 );
		// add_filter( 'post_thumbnail_html', 'bodhi_svgs_auto_insert_class', 10 );
	}


}
