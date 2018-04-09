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

class The_Grid_Styles {

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
	* skin fonts
	*
	* @since 1.0.0
	* @access protected
	*
	* @var array
	*/
	protected $skin_fonts = array();
	
	/**
	* Base class
	*
	* @since 1.0.0
	* @access private
	*
	* @var objet
	*/
	private $base;
	
	/**
	* initialization
	* @since 1.0.0
	*/
	public function __construct($grid_data) {
		
		$this->grid_data = $grid_data;
		
	}
	
	/**
	* Processing styles
	* @since 1.0.0
	*/
	public function styles_processing() {
		
		// set the grid base helper class
		$this->base = new The_Grid_Base();
	
		// defined the grid ID for css
		$this->grid_data['css_id'] = '#'.$this->grid_data['ID'];
		
		// retrive all styles
		$styles  = $this->main_wrapper_style();
		$styles .= $this->main_background_style();
		$styles .= $this->navigation_style();
		$styles .= $this->areas_style();
		$styles .= $this->item_style();
		$styles .= $this->item_skin();
		$styles .= $this->item_color();
		$styles .= $this->custom_css();
		
		// compress css rules
		$styles = $this->base->compress_css($styles);
		
		// reassign grid css for later (grid layout)
		$this->grid_data['grid_css'] = $styles;
		
		// get fonts families
		$this->grid_data['grid_fonts'] = $this->font_family();

		// return new grid data
		return $this->grid_data;
		
	}
	
	/**
	* Process main wrapper styles
	* @since 1.0.0
	*/
	public function main_wrapper_style() {
		
		$marg    = null;
		$styles  = null;
		$grid_ID = $this->grid_data['css_id'];
		$wrapper = 'wrap_marg_';
		$margins = array('top','bottom','left','right');
		
		foreach($margins as $margin) {
			
			$value = $this->grid_data[$wrapper.$margin];
			if ((int) $value) {
				$marg .= 'margin-'.$margin.':'.esc_attr($value).'px;';
			}
			
		}
		
		if (!empty($marg)) {
			$styles = $grid_ID.'{'.$marg.'}';
		}
		
		return $styles;
		
	}
	
	/**
	* Process main background color
	* @since 1.0.0
	*/
	public function main_background_style() {
		
		$styles  = null;
		$grid_ID = $this->grid_data['css_id'];
		$grid_bg = $this->grid_data['grid_background'];
		$grid_layout = $this->grid_data['layout'];
		
		if (!empty($grid_bg)) {
			
			if ($grid_layout == 'horizontal') {
				$styles .= $grid_ID.' .tg-grid-slider{background:'.esc_attr($grid_bg).';}';
			} else {
				$styles .= $grid_ID.' .tg-grid-holder:before{content:"";background-color:'.esc_attr($grid_bg).';}';
			}
			
		}
		
		return $styles;
		
	}
	
	/**
	* Process navigation style
	* @since 1.0.0
	*/
	public function navigation_style() {
		
		$styles  = null;
		
		// navigation styles
		$grid_ID          = $this->grid_data['css_id'];
		$navigation_name  = $this->grid_data['navigation_style'];
		$nav_text_color   = $this->grid_data['navigation_color'];
		$nav_accent_color = $this->grid_data['navigation_accent_color'];
		$nav_background   = $this->grid_data['navigation_bg'];
		$nav_accent_bg    = $this->grid_data['navigation_accent_bg'];
		$brightness = $this->base->brightness($nav_text_color);
		$nav_border_color = ($brightness == 'bright') ? $this->base->HEX2RGB($nav_text_color,$alpha=0.6) : $this->base->HEXLighter($nav_text_color,$ratio=3);
		
		// navigation styles
		global $tg_nav_colors;
		$tg_nav_colors['css_ID']       = $grid_ID;
		$tg_nav_colors['text_color']   = esc_attr($nav_text_color);
		$tg_nav_colors['accent_color'] = esc_attr($nav_accent_color);
		$tg_nav_colors['border_color'] = esc_attr($nav_border_color);
		$tg_nav_colors['background']   = esc_attr($nav_background);
		$tg_nav_colors['accent_background'] = esc_attr($nav_accent_bg);
		
		$navigation_base = new The_Grid_Navigation_Skin();
		$navigation_skin = $navigation_base->$navigation_name();
		$styles .= $navigation_skin['css'];
		
		// navigation colors
		$styles .= $grid_ID.' .tg-nav-color,
				   '.$grid_ID.' .tg-search-icon:hover:before,
				   '.$grid_ID.' .tg-search-icon:hover input,
				   '.$grid_ID.' .tg-disabled:hover .tg-icon-left-arrow,
				   '.$grid_ID.' .tg-disabled:hover .tg-icon-right-arrow,
				   '.$grid_ID.' .tg-dropdown-title.tg-nav-color:hover {
					   color:'.esc_attr($nav_text_color).';
				   }
				   '.$grid_ID.' input.tg-search:hover {
					   color:'.esc_attr($nav_text_color).' !important;
				   }
				   '.$grid_ID.' input.tg-search::-webkit-input-placeholder {
					   color:'.esc_attr($nav_text_color).';
				   }
				   '.$grid_ID.' input.tg-search::-moz-placeholder {
					   color:'.esc_attr($nav_text_color).';
					   opacity: 1;
				   }
				   '.$grid_ID.' input.tg-search:-ms-input-placeholder {
					   color:'.esc_attr($nav_text_color).';
				   }';
		
		// dropdown list colors
		$dropdown_txt_color  = $this->grid_data['dropdown_color'];
		$dropdown_txt_colorA = $this->grid_data['dropdown_hover_color'];
		$dropdown_bg_color   = $this->grid_data['dropdown_bg'];
		$dropdown_bg_colorA  = $this->grid_data['dropdown_hover_bg'];
		$styles .= '.'.$this->grid_data['ID'].' .tg-dropdown-item {
			color:'.esc_attr($dropdown_txt_color).';
			background:'.esc_attr($dropdown_bg_color).'
		;}
		.'.$this->grid_data['ID'].' .tg-filter-active,
		.'.$this->grid_data['ID'].' .tg-dropdown-item:hover {
			color:'.esc_attr($dropdown_txt_colorA).';
			background:'.esc_attr($dropdown_bg_colorA).'
		;}';
		
		// slider arrows text/bg colors
		$slider_arrows_color  = $this->grid_data['navigation_arrows_color'];
		$slider_arrows_bg = $this->grid_data['navigation_arrows_bg'];
		$styles .= (!empty($slider_arrows_bg)) ? 
			$grid_ID.' .tg-left-arrow i,
			'.$grid_ID.' .tg-right-arrow i {
				background:'.esc_attr($slider_arrows_bg).';
				color:'.esc_attr($slider_arrows_color).';
			}' : null;
		
		// slider bullets colors
		$slider_bullet_color  = $this->grid_data['navigation_bullets_color'];
		$slider_bullet_colorA = $this->grid_data['navigation_bullets_color_active'];
		$styles .= $grid_ID.' .tg-slider-bullets li.tg-active-item span{background:'.esc_attr($slider_bullet_colorA).';}';
		$styles .= $grid_ID.' .tg-slider-bullets li span{background:'.esc_attr($slider_bullet_color).';}';
		
		return $styles;
		
	}
	
	/**
	* Process areas styles
	* @since 1.0.0
	*/
	public function areas_style() {
		
		$styles  = null;
		$grid_ID = $this->grid_data['css_id'];
		
		$areas = array('top1','top2','left','right','bottom1','bottom2');
		$area_div = ' .tg-grid-area-';
		
		foreach($areas as $area) {
			
			$data = $this->grid_data['area_'.$area];
			$data = json_decode($data, TRUE);
			
			if (isset($data['functions']) && !empty($data['functions']) && isset($data['styles']) && !empty($data['styles'])) {
				$styles .= $grid_ID.$area_div.$area.'{';
				foreach($data['styles'] as $style => $value) {
					if (isset($value) && !empty($value)) {
						$unit = (is_numeric($value)) ? 'px' : '';
						$styles  .= esc_attr($style).':'.esc_attr($value.$unit).';';
					}
				}
				$styles .= '}';
			}
			
		}
		
		return $styles;
		
	}

	/**
	* Process item style
	* @since 1.0.0
	*/
	public function item_style() {
		
		$styles    = null;
		$grid_ID   = $this->grid_data['css_id'];
		$animation = $this->grid_data['animation'];
		
		// add perspective on item if necessary
		if ($animation === 'perspective_x') {
			$styles .= $grid_ID. '.tg-item{-webkit-transform-origin: 50% 0%;-moz-transform-origin: 50% 0%;-ms-transform-origin: 50% 0%;-o-transform-origin: 50% 0%;transform-origin: 50% 0%;}';
		} else if ($animation === 'perspective_y')  {
			$styles .= $grid_ID. '.tg-item{-webkit-transform-origin: 0% 50%;-moz-transform-origin: 0% 50%;-ms-transform-origin: 0% 50%;-o-transform-origin: 0% 50%;transform-origin: 0% 50%;}';
		}
		
		return $styles;
		
	}
	
	/**
	* Process item skin
	* @since 1.0.0
	*/
	public function item_skin() {
		
		$styles      = null;
		$item_skins  = $this->grid_data['item_skins'];
		$get_skins   = $this->grid_data['grid_skins'];
		$skins       = json_decode($this->grid_data['skins'], true);
		$skins       = (empty($skins) || !is_array($skins)) ? array('post' => $this->base->default_skin($grid_style)) : $skins;
		$social_skin = $this->grid_data['social_skin'];
		$grid_style  = ($this->grid_data['style'] === 'justified') ? 'grid' : $this->grid_data['style'];

		if (is_array($item_skins) && $this->grid_data['source_type'] == 'post_type') {

			foreach ($item_skins as $item_skin) {	
				$item_skin_slug = array_key_exists($item_skin,$get_skins) ? $item_skin : '';
				if (!empty($item_skin_slug) && $get_skins[$item_skin_slug]['type'] != $grid_style) {
					$item_skin_slug = '';
				}
				$item_skins[] = $item_skin_slug;
			}
			
			$item_skins = (array) array_filter($item_skins, 'strlen');
			$skins = array_merge($skins,$item_skins);
			$skins = array_unique($skins);

		} else {
			// social media skin
			$skins = (array) $social_skin;
		}

		foreach ($skins as $skin) {	

			$skin_slug = (array_key_exists($skin, $get_skins) && $get_skins[$skin]['type'] == $grid_style) ? $skin : $this->base->default_skin($grid_style);
			if (!$skin_slug) {
				return false;
			}
			
			if ($get_skins[$skin_slug]['css'] == 'is_custom_skin') {
				$skin_data = The_Grid_Custom_Table::get_skin_styles($skin_slug);
				$skin_data = json_decode($skin_data, true);
				$styles   .= (isset($skin_data['css'])) ? $skin_data['css'] : null;
				$this->skin_fonts = (isset($skin_data['font']) && $skin_data['font']) ? array_merge_recursive($this->skin_fonts, json_decode($skin_data['font'], true)) : $this->skin_fonts;
			} else {
				ob_start();
				include $get_skins[$skin_slug]['css'];
				$file_content  = ob_get_contents();
				ob_end_clean();
				$styles .= $file_content;
			}
			
		}

		return $styles;
		
	}
	
	/**
	* Process item skin
	* @since 1.0.0
	*/
	public function item_color() {
		
		$styles  = null;
		$grid_ID = $this->grid_data['css_id'];
		
		$schemes    = array('dark','light');
		$title_tags = array('div', 'h1','h1 a','h2','h2 a','h3','h3 a','h4','h4 a','h5','h5 a','h6','h6 a','a','a.tg-link-url','i','.tg-media-button', '.tg-item-price span');
		$para_tags  = array('p', 'ol', 'ul', 'li');
		$span_tags  = array('span','.no-liked .to-heart-icon path','.empty-heart .to-heart-icon path', '.tg-item-comment i', '.tg-item-price del span');
		
		$tags = array(
			'title' => $title_tags,
			'text'  => $para_tags,
			'span'  => $span_tags
		);
		
		$default = array(
			'dark_title'  => '#444444',
			'dark_text'   => '#777777',
			'dark_span'   => '#999999',
			'light_title' => '#ffffff',
			'light_text'  => '#f5f5f5',
			'light_span'  => '#f6f6f6',
		);
		
		$colors = null;
		foreach ($schemes as $scheme) {
			foreach ($tags as $tag => $classes) {
				$classes   = implode(',.tg-item .tg-'.$scheme.' ', $classes);
				$def_color = $default[$scheme.'_'.$tag];
				$color_options = $this->base->getVar($this->grid_data,'grid_colors', array());
				$color_scheme  = $this->base->getVar($color_options,$scheme, array());
				$color_value   = $this->base->getVar($color_scheme,$tag,$def_color);
				$colors .= ($color_value) ? '.tg-item .tg-'.$scheme.' '.$classes.'{color:'.$color_value.';fill:'.$color_value.';stroke:'.$color_value.';border-color:'.$color_value.';}' : '';
			}
		}

		$styles = $colors;
		
		$content_bg_skin = $this->grid_data['skin_content_background'];
		$overlay_bg_skin = $this->grid_data['skin_overlay_background'];
		$styles .= $grid_ID.' .tg-item-content-holder {
					   background-color:'.esc_attr($content_bg_skin).';
				   }
				   '.$grid_ID.' .tg-item-overlay {
					   background-color:'.esc_attr($overlay_bg_skin).';
				   }';
				   
		return $styles;
		
	}
	
	/**
	* Retrieve font family from css
	* @since 1.0.0
	*/
	public function font_family() {
				
		$font_url = $this->base->get_google_fonts($this->skin_fonts);

		return ($font_url) ? '<link href="'.esc_url($font_url).'" rel="stylesheet" property="stylesheet" type="text/css" media="all">' : null;
		
	
	}
	
	/**
	* Retrieve custom css
	* @since 1.0.0
	*/
	public function custom_css() {

		// custom styles
		$custom_css = $this->grid_data['custom_css'];
		$styles = (!empty($custom_css)) ? $custom_css : null;
		
		return $styles;

	}
		
}