<?php

// inline css to prevent loading of the whole ai.css
   $error_css_start = '<style type="text/css">
       .errordiv { padding:10px; margin:10px; border: 1px solid #555555;color: #000000;background-color: #f8f8f8; width:500px; }';
   $html .= $error_css_start;
   $error_css = $error_css_start . '</style>';

// Scripts are loaded to the footer only if the shortcode is on the page.
$this->scriptsNeeded = true;
if (isset($aip_standalone)) {
 $aiPath="";
 $options = array();
 $options['securitykey'] = 'standalone';
 // load standalone default settings
 extract($iframeStandaloneDefaultOptions);
 // load standalone settings
 extract($iframeStandaloneOptions);

 // check id
                 // autovalue if no id is set but a src
  if (!isset($iframeStandaloneOptions['id'])) {
     global $instance_counter;

     if (isset($instance_counter)) {
         $autoid =  $id . "_" . $instance_counter++;
         $id = $autoid;
     } else {
         $instance_counter = 2;
     }

     if (!isset($iframeStandaloneOptions['name'])) {
         // check if we have name - if not we first use the id if given - if not - autoname!
         $name = $id;
     }
  }

} else {

$aiPath="/advanced-iframe";
$options = get_option('advancediFrameAdminOptions');
// set defaults for not existing settings
// can happen if users never save the config but only use the shortcodes
$defaults = $this->iframe_defaults();
foreach ($defaults as $key => $option) {
$iframeAdminOptions[$key] = $option;
if (!isset ($options[$key])) { $options[$key] = $option; }
}

// check if defaults from confg should be read
extract(shortcode_atts(array('use_shortcode_attributes_only' => 'not set'), $atts));
// if not set in shortcode we look in the config
if ($use_shortcode_attributes_only == 'not set') {
 $use_shortcode_attributes_only = $options['use_shortcode_attributes_only'];
}

// version is always read.
$version_counter = $options['version_counter'];
$alternative_shortcode = $options['alternative_shortcode'];
$check_shortcode = $options['check_shortcode'];
$use_post_message = $options['use_post_message'];
$multi_domain_enabled = $options['multi_domain_enabled'];
$debug_js = $options['debug_js'];

// defaults from main config
if ($use_shortcode_attributes_only == 'false' || $options['shortcode_attributes'] == 'false') {  //
extract(array('securitykey' => 'not set',
  'src' => $options['src'], 'height' => $options['height'], 'width' => $options['width'],
  'frameborder' => $options['frameborder'], 'scrolling' => $options['scrolling'],
  'marginheight' => $options['marginheight'], 'marginwidth' => $options['marginwidth'],
  'transparency' => $options['transparency'], 'content_id' => $options['content_id'],
  'content_styles' => $options['content_styles'], 'hide_elements' => $options['hide_elements'],
  'class' => $options['class'], 'url_forward_parameter' => $options['url_forward_parameter'],
  'id' => $options['id'], 'name' => $options['name'],
  'onload' => $options['onload'], 'onload_resize' => $options['onload_resize'],
  'onload_scroll_top'=> $options['onload_scroll_top'],
  'additional_js'=> $options['additional_js'],
  'additional_css'=> $options['additional_css'],
  'store_height_in_cookie'=> $options['store_height_in_cookie'],
  'additional_height' =>  $options['additional_height'],
  'iframe_content_id' =>  $options['iframe_content_id'],
  'iframe_content_styles' =>  $options['iframe_content_styles'],
  'iframe_hide_elements' =>  $options['iframe_hide_elements'],
  'version_counter' =>  $options['version_counter'],
  'onload_show_element_only' =>  $options['onload_show_element_only'],
  'include_url' =>  $options['include_url'],
  'include_content' =>  $options['include_content'],
  'include_height' =>  $options['include_height'],
  'include_fade' =>  $options['include_fade'],
  'include_hide_page_until_loaded' =>  $options['include_hide_page_until_loaded'],
  'onload_resize_width' =>  $options['onload_resize_width'],
  'resize_on_ajax' =>  $options['resize_on_ajax'],
  'resize_on_ajax_jquery' =>  $options['resize_on_ajax_jquery'],
  'resize_on_click' =>  $options['resize_on_click'],
  'resize_on_click_elements' =>  $options['resize_on_click_elements'],
  'hide_page_until_loaded' =>  $options['hide_page_until_loaded'],
  'show_part_of_iframe' =>  $options['show_part_of_iframe'],
  'show_part_of_iframe_x' =>  $options['show_part_of_iframe_x'],
  'show_part_of_iframe_y' =>  $options['show_part_of_iframe_y'],
  'show_part_of_iframe_width' =>  $options['show_part_of_iframe_width'],
  'show_part_of_iframe_height' =>  $options['show_part_of_iframe_height'],
  'show_part_of_iframe_new_window' =>  $options['show_part_of_iframe_new_window'],
  'show_part_of_iframe_new_url' =>  $options['show_part_of_iframe_new_url'],
  'show_part_of_iframe_next_viewports_hide' =>  $options['show_part_of_iframe_next_viewports_hide'],
  'show_part_of_iframe_next_viewports' => $options['show_part_of_iframe_next_viewports'],
  'show_part_of_iframe_next_viewports_loop' => $options['show_part_of_iframe_next_viewports_loop'],
  'style' => $options['style'],
  'enable_external_height_workaround' => $options['enable_external_height_workaround'],
  'hide_page_until_loaded_external' => $options['hide_page_until_loaded_external'],
  'onload_resize_delay' => $options['onload_resize_delay'],
  'show_part_of_iframe_allow_scrollbar_vertical' => $options['show_part_of_iframe_allow_scrollbar_vertical'],
  'show_part_of_iframe_allow_scrollbar_horizontal' => $options['show_part_of_iframe_allow_scrollbar_horizontal'],
  'hide_part_of_iframe'  => $options['hide_part_of_iframe'],
  'change_parent_links_target'  => $options['change_parent_links_target'],
  'change_iframe_links'  => $options['change_iframe_links'],
  'change_iframe_links_target'  => $options['change_iframe_links_target'],
  'browser'  => $options['browser'],
  'show_part_of_iframe_style'  => $options['show_part_of_iframe_style'],
  'map_parameter_to_url'  => $options['map_parameter_to_url'],
  'iframe_zoom'  => $options['iframe_zoom'],
  'show_iframe_loader'  => $options['show_iframe_loader'],
  'tab_visible'  => $options['tab_visible'],
  'tab_hidden'  => $options['tab_hidden'],
  'enable_responsive_iframe'  => $options['enable_responsive_iframe'],
  'allowfullscreen'  => $options['allowfullscreen'],
  'iframe_height_ratio'  => $options['iframe_height_ratio'],
  'enable_lazy_load'  => $options['enable_lazy_load'],
  'enable_lazy_load_threshold'  => $options['enable_lazy_load_threshold'],
  'enable_lazy_load_fadetime'  => $options['enable_lazy_load_fadetime'],
  'enable_lazy_load_manual'  => $options['enable_lazy_load_manual'],
  'pass_id_by_url'  => $options['pass_id_by_url'],
  'resize_on_element_resize'  => $options['resize_on_element_resize'],
  'resize_on_element_resize_delay'  => $options['resize_on_element_resize_delay'],
  'add_css_class_parent'  => $options['add_css_class_parent'],
  'auto_zoom'  => $options['auto_zoom'],
  'enable_lazy_load_manual_element'  => $options['enable_lazy_load_manual_element'],
  'show_iframe_as_layer'  => $options['show_iframe_as_layer'],
  'add_iframe_url_as_param'  => $options['add_iframe_url_as_param'],
  'add_iframe_url_as_param_prefix'  => $options['add_iframe_url_as_param_prefix'],
  'auto_zoom_by_ratio'  => $options['auto_zoom_by_ratio'],
  'reload_interval'  => $options['reload_interval'],
  'iframe_content_css'  => $options['iframe_content_css'],
  'additional_css_file_iframe'  => $options['additional_css_file_iframe'],
  'additional_js_file_iframe'  => $options['additional_js_file_iframe'],
  'add_css_class_iframe'  => $options['add_css_class_iframe'],
  'iframe_zoom_ie8'  => $options['iframe_zoom_ie8'],
  'enable_lazy_load_reserve_space'  => $options['enable_lazy_load_reserve_space'],
  'hide_content_until_iframe_color'  => $options['hide_content_until_iframe_color'],
  'use_zoom_absolute_fix'  => $options['use_zoom_absolute_fix'],
  'include_html'  => $options['include_html'],
  'enable_ios_mobile_scolling'  => $options['enable_ios_mobile_scolling'],
  'show_iframe_as_layer_header_file'  => $options['show_iframe_as_layer_header_file'],
  'show_iframe_as_layer_header_height'  => $options['show_iframe_as_layer_header_height'],
  'show_iframe_as_layer_header_position'  => $options['show_iframe_as_layer_header_position'],
  'resize_min_height'  => $options['resize_min_height'],
  'show_iframe_as_layer_full'  => $options['show_iframe_as_layer_full'],
  'show_part_of_iframe_zoom'  => $options['show_part_of_iframe_zoom'],
  'add_document_domain'  => $options['add_document_domain'],
  'document_domain'  => $options['document_domain'],
  'sandbox'  => $options['sandbox'],
  'show_iframe_as_layer_keep_content', $options['show_iframe_as_layer_keep_content'],
  'parent_content_css'  => $options['parent_content_css'],
  'include_scripts_in_content'  => $options['include_scripts_in_content'],
  'title' => $options['title'],
   $atts));
}

extract(array('include_scripts_in_footer' => $options['include_scripts_in_footer'],
   $atts));

// read the shortcode attributes
if ($options['shortcode_attributes'] == 'true') {
  // src value can be hidden in [0] and [1] if the editor does hotlink the url. Therefore I look in there if the src is not set!
  if (!isset($atts['src'])) {
      if (isset($atts[0]) && (stristr($atts[0], 'src') !== FALSE)) {
        if (isset($atts[1])) {
          $input = '<a ' . $atts[1];
          $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
          if(preg_match_all("/$regexp/siU", $input, $matches)) {
            if (isset($matches[2])) {
              $atts['src'] = $matches[2][0];
            }
          }
        }
      }
  }

  if ($use_shortcode_attributes_only == 'true') {
       $key_temp = $options['securitykey'];
       $options = $defaults;
       $options['securitykey'] = $key_temp;
       $options['src'] = "not set";
       $options['height'] = "not set";
       $options['width'] = "not set";
  }

  if ($check_shortcode == 'true') {
    $error_array = array();
    // we go through all parameters and theck if they are valid.
     foreach ($atts as $key => $option) {
        if ($key == 'id') {
            $id_new =  preg_replace("/\W/", "_", $option);
            $id_new = preg_replace('/^[0-9]+/', '', $id_new);
            if ($id_new != $option) {
               $error_array[] = 'Id "' . esc_html($option) . '" is not valid. Please check the documentation.';
            }
        }

        // we add the typo of enable_ios_mobile_scolling -> enable_ios_mobile_scrolling
        $defaults['enable_ios_mobile_scrolling'] = 'true';

        if (!array_key_exists ( $key , $defaults )) {
          if (strlen($key) == 1 ) {
            $error_array[] = $option;
          } else {
             $error_array[] =  $key . '="' . $option . '"';
          }
        }
     }

     if (!empty($error_array)) {
     echo $error_css . '<div class="errordiv">' . __('<strong>The following [advanced_iframe] attributes are unknown or cannot be read properly. Please fix or remove this attributes. You can disable this message in the administration on the "Options" tab:</strong>', 'advanced-iframe');
        echo '<ul>';
        foreach ($error_array as $error_string ) {
          echo '<li>' . $error_string . '</li>';
        }
        echo '</ul></div>';
     }
   }

  extract(shortcode_atts(array('securitykey' => 'not set',
      'src' => $options['src'], 'height' => $options['height'], 'width' => $options['width'],
      'frameborder' => $options['frameborder'], 'scrolling' => $options['scrolling'],
      'marginheight' => $options['marginheight'], 'marginwidth' => $options['marginwidth'],
      'transparency' => $options['transparency'], 'content_id' => $options['content_id'],
      'content_styles' => $options['content_styles'], 'hide_elements' => $options['hide_elements'],
      'class' => $options['class'], 'url_forward_parameter' => $options['url_forward_parameter'],
      'id' => $options['id'], 'name' => $options['name'],
      'onload' => $options['onload'],
      'onload_resize' => $options['onload_resize'],
      'onload_scroll_top'=> $options['onload_scroll_top'],
      'additional_js'=> $options['additional_js'],
      'additional_css'=> $options['additional_css'],
      'store_height_in_cookie'=> $options['store_height_in_cookie'],
      'additional_height' =>  $options['additional_height'],
      'iframe_content_id' =>  $options['iframe_content_id'],
      'iframe_content_styles' =>  $options['iframe_content_styles'],
      'iframe_hide_elements' =>  $options['iframe_hide_elements'],
      'onload_show_element_only' =>  $options['onload_show_element_only'],
      'include_url' =>  $options['include_url'],
      'include_content' =>  $options['include_content'],
      'include_height' =>  $options['include_height'],
      'include_fade' =>  $options['include_fade'],
      'include_hide_page_until_loaded' =>  $options['include_hide_page_until_loaded'],
      'onload_resize_width'  =>  $options['onload_resize_width'],
      'resize_on_ajax'  =>  $options['resize_on_ajax'],
      'resize_on_ajax_jquery' =>  $options['resize_on_ajax_jquery'],
      'resize_on_click' =>  $options['resize_on_click'],
      'resize_on_click_elements' =>  $options['resize_on_click_elements'],
      'hide_page_until_loaded' =>  $options['hide_page_until_loaded'],
      'show_part_of_iframe' =>  $options['show_part_of_iframe'],
      'show_part_of_iframe_x' =>  $options['show_part_of_iframe_x'],
      'show_part_of_iframe_y' =>  $options['show_part_of_iframe_y'],
      'show_part_of_iframe_width' =>  $options['show_part_of_iframe_width'],
      'show_part_of_iframe_height' =>  $options['show_part_of_iframe_height'],
      'show_part_of_iframe_new_window' =>  $options['show_part_of_iframe_new_window'],
      'show_part_of_iframe_new_url' =>  $options['show_part_of_iframe_new_url'],
      'show_part_of_iframe_next_viewports_hide' =>  $options['show_part_of_iframe_next_viewports_hide'],
      'show_part_of_iframe_next_viewports' =>  $options['show_part_of_iframe_next_viewports'],
      'show_part_of_iframe_next_viewports_loop' => $options['show_part_of_iframe_next_viewports_loop'],
      'style' => $options['style'],
      'enable_external_height_workaround' => $options['enable_external_height_workaround'],
      'hide_page_until_loaded_external' => $options['hide_page_until_loaded_external'],
      'onload_resize_delay' => $options['onload_resize_delay'],
      'show_part_of_iframe_allow_scrollbar_vertical' => $options['show_part_of_iframe_allow_scrollbar_vertical'],
      'show_part_of_iframe_allow_scrollbar_horizontal' => $options['show_part_of_iframe_allow_scrollbar_horizontal'],
      'hide_part_of_iframe'  => $options['hide_part_of_iframe'],
      'change_parent_links_target'  => $options['change_parent_links_target'],
      'change_iframe_links'  => $options['change_iframe_links'],
      'change_iframe_links_target'  => $options['change_iframe_links_target'],
      'browser'  => $options['browser'],
      'show_part_of_iframe_style'  => $options['show_part_of_iframe_style'],
      'map_parameter_to_url'  => $options['map_parameter_to_url'],
      'iframe_zoom'  => $options['iframe_zoom'],
      'show_iframe_loader'  => $options['show_iframe_loader'],
      'tab_visible'  => $options['tab_visible'],
      'tab_hidden'  => $options['tab_hidden'],
      'enable_responsive_iframe'  => $options['enable_responsive_iframe'],
      'allowfullscreen'  => $options['allowfullscreen'],
      'iframe_height_ratio'  => $options['iframe_height_ratio'],
      'enable_lazy_load'  => $options['enable_lazy_load'],
      'enable_lazy_load_threshold'  => $options['enable_lazy_load_threshold'],
      'enable_lazy_load_fadetime'  => $options['enable_lazy_load_fadetime'],
      'enable_lazy_load_manual'  => $options['enable_lazy_load_manual'],
      'pass_id_by_url'  => $options['pass_id_by_url'],
      'resize_on_element_resize'  => $options['resize_on_element_resize'],
      'resize_on_element_resize_delay'  => $options['resize_on_element_resize_delay'],
      'add_css_class_parent'  => $options['add_css_class_parent'],
      'auto_zoom'  => $options['auto_zoom'],
      'enable_lazy_load_manual_element'  => $options['enable_lazy_load_manual_element'],
      'show_iframe_as_layer'  => $options['show_iframe_as_layer'],
      'add_iframe_url_as_param'  => $options['add_iframe_url_as_param'],
      'add_iframe_url_as_param_prefix'  => $options['add_iframe_url_as_param_prefix'],
      'auto_zoom_by_ratio'  => $options['auto_zoom_by_ratio'],
      'reload_interval'  => $options['reload_interval'],
      'iframe_content_css'  => $options['iframe_content_css'],
      'additional_js_file_iframe'  => $options['additional_js_file_iframe'],
      'additional_css_file_iframe'  => $options['additional_css_file_iframe'],
      'add_css_class_iframe'  => $options['add_css_class_iframe'],
      'iframe_zoom_ie8'  => $options['iframe_zoom_ie8'],
      'enable_lazy_load_reserve_space'  => $options['enable_lazy_load_reserve_space'],
      'hide_content_until_iframe_color'  => $options['hide_content_until_iframe_color'],
      'use_zoom_absolute_fix'  => $options['use_zoom_absolute_fix'],
      'include_html'  => $options['include_html'],
      'enable_ios_mobile_scolling'  => $options['enable_ios_mobile_scolling'],
      'enable_ios_mobile_scrolling'  => 'not_set',
      'show_iframe_as_layer_header_file'  => $options['show_iframe_as_layer_header_file'],
      'show_iframe_as_layer_header_height'  => $options['show_iframe_as_layer_header_height'],
      'show_iframe_as_layer_header_position'  => $options['show_iframe_as_layer_header_position'],
      'resize_min_height'  => $options['resize_min_height'],
      'show_iframe_as_layer_full'  => $options['show_iframe_as_layer_full'],
      'show_part_of_iframe_zoom'  => $options['show_part_of_iframe_zoom'],
      'add_document_domain'  => $options['add_document_domain'],
      'document_domain'  => $options['document_domain'],
      'sandbox'  => $options['sandbox'],
      'use_post_message'  => $use_post_message,
      'multi_domain_enabled' => $multi_domain_enabled,
      'show_iframe_as_layer_keep_content' => $options['show_iframe_as_layer_keep_content'],
      'parent_content_css'  => $options['parent_content_css'],
      // this setting is only available in the shortcode as it is only needed in the special case if no footer is rendered.
      'include_scripts_in_content'  => $options['include_scripts_in_content'],
      'debug_js'  => $debug_js,
      'title' => $options['title']
       )
      , $atts));

      // fix of typo in $enable_ios_mobile_scolling - the shortcode can now handle both
      if ($enable_ios_mobile_scrolling  != 'not_set') {
          $enable_ios_mobile_scolling = $enable_ios_mobile_scrolling;
      }

  $id_check = shortcode_atts( array('src' => 'no_src','id' => 'no_id', 'name' => 'no_name'), $atts);

  if (empty ($id)) { $id = 'advanced_iframe'; }
  if (empty ($name)) { $name = 'advanced_iframe'; }

  // autovalue if no id is set but a src
  if ($id_check['src'] != 'no_src' &&  ($id_check['id'] == 'no_id' || $id_check['name'] == 'no_name')) {
      global $instance_counter;

      if (isset($instance_counter)) {
           $autoid =  $id . "_" . $instance_counter++;
       } else {
           $instance_counter = 2;
           $autoid =  $id;
       }
      // check if we have set id
      if ($id_check['id'] == 'no_id') {
         $id = $autoid;
      }
      // check if we have name - if not we first use the id if given - if not - autoname!
      if ($id_check['name'] == 'no_name') {
         if ($id_check['id'] != 'no_id') {
             $name = $id;
         } else {
             $name = $autoid;
         }
      }
  }


} else {
  // only the secrity key is read.
  extract(shortcode_atts(array('securitykey' => 'not set'), $atts));
}

if (!empty( $content )) {
 $src = $content;
}
}
// settings when you include an url which causes errors otherwise.
if (!empty($include_url)) {
$resize_on_element_resize = '';
$enable_lazy_load = false;
}

// TODO - check if the remote is really remote and set $enable_external_height_workaround!
// ignore for placeholders and sub domains.

// disable stuff that causes javascript errors when used used on an external domain!
if ($enable_external_height_workaround == "true") {
  $onload = '';
  $onload_resize = 'false';
  $resize_on_ajax = '';
  $resize_on_click = '';
  $resize_on_element_resize = '';
  $iframe_hide_elements = '';
  $iframe_content_styles = '';
  $iframe_content_id = '';
  $onload_show_element_only = '';
  $change_iframe_links = '';
  $change_iframe_links_target = '';
  $iframe_content_css = '';
  $additional_js_file_iframe = '';
  $additional_css_file_iframe = '';
  $add_css_class_iframe = 'false';
}

// check if the iframe is called inside wp-admin. if this is the case we
// disable the hide_unit_loaded stuff that is is shown there properly.
if (false !== strpos($_SERVER['REQUEST_URI'], 'wp-admin')) {
   $hide_page_until_loaded='false';
   $hide_page_until_loaded_external='false';
   $hide_content_until_iframe_color='';
}


// Settings defaults
// Invalid user input is replaced as good as possible
$enable_replace = true;

if (!empty($iframe_height_ratio)) {
 $onload_resize = 'false';
 $resize_on_ajax = '';
 $resize_on_click = '';
}
if (empty($resize_on_click_elements)) {
 $resize_on_click_elements = 'a';
}

if (!empty($add_iframe_url_as_param_prefix)) {
  $add_iframe_url_as_param_prefix = urlencode($add_iframe_url_as_param_prefix);
}

if ($add_iframe_url_as_param != 'false' && empty($map_parameter_to_url)) {
  $map_parameter_to_url = 'page';
}

$default_options = isset($aip_standalone) ? 1 : get_option('default_a_options');
$pro = true;
if (!file_exists(dirname(__FILE__) . "/class-cw-envato-api.php")) {
  if (empty($default_options) || (date('j') < 3)) { $default_options = 1; }
  update_option("default_a_options", ++$default_options);
  $pro = $enable_replace = false;
  $show_part_of_iframe = 'false';
  $hide_part_of_iframe = $change_parent_links_target = '';
  $change_iframe_links = $change_iframe_links_target = '';
  $url_forward_parameter = str_replace('|',',',$url_forward_parameter);
  $browser = $map_parameter_to_url = $iframe_zoom = '';
  $show_iframe_loader = $enable_lazy_load = 'false';
  $tab_visible = $tab_hidden = '';
  $enable_responsive_iframe = 'false';
  $iframe_height_ratio = $pass_id_by_url = $resize_on_element_resize = '';
  $add_css_class_parent = $auto_zoom = 'false';
  $reload_interval = $iframe_content_css = '';
  $additional_js_file_iframe = $additional_css_file_iframe = '';
  $add_css_class_iframe = $hide_content_until_iframe_color = '';
  $include_html = $show_iframe_as_layer = '';
  $enable_ios_mobile_scolling = $add_document_domain = 'false';
  $parent_content_css = '';
} else { $default_options = 0; }

if (!empty($iframe_zoom)) {
  $iframe_zoom = str_replace(',','.',$iframe_zoom);
}

// check ratio
if ($iframe_height_ratio == 'false') {
  $iframe_height_ratio = '';
}

$id = (empty ($id)) ? 'advanced_iframe' : preg_replace("/[^a-zA-Z0-9]/", "_", $id);
$name = (empty ($name)) ? 'advanced_iframe'  : preg_replace("/[^a-zA-Z0-9]/", "_", $name);

// end defaults
$this->addCustomCss($parent_content_css);

if ($auto_zoom == 'same' || $auto_zoom == 'remote') {
  $iframe_zoom = '1';
}

if ($enable_ios_mobile_scolling == 'true' || $browser != '' || (!empty($iframe_zoom) ||
   ($show_iframe_as_layer == 'true' || $show_iframe_as_layer == 'external'))) {
if (file_exists(dirname(__FILE__) . '/advanced-iframe-browser-detection.php')) {
    include_once dirname(__FILE__) . '/advanced-iframe-browser-detection.php';
    if ($browser != '' || (!empty($iframe_zoom))) {
      if (!is_selected_browser($browser,$id)) {
        $isValidBrowser = false;
      }
    }
}
}

// calculates dynamic width and height for + and -
if ($pro) {
if (strpos($width, '-') !== false || strpos($width, '+') !== false ) {
   // + and - needs a space before and after the + and -. Otherwise is does not work in Firefox
   $width = str_replace("-", " - ", $width);
   $width = str_replace("+", " + ", $width);
   $style .= ';width: calc('.esc_html(trim($width)).');';
   $width = '';
}
if (strpos($height, '-') !== false || strpos($height, '+') !== false ) {
   $height = str_replace("-", " - ", $height);
   $height = str_replace("+", " + ", $height);
   $style .= ';height: calc('.esc_html(trim($height)).');';
   $height = '';
}
}

$show_iframe_as_layer_div = false;
$show_iframe_as_layer_div_header = false;
$show_iframe_loader_layer = $show_iframe_loader;

if ($show_iframe_as_layer == 'true' || $show_iframe_as_layer == 'external') {
   $ios_scroll = $enable_ios_mobile_scolling == 'true' && ai_is_ios() && ai_is_mobile();
   if ($ios_scroll || !empty($show_iframe_as_layer_header_file)) {
     $show_iframe_as_layer_div = true;
   }
   $layer_width = $layer_height = '100%';
   if ($show_iframe_as_layer_full == 'true') {
     $layer_div_base = 'top:0;left:0;width:100%;height:100%;border: none';
   } else {
     $layer_div_base = 'top:2%;left:1.9%;width:96%;height:96%;border:solid 2px #eee';
     $layer_width = $layer_height = '96%';
     if ($show_iframe_as_layer_full == 'original') {
       $esc_width = esc_html($this->addPx($width));
       $esc_height = esc_html($this->addPx($height));
       $layer_width = $esc_width;
       $layer_height = $esc_height;
       $layer_div_base .= ';left:50%;top:50%;transform: translate(-50%,-50%);max-width:' . $esc_width . ';max-height:' . $esc_height;
     }
   }

   $layer_div_base .= ';background-color:#fff;display:none;position:fixed;z-index:100003;margin:0px !important;padding:0px !important;';
   if ($ios_scroll) {
     $width='100%';
     $height='100%';
     $style .= ";width:100%;height:100%;margin:0px !important;padding:0px !important;";
     if (empty($show_iframe_as_layer_header_file)) {
       $layer_div_style = "-webkit-overflow-scrolling: touch; overflow: auto;".$layer_div_base;
     } else {
       $layer_div_style = $layer_div_base;
       $show_iframe_as_layer_div_header = true;
       $adHeight = esc_html($this->addPx($show_iframe_as_layer_header_height));
       $layer_div_header_style = ";-webkit-overflow-scrolling: touch; overflow: auto;margin:0px !important;padding:0px !important;width:100%;height:calc(100% - ". $adHeight .")";
     }
   } else if (!empty($show_iframe_as_layer_header_file)) {
     // sticky ist normalles div und iframe scrollt mit calc height - 96%
     $layer_div_style = $layer_div_base;
     $height = '';
     $adHeight = esc_html($this->addPx($show_iframe_as_layer_header_height));
     $width='100%';
     $style .= ";display:none;margin:0px !important;padding:0px !important;height:calc(100% - ". $adHeight .")";
   } else {
     $width=$layer_width;
     $height=$layer_height;
     $style .= $layer_div_base;    
   }
   $src="about:blank";
   $show_part_of_iframe = 'false';
   $enable_lazy_load = 'false';
   $hide_part_of_iframe = '';
   $show_iframe_loader = 'false';
} else {
   // we only make the ios fix for features where it is not implemented directly.
   // show_iframe_as_layer and show_part_of_iframe. Also it is not enabled if we have
   // auto height. Zoom is not supported in the first iteration as it is more complicated!
   $use_ios_fix =  $enable_ios_mobile_scolling == 'true' && $scrolling != 'no' && empty($iframe_zoom) &&
     $show_part_of_iframe == 'false' && $onload_resize == 'false' &&
     ($enable_external_height_workaround == 'false' || $enable_external_height_workaround == 'external') && empty($hide_part_of_iframe);

   if ($use_ios_fix && ai_is_ios() && ai_is_mobile()) {
     $show_iframe_as_layer_div = true;
     // without height and width on ipad you have autoheight!
     $layer_div_style = $style . ';-webkit-overflow-scrolling: touch; overflow: auto;width:'.$this->addPx($width).';height:'.$this->addPx($height) . ';';
     $width='100%';
     $height='100%';
     $style = "width:100%;height:100%";
   }
}
?>