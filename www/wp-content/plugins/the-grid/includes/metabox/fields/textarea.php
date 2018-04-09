<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Textarea_Field' ) ) {

	class TOMB_Textarea_Field extends TOMB_Fields {
		
		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 * @return string
		 */
		static function html( $meta, $field ) {

			// Check for std parameter
			if(!$meta && array_key_exists('std', $field)){
				$meta = $field['std'];
			}
			$output = '<textarea class="tomb-textarea large-text" name="'.$field['id'].'" id="'.$field['id'].'" cols="'.$field['cols'].'" rows="'.$field['rows'].'" placeholder="'.$field['placeholder'].'">'.$meta.'</textarea>';
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
				'cols' => 1,
				'rows' => 5,
			) );

			return $field;

		}

		/**
		 * Sanitize
		 *
		 * @param mixed $new
		 * @param mixed $old
		 * @param int   $post_id
		 * @param array $field
		 *
		 * @return string
		 */
		static function value( $new, $old, $post_id, $field ){
			
			return esc_textarea( $new );
			
		}

	}

}
