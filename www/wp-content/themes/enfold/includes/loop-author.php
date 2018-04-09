<?php
global $avia_config, $post_loop_count;


if(empty($post_loop_count)) $post_loop_count = 1;
$blog_style = avia_get_option('blog_style','multi-big');
$blog_global_style = avia_get_option('blog_global_style',''); //alt: elegant-blog

// check if we got posts to display:
if (have_posts()) :

	while (have_posts()) : the_post();

	/*
     * get the current post id, the current post class and current post format
 	 */

	$current_post = array();
	$current_post['post_loop_count'] = $post_loop_count;
	$current_post['the_id']	   	 = get_the_ID();
	$current_post['parity']	   	 = $post_loop_count % 2 ? 'odd' : 'even';
	$current_post['last']      	 = count($wp_query->posts) == $post_loop_count ? " post-entry-last " : "";
	$current_post['post_class'] 	 = "post-entry-".$current_post['the_id']." post-loop-".$post_loop_count." post-parity-".$current_post['parity'].$current_post['last']." ".$blog_style;
	$current_post['post_format'] 	 = get_post_format() ? get_post_format() : 'standard';
	$current_post['post_layout']	 = avia_layout_class('main', false);

	/*
     * retrieve slider, title and content for this post,...
     */
    $size = strpos($blog_style, 'big') ? (strpos($current_post['post_layout'], 'sidebar') !== false) ? 'entry_with_sidebar' : 'entry_without_sidebar' : 'square';
	$current_post['slider']  	= get_the_post_thumbnail($current_post['the_id'], $size);
	$current_post['title']   	= get_the_title();
	$current_post['content'] 	= apply_filters('avf_loop_author_content', get_the_excerpt());
	$with_slider    = empty($current_post['slider']) ? "" : "with-slider";


	/*
     * ...now apply a filter, based on the post type... (filter function is located in includes/helper-post-format.php)
     */
	$current_post	= apply_filters( 'post-format-'.$current_post['post_format'], $current_post );

	/*
     * ... last apply the default wordpress filters to the content
     */
	$current_post['content'] = str_replace(']]>', ']]&gt;', apply_filters('the_content', $current_post['content'] ));

	/*
	 * Now extract the variables so that $current_post['slider'] becomes $slider, $current_post['title'] becomes $title, etc
	 */
	extract($current_post);








	/*
	 * render the html:
	 */
	?>

		<article <?php post_class('post-entry post-entry-type-'.$post_format . " " . $post_class . " ".$with_slider); ?>' <?php avia_markup_helper(array('context' => 'entry')); ?>>


			<div class="entry-content-wrapper clearfix <?php echo $post_format; ?>-content">
                <header class="entry-content-header">
                    <?php
					
					$content_output  =  '<div class="entry-content" '.avia_markup_helper(array('context' => 'entry_content','echo'=>false)).'>';
					$content_output .=  wpautop($content);
					$content_output .=  '</div>';
	            	
	            	
	            	$taxonomies  = get_object_taxonomies(get_post_type($the_id));
	                $cats = '';
	                					
					$excluded_taxonomies = array_merge( get_taxonomies( array( 'public' => false ) ), array('post_tag','post_format') );
					$excluded_taxonomies = apply_filters('avf_exclude_taxonomies', $excluded_taxonomies, get_post_type($the_id), $the_id);
					
	                if(!empty($taxonomies))
	                {
	                    foreach($taxonomies as $taxonomy)
	                    {
	                        if(!in_array($taxonomy, $excluded_taxonomies))
	                        {
	                            $cats .= get_the_term_list($the_id, $taxonomy, '', ', ','').' ';
	                        }
	                    }
	                }
					//elegant blog
	            	if( strpos($blog_global_style, 'elegant-blog') !== false )
	            	{
		            	if(!empty($cats))
	                    {
	                        echo '<span class="blog-categories minor-meta">';
	                        echo $cats;
	                        echo '</span>';
	                        $cats = "";
	                    }
	            
						echo $title;
						
						echo '<span class="av-vertical-delimiter"></span>';
						
						
						echo $content_output;
						
						$cats = "";
						$title = "";
						$content_output = "";
					}
					
                    //echo the post title
                    echo $title;
                    
                    ?>
                    <span class='post-meta-infos'>
                        <span class='date-container minor-meta updated'><?php the_time(get_option('date_format')); ?></span>

                        <?php if ( get_comments_number() != "0" || comments_open() ){
                        echo "<span class='text-sep'>/</span>";
                        echo "<span class='comment-container minor-meta'>";
                        comments_popup_link(  "0 ".__('Comments','avia_framework'),
                                              "1 ".__('Comment' ,'avia_framework'),
                                              "% ".__('Comments','avia_framework'),'comments-link',
                                              "".__('Comments Disabled','avia_framework'));
                        echo "</span>";
						echo "<span class='text-sep text-sep-comment'>/</span>";
                        }


                        if(!empty($cats))
                        {
                            echo '<span class="blog-categories minor-meta">'.__('in','avia_framework')." ";
                            echo $cats;
                            echo '</span><span class="text-sep text-sep-cat">/</span>';
                        }
                        
			echo '<span class="blog-author minor-meta">'.__('by','avia_framework')." ";
			echo '<span class="entry-author-link" '.avia_markup_helper(array('context' => 'author_name','echo'=>false)).'>';
			echo '<span class="vcard author"><span class="fn">';
				the_author_posts_link();
			echo '</span></span>';
			echo '</span>';
			echo '</span>';

                        ?>

                    </span>
                </header>

				<?php
				// echo the post content
				echo $content_output;

				?>
			</div>

            <footer class="entry-footer"></footer>
            
            <?php do_action('ava_after_content', $the_id, 'loop-author'); ?>
            
		</article><!--end post-entry-->
	<?php

	$post_loop_count++;
	endwhile;
	else:

?>

        <article class="entry">
            <header class="entry-content-header">
                <h1 class='post-title entry-title'><?php _e('Nothing Found', 'avia_framework'); ?></h1>
            </header>

            <p class="entry-content" <?php avia_markup_helper(array('context' => 'entry_content')); ?>><?php _e('Sorry, no posts matched your criteria', 'avia_framework'); ?></p>

            <footer class="entry-footer"></footer>
        </article>

<?php

	endif;

	if(!isset($avia_config['remove_pagination'] ))
	{
		echo avia_pagination('', 'nav');
		// paginate_links(); posts_nav_link(); next_posts_link(); previous_posts_link();
	}
?>
