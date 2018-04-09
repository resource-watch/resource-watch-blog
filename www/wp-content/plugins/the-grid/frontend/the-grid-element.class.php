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

class The_Grid_Elements {
	
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
	* Grid items
	*
	* @since 1.0.0
	* @access public
	*
	* @var array
	*/
	public $item_colors;
	
	/**
	* Lightbox output
	*
	* @since 2.1.0
	* @access public
	*
	* @var boolean
	*/
	public $lightbox_output;
	
	/**
	* Social share data (int cache)
	*
	* @since 2.1.2
	* @access private
	*
	* @var string
	*/
	private $social_link;
	private $social_title;
	
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
	* To initialize a The_Grid_Elements object
	* @since 1.0.0
	*/
	static public function getInstance() {
		
		if(self::$instance == null) {
			self::$instance = new self;
		}
		
		return self::$instance;
		
	}
	
	/**
	* To initialize a The_Grid_Elements object
	* @since 1.0.0
	*/
	public function init() {
		
		// set the grid base helper class
		$this->base = new The_Grid_Base();
		// retrieve grid data
		$this->grid_data = tg_get_grid_data();
		// retrieve grid item data
		$this->grid_item = tg_get_grid_item();
		// retrieve item colors
		$this->item_colors = $this->get_colors();
		// set lightbox output to false (for each new proceeded item)
		$this->lightbox_output = false;
		// set social share data to null (int cache ~10%)
		$this->social_link  = null;
		$this->social_title = null;
		
	}
	
	/**
	* Add action to element
	* @since: 1.7.0
	*/
	public function add_action($args, $content = '', $class = '') {

		$action_args = $this->base->getVar($args, 'action');

		if ($action_args) {
			
			$type = $this->base->getVar($action_args, 'type');
			
			if ($type == 'link') {
				
				$target = $this->base->getVar($action_args, 'link_target', '_self');
				
				switch ($this->base->getVar($action_args, 'link_url')) {
					case 'author_posts_url':
						$author = $this->base->getVar($this->grid_item, 'author');
						$url    = $this->base->getVar($author, 'url');
						break;
					case 'custom_url':
						$url = $this->base->getVar($action_args, 'custom_url');
						break;
					case 'meta_data_url':
						$url = $this->get_item_meta($this->base->getVar($action_args, 'meta_data_url'));
						break;
					default:
						$url = $this->get_the_permalink();
						break;
				}
				
				global $tg_skins_preview;
				$url = ($tg_skins_preview) ? 'javascript:;' : $url;

				$class = ($class) ? ' class="'.esc_attr($class).'"' : null;

				// content is already escaped
				return ($url) ? '<a'.$class.' target="'.esc_attr($target).'" href="'.esc_url($url).'">'.$content.'</a>' : $content;
			
			} else if ($type == 'lightbox') {

				$lightbox = $this->get_media_button(array('content' => $content), $class);
				
				// content is already escaped
				return ($lightbox) ? $lightbox : $content;
				
			}
			
		}
		
		return $content;
		
	}
	
	/**
	* Add action to layer/container
	* @since: 1.7.0
	*/
	public function add_layer_action($args, $class = '') {
		
		$class = (isset($args['action']['position']) && $args['action']['position'] == 'above') ? $class.' tg-element-above' : $class;
		return $this->add_action($args, '', $class);
	
	}
	
	/**
	* Content clear
	* @since: 1.6.0
	*/
	public function get_content_clear() {	
	
		return '<div class="tg-item-clear"></div>';
		
	}

	/**
	* Content line break
	* @since: 1.6.0
	*/
	public function get_line_break() {	
	
		return '<div class="tg-item-line-break"></div>';
		
	}
	
	/**
	* Media holder markup start 
	* @since: 1.0.0
	*/
	public function get_media_wrapper_start($class = '') {
		
		$class = ($class) ? ' '.$class : null;
		$color = $this->item_colors['overlay']['class'];
		
		return '<div class="tg-item-media-holder '.esc_attr($color.$class).'">';
		
	}
	
	/**
	* Media holder markup end 
	* @since: 1.0.0
	*/
	public function get_media_wrapper_end() {	
	
		return '</div>';
		
	}
	
	/**
	* Media inner markup start 
	* @since: 1.7.0
	*/
	public function get_media_content_start($class = '') {
		
		return '<div class="tg-item-media-content '.esc_attr($class).'">';
		
	}
	
	/**
	* Media inner markup end 
	* @since: 1.0.0
	*/
	public function get_media_content_end() {	
	
		return '</div>';
		
	}
	
	/**
	* Content holder markup start (masonry)
	* @since: 1.0.0
	*/
	public function get_content_wrapper_start($class = '', $position = '') {
		
		$class      = ($class) ? ' '.$class : null;
		$color      = $this->item_colors['content']['class'];
		$format     = ' '.$this->get_item_format().'-format';
		$bg_skin    = $this->grid_data['skin_content_background'];
		$bg_item    = $this->item_colors['content']['background'];
		$background = ($bg_skin != $bg_item) ? ' style="background-color:'.esc_attr($bg_item).'"' : null;
		$position   = ($position) ? ' data-position="'.esc_attr($position).'"' : null;
		
		return '<div class="tg-item-content-holder '.esc_attr($color.$format.$class).'"'.$position.$background.'>';
		
	}
	
	/**
	* Content holder markup end  (masonry)
	* @since: 1.0.0
	*/
	public function get_content_wrapper_end() {	
	
		return '</div>';
		
	}
	
	/**
	* Top markup Start
	* @since: 1.7.0
	*/
	public function get_top_wrapper_start() {	
	
		return '<div class="tg-top-holder">';
		
	}
	
	/**
	* Top markup End
	* @since: 1.7.0
	*/
	public function get_top_wrapper_end() {	

		return '</div>';
		
	}
	
	/**
	* Center markup Start
	* @since: 1.0.0
	*/
	public function get_center_wrapper_start() {	
		
		$html  = '<div class="tg-center-holder">';
			$html  .= '<div class="tg-center-inner">';
			
		return $html;
		
	}
	
	/**
	* Center markup End
	* @since: 1.0.0
	*/
	public function get_center_wrapper_end() {	
	
			$html  = '</div>';
		$html  .= '</div>';

		return $html;
		
	}
	
	/**
	* Bottom markup Start
	* @since: 1.7.0
	*/
	public function get_bottom_wrapper_start() {	
	
		return '<div class="tg-bottom-holder">';
		
	}
	
	/**
	* Bottom markup End
	* @since: 1.7.0
	*/
	public function get_bottom_wrapper_end() {	

		return '</div>';
		
	}
	
	/**
	* Icon element
	* @since: 1.6.0
	*/
	public function get_icon_element($args = '', $class ='') {	
	
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'i');
		// get icon class
		$icon  = $this->base->getVar($args, 'icon');

		return ($icon) ? '<'.esc_attr($html_tag).' class="'.esc_attr($icon.$class).'">'.$this->add_action($args, '', 'tg-element-absolute').'</'.esc_attr($html_tag).'>' : null;
		
	}
	
	/**
	* HTML element
	* @since: 1.6.0
	*/
	public function get_html_element($args = '', $class ='') {
		
		// get html content
		$html = $this->base->getVar($args, 'html');
		
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
		
		global $tg_skins_preview;

		// merge native Wordpress allowed tags + The Grid allowed tags
		$allowed = array_merge_recursive($allowedposttags, $allowedtags, $allowedsvg);

		// fetch item ID from The Grid 'shortcode code' (#post_ID.#)
		$content = str_replace('&quot;#post_ID#&quot;', '#post_ID#', $html);
		$content = str_replace('#post_ID#', $this->base->getVar($this->grid_item, 'ID'), $content);
		// fetch metadata value from The Grid 'shortcode code' (#meta:my_meta_key#)
		$content = preg_replace_callback('/#meta:(.*?)#/', array($this, 'replace_meta_key'), $content);
		// remove unwanted tags (before checking color otherwise rgb colors will be removed by wp_kses: https://core.trac.wordpress.org/ticket/24157)
		$content = wp_kses($content, $allowed);
		// fetch color value from get_colors
		$content = preg_replace_callback('/#color:(.*?)#/', array($this, 'replace_color_value'), $content);
		// do_shortcode from custom html content (not in preview skin mode otherwise some shortcode can generate issue)
		$content = (!$tg_skins_preview) ? do_shortcode($content) : $content;

		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'div');
		
		// check for an action class (if no content then force absolute position for action otherwise it will be not effective)
		$action_class = (empty($content)) ? 'tg-element-absolute' : null;
		
		return '<'.esc_attr($html_tag).' class="'.esc_attr($class).'">'.$this->add_action($args, $content, $action_class).'</'.esc_attr($html_tag).'>';
	
	}

	/**
	* Replace metakey by value
	* @since: 1.7.0
	*/
	public function replace_meta_key($content = '') {

		return (isset($content[1])) ? $this->get_item_meta($content[1]) : $content;
		
	}
	
	/**
	* Replace color by value
	* @since: 1.7.0
	*/
	public function replace_color_value($content = '') {
		
		if (isset($content[1])) {
			
			$attributes   = explode('-', $content[1]);
			$content_type = (isset($attributes[0])) ? $attributes[0] : null;
			$color_type   = (isset($attributes[1])) ? $attributes[1] : null;

			if ($content_type && $color_type) {
				return (isset($this->item_colors[$content_type]) && isset($this->item_colors[$content_type][$color_type])) ? $this->item_colors[$content_type][$color_type] : null;
			}
			
		}
		
	}
	
	/**
	* Overlay markup
	* @since: 1.0.0
	*/
	public function get_overlay($class = '', $position = '') {
		
		$bg_skin = $this->grid_data['skin_overlay_background'];
		$bg_item = $this->item_colors['overlay']['background'];
		$background = ($bg_skin != $bg_item) ? ' style="background-color:'.esc_attr($bg_item).'"' : null;
		$position   = ($position) ? ' data-position="'.esc_attr($position).'"' : null;
		
		$html  = '<div class="tg-item-overlay"'.$background.$position.'></div>';
		return $html;
		
	}
	
	/**
	* Get item format
	* @since: 1.0.0
	*/
	public function get_item_format() {
		
		$format = $this->base->getVar($this->grid_item, 'format');
		$format = (!empty($format) && in_array($format, $this->grid_data['items_format'])) ? $format : 'image';
		
		$image = $this->base->getVar($this->grid_item, 'image');
		$image = $this->base->getVar($image, 'url');
		if ($format == 'image' && !$image) {
			$format = 'standard';
		}

		return esc_attr($format);
		
	}
	
	/**
	* Get item data array
	* @since: 1.0.0
	*/
	public function get_item_data() {
		
		return $this->grid_item;
	
	}
	
	/**
	* Get item ID
	* @since: 1.0.0
	*/
	public function get_item_ID() {
		
		return esc_attr($this->base->getVar($this->grid_item, 'ID'));
		
	}
	
	/**
	* Get attachament url (drepecated / typo)
	* @since: 1.0.0
	*/
	public function get_attachament_url() {
		
		return $this->get_attachment_url();
		
	}
	
	/**
	* Get attachment url
	* @since: 1.7.0
	*/
	public function get_attachment_url() {
		
		return esc_url($this->base->getVar($this->grid_item['image'], 'url'));
		
	}
	
	/**
	* Get the permalink
	* @since: 1.0.0
	*/
	public function get_the_permalink() {
		
		return esc_url($this->base->getVar($this->grid_item, 'url'));
		
	}
	
	/**
	* Get the permalink target
	* @since: 1.0.0
	*/
	public function get_the_permalink_target() {
		
		return esc_attr($this->base->getVar($this->grid_item, 'url_target'));
		
	}
	
	/**
	* Get item metadata
	* @since: 1.0.0
	*/
	public function get_item_meta($meta_key = '', $class = '') {
		
		global $tg_skins_preview;
		
		if (!$tg_skins_preview && !empty($meta_key)) {
			
			global $allowedposttags;
			
			$ID = $this->base->getVar($this->grid_item, 'ID');
			$meta_data  = $this->base->getVar($this->grid_item, 'meta_data');
			$meta_value = $this->base->getVar($meta_data, $meta_key);
			
			return wp_kses($meta_value, $allowedposttags);
			
		} else {
			
			return '_metadata "'.esc_attr($meta_key).'"';
			
		}
		
	}
	
	/**
	* Get the metadata
	* @since: 1.7.0
	*/
	public function get_the_meta_data($args = '', $class = '') {
		
		global $allowedposttags;
		
		$meta_key   = $this->base->getVar($args,'meta_key');
		$meta_value = $this->get_item_meta($meta_key);
		
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'span');
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		
		return ($meta_value) ? '<'.esc_attr($html_tag).' class="tg-item-meta-data'.esc_attr($class).'">'.$this->add_action($args, wp_kses($meta_value, $allowedposttags)).'</'.esc_attr($html_tag).'>' : null;
			
	}
	
	/**
	* The title
	* @since: 1.0.0
	*/
	public function the_title() {
	
		return esc_html($this->base->getVar($this->grid_item, 'title'));
	
	}

	/**
	* Get the title
	* @since: 1.0.0
	*/
	public function get_the_title($args = '', $class ='') {
		
		// retrieve title data
		$title  = $this->base->getVar($this->grid_item, 'title');
		$url    = $this->base->getVar($this->grid_item, 'url');
		$target = $this->base->getVar($this->grid_item, 'url_target');
		
		$title_link  = (!isset($args['link'])) ? true : $args['link'];
		$link_target = (!isset($args['target']) || empty($args['target'])) ? $target : $args['target'];

		if (!empty($title)) {
			
			// allowed HTML tags in the title
			$allowed_tags = array(
				'strong' => array(),
				'em'     => array(),
				'b'      => array(),
				'i'      => array()
			);
			
			// prepare additional class
			$class = ($class) ? ' '.$class : null;			
			// get html tag
			$html_tag = $this->base->getVar($args, 'html_tag', 'h2');
			
			$html  = '<'.esc_attr($html_tag).' class="tg-item-title'.esc_attr($class).'">';
				$html .= (!empty($url) && $title_link) ? '<a href="'.esc_url($url).'" target="'.esc_attr($link_target).'">' : null;
					$html .= $this->add_action($args, wp_kses($title, $allowed_tags));
				$html .= (!empty($url) && $title_link) ? '</a>' : null;
			$html .= '</'.esc_attr($html_tag).'>';
			
			return $html;
			
		}
	
	}
	
	/**
	* Get read more button
	* @since: 1.0.0
	*/
	public function get_read_more_button($args = '', $class ='') {
		
		$url = $this->base->getVar($this->grid_item, 'url');
		$url_target = $this->base->getVar($this->grid_item, 'url_target');
		
		if (!empty($url)) {
			
			// allowed HTML tags in the read more button
			$allowed_tags = array(
				'i' => array(
					'style' => array(),
					'class' => array()
				),
			);
			
			// prepare additional class
			$class = ($class) ? ' '.$class : null;
			// read more text
			$text = ($this->base->getVar($args, 'text', __( 'Read More', 'tg-text-domain' )));
			
			$output  = '<div class="tg-item-read-more">';
				$output .= '<a href="'.esc_url($url).'" target="'.esc_attr($url_target).'">';
					$output .= wp_kses($text, $allowed_tags);
				$output .= '</a>';
			$output .= '</div>';
			
			return $output;
			
		}
		
	}

	/**
	* Get the excerpt
	* @since: 1.0.0
	*/
	public function the_excerpt() {
		
		$allowed_tags = array(
			'a' => array(
				'class'  => array(),
				'href'   => array(),
				'target' => array(),
				'style'  => array(),
				'title'  => array()
			),
			'br' => array(),
			'hr' => array(),
			'em' => array(),
			'i'  => array(),
			'b'  => array(),
			'strong' => array(),
		);

		return wp_kses($this->base->getVar($this->grid_item, 'excerpt'), $allowed_tags);
	
	}
	
	/**
	* Get the excerpt
	* @since: 1.0.0
	* @modified: 2.4.0
	*/
	public function get_the_excerpt($args = '', $class ='') {
		
		// retrieve excerpt data
		$excerpt = $this->base->getVar($this->grid_item, 'excerpt');
		$length  = $this->base->getVar($args, 'length', 240);
		$suffix  = isset($args['suffix']) ? $args['suffix'] : '...';
		
		// if there are no HTML tags, strlen otherwise preserve tags
		if ($excerpt == strip_tags($excerpt) && $length > 0 && $excerpt) {

			$length++;
		
			if ( mb_strlen( $excerpt ) > $length ) {

				// for multibyte install
				if ( function_exists( 'mb_strrpos' ) ) {

					$excerpt  = mb_substr( $excerpt, 0, $length - 5 );
					$spacepos = mb_strrpos( $excerpt, ' ' );

					// search the last occurance of a space
					if ($spacepos) {
						$excerpt = mb_substr( $excerpt, 0, $spacepos );
					}

				} else {

					$subex   = mb_substr( $excerpt, 0, $length - 5);
					$exwords = explode( ' ', $subex );
					$excut   = - mb_strlen( $exwords[ count( $exwords ) - 1 ] );

					if ($excut < 0) {
						$excerpt = mb_substr( $subex, 0, $excut );
					} else {
						$excerpt = $subex;
					}

				}

			}

			$excerpt = rtrim( $excerpt ) . $suffix;
		
		} else if ($length > 0 && $excerpt) {

			$excerpt = $this->base->truncate_html($excerpt, $length, $suffix, true);
		
		}
		
		if (!empty($excerpt) && !ctype_space($excerpt)) {
			
			$allowed_tags = array(
				'a' => array(
					'class'  => array(),
					'href'   => array(),
					'target' => array(),
					'style'  => array(),
					'title'  => array()
				),
				'br' => array(),
				'hr' => array(),
				'em' => array(),
				'i'  => array(),
				'b'  => array(),
				'strong' => array(),
			);
			
			// prepare additional class
			$class = ($class) ? ' '.$class : null;
			// get html tag
			$html_tag = $this->base->getVar($args, 'html_tag', 'p');

			return '<'.esc_attr($html_tag).' class="tg-item-excerpt'.esc_attr($class).'">'.$this->add_action($args, wp_kses($excerpt, $allowed_tags)).'</'.esc_attr($html_tag).'>';
			
		}
	
	}
	
	/**
	* the date
	* @since: 1.0.0
	*/
	public function the_date() {
		
		return esc_html($this->base->getVar($this->grid_item, 'date'));
	
	}
	
	/**
	* Get the date
	* @since: 1.0.0
	*/
	public function get_the_date($args = '', $class ='') {
		
		$date = $this->base->getVar($this->grid_item, 'date');
		
		if ($date) {
			
			// date format
			$date_format = $this->base->getVar($args, 'format');
			// prepare additional class
			$class = ($class) ? ' '.$class : null;
			// get html tag
			$html_tag = $this->base->getVar($args, 'html_tag', 'span');

			if ($this->grid_data['source_type'] != 'post_type' || $date_format == 'ago' ) {
				
				$date = sprintf( _x( '%s ago', '%s = human-readable time difference', 'tg-text-domain' ), human_time_diff($date, date_i18n('U')));
				
			} else {
				
				$date_format = ($date_format) ? html_entity_decode($date_format) : $this->grid_data['date_format'];
				$date = date_i18n($date_format, $date);
				
			}
			
			$allowed_tags = array(
				'i' => array(
					'class' => array(),
					'style' => array()
				),
				'br' => array(
					'class' => array(),
					'style' => array()
				),
				'em' => array(
					'class' => array(),
					'style' => array()
				),
				'strong' => array(
					'class' => array(),
					'style' => array()
				),
			);
			
			return '<'.esc_attr($html_tag).' class="tg-item-date'.esc_attr($class).'">'.$this->add_action($args, wp_kses($date, $allowed_tags)).'</'.esc_attr($html_tag).'>';
			
		}
		
	}
	
	/**
	* Get social share buttons
	* @since: 1.0.0
	*/
	public function get_social_share_links() {
		
		return array(
			'facebook'  => $this->get_social_share_link(array('type' => 'facebook')),
			'twitter'   => $this->get_social_share_link(array('type' => 'twitter')),
			'google+'   => $this->get_social_share_link(array('type' => 'google-plus')),
			'pinterest' => $this->get_social_share_link(array('type' => 'pinterest'))
		);
		
	}
	
	/**
	* Get social share link
	* @since: 1.7.0
	*/
	public function get_social_share_link($args = '', $class ='') {

		$type  = $this->base->getVar($args, 'type');
		$link  = $this->social_link;
		$title = $this->social_title;
		
		if (!$link) {
			$link  = $this->base->getVar($this->grid_item, 'url');
			$link  = empty($link) ? home_url(add_query_arg(NULL, NULL)) : $link;
			$link  = rawurlencode($link);
			$this->social_link = $link;
		}
		
		if (!$title) {
			$title = $this->base->getVar($this->grid_item, 'title');
			$title = ($title) ? rawurlencode(wp_strip_all_tags(html_entity_decode($title, ENT_QUOTES, 'UTF-8'))) : null;
			$this->social_title = $title;
		}

		switch ($type) {
			case 'facebook':
				$icon = '<i class="tg-icon-facebook"></i>';
				$href = 'https://www.facebook.com/sharer.php?u='.$link.'&t='.$title;
				break;
			case 'twitter':
				$icon = '<i class="tg-icon-twitter"></i>';
				$href = 'https://twitter.com/share?url='.$link.'&text='.$title;
				break;
			case 'google-plus':
				$icon = '<i class="tg-icon-google-plus"></i>';
				$href = 'https://plus.google.com/share?url='.$link;
				break;
			case 'pinterest':
				$format  = $this->get_item_format();
				$gallery = $this->base->getVar($this->grid_item, 'gallery');
				$image   = $this->get_attachment_url();
				$image   = ($format != 'gallery') ? $image : (isset($gallery[0]) ? $gallery[0]['url'] : $image);
				$icon = '<i class="tg-icon-pinterest"></i>';
				$href = 'https://pinterest.com/pin/create/button/?url='.$link.'&description='.$title.'&media='.rawurlencode($image);
				break;
			case 'linkedin':
				$icon = '<i class="tg-icon-linkedin"></i>';
				$href = 'https://www.linkedin.com/shareArticle?url='.$link.'&mini=true&title='.$title;
				break;
			case 'reddit':
				$icon = '<i class="tg-icon-reddit"></i>';
				$href = 'http://www.reddit.com/submit?url='.$link.'&title='.$title;
				break;
			case 'whatsapp':
				$icon = '<i class="tg-icon-whatsapp"></i>';
				$href = 'whatsapp://send?text='.$title.' '.$link;
				break;
			case 'stumbleupon' :
				$icon = '<i class="tg-icon-stumbleupon"></i>';
				$href = 'http://www.stumbleupon.com/badge?url='.$link.'&title='.$title;
				break;
			case 'tumblr' :
				$icon = '<i class="tg-icon-tumblr"></i>';
				$href = 'https://www.tumblr.com/share?v=3&u='.$link.'&t='.$title;
				break;
			case 'blogger' :
				$icon = '<i class="tg-icon-blogger"></i>';
				$href = 'https://www.blogger.com/blog_this.pyra?t&u='.$link.'&n='.$title;
				break;
			case 'myspace' :
				$icon = '<i class="tg-icon-myspace"></i>';
				$href = 'https://myspace.com/post?u='.$link;
				break;
			case 'delicious' :
				$icon = '<i class="tg-icon-delicious"></i>';
				$href = 'https://delicious.com/post?url='.$link.'&title='.$title;
				break;
			case 'amazon' :
				$icon = '<i class="tg-icon-amazon"></i>';
				$href = 'http://www.amazon.com/gp/wishlist/static-add?u='.$link.'&t='.$title;
				break;
			case 'printfriendly' :
				$icon = '<i class="tg-icon-printfriendly"></i>';
				$href = 'http://www.printfriendly.com/print?url='.$link.'&title='.$title;
				break;
			case 'yahoomail' :
				$icon = '<i class="tg-icon-yahoomail"></i>';
				$href = 'http://compose.mail.yahoo.com/?body='.$link;
				break;
			case 'gmail' :
				$icon = '<i class="tg-icon-gmail"></i>';
				$href = 'https://mail.google.com/mail/u/0/?view=cm&fs=1&su'.$title.'&body='.$link.'&ui=2&tf=1';
				break;
			case 'aol' :
				$icon = '<i class="tg-icon-aol"></i>';
				$href = 'http://webmail.aol.com/Mail/ComposeMessage.aspx?subject='.$title.'&body='.$link;
				break;
			case 'newsvine' :
				$icon = '<i class="tg-icon-newsvine"></i>';
				$href = 'http://www.newsvine.com/_tools/seed&save?u='.$link.'&h='.$title;
				break;
			case 'hackernews' :
				$icon = '<i class="tg-icon-hackernews"></i>';
				$href = 'https://news.ycombinator.com/submitlink?u='.$link.'&t='.$title;
				break;
			case 'evernote' :
				$icon = '<i class="tg-icon-evernote"></i>';
				$href = 'http://www.evernote.com/clip.action?url='.$link.'&title='.$title;
				break;
			case 'digg' :
				$icon = '<i class="tg-icon-digg"></i>';
				$href = 'http://digg.com/submit?url='.$link.'&title='.$title;
				break;
			case 'livejournal' :
				$icon = '<i class="tg-icon-livejournal"></i>';
				$href = 'http://www.livejournal.com/update.bml?subject='.$title.'&event='.$link;
				break;
			case 'friendfeed' :
				$icon = '<i class="tg-icon-friendfeed"></i>';
				$href = 'http://friendfeed.com/?url='.$link.'&title='.$title;
				break;
			case 'buffer' :
				$icon = '<i class="tg-icon-buffer"></i>';
				$href = 'https://bufferapp.com/add?url='.$link.'&title='.$title;
				break;	
			default:
				$href = $icon = null;
				break;
		}
		
		global $tg_grid_preview, $tg_skins_preview;
		
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		$disable_class = ((is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) || $tg_grid_preview || $tg_skins_preview) ? ' tg-social-disabled' : null;
		
		return (isset($href) && !empty($href)) ? '<a class="tg-social-share'.$disable_class.' tg-'.$type.esc_attr($class).'" href="'.($disable_class ? null : esc_url($href)).'">'.$icon.'</a>' : null;
		
	}
	
	/**
	* The terms
	* @since: 1.0.0
	*/
	public function the_terms() {
	
		return $this->base->getVar($this->grid_item, 'terms');
	
	}
	
	/**
	* Get the terms
	* @since: 1.0.0
	*/
	public function get_the_terms($args = '', $class ='') {
		
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'span');
		
		// retrieve terms data
		$terms            = $this->base->getVar($this->grid_item, 'terms');
		$terms_link       = (!isset($args['link'])) ? true : $args['link'];
		$terms_color      = $this->base->getVar($args, 'color');
		$terms_separator  = esc_html($this->base->getVar($args, 'separator'));
		$terms_override   = $this->base->getVar($args, 'override');
		$taxonomy         = $this->base->getVar($args, 'taxonomy') ? array_map('trim', explode(',', $args['taxonomy'])) : array();

		$cat = null;
			
		if (!empty($terms)) {
				
			global $tg_skins_preview;
				
			foreach ($terms as $term) {
				
				if (($tg_skins_preview || empty($taxonomy) || in_array($term['taxonomy'], $taxonomy)) && ($term['taxonomy'] != 'post_format' || in_array('post_format', $taxonomy))) {
						
					$color = null;
					$term['name'] = ($tg_skins_preview && $taxonomy) ? implode(', ', $taxonomy) : $term['name'];
					
					if ($terms_color == 'background' || $terms_color == 'color') { 
						
						$term_color = $term['color'];
							
						if ($terms_color == 'background') {
							$important = $terms_override ? '!important' : null;
							$brightness = $this->base->brightness($term_color);
							$color = ($brightness == 'bright') ? '#000000' : '#ffffff';
							$color = (!empty($term_color)) ? ' style="background:'.esc_attr($term_color.$important).';color:'.esc_attr($color.$important).'"' : null;
						} else {
							$important = $terms_override ? '!important' : null;
							$color = (!empty($term_color)) ? ' style="color:'.esc_attr($term_color.$important).'"' : null;
						}
		
					}
							
					if ($cat && !empty($terms_separator)) {
						$cat .= '<span>'.$terms_separator.'</span>';
					}
					
					if ($terms_link && !empty($term['url'])) {
						$cat .= '<a class="'. esc_attr($term['taxonomy']) .'" href="' . esc_url($term['url']) . '" rel="category" data-term-id="' . esc_attr($term['ID']) . '">';
							$cat .= '<span class="tg-item-term" '.$color.'>'. esc_html($term['name']) .'</span>';
						$cat .= '</a>';
					} else {
						$cat .= '<span class="tg-item-term '. esc_attr($term['taxonomy']) .'" data-term-id="' . esc_attr($term['ID']) . '" '.$color.'>'. esc_html($term['name']) .'</span>';
					}
				
				}
				
			}
			
		}
				
		if (!empty($cat)) {
			
			$html  = '<'.esc_attr($html_tag).' class="tg-cats-holder'.esc_attr($class).'">';
				$html .= (!$terms_link) ? $this->add_action($args, $cat) : $cat;
			$html .= '</'.esc_attr($html_tag).'>';
			
			return $html;
			
		}
	
	}
	
	/**
	* The comments number
	* @since: 1.0.0
	*/
	public function the_comments_number() {
		
		$comments_number = $this->base->getVar($this->grid_item, 'comments_number', 0);
		return esc_html($this->base->shorten_number_format($comments_number));
		
	}
	
	/**
	* Get the comments number
	* @since: 1.0.0
	*/
	public function get_the_comments_number($args = '', $class ='') {
		
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'span');
		
		$comments = null;
		
		// retrieve terms data
		$item_id         = $this->base->getVar($this->grid_item, 'ID');
		$url             = $this->base->getVar($this->grid_item, 'url');
		$url_target      = $this->base->getVar($this->grid_item, 'url_target');
		$comments_link   = (!isset($args['link'])) ? true : $args['link'];
		$comments_number = $this->base->getVar($this->grid_item, 'comments_number');
		$comments_icon   = $this->base->getVar($args, 'icon');
	
		// translatable string
		$nonCom  = __( 'No comment', 'tg-text-domain' );
		$oneCom  = __( 'comment', 'tg-text-domain' );
		$sevCom  = __( 'Comments', 'tg-text-domain' );
		
		if (!$comments_icon) {
			
			if ($comments_number == 0) {
				$comments = $nonCom;
			} else if ($comments_number == 1) {
				$comments = $comments_number .' '. $oneCom;
			} else {
				$num_comments = $this->base->shorten_number_format($comments_number);
				$comments = $comments_number .' '. $sevCom;
			}

			$comments = esc_html($comments);
			
		} else {
			
			$comments_number = $this->base->shorten_number_format($comments_number);
			$comments = $comments_icon.'<span>'.esc_html($comments_number).'</span>';
			
		}
		
		if ($comments_link) {
			$comments = '<a class="tg-item-comment'.esc_attr($class).'" href="'.esc_url($url).'"  target="'.esc_attr($url_target).'">'.$comments.'</a>';			
		} else {
			$comments = '<'.esc_attr($html_tag).' class="tg-item-comment'.esc_attr($class).'">'.$this->add_action($args, $comments).'</'.esc_attr($html_tag).'>';
		}
		
		return $comments;
		
	}
	
	/**
	* The likes number
	* @since: 1.0.0
	*/
	public function the_likes_number() {
		
		$source_type  = $this->base->getVar($this->grid_data, 'source_type');
		$likes_number = $this->base->getVar($this->grid_item, 'likes_number');
		
		if ($source_type == 'post_type' && !is_numeric($likes_number)) {

			$meta_data = $this->base->getVar($this->grid_item, 'meta_data');
			$likes_number = $this->base->getVar($meta_data, '_post_like_count');

		}
		
		return esc_html($likes_number);
	
	}
	
	/**
	* Get the likes number
	* @since: 1.0.0
	*/
	public function get_the_likes_number($args = '', $class ='') {
		
		$output = null;
		
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'span');
		
		$source_type = $this->base->getVar($this->grid_data, 'source_type');
		$likes = $this->base->getVar($this->grid_item, 'likes_number');
		
		if ($source_type == 'post_type' && !is_numeric($likes)) {
			
			$output = str_replace('to-post-like', 'to-post-like'.$class, $likes);			
			$output = ($html_tag != 'span') ? str_replace(array('<span', '</span>'), array('<'.$html_tag, '</'.$html_tag.'>'), $output) : $output;
			
		} else {
			
			$url = $this->base->getVar($this->grid_item, 'url');

			$heart = '<span class="to-heart-icon">';
				$heart .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 64 64">';
					$heart .= '<g transform="translate(0, 0)">';
						$heart .= '<path stroke-width="6" stroke-linecap="square" stroke-miterlimit="10" d="M1,21c0,20,31,38,31,38s31-18,31-38 c0-8.285-6-16-15-16c-8.285,0-16,5.715-16,14c0-8.285-7.715-14-16-14C7,5,1,12.715,1,21z"></path>';
					$heart .= '</g>';
				$heart .= '</svg>';
			$heart .= '</span>';
			
			$title  = $this->base->getVar($this->grid_item, 'likes_title');
			$title  = ($title) ? ' title="'.esc_attr($title).'"' : null;
			$target = $this->base->getVar($this->grid_item, 'url_target');
			
			$output = '<'.esc_attr($html_tag).' class="no-ajaxy to-post-like to-post-like-unactive  empty-heart'.esc_attr($class).'"'.$title.'>';
				$output .= ($url && $source_type != 'post_type') ? '<a href="'.esc_url($url).'" target="'.esc_attr($target).'">' : null;
					$output .= $heart;
					$output .= '<span class="to-like-count">';
						$output .= esc_attr($this->base->shorten_number_format($likes));
					$output .= '</span>';
				$output .= ($url && $source_type != 'post_type') ? '</a>' : null;
			$output .= '</'.esc_attr($html_tag).'>';
		
		}
		
		return $output;
	}
	
	/**
	* The duration
	* @since: 1.0.0
	*/
	public function the_duration() {
	
		$video_data = $this->base->getVar($this->grid_item, 'video');
		$duration   = $this->base->getVar($video_data, 'duration');
		return esc_html($duration);
	
	}
	
	/**
	* Get the duration
	* @since: 1.0.0
	*/
	public function get_the_duration($args = '', $class ='') {
		
		$video_data = $this->base->getVar($this->grid_item, 'video');
		$duration   = $this->base->getVar($video_data, 'duration');
		
		if (!empty($duration)) {
			
			// prepare additional class
			$class = ($class) ? ' '.$class : null;
			// get html tag
			$html_tag = $this->base->getVar($args, 'html_tag', 'div');
			
			return '<'.esc_attr($html_tag).' class="tg-item-duration'.esc_attr($class).'">'.$this->add_action($args, esc_html($duration)).'</'.esc_attr($html_tag).'>';
			
		}
		
	}
	
	/**
	* The views number
	* @since: 1.0.0
	*/
	public function the_views_number() {
		
		return esc_html($this->base->getVar($this->grid_item, 'views_number'));
		
	}
	
	/**
	* Get the views number
	* @since: 1.0.0
	*/
	public function get_the_views_number($args = '', $class ='') {
		
		$views = $this->base->getVar($this->grid_item, 'views_number');
		
		if (!empty($views)) {
			
			// prepare additional class
			$class = ($class) ? ' '.$class : null;
			
			// shorten view number
			$views = $this->base->shorten_number_format($views);
			
			// get html tag
			$html_tag = $this->base->getVar($args, 'html_tag', 'span');
			$suffix = (!isset($args['view_suffix']) || (isset($args['view_suffix']) && $args['view_suffix']))  ? true : null;
			$suffix = ($suffix) ? _n( 'view', 'views', $views, 'tg-text-domain' ) : null;
			
			return '<'.esc_attr($html_tag).' class="tg-item-views'.esc_attr($class).'">'.$this->add_action($args, esc_html($views.' '.$suffix)).'</'.esc_attr($html_tag).'>';
			
		}
		
	}
	
	/**
	* The author
	* @since: 1.0.0
	*/
	public function the_author() {
		
		return esc_html($this->base->getVar($this->grid_item, 'author'));
		
	}
	
	/**
	* Get the author
	* @since: 1.0.0
	*/
	public function get_the_author($args = '', $class ='') {
		
		$author        = $this->base->getVar($this->grid_item, 'author');
		$author_prefix = $this->base->getVar($args, 'prefix');
		$author_name   = $this->base->getVar($author, 'name');
		$author_url    = $this->base->getVar($author, 'url');
		$author_url    = (!isset($args['link']) || $args['link']) ? $author_url : null;
		$author_avatar = ((int)$this->base->getVar($args, 'avatar', false) == true) ? $this->base->getVar($author, 'avatar') : null;
		$url_target    = $this->base->getVar($this->grid_item, 'url_target');

		if ($author_name) {
			
			// prepare additional class
			$class = ($class) ? ' '.$class : null;
			// get html tag
			$html_tag = $this->base->getVar($args, 'html_tag', 'span');
			
			$output  = ($author_avatar) ? '<div class="tg-item-author-holder'.esc_attr($class).'">' : null;
				$output .= ($author_avatar) ? '<span class="tg-item-avatar"><img src="'.esc_url($author_avatar).'"/></span>' : null;
				$output .= '<'.esc_attr($html_tag).' class="tg-item-author'.((!$author_avatar) ? esc_attr($class) : null).'">';
					$output .= ($author_prefix) ? '<span>'.esc_html($author_prefix).'</span>' : null;
					$output .= ($author_url) ? '<a href="'. esc_url($author_url) .'" target="'.esc_attr($url_target).'">' : null;
					$output .= (!$author_url) ? '<span class="tg-item-author-name">' : null;
						$output .= $this->add_action($args, esc_html($author_name));
					$output .= (!$author_url) ? '</span>' : null;
					$output .= ($author_url) ? '</a>' : null;
				$output .= '</'.esc_attr($html_tag).'>';
			$output .= ($author_avatar) ? '</div>' : null;
			
			return $output;
			
		}
		
	}
	
	/**
	* Get the author avatar
	* @since: 1.0.0
	*/
	public function get_the_author_avatar($args = '', $class ='') {
		
		$author        = $this->base->getVar($this->grid_item, 'author');
		$author_name   = $this->base->getVar($author, 'name');
		$author_avatar = $this->base->getVar($author, 'avatar');
		
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		
		$avatar_img = '<img class="tg-item-author-avatar'.esc_attr($class).'" src="'.esc_url($author_avatar).'" alt="'.esc_attr($author_name).'" title="'.esc_attr($author_name).'" width="46" height="46"/>';
		$avatar_img = $this->add_action($args, $avatar_img);
		
		return ($author_avatar) ? $avatar_img : null;
	
	}
	
	/**
	* Get quote markup
	* @since: 1.0.0
	*/
	public function get_the_quote_format() {
		
		$url = $this->base->getVar($this->grid_item, 'url');
		$url_target = $this->base->getVar($this->grid_item, 'url_target');
		
		$source = $this->base->getVar($this->grid_item['quote'], 'source');
		$quote  = $this->base->getVar($source, 'content');
		$author = $this->base->getVar($source, 'author');
		
		if ($quote) {
			$output  = '<h2 class="tg-quote-content tg-item-title"><a href="'.esc_url($url).'" target="'.esc_attr($url_target).'">'.esc_html($quote).'</a></h2>';
			$output .= (!empty($author)) ? '<span class="tg-quote-author">'.esc_html($author).'</span>' : null;
			return $output;
		}
		
	}
	
	/**
	* Get link markup
	* @since: 1.0.0
	*/
	public function get_the_link_format() {
		
		$url_item   = $this->base->getVar($this->grid_item, 'url');
		$url_target = $this->base->getVar($this->grid_item, 'url_target');
		
		$link    = $this->base->getVar($this->grid_item,'link');
		$source  = $this->base->getVar($link,'source');
		$content = $this->base->getVar($source, 'content');
		$url     = $this->base->getVar($source, 'url');
		
		if ($link) {
			$output  = '<h2 class="tg-link-content tg-item-title"><a href="'.esc_url($url_item).'" target="'.esc_attr($url_target).'">'.esc_html($content).'</a></h2>';
			$output .= (!empty($url)) ? '<a class="tg-link-url" href="'.esc_url($url_item).'" target="'.esc_attr($url_target).'">'.esc_url($url).'</a>' : null;
			return $output;
		}
		
	}
	
	/**
	* Get Woocommerce price
	* @since: 1.6.0
	*/
	public function get_product_price($args = '', $class ='') {

		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'div');
		
		$product = $this->base->getVar($this->grid_item, 'product');
		$price   = $this->base->getVar($product, 'price');
		
		return ($price) ? '<'.esc_attr($html_tag).' class="tg-item-price'.esc_attr($class).'">'.$this->add_action($args, $price).'</'.esc_attr($html_tag).'>' : null;
		
	}
	
	/**
	* Get Woocommerce full price
	* @since: 1.6.0
	*/
	public function get_product_full_price($args = '', $class ='') {

		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'div');
		
		$product = $this->base->getVar($this->grid_item, 'product');
		$price   = $this->base->getVar($product, 'full_price');
		
		return ($price) ? '<'.esc_attr($html_tag).' class="tg-item-price'.esc_attr($class).'">'.$this->add_action($args, $price).'</'.esc_attr($html_tag).'>' : null;
		
	}
	
	/**
	* Get Woocommerce regular price
	* @since: 1.6.0
	*/
	public function get_product_regular_price($args = '', $class ='') {

		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'div');

		$product = $this->base->getVar($this->grid_item, 'product');
		$price   = $this->base->getVar($product, 'regular_price');
		
		return ($price) ? '<'.esc_attr($html_tag).' class="tg-item-price'.esc_attr($class).'">'.$this->add_action($args, $price).'</'.esc_attr($html_tag).'>' : null;
		
	}
	
	/**
	* Get Woocommerce sale price
	* @since: 1.6.0
	*/
	public function get_product_sale_price($args = '', $class ='') {

		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'div');

		$product = $this->base->getVar($this->grid_item, 'product');
		$price   = $this->base->getVar($product, 'sale_price');
		
		return ($price) ? '<'.esc_attr($html_tag).' class="tg-item-price'.esc_attr($class).'">'.$this->add_action($args, $price).'</'.esc_attr($html_tag).'>' : null;
		
	}
	
	/**
	* Get Woocommerce star rating
	* @since: 1.0.0
	*/
	public function get_product_rating($args = '', $class ='') {
		
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'div');
		
		$product = $this->base->getVar($this->grid_item, 'product');
		$rating  = $this->base->getVar($product, 'rating');
		
		return ($rating) ? '<'.esc_attr($html_tag).' class="tg-item-rating'.esc_attr($class).'">'.$this->add_action($args, $rating).'</'.esc_attr($html_tag).'>' : null;
		
	}
	
	/**
	* Get Woocommerce text rating
	* @since: 1.6.0
	*/
	public function get_product_text_rating($args = '', $class ='') {
		
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'span');
		
		$product = $this->base->getVar($this->grid_item, 'product');
		$rating  = $this->base->getVar($product, 'text_rating');
		
		if ($rating > 0) {
			$rating_html  = '<'.esc_attr($html_tag).' class="tg-item-text-rating'.esc_attr($class).'" title="' . sprintf( __( 'Rated %s out of 5', 'woocommerce' ), $rating ) . '">';
			$rating_html .= $this->add_action($args, '<strong class="rating">' . $rating . '</strong> ' . __( 'out of 5', 'woocommerce' ));
      		$rating_html .= '</'.esc_attr($html_tag).'>';
			return $rating_html;
    	}
		
	}
	
	/**
	* Get Woocommerce sale status
	* @since: 1.0.0
	*/
	public function get_product_on_sale($args = '', $class ='') {
		
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'div');
		
		$product = $this->base->getVar($this->grid_item, 'product');
		$on_sale = $this->base->getVar($product, 'on_sale');
		
		return ($on_sale) ? '<'.esc_attr($html_tag).' class="tg-item-on-sale'.esc_attr($class).'">'.$this->add_action($args, $on_sale).'</'.esc_attr($html_tag).'>' : null;
		
	}
	
	/**
	* Get Woocommerce add to cart url
	* @since: 1.6.0
	*/
	public function get_product_add_to_cart_url($args = '', $class ='') {
		
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		
		$product   = $this->base->getVar($this->grid_item, 'product');
		$cart_url  = $this->base->getVar($product, 'add_to_cart_url');
		$cart_text = $this->base->getVar($args, 'text', __( 'Add to Cart', 'tg-text-domain' ));
		
		// get target
		$action_args = $this->base->getVar($args, 'action');
		$target = $this->base->getVar($action_args, 'link_target', '_self');
		
		return ($cart_url) ? '<a class="tg-item-add-to-cart-url'.esc_attr($class).'" href="'.esc_url($cart_url).'" target="'.esc_attr($target).'">'.esc_html($cart_text).'</a>' : null;
		
	}
	
	/**
	* Get Woocommerce add to cart button
	* @since: 1.0.0
	*/
	public function get_product_cart_button($args = '', $class ='') {
		
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// get html tag
		$html_tag = $this->base->getVar($args, 'html_tag', 'div');
		
		$product = $this->base->getVar($this->grid_item, 'product');
		$cart_button = $this->base->getVar($product, 'cart_button');
		
		if ($this->base->getVar($args, 'cart_icon')) {
			$icon_simple = $this->base->getVar($args, 'icon_simple', '<i class="tg-icon-shop-bag"></i>');
			$icon_variation = $this->base->getVar($args, 'icon_variable', '<i class="tg-icon-settings"></i>');
			$cart_icon = (strpos($cart_button, 'product_type_simple') !== false) ? $icon_simple : $icon_variation;
			$cart_button = ($cart_icon) ? preg_replace('#(<a.*?>).*?(</a>)#', '$1'.$cart_icon.'$2', $cart_button) : $cart_button;
		}
		
		// get target
		$action_args = $this->base->getVar($args, 'action');
		$target = $this->base->getVar($action_args, 'link_target', '_self');
		$link_url = $this->base->getVar($action_args, 'link_url');
		
		// set target if _blank and link to post url
		if ($target == '_blank' && $link_url == 'post_url') {
			$cart_button = preg_replace('/(<a\b[^><]*)>/i', '$1 target="'.esc_attr($target).'">', $cart_button);
		}
		
		return ($cart_button) ? '<'.esc_attr($html_tag).' class="tg-item-cart-button'.esc_attr($class).'">'.$cart_button.'</'.esc_attr($html_tag).'>' : null;
		
	}
	
	/**
	* Retrieve Woocommerce YITH Whislist
	* @since: 1.0.0
	*/
	public function get_product_wishlist() {
		
		$product = $this->base->getVar($this->grid_item, 'product');
		return $this->base->getVar($product, 'wishlist');
	
	}

	/**
	* Get media content (image/gallery/audio/video)
	* @since: 1.0.0
	*/
	public function get_media() {
		
		// get item format
		$format = $this->get_item_format();

		// get media content depending on of format
		switch ($format) {
			case 'gallery':
				$content = $this->gallery_markup();
				break;
			case 'audio':
				$content = $this->audio_markup();
				break;
			case 'video':
				$content = $this->video_type();
				break;
			default:
				$content = $this->image_markup();
				break;
		}

		return $content;

	}
	
	/**
	* Search for the right video data
	* @since: 1.0.0
	*/
	public function video_type() {
		
		$type   = $this->base->getVar($this->grid_item['video'], 'type');
		$format = (!$this->grid_data['video_lightbox']) ? $type : null;

		switch ($format) {
			case 'youtube':
				$content = $this->youtube_markup();
				break;
			case 'vimeo':
				$content = $this->vimeo_markup();
				break;
			case 'wistia':
				$content = $this->wistia_markup();
				break;
			case 'video':
				$content = $this->video_markup();
				break;
			default:
				$content = $this->image_markup();
				break;
		}
		
		return $content;
		
	}
	
	/**
	* Image markup
	* @since: 1.0.0
	*/
	public function image_markup() {
		
		$url         = $this->base->getVar($this->grid_item['image'], 'url');
		$source_type = $this->grid_data['source_type'];
		$grid_style  = $this->grid_data['style'];
		$lightbox    = $this->grid_data['video_lightbox'];
		
		$data_ratio  = null;
		$image       = null;

		if (!empty($url)) {
			
			if ($grid_style == 'masonry' && $lightbox && in_array($source_type, array('youtube','vimeo','wistia'))) {
				$data_ratio = ' data-ratio="16:9"';
				$image .= $this->get_media_poster();
			} else if ($grid_style == 'grid') {
				$image .= '<div class="tg-item-image" style="background-image: url('.esc_url($url).')"></div>';
			} else {
				$alt    = $this->base->getVar($this->grid_item['image'], 'alt');
				$width  = $this->base->getVar($this->grid_item['image'], 'width');
				$height = $this->base->getVar($this->grid_item['image'], 'height');
				$image  .= '<img class="tg-item-image" alt="'.esc_attr($alt).'" width="'.esc_attr($width).'" height="'.esc_attr($height).'" src="'.esc_url($url).'">';
			}
			
			// add woocommerce first gallery image if product
			$product_image = $this->base->getVar($this->grid_item, 'product_image');
			$product_image = $this->base->getVar($product_image, 'url');
			if ($product_image) {
				$image .= '<div class="tg-item-image tg-alternative-product-image" style="background-image: url('.esc_url($product_image).')"></div>';
			}
			
			// media inner
			$output  = '<div class="tg-item-media-inner"'.$data_ratio.'>';
				$output .= $image;
			$output .= '</div>';
			
			return $output;
		
		}

	}
	
	/**
	* Gallery markup
	* @since: 1.0.0
	*/
	public function gallery_markup() {
		
		
		$images    = $this->base->getVar($this->grid_item, 'gallery');
		$style     = $this->grid_data['style'];
		$slideshow = $this->grid_data['gallery_slide_show'];
		
		if ($images) {
			
			$class  = ' first-image show';
			
			$output = '<div class="tg-item-media-inner">';
				$output.= '<div class="tg-item-gallery-holder">';
				
				if ($style == 'grid') {
					
					foreach($images as $image) {
						if ($class || ($slideshow && !$class)) {
							$output .= '<div class="tg-item-image'.esc_attr($class).'" style="background-image: url('.esc_url($image['url']).')"></div>';
						}
						$class = null;
					}
					
				} else {
					
					foreach($images as $image) {
						if ($class) {
							$output .= '<img class="tg-item-image'.esc_attr($class).'" alt="'.esc_attr($image['alt']).'" width="'.esc_attr($image['width']).'" height="'. esc_attr($image['height']).'" src="'.esc_url($image['url']).'">';
						} else if ($slideshow) {
							$output .= '<div class="tg-item-image" style="background-image: url('.esc_url($image['url']).')"></div>';
						}
						$class = null;
					}
				}
				
				$output .= '</div>';
			$output .= '</div>';
			
			return $output;
		
		}
		
	}
	
	/**
	* Audio markup
	* @since: 1.0.0
	*/
	public function audio_markup() {
		
		$type = $this->base->getVar($this->grid_item['audio'], 'type');
		
		if ($type == 'soundcloud') {
			return $this->soundcloud_markup();
		} else {
			return $this->html_audio_markup();
		}
	
	}
	
	/**
	* SoundCloud markup
	* @since: 1.0.0
	*/
	public function soundcloud_markup() {
		
		$class  = ($this->base->getVar($this->grid_item['image'],'url')) ? ' has-media-poster' : null;
		$source = $this->base->getVar($this->grid_item['audio'],'source',array());
		$SC_ID  = $this->base->getVar($source,'ID');
		
		if ($SC_ID) {
			$SC_URL  = '//w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.esc_attr($SC_ID).'&amp;auto_play=false&amp;hide_related=true&amp;visual=true&amp;show_artwork=true&amp;color=white_transparent';
			$output  = '<div class="tg-item-media-inner'.esc_attr($class).'" data-ratio="4:3">';
				$output .= '<iframe id="SC-'.uniqid().'" class="tg-item-soundcloud tg-item-media" data-api="1" src="about:blank" data-src="'.esc_url($SC_URL).'"></iframe>';
				$output .= $this->get_media_poster();
			$output .= '</div>';
			$output .= '<div class="tg-item-media-poster tg-item-media-soundcloud tg-item-button-play"></div>';
			return $output;
		}
		
	}
	
	/**
	* HTML audio markup
	* @since: 1.0.0
	*/
	public function html_audio_markup() {
		
		$class   = ($this->base->getVar($this->grid_item['image'],'url')) ? ' has-media-poster' : ' no-media-poster';
		$sources = $this->base->getVar($this->grid_item['audio'],'source');
		
		if ($sources) {
			$output  = '<div class="tg-item-media-inner'.esc_attr($class).'">';
				$output .= $this->get_media_poster();
				$output .= '<audio class="tg-item-audio-player tg-item-media" controls preload="none">';
					foreach($sources as $type => $src ){
						if (in_array($type,array('mp3','ogg')) && !empty($src)) {
							$output .= '<source src="'.esc_url($src).'" type="audio/'.esc_attr($type).'">';  
						}
					}
				$output .= '</audio>';
			$output .= '</div>';
			return $output;
		}
		
	}

	/**
	* Youtube markup
	* @since: 1.0.0
	*/
	public function youtube_markup() {
		
		$class  = ($this->base->getVar($this->grid_item['image'],'url')) ? ' has-media-poster' : null;
		$source = $this->base->getVar($this->grid_item['video'],'source',array());
		$YT_ID  = $this->base->getVar($source,'ID');
		
		if ($YT_ID) {
			$YT_URL  = 'https://www.youtube.com/embed/'.esc_attr($YT_ID).'?version=3&amp;enablejsapi=1&amp;html5=1&amp;controls=1&amp;autohide=1&amp;rel=0&amp;showinfo=0';
			$ratio   = $this->base->getVar($this->grid_item['meta_data'], 'the_grid_item_youtube_ratio', '16:9');
			$output  = '<div class="tg-item-media-inner'.esc_attr($class).'" data-ratio="'.esc_attr($ratio).'">';
				$output .= '<iframe class="tg-item-youtube tg-item-media" src="about:blank" data-src="'.esc_url($YT_URL).'" data-api="1" id="YT-'.uniqid().'" allowfullscreen></iframe>';
				$output .= $this->get_media_poster();
			$output .= '</div>';
			return $output;
		}
		
	}

	/**
	* Vimeo markup
	* @since: 1.0.0
	*/
	public function vimeo_markup() {
		
		$class  = ($this->base->getVar($this->grid_item['image'],'url')) ? ' has-media-poster' : null;
		$source = $this->base->getVar($this->grid_item['video'],'source',array());
		$VM_ID  = $this->base->getVar($source,'ID');
		
		if ($VM_ID) {
			$HTML_ID = 'VM-'.uniqid();
			$VM_URL  = 'https://player.vimeo.com/video/'.esc_attr($VM_ID).'?title=0&amp;byline=0&amp;portrait=0&amp;api=1&amp;player_id='.$HTML_ID;
			$ratio   = $this->base->getVar($this->grid_item['meta_data'], 'the_grid_item_vimeo_ratio', '16:9');
			$output  = '<div class="tg-item-media-inner'.esc_attr($class).'" data-ratio="'.esc_attr($ratio).'">';
				$output .= '<iframe class="tg-item-vimeo tg-item-media" src="about:blank" data-src="'.esc_url($VM_URL).'" data-api="1" id="'.$HTML_ID.'" allowfullscreen></iframe>';
				$output .= $this->get_media_poster();
			$output .= '</div>';
			return $output;
		}
		
	}
	
	/**
	* Wistia markup
	* @since: 1.0.7
	*/
	public function wistia_markup() {
		
		$class  = ($this->base->getVar($this->grid_item['image'],'url')) ? ' has-media-poster' : null;
		$source = $this->base->getVar($this->grid_item['video'],'source',array());
		$WT_ID  = $this->base->getVar($source,'ID');
		
		if ($WT_ID) {
			$WT_URL  = 'https://fast.wistia.net/embed/iframe/'.esc_attr($WT_ID).'?version=3&enablejsapi=1&html5=1&controls=1&autohide=1&rel=0&showinfo=0';
			$ratio   = $this->base->getVar($this->grid_item['meta_data'], 'the_grid_item_wistia_ratio', '16:9');
			$output  = '<div class="tg-item-media-inner'.esc_attr($class).'" data-ratio="'.esc_attr($ratio).'">';
				$output .= '<iframe class="tg-item-wistia wistia_embed tg-item-media" src="about:blank" data-src="'.esc_url($WT_URL).'" data-api="1" id="WT-'.uniqid().'" allowfullscreen></iframe>';
				$output .= $this->get_media_poster();
			$output .= '</div>';
			return $output;
		
		}
	}
	
	/**
	* Video markup
	* @since: 1.0.0
	*/
	public function video_markup() {
		
		$class  = ($this->base->getVar($this->grid_item['image'],'url')) ? ' has-media-poster' : null;
		$source = $this->base->getVar($this->grid_item['video'],'source',array());
		
		if ($source) {
			$poster_url = $this->base->getVar($this->grid_item['image'],'url');
			$ratio   = $this->base->getVar($this->grid_item['meta_data'], 'the_grid_item_video_ratio', '16:9');
			$output  = '<div class="tg-item-media-inner'.esc_attr($class).'" data-ratio="'.esc_attr($ratio).'">';
				$poster  = ($poster_url) ? ' poster="'.esc_url($poster_url).'"' : null;
				$output .= '<video class="tg-item-video-player tg-item-media"'.$poster.' controls preload="none" style="width:100%;height:100%">';
				foreach($source as $type => $src ){
					if (in_array($type,array('mp4','webm','ogv')) && !empty($src)) {
						$output .= '<source src="'.esc_url($src).'" type="video/'.esc_attr($type).'">';  
					}
				} 
				$output .= '</video>';
				$output .= $this->get_media_poster();
			$output .= '</div>';
			return $output;
		}
		
	}
	
	/**
	* Media poster markup
	* @since: 1.0.0
	*/
	public function get_media_poster() {
		
		$poster_url  = $this->base->getVar($this->grid_item['image'],'url');
		$item_format = $this->base->getVar($this->grid_item, 'format');
		$audio_type  = $this->base->getVar($this->grid_item['audio'], 'type');
		$grid_style  = $this->grid_data['style'];

		if ($poster_url) {
			
			if ($grid_style == 'masonry' && $item_format == 'audio' && $audio_type == 'audio') {
				$alt    = $this->base->getVar($this->grid_item['image'], 'alt');
				$width  = $this->base->getVar($this->grid_item['image'], 'width');
				$height = $this->base->getVar($this->grid_item['image'], 'height');
				return '<img class="tg-item-audio-poster" width="'.esc_attr($width).'" height="'.esc_attr($height).'" alt="'.esc_attr($alt).'" src="'.esc_url($poster_url).'">';
			} else if ($item_format == 'audio' && $audio_type == 'audio') {
				return '<div class="tg-item-audio-poster" style="background-image: url('.esc_url($poster_url).')"></div>';
			} else {
				return '<div class="tg-item-media-poster" style="background-image: url('.esc_url($poster_url).')"></div>';
			}
			
		}	
	
	}
	
	/**
	* Get link button
	* @since: 1.0.0
	*/
	public function get_link_button($args = '', $class ='') {	
	
		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		
		$icon = $this->base->getVar($args, 'icon', '<i class="tg-icon-link"></i>');
	
		$url = $this->base->getVar($this->grid_item, 'url');
		$url_target = $this->base->getVar($this->grid_item, 'url_target');
		
		if (!empty($url)) {
			
			$allowed_tags = array(
				'i' => array(
					'style' => array(),
					'class' => array()
				),
			);
			
			return '<a class="tg-link-button'.esc_attr($class).'" href="'.esc_url($url).'" target="'.esc_attr($url_target).'">'.wp_kses($icon, $allowed_tags).'</a>';
			
		}
		
	}
	
	/**
	* Get lightbox markup fo each media type and for each lighbox plugins
	* @since: 1.0.0
	*/
	public function get_media_button($args = '', $class = '') {
			
		// video in lightbox option
		$video_lightbox = $this->grid_data['video_lightbox'];

		// Get media type
		$format     = $this->get_item_format();
		$media_type = ($format == 'video' && isset($this->grid_item['video']['type'])) ? $this->grid_item['video']['type'] : $format;
		$media_type = ($format == 'audio' && isset($this->grid_item['audio']['type'])) ? $this->grid_item['audio']['type'] : $media_type;

		// prepare additional class
		$class = ($class) ? ' '.$class : null;
		// prepare unique lightbox ID
		$ID = 'tolb-'.str_replace('grid-','',$this->grid_data['ID']).$this->grid_item['ID'].'';
		
		$allowed_tags = array(
			'i' => array(
				'style' => array(),
				'class' => array()
			),
		);

		// get custom icons or default icons
		$icons = $this->base->getVar($args, 'icons');
		$icons = array (
			'image' => (!isset($args['content'])) ? strip_tags($this->base->getVar($icons, 'image', '<i class="tg-icon-add"></i>'), '<i>') : $args['content'],
			'audio' => (!isset($args['content'])) ? strip_tags($this->base->getVar($icons, 'audio', '<i class="tg-icon-play"></i>'), '<i>') : $args['content'],
			'video' => (!isset($args['content'])) ? strip_tags($this->base->getVar($icons, 'video', '<i class="tg-icon-play"></i>'), '<i>') : $args['content'],
			'pause' => (!isset($args['content'])) ? strip_tags($this->base->getVar($icons, 'pause', null), '<i>') : null
		);
		
		$output = null;

		switch ($media_type) {
			case 'youtube':
				if ($video_lightbox) {
					$output = $this->get_youtube_lightbox($icons['video'], $class, $ID);
				} else {
					$output = '<a class="tg-media-button tg-item-button-play'.esc_attr($class).'">'.$icons['video'].$icons['pause'].'</a>';
				}
				break;
			case 'vimeo':
				if ($video_lightbox) {
					$output = $this->get_vimeo_lightbox($icons['video'], $class, $ID);
				} else {
					$output = '<a class="tg-media-button tg-item-button-play'.esc_attr($class).'">'.$icons['video'].$icons['pause'].'</a>';
				}
				break;
			case 'wistia':
				if ($video_lightbox) {
					$output = $this->get_wistia_lightbox($icons['video'], $class, $ID);
				} else {
					$output = '<a class="tg-media-button tg-item-button-play'.esc_attr($class).'">'.$icons['video'].$icons['pause'].'</a>';
				}
				break;
			case 'video':
				if ($video_lightbox) {
					$output = $this->get_video_lightbox($icons['video'], $class, $ID);
				} else {
					$output = '<a class="tg-media-button tg-item-button-play'.esc_attr($class).'">'.$icons['video'].$icons['pause'].'</a>';
				}
				break;
			case 'soundcloud':
				$output = '<a class="tg-media-button tg-item-button-play'.esc_attr($class).'">'.$icons['audio'].$icons['pause'].'</a>';
				break;
			case 'audio':
				$output = '<a class="tg-media-button tg-item-button-play'.esc_attr($class).'">'.$icons['audio'].$icons['pause'].'</a>';
				break;
			case 'gallery':
				$output = $this->get_gallery_lightbox($icons['image'], $class, $ID);
				break;
			default:
				$output = $this->get_image_lightbox($icons['image'], $class, $ID);
				break;
		}
		
		$this->lightbox_output = true;
				
		return $output;

	}
	
	/**
	* Get Youtube lightbox markup
	* @since: 1.0.0
	*/
	public function get_youtube_lightbox($icon, $class, $ID) {
		
		$source = $this->base->getVar($this->grid_item['video'],'source',array());
		$YT_ID  = $this->base->getVar($source,'ID');
		
		if ($YT_ID) {
			
			if ($redirection = $this->lightbox_redirection($icon, $class, $ID)) {
				return $redirection;
			}
			
			$lightbox_type = $this->grid_data['lightbox_type'];
		
			switch ($lightbox_type) {
				case 'prettyphoto':
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" href="//www.youtube.com/watch?v='.esc_attr($YT_ID).'" rel="prettyPhoto[pp_gal]" title="">'.$icon.'</a>';
					break;
				case 'fancybox':
					return '<a id="'.$ID.'" class="tg-media-button fancybox iframe'.esc_attr($class).'" rel="tg_group" href="//www.youtube.com/embed/'.esc_attr($YT_ID).'?autoplay=0&wmode=opaque">'.$icon.'</a>';
					break;
				case 'foobox':
					return '<a id="'.$ID.'" class="tg-media-button foobox'.esc_attr($class).'" href="//www.youtube.com/embed/'.esc_attr($YT_ID).'?autoplay=0&wmode=opaque" rel="foobox">'.$icon.'</a>';
					break;
				case 'the_grid':
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-tolb-src="'.esc_attr($YT_ID).'" data-tolb-type="youtube" data-tolb-alt="">'.$icon.'</a>';
					break;
				case 'modulobox':
					$image = $this->base->getVar($this->grid_item['image'], 'lb_url');
					$image = (!$image) ? $this->base->getVar($this->grid_item['image'], 'url') : $image;
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-thumb="'.esc_url($this->grid_item['image']['url']).'" data-poster="'.esc_url($image).'" href="https://youtu.be/'.esc_attr($YT_ID).'" data-rel="'.esc_attr($this->grid_data['ID']).'">'.$icon.'</a>';
					break;
			}
		
		}

	}
	
	/**
	* Get Vimeo lightbox markup
	* @since: 1.0.0
	*/
	public function get_vimeo_lightbox($icon, $class, $ID) {
		
		$source = $this->base->getVar($this->grid_item['video'],'source',array());
		$VM_ID  = $this->base->getVar($source,'ID');
		
		if ($VM_ID) {
			
			if ($redirection = $this->lightbox_redirection($icon, $class, $ID)) {
				return $redirection;
			}
			
			$lightbox_type = $this->grid_data['lightbox_type'];
		
			switch ($lightbox_type) {
				case 'prettyphoto':
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" href="https://vimeo.com/'.esc_attr($VM_ID).'" rel="prettyPhoto[pp_gal]">'.$icon.'</a>';
					break;
				case 'fancybox':
					return '<a id="'.$ID.'" class="tg-media-button fancybox iframe'.esc_attr($class).'" rel="tg_group" href="//player.vimeo.com/video/'.esc_attr($VM_ID).'">'.$icon.'</a>';
					break;
				case 'foobox':
					return '<a id="'.$ID.'" class="tg-media-button foobox'.esc_attr($class).'" href="//vimeo.com/'.esc_attr($VM_ID).'" rel="foobox">'.$icon.'</a>';
					break;
				case 'the_grid':
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-tolb-src="'.esc_attr($VM_ID).'" data-tolb-type="vimeo" data-tolb-alt="">'.$icon.'</a>';
					break;
				case 'modulobox':
					$image = $this->base->getVar($this->grid_item['image'], 'lb_url');
					$image = (!$image) ? $this->base->getVar($this->grid_item['image'], 'url') : $image;
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-thumb="'.esc_url($this->grid_item['image']['url']).'" data-poster="'.esc_url($image).'" href="//vimeo.com/'.esc_attr($VM_ID).'" data-rel="'.esc_attr($this->grid_data['ID']).'">'.$icon.'</a>';
					break;
			}
		
		}
		
	}
	
	/**
	* Get Wistia lightbox markup
	* @since: 1.0.7
	*/
	public function get_wistia_lightbox($icon, $class, $ID) {
		
		$source = $this->base->getVar($this->grid_item['video'],'source',array());
		$WT_ID  = $this->base->getVar($source,'ID');
		
		if ($WT_ID) {
			
			if ($redirection = $this->lightbox_redirection($icon, $class, $ID)) {
				return $redirection;
			}
			
			$lightbox_type = $this->grid_data['lightbox_type'];

			switch ($lightbox_type) {
				case 'prettyphoto':
					$HTML_ID = 'V'.uniqid();
					$output  = '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" href="#'.esc_attr($HTML_ID).'" rel="prettyPhoto[pp_gal]">'.$icon.'</a>';
					$output .= '<span style="display:none" id="'.$HTML_ID.'">';
					$output .= '<iframe src="//fast.wistia.net/embed/iframe/'.esc_attr($WT_ID).'?videoFoam=true" width="500" height="344" frameborder="no" class="wistia_embed" name="wistia_embed"></iframe>';
					$output .= '</span>';
					return $output;
					break;
				case 'fancybox':
					return '<a id="'.$ID.'" class="tg-media-button fancybox iframe'.esc_attr($class).'" rel="tg_group" href="//fast.wistia.net/embed/iframe/'.esc_attr($WT_ID).'">'.$icon.'</a>';
					break;
				case 'foobox':
					return '<a id="'.$ID.'" class="tg-media-button foobox'.esc_attr($class).'" href="//fast.wistia.net/embed/iframe/'.esc_attr($WT_ID).'" rel="foobox">'.$icon.'</a>';
					break;
				case 'the_grid':
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-tolb-src="'.esc_attr($WT_ID).'" data-tolb-type="wistia" data-tolb-alt="">'.$icon.'</a>';
					break;
				case 'modulobox':
					$image = $this->base->getVar($this->grid_item['image'], 'lb_url');
					$image = (!$image) ? $this->base->getVar($this->grid_item['image'], 'url') : $image;
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-thumb="'.esc_url($this->grid_item['image']['url']).'" data-poster="'.esc_url($image).'" href="//fast.wistia.net/embed/iframe/'.esc_attr($WT_ID).'" data-rel="'.esc_attr($this->grid_data['ID']).'">'.$icon.'</a>';
					break;
			}
			
		}
		
	}
	
	/**
	* Get html5 video lightbox markup
	* @since: 1.0.0
	*/
	public function get_video_lightbox($icon, $class, $ID) {
		
		$mp4  = (isset($this->grid_item['video']['source']['mp4'])  && !empty($this->grid_item['video']['source']['mp4']))  ? esc_url($this->grid_item['video']['source']['mp4'])  : null;
		$ogv  = (isset($this->grid_item['video']['source']['ogv'])  && !empty($this->grid_item['video']['source']['ogv']))  ? esc_url($this->grid_item['video']['source']['ogv'])  : null;
		$webm = (isset($this->grid_item['video']['source']['webm']) && !empty($this->grid_item['video']['source']['webm'])) ? esc_url($this->grid_item['video']['source']['webm']) : null;
		
		if ($mp4 || $ogv || $webm) {
			
			if ($redirection = $this->lightbox_redirection($icon, $class, $ID)) {
				return $redirection;
			}
			
			$lightbox_type = $this->grid_data['lightbox_type'];

			switch ($lightbox_type) {
				case 'prettyphoto':
					$HTML_ID = 'V'.uniqid();
					$output  = '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" href="#'.esc_attr($HTML_ID).'" rel="prettyPhoto[pp_gal]">'.$icon.'</a>';
					$output .= '<span style="display:none" id="'.$HTML_ID.'">';
					$output .= '<video controls style="height:280px;width:500px">';
					$output .= ($mp4)  ? '<source src="'.esc_url($mp4).'" type="video/mp4">'   : null;
					$output .= ($webm) ? '<source src="'.esc_url($webm).'" type="video/webm">' : null;
					$output .= ($ogv)  ? '<source src="'.esc_url($ogv).'" type="video/ogg">'   : null;
					$output .= '</video>';
					$output .= '</span>';
					return $output;
					break;
				case 'fancybox':
					$video = ($mp4) ? $mp4 : null;
					$video = (!$video) ? $ogv : $video;
					$video = (!$video) ? $webm : $video;
					return '<a id="'.$ID.'" class="tg-media-button fancybox iframe'.esc_attr($class).'" rel="tg_group" href="'.esc_url($video).'">'.$icon.'</a>';		
					break;
				case 'foobox':
					$video = ($mp4) ? esc_url($mp4) : null;
					$video = ($video && $ogv) ? $video.','.esc_url($ogv) : $video.esc_url($ogv);
					$video = ($video && $webm) ? $video.','.esc_url($webm) : $video.esc_url($webm);
					return '<a id="'.$ID.'" class="tg-media-button foobox'.esc_attr($class).'" href="'.$video.'" rel="foobox">'.$icon.'</a>';
					break;
				case 'the_grid':
					$source = array();
					$mp4    = ($mp4)  ? array_push($source, '[{"type":"mp4","source":"'.esc_url($mp4).'"}]')   : null;
					$ogv    = ($ogv)  ? array_push($source, '[{"type":"ogg","source":"'.esc_url($ogv).'"}]')   : null;
					$webm   = ($webm) ? array_push($source, '[{"type":"webm","source":"'.esc_url($webm).'"}]') : null;
					$source = ($source) ? implode(',', $source) : null;
					$poster_url = $this->base->getVar($this->grid_item['image'], 'url');
					$poster = ($poster_url) ? ' data-tolb-poster="'.esc_url($poster_url).'"' : null;
					return ($source) ? '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-tolb-src=\'['.$source.']\' data-tolb-type="'.esc_attr($this->grid_item['format']).'" data-tolb-alt=""'.$poster.'>'.$icon.'</a>' : null;
					break;
				case 'modulobox':
					$video = ($mp4) ? esc_url($mp4) : null;
					$video = ($video && $ogv) ? $video.','.esc_url($ogv) : $video.esc_url($ogv);
					$video = ($video && $webm) ? $video.','.esc_url($webm) : $video.esc_url($webm);
					$poster_url = $this->base->getVar($this->grid_item['image'], 'url');
					$poster = ($poster_url) ? ' data-poster="'.esc_url($poster_url).'"' : null;
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-thumb="'.esc_url($this->grid_item['image']['url']).'" data-src="'.$video.'" data-rel="'.esc_attr($this->grid_data['ID']).'"'.$poster.'>'.$icon.'</a>';
					break;
			}

		}

	}
	
	/**
	* Get gallery lightbox markup
	* @since: 1.0.0
	*/
	public function get_gallery_lightbox($icon, $class, $ID) {

		$gallery = $this->base->getVar($this->grid_item, 'gallery');

		if ($gallery) {
			
			if ($redirection = $this->lightbox_redirection($icon, $class, $ID)) {
				return $redirection;
			}
			
			$lightbox_type = $this->grid_data['lightbox_type'];
			
			$output = null;
		
			switch ($lightbox_type) {
				case 'prettyphoto':
					for ($i = 0; $i < count($gallery); $i++) {
						$image = $gallery[$i]['lb_url'];
						$title = $gallery[$i]['title'];
						$alt   = $gallery[$i]['alt'];
						$alt    = (!empty($alt)) ? ucfirst($alt) : ucfirst($title);
						if ($i == 0) {
							$output .= '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" href="'.esc_url($image).'" rel="prettyPhoto[pp_gal]" title="'.esc_attr($alt).'">'.$icon.'</a>';
						} else {
							$output .= '<a class="tg-hidden-tag'.esc_attr($class).'" href="'.esc_url($image).'" rel="prettyPhoto[pp_gal]" title="'.esc_attr($alt).'"></a>';
						}
					}
					break;
				case 'fancybox':
					for ($i = 0; $i < count($gallery); $i++) {
						$image = $gallery[$i]['lb_url'];
						if ($i == 0) {
							$output .= '<a id="'.$ID.'" class="tg-media-button fancybox'.esc_attr($class).'" rel="tg_group" href="'.esc_url($image).'">'.$icon.'</a>';
						} else {
							$output .= '<a class="tg-hidden-tag fancybox" rel="tg_group" href="'.esc_url($image).'"></a>';
						}
					}
					break;
				case 'foobox':
					for ($i = 0; $i < count($gallery); $i++) {
						$image = $gallery[$i]['lb_url'];
						$title = $gallery[$i]['title'];
						$alt   = $gallery[$i]['alt'];
						$alt    = (!empty($alt)) ? ucfirst($alt) : ucfirst($title);
						if ($i == 0) {
							$output .= '<a id="'.$ID.'" class="tg-media-button foobox'.esc_attr($class).'" href="'.esc_url($image).'" title="'.esc_attr($alt).'" rel="foobox">'.$icon.'</a>';
						} else {
							$output .= '<a class="tg-hidden-tag foobox" href="'.esc_url($image).'" title="'.esc_attr($alt).'" rel="foobox"></a>';
						}
					}
					break;
				case 'the_grid':
					for ($i = 0; $i < count($gallery); $i++) {
						$image = $gallery[$i]['lb_url'];
						$title = $gallery[$i]['title'];
						$alt   = $gallery[$i]['alt'];
						$alt   = (!empty($alt)) ? ucfirst($alt) : ucfirst($title);
						if ($i == 0) {
							$output .= '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-tolb-src="'.esc_url($image).'" data-tolb-type="image" data-tolb-alt="'.esc_attr($alt).'">'.$icon.'</a>';
						} else {
							$output .= '<a class="tg-hidden-tag" data-tolb-src="'.esc_url($image).'" data-tolb-type="image" data-tolb-alt="'.esc_attr($alt).'"></a>';
						}
					}
					break;
				case 'modulobox':
					for ($i = 0; $i < count($gallery); $i++) {
						$image = $gallery[$i]['lb_url'];
						$thumb = $gallery[$i]['url'];
						$title = $gallery[$i]['title'];
						$alt   = $gallery[$i]['alt'];
						$alt   = (!empty($alt)) ? ucfirst($alt) : ucfirst($title);
						if ($i == 0) {
							$output .= '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-src="'.esc_url($image).'" data-thumb="'.esc_url($thumb).'" data-type="image" data-title="'.esc_attr($alt).'" data-rel="'.esc_attr($this->grid_data['ID']).'">'.$icon.'</a>';
						} else {
							$output .= '<a class="tg-media-button tg-hidden-tag" data-src="'.esc_url($image).'" data-type="image" data-thumb="'.esc_url($thumb).'" data-title="'.esc_attr($alt).'" data-rel="'.esc_attr($this->grid_data['ID']).'"></a>';
						}
					}
					break;
			}
			
			return $output;
		
		}
		
		
	}
	
	/**
	* Get image lightbox markup
	* @since: 1.0.0
	*/
	public function get_image_lightbox($icon, $class, $ID) {

		$image = $this->base->getVar($this->grid_item['image'], 'lb_url');
		$image = (!$image) ? $this->base->getVar($this->grid_item['image'], 'url') : $image;
		
		if ($image) {
			
			if ($redirection = $this->lightbox_redirection($icon, $class, $ID)) {
				return $redirection;
			}
			
			$lightbox_type = $this->grid_data['lightbox_type'];
			$title = $this->base->getVar($this->grid_item['image'], 'title');
			$alt   = $this->base->getVar($this->grid_item['image'], 'alt');
			$alt   = (!empty($alt)) ? ucfirst($alt) : ucfirst($title);
			
			switch ($lightbox_type) {
				case 'prettyphoto':
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" href="'.esc_url($image).'" rel="prettyPhoto[pp_gal]" title="'.esc_attr($alt).'">'.$icon.'</a>';
					break;
				case 'fancybox':
					return '<a id="'.$ID.'" class="tg-media-button fancybox'.esc_attr($class).'" href="'.esc_url($image).'" rel="tg_group">'.$icon.'</a>';
					break;
				case 'foobox':
					return '<a id="'.$ID.'" class="tg-media-button foobox'.esc_attr($class).'" href="'.esc_url($image).'" title="'.esc_attr($alt).'" rel="foobox">'.$icon.'</a>';
					break;
				case 'the_grid':
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-tolb-src="'.esc_url($image).'" data-tolb-type="image" data-tolb-alt="'.esc_attr($alt).'">'.$icon.'</a>';
					break;
				case 'modulobox':
					return '<a id="'.$ID.'" class="tg-media-button'.esc_attr($class).'" data-src="'.esc_url($image).'" data-type="image" data-thumb="'.esc_url($this->grid_item['image']['url']).'" data-title="'.esc_attr($alt).'" data-rel="'.esc_attr($this->grid_data['ID']).'">'.$icon.'</a>';
					break;
			}
		
		}
		
	}
	
	public function lightbox_redirection($icon, $class, $ID) {
		
		$class = $class ? 'class="'.esc_attr(trim($class)).'" ' : null;
		return $this->lightbox_output ? '<a '.$class.'data-tolb-id="'.$ID.'">'.$icon.'</a>' : null;
	
	}
	
	/**
	* Set default colors from item/skin grid settings
	* @since: 1.0.0
	*/
	public function get_colors() {
			
		// get meta data
		$meta_data = $this->base->getVar($this->grid_item, 'meta_data', array());
			
		// content colors
		$content_co_skin = $this->grid_data['skin_content_color'];
		$content_co_item = $this->base->getVar($meta_data, 'the_grid_item_content_color');		
		$content_bg_skin = $this->grid_data['skin_content_background'];
		$content_bg_item = $this->base->getVar($meta_data, 'the_grid_item_content_background');
		$content_co      = (empty($content_bg_item)) ? $content_co_skin : $content_co_item;
		$content_bg      = (empty($content_bg_item)) ? $content_bg_skin : $content_bg_item;
			
		// overlay colors
		$overlay_co_skin = $this->grid_data['skin_overlay_color'];
		$overlay_co_item = $this->base->getVar($meta_data, 'the_grid_item_overlay_color');		
		$overlay_bg_skin = $this->grid_data['skin_overlay_background'];
		$overlay_bg_item = $this->base->getVar($meta_data, 'the_grid_item_overlay_background');
		$overlay_co      = (empty($overlay_bg_item)) ? $overlay_co_skin : $overlay_co_item;
		$overlay_bg      = (empty($overlay_bg_item)) ? $overlay_bg_skin : $overlay_bg_item;

		// defaults colors
		$def_colors = array(
			'dark_title'  => '#444444',
			'dark_text'   => '#777777',
			'dark_span'   => '#999999',
			'light_title' => '#ffffff',
			'light_text'  => '#eeeeee',
			'light_span'  => '#dddddd'
		);
			
		$grid_colors = $this->base->getVar($this->grid_data, 'grid_colors', array());
		$grid_colors_content_co = $this->base->getVar($grid_colors, $content_co, array());
		$grid_colors_overlay_co = $this->base->getVar($grid_colors, $overlay_co, array());
			
		// defined color array
		return array(
			'content' => array(
				'background' => $content_bg,
				'class' => 'tg-'.$content_co,
				'title' => $this->base->getVar($grid_colors_content_co,'title',$def_colors[$content_co.'_title']),
				'text'  => $this->base->getVar($grid_colors_content_co,'text',$def_colors[$content_co.'_title']),
				'span'  => $this->base->getVar($grid_colors_content_co,'span',$def_colors[$content_co.'_title']),
			),
			'overlay' => array(
				'background' => $overlay_bg,
				'class' => 'tg-'.$overlay_co,
				'title' => $this->base->getVar($grid_colors_overlay_co,'title',$def_colors[$overlay_co.'_title']),
				'text'  => $this->base->getVar($grid_colors_overlay_co,'text',$def_colors[$overlay_co.'_title']),
				'span'  => $this->base->getVar($grid_colors_overlay_co,'span',$def_colors[$overlay_co.'_title']),
			)
		);

	}
	
}

if(!function_exists('The_Grid_Elements')) {
	
	/**
	* Tiny wrapper function
	* @since 1.0.0
	*/
	function The_Grid_Elements() {
		$to_Item_Content = The_Grid_Elements::getInstance();	
		$to_Item_Content->init();
		return $to_Item_Content;
	}
	
}