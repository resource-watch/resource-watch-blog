<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
?>
<div>
     <div id="icon-options-general" class="icon_ai">
      <br>
    </div><h2 id="browser-detection-id">
      <?php _e('Advanced iframe browser detection', 'advanced-iframe'); ?></h2>
      <p>
     Pro users can now specify browser specific iframes. This is imporant especially for the "Show only part of the iframe" feature where browser differences of a few pixels can matter. But you can use this for other things as well because mobile, iphone, ipad can also be detected.
      </p>
     <?php if ($evanto || $isDemo) { 
     $securitykeyString =  $devOptions['securitykey'] == '' ? '' : 'securitykey="xxx" ';
      ?>
    <p>
    <a href="#" onclick="jQuery('#browser-help').show(); return false;" > <?php _e('Show me how to configure the browser detection in advanced iframe pro.') ?></a>
    </p>
      <?php
      _e('<div id="browser-help">
         <p>
         Modern website designs are not pixel based anymore and depending on the features of the browser they also look slightly different. So if you use the "Show only part of the iframe" feature it is possible that the area you want to cut out of the website is at a slightly different place. You can also use the browser detection to show different iframes for different browsers or even mobile devices.
         </p>
         <h3>Setup</h3>
         <p>
         If you want to have different iframe configurations depending on the browser you have to use the shortcode attribute <strong>browser=""</strong> and define the browsers there which should be used for this shortcode. See the different <a href="#config-options">configuration options</a> below. You can define several browsers by separaring them by, and even define browser versions by adding the versions with (version). Each of the shortcodes which are browser dependent need to have the <strong>same id</strong>! The last shortcode should have the attribute browser="default". This is then used if no browser does match before. If you don\'t do this you can show iframes only for a specific browser.
         </p>
         <h4>Example 1 - Special settings for IE 10 and IE 11</h4>
         <p>
            [advanced_iframe '.$securitykeyString.'id="example1" show_part_of_iframe_x="25" browser="ie(10),ie(11)"]<br />
            [advanced_iframe '.$securitykeyString.'id="example1" show_part_of_iframe_x="20" browser="default"]
         </p>
         <h4>Example 2 - Special settings for IE, Firefox and Chrome</h4>
         <p>
            [advanced_iframe '.$securitykeyString.'id="example2" show_part_of_iframe_x="25" browser="ie"]<br />
            [advanced_iframe '.$securitykeyString.'id="example2" show_part_of_iframe_x="23" browser="firefox,chrome"]<br />
            [advanced_iframe '.$securitykeyString.'id="example2" show_part_of_iframe_x="20" browser="default"]
         </p>
          <h4>Example 3 - Show a different iframe on iframe on apple devices and mobile devices</h4>
         <p>
            [advanced_iframe '.$securitykeyString.'id="example3" src="apple iframe" browser="iphone,ipad,ipod"]<br />
            [advanced_iframe '.$securitykeyString.'id="example3" src="other mobile devices iframe" browser="mobile"]<br />
            [advanced_iframe '.$securitykeyString.'id="example3" src="normal iframe" browser="default"]
         </p>

         <h3 id="config-options">Configuration options</h3>
         
         The following options for most common browsers can be used:
         <ul id="browser-list">
           <li>ie - Selects all versions of Internet Explorer. Also a version is supported. ie(10) selects IE10, ie(11) selects IE11</li>
           <li>safari - Selects all versions of Safari. Also a version is supported. Add the version in (). e.g. safari(5)</li>
           <li>firefox - Selects all versions of Firefox. Also a version is supported. Add the version in (). e.g. firefox(20)</li>
           <li>chrome - Selects all versions of Chrome. Also a version is supported. Add the version in (). e.g. chrome(25)</li>
           <li>opera - Selects all versions of Opera. Also a version is supported. Add the version in (). e.g. opera(20)</li>
           <li>ipad - Selects all versions of ipad.</li>
           <li>ipod - Selects all versions of ipod.</li>
           <li>iphone - Selects all versions of iphone.</li>
           <li>mobile - Selects all mobile devices.</li>
           <li>tablet - Selects all tablet devices.</li>
           <li>android - Selects all android devices.</li> 
           <li>androidtablet - Selects all android tablet devices.</li> 
           <li>desktop - Selects all desktop browsers.</li> 
           <li>browser - Selects all browsers. Desktop, tablet and mobile. Can be used to show something only for browsers and e.g for crawlers you can use the default and show nothing.</li> 
           <li>default - Is used if no other advanced iframe pro with the same id was selected before.</li>
         </ul>

      <h3>Credit and update</h3>
      <p>
        Advanced iFrame Pro uses an integrated browser detection which is based on the wordpress plugin php-browser-detection 3.2.
      </p>
      <p>
         If the automatich update does not work you can get an updated version of the browsercap.ini lite file here: http://browscap.org/<br />Please use the light version as it conains all settings for the provided settings ! 
      </p>
      <p>
         If you want to update the browser detection file get the lite_php_browscap.ini from there and rename it to php-browser-detection/cache/browscap.ini.<br />
         Or always get the latest version of the advanced iframe pro plugin. This file is also updated there!
      </p>
      </div>
    ', 'advanced-iframe');
}
?>
</div>
