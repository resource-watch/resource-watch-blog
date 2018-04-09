<?php
/**
 * Author:      Themeone
 * Author URI:  https://theme-one.com
 */
 
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('TOMB_Fields')) {

	class TOMB_Fields {
		
		/**
		* Enables markup for all fields
		* @since 1.0.0
		*/
		static function display_wrapper() {
			
			return true;
			
		}
		
		/**
		* Enables markup for fields who don't require markup
		* @since 1.0.0
		*/
		static function display_empty_wrapper() {
			
			return false;
			
		}
		
		/**
		* Enqueue scripts and styles
		* @since 1.0.0
		*/
		static function admin_enqueue_scripts() {
		}
		
		/**
		* Add actions
		* @since 1.0.0
		*/
		static function add_actions() {
		}

		/**
		* Show field HTML
		* @since 1.0.0
		*/
		static function show($field, $saved) {
			
			global $post;
			
			$field_class = TOMB_Metabox::get_class_name($field);
			$meta = call_user_func(array($field_class, 'meta'), $post->ID, $saved, $field);
			// Call separated methods for displaying each type of field
			$field_html = call_user_func(array($field_class, 'html'), $meta, $field);
			// return field markup
			echo $field_html;
			
		}
		
		/**
		* Show field HTML For taxonomies
		* @since 1.0.0
		*/
		static function show_taxonomy($field, $meta) {
			
			$field_class = TOMB_Metabox::get_class_name($field );
			// Call separated methods for displaying each type of field
			$field_html = call_user_func(array($field_class, 'html'), $meta, $field);
			// return field markup
			echo $field_html;
			
		}
		
		/**
		* Get field HTML
		* @since 1.0.0
		*/
		static function html($meta, $field) {
			
			return '';
			
		}
		
		/**
		* Get meta value
		* @since 1.0.0
		*/
		static function meta($post_id, $saved, $field) {
			
			// retrieve meta value
			$meta = get_post_meta($post_id, $field['id'], true);
			// Use $field['std'] only when the meta box hasn't been saved (i.e. the first time we run)
			$meta = (!$saved && '' === $meta/* || array() === $meta*/) ? $field['std'] : $meta;
			//$meta = is_array($meta) ? $meta  :  $meta;
			
			return $meta;
			
		}
		 
		/**
		* Set value of meta before saving into database
		* @since 1.0.0
		*/
		static function value($new, $old, $post_id, $field) {
			
			return $new;
			
		}
 
		/**
		* Save meta value
		* @since 1.0.0
		*/
		static function save($new, $old, $object_id, $field, $method = 'post') {
			
			$name = $field['id'];
			if ('' === $new || array() === $new) {
				delete_post_meta($object_id, $name);
				return;
			}
			
			update_post_meta($object_id, $name, $new);
			
		}
		 
		/**
		* Normalize parameters for field
		* @since 1.0.0
		*/
		static function normalize_field($field){
			
			return $field;
			
		}

		/**
		* Show custom markup before the markup of the field.
		* @since 1.0.0
		*/
		static function before_field($field) {
			
			$output          = null;
			$required_fields = null;
			
			// Retrieve current class name
			$field_class = TOMB_Metabox::get_class_name($field);
			
			// add requiere attribute if needed
			if (isset($field['required'])) {
				foreach ($field['required'] as $requireds) {
					$required_fields .= $requireds[0].','.$requireds[1].','.$requireds[2].';';
				}
				$required_fields = rtrim($required_fields, ";");
				$required_fields = ' data-tomb-required="'.$required_fields.'"';
			}
			
			if(call_user_func(array($field_class, 'display_wrapper'))) {
				$output .= '<div class="'.$field['id'].' '.$field['classes'].' tomb-type-'.$field['type'].' tomb-row"'.$required_fields.'>';
				if ($field['name']) {
					$output .= '<label class="tomb-label">'.$field['name'].'</label>';
				}
				if ($field['desc']) {
					$output .= '<p class="tomb-desc">'.$field['desc'].'</p>';
				}
			}
			
			return $output;
			
		}
		
		/**
		* Show custom markup after the markup of the field.
		* @since 1.0.0
		*/
		static function after_field($field) {
			
			$output = null;
			$field_class = TOMB_Metabox::get_class_name($field);
			if(call_user_func( array( $field_class, 'display_wrapper' ) )) {
				if ($field['sub_desc']) {
					$output .= '<p class="tomb-sub-desc">'.$field['sub_desc'].'</p>';
				}
				$output .= '</div>';
			}
			
			return $output;
			
		}
		
	}
	
}