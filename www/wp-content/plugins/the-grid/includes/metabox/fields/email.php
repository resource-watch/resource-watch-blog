<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Email_Field' ) ) {

	class TOMB_Email_Field extends TOMB_Fields {

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
			
			return sprintf(
				'<input type="text" class="tomb-email mini" name="%s" id="%s" value="%s" placeholder="%s">',
				$field['id'],
				$field['id'],
				$meta,
				$field['placeholder']
			);

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
			return sanitize_email( $new );
		}

	}

}
