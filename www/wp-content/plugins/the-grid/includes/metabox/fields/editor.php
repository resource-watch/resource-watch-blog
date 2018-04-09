<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Editor_Field' ) ) {

	class TOMB_Editor_Field extends TOMB_Fields {

		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 * @return string
		 *
		 */
		static function html( $meta, $field ) {

			ob_start();

			// Prepare Editor ID
			$editor_id = 'tomb-'.$field['id'];

			// Get Settings
			// Accepts all args listed here http://codex.wordpress.org/Function_Reference/wp_editor
			$settings = $field['args'];

			wp_editor( $meta, $editor_id, $settings );

			$output = ob_get_clean();

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
				'args' => array(),
			) );

			return $field;

		}

		/**
		 * Sanitize editor
		 *
		 * @param mixed $new
		 * @param mixed $old
		 * @param int   $post_id
		 * @param array $field
		 *
		 * @return string
		 */
		static function value( $new, $old, $post_id, $field ){

			$prefix = 'tomb-';
			$the_field_id = $prefix.$field['id'];

			$allowed_html = apply_filters('wppf_editor_field_allowed_html',wp_kses_allowed_html( 'post' ));

			return wp_kses($_POST[$the_field_id], $allowed_html);
		}

	}

}
