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

class The_Grid_RSS {
		
	/**
	* RSS feed transient
	*
	* @since 2.1.0
	* @access private
	*
	* @var string
	*/
	private $transient_sec;
	
	/**
	* Grid items
	*
	* @since 2.1.0
	* @access private
	*
	* @var array
	*/
	private $grid_items;
	
	/**
	* Grid data
	*
	* @since 2.1.0
	* @access private
	*
	* @var array
	*/
	private $grid_data;
	
	/**
	* Initialize the class and set its properties.
	* @since 2.1.0
	*/
	public function __construct($grid_data = '') {
		
		$this->grid_data = $grid_data;
		$this->get_transient_expiration();
		
	}
	
	/**
	* Return array of grid data
	* @since: 2.0.0
	*/
	public function get_grid_data(){

		return $this->grid_data;
		
	}

	/**
	* Get RSS feed transient expiration
	* @since: 2.0.0
	*/
	public function get_transient_expiration(){
		
		$this->transient_sec = apply_filters('tg_transient_rss', 3600);
		
	}
	
	/**
	* Return array of data
	* @since 2.1.0
	*/
	public function get_grid_items() {
		
		$this->get_response();

		return $this->grid_items;

	}
	
	/**
	* Get url response (set transient)
	* @since 2.1.0
	*/
	public function get_response() {
		
		global $tg_is_ajax;
		
		$url = $this->grid_data['rss_feed_url'] ? explode(',', preg_replace('/\s+/', '', $this->grid_data['rss_feed_url'])) : null;
		
		if (empty($url)) {
			$error_msg  = __( 'Please enter a rss feed url', 'tg-text-domain' );
			throw new Exception($error_msg);
		}
		
		$transient_name = 'tg_grid_' . md5(implode('', $url)).'-'.$this->grid_data['offset'].'-'.($this->grid_data['offset']+$this->grid_data['item_number']);
		
		if ($this->transient_sec > 0 && ($transient = get_transient($transient_name)) !== false) {
			$this->grid_items = $transient;
		} else {
			
			// Get RSS Feed(s)
			include_once( ABSPATH . WPINC . '/feed.php' );
			$rss = fetch_feed($url);
			
			if (!is_wp_error($rss)) {
				$maxitems = $rss->get_item_quantity($this->grid_data['item_number']); 
				$this->rss_items = $rss->get_items($this->grid_data['offset'], $maxitems);	
			} else {
				$rss_error  = isset($rss->errors['simplepie-error'][0][0]) ? $rss->errors['simplepie-error'][0][0] : null;
				$error_msg  = __( 'Sorry, an error occured while retrieving RSS content:', 'tg-text-domain' );
				$error_msg .= '<br>';
				$error_msg .= $rss_error ? $rss_error : __( 'Unknown error...', 'tg-text-domain' );
				throw new Exception($error_msg);
			}
			
			$this->build_media_array();
			set_transient($transient_name, $this->grid_items, $this->transient_sec);
		
		}

	}
	
	/**
	* Get excerpt
	* @since 2.1.0
	*/
	public function get_excerpt($rss_item) {
		
		$excerpt = $rss_item->get_description();	
		$excerpt = $excerpt ? strip_tags($excerpt, '<a><i><b><br><span><strong><italic>') : null;
		$excerpt = $excerpt ? preg_replace('%(^(<(br|/p|p></p)\s*+(/>|>))*+)|(((<(br|p></p)\s*+(/>|>))|:)*+$)%m', '', $excerpt) : null;
		$excerpt = $excerpt ? preg_replace('%<a[^>]*></a>%m', '', $excerpt) : null;
		$excerpt = $excerpt ? stripslashes(trim($excerpt)) : null;
		
		return $excerpt;
		
	}
	
	/**
	* Get author info
	* @since 2.1.0
	*/
	public function get_author($rss_item) {
		
		$author = $rss_item->get_author();
		
		return array(
			'ID'     => null,
			'name'   => isset($author) ? $author->get_name() : null,
			'url'    => isset($author) ? $author->get_link() : null,
			'avatar' => null
		);
	
	}
	
	/**
	* Get image data
	* @since 2.1.0
	*/
	public function get_image($rss_item) {
		
		$enclosure = $rss_item->get_enclosure();
		$thumbnail = (array) $enclosure->get_thumbnails();
		$thumbnail = end($thumbnail);

		if (in_array($enclosure->type, array('image', 'image/png', 'image/jpg', 'image/jpeg', 'image/gif'))){
			
			$thumbnail = $enclosure->link;
		
		} else if (empty($thumbnail)){
			
			$content = html_entity_decode($rss_item->get_content(), ENT_QUOTES, 'UTF-8');
			
    		if(preg_match('/<img[^>]+\>/i', $content, $matches) === 1){
    			if(preg_match('/src=[\'"]?([^\'">]+)[\'" >]/', $matches[0], $link) === 1){
    				$thumbnail = urldecode($link[1]);
    				if(stripos($thumbnail, 'pinimg.com/236x/') !== false){
    					$thumbnail = str_replace('pinimg.com/236x/', 'pinimg.com/564x/', $thumbnail);
    				}
    			}
    		}
			
		}
		
		return array(
			'alt'    => $enclosure->get_title(),
			'url'    => $thumbnail,
			'width'  => isset($enclosure->width) && !empty($enclosure->width) ? $enclosure->width : 500,
			'height' => isset($enclosure->height) && !empty($enclosure->height) ? $enclosure->height : 500
		);

	}
	
	/**
	* Get audio data
	* @since 2.1.0
	*/
	public function get_audio($rss_item) {
		
		$enclosure = $rss_item->get_enclosure();

		if (in_array($enclosure->type, array('audio/x-m4a', 'audio/m4a', 'audio/mp4', 'audio/mp3', 'audio/mpeg'))) {
			$audio_type   = 'audio';
			$audio_source = array('mp3' => $enclosure->link);			
		} else if ($enclosure->type == 'audio/ogg') {
			$audio_type   = 'audio';
			$audio_source = array('ogg' => $enclosure->link);			
		}
		
		if (isset($audio_source) && !empty($audio_source)) {
			return array(
				'type'     => $audio_type,
				'source'   => $audio_source
			);
		}
		
	}

	/**
	* Get video data
	* @since 2.1.0
	*/
    public function get_video($rss_item) {
			
		$enclosure = $rss_item->get_enclosure();

		if ($enclosure->type == 'video/mp4') {
			$video_type   = 'video';
			$video_source = array('mp4' => $enclosure->link);
		} else if ($enclosure->type == 'video/ogv') {
			$video_type   = 'video';
			$video_source = array('ogv' => $enclosure->link);	
		} else if ($enclosure->type == 'video/webm') {
			$video_type   = 'video';
			$video_source = array('webm' => $enclosure->link);	
		}  else {
			$video = $this->get_first_embed_video($rss_item);
			$video_type   = isset($video['type']) && !empty($video['type']) ? $video['type'] : null;
			$video_source = isset($video['source']['ID']) && !empty($video['source']['ID']) ? array('ID' => $video['source']['ID']): null;
		}
		
		if (isset($video_source) && !empty($video_source)) {
			return array(
				'type'     => $video_type,
				'duration' => $enclosure->duration,
				'source'   => $video_source
			);
		}
        
	}
	
	/**
	* Get embed videos (Youtube & Vimeo)
	* @since 2.1.0
	*/
	public function get_first_embed_video($rss_item) {
		
		$url     = $rss_item->get_permalink();
		$encoded = $rss_item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_10_MODULES_CONTENT, 'encoded');
		$encoded = isset($encoded[0]['data']) ? $encoded[0]['data'] : null;
		$content = $url.$encoded;
		
		$embeds  = get_media_embedded_in_content(apply_filters('the_content', $content));
		
		foreach($embeds as $key => $value) {				
			if (The_Grid_Base::strpos_array($value,array('youtube','vimeo','<video')) !== false) {
				$embeds = $value;
				break;
			}	
		}
			
		if(isset($embeds) && !empty($embeds)) {
				
			switch (true) {
				case strpos((string) $embeds, 'youtube'): 
					return $this->get_youtube($embeds);
					break;
				case strpos((string) $embeds, 'vimeo'):
					return $this->get_vimeo($embeds);
					break;
			}
				
		}

	}

	/**
	* get Youtube url and ID
	* @since: 2.1.0
	*/
	public function get_youtube($content) {
			
		preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $content, $url, PREG_PATTERN_ORDER);
		
		if (isset($url[0][0])) {
			preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url[0][0], $url);
		}
			
		if(isset($url[0]) && !empty($url[0]) && isset($url[1])) {
				
			$video['type'] = 'youtube';
			$video['source']['url'] = $url[0];
			$video['source']['ID']  = $url[1];
			return $video;
				
		}
			
	}
	
	/**
	* get Vimeo url and ID
	* @since: 2.1.0
	*/
	public function get_vimeo($content) {
			
		preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $content, $url, PREG_PATTERN_ORDER);
		
		if (isset($url[0][0])) {
			preg_match('#(https?://)?(www.)?(player.)?vimeo.com/([a-z]*/)*([0-9]{6,11})[?]?.*#', $url[0][0], $url);
		}
			
		if(isset($url[0]) && !empty($url[0]) && strlen($url[5])) {
				
			$video['type'] = 'vimeo';
			$video['source']['url'] = $url[0];
			$video['source']['ID']  = $url[5];
			return $video;
				
		}
			
	}
		 
	/**
	* Build data array for the grid
	* @since 2.1.0
	*/
	public function build_media_array() {
		
		$this->grid_items = array();

		if (isset($this->rss_items) && !empty($this->rss_items)) {
			
			$count = 1;
			
			foreach ($this->rss_items as $rss_item) {
				
				$image  = $this->get_image($rss_item);
				$video  = $this->get_video($rss_item);
				$audio  = $this->get_audio($rss_item);

				$post_format = 'standard';
				$post_format = ($video) ? 'video' : $post_format;
				$post_format = ($audio) ? 'audio' : $post_format;

				$this->grid_items[] = array(
					'ID'              => $this->grid_data['offset']+$count,
					'date'            => strtotime($rss_item->get_date()),
					'post_type'       => null,
					'format'          => $post_format,
					'url'             => $rss_item->get_permalink(),
					'url_target'      => '_blank',
					'title'           => $rss_item->get_title(),
					'excerpt'         => $this->get_excerpt($rss_item),
					'terms'           => null,
					'author'          => $this->get_author($rss_item),
					'likes_number'    => null,
					'likes_title'     => null,
					'comments_number' => null,
					'views_number'    => null,
					'image'           => $image,
					'gallery'         => null,
					'video'           => $video,
					'audio'           => $audio,
					'quote'           => null,
					'link'            => null,
					'meta_data'       => null
				);
				
				$count++;
	
			}

		}
		
	}
	
}