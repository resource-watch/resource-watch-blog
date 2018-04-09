<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
/**
 *  Prepares Javascript and values for the iframe
 */ 
if ($include_scripts_in_content == 'true') {
    $html .= '<script type="text/javascript" src="' . plugins_url() . $aiPath . '/js/ai.js" ></script>';
}  
$html .= '<script type="text/javascript">';
$html .= '  var ai_iframe_width_'.$id.' = 0;';
$html .= '  var ai_iframe_height_'.$id.' = 0;';

if ($add_document_domain == 'true') {
   $html .= 'document.domain="'.esc_html($document_domain).'";'; 
}

if ($use_post_message != 'false') {

    $iframe_origin_full = $src;
    $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https:' : 'http:';
    if ($this->ai_startsWith($src, '//')) {
      $iframe_origin_full = $protocol . $iframe_origin_full; 
    } else if (!$this->ai_startsWith($src, 'http')) {
       $iframe_origin_full = $protocol . '//'.$_SERVER['HTTP_HOST'] . '/';    
    }
    $iframe_origin_parts = parse_url($iframe_origin_full);
    $iframe_origin = $iframe_origin_parts['scheme'] . '://' . $iframe_origin_parts['host'];
    
    $html .= 'function receiveMessage'.$id.'(event) {';
    
    if ($use_post_message == 'debug') {
      $html .= '    if (console && console.log) {';
      $html .= '        console.log("postMessage received: " + event.data + " - origin: " + event.origin);';
      $html .= '    }';
    }
    
    if ($multi_domain_enabled == 'false') {
      $html .= "if (event.origin !== '". $iframe_origin ."') {return;}"; 
    }
    
    // this is a special file that can be included to convert postMessage events 
    // from non ai pages. 
    $filenamedir  = dirname(__FILE__) . '/../../advanced-iframe-custom';
    $post_js_filename = $filenamedir . '/ai_post_message_converter_'.$id.'.js';
    $post_js_filename_old = $filenamedir . '/ai_post_message_converter.js';
    if (file_exists($post_js_filename)) {
      $html .=  trim(file_get_contents($post_js_filename));
      $html .= 'event = aiConvertPostMessage(event);';
    } else  if (file_exists($post_js_filename_old)) {
      $html .=  trim(file_get_contents($post_js_filename_old));
      $html .= 'event = aiConvertPostMessage(event);';
    }
    $html .= '  aiProcessMessage(event,"'.$id.'", "'.$use_post_message.'");';
    $html .= '}';
    $html .= 'if (window.addEventListener) {';
    $html .= '  window.addEventListener("message", receiveMessage'.$id.');'; 
    $html .= '} else if (el.attachEvent)  {';
    $html .= '  el.attachEvent("message", receiveMessage'.$id.');';
    $html .= '}';      
    // $html .= 'window.addEventListener("message", receiveMessage'.$id.');';
}

if (version_compare(PHP_VERSION, '5.3.0') >= 0 && (!empty($iframe_zoom) || !empty($show_part_of_iframe_zoom) )) { 
  $html .= ($enable_ie_8_support) ? 'var aiIsIe8=true;' : 'var aiIsIe8=false;';
} else {
  $html .= 'var aiIsIe8=false;';
}
if ($store_height_in_cookie == 'true') {
    $html .=  'var aiEnableCookie=true; aiId="' . $id . '";';
}
if ($additional_height != 0) {
    $html .=  'var aiExtraSpace=' . esc_html($additional_height) . ';';
}
if (!empty($iframe_zoom)) {
    $html .= ' var zoom_' . $id.' = ' .esc_html($iframe_zoom). ';'; 
}
// $html .= 'var aiReadyCallbacks = ( typeof aiReadyCallbacks !== \'undefined\' && aiReadyCallbacks instanceof Array ) ? aiReadyCallbacks : [];'; 
// is written like this to avoid && which is encoded to &#038;&#038; depending on the wordpress settings!

$html .= '
if (typeof aiReadyCallbacks === \'undefined\') {
    var aiReadyCallbacks = [];  
} else if (!(aiReadyCallbacks instanceof Array)) {
    var aiReadyCallbacks = [];
}';

$html .= 'var onloadFired'.$id.' = false; '; 
$html .= '    function aiShowIframeId(id_iframe) { jQuery("#"+id_iframe).css("visibility", "visible");';
if (!empty($hide_part_of_iframe)) {
    $html .= '        jQuery("#wrapper-div-"+id_iframe).css("visibility", "visible");';
}
$html .= '    }';

$html .= '    function aiResizeIframeHeight(height) { aiResizeIframeHeight(height,'.$id.'); }'; 
  // the external height is rendered always for easier configuration
  $html .= '    function aiResizeIframeHeightId(height,width,id) {'; 
  if ($auto_zoom == 'remote') { 
      $html .= '   aiAutoZoomExternal(id, width,"' . $enable_responsive_iframe . '");';
      $html .= '   ai_iframe_width_'.$id.' = width;';
      $html .= '   ai_iframe_height_'.$id.' = height;';
  }
  if (!empty($iframe_zoom)) { 
    $html .= ' var zoom_height = parseInt(height * parseFloat(window["zoom_" + id]))+1;';
    $html .= ' jQuery(\'#ai-zoom-div-\' + id).css("height",zoom_height);';
  }            
  if ($show_part_of_iframe === 'true') {
    $html .= ' resetShowPartOfAnIframe(id);';
  }
  $html .= 'aiResizeIframeHeightById(id,height);';
  $html .= '}';
  // end aiResizeIframeHeightId
$html .= '</script>';


// add parameters
if ($url_forward_parameter != '') {
    $sep = (strpos($src, '?') === false)? '?': "&amp;";
    if ($url_forward_parameter == 'ALL') {
        $parameters = array();
        foreach ($_GET as $key => $value) {
            $parameters[] = $key;
        }
        foreach ($_POST as $key => $value) {
            $parameters[] = $key;
        }  
    } else {
        $url_forward_parameter = str_replace('{{', "[", $url_forward_parameter);
        $url_forward_parameter = str_replace('}}', "]", $url_forward_parameter);
        $parameters = explode(",", $url_forward_parameter);
    }
    foreach ($parameters as $parameter) {
        // check for mapping urlname|iframe name
        $parameter_mapping = explode("|", $parameter);
        if (count($parameter_mapping) == 1) {
            $parameter_mapping[1] = $parameter_mapping[0];
        }
        $read_param_url = $this->param($parameter_mapping[0]);
        if ($read_param_url != '') {
            $src .= $sep . $parameter_mapping[1] . "=" . ($read_param_url);
            $sep = "&amp;";
        }
    }
}

if (!empty($pass_id_by_url)) {
    $sep = (strpos($src, '?') === false)? '?': "&amp;";
    $src .= $sep . $pass_id_by_url . "=" . $id;  
}  
  
// Evaluate shortcodes and replace placeholders for the src - they are not encoded! 
// This has to be done by the shortcode that is used
$src = AdvancedIframeHelper::ai_replace_placeholders($src , $enable_replace, $aip_standalone);

$src_orig = $src;
if (!empty($map_parameter_to_url)) {
    $parameters = explode(",", $map_parameter_to_url); 
    foreach ($parameters as $parameter) {
        // check for mapping parameter|value|url
        $parameter_url_mapping = explode("|", $parameter);
         if (count($parameter_url_mapping) == 3) {
            $read_param_url = $this->param($parameter_url_mapping[0]);
            if ($read_param_url == $parameter_url_mapping[1]) {
                $src = $parameter_url_mapping[2]; 
            }  
         } else if (count($parameter_url_mapping) == 1) {
            $src_url = $this->param($parameter_url_mapping[0]);
            if (!empty($src_url)) { 
                $src = urldecode($src_url);   
                $prefix = urldecode($add_iframe_url_as_param_prefix);
                if (!$this->ai_startsWith($src,"http")) {
                   if ($this->ai_startsWith($src,"s|")) { 
                     $src = "https://" . $prefix . substr($src,2);
                   } else if ($this->ai_startsWith($src_orig,"https")) {
                     $src = "https://" . $prefix . $src;
                   } else {
                     $src = "http://" . $prefix . $src;
                   } 
                }  
            }
         } else {
            return $error_css . '<div class="errordiv">' . __('ERROR: map_parameter_to_url does not have the required 1 or 3 parameters', 'advanced-iframe') . '</div>';
         }
    }        
}

// pdf
if ($this->ai_endsWith($src, '.pdf')) {
    if ($this->ai_startsWith($src, 'NATIVE:')) {
       $src = substr($src, 7);
    } else {
       $src = '//docs.google.com/gview?url=' . $src . '&embedded=true';
    }     
}

   if ((!empty($content_id) && !empty($content_styles)) ||
       !empty($hide_elements) || !empty($change_parent_links_target)
       || $enable_lazy_load == 'true' || $add_css_class_parent == 'true'
       || $show_iframe_as_layer == 'external' || $show_part_of_iframe_zoom !== 'false' ) {

   

    // hide elements is called directy in the page to hide elements as fast as quickly
    $hidehtml = '';
     // Add class to all parent elements for easier styling
    if ($add_css_class_parent == 'true') {
        $hidehtml .= " if (window.aiAddCssClassAllParents) { aiAddCssClassAllParents('#".$id."'); }";
    }
                        
    if (!empty($hide_elements)) {
        $hidehtml .= "jQuery('" . esc_html($hide_elements) . "').css('display', 'none');";
    }
    if (!empty($content_id)) {
        $elements = esc_html($content_id); // this field should not have a problem if they are encoded.
        $values = esc_html($content_styles); // this field style should not have a problem if they are encoded.
        $elementArray = explode("|", $elements);
        $valuesArray = explode("|", $values);
        if (count($elementArray) != count($valuesArray)) {
            return $error_css . '<div class="errordiv">' . __('Configuration error: The attributes content_id and content_styles have to have the amount of value sets separated by |.', 'advanced-iframe') . '</div>';
        } else {
            for ($x = 0; $x < count($elementArray); ++$x) {
                $valuesArrayPairs = explode(";", trim($valuesArray[$x], " ;:"));
                for ($y = 0; $y < count($valuesArrayPairs); ++$y) {
                    $elements = explode(":", $valuesArrayPairs[$y]);
                    $sel = trim($elementArray[$x]);
                    $sel = str_replace('##', '>', $sel ); 
                    $hidehtml .= "jQuery('" . $sel . "').css('" . trim(strtolower($elements[0])) . "', '" . trim(strtolower($elements[1])) . "');";
                }
            }
        }
    }

    $html .= '<script type="text/javascript">';
    $html .= 'function loadElem_'.$id.'(elem)
     {'; 
     if ($enable_lazy_load_fadetime != '0') {
     $html .= ' 
        elem.fadeOut(0, function() {
          elem.fadeIn('.$enable_lazy_load_fadetime.');
        });';
     }
     $html .= '}';

    $html .= 'function aiModifyParent_' . $id . '() { ';
    $html .=  $hidehtml;
    $html .= '}';
    
    $aiReady = '';
    $hide_page_sum = ($hide_page_until_loaded  == 'true' || $hide_page_until_loaded_external == 'true')? 'true':'false';
    //  Change parent links target
    if (!empty($change_parent_links_target) && $show_iframe_as_layer !== 'external') {
      $elementArray = explode("|", $change_parent_links_target);
      for ($x = 0; $x < count($elementArray); ++$x) {
          $aiReady .= 'jQuery("'. trim($elementArray[$x]) .'").attr("target", "'.$id.'");';
      }
     
      if ($show_iframe_as_layer == 'true') {
        $aiReady .=  'jQuery("'.$change_parent_links_target.'").on( "click", function(event) { var reload=ai_checkReload(this, "' . $id . '"); ai_showLayerIframe(event,"' . $id . '","'.plugins_url() . $aiPath.'/img/","'.$hide_page_sum.'","'.$show_iframe_loader_layer.'", '.$show_iframe_as_layer_keep_content.', reload); });'; 
      }      
    }
    if ($show_iframe_as_layer == 'external') {   
         $aiReady .=  'jQuery("a").each(function () {
          if (this.host !== location.host) {
            jQuery(this).attr("target", "'.$id.'");
            jQuery(this).on("click", function(event) { var reload=ai_checkReload(this, "' . $id . '"); ai_showLayerIframe(event,"' . $id . '","'.plugins_url() . $aiPath.'/img/","'.$hide_page_sum.'","'.$show_iframe_loader_layer.'", '.$show_iframe_as_layer_keep_content.', reload); });
          }
      });';
    }

    $aiReady .= 'aiModifyParent_' . $id . '();';
    
    if ($enable_lazy_load == 'true') { 
       // the 50 ms timeout is used because tabs need a little bit to initialize and hide the content.
       $initLazyIframe = 'setTimeout(function() { jQuery("#ai-lazy-load-'.$id.'").lazyload({threshold: '.$enable_lazy_load_threshold.', load: loadElem_'.$id.'}); },50);';   
       if ($enable_lazy_load_manual != 'auto') {
           $initLazyIframe .= "jQuery.lazyload.setInterval(0);"; 
       }
       if ($enable_lazy_load_manual == 'true') {
           $html .= 'function aiLoadIframe_' . $id . '() { ';
           $html .=  $initLazyIframe;
           $html .= '};'; 
           
            if (!empty($enable_lazy_load_manual_element)) {
               $html .= ' function trigger_manual_' . $id . '() { '; 
               $html .= 'jQuery( "' . esc_html($enable_lazy_load_manual_element) . '" ).click(function() { ';
               $html .= 'window.setTimeout(function(){'; 
               $html .= '  aiLoadIframe_' . $id . '(); ';  
               $html .= '}, 10);';
               $html .= 'return false;';
               $html .= '});'; 
               $html .= '}';  
               $aiReady .= 'trigger_manual_' . $id . '();';
            }    
       } else {
           $aiReady .= $initLazyIframe; 
       } 
    }
    
    if ($show_part_of_iframe_zoom !== 'false' ) { 
       $auto_zoom_div = empty($hide_part_of_iframe) ? '#ai-div-': '#wrapper-div-';
       $aiReady .= 'aiAutoZoomViewport("'.$auto_zoom_div.$id.'","' . $show_part_of_iframe_zoom . '");';
    }
    
    $html .= 'var aiReadyAiFunct_' . $id . ' = function aiReadyAi_' . $id . '() { ';
    $html .=  $aiReady;
    $html .= '};';
    $html .= 'aiReadyCallbacks.push(aiReadyAiFunct_' . $id . ');';
    
    // Modify parent is called right away to do the modifications even when the dom is not ready yet.
    // It is called again on dom ready 
    $html .= 'if (window.jQuery) { aiModifyParent_' . $id . '(); }';
    $html .= '</script>';
}

    
    // jQuery("#advanced_iframe").contents().find("#iframe-div").css("border","4px solid blue");
    $hideiframehtml = '';

    if ((!empty($iframe_content_id) && !empty($iframe_content_styles))|| !empty($iframe_hide_elements) 
       || (!empty($change_iframe_links) && !empty($change_iframe_links_target)) || !empty($iframe_content_css)
       || !empty($additional_js_file_iframe) || !empty($additional_css_file_iframe)
       ) {
    if ($add_css_class_iframe) {
       // get the url from the iframe - create a hash and add this as class to the body. 
       // this enables us to distinguish between sites with the same structure but where 
       // different thing e.g. should be hidden.
       $hideiframehtml .= "var iframeHref".$id." = jQuery('#".$id."').contents().get(0).location.href; 
       if (iframeHref".$id.".substr(-1) == '/') {
           iframeHref".$id." = iframeHref".$id.".substr(0, iframeHref".$id.".length - 1);
       }
       var lastIndex".$id." = iframeHref".$id.".lastIndexOf('/');
       var result".$id." = iframeHref".$id.".substring(lastIndex".$id." + 1);
       var newClass".$id." = result".$id.".replace(/[^A-Za-z0-9]/g, '-');
       var iframeBody".$id." = jQuery('#".$id."').contents().find('body');
       iframeBody".$id.".addClass('ai-' + newClass".$id.");
       iframeBody".$id.".children().each(function (i) {
             jQuery(this).addClass('ai-' + newClass".$id." + '-child-' + (i+1)); 
        });
       "; 
    }
    
    
    if (!empty($iframe_hide_elements)) {
        $hideiframehtml .= "jQuery('#".$id."').contents().find('" .
            esc_html($iframe_hide_elements) . "').css('display', 'none').css('width', '0').css('height','0');";
    }
    if (!empty($iframe_content_id)) {
        $elements = esc_html($iframe_content_id); // this field should not have a problem if they are encoded.
        $values = esc_html($iframe_content_styles); // this field style should not have a problem if they are encoded.
        $elementArray = explode("|", $elements);
        $valuesArray = explode("|", $values);
        if (count($elementArray) != count($valuesArray)) {
            return $error_css . '<div class="errordiv">' . __('Configuration error: The attributes iframe_content_id and iframe_content_styles have to have the amount of value sets separated by |.', 'advanced-iframe') . '</div>';
        } else {
            for ($x = 0; $x < count($elementArray); ++$x) {
                $valuesArrayPairs = explode(";", trim($valuesArray[$x], " ;:"));
                for ($y = 0; $y < count($valuesArrayPairs); ++$y) {
                    $elements = explode(":", $valuesArrayPairs[$y]);
                    $hideiframehtml .= "jQuery('#".$id."').contents().find('" . trim($elementArray[$x])
                      . "').css('" . trim(strtolower($elements[0])) . "', '" . trim(strtolower($elements[1])) . "');";
                }
            }
        }
    }

    // change_iframe_links
    if (!empty($change_iframe_links)) {
        $links = esc_html($change_iframe_links); // this field should not have a problem if they are encoded.
        $targets = esc_html($change_iframe_links_target); // this field style should not have a problem if they are encoded.
        $linksArray = explode("|", $links);
        $targetArray = explode("|", $targets);
        if (count($linksArray) != count($targetArray)) {
            return $error_css . '<div class="errordiv">' . __('Configuration error: The attributes change_iframe_links and change_iframe_links_target have to have the amount of value sets separated by |.', 'advanced-iframe') . '</div>';
        } else {
            for ($x = 0; $x < count($linksArray); ++$x) {
                $hideiframehtml .= "jQuery('#".$id."').contents().find('" . trim($linksArray[$x])
                      . "').attr('target', '".trim($targetArray[$x])."');";
            }
        }
    }
    if (!empty($iframe_content_css)) {
        $hideiframehtml .= 'aiAddCss("#'.$id.'","'.urlencode(wp_kses($iframe_content_css, array())).'");';
    }
    if (!empty($additional_css_file_iframe)) {
        $hideiframehtml .= 'aiAddCssFile("#'.$id.'","'.$additional_css_file_iframe.'");';
    }
    if (!empty($additional_js_file_iframe)) {
        $hideiframehtml .= 'aiAddJsFile("#'.$id.'","'.$additional_js_file_iframe.'");';
    }
    
    if ($hideiframehtml != '') {
    $html .= '<script type="text/javascript">';
    $html .= 'function aiModifyIframe_' . $id . '() { ';
    $html .= 'try {';
    $html .=  $hideiframehtml;
    $html .=  '}  catch(e) {';
    $html .=  '  if (console) {';
    $html .=  '    if (console.log) {';
    $html .=  '      console.log("Advanced iframe configuration error: You have enabled the modification of the iframe for pages on the same domain. But you use an iframe page on a different domain. You need to use the pro version of external workaround like described in the settings. Also check the next log. There the browser message for this error is displayed."); ';
    $html .=  '      console.log(e);';
    $html .=  '    }';
    $html .=  '  }';
    $html .=  '}';
    $html .= '}';
    $html .= '</script>';
    }
}

?>