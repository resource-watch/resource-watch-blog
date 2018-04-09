<?php
/**
 * Product List
 *
 * Display a List of Product Entries
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if( !class_exists( 'woocommerce' ) )
{
	add_shortcode('av_productlist', 'avia_please_install_woo');
	return;
}

if ( !class_exists( 'avia_sc_productlist' ) )
{
	class avia_sc_productlist extends aviaShortcodeTemplate
	{
		/**
		 * Create the config array for the shortcode button
		 */
		function shortcode_insert_button()
		{
			$this->config['self_closing']	=	'yes';
			
			$this->config['name']		= __('Product List', 'avia_framework' );
			$this->config['tab']		= __('Plugin Additions', 'avia_framework' );
			$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-catalogue.png";
			$this->config['order']		= 20;
			$this->config['target']		= 'avia-target-insert';
			$this->config['shortcode'] 	= 'av_productlist';
			$this->config['tooltip'] 	= __('Display a List of Product Entries', 'avia_framework' );
			$this->config['drag-level'] = 3;
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
			$this->elements = array(
				array(
						"type" 	=> "tab_container", 'nodescription' => true
					),
					
				array(
						"type" 	=> "tab",
						"name"  => __("Content" , 'avia_framework'),
						'nodescription' => true
					),
				array(
						"name" 	=> __("Which Entries?", 'avia_framework' ),
						"desc" 	=> __("Select which entries should be displayed by selecting a taxonomy", 'avia_framework' ),
						"id" 	=> "categories",
						"type" 	=> "select",
						"taxonomy" => "product_cat",
					    "subtype" => "cat",
						"multiple"	=> 6
				),

				array(
						"name" 	=> __("Columns", 'avia_framework' ),
						"desc" 	=> __("How many columns should be displayed?", 'avia_framework' ),
						"id" 	=> "columns",
						"type" 	=> "select",
						"std" 	=> "1",
						"subtype" => array(	__('1 Column', 'avia_framework' )	=>'1',
											__('2 Columns', 'avia_framework' )	=>'2',
											__('3 Columns', 'avia_framework' )	=>'3',
											__('4 Columns', 'avia_framework' )	=>'4',
											)),
				array(
						"name" 	=> __("Entry Number", 'avia_framework' ),
						"desc" 	=> __("How many items should be displayed?", 'avia_framework' ),
						"id" 	=> "items",
						"type" 	=> "select",
						"std" 	=> "9",
						"subtype" => AviaHtmlHelper::number_array(1,100,1, array('All'=>'-1'))),
				
				array(
						"name" 	=> __("WooCommerce Out of Stock Products visibility?", 'avia_framework' ),
						"desc" 	=> __("Select the visibility of WooCommerce products. Default setting can be set at Woocommerce -&gt Settings -&gt Products -&gt Inventory -&gt Out of stock visibility", 'avia_framework' ),
						"id" 	=> "wc_prod_visible",
						"type" 	=> "select",
						"std" 	=> "",
						"subtype" => array(
							__('Use default WooCommerce Setting (Settings -&gt; Products -&gt; Out of stock visibility)', 'avia_framework' ) => '',
							__('Hide products out of stock', 'avia_framework' )		=> 'hide',
							__('Show products out of stock', 'avia_framework' )		=> 'show')
					),
				
				array(
						"name" 	=> __("WooCommerce Hidden Products visibility", 'avia_framework' ),
						"desc" 	=> __("Select the visibility of WooCommerce products depending on catalog visibility. Can be set independently for each product: Edit Product -&gt Publish panel -&gt Catalog visibility", 'avia_framework' ),
						"id" 	=> "wc_prod_hidden",
						"type" 	=> "select",
						"std" 	=> "",
						"subtype" => array(
							__('Show all products', 'avia_framework' )			=> '',
							__('Hide hidden products', 'avia_framework' )		=> 'hide',
							__('Show hidden products only', 'avia_framework' )  => 'show')
					),
				
				array(
						"name" 	=> __("WooCommerce Featured Products visibility", 'avia_framework' ),
						"desc" 	=> __("Select the visibility of WooCommerce products depending on checkbox &quot;This is a featured product&quot; in catalog visibility. Can be set independently for each product: Edit Product -&gt Publish panel -&gt Catalog visibility", 'avia_framework' ),
						"id" 	=> "wc_prod_featured",
						"type" 	=> "select",
						"std" 	=> "",
						"subtype" => array(
							__('Show all products', 'avia_framework' )				=> '',
							__('Hide featured products', 'avia_framework' )			=> 'hide',
							__('Show featured products only', 'avia_framework' )	=> 'show')
					),

                array(
                    "name" 	=> __("Offset Number", 'avia_framework' ),
                    "desc" 	=> __("The offset determines where the query begins pulling products. Useful if you want to remove a certain number of products because you already query them with another product grid. Attention: Use this option only if the product sorting of the product grids match and do not allow the user to pick the sort order!", 'avia_framework' ),
                    "id" 	=> "offset",
                    "type" 	=> "select",
                    "std" 	=> "0",
                    "subtype" => AviaHtmlHelper::number_array(1,100,1, array(__('Deactivate offset','avia_framework')=>'0', __('Do not allow duplicate posts on the entire page (set offset automatically)', 'avia_framework' ) =>'no_duplicates'))),

				array(
						"name" 	=> __("Sorting Options", 'avia_framework' ),
						"desc" 	=> __("Here you can choose how to sort the products. Default setting can be set at Woocommerce -&gt Settings -&gt Products -&gt Display -&gt Default product sorting", 'avia_framework' ),
						"id" 	=> "sort",
						"type" 	=> "select",
						"std" 	=> "dropdown",
						"no_first"=>true,
						"subtype" => array( __('Use defaut (defined at Woocommerce -&gt; Settings -&gt Default product sorting) ', 'avia_framework' ) =>'0',
											__('Sort alphabetically', 'avia_framework' ) =>'title',
											__('Sort by most recent', 'avia_framework' ) =>'date',
											__('Sort by price', 'avia_framework' ) =>'price',
											__('Sort by popularity', 'avia_framework' ) =>'popularity')),

				array(
							"name" 	=> __("Pagination", 'avia_framework' ),
							"desc" 	=> __("Should a pagination be displayed?", 'avia_framework' ),
							"id" 	=> "paginate",
							"type" 	=> "select",
							"std" 	=> "yes",
							"subtype" => array(
								__('yes',  'avia_framework' ) =>'yes',
								__('no',  'avia_framework' ) =>'no')),
				
				array(
							"name" 	=> __("Item Links", 'avia_framework' ),
							"desc" 	=> __("What should happen if a user clicks the product link?", 'avia_framework' ),
							"id" 	=> "link_behavior",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array(
								__('Show single product page',  'avia_framework' ) =>'',
								__('Add item to cart (if item has variations the single product page will be opened)',  'avia_framework' ) =>'add_cart')),
								
				array(
							"name" 	=> __("Product Images", 'avia_framework' ),
							"desc" 	=> __("Should product image be displayed?", 'avia_framework' ),
							"id" 	=> "show_images",
							"type" 	=> "select",
							"std" 	=> "yes",
							"subtype" => array(
								__('yes',  'avia_framework' ) =>'yes',
								__('no',  'avia_framework' ) =>'no')),

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




				);
		}

		/**
		 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
		 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
		 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
		 *
		 *
		 * @param array $params this array holds the default values for $content and $args.
		 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
		 */
		function editor_element($params)
		{
			$params['innerHtml'] = "<img src='".$this->config['icon']."' title='".$this->config['name']."' />";
			$params['innerHtml'].= "<div class='avia-element-label'>".$this->config['name']."</div>";

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
			global $avia_config, $woocommerce;
			
			$screen_sizes = AviaHelper::av_mobile_sizes($atts);
			$atts['class'] = $meta['el_class'];
			$atts['autoplay'] = "no";
			$atts['type'] = "list";
			
			//	fix for seo plugins which execute the do_shortcode() function before the WooCommerce plugin is loaded
			if(!is_object($woocommerce) || !is_object($woocommerce->query)) return;
			
			$atts = array_merge($atts, $screen_sizes);
			$slider = new avia_product_slider($atts);
			$slider->query_entries();
			
				//	force to ignore WC default setting - see hooked function avia_wc_product_is_visible
			$avia_config['woocommerce']['catalog_product_visibility'] = 'show_all';
			$html = $slider->html_list();
			
				//	reset again
			$avia_config['woocommerce']['catalog_product_visibility'] = 'use_default';
			
			return $html;
		}
	}
}



