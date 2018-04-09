
<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
 
if ($evanto || $isDemo) {  
if ($devOptions['accordeon_menu'] == 'false') { ?>
<div class="ai-anchor" id="la"></div>
<?php } ?>
<h1 id="h1-la"><?php _e('Lazy load', 'advanced-iframe') ?></h1>
<div>
    <div id="icon-options-general" class="icon_ai">
      <br>
    </div><h2>
      <?php _e('Lazy load', 'advanced-iframe'); ?></h2>
    <p>
<?php _e('<p>Iframes do often slow down the loading of pages because more content needs to be loaded at the same time.</p>
<p>The lazy load feature can improve this by loading the iframe only when needed and can be configured in several ways:</p>
<ol>
<li>The iframe is loaded when it is visible</li>
<li>The iframe is loaded after the parent page is loaded</li>
<li>The iframe is loaded manually</li>
</ol>', 'advanced-iframe');
echo '<table class="form-table">';
// lazy load
        printTrueFalse(true,$devOptions, __('Enable lazy load', 'advanced-iframe'), 'enable_lazy_load', __('You can enable that iframes are lazy loaded. If you enable this, the iframe is loaded either after the ready event of the parent or if the iframe gets visible. Please check the "Enable lazy load threshold" setting below how to configure this. Shortcode attribute: enable_lazy_load="true" or enable_lazy_load="false" ', 'advanced-iframe'), 'false', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/lazy-loading', false);
        printNumberInput(true,$devOptions, __('i-20-Lazy load threshold', 'advanced-iframe'), 'enable_lazy_load_threshold', __('This setting sets the pixels to load earlier. Setting the value e.g. to 200 causes iframe to load 200 pixels before it appears in the viewport. It should be greater or equal zero. The default is set to 3000 which normally is a lazy load after the parent finished loading. If you set this value higher then the distance of the iframe to the top, the iframe is lazy loaded after the parent document ready event is fired. If you leave this field empty 0 is used. Shortcode attribute: enable_lazy_load_threshold="" ', 'advanced-iframe'), 'text', '3000');
        printNumberInput(true,$devOptions, __('i-20-Lazy load fadein time', 'advanced-iframe'), 'enable_lazy_load_fadetime', __('This setting enables you to fade in the iframe after it is lazy loaded. Enter the time in milliseconds.  Depending on the content of the iframe this looks good or not. Please test if you like the behaviour. If you leave this field empty 0 is used. Shortcode attribute: enable_lazy_load_fadetime="" ', 'advanced-iframe'), 'text', '0');
        printTrueFalse(true,$devOptions, __('i-20-Reserve iframe space', 'advanced-iframe'), 'enable_lazy_load_reserve_space', __('By default the initial height of the iframe is reserved in the layout to avoid jumping when the iframe is loaded. "No" does not reserve the space anymore. Shortcode attribute: enable_lazy_load_reserve_space="true" or enable_lazy_load_reserve_space="false" ', 'advanced-iframe'), 'true', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/lazy-loading', false);
        printScollAutoManuall($devOptions, __('i-20-How trigger lazy loading', 'advanced-iframe'), 'enable_lazy_load_manual', __('Normally (Default (Scroll)) the iframes are loaded lazy after the settings you specify above. The option "Auto" does check every 50 ms if the iframe is visible in the viewport and should be loaded. This is especially useful for iframes that are hidden when the page is loaded. So this can be used for hidden tabs because when this is shown no internal Javascipt event like scrolling does exist! If you use auto all iframes on the same page do poll because this is a global setting of the plugin. But you also can trigger the loading manually. This can also be used to lazy load tabs or when you want to load the iframe by yourself. For each iframe a Javascript function to show the iframe is created: aiLoadIframe_"your id"(); e.g. aiLoadIframe_advanced_iframe(); Simply call it when you want. Also see the next option! If you want to avoid polling for tabs use the manual setting. See the lazy load demo for an example. Shortcode attribute: enable_lazy_load_manual="false"  enable_lazy_load_manual="auto" or enable_lazy_load_manual="true" ', 'advanced-iframe'));
        printTextInput(true,$devOptions, __('i-20-Element that triggers the lazy load', 'advanced-iframe'), 'enable_lazy_load_manual_element', __('If you enable "How trigger lazy loading -> manually" you have a Javascript function that triggers the lazy load. With this setting you can add an click event with this Javascript function to the element you define here. So if you e.g. have a tab with the id="tab1" you simply enter #tab1 here. Any jQuery selector does work here. So you can even attach this to several elements. Shortcode attribute: enable_lazy_load_manual_element=""', 'advanced-iframe'));
echo '</table>';

?>
<?php if ($devOptions['single_save_button'] == 'false') { ?>      
      <p class="button-submit">
        <input class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>
      </p>
<?php } ?>
</div>
<?php } ?>