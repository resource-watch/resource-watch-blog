<?php
/*
Advanced iFrame
http://www.tinywebgallery.com/blog/advanced-iframe
Michael Dempfle
Administration include
*/
?>
<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');

include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-functions.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-quickstart.php';

$version = '7.5.4';
$updated = false;
$evanto = (file_exists(dirname(__FILE__) . "/includes/class-cw-envato-api.php"));
if (is_user_logged_in() && is_admin()) {
    $scrollposition = 0;
    $devOptions = $this->getAiAdminOptions();
    if ($devOptions['admin_was_loaded'] == false) {
        // we disable the check of the src.
        $devOptions['check_iframe_url_when_load'] = 'false';
        echo '<div class="error"><p><strong>';
        _e('The administration was not loaded until the end last time. It seems the integrated check of the "Url" field failed and this is now disabled. You can enable this again on the "Options" tab if you like.', 'advanced-iframe');  
        echo '</strong></p></div>';      
    } 
    $devOptions['admin_was_loaded'] = false;    
    update_option($this->adminOptionsName, $devOptions);
    
    if ($evanto) {
      $devOptions['demo'] = 'false';
    } else {
      $devOptions['alternative_shortcode'] = '';
    }
    
    
    if (isset($_POST['scrollposition'])) {
      $scrollposition = urlencode($_POST['scrollposition']); 
    }
    
    $is_latest = true;
    if ($evanto) {
      $latest_version = ai_getlatestVersion(); 
      if ($latest_version != -1) {
        if (version_compare ($latest_version,$version) == 1) { 
           printMessage(__('Version ', 'advanced-iframe')  .$latest_version. __(' of Advanced iFrame Pro is available. See the <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-history" target="_blank">history</a> for details.<br /><br />Please download the latest version from your download page of codecanyon. The easiest way to update is to overwrite all files with FTP.', 'advanced-iframe'));
           $is_latest = false;   
        }  
      } else {
        $is_latest = true;
      }
    } else {
      $is_latest = false;
    }
    
    $current_tab = ($devOptions['donation_bottom'] === 'false') ? 0:1;
      
    if (isset($_POST['current_tab'])) {
      $current_tab = urlencode($_POST['current_tab']); 
    }
    $current_tab = processConfigActions($current_tab);
    
    if (isset($_POST['current_open_sections'])) {
      $current_open_sections = urlencode($_POST['current_open_sections']);
      # and , we decode
      $current_open_sections = str_replace('%23', '#', $current_open_sections);
      $current_open_sections = str_replace('%2C', ',', $current_open_sections);    
    } else {
      $current_open_sections = ''; 
    }
    
    
    if (isset($_POST['update_iframe-loader'])) { //save option changes
        $adminSettings = array('securitykey', 'src', 'width', 'height', 'scrolling',
            'marginwidth', 'marginheight', 'frameborder', 'transparency',
            'content_id', 'content_styles', 'hide_elements', 'class',
            'shortcode_attributes', 'url_forward_parameter', 'id', 'name',
            'onload', 'onload_resize', 'onload_scroll_top',
            'additional_js', 'additional_css', 'store_height_in_cookie', 'additional_height',
            'iframe_content_id', 'iframe_content_styles', 'iframe_hide_elements', 'version_counter',
            'onload_show_element_only', 'donation_bottom',
            'include_url','include_content','include_height','include_fade','include_hide_page_until_loaded',
            'onload_resize_width', 'resize_on_ajax', 'resize_on_ajax_jquery','resize_on_click',
            'resize_on_click_elements','hide_page_until_loaded',
            'show_part_of_iframe', 'show_part_of_iframe_x', 'show_part_of_iframe_y',
            'show_part_of_iframe_width', 'show_part_of_iframe_height',
            'show_part_of_iframe_new_window','show_part_of_iframe_new_url',
            'show_part_of_iframe_next_viewports_hide', 'show_part_of_iframe_next_viewports',
            'show_part_of_iframe_next_viewports_loop','style',
            'use_shortcode_attributes_only','enable_external_height_workaround',
            'keep_overflow_hidden','hide_page_until_loaded_external',
            'onload_resize_delay', 'expert_mode', 'accordeon_menu',
            'show_part_of_iframe_allow_scrollbar_vertical', 'show_part_of_iframe_allow_scrollbar_horizontal',
            'hide_part_of_iframe','change_parent_links_target',
            'change_iframe_links','change_iframe_links_target',
            'iframe_redirect_url', 'show_part_of_iframe_style',
            'map_parameter_to_url', 'iframe_zoom',
            'tab_visible', 'tab_hidden','enable_responsive_iframe',
            'allowfullscreen','iframe_height_ratio',
            'show_iframe_loader', 'enable_lazy_load',
            'enable_lazy_load_threshold','enable_lazy_load_fadetime',
            'pass_id_by_url','include_scripts_in_footer',
            'enable_lazy_load_manual', 'write_css_directly',
            'resize_on_element_resize', 'resize_on_element_resize_delay',
            'add_css_class_parent',
            'auto_zoom','single_save_button','enable_lazy_load_manual_element',
            'alternative_shortcode', 'show_menu_link', 'load_jquery',
            'show_iframe_as_layer', 'auto_zoom_by_ratio',
            'add_iframe_url_as_param', 'add_iframe_url_as_param_prefix',
            'reload_interval', 'iframe_content_css',
            'additional_js_file_iframe', 'additional_css_file_iframe',
            'add_css_class_iframe','iframe_zoom_ie8',
            'enable_lazy_load_reserve_space','editorbutton',
            'hide_content_until_iframe_color', 'include_html',
            'enable_ios_mobile_scolling', 'sandbox',
            'show_iframe_as_layer_header_file', 'show_iframe_as_layer_header_height',
            'show_iframe_as_layer_header_position', 'show_iframe_as_layer_full',
            'demo', 'show_part_of_iframe_zoom',
            'external_height_workaround_delay',  
            'add_document_domain','document_domain',
            'multi_domain_enabled','check_shortcode',
            'use_post_message', 'element_to_measure_offset',
            'data_post_message', 'element_to_measure',
            'show_iframe_as_layer_keep_content','roles',
            'parent_content_css', 'debug_js',
            'check_iframe_cronjob','check_iframe_cronjob_email',
            'enable_content_filter', 'add_ai_external_local', 'title', 
            'check_iframes_when_save','admin_was_loaded',
            'check_iframe_url_when_load'
            );  
        if (!wp_verify_nonce($_POST['twg-options'], 'twg-options')) die('Sorry, your nonce did not verify.');
        
        if (!isset($_POST['action']) || $_POST['action'] !== 'reset') {
          foreach ($adminSettings as $item) {
             if ($item == 'version_counter') {
                $text = rand(100000, 999999);
             } else if ($item == 'additional_height') {
                 $text = trim(trim($_POST[$item]),'px%emt'); // remove px...
             } else {
                 if (isset($_POST[$item])) {
                     $text = trim($_POST[$item]);
                 } else {  
                     if ($item == 'show_part_of_iframe' || $item == 'show_part_of_iframe_next_viewports_loop'
                       || $item == 'show_iframe_loader' || $item == 'enable_lazy_load_manual' 
                       || $item == 'show_part_of_iframe_next_viewports_hide' || $item == 'write_css_directly' 
                       || $item == 'enable_responsive_iframe' || $item == 'enable_lazy_load'   
                       || $item == 'accordeon_menu' || $item == 'single_save_button'
                       || $item == 'show_iframe_as_layer' || $item == 'add_iframe_url_as_param'
                       || $item == 'auto_zoom' || $item == 'show_part_of_iframe_zoom'
                       || $item == 'demo' ||  $item == 'enable_ios_mobile_scolling'
                       || $item == 'store_height_in_cookie' || $item == 'show_iframe_as_layer_full'
                       || $item == 'use_post_message' || $item == 'multi_domain_enabled'
                       || $item == 'enable_content_filter' || $item == 'add_ai_external_local') {
                          $text = 'false';
                     } else if ($item == 'show_menu_link' || $item == 'resize_on_ajax_jquery' 
                       || $item == 'show_iframe_as_layer_keep_content' ||  $item == 'admin_was_loaded' ) {
                         $text = 'true';
                     } else if ($item == 'resize_on_element_resize_delay') {
                         $text = '250';
                     } else if ($item == 'show_iframe_as_layer_header_height') {
                         $text = '100';
                     } else if ($item == 'show_iframe_as_layer_header_position') {
                         $text = 'top';
                     } else if ($item == 'external_height_workaround_delay' || $item == 'element_to_measure_offset' )  {
                         $text = '0';
                     } else if ($item == 'element_to_measure' )  {
                         $text = 'default';
                     } else if ($item == 'roles' )  {
                         $text = 'none';
                     } else {
                         $text = '';
                     }
                 }
             }
             
             // Mixed signle and double quotes are only allowed for the parameters below because they do support 
             // shortcodes as input where both quotes are used.
             if ($item != 'src')  {
                $text = str_replace("'", '"' ,$text);
             }
             if ($item == 'roles') {
                // roles can only be changed by administrators!
                $user = wp_get_current_user();
                if ( in_array( 'administrator', (array) $user->roles ) ) {
                    $devOptions[$item] = stripslashes($text);
                }
             // replace ' with " 
              } else if ($item == 'include_url' || $item == 'src') {
                $text = str_replace('{', '__BRACKETS_OPEN__' ,$text);
                $text = str_replace('}', '__BRACKETS_CLOSE__' ,$text);
                $text = esc_url($text);
                $text = str_replace('__BRACKETS_OPEN__', '{' ,$text);
                $text = str_replace('__BRACKETS_CLOSE__' , '}' ,$text);
                $devOptions[$item] = stripslashes($text);
             } else if ($item == 'include_html') {
                $text = wp_kses( $text, array(
                    'strong' => array(),
                    'br' => array(),
                    'em' => array(),
                    'p' => array(),
                    'div' => array('id' => array(), 'class' => array(), 'style' => array()),
                    'a' => array('href' => array(),'target' => array(), 'class' => array(), 'style' => array()),
                    'img' => array('src' => array(), 'class' => array(), 'style' => array(), 'width' => array(), 'height')
                ) );
                $text =  balanceTags($text,true);
                $devOptions[$item] = stripslashes($text);
             } else if (function_exists('sanitize_text_field')) { 
                $devOptions[$item] = stripslashes(sanitize_text_field($text));
             } else {
                $devOptions[$item] = stripslashes($text);
             }
             if ($item == 'id') {
                $devOptions[$item] =  preg_replace("/\W/", "_", $text);
                // remove trailing numbers
                $devOptions[$item] = preg_replace('/^[0-9]+/', '', $devOptions[$item]);
             }
             
             // we check if we have an invalid configuration!
             if ($devOptions['shortcode_attributes'] === 'false' && $devOptions['use_shortcode_attributes_only'] === 'true') {
                $devOptions['shortcode_attributes'] = 'true';
                printError(__('You have set "Allow shortcode attributes" to "No" and "Use shortcode attributes only" to "Yes". This combination is not valid. "Allow shortcode attributes" was set to "Yes". Please check if this is what you  want. "Allow shortcode attributes" overrules "Use shortcode attributes only" if you set "Use shortcode attributes only" directly in the shortcode with use_shortcode_attributes_only="true".', "advanced-iframe"));
                $scrollposition = 0;
             }
             
          
             
          }
        } else {
          $securityKey = $devOptions['securitykey'];
          $it = $devOptions['install_date'];
          $devOptions = advancediFrame::iframe_defaults();
          $devOptions['securitykey'] = $securityKey;
          $devOptions['install_date'] = $it;  
        }
                                                                                                                                                                                                                                                              if ($evanto && empty($devOptions['install_date'])) {$devOptions['install_date'] = time();}
        update_option($this->adminOptionsName, $devOptions);

        // create the external js file with the url of the wordpress installation
        $this->saveExternalJsFile();
        
        ?>
<?php if ($devOptions['single_save_button'] == 'false') { ?> 
<div class="updated">
  <p>
     <strong>
      <?php 
      if (!isset($_POST['action']) || $_POST['action'] !== 'reset') {
        _e('Settings updated.', 'advanced-iframe');
      } else {
        _e('Settings resetted.', 'advanced-iframe');   
      }
       ?>
     </strong>
  </p>
</div>
<?php
  } else {
  $updated = true;
  }
}

// needs to be set after the save again.
if ($evanto) {
    $devOptions['demo'] = 'false';
}
$isDemo =  $devOptions['demo'] == 'true';
    
if ($isDemo) { ?>
<div class="updated top-10">
  <p>
     <strong>
      <?php _e('The administration is running in the pro modus. Please note that the blue settings of the pro version are not working. They only show what is possible!', 'advanced-iframe'); ?>
     </strong>
  </p>
</div>
<?php
}
    if ($evanto && clearstatscache($devOptions)) {
      printError(__('Yo'+'ur ver'+'sion of Adv'+'anced iFr'+'ame Pro s'+'eems to be an ill'+'egal co'+'py and is now wo'+'rking in the fr'+ 'eeware m'+'ode ag'+'ain.<br />Ple'+'ase get the of'+'fical v'+'ersion from co'+'decanyon or co'+'ntact the au'+'thor thr'+'ough code'+'canyon if you th'+'ink this is a fa'+'lse al'+'arm.', 'advanced-iframe'));
    }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              if (clearstatscache($devOptions)) {$evanto = false; }
    ?>
<style type="text/css">table th {text-align: left;}
</style>
<div id="ai" class="wrap">
  <!-- options-general.php?page=advanced-iframe.php -->
  <form id="ai_form" name="ai_form" method="post" action="options-general.php?page=advanced-iframe.php">
    <input type="hidden" id="current_tab" name="current_tab" value="<?php echo $current_tab; ?>">
    <input type="hidden" id="current_open_sections" name="current_open_sections" value="">
    
    <?php wp_nonce_field('twg-options', 'twg-options'); ?>

      <div id="icon-options-general" class="icon_ai show-always">
      <br />
      </div>
<h1 class="show-always" class="full-width"><?php
        _e('Advanced iFrame ', 'advanced-iframe');
        if ($evanto) {
        _e('Pro', 'advanced-iframe');
        } 
        echo ' <small>v' . $version. '</small>';  
        if ($evanto) {
          if ($is_latest) {
            echo ' <small class="hide-print"><small><small>' . __('(Your installation is up to date - <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-history" target="_blank">view history</a>)', 'advanced-iframe') . '</small></small></small>';  
          } else {
             echo ' <small class="hide-print"><small><small>' . __('(<a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-history" target="_blank">Version '.$latest_version.'</a> is available. <a href="http://codecanyon.net/downloads" target="_blank">Download</a> it from CodeCanyon and follow the <a href="http://codecanyon.net/item/advanced-iframe-pro/5344999?ref=mdempfle#item-description__upgrade" _target="blank">update instructions</a>!', 'advanced-iframe') . '</small></small></small>';
          }
        } else {
           echo ' <small class="hide-print"><small><small>' . __('(<a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-history" target="_blank">view history</a>)', 'advanced-iframe') . '</small></small></small>';  
        }
        if (!$evanto && !$isDemo) {  
            echo '<div class="pro-hint">' . __('Test the pro administration:<br />Enable it on the options tab', 'advanced-iframe') . '</div>';
        }
        echo '<div class="header-help">' . __('If you start using advanced iframe please read the quickstart guide on the options tab first. After that continue with an iframe like described on the basic tab. Only if the iframe appears add additional features. Go to the <a href="http://www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0" target="_blank">free</a> and the <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo" target="_blank">pro demos</a> page for running examples.', 'advanced-iframe') . '</div>';     
        
        ?><span id="help-header">&nbsp;<?php __('attribute help', 'advanced-iframe'); ?></span></h1>
<?php if (!$isDemo) { ?>
<br />
<?php }

_e('<input type="search" class="ai-input-search" placeholder="Search for settings" />
<div id="ai-input-search-result">
No settings found for this search term.
</div><div id="ai-input-search-result-show">&nbsp;</div>
<div style="clear:left;"></div>
<div id="ai-input-search-help">
The search does look for the search term in the label and the description of each setting on all tabs. Tabs with findings are marked yellow. It does not search in the additional documentation that does exist in each section. Please use the browser search for a full text of this page.
</div>', 'advanced-iframe');

_e('<h2 class="nav-tab-wrapper show-always">', 'advanced-iframe');
if ($devOptions['donation_bottom'] === 'false') {
  _e('<a id="tab_0" class="nav-tab nav-tab-active" href="#introduction"><span>Options</span></a>
      <a id="tab_1" class="nav-tab" href="#basic"><span>Basic Settings</span></a>
      <a id="tab_2" class="nav-tab advanced-settings-tab" href="#advanced"><span>Advanced Settings</span></a>
      <a id="tab_3" class="nav-tab external-workaround" href="#external-workaround"><span>External workaround</span></a>
      <a id="tab_4" class="nav-tab" href="#add-files"><span>Add/Include files</span></a>
      <a id="tab_5" class="nav-tab help-tab" href="#help"><span>Help / FAQ</span></a>', 'advanced-iframe');
} else {
  _e('<a id="tab_1" class="nav-tab nav-tab-active" href="#basic"><span>Basic Settings</span></a>
    <a id="tab_2" class="nav-tab advanced-settings-tab" href="#advanced"><span>Advanced Settings</span></a>
    <a id="tab_3" class="nav-tab external-workaround" href="#external-workaround"><span>External workaround</span></a>
    <a id="tab_4" class="nav-tab" href="#add-files"><span>Add/Include files</span></a>
    <a id="tab_5" class="nav-tab help-tab" href="#help"><span>Help / FAQ</span></a>
    <a id="tab_0" class="nav-tab" href="#introduction"><span>Options</span></a>', 'advanced-iframe');
}
_e('
</h2>
', 'advanced-iframe');
?>

<div style="clear:both;"></div>
<div id="tab_wrapper">
<?php
if ($devOptions['donation_bottom'] === 'false') {
  echo '<section id="section-quickstart" class="tab_0">';
  printDonation($devOptions, $evanto);
  echo "</div>";
  echo '</section>';
}

echo '<section id="section-default" class="tab_1">';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-default.php';
echo '</section><section id="section-advanced" class="tab_2">';

if ($devOptions['accordeon_menu'] == 'false') { ?>
<div id="acc">
<?php } else { 
_e('<p>Please open the section where you want to change a default setting. Please note that some of the advanced features require basic html/css knowhow! You can open several sections at once for easier navigation.</p>', 'advanced-iframe');
?>
<div id="accordion">
<?php }

include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-advanced.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-resize.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-modify-iframe.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-modify-parent.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-zoom.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-lazy-load.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-parameters.php';
echo '</div>';

echo '</section><section id="section-external-workaround" class="tab_3">';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-external-workaround.php';
echo '</section><section id="section-add-files" class="tab_4">';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-add-files.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-include-directly.php';
echo '</section><section  id="section-help" class="tab_5">';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-video.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-faq.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-forum.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-support.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-find-id.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-jquery.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-browser.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-help-post.php';
include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-twg.php';
echo '</section>';
if ($devOptions['donation_bottom'] === 'true') {
  echo '<section id="section-quickstart" class="tab_0">';
  printDonation($devOptions, $evanto);
  echo "</div>";
  echo '</section>';
}
?>
</div>
<?php if ($devOptions['single_save_button'] == 'true') { ?>    
<div id="wpadminbar" class="wp-core-ui ai-save-bar">
        <div>
        <?php 
        if ($updated) { 
          $updated_display_text = "visible";
          echo '<script type="text/javascript">setTimeout(function() { jQuery("#updated_text").css("visibility","hidden")}, 4000);</script>'; 
        } else {
          $updated_display_text = "hidden";
        }
        ?>
           <div id="updated_text" style="visibility:<?php echo $updated_display_text; ?>;"><?php
           if (!isset($_POST['action']) ||  $_POST['action'] !== 'reset') {
               _e("Settings updated.", "advanced-iframe");
           } else {
              _e("Settings resetted.", "advanced-iframe");   
           }
           ?></div> 
        <input type="hidden" name="action" id="action" value="update">
        <input id="wpbarbutton" class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>  <input id="wpresetbutton" class="button-secondary confirmation" name="update_iframe-loader" onclick="resetAiSettings();" type="submit" value="<?php _e('Reset Settings', 'advanced-iframe') ?>" />
        </div>
        
      
</div> 
<input type="hidden" id="scrollposition" name="scrollposition" value="0">   
<?php } ?>
</form>
</div>
<?php
// All sections are closed if we use the accordeon and open sections arlready 
if ($devOptions['accordeon_menu'] != 'false' && !empty($current_open_sections)) {
  $devOptions['accordeon_menu'] = 'no';
} 
?>
<script type="text/javascript">
jQuery(function() {
  initAdminConfiguration(<?php echo ($evanto) ? "true" : "false"; ?>,<?php echo '"' .$devOptions['accordeon_menu'] . '"'; ?>);  
  <?php if (!empty($current_open_sections)) { ?>
     jQuery('<?php echo $current_open_sections; ?>').click();
  <?php } ?>
  document.getElementById('tab_<?php echo $current_tab; ?>').click(); 
  setTimeout(function() {
    jQuery(document).scrollTop(<?php echo $scrollposition; ?>);
    accTime = 400;
  }, 100);
});
</script>
<?php 
  $devOptions['admin_was_loaded'] = true;    
  update_option($this->adminOptionsName, $devOptions);
} 
?>