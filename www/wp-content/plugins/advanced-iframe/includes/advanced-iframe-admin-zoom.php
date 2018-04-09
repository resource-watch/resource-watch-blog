<?php 
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');

if ($evanto || $isDemo) {  
if ($devOptions['accordeon_menu'] == 'false') { ?>
<div class="ai-anchor" id="zo"></div>
<?php } ?>
<h1 id="h1-zo"><?php _e('Zoom', 'advanced-iframe') ?></h1>
<div>
    <div id="icon-options-general" class="icon_ai">
      <br>
    </div><h2>
      <?php _e('Zoom', 'advanced-iframe'); ?></h2>
    <p>
<?php _e('All major browsers do support the zoom of iframes. Depending on your setup you can use a static zoom factor or even automatic zoom which does zoom the content depending on the available space. Please check the examples how the different zoom settings do work. Please note that the zoom below does only zoom the iframe. When you use the "Show only a part of the iframe" the inner content is zoomed. For zoom options of the viewport please check the settings at "Show only a part of the iframe"', 'advanced-iframe');
echo '<table class="form-table">';
    printNumberInput(true,$devOptions, __('Zoom iframe', 'advanced-iframe'), 'iframe_zoom', __('You can zoom the content of the iframe with this setting. E.g. entering 0.5 does resize the iframe to 50%. At the iframe width and height you need to enter the FULL size of the iframe. So if you enter width = 1000, height = 500 and zoom = 0.5 than the result will be 500x250. The following browsers are supported: IE8-11, Firefox, Chrome, Safari, Opera. Older versions of IE are not supported. Please test all the browsers you want to support with your page because not all pages do look good in a zoomed mode! "Show only a part of an iframe" and "Resize iframe to content height" are supported. Shortcode attribute: iframe_zoom=""', 'advanced-iframe'),'text','','http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/zoom-iframe-content');
    printTrueFalse(true,$devOptions, __('i-20-Support zoom on IE8', 'advanced-iframe'), 'iframe_zoom_ie8', __('Zoom on IE8 does require the browser detection. And the browser detection does need a lot of memory during processing and is only available for php > 5.3.x. So by default the IE8 support of zoom is disabled. If you enable this and your system runs out of memory the plugin does automatically disable this support by creating a file called advanced-iframe-custom/browser-check-failed.txt. As long as this file does exist the IE8 support for zoom is disabled. Shortcode attribute: iframe_zoom_ie8="true" or iframe_zoom_ie8="false"', 'advanced-iframe'),'false');
    printTrueFalse(true,$devOptions, __('i-20-Zoom absolute fix', 'advanced-iframe'), 'use_zoom_absolute_fix', __('Sometimes the zoom measurements need an additional position:absolute to work correctly. Only set this to true if the zooms doens not work as expected. Shortcode attribute: use_zoom_absolute_fix="true" or use_zoom_absolute_fix="false"', 'advanced-iframe'),'false');
    printSameRemote($devOptions, __('Auto zoom iframe', 'advanced-iframe'), 'auto_zoom', __('This feature does automatically calculates the needed zoom factor to fit the iframe page into the parent page. Especially when you have a responsive website but the remote website is not responsive this is the only way that the page in the iframe does also zoom. Many smartphones and tablets to automatically zoom the parent page but not the iframe page. So there this feature can also be used. This feature works on the same domain and if you are able to use the external workaround and use auto height there (otherwise the width does not get transfered). Shortcode attribute: auto_zoom="same", auto_zoom="remote" or auto_zoom="false" ', 'advanced-iframe'),'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/auto-zoom-iframe-content',true);
    printTextInput(true,$devOptions, __('i-20-Auto zoom by ratio', 'advanced-iframe'), 'auto_zoom_by_ratio', __('This setting can be used on the SAME domain if the height of the page cannot be mesured but the ratio of the page is known. And if the width also cannot be measured automatically but is known because the iframe page has a fixed width, you can specify this width by adding with a pipe like ratio|width. E.g. 0.80|800. If you know the the ratio and the width, this setting does also work on REMOTE domains. You don\'t even need access to the remote domain! For remote domains also select SAME in the setting before as remote means that the height/width information is sent from the remote domain which is not the case here. Shortcode attribute: auto_zoom_by_ratio=""', 'advanced-iframe'),'text','http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/auto-zoom-iframe-content#e35');
echo '</table>';

?>
<?php if ($devOptions['single_save_button'] == 'false') { ?>      
      <p class="button-submit">
        <input class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>
      </p>
<?php } ?>
</div>
<?php } ?>