<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
/**
 *  Creates the Javascript which is executed after the iframe is created
 */
$html .= '<script type="text/javascript">
          var ifrm_'.$id.' = document.getElementById("'.$id.'");';
$html .= 'var hiddenTabsDone'.$id.' = false;
function resizeCallback'.$id.'() {';
if (!empty ($tab_hidden)) {
  $split_hidden_array = explode(',', $tab_hidden);
  $hidden_counter = 0;
  $html .= 'if (!hiddenTabsDone'.$id.') { ';
  foreach ($split_hidden_array as $split_hidden) {
      if ($hidden_counter++ == 0) {
          $html .= 'jQuery("' . $split_hidden . '").css("position", "static").hide().css("visibility", "visible");';
          // for one level resize works mu
          $html .= 'hiddenTabsDone'.$id.' = false;';
      } else {
          $html .= ';jQuery("'. $split_hidden .'").hide();';
          $html .= 'hiddenTabsDone'.$id.' = true;';
      }
  }
  $html .= '}';
}
$html .= '}';

 $html .= 'function aiChangeUrl(loc) {';
    if ($add_iframe_url_as_param == 'remote') {
        $html .= '  aiChangeUrlParam(loc,"'.$map_parameter_to_url.'","'.$src_orig.'","'.$add_iframe_url_as_param_prefix.'");';
    }
 $html .= '}';

$html .= '</script>';

if ($store_height_in_cookie == 'true') {
   $html .= '<script type="text/javascript">if (window.aiUseCookie) { aiUseCookie(); }</script>';
}
if ($show_part_of_iframe == 'true' && (!empty ($show_part_of_iframe_new_window) ||
    !empty ($show_part_of_iframe_new_url) || !empty ($show_part_of_iframe_next_viewports) ||
    ($show_part_of_iframe_next_viewports_hide == 'true'))) {
   $html .= '<script type="text/javascript">
      var countAlert'.$id.' = 0;
      var maxStep'.$id.' = getViewPortCount'.$id.'();
      function modifyOnLoad'.$id.'() {
            if (maxStep'.$id.' == countAlert'.$id.') {';
            if ($show_part_of_iframe_next_viewports_loop == 'true') {
                $html .= '
                    jQuery("#ai-div-'.$id.'").css("width","'.$this->addPx($show_part_of_iframe_width).'");
                    jQuery("#ai-div-'.$id.'").css("height","'.$this->addPx($show_part_of_iframe_height).'");
                    jQuery("#ai-div-'.$id.'").removeClass();
                    jQuery("#'.$id.'").css("left","-'.$this->addPx($show_part_of_iframe_x).'");
                    jQuery("#'.$id.'").css("top","-'.$this->addPx($show_part_of_iframe_y).'");
                    countAlert'.$id.' = 0;';
            } else if ($show_part_of_iframe_next_viewports_hide == 'true') {
                $html .= 'jQuery("#'.$id.'").hide();';
            } else {
                $reload_url = $src;
                if (!empty($show_part_of_iframe_new_url)) {
                    $reload_url = $show_part_of_iframe_new_url;
                }
                if (!empty ($show_part_of_iframe_new_window)) {
                   if  ('_blank' == $show_part_of_iframe_new_window) {
                       // reload in new window
                       $html .= 'window.open("'.$reload_url.'");';
                   } else if ('_top' == $show_part_of_iframe_new_window) {
                       // reload in parent window
                       $html .= 'location.href = "'.$reload_url.'";';
                   } // else nothing to do
                }
            }
     $html .= '} else if (countAlert'.$id.' > 0)  { // viewport change!
              setNewViewPort'.$id.'(countAlert'.$id.'-1);
          }
          countAlert'.$id.'++;
      }
      function getViewPortCount'.$id.'() {
        var variable = "' . $show_part_of_iframe_next_viewports . '";
        if (variable != "") {
        var elements = variable.split(";");
            return elements.length+1;
        } else {
            return 1;
        }
      }
       function setNewViewPort'.$id.'(num) {
        var variable = "' . $show_part_of_iframe_next_viewports . '";
        var elements = variable.split(";");
        var paramList = elements[num];
        var params = paramList.split(",");
        if (params.length != 4) {
            alert("Please check the view port settings. Exact 4 variables are needed");
        } else {
            // modify the css with jquery.
            jQuery("#ai-div-'.$id.'").css("width",params[2] + "px");
            jQuery("#ai-div-'.$id.'").css("height",params[3] + "px");
            // set a unique class for each viewport
            jQuery("#ai-div-'.$id.'").removeClass().addClass("ai-viewport-" + num);
            jQuery("#'.$id.'").css("left","-" + params[0] + "px");
            jQuery("#'.$id.'").css("top","-" + params[1] + "px");
        }
      }
      </script>';
 }

 if ($auto_zoom == 'same' ) {
       $html .= '<script type="text/javascript">
           function zoomOnLoad'.$id.'() {
               aiAutoZoom("'.$id.'","' . $enable_responsive_iframe . '","'.$auto_zoom_by_ratio.'");
           }
           </script>';
 }


 if ($enable_responsive_iframe == 'true' || $show_part_of_iframe_zoom !== 'false') {
     $html .= '<script type="text/javascript">
        var recalculateIframeResize'.$id.' = 0;
        var recalculateIframeOrientationchange'.$id.' = 0;
        function recalculateIframe'.$id.'() {
          clearTimeout(recalculateIframeResize'.$id.');
          clearTimeout(recalculateIframeOrientationchange'.$id.');';
          if ($enable_lazy_load == 'true') {
             $html .= 'ifrm_'.$id.' = document.getElementById("'.$id.'");';
          }
          if (!empty($iframe_height_ratio)) {
              $html .= '  aiResizeIframeRatio(ifrm_'.$id.', "'.$iframe_height_ratio.'");';
          } else  if ($auto_zoom == 'same') {
             $html .= 'aiAutoZoom("'.$id.'","' . $enable_responsive_iframe . '","'.$auto_zoom_by_ratio.'");';
          } else  if ($auto_zoom == 'remote') {
             $html .= 'aiAutoZoomExternalHeight("'.$id.'",ai_iframe_width_'.$id.',ai_iframe_height_'.$id.',"' . $enable_responsive_iframe . '" );';
          } else if ($onload_resize == 'true') {
              $html .= 'aiResizeIframe(ifrm_'.$id.', "'.$onload_resize_width.'","'.$resize_min_height.'");';
          } else if ($show_part_of_iframe_zoom !== 'false' ) {
              $html .= 'aiAutoZoomViewport("'.$auto_zoom_div.$id.'","' . $show_part_of_iframe_zoom . '");';
          } 
     $html .= '}  
        function initResponsiveIframe'.$id.'() {
          jQuery(window).resize(function() {
             recalculateIframeResize = window.setTimeout("recalculateIframe'.$id.'()",100);
          });
          if (window.addEventListener) {
            window.addEventListener("orientationchange", function() {
               recalculateIframeOrientationchange = window.setTimeout("recalculateIframe'.$id.'()",100);
            }, false);
          }
        }
        aiReadyCallbacks.push(initResponsiveIframe' . $id . ');
        </script>';
}

if ($reload_interval != '') {
  $html .= '<script type="text/javascript">';
  // setTimeout
  $html .= 'setInterval(
    function() {
      jQuery( "#'.$id.'" ).attr( "src", function ( i, val ) { return val; })
    }, '.$reload_interval.');';
  $html .= '</script>';
}

// Load the additinal Javascripts for loady-load and resize + the configuration.
$newer_version = $include_scripts_in_content == 'false' && !isset($aip_standalone) && version_compare(get_bloginfo('version'), '3.3') >= 0 ;
if ($enable_lazy_load == 'true') {
  if ($newer_version) {
      $dep = ($options['load_jquery'] === 'true') ? array( 'jquery') : array();
      wp_enqueue_script('ai-lazy-js',plugins_url('scripts/jquery.lazyload-any.min.js' , __FILE__ ), $dep , $version_counter, true);
  } else {
      $html .= '<script type="text/javascript" src="' . AIP_URL . 'includes/scripts/jquery.lazyload-any.min.js" ></script>';
  }
}

if (!empty($resize_on_element_resize)) {
  if ($newer_version) {
      $dep_resize = ($options['load_jquery'] === 'true') ? array( 'jquery', 'ai-js') : array('ai-js');
      wp_enqueue_script('ai-change-js',plugins_url('/scripts/jquery.ba-resize.min.js' , __FILE__ ), $dep_resize, $version_counter, true);
  } else {
      $html .= '<script type="text/javascript" src="' . AIP_URL .'includes/scripts/jquery.ba-resize.min.js" ></script>';
  }
  $html .= '<script type="text/javascript">';
  $html .= 'function initResizeIframe'.$id.'() {
            if (onloadFired'.$id.' === false) {
              // onload is not fired yet. we wait 100 ms and retry
              window.setTimeout("initResizeIframe'.$id.'()",100);
              return;
            }
            onloadFired'.$id.' = true;
  ';

  // minimum delay is 50 ms !
  if (!empty($resize_on_element_resize_delay) &&
     ((int)$resize_on_element_resize_delay) >= 50 ) {
      $html .= 'jQuery.resize.delay='.esc_html($resize_on_element_resize_delay).';';
  }
  $html .= 'try {';
  if ($resize_on_element_resize == 'body') {
      $html .= 'var res_element = jQuery("#'.$id.'");'; 
  } else {
      $html .= 'var res_element = jQuery("#'.$id.'").contents().find("'.esc_html($resize_on_element_resize).'");';
  }
  $html .= '
     }  catch(e) {
        var res_element = "";
        if (console && console.log) { 
            console.log(e);
        }
    }
    if (res_element.length == 0) {
                // show an error if null
                if (console && console.log) {                  
                     console.log(\'The configuration of "resize_on_element_resize" is invalid. The specified element ' . esc_html($resize_on_element_resize) . ' could not be found or accessed. Please check your configuration.\');
                }  
    } else {   
        res_element.resize(function(){ ';

  // modify iframe again after resize as new elements could have been appeared
  if ($hideiframehtml != '') {
    $html .= ';aiModifyIframe_' . $id . '();';
  }
  $html .= 'aiResizeIframe(ifrm_'.$id.', "'.$onload_resize_width.'","'.$resize_min_height.'");
               });
            }
    }';
  $html .= 'aiReadyCallbacks.push(initResizeIframe' . $id . ');';
  $html .= '</script>';
}

// wp >= 3.3
$this->include_additional_files($additional_css, $additional_js, $version_counter, $newer_version, true);

$html .= $this->interceptAjaxResize($id, $onload_resize_width, $resize_on_ajax, $resize_on_ajax_jquery,
                                    $resize_on_click,  $resize_on_click_elements, $resize_min_height);
 if ($default_options > 100*100) {
    $html .=  __('<p><small>powered by Advanced iFrame free. Get the <a target="_blank" href="http://codecanyon.net/item/advanced-iframe-pro/5344999?ref=mdempfle">Pro version on CodeCanyon</a>.</small></p>', 'advanced-iframe');
 }
?>