<?php

			
$strings['avia_modal_js']  = array(
				'ajax_error' 	=> __( 'Error fetching content - please reload the page and try again', 'avia_framework' ),
				'login_error' 	=> __( 'It seems your are no longer logged in. Please reload the page and try again', 'avia_framework' ),
				'timeout' 	    => __( 'Your session timed out. Simply reload the page and try again', 'avia_framework' ),
				'error' 		=> __( 'An error occurred', 'avia_framework' ),
				'attention' 	=> __( 'Attention!', 'avia_framework' ),
				'success' 		=> __( 'All right!', 'avia_framework' ),
				'save' 			=> __( 'Save', 'avia_framework' ),
				'close' 		=> __( 'Close', 'avia_framework' ),
				
				/*shortcode specific*/
				'select_layout'  => __( 'Select a cell layout', 'avia_framework' ),
				'no_layout'  => __( 'The current number of cells does not allow any layout variations', 'avia_framework' ),
				'add_one_cell'  => __( 'You need to add at least one cell', 'avia_framework' ),
				'remove_one_cell'  => __( 'You need to remove at least one cell', 'avia_framework' ),
				
				'gmap_api_text' => __( 'Google changed the way google maps work. You now need to enter a valid Google Maps API Key', 'avia_framework' )."<br/><br/>".
								   __( 'You can read a description on how to create and enter that key here:', 'avia_framework' )." ".
								   "<a target='_blank' href='".admin_url( "admin.php?page=avia#goto_google" )."'>".__( 'Enfold Google Settings', 'avia_framework' )."</a>",
				
				'gmap_api_wrong' => __( 'It seems that your Google API key is not configured correctly', 'avia_framework' )."<br/><br/>".
								   __( 'The key is probably either restricted to the wrong domain or the domain syntax you entered is wrong.', 'avia_framework' )." <br><br>".
								   __( 'Please check your API key', 'avia_framework' )." <a target='_blank' href='https://console.developers.google.com/apis/credentials'>".__( 'here', 'avia_framework' )."</a><br><br>".
								   
								   __( 'The domain that should be allowed is:', 'avia_framework' )." <br><strong>". trailingslashit(get_site_url()) ."*</strong>",
								   
				'toomanyrequests'	=> __("Too many requests at once, please wait a few seconds before requesting coordinates again",'avia_framework'),
		        'notfound'			=> __("Address couldn't be found by Google, please add it manually",'avia_framework'),
		        'insertaddress' 	=> __("Please insert a valid address in the fields above",'avia_framework')				   
			
			);
			
			
$strings['avia_history_js']  = array(
				'undo_label' => __( 'Undo', 'avia_framework' ),
				'redo_label' => __( 'Redo', 'avia_framework' ),
			);			


$strings['avia_template_save_js']  = array(
				'no_content' => __( 'You need to add at least one element to the canvas to save this entry as a template', 'avia_framework' ),
				'chose_name' => __( 'Choose Template Name', 'avia_framework' ),
				'chose_save' => __( 'Save Element as Template: Choose a Name', 'avia_framework' ),
				'chars'      => __( 'Allowed Characters: Whitespace', 'avia_framework' ),
				'save_msg'   => __( 'Template Name must have at least 3 characters', 'avia_framework' ),
				'not_found'  => __( 'Could not load the template. You might want to try and reload the page', 'avia_framework' ),
			);	
