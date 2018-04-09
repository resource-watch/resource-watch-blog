<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
/**
 * Code for the include directly feature.
 */
$this->scriptsNeeded = false;
if (empty($include_html)) {
  $ai_height = (empty ($include_height)) ? '' : (' style="height:'.$include_height.';" ');

  $html = '<div '.$ai_height.' id="ai-temp-'.$id.'"><!-- --></div>';
  $html .= '<script type="text/javascript">';
  if  ($include_hide_page_until_loaded === 'true') {
    $html .= 'jQuery("body").css("display", "none");';
  }
  $html .= 'jQuery("#ai-temp-'.$id.'").load("' . $include_url;
  if  (!empty ($include_content)) {
    $html .= ' ' . $include_content;
  }
  $html .= '" , function() {';
  if  ($include_hide_page_until_loaded === 'true') {
  $html .= ' jQuery("body").css("display", "block"); ';
  }
  $html .= ' })';
  if  (!empty ($include_fade)) {
    $html .= '.hide().fadeIn('.$include_fade.');';
  }
  $html .= '</script>';
} else {
  $html .= $include_html;
}  
?>