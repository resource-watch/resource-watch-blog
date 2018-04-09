<?php
	if ( !defined('ABSPATH') ){ die(); }
	
	global $avia_config;

	/*
	 * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
	 */
	 get_header();


 	 $title = "";
	if(!is_singular()) $title = __('Forums',"avia_framework");
	
	
	if(function_exists('bbp_is_single_user_edit') && (bbp_is_single_user_edit() || bbp_is_single_user()))
	{
		$user_info = get_userdata(bbp_get_displayed_user_id());
		$title = __("Profile for User:","avia_framework")." ".$user_info->display_name;
		if(bbp_is_single_user_edit())
		{
			$title = __("Edit profile for User:","avia_framework")." ".$user_info->display_name;
		}
	}

 	 $args = array();
 	 if(!empty($title)) $args['title'] = $title;

 	 if( get_post_meta( @get_the_ID(), 'header', true) != 'no') echo avia_title($args);
 	 
 	 do_action( 'ava_after_main_title' );
	 ?>

		<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>

			<div class='container'>

				<main class='template-page content  <?php avia_layout_class( 'content' ); ?> units' <?php avia_markup_helper(array('context' => 'content','post_type'=>'forum'));?>>

                    <?php
                    /* Run the loop to output the posts.
                    * If you want to overload this in a child theme then include a file
                    * called loop-page.php and that will be used instead.
                    */
                    $avia_config['size'] = 'page';
                    get_template_part( 'includes/loop', 'page' );
                    ?>

				<!--end content-->
				</main>

				<?php

				//get the sidebar
				$avia_config['currently_viewing'] = 'forum';
				get_sidebar();

				?>

			</div><!--end container-->

		</div><!-- close default .container_wrap element -->




<?php get_footer(); ?>