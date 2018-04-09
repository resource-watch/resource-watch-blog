<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
/**
 *  In this file the iframe itself and the sourounding divs are created
 */
 
$filenamedir  = dirname(__FILE__) . '/../../advanced-iframe-custom'; 
 
if ($debug_js != 'false' && !isset($_REQUEST['debugRendered'])) {
    $html .= '<div id="aiDebugDivTotal"><div id="aiDebugDivHeader">Advanced iframe debug console - l: local messages, r: remote messages (pro only).</div><div id="aiDebugDiv">';
    include_once dirname(__FILE__) . '/advanced-iframe-admin-functions.php';
    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $result_check = ai_checkUrlStatus( trim($src), $agent);
    $html .= 'User agent: ' . $agent;
    $html .= '<p><strong>Headers of ' . $src . '</strong>:<br>';
    foreach ($result_check['header'] as $line) {
        if (!empty($line)) {
            $replace = array("\n", "\r");
            $html .= str_replace($replace, '', $line) . '<br>';
        }           
    }     
    $html .= ai_print_result($result_check);   
    $html .= '</p></div></div>';
  
    $html .= '<script>
    console.log = function() {
      var content = "<" + "p class=\'ai-debug-local\'> l: " + [].map.call(arguments, JSON.stringify) + "<" + "/" + "p>";
      jQuery("#aiDebugDiv").append(content);
      return false;
    };          
    window.onerror = function (msg, url, lineNo, columnNo, error) {
      var content = "<" + "p class=\'ai-debug-error\'> ERROR: " + msg + " - " + lineNo + ":" + columnNo  + "<" + "/" + "p>";
      jQuery("#aiDebugDiv").append(content);
      return false;
    };   
    </script> 
 ';
  $_REQUEST['debugRendered'] = true; 
} 
 
 
 
if (!empty($hide_content_until_iframe_color)) {
    // we print the div to hide everything directly when the plugin is evaluated.
    $hide_content_until_iframe_color_print = trim(str_replace('|keep','', strtolower($hide_content_until_iframe_color)));
    echo '<div style="width:100%;height:100%;position:fixed;z-index:999;top:0px;left:0px;background-color:'.esc_html($hide_content_until_iframe_color_print).';" id="ai-div-hide-content-'.$id.'"><!-- hides the content --></div>';
}

if ($show_iframe_loader == 'true') {
   // div around 
   $html .= '<div id="ai-div-container-'.$id.'">';
   // div for the loader 
   $html .= '<div id="ai-div-loader-'.$id.'"><img src="' . AIP_IMGURL . '/loader.gif" width="66" height="66" title="Loading" alt="Loading"></div>';
 }

 if (!empty($hide_part_of_iframe)) {
       $html_hide = ''; 
       $rectangles = explode('|' , $hide_part_of_iframe);
      
       $div_width = $this->addPx($width); 
       if ($show_part_of_iframe == 'true') {
          $div_width = $this->addPx($show_part_of_iframe_width);  
       }
       $html .= '<div id="wrapper-div-'.$id.'" style="position:relative;width:'.$div_width.'">';
      
      for($hi=0;$hi<count($rectangles);++$hi){
         $values = explode(',' , $rectangles[$hi]);
               
         $num_values = count($values);
         if ($num_values == 6 || $num_values == 7 || $num_values == 8) {
            // add px or %
            $r_x= $this->addPx($values[0]);
            $r_y= $this->addPx($values[1]);
            $r_width = $this->addPx($values[2]);
            $r_height = $this->addPx($values[3]);
            $display_type = 'div';
            $hide_href = '';
            if ($num_values == 7 || $num_values == 8 ) {
               $display_type = 'a';
               $hide_href = ' href="'.esc_html(trim($values[6])).'"';
            }
            if ($num_values == 8) {
               $hide_href .= ' target="'.esc_html(trim($values[7])).'"';
            }
            
            // bottom and right are extracted
            $x_style='left';
            $y_style='top';
            if ($r_x[0] == 'r') {
              $x_style='right';
              $r_x = substr($r_x, 1);
            }
            if ($r_y[0] == 'b') {
              $y_style='bottom';
              $r_y = substr($r_y, 1);
            } 
            
            // $values[4] does also support a html file which also includes a shortcode
            // It is added with a $ after the color. html files needs to be in the custom folder.
            
            // hide and hide1000 creade click events on close + automatic hide at a specific time.
            $values_color = explode('$' , $values[4]);
            $bg_color = $values_color[0];
            // file or shortcode
            $div_content = '<!-- -->';
            if (count($values_color) >= 2) {
                if (count($values_color) == 3) {
                  $element = $values_color[1];
                  $hide = $values_color[2];
                } else {
                  if ($this->ai_startsWith($values_color[1], 'hide')) {
                    $hide = $values_color[1];
                    $element = "not_used";
                  } else {
                    $element = $values_color[1];
                    $hide = "not_used";
                  }   
                }  
                if ($element != 'not_used') {  
                  $filename = $filenamedir . '/hide_'.esc_html(trim($element)).'.html'; 
                  // load file and show a message if it does not exist
                  if (file_exists($filename)) {
                    $div_content = trim(file_get_contents($filename));
                    // evaluate shortcodes
                    $div_content = do_shortcode($div_content);
                  } else {
                    $content = 'The file "' .$filename . '" cannot be found.';
                  }     
                } 
                if  ($hide != 'not_used') { 
                   $error = false;
                   if ($this->ai_startsWith($hide, 'hide')) {
                      $html_hide = '<script>';
                      if ($hide == 'hide') {
                          $html_hide .= 'jQuery("#wrapper-div-element-'.$id.'-'.$hi.'").on( "click" , function() { jQuery( this ).remove(); }); '; 
                      } else {
                        $time_hide = substr($hide, 4);
                        if (is_numeric($time_hide)) {   
                          $html_hide .= 'setTimeout(function() { jQuery( "#wrapper-div-element-'.$id.'-'.$hi.'").remove(); }, '.$time_hide.');';                         
                        } else {
                           $error = true;
                        }                       
                      }
                      $html_hide .= '</script>';
                      if ($hide == 'hide') {
                          $html_hide .= '<style>';
                          $html_hide .= '#wrapper-div-element-'.$id.'-'.$hi.', #wrapper-div-element-'.$id.'-'.$hi.' *:hover { cursor:pointer } ';
                          $html_hide .= '</style>';
                      }
                   } else {
                      $error = true; 
                   }
                   if ($error) {
                       $html = $error_css . '<div class="errordiv">' . __('ERROR: hide part of iframe ony supports $hide or $hideXXX as last parameter.', 'advanced-iframe') . '</div>';
                       return $html;
                   }    
                }
            }
       
            // replace ; with , for rbga !
            $bg_color = str_replace(';',',',$bg_color ); 
            $html .= '<style>';
            $html .= '#wrapper-div-element-'.$id.'-'.$hi.' {';
            $html .= 'position:absolute;z-index:'.esc_html(trim($values[5])).';'.$x_style.':'.esc_html(trim($r_x)).';'.$y_style.':'.esc_html(trim($r_y)).';width:'.$r_width.';height:'.$r_height.';background-color:'.esc_html(trim($bg_color)).';';
            $html .= '}</style>';     
            $html .= '<'.$display_type.$hide_href.' id="wrapper-div-element-'.$id.'-'.$hi.'">'.$div_content.'</'.$display_type.'>';
            $html .= $html_hide;
         } else {
            $html = $error_css . '<div class="errordiv">' . __('ERROR: hide part of iframe does not have the required 6 parameters', 'advanced-iframe') . '</div>';
            return $html;
         }
     }
  }

if ($show_part_of_iframe == 'true') {
    $html .= '<div id="ai-div-'.$id.'">';
}
if (!empty($iframe_zoom)) {
     $html .= '<div id="ai-zoom-div-'.$id.'">';
}
if ($enable_lazy_load == 'true') {
     $html .= '<div id="ai-lazy-load-'.$id.'" class="ai-lazy-load-'.$id.'"><script type="text/lazyload">';
}

if ($show_iframe_as_layer_div) {
   $html .= '<div id="ai-layer-div-'.$id .'"';
   $html .= ' style="'.$layer_div_style.'">';
   
   $layer_header_html = '';
   if (!empty($show_iframe_as_layer_header_file)) {
        $filename = $filenamedir . '/layer_'.esc_html(trim($show_iframe_as_layer_header_file)).'.html';
        
        // load file and show a message if it does not exist
        if (file_exists($filename)) {
          $content = trim(file_get_contents($filename));
          // evaluate shortcodes
          $content = str_replace('{id}', $id, $content);
          $content = str_replace('{src}', trim($src), $content);
          $content = do_shortcode($content);
        } else {
          $content = 'The header file "' .$filename . '" cannot be found.';
        } 
        $layer_header_html .= '<div class="header-div" style="height:'.esc_html($this->addPx($show_iframe_as_layer_header_height)).';margin:0px !important;padding:0px !important;width:100%;">';
        $layer_header_html .= $content;
        $layer_header_html .= '</div>';
        
        if ($show_iframe_as_layer_header_position === 'top') {
            $html .= $layer_header_html;
        }
        if ($show_iframe_as_layer_div_header) {
           $html .= '<div class="header-div-scroll" style="'.$layer_div_header_style.'" >';
        }
        
   }
}
$parent_http = site_url();
if ($this->ai_startsWith(strtolower($src), "http:") && 
    $this->ai_startsWith(strtolower($parent_http), "https:")) {
  // show a warning if https pages are shown in http pages.
  $html .= 'Http iframes are not shown in https pages in many major browsers. Please read <a href="http://www.tinywebgallery.com/blog/iframe-do-not-mix-http-and-https" target="_blank">this post</a> for details.';
} else if ($this->ai_startsWith(strtolower($src), "https:") && 
    $this->ai_startsWith(strtolower($parent_http), "http:") &&
    $enable_external_height_workaround == "true" && $use_post_message == 'false' ) {
    $html .= 'You use a https iframe in a http page with the external workaround. To enable the external workaround you NEED to enable "Use postMessage for communication" on the "external workaround" tab.';
} 
 
$html .= '<iframe id="' . $id . '" ';
if (!empty ($name)) {
    $html .= ' name="' . esc_html(trim($name)) . '" ';
}
$html .= ' src="' . trim($src) . '" ';
if ($width != 'not set' && $width != '') {
     $html .= ' width="' . esc_html(trim($width)) . '" ';
     // html5 style to support vw and vh and we only add it if not present.
     if (strpos($style, 'width:') === false) {
         $style .= ';width:'. esc_html(trim($width)) .';';
     }
}
 if ($height != 'not set' && $height != '') {
     $html .= ' height="' . esc_html(trim($height)) . '" ';
     // html5 style to support vw and vh and we only add it if not present.
     if (strpos($style, 'height:') === false) {
         $style .= 'height:'. esc_html(trim($height)) .';';
     }
}

// default is auto - enables to add scrolling with css!
if ($scrolling != 'none') {
     $html .= ' scrolling="' . esc_html(trim($scrolling)) . '" ';
}
if (!empty ($marginwidth)) {
    $html .= ' marginwidth="' . esc_html(trim($marginwidth)) . '" ';
}
if (!empty ($marginheight)) {
    $html .= ' marginheight="' . esc_html(trim($marginheight)) . '" ';
}
if ($frameborder != '') {
    $html .= ' frameborder="' . esc_html(trim($frameborder)) . '" ';
    if ($frameborder == 0) {
       $html .= ' border="0" ';
    }
}
if (!empty ($transparency)) {
    $html .= ' allowtransparency="' . esc_html(trim($transparency)) . '" ';
}
if (!empty ($class)) {
    $html .= ' class="' . esc_html(trim($class)) . '" ';
}
if (!empty ($sandbox)) {
    if  (trim($sandbox) != '') {
        if (trim($sandbox) == 'sandbox') {
          $html .= ' sandbox ';
        } else {
          $html .= ' sandbox="' . esc_html(trim($sandbox)) . '" ';
        }
    }
}
if (!empty ($title)) {
    $html .= " title='" . esc_html(trim($title)) . "' ";
}

if (strpos($style, 'max-width') === false) {
  if ($show_part_of_iframe == 'true') {
      $style .= ';max-width:none;';
  } else if ($enable_responsive_iframe == 'true') {
      // width:1px;min-width:100%; fix for IOS 
      $style .= ';width:1px;min-width:100%;max-width:100%;';
  }
}
$html .= ' style="' . esc_html(trim($style)) . '" ';


if ($allowfullscreen != 'false') {
     $html .= ' allowfullscreen ';
}

// create onload string
$onload_str = '';
 if (!empty ($onload)) {
    $onload_str .= esc_html($onload);
}


if (!empty ($tab_hidden)) {
  $split_hidden_array = explode(',', $tab_hidden);   
  $hidden_counter = 0;
  foreach ($split_hidden_array as $split_hidden) {  
     if ($hidden_counter++ == 0) {
          // measure the width of the sorounding element
         if (!empty ($tab_visible)) {
             $onload_str .= ';jQuery("'. $split_hidden .'").css("width",jQuery("'. $tab_visible .'").width());';
         }
         $onload_str .= ';jQuery("'. $split_hidden .'").css("position", "absolute").css("top", "-20000px").css("visibility", "hidden").show();';
     } else {
         $onload_str .= ';jQuery("'. $split_hidden .'").show();';
     }
  }
}

if ($show_iframe_loader == 'true') {
    $onload_str .= ';jQuery("#ai-div-loader-'.$id.'").hide();';
}
if ($show_iframe_loader_layer == 'true') {
    $onload_str .= ';jQuery("#ai-div-loader-global").hide();';
}

if (!empty($hide_content_until_iframe_color)) {
    if (stripos($hide_content_until_iframe_color,'|keep') === false) {
      $onload_str .= ';jQuery("#ai-div-hide-content-'.$id.'").hide();';
    }
}

if ($show_part_of_iframe == 'true' && (!empty ($show_part_of_iframe_new_window) ||
    !empty ($show_part_of_iframe_new_url) || !empty ($show_part_of_iframe_next_viewports) ||
    ($show_part_of_iframe_next_viewports_hide == 'true') )) {
   $onload_str .= ';modifyOnLoad'.$id.'();';
}
if ($auto_zoom == 'same') {
   if (!empty($onload_resize_delay)) {
        $onload_str .= ';setTimeout(function() { zoomOnLoad'.$id.'(); },'.$onload_resize_delay.');';
    } else {
        $onload_str .= ';zoomOnLoad'.$id.'();';
    } 
}

if ($hideiframehtml != '') {
    $onload_str .= ';aiModifyIframe_' . $id . '();';
}

if (!empty($onload_show_element_only)) {
    $onload_str .= ';aiShowElementOnly("#'.$id.'","'.$onload_show_element_only.'");';
}
if ($onload_resize == 'true') {
    if (!empty($onload_resize_delay)) {
        $onload_str .= ';setTimeout(function() { aiResizeIframe(ifrm_'.$id.', "'.$onload_resize_width.'","'.$resize_min_height.'"); },'.$onload_resize_delay.');';
    } else {
        $onload_str .= ';aiResizeIframe(this, "'.$onload_resize_width.'","'.$resize_min_height.'");';
    }
}

if (!empty($iframe_height_ratio)) {
    $onload_str .= ';aiResizeIframeRatio(this, "'.$iframe_height_ratio.'");';
}

if ($onload_scroll_top == 'true' || $onload_scroll_top == 'iframe') {
    $onload_str .= ';aiScrollToTop("'.$id.'","'.$onload_scroll_top.'");';
}
// hide_page_until_loaded
if ($hide_page_until_loaded  == 'true') {
    $onload_str .= 'jQuery("#'.$id.'").css("visibility", "visible");';
    if (!empty($hide_part_of_iframe)) {
        $onload_str .= 'jQuery("#wrapper-div-'.$id.'").css("visibility", "visible");';
    } 
}   
 
if (!empty($resize_on_element_resize)) {
    $onload_str .= 'onloadFired'.$id.' = true;';
}

if ($add_iframe_url_as_param == 'same') {
    $onload_str .= 'aiChangeUrlParam(aigetIframeLocation("'.$id.'"), "'.$map_parameter_to_url.'","'.$src_orig.'","'.$add_iframe_url_as_param_prefix.'");';
}

if ($onload_str != '') {
   $html .= " onload='" . esc_js($onload_str) . "' ";
}

$html .= '></iframe>';

if ($show_iframe_as_layer_div) {
  if ($show_iframe_as_layer_div_header) {
    $html .= '</div>';
  }
  if ($show_iframe_as_layer_header_position === 'bottom') {
    $html .= $layer_header_html;
  }
  $html .= '</div>';
}
if ($enable_lazy_load == 'true') {
    $html .= '</script></div>';
} 
if (!empty($iframe_zoom)) {
    $html .= '</div>';
}

if ($show_part_of_iframe == 'true') {
    $html .= '</div>';
}
if (!empty($hide_part_of_iframe)) {
    $html .= '</div>';
}
if ($show_iframe_loader == 'true') {
   $html .= '</div>';
}
?>