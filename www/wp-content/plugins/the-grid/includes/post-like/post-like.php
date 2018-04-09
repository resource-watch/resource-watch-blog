<?php
/**
 * @package   Post_Like
 * @author    ThemeOne <themeone.master@gmail.com>
 * @copyright 2015 ThemeOne
 *
 * @wordpress-plugin
 * Plugin Name:       Post Like
 * Plugin URI:        http://www.theme-one.com/
 * Description:       Like/Unlike any post type
 * Version:           1.0.0
 * Author:            ThemeOne
 * Author URI:        http://www.theme-one.com/
 * Text Domain:       post-like
 * Domain Path:       /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

if(!class_exists('TO_Post_Like')) {

	class TO_Post_Like {
		
		/**
		* Main var for this instance
		* @since 1.0.0
		*/
		private $like_icon    = false;
		private $unlike_icon  = false;
		protected $debug_mode = false;
		
		/**
		* Initialization allowed
		* @since 1.0.0
		*/
		public function __construct() {
			
			$this->define_constants();
			$this->localize_plugin();
			// get debug mode option
			$this->debug_mode = get_option('the_grid_debug', false);
			// add shortcode for post like
			add_shortcode('to_post_like', array($this, 'to_post_like_shortcode'));
			// register actions for ajax
			add_action( 'wp_ajax_nopriv_to_like_post', array($this, 'like_post_callback') );
			add_action( 'wp_ajax_to_like_post', array($this, 'like_post_callback') );
			// Load public/admin CSS styles
			add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'), 101);
			add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'), 101);
			// Load front-end JS script
			add_action('wp_enqueue_scripts', array($this, 'enqueue_script'), 101);
			// register post like script (prevent additionnal request just fro a small script)
			add_action('wp_footer', array($this, 'enqueue_post_like_script'), 100);
			
		}
		
		/**
		* Localize_plugin
		* @since 1.0.0
		*/
		public function localize_plugin() {
			
			load_plugin_textdomain(
				'tg-text-domain',
				FALSE,
				TO_POST_LIKE_PATH . '/langs'
			);
			
		}
		
		/**
		* Define TO post like Constants
		* @since 1.0.0
		*/
		public function define_constants() {
			
			define('TO_POST_LIKE_PATH', trailingslashit(str_replace('\\', '/', dirname(__FILE__))));
			define('TO_POST_LIKE_URL', site_url(str_replace(trailingslashit(str_replace('\\', '/',ABSPATH)), '', TO_POST_LIKE_PATH)));
			
		}
		
		
		/**
		* Register Post Like Shortcode
		* @since 1.0.0
		*/
		public function to_post_like_shortcode($atts, $content = null){
			
			extract(shortcode_atts(array(), $atts));
			return TO_Get_Post_Like();
			
		}
		
		
		/**
		* Enqueue styles on front-end for debug mode enable
		* @since 1.0.0
		*/
		public function enqueue_script() {
				
			// enqueue js scripts
			if ($this->debug_mode) {
				wp_enqueue_script( 'to-like-post', TO_POST_LIKE_URL .'assets/js/post-like.js', array('jquery'), '1.0', 1 );
				wp_localize_script( 'to-like-post', 'to_like_post', array('url' => admin_url( 'admin-ajax.php' ),'nonce' => wp_create_nonce( 'to_like_post' )));
			}
			
		}
		
		/**
		* Enqueue post like script on front-end for debug mode disable
		* @since 1.0.5
		*/
		public function enqueue_post_like_script() {
				
			if (!$this->debug_mode) {
				$script = '<script type="text/javascript">';
				$script .= 'var to_like_post = {"url":"'.admin_url( 'admin-ajax.php' ).'","nonce":"'.wp_create_nonce( 'to_like_post' ).'"};';
				$script .= '!function(t){"use strict";t(document).ready(function(){t(document).on("click",".to-post-like:not(\'.to-post-like-unactive\')",function(e){e.preventDefault();var o=t(this),n=o.data("post-id"),s=parseInt(o.find(".to-like-count").text());return o.addClass("heart-pulse"),t.ajax({type:"post",url:to_like_post.url,data:{nonce:to_like_post.nonce,action:"to_like_post",post_id:n,like_nb:s},context:o,success:function(e){e&&((o=t(this)).attr("title",e.title),o.find(".to-like-count").text(e.count),o.removeClass(e.remove_class+" heart-pulse").addClass(e.add_class))}}),!1})})}(jQuery);';
				$script .= '</script>';
				echo $script;
			}
			
		}
		
		/**
		* Enqueue styles on front-end & back-end
		* @since 1.0.0
		*/
		public function enqueue_styles() {	
		
			// enqueue inline styles (because really small css) no need additionnal request blocking the page
			$custom_css = '.to-heart-icon,.to-heart-icon svg,.to-post-like,.to-post-like .to-like-count{position:relative;display:inline-block}.to-post-like{width:auto;cursor:pointer;font-weight:400}.to-heart-icon{float:left;margin:0 4px 0 0}.to-heart-icon svg{overflow:visible;width:15px;height:14px}.to-heart-icon g{-webkit-transform:scale(1);transform:scale(1)}.to-heart-icon path{-webkit-transform:scale(1);transform:scale(1);transition:fill .4s ease,stroke .4s ease}.no-liked .to-heart-icon path{fill:#999;stroke:#999}.empty-heart .to-heart-icon path{fill:transparent!important;stroke:#999}.liked .to-heart-icon path,.to-heart-icon svg:hover path{fill:#ff6863!important;stroke:#ff6863!important}@keyframes heartBeat{0%{transform:scale(1)}20%{transform:scale(.8)}30%{transform:scale(.95)}45%{transform:scale(.75)}50%{transform:scale(.85)}100%{transform:scale(.9)}}@-webkit-keyframes heartBeat{0%,100%,50%{-webkit-transform:scale(1)}20%{-webkit-transform:scale(.8)}30%{-webkit-transform:scale(.95)}45%{-webkit-transform:scale(.75)}}.heart-pulse g{-webkit-animation-name:heartBeat;animation-name:heartBeat;-webkit-animation-duration:1s;animation-duration:1s;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;-webkit-transform-origin:50% 50%;transform-origin:50% 50%}.to-post-like a{color:inherit!important;fill:inherit!important;stroke:inherit!important}';

			wp_add_inline_style('the-grid', $custom_css);
			
		}
		
		/**
		* Like the post Ajax
		* @since 1.0.0
		*/
		public function like_post_callback() {
	
			$nonce  = $_POST['nonce'];
			$action = $_POST['action'];
			
			if (!wp_verify_nonce($nonce, $action) && is_user_logged_in()) {
				die();
			}
			
			if (isset($_POST['post_id'])) {
			
				$post_id = $_POST['post_id'];
				$like_nb = $_POST['like_nb'];
				$count_like = get_post_meta($post_id, '_post_like_count', true);
				
				if (is_user_logged_in()) {
					
					global $current_user;
					
					$user_id     = $current_user->ID;
					$meta_POSTS  = get_user_meta( $user_id, '_liked_posts');
					$meta_USERS  = get_post_meta( $post_id, '_user_liked');
					$liked_POSTS = null;
					$liked_USERS = null;
					
					if (count($meta_POSTS) != 0) {
						$liked_POSTS = $meta_POSTS[0];
					}
					if (!is_array($liked_POSTS)) {
						$liked_POSTS = array();
					}	
					if (count($meta_USERS) != 0) {
						$liked_USERS = $meta_USERS[0];
					}		
					if (!is_array( $liked_USERS)) {
						$liked_USERS = array();
					}
						
					$liked_POSTS['post-'.$post_id] = $post_id;
					$liked_USERS['user-'.$user_id] = $user_id;
					$user_likes = count( $liked_POSTS );
					
					if (!$this->post_already_like($post_id)) {
						++$count_like;
					} else {
						$pid_key = array_search( $post_id, $liked_POSTS );
						$uid_key = array_search( $user_id, $liked_USERS );
						unset($liked_POSTS[$pid_key]);
						unset($liked_USERS[$uid_key]);
						$user_likes = count($liked_POSTS);
						--$count_like;
					}
					
					update_post_meta($post_id, '_user_liked', $liked_USERS);
					update_post_meta($post_id, '_post_like_count', $count_like);
					
					if (is_multisite()) {
						update_user_option($user_id, '_liked_posts', $liked_POSTS);		
						update_user_option($user_id, '_user_like_count', $user_likes) ;
					} else {
						update_user_meta($user_id, '_liked_posts', $liked_POSTS);
						update_user_meta($user_id, '_user_like_count', $user_likes);
					}
					
				} else {
					
					$ip = $_SERVER['REMOTE_ADDR'];
					$meta_IPS = get_post_meta( $post_id, '_user_IP');
					$liked_IPS = null;
					if (count( $meta_IPS ) != 0) {
						$liked_IPS = $meta_IPS[0];
					}
					if (!is_array($liked_IPS)) {
						$liked_IPS = array();
					}
					if (!in_array($ip, $liked_IPS)) {
						$liked_IPS['ip-'.$ip] = $ip;
					}
					if (!$this->post_already_like($post_id)) {
						++$count_like;
					} else {
						$ip_key = array_search($ip, $liked_IPS);
						unset($liked_IPS[$ip_key]);
						--$count_like;
					}
					update_post_meta($post_id, '_user_IP', $liked_IPS);
					update_post_meta($post_id, '_post_like_count', $count_like);
					
				}

				if ($count_like > $like_nb) {
					$title  = __('Unlike', 'to-text-domain');
					$class  = 'liked';
					$remove = 'no-liked empty-heart';
				} else {
					$title  = __('Like', 'to-text-domain');
					$class  = ($count_like == 0) ? 'no-liked empty-heart' : 'no-liked';
					$remove = ($count_like == 0) ? 'liked' : 'liked empty-heart'; 
				}
				
				$data = array(
					'title'	=> esc_attr($title),
					'count' => esc_attr($count_like),
					'add_class' => esc_attr($class),
					'remove_class' => esc_attr($remove)
					
				);

				wp_send_json($data);				
			}
			
			die();
		}
		
		/**
		* Check post like
		* @since 1.0.0
		*/
		public function post_already_like($post_id) {
	
			if (is_user_logged_in()) {
				
				$user_id     = get_current_user_id();
				$meta_USERS  = get_post_meta( $post_id, '_user_liked');
				$liked_USERS = '';
				
				if (count($meta_USERS) != 0) {
					$liked_USERS = $meta_USERS[0];
				}
				if(!is_array($liked_USERS)) {
					$liked_USERS = array();
				}
				if (in_array(get_current_user_id(), $liked_USERS)) {
					return true;
				}
				
				return false;
				
			} else {
			
				$meta_IPS = get_post_meta($post_id, '_user_IP');
				$ip = $_SERVER['REMOTE_ADDR'];
				$liked_IPS = '';
				
				if (count($meta_IPS) != 0) {
					$liked_IPS = $meta_IPS[0];
				}
				if (!is_array($liked_IPS)) {
					$liked_IPS = array();
				}
				if (in_array($ip, $liked_IPS)) {
					return true;
				}
				
				return false;
			}
			
		}

	}
	
	new TO_Post_Like();

}




class TO_Get_Post_Like extends TO_Post_Like {
	
	/**
	* The singleton instance
	* @since 1.0.0
	*/
	static private $instance = null;
		
	/**
	* No cloning allowed
	* @since 1.0.0
	*/
	private function __clone() {}
		
	/**
	* getInstance for tiny wrapper
	* @since 1.0.0
	*/
	static public function getInstance() {
		
		if(self::$instance == null) {
			self::$instance = new self;
		}
		return self::$instance;
		
	}
	
	/**
	* to initialize a TO_Get_Post_Like object
	* @since 1.0.0
	*/
    public function __construct($post_id = null) {
		
        $post_id    = (!empty($post_id)) ? $post_id : get_the_ID();
		$like_count = get_post_meta($post_id, '_post_like_count', true);
		$like_count = (empty( $like_count) || $like_count == '0' ) ? '0' : $like_count;
			
		if (parent::post_already_like($post_id)) {
			$class = ' liked';
			$title = __('Unlike', 'to-text-domain');		
		} else if ($like_count == 0) {
			$class = ' no-liked empty-heart';
			$title = __('Like', 'to-text-domain');
		} else {
			$class = ' no-liked';
			$title = __('Like', 'to-text-domain');			
		}

		$heart = '<span class="to-heart-icon">';
			$heart .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 64 64">';
				$heart .= '<g transform="translate(0, 0)">';
					$heart .= '<path stroke-width="6" stroke-linecap="square" stroke-miterlimit="10" d="M1,21c0,20,31,38,31,38s31-18,31-38 c0-8.285-6-16-15-16c-8.285,0-16,5.715-16,14c0-8.285-7.715-14-16-14C7,5,1,12.715,1,21z"></path>';
				$heart .= '</g>';
			$heart .= '</svg>';
		$heart .= '</span>';
		
		$output = '<span class="no-ajaxy to-post-like'.esc_attr($class).'" data-post-id="'.esc_attr($post_id).'" title="'.esc_attr($title).'">';
			$output .= $heart;
			$output .= '<span class="to-like-count">';
				$output .= esc_attr($like_count);
			$output .= '</span>';
		$output .= '</span>';
			
		return $output;
    }
	
}

if(!function_exists('TO_Get_Post_Like')) {
	/**
	* Tiny wrapper function
	* @since 1.0.0
	*/
	function TO_Get_Post_Like($post_id = null) {
		$to_first_media = TO_Get_Post_Like::getInstance();
		return $to_first_media->__construct($post_id);
	}
	
}