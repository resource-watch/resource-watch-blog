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

class The_Grid {
	
	/**
	* Grid Name
	*
	* @since 1.0.0
	* @access public
	*
	* @var string
	*/
	public $grid_name;
		
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
	* Grid items
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	public $grid_items;
	
	/**
	* Timer Start
	*
	* @since 1.0.0
	* @access public
	*
	* @var integer
	*/
	protected $time_start;
	
	/**
	* Timer End
	*
	* @since 1.0.0
	* @access public
	*
	* @var integer
	*/
	protected $time_end;
	
	/**
	* Debug Mode
	*
	* @since 1.0.0
	* @access public
	*
	* @var integer
	*/
	protected $debug_mode;
	
	/**
	* The singleton instance
	*
	* @since 1.0.0
	* @access private
	*
	* @var objet
	*/
	static private $instance = null;
	
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
	* _construct disabled
	* @since 1.0.0
	*/
	public function __construct() {		
	}
	
	/**
	* To initialize a The_Grid object
	* @since 1.0.0
	*/
	static public function getInstance() {
		
		if(self::$instance == null) {
			self::$instance = new self;
		}
		
		return self::$instance;
		
	}
	
	/**
	* Check debug Mode
	* @since 1.0.0
	*/
	public function debug_mode() {
		
		$this->debug_mode = get_option('the_grid_debug', false);
		
		if ($this->debug_mode) {
			// start mesure render time
			$this->time_start();
		}

	}
	
	/**
	* Get time (start)
	* @since 1.0.0
	*/
	public function time_start() {
		
		$this->time_start = microtime(true);

	}
	
	/**
	* Get time (end)
	* @since 1.0.0
	*/
	public function time_end() {
		
		$this->time_end = microtime(true);

	}
	
	/**
	* Render the grid
	* @since 1.0.0
	*/
	public function render($name, $template) {
		
		// set grid name
		$this->name = $name;
		// set debug mode
		$this->debug_mode();
		// get grid data
		$this->get_data($template);
		// get grid items
		$this->get_items();
		// get grid styles
		$this->get_styles();
		// get grid layout
		$layout = $this->get_layout();
		
		// add plugin version
		$output  = $this->add_plugin_info();
		// add debug info
		$output .= $this->add_debug_info();
		// add cache info
		$output .= $this->add_cache_info();
		// add layout to output	
		$output .= $layout;	
			
		// return the grid
		if (!$template) {
			return $output;
		} else {
			echo $output;
		}

	}
	
	/**
	* Retrieve grid data
	* @since 1.0.0
	*/
	public function get_data($template) {
		
		try {
			
			// get grid data
			$data_class = new The_Grid_data($this->name, $template);
			$this->grid_data = $data_class->get_data();
			
		} catch (Exception $e) {
			
			// show error message if throw
			throw new Exception($e->getMessage());
			
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
			throw new Exception($e->getMessage());
			
		}

	}
	
	/**
	* Retrieve grid styles
	* @since 1.0.0
	*/
	public function get_styles() {
		
		try {
			
			// get grid styles
			$styles_class = new The_Grid_Styles($this->grid_data);
			$this->grid_data = $styles_class->styles_processing();
			
		} catch (Exception $e) {
			
			// show error message if throw
			throw new Exception($e->getMessage());
			
		}
		
	}
	
	/**
	* Retrieve grid layout
	* @since 1.0.0
	*/
	public function get_layout() {
		
		try {
			
			// retrive entire grid layout
			$layout_class = new The_Grid_Layout($this->grid_data, $this->grid_items);
			return $layout_class->output();
		
		} catch (Exception $e) {
			
			// show error message if throw
			throw new Exception($e->getMessage());
			
		}
	
	}
	
	/**
	* Add grid cache info
	* @since 1.0.0
	*/
	public function add_plugin_info() {
		
		return '<!-- The Grid Plugin Version '.TG_VERSION.' -->';	
		
	}
	
	/**
	* Add grid cache info
	* @since 1.0.0
	*/
	public function add_cache_info() {
		
		// check if a cache date is available
		$cache_date = isset($this->grid_data['cache_date']) ? $this->grid_data['cache_date'] : null;
		// add cache comment tag
		return $cache_date ? '<!-- The Grid Cache Enabled - Date: '.$cache_date.' -->' : null;
		
	}
	
	/**
	* Add debug info
	* @since 1.0.0
	*/
	public function add_debug_info() {
		
		if ($this->debug_mode) {
			
			// end mesure render time
			$this->time_end();
			// add cache comment tag
			return '<!-- The Grid Debug Mode Enabled - Execution Time: '.round($this->time_end - $this->time_start,5).'s for '.count($this->grid_items).' items -->';

			
		}
		
	}

}

if(!function_exists('The_Grid')) {
	
	/**
	* Tiny wrapper function
	* @since 1.0.0
	*/
	
	function The_Grid($name = '', $template = false) {
		
		try {
			
			// render the grid
			$the_grid = The_Grid::getInstance();
			return $the_grid->render($name, $template);
			
		} catch (Exception $e) {
			
			// display any error which occurred while building the grid
			$error_msg  = '<!-- The Grid Plugin Version '.TG_VERSION.' -->';
			$error_msg .= $the_grid->add_debug_info();
			$error_msg .= $the_grid->add_cache_info();
			$error_msg .= '<div class="tg-error-msg" data-grid-name="'.$name.'">';
				$error_msg .= wp_kses_post($e->getMessage());
			$error_msg .= '</div>';
			
			if (!$template) {
				return $error_msg;
			} else {
				echo $error_msg;
			}
			
		}
		
	}

}