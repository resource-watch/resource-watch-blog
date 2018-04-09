<?php
/*
 * Plugin Name: Sharing buttons shortcode for Jetpack
 * Plugin URI: http://wordpress.org/plugins/jetpack-sharing-butttons-shortcode/
 * Description: Extends the Jetpack plugin and allows you to add sharing buttons anywhere inside your posts thanks to the [jpshare] shortcode
 * Author: Jeremy Herve
 * Version: 1.2.2
 * Author URI: http://jeremy.hu
 * License: GPL2+
 * Text Domain: jetpack
 */

function tweakjp_sd_shortcode() {
	$output = '';
	if (
		class_exists( 'Jetpack' ) &&
		method_exists( 'Jetpack', 'get_active_modules' ) &&
		in_array( 'sharedaddy', Jetpack::get_active_modules() ) &&
		function_exists( 'sharing_display' )
	) {
		$output = sharing_display();
	}
	return $output;
}

function tweakjp_sd_enable() {
	add_shortcode( 'jpshare', 'tweakjp_sd_shortcode' );
}
add_action( 'plugins_loaded', 'tweakjp_sd_enable' );
