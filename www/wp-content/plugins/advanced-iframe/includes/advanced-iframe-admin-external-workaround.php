<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
?>
<br />
<div>
        <div id="icon-options-general" class="icon_ai">
      <br>
    </div><h2>
      <?php _e('External workaround: Howto enable cross domain resize and modification', 'advanced-iframe') ?></h2>  
      <h3><?php _e('Use this solution if the iframe is NOT on the same domain and you want features like auto height and css modifications.', 'advanced-iframe') ?></h3>
    <p><?php _e('The external workaround does enable many features which are not possible directly because of cross domain security restrictions. You need to include a Javascript file (ai_exernal.js) to the page in the iframe which is generated dynamically with your settings. <strong>If you mix http and https pages you NEED to enable "<a href="#upm">Use postMessage for communication</a>" and follow  <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-with-post-message#e51" target="_blank">example 53</a> for this advanced setup (pro version needed)</strong>! In the advanced tab are many settings marked with ', 'advanced-iframe');
    echo renderExternalWorkaroundIcon(true);
    _e('. This means that this setting is saved to the ai_exernal.js', 'advanced-iframe') ?>
    </p>
    <p><h4><?php _e('Important: You need to be able to modify the external web page in the iframe to use the workaround!', 'advanced-iframe') ?></h4></b></p>
    
    <p><b><?php _e('Step 1: Check if the page in the iframe has the same <a href="https://en.wikipedia.org/wiki/Second-level_domain" target="_blank">second level domain</a>. e.g. www.example.com, subdomain.example.com -> second level domain: example.com: <ul class="ai-src-list"><li>Yes - Only read the box "Special case sub domain" </li><li>No - Go to "<a class="link-external-domain" href="#h-external-domain">If you are on a different domain</a>" section and follow the instructions there!</li></ul>', 'advanced-iframe') ?></b></p>
    
    <div class="manage-menus nounderline sub-domain-container hide-search">
<strong class="hide-search"><big><?php _e('Special case sub domain:', 'advanced-iframe') ?></big></strong><p><?php _e('If your iframe has the same second level domain an easier way is possible. You still have to include one line of Javascript but the whole configuration is than like you are on the same domain and all settings can be done by shortcode. To enable this you need to set the value "document.domain" to your second level domain (e.g. parent: www.example.com, iframe: subdomain.example.com -> second level domain: example.com) with Javascript on both pages. The plugin does set this value to the domain below if you enable this feature. See <a href="http://www.tinywebgallery.com/blog/using-sub-domains-with-advanced-iframe" target="_blank">this blog entry</a> or <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/sub-domain-auto-height-and-css-modifications" target="_blank">example 42</a> for further details.
</p><p>
Please note: The resize implementation for the same domain is the same for the pro and the free version. But the external workaround of the PRO version has additional options like you can define the element to measure and it has some additional tricks that the measurements do work. So if you have problems with the auto height don\'t use this sub domain workaround but the solution with ai_external.js below!
', 'advanced-iframe') ?> 
</p>
<table class="form-table">
<?php
        printTrueFalse(false,$devOptions, __('Set document.domain', 'advanced-iframe'), 'add_document_domain', __('Set the document.domain value to the setting below.', 'advanced-iframe'), "false",'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-auto-height-and-css-modifications',false);


if (empty($devOptions['document_domain'])) {
  $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https:' : 'http:';
  $url = $protocol . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];         
  $currentDomain = aiGet2ndLvlDomainName($url);
  $devOptions['document_domain'] = $currentDomain;
}
         printTextInput(false,$devOptions, __('Second level domain', 'advanced-iframe'), 'document_domain', __('The domain both of your pages have in common. So if your parent is www.example.com and your sub domain is test.example.com your main domain is example.com. Advanced iframe pro tries to detect your main domain but this is not always correct. So please change the value if it is not your main domain. Shortcode: document_domain=""', 'advanced-iframe'), 'text', '',false);
?>
</table>
<p>
<?php _e('If the domain above is correct please add', 'advanced-iframe'); ?> 
</p>
<p>
     &lt;script&gt;document.domain='<?php echo $devOptions['document_domain']; ?>';&lt;/script&gt;
</p>
<p>
<?php _e('anywhere to the source code of your page of the iframe (!). 
<br />
After you have done this you can configure Advanced iFrame like you are on the same domain. Don\'t use the external workaround like describe below as now the configuration is much easier directly on the advanced tab.
', 'advanced-iframe'); ?>  
</p>    




 
</div>
<h3 id="h-external-domain">If you are on a different domain (not sub domain):</h3>    
  
<?php   
       $script_name = dirname(__FILE__) . '/../js/ai_external.js';
       if (!file_exists($script_name)) {
        echo '<p class="shortcode hide-print">';
        _e('The file ai_external.js is not generated yet. Please save the configuration once to create this file.', 'advanced-iframe');
        echo '</p>';
      }
      
      $channel = $devOptions['use_post_message'] == 'false' ? 'iframe' : 'window.postMessage';
      
      _e('<p><strong class="bold">Info:</strong> Currently selected communication channel: <strong class="bold"><a href="#upm">', 'advanced-iframe');
      echo $channel; 
      _e('</strong></a>. See <a class="post-message-help-link" href="#">here</a> what this means.</p>', 'advanced-iframe');
      
          
      _e('
      <p>Everything is already prepared what you need on the parent domain. For the remote page the Javascript file ai_external.js is generated when you save the settings. This file hat to be <strong class="bold">included into your external iframe page</strong>:
      </p>
      <ol>
        <li><b class="bold">Add the following Javascript to the external web page you want to show in the iframe</b> (The optimal place is before the &lt;/body&gt; if possible. Otherwise put it in the head section. NEVER place it just after the &lt;body&gt; as than the height of the script element would be measured!):', 'advanced-iframe') ?>
        <div class="manage-menus nounderline sub-domain-container hide-search">
          <p class="bold">&lt;script src="<?php echo plugins_url(); ?>/advanced-iframe/js/ai_external.js"&gt;&lt;/script&gt;</p>
        </div>
     <p>
     <a href="#" onclick="jQuery('#details-javascript').show(); return false;" ><?php _e('Show me what the Javascript does', 'advanced-iframe') ?></a>
     <div id="details-javascript" >
<?php _e('
    The Javascript does the following:
         <ul>
           <li>Adds "aiUpdateIframeHeight()" to the onload event of the page</li>
           <li>Sends the height, width and optional data to the parent to enable auto height</a>
           <li>Modifies the remote iframe page (pro version only)
       ', 'advanced-iframe');
    if ($evanto || $isDemo) {
        _e(' - <a href="#mirp">Please see below how to configure this</a>.', 'advanced-iframe');
    }
    _e('</li>
           <li>Adds the communication iframe dynamically if iframe communication is used</li>
           <li>Adds an optional wrapper div below the body that the height can be measured properly</li>
           <li>Removes any margin, padding from the body</li>
           <li>Adds a temporary overflow:hidden to the body to avoid scrollbars</li>    
         </ul>
          ', 'advanced-iframe');
    ?>
    </div>
    </p>
        </li>
        <li>
          <?php _e('Add enable_external_height_workaround="true" to your shortcode! This is needed to disable the settings with the ', 'advanced-iframe');
          echo renderExternalWorkaroundIcon(true);
          _e(' for the same domain.', 'advanced-iframe');
           ?>
        </li>
        
        <li>
          <?php _e('Enable the features you want to use. <strong>Please note:</strong> All settings here and also in the other sections which are marked with a ', 'advanced-iframe');
      echo renderExternalWorkaroundIcon(true);
      _e(' are saved to the external ai_external.js workaround file!  ', 'advanced-iframe');
           ?>
        </li>
        <li>
           <?php _e('Done.', 'advanced-iframe'); 
           ?>
            <a href="#" onclick="jQuery('#details-not-work').show(); return false;" ><?php _e('Click here if it does not work.', 'advanced-iframe') ?></a>
            
             <div id="details-not-work" >
<?php _e('
    <p>If it does not work please check the following things:</p>
         <ol>
           <li>Use Chrome for debugging</li>
           <li>Force a full reload (win: ctrl + F5)</li>
           <li>Clean the browser cache</li>
           <li>I recommend to start with auto height only. Open the Javascript console (press F12 to open the developer tools of the browser), check for errors and fix them. Advanced iframe does also show configuration errors there!</li>
           <li>Check if ai_external.js is loaded in the network tab (F12). If you do not see it check if the file is included properly. 
           <li>If you have enabled "<a href="#upm">Use postMessage for communication</a>" set it to "Debug" and check the output in the Javascript console.</li>
           <li>If you use iframe communication check the network tab (F12), filter for doc/html and look for height.html. There you should see the measured height as parameter. If this does not show switch to <a href="#upm">postMessage communication</a> as this way has less restrictions.</li>
           <li>If you mix http and https you need to enable "<a href="#upm">Use postMessage for communication</a>" and "Support WP multisite"</li>      
           <li>Enable the internal debug console (Options -> Debug Javascript) if you have only problems on mobile devices.</li>
           <li>Go to the Help/ FAQ tab. There you find additional infos and links to the FAQ and the forum</li>      
        
         </ol>
          ', 'advanced-iframe');
    ?>
    </div>
            
        </li>
      </ol>
    </p>

      <table class="form-table">
<?php
        printTrueFalseHeight(false,$devOptions, __('Resize remote iframe to content height', 'advanced-iframe'), 'enable_external_height_workaround', __('Enable the auto height workaround by enabling it in the generated Javascript file ai_external.js. This settings only works if you have included the Javascript to the remote page.<br><strong>Important</strong>:<br />"Yes" does disable all settings with a ' . renderExternalWorkaroundIcon(true) . ' in the administration for the <strong>same</strong> domain and enables auto height in the ai_externals.js! This is needed as otherwise the plugin would try to use this settings directly which causes Javascript security errors! Only set this if ALL of your iframes are remote!<br />"External" does enable the setting only in the ai_externals.js. This is the default now as auto height is than working right away. You need to set enable_external_height_workaround="true" or use_shortcode_attributes_only="true" in the shortcode for iframes with external pages that the settings from the administration are not used directly.<br/>So if you use several iframes with the external workaround and the same domain you should set this setting to "External" and set enable_external_height_workaround="true" in the short code for full flexibility. <a href="#mirp">Please see below</a> how to configure ai_external.js directly.', 'advanced-iframe'), "external",'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-auto-height-and-css-modifications',true);
         if ($evanto || $isDemo) {  
          printTextInput(true,$devOptions, __('Element to measure', 'advanced-iframe'), 'element_to_measure', __('This parameter defines the element that is measured on the remote page. "default" tries to measure the first element of the body or the wrapper div. But sometimes this does not work if e.g. the element does return a wrong height because of css styles or dynamically added elements. You can use the id directly here or you can also a valid <a class="jquery-help-link" href="#">jQuery selector pattern</a> here that start with # or .!  Please read the section "<a class="howto-id-link" href="#">How to find the id and the attributes</a>" to find the right id or class. This setting cannot be set by a shortcode. <a href="#mirp">Please see below</a> how to configure ai_external.js directly.', 'advanced-iframe'), 'text', '',true);
         printHeightNumberInput(true,$devOptions, __('Additional height', 'advanced-iframe'), 'element_to_measure_offset', __('If you like that the iframe is higher than the calculated value you can add some extra height here. This number is then added to the calculated one. This is e.g. needed if one of your tested browsers displays a scrollbar because of 1 or 2 pixel. Or if there is an additional margin around the body. This setting cannot be set by a shortcode. <a href="#mirp">Please see below</a> how to configure ai_external.js directly.', 'advanced-iframe'),'text', '', true);
         printNumberInput(true,$devOptions, __('Resize/ css modification delay', 'advanced-iframe'), 'external_height_workaround_delay', __('Sometimes the external page does not have its full height and all elements after loading because e.g. parts of the page are build by Javascript. If this is the case you can define a timeout in millisecounds until the resize and the modification of elements are called. Otherwise set a 0 to disable the delay. This setting cannot be set by a shortcode. <a href="#mirp">Please see below</a> how to configure ai_external.js directly.', 'advanced-iframe'),'text', '', '', true);
         }
        
        printTrueFalse(false,$devOptions, __('Keep overflow:hidden after resize', 'advanced-iframe'), 'keep_overflow_hidden', __('By default overflow:hidden (removes any scrollbars inside the iframe) is set during the resize to avoid scrollbars and is removed afterwards to allow scrollbars if e.g. the content changes because of dynamic elements. If you set this setting to true the overflow:hidden is not removed and any scrollbars are not shown. This is e.g. helpful if the page is still to wide! If you want to use several iframes please use the description below for configuration. These settings only works if you have included the Javascript to the remote page. This setting cannot be set by a shortcode. <a href="#mirp">Please see below</a> how to configure ai_external.js directly.', 'advanced-iframe'), "false",'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-auto-height-and-css-modifications',true);
        printTrueFalse(false,$devOptions, __('Hide the iframe until it is completely modified.', 'advanced-iframe'), 'hide_page_until_loaded_external', __('This setting hides the iframe until the external workaround is completely done. This prevents that you see the original site before any modifications. You need to enable this AND in the shortcode. The normal "Hide the iframe until it is loaded" shows the iframe after all modifications are done which are all done by a local script. This way cannot be used for the external workaround because the exact time when the external modifications are done is unknown. Therefore the setting in the shortcode does hide in iframe until the external workaround does call iaShowIframe after all modifications are done. Shortcode attribute: hide_page_until_loaded_external="true" or hide_page_until_loaded_external="false"', 'advanced-iframe'), "false",'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-auto-height-and-css-modifications',true);
    if ($evanto || $isDemo) {
        printTrueFalse(true,$devOptions, __('Write css directly', 'advanced-iframe'), 'write_css_directly', __('By default changes off the iframe are made by jQuery after the page is loaded. This is the only way this is possible if you do this directly. But with the external workaround it is now also possible that the style is written directly to the page. It is written where the ai_external.js is included. So if you use this option you need to include the ai_external.js as last element in the header. This setting has the advantage that the changes are not done after the page is loaded but when the browser renders the page initially. Also the page is not hidden until the page is fully modified. The settings "Hide elements in iframe" and "Modify content in iframe" are supported! This setting cannot be set by a shortcode. <a href="#mirp">Please see below</a> how to configure ai_external.js directly.', 'advanced-iframe'), "false", 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/resize-on-element-resize#e27',true);
        printTextInput(true,$devOptions, __('Iframe redirect url', 'advanced-iframe'), 'iframe_redirect_url', __('If you like that the page you want to include can only be viewed in your iframe you can define the parent url here. If someone tries to open the url directly he will be redirected to this url. Existing parameters from the original url are added to the new url IF no ? is found in the  iframe_redirect_url. You need to add the possible parameters to the "URL forward parameters" that they will be passed to the iframe again. This setting does use Javascript for the redirect. If Javascript is turned off the user can still access the site. If you also want to avoid this add "html {visibility:hidden;}" to the style sheet of your iframe page. Than the page is simply white. The Javascript does set the page visible after it is loaded! iframe_redirect_url does now also check if the page is included by the domain specified. Otherwise it is redirected to the iframe_redirect_url. This way you can make sure that the iframe page can only be included by a page on your domain. Please see the additional option below you have if you define this parameter before the ai_external.js. This setting cannot be set by a shortcode. <a href="#mirp">Please see below</a> how to configure ai_external.js directly.', 'advanced-iframe'), 'text', '',true);
        printTextInput(true,$devOptions, __('Add the id to the url of the iframe', 'advanced-iframe'), 'pass_id_by_url', __('This feature adds the id of the iframe to the iframe url. The id is than extracted on the iframe and used as value for the callback to find the right iframe on the parent side. The static way is to set iframe_id (Please see below). The dynamic solution has to be used if you want to include the same page several times to the parent page (e.g. the page you include is called with different parameters and shows different content). You specify the parameter that is added to the url. So e.g. ai_id can be used. Allowed values are only a-zA-Z0-9_. Do NOT use any other characters. You need to set the parameter here or by setting iframe_url_id before you include ai_external.js. Please note the if you specify it here ALL shortcodes with use_shortcode_parameters_only="true" need pass_id_by_url to be set. See example 27 for a working setup. Shortcode: pass_id_by_url=""', 'advanced-iframe'), 'text', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/resize-on-element-resize#e27',true);
        printTrueFalse(true,$devOptions, __('Support WP multisite', 'advanced-iframe'), 'multi_domain_enabled', __('By default the callback url is rendered to the ai_external.js. For WP multi sites the default setting does than only work for one of the domains. "Yes" does enable that the Javascript compares the default url with the one from the referrer. Than the default domain will be exchanged with the one from the referrer. This only works if the domain is the only difference between the multi sites. <a href="#mirp">Please see below</a> how to configure ai_external.js directly. If you only enable this in ai_external.js directly you need to use multi_domain_enabled="true" in the shortcode! If you use postMessage for communication please read the documenation there how to use this setting!', 'advanced-iframe'), "false", '',true);
     }
?>     
</table>      
<?php if ($evanto || $isDemo) { ?>
<div id="upm" class="manage-menus nounderline sub-domain-container hide-search">
<table class="form-table">     
<?php     
        printTrueDebugFalse($devOptions, __('Use postMessage for communication', 'advanced-iframe'), 'use_post_message', __('From version 7.4, this is the default communication way between the iframe and the parent for new installations of the pro version. See <a class="post-message-help-link" href="#">here</a> for the two different communication ways and what is the best one for you. If you have any problems with windows.postMessage select "Debug" and additional log information about the transfered data is printed to the browser console. Use F12 at your browser to open the developer tools. The administration does save the current url as targetOrigin into the ai_external.js. If have a multi site or you include your page into differnt parents than you need the pro version and select "Support WP multisite" to "Yes" as than * is used as targetOrigin. Also use post communication if you have a https page in the iframe and your page is http. Please see <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-with-post-message#e51" target="_blank">example 53</a> for this advanced setup! <a href="#mirp">Please see below</a> how to configure ai_external.js directly. If you only enable this in ai_external.js directly you need to use use_post_message="true"/"debug" in the shortcode!', 'advanced-iframe'), 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-with-post-message#e51',true);
     if ($evanto || $isDemo) {
        printTextInput(true,$devOptions, __('i-20-Include content directly from the iframe', 'advanced-iframe'), 'data_post_message', __('When you enable post communication you can read elements from the iframe and transfer it to the parent and include it there. This is like the feature "Include content directly" from the "Add files/content" from the next tab  but more powerful. You can define here as many elements you like and insert it to the parent page. To enable this setting you need to specify ther element of the parent and the element of the iframe seperated by a |. Several settings are seperatd by , e.g. #c-id|#content,#s-id|#some-images,#p-id|#iframe-right p:nth-child(2). You can use any valid <a class="jquery-help-link" href="#">jQuery selector pattern</a> here! Currently the iframe is NOT hidden by default. After the setup you need to set display:none; on the basic tab at "Style". Currently there are no additional settings like for the same domain. So make sure that e.g. the divs you want to add the content have e.g. the correct height for optimal display! This setting cannot be set by a shortcode. <a href="#mirp">Please see below</a> how to configure ai_external.js directly.', 'advanced-iframe'), 'text', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-with-post-message#e52',true); 
     }     
    ?></table></div>
<?php } ?>
    <p>
    <?php _e('<strong>Please note:</strong> If you change the settings above I recommend to reload the iframe page in a different tab because otherwise the page is cached by many browsers!', 'advanced-iframe') ?>
    </p>
   
<?php if ($devOptions['single_save_button'] == 'false') { ?>
     <p class="button-submit">
      <input id="external-button" class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>
    </p>
<?php } ?>
    <p>
      <?php _e('Please test with <strong>all</strong> browsers! If the wrapper div is needed (It has a transparent border of 1px!) and it causes layout problems you have to remove the wrapper div in the Javascript file and you have to measure the body. The alternative solution is stored as comment in the Javascript file. The Javascript file is regenerated each time you save the settings on this page.', 'advanced-iframe') ?>
    </p>
    <?php
    if ($evanto || $isDemo) {
     _e('
    <h3 id="mirp">How to configure the "Modifies the remote iframe page" options</h3>
    <p>
    The configuration which is rendered by default to the Javascript is the one you enter in the settings of <a class="modifycontent-link" href="#">"Modify the content of the iframe if the iframe page is on the same domain"</a>.
    </p>', 'advanced-iframe');
    }
    _e('
    <h3 id="mirp">How to configure the workaround file ai_external.js directly to work with different settings.</h3>
    <p>
        The file ai_external.js is created when you save the settings.
        If you want to have different settings for different pages you can define the parameters which are used
        in the script before you include the file ai_external.js.
    <p>
    <div class="manage-menus nounderline hide-always">
    <strong>Please note:</strong> All parameters can be set in the administration. This are the settings where a ', 'advanced-iframe');
    echo renderExternalWorkaroundIcon(true);
    _e(' is shown! You only need to define variables before the script or a configuration file if you need to include the SAME ai_external.js with DIFFERENT configurations. See the <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-faq#config" target="_blank">FAQ</a> for more details.
    </div>
    <p>The following parameters can be used:
    ', 'advanced-iframe');
    ?>   
    </p><p>
      <a href="#" onclick="jQuery('#all-parameters').show(); return false;" > <?php _e('Show me the parameters.', 'advanced-iframe') ?></a>
    </p>
      <?php
        _e('<div id="all-parameters" class="hide-always">
       <ul class="ulli">
         <li>iframe_id - By default the id from the settings are used. If you want to use several iframes on the same page with the external workaround you need to specify the id from the shortcode.</li>
         <li>updateIframeHeight - Enable/disable the resize height workaround. Valid values are "true", "false".</li>
         <li>keepOverflowHidden - Enable/disable if the overflow:hidden is kept. Valid values are "true", "false".</li>
          <li>hide_page_until_loaded_external - Enable/disable that the page is hidden until fully modified. Valid values are "true". Needs only to be set on the remote site if you do not use auto height because otherwise no request is sent back!, "false".</li>
      ', 'advanced-iframe');
    if ($evanto || $isDemo) {
      _e('
          <li>iframe_hide_elements - See <a class="modifycontent-link" href="#">Hide elements in iframe</a>.</li>
          <li>onload_show_element_only - See <a class="modifycontent-link" href="#">Show only one element</a></li>
          <li>iframe_content_id - See <a class="modifycontent-link" href="#">Content id in iframe</a></li>
          <li>iframe_content_styles - See <a class="modifycontent-link" href="#">Content styles in iframe</a></li>
          <li>change_iframe_links - See <a class="modifycontent-link" href="#">Change iframe links</a></li>
          <li>change_iframe_links_target - See <a class="modifycontent-link" href="#">Change iframe links target</a></li>
          <li>onload_resize_delay - See resize delay above. E.g. var onload_resize_delay=100; means 100 ms resize delay. You also need this setting when you use the hidden tabs feature.</li>
          <li>iframe_redirect_url - Defines an url which is loaded if the page is not included in an iframe. See "Iframe redirect url" above. As an additional option you can use   Javascript to add e.g. the current domain to the redirect url! So e.g. var iframe_redirect_url = "http://parent-domain?page=" + escape(window.location.href); would add the iframe url as parameter to the parent. If a ? is found in the iframe_redirect_url then the parameters of the iframe page are NOT added to the redirect url!</li>
          <li>write_css_directly - See "Write css directly" above. Valid settings are write_css_directly="true" or write_css_directly="false". </li>
          <li>additional_css_file_iframe - The ai_external.js can also load an additional css file. This is loaded at the end of ai_external.js. The advantage using this is that the file is only loaded if the page is inside the iframe and is not loaded when accessed directly. Please note that the file is loaded asynchronously.</li>
          <li>additional_js_iframe - The ai_external.js can also write additional Javscript. This is loaded at the end of ai_external.js. The advantage using this is that the Javascript is only loaded if the page is inside the iframe and is not loaded when accessed directly.</li>
          <li>additional_js_file_iframe - The ai_external.js can also load an additional Javascript file. This is loaded at the end of ai_external.js. The advantage using this is that the file is only loaded if the page is inside the iframe and is not loaded when accessed directly. Please note that the file is loaded asynchronously.</li>
          <li>resize_on_element_resize - See "Resize on element resize"</li> 
          <li>resize_on_element_resize_delay - See "Poll interval for the resize detection"</li> 
          <li>iframe_url_id - See "Add the id to the url of the iframe"</li> 
          <li>element_to_measure - You can define the element you want to measure + a offset for fix content. This is sometimes needed for some themes where e.g. chrome returns 0 as height. You need to specify a id. So no # is allowed here.</li> 
          <li>element_to_measure - The element you want to measure. See "element to measure" above</li> 
          <li>element_to_measure_offset - The offset for a fix content </li> 
          <li>add_iframe_url_as_param - See "Add iframe url as param"</li>
          <li>additional_styles_wrapper_div - Adds additional styles to the wrapper div. Depending on the html/css this is sometimes needed that the element can be measured correctly. overflow:auto; is sometimes needed!</li>
          <li>domainMultisite - Enable/disable multi site settings. See above. Valid values are "true", "false".</li>
          <li>usePostMessage -  Enable/disable the usage of postMessage for communication. See above. Valid values are true, false.</li>
          <li>debugPostMessage -  Enable/disable the debug of postMessage for communication. See above. Valid values are true, false.</li>
          <li>dataPostMessage - Defines the elementes that should be transfered to the client. See above. </li>
          ', 'advanced-iframe');
          
    }
      _e('
      </ul>
      </div>
     <p>An example would look like this:
     </p>
     <p>
        &lt;script&gt;<br />
        &nbsp;&nbsp;&nbsp;var updateIframeHeight = "true";<br />
        &nbsp;&nbsp;&nbsp;var keepOverflowHidden = "false";<br />
        &lt;/script&gt;<br />
      ', 'advanced-iframe') ?>
        &lt;script src="<?php echo plugins_url(); ?>/advanced-iframe/js/ai_external.js"&gt;&lt;/script&gt;  
    </p>
    <?php if ($evanto || $isDemo) { ?>  
    <h3>
    <?php _e('Config files for the external workaround', 'advanced-iframe') ?>
    </h3>  
    <p>
    <?php _e('Defining the variables before the script has the disadvantage that you need to modify the html of the remote domain for every change and also the code there can get really big. Therefore it is now possible to use config files which are located on the parent server and loaded dynamically from the external_ai.js. Config files have to be placed in the folder "advanced-iframe-custom" in the plugin directory (This directory does not exist by default and is created if you use the create input filed below) and need to follow this naming convention: ai_external_config_&lt;config_id&gt;.js. This file does contain exactly the variables described before. Then you need to add the config_id as parameter to the external_ai.js like this:  ..../ai_external.js?config_id=&lt;config_id&gt;. The config_id can only contain alphanumeric characters, - and _.', 'advanced-iframe') ?>
    </p>
    <p>
    <?php _e('You can also include the config file directly before the ai_external.js script. This gives you the same flexibility but you have to include 2 scripts. The performance including this directly is a little bit better because no internal loading has to be done!', 'advanced-iframe') ?>
    </p>
     <p>
    <?php _e('<strong>Please note</strong>: The folder "advanced-iframe-custom" is used because Wordpress does delete the whole plugin folder at an update and all your settings would be lost! If you delete the plugin completely you need to remove the folder "advanced-iframe-custom" manually if you don\'t want to keep this settings!', 'advanced-iframe') ?>
    </p>
   
    
     <p>
     <a href="#" onclick="jQuery('#details-config').show(); return false;" ><?php _e('Show me the example above with a config file', 'advanced-iframe') ?></a>
     <div id="details-config" >
     <p>
<?php _e('In this example the config_id "example" is used.', 'advanced-iframe'); ?>
    </p>
    <ol>
    <li><?php _e('First create a file called "ai_external_config_example.js" in the folder "advanced-iframe-custom" of the plugin directoy (or create the file below) and save the following lines there:<br />
        &nbsp;&nbsp;&nbsp;var updateIframeHeight = "true";<br />
        &nbsp;&nbsp;&nbsp;var keepOverflowHidden = "false";', 'advanced-iframe') ?><br />
    </li>
    <li>
      <?php _e('a. Add the parameter ?config_id=example to the external_ai.js', 'advanced-iframe') ?><br />
        &nbsp;&nbsp;&nbsp;&nbsp;&lt;script src="<?php echo plugins_url(); ?>/advanced-iframe/js/ai_external.js?config_id=example"&gt;&lt;/script&gt;
        <br />
        or
        <br />
        b. &lt;script src="<?php echo plugins_url(); ?>/advanced-iframe-custom/ai_external_config_example.js"&gt;&lt;/script&gt;<br />
        &nbsp;&nbsp;&nbsp;&nbsp;&lt;script src="<?php echo plugins_url(); ?>/advanced-iframe/js/ai_external.js"&gt;&lt;/script&gt;
          
    </li>
    <li>  
    <?php _e('Done. Make sure that you refresh the browser cache if you make changes to your config file. <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-auto-height-and-css-modifications#e7" target="_blank">Example 7</a> shows a working setup.', 'advanced-iframe') ?>
    </li>
    </ol>
    </div>
    </p>
    <?php } ?>   
    <div class="hide-print">
    <h4><?php _e('Existing config files', 'advanced-iframe') ?></h4>
    <p><?php _e('The following configuration files do currently exist. Please note that you can view/edit this files with the plugin editor of Wordpress by clicking on the "Edit/View" link. Hover over the script you want to include and click 3 times fast to select it. Than add the line before your ai_external.js if you use the 2nd way to include the configuration.', 'advanced-iframe') ?> 
    </p>
<?php
  $config_files = array();
  foreach (glob(dirname(__FILE__) .'/../../advanced-iframe-custom/ai_external_config_*.js') as $filename) {
    $base = basename($filename);
    $base_url1 = site_url() . '/wp-admin/plugin-editor.php?file=advanced-iframe-custom%2F';
    $base_url2 = ''; // '&plugin=advanced-iframe%2Fadvanced-iframe.php';
    $config_files[] = $base ; 
  }
echo "<hr height=1>";
if (count($config_files) == 0) {
    echo "<ul><li>";
    _e('No custom configuration files found.', 'advanced-iframe');
    echo "</li></ul>";
} else {
  foreach ($config_files as $file) {
    echo '<div class="config-file-block"><div class="ai-external-config-label"><span class="config-list">' .$file .  '</span> &nbsp; <a href="'.$base_url1 . $file . $base_url2 .'">';
    _e('Edit/View', 'advanced-iframe');
    echo '</a>';
    $rid =  substr(basename($file, ".js"),19);
    echo ' &nbsp; <a class="confirmation post" href="options-general.php?page=advanced-iframe.php&remove-id='.$rid.'">';
    _e('Remove', 'advanced-iframe');
    echo '</a></div>';
    echo '<div class="ai-external-config">&lt;script src="' . plugins_url() . '/advanced-iframe-custom/'.$file.'"&gt;&lt;/script&gt;</div>';
    echo '</br></div>';
  }
}
echo "<hr height=1>";
?>  
    <p><?php _e('Create a config file with the following id:', 'advanced-iframe') ?><br />
      <input name="ai_config_id" id="ai_config_id" type="text" size="20" maxlength="20" /> 
      <input id="ccf" class="button-primary" type="submit" name="create-id" value="<?php _e('Create empty config file', 'advanced-iframe') ?>"/>
    </p>
    </div>
</div>

<h3><?php _e('Load different configurations depending on an url parameter', 'advanced-iframe') ?></h3>
<p><?php _e('Important: If you want to include one external page into more than one iframe only one configuration for the external page is possible by default. An advanced topic is to switch configurations depending on an url parameter. This requires to create a custom version of the config switcher template that is included in the js folder of the plugin and called ai_config_switcher_template.js.</p><p>Please make a copy of this file and copy it to the folder advanced-iframe-custom. If you want to have this config file listed above please use  the following naming ai_external_config_&lt;config_id&gt;.js. Please follow the documenation inside the config switcher file. If you need more complex configurations like this I recommend to get the professional support offered for this plugin because then an indivitual solution has to be designed and a custom version of the plugin would be needed.', 'advanced-iframe') ?>
</p>
   
   
   
   
