<?php
/*add color styles

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
*/

if(!function_exists('avia_woo_dynamic_css'))
{
	add_filter('avia_dynamic_css_output', 'avia_woo_dynamic_css', 10, 2); 

	function avia_woo_dynamic_css($output, $color_set)
	{
		/*color sets*/
		foreach ($color_set as $key => $colors) // iterates over the color sets: usually $key is either: header_color, main_color, footer_color, socket_color
		{
			$key = ".".$key;
			extract($colors);
			$constant_font = avia_backend_calc_preceived_brightness($primary, 230) ?  '#ffffff' : $bg;
			$dark_bg2 = avia_backend_calculate_similar_color($bg2, 'darker', 1);
			$primary2 = avia_backend_calculate_similar_color($primary, 'darker', 4);
			
			$output .= "
			
			$key .cart_dropdown .dropdown_widget li a, #top $key  .avia_cart_buttons .button,  #top $key .dropdown_widget .buttons .button, $key .cart_dropdown_first .cart_dropdown_link, #top $key p.order-info mark{
			color: $color;
			}
			
			$key .woocommerce-tabs .tabs a, $key .product_meta, $key .quantity input.qty, $key .cart_dropdown .dropdown_widget, $key .avia_select_fake_val, $key address, $key .product>a $key .product_excerpt, $key .term_description, #top $key .price .from, #top #wrap_all $key del, $key .dynamic-title .dynamic-heading, $key .dynamic-title a, $key .entry-summary .woocommerce-product-rating  .woocommerce-review-link, $key .chosen-container-single .chosen-single span, #top $key .select2-container .select2-choice, $key .woocommerce-MyAccount-navigation-link.is-active a{
			color: $meta;
			}
			
			$key div.product .woocommerce-tabs ul.tabs li.active a, $key .cart_dropdown .dropdown_widget .widget_shopping_cart_content,  $key .cart_dropdown_link, $key .inner_product_header, $key .avia-arrow, #top $key .variations select, #top $key .variations input, #top $key #reviews input[type='text'], #top $key #reviews input[type='email'], $key #reviews .comment-text, $key #reviews #comment, $key .single-product-main-image .images a, #top $key .shop_table.cart .input-text, #top $key form.login .input-text, #top $key form.register .input-text, $key .chosen-container-single .chosen-search, $key .products .product-category h3:before, #top $key .quantity input.qty{
			background-color: $bg;
			}
			
			$key .woocommerce-tabs .tabs .active, $key div.product .woocommerce-tabs .panel, $key .activeslideThumb, $key #payment li, $key .widget_price_filter .ui-slider-horizontal .ui-slider-range,  $key .avia_cart, $key form.login, $key form.register, $key .col-1, $key .col-2, $key .variations_form,  $key .dynamic-title, $key .single-product-main-image .thumbnails a , $key .quantity input.qty, $key .avia_cart_buttons,  #top  $key .dropdown_widget .buttons, div .dropdown_widget .cart_list li:hover, $key .woocommerce-info, #top $key .chosen-container-single .chosen-single, #top $key .chosen-search input[type='text'], $key .chosen-results, $key .chosen-container .chosen-drop, #top $key .select2-container .select2-choice, $key .widget_layered_nav_filters .chosen a:hover,  $key .widget_layered_nav .chosen a:hover, $key .woocommerce-MyAccount-navigation-link.is-active, $key .woocommerce-MyAccount-navigation-link:hover{
			background-color: $bg2;
			}
			
			$key .thumbnail_container img, #top $key #main .order_details, #top $key .chosen-search input[type='text'], #top $key .chosen-container-single .chosen-single, #top $key .chosen-container-active .chosen-single, #top $key .chosen-container .chosen-drop, $key .chosen-container .chosen-results, $key .products .product-category h3:before, #top $key .select2-container .select2-choice{
			border-color: $border;
			}
			
			$key .summary div{
			border-color: $bg2;
			}
			
			$key .widget_price_filter .price_slider_wrapper .price_slider .ui-slider-handle, #top $key a.remove, #top $key .onsale{
			background-color: $primary;
			}
			
			#top $key .active-result.highlighted{
			background-color: $primary;
			color: $constant_font;
			}
			
			$key #shop_header a:hover{
			color: #fff;
			}
			
			#top $key .price, $key .stock, #top #wrap_all $key ins, $key .products .product-category h3 .count, $key .widget_layered_nav_filters .chosen a:before, $key .widget_layered_nav .chosen a:before{
			color:$primary;
			}
			
			$key .dynamic-title a:hover{
			color:$secondary;
			}
			
			$key .widget_price_filter .price_slider_wrapper .ui-widget-content{
			background: $border;
			}
			
			#top $key .chzn-container-single .chzn-single{
			border-color: $border;
			background-color: $bg2;
			color:$meta;
			}
			
			$key #payment {
			background-color: $bg2;
			}
			
			
			#top $key .quantity input.plus, #top $key .quantity input.minus {
			border-color: $border;
			background-color: $dark_bg2;
			color:$meta;
			}
			
	
			";
			
			//bookings menu
			$output .= "
			$key .wc-bookings-booking-form .block-picker li a, #top $key .wc-bookings-date-picker .ui-datepicker td{
			border-color: $border;	
			}
			
			#top $key .wc-bookings-booking-form .block-picker li a:hover,
			#top $key .wc-bookings-booking-form .block-picker li a:focus,
			#top $key .wc-bookings-booking-form .block-picker li a.selected{
			background-color: $primary !important;
			color:$constant_font !important;
			}
			
			#top $key .wc-bookings-date-picker legend .wc-bookings-date-picker-choose-date, #top $key .wc-bookings-date-picker .ui-datepicker-header{
			border-color: $primary;
			background-color: $primary;
			color:$constant_font;
			}
			
			$key .wc-bookings-date-picker .ui-datepicker td.bookable a{
			background-color: $primary !important;
			color:$constant_font !important;
			border-color: $border;
			}
			
			#top $key .wc-bookings-date-picker .ui-datepicker th,
			#top $key .wc-bookings-date-picker .ui-datepicker td .ui-state-default{
			color:$color;	
			}
			
			#top $key .wc_bookings_field_start_date, #top $key .wc-bookings-date-picker span.label{
				color: $meta;
			}
			
			#top $key .wc-bookings-booking-form .wc-bookings-booking-cost, #top $key .wc-bookings-date-picker .ui-datepicker td.ui-datepicker-other-month{
				background-color: $bg2;
			}
			
			#top .wc-bookings-booking-form .blockUI.blockOverlay{
				background-color: $bg !important;
			}
			
			
			#top $key .wc-bookings-date-picker .ui-datepicker td.bookable-range .ui-state-default, 
			#top $key .wc-bookings-date-picker .ui-datepicker td.ui-datepicker-current-day a,
			#top $key .wc-bookings-date-picker .ui-datepicker td.bookable-range{
				background: $primary2 !important;
				color: $constant_font;
			}
			
			
			";
			
			//sort menu
			$output .= "
			
			$key .sort-param > li > span, $key .sort-param > li > a, $key .sort-param ul{
			background-color: $bg2;
			}
			
			$key .sort-param > li:hover > span, $key .sort-param > li:hover > a, $key .sort-param > li:hover ul, $key .product-sorting strong{
			color:$heading;
			}
			
			$key .sort-param  a{
			color:$meta;
			}
			
			#top $key .sort-param  a:hover{
			color:$secondary;
			}
			
			$key .avia-bullet{
			border-color: $meta;
			}
			
			#top $key a:hover .avia-bullet{
			border-color: $secondary;
			}
			
			$key .sort-param  .current-param a{
			color:$primary;
			}
			
			$key .sort-param .current-param .avia-bullet{
			border-color:$primary;
			}
			
			";
			
			
			if($key == '.header_color')
			{
				$output .= "
			
				#top .av-cart-counter{background-color:$primary; color: $constant_font;}
				
				";
				
			}
			
			
			if($key == '.main_color')
			{
				$output .= "
			
				.added_to_cart_notification, .added_to_cart_notification .avia-arrow{
				background-color: $bg;
				color: $meta;
				border-color: $border;
				}
				
				.added_to_cart_notification strong{
				color:$heading;
				}
				
				#top .select2-drop{ background-color: $bg; }
				#top .select2-results{color:$meta;}
				#top .select2-search{ background-color: $bg2; }
				#top .select2-search, #top .select2-drop-active{border-color: $border;}
				#top .select2-search input[type='text']{background-color: $bg; color: $meta; border-color: $border;}
				.select2-results .select2-highlighted {color: $constant_font; background-color: $primary; }
				
				";
				
			}
			
			
			//unset all vars with the help of variable vars :)
			foreach($colors as $key=>$val){ unset($$key); }
		}
		
		return $output;
	}
}
		
		
		
		
	