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

// get user session info
function check_user() {
    global $user_info;
    // $url_data = file_get_contents('/auth/user');
    // if ($url_data) {
    //   $user_info = json_decode($url_data);
    // }
}

add_action( 'wp', 'check_user' );


// init sessions
function init_sessions() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'init_sessions');


?>
