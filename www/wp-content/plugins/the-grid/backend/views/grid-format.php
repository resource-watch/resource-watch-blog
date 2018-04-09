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


$prefix = 'the_grid_item_'; 
$custom_fields = new The_Grid_Custom_Fields();

$post_types = array();
$post_types_arr = The_Grid_Base::get_all_post_types();

foreach ($post_types_arr as $key => $val) {
	if($key != 'attachment') {
		$post_types[] = $key;
	}
}

$the_grid_item_format = array(
	'id'    => $prefix . 'formats',
	'title' => __('The Grid - Item Format', 'tg-text-domain'),
	'icon' => '<i class="dashicons tg-metabox-icon"></i>',
	'color' => '#f1f1f1',
	'background' => '#34495e',
	'context' => 'normal',
	'priority' => 'high',
	'pages' => $post_types,
	'fields' => array(
		array(
			'id' => $prefix.'format',
			'name' => __('Alternative Media', 'tg-text-domain'),
			'desc' => __( 'In this option panel, you can set an alternative media content for the current post in the grid depending of the post format.', 'tg-text-domain').'<br>'.__( 'Alternative content will be retrieved at first. If no alternative content is set, then the first content in the text editor will be fetched (depending of native post format).', 'tg-text-domain') . ' <strong><a href="https://en.support.wordpress.com/posts/post-formats/" target="_blank">' . __('Post_Formats', 'tg-text-domain') .'</a></strong>',
			'sub_desc' => '',
			'type' => 'radio',
			'std' => '',
			'options' => array (
				'' =>  __('Default', 'tg-text-domain'),
				'gallery' =>  __('Gallery', 'tg-text-domain'),
				'audio' =>  __('Audio', 'tg-text-domain'),
				'video' =>  __('Video', 'tg-text-domain'),
				'quote' =>  __('Quote', 'tg-text-domain'),
				'link' =>  __('Link', 'tg-text-domain')
			),
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'custom_link',
			'name' => __('Alternative Link', 'tg-text-domain'),
			'desc' => __( 'Thanks to this option, you can add an alternative link to the current post link.', 'tg-text-domain'),
			'sub_desc' => '',
			'type' => 'url',
			'width' => 220,
			'options' => '',
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'custom_link_target',
			'name' => '',
			'desc' => '',
			'sub_desc' => '',
			'type' => 'select',
			'std' => '_self',
			'width' => 78,
			'options' => array(
				'_blank' => __( 'Blank', 'tg-text-domain' ),
				'_self' => __( 'Self', 'tg-text-domain' )
			),
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'General', 'tg-text-domain' )
		),
		
		
		array(
			'id'   => $prefix.'skin',
			'name' => __('Item Skin', 'tg-text-domain'),
			'desc' => __( 'Select a skin for the current post', 'tg-text-domain'),
			'sub_desc' => __( 'The skin selected must correspond to the grid type (Masonry/Grid) otherwise the default grid/masonry skin will be set.', 'tg-text-domain'),
			'type' => 'select',
			'placeholder' => __( 'Select a skin', 'tg-text-domain'),
			'clear' => true,
			'width' => 180,
			'options' => $custom_fields->get_all_grid_skins(),
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'sizes',
			'name' => __('Item Size', 'tg-text-domain'),
			'desc' => __( 'Set the current item size', 'tg-text-domain' ).'<br>'.__('Row number only works for grid layout (not masonry layout).', 'tg-text-domain'),
			'sub_desc' => '',
			'type' => 'custom',
			'options' => '',
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'col',
			'name' => '',
			'sub_desc' => '',
			'desc' => '',
			'type' => 'number',
			'label' => '',
			'sign'  => '&nbsp;col(s)',
			'min' => 1,
			'max' => 12,
			'std' => 1,
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'row',
			'name' => '',
			'sub_desc' => '',
			'desc' => '',
			'type' => 'number',
			'label' => '',
			'sign'  => '&nbsp;row(s)',
			'min' => 1,
			'max' => 12,
			'std' => 1,
			'tab' => __( 'General', 'tg-text-domain' ),
			'tab_icon' => '<i class="tomb-icon dashicons dashicons-admin-generic"></i>'
		),
		array(
			'id'   => $prefix.'content_colors',
			'name' => '',
			'desc' => '<br>'.__( 'Here you can override the default colors set in the grid settings.','tg-text-domain').'<br>'. __('Keep the background color field empty if you do not want to add a custom color for the current post.', 'tg-text-domain'),
			'sub_desc' => '',
			'type' => 'title',
			'options' => '',
			'tab' => __( 'Colors', 'tg-text-domain' )
		),
		array(
			'id' => $prefix . 'content_background',
			'name' => __('Content Background Color', 'tg-text-domain'),
			'desc' => __('Choose a background color for main content holder (masonry).', 'tg-text-domain'),
			'sub_desc' => '',
			'type' => 'color',
			'rgba' => true,
			'std' => '',
			'tab' => __( 'Colors', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'content_color',
			'name' => __( 'Content Color Scheme', 'tg-text-domain' ),
			'desc' => __( 'Select a color scheme for the text content.', 'tg-text-domain' ) .'&nbsp;&nbsp;&nbsp;&nbsp;',
			'sub_desc' => '',
			'type' => 'radio',
			'std' => 'dark',
			'options' => array(
				'light' => __( 'Light', 'tg-text-domain' ),
				'dark' => __( 'Dark', 'tg-text-domain' )
			),
			'tab' => __( 'Colors', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'Colors', 'tg-text-domain' )
		),
		array(
			'id' => $prefix . 'overlay_background',
			'name' => __('Overlay Background Color', 'tg-text-domain'),
			'desc' => __('Choose a background color for the overlay (over image content).', 'tg-text-domain'),
			'sub_desc' => '',
			'type' => 'color',
			'rgba' => true,
			'std' => '',
			'tab' => __( 'Colors', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'overlay_color',
			'name' => __( 'Overlay Color Scheme', 'tg-text-domain' ),
			'desc' => __( 'Select a color scheme for the overlay.', 'tg-text-domain' ) .'&nbsp;&nbsp;&nbsp;&nbsp;',
			'sub_desc' => '',
			'type' => 'radio',
			'std' => 'light',
			'options' => array(
				'light' => __( 'Light', 'tg-text-domain' ),
				'dark' => __( 'Dark', 'tg-text-domain' )
			),
			'tab' => __( 'Colors', 'tg-text-domain' ),				'tab_icon' => '<i class="tomb-icon dashicons dashicons-art"></i>'
		),
		array( 
			'id' => $prefix . 'image',
			'name' => __('Alternative Image', 'tg-text-domain'),
			'desc' => __('Add an alternative image to the current feature thumbnail image (if available).', 'tg-text-domain').'<br>'.__( 'This image will also be used as a poster for video/audio format', 'tg-text-domain'),
			'sub_desc' => '',
			'type' => 'image_id',
			'frame_title'   => __( 'Select an image as an alternative to the feature image', 'tg-text-domain'),
			'frame_button'  => __( 'Insert image', 'tg-text-domain'),
			'button_upload' => __( 'Upload', 'tg-text-domain'),
			'button_remove' => __( 'Remove', 'tg-text-domain'),
			'tab' => __( 'Image', 'tg-text-domain' ),
			'tab_icon' => '<i class="tomb-icon dashicons dashicons-format-image"></i>'
		),	
		array( 
			'id' => $prefix . 'gallery',
			'name' => __('Gallery', 'tg-text-domain'),
			'desc' => __('Select multiple image to add as a gallery in the current grid item.', 'tg-text-domain'),
			'sub_desc' => __('You can easly arrange the gallery order by dragging and dropping images.', 'tg-text-domain'),
			'type' => 'gallery',
			'frame_title'   => __( 'Select or upload images to create a gallery', 'tg-text-domain'),
			'frame_button'  => __('Insert gallery', 'tg-text-domain'),
			'button_upload' => __( 'Add images', 'tg-text-domain'),
			'button_remove' => __( 'Remove gallery', 'tg-text-domain'),
			'delete_message' => __( 'Are you sure you want to remove all the gallery images?', 'tg-text-domain'),
			'tab' => __( 'Gallery', 'tg-text-domain' ),
			'tab_icon' => '<i class="tomb-icon dashicons dashicons-format-gallery"></i>'
		),
		array( 
			'id' => $prefix . 'mp3',
			'name' => __('MP3 File URL', 'tg-text-domain'),
			'desc' => __('Please enter an URL or upload your .mp3 audio file', 'tg-text-domain'),
			'type' => 'upload',
			'frame_title'   => __( 'Select a .mp3 file', 'tg-text-domain'),
			'frame_button'  => __( 'Insert .mp3', 'tg-text-domain'),
			'button_upload' => __( 'Add .mp3', 'tg-text-domain'),
			'button_remove' => __( 'Remove', 'tg-text-domain'),
			'media_type' => 'audio',
			'std' => '',
			'tab' => __( 'Audio', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'Audio', 'tg-text-domain' )
		),
		array( 
			'id' => $prefix . 'ogg',
			'name' => __('OGG File URL', 'tg-text-domain'),
			'desc' => __('Please enter an URL or upload your .oga/.ogg audio file', 'tg-text-domain'),
			'type' => 'upload',
			'frame_title'   => __( 'Select an .ogg file', 'tg-text-domain'),
			'frame_button'  => __( 'Insert .ogg', 'tg-text-domain'),
			'button_upload' => __( 'Add .ogg', 'tg-text-domain'),
			'button_remove' => __( 'Remove', 'tg-text-domain'),
			'media_type' => 'audio',
			'std' => '',
			'tab' => __( 'Audio', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'Audio', 'tg-text-domain' )
		),
		array( 
			'id' => $prefix . 'soundcloud',
			'name' => __('SoundCloud ID', 'tg-text-domain'),
			'desc' => __('Please enter your sound ID', 'tg-text-domain'),
			'type' => 'text',
			'std' => '',
			'tab' => __( 'Audio', 'tg-text-domain' ),
			'tab_icon' => '<i class="tomb-icon dashicons dashicons-format-audio"></i>'
		),
		array(
			'id' => $prefix . 'mp4',
			'name' => __('MP4 File URL', 'tg-text-domain'),
			'desc' => __('Please enter an URL or upload your .m4v/.mp4 video file', 'tg-text-domain'),
			'type' => 'upload',
			'frame_title'   => __( 'Select a .mp4 file', 'tg-text-domain'),
			'frame_button'  => __( 'Insert .mp4', 'tg-text-domain'),
			'button_upload' => __( 'Add a video', 'tg-text-domain'),
			'button_remove' => __( 'Remove', 'tg-text-domain'),
			'media_type' => 'video',
			'std' => '',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'id' => $prefix . 'ogv',
			'name' => __('OGV File URL', 'tg-text-domain'),
			'desc' => __('Please enter an URL or upload your .ogv/.ogg video file', 'tg-text-domain'),
			'type' => 'upload',
			'frame_title'   => __( 'Select an .ogv file', 'tg-text-domain'),
			'frame_button'  => __( 'Insert .ogv', 'tg-text-domain'),
			'button_upload' => __( 'Add a video', 'tg-text-domain'),
			'button_remove' => __( 'Remove', 'tg-text-domain'),
			'media_type' => 'video',
			'std' => '',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'id' => $prefix . 'webm',
			'name' => __('WEBM File URL', 'tg-text-domain'),
			'desc' => __('Please enter an URL or upload your .webm video file', 'tg-text-domain'),
			'type' => 'upload',
			'frame_title'   => __( 'Select a .webm file', 'tg-text-domain'),
			'frame_button'  => __( 'Insert .webm', 'tg-text-domain'),
			'button_upload' => __( 'Add a video', 'tg-text-domain'),
			'button_remove' => __( 'Remove', 'tg-text-domain'),
			'media_type' => 'video',
			'std' => '',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'video_ratio',
			'name' => __( 'Video Aspect ratio (mp4, ogv, webm)', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => __( 'Video ratio (only works with masonry)', 'tg-text-domain'  ),
			'type' => 'select',
			'placeholder' => '',
			'width' => 70,
			'options' => array(
				'4:3' => '4:3',
				'16:9' => '16:9',
				'16:10' => '16:10'
			),
			'std' => 'publish',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'id' => $prefix . 'youtube',
			'name' => __('Youtube ID', 'tg-text-domain'),
			'desc' => __('Copy/Paste the Youtube ID', 'tg-text-domain'),
			'type' => 'text',
			'disabled' => false,
			'placeholder' => '',
			'std' => '',
			'tab' => __( 'Video', 'tg-text-domain' ),
			'tab_icon' => '<i class="tomb-icon dashicons dashicons-format-video"></i>'
		),
		array(
			'id'   => $prefix.'youtube_ratio',
			'name' => __( 'Youtube Aspect ratio', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => __( 'Video ratio (only works with masonry)', 'tg-text-domain'  ),
			'type' => 'select',
			'placeholder' => '',
			'width' => 70,
			'options' => array(
				'4:3' => '4:3',
				'16:9' => '16:9',
				'16:10' => '16:10'
			),
			'std' => 'publish',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'id' => $prefix . 'vimeo',
			'name' => __('Vimeo ID', 'tg-text-domain'),
			'desc' => __('Copy/Paste the Vimeo ID', 'tg-text-domain'),
			'type' => 'text',
			'disabled' => false,
			'placeholder' => '',
			'std' => '',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'vimeo_ratio',
			'name' => __( 'Vimeo Aspect ratio', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => __( 'Video ratio (only works with masonry)', 'tg-text-domain'  ),
			'type' => 'select',
			'placeholder' => '',
			'width' => 70,
			'options' => array(
				'4:3' => '4:3',
				'16:9' => '16:9',
				'16:10' => '16:10'
			),
			'std' => 'publish',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'id' => $prefix . 'wistia',
			'name' => __('Wistia ID', 'tg-text-domain'),
			'desc' => __('Copy/Paste the Wistia ID', 'tg-text-domain'),
			'type' => 'text',
			'disabled' => false,
			'placeholder' => '',
			'std' => '',
			'tab' => __( 'Video', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'wistia_ratio',
			'name' => __( 'Wistia Aspect ratio', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => __( 'Wistia ratio (only works with masonry)', 'tg-text-domain'  ),
			'type' => 'select',
			'placeholder' => '',
			'width' => 70,
			'options' => array(
				'4:3' => '4:3',
				'16:9' => '16:9',
				'16:10' => '16:10'
			),
			'std' => 'publish',
			'tab' => __( 'Video', 'tg-text-domain' ),
			'tab_icon' => '<i class="tomb-icon dashicons dashicons-format-video"></i>'
		),
		array(
			'id' => $prefix . 'quote_author',
			'name' =>  __('Quote Author', 'tg-text-domain'),
			'desc' => __('Please enter the quote author.', 'tg-text-domain'),
			'type' => 'text',
			'std' => '',
			'tab' => __( 'Quote', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'Quote', 'tg-text-domain' )
		),
		array(
			'id' => $prefix . 'quote_content',
			'name' =>  __('Quote Content', 'tg-text-domain'),
			'desc' => __('Please type the text for your quote here.', 'tg-text-domain'),
			'type' => 'textarea',
			'disabled' => false,
			'cols' => 80,
			'rows' => 6,
			'placeholder' => '',
			'std' => '',
			'tab' => __( 'Quote', 'tg-text-domain' ),
			'tab_icon' => '<i class="tomb-icon dashicons dashicons-format-quote"></i>'
		),
		array(
			'id' => $prefix . 'link_url',
			'name' =>  __('Link URL', 'tg-text-domain'),
			'desc' => __('Please type the url link', 'tg-text-domain'),
			'type' => 'text',
			'std' => '',
			'tab' => __( 'Link', 'tg-text-domain' ),
		),
		array(
			'type' => 'break',
			'tab' => __( 'Link', 'tg-text-domain' )
		),
		array(
			'id' => $prefix . 'link_content',
			'name' =>  __('Link Content', 'tg-text-domain'),
			'desc' => __('Please enter the Link Content/Text.', 'tg-text-domain'),
			'type' => 'textarea',
			'disabled' => false,
			'cols' => 80,
			'rows' => 6,
			'placeholder' => '',
			'std' => '',
			'tab' => __( 'Link', 'tg-text-domain' ),
			'tab_icon' => '<i class="tomb-icon dashicons dashicons-admin-links"></i>'
		),
	)
);

new TOMB_Metabox($the_grid_item_format);

// set custom metaboxes for attachment
$the_grid_attachment_format = array(
	'id'    => $prefix . 'formats',
	'title' => __('The Grid - Image Settings', 'tg-text-domain'),
	'icon' => '<i class="dashicons tg-metabox-icon"></i>',
	'color' => '#f1f1f1',
	'background' => '#34495e',
	'context' => 'normal',
	'priority' => 'high',
	'pages' => array('attachment'),
	'fields' => array(
		array(
			'id'   => $prefix.'custom_link',
			'name' => __('Custom Link', 'tg-text-domain'),
			'desc' => __( 'Attachment doesn\'t have link to redirect to a particular page/post except the image url itself.', 'tg-text-domain') .'<br>' .__( 'Thanks to this option you can add a custom link to redirect everywhere you want on your website or external link.', 'tg-text-domain'),
			'sub_desc' => '',
			'type' => 'url',
			'width' => 220,
			'options' => '',
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'custom_link_target',
			'name' => '',
			'desc' => '',
			'sub_desc' => '',
			'type' => 'select',
			'std' => '_self',
			'width' => 70,
			'options' => array(
				'_blank' => __( 'Blank', 'tg-text-domain' ),
				'_self' => __( 'Self', 'tg-text-domain' )
			),
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'skin',
			'name' => __('Item Skin', 'tg-text-domain'),
			'desc' => __( 'Select a skin for the current post', 'tg-text-domain'),
			'sub_desc' => __( 'The skin selected must correspond to the grid type (Masonry/Grid) otherwise the default grid/masonry skin will be set.', 'tg-text-domain'),
			'type' => 'select',
			'placeholder' => __( 'Select a skin', 'tg-text-domain'),
			'clear' => true,
			'width' => 180,
			'options' => $custom_fields->get_all_grid_skins(),
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'sizes',
			'name' => __('Item Size', 'tg-text-domain'),
			'desc' => __( 'Set the current item size', 'tg-text-domain' ).'<br>'.__('Row number only works for grid layout (not masonry layout).', 'tg-text-domain'),
			'sub_desc' => '',
			'type' => 'custom',
			'options' => '',
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'col',
			'name' => '',
			'sub_desc' => '',
			'desc' => '',
			'type' => 'number',
			'label' => '',
			'sign'  => '&nbsp;col(s)',
			'min' => 1,
			'max' => 12,
			'std' => 1,
			'tab' => __( 'General', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'row',
			'name' => '',
			'sub_desc' => '',
			'desc' => '',
			'type' => 'number',
			'label' => '',
			'sign'  => '&nbsp;row(s)',
			'min' => 1,
			'max' => 12,
			'std' => 1,
			'tab' => __( 'General', 'tg-text-domain' ),
			'tab_icon' => '<i class="tomb-icon dashicons dashicons-admin-generic"></i>'
		),
		array(
			'id'   => $prefix.'content_colors',
			'name' => '',
			'desc' => __( 'Here you can override the default colors set in the grid settings.','tg-text-domain').'<br>'. __('Keep the background color field empty if you do not want to add a custom color for the current post.', 'tg-text-domain'),
			'sub_desc' => '',
			'type' => 'title',
			'options' => '',
			'tab' => __( 'Colors', 'tg-text-domain' )
		),
		array(
			'id' => $prefix . 'content_background',
			'name' => __('Content Background Color', 'tg-text-domain'),
			'desc' => __('Choose a background color for main content holder (masonry).', 'tg-text-domain'),
			'sub_desc' => '',
			'type' => 'color',
			'rgba' => true,
			'std' => '',
			'tab' => __( 'Colors', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'content_color',
			'name' => __( 'Content Color Scheme', 'tg-text-domain' ),
			'desc' => __( 'Select a color scheme for the text content.', 'tg-text-domain' ) .'&nbsp;&nbsp;&nbsp;&nbsp;',
			'sub_desc' => '',
			'type' => 'radio',
			'std' => 'dark',
			'options' => array(
				'light' => __( 'Light', 'tg-text-domain' ),
				'dark' => __( 'Dark', 'tg-text-domain' )
			),
			'tab' => __( 'Colors', 'tg-text-domain' )
		),
		array(
			'type' => 'break',
			'tab' => __( 'Colors', 'tg-text-domain' )
		),
		array(
			'id' => $prefix . 'overlay_background',
			'name' => __('Overlay Background Color', 'tg-text-domain'),
			'desc' => __('Choose a background color for the overlay (over image content).', 'tg-text-domain'),
			'sub_desc' => '',
			'type' => 'color',
			'rgba' => true,
			'std' => '',
			'tab' => __( 'Colors', 'tg-text-domain' )
		),
		array(
			'id'   => $prefix.'overlay_color',
			'name' => __( 'Overlay Color Scheme', 'tg-text-domain' ),
			'desc' => __( 'Select a color scheme for the overlay.', 'tg-text-domain' ) .'&nbsp;&nbsp;&nbsp;&nbsp;',
			'sub_desc' => '',
			'type' => 'radio',
			'std' => 'light',
			'options' => array(
				'light' => __( 'Light', 'tg-text-domain' ),
				'dark' => __( 'Dark', 'tg-text-domain' )
			),
			'tab' => __( 'Colors', 'tg-text-domain' ),				'tab_icon' => '<i class="tomb-icon dashicons dashicons-art"></i>'
		),
	)
);

new TOMB_Metabox($the_grid_attachment_format);


$taxonomies = get_taxonomies(); 

foreach ($taxonomies as $taxonomy) {

	$the_grid_item_format = array(
		'id'    => $prefix . 'term_color',
		'title' => __('The Grid Term Color', 'tg-text-domain'),
		'icon' => '<i class="dashicons tg-metabox-icon"></i>',
		'color' => '',
		'background' => '',
		'taxonomy' => $taxonomy,
		'fields' => array(
			array(
				'id'   => 'the_grid_term_color',
				'name' => __('Term color:', 'tg-text-domain').'<br><br>',
				'desc' => '',
				'sub_desc' => __('Choose a color for the current term', 'tg-text-domain'),
				'type' => 'color',
				'std' => ''
			)
		),
	);
	
	new TOMB_Taxonomy($the_grid_item_format);

}