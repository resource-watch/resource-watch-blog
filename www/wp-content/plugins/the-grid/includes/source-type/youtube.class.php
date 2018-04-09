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

class The_Grid_Youtube {
	
	/**
	* Youtube API Key
	*
	* @since 1.0.0
	* @access private
	*
	* @var integer
	*/
	private $api_key;
	
	/**
	* Youtube transient
	*
	* @since 1.0.0
	* @access private
	*
	* @var string
	*/
	private $transient_sec;
	
	/**
	* Youtube error
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
	* Youtube count
	*
	* @since 1.0.0
	* @access private
	*
	* @var integer
	*/
	private $count;
	
	/**
	* Youtube media data
	*
	* @since 1.0.0
	* @access private
	*
	* @var array
	*/
	private $media = array();
	
	/**
	* Youtube last media
	*
	* @since 1.0.0
	* @access private
	*
	* @var string/array
	*/
	private $last_media = array();
	
	/**
	* Youtube paramters
	*
	* @since 1.0.0
	* @access private
	*
	* @var string/array
	*/
	private $order;
	private $source_type;
	private $playlist_id;
	private $channel_id;
	private $video_ids = array();
	private $videos_details = array();
	
	/**
	* Initialize the class and set its properties.
	* @since 1.0.0
	*/
	public function __construct($grid_data = '') {
		
		$this->get_API_key();
		$this->get_transient_expiration();
		$this->grid_data = $grid_data;
		
	}

	/**
	* Get Youtube API Key
	* @since: 1.0.0
	*/
	public function get_API_key(){
		
		$this->api_key = trim(get_option('the_grid_youtube_api_key', ''));
		
		if (empty($this->api_key)) {
			$error_msg  = __( 'You didn\'t authorize The Grid to', 'tg-text-domain' );
			$error_msg .= ' <a style="text-decoration: underline;" href="'.admin_url('admin.php?page=the_grid_global_settings').'">';
			$error_msg .= __( 'connect to Youtube.', 'tg-text-domain' );
			$error_msg .= '</a>';
			throw new Exception($error_msg);
		}

	}
	
	/**
	* Get Youtube transient expiration
	* @since: 1.0.0
	*/
	public function get_transient_expiration(){
		
		$this->transient_sec = apply_filters('tg_transient_youtube', 3600);
		
	}
	
	/**
	* Return array of data
	* @since 1.0.0
	*/
	public function get_grid_items() {
		
		$this->get_data(
			$this->grid_data['youtube_order'],
			$this->grid_data['youtube_source'],
			$this->grid_data['youtube_channel'],
			$this->grid_data['youtube_playlist'],
			$this->grid_data['youtube_videos'],
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
	public function get_data($order, $source, $channel, $playlist, $videos, $count){
		
		// store Youtube data
		$this->order       = $order;
		$this->source_type = $source;
		$this->channel_id  = $channel;
		$this->playlist_id = $playlist;
		$this->video_ids   = preg_replace('/\s+/', '', $videos);
		$this->count = ($count <= 0) ? 10 : $count;
		$this->count = ($this->count > 50) ? 50 : $this->count;
		
		// get last media from ajax
		$this->last_media = (isset($_POST['grid_ajax']) && !empty($_POST['grid_ajax'])) ? $_POST['grid_ajax'] : array();
		$this->last_media['pageToken'] = (isset($this->last_media['pageToken'])) ? $this->last_media['pageToken'] : '';
		$this->last_media['count'] = (isset($this->last_media['count'])) ? (int) $this->last_media['count'] : 0;
		
		if (isset($this->last_media['total']) && $this->last_media['count'] >= $this->last_media['total']) {
			return '';
		}
		
		// retrieve Youtube data
		$this->get_media();

		
		return $this->media;
		
	}
	
	/**
	* Retrieve media data
	* @since 1.0.0
	*/
	public function get_media() {
		
		switch ($this->source_type) {
			case 'channel_info':
				$this->get_channel_info();		
				break;
			case 'channel':
				$this->get_channel();
				$this->get_video_ids();			
				break;
			case 'playlist':
				$this->get_playlist();
				$this->get_video_ids();
				break;
			case 'videos':
				$videos_array = explode(',', $this->video_ids);
				$this->last_media['total'] = count($videos_array);
				$videos_array = array_slice($videos_array, $this->last_media['count'], $this->count);
				$this->video_ids = implode(',', $videos_array);
				break;
		}
		
		// get each video data
		$this->get_video();

		// store the total number of items retrieved
		$this->last_media['count'] = $this->last_media['count'] + count($this->media);
		
	}
	
	/**
	* Retrieve media data
	* @since 1.0.0
	*/
	public function get_video_ids() {
		
		$this->video_ids = array();
		
		if (isset($this->media->items)) {
			
			// loop through each video details
			foreach($this->media->items as $item) {
				
				// get video id (depends if playlist or not)
				if (isset($item->id->videoId)) {
					array_push($this->video_ids, $item->id->videoId);
				} else if (isset($item->snippet->resourceId->videoId)) {
					array_push($this->video_ids, $item->snippet->resourceId->videoId);
				}
				
			}

			// prepare video id for videos youtube call
			$this->video_ids = implode(',', $this->video_ids);
			
		}

	}
	
	/**
	* Get Youtube Channel Items
	* @since    1.0.0
	*/
	public function get_channel_info() {
		
		$call = $this->_makeCall('channels', 'id', $this->channel_id, 'id,contentDetails,snippet,brandingSettings,statistics');

	}

	/**
	* Get Youtube Channel Items
	* @since    1.0.0
	*/
	public function get_channel() {
		
		$call = $this->_makeCall('search', 'channelId', $this->channel_id, 'snippet&type=video', true);
		$this->last_media['pageToken'] = (isset($call->nextPageToken)) ? $call->nextPageToken : '';
		$this->last_media['total'] = (isset($call->pageInfo->totalResults)) ? $call->pageInfo->totalResults : '';
		
	}
	
	/**
	* Get Youtube Playlist Items
	* @since    1.0.0
	*/
	public function get_playlist() {
		
		$call = $this->_makeCall('playlistItems', 'playlistId', $this->playlist_id, 'snippet,contentDetails', true);
		$this->last_media['pageToken'] = (isset($call->nextPageToken)) ? $call->nextPageToken : '';
		$this->last_media['total'] = (isset($call->pageInfo->totalResults)) ? $call->pageInfo->totalResults : '';
		
	}
	
	/**
	* Get Youtube videos details
	* @since 1.0.0
	*/
	public function get_video() {

		if (!empty($this->video_ids)) {
			
			$this->videos_details = $this->_makeCall('videos', 'id', $this->video_ids, 'snippet,contentDetails,statistics,status');
			$this->media = $this->build_media_array($this->videos_details, '', '');
			
		}
	
	}
	
	/**
	* Youtube API call
	* @since 1.0.0
	*/
	public function _makeCall($type, $id_type, $id, $part, $page = null) {

		// set and retrieve response
		$page  = $page ? '&pageToken='.$this->last_media['pageToken'] : '';
		$order = ($type == 'search') ? '&order='.$this->order : '';
		$url   = 'https://www.googleapis.com/youtube/v3/'.$type.'?'.$id_type.'='.$id.'&part='.$part.'&maxResults='.$this->count.'&key='.$this->api_key.$page.$order;

		$response = $this->get_response($url);

		if (isset($response) && !empty($response)){
			$this->media = $response;
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
			
			if (isset($response->error->errors[0]->reason)) {
				$error_msg  = __( 'Sorry, an error occurs from Youtube API:', 'tg-text-domain' );
				$error_msg .= ' '.$response->error->errors[0]->reason;
				throw new Exception($error_msg);
			}
			
			if (isset($response->items) && !empty($response->items)){
				set_transient($transient_name, $response, $this->transient_sec);
			} else if (!$tg_is_ajax) {
				$error_msg  = __( 'No content was found for the current Channel/Playlist/Videos.', 'tg-text-domain' );
				throw new Exception($error_msg);
			}
			
		}
		
		return $response;
		
	}
	
	/**
	* Convert Youtube duration format
	* @since 1.0.0
	*/
	public function covtime($duration){

		$duration = new DateInterval($duration);
		return (intval($duration->format('%h')) != 0) ? $duration->format('%H:%I:%S') : $duration->format('%I:%S');
		
	} 
	
	/**
	* Get excerpt
	* @since 2.0.0
	*/
	public function get_excerpt($data) {
		
		if (isset($data->snippet->description) && !empty($data->snippet->description)) {
			
			$attributes = ' target="_blank" class="tg-item-social-link"'; 
			return preg_replace('/(https?:\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/i', '<a href="$1"'.$attributes.'>$1</a>', $data->snippet->description);

		}
	
	}
	
	/**
	* Build data array for the grid
	* @since 1.0.0
	*/
	public function build_media_array($response, $type, $type_id) {
		
		$videos = array();
		
		if (isset($response->items)) {

			foreach ($response->items as $data) {

				$videos[] = array(
					'ID'              => $data->id,
					'type'            => $type,
					'type_id'         => $type_id,
					'date'            => (isset($data->snippet->publishedAt)) ?  strtotime($data->snippet->publishedAt) : null,
					'post_type'       => null,
					'format'          => 'video',
					'url'             => 'https://www.youtube.com/watch?v='.$data->id,
					'url_target'      => '_blank',
					'title'           => (isset($data->snippet->title)) ? $data->snippet->title : null,
					'excerpt'         => (isset($data->snippet->description)) ? $this->get_excerpt($data) : null,
					'terms'           => null,
					'author'          => array(
						'ID'     => '',
						'name'   => (isset($data->snippet->channelTitle)) ? $data->snippet->channelTitle : null,
						'url'    => null,
						'avatar' => null,
					),
					'likes_number'    => (isset($data->statistics->likeCount)) ? $data->statistics->likeCount : null,
					'likes_title'     =>  __( 'Like on Youtube', 'tg-text-domain' ),
					'comments_number' => (isset($data->statistics->commentCount)) ? $data->statistics->commentCount : null,
					'views_number'    => (isset($data->statistics->viewCount)) ? $data->statistics->viewCount : null,
					'image'           => array(
						'alt'    => null,
						'url'    => (isset($data->snippet->thumbnails->high->url)) ? $data->snippet->thumbnails->high->url : null,
						'width'  => (isset($data->snippet->thumbnails->high->width)) ? $data->snippet->thumbnails->high->width : null,
						'height' => (isset($data->snippet->thumbnails->high->height)) ? $data->snippet->thumbnails->high->height : null
					),
					'gallery'         => null,
					'video'           => array(
						'type'     => 'youtube',
						'duration' => (isset($data->contentDetails->duration)) ? $this->covtime($data->contentDetails->duration) : null,
						'source'   => array(
							'ID'   => $data->id
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