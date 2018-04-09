<?php
// global $wp_registered_sidebars;
#########################################
	
	
	register_sidebar(array(
		'name' => 'Displayed Everywhere',
		'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">', 
		'after_widget' => '<span class="seperator extralight-border"></span></section>', 
		'before_title' => '<h3 class="widgettitle">', 
		'after_title' => '</h3>',
		'id'=>'av_everywhere'
	));

	register_sidebar(array(
		'name' => 'Sidebar Blog',
		'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">', 
		'after_widget' => '<span class="seperator extralight-border"></span></section>', 
		'before_title' => '<h3 class="widgettitle">', 
		'after_title' => '</h3>', 
		'id'=>'av_blog'
	));
		
	register_sidebar(array(
		'name' => 'Sidebar Pages',
		'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">', 
		'after_widget' => '<span class="seperator extralight-border"></span></section>', 
		'before_title' => '<h3 class="widgettitle">', 
		'after_title' => '</h3>', 
		'id'=>'av_pages'
	));

	
	if(class_exists( 'woocommerce' ))
	{
		
		register_sidebar(array(
			'name' => 'Shop Overview Page',
			'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">', 
			'after_widget' => '<span class="seperator extralight-border"></span></section>', 
			'before_title' => '<h3 class="widgettitle">', 
			'after_title' => '</h3>', 
			'id'=>'av_shop_overview'
		));
	
	
	
		register_sidebar(array(
			'name' => 'Single Product Pages',
			'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">', 
			'after_widget' => '<span class="seperator extralight-border"></span></section>', 
			'before_title' => '<h3 class="widgettitle">', 
			'after_title' => '</h3>', 
			'id'=>'av_shop_single'
		));
		
	}
		


	
	//dynamic widgets
	
	#footer
	$footer_columns = avia_get_option('footer_columns','5');
	
	for ($i = 1; $i <= $footer_columns; $i++)
	{
		register_sidebar(array(
			'name' => 'Footer - column'.$i,
			'before_widget' => '<section id="%1$s" class="widget clearfix %2$s">', 
			'after_widget' => '<span class="seperator extralight-border"></span></section>', 
			'before_title' => '<h3 class="widgettitle">', 
			'after_title' => '</h3>', 
			'id'=>'av_footer_'.$i
		));
	}

	
	//dummy widgets
	
	function avia_dummy_widget($number)
	{
		switch($number)
		{
			case 1:
			$title = apply_filters('widget_title', __('Interesting links','avia_framework') );

			?>
				<section class='widget'>
				<h3 class='widgettitle'><?php echo $title; ?></h3>
				<span class='minitext'><?php _e('Here are some interesting links for you! Enjoy your stay :)','avia_framework');?></span>
				</section>
			<?php
			break;
			
		
			case 4: 
				$title = apply_filters('widget_title', __('Archive','avia_framework') );
			
				echo "<section class='widget widget_archive'>";
				echo "<h3 class='widgettitle'>" . $title . "</h3>";
				echo "<ul>";
				wp_get_archives('type=monthly');
				echo "</ul>";
				echo "<span class='seperator extralight-border'></span></section>";
			break;
			
			case 3: 
				$title = apply_filters('widget_title', __('Categories','avia_framework') );
			
				echo "<section class='widget widget_categories'>";
				echo "<h3 class='widgettitle'>" . $title . "</h3>";
				echo "<ul>";
				wp_list_categories('sort_column=name&optioncount=0&hierarchical=0&title_li=');
				echo "</ul>";
				echo "<span class='seperator extralight-border'></span></section>";
			break;
			
			case 2: 
				$title = apply_filters('widget_title', __('Pages','avia_framework') );
			
				echo "<section class='widget widget_pages'>";
				echo "<h3 class='widgettitle'>" . $title . "</h3>";
				echo "<ul>";
				wp_list_pages('title_li=&depth=-1' );
				echo "</ul>";
				echo "<span class='seperator extralight-border'></span></section>";
			break;
			
			case 5: 
				$title = apply_filters('widget_title', __('Bookmarks','avia_framework') );
			
				echo "<section class='widget widget_archive'>";
				echo "<h3 class='widgettitle'>" . $title. "</h3>";
				echo "<ul>";
				wp_list_bookmarks('title_li=&categorize=0');
				echo "</ul>";
				echo "<span class='seperator extralight-border'></span></section>";
			break;
		}
	}