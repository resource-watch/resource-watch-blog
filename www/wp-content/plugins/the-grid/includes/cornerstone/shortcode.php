<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

global $wp_query;

if ( ( isset($_SERVER['QUERY_STRING']) && strpos($_SERVER['QUERY_STRING'], 'action=cs_render_element') !== false ) || ( isset( $wp_query->query_vars[ 'cornerstone-endpoint' ] ) && defined( 'DOING_AJAX' ) ) ) {
	echo '<div class="tg-error-msg">The Grid - Preview not available in Cornerstone</div>';
} else {
	echo The_Grid( $name );
}


/*if ( ( isset($_SERVER['QUERY_STRING']) && strpos($_SERVER['QUERY_STRING'], 'action=cs_render_element') !== false ) || ( isset( $wp_query->query_vars[ 'cornerstone-endpoint' ] ) && defined( 'DOING_AJAX' ) ) ) {

	$grid_info = get_page_by_title( html_entity_decode( $name ), 'OBJECT', 'the_grid' );
	$ID = 'grid-' . $grid_info->ID;

	echo '<script language="javascript">';
	echo '(function($) {
		"use strict";
		$.TG_media_init();
		$(document).ready(function() {
			$("#'.esc_attr($ID).' .preloader-styles,#'.esc_attr($ID).' .the_grid_styles").removeAttr("scoped");
			$("#'.esc_attr($ID).' .tg-grid-holder").The_Grid();
		});
	})(jQuery);';
	echo '</script>';

}*/