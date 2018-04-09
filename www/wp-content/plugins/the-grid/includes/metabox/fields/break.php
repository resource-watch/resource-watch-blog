<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Break_Field' ) ) {

	class TOMB_Break_Field extends TOMB_Fields {
		/**
		 * Disables the output of the regular fields wrapper
		 */
		static function display_wrapper() {
			return false;
		}
		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 * @return string
		 */
		static function html( $meta, $field ) {
			$output = null;
			$output = '<div class="tomb-clearfix"></div>';
			return $output;

		}

	}

}
