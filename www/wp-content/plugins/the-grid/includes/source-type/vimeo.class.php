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

class The_Grid_Vimeo {
	
	/**
	* Vimeo API Key
	*
	* @since 1.0.0
	* @access private
	*
	* @var integer
	*/
	private $api_key;
	
	/**
	* Vimeo transient
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $transient_sec;
	
	/**
	* Vimeo error
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $error;
	
	/**
	* Grid data
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $grid_data;

	/**
	* Vimeo count
	*
	* @since 1.0.0
	* @access private
	*
	* @var integer
	*/
	private $count;
	
	/**
	* Vimeo media data
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $media = array();
	
	/**
	* Vimeo last media data
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $last_media = array();
	
	/**
	* Vimeo offset
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $offset = null;
	
	/**
	* Vimeo item nb loaded
	*
	* @since 1.0.0
	* @access private
	*
	* @var integer
	*/
	private $loaded;
	
	/**
	* Vimeo item nb to load
	*
	* @since 1.0.0
	* @access private
	*
	* @var integer
	*/
	private $to_load;
	
	/**
	* Vimeo paramters
	*
	* @since 1.0.0
	* @access private
	*
	* @var string/array
	*/
	private $sort;
	private $order;
	private $source_type;
	private $source_id;

	/**
	* Initialize the class and set its properties.
	* @since 1.0.0
	*/
	public function __construct($grid_data = '') {

		$this->get_transient_expiration();
		$this->get_oauth();
		$this->get_API_key();
		$this->grid_data = $grid_data;
		
	}

	/**
	* Get Vimeo transient expiration
	* @since: 1.0.0
	*/
	public function get_transient_expiration(){
		
		$this->transient_sec = apply_filters('tg_transient_vimeo', 3600);
		
	}

	/**
	* Get url response (transient)
	* @since 2.0.0
	*/
	public function get_oauth() {

		$client_id      = trim( get_option( 'the_grid_vimeo_client_id', '' ) );
		$client_secrets = trim( get_option( 'the_grid_vimeo_client_secrets', '' ) );

		if ( empty( $client_id ) || empty( $client_secrets ) ) {
			return;
		}

		$oauth = 'https://api.vimeo.com/oauth/authorize/client?grant_type=client_credentials&scope=public&private';
		$args  = array(
			'headers'  => array(
				'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secrets ),
				'Content-Type'  => 'application/json'
			),
			'timeout' => 30
		);

		$transient_name = 'tg_grid_' . md5( $client_id . $client_secrets );

		if ( ( $transient = get_transient( $transient_name) ) !== false ) {
			$this->api_key = $transient;
		} else {

			$response = wp_remote_post( $oauth, $args );
		
			if ( is_wp_error( $response ) ) {

				$error_msg  = __( 'Sorry, an error occurs from your Vimeo API:', 'tg-text-domain' );
				$error_msg .= ' ' . $response->get_error_message();
				throw new Exception( $error_msg );

			} else {

				$body = json_decode( $response['body'] );

				if ( isset( $body->access_token ) ) {

					$this->api_key = $body->access_token;
					set_transient( $transient_name, $this->api_key, 0 );

				} else {

					$error_msg  = __( 'Sorry, your Vimeo Client ID and Secrets are not valid.', 'tg-text-domain' );
					throw new Exception( $error_msg );

				}

			}

		}

	}

	/**
	* Get Vimeo API Key
	* @since: 1.0.0
	*/
	public function get_API_key(){
		
		if ( $this->api_key ) {
			return;
		}
		
		$this->api_key = trim(get_option('the_grid_vimeo_api_key', ''));
		
		if ( empty( $this->api_key ) ) {
			$error_msg  = __( 'You didn\'t authorize The Grid to', 'tg-text-domain' );
			$error_msg .= ' <a style="text-decoration: underline;" href="'.admin_url('admin.php?page=the_grid_global_settings').'">';
			$error_msg .= __( 'connect to Vimeo.', 'tg-text-domain' );
			$error_msg .= '</a>';
			throw new Exception($error_msg);
		}
		
	}

	/**
	* Return array of data
	* @since 1.0.0
	*/
	public function get_grid_items() {
		
		$this->get_data(
			$this->grid_data['vimeo_sort'],
			$this->grid_data['vimeo_order'],
			$this->grid_data['vimeo_source'],
			$this->grid_data['vimeo_user'],
			$this->grid_data['vimeo_album'],
			$this->grid_data['vimeo_group'],
			$this->grid_data['vimeo_channel'],
			$this->grid_data['item_number']
		);
		
		$this->grid_data['ajax_data'] = htmlspecialchars(json_encode($this->last_media), ENT_QUOTES, 'UTF-8');
		
		return $this->media;

	}
	
	/**
	* Return array of grid data
	* @since: 1.0.0
	*/
	public function get_grid_data(){

		return $this->grid_data;
		
	}
	
	/**
	* Get instagram data
	* @since 1.0.0
	*/
	public function get_data($sort, $order, $source, $user, $album, $group, $channel, $count){
		
		// store Vimeo data
		$this->sort  = $sort;
		$this->order = $order;
		$this->source_type = $source;
		
		// get right source content from Vimeo
		switch ($this->source_type) {
			case 'users':
				$this->source_id = $user;
				break;
			case 'albums':
				$this->source_id = $album;		
				break;
			case 'groups':
				$this->source_id = $group;
				break;
			case 'channels':
				$this->source_id = $channel;		
				break;
		}
		
		// set the number of video to retrieve
		$this->count = ($count <= 0) ? 10 : $count;
		$this->count = ($this->count > 50) ? 50 : $this->count;

		// get last media from ajax
		$this->last_media['page']   = (isset($_POST['grid_ajax']) && !empty($_POST['grid_ajax'])) ? (int) $_POST['grid_ajax']['page']   : 1;
		$this->last_media['count']  = (isset($_POST['grid_ajax']) && !empty($_POST['grid_ajax'])) ? (int) $_POST['grid_ajax']['count']  : 0;
		$this->last_media['onload'] = (isset($_POST['grid_ajax']) && !empty($_POST['grid_ajax'])) ? (int) $_POST['grid_ajax']['onload'] : $this->count;
		$this->last_media['total']  = (isset($_POST['grid_ajax']) && !empty($_POST['grid_ajax'])) ? (int) $_POST['grid_ajax']['total']  : 9999;
		
		// if no more item to load
		if ($this->last_media['count'] > 0 && $this->last_media['count'] >= $this->last_media['total']) {
			return '';
		}
		
		// adjust the number of media to load
		if ($this->last_media['count'] > 0) {
			
			$max_media = $this->last_media['total'] - $this->last_media['count'];
			if ($max_media < $this->count) {
				$this->count = $max_media;
			}
			
		}

		// retrieve Instagram data
		$media = $this->get_media();
		
		// build response array
		return $this->media;
		
	}
	
	/**
	* Get instagram user data
	* @since 1.0.0
	*/
	public function get_user($user){
		
		$url = 'https://api.vimeo.com/users/'.$user.'?access_token='.$this->api_key;
		$response = $this->get_response($url);

		if (isset($response) && !empty($response)){
			return $response;
		} else {
			return '';
		}
		
	}
	
	/**
	* Retrieve media data
	* @since 1.0.0
	*/
	public function get_media() {
		
		// retrieve current Vimeo page data
		$this->get_page();
		// set page offset if ajax nb !=  onload nb
		$this->offset = $this->to_load - $this->loaded + $this->last_media['onload'] - $this->count;
		
		// if the number of result is not enough then loop until enough
		// auto offset because Vimeo doesn't have offset for video endpoint
		while ($this->to_load > $this->loaded && count($this->media) <= $this->last_media['total']) {
			$this->get_page();
		}
		
		// get only necessary element from vimeo data array
		$this->media = array_slice($this->media, $this->offset, $this->count);
		// store last number of video we append
		$this->last_media['count'] = $this->last_media['count'] + count($this->media);
		// get error message if error occurs
		$this->error = (isset($call->error)) ? $call->error : '';
				
	}
	
	/**
	* Retrieve Vimeo page data
	* @since 1.0.0
	*/
	public function get_page() {
		
		// make Vimeo API call
		$call  = $this->_makeCall($this->source_type, $this->source_id, $this->last_media['page']);
		// transform Vimeo data to our data array
		$media = $this->build_media_array($call, '', '');
		// merge current result to previous Vimeo page(s) result
		$this->media = array_merge($this->media, $media);
		
		// check if we need to retrieve next page
		$this->loaded  = $this->last_media['page'] * $this->last_media['onload'];
		$this->to_load = $this->last_media['count'] + $this->count;
		$this->last_media['page']  = ($this->to_load >= $this->loaded && isset($call->page)) ? $call->page+1 : $this->last_media['page'];
		$this->last_media['total'] = (isset($call->total)) ? $call->total : -1;
		
	}
	
	/**
	* Vimeo API call
	* @since 1.0.0
	*/
	public function _makeCall($type, $id, $page = null) {

		// set and retrieve response
		$page  = (!empty($page)) ? '&page='.$page : '';
		$sort  = (!empty($this->sort)) ? '&sort='.$this->sort : '';
		$order = (!empty($this->order)) ? '&direction='.$this->order : '';
		$url  = 'https://api.vimeo.com/'.$type.'/'.$id.'/videos?access_token='.$this->api_key.'&per_page='.$this->last_media['onload'].$page.$sort.$order;
		$response = $this->get_response($url);

		if (isset($response) && !empty($response)){
			return $response;
		}
		

	}
	
	/**
	* Get url response (transient)
	* @since 1.0.0
	*/
	public function get_response($url) {
		
		global $tg_is_ajax;
		
		$transient_name = 'tg_grid_' . md5($url);
		
		if ($this->transient_sec > 0 && ($transient = get_transient($transient_name)) !== false) {
			$response = $transient;
		} else {
			$response = json_decode(wp_remote_fopen($url));
			if (isset($response->error) && !empty($response->error)) {
				$error_msg  = __( 'Sorry, an error occurs from Vimeo API:', 'tg-text-domain' );
				$error_msg .= ' '.$response->error;
				throw new Exception($error_msg);
			}
			if (isset($response) && !empty($response)){
				set_transient($transient_name, $response, $this->transient_sec);
			} else if (!$tg_is_ajax) {
				$error_msg  = __( 'No content was found for the current User/Album/Group/Channel.', 'tg-text-domain' );
				throw new Exception($error_msg);
			}
		}
		
		return $response;
		
	}
	
	/**
	* Convert Vimeo duration format
	* @since 1.0.0
	*/
	public function covtime($duration){
		
		if ($duration/3600 >= 1) {
    		return gmdate('H:i:s', $duration);
		} else {
			return gmdate('i:s', $duration);
		}
		
	} 
	
	/**
	* Get excerpt
	* @since 2.0.0
	*/
	public function get_excerpt($data) {
		
		if (isset($data->description) && !empty($data->description)) {
			
			$attributes = ' target="_blank" class="tg-item-social-link"'; 
			return preg_replace('/(https?:\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/i', '<a href="$1"'.$attributes.'>$1</a>', $data->description);

		}
	
	}
	
	/**
	* Build data array for the grid
	* @since 1.0.0
	*/
	public function build_media_array($response, $type, $type_id) {
		
		$videos = array();

		if (isset($response->data)) {

			foreach ($response->data as $data) {
				
				for ($i = 3; $i >= 0; $i--) {
					if (!empty($data->pictures->sizes[$i]->link)) {
						$index = $i;
						break;
					}
				}

				$videos[] = array(
					'ID'              => str_replace('/videos/', '', $data->uri),
					'type'            => $type,
					'type_id'         => $type_id,
					'date'            => (isset($data->created_time)) ?  strtotime($data->created_time) : null,
					'post_type'       => null,
					'format'          => 'video',
					'url'             => (isset($data->link)) ? $data->link : null,
					'url_target'      => '_blank',
					'title'           => (isset($data->name)) ? $data->name : null,
					'excerpt'         => (isset($data->description)) ? $this->get_excerpt($data) : null,
					'terms'           => null,
					'author'          => array(
						'ID'     => '',
						'name'   => (isset($data->user->name)) ? $data->user->name : null,
						'url'    => (isset($data->user->link)) ? $data->user->link : null,
						'avatar' => (isset($data->user->pictures->sizes[1]->link)) ?  $data->user->pictures->sizes[1]->link : null,
					),
					'likes_number'    => (isset($data->metadata->connections->likes->total)) ? $data->metadata->connections->likes->total : null,
					'likes_title'     =>  __( 'Like on Vimeo', 'tg-text-domain' ),
					'comments_number' => (isset($data->metadata->connections->comments->total)) ? $data->metadata->connections->comments->total : null,
					'views_number'    => (isset($data->stats->plays)) ? $data->stats->plays : null,
					'image'           => array(
						'alt'    => null,
						'url'    => (isset($data->pictures->sizes[$index]->link)) ? $data->pictures->sizes[$index]->link : null,
						'width'  => (isset($data->pictures->sizes[$index]->width)) ? $data->pictures->sizes[$index]->width : null,
						'height' => (isset($data->pictures->sizes[$index]->height)) ? $data->pictures->sizes[$index]->height : null
					),
					'gallery'         => null,
					'video'           => array(
						'type'     => 'vimeo',
						'duration' => (isset($data->duration)) ? $this->covtime($data->duration) : null,
						'source'   => array(
							'ID'   => str_replace('/videos/', '', $data->uri)
						),
					),
					'audio'           => null,
					'quote'           => null,
					'link'            => null,
					'meta_data'       => null
				);
	
			}

		}

		return $videos;
		
	}
	
}