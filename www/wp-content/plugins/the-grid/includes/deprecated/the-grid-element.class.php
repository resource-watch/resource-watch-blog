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
 
/***********************************************
* THE GRID CONTENT Class
***********************************************/

class The_Grid_Item_Content {

	/**
	 * Plugin slug & prefix
	 * @since 1.0.0
	 */
	protected $plugin_slug = TG_SLUG;
	protected $grid_prefix = TG_PREFIX;
	protected $grid_base;
	protected $grid_data;
	protected $grid_style;
	protected $item_format;
	protected $post_type;
	protected $post_format;
	protected $tg_item;
	
	private $base;
	
	/**
	 * Custom link/target
	 * @since 1.0.5
	 */
	private $custom_link   = '';
	private $custom_target = '_blank';
	
	/**
	 * Main var for this instance
	 * @since 1.0.0
	 */
	private $iframe_poster;
	private $media_poster   = true;
	private $video_lightbox = false;
	private $allowed_format = array('gallery','video','audio');
	private $icons = array(
		'link'       => '<i class="tg-icon-link"></i>',
		'comment'    => '',
		'image'      => '<i class="tg-icon-zoom-in"></i>',
		'audio'      => '<i class="tg-icon-play"></i>',
		'video'      => '<i class="tg-icon-play"></i>',
		'vimeo'      => '<i class="tg-icon-play"></i>',
		'wistia'     => '<i class="tg-icon-play"></i>',
		'youtube'    => '<i class="tg-icon-play"></i>',
		'soundcloud' => '<i class="tg-icon-play"></i>'
	);
	private $excerpt_length  = 280;
	private $excerpt_tag     = '...';
	private $read_more       = '';
	private $date_format     = '';
	private $get_terms       = true;
	private $term_color      = 'none';
	private $term_link       = true;
	private $term_separator  = '';
	private $item_colors     = array();
	private $author_prefix   = '';
	private $avatar          = false;

	/**
	* The singleton instance
	* @since 1.0.0
	*/
	static private $instance = null;
	
	/**
	* No initialization allowed
	* @since 1.0.0
	*/
	private function __construct() {}
	
	/**
	* to initialize a The_Grid_Item_Content object
	* @since 1.0.0
	*/
	static public function getInstance() {
		if(self::$instance == null) {
			self::$instance = new self;
		}
		return self::$instance;
	}
		
	/**
	* Initialize the plugin by retrieving and settings all grid options & data
	* @since: 1.0.0
	*/
	public function process($options) {
		
		global $tg_deprecated_msg;

		/*$show_deprecated = apply_filters('tg_deprecated_warning', true);
		
		if ($show_deprecated && !$tg_deprecated_msg) {
			$tg_deprecated_msg = true;
			trigger_error('PHP Deprecated: The_Grid_Item_Content() is deprecated since The Grid v1.5.0 Please, look at the developer documentation to build skin with the new API', E_USER_WARNING);
		}*/
		
		// set the grid base helper class
		$this->base = new The_Grid_Base();
		
		// set main item/grid vars
		$this->set_var($options);

		// get media content (for all formats retieve markup for image/gallery, video, audio)
		$media_content = $this->get_media();
		
		// get quote/link content
		$quote_link = $this->get_quote_link_markup();
		
		// get center/content wrapper markup
		$content['center_wrapper_start']  = $this->center_wrapper_start();
		$content['center_wrapper_end']    = $this->center_wrapper_end();
		$content['content_wrapper_start'] = $this->content_wrapper_start();
		$content['content_wrapper_end']   = $this->content_wrapper_end();
		
		// get main content
		$content['overlay']      = $this->overlay_markup();
		$content['content']      = $this->get_excerpt();
		$content['title']        = $this->get_title();
		$content['date']         = $this->get_date();
		$content['terms']        = $this->get_terms();
		$content['comments']     = $this->get_comments();
		$content['author']       = $this->get_author();
		$content['duration']     = $this->get_duration();
		$content['views']        = $this->get_views();
		$content['read_more']    = $this->get_read_more();
		$content['post_like']    = $this->get_post_like();
		$content['social_links'] = $this->get_social_share();
		$content['colors']       = $this->item_colors;
		
		// get link
		$content['permalink'] = $this->get_the_permalink();
		$content['target']    = $this->custom_target;
		
		// get woocommerce data
		$content['product_price']  = $this->get_product_price();
		$content['product_rating'] = $this->get_product_rating();
		$content['product_sale']   = $this->get_product_on_sale();
		$content['product_cart_button'] = $this->get_product_cart_button();
		$content['product_wishlist'] = $this->get_product_wishlist();
		
		// merge content + media content
		$content = $media_content + $quote_link + $content;	

		// return awesome array of all necessary info to build an element
		return $content;
		
	}
	
	/**
	* Assign & set main variables
	* @since: 1.0.0
	*/
	public function set_var($options) {
		
		// set current social item data
		$this->tg_item = tg_get_grid_item();
		// re-assign grid data for current class
		$this->grid_data = tg_get_grid_data();
		
		// get the post format
		$this->post_format = $this->base->getVar($this->tg_item, 'format');
		$this->post_format = (empty($this->post_format)) ? 'standard' : $this->post_format;
		
		// set the custom link/target
		$this->custom_link   = '';
		$this->custom_target = '_blank';
		
		// Set main var from current grid data
		$this->video_lightbox = $this->grid_data['video_lightbox'];
		$this->allowed_format = (isset($this->grid_data['items_format'])) ? $this->grid_data['items_format'] : $this->allowed_format;
		$this->grid_style     = $this->grid_data['style'];
		// Set var to get or not video/youtube/soundcloud posters (depends of the skin)
		$this->media_poster   = (isset($options['poster'])) ? $options['poster'] : $this->media_poster;
		$this->iframe_poster  = ($this->media_poster) ? true : false;
		// Set media icons
		$icons = (isset($options['icons'])) ? $options['icons'] : null;
		$this->icons['image'] = (isset($icons['image']) && !empty($icons['image'])) ? $icons['image'] : $this->icons['image'];
		$this->icons['audio'] = (isset($icons['audio']) && !empty($icons['audio'])) ? $icons['audio'] : $this->icons['audio'];
		$this->icons['video'] = (isset($icons['video']) && !empty($icons['video'])) ? $icons['video'] : $this->icons['video'];
		$this->icons['vimeo'] = (isset($icons['vimeo']) && !empty($icons['vimeo'])) ? $icons['vimeo'] : $this->icons['vimeo'];
		$this->icons['wistia'] = (isset($icons['wistia']) && !empty($icons['wistia'])) ? $icons['wistia'] : $this->icons['wistia'];
		$this->icons['youtube'] = (isset($icons['youtube']) && !empty($icons['youtube'])) ? $icons['youtube'] : $this->icons['youtube'];
		$this->icons['soundcloud'] = (isset($icons['soundcloud']) && !empty($icons['soundcloud'])) ? $icons['soundcloud'] : $this->icons['soundcloud'];
		// Set link icon
		$this->link_icon = (isset($icons['link']) && !empty($icons['link'])) ? $icons['link'] : $this->icons['link'];
		// Set comment icon
		$this->comment_icon = (isset($icons['comment']) && !empty($icons['comment'])) ? $icons['comment'] : $this->icons['comment'];
		// Set excerpt length & more tag
		$this->excerpt_length  = (isset($options['excerpt_length'])) ? $options['excerpt_length'] : $this->excerpt_length;
		$this->excerpt_tag     = (isset($options['excerpt_tag']) && !empty($options['excerpt_tag'])) ? $options['excerpt_tag'] : $this->excerpt_tag;
		// Set read more text/icon
		$this->read_more       = (isset($options['read_more']) && !empty($options['read_more'])) ? $options['read_more'] : __( 'Read More', 'tg-text-domain' );
		// Set date format
		$this->date_format     = (isset($options['date_format']) && !empty($options['date_format'])) ? $options['date_format'] : $this->date_format;
		// Retrieve categories
		$this->get_terms       = (isset($options['get_terms'])) ? $options['get_terms'] : $this->get_terms;
		// Set terms color
		$this->term_color      = (isset($options['term_color']) && !empty($options['term_color'])) ? $options['term_color'] : $this->term_color;
		// Set link in terms
		$this->term_link       = (isset($options['term_link'])) ? $options['term_link'] : $this->term_link;
		// Set terms separator
		$this->term_separator  = (isset($options['term_separator'])) ? $options['term_separator'] : $this->term_separator;
		// Set avatar
		$this->author_prefix   = (isset($options['author_prefix'])) ? $options['author_prefix'] : $this->author_prefix;
		// Set avatar
		$this->avatar = (isset($options['avatar'])) ? $options['avatar'] : $this->avatar;
		// Set item colors
		$this->set_colors();
		
	}
	
	/**
	* Set default colors from item/skin grid settings
	* @since: 1.0.0
	*/
	public function set_colors() {
		
		// get meta data
		$meta_data = $this->base->getVar($this->tg_item, 'meta_data', array());
		
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
		$this->item_colors = array(
			'content' => array(
				'background' => $content_bg,
				'class' => $content_co,
				'title' => $this->base->getVar($grid_colors_content_co,'title',$def_colors[$content_co.'_title']),
				'text'  => $this->base->getVar($grid_colors_content_co,'text',$def_colors[$content_co.'_title']),
				'span'  => $this->base->getVar($grid_colors_content_co,'span',$def_colors[$content_co.'_title']),
			),
			'overlay' => array(
				'background' => $overlay_bg,
				'class' => $overlay_co,
				'title' => $this->base->getVar($grid_colors_overlay_co,'title',$def_colors[$overlay_co.'_title']),
				'text'  => $this->base->getVar($grid_colors_overlay_co,'text',$def_colors[$overlay_co.'_title']),
				'span'  => $this->base->getVar($grid_colors_overlay_co,'span',$def_colors[$overlay_co.'_title']),
			)
		);	
	
	}

	/**
	* Main function to get formated media (used for skin)
	* return an array of data (url,id,size,type) for each media and the markup
	* @since: 1.0.0
	*/
	public function get_media() {
		
		$format = $this->base->getVar($this->tg_item, 'format');

		// check allowed formats from grid settings (Gallery, Video or Audio). Force std format if not authorized
		$format  = (!empty($format) && in_array($format,$this->allowed_format)) ? $format : 'image';

		$media = null;

		// serach for the media content depend on of the format
		switch ($format) {
			case 'gallery':
				$content = $this->gallery_markup();
				break;
			case 'audio':
				$content = $this->audio_markup();
				break;
			case 'video':
				$content = $this->video_format();
				break;
			default:
				$content = $this->image_markup();
				break;
		}

		// set the post format
		$media['post_format']  = $format;

		// set the real content type (not necessary format if missing video or audio,...)
		$media['media_type']         = ($format == 'standard') ? 'image' : $format;
		$media['media_data']['type'] = ($format == 'standard') ? 'image' : $format;
		$media['media_data']['url']  = esc_url($this->base->getVar($this->tg_item['image'], 'url'));
		$this->item_format = $media['media_type'];
		// retrieve the media poster (Youtube, Vimeo, SoundCloud, Video, Audio)
		$media['media_poster'] = $this->media_poster();
		// retrieve the media markup
		$media['media_markup'] = $content;

		// lightBox/Play button conditions
		// Add the lightbox if a poster exist or if it's an image
		$poster_exist   = $media['media_poster']['markup'];        // if there is a poster
		$media_image    = (($media['media_type'] == 'image' || $media['media_type'] == 'gallery')); // if it's an image/gallery
		$lightbox_video = ($this->video_lightbox);           // if video in lightbox
		$media['media_button'] = ($poster_exist || $media_image  || $lightbox_video && $poster_exist || $this->grid_style == 'grid') ? $this->get_lightbox($media) : null;
		$media['link_button']  = ($media['media_button']) ? $this->get_post_link() : null;
		
		
		// get media wrapper markup
		$media['media_wrapper_start'] = ($media['media_markup']) ? $this->media_wrapper_start() : null;
		$media['media_wrapper_end']   = ($media['media_markup']) ? $this->media_wrapper_end()   : null;

		return $media;

	}
	
	/**
	* Get Quote link Markup
	* return an array of data
	* @since: 1.0.0
	*/
	public function get_quote_link_markup() {
		
		$post_id = $this->base->getVar($this->tg_item, 'ID');
		$format  = $this->base->getVar($this->tg_item, 'format');
		
		// check allowed formats from grid settings (Gallery, Video or Audio). Force std format if not authorized
		$format  = (!empty($format) && in_array($format,$this->allowed_format)) ? $format : 'standard';
	
		$content = null;
		$data = array();
		
		// serach for the media content depend on of the format
		switch ($format) {
			case 'quote':
				$data[$format.'_markup'] = $this->quote_markup();
				break;
			case 'link':
				$data[$format.'_markup'] = $this->link_markup();
				break;
		}

		return $data;
		
	}

	/**
	* Media holder markup start 
	* @since: 1.0.0
	*/
	public function media_wrapper_start() {	
		$class = $this->item_colors['overlay']['class'];
		return '<div class="tg-item-media-holder '.esc_attr($class).'">';
	}
	
	/**
	* Media holder markup end 
	* @since: 1.0.0
	*/
	public function media_wrapper_end() {	
		return '</div>';
	}
	
	/**
	* Content holder markup start (masonry)
	* @since: 1.0.0
	*/
	public function content_wrapper_start() {
		$class   = $this->item_colors['content']['class'];
		$format  = $this->item_format.'-format';
		$bg_skin = $this->grid_data['skin_content_background'];
		$bg_item = $this->item_colors['content']['background'];
		$background = ($bg_skin != $bg_item) ? 'style="background-color:'.esc_attr($bg_item).'"' : null;
		return '<div class="tg-item-content-holder '.esc_attr($class).' '.esc_attr($format).'" '.$background.'>';
	}
	
	/**
	* Content holder markup end  (masonry)
	* @since: 1.0.0
	*/
	public function content_wrapper_end() {	
		return '</div>';
	}
	
	/**
	* Center markup Start
	* @since: 1.0.0
	*/
	public function center_wrapper_start() {	
		$html  = '<div class="tg-center-outer">';
			$html .= '<div class="tg-center-inner">';
		return $html;
	}
	
	/**
	* Center markup End
	* @since: 1.0.0
	*/
	public function center_wrapper_end() {	
			$html  = '</div>';
		$html .= '</div>';
		return $html;
	}
	
	/**
	* Overlay markup
	* @since: 1.0.0
	*/
	public function overlay_markup() {
		
		$bg_skin = $this->grid_data['skin_overlay_background'];
		$bg_item = $this->item_colors['overlay']['background'];
		$background = ($bg_skin != $bg_item) ? 'style="background-color:'.esc_attr($bg_item).'"' : null;
		$html  = '<div class="tg-item-overlay" '.$background.'></div>';
		return $html;
		
	}
	
	/**
	* Comment number
	* @since: 1.0.0
	*/
	public function get_comments() {
		
		$cache_date = $this->base->getVar($this->grid_data, 'cache_date');
		$icon = $this->comment_icon;
		$num_comments = (empty($cache_date)) ? $this->base->getVar($this->tg_item, 'comments_number') : get_comments_number($this->base->getVar($this->tg_item, 'ID'));
		$write_comments = null;
		$nonCom  = __( 'No comment', 'tg-text-domain' );
		$oneCom  = __( 'comment', 'tg-text-domain' );
		$sevCom  = __( 'Comments', 'tg-text-domain' );
		$dataCom = 'data-comment=\'{"no":"'.$nonCom.'","one":"'.$oneCom.'","plus":"'.$sevCom.'"}\'';
		$target = $this->base->getVar($this->tg_item, 'url_target');
		if (!$icon) {
			if ($num_comments == 0) {
				$comments = $nonCom;
			} else if ($num_comments == 1) {
				$comments = $num_comments .' '. $oneCom;
			} else {
				$num_comments = $this->base->shorten_number_format($num_comments);
				$comments = $num_comments .' '. $sevCom;
			}
			$write_comments = '<a class="tg-item-comment" '.$dataCom.' href="' . esc_url($this->base->getVar($this->tg_item, 'url')) .'"  target="'.esc_attr($target).'">'. $comments.'</a>';
		} else {
			$num_comments = $this->base->shorten_number_format($num_comments);
			$write_comments = '<a class="tg-item-comment" href="' . esc_url($this->base->getVar($this->tg_item, 'url')) .'"  target="'.esc_attr($target).'">'.$icon.$num_comments.'</a>';
		}
		$comment['markup'] = $write_comments;
		$comment['number'] = $num_comments;
		return $comment;

	}
	
	/**
	* Get author
	* @since: 1.0.0
	*/
	public function get_author() {
		
		$author = $this->base->getVar($this->tg_item, 'author');
		$author_prefix = '<span>'.esc_html($this->author_prefix).'</span>';
		$author_name   = $this->base->getVar($author, 'name');
		$author_url    = $this->base->getVar($author, 'url');
		$author_avatar = $this->base->getVar($author, 'avatar');
		$url_target    = $this->base->getVar($this->tg_item, 'url_target');
		
		if ($author_name) {
			
			if ($this->avatar) {
				$html  = '<div class="tg-item-author-holder">';
				$html .= ($author_avatar) ? '<span class="tg-item-avatar"><img src="'.$author_avatar.'"/></span>' : null;
				$html .= '<span class="tg-item-author">'.$author_prefix;
				$html .= ($author_url) ? '<a href="'. esc_url($author_url) .'" target="'.$url_target.'">' : null;
				$html .= (!$author_url) ? '<span class="tg-item-author-name">' : null;
				$html .= $author_name;
				$html .= (!$author_url) ? '</span>' : null;
				$html .= ($author_url) ? '</a>' : null;
				$html .= '</span>';
				$html .= '</div>';
			} else {
				$html  = '<span class="tg-item-author">'.$author_prefix;
				$html .= ($author_url) ? '<a href="'. esc_url($author_url) .'" target="'.$url_target.'">' : null;
				$html .= (!$author_url) ? '<span class="tg-item-author-name">' : null;
				$html .= $author_name;
				$html .= (!$author_url) ? '</span>' : null;
				$html .= ($author_url) ? '</a>' : null;
				$html .= '</span>';
			}
			
			return $html;
		}
		
	}
	
	/**
	* Get duration
	* @since: 1.0.0
	*/
	public function get_duration() {
		$duration = $this->base->getVar($this->tg_item['video'], 'duration');
		if (!empty($duration)) {
			$duration = $duration;
			$html = '<div class="tg-item-duration">'.$duration.'</div>';
			return $html;
		}
	}
	
	/**
	* Get views
	* @since: 1.0.0
	*/
	public function get_views() {
		$views = $this->base->getVar($this->tg_item, 'views_number');
		if (!empty($views)) {
			$views = $this->base->shorten_number_format($views);
			$html = '<span class="tg-item-views">'.$views.' '.__( 'views', 'tg-text-domain' ) .'</span>';
			return $html;
		}
	}
	
	/**
	* Get read more
	* @since: 1.0.0
	*/
	public function get_read_more() {
		$url = $this->base->getVar($this->tg_item, 'url');
		$url_target = $this->base->getVar($this->tg_item, 'url_target');
		if (!empty($url)) {
			$html  = '<div class="tg-item-read-more">';
			$html .= '<a href="'.esc_url($url).'" target="'.esc_attr($url_target).'">';
			$html .= $this->read_more;
			$html .= '</a>';
			$html .= '</div>';
			return $html;
		}
	}
	
	/**
	* Media title 
	* @since: 1.0.0
	*/
	public function get_post_link() {	
		$url = $this->base->getVar($this->tg_item, 'url');
		$url_target = $this->base->getVar($this->tg_item, 'url_target');
		if (!empty($url)) {
			return '<a class="tg-link-button" href="'.esc_url($url).'" target="'.esc_attr($url_target).'">'. $this->link_icon .'</a>';
		}
	}
	
	/**
	* Retrieve title
	* @since: 1.0.0
	*/
	public function get_title() {
		$title = $this->base->getVar($this->tg_item, 'title');
		if (!empty($title)) {
			$url    = $this->base->getVar($this->tg_item, 'url');
			$target = $this->base->getVar($this->tg_item, 'url_target');
			$html  = '<h2 class="tg-item-title">';
			$html .= (!empty($url)) ? '<a href="'.esc_url($url).'" target="'.esc_attr($target).'">' : null;
			$html .= $title;
			$html .= (!empty($url)) ? '</a>' : null;
			$html .= '</h2>';
			return $html;
		}
	}
	
	/**
	* Get the permalink
	* @since: 1.0.5
	*/
	public function get_the_permalink() {
		$permalink = $this->base->getVar($this->tg_item, 'url');
		return $permalink;
	}
	
	/**
	* Retrieve excerpt/content
	* @since: 1.0.0
	*/
	public function get_excerpt() {
		
		$charlength = $this->excerpt_length;
		if (!empty($charlength)) {
			$more_tag   = $this->excerpt_tag;
			$excerpt    = $this->base->getVar($this->tg_item, 'excerpt');
			$charlength++;
			if (mb_strlen($excerpt) > $charlength) {
				$subex   = mb_substr($excerpt, 0, $charlength - 5);
				$exwords = explode( ' ', $subex );
				$excut   = - (mb_strlen($exwords[count($exwords) - 1]));
				if ($excut < 0) {
					$content = mb_substr($subex, 0, $excut);
				} else {
					$content = $subex;
				}
			} else {
				$content = $excerpt;
			}
			if (!empty($content)) {
				return '<p class="tg-item-excerpt">'.$content.esc_html($more_tag).'</p>';
			}
		}
	}
	
	/**
	* Retrieve the date
	* @since: 1.0.0
	*/
	public function get_date() {
		
		$date = $this->base->getVar($this->tg_item, 'date');
		
		if ($date) {
		
			if ($this->grid_data['source_type'] != 'post_type') {
				$date = $this->base->time_elapsed_string($date);
			} else {
				$date_format = (empty($this->date_format)) ? $this->grid_data['date_format'] : $this->date_format;
				$date = date($date_format, $date);
			}
			
			return '<span class="tg-item-date">'.$date.'</span>';
			
		}
		
	}
	
	/**
	* Retrieve post like
	* @since: 1.0.0
	*/
	public function get_post_like() {
		
		$source_type = $this->base->getVar($this->grid_data, 'source_type');
		$likes = $this->base->getVar($this->tg_item, 'likes_number');
		
		if ($source_type == 'post_type' && !is_numeric($likes)) {
			
			$cache_date = $this->base->getVar($this->grid_data, 'cache_date');
			if (empty($cache_date)) {
				$output = $likes;
			} else {
				$ID = $this->base->getVar($this->tg_item, 'ID');
				$output = TO_get_post_like($ID);
			}
			
		} else {
			
			$url = $this->base->getVar($this->tg_item, 'url');

			$heart  = '<svg class="to-heart-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 64 64">';
			$heart .= '<g transform="translate(0, 0)">';
			$heart .= '<path stroke-width="6" stroke-linecap="square" stroke-miterlimit="10" d="M1,21c0,20,31,38,31,38s31-18,31-38  c0-8.285-6-16-15-16c-8.285,0-16,5.715-16,14c0-8.285-7.715-14-16-14C7,5,1,12.715,1,21z"></path>';
			$heart .= '</g>';
			$heart .= '</svg>';
			
			$title = $this->base->getVar($this->tg_item, 'likes_title');
			$title = ($title) ? ' title="'.esc_attr($title).'"' : null;
			$target = $this->base->getVar($this->tg_item, 'url_target');
			
			$output = '<span class="no-ajaxy to-post-like to-post-like-unactive  empty-heart"'.$title.'>';
				$output .= ($url && $source_type != 'post_type') ? '<a href="'.esc_url($url).'" target="'.esc_attr($target).'">' : null;
					$output .= $heart;
					$output .= '<span class="to-like-count">';
						$output .= esc_attr($this->base->shorten_number_format($likes));
					$output .= '</span>';
				$output .= ($url && $source_type != 'post_type') ? '</a>' : null;
			$output .= '</span>';
		
		}
		
		return $output;
	}
	
	/**
	* Retrieve social share
	* @since: 1.0.0
	*/
	public function get_social_share() {
		
		if ($this->grid_data['source_type'] == 'post_type') {
		
			return array(
				'facebook'  => '<a href="#" class="tg-facebook"><i class="tg-icon-facebook"></i></a>',
				'twitter'   => '<a href="#" class="tg-twitter"><i class="tg-icon-twitter"></i></a>',
				'google+'   => '<a href="#" class="tg-google1"><i class="tg-icon-google-plus"></i></a>',
				'pinterest' => '<a href="#" class="tg-pinterest"><i class="tg-icon-pinterest"></i></a>'
			);
		
		}
		
	}
	
	/**
	* Retrieve Woocommerce price
	* @since: 1.0.0
	*/
	public function get_product_price() {
		
		$product = $this->base->getVar($this->tg_item, 'product');
		$price   = $this->base->getVar($product, 'price');
		return ($price) ? '<div class="tg-item-price">'.$price.'</div>' : null;
		
	}
	/**
	* Retrieve Woocommerce rating
	* @since: 1.0.0
	*/
	public function get_product_rating() {
		
		$product = $this->base->getVar($this->tg_item, 'product');
		$rating  = $this->base->getVar($product, 'rating');
		return ($rating) ? '<div class="tg-item-rating">'.$rating.'</div>' : null;
		
	}
	
	/**
	* Retrieve Woocommerce sale status
	* @since: 1.0.0
	*/
	public function get_product_on_sale() {
		
		$product = $this->base->getVar($this->tg_item, 'product');
		$on_sale = $this->base->getVar($product, 'on_sale');
		return ($on_sale) ? '<div class="tg-item-on-sale light">'.$on_sale.'</div>' : null;
		
	}
	
	/**
	* Retrieve Woocommerce add to cart button
	* @since: 1.0.0
	*/
	public function get_product_cart_button() {
		
		$product = $this->base->getVar($this->tg_item, 'product');
		$cart_button = $this->base->getVar($product, 'cart_button');
		return ($cart_button) ? '<div class="tg-item-cart-button">'.$cart_button.'</div>' : null;
		
	}
	
	/**
	* Retrieve Woocommerce YITH Whislist
	* @since: 1.0.0
	*/
	public function get_product_wishlist() {
		
		$product = $this->base->getVar($this->tg_item, 'product');
		return $this->base->getVar($product, 'wishlist');
	
	}
	
	/**
	* Retrieve categories
	* @since: 1.0.0
	*/
	public function get_terms() {
		
		$cat = null;
		$terms = $this->base->getVar($this->tg_item, 'terms');
		
		if (!empty($terms)) {
			
			global $wp_rewrite;
		
			$i = 0;
			$separator = $this->term_separator;
			$rel_attr  = (is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks()) ? 'rel="category tag"' : 'rel="category"';
				
			foreach ($terms as $term) {
					
				$color = null;
				if ($this->term_color == 'background' || $this->term_color == 'color') { 
				
					$term_color = $term['color'];
					
					if ($this->term_color == 'background') {
						$brightness = $this->base->brightness($term_color);
						$color = ($brightness == 'bright') ? '#000000' : '#ffffff';
						$color = (!empty($term_color)) ? ' style="background:'.$term_color.';color:'.$color.'"' : null;
					} else {
						$color = (!empty($term_color)) ? ' style="color:'.$term_color.'"' : null;
					}

				}
					
				if ($i > 0 && !empty($separator)) {
					$cat .= '<span>'.$separator.'</span>';
				}
				if ($this->term_link) {
					$cat .= '<a class="'. $term['taxonomy'] .'" href="' . esc_url($term['url']) . '" ' . $rel_attr  . '><span'.$color.'>' . $term['name'].'</span></a>';
				} else {
					$cat .= '<span class="'. $term['taxonomy'] .'"'.$color.'>'. $term['name'] .'</span>';
				}
				++$i;
			}
		}
			
		if (!empty($cat)) {
			$html  = '<div class="tg-cats-holder">';
			$html .= $cat;
			$html .= '</div>';
			return $html;
		}
		
	}
	
	/**
	* Image markup
	* @since: 1.0.0
	*/
	public function image_markup() {
				
		$url = $this->base->getVar($this->tg_item['image'], 'url');
		$markup = null;
		
		if (!empty($url)) {
		
			if ($this->grid_style == 'masonry' && $this->video_lightbox && ($this->grid_data['source_type'] == 'youtube' || $this->grid_data['source_type'] == 'vimeo')) {
				$poster  = $this->media_poster();
				$markup .= '<div class="tg-item-media-inner" data-ratio="16:9"></div>';
				$markup .= $poster['markup'];
			} else if ($this->grid_style == 'grid') {
				$markup = '<div class="tg-item-image" style="background-image: url('.$url.')"></div>';
			} else {
				$alt    = $this->base->getVar($this->tg_item['image'], 'alt');
				$width  = $this->base->getVar($this->tg_item['image'], 'width');
				$height = $this->base->getVar($this->tg_item['image'], 'height');
				$markup = '<img class="tg-item-image" alt="'.$alt.'" width="'.$width.'" height="'.$height.'" src="'.esc_url($url).'">';
			}
		
		}
		
		return $markup;
	}
	
	/**
	* Gallery markup
	* @since: 1.0.0
	*/
	public function gallery_markup() {
		
		$markup = '<div class="tg-item-gallery-holder">';
		$first_img = ' first-image show';
		$images = $this->base->getVar($this->tg_item, 'gallery');
		$style     = $this->grid_data['style'];
		$slideshow = $this->grid_data['gallery_slide_show'];
		
		if ($style == 'grid') {
			foreach($images as $image) {
				if ($first_img || ($slideshow && !$first_img)) {
					$markup .= '<div class="tg-item-image'.$first_img.'" style="background-image: url('.esc_url($image['url']).')"></div>';
				}
				$first_img = null;
			}				
		} else {
			foreach($images as $image) {
				$url = esc_url($image['url']);
				$alt = esc_attr($image['alt']);	
				if ($first_img) {
					$width  = esc_attr($image['width']);
					$height = esc_attr($image['height']);	
					$markup .= '<img class="tg-item-image'.$first_img.'" alt="'.$alt.'" width="'.$width.'" height="'.$height.'" src="'.$url.'">';
				} else {
					$markup .= '<div class="tg-item-image" style="background-image: url('.$url.')"></div>';
				}
				$first_img = null;
			}
		}
		
		$markup .= '</div>';
		
		return $markup;
	}
	
	/**
	* Quote markup
	* @since: 1.0.0
	*/
	public function quote_markup() {
		
		$source = $this->base->getVar($this->tg_item['quote'], 'source');
		$quote  = $this->base->getVar($source, 'content');
		$author =  $this->base->getVar($source, 'author');
		
		$markup  = '<h2 class="tg-quote-content tg-item-title"><a href="'.get_permalink().'">'.esc_html($quote).'</a></h2>';
		$markup .= (!empty($author)) ? '<span class="tg-quote-author">'.esc_html($author).'</span>' : null;
		
		return $markup;
	}
	
	/**
	* Link markup
	* @since: 1.0.0
	*/
	public function link_markup() {

		$link = $this->tg_item['link']['source']['content'];
		$url  = $this->tg_item['link']['source']['url'];
		
		$markup  = '<h2 class="tg-link-content tg-item-title"><a href="'.get_permalink().'">'.esc_html($link).'</a></h2>';
		$markup .= (!empty($url)) ? '<a class="tg-link-url" href="'.get_permalink().'">'.esc_url($url).'</a>' : null;
		
		return $markup;
	}
	
	/**
	* search for the right video data
	* @since: 1.0.0
	*/
	public function video_format() {
		
		$format = (!$this->video_lightbox) ? $this->tg_item['video']['type'] : null;

		// serach for the media content depend on of the format
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
	* Youtube markup
	* @since: 1.0.0
	*/
	public function youtube_markup() {
		$YT_ID   = $this->tg_item['video']['source']['ID'];
		$HTML_ID = 'YT-'.uniqid();
		$YT_URL  = 'https://www.youtube.com/embed/'.$YT_ID.'?version=3&amp;enablejsapi=1&amp;html5=1&amp;controls=1&amp;autohide=1&amp;rel=0&amp;showinfo=0';
		$ratio   = '16:9';
		$markup  = '<div class="tg-item-media-inner" data-ratio="'.esc_attr($ratio).'">';
		if ($this->iframe_poster) {
			$markup .= '<iframe class="tg-item-youtube tg-item-media" src="about:blank" data-src="'.esc_url($YT_URL).'" data-api="'.$this->iframe_poster.'" id="'.esc_attr($HTML_ID).'" allowfullscreen></iframe>';
		} else {
			$markup .= '<iframe class="tg-item-youtube tg-item-media" src="'.esc_url($YT_URL).'" id="'.esc_attr($HTML_ID).'" allowfullscreen></iframe>';
		}
		$markup .= '</div>';
		$poster  = $this->media_poster();
		$markup .= $poster['markup'];
		return $markup;
	}
	
	/**
	* Vimeo markup
	* @since: 1.0.0
	*/
	public function vimeo_markup() {
		$VM_ID   = $this->tg_item['video']['source']['ID'];
		$HTML_ID = 'VM-'.uniqid();
		$VM_URL  = 'https://player.vimeo.com/video/'.$VM_ID.'?title=0&amp;byline=0&amp;portrait=0&amp;api=1&amp;player_id='.$HTML_ID;
		$ratio   = '16:9';
		$markup  = '<div class="tg-item-media-inner" data-ratio="'.esc_attr($ratio).'">';
		if ($this->iframe_poster) {
			$markup .= '<iframe class="tg-item-vimeo tg-item-media" src="about:blank" data-src="'.esc_url($VM_URL).'" data-api="'.$this->iframe_poster.'" id="'.esc_attr($HTML_ID).'" allowfullscreen></iframe>';
		} else {
			$markup .= '<iframe class="tg-item-vimeo tg-item-media" src="'.esc_url($VM_URL).'" id="'.esc_attr($HTML_ID).'" allowfullscreen></iframe>';
		}
		$markup .= '</div>';
		$poster  = $this->media_poster();
		$markup .= $poster['markup'];
		return $markup;
	}
	
	/**
	* Wistia markup
	* @since: 1.0.7
	*/
	public function wistia_markup() {
		$WT_ID   = $this->tg_item['video']['source']['ID'];
		$HTML_ID = 'WT-'.uniqid();
		$WT_URL  = 'https://fast.wistia.net/embed/iframe/'.$WT_ID.'?version=3&enablejsapi=1&html5=1&controls=1&autohide=1&rel=0&showinfo=0';
		$ratio   = '16:9';
		$markup  = '<div class="tg-item-media-inner" data-ratio="'.esc_attr($ratio).'">';
		if ($this->iframe_poster) {
			$markup .= '<iframe class="tg-item-wistia wistia_embed tg-item-media" src="about:blank" data-src="'.esc_url($WT_URL).'" data-api="'.$this->iframe_poster.'" id="'.esc_attr($HTML_ID).'" allowfullscreen></iframe>';
		} else {
			$markup .= '<iframe name="wistia_embed" class="tg-item-wistia wistia_embed tg-item-media" src="'.esc_url($WT_URL).'" id="'.esc_attr($HTML_ID).'" allowfullscreen></iframe>';
		}
		$markup .= '</div>';
		$poster  = $this->media_poster();
		$markup .= $poster['markup'];
		return $markup;
	}
	
	/**
	* Video markup
	* @since: 1.0.0
	*/
	public function video_markup() {
		$ratio   = '16:9';
		$poster  = $this->media_poster();
		$poster_url = (isset($poster['url'])) ? $poster['url'] : null;
		$source  = $this->tg_item['video']['source'];
		$poster  = $this->tg_item['image']['url'];
		$markup  = '<div class="tg-item-media-inner" data-ratio="'.esc_attr($ratio).'">';
		$markup .= '<video class="tg-item-video-player tg-item-media" poster="'.esc_url($poster_url).'" controls style="width:100%;height:100%">';
		foreach($source as $type => $src ){
			if (in_array($type,array('mp4','webm','ogv'))) {
				$markup .= '<source src="'.esc_url($src).'" type="video/'.esc_attr($type).'">';  
			}
		} 
		$markup .= '</video>';
		$markup .= '</div>';
		$poster  = $this->media_poster();
		$markup .= $poster['markup'];
		return $markup;
	}
	
	/**
	* SoundCloud markup
	* @since: 1.0.0
	*/
	public function soundcloud_markup() {
		$SC_ID   = 'SC-'.uniqid();
		$SC_URL  = '//w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.$this->tg_item['audio']['source']['ID'].
		'&amp;auto_play=false&amp;hide_related=true&amp;visual=true&amp;show_artwork=true&amp;color=white_transparent';
		$markup  = '<div class="tg-item-media-inner" data-ratio="4:3">';
		if ($this->iframe_poster) {
			$markup .= '<iframe id="'.esc_attr($SC_ID).'" class="tg-item-soundcloud tg-item-media" data-api="'.$this->iframe_poster.'" src="about:blank" data-src="'.esc_url($SC_URL).'"></iframe>';
		} else {
			$markup .= '<iframe id="'.esc_attr($SC_ID).'" class="tg-item-soundcloud tg-item-media" src="'.esc_url($SC_URL).'"></iframe>';
		}
		$markup .= '</div>';
		$markup .= '<div class="tg-item-media-poster tg-item-media-soundcloud tg-item-button-play"></div>';
		$poster  = $this->media_poster();
		$markup .= $poster['markup'];
		return $markup;
	}
	
	/**
	* Audio markup
	* @since: 1.0.0
	*/
	public function audio_markup() {
		
		if ($this->tg_item['audio']['type'] == 'soundcloud') {
			return $this->soundcloud_markup();
		} else {
		
			$markup  = null;
			
			$poster  = $this->media_poster();
			
			$class = (!empty($poster['url'])) ? ' has-media-poster' : null;
			$class = (!$this->media_poster) ? $class.' no-index' : $class;
	
			$markup .= '<div class="tg-item-media-inner'.esc_attr($class).'">';
			$markup .= '<audio class="tg-item-audio-player tg-item-media" controls>';
			foreach($this->tg_item['audio']['source'] as $key => $value ){
				if (in_array($key,array('mp3','ogg'))) {
					$markup .= '<source src="'.esc_url($value).'" type="audio/'.esc_attr($key).'">';  
				}
			}
			$markup .= '</audio>';
			
			if ($this->grid_style == 'masonry') {
				$markup .= (!empty($poster['url'])) ? '<img class="tg-item-audio-poster" width="'.$poster['width'].'" height="'.$poster['height'].'" alt="'.$poster['alt'].'" src="'.$poster['url'].'">' : null;
			} else {
				$markup .= (!empty($poster['markup'])) ? str_replace('tg-item-media-poster','tg-item-audio-poster',$poster['markup']) : null;
			}
			$markup .= '</div>';
			
			return $markup;
		
		}
	}
	
	/**
	* Media Poster markup
	* @since: 1.0.0
	*/
	public function media_poster() {
		
		$type = $this->tg_item['format'];
		
		if ($this->media_poster || $this->video_lightbox) {
			
			if (!empty($this->tg_item['image']['url'])) {
				$media_poster['url']    = $this->tg_item['image']['url'];
				$media_poster['width']  = $this->tg_item['image']['width'];
				$media_poster['height'] = $this->tg_item['image']['height'];
				$media_poster['alt']    = $this->tg_item['image']['alt'];
				$media_poster['markup'] = '<div class="tg-item-media-poster" style="background-image: url('.esc_url($media_poster['url']).')"></div>';
				return $media_poster;
			}
	
		} 
	
	}
	
	/**
	* Get lightbox markup fo each type of media and different lighbox plugin
	* @since: 1.0.0
	* @modified 1.0.7
	*/
	public function get_lightbox($media) {
		
		$type       = $this->grid_data['lightbox_type'];
		$media_type = $this->tg_item['format'];
		$lightbox   = null;
		
		// if no poster then no lightbox except for image
		if ($this->media_poster || $this->video_lightbox || !in_array($media_type,array('youtube','vimeo','wistia','video','soundcloud','audio'))) {
			
			$media_type  = (!empty($media_type) && in_array($media_type,$this->allowed_format)) ? $media_type : 'standard';
			$media_type = ($media_type == 'video' && in_array($media_type,$this->allowed_format)) ? $this->tg_item['video']['type'] : $media_type;
			
			switch ($media_type) {
				case 'youtube':
					if ($this->video_lightbox) {
						$lightbox = $this->get_youtube_lightbox($type,$media);
					} else {
						$lightbox = '<div class="tg-media-button tg-item-button-play">'. $this->icons[$media_type] .'</div>';
					}
					break;
				case 'vimeo':
					if ($this->video_lightbox) {
						$lightbox = $this->get_vimeo_lightbox($type,$media);
					} else {
						$lightbox = '<div class="tg-media-button tg-item-button-play">'. $this->icons[$media_type] .'</div>';
					}
					break;
				case 'wistia':
					if ($this->video_lightbox) {
						$lightbox = $this->get_wistia_lightbox($type,$media);
					} else {
						$lightbox = '<div class="tg-media-button tg-item-button-play">'. $this->icons[$media_type] .'</div>';
					}
					break;
				case 'video':
					if ($this->video_lightbox) {
						$lightbox = $this->get_video_lightbox($type,$media);
					} else {
						$lightbox = '<div class="tg-media-button tg-item-button-play">'. $this->icons[$media_type] .'</div>';
					}
					break;
				case 'soundcloud':
					$lightbox = '<div class="tg-media-button tg-item-button-play">'. $this->icons[$media_type] .'</div>';
					break;
				case 'audio':
					$lightbox = '<div class="tg-media-button tg-item-button-play">'. $this->icons[$media_type] .'</div>';
					break;
				default:
					$lightbox = $this->get_image_lightbox($type,$media);
					break;
			}
			
		}
		return $lightbox;
	}
	
	/**
	* Get youtube lightbox markup
	* @since: 1.0.0
	* @modified 1.0.7
	*/
	public function get_youtube_lightbox($type,$media) {
		
		$lightbox   = null;
		
		$icons = $this->icons['youtube'];
		$youtube_ID = esc_attr($this->tg_item['video']['source']['ID']);
		
		switch ($type) {
			case 'prettyphoto':
				$lightbox = '<a class="tg-media-button" href="http://www.youtube.com/watch?v='.$youtube_ID.'" rel="prettyPhoto" title="">'.$icons.'</a>';
				break;
			case 'fancybox':
				$lightbox = '<a class="tg-media-button fancybox iframe" href="http://www.youtube.com/embed/'.$youtube_ID.'?autoplay=0&wmode=opaque">'.$icons.'</a>';
				break;
			case 'foobox':
				$lightbox = '<a class="tg-media-button foobox" href="http://www.youtube.com/embed/'.$youtube_ID.'?autoplay=0&wmode=opaque" rel="foobox">'.$icons.'</a>';
				break;
			case 'the_grid':
				$lightbox = '<div class="tg-media-button" data-tolb-src="'.$youtube_ID.'" data-tolb-type="youtube" data-tolb-alt="">'.$icons.'</div>';
				break;
		}
		
		return $lightbox;
	}
	
	/**
	* Get vimeo lightbox markup
	* @since: 1.0.0
	* @modified 1.0.7
	*/
	public function get_vimeo_lightbox($type,$media) {
		
		$lightbox = null;
		
		$icons = $this->icons['vimeo'];
		$vimeo_ID = esc_attr($this->tg_item['video']['source']['ID']);
		
		switch ($type) {
			case 'prettyphoto':
				$lightbox = '<a class="tg-media-button" href="http://vimeo.com/'.$vimeo_ID.'" rel="prettyPhoto">'.$icons.'</a>';
				break;
			case 'fancybox':
				$lightbox = '<a class="tg-media-button fancybox iframe" href="//player.vimeo.com/video/'.$vimeo_ID.'">'.$icons.'</a>';
				break;
			case 'foobox':
				$lightbox = '<a class="tg-media-button foobox" href="http://vimeo.com/'.$vimeo_ID.'" rel="foobox">'.$icons.'</a>';
				break;
			case 'the_grid':
				$lightbox = '<div class="tg-media-button" data-tolb-src="'.$vimeo_ID.'" data-tolb-type="vimeo" data-tolb-alt="">'.$icons.'</div>';
				break;
		}
		
		return $lightbox;
		
	}
	
	/**
	* Get wistia lightbox markup
	* @since: 1.0.7
	*/
	public function get_wistia_lightbox($type,$media) {
		
		$lightbox = null;
		
		$icons = $this->icons['wistia'];
		$wistia_ID = esc_attr($this->tg_item['video']['source']['ID']);

		switch ($type) {
			case 'prettyphoto':
				$lightbox = '<div class="tg-media-button" data-tolb-src="'.$wistia_ID.'" data-tolb-type="wistia" data-tolb-alt="">'.$icons.'</div>';
				break;
			case 'fancybox':
				$lightbox = '<a class="tg-media-button fancybox iframe" href="https://fast.wistia.net/embed/iframe/'.$wistia_ID.'">'.$icons.'</a>';
				break;
			case 'foobox':
				$lightbox = '<div class="tg-media-button" data-tolb-src="'.$wistia_ID.'" data-tolb-type="wistia" data-tolb-alt="">'.$icons.'</div>';
				break;
			case 'the_grid':
				$lightbox = '<div class="tg-media-button" data-tolb-src="'.$wistia_ID.'" data-tolb-type="wistia" data-tolb-alt="">'.$icons.'</div>';
				break;
		}
		
		return $lightbox;
		
	}
	
	/**
	* Get html5 video lightbox markup
	* @since: 1.0.0
	* @modified 1.1.0
	*/
	public function get_video_lightbox($type,$media) {
		
		$lightbox = null;
		
		$icons = $this->icons['video'];
		$mp4  = (isset($this->tg_item['video']['source']['mp4'])  && !empty($this->tg_item['video']['source']['mp4'])) ? esc_url($this->tg_item['video']['source']['mp4']) : null;
		$ogv  = (isset($this->tg_item['video']['source']['ogv'])  && !empty($this->tg_item['video']['source']['ogv'])) ? esc_url($this->tg_item['video']['source']['ogv']) : null;
		$webm = (isset($this->tg_item['video']['source']['webm']) && !empty($this->tg_item['video']['source']['webm'])) ? esc_url($this->tg_item['video']['source']['webm']) : null;

		switch ($type) {
			case 'prettyphoto':
				$HTML_ID   = 'V'.uniqid();
				$lightbox  = '<a class="tg-media-button" href="#'.$HTML_ID.'" rel="prettyPhoto">'.$icons.'</a>';
				$lightbox .= '<div style="display:none" id="'.$HTML_ID.'">';
				$lightbox .= '<video controls style="height:280px;width:500px">';
				$lightbox .= ($mp4)  ? '<source src="'.$mp4.'" type="video/mp4">'   : null;
				$lightbox .= ($webm) ? '<source src="'.$webm.'" type="video/webm">' : null;
				$lightbox .= ($ogv)  ? '<source src="'.$ogv.'" type="video/ogg">'   : null;
				$lightbox .= '</video>';
				$lightbox .= '</div>';
				break;
			case 'fancybox':
				$video = ($mp4) ? $mp4 : null;
				$video = ($ogv  && $mp4) ? $ogv : $video;
				$video = ($webm && $ogv) ? $webm : $video;
				$lightbox  = '<a class="tg-media-button fancybox iframe" href="'.$video.'">'.$icons.'</a>';		
				break;
			case 'foobox':
				$HTML_ID   = 'V'.uniqid();
				$lightbox  = '<a class="tg-media-button foobox" target="foobox" href="#'.$HTML_ID.'" data-height="360" data-width="640">'.$icons.'</a>';
				$lightbox .= '<div style="display:none" id="'.$HTML_ID.'">';
				$lightbox .= '<video controls>';
				$lightbox .= ($mp4)  ? '<source src="'.$mp4.'" type="video/mp4">'   : null;
				$lightbox .= ($webm) ? '<source src="'.$webm.'" type="video/webm">' : null;
				$lightbox .= ($ogv)  ? '<source src="'.$ogv.'" type="video/ogg">'   : null;
				$lightbox .= '</video>';
				$lightbox .= '</div>';
				break;
			case 'the_grid':
				$source = array();
				$mp4  = ($mp4)  ? array_push($source, '[{"type":"mp4","source":"'.$mp4.'"}]')   : null;
				$ogv  = ($ogv)  ? array_push($source, '[{"type":"ogg","source":"'.$ogv.'"}]')   : null;
				$webm = ($webm) ? array_push($source, '[{"type":"webm","source":"'.$webm.'"}]') : null;
				$source = ($source) ? implode(',', $source) : null;
				$poster = (isset($this->tg_item['image']['url']) && !empty($this->tg_item['image']['url'])) ? ' data-tolb-poster="'.esc_url($this->tg_item['image']['url']).'"' : null;
				$lightbox = '<div class="tg-media-button" data-tolb-src=\'['.$source.']\' data-tolb-type="'.$this->tg_item['format'].'" data-tolb-alt=""'.$poster.'>'.$icons.'</div>';
				break;
		}
		
		return $lightbox;
		
	}
	
	/**
	* Get image/gallery lightbox markup
	* @since: 1.0.0
	* @modified 1.0.7
	*/
	public function get_image_lightbox($type,$media) {

		global $tg_skins_preview;
		
		$lightbox = null;
		
		$icons   = $this->icons['image'];
		$format  = $this->tg_item['format'];
		$image   = $this->tg_item['image']['url'];
		$gallery = $this->tg_item['gallery'];
		$title   = '';
		$alt     = ucfirst('');
		
		$lightbox = null;
		$type  = ($tg_skins_preview) ? 'the_grid' : $type;
		$class = ($tg_skins_preview) ? ' tolb-disabled' : null;
		
		switch ($type) {
			case 'prettyphoto':
				$lightbox .= '<a class="tg-media-button" href="'.$image.'" rel="prettyPhoto" title="'.$alt.'">'.$icons.'</a>';
				break;
			case 'fancybox':
				if ($format == 'gallery' && count($gallery) > 1) {
					for ($i = 0; $i < count($gallery); $i++) {
						$image = $gallery[$i]['lb_url'];
						$lightbox .= '<a class="tg-hidden-tag fancybox" href="'.$image.'"></a>';
						if ($i == 0) {
							$lightbox .= '<a class="tg-media-button fancybox'.$class.'" href="'.$image.'">'.$icons.'</a>';
						} else {
							$lightbox .= '<a class="tg-hidden-tag fancybox" href="'.$image.'"></a>';
						}
					}
				} else {
					$lightbox .= '<a class="tg-media-button fancybox" href="'.$image.'">'.$icons.'</a>';
				}
				break;
			case 'foobox':
				if ($media['media_data']['type'] == 'gallery' && count($media['media_data']['images']) > 1) {
					for ($i = 0; $i < count($media['media_data']['images']); $i++) {
						$image = $media['media_data']['images'][$i]['lb_url'];
						$title = $media['media_data']['images'][$i]['title'];
						$alt   = $media['media_data']['images'][$i]['alt'];
						$alt   = (!empty($alt)) ? ucfirst($alt) : ucfirst($title);
						if ($i == 0) {
							$lightbox .= '<a class="tg-media-button foobox'.$class.'" href="'.$image.'" title="'.$alt.'" rel="">'.$icons.'</a>';
						} else {
							$lightbox .= '<a class="tg-hidden-tag foobox" href="'.$image.'" title="'.$alt.'" rel=""></a>';
						}
					}
				} else {
					$lightbox .= '<a class="tg-media-button foobox" href="'.$image.'" title="'.$alt.'" rel="">'.$icons.'</a>';
				}
				break;
			case 'the_grid':	
				if ($format == 'gallery' && count($gallery) > 1) {
					for ($i = 0; $i < count($gallery); $i++) {
						$image = $gallery[$i]['lb_url'];
						$title = $gallery[$i]['title'];
						$alt   = $gallery[$i]['alt'];
						$alt   = (!empty($alt)) ? ucfirst($alt) : ucfirst($title);
						if ($i == 0) {
							$lightbox .= '<div class="tg-media-button'.$class.'" data-tolb-src="'.esc_url($image).'" data-tolb-type="image" data-tolb-alt="'.esc_attr($alt).'">'.$icons.'</div>';
						} else {
							$lightbox .= '<div class="tg-hidden-tag" data-tolb-src="'.esc_url($image).'" data-tolb-type="image" data-tolb-alt="'.esc_attr($alt).'"></div>';
						}
					}
				} else {
					$lightbox .= '<div class="tg-media-button'.$class.'" data-tolb-src="'.$image.'" data-tolb-type="image" data-tolb-alt="'.$alt.'">'.$icons.'</div>';
				}
				break;
		}
		
		return $lightbox;
	}

}

if(!function_exists('The_Grid_Item_Content')) {
	/**
	* Tiny wrapper function
	* @since 1.0.0
	*/
	function The_Grid_Item_Content($options = '') {
		$to_Item_Content = The_Grid_Item_Content::getInstance();	
		return $to_Item_Content->process($options);
	}
	
}