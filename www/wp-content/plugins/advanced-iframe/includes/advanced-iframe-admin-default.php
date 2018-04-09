<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
?>
<br>
    <div>
    <div id="icon-options-general" class="icon_ai">
      <br>
    </div>
    <h2>
<?php _e('Basic settings', 'advanced-iframe'); ?></h2>
   
    <p class="shortcode hide-print">
      <?php _e('Please use the following shortcode to add an iframe to your page: ', 'advanced-iframe'); ?><br>
      <?php $securitykeyString =  $devOptions['securitykey'] == '' ? '' : ' securitykey="'.$devOptions['securitykey'].'"'; ?>
      <span> [advanced_iframe<?php echo $securitykeyString; ?>]</span>
      <span class="additional-shortcode">or use the "<strong>Add advanced iframe</strong>" button above the editor.</span>
      
        <?php _e('<br/>Specify at least an url and the size. <strong>You can overwrite all of the default administration settings by specifying the attribute in the shortcode to create iframes with different settings!</strong>', 'advanced-iframe'); ?>
      
     </p>      
      <p class="hide-print">
      <?php _e('You can also generate a shortcode which does include all settings as shortcode attributes. This shortcode does not use any of the defaults.', 'advanced-iframe'); ?>
      <br><br><input id="gen" class="button-primary" type="button" name="generate" value="Generate a shortcode for the current settings" onclick="aiGenerateShortcode(); jQuery('#jquery-gen').show(); return false;"  /></p>
      <div id="jquery-gen" class="hide-print">
      <p  class="hide-print">
      <?php _e('Copy the following shortcode to your page:', 'advanced-iframe'); ?>  
      </p>
      <p id="gen-shortcode"  class="hide-print">
          [advanced_iframe<?php echo $securitykeyString; ?>]
      </p>
      
      </div>
      <p class="hide-print">
      <?php _e('Examples if you want to use several iframes with different settings. Also read the'); ?> <a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-faq">FAQ</a>:
      </p>
      <ul class="hide-print">
      <li>[advanced_iframe<?php echo $securitykeyString; ?> src="http://www.tinywebgallery.com"] </li>
      <li>[advanced_iframe<?php echo $securitykeyString; ?> src="http://www.tinywebgallery.com" width="100%" height="600"]</li>
      <li>[advanced_iframe<?php echo $securitykeyString; ?> src="http://www.tinywebgallery.com" id="iframe1" name="iframe1" width="100%" height="600" ]</li>
      <li>[advanced_iframe<?php echo $securitykeyString; ?> id="iframe1" name="iframe1" width="100%" height="600"]http://www.tinywebgallery.com[/advanced_iframe]</li>
      </ul>   
    </p>
    <table class="form-table">
<?php
        printTextInput(false,$devOptions, __('Security key', 'advanced-iframe'), 'securitykey', __('This is the security key which can be used in the shorttag. This is optional since version 7.5.4. If you set this only users who know the security key can insert an advanced iframe. Also they need to have the minimum user role defined in the options to access a page with an advenced iframe. The key was made optional because many user have not the need of a security key and without the configuration is easier. Because of compability reasons security key in a shortcode are ignoered if you don\'t define a key here! Shortcode attribute: securitykey=""', 'advanced-iframe'));
if ($evanto || $isDemo) {

 // create the help for userinfo
$current_user = wp_get_current_user();
 $userinfo_html = __('Click <a href="#" id="user-help-link">here</a> for all values of the current user <span id="user-help">', 'advanced-iframe');
 $ovars = get_object_vars ($current_user->data);
 foreach ($ovars as $key => $value) {
    if (!is_object($value) && !is_array($value)) {
        $userinfo_html .= $key . " => " . $value . "<br>";
    }
 } 
 $userinfo_html .= '</span>';
 
 $all_meta_for_user = array_map( "aiFirstElement", get_user_meta( $current_user->ID  ) );
 
 $usermeta_html = __('Click <a href="#" id="user-meta-link">here</a> for all values of the current user.', 'advanced-iframe');
 $usermeta_html .= '<span id="meta-help">';
 foreach ($all_meta_for_user as $key => $value) {
    if (!is_object($value) && !is_array($value)) {
        $usermeta_html .= $key . " => " . $value . "<br>";
    }
 } 
 $usermeta_html .= '</span>';

        $src_text =  __('Enter the full URL to your page. e.g. http://www.tinywebgallery.com. <strong>Please do not use a different protocol for the iframe: Do not mix http and https if possible! Http pages are NOT shown in https pages.</strong> Please read <a href="http://www.tinywebgallery.com/blog/iframe-do-not-mix-http-and-https" target="_blank">this post</a> for details. If you cannot save the full url because of mod_security don\'t specify the protocoll (e.g. //www.tinywebgallery.com) or leave this field empty and define the src in the shortcode. Also use the free url checker below to make sure that you can include the page. You can also add parameters to this url like http://www.tinywebgallery.com/test.php?iframe=true. Then you can check this variable and use it to e.g. hide some elements in the iframe.<br>The pro version also has some placeholders (the standalone version has only host and port available) which are replaced on the fly: <span><strong>{site}</strong>: the url to the wordpress root</span><span><strong>{host}</strong>: the current host from the request</span><span><strong>{port}</strong>: the current port from the request</span><span><strong>{userid [,defaultvalue]}</strong>: the id of the current user. The optional defaultvalue is used if no user is logged in.</span><span><strong>{username [,defaultvalue]}</strong>: the username of the current user. The optional defaultvalue is used if no user is logged in.</span><span><strong>{useremail [,defaultvalue]}</strong>: the e-mail of the current user. The optional defaultvalue is used if no user is logged in.</span><span><strong>{adminmail}</strong>: the e-mail of the wordpress admin</span><span><strong>{userinfo-X [,defaultvalue]}</strong>: extract attribute X from get_currentuserinfo. E.g. {userinfo-display_name}. The optional defaultvalue is used if the attribute is not found or set. See <a href="https://codex.wordpress.org/Function_Reference/wp_get_current_user" target="_blank">here</a> for details. '. $userinfo_html.'</span><span><strong>{usermeta-X [,defaultvalue]}</strong>: extract key X from get_user_meta. E.g. {usermeta-last_name}. The optional defaultvalue is used if the attribute is not found or set. See <a href=" https://codex.wordpress.org/Function_Reference/get_user_meta" target="_blank">here</a> for details. '. $usermeta_html.'</span><span><strong>{href}</strong>: The full url that is shown in the address bar</span><span><strong>{urlpathX}</strong>: the Xth path element from the front. The first path element would be {urlpath1}<</span><span><strong>{urlpath-X}</strong>: the Xth path element from behind. The last path element would be {urlpath-1}</span><span><strong>{query-X [,defaultvalue]}</strong>: the value of the query parameter sent by GET or POST. ?example=myvalue would be {query-example} -> myvalue. The optional defaultvalue is used if the parameter is not found.</span><span><strong>{timestamp}</strong>: a timestamp which can be used to avoid caching of iframes</span><br>Make sure that <strong>no spaces are in the placeholders</strong>.<br/>All placeholders except {site}, {host}, {port} are urlencoded! An example would be src="http://demo.{host}/url?id={userid}". Especially for multidomain installations this is maybe helpful. If no user is logged in the values are empty or 0 for the id.<br>urlpath does extract path elements from the url in the address bar. So {urlpath-1} for the url www.xx.com/a/bb/cc would be cc. All placeholders that cannot be resolved are removed.<br><strong>Also shortcodes are supported.</strong> You have to replace the bracket [ with {{ and ] with }}. So if the shortcode is [link] you have to use {{link}} because shortcode attributes which include shortcodes are not supported directly by Wordpress. Also be aware of single and double quotations: src="http://demo.{{url domain=\'home\'}}/url". So only use \' for attributes of the nested shortcode.<br><strong>BBCode:</strong> If you have special characters e.g. [] in the url you need to use the bbcode style for the url: [advanced_iframe]url[/advanced_iframe].<br><strong>PDF support: </strong>If you include a pdf google doc is used to render the pdf. This solution looks the same on all browsers. If you want to use the native pdf renderer of the browser/your system add NATIVE: before the url. Like NATIVE:http://www.example.com/pdf.pdf.<br>Shortcode attribute: src=""', 'advanced-iframe');
} else {
        $src_text =  __('Enter the full URL to your page. e.g. http://www.tinywebgallery.com. <strong>Please do not use a different protocol for the iframe: Do not mix http and https if possible! Http pages are NOT shown in https pages.</strong> Please read <a href="http://www.tinywebgallery.com/blog/iframe-do-not-mix-http-and-https" target="_blank">this post</a> for details. If you cannot save the full url because of mod_security don\'t specify the protocoll (e.g. //www.tinywebgallery.com) or leave this field empty and define the src in the shortcode. Also use the free url checker below to make sure that you can include the page. You can also add parameters to this url like http://www.tinywebgallery.com/test.php?iframe=true. Then you can check this variable and use it to e.g. hide some elements in the iframe.', 'advanced-iframe');
}        

        printTextInputSrc(false,$devOptions, __('<b>Url</b>', 'advanced-iframe'), 'src', $src_text, 'text', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/url-features');
?>
      <tr>
        <th scope="row"><strong><?php _e('Free url checker', 'advanced-iframe'); ?></strong>
        </th>      <td>
          <?php _e('<strong>Not all pages</strong> can be included in an iframe because they have a header flag this does not allow this. The free iframe checker is already included now in the administration. The <a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/free-iframe-checker/">Free iframe checker</a> page has a 2nd step where you also can see if iframe killer scripts are running!', 'advanced-iframe'); ?></td>
      </tr>
<?php
        printNumberInput(false,$devOptions, __('Width', 'advanced-iframe'), 'width', __('The width of the iframe. You can specify the value in px or in %. If you don\'t specify anything px is assumed. Pro user can also do basic calculations here if you have e.g. a fix left navigation on a page. e.g. 100%-200px. Also vw is now supported. vw is viewport width. This is more important for the height with vh! See <a target="_blank" href="http://caniuse.com/#feat=calc">http://caniuse.com/#feat=calc</a> for supported browsers! Shortcode attribute: width=""', 'advanced-iframe'));
        printNumberInput(false,$devOptions, __('Height', 'advanced-iframe'), 'height', __('The height of the iframe. You can specify the value in px or in %. If you don\'t specify anything px is assumed. Please note that % does most of the time does NOT give the expected result (e.g. 100% is only 150px) because the % are not from the iframe page but from the parent element. If you like that the iframe is resized to the content please go to \'<a id="resize-same-link" href="#rt">Resize the iframe to the content height/width</a>\' if you are one hte same domain or the "<a id="external-workaround-link" href="#xss">External workaround</a>" if the iframe is on a diffent domain. Also vh is now supported! e.g. 100vh means 100% of the viewport height. This is the "fullscreen" many users look after. This is now supported by all major browsers. See <a href="https://caniuse.com/#feat=viewport-units" target="blank">here</a>. Pro user can also do basic calculations here if you have e.g. a fix header or footer on a page. e.g. 100%-200px. See <a target="_blank" href="http://caniuse.com/#feat=calc">http://caniuse.com/#feat=calc</a> for supported browsers! Shortcode attribute: height=""', 'advanced-iframe'));
        printAutoNo($devOptions, __('Scrolling', 'advanced-iframe'), 'scrolling', __('Defines if scrollbars are shown if the page is too big for your iframe. Please note: If you select \'Yes\' IE does always show scrollbars on many pages! So only use this if needed. Scrolling "none" means that the attribute is not rendered at all and can be set by css to enable the scrollbars responsive.  Shortcode attribute: scrolling="auto" or scrolling="no" or scrolling="none"', 'advanced-iframe'));
        if ($evanto || $isDemo) {
            printTrueFalse(true,$devOptions, __('i-20-Enable scrolling on ipad and iphone ', 'advanced-iframe'), 'enable_ios_mobile_scolling', __('Currently mobile ios devices like ipad and iphone do not support scrolling inside an iframe properly. This changes from version to version. By enabling this parameter an additional div with additional ios css styles is wrapped around the iframe which does the scrolling. This feature is currently supported for simple iframes, show iframe as layer and show only a part of an iframe when scrolling is enabled. Please test this feature with all the ios devices you want to support! This feature does use the internal browser detection. So the additional div is only rendered for mobile ios devices! Zoom is currenly only supported in the "show only a part of the iframe" mode! For all features where auto height is enabled the additional div is also not rendered as there no scrolling does exist. Currently the default is set to false. As soon as many users also report that this is working on many devices the default will be set to true. So please report if this features does/doesn\'t work for you! Shortcode attribute: enable_ios_mobile_scolling="true" or enable_ios_mobile_scolling="false" ', 'advanced-iframe'), false, 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/scrolling-on-ipad-and-iphone');
        }
        printNumberInput(false,$devOptions, __('Margin width', 'advanced-iframe'), 'marginwidth', __('The margin width of the iframe. You can specify the value in px. If you don\'t specify anything px is assumed.  Shortcode attribute: marginwidth=""', 'advanced-iframe'));
        printNumberInput(false,$devOptions, __('Margin height', 'advanced-iframe'), 'marginheight', __('The margin height of the iframe. You can specify the value in px. If you don\'t specify anything px is assumed.  Shortcode attribute: marginheight=""', 'advanced-iframe'));
        printNumberInput(false,$devOptions, __('Frame border', 'advanced-iframe'), 'frameborder', __('The frame border of the iframe. You can specify the value in px. If you don\'t specify anything px is assumed.  Shortcode attribute: frameborder=""', 'advanced-iframe'));
        printTrueFalse(false,$devOptions, __('Transparency', 'advanced-iframe'), 'transparency', __('If you like that the iframe is transparent and your background is shown you should set this to \'Yes\'. If this value is not set then the iframe is transparent in IE but transparent in e.g. Firefox. So by default you should leave this to \'Yes\'. Shortcode attribute: transparency="true" or transparency="false" ', 'advanced-iframe'));
        printTextInput(false,$devOptions, __('Class', 'advanced-iframe'), 'class', __('You can define a class for the iframe if you like. Shortcode attribute: class=""', 'advanced-iframe'));
        
        if ($evanto) {
            $style_fs = '<br><input type="button" onclick="aiPresetFullscreen(); return false;" value="Set settings for fullscreen iframe" name="presetFullscreen" class="button-primary" id="presetFullscreen" />';
        } else {
            $style_fs = '';  
        }
        printTextInput(false,$devOptions, __('Style', 'advanced-iframe'), 'style', __('You can define styles for the iframe if you like. The recommended way is to put the styles in a css file and use the class option. With the button below the width, height, content_id, content_styles, hide_content_until_iframe_color and the needed styles above for a fullscreen iframe are set. Also check the settings at the height where you can do calculations to add fixed headers/footers. Shortcode attribute: style=""' . $style_fs , 'advanced-iframe'));
        printTextInput(false,$devOptions, __('Id', 'advanced-iframe'), 'id', __('Enter the \'id\' attribute of the iframe. Allowed values are only a-zA-Z0-9_. Ids cannot start with a number!!! Do NOT use any other characters because the id is also used to generate unique javascript functions! Other characters will be removed when you save! If a src directly in a shortcode is set and no id than an id is generated automatically if several iframes are on one page to avoid configuration problems. Shortcode attribute: id=""', 'advanced-iframe'));     
        printTextInput(false,$devOptions, __('Name', 'advanced-iframe'), 'name', __('Enter the \'name\' attribute of the iframe. Shortcode attribute: name=""', 'advanced-iframe'));
        printTrueFalse(false,$devOptions, __('Allow full screen', 'advanced-iframe'), 'allowfullscreen', __('allowfullscreen is an HTML attribute that enables videos to be displayed in fullscreen mode. Currently this is a new html attribute not supported by all browsers. So please check  all of the browsers you want to support. Shortcode attribute: allowfullscreen="true" or allowfullscreen="false"', 'advanced-iframe'));
        printTextInput(false,$devOptions, __('Sandbox', 'advanced-iframe'), 'sandbox', __('Enter the \'sandbox\' attribute of the iframe. See <a href="http://www.w3.org/TR/2011/WD-html5-20110525/the-iframe-element.html#attr-iframe-sandbox" target="_blank">w3c</a> or <a href="http://www.w3schools.com/tags/att_iframe_sandbox.asp" target="_blank">w3schools</a> for details. To render sandbox without a value for all restrictions please enter "sandbox". Shortcode attribute: sandbox=""', 'advanced-iframe'));     
          printTextInput(false,$devOptions, __('Title', 'advanced-iframe'), 'title', __('The html title attribute of an iframe. Shortcode attribute: title=""', 'advanced-iframe'));
?>
    </table>
<?php if ($devOptions['single_save_button'] == 'false') { ?>
    <p class="button-submit">
      <input id="gs" class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>
    </p>
<?php } ?>    
</div>
