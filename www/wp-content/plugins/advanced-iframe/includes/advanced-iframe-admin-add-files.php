<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
?>
<br />
<div>
    <div id="icon-options-general" class="icon_ai">
      <br>
    </div>    <h2>
      <?php _e('Add additional files', 'advanced-iframe') ?></h2>
     <p>
      <?php _e('All settings above are designed for smaller changes of the parent or the iframe. If you want to make bigger changes and you are able to store this in a file including a whole file is the better solution. Below you can add additional Javascript or css files to the different pages.', 'advanced-iframe'); ?>
    </p> 
      <p>
      <?php _e('<strong>Please note:</strong> The files are edited/viewed by the default Worpdress plugin editor! It is o.k. that "inactive" is shown in the editor as the advanced-iframe-custom folder is not a real plugin folder. If the editor does not work because of file permissions please edit the files directly on your server.', 'advanced-iframe'); ?>
    </p> 
    
    
     <div class="hide-print">
    <h4><?php _e('Existing additional files', 'advanced-iframe') ?></h4>
    <p><?php _e('The following additional files in the folder "advanced-iframe-custom" currently exist. Please note that you can view/edit this files with the plugin editor of Wordpress by clicking on the "Edit/View" link. Hover over the file you want to include and click 3 times fast to select it.', 'advanced-iframe') ?> 
    </p>
<?php
  $config_files = array();
  foreach (glob(dirname(__FILE__) .'/../../advanced-iframe-custom/custom_*.*') as $filename) {
    $base = basename($filename);
    $base_url1 = site_url() . '/wp-admin/plugin-editor.php?file=advanced-iframe-custom%2F';
    $base_url2 = ''; //  '&plugin=advanced-iframe%2Fadvanced-iframe.php';
    $config_files[] = $base ; 
  }
echo "<hr height=1>";
if (count($config_files) == 0) {
    echo "<ul><li>";
    _e('No custom additional files found.', 'advanced-iframe');
    echo "</li></ul>";
} else {
  foreach ($config_files as $file) {
    echo '<div class="config-file-block"><div class="ai-external-config-label"><span class="config-list">' .$file .  '</span> &nbsp; <a href="'.$base_url1 . $file . $base_url2 .'">';
    _e('Edit/View', 'advanced-iframe');
    echo '</a>';    
    $rid =  substr(basename($file),7);
    echo ' &nbsp; <a class="confirmation post" href="options-general.php?page=advanced-iframe.php&remove-custom-id='.$rid.'">';
    _e('Remove', 'advanced-iframe');
    echo '</a></div>';
    echo '<div class="ai-external-config">' . plugins_url() . '/advanced-iframe-custom/'.$file.'</div>';
    echo '<br /></div>';
  }
}
echo "<hr height=1>";
?>  
    <p><?php _e('Create a custom file. Only files with the extensions css or js are allowed. All files are prefixed with "custom_":', 'advanced-iframe') ?><br />
      <input name="ai_custom_id" id="ai_custom_id" type="text" size="20" maxlength="20" /> 
      <input id="ccf" class="button-primary" type="submit" name="create-custom-id" value="<?php _e('Create custom file', 'advanced-iframe') ?>"/>
    </p>
    </div>
     
    <h3><?php _e('Parent', 'advanced-iframe') ?></h3>
    <p>
      <?php _e('For some features in iframes additional css or js files are needed in the parent(!) page. E.g. for the newest version of lytebox this is needed. Each of the files do get a version number which is randomly changed each time you save the settings. So if you change the css or the js file you should save the settings to make sure your users to get the new version right away and not a chached one. If you need to add css or Javascipt to the iframe please check the settings of the external workaround.', 'advanced-iframe'); ?>
    </p>
    <table class="form-table">
<?php
        printTextInput(false,$devOptions, __('Additional css', 'advanced-iframe'), 'additional_css', __('If you want to include an additional css into the parent page please specify the path to this file here. The css file will be added into the header of the page. You can specify a full or relative url. Make sure you take "<a href="https://designshack.net/articles/css/what-the-heck-is-css-specificity/" target="blank">CSS specificity"</a> into account if you want to overwrite styles! If you specify a relative one /style.css means that the style.css is located in the main directory of Wordpress. Start relative urls with /. Please note: Before Wordpress 3.3 the shortcode attribute cannot be used. You can only set it here. Shortcode attribute: additional_css=""', 'advanced-iframe'));
        printTextInput(false,$devOptions, __('Additional js', 'advanced-iframe'), 'additional_js', __('If you want to include an additional Javascript into the parent page please specify the path to this file here. The Javascript will be added after the iframe or if you use Wordpress >= 3.3 in the footer section. You can specify a full or relative url. If you specify a relative one /javascript.js means that the javascript.js is located in the main directory of Wordpress. Start relative urls with /. Please note: Before Wordpress 3.3 the shortcode attribute cannot be used. You can only set it here. Shortcode attribute: additional_js=""', 'advanced-iframe'));
                          ?>
    </table>
    
    <?php if ($evanto || $isDemo) { ?> 
    <h3><?php _e('Iframe', 'advanced-iframe') ?></h3>
    <p>
     <?php _e('You can also include a css file directly into the iframe page. This setting is also saved in the external workaround file. In the external workaround file the settings below are written at the place where the ai_external.js is included!', 'advanced-iframe'); ?>
    </p>
        <table class="form-table">
<?php
        printTextInput(true,$devOptions, __('Additional css in iframe', 'advanced-iframe'), 'additional_css_file_iframe', __('You can also include a css file directly into the iframe page. The css file will be added at the bottom of the body to overwrite also all inline styles. The styles are added after the page is loaded. Make sure you take "<a href="https://designshack.net/articles/css/what-the-heck-is-css-specificity/" target="blank">CSS specificity"</a> into account if you want to overwrite styles! You can specify a full or relative url. If you specify a relative one /style.css means that the style.css is located in the main directory of the iframe page. In the external workaround the file is added after the ai_external.js. Shortcode attribute: additional_css_file_iframe=""', 'advanced-iframe'),'text','', $evanto);
        printTextInput(true,$devOptions, __('Additional Javascript in iframe', 'advanced-iframe'), 'additional_js_file_iframe', __('You can also include a js file directly into the iframe page. The js file will be added at the bottom of the body. You can specify a full or relative url. If you specify a relative one /javascript.js means that the javascript.js is located in the main directory of the iframe page. In the external workaround the file is added after the ai_external.js. Shortcode attribute: additional_js_file_iframe=""', 'advanced-iframe'),'text','', $evanto);
?>
    </table>
<?php } ?>    
<?php if ($devOptions['single_save_button'] == 'false') { ?>
    <p class="button-submit">
      <input id="ic" class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>
    </p>
<?php } ?> 
</div>