<?php
global $builder;

$av_default_title = __('Avia Layout Builder','avia_framework' );
if($builder->disable_drag_drop == true)
{
	$av_default_title = __('Page Layout','avia_framework' );
}

$boxes = array(
	
    array( 'title' =>$av_default_title, 'id'=>'avia_builder', 'page'=>array('post','portfolio','page','product'), 'context'=>'normal', 'priority'=>'high', 'expandable'=>true ),
	array( 'title' =>__('Enfold Shortcode Parser','avia_framework' ), 'id'=>'avia_sc_parser', 'page'=>array('post','portfolio','page','product'), 'context'=>'normal', 'priority'=>'high', 'expandable'=>true ),
    array( 'title' =>__('Layout','avia_framework' ), 'id'=>'layout', 'page'=>array('portfolio', 'page' , 'post'), 'context'=>'side', 'priority'=>'low'),
    array( 'title' =>__('Additional Portfolio Settings','avia_framework' ), 'id'=>'preview', 'page'=>array('portfolio'), 'context'=>'normal', 'priority'=>'high' ),
    array( 'title' =>__('Breadcrumb Hierarchy','avia_framework' ), 'id'=>'hierarchy', 'page'=>array('portfolio'), 'context'=>'side', 'priority'=>'low'),
);

/**
 * used_by:		enfold\config-woocommerce\admin-options.php  avia_woocommerce_product_options()			10
 */
$boxes = apply_filters('avf_builder_boxes', $boxes);


$elements = array(
	
	array(
		"slug"          => "avia_sc_parser",
        "name"          => __("Enfold Shortcode Parser Info Window", 'avia_framework' ),
        "id"            => "sc_parser_info",
		"type"          => array( $builder, 'parser_select_panel' )
		),
	
	array(
        "slug"          => "avia_builder",
        "name"          => __("Visual layout editor",'avia_framework'),
        "id"            => "layout_editor",
        "type"          => array($builder,'visual_editor'),
        "tab_order"     => array(__('Layout Elements','avia_framework' ), __('Content Elements','avia_framework' ) , __('Media Elements','avia_framework' )),
        "desc"          =>  '<h4>'.__('Quick Info & Hotkeys', 'avia_framework' )."</h4>".
                            '<strong>'.__('General Info', 'avia_framework' ).'</strong>'.
                            "<ul>".
                            '   <li>'.__('To insert an Element either click the insert button for that element or drag the button onto the canvas', 'avia_framework' ).'</li>'.
                            '   <li>'.__('If you place your mouse above the insert button a short info tooltip will appear', 'avia_framework' ).'</li>'.
                            '   <li>'.__('To sort and arrange your elements just drag them to a position of your choice and release them', 'avia_framework' ).'</li>'.
                            '   <li>'.__('Valid drop targets will be highlighted. Some elements like fullwidth sliders and color section can not be dropped onto other elements', 'avia_framework' ).'</li>'.
                            "</ul>".
                            '<strong>'.__('Edit Elements in Popup Window:', 'avia_framework' ).'</strong>'.
                            "<ul>".
                            '   <li>'.__('Most elements open a popup window if you click them', 'avia_framework' ).'</li>'.
                            '   <li>'.__('Press TAB to navigate trough the various form fields of a popup window.', 'avia_framework' ).'</li>'.
                            '   <li>'.__('Press ESC on your keyboard or the Close Button to close popup window.', 'avia_framework' ).'</li>'.
                            '   <li>'.__('Press ENTER on your keyboard or the Save Button to save current state of a popup window', 'avia_framework' ).'</li>'.
                            "</ul>"
		),

    array(
        "container_class" => "av_2columns av_col_1 avia-style",
        "slug"  => "preview",
        "name"  => __("Overwrite Portfolio Link setting",'avia_framework'),
        "desc"  => __("If this entry is displayed in a portfolio grid, it will use the grids link settings (open either in lightbox, or open link url). You may overwrite this setting here",'avia_framework'),
        "id"    => "_portfolio_custom_link",
        "type"  => "select",
        "std"   => "",
        "subtype" => array( "Use default setting"   => '',
                            "Define custom link" => 'custom',

        )),

    array(
        "slug"  => "preview",
        "name"  => __("Link portfolio item to external URL",'avia_framework' ),
        "desc"  => __("You can add a link to any (external) page here. ",'avia_framework' ).
        "<br/>".__("If you add a link to a video that video will open in a lightbox ",'avia_framework' ),
        "id"    => "_portfolio_custom_link_url",
        "type"  => "input",
        "required"  => array('_portfolio_custom_link','equals','custom'),
        "container_class" => "avia-style av_2columns av_col_2",
        "std"   => "http://"),

    array(
        "slug"  => "preview",
        "id"    => "_portfolio_hr",
        "type"  => "hr",
        "std"   => ""),


    array(
        "slug"  => "preview",
        "name"  => __("Ajax Portfolio Preview Settings",'avia_framework' ),
        "desc"  => __("If you have selected to display your portfolio grid as an 'Ajax Portfolio' please choose preview images here and write some preview text. Once the user clicks on the portfolio item a preview element with those images and info will open.",'avia_framework' ),
        "id"    => "_preview_heading",
        "type"  => "heading",
        "std"   => ""),


    array(
        "slug"  => "preview",
        "container_class" => "av_2columns av_col_1",
        "name"  => __("Add Preview Images",'avia_framework' ),
        "desc"  => __("Create a new Preview Gallery or Slideshow by selecting existing or uploading new images",'avia_framework' ),
        "id"    => "_preview_ids",
        "type"  => "gallery",
        "title" => __("Add Preview Images",'avia_framework' ),
        "delete" => __("Remove Images",'avia_framework' ),
        "button" => __("Insert Images",'avia_framework' ),
        "std"   => ""),

        array(
        "container_class" => "av_2columns av_col_2",
        "slug"  => "preview",
        "name"  => __("Display Preview Images",'avia_framework'),
        "desc"  => __("Display Images as either gallery, slideshow or as a list below each other",'avia_framework'),
        "id"    => "_preview_display",
        "type"  => "select",
        "std"   => "gallery",
        "class" => "avia-style",
        "subtype" => array( __("Gallery",'avia_framework')      => 'gallery',
                            __("Slideshow",'avia_framework')    => 'slideshow',
                            __("Image List",'avia_framework')   => 'list',
                            __("Don't show the images at all and display the preview text only",'avia_framework')  => 'no'

        ),
        ),

        array(
        "container_class" => "av_2columns av_col_2",
        "slug"  => "preview",
        "name"  => __("Autorotation",'avia_framework'),
        "desc"  => __("Slideshow autorotation Settings in Seconds",'avia_framework'),
        "id"    => "_preview_autorotation",
        "type"  => "select",
        "std"   => "disabled",
        "class" => "avia-style",
        "required"  => array('_preview_display','equals','slideshow'),
        "subtype" => array(
                            __("Disabled",'avia_framework')  => 'disabled',
                            "3"   => '3',
                            "4"   => '4',
                            "5"   => '5',
                            "6"   => '6',
                            "7"   => '7',
                            "8"   => '8',
                            "9"   => '9',
                            "10"   => '10',
                            "15"   => '15',
                            "20"   => '20',

        ),
        ),

                array(
        "container_class" => "av_2columns av_col_2",
        "slug"  => "preview",
        "name"  => __("Gallery Thumbnail Columns",'avia_framework'),
        "desc"  => __("How many Thumbnails should be displayed beside each other",'avia_framework'),
        "id"    => "_preview_columns",
        "type"  => "select",
        "std"   => "6",
        "class" => "avia-style",
        "required"  => array('_preview_display','equals','gallery'),
        "subtype" => array(
                            "2"   => '2',
                            "3"   => '3',
                            "4"   => '4',
                            "5"   => '5',
                            "6"   => '6',
                            "7"   => '7',
                            "8"   => '8',
                            "9"   => '9',
                            "10"   => '10',
                            "11"   => '11',
                            "12"   => '12',

        ),
        ),



    array(
        "slug"  => "preview",
        "container_class" => "avia_clear",
        "name"  => __("Add Preview Text",'avia_framework' ),
        "desc"  => __("The text will appear beside your gallery/slideshow",'avia_framework' ),
        "id"    => "_preview_text",
        "type"  => "tiny_mce",
        "std"   => ""),


    array(

        "slug"  => "layout",
        "name"  => __("Sidebar Settings",'avia_framework'),
        "desc"  => __("Select the desired Page layout",'avia_framework'),
        "id"    => "layout",
        "type"  => "select",
        "std"   => "",
        "class" => "avia-style",
        "subtype" => array( __("Default Layout - set in",'avia_framework')." ".THEMENAME." > " . __('Sidebar','avia_framework') => '',
                            __("No Sidebar",'avia_framework')       => 'fullsize',
                            __("Left Sidebar",'avia_framework')     => 'sidebar_left',
                            __("Right Sidebar",'avia_framework')    => 'sidebar_right',

        ),
        ),


    array(

        "slug"  => "layout",
        "name"  => __("Sidebar Setting",'avia_framework'),
        "desc"  => __("Choose a custom sidebar for this entry",'avia_framework'),
        "id"    => "sidebar",
        "type"  => "select",
        "std"   => "",
        "class" => "avia-style",
        "required" => array('layout','not','fullsize'),
        "subtype" => AviaHelper::get_registered_sidebars(array('Default Sidebars' => ""), array('Displayed Everywhere'))

        ),
        
		array(

        "slug"  => "layout",
        "name"  => __("Footer Settings",'avia_framework'),
        "desc"  => __("Display the footer widgets?",'avia_framework'),
        "id"    => "footer",
        "type"  => "select",
        "std"   => "",
        "class" => "avia-style",
        "subtype" => array(
                        __("Default Layout - set in",'avia_framework')." ".THEMENAME." > ". __('Footer','avia_framework') => '',
                        __('Display the footer widgets & socket','avia_framework')=>'all',
                        __('Display only the footer widgets (no socket)','avia_framework')=>'nosocket',
                        __('Display only the socket (no footer widgets)','avia_framework')=>'nofooterwidgets',
                        __('Don\'t display the socket & footer widgets','avia_framework')=>'nofooterarea'
                    ),

    ),
		
		
        array(
        "slug"  => "layout",
        "notice"  => __("These settings are only available for layouts with a main menu placed at the top",'avia_framework')." - <a href='".admin_url('admin.php?page=avia#goto_layout')."'>".__("Change layout",'avia_framework')."</a>",
        "id"    => "conditional_header",
        "type"  => "condition",
        "class" => "avia-style", 
        "condition" => array('option' => 'header_position', 'compare' => "equal_or_empty", "value"=>"header_top"),
        "nodescription" => true
        ),
        
        
        array(

        "slug"  => "layout",
        "name"  => __("Title Bar Settings",'avia_framework'),
        "desc"  => __("Display the Title Bar with Page Title and Breadcrumb Navigation?",'avia_framework'),
        "id"    => "header_title_bar",
        "type"  => "select",
        "std"   => "",
        "class" => "avia-style",
        "subtype" => array( __("Default Layout - set in",'avia_framework')." ".THEMENAME." > ". __('Header','avia_framework') => '',
                            __('Display title and breadcrumbs','avia_framework')    =>'title_bar_breadcrumb',
                            __('Display only title'           ,'avia_framework')    =>'title_bar',
                            __('Display only breadcrumbs', 'avia_framework')	    =>'breadcrumbs_only',
                            __('Hide both'                    ,'avia_framework')    =>'hidden_title_bar',

                    )
        ),
        
        
        
        array(
        "slug"  => "layout",
        "notice"  => __("Only available if the logo is not",'avia_framework')." <a href='".admin_url('admin.php?page=avia#goto_header')."'>".__("below the menu",'avia_framework').".</a>",
        "id"    => "conditional_header2",
        "type"  => "condition",
        "class" => "avia-style", 
        "condition" => array('option' => 'header_layout', 'compare' => "contains", "value"=>"top_nav_header"),
        "nodescription" => true
        ),
        
        array(

        "slug"  => "layout",
        "name"  => __("Header visibility and transparency",'avia_framework'),
        "desc"  => __("Several options to change the header transparency and visibility on this page.",'avia_framework'),
        "id"    => "header_transparency",
        "type"  => "select",
        "std"   => "",
        "class" => "avia-style",
        "subtype" => array( __("No transparency",'avia_framework') => '',
                            __('Transparent Header','avia_framework') =>'header_transparent',
                            __('Transparent Header with border','avia_framework') =>'header_transparent header_with_border',
                            __('Transparent & Glassy Header','avia_framework') =>'header_transparent header_glassy ',
                            __('Header is invisible and appears once the users scrolls down ','avia_framework') =>'header_transparent header_scrolldown ',
                            __('Hide Header on this page ','avia_framework') =>'header_transparent header_hidden ',

                    )
        ),
        
        array(
        "slug"  => "layout",
        "id"    => "conditional_header_end",
        "type"  => "condition_end", 
        "nodescription" => true
        ),
        
        array(
        "slug"  => "layout",
        "id"    => "conditional_header_end",
        "type"  => "condition_end", 
        "nodescription" => true
        
        ),

        
    

    array(
        "slug"  => "hierarchy",
        "name"  => __("Breadcrumb parent page",'avia_framework'),
        "desc"  => __("Select a parent page for this entry. If no page is selected the theme will use session data to build the breadcrumb.",'avia_framework'),
        "id"    => "breadcrumb_parent",
        "type"  => "select",
        "subtype" => 'page',
        "with_first" => true,
        "std"   => "",
        "class" => "avia-style",
    ),


);





$elements = apply_filters('avf_builder_elements', $elements);




/*
array(

        "slug"  => "avia_builder",
        "name"  => "Layout",
        "desc"  => "Select the desired Page layout",
        "id"    => "layout",
        "type"  => "radio",
        "class" => "image_radio image_radio_layout",
        "std"   => "fullwidth",
        "options" => array( 'default'       => "Default layout",
                            'sidebar_left'  => "Left Sidebar",
                            'sidebar_right' => "Right Sidebar",
                            'fullwidth'     => "No Sidebar"
        ),

        "images" => array(  'default'       => AviaBuilder::$path['imagesURL']."layout-slideshow.png",
                            'sidebar_left'  => AviaBuilder::$path['imagesURL']."layout-left.png",
                            'sidebar_right' => AviaBuilder::$path['imagesURL']."layout-right.png",
                            'fullwidth'     => AviaBuilder::$path['imagesURL']."layout-fullwidth.png",
        ),
    ),
*/
