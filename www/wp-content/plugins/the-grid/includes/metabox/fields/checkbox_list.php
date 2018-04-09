<?php

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

if (!class_exists('TOMB_Checkbox_List_Field')) {

	class TOMB_Checkbox_List_Field extends TOMB_Fields {

		/**
		* Get field HTML
		* @param mixed $meta
		* @param array $field
		* @return string
		*/
		static function html($meta, $field) {

			if(!isset($meta) && array_key_exists('std', $field)){
				$meta = $field['std'];	
			}
			
			// be sure $meta is an array
			$meta = (array) $meta;
			
			$output = null;
			foreach ($field['options'] as $value => $label) {
				$output .= '<input type="checkbox" class="tomb-checkbox-list" name="'.$field['id'].'[]" id="'.$field['id'].'" value="'.$value.'" '.checked(in_array($value, $meta), 1, false).'>';
				$output .= '<label >'.$label.'</label>';
				$output .= '<br>';
			}
			return $output;

		}

		/**
		* Normalize parameters for field
		* @param array $field
		* @return array
		*/
		static function normalize_field($field) {

			$field = wp_parse_args($field, array(
				'options' => array()
			));

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
		/*static function value($new, $old, $post_id, $field) {
			return empty($new) ? array() : $new;
		}*/

	}

}