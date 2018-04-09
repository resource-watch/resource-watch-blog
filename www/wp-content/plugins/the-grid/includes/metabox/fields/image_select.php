<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Image_Select_Field' ) ) {

	class TOMB_Image_Select_Field extends TOMB_Fields {

		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 * @return string
		 *
		 * @todo add support for std
		 */
		static function html( $meta, $field ) {
			$output = null;
			
			// Check for std parameter
			if(!$meta && array_key_exists('std', $field)){
				$meta = $field['std'];
			}
			foreach ( $field['options'] as $value ) {
				$checked = ($value['value'] == $meta) ? true : false;
				$output .= '<span data-val="'.$meta.':'.$value['value'].'" class="tomb-image-holder" data-checked="'.$checked.'">';
				$output .= '<img src="'.$value['image'].'" data-checked="'.$checked.'" alt=""/>';
				$output .= '<label>'.$value['label'].'</label>';
				$output .= '<input type="radio" class="tomb-image-select" name="'.$field['id'].'" value="'.$value['value'].'" '.checked($value['value'], $meta, false ).'>';
				$output .= '</span>';
			}
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
				'options' => array()
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
			return sanitize_text_field( $new );
		}
	}

}
