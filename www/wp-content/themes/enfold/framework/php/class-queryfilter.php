<?php  if ( ! defined('AVIA_FW')) exit('No direct script access allowed');
/**
 * This file holds various classes and methods for post object manipulation
 *
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright (c) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @package 	AviaFramework
 */

/**
 * adds various params to the queried posts: av_title, av_modified_content, av_image, av_media, av_custom_url
 */


if( !class_exists( 'avia_queryfilter' ) && current_theme_supports('avia_queryfilter'))
{

	class avia_queryfilter
	{
		function __construct()
		{
			if(!is_admin()) add_filter( 'posts_results', array(&$this, 'filter_entries') );
		}
		
		function filter_entries( $entries )
		{
			foreach($entries as &$entry)
			{
				//make sure that we never process the same entry twice
				if(!isset($entry->av_filter) && isset($entry->post_type))
				{
					$entry->av_filter = true;
					
					switch($entry->post_type)
					{
						case 'post' : $this->modify_post($entry); break;
						case 'page' : $this->modify_page($entry);break;
					}
				}
			}
		
			return $entries;
		}
		
		
		
		function modify_post($entry)
		{
			$format = get_post_format($entry->ID);
			if(empty($format)) $format = "standard";
			
			switch($format)
			{
				case 'standard' : $this->filter_title($entry); 	break;
				case 'gallery' 	: $this->filter_title($entry); $this->filter_gallery($entry); 	break;
				case 'video' 	: $this->filter_title($entry); $this->filter_video($entry); 	break;
				case 'image' 	: $this->filter_title($entry); $this->filter_image($entry); 	break;
				case 'link' 	: $this->filter_link($entry); 	break;
				case 'quote' 	: $this->filter_quote($entry); 	break;
				case 'audio' 	: $this->filter_title($entry); $this->filter_audio($entry); 	break;
			}
		}
		
		function modify_page($entry)
		{
			
			
		}
		
		
		function filter_title($entry)
		{
			$heading = is_singular() ? "h1" : "h2";
			$title_attr = the_title_attribute(array('before' =>__('Link to:','avia_framework')." ",'after' => '','echo' => false ,'post' => $entry->ID));
			
			$output  = "";
			$output .= "<{$heading} class='post-title entry-title' ".avia_markup_helper(array('context' => 'entry_title','echo'=>false)).">";
			$output .= "<a href='".get_permalink($entry->ID)."' rel='bookmark' title='{$title_attr}'>";
			$output .= get_the_title($entry->ID);
			$output .= "<span class='post-format-icon minor-meta'></span>";
			$output .= "</a>";
			$output .= "</{$heading}>";
	
			$entry->av_title = $output;
			
		}
		
		
		function filter_gallery($entry)
		{
			//search for the first av gallery or gallery shortcode
			preg_match("!\[(?:av_)?gallery.+?\]!", $entry->post_content, $match_gallery);
	
			if(!empty($match_gallery))
			{
				$gallery = $match_gallery[0];
	
				if(strpos($gallery, 'av_') === false)   $gallery = str_replace("gallery", 'av_gallery', $gallery);
				if(strpos($gallery, 'style') === false) $gallery = str_replace("]", " style='big_thumb' preview_size='gallery']", $gallery);
	
				$entry->av_image = do_shortcode($gallery);
				$entry->av_modified_content = str_replace($match_gallery[0], "", $entry->post_content);
			}
		}
		
		function filter_video($entry)
		{
			//replace empty url strings with an embed code
		 	$content = preg_replace( '|^\s*(https?://[^\s"]+)\s*$|im', "[embed]$1[/embed]", $entry->post_content );
	
			//extrect embed and av_video codes from the content. if any were found execute them and prepend them to the post
			preg_match("!\[embed.+?\]|\[av_video.+?\]!", $content, $match_video);
	
			if(!empty($match_video))
			{
				global $wp_embed;
				$video = $match_video[0];
				$entry->av_media = do_shortcode($wp_embed->run_shortcode($video));
				$entry->av_modified_content = str_replace($match_video[0], "", $content);
			}
		}
		
		function filter_image($entry)
		{
			$prepend_image = get_the_post_thumbnail(get_the_ID(), 'large');
			$image = "";

			if(!$prepend_image)
			{
				$image = avia_regex($entry->post_content,'image');
				if(is_array($image))
				{
					$image = $image[0];
					$prepend_image = '<div class="avia-post-format-image"><img src="'.$image.'" alt="" title ="" /></div>';
				}
				else
				{
					$image = avia_regex($entry->post_content,'<img />',"");
					if(is_array($image))
					{
						$prepend_image = '<div class="avia-post-format-image">'.$image[0]."</div>";
					}
				}
			}
			else
			{
				
				$large_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'extra_large' );
				$prepend_image = '<div class="avia-post-format-image"><a href="'.$large_image[0].'">'.$prepend_image."</a></div>";
			}
			
	
	
			if(!empty($prepend_image) && is_string($prepend_image))
			{
				if($image)$entry->av_modified_content = str_replace($image, "", $entry->post_content);
				$entry->av_image = $prepend_image;
			}
		}
		
		function filter_link($entry)
		{
			//retrieve the link for the post
			$link 		= "";
			$title_attr = the_title_attribute(array('before' =>__('Link to:','avia_framework')." ",'after' => '','echo' => false ,'post' => $entry->ID));
			
			$pattern1 	= '$^\b(https?|ftp|file)://[-A-Z0-9+&@#/%?=~_|!:,.;]*[-A-Z0-9+&@#/%=~_|]$i';
			$pattern2 	= "!^\<a.+?<\/a>!";
			$pattern3 	= "!\<a.+?<\/a>!";
	
			//if the url is at the begnning of the content extract it
			preg_match($pattern1, $entry->post_content , $link);
			if(!empty($link[0]))
			{
				$link = $link[0];
				$markup = avia_markup_helper(array('context' => 'entry_title','echo'=>false));
				$entry->av_title = "<a href='{$link}' rel='bookmark' title='{$title_attr}' $markup>".get_the_title($entry->ID)."</a>";
				$entry->av_modified_content = preg_replace("!".str_replace("?", "\?", $link)."!", "", $entry->post_content, 1);
			}
			else
			{
				preg_match($pattern2, $entry->post_content , $link);
				if(!empty($link[0]))
				{
					$link = $link[0];
					$entry->av_title = $link;
					$entry->av_modified_content = preg_replace("!".str_replace("?", "\?", $link)."!", "", $entry->post_content, 1);
				}
				else
				{
					preg_match($pattern3,  $entry->post_content , $link);
					if(!empty($link[0]))
					{
						$entry->av_title = $link[0];
					}
				}
			}
	
			if($link)
			{
				if(is_array($link)) $link = $link[0];
			
				$heading = is_singular() ? "h1" : "h2";
	
				$entry->av_title = "<{$heading} class='post-title entry-title' ".avia_markup_helper(array('context' => 'entry_title','echo'=>false)).">".$entry->av_title."</{$heading}>";
				
				//needs to be set for masonry
				$entry->av_custom_url = $link;
			}
			else
			{
				$this->filter_title($entry);
			}
	

		}
		
		function filter_quote($entry)
		{
		
			$output  = "";	
			$output .= "<blockquote class='first-quote' ".avia_markup_helper(array('context' => 'entry_title','echo'=>false)).">";
			$output .= get_the_title($entry->ID);
			$output .= "</blockquote>";
			 
			 $entry->av_title = $output;
		
		}
		
		function filter_audio($entry)
		{
			preg_match("!\[audio.+?\]\[\/audio\]!", $entry->post_content, $match_audio);

			if(!empty($match_audio))
			{
				$entry->av_media = do_shortcode($match_audio[0]);
				$entry->av_modified_content = str_replace($match_audio[0], "", $entry->post_content);
			}
		}
		
		
	}
	
	new avia_queryfilter();
}

