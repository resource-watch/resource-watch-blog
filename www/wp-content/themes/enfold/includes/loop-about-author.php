<?php
	global $avia_config;

	$author_id    = get_query_var( 'author' );
	if(empty($author_id)) $author_id = get_the_author_meta('ID');
	
	$name         = apply_filters('avf_author_name', get_the_author_meta('display_name', $author_id), $author_id);
	$email        = apply_filters('avf_author_email', get_the_author_meta('email', $author_id), $author_id);
	
	$gravatar_alt = esc_html($name);
	$gravatar     = get_avatar($email, '81', '', $gravatar_alt);
	$name         = "<span class='author-box-name' ".avia_markup_helper(array('context' => 'author_name','echo'=>false)).">". $name ."</span>";
	$heading      = __("About",'avia_framework') ." ". $name;
	$description  = apply_filters('avf_author_description', get_the_author_meta('description', $author_id), $author_id);

    echo '<section class="author-box" '.avia_markup_helper(array('context' => 'author','echo'=>false)).'>';
	if(empty($description))
	{
		$description  = __("This author has yet to write their bio.",'avia_framework');
		$description .= '</br>'.sprintf( __( 'Meanwhile lets just say that we are proud %s contributed a whooping %s entries.', 'avia_framework' ), $name, count_user_posts( $author_id ) );

		if(current_user_can('edit_users') || get_current_user_id() == $author_id)
		{
	    	$description .= "</br><a href='".admin_url( 'profile.php?user_id=' . $author_id )."'>".__( 'Edit the profile description here.', 'avia_framework' )."</a>";
		}
	}


	echo "<span class='post-author-format-type blog-meta'><span class='rounded-container'>{$gravatar}</span></span>";
    echo "<div class='author_description '>
        <h3 class='author-title'>{$heading}</h3>
        <div class='author_description_text'" .avia_markup_helper(array('context' => 'description','echo'=>false)).">".wpautop($description)."</div><span class='author-extra-border'></span></div>";
    echo '</section>';
?>
