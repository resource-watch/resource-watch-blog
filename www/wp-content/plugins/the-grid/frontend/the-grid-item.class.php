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

class The_Grid_Item {
	
	/**
	* Skin style
	*
	* @since 1.0.0
	* @access public
	*
	* @var string
	*/
	protected $skin_style;
	
	/**
	* skin elements
	*
	* @since 1.6.0
	* @access protected
	*
	* @var array
	*/
	protected $skin_elements = array();
	
	/**
	* skin elements
	*
	* @since 1.6.0
	* @access protected
	*
	* @var string
	*/
	protected $skin_content;
	
	/**
	* skin slugs
	*
	* @since 1.6.0
	* @access protected
	*
	* @var array
	*/
	protected $skin_slugs = array();
	
	/**
	* skin element class
	*
	* @since 1.6.0
	* @access protected
	*
	* @var array
	*/
	protected $tg_el = array();
	
	/**
	* The singleton instance
	*
	* @since 1.6.0
	* @access private
	*
	* @var objet
	*/
	static private $instance = null;
	
	/**
	* To initialize a The_Grid_Item object
	* @since 1.6.0
	*/
	static public function getInstance() {
		
		if(self::$instance == null) {
			self::$instance = new self;
		}
		
		return self::$instance;
		
	}
	
	/**
	* Build skin
	* @since 1.6.0
	*/
	public function build_skin($slug, $skin_style) {
		
		// assign skin style (from loop)
		$this->skin_style = $skin_style;
		// reset content if item already proceeded
		$this->skin_content = null;
		// retrive skin settings from DB
		$this->get_skin_settings($slug);
		// generate skin markup
		$this->generate_skin();
		
		// return skin markup in the custom loop
		return $this->skin_content;
	
	}
	
	/**
	* Get skin settings
	* @since 1.6.0
	*/
	public function get_skin_settings($slug) {
		
		// if this skin was not proceed before
		if (!isset($this->skin_slugs[$slug])) {
			
			// fetch skin data from custom table
			$this->skin_elements = The_Grid_Custom_Table::get_skin_elements($slug);
			$this->skin_elements = json_decode($this->skin_elements, true);

			// store skin settings for next time
			$this->skin_slugs[$slug] = $this->skin_elements;
		
		// if this skin was already proceeded then get directly the settings
		// (prevent additional query from custom table)
		} else {
			
			$this->skin_elements = $this->skin_slugs[$slug];
			
		}
	
	}
	
	/**
	* Generate skin logic
	* @since 1.6.0
	*/
	public function generate_skin() {
		
		// prepare main instance of the grid element
		$this->tg_el = The_Grid_Elements();
		
		// proceed to each layers/areas
		$this->content_holder('top');
		$this->media_holder();
		$this->content_holder('bottom');
		
	}
	
	/**
	* Skin content holder (top & button)
	* @since 1.6.0
	*/
	public function content_holder($position) {
		
		$elements = (isset($this->skin_elements[$position.'_content_holder'])) ? $this->skin_elements[$position.'_content_holder'] : null;
		
		// if top content is available
		if ($elements) {
			
			$elements = $this->get_element($elements);
			
			if ($elements) {
				
				// open tag of content wrapper
				$this->skin_content .= $this->tg_el->get_content_wrapper_start('', $position);
					
					// get each element in content
					$this->skin_content .= $elements;
					// add clear HTML div (prevent css issue)
					$this->skin_content .= $this->tg_el->get_content_clear();
				
				// close tag of content wrapper
				$this->skin_content .= $this->tg_el->get_content_wrapper_end();
			
			}

		}
	
	}
	
	/**
	* Skin media holder wrapper
	* @since 1.6.0
	*/
	public function media_holder() {
		
		// check if media_content is enable
		$media_content = (isset($this->skin_elements['media_content']) && $this->skin_elements['media_content']) ? $this->skin_elements['media_content'] : null;
		
		// if media content disable return directly
		if (!$media_content) {
			return;
		}
		
		// get the media content
		$media  = $this->tg_el->get_media();

		// if there is a media
		if ($media || $this->skin_style != 'masonry') {
		
			// open tag of media holder
			$this->skin_content .= $this->tg_el->get_media_wrapper_start();
			
			// media HTML content (image, gallery, audi, video)
			$this->skin_content .= $media;
			
			// get the item image
			$image  = $this->tg_el->get_attachment_url();
			$format = $this->tg_el->get_item_format();
			
			// if there is an image or it's a gallery/video then allow elements in media holder
			if ($image || in_array($format, array('gallery', 'video')) || $this->skin_style != 'masonry') {
				
				// media content inner start
				$this->skin_content .= $this->tg_el->get_media_content_start();

				$areas = array(
					'media_holder_top',
					'media_holder_center',
					'media_holder_bottom'
				);
				
				// prepare content
				$content = null;
				
				// loop through each media content holder
				foreach ($areas as $area) {
					
					// generate each available elements
					$elements = (isset($this->skin_elements[$area])) ? $this->skin_elements[$area] : array();
					$content .= is_string($area) ? $this->{$area}($elements) : null;
				
				}
				
				// process absolute holder elements
				$content .= $this->media_holder_absolute();
				
				// generate the overlay
				if (isset($this->skin_elements['media_overlay'])) {
					$this->skin_content .= $this->tg_el->get_overlay();
				}
				
				// get action under content
				$layer_action = (isset($this->skin_elements['media_holder_before'])) ? $this->skin_elements['media_holder_before'] : array();
				$this->skin_content .= ($layer_action) ? $this->get_element($layer_action) : null;
				
				// assign media content to the skin
				$this->skin_content .= $content;
				
				// get action above content
				$layer_action = (isset($this->skin_elements['media_holder_after'])) ? $this->skin_elements['media_holder_after'] : array();
				$this->skin_content .= ($layer_action) ? $this->get_element($layer_action) : null;
				
				// media content inner end
				$this->skin_content .= $this->tg_el->get_media_content_end();

			}
			
			// close tag of media holder
			$this->skin_content .= $this->tg_el->get_media_wrapper_end();
		
		}

	}
	
	/**
	* Skin media holder top
	* @since 1.6.0
	*/
	public function media_holder_top($elements) {
		
		$content  = null;
		$elements = $this->get_element($elements);
			
		if ($elements) {
		
			$content .= $this->tg_el->get_top_wrapper_start();

				$content .= $elements;
					
			$content .= $this->tg_el->get_top_wrapper_end();
		
		}
		
		return $content;
	
	}
	
	/**
	* Skin media holder center
	* @since 1.6.0
	*/
	public function media_holder_center($elements) {
		
		$content  = null;
		$elements = $this->get_element($elements);
			
		if ($elements) {
		
			$content .= $this->tg_el->get_center_wrapper_start();
				
				$content .= $elements;
					
				$content .= $this->tg_el->get_content_clear();
					
			$content .= $this->tg_el->get_center_wrapper_end();
		
		}
		
		return $content;
	
	}
	
	/**
	* Skin media holder bottom
	* @since 1.6.0
	*/
	public function media_holder_bottom($elements) {
		
		$content  = null;
		$elements = $this->get_element($elements);
			
		if ($elements) {
		
			$content .= $this->tg_el->get_bottom_wrapper_start();

				$content .= $elements;
					
			$content .= $this->tg_el->get_bottom_wrapper_end();
		
		}
		
		return $content;
	
	}
	
	/**
	* Skin media holder full (for absolute elements)
	* @since 1.6.0
	*/
	public function media_holder_absolute() {

		$content  = null;
		$elements = (isset($this->skin_elements['media_holder'])) ? $this->skin_elements['media_holder'] : null;
		
		if ($elements) {
		
			$content = $this->get_element($elements);
		
		}
		
		return ($content) ? $content : null;
	
	}
	
	/**
	* Skin element
	* @since 1.6.0
	*/
	public function get_element($elements) {
		
		$content = null;
		
		if (is_array($elements)) {
			
			// loop through each element available in the current layer/area
			foreach ($elements as $element) {
				
				// if the element is from The Grid API => generate option (array)
				if (isset($element['type']) && isset($element['content']) && isset($element['element']) && $element['type'] == 'function') {
					$content .= (isset($element['args']) && is_string($element['content'])) ? $this->tg_el->{$element['content']}($element['args'], esc_attr($element['element'])) : null;
				} else {
				// if custom content directly output
					$content .= $element['content'];
				}
		
			}
		
		}
		
		return $content;
	
	}
	
}

if (!function_exists('The_Grid_Item')) {
	
	/**
	* Tiny wrappers functions
	* @since 1.6.0
	*/
	function The_Grid_Item($slug, $skin_style) {
		
		$the_grid_item = The_Grid_Item::getInstance();	
		return $the_grid_item->build_skin($slug, $skin_style);
		
	}

}