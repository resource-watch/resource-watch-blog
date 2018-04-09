<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Gallery_Field' ) ) {

	class TOMB_Gallery_Field extends TOMB_Fields {

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

			$output = null;
			$ids = $meta;
			$ids = (!empty($ids)) ? explode(',', $ids) : $ids;

			$output = '<div class="tomb-gallery-container">';
			
				$output .= '<div class="tomb-thumbs-container">';
					$output .= '<ul id="tomb-gallery-thumbs-'.$field['id'].'" class="tomb-gallery-holder">';
						if($ids) {
							foreach ($ids as $id) {
								$url  = wp_get_attachment_thumb_url($id );
								$img  = 'style="background-image: url('.$url.')"';
								$output .= '<li class="tomb-gallery-item" data-id="'.$id.'">';
									$output .= '<div class="tomb-gallery-item-remove">x</div>';
									$output .= '<div class="tomb-gallery-item-image" '.$img.'></div>';
								$output .= '</li>';
							}
						}
					$output .= '</ul>';
				$output .= '</div>';

				
				$output .= '<div class="tomb-actions">';
					$output .='<div class="action-left">';
						// media gallery manager trigger button
						$output .= '<input type="hidden" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'">';
						$output .= '<a href="#" id="tomb-gallery-'.$field['id'].'" class="tomb-open-gallery tomb-button button button-primary tomb-gallery" data-list="#tomb-gallery-thumbs-'.$field['id'].'"  data-title="'.$field['frame_title'].'" data-button="'.$field['frame_button'].'">'.$field['button_upload'].'</a> ';
					$output .= '</div>';
					$output .='<div class="action-right">';
						// media gallery manager delete gallery button
						$output .= '<a href="#" id="tomb-gallery-delete-'.$field['id'].'" class="tomb-delete-gallery tomb-button button button-secondary tomb-gallery" data-list="#tomb-gallery-thumbs-'.$field['id'].'" data-del="'.$field['delete_message'].'">'.$field['button_remove'].'</a>';
					$output .='</div>';
					$output .='<div class="clear"></div>';
				$output .='</div>';

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
				'button_create'  => __('Add/Edit Gallery', 'tomb-text-domain'),
				'button_delete'  => __('Delete Gallery', 'tomb-text-domain'),
				'frame_title'    => __('Select or upload images to create a gallery', 'tomb-text-domain'),
				'frame_button'   => __('Insert gallery', 'tomb-text-domain'),
				'delete_message' => __('Are you sure you want to delete the gallery?', 'tomb-text-domain')				
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
			
			return $new;
			
		}

	}

}
