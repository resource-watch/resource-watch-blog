<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Image_id_Field' ) ) {

	class TOMB_Image_id_Field extends TOMB_Fields {

		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		static function admin_enqueue_scripts() {
			
			// This function loads in the required media files for the media manager.
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
			if(!$meta && array_key_exists('std', $field)){
				$meta = $field['std'];
			}
			
			$img  = null;
			$show = null;
			if(!empty($meta)) {
				$url  = wp_get_attachment_thumb_url($meta );
				$img  = 'style="background-image: url('.$url.')"';
				$show = 'show';
			}
			// Image url container
			$output  = '<div class="tomb-img-field screenshot '.$show.'" '.$img.'></div>';	
			$output .= '<input type="hidden" class="tomb-image tomb-image-url '.$show.'" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'"/>';
			$output .= '<div class="tomb-image-clearfix"></div>';
			$output .= '<a href="#" class="tomb-open-media tomb-button button-primary tomb-image-id" data-frame-title="'.$field['frame_title'].'" data-frame-button="'.$field['frame_button'].'">'.$field['button_upload'].'</a>';
			$output .= '<a href="javascript:void(0);" class="tomb-image-remove tomb-button button-secondary  '.$show.'">'.$field['button_remove'].'</a>';
			$output .= '<div class="tomb-clearfix"></div>';
			

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
				'frame_title' => __('Select or upload an image', 'tomb-text-domain'),
				'frame_button' => __('Insert image', 'tomb-text-domain')
			) );

			return $field;

		}

		/**
		 * Sanitize attr
		 *
		 * @param mixed $new
		 * @param mixed $old
		 * @param int   $post_id
		 * @param array $field
		 *
		 * @return string
		 */
		static function value( $new, $old, $post_id, $field ){

			return esc_attr( $new );
			
		}

	}

}
