<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Select_Field' ) ) {

	class TOMB_Select_Field extends TOMB_Fields {		

		/**
		 * Get field HTML
		 */
		static function html($meta, $field) {

			// Check for std parameter
			if(!$meta && array_key_exists('std', $field)){
				$meta = $field['std'];
			}
			
			$placeholder = (isset($field['placeholder']) && !empty($field['placeholder'])) ? $field['placeholder'] : null;
			$clear = (isset($field['clear']) && !empty($field['clear'])) ? $field['clear'] : null;
			$width = (isset($field['width']) && !empty($field['width'])) ? $field['width'] : 180;
			$width = ' style="width:'.$field['width'].'px"';
			
			$output = '<div class="tomb-select-holder" data-noresult="'.__('No results found', 'tomb-text-domain').'" data-clear="'.$clear.'"'.$width.'>';
			
				$output .= '<div class="tomb-select-fake">';
					$output .= '<span class="tomb-select-value"></span>';
					$output .= ($placeholder) ? '<span class="tomb-select-placeholder">'.$placeholder.'</span>' : null;
					$output .= ($clear) ? '<span class="tomb-select-clear">×</span>' : null;
					$output .= '<span class="tomb-select-arrow"><i></i></span>';
				$output .= '</div>';	
				
				$output .= '<select class="tomb-select" name="'.$field['id'].'" data-clear="'.$clear.'">';
				if (isset($field['options']) && !empty($field['options'])) {
					$output .= ($clear) ? '<option></option>' : null;
					foreach ( $field['options'] as $value => $label ) {
						$disabled = strpos($value, 'disabled') ? 'disabled="disabled"' : false;
						$output .= '<option value="'.$value.'" '.selected( $value, $meta, false ).' '. $disabled .'>'.$label.'</option>';
					}
				}
				$output .= '</select>';
			
			$output .= '</div>';

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
				'options' => array(),
			) );

			return $field;

		}

		/**
		 * Sanitize select
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
