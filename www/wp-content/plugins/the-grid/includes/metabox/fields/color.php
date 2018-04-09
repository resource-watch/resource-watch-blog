<?php

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

if ( ! class_exists( 'TOMB_Color_Field' ) ) {

	class TOMB_Color_Field extends TOMB_Fields {

		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		static function admin_enqueue_scripts() {
			
			// This function loads in the required scripts.
	        wp_enqueue_script('wp-color-picker');
    		wp_enqueue_style('wp-color-picker');

		}

		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 * @return string
		 *
		 * @todo add support for std
		 */
		static function html( $meta, $field ) {

			// Set meta as default value if meta is empty.
			if(empty($meta)) {
				$meta = $field['std'];
			}
			if(!isset($field['rgba'])) {
				$field['rgba'] = false;
			}

			$output = '<input class="tomb-colorpicker" name="'.$field['id'].'" data-alpha="'.$field['rgba'].'" type="text" id="'.$field['id'].'" value="'.$meta.'">';

			return $output;

		}

	}

}
