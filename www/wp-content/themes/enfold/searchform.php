<?php 
if ( !defined('ABSPATH') ){ die(); }

global $avia_config; 


//allows you to modify the search parameters. for example bbpress search_id needs to be 'bbp_search' instead of 's'. you can also deactivate ajax search by setting ajax_disable to true
$search_params = apply_filters('avf_frontend_search_form_param', array(
	
	'placeholder'  	=> __('Search','avia_framework'),
	'search_id'	   	=> 's',
	'form_action'	=> home_url( '/' ),
	'ajax_disable'	=> false
));

$disable_ajax = $search_params['ajax_disable'] == false ? "" : "av_disable_ajax_search";

$icon  = av_icon_char('search');
$class = av_icon_class('search');
?>


<form action="<?php echo $search_params['form_action']; ?>" id="searchform" method="get" class="<?php echo $disable_ajax; ?>">
	<div>
		<input type="submit" value="<?php echo $icon; ?>" id="searchsubmit" class="button <?php echo $class; ?>" />
		<input type="text" id="s" name="<?php echo $search_params['search_id']; ?>" value="<?php if(!empty($_GET['s'])) echo get_search_query(); ?>" placeholder='<?php echo $search_params['placeholder']; ?>' />
		<?php 
		
		// allows to add aditional form fields to modify the query (eg add an input with name "post_type" and value "page" to search for pages only)
		do_action('ava_frontend_search_form'); 
		
		?>
	</div>
</form>