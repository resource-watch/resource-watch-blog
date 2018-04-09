<?php
	
	if ( !defined('ABSPATH') ){ die(); }
	
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback which is
 * located in the loop-comments.php file.
 *
 */
?>

<?php if ( post_password_required() ) : 

 if (comments_open() ) :
	?>
		<p><?php _e( 'This post is password protected. Enter the password to view any comments.', 'avia_framework' ); ?></p>
	<?php
endif;
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	
endif;
?>

<?php
	// You can start editing here -- including this comment!
	
	//create seperator
	//if(comments_open() || get_comments_number()) echo "<div class='hr hr_invisible'></div>";
?>

	        	
	        	
<div class='comment-entry post-entry'>

<?php

 if ( get_comments_number() != "0" || comments_open() ) : ?>
<div class='comment_meta_container'>
			
			<div class='side-container-comment'>
	        		
	        		<div class='side-container-comment-inner'>
	        			<?php 
	        			$ccount = (int) get_comments_number();
	        			$rep	= __( 'replies', 'avia_framework' );
	        			if($ccount === 1) $rep	= __( 'reply', 'avia_framework' );
	        			?>
	        			
	        			<span class='comment-count'><?php echo $ccount; ?></span>
   						<span class='comment-text'><?php echo $rep; ?></span>
   						<span class='center-border center-border-left'></span>
   						<span class='center-border center-border-right'></span>
   						
	        		</div>
	        		
	        	</div>
			
			</div>

<?php 
endif;

if ( have_comments() ) : ?>
			
			<div class='comment_container'>
			

<?php 		
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through?
			echo "<span class='comment_page_nav_links comment_page_nav_links_top'>";
			echo "<span class='comment_prev_page'>";
				 previous_comments_link( __( '&laquo; Older Comments', 'avia_framework' ) );
			echo "</span>";
			echo "<span class='comment_next_page'>";
				 next_comments_link( __( 'Newer Comments &raquo;', 'avia_framework' ) );
			echo "</span>";
			echo "</span>";
		endif; // check for comment navigation
		
		
			//get comments
			$comment_entries = get_comments(array( 'type'=> 'comment', 'post_id' => $post->ID ));
			
			if(!empty($comment_entries)){
			
		 	?>
			<ol class="commentlist" id="comments">
				<?php
					/* Loop through and list the comments. Tell wp_list_comments()
					 * to use avia_inc_custom_comments() to format the comments.
					 * If you want to overload this in a child theme then you can
					 * define avia_framework_comment() and that will be used instead.
					 * See avia_framework_comment() in includes/loop-comments.php for more.
					 */
					wp_list_comments( array( 'type'=> 'comment', 'callback' => 'avia_inc_custom_comments' ) );
				?>
			</ol>
			<?php 
			}
			
			
			//get ping and trackbacks
			$ping_entries = get_comments(array( 'type'=> 'pings', 'post_id' => $post->ID ));
			
			if(!empty($ping_entries)){
			echo "<h4 id='pingback_heading'>".__('Trackbacks &amp; Pingbacks','avia_framework')."</h4>";
			?>
			
			<ol class="pingbacklist">
				<?php
					/* 
					 * Loop through and list the pingbacks and trackbacks. 
					 */
					wp_list_comments( array( 'type'=> 'pings', 'reverse_top_level'=>true ) );
				?>
			</ol>
			<?php } ?>
			
			
			
			
			
<?php 
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through?
			echo "<span class='comment_page_nav_links comment_page_nav_links_bottom'>";
			echo "<span class='comment_prev_page'>";
				 previous_comments_link( __( '&laquo; Older Comments', 'avia_framework' ) );
			echo "</span>";
			echo "<span class='comment_next_page'>";
				 next_comments_link( __( 'Newer Comments &raquo;', 'avia_framework' ) );
			echo "</span>";
			echo "</span>";
		endif; // check for comment navigation
	
	echo "</div> <!-- end grid div-->";
	
	
	else : // or, if we don't have comments:
	
	//do nothing
	
	

endif; // end have_comments()

 

	/* Last but not least the comment_form() wordpress function
	 * renders the comment form as defined by wordpress itself
	 * if you want to modify the submission form check the documentation here:
	 * http://codex.wordpress.org/Function_Reference/comment_form
	 */
	 if(comments_open()){
		
		 
		 echo "<div class='comment_container'>";
		 echo "<h3 class='miniheading'>".__('Leave a Reply','avia_framework')."</h3>";
		 echo "<span class='minitext'>".__('Want to join the discussion?','avia_framework')." <br/>".__('Feel free to contribute!','avia_framework')."</span>";
		 comment_form();
		 echo "</div>";
	 }
	 else if(get_comments_number())
	 {
		 /* If there are no comments and comments are closed,
		 * let's leave a little note, shall we?
		 */
	 	
	 	echo "<h3 class=' commentsclosed'>".__( 'Comments are closed.', 'avia_framework' )."</h3>";
	 } 
	  
	  ?>

</div>