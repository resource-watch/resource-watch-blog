<?php
/*
ATTENTION: Changes to this file will only be visible in your frontend after you have re-saved your Themes Styling Page
*/



/*
This file holds ALL color information of the theme that is applied with the styling backend admin panel.
It is recommended to not edit this file, instead create new styles in custom.css and overwrite the styles within this file

Example of available values
$bg 				=> #222222
$bg2 				=> #f8f8f8
$primary 			=> #c8ccc2
$secondary			=> #182402
$color	 			=> #ffffff
$border 			=> #e1e1e1
$img 				=> /wp-content/themes/skylink/images/background-images/dashed-cross-dark.png
$pos 				=> top left
$repeat 			=> no-repeat
$attach 			=> scroll
$heading 			=> #eeeeee
$meta 				=> #888888
$background_image	=> #222222 url(/wp-content/themes/skylink/images/background-images/dashed-cross-dark.png) top left no-repeat scroll
$default_font_size  => empty or a px size
*/


global 	$avia_config;
$output = "";
$body_color = "";

extract($color_set);
if ($main_color !== NULL) { extract($main_color); }
extract($styles);

unset($background_image);
######################################################################
# CREATE THE CSS DYNAMIC CSS RULES
######################################################################
/*default*/

$output .= "

::-moz-selection{
background-color: $primary;
color: $bg;
}

::selection{
background-color: $primary;
color: $bg;
}

";


/* not needed since we got no "boxed" option*/
$output .= "

html.html_boxed {background: $body_background;}

";

if($default_font_size)
{
	$output .= "body, body .avia-tooltip {font-size: $default_font_size; }";
}


/*color sets*/
foreach ($color_set as $key => $colors) // iterates over the color sets: usually $key is either: header_color, main_color, footer_color, socket_color
{
	$key = ".".$key;
	extract($colors);
	$constant_font 	= avia_backend_calc_preceived_brightness($primary, 230) ?  '#ffffff' : $bg;
	$button_border  = avia_backend_calculate_similar_color($primary, 'darker', 2);
	$button_border2 = avia_backend_calculate_similar_color($secondary, 'darker', 2);
	
	/*general styles*/
	$output.= "
$key, $key div, $key header, $key main, $key aside, $key footer, $key article, $key nav, $key section, $key  span, $key  applet, $key object, $key iframe, $key h1, $key h2, $key h3, $key h4, $key h5, $key h6, $key p, $key blockquote, $key pre, $key a, $key abbr, $key acronym, $key address, $key big, $key cite, $key code, $key del, $key dfn, $key em, $key img, $key ins, $key kbd, $key q, $key s, $key samp, $key small, $key strike, $key strong, $key sub, $key sup, $key tt, $key var, $key b, $key u, $key i, $key center, $key dl, $key dt, $key dd, $key ol, $key ul, $key li, $key fieldset, $key form, $key label, $key legend, $key table, $key caption, $key tbody, $key tfoot, $key thead, $key tr, $key th, $key td, $key article, $key aside, $key canvas, $key details, $key embed, $key figure, $key fieldset, $key figcaption, $key footer, $key header, $key hgroup, $key menu, $key nav, $key output, $key ruby, $key section, $key summary, $key time, $key mark, $key audio, $key video, #top $key .pullquote_boxed, .responsive #top $key .avia-testimonial, .responsive #top.avia-blank #main $key.container_wrap:first-child, #top $key.fullsize .template-blog .post_delimiter, $key .related_posts.av-related-style-full a{
border-color:$border;
}

$key .rounded-container, #top $key .pagination a:hover, $key .small-preview, $key .fallback-post-type-icon{
background:$meta;
color:$bg;
}

$key .av-default-color, #top $key .av-force-default-color, $key .av-catalogue-item, $key .wp-playlist-item .wp-playlist-caption, $key .wp-playlist{
color: $color;
}

$key , $key .site-background, $key .first-quote,  $key .related_image_wrap, $key .gravatar img  $key .hr_content, $key .news-thumb, $key .post-format-icon, $key .ajax_controlls a, $key .tweet-text.avatar_no, $key .toggler, $key .toggler.activeTitle:hover, $key #js_sort_items, $key.inner-entry, $key .grid-entry-title, $key .related-format-icon,  .grid-entry $key .avia-arrow, $key .avia-gallery-big, $key .avia-gallery-big, $key .avia-gallery img, $key .grid-content, $key .av-share-box ul, #top $key .av-related-style-full .related-format-icon, $key .related_posts.av-related-style-full a:hover, $key.avia-fullwidth-portfolio .pagination .current,  $key.avia-fullwidth-portfolio .pagination a, $key .av-hotspot-fallback-tooltip-inner, $key .av-hotspot-fallback-tooltip-count{
background-color:$bg;
color: $color;
}

$key .heading-color, $key a.iconbox_icon:hover, $key h1, $key h2, $key h3, $key h4, $key h5, $key h6, $key .sidebar .current_page_item>a, $key .sidebar .current-menu-item>a, $key .pagination .current, $key .pagination a:hover, $key strong.avia-testimonial-name, $key .heading, $key .toggle_content strong, $key .toggle_content strong a, $key .tab_content strong, $key .tab_content strong a , $key .asc_count, $key .avia-testimonial-content strong, $key div .news-headline, #top $key .av-related-style-full .av-related-title, $key .av-default-style .av-countdown-cell-inner .av-countdown-time, $key .wp-playlist-item-meta.wp-playlist-item-title, #top $key .av-no-image-slider h2 a, $key .av-small-bar .avia-progress-bar .progressbar-title-wrap{
    color:$heading;
}

$key .meta-color, $key .sidebar, $key .sidebar a, $key .minor-meta, $key .minor-meta a, $key .text-sep, $key blockquote, $key .post_nav a, $key .comment-text, $key .side-container-inner, $key .news-time, $key .pagination a, $key .pagination span,  $key .tweet-text.avatar_no .tweet-time, #top $key .extra-mini-title, $key .team-member-job-title, $key .team-social a, $key #js_sort_items a, .grid-entry-excerpt, $key .avia-testimonial-subtitle, $key .commentmetadata a,$key .social_bookmarks a, $key .meta-heading>*, $key .slide-meta, $key .slide-meta a, $key .taglist, $key .taglist a, $key .phone-info, $key .phone-info a, $key .av-sort-by-term a, $key .av-magazine-time, $key .av-magazine .av-magazine-entry-icon, $key .av-catalogue-content, $key .wp-playlist-item-length, .html_modern-blog #top div $key .blog-categories a, .html_modern-blog #top div $key .blog-categories a:hover{
color: $meta;
}

$key .special-heading-inner-border{ border-color: $color; }
$key .meta-heading .special-heading-inner-border{ border-color: $meta; }

$key a, $key .widget_first, $key strong, $key b, $key b a, $key strong a, $key #js_sort_items a:hover, $key #js_sort_items a.active_sort, $key .av-sort-by-term a.active_sort, $key .special_amp, $key .taglist a.activeFilter, $key #commentform .required, #top $key .av-no-color.av-icon-style-border a.av-icon-char, .html_elegant-blog #top $key .blog-categories a, .html_elegant-blog #top $key .blog-categories a:hover{
color:$primary;
}

$key a:hover, $key h1 a:hover, $key h2 a:hover, $key h3 a:hover, $key h4 a:hover, $key h5 a:hover, $key h6 a:hover,  $key .template-search  a.news-content:hover, $key .wp-playlist-item .wp-playlist-caption:hover{
color: $secondary;
}

$key .primary-background, $key .primary-background a, div $key .button, $key #submit, $key input[type='submit'], $key .small-preview:hover, $key .avia-menu-fx, $key .avia-menu-fx .avia-arrow, $key.iconbox_top .iconbox_icon, $key .iconbox_top a.iconbox_icon:hover, $key .avia-data-table th.avia-highlight-col, $key .avia-color-theme-color, $key .avia-color-theme-color:hover, $key .image-overlay .image-overlay-inside:before, $key .comment-count, $key .av_dropcap2, #top #wrap_all $key .av-menu-button-colored > a .avia-menu-text, $key .av-colored-style .av-countdown-cell-inner, .responsive #top $key .av-open-submenu.av-subnav-menu > li > a:hover, #top $key .av-open-submenu.av-subnav-menu li > ul a:hover{
background-color: $primary;
color:$constant_font;
border-color:$button_border;
}

#top $key .mobile_menu_toggle{
color: $primary;
background:$bg;
}

#top $key .av-menu-mobile-active .av-subnav-menu > li > a:before{
color: $primary;
}

#top $key .av-open-submenu.av-subnav-menu > li > a:hover:before{
color: $bg;
}


$key .button:hover, $key .ajax_controlls a:hover, $key #submit:hover, $key .big_button:hover, $key .contentSlideControlls a:hover, $key #submit:hover , $key input[type='submit']:hover{
background-color: $secondary;
color:$bg;
border-color:$button_border2;
}

$key .ajax_controlls a:hover{
border-color:$secondary;
}

$key .timeline-bullet{
background-color:$border;
border-color: $bg;
}

$key table, $key .widget_nav_menu ul:first-child>.current-menu-item, $key .widget_nav_menu ul:first-child>.current_page_item, $key .widget_nav_menu ul:first-child>.current-menu-ancestor, $key .pagination .current, $key .pagination a, $key.iconbox_top .iconbox_content, $key .av_promobox, $key .toggle_content, $key .toggler:hover, #top $key .av-minimal-toggle .toggler, $key .related_posts_default_image, $key .search-result-counter, $key .container_wrap_meta, $key .avia-content-slider .slide-image, $key .avia-slider-testimonials .avia-testimonial-content, $key .avia-testimonial-arrow-wrap .avia-arrow, $key .news-thumb, $key .portfolio-preview-content, $key .portfolio-preview-content .avia-arrow, $key .av-magazine .av-magazine-entry-icon, $key .related_posts.av-related-style-full a, $key .aviaccordion-slide, $key.avia-fullwidth-portfolio .pagination, $key .isotope-item.special_av_fullwidth .av_table_col.portfolio-grid-image, $key .av-catalogue-list li:hover, $key .wp-playlist, $key .avia-slideshow-fixed-height > li, $key .avia-form-success, $key .av-boxed-grid-style .avia-testimonial{
background: $bg2;
}



#top $key .post_timeline li:hover .timeline-bullet{
background-color:$secondary;
}

$key blockquote, $key .avia-bullet, $key .av-no-color.av-icon-style-border a.av-icon-char{
border-color:$primary;
}

.html_header_top $key .main_menu ul:first-child >li > ul, .html_header_top #top $key .avia_mega_div > .sub-menu{
border-top-color:$primary;
}

$key .breadcrumb, $key .breadcrumb a, #top $key.title_container .main-title, #top $key.title_container .main-title a{
color:$color;
}


$key .av-icon-display, #top $key .av-related-style-full a:hover .related-format-icon, $key .av-default-style .av-countdown-cell-inner{
background-color:$bg2;
color:$meta;
}

$key .av-masonry-entry:hover .av-icon-display{
background-color: $primary;
color:$constant_font;
border-color:$button_border;
}

#top $key .av-masonry-entry.format-quote:hover .av-icon-display{
color:$primary;
}


$key ::-webkit-input-placeholder {color: $meta; }
$key ::-moz-placeholder {color: $meta; opacity:1; }
$key :-ms-input-placeholder {color: $meta;}


";



// menu colors
$output.= "


$key .header_bg, $key .main_menu ul ul, $key .main_menu .menu ul li a, $key .pointer_arrow_wrap .pointer_arrow, $key .avia_mega_div, $key .av-subnav-menu > li ul, $key .av-subnav-menu a{
background-color:$bg;
color: $meta;
}

$key .main_menu .menu ul li a:hover, $key .av-subnav-menu ul a:hover{
background-color:$bg2;
}

$key .sub_menu>ul>li>a, $key .sub_menu>div>ul>li>a, $key .main_menu ul:first-child > li > a, #top $key .main_menu .menu ul .current_page_item > a, #top $key .main_menu .menu ul .current-menu-item > a , #top $key .sub_menu li ul a{
color:$meta;
}

#top $key .main_menu .menu ul li>a:hover{
color:$color;
}

$key .av-subnav-menu a:hover,
$key .main_menu ul:first-child > li a:hover,
$key .main_menu ul:first-child > li.current-menu-item > a,
$key .main_menu ul:first-child > li.current_page_item > a,
$key .main_menu ul:first-child > li.active-parent-item > a{
color:$color;
}

#top $key .main_menu .menu .avia_mega_div ul .current-menu-item > a{
color:$primary;
}

$key .sub_menu>ul>li>a:hover, $key .sub_menu>div>ul>li>a:hover{
color:$color;
}

#top $key .sub_menu ul li a:hover,
$key .sub_menu ul:first-child > li.current-menu-item > a,
$key .sub_menu ul:first-child > li.current_page_item > a,
$key .sub_menu ul:first-child > li.active-parent-item > a{
color:$color;
}

$key .sub_menu li ul a, $key #payment, $key .sub_menu ul li, $key .sub_menu ul, #top $key .sub_menu li li a:hover{
background-color: $bg;
}

$key#header .avia_mega_div > .sub-menu.avia_mega_hr, .html_bottom_nav_header.html_logo_center #top #menu-item-search>a{
border-color:$border;
}

@media only screen and (max-width: 767px) { 

#top #wrap_all .av_header_transparency{
	background-color:$bg;
	color: $color;
	border-color: $border;
}

}

";




//apply background image if available
if(isset($background_image))
{
	$output .= "$key .header_bg { background: $background_image; }
	";
}







//tooltips +  ajax search
$output.= "


$key .avia-tt, $key .avia-tt .avia-arrow, $key .avia-tt .avia-arrow{
background-color: $bg;
color: $meta;
}

$key .ajax_search_image{
background-color: $primary;
color:$bg;
}

$key .ajax_search_excerpt{
color: $meta;
}

#top $key .ajax_search_entry:hover{
background-color:$bg2;
}

$key .ajax_search_title{
color: $heading;
}

$key .ajax_load{
background-color:$primary;
}

";

//button
$button_font = avia_backend_calc_preceived_brightness($primary, 230) ?  '#ffffff' : $bg;

$output.= "
#top $key .avia-color-theme-color{
color: $button_font;
border-color: $button_border;
}


$key .avia-color-theme-color-subtle{
background-color:$bg2;
color: $color;
}

$key .avia-color-theme-color-subtle:hover{
background-color:$bg;
color: $heading;
}

#top $key .avia-color-theme-color-highlight{
color: $button_font;
border-color: $secondary;
background-color: $secondary;
}


";

//icon list

$iconlist = avia_backend_calculate_similar_color($border, 'darker', 1);
$output.= "
$key .avia-icon-list .iconlist_icon{
background-color:$iconlist;
}

$key .avia-icon-list .iconlist-timeline{
border-color:$border;
}

$key .iconlist_content{
color:$meta;
}

";




// form fields
$output.= "

#top $key .input-text, #top $key input[type='text'], #top $key input[type='input'], #top $key input[type='password'], #top $key input[type='email'], #top $key input[type='number'], #top $key input[type='url'], #top $key input[type='tel'], #top $key input[type='search'], #top $key textarea, #top $key select{
border-color:$border;
background-color: $bg2;
color:$meta;
}

#top $key .invers-color .input-text, #top $key .invers-color input[type='text'], #top $key .invers-color input[type='input'], #top $key .invers-color input[type='password'], #top $key .invers-color input[type='email'], #top $key .invers-color input[type='number'], #top $key .invers-color input[type='url'], #top $key .invers-color input[type='tel'], #top $key .invers-color input[type='search'], #top $key .invers-color textarea, #top $key .invers-color select{
background-color: $bg;
}

$key .required{
color:$primary;
}


";



// masonry
$masonry = avia_backend_calculate_similar_color($bg2, 'darker', 1);
$output.= "

$key .av-masonry{
	background-color: $masonry;
 }

$key .av-masonry-pagination, $key .av-masonry-pagination:hover, $key .av-masonry-outerimage-container{
	background-color: $bg;
}


$key .container .av-inner-masonry-content, #top $key .container .av-masonry-load-more, #top $key .container .av-masonry-sort, $key .container .av-masonry-entry .avia-arrow{
	background-color: $bg2;
}

";



// hr shortcode
$output.= "

 $key .hr-short .hr-inner-style,  $key .hr-short .hr-inner{

background-color: $bg;
}

";



//sidebar tab & Tabs shortcode
$output.= "
div  $key .tabcontainer .active_tab_content, div $key .tabcontainer  .active_tab{
background-color: $bg2;
color:$color;
}

.responsive.js_active #top $key .avia_combo_widget .top_tab .tab{
border-top-color:$border;
}


$key .template-archives  .tabcontainer a, #top $key .tabcontainer .tab:hover, #top $key .tabcontainer .tab.active_tab{
color:$color;
}

 $key .template-archives .tabcontainer a:hover{
color:$secondary;
}


$key .sidebar_tab_icon {
background-color: $border;
}

#top $key .sidebar_active_tab .sidebar_tab_icon {
background-color: $primary;
}

$key .sidebar_tab:hover .sidebar_tab_icon {
background-color: $secondary;
}

$key .sidebar_tab, $key .tabcontainer .tab{
color: $meta;
}

$key div .sidebar_active_tab , div  $key .tabcontainer.noborder_tabs .active_tab_content, div $key .tabcontainer.noborder_tabs  .active_tab{
color: $color;
background-color: $bg;
}

#top .avia-smallarrow-slider  .avia-slideshow-dots a{
background-color: $bg2;
}

#top $key .avia-smallarrow-slider  .avia-slideshow-dots a.active, #top $key .avia-smallarrow-slider  .avia-slideshow-dots a:hover{
background-color: $meta;
}


@media only screen and (max-width: 767px) {
	.responsive #top $key .tabcontainer .active_tab{ background-color: $secondary; color:$constant_font; } /*hard coded white to match the icons beside which are also white*/
	.responsive #top $key .tabcontainer{border-color:$border;}
	.responsive #top $key .active_tab_content{background-color: $bg2;}
}

";


//pricing table
$stripe = avia_backend_calculate_similar_color($primary, 'lighter', 2);
$stripe2 = avia_backend_calculate_similar_color($primary, 'lighter', 1);

$output.= "
$key tr:nth-child(even), $key .avia-data-table .avia-heading-row .avia-desc-col, $key .avia-data-table .avia-highlight-col, $key .pricing-table>li:nth-child(even), body $key .pricing-table.avia-desc-col li, #top $key  .avia-data-table.avia_pricing_minimal th{
background-color:$bg;
color: $color;
}

$key table caption, $key tr:nth-child(even), $key .pricing-table>li:nth-child(even), #top $key  .avia-data-table.avia_pricing_minimal td{
color: $meta;
}

$key tr:nth-child(odd), $key .pricing-table>li:nth-child(odd), $key .pricing-extra{
background: $bg2;
}

$key .pricing-table li.avia-pricing-row, $key .pricing-table li.avia-heading-row, $key .pricing-table li.avia-pricing-row .pricing-extra{
background-color: $primary;
color:$constant_font;
border-color:$stripe;
}

$key .pricing-table li.avia-heading-row, $key .pricing-table li.avia-heading-row .pricing-extra{
background-color: $stripe2;
color:$constant_font;
border-color:$stripe;
}

$key  .pricing-table.avia-desc-col .avia-heading-row, $key  .pricing-table.avia-desc-col .avia-pricing-row{
border-color:$border;
}

";




//media player + progress bar shortcode

$stripe = avia_backend_calculate_similar_color($primary, 'lighter', 2);

$output.= "
$key .theme-color-bar .bar{
background: $primary;
}



$key .mejs-controls .mejs-time-rail .mejs-time-current, $key .mejs-controls .mejs-volume-button .mejs-volume-slider .mejs-volume-current, $key .mejs-controls .mejs-horizontal-volume-slider .mejs-horizontal-volume-current, $key .button.av-sending-button, $key .av-striped-bar .theme-color-bar .bar{

background: $primary;
}

body $key .mejs-controls .mejs-time-rail .mejs-time-float {
background: $primary;
color: #fff;
}

body $key .mejs-controls .mejs-time-rail .mejs-time-float-corner {
border: solid 4px $primary;
border-color: $primary transparent transparent transparent;
}


$key .progress{
background-color:$bg2;
}

";

/*contact form send button*/

$stripe2nd = avia_backend_calculate_similar_color($secondary, 'lighter', 1);
$output.= " $key .button.av-sending-button{
background: $secondary;
background-image:	-webkit-linear-gradient(-45deg, $secondary 25%, $stripe2nd 25%, $stripe2nd 50%, $secondary 50%, $secondary 75%, $stripe2nd 75%, $stripe2nd);
background-image:      -moz-linear-gradient(-45deg, $secondary 25%, $stripe2nd 25%, $stripe2nd 50%, $secondary 50%, $secondary 75%, $stripe2nd 75%, $stripe2nd);
background-image:           linear-gradient(-45deg, $secondary 25%, $stripe2nd 25%, $stripe2nd 50%, $secondary 50%, $secondary 75%, $stripe2nd 75%, $stripe2nd);
border-color:$secondary;
}";



/*forum*/

$output.= "

$key span.bbp-admin-links a{
color: $primary;
}

$key span.bbp-admin-links a:hover{
color: $secondary;
}

#top $key .bbp-reply-content, #top $key .bbp-topic-content, #top $key .bbp-body .super-sticky .page-numbers, #top $key .bbp-body .sticky .page-numbers, #top $key .bbp-pagination-links a:hover, #top $key .bbp-pagination-links span.current{ background:$bg; }

#top $key .bbp-topics .bbp-header, #top $key .bbp-topics .bbp-header, #top $key .bbp-forums .bbp-header, #top $key .bbp-topics-front ul.super-sticky, #top $key .bbp-topics ul.super-sticky, #top $key .bbp-topics ul.sticky, #top $key .bbp-forum-content ul.sticky, #top $key .bbp-body .page-numbers{
background-color:$bg2;
}

#top $key .bbp-meta, #top $key .bbp-author-role, #top $key .bbp-author-ip, #top $key .bbp-pagination-count, #top $key .bbp-topics .bbp-body .bbp-topic-title:before{
color: $meta;
}

#top $key .bbp-admin-links{
color:$border;
}

$key #bbpress-forums li.bbp-body ul.forum, $key #bbpress-forums li.bbp-body ul.topic,
.avia_transform $key .bbp-replies .bbp-reply-author:before, 
.avia_transform .forum-search $key .bbp-reply-author:before,
.avia_transform .forum-search $key .bbp-topic-author:before{
background-color:$bg;
border-color:$border;
}

#top $key .bbp-author-name{
color:$heading;
}

$key .widget_display_stats dt, $key .widget_display_stats dd{
background-color:$bg2;
}

";



	//apply background image if available
	if(isset($background_image))
	{
		$output .= "$key { background: $background_image; }
		";
	}

	//button and dropcap color white unless primary color is very very light
	if(avia_backend_calc_preceived_brightness($primary, 220))
	{
		$output .= "

		$key dropcap2, $key dropcap3, $key avia_button, $key avia_button:hover, $key .on-primary-color, $key .on-primary-color:hover{
		color: $constant_font;
		}

		";
	}



	//only for certain areas
	switch($key)
	{
		case '.header_color':

		$constant_font = avia_backend_calc_preceived_brightness($primary, 230) ?  '#ffffff' : $bg;
		$output .= "

			#main, .avia-msie-8 .av_header_sticky_disabled#header{
			background-color:$bg;
			}
			
			.html_header_sidebar #header .av-main-nav > li > a .avia-menu-text{color:$heading;}
			.html_header_sidebar #header .av-main-nav > li > a .avia-menu-subtext{color:$meta;}
			.html_header_sidebar #header .av-main-nav > li:hover > a .avia-menu-text, 
			.html_header_sidebar #header .av-main-nav > li.current-menu-ancestor > a .avia-menu-text,
			.html_header_sidebar #header .av-main-nav li.current-menu-item > a .avia-menu-text
			{color:$primary;}
			
			#top #wrap_all .av_seperator_big_border#header .av-menu-button-colored > a{background-color: $primary; }
			#top #wrap_all .av_seperator_big_border#header .av-menu-button-bordered > a{background-color: $bg2; }
			
			
			html.html_header_sidebar #wrap_all{
			background-color:$bg;
			}
			
			$key .av-hamburger-inner, $key .av-hamburger-inner::before, $key .av-hamburger-inner::after{
				background-color:$meta;
			}
			
			
			.html_av-overlay-side #top .av-burger-overlay-scroll{background:$bg}
			
			.html_av-overlay-side #top #wrap_all div .av-burger-overlay-scroll #av-burger-menu-ul a:hover{background-color:$bg2;}
			
			
			.html_av-overlay-side-classic #top #wrap_all .av-burger-overlay #av-burger-menu-ul li a{ border-color: $border; }
			
			.html_av-overlay-side #top #wrap_all .av-burger-overlay-scroll #av-burger-menu-ul a{color:$color}
			
			.html_av-overlay-side.av-burger-overlay-active #top #wrap_all #header .menu-item-search-dropdown a{ color:$color }
			.html_av-overlay-side-classic #top .av-burger-overlay li li .avia-bullet,
			.html_av-overlay-side.av-burger-overlay-active #top .av-hamburger-inner, 
			.html_av-overlay-side.av-burger-overlay-active #top .av-hamburger-inner::before, 
			.html_av-overlay-side.av-burger-overlay-active #top .av-hamburger-inner::after{
				background-color:$color;
			}
			
			
			
			
			";
		
		if(!empty($avia_config['backend_colors']['burger_color']))
		{
			$output .= "
			$key .av-hamburger-inner, $key .av-hamburger-inner::before, $key .av-hamburger-inner::after{
				background-color:".$avia_config['backend_colors']['burger_color'].";
			}
			";
			
			$output .= " @media only screen and (max-width: 767px) {
				#top $key .av-hamburger-inner, #top $key .av-hamburger-inner::before, #top $key .av-hamburger-inner::after{
					background-color:".$avia_config['backend_colors']['burger_color'].";
				}
			}
			";
		}
			
		if(!empty($avia_config['backend_colors']['menu_transparent']))
		{
			$output .= "
			#top #wrap_all .av_header_transparency .main_menu ul:first-child > li > a, #top #wrap_all .av_header_transparency .sub_menu > ul > li > a, #top .av_header_transparency #header_main_alternate, .av_header_transparency #header_main .social_bookmarks li a{ color:inherit; border-color: transparent; background: transparent;}

			
			#top #wrap_all {$key}.av_header_transparency, #top #wrap_all {$key}.av_header_transparency .phone-info.with_nav span,
			#top #header{$key}.av_header_transparency .av-main-nav > li > a .avia-menu-text, #top #header{$key}.av_header_transparency .av-main-nav > li > a .avia-menu-subtext{
				color: ".$avia_config['backend_colors']['menu_transparent']."
			}
			
			#top {$key}.av_header_transparency .avia-menu-fx, 
			.av_header_transparency div .av-hamburger-inner, .av_header_transparency div .av-hamburger-inner::before, .av_header_transparency div .av-hamburger-inner::after{background:".$avia_config['backend_colors']['menu_transparent'].";}
			";
			
			$output .= " @media only screen and (max-width: 767px) {
				#top #wrap_all {$key}.av_header_transparency, #top #wrap_all {$key}.av_header_transparency .phone-info.with_nav span,
				#top #header{$key}.av_header_transparency .av-main-nav > li > a .avia-menu-text, #top #header{$key}.av_header_transparency .av-main-nav > li > a .avia-menu-subtex{ color: $meta }
				
				$key div .av-hamburger-inner, $key div .av-hamburger-inner::before, $key div .av-hamburger-inner::after{
					background-color:$meta;
				}
				
				#top .av_header_with_border.av_header_transparency .avia-menu.av_menu_icon_beside{
					border-color:$border;
				}
			}
			";
		}
		
		if(!empty($avia_config['backend_colors']['burger_flyout_width']))
		{
			$output .= "
			
			.html_av-overlay-side .av-burger-overlay-scroll{width:".$avia_config['backend_colors']['burger_flyout_width']."; 
			 -webkit-transform: translateX(".$avia_config['backend_colors']['burger_flyout_width']."); transform: translateX(".$avia_config['backend_colors']['burger_flyout_width']."); 
			}
			
			
			
			";
		}
		
		

		break;

		case '.main_color':

			$constant_font = avia_backend_calc_preceived_brightness($primary, 230) ?  '#ffffff' : $bg;
			$output .= "
			
			#main{ border-color: $border;  }
			
			#scroll-top-link:hover{ background-color: $bg2; color: $primary; border:1px solid $border; }

			";

			/*contact form picker*/

			$output .= "
			#top .avia-datepicker-div .ui-datepicker-month, #top .avia-datepicker-div .ui-datepicker-year{color:$heading;}
			#top .avia-datepicker-div{ background: $bg; border:1px solid $border; }
			#top .avia-datepicker-div a{ color:$meta; background-color: $bg2; }
			#top .avia-datepicker-div a.ui-state-active, #top .avia-datepicker-div a.ui-state-highlight{ color:$primary; }
			#top .avia-datepicker-div a.ui-state-hover{ color:$bg2; background-color: $meta; }
			#top .avia-datepicker-div .ui-datepicker-buttonpane button{ background-color: $primary; color: $constant_font; border-color: $primary; }

			";
			
			/*site loader*/
			$output .= "
			#top .av-siteloader{ border-color: $border; border-left-color:$primary; }
			#top div.avia-popup .mfp-preloader { border-left-color:$primary; }
			.av-preloader-reactive #top .av-siteloader{border-color: $border; }
			#top .av-siteloader-wrap{background-color: $bg; }
			.av-preloader-reactive #top .av-siteloader:before{ background-color: $border;  }
			";
			
			/*tab section*/
			
			$output .= "
			.av-tab-section-tab-title-container{background-color: $bg2; }
			#top .av-section-tab-title{color:$meta;}
			#top a.av-active-tab-title{color:$primary;}
			#top .av-tab-arrow-container span{background-color: $bg;}
			";
		break;



		case '.footer_color':

			$output .= "

			

			";

		break;


		case '.socket_color':

			$output .= "

			html, #scroll-top-link{ background-color: $bg; }
			#scroll-top-link{ color: $color; border:1px solid $border; }
			
			.html_stretched #wrap_all{
			background-color:$bg;
			}
			";


		break;
	}



	//unset all vars with the help of variable vars :)
	foreach($colors as $key=>$val){ unset($$key); }


}

//filter to plug in, in case a plugin/extension/config file wants to make use of it
$output = apply_filters('avia_dynamic_css_output', $output, $color_set);



######################################################################
# DYNAMIC ICONFONT CHARACTERS 
######################################################################

//forum topic icons
$output .= "
.bbp-topics .bbp-body .bbp-topic-title:before{ ".av_icon_css_string('one_voice')." }
.bbp-topics .bbp-body .topic-voices-multi .bbp-topic-title:before { ".av_icon_css_string('multi_voice')." }
.bbp-topics .bbp-body .super-sticky .bbp-topic-title:before { ".av_icon_css_string('supersticky')." }
.bbp-topics .bbp-body .sticky .bbp-topic-title:before { ".av_icon_css_string('sticky')." }
.bbp-topics .bbp-body .status-closed .bbp-topic-title:before { ".av_icon_css_string('closed')." }
.bbp-topics .bbp-body .super-sticky.status-closed .bbp-topic-title:before{ ".av_icon_css_string('supersticky_closed')." }
.bbp-topics .bbp-body .sticky.status-closed .bbp-topic-title:before{ ".av_icon_css_string('sticky_closed')." }
";

//layerslider nav icons
$output .= "
#top .avia-layerslider .ls-nav-prev:before{  ".av_icon_css_string('prev_big')." }
#top .avia-layerslider .ls-nav-next:before{  ".av_icon_css_string('next_big')." }
#top .avia-layerslider .ls-nav-start:before, #top .avia_playpause_icon:before{ ".av_icon_css_string('play')." }
#top .avia-layerslider .ls-nav-stop:before, #top .avia_playpause_icon.av-pause:before{ ".av_icon_css_string('pause')." }
";

//image hover overlay icons
$output .= "
.image-overlay .image-overlay-inside:before{ ".av_icon_css_string('ov_image')." }
.image-overlay.overlay-type-extern .image-overlay-inside:before{ ".av_icon_css_string('ov_external')." }
.image-overlay.overlay-type-video .image-overlay-inside:before{ ".av_icon_css_string('ov_video')." }
";

//lightbox next/prev icons
$output .= "
div.avia-popup button.mfp-arrow:before		{ ".av_icon_css_string('next_big')." }
div.avia-popup button.mfp-arrow-left:before { ".av_icon_css_string('prev_big')."}
";




######################################################################
# OUTPUT THE DYNAMIC CSS RULES
######################################################################

//compress output
$output = preg_replace('/\r|\n|\t/', '', $output);

//todo: if the style are generated for the wordpress header call the generating script, otherwise create a simple css file and link to that file


$avia_config['style'] = array(

		array(
		'key'	=>	'direct_input',
		'value'		=> $output
		),

		array(
		'key'	=>	'direct_input',
		'value'	=> ".html_header_transparency #top .avia-builder-el-0 .container, .html_header_transparency #top .avia-builder-el-0 .slideshow_caption{padding-top:".avia_get_header_scroll_offset()."px;}"
		),

		//google webfonts
		array(
		'elements'	=> 'h1, h2, h3, h4, h5, h6, #top .title_container .main-title, tr.pricing-row td, #top .portfolio-title, .callout .content-area, .avia-big-box .avia-innerbox, .av-special-font, .av-current-sort-title, .html_elegant-blog #top .minor-meta, #av-burger-menu-ul li',
		'key'	=>	'google_webfont',
		'value'		=> avia_get_option('google_webfont')
		),

		//google webfonts
		array(
		'elements'	=> 'body',
		'key'	=>	'google_webfont',
		'value'		=> avia_get_option('default_font')
		),
		
		array(
		'key'	=>	'direct_input',
		'value'		=> avia_get_option('quick_css')
		),
);


do_action('ava_generate_styles', $options, $color_set, $styles);

