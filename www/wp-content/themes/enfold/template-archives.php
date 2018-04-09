<?php
	/*
	Template Name: Archives
	*/

	if ( !defined('ABSPATH') ){ die(); }
	/*
	 * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
	 */


	 global $avia_config, $more;
	 get_header();
	 echo avia_title();
	 
	 do_action( 'ava_after_main_title' );
	 ?>



		<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>

			<div class='container'>

				<main class='template-archives content <?php avia_layout_class( 'content' ); ?> units' <?php avia_markup_helper(array('context' => 'content'));?>>

                    <div class="entry-content-wrapper entry-content clearfix">

                    <?php
                    //display the actual post content
                    the_post();
                    the_content();


                    /*
                    * Display the latest 20 blog posts
                    */


                    query_posts(array('posts_per_page'=>20));

                    // check if we got posts to display:
                    

                    echo '<div class="tabcontainer top_tab">'."\n";
                    echo '<div data-fake-id="#tab-id-1" class="tab active_tab">'.__('Blog Posts','avia_framework').'</div>'."\n";
                    
                   
                    echo '<div class="tab_content active_tab_content" >'."\n";
                    echo '<div class="tab_inner_content">'."\n";

					 if (have_posts()) :
                    echo "<h3>" . __('The 20 latest Blog Posts','avia_framework') . "</h3>";
                    echo "<ul>";
                        while (have_posts()) : the_post();

                        echo "<li><a href='".get_permalink()."' rel='bookmark' title='". __('Permanent Link:','avia_framework')." ".the_title_attribute('echo=0')."'>".get_the_title()."</a></li>";

                        endwhile;
                    echo "</ul>";
                    else:
                    
                      echo "<h3>" . __('No Blog Posts found','avia_framework') . "</h3>";
                    
                    endif;

                    echo '</div>'."\n";
                    echo '</div>'."\n";


                    /*
                    * Display the latest 20 portfolio posts
                    */


                    query_posts(array('posts_per_page'=>8, 'post_type'=>'portfolio'));

                    // check if we got posts to display:
                    if (have_posts()) :



                    $columns = 4;
                    $rel_class = "av_one_fourth ";
                    $slidecount = 0;
                    $postcount = ($columns * 1);
                    $count = 1;
                    $output = "";
                    $first = "first";

                    $output .= "<div class ='latest-portfolio-archive'>";
                    $output .= "<h3>" . __('The 8 latest Portfolio Entries','avia_framework') . "</h3>";
                    $output .= "<div class=' autoslide_false'>";

                    while (have_posts()) : the_post();


                        $slidecount ++;

                        if($count == 1)
                        {
                            $output .= "<div class='single_slide single_slide_nr_$slidecount'>";
                        }

                        $image = get_the_post_thumbnail( get_the_ID(), 'portfolio_small' );

                        if(empty($image))
                        $image = "<span class='related_posts_default_image'></span>";



                        $output .= "<div class='relThumb relThumb flex_column {$count} {$first} {$rel_class}'>\n";
                        $output .= "<a href='".get_permalink()."' class='relThumWrap noLightbox'>\n";
                        $output .= "<span class='related_image_wrap'>";
                        $output .= $image;
                        $output .= "</span>\n";
                        $output .= "<span class='relThumbTitle'>\n";
                        $output .= "<strong class='relThumbHeading'>".avia_backend_truncate(get_the_title(), 50)."</strong>\n";
                        $output .= "</span>\n</a>";
                        $output .= "</div><!-- end .relThumb -->\n";

                        $count++;
                        $first = "";
                        if($count == $columns+1)
                        {
                            $first = "first";
                            $output .= "</div>";
                            $count = 1;
                        }

                    endwhile;

                    if($count != 1) $output .= "</div>";

                    $output .= "</div></div>";


                    echo '<div data-fake-id="#tab-id-2" class="tab">'.__('Portfolio','avia_framework').'</div>'."\n";
                    echo '<div class="tab_content" >'."\n";
                    echo '<div class="tab_inner_content">'."\n";

                    echo $output;


                    echo '</div>'."\n";
                    echo '</div>'."\n";

                    endif;




                    /*
                    * Display pages, categories and month archives
                    */

                    echo '<div data-fake-id="#tab-id-3" class="tab">'.__('Pages','avia_framework').'</div>'."\n";
                    echo '<div class="tab_content" >'."\n";
                    echo '<div class="tab_inner_content">'."\n";

                    echo "<div class='one_third first archive_list'>";
                    echo "<h3>" . __('Available Pages','avia_framework') . "</h3>";
                    echo "<ul>";
                    wp_list_pages('title_li=&depth=-1' );
                    echo "</ul>";
                    echo "</div>";

                    echo '</div>'."\n";
                    echo '</div>'."\n";

                    echo '<div data-fake-id="#tab-id-4" class="tab">'.__('Categories','avia_framework').'</div>'."\n";
                    echo '<div class="tab_content" >'."\n";
                    echo '<div class="tab_inner_content">'."\n";

                    echo "<div class='one_third archive_list'>";
                    echo "<h3>" . __('Archives by Subject:','avia_framework') . "</h3>";
                    echo "<ul>";
                    wp_list_categories('sort_column=name&optioncount=0&hierarchical=0&title_li=');
                    echo "</ul>";
                    echo "</div>";

                    echo '</div>'."\n";
                    echo '</div>'."\n";


                    echo '<div data-fake-id="#tab-id-5" class="tab">'.__('Monthly','avia_framework').'</div>'."\n";
                    echo '<div class="tab_content" >'."\n";
                    echo '<div class="tab_inner_content">'."\n";

                    echo "<div class='one_third archive_list'>";
                    echo "<h3>" . __('Archives by Month:','avia_framework') . "</h3>";
                    echo "<ul>";
                    wp_get_archives('type=monthly');
                    echo "</ul>";
                    echo "</div>";

                    echo '</div>'."\n";
                    echo '</div>'."\n";


                    echo '</div>'."\n" //tabcontainer close;


                     ?>


                    </div>

				<!--end content-->
				</main>

				<?php
				wp_reset_query();
				//get the sidebar
				$avia_config['currently_viewing'] = 'page';
				get_sidebar();

				?>

			</div><!--end container-->

		</div><!-- close default .container_wrap element -->




<?php get_footer(); ?>