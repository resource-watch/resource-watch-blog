<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'TOMB_Slider_Field' ) ) {

	class TOMB_Slider_Field extends TOMB_Fields {
		
		/**
		 * Enqueue scripts and styles
		 *
		 * @return void
		 */
		static function admin_enqueue_scripts() {	
			// This function loads in the required scripts.
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-slider');
			wp_enqueue_style('jquery-ui-style');
		}
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
			
			if(!isset($meta) && array_key_exists('std', $field)){
				$meta = $field['std'];
			}
			if(!empty($field['label'])){
				$output .= '<label class="tomb-slider-label">'.$field['label'].'</label>';
			}
			$output .= '<span class="tomb-slider-range" data-value="'.$meta.'" data-min="'.$field['min'].'" data-max="'.$field['max'].'" data-step="'.$field['step'].'" data-sign="'.$field['sign'].'"></span>';
			$output .= '<div class="tomb-slider-controls">';
			$output .= '<div class="tomb-slider-plus">+</div>';
			$output .= '<div class="tomb-slider-less">-</div>';
			$output .= '</div>';
			$output .= '<input type="text" readonly="readonly" class="tomb-slider" value="'.$meta.$field['sign'].'"/>';
			$output .= '<input type="hidden" class="tomb-slider-input" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'"/>';
			

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
				'step' => 1,
				'min'  => 0,
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
			
			return esc_attr( $new );
			
		}

	}

}
