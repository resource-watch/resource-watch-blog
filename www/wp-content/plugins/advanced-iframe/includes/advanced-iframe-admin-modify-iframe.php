<?php 
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');

if ($devOptions['accordeon_menu'] == 'false') { ?>
<div class="ai-anchor" id="so"></div>
<?php } ?>
<h1 id="h1-so"><?php _e('Show only a part of the iframe', 'advanced-iframe'); ?></h1>
<div>
    <div id="icon-options-general" class="icon_ai">
      <br>
    </div><h2>
      <?php _e('Show only a part of the iframe', 'advanced-iframe'); ?></h2>
<?php if ($evanto || $isDemo) { ?>
    <p>

<?php _e('You can only show a part of the iframe. This solution DOES WORK across domains without any hacks! This is a solution that works only with css by placing a window over the iframe which does a clipping. All areas of the iframe that are not inside the window cannot be seen. Please specify the upper left corner coordinates x and y and the height and width that should be shown. Specify a fixed height and width in the iframe options at the top for optimal results! Simply select the area you want to show with the graphical area selector! You can even zoom the selected area that it fits properly e.g. on a mobile phone. Please go to the <a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo">pro demo</a> for some working examples. Please also check the additional 5 options. These are the advanced features to handle changes in the iframe.<p>Also media queries are supported! This enables you to show different areas depending on the browser width. Please see <a  target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/show-only-a-part-of-the-iframe#e55">example 55</a> for a working demo.</p>', 'advanced-iframe');

echo '<p><input id="s" class="button-primary" type="button" name="update_iframe-loader" onclick="openSelectorWindow(\''. plugins_url() .'/advanced-iframe/includes/advanced-iframe-area-selector.html\');" value="';
 _e('Open the area selector', 'advanced-iframe');
echo '" /><a href="#" id="ai-selector-help-link">Show me an image how the settings are used.</a></p>';

echo '<div id="ai-selector-help"><img src="' . plugins_url() . '/advanced-iframe/img/help-area-selector.gif"></div>';


echo '<table class="form-table">';
     printTrueFalse(true,$devOptions, __('Show only part of the iframe', 'advanced-iframe'), 'show_part_of_iframe', __('Show only part of the iframe. You have to enable this to use all the options below. Please read the text above. Shortcode attribute: show_part_of_iframe="true" or show_part_of_iframe="false" ', 'advanced-iframe'), 'false', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/show-only-a-part-of-the-iframe', false);
     printNumberInput(true,$devOptions, __('i-20-Upper left corner x', 'advanced-iframe'), 'show_part_of_iframe_x', __('Specifies the x coordinate of the upper left corner of the view window. Enter the x-offset from the left border of your external iframe page you want to show. Shortcode attribute: show_part_of_iframe_x="". <a href="#" class="ai-selector-help-link-move">Show me an image how this settings is used.</a>', 'advanced-iframe'));
     printNumberInput(true,$devOptions, __('i-20-Upper left corner y (top distance)', 'advanced-iframe'), 'show_part_of_iframe_y', __('Specifies the y coordinate of the upper left corner.  Enter the y-offset from the top border of your external iframe page you want to show. Shortcode attribute: show_part_of_iframe_y="". <a href="#" class="ai-selector-help-link-move">Show me an image how this settings is used.</a>', 'advanced-iframe'));
     printNumberInput(true,$devOptions, __('i-20-Width of the visible content', 'advanced-iframe'), 'show_part_of_iframe_width', __('Specifies the width of the content in pixel that should be shown. Shortcode attribute: show_part_of_iframe_width="". <a href="#" class="ai-selector-help-link-move">Show me an image how this settings is used.</a>', 'advanced-iframe'));
     printNumberInput(true,$devOptions, __('i-20-Height of the visible content', 'advanced-iframe'), 'show_part_of_iframe_height', __('Specifies the height of the content in pixel that should be shown. Shortcode attribute: show_part_of_iframe_height="". <a href="#" class="ai-selector-help-link-move">Show me an image how this settings is used.</a>', 'advanced-iframe'));
     printTrueFalse(true,$devOptions, __('i-20-Enable horizontal scrollbar', 'advanced-iframe'), 'show_part_of_iframe_allow_scrollbar_horizontal', __('By default you specify a fixed area you want to show from the external page. Settings this to "true" will show a horizontal scrollbar if needed. Shortcode attribute: show_part_of_iframe_allow_scrollbar_horizontal="true" or show_part_of_iframe_allow_scrollbar_horizontal="false". <a href="#" class="ai-selector-help-link-move">Show me an image how this settings is used.</a>', 'advanced-iframe'), 'false');
     printTrueFalse(true,$devOptions, __('i-20-Enable vertical scrollbar', 'advanced-iframe'), 'show_part_of_iframe_allow_scrollbar_vertical', __('By default you specify a fixed area you want to show from the external page. Settings this to "true" will show a vertical scrollbar if needed. Shortcode attribute: show_part_of_iframe_allow_scrollbar_vertical="true" or show_part_of_iframe_allow_scrollbar_vertical="false". <a href="#" class="ai-selector-help-link-move">Show me an image how this settings is used.</a>', 'advanced-iframe'), 'false');
     printTextInput(true,$devOptions, __('i-20-Viewport style', 'advanced-iframe'), 'show_part_of_iframe_style', __('Show part of an iframe does create an additional div which is the element you can style here. If you e.g. want to add a border you can use "border: 2px solid #ff0000;". Using the style, border or class in the default settings do not work as they are all related to the iframe directly! Shortcode attribute:  show_part_of_iframe_style=""', 'advanced-iframe'));
     
     printTrueFalseFull($devOptions, __('i-20-Enable auto zoom', 'advanced-iframe'), 'show_part_of_iframe_zoom', __('This zoom setting enables you to zoom the viewport automatically to the available space. The difference to the normal zoom options is that the whole selected area is zoomed and not the content of the iframe only. This zoom works like the "Auto zoom by ratio" but you don\'t have to specify a ratio as the height and the width is already known from the settings above. This feature does check the size of the div around the viewport and calculates the needed zoom factor and offsets. Therefore you have to select a fixed viewport (e.g. width:500) because otherwise the calculated zoom would always be 1. If you select "Yes" the zoom does only shrink the viewport which is normally the best choice because looks good on desktop and is shown smaller on mobile devices. If you select "Full" the viewport is also enlaged. Also the feature "Hide/cover parts of the iframe" is supported. So if you place e.g. a colored div over a certain area to hide it it is also zoomed Shortcode attribute: show_part_of_iframe_zoom="true", show_part_of_iframe_zoom="false" or show_part_of_iframe_zoom="full"', 'advanced-iframe'), 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/show-only-a-part-of-the-iframe/show-only-a-part-of-an-iframe-zoom');
     echo '</table>';


echo '<p>';
       _e('With the following 5 options you can do something when the page in the iframe does change. The parent page does only know the url of the iframe that is loaded initially. This is a browser restriction when the pages are not on the same domain. The parent only can find out when the page inside does change. But it does not know to which url. So the options below rely on a counting of the onload event. But for certain solutions (e.g. show only the login part of a page and then open the result page as parent) this will work.', 'advanced-iframe');
echo '</p><table class="form-table">';

    printTextInput(true,$devOptions, __('i-20-Change the viewport when iframe changes to the next step', 'advanced-iframe'), 'show_part_of_iframe_next_viewports', __('You can define different viewports when the page inside the iframe does change and a onload event is fired. Each time this event is fired a different viewport is shown. A viewport is defined the following way: left,top,width,height e.g. 50,100,500,600. You can define several viewports (if you e.g. have a straigt  workflow) by separating the viewports by ; e.g. 50,100,500,600;10,40,200,400. Each viewport has its own class: ai-viewport-X. X is the number of the viewport starting with 0! You can e.g. enable scroll for specific viewports with this setting. Shortcode attribute:  show_part_of_iframe_next_viewports=""', 'advanced-iframe'));
    printTrueFalse(true,$devOptions, __('i-20-Restart the viewports from the beginning after the last step.', 'advanced-iframe'), 'show_part_of_iframe_next_viewports_loop', __('If you define different viewports it could make sense always to use them in a loop. E.g. if you have an image gallery where you have an overview with viewport 1 and a detail page with viewport 2. And you can only can come from the overview to the detail page and back. Shortcode attribute: show_part_of_iframe_next_viewports_loop="true" or show_part_of_iframe_next_viewports_loop="false" ', 'advanced-iframe'));
    printTextInput(true,$devOptions, __('i-20-Open iFrame in new window after the last step', 'advanced-iframe'), 'show_part_of_iframe_new_window', __('You can define if the iframe is opened in a new tab/window or as full window. the options you can use are "_top" = as full window, "_blank" = new tab/window or you leave it blank to stay in the iframe. Because of the browser restriction not the current url of the iframe can be loaded. It is either the initial one or the one you specify in the next setting. Shortcode attribute: show_part_of_iframe_new_window="", show_part_of_iframe_new_window="_top" or show_part_of_iframe_new_window="_blank" ', 'advanced-iframe'));
    printTextInput(true,$devOptions, __('i-20-Url that is opened after the last step', 'advanced-iframe'), 'show_part_of_iframe_new_url', __('You can define the url that is loaded after the last step. This enables you to jump to a certain page after your workflow. This is useful with the above. Shortcode attribute: show_part_of_iframe_new_url="" ', 'advanced-iframe'));
    printTrueFalse(true,$devOptions, __('i-20-Hide the iframe after the last step', 'advanced-iframe'), 'show_part_of_iframe_next_viewports_hide', __('Hides the iframe after the last step completely. Shortcode attribute: show_part_of_iframe_next_viewports_hide="true" or show_part_of_iframe_next_viewports_hide="false" ', 'advanced-iframe'));

echo '</table>'; ?>




<?php if ($devOptions['single_save_button'] == 'false') { ?>
    <p class="button-submit">
        <input id="onload-button" class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>
    </p>
    <?php } ?>
<?php } else { ?>
    <p>
     <?php _e('This feature is only available in the Pro version where you have the option to show only a part of the iframe even when the content you want to include is on a different domain. Please note that there is still no way to modify anything on the remote site.', 'advanced-iframe') ?>
    </p>
    <?php } ?>
</div>  

<?php if ($evanto || $isDemo) { ?>  

<?php if ($devOptions['accordeon_menu'] == 'false') { ?>
<div class="ai-anchor" id="hi"></div>
<?php } ?>
<h1 id="h1-hi"><?php _e('Hide/cover parts of the iframe.', 'advanced-iframe'); ?></h1>
<div>
    <div id="icon-options-general" class="icon_ai">
      <br>
    </div><h2 id="mi-hi">
      <?php _e('Hide/cover parts of the iframe.', 'advanced-iframe'); ?></h2>
    
    <p>
       <?php _e('Please note: This is an advanced setting! You need to know basic html/css to use all possibilities of this feature! You can define an area which will be hidden by a rectangle you define. This can e.g. be used to hide a logo.', 'advanced-iframe'); ?>
    </p>
    <?php 
    echo '<p><input id="s" class="button-primary" type="button" name="update_iframe-loader" onclick="openSelectorWindow(\''. plugins_url() .'/advanced-iframe/includes/advanced-iframe-area-selector.html?hide_feature=true\');" value="';
    _e('Open the area selector in the hide parts mode', 'advanced-iframe');
    echo '" /></p>';
    ?>
    
    <table class="form-table">
<?php
      if ($evanto || $isDemo) {
          printTextInput(true,$devOptions, __('Hide/cover parts of the iframe. Make an iframe read only', 'advanced-iframe'), 'hide_part_of_iframe', __('A rectangle is defined the following way: left,top,width,height,color,z-index e.g. 10,20,200,50,#ffffff,10. This defines a rectangle in white with the z-index of 10. z-index means the layer the rectangle is placed. If you don\'t see your rectangle please use a higher z-index. You can also define a background image here! use e.g. 10,20,200,50,#ffffff;background-image:url(your-logo.gif);background-repeat:no-repeat;,10 for a white rectangle with the given background image. Use the area selector to get the coordinates very easy. You can specify several rectangles by separating them by |. Please see the <a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/hide-a-part-of-the-iframe#e8">pro demo</a> for a cool example where a logo is exchanged.<p class="description">You can also create read only iframes with this feature. Use hide_part_of_iframe="0,0,100%,100%,transparent,10". For a working example please see example 21 of the pro demo.</p><p class="description">It is also possible to define an optional link and an optional target for this area. Parameter 7 is the url and parameter 8 the target. So a working example would be: hide_part_of_iframe="0,0,100%,100%,transparent,10,http://www.tinywebgallery.com,_blank".</p><p class="description">The divs can also be right and bottom aligned. You need to specify the prefix r for right instead of left and b for bottom instead of top. An example would look like this: r10,b20,200,50,#ffffff,10.</p><p class="description">Also media queries are supported! This enables you to hides areas depending on the browser width. Please see <a  target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/hide-a-part-of-the-iframe#e50">example 50</a> for a working demo.</p><p class="description">The div does also support the usage of an external html files even with shortcodes. Below you see the existing external files, how to use them and you can also create/edit/delete them.</p><p class="description">Also you can hide the divs by click or hide them after a given time of ms. Add $hide or $hideXXXX where XXXX is the time in ms. So $hide3000 hides the div after 3 seconds. You can add this like an additional file or even together with it. e.g. #ffffff$hide or #ffffff$file$hide3000 is possible. For this feature it also makes sense to use semi transpartent backrounds. rgba is therefore supported now. The only thing which is important is that , needs to replaced by ;. So rgba(1;1;1;0.5) has to be used here.</p><p class="description">Shortcode attribute: hide_part_of_iframe=""</p>', 'advanced-iframe'),'text','http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/hide-a-part-of-the-iframe', $evanto || $isDemo);
      }
      ?>
    </table>

<?php if ($evanto || $isDemo) { ?>     
  <div class="hide-print">    <h4>
      <?php _e('Existing external div files', 'advanced-iframe') ?></h4>    
    <p>
      <?php _e('You can show a custom html inside the div you create. This makes it possible to show whatever you like over the other iframe. Also shortcodes are supported in the external file. You can use this file if you attach the id of the file to the style settings seperated by a $. So 10,20,200,50,#ffffff$123,10 has to be used if your id is 123 (file name = hide_123.html)', 'advanced-iframe') ?>      
    </p>
    <p>
      <?php _e('The following external div files in the folder "advanced-iframe-custom" currently exist. Please note that you can view/edit this files with the plugin editor of Wordpress by clicking on the "Edit/View" link.', 'advanced-iframe') ?>      
    </p>
<?php
  $config_files = array();
  foreach (glob(dirname(__FILE__) .'/../../advanced-iframe-custom/hide_*.html') as $filename) {
    $base = basename($filename);
    $base_url1 = site_url() . '/wp-admin/plugin-editor.php?file=advanced-iframe-custom%2F';
    $base_url2 = ''; // '&plugin=advanced-iframe%2Fadvanced-iframe.php';
    $config_files[] = $base ; 
  }
echo "<hr height=1>";
if (count($config_files) == 0) {
    echo "<ul><li>";
    _e('No custom external div files found.', 'advanced-iframe');
    echo "</li></ul>";
} else {
  foreach ($config_files as $file) {
    echo '<div class="config-file-block"><div class="ai-external-config-label"><span class="config-list">' .$file .  '</span> &nbsp; <a href="'.$base_url1 . $file . $base_url2 .'">';
    _e('Edit/View', 'advanced-iframe');
    echo '</a>';    
    $rid =  substr(basename($file,'.html'),5);
    echo ' &nbsp; <a class="confirmation post" href="options-general.php?page=advanced-iframe.php&remove-custom-hide-id='.$rid.'">';
    _e('Remove', 'advanced-iframe');
    echo '</a></div>';
    echo '<br /></div>';
  }
}
echo "<hr height=1>";
    ?>       
    <p>
      <?php _e('Create a custom external div file. Only specify the id. All files are named "hide_{id}.html":', 'advanced-iframe') ?><br />
      <input name="ai_custom_hide_id" id="ai_custom_hide_id" type="text" size="20" maxlength="20" />        
      <input id="chf" class="button-primary" type="submit" name="create-custom-hide-id" value="<?php _e('Create external div file', 'advanced-iframe') ?>"/>    
    </p>    
  </div>
  <?php } ?>





<?php if ($devOptions['single_save_button'] == 'false') { ?>      
    <p class="button-submit">
      <input id="rt" class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>
    </p>
<?php } ?> 
</div>

<?php } ?>

  
<?php if ($devOptions['accordeon_menu'] == 'false') { ?>
<div class="ai-anchor" id="mi"></div>
<?php } ?>
<h1 id="h1-mi"><?php _e('Modify the iframe', 'advanced-iframe'); ?></h1>
<div>
    <div id="icon-options-general" class="icon_ai">
      <br>
    </div><h2 id="mi-id">
      <?php _e('Modify the iframe', 'advanced-iframe'); ?></h2>
    
    
    <h3 id="modifycontent"><?php _e('Modify the content of the iframe if the iframe page is on the same domain', 'advanced-iframe') ?><?php
    if ($evanto || $isDemo) {
       _e(' or if you can use the external workaround.', 'advanced-iframe'); 
    } else {
       echo '.';
    }
    ?>

    </h3>
    <p>
      <?php _e('With the following options you can modify the content of the iframe. <strong>IMPORTANT</strong>: This is only possible if the iframe comes from the <strong>same domain</strong> because of the <a href="http://en.wikipedia.org/wiki/Same_origin_policy" target="_blank">same origin policy</a> of Javascript.<p>If you can use the "<a id="external-workaround-link" href="#xss">External workaround</a>", you can also use this setting in the pro version.</p><p>Please read the section "<a class="howto-id-link" href="#">How to find the id and the attributes</a>" above how to find the right styles. If the content comes from a different domain you have to modify the iframe page by e.g. adding a Javascript function that is then called by the onload function you can set above or you add a parameter in the url that you can read in the iframe and display the page differently then. You should also use the external workaround to modify the iframe if your page loads quite slow and you see the modifications on subsequent pages. The reason is that the direct modification can only be done after the page is loaded and the "Hide until loaded" is only working for the 1st page. The external workaround is able to hide the iframe until it is modified always and also css can be added to the header directly.', 'advanced-iframe'); ?>
    </p>
    <table class="form-table">
<?php
      if ($evanto || $isDemo) {
          printTrueFalse(true,$devOptions, __('Add css class to iframe elements', 'advanced-iframe'), 'add_css_class_iframe', __('Sometimes it is not possible to modify existing css classes in the iframe because they are also used somewhere else or there is no unique selector for this element. Also it is sometimes needed that each iframe page do need a different unique selector. Setting this attribute to true causes that in the iframe an unique is created from the iframe url and is added as class to the body and his children. Then you are also able to e.g. hide a element on one page and show it on another page. Shortcode attribute: add_css_class_iframe="true" or add_css_class_iframe="false" ', 'advanced-iframe'),'false','',true); 
      }


        printTextInput(false,$devOptions, __('Hide elements in iframe', 'advanced-iframe'), 'iframe_hide_elements', __('This setting allows you to hide elements inside the iframe. This can be used to hide e.g. a div or a heading. Usage: If you want to hide a div you have to enter a hash (#) followed by the id e.g. #header. If you want to hide a heading which is a &lt;h2&gt; you have to enter h2. You can define several elements separated by , e.g. #header,h2. I recommend using firebug to find the elements and the ids. You can use any valid <a class="jquery-help-link" href="#">jQuery selector pattern</a> here! Also the width and height of the elements are set to 0 because e.g. auto height or auto zoom could have problems measuring! Shortcode attribute: iframe_hide_elements=""', 'advanced-iframe'),'text','http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-auto-height-and-css-modifications', $evanto || $isDemo);
        printTextInput(false,$devOptions, __('Show only one element', 'advanced-iframe'), 'onload_show_element_only', __('You can define which part of the page should be shown in the iframe. You can define the id (e.g. #id) or the class (.class) which should be shown. Be aware that all other elements below the body are removed! So if your css relies on a certain structure you have to add additional css by "Content id in iframe" below. Very often also a background is defined for the header which you should remove below. e.g. by setting background-image: none; in the body. This can be done at "Content id in iframe" and "Content styles in iframe" below. Shortcode attribute: onload_show_element_only=""', 'advanced-iframe'),'text', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-auto-height-and-css-modifications#e7', $evanto || $isDemo);
echo '</table>';
echo '<p>';
       _e('With the next 2 options you can modify the css of your iframe if <strong>it is on the same domain</strong> or if you can use the external workaround and have the pro version. This settings are save to the ai_external.js. The first option defines the id/class/element you want to modify and at the 2nd option you define the styles you want to change.', 'advanced-iframe');
echo '</p><table class="form-table">';

        printTextInput(false,$devOptions, __('Content id in iframe', 'advanced-iframe'), 'iframe_content_id', __('Set the id of the element starting with a hash (#) that defines element you want to modify the css.  You can use any valid <a class="jquery-help-link" href="#">jQuery selector pattern</a> here! In the field below you then define the style you want to overwrite. You can also define more than one element. Please separate them by | and provide the styles below. Please read the note below how to find this id for other templates. #content|h2 means that you want to set a new style for the div content and the heading h2 below. Shortcode attribute: iframe_content_id=""', 'advanced-iframe'),'text','www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-auto-height-and-css-modifications', $evanto || $isDemo);
        printTextInput(false,$devOptions, __('Content styles in iframe', 'advanced-iframe'), 'iframe_content_styles', __('Define the styles that have to be overwritten to enable the full width. Most of the time you have to modify some of the following attributes: width, margin-left, margin-right, padding-left. Please use ; as separator between styles. If you have defined more than one element above (Content id in iframe) please separate the different style sets with |. The default values are: Wordpress default: \'width:450px;padding-left:45px;\'. Twenty Ten: \'margin-left:20px;margin-right:240px\'. iNove: \'width:605px\'. Please read the note below how to find these styles for other templates. If you have defined #content|h2 at the Content id you can e.g. set \'width:650px;padding-left:25px;|padding-left:15px;\'. Shortcode attribute: iframe_content_styles=""', 'iframe_advanced-iframe'),'text','www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-auto-height-and-css-modifications', $evanto || $isDemo);
        
   if ($evanto || $isDemo) {
       printTextInput(true,$devOptions, __('Add css styles to iframe', 'advanced-iframe'), 'iframe_content_css', __('This setting does add the css you enter here directly as last element to the body of the iframe page. The big difference to the two settings before is, that not the css styles are modified by Javascript but a style element is added directly to the iframe. The advantage is that also !important can be used to overwrite such styles. This setting is only supported for the <strong>same domain</strong>. The disadvantage is that adding the style element is still done after the iframe is fully loaded and that writting valid css is a little bit more complicated. Use "Write css directly" for the external workaround. Enter the styles without &lt;style&gt;. The value is sanitized at the output! Therefore not all styles do work! e.g. body &gt; p cannot be used. Use external files if you need this. Shortcode attribute: iframe_content_css=""', 'iframe_advanced-iframe'),'text','', false);
   }          
      ?>
    </table>
<?php
        if ($evanto || $isDemo) {
        _e('<p>With the next 2 options you can modify the target of links in your iframe if <strong>it is on the same domain or if you can use the external workaround and have the pro version. This settings are save to the ai_external.js. </strong>.</p>', 'advanced-iframe');
echo '</p><table class="form-table">';
        
        printTextInput(true,$devOptions, __('Change iframe links/forms target', 'advanced-iframe'), 'change_iframe_links', __('Change links of the iframe page to open the url at a different target. This option does add the attribute target="your target" to the links you define. The targets are defined in the next setting. You can use any valid <a class="jquery-help-link" href="#">jQuery selector pattern</a> here! So if your link e.g. has an id="link1" you have to use "a#link1". If you want to change all links e.g. in the div with the id="menu-div" you have to use "#menu-div a". If you e.g. only want to change all links of pdfs you can use "a[href$=.pdf]"). Also the target of a form can be changed. So using "form" will change the target of all forms. You can also define more than one element. Please separate them with |. Shortcode attribute: change_iframe_links=""', 'iframe_advanced-iframe'),'text', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/change-links-targets', true);
        printTextInput(true,$devOptions, __('Change iframe links/forms target value', 'advanced-iframe'), 'change_iframe_links_target', __('Here you define the targets for the links you define in the setting before. If you have defined more than one element above (Change iframe links) please separate the different targets with |. E.g. "_blank|_top". Shortcode attribute: change_iframe_links_target=""', 'iframe_advanced-iframe'),'text', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/change-links-targets', true);
        echo '</table>';
        }
      ?>
<?php if ($devOptions['single_save_button'] == 'false') { ?>      
    <p class="button-submit">
      <input id="rt" class="button-primary" type="submit" name="update_iframe-loader" value="<?php _e('Update Settings', 'advanced-iframe') ?>"/>
    </p>
<?php } ?> 
</div>