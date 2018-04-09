<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 *
 * Themeone First Media in Content Class Plugin
 * @since: 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

if(!class_exists('TO_First_Media')) {

	class TO_First_Media {
	
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
		* No cloning allowed
		* @since 1.0.0
		*/
		private function __clone() {}
		
		/**
		* to initialize a TO_First_Media object
		* @since 1.0.0
		*/
		static public function getInstance() {
			if(self::$instance == null) {
				self::$instance = new self;
			}
			return self::$instance;
		}
		
		/**
		* Run the main function class
		* @since 1.0.0
		*/
		public function process($format) {
			
			$media = array();

			switch ($format) {
				case 'gallery':
					$media = $this->get_first_content_gallery();
					break;
				case 'quote':
					$media = $this->get_first_content_quote();
					break;
				case 'link':
					$media = $this->get_first_content_link();
					break;
				case 'audio':
					$media = $this->get_first_content_audio();
					break;
				case 'video':
					$media = $this->get_first_content_video();
					break;
				default:
					$media = $this->get_first_content_image();
					break;
			}
            
			return $media;
			
		}
		
		/**
		* retieve first image tag url in post text editor
		* @since: 1.0.0
		*/
		public function get_first_content_image() {
			
			global $post;
			
			ob_start();
			ob_end_clean();
			
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
			$first_img = (isset($matches[1][0]) && !empty($matches[1][0])) ? $this->get_image_id_by_url($matches[1][0]) : null;
			
			return $first_img;
			
		}

		
		/**
		* retrieve all content gallery images in post text
		* @since: 1.0.0
		*/
		public function get_first_content_gallery(){
			
			if (get_post_gallery()) {
				
            	$gallery_IDs = get_post_gallery(get_the_ID(), false);
				$gallery_IDs = (!empty($gallery_IDs['ids'])) ? explode(',', $gallery_IDs['ids']) : null;
				return $gallery_IDs;
				
			}
			
		}
		
		/**
		* retrieve first content blockquote
		* https://kovshenin.com/2011/post-formats-in-wordpress-breaking-down-those-quotes/
		* @since: 1.0.0
		*/
		public function get_first_content_quote() {
			
			$dom = new DOMDocument;
			$content = get_the_content('');
			
			if (!empty($content)) {
				
				libxml_use_internal_errors(true);
				$dom->loadHTML('<?xml encoding="UTF-8">' . apply_filters('the_content', $content));
				$blockquotes = $dom->getElementsByTagname('blockquote');
	
				if ($blockquotes->length > 0) {
				
					$cite_content = null;
					$blockquote_content = null;
					$blockquote_arr = array();
				
					// First blockquote
					$blockquote = $blockquotes->item(0);
	
					$cite = $blockquote->getElementsByTagName('cite')->item(0);
					$p = $blockquote->getElementsByTagName('p');
	
					if ($cite && $p) {
						// Remove the cite from the paragraph
						foreach ($p as $paragraph) {
							try {
								$paragraph->removeChild($cite);
							}
							catch(Exception $e) {}
						}
						$cite_content = $cite->nodeValue;
					}
	
					foreach ($p as $paragraph) {
						if (strlen(trim($paragraph->nodeValue)) > 0) {
							$blockquote_content .= $paragraph->nodeValue;
						} else {
							$paragraph->parentNode->removeChild($paragraph);
						}
					}
					
					$blockquote->parentNode->removeChild($blockquote);
					$remaining_content = $dom->saveXML();
	
					$blockquote_arr['type'] = 'quote';
					$blockquote_arr['source']['content'] = $blockquote_content;
					$blockquote_arr['source']['author']  = $cite_content;
					
					return $blockquote_arr;
					
				}
			
			}

		}
		
		
		/**
		* retrieve first link tag
		* @since: 1.0.0
		*/
		public function get_first_content_link() {
			
			global $post;
			$link = preg_match_all( '/href\s*=\s*[\"\']([^\"\']+)/', $post->post_content, $links );
			$link = (isset($links[1][0]) && !empty($links[1][0])) ? $links[1][0] : null;
			
			if (!empty($link)) {
					
				$link_arr['type'] = 'link';
				$link_arr['source']['content'] = '';
				$link_arr['source']['url'] = $link;
				return $link;
				
			}
			
		}
		
		/**
		* retrieve first media video in content (since Wordpress 4.2)
		* @since: 1.0.0
		*/
		public function get_first_content_video() {
			
			$video   = array();
			$post    = get_post(get_the_ID());
			$content = $post->post_content;
			$embeds  = (array) get_media_embedded_in_content(apply_filters('the_content', $content));

			foreach($embeds as $key => $value) {
				
				if ($this->strpos_array($value,array('youtube','vimeo','<video')) !== false) {
					$embeds = $value;
					break;
				}
				
			}
			
			if(isset($embeds) && !empty($embeds)) {
				
				switch (true) {
					case strpos((string) $embeds, 'youtube'): 
						$video = self::get_youtube($embeds);
						break;
					case strpos((string) $embeds, 'vimeo'):
						$video = self::get_vimeo($embeds);
						break;
					case strpos((string) $embeds, 'video'):
						$video = self::get_video($content);
						break;
				}
				
			}
			
			return $video;
		}
		
		
		/**
		* retieve first audio source in post text editor
		* @since: 1.0.0
		*/
		public function get_first_content_audio() {
			
			$audio   = array();
			$post    = get_post(get_the_ID());
			$content = $post->post_content;
			$embeds  = get_media_embedded_in_content(apply_filters('the_content', $content));

			foreach($embeds as $key => $value) {
				
				if ($this->strpos_array($value,array('<audio','soundcloud')) !== false) {
					$embeds = $value;
					break;
				}
				
			}
			
			if(isset($embeds) && !empty($embeds) && is_string($embeds)) {
				
				switch (true) {
					case strpos((string) $embeds, 'soundcloud'):
						$audio = self::get_soundclound($embeds);
						break;
					case strpos((string) $embeds, 'audio'): 
						$audio = self::get_audio($content);
						break;
				}
				
			}

			return $audio;
			
		}
		
		/**
		* get audio shortocde source
		* @since: 1.0.0
		*/
		public function get_audio($content) {
			
			preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches );
			$audios = array_keys($matches[2],'audio');
			
			if (is_array($matches) && !empty($audios)) {
				
				$attr = shortcode_parse_atts($matches[3][$audios[0]]);
				$audio['type'] = 'audio';
				
				foreach($attr as $key => $value ){
					if ($this->strpos_array($key,array('mp3','ogg')) !== false) {
						$audio['source'][$key] = $value;  
					}
				}
				
				return $audio;
				
			}
			
		}
		
		/**
		* get sonudclound url
		* @since: 1.0.0
		*/
		public function get_soundclound($content) {
			
			preg_match_all('#(https?://[a-z0-9\.\-_\#%&=/?;,!:~@\$\+]+)#iu', $content, $url, PREG_PATTERN_ORDER);
			
			if(isset($url[0][0]) && !empty($url[0][0])) {
				
				preg_match_all('/\/\/api.soundcloud.com\/tracks\/(.[0-9]*)/i', rawurldecode($url[0][0]), $matches);
				
				if(isset($matches[1][0])) {
					
					$audio['type'] = 'soundcloud';
					$audio['source']['url'] = $matches[0][0];
					$audio['source']['ID']  = $matches[1][0];
					return $audio;
					
				}
				
			}
		}
		
		/**
		* get Yoututbe url and ID
		* @since: 1.0.0
		*/
		public function get_youtube($content) {
			
			preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $content, $url, PREG_PATTERN_ORDER);
			preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url[0][0], $url);
			
			if(isset($url[0]) && !empty($url[0])) {
				
				$video['type'] = 'youtube';
				$video['source']['url'] = $url[0];
				$video['source']['ID']  = $url[1];
				return $video;
				
			}
			
		}
		
		/**
		* get Vimeo url and ID
		* @since: 1.0.0
		*/
		public function get_vimeo($content) {
			
			preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $content, $url, PREG_PATTERN_ORDER);
			preg_match('#(https?://)?(www.)?(player.)?vimeo.com/([a-z]*/)*([0-9]{6,11})[?]?.*#', $url[0][0], $url);
			
			if(isset($url[0]) && !empty($url[0]) && strlen($url[5])) {
				
				$video['type'] = 'vimeo';
				$video['source']['url'] = $url[0];
				$video['source']['ID']  = $url[5];
				return $video;
				
			}
			
		}
		
		/**
		* get Video urls
		* @since: 1.0.0
		*/
		public function get_video($content) {
			
			preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches );
			
			if (isset($matches[2]) && !empty($matches[2])) {
				
				$videos = array_keys($matches[2],'video');
				
				if (isset($videos) && !empty($videos)) {
					
					$attr = shortcode_parse_atts($matches[3][$videos[0]]);
					$video['type'] = 'video';
					
					foreach($attr as $key => $value ){
						if ($this->strpos_array($key,array('mp4','webm','ogv','poster')) !== false) {
							$video['source'][$key] = $value;
						}
					}
					
					return $video;
					
				}
				
			}
			
		}
		
		/**
		* Try to convert an attachment URL into a post ID.
		* @since 1.0.0
		* source: http://themeforest.net/forums/thread/get-attachment-id-by-image-url/36381
		*/
		public function get_image_id_by_url($image_url) {
	
			global $wpdb;
			$attachment_id = $image_url;
			
			// If there is no url, return.
			if ('' == $image_url) {
				return;
			}
			
			// Get the upload directory paths
			$upload_dir_paths = wp_upload_dir();
			
			// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
			if ( false !== strpos( $image_url, $upload_dir_paths['baseurl'] ) ) {
				
				// If this is the URL of an auto-generated thumbnail, get the URL of the original image
				$image_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $image_url );
				// Remove the upload path base directory from the attachment URL
				$image_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $image_url );
				// Finally, run a custom database query to get the attachment ID from the modified attachment URL
				$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $image_url ) );
	
			}
			
			return $attachment_id;
			
		}
		
		/**
		* simple function to search in array strpos
		* @since: 1.0.0
		*/
		public function strpos_array($haystack, $needles, $offset = 0) {
			
			if (is_array($needles)) {
				
				foreach ($needles as $needle) {
					
					$pos = self::strpos_array($haystack, $needle);
					if ($pos !== false) {
						return true;
					}
					
				}
				
				return false;
				
			} else {
				
				return strpos((string) $haystack, $needles, $offset);
				
			}
		}

	}

}

if(!function_exists('TO_First_Media')) {
	/**
	* Tiny wrapper function
	* @since 1.0.0
	*/
	function TO_First_Media($format = 'standard') {
		$to_first_media = TO_First_Media::getInstance($format);
		return $to_first_media->process($format);
	}
	
}