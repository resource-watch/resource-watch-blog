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

class The_Grid_Source {
	
	/**
	* Grid Data
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	public $grid_data;	
	
	/**
	* Grid item
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	public $grid_items;	
	
	/**
	* Constructor
	* @since 1.0.0
	*/
	public function __construct($grid_data) {
		
		$this->grid_data = $grid_data;
		
	}
	
	/**
	* Get grid items
	* @since 1.0.0
	*/
	public function get_items() {
				
		$source_class = 'The_Grid_'.$this->grid_data['source_type'];
		
		if ($this->grid_data['source_type'] == 'nextgen' && !class_exists('nggdb')) {
			
			 // If nextgen source but NexGen Gallery plugin not activated
			$error_msg  = __( 'You must intall and activate NextGen Gallery Plugin in order to use it as source.', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}

		// check if class exist
		if (class_exists($source_class)) {
			
			try {
				
				// Retrieve items from corresponding source
				$source = new $source_class($this->grid_data);
				$this->grid_items = $source->get_grid_items();
				$this->grid_data  = $source->get_grid_data();
				return $this->grid_items;
				
			} catch (Exception $e) {
				
				// show error message if throw
				throw new Exception($e->getMessage());
				
			}

		} else {
			
			// If no content and no errors then trigger unknown error
			$error_msg  = __( 'Sorry, an error occurs...', 'tg-text-domain' );
			$error_msg .= '<br>';
			$error_msg .= __( 'Class name', 'tg-text-domain' );
			$error_msg .= ' "'.$source_class.'()" ';
			$error_msg .= __( 'does not exist.', 'tg-text-domain' );
			throw new Exception($error_msg);
				
		}
		
	}
	
	/**
	* Get grid data
	* @since 1.0.0
	*/
	public function get_data() {
		
		return $this->grid_data;
	
	}
	
}