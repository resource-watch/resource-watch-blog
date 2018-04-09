<?php  if (  ! defined( 'AVIA_FW' ) ) exit( 'No direct script access allowed' );
/**
 * This file holds the avia_htmlhelper class which renders html elements based on the options passed.
 * Basically all backend html output for the option pages is defined within this file
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright ( c ) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */


/**
 * AVIA HTML HELPER
 *
 * This class receives an extended $avia_superobject file when called, which holds the information which page we are currently viewing
 * Based on that information it renders all form elements necessary for creating the option page.
 * Methods can also be called without looping over the $avia_superobject, but for html generating purposes in other parts of the theme
 * (for example meta boxes, widgets etc)
 * @package AviaFramework
 * 
 */
 
if( ! class_exists( 'avia_htmlhelper' ) )
{
	class avia_htmlhelper
	{
		/**
		 * This object holds the $avia_superobject with all the previously stored informations like theme/plugin data, options data, default values etc
		 * @var obj
		 */
		var $avia_superobject;
		
		/**
		 * This object holds the avia_database_set controller methods to check if an item is grouped or not
		 * @var obj
		 */
		var $set;
		
		/**
		 * Different behaviour for some methods based on the context (option_page/metabox)
		 * @var string
		 */
		var $context = 'options_page';
		
		/**
		 * Checks if a database entry with values is available and if so set to true to replace default values
		 * @var bool
		 */
		var $replace_default = array();
		
				
		######################################################################
		# Non rendering Functions
		######################################################################
		
				
		/**
         * Constructor
         *
         * The constructor sets up the superobject, if it was passed
         */
		function __construct($avia_superobject = false)
		{
		
			if(!$avia_superobject) { $avia_superobject = $GLOBALS['avia']; }
			$this->avia_superobject = $avia_superobject;
			
			$options = get_option($this->avia_superobject->option_prefix);
			
			
			//check which option pages were already saved yet and need replacement of the default values
			foreach($avia_superobject->option_pages as $page)
			{
				if(isset($options[$page['parent']]) && $options[$page['parent']] != "")
				{
					$this->replace_default[$page['slug']] = true;
				}
			}
		}
		
		

		function get_page_elements($slug)
		{
			$page_elements = array();
			if(isset($this->avia_superobject->option_page_data))
			{
				foreach($this->avia_superobject->option_page_data as $key => $value)
				{
					if($value['slug'] == $slug)
					{
						$page_elements[$key] = $value;
					}
				}
			}
	
			return $page_elements;
		}
		
		
		
		
		
		######################################################################
		# Rendering Functions
		######################################################################

		
		function create_container_based_on_slug($option_page, $firstClass = '')
		{
			$output = "";
		
			//get all elements of the current page and save them to the page elements array
			$page_elements = $this->get_page_elements($option_page['slug']);
		
			//subpage heading
			$output .= $this->render_page_container($option_page, $firstClass);
			
			//remove button if available:
			if(isset($option_page['removable'])) 
			{
				$output .= "<a href='#".$option_page['slug']."' title='".$option_page['removable']."' class='avia_remove_dynamic_page'>".$option_page['removable']."</a>";
			}
			
			//page elements
			foreach($page_elements as $key=>$element)
			{	
				$output .= $this->render_single_element($element);
			}
			
			$output .= $this->render_page_container_end();
			
			
			return $output;
		}
		
		
		/**
         * render_single_element
         *
         * The function renders a single option-section which means it creates the divs arround the form element, as well as descripio, adds values and sets ids
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
         
		function render_single_element( $element )
		{

			if( method_exists( $this, $element['type'] ) || (isset($element['use_function']) && function_exists($element['type'])))
			{
				//init indices that are not set yet to prevent php notice
				if( !isset( $element['id'] ) ) 	  { $element['id'] = ""; 	}
				if( !isset( $element['desc']  ) ) { $element['desc']  = ""; }
				if( !isset( $element['name']  ) ) { $element['name']  = ""; }
				if( !isset( $element['label'] ) ) { $element['label'] = ""; }
				if( !isset( $element['std'] ) )   { $element['std'] = ""; }
				if( !isset( $element['class'] ) ) { $element['class'] = ""; } 
				if( !isset( $element['dynamic'] ) ) { $element['dynamic'] = false; } 
				
				
				if($this->context != 'metabox')
				{
					if(isset($this->avia_superobject->page_slug) && 
					   isset($this->replace_default[$element['slug']]) && 
					   isset($this->avia_superobject->options[$this->avia_superobject->page_slug][$element['id']]))
					{
						$element['std'] = $this->avia_superobject->options[$this->avia_superobject->page_slug][$element['id']];
					}
				}
							
				//start rendering
				$output = "";
					
				
				//check if its a dynamic (sortable) element	
				$dynamic_end = "";
				
				if($element['dynamic'])
				{
					$output .= '<div class="avia_row">';
					$output .= '	<div class="avia_style_wrap avia_style_wrap_portlet">';
					$output .= '		<div class="avia-row-portlet-header">'.$element['name'].'<a href="#" class="avia-item-edit">+</a></div>';
					$output .= '		<div class="avia-portlet-content">';
					$output .= '		<span class="avia_clone_loading avia_removable_element_loading avia_hidden">Loading</span>';
					$output .= "		<a href='#".$element['id']."' title='".$element['removable']."' class='avia_remove_dynamic_element'><span>".$element['removable']."</span></a>";
					$dynamic_end = '<div class="avia_clear"></div></div></div></div>';
				}				
					
					
					
				//check if we should only render the element itself or description as well
				if($element['type'] == 'group' || (isset($element['nodescription']) && $element['nodescription'] != ""))
				{
					if(isset($element['use_function']))
					{
						$output .= $element['type']( $element );
					}
					else
					{
						$output .= $this->{$element['type']}( $element );
					}
				}
				else
				{
					$output .= $this->section_start( $element );
					if(isset($element['removable']) && !isset($element['dynamic'])) 
					{
						$output .= '		<span class="avia_clone_loading avia_removable_element_loading avia_hidden">Loading</span>';
						$output .= "		<a href='#".$element['id']."' title='".$element['removable']."' class='avia_remove_dynamic_element'><span>".$element['removable']."</span></a>";
					}
					
					$output .= $this->description( $element );
					if(isset($element['use_function']))
					{
						$output .= $element['type']( $element );
					}
					else
					{
						$output .= $this->{$element['type']}( $element );
					}
					
					$output .= $this->section_end( $element );
				}
				$output .= $dynamic_end;
				return $output;
			}
		}
		
		
		/**
         * Creates a wrapper around a set of elements. This set can be cloned with javascript
         * @param array $element the array holds data like id, class and some js settings
         * @return string $output the string returned contains the html code generated within the method
         */
         
		function group( $element )
		{	

			
			$iterations = 1;
			$output = "";
			$real_id = $element['id'];
			
			if((isset($element['std']) && is_array($element['std'])) && !isset($element['ajax_request']))
			{
				$iterations = count($element['std']);
			}
			
			if(isset($element['ajax_request'])) $iterations = $element['ajax_request']; // ajax requests usually need only one element per default
			
		
			for ($i = 0; $i < $iterations; $i++)
			{
				if(!isset($element['linktext'])) $element['linktext'] = "add";
				if(!isset($element['deletetext'])) $element['deletetext'] = "remove";
	
				
				//start generating html output
				
				
				$element['id'] = $real_id.'-__-'.$i;
				$output   .= '<div class="avia_set '.$element['class'].'" id="avia_'.$element['id'].'">';
		
				$output   .= '<div class="avia_single_set">';
				
				$output	 .= $this->get_subelements($element, $i);
				
				$output  .= '	<span class="avia_clone_loading avia_hidden" href="#">Loading</span>';
				$output  .= '	<a class="avia_clone_set" href="#">'.$element['linktext'].'</a>';
				$output  .= '	<a class="avia_remove_set" href="#">'.$element['deletetext'].'</a>';
				$output  .= '</div>';
				$output  .= '</div>';
			}
			
			
			return $output;
		}
		
		/**
         * Creates the subelements for groups and specail objects like gallery upload
         * @param array $element the array holds data like id, class and some js settings
         * @return string $output the string returned contains the html code generated within the method
         */
		function get_subelements($element, $i = 1)
		{
			$output = "";
					
			foreach($element['subelements'] as $key => $subelement)
			{
				if(isset($element['std']) && is_array($element['std']) && isset($element['std'][$i][$subelement['id']]))
				{
					$subelement['std'] = $element['std'][$i][$subelement['id']];
				}
				
				if(isset($element['ajax_request']))
				{
					$subelement['ajax_request'] = $element['ajax_request'];
				}
				
				$subelement['subgroup_item'] = true;
				$subelement['id'] = $element['id']."-__-".$subelement['id'];
				
				if(isset($element['apply_all'])) $subelement['apply_all'] = $element['apply_all'];
				$output  .=      $this->render_single_element($subelement);
			}
			
			return $output;
		}
		
		

		
		/**
         * 
         * The text method renders the title and the page containing wrapper necessary for javascript sidebar tabs 
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function render_page_container( $pageinfo , $firstClass )
		{	
			if(!isset($pageinfo['sortable'])) $pageinfo['sortable'] = "";
			
			$output  = '<div class="avia_subpage_container '.$firstClass.' '.$pageinfo['sortable'].'" id="avia_'.avia_backend_safe_string($pageinfo['slug']).'">';	
			$output .= '	<div class="avia_section_header">';	
			$output .= '		<strong class="avia_page_title" style="background-Image:url('.AVIA_IMG_URL."icons/".$pageinfo['icon'].');">'; 
			$output .= 			$pageinfo['title'];
			$output .= '		</strong>'; 
			$output .= '	</div>'; 
			return $output;
		}
		
		/**
         * Closes the page container
         * @return string $output the string returned contains the html code generated within the method
         */
		function render_page_container_end()
		{
			$output = '</div>'; 
			return $output;
		}

		
		
		function verification_field( $element )
		{
			$callback 			= $element['ajax'];
			$js_callback 		= isset($element['js_callback']) ? $element['js_callback'] : "";
			$element['simple'] 	= true;
			$output  			= "";
			$ajax				= false;
			
			if(isset($element['button-relabel']) && !empty($element['std']))
			{
				$element['button-label'] = $element['button-relabel'];
			}
			
			$output .= '<span class="avia_style_wrap avia_verify_input avia_upload_style_wrap">';
			$output .= $this->text($element);
			$output .= '<a href="#" data-av-verification-callback="'.$callback.'" data-av-verification-callback-javascript="'.$js_callback.'" class="avia_button avia_verify_button" id="avia_check'.$element['id'].'">'.$element['button-label'].'</a>';
			$output .= '</span>';
			$output .= isset($element['help']) ? "<small>".$element['help']."</small>" : "";
			
			$output .= "<div class='av-verification-result'>";
			
			if($element['std'] != "")
			{
				$output .= str_replace('avia_trigger_save' ,"",$callback( $element['std'] , $ajax));
			}
			
			$output .= "</div>";
			
			return $output;
		}
		
		
		
		/**
         * 
         * The text method renders a single input type:text element
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function text( $element )
		{	
			$extraClass = "";
			if(isset($element['class_on_value']) && !empty($element['std'])) $extraClass = " ".$element['class_on_value'];
			
			$text = '<input type="text" class="'.$element['class'].$extraClass.'" value="'.$element['std'].'" id="'.$element['id'].'" name="'.$element['id'].'"/>';
			
			if(isset($element['simple'])) return $text;
			return '<span class="avia_style_wrap">'.$text.'</span>';
		}
		
		
		/**
         * 
         * The hidden method renders a single input type:hidden element
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function hidden( $element )
		{			
			$output  = '<div class="avia_section avia_hidden"><input type="hidden" value="'.$element['std'].'" id="'.$element['id'].'" name="'.$element['id'].'"/></div>';
			return $output;
		}
		
		
		/**
         * 
         * The checkbox method renders a single input type:checkbox element
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         * @todo: fix: checkboxes at metaboxes currently dont work
         */
  		function checkbox( $element )
		{	
			$checked = "";
			if( $element['std'] != "" && $element['std'] != "disabled" ) { $checked = 'checked = "checked"'; }
				
			$output   = '<input '.$checked.' type="checkbox" class="'.$element['class'].'" ';
			$output  .= 'value="'.$element['id'].'" id="'.$element['id'].'" name="'.$element['id'].'"/>';
			
			return $output;
		}
		
		
		/**
         * 
         * The radio method renders one or more input type:radio elements, based on the definition of the $elements array
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function radio( $element )
		{	
			$output = "";
			$counter = 1;
			foreach($element['buttons'] as $radiobutton)
			{	
				$checked = "";
				if( $element['std'] == $counter ) { $checked = 'checked = "checked"'; }
				
				$output  .= '<span class="avia_radio_wrap">';
				$output  .= '<input '.$checked.' type="radio" class="'.$element['class'].'" ';
				$output  .= 'value="'.$counter.'" id="'.$element['id'].$counter.'" name="'.$element['id'].'"/>';
				
				$output  .= '<label for="'.$element['id'].$counter.'">'.$radiobutton.'</label>';
				$output  .= '</span>';
				
				$counter++;
			}	
				
			return $output;
		}
		
		
		/**
         * 
         * The textarea method renders a single textarea element
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function textarea( $element )
		{	
			$output  = '<textarea rows="5" cols="30" class="'.$element['class'].'" id="'.$element['id'].'" name="'.$element['id'].'">';
			$output .= $element['std'].'</textarea>';
			return $output;
		}
		
		
		/**
         * 
         * The link_controller method renders a bunch of links that are able to set values for other page elements
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function link_controller( $element )
		{
			$output  = "";
			$output .= '<div class="'.$element['class'].'">';
			
			if(!empty($element['subtype']))
			{
				$counter = 0;
		
				foreach($element['subtype'] as $key=>$array)
				{
					$counter ++;
					$active = $style = $class = $data = "";
					if(isset($array[$element['id']]) && $array[$element['id']] == $element['std'] ) $active = " avia_link_controller_active";
					if(isset($array['style'])) { $style = " style='".$array['style']."' "; unset($array['style']); }
					if(isset($array['class'])) { $class = " ".$array['class']; unset($array['class']); }
					
					foreach($array as $datakey=> $datavalue)
					{
						$data .= "data-".$datakey."='".$datavalue."' ";
					}

					$output .= "<a href='#' ".$data." ".$style." class='avia_link_controller avia_link_controller_".$counter.$active.$class."'>".$key."</a>";
				}
			}
			
			$output .= '<input type="hidden" value="'.$element['std'].'" id="'.$element['id'].'" name="'.$element['id'].'"/>';
			
			$output .= "</div>";
			return $output;
		}
		

		
		/**
         * 
         * The upload method renders a single upload element so users can add their own pictures
         * the script first gets the id of a hidden post that should store the image. if no post is set it will create one
         * then we check if a basic url based upload should be used or a more sophisticated id based for slideshows and feauted images, which need
         * the images resized automatically
         *
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function upload( $element )
		{	
			global $post_ID;
			$output  = "";
			$gallery_mode = false;
			$id_generated = false;
			$image_url = $element['std'];
			if(empty($element['button-label'])) $element['button-label'] = "Upload";
			
			//get post id of the hidden post that stores the image
			if(!empty($element['attachment-prefix']))
			{
				if(empty($element['std']) && empty($element['no-attachment-id'])) 
				{
					$element['std'] = uniqid();
					$id_generated = true;
				}
				
				$gallery_mode = true;
				$postId = avia_media::get_custom_post($element['attachment-prefix'].$element['std']);
			}
			else
			{
				$postId = avia_media::get_custom_post($element['name']);
				if(is_numeric($element['std']))
				{
					$image_url = wp_get_attachment_image_src($element['std'], 'full');
					$image_url = $image_url[0];
				}
			}
			//switch between normal url upload and advanced image id upload
			$mode = $prevImg = "";
			
			//video or image, advanced or default upload?
			
				if(isset($element['subtype'])) 
				{
					$mode = ' avia_advanced_upload';
					
					if(!is_numeric($element['std']) && $element['std'] != '')
					{
						$prevImg = '<a href="#" class="avia_remove_image">×</a><img src="'.AVIA_IMG_URL.'icons/video.png" alt="" />';
					}
					else if($element['std'] != '')
					{
						$prevImg = '<a href="#" class="avia_remove_image">×</a>'.wp_get_attachment_image($element['std'], array(100,100));
					}
					
				}
				else
				{
					if(!preg_match('!\.jpg$|\.jpeg$|\.ico$|\.png$|\.gif$!', $image_url) && $image_url != "" )
					{
						$prevImg = '<a href="#" class="avia_remove_image">×</a><img src="'.AVIA_IMG_URL.'icons/video.png" alt="" />';
					}
					else if($image_url != '')
					{
						$prevImg = '<a href="#" class="avia_remove_image">×</a><img src="'.$image_url.'" alt="" />'; 
					}
				}
			
			if($gallery_mode)
			{
				$image_url_array = array();
				$attachments = get_children(array('post_parent' => $postId,
	                        'post_status' => 'inherit',
	                        'post_type' => 'attachment',
	                        'post_mime_type' => 'image',
	                        'order' => 'ASC',
	                        'orderby' => 'menu_order ID'));
	
	
				foreach($attachments as $key => $attachment) 
				{
					$image_url_array[] = avia_image_by_id($attachment->ID, array('width'=>80,'height'=>80));
				}
				
				$output  .= "<div class='avia_thumbnail_container'>";
				
				if(isset($image_url_array[0]))
				{
					foreach($image_url_array as $key => $img) 
					{
						$output  .= "<div class='avia_gallery_thumb'><div class='avia_gallery_thumb_inner'>".$img."</div></div>";
					}
					
					$output  .= "<div class='avia_clear'></div>";
				}
				$output  .= "</div>";
				
			}
			
			$data = "";
			$upload_class = "avia_uploader"; 
			global $wp_version;
			
			if(version_compare($wp_version, '3.5', '>=' ) && empty($element['force_old_media']) && empty($element['subtype']) ) //check if new media upload is enabled
			{
				$upload_class = "avia_uploader_35"; 
			
				if(empty($element['data']))
				{
					$element['data'] =  array(	'target' => $element['id'], 
												'title'  => $element['name'], 
												'type'   => 'image', 
												'button' => $element['label'],
												'class'  => 'media-frame av-media-frame-image-only' ,
												'frame'  => 'select',
												'state'	 => 'av_select_single_image',
												'fetch'  => 'url',
									);
				}
				
				foreach($element['data'] as $key=>$value)
				{
					if(is_array($value)) $value = implode(", ",$value);
					$data .= " data-$key='$value' ";
				}
			}
			
			
			$output .= '<div class="avia_upload_container avia_upload_container_'.$postId.$mode.'">';
			$output .= '	<span class="avia_style_wrap avia_upload_style_wrap">';
			

			$output .= '	<input type="text" class="avia_upload_input '.$element['class'].'" value="'.$element['std'].'" name="'.$element['id'].'" id="'.$element['id'].'" />';
			$output .= '	<a '.$data.' href="#'.$postId.'" class="avia_button '.$upload_class.'" title="'.$element['name'].'" id="avia_upload'.$element['id'].'">'.$element['button-label'].'</a>';
			$output .= '	</span>';
			$output .= '	<div class="avia_preview_pic" id="div_'.$element['id'].'">'.$prevImg.'</div>';
			$output .= '	<input class="avia_upload_insert_label" type="hidden" value="'.$element['label'].'" />';
			if($gallery_mode) $output .= '	<input class="avia_gallery_mode" type="hidden" value="'.$postId.'" />';
			$output .= '</div>';
				
			return $output;
		}
		
		/**
         * 
         * The upload gallery method renders a single upload element so users can add their own pictures and/or videos
         *
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function upload_gallery( $element )
		{
			//first gernerate the sub_item_output
			$sub_output = "";
			$iterations = 0;
			$real_id = $element['id'];
			
			if((isset($element['std']) && is_array($element['std'])) && !isset($element['ajax_request']))
			{
				if(!empty($element['std'][0]['slideshow_image']) || !empty($element['std'][0]['slideshow_video']))
				{
					$iterations = count($element['std']);
				}
			}
			
			$video_button = "Add external video by URL";
			if(isset($element['button_video'])) $video_button = $element['button_video']; // ajax requests usually need only one element per default
			if(isset($element['ajax_request'])) $iterations = $element['ajax_request']; // ajax requests usually need only one element per default
			
			for ($i = 0; $i < $iterations; $i++)
			{
				//start generating html output
				
				$element['id'] = $real_id.'-__-'.$i;
				
				$sub_output  .= '<div class="avia_set avia_row '.$element['class'].'" id="avia_'.$element['id'].'" >';
				$sub_output  .= 	'<div class="avia_single_set"><div class="avia_handle"></div>';
				$sub_output	 .= 		$this->get_subelements($element, $i);
				$sub_output  .= '		<a class="avia_remove_set remove_all_allowed" href="#">'.__('(remove)').'</a>';
				$sub_output  .= '		<a class="open_set" data-openset="'.__('Show').'" data-closedset="'.__('Hide').'" href="#">'.__('Show').'</a>';
				$sub_output  .= 	'</div>';
				$sub_output  .= '</div>';
			}
			
			//if this is an ajax request stop here
			if(isset($element['ajax_request'])) return $sub_output;
			
			
			global $post_ID;
			//if we want to retrieve the whole element and this is not an ajax call do the following as well:
			if(empty($element['button-label'])) $element['button-label'] = "Add Image to slideshow";
			$postId = $post_ID; //avia_media::get_custom_post($element['name']);
			$output = "";
			
			$output .= '<div class="avia_gallery_upload_container avia_gallery_upload_container'.$postId.' avia_delay_required">';
			$output .= '<div class="avia_sortable_gallery_container">';
			
			$output .= $sub_output; 

			$output .= '</div>';
			
			$output .= '<div class="button_bar">';
			$output .= '	<span class="avia_style_wrap avia_upload_style_wrap">';
				//generate the upload link
					$output .= '<a href="#" class="avia_button avia_gallery_uploader" title="'.$element['name'].'" id="avia_gallery_uploader '.$element['id'].'"';
					$output .= 'data-label="'.$element['label'].'" ';
					$output .= 'data-this-id="'.$element['id'].'" ';
					$output .= 'data-attach-to-post = "'.$postId.'" ';
					$output .= 'data-real-id="'.$real_id.'" ';
					$output .= '>'.$element['button-label'].'</a>';
				//end link
			$output .= '	</span>';
			
			if(!empty($video_button))
			{
			$output .= '	<span class="avia_style_wrap avia_upload_style_wrap">';
				//generate the upload link
					$output .= '<a href="#" class="avia_button avia_gallery_uploader" title="'.$element['name'].'" id="avia_gallery_uploader '.$element['id'].'"';
					$output .= 'data-label="'.$element['label'].'" data-video-insert = "avia_video_insert"';
					$output .= 'data-attach-to-post = "'.$postId.'" ';
					$output .= 'data-real-id="'.$real_id.'" ';
					$output .= 'data-this-id="'.$element['id'].'" ';
					$output .= '>'.$video_button.'</a>';
				//end link
			$output .= '	</span>';
			}
			
			//delete button
			$output .= '	<span class="avia_style_wrap avia_upload_style_wrap avia_delete_style_wrap">';
				//generate the upload link
					$output .= '<a href="#" class="avia_button avia_gallery_delete_all avia_button_grey" id="avia_gallery_delete_all"';
					$output .= '>Remove All</a>';
				//end link
			$output .= '	</span>';
			$output .= '</div>'; //end button bar
			
			
			
			$output .= '</div>';
			return $output;
		}
		
		
		
		//the gallery image is a helper to the upload_gallery method that displays a single image and enables you to change that image
		function gallery_image($element)
		{
			$prevImg = $extraClass = "";
			$real_id = explode('-__-', $element['id']);
			$real_id = $real_id[0];
			
			global $post_ID;
			if(empty($post_ID) && isset($element['apply_all'])) $post_ID = $element['apply_all'];
			
			if(!is_numeric($element['std']) || $element['std'] == '')
			{
				$prevImg = '<img src="'.AVIA_IMG_URL.'icons/video_insert_image.png" alt="" />';
				$extraClass = " avia_gallery_image_vid";
			}
			else if($element['std'] != '')
			{
				$prevImg = wp_get_attachment_image($element['std'], array(100,100));
				$extraClass = " avia_gallery_image_img";
			}
			
		
			$output ="";
			$output .=' <div class="avia_gallery_image'.$extraClass.'">';
			
				//generate the upload link
					$output .= '<a href="#" class="avia_gallery_uploader" title="'.$element['name'].'" id="avia_gallery_image '.$element['id'].'"';
					$output .= 'data-label="'.$element['label'].'" ';
					$output .= 'data-this-id="'.$element['id'].'" ';
					$output .= 'data-attach-to-post = "'.$post_ID.'" ';
					$output .= 'data-real-id="'.$real_id.'" ';
					$output .= 'data-overwrite="true" ';
					$output .= '>'.$prevImg.'</a>';
					$output .= '<input type="text" class="avia_gallery_image_value '.$element['class'].'" value="'.$element['std'].'" name="'.$element['id'].'" id="'.$element['id'].'" />';
				//end link
			
			$output .= '</div>';
			return $output;
		}
		
		
		
		/**
         * 
         * The text method renders a single input type:text element. If autodetect is set the color picker trys to get the color from an image upload element
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function colorpicker( $element )
		{	
			$autodetect = $autodetectClass = "";
			if(isset($element['autodetect']) && function_exists('gd_info'))
			{
				$autodetect = '<a id="avia_autodetect_'.$element['id'].'" class="avia_button avia_autodetect" href="#'.$element['autodetect'].'">Auto detection</a><span class="avia_loading"></span>';
				$autodetectClass = ' avia_auto_detector';
			}
			
				
			$output  = '<span class="avia_style_wrap avia_colorpicker_style_wrap'.$autodetectClass.'">';
			$output .= '<input type="text" class="avia_color_picker '.$element['class'].'" value="'.$element['std'].'" id="'.$element['id'].'" name="'.$element['id'].'"/>';
			$output .= '<span class="avia_color_picker_div"></span>'.$autodetect.'</span>';
			return $output;
		}


		/**
         * 
         * The file_upload method renders a single zip file upload/insert element: user can select a zip file and a custom function/class will be executed then
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		
		function file_upload($element)
		{
			# deny if user is no super admin
			$output = "";
			$cap = apply_filters('avf_file_upload_capability', 'update_plugins', $element);
			
			if(!current_user_can($cap)) 
			{
				return "<div class='av-error'><p>Using this feature is reserved for Super Admins</p><p>You unfortunately don't have the necessary permissions.</p></div>";
			}
			
			#check if its allowed on multisite
			if(is_multisite() && strpos(get_site_option('upload_filetypes'), $element['file_extension']) === false)
			{
				$file = strtoupper($element['file_extension']);
			
				return "<div class='av-error'><p>You are currently on a WordPress multisite installation and .{$file} file upload is disabled. <br/>Go to your <a href='".network_admin_url('settings.php')."'>Network settings page</a> and add the '{$file}' file extension to the list of allowed 'Upload file types'</p></div>";
			}
			
			if( !ini_get('allow_url_fopen') && !empty($element['fopen_check'])) 
			{
			   $output .= "<div class='av-error'><p>Your Server has disabled the 'allow_url_fopen' setting in your php.ini which might cause problems when uploading and processing files</p></div>";
			}
			

			
			# is user is alowed to extract files create the upload/insert button
			if(empty($element['data']))
			{
				$element['data'] =  array(	'target' => $element['id'], 
											'title'  => $element['title'], 
											'type'   => $element['file_type'], 
											'button' => $element['button'],
											'trigger' => $element['trigger'],
											'class'  => 'media-frame '
									);
			}
			
			$data = "";
			
			foreach($element['data'] as $key=>$value)
			{
				if(is_array($value)) $value = implode(", ",$value);
				$data .= " data-$key='$value' ";
			}
			
			
			$class 	 = 'avia_button avia-media-35 aviabuilder-file-upload avia-builder-file-insert '.$element['class'];
			$output .= '<span class="avia_style_wrap"><a href="#" class=" '.$class.'" '.$data.' title="'.esc_attr($element['title']).'">'.$element['title'].'</a></span>';
			$output .= '<span class="avia_loading avia_upload_loading"></span>';
			//$output .= $this->text($element);
			$output .= $this->hidden($element);
			
			$output .= apply_filters('avf_file_upload_extra', '', $element);
			
			
			return $output;
		}
		

		
		
		
		/**
         * 
         * The select method renders a single select element: it either lists custom values, all wordpress pages or all wordpress categories
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function select( $element )
		{	
			$base_url	 = "";
			$folder_data = "";
		
			if($element['subtype'] == 'page')
			{
				$select = 'Select page';
				$entries = get_pages('title_li=&orderby=name');
			}
			else if($element['subtype'] == 'post')
			{
				$select = 'Select post';
				$entries = get_posts('title_li=&orderby=name&numberposts=9999');
			}
			else if($element['subtype'] == 'cat')
			{
				$add_taxonomy = "";
				
				if(!empty($element['taxonomy'])) $add_taxonomy = "&taxonomy=".$element['taxonomy'];
			
				$select = 'Select category';
				$entries = get_categories('title_li=&orderby=name&hide_empty=0'.$add_taxonomy);
				
			}
			else
			{	
				$select = 'Select...';
				$entries = $element['subtype'];
				$add_entries = array();
				
				if(isset($element['folder']))
				{	
					$add_file_array = avia_backend_load_scripts_by_folder(AVIA_BASE.$element['folder']);
					$base_url = AVIA_BASE_URL;
					$folder_data = "data-baseurl='{$base_url}'";
					
					if(is_array($add_file_array))
					{
						foreach($add_file_array as $file)
						{
							$skip = false;
						
							if(!empty($element['exclude']))
							{
								foreach($element['exclude'] as $exclude) {
        							if (stripos($file,$exclude) !== false) $skip = true;
    							}
							}
							
							if(strpos($file, '.') !== 0 && $skip == false)
							{
								$add_entries[$element['folderlabel'].$file] = "{{AVIA_BASE_URL}}".$element['folder'].$file; 
							}
						}
					
					
						if(isset($element['group']))
						{
							$entries[$element['group']] = $add_entries;
						}
						else
						{
							$entries = array_merge($entries, $add_entries);
						}
					}
						
				}
			}
			
			
			//check for onchange function
			$onchange = "";
			if(isset($element['onchange'])) 
			{
				$onchange = " data-avia-onchange='".$element['onchange']."' ";
				$element['class'] .= " avia_onchange";
			}
			
			$multi = $multi_class = "";
			if(isset($element['multiple'])) 
			{
				$multi_class = " avia_multiple_select";
				$multi = 'multiple="multiple" size="'.$element['multiple'].'"';
				
				if(!empty($element['std']))
				{
					$element['std'] = explode(',', (string) $element['std']);
				}
			}
			
			$output  = '<span class="avia_style_wrap avia_select_style_wrap'.$multi_class.'"><span class="avia_select_unify">';
			$output .= '<select '.$folder_data.' '.$onchange.' '.$multi.' class="'.$element['class'].'" id="'. $element['id'] .'" name="'. $element['id'] . '"> ';
			
			
			if(!isset($element['no_first'])) { $output .= '<option value="">'.$select .'</option>  '; $fake_val = $select; }
			
			$real_entries = array();
			foreach ($entries as $key => $entry)
			{
				if(!is_array($entry))
				{
					$real_entries[$key] = $entry;
				}
				else
				{
					$real_entries['option_group_'.$key] = $key;
				
					foreach($entry as $subkey => $subentry)
					{
						$real_entries[$subkey] = $subentry;
					}
					
					$real_entries['close_option_group_'.$key] = "close";
				}
			}
			
			$entries = $real_entries;
			
			foreach ($entries as $key => $entry)
			{
				
				if($element['subtype'] == 'page' || $element['subtype'] == 'post')
				{
					$id = $entry->ID;
					$title = $entry->post_title;
				}
				else if($element['subtype'] == 'cat')
				{
					if(isset($entry->term_id))
					{
						$id = $entry->term_id;
						$title = $entry->name;
					}
				}
				else
				{
					$id = $entry;
					$title = $key;				
				}
			
				if(!empty($title) || (isset($title) && $title === 0))
				{
					if(!isset($fake_val)) $fake_val = $title;
					$selected = "";
					if ($element['std'] == $id || (is_array($element['std']) && in_array($id, $element['std']))) { $selected = "selected='selected'"; $fake_val = $title;}
					if($base_url &&  str_replace($base_url, '{{AVIA_BASE_URL}}', $element['std']) == $id) {$selected = "selected='selected'"; $fake_val = $title;}
					
					if(strpos ( $title , 'option_group_') === 0) 
					{
						$output .= "<optgroup label='". $id."'>";
					}
					else if(strpos ( $title , 'close_option_group_') === 0) 
					{
						$output .= "</optgroup>";
					}
					else
					{
						$output .= "<option $selected value='". $id."'>". $title."</option>";
					}
					
				}
			}
			$output .= '</select>';
			$output .= '<span class="avia_select_fake_val">'.$fake_val.'</span>';
			$output .= '</span></span>';
			
			if(isset($element['hook'])) $output.= '<input type="hidden" name="'.$element['hook'].'" value="'.$element['hook'].'" />';
			
			return $output;
		}
		
		
		function select_sidebar( $element )
		{
			$save_as = array();
			foreach($element['additions'] as $key => $additions)
			{
				if($additions == "%result%") 
				{
					$save_as = $key;
					unset($element['additions'][$key]);
				}
			}
			
			if(empty($save_as))
			{
				$element['subtype'] = av_backend_registered_sidebars($element['additions'] , $element['exclude']);
			}
			else
			{
				$element['subtype'] = $element['additions'];
				$element['subtype'][$save_as] = av_backend_registered_sidebars(array() , $element['exclude']);
			}
			
			return $this->select($element);
		}
		
		
		
		
		/**
         * 
         * The hidden method renders a div for a visually conected group
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function visual_group_start( $element )
		{	
			$required = $extraclass = $data= "";
			
			if(isset($element['required'])) 
			{ 
				$required = '<input type="hidden" value="'.$element['required'][0].'::'.$element['required'][1].'" class="avia_required" />';  
				$extraclass = ' avia_hidden avia_required_container';
			} 
			$data = "";
			if(isset($element['name'])) 		$data .= " data-group-name='".$element['name']."'";
			if(isset($element['global_class'])) $data .= " data-av_set_global_tab_active='".$element['global_class']."'";
			if(isset($element['inactive'])) { $data .= " data-group-inactive='".htmlspecialchars($element['inactive'], ENT_QUOTES)."'"; $extraclass .= " inactive_visible";}
			
		
			$output  = '<div class="avia_visual_set avia_'.$element['type'].$extraclass.' '.$element['class'].'" id="'.$element['id'].'" '.$data.'>';
			$output .= $required;
				
			return $output;
		}
		
		/**
         * 
         * The hidden method ends the div of the visual group
         * @return string $output the string returned contains the html code generated within the method
         */
		function visual_group_end()
		{			
			$output  = '</div>';
			return $output;
		}
		
		
		/**
         * 
         * The hidden method renders a single div element with class hr
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function hr( $element )
		{	
			$class = "";
			if(isset($element['class'])) $class = $element['class'];	
			
			$output  = '<div class="avia_hr '.$class.'"><div class="avia_inner_hr"></div></div>';
			return $output;
		}
		
		/**
         * 
         * The import method adds the option to import dummy content
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
  		function import( $element )
		{	
			$data 	= "";
			$extra 	= "";
			$activeClass = "";
			$button_class= "";
			$click_to_import = __("Click to import",'avia_framework');
			
			if(isset($element['files'])) $data = "data-files='".$element['files']."'";
			if(!empty($element['image'])) $extra = "av-import-with-image";
			
			if(isset($element['exists']))
			{
				$plugin = $element['exists'][0];
				if(!class_exists($plugin))
				{
					$extra .= " av-disable-import";
					$click_to_import = $element['exists'][1];
					$button_class .= "avia_button_inactive";
				}
			}
			
			
			$output  = "<div class='av-import-wrap {$extra}'>";
			$nonce	 = 	wp_create_nonce ('avia_nonce_import_dummy_data');
			$output .= '<input type="hidden" name="avia-nonce-import" value="'.$nonce.'" />';
			
			if(empty($element['image']))
			{
				$output .= '<span class="avia_style_wrap"><a href="#" class="avia_button avia_import_button" '.$data.'>Import dummy data</a></span>';
			}
			else
			{
				$output .= '<a href="#" class="avia_import_image avia_import_button '.$button_class.'" '.$data.'><div class="avia_import_overlay">'.$click_to_import.'</div><img src="'.AVIA_BASE_URL.$element['image'].'" alt="" title="" /></a>';
			}
			
			$output .= '<span class="avia_loading avia_import_loading"></span>';
			$output .= '<div class="avia_import_wait"><strong>Import started.</strong><br/>Please dont reload the page. You will be notified as soon as the import has finished! (Usually within a few minutes) :)</div>';
			$output .= '<div class="avia_import_result"></div>';
			$output .= "</div>";
			return $output;
		}
		
		/**
     *
     * The parent_setting_import method adds the option to import settings from your parent theme
     * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
     * @return string $output the string returned contains the html code generated within the method
     */
        function parent_setting_import($element)
        {

            $output = "";
            $nonce	 = 	wp_create_nonce ('avia_nonce_import_parent_settings');
            $output .= '<input type="hidden" name="avia-nonce-import-parent" value="'.$nonce.'" />';
            $output .= '<span class="avia_style_wrap"><a href="#" class="avia_button avia_import_parent_button">Import Parent Theme Settings</a></span>';
            $output .= '<span class="avia_loading avia_import_loading_parent"></span>';
            $output .= '<div class="avia_import_parent_wait"><strong>Import started.</strong><br/>Please wait a few seconds and dont reload the page. You will be notified as soon as the import has finished! :)</div>';
            $output .= '<div class="avia_import_result_parent"></div>';
            return $output;
        }



        /**
         *
         * The theme_settings_export method adds the option to export the theme settings
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
        function theme_settings_export($element)
        {
            $url = admin_url("admin.php?page=avia&avia_export=1&avia_generate_config_file=1");
            $output = "";
            $output .= '<span class="avia_style_wrap"><a href="'.$url.'" class="avia_button avia_theme_settings_export_button">'.__('Export Theme Settings File','avia_framework').'</a></span>';
            return $output;
        }


		/**
         * 
         * The heading method renders a fullwidth extra description or title
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function heading( $element )
		{	
			$extraclass = $required = "";	
			if(isset($element['required'])) 
			{ 
				$required = '<input type="hidden" value="'.$element['required'][0].'::'.$element['required'][1].'" class="avia_required" />';  
				$extraclass = ' avia_hidden avia_required_container';
			} 
			
			if(isset($element['class'])) $extraclass .= ' '.$element['class'];
		
			$output  = '<div class="avia_section avia_'.$element['type'].' '.$extraclass.'"  id="avia_'.$element['id'].'">';
			$output .= $required;
			if($element['name'] != "") $output .= '<h4>'.$element['name'].'</h4>';
			$output .= $element['desc'];
			$output .= '</div>';
			return $output;
		}
		
		
		
		/**
         * 
         * The target method renders a div that is able to hold an image or a background color
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function target( $element )
		{	
			$output  = $this->section_start( $element );
			$output .= '	<div><div class="avia_target_inside">';
			$output .= 		$element['std'];
			$output .= '	</div>';
			$output .= $this->section_end( $element );
			return $output;
		}
		
	
		
		/**
         * 
         * The section_start method renders the beginning of an option-section wich basically includes some wrapping divs and the elment name
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function section_start( $element )
		{
			$required = $extraclass = $target = "";
			
			if(isset($element['required'])) 
			{ 
				$required = '<input type="hidden" value="'.$element['required'][0].'::'.$element['required'][1].'" class="avia_required" />';  
				$extraclass = ' avia_hidden avia_required_container';
			} 
			
			if(isset($element['target'])) 
			{ 
				if(is_array($element['target']))
				{
					foreach($element['target'] as $value) { $target .= '<input type="hidden" value="'.$value.'" class="avia_target_value" />';  }
				}
				else
				{
					$target = '<input type="hidden" value="'.$element['target'].'" class="avia_target_value" />';  
				}
				
			} 
			if(isset($element['class'])) $extraclass .= ' '.$element['class'];
			
			$output  = '<div class="avia_section avia_'.$element['type'].$extraclass.'" id="avia_'.$element['id'].'">';
			$output .= $required;
			$output .= $target;
			if($element['name'] != "")
			{
				$output .= '<h4>'.$element['name'].'</h4>';
			}
			$output .= '<div class="avia_control_container">';

			return $output;
		}
		
		
		/**
         * 
         * The section_end method renders the end of an option-section by closing various divs
         * @return string $output the string returned contains the html code generated within the method
         */
		function section_end()
		{
			$output  = '</div>'; // <!--end avia_control-->
			$output .= '<div class="avia_clear"></div>';
			$output .= '</div>'; //<!--end avia_control_container-->
			$output .= '</div>'; //<!--end avia_section-->
			return $output;
		}
		
		
		/**
         * 
         * The description method renders the description of the current option-section
         * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
         * @return string $output the string returned contains the html code generated within the method
         */
		function description($element)
		{
			$tags = array('div','div');
		
			if($element['type'] == 'checkbox')
			{
				$tags = array('label for="'.$element['id'].'"','label');
			}
		
			$output  = "<{$tags[0]} class='avia_description'>";
			$output .= $element['desc'];
			$output .= "</{$tags[1]}>"; // <!--end avia_description-->
			$output .= '<div class="avia_control">';
			return $output;		
		}
		
		
		/**
         * 
         * The page_header method renders the beginning of the option page, with header area, logo and various other informations
         * @return string $output the string returned contains the html code generated within the method
         */
		function page_header()
		{
			$the_title = apply_filters( 'avia_filter_backend_page_title', $this->avia_superobject->base_data['Title'] );
			
			$output  = '<form id="avia_options_page" action="#" method="post">';
			$output .= '	<div class="avia_options_page_inner avia_sidebar_active">';
			$output .= '	<div class="avia_options_page_sidebar"><div class="avia_header">';
			$output .=      apply_filters('avia_options_page_header', "");
			$output .= '	</div><div class="avia_sidebar_content"></div></div>';
			$output .= '		<div class="avia_options_page_content">';
			$output .= '			<div class="avia_header">';
			$output .= '			<h2 class="avia_logo">'.$the_title.' '.$this->avia_superobject->currentpage.'</h2>';
			$output .= '				<ul class="avia_help_links">';
			$output .= '					<li><a class="thickbox" onclick="return false;" href="http://docs.kriesi.at/'.avia_backend_safe_string($this->avia_superobject->base_data['prefix']).'/changelog/index.php?TB_iframe=1">Changelog</a> |</li>';
			$output .= '					<li><a target="_blank" href="http://docs.kriesi.at/'.avia_backend_safe_string($this->avia_superobject->base_data['prefix']).'/documentation/">Docs</a></li>';
			$output .= '				</ul>';
			$output .= '				<a class="avia_shop_option_link" href="#">Show all Options [+]</a>';
			$output .= '				<span class="avia_loading"></span>';
			$output .= 					$this->save_button();
			$output .= '			</div>';
			$output .= '			<div class="avia_options_container">';
			
			return $output;
		}
		
		/**
         * 
         * The page_footer method renders the end of the option page by closing various divs and appending a save and a reset button
         * @return string $output the string returned contains the html code generated within the method
         */
		function page_footer()
		{
			$output  = '			</div>'; // <!-- end .avia_options_container -->
			$output .= '			<div class="avia_footer">';
			$output .=  			$this->hidden_data();
			$output .= '			<span class="avia_loading"></span>';
			$output .= '				<ul class="avia_footer_links">';
			$output .= '					<li class="avia_footer_reset">'.$this->reset_button().'</li>';
			$output .= '					<li class="avia_footer_save">'.$this->save_button().'</li>';
			$output .= '				</ul>';
			$output .= '			</div>';
			$output .= '		</div>'; // <!--end avia_options_page_content-->
			$output .= '		<div class="avia_clear"></div>';
			$output .= '	</div>'; //<!--end avia_options_page_inner-->
			$output .= '</form>'; // <!-- end #avia_options_page -->
			$output .= '<div class="avia_bottom_shadow"></div>';

			
			return $output;
		}
		
		

		/**
         * 
         * Creates a button to save the form via ajax
         * @return string $output the string returned contains the html code generated within the method
         */
		function save_button()
		{
			$output = '<span class="avia_style_wrap"><a href="#" class="avia_button avia_button_inactive avia_submit">Save all changes</a></span>';
			return $output;
		}

		
		
		/**
         * 
         * Creates a button to reset the form
         * @return string $output the string returned contains the html code generated within the method
         */
		function reset_button()
		{
			if(current_theme_supports('avia_disable_reset_options')) return "";
		
			$output = '<span class="avia_style_wrap"><a href="#" class="avia_button avia_button_grey avia_reset">Reset all options</a></span>';
			return $output;
		}
		
		
		/**
         * 
         * A important function that sets various necessary parameters within hidden input elements that the ajax script needs to save the current page
         * @return string $output the string returned contains the html code generated within the method
         */
		function hidden_data()
		{
			$options = get_option($this->avia_superobject->option_prefix);
			
			$output  = '	<div id="avia_hidden_data" class="avia_hidden">';
			
			$nonce_reset = 		wp_create_nonce ('avia_nonce_reset_backend');
			$output .= 			wp_referer_field( false );			
			$output .= '		<input type="hidden" name="avia-nonce-reset" value="'.$nonce_reset.'" />';
			$output .= '		<input type="hidden" name="resetaction" value="avia_ajax_reset_options_page" />';
			$output .= '		<input type="hidden" name="admin_ajax_url" value="'.admin_url("admin-ajax.php").'" />';
			$output .= '		<input type="hidden" name="avia_options_prefix" value="'.$this->avia_superobject->option_prefix.'" />';
			
			//if we are viewing a page and not a meta box
			if($this->context == 'options_page')
			{
				$nonce	= 			wp_create_nonce ('avia_nonce_save_backend');
			    $output .= '		<input type="hidden" name="avia-nonce" value="'.$nonce.'" />';
				$output .= '		<input type="hidden" name="action" value="avia_ajax_save_options_page" />';
				$output .= '		<input type="hidden" name="avia_options_page_slug" value="'.$this->avia_superobject->page_slug.'" />';
				if(empty($options)) $output .= ' <input type="hidden" name="avia_options_first_call" value="true" />';
			}
			//if the code was rendered for a meta box
			if($this->context == 'metabox')
			{
				$nonce	= 			wp_create_nonce ('avia_nonce_save_metabox');
			    $output .= '		<input type="hidden" name="avia-nonce" value="'.$nonce.'" />';
				$output .= '		<input type="hidden" name="meta_active" value="true" />';
			}
			
			
			
			
			$output .= '	</div>';
			
			return $output;
		}
		
		
		
	######################################################################
	# Dynamic Template Creation:
	######################################################################
	
	/**
     * 
     * The create_options_page method renders an input field and button that lets you create options pages dynamically by entering the new of the new option page
     * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
     * @return string $output the string returned contains the html code generated within the method
     */

	function create_options_page($element)
	{
		$output = "";
		
		$output .= '<div class="avia_create_options_container">';
		$output .= '	<span class="avia_style_wrap avia_create_options_style_wrap">';
		$output .= '	<input type="text" class="avia_create_options_page_new_name avia_dont_activate_save_buttons'.$element['class'].'" value="" name="'.$element['id'].'" id="'.$element['id'].'" />';
		$output .= '	<a href="#" class="avia_button avia_create_options avia_button_inactive" title="'.$element['name'].'" id="avia_'.$element['id'].'">'.$element['label'].'</a>';
		$output .= '	<span class="avia_loading avia_beside_button_loading"></span>';
		$output .= '	</span>';
		$output .= '	<input class="avia_create_options_page_temlate_sortable" type="hidden" value="'.$element['template_sortable'].'" />';
		$output .= '	<input class="avia_create_options_page_temlate_parent" type="hidden" value="'.$element['temlate_parent'].'" />';
		$output .= '	<input class="avia_create_options_page_temlate_icon" type="hidden" value="'.$element['temlate_icon'].'" />';
		$output .= '	<input class="avia_create_options_page_temlate_remove_label" type="hidden" value="'.$element['remove_label'].'" />';
		if(isset($element['temlate_default_elements']))
		{
			$elString = base64_encode(serialize($element['temlate_default_elements']));
			$output .= '	<input class="avia_create_options_page_subelements_of" type="hidden" value="'.$elString.'" />';
		}
		$output .= '</div>';
		return $output;
	}
	
		
	/**
     * 
     * The dynamical_add_elements method adds a dropdown list of elements and a submit button that allows you add the selcted element to the dom
     * @param array $element the array holds data like type, value, id, class, description which are necessary to render the whole option-section
     * @return string $output the string returned contains the html code generated within the method
     */
	function dynamical_add_elements($element)
	{
		$output = "";
		
		$output .= '<div class="avia_dynamical_add_elements_container">';
		$output .= '	<span class="avia_style_wrap avia_dynamical_add_elements_style_wrap">';
		$output .= '<span class="avia_select_unify"><select class="'.$element['class'].' avia_dynamical_add_elements_select">';
		$output .= '<option value="">Select Element</option>  ';
	

		$link = false;
		
		switch($element['options_file'])
		{
			case "dynamic" :
			case AVIA_BASE."includes/admin/register-admin-dynamic-options.php" :
			case "includes/admin/register-admin-dynamic-options.php" : $link = AVIA_BASE."includes/admin/register-admin-dynamic-options.php"; break;
			case "one_page": 
			case "includes/admin/register-admin-dynamic-one-page-portfolio.php": $link = AVIA_BASE."includes/admin/register-admin-dynamic-one-page-portfolio.php"; break;

		}
		
		if($link) @include($link);
		

		foreach ($elements as $dynamic_element)
		{
			if(empty($dynamic_element['name'])) $dynamic_element['name'] = $dynamic_element['id'];
			$output .= "<option value='". $dynamic_element['id']."'>". $dynamic_element['name']."</option>";
		}
		
		$output .= '</select>';
		$output .= '<span class="avia_select_fake_val">Select Element</span></span>';
		$output .= '	<a href="#" class="avia_button avia_dynamical_add_elements" title="'.$element['name'].'" id="avia_'.$element['id'].'">Add Element</a>';
		$output .= '	<span class="avia_loading avia_beside_button_loading"></span>';
		$output .= '	</span>';
		$output .= '	<input class="avia_dynamical_add_elements_parent" type="hidden" value="'.$element['slug'].'" />';
		$output .= '	<input class="avia_dynamical_add_elements_config_file" type="hidden" value="'.$element['options_file'].'" />';
	
		$output .= '</div>';
		return $output;
	}
	
	
	
	
	
######################################################################
# STYLING WIZARD + HELPER FUNCTIONS
######################################################################
	function styling_wizard($element)
	{
		$output 	= "";
		$select  	= "<select class='add_wizard_el'>";
		$fake_val	= __("Select an element to customize",'avia_framework');
		$iteration  = 0;
		
		$output  = '<span class="avia_style_wrap avia_select_style_wrap"><span class="avia_select_unify">';
		$select .= "<option value=''>{$fake_val}</option>";
		
		//dropdown menu with elements
		foreach($element['order'] as $order)
		{
			$select .= "<optgroup label='{$order}'>";
			
			foreach($element['elements'] as $sub)
			{
				if($sub['group'] == $order)
				{
					$select .= "<option value='".$sub['id']."'>".$sub['name']."</option>";
				}
			}
			
			$select .= "</optgroup>";
		}
		
		$select .= "</select>";
		$select .= '<span class="avia_select_fake_val">'.$fake_val.'</span>';
		$select .= '</span><a href="#" class="avia_button add_wizard_el_button" >'.__("Edit Element",'avia_framework').'</a></span>';
		
		
		$output .= $select;
		$output .= "<div class='av-wizard-element-container'>";
		
		
		//show active items
		if(is_array($element['std']))
		{
			foreach ($element['std'] as $key => $value)
			{
				if(empty($element['elements'][$value['id']])) continue;
			
				$sub 				= $element['elements'][$value['id']];
				$sub['std_a'] 		= $value;
				$sub['iteration'] 	= $key;
				$sub['master_id'] 	= $element['id'];
				$output 		   .= $this->styling_wizard_el($sub);
				$iteration ++;
			}
		}
		
		
		//generate the templates for new items
		$template = "";
		foreach($element['elements'] as $sub)
		{
			$sub['iteration'] 	= "{counter}";
			$sub['master_id'] 	= $element['id'];
			
			$template .= "\n<script type='text/html' id='avia-tmpl-wizard-{$sub['id']}'>\n";
			$template .= $this->styling_wizard_el($sub);
			$template .= "\n</script>\n\n";
			
		}
		$output .= $template;
		
		
		$output .= "</div>";
		
		return $output;
	}
	
	
	function styling_wizard_el($element)
	{
		extract($element);
		
		$extraClass		=  ($sections || $hover) ? "av-wizard-with-extra" : "";
		$name_string  	=  $master_id."-__-".$iteration."-__-";
		$blank_string 	=  $master_id."-__-{counter}-__-";
		
		$output  = "";
		$output .= "<div class='av-wizard-element  {$extraClass}'>";
		$output .= 		"<strong>{$name}</strong>";
		
		if($description)
		{
			$output .= 		"<span class='av-wizard-description'> - {$description}</span>";
		}
		
		$output .= 		"<div class='av-wizard-form-elements'>";
		$output .= 		"<input name='".$name_string."id' value='".$element['id']."' type='hidden' data-recalc='".$blank_string."id' />";
		
			foreach($edit as $key => $form)
			{
				$method = "styling_wizard_".$form['type'];
				
				if(method_exists($this, $method))
				{
					$element['html_name']  = $name_string.$key;
					$element['data_tmpl']  = $blank_string.$key;
					$element['sub_values'] = $form;
					$element['std'] = isset($element['std_a'][$key]) ? $element['std_a'][$key] : "";
					
					$output .= "<div class='av-wizard-subcontainer av-wizard-subcontainer-".$element['sub_values']['type']."'>";
					$output .= $this->$method($element);
					$output .= "</div>";
				}
			}
			
			
		if($extraClass) $output .= "<span class='av-wizard-delimiter'></span>";
		
			
		if($sections)
		{
			global $avia_config;
	
			$output .= "<div class='av-wizard-subcontainer av-wizard-subcontainer-checkbox av-wizard-subcontainer-bottom-box'>";
			$output .= "<span class='av-wizard-checkbox-label-section'>".__('Apply to Section: ','avia_framework')."</span>";
			
			foreach($avia_config['color_sets'] as $key => $name)
			{
				$element['name']  		= $name;
				$element['html_name']  	= $name_string.$key;
				$element['data_tmpl']  	= $blank_string.$key;
				$element['std'] 		= isset($element['std_a'][$key]) ? $element['std_a'][$key] : "true";
				$output .= $this->styling_wizard_checkbox($element);
			}
			
			$output .= "</div>";
		}
		
		if($hover)
		{
			$key 					= 'hover_active';
			$element['name']  		= __('Apply only to mouse hover state','avia_framework');
			$element['html_name']  	= $name_string.$key;
			$element['data_tmpl']  	= $blank_string.$key;
			$element['std'] 		= isset($element['std_a'][$key]) ? $element['std_a'][$key] : "";
			$output .= "<div class='av-wizard-subcontainer av-wizard-subcontainer-checkbox av-wizard-subcontainer-bottom-box'>";
			$output .= $this->styling_wizard_checkbox($element);
			$output .= "</div>";
		}
		
			
			
		$output .= 		'<a class="avia_remove_wizard_set" href="#">×</a>';
		$output .= 		"</div>";
		$output .= "</div>";
		
		return $output;
	}
	
	
	function styling_wizard_hr($element)
	{
		$output  = "";
		$output .= "<div class='av-wizard-hr'></div>";
		
		return $output;

	}
	
	
	function styling_wizard_checkbox($element)
	{
		extract($element);
		
		$checked = ($std != "" && $std != 'disabled') ? "checked='checked'" : "";
		
		$output  = "";
		$output .= "<label><input type='checkbox' class='avia_color_picker' value='true' {$checked} name='{$html_name}' data-recalc='{$data_tmpl}' /> <span class='av-wizard-checkbox-label'>{$name}</span></label>";
		
		return $output;
	}
	
	
	
	
	function styling_wizard_colorpicker($element)
	{
		extract($element);
		
		$output  = "";
		$output .= "<span class='avia_style_wrap avia_colorpicker avia_colorpicker_style_wrap'>";
		$output .= "<input type='text' class='avia_color_picker' value='{$std}' name='{$html_name}' data-recalc='{$data_tmpl}' />";
		$output .= "<span class='avia_color_picker_div'></span></span>";
		$output .= "<div class='subname'>".$sub_values['name']."</div>";
		

		return $output;
	}
	
	
	
	function styling_wizard_size($element)
	{
		$range 		= is_array($element['sub_values']['range']) ? $element['sub_values']['range'] : explode("-", $element['sub_values']['range']);
		$unit 		= !isset($element['sub_values']['unit']) ? "px" : $element['sub_values']['unit'];
		$increment 	= !isset($element['sub_values']['increment']) ? 1 : $element['sub_values']['increment'];
		
		$element['sub_values']['options'] = array();
		$element['sub_values']['options']['Default'] = "";
		
		for ($i = $range[0]; $i <= $range[1]; $i += $increment)
		{
			$element['sub_values']['options'][$i . $unit] = $i . $unit;
		}
		
		return $this->styling_wizard_select($element);
	}
	
	
	
	function styling_wizard_font($element)
	{
		return $this->styling_wizard_select($element);
	}
	
	
	
	function styling_wizard_select($element)
	{
		extract($element);
		$output  = "";
		$output .= "<span class='avia_style_wrap avia_select_style_wrap'><span class='avia_select_unify'><select name='{$html_name}' data-recalc='{$data_tmpl}'>";
		
		foreach($sub_values['options'] as $key => $option)
		{
			$selected = $option == $std ? "selected='selected'" : "";
			$output .= "<option {$selected} value='{$option}'>{$key}</option>";
		}
		
		$output .= "</select><span class='avia_select_fake_val'>Default</span></span></span>";
		$output .= "<div class='subname'>".$sub_values['name']."</div>";
		return $output;
	}
		
	}
}









