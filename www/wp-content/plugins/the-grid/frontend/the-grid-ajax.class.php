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

class The_Grid_Ajax extends The_Grid {
	
	/**
	* Cloning disabled
	* @since 1.0.0
	*/
	private function __clone() {
	}
	
	/**
	* De-serialization disabled
	* @since 1.0.0
	*/
	private function __wakeup() {
	}
	
	/**
	* Construct for Ajax
	* @since 1.0.0
	*/
	public function __construct() {

		// Add action - ajax load more item in grid
		add_action('wp_ajax_the_grid_load_more', array($this, 'the_grid_load_more_items'));
		add_action('wp_ajax_nopriv_the_grid_load_more', array($this, 'the_grid_load_more_items'));
		
	}
	
	/**
	* Load more item with ajax (button or scroll)
	* @since 1.0.0
	*/
	public function the_grid_load_more_items() {
		
		$nonce  = $_POST['grid_nonce']; 
		$action = $_POST['action'];
		
		// check ajax nounce
		if (!wp_verify_nonce($nonce, $action) && is_user_logged_in()) {
			
			// build error response
			$response = array(
				'success' => false,			
				'message' => __('Loading Error', 'tg-text-domain'),
				'content' => null
			);
			
			// return response
			wp_send_json($response);				
			
		} else {
			
			global $tg_is_ajax, $tg_grid_preview;
			
			// set ajax mode
			$tg_is_ajax = true;
				
			// retrieve ajax data
			$grid_page = (isset($_POST['grid_page'])) ? $_POST['grid_page'] : die();
			$grid_name = (isset($_POST['grid_name']) && !empty($_POST['grid_name'])) ? $_POST['grid_name'] : die();
			$grid_data = (isset($_POST['grid_data']) && !empty($_POST['grid_data'])) ? $_POST['grid_data'] : null;
			
			$process_data = new The_Grid_Data($grid_name);

			// get grid options (back end if data and front end if not)
			if ($grid_data) {
				
				// set preview mode
				$tg_grid_preview = true;

				// retrieve all grid settings
				foreach ($grid_data as $data => $val) {
					$data = str_replace('the_grid_', '', $data);
					$grid_data[$data] = wp_unslash($val);
				}
				
				// normalize data in preview mode
				$this->grid_data = $process_data->normalize_data($grid_data);
				
			} else {
				
				// fetch data from grid name
				$this->grid_data = $process_data->get_data();
				
			}
			
			// get grid items
			$this->get_items();
			
			// build each item in custom loop
			$content = $this->get_item();

			// send json response
			$response = array(
				'success'   => true,				
				'message'   => __('Content correctly retrieved', 'tg-text-domain'),
				'content'   => $content,
				'ajax_data' => htmlspecialchars_decode($this->grid_data['ajax_data'])
			);
			
			// return success response
			wp_send_json($response);

		}

	}
	
	/**
	* Retrieve grid items
	* @since 1.0.0
	*/
	public function get_items() {
		
		try {
			
			// get grid items
			$source_class = new The_Grid_Source($this->grid_data);
			$this->grid_items = $source_class->get_items();
			$this->grid_data  = $source_class->get_data();
			
		} catch (Exception $e) {
			
			// show error message if throw
			$response = array(
				'success'   => false,				
				'message'   => html_entity_decode($e->getMessage()),
				'content'   => null,
				'ajax_data' => null
			);
			
			// return error response
			wp_send_json($response);
			
		}

	}
	
	/**
	* Retrieve grid items
	* @since 1.0.0
	*/
	public function get_item() {
		
		try {
			
			// loop through each item
			ob_start();
			The_Grid_Loop($this->grid_data, $this->grid_items);
			$content = ob_get_contents();
			ob_end_clean();
			
			return $content;
			
		} catch (Exception $e) {
			
			// build error response if throw
			$response = array(
				'success'   => false,				
				'message'   => html_entity_decode($e->getMessage()),
				'content'   => null,
				'ajax_data' => null
			);
			
			// return error response
			wp_send_json($response);
			
		}

	}
	
}

new The_Grid_Ajax();