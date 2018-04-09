<?php

class AviaMedia{

	var $config;

	function __construct()
	{
		$this->hook_in();
		$this->add_assets();
	}
	
	function hook_in()
	{
		add_filter( 'image_size_names_choose', array($this, 'avia_media_choose_image_size' ));
		//add_filter( 'media_view_strings', array($this, 'avia_media_menu_filter' ));image_size_names_choose
		//add_action( 'print_media_templates', array($this, 'add_media_views' ));
	}
	
	function add_assets()
	{
		if(is_admin())
		{
			$ver = AviaBuilder::VERSION;
	
			wp_enqueue_script('avia_media_js' , AviaBuilder::$path['assetsURL'].'js/avia-media.js' , array('avia_element_js'), $ver, TRUE );
			wp_enqueue_style( 'avia-media-style' , AviaBuilder::$path['assetsURL'].'css/avia-media.css');
		}
	}
	
	
	function avia_media_choose_image_size($sizes)
	{
		global $avia_config;

		if(isset($avia_config['selectableImgSize']))
		{
			$sizes = array_merge($sizes, $avia_config['selectableImgSize']);
		}
		
		return $sizes;
	}
	
	function avia_media_menu_filter( $strings ) 
	{
		$image_only = 1;
		$gallery_only = 0;
		
		if($image_only)
		{
			unset( $strings['setFeaturedImageTitle'], $strings['createGalleryTitle']);
			$strings['insertIntoPost'] = $strings['insertMediaTitle'] = __('Insert Image','avia_framework' );
		}
		
		if($gallery_only)
		{
			unset( $strings['setFeaturedImageTitle'], $strings['createGalleryTitle'] );
		}
		
	
	    //unset( $strings['insertFromUrlTitle'] );
	    return $strings;
	}
		
	function add_media_views()
	{
	
	}
}








// http://wordpress.stackexchange.com/questions/83532/saving-custom-field-in-attachment-window-in-wordpress-3-5

/**
 * Add Author Name and URL fields to media uploader
 *
 * @param $form_fields array, fields to include in attachment form
 * @param $post object, attachment record in database
 * @return $form_fields, modified form fields
 */
function admin_attachment_field_media_author_credit( $form_fields, $post ) {

    $form_fields['av-custom-link'] = array(
        'label' => __('Custom Link','avia_framework'),
        'input' => 'text',
        'value' => get_post_meta( $post->ID, 'av-custom-link', true ),
        'helps' => 'If provided, the image will link to this URL',
        'show_in_modal' => true,
        'show_in_edit' => false,
    );

    return $form_fields;

} add_filter( 'attachment_fields_to_edit', 'admin_attachment_field_media_author_credit', 10, 2 );

/**
 * Save values of Author Name and URL in media uploader
 *
 * @param $post array, the post data for database
 * @param $attachment array, attachment fields from $_POST form
 * @return $post array, modified post data
 */

function admin_attachment_field_media_author_credit_save( $post, $attachment ) {

    if( isset( $attachment['av-custom-link'] ) )
        update_post_meta( $post['ID'], 'av-custom-link', $attachment['av-custom-link'] );

    return $post;

} add_filter( 'attachment_fields_to_save', 'admin_attachment_field_media_author_credit_save', 10, 2 );

/**
 * Save values of Author Name and URL in media uploader modal via AJAX
 */

function admin_attachment_field_media_author_credit_ajax_save() {

    $post_id = $_POST['id'];
	
	check_ajax_referer( 'update-post_' . $post_id, 'nonce' );
	if(!current_user_can('edit_posts')) die();
	
    if( isset( $_POST['attachments'][$post_id]['av-custom-link'] ) )
    {
        update_post_meta( $post_id, 'av-custom-link', $_POST['attachments'][$post_id]['av-custom-link'] );
	}
	
	clean_post_cache($post_id);

} 

add_action('wp_ajax_save-attachment-compat', 'admin_attachment_field_media_author_credit_ajax_save', 0, 1); 




