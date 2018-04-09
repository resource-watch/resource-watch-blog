<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Upload_Field' ) ) {

	class TOMB_Upload_Field extends TOMB_Fields {

		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		static function admin_enqueue_scripts() {
			
			// Enqueue media manager.
	        wp_enqueue_media();

		}

		/**
		 * Get field HTML
		 *
		 * @param mixed $meta
		 * @param array $field
		 * @return string
		 */
		static function html( $meta, $field ) {
			$style = null;
			$show  = null;
			if(!empty($meta)) {
				$show = 'show';
			}
			
			$type = (isset($field['media_type']) && !empty($field['media_type'])) ? $field['media_type'] : 'image';			
			
			
			$output  = '<input type="text" class="tomb-image tomb-image-url" name="'.$field['id'].'" id="'.$field['id'].'" size="70" value="'.$meta.'"/>';
			$output .= '<a href="#" class="tomb-open-media tomb-button button-primary" data-inputid="#tomb-'.$field['id'].'" data-media-type="'.$type.'" data-frame-title="'.$field['frame_title'].'" data-frame-button="'.$field['frame_button'].'">'.$field['button_upload'].'</a>';
			$output .= '<a href="javascript:void(0);" class="tomb-image-remove tomb-button button-secondary '.$show.'">'.$field['button_remove'].'</a>';
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
				'button_label' => __('Upload', 'tomb-text-domain'),
				'frame_title'  => __('Select or upload an image', 'tomb-text-domain'),
				'frame_button' => __('Insert image', 'tomb-text-domain')
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
			
			return esc_url( $new );
			
		}

	}

}
