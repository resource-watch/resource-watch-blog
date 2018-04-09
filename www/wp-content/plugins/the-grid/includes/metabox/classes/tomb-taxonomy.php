<?php
/**
 * Author:      Themeone
 * Author URI:  https://theme-one.com
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('TOMB_Taxonomy')) {
	
	class TOMB_Taxonomy {
		
		/**
		* @vars string
		* @since 1.0.0
		*/
		protected $_meta_box;
		var $errors;
		public $fields;
		public $saved = false;
		protected $_form_type;

		/**
	 	* Constructor
		* @since 1.0.0
	 	*/
		public function __construct($meta_box) {
			
			// Prepare the metabox values with the class variables.
		    $this->_meta_box = $meta_box;
		    $this->fields = $this->_meta_box['fields'];
			// Set default values for fields
			$this->_meta_box['fields'] = TOMB_Metabox::normalize_fields( $this->_meta_box['fields'] );
			$this->init_hooks();

		}
		
		/**
		* Hook into actions and filters
		* @since 1.0.0
		*/
		public function init_hooks() {
			
			// Run metabox output & save methods
			$page = $this->_meta_box['taxonomy'];
			//add fields to edit form
      		add_action( $page.'_edit_form_fields',array( $this, 'show_edit_form' ));
      		//add fields to add new form
      		add_action( $page.'_add_form_fields',array( $this, 'show_new_form' ));
      		// this saves the edit fields
      		add_action( 'edited_'.$page, array( $this, 'save' ), 10, 2);
		    // this saves the add fields
		    add_action( 'created_'.$page,array( $this, 'save' ), 10, 2 );
			//delete term meta on term deletion
    		add_action('delete_term', array( $this, 'delete_term_metadata'), 10, 2 );
			// Enqueue styles and scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			
		}

		/**
		* Get the metabox id
		* @since 1.0.0
		*/
		public function get_id() {
			
			return $this->_meta_box['id'];
			
		}

		/**
		* Load metabox on term add page
		* @since 1.0.0
		*/
		public function show_new_form( $term_id ) {
			
			 $this->_form_type = 'new';
			 $this->show_metabox($term_id);
			 
		}

		/**
		* Load metabox on term edit page
		* @since 1.0.0
		*/
		public function show_edit_form( $term_id ) {
			
			 $this->_form_type = 'edit';
			 $this->show_metabox($term_id);
			 
		}

		/**
		* Show metabox on term edit page
		* @since 1.0.0
		*/
		public function show_metabox( $term_id ) {
			
			// generate nonce
			wp_nonce_field( 'tomb-save-'.$this->_meta_box['id'], 'nonce_'.$this->_meta_box['id'] );
			
			echo '<table class="form-table tomb-table tomb-taxonomy-table"><tbody>';
			echo '<h2 class="tomb-taxonomy-header"><span><div class="tomb-icon">'.$this->_meta_box['icon'].'</div>'.$this->_meta_box['title'].'</span></h2>';
			
			$taxonomy = $this->_meta_box['taxonomy'];
			foreach ( $this->fields as $field ) {
				// Display content before markup of the single field
				echo self::before_field($field);
				// Get single field markup
				$saved    = $this->get_tax_meta( $taxonomy, $term_id, $field['id'] );
				$field['std'] = ( $saved !== '' ) ? $saved : (isset($field['std'])? $field['std'] : '');
				call_user_func( array( TOMB_Metabox::get_class_name( $field ), 'show_taxonomy' ), $field, $field['std'] );
				// Display content before markup of the single field
				echo self::after_field($field);

			}
			
			echo '</tbody></table>';
			
		}
		
		/**
		* Show custom markup before the markup of the field
		* @since 1.0.0
		*/
		static function before_field($field) {
			
			$output = null;
			$field_class = TOMB_Metabox::get_class_name($field);
			$required = null;
			$required_fields = null;
			$output .= '<tr>';
			$output .= '<th>';
			if ($field['name']) {
				$output .= '<label class="tomb-label">'.$field['name'].'</label>';
			}
			if ($field['desc']) {
				$output .= '<p class="tomb-desc">'.$field['desc'].'</p>';
			}
			$output .= '</th>';
			$output .= '<td class="tomb-field">';
			return $output;
			
		}

		/**
		* Show custom markup after the markup of the field
		* @since 1.0.0
		*/
		static function after_field($field) {
			
			$output  = null;
			$field_class = TOMB_Metabox::get_class_name($field);
			if ($field['sub_desc']) {
				$output .= '<p class="tomb-sub-desc">'.$field['sub_desc'].'</p>';
			}
			$output .= '</td>';
			$output .= '</tr>';
			return $output;
			
		}

		/**
		* Save data from meta box
		* @since 1.0.0
		*/
		public function save( $term_id ) {
			
			// Check if the we are coming from quick edit.
			if (isset($_REQUEST['action'])  &&  $_REQUEST['action'] == 'inline-save-tax') {
				return $term_id;
			}
			
		    // Check whether form is submitted properly
			$id    = $this->_meta_box['id'];
			$nonce = isset( $_POST["nonce_{$id}"] ) ? sanitize_key( $_POST["nonce_{$id}"] ) : '';
			if ( empty( $_POST["nonce_{$id}"] ) || ! wp_verify_nonce( $nonce, "tomb-save-{$id}" ) ) {
				return;
			}
			
			// Cycle through each field and save the values.
			$taxo = $this->_meta_box['taxonomy'];
			foreach ($this->fields as $field) {
				$name = $field['id'];
				$arr  = get_option($taxo.'_'.$term_id);
				$new  = isset( $_POST[$name] ) ? $_POST[$name] : '';
				$arr[$name] = $new;
				update_option($taxo.'_'.$term_id, $arr );
			}
			
		}		

		/**
		* Retrieve data from taxonomy term
		* @since 1.0.0
		*/
		function get_tax_meta( $taxonomy, $term_id, $key ) {
			
			$t_id = (is_object($term_id))? $term_id->term_id: $term_id;
			$m = get_option($taxonomy.'_'.$t_id);

			if (isset($m[$key]) ){
				return $m[$key];
		    } else{
		    	return '';
		    }
			
		}
		
		/**
		* Delete data from taxonomy term
		* @since 1.0.0
		*/
		static function delete_tax_meta( $taxonomy, $term_id, $key ) {
			
			$m = get_option( $taxonomy.'_'.$term_id );
			if ( isset($m[$key]) ){
		      unset($m[$key]);
		    }
		    update_option( $taxonomy.'_'.$term_id, $m );
			
		}
		
		/**
		* Delete term meta options on term delete
		* @since 1.0.0
		*/
		static function delete_term_metadata( $term, $term_id ) {
			delete_option( 'tax_meta_'.$term_id );
		}

		/**
		* Enqueue common styles
		* @since 1.0.0
		*/
		function admin_enqueue_scripts() {
			$screen = get_current_screen();
			// Enqueue scripts and styles for registered pages (post types) only
			if ( ! in_array( $screen->taxonomy, $this->_meta_box ) )
				return;
			$fields = TOMB_Metabox::get_fields( $this->fields );
			foreach ( $fields as $field ) {
				// Enqueue scripts and styles for fields
				call_user_func( array( TOMB_Metabox::get_class_name( $field ), 'admin_enqueue_scripts' ) );
			}
		}
		
	}

}