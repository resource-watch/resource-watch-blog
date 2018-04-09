<?php
/**
 * Product Info Tab
 * 
 * Display the info and review tab for the current product
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if( !class_exists( 'woocommerce' ) )
{
	add_shortcode('av_product_tabs', 'avia_please_install_woo');
	return;
}

if ( !class_exists( 'avia_sc_product_tabs' ) )
{
	class avia_sc_product_tabs extends aviaShortcodeTemplate
	{
		/**
		 * Create the config array for the shortcode button
		 */
		function shortcode_insert_button()
		{
			$this->config['self_closing']	=	'yes';
			
			$this->config['name']		= __('Product Info Tab', 'avia_framework' );
			$this->config['tab']		= __('Plugin Additions', 'avia_framework' );
			$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-tabs.png";
			$this->config['order']		= 9;
			$this->config['target']		= 'avia-target-insert';
			$this->config['shortcode'] 	= 'av_product_tabs';
			$this->config['tooltip'] 	= __('Display the info and review tab for the current product', 'avia_framework' );
			$this->config['drag-level'] = 3;
			$this->config['tinyMCE'] 	= array('disable' => "true");
			$this->config['posttype'] 	= array('product',__('This element can only be used on single product pages','avia_framework'));
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
			
			$params['innerHtml'].= "<div class='avia-flex-element product-info-tabs'>"; 
			$params['innerHtml'].= 		'<span>' . __( 'Display info tabs for this product:', 'avia_framework') . '</span>';
			$params['innerHtml'].=		'<ul>';
			$params['innerHtml'].=			'<li>' . __( '&quot;Additional information&quot; tab with product attributes', 'avia_framework') . '</li>';
			$params['innerHtml'].=			'<li>' . __( '&quot;Reviews&quot; tab (needs to enable reviews in advanced tab)', 'avia_framework') . '</li>';
			$params['innerHtml'].=			'<li>' . __( '.... possible 3rd party tabs', 'avia_framework') . '</li>';
			$params['innerHtml'].=		'</ul>';
			$params['innerHtml'].= "</div>";
			
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
			add_filter('woocommerce_product_tabs', array($this, 'av_advanced_tabs_remove_content_tab'));
		
			$output = "";
			$meta['el_class'];
			
			global $woocommerce, $product;
			if(!is_object($woocommerce) || !is_object($woocommerce->query) || empty($product)) return;
			
			//$temp = get_post( $product->get_id() )->post_content;
			//$product->post->post_content = "";
			
			// $product = wc_get_product();
			$output .= "<div class='av-woo-product-review av-woo-product-tabs product ".$meta['el_class']."'>";
			ob_start();
			wc_clear_notices();
			woocommerce_output_product_data_tabs();
			// comments_template('reviews');
			$output .= ob_get_clean();
			$output .= "</div>";
			//$product->post->post_content = $temp;
			
			remove_filter('woocommerce_product_tabs', array($this, 'av_advanced_tabs_remove_content_tab'));
			return $output;
		}
		
		function av_advanced_tabs_remove_content_tab( $tabs )
		{
			unset($tabs['description']);
			return $tabs;		
		}
	}
}



