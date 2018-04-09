<?php
/**
 * Author:      Themeone
 * Author URI:  https://theme-one.com
 */
 
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('TOMB_Metabox')) {
	
	class TOMB_Metabox {
	 
	 	/**
		* @vars string
		* @since 1.0.0
		*/
		protected $_meta_box;
		public $saved = false;
		public $fields;
		
		/**
	 	* Themeone Metabox Constructor
		* @since 1.0.0
	 	*/
		public function __construct($meta_box) {
			
			$this->_meta_box = $meta_box;
			$this->init_hooks();
			$this->build_metaboxes();
				
		}
		
		/**
		* Hook into actions and filters
		* @since 1.0.0
		*/
		public function init_hooks() {
			
			// register setup and save actions
			add_action( 'add_meta_boxes', array($this, 'TOMB_Metabox_add' ));
			add_action( 'save_post', array($this, 'TOMB_Metabox_save' ));
			// attachment post type hook to save
			add_action( 'edit_attachment', array ( $this, 'TOMB_Metabox_save' ));
			// Enqueue styles and scripts
			add_action( 'admin_head',array($this, 'append_scripts' ));
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ));
			
		}
	 	
		/**
	 	* Themeone Metabox Constructor
		* @since 1.0.0
	 	*/
		public function build_metaboxes() {
			
			// Get fields value class name
			$this->themeone_default_metabox_args();
		    $this->fields = &$this->_meta_box['fields'];
			$fields = self::get_fields($this->fields);
			
			// get metabox class name
			foreach ($fields as $field) {
				call_user_func(array(self::get_class_name($field), 'add_actions'));
			}

			// Add custom classes to metabox
			$post_type_object = $this->_meta_box['pages'];
			foreach ($post_type_object as $page) {
				add_filter( 'postbox_classes_'.$page.'_'.$this->get_id(), array( $this, 'add_class_to_metabox' ) );
			}
			
			// add metabox in page
			$this->metabox_page();
			
		}
		
		/**
		* Add any metabox in page
		* @since 1.0.0
		*/
		public function metabox_page() {
			
			if (isset($this->_meta_box['type']) && $this->_meta_box['type'] == 'page') {

				// Get post ID
				if (isset($_GET['id']) && !empty($_GET['id'])) {
					$post_ID = $_GET['id'];
				} else {
					$post = get_default_post_to_edit('the_grid', true);
					$post_ID = $post->ID;
				}
				
				// set global post
				global $post;
				$post = array('ID' => $post_ID);
				$post = new \WP_Post( (object) $post );
				
				$class = (isset($this->_meta_box['class']) && !empty($this->_meta_box['class'])) ? ' '.$this->_meta_box['class'] : '';
				
				echo '<div id="'.$this->_meta_box['id'].'" class="tomb-metabox'.$class.'">';
					$this->TOMB_Metabox_show($post);
				echo '</div>';
			
			} else if (isset($this->_meta_box['type']) && $this->_meta_box['type'] == 'page2') {
				
				global $post;
				$post = array('ID' => -1);
				$post = new \WP_Post( (object) $post );
				
				$class = (isset($this->_meta_box['class']) && !empty($this->_meta_box['class'])) ? ' '.$this->_meta_box['class'] : '';
				
				echo '<div id="'.$this->_meta_box['id'].'" class="tomb-metabox'.$class.'">';
					$this->TOMB_Metabox_show($post);
				echo '</div>';
				
			}
			
		}
		
		/**
		* Append custom style to metaboxes
		* @since 1.0.0
		*/
		public function append_scripts() {
			
			global $post_type;
			if ($this->_meta_box['color'] && $this->_meta_box['background'] && !empty($post_type)) {
				$style  = '<style type="text/css">';
				$style .= '#'.$this->_meta_box['id'].' .hndle.ui-sortable-handle{';
				$style .= 'color:'.$this->_meta_box['color'].';';
				$style .= 'background:'.$this->_meta_box['background'].';';
				$style .= '}';
				$style .= '#'.$this->_meta_box['id'].'.tomb-metabox .handlediv:before {';
				$style .= 'color:'.$this->_meta_box['color'].';';
				$style .= '}';
				$style .= '</style>';
				echo $style;
			}	
					
		}
		
		/**
		* Set metabox class name
		* @since 1.0.0
		*/
		public function add_class_to_metabox($classes) {
			
			$className = null;
			if (isset($this->_meta_box['close']) && $this->_meta_box['close'] === true) {
				$className .= ' closed';
			} 
			if (isset($this->_meta_box['menu']) && $this->_meta_box['menu'] === true) {
				$className .= ' tomb-menu-options';
			} 
			if (isset($this->_meta_box['context']) && $this->_meta_box['context'] === 'side') {
				$className .= ' tomb-side';
			}
			array_push($classes, 'tomb-metabox'.$className);
		    return $classes;
			
		}
		
		/**
		* Get metabox ID
		* @since 1.0.0
		*/
		public function get_id() {
			
			return $this->_meta_box['id'];
			
		}
		
		/**
		* Set default metabox arguments
		* @since 1.0.0
		*/
		public function themeone_default_metabox_args() {
			
    		$this->_meta_box = array_merge( array( 'context' => 'normal', 'priority' => 'high', 'pages' => array( 'post' )), (array)$this->_meta_box );
			$this->_meta_box['fields'] = self::normalize_fields($this->_meta_box['fields']);
			
		}
		
		/**
		* Add metabox
		* @since 1.0.0
		*/
		public function TOMB_Metabox_add($post_type) {

			foreach ($this->_meta_box['pages'] as $page) {
				add_meta_box(
					$this->_meta_box['id'], 
					'<span class="tomb-icon">'.$this->_meta_box['icon'].'</span>'.$this->_meta_box['title'],
					array( $this, 'TOMB_Metabox_show' ),
					$page,
					$this->_meta_box['context'],
					$this->_meta_box['priority']
				);
			}
			
		}
		
		/**
		* Set metabox fields from array
		* @since 1.0.0
		*/
		static function get_fields( $fields ) {
			
			$all_fields = array();
			foreach ($fields as $field) {
				$all_fields[] = $field;
				if (isset( $field['fields'])) {
					$all_fields = array_merge($all_fields, self::get_fields($field['fields']));
				}
			}
			return $all_fields;
			
		}
		
		/**
		* Normalize metabox fields class name
		* @since 1.0.0
		*/
		static function get_class_name( $field ) {
			
			$type  = str_replace( '_', ' ', $field['type'] );
			$class = 'TOMB_' . ucwords( $type ) . '_Field';
			$class = str_replace( ' ', '_', $class );
			return class_exists( $class ) ? $class : false;
			
		}
		
		/**
		* Normalize fied values
		* @since 1.0.0
		*/
		static function normalize_fields( $fields ) {
			
			foreach ($fields as &$field) {
				$field = wp_parse_args( $field, array(
					'id'            => isset( $field['id'] ) ? $field['id'] : md5(uniqid(rand(), true)),
					'icon'          => '',
					'color'         => '',
					'background'    => '',
					'std'           => '',
					'desc'          => '',
					'sub_desc'      => '',
					'size'          => '',
					'checkbox_title'=> '',
					'disabled'      => '',
					'frame_title'   => '',
					'frame_button'  => '',
					'button_upload' => '',
					'button_remove' => '',
					'name'          => isset( $field['id'] ) ? $field['id'] : '',
					'placeholder'   => '',
					'in_row'        => '',
					'min'           => '',
					'max'           => '',
					'step'          => '',
					'sign'          => '',
					'args'          => '',
					'theme'         => '',
					'mode'          => '',
					'classes'       => 'tomb-field'
				) );
			}
			return $fields;
			
		}
		
		/**
		* Metabox has been saved
		* @since 1.0.0
		*/
		static function has_been_saved( $post_id, $fields ) {
			
			foreach ( $fields as $field ) {
				$value = get_post_meta( $post_id, $field['id'], true );
				if ( '' !== $value ) {
					return true;
				}
			}
			return false;
			
		}
		
		/**
		* Show metabox array
		* @since 1.0.0
		*/
		function TOMB_Metabox_show($post) {
			
			$post_id = $post->ID;
			$fields  = array();
			$icons   = array();
			$saved   = self::has_been_saved($post_id, $this->fields);
			
			if (!isset($this->_meta_box['type']) || empty($this->_meta_box['type'])) {
				wp_nonce_field( "tomb-save-{$this->_meta_box['id']}", "nonce_{$this->_meta_box['id']}" );	
			}
			
			foreach ( $this->fields as $field ) {
				// Display content before markup of the single field
				$tabName = isset($field['tab']) ? $field['tab'] : 'general';
				$tabIcon = isset($field['tab_icon']) ? $field['tab_icon'] : '';
				$fieldID = $field['id'];
				$output  = null;
				$output .= TOMB_Fields::before_field($field);
				// Output buffering
				ob_start();
				$output .= call_user_func( array( self::get_class_name($field), 'show' ), $field, $saved );
				$output .= ob_get_contents();
				ob_end_clean();
				// Display content after markup of the single field.
				$output .= TOMB_Fields::after_field($field);
				$fields[$tabName][$fieldID] = $output;
				$icons[$tabName] = $tabIcon;
			}
			
			// Construct Tabs
			$tabNb    = sizeof($fields);
			$tabs     = array_keys($fields);
			$selected = ' selected';
			
			if ($tabNb > 1) {
				echo '<ul class="tomb-tabs-holder">';
				foreach ($tabs as $tab) {
					echo '<li class="tomb-tab'.$selected.'" data-target="'.$this->themeone_tab_name($tab).'">'.$icons[$tab].$tab.'</li>';
					$selected = null;
				}
				echo '</ul>';
				
				foreach ($tabs as $tab) {
					echo '<div class="tomb-tab-content '.$this->themeone_tab_name($tab).'">';
					foreach ($fields[$tab] as $input) {
						print_r($input);
					}
					echo '</div>';
				}
			} else {
				foreach ($tabs as $tab) {
					foreach ($fields[$tab] as $input) {
						print_r($input);
					}
				}
			}
		}
		
		/**
		* Set metabox tab name
		* @since 1.0.0
		*/
		function themeone_tab_name($string) {
			$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
			$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
			return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
		}
		
		/**
		* Enqueue metabox scripts
		* @since 1.0.0
		*/
		function admin_enqueue_scripts() {
			
			$screen = get_current_screen();
			
			// Enqueue scripts and styles for registered pages (post types) only
			if ( 'post' != $screen->base || ! in_array( $screen->post_type, $this->_meta_box['pages'] ) ) {
				return;
			}

			$fields = self::get_fields( $this->fields );
			foreach ( $fields as $field ) {
				// Enqueue scripts and styles for fields
				call_user_func( array( self::get_class_name( $field ), 'admin_enqueue_scripts' ) );
			}
			
		}
		
		/**
		* Save MetaBox
		* @since 1.0.0
		*/
		function TOMB_Metabox_save($post_id) {
			
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return;
			}
			
			// Check the user's permissions.
			if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
				if (!current_user_can('edit_page', $post_id)) {
					return;
				}
			} else {
				if (!current_user_can('edit_post', $post_id)) {
					return;
				}
			}
			$this->saved = true;
			

			// Check whether form is submitted properly
			$id    = $this->_meta_box['id'];
			$nonce = isset( $_POST["nonce_{$id}"] ) ? sanitize_key( $_POST["nonce_{$id}"] ) : '';
			if ( empty( $_POST["nonce_{$id}"] ) || ! wp_verify_nonce( $nonce, "tomb-save-{$id}" ) ) {
				return;
			}

			// Make sure meta is added to the post, not a revision
			if ($the_post = wp_is_post_revision($post_id)) {
				$post_id = $the_post;
			}
			
			// Cycle through each field and save the values.
			foreach ($this->fields as $field) {
				$name = $field['id'];
				$old  = get_post_meta($post_id, $name, true);
				$new  = isset($_POST[$name]) ? $_POST[$name] : '';
				$new  = call_user_func(array(self::get_class_name($field), 'value'), $new, $old, $post_id, $field);
				call_user_func(array(self::get_class_name($field), 'save'), $new, $old, $post_id, $field, 'post');
			}
		}
		
	}

}