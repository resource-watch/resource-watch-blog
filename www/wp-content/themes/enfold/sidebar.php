<?php
if ( !defined('ABSPATH') ){ die(); }
	
global $avia_config;

##############################################################################
# Display the sidebar
##############################################################################

$default_sidebar = true;
$sidebar_pos = avia_layout_class('main', false);

$sidebar_smartphone = avia_get_option('smartphones_sidebar') == 'smartphones_sidebar' ? 'smartphones_sidebar_active' : "";
$sidebar = "";

if(strpos($sidebar_pos, 'sidebar_left')  !== false) $sidebar = 'left';
if(strpos($sidebar_pos, 'sidebar_right') !== false) $sidebar = 'right';

//filter the sidebar position (eg woocommerce single product pages always want the same sidebar pos)
$sidebar = apply_filters('avf_sidebar_position', $sidebar);

//if the layout hasnt the sidebar keyword defined we dont need to display one
if(empty($sidebar)) return;
if(!empty($avia_config['overload_sidebar'])) $avia_config['currently_viewing'] = $avia_config['overload_sidebar'];


echo "<aside class='sidebar sidebar_".$sidebar." ".$sidebar_smartphone." ".avia_layout_class( 'sidebar', false )." units' ".avia_markup_helper(array('context' => 'sidebar', 'echo' => false)).">";
    echo "<div class='inner_sidebar extralight-border'>";

        //Display a subnavigation for pages that is automatically generated, so the users do not need to work with widgets
        $av_sidebar_menu = avia_sidebar_menu(false);
        if($av_sidebar_menu)
        {
            echo $av_sidebar_menu;
            $default_sidebar = false;
        }


        $the_id = @get_the_ID();
        $custom_sidebar = "";
        if(!empty($the_id) && is_singular())
        {
            $custom_sidebar = get_post_meta($the_id, 'sidebar', true);
        }
		
		$custom_sidebar = apply_filters('avf_custom_sidebar', $custom_sidebar);
		
        if($custom_sidebar)
        {
            dynamic_sidebar($custom_sidebar);
            $default_sidebar = false;
        }
        else
        {	
            if(empty($avia_config['currently_viewing'])) $avia_config['currently_viewing'] = 'page';

            // general shop sidebars
            if ($avia_config['currently_viewing'] == 'shop' && dynamic_sidebar('Shop Overview Page') ) : $default_sidebar = false; endif;

            // single shop sidebars
            if ($avia_config['currently_viewing'] == 'shop_single') $default_sidebar = false;
            if ($avia_config['currently_viewing'] == 'shop_single' && dynamic_sidebar('Single Product Pages') ) : $default_sidebar = false; endif;

            // general blog sidebars
            if ($avia_config['currently_viewing'] == 'blog' && dynamic_sidebar('Sidebar Blog') ) : $default_sidebar = false; endif;

            // general pages sidebars
            if ($avia_config['currently_viewing'] == 'page' && dynamic_sidebar('Sidebar Pages') ) : $default_sidebar = false; endif;

            // forum pages sidebars
            if ($avia_config['currently_viewing'] == 'forum' && dynamic_sidebar('Forum') ) : $default_sidebar = false; endif;

        }

        //global sidebar
        if (dynamic_sidebar('Displayed Everywhere')) : $default_sidebar = false; endif;



        //default dummy sidebar
        if (apply_filters('avf_show_default_sidebars', $default_sidebar))
        {
			 if(apply_filters('avf_show_default_sidebar_pages', true)) {avia_dummy_widget(2);}
             if(apply_filters('avf_show_default_sidebar_categories', true)) {avia_dummy_widget(3);}
             if(apply_filters('avf_show_default_sidebar_archiv', true)) {avia_dummy_widget(4);}
             
             //	customize default sidebar and add your sidebars
	     do_action ('ava_add_custom_default_sidebars');
        }

    echo "</div>";
echo "</aside>";






