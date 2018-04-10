<?php
/**
 * Resource Watch theme functions & definitions
**/


/**
 * Parent theme style.css
**/

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

// init custom js file
function custom_js() {
    wp_enqueue_script(
        'custom-script',
        get_stylesheet_directory_uri() . '/js/custom.js',
        array( 'jquery' )
    );
}

add_action( 'wp_enqueue_scripts', 'custom_js' );

add_filter('avf_logo_link', 'avf_redirect_logo_link');

function avf_redirect_logo_link($link) {
	$link = 'https://resourcewatch.org';
	return $link;
}

?>
