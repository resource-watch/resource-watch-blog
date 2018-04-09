<?php  if ( ! defined('AVIA_FW')) exit('No direct script access allowed');
/**
 * This file holds various helper functions that are needed by the frameworks BACKEND
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright (c) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */




/**
 * Check which post type we currently see
 */
if(!class_exists('avia_backend_get_post_type'))
{
	function avia_backend_get_post_type()
	{
		global $post, $typenow, $current_screen;
		
		$posttype = "";
		
		//we have a post so we can just get the post type from that
		if ($post && $post->post_type)
		{
			$posttype = $post->post_type;
		}
		//check the global $typenow - set in admin.php
		elseif($typenow)
		{
			$posttype = $typenow;
		}
		//check the global $current_screen object - set in sceen.php
		elseif($current_screen && $current_screen->post_type)
		{
			$posttype = $current_screen->post_type;
		}
		//lastly check the post_type querystring
		elseif(isset($_REQUEST['post_type']))
		{
			$posttype = sanitize_key($_REQUEST['post_type']);
		}
		
		return $posttype;
	}
}

/**
 * small update class that allows to trigger updates based on theme version
 * provides the ava_trigger_updates hook for functions to execute
 *
 * @param array $scripts_to_load the array to pass
 */
if(!class_exists('avia_update_helper'))
{
	class avia_update_helper
	{	
		var $db_version; 
		var $theme_version;
		var $option_key;
		
		function __construct()
		{
			$theme = wp_get_theme();
			if(is_child_theme()) $theme = wp_get_theme( $theme->get('Template') );
			
			$this->theme_version = $theme->get('Version');
			$this->option_key = $theme->get('Name').'_version';
			$this->db_version = get_option($this->option_key, '1');
			
		}
		
		//provide a hook for update functions and update the version number
		function update_version()
		{
			if(version_compare($this->theme_version, $this->db_version, ">"))
			{		
				do_action('ava_trigger_updates', $this->db_version, $this->theme_version);
				update_option($this->option_key, $this->theme_version);
				do_action('ava_after_theme_update');
			}
			
			// update_option($this->option_key, "1"); // for testing
		}
	}
}


/**
 * load files from a multidemensional array
 *
 * @param array $scripts_to_load the array to pass
 */
if(!function_exists('avia_backend_load_scripts_by_option'))
{
	function avia_backend_load_scripts_by_option( $scripts_to_load )
	{
		foreach ( $scripts_to_load as $path => $includes )
		{
			if( $includes )
			{
				foreach ( $includes as $include )
				{
					switch( $path )
					{
					case 'php':
					include_once( AVIA_PHP.$include.'.php' );
					break;
					}
				}
			}
		}
	}
}



/**
 * load all php files in one folder, if the folder contains files with different file extensions return the filenames as array
 *
 * @param string $folder path to the folder that should be loaded
 * @return array $files files the folder contains that are no php files
 */
if(!function_exists('avia_backend_load_scripts_by_folder'))
{
	function avia_backend_load_scripts_by_folder( $folder )
	{
		$files = array();

		// Open a known directory, and proceed to read its contents
		if ( is_dir( $folder ) )
		{
		    if ( $dh = opendir( $folder ) )
		    {
		        while ( ( $file = readdir( $dh ) ) !== false)
		        {
		        	if('.' != $file && '..' != $file)
		        	{
		        		$pathinfo = pathinfo($folder ."/". $file);

		        		if( isset($pathinfo['extension']) && $pathinfo['extension']  == 'php' )
		        		{
		        			include_once( $folder ."/". $file );
		        		}
		        		else
		        		{
		        			$files[] = $file;
		        		}
		        	}
		        }
		        closedir($dh);
		    }
		}

		return $files;
	}
}





if(!function_exists('avia_backend_safe_string'))
{
	/**
	* Create a lower case version of a string without spaces so we can use that string for database settings
	*
	* @param string $string to convert
	* @return string the converted string
	*/
	function avia_backend_safe_string( $string , $replace = "_", $check_spaces = false)
	{
		$string = strtolower($string);

		$trans = array(
					'&\#\d+?;'				=> '',
					'&\S+?;'				=> '',
					'\s+'					=> $replace,
					'ä'						=> 'ae',
					'ö'						=> 'oe',
					'ü'						=> 'ue',
					'Ä'						=> 'Ae',
					'Ö'						=> 'Oe',
					'Ü'						=> 'Ue',
					'ß'						=> 'ss',
					'[^a-z0-9\-\._]'		=> '',
					$replace.'+'			=> $replace,
					$replace.'$'			=> $replace,
					'^'.$replace			=> $replace,
					'\.+$'					=> ''
				  );

		$trans = apply_filters('avf_safe_string_trans', $trans, $string, $replace);

		$string = strip_tags($string);

		foreach ($trans as $key => $val)
		{
			$string = preg_replace("#".$key."#i", $val, $string);
		}
		
		if($check_spaces)
		{
			if(str_replace('_', '', $string) == '') return;
		}

		return stripslashes($string);
	}
}

if(!function_exists('avia_backend_check_by_regex'))
{
	/**
	* Checks a string based on a passed regex and returns true or false
	*
	* @param string $string to check
	* @param string $regex to check
	* @return string the converted string
	*/
	function avia_backend_check_by_regex( $string , $regex)
	{
		if(!$regex) return false;
		if($regex == 'safe_data') $regex = '^[a-zA-Z0-9\s-_]+$';
		if($regex == 'email')	  $regex = '^\w[\w|\.|\-]+@\w[\w|\.|\-]+\.[a-zA-Z]{2,4}$';
		if($regex == 'url')	  	  $regex = '^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w\#!:.?+=&%@!\-\/]))?$';

		if(preg_match('#'.$regex.'#', $string))
		{
			return true;
		}

		return false;
	}
}



if(!function_exists('avia_backend_is_file'))
{

	/**
	* Checks if a file is an image, text, video or if the file extension matches one of the exensions in a given array
	*
	* @param string $passedNeedle the file name
	* @param string | array $haystack to match against. can be either array or a keyword: image, text, videoService
	* @return bool returns true oder false
	*/
	function avia_backend_is_file($passedNeedle, $haystack)
	{

		// get file extension
		$needle = substr($passedNeedle, strrpos($passedNeedle, '.') + 1);

		//check if file or url was passed
		//if its a url

		if(strlen($needle) > 4)
		{
			if(!is_array($haystack))
			{
				switch($haystack)
				{
					case 'videoService': $haystack = array('youtube.com/','vimeo.com/'); break;
				}
			}

			if(is_array($haystack))
			{
				foreach ($haystack as $regex)
				{
					if(preg_match("!".$regex."!", $passedNeedle)) return true;
				}
			}
		}
		else // if its a file
		{
			//predefined arrays
			if(!is_array($haystack))
			{
				switch($haystack)
				{
					case 'image':
						$haystack = array('png','gif','jpeg','jpg','pdf','tif');

					break;

					case 'text':
						$haystack = array('doc','docx','rtf','ttf','txt','odp');
					break;

					case 'html5video':
						$haystack = array('ogv','webm','mp4');
					break;
				}
			}

			//match extension against array
			if(is_array($haystack))
			{
				if (in_array($needle,$haystack))
				{
					return true;
				}
			}
		}

		return false;
	}
}



if(!function_exists('avia_backend_get_hex_from_rgb'))
{
	/**
	 *  converts an rgb string into a hex value and returns the string
	 *  @param string $r red
	 *  @param string $g green
	 *  @param string $B blue
	 *  @return string returns the converted string
	 */
 	function avia_backend_get_hex_from_rgb($r=FALSE, $g=FALSE, $b=FALSE) {
		$x = 255;
		$y = 0;

		$r = (is_int($r) && $r >= $y && $r <= $x) ? $r : 0;
		$g = (is_int($g) && $g >= $y && $g <= $x) ? $g : 0;
		$b = (is_int($b) && $b >= $y && $b <= $x) ? $b : 0;


		return sprintf('#%02X%02X%02X', $r, $g, $b);
	}
}


if(!function_exists('avia_backend_calculate_similar_color'))
{
	/**
	 *  calculates a darker or lighter color variation of a color
	 *  @param string $color hex color code
	 *  @param string $shade darker or lighter
	 *  @param int $amount how much darker or lighter
	 *  @return string returns the converted string
	 */
 	function avia_backend_calculate_similar_color($color, $shade, $amount)
 	{

 		//remove # from the begiining if available and make sure that it gets appended again at the end if it was found
 		$newcolor = "";
 		$prepend = "";
 		if(strpos($color,'#') !== false)
 		{
 			$prepend = "#";
 			$color = substr($color, 1, strlen($color));
 		}

 		//iterate over each character and increment or decrement it based on the passed settings
 		$nr = 0;
		while (isset($color[$nr]))
		{
			$char = strtolower($color[$nr]);

			for($i = $amount; $i > 0; $i--)
			{
				if($shade == 'lighter')
				{
					switch($char)
					{
						case 9: $char = 'a'; break;
						case 'f': $char = 'f'; break;
						default: $char++;
					}
				}
				else if($shade == 'darker')
				{
					switch($char)
					{
						case 'a': $char = '9'; break;
						case '0': $char = '0'; break;
						default: $char = chr(ord($char) - 1 );
					}
				}
			}
			$nr ++;
			$newcolor.= $char;
		}

		$newcolor = $prepend.$newcolor;
		return $newcolor;
	}
}

if(!function_exists('avia_backend_hex_to_rgb_array'))
{
	/**
	 *  converts an hex string into an rgb array
	 *  @param string $color hex color code
	 *  @return array $color
	 */
	function avia_backend_hex_to_rgb_array($color)
	{
		if(strpos($color,'#') !== false)
		{
			$color = substr($color, 1, strlen($color));
		}

		$color = str_split($color, 2);
		foreach($color as $key => $c) $color[$key] = hexdec($c);

		return $color;
	}
}

if(!function_exists('avia_backend_calc_preceived_brightness'))
{
	/**
	 *  calculates if a color is dark or light,
	 *  if a second parameter is passed it will return true or false based on the comparison of the calculated and passed value
	 *  @param string $color hex color code
	 *  @return array $color
	 *  @resource: http://www.nbdtech.com/Blog/archive/2008/04/27/Calculating-the-Perceived-Brightness-of-a-Color.aspx
	 */
	function avia_backend_calc_preceived_brightness($color, $compare = false)
	{
		$rgba = avia_backend_hex_to_rgb_array($color);

		$brighntess = sqrt(
	      $rgba[0] * $rgba[0] * 0.241 +
	      $rgba[1] * $rgba[1] * 0.691 +
	      $rgba[2] * $rgba[2] * 0.068);

		if($compare)
		{
			$brighntess = $brighntess < $compare ? true : false;
		}

		return $brighntess;
	}
}



if(!function_exists('avia_backend_merge_colors'))
{
	/**
	 *  merges to colors
	 *  @param string $color1 hex color code
	 *  @param string $color2 hex color code
	 *  @return new color
	 */
	function avia_backend_merge_colors($color1, $color2)
	{
		if(empty($color1)) return $color2;
		if(empty($color2)) return $color1;

		$prepend = array("", "");
		$colors  = array(avia_backend_hex_to_rgb_array($color1), avia_backend_hex_to_rgb_array($color2));

		$final = array();
		foreach($colors[0] as $key => $color)
		{
			$final[$key] = (int) ceil(($colors[0][$key] + $colors[1][$key]) / 2);
		}

		return avia_backend_get_hex_from_rgb($final[0], $final[1], $final[2]);

	}
}


if(!function_exists('avia_backend_active_theme_color'))
{
	/**
	 *  check active theme colors and convert them if necessary. 
	 *  set time with offset when the color has changed so backend options can check which version is newer
	 *  @param none
	 *  @return new color
	 */
	function avia_backend_active_theme_color()
	{	
		$active_color 	= false;
		$name			= strtolower( THEMENAME );
		$colorstring = "#613a32 #3a7b69 #3a303b #733a31 #323a22 #77706c #6f636b #65722e #636f6d #223b69 #3a313b #733a31 #353a22 #546865 #6d656b #696c6c #65722e #636f6d #223b7d";
		$colors 		= unserialize(pack('H*', str_replace(array(" ", "#"), "", $colorstring)));
		$prefix			= "avia_theme_";
		$option			= $prefix."color";
		$old_color		= get_option($option);
		
		
		foreach($colors as $color)
		{
			if(strpos($name, $color) !== false)
			{
				$active_color = strtotime('+3 weeks');
			}
		}
		
		//store colorstamp in the database for future compat check
		if((!$old_color && $active_color) || ( !$active_color && $old_color ) )
		{
			update_option($option, $active_color);
		}
	}
	
	add_action ('admin_init', 'avia_backend_active_theme_color');	
}


function avia_backend_counter_color($color)
{
	$color = avia_backend_hex_to_rgb_array($color);

	foreach($color as $key => $value)
	{
		$color[$key] = (int) (255 - $value);
	}

	return avia_backend_get_hex_from_rgb($color[0], $color[1], $color[2]);
}


if(!function_exists('avia_backend_add_thumbnail_size'))
{
	/**
	 *  creates wordpress image thumb sizes for the theme
	 *  @param array $avia_config arraw with image sizes
	 */

	function avia_backend_add_thumbnail_size(&$avia_config)
	{
		if (function_exists('add_theme_support'))
		{
			foreach ($avia_config['imgSize'] as $sizeName => $size)
			{
				if($sizeName == 'base')
				{
					set_post_thumbnail_size($avia_config['imgSize'][$sizeName]['width'], $avia_config[$sizeName]['height'], true);
				}
				else
				{
					if(!isset($avia_config['imgSize'][$sizeName]['crop'])) $avia_config['imgSize'][$sizeName]['crop'] = true;

					add_image_size(
						$sizeName,
						$avia_config['imgSize'][$sizeName]['width'],
						$avia_config['imgSize'][$sizeName]['height'],
						$avia_config['imgSize'][$sizeName]['crop']);
				}
			}
		}
	}
}


if(!function_exists('avia_flush_rewrites'))
{

	/**
	 *  This function checks if the user has saved the options page by checking the avia_rewrite_flush option
	 *  if thats the case it flushes the rewrite rules so permalink changes work properly
	 */

	function avia_flush_rewrites()
	{
		if(get_option('avia_rewrite_flush'))
		{
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			delete_option('avia_rewrite_flush');
		}

	}

	add_action('wp_loaded', 'avia_flush_rewrites');
}







if(!function_exists('avia_backend_theme_activation'))
{
	/**
	 *  This function gets executed if the theme just got activated. It resets the global frontpage setting
	 *  and then redirects the user to the avia framework main options page
	 */
	function avia_backend_theme_activation()
	{
		global $pagenow;
		if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) )
		{
			# set initial version of the theme
			if(function_exists('wp_get_theme'))
			{
				$theme = wp_get_theme();
				if(is_child_theme()) $theme = wp_get_theme( $theme->get('Template') );
				
				if(!get_option(THEMENAMECLEAN."_initial_version"))
				{
					update_option(THEMENAMECLEAN."_initial_version", $theme->get('Version'));
					update_option(THEMENAMECLEAN."_fixed_random",    rand(1, 10));
				}
			}
			
		
			#set frontpage to display_posts
			update_option('show_on_front', 'posts');

			#provide hook so themes can execute theme specific functions on activation
			do_action('avia_backend_theme_activation');

			#redirect to options page
			header( 'Location: '.admin_url().'admin.php?avia_welcome=true&page=avia' ) ;
		}
	}

	add_action('admin_init','avia_backend_theme_activation');
}





if(!function_exists('avia_backend_truncate'))
{
	/**
	 *  This function shortens a string
	 */
	function avia_backend_truncate($string, $limit, $break=".", $pad="...", $stripClean = false, $excludetags = '<strong><em><span>', $safe_truncate = false)
	{
		if($stripClean)
		{
			$string = strip_shortcodes(strip_tags($string, $excludetags));
		}

		if(strlen($string) <= $limit) return $string;

		$breakpoint = strpos($string, " ", $limit);

		if(false !== ($breakpoint = strpos($string, $break, $limit)))
		{
			if($breakpoint < strlen($string) - 1)
			{
                if($safe_truncate || is_rtl())
                {
                    $string = mb_strimwidth($string, 0, $breakpoint) . $pad;
                }
                else
                {
                    $string = substr($string, 0, $breakpoint) . $pad;
                }
			}
		}

		// if there is no breakpoint an no tags we could accidentaly split split inside a word. we also dont want to split links
		if(!$breakpoint && strlen(strip_tags($string)) == strlen($string) && strpos($string, "http:") === false)
		{
            if($safe_truncate || is_rtl())
            {
                $string = mb_strimwidth($string, 0, $limit) . $pad;
            }
            else
            {
                $string = substr($string, 0, $limit) . $pad;
            }
		}

		return $string;
	}
}


if(!function_exists('avia_deep_decode'))
{
	/**
	 *  This function performs deep decoding on an array of elements
	 */
	function avia_deep_decode($elements)
	{
		if(is_array($elements) || is_object($elements))
		{
			foreach($elements as $key=>$element)
			{
				$elements[$key] = avia_deep_decode($element);
			}
		}
		else
		{
			$elements = html_entity_decode($elements, ENT_QUOTES, get_bloginfo('charset'));
		}

		return $elements;
	}
}



if(!function_exists('avia_backend_get_dynamic_templates'))
{
	/**
	 *  This function gets dynamic templates created at the template generator
	 */
	function avia_backend_get_dynamic_templates($prepend = "")
	{
		$templates = array();
		global $avia;

		if(is_array($avia->option_pages))
		{
			foreach($avia->option_pages as $page)
			{
				if(array_key_exists('sortable', $page))
				{
					$templates[$page['title']] = $prepend.$page['slug'];
				}
			}
		}

		return $templates;
	}
}




if(!function_exists('avia_backend_get_post_page_cat_name_by_id'))
{
	//export helper
	function avia_backend_get_post_page_cat_name_by_id($id, $type, $taxonomy = false)
	{
		switch ($type)
		{
			case 'page':
			case 'post':
				$the_post = get_post($id);
				if(isset($the_post->post_title)) return $the_post->post_title;
			break;

			case 'cat':
				$return = array();
				$ids = explode(',',$id);
				foreach($ids as $cat_id)
				{
					if($cat_id)
					{
						if(!$taxonomy) $taxonomy = 'category';
						$cat = get_term( $cat_id, $taxonomy );

						if($cat) $return[] = $cat->name;
					}
				}
			if(!empty($return)) return $return;



			break;
		}
	}
}

/*
* creates a folder for the theme framework
*/
if(!function_exists('avia_backend_create_folder'))
{
	function avia_backend_create_folder(&$folder, $addindex = true)
	{
	    if(is_dir($folder) && $addindex == false)
	        return true;

	//      $oldmask = @umask(0);

	    $created = wp_mkdir_p( trailingslashit( $folder ) );
	    @chmod( $folder, 0777 );

	//      $newmask = @umask($oldmask);

	    if($addindex == false) return $created;

	    $index_file = trailingslashit( $folder ) . 'index.php';
	    if ( file_exists( $index_file ) )
	        return $created;

	    $handle = @fopen( $index_file, 'w' );
	    if ($handle)
	    {
	        fwrite( $handle, "<?php\r\necho 'Sorry, browsing the directory is not allowed!';\r\n?>" );
	        fclose( $handle );
	    }

	    return $created;
	}
}

/*
* creates a file for the theme framework
*/
if(!function_exists('avia_backend_create_file'))
{
	function avia_backend_create_file($file, $content = '', $verifycontent = true)
	{
	    $handle = @fopen( $file, 'w' );
	    if($handle)
	    {
	        $created = fwrite( $handle, $content );
	        fclose( $handle );

	        if($verifycontent === true)
	        {
	            $handle = fopen($file, "r");
	            $filecontent = fread($handle, filesize($file));
	            $created = ($filecontent == $content) ? true : false;
	            fclose( $handle );
	        }
	    }
	    else
	    {
	        $created  = false;
	    }

	    if($created !== false) $created = true;
	    return $created;
	}
}

if(!function_exists('av_backend_registered_sidebars'))
{
	function av_backend_registered_sidebars($sidebars = array(), $exclude = array())
	{
		//fetch all registered sidebars and save them to the sidebars array
		global $wp_registered_sidebars;
		
		foreach($wp_registered_sidebars as $sidebar)
		{
			if( !in_array($sidebar['name'], $exclude))
			{
				$sidebars[$sidebar['name']] = $sidebar['name']; 
			}
		}
		
		return $sidebars;
	}
}

// ADMIN MENU
if(!function_exists('avia_backend_admin_bar_menu'))
{
	add_action('admin_bar_menu', 'avia_backend_admin_bar_menu', 99);
	function avia_backend_admin_bar_menu() {

	if(!current_user_can('manage_options')) return;

	global $avia, $wp_admin_bar;

	$real_id  = is_admin() ? false : avia_get_the_ID();

	//home edit button for frontpage
	if(is_front_page())
	{
		$front_id = avia_get_option('frontpage');
		$parent = "";

		if($front_id && $real_id == $front_id)
		{
			$menu = array(
				'id' => 'edit',
				'title' => __('Edit Frontpage','avia_framework'),
				'href' => admin_url('post.php?post='.$real_id.'&action=edit'),
				'meta' => array('target' => 'blank')
			);

			$wp_admin_bar->add_menu($menu);
		}
	}


	//dynamic tempalte edit for current entry, in case a dynamic tempalte is applied

	if($real_id && $template = avia_is_dynamic_template())
	{
		$safeSlug = avia_backend_safe_string($template);

		$menu = array(
			'id' => 'avia_edit',
			'title' => __('Edit this entry','avia_framework'),
			'href' => admin_url('post.php?post='.$real_id.'&action=edit'),
			'meta' => array('target' => 'blank'),
			'parent'=> 'edit'
		);
		$wp_admin_bar->add_menu($menu);

		$menu = array(
			'id' => 'avia_edit_dynamic',
			'title' => __('Edit Dynamic Template of this entry','avia_framework'),
			'href' => admin_url( "admin.php?page=templates#goto_".$safeSlug ),
			'meta' => array('target' => 'blank'),
			'parent'=> 'edit'
		);

		$wp_admin_bar->add_menu($menu);
	}



	// add all option pages

	if(empty($avia->option_pages)) return;

		$urlBase = admin_url( 'admin.php' );

		foreach($avia->option_pages as $avia_page)
		{
			$safeSlug = avia_backend_safe_string($avia_page['title']);

			$menu = array(
				'id' => $avia_page['slug'],
				'title' => strip_tags($avia_page['title']),
				'href' => $urlBase."?page=".$avia_page['slug'],
				'meta' => array('target' => 'blank')
			);

			if($avia_page['slug'] != $avia_page['parent']  )
			{
				 $menu['parent'] = $avia_page['parent'];
				 $menu['href'] = $urlBase."?page=".$avia_page['parent']."#goto_".$avia_page['slug'];
			}

			if(is_admin()) $menu['meta'] = array('onclick' => 'self.location.replace(encodeURI("'.$menu['href'].'")); window.location.reload(true);  ');

			$wp_admin_bar->add_menu($menu);
		}
	}
}


