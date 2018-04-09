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

class The_Grid_Twitter {
	
	/**
	* Twitter Consumer Key
	*
	* @since 2.0.0
	* @access private
	*
	* @var string
	*/
	private $consumer_key;
	
	/**
	* Twitter Consumer Secret
	*
	* @since 2.0.0
	* @access private
	*
	* @var string
	*/
	private $consumer_secret;
	
	/**
	* Twitter Access Token
	*
	* @since 2.0.0
	* @access private
	*
	* @var string
	*/
	private $access_token;
		
	/**
	* Twitter transient
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
	* Grid Max ID
	*
	* @since 2.0.0
	* @access private
	*
	* @var integer
	*/
	private $max_id = null;
	
	/**
	* Initialize the class and set its properties.
	* @since 2.0.0
	*/
	public function __construct($grid_data = '') {
		
		$this->grid_data  = $grid_data;
		$this->max_id = (isset($_POST['grid_ajax']) && !empty($_POST['grid_ajax'])) ? $_POST['grid_ajax'] : null;
		
		$this->get_api_key();
		$this->get_transient_expiration();
		$this->get_bearer_token();
		
	}
	
	/**
	* Return array of grid data
	* @since: 2.0.0
	*/
	public function get_grid_data(){

		return $this->grid_data;
		
	}
	
	/**
	* Get Twitter APP Key
	* @since: 2.0.0
	*/
	public function get_api_key(){
		
		$this->consumer_key    = trim(get_option('the_grid_twitter_consumer_key', ''));
		$this->consumer_secret = trim(get_option('the_grid_twitter_consumer_secret', ''));

		if (empty($this->consumer_key) || empty($this->consumer_secret)) {
			
			$error_msg  = __( 'You didn\'t authorize The Grid to', 'tg-text-domain' );
			$error_msg .= ' <a style="text-decoration: underline" href="'.admin_url('admin.php?page=the_grid_global_settings').'">';
			$error_msg .= __( 'connect to Twitter.', 'tg-text-domain' );
			$error_msg .= '</a>';
			throw new Exception($error_msg);
			
		}
	
	}
	
	/**
	* Get Twitter transient expiration
	* @since: 2.0.0
	*/
	public function get_transient_expiration(){
		
		$this->transient_sec = apply_filters('tg_transient_twitter', 3600);
		
	}
	
	/**
	* Return array of data
	* @since 2.0.0
	*/
	public function get_grid_items() {
		
		switch ($this->grid_data['twitter_source']) {
			case 'user_timeline':
				$this->get_response('statuses/user_timeline');
				break;
			case 'search':
				$this->get_response('search/tweets');
				break;
			case 'favorites':
				$this->get_response('favorites/list');
				break;
			case 'list_timeline':
				$this->get_response('lists/statuses');
				break;
			default:
				$error_msg  = __( 'No Twitter source was set in The Grid settings.', 'tg-text-domain' );
				throw new Exception($error_msg);
		}

		return $this->media;

	}	

	/**
	* Get the token from oauth Twitter API
	* @since: 2.0.0
	*/
	public function get_bearer_token() {

		// retrieve access token & consumer data
		$twitter_app = get_option('tg_twitter_bearer_token');

		// if an access token is set return directly (also check if consumer key & secret didn't changed)
		if (isset($twitter_app['consumer_key'])    == $this->consumer_key    &&
			isset($twitter_app['consumer_secret']) == $this->consumer_secret &&
			isset($twitter_app['access_token']) && !empty($twitter_app['access_token'])) {
			$this->access_token = $twitter_app['access_token'];
			return;
		}

		// set header arguments for wp_remote_post
		$args = array(
			'method'      => 'POST',
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'blocking'    => true,
			'headers'     => array(
				'Authorization' => 'Basic ' . base64_encode($this->consumer_key . ':' . $this->consumer_secret),
				'Content-Type'  => 'application/x-www-form-urlencoded;charset=UTF-8',
			),
			'body' => array(
				'grant_type' => 'client_credentials'
			),
			'decompress' => false // prevent gzinflate() error
		);
             
		// get twitter oauth
		$response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);
            
		// if an error occurs    
		if (is_wp_error($response) || !isset($response['body'])) {
			
			$error_msg  = __( 'Sorry, can\'t connect to Twitter server.', 'tg-text-domain' );
			$error_msg .= '<br>';
			$error_msg .= __( 'Please check your credentials in the global settings.', 'tg-text-domain' );
			throw new Exception($error_msg);

		}
        
		// decode body response 
		$result = json_decode($response['body']);
		
		// if an access token exist then store it
		if (isset($result->access_token) && !empty($result->access_token)) {

			$this->access_token = $result->access_token;
			
			$twitter_app = array(
				'consumer_key'    => $this->consumer_key,
				'consumer_secret' => $this->consumer_secret,
				'access_token'    => $this->access_token
			);
			
			update_option('tg_twitter_bearer_token', $twitter_app);
					
		}

	}
	
	/**
	* Get url response
	* @since 2.0.0
	*/
	public function get_response($type) {
		
		global $tg_is_ajax;
		
		// if ajax request and no max_id then return directly
		if (!$this->max_id && $tg_is_ajax) {
			return;
		}
		
		$this->get_tweets($type);
		
		// if there are items and a max_id
		if (sizeof($this->media) > 0 && $this->max_id) {
			
			// while the number of item is not reached then query Twitter
			while (sizeof($this->media) < $this->grid_data['item_number'] && $this->max_id) {
				$this->get_tweets($type);
			}
		
		// if not ajax request and no tweets
		} else if (sizeof($this->media) == 0 && !$tg_is_ajax) {
			
			$error_msg = __( 'No content was found from Twitter.', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}
		
		// if there are items in the result
		if (sizeof($this->media) > 0) {
			
			// reduce array if number of tweets bigger than the item number requested
			$this->media = sizeof($this->media) > $this->grid_data['item_number'] ? array_slice($this->media, 0, $this->grid_data['item_number']) : $this->media;
			$this->media = array_map('unserialize', array_unique(array_map('serialize', $this->media)));
			
		}
		
		// set max_id once the array was reduced
		$this->max_id = (sizeof($this->media) > 0) ? $this->media[sizeof($this->media)-1]['ID'] : null;
		
		// store max_id for next ajax call
		$this->grid_data['ajax_data'] = $this->max_id;
	
	}
	
	/**
	* Get tweets from Twitter
	* @since 2.0.0
	*/
	public function get_tweets($type) {
		
		// Query twitter API
		$tweets = $this->query($type);
		// Build item array
		$tweets = (array) $this->build_media_array($tweets);
		// Store max_id for next query
		$this->max_id = sizeof($tweets) > 0 ? $tweets[sizeof($tweets)-1]['ID'] : null;

	}
	
	/**
	* Set query args for Twitter
	* @since 2.0.0
	*/
	public function query_args() {
		
		$twitter_username  = $this->grid_data['twitter_source'] != 'list_timeline' ? '&screen_name='.$this->grid_data['twitter_username'] : '&owner_screen_name='.$this->grid_data['twitter_username'];
		$twitter_listname  = ($this->grid_data['twitter_listname'] && $this->grid_data['twitter_source'] == 'list_timeline') ? 'slug='.$this->grid_data['twitter_listname'].'&' : null;
		$twitter_searchkey = ($this->grid_data['twitter_source'] == 'search') ? 'q='.urlencode($this->grid_data['twitter_searchkey']).'&' : null;
		$include_retweets  = (isset($this->grid_data['twitter_include']) && in_array('retweets', (array) $this->grid_data['twitter_include'])) ? 'true' : 'false';
		$exclude_replies   = (isset($this->grid_data['twitter_include']) && in_array('replies', (array) $this->grid_data['twitter_include']))  ? 'false' : 'true';
		$twitter_max_id    = $this->max_id ? 'max_id='.$this->max_id.'&' : null;

		return $twitter_max_id.
			$twitter_username.'&'.
			$twitter_listname.
			$twitter_searchkey.
			'count='.($this->grid_data['item_number']+20).
			'&include_entities=true'.
			'&include_rts='.$include_retweets.
			'&exclude_replies='.$exclude_replies.
			'&tweet_mode=extended'.
			'&result_type=mixed';
	
	}
	
	/**
	* Query Twitter API
	* @since: 2.0.0
	*/
	public function query($type) {
		
		$query_args = $this->query_args();
             
		$transient_name = 'tg_grid_' . md5($type.$query_args);
		
		if (false !== ($tweets = get_transient($transient_name))) {
			return json_decode($tweets);
		}
                
		$args = array(
			'method'      => 'GET',
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(
				'Authorization'   => 'Bearer ' . $this->access_token,
			),
			'body'       => null,
			'cookies'    => array(),
			'decompress' => false // prevent gzinflate() error
		);
        
		$response = wp_remote_get('https://api.twitter.com/1.1/' . $type . '.json?' . $query_args, $args);

		if (is_wp_error($response) || !isset($response['body'])) {
  
			$twitter_error = is_array($response) && isset($response['body']) ? json_decode((array) $response['body']) : null;
			$twitter_error = isset($twitter_error->errors[0]->message) ? $twitter_error->errors[0]->message : null;
			$error_msg  = __( 'Sorry, an error occured from Twitter:', 'tg-text-domain' );
			$error_msg .= '<br>';
			$error_msg .= $twitter_error ? $twitter_error : __( 'API issue, please try to reload your page and or clear The Grid cache.', 'tg-text-domain' );
			throw new Exception($error_msg);
                        
		}
		
		$tweets = $response['body'];
		
		set_transient($transient_name, $tweets, $this->transient_sec);
		
		return json_decode($tweets);
                
	}
	
	/**
	* Convert tweet text with pretty url
	* @since 2.0.0
	*/
	public function tweet_text($tweet) {
		
		$reply    = isset( $tweet->in_reply_to_status_id ) && ! empty( $tweet->in_reply_to_status_id );
		$data     = isset( $tweet->retweeted_status ) ? $tweet->retweeted_status : $tweet;
		$text     = isset( $data->display_text_range ) && ! $reply ? mb_substr( $data->full_text, $data->display_text_range[0], $data->display_text_range[1] - $data->display_text_range[0], 'UTF-8' ) : $data->full_text;
		$entities = array();

		if (isset($data->entities->urls) && is_array($data->entities->urls)) {
			foreach($data->entities->urls as $e) {
				array_push($entities, array(
					'start' => $e->indices[0],
					'end'   => $e->indices[1],
					'repl'  => '<a href="'.$e->expanded_url.'" target="_blank" class="tg-item-social-link">'.$e->display_url.'</a>'
				));
			}
		} 
		
		if (isset($data->entities->user_mentions) && is_array($data->entities->user_mentions)) {
			foreach($data->entities->user_mentions as $e) {
				array_push($entities, array(
					'start' => $e->indices[0],
					'end'   => $e->indices[1],
					'repl'  => '<a href="https://twitter.com/'.$e->screen_name.'" target="_blank" class="tg-item-social-link">@'.$e->screen_name.'</a>'
				));
			}
		}
		
		if (isset($data->entities->hashtags) && is_array($data->entities->hashtags)) {
			foreach($data->entities->hashtags as $e) {
				array_push($entities, array(
					'start' => $e->indices[0],
					'end'   => $e->indices[1],
					'repl'  => '<a href="https://twitter.com/hashtag/'.$e->text.'?src=hash" target="_blank" class="tg-item-social-link">#'.$e->text.'</a>'
				));
			}
		}
		
		if (isset($data->entities->media) && is_array($data->entities->media)) {
			foreach($data->entities->media as $e) {
				array_push($entities, array(
					'start' => $e->indices[0],
					'end'   => $e->indices[1],
					'repl'  => null
				));
			}
		}
	
		usort($entities, function($a,$b){
			return($b['start'] - $a['start']);
		});
	
		foreach ($entities as $item) {
			$startString = mb_substr($text, 0, $item['start'], 'UTF-8');
			$endString   = mb_substr($text, $item['end'], mb_strlen($text), 'UTF-8');
			$text = $startString . $item['repl'] . $endString;
		}
		
		if ( isset( $tweet->retweeted_status ) ) {

			foreach( $tweet->entities->user_mentions as $e ) {

				if ( $e->indices[0] === 3 && $e->indices[1] === 14 ) {
					$replace     = '<a href="https://twitter.com/'.$e->screen_name.'" target="_blank" class="tg-item-social-link">@'.$e->screen_name.'</a>';
					$text        = 'RT ' . $replace . ': ' . $text;
				} else {
					break;
				}

			}
			
		}

		return $text;
		
	}
	 
	/**
	* Build data array for the grid
	* @since 2.0.0
	*/
	public function build_media_array($response) {
		
		$items = array();

		if (isset($response) && !empty($response)) {
			
			$response = isset($response->statuses) ? $response->statuses : $response;

			foreach ((array) $response as $data) {
				
				if (isset($data->id_str) && $data->id_str != $this->max_id) {

					$image = null;

					if(isset($data->entities->media[0])) {
						
						$image = array(
							'alt'    => null,
							'url'    => $data->entities->media[0]->media_url_https,
							'width'  => $data->entities->media[0]->sizes->large->w,
							'height' => $data->entities->media[0]->sizes->large->h
						);
						
					}

					$this->media[] = $items[] = array(
						'ID'              => $data->id_str,
						'date'            => isset($data->created_at) ? strtotime($data->created_at) : null,
						'post_type'       => null,
						'format'          => 'standard',
						'url'             => isset($data->user->screen_name) ? 'https://twitter.com/'.$data->user->screen_name.'/status/'.$data->id_str : null,
						'url_target'      => '_blank',
						'title'           => null,
						'excerpt'         => isset($data->full_text) ? $this->tweet_text($data) : null,
						'terms'           => null,
						'author'          => array(
							'ID'     => isset($data->id_str) ? $data->id_str : null,
							'name'   => isset($data->user->screen_name) ? $data->user->screen_name : null,
							'url'    => isset($data->user->screen_name) ? 'https://twitter.com/'.$data->user->screen_name : null,
							'avatar' => isset($data->user->profile_image_url) ? str_replace('.jpg', '_200x200.jpg', str_replace('_normal', '', (string)$data->user->profile_image_url)) : null
						),
						'likes_number'    => null,//(isset($data->retweet_count)) ? $data->retweet_count : null,
						'likes_title'     => null,
						'comments_number' => null,
						'views_number'    => null,
						'image'           => $image,
						'gallery'         => null,
						'video'           => null,
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
		
}