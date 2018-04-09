<?php  if ( ! defined('AVIA_FW')) exit('No direct script access allowed');
/**
 * This file holds various functions that modify the wordpress media uploader
 *
 * It utilizes custom posts to create a gallerie for each upload field. 
 * Kudos to woothemes for this great idea :)
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright (c) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */

/**
 *
 */
 
add_action( 'init', array('avia_media', 'generate_post_type' ));
add_filter( 'media_upload_tabs', array('avia_media','add_media_label_header'));

if( !class_exists( 'avia_media' ) )
{	

	/**
	 * The avia media class is a set of static class methods that help to create the hidden image containing posts
	 * @package 	AviaFramework
	 */
	class avia_media
	{
	
		/**
		 * The avia media generate_post_type function builds the hidden posts necessary for image saving on options pages
		 */
		public static function generate_post_type()
		{
			register_post_type( 'avia_framework_post', array(
			'labels' => array('name' => 'Avia Framework' ),
			'show_ui' => false,
			'query_var' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => false,
			'supports' => array( 'editor', 'title' ), 
			'can_export' => true,
			'public' => true,
			'show_in_nav_menus' => false
		) );
		}
		
		
		/**
		 * The avia media get_custom_post function gets a custom post based on a post title. if no post cold be found it creates one
		 * @param string $post_title the title of the post
		 * @package 	AviaFramework
		 */
		public static function get_custom_post( $post_title )
		{
			$save_title = avia_backend_safe_string( $post_title );
			
			$args = array( 	'post_type' => 'avia_framework_post', 
							'post_title' => 'avia_' . $save_title,
							'post_status' => 'draft', 
							'comment_status' => 'closed', 
							'ping_status' => 'closed');
							
			$avia_post = avia_media::get_post_by_title( $args['post_title'] );

			if(!isset($avia_post['ID']) ) 
			{ 
				$avia_post_id = wp_insert_post( $args );
			}
			else
			{
				$avia_post_id = $avia_post['ID'];
			}

			return $avia_post_id;
		}
		
		/**
		 * The avia media get_post_by_title function gets a custom post based on a post title. 
		 * @param string $post_title the title of the post
		 * @package 	AviaFramework
		 */
		public static function get_post_by_title($post_title) {
		    global $wpdb;
		        $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='avia_framework_post'", $post_title ));
		        if ( $post )
		            return get_post($post, 'ARRAY_A');
		
		    return null;
		}
		
		
		/**
		 * The avia add_media_label_header function hooks into wordpress galery tabs and injects a new button label
		 * this label can be found by the frameworks javascript and it then overwrites the default "insert into post" text
		 * @param array $_default_tabs the default tabs
		 * @package 	AviaFramework
		 */
		public static function add_media_label_header($_default_tabs)
		{	
			
			//change the label of the insert button
			if(isset($_GET['avia_label']))
			{	
				echo "<input class='avia_insert_button_label' type='hidden' value='".html_entity_decode($_GET['avia_label'])."' />";
			}
			
			//activate the gallery mode
			if(isset($_GET['avia_gallery_mode']))
			{	
				echo "<input class='avia_gallery_mode_active' type='hidden' value='".$_GET['avia_gallery_mode']."' />";
				if(isset($_default_tabs['library'])) unset($_default_tabs['library']);
				if(isset($_default_tabs['type_url'])) unset($_default_tabs['type_url']);
			}
			
			//remove the default insert method and replace it with the better image id based method
			if(isset($_GET['avia_idbased']))
			{	
				echo "<input class='avia_idbased' type='hidden' value='".$_GET['avia_idbased']."' />";
			}
			
			return $_default_tabs;
		}
		
		public static function url_filter($form_action_url, $type)
		{
			if(isset($_REQUEST['avia_idbased']))
			{
				$form_action_url = $form_action_url . "&amp;avia_idbased=".$_REQUEST['avia_idbased'];
			}
			
			if(isset($_REQUEST['avia_gallery_mode']))
			{
				$form_action_url = $form_action_url . "&amp;avia_gallery_mode=".$_REQUEST['avia_gallery_mode'];
			}
			
			if(isset($_REQUEST['avia_label']))
			{
				$form_action_url = $form_action_url . "&amp;avia_label=".$_REQUEST['avia_label'];
			}
			
			
			return $form_action_url;
		}
	}
}

add_filter( 'media_upload_form_url', array('avia_media','url_filter'), 10, 2);




if( !class_exists( 'avia_media_gallery' ) )
{	
	/**
	 * The avia media gallery class is used for the new media gallery
	 * @package 	AviaFramework
	 */
	class avia_media_gallery
	{
	
		/**
		 * The url filter function attaches the avia_gallery_parameter to all urls within the form
		 * based on that parameter we can perform php checks if the current gallery is a advanced gallery
		 */
		public static function url_filter($form_action_url, $type)
		{
			if(isset($_REQUEST['avia_gallery_active']))
			{
				$form_action_url = $form_action_url . "&amp;avia_gallery_active=".$_REQUEST['avia_gallery_active'];
			}
			return $form_action_url;
		}
		
		
		/*
		* 	register the stylesheet that hides default insert buttons and adds styles for the additional insert buttons
		*/
		public static function register_stylesheet($current_hook)
		{
			if(isset($_REQUEST['avia_gallery_active']) && $current_hook == 'media-upload-popup')
			{
				wp_enqueue_style(  'avia_gallery_mode', AVIA_CSS_URL . 'conditional_load/avia_gallery_mode.css' ); 
			}
		}
		
		public static function add_buttons( $form_fields, $post ) {
		
			if(isset($_REQUEST['avia_gallery_active']) || isset($_REQUEST['fetch']))
			{
				$label = __('Add to Gallery', 'avia_framework');
				if(isset($_REQUEST['avia_gallery_label'])) $label = $_REQUEST['avia_gallery_label'];
			
				$form_fields['avia-send-to-editor'] = array(
					'label' => '',
					'input' => 'html',
					'html'  => '<a href="#" data-attachment-id="'.$post->ID.'" class="button avia_send_to_gallery">'.$label.'</a>',
				);
			}
		
			return $form_fields;
		}
		
		public static function remove_unused_tab($default_tabs)
		{	
			if(isset($_REQUEST['avia_gallery_active']))
			{
				$default_tabs = array('type' => 'From Computer', 'gallery' => 'Gallery', 'library' => 'Media Library');
			}
			return $default_tabs;
		}
		
		//filter function for the thickbox - only display insert video tab
		public static function video_tab($default_tabs)
		{	
			if(isset($_REQUEST['tab']) && 'avia_video_insert' == $_REQUEST['tab'] )
			{
				$default_tabs = array('avia_video_insert' => 'Insert Video');
			}
			return $default_tabs;
		}
		
		//function that creates the insert form
		public static function create_video_tab()
		{
			wp_iframe( array('avia_media_gallery','media_avia_create_video_insert') );
		
			
		}
		
		public static function media_avia_create_video_insert()
		{
			
			$video_description = '<p class="help">Enter the URL to the Video. <br/> A list of all supported Video Services can be found <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">here</a>
								<br/> <br/> 
								Working examples:<br/>
								<strong>http://vimeo.com/1084537</strong><br/> 
								<strong>http://www.youtube.com/watch?v=G0k3kHtyoqc</strong><br/> 
								</p>';
					
			$video_description = apply_filters('avia_filter_video_insert_label', $video_description);
			
			$output = "<form>";
			$output .='<h3 class="media-title">Insert media from another website</h3>';			
			$output .= '<div id="media-items">';
			$output .= '<div class="media-item media-blank">';
			$output .=	'<table class="describe avia_video_insert_table"><tbody>
						<tr>
							<th valign="top" scope="row" class="label" style="width:130px;">
								<span class="alignleft"><label for="src">' . __('Enter Video URL', 'avia_framework') . '</label></span>
								<span class="alignright"><abbr id="status_img" title="required" class="required">*</abbr></span>
							</th>
							<td class="field">
								<input id="src" name="src" value="" type="text" aria-required="true"  />
								'.$video_description.'								
							</td>
						</tr>
						<tr>
							<td></td>
							<td class="avia_button_container">
								<input type="button" class="button" id="avia_insert_video" value="' . esc_attr__('Insert Video') . '" />
							</td>
						</tr>';
			$output .= '</tbody></table>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</form>';
			echo $output;

			}
		
	}
}



add_action( 'media_upload_avia_video_insert', array('avia_media_gallery','create_video_tab') );
add_filter( 'media_upload_tabs', array('avia_media_gallery','video_tab'), 11);
add_filter( 'media_upload_tabs', array('avia_media_gallery','remove_unused_tab'));
add_filter( 'attachment_fields_to_edit', array('avia_media_gallery','add_buttons'), 10, 2 );
add_filter( 'attachment_fields_to_edit', array('avia_media_gallery','add_buttons'), 10, 2 );
add_filter( 'media_upload_form_url', array('avia_media_gallery','url_filter'), 10, 2);
add_filter( 'admin_enqueue_scripts', array('avia_media_gallery','register_stylesheet'), 10);
