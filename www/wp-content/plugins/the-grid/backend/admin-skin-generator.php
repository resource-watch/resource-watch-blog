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

class The_Grid_Skin_Generator {
	
	/**
	* The Grid base class
	*
	* @since 1.0.0
	* @access protected
	*
	* @var object
	*/
	protected $base;
	
	/**
	* skins settings
	*
	* @since 1.0.0
	* @access protected
	*
	* @var array
	*/
	protected $skins_settings;
	
	/**
	* skin slug/style
	*
	* @since 1.0.0
	* @access protected
	*
	* @var string
	*/
	protected $skin_slug;
	
	/**
	* skin data
	*
	* @since 1.0.0
	* @access protected
	*
	* @var array
	*/
	protected $skin_elements = array();
	
	/**
	* skin settings
	*
	* @since 1.0.0
	* @access protected
	*
	* @var array
	*/
	protected $skin_settings = array();
	
	/**
	* skin css
	*
	* @since 1.0.0
	* @access protected
	*
	* @var string
	*/
	protected $skin_css;
	
	/**
	* skin fonts
	*
	* @since 1.0.0
	* @access protected
	*
	* @var array
	*/
	protected $skin_fonts = array();
	
	/**
	* skin php
	*
	* @since 1.0.0
	* @access protected
	*
	* @var string
	*/
	protected $skin_php;
	
	/**
	* skin css file path
	*
	* @since 1.0.0
	* @access protected
	*
	* @var string
	*/
	protected $skin_css_file;
	
	/**
	* skin php file path
	*
	* @since 1.0.0
	* @access protected
	*
	* @var string
	*/
	protected $skin_php_file;
	
	/**
	* Google Font
	*
	* @since 1.0.0
	* @access protected
	*
	* @var array
	*/
	protected $google_font;
	
	/**
	* unvalid css rules
	*
	* @since 1.0.0
	* @access protected
	*
	* @var array
	*/
	protected $invalid_rules = array(
		'positions-unit',
		'display',
		'z-index',
		'float',
		'opacity',
		'width',
		'height',
		'width-unit',
		'height-unit',
		'margin-unit',
		'margin-top',
		'margin-right',
		'margin-bottom',
		'margin-left',
		'padding-unit',
		'padding-top',
		'padding-right',
		'padding-bottom',
		'padding-left',
		'border-unit',
		'border-style',
		'border-top',
		'border-right',
		'border-bottom',
		'border-left',
		'border-radius-unit',
		'border-top-left-radius',
		'border-top-right-radius',
		'border-bottom-right-radius',
		'border-bottom-left-radius',
		'box-shadow-unit',
		'box-shadow-color',
		'box-shadow-horizontal',
		'box-shadow-vertical',
		'box-shadow-blur',
		'box-shadow-size',
		'box-shadow-inset-unit',
		'box-shadow-inset-color',
		'box-shadow-inset-horizontal',
		'box-shadow-inset-vertical',
		'box-shadow-inset-blur',
		'box-shadow-inset-size',	
		'text-shadow-unit',
		'text-shadow-color',
		'text-shadow-horizontal',
		'text-shadow-vertical',
		'text-shadow-blur',
		'letter-spacing-unit',
		'word-spacing-unit',
		'background-color-important',
		'background-position-x-unit',
		'background-position-y-unit',
		'positions-from',
		'top','bottom','left','right',
		'line-height-unit',
		'font-subset',
		'font-size-unit',
		'background-image',
		'custom-rules'
	);
	
	/**
	* Timing Functions css
	*
	* @since 1.0.0
	* @access protected
	*
	* @var array
	*/
	protected $timing_functions = array(
		'ease'           => 'ease',
		'linear'         => 'linear',
		'ease-in'        => 'ease-in',
		'ease-out'       => 'ease-out',
		'ease-in-out'    => 'ease-in-out',
		'easeInCubic'    => 'cubic-bezier(0.550, 0.055, 0.675, 0.190)',
		'easeOutCubic'   => 'cubic-bezier(0.215, 0.610, 0.355, 1.000)',
		'easeInOutCubic' => 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
		'easeInCirc'     => 'cubic-bezier(0.600, 0.040, 0.980, 0.335)',
		'easeOutCirc'    => 'cubic-bezier(0.075, 0.820, 0.165, 1.000)',
		'easeInOutCirc'  => 'cubic-bezier(0.785, 0.135, 0.150, 0.860)',
		'easeInExpo'     => 'cubic-bezier(0.950, 0.050, 0.795, 0.035)',
		'easeOutExpo'    => 'cubic-bezier(0.190, 1.000, 0.220, 1.000)',
		'easeInOutExpo'  => 'cubic-bezier(1.000, 0.000, 0.000, 1.000)',
		'easeInQuad'     => 'cubic-bezier(0.550, 0.085, 0.680, 0.530)',
		'easeOutQuad'    => 'cubic-bezier(0.250, 0.460, 0.450, 0.940)',
		'easeInOutQuad'  => 'cubic-bezier(0.455, 0.030, 0.515, 0.955)',
		'easeInQuart'    => 'cubic-bezier(0.895, 0.030, 0.685, 0.220)',
		'easeOutQuart'   => 'cubic-bezier(0.165, 0.840, 0.440, 1.000)',
		'easeInOutQuart' => 'cubic-bezier(0.770, 0.000, 0.175, 1.000)',
		'easeInQuint'    => 'cubic-bezier(0.755, 0.050, 0.855, 0.060)',
		'easeOutQuint'   => 'cubic-bezier(0.230, 1.000, 0.320, 1.000)',
		'easeInOutQuint' => 'cubic-bezier(0.860, 0.000, 0.070, 1.000)',
		'easeInSine'     => 'cubic-bezier(0.470, 0.000, 0.745, 0.715)',
		'easeOutSine'    => 'cubic-bezier(0.390, 0.575, 0.565, 1.000)',
		'easeInOutSine'  => 'cubic-bezier(0.445, 0.050, 0.550, 0.950)',
		'easeInBack'     => 'cubic-bezier(0.600, -0.280, 0.735, 0.045)',
		'easeOutBack'    => 'cubic-bezier(0.175,  0.885, 0.320, 1.275)',
		'easeInOutBack'  => 'cubic-bezier(0.680, -0.550, 0.265, 1.550)'
	);	
	
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
	* No initialization allowed
	* @since 1.0.0
	*/
	public function __construct() {
		
		// declare The Grid base helper
		$this->base = new The_Grid_Base();
		
	}
	
	/**
	* Generate Skin
	* @since 1.0.0
	*/
	public function generate_skin($skin_data = array()) {
			
		$this->skin_settings = $skin_data;
		$this->skin_settings = json_decode($this->skin_settings, true);
				
		if ($this->skin_settings) {
			
			// generate skin slug
			$this->generate_skin_slug();
			
			// store google font data
			$this->get_google_font();
			
			// reset css for element
			$this->reset_css_element();
			
			// vertical alignment for center content overlay
			$this->vertical_alignment();
			
			// generate all data fro the skin css/php
			$this->generate_item_styles();
			$this->generate_element_styles();
			$this->generate_global_custom_css();
			$this->generate_skin_php();
			
			// return new skin slug/style
			return $this->build_skin_data();
		
		} else {
			
			$error_msg = __('Sorry, an unexpected error occurs while retrieving the skin settings', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}
		
	}
	
	/**
	* Build Skin data
	* @since 1.0.0
	*/
	public function build_skin_data() {
		
		 // sanitized css
		$this->skin_css = wp_kses($this->skin_css, array( '\'', '\"' ));
		$this->skin_css = str_replace('&gt;' , '>' , $this->skin_css);
		
		$params = array(
			'type'   => esc_attr($this->skin_settings['item']['layout']['skin_style']),
			'filter' => esc_attr($this->skin_settings['item']['layout']['skin_filter']),
			'slug'   => $this->skin_slug, // already sanitized
			'name'   => esc_attr($this->skin_settings['item']['layout']['skin_name']),
			'col'    => esc_attr($this->skin_settings['item']['layout']['skin_col']),
			'row'    => esc_attr($this->skin_settings['item']['layout']['skin_row']),
			'php'    => 'is_custom_skin', // fixed string
			'css'    => 'is_custom_skin'  // fixed string
		);
		
		return array(
			'name'     => esc_attr($this->skin_settings['item']['layout']['skin_name']),
			'slug'     => $this->skin_slug, // already sanitized
			'params'   => wp_json_encode($params), 
			'settings' => wp_json_encode($this->skin_settings),
			'elements' => wp_json_encode($this->skin_elements),
			'styles'   => wp_json_encode(array(
				'css'  => $this->base->compress_css($this->skin_css), // already sanitized
				'font' => wp_json_encode($this->skin_fonts)
			)),
			'php_file' => $this->skin_php, // already sanitized
			'css_file' => $this->skin_css, // already sanitized
		);
					
	}
	
	/**
	* Generate skin slug
	* @since 1.0.0
	*/
	public function generate_skin_slug() {
		
		$this->skin_slug = (isset($this->skin_settings['item']['layout']['skin_name'])) ? $this->skin_settings['item']['layout']['skin_name'] : null;
		
		if (empty($this->skin_slug)) {
			
			$error_msg = __('Please enter a skin name', 'tg-text-domain' );
			throw new Exception($error_msg);
		
		}
		
		if (strlen($this->skin_slug) < 2) {
			
			$error_msg = __('Invalid skin name. The skin name has to be at least 2 characters long', 'tg-text-domain' );
			throw new Exception($error_msg);
		
		}
		
		$this->skin_slug = sanitize_title($this->skin_slug);
		$this->skin_slug = sanitize_html_class($this->skin_slug);
		
		if (strlen($this->skin_slug) < 2) {
			
			$error_msg = __('Invalid skin slug. The skin slug has to be at least 2 characters long.', 'tg-text-domain' );
			throw new Exception($error_msg);
		
		}
		
		// add 'tg-' prefix for custom skin
		$this->skin_slug = 'tg-'.$this->skin_slug;
		
	}
	
	/**
	* Generate php code of the skin
	* @since 1.0.0
	*/
	public function generate_skin_php() {
	
		$this->skin_php_start();
		$this->top_content_holder();
		$this->media_holder();
		$this->bottom_content_holder();
		$this->skin_php_end();
		
	}
	
	/**
	* PHP skin data header
	* @since 1.0.0
	*/
	public function skin_php_start() {
		
		$this->skin_php .= '<?php'. "\r\n";
		$this->skin_php .= '/**'. "\r\n";
		$this->skin_php .= '* @package   The_Grid'. "\r\n";
		$this->skin_php .= '* @author    Themeone <themeone.master@gmail.com>'. "\r\n";
		$this->skin_php .= '* @copyright 2015 Themeone'. "\r\n";
		$this->skin_php .= '*'. "\r\n";
		$this->skin_php .= '* Skin Name: '.$this->skin_settings['item']['layout']['skin_name']. "\r\n";
		$this->skin_php .= '* Skin Slug: '.$this->skin_slug. "\r\n";
		$this->skin_php .= '* Date: '.date('m/d/y - h:i:sA'). "\r\n";
		$this->skin_php .= '*'. "\r\n";
		$this->skin_php .= '*/'. "\r\n\r\n";
		$this->skin_php .= '// Exit if accessed directly'. "\r\n";
		$this->skin_php .= 'if (!defined(\'ABSPATH\')) {'. "\r\n";
		$this->skin_php .= "\t". 'exit;'. "\r\n";
		$this->skin_php .= '}'. "\r\n\r\n";
		$this->skin_php .= '// Init The Grid Elements instance'. "\r\n";
		$this->skin_php .= '$tg_el = The_Grid_Elements();'. "\r\n\r\n";
		$this->skin_php .= '// Prepare main data for futur conditions'. "\r\n";
		$this->skin_php .= '$image  = $tg_el->get_attachment_url();'. "\r\n";
		$this->skin_php .= '$format = $tg_el->get_item_format();'. "\r\n\r\n";
		$this->skin_php .= '$output = null;'. "\r\n\r\n";
		
	}
	
	/**
	* PHP return skin
	* @since 1.0.0
	*/
	public function skin_php_end() {
		
		$this->skin_php .= "\r\n". 'return $output;';
		
	}

	/**
	* PHP skin top content holder
	* @since 1.0.0
	*/
	public function top_content_holder() {
		
		$holder   = 'top_content_holder';
		$elements = (isset($this->skin_settings['elements']['top-content-holder'])) ? $this->skin_settings['elements']['top-content-holder'] : null;	
		
		if ($elements) {
			
			$this->skin_php .= '// Top content wrapper start'. "\r\n";
			$this->skin_php .= '$output .= $tg_el->get_content_wrapper_start(\'\', \'top\');'. "\r\n";
			
				$this->skin_php .= $this->add_layer_action('top_content_holder', 'under');
				
				foreach ($elements as $element => $data) {
					$this->skin_php .= $this->item_element($holder, $element, $data);
				}
				$this->skin_php .= "\t". '$output .= $tg_el->get_content_clear();'. "\r\n";
				
				$this->skin_php .= $this->add_layer_action('top_content_holder', 'above');
				
			$this->skin_php .= '$output .= $tg_el->get_content_wrapper_end();'. "\r\n";
			$this->skin_php .= '// Top content wrapper end'. "\r\n\r\n";

		}
		
	}
	
	/**
	* PHP skin bottom content holder
	* @since 1.0.0
	*/
	public function bottom_content_holder() {
		
		$holder   = 'bottom_content_holder';
		$elements = (isset($this->skin_settings['elements']['bottom-content-holder'])) ? $this->skin_settings['elements']['bottom-content-holder'] : null;
		
		if ($elements) {
			
			$this->skin_php .= '// Bottom content wrapper start'. "\r\n";
			$this->skin_php .= '$output .= $tg_el->get_content_wrapper_start(\'\', \'bottom\');'. "\r\n";
			
				$this->skin_php .= $this->add_layer_action('bottom_content_holder', 'under');
				
				foreach ($elements as $element => $data) {
					$this->skin_php .= $this->item_element('bottom_content_holder', $element, $data);
				}
				
				$this->skin_php .= $this->add_layer_action('bottom_content_holder', 'above');
				
				$this->skin_php .= "\t". '$output .= $tg_el->get_content_clear();'. "\r\n";
			$this->skin_php .= '$output .= $tg_el->get_content_wrapper_end();'. "\r\n";
			$this->skin_php .= '// Bottom content wrapper end'. "\r\n";
			
		}
		
	}
	
	/**
	* PHP skin media holder
	* @since 1.0.0
	*/
	public function media_holder() {
		
		$holder        = 'media_holder';
		$skin_style    = (isset($this->skin_settings['item']['layout']['skin_style'])) ? $this->skin_settings['item']['layout']['skin_style'] : null;
		$media_content = (isset($this->skin_settings['item']['layout']['media_content'])) ? $this->skin_settings['item']['layout']['media_content'] : null;
		
		// set media_content (show or hide)
		$this->skin_elements['media_content'] = $media_content;

		if ($media_content || $skin_style == 'grid') {
			
			$overlay_type   = (isset($this->skin_settings['item']['layout']['overlay_type'])) ? $this->skin_settings['item']['layout']['overlay_type'] : null;
		
			$full_content   = (isset($this->skin_settings['elements']['media-holder']))        ? $this->skin_settings['elements']['media-holder']        : null;
			$top_content    = (isset($this->skin_settings['elements']['media-holder-top']))    ? $this->skin_settings['elements']['media-holder-top']    : null;
			$center_content = (isset($this->skin_settings['elements']['media-holder-center'])) ? $this->skin_settings['elements']['media-holder-center'] : null;
			$bottom_content = (isset($this->skin_settings['elements']['media-holder-bottom'])) ? $this->skin_settings['elements']['media-holder-bottom'] : null;
			
			if ($overlay_type == 'full') {
				$this->skin_elements['media_overlay'][] = array(
					'type'    => 'function',
					'element' => '',
					'content' => 'get_overlay',
					'args'    => ''
				);
			}
			
			$this->skin_php .= ($skin_style == 'masonry') ? '$media = $tg_el->get_media();'. "\r\n\r\n" : null;
			$this->skin_php .= ($skin_style == 'masonry') ? '// if there is a media'. "\r\n" : null;
			$this->skin_php .= ($skin_style == 'masonry') ? 'if ($media) {'. "\r\n\r\n" : null;
			$this->skin_php .= "\t". '// Media wrapper start'. "\r\n";
			$this->skin_php .= "\t". '$output .= $tg_el->get_media_wrapper_start();'. "\r\n\r\n";
			$this->skin_php .= "\t". '// Media content (image, gallery, audio, video)'. "\r\n";
			$this->skin_php .= ($skin_style == 'masonry') ? "\t". '$output .= $media;'. "\r\n\r\n" : "\t". '$output .= $tg_el->get_media();'. "\r\n";
			$this->skin_php .= ($skin_style == 'masonry') ? "\t". '// if there is an image'. "\r\n" : null;
			$this->skin_php .= ($skin_style == 'masonry') ? "\t". 'if ($image || in_array($format, array(\'gallery\', \'video\'))) {'. "\r\n\r\n" : "\r\n";
			
			// media content holder start
			$this->skin_php .= "\t\t". '// Media content holder start'. "\r\n";
			$this->skin_php .= "\t\t". '$output .= $tg_el->get_media_content_start();'. "\r\n\r\n";
			
			// overlay
			$this->skin_php .= ($overlay_type == 'full') ? "\t\t". '// Overlay'. "\r\n" : "\r\n";
			$this->skin_php .= ($overlay_type == 'full') ? "\t\t". '$output .= $tg_el->get_overlay();'. "\r\n\r\n" : "\r\n\r\n";
			
			$under_action = $this->add_layer_action('media_holder_before', 'under');
			$this->skin_php .= ($under_action) ? "\t" . $under_action . "\r\n": null;
			
			// top content
			if ($top_content) {
				
				if ($overlay_type == 'content') {
					$this->skin_elements['media_holder_top'][] = array(
						'type'    => 'function',
						'element' => 'top',
						'content' => 'get_overlay',
						'args'    => array('', 'top')
					);
				}
				
				$this->skin_php .= "\t\t". '// Top wrapper start'. "\r\n";
				$this->skin_php .= "\t\t". '$output .= \'<div class="tg-top-holder">\';'. "\r\n";
				$this->skin_php .= ($overlay_type == 'content') ? "\t\t\t". '// Overlay'. "\r\n" : null;
				$this->skin_php .= ($overlay_type == 'content') ? "\t\t\t". '$output .= $tg_el->get_overlay(\'\',\'top\');'. "\r\n" : null;
				foreach ($top_content as $element => $data) {
					$function = str_replace("\t", "\t\t\t", $this->item_element('media_holder_top', $element, $data));
					$this->skin_php .= $function;
				}
				$this->skin_php .= "\t\t". '$output .= \'</div>\';'. "\r\n";
				$this->skin_php .= "\t\t". '// Top wrapper end'. "\r\n\r\n";
				
			}
			
			// center content
			if ($center_content) {
				
				if ($overlay_type == 'content') {
					$this->skin_elements['media_holder_center'][] = array(
						'type'    => 'function',
						'element' => 'center',
						'content' => 'get_overlay',
						'args'    => array('', 'center')
					);
				}
				
				$this->skin_php .= "\t\t". '// Center wrapper start'. "\r\n";
				$this->skin_php .= "\t\t". '$output .= $tg_el->get_center_wrapper_start();'. "\r\n";
				$this->skin_php .= ($overlay_type == 'content') ? "\t\t\t". '// Overlay'. "\r\n" : null;
				$this->skin_php .= ($overlay_type == 'content') ? "\t\t\t". '$output .= $tg_el->get_overlay(\'\',\'center\');'. "\r\n" : null;
				foreach ($center_content as $element => $data) {
					$function = str_replace("\t", "\t\t\t", $this->item_element('media_holder_center', $element, $data));
					$this->skin_php .= $function;
				}
				$this->skin_php .= "\t\t". '$output .= $tg_el->get_center_wrapper_end();'. "\r\n";
				$this->skin_php .= "\t\t". '// Center wrapper end'. "\r\n\r\n";

			}
			
			
			// bottom content
			if ($bottom_content) {
				
				if ($overlay_type == 'content') {
					$this->skin_elements['media_holder_bottom'][] = array(
						'type'    => 'function',
						'element' => 'bottom',
						'content' => 'get_overlay',
						'args'    => array('', 'bottom')
					);
				}
				
				$this->skin_php .= "\t\t". '// Bottom wrapper start'. "\r\n";
				$this->skin_php .= "\t\t". '$output .= \'<div class="tg-bottom-holder">\';'. "\r\n";
				$this->skin_php .= ($overlay_type == 'content') ? "\t\t\t". '// Overlay'. "\r\n" : null;
				$this->skin_php .= ($overlay_type == 'content') ? "\t\t\t". '$output .= $tg_el->get_overlay(\'\',\'bottom\');'. "\r\n" : null;
				foreach ($bottom_content as $element => $data) {
					$function = str_replace("\t", "\t\t\t", $this->item_element('media_holder_bottom', $element, $data));
					$this->skin_php .= $function;
				}
				$this->skin_php .= "\t\t". '$output .= \'</div>\';'. "\r\n";
				$this->skin_php .= "\t\t". '// Bottom wrapper end'. "\r\n\r\n";
				
			}
			
			// full content
			if ($full_content) {
				
				$this->skin_php .= "\t\t". '// Absolute element(s) in Media wrapper'. "\r\n";
				
				foreach ($full_content as $element => $data) {
					$function = "\t" . $this->item_element('media_holder', $element, $data);
					$this->skin_php .= $function;
				}
				
				$this->skin_php .= "\r\n";
			
			}
			
			// media content holder end
			$this->skin_php .= "\t\t". '// Media content holder end'. "\r\n";
			$this->skin_php .= "\t\t". '$output .= $tg_el->get_media_content_end();'. "\r\n\r\n";
			
			$above_action = $this->add_layer_action('media_holder_after', 'above');
			$this->skin_php .= ($above_action) ? "\t" . $above_action . "\r\n" : null;
			
			$this->skin_php .= ($skin_style == 'masonry') ? "\t". '}'. "\r\n\r\n" : null;
			$this->skin_php .= "\t". '$output .= $tg_el->get_media_wrapper_end();'. "\r\n";
			$this->skin_php .= "\t". '// Media wrapper end'. "\r\n\r\n";
			$this->skin_php .= ($skin_style == 'masonry') ? '}'. "\r\n\r\n" : null;
		
		}
			
	}
	
	/**
	* PHP generate element action args
	* @since 1.0.0
	*/
	public function get_action_args($data) {
		
		$action_args = array();
		$args = (isset($data['action'])) ? $data['action'] : null;
		
		if ($args) {
			
			$type = $this->base->getVar($args, 'type');
			
			if ($type == 'link') {
				
				$action_args = array(
					'action' => array(
						'type'          => $type,
						'link_target'   => $this->base->getVar($args, 'link_target', 'self'),
						'link_url'      => $this->base->getVar($args, 'link_url'),
						'custom_url'    => $this->base->getVar($args, 'custom_url'),
						'meta_data_url' => $this->base->getVar($args, 'meta_data_url'),
					)
				);

			
			} else if ($type == 'lightbox') {
				
				$action_args = array(
					'action' => array(
						'type' => $type
					)
				);
			}
			
			if (isset($args['position'])) {
				$action_args['action']['position'] = $args['position'];
			}
			
		}
		
		return $action_args;
	
	}
	
	/**
	* PHP add layer action + args
	* @since 1.0.0
	*/
	public function add_layer_action($name, $position) {
		
		$names = array(
			'bottom_content_holder' => 'tg-item-content-holder[data-position="bottom"]',
			'top_content_holder'    => 'tg-item-content-holder[data-position="top"]',
			'media_holder_before'   => 'tg-item-media-holder',
			'media_holder_after'    => 'tg-item-media-holder',
			'media_holder'          => 'tg-item-media-holder'	
		);
		
		$containers = (isset($this->skin_settings['item']['containers'])) ? $this->skin_settings['item']['containers'] : null;
		
		if ($containers) {
		
			$action_args = $this->get_action_args($containers[$names[$name]]);
			
			if (isset($action_args['action']['type']) && $action_args['action']['position']) {
			
				if ($action_args && isset($action_args['action']['position']) && $action_args['action']['position'] == $position && isset($action_args['action']['type'])) {
					
					$this->skin_elements[$name][] = array(
						'type'    => 'function',
						'element' => 'tg-element-absolute',
						'content' => 'add_layer_action',
						'args'    => $action_args
					);
					
					$action_args = $this->base->var_export_min($action_args, true);
					
					return "\t". '$output .= $tg_el->add_layer_action('.$action_args.', \'tg-absolute\');'. "\r\n";
				
				}
			
			}
		
		}
		
		return '';
		
	}

	/**
	* PHP generate elements
	* @since 1.0.0
	*/
	public function item_element($holder, $element, $data) {
		
		$source_type = (isset($data['source']['source_type'])) ? $data['source']['source_type'] : null;
		
		$element = (isset($data['source']['class_name']) && $data['source']['class_name']) ? $element.' '.$data['source']['class_name'] : $element;
		
		switch ($source_type) {
			case 'post':
				return $this->post_elements($holder, $element, $data);
				break;
			case 'woocommerce':
				return $this->woocommerce_elements($holder, $element, $data);
				break;
			case 'video_stream':
				return $this->video_stream_elements($holder, $element, $data);
				break;
			case 'media_button':
				return $this->media_button($holder, $element, $data);
				break;
			case 'social_link':
				return $this->social_link($holder, $element, $data);
				break;
			case 'icon':
				return $this->icon_element($holder, $element, $data);
				break;
			case 'html':
				return $this->html_element($holder, $element, $data);
				break;
			case 'line_break':
				$this->skin_elements[$holder][] = array(
					'type'    => 'function',
					'element' => '',
					'content' => 'get_line_break',
					'args'    => ''
				);
				return "\t". '$output .= $tg_el->get_line_break();'. "\r\n";
				break;
		}

	}
	
	/**
	* PHP post content type
	* @since 1.0.0
	*/
	public function post_elements($holder, $element, $data) {
		
		$action   = (isset($data['action'])) ? $data['action'] : null;
		$settings = (isset($data['source'])) ? $data['source'] : null;
		$function = (isset($settings['post_content'])) ? $settings['post_content'] : null;
		
		if (!$function) {
			return;
		}
		
		switch ($function) {
			case 'get_the_title':
				$args   = array(
					'link'   => false
				);
				break;
			case 'get_the_excerpt':
				$args   = array(
					'length' => (isset($settings['excerpt_length'])) ? esc_attr($settings['excerpt_length']) : '',
					'suffix' => (isset($settings['excerpt_suffix'])) ? esc_attr($settings['excerpt_suffix']) : ''
				);
				break;
			case 'get_the_date':
				$args   = array(
					'format' => (isset($settings['date_format'])) ? esc_attr($settings['date_format']) : ''
				);
				break;
			case 'get_the_author':
				$args   = array(
					'link'   => false,
					'prefix' => (isset($settings['author_prefix']) && !empty($settings['author_prefix'])) ? esc_attr($settings['author_prefix']).' ' : ''
				);
				break;
			case 'get_the_author_avatar':
				$args  = array();
				break;
			case 'get_the_terms':
				$args  = array(
					'taxonomy'  => (isset($settings['taxonomy']) && $settings['taxonomy']) ? $settings['taxonomy'] : '',
					'link'      => (isset($settings['terms_link']) && !$settings['terms_link']) ? false : true,
					'color'     => (isset($settings['terms_color'])) ? esc_attr($settings['terms_color']) : '',
					'separator' => (isset($settings['terms_separator'])) ? esc_attr($settings['terms_separator']) : '',
					'override'  => true
				);
				break;
			case 'get_the_comments_number':
				$args  = array(
					'link' => false,
					'icon' => (isset($settings['comment_icon']) && $settings['comment_icon']) ? '<i class="'.esc_attr($settings['comment_icon']).'"></i>' : ''
				);
				break;
			case 'get_the_likes_number':
				$args  = array();
				break;
			case 'get_the_meta_data':
				$args  = array(
					'meta_key' => (isset($settings['metadata_key'])) ? esc_attr($settings['metadata_key']) : ''
				);
				break;
		}
		
		// assign HTML tag
		if (isset($data['source']['html_tag']) && $data['source']['html_tag']) {
			$args['html_tag'] = esc_attr($data['source']['html_tag']);
		}
		
		// get action args and merge with element args
		$action_args = $this->get_action_args($data);
		$args = array_merge((array) $args, $action_args);
		
		// prepare element function
		$this->skin_elements[$holder][] = array(
			'type'    => 'function',
			'element' => $element,
			'content' => $function,
			'args'    => $args
		);
		
		// pretty return array for php file
		$args = $this->base->var_export_min($args, true);
		
		return (isset($args) && $function && $element) ? "\t". '$output .= $tg_el->'. $function .'('.$args.', \''.esc_attr($element).'\');'. "\r\n" : '';
		
	}

	/**
	* PHP woocommerce content type
	* @since 1.0.0
	*/
	public function woocommerce_elements($holder, $element, $data) {
		
		$settings = (isset($data['source'])) ? $data['source'] : null;
		$function = (isset($settings['post_content'])) ? $settings['woocommerce_content'] : null;
		
		if (!$function) {
			return;
		}
		
		if ($function == 'get_product_add_to_cart_url') {
			$args = array(
				'text' => (isset($settings['add_to_cart_url_text'])) ? esc_html($settings['add_to_cart_url_text']) : ''
			);
		} else if ($function == 'get_product_cart_button') {
			$args = array(
				'cart_icon'     => (isset($settings['woo_cart_icon']) && $settings['woo_cart_icon']) ? true : false,
				'icon_simple'   => (isset($settings['woo_cart_icon_simple'])) ? '<i class="'.esc_attr($settings['woo_cart_icon_simple']).'"></i>' : null,
				'icon_variable' => (isset($settings['woo_cart_icon_variable'])) ? '<i class="'.esc_attr($settings['woo_cart_icon_variable']).'"></i>' : ''
			);
		} else {
			$args = array();
		}
		
		// assign HTML tag
		if (isset($data['source']['html_tag']) && $data['source']['html_tag']) {
			$args['html_tag'] = esc_attr($data['source']['html_tag']);
		}
		
		// get action args and merge with element args
		$action_args = $this->get_action_args($data);
		$args = array_merge((array) $args, $action_args);
		
		// prepare element function
		$this->skin_elements[$holder][] = array(
			'type'    => 'function',
			'element' => $element,
			'content' => $function,
			'args'    => $args
		);
		
		// pretty return array for php file
		$args = $this->base->var_export_min($args, true);
		
		return (isset($args) && $function && $element) ? "\t". '$output .= $tg_el->'. $function .'('.$args.', \''.esc_attr($element).'\');'. "\r\n" : '';
	
	}
	
	/**
	* PHP Youtube/Vimeo content type
	* @since 1.0.0
	*/
	public function video_stream_elements($holder, $element, $data) {
		
		$settings = (isset($data['source'])) ? $data['source'] : null;
		$function = (isset($settings['video_stream_content'])) ? $settings['video_stream_content'] : null;
		
		if (!$function) {
			return;
		}
		
		if ($function == 'get_the_views_number') {
			$args = array(
				'view_suffix' => (isset($settings['view_number_suffix'])) ? esc_attr($settings['view_number_suffix']) : ''
			);
		} else {
			$args = array();
		}
		
		// assign HTML tag
		if (isset($data['source']['html_tag']) && $data['source']['html_tag']) {
			$args['html_tag'] = esc_attr($data['source']['html_tag']);
		}
		
		// get action args and merge with element args
		$action_args = $this->get_action_args($data);
		$args = array_merge((array) $args, $action_args);
		
		// prepare element function
		$this->skin_elements[$holder][] = array(
			'type'    => 'function',
			'element' => $element,
			'content' => $function,
			'args'    => $args
		);
		
		// pretty return array for php file
		$args = $this->base->var_export_min($args, true);
		
		return (isset($args) && $function && $element) ? "\t". '$output .= $tg_el->'. $function .'('.$args.', \''.esc_attr($element).'\');'. "\r\n" : '';
	
	}
	
	/**
	* PHP icon element
	* @since 1.0.0
	*/
	public function icon_element($holder, $element, $data) {
		
		if (isset($data['source']['element_icon']) && $data['source']['element_icon']) {
			
			$args = array(
				'icon' => (isset($data['source']['element_icon'])) ? esc_attr($data['source']['element_icon']) : ''
			);
			
			// assign HTML tag
			if (isset($data['source']['html_tag']) && $data['source']['html_tag']) {
				$args['html_tag'] = esc_attr($data['source']['html_tag']);
			}
			
			// get action args and merge with element args
			$action_args = $this->get_action_args($data);
			$args = array_merge((array) $args, $action_args);
			
			// prepare element function
			$this->skin_elements[$holder][] = array(
				'type'    => 'function',
				'element' => $element,
				'content' => 'get_icon_element',
				'args'    => $args
			);
			
			// pretty return array for php file
			$args = $this->base->var_export_min($args, true);
			
			return (isset($args) && $element) ? "\t". '$output .= $tg_el->get_icon_element('.$args.', \''.esc_attr($element).'\');'. "\r\n" : '';
				
		}
	
	}
	
	/**
	* PHP html element
	* @since 1.0.0
	*/
	public function html_element($holder, $element, $data) {
		
		if (isset($data['source']['html_content'])) {
			
			// get Wordpress global from main tags & attributes
			global $allowedposttags, $allowedtags;
			
			$allowedposttags['a']['class'] = array();
			$allowedposttags['a']['data-tolb-src']  = array();
			$allowedposttags['a']['data-tolb-type'] = array();
			$allowedposttags['a']['data-tolb-alt']  = array();
			
			// add i/svg tags & attributes
			$svg_attr = array(
				'd'                 => array(),
				'x'                 => array(),
				'x1'                => array(),
				'x2'                => array(),
				'y'                 => array(),
				'y1'                => array(),
				'y2'                => array(),
				'r'					=> array(),
				'rx'				=> array(),
				'ry'				=> array(),
				'cx'                => array(),
				'cy'                => array(),
				'dx'                => array(),
				'fx'                => array(),
				'fy'                => array(),
				'g1'                => array(),
				'g2'                => array(),
				'u1'                => array(),
				'u2'                => array(),
				'id'                => array(), 
				'class'             => array(), 
				'style'             => array(),
				'xmlns'             => array(),
				'xmlns:xlink'       => array(),
				'width'				=> array(),
				'height'	        => array(),
				'viewbox'			=> array(),
				'transform'         => array(),
				'fill'              => array(),
				'fill-rule'         => array(),
				'points'            => array(),
				'stroke'            => array(),
				'stroke-width'      => array(),
				'stroke-linecap'    => array(),
				'stroke-linejoin'   => array(),
				'stroke-miterlimit' => array()
			);
			
			$allowedsvg = array(
				'i' => array(
					'id'          => array(), 
					'class'       => array(), 
					'width'       => array(),
					'height'      => array(),
					'style'       => array(),
				),
				'svg'      => $svg_attr,
				'g'        => $svg_attr,
				'path'     => $svg_attr,
				'line'     => $svg_attr,
				'circle'   => $svg_attr,
				'polyline' => $svg_attr,
			);
			
			$allowed = array_merge_recursive($allowedposttags, $allowedtags, $allowedsvg);
			
			$html_content = $data['source']['html_content'];
			
			$args = array(
				'html' => $html_content
			);
			
			// assign HTML tag
			if (isset($data['source']['html_tag']) && $data['source']['html_tag']) {
				$args['html_tag'] = esc_attr($data['source']['html_tag']);
			}
			
			// get action args and merge with element args
			$action_args = $this->get_action_args($data);
			$args = array_merge((array) $args, $action_args);
			
			// prepare element function
			$this->skin_elements[$holder][] = array(
				'type'    => 'function',
				'element' => $element,
				'content' => 'get_html_element',
				'args'    => $args
			);
			
			$html_content = wp_kses($data['source']['html_content'], $allowed);
			$html_content = str_ireplace("'",  "&apos;", $html_content);
			$html_content = str_ireplace('"',  "&quot;", $html_content);

			// reassign html content formated for php file
			$args['html'] = $html_content;
			// pretty return array for php file
			$args = $this->base->var_export_min($args, true);
			
			return (isset($args) && $element) ? "\t". '$output .= $tg_el->get_html_element('.$args.', \''.esc_attr($element).'\');'. "\r\n" : '';
				
		}
	
	}
	
	/**
	* PHP social link element
	* @since 1.0.0
	*/
	public function social_link($holder, $element, $data) {
		
		$args = array(
			'type' => (isset($data['source']['social_link_type']) && $data['source']['social_link_type']) ? esc_attr($data['source']['social_link_type']) : 'facebook',
		);
		
		// assign HTML tag
		if (isset($data['source']['html_tag']) && $data['source']['html_tag']) {
			$args['html_tag'] = esc_attr($data['source']['html_tag']);
		}
		
		// prepare element function
		$this->skin_elements[$holder][] = array(
			'type'    => 'function',
			'element' => $element,
			'content' => 'get_social_share_link',
			'args'    => $args
		);
		
		// pretty return array for php file
		$args = $this->base->var_export_min($args, true);
			
		return (isset($args) && $element) ? "\t". '$output .= $tg_el->get_social_share_link('.$args.', \''.esc_attr($element).'\');'. "\r\n" : '';
	
	}
	
	/**
	* PHP media button element
	* @since 1.0.0
	*/
	public function media_button($holder, $element, $data) {
		
		$settings = (isset($data['source'])) ? $data['source'] : null;
		
		if (isset($settings) && !empty($settings)) {
			
			$content_type = (isset($settings['lightbox_content_type'])) ? $settings['lightbox_content_type'] : null;
			$icon_type = array('image', 'audio', 'video', 'pause');
			
			foreach ($icon_type as $type) {
				
				$text = (isset($settings['lightbox_'.$type.'_text']) && $settings['lightbox_'.$type.'_text']) ? '<i>'.esc_attr($settings['lightbox_'.$type.'_text']).'</i>' : null;
				$icon = (isset($settings['lightbox_'.$type.'_icon']) && $settings['lightbox_'.$type.'_icon']) ? '<i class="'.esc_attr($settings['lightbox_'.$type.'_icon']).'"></i>' : null;
				$icons[$type] = ($content_type == 'text' && isset($settings['lightbox_'.$type.'_text']) && $settings['lightbox_'.$type.'_text']) ? $text : $icon;
			
			}
			
			$args = array(
				'icons' => array(
					'image' => $icons['image'],
					'audio' => $icons['audio'],
					'video' => $icons['video'],
					'pause' => $icons['pause']
				)
			);
			
			// assign HTML tag
			if (isset($data['source']['html_tag']) && $data['source']['html_tag']) {
				$args['html_tag'] = esc_attr($data['source']['html_tag']);
			}
			
			// get action args and merge with element args
			$action_args = $this->get_action_args($data);
			$args = array_merge((array) $args, $action_args);
			
			// prepare element function
			$this->skin_elements[$holder][] = array(
				'type'    => 'function',
				'element' => $element,
				'content' => 'get_media_button',
				'args'    => $args
			);
			
			// pretty return array for php file
			$args = $this->base->var_export_min($args, true);
			
			return (isset($args) && $element) ? "\t". '$output .= $tg_el->get_media_button('.$args.', \''.esc_attr($element).'\');'. "\r\n" : '';
		
		}
	
	}
	
	/**
	* Get google font data
	* @since 1.0.0
	*/
	public function get_google_font() {
		
		include TG_PLUGIN_PATH . '/includes/google-fonts.php';
		$this->google_font = (isset($googlefonts)) ? $googlefonts : array();
			
	}
	
	/**
	* Generate Item styles
	* @since 1.0.0
	*/
	public function generate_item_styles() {
		
		// skin style
		$skin_style = $this->skin_settings['item']['layout']['skin_style'];
		
		// get main layout containers
		$elements = (isset($this->skin_settings['item']['containers'])) ? $this->skin_settings['item']['containers'] : null;

		// overlay type (none, full-size, content based)
		$overlay_type = (isset($this->skin_settings['item']['layout']['overlay_type'])) ? $this->skin_settings['item']['layout']['overlay_type'] : null;
		
		// add z-index for item inner
		$elements['tg-item-inner']['styles']['idle_state']['z-index'] = '0';
		
		// assign relative positon to top/bottom content holders if elements present inside
		$content_holders = array('top', 'bottom');
		foreach ($content_holders as $holder) {
			
			if (isset($this->skin_settings['elements'][$holder.'-content-holder']) && $this->skin_settings['elements'][$holder.'-content-holder']) {
			
				$elements['tg-item-content-holder[data-position="'.$holder.'"]']['styles']['idle_state']['position']   = 'relative';
				$elements['tg-item-content-holder[data-position="'.$holder.'"]']['styles']['idle_state']['display']    = 'block';
				$elements['tg-item-content-holder[data-position="'.$holder.'"]']['styles']['idle_state']['min-height'] = '1px';
				
			} else {
				unset($elements['tg-item-content-holder[data-position="'.$holder.'"]']);
			}
			
		}
		
		// if overlay exists
		if ($overlay_type) {
			
			$overlay_idle = isset($elements['tg-item-overlay']['styles']['idle_state']) ? $elements['tg-item-overlay']['styles']['idle_state'] : null;
			
			// set absolute positions
			$elements['tg-item-overlay']['styles']['idle_state']['position'] = 'absolute';
			$elements['tg-item-overlay']['styles']['idle_state']['top']      = $this->base->getVar($overlay_idle, 'top', '0');
			$elements['tg-item-overlay']['styles']['idle_state']['right']    = $this->base->getVar($overlay_idle, 'right', '0');
			$elements['tg-item-overlay']['styles']['idle_state']['bottom']   = $this->base->getVar($overlay_idle, 'bottom', '0');
			$elements['tg-item-overlay']['styles']['idle_state']['left']     = $this->base->getVar($overlay_idle, 'left', '0');
			
			// reassigned animation if full overlay style
			$elements['tg-item-overlay']['animation'] = ($overlay_type == 'full') ? $elements['tg-item-overlay[data-position="center"]']['animation'] : null;
			
			// if content based overlay
			if ($overlay_type == 'content') {
				
				$elements['tg-item-overlay']['animation'] = null;
				
			} else {
				
				unset($elements['tg-item-overlay[data-position="top"]']);
				unset($elements['tg-item-overlay[data-position="center"]']);
				unset($elements['tg-item-overlay[data-position="bottom"]']);

			}
			
		}

		// remove z-index for media holder if grid style
		if ($skin_style == 'grid') {
			$elements['tg-item-media-holder']['styles']['idle_state']['z-index'] = null;
		}
		
		// remove margin to media if grid style (prevent issue with justify layout)
		if ($skin_style == 'grid') {
			$elements['tg-item-media-holder']['styles']['idle_state']['margin-top']    = null;
			$elements['tg-item-media-holder']['styles']['idle_state']['margin-right']  = null;
			$elements['tg-item-media-holder']['styles']['idle_state']['margin-bottom'] = null;
			$elements['tg-item-media-holder']['styles']['idle_state']['margin-left']   = null;
		}
		
		// process styles of content holders
		if ($elements) {
			
			// loop through each element in the area
			foreach ($elements as $element => $data) {
				// generate the css of the element
				$this->process_css($element, $data);	
			}
			
		}	
		
	}
	
	/**
	* Generate element styles
	* @since 1.0.0
	*/
	public function generate_element_styles() {
		
		// get all item area (top-content/media/bottom-content)
		$areas = (array) $this->base->getVar($this->skin_settings, 'elements');

		// loop through each area
		foreach ($areas as $area => $elements) {
			
			// if there are elements in the area
			if ($elements) {
				
				// loop through each element in the area
				foreach ($elements as $element => $data) {
					// process icon (remove font-family property)
					$data = $this->process_icon_css($data);	
					// generate the css of the element
					$this->process_css($element, $data);
					// generate the css for comment icon
					$this->process_comment_icon_css($element, $data);	
					// generate the css for comment icon
					$this->process_like_icon_css($element, $data);
					// generate the css for each taxonomy term
					$this->process_taxonomy_term_css($element, $data);
					// generate the css for text rating
					$this->process_text_rating($element, $data);
					// generate the css for star rating
					$this->process_star_rating($element, $data);
					// generate the css cart button
					$this->process_cart_button($element, $data);
					// generate the css full price
					$this->process_full_price($element, $data);
				}
				
			}
		}
		
	}
	
	/**
	* Remove icon styles font-family
	* @since 1.0.0
	*/
	public function process_icon_css($data) {
	
		$source = (array) $this->base->getVar($data, 'source');
		
		// remove font family if icon otherwise the font icon will be overriden
		if ($this->base->getVar($source, 'source_type') == 'icon') {
			$data['styles']['idle_state']['font-family'] = null;
		}
		
		return $data;
		
	}
	
	/**
	* Generate comment icon styles
	* @since 1.0.0
	*/
	public function process_comment_icon_css($element, $data) {
		
		$source = (array) $this->base->getVar($data, 'source');
		
		if ($this->base->getVar($source, 'source_type') == 'post' && $this->base->getVar($source, 'post_content') == 'get_the_comments_number' && $this->base->getVar($source, 'comment_icon')) {
			
			$rules['float']         = $this->base->getVar($source, 'comment_icon_float');
			$rules['color']         = $this->base->getVar($source, 'comment_icon_color');
			$rules['font-size']     = $this->base->getVar($source, 'comment_icon_font-size');
			$rules['font-unit']     = $this->base->getVar($source, 'comment_icon_font-size-unit');
			$rules['margin-unit']   = $this->base->getVar($source, 'comment_icon_margin-unit');
			$rules['margin-top']    = $this->base->getVar($source, 'comment_icon_margin-top');
			$rules['margin-left']   = $this->base->getVar($source, 'comment_icon_margin-left');
			$rules['margin-bottom'] = $this->base->getVar($source, 'comment_icon_margin-bottom');
			$rules['margin-right']  = $this->base->getVar($source, 'comment_icon_margin-right');
			
			$this->skin_css .= '.'.$this->skin_slug.' .'.$element.' {'. "\r\n";
				$this->skin_css .= "\t". 'text-decoration: none;'. "\r\n";
				$this->skin_css .= "\t". 'outline: none;'. "\r\n";
				$this->skin_css .= "\t". '-webkit-box-shadow: none;'. "\r\n";
				$this->skin_css .= "\t". 'box-shadow: none;'. "\r\n";
			$this->skin_css .= '}'. "\r\n";	
			
			$this->skin_css .= '.'.$this->skin_slug.'.tg-item .'.$element.' i {'. "\r\n";
				$this->skin_css .= "\t". 'position: relative;'. "\r\n";
				$this->skin_css .= "\t". 'display: inline-block;'. "\r\n";
				$this->skin_css .= "\t". 'padding: 0 1px;'. "\r\n";
				$this->skin_css .= ($rules['float']) ? "\t". 'float: '.$rules['float'].';'. "\r\n" :  'float: left;'. "\r\n";
				$this->skin_css .= ($rules['color']) ? "\t". 'color: '.$rules['color'].' !important;'. "\r\n" : null;
				$this->skin_css .= ($rules['font-size'] && $rules['font-unit']) ? "\t". 'font-size: '.$rules['font-size'].$rules['font-unit'].';'. "\r\n" : null;
				$this->skin_css .= "\t". 'line-height: initial;'. "\r\n";
				$this->skin_css .= $this->get_margin($rules);
			$this->skin_css .= '}'. "\r\n";
			
			$styles = $this->base->getVar($data, 'styles');
			$idle_state = $this->base->getVar($styles, 'idle_state');
			if ($this->base->getVar($idle_state, 'color-important')) {
				$this->skin_css .= '.tg-item.'.$this->skin_slug.' .'.$element.' span {'. "\r\n";
					$this->skin_css .= "\t". 'color: inherit;'. "\r\n";
				$this->skin_css .= '}'. "\r\n";	
			}
					
		}
	
	}
	
	/**
	* Generate like icon styles
	* @since 1.0.0
	*/
	public function process_like_icon_css($element, $data) {
		
		$source = (array) $this->base->getVar($data, 'source');
		
		if ($this->base->getVar($source, 'source_type') == 'post' && $this->base->getVar($source, 'post_content') == 'get_the_likes_number') {
			
			$rules['float']         = $this->base->getVar($source, 'like_icon_float');
			$rules['color']         = $this->base->getVar($source, 'like_icon_color');
			$rules['font-size']     = $this->base->getVar($source, 'like_icon_font-size');
			$rules['font-unit']     = $this->base->getVar($source, 'like_icon_font-size-unit');
			$rules['margin-unit']   = $this->base->getVar($source, 'like_icon_margin-unit');
			$rules['margin-top']    = $this->base->getVar($source, 'like_icon_margin-top');
			$rules['margin-left']   = $this->base->getVar($source, 'like_icon_margin-left');
			$rules['margin-bottom'] = $this->base->getVar($source, 'like_icon_margin-bottom');
			$rules['margin-right']  = $this->base->getVar($source, 'like_icon_margin-right');
			
			// icon position
			$this->skin_css .= '.'.$this->skin_slug.' .'.$element.' .to-heart-icon {'. "\r\n";
				$this->skin_css .= "\t". 'position: relative;'. "\r\n";
				$this->skin_css .= "\t". 'display: inline-block;'. "\r\n";
				$this->skin_css .= ($rules['float']) ? "\t". 'float: '.$rules['float'].';'. "\r\n" :  'float: left;'. "\r\n";
				$this->skin_css .= $this->get_margin($rules);
			$this->skin_css .= '}'. "\r\n";	
			
			// icon size
			if ($rules['font-size']) {
				$this->skin_css .= '.'.$this->skin_slug.' .'.$element.' .to-heart-icon svg {'. "\r\n";
					$this->skin_css .= ($rules['font-size'] && $rules['font-unit']) ? "\t". 'height: '.$rules['font-size'].$rules['font-unit'].';'. "\r\n" : null;
					$this->skin_css .= "\t". 'width: '.ceil($rules['font-size']*1.071).$rules['font-unit'].';'. "\r\n";
				$this->skin_css .= '}'. "\r\n";
			}
			
			// icon color
			if ($rules['color']) {
				$this->skin_css .= '.'.$this->skin_slug.' .'.$element.'.liked .to-heart-icon svg path,'. "\r\n";
				$this->skin_css .= '.'.$this->skin_slug.' .'.$element.' .to-heart-icon svg:hover path {'. "\r\n";
					$this->skin_css .= "\t". 'fill: '.$rules['color'].' !important;'. "\r\n";
					$this->skin_css .= "\t". 'stroke: '.$rules['color'].' !important;'. "\r\n";
				$this->skin_css .= '}'. "\r\n";	
			}
			
			// span number color
			$styles = $this->base->getVar($data, 'styles');
			$idle_state = $this->base->getVar($styles, 'idle_state');
			if ($this->base->getVar($idle_state, 'color-important')) {
				$this->skin_css .= '.tg-item.'.$this->skin_slug.' .'.$element.' span {'. "\r\n";
					$this->skin_css .= "\t". 'color: inherit;'. "\r\n";
				$this->skin_css .= '}'. "\r\n";	
			}
					
		}
	
	}
	
	/**
	* Generate terms styles
	* @since 1.0.0
	*/
	public function process_taxonomy_term_css($element, $data) {
		
		$source = (array) $this->base->getVar($data, 'source');
		
		if ($this->base->getVar($source, 'source_type') == 'post' && $this->base->getVar($source, 'post_content') == 'get_the_terms') {
			
			$rules['padding-unit']   = $this->base->getVar($source, 'terms_padding-unit');
			$rules['padding-top']    = $this->base->getVar($source, 'terms_padding-top');
			$rules['padding-left']   = $this->base->getVar($source, 'terms_padding-left');
			$rules['padding-bottom'] = $this->base->getVar($source, 'terms_padding-bottom');
			$rules['padding-right']  = $this->base->getVar($source, 'terms_padding-right');
			
			$this->skin_css .= '.'.$this->skin_slug.' .'.$element.' .tg-item-term {'. "\r\n";
				$this->skin_css .= "\t". 'position: relative;'. "\r\n";
				$this->skin_css .= "\t". 'display: inline-block;'. "\r\n";
				$this->skin_css .= $this->get_padding($rules, '');
			$this->skin_css .= '}'. "\r\n";	
		
		}
	
	}
	
	/**
	* Generate star rating styles (Woocommerce)
	* @since 1.0.0
	*/
	public function process_star_rating($element, $data) {
		
		$source = (array) $this->base->getVar($data, 'source');
		
		if ($this->base->getVar($source, 'source_type') == 'woocommerce' && $this->base->getVar($source, 'woocommerce_content') == 'get_product_rating') {
			
			$rules['font-size']    = $this->base->getVar($source, 'woo_star_font-size', 13);
			$rules['font-unit']    = $this->base->getVar($source, 'woo_star_font-size-unit', 'px');
			$rules['color-empty']  = $this->base->getVar($source, 'woo_star_color_empty', '#cccccc');
			$rules['color-fill']   = $this->base->getVar($source, 'woo_star_color_fill', '#e6ae48');
			
			$this->skin_css .= '.'.$this->skin_slug.' .'.$element.'.tg-item-rating a,'. "\r\n";
			$this->skin_css .= '.'.$this->skin_slug.' .'.$element.'.tg-item-rating .star-rating {'. "\r\n";
				$this->skin_css .= "\t". 'position: relative;'. "\r\n";
				$this->skin_css .= "\t". 'display: inline-block;'. "\r\n";
				$this->skin_css .= "\t". 'overflow: hidden;'. "\r\n";
				$this->skin_css .= "\t". 'vertical-align: top;'. "\r\n";
				$this->skin_css .= "\t". 'margin: 0;'. "\r\n";
			$this->skin_css .= '}'. "\r\n";	
			
			$this->skin_css .= '.'.$this->skin_slug.' .'.$element.'.tg-item-rating .star-rating span {'. "\r\n";
				$this->skin_css .= "\t". 'position: absolute;'. "\r\n";
				$this->skin_css .= "\t". 'display: block;'. "\r\n";
				$this->skin_css .= "\t". 'overflow: hidden;'. "\r\n";
				$this->skin_css .= "\t". 'left: 0;'. "\r\n";
				$this->skin_css .= "\t". 'top: 0;'. "\r\n";
				$this->skin_css .= "\t". 'bottom: 0;'. "\r\n";
				$this->skin_css .= "\t". 'margin: 0;'. "\r\n";
			$this->skin_css .= '}'. "\r\n";	
			
			$this->skin_css .= '.'.$this->skin_slug.' .'.$element.'.tg-item-rating .star-rating:before,'. "\r\n";
			$this->skin_css .= '.'.$this->skin_slug.' .'.$element.'.tg-item-rating .star-rating span:before {'. "\r\n";
				$this->skin_css .= "\t". 'position: relative;'. "\r\n";
				$this->skin_css .= "\t". 'display: inline-block;'. "\r\n";
				$this->skin_css .= "\t". 'float: left;'. "\r\n";
				$this->skin_css .= "\t". 'content: "\e636\e636\e636\e636\e636";'. "\r\n";
				$this->skin_css .= "\t". 'color: '.$rules['color-empty'].';'. "\r\n";
				$this->skin_css .= "\t". 'text-align: left;'. "\r\n";
				$this->skin_css .= "\t". 'white-space: nowrap;'. "\r\n";
				$this->skin_css .= "\t". 'font-family: "the_grid";'. "\r\n";
				$this->skin_css .= "\t". 'speak: none;'. "\r\n";
				$this->skin_css .= "\t". 'font-size: '.$rules['font-size'].$rules['font-unit'].';'. "\r\n";
				$this->skin_css .= "\t". 'line-height: '.$rules['font-size'].$rules['font-unit'].';'. "\r\n";
				$this->skin_css .= "\t". 'font-style: normal;'. "\r\n";
				$this->skin_css .= "\t". 'font-weight: normal;'. "\r\n";
				$this->skin_css .= "\t". 'font-variant: normal;'. "\r\n";
				$this->skin_css .= "\t". 'text-transform: none;'. "\r\n";
			$this->skin_css .= '}'. "\r\n";	
			
			$this->skin_css .= '.'.$this->skin_slug.' .'.$element.'.tg-item-rating .star-rating span:before {'. "\r\n";
				$this->skin_css .= "\t". 'color: '.$rules['color-fill'].';'. "\r\n";
			$this->skin_css .= '}'. "\r\n";	

		}
	
	}
	
	/**
	* Generate text rating styles (Woocommerce)
	* @since 1.0.0
	*/
	public function process_text_rating($element, $data) {
		
		$source = (array) $this->base->getVar($data, 'source');
		
		if ($this->base->getVar($source, 'source_type') == 'woocommerce' && $this->base->getVar($source, 'woocommerce_content') == 'get_product_text_rating') {
			
			$this->skin_css .= '.'.$this->skin_slug.' .'.$element.'.tg-item-text-rating * {'. "\r\n";
				$this->skin_css .= "\t". 'position: relative;'. "\r\n";
				$this->skin_css .= "\t". 'display: inline;'. "\r\n";
				$this->skin_css .= "\t". 'padding: 0;'. "\r\n";
				$this->skin_css .= "\t". 'margin: 0;'. "\r\n";
				$this->skin_css .= "\t". 'color: inherit !important;'. "\r\n";
				$this->skin_css .= "\t". 'font-size: inherit;'. "\r\n";
				$this->skin_css .= "\t". 'line-height: inherit;'. "\r\n";
				$this->skin_css .= "\t". 'font-style: inherit;'. "\r\n";
				$this->skin_css .= "\t". 'font-weight: inherit;'. "\r\n";
				$this->skin_css .= "\t". 'font-variant: inherit;'. "\r\n";
				$this->skin_css .= "\t". 'text-transform: inherit;'. "\r\n";	
			$this->skin_css .= '}'. "\r\n";	

		}
	
	}
	
	/**
	* Generate cart button (Woocommerce)
	* @since 1.0.0
	*/
	public function process_cart_button($element, $data) {
		
		$source = (array) $this->base->getVar($data, 'source');
		
		if ($this->base->getVar($source, 'source_type') == 'woocommerce' && $this->base->getVar($source, 'woocommerce_content') == 'get_product_cart_button') {
			
			$this->skin_css .= '.'.$this->skin_slug.' .'.$element.' a {'. "\r\n";
				$this->skin_css .= "\t". 'background: none !important;'. "\r\n";
				$this->skin_css .= "\t". 'border: none !important;'. "\r\n";
				$this->skin_css .= "\t". 'line-height: inherit !important;'. "\r\n";
			$this->skin_css .= '}'. "\r\n";	
			
			if ($this->base->getVar($source, 'woo_cart_icon')) {
				
				$this->skin_css .= '.'.$this->skin_slug.' .'.$element.' .added_to_cart.wc-forward {'. "\r\n";
					$this->skin_css .= "\t". 'position: absolute;'. "\r\n";
					$this->skin_css .= "\t". 'display: block;'. "\r\n";
					$this->skin_css .= "\t". 'top: 0;'. "\r\n";
					$this->skin_css .= "\t". 'right: 0;'. "\r\n";
					$this->skin_css .= "\t". 'bottom: 0;'. "\r\n";
					$this->skin_css .= "\t". 'left: 0;'. "\r\n";
					$this->skin_css .= "\t". 'opacity: 0 !important;'. "\r\n";
				$this->skin_css .= '}'. "\r\n";
				
				$this->skin_css .= '.'. $this->skin_slug .' i {'. "\r\n";
					$this->skin_css .= "\t". 'color: inherit !important;'."\r\n";
				$this->skin_css .= '}'. "\r\n";
			
				$this->skin_css .= '.'.$this->skin_slug.' .'.$element.' .added.add_to_cart_button i:before {'. "\r\n";	
					$this->skin_css .= "\t". 'content: "\e612";'. "\r\n";
				$this->skin_css .= '}'. "\r\n";	
			
			}
		
		}
	
	}
	
	/**
	* Generate full price (Woocommerce)
	* @since 1.0.0
	*/
	public function process_full_price($element, $data) {
		
		$source = (array) $this->base->getVar($data, 'source');
		
		if ($this->base->getVar($source, 'source_type') == 'woocommerce' && $this->base->getVar($source, 'woocommerce_content') == 'get_product_full_price') {
			
			$styles = (array) $this->base->getVar($data, 'styles');
			$idle_state = (array) $this->base->getVar($styles, 'idle_state');
			$color_important = $this->base->getVar($idle_state, 'color-important');
			$color = $this->base->getVar($idle_state, 'color');
			
			if ($color_important) {
				$this->skin_css .= '.'.$this->skin_slug.' .'.$element.' * {'. "\r\n";
					$this->skin_css .= "\t". 'color: '.$color.' !important;'. "\r\n";
				$this->skin_css .= '}'. "\r\n";
			}
			
			$hover_state = (array) $this->base->getVar($styles, 'hover_state');
			$color_important = $this->base->getVar($hover_state, 'color-important');
			$color = $this->base->getVar($hover_state, 'color');
			
			if ($color_important) {
				$this->skin_css .= '.'.$this->skin_slug.' .'.$element.':hover * {'. "\r\n";
					$this->skin_css .= "\t". 'color: '.$color.' !important;'. "\r\n";
				$this->skin_css .= '}'. "\r\n";
			}
		
		}
	
	}
	
	/**
	* Generate Global custom CSS
	* @since 1.0.0
	*/
	public function generate_global_custom_css() {
		
		$item = (array) $this->base->getVar($this->skin_settings, 'item');
		$custom_css = $this->base->getVar($item, 'global_css');
		$this->skin_css .= ($custom_css) ? $this->base->parse_css($custom_css) : null;
		
	}
	
	/**
	* Retrieve all css rules & value
	* @since 1.0.0
	*/
	public function process_css($element, $data) {
		
		// get idle & hover states
		$states = (array) $this->base->getVar($data, 'styles');
		
		// for each element state (idle & hover states)
		foreach ($states as $state => $rules) {
			
			// get css animation (hover)
			if ($state == 'hover_state') {
				$this->skin_css .= $this->get_animation_hover($element, $data);
			}
			
			// only if idle state or is hover and hover state
			if (is_array($rules) && (($this->base->getVar($states, 'is_hover') && $state == 'hover_state') || $state == 'idle_state' ) && !empty($rules)) {
			
				// prepare var for css rules
				$css_rules = null;
				
				// loop through each rule of the current state
				foreach ($rules as $rule => $value) {
					
					// if there is a value and the rule is valid
					if ($value != '' && !in_array($rule,$this->invalid_rules) && strpos($rule, '-important') == false) {
						$unit = (isset($rules[$rule.'-unit'])) ? $rules[$rule.'-unit'] : null;
						$important_rule  = (isset($rules[$rule.'-important']) && $rules[$rule.'-important']) ? ' !important' : null;
						$css_rules .= "\t". esc_attr($rule) .': '. str_replace(array('&#039;','&quot;') , "'", esc_attr($value)) . esc_attr($unit) . esc_attr($important_rule) .';'. "\r\n";	
					}
				
				}
				
				// only for idle_state
				if ($state == 'idle_state') {
					$css_rules .= $this->get_display($rules);
					$css_rules .= $this->get_positions($rules);
					$css_rules .= $this->get_zindex($rules, $element);
					$css_rules .= $this->get_float($rules);
					$css_rules .= $this->get_sizes($rules);
					$css_rules .= $this->get_margin($rules, $data);
					$css_rules .= $this->get_padding($rules, $data);
					$css_rules .= $this->get_cursor($data);
				}
				
				// generate special css rules (shorthands)				
				$css_rules .= $this->get_border_width($rules);
				$css_rules .= $this->get_border_radius($rules);
				$css_rules .= $this->get_opacity($rules, $state, $this->base->getVar($states, 'is_hover'));
				$css_rules .= $this->get_text_shadow($rules);
				$css_rules .= $this->get_box_shadow($rules);
				$css_rules .= $this->get_background_image($rules);
				$css_rules .= $this->get_custom_rules($rules);
				
				// generate transition for animation
				if ($state == 'idle_state') {
					$css_rules .= $this->get_animation_idle($element, $data);
				}

				// if current element/layer have some css rules
				if ($css_rules) {
					
					
						
					// pseudo class for hover css rules
					$pseudo = ($state == 'hover_state') ? ':hover' : null;
					
					// if element is media content holder
					$selector = ($element == 'tg-item-media-content') ? 'div.'.$element : '.'.$element;
					
					// if element is media inner
					$not_with = null;
					if ($element == 'tg-item-media-inner') {
						$skin_style  = (isset($this->skin_settings['item']['layout']['skin_style'])) ? $this->skin_settings['item']['layout']['skin_style'] : null;
						$not_element = ($skin_style == 'masonry') ? ':not(.no-media-poster)' : null;
						$selector    = '.tg-item-media-inner'.$not_element.' > *';
						$not_with    = ':not(.tg-force-play):not(.tg-is-playing)';	
					}
					
					// exception for the overlay (allows to apply over styles from media holder)
					if ($element == 'tg-item-overlay' && $state == 'hover_state') {
						$selector = '.tg-item-media-holder:hover .tg-item-overlay';
						$pseudo = '';
					}
					
					// declare css class+pseudo (if hover)
					$prefix_class = ($this->skin_slug) ? '.'.$this->skin_slug.$not_with.' ' : null;
					
					$this->skin_css .= $prefix_class.$selector.$pseudo.' {'. "\r\n";
						$this->skin_css .= $css_rules;
					$this->skin_css .= '}'. "\r\n";
					
				}
				
				// get font (google font)
				$this->get_font($rules);
			
			}
			
		}
		
		// return css for manual call
		return $this->skin_css;
				
	}

	/**
	* Format css a tags
	* @since 1.0.0
	*/
	public function reset_css_element() {
		
		$this->skin_css .= '.'. $this->skin_slug .' a:not([class*="tg-element-"]),'. "\r\n";
		$this->skin_css .= '.'. $this->skin_slug .' a:not([class*="tg-element-"]):active,'. "\r\n";
		$this->skin_css .= '.'. $this->skin_slug .' a:not([class*="tg-element-"]):focus,'. "\r\n";
		$this->skin_css .= '.'. $this->skin_slug .' [class*="tg-element-"] *:not(del) {'. "\r\n";
			$this->skin_css .= "\t". 'margin: 0;'. "\r\n";
			$this->skin_css .= "\t". 'padding: 0;'. "\r\n";
			$this->skin_css .= "\t". 'color: inherit !important;'."\r\n";
			$this->skin_css .= "\t". 'text-align: inherit;'."\r\n";
			$this->skin_css .= "\t". 'font-size: inherit;'."\r\n";
			$this->skin_css .= "\t". 'font-style: inherit;'."\r\n";
			$this->skin_css .= "\t". 'line-height: inherit;'."\r\n";
			$this->skin_css .= "\t". 'font-weight: inherit;'."\r\n";
			$this->skin_css .= "\t". 'text-transform: inherit;'."\r\n";
			$this->skin_css .= "\t". 'text-decoration: inherit;'."\r\n";
			$this->skin_css .= "\t". '-webkit-box-shadow: none;'. "\r\n";
			$this->skin_css .= "\t". 'box-shadow: none;'. "\r\n";
			$this->skin_css .= "\t". 'border: none;'. "\r\n";
		$this->skin_css .= '}'. "\r\n";

	}
	
	/**
	* Vertical Alignment css
	* @since 1.0.0
	*/
	public function vertical_alignment() {
		
		$this->skin_css .= '.'. $this->skin_slug .' [class*="tg-element-"],'. "\r\n";
		$this->skin_css .= '.'. $this->skin_slug .' .tg-item-overlay,'. "\r\n";
		$this->skin_css .= '.'. $this->skin_slug .' .tg-center-holder,'. "\r\n";
		$this->skin_css .= '.'. $this->skin_slug .' .tg-center-inner > * {'. "\r\n";
		$this->skin_css .= "\t". 'vertical-align: middle;'. "\r\n";
		$this->skin_css .= '}'. "\r\n";

	}
	
	/**
	* Get css display
	* @since 1.0.0
	*/
	public function get_display($rules) {
			
		// get important rule
		$important = ($this->base->getVar($rules, 'display-important')) ? ' !important' : null;
		
		// get position
		$position  = $this->base->getVar($rules, 'position');
		
		// get display
		$display   = $this->base->getVar($rules, 'display');
		$display   = ($position != 'absolute') ? $display : 'block';
			
		return ($display) ? "\t". 'display: '. esc_attr($display . $important).';'. "\r\n" : null;
				
	}
	
	/**
	* Get css absolute positions
	* @since 1.0.0
	*/
	public function get_positions($rules) {
		
		// only if position absolute (not exist for relative)
		if ($this->base->getVar($rules, 'position') == 'absolute') {
			
			// get important rule
			$important = ($this->base->getVar($rules, 'positions-important')) ? ' !important' : null;
			// get the unit (px/em/%)
			$unit  = ($this->base->getVar($rules, 'positions-unit')) ? $rules['positions-unit'].$important : 'px'.$important;
			$float = ($unit == '%') ? 2 : 0;
			
			// get each position value
			$po_u  = (isset($rules['top']) && $rules['top']) ? $unit : null;
			$po_t  = (isset($rules['top']) && is_numeric($rules['top'])) ? "\t". 'top: '. esc_attr(number_format($rules['top'], $float, '.', ''). $po_u) .';'. "\r\n" : null;
			$po_u  = (isset($rules['right']) && $rules['right']) ? $unit : null;
			$po_r  = (isset($rules['right']) && is_numeric($rules['right'])) ? "\t". 'right: '. esc_attr(number_format($rules['right'], $float, '.', ''). $po_u) .';'. "\r\n" : null;
			$po_u  = (isset($rules['bottom']) && $rules['bottom']) ? $unit : null;
			$po_b  = (isset($rules['bottom']) && is_numeric($rules['bottom'])) ? "\t". 'bottom: '. esc_attr(number_format($rules['bottom'], $float, '.', ''). $po_u) .';'. "\r\n" : null;
			$po_u  = (isset($rules['left']) && $rules['left']) ? $unit : null;
			$po_l  = (isset($rules['left']) && is_numeric($rules['left'])) ? "\t". 'left: '. esc_attr(number_format($rules['left'], $float, '.', ''). $po_u) .';'. "\r\n" : null;
			
			// return all positions
			return $po_t.$po_l.$po_b.$po_r;
			
		}
			
	}
	
	/**
	* Get css z-index
	* @since 1.0.0
	*/
	public function get_zindex($rules, $element) {
			
		// get important rule
		$important = ($this->base->getVar($rules, 'z-index-important')) ? ' !important' : null;
		
		// get position
		$position  = $this->base->getVar($rules, 'position');
		
		// get z-index
		$zindex   = $this->base->getVar($rules, 'z-index');
		
		if ($position == 'absolute' && strpos($element, 'tg-element-') !== false) {
			return "\t". 'z-index: 3'.esc_attr($important).';'. "\r\n";
		} else if (strpos($element, 'tg-element-') == false && $zindex) {
			return "\t". 'z-index: '.esc_attr($zindex.$important).';'. "\r\n";
		}
				
	}
	
	/**
	* Get css float
	* @since 1.0.0
	*/
	public function get_float($rules) {
		
		// only if position absolute (not exist for relative)
		if ($this->base->getVar($rules, 'display') == 'inline-block') {
			
			// get important rule
			$important = ($this->base->getVar($rules, 'float-important')) ? ' !important' : null;
			
			// get float value
			return (isset($rules['float']) && $rules['float'])  ? "\t". 'float: '. esc_attr($rules['float'] . $important).';'. "\r\n" : '';
			
		}
			
	}
	
	/**
	* Get css sizes
	* @since 1.0.0
	*/
	public function get_sizes($rules) {
		
		// get important rule
		$width_important  = ($this->base->getVar($rules, 'width-important'))  ? ' !important' : null;
		$height_important = ($this->base->getVar($rules, 'height-important')) ? ' !important' : null;
		
		// get the unit (px/em/%)
		$width_unit  = ($this->base->getVar($rules, 'width-unit'))  ? $rules['width-unit'].$width_important : 'px'.$width_important;
		$height_unit = ($this->base->getVar($rules, 'height-unit')) ? $rules['height-unit'].$height_important : 'px'.$height_important;
			
		// get each position value
		$sizes  = ($this->base->getVar($rules, 'width'))  ? "\t". 'width: '. esc_attr((int) $rules['width'] . $width_unit).';'. "\r\n" : null;
		$sizes .= ($this->base->getVar($rules, 'width'))  ? "\t". 'min-width: '. esc_attr((int) $rules['width'] . $width_unit).';'. "\r\n" : null;
		$sizes .= ($this->base->getVar($rules, 'height')) ? "\t". 'height: '. esc_attr((int) $rules['height'] . $height_unit).';'. "\r\n" : null;
		$sizes .= ($this->base->getVar($rules, 'height')) ? "\t". 'min-height: '. esc_attr((int) $rules['height'] . $height_unit).';'. "\r\n" : null;
		return $sizes;
			
	}
	
	/**
	* Get css margins
	* @since 1.0.0
	*/
	public function get_margin($rules, $data = '') {
		
		$mg_css = $this->base->get_css_directions(
			'margin-top',
			'margin-right',
			'margin-bottom',
			'margin-left',
			'margin-unit',
			'margin-important',
			$rules
		);
		
		$source  = (array) $this->base->getVar($data, 'source');
		$content = $this->base->getVar($source, 'post_content');
		$p_h_tag = $this->base->getVar($source, 'source_type') == 'post' && ($content == 'get_the_title' || $content == 'get_the_excerpt');
		$mg_css  = (empty($mg_css) && $p_h_tag) ? 0 : $mg_css;
		
		return ($mg_css || $p_h_tag) ? "\t". 'margin: '.$mg_css.';'. "\r\n" : null;

	}
	
	/**
	* Get css paddings
	* @since 1.0.0
	*/
	public function get_padding($rules, $data = '') {
				
		$pd_css = $this->base->get_css_directions(
			'padding-top',
			'padding-right',
			'padding-bottom',
			'padding-left',
			'padding-unit',
			'padding-important',
			$rules
		);
		
		$source  = (array) $this->base->getVar($data, 'source');
		$content = $this->base->getVar($source, 'post_content');
		$p_h_tag = $this->base->getVar($source, 'source_type') == 'post' && ($content == 'get_the_title' || $content == 'get_the_excerpt');
		$pd_css  = (empty($pd_css) && $p_h_tag) ? 0 : $pd_css;
		
		return ($pd_css || $p_h_tag) ? "\t". 'padding: '.$pd_css.';'. "\r\n" : null;
	
	}
	
	/**
	* Get css border-width
	* @since 1.0.0
	*/
	public function get_border_width($rules) {
				
		$border_width = $this->base->get_css_directions(
			'border-top',
			'border-right',
			'border-bottom',
			'border-left',
			'border-unit',
			'border-important',
			$rules
		);
		
		$border_style = ($border_width) ? $this->get_border_style($rules) : null;
		$border_width = ($border_width) ? "\t". 'border-width: '.$border_width.';'. "\r\n" : null;
		
		return $border_width.$border_style;
	
	}
	
	/**
	* Get css border-style
	* @since 1.0.0
	*/
	public function get_border_style($rules) {
		
		// get important rule
		$important = ($this->base->getVar($rules, 'border-style-important')) ? ' !important' : null;
		
		// get border style
		$border_style = ($this->base->getVar($rules, 'border-style'))   ? $rules['border-style'] : null;
		
		if ($border_style) {
			return  "\t". 'border-style: '.esc_attr($border_style.$important).';'. "\r\n";
		}
		
	}
	
	/**
	* Get css border-radius
	* @since 1.0.0
	*/
	public function get_border_radius($rules) {
				
		$br_css = $this->base->get_css_directions(
			'border-top-left-radius',
			'border-top-right-radius',
			'border-bottom-right-radius',
			'border-bottom-left-radius',
			'border-radius-unit',
			'border-radius-important',
			$rules
		);
		return ($br_css) ? "\t". 'border-radius: '.$br_css.';'. "\r\n" : null;

	}

	/**
	* Get css box shadow
	* @since 1.0.0
	*/
	public function get_box_shadow($rules) {
			
		// get important rule
		$important = ($this->base->getVar($rules, 'box-shadow-important')) ? ' !important' : null;
		
		// get the unit (px/em/%)
		$sd_un   = ($this->base->getVar($rules, 'box-shadow-unit')) ? $rules['box-shadow-unit'] : 'px';
		$sdi_un  = ($this->base->getVar($rules, 'box-shadow-inset-unit')) ? $rules['box-shadow-inset-unit'] : 'px';
		
		// get the colors
		$sd_co = $this->base->getVar($rules, 'box-shadow-color', 'rgba(0,0,0,0)');
		$sdi_co = $this->base->getVar($rules, 'box-shadow-inset-color', 'rgba(0,0,0,0)');
		
		$sd_css = $this->base->get_css_directions(
			'box-shadow-horizontal',
			'box-shadow-vertical',
			'box-shadow-blur',
			'box-shadow-size',
			'box-shadow-unit',
			null,
			$rules,
			4,
			false
		);
		$sd_css = ($sd_css) ? $sd_css.' '.$sd_co : null;

		$sdi_css = $this->base->get_css_directions(
			'box-shadow-inset-horizontal',
			'box-shadow-inset-vertical',
			'box-shadow-inset-blur',
			'box-shadow-inset-size',
			'box-shadow-inset-unit',
			null,
			$rules,
			4,
			false
		);
		$sdi_css = ($sdi_css) ? 'inset '.$sdi_css.' '.$sdi_co : null;
		$sdi_css = ($sd_css && $sdi_css)  ? ', '.$sdi_css : $sdi_css;
		
		if ($sd_css || $sdi_css) {
			$css_rule  = "\t". '-webkit-box-shadow:'.$sd_css.$sdi_css.$important.';'. "\r\n";
			$css_rule .= "\t". '-moz-box-shadow:'.$sd_css.$sdi_css.$important.';'. "\r\n";
			$css_rule .= "\t". 'box-shadow:'.$sd_css.$sdi_css.$important.';'. "\r\n";
			return $css_rule;
		}
		
	}
	
	/**
	* Get css text shadow
	* @since 1.0.0
	* @modified 2.1.2
	*/
	public function get_text_shadow($rules) {
		
		// get important rule
		$important = ($this->base->getVar($rules, 'text-shadow-important')) ? ' !important' : null;
		
		// get the colors
		$ts_co = $this->base->getVar($rules, 'text-shadow-color', '');
					
		$ts_css = $this->base->get_css_directions(
			'text-shadow-horizontal',
			'text-shadow-vertical',
			'text-shadow-blur',
			null,
			'text-shadow-unit',
			null,
			$rules,
			3
		);

		return ($ts_css) ? "\t". 'text-shadow: '.$ts_css.' '.$ts_co.$important.';'. "\r\n" : null;
			
	}

	/**
	* Get css opacity
	* @since 1.0.0
	*/
	public function get_opacity($rules, $state) {
				
		if ($this->base->getVar($rules, 'opacity')) {
			
			// if opacity < 1 & idle state or if opacity & hover state
			if (($state == 'idle_state' && $rules['opacity'] < 1) || ($state != 'idle_state') || $this->base->getVar($rules, 'opacity-important')) {
				
				// get important rule
				$important = ($this->base->getVar($rules, 'opacity-important') || $state == 'hover_state') ? ' !important' : null;
				return "\t". 'opacity: '.esc_attr($rules['opacity'].$important).';'. "\r\n";
				
			}
		}
	
	}
	
	/**
	* Get css background image
	* @since 1.0.0
	*/
	public function get_background_image($rules) {
		
		if ($this->base->getVar($rules, 'background-image')) {
			
			// get important rule
			$important = ($this->base->getVar($rules, 'background-image-important')) ? ' !important' : null;
			return ($rules['background-image']) ? "\t". 'background-image: url('.esc_url($rules['background-image']).')'.esc_attr($important).';'. "\r\n" : null;
			
		}
		
	}
	
	/**
	* Get css cursor pointer
	* @since 1.0.0
	*/
	public function get_cursor($data) {
		
		$source       = (array) $this->base->getVar($data, 'source');
		$source_type  = $this->base->getVar($source ,'source_type');
		
		if ($source_type == 'media_button') {
			return "\t". 'cursor: pointer;'. "\r\n";
		}
	
	}
	
	/**
	* Get custom css rules
	* @since 1.0.0
	*/
	public function get_custom_rules($rules) {
		
		// if there are custom rules
		if ($this->base->getVar($rules, 'custom-rules')) {

			// Strip all line endings and both single and multiline comments
			$data = preg_replace('/\/\*.+?\*\//s', '', $rules['custom-rules']);
			
			// transform to array
			$data = explode(';', $data);
			
			if ($data) {
			
				$custom_css = null;
				
				foreach ($data as $rule) {
					// separate css rule from css value
					$rule = explode(':', $rule);
					if (isset($rule[0]) && isset($rule[1])) {
						$custom_css .= "\t". esc_attr(preg_replace('/\s+/', '', $rule[0])) .': '. esc_attr($rule[1]) .';'. "\r\n";
					}
				}
				
				return $custom_css;
			
			}
		
		}
		
	}
	
	/**
	* Get animation data
	* @since 1.0.0
	*/
	public function get_animation_data($data) {
		
		// get animation settings
		$animation  = $this->base->getVar($data, 'animation');
		
		// prepare animation settings
		$easing     = $this->base->getVar($animation, 'animation_easing');
		$easing     = ($easing == 'custom-easing') ? $this->base->getVar($animation, 'animation_custom_easing', 'ease') : $easing;
		$easing     = (isset($this->timing_functions[$easing])) ? $this->timing_functions[$easing] : $easing;
		$duration   = $this->base->getVar($animation, 'animation_duration');
		$duration   = ($duration) ? $duration.'ms' : 0;
		$delay      = $this->base->getVar($animation, 'animation_delay');
		$delay      = ($delay) ? ' '.$delay.'ms' : '';
		$translateu = $this->base->getVar($animation, 'translate-unit', 'px');
		$translateX = $this->base->getVar($animation, 'translatex', 0);
		$translateY = $this->base->getVar($animation, 'translatey', 0);
		$translateZ = $this->base->getVar($animation, 'translatez', 0);
		$rotateX    = $this->base->getVar($animation, 'rotatex');
		$rotateY    = $this->base->getVar($animation, 'rotatey');
		$rotateZ    = $this->base->getVar($animation, 'rotatez');
		$scaleX     = isset($animation['scalex']) ? $animation['scalex'] : null;
		$scaleY     = isset($animation['scaley']) ? $animation['scaley'] : null;
		$scaleZ     = isset($animation['scalez']) ? $animation['scalez'] : null;
		$skewX      = $this->base->getVar($animation, 'skewx');
		$skewY      = $this->base->getVar($animation, 'skewy');
		$originX    = isset($animation['originx']) ? $animation['originx'] : null;
		$originY    = isset($animation['originy']) ? $animation['originy'] : null;
		$originZ    = isset($animation['originz']) ? $animation['originz'] : null;
		$perspective= isset($animation['perspective']) ? $animation['perspective'] : null;
		
		// build animation (transformed)
		$t_rotateX    = ($rotateX) ? ' rotateX('.$rotateX.'deg)' : null;
		$t_rotateY    = ($rotateY) ? ' rotateY('.$rotateY.'deg)' : null;
		$t_rotateZ    = ($rotateZ) ? ' rotateZ('.$rotateZ.'deg)' : null;
		$t_skewX      = ($skewX) ? ' skewX('.$skewX.'deg)' : null;
		$t_skewY      = ($skewY) ? ' skewY('.$skewY.'deg)' : null;
		$perspective  = ($perspective) ? 'perspective('.$perspective.'px) ' : null;
		$t_translate  = ($translateX || $translateY || $translateZ) ? 'translate3d('.$translateX.$translateu.','.$translateY.$translateu.','.$translateZ.'px)' : null;
		if ((is_numeric($scaleX) || is_numeric($scaleY) || is_numeric($scaleZ))) {
			$t_scale3d = ' scale3d('.
				(is_numeric($scaleX) && $scaleX >= 0 ? $scaleX : 1).','.
				(is_numeric($scaleY) && $scaleY >= 0 ? $scaleY : 1).','.
				(is_numeric($scaleZ) && $scaleZ >= 0 ? $scaleZ : 1).')';
		} else {
			$t_scale3d = null;
		}
		if (is_numeric($originX) || is_numeric($originY) || is_numeric($originZ)) {
			$t_origin = ($originX ? $originX.'% ' : 0 .' ').
						($originY ? $originY.'% ' : 0 .' ').
						($originZ ? $originZ.'px' : 0);
		} else {
			$t_origin = null;
		}
		
		
		// build animation (initial)
		$i_rotateX   = ($t_rotateX) ? ' rotateX(0)' : null;
		$i_rotateY   = ($t_rotateY) ? ' rotateY(0)' : null;
		$i_rotateZ   = ($t_rotateZ) ? ' rotateZ(0)' : null;
		$i_skewX     = ($t_skewX) ? ' skewX(0)' : null;
		$i_skewY     = ($t_skewY) ? ' skewY(0)' : null;
		$i_translate = ($t_translate) ? 'translate3d(0,0,0)' : null;
		$i_scale3d   = ($t_scale3d) ? ' scale3d(1,1,1)' : null;

		// build complete transform property for transformed and initial position
		return array(
			'transformed' => $perspective.$t_translate.$t_scale3d.$t_rotateX.$t_rotateY.$t_rotateZ.$t_skewX.$t_skewY,
			'initial'     => $perspective.$i_translate.$i_scale3d.$i_rotateX.$i_rotateY.$i_rotateZ.$i_skewX.$i_skewY,
			'transition'  => ($duration) ? $duration.' '.$easing.$delay : null,
			'origin'      => ($t_origin) ? $t_origin : null
		);
	
	}
	
	/**
	* Get css animation/transition idle state
	* @since 1.0.0
	*/
	public function get_animation_idle($element, $data) {
		
		$states    = (array) $this->base->getVar($data, 'styles');
		$is_hover  = $this->base->getVar($states, 'is_hover');
		$animation = $this->base->getVar($data, 'animation');
		
		if ($animation) {
			
			// set main data
			$name     = $this->base->getVar($animation, 'animation_name');
			$type     = $this->base->getVar($animation, 'animation_type', 'show');
			$position = $this->base->getVar($animation, 'animation_position', 'from');
			$from     = $this->base->getVar($animation, 'animation_from', 'item');
				
			// get css transform properties
			$animation_data = $this->get_animation_data($data);
			$transformed    = (isset($animation_data['transformed'])) ? $animation_data['transformed'] : null;
			$initial        = (isset($animation_data['initial'])) ? $animation_data['initial'] : null;
			$transition     = (isset($animation_data['transition'])) ? $animation_data['transition'] : null;
			$origin         = (isset($animation_data['origin'])) ? $animation_data['origin'] : null;
				
			// if a transformation exists
			if (($transformed && $initial) || $name == 'fade_in') {
			
				$opacity    = ($type == 'show') ? 0 : 1;
				$vendors    = array('-webkit-', '-moz-', '-ms-', '');
				$important  = ($element == 'tg-item-overlay') ? ' !important' : null;
				$visibility = ($type == 'show') ? 'hidden'  : 'visible';
				
				// set transform properties
				$transform  = ($position == 'from') ? $transformed : $initial;
				
				$animation  = (in_array($type, array('show','hide'))) ? "\t". 'opacity: '.esc_attr($opacity).';'. "\r\n" : null;
				$animation .= (in_array($type, array('show','hide'))) ? "\t". 'visibility: '. esc_attr($visibility) .';'. "\r\n" : null;
				
				if ($transition) {
					foreach ($vendors as $vendor) {
						$animation .= "\t". $vendor.'transition: all '.esc_attr($transition.$important).';'. "\r\n";
					}
				}
					
				if ($origin) {
					foreach ($vendors as $vendor) {
						$animation .= "\t". $vendor.'transform-origin: '.esc_attr($origin).';'. "\r\n";
					}
				}
					
				if ($name != 'fade_in' && $transform) {
					foreach ($vendors as $vendor) {
						$animation .= "\t". $vendor.'transform: '.esc_attr($transform).';'. "\r\n";
					}
				}
				
				return ($animation) ? $animation : '';
			
			}
		
		}
		
		// add transition if hover state activated
		if ($is_hover) {
			
			$vendors    = array('-webkit-', '-moz-', '-ms-', '');
			$animation_data = $this->get_animation_data($data);
			$transition = (isset($animation_data['transition'])) ? $animation_data['transition'] : null;
			
			if ($transition) {
				
				$animation = null;
				
				foreach ($vendors as $vendor) {
					$animation .= "\t". $vendor.'transition: all '.esc_attr($transition).';'. "\r\n";
				}
				
				return $animation;
			
			}
		
		}
	
	}
	
	/**
	* Get css animation hover state
	* @since 1.0.0
	*/
	public function get_animation_hover($element, $data) {
		
		$animation = $this->base->getVar($data, 'animation');
		
		if ($animation) {
			
			// set main data
			$name     = $this->base->getVar($animation, 'animation_name');
			$type     = $this->base->getVar($animation, 'animation_type', 'show');
			$position = $this->base->getVar($animation, 'animation_position', 'from');
			$from     = $this->base->getVar($animation, 'animation_from', 'item');
				
			// get css transform properties
			$animation_data = $this->get_animation_data($data);
			$transformed    = (isset($animation_data['transformed'])) ? $animation_data['transformed'] : null;
			$initial        = (isset($animation_data['initial'])) ? $animation_data['initial'] : null;
			
			// if a transformation exists
			if (($transformed && $initial) || $name == 'fade_in') {
				
				$selector_hover = array(
					'item' => ':hover',
					'media' => ' .tg-item-media-holder:hover',
					'top-content' => ' .tg-item-content-holder[data-position="top"]:hover',
					'bottom-content' => ' .tg-item-content-holder[data-position="bottom"]:hover'
				);
				
				if ($from == 'parent') {
					$from  = (isset($this->skin_settings['elements']['top-content-holder'][$element]))    ? 'top-content' : $from;
					$from  = (isset($this->skin_settings['elements']['bottom-content-holder'][$element])) ? 'bottom-content' : $from;
					$from  = (isset($this->skin_settings['elements']['media-holder'][$element]))          ? 'media' : $from;
					$from  = (isset($this->skin_settings['elements']['media-holder-top'][$element]))      ? 'media' : $from;
					$from  = (isset($this->skin_settings['elements']['media-holder-center'][$element]))   ? 'media' : $from;
					$from  = (isset($this->skin_settings['elements']['media-holder-bottom'][$element]))   ? 'media' : $from;
				}
				$selector_hover = (isset($selector_hover[$from])) ? $selector_hover[$from] : ':hover';
			
				$opacity    = ($type == 'show') ? 1 : 0;
				$vendors    = array('-webkit-', '-moz-', '-ms-', '');
				$important  = ($element == 'tg-item-overlay') ? ' !important' : null;
				$visibility = ($type == 'show') ? 'visible'  : 'hidden';
				$not_with   = (strpos($element, 'tg-item-overlay') !== false || $element == 'tg-item-media-inner') ? ':not(.tg-force-play):not(.tg-is-playing)' : null;				
				
				// set transform properties
				$transform = ($position == 'from') ? $initial : $transformed;
				
				$animation  = (in_array($type, array('show','hide'))) ? "\t". 'opacity: '.esc_attr($opacity).';'. "\r\n" : null;
				$animation .= (in_array($type, array('show','hide'))) ? "\t". 'visibility: '. esc_attr($visibility) .';'. "\r\n" : null;
				
				if ($name != 'fade_in' && $transform) {
					foreach ($vendors as $vendor) {
						$animation .= "\t". $vendor.'transform: '.esc_attr($transform).';'. "\r\n";
					}
				}
				
				if ($animation) {
					
					$skin_style   = (isset($this->skin_settings['item']['layout']['skin_style'])) ? $this->skin_settings['item']['layout']['skin_style'] : null;
					$not_element  = ($skin_style == 'masonry') ? ':not(.no-media-poster)' : null;
					
					$element = ($element == 'tg-item-media-inner') ? 'tg-item-media-inner'.$not_element.' > *' : $element;
					
					$css_rules  = ($element) ? '.'. $this->skin_slug. $not_with . $selector_hover .' .'. $element.' {'. "\r\n" : null;
						$css_rules .= $animation;
					$css_rules .= ($element) ? '}'. "\r\n" : null;
					
					return $css_rules;
					
				}
			
			}
		
		}
	
	}
		
	/**
	* Get font from css
	* @since 1.7.0
	*/
	public function get_font($rules) {
		
		$font_family = ($this->base->getVar($rules, 'font-family')) ? $rules['font-family'] : null;
		$font_weight = ($this->base->getVar($rules, 'font-weight')) ? $rules['font-weight'] : null;
		$font_subset = ($this->base->getVar($rules, 'font-subset')) ? $rules['font-subset'] : null;

		if ($font_family && isset($this->google_font[$font_family])) {
			if ($font_weight) {
				$this->skin_fonts['google_font'][$font_family]['variants'][] = $font_weight;
			}
			if ($font_subset) {
				$this->skin_fonts['google_font'][$font_family]['subsets'][]  = $font_subset;
			}
		}
	
	}
	
	/**
	* Get css animation
	* @since 1.0.0
	*/
	public function reset_css() {
		$this->skin_css = null;
	}
	
}