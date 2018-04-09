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

// Get current post ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
	$post_ID = $_GET['id'];
    
} else {
    
	// generate one if post id does not exist
	$post = get_default_post_to_edit('the_grid', true);
	$_GET['id'] = $post_ID = $post->ID;
    
}

echo '<div id="post_ID" value="'.$post_ID.'"></div>';

// main classes
$WPML_base       = new The_Grid_WPML();
$the_grid_base   = new The_Grid_Base();
$custom_fields   = new The_Grid_Custom_Fields();
$preloader_base  = new The_Grid_Preloader_Skin();
$navigation_base = new The_Grid_Navigation_Skin();
$animation_name  = new The_Grid_Item_Animation();

// set prefix for metabox fields
$prefix = TG_PREFIX;

// get/set grid name
$grid_name = get_post_meta($post_ID, $prefix.'name', true);
$grid_name = ($grid_name) ? $grid_name : 'New Grid';

// check if next gen is active
$nextgen = class_exists('nggdb') ? true : false;

// build metabox
$grid_settings = array(
		'id'    => 'the_grid_metabox',
		'title' => 'title',
		'icon' => '<i class="dashicons tg-metabox-icon"></i>',
		'color' => '#f1f1f1',
		'background' => '#e74c3c',
		'class' => 'tomb-menu-options',
		'menu' => true,
		'pages' => array('the_grid'),
		'type' => 'page',
		'fields' => array(	
			array(
				'id'   => 'section_name_start',
				'name' => __( 'Naming', 'tg-text-domain' ),
				'desc' => __( 'The shortcode can be added everywhere in a page or a post. The grid name must be unique!', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'General', 'tg-text-domain' ),
			),	
			array(
				'id'   => $prefix.'name',
				'name' => __( 'Name', 'tg-text-domain' ),
				'desc' => __( 'Enter the name of the current grid.', 'tg-text-domain' ),
				'sub_desc' =>  '<strong>'.__( '* The grid name must be unique in order to prevent any conflict.', 'tg-text-domain' ).'</strong>',
				'type' => 'text',
				'disabled' => false,
				'std' => $grid_name,
				'tab' => __( 'General', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'General', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'shortcode',
				'name' => __( 'Shortcode', 'tg-text-domain' ),
				'desc' => __( 'Use this shortcode anywhere in a post type or a page to display the current grid.', 'tg-text-domain' ).'<br>'. __( 'The shortcode grid name is based on the grid name and can be confused if several grid have the same name.', 'tg-text-domain' ),
				'type' => 'custom',
				'options' => '<div id="tg-shortcode-wrong-name" style="display:none">*** '.__( 'Wrong name', 'tg-text-domain' ).' ****</div><input type="text" disabled class="tomb-text" id="the_grid_shortcode" value=\'[the_grid name="'.$grid_name.'"]\'>',
				'tab' => __( 'General', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'General', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'id',
				'name' => __( 'Grid ID', 'tg-text-domain' ),
				'desc' => __( 'You will find here the grid ID.', 'tg-text-domain' ),
				'sub_desc' =>  '<strong>'.__( '* The grid ID is unique.', 'tg-text-domain' ).'</strong>',
				'type' => 'custom',
				'options' => '<input type="text" disabled class="tomb-text" id="the_grid_id" value="grid-'.$post_ID.'">',
				'tab' => __( 'General', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'General', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'css_class',
				'name' => __( 'Custom CSS class', 'tg-text-domain' ),
				'desc' => __( 'Enter your custom css class.', 'tg-text-domain' ),
				'sub_desc' =>  '<strong>'.__( '* Usefull to add custom css to the grid.', 'tg-text-domain' ).'</strong>',
				'type' => 'text',
				'disabled' => false,
				'std' => '',
				'tab' => __( 'General', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons-admin-generic"></i>'
			),
			array(
				'type' => 'break',
				'tab' => __( 'General', 'tg-text-domain' ),
			),
			$WPML_base->WPML_language_switcher(),
			array(
				'id'   => 'section_name_end',
				'type' => 'section_end',
				'tab' => __( 'General', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons-admin-generic"></i>'
			),
			array(
				'id'   => 'section_source_start',
				'name' => __( 'Content Source', 'tg-text-domain' ),
				'desc' => __( 'Source content settings', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'source_type',
				'name' => __('Source type', 'mobius'),
				'desc' => __('Select the type of content to display inside the grid.', 'tg-text-domain').'</strong>',
				'sub_desc' => '',
				'type' => 'image_select',
				'std' => 'post_type',
				'options' => array (
					array (
						'label' => 'Post Type',
						'value' => 'post_type',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/wordpress-logo.png'
					),
					array (
						'label' => 'Instagram',
						'value' => 'instagram',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/instagram-logo.png'
					),
					array (
						'label' => 'Youtube',
						'value' => 'youtube',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/youtube-logo.png'
					),
					array (
						'label' => 'Vimeo',
						'value' => 'vimeo',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/vimeo-logo.png'
					),
					array (
						'label' => 'Facebook',
						'value' => 'facebook',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/facebook-logo.png'
					),
					array (
						'label' => 'Twitter',
						'value' => 'twitter',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/twitter-logo.png'
					),
					array (
						'label' => 'Flickr',
						'value' => 'flickr',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/flickr-logo.png'
					),
					array (
						'label' => 'RSS Feed',
						'value' => 'rss',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/rss-logo.png'
					),
					/*array (
						'label' => '500px',
						'value' => '500px',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/500px-logo.png'
					),
					array (
						'label' => 'Dribbble',
						'value' => 'dribbble',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/dribbble-logo.png'
					),
					*/
				),
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'item_number',
				'name' => __( 'Item Number', 'tg-text-domain' ),
				'desc' => __( 'Enter the number of items to load inside the grid.', 'tg-text-domain' ),
				'sub_desc' => '<strong>'.__( '* -1 allows to load all items (only for Post Type)', 'tg-text-domain' ).'<br>'.__( '* 0 corresponds to the default number of ', 'tg-text-domain' ).'<a href="'.admin_url('options-reading.php').'" target="_blank">'.__( 'post per page.', 'tg-text-domain' ).'</a></strong>',
				'type' => 'number',
				'label' => '',
				'sign'  => '',
				'min' => -1,
				'max' => 50,
				'std' => get_option('posts_per_page'),
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'post_type',
				'name' => __( 'Post Types', 'tg-text-domain'  ),	
				'desc' => __( 'Select one or several post type to display inside the current grid.', 'tg-text-domain'  ),
				'sub_desc' => '<strong>'.__( '* Multiple selection is posssible', 'tg-text-domain' ).'</strong>',
				'type' => 'multiselect',
				'placeholder' => __( 'Select a post type', 'tg-text-domain' ),
				'width' => 410,
				'options' => $the_grid_base->get_all_post_types(),
				'std' => 'post',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			
			array( 
				'id' => $prefix . 'gallery',
				'name' => __('Media Library Images', 'tg-text-domain'),
				'desc' => __('You can select multiple images to add in the current grid.', 'tg-text-domain').'<br>'.__('If no image is added then all images will be displayed.', 'tg-text-domain'),
				'sub_desc' => __('You can easily arrange order by dragging and dropping images.', 'tg-text-domain') .'<br><strong>' .__('* Custom order will only works if you have media library as unique post type source.', 'tg-text-domain') .'</strong>',
				'type' => 'gallery',
				'frame_title'   => __( 'Select or upload images to create a gallery', 'tg-text-domain'),
				'frame_button'  => __('Insert gallery', 'tg-text-domain'),
				'button_upload' => __( 'Add images', 'tg-text-domain'),
				'button_remove' => __( 'Remove gallery', 'tg-text-domain'),
				'delete_message' => __( 'Are you sure you want to remove all the gallery images?', 'tg-text-domain'),
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type'),
					array($prefix.'post_type', 'contains', 'attachment')
				)
			),
			
			array(
				'id'   => 'section_source_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' )
			),

			array(
				'id'   => 'section_instagram_start',
				'name' => __( 'Instagram Settings' ),
				'desc' => __( 'You set any combination of @username and #hashtag.', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'instagram')
				)
			),
			array(
				'id'   => $prefix.'instagram_username',
				'name' => __( 'Username(s)', 'tg-text-domain' ),
				'desc' => __( 'Type usernames', 'tg-text-domain' ),
				'sub_desc' => __( 'Username can be an ID or the username.', 'tg-text-domain' ).'<br><strong> * '.__( 'For multiple username, please seperate them by a comma (e.g.: user1, user2, user3, ...)', 'tg-text-domain' ).'<br>* '.__( 'If you leave username and hashtag fields blank then it will retrieve your Instagram images.', 'tg-text-domain' ).'<br>* '. __( 'Username will be used for Instagram user (Layout Tab)', 'tg-text-domain' ) .'</strong>',
				'type' => 'text',
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'instagram_hashtag',
				'name' => __( 'Hash Tag(s)', 'tg-text-domain' ),
				'desc' =>  __( 'Type hash tags', 'tg-text-domain' ),
				'sub_desc' => '<strong> *'.__( 'For multiple hash tags, please seperate them by a comma (e.g.: hashtag1, hashtag2, hashtag3, ...)', 'tg-text-domain' ).'</strong>',
				'type' => 'text',
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_instagram_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' ),
			),
			
			
			array(
				'id'   => 'section_youtube_start',
				'name' => __( 'Youtube Settings' ),
				'desc' => '',
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'youtube')
				)
			),
			array(
				'id'   => $prefix.'youtube_source',
				'name' => __( 'Youtube Source', 'tg-text-domain'  ),
				'sub_desc' => '',
				'desc' => __( 'Please select Youtube source to display in the grid.', 'tg-text-domain'  ),
				'type' => 'select',
				'placeholder' => __( 'Select a Source', 'tg-text-domain' ),
				'width' => 180,
				'options' => array(
					'channel' => __( 'Channel', 'tg-text-domain' ),
					'playlist' => __( 'Playlist', 'tg-text-domain' ),
					'videos' => __( 'Video(s)', 'tg-text-domain' )
				),
				'std' => 'channel',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'youtube_channel',
				'name' => __( 'Youtube Channel', 'tg-text-domain' ),
				'desc' =>  __( 'Enter YouTube Channel ID.', 'tg-text-domain' ),
				'sub_desc' => __( 'See how to find the Youtube channel ID ', 'tg-text-domain' ).' <a target="_blank" href="https://support.google.com/youtube/answer/3250431?hl=en">'.__( 'here', 'tg-text-domain' ).'</a><br>'. __( 'Channel ID will be used for Youtube banner (Layout Tab)', 'tg-text-domain' ),
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'youtube_source', '==', 'channel')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'youtube_playlist',
				'name' => __( 'Youtube Playlist', 'tg-text-domain' ),
				'desc' =>  __( 'Enter YouTube Playlist ID.', 'tg-text-domain' ),
				'sub_desc' => __( 'See how to find the Youtube Playlist ID ', 'tg-text-domain' ).' <a target="_blank" href="https://support.google.com/youtube/answer/3250431?hl=en">'.__( 'here', 'tg-text-domain' ).'</a>',
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'youtube_source', '==', 'playlist')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'youtube_videos',
				'name' => __( 'Youtube Video(s)', 'tg-text-domain' ),
				'desc' =>  __( 'Enter YouTube Video(s) ID.', 'tg-text-domain' ),
				'sub_desc' => '<strong>* '. __( 'You can enter multiple video IDs separated with a comma', 'tg-text-domain' ).'</strong><br> '.__( '(e.g.: sGbxmsDFVnE, CTNJ51ghzdY, da8s9m4zEpo)', 'tg-text-domain' ),
				'type' => 'textarea',
				'cols' => 80,
				'rows' => 5,
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'youtube_source', '==', 'videos')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'youtube_order',
				'name' => __( 'Youtube Order', 'tg-text-domain' ),
				'desc' =>  __( 'Sort Youtube videos by.', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'select',
				'placeholder' => __( 'Select a Source', 'tg-text-domain' ),
				'width' => 180,
				'options' => array(
					//'relevance' => __( 'Relevance (default)', 'tg-text-domain' ),
					'date' => __( 'Date', 'tg-text-domain' ),
					//'rating' => __( 'Rating', 'tg-text-domain' ),
					'title' => __( 'Title', 'tg-text-domain' ),
					'viewCount' => __( 'Number of view', 'tg-text-domain' )
					
				),
				'std' => 'date',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'youtube_source', '==', 'channel')
				)
			),
			array(
				'id'   => 'section_youtube_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' ),
			),
			

			
			array(
				'id'   => 'section_vimeo_start',
				'name' => __( 'Vimeo Settings' ),
				'desc' => '',
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'vimeo')
				)
			),
			array(
				'id'   => $prefix.'vimeo_source',
				'name' => __( 'Vimeo Source', 'tg-text-domain'  ),
				'sub_desc' => '',
				'desc' => __( 'Please select Vimeo source to display in the grid.', 'tg-text-domain'  ),
				'type' => 'select',
				'placeholder' => __( 'Select a Source', 'tg-text-domain' ),
				'width' => 180,
				'options' => array(
					'users' => __( 'User', 'tg-text-domain' ),
					'albums' => __( 'Album', 'tg-text-domain' ),
					'groups' => __( 'Group', 'tg-text-domain' ),
					'channels' => __( 'Channel', 'tg-text-domain' )
				),
				'std' => 'users',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'vimeo_user',
				'name' => __( 'Vimeo User', 'tg-text-domain' ),
				'desc' =>  __( 'Enter Vimeo username.', 'tg-text-domain' ),
				'sub_desc' => __( 'Username can be found in the vimeo url (e.g.: gopro)', 'tg-text-domain' ) .'<br>'. __( 'Username will be used for vimeo user banner (Layout Tab)', 'tg-text-domain' ),
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'vimeo_source', '==', 'users')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'vimeo_group',
				'name' => __( 'Vimeo Group', 'tg-text-domain' ),
				'desc' =>  __( 'Enter Vimeo group ID.', 'tg-text-domain' ),
				'sub_desc' => __( 'Group ID can be found in the vimeo url (e.g.: 114)', 'tg-text-domain' ),
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'vimeo_source', '==', 'groups')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'vimeo_album',
				'name' => __( 'Vimeo Album', 'tg-text-domain' ),
				'desc' =>  __( 'Enter Vimeo album ID.', 'tg-text-domain' ),
				'sub_desc' => __( 'Album ID can be found in the vimeo url (e.g.: 1893031)', 'tg-text-domain' ),
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'vimeo_source', '==', 'albums')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'vimeo_channel',
				'name' => __( 'Vimeo Channel', 'tg-text-domain' ),
				'desc' =>  __( 'Enter Vimeo channel name.', 'tg-text-domain' ),
				'sub_desc' => __( 'Channel name can be found in the vimeo url (e.g.: goprocreative)', 'tg-text-domain' ),
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'vimeo_source', '==', 'channels')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'vimeo_sort',
				'name' => __( 'Vimeo Sort', 'tg-text-domain' ),
				'desc' =>  __( 'Sort Vimeo videos by.', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'select',
				'placeholder' => '',
				'width' => 200,
				'options' => array(
					'' => __( 'Default', 'tg-text-domain' ),
					'date' => __( 'Date', 'tg-text-domain' ),
					'alphabetical' => __( 'Alphabetical', 'tg-text-domain' ),
					'plays' => __( 'Number of views', 'tg-text-domain' ),
					'likes' => __( 'Number of likes', 'tg-text-domain' ),
					'comments' => __( 'Number of comments', 'tg-text-domain' ),
					'duration' => __( 'Video duration', 'tg-text-domain' ),
					'modified_time' => __( 'Modified time', 'tg-text-domain' ),
					'manual' => __( 'Manual (Channel only)', 'tg-text-domain' )
					
				),
				'std' => 'default',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'vimeo_order',
				'name' => __( 'Vimeo Order', 'tg-text-domain' ),
				'desc' =>  __( 'Vimeo videos order.', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'select',
				'placeholder' => '',
				'width' => 140,
				'options' => array(
					'desc' => __( 'Descending', 'tg-text-domain' ),
					'asc' => __( 'Ascending', 'tg-text-domain' )	
				),
				'std' => 'desc',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_vimeo_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' ),
			),
			
			array(
				'id'   => 'section_facebook_start',
				'name' => __( 'Facebook Settings' ),
				'desc' => '',
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'facebook')
				)
			),
			array(
				'id' => $prefix . 'facebook_source',
				'name' => __('Source', 'tg-text-domain'),
				'desc' => __( 'Please select Facebook source to display in the grid.', 'tg-text-domain' ),
				'sub_desc' => '',
				'width' => 200,
				'type' => 'select',
				'std' => 'page_timeline',
				'options' => array (
					'page_timeline' =>  __('Facebook public page', 'tg-text-domain'),
					'group'         =>  __('Public group page', 'tg-text-domain'),                      
					'album'         =>  __('Public album', 'tg-text-domain')	
				),
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'facebook_page',
				'name' => __( 'Facebook Page', 'tg-text-domain' ),
				'desc' =>  __( 'Enter a Page Name (or url) of a public Facebook Page', 'tg-text-domain' ),
				'sub_desc' => __( 'Not work with personal profile', 'tg-text-domain' ),
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'facebook_source', '==', 'page_timeline')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'facebook_group_id',
				'name' => __( 'Facebook Group ID', 'tg-text-domain' ),
				'desc' =>  __( 'Enter a Facebook Group ID', 'tg-text-domain' ),
				'sub_desc' =>  __( 'You will find the Group ID in the Facebook URL', 'tg-text-domain' ).' <a href="https://lookup-id.com/">('. __( 'Find groud ID', 'tg-text-domain' ).')</a>',
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'facebook_source', '==', 'group')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'facebook_album_id',
				'name' => __( 'Facebook Album ID', 'tg-text-domain' ),
				'desc' =>  __( 'Enter a facebook album ID', 'tg-text-domain' ),
				'sub_desc' => __( 'You will find the album ID in the Facebook URL', 'tg-text-domain' ),
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'facebook_source', '==', 'album')
				)
			),
			array(
				'id'   => 'section_facebook_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' ),
			),
			array(
				'id'   => 'section_twitter_start',
				'name' => __( 'Twitter Settings' ),
				'desc' => '',
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'twitter')
				)
			),
			array(
				'id' => $prefix . 'twitter_source',
				'name' => __('Source', 'tg-text-domain'),
				'desc' => __( 'Please select Twitter source to display in the grid.', 'tg-text-domain' ),
				'sub_desc' => '',
				'width' => 200,
				'type' => 'select',
				'std' => 'user_timeline',
				'options' => array (
					'user_timeline' =>  __('User Timeline', 'tg-text-domain'),
					'search'        =>  __('Tweets by search', 'tg-text-domain'), 
					'favorites'     =>  __('User Favorites', 'tg-text-domain'),                      
					'list_timeline' =>  __('User List', 'tg-text-domain')	
				),
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'twitter_include',
				'name' => __( 'Included Content', 'tg-text-domain' ),
				'desc' => __( 'Check content to include from Twitter', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'checkbox_list',
				'std' => array('gallery','video','audio','quote','link'),
				'options' => array(
					'retweets' =>  __('Retweets', 'tg-text-domain'),
					'replies'  =>  __('Replies', 'tg-text-domain')
				),
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'twitter_source', '!=', 'search')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'twitter_username',
				'name' => __( 'Twitter Username', 'tg-text-domain' ),
				'desc' =>  __( 'Enter a Twitter Username', 'tg-text-domain' ),
				'sub_desc' => '('.__( 'Note: Do not add @', 'tg-text-domain' ).')',
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'twitter_source', '!=', 'search')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'twitter_listname',
				'name' => __( 'Twitter List Name', 'tg-text-domain' ),
				'desc' =>  __( 'Enter a Twitter List Name', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'twitter_source', 'contains', 'list_timeline')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'twitter_searchkey',
				'name' => __( 'Twitter Search Key Word', 'tg-text-domain' ),
				'desc' =>  __( 'Enter any word or #hashtag', 'tg-text-domain' ),
				'sub_desc' => __( 'look', 'tg-text-domain' ).' <a href="https://dev.twitter.com/rest/public/search" target="_blank">'.__( 'here', 'tg-text-domain' ).' </a>'.__( 'for advanced terms', 'tg-text-domain' ),
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'twitter_source', '==', 'search')
				)
			),
			array(
				'id'   => 'section_twitter_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' ),
			),
			array(
				'id'   => 'section_flickr_start',
				'name' => __( 'Flickr Settings' ),
				'desc' => '',
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'flickr')
				)
			),
			array(
				'id' => $prefix . 'flickr_source',
				'name' => __('Source', 'tg-text-domain'),
				'desc' => __( 'Please select Flickr source to display in the grid.', 'tg-text-domain' ),
				'sub_desc' => '',
				'width' => 200,
				'type' => 'select',
				'std' => 'public_photos',
				'options' => array (
					'public_photos' =>  __('Public Photos', 'tg-text-domain'),
					'photo_sets'    =>  __('Photoset', 'tg-text-domain'),
					'gallery'       =>  __('Gallery', 'tg-text-domain'),                       
					'group'         =>  __('Groups Photos', 'tg-text-domain')	
				),
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'flickr_user_url',
				'name' => __( 'Flickr User URL', 'tg-text-domain' ),
				'desc' =>  __( 'Enter a Flickr user url', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'flickr'),
					array($prefix.'flickr_source', 'contains', 'photo')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'flickr_photoset_id',
				'name' => __( 'Flickr Photoset ID', 'tg-text-domain' ),
				'desc' =>  __( 'Enter a Flickr photoset id', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'flickr'),
					array($prefix.'flickr_source', '==', 'photo_sets')
				)
			),
			array(
				'id'   => $prefix.'flickr_photoset_get',
				'name' => __( 'Get Photoset ID', 'tg-text-domain' ),
				'desc' =>  __( 'Click here to fetch the photoset from the current flickr user url', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'custom',
				'std'  => '',
				'options' => '<div class="tomb-button button-primary" id="tg_flickr_get_photosets_list" data-action="tg_get_flickr_photosets">'.__( 'Get Photoset(s)', 'tg-text-domain' ).'</div>',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'flickr'),
					array($prefix.'flickr_source', '==', 'photo_sets')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'flickr_gallery_url',
				'name' => __( 'Flickr Gallery URL', 'tg-text-domain' ),
				'desc' =>  __( 'Enter a Flickr gallery URL', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'flickr'),
					array($prefix.'flickr_source', '==', 'gallery')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'flickr_group_url',
				'name' => __( 'Flickr Group URL', 'tg-text-domain' ),
				'desc' =>  __( 'Enter a Flickr group url', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'flickr'),
					array($prefix.'flickr_source', '==', 'group')
				)
			),
			array(
				'id'   => 'section_flickr_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' ),
			),
			array(
				'id'   => 'section_rss_start',
				'name' => __( 'RSS Feed Settings' ),
				'desc' => '',
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'rss')
				)
			),
			array(
				'id'   => $prefix.'rss_feed_url',
				'name' => __( 'RSS Feed URL', 'tg-text-domain' ),
				'desc' => __( 'Enter your RSS Feed url(s)', 'tg-text-domain' ),
				'sub_desc' => __( 'Several urls, separated by a comma, are allowed (e.g.: url1.com, url2.com)', 'tg-text-domain' ) .'<br>'.__( 'N.B.: A RSS feed has a limited amount of posts. So you will not able, most of the time, to load more than 20-30 posts', 'tg-text-domain' ),
				'type' => 'text',
				'std'  => '',
				'size' => 320,
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'rss')
				)
			),
			array(
				'id'   => 'section_rss_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' ),
			),
			/*array(
				'id'   => 'section_nextgen_start',
				'name' => __( 'NexGen Gallery Settings' ),
				'desc' => '',
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'nextgen')
				)
			),
			array(
				'id'   => $prefix . 'nextgen_source',
				'name' => __( 'NexGen Gallery Settings' ),
				'desc' => '',
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'nextgen')
				)
			),
			array(
				'id' => $prefix . 'nextgen_source',
				'name' => __('Source', 'tg-text-domain'),
				'desc' => __( 'Please select a NextGen source to display in the grid.', 'tg-text-domain' ),
				'sub_desc' => '',
				'width' => 200,
				'type' => 'select',
				'std' => 'public_photos',
				'options' => array (
					'gallery'        =>  __('Gallery', 'tg-text-domain'),
					'album'          =>  __('Album', 'tg-text-domain'),
					'single_images'  =>  __('Single Images', 'tg-text-domain'),                       
					'recent_images'  =>  __('Recent Images', 'tg-text-domain'),
					'random_images'  =>  __('Random Images', 'tg-text-domain'),
					'search_images'  =>  __('Search Images', 'tg-text-domain'),
					'tags_gallery'   =>  __('Tags Gallery', 'tg-text-domain'),
					'tags_album'     =>  __('Tags Album', 'tg-text-domain')
				),
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'nextgen_gallery_id',
				'name' => __('Gallery', 'tg-text-domain'),
				'desc' => __( 'Select a gallery to display in the grid.', 'tg-text-domain' ),
				'sub_desc' => '',
				'width' => 200,
				'type' => 'select',
				'std' => 'public_photos',
				'options' => $nextgen ? The_Grid_Nextgen::get_gallery_list() : array(),
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'nextgen_source', '==', 'gallery')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'nextgen_album_id',
				'name' => __('Albums', 'tg-text-domain'),
				'desc' => __( 'Select an album to display in the grid.', 'tg-text-domain' ),
				'sub_desc' => '',
				'width' => 200,
				'type' => 'select',
				'std' => 'public_photos',
				'options' => $nextgen ? The_Grid_Nextgen::get_album_list() : array(),
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'nextgen_source', '==', 'album')
				)
			),
			array(
				'id'   => $prefix.'nextgen_image_ids',
				'name' => __( 'Images ID', 'tg-text-domain' ),
				'desc' =>  __( 'Enter a your image IDs from NextGen plugin', 'tg-text-domain' ),
				'sub_desc' =>  __( 'IDs, must be comma separated. IDs Ranges are accepted (e.g.: 2, 5, 8-14, 20, 30-50)', 'tg-text-domain' ),
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'nextgen_source', '==', 'single_images')
				)
			),
			array(
				'id'   => $prefix.'nextgen_search_request',
				'name' => __( 'Search Query', 'tg-text-domain' ),
				'desc' =>  __( 'Enter your search query', 'tg-text-domain' ),
				'sub_desc' =>  __( 'Accept comma separated multiplue queries (e.g.: book, story)', 'tg-text-domain' ),
				'type' => 'text',
				'size' => 320,
				'std'  => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'nextgen_source', '==', 'search_images')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'nextgen_tags',
				'name' => __('Tags', 'tg-text-domain'),
				'desc' => __( 'Select tag(s) to display in the grid.', 'tg-text-domain' ),
				'sub_desc' => '',
				'width' => 200,
				'type' => 'select',
				'std' => 'public_photos',
				'options' => $nextgen ? The_Grid_Nextgen::get_tag_list() : array(),
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'nextgen_source', '==', 'tags_gallery')
				)
			),
			array(
				'id'   => 'section_nextgen_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' ),
			),*/
			array(
				'id'   => 'section_post_status_start',
				'name' => __( 'Content Status', 'tg-text-domain' ),
				'desc' => __( 'Retrieves content by Post Status.', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type')
				)
			),
			array(
				'id'   => $prefix.'post_status',
				'name' => __( 'Post Status', 'tg-text-domain'  ),
				'sub_desc' => '<strong>'.__( '* Default value is \'publish\', but if the user is logged in, \'private\' is added.', 'tg-text-domain'  ).'</strong>',
				'desc' => __( 'Show posts associated with certain status.', 'tg-text-domain'  ),
				'type' => 'multiselect',
				'placeholder' => __( 'Select a post status', 'tg-text-domain' ),
				'width' => 410,
				'options' => array(
					'any' => __( 'Any', 'tg-text-domain' ),
					'publish' => __( 'Publish', 'tg-text-domain' ),
					'pending' => __( 'Pending', 'tg-text-domain' ),
					'draft' => __( 'Draft', 'tg-text-domain' ),
					'auto-draft' => __( 'Auto Draft', 'tg-text-domain' ),
					'future' => __( 'Future', 'tg-text-domain' ),
					'private' => __( 'Private', 'tg-text-domain' ),
					'inherit' => __( 'Inherit', 'tg-text-domain' ),
					'trash' => __( 'Trash', 'tg-text-domain' )
				),
				'std' => 'publish',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_post_status_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_cats_start',
				'name' => __( 'Category Filter', 'tg-text-domain' ),
				'desc' => __( 'Filter your grid by taxonomy terms', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type')
				)
			),
			array(
				'id'   => $prefix.'categories_input',
				'name' => '',
				'desc' => '',
				'sub_desc' => '',
				'type' => 'custom',
				'options' => '<div data-tg-taxonomy-terms=\''.$the_grid_base->get_all_terms().'\'></div>',
				'std' => '',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'categories',
				'name' => __( 'Category/Taxonomy terms', 'tg-text-domain'  ),
				'desc' => __( 'Select taxonomy term(s) from the current post type(s).', 'tg-text-domain' ),
				'sub_desc' => '<strong>'.__( '* Multiple selection is posssible', 'tg-text-domain' ).'</strong>',
				'type' => 'multiselect',
				'placeholder' => __( 'Select taxonomy terms', 'tg-text-domain' ),
				'width' => 410,
				'options' => '',
				'meta_holder' => 'tg-cat-val',
				'std' => '',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'categories_child',
				'name' => __( 'Child Terms', 'tg-text-domain' ),
				'desc' => __( 'Whether or not to include children for hierarchical taxonomies.', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'checkbox',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_cats_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_page_id_start',
				'name' => __( 'Page Filter', 'tg-text-domain'   ),
				'desc' => __( 'Filter the page to display in the grid', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type'),
					array($prefix.'post_type', 'contains', 'page')
				)
			),
			array(
				'id'   => $prefix.'pages_id',
				'name' => __( 'Available page(s)', 'tg-text-domain'  ),
				'desc' => __( 'Select the page to display in the grid.', 'tg-text-domain' ),
				'sub_desc' => '<strong>'.__( '* If no page selected then all pages will be displayed', 'tg-text-domain' ).'</strong>',
				'type' => 'multiselect',
				'placeholder' => __( 'Select page(s)', 'tg-text-domain' ),
				'width' => 410,
				'options' => $the_grid_base->get_all_page_id(),
				'std' => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'post_type', 'contains', 'page'),
				)
			),
			array(
				'id'   => 'section_page_id_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'post_type', 'contains', 'page'),
				)
			),
			array(
				'id'   => 'section_author_start',
				'name' => __( 'Authors Filter', 'tg-text-domain' ),
				'desc' => __( 'Filter the grid by author(s)', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type')
				)
			),			
			array(
				'id'   => $prefix.'author',
				'name' => __( 'Author(s)', 'tg-text-domain' ),
				'desc' => __( 'Will display selected author(s) for the current grid post type(s)', 'tg-text-domain' ),
				'sub_desc' => '<strong>'.__( '* If no author selected then all authors will be displayed', 'tg-text-domain' ).'</strong>',
				'std' => '',
				'type' => 'multiselect',
				'clear' => true,
				'placeholder' => __( 'Select an author(s)', 'tg-text-domain' ),
				'width' => 230,
				'options' => $the_grid_base->get_all_users(),
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_author_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_item_exclude_start',
				'name' => __( 'Exclude Item(s)', 'tg-text-domain' ),
				'desc' => __( 'Exclude item from the current grid', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type')
				)
			),
			array(
				'id'   => $prefix.'post_not_in',
				'name' => __( 'Exclude Items From Grid', 'tg-text-domain' ),
				'desc' => __( 'Enter post ID(s) to exclude from the current grid.', 'tg-text-domain' ).'<strong><br>'.__( 'You can also easly exclude item(s) thanks to the grid preview and eye icon.', 'tg-text-domain' ).'<br>'.__( 'It will automatically fill this text input.', 'tg-text-domain' ).'</strong>',
				'sub_desc' =>  '<strong>'.__( '* Add post IDs separated by a comma (e.g: 56, 4, 66, 34, 56, 6).', 'tg-text-domain' ).'</strong>',
				'type' => 'text',
				'disabled' => false,
				'std' => '',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_item_exclude_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_order_start',
				'name' => __( 'Ordering', 'tg-text-domain' ),
				'desc' => __( 'Order your grid content by parameters', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type')
				)
			),
			array(
				'id'   => $prefix.'order',
				'name' => __( 'Order', 'tg-text-domain'  ),
				'sub_desc' => '',
				'desc' => __( 'Designates the ascending or descending order of the \'orderby\' parameter', 'tg-text-domain'  ),
				'type' => 'select',
				'placeholder' => __( 'Select an order', 'tg-text-domain' ),
				'width' => 180,
				'options' => array(
					'ASC' => __( 'ascending', 'tg-text-domain' ),
					'DESC' => __( 'descending', 'tg-text-domain' )
				),
				'std' => 'DESC',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'orderby',
				'name' => __( 'Order By', 'tg-text-domain'  ),
				'sub_desc' => '',
				'desc' => __( 'Sort retrieved posts by parameter. Defaults to \'date (post_date)\'.', 'tg-text-domain'  ),
				'type' => 'multiselect',
				'placeholder' => __( 'Select an order by', 'tg-text-domain' ),
				'width' => 280,
				'options' => array(
					'none' => __( 'No order', 'tg-text-domain' ),
					'ID' => __( 'By post id', 'tg-text-domain' ),
					'author' => __( 'By author', 'tg-text-domain' ),
					'title' => __( 'By title', 'tg-text-domain' ),
					'name' => __( 'By post name (post slug)', 'tg-text-domain' ),
					'date' => __( 'By date', 'tg-text-domain' ),
					'modified' => __( 'By last modified date', 'tg-text-domain' ),
					'parent' => __( 'By post/page parent id', 'tg-text-domain' ),
					'rand' => __( 'Random order', 'tg-text-domain' ),
					'comment_count' => __( 'By number of comments', 'tg-text-domain' ),
					'menu_order' => __( 'By Page Order (Menu Order)', 'tg-text-domain' ),
					'meta_value' => __( 'By Meta Value', 'tg-text-domain' ),
					'meta_value_num' => __( 'By numeric meta value', 'tg-text-domain' ),
					'post__in' => __( 'Preserve post ID order', 'tg-text-domain' ),
					'woocommerce_recently_viewed' => __( 'Recently Viewed Products', 'tg-text-domain' )
				),
				'std' => '',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'orderby_id',
				'name' => __( 'Post In (Order by ID)', 'tg-text-domain' ),
				'desc' => __( 'Please add the desired post ID(s) to be included in the grid.', 'tg-text-domain' ),
				'sub_desc' =>  '<strong>'.__( '* Add post IDs separated by a comma (e.g: 56, 4, 66, 34, 56, 6).', 'tg-text-domain' ).'</strong><br>'.__( 'Will only works if page & categories are not selected at the same time.', 'tg-text-domain' ),
				'type' => 'text',
				'disabled' => false,
				'std' => '',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'orderby', 'contains', 'post__in'),
				)
			),
			array(
				'id'   => $prefix.'meta_key',
				'name' => __( 'Order by a meta key value', 'tg-text-domain' ),
				'desc' => __( 'Enter a meta key name', 'tg-text-domain' ),
				'sub_desc' =>  '',
				'std' => '',
				'type' => 'text',
				'width' => 230,
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'orderby', 'contains', 'meta_value')
				)
			),
			array(
				'id'   => 'section_order_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_metakey_start',
				'name' => __( 'Meta Key Filter', 'tg-text-domain' ),
				'desc' => __( 'Filter your post types by custom meta keys', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type')
				)
			),
			array(
				'id'   => $prefix.'metakey_relation',
				'name' =>  __( 'Relation', 'tg-text-domain' ),
				'sub_desc' => '',
				'desc' => '',
				'type' => 'select',
				'placeholder' => ' ',
				'width' => 80,
				'clear' => false,
				'options' => array(
					'OR' => __( 'Or', 'tg-text-domain'  ),
					'AND' => __( 'And', 'tg-text-domain'  )
				),
				'std' => 'AND',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'metakey',
				'name' => __( 'Meta Key Name', 'tg-text-domain' ),
				'desc' => __( 'A meta key name must be informed in order to be takken into account', 'tg-text-domain' ),
				'sub_desc' =>  '',
				'std' => '',
				'type' => 'text',
				'width' => 230,
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'metakey_compare',
				'name' => __( 'Meta Key Compare', 'tg-text-domain'  ),
				'sub_desc' => '',
				'desc' => __( 'Select an operator to compare with the meta key value', 'tg-text-domain'  ),
				'type' => 'select',
				'placeholder' => __( 'Select an order by', 'tg-text-domain' ),
				'width' => 230,
				'clear' => false,
				'options' => array(
					'=' => __( 'Equals (=)', 'tg-text-domain'  ),
					'!=' => __( 'Does not equal (!=)', 'tg-text-domain'  ),
					'>' => __( 'Greater than (>)', 'tg-text-domain'  ),
					'>=' => __( 'Greater than or equal to (>=)', 'tg-text-domain'  ),
					'<' => __( 'Less than (&lt;)', 'tg-text-domain'  ),
					'<=' => __( 'Less than or equal to (&lt;=)', 'tg-text-domain'  ),
					'LIKE' => __( 'Like', 'tg-text-domain'  ),
					'NOT LIKE' => __( 'Not like', 'tg-text-domain'  ),
					'IN' => __( 'In', 'tg-text-domain'  ),
					'NOT IN' => __( 'Not in', 'tg-text-domain'  ),
					'BETWEEN' => __( 'Between', 'tg-text-domain'  ),
					'NOT BETWEEN' => __( 'Not between', 'tg-text-domain'  ),
					'NOT EXISTS' => __( 'Not exist', 'tg-text-domain'  ),
				),
				'std' => '=',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'metakey_value',
				'name' => __( 'Meta Key Value', 'tg-text-domain' ),
				'desc' => __( 'Add the meta key value to compare with', 'tg-text-domain' ),
				'sub_desc' =>  '',
				'type' => 'text',
				'disabled' => false,
				'std' => '',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'metakey_type',
				'name' => __( 'Meta Key Type', 'tg-text-domain'  ),
				'sub_desc' => '',
				'desc' => __( 'Custom meta key field content type.', 'tg-text-domain'  ),
				'type' => 'select',
				'placeholder' => __( 'Select a field type', 'tg-text-domain' ),
				'width' => 220,
				'clear' => true,
				'options' => array(
					'NUMERIC' => __( 'Numeric', 'tg-text-domain'  ),
					'BINARY' => __( 'Binary', 'tg-text-domain'  ),
					'DATE' => __( 'Date', 'tg-text-domain'  ),
					'CHAR' => __( 'Char', 'tg-text-domain'  ),
					'DATETIME' => __( 'Date time', 'tg-text-domain'  ),
					'DECIMAL' => __( 'Decimal', 'tg-text-domain'  ),
					'SIGNED' => __( 'Signed', 'tg-text-domain'  ),
					'TIME' => __( 'Time', 'tg-text-domain'  ),
					'UNSIGNED' => __( 'Unsigned', 'tg-text-domain'  )
				),
				'std' => '',
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'meta_query',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => $custom_fields->grid_meta_key_config(),
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'meta_query_info',
				'name' => '',
				'type' => 'info_box',
				'title' => __( 'Meta Data Info', 'tg-text-domain' ),
				'desc' => __( 'WordPress has the ability to allow post authors to assign custom fields to a post. This arbitrary extra information is known as meta-data.', 'tg-text-domain' ).'<br>'.__( ' Meta-data is handled with key/value pairs. The key is the name of the meta-data element. The value is the information that will appear in the meta-data list on each individual post that the information is associated with', 'tg-text-domain' ).'<br>- <a href="https://codex.wordpress.org/Custom_Fields">'.__( 'Learn more about meta-data', 'tg-text-domain' ).'</a><br>- <a href="https://codex.wordpress.org/Class_Reference/WP_Meta_Query">'.__( 'Learn more about meta query', 'tg-text-domain' ).'</a><br><br>'.__( 'Regretfully, we cannot provide support for meta-data due to the fact that there is simply no way to account for all of the potential variables at play when using another developer\'s plugin or script.', 'tg-text-domain' ).'<br><br>'.__( 'If you are using Woocommerce, you can easly order by a (numeric) meta value with these key name:', 'tg-text-domain' ).'<br>'.__( ' \'_price\' | \'_sale_price\' | \'_sale_price_dates_from\' | \'_sale_price_dates_to\' | \'_stock\'', 'tg-text-domain' ),
				'tab' => __( 'Source', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_metakey_end',
				'type' => 'section_end',
				'tab' => __( 'Source', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons-portfolio"></i>'
			),
			array(
				'id'   => 'section_default_image_start',
				'name' => __( 'Default Image', 'tg-text-domain' ),
				'desc' => __( 'Default image fallback for item in grid mode', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Media', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type')
				)
			),
			array( 
				'id' => $prefix . 'default_image',
				'name' => __('Default Image', 'tg-text-domain'),
				'desc' => __('Add a default image if image is missing.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'image_id',
				'frame_title'   => __( 'Select a default image', 'tg-text-domain'),
				'frame_button'  => __( 'Insert image', 'tg-text-domain' ),
				'button_upload' => __( 'Upload', 'tg-text-domain'),
				'button_remove' => __( 'Remove', 'tg-text-domain'),
				'tab' => __( 'Media', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_default_image_end',
				'type' => 'section_end',
				'tab' => __( 'Media', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_image_format_start',
				'name' => __( 'Image Format', 'tg-text-domain' ),
				'desc' => __( 'Set the image size of the grid item', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Media', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type')
				)
			),
			array(
				'id'   => $prefix.'aqua_resizer',
				'name' => __( 'Smart Resize', 'tg-text-domain' ),
				'desc' => __( 'Resize & crop images uploaded on the fly.', 'tg-text-domain' ),
				'sub_desc' => '<strong>* '.__( 'Don\'t work with CDN image hosting.', 'tg-text-domain' ).'</strong>',
				'type' => 'checkbox',
				'std' => '',
				'tab' => __( 'Media', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '!=', 'justified')
				)
			),
			array(
				'id'   => $prefix.'image_size',
				'name' => __( 'Images Size', 'tg-text-domain'  ),	
				'desc' => __( 'Select a size for image in the grid.', 'tg-text-domain'  ),
				'sub_desc' => '<strong>'.__( '* You also can set the_grid image sizes in', 'tg-text-domain' ).' <a href="'.admin_url( 'admin.php?page=the_grid_global_settings').'" target="_blank">'.__( 'global settings', 'tg-text-domain' ).'</a>.</strong>',
				'type' => 'select',
				'placeholder' => __( 'Select a size', 'tg-text-domain' ),
				'width' => 220,
				'options' => $the_grid_base->get_image_size(),
				'std' => 'full',
				'required' => array(
					array($prefix.'aqua_resizer', '!=', 'true')
				),
				'tab' => __( 'Media', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_image_format_end',
				'type' => 'section_end',
				'tab' => __( 'Media', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_media_content_start',
				'name' => __( 'Media Content', 'tg-text-domain' ),
				'desc' => __( 'Set the kind of media to display in the grid', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Media', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'items_format',
				'name' => __( 'Item format', 'tg-text-domain' ),
				'desc' => __( 'Check the media source (post format) you want to display in the current grid. ', 'tg-text-domain' ),
				'sub_desc' => '<strong>* '. __( 'If everything disabled then it will display only images', 'tg-text-domain' ) .'</strong>',
				'type' => 'checkbox_list',
				'std' => array('gallery','video','audio','quote','link'),
				'options' => array(
					'gallery' =>  __('Gallery', 'tg-text-domain'),
					'video'   =>  __('Video', 'tg-text-domain'),
					'audio'   =>  __('Audio', 'tg-text-domain'),
					'quote'   =>  __('Quote', 'tg-text-domain'),
					'link'   =>  __('Link', 'tg-text-domain')
				),
				'tab' => __( 'Media', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Media', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'gallery_slide_show',
				'name' => __( 'Gallery slide show', 'tg-text-domain' ),
				'desc' => __( 'Enable gallery slide show in each item', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'checkbox',
				'std' => '',
				'tab' => __( 'Media', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'items_format', 'contains', 'gallery'),
				),
			),
			array(
				'id'   => 'section_media_content_end',
				'type' => 'section_end',
				'tab' => __( 'Media', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_lightbox_content_start',
				'name' => __( 'Lightbox Content', 'tg-text-domain' ),
				'desc' => __( 'Display or not media video in the lightbox', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Media', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'video_lightbox',
				'name' => __( 'Video Ligthbox', 'tg-text-domain' ),
				'desc' => __( 'Allows to play video inside lightbox.', 'tg-text-domain' ).'<br>'.__( 'By default, videos will be played inside grid item.', 'tg-text-domain' ).'<br>'.__( '(In preview mode, videos are automatically set in lightbox for performance reasons.)', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'checkbox',
				'tab' => __( 'Media', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_lightbox_content_end',
				'type' => 'section_end',
				'tab' => __( 'Media', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons-format-image"></i>'
			),
			array(
				'id'   => 'section_layout_start',
				'name' => __( 'Grid Type', 'tg-text-domain'   ),
				'desc' => __( 'Style/aspect of items in the grid', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'style',
				'name' => __('Type', 'mobius'),
				'desc' => __('Select a grid type'),
				'type' => 'image_select',
				'std' => 'grid',
				'options' => array (
					array (
						'label' => 'Grid',
						'value' => 'grid',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/grid-layout.png'
					),
					array (
						'label' => 'Masonry',
						'value' => 'masonry',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/masonry-layout.png'
					),
					array (
						'label' => 'Justified',
						'value' => 'justified',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/justified-layout.png'
					),
				),
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),			
			array(
				'id'   => 'section_layout_end',
				'type' => 'section_end',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_item_settings_start',
				'name' => __( 'Items', 'tg-text-domain'   ),
				'desc' => __( 'Items aspect in the grid', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '!=', 'justified')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'item_ratio (X:Y)',
				'name' => __('Item Ratio', 'tg-text-domain'),
				'desc' => __( 'Correspond to the ratio between width and height (X:Y)', 'tg-text-domain' ).'<br><em>'.__('(e.g: 4:3 or 16:9 format)', 'tg-text-domain').'</em>',
				'sub_desc' => '',
				'type' => 'custom',
				'options' => '',
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'grid')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'grid')
				)
			),
			array(
				'id'   => $prefix.'item_x_ratio',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'sign'  => '&nbsp;&nbsp;:&nbsp;&nbsp;',
				'min' => 1,
				'max' => 9999,
				'std' => 1,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'grid')
				)
			),
			array(
				'id'   => $prefix.'item_y_ratio',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'sign'  => '',
				'min' => 1,
				'max' => 9999,
				'std' => 1,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'grid')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'grid')
				)
			),
			array(
				'id'   => $prefix.'item_fitrows',
				'name' => __( 'Item Fit Rows', 'tg-text-domain' ),
				'sub_desc' => '',
				'desc' => __('Items are arranged into rows. Rows progress vertically.', 'tg-text-domain').'<br>'.__('Similar to what you would expect from a classic column layout.', 'tg-text-domain'),
				'type' => 'checkbox',
				'checkbox_title' => '',
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'masonry')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'item_force_size',
				'name' => __( 'Force Item Sizes', 'tg-text-domain' ),
				'sub_desc' => '<strong>'. __( '* This option will override all item sizes set in each post/item', 'tg-text-domain' ) .'</strong>',
				'desc' => __( 'This option will force all items in the grid to have the same size.', 'tg-text-domain' ),
				'type' => 'checkbox',
				'checkbox_title' => '',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'item_sizes',
				'name' => __('Items Size', 'tg-text-domain'),
				'desc' => __( 'Set an unique size of each item in the grid', 'tg-text-domain' ).'<br>',
				'sub_desc' => '',
				'type' => 'custom',
				'options' => '',
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'item_force_size', '==', 'true')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'items_col',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'sign'  => '&nbsp;col(s)',
				'min' => 1,
				'max' => 12,
				'std' => 1,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'item_force_size', '==', 'true')
				)
			),
			array(
				'id'   => $prefix.'items_row',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'sign'  => '&nbsp;row(s)',
				'min' => 1,
				'max' => 12,
				'std' => 1,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'item_force_size', '==', 'true'),
					array($prefix . 'style', '==', 'grid')
				)
			),
			array(
				'id'   => 'section_item_settings_end',
				'type' => 'section_end',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),			
			array(
				'id'   => 'section_columns_start',
				'name' => __( 'Columns / Rows', 'tg-text-domain'   ),
				'desc' => __( 'Set the responsiveness of the grid', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Grid', 'tg-text-domain' ),
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'responsive_settings_title',
				'name' => __( 'Responsive Setting', 'tg-text-domain' ),
				'desc' => __( 'Set the number of columns or the row height (Justified layout) for each device format.', 'tg-text-domain' ).'<br>'. __( 'Each device format correspond to a maximal width in px.', 'tg-text-domain' ),
				'type' => 'custom',
				'options' => '',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'responsive_settings',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' =>
					'<label class="the_grid_resonsive_settings_columns tomb-row" data-tomb-required="the_grid_style,!=,justified">'.__( 'Number of Columns', 'tg-text-domain' ).'</label><label class="the_grid_resonsive_settings_rowheights tomb-row" data-tomb-required="the_grid_style,==,justified">'.__( 'Row Heights', 'tg-text-domain' ).'</label><label class="the_grid_resonsive_settings_gutter">'.__( 'Items Spacings (gutter)', 'tg-text-domain' ).'</label><label class="the_grid_resonsive_settings_widths">'.__( 'Browser Widths', 'tg-text-domain' ).'</label>',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => 'grid_settings_device_img1',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '<img class="tg-grid-settings-device-img" src="'.TG_PLUGIN_URL . 'backend/assets/images/desktop-large.png'.'"><div class="tg-grid-preview-tooltip">'.__( 'Desktop Large', 'tg-text-domain' ).'</div>',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'desktop_large',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'sign' => ' cols',
				'std' => 6,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '!=', 'justified')
				)
			),
			array(
				'id'   => $prefix.'desktop_large_row',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 10,
				'max' => 1000,
				'step' => 1,
				'sign' => 'px ',
				'std' => 240,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'justified')
				)
			),
			array(
				'id'   => $prefix.'gutter',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'min' => -1,
				'max' => 200,
				'step' => 1,
				'sign' => ' px',
				'std' => 0,
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'desktop_large_width',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '<label class="tomb-number-label">'.__( 'Infinity', 'tg-text-domain' ).'</label>',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => 'grid_settings_device_img2',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '<img class="tg-grid-settings-device-img" src="'.TG_PLUGIN_URL . 'backend/assets/images/desktop-medium.png'.'"><div class="tg-grid-preview-tooltip">'.__( 'Desktop Medium', 'tg-text-domain' ).'</div>',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'desktop_medium',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'sign' => ' cols',
				'std' => 5,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '!=', 'justified')
				)
			),
			array(
				'id'   => $prefix.'desktop_medium_row',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 10,
				'max' => 1000,
				'step' => 1,
				'sign' => 'px ',
				'std' => 240,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'justified')
				)
			),
			array(
				'id'   => $prefix.'desktop_medium_gutter',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'min' => -1,
				'max' => 200,
				'step' => 1,
				'sign' => ' px',
				'std' => '',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'desktop_medium_width',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'sign'  => __( 'px', 'tg-text-domain' ),
				'min' => 1,
				'max' => 4000,
				'std' => 1200,
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => 'grid_settings_device_img3',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '<img class="tg-grid-settings-device-img" src="'.TG_PLUGIN_URL . 'backend/assets/images/desktop-small.png'.'"><div class="tg-grid-preview-tooltip">'.__( 'Desktop Small', 'tg-text-domain' ).'</div>',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'desktop_small',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'sign' => ' cols',
				'std' => 4,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '!=', 'justified')
				)
			),
			array(
				'id'   => $prefix.'desktop_small_row',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 10,
				'max' => 1000,
				'step' => 1,
				'sign' => 'px ',
				'std' => 220,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'justified')
				)
			),
			array(
				'id'   => $prefix.'desktop_small_gutter',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'min' => -1,
				'max' => 200,
				'step' => 1,
				'sign' => ' px',
				'std' => '',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'desktop_small_width',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'sign'  => __( 'px', 'tg-text-domain' ),
				'min' => 1,
				'max' => 4000,
				'std' => 980,
				'tab' => __( 'Grid', 'tg-text-domain' )
			),			
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => 'grid_settings_device_img4',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '<img class="tg-grid-settings-device-img" src="'.TG_PLUGIN_URL . 'backend/assets/images/tablet.png'.'"><div class="tg-grid-preview-tooltip">'.__( 'Tablet', 'tg-text-domain' ).'</div>',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'tablet',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'sign' => ' cols',
				'std' => 3,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '!=', 'justified')
				)
			),
			array(
				'id'   => $prefix.'tablet_row',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 10,
				'max' => 1000,
				'step' => 1,
				'sign' => 'px ',
				'std' => 220,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'justified')
				)
			),
			array(
				'id'   => $prefix.'tablet_gutter',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'min' => -1,
				'max' => 200,
				'step' => 1,
				'sign' => ' px',
				'std' => '',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'tablet_width',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'sign'  => __( 'px', 'tg-text-domain' ),
				'min' => 1,
				'max' => 4000,
				'std' => 768,
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => 'Grid'
			),
			array(
				'id'   => 'grid_settings_device_img5',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '<img class="tg-grid-settings-device-img" src="'.TG_PLUGIN_URL . 'backend/assets/images/tablet-small.png'.'"><div class="tg-grid-preview-tooltip">'.__( 'Tablet Small', 'tg-text-domain' ).'</div>',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'tablet_small',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'sign' => ' cols',
				'std' => 2,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '!=', 'justified')
				)
			),
			array(
				'id'   => $prefix.'tablet_small_row',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 10,
				'max' => 1000,
				'step' => 1,
				'sign' => 'px ',
				'std' => 200,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'justified')
				)
			),
			array(
				'id'   => $prefix.'tablet_small_gutter',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'min' => -1,
				'max' => 200,
				'step' => 1,
				'sign' => ' px',
				'std' => '',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'tablet_small_width',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'sign'  => __( 'px', 'tg-text-domain' ),
				'min' => 1,
				'max' => 4000,
				'std' => 480,
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => 'grid_settings_device_img6',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '<img class="tg-grid-settings-device-img" src="'.TG_PLUGIN_URL . 'backend/assets/images/mobile.png'.'"><div class="tg-grid-preview-tooltip">'.__( 'Mobile', 'tg-text-domain' ).'</div>',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'mobile',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'sign' => ' cols',
				'std' => 1,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '!=', 'justified')
				)
			),
			array(
				'id'   => $prefix.'mobile_row',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'slider',
				'label' => '',
				'min' => 10,
				'max' => 1000,
				'step' => 1,
				'sign' => 'px ',
				'std' => 200,
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'justified')
				)
			),
			array(
				'id'   => $prefix.'mobile_gutter',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'min' => -1,
				'max' => 200,
				'step' => 1,
				'sign' => ' px',
				'std' => '',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'mobile_width',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => '',
				'sign'  => __( 'px', 'tg-text-domain' ),
				'min' => 1,
				'max' => 4000,
				'std' => 320,
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),	
			array(
				'id'   => $prefix.'grid_settings_spacer',
				'name' => '',
				'desc' => '',
				'sub_desc' => '<strong>* '.__( 'Empty values or equal to -1 for items spacings will allow to inherit of previous value', 'tg-text-domain' ).'</strong>',
				'type' => 'custom',
				'options' => '',
				'tab' => __( 'Grid', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_columns_end',
				'type' => 'section_end',
				'tab' => __( 'Grid', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons-schedule"></i>'
			),
			array(
				'id'   => 'section_filters_start',
				'name' => __( 'Filter', 'tg-text-domain'   ),
				'desc' => __( 'Organize your filter key like you want', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type')
				)
			),
			array(
				'id'   => $prefix.'filters_title',
				'name' => __( 'Filter(s)', 'tg-text-domain' ),
				'desc' => __( 'Drag and drop available filter items in active filter area.', 'tg-text-domain' ).'<br>'.__( 'You can add unlimited number of filter areas in order to create complex filtering system..', 'tg-text-domain' ).'<br>'.__( 'Each new filter added will appear in the layout tab.', 'tg-text-domain' ),
				'type' => 'custom',
				'options' => '',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'filter_onload',
				'name' => __( 'Filter by on load', 'tg-text-domain'  ),
				'desc' => __( 'Filter the grid by taxonomy/category term(s) on load.', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'multiselect',
				'meta_holder' => 'tg-filter-load',
				'placeholder' => __( 'Select categories', 'tg-text-domain' ),
				'width' => 410,
				'options' => array(),
				'std' => '',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'filter_combination',
				'name' => __( 'Filter Combination', 'tg-text-domain' ),
				'desc' => __( 'Filter by multiple values (e.g: red + green elements)', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'checkbox',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'filter_logic',
				'name' => __('Filter Combination Logic', 'tg-text-domain'),
				'desc' => __('Select a filter logic (for multiple filter selected at the same time).', 'tg-text-domain'),
				'sub_desc' => '<strong>* '.__('AND - Shows elements that meet all selected filters', 'tg-text-domain').'<br>* '.__('OR - Show elements that meet at minimum one of the selected filters', 'tg-text-domain').'</strong>',
				'type' => 'radio',
				'std' => 'AND',
				'options' => array (
						'AND' =>  __('AND', 'tg-text-domain'),
						'OR' =>  __('OR', 'tg-text-domain'),	
				),
				'tab' => __( 'Filter/Sort', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'filter_combination', '==', 'true'),
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'available_filters',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => $custom_fields->available_filters_config(),
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'filters_holder',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '<div class="tg-filters-area"><h3 class="tg-filter-title">'. __( 'Filter', 'tg-text-domain' ) .'<span> - </span><span class="tg-filter-name-area">1</span></h3>',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'filters_order_1',
				'name' => __('Filter(s) order', 'tg-text-domain'),
				'desc' => '',
				'sub_desc' => '',
				'type' => 'select',
				'width' => 240,
				'clear' => true,
				'placeholder' => __('Select an order', 'tg-text-domain'),
				'std' => '',
				'options' => array (
					'alphabetical_asc' =>  __('Alphabetical (ASC)', 'tg-text-domain'),
					'alphabetical_desc' =>  __('Alphabetical (DESC)', 'tg-text-domain'),
					'number_asc' =>  __('Number (ASC)', 'tg-text-domain'),
					'number_desc' =>  __('Number  (DESC)', 'tg-text-domain')
				),
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'filter_type_1',
				'name' => __( 'Filter Type', 'tg-text-domain' ),
				'desc' => '',
				'sub_desc' => '',
				'type' => 'select',
				'width' => 240,
				'std' => 'button',
				'options' => array(
					'button' => __( 'Inline Buttons', 'tg-text-domain' ),
					'dropdown' => __( 'DropDown List', 'tg-text-domain' )
				),
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'filter_dropdown_title_1',
				'name' => __( 'Filter DropDown List Title', 'tg-text-domain' ),
				'desc' => '',
				'sub_desc' =>  '',
				'type' => 'text',
				'disabled' => false,
				'std' => __( 'Filter Categories', 'tg-text-domain' ),
				'force_std' => true,
				'tab' => __( 'Filter/Sort', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'filter_type_1', '==', 'dropdown'),
				)
			),
			array(
				'id'   => $prefix.'filter_all_text_1',
				'name' => __( 'Filter "All" Text', 'tg-text-domain' ),
				'desc' => '',
				'sub_desc' =>  '',
				'type' => 'text',
				'disabled' => false,
				'std' => __( 'All', 'tg-text-domain' ),
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'filter_count_1',
				'name' => __( 'Show Number of Elements', 'tg-text-domain' ),
				'desc' => '',
				'sub_desc' => '',
				'width' => 240,
				'type' => 'select',
				'std' => 'none',
				'options' => array (
					'none'    =>  __('None', 'tg-text-domain'),
					'tooltip' =>  __('Show in Tooltip', 'tg-text-domain'),
					'inline'  =>  __('Show in button', 'tg-text-domain'),	
				),
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'filters',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => $custom_fields->active_filters_config(),
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'filters_end',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '</div>',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'filters_add',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '<div class="tg-add-filters tg-button"><i class="dashicons dashicons-plus"></i>'.__( 'Add filter', 'tg-text-domain' ).'</div>',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_filters_end',
				'type' => 'section_end',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_sorter_start',
				'name' => __( 'Sorting', 'tg-text-domain'   ),
				'desc' => __( 'Add sorters to sort items in the grid', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'sort_by',
				'name' => __( 'Sort By', 'tg-text-domain'  ),
				'desc' => __( 'Select sorter(s) from available sortings', 'tg-text-domain'  ),
				'sub_desc' => '<strong>* '.__('Excerpt will only works if skin have an excerpt.', 'tg-text-domain').'</strong>',
				'type' => 'multiselect',
				'placeholder' => __( 'Select Sortings', 'tg-text-domain' ),
				'width' => 230,
				'clear' => false,
				'options' => $the_grid_base->grid_sorting(),
				'std' => '',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'sort_by_onload',
				'name' => __( 'Sort By on Load', 'tg-text-domain'  ),
				'desc' => __( 'Sort the grid by a value on load.', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'select',
				'placeholder' => __( 'Select a value', 'tg-text-domain' ),
				'width' => 230,
				'clear' => true,
				'options' => $the_grid_base->grid_sorting(),
				'std' => '',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'sort_order_onload',
				'name' => __('Sort Order on Load', 'tg-text-domain'),
				'desc' => __('Sort by ASC (1,2,3;a,b,c) or DESC (3,2,1;c,b,a)', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'radio',
				'std' => 'false',
				'options' => array (
						'false' =>  __('DESC', 'tg-text-domain'),
						'true' =>  __('ASC', 'tg-text-domain'),	
				),
				'tab' => __( 'Filter/Sort', 'tg-text-domain' ),
			),
			array(
				'type' => 'break',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'sort_by_text',
				'name' => __( 'Sort By Text', 'tg-text-domain' ),
				'desc' => __( 'Enter the default text used in the sort dropdown list', 'tg-text-domain' ),
				'sub_desc' =>  '',
				'type' => 'text',
				'disabled' => false,
				'std' => __( 'Sort By', 'tg-text-domain' ),
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_sorter_end',
				'type' => 'section_end',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_search_start',
				'name' => __( 'Search', 'tg-text-domain'   ),
				'desc' => __( 'Search form to search inside each item strings', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'search_text',
				'name' => __( 'Search Text', 'tg-text-domain' ),
				'desc' => __( 'Enter the default text used in the search bar', 'tg-text-domain' ),
				'sub_desc' =>  '',
				'type' => 'text',
				'disabled' => false,
				'std' => __( 'Search...', 'tg-text-domain' ),
				'tab' => __( 'Filter/Sort', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_search_end',
				'type' => 'section_end',
				'tab' => __( 'Filter/Sort', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons-admin-settings"></i>'
			),
			array(
				'id'   => 'section_pagination_start',
				'name' => __( 'Pagination', 'tg-text-domain'   ),
				'desc' => __( 'Pagination settings', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Pagination', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'pagination',
				'name' => __( 'Pagination', 'tg-text-domain'   ),
				'desc' => __( 'Pagination allows to you to add a real pagination system to the grid.', 'tg-text-domain' ).'<br>'.__( 'It\'s really usefull to create a portfolio page.', 'tg-text-domain' ),
				'type' => 'custom',
				'options' => '',
				'tab' => __( 'Pagination', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Pagination', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'ajax_pagination',
				'name' => __( 'Ajax pagination', 'tg-text-domain' ),
				'desc' => __( 'Load only new page item(s) with ajax instead of reloading the whole page.', 'tg-text-domain' ).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'sub_desc' => '<strong>'. __( '* In preview mode, ajax is always enabled to avoid reloading the current page.', 'tg-text-domain' ) .'</strong>',
				'type' => 'checkbox',
				'checkbox_title' => '',
				'tab' => __( 'Pagination', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Pagination', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'pagination_type',
				'name' => __( 'Pagination Type', 'tg-text-domain' ),
				'desc' => __( 'Select a pagination type.', 'tg-text-domain' ) .'&nbsp;&nbsp;&nbsp;&nbsp;',
				'sub_desc' => '',
				'type' => 'radio',
				'std' => 'number',
				'options' => array(
					'number' => __( 'Page Numbers', 'tg-text-domain' ),
					'button' => __( 'Prev/Next Buttons', 'tg-text-domain' )
				),
				'tab' => __( 'Pagination', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'ajax_pagination', '!=', 'true')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Pagination', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'pagination_prev_next',
				'name' => __( 'Prev/Next Buttons', 'tg-text-domain' ),
				'desc' => __( 'Include the previous and next links in the list or not.', 'tg-text-domain' ).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'sub_desc' => '',
				'type' => 'checkbox',
				'checkbox_title' => '',
				'std' => '',
				'tab' => __( 'Pagination', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'pagination_type', '==', 'number'),
					array($prefix . 'ajax_pagination', '!=', 'true')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Pagination', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'pagination_show_all',
				'name' => __( 'Show All Pages', 'tg-text-domain' ),
				'desc' => __( 'Will show all of the pages instead of a short list of the pages.', 'tg-text-domain' ).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'sub_desc' => '',
				'type' => 'checkbox',
				'checkbox_title' => '',
				'std' => '',
				'tab' => __( 'Pagination', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'pagination_type', '==', 'number'),
					array($prefix . 'ajax_pagination', '!=', 'true')
				)
			),
			array(
				'id'   => $prefix.'pagination_mid_size',
				'name' => __( 'Middle Size', 'tg-text-domain' ),
				'desc' => __( 'Number of page links around the current page to display.', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'number',
				'label' => '',
				'sign'  => '',
				'min' => 0,
				'max' => 50,
				'std' => 2,
				'tab' => __( 'Pagination', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'pagination_type', '==', 'number'),
					array($prefix . 'pagination_show_all', '!=', 'true'),
					array($prefix . 'ajax_pagination', '!=', 'true')
				)
			),
			array(
				'id'   => $prefix.'pagination_end_size',
				'name' => __( 'End Size', 'tg-text-domain' ),
				'desc' => __( 'Number of page links to either side of current page.', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'number',
				'label' => '',
				'sign'  => '',
				'min' => 0,
				'max' => 50,
				'std' => 2,
				'tab' => __( 'Pagination', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'pagination_type', '==', 'number'),
					array($prefix . 'pagination_show_all', '!=', 'true'),
					array($prefix . 'ajax_pagination', '!=', 'true')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Pagination', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'pagination_prev',
				'name' => __('Prev Button Text', 'tg-text-domain'),
				'desc' => __('Type the the prev button text.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'text',
				'force_std' => true,
				'std' => __('&#171; Prev', 'tg-text-domain'),
				'tab' => __( 'Pagination', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'ajax_pagination', '!=', 'true')
				)
			),
			array(
				'id' => $prefix . 'pagination_next',
				'name' => __('Next Button Text', 'tg-text-domain'),
				'desc' => __('Type the next button text.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'text',
				'force_std' => true,
				'std' => __('Next &#187;', 'tg-text-domain'),
				'tab' => __( 'Pagination', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'ajax_pagination', '!=', 'true')
				)
			),
			array(
				'id'   => 'section_pagination_end',
				'type' => 'section_end',
				'tab' => __( 'Pagination', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons-editor-kitchensink"></i>',
			),	
			array(
				'id'   => 'section_grid_layout_start',
				'name' => __( 'Grid Composer', 'tg-text-domain'   ),
				'desc' => __( 'Drag and drop element to compose your custom grid layout', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'grid_layout',
				'name' => __( 'Grid Composer', 'tg-text-domain'   ),
				'desc' => __( 'Simply Drag and drop elements to compose your custom grid layout.', 'tg-text-domain' ). '<br>'. __( 'In each area you can set margin, padding, and background style.', 'tg-text-domain' ),
				'type' => 'custom',
				'options' => $custom_fields->grid_layout_config(),
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_grid_layout_end',
				'type' => 'section_end',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_layout2_start',
				'name' => __( 'Grid layout', 'tg-text-domain'   ),
				'desc' => '',
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'layout',
				'name' => __('Layout', 'tg-text-domain'),
				'desc' => __('Select the grid layout.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'image_select',
				'std' => 'vertical',
				'options' => array (
					array (
						'label' => __('Vertical', 'tg-text-domain'),
						'value' => 'vertical',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/vertical-layout.png'
					),
					array (
						'label' => __('Horizontal', 'tg-text-domain'),
						'value' => 'horizontal',
						'image' => TG_PLUGIN_URL . 'backend/assets/images/horizontal-layout.png'
					),
				),
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'rtl',
				'name' => __( 'Right To Left (RTL)', 'tg-text-domain' ),
				'desc' => __( 'RTL layout for all grid items.', 'tg-text-domain' ).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'sub_desc' => '',
				'type' => 'checkbox',
				'checkbox_title' => '',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'grid_margin',
				'name' => __('Grid Margin', 'tg-text-domain'),
				'desc' => __( 'Add margin to the whole grid (negative values are working)', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'title',
				'options' => '',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'wrap_marg_left',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => __( 'Left:', 'tg-text-domain' ).'&nbsp;&nbsp;&nbsp;',
				'sign'  => '&nbsp;'.__( 'px', 'tg-text-domain' ),
				'min' => -1000,
				'max' => 1000,
				'step' => 1,
				'std' => 0,
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'wrap_marg_top',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => __( 'Top:', 'tg-text-domain' ).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'sign'  => '&nbsp;'.__( 'px', 'tg-text-domain' ),
				'min' => -1000,
				'max' => 1000,
				'step' => 1,
				'std' => 0,
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'wrap_marg_right',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => __( 'Right:', 'tg-text-domain' ),
				'sign'  => '&nbsp;'.__( 'px', 'tg-text-domain' ),
				'min' => -1000,
				'max' => 1000,
				'step' => 1,
				'std' => 0,
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'wrap_marg_bottom',
				'name' => '',
				'sub_desc' => '',
				'desc' => '',
				'type' => 'number',
				'label' => __( 'Bottom:', 'tg-text-domain' ),
				'sign'  => '&nbsp;'.__( 'px', 'tg-text-domain' ),
				'min' => -1000,
				'max' => 1000,
				'step' => 1,
				'std' => 0,
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'grid_background',
				'name' => __('Grid Background Color', 'tg-text-domain'),
				'desc' => __('Choose a background color for the grid.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'rgba' => true,
				'std' => '',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'full_width',
				'name' => __( 'Full Width', 'tg-text-domain' ),
				'desc' => __( 'Force the grid to fill the current window width.', 'tg-text-domain' ).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
				'sub_desc' => '<strong>'. __( '* All areas will be also in full width mode', 'tg-text-domain' ) .'</strong>',
				'type' => 'checkbox',
				'checkbox_title' => '',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'full_height',
				'name' => __( 'Full Height', 'tg-text-domain' ),
				'desc' => __( 'Force the grid to fill the current window height.', 'tg-text-domain' ),
				'sub_desc' => '<strong>'. __( '* It will override item ratio aspect.', 'tg-text-domain' ) .'</strong>',
				'type' => 'checkbox',
				'checkbox_title' => '',
				'tab' => __( 'Layout', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '==', 'grid'),
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'id'   => 'section_layout2_end',
				'type' => 'section_end',
				'tab' => __( 'Layout', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_slider_start',
				'name' => __( 'Grid Slider', 'tg-text-domain'   ),
				'desc' => __( 'Slider settings of the grid in horizontal mode', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Layout', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'id'   => $prefix.'row_nb',
				'name' => __( 'Number of rows', 'tg-text-domain' ),
				'desc' => __( 'Set the number of rows in the slider.', 'tg-text-domain' ),
				'sub_desc' => '<strong>* '.__( 'Smart Navigation only available for 1 row', 'tg-text-domain' ).'</strong>',
				'type' => 'slider',
				'label' => '',
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'sign' => ' rows',
				'std' => 1,
				'tab' => __( 'Layout', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'style', '!=', 'masonry'),
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'id'   => $prefix.'slider_swingSpeed',
				'name' => __( 'Slider Speed', 'tg-text-domain' ),
				'desc' => __( 'Animations speed of the slider on release.', 'tg-text-domain' ),
				'sub_desc' => '<strong>* '.__( 'Swing synchronization speed, where: 1 = instant, 0 = infinite', 'tg-text-domain' ) .'</strong>',
				'type' => 'slider',
				'label' => '',
				'min' => 0.01,
				'max' => 1,
				'step' => 0.01,
				'sign' => '',
				'std' => 0.1,
				'tab' => __( 'Layout', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Layout', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'id'   => $prefix.'slider_itemNav',
				'name' => __( 'Item Navigation', 'tg-text-domain' ),				
				'desc' => __( 'Allows to snap items to the border of the slider.', 'tg-text-domain' ) .'&nbsp;&nbsp;&nbsp;&nbsp;',
				'sub_desc' => '<strong>* '. __( 'Free mode, if no navigation selected', 'tg-text-domain' ) .'</strong>',
				'type' => 'select',
				'clear' => true,
				'placeholder' => __( 'Type of navigation', 'tg-text-domain' ),
				'width' => 180,
				'options' => array(
					'basic' => __( 'Basic', 'tg-text-domain' ),
					'centered' => __( 'Centered', 'tg-text-domain' ),
					'forceCentered' => __( 'Force Centered', 'tg-text-domain' ),
				),
				'tab' => __( 'Layout', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal')
				)
			),
			array(
				'id'   => $prefix.'slider_startAt',
				'name' => __( 'Slider Start At', 'tg-text-domain' ),
				'desc' => __( 'Starting position in the slider in items.', 'tg-text-domain' ),
				'sub_desc' => '<strong>* '.__( 'Corresponding to the page bullet number position', 'tg-text-domain' ).'</strong>',
				'type' => 'slider',
				'label' => '',
				'min' => 1,
				'max' => 20,
				'step' => 1,
				'sign' => '',
				'std' => 0,
				'tab' => __( 'Layout', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
					array($prefix . 'slider_itemNav', 'contains', 'c')
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Layout', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'id'   => $prefix.'slider_autoplay',
				'name' => __( 'Slider Auto Play', 'tg-text-domain' ),
				'desc' => __( 'Enable automatic cycling by page in the grid', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'checkbox',
				'checkbox_title' => '',
				'tab' => __( 'Layout', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'id'   => $prefix.'slider_cycleInterval',
				'name' => __( 'Slider Auto Play Speed', 'tg-text-domain' ),
				'desc' => __( 'Animations speed of the slider.', 'tg-text-domain' ),
				'sub_desc' => __( 'Delay between cycles in milliseconds (autoplay speed)', 'tg-text-domain' ),
				'type' => 'slider',
				'label' => '',
				'min' => 0,
				'max' => 60000,
				'step' => 50,
				'sign' => 'ms',
				'std' => 5000,
				'tab' => __( 'Layout', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
					array($prefix . 'slider_autoplay', '==', 'true'),
				)
			),
			array(
				'id'   => 'section_slider_end',
				'type' => 'section_end',
				'tab' => __( 'Layout', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons-welcome-widgets-menus"></i>',
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'id'   => 'section_skins_start',
				'name' => __( 'Item Skins', 'tg-text-domain'   ),
				'desc' => __( 'Choose an skin to apply to the current grid items.', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'item_skin_post',
				'name' => __( 'Item Skin', 'tg-text-domain'  ),	
				'desc' => __( 'Choose a skin for each post type added as a source in the grid.', 'tg-text-domain'  ).'<br>'.__( 'Select a post type and assign a skin to it . The grid support multiple skin per grid (one per post type).', 'tg-text-domain'  ),
				'sub_desc' => '',
				'type' => 'custom',
				'placeholder' => __( 'Select a post type', 'tg-text-domain' ),
				'width' => 210,
				'options' => $custom_fields->grid_skin_post_type(),
				'std' => 'post',
				'tab' => __( 'Skins', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '==', 'post_type')
				)
			),
			array(
				'id'   => $prefix.'item_skin_social',
				'name' => __( 'Item Skin', 'tg-text-domain'  ),	
				'desc' => __( 'Choose a skin for the current social media.', 'tg-text-domain'  ),
				'sub_desc' => '',
				'type' => 'custom',
				'options' => '<input type="hidden" class="the_grid_social_skin" name="the_grid_social_skin" value="'.get_post_meta($post_ID, $prefix.'social_skin', true).'">',
				'tab' => __( 'Skins', 'tg-text-domain' ),
				'required' => array(
					array($prefix.'source_type', '!=', 'post_type')
				)
			),
			array(
				'id' => $prefix . 'item_skin_holder',
				'name' => '',
				'desc' => '',
				'sub_desc' => '',
				'type' => 'custom',
				'options' => '<div id="tg-grid-skins"><div id="tg-grid-skins-loading" class="loading-anim"></div></div>',
				'std' => 'standard',
				'tab' => __( 'Skins', 'tg-text-domain' ),
			),
			array(
				'type' => 'break',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'skin_content_background',
				'name' => __('Content Background Color', 'tg-text-domain'),
				'desc' => __('Choose a background color for main content holder (masonry).', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'rgba' => true,
				'std' => '#ffffff',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'skin_content_color',
				'name' => __( 'Content Color Scheme', 'tg-text-domain' ),
				'desc' => __( 'Select a color scheme for the text content.', 'tg-text-domain' ) .'&nbsp;&nbsp;&nbsp;&nbsp;',
				'sub_desc' => '',
				'type' => 'radio',
				'std' => 'dark',
				'options' => array(
					'light' => __( 'Light', 'tg-text-domain' ),
					'dark' => __( 'Dark', 'tg-text-domain' )
				),
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'skin_overlay_background',
				'name' => __('Overlay Background Color', 'tg-text-domain'),
				'desc' => __('Choose a background color for the overlay (over image content).', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'rgba' => true,
				'std' => 'rgba(22,22,22,0.65)',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'skin_overlay_color',
				'name' => __( 'Overlay Color Scheme', 'tg-text-domain' ),
				'desc' => __( 'Select a color scheme for the overlay.', 'tg-text-domain' ) .'&nbsp;&nbsp;&nbsp;&nbsp;',
				'sub_desc' => '',
				'type' => 'radio',
				'std' => 'light',
				'options' => array(
					'light' => __( 'Light', 'tg-text-domain' ),
					'dark' => __( 'Dark', 'tg-text-domain' )
				),
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_skins_color_end',
				'type' => 'section_end',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_nav_skins_start',
				'name' => __( 'Navigation Skins', 'tg-text-domain'   ),
				'desc' => __( 'Choose an skin to apply to all navigation element (button, filters,etc...).', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'navigation_style',
				'name' => __('Navigation Style', 'tg-text-domain'),
				'desc' => __('Choose a navigation style', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'select',
				'placeholder' => __( 'Select a style', 'tg-text-domain' ),
				'width' => 200,
				'options' => $navigation_base->get_navigation_name(),
				'std' => 'tg-txt',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'navigation_color',
				'name' => __('Navigation Text Color', 'tg-text-domain'),
				'desc' => __('Choose a text color.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'std' => '#999999',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'navigation_accent_color',
				'name' => __('Navigation Text Accent Color', 'tg-text-domain'),
				'desc' => __('Choose an accent text color (hover/active).', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'std' => '#ff6863',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'navigation_bg',
				'name' => __('Navigation Background Color', 'tg-text-domain'),
				'desc' => __('Choose a background color.', 'tg-text-domain'),
				'sub_desc' => '<strong>* '.__('Not all navigation style support background color.', 'tg-text-domain').'</strong>',
				'type' => 'color',
				'rgba' => true,
				'std' => '#999999',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'navigation_accent_bg',
				'name' => __('Navigation Background Accent Color', 'tg-text-domain'),
				'desc' => __('Choose an accent background color (hover/active).', 'tg-text-domain'),
				'sub_desc' => '<strong>* '.__('Not all navigation style support background color.', 'tg-text-domain').'</strong>',
				'type' => 'color',
				'rgba' => true,
				'std' => '#ff6863',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			
			array(
				'type' => 'break',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'dropdown_color',
				'name' => __('DropDown List Color', 'tg-text-domain'),
				'desc' => __('Choose a text color.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'std' => '#777777',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'dropdown_bg',
				'name' => __('DropDown List Background', 'tg-text-domain'),
				'desc' => __('Choose a background color.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'rgba' => true,
				'std' => '#ffffff',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'dropdown_hover_color',
				'name' => __('DropDown List Color Hover/Active', 'tg-text-domain'),
				'desc' => __('Choose a text color on over.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'std' => '#444444',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'dropdown_hover_bg',
				'name' => __('DropDown List Background Hover/Active', 'tg-text-domain'),
				'desc' => __('Choose a background color on hover/active.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'rgba' => true,
				'std' => '#f5f6fa',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Skins', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'navigation_arrows_color',
				'name' => __('Slider Arrow Color', 'tg-text-domain'),
				'desc' => __('Will be only applied on the left/right slider arrows.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'std' => '',
				'tab' => __( 'Skins', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'id' => $prefix . 'navigation_arrows_bg',
				'name' => __('Slider Arrow Background', 'tg-text-domain'),
				'desc' => __('Will be only applied on the left/right slider arrows.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'rgba' => true,
				'std' => '',
				'tab' => __( 'Skins', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'id' => $prefix . 'navigation_bullets_color',
				'name' => __('Slider Bullet Color', 'tg-text-domain'),
				'desc' => __('Choose a color of bullet slider.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'rgba' => true,
				'std' => '#DDDDDD',
				'tab' => __( 'Skins', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'id' => $prefix . 'navigation_bullets_color_active',
				'name' => __('Slider Bullet Color Active', 'tg-text-domain'),
				'desc' => __('Choose a color of the active bullet slider.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'rgba' => true,
				'std' => '#59585b',
				'tab' => __( 'Skins', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'layout', '==', 'horizontal'),
				)
			),
			array(
				'id'   => 'section_nav_skins_end',
				'type' => 'section_end',
				'tab' => __( 'Skins', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons dashicons-art"></i>'
			),
			array(
				'id'   => 'section_animation_start',
				'name' => __( 'Item Animations', 'tg-text-domain'   ),
				'desc' => __( 'Set the animation style and duration.', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Animations', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'animation_data',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '<div class="tg-data-amin" data-item-anim=\''.json_encode($animation_name->get_animation_name()).'\'></div>',
				'tab' => __( 'Animations', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'animation',
				'name' => __( 'Animation Style', 'tg-text-domain'  ),
				'sub_desc' => '',
				'desc' => __( 'Select an animation for the grid items', 'tg-text-domain'  ),
				'type' => 'select',
				'placeholder' => __( 'Select an animation', 'tg-text-domain' ),
				'width' => 280,
				'options' => $animation_name->get_animation_arr(),
				'std' => 'fade_in',
				'tab' => __( 'Animations', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Animations', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'transition',
				'name' => __( 'Animation duration', 'tg-text-domain' ),
				'desc' => __( 'This option corresponds to the duration of the transition when items change of position.', 'tg-text-domain' ),
				'sub_desc' => '<strong>'.__( '* (1000ms = 1s)', 'tg-text-domain' ).'</strong>',
				'type' => 'slider',
				'label' => '',
				'min' => 0,
				'max' => 3000,
				'step' => 10,
				'sign' => ' ms',
				'std' => 700,
				'tab' => __( 'Animations', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Animations', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'animation_preview',
				'name' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => '<div id="tg-animation-preview-button" class="tg-button">'.__( 'Preview', 'tg-text-domain' ).'</div><div id="tg-animation-preview"></div>',
				'tab' => __( 'Animations', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_animation_end',
				'type' => 'section_end',
				'tab' => __( 'Animations', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons-editor-expand"></i>'
			),				
			array(
				'id'   => 'section_ajax_start',
				'name' => __( 'Load More', 'tg-text-domain'   ),
				'desc' => __( 'Settings for loading more items with ajax (with a button or on scroll)', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'ajax_method',
				'name' => __('Ajax Method', 'tg-text-domain'),
				'desc' => __('Select a method to load more item with ajax.', 'tg-text-domain'),
				'sub_desc' => '<strong>'.__('* Ajax on scroll is not available with horizontal layout (slider)', 'tg-text-domain').'</strong>',
				'type' => 'radio',
				'std' => 'load_more',
				'options' => array (
						'load_more' =>  __('On Click', 'tg-text-domain'),
						'on_scroll' =>  __('On Scroll', 'tg-text-domain'),	
				),
				'tab' => __( 'Load/Ajax', 'tg-text-domain' ),
			),
			array(
				'type' => 'break',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'ajax_button_text',
				'name' => __('Button Text', 'tg-text-domain'),
				'desc' => __('Type the text that will appear inside the button.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'text',
				'std' => __('Load More', 'tg-text-domain'),
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'ajax_button_loading',
				'name' => __('Loading Text', 'tg-text-domain'),
				'desc' => __('Type the text that will appear during loading.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'text',
				'std' => __('Loading...', 'tg-text-domain'),
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'ajax_button_no_more',
				'name' => __('No More Item Text', 'tg-text-domain'),
				'desc' => __('Type the text that will appear when all items are loaded.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'text',
				'std' => __('No more item', 'tg-text-domain'),
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'ajax_items_remain',
				'name' => __('Item Number Remaining', 'tg-text-domain'),
				'desc' => __('Allows to display the number of items remaining.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'checkbox',
				'std' => '',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'ajax_item_number',
				'name' => __( 'Load More Item Number', 'tg-text-domain' ),
				'desc' => __( 'Number of items to load with ajax.', 'tg-text-domain' ),
				'sub_desc' => '<strong>'.__( '* Works with load more button and ajax scroll', 'tg-text-domain' ).'</strong>',
				'type' => 'number',
				'label' => '',
				'sign'  => '',
				'min' => 1,
				'max' => 50,
				'std' => 4,
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'ajax_item_delay',
				'name' => __( 'Load More Item Delay', 'tg-text-domain' ),
				'desc' => __( 'Delay in ms between each new item loaded in the grid.', 'tg-text-domain' ),
				'sub_desc' => '<strong>'.__( '* If no delay is set (0ms), then all new items will appear at the same time.', 'tg-text-domain' ).'</strong>',
				'type' => 'slider',
				'label' => '',
				'sign'  => 'ms',
				'step' => 10,
				'min' => 0,
				'max' => 1000,
				'std' => 100,
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_ajax_end',
				'type' => 'section_end',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_load_start',
				'name' => __( 'Pre-loader', 'tg-text-domain'   ),
				'desc' => __( 'Allows to wait all items in the grid are loaded before reveal the grid', 'tg-text-domain' ),
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'id' => $prefix . 'preloader',
				'name' => __('Grid Preloader', 'tg-text-domain'),
				'desc' => __('Preload all items (images) before to display the grid.', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'checkbox',
				'std' => '',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'type' => 'break',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'preloader', '==', 'true'),
				)
			),
			array(
				'id' => $prefix . 'preloader_style',
				'name' => __('Preloader Style', 'tg-text-domain'),
				'desc' => __('Choose a preloader animation style', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'select',
				'placeholder' => __( 'Select an animation', 'tg-text-domain' ),
				'width' => 200,
				'options' => $preloader_base->get_preloader_name(),
				'std' => 'square-grid-pulse',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'preloader', '==', 'true'),
				)
			),
			array(
				'id' => $prefix . 'preloader_color',
				'name' => __('Preloader Color', 'tg-text-domain'),
				'desc' => __('Choose a preloader color', 'tg-text-domain'),
				'sub_desc' => '',
				'type' => 'color',
				'std' => '#34495e',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'preloader', '==', 'true'),
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'preloader', '==', 'true'),
				)
			),
			array(
				'id' => $prefix . 'preloader_preview',
				'name' => '',
				'desc' => '',
				'sub_desc' => '',
				'type' => 'custom',
				'options' => $custom_fields->preloader_config(),
				'std' => '',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'preloader', '==', 'true'),
				)
			),
			array(
				'id'   => $prefix.'preloader_size',
				'name' => __( 'Preloader Size', 'tg-text-domain' ),
				'desc' => __( 'Increase or reduce size of the preloader.', 'tg-text-domain' ),
				'sub_desc' => '<strong>'.__( '* Increase size can degrade preloader quality.', 'tg-text-domain' ).'</strong>',
				'type' => 'slider',
				'label' => '',
				'sign'  => 'X',
				'step' => 0.01,
				'min' => 0.10,
				'max' => 2.00,
				'std' => 1.00,
				'tab' => __( 'Load/Ajax', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'preloader', '==', 'true'),
				)
			),
			array(
				'type' => 'break',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'item_delay',
				'name' => __( 'Preloader Item Delay', 'tg-text-domain' ),
				'desc' => __( 'Delay in ms between each item loaded in the grid.', 'tg-text-domain' ),
				'sub_desc' => '<strong>'.__( '* If no delay is set (0ms), then all items will appear at the same time.', 'tg-text-domain' ).'</strong>',
				'type' => 'slider',
				'label' => '',
				'sign'  => 'ms',
				'step' => 10,
				'min' => 0,
				'max' => 1000,
				'std' => 100,
				'tab' => __( 'Load/Ajax', 'tg-text-domain' ),
				'required' => array(
					array($prefix . 'preloader', '==', 'true'),
				)
			),
			array(
				'id'   => 'section_load_end',
				'type' => 'section_end',
				'tab' => __( 'Load/Ajax', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons dashicons-update"></i>'
			),
			array(
				'id'   => 'section_custom_css_start',
				'name' => __( 'Custom css', 'tg-text-domain'   ),
				'desc' => __( 'Apply custom style to the current grid', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'css/js Code', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'custom_css',
				'name' => __( 'Custom css', 'tg-text-domain' ),
				'desc' => __( 'Enter you custom css code.', 'tg-text-domain' ),
				'sub_desc' => '<br><strong>* '.__( 'You should use the custom css class in order to only apply this code to the current grid.', 'tg-text-domain' ).'</strong>',
				'type' => 'code',
				'mode' => 'text/css',
				'theme' => 'eclipse',
				'std' => '',
				'tab' => __( 'css/js Code', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_custom_css_end',
				'type' => 'section_end',
				'tab' => __( 'css/js Code', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons-admin-tools"></i>'
			),
			array(
				'id'   => 'section_custom_js_start',
				'name' => __( 'Custom js', 'tg-text-domain'   ),
				'desc' => __( 'Add any custom jquery/javascript code to add additionnal functionnalities', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'section_start',
				'color' => '#ffffff',
				'background' => '#34495e',
				'tab' => __( 'css/js Code', 'tg-text-domain' )
			),
			array(
				'id'   => $prefix.'custom_js',
				'name' => __( 'Custom js', 'tg-text-domain' ),
				'desc' => __( 'Enter you custom js code.', 'tg-text-domain' ),
				'sub_desc' => '',
				'type' => 'code',
				'mode' => 'text/javascript',
				'theme' => 'monokai',
				'std' => '',
				'tab' => __( 'css/js Code', 'tg-text-domain' )
			),
			array(
				'id'   => 'section_custom_js_end',
				'type' => 'section_end',
				'tab' => __( 'css/js Code', 'tg-text-domain' ),
				'tab_icon' => '<i class="tomb-icon dashicons dashicons-admin-tools"></i>'
			),
	),
);

/*if ($nextgen) {
	
	foreach ($grid_settings['fields'] as $key => $val) {
		
		if (isset($val['id']) && $val['id'] == $prefix.'source_type') {
			
			$grid_settings['fields'][$key]['options'][] = array (
				'label' => 'NextGen Gallery',
				'value' => 'nextgen',
				'image' => TG_PLUGIN_URL . 'backend/assets/images/NextGen-logo.png'
			);
			
			break;
			
		}
		
	}
	
}*/

new TOMB_Metabox($grid_settings);