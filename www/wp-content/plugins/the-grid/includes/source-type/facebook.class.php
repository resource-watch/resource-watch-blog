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

class The_Grid_Facebook {
	
	/**
	* Facebook App ID
	*
	* @since 2.0.0
	* @access private
	*
	* @var integer
	*/
	private $app_ID;
	
	/**
	* Facebook App Secret
	*
	* @since 2.0.0
	* @access private
	*
	* @var integer
	*/
	private $app_secret;
	
	/**
	* Facebook user
	*
	* @since 2.0.0
	* @access private
	*
	* @var string
	*/
	private $facebook_user;
	
	/**
	* Facebook user ID
	*
	* @since 2.0.0
	* @access private
	*
	* @var array
	*/
	private $facebook_user_id = array();
	
	/**
	* Facebook page url/username
	*
	* @since 2.0.0
	* @access public
	*
	* @var string
	*/
	private $facebook_page;
	
	/**
	* Facebook album ID
	*
	* @since 2.0.0
	* @access private
	*
	* @var integer
	*/
	private $facebook_album_id;
	
	/**
	* Facebook source type
	*
	* @since 2.0.0
	* @access public
	*
	* @var string
	*/
	private $facebook_source;
		
	/**
	* Facebook transient
	*
	* @since 2.0.0
	* @access private
	*
	* @var string
	*/
	private $transient_sec;
	
	/**
	* Grid data
	*
	* @since 2.0.0
	* @access private
	*
	* @var array
	*/
	private $grid_data;
	
	/**
	* Initialize the class and set its properties.
	* @since 2.0.0
	*/
	public function __construct($grid_data = '') {
		
		$this->grid_data = $grid_data;
		
		$this->get_app_key();
		$this->get_settings();
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
	* Get Facebook APP Key
	* @since: 2.0.0
	*/
	public function get_app_key(){
		
		$this->app_ID = trim(get_option('the_grid_facebook_app_ID', ''));
		$this->app_secret = trim(get_option('the_grid_facebook_app_secret', ''));
		
		if (empty($this->app_ID) || empty($this->app_secret)) {
			
			$error_msg  = __( 'You didn\'t authorize The Grid to', 'tg-text-domain' );
			$error_msg .= ' <a style="text-decoration: underline" href="'.admin_url('admin.php?page=the_grid_global_settings').'">';
			$error_msg .= __( 'connect to Facebook.', 'tg-text-domain' );
			$error_msg .= '</a>';
			throw new Exception($error_msg);
			
		}
		
	}
	
	/**
	* Get Facebook settings
	* @since: 2.0.0
	*/
	public function get_settings(){

		$this->facebook_source   = $this->grid_data['facebook_source'];
		$this->facebook_page     = $this->grid_data['facebook_page'];
		$this->facebook_album_id = $this->grid_data['facebook_album_id'];
		$this->facebook_group_id = $this->grid_data['facebook_group_id'];

	}
	
	/**
	* Get Facebook transient expiration
	* @since: 2.0.0
	*/
	public function get_transient_expiration(){
		
		$this->transient_sec = apply_filters('tg_transient_facebook', 3600);
		
	}
	
	/**
	* Return array of data
	* @since 2.0.0
	*/
	public function get_grid_items() {
		
		switch ($this->facebook_source) {
			case 'page_timeline':
				$this->get_page_timeline();
				break;
			case 'group':
				$this->get_public_group();
				break;
			case 'album':
				$this->get_public_album();
				break;
			default:
				$error_msg  = __( 'No Facebook source was set in The Grid settings.', 'tg-text-domain' );
				throw new Exception($error_msg);
		}

		return $this->media;

	}

	/**
	* Get user from Facebook url
	* @since: 2.0.0
	*/
	public function get_facebook_user(){
		
		if (empty($this->facebook_page)) {
			
			$error_msg  = __( 'No Facebook page was found.', 'tg-text-domain' );
			$error_msg .= '<br>';
			$error_msg .= __( 'Please, make sure you have entered a Facebook page in the grid settings.', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}

		$this->facebook_user = str_replace(array('https', 'http', '://', 'www.', 'facebook', '.com', '/'), '', $this->facebook_page);
		$this->facebook_user = explode('?', $this->facebook_user);
		$this->facebook_user = isset($this->facebook_user[0]) ? trim($this->facebook_user[0]) : null;
		$this->facebook_user = isset($this->facebook_user) ? explode('-', $this->facebook_user) : null;
		$this->facebook_user = is_array($this->facebook_user) ? trim(end($this->facebook_user)) : null;

		if (empty($this->facebook_user)) {
			
			$error_msg  = __( 'No Facebook user was found with the current Facebook page url', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}
		
	}

	/**
	* Facebook get page timeline
	* @since 2.0.0
	*/
	public function get_page_timeline() {
		
		$this->get_facebook_user();
				
		if (empty($this->facebook_user)) {
			$error_msg  = __( 'No Facebook page was set in The Grid settings.', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}
		
		$this->get_response('https://graph.facebook.com/'.$this->facebook_user.'/posts?'.$this->request_query());

	}
	
	/**
	* Facebook get group from ID
	* @since 2.0.0
	*/
	public function get_public_group(){
		
		if (empty($this->facebook_group_id)) {

			$error_msg  = __( 'No Facebook group ID was set in The Grid settings.', 'tg-text-domain' );
			throw new Exception($error_msg);

		}

		$this->get_response('https://graph.facebook.com/'.$this->facebook_group_id.'/feed?'.$this->request_query());
	
	}
	
	/**
	* Facebook get album from ID
	* @since 2.0.0
	*/
	public function get_public_album(){
		
		if (empty($this->facebook_album_id)) {

			$error_msg  = __( 'No Facebook album ID was set in The Grid settings.', 'tg-text-domain' );
			throw new Exception($error_msg);

		}
		
		$this->get_response('https://graph.facebook.com/'.$this->facebook_album_id.'/photos?'.$this->request_query());
	
	}
	
	/**
	* Build Facebook query url
	* @since: 2.0.0
	*/
	public function request_query(){

		$access_token = $this->get_oauth();
		$oauth = $access_token ? 'access_token=' . $access_token : 'access_token=null';

		return $oauth . $this->request_fields() . '&offset=' . $this->grid_data['offset'] . '&limit=' . $this->grid_data['item_number'];
	
	}
	
	/**
	* Build Facebook fields
	* @since: 2.0.0
	*/
	public function request_fields(){
		
		$fields  = '&fields=';
		$fields .= 'likes.summary(true),comments.summary(true),shares,';
		$fields .= 'id,object_id,source,type,status_type,link,from,name,message,story,created_time,picture,full_picture,attachments{media,subattachments}&locale=de_DE';
		
		return $fields;
		
	}
	
	/**
	* Get url response (transient)
	* @since 2.0.0
	*/
	public function get_oauth() {

		$oauth = add_query_arg( array(
			'type'          => 'client_cred',
			'client_id'     => $this->app_ID,
			'client_secret' => $this->app_secret
		), 'https://graph.facebook.com/oauth/access_token' );

		$transient_name = 'tg_grid_' . md5( $oauth );

		if ( $this->transient_sec > 0 && ( $transient = get_transient( $transient_name ) ) !== false ) {
			return $transient;
		}

		$oauth = wp_remote_fopen( $oauth );
		$oauth = json_decode( $oauth );

		if ( isset( $oauth->error->message ) ) {

			$error_msg  = __( 'Sorry, an error occurs from your Facebook App:', 'tg-text-domain' );
			$error_msg .= ' ' . $oauth->error->message;
			throw new Exception( $error_msg );

		}

		if ( isset( $oauth->access_token ) ) {

			set_transient( $transient_name, $oauth->access_token, $this->transient_sec );
			return $oauth->access_token;

		}
	
	}
	
	
	/**
	* Get url response (transient)
	* @since 2.0.0
	*/
	public function get_response($url) {

		global $tg_is_ajax;
		
		$transient_name = 'tg_grid_' . md5($url);

		if ($this->transient_sec > 0 && ($transient = get_transient($transient_name)) !== false) {
			$this->media = json_decode($transient, true);
		} else {
			$response = wp_remote_fopen($url);
			$json = json_decode($response);
			if (isset($json->error->message)) {
				$error_msg  = __( 'Sorry, an error occurs from Facebook API:', 'tg-text-domain' );
				$error_msg .= ' '.$json->error->message;
				throw new Exception($error_msg);
			}
			if (isset($json->data) && !empty($json->data)){
				$this->media = $this->build_media_array($json);
				set_transient($transient_name, json_encode($this->media), $this->transient_sec);
			} else if (!$tg_is_ajax) {
				$error_msg  = __( 'No content was found from Facebook.', 'tg-text-domain' );
				throw new Exception($error_msg);
			}
		}
		
	}
	
	/**
	* Get title from Facebook data .json
	* @since 2.0.0
	*/
	public function get_title($data) {
		
		if (!isset($data->type) || $this->facebook_source == 'album' || (isset($data->status_type) && $data->status_type == 'added_photos')){
			return;
		} else if (isset($data->name)){
			return $data->name;
		}
		
		return;
		
	}
	
	/**
	* Get excerpt from Facebook data .json
	* @since 2.0.0
	*/
	public function get_excerpt($data) {
		
		if (!isset($data->type) || $this->facebook_source == 'album'){
		    return isset($data->name) ? (string) $data->name : null;
	    } else if (isset($data->message)) {
			$attributes = ' target="_blank" class="tg-item-social-link"'; 
			$message    = preg_replace('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', '<a href="$0"'.$attributes.'>$0</a>', $data->message);
			//$message    = preg_replace('/(https?:\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/i', '<a href="$1"'.$attributes.'>$1</a>', $data->message);
			$message    = preg_replace('/#([\\d\\w]+)/', '<a href="https://www.facebook.com/hashtag/$1?source=feed_text&story_id='.$data->id.'"'.$attributes.'>$0</a>', $message);
			return $message;
		} else if (isset($data->story)) {
			return (string) $data->story;
		}
		
		return;		

	}
	
	/**
	* Get user picture from Facebook data .json
	* @since 2.0.0
	*/
	public function get_user_picture($data) {
		
		if (isset($data->from->id) && !empty($data->from->id) && !array_key_exists($data->from->id, $this->facebook_user_id)){
			
			$response = wp_remote_fopen('https://graph.facebook.com/'.$data->from->id.'/picture?type=square&width=80&height=80&redirect=false');
			$json = json_decode($response);
			
			if (isset($json->data->url) && !empty($json->data->url)) {
				$this->facebook_user_id[$data->from->id] = $json->data->url;
				return $json->data->url;
			}
			
		} else if (array_key_exists($data->from->id, $this->facebook_user_id)) {
			
			return $this->facebook_user_id[$data->from->id];
			
		}
		
		return;
		
	}
	
	/**
	* Get image from Facebook data .json
	* @since 2.0.0
	*/
	public function get_image($data) {
		
		$attachments = isset($data->attachments->data[0]) ? $data->attachments->data[0] : null;

		if (isset($attachments->media->image->src)) {

			return array(
				'alt'    => null,
				'title'  => null,
				'url'    => isset($attachments->media->image->src) ? $attachments->media->image->src : null,
				'lb_url' => isset($data->full_picture) && $data->full_picture ? $data->full_picture : $attachments->media->image->src,
				'width'  => isset($attachments->media->image->width) ? $attachments->media->image->width : 500,
				'height' => isset($attachments->media->image->height) ? $attachments->media->image->height : 500
			);
						
					
		} else if (isset($data->source)) {

			return array(
				'alt'    => null,
				'title'  => null,
				'url'    => isset($data->source) ? $data->source : null,
				'lb_url' => isset($data->source) ? $data->source : null,
				'width'  => isset($data->width)  ? $data->width  : 500,
				'height' => isset($data->height) ? $data->height : 500,
			);
				
		}
		
		return;
	
	}
	
	/**
	* Get gallery from Facebook data .json
	* @since 2.0.0
	*/
	public function get_gallery($data) {
		
		$attachments = isset($data->attachments->data[0]) ? $data->attachments->data[0] : null;

		if ($attachments && isset($attachments->subattachments)) {

			$gallery = array();
			
			if (isset($attachments->media->image->src)) {
				
				$gallery[] = array(
					'alt'    => null,
					'title'  => null,
					'url'    => isset($attachments->media->image->src) ? $attachments->media->image->src : null,
					'lb_url' => isset($attachments->media->image->src) ? $attachments->media->image->src : null,
					'width'  => isset($attachments->media->image->width) ? $attachments->media->image->width : 500,
					'height' => isset($attachments->media->image->height) ? $attachments->media->image->height : 500
				);
				
			}
			
			foreach ($attachments->subattachments->data as $attachment) {
				
				$gallery[] = array(
					'alt'    => null,
					'title'  => null,
					'lb_url' => isset($attachment->media->image->src) ? $attachment->media->image->src : null,
					'url'    => isset($attachment->media->image->src) ? $attachment->media->image->src : null,
					'width'  => isset($attachment->media->image->width) ? $attachment->media->image->width : 500,
					'height' => isset($attachment->media->image->height) ? $attachment->media->image->height : 500
				);
				
			}
			
			return $gallery;
			
		}
		
		return;
		
	}
	
	/**
	* Get video from Facebook data .json
	* @since 2.0.0
	*/
	public function get_video($data) {

		if ( isset( $data->type ) && $data->type == 'video' && isset( $data->source ) && ! empty( $data->source ) ) {

			return array(
				'type'     => 'video',
				'duration' => null,
				'source'   => array(
					'mp4' => $data->source,
				)
			);

		}
			
	}
	 
	/**
	* Build data array for the grid
	* @since 2.0.0
	*/
	public function build_media_array($response) {
		
		$items = array();

		if (isset($response->data)) {

			foreach ($response->data as $data) {
				
				$video   = $this->get_video($data);
				$gallery = $this->get_gallery($data);
				
				$post_format = ($gallery) ? 'gallery' : 'standard';
				$post_format = ($video) ? 'video' : $post_format;

				$items[] = array(
					'ID'              => strstr($data->id, '_') ? str_replace('_', '', strstr($data->id, '_')) : $data->id,
					'date'            => (isset($data->created_time)) ? strtotime($data->created_time) : null,
					'post_type'       => null,
					'format'          => $post_format,
					'url'             => (isset($data->link)) ? $data->link : null,
					'url_target'      => '_blank',
					'title'           => $this->get_title($data),
					'excerpt'         => $this->get_excerpt($data),
					'terms'           => null,
					'author'          => array(
						'ID'     => (isset($data->from->id))   ? $data->from->id : null,
						'name'   => (isset($data->from->name)) ? $data->from->name : null,
						'url'    => (isset($data->from->id))   ? '//www.facebook.com/'.$data->from->id : null,
						'avatar' => $this->get_user_picture($data)
					),
					'likes_number'    => (isset($data->likes->summary->total_count)) ? $data->likes->summary->total_count : null,
					'likes_title'     =>  __( 'Like on FaceBook', 'tg-text-domain' ),
					'comments_number' => (isset($data->comments->data)) ? sizeof($data->comments->data) : null,
					'views_number'    => null,
					'image'           => $this->get_image($data),
					'gallery'         => $gallery,
					'video'           => $video,
					'audio'           => null,
					'quote'           => null,
					'link'            => null,
					'meta_data'       => null
				);
	
			}

		}

		return $items;
		
	}
	
}