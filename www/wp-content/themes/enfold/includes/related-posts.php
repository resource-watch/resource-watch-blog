<?php

/**
 *  These functions shows a number of posts related to the currently displayed post.
 *  Relations are defined by tags: if post tags match, the post will be displayed as related
 */
global $avia_config;


$rp = avia_get_option('single_post_related_entries');




if(!isset($avia_config['related_posts_config']))
{
	$avia_config['related_posts_config'] = array(
	
	'columns' => 8,
	'post_class' =>  "av_one_eighth no_margin ",
	'image_size' => 'square',
	'tooltip'	 => true,
	'title_short'=> false
	
	);
	
	if($rp == "av-related-style-full")
	{
		$avia_config['related_posts_config'] = array(
	
		'columns' => 6,
		'post_class' =>  "av_one_half no_margin ",
		'image_size' => 'square',
		'tooltip'	 => false,	
		'title_short'=> true	
		);
	}
}

if($rp == "disabled") return;







extract($avia_config['related_posts_config']);


$is_portfolio 		= false; //avia_is_portfolio_single();
$related_posts 		= false;
$this_id 			= $post->ID;
$slidecount 		= 0;
$postcount 			= ($columns * 1);
$format 			= "";
$fake_image			= "";
$tags               = wp_get_post_tags($this_id);


if (!empty($tags) && is_array($tags))
{
     $tag_ids = array();
     foreach ($tags as $tag ) {
	     
	     if($tag->slug != "portrait" && $tag->slug != "landscape")
	     {
	     	$tag_ids[] = (int)$tag->term_id;
	     }
	 }

     if(!empty($tag_ids))
     {


        $my_query = get_posts(
                            array(
                                'tag__in' => $tag_ids,
                                'post_type' => get_post_type($this_id),
                                'showposts'=>$postcount, 'ignore_sticky_posts'=>1,
                                'orderby'=>'rand',
                                'post__not_in' => array($this_id))
                            );


  		if (!empty($my_query))
  		{
  			$extra = 'alpha';
  			$count = 1;
  			$output = "";

  			//create seperator

     		$output .= "<div class ='related_posts {$rp}'>";


     		$output .= "<h5 class='related_title'>".__('You might also like', 'avia_framework')."</h5>";
     		$output .= "<div class='related_entries_container '>";

     		foreach ($my_query as $related_post)
     		{
     			$related_posts = true;
     			$slidecount ++;
     			$format = "";
     			if($is_portfolio) $format = "portfolio";
     			if(!$format) $format = get_post_format($related_post->ID);
     			if(!$format) $format = 'standard';
     			if(!empty($title_short)) $related_post->post_title = wp_trim_words($related_post->post_title, 17);
     			
     			

                $post_thumbnail_id = get_post_thumbnail_id($related_post->ID);
	 			$post_thumb = get_the_post_thumbnail($related_post->ID, $image_size, array('title' => esc_attr(get_the_title($post_thumbnail_id))));
     			$image 		= $post_thumb ? $post_thumb : "<span class='related_posts_default_image'>{image}</span>";
     			$fake_image = $post_thumb ? $post_thumb : $fake_image;
     			$extra_class= $post_thumb ? "" : "related-format-visible";
     			$parity		= $slidecount % 2 ? 'Odd' : 'Even';
				$insert_tooltip = $tooltip == true ? "data-avia-related-tooltip=\"". esc_attr($related_post->post_title)."\"" : "";
				
     			$output .= "<div class='$post_class $extra relThumb relThumb{$count} relThumb{$parity} post-format-{$format} related_column'>\n";
	 			$output .= "	<a href='".get_permalink($related_post->ID)."' class='relThumWrap noLightbox' title='".esc_attr($related_post->post_title)."'>\n";
     			$output .= "	<span class='related_image_wrap' {$insert_tooltip}>";
	 			$output .= 		$image;
	 			$output .= "	<span class='related-format-icon {$extra_class}'><span class='related-format-icon-inner' ".av_icon_string($format)."></span></span>";
	 			$output .= "	</span>";
	 			$output .= 		"<strong class='av-related-title'>".$related_post->post_title."</strong>";
	 			$output .= 		apply_filters('avf_related_post_loop', "", $related_post);
	 			$output .= "	</a>";
	 			$output .= "</div>";

     			$count++;
     			$extra = "";

     			if($count == count($my_query)) $extra = 'omega';

     		}


     		$output .= "</div></div>";
     		$output = str_replace("{image}",$fake_image,$output);

     		if($related_posts) echo $output;

     	}

     	wp_reset_query();
    }
}

