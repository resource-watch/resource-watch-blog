<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Info_Box_Field' ) ) {

	class TOMB_Info_Box_Field extends TOMB_Fields {

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
			$output  = '<div class="tomb-info-box">';
			$output .= '<div class="dashicons dashicons-lightbulb" style="float:left;margin-right:10px;"></div>';
			$output .= '<div class="tomb-info-box-holder">';
			if (isset($field['title']) && !empty($field['title'])) {
				$output .= '<h3 class="tomb-info-box-title">'.$field['title'].'</h3>';
			}
			$output .= '<p class="tomb-info-box-content">'.$field['desc'].'</p>';
			$output .= '<div style="clear:both"></div>';
			$output .= '</div></div>';
			return $output;
		}

	}

}
