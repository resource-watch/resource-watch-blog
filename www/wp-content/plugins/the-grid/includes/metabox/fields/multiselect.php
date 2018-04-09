<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Multiselect_Field' ) ) {

	class TOMB_Multiselect_Field extends TOMB_Fields {

		/**
		 * Get field HTML
		 */
		static function html( $meta, $field ) {

			$meta_holder  = '';
			if (isset($field['meta_holder']) && !empty($field['meta_holder'])) {
				if (is_array($meta)) {
					$value = '';
					foreach($meta as  $key => $val) {
						$value .= $val.',';
					}
					$value = rtrim($value, ",");
				} else {
					$value = $meta;
				}
				$meta_holder = '<div class="tomb-meta-holder" id="'.$field['meta_holder'].'" data-meta="'.$value.'"></div>';
			}
			
			$placeholder = (isset($field['placeholder']) && !empty($field['placeholder'])) ? $field['placeholder'] : null;
			$clear = (isset($field['clear']) && !empty($field['clear'])) ? $field['clear'] : null;
			$width = (isset($field['width']) && !empty($field['width'])) ? $field['width'] : 180;
			$width = ' style="width:'.$field['width'].'px"';
			
			$output = '<div class="tomb-select-holder" data-noresult="'.__('No results found', 'tomb-text-domain').'" data-multiple="true"'.$width.'>';
			
				$output .= '<div class="tomb-value-holder"></div>';
				
				$output .= '<div class="tomb-select-fake">';
					$output .= '<span class="tomb-select-value"></span>';
					$output .= ($placeholder) ? '<span class="tomb-select-placeholder">'.$placeholder.'</span>' : null;
					$output .= '<span class="tomb-select-arrow">+</span>';
				$output .= '</div>';
			
				$output .= '<select class="tomb-multiselect" name="'.$field['id'].'[]" id="'.$field['id'].'" multiple="multiple">';
				if (isset($field['options']) && !empty($field['options'])) {
					foreach ( $field['options'] as $value => $label ) {
						$disabled = strpos($value, 'disabled') ? 'disabled="disabled"' : false;
						$output .= '<option value="'.$value.'" '.selected( in_array( $value, (array) $meta ), true, false ).' '. $disabled .'>'.$label.'</option>';
					}
				}
				$output .= '</select>';

				$output .= $meta_holder;
			
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

	}

}
