<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Number_Field' ) ) {

	class TOMB_Number_Field extends TOMB_Fields {

		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 * @return string
		 */
		static function html( $meta, $field ) {
			
			$output = null;
			// Check for std parameter
			if(!$meta && array_key_exists('std', $field)){
				$meta = $field['std'];
			}
			// get zero value instead of empty value
			$meta = ($meta == '00') ? 0 : $meta;
			if(!empty($field['label'])){
				$output .= '<label class="tomb-number-label">'.$field['label'].'</label>';
			}
			if(empty($field['step'])){
				$field['step'] = 1;
			}
			$output .= '<input type="number" class="tomb-text number mini" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" step="'.$field['step'].'" min="'.$field['min'].'" max="'.$field['max'].'">';
			if(!empty($field['sign'])){
				$output .= '<label class="tomb-number-label">'.$field['sign'].'</label>';
			}
			return $output;
		}

		/**
		 * Normalize parameters for field
		 *
		 * @param array $field
		 * @return array
		 */
		static function normalize_field( $field ) {

			$field = wp_parse_args( $field, array(
				'step' => 1,
				'min'  => 0,
			) );

			return $field;

		}

		/**
		 * Sanitize url
		 *
		 * @param mixed $new
		 * @param mixed $old
		 * @param int   $post_id
		 * @param array $field
		 *
		 * @return string
		 */
		static function value( $new, $old, $post_id, $field ){
			return intval( $new );
		}

	}

}
