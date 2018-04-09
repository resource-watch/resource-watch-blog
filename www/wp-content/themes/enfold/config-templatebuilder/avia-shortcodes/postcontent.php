<?php
/**
 * Post/Page Content
 *
 * Display the content of another entry
 * Element is in Beta and by default disabled. Todo: test with layerslider elements. currently throws error bc layerslider is only included if layerslider element is detected which is not the case with the post/page element
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_postcontent' ) && current_theme_supports('experimental_postcontent'))
{
	class avia_sc_postcontent extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'yes';
				
				$this->config['name']		= __('Page Content', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-postcontent.png";
				$this->config['order']		= 30;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_postcontent';
				$this->config['modal_data'] = array('modal_class' => 'flexscreen');
				$this->config['tooltip'] 	= __('Display the content of another entry', 'avia_framework' );
				$this->config['drag-level'] = 1;
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
				$itemcount = array('All'=>'-1');
				for($i = 1; $i<101; $i++) $itemcount[$i] = $i;

				$this->elements = array(

					 array(
							"name" 	=> __("Which Entry?", 'avia_framework' ),
							"desc" 	=> __("Select the Entry that should be displayed", 'avia_framework' ),
							"id" 	=> "link",
							"fetchTMPL"	=> true,
							"type" 	=> "linkpicker",
							"subtype"  => array(	__('Single Entry', 'avia_framework' ) =>'single'),
							"posttype" => array('page','portfolio'),
							"std" 	=> "page")
					);
			}

			function extra_assets()
			{
				add_filter('avia_builder_precompile', array($this, 'pre_compile'), 1);
			}


			function pre_compile($content)
			{
				global $shortcode_tags;

	 			//in case we got none/more than one postcontent elements make sure that replacement doesnt get executed/onle gets executed once
	 			if(strpos($content, 'av_postcontent') === false) return $content;

	 			//save the "real" shortcode array
	 			$old_sc = $shortcode_tags;

	 			//execute only this single shortcode and return the result
	 			$shortcode_tags = array($this->config['shortcode'] => array($this, 'shortcode_handler'));
	 			$content = do_shortcode($content);

	 			//re create the old shortcode pattern
	 			$shortcode_tags = $old_sc;

	 			//$content = preg_replace("!\[av_postcontent.*?\]!","",$content);

	 			//now we need to re calculate the shortcode tree so that all elements that are pulled from different posts also get the correct location
	 			$pattern = str_replace('av_postcontent','av_psprocessed', ShortcodeHelper::get_fake_pattern());

	 			preg_match_all("/".$pattern."/s", $content, $matches);
	 			ShortcodeHelper::$tree = ShortcodeHelper::build_shortcode_tree($matches);


	 			return $content;
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
				extract(shortcode_atts(array('link' => ''), $atts, $this->config['shortcode']));

				$output  = "";
				$post_id = function_exists('avia_get_the_id') ? avia_get_the_id() : get_the_ID();
				$entry   = AviaHelper::get_entry($link);

				if(!empty($entry))
				{
					if($entry->ID == $post_id)
					{
						$output .= '<article class="entry-content" '.avia_markup_helper(array('context' => 'entry','echo'=>false, 'id'=>$post_id, 'custom_markup'=>$meta['custom_markup'])).'>';
						$output .= "You added a Post/Page Content Element to this entry that tries to display itself. This would result in an infinite loop. Please select a different entry or remove the element";
						$output .= '</article>';
					}
					else
					{
						$output .= '<article class="entry-content" '.avia_markup_helper(array('context' => 'entry','echo'=>false, 'id'=>$post_id, 'custom_markup'=>$meta['custom_markup'])).'>';
						$output .= apply_filters( 'the_content', $entry->post_content );
						$output .= '</article>';
					}
				}

				return do_shortcode($output);
			}




	}
}
