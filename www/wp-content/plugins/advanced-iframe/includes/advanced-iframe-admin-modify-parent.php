<?php 
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');

if ($devOptions['accordeon_menu'] == 'false') { ?>
<div class="ai-anchor" id="mp">
</div>
<?php } ?>
<h1 id="h1-mp">
  <?php _e('Modify the parent page', 'advanced-iframe'); ?></h1>
<div>    
  <div id="icon-options-general" class="icon_ai">      
    <br>    
  </div>     <h2>
    <?php _e('Modify the parent page', 'advanced-iframe'); ?></h2>    
  <p>      
    <?php _e('With the following options you can modify your template on the fly to give the iframe more space! At most templates you would have to create a page template with a special css and this is quite complicated. By using the options below your template is modified on the fly by jQuery. Please look at the screenshots to make these options more clear. The options are very useful for templates that have a top navigation because otherwise your menu is gone! If you still want to do this you should add a back link to the page. The examples below are for Twenty Ten, iNove and the default Wordpress theme. Please also look at "Add css styles to parent" if you have the pro version because there the css is directly written to the page which should work for many setups!', 'advanced-iframe'); ?>    
  </p>    
  <table class="form-table">
<?php
        printTextInput(false,$devOptions, __('Hide elements', 'advanced-iframe'), 'hide_elements', __('This setting allows you to hide elements when the iframe is shown. This can be used to hide the sidebar or the heading. Usage: If you want to hide a div you have to enter a hash (#) followed by the id e.g. #sidebar. If you want to hide a heading which is a &lt;h2&gt; you have to enter h2. You can define several elements separated by , e.g. #sidebar,h2. This gives you a lot more space to show the content of the iframe. To get the id of the sidebar go to Appearance -> Editor -> Click on \'Sidebar\' on the right side. Then look for the first \'div\' you find. The id of this div is the one you need. For some common templates the id is e.g. #menu, #sidebar, or #primary. For Twenty Ten and iNove you can remove the sidebar directly: Page attributes -> Template -> no sidebar. Wordpress default: \'#sidebar\'. I recommend using firebug (see below) to find the elements and the ids. You can use any valid <a class="jquery-help-link" href="#">jQuery selector pattern</a> here! Shortcode attribute: hide_elements=""', 'advanced-iframe'));
echo '</table><p>';
       _e('With the next 2 options you can modify the css of your parent page. The first option defines the id/class/element you want to modify and at the 2nd option you define the styles you want to change.', 'advanced-iframe');
echo '</p><table class="form-table">';
        printTextInput(false,$devOptions, __('Content id', 'advanced-iframe'), 'content_id', __('Some templates do not use the full width for their content and even most \'One column, no sidebar Page Template\' templates only remove the sidebar but do not change the content width. Set the e.g. id of the div starting with a hash (#) that defines the content.  You can use any valid <a class="jquery-help-link" href="#">jQuery selector pattern</a> here! In the field below you then define the style you want to overwrite. For Twenty Ten and WordPress Default the id is #content, for iNove it is #main. You can also define more than one element. Please separate them with | and provide the styles below. Please read the note below how to find this id for other templates. #content|h2 means that you want to set a new style for the div content and the heading h2 below. Shortcode attribute: content_id=""', 'advanced-iframe'));
        printTextInput(false,$devOptions, __('Content styles', 'advanced-iframe'), 'content_styles', __('Define the styles that have to be overwritten to enable the full width. Most of the time you have to modify some of the following attributes: width, margin-left, margin-right, padding-left. Please use ; as separator between styles. If you have defined more than one element above (Content id) please separate the different style sets with |. The default values are: Wordpress default: \'width:450px;padding-left:45px;\'. Twenty Ten: \'margin-left:20px;margin-right:240px\'. iNove: \'width:605px\'. Read the note below how to find these styles for other templates. If you have defined #content|h2 at the Content id you can e.g. set \'width:650px;padding-left:25px;|padding-left:15px;\'. Shortcode attribute: content_styles=""', 'advanced-iframe'));
       
    if ($evanto || $isDemo) { 
         printTextInput(true,$devOptions, __('Add css styles to parent', 'advanced-iframe'), 'parent_content_css', __('This setting does add the css you enter here directly where the plugin is written to the page. The difference to the settings before is, that the modification is not done by jQuery but the css is directly written to the parent. The advantage is that also !important can be used to overwrite such styles and that the modifications is not done after the whole page is loaded. You can also use this setting to configure "Hide elements" directly. The disadvantage is that the styles added where the plugin is written and do not overwrite the stlye rendered later and that writting valid css is a little bit more complicated. Enter the styles without &lt;style&gt;. The value is sanitized at the output! Therefore not all styles do work! e.g. body &gt; p cannot be used. Use external files if you need this. Shortcode attribute: parent_content_css=""', 'iframe_advanced-iframe'),'text','', false);
        
       
          printTrueFalse(true,$devOptions, __('Add css class to parent elements', 'advanced-iframe'), 'add_css_class_parent', __('Sometimes it is not possible to modify existing css classes of the parent because they are also used somewhere else or there is no unique selector for this element. Setting this attribute to true causes that a new class is added at each parent of the iframe up to the body! If the element has an id the class is named "ai-class-(id)". Otherwise "ai-class-(number)" is added. Then it is easy to identify all parent elements of the iframe and modify them. If you have several iframes on one page the classes could not be unique anymore. You need to set "Include ai.js in the footer" to false if you want to use this! Shortcode attribute: add_css_class_parent="true" or add_css_class_parent="false" ', 'advanced-iframe'));
        }
     echo '</table>';
     if ($devOptions['single_save_button'] == 'false') { ?>           
    <p class="button-submit">        
      <input id="so" class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>      
    </p>  
    <?php } ?>     
</div>

<?php 
if ($evanto || $isDemo) {               
if ($devOptions['accordeon_menu'] == 'false') { ?> 
<div class="ai-anchor" id="ol">
</div>
<?php } ?>
<h1 id="h1-ol">
  <?php _e('Open iframe in layer', 'advanced-iframe'); ?></h1>
<div>    
  <div id="icon-options-general" class="icon_ai">      
    <br>    
  </div>     <h2>
    <?php _e('Open iframe in layer', 'advanced-iframe'); ?></h2>    
  <p>      
    <?php _e('With the following options you can modify your template on the fly to give the iframe more space! At most templates you would have to create a page template with a special css and this is quite complicated. By using the options below your template is modified on the fly by jQuery. Please look at the screenshots to make these options more clear. The options are very useful for templates that have a top navigation because otherwise your menu is gone! If you still want to do this you should add a back link to the page. The examples below are for Twenty Ten, iNove and the default Wordpress theme.', 'advanced-iframe'); ?>    
  </p>    
  <table class="form-table">         
<?php  
           
           printTextInput(true,$devOptions, __('Change parent links target', 'advanced-iframe'), 'change_parent_links_target', __('Change links of the parent page to open the url inside the iframe. This option does add the attribute target="your id" to the links you define. You can use any valid <a class="jquery-help-link" href="#">jQuery selector pattern</a> here! So if your link e.g. has an id="link1" you have to use "a#link1". If you want to change all links e.g. in the div with the id="menu-div" you have to use "#menu-div a". You can also define more than one element. Please separate them with ,. Shortcode attribute: change_parent_links_target=""', 'advanced-iframe'));
           printTrueExternalFalse($devOptions, __('i-20-Show iframe as layer', 'advanced-iframe'), 'show_iframe_as_layer', __('If you enable this, the iframe is initally hidden and if you click on a link defined at "Change parent links target" the iframe is shown as a simple lighbox as overlay on the page. So if you use this for external links the user does not leave your page! "External" does simply open all links that are not on the same domain in a layer. The setting at "Change parent links target" is ignored than. This setting does overwrite some iframe settings like height, width and border! Show part of iframe, lazy load, hide part of iframe and iframe loader are disabled as they do not work with this feature. Shortcode attribute: show_iframe_as_layer="true", show_iframe_as_layer="external", show_iframe_as_layer="false" ', 'advanced-iframe'),'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/show-the-iframe-as-layer#e34');
           printTrueOriginalFalse($devOptions, __('i-40-Show layer 100% or original', 'advanced-iframe'), 'show_iframe_as_layer_full', __('Show the layer with 100% ("Yes") or 96% ("No"). Original does mean that the size you set for the iframe is set as max width/height of the layer and 96% if the parent is smaller than your height/width Shortcode attribute: show_iframe_as_layer_full="true", show_iframe_as_layer_full="false", show_iframe_as_layer_full="original" ', 'advanced-iframe'));               
           printTextInput(true,$devOptions, __('i-40-Layer file id', 'advanced-iframe'), 'show_iframe_as_layer_header_file', __('You can add an additional header/footer with custom html above or below the iframe in the layer. Header/Footer files need to be in the folder plugins/advanced-iframe-custom with the following naming convention: layer_{id}.html. The id has to be saved in this text field. Below you see the existing header/footer files and also you can create/edit/delete them. The content of this file is included into a div at the given position. You need to provide the height of your additional content in the next setting. Shortcodes in your custom file are supported! The placeholder {id} is replaced by the id of your iframe. This can be used to reuse a layer file where e.g.  different images depending on the iframe should be shown. The id can only contain alphanumeric characters, - and _ . The placeholder {src} is replaced by the src of your iframe. This can be used to create a link like: "Go to this page".  Shortcode attribute: show_iframe_as_layer_header_file=""', 'advanced-iframe'),'text','http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/show-the-iframe-as-layer#e37');
           printNumberInput(true,$devOptions, __('i-40-Layer header/footer height', 'advanced-iframe'), 'show_iframe_as_layer_header_height', __('The height of the additional layer. The height is needed to calculate the height of the iframe properly. Shortcode attribute: show_iframe_as_layer_header_height=""', 'advanced-iframe'));
           printTopBottom($devOptions, __('i-40-Layer header postion', 'advanced-iframe'), 'show_iframe_as_layer_header_position', __('Show the additional area above or below the iframe. Shortcode attribute: show_iframe_as_layer_header_position="top" or show_iframe_as_layer_header_position="bottom" ', 'advanced-iframe'),'top','http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/show-the-iframe-as-layer#e38');
           printTrueFalse(true,$devOptions, __('i-40-Keep the content after closing', 'advanced-iframe'), 'show_iframe_as_layer_keep_content', __('To improve performance the content of an iframe is not loaded again if the same opening link is clicked again. It is only hidden and shown then. But sometimes it makes sense to unload the content if e.g. sound should be stopped or it is mandatory that the iframe shows the first page again. Shortcode attribute: show_iframe_as_layer_keep_content="true" or show_iframe_as_layer_keep_content="false" ', 'advanced-iframe'));
        ?>     
  </table>
  <?php if ($evanto || $isDemo) { ?>     
  <div class="hide-print">    <h4>
      <?php _e('Existing additional layer files', 'advanced-iframe') ?></h4>    
    <p>
      <?php _e('The following additional layer files in the folder "advanced-iframe-custom" currently exist. Please note that you can view/edit this files with the plugin editor of Wordpress by clicking on the "Edit/View" link.', 'advanced-iframe') ?>      
    </p>
<?php
  $config_files = array();
  foreach (glob(dirname(__FILE__) .'/../../advanced-iframe-custom/layer_*.html') as $filename) {
    $base = basename($filename);
    $base_url1 = site_url() . '/wp-admin/plugin-editor.php?file=advanced-iframe-custom%2F';
    $base_url2 = ''; // '&plugin=advanced-iframe%2Fadvanced-iframe.php';
    $config_files[] = $base ; 
  }
echo "<hr height=1>";
if (count($config_files) == 0) {
    echo "<ul><li>";
    _e('No custom additional header files found.', 'advanced-iframe');
    echo "</li></ul>";
} else {
  foreach ($config_files as $file) {
    echo '<div class="config-file-block"><div class="ai-external-config-label"><span class="config-list">' .$file .  '</span> &nbsp; <a href="'.$base_url1 . $file . $base_url2 .'">';
    _e('Edit/View', 'advanced-iframe');
    echo '</a>';    
    $rid =  substr(basename($file,'.html'),6);
    echo ' &nbsp; <a class="confirmation post" href="options-general.php?page=advanced-iframe.php&remove-custom-header-id='.$rid.'">';
    _e('Remove', 'advanced-iframe');
    echo '</a></div>';
    // echo '<div class="ai-external-config">' . plugins_url() . '/advanced-iframe-custom/'.$file.'</div>';
    echo '<br /></div>';
  }
}
echo "<hr height=1>";
    ?>       
    <p>
      <?php _e('Create a custom layer file. Only specify the id. All files are named "layer_{id}.html":', 'advanced-iframe') ?><br />
      <input name="ai_custom_header_id" id="ai_custom_header_id" type="text" size="20" maxlength="20" />        
      <input id="chf" class="button-primary" type="submit" name="create-custom-header-id" value="<?php _e('Create custom layer file', 'advanced-iframe') ?>"/>    
    </p>    
  </div>
  <?php } ?>       
  <?php if ($devOptions['single_save_button'] == 'false') { ?>          
  <p class="button-submit">      
    <input id="so" class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>    
  </p>
  <?php } ?>     
</div>
<?php } ?> 