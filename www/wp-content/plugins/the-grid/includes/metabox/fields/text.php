<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Text_Field' ) ) {

	class TOMB_Text_Field extends TOMB_Fields {

		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 * @return string
		 */
		static function html( $meta, $field ) {

			$size = null;
			$disabled = null;
			$placeholder = null;
			if((!isset($meta) && array_key_exists('std', $field)) || (empty($meta) && isset($field['force_std']))){
				$meta = $field['std'];
			}
			if ($field['placeholder']) {
				$placeholder = 'placeholder="'.$field['placeholder'].'"';
			}
			if ($field['disabled']) {
				$disabled = 'disabled="disabled"';
			}
			if ($field['size']) {
				$size = 'style="width:'.$field['size'].'px"';
			}
			return '<input type="text" class="tomb-text" '.$size.' name="'.$field['id'].'" id="'.$field['id'].'" value=\''. esc_attr( $meta ) .'\' '.$placeholder.' '.$disabled.'/>';

		}

		/**
		 * Normalize parameters for field
		 *
		 * @param array $field
		 * @return array
		 */
		static function normalize_field( $field ) {

			$field = wp_parse_args( $field, array(
				'size' => 'large',
				'disabled' => ''
			) );

			return $field;

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
			
			return esc_textarea( $new );
			
		}

	}

}
