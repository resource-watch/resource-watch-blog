<?php
/**
 * a very small and basic toolset for the for mailchimp 3.0 API, inspired by the mailchimp-for-wp-plugins 2.0 API
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'av_mailchimp_api' ) )
{
	class av_mailchimp_api
	{
		
		/**
		* @var string
	 	*/
	 	private $api_version = '3.0';
	 	
	 	
		/**
		* @var string
	 	*/
	 	private $api_url = 'api.mailchimp.com';	
	 	
	 	
	 	/**
		* Can be delted once the 3.0 version of the API properly returns the email field when getting the list merge-values
		* @var string
	 	*/
	 	private $api_old = '';	
	 	
	 	
	 	/**
		 * @var string
	 	*/
	    private $api_key = '';
	    
	    /**
		 * @var array
	 	*/
	    private $msg = array();
	    
	    /**
		 * @var array
	 	*/
	    public $lists = array();
	    
	    
	    /**
		 * @var array
	 	*/
	    public $fields = array();
	    
	    
	    public function __construct( $api_key ) 
	    {
		    $server = "";
			$this->api_key = $api_key;
			$dash_position = strpos( $api_key, '-' );
			
			if( $dash_position !== false ) 
			{
				$server =  substr( $api_key, $dash_position + 1 ). ".";
			}
			
			$this->api_old = 'https://' . $server . $this->api_url ."/2.0/";
			$this->api_url = 'https://' . $server . $this->api_url ."/" . $this->api_version . "/";
			
			
			$this->msg = apply_filters( 'avf_mailchimp_messages' , array(
				'email' 		=> __('Please provide a valid email address.', 'avia_framework'),
				'all' 			=> __('Please fill in all required fields.', 'avia_framework'),
				'already' 		=> __('This email address is already subscribed, thank you!', 'avia_framework'),
				'general' 		=> __('We are very sorry but something went wrong. Please try again later.', 'avia_framework'),
				'invalid_field' => __('Please make sure that your fields are filled in correctly', 'avia_framework'),
				
			));
			
		}
		
		
		public function get( $url = "" , $data = array())
		{
			$url = $this->api_url . $url ;
		
			$response = wp_remote_get( $url, array(
					'body' 		=> $data,
					'timeout' 	=> 20,
					'headers' 	=> $this->get_headers(),
					'sslverify'	=> false 
					// disable ssl verify: https://kriesi.at/support/topic/mailchimp-api-not-connecting/page/2/#post-596342
				)
			);
			
			$response = $this->check_response( $response );
		
			return $response;
		}
		
		public function post( $url = "" , $data = array() , $file_extension = "" )
		{
			if($file_extension)
			{	//can be removed once v3 merge fields work properly
				$data['apikey'] = $this->api_key;
				$url = $this->api_old . $url . $file_extension;
			}
			else
			{
				$url = $this->api_url . $url;
				$data = json_encode($data);
			}
			
			$response = wp_remote_post( $url, array(
					'body' 		=> $data,
					'timeout' 	=> 20,
					'headers' 	=> $this->get_headers(),
					'sslverify'	=> false
				)
			);
			
			$response = $this->check_response( $response );
			
			return $response;

		}
		
		private function check_response( $response )
		{
			// test for wp errors
			if( is_wp_error( $response ) ) 
			{
				$this->show_error( 'HTTP Error: ' . $response->get_error_message() );
				return false;
			}
			
			$body = wp_remote_retrieve_body( $response );
			$response = json_decode( $body );

			// store response
			if( is_object( $response ) ) {
				$this->last_response = $response;
		
				if( isset( $response->error ) ) {
					$this->error_message = $response->error;
				}
		
				if( isset( $response->code ) ) {
					$this->error_code = (int) $response->code;
				}
		
			}
		
			if( is_null( $response ) ) {
				return false;
			}
			
			return $response;
		}
		
		
		public function api_owner()
		{
			$owner 		= false;
			$response 	= $this->get();
			
			if( isset($response->account_name) ) 
			{
				$owner = $response->account_name;
				update_option('av_mailchimp_owner', $owner);
			}
			else
			{
				delete_option('av_mailchimp_owner');
			}
			
			return $owner;
		}
		
		
		public function get_list_ids($force_refresh = false)
		{
			if(empty($this->lists) || $force_refresh)
			{
				$response = $this->get('lists?count=200&fields=lists.name,lists.id,lists.stats');
				$results  = array();
				
				delete_option('av_chimplist');
				
				if(!empty( $response->lists ))
				{
					foreach( $response->lists as $list)
					{
						$results[ $list->id ] = array( 'name' => $list->name, 'stats' => $list->stats );
					}
					
					$this->lists = $results;
					update_option('av_chimplist' , $this->lists);
				}
				else if(!empty( $response->title ))
				{
					return $response->title;
				}
			}
			else
			{
				$results = $this->lists;
			}
			
			return $results;
		}
		
		
		 //once v3 merge field work use this
		public function get_list_fields_new( $list_id = "")
		{
			$response = $this->get( "lists/{$list_id}/merge-fields");
			
			$results  	= array();
			
			if(!empty( $response->merge_fields ))
			{
				foreach( $response->merge_fields as $field)
				{
					unset($field->_links);
					$results[ $field->merge_id ] = $field;
				}
				
				$this->fields[ $list_id ] = $results;
			}
			else if(!empty( $response->title ))
			{
				return $response->title;
			}
			
			return $results;
		}
		
		
		//once 3.0 works fine remove this function and rename get_list_fields_new to get_list_fields
		public function get_list_fields( $list_id = "")
		{
			$response 	= $this->post( 'lists/merge-vars', array('id' => array( $list_id ) ) , '.json');
			$results  	= array();
			
			if(!empty( $response->data[0] ) && $response->data[0]->merge_vars)
			{
				foreach( $response->data[0]->merge_vars as $field)
				{
					//convert return values to version 3.0 values
					$field->merge_id = $field->id;
					$field->required = $field->req;
					$field->type = $field->field_type;
					$field->display_order = $field->order;
					$field->default_value = empty($field->default) ? "" : $field->default;
					if(isset($field->choices))
					{
						$field->options = new stdClass();
						$field->options->choices = $field->choices;
					}
					
					unset($field->id, $field->req, $field->field_type, $field->order, $field->default, $field->choices);
					$results[ $field->merge_id ] = $field;
					
					$this->fields[ $list_id ] = $results;
				}
			}
			else if(!empty( $response->title ))
			{
				return $response->title;
			}
			
			return $results;
		}
		
		
		private function get_headers() 
		{
			$headers = array(
				'Accept' 		=> 'application/json',
				'Authorization' => 'apikey ' . $this->api_key
			);
	
			// Copy Accept-Language from browser headers
			if( ! empty( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
				$headers['Accept-Language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			}
	
			return apply_filters( 'avf_mailchimp_headers', $headers );
		}
		
		
		private function show_error( $message ) 
		{
			if( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
				return false;
			}
	
			if( ! function_exists( 'add_settings_error' ) ) {
				return false;
			}
	
			add_settings_error( 'av_mailchimp_api', 'av_mailchimp_api-error', $message, 'error' );
			return true;
		}
		
		
		public function store_lists($update_owner = false)
		{
			//delete all older lists and owners in case some where deleted by the user
			delete_option('av_chimplist_field');
			
			if($update_owner) $this->owner();
			
			//create new ones
			$this->lists = $this->get_list_ids();
			
			foreach( $this->lists as $key => $name)
			{
				$fields[$key] = $this->get_list_fields( $key );
			}
			
			if(!empty($fields))
			{
				update_option('av_chimplist_field' , $fields);
			}
		}
		
		
		public function message( $key )
		{
			if(isset( $this->msg[$key] ))
			{
				return $this->msg[$key];
			}
		}
		
		static function backend_html($value = "", $ajax = true)
		{
			$owner = false;
			$response = array();
			$response_text  = __("Could not connect to Mailchimp with this API Key. Please try again with a different key",'avia_framework');
			$response_class = "av-notice-error";
			$trigger_global_save = false;
			$list_output_default  = __("You might also want to check your internet connection and make sure that mailchimp.com is available",'avia_framework');
			
			if($ajax)
			{
				if(!empty($value))
				{
					$api = new av_mailchimp_api($value);
					$owner = $api->api_owner();
				}
				
				if($owner)
				{
					$api->store_lists();
					$trigger_global_save = true;
					$response_class = "";
					$response_text  = __("We were able to connect to your Mailchimp account",'avia_framework');
					$response_text .= " ($owner)";
					
					$response_text .= " avia_trigger_save";
				}
			}
			else
			{
				$owner = get_option('av_mailchimp_owner');
				if($owner)
				{
					$response_class = "";
					$response_text  = __("Last time we checked we were able to connected to your Mailchimp account",'avia_framework');
					$response_text .= " ($owner)";
				}
			}
			
			if($owner)
			{
				$lists = get_option('av_chimplist');
				$list_fields = get_option('av_chimplist_field');
				
				$list_output_default = __("We were not able to find any Newsletter List on your Mailchimp that your visitors can subscribe to. Please create at least one in your Mailchimp back-end and refresh the list data here to use Mailchimp with this theme.",'avia_framework');
				
				if(!empty($list_fields))
				{
					$list_output = "";
					$list_output_default = "";
					
					$list_output .= "<div class='av-verification-cell'><strong>".__('Available Lists', 'avia_framework').":</strong></div>";
					
					foreach($list_fields as $key => $list_items)
					{
						$sub = $lists[$key]['stats']->member_count;
						
						$list_output .= "<div class='av-verification-cell av-verification-cell-heading'>";
						$list_output .= "<strong>{$lists[$key]['name']}</strong>";
						$list_output .= "<small class='av-verification-extra-data'> (".__('Subscriber', 'avia_framework').": {$sub})</small>";
						$list_output .= "<small class='av-verification-extra-data av-verification-extra-data-right'>(".__('ID', 'avia_framework').": {$key})</small>";
						$list_output .= "</div>";
						
						
						foreach($list_items as $key => $field)
						{
							$required = !empty($field->required) ? "<span class='av-verification-required'>*</span>" : "";
							$list_output .= "<div class='av-verification-cell av-verification-cell-sub'>";
							$list_output .= "{$field->name} {$required}";
							$list_output .= "<span class='av-verification-extra-data av-verification-extra-data-right'>{$field->type}</span>";
							$list_output .= "</div>";
						}
					}
					
					$list_output .= "<div class='av-verification-cell av-verification-cell-heading'><strong>".__('If you ever change the fields in your list please re-validate your API key to update the list data presented here.', 'avia_framework')."</strong></div>";
				}
				
			}

			$output  = "<div class='av-verification-response-wrapper'>";
			$output .= "<div class='av-text-notice {$response_class}'>";
			$output .= $response_text;
			$output .= "</div>";
			
			if(!empty($list_output) || !empty($list_output_default))
			{
				$output .= "<div class='av-verification-mailchimp-list'>";
				
				if(!empty($list_output))
				{
					$output .= $list_output;
				}
				
				if(!empty($list_output_default))
				{
					$output .= "<div class='av-verification-cell'>".$list_output_default."</div>";
				}
				
				$output .= "</div>";
			}
			
			
			$output .= "</div>";
			
			
			return $output;
		}
	}
}


if (!function_exists('av_mailchimp_check_ajax'))
{
	function av_mailchimp_check_ajax($value, $ajax = true, $js_callback = false)
	{
		
		return av_mailchimp_api::backend_html($value, $ajax);	
		
	}
}




