<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

class The_Grid_Admin_Ajax {
	
	// Instance of this class.
	protected $plugin_slug = TG_SLUG;
	protected $ajax_data;
	protected $ajax_msg;
	
	// grid list templates
	const VIEW_GRID_LIST     = "grid-list";
	const VIEW_GRID_INFO     = "grid-info";
	const VIEW_GRID_SETTINGS = "grid-settings";
	const VIEW_SKIN_OVERVIEW = "skins-overview";
	
	/**
	* Initialization (load admin scripts & styles and add main actions)
	* @since 1.0.0
	*/
	public function __construct() {
		
		// Init all admin Hooks
		$this->init_hooks();
					
	}
	
	/**
	* Hook into actions and filters
	* @since 1.0.0
	*/
	public function init_hooks() {
		
		// Export grids functionnality
		add_action('admin_init', array($this, 'items_export_callback'));
		// Export skin functionnality
		add_action('admin_init', array($this, 'skin_download_callback'));
		// Register backend ajax action
		add_action('wp_ajax_backend_grid_ajax', array($this, 'backend_grid_ajax'));
		// Load admin ajax js script
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
		
	}
	
	/**
	* Ajax array response for wp_send_json
	* @since 1.0.0
	*/
	public function ajax_response($success = true, $message = null, $content = null) {
		
		$response = array(
			'success' => $success,				
			'message' => $message,
			'content' => $content
		);
		
		return $response;
		
	}
	
	/**
	* Check nonce in backend
	* @since 1.0.0
	*/
	public function grid_check_nonce() {
		
		// retrieve nonce
		$nonce  = (isset($_POST['nonce'])) ? $_POST['nonce'] : $_GET['nonce']; 
		
		// nonce action for the grid
		$action = 'tg_admin_nonce';
		
		// check ajax nounce
		if (!wp_verify_nonce($nonce, $action)) {
			
			// build response
			$response = $this->ajax_response(false, __('Sorry, an error occurred. Please refresh the page.', 'tg-text-domain'));
			// die and send json error response
			wp_send_json($response);
			
		}
		
	}
	
	/**
	* Backend ajax action - functions switcher
	* @since 1.0.0
	*/
	public function backend_grid_ajax() {
		
		// check the nonce
		$this->grid_check_nonce();
		
		// retrieve data
		$this->ajax_data = (isset($_POST)) ? $_POST : $_GET;
		
		// retrieve function
		$func = $this->ajax_data['func'];

		switch ($func) {
			case 'tg_save':
				$response = $this->save_grid_callback();
				break;
			case 'tg_clone':
				$response = $this->clone_grid_callback();
				break;
			case 'tg_delete':
				$response = $this->delete_grid_callback();
				break;
			case 'tg_order':
				$response = $this->order_list_callback();
				break;
			case 'tg_per_page':
				$response = $this->per_page_list_callback();
				break;
			case 'tg_page_nb':
				$response = $this->page_nb_list_callback();
				break;
			case 'tg_favorite':
				$response = $this->favorite_grid_callback();
				break;
			case 'tg_save_settings':
				$response = $this->save_settings_callback();
				break;
			case 'tg_reset_settings':
				$response = $this->save_settings_callback();
				break;
			case 'tg_delete_cache':
				$response = $this->clear_cache_callback();
				break;
			case 'tg_import_items':
				$response = $this->import_item_callback();
				break;
			case 'tg_read_import_file':
				$response = $this->import_read_file_callback();
				break;
			case 'tg_grid_preview':
				$response = $this->grid_preview_callback();
				break;
			case 'tg_skin_selector':
				$response = $this->grid_skins_callback();
				break;
			case 'tg_save_item_settings':
				$response = $this->save_item_settings_callback();
				break;
			case 'tg_save_envato_api_token':
				$response = $this->save_envato_api_token();
				break;
			case 'tg_check_for_update':
				$response = $this->check_for_update();
				break;
			case 'tg_save_skin':
				$response = $this->save_skin();
				break;
			case 'tg_clone_skin':
				$response = $this->clone_skin();
				break;
			case 'tg_delete_skin':
				$response = $this->delete_skin();
				break;
			case 'tg_import_demo_skins':
				$response = $this->import_demo_skins();
				break;
			case 'tg_get_elements':
				$response = $this->get_elements();
				break;
			case 'tg_save_element':
				$response = $this->save_element();
				break;
			case 'tg_delete_element':
				$response = $this->delete_element();
				break;
			case 'tg_get_flickr_photosets':
				$response = $this->get_flickr_photosets();
				break;
			default:
				$response = ajax_response(false, __( 'Sorry, an unknown error occurred...', 'tg-text-domain'), null);
				break;
		}
		
		// send json response and die
		wp_send_json($response);
	
	}
	
	/**
	* Retrieve the grid list
	* @since 1.0.0
	*/
	public function get_grid_list() {
		
		ob_start();
		require_once('views/'.self::VIEW_GRID_LIST.'.php');
		$list = ob_get_clean();
		return $list;
		
	}
	
	/**
	* Clone grid list
	* @since 1.0.0
	*/
	public function clone_grid_callback() {

		$post = get_post($this->ajax_data['post_ID']);
		
		$new_post = array(
			'menu_order'     => $post->menu_order,
			'comment_status' => $post->comment_status,
			'ping_status'    => 'default_ping_status',
			'post_author'    => '',
			'post_content'   => '',
			'post_excerpt'   => '',
			'post_parent'    => 0,
			'post_password'  => '',
			'post_status'    => 'publish',
			'post_title'     => $post->post_title,
			'post_name'      => $post->post_title,
			'post_type'      => $this->plugin_slug
		);

		$new_post_id = wp_insert_post($new_post);

		//unique post slug based on cloned post
		$post_title = $post->post_title.' '.$new_post_id;
		
		$new_post = array();
		$new_post['ID']         = $new_post_id;
		$new_post['post_title'] = $post_title;
		$new_post['post_name']  = $post_title;
		
		// Update the post into the database
		wp_update_post( $new_post );
		
		// get meta data and clone
		$this->duplicate_meta_data($new_post_id,$post);
		
		// add new post title to meta value
		update_post_meta($new_post_id, 'the_grid_name', $post_title);

		// sort to date and DSC in order to see the new one cloned
		update_option('the_grid_order', 'data');
		update_option('the_grid_orderby', 'DSC');

		// build response
		$response = $this->ajax_response(true, null, $this->get_grid_list());		
		return $response;
		
	}
	
	/**
	* Get all meta data / add them to new post
	* @since 1.0.0
	*/
	public function duplicate_meta_data($new_id, $post) {
		
		$post_meta_keys = get_post_custom_keys($post->ID);
		
		if (empty($post_meta_keys)) {
			return;
		}
	
		foreach ($post_meta_keys as $meta_key) {
			$meta_values = get_post_custom_values($meta_key, $post->ID);
			foreach ($meta_values as $meta_value) {
				$meta_value = maybe_unserialize($meta_value);
				add_post_meta($new_id, $meta_key, $meta_value);
			}
		}
		
	}

	/**
	* Delete grid list
	* @since 1.0.0
	*/
	public function delete_grid_callback() {
		
		wp_delete_post($this->ajax_data['post_ID'], true);
		
		// build response
		$response = $this->ajax_response(true, null, $this->get_grid_list());		
		return $response;
		
	}
	
	/**
	* Favorite grid list
	* @since 1.0.0
	*/
	public function favorite_grid_callback() {
		
		if ($this->ajax_data['meta_data'] != 'favorite') {
			update_post_meta($this->ajax_data['post_ID'], 'the_grid_favorite', 'favorite');
		} else {
			update_post_meta($this->ajax_data['post_ID'], 'the_grid_favorite', '');
		}
		
		// build response
		$response = $this->ajax_response(true, null, $this->get_grid_list());		
		return $response;
		
	}
	
	/**
	* Order grid list
	* @since 1.0.0
	*/
	public function order_list_callback() {
		
		// update option orber/oderby for query
		update_option('the_grid_order', $this->ajax_data['order']);
		update_option('the_grid_orderby', $this->ajax_data['orderby']);
		
		// build response
		$response = $this->ajax_response(true, null, $this->get_grid_list());		
		return $response;
		
	}
	
	/**
	* Item number in grid list
	* @since 1.0.0
	*/
	public function per_page_list_callback() {
		
		// update page number for query
		update_option('the_grid_number', $this->ajax_data['number']);
		
		// build response
		$response = $this->ajax_response(true, null, $this->get_grid_list());
		return $response;
		
	}
	
	/**
	* Page number in grid list
	* @since 1.0.0
	*/
	public function page_nb_list_callback() {
		
		$_GET['pagenum'] = $this->ajax_data['page_nb'];
		
		// build response
		$response = $this->ajax_response(true, null, $this->get_grid_list());
		return $response;
		
	}

	/**
	* Save meta box
	* @since 1.0.0
	*/
	public function save_grid_callback() {
		
		// retrieve data from jquery
		global $wpdb;
		
		// retireve post data
		$meta_data = json_decode(stripslashes($this->ajax_data['meta_data']),true);
		$post_ID   = $this->ajax_data['post_ID'];
		$grid_name = $meta_data['the_grid_name'];
		
		// check if empty grid name
		if (empty($grid_name)) {
			// build response
			$response = $this->ajax_response(false, $this->empty_grid_name_msg(), null);
			return $response;
		}
		
		// check unique grid title
		$grid_exist = $this->check_grid_name($grid_name, $post_ID);
		if ($grid_exist) {
			// build response
			$response = $this->ajax_response(false, $this->exist_grid_name_msg(), null);
			return $response;
		}
		
		// set info for grid modification date
		$datetime  = current_time('Y-m-d\ H:i:s');
		
		// insert post if do not exist
		if ('publish' != get_post_status($post_ID)) {
			$post_ID = wp_insert_post( array(
				'import_id'    => $post_ID,
				'post_type'    => $this->plugin_slug,
				'post_title'   => $grid_name,
				'post_name'    => $grid_name,
				'post_content' => '',
				'post_status'  => 'publish',
				'post_author'  => ''
			));
			// set meta value favorite
			add_post_meta($post_ID, 'the_grid_favorite', '');
			// save post title if post exist
			$wpdb->query( "UPDATE `$wpdb->posts` SET `post_date` = '".$datetime." 'WHERE `ID` = '".$post_ID."'" );
		} else {
			// save post title if post exist
			$wpdb->update( $wpdb->posts, array( 'post_title' =>  $grid_name ), array( 'ID' => $post_ID ) );
			$wpdb->query( "UPDATE `$wpdb->posts` SET `post_modified` = '".$datetime." 'WHERE `ID` = '".$post_ID."'" );
			
		}
		
		// add metadata
		foreach ($meta_data as $meta => $value) {
			update_post_meta($post_ID, $meta, $value );
		}
		
		// delete cache if grid options change
		$grid_name = 'tg_grid_'.$post_ID;
		$base = new The_Grid_Base();
		$base->delete_transient($grid_name);	
		
		// change post new post ID
		global $pagenow;
		if ($this->ajax_data['post_ID'] != $post_ID && 'edit.php' != $pagenow) {
			// build response
			$response = $this->ajax_response(true, null, $post_ID);
		} else {
			$response = $this->ajax_response(true, null, null);
		}
		
		return $response;

	}
	
	/**
	* Check if grid name is unique
	* @since 1.0.0
	*/
	public function check_grid_name($grid_name, $grid_ID) {
		
		// check if post grid title exist
		$grid_exist = get_page_by_title($grid_name, OBJECT, 'the_grid');
		
		if ($grid_exist && $grid_exist->ID != $grid_ID) {
			return true;
		}
	
	}
	
	/**
	* Grid name is empty message
	* @since 1.0.0
	*/
	public function empty_grid_name_msg() {
		
		$html  = '<strong>'. __( 'The grid name field is empty!', 'tg-text-domain' ).'</strong><br>';
		$html .= __( 'Please, you must fill the grid name field.', 'tg-text-domain' ).'<br>';
		$html .= __( 'The grid name field is accessible in General tab.', 'tg-text-domain' ).'<br>';
		$html .= '<div class="tg-button tg-close-infox-box">';
			$html .= '<i class="dashicons dashicons-no-alt"></i>';
			$html .= __( 'Close', 'tg-text-domain' );
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	* Grid name already exist message
	* @since 1.0.0
	*/
	public function exist_grid_name_msg() {
		
		$html  = '<strong>'. __( 'This grid name already exist!', 'tg-text-domain' ).'</strong><br>';
		$html .= __( 'Please, change your grid name in order to save your current settings.', 'tg-text-domain' ).'<br>';
		$html .= __( 'The grid name field is accessible in General tab.', 'tg-text-domain' ).'<br>';
		$html .= '<div class="tg-button tg-close-infox-box">';
			$html .= '<i class="dashicons dashicons-no-alt"></i>';
			$html .= __( 'Close', 'tg-text-domain' );
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	* Clear cache, delete transient
	* @since 1.0.0
	*/
	public function clear_cache_callback() {
		
		// delete all transient generated for The Grid plugin
		$base = new The_Grid_Base();
		$base->delete_transient('tg_grid');
		$response = $this->ajax_response(true, null, null);
		return $response;
	
	}
	
	/**
	* Save global settings
	* @since 1.0.0
	*/
	public function save_settings_callback() {

		// retrieve data from jquery
		$setting_data = json_decode(stripslashes($this->ajax_data['setting_data']), true);
		
		// add metadata
		foreach ($setting_data as $setting => $value) {
			// because of get_magic_quotes_gpc()
			$value = stripslashes($value);
			update_option($setting, $value);
		}
		
		$template = false;
		// get new restore global settings panel
		if($this->ajax_data['reset']) {
			ob_start();
			require_once('views/'.self::VIEW_GRID_SETTINGS.'.php');
			$template = ob_get_clean();
		}
		
		$response = $this->ajax_response(true, $this->ajax_data['reset'], $template);
		return $response;

	}
	
	/**
	* Export grid(s) settings as .json file
	* @since 1.0.0
	*/
	public function items_export_callback() {
		
		if (isset($_POST['tg_export_items'])) {
			
			$item_ids  = $_POST['tg_export_items'];
			
			if (!empty($item_ids)) {
				
				$item_ids = json_decode(stripslashes($item_ids), true);

				$items = array();
				
				// process grids
				$grid_ids = (isset($item_ids['grid'])) ? $item_ids['grid'] : array();
				foreach ($grid_ids as $grid_name => $grid_id) {	
					$items['grids'][$grid_name] = $this->retrieve_grid_settings($grid_id);
				}
				
				// process skins
				$skin_ids = (isset($item_ids['skin'])) ? $item_ids['skin'] : array();
				foreach ($skin_ids as $skin_name => $skin_id) {
					$items['skins'][$skin_name] = The_Grid_Custom_Table::get_skin_settings($skin_id);
				}
				
				// process elements
				$elem_ids = (isset($item_ids['elem'])) ? $item_ids['elem'] : array();
				foreach ($elem_ids as $elem_name => $elem_id) {
					$items['elements'][$elem_name] = The_Grid_Custom_Table::get_element_settings($elem_id);
				}
				
				// set header to download the export file
				$filename = 'the_grid_'.current_time( 'Y_m_d' ).'.json';
				header('Content-Disposition: attachment; filename='.$filename);
				header('Content-Type: application/download');
				header('Pragma: no-cache');
				header('Expires: 0');
				echo json_encode($items);
				
				exit();
				
			}

		}
		
	}
		
	/**
	* Get all grid settings to export
	* @since 1.0.0
	*/
	public function retrieve_grid_settings($post_ID) {
		
		// retrieve all metadata for current grid
		$post_meta_keys = get_post_custom_keys($post_ID);

		if (empty($post_meta_keys)) {
			return;
		}
		
		// loop through each metadata an push them in array
		foreach ($post_meta_keys as $meta_key) {
			
			if (strpos($meta_key,$this->plugin_slug) !== false) {
				
				$meta_values = get_post_custom_values($meta_key, $post_ID);
				
				foreach ($meta_values as $meta_value) {
					$meta_value = maybe_unserialize($meta_value);
					$post_info[$meta_key] = $meta_value;
				}
				
			}
			
		}
		
		return $post_info;
		
	}
	
	/**
	* Import Grid(s) from .json file/data
	* @since 1.0.0
	*/
	public function import_item_callback() {
		
		$template   = null;
		$item_data  = (isset($this->ajax_data['item_data']))  ? stripslashes($this->ajax_data['item_data']) : null;
		$item_names = (isset($this->ajax_data['item_names'])) ? $this->ajax_data['item_names'] : array();
		$grid_demo  = (isset($this->ajax_data['grid_demo']))  ? $this->ajax_data['grid_demo'] : null;
		
		// if no data
		if (empty($item_data) && !$grid_demo) {
			
			$response = $this->ajax_response(false, __( 'Sorry, an error occurs, no data found', 'tg-text-domain' ));
			return $response;
			
		}
		
		// if no item selected
		if ((!is_array($item_names) || count($item_names) == 0) && !$grid_demo) {
			
			$response = $this->ajax_response(false, __( 'Please, select item(s) to import', 'tg-text-domain' ));
			return $response;
			
		}
		
		// decode item data
		$grids = json_decode(stripslashes($item_data));
		
		// if grid demo content get demo file content
		if ($grid_demo) {
			
			$base = new The_Grid_Base();
			$item_data = $base->request_data(TG_PLUGIN_URL . '/backend/data/demo-content.json');
			
		}

		if (!empty($item_data)) {
			
			$items = json_decode($item_data);
			
			// import grids
			$grids = (isset($items->grids)) ? $items->grids : null;
			if ($grids) {
				
				foreach ($grids as $grid => $settings) {
					if (in_array($grid, $item_names) || $grid_demo) {
						
						$new_grid_id = $this->import_grid_settings($grid);
						foreach ($settings as $meta => $value) {
							if ($meta != 'the_grid_name') {
								add_post_meta($new_grid_id, $meta, $value);
							}
						}
						
					}
				}
				
			}
			
			// import skins
			$skins = (isset($items->skins)) ? $items->skins : null;
			if ($skins) {
				foreach ($skins as $skin => $settings) {
					if (in_array($skin, $item_names)) {
						
						try {
			
							The_Grid_Custom_Table::import_item_skin($settings);
								
						} catch (Exception $e) {
									
							// show error message if throw
							$response = $this->ajax_response(false, $e->getMessage(), null);
							return $response;
									
						}
						
					}
				}
			}
			
			// import elements
			$elements = (isset($items->elements)) ? $items->elements : null;
			if ($elements) {
				foreach ($elements as $element => $settings) {
					if (in_array($element, $item_names)) {
						
						try {
			
							The_Grid_Custom_Table::import_item_element($element, $settings);
								
						} catch (Exception $e) {
									
							// show error message if throw
							$response = $this->ajax_response(false, $e->getMessage(), null);
							return $response;
									
						}
						
					}
				}
			}
			
		} else {
		
			$response = $this->ajax_response(false, __( 'Sorry, an unexpected error occurred while importing', 'tg-text-domain' ));
			return $response;
			
		}
		
		if ($grid_demo) {
			$template = $this->get_grid_list();
		}
		
		$response = $this->ajax_response(true, null, $template);
		return $response;
		
	}
	
	/**
	* Import grid settings
	* @since 1.0.0
	*/
	public function import_grid_settings($post_title) {
		
		$new_grid = array(
			'menu_order'     => '',
			'comment_status' => '',
			'ping_status'    => 'default_ping_status',
			'post_author'    => '',
			'post_content'   => '',
			'post_excerpt'   => '',
			'post_parent'    => 0,
			'post_password'  => '',
			'post_status'    => 'publish',
			'post_title'     => $post_title,
			'post_name'      => $post_title,
			'post_type'      => $this->plugin_slug,
		);
		
		$post_exists = post_exists($post_title);
		
		if (post_type_exists($this->plugin_slug)) {
			
			$new_grid_id = wp_insert_post($new_grid);
			$post_exists = $this->check_grid_name($post_title, $new_grid_id);
			
			if ($post_exists) {
				$post_title = $post_title.' '.$new_grid_id;
				$new_post['ID'] = $new_grid_id;
				$new_post['post_title'] = $post_title;
				$new_post['post_name']  = $post_title;
				// Update the post into the database
				wp_update_post($new_post);
			}
			
			// update grid name
			add_post_meta($new_grid_id, 'the_grid_name', $post_title);
			return $new_grid_id;	
			
		}
		
	}
	
	/**
	* Read uploaded import .json file
	* @since 1.0.0
	*/
	public function import_read_file_callback() {
		
		$base = new The_Grid_Base();
		
		// retieve demo content file
		if (isset($this->ajax_data['demo']) && $this->ajax_data['demo']) {
			$json = $base->request_data(TG_PLUGIN_URL . '/backend/data/demo-content.json');
			$type = 'json';
		} else {
			$json = $base->request_data($_FILES['file']['tmp_name']);
			$path = pathinfo($_FILES['file']['name']);
			$type = $path['extension'];
		}
		
		// check if the uploaded file is .json
		if ($type != 'json') {
			$response = $this->ajax_response(false, __('Sorry, the file uploaded is not a .json file.', 'tg-text-domain'), null);
			return $response;
		}
		
		// check if the uploaded file can be json_decode
		if (!json_decode($json)) {
			$response = $this->ajax_response(false, __('Sorry, an error occurs, the file can\'t be read.', 'tg-text-domain'), null);
			return $response;
		}
		
		// decode file
		$obj = json_decode($json);
		
		// retirieve grid list
		$response = $this->get_import_item_list($obj);
		return $response;
		
	}
	
	/**
	* Read uploaded import .json file build grid list
	* @since 1.0.0
	*/
	public function get_import_item_list($obj) {
		
		// check if the .json file contains the right structure
		if (isset($obj->grids) || isset($obj->skins) || isset($obj->elements)) {
			
			$purchase_code = The_Grid_Base::get_purchase_code();
			
			$grids = $this->get_import_grid_list($obj);
			$skins = ($purchase_code) ? $this->get_import_skin_list($obj) : null;
			$elems = ($purchase_code) ? $this->get_import_element_list($obj) : null;
			
			// check if there is grids inside the file
			if (!$grids && !$skins && !$elems) {
				
				$response = $this->ajax_response(false, __( 'The file doesn\'t contain any data.', 'tg-text-domain'), null);
				return $response;
				
			}
			
			$list  = '<br><h3>'.__( 'The uploaded file have the following content :', 'tg-text-domain').'</h3>';
			$list .= '<p>'.__( 'Please select one or several item(s) to import.', 'tg-text-domain').'</p>';
			$list .= '<div class="tg-list-item-wrapper" data-multi-select="1">';
				$list .= '<ul class="tg-list-item-holder">';
				$list .= $grids;
				$list .= $skins;
				$list .= $elems;
				$list .= '</ul>';
			$list .= '</div>';
			$list .= '<span class="tg-list-item-add-all">'.__( 'Select all', 'tg-text-domain').'&nbsp;&nbsp;/&nbsp;&nbsp;</span>';
			$list .= '<span class="tg-list-item-clear">'.__( 'Clear selection', 'tg-text-domain').'</span>';
			$list .= '<br><br><div class="tg-button" data-action="tg_import_items" id="tg_import_items"><i class="tg-info-box-icon dashicons dashicons-download"></i>'. __( 'Import selected item(s)', 'tg-text-domain' ) .'</div>';
			$list .= '<strong class="tg-import-item-msg-error"></strong>';

			$response = $this->ajax_response(true, json_encode($obj), $list);
			return $response;
			
		} else {
			
			$response = $this->ajax_response(false,  __( 'The file uploaded doesn\'t meet the standard grid .json settings.', 'tg-text-domain'), null);
			return $response;
			
		}
		
	}
	
	/**
	* Get import file : grid list
	* @since 1.6.0
	*/
	public function get_import_grid_list($obj) {
		
		$list  = null;
		$grids = (isset($obj->grids)) ? $obj->grids : null;
		
		if ($grids) {
		
			foreach ($grids as $grid => $settings) {
						
				$grid_name   = $settings->the_grid_name;
				$grid_lang   = (isset($settings->the_grid_language)) ? $settings->the_grid_language : null;
						
				$WPML = new The_Grid_WPML();
				$WPML_exist = $WPML->WPML_exists();
				$WPML_flag_data = null;
				
				if($WPML_exist) {
					
					$grid_lang = (!$grid_lang) ? $WPML->WPML_default_lang() : $grid_lang;
					$WPML_languages = icl_get_languages('skip_missing=0');
					
					if (1 < count($WPML_languages)) {
						foreach ($WPML_languages as $l) {
							if ($l['language_code'] == $grid_lang) {
								$WPML_flag_data  = '<img src="'.esc_url($l['country_flag_url']).'">';
								break;
							}
						}
					}
					
				}
						
				$list .= '<li class="tg-list-item" data-type="grid" data-name="'.esc_attr($grid_name).'">';
					$list .= (!empty($WPML_flag_data)) ? '<span>'.$WPML_flag_data.'</span>' : null;
					$list .= '<span><b>'.esc_attr($grid_name).'</b></span>';
				$list .= '</li>';
						
			}
		
		}

		return $list;
	
	}
	
	/**
	* Get import file : skin list
	* @since 1.6.0
	*/
	public function get_import_skin_list($obj) {
		
		$list  = null;
		$skins = (isset($obj->skins)) ? $obj->skins : null;
		
		if ($skins) {
		
			foreach ($skins as $skin => $settings) {
				
				$list .= '<li class="tg-list-item" data-type="skin" data-name="'.esc_attr($skin).'">';
					$list .= '<span><b>'.esc_attr($skin).'</b></span>';
				$list .= '</li>';
			}
		
			return $list;
		
		}
		
	}
	
	/**
	* Get import file : element list
	* @since 1.6.0
	*/
	public function get_import_element_list($obj) {
		
		$list  = null;
		$elements = (isset($obj->elements)) ? $obj->elements : null;
		
		if ($elements) {
		
			foreach ($elements as $element => $settings) {
				
				$list .= '<li class="tg-list-item" data-type="elem" data-name="'.esc_attr($element).'">';
					$list .= '<span><b>'.esc_attr($element).'</b></span>';
				$list .= '</li>';
			}
		
		}
	
		return $list;
		
	}
	
	/**
	* Retrieve grid preview data
	* @since 1.0.0
	*/
	public function grid_preview_callback() {
		
		try {
			
			$class = new The_Grid_preview($this->ajax_data);
			$preview = $class->grid_preview_callback();
			$response = $this->ajax_response(true, null, $preview);
			
		} catch (Exception $e) {

			// show error message if throw
			$response = $this->ajax_response(false, $e->getMessage());
			
		}

		return $response;

	}
	
	/**
	* Retrieve preview skins selector
	* @since 1.0.0
	*/
	public function grid_skins_callback() {
		
		try {
		
			$skins = The_Grid_Skins_Preview($this->ajax_data['post_ID']);
			$response = $this->ajax_response(true, null, $skins);
		
		} catch (Exception $e) {

			// show error message if throw
			$response = $this->ajax_response(false, $e->getMessage());
			
		}

		return $response;

	}
	
	/**
	* Save item settings meta box value only
	* @since 1.0.0
	*/
	public function save_item_settings_callback() {
		
		$post_ID   = $this->ajax_data['post_ID'];
		$meta_data = json_decode(stripslashes($this->ajax_data['meta_data']), true);
		
		// add metadata
		foreach ($meta_data as $meta => $value) {
			update_post_meta($post_ID, $meta, $value );
		}
		
		$response = $this->ajax_response(true, null, null);
		return $response;
		
	}
	
	/**
	* Save Envato API Personal Token
	* @since 1.3.5
	*/
	public function save_envato_api_token() {
		
		$plugin_info  = null;
		$envato_token = $this->ajax_data['token'];
		
		if ($envato_token) {
				
			$API = new TG_Envato_API();
			$API->init_globals($envato_token);
			$plugins = $API->plugins();

			if (!is_array($plugins)) {
				
				// show error message if error from Envato API
				$defaut_msg = __( 'An unknown API error occurred from Envato', 'tg-text-domain');
				$error_msg  = !empty($plugins) && is_string($plugins) ? __( 'Envato API error:', 'tg-text-domain').'&nbsp;'.$plugins : $defaut_msg;
				$response   = $this->ajax_response(false, $error_msg, null);
				return $response;
						
			}
			
			foreach ($plugins as $key) {
				
				$id = $key['id'];
				
				if ($id == 13306812) {
					// save Plugin info
					$plugin_info = $this->get_plugin_info($key);
					update_option('the_grid_plugin_info', $plugin_info);
					// save Envato API personal token
					update_option('the_grid_envato_api_token', $envato_token);
					break;
				}
				
			}
		
		}
		
		if (empty($envato_token)) {
			
			$state   = false;
			$message = __( 'Please enter your Personal Token', 'tg-text-domain');
			update_option('the_grid_plugin_info', '');
			update_option('the_grid_envato_api_token', '');
			
		} else if (!$plugin_info) {
			
			$state   = false;
			$message = __( 'No purchase was found', 'tg-text-domain');
			update_option('the_grid_envato_api_token', '');
			update_option('the_grid_plugin_info', '');
			
		} else {
			
			$state   = true;
			$message = null;
			
		}
		
		ob_start();
		require_once('views/'.self::VIEW_GRID_INFO.'.php');
		$content = ob_get_clean();
		
		$response = $this->ajax_response($state, $message, $content);
		
		return $response;
		
	}
	
	/**
	* Check for new plugin update
	* @since 1.0.5
	*/
	public function check_for_update() {
		
		$plugin_info  =  null;
		$envato_token = get_option('the_grid_envato_api_token', '');
		
		if ($envato_token) {

			$API = new TG_Envato_API();
			$API->init_globals($envato_token);
			$plugins = (array) $API->plugins();
			
			foreach ($plugins as $key) {
				$id = isset($key['id']) ? $key['id'] : null;
				if ($id == 13306812) {
					// save Plugin info
					$plugin_info = $this->get_plugin_info($key);
					update_option('the_grid_plugin_info', $plugin_info);
					break;
				}
			}
		
		}
		
		if (!empty($plugin_info) && isset($plugin_info['version']) && version_compare($plugin_info['version'], TG_VERSION) >  0) {
			
			ob_start();
			require_once('views/'.self::VIEW_GRID_INFO.'.php');
			$content = ob_get_clean();
			$response = $this->ajax_response(true, __( 'A new update is available', 'tg-text-domain'), $content);
			
		} else {
			
			$response = $this->ajax_response(false, __( 'No update available', 'tg-text-domain'), null);
			
		}
		
		return $response;
		
	}
	
	/**
	* Get plugin infor from Envato API
	* @since 1.3.5
	*/
	public function get_plugin_info($key) {
		
		return array(
			'id'              => $key['id'],
			'slug'            => 'the-grid/the-grid.php',
			'name'            => $key['name'],
			'author'          => $key['author'],
			'version'         => $key['version'],
			'description'     => $key['description'],
			'content'         => $key['content'],
			'url'             => $key['url'],
			'author_url'      => $key['author_url'],
			'license'         => $key['license'],
			'updated_at'      => $key['updated_at'],
			'purchase_code'   => $key['purchase_code'],
			'supported_until' => $key['supported_until'],
			'thumbnail_url'   => $key['thumbnail_url'],
			'landscape_url'   => $key['landscape_url'],
			'requires'        => $key['requires'],
			'tested'          => $key['tested'],
			'number_of_sales' => $key['number_of_sales'],
			'rating'          => $key['rating'],
		);
		
	}

	/**
	* Save item skin
	* @since 1.6.0
	*/
	public function save_skin() {
		
		$purchase_code = The_Grid_Base::get_purchase_code();
		
		if (!$purchase_code) {
			
			$response = $this->ajax_response(false, __( 'You must activate The Grid to save a skin', 'tg-text-domain'), null);
			return $response;
			
		}
		
		try {
			
			$generator_class = new The_Grid_Skin_Generator();
			$skin_data = $generator_class->generate_skin(stripslashes($this->ajax_data['settings']));
			$skin_id   = isset($this->ajax_data['id']) ? $this->ajax_data['id'] : 0;
			$response  = The_Grid_Custom_Table::save_item_skin($skin_data, $skin_id);
				
		} catch (Exception $e) {
					
			// show error message if throw
			$response = $this->ajax_response(false, $e->getMessage(), null);
			return $response;
					
		}
		
		$response = $this->ajax_response(true, $response, null);
		return $response;	

	}
	
	/**
	* Delete item skin
	* @since 1.6.0
	*/
	public function delete_skin() {
		
		$skin_id = $this->ajax_data['id'];
		
		try {
			
			$response = The_Grid_Custom_Table::delete_item_skin($skin_id);
				
		} catch (Exception $e) {
					
			// show error message if throw
			$response = $this->ajax_response(false, $e->getMessage(), null);
			return $response;
					
		}
			
		ob_start();
		require_once('views/'.self::VIEW_SKIN_OVERVIEW.'.php');
		$template = ob_get_clean();
			
		$response = $this->ajax_response(true, '', $template);

		return $response;

	}
	
	/**
	* Clone item skin
	* @since 1.6.0
	*/
	public function clone_skin() {
		
		$skin_id = $this->ajax_data['id'];
		
		try {
			
			$response = The_Grid_Custom_Table::clone_item_skin($skin_id);
				
		} catch (Exception $e) {
					
			// show error message if throw
			$response = $this->ajax_response(false, $e->getMessage(), null);
			return $response;
					
		}
			
		ob_start();
		require_once('views/'.self::VIEW_SKIN_OVERVIEW.'.php');
		$template = ob_get_clean();
			
		$response = $this->ajax_response(true, '', $template);
		
		return $response;
				
	}
	
	/**
	* Import demo skins
	* @since 1.6.0
	*/
	public function import_demo_skins() {
		
		$base  = new The_Grid_Base();

		$skins = $base->request_data(TG_PLUGIN_PATH.'/backend/data/custom-skins.json');
		$skins = json_decode($skins, true);
		$skins = (isset($skins['skins'])) ? $skins['skins'] : null;
		
		if ($skins) {
			
			foreach ($skins as $skin => $settings) {
		
				try {
			
					The_Grid_Custom_Table::import_item_skin($settings);
								
				} catch (Exception $e) {
									
					// show error message if throw
					$response = $this->ajax_response(false, $e->getMessage(), null);
					return $response;
									
				}
				
			}
			
		} else {
			
			$response = $this->ajax_response(false, __( 'Sorry, an unexpected error occured while importing skins', 'tg-text-domain'), null);
			return $response;
		
		}

		ob_start();
		require_once('views/'.self::VIEW_SKIN_OVERVIEW.'.php');
		$template = ob_get_clean();
		
		$response = $this->ajax_response(true, '', $template);
		return $response;
	
	}
	
	/**
	* Download skin file .css/.php (for developer, include in theme)
	* @since 1.6.0
	*/
	public function skin_download_callback() {

		if (isset($_POST['tg_export_skin']) && !empty($_POST['tg_export_skin'])) {
			
			$skin_data = null;

			try {
			
				$generator_class = new The_Grid_Skin_Generator();
				$skin_data = $generator_class->generate_skin(stripslashes($_POST['tg_export_skin']));
					
			} catch (Exception $e) {
				
				global $tg_download_skin_error;
						
				// show error message if throw
				$tg_download_skin_error = '<div class="error notice is-dismissible" style="margin: 0;">';
            		$tg_download_skin_error .= '<p><strong>'. $e->getMessage() .'</strong></p>';
        		$tg_download_skin_error .= '</div>';
				
				return false;
						
			}
			
			if ($skin_data && is_array($skin_data)) {
				
				$skin_slug = (isset($skin_data['slug'])) ? $skin_data['slug'] : 'unknown';
				$skin_css  = (isset($skin_data['css_file'])) ? $skin_data['css_file']  : 'Sorry, an error occurs';
				$skin_php  = (isset($skin_data['php_file'])) ? $skin_data['php_file']  : 'Sorry, an error occurs';
					
				$archiveName = $skin_slug.'.zip';
				
				$upload_dir = wp_upload_dir();
				$upload_dir = $upload_dir['basedir'];
				
				$file = $upload_dir . $archiveName;
				
				/*** get google font-face otherwise css @import will not work (not top of file) ***/
				$font_face = null;
				if (isset($skin_data['styles']) && !empty($skin_data['styles'])) {
					
					$base = new The_Grid_Base();
					$styles = json_decode($skin_data['styles'], true);
					
					if (isset($styles['font']) && $styles['font']) {
						
						$font_url = $base->get_google_fonts(json_decode($styles['font'], true));
						if ($font_url) {
							
							$proxy = (is_ssl()) ? 'https:' : 'http:';
							$response = wp_remote_get($proxy.$font_url);
							
							if (!is_wp_error($response)) {
								
								$data = wp_remote_retrieve_body($response);
								if (!is_wp_error($data)) {
									$font_face .= '/*** Google Fonts ('.$proxy.$font_url.') ***/'. "\r\n";
									$font_face .= $data. "\r\n";
									$font_face .= '/*** Skin css ***/'. "\r\n";
								}
								
							}
							
						}
						
					}
					
				}
				
				ob_start();
				
				$zip = new ZipArchive();
				
				if($zip->open($file, ZipArchive::CREATE && ZipArchive::OVERWRITE) === true) {
					$zip->addFromString($skin_slug.'/'.$skin_slug.'.css', $font_face.rtrim($skin_css));
					$zip->addFromString($skin_slug.'/'.$skin_slug.'.php', $skin_php);
					$zip->close();
				}
				
				header('Content-Type: application/download');
				header("Content-Disposition: attachment; filename=$archiveName");	
				header("Pragma: no-cache");
				header("Expires: 0");
				
				@readfile($file);
				
				@unlink($file);
				
				ob_end_flush();
					
				exit();
			
			}
		
		}

	}
	
	/**
	* Get item elements
	* @since 1.6.0
	*/
	public function get_elements() {
		
		
		$base = new The_Grid_Base();
		$generator = new The_Grid_Skin_Generator();

		// get native elements
		require TG_PLUGIN_PATH .'includes/item-elements/elements.php';
		$elements = $base->get_item_element($elements);
		
		// store native elements data
		$native_elements   = (isset($elements['markup'])) ? $elements['markup'] : null;
		$elements_styles   = (isset($elements['styles'])) ? $elements['styles'] : null;
		$elements_settings = (isset($elements['settings'])) ? $elements['settings'] : array();

		if (empty($native_elements)) {
			$native_elements .= '<div class="tg-no-default-element">';
				$native_elements .=  __( 'Sorry, no default elements was found!', 'tg-text-domain' );
			$native_elements .=  '</div>';
		}

		// get custom elements
		$elements = The_Grid_Custom_Table::get_elements();
		$elements = $base->get_item_element($elements, true);	
		
		// store custom elements data
		$custom_elements   = (isset($elements['markup'])) ? $elements['markup'] : null;
		$elements_styles  .= (isset($elements['styles'])) ? $elements['styles'] : $elements_styles;
		$elements_settings = (isset($elements['settings'])) ? array_merge($elements_settings, $elements['settings']) : $elements_settings;
			
		$display = ($custom_elements) ? ' style="display:none"' : null;
		$custom_elements .= '<div class="tg-no-custom-element"'.$display.'>';
			$custom_elements .=  __( 'Your don\'t have any custom elements yet!', 'tg-text-domain' );
			$custom_elements .= '<p>'.__( 'To add a custom element you can save any element as template when editing.', 'tg-text-domain' ).'</p>';
		$custom_elements .=  '</div>';
		
		$data = array(
			'styles'          => $elements_styles,
			'settings'        => $elements_settings,
			'native_elements' => $native_elements,
			'custom_elements' => $custom_elements	
		);

		$response = $this->ajax_response(true, '', $data);
		return $response;
	
	}

	/**
	* Save item element
	* @since 1.6.0
	*/
	public function save_element() {
		
		$overwrite    = (bool) $this->ajax_data['overwrite'];
		$element_name = $this->ajax_data['element_name'];
		$element_slug = sanitize_title($element_name);
		$element_slug = sanitize_html_class($element_slug);
		$element_settings = $this->ajax_data['element_settings'];

		if (empty($element_name)) {
			
			$response = $this->ajax_response(false, __( 'This element name is empty', 'tg-text-domain'), null);
			return $response;
			
		}
		
		if (strpos($element_slug, 'tgdef-element-') !== false) {
			
			$response = $this->ajax_response(false, __( 'Choosen name is reserved for default Elements. Please choose a different name', 'tg-text-domain'), null);
			return $response;
		
		}
		
		if (!empty($element_settings)) {
			
			global $wpdb;
		
			$table_name = $wpdb->prefix . The_Grid_Custom_Table::TABLE_ELEMENTS;
			
			$element_exist = $wpdb->get_row($wpdb->prepare("SELECT id FROM $table_name WHERE name = %s", $element_name), ARRAY_A);
			$element_id = (isset($element_exist['id']) && $element_exist['id']) ? $element_exist['id'] : 0;

			if ($element_id && !$overwrite) {
				
				$response = $this->ajax_response(true, __( 'This element name alread exists!', 'tg-text-domain'), 'exists');
				return $response;
				
			}

			// put content in new element .json file
			$element_data = array(
				'id'       => (int) $element_id,
				'name'     => $element_name,
				'slug'     => $element_slug,
				'settings' => json_encode($element_settings)
			);

			try {
			
				$element_id = The_Grid_Custom_Table::save_item_element($element_data, $element_id);
					
			} catch (Exception $e) {
						
				// show error message if throw
				$response = $this->ajax_response(false, $e->getMessage(), null);
				return $response;
						
			}
			
			$base = new The_Grid_Base();
			$element_data['id'] = $element_id;
			$element = $base->get_item_element(array($element_slug => $element_data), true, true);
			
			if (is_array($element) && isset($element['markup']) && isset($element['styles'])) {
				
				$data = array(
					'slug'     => $element_slug,
					'markup'   => $element['markup'],
					'styles'   => $element['styles']
				);
				
			} else {
				
				$response = $this->ajax_response(false, __( 'Sorry, an unexpected error occured while saving this element', 'tg-text-domain'), null);
				return $response;
				
			}
						
			$response = $this->ajax_response(true, '', $data);
			return $response;
		
		} else {
			
			$response = $this->ajax_response(false, __( 'This element doesn\'t have any settings', 'tg-text-domain'), null);
			return $response;
		
		}	
	
	}
	
	
	/**
	* Delete item element
	* @since 1.6.0
	*/
	public function delete_element() {
		
		global $wp_filesystem;
		
		$element_id = $this->ajax_data['id'];

		if (isset($element_id)) {
			
			try {
			
				$response = The_Grid_Custom_Table::delete_item_element($element_id);	
					
			} catch (Exception $e) {
						
				// show error message if throw
				$response = $this->ajax_response(false, $e->getMessage(), null);
				return $response;
						
			}		
			
		} else {
			
			$response = $this->ajax_response(false, __( 'Sorry, an unexpected error occured while deleting this element', 'tg-text-domain'), null);
			return $response;
			
		}

		$response = $this->ajax_response(true, '', null);
		return $response;
	
	}
	
	/**
	* Get flickr Photoset
	* @since 2.0.5
	*/
	public function get_flickr_photosets() {
		
		$user_url = $this->ajax_data['user_url'];
		
		try {
			
			$flickr_class = new The_Grid_Flickr(array(
				'flickr_user_url' => $user_url,	
			));
			
			$photosets = $flickr_class->get_photoset_id();
					
		} catch (Exception $e) {
			
			// show error message if throw
			$response = $this->ajax_response(false, $e->getMessage(), null);
			return $response;
			
		}
		
		if (isset($photosets->photosets->photoset) && !empty($photosets->photosets->photoset)) {
			
			$photoset_select = '<div class="tomb-select-holder tg_flickr_get_photosets_list" data-noresult="'.__('No results found', 'tomb-text-domain').'" data-clear="" style="width:220px">';
			
				$photoset_select .= '<div class="tomb-select-fake">';
					$photoset_select .= '<span class="tomb-select-value"></span>';
					$photoset_select .= '<span class="tomb-select-placeholder">'.__( 'Select a Photoset', 'tg-text-domain').'</span>';
					$photoset_select .= '<span class="tomb-select-arrow"><i></i></span>';
				$photoset_select .= '</div>';	
				
				$photoset_select .= '<select class="tomb-select" name="" data-clear="">';
				foreach($photosets->photosets->photoset as $photoset){
					$photoset->photos = !empty($photoset->photos) ? $photoset->photos : 0;
					$photoset_select .= '<option value="'.$photoset->id.'">'.$photoset->title->_content.' ('.$photoset->photos.' '.__( 'photos', 'tg-text-domain').')</option>';
				}
				$photoset_select .= '</select>';
			
			$photoset_select .= '</div>';
			
			$response = $this->ajax_response(true, '', $photoset_select);
			return $response;
		
		} else {
		
			$response = $this->ajax_response(false, __( 'Sorry, no photoset was found', 'tg-text-domain'), null);
			return $response;
		
		}
		
	}
		
	/**
	* Localize strings for message box
	* @since 1.0.0
	*/
	public function localize_strings() {
		
		$base = new The_Grid_Base();
		
		$updating_err_msg = __( 'Sorry, an error occurs while updating...', 'tg-text-domain');
		
		$this->ajax_msg = array(
			'default_skin' => array(
				'grid'    => $base->default_skin('grid'),
				'masonry' => $base->default_skin('masonry')
			),
			'box_icons' => array(
				'before'  => '<i class="tg-info-box-icon dashicons dashicons-admin-generic"></i>',
				'success' => '<i class="tg-info-box-icon dashicons dashicons-yes"></i>',
				'error'   => '<i class="tg-info-box-icon dashicons dashicons-no-alt"></i>'
			),
			'box_messages' => array(
				'tg_favorite'  => array(
					'before'  => __( 'Updating favorite grid', 'tg-text-domain').'...',
					'success' => __( 'Favorite grid updated', 'tg-text-domain'),
					'error'   => $updating_err_msg
				),
				'tg_per_page' => array(
					'before'  => __( 'Updating grid list', 'tg-text-domain').'...',
					'success' => __( 'Grid list updated', 'tg-text-domain'),
					'error'   => $updating_err_msg
				),
				'tg_page_nb' => array(
					'before'  => __( 'Loading page', 'tg-text-domain').'...',
					'success' => __( 'Page loaded', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while loading...', 'tg-text-domain')
				),
				'tg_order'   => array(
					'before'  => __( 'Ordering grid list', 'tg-text-domain').'...',
					'success' => __( 'Grid list ordered', 'tg-text-domain'),
					'error'   =>  __( 'Sorry, an error occurs while ordering...', 'tg-text-domain')
				),
				'tg_save'    => array(
					'before'  => __( 'Saving grid settings', 'tg-text-domain').'...',
					'success' => __( 'Grid settings saved', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while saving...', 'tg-text-domain')
				),
				'tg_delete'  => array(
					'before'  => __( 'Deleting grid', 'tg-text-domain').'...',
					'success' => __( 'Grid deleted', 'tg-text-domain'),
					'message' => __( 'Are you sure you want to delete this grid?', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while deleting...', 'tg-text-domain')
				),
				'tg_clone'   => array(
					'before'  => __( 'Cloning grid', 'tg-text-domain').'...',
					'success' => __( 'Grid cloned', 'tg-text-domain'),
					'message' => __( 'Are you sure you want to clone this grid?', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while clonning...', 'tg-text-domain')
				),
				'tg_save_settings' => array(
					'before'  => __( 'Saving global settings', 'tg-text-domain' ),
					'success' => __( 'Global settings Saved', 'tg-text-domain' ),
					'error'   => __( 'Sorry, an error occurs while saving settings...', 'tg-text-domain')
				),
				'tg_reset_settings' => array(
					'before'  => __( 'Resetting global settings', 'tg-text-domain' ),
					'success' => __( 'Global settings resetted', 'tg-text-domain' ),
					'message' => __( 'Are you sure you want to reset the global settings?', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurred while resetting settings', 'tg-text-domain' )
				),
				'tg_delete_cache'  => array(
					'before'  => __( 'Please wait...', 'tg-text-domain'),
					'success' => __( 'Done!', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while clearing cache...', 'tg-text-domain')
				),
				'tg_export_items'  => array(
					'before'  => __( 'Exporting item(s) data', 'tg-text-domain'),
					'success' => __( 'Item(s) exported', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while exporting...', 'tg-text-domain'),
					'empty'   => __( 'Please select item(s)', 'tg-text-domain')
				),
				'tg_read_import_file'  => array(
					'before'  => __( 'Fetching item(s) data', 'tg-text-domain'),
					'success' => __( 'Item(s) was correctly fetched', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while reading file...', 'tg-text-domain'),
					'empty'   => __( 'Please select a file to import.', 'tg-text-domain')
				),
				'tg_import_items'  => array(
					'before'  => __( 'Importing item(s)', 'tg-text-domain'),
					'success' => __( 'Item(s) imported', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while importing...', 'tg-text-domain'),
					'empty'   => __( 'Please select item(s) to import.', 'tg-text-domain')
				),
				'tg_grid_preview' => array(
					'before'  => __( 'Please wait, fetching grid settings...', 'tg-text-domain'),
					'success' => '',
					'error'   => __( 'Sorry, an error occurs while retieving data...', 'tg-text-domain'),
				),
				'tg_skin_selector' => array(
					'before'  => __( 'Please wait, loading skins...', 'tg-text-domain'),
					'success' => '',
					'error'   => __( 'Sorry, an error occurs while retieving skins...', 'tg-text-domain'),
					'empty'   => __( 'Sorry, no skin was found.', 'tg-text-domain')
				),
				'tg_save_item_settings' => array(
					'before'  => __( 'Please wait, saving settings...', 'tg-text-domain'),
					'success' => __( 'Item settings saved', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while saving...', 'tg-text-domain')
				),
				'tg_save_envato_api_token' => array(
					'before'  => __( 'Please wait...', 'tg-text-domain'),
					'success' => __( 'The Grid was correctly registered', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while registering The Grid...', 'tg-text-domain')
				),
				'tg_check_for_update' => array(
					'before'  => __( 'Please wait...', 'tg-text-domain'),
					'success' => __( 'A new update is available', 'tg-text-domain'),
					'error'   => __( 'No new update available', 'tg-text-domain')
				),
				'tg_update_plugin' => array(
					'before'  => __( 'Please wait, updating...', 'tg-text-domain'),
					'success' => __( 'Plugin updated', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while updating', 'tg-text-domain')
				),
				'tg_import_demo_skins' => array(
					'before'  => __( 'Please wait, importing skins...', 'tg-text-domain'),
					'success' => __( 'Demo Skins imported', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while importing', 'tg-text-domain')
				),
				'tg_save_skin' => array(
					'before'  => __( 'Please wait, saving skin...', 'tg-text-domain'),
					'success' => __( 'Skin saved', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while saving', 'tg-text-domain')
				),
				'tg_download_skin' => array(
					'before'  => __( 'Please wait, building skin...', 'tg-text-domain'),
					'success' => __( 'Skin built', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while building the skin', 'tg-text-domain')
				),
				'tg_delete_skin' => array(
					'before'  => __( 'Please wait, deleting skin...', 'tg-text-domain'),
					'success' => __( 'Skin deleted', 'tg-text-domain'),
					'message' => __( 'Are you sure you want to delete this skin?', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while deleting', 'tg-text-domain')
				),
				'tg_clone_skin' => array(
					'before'  => __( 'Please wait, cloning skin...', 'tg-text-domain'),
					'success' => __( 'Skin cloned', 'tg-text-domain'),
					'message' => __( 'Are you sure you want to clone this skin?', 'tg-text-domain'),
					'error'   => __( 'Sorry, an error occurs while cloning', 'tg-text-domain')
				),
				'tg_get_elements' => array(
					'before'   => sprintf(__( 'Please wait,%s retrieving elements...', 'tg-text-domain'), '<br>'),
					'success'  => '',
					'error'    => __( 'Sorry, an error occurs while retrieving elements', 'tg-text-domain')
				),
				'tg_save_element' => array(
					'before'   => __( 'Please wait, saving element...', 'tg-text-domain'),
					'success'  => __( 'Element saved', 'tg-text-domain'),
					'message'  => __( 'Please enter your element name. By entering an existing element name, you will be able to overwrite its styles.', 'tg-text-domain'),
					'message2' => __( 'This element name already exists. Do you want to overwrite it?', 'tg-text-domain'),
					'error'    => __( 'Sorry, an error occurs while saving', 'tg-text-domain')
				),
				'tg_delete_element' => array(
					'before'   => __( 'Please wait, deleting element...', 'tg-text-domain'),
					'success'  => __( 'Element deleted', 'tg-text-domain'),
					'message'  => __( 'Are you sure you want to delete this element?', 'tg-text-domain'),
					'error'    => __( 'Sorry, an error occurs while deleting', 'tg-text-domain')
				),
				'tg_get_flickr_photosets' => array(
					'before'   => __( 'Please wait, fetching photosets list...', 'tg-text-domain'),
					'success'  => '',
					'message'  => '',
					'error'    => __( 'Sorry, an error occurs while fetching photosets', 'tg-text-domain')
				)
			)
		);

	}
	
	/**
	* Declare ajaxurl and admin nonce for backend
	* @since 1.0.0
	*/
	public function admin_nonce() {
		
		return array(
			'url'   => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('tg_admin_nonce')
		);
		
	}
	
	/**
	* Enqueue admin scripts
	* @since 1.0.0
	*/
	public function enqueue_admin_scripts() {
		
		$screen = get_current_screen();
		
		// enqueue only in grid panel
		if (strpos($screen->id, $this->plugin_slug) !== false) {
			
			// retrieve all ajax string to localize
			$this->localize_strings();
			// merge nonce to translatable strings
			$strings = array_merge($this->admin_nonce(), $this->ajax_msg);
			// register and localize script for ajax methods
			wp_enqueue_script($this->plugin_slug . '-admin-ajax', TG_PLUGIN_URL . 'backend/assets/js/admin-ajax.js', array('jquery'), TG_VERSION, true );
			wp_localize_script($this->plugin_slug . '-admin-ajax', 'tg_admin_global_var', $strings);
			
		}
	}
	
}

new The_Grid_Admin_Ajax;