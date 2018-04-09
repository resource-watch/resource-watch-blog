<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Title_Field' ) ) {

	class TOMB_Title_Field extends TOMB_Fields {

		/**
		 * Disables the output of the regular fields wrapper
		 */
		static function display_wrapper() {
			return false;
		}

		/**
		 * Enables the output of markup for fields that do not require a wrapper.
		 */
		static function display_empty_wrapper() {
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

			$output = ($field['name']) ? '<h3 class="tomb-title">'.$field['name'].'</h3>' : null;
			$output .= '<p class="tomb-title description">'.$field['desc'].'</p>';

			return $output;

		}

	}

}
