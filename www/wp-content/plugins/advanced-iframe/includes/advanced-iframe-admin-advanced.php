<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
?>
<div id="spacer-div"></div>
<?php if ($devOptions['accordeon_menu'] == 'false') { ?>
    <div class="ai-anchor" id="ad"></div>
<?php } ?>
<h1 id="h1-as"><?php _e('Advanced features', 'advanced-iframe'); ?></h1> 
<div>
  <div id="icon-options-general" class="icon_ai">
      <br>
    </div>
     <h2>
<?php  _e('Advanced features', 'advanced-iframe'); ?></h2>  
   <p>
   <?php _e('<p>The following options are already features which are not html standard anymore. All the options do already require additional Javascript, css or dynamic processing.</p>', 'advanced-iframe'); ?>
  </p>
<?php _e(' 
<div class="manage-menus nounderline hide-always">
<div class="small-menu">
<strong>Quicklinks:</strong>
</div> 
', 'advanced-iframe');

if ($evanto || $isDemo) {
_e(' 
<div class="small-menu">
  <a href="#rt">Auto height/width</a><br />
  <a href="#so">Show only a part of the iframe</a><br />
  <a href="#hi">Hide/cover parts of the iframe</a> 
</div>
<div class="small-menu">
  <a href="#mi">Modify the iframe</a><br />
  <a href="#mp">Modify the parent page</a><br />
  <a href="#ol">Open iframe in layer</a>
</div>

<div class="small-menu">
 <a href="#zo">Zoom</a><br />
  <a href="#la">Lazy load</a><br />
  <a href="#pa">Url parameter handling</a>
</div>
', 'advanced-iframe');
} else {
_e(' 
<div class="small-menu">
  <a href="#rt">Auto height/width</a><br />
  <a href="#mi">Modify the iframe</a>
</div>
<div class="small-menu">
  <a href="#mp">Modify the parent page</a><br />
  <a href="#pa">Url parameter handling</a>
</div>
', 'advanced-iframe');	
}

_e('<div style="clear:left;"></div>
</div>
', 'advanced-iframe');    
?>       
    
    <table class="form-table">
    <?php               
        printTrueIframeFalse($devOptions, __('Scrolls the parent window/iframe to the top', 'advanced-iframe'), 'onload_scroll_top', __('If you like that if you click on a link in the iframe the parent page should scroll to the top of the whole page you should set this to \'Yes\'. Please note that this is done by Javascript! So if a user has Javascript deactivated no scrolling is done. This setting generates the code onload="aiScrollToTop("id","true");" to the iframe. If you select the resize iframe as well then onload="aiResizeIframe(this);aiScrollToTop("your_id","true");" is generated. If you like a different order please enter the javascript functions directly in the onload parameter in the order you like. You can also scroll to the top of the iframe by selecting \'Iframe\'. Then this setting generates the code onload="aiScrollToTop("your_id","iframe");". Shortcode attribute: onload_scroll_top="true", onload_scroll_top="iframe" or onload_scroll_top="false" ', 'advanced-iframe'));

        printTrueFalse(false,$devOptions, __('Hide the iframe until it is loaded', 'advanced-iframe'), 'hide_page_until_loaded', __('This setting hides the iframe until it is loaded. This prevents the iframe white flash issue while loading. When you use the external workaround please check the setting for the "<a id="external-workaround-link" href="#xss">External workaround</a>". The setting there overwrites this setting because otherwise the iframe is maybe shown too early! Shortcode attribute: hide_page_until_loaded="true" or hide_page_until_loaded="false" ', 'advanced-iframe'));
    if ($evanto || $isDemo) {        
        printTrueFalse(true,$devOptions, __('Show loading icon', 'advanced-iframe'), 'show_iframe_loader', __('You can show a loading icon until the page in the iframe is fully loaded. You can use your own image with the size of 66 x 66 px by replacing the file img/loader.gif. Shortcode attribute: show_iframe_loader="true" or show_iframe_loader="false" ', 'advanced-iframe'),'false','http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/zoom-iframe-content');
        printTextInput(true,$devOptions, __('Hide the content until iframe is loaded', 'advanced-iframe'), 'hide_content_until_iframe_color', __('If you define a color here (e.g. #ffffff) the content of the main page is hidden until the iframe is loaded. Especially if the iframe does cover most of your page the iframe looks more integrated. If you use fullscreen iframes sometimes it is better to keep this additional layer as the fullscreen iframe is on top of this. Add |keep to your color then. E.g. #ffffff|keep. Shortcode attribute: hide_content_until_iframe_color=""', 'advanced-iframe'));
 
        printTrueFalse(true,$devOptions, __('Enable responsive iframe', 'advanced-iframe'), 'enable_responsive_iframe', __('You can enable that the width of iframe is responsive. This features adds a max-width:100% to the iframe. So the defined  width is the maximum width of the iframe. If the surrounding element gets smaller than this, the iframe is responsive and does shrink! When you enable this feature <strong>AND also the resize the iframe to the content height (direct or by external workaround)</strong>, the height does get responsive too! And this is the big difference to any other pure css solution which only work for iframes with a certain ratio e.g. for videos. Please read <a href="http://www.tinywebgallery.com/blog/responsive-iframes-with-advanced-iframe-pro" target="_blank">this post</a> for details and take a look <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/responsive-iframes" target="_blank">pro demo</a>. Please note that this feature does NOT work together with "Show only a part of an iframe" and "Hidden tabs". Shortcode attribute: enable_responsive_iframe="true" or enable_responsive_iframe="false" ', 'advanced-iframe'), 'false', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/responsive-iframes', true);
        printNumberInput(true,$devOptions, __('Set Iframe height by ratio', 'advanced-iframe'), 'iframe_height_ratio', __('This setting enables you to set the height of an iframe depending on the width of an iframe with a given ratio. If you have a static site you know the width of an iframe and you can set the height to a fix value. But if you e.g. have an iframe width of 100% and responsive layout you do not know the height. Using auto height does solve this most of the time but sometimes the content inside the iframe is fully dynamic too (like a video which does scale). If this is the case you can define a ratio here. e.g. 0.5 means that if you have a width of 1000 you have a height of 500. If the width changes to 800 the height changes to 400. Please use a . as decimal char. This setting does also work together with "Enable responsive iframe". Scalling the browser does change the height also if you enable the setting above. If you enable this setting the local resize settings are disabled! Shortcode attribute: iframe_height_ratio="" ', 'advanced-iframe'), 'text', '', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/responsive-videos', false);
   
        printTextInput(true,$devOptions, __('Reload interval', 'advanced-iframe'), 'reload_interval', __('You can reload the iframe in a given interval. Enter the intervall im ms or leave the field blank for no reload. Shortcode attribute: reload_interval=""', 'advanced-iframe'));    
 
    ?> 
     
     <tr <?php if ($isDemo) { echo 'class="ai-pro"'; } ?>>
        
        <th scope="row"><strong><?php _e('Browser detection', 'advanced-iframe'); ?></strong>
        </th><td>
          <?php _e('You can specify browser specific iframes. This is imporant especially for the "Show only part of the iframe" feature where browser differences of a few pixels can matter. But you can use this for other things as well because mobile, iphone, ipad can also be detected. Please read the <a id="browser-detection-link" href="#">browser detection</a> section for details. Shortcode: browser=""', 'advanced-iframe'); ?></td>
    </tr> 
    <?php } ?>   
    </table>
<?php if ($devOptions['single_save_button'] == 'false') { ?> 
     <p class="button-submit">
      <input class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>
    </p>
<?php } ?>     
</div>
