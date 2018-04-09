<?php

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

if (!class_exists('TOMB_Checkbox_Field')) {

	class TOMB_Checkbox_Field extends TOMB_Fields {

		/**
		* Get field HTML
		* @param mixed $meta
		* @param array $field
		* @return string
		*/
		static function html($meta, $field) {

			// check if d value set for checkbox
			if (!isset($meta) && array_key_exists('std', $field)) {
				$meta = $field['std'];
			}
			
			$output  = '<div class="tomb-switch">';
				$output .= '<input type="checkbox" class="tomb-checkbox" name="'.$field['id'].'" id="'.$field['id'].'" '.checked(!empty($meta), 1, false).'>';
				$output .= $field['checkbox_title'];
				$output .= '<label for="'.$field['id'].'"></label>';
			$output .= '</div>';

			return $output;

		}

		/**
		* Normalize parameters for field
		* @param array $field
		* @return array
		*/
		static function normalize_field( $field ) {
			$field = wp_parse_args( $field, array(
				'checkbox_title' => ''
			) );
			return $field;
		}
		
		/**
		 * Check the value of the checkbox
		 * @param mixed $new
		 * @param mixed $old
		 * @param int   $post_id
		 * @param array $field
		 * @return int
		 */
		static function value($new, $old, $post_id, $field) {
			return empty($new) ? '' : 1;
		}

	}

}