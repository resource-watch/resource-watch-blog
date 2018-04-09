<?php
/**
 * Enqueue scripts and styles
 * This file is to enqueue the scripts and styles both admin and front end
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Enqueue the admin CSS using screen check functions
 */
function bodhi_svgs_admin_css() {

	// check if user is on SVG Support settings page or media library page
	if ( bodhi_svgs_specific_pages_settings() || bodhi_svgs_specific_pages_media_library() ) {

		// enqueue the admin CSS
		wp_enqueue_style( 'bodhi-svgs-admin', BODHI_SVGS_PLUGIN_URL . 'css/svgs-admin.css' );

	}

	// check if user is on SVG Support settings page and not in "Advanced Mode"
	if ( bodhi_svgs_specific_pages_settings() && ! bodhi_svgs_advanced_mode() ) {

		// enqueue the simple mode admin CSS
		wp_enqueue_style( 'bodhi-svgs-admin-simple-mode', BODHI_SVGS_PLUGIN_URL . 'css/svgs-admin-simple-mode.css' );

	}

	// check if user is on an edit post page
	if ( bodhi_svgs_is_edit_page() ) {

		// enqueue the edit post CSS
		wp_enqueue_style( 'bodhi-svgs-admin-edit-post', BODHI_SVGS_PLUGIN_URL . 'css/svgs-admin-edit-post.css' );

	}

}
add_action( 'admin_enqueue_scripts', 'bodhi_svgs_admin_css' );

/**
 * Enqueue front end CSS for attachment pages
 */
function bodhi_svgs_frontend_css() {

	// check if user is on attachment page
	if ( is_attachment() ) {
		wp_enqueue_style( 'bodhi-svgs-attachment', BODHI_SVGS_PLUGIN_URL . 'css/svgs-attachment.css' );
	}

}
add_action( 'wp_enqueue_scripts', 'bodhi_svgs_frontend_css' );

/**
 * Enqueue and localize JS for IMG tag replacement
 */
function bodhi_svgs_inline() {

	if ( bodhi_svgs_advanced_mode() ) {

		// get the settings
		global $bodhi_svgs_options;

		// check if force inline svg is active
		if ( ! empty( $bodhi_svgs_options['force_inline_svg'] ) ) {

			// set variable as true to pass to js
			$force_inline_svg_active = 'true';

			// set the class for use in JS
			if ( ! empty( $bodhi_svgs_options['css_target'] ) ) {

				// use custom class if set
				$css_target_array = array(
					'Bodhi' => 'img.'. $bodhi_svgs_options['css_target'],
					'ForceInlineSVG' => $bodhi_svgs_options['css_target']
				);

			} else {

				// set default class
				$css_target_array = array(
					'Bodhi' => 'img.style-svg',
					'ForceInlineSVG' => 'style-svg'
				);

			}

		} else {

			// set variable as false to pass to JS
			$force_inline_svg_active = 'false';

			// if custom target is set, use that, otherwise use default
			if ( ! empty( $bodhi_svgs_options['css_target'] ) ) {
				$css_target = 'img.'. $bodhi_svgs_options['css_target'];
			} else {
				$css_target = 'img.style-svg';
			}

			// set the array to target for passing to JS
			$css_target_array = $css_target;

		}

		// use expanded or minified JS
		if ( ! empty( $bodhi_svgs_options['use_expanded_js'] ) ) {

			// set variables to blank so we use the full JS version
			$bodhi_svgs_js_folder = '';
			$bodhi_svgs_js_file = '';

		} else {

			// set variables to the minified version in the min folder
			$bodhi_svgs_js_folder = 'min/'; // min folder
			$bodhi_svgs_js_file = '-min'; // min file

		}

		// check where the JS should be placed, header or footer
		if ( ! empty( $bodhi_svgs_options['js_foot_choice'] ) ) {

			$bodhi_svgs_js_footer = true;

		} else {

			$bodhi_svgs_js_footer = false;

		}

		// create path for the correct js file
		$bodhi_svgs_js_path = 'js/' . $bodhi_svgs_js_folder .'svgs-inline' . $bodhi_svgs_js_file . '.js' ;

		wp_register_script( 'bodhi_svg_inline', BODHI_SVGS_PLUGIN_URL . $bodhi_svgs_js_path, array( 'jquery' ), '1.0.0', $bodhi_svgs_js_footer );
		wp_enqueue_script( 'bodhi_svg_inline' );

		wp_localize_script( 'bodhi_svg_inline', 'cssTarget', $css_target_array );
		wp_localize_script( 'bodhi_svg_inline', 'ForceInlineSVGActive', $force_inline_svg_active );

	}

}
add_action( 'wp_enqueue_scripts', 'bodhi_svgs_inline' );