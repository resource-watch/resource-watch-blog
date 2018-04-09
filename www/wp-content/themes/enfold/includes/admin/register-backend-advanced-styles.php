<?php
$weight 	= array(__('Default','avia_framework') => '' , __('Normal','avia_framework') =>'normal', __('Bold','avia_framework')=>'bold', __('Light','avia_framework')=>'lighter');
$transform 	= array(__('Default','avia_framework') => '' , __('None'  ,'avia_framework') =>'none', __('Uppercase','avia_framework')=>'uppercase', __('Lowercase','avia_framework')=>'lowercase');
$align 	= array(__('Default','avia_framework') => '' , __('Left'  ,'avia_framework') =>'left', __('Center','avia_framework')=>'center', __('Right','avia_framework')=>'right');
$decoration = array(__('Default','avia_framework') => '' , __('None','avia_framework')=>'none !important' , __('Underline'  ,'avia_framework') =>'underline !important', __('Overline','avia_framework')=>'overline !important', __('Line Trough','avia_framework')=>'line-through !important');
$display 	= array(__('Default','avia_framework') => '' , __('Inline','avia_framework') =>'inline', __('Inline Block','avia_framework')=>'inline-block', __('Block','avia_framework')=>'block');


$advanced = array();


$advanced['body'] = array(
	"id"			=> "body", //needs to match array key
	"name"			=> "&lt;body&gt;",
	"group" 		=> __("HTML Tags",'avia_framework'),
	"description"	=> __("Change the styling for the &lt;body&gt; tag.",'avia_framework'),
	"selector"		=> array("body#top" => ""),
	"sections"		=> false,
	"hover"			=> false,
	"edit"			=> array(	
							'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
							'line_height'		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
							'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
							'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
							)
);

$advanced['paragraph'] = array(
	"id"			=> "paragraph", //needs to match array key
	"name"			=> "&lt;p&gt;",
	"group" 		=> __("HTML Tags",'avia_framework'),
	"description"	=> __("Change the styling for all &lt;p&gt; tags",'avia_framework'),
	"selector"		=> array("#top [sections] p" => array( 
															"font_size" => "font-size: %font_size%;", 
															"line_height" => "line-height: %line_height%;", 
															"font_weight" => "font-weight: %font_weight%;", 
															"margin" => "margin: %margin% 0;")
															),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	
							'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
							'line_height'=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
							'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
							'margin' 			=> array('type' => 'size', 'range' => '0.5-3', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Top &amp; Bottom margin",'avia_framework')),
							)
);

$advanced['strong'] = array(
	"id"			=> "strong", //needs to match array key
	"name"			=> "&lt;strong&gt;",
	"group" 		=> __("HTML Tags",'avia_framework'),
	"description"	=> __("Change the styling for all &lt;strong&gt; tags",'avia_framework'),
	"selector"		=> array("#top [sections] strong" => ""),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
							)
);

$advanced['blockquote'] = array(
	"id"			=> "blockquote", //needs to match array key
	"name"			=> "&lt;blockquote&gt;",
	"group" 		=> __("HTML Tags",'avia_framework'),
	"description"	=> __("Change the styling for all &lt;blockquote&gt; tags",'avia_framework'),
	"selector"		=> array("#top [sections] blockquote"=> ""),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'border_color' 		=> array('type' => 'colorpicker', 'name'=> __("Border Color",'avia_framework')),
								'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
							)
);

$advanced['underline'] = array(
	"id"			=> "underline", //needs to match array key
	"name"			=> "&lt;u&gt;",
	"group" 		=> __("HTML Tags",'avia_framework'),
	"description"	=> __("Change the styling for all &lt;u&gt; (underline) tags",'avia_framework'),
	"selector"		=> array("#top [sections] u, #top [sections] span[style*='text-decoration: underline;'], #top [sections] span[style*='text-decoration:underline;']"=> ""),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'text_decoration' 	=> array('type' => 'select', 'name'=> __("Text Decoration",'avia_framework'), 'options' => $decoration),
								'display' 			=> array('type' => 'select', 'name'=> __("Display",'avia_framework'), 'options' => $display),
							)
);


$advanced['mark'] = array(
	"id"			=> "mark", //needs to match array key
	"name"			=> "&lt;mark&gt;",
	"group" 		=> __("HTML Tags",'avia_framework'),
	"description"	=> __("Change the styling for all &lt;mark&gt; tags",'avia_framework'),
	"selector"		=> array("#top [sections] mark"=> ""),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'text_decoration' 	=> array('type' => 'select', 'name'=> __("Text Decoration",'avia_framework'), 'options' => $decoration),
								'display' 			=> array('type' => 'select', 'name'=> __("Display",'avia_framework'), 'options' => $display),
							)
);





$advanced['headings_all'] = array(
	"id"			=> "headings_all", //needs to match array key
	"name"			=> "All Headings (H1-H6)",
	"group" 		=> __("Headings",'avia_framework'),
	"description"	=> __("Change the styling for all Heading tags",'avia_framework'),
	"selector"		=> array("#top #wrap_all [sections] h1, #top #wrap_all [sections] h2, #top #wrap_all [sections] h3, #top #wrap_all [sections] h4, #top #wrap_all [sections] h5, #top #wrap_all [sections] h6"=> ""),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'text_transform' 	=> array('type' => 'select', 'name'=> __("Text Transform",'avia_framework'), 'options' => $transform ),							
								)

);

$advanced['h1'] = array(
	"id"			=> "h1", //needs to match array key
	"name"			=> "H1",
	"group" 		=> __("Headings",'avia_framework'),
	"description"	=> __("Change the styling for your H1 Tag",'avia_framework'),
	"selector"		=> array("#top #wrap_all [sections] h1"=> ""),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'text_transform' 	=> array('type' => 'select', 'name'=> __("Text Transform",'avia_framework'), 'options' => $transform ),
							)

);





$advanced['h2'] = array(
	"id"			=> "h2", //needs to match array key
	"name"			=> "H2",
	"group" 		=> __("Headings",'avia_framework'),
	"description"	=> __("Change the styling for your H2 Tag",'avia_framework'),
	"selector"		=> array("#top #wrap_all [sections] h2"=> ""),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'text_transform' 	=> array('type' => 'select', 'name'=> __("Text Transform",'avia_framework'), 'options' => $transform ),		
								)
);

$advanced['h3'] = array(
	"id"			=> "h3", //needs to match array key
	"name"			=> "H3",
	"group" 		=> __("Headings",'avia_framework'),
	"description"	=> __("Change the styling for your H3 Tag",'avia_framework'),
	"selector"		=> array("#top #wrap_all [sections] h3"=> ""),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'text_transform' 	=> array('type' => 'select', 'name'=> __("Text Transform",'avia_framework'), 'options' => $transform ),		
							)
);

$advanced['h4'] = array(
	"id"			=> "h4", //needs to match array key
	"name"			=> "H4",
	"group" 		=> __("Headings",'avia_framework'),
	"description"	=> __("Change the styling for your H4 Tag",'avia_framework'),
	"selector"		=> array("#top #wrap_all [sections] h4"=> ""),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'text_transform' 	=> array('type' => 'select', 'name'=> __("Text Transform",'avia_framework'), 'options' => $transform ),		
							)
);

$advanced['h5'] = array(
	"id"			=> "h5", //needs to match array key
	"name"			=> "H5",
	"group" 		=> __("Headings",'avia_framework'),
	"description"	=> __("Change the styling for your H5 Tag",'avia_framework'),
	"selector"		=> array("#top #wrap_all [sections] h5"=> ""),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'text_transform' 	=> array('type' => 'select', 'name'=> __("Text Transform",'avia_framework'), 'options' => $transform ),		
							)
);

$advanced['h6'] = array(
	"id"			=> "h6", //needs to match array key
	"name"			=> "H6",
	"group" 		=> __("Headings",'avia_framework'),
	"description"	=> __("Change the styling for your H6 Tag",'avia_framework'),
	"selector"		=> array("#top #wrap_all [sections] h6"=> ""),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'text_transform' 	=> array('type' => 'select', 'name'=> __("Text Transform",'avia_framework'), 'options' => $transform ),		
							)
);



$advanced['main_menu'] = array(
	"id"			=> "main_menu", //needs to match array key
	"name"			=> __("Main Menu Links",'avia_framework'),
	"group" 		=> __("Main Menu",'avia_framework'),
	"description"	=> __("Change the styling for your main menu links",'avia_framework'),
	"selector"		=> array(
		/*trick: hover is used inside the selector to prevent it from beeing applied when :hover is checked*/
		"#top #header[hover]_main_alternate" => array(  "background_color" => "background-color: %background_color%;" ),
		"#top #header .av-main-nav > li[hover] " => array(  "font_family" => "font-family: %font_family%;" ),
		"#top #header .av-main-nav > li[hover] > a" => "",
		".av_seperator_small_border .av-main-nav > li[hover] > a > .avia-menu-text,
		#top #wrap_all #header #menu-item-search[hover]>a
		
		"=> array(  "border_color" => "border-color: %border_color%;" ),
		"#top #header .av-main-nav > li[hover] > a .avia-menu-text, #top #header .av-main-nav > li[hover] > a .avia-menu-subtext"=> array(  "color" => "color: %color%;" )
	),
	"sections"		=> false,
	"hover"			=> true,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'border_color' 		=> array('type' => 'colorpicker', 'name'=> __("Border Color",'avia_framework')),
								'font_size' 		=> array('type' => 'size', 'range' => '8-30', 'name'=> __("Font Size",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
							)						
);


$advanced['main_menu_dropdown'] = array(
	"id"			=> "main_menu_dropdown", //needs to match array key
	"name"			=> __("Main Menu sublevel Links",'avia_framework'),
	"group" 		=> __("Main Menu",'avia_framework'),
	"description"	=> __("Change the styling for your main menu dropdown links",'avia_framework'),
	"selector"		=> array("#top #wrap_all .av-main-nav ul > li[hover] > a, #top #wrap_all .avia_mega_div, #top #wrap_all .avia_mega_div ul, #top #wrap_all .av-main-nav ul ul"=> ""),
	"sections"		=> false,
	"hover"			=> true,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'border_color' 		=> array('type' => 'colorpicker', 'name'=> __("Border Color",'avia_framework')),
								'font_size' 		=> array('type' => 'size', 'range' => '8-30', 'name'=> __("Font Size",'avia_framework')),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-3', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
							)						
);

$advanced['top_bar'] = array(
	"id"			=> "top_bar", //needs to match array key
	"name"			=> __("Small bar above Main Menu",'avia_framework'),
	"group" 		=> __("Main Menu",'avia_framework'),
	"description"	=> __("Change the styling for the small bar above the main menu which can contain social icons, a second menu and a phone number ",'avia_framework'),
	"selector"		=> array(
							 	"#top #header_meta, #top #header_meta nav ul ul li, #top #header_meta nav ul ul a, #top #header_meta nav ul ul" => array("background_color" => "background-color: %background_color%;"), 
							 	"#top #header_meta a, #top #header_meta li, #top #header_meta .phone-info" => array( "border_color" => "border-color: %border_color%;", "color" => "color: %color%;"),
							 	"#top #header_meta" => array("font_family" => "font-family: %font_family%;"), 
							 	
							 ),
	"sections"		=> false,
	"hover"			=> false,
	"edit"			=> array(	'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework')),
								'border_color' 		=> array('type' => 'colorpicker', 'name'=> __("Border Color",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
							)						
);



/*
$scale 	= array(__('Default','avia_framework') => '' , __('Small','avia_framework') =>'0.6');

$advanced['main_menu_icon_style'] = array(
	"id"			=> "main_menu_icon_style", //needs to match array key
	"name"			=> __("Main Menu Icon",'avia_framework'),
	"group" 		=> __("Main Menu (Icon)",'avia_framework'),
	"description"	=> __("Change the styling for your main menu links once they are displayed in the page overlay",'avia_framework'),
	"selector"		=> array( 
		"#top .av-burger-menu-main>a" => array("size"=>"padding:0;"),
		"#header .av-hamburger" => array("size"=>"-ms-transform: scale(%size%); transform: scale(%size%); transform-origin: right;"),
		".header_color .av-hamburger-inner, .header_color .av-hamburger-inner::before, .header_color .av-hamburger-inner::after" => array("color"=>"background-color: %color%")
	
	),
	"sections"		=> false,
	"hover"			=> false,
	"edit"			=> array(	
		'color' 	=> array('type' => 'colorpicker', 'name'=> __("Icon Color",'avia_framework')), 
		'size' 		=> array('type' => 'select', 'name'=> __("Icon Size",'avia_framework'), 'options' => $scale),
							)
);
*/



$advanced['main_menu_icon'] = array(
	"id"			=> "main_menu_icon", //needs to match array key
	"name"			=> __("Menu Links in overlay/slide out",'avia_framework'),
	"group" 		=> __("Main Menu (Icon)",'avia_framework'),
	"description"	=> __("Change the styling for your main menu links once they are displayed in the page overlay/slide out",'avia_framework'),
	"selector"		=> array(
		"#top #wrap_all .av-burger-overlay .av-burger-overlay-scroll #av-burger-menu-ul li a" => array(
								'color' 		=> "color:%color%;",
								"font_family" 	=> "font-family: %font_family% ,'Helvetica Neue', Helvetica, Arial, sans-serif;", 
								"font_weight" 	=> "font-weight: %font_weight%;",
								"letter_spacing" => "letter-spacing: %letter_spacing%;",
								"text_transform" => "text-transform: %text_transform%;",
								"line_height" 	=> "line-height: %line_height%;",
								),
		"#top #wrap_all #av-burger-menu-ul li" => array(
								'font_size' 	=> "font-size:%font_size%;",
								"line_height" 	=> "line-height: %line_height% !important;",
								),
		".av-burger-overlay-active #top #wrap_all .av-hamburger-inner, .av-burger-overlay-active #top #wrap_all .av-hamburger-inner::before, .av-burger-overlay-active #top #wrap_all .av-hamburger-inner::after, .html_av-overlay-side-classic #top div .av-burger-overlay li li .avia-bullet" => array(
			'color'=> "background-color:%color%;",
		),
		"div.av-burger-overlay-bg" => array(
			'background_color' => "background-color:%background_color%;",
		),
		".av-burger-overlay-active #top #wrap_all #header #menu-item-search a, .av-burger-overlay-active #top #wrap_all #main #menu-item-search a, .av-burger-overlay-active #top #wrap_all #menu-item-search a:hover" => array(
			'color' => "color:%color%;",
		),
		"#top #wrap_all .av-burger-overlay-scroll" => array(
			'menu_bg' => "background-color:%menu_bg%;",
		),
		".html_av-overlay-side #top #wrap_all div .av-burger-overlay-scroll #av-burger-menu-ul a:hover" => array(
			'menu_bg_hover' => "background-color:%menu_bg_hover%;",
		),
		".html_av-overlay-side-classic #top #wrap_all .av-burger-overlay #av-burger-menu-ul li a" => array(
			'border_color' => "border-color:%border_color%;",
		),
		
	
	),
	"sections"		=> false,
	"hover"			=> false,
	"edit"			=> array(	
								'color' 			=> array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework')), 
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Overlay Color",'avia_framework')), 
								'menu_bg' 			=> array('type' => 'colorpicker', 'name'=> __("Menu Background",'avia_framework')), 
								'menu_bg_hover' 	=> array('type' => 'colorpicker', 'name'=> __("Menu Hover BG",'avia_framework')), 
								'border_color' 		=> array('type' => 'colorpicker', 'name'=> __("Border Color",'avia_framework')), 
								
								'hr1' 				=> array('type' => 'hr'), 
		
								'font_size' 		=> array('type' => 'size', 'range' => '8-120', 'name'=> __("Font Size",'avia_framework')),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-3', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'letter_spacing' 	=> array('type' => 'size', 'range' => array(-10,20), 'increment' => 1, 'unit' => 'px',  'name'=> __("Letter Spacing",'avia_framework')),
								'text_transform' 	=> array('type' => 'select', 'name'=> __("Text Transform",'avia_framework'), 'options' => $transform ),
								
								
							)
													
);














$advanced['hover_overlay'] = array(
	"id"			=> "hover_overlay", //needs to match array key
	"name"			=> __("Linked Image Overlay",'avia_framework'),
	"group" 		=> __("Misc",'avia_framework'),
	"description"	=> __("Change the styling for the overlay that appears when you place your mouse cursor above a linked image",'avia_framework'),
	"selector"		=> array(  
								"#top [sections] .image-overlay-inside" => array("overlay_style" => array( "none" , "display: none;") ),
								"#top [sections] .image-overlay" 		=> array("background_color" => "background-color: %background_color%;", "overlay_style" => array( "hide" , "visibility: hidden;")),   
								"#top [sections] div .image-overlay" 		=> array("overlay_style" => array( "icon" , "background: none;")),   
								"#top [sections] .image-overlay .image-overlay-inside:before" => array( "icon_color" => "background-color: %icon_color%;", "color" => "color: %color%;" )
							),
							
							
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	'overlay_style' 	=> array('type' => 'select', 'name'=> __("Overlay Style",'avia_framework'), 'options' => array(__('Default','avia_framework') => '' , __('Minimal Overlay (No Icon)','avia_framework') =>'none' , __('Icon only','avia_framework') =>'icon' , __('Disable Overlay','avia_framework') =>'hide' )) ,		
								'color' 			=> array('type' => 'colorpicker', 'name'=> __("Icon Color",'avia_framework')), 
								'icon_color' 		=> array('type' => 'colorpicker', 'name'=> __("Icon background",'avia_framework')),
								'background_color' 	=> array('type' => 'colorpicker', 'name'=> __("Overlay Color",'avia_framework')),
							)						
);


$advanced['buttons'] = array(
	"id"			=> "buttons", //needs to match array key
	"name"			=> "Buttons",
	"group" 		=> __("Misc",'avia_framework'),
	"description"	=> __("Change the styling of your buttons",'avia_framework'),
	"selector"		=> array(
	"#top #wrap_all .avia-slideshow-button, #top .avia-button, .html_elegant-blog .more-link, .avia-slideshow-arrows a:before, #top .av-menu-button > a .avia-menu-text"=> array('border_radius' => "border-radius: %border_radius%;"),
	"#top #wrap_all .avia-button.avia-color-light, #top #wrap_all .avia-button.avia-color-dark" => array('border_width' => 'border-width:%border_width%;'),
	"#top #wrap_all .avia-button.avia-color-light" => array('opacity' => 'color:#fff; border-color:#fff; background:transparent;'),
	"#top #wrap_all .avia-button.avia-color-dark" => array('opacity' => 'color:#000; border-color:#000; background:transparent;'),
	
	),
	
	"sections"		=> false,
	"hover"			=> false,
	"edit"			=> array(	
								'border_radius' => array('type' => 'size', 'range' => '0-100', 'name'=> __("Border Radius",'avia_framework')),
								'border_width' 	=> array('type' => 'size', 'range' => array(1,10), 'increment' => 1, 'unit' => 'px',  'name'=> __("Border width and",'avia_framework')),
								'opacity' 	=> array('type' => 'select', 'name'=> __("opacity for transparent buttons",'avia_framework'), 
								'options' => array(__('Semi-transparent','avia_framework') => '' , __('Full-transparent','avia_framework') => 'off' )),
							)
);


$advanced['widget_title'] = array(
	"id"			=> "widget_title", //needs to match array key
	"name"			=> "Widget title",
	"group" 		=> __("Misc",'avia_framework'),
	"description"	=> __("Change the styling of your widget title",'avia_framework'),
	"selector"		=> array(
						"#top [sections] .widgettitle" => array("style" => array( "border" , "border-style:solid; border-width:1px; padding:10px; text-align:center; margin-bottom:15px") ),
						"html #top [sections] .widgettitle" => array("style" => array( "border-tp" , "border-style:solid; border-width:1px; padding:10px 0; border-left:none; border-right:none; margin-bottom:15px") ),
						"body#top [sections] .widgettitle" => array( "border_color" => "border-color: %border_color%;", "background_color" => "background-color: %background_color%;", "color" => "color: %color%;", "text_align" => "text-align: %text_align%;"),
						
							),
	"sections"		=> true,
	"hover"			=> false,
	"edit"			=> array(	
								'style' 	=> array(
													'type' => 'select', 
													'name'=> __("Overlay Style",'avia_framework'), 
													'options' => array(
														__('No Border','avia_framework') => '' , 
														__('Border on top and bottom','avia_framework') =>'border-tp' , 
														__('Border around the widget title','avia_framework') =>'border' , 
													)
												) ,
								'border_color' => array('type' => 'colorpicker', 'name'=> __("Border Color",'avia_framework') ),
								'background_color' => array('type' => 'colorpicker', 'name'=> __("Background Color",'avia_framework') ),
								'color' => array('type' => 'colorpicker', 'name'=> __("Font Color",'avia_framework') ),
								'text_align' 	=> array('type' => 'select', 'name'=> __("Text Align",'avia_framework'), 'options' => $align ),							
							)
);

$advanced['slideshow_titles'] = array(
	"id"			=> "slideshow_titles", //needs to match array key
	"name"			=> "Slideshow titles",
	"group" 		=> __("Misc",'avia_framework'),
	"description"	=> __("Change the styling for your fullscreen, fullwidth and easy slider title",'avia_framework'),
	"selector"		=> array("#top #wrap_all .slideshow_caption h2.avia-caption-title, #top #wrap_all .av-slideshow-caption h2.avia-caption-title"=> ""),
	"sections"		=> false,
	"hover"			=> false,
	"edit"			=> array(	
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'letter_spacing' 	=> array('type' => 'size', 'range' => array(-10,20), 'increment' => 1, 'unit' => 'px',  'name'=> __("Letter Spacing",'avia_framework')),
								'text_transform' 	=> array('type' => 'select', 'name'=> __("Text Transform",'avia_framework'), 'options' => $transform ),
							)
);


$advanced['slideshow_caption'] = array(
	"id"			=> "slideshow_caption", //needs to match array key
	"name"			=> "Slideshow caption",
	"group" 		=> __("Misc",'avia_framework'),
	"description"	=> __("Change the styling for your fullscreen, fullwidth and easy slider caption",'avia_framework'),
	"selector"		=> array(

		"#top #wrap_all .avia-caption-content p" => array( 
															"font_family" 	=> "font-family: %font_family% ,'Helvetica Neue', Helvetica, Arial, sans-serif;", 
															"font_weight" 	=> "font-weight: %font_weight%;",
															"line_height" 	=> "line-height: %line_height%;",
															"letter_spacing" => "letter-spacing: %letter_spacing%;",
															"text_transform" => "text-transform: %text_transform%;",
															 ),
		"#top .slideshow_caption" => array( "width" => "width: %width%;"),
	),
	"sections"		=> false,
	"hover"			=> false,
	"edit"			=> array(	
								
								'font_family' 		=> array('type' => 'font', 'name'=> __("Font Family",'avia_framework'), 'options' => $google_fonts),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'letter_spacing' 	=> array('type' => 'size', 'range' => array(-10,20), 'increment' => 1, 'unit' => 'px',  'name'=> __("Letter Spacing",'avia_framework')),
								'text_transform' 	=> array('type' => 'select', 'name'=> __("Text Transform",'avia_framework'), 'options' => $transform ),
								'width' 			=> array('type' => 'size', 'range' => array(40,100), 'increment' => 10, 'unit' => '%',  'name'=> __("Container Width",'avia_framework')),
							)
);

$advanced['slideshow_button'] = array(
	"id"			=> "slideshow_button", //needs to match array key
	"name"			=> "Slideshow button",
	"group" 		=> __("Misc",'avia_framework'),
	"description"	=> __("Change the styling for your fullscreen, fullwidth and easy slider buttons",'avia_framework'),
	"selector"		=> array("#top #wrap_all .avia-slideshow-button"=> ""),
	"sections"		=> false,
	"hover"			=> false,
	"edit"			=> array(	
								'font_size' 		=> array('type' => 'size', 'range' => '8-80', 'name'=> __("Font Size",'avia_framework')),
								'font_weight' 		=> array('type' => 'select', 'name'=> __("Font Weight",'avia_framework'), 'options' => $weight),
								'line_height' 		=> array('type' => 'size', 'range' => '0.7-2', 'increment' => 0.1, 'unit' => 'em',  'name'=> __("Line Height",'avia_framework')),
								'letter_spacing' 	=> array('type' => 'size', 'range' => array(-10,20), 'increment' => 1, 'unit' => 'px',  'name'=> __("Letter Spacing",'avia_framework')),
								'text_transform' 	=> array('type' => 'select', 'name'=> __("Text Transform",'avia_framework'), 'options' => $transform ),
							)
);


//body font size
//dropdown menu
//icon colors
//hover states
//links
// all sections/specific section