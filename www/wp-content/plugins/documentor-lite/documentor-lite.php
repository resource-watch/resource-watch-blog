<?php /*********************************************************
Plugin Name: Documentor Lite
Plugin URI: http://documentor.in/
Description: Best plugin to create online documentation or product guide on WordPress.
Text Domain: documentor-lite
Version: 1.5
Author: Tejaswini Deshpande
Author URI: http://tejaswinideshpande.com/
Wordpress version supported: 3.6 and above
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*----------------------------------------------------------------*
* Copyright 2015-2017  Documentor (email : support@documentor.in)
*****************************************************************/
// This is a wrong way to separate me buddy...I am so dependent on WordPress! Shoo away!
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
/**
 * DocumentorLite class
 *
 * @class DocumentorLite - class that holds the entire Documentor Lite plugin
 */
class DocumentorLite{
	var $documentor;
	public $default_documentor_settings;
	public $documentor_global_options;
	
	/**
	 * Constructor.
	 *
	 * @since 1.0
	 * @access public
	 */
	function __construct(){
		$this->_define_constants();
		$this->include_files();
		$this->_register_hooks();
		$this->default_documentor_settings = documentor_lite_default_settings();
		$this->documentor_global_options = documentor_lite_global_settings();
		$this->create_custom_post();
	}
	/**
	 * Define necessary constants.
	 *
	 * @since 1.0
	 * @access private
	 *
	 */
	private function _define_constants(){
		if ( ! defined( 'DOCUMENTORLITE_TABLE' ) ) define('DOCUMENTORLITE_TABLE','documentor'); //Documentor TABLE NAME
		if ( ! defined( 'DOCUMENTORLITE_SECTIONS' ) ) define('DOCUMENTORLITE_SECTIONS','documentor_sections'); //Sections TABLE NAME
		if ( ! defined( 'DOCUMENTORLITE_FEEDBACK' ) ) define('DOCUMENTORLITE_FEEDBACK','documentor_feedback'); //feedback TABLE NAME
		if ( ! defined( 'DOCUMENTORLITE_VER' ) ) define("DOCUMENTORLITE_VER","1.5",false);//Current Version of Documentor
		if ( ! defined( 'DOCUMENTORLITE_PLUGIN_BASENAME' ) )
			define( 'DOCUMENTORLITE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		if ( ! defined( 'DOCUMENTORLITE_CSS_DIR' ) )
			define( 'DOCUMENTORLITE_CSS_DIR', WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'skins/' );
		if ( ! defined( 'DOCLITE_PATH' ) )
			define( 'DOCLITE_PATH', WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) );
		if ( ! defined( 'DOCLITE_URLPATH' ) )
			define('DOCLITE_URLPATH', trailingslashit( WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) ) );
	}
	/**
	 * Attach functions to proper hooks.
	 * Load textdomain for translations 
	 * Register Documentor shortcodes
	 *
	 * @since 1.0
	 * @access public
	 *
	 */
	function _register_hooks(){
		// Register for activation
		register_activation_hook( __FILE__, array( 'DocuLite_Install', 'install') );
		add_action('plugins_loaded', array(&$this, 'documentor_update_db_check'));
		add_action('wp_enqueue_scripts', array(&$this, 'load_styles_scripts'));
		add_action('wp_footer', array(&$this, 'documentor_custom_styles') );
		load_plugin_textdomain('documentor-lite', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
		//[documentor] shortcode
		if (!shortcode_exists( 'documentor' ) ) add_shortcode('documentor', array(&$this,'shortcode'));
	}
	/**
	* Necessary resources to be enqueued on page load
	*
	* @since 1.5
	* @access public
	*
	* @param none 
	* @return none 
	*/
	function load_styles_scripts(){
        wp_enqueue_script('jquery');
    }
	
	/**
	* Function to run when [documentor] shortcode is called
	*
	* @since 1.0
	* @access public
	*
	* @param string $atts Shortcode Attributes.
	* @return string HTML for the complete Guide.
	*/
	function shortcode( $atts ) {
		$doc_id = isset($atts[0])?$atts[0]:'';
		$id 	= intVal($doc_id);
		$guide 	= new DocumentorLiteGuide( $id );
		$html 	= $guide->view();
		return $html;
	}
	
	/**
	* Include all the necessary files
	*
	* @since 1.0
	* @access public
	*/
	function include_files() { 
		require_once (dirname (__FILE__) . '/core/includes/functions.php');
		require_once (dirname (__FILE__) . '/core/includes/compat.php');
		require_once (dirname (__FILE__) . '/core/class-docu-install.php');
		require_once (dirname (__FILE__) . '/core/includes/fonts.php');
		require_once (dirname (__FILE__) . '/core/admin.php');
		require_once (dirname (__FILE__) . '/core/guide.php');
		require_once (dirname (__FILE__) . '/core/api.php');
		require_once (dirname (__FILE__) . '/core/section.php');
		require_once (dirname (__FILE__) . '/core/ajax.php');
	}
	
	/**
	* Returns the Documentor Plugin Directory URL
	*
	* @since 1.0
	* @access public
	*
	* @param string $path The file path/relative url inside the Documentor Plugin folder.
	* @return string Complete URL of the file.
	*/
	public static function documentor_plugin_url( $path = '' ) {
		return plugins_url( $path, __FILE__ );
	}
	
	/**
	* Returns the Admin URL for specific page of Documentor depending on the query passed
	*
	* @since 1.0
	* @access public
	*
	* @param string $query Which page to display
	* @return string Complete Admin URL of the page.
	*/
	public static function documentor_admin_url( $query = array() ) {
		global $plugin_page;

		if ( ! isset( $query['page'] ) )
			$query['page'] = $plugin_page;

		$path = 'admin.php';

		if ( $query = build_query( $query ) )
			$path .= '?' . $query;

		$url = admin_url( $path );

		return esc_url_raw( $url );
	}
	/**
	* Run the install functions if the database version of 
	* Documentor does not match the current plugin version. 
	*
	* @since 1.0
	* @access public
	*
	*/
	function documentor_update_db_check() {
		$documentorlite_db_version = DOCUMENTORLITE_VER;
		if (get_site_option('documentorlite_db_version') != $documentorlite_db_version) {
			DocuLite_Install::install();
		}
	}
	/**
	* New Custom Post Types - for Guide and Section
	*
	* @since 1.0
	* @access public
	*
	*/
	function create_custom_post() {
		//New Custom Post Type
		$global_settings_curr = get_option('documentor_global_options');
		if( isset( $global_settings_curr['custom_post'] ) && $global_settings_curr['custom_post'] == '1' && !post_type_exists('documentor-sections') ){
			add_action( 'init', array( &$this, 'section_post_type'), 11 );
			//add filter to ensure the text Sections, or Section, is displayed when user updates a Section 
			add_filter('post_updated_messages', array( &$this, 'section_updated_messages') );
		} //if custom_post is true //ver1.4 start
		if( !post_type_exists('guide') ){		
			add_action( 'init', array( &$this, 'guide_post_type'), 11 );
		}
	}	
	/**
	* Register 'documentor-sections' custom post type
	*
	* @since 1.0
	* @access public
	*
	*/
	function section_post_type() {
		$labels = array(
			'name' 				=> _x('Sections', 'post type general name', 'documentor-lite'),
			'singular_name' 	=> _x('Section', 'post type singular name', 'documentor-lite'),
			'add_new' 			=> _x('Add New', 'Add New Documentor Section', 'documentor-lite'),
			'add_new_item' 		=> __('Add New Documentor Section', 'documentor-lite'),
			'edit_item' 		=> __('Edit Documentor Section', 'documentor-lite'),
			'new_item' 			=> __('New Documentor Section', 'documentor-lite'),
			'all_items' 		=> __('All Documentor Sections', 'documentor-lite'),
			'view_item' 		=> __('View Documentor Section', 'documentor-lite'),
			'search_items' 		=> __('Search Documentor Sections', 'documentor-lite'),
			'not_found' 		=>  __('No Documentor sections found', 'documentor-lite'),
			'not_found_in_trash'=> __('No Documentor section found in Trash', 'documentor-lite'), 
			'parent_item_colon' => '',
			'menu_name' 		=> 'Sections'
		);
		$args = array(
			'labels' 			=> $labels,
			'public' 			=> true,
			'exclude_from_search'=> true,
			'publicly_queryable'=> false,
			'show_ui' 			=> true, 
			'show_in_menu' 		=> false, 
			'show_in_nav_menus' => false,
			'query_var' 		=> false,
			'rewrite' 			=> array('slug' => 'section','with_front' => false),
			'capability_type' 	=> 'post',
			'has_archive' 		=> false, 
			'hierarchical' 		=> false,
			'menu_position' 	=> null,
			'can_export' 		=> true,
			'supports' 			=> array('editor','thumbnail','excerpt','custom-fields', 'comments')
		); 
		register_post_type('documentor-sections',$args);
	} 
	/**
	* Register 'guide' custom post type
	*
	* @since 1.0
	* @access public
	*
	*/
	function guide_post_type() {
		$labels = array(
			'name'				=> _x('Guides', 'post type general name', 'documentor-lite'),
			'singular_name' 	=> _x('Guide', 'post type singular name', 'documentor-lite'),
			'add_new' 			=> _x('Add New', 'documentor-lite', 'documentor-lite'),
			'add_new_item' 		=> __('Add New Documentor Guide', 'documentor-lite'),
			'edit_item' 		=> __('Edit Documentor Guide', 'documentor-lite'),
			'new_item' 			=> __('New Documentor Guide', 'documentor-lite'),
			'all_items' 		=> __('All Documentor Guides', 'documentor-lite'),
			'view_item' 		=> __('View Documentor Guide', 'documentor-lite'),
			'search_items' 		=> __('Search Documentor Guides', 'documentor-lite'),
			'not_found' 		=>  __('No Documentor guides found', 'documentor-lite'),
			'not_found_in_trash'=> __('No Documentor guides found in Trash', 'documentor-lite'), 
			'parent_item_colon' => '',
			'menu_name' 		=> 'Guides'
		);
		$args = array(
			'labels' 			=> $labels,
			'public' 			=> false,
			'publicly_queryable'=> false,
			'show_ui' 			=> false, 
			'show_in_menu' 		=> false, 
			'show_in_nav_menus' => false,
			'query_var' 		=> false,
			'rewrite' 			=> array('slug' => 'guide','with_front' => false),
			'capability_type' 	=> 'post',
			'has_archive' 		=> false, 
			'hierarchical' 		=> false,
			'menu_position' 	=> null,
			'can_export' 		=> true,
			'supports' 			=> array('title','editor','thumbnail','excerpt','custom-fields')
		); 
		
		register_post_type('guide',	$args); //ver1.4 end
	}
	
	/**
	* Add Section post type update messages (by default it will be similar to post)
	*
	* @since 1.0
	* @access public
	*
	* @param array $messages Display messages for various post types
	* @return array $messages Display messages for various post types including for documentor-sections
	*/
	function section_updated_messages( $messages ) {
		global $post, $post_ID;
		$messages['documentor-sections'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Documentor Section updated. <a href="%s">View Documentor section</a>'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __('Documentor Section updated.'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Documentor section restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Documentor Section published. <a href="%s">View Documentor section</a>'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Section saved.'),
			8 => sprintf( __('Documentor Section submitted. <a target="_blank" href="%s">Preview Documentor Section</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('Documentor Sections scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Documentor Section</a>'),
			  // translators: Publish box date format, see http://php.net/date
			  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Documentor Section draft updated. <a target="_blank" href="%s">Preview Documentor Section</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

		return $messages;
	}
	
	/**
	* Include the CSS added on Global Settings in the page head on front
	*
	* @since 1.0
	* @access public
	*/
	function documentor_custom_styles() {
		global $doc_customstyles;
		if( !isset( $doc_customstyles ) or $doc_customstyles < 1 ) {
			$global_curr = get_option('documentor_global_options');
			if( !empty( $global_curr['custom_styles'] ) ) {  ?>
				<style type="text/css"><?php echo $global_curr['custom_styles'];?></style>
			<?php }
			$doc_customstyles++;
		}
	}
}

/**
* Documentor template tag/function - Diplays the said guide
*
* @since 1.0
* @access public
*
* @param string $id The ID of the Guide to display
*/
if(!function_exists('get_documentor')){
	function get_documentor( $id=0 ) {
		$guide = new DocumentorLiteGuide( $id );
		$html = $guide->view();
		echo $html;
	}
}

/**
* Create an instance of the Documentor class
*/
if( class_exists( 'DocumentorLite' ) ) {
  $cn = new DocumentorLite();
}
?>
