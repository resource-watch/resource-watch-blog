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

class The_Grid_Flickr {
	
	/**
	* API Key
	*
	* @since 2.0.0
	* @access private
	*
	* @var string
	*/
	private $api_key;
	
	/**
	* API Key
	*
	* @since 2.0.0
	* @access private
	*
	* @var array
	*/
	private $flickr_owner_ids = array();
	
	/**
	* Flickr query parameters (default)
	*
	* @since 2.0.0
	* @access private
	*
	* @var array
	*/
	private $default_params = array();
		
	/**
	* Flickr transient
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
	* Grid media
	*
	* @since 2.0.0
	* @access private
	*
	* @var array
	*/
	private $media = array();
	
	/**
	* Initialize the class and set its properties.
	* @since 2.0.0
	*/
	public function __construct($grid_data = '') {
		
		$this->grid_data = $grid_data;
		$this->get_api_key();
		$this->get_default_query_params();
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
	* Get Flickr API Key
	* @since: 2.0.0
	*/
	public function get_api_key(){
		
		$this->api_key = trim(get_option('the_grid_flickr_api_key', ''));

		if (empty($this->api_key)) {
			
			$error_msg  = __( 'You didn\'t authorize The Grid to', 'tg-text-domain' );
			$error_msg .= ' <a style="text-decoration: underline" href="'.admin_url('admin.php?page=the_grid_global_settings').'">';
			$error_msg .= __( 'connect to Flickr.', 'tg-text-domain' );
			$error_msg .= '</a>';
			throw new Exception($error_msg);
			
		}
	
	}
	
	/**
	* Get Flickr default query parameters
	* @since: 2.0.0
	*/
	public function get_default_query_params(){
		
		$this->default_params = array(
			'api_key'        => $this->api_key,
			'nojsoncallback' => 1,
			'format'         => 'json'
		);
	
	}
	
	/**
	* Get Flickr transient expiration
	* @since: 2.0.0
	*/
	public function get_transient_expiration(){
		
		$this->transient_sec = apply_filters('tg_transient_flickr', 3600);
		
	}
	
	/**
	* Return array of data
	* @since 2.0.0
	*/
	public function get_grid_items() {
		
		switch ($this->grid_data['flickr_source']) {
			case 'public_photos':
				$this->_makeCall('flickr.people.getPublicPhotos');
				break;
			case 'photo_sets':
				$this->_makeCall('flickr.photosets.getPhotos');
				break;
			case 'gallery':
				$this->_makeCall('flickr.galleries.getPhotos');
				break;
			case 'group':
				$this->_makeCall('flickr.groups.pools.getPhotos');
				break;
			default:
				$error_msg  = __( 'No Flickr source was set in The Grid settings.', 'tg-text-domain' );
				throw new Exception($error_msg);
		}

		return $this->media;

	}
	
	/**
	* Get user id from user url
	* @since 2.0.0
	*/
	public function get_user_id(){
		
		$response = $this->get_response(array(
			'method' => 'flickr.urls.lookupUser',
			'url'    => $this->grid_data['flickr_user_url'],
		));

		if (isset($response->user->id) && !empty($response->user->id)) {
			return $response->user->id;
		}

	}
	
	/**
	* Get photoset id from user id
	* @since 2.0.0
	*/
	public function get_photoset_id(){

		$response = $this->get_response(array(
			'method'  => 'flickr.photosets.getList',
			'user_id' => $this->get_user_id(),
		));
		
		return $response;
		
	}
	
	/**
	* Get group id from group url
	* @since 2.0.0
	*/
	public function get_group_id(){

		$response = $this->get_response(array(
			'method' => 'flickr.urls.lookupGroup',
			'url'    => $this->grid_data['flickr_group_url'],
		));
		
		if (isset($response->group->id) && !empty($response->group->id)) {
			return $response->group->id;
		}
		
	}

	/**
	* Get gallery id from gallery url
	* @since 2.0.0
	*/
	public function get_gallery_id() {
		
		$response = $this->get_response(array(
			'method' => 'flickr.urls.lookupGallery',
			'url'    => $this->grid_data['flickr_gallery_url'],
		));

		if (isset($response->gallery->id) && !empty($response->gallery->id)) {
			return $response->gallery->id;
		}

	}
	
	/**
	* Get page number from offset
	* @since 2.0.0
	*/
	public function get_page_number() {
		
		$offset  = $this->grid_data['offset'];
		$item_nb = $this->grid_data['item_number'];

		if ($item_nb > $offset) {
			$item_nb = $item_nb+$offset;
			$page_nb = 1;
		} else {
			$i = $offset - 1;
			while ($i > 0 && (int)($offset/$i) != ($offset/$i)) {
				$i--;
			}
			$page_nb = ($i > $item_nb) ? $offset/$i : 1;
			$item_nb = ($i > $item_nb) ? $i : $offset;
			$page_nb++;
		}

		return array(
			'page'     => $page_nb,
			'per_page' => $item_nb,
		);

	}
	
	/**
	* Make call to Flickr server
	* @since 2.0.0
	*/
	public function _makeCall($query) {
		
		global $tg_is_ajax;

		$query_data = $this->get_page_number();

		// set and retrieve response
		$query_params = array(
			'method'      => $query,
			'page'        => $query_data['page'],
			'per_page'    => $query_data['per_page'],
			'user_id'     => ($query == 'flickr.people.getPublicPhotos') ? $this->get_user_id() : null,
			'photoset_id' => ($query == 'flickr.photosets.getPhotos')    ? $this->grid_data['flickr_photoset_id'] : null,
			'gallery_id'  => ($query == 'flickr.galleries.getPhotos')    ? $this->get_gallery_id(): null, 
			'group_id'    => ($query == 'flickr.groups.pools.getPhotos') ? $this->get_group_id() : null,
			'extras'      => 'description, date_upload, date_taken, owner_name, icon_server, original_format, o_dims, views, media, url_m, url_l, url_o, count_comments, count_faves'
		);
		
		$this->media = $this->get_response($query_params, $this->transient_sec, true);
	
		if (empty($this->media) && !$tg_is_ajax) {
			$error_msg  = __( 'No content was found for the current Flickr settings.', 'tg-text-domain' );
			throw new Exception($error_msg);
		}

	}


	/**
	* Get url response
	* @since 2.0.0
	*/
	public function get_response($query_params, $transient_sec = 0, $build = false) {
		
		global $tg_is_ajax;
		
		$query_params = array_merge((array) $this->default_params, (array) $query_params);
		
		$url = 'https://api.flickr.com/services/rest/?'.http_build_query($query_params,'','&');

		$transient_name = 'tg_grid_' . md5($url);
		
		if ($this->transient_sec > 0 && ($transient = get_transient($transient_name)) !== false) {
			$json = ($build) ? json_decode($transient, true) : json_decode($transient);
		} else {
			$response = wp_remote_fopen($url);
			$json  = json_decode(wp_remote_fopen($url));
			$page  = isset($json->photos->page) ? $json->photos->page : null;
			$page  = isset($json->photoset->page) ? $json->photoset->page : $page;
			if (isset($json->stat) && $json->stat == 'fail' && !$tg_is_ajax) {
				$error_msg  = __( 'Sorry, an error occurs from Flickr API:', 'tg-text-domain' );
				$error_msg .= '<br>';
				$error_msg .= ' '.$json->message;
				throw new Exception($error_msg);
			} else {
				$json = ($build) ? array_slice($this->build_media_array($json), ($page == 1 ? $this->grid_data['offset'] : 0),  $this->grid_data['item_number']) : $json;
				set_transient($transient_name, json_encode($json, JSON_FORCE_OBJECT), (int) $transient_sec);
			}
			
		}
		
		return $json;
	
	}

	/**
	* Get image from Flickr
	* @since 2.0.0
	*/
	public function get_image($data) {
				
		if (isset($data->url_m) || isset($data->url_l)) {

			return array(
				'alt'    => null,
				'title'  => isset($data->title)    ? $data->title : null,
				'url'    => isset($data->url_m)    ? $data->url_m : $data->url_l,
				'lb_url' => isset($data->url_l)    ? $data->url_l : (isset($data->url_o) ? $data->url_o : $data->url_m),
				'width'  => isset($data->width_m)  ? $data->width_m : $data->width_l,
				'height' => isset($data->height_m) ? $data->height_m : $data->height_l
			);
			
		}
	
	}
	
	/**
	* Get post url from Flickr
	* @since 2.0.0
	*/
	public function get_post_url($data) {
		
		if (isset($data->id) && !empty($data->id)) {
			return 'http://flic.kr/p/'.The_Grid_Base::base58_encode($data->id);
		}
	
	}
	
	/**
	* Get post excerpt from Flickr
	* @since 2.0.0
	*/
	public function get_excerpt($data) {
		
		if (isset($data->description->_content) && !empty($data->description->_content)) {
			
			if ($data->description->_content== 'Untitled') {
				return null;
			}
			
			$attributes = ' target="_blank" class="tg-item-social-link"'; 
			return preg_replace('/<a.*href="(.+)">(.+)<\/a>/','<a href="$1" target="_blank" class="tg-item-social-link">$2</a>', $data->description->_content);

		}
	
	}
	
	/**
	* Get user data from user owner
	* @since 2.0.0
	*/
	public function get_author_data($data) {
		
		if ($this->grid_data['flickr_source'] != 'public_photos') {
			
			return array(
				'ID'     => isset($data->id) ? $data->id : null,
				'name'   => isset($data->ownername) ? $data->ownername : null,
				'url'    => isset($data->owner) ? 'https://www.flickr.com/photos/'.$data->owner : $this->grid_data['flickr_user_url'],
				'avatar' => null
			);
		
		}
		
		if (isset($data->owner) && !empty($data->owner) && !array_key_exists($data->owner, $this->flickr_owner_ids)){

			$json = $this->get_response(array(
				'method'  => 'flickr.people.getInfo',
				'user_id' => $data->owner,
			));

			if (isset($json->person->nsid) && !empty($json->person->nsid)) {

				if ($json->person->iconserver != 0) {	
					$avatar_url = 'http://farm'.$json->person->iconfarm.'.staticflickr.com/'.$json->person->iconserver.'/buddyicons/'.$json->person->nsid.'.jpg' ;
				} else {
					$avatar_url = 'https://www.flickr.com/images/buddyicon.gif';
				}

				$author = array(
					'ID'     => isset($json->person->nsid) ? $json->person->nsid : $data->owner,
					'name'   => isset($json->person->realname->_content) && !empty($json->person->realname->_content) ? $json->person->realname->_content : $data->ownername,
					'url'    => isset($json->person->nsid) ? 'https://www.flickr.com/photos/'.$json->person->nsid.'/' : 'https://www.flickr.com/photos/'.$data->owner.'/',
					'avatar' => $avatar_url
				);
				
				return $this->flickr_owner_ids[$data->owner] = $author;
				
			}
			
		} else if (isset($data->owner) && array_key_exists($data->owner, $this->flickr_owner_ids)) {
			
			return $this->flickr_owner_ids[$data->owner];
			
		}
		
		return;
		
	}
	 
	/**
	* Build data array for the grid
	* @since 2.0.0
	*/
	public function build_media_array($response) {
		
		if (isset($response) && !empty($response)) {
			
			if (isset($response->photos->photo)) {
				$response = $response->photos->photo;
			} else if (isset($response->photoset->photo)) {
				$response = $response->photoset->photo;
			} else {
				return array();
			}

			foreach ($response as $data) {
				
				$this->media[] = array(
					'ID'              => isset($data->id) ? $data->id : null,
					'date'            => isset($data->datetaken) ? strtotime($data->datetaken) : null,
					'post_type'       => null,
					'format'          => 'standard',
					'url'             => $this->get_post_url($data),
					'url_target'      => '_blank',
					'title'           => isset($data->title) ? $data->title : null,
					'excerpt'         => $this->get_excerpt($data),
					'terms'           => null,
					'author'          => $this->get_author_data($data),
					'likes_number'    => isset($data->count_faves) ? $data->count_faves : null,
					'likes_title'     => __( 'Favorite on Flickr', 'tg-text-domain' ),
					'comments_number' => isset($data->count_comments) ? $data->count_comments : null,
					'views_number'    => isset($data->views) ? $data->views : null,
					'image'           => $this->get_image($data),
					'gallery'         => null,
					'video'           => null,
					'audio'           => null,
					'quote'           => null,
					'link'            => null,
					'meta_data'       => null
				);

			}

		}
		
		return $this->media;
		
	}
	
}