<?php
/**
 * Contact Form
 * 
 * Displays a customizable contact form
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_contact' ) )
{
	class avia_sc_contact extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']		= __('Contact Form', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-contact.png";
				$this->config['order']		= 43;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_contact';
				$this->config['shortcode_nested'] = array('av_contact_field');
				$this->config['tooltip'] 	= __('Creates a customizable contact form', 'avia_framework' );
				$this->config['preview'] 	= "large";
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
				$this->elements = apply_filters( 'avf_sc_contact_popup_elements',  array(
						
						array(
								"type" 	=> "tab_container", 'nodescription' => true
							),
							
						array(
								"type" 	=> "tab",
								"name"  => __("Form" , 'avia_framework'),
								'nodescription' => true
							),
						
						array(
						"name" 	=> __("Your email address", 'avia_framework' ),
						"desc" 	=> __("Enter one or more Email addresses (separated by comma) where mails should be delivered to.", 'avia_framework' ) ." (".__("Default:", 'avia_framework' ) ." ". get_option('admin_email').")",
						"id" 	=> "email",
						'container_class' =>"avia-element-fullwidth",
						"std" 	=> get_option('admin_email'),
						"type" 	=> "input"),
						
						array(
						"name" 	=> __("Form Title", 'avia_framework' ),
						"desc" 	=> __("Enter a form title that is displayed above the form", 'avia_framework' ),
						"id" 	=> "title",
						"std" 	=> __("Send us mail", 'avia_framework' ),
						"type" 	=> "input"),

						array(
							"name" => __("Add/Edit Contact Form Elements", 'avia_framework' ),
							"desc" => __("Here you can add, remove and edit the form Elements of your contact form.", 'avia_framework' )."<br/>".
									  __("Available form elements are: single line Input elements, Textareas, Checkboxes and Select-Dropdown menus.", 'avia_framework' )."<br/><br/>".
									  __("It is recommended to not delete the 'E-Mail' field if you want to use an auto responder.", 'avia_framework' ),

							"type" 			=> "modal_group",
							"id" 			=> "content",
							"modal_title" 	=> __("Edit Form Element", 'avia_framework' ),
							"std"			=> array(

													array('label'=>__('Name', 'avia_framework' ), 'type'=>'text', 'check'=>'is_empty'),
													array('label'=>__('E-Mail', 'avia_framework' ), 'type'=>'text', 'check'=>'is_email'),
													array('label'=>__('Subject', 'avia_framework' ), 'type'=>'text', 'check'=>'is_empty'),
													array('label'=>__('Message', 'avia_framework' ), 'type'=>'textarea', 'check'=>'is_empty'),

													),


							'subelements' 	=> array(

									array(
									"name" 	=> __("Form Element Label", 'avia_framework' ),
									"desc" 	=> "",
									"id" 	=> "label",
									"std" 	=> "",
									"type" 	=> "input"),


							        array(
									"name" 	=> __("Form Element Type", 'avia_framework' ),
									"desc" 	=> "",
									"id" 	=> "type",
									"type" 	=> "select",
									"std" 	=> "text",
									"no_first"=>true,
									"subtype" => array(	__('Form Element: Text Input', 'avia_framework' ) =>'text',
														__('Form Element: Text Area', 'avia_framework' ) =>'textarea',
														__('Form Element: Select Element', 'avia_framework' ) =>'select',
														__('Form Element: Checkbox', 'avia_framework' ) =>'checkbox',
														__('Form Element: Datepicker', 'avia_framework' ) =>'datepicker',
														__('Custom HTML: Add a Description', 'avia_framework' ) =>'html',
														)),

									array(
										"name" 	=> __("Form Element Options", 'avia_framework' ) ,
										"desc" 	=> __("Enter any number of options that the visitor can choose from. Separate these Options with a comma.", 'avia_framework' ) ."<br/><small>".
												   __("Example: Option 1, Option 2, Option 3", 'avia_framework' )."</small>"."<br/><small>".
												   __("Note: If you want to use a comma in the option text you have to write 2 comma.", 'avia_framework' )."</small>" ,

										"id" 	=> "options",
										"required" => array('type','equals','select'),
										"std" 	=> "",
										"type" 	=> "input"),
										
									
										 array(	
										"name" 	=> __("Multiple answers", 'avia_framework' ),
										"desc" 	=> __("Check if you want to enable multiple answers", 'avia_framework' ) ,
										"id" 	=> "multi_select",
										"required" => array('type','equals','select'),
										"std" 	=> "",
										"type" 	=> "checkbox"),	
										
									array(	
										"name" 	=> __("Preselect checkbox", 'avia_framework' ),
										"desc" 	=> __("Check if you want to preselect the checkbox", 'avia_framework' ) ,
										"id" 	=> "av_contact_preselect",
										"required" => array('type','equals','checkbox'),
										"std" 	=> "",
										"type" 	=> "checkbox"),	
										
									array(
										"name" 	=> __("Add Description", 'avia_framework' ) ,
										"id" 	=> "content",
										"required" => array('type','equals','html'),
										"std" 	=> "",
										"type" 	=> "tiny_mce"),
									
									
								    array(
									"name" 	=> __("Form Element Validation", 'avia_framework' ),
									"desc" 	=> "",
									"id" 	=> "check",
									"type" 	=> "select",
									"std" 	=> "",
									"no_first"=>true,
									"required" => array('type','not','html'),
									"subtype" => array(	__('No Validation', 'avia_framework' ) =>'',
														__('Is not empty', 'avia_framework' ) =>'is_empty',
														__('Valid E-Mail address', 'avia_framework' ) =>'is_email',
														__('Valid Phone Number', 'avia_framework' ) =>'is_phone',
														__('Valid Number', 'avia_framework' ) =>'is_number')),

									 array(
									"name" 	=> __("Form Element Width", 'avia_framework' ),
									"desc" 	=> __("Change the width of your elements and let them appear beside each other instead of underneath", 'avia_framework' ) ,
									"id" 	=> "width",
									"type" 	=> "select",
									"std" 	=> "",
									"no_first"=>true,
									"required" => array('type','not','html'),
									"subtype" => array(	"Fullwidth" =>'', "1/2" =>'element_half', "1/3" =>'element_third' , "2/3" =>'element_two_third', "1/4" => 'element_fourth', "3/4" => 'element_three_fourth')),

						)
					),

						array(
						"name" 	=> __("Submit Button Label", 'avia_framework' ),
						"desc" 	=> __("Enter the submit buttons label text here", 'avia_framework' ),
						"id" 	=> "button",
						"std" 	=> __("Submit", 'avia_framework' ),
						"type" 	=> "input"),
						
						
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
						"std" 	=> __("Your message has been sent!", 'avia_framework' ),
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
						"name" 	=> __("E-Mail Subject", 'avia_framework' ),
						"desc" 	=> __("You can define a custom Email Subject for your form here. If left empty the subject will be", 'avia_framework' ).": <small>".__("New Message", 'avia_framework') . " (".__('sent by contact form at','avia_framework')." ".get_option('blogname').")</small>" ,
						"id" 	=> "subject",
						"std" 	=> "",
						"type" 	=> "input"),
						
		 				array(
							"name" 	=> __("Autorespond Text", 'avia_framework' ),
							"desc" 	=> __("Enter a message that will be sent to the users email address once he has submitted the form.", 'avia_framework' )."<br/><br/>".
									   __("If left empty no auto-response will be sent.", 'avia_framework' ),
							"id" 	=> "autorespond",
							"std" 	=> "",
							"type" 	=> "textarea"
							),

						array(
							"name" 	=> __("Contact Form Captcha", 'avia_framework' ),
							"desc" 	=> __("Do you want to display a Captcha field at the end of the form so users must prove they are human by solving a simply mathematical question?", 'avia_framework' )."</br></br>". 										   __("(It is recommended to only activate this if you receive spam from your contact form, since an invisible spam protection is also implemented that should filter most spam messages by robots anyway)", 'avia_framework' ),
							"id" 	=> "captcha",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array(__("Don't display Captcha", 'avia_framework' ) => '', __('Display Captcha', 'avia_framework' ) =>'active')
						),
						
						array(	
							"name" 	=> __("Hide Form Labels", 'avia_framework' ),
							"desc" 	=> __("Check if you want to hide form labels above the form elements. The form will instead try to use an inline label (not supported on old browsers)", 'avia_framework' ) ,
							"id" 	=> "hide_labels",
							"std" 	=> "",
							"type" 	=> "checkbox"),
							
						array(
	                    "name" 	=> __("Label/Send Button alignment", 'avia_framework' ),
	                    "desc" 	=> __("Select how to align the form labels and the send button", 'avia_framework' ),
	                    "id" 	=> "form_align",
	                    "type" 	=> "select",
	                    "std"	=> "",
	                    "subtype" => array(
	                        __('Default', 'avia_framework' ) 		=>'',
	                        __('Centered', 'avia_framework' ) 	=> 'centered'
	                    ),
	                    "std" 	=> ""),
						
						array(
							"type" 	=> "close_div",
							'nodescription' => true
						),
					
					array(
							"type" 	=> "tab",
							"name"	=> __("Colors",'avia_framework' ),
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
				$template = $this->update_template("label", __("Element", 'avia_framework' ). ": {{label}}");

				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_title_container'>";
				$params['innerHtml'] .=	"<div ".$this->class_by_arguments('check' ,$params['args']).">";
				$params['innerHtml'] .= "<span {$template} >".__("Element", 'avia_framework' ). ": ".$params['args']['label']."</span>";
				$params['innerHtml'] .= "<span class='av-required-indicator'> *</span>";
				$params['innerHtml'] .=	"</div>";
				$params['innerHtml'] .=	"</div>";
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
				
				extract(AviaHelper::av_mobile_sizes($atts)); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
				
				$atts =  shortcode_atts(
							apply_filters( 'avf_sc_contact_default_atts', 
										array('email' 		=> get_option('admin_email'),
			                                 'button' 		=> __("Submit", 'avia_framework' ),
			                                 'autorespond' 	=> '',
			                                 'captcha' 		=> '',
			                                 'subject'		=> '',
			                                 'on_send'		=> '',
			                                 'link'			=> '',
			                                 'sent'			=> __("Your message has been sent!", 'avia_framework' ),
			                                 'title'		=> __("Send us mail", 'avia_framework' ),
			                                 'color'		=> "",
			                                 'hide_labels'	=> "",
			                                 'form_align'	=> ""

			                                 )), $atts, $this->config['shortcode']);
				extract($atts);

				$post_id  = function_exists('avia_get_the_id') ? avia_get_the_id() : get_the_ID();
				$redirect = !empty($on_send) ? AviaHelper::get_url($link) : "";
				
				if(!empty($form_align)) $meta['el_class'] .= " av-centered-form ";
				
				$form_args = array(
					"heading" 				=> $title ? "<h3>".$title."</h3>" : "",
					"success" 				=> "<h3 class='avia-form-success'>".$sent."</h3>",
					"submit"  				=> $button,
					"myemail" 				=> $email,
					"action"  				=> get_permalink($post_id),
					"myblogname" 			=> get_option('blogname'),
					"autoresponder" 		=> $autorespond,
					"autoresponder_subject" => __('Thank you for your Message!','avia_framework' ),
					"autoresponder_email" 	=> $email,
					"subject"				=> $subject,
					"form_class" 			=> $meta['el_class']." ".$color." ".$av_display_classes,
					"multiform"  			=> true, //allows creation of multiple forms without id collision
					"label_first"  			=> true,
					"redirect"				=> $redirect,
					"placeholder"			=> $hide_labels,
					"numeric_names"			=> true,
				);
				
				if(trim($form_args['myemail']) == '') $form_args['myemail'] = get_option('admin_email');


				$content = str_replace("\,", "&#44;", $content );
				
				//form fields passed by the user
				$form_fields = $this->helper_array2form_fields(ShortcodeHelper::shortcode2array($content, 1));

				//fake username field that is not visible. if the field has a value a spam bot tried to send the form
				$elements['avia_username']  = array('type'=>'decoy', 'label'=>'', 'check'=>'must_empty');

				//captcha field for the user to verify that he is real
				if($captcha)
				$elements['avia_age'] =	array('type'=>'captcha', 'check'=>'captcha', 'label'=> __('Please prove that you are human by solving the equation','avia_framework' ));

				//merge all fields
				$form_fields = apply_filters('avia_contact_form_elements', array_merge($form_fields, $elements));
				$form_fields = apply_filters('avf_sc_contact_form_elements', $form_fields, $atts );
				$form_args   = apply_filters('avia_contact_form_args', $form_args, $post_id);

				$contact_form = new avia_form($form_args);
				$contact_form->create_elements($form_fields);
				$output = $contact_form->display_form(true);


				return $output;
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
                        			$sanizited_id = trim(strtolower($field['attr']['label']));

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
