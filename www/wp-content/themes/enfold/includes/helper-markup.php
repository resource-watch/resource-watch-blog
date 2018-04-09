<?php
/*
 * Returns the schema.org markup based on the context value.
 * $args: context (string), echo (boolean) and post_type (string)
 */
if(!function_exists('avia_markup_helper'))
{
    function avia_markup_helper($args)
    {
        if(!empty($args))
        $args = array_merge(array('context' => '', 'echo' => true, 'post_type' => '', 'id' => '', 'custom_markup' => '', 'force' => false), $args);

		$args = apply_filters('avf_markup_helper_args', $args);
			
		// dont show markup if its deactivated. markup can still be enforced with args['force'] = true;
		if('inactive' == avia_get_option('markup') && $args['force'] == false) return;

        if(empty($args['context'])) return;

        // markup string - stores markup output
        $markup = ' ';
        $attributes = array();

        //try to fetch the right markup
        switch($args['context'])
        {
            case 'body':
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/WebPage';
                break;

            case 'header':
                $attributes['role']      = 'banner';
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/WPHeader';
                break;

            case 'title':
                $attributes['itemprop'] = 'headline';
                break;

            case 'avia_title':
                $attributes['itemprop'] = 'headline';
                break;

            case 'description':
                $attributes['itemprop'] = 'description';
                break;

            case 'nav':
                $attributes['role']      = 'navigation';
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/SiteNavigationElement';
                break;

            case 'content':
                $attributes['role']     = 'main';
                $attributes['itemprop'] = 'mainContentOfPage';
				
				if (is_singular('post'))
                {
                    unset($attributes['itemprop']);
                }
				
                //* Blog microdata
                if (is_singular('post') || is_archive() || is_home())
                {
                    $attributes['itemscope'] = 'itemscope';
                    $attributes['itemtype']  = 'https://schema.org/Blog';
                }

                if(is_archive() && $args['post_type'] == 'products')
                {
                    $attributes['itemtype']  = 'https://schema.org/SomeProducts';
                }

                //* Search results pages
                if (is_search())
                {
                    $attributes['itemscope'] = 'itemscope';
                    $attributes['itemtype'] = 'https://schema.org/SearchResultsPage';
                }
                break;

            case 'entry':
                global $post;
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/CreativeWork';

                //* Blog posts microdata
                if ( is_object($post) && 'post' === $post->post_type )
                {
                    $attributes['itemtype']  = 'https://schema.org/BlogPosting';

                    //* If main query,
                    if ( is_main_query() )
                        $attributes['itemprop']  = 'blogPost';
                }
                break;

            case 'phone':
                $attributes['itemprop']  = 'telephone';
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/LocalBusiness';
                break;

            case 'image':
		$attributes['itemprop']  = 'ImageObject';
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/ImageObject';
                break;

            case 'image_url':
                $attributes['itemprop']  = 'thumbnailUrl';
                break;

            case 'name':
                $attributes['itemprop'] = 'name';
                break;

            case 'email':
                $attributes['itemprop'] = 'email';
                break;

            case 'job':
                $attributes['itemprop'] = 'jobTitle';
                break;

            case 'url':
                $attributes['itemprop'] = 'url';
                break;

            case 'affiliation':
                $attributes['itemprop']  = 'affiliation';
                break;

            case 'author':
                $attributes['itemprop']  = 'author';
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/Person';
                break;

            case 'person':
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/Person';
                break;

            case 'single_image':
                $attributes['itemprop'] = 'image';
                break;

            case 'author_link':
                $attributes['itemprop'] = 'url';
                break;

            case 'author_name':
                $attributes['itemprop'] = 'author';
                break;

            case 'entry_time':
            
                $attributes['itemprop'] = 'datePublished';
                $attributes['datetime'] = get_the_time('c', $args['id']);
                break;

            case 'entry_title':
                $attributes['itemprop'] = 'headline';
                break;

            case 'entry_content':
                $attributes['itemprop'] = 'text';
                break;

            case 'comment':
                $attributes['itemprop']  = 'comment';
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/UserComments';
                break;

            case 'comment_author':
                $attributes['itemprop']  = 'creator';
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/Person';
                break;

            case 'comment_author_link':
                $attributes['itemprop']  = 'creator';
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/Person';
                $attributes['rel']  = 'external nofollow';
                break;

            case 'comment_time':
                $attributes['itemprop']  = 'commentTime';
                $attributes['itemscope'] = 'itemscope';
                $attributes['datetime'] = get_the_time('c');
                break;

            case 'comment_text':
                $attributes['itemprop']  = 'commentText';
                break;

            case 'author_box':
                $attributes['itemprop']  = 'author';
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/Person';
                break;

            case 'table':
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/Table';
                break;

            case 'video':
                $attributes['itemprop'] = 'video';
                $attributes['itemtype']  = 'https://schema.org/VideoObject';
                break;

            case 'audio':
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/AudioObject';
                break;

            case 'blog':
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/Blog';
                break;

            case 'sidebar':
                $attributes['role']      = 'complementary';
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/WPSideBar';
                break;

            case 'footer':
                $attributes['role']      = 'contentinfo';
                $attributes['itemscope'] = 'itemscope';
                $attributes['itemtype']  = 'https://schema.org/WPFooter';
                break;
                
           case 'blog_publisher':
                $attributes['itemprop']  = 'publisher';
                $attributes['itemtype']  = 'https://schema.org/Organization';
                $attributes['itemscope'] = 'itemscope';
                break;
			
			case 'blog_date_modified':
                $attributes['itemprop']  = 'dateModified';
                $attributes['itemtype']  = 'https://schema.org/dateModified';
                break;
			
			case 'blog_mainEntityOfPage':
                $attributes['itemprop']  = 'mainEntityOfPage';
                $attributes['itemtype']  = 'https://schema.org/mainEntityOfPage';
                break;
			
			
			
        }


        $attributes = apply_filters('avf_markup_helper_attributes', $attributes, $args);

        //we failed to fetch the attributes - let's stop
        if(empty($attributes)) return;

        foreach ($attributes as $key => $value)
        {
            $markup .= $key . '="' . $value . '" ';
        }

        $markup = apply_filters('avf_markup_helper_output', $markup, $args);

        if($args['echo'])
        {
            echo $markup;
        }
        else
        {
            return $markup;
        }
    }
}




if(!function_exists('av_blog_entry_markup_helper'))
{
	function av_blog_entry_markup_helper( $id , $exclude = array())
	{
		if('inactive' == avia_get_option('markup')) return;
		
		$logo = $logo_url = $logo_h = $logo_w = $url_string = $url_h = $url_w = "";
		$post = get_post($id);
		if($logo = avia_get_option('logo'))
		{
			 $logo = apply_filters('avf_logo', $logo);
			 if(is_numeric($logo)){ 
				 $logo = wp_get_attachment_image_src($logo, 'full'); 
				 $logo_url = $logo[0]; 
				}
				else
				{
					$logo_url = $logo;
				}
		} 
				
		$thumb_id = get_post_thumbnail_id($id);  
		
		if($thumb_id)
		{
			$url = wp_get_attachment_image_src($thumb_id, 'full'); 
			$url_string = $url[0];
			$url_w = $url[1];
			$url_h = $url[2];
			
		}
		else
		{
			if(is_array($logo)){			
				$url_string = $logo[0];
				$url_w = $logo[1];
				$url_h = $logo[2];
			}
			else
			{
				$url_string = $logo;
				$url_w = 0;
				$url_h = 0;
			}
		}
		
		
		$author_name 		= apply_filters('avf_author_name', get_the_author_meta('display_name', $post->post_author), $post->post_author);
		$publisher_markup 	= avia_markup_helper(array('context' => 'blog_publisher','echo'=>false));
		$author_markup 		= avia_markup_helper(array('context' => 'author','echo'=>false));
		$date_markup 		= avia_markup_helper(array('context' => 'blog_date_modified','echo'=>false));
		$entry_time_markup 	= avia_markup_helper(array('context' => 'entry_time','echo'=>false));
		$main_entity_markup = avia_markup_helper(array('context' => 'blog_mainEntityOfPage','echo'=>false));
		$image_markup 		= avia_markup_helper(array('context' => 'image','echo'=>false));		
		
		$output = "";
		
		if( !in_array('image', $exclude) )
		{
			$output .= "
			<span class='av-structured-data' {$image_markup} itemprop='image'>
					   <span itemprop='url' >{$url_string}</span>
					   <span itemprop='height' >{$url_h}</span>
					   <span itemprop='width' >{$url_w}</span>
				  </span>";
		}
		
		if( !in_array('publisher', $exclude) )
		{
			$output .= "<span class='av-structured-data' {$publisher_markup}>
				<span itemprop='name'>{$author_name}</span>
				<span itemprop='logo' itemscope itemtype='http://schema.org/ImageObject'>
				   <span itemprop='url'>{$logo_url}</span>
				 </span>
			  </span>";
		}
		
		if( !in_array('author', $exclude) )
		{	  
			$output .= "<span class='av-structured-data' {$author_markup}><span itemprop='name'>{$author_name}</span></span>";
		}
		if( !in_array('date', $exclude) )
		{
			$output .= "<span class='av-structured-data' {$entry_time_markup}>{$post->post_date}</span>";
		}
		
		if( !in_array('date_modified', $exclude) )
		{
			$output .= "<span class='av-structured-data' {$date_markup}>{$post->post_modified}</span>";
		}
		
		if( !in_array('mainEntityOfPage', $exclude) )
		{
			$output .= "<span class='av-structured-data' {$main_entity_markup}><span itemprop='name'>{$post->post_title}</span></span>";
		}
		
		if(!empty($output)) $output = "<span class='hidden'>{$output}</span>";
		
		return $output;
		
	}
}







