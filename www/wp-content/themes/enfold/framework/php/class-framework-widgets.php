<?php  if ( ! defined('AVIA_FW')) exit('No direct script access allowed');
/**
 * This file holds several widgets exclusive to the framework
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright (c) Christian Budschedl
 * @link		http://Kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */







/**
 * AVIA FACEBOOK WIDGET
 */

if (!class_exists('avia_fb_likebox'))
{
	class avia_fb_likebox extends WP_Widget {
	
		static $script_loaded = 0;
	
		function __construct() {
			//Constructor
			$widget_ops = array('classname' => 'avia_fb_likebox', 'description' => __('A widget that displays a facebook Likebox to a facebook page of your choice', 'avia_framework') );
			parent::__construct( 'avia_fb_likebox', THEMENAME.' Facebook Likebox', $widget_ops );
		}

		function widget($args, $instance)
		{
			// prints the widget

			extract($args, EXTR_SKIP);
			if(empty($instance['url'])) return;
			$url 		= $instance['url'];
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$height 	= 151; 
			$faces 		= "true";
			$extraClass = "";
			$style 		= "";
			
			
			if(strpos($height, "%") !== false)
			{
				$extraClass = "av_facebook_widget_wrap_positioner";
				$style		= "style='padding-bottom:{$height}'";
				$height		= "100%";
			}
			
			
			echo $before_widget;
			
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
			
			echo "<div class='av_facebook_widget_wrap {$extraClass}' {$style}>";
			echo '<div class="fb-page" data-width="500" data-href="'.$url.'" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"></div></div>';
			echo "</div>";
			echo $after_widget;
			add_action('wp_footer', array( $this,'fb_js' ));
		}
		
		
		
		function fb_js()
		{
			if ( function_exists('icl_object_id') ) {
				$locale = ICL_LANGUAGE_NAME_EN;
				$fbxml = @simplexml_load_file( AVIA_BASE . '/config-wpml/FacebookLocales.xml' );
				
				if(is_object($fbxml))
				{
					$langcode = array();
					foreach($fbxml as $loc) {
						if($loc->englishName == $locale) {
							$langcode = $loc->codes->code->standard->representation;
						}
					}
				}
		 	}

			$langcode = function_exists('icl_object_id') && !empty($langcode) ? $langcode : get_locale();

			if(self::$script_loaded == 1) return;
			self::$script_loaded = 1;

			echo '
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/'. $langcode .'/sdk.js#xfbml=1&version=v2.7";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>';

		}


		function update($new_instance, $old_instance)
		{
			$instance = $old_instance;
			foreach($new_instance as $key=>$value)
			{
				$instance[$key]	= strip_tags($new_instance[$key]);
			}

			return $instance;
		}

		function form($instance) {
			//widgetform in backend

			$instance = wp_parse_args( (array) $instance, array('url' => 'https://www.facebook.com/kriesi.at', 'title' => '') );
			$html = new avia_htmlhelper();
			
			
	?>
			
			<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'avia_framework'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></label>
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Enter the url to the Page. Please note that it needs to be a link to a <strong>facebook fanpage</strong>. Personal profiles are not allowed!', 'avia_framework'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo esc_attr($instance['url']); ?>" /></label>
			</p>
			
			


		<?php
		}
	}
}
















/**
 * AVIA TWEETBOX
 *
 * Widget that creates a list of latest tweets
 *
 * @package AviaFramework
 * @todo replace the widget system with a dynamic one, based on config files for easier widget creation
 */



/*
Twitter widget only for compatibility reasons with older themes present. no onger used since API will be shut down by twitter
*/
if (!class_exists('avia_tweetbox'))
{
	class avia_tweetbox extends WP_Widget {

		function __construct() {
			//Constructor
			$widget_ops = array('classname' => 'tweetbox', 'description' => 'A widget to display your latest twitter messages' );
			parent::__construct( 'tweetbox', THEMENAME.' Twitter Widget', $widget_ops );
		}

		function widget($args, $instance) {
			// prints the widget

			extract($args, EXTR_SKIP);
			echo $before_widget;

			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$count = empty($instance['count']) ? '' : $instance['count'];
			$username = empty($instance['username']) ? '' : $instance['username'];
			$exclude_replies = empty($instance['exclude_replies']) ? '' : $instance['exclude_replies'];
			$time = empty($instance['time']) ? 'no' : $instance['time'];
			$display_image = empty($instance['display_image']) ? 'no' : $instance['display_image'];

			if ( !empty( $title ) ) { echo $before_title . "<a href='http://twitter.com/$username/' title='".strip_tags($title)."'>".$title ."</a>". $after_title; };

			$messages = tweetbox_get_tweet($count, $username, $widget_id, $time, $exclude_replies, $display_image);
			echo $messages;

			echo $after_widget;


		}

		function update($new_instance, $old_instance) {
			//save the widget
			$instance = $old_instance;
			foreach($new_instance as $key=>$value)
			{
				$instance[$key]	= strip_tags($new_instance[$key]);
			}

			delete_transient(THEMENAME.'_tweetcache_id_'.$instance['username'].'_'.$this->id_base."-".$this->number);
			return $instance;
		}

		function form($instance) {
			//widgetform in backend

			$instance = wp_parse_args( (array) $instance, array( 'title' => 'Latest Tweets', 'count' => '3', 'username' => avia_get_option('twitter') ) );
			$title = 			isset($instance['title']) ? strip_tags($instance['title']): "";
			$count = 			isset($instance['count']) ? strip_tags($instance['count']): "";
			$username = 		isset($instance['username']) ? strip_tags($instance['username']): "";
			$exclude_replies = 	isset($instance['exclude_replies']) ? strip_tags($instance['exclude_replies']): "";
			$time = 			isset($instance['time']) ? strip_tags($instance['time']): "";
			$display_image = 	isset($instance['display_image']) ? strip_tags($instance['display_image']): "";
	?>
			<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'avia_framework'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('username'); ?>">Enter your twitter username:
			<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo esc_attr($username); ?>" /></label></p>

			<p>
				<label for="<?php echo $this->get_field_id('count'); ?>">How many entries do you want to display: </label>
				<select class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>">
					<?php
					$list = "";
					for ($i = 1; $i <= 20; $i++ )
					{
						$selected = "";
						if($count == $i) $selected = 'selected="selected"';

						$list .= "<option $selected value='$i'>$i</option>";
					}
					$list .= "</select>";
					echo $list;
					?>


			</p>

			<p>
				<label for="<?php echo $this->get_field_id('exclude_replies'); ?>">Exclude @replies: </label>
				<select class="widefat" id="<?php echo $this->get_field_id('exclude_replies'); ?>" name="<?php echo $this->get_field_name('exclude_replies'); ?>">
					<?php
					$list = "";
					$answers = array('yes','no');
					foreach ($answers as $answer)
					{
						$selected = "";
						if($answer == $exclude_replies) $selected = 'selected="selected"';

						$list .= "<option $selected value='$answer'>$answer</option>";
					}
					$list .= "</select>";
					echo $list;
					?>


			</p>

			<p>
				<label for="<?php echo $this->get_field_id('time'); ?>">Display time of tweet</label>
				<select class="widefat" id="<?php echo $this->get_field_id('time'); ?>" name="<?php echo $this->get_field_name('time'); ?>">
					<?php
					$list = "";
					$answers = array('yes','no');
					foreach ($answers as $answer)
					{
						$selected = "";
						if($answer == $time) $selected = 'selected="selected"';

						$list .= "<option $selected value='$answer'>$answer</option>";
					}
					$list .= "</select>";
					echo $list;
					?>


			</p>

			<p>
				<label for="<?php echo $this->get_field_id('display_image'); ?>">Display Twitter User Avatar</label>
				<select class="widefat" id="<?php echo $this->get_field_id('display_image'); ?>" name="<?php echo $this->get_field_name('display_image'); ?>">
					<?php
					$list = "";
					$answers = array('yes','no');
					foreach ($answers as $answer)
					{
						$selected = "";
						if($answer == $display_image) $selected = 'selected="selected"';

						$list .= "<option $selected value='$answer'>$answer</option>";
					}
					$list .= "</select>";
					echo $list;
					?>
			</p>



		<?php
		}
	}
}

if(!function_exists('tweetbox_get_tweet'))
{
	function tweetbox_get_tweet($count, $username, $widget_id, $time='yes', $exclude_replies='yes', $avatar = 'yes')
	{
			$filtered_message = "";
			$output = "";
			$iterations = 0;

			$cache = get_transient(THEMENAME.'_tweetcache_id_'.$username.'_'.$widget_id);

			if($cache)
			{
				$tweets = get_option(THEMENAME.'_tweetcache_'.$username.'_'.$widget_id);
			}
			else
			{
				//$response = wp_remote_get( 'http://api.twitter.com/1/statuses/user_timeline.xml?screen_name='.$username );
				$response = wp_remote_get( 'http://api.twitter.com/1/statuses/user_timeline.xml?include_rts=true&screen_name='.$username );
				if (!is_wp_error($response))
				{
					$xml = @simplexml_load_string($response['body']);
					//follower: (int) $xml->status->user->followers_count

					if( empty( $xml->error ) )
				    {
				    	if ( isset($xml->status[0]))
				    	{

				    	    $tweets = array();
				    	    foreach ($xml->status as $tweet)
				    	    {
				    	    	if($iterations == $count) break;

				    	    	$text = (string) $tweet->text;
				    	    	if($exclude_replies == 'no' || ($exclude_replies == 'yes' && $text[0] != "@"))
				    	    	{
				    	    		$iterations++;
				    	    		$tweets[] = array(
				    	    			'text' => tweetbox_filter( $text ),
				    	    			'created' =>  strtotime( $tweet->created_at ),
				    	    			'user' => array(
				    	    				'name' => (string)$tweet->user->name,
				    	    				'screen_name' => (string)$tweet->user->screen_name,
				    	    				'image' => (string)$tweet->user->profile_image_url,
				    	    				'utc_offset' => (int) $tweet->user->utc_offset[0],
				    	    				'follower' => (int) $tweet->user->followers_count

				    	    			));
				    			}
				    		}

				    		set_transient(THEMENAME.'_tweetcache_id_'.$username.'_'.$widget_id, 'true', 60*30);
				    		update_option(THEMENAME.'_tweetcache_'.$username.'_'.$widget_id, $tweets);
				    	}
				    }
				}
			}



			if(!isset($tweets[0]))
			{
				$tweets = get_option(THEMENAME.'_tweetcache_'.$username.'_'.$widget_id);
			}

		    if(isset($tweets[0]))
		    {
		    	$time_format = apply_filters( 'avia_widget_time', get_option('date_format')." - ".get_option('time_format'), 'tweetbox' );

		    	foreach ($tweets as $message)
		    	{
		    		$output .= '<li class="tweet">';
		    		if($avatar == "yes") $output .= '<div class="tweet-thumb"><a href="http://twitter.com/'.$username.'" title=""><img src="'.$message['user']['image'].'" alt="" /></a></div>';
		    		$output .= '<div class="tweet-text avatar_'.$avatar.'">'.$message['text'];
		    		if($time == "yes") $output .= '<div class="tweet-time">'.date_i18n( $time_format, $message['created'] + $message['user']['utc_offset']).'</div>';
		    		$output .= '</div></li>';
				}
		    }


			if($output != "")
			{
				$filtered_message = "<ul class='tweets'>$output</ul>";
			}
			else
			{
				$filtered_message = "<ul class='tweets'><li>No public Tweets found</li></ul>";
			}

			return $filtered_message;
	}
}

if(!function_exists('tweetbox_filter'))
{
	function tweetbox_filter($text) {
	    // Props to Allen Shaw & webmancers.com & Michael Voigt
	    $text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\">$1</a>", $text);
	    $text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\">$1</a>", $text);
	    $text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $text);
	    $text = preg_replace("/#([\p{L}\p{Mn}]+)/u", "<a class=\"twitter-link\" href=\"http://search.twitter.com/search?q=\\1\">#\\1</a>", $text);
	    $text = preg_replace("/@([\p{L}\p{Mn}]+)/u", "<a class=\"twitter-link\" href=\"http://twitter.com/\\1\">@\\1</a>", $text);

	    return $text;
	}
}









/**
 * AVIA NEWSBOX
 *
 * Widget that creates a list of latest news entries
 *
 * @package AviaFramework
 * @todo replace the widget system with a dynamic one, based on config files for easier widget creation
 */

if (!class_exists('avia_newsbox'))
{
	class avia_newsbox extends WP_Widget {

		var $avia_term = '';
		var $avia_post_type = '';
		var $avia_new_query = '';

		function __construct()
		{
			$widget_ops = array('classname' => 'newsbox', 'description' => __('A Sidebar widget to display latest post entries in your sidebar', 'avia_framework') );

			parent::__construct( 'newsbox', THEMENAME.' Latest News', $widget_ops );
		}

		function widget($args, $instance)
		{
			global $avia_config;

			extract($args, EXTR_SKIP);
			echo $before_widget;

			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$count = empty($instance['count']) ? '' : $instance['count'];
			$cat = empty($instance['cat']) ? '' : $instance['cat'];
			$excerpt = empty($instance['excerpt']) ? '' : $instance['excerpt'];
			$image_size = isset($avia_config['widget_image_size']) ? $avia_config['widget_image_size'] : 'widget';

			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };


			if(empty($this->avia_term))
			{
				$additional_loop = new WP_Query("cat=".$cat."&posts_per_page=".$count);
			}
			else
			{
				$catarray = explode(',', $cat);


				if(empty($catarray[0]))
				{
					$new_query = array("posts_per_page"=>$count,"post_type"=>$this->avia_post_type);
				}
				else
				{
					if($this->avia_new_query)
					{
						$new_query = $this->avia_new_query;
					}
					else
					{
						$new_query = array(	"posts_per_page"=>$count, 'tax_query' => array(
														array( 'taxonomy' => $this->avia_term,
															   'field' => 'id',
															   'terms' => explode(',', $cat),
															   'operator' => 'IN')
															  )
														);
					}
				}

				$additional_loop = new WP_Query($new_query);
			}

			if($additional_loop->have_posts()) :



			echo '<ul class="news-wrap image_size_'.$image_size.'">';
			while ($additional_loop->have_posts()) : $additional_loop->the_post();

			$format = "";
			if(empty($this->avia_post_type)) 	$format = $this->avia_post_type;
			if(empty($format)) 					$format = get_post_format();
	     	if(empty($format)) 					$format = 'standard';
			
			$the_id = get_the_ID();
			$link = get_post_meta( $the_id  ,'_portfolio_custom_link', true) != "" ? get_post_meta( $the_id ,'_portfolio_custom_link_url', true) : get_permalink();
			
			
			echo '<li class="news-content post-format-'.$format.'">';

			//check for preview images:
			$image = "";

			if(!current_theme_supports('force-post-thumbnails-in-widget'))
			{
				$slides = avia_post_meta(get_the_ID(), 'slideshow', true);

				if( $slides != "" && !empty( $slides[0]['slideshow_image'] ) )
				{
					$image = avia_image_by_id($slides[0]['slideshow_image'], $image_size, 'image');
				}
			}

			if(current_theme_supports( 'post-thumbnails' ) && !$image )
			{
				$image = get_the_post_thumbnail( $the_id, $image_size );
			}

			$time_format = apply_filters( 'avia_widget_time', get_option('date_format')." - ".get_option('time_format'), 'avia_newsbox' );


			echo "<a class='news-link' title='".get_the_title()."' href='".$link."'>";

			$nothumb = (!$image) ? 'no-news-thumb' : '';

			echo "<span class='news-thumb $nothumb'>";
			echo $image;
			echo "</span>";
			if(empty($avia_config['widget_image_size']) || 'display title and excerpt' != $excerpt)
			{
				echo "<strong class='news-headline'>".get_the_title();
				
				if($time_format)
				{
					echo "<span class='news-time'>".get_the_time($time_format)."</span>";	
				}
				
				echo "</strong>";
			}
			echo "</a>";

			if( 'display title and excerpt' == $excerpt )
			{
				echo "<div class='news-excerpt'>";

				if(!empty($avia_config['widget_image_size']))
				{
					echo "<a class='news-link-inner' title='".get_the_title()."' href='".$link."'>";
					echo "<strong class='news-headline'>".get_the_title()."</strong>";
					echo "</a>";
					if($time_format)
					{
						echo "<span class='news-time'>".get_the_time($time_format)."</span>";	
					}

				}
				the_excerpt();
				echo "</div>";
			}

			echo '</li>';


			endwhile;
			echo "</ul>";
			wp_reset_postdata();
			endif;


			echo $after_widget;

		}


		function update($new_instance, $old_instance)
		{
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['count'] = strip_tags($new_instance['count']);
			$instance['excerpt'] = strip_tags($new_instance['excerpt']);
			$instance['cat'] = implode(',',$new_instance['cat']);
			return $instance;
		}



		function form($instance)
		{
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => '', 'cat' => '', 'excerpt'=>'' ) );
			$title = strip_tags($instance['title']);
			$count = strip_tags($instance['count']);
			$excerpt = strip_tags($instance['excerpt']);


			$elementCat = array("name" 	=> __("Which categories should be used for the portfolio?", 'avia_framework'), 
								"desc" 	=> __("You can select multiple categories here", 'avia_framework'),
					            "id" 	=> $this->get_field_name('cat')."[]",
					            "type" 	=> "select",
					            "std"   => strip_tags($instance['cat']),
					            "class" => "",
	            				"multiple"=>6,
					            "subtype" => "cat");
			//check if a different taxonomy than the default is set
			if(!empty($this->avia_term))
			{
				$elementCat['taxonomy'] = $this->avia_term;
			}




			$html = new avia_htmlhelper();

	?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'avia_framework'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

			<p>
				<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('How many entries do you want to display: ', 'avia_framework'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>">
					<?php
					$list = "";
					for ($i = 1; $i <= 20; $i++ )
					{
						$selected = "";
						if($count == $i) $selected = 'selected="selected"';

						$list .= "<option $selected value='$i'>$i</option>";
					}
					$list .= "</select>";
					echo $list;
					?>


			</p>

			<p><label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e('Choose the categories you want to display (multiple selection possible):', 'avia_framework'); ?>
			<?php echo $html->select($elementCat); ?>
			</label></p>

			<p>
				<label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e('Display title only or title &amp; excerpt', 'avia_framework'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>">
					<?php
					$list = "";
					$answers = array(
								'show title only'			=>	__( 'show title only', 'avia_framework' ),
								'display title and excerpt'	=>	__('display title and excerpt', 'avia_framework')
								);
					
					foreach ( $answers as $key => $answer )
					{
						$selected = "";
						if( $key == $excerpt ) $selected = 'selected="selected"';

						$list .= "<option $selected value='$key'>$answer</option>";
					}
					$list .= "</select>";
					echo $list;
					?>


			</p>


	<?php
		}
	}
}


/**
 * AVIA PORTFOLIOBOX
 *
 * Widget that creates a list of latest portfolio entries. Basically the same widget as the newsbox with some minor modifications, therefore it just extends the Newsbox
 *
 * @package AviaFramework
 * @todo replace the widget system with a dynamic one, based on config files for easier widget creation
 */

if (!class_exists('avia_portfoliobox'))
{
	class avia_portfoliobox extends avia_newsbox
	{
		function __construct()
		{
			$this->avia_term = 'portfolio_entries';
			$this->avia_post_type = 'portfolio';
			$this->avia_new_query = ''; //set a custom query here


			$widget_ops = array('classname' => 'newsbox', 'description' => __('A Sidebar widget to display latest portfolio entries in your sidebar', 'avia_framework') );

			WP_Widget::__construct( 'portfoliobox', THEMENAME.' Latest Portfolio', $widget_ops );
		}
	}
}



/**
 * AVIA SOCIALCOUNT
 *
 * Widget that retrieves, stores and displays the number of twitter and rss followers
 *
 * @package AviaFramework
 * @todo replace the widget system with a dynamic one, based on config files for easier widget creation
 */

if (!class_exists('avia_socialcount'))
{
	class avia_socialcount extends WP_Widget {

		function __construct() {
			//Constructor
			$widget_ops = array('classname' => 'avia_socialcount', 'description' => __('A widget to display a link to your twitter profile and rss feed', 'avia_framework') );
			parent::__construct( 'avia_socialcount', THEMENAME.' RSS Link and Twitter Account', $widget_ops );
		}

		function widget($args, $instance) {
			// prints the widget

			extract($args, EXTR_SKIP);
			$twitter = empty($instance['twitter']) ? '' : $instance['twitter'];
			$rss 	 = empty($instance['rss'])     ? '' : $instance['rss'];
			$rss = preg_replace('!https?:\/\/feeds.feedburner.com\/!','',$rss);


			if(!empty($twitter) || !empty($rss))
			{
				$addClass = "asc_multi_count";
				if(!isset($twitter) || !isset($rss)) $addClass = 'asc_single_count';

				echo $before_widget;
				$output = "";
				if(!empty($twitter))
				{
					$link = 'http://twitter.com/'.$twitter.'/';
					$before = apply_filters('avf_social_widget', "", 'twitter');
					$output .= "<a href='$link' class='asc_twitter $addClass'>{$before}<strong class='asc_count'>".__('Follow','avia_framework')."</strong><span>".__('on Twitter','avia_framework')."</span></a>";
					
				}

				if($rss)
				{
					$output .= "<a href='$rss' class='asc_rss $addClass'>".apply_filters('avf_social_widget',"", 'rss')."<strong class='asc_count'>".__('Subscribe','avia_framework')."</strong><span>".__('to RSS Feed','avia_framework')."</span></a>";
				}

				echo $output;
				echo $after_widget;
			}
		}



		function update($new_instance, $old_instance) {
			//save the widget
			$instance = $old_instance;
			foreach($new_instance as $key=>$value)
			{
				$instance[$key]	= strip_tags($new_instance[$key]);
			}

			return $instance;
		}

		function form($instance) {
			//widgetform in backend

			$instance = wp_parse_args( (array) $instance, array('rss' => avia_get_option('feedburner'), 'twitter' => avia_get_option('twitter') ) );
			$twitter = empty($instance['twitter']) ? '' :  strip_tags($instance['twitter']);
			$rss 	 = empty($instance['rss'])     ? '' :  strip_tags($instance['rss']);
	?>
			<p>
			<label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter Username:', 'avia_framework'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo esc_attr($twitter); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e('Enter your feed url:', 'avia_framework'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('rss'); ?>" name="<?php echo $this->get_field_name('rss'); ?>" type="text" value="<?php echo esc_attr($rss); ?>" /></label></p>



		<?php
		}
	}
}




/**
 * AVIA ADVERTISING WIDGET
 *
 * Widget that retrieves, stores and displays the number of twitter and rss followers
 *
 * @package AviaFramework
 * @todo replace the widget system with a dynamic one, based on config files for easier widget creation
 */


//multiple images
if (!class_exists('avia_partner_widget'))
{
	class avia_partner_widget extends WP_Widget {

		function __construct() {

			$this->add_cont = 2;
			//Constructor
			$widget_ops = array('classname' => 'avia_partner_widget', 'description' => __('An advertising widget that displays 2 images with 125 x 125 px in size', 'avia_framework') );
			parent::__construct( 'avia_partner_widget', THEMENAME.' Advertising Area', $widget_ops );
		}

		function widget($args, $instance)
		{
			extract($args, EXTR_SKIP);
			echo $before_widget;

			global $kriesiaddwidget, $firsttitle;
			$kriesiaddwidget ++;

			$title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$image_url = empty($instance['image_url']) ? '<span class="avia_parnter_empty"><span>'.__('Advertise here','avia_framework').'</span></span>' : '<img class="rounded" src="'.$instance['image_url'].'" title="'.$title.'" alt="'.$title.'"/>';
			$ref_url = empty($instance['ref_url']) ? '#' : apply_filters('widget_comments_title', $instance['ref_url']);
			$image_url2 = empty($instance['image_url2']) ? '<span class="avia_parnter_empty"><span>'.__('Advertise here','avia_framework').'</span></span>' : '<img class="rounded" src="'.$instance['image_url2'].'" title="'.$title.'" alt="'.$title.'"/>';
			$ref_url2 = empty($instance['ref_url2']) ? '#' : apply_filters('widget_comments_title', $instance['ref_url2']);

			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
			echo '<a target="_blank" href="'.$ref_url.'" class="preloading_background  avia_partner1 link_list_item'.$kriesiaddwidget.' '.$firsttitle.'" >'.$image_url.'</a>';
			if($this->add_cont == 2) echo '<a target="_blank" href="'.$ref_url2.'" class="preloading_background avia_partner2 link_list_item'.$kriesiaddwidget.' '.$firsttitle.'" >'.$image_url2.'</a>';
			echo $after_widget;

			if($title == '')
			{
				$firsttitle = 'no_top_margin';
			}

		}


		function update($new_instance, $old_instance) {
			//save the widget
			$instance = $old_instance;
			foreach($new_instance as $key=>$value)
			{
				$instance[$key]	= strip_tags($new_instance[$key]);
			}
			return $instance;
		}



		function form($instance)
		{
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'image_url' => '', 'ref_url' => '', 'image_url2' => '', 'ref_url2' => '' ) );
			$title = strip_tags($instance['title']);
			$image_url = strip_tags($instance['image_url']);
			$ref_url = strip_tags($instance['ref_url']);
			$image_url2 = strip_tags($instance['image_url2']);
			$ref_url2 = strip_tags($instance['ref_url2']);
	?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'avia_framework'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Image URL:', 'avia_framework'); ?> <?php if($this->add_cont == 2) echo "(125px * 125px):"; ?>
			<input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php echo esc_attr($image_url); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('ref_url'); ?>"><?php _e('Referal URL:', 'avia_framework'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('ref_url'); ?>" name="<?php echo $this->get_field_name('ref_url'); ?>" type="text" value="<?php echo esc_attr($ref_url); ?>" /></label></p>

			<?php if($this->add_cont == 2)
			{ ?>

					<p><label for="<?php echo $this->get_field_id('image_url2'); ?>"><?php _e('Image URL 2: (125px * 125px):', 'avia_framework'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('image_url2'); ?>" name="<?php echo $this->get_field_name('image_url2'); ?>" type="text" value="<?php echo esc_attr($image_url2); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('ref_url2'); ?>"><?php _e('Referal URL 2:', 'avia_framework'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('ref_url2'); ?>" name="<?php echo $this->get_field_name('ref_url2'); ?>" type="text" value="<?php echo esc_attr($ref_url2); ?>" /></label></p>

			<?php }?>

	<?php
		}
	}
}


if (!class_exists('avia_one_partner_widget'))
{
	//one image
	class avia_one_partner_widget extends avia_partner_widget
	{
		function __construct()
		{

			$this->add_cont = 1;

			$widget_ops = array('classname' => 'avia_one_partner_widget', 'description' => __('An advertising widget that displays 1 big image', 'avia_framework') );

			parent::__construct( 'avia_one_partner_widget', THEMENAME.' Big Advertising Area', $widget_ops );
		}
	}
}



/**
 * AVIA COMBO WIDGET
 *
 * Widget that retrieves, stores and displays the number of twitter and rss followers
 *
 * @package AviaFramework
 * @todo replace the widget system with a dynamic one, based on config files for easier widget creation
 */

if (!class_exists('avia_combo_widget'))
{
	class avia_combo_widget extends WP_Widget {

		function __construct() {
			//Constructor
			$widget_ops = array('classname' => 'avia_combo_widget', 'description' => __('A widget that displays your popular posts, recent posts, recent comments and a tagcloud', 'avia_framework') );
			parent::__construct( 'avia_combo_widget', THEMENAME.' Combo Widget', $widget_ops );
		}

		function widget($args, $instance)
		{
			// prints the widget

			extract($args, EXTR_SKIP);
			$posts = empty($instance['count']) ? 4 : $instance['count'];

			echo $before_widget;
			echo "<div class='tabcontainer border_tabs top_tab tab_initial_open tab_initial_open__1'>";

			echo '<div class="tab first_tab active_tab widget_tab_popular"><span>'.__('Popular', 'avia_framework').'</span></div>';
			echo "<div class='tab_content active_tab_content'>";
			avia_get_post_list('cat=&orderby=comment_count&posts_per_page='.$posts);
			echo "</div>";

			echo '<div class="tab widget_tab_recent"><span>'.__('Recent', 'avia_framework').'</span></div>';
			echo "<div class='tab_content'>";
			avia_get_post_list('showposts='. $posts .'&orderby=post_date&order=desc');
			echo "</div>";

			echo '<div class="tab widget_tab_comments"><span>'.__('Comments', 'avia_framework').'</span></div>';
			echo "<div class='tab_content'>";
			avia_get_comment_list( array('number' => $posts, 'status' => 'approve', 'order' => 'DESC') );
			echo "</div>";

			echo '<div class="tab last_tab widget_tab_tags"><span>'.__('Tags', 'avia_framework').'</span></div>';
			echo "<div class='tab_content tagcloud'>";
			wp_tag_cloud('smallest=12&largest=12&unit=px');
			echo "</div>";

			echo "</div>";
			echo $after_widget;
		}


		function update($new_instance, $old_instance)
		{
			$instance = $old_instance;
			foreach($new_instance as $key=>$value)
			{
				$instance[$key]	= strip_tags($new_instance[$key]);
			}

			return $instance;
		}

		function form($instance) {
			//widgetform in backend

			$instance = wp_parse_args( (array) $instance, array('count' => 4) );
			if(!is_numeric($instance['count'])) $instance['count'] = 4;

	?>
			<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of posts you want to display:', 'avia_framework'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr($instance['count']); ?>" /></label></p>


		<?php
		}
	}
}

/*-----------------------------------------------------------------------------------
get posts posts
-----------------------------------------------------------------------------------*/
if (!function_exists('avia_get_post_list'))
{
	function avia_get_post_list( $avia_new_query , $excerpt = false)
	{
		global $avia_config;
		$image_size = isset($avia_config['widget_image_size']) ? $avia_config['widget_image_size'] : 'widget';
		$additional_loop = new WP_Query($avia_new_query);

		if($additional_loop->have_posts()) :
		echo '<ul class="news-wrap">';
		while ($additional_loop->have_posts()) : $additional_loop->the_post();

		$format = "";
		if(get_post_type() != 'post') 		$format = get_post_type();
		if(empty($format)) 					$format = get_post_format();
     	if(empty($format)) 					$format = 'standard';

		echo '<li class="news-content post-format-'.$format.'">';

		//check for preview images:
		$image = "";

		if(!current_theme_supports('force-post-thumbnails-in-widget'))
			{
			$slides = avia_post_meta(get_the_ID(), 'slideshow');

			if( $slides != "" && !empty( $slides[0]['slideshow_image'] ) )
			{
				$image = avia_image_by_id($slides[0]['slideshow_image'], 'widget', 'image');
			}
		}

		if(!$image && current_theme_supports( 'post-thumbnails' ))
		{
			$image = get_the_post_thumbnail( get_the_ID(), $image_size );
		}

		$time_format = apply_filters( 'avia_widget_time', get_option('date_format')." - ".get_option('time_format'), 'avia_get_post_list' );

		$nothumb = (!$image) ? 'no-news-thumb' : '';

		echo "<a class='news-link' title='".get_the_title()."' href='".get_permalink()."'>";
		echo "<span class='news-thumb $nothumb'>";
		echo $image;
		echo "</span>";
		echo "<strong class='news-headline'>".avia_backend_truncate(get_the_title(), 55," ");
		echo "<span class='news-time'>".get_the_time($time_format)."</span>";
		echo "</strong>";
		echo "</a>";

		if('display title and excerpt' == $excerpt)
		{
			echo "<div class='news-excerpt'>";
			the_excerpt();
			echo "</div>";
		}

		echo '</li>';


		endwhile;
		echo "</ul>";
		wp_reset_postdata();
		endif;
	}
}





if (!function_exists('avia_get_comment_list'))
{

	function avia_get_comment_list($avia_new_query)
	{
		$time_format = apply_filters( 'avia_widget_time', get_option('date_format')." - ".get_option('time_format'), 'avia_get_comment_list' );

		global $avia_config;
		
		
		$comments = get_comments($avia_new_query);

		if(!empty($comments)) :
		echo '<ul class="news-wrap">';
		foreach($comments as $comment)
		{
			if ($comment->comment_author != 'ActionScheduler')
			{
			$gravatar_alt = esc_html($comment->comment_author);
			echo '<li class="news-content">';
			echo "<a class='news-link' title='".get_the_title($comment->comment_post_ID)."' href='".get_comment_link($comment)."'>";
			echo "<span class='news-thumb'>";
			echo get_avatar($comment,'48', '', $gravatar_alt);
			echo "</span>";
			echo "<strong class='news-headline'>".avia_backend_truncate($comment->comment_content, 55," ");
			
			if($time_format)
			{
				echo "<span class='news-time'>".get_comment_date($time_format, $comment->comment_ID)." ".__('by','avia_framework')." ".$comment->comment_author."</span>";
			}
			echo "</strong>";
			echo "</a>";
			echo '</li>';
			}
		}
		echo "</ul>";
		wp_reset_postdata();
		endif;
	}
}



/*
	Google Maps Widget

	Copyright 2009  Clark Nikdel Powell  (email : taylor@cnpstudio.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists('avia_google_maps'))
{
	class avia_google_maps extends WP_Widget {

		// constructor
		function __construct() {
			$widget_ops = array('classname' => 'avia_google_maps', 'description' => __( 'Add a google map to your blog or site', 'avia_framework') );
			parent::__construct('avia_google_maps', THEMENAME.' Google Maps Widget', $widget_ops);

            add_action( 'admin_enqueue_scripts', array(&$this,'helper_print_google_maps_scripts') );
		}

		// output the content of the widget
		function widget($args, $instance) {
			extract( $args );

			$title = empty($instance['title']) ? '' : apply_filters('widget_title', esc_attr($instance['title']));

			print $before_widget;
			if (!empty($instance['title'])) { print $before_title.$title.$after_title; }
			print avia_printmap($instance['lat'], $instance['lng'], $instance['zoom'], $instance['type'], $instance['content'], $instance['directionsto'], $instance['width'], $instance['height'], $instance['icon']);
			print $after_widget;
		}

		// process widget options to be saved
		function update($new_instance, $old_instance) {
			print_r($old_instance);
			print_r($new_instance);
			return $new_instance;
		}

		// output the options form on admin
		function form($instance) {
			global $wpdb;
			$title = empty($instance['title']) ? '' : esc_attr($instance['title']);
			$lat = empty($instance['lat']) ? '' : esc_attr($instance['lat']);
			$lng = empty($instance['lng']) ? '' : esc_attr($instance['lng']);
			$zoom = empty($instance['zoom']) ? '15' : esc_attr($instance['zoom']);
			$type = empty($instance['type']) ? 'ROADMAP' : esc_attr($instance['type']);
			$directionsto = empty($instance['directionsto']) ? '' : esc_attr($instance['directionsto']);
			$content = empty($instance['content']) ? '' : esc_attr($instance['content']);
            $width = empty($instance['width']) ? '' : esc_attr($instance['width']);
            $height = empty($instance['height']) ? '' : esc_attr($instance['height']);
            $street_address = empty($instance['street-address']) ? '' : esc_attr($instance['street-address']);
            $city = empty($instance['city']) ? '' : esc_attr($instance['city']);
            $state = empty($instance['state']) ? '' : esc_attr($instance['state']);
            $postcode = empty($instance['postcode']) ? '' : esc_attr($instance['postcode']);
            $country = empty($instance['country']) ? '' : esc_attr($instance['country']);
            $icon = empty($instance['icon']) ? '' : esc_attr($instance['icon']);
			?>
				<p>
				<label for="<?php print $this->get_field_id('title'); ?>"><?php _e('Title:','avia_framework'); ?></label>
				<input class="widefat" id="<?php print $this->get_field_id('title'); ?>" name="<?php print $this->get_field_name('title'); ?>" type="text" value="<?php print $title; ?>" />
				</p>
				<p>
				<?php _e('Enter the latitude and longitude of the location you want to display. Need help finding the latitude and longitude?', 'avia_framework'); ?> <a href="#" class="avia-coordinates-help-link button"><?php _e('Click here to enter an address.','avia_framework'); ?></a>
                </p>
                <div class="avia-find-coordinates-wrapper">
                    <p>
                        <label for="<?php print $this->get_field_id('street-address'); ?>"><?php _e('Street Address:','avia_framework'); ?></label>
                        <input class='widefat avia-map-street-address' id="<?php print $this->get_field_id('street-address'); ?>" name="<?php print $this->get_field_name('street-address'); ?>" type="text" value="<?php print $street_address; ?>" />
                    </p>
                    <p>
                        <label for="<?php print $this->get_field_id('city'); ?>"><?php _e('City:','avia_framework'); ?></label>
                        <input class='widefat avia-map-city' id="<?php print $this->get_field_id('city'); ?>" name="<?php print $this->get_field_name('city'); ?>" type="text" value="<?php print $city; ?>" />
                    </p>
                    <p>
                        <label for="<?php print $this->get_field_id('state'); ?>"><?php _e('State:','avia_framework'); ?></label>
                        <input class='widefat avia-map-state' id="<?php print $this->get_field_id('state'); ?>" name="<?php print $this->get_field_name('state'); ?>" type="text" value="<?php print $state; ?>" />
                    </p>
                    <p>
                        <label for="<?php print $this->get_field_id('postcode'); ?>"><?php _e('Postcode:','avia_framework'); ?></label>
                        <input class='widefat avia-map-postcode' id="<?php print $this->get_field_id('postcode'); ?>" name="<?php print $this->get_field_name('postcode'); ?>" type="text" value="<?php print $postcode; ?>" />
                    </p>
                    <p>
                        <label for="<?php print $this->get_field_id('country'); ?>"><?php _e('Country:','avia_framework'); ?></label>
                        <input class='widefat avia-map-country' id="<?php print $this->get_field_id('country'); ?>" name="<?php print $this->get_field_name('country'); ?>" type="text" value="<?php print $country; ?>" />
                    </p>
                    <p>
                        <a class="button avia-populate-coordinates"><?php _e('Fetch coordinates!','avia_framework'); ?></a>
                        <div class='avia-loading-coordinates'><?php _e('Fetching the coordinates. Please wait...','avia_framework'); ?></div>
                    </p>
                </div>
                <div class="avia-coordinates-wrapper">
                <p>
                    <label for="<?php print $this->get_field_id('lat'); ?>"><?php _e('Latitude:','avia_framework'); ?></label>
                    <input class='widefat avia-map-lat' id="<?php print $this->get_field_id('lat'); ?>" name="<?php print $this->get_field_name('lat'); ?>" type="text" value="<?php print $lat; ?>" />
                </p>
				<p>
				<label for="<?php print $this->get_field_id('lng'); ?>"><?php _e('Longitude:','avia_framework'); ?></label>
				<input class='widefat avia-map-lng' id="<?php print $this->get_field_id('lng'); ?>" name="<?php print $this->get_field_name('lng'); ?>" type="text" value="<?php print $lng; ?>" />
				</p>
                </div>
        		<p>
				<label for="<?php print $this->get_field_id('zoom'); ?>"><?php echo __('Zoom Level:','avia_framework').' <small>'.__('(1-19)','avia_framework').'</small>'; ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id('zoom'); ?>" name="<?php echo $this->get_field_name('zoom'); ?>">
					<?php
					$list = "";
					$answers = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19);
					foreach ($answers as $answer)
					{
						$selected = "";
						if($answer == $zoom) $selected = 'selected="selected"';

						$list .= "<option $selected value='$answer'>$answer</option>";
					}
					$list .= "</select>";
					echo $list;
					?>


				</p>

				<p>
				<label for="<?php print $this->get_field_id('type'); ?>"><?php _e('Map Type:','avia_framework'); ?></label>

				<select class="widefat" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
					<?php
					$list = "";
					$answers = array('ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN');
					foreach ($answers as $answer)
					{
						$selected = "";
						if($answer == $type) $selected = 'selected="selected"';

						$list .= "<option $selected value='$answer'>$answer</option>";
					}
					$list .= "</select>";
					echo $list;
					?>

				</p>
				<p>
				<label for="<?php print $this->get_field_id('directionsto'); ?>"><?php _e('Display a Route by entering a address here. (If address is added Zoom will be ignored)','avia_framework'); ?></label>
				<input class="widefat" id="<?php print $this->get_field_id('directionsto'); ?>" name="<?php print $this->get_field_name('directionsto'); ?>" type="text" value="<?php print $directionsto; ?>" />
				</p>
				<p>
				<label for="<?php print $this->get_field_id('content'); ?>"><?php _e('Info Bubble Content:','avia_framework'); ?></label>
				<textarea rows="7" class="widefat" id="<?php print $this->get_field_id('content'); ?>" name="<?php print $this->get_field_name('content'); ?>"><?php print $content; ?></textarea>
				</p>
                <p>
                <label for="<?php print $this->get_field_id('icon'); ?>"><?php _e('Custom Marker Image URL:','avia_framework'); ?></label>
                <input class="widefat" class="avia-marker-icon" id="<?php print $this->get_field_id('icon'); ?>" name="<?php print $this->get_field_name('icon'); ?>" type="text" value="<?php print $icon; ?>" />
                </p>
                <p>
                <label for="<?php print $this->get_field_id('width'); ?>"><?php _e('Enter the width in px or &percnt; (100&percnt; width will be used if you leave this field empty)','avia_framework'); ?></label>
                <input class="widefat" id="<?php print $this->get_field_id('width'); ?>" name="<?php print $this->get_field_name('width'); ?>" type="text" value="<?php print $width; ?>" />
                </p>
                <p>
                <label for="<?php print $this->get_field_id('height'); ?>"><?php _e('Enter the height in px or &percnt;','avia_framework'); ?></label>
                <input class="widefat" id="<?php print $this->get_field_id('height'); ?>" name="<?php print $this->get_field_name('height'); ?>" type="text" value="<?php print $height; ?>" />
                </p>
			<?php
		}

        function helper_print_google_maps_scripts()
        {
            
			$api_key = avia_get_option('gmap_api');
			$api_url = av_google_maps::api_url( $api_key );
            
            wp_register_script( 'avia-google-maps-api', $api_url, array('jquery'), NULL, true);
            
            $load_google_map_api = apply_filters('avf_load_google_map_api', true, 'avia_google_map_widget');
            
            if($load_google_map_api) wp_enqueue_script( 'avia-google-maps-api' );

            $is_widget_edit_page = in_array(basename($_SERVER['PHP_SELF']), array('widgets.php'));
            if($is_widget_edit_page)
            {
	            wp_register_script( 'avia-google-maps-widget', AVIA_JS_URL.'conditional_load/avia_google_maps_widget.js', array( 'jquery','media-upload','media-views' ), '1.0.0', true);
	            wp_enqueue_script( 'avia-google-maps-widget' );

	            $args = array(
	                'toomanyrequests'	=> __("Too many requests at once, please refresh the page to complete geocoding",'avia_framework'),
	                'latitude'			=> __("Latitude and longitude for",'avia_framework'),
	                'notfound'			=> __("couldn't be found by Google, please add them manually",'avia_framework'),
	                'insertaddress' 	=> __("Please insert a valid address in the fields above",'avia_framework')
	            );

	            if($load_google_map_api) wp_localize_script( 'avia-google-maps-api', 'AviaMapTranslation', $args );
            }
        }


	} // SGMwidget widget
}


if(!function_exists('avia_printmap'))
{
	function avia_printmap($lat, $lng, $zoom, $type, $content, $directionsto, $width, $height, $icon) {

		global $avia_config;

		$SGMoptions = get_option('SGMoptions'); // get options defined in admin page

		if (!$lat) {$lat = '0';}
		if (!$lng) {$lng = '0';}
		if (!$zoom) {$zoom = $SGMoptions['zoom'];} // 1-19
		if (!$type) {$type = $SGMoptions['type'];} // ROADMAP, SATELLITE, HYBRID, TERRAIN
		if (!$content) {$content = $SGMoptions['content'];}
		$output = "";
		$unique = uniqid();
		
		$prefix  = isset($_SERVER['HTTPS'] ) ? "https" : "http";
        $width = !empty($width) ? 'width:'.$width.';' : 'width:100%;';
        $height = !empty($height) ? 'height:'.$height.';' : '';
        $icon = !empty($icon) ? $icon : '';
		
		$content = htmlspecialchars($content, ENT_QUOTES);
		$content = str_replace('&lt;', '<', $content);
		$content = str_replace('&gt;', '>', $content);
		$content = str_replace('&quot;', '"', $content);
		$content = str_replace('&#039;', '"', $content);
		$content = json_encode($content);

		
		$directionsForm = "";
		if(empty($avia_config['g_maps_widget_active']))
		{
			$avia_config['g_maps_widget_active'] = 0;
		}


		if(apply_filters('avia_google_maps_widget_load_api', true, $avia_config['g_maps_widget_active']))
        {	
			$api_key = avia_get_option('gmap_api');
			$api_url = av_google_maps::api_url( $api_key );
	  
            wp_register_script( 'avia-google-maps-api', $api_url, array('jquery'), NULL, true);
            wp_enqueue_script( 'avia-google-maps-api' );
        }

		$avia_config['g_maps_widget_active'] ++;

	$output .= "<script type='text/javascript'>
		function makeMap_".$avia_config['g_maps_widget_active']."() {\n";

	$avia_maps_config = "
		var directionsDisplay;
		directionsDisplay = new google.maps.DirectionsRenderer;
		var directionsService = new google.maps.DirectionsService;
		var map;
		var latlng = new google.maps.LatLng(".$lat.", ".$lng.");
		var directionsto = '".$directionsto."';
		var myOptions = {
		  zoom:".$zoom.",
		  mapTypeControl:true,
		  mapTypeId:google.maps.MapTypeId.".$type.",
		  mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		  navigationControl:true,
		  navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL},
		  center:latlng
		};
		var map = new google.maps.Map(document.getElementById('avia_google_maps_$unique'), myOptions);

		if(directionsto.length > 5)
		{
		  directionsDisplay.setMap(map);
		  var request = {
		     origin:directionsto,
		     destination:latlng,
		     travelMode:google.maps.DirectionsTravelMode.DRIVING
		};
		  directionsService.route(request, function(response, status) {
		     if(status == google.maps.DirectionsStatus.OK) {
		        directionsDisplay.setDirections(response)
		     }
		  })
		}
		else
		{
		  var contentString = ".$content.";
		  var infowindow = new google.maps.InfoWindow({
		     content: contentString
		  });
		  var marker = new google.maps.Marker({
		     position: latlng,
		     map: map,
		     icon: '".$icon."',
		     title: ''
		  });

		  google.maps.event.addListener(marker, 'click', function() {
			  infowindow.open(map,marker);
		  });
		}";

	$output .= apply_filters('avia_google_maps_widget_config', $avia_maps_config, $lat, $lng, $directionsto, $zoom, $type, $unique, $content, $icon);

	$output .= "\n}\n\n
			jQuery(document).ready(function() {
		   		makeMap_".$avia_config['g_maps_widget_active']."()
			});
		</script>
	   	<div id='avia_google_maps_$unique' style='$height $width' class='avia_google_maps_container'></div>";

	   return $output;
	}
}





/*
Modified version of instagram widget by
Author: Scott Evans
Copyright © 2013 Scott Evans

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

class avia_instagram_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'avia-instagram-feed',
			THEMENAME ." ". __( 'Instagram', 'avia_framework' ),
			array( 'classname' => 'avia-instagram-feed', 'description' => __( 'Displays your latest Instagram photos', 'avia_framework' ) )
		);
	}
	
	function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$username = empty( $instance['username'] ) ? '' : $instance['username'];
		$limit = empty( $instance['number'] ) ? 9 : $instance['number'];
		$columns = empty( $instance['columns'] ) ? 3 : $instance['columns'];
		$size = empty( $instance['size'] ) ? 'large' : $instance['size'];
		$target = empty( $instance['target'] ) ? '_self' : $instance['target'];
		$link = empty( $instance['link'] ) ? '' : $instance['link'];

		echo $before_widget;
		if ( ! empty( $title ) ) { echo $before_title . $title . $after_title; };

		do_action( 'aviw_before_widget', $instance );

		if ( $username != '' ) {

			$media_array = $this->scrape_instagram( $username, $limit );

			if ( is_wp_error( $media_array ) ) {

				echo $media_array->get_error_message();

			} else {

				// filter for images only?
				if ( $images_only = apply_filters( 'aviw_images_only', FALSE ) )
					$media_array = array_filter( $media_array, array( $this, 'images_only' ) );

				// filters for custom classes
				$ulclass = esc_attr( apply_filters( 'aviw_list_class', 'av-instagram-pics av-instagram-size-' . $size ) );
				$rowclass = esc_attr( apply_filters( 'aviw_row_class', 'av-instagram-row' ) );
				$liclass = esc_attr( apply_filters( 'aviw_item_class', 'av-instagram-item' ) );
				$aclass = esc_attr( apply_filters( 'aviw_a_class', '' ) );
				$imgclass = esc_attr( apply_filters( 'aviw_img_class', '' ) );

				?><div class="<?php echo esc_attr( $ulclass ); ?>"><?php
				
				$last_id  = end($media_array);
				$last_id  = $last_id['id'];
				
				$rowcount = 0;	
				foreach ( $media_array as $item ) 
				{
					if($rowcount == 0)
					{
						echo "<div class='{$rowclass}'>";
					}
					
					$rowcount ++ ;
					$targeting = $target;
					if($target == "lightbox")
					{
						$targeting = "";
						$item['link'] = $item['original'];
					}
	
					echo '<div class="'. $liclass .'">';
					echo '<a href="'. esc_url( $item['link'] ) .'" target="'. esc_attr( $targeting ) .'"  class="'. $aclass .'">';
					echo '<img src="'. esc_url( $item[$size] ) .'"  alt="'. esc_attr( $item['description'] ) .'" title="'. esc_attr( $item['description'] ).'"  class="'. $imgclass .'"/>';
					echo '</a></div>';
					
					if($rowcount % $columns == 0 || $last_id == $item['id'])
					{
						echo '</div>';
						$rowcount = 0;
					}
					
				}
				echo '</div>';
			}
		}

		if ( $link != '' ) {
			?>
			<a class="av-instagram-follow avia-button" href="//instagram.com/<?php echo esc_attr( trim( $username ) ); ?>" rel="me" target="<?php echo esc_attr( $target ); ?>"><?php echo $link; ?></a><?php
		}

		do_action( 'aviw_after_widget', $instance );

		echo $after_widget;
	}
	

	
	function form( $instance ) 
	{
			
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => __( 'Instagram', 'avia_framework' ), 
			'username' => '', 
			'size' => 'large', 
			'link' => __( 'Follow Me!', 'avia_framework' ), 
			'number' => 9, 
			'target' => 'lightbox' , 
			'columns' => 3 ) 
		);
		
		$title = esc_attr( $instance['title'] );
		$username = esc_attr( $instance['username'] );
		$number = absint( $instance['number'] );
		if($number > 12) $number = 12;
		$size = esc_attr( $instance['size'] );
		$target = esc_attr( $instance['target'] );
		$link = esc_attr( $instance['link'] );
		$columns = esc_attr( $instance['columns'] );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'avia_framework' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Username', 'avia_framework' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo $username; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of photos (maximum 12)', 'avia_framework' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'columns' ); ?>"><?php _e( 'Number of columns', 'avia_framework' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>" type="text" value="<?php echo $columns; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Photo size', 'avia_framework' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" class="widefat">
				<option value="thumbnail" <?php selected( 'thumbnail', $size ) ?>><?php _e( 'Thumbnail', 'avia_framework' ); ?></option>
				<option value="small" <?php selected( 'small', $size ) ?>><?php _e( 'Small', 'avia_framework' ); ?></option>
				<option value="large" <?php selected( 'large', $size ) ?>><?php _e( 'Large', 'avia_framework' ); ?></option>
				<option value="original" <?php selected( 'original', $size ) ?>><?php _e( 'Original', 'avia_framework' ); ?></option>
			</select>
		</p>
		<p><label for="<?php echo $this->get_field_id( 'target' ); ?>"><?php _e( 'Open links in', 'avia_framework' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'target' ); ?>" name="<?php echo $this->get_field_name( 'target' ); ?>" class="widefat">
				<option value="lightbox" <?php selected( 'lightbox', $target ) ?>><?php _e( 'Lightbox', 'avia_framework' ); ?></option>
				<option value="_self" <?php selected( '_self', $target ) ?>><?php _e( 'Current window (_self)', 'avia_framework' ); ?></option>
				<option value="_blank" <?php selected( '_blank', $target ) ?>><?php _e( 'New window (_blank)', 'avia_framework' ); ?></option>
			</select>
		</p>
		<p><label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link text', 'avia_framework' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo $link; ?>" /></label></p>
		<?php
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['username'] = trim( strip_tags( $new_instance['username'] ) );
		$instance['number'] = ! absint( $new_instance['number'] ) ? 9 : $new_instance['number'];
		$instance['columns'] = ! absint( $new_instance['columns'] ) ? 3 : $new_instance['columns'];
		
		if($instance['columns'] > 6) $instance['columns'] = 6;
		if($instance['columns'] < 1) $instance['columns'] = 1;
		
		
		$instance['size'] = ( ( $new_instance['size'] == 'thumbnail' || $new_instance['size'] == 'large' || $new_instance['size'] == 'small' || $new_instance['size'] == 'original' ) ? $new_instance['size'] : 'large' );
		$instance['target'] = ( ( $new_instance['target'] == '_self' || $new_instance['target'] == '_blank'|| $new_instance['target'] == 'lightbox' ) ? $new_instance['target'] : '_self' );
		$instance['link'] = strip_tags( $new_instance['link'] );
		return $instance;
	}
	
		// based on https://gist.github.com/cosmocatalano/4544576
	function scrape_instagram( $username, $slice = 9 ) {

		$username = strtolower( $username );
		$username = str_replace( '@', '', $username );

		if ( false === ( $instagram = get_transient( 'av_insta1-'.sanitize_title_with_dashes( $username ) ) ) ) {

			//$remote = wp_remote_get( 'http://instagram.com/'.trim( $username ) );
			$remote = wp_remote_get( 'https://www.instagram.com/'.trim( $username ), array( 'sslverify' => false, 'timeout' => 60 ) );
			
			if ( is_wp_error( $remote ) )
				return new WP_Error( 'site_down', __( 'Unable to communicate with Instagram.', 'avia_framework' ) );

			if ( 200 != wp_remote_retrieve_response_code( $remote ) )
				return new WP_Error( 'invalid_response', __( 'Instagram did not return a 200.', 'avia_framework' ) );

			$shards = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], TRUE );

			if ( ! $insta_array )
				return new WP_Error( 'bad_json', __( 'Instagram has returned invalid data.', 'avia_framework' ) );

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'];
			} else {
				return new WP_Error( 'bad_json_2', __( 'Instagram has returned invalid data.', 'avia_framework' ) );
			}

			if ( ! is_array( $images ) )
				return new WP_Error( 'bad_array', __( 'Instagram has returned invalid data.', 'avia_framework' ) );

			$instagram = array();

			foreach ( $images as $image ) {

				// see https://github.com/stevenschobert/instafeed.js/issues/549
				$image['thumbnail_src'] = preg_replace( "/^https:/i", "", $image['thumbnail_src'] );
				$image['thumbnail'] = $image['thumbnail_src'];
				$image['small'] = $image['thumbnail_src'];
				$image['large'] = $image['thumbnail_src'];
				$image['display_src'] = preg_replace( "/^https:/i", "", $image['display_src'] );

				if ( $image['is_video'] == true ) {
					$type = 'video';
				} else {
					$type = 'image';
				}

				$caption = __( 'Instagram Image', 'avia_framework' );
				if ( ! empty( $image['caption'] ) ) {
					$caption = $image['caption'];
				}

				$instagram[] = array(
					'description'   => $caption,
					'link'		  	=> '//instagram.com/p/' . $image['code'],
					'time'		  	=> $image['date'],
					'comments'	  	=> $image['comments']['count'],
					'likes'		 	=> $image['likes']['count'],
					'thumbnail'	 	=> $image['thumbnail'],
					'small'			=> $image['small'],
					'large'			=> $image['large'],
					'original'		=> $image['display_src'],
					'type'		  	=> $type,
					'id'			=> $image['id']
				);
			}

			// do not set an empty transient - should help catch private or empty accounts
			if ( ! empty( $instagram ) ) {
				$instagram = base64_encode( serialize( $instagram ) );
				set_transient( 'av_insta1-'.sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS*2 ) );
			}
		}

		if ( ! empty( $instagram ) ) {

			$instagram = unserialize( base64_decode( $instagram ) );
			return array_slice( $instagram, 0, $slice );

		} else {

			return new WP_Error( 'no_images', __( 'Instagram did not return any images.', 'avia_framework' ) );

		}
	}

	function images_only( $media_item ) {

		if ( $media_item['type'] == 'image' )
			return true;

		return false;
	}
}
