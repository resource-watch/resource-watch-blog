<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
/**
 *  Print the donation details depending on the version type
 */
 
 
function printQuickstartGuide() {
_e('<h3>Quick start guide</h3>
 <p>
      <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-video-tutorials" target="_blank" id="vid" class="button-primary">Show me the quickstart video</a>    
</p>

<p>To include a web page to your page please check the following things first:</p>
<ul>
<li>- Check if your page you want to include is allowed to be included:<br />&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/free-iframe-checker">http://www.tinywebgallery.com/blog/advanced-iframe/free-iframe-checker</a>!</li>
<li>- Check if the iframe page and the parent page are one the same domain. www.example.com and text.example.com are different domains!</li>
<li>- Can you modify the page that should be included?</li>
</ul>
<p>Most likely you have one of the following setups:</p>
<ol>
<li>iframe cannot be included: You cannot include the content because the owner does not allow this. </li>
<li>iframe can be included and you are on a different domain: See the <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-comparison-chart" target="_blank">feature comparison chart</a> and the <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-features-availability-overview" target="_blank">features availability overview</a>. To resize the content to the height/width or modify css you <strong>need to modify the remote iframe page</strong> by adding one line of Javascript to enable the provided workaround.</li>
<li>Iframe can be included and you are on the same domain: All features of the plugin can be used.</li>
</ol>', 'advanced-iframe');

_e('<p>To enter a simple iframe please go to the administration and follow the instructions on the basic settings tab. There you can either use a basic shortcode and set the settings in the administration or overwrite the settings directly in the shortcode. Please also read the <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-faq" target="_blank">FAQ</a> and look at the <a href="http://www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0" target="_blank">free</a> and <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo" target="_blank">pro examples</a>.</p>', 'advanced-iframe');

_e('<p>Advanced users that have their own server might also setup a reverse proxy if the iframe page is on a different domain and cannot use the external workaround. See <a href="http://www.tinywebgallery.com/blog/using-a-reverse-proxy-to-enable-all-features-of-advanced-iframe-pro" target="_blank">this blog</a> for details.<br />', 'advanced-iframe');
_e('If you mix http and https read <a href="http://www.tinywebgallery.com/blog/iframe-do-not-mix-http-and-https" target="_blank">this blog</a>. Parent https and iframe http does not work on all browsers!</p>', 'advanced-iframe');

} 
 
function printDonation($devOptions, $evanto) {
if ($evanto) {
      echo '<br/>
      <div>
      <div id="icon-options-general" class="icon_ai">
      <br>
      </div><h2>';
      _e('Advanced iFrame Pro - Quickstart guide, plugin options, widget, vote for the plugin on codecanyon', 'advanced-iframe');
  echo '</h2>';


printQuickstartGuide();


      _e('<h3 class="hide-print">Plugin options</h3>', 'advanced-iframe' );
  echo '<table class="form-table hide-print">';
      printTrueFalse(false,$devOptions, __('Show this section as last tab', 'advanced-iframe'), 'donation_bottom', __('<strong class="move-bottom">You can show this tab as last tab after you have read it. Then the basic tab is shown first.</strong>', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Check shortcode', 'advanced-iframe'), 'check_shortcode', __('<strong class="move-bottom">If you enable this the plugin does check if the shortcode attributes are known. You will find typos, wrong quotes and missing spaces. It does not check the values! The only reason this is not enabled by default is to make sure that old shortcodes don\'t show a warning after an update! I stongly recommend to enable this setting!</strong>', 'advanced-iframe'), true);  
      printTrueFalse(false,$devOptions, __('Enable expert mode', 'advanced-iframe'), 'expert_mode', __('If you enable the expert mode the description is only shown if you click on the label of the setting. You see more settings at once but only one description at once. Also the padding between the table rows are reduced a lot. So you see a lot of more settings on one screen. Use this if you are common with the settings.', 'advanced-iframe'), 'false');
      printTrueFalse(false,$devOptions, __('Use footer save button', 'advanced-iframe'), 'single_save_button', __('The new default is that the save button is in a sticky footer. I was testing this for all major browsers but not for all worpress versions. So if this does not work for your version set this to false to get one save button for each section.', 'advanced-iframe'), 'false');
      printAccordeon($devOptions, __('Use accordeon menu on the advanced tab', 'advanced-iframe'), 'accordeon_menu', __('The accordeon menu on the advanced tab does not show the different sections in one big page but does only show the sections you open. You can define the default section which is open by default here also. Sections do not close if you open another one because sometimes is is useful to open several sections at once. Also the quick jump links at the top are removed because they do not make sense then anymore. The menu is used after you saved this setting. Only important sections are offered in the dropdown.', 'advanced-iframe'), 'false');
      printTextInput(false,$devOptions, __('Alternative shortcode', 'advanced-iframe'), 'alternative_shortcode', __('You can define an alternative shortcode the plugin should evaluate. This is e.g. useful if you chance/upgrade from iframe to advanced iframe (pro). Simply insert "iframe" in the text field. Most if the parameters do already match! Make sure to deactivate the other plugin that used the shortcode. With using iframe also the BBCode [iframe]url[/iframe] is supported. IMPORTANT: If you use this, security codes are NOT checked anymore. So anyone who can e.g. write a post can also insert an iframe!', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Show plugin in main menu', 'advanced-iframe'), 'show_menu_link', __('Show the "Advanced iFrame Pro" Menu link also in the main menu. If set to "False" it is only shown in the settings menu.', 'advanced-iframe'), 'true');
 
      printTrueFalse(false,$devOptions, __('Allow shortcode attributes', 'advanced-iframe'), 'shortcode_attributes', __('Allow to set attributes in the shortcode. All of the attributes can be overwritten in the shortcode if you set \'Yes\'. Otherwise the settings you specify here are used.', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Use shortcode attributes only', 'advanced-iframe'), 'use_shortcode_attributes_only', __('All iframes you use in your pages use the administration. With shortcode attributes you can overwrite these settings. When you use several iframes with different settings this can lead to strange behavior because you do not see the whole configuration in the shortcode. By setting this option to true only the parameters defined as attributes are used. You can set this for a single iframe as well with the shortcode attribute use_shortcode_attributes_only="true". Shortcode attribute: use_shortcode_attributes_only="true" or use_shortcode_attributes_only="false"', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Include ai.js in the footer', 'advanced-iframe'), 'include_scripts_in_footer', __('By default the needed Javascripts are included at the footer. So you can include jQuery also at the footer if you like. If you like/need it in the header set this value to false. Before Wordpress 3.3 jQuery is needed in the header if you want to use lazy-loading! The ai.js has also to be in the footer if it should only be loaded when the shortcode is on the page. This setting cannot be set as shortcode! There is an additional shortcode attribute called include_scripts_in_content="true". This is only needed in the special case if you use the page with content only (like using the plugin "Show Content Only" with "Content + Styles" mode). Then ai.js is directly rendered before the iframe. See demo <a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0/same-domain-wrapped-auto-height">wrapped auto height</a>.', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Load jQuery as dependency', 'advanced-iframe'), 'load_jquery', __('By default jQuery is loaded as dependeny. If you have a theme or another plugin that does not stick to the Wordpress way to load the scripts you might have to disable the dependeny. This avoids that jQuery is loaded again and other plugins do maybe not work anymore.', 'advanced-iframe'), true);
      printTextInput(false,$devOptions, __('Editor button', 'advanced-iframe'), 'editorbutton', __('With this setting you can add an "advanced iframe" button to the text editor of Wordpress. The button does add the shortcode with the current security code if set + the settings you define. You can use any setting from the administration. By default src,width,height is used. The securitykey is additionally rendered if you specify one. If you leave this setting empty the button is not shown.', 'advanced-iframe')); 
     
      printTrueFalse(true,$devOptions, __('Enable content filter', 'advanced-iframe'), 'enable_content_filter', __('This feature does not render an iframe. It gives you the option to filter the content of your page by an id. So you can offer parts of your page that then can be included into any iframe. You only need to specify the id of the element you want to show with the parameter ?ai-show-id-only=id. If you only specify this parameter the whole page is loaded and then with Javascript all other elements are hidden. If you add ai-server-side=1 to the url the content is filtered on the server side but this only works for elements which are in the content area because everything else depends on the template. By default overflow (scrolling) is hidden inside the iframe. If you like that scrollbars are shown if needed add &ai-show-overflow=1 to the url. Also check "Add ai_external.js local" as this actually the even more powerfull solution but more complicated to setup. So try what fits best to your needs! Also the height of the content is sent to the parent by a post message. Please see the <a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/share-content-from-your-domain-content-filter">demo</a> how you can use this and code you need to include to use this the optimal way.', 'advanced-iframe'), 'false', 'http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/share-content-from-your-domain-content-filter');
      printTrueFalse(false,$devOptions, __('Add ai_external.js local', 'advanced-iframe'), 'add_ai_external_local', __('The setting does add the ai_external.js to your own site. This enables you to provide parts of your site into an external iframe. This is simelar to "enable_content_filter" where you can filter parts of your page. The advantage of this solution is that you can use all css modifications and auto height of this solution. Also resize of element resize does work here. Also this works on included links if they still stay on the page. The disadvantage is that it is more complecated to setup then "enable_content_filter" and only one configuration is supported automatically. Also the height of the content is sent to the parent by a post message. If you like to include the script only to a single page use the shortcode [ai_advanced_js_local] to your page. Then the script will be included to your footer. You can even add custom settings like described on the external workaround page by adding a script to the content. Please see the <a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/share-content-from-your-domain-add-ai_external-js-local">demo</a> how you can use this and code you need to include to use this the optimal way.', 'advanced-iframe'), 'false','http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/share-content-from-your-domain-add-ai_external-js-local');
          
      printDebug($devOptions, __('Debug Javascript', 'advanced-iframe'), 'debug_js', __('You can enable that most messages from the Javascript console are shown in a debug div which shown at the bottom of the page. This is very useful if you have problems on Android or IOS because there it is quite hard to get on the infos displayed in the Javascript log. Important: You need to use the external workaround with postMessage set to debug(!) (see the external workaround tab) if you also want to get the messages from the page in the iframe! Also the current user agent, the headers and a basic iframe check of the first found iframe are printed into the debug log. Click on the debug area to enlage and shrink it. Shortcode attribute: debug_js="no/bottom"  ', 'advanced-iframe'));
      
      printTrueFalse(false,$devOptions, __('Check Url on load', 'advanced-iframe'), 'check_iframe_url_when_load', __('By default the url on the basic tab is checked if it can be included into an iframe. Some servers do block this feature and the administration does fail then. If this is the case the plugin does disable this check automatically by setting this setting to false. You can enable this feature again if you allow curl calls on your server.', 'advanced-iframe'), true);
      printAllWarningFalse($devOptions, __('Check iframes on save', 'advanced-iframe'), 'check_iframes_when_save', __('You can define if you show errors only, errors and warnings or if iframes are not checked at all at save.', 'advanced-iframe') );
      printTrueFalse(true,$devOptions, __('Check all iframes once a day', 'advanced-iframe'), 'check_iframe_cronjob', __('You can automatically check all iframes of your site once a day. IF you upgrade from an older version please deactive and active the plugin once to get the cronjob setup properly! The same function like  on the "Basic" tab -> Url -> "Check all iframes" is triggered here. Be aware that default wordpress cronjobs are triggered by the user that hits the next execution time and therefore this user has to wait until this checkis done! In the status e-mail you find also how long the check needed. Because of this performance impact the cron job check only checks until an error happens. So please go to the administration if you get an e-mail for a full check! If the checks of your iframes takes longer than 5 sec. I recommend to switch to a native cronjob if possible. Google for "wordpress cronjob replace" and also add your hoster! Because native cronjobs can not be created everywhere and also often depends on your package. To test cronjobs you can e.g. use the plugin "WP Crontrol" where you can simply execute this cronjob.', 'advanced-iframe'));
      printTextInput(true,$devOptions, __('i-20-Check iframe cronjob email', 'advanced-iframe'), 'check_iframe_cronjob_email', __('You can define an alternative e-mail the iframe status check is sent to. If you leave this field empty the admin e-mail from "Settings -> Email Address" is used. You can define several e-mails seperating them by ",".','advanced-iframe'));
       
     $user = wp_get_current_user();
     if ( in_array( 'administrator', (array) $user->roles ) ) {
         printRoles($devOptions, __('Minimum user role', 'advanced-iframe'), 'roles', __('You can define the minimum user role a user needs to use the advanced iframe plugin. This limits the access to the administration, the editor button and if a user can edit a page with a advanced iframe shortcode. If a page with an advanced iframe is detected and the rights are not sufficient an error message is displayed and the user can not edit the page! A user with insufficient rights still can add the shortcode if he has the security code! This settings only works optimal for the 5 default user roles as it depends on the order of roles! So please check if this settings works for you if you have additional roles! Default restrictions means that the administration is only shown for administrators and the editor button for everyone who can edit a post/page. This setting can only be changed by an administrator!', 'advanced-iframe'), 'contributor');
     }     
  echo '</table><p>';
  echo '<p class="button-submit">
        <input class="button-primary" type="submit" name="update_iframe-loader" value="';
      _e('Update Settings', 'advanced-iframe');
  echo '"/></p>';
  
       if (true) {
  _e('
   <h3>Warning: Illegal copies of Advanced iFrame Pro</h3>
   <p>
   Unfortuatelly for most good plugins on codecanyon also illegal versions can be found in the internet. Please make sure you got your version from codecanyon. Very often, the scripts are modified and allow hackers to access your server. These are very dangerous to use! I already found hacked versions with backdoors!<br />
   </p><p>
   The only offical version of Advanced iFrame Pro can be found here: <a href="http://codecanyon.net/item/advanced-iframe-pro/5344999?ref=mdempfle" target="_blank">http://codecanyon.net/item/advanced-iframe-pro/5344999</a>  
   </p>
   <p>
   Thank you.
   </p>', 'advanced-iframe');
  }
  
      _e('<h3 class="hide-print">Advanced iFrame Pro Widget</h3><p class="hide-print">The pro version also does offer a widget where you can include the iframe. The usage is really simple. Go to Appearance -> Widgets and insert the shortcode you would normally put into a page into the text field of the "Advanced iFrame Pro Widget" .</p>', 'advanced-iframe' );
   
    _e('<h3>Vote for the plugin</h3><p>Thank you for getting Advanced iFrame Pro at Codecanyon.<br/>', 'advanced-iframe' );
    _e('Please feel free to leave an item rating from your items download page if you haven\'t already done so.</p>', 'advanced-iframe' );
    _e('<p>Please get in contact with me if you have problems because most of the issues are easy to solve. But at least tell me what you did not like so I can improve this. Also make sure that you took a look at the quick start guide to make sure the feature you like can be used!</p>', 'advanced-iframe' );
  
  
  

} else {

echo '<br/>
<div>
    <div id="icon-options-general" class="icon_ai">
    <br>
  </div><h2>';
  _e('Advanced iFrame - Upgrading to Advanced iFrame Pro', 'advanced-iframe');
  echo '</h2>
  <p>';
  _e('<p>Advanced iframe is <strong>free for personal use</strong> and the Pro version a bargain for your business. The personal version does already contain many of the cool features of the Pro version. It has a limit of 10.000 views a month which should normaly not been hit by a personal website.</p>', 'advanced-iframe' );

echo '<div id="first" class="signup_account_container signup_account_container_active" style="cursor: default;" title="';
_e('Free - For personal and non-commercial sites', 'advanced-iframe');
echo '">
			<div class="signup_inner">
				<div class="signup_inner_plan">';
        _e(' ', 'advanced-iframe');
        echo '</div>
				<div class="signup_inner_price">
					<strong>';
          _e('FREE', 'advanced-iframe');
          echo '</strong>
				</div>
				<div class="signup_inner_header">';
        _e('For personal and non-commercial sites', 'advanced-iframe');
        echo '</div>
				<div class="signup_inner_desc">';
        _e('10.000 views/month without notice*', 'advanced-iframe');
        echo '</div>
				<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=paypal%40mdempfle%2ede&item_name=advanced%20iframe&item_number=Support%20Open%20Source&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=EN&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank" id="plan_button_pro" class="signup_inner_button">';
         _e('Donate with Paypal', 'advanced-iframe');
        echo '</a>
			</div>
    </div>
      ';
echo '
   <div  class="signup_account_container signup_account_container_active" style="cursor: default;" title="';
   _e('Pro - For commercial, business and professional sites', 'advanced-iframe');
   echo '">
			<div class="signup_inner">
				<div class="signup_inner_plan">';
        _e(' ', 'advanced-iframe');
        echo '</div>
				<div class="signup_inner_price">
					<strong>PRO</strong>
				</div>
				<div class="signup_inner_header">';
        _e('For commercial, business and professional sites', 'advanced-iframe');
        echo '</div>
				<div class="signup_inner_desc">';
        _e('+ <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-comparison-chart" target="_blank">Many additional features!</a><br />&nbsp;', 'advanced-iframe');
        echo '</div>
				<a href="http://codecanyon.net/item/advanced-iframe-pro/5344999?ref=mdempfle" target="_blank" id="plan_button_pro" class="signup_inner_button">';
        _e('Get pro at Codecanyon', 'advanced-iframe');
        echo '</a>
			</div>
		</div>
';
echo '
       <div id="last" class="signup_account_container signup_account_container_active" style="cursor: default;">
			<div class="signup_inner">
				<div class="signup_inner_plan">';
        _e('Pro Version Benefits', 'advanced-iframe');
        echo '</div>

				<div class="signup_inner_desc">
           <ul class="pro"><li>';
           _e('<a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/show-only-a-part-of-the-iframe" target="_blank">Show/Hide specific areas of the iframe</a> if the iframe is on a different domain<br /><a target="_blank" href="http://examples.tinywebgallery.com/configurator/advanced-iframe-area-selector.html">Show the graphical selector</a></li><li><a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/widgets" target="_blank">Widget support</a>, <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/change-links-targets" target="_blank">change link targets</a></li><li>External workaround supports <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-auto-height-and-css-modifications" target="_blank">iframe modifications</a> and <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/responsive-iframes" target="_blank">responsive iframes</a></li><li><a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/browser-detection" target="_blank">Browser dependant settings</a>, <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/lazy-loading" target="_blank">lazy load</a></li><li>No view limit, <a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/zoom-iframe-content" target="_blank">zoom</a>, <a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-standalone">standalone version!</a></li><li><a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo">See the pro demo</a><li><a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-comparison-chart">Compare versions for all features</a>', 'advanced-iframe');
           echo '</li></ul>
        </div>
			</div>
		</div>

<div class="clear"></div><br />
';

_e('<p>* After 10.000 views/month the iframe is still working but below the iframe a small "powered by" notice with a link to the pro version is shown. If you hit this limit and you qualify for the free license please contact <a href="http://www.tinywebgallery.com/en/about.php" target="_blank">me</a> to get a version with a higher limit.<br/>If you use the Advanced iFrame on a non personal website please first test the plugin carefully before buying. After that it is quick and painless to get Advanced iFrame Pro. Simply get <strong><a target="_blank" href="http://codecanyon.net/item/advanced-iframe-pro/5344999?ref=mdempfle">Advanced iFrame Pro on CodeCanyon</a></strong> and be pro in a few minutes!</p>', 'advanced-iframe');

_e('<p><strong>Current status</strong>: ', 'advanced-iframe');
echo get_option('default_a_options') / 100 . ' % of views for this month used.';
_e('</p>', 'advanced-iframe');
echo '</p>';

printQuickstartGuide();

 _e('<h3 class="hide-print">Plugin options</h3>', 'advanced-iframe' );
echo '<table class="form-table">';
      printTrueFalse(false,$devOptions, __('Show this section as last tab', 'advanced-iframe'), 'donation_bottom', __('<strong class="move-bottom">You can show this tab as last tab after you have read it. Then the basic tab is shown first.</strong>', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Check shortcode', 'advanced-iframe'), 'check_shortcode', __('<strong class="move-bottom">If you enable this the plugin does check if the shortcode attributes are known. You will find typos, wrong quotes and missing spaces. It does not check the values! The only reason this is not enabled by default is to make sure that old shortcodes don\'t show a warning after an update! I stongly recommend to enable this setting!</strong>', 'advanced-iframe'), true);
      printTrueFalse(false,$devOptions, __('Show the administration of the pro version', 'advanced-iframe'), 'demo', __('<strong class="move-bottom">You can enable the administration of the pro version to see the available features there. Everything except the additional buttons are shown there. NONE of this settings do work if you enable them. It is only for demonstration. All pro features have a blue label or differences are described in the documentation!</strong>', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Use footer save button', 'advanced-iframe'), 'single_save_button', __('The new default is that the save button is in a sticky footer. I was testing this for all major browsers but not for all worpress versions. So if this does not work for your version set this to false to get one save button for each section.', 'advanced-iframe'), 'false');
      printAccordeon($devOptions, __('Use accordeon menu on the advanced tab', 'advanced-iframe'), 'accordeon_menu', __('The accordeon menu on the advanced tab does not show the different sections in one big page but does only show the sections you open. You can define the default section which is open by default here also. Sections do not close if you open another one because sometimes is is useful to open several sections at once. Also the quick jump links at the top are removed because they do not make sense then anymore. The menu is used after you saved this setting. Only important sections are offered in the dropdown.', 'advanced-iframe'), 'false');
      printTrueFalse(false,$devOptions, __('Allow shortcode attributes', 'advanced-iframe'), 'shortcode_attributes', __('Allow to set attributes in the shortcode. All of the attributes can be overwritten in the shortcode if you set \'Yes\'. Otherwise the settings you specify here are used.', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Use shortcode attributes only', 'advanced-iframe'), 'use_shortcode_attributes_only', __('All iframes you use in your pages use the administration. With shortcode attributes you can overwrite these settings. When you use several iframes with different settings this can lead to strange behavior because you do not see the whole configuration in the shortcode. By setting this option to true only the parameters defined as attributes are used. You can set this for a single iframe as well with the shortcode attribute use_shortcode_attributes_only="true". Shortcode attribute: use_shortcode_attributes_only="true" or use_shortcode_attributes_only="false"', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Include ai.js in the footer', 'advanced-iframe'), 'include_scripts_in_footer', __('By default the needed Javascripts are included at the footer. So you can include jQuery also at the footer if you like. If you like/need it in the header set this value to false. Before Wordpress 3.3 jQuery is needed in the header if you want to use lazy-loading! The ai.js has also to be in the footer if it should only be loaded when the shortcode is on the page. This setting cannot be set as shortcode! There is an additional shortcode attribute called include_scripts_in_content="true". This is only needed in the special case if you use the page with content only (like using the plugin "Show Content Only" with "Content + Styles" mode). Then ai.js is directly rendered before the iframe. See demo <a target="_blank" href="http://www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0/same-domain-wrapped-auto-height">wrapped auto height</a>.', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Load jQuery as dependency', 'advanced-iframe'), 'load_jquery', __('By default jQuery is loaded as dependeny. If you have a theme or another plugin that does not stick to the Wordpress way to load the scripts you might have to disable the dependeny. This avoids that jQuery is loaded again and other plugins do maybe not work anymore.', 'advanced-iframe'), true);
     
      printTextInput(false,$devOptions, __('Editor button', 'advanced-iframe'), 'editorbutton', __('With this setting you can add an "advanced iframe" button to the text editor of Wordpress. The button does add the shortcode with the current security code if set + the settings you define. You can use any setting from the administration. By default src,width,height is used. The securitykey is additionally rendered if you specify one. If you leave this setting empty the button is not shown.', 'advanced-iframe')); 
      printDebug($devOptions, __('Debug Javascript', 'advanced-iframe'), 'debug_js', __('You can enable that most messages from the Javascript console are shown in a debug div which shown at the bottom of the page. This is very useful if you have problems on Android or IOS because there it is quite hard to get on the infos displayed in the Javascript log. Important: You need to use the external workaround with postMessage set to debug(!) (see the external workaround tab) if you also want to get the messages from the page in the iframe! Also the current user agent, the headers and a basic iframe check of the first found iframe are printed into the debug log. Click on the debug area to enlage and shrink it. Shortcode attribute: debug_js="no/bottom"  ', 'advanced-iframe'));         
      printTrueFalse(false,$devOptions, __('Check Url on load', 'advanced-iframe'), 'check_iframe_url_when_load', __('By default the url on the basic tab is checked if it can be included into an iframe. Some servers do block this feature and the administration does fail then. If this is the case the plugin does disable this check automatically by setting this setting to false. You can enable this feature again if you allow curl calls on your server.', 'advanced-iframe'), true);
      printAllWarningFalse($devOptions, __('Check iframes on save', 'advanced-iframe'), 'check_iframes_when_save', __('You can define if you show errors only, errors and warnings or if iframes are not checked at all at save.', 'advanced-iframe') );
echo '
     </table>
    <p class="button-submit">
      <input class="button-primary" type="submit" name="update_iframe-loader" value="';
      _e('Update Settings', 'advanced-iframe');
echo '"/>
    </p>

';
}
}
?>