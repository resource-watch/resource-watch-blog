<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
?>
<div>
    <div id="icon-options-general" class="icon_ai">
      <br>
    </div>    <h2>
      <?php _e('Include content directly', 'advanced-iframe'); ?></h2>
    <p>
<?php _e('You can also include content directly with jQuery. The page is loaded and the part you specify below is included by Javascript into the page. The cool thing is that you can specify an id or a class which specify the content area that should be included. <strong>This feature does only work if the page is loaded from the SAME domain.</strong>. If you use the setting below no iframe is used anymore. So only include stuff that is for display only.<br/>Please note: Loading the external content is done after the page is fully loaded and takes some time. Therefore some extra settings below are possible to make the integration as invisible as possible. The included div has the id ai_temp_&gt;iframe_name&lt;. So if you need to overwrite some css you can put it in an extra file and add this in the section "Additional files" ', 'advanced-iframe');
_e('</p><p>"Include html" does write the given string directly and all other settings are not used!', 'advanced-iframe');



echo '<table class="form-table">';
     printTextInput(false,$devOptions, __('Include url', 'advanced-iframe'), 'include_url', __('Enter the full URL to your page you want to include. e.g. http://www.tinywebgallery.com. <strong>If you specify this then the page is included directly, the iframe settings above are not used and no iframe is included.</strong>. Shortcode attribute: include_url=""', 'advanced-iframe'));
     printTextInput(false,$devOptions, __('Include content', 'advanced-iframe'), 'include_content', __('You can specify an id or a class which specify the content area that should be included. For an id please use e.g. #id, for a class use .class. Shortcode attribute: include_content=""', 'advanced-iframe'));
     printNumberInput(false,$devOptions, __('Include height', 'advanced-iframe'), 'include_height', __('You can specify the height of the content that should be included. If you do this the space for the content is already reserved and this prevents that you maybe see when the page gets updated. You should specify the value in px. Shortcode attribute: include_height=""', 'advanced-iframe'));
     printNumberInput(false,$devOptions, __('Include fade', 'advanced-iframe'), 'include_fade', __('You can specify a fade in time that is used when the content is done loading. If you leave this setting entry the content is shown right away. If you specify a time in milliseconds then this content is faded in in the given time. This does sometimes looks nicer than if the content suddenly appears. Shortcode attribute: include_fade=""', 'advanced-iframe'));
     printTrueFalse(false,$devOptions, __('Hide page until include is loaded', 'advanced-iframe'), 'include_hide_page_until_loaded', __('If you like to hide the whole page until the extra content is loaded you should set this to \'Yes\'. You should test this setting and decide what looks best for you. Shortcode attribute: include_hide_page_until_loaded="true" or include_hide_page_until_loaded="false" ', 'advanced-iframe'));
     if ($evanto || $isDemo) {
         printTextInput(true,$devOptions, __('Include html', 'advanced-iframe'), 'include_html', __('If you enter something here the given string is written instead of an iframe. This is especially helpful if you e.g. use the browser detection and you want to display a simple text, image or link instead of the iframe in this case. If you set this include_url is also ignored! Only the following tags are allowed: br, em, p, div(id, class, style), a(href, target, class, style), img(src, class, style, width, height). Shortcode attribute: include_html=""', 'advanced-iframe'));
     }
    echo '</table>';

           ?>
<?php if ($devOptions['single_save_button'] == 'false') { ?>      
      <p class="button-submit">
        <input class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>
      </p>
<?php } ?>
</div>