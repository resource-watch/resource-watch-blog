<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Hidden_Field' ) ) {

	class TOMB_Hidden_Field extends TOMB_Fields {

		static function display_wrapper() {
			return true;
		}

		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 * @return string
		 */
		static function html( $meta, $field ) {
			$output  = null;
			$output .= $field['options'];
			$output .= '<input type="hidden" class="tomb-hidden" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'">';
			return $output;
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
			return sanitize_text_field( $new );
		}

	}

}
