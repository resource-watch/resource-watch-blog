<?php
/**
 * Widget Area
 * 
 * Displays one of the registered Widget Areas of the theme
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_widgetarea' ) )
{
	class avia_sc_widgetarea extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'yes';
				
				$this->config['name']		= __('Widget Area', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-sidebar.png";
				$this->config['order']		= 10;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_sidebar';
				$this->config['tinyMCE'] 	= array('instantInsert' => "[av_sidebar widget_area='Displayed Everywhere']");
				$this->config['tooltip'] 	= __('Display one of the themes widget areas', 'avia_framework' );
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
				//fetch all registered sidebars
				$sidebars = AviaHelper::get_registered_sidebars();

				if(empty($params['args']['widget_area'])) $params['args']['widget_area'] = reset($sidebars);
				
				$element = array(
					'subtype' => $sidebars,
					'type'=>'select',
					'std' => htmlspecialchars_decode($params['args']['widget_area']),
					'class' => 'avia-recalc-shortcode',
					'data'	=> array('attr'=>'widget_area')
				);

				$inner		 = "<img src='".$this->config['icon']."' title='".$this->config['name']."' />";
				$inner		.= "<div class='avia-element-label'>".$this->config['name']."</div>";
				$inner		.= AviaHtmlHelper::render_element($element);

				$params['class'] = "";
				$params['content']	 = NULL;
				$params['innerHtml'] = $inner;

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
				$output = "";

				if(!isset($atts['widget_area'])) return $output;

				if( is_dynamic_sidebar( $atts['widget_area'] ) )
				{
					ob_start();
					dynamic_sidebar( $atts['widget_area'] );
					$output = ShortcodeHelper::avia_remove_autop(ob_get_clean(), true);

					if($output) $output = "<div class='avia-builder-widget-area clearfix ".$meta['el_class']."'>".$output."</div>";
				}


				return $output;
			}

	}
}
