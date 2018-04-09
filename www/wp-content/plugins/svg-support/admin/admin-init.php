<?php
/**
 * Admin init
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Add menu item to wp-admin
 */
function bodhi_svgs_admin_menu() {

	$bodhi_svgs_options_page = add_options_page(
		__('SVG Support Settings and Usage', 'svg-support'),
		__('SVG Support', 'svg-support'),
		'manage_options',
		'svg-support',
		'bodhi_svg_support_settings_page'
		);

}
add_action( 'admin_menu', 'bodhi_svgs_admin_menu' );

/**
 * Create settings page
 */
function bodhi_svg_support_settings_page() {

	if ( ! current_user_can( 'manage_options' ) ) {

		wp_die( __('You can\'t play with this.', 'svg-support') );

	}

	// Swapped the global with this line to work with WordPress Bedrock on LEMP stack | https://wordpress.org/support/topic/settings-not-saving-24/
	// global $bodhi_svgs_options;
	$bodhi_svgs_options = get_option( 'bodhi_svgs_settings' );

	require( BODHI_SVGS_PLUGIN_PATH . 'admin/svgs-settings-page.php' );

}

/**
 * Register settings in the database
 */
function bodhi_svgs_register_settings() {

	register_setting( 'bodhi_svgs_settings_group', 'bodhi_svgs_settings' );

}
add_action( 'admin_init', 'bodhi_svgs_register_settings' );

/**
 * Advanced Mode Check
 *
 * Creates a usable function for conditionals around the plugin
 */
function bodhi_svgs_advanced_mode() {

	global $bodhi_svgs_options;

	if ( ! empty( $bodhi_svgs_options['advanced_mode'] ) ) {

		return true;

	} else {

		return false;

	}

}
add_action( 'admin_init', 'bodhi_svgs_advanced_mode' );

/**
 * Screen check function
 * Checks if current page is SVG Support settings page
 */
function bodhi_svgs_specific_pages_settings() {

	// check current page
	$screen = get_current_screen();

	// check if we're on SVG Support settings page
	if ( is_object($screen) && $screen->id == 'settings_page_svg-support' ) {

		return true;

	} else {

		return false;

	}

}

/**
 * Screen check function
 * Checks if the current page is the Media Library page
 */
function bodhi_svgs_specific_pages_media_library() {

	// check current page
	$screen = get_current_screen();

	// check if we're on Media Library page
	if ( is_object($screen) && $screen->id == 'upload' ) {

		return true;

	} else {

		return false;

	}
}

/**
 * Screen check function
 * Check if the current page is a post edit page
 */
function bodhi_svgs_is_edit_page( $new_edit = null ) {

    global $pagenow;

    if ( ! is_admin() ) return false;

    if ( $new_edit == 'edit' ) {

        return in_array( $pagenow, array( 'post.php',  ) );

    } elseif ( $new_edit == "new" ) { //check for new post page

        return in_array( $pagenow, array( 'post-new.php' ) );

    } else { //check for either new or edit

        return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );

    }

}

/**
 * Add rating text to footer on settings page
 */
function bodhi_svgs_admin_footer_text( $default ) {

	if ( bodhi_svgs_specific_pages_settings() || bodhi_svgs_specific_pages_media_library() ) {

		printf( __( 'If you like <strong>SVG Support</strong> please leave a %s&#9733;&#9733;&#9733;&#9733;&#9733;%s rating. A huge thanks in advance!', 'svg-support' ), '<a href="https://wordpress.org/support/view/plugin-reviews/svg-support?filter=5#postform" target="_blank" class="svgs-rating-link">', '</a>' );

	} else {

		return $default;

	}

}
add_filter( 'admin_footer_text', 'bodhi_svgs_admin_footer_text' );
