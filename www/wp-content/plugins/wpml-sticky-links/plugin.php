<?php
/*
Plugin Name: WPML Sticky Links
Plugin URI: https://wpml.org/
Description: Prevents internal links from ever breaking | <a href="https://wpml.org">Documentation</a> | <a href="https://wpml.org/version/sticky-links-1-4-3/">WPML Sticky Links 1.4.3 release notes</a>
Author: OnTheGoSystems
Author URI: http://www.onthegosystems.com/
Version: 1.4.3
Plugin Slug: wpml-sticky-links
*/

if(defined('WPML_STICKY_LINKS_VERSION')) return;

define('WPML_STICKY_LINKS_VERSION', '1.4.3');
define('WPML_STICKY_LINKS_PATH', dirname(__FILE__));

require WPML_STICKY_LINKS_PATH . '/inc/constants.php';

$autoloader_dir = WPML_STICKY_LINKS_PATH . '/vendor';
if ( version_compare( PHP_VERSION, '5.3.0' ) >= 0 ) {
	$autoloader = $autoloader_dir . '/autoload.php';
} else {
	$autoloader = $autoloader_dir . '/autoload_52.php';
}
require_once $autoloader;


global $WPML_Sticky_Links;

if ( ! isset( $WPML_Sticky_Links ) ) {
	$WPML_Sticky_Links = new WPML_Sticky_Links();
	$WPML_Sticky_Links->init_hooks();
}

if ( ! function_exists( 'icl_js_escape' ) ) {
	function icl_js_escape( $str ) {
		$str = esc_js( $str );
		$str = htmlspecialchars_decode( $str );

		return $str;
	}
}
