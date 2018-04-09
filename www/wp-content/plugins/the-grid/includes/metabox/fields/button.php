<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Button_Field' ) ) {

	class TOMB_Field extends TOMB_Fields {

		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 * @return string
		 */
		static function html( $meta, $field ) {

			return sprintf(
				'<a href="%s" class="button" id="%s">%s</a>',
				$field['url'],
				$field['id'],
				$field['name']
			);

		}

		/**
		 * Normalize parameters for field
		 *
		 * @param array $field
		 * @return array
		 */
		static function normalize_field( $field ) {

			$field = wp_parse_args( $field, array(
				'url' => '',
			) );

			return $field;

		}


	}

}
