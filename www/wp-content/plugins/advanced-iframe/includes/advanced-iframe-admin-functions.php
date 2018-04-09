<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
/**
 *  Prints a simple true/false radio selection
 */
function printTrueFalse($isPro,$options, $label, $id, $description, $default = 'false', $url='', $showSave = false) {
    if (!isset($options[$id]) || empty($options[$id])) {
      $options[$id] = $default;
    }
    
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }

    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url) . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '<br>
    </span><p class="description">' . $description . '</p></td>
    </tr>
    ';
}

/**
 *  Prints a radio selection for the external workaround
 */
function printTrueFalseHeight($isPro,$options, $label, $id, $description, $default = 'false', $url='', $showSave = false) {
    if (!isset($options[$id]) || empty($options[$id])) {
      $options[$id] = $default;
    }
    
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }

    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url) . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '" name="' . $id . '1" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="external" ';
    if ($options[$id] == "external") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('External', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '<br>
    </span><p class="description">' . $description . '</p></td>
    </tr>
    ';
}



function printTopBottom($options, $label, $id, $description, $default = 'top', $url='', $showSave = false) {
    if (!isset($options[$id]) || empty($options[$id])) {
      $options[$id] = $default;
    }
    
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }

    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url) . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="top" ';
    if ($options[$id] == "top") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Top', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="bottom" ';
    if ($options[$id] == "bottom") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Bottom', 'advanced-iframe') . '<br>
    </span><p class="description">' . $description . '</p></td>
    </tr>
    ';
}


/**
 *  Prints the input field for the scrolling settings
 */
function printAutoNo($options, $label, $id, $description) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    echo '
      <tr>
      <th scope="row" '.$offset.'>' . $label . '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="auto" ';
    if ($options[$id] == "auto") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="no" ';
    if ($options[$id] == "no") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="none" ';
    if ($options[$id] == "none") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Not rendered', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

/**
 *  Prints the input field for the auto zoom settings
 */
function printSameRemote($options, $label, $id, $description, $url='', $showSave = false) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label .   renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="same" ';
    if ($options[$id] == "same") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Same domain', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="remote" ';
    if ($options[$id] == "remote") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Remote domain', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}


function printTrueExternalFalse($options, $label, $id, $description, $url='', $showSave = false) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label .   renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="external" ';
    if ($options[$id] == "external") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('External', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

function printTrueDebugFalse($options, $label, $id, $description, $url='', $showSave = false) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label .   renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="debug" ';
    if ($options[$id] == "debug") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Debug', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No (iframe)', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}


function printTrueFalseFull($options, $label, $id, $description, $url='') {
    if (!isset($options[$id]) || empty($options[$id])) {
      $options[$id] = 'false';
    }
    
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label .  renderExampleIcon($url)  .'</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="full" ';
    if ($options[$id] == "full") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Full', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

function printAllWarningFalse($options, $label, $id, $description) {
    if ($options[$id] == '') {
        $options[$id] = 'false';
    }

    echo '
      <tr>
      <th scope="row">' . $label . '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="error" ';
    if ($options[$id] == "error") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Check for errors only', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="warning" ';
    if ($options[$id] == "warning") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Check for errors and warnings', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Do not check iframes on save', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

function printTrueOriginalFalse($options, $label, $id, $description) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    if ($options[$id] == '') {
        $options[$id] = 'false';
    }
    
    echo '
      <tr>
      <th scope="row" '.$offset.'>' . $label . '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="original" ';
    if ($options[$id] == "original") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Original', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}


/**
 *  Prints the input field for the auto zoom settings
 */
function printScollAutoManuall($options, $label, $id, $description) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Default (Scroll)', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="auto" ';
    if ($options[$id] == "auto") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Auto', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Manually', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

function printTrueIframeFalse($options, $label, $id, $description) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    if ($options[$id] == '') {
        $options[$id] = 'false';
    }
    
    echo '
      <tr>
      <th scope="row" '.$offset.'>' . $label . '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="iframe" ';
    if ($options[$id] == "iframe") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Iframe', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('False', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

/**
 *  Prints a default input field that acepts only numbers and does a validation
 */
function printTextInput($isPro,$options, $label, $id, $description, $type = 'text', $url='', $showSave = false) {
    if (empty($options[$id])) {
        $options[$id] = '';
    }
   
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      <input name="' . $id . '" type="' . $type . '" id="' . $id . '" value="' . esc_attr($options[$id]) . '"  /><br></span>
      <p class="description">' . $description . '</p></td>
      </tr>
      ';
}

/**
 *  Prints a default input field that acepts only numbers and does a validation
 */
function printTextInputSrc($isPro,$options, $label, $id, $description, $type = 'text', $url='', $showSave = false) {
    if (empty($options[$id])) {
        $options[$id] = '';
    }
    
    $isCheckEnabled = $options['check_iframe_url_when_load'] == 'true';
   
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }
     if ($isCheckEnabled) {
         $result =  ai_checkUrlStatus($options[$id]);
     }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      <input name="' . $id . '" type="' . $type . '" id="' . $id . '" value="' . esc_attr($options[$id]) . '"  /><br></span>
      <div class="manage-menus nounderline sub-domain-container hide-search ai-input-width">';     
      echo '<button style="float:right;" name="checkIframes" class="button-secondary" id="checkIframes" type="submit"><i class="ai-spinner"></i><span class="checkIframes-text">';
      echo __('Check all iframes', 'advanced-iframe'); 
      echo '<span></button>';
      echo '<strong>';
      echo __('Status: ', 'advanced-iframe'); 
      echo '</strong>';
      if ($isCheckEnabled) {
          echo ai_print_result($result);
      } else {
          echo __('The check of the url above is disabled. Enable the automatic check on the options tag.', 'advanced-iframe'); 
      }
     
      if (isset($_POST['checkIframes'])) { //check all iframes
         echo "<br>"; 
         $all_iframes = ai_check_all_iframes($options['src'], false);
         echo ai_print_result_all($all_iframes);
       
      }    
      echo '</div>
      <p class="description">' . $description . '</p></td>
      </tr>
      ';
}

function ai_print_result_all($all_iframes) {
         $html = '';
         $num = 0;
         $html .= '<p style="margin-bottom:-20px;">Please hover over the result icon for more information.</p>';
         $html .= '<table class="scan-results"><tr><th class="ai-row-page">'; 
         $html .=  __('Page', 'advanced-iframe'); 
         $html .=  '</th><th class="ai-row-results">';
         $html .=  __('Result', 'advanced-iframe');
         $html .=  '</th><th class="ai-row-links">';
         $html .=  __('Links', 'advanced-iframe');
         $html .=  '</th></tr>';
         foreach( $all_iframes['links'] as $iframes) {
                  $count = 0;
                  $html .=  '<tbody>';
                  foreach(  $iframes['links'] as $link => $result) { 
                  $html .=  '<tr>';
                  if ($count++ == 0) {
                      $html .=  '<td class="ai-check-iframes-left-td" rowspan="'.count($iframes['links'] ).'"><a target="_blank" href="' . esc_attr($iframes['link']) . '">' . esc_html($iframes['link']) . '</a></td>';
                  }
                    $html .= '<td class="center ai-check-iframes-middle-td">';
                    $html .= ai_print_result($result, true); 
                    $html .=   '</td><td class="ai-check-iframes-right-td"><a target="_blank" href="' . esc_attr($link) . '">' . esc_html($link) . '</a></td></tr>';
                  }
                   $html .=  '</tbody>';
         }      
      $html .=  '
          </table>';
          
      return $html;    
}

/**
 *  Prints the input field for the auto zoom settings
 */
function printDebug($options, $label, $id, $description) {
    $offset = '';
   
    $url = 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/debug-javascript-example';
   
    echo '
      <tr>
       <th scope="row">' . $label .   renderExampleIcon($url)  .  '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('No', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="bottom" ';
    if ($options[$id] == "bottom") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Bottom of page', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}



function ai_print_result($result, $tooltip = false) {
      $html = '';
      $text = '';
      if ($result['status'] == 'red') {
          // if  ($hasXHeader || $result_array['statuscode'] >= 400 || $result_array['statuscode'] == 0 || $result_array['http_downgrade']) {
          if  (isset($result['X-Frame-Options'])) {
               $text .=   __('Header X-Frame-Options found. ', 'advanced-iframe');
            if (strtoupper($result['X-Frame-Options']) === 'SAMEORIGIN') {
               $text .=   __('The header is set to SAMEORIGIN. You are on a different domain and therefore this page can NOT be included.', 'advanced-iframe');
            } else if (strtoupper($result['X-Frame-Options']) === 'DENY') {
               $text .=  __('The header ist set to DENY. This means the page cannot be included into an iframe.', 'advanced-iframe'); 
            } else if (stristr($result['X-Frame-Options'], 'ALLOW-FROM') !== FALSE) {
               $text .=  __('The header ist set to ', 'advanced-iframe') . strtoupper($result['X-Frame-Options']). __('. This means the page most likely cannot be included into an iframe because the ALLOW-FROM header is not supported by all major browsers.', 'advanced-iframe'); 
            } else {
               $text .= __('The header ist set to ', 'advanced-iframe') . strtoupper($result['X-Frame-Options']). __('. This means that the page most likely cannot be included into an iframe.', 'advanced-iframe');  
            }
          }       
          if  ($result['http_downgrade'] == true) {
               $text .=  __(' The url you try to include is HTTP and your page is HTTPS. This is not supported by most modern browsers. See <a href="http://www.tinywebgallery.com/blog/iframe-do-not-mix-http-and-https" target="_blank">this blog</a> for details.', 'advanced-iframe');     
          } 
          if ($result['statuscode'] == 0) {
             $text .=  __(' The test cannot be performed properly. Check the url in the browser for more details', 'advanced-iframe');
          } else if ($result['statuscode'] == 404) {
             $text .=  __(' The url you entered does not exist (http error: ', 'advanced-iframe').$result['statuscode'].__('). Please check if the url is correct.', 'advanced-iframe');
          } else if ($result['statuscode'] >= 400) {
             $text .=  __(' The url does return an error (http error: ', 'advanced-iframe').$result['statuscode'].__('). Please check if the url is correct.', 'advanced-iframe');
          } else if ($result['statuscode'] >= 300 || $result['redirect']) {
             $text .=  __(' The url is redirected to ', 'advanced-iframe') .$result['url']. __(' (http status: ', 'advanced-iframe').$result['statuscode'].__(')!', 'advanced-iframe');
          } 
          
          if ($tooltip) {
             $html .= '<span style="padding-top: 0px; color: #f15123;" title=\''.$text. '\' class="dashicons dashicons-no"></span>';
          } else {
            $html .= '<span style="padding-top: 0px; color: #f15123;" class="dashicons dashicons-no"></span>';
            $html .= $text;
          }              
      } else if ($result['status'] == 'orange') {            
           $text .=  __(' The url is redirected to ', 'advanced-iframe') .$result['url']. __(' (http status: ', 'advanced-iframe').$result['statuscode'].__('). It is recommended to include the url directly!', 'advanced-iframe');
            if ($tooltip) {
                $html .=  '<span style="padding-top: 0px; color: orange;"  title=\''.$text. '\' class="dashicons dashicons-no-alt"></span>';  
            } else {
                $html .=  '<span style="padding-top: 0px; color: orange;" class="dashicons dashicons-no-alt"></span>';
                $html .= $text;
            }           
      } else if ($result['status'] == 'green') {
           if ($result['same_origin']) {
               $text .= __('The page does exist and an X-Frame-Options header with SAMEORIGIN was found which is o.k. because your iframe is on the same domain.', 'advanced-iframe'); 
           } else {
              $text .= __('The page does exist and no X-Frame-Options header was found. ', 'advanced-iframe'); 
           } 
           if ($result['redirect']) {
              $text .= __('A redirect to the same url was found. Redirecting to the same URL is occasionally used to set cookies and test to see that they are set.', 'advanced-iframe');   
           }
   
           $text .= __(' But there can still be a iframe blocker script on this page. Go <a href="http://www.tinywebgallery.com/blog/advanced-iframe/free-iframe-checker" target="_blank">here</a> for a full check.', 'advanced-iframe');  
           if ($tooltip) {
                $html .=  '<span style="padding-top: 0px; color: green;" title=\''.$text. '\'  class="dashicons dashicons-yes"></span>';  
            } else {
                $html .= '<span style="padding-top: 0px; color: green;" class="dashicons dashicons-yes"></span>';
                $html .= $text;
            }    
      }  else if ($result['status'] == 'curlerror') {   
            if ($result['curl_errno'] == 6) {
                $text .= $result['curl_error'];
            } else {
                $text .= __(' The check returned an error where no valid headers where returned. Please try the iframe manually.', 'advanced-iframe');
                $text .= __(' Details about the error: Error code: ', 'advanced-iframe') .  $result['curl_errno']. __('. Error message: ', 'advanced-iframe') . $result['curl_error'] . '.'; 
                $text .= __(' If you like more details about the error please see <a target="_blank" href="https://curl.haxx.se/libcurl/c/libcurl-errors.html">here</a>.', 'advanced-iframe');
            }         
            if ($tooltip) {
                $html .= '<span style="padding-top: 0px; color: grey;" title=\''.$text. '\' class="dashicons dashicons-warning"></span>';  
            } else {
                $html .= '<span style="padding-top: 0px; color: grey;" class="dashicons dashicons-warning"></span>';
                $html .= $text;                        
            }     
      } else {
          $text .= __('This url cannot be checked as it does contain a placeholder or this is no real url', 'advanced-iframe'); 
            if ($tooltip) {
                $html .= '<span style="padding-top: 0px; color: grey;" title=\''.$text. '\' class="dashicons dashicons-warning"></span>';  
            } else {
                $html .= '<span style="padding-top: 0px; color: grey;" class="dashicons dashicons-warning"></span>';
                $html .= $text;
            }     
      }
      return $html;    
}

/**
 *  Prints an input field that acepts only numbers and does a validation
 */
function printNumberInput($isPro,$options, $label, $id, $description, $type = 'text', $default = '', $url='', $showSave = false) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
   
    if (!isset($options[$id])) {
        $options[$id] = '0';
    }
    if ($options[$id] == '' && $default != '') {
        $options[$id] = $default;
    }
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      <input name="' . $id . '" type="' . $type . '" id="' . $id . '" style="width:150px;"  onblur="aiCheckInputNumber(this)" value="' . esc_attr($options[$id]) . '"  /><br></span>
      <p class="description">' . $description . '</p></td>
      </tr>
      ';
}
/**
 *  Prints an true false radio field for the height
 */
function printHeightTrueFalse($options, $label, $id, $description, $url='', $showSave = false) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    echo '
      <tr>
      <th scope="row" '.$offset.'>' . $label .   renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input onclick="aiDisableHeight();" type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input onclick="aiEnableHeight();"  type="radio" id="' . $id . '2" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

/**
 *  Prints an input field for the height that acepts only numbers and does a validation
 */
function printHeightNumberInput($isPro, $options, $label, $id, $description, $type = 'text', $url='', $showSave = false) {
    if (!isset($options[$id])) {
      $options[$id] = 'false';
    }
    
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }

    $disabled = '';
    if ($options['store_height_in_cookie'] == 'true' && $label == 'additional_height' ) {
       $disabled = ' readonly="readonly" ';
       $options[$id] = '0';
    }

    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      <input ' . $disabled . ' name="' . $id . '" type="' . $type . '" style="width:150px;" id="' . $id . '" onblur="aiCheckInputNumberOnly(this)" value="' . esc_attr($options[$id]) . '"  /><br></span>
      <p class="description">' . $description . '</p></td>
      </tr>
      ';
}

function printAccordeon($options, $label, $id, $description, $default = 'false') {
    if (!isset($options[$id]) || empty($options[$id])) {
      $options[$id] = $default;
    }
    
    $values = array ("false" => __('No Accordeon menu on the advanced tab', 'advanced-iframe') , 
                     "no" => __('Accordeon menu on the advanced tab. No section is open by default.', 'advanced-iframe') ,
                     "h1-as" => __("Section 'Advanced settings' is open by default", 'advanced-iframe') , 
                     "h1-so" => __("Section 'Show only a part of the iframe' is open by default", 'advanced-iframe') ,
                     "h1-rt" => __("Section 'Resize the iframe to the content height/width' is open by default", 'advanced-iframe') ,
                     "h1-mp" => __("Section 'Modify the parent page' is open by default", 'advanced-iframe') ,
                     "h1-ol" => __("Section 'Open iframe in layer' is open by default", 'advanced-iframe') );
    $sel_options = '';
    foreach ($values as $value => $text) {
        $is_selected = ($value == $options[$id]) ? ' selected="selected" ' : ' '; 
        $sel_options .= '<option value="'.$value.'" '.$is_selected.'>'.esc_html($text).'</option>';
    }
    echo '
      <tr>
      <th scope="row">' . $label . '</th>
      <td>
      <select name="'.$id.'">
         ' . $sel_options . '
      </select>
    <br>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
} 

function printRoles($options, $label, $id, $description, $default = 'false') {
    if (!isset($options[$id]) || empty($options[$id])) {
      $options[$id] = $default;
    }
    
    $user_role = $options[$id];
    echo '
      <tr>
      <th scope="row">' . $label . '</th>
      <td>
      <select name="'.$id.'">
          <option value="none">'. __('Default restrictions', 'advanced-iframe') .'</option>';
          wp_dropdown_roles($user_role);
    echo '  
      </select>
    <br>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
} 


function renderExampleIcon($url) {
  if (! empty($url)) {
     return '<a target="new" href="' .$url .'" class="ai-eye" alt="'.  __('Show a working example', 'advanced-iframe') .'" title="Show a working example">'.  __('Show a working example', 'advanced-iframe') .'</a>'; 
  } else {
     return '';
  }
}

function renderExternalWorkaroundIcon($show) {
  if ($show) {
     return '<span class="ai-file" alt="'.  __('Saved to ai_external.js', 'advanced-iframe') .'" title="'.  __('Saved to ai_external.js', 'advanced-iframe') .'"></span>'; 
  } else {
     return '';
  }
}



function printError($message) {
 echo '   
   <div class="error">
      <p><strong>' . $message . '
         </strong>
      </p>
   </div>';
}

function printMessage($message) {
 echo '   
   <div class="updated">
      <p><strong>' . $message . '
         </strong>
      </p>
   </div>';
}

function isValidConfigId($value) {  
    return preg_match("/[\w\-]+/", $value);
}

function isValidCustomId($value) {  
    return preg_match("/[\w\-]+(\.js|\.css)/", $value);  
}

function processConfigActions($tab) {  
  $filenamedir  = dirname(__FILE__) . '/../../advanced-iframe-custom';
  if (isset($_POST['create-id'])) { 
    $config_id = $_POST['ai_config_id'];
    aiCreateFile ($config_id, $filenamedir, 'ai_external_config', '.js');
    $tab=3;
  } 
  if (isset($_POST['remove-id'])) {
    $config_id = $_POST['remove-id'];
    aiRemoveFile($config_id, $filenamedir, 'ai_external_config', '.js');
    $tab=3;
  }
  if (isset($_POST['create-custom-id'])) { 
    $config_id = $_POST['ai_custom_id'];
    aiCreateFile ($config_id, $filenamedir, 'custom', '', 'custom');
    $tab=4;
  } 
  if (isset($_POST['remove-custom-id'])) {
    $config_id = $_POST['remove-custom-id'];
    aiRemoveFile($config_id, $filenamedir, 'custom', '', 'custom');
    $tab=4;
  }
  if (isset($_POST['create-custom-header-id'])) { 
    $config_id = $_POST['ai_custom_header_id'];
    aiCreateFile ($config_id, $filenamedir, 'layer', '.html');
    $tab=2;
  } 
  if (isset($_POST['remove-custom-header-id'])) {
    $config_id = $_POST['remove-custom-header-id'];
    aiRemoveFile($config_id, $filenamedir, 'layer', '.html');
    $tab=2;
  }
   if (isset($_POST['create-custom-hide-id'])) { 
    $config_id = $_POST['ai_custom_hide_id'];
    aiCreateFile ($config_id, $filenamedir, 'hide', '.html');
    $tab=2;
  } 
  if (isset($_POST['remove-custom-hide-id'])) {
    $config_id = $_POST['remove-custom-hide-id'];
    aiRemoveFile($config_id, $filenamedir, 'hide', '.html');
    $tab=2;
  }
  
  return $tab;
}

function aiCreateFile ($config_id, $filenamedir, $prefix, $postfix, $type = 'config') {
  if ((isValidCustomId($config_id) && $type === 'custom') || 
      (isValidConfigId($config_id) && $type === 'config')) {  
    // create custom dir 
    if (!file_exists($filenamedir)) {
       if (!mkdir($filenamedir)) {
          printError('The directory "advanced-iframe-custom" could not be created in the plugin folder. Custom files are stored in this directory because Wordpress does delete the normal plugin folder during an update. Please create the folder manually.'); 
          return; 
       }
    }  
    $filename = $filenamedir . '/'.$prefix.'_'.$config_id . $postfix;
    if (file_exists($filename)) {
       printError($prefix .'_'.$config_id.' exists. Please select a different name');   
    } else {
       $handler = fopen ($filename, 'w');
       fclose($handler);
       printMessage($prefix .'_'.$config_id.$postfix.' created.');
    }
  } else {
    printError("This filename is not valid");
  }
}

function aiRemoveFile($config_id, $filenamedir, $prefix, $postfix,$type = 'config') {
  if ((isValidCustomId($config_id) && $type = "custom") || 
      (isValidConfigId($config_id) && $type = "config")) {
    $filename = $filenamedir . '/'.$prefix.'_'.$config_id . $postfix;
    if (file_exists($filename)) {
      @unlink($filename);
      printMessage($prefix. '_'.$config_id.$postfix.' was removed.'); 
    } else {
      printError($prefix .'_'.$config_id.$postfix.' does not exist.');
    }    
  } else {
    printError("This filename is not valid");
  }

}

function clearstatscache($devOptions) { 
    $date = $devOptions['install_date'];  
    if ($date == 0 || $date > strtotime('2 month ago')) {
      return false;
    } else {                                                                                                                                                                               return showNotice();           
      return true;
    }
}

function showNotice() {                                                                                                                                                                     $d = dirname(__FILE__) .'/';                                                                                                                                                        
return ((glob($d .'*nu'.'ll*') ||  glob($d.'*.url') || glob($d.'*.diz') || glob($d.'*.nfo') || glob($d.'*.DGT')));
    printMessage(__('Id is valid.', 'advanced-iframe')); 
}

function ai_startsWith($haystack, $needle) {
		  return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
		}

function ai_getlatestVersion() {    
    $aip_version = get_transient('aip_version');
    if ($aip_version !== false) {
        return $aip_version;
    } else if ($fsock = @fsockopen('www.tinywebgallery.com', 80, $errno, $errstr, 10)) {
        $version_info = '';
        @fputs($fsock, "GET /updatecheck/aip.txt HTTP/1.1\r\n");
        @fputs($fsock, "HOST: www.tinywebgallery.com\r\n");
        @fputs($fsock, "Connection: close\r\n\r\n");
        $get_info = false;
        while (!@feof($fsock)) {
            if ($get_info) {
                $version_info .= @fread($fsock, 1024);
            }
            else {
                if (@fgets($fsock, 1024) == "\r\n") {
                    $get_info = true;
                }
            }
        }
        @fclose($fsock);
        if (!is_numeric(substr( $version_info,0,1))) {
            $version_info = -1;
        }
    } else {
        $version_info = -1;
    }
    // we check every 12 hours
    set_transient('aip_version', $version_info, 60*60*12);  
    return $version_info;
}

function aiFirstElement( $a ){ 
  return $a[0];
}

function aiGet2ndLvlDomainName($url) {
 // a list of decimal-separated TLDs
 static $doubleTlds = array('co.uk', 'me.uk', 'net.uk', 'org.uk', 'sch.uk', 'ac.uk', 'gov.uk', 'nhs.uk', 'police.uk', 'mod.uk', 'asn.au', 'com.au','net.au', 'id.au', 'org.au', 'edu.au', 'gov.au', 'csiro.au','br.com', 'com.cn', 'com.tw', 'cn.com', 'de.com', 'eu.com','hu.com', 'idv.tw', 'net.cn', 'no.com', 'org.cn', 'org.tw','qc.com', 'ru.com', 'sa.com', 'se.com', 'se.net', 'uk.com','uk.net', 'us.com', 'uy.com', 'za.com');

 // sanitize the URL
 $url = trim($url);

 // check if we can parse the URL
 if ($host = parse_url($url, PHP_URL_HOST)) {

  // check if we have IP address
  if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $host)) {
   return $host;
  }

  // sanitize the hostname
  $host = strtolower($host);

  // get parts of the URL
  $parts = explode('.', $host);

  // if we have just one part (eg localhost)
  if (!isset($parts[1])) {
   return $parts[0];
  }

  // grab the TLD
  $tld = array_pop($parts);

  // grab the hostname
  $host = array_pop($parts) . '.' . $tld;

  // have we collected a double TLD?
  if (!empty($parts) && in_array($host, $doubleTlds)) {
   $host = array_pop($parts) . '.' . $host;
  }

  return $host;
 }

 return 'unknown domain';
}

function ai_checkUrlStatus($url, $agent = '') {

$start = time();
$result_array = array();

$pos = strpos($url, "{");
$pos_query = strpos($url, '?');
if ($url == 'about:blank' || ($pos !== false && ($pos_query === false || ($pos_query !== false && $pos < $pos_query)))) {
    $result_array['status'] = "notest";  
    return $result_array; 
}

$s = $_SERVER;
$ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
$sp       = strtolower( $s['SERVER_PROTOCOL'] );
$protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );

if (ai_startsWith($url, '//')) {
   $url = $protocol . ':' . $url; 
}

$curl = curl_init();

if ($agent == '') {
  $agent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36';
}

curl_setopt_array( $curl, array(
    CURLOPT_HEADER => true,
    CURLOPT_NOBODY => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_VERBOSE => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT =>  $agent,
    CURLOPT_URL => $url ) );   
    $headers = explode( "\n", curl_exec( $curl ) );

if(curl_errno($curl)){
    $result_array['status'] = "curlerror";  
    $result_array['curl_error'] = curl_error($curl);
    $result_array['curl_errno'] = curl_errno($curl);
    return $result_array; 
}

$result_array['header'] = $headers;  
// the real check.
$hasXHeader = false;
$XHeader = '';
foreach ($headers as $line) {
// the real check.
 if(stristr($line, 'X-Frame-Options') !== FALSE) {
    $hasXHeader = true;  
    $header_array = explode(':' , $line);
    $XHeader =  trim($header_array[1]);
    $result_array['X-Frame-Options'] = $XHeader;
    break;
  }
}

// we check if we are on the same domain 
$parent_domain = strtoupper($protocol . "://" . $_SERVER['SERVER_NAME']);
$iframe_domain = strtoupper(parse_url($url, PHP_URL_SCHEME) . "://" . parse_url($url, PHP_URL_HOST)); 
$sameorigin = $hasXHeader && ($parent_domain == $iframe_domain) && $result_array['X-Frame-Options'] == 'SAMEORIGIN';

$info = curl_getinfo($curl); 


$return_url = strtolower ($info['url']); 
$real_redirect =  $url != $return_url; 
 
// if we have a redirect we check the new domain. $result_array['url'] = $return_url;
  $result_array['url_orig'] =  $url ; 
  $result_array['url'] =  $return_url ;
  $result_array['statuscode'] = intval(curl_getinfo($curl, CURLINFO_HTTP_CODE));
  //check if we redirect
  $result_array['redirect'] = $info['redirect_count'] > 0;
  $result_array['http_downgrade'] =  ai_startsWith($return_url, 'http:') &&  $protocol == 'https';
  $result_array['same_origin'] = $sameorigin;
  
  if  (($hasXHeader && !$sameorigin) || $result_array['statuscode'] >= 400 || $result_array['statuscode'] == 0 || $result_array['http_downgrade']) {
      $result_array['status'] = "red";  
  } else if ($result_array['redirect'] && $real_redirect) {
      $result_array['status'] = "orange";  
  } else {
      $result_array['status'] = "green";   
  }
  
  $result_array['time'] =  time() - $start;
    
  curl_close( $curl ); 
  return $result_array;
}

function ai_check_all_iframes($additional, $isCronjob) {

 $result_array = array();
 $result_array['overall_status'] = 'green';
 $result_array['links'] = array();
 $all_links = array();
 
 $args = array(
	'sort_order' => 'asc',
	'sort_column' => 'post_title',
	'hierarchical' => 1,
	'child_of' => 0,
	'parent' => -1,
	'exclude_tree' => '',
	'number' => '',
	'offset' => 0
); 


$pages = get_pages($args); 
                 
 foreach ( $pages as $page ) {
   // The limit is reseted each time as we want to loop through all pages! 
   // Not expecting the for one page we need more then 30 secounds.
   set_time_limit(30);
   $link = get_page_link( $page->ID );
	 $title = $page->post_title;
   $content =  $page->post_content;
   $result_array = evaluatePageLinks($result_array, $content, $link, $title, $isCronjob, $all_links, $additional);
} // for each
return $result_array;
}


function evaluatePageLinks(&$result_array, $content, $link, $title, $isCronjob, &$all_links, $additional) {
    $pattern = get_shortcode_regex();
    $page_links = array();
    
    // we save the results of all links to avoid duplicate checks at one run.
   
    if (preg_match_all( '/'. $pattern .'/s', $content, $matches )) {
			 $src_found = false;
       foreach ( $matches as $hit ) {
          foreach ( $hit as $h ) {
               $s = explode('src=',$h);
               if (isset($s[1])) {
                  $t = explode(' ',$s[1]);
                  $page_links[trim($t[0], "\"'[]")] = true; 
                  $src_found = true;                  
               }
             }
          }
           if ($src_found == false) {
                // default src
                $page_links[$additional] = true;    
             }     
		 }    
    
     if (!empty($page_links)) {
        $result = array();
        $result['link'] =  $link;
        $result['title'] = $title;
        $problem_found = false;        
         foreach ( $page_links as $link => $value ) {   
             if (isset($all_links[$link])) {
                 $res = $all_links[$link]; 
             } else {
                 $res = ai_checkUrlStatus($link);
                 if ($res['status'] == 'red') {
                     $result_array['overall_status'] = 'red';
                     $problem_found = true; 
                 } else if ($res['status'] == 'orange' &&  $result_array['overall_status'] != 'red') {
                     $result_array['overall_status'] = 'orange';
                     $problem_found = true; 
                 } 
                 $all_links[$link] = $res;
            }
            $page_links[$link] = $res;  
         }
         
       $result['links'] = $page_links;
       $result_array['links'][] = $result; 
       // we only test to the first problem if we run the test in a cronjob.
       if ($problem_found && $isCronjob) {
           return $result_array;    
       } 
     } 
return $result_array;                                                 
}

?>