<?php
/**
 * Mailchimp signup form
 * 
 * Creates a mailschimp signup form
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_mailchimp' ) )
{
	class avia_sc_mailchimp extends aviaShortcodeTemplate
	{
			
			//mailchimp api key
			var $api_key = "";
			
			// form fields
			var $fields;
			
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->api_key = avia_get_option('mailchimp_api');
				
				$this->config['self_closing']	=	'no';
				
				$this->config['name']		= __('Mailchimp Signup', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-contact.png";
				$this->config['order']		= 10;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_mailchimp';
				$this->config['shortcode_nested'] = array('av_mailchimp_field');
				$this->config['tooltip'] 	= __('Creates a mailschimp signup form', 'avia_framework' );
				$this->config['preview'] 	= false;
			}

			/**
			 * Popup Elements
			 *
			 * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
			 * opens a modal window that allows to edit the element properties
			 *
			 * @return void
			 */
			function popup_elements()
			{
				$api = false;
				$owner = "";
				$lists = array();
				
				//load the api only when the popup gets opened
				if(!empty($_POST) && !empty($_POST['action']) && $_POST['action'] == "avia_ajax_av_mailchimp")
				{
					
					$api   = new av_mailchimp_api( $this->api_key );
					$owner = $api->api_owner();
					
					if(empty( $this->api_key ) || !$owner)
					{
						$this->elements = array(
							array(
								"name" 	=> __("No Mailchimp API key found",'avia_framework' ),
								"desc" 	=> __("Please enter a valid Mailchimp API key, otherwise we will not be able to retrieve the lists that your visitors may subscribe to.", 'avia_framework' ).
								'<br/><br/><a target="_blank" href="'.admin_url('admin.php?page=avia#goto_newsletter').'">'.__("You can enter your API key here",'avia_framework' )."</a>",
								"type" 	=> "heading",
								"description_class" => "av-builder-note av-error",
								)
							);
							
						return;
					}
					
					$api->store_lists();
					$lists = $api->get_list_ids();
					
					if(!empty( $this->api_key ) && ( empty( $lists ) || !is_array($lists) ))
					{
						$this->elements = array(
							array(
								"name" 	=> __("No Mailchimp Lists found",'avia_framework' ),
								"desc" 	=> __("We could not find any lists that your customers can subscribe to. Please check if the API key you have entered in your Enfold Theme Panel is valid and also check if you have at least one list created on mailchimp", 'avia_framework' ).
								'<br/><br/><a target="_blank" href="'.admin_url('admin.php?page=avia#goto_newsletter').'">'.__("Check API key here",'avia_framework' ).'</a> | <a target="_blank" href="https://login.mailchimp.com/">'.__("Go to Mailchimp",'avia_framework' )."</a>",
								"type" 	=> "heading",
								"description_class" => "av-builder-note av-error",
								)
							);
							
						return;
					}
				
					$newlist = array();
					foreach($lists as $key => $list_item)
					{
						$newlist[$list_item['name']] = $key;
					}
	
					$lists = $newlist;
					$first = array(__("Select a mailchimp list...","avia_framework") => '' );
					
					$lists = array_merge($first, $lists);
				
				}
				
				
				
				//default elements that gets loaded if 
				$this->elements = apply_filters( 'avf_sc_mailchimp_popup_elements',  array(
						
						array(
								"type" 	=> "tab_container", 'nodescription' => true
							),
							
						array(
								"type" 	=> "tab",
								"name"  => __("Form" , 'avia_framework'),
								'nodescription' => true
							),
							
						array(
							"name" 	=> __("Mailchimp active",'avia_framework' ),
							"desc" 	=> __("This installation is connected to the Mailchimp account: ", 'avia_framework' )."'".$owner."'<br/><br/>".
									   "<strong>".__("Please note:", 'avia_framework' )."</strong> ".
									   __("This element currently only supports basic list subscription with basic form fields (text and dropdowns). Please let us know if you would like to see more advanced features.", 'avia_framework' ),
							"type" 	=> "heading",
							"description_class" => "av-builder-note av-notice",
							),
						
						
						array(
	                    "name" 	=> __("Lists", 'avia_framework' ),
	                    "desc" 	=> __("Select the list that the user should be added to. The form will be build automatically based on the list that you have set up in mailchimp.", 'avia_framework' ),
	                    "id" 	=> "list",
	                    "type" 	=> "mailchimp_list",
	                    "std"	=> "",
	                    "subtype" 	=> $lists,
	                    "api" 		=> $api,
	                    "std" 	=> ""),
	                    
	                    array(
							"name" => __("Edit Contact Form Elements", 'avia_framework' ),
						"desc" => __("Once you have selected a list above the available form fields will be displayed here", 'avia_framework' )."<br/>".
								  "<br/><strong>".__("Please note:", 'avia_framework' )."</strong>".
								  "<ul>".
								  "<li>".__("You can only hide form fields that are not required", 'avia_framework' )."</li>".
								  "<li>".__("Currently only text and dropdown elements are supported properly", 'avia_framework' )."</li>".
								  "</ul>",
								  

							"type" 			=> "modal_group",
							"id" 			=> "content",
							"modal_title" 	=> __("Edit Form Element", 'avia_framework' ),
							"disable_manual"=> true,
							"class"			=> "av-automated-inserts",
							"std"			=> array(),

							'subelements' 	=> array(
									
									array( 
				                    "id"    => 'id',
				                    "std"   => '',
				                    "type"  => "hidden"),
									
									array( 
				                    "id"    => 'type',
				                    "std"   => '',
				                    "type"  => "hidden"),
									
									array( 
				                    "id"    => 'check',
				                    "std"   => '',
				                    "type"  => "hidden"),
				                    
				                    array( 
				                    "id"    => 'options',
				                    "std"   => '',
				                    "type"  => "hidden"),
									
									array(
			                        "name" 	=> __("Form Element hidden", 'avia_framework' ),
			                        "desc" 	=> __("Check if you want to hide this form element", 'avia_framework' ),
			                        "id" 	=> "disabled",
			                        "type" 	=> "checkbox",
			                        "std"	=> "",
									"required" => array('check','equals',''),
				                     ),
				                     
									array(
									"name" 	=> __("Form Element Label", 'avia_framework' ),
									"desc" 	=> "",
									"id" 	=> "label",
									"std" 	=> "",
									"type" 	=> "input"),									

									 array(
									"name" 	=> __("Form Element Width", 'avia_framework' ),
									"desc" 	=> __("Change the width of your elements and let them appear beside each other instead of underneath", 'avia_framework' ) ,
									"id" 	=> "width",
									"type" 	=> "select",
									"std" 	=> "",
									"no_first"=>true,
									"subtype" => array(	"Fullwidth" =>'', "1/2" =>'element_half', "1/3" =>'element_third' , "2/3" =>'element_two_third', "1/4" => 'element_fourth', "3/4" => 'element_three_fourth')),

						)
					),
	                    
						
						array(	
							"name" 	=> __("Double opt-in?", 'avia_framework' ),
							"desc" 	=> __("Check if you want people to confirm their email address before being subscribed (highly recommended)", 'avia_framework' ) ,
							"id" 	=> "double_opt_in",
							"std" 	=> "true",
							"type" 	=> "checkbox"),
							
							
						array(
						"name" 	=> __("What should happen once the form gets sent?", 'avia_framework' ),
						"desc" 	=> "",
						"id" 	=> "on_send",
						"type" 	=> "select",
						"std" 	=> "text",
						"no_first"=>true,
						"subtype" => array(	__('Display a short message on the same page', 'avia_framework' ) =>'',
											__('Redirect the user to another page', 'avia_framework' ) =>'redirect',
											)),
						

						array(
						"name" 	=> __("Message Sent label", 'avia_framework' ),
						"desc" 	=> __("What should be displayed once the message is sent?", 'avia_framework' ),
						"id" 	=> "sent",
						"required" => array('on_send','not','redirect'),
						"std" 	=> __("Thank you for subscribing to our newsletter!", 'avia_framework' ),
						"type" 	=> "input"),
						
						
						array(
	                    "name" 	=> __("Redirect", 'avia_framework' ),
	                    "desc" 	=> __("To which page do you want the user send to?", 'avia_framework' ),
	                    "id" 	=> "link",
	                    "type" 	=> "linkpicker",
	                    "fetchTMPL"	=> true,
	                    "std"	=> "",
						"required" => array('on_send','equals','redirect'),
	                    "subtype" => array(
	                        __('Set Manually', 'avia_framework' ) =>'manually',
	                        __('Single Entry', 'avia_framework' ) =>'single'
	                    ),
	                    "std" 	=> ""),
	                    
	                    
	                    
						
						array(
							"type" 	=> "close_div",
							'nodescription' => true
						),
					
					array(
							"type" 	=> "tab",
							"name"	=> __("Form Styling",'avia_framework' ),
							'nodescription' => true
						),
					
					
					array(
							"name" 	=> __("Form Color Scheme", 'avia_framework' ),
							"desc" 	=> __("Select a form color scheme here", 'avia_framework' ),
							"id" 	=> "color",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array( __('Default', 'avia_framework' )=>'',
												__('Light transparent', 'avia_framework' )=>'av-custom-form-color av-light-form',
												__('Dark transparent', 'avia_framework' ) =>'av-custom-form-color av-dark-form'),
					),
					
					array(	
							"name" 	=> __("Hide Form Labels", 'avia_framework' ),
							"desc" 	=> __("Check if you want to hide form labels above the form elements. The form will instead try to use an inline label (not supported on old browsers)", 'avia_framework' ) ,
							"id" 	=> "hide_labels",
							"std" 	=> "",
							"type" 	=> "checkbox"),
					
							
					array(
							"type" 	=> "close_div",
							'nodescription' => true
						),
						
						array(
									"type" 	=> "tab",
									"name"	=> __("Screen Options",'avia_framework' ),
									'nodescription' => true
								),
								
								
								array(
								"name" 	=> __("Element Visibility",'avia_framework' ),
								"desc" 	=> __("Set the visibility for this element, based on the device screensize.", 'avia_framework' ),
								"type" 	=> "heading",
								"description_class" => "av-builder-note av-neutral",
								),
							
								array(	
										"desc" 	=> __("Hide on large screens (wider than 990px - eg: Desktop)", 'avia_framework'),
										"id" 	=> "av-desktop-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),
								
								array(	
									
										"desc" 	=> __("Hide on medium sized screens (between 768px and 989px - eg: Tablet Landscape)", 'avia_framework'),
										"id" 	=> "av-medium-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),
										
								array(	
									
										"desc" 	=> __("Hide on small screens (between 480px and 767px - eg: Tablet Portrait)", 'avia_framework'),
										"id" 	=> "av-small-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),
										
								array(	
									
										"desc" 	=> __("Hide on very small screens (smaller than 479px - eg: Smartphone Portrait)", 'avia_framework'),
										"id" 	=> "av-mini-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),
	
								
							array(
									"type" 	=> "close_div",
									'nodescription' => true
								),	
						
					array(
							"type" 	=> "close_div",
							'nodescription' => true
						),


				));
				
				
				

			}

			/**
			 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
			 * Works in the same way as Editor Element
			 * @param array $params this array holds the default values for $content and $args.
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_sub_element($params)
			{
				$template = $this->update_template("label", "<span class='av-mailchimp-el-label'>".__("Element", 'avia_framework' ). "</span><span class='av-mailchimp-btn-label'>".__("Button", 'avia_framework' ). "</span>: {{label}}");

				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_title_container'>";
				$params['innerHtml'] .=	"<div ".$this->class_by_arguments('check' ,$params['args']).">";
				$params['innerHtml'] .=	"<div ".$this->class_by_arguments('id' ,$params['args']).">";
				$params['innerHtml'] .=	"<div ".$this->class_by_arguments('disabled' ,$params['args']).">";
				$params['innerHtml'] .= "<span {$template} ><span class='av-mailchimp-el-label'>".__("Element", 'avia_framework' ). "</span><span class='av-mailchimp-btn-label'>".__("Button", 'avia_framework' ). "</span>: ".$params['args']['label']."</span>";
				$params['innerHtml'] .= "<span class='av-required-indicator'> *</span>";
				$params['innerHtml'] .= "</div>";
				$params['innerHtml'] .= "</div>";
				$params['innerHtml'] .= "</div>";
				$params['innerHtml'] .= "</div>";

				return $params;
			}



			/**
			 * Frontend Shortcode Handler
			 *
			 * @param array $atts array of attributes
			 * @param string $content text within enclosing form of shortcode element
			 * @param string $shortcodename the shortcode found, when == callback name
			 * @return string $output returns the modified html string
			 */
			function shortcode_handler($atts, $content = "", $shortcodename = "", $meta = "")
			{
				if(empty($this->api_key)) return;
				
				$lists 		= get_option('av_chimplist');
				$newlist 	= array();
			
				if(empty($lists))
				{
					return;
				}
				
				foreach($lists as $key => $list_item)
				{
					$newlist[$list_item['name']] = $key;
				}
				
				$lists = $newlist;
				
				extract(AviaHelper::av_mobile_sizes($atts)); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
				
				$atts =  shortcode_atts(
							apply_filters( 'avf_sc_mailchimp_atts', 
										array(
											'list' => "",
											'email' 		=> get_option('admin_email'),
											'button' 		=> __("Submit", 'avia_framework' ),
											'captcha' 		=> '',
											'subject'		=> '',
											'on_send'		=> '',
											'link'			=> '',
											'sent'			=> __("Thank you for subscribing to our newsletter!", 'avia_framework' ),
											'color'			=> "",
											'hide_labels'	=> "",
											'form_align'	=> "",
											 'listonly'		=> false, //if we should only use the list items or sub shortcodes
											 'double_opt_in'=> ""

			                                 )), $atts, $this->config['shortcode']);
				
				if( empty( $atts['list'] ) ) return;
				
				//extract form fields
				
				if($atts['listonly'])
				{
					$form_fields = $this->convert_fields_from_list( $atts['list'] );
				}
				else
				{
					$content = str_replace("\,", "&#44;", $content );
					$form_fields = $this->helper_array2form_fields(ShortcodeHelper::shortcode2array($content, 1));
				}
				
				if( empty( $form_fields ) ) return;
				
				extract($atts);

				$post_id  = function_exists('avia_get_the_id') ? avia_get_the_id() : get_the_ID();
				$redirect = !empty($on_send) ? AviaHelper::get_url($link) : "";
				
				if(!empty($form_align)) $meta['el_class'] .= " av-centered-form ";
				
				$form_args = array(
					"heading" 				=> "",
					"success" 				=> "<h3 class='avia-form-success avia-mailchimp-success'>".$sent."</h3>",
					"submit"  				=> $button,
					"myemail" 				=> $email,
					"action"  				=> get_permalink($post_id),
					"myblogname" 			=> get_option('blogname'),
					"subject"				=> $subject,
					"form_class" 			=> $meta['el_class']." ".$color." avia-mailchimp-form"." ".$av_display_classes,
					"form_data" 			=> array('av-custom-send'=>'mailchimp_send'),
					"multiform"  			=> true, //allows creation of multiple forms without id collision
					"label_first"  			=> true,
					"redirect"				=> $redirect,
					"placeholder"			=> $hide_labels,
					"mailchimp"				=> $atts['list'],
					"custom_send"			=> array($this, 'send'),
					"double_opt_in"			=> $atts['double_opt_in'],
			       
					
				);
				
				
				
				if(trim($form_args['myemail']) == '') $form_args['myemail'] = get_option('admin_email');


				$content = str_replace("\,", "&#44;", $content );

				//fake username field that is not visible. if the field has a value a spam bot tried to send the form
				$elements['avia_username']  = array('type'=>'decoy', 'label'=>'', 'check'=>'must_empty');

				//captcha field for the user to verify that he is real
				if($captcha)
				$elements['avia_age'] =	array('type'=>'captcha', 'check'=>'captcha', 'label'=> __('Please prove that you are human by solving the equation','avia_framework' ));

				//merge all fields
				$form_fields = apply_filters('ava_mailchimp_contact_form_elements', array_merge($form_fields, $elements));
				$form_fields = apply_filters('avf_sc_mailchimp_form_elements', $form_fields, $atts );
				$form_args   = apply_filters('avia_mailchimp_form_args', $form_args, $post_id);

				$contact_form = new avia_form($form_args);
				$contact_form->create_elements($form_fields);
				$output = $contact_form->display_form(true);

				
				return $output;
				
			}
			
			public function send( &$instance )
			{
				$params = $instance->form_params;
				
				if( isset( $_POST['avia_generated_form' . $params['avia_formID']] ) )
				{
					$form_suffix 	= '_' . $params['avia_formID'];
					$suffix_length	= (strlen($form_suffix) * -1);
					$merge_fields 	= array();
					$post_data 		= array();
					$mail			= "";
					$status			= !empty( $params['double_opt_in'] ) ? "pending" : "subscribed"; // subscribed // pending
					
					foreach($_POST as $key => $value)
					{
						$key = substr($key, 0, $suffix_length);
						$key = str_replace('avia_','',$key);
						
						if(isset($_POST['ajax_mailchimp']))
						{
							$value = urldecode($value);	
						}
						
						$post_data[ $key ] = $value;
						
					}
					
					//make sure that the username is not filled in, otherwise a bot has sent the form. if so simply fake the send event
					if(!empty($post_data['username']) ) return true;
					
					//iterate over form fields to generate the merge field data
					if( empty( $this->fields ) ) 
					{
						$all_fields 	= get_option('av_chimplist_field');
						$this->fields 	= $all_fields[$params['mailchimp']];
					}
					
					foreach ($this->fields as $field)
					{
						$value = !empty( $post_data[ $field->merge_id ] ) ? $post_data[ $field->merge_id ] : false;
						
						if($value !== false)
						{
							if($field->merge_id != 0)
							{
								$merge_fields[ $field->tag ] = $value;
							}
							else
							{
								$mail = $value;
							}
						}
					}
					
					$data_to_send = array(
						'email_address' => $mail,
						'status'		=> $status,
					);
					
					if( !empty( $merge_fields ) )
					{
						$data_to_send['merge_fields'] = $merge_fields;
					}
					
					$data_to_send 	= apply_filters( 'avf_mailchimp_subscriber_data' , $data_to_send , $this );
					$api  			= new av_mailchimp_api( $this->api_key );
					$this->add_user = $api->post( 'lists/'.$params['mailchimp'].'/members' ,$data_to_send); 
			
					//user was successfully added
					if( isset($this->add_user->id))
					{
						return true;
					}
					
					//if we got no id the user was not added which means we got an error. 
					$error_key = "general";
					
					if($this->add_user->title == "Invalid Resource")
					{
						$error_key = 'all';
						
						if( strpos($this->add_user->detail, 'email') !== false)
						{
							$error_key = 'email';
						}
						
						if( strpos($this->add_user->detail, 'merge fields') !== false)
						{
							$error_key = 'invalid_field';
						}
					}
					
					if($this->add_user->title == "Member Exists")
					{
						$error_key = 'already';
					}
					
					$instance->error_msg = "<div class='avia-mailchimp-ajax-error av-form-error-container'>". $api->message($error_key) ."</div>";
					
					
					add_action('wp_footer', array($this, 'print_js_error'), 2, 100000);
				}
				
				
				
				return false;
			}
			
			public function print_js_error()
			{
				echo "<script type='text/javascript'>";
				echo "var av_mailchimp_errors = ".json_encode( $this->add_user ).";";
				echo "if(console) console.log( 'Mailchimp Error:' , av_mailchimp_errors );";
				echo "</script>";
			}
			
			
			public function sort_elements($a, $b)
			{
				return $a['order'] - $b['order'];
			}


			/*helper function that converts the shortcode sub array into the format necessary for the contact form*/
			private function convert_fields_from_list( $key )
			{
				$all_fields 	= get_option('av_chimplist_field');
				$this->fields 	= $all_fields[$key];
				
				$converted 	= array();
				
				if(!empty($this->fields))
				{
					foreach ($this->fields as $field)
					{
						if($field->public == 1)
						{
							$required 	= $field->required;
							$options 	= isset( $field->options->choices ) ? $field->options->choices : "";
							$type	 	= "text";
							$check		= "";
							
							switch( $field->type )
							{
								case "dropdown": 	$type = "select"; break;
								case "date": 		$type = "datepicker"; break;
								case "radio": 		$type = "select"; break;
								case "number": 		if(!empty($required)){ $check = "is_number"; } break;
								case "email": 		$type = "text"; if(!empty($required)){ $check = "is_email";  } break;
								default: 			$type = 'text';
							}
							
							if( empty($check) )
							{
								$check = !empty($required) ? "is_empty" : "";
							}
							
							$converted[ $field->merge_id ] = array(
								
								'id' 		=> $field->merge_id,
								'label' 	=> $field->name,
								'type'  	=> $type,
								'check' 	=> $check,
								'options' 	=> $options,
								'order'		=> $field->display_order,
								'value'		=> $field->default_value
							);
						}
					}
				
					usort($converted, array( $this , 'sort_elements') );
				}
				
				return $converted;
			}
			
			/*helper function that converts the shortcode sub array into the format necessary for the contact form*/
			function helper_array2form_fields($base)
			{
				$form_fields = array();
                $labels = array();

				if(is_array($base))
				{
					foreach($base as $key => $field)
					{
						if(!empty($field['attr']['disabled']) && empty( $field['attr']['check'] ) && $field['attr']['type'] != "button" ) continue;
						
						switch( $field['attr']['type'] )
						{
							case "dropdown": 	$field['attr']['type'] = "select"; break;
							case "date": 		$field['attr']['type'] = "datepicker"; break;
							case "radio": 		$field['attr']['type'] = "select"; break;
							case "button": 		$field['attr']['type'] = "button"; break;
							case "number": 		$field['attr']['type'] = "number"; break;
							default: 			$field['attr']['type'] = 'text';
						}
					
            			$sanizited_id = $field['attr']['id'];

            			$labels[$sanizited_id] = empty($labels[$sanizited_id]) ? 1 : $labels[$sanizited_id] + 1;
            			if($labels[$sanizited_id] > 1) $sanizited_id = $sanizited_id . '_' . $labels[$sanizited_id];

						$form_fields[$sanizited_id] = $field['attr'];
						if(!empty($field['content'])) $form_fields[$sanizited_id]['content'] = ShortcodeHelper::avia_apply_autop($field['content']);
					}
				}

				return $form_fields;
			}

	}
}
