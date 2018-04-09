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

class The_Grid_Element {

	public function ui() {
		
		return array(
			'title'       => __( 'The Grid', 'tg-text-domain' ),
			'autofocus' => array(
				'heading' => 'h4.the-grid-heading',
				'content' => '.the-grid-element'
			),
			'icon_id' => 'the-grid',
			'icon_group' => 'the-grid'
		);
		
	}
	
	public function update_build_shortcode_atts( $atts ) {
		
		return $atts;
		
	}

}