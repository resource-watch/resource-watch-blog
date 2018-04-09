<?php
/**
 * Page Split Element
 * 
 * Add a page split to the template. A pagination helps the user to navigate to the previous/next page.
 */
 
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

if(current_theme_supports('avia_template_builder_page_split_element'))
{
	if ( !class_exists( 'av_sc_page_split' ) )
	{
		class av_sc_page_split extends aviaShortcodeTemplate{
				
				/**
				 * Create the config array for the shortcode button
				 */
				function shortcode_insert_button()
				{
					$this->config['self_closing']	=	'yes';
					
					$this->config['name']		= __('Page Split', 'avia_framework' );
					$this->config['tab']		= __('Layout Elements', 'avia_framework' );
					$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-heading.png";
					$this->config['order']		= 5;
					$this->config['target']		= 'avia-target-insert';
					$this->config['shortcode'] 	= 'av_sc_page_split';
	                $this->config['tinyMCE'] 	= array('disable' => "true");
					$this->config['tooltip'] 	= __('Add a page split to the template. A pagination helps the user to navigate to the previous/next page.', 'avia_framework' );
	                $this->config['drag-level'] = 1;
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
	                $params['content'] 	 = NULL; //remove to allow content elements
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
	        		return '<!--avia_template_builder_nextpage-->';
	        	}
				
				
		}
	}


	if(!function_exists('avia_template_builder_split_page_filter'))
	{
		add_filter('avf_template_builder_content', 'avia_template_builder_split_page_filter', 10, 1);
		function avia_template_builder_split_page_filter($content)
		{
			/*
			multipage support - adds page split to content if user uses the element in the page builder
			nextpage code taken from /wp-includes/query.php and slightly modified 
			 */
			global $id, $page, $pages, $multipage, $numpages;
			$numpages = 1;
			$multipage = 0;
			$page = get_query_var('page');
			if(!$page) $page = 1;
			if(false !== strpos($content, '<!--avia_template_builder_nextpage-->'))
			{
				$content = str_replace( "\n<!--avia_template_builder_nextpage-->\n", '<!--avia_template_builder_nextpage-->', $content );
				$content = str_replace( "\n<!--avia_template_builder_nextpage-->", '<!--avia_template_builder_nextpage-->', $content );
				$content = str_replace( "<!--avia_template_builder_nextpage-->\n", '<!--avia_template_builder_nextpage-->', $content );
				// Ignore nextpage at the beginning of the content.
				if ( 0 === strpos( $content, '<!--avia_template_builder_nextpage-->' ) )
					$content = substr( $content, 15 );
				$pages = explode('<!--avia_template_builder_nextpage-->', $content);
				$numpages = count($pages);
				if ( $numpages > 1 )
					$multipage = 1;
			}

			//check if we have at least 2 pages...
			if(count($pages) > 1)
			{
				$current_page = (int)$page - 1;
				if(isset($pages[$current_page])) $content = $pages[$current_page];
			}

			return $content;
		}
	}
}
