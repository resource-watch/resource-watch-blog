<?php
/*
Plugin Name: 	SVG Support
Plugin URI:		http://wordpress.org/plugins/svg-support/
Description: 	Allow SVG file uploads using the WordPress Media Library uploader plus the ability to inline SVG files for direct styling/animation of SVG elements using CSS/JS.
Version: 		2.3.11
Author: 		Benbodhi
Author URI: 	http://benbodhi.com
Text Domain: 	svg-support
Domain Path:	/languages
License: 		GPLv2 or later
License URI:	http://www.gnu.org/licenses/gpl-2.0.html

	Copyright 2013 and beyond | Benbodhi (email : wp@benbodhi.com)

*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Global variables
 */
$svgs_plugin_version = '2.3.11';									// for use on admin pages
$plugin_file = plugin_basename(__FILE__);							// plugin file for reference
define( 'BODHI_SVGS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );	// define the absolute plugin path for includes
define( 'BODHI_SVGS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );		// define the plugin url for use in enqueue
$bodhi_svgs_options = get_option('bodhi_svgs_settings');			// retrieve our plugin settings from the options table

/**
 * Includes - keeping it modular
 */
include( BODHI_SVGS_PLUGIN_PATH . 'admin/admin-init.php' );         		// initialize admin menu & settings page
include( BODHI_SVGS_PLUGIN_PATH . 'admin/plugin-action-meta-links.php' );	// add links to the plugin on the plugins page
include( BODHI_SVGS_PLUGIN_PATH . 'admin/admin-notice.php' );				// dismissable admin notice to warn users to update settings
include( BODHI_SVGS_PLUGIN_PATH . 'functions/mime-types.php' );				// setup mime types support for SVG (with fix for WP 4.7.1 - 4.7.2)
include( BODHI_SVGS_PLUGIN_PATH . 'functions/thumbnail-display.php' );		// make SVG thumbnails display correctly in media library
include( BODHI_SVGS_PLUGIN_PATH . 'functions/attachment-modal.php' );		// make SVG thumbnails display correctly in attachment modals
include( BODHI_SVGS_PLUGIN_PATH . 'functions/enqueue.php' );				// enqueue js & css for inline replacement & admin
include( BODHI_SVGS_PLUGIN_PATH . 'functions/localization.php' );			// setup localization & languages
include( BODHI_SVGS_PLUGIN_PATH . 'functions/attribute-control.php' );		// auto set SVG class & remove dimensions during insertion
include( BODHI_SVGS_PLUGIN_PATH . 'functions/featured-image.php' );			// allow inline SVG for featured images

/**
 * Version based conditional / Check for stored plugin version
 *
 * Versions prior to 2.3 did not store the version number,
 * If no version number is stored, store current plugin version number.
 * If there is a version number stored, update it with the new version number.
 */
// get the stored plugin version
$svgs_plugin_version_stored = get_option( 'bodhi_svgs_plugin_version' );
// only run this if there is no stored version number (have never stored the number in previous versions)
if ( empty( $svgs_plugin_version_stored ) ) {

	// add plugin version number to options table
	update_option( 'bodhi_svgs_plugin_version', $svgs_plugin_version );

} else {

	// update plugin version number in options table
	update_option( 'bodhi_svgs_plugin_version', $svgs_plugin_version );

}
