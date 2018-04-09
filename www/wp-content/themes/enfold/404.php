<?php
	if ( !defined('ABSPATH') ){ die(); }
	
	global $avia_config;

	/*
	 * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
	 */
	 get_header();


	 echo avia_title(array('title' => __('Error 404 - page not found', 'avia_framework')));
	 
	 do_action( 'ava_after_main_title' );
	?>


		<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>
			
			<?php 
				do_action('avia_404_extra'); // allows user to hook into 404 page fr extra functionallity. eg: send mail that page is missing, output additional information
			?>
			
			<div class='container'>

				<main class='template-page content <?php avia_layout_class( 'content' ); ?> units' <?php avia_markup_helper(array('context' => 'content'));?>>


                    <div class="entry entry-content-wrapper clearfix" id='search-fail'>
                    <?php

                    get_template_part('includes/error404');

                    ?>
                    </div>

				<!--end content-->
				</main>

				<?php

				//get the sidebar
				$avia_config['currently_viewing'] = 'page';
				get_sidebar();

				?>

			</div><!--end container-->

		</div><!-- close default .container_wrap element -->




<?php get_footer(); ?>
