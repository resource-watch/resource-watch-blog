<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Title_Field' ) ) {

	class TOMB_Section_Start_Field extends TOMB_Fields {

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
			$color = null;
			$background = null;
			$style = null;
			$style2 = null;
			if (isset($field['color']) && !empty($field['color'])) {
				$color = 'color:'.$field['color'].';';
			}
			if (isset($field['background']) && !empty($field['background'])) {
				$background = 'background:'.$field['background'].';';
			}
			if (!empty($color) || !empty($background)) {
				$style = 'style="'.$color.$background.'"';
				$style2 = 'style="'.$color.'"';
			}
			
			$required_fields = null;
			if (isset($field['required'])) {
				foreach ($field['required'] as $requireds) {
					$required_fields .= $requireds[0].','.$requireds[1].','.$requireds[2].';';
				}
				$required_fields = rtrim($required_fields, ";");
				$required_fields = ' data-tomb-required="'.$required_fields.'"';
			}
			
			$output  = '<div class="tomb-section" id="'.$field['id'].'" '.$required_fields.'>';
				$output .= '<div class="tomb-section-left" '.$style.'>';
					$output .= '<h3 class="tomb-section-title" '.$style2.'>'.$field['name'].'</h3>';
					$output .= '<p class="tomb-section-desc">'.$field['desc'].'</p>';
				$output .= '</div>';
				$output .= '<div class="tomb-section-content">';
			return $output;
		}

	}

}
