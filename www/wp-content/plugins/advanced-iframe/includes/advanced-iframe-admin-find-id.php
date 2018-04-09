<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
?>
<div>
    <div id="icon-options-general" class="icon_ai">
      <br />
    </div><h2 id="how-id">
<?php _e('How to find ids and attributes', 'advanced-iframe'); ?>       </h2>
    <p>
    <?php 
     _e('<ol><li>Manually: Go to Appearance -> Editor and select the page template. Then you have to look which div elements are defined. e.g. container, content, main. Also classes can be defined here. Then you have to select the style sheet below and search for this ids and classes and look which one does define the width of you content.</li><li>Firebug: For Firefox you can use the plugin firebug to select the content element directly in the page. On the right side the styles are always shown. Look for the styles that set the width or any bigger margins. These are the values you can then overwrite by the settings above.</li><li><strong>Small jquery help</strong><br>Above you have to use the jQuery syntax:<p><ul><li>- tags - if you want to hide/modify a tag directly (e.g. h1, h2) simply use it directly e.g. h1,h2</li><li>- id - if you want to hide/modify an element where you have the id use #id</li><li>- class - if you want to hide/modify an element where you have the class use .class</li></ul></p>You can use any valid <a class="jquery-help-link" href="#">jQuery selector pattern</a> here!</li></ol>', 'advanced-iframe');    
    ?></p>
</div> 


   