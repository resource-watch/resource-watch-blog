<?php
/*
Plugin Name: Advanced iFrame
Plugin URI: http://www.tinywebgallery.com/blog/advanced-iframe
Version: 7.5.4
Text Domain: advanced-iframe
Domain Path: /languages
Author: Michael Dempfle
Author URI: http://www.tinywebgallery.com
Description: This plugin includes any webpage as shortcode in an advanced iframe or embeds the content directly.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
define('_VALID_AI', '42');
define('AIP_URL', plugin_dir_url( __FILE__ ));
define('AIP_IMGURL',  AIP_URL.'img');


include dirname(__FILE__) . '/includes/advanced-iframe-main-helper.php';

if (!class_exists('advancediFrame')) {
    class advancediFrame
    {

        var $adminOptionsName = 'advancediFrameAdminOptions';
        var $scriptsNeeded = false;

        /**
         *  wp init
         */
        function init()
        {
            $this->getAiAdminOptions();
        }

        /**
         *  wp activate
         */
        function activate()
        {
            $this->getAiAdminOptions();
            if (! wp_next_scheduled ( 'ai_check_iframes_event' )) {
	              wp_schedule_event(time(), 'daily', 'ai_check_iframes_event');
            }
        }

        /*
         * wp deactivate
         */
        function deactivate() {
	          wp_clear_scheduled_hook('ai_check_iframes_event');
        }
        function aiCheckIframes() {
            $before = time();
            include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-functions.php';
            $options = $this->getAiAdminOptions();
            if ($options['check_iframe_cronjob'] !== 'false') {
               if (empty ($options['check_iframe_cronjob_email'])) {
                   $to = get_bloginfo('admin_email');
               } else {
                   $to = $options['check_iframe_cronjob_email'];
               }
               $result = ai_check_all_iframes($options['src'], true);
               $after = time();
               $total_time = $after-$before;
               $body = '<html><head>';
               $body .= '</head><body>';
               $body .= __('Hello advanced iframe pro user,', 'advanced-iframe');
               $body .= '<p>';
               if ($result['overall_status'] == 'red') {
                  $subject = __('At least one of your iframes failed!', 'advanced-iframe');
                  $body .= __('At least one of the iframes you have configured has a src which does not work properly.<br>Because checking all pages takes quite a while the test was only performed to the first error.<br>Please go to the administration of advanced iframe pro to get more details.', 'advanced-iframe');
               } else if ($result['overall_status'] == 'orange') {
                  $subject = __('At least one of your iframes is not optimal configured!', 'advanced-iframe');
                  $body .= __('At least one of the iframes you have configured has most likely an src which is redirected.<br>Because checking all pages takes quite a while the test was only performed to the first warning.<br>Please go to the administration of advanced iframe pro to get more details.', 'advanced-iframe');
               } else {
                  $subject = __('All your iframes look good.', 'advanced-iframe');
                  $body .= __('All your iframes look good.<br>Please go to the administration of advanced iframe pro to get more details.', 'advanced-iframe');
               }
               $body .= '</p><p>';
               $body .= __('The test took ', 'advanced-iframe') . $total_time . __(' sec.', 'advanced-iframe');
               $body .= '</p>';
               $body .= __('Best regards,<br>Your advanced iframe plugin', 'advanced-iframe');
               $body .= '</body></html>';
               $headers = array('Content-Type: text/html; charset=UTF-8');
               wp_mail( $to, $subject, $body, $headers );
            }
        }
        /**
         * Set the iframe default
         */
        function iframe_defaults() {
            $auth_key = defined( 'AUTH_KEY' ) ? AUTH_KEY : 'AUTH_KEY_MISSING';
            $iframeAdminOptions = array(
                'securitykey' => '',
                'src' => '//www.tinywebgallery.com', 'width' => '100%',
                'height' => '600', 'scrolling' => 'none', 'marginwidth' => '0', 'marginheight' => '0',
                'frameborder' => '0', 'transparency' => 'true', 'content_id' => '', 'content_styles' => '',
                'hide_elements' => '', 'class' => '', 'shortcode_attributes' => 'true', 'url_forward_parameter' => '',
                'id' => 'advanced_iframe', 'name' => '',
                'onload' => '', 'onload_resize' => 'false', 'onload_scroll_top' => 'false',
                'additional_js' => '', 'additional_css' => '', 'store_height_in_cookie' => 'false',
                'additional_height' => '0', 'iframe_content_id' => '', 'iframe_content_styles' => '',
                'iframe_hide_elements' => '', 'version_counter' => '1', 'onload_show_element_only' => '',
                'include_url' => '', 'include_content' => '', 'include_height' => '', 'include_fade' => '',
                'include_hide_page_until_loaded' => 'false', 'donation_bottom' => 'false',
                'onload_resize_width' => 'false', 'resize_on_ajax' => '', 'resize_on_ajax_jquery' => 'true',
                'resize_on_click' => '', 'resize_on_click_elements' => 'a', 'hide_page_until_loaded' => 'false',
                'show_part_of_iframe' => 'false', 'show_part_of_iframe_x' => '100', 'show_part_of_iframe_y' => '100',
                'show_part_of_iframe_width' => '400', 'show_part_of_iframe_height' => '300',
                'show_part_of_iframe_new_window' => '', 'show_part_of_iframe_new_url' => '',
                'show_part_of_iframe_next_viewports_hide' => 'false', 'show_part_of_iframe_next_viewports' => '',
                'show_part_of_iframe_next_viewports_loop' => 'false', 'style' => '',
                'use_shortcode_attributes_only' => 'false', 'enable_external_height_workaround' => 'external',
                'keep_overflow_hidden' => 'false', 'hide_page_until_loaded_external' => 'false',
                'onload_resize_delay' => '', 'expert_mode' => 'false',
                'show_part_of_iframe_allow_scrollbar_vertical' => 'false',
                'show_part_of_iframe_allow_scrollbar_horizontal' => 'false',
                'hide_part_of_iframe' => '', 'change_parent_links_target' => '',
                'change_iframe_links' => '', 'change_iframe_links_target' => '',
                'browser' => '', 'show_part_of_iframe_style' => '',
                'map_parameter_to_url' => '', 'iframe_zoom' => '',
                'accordeon_menu' => 'no',
                'show_iframe_loader' => 'false',
                'tab_visible' => '', 'tab_hidden' => '',
                'enable_responsive_iframe' => 'false',
                'allowfullscreen' => 'false', 'iframe_height_ratio' => '',
                'enable_lazy_load' => 'false', 'enable_lazy_load_threshold' => '3000',
                'enable_lazy_load_fadetime' => '0', 'enable_lazy_load_manual' => 'false',
                'pass_id_by_url' => '', 'include_scripts_in_footer' => 'true',
                'write_css_directly' => 'false', 'resize_on_element_resize' => '',
                'resize_on_element_resize_delay' => '250', 'add_css_class_parent' => 'false',
                'auto_zoom' => 'false', 'auto_zoom_by_ratio' => '',
                'single_save_button' => 'true', 'enable_lazy_load_manual_element' => '',
                'alternative_shortcode' => '', 'show_menu_link' => 'true',
                'iframe_redirect_url' => '', 'install_date' => 0,
                'show_part_of_iframe_last_viewport_remove' => 'false',
                'load_jquery' => 'true', 'show_iframe_as_layer' => 'false',
                'add_iframe_url_as_param' => 'false', 'add_iframe_url_as_param_prefix' => '',
                'reload_interval' => '', 'iframe_content_css' => '',
                'additional_js_file_iframe' => '', 'additional_css_file_iframe' => '',
                'add_css_class_iframe' => 'false', 'editorbutton' => 'src,width,height',
                'iframe_zoom_ie8' => 'false', 'enable_lazy_load_reserve_space' => 'true',
                'hide_content_until_iframe_color' => '', 'use_zoom_absolute_fix' => 'false',
                'include_html' => '', 'enable_ios_mobile_scolling' => 'false',
                'sandbox' => '', 'show_iframe_as_layer_header_file' => '',
                'show_iframe_as_layer_header_height' => '100', 'show_iframe_as_layer_header_position' => 'top',
                'resize_min_height' => '1', 'show_iframe_as_layer_full' => 'false',
                'demo' => 'false', 'show_part_of_iframe_zoom' => 'false',
                'external_height_workaround_delay' => '0',
                'add_document_domain' => 'false', 'document_domain' => '',
                'multi_domain_enabled' => 'false', 'check_shortcode' => 'false',
                'use_post_message' => 'false', 'element_to_measure_offset' => '0',
                'data_post_message' => '', 'element_to_measure' => 'default',
                'show_iframe_as_layer_keep_content' => 'true','roles' => 'none',
                'parent_content_css' => '',
                'include_scripts_in_content' => 'false', 'debug_js' => 'false',
                'check_iframe_cronjob' => 'false', 'check_iframe_cronjob_email' => '',
                'enable_content_filter' => 'false', 'add_ai_external_local' => 'false',
                'title' => '', 'check_iframes_when_save' => 'error',
                'admin_was_loaded' => 'true', 'check_iframe_url_when_load' => 'true'
            );
            return $iframeAdminOptions;
        }

        function printError($message)
        {
            echo '
           <div class="error">
              <p><strong>' . $message . '
                 </strong>
              </p>
           </div>';
        }

        /**
         * Get the admin options
         */
        function getAiAdminOptions()
        {
            $iframeAdminOptions = advancediFrame::iframe_defaults();
            $devOptions = get_option("advancediFrameAdminOptions");
            if (!empty($devOptions)) {
                foreach ($devOptions as $key => $option)
                    $iframeAdminOptions[$key] = $option;
            }  else {
                // new installations do now get postMessage as default
                if (file_exists(dirname(__FILE__) . "/includes/class-cw-envato-api.php")) {
                    $iframeAdminOptions['use_post_message'] = 'true';
                }
            }
            update_option("advancediFrameAdminOptions", $iframeAdminOptions);
            return $iframeAdminOptions;
        }

        /**
         *  loads the language files
         */
        function loadLanguage()
        {
            load_plugin_textdomain('advanced-iframe', false, dirname(plugin_basename(__FILE__)) . '/languages');
            $options = $this->getAiAdminOptions();
            if ($options['load_jquery'] === 'true') {
                wp_enqueue_script('jquery');
            }
        }

        /* CSS and js for the admin area - only loaded when needed */
        function addAdminHeaderCode($hook)
        {
            if ($hook != 'settings_page_advanced-iframe' && $hook != 'toplevel_page_advanced-iframe')
                return;
            $options = get_option('advancediFrameAdminOptions');
            // defaults
            extract(array('version_counter' => $options['version_counter']));
            wp_enqueue_style('ai-css', plugins_url('css/ai.css', __FILE__), false, $version_counter);
            // wp_enqueue_style('ai-css-print', plugins_url( 'css/ai-print.css' , __FILE__ ), false, $version_counter);
            wp_enqueue_script('ai-js', plugins_url('js/ai.js', __FILE__), false, $version_counter);
            wp_enqueue_script('ai-search', plugins_url('js/findAndReplaceDOMText.js', __FILE__), false, $version_counter);
        }

        /* Add the Javascript for the iframe button above the editor. */
        function addAiButtonJs()
        {
            $options = get_option('advancediFrameAdminOptions');
            if ($options['editorbutton'] != '' && $this->hasValidRole()) {
                $additional_settings = '';
                    $elements = explode (',',  $options['editorbutton']);
                    foreach ($elements as $setting) {
                        $setting = trim($setting);
                        if ($setting != 'securitykey') {
                            if (isset($options[$setting])) {
                                $new_setting = $options[$setting];
                                if (!empty($new_setting)) {
                                    $additional_settings .= ' ' . esc_html($setting) . '=\"' . esc_html(trim($new_setting)) . '\"';
                                }
                            }
                        }       
                    }
 
                echo '<script type="text/javascript">
              jQuery(document).ready(function(){
                 jQuery("#insert-iframe-button").click(function() {';
                 if ($options['securitykey'] != '') {
                   echo 'send_to_editor("[advanced_iframe securitykey=\"' . $options['securitykey'] . '\"' . $additional_settings . ']");';
                 } else {
                   echo 'send_to_editor("[advanced_iframe' . $additional_settings . ']");';
                 }
                 echo 'return false;
                 });
              });
              </script>';
            }
        }

        /* Add iframe button above the editor. */
        function addAiButton()
        {
            $options = get_option('advancediFrameAdminOptions');
            if ($options['editorbutton'] != '' && $this->hasValidRole()) {
                echo '<a title="Insert Advanced iFrame" class="button insert-media add_media" id="insert-iframe-button" href="#"><img style="padding-bottom:3px;" src="'. AIP_IMGURL . '/logo_16x16.png" />Add Advanced iFrame</a>';
            }
        }

        /* additional CSS for wp area */
        function addWpHeaderCode($atts)
        {
            $options = get_option('advancediFrameAdminOptions');
            // defaults
            extract(array('additional_css' => $options['additional_css'],
                'additional_js' => $options['additional_js'],
                'version_counter' => $options['version_counter'],
                'enable_lazy_load' => $options['enable_lazy_load'],
                'include_scripts_in_footer' => $options['include_scripts_in_footer'],
                'add_css_class_parent' => $options['add_css_class_parent'],
                'add_ai_external_local' => $options['add_ai_external_local'],
                $atts));
            $to_footer = ($include_scripts_in_footer === 'true' && $add_css_class_parent === 'false');

            $older_version = version_compare(get_bloginfo('version'), '3.3') < 0; // wp < 3.3 - older version need to be included here
            $this->include_additional_files($additional_css, $additional_js, $version_counter, $older_version, $to_footer);

            $dep = ($options['load_jquery'] === 'true') ? array('jquery') : array();
            wp_enqueue_script('ai-js', plugins_url('js/ai.js', __FILE__), $dep, $version_counter, $to_footer);    
            if ($add_ai_external_local == 'true') {
                wp_enqueue_script('ai-external-js', plugins_url('js/ai_external.js', __FILE__), $dep, $version_counter, $to_footer);
            }
        }
        function addAiExternalLocal($atts) {
            $options = get_option('advancediFrameAdminOptions');
            $dep = ($options['load_jquery'] === 'true') ? array('jquery') : array();
            // we add this independant of the main settings to make the feature more save.
            echo "<script>var domainMultisite = 'true';var usePostMessage = true;</script>";
            wp_enqueue_script('ai-external-js', plugins_url('js/ai_external.js', __FILE__), $dep, $options['version_counter'] , 'true');
        }

        function addCustomCss($parent_content_css) {             
             if (!empty($parent_content_css)) {  
                 echo '<style>';
                 echo wp_kses($parent_content_css, array());
                 echo '</style>';
             }
        }

        /**
         * Checks the parameter and returns the value. If only chars on the whitelist are in the request nothing is done
         * Otherwise it is returned encoded.
         */
        function param($param, $content = null)
        {
            // get and post parameters are checked. if both are set the get parameter is used.
            $value = isset($_GET[$param]) ? $_GET[$param] : (isset($_POST[$param]) ? $_POST[$param] : '');

            $value_check = $value;
            // first we decode the param to be sure the it is not already encoded or doubleencoded as part of an attack
            while ($value_check != urldecode($value_check)) {
                $value_check = urldecode($value_check);
            }
            if (get_magic_quotes_gpc()) {
                $value_check = stripcslashes($value_check);
            }
            // If all chars are in the whitelist no additional encoding is done!
            if (preg_match('/^[\.@~a-zA-Z0-9À-ÖØ-öø-ÿ\/\:\-\|\)\(]*$/', $value_check)) {
                return $value;
            } else {
                return urlencode($value);
            }
        }

       
        function addPx($value)
        {
            if (strpos($value, '-') === false && strpos($value, '+') === false) {
                $value = trim($value);
                if (strpos($value, 'px') === false && strpos($value, 'px') === false &&
                    strpos($value, '%') === false && strpos($value, '%') === false &&
                    strpos($value, 'vw') === false && strpos(strtolower($value), 'vh') === false
                ) {
                    $value = $value . 'px';
                }
            }
            return $value;
        }

        /**
         *  renders the iframe script
         */
        function do_iframe_script($atts, $content = null)
        {
            global $aip_standalone, $iframeStandaloneDefaultOptions, $iframeStandaloneOptions;
  
            $isValidBrowser = true;
            $html = ''; // the output

            include dirname(__FILE__) . '/includes/advanced-iframe-main-read-config.php';

            if (!$isValidBrowser) {
                return;
            }

            include dirname(__FILE__) . '/includes/advanced-iframe-main-css.php';
            // check if the ai_external.js does exist

            $script_name = dirname(__FILE__) . '/js/ai_external.js';
            if (!isset($aip_standalone) && !file_exists($script_name)) {
                $retValue = $this->saveExternalJsFile(false);
                if (!empty($retValue)) {
                    return $error_css . '<div class="errordiv">' . $retValue . '</div>';
                }
            }

            if ($options['securitykey'] != '' && $options['securitykey'] != $securitykey && empty($alternative_shortcode)) {
                return $error_css . '<div class="errordiv">' . __('No valid security key found. Please use at least the following shortcode:<br>&#91;advanced_iframe securitykey="&lt;your security key - see settings&gt;"&#93;<br /> Please also check in the html mode that your shortcode does only contain normal spaces and not a &amp;nbsp; instead.  It is also possible that you use wrong quotes like &#8220; or &#8221;. Only &#34; is valid!', 'advanced-iframe') . '</div>';
            } else if ($src == "not set" && empty($include_url) && empty($include_html)) {
                return $error_css . '<div class="errordiv">' . __('You have set "Use shortcode attributes only" (use_shortcode_attributes_only) to "true" which means that you have to specify all parameters as shortcode attributes. Please specify at least "securitykey" and "src". Examples are available in the administration.', 'advanced-iframe') . '</div>';
            } else {
                if (empty($include_url) && empty($include_html)) {
                    include dirname(__FILE__) . '/includes/advanced-iframe-main-prepare.php';
                    include dirname(__FILE__) . '/includes/advanced-iframe-main-iframe.php';
                    include dirname(__FILE__) . '/includes/advanced-iframe-main-after-iframe.php';
                } else {
                    include dirname(__FILE__) . '/includes/advanced-iframe-main-include-directly.php';
                }
                return $html;
            }
        }

        /**
         * Enqueue the additional js or css
         */
        function include_additional_files($additional_css, $additional_js, $version_counter, $version, $to_footer)
        {
            if ($additional_css != '' && $version) {  // wp >= 3.3
                wp_enqueue_style('additional-advanced-iframe-css', $additional_css, false, $version_counter);
            }
            if ($additional_js != '' && $version) {  // wp >= 3.3 
                wp_enqueue_script('additional-advanced-iframe-js', $additional_js, false, $version_counter, $to_footer);
            }
        }

        function add_script_footer()
        {
            if (!$this->scriptsNeeded) {
                $options = get_option('advancediFrameAdminOptions');
                if ($options['enable_content_filter'] == 'true' && isset($_GET['ai-show-id-only'])) {
                    $ai_show_id_only = $_GET['ai-show-id-only'];
                    echo '<script type="text/javascript">var ai_show_id_only = "' . esc_js($ai_show_id_only) . '"</script>';
                    echo '<style>html, body { margin: 0px !important; padding: 0px !important; }</style>';
                } else {
                wp_dequeue_script('ai-js');
                }
                wp_dequeue_script('additional-advanced-iframe-js');
                wp_dequeue_script('ai-change-js');
                wp_dequeue_script('ai-lazy-js');
            } else {
                echo '<script type="text/javascript">if(window.aiModifyParent) {aiModifyParent();}</script>';
            }
        }

        function printAdminPage()
        {
            require_once('advanced-iframe-admin-page.php');
        }

        function saveExternalJsFile($backend = true)
        {
            $devOptions = $this->getAiAdminOptions();
            $template_name = dirname(__FILE__) . '/js/ai_external.template.js';

            $jquery_path = site_url() . '/wp-includes/js/jquery/jquery.js';
          
            $content = file_get_contents($template_name);
            $new_content = str_replace('PLUGIN_URL', plugins_url() . '/advanced-iframe', $content);
            $new_content = str_replace('PARAM_ID', $devOptions['id'], $new_content);
            $new_content = str_replace('PARAM_IFRAME_HIDE_ELEMENTS', $devOptions['iframe_hide_elements'], $new_content);
            $new_content = str_replace('PARAM_ONLOAD_SHOW_ELEMENT_ONLY', $devOptions['onload_show_element_only'], $new_content);
            $new_content = str_replace('PARAM_IFRAME_CONTENT_ID', $devOptions['iframe_content_id'], $new_content);
            $new_content = str_replace('PARAM_IFRAME_CONTENT_STYLES', $devOptions['iframe_content_styles'], $new_content);
            $new_content = str_replace('PARAM_CHANGE_IFRAME_LINKS_TARGET', $devOptions['change_iframe_links_target'], $new_content);
            $new_content = str_replace('PARAM_CHANGE_IFRAME_LINKS', $devOptions['change_iframe_links'], $new_content);

            $delay = empty($devOptions['external_height_workaround_delay']) ? '0' : $devOptions['external_height_workaround_delay'];
            $new_content = str_replace('PARAM_ENABLE_EXTERNAL_HEIGHT_WORKAROUND_DELAY', $delay, $new_content);

            // external and true = true, false = false
            $isExternal =  ($devOptions['enable_external_height_workaround'] == 'false') ? 'false': 'true';
            $new_content = str_replace('PARAM_ENABLE_EXTERNAL_HEIGHT_WORKAROUND', $isExternal, $new_content);
            $new_content = str_replace('PARAM_KEEP_OVERFLOW_HIDDEN', $devOptions['keep_overflow_hidden'], $new_content);
            $new_content = str_replace('PARAM_HIDE_PAGE_UNTIL_LOADED_EXTERNAL', $devOptions['hide_page_until_loaded_external'], $new_content);
            $new_content = str_replace('PARAM_IFRAME_REDIRECT_URL', $devOptions['iframe_redirect_url'], $new_content);
            $new_content = str_replace('PARAM_ENABLE_RESPONSIVE_IFRAME', $devOptions['enable_responsive_iframe'], $new_content);
            $new_content = str_replace('PARAM_WRITE_CSS_DIRECTLY', $devOptions['write_css_directly'], $new_content);
            $new_content = str_replace('PARAM_RESIZE_ON_ELEMENT_RESIZE_DELAY', $devOptions['resize_on_element_resize_delay'], $new_content);
            $new_content = str_replace('PARAM_RESIZE_ON_ELEMENT_RESIZE', $devOptions['resize_on_element_resize'], $new_content);
            $new_content = str_replace('PARAM_URL_ID', $devOptions['pass_id_by_url'], $new_content);

            $new_content = str_replace('PARAM_JQUERY_PATH', $jquery_path, $new_content);
            $new_content = str_replace('PARAM_ADD_IFRAME_URL_AS_PARAM', $devOptions['add_iframe_url_as_param'], $new_content);
            $new_content = str_replace('PARAM_ADDITIONAL_CSS_FILE_IFRAME', $devOptions['additional_css_file_iframe'], $new_content);
            $new_content = str_replace('PARAM_ADDITIONAL_JS_FILE_IFRAME', $devOptions['additional_js_file_iframe'], $new_content);
            $new_content = str_replace('PARAM_ADD_CSS_CLASS_IFRAME', $devOptions['add_css_class_iframe'], $new_content);
            $new_content = str_replace('PARAM_TIMESTAMP', date("Y-m-d H:i:s"), $new_content);

            $new_content = str_replace('MULTI_DOMAIN_ENABLED', $devOptions['multi_domain_enabled'], $new_content);
            $new_content = str_replace('USE_POST_MESSAGE', ($devOptions['use_post_message'] != 'false') ? 'true':'false' , $new_content);
            $new_content = str_replace('DEBUG_POST_MESSAGE', ($devOptions['use_post_message'] == 'debug') ? 'true':'false' , $new_content);
            $new_content = str_replace('DATA_POST_MESSAGE', $devOptions['data_post_message'], $new_content);
            $new_content = str_replace('PARAM_SEND_CONSOLE_LOG', ($devOptions['debug_js'] == 'true') ? 'true':'false' , $new_content);

            $asParts = parse_url(site_url()); // PHP function
            $home_url = $asParts['scheme'] . '://' . $asParts['host'];
            $post_domain = ($devOptions['multi_domain_enabled'] == 'true') ? '*' : $home_url;
            $new_content = str_replace('POST_MESSAGE_DOMAIN', $post_domain, $new_content);

            $new_content = str_replace('PARAM_ELEMENT_TO_MEASURE_OFFSET', $devOptions['element_to_measure_offset'], $new_content);
            $new_content = str_replace('PARAM_ELEMENT_TO_MEASURE', $devOptions['element_to_measure'], $new_content);

            $script_name = dirname(__FILE__) . '/js/ai_external.js';
            clearstatcache();
            if (file_exists($script_name)) {
                if (!unlink($script_name)) {
                    if ($backend) {
                        $errorText = __('The file "advanced-iframe/js/ai_external.js" can not be removed before saving. Please check the permissions of the js folder and the ai_external.js and save the settings again. This file is needed for the external workaround! If you don\'t use the external workaround please create a empty file with the name ai_external.js in the js folder of the plugin.', "advanced-iframe");
                        printError($errorText);
                    } 
                    return '';
                }
            }  
            $fh = fopen($script_name, 'w');
            if ($fh) {
                fwrite($fh, $new_content);
                fclose($fh);
            } else {
                $errorText = __('The file "advanced-iframe/js/ai_external.js" can not be saved. Please check the permissions of the js folder and save the settings again. This file is needed for the external workaround! If you don\'t use the external workaround please create a empty file with the name ai_external.js in the js folder of the plugin.', "advanced-iframe");
                if ($backend) {
                    printError($errorText);
                } else {
                    return $errorText;
                }
            }
            return '';
        }


        function ai_startsWith($haystack, $needle)
        {
            return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
        }

        function ai_endsWith($haystack, $needle)
        {
            return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
        }

        function ai_createCustomFolder()
        {
            $filenamedir = dirname(__FILE__) . '/../advanced-iframe-custom';
            if (!@file_exists($filenamedir)) {
                if (!@mkdir($filenamedir)) {
                    echo 'The directory "advanced-iframe-custom" could not be created in the plugin folder. Custom files are stored in this directory because Wordpress does delete the normal plugin folder during an update. Please create the folder manually.';
                    return false;
                }
            }
        }

        function checkIE8()
        {
            $filenamedir = dirname(__FILE__) . '/../advanced-iframe-custom/browser-check-failed.txt';
            if (file_exists($filenamedir)) {
                return false;
            } else {
                $this->ai_createCustomFolder();
                $fh = @fopen($filenamedir, 'w');
                if ($fh) {
                    @fwrite($fh, "Browser detection crashed. Please increase your php memory, delete this file and retry.");
                    @fclose($fh);
                }
                @unlink($filenamedir);
                return ai_is_ie(8);
            }
        }

        function ai_plugin_action_links($links, $file)
        {
            $plugin_file = basename(__FILE__);
            $file = basename($file);
            if ($file == $plugin_file) {
                $settings_link = '<a href="options-general.php?page=' . $plugin_file . '">' . __('Settings', 'advanced-iframe') . '</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }
            
        function aiCheckContent($content) {           
              // content contains [advanced AND role is not enough....          
              if (!$this->hasValidRole() && strpos($content, '[advanced_iframe') !== false) {    
                  set_transient("ai_no_rights_post_errors", __('This page/post contains an advanced iframe shortcode but you don\'t have the needed role to use this plugin. Please contact your system administrator to get the needed role.', 'advanced-iframe'), 20);
                  wp_redirect( admin_url());  
              }             
        return $content;
        }
        
        function aiShowValidationErrors() {
            if ( $error = get_transient( "ai_no_rights_post_errors" ) ) { ?>
                <div class="error">
                    <p><?php echo $error ?></p>
                </div><?php
                 delete_transient("ai_no_rights_post_errors");
            }        
            if ( $error = get_transient( "ai_save_post_errors" ) ) { ?>
                <div class="error">
                    <?php echo $error; ?>
                </div><?php
                delete_transient("ai_save_post_errors");
            }
        }
        
        function hasValidRole () {
              $options = get_option('advancediFrameAdminOptions');
              $config_role = $options['roles'];
              if  ($config_role == 'none' || $config_role == '') {
                  return true;
              }
             
              global $wp_roles; 
              if ( ! isset( $wp_roles ) ) {
                  return true;
              }
              $roles_by_index = array_flip(array_keys($wp_roles->get_names()));
             
              // get the user role              
              $current_user = wp_get_current_user();
              $user_roles = $current_user->roles;
              $user_role = array_shift( $user_roles );

              $user_index = $roles_by_index[$user_role];
              $config_index = $roles_by_index[$config_role];          
              return  $user_index <= $config_index;
        }
        
    function ai_show_id_only() {
		global $post;
    $options = get_option('advancediFrameAdminOptions');
		if ($options['enable_content_filter'] == 'true' && isset( $_GET['ai-show-id-only'] ) && isset( $_GET['ai-server-side'] ) && is_singular() ) {
			the_post();
			echo '<html><head><meta name="robots" value="noindex,nofollow" />';
				wp_print_scripts();
				wp_head();
				wp_print_styles();
				if ( function_exists( 'post_class' ) ) {
					echo '</head><body ';
					post_class();
					echo '>';
				} else {
					echo '</head><body>';
				}
			if ( isset( $_GET['plain'] ) ) {
				$html = get_the_content();
			} else {
				$html = $this->my_the_content();
			}
      $html = '<div>' . $html . '</div>';
      $dom = new DOMDocument();
      $dom->loadHTML($html);
      $xpath = new DOMXPath($dom);
      $xpath_resultset = $xpath->query('//div[@id="'.$_GET['ai-show-id-only'].'"]');
      if($xpath_resultset->length > 0) {
        echo $dom->saveHTML($xpath_resultset->item(0));
      } else {
       echo "No content found for this id. Please check if your id is correct and the part you want to show is in the content area.";
      }
      wp_footer();
      $overflow = isset( $_GET['ai-show-overflow'] ) ? '': ' overflow: hidden;';
			echo '<style>';
      echo 'html, body { margin: 0px !important; padding: 0px !important;'.$overflow.' }';
      echo '</style>';
      echo '</body></html>';
			die();
		}
	}
   /**
     * Filters the post content.
     *
     * @since 0.71
     *
     * @param string $content Content of the current post.
     */
  function my_the_content( $more_link_text = null, $strip_teaser = false) {
    $content = get_the_content( $more_link_text, $strip_teaser );
    $content = apply_filters( 'the_content', $content );
    $content = str_replace( ']]>', ']]&gt;', $content );
    return $content;
  }
function ai_save_post( $content ) {
  $options = get_option('advancediFrameAdminOptions');
  $check_save = $options['check_iframes_when_save'];
  if ($check_save == 'false') {
      return $content;
  }
  include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-functions.php';
  $result_array = array();
  $result_array['links'] = array();
  $result_array['overall_status'] = 'green';
  $all_links = array();
  $result_array = evaluatePageLinks($result_array, stripcslashes ($content), 'link', 'title', false, $all_links, 'none');
  $error = '';
   foreach( $result_array['links'] as $iframes) {
       foreach( $iframes['links'] as $link => $result) {
           if (($result['status'] == 'orange' && $check_save != 'error') || $result['status'] == 'red' ) {
               $error .= '<p><strong>Check this iframe url</strong>: ' . $result['url_orig'] . '<br>' . ai_print_result($result) . '</p>';
           }
      }
   }
   if (!empty($error)) {
       set_transient("ai_save_post_errors", $error, 20);
   }
   return $content;
}

        /**
         *  Intercepts the Ajax resize events in iframes.
         */
        function interceptAjaxResize($iframe_id, $resize_width, $timeout, $resize_on_ajax_jquery,
                                     $click_timeout, $resize_on_click_elements, $resize_min_height)
        {
            $debug = false;
            $val = '';
            if ($timeout != '' || $click_timeout != '') {
                $val .= '<script type="text/javascript">';
                $val .= 'function local_resize_' . $iframe_id . '(timeout) {
            if (timeout != 0) {
               setTimeout(function() { aiResizeIframe(ifrm_' . $iframe_id . ', "' . $resize_width . '","' . $resize_min_height . '")},timeout);
            } else {
               aiResizeIframe(ifrm_' . $iframe_id . ', "' . $resize_width . '","' . $resize_min_height . '");
            }
          }';
                $val .= '</script>';

                if ($resize_on_ajax_jquery == 'true' || $click_timeout != '') {
                    $val .= '<script type="text/javascript">
                function ai_jquery_ajax_resize_' . $iframe_id . '() {
                    jQuery("#' . $iframe_id . '").bind("load",function(){
                    doc = this.contentWindow.document;';
                    if ($timeout != '' && $resize_on_ajax_jquery == 'true') {
                        $val .= 'var instance = this.contentWindow.jQuery;';
                        $val .= 'instance(doc).ajaxComplete(function(){';
                        if ($debug) {
                            $val .= 'alert("AJAX request completed.");';
                        }
                        $val .= 'local_resize_' . $iframe_id . '(' . $timeout . ');';
                        $val .= '});';
                    }
                    if ($click_timeout != '' && $resize_on_click_elements != '') {
                        $val .= 'doc.addEventListener("click", function(evt) { ';
                        $val .= '  if (checkIfValidTarget(evt,"' . $resize_on_click_elements . '")) {';
                        if ($debug) {
                            $val .= 'alert("Click event intercepted.");';
                        }
                        $val .= '   local_resize_' . $iframe_id . '(' . $click_timeout . ');';
                        $val .= '  }';
                        $val .= '}, true);';
                    }
                    $val .= '});
            }';
                    $val .= 'ai_jquery_ajax_resize_' . $iframe_id . '();';

                    $val .= '</script>';
                }
                if ($resize_on_ajax_jquery == 'false' && $timeout != '') {
                    $val .= '<script type="text/javascript">';
                    $val .= '

              var send_' . $iframe_id . ' = ifrm_' . $iframe_id . '.contentWindow.XMLHttpRequest.prototype.send,
                  onReadyStateChange_' . $iframe_id . ';

              function sendReplacement_' . $iframe_id . '(data) {
                  if(this.onreadystatechange) {
                      this._onreadystatechange_' . $iframe_id . ' = this.onreadystatechange;
                  }
                  this.onreadystatechange = onReadyStateChangeReplacement_' . $iframe_id . ';
                  return send_' . $iframe_id . '.apply(this, arguments);
              }

              function onReadyStateChangeReplacement_' . $iframe_id . '() {
                  if(this.readyState == 4 ) {
                      var retValue;
                      if (this._onreadystatechange_' . $iframe_id . ') {
                          retValue = this._onreadystatechange_' . $iframe_id . '.apply(this, arguments);
                      }';
                    $val .= 'local_resize_' . $iframe_id . '(' . $timeout . ');';
                    $val .= 'return retValue;
                  }
              }';
                    $val .= '  ifrm_' . $iframe_id . '.contentWindow.XMLHttpRequest.prototype.send = sendReplacement_' . $iframe_id . ';';
                    $val .= '</script>';
                }
            }
            return $val;
        }
    }
}

if (!isset($aip_standalone)) {
    //  setup new instance of plugin if not standalone
    if (class_exists("advancediFrame")) {
        $cons_advancediFrame = new advancediFrame();
    }
}
//Actions and Filters
if (isset($cons_advancediFrame)) {
    //Initialize the admin panel
    if (!function_exists('advancediFrame_ap')) {
        function advancediFrame_ap()
        {
            global $cons_advancediFrame;
            if (!isset($cons_advancediFrame)) {
                return;
            }
            $aiOptions = $cons_advancediFrame->getAiAdminOptions();

            $pro = (file_exists(dirname(__FILE__) . "/includes/class-cw-envato-api.php")) ? " Pro" : "";

            $cap = ai_map_role_to_capability($aiOptions['roles']);
            if (function_exists('add_options_page')) {
                add_options_page('Advanced iFrame' . $pro, 'Advanced iFrame' . $pro, $cap,
                    basename(__FILE__), array($cons_advancediFrame, 'printAdminPage'));
            }
            if ($aiOptions['show_menu_link'] == "true") {
                add_menu_page('Advanced iFrame' . $pro, 'Advanced iFrame' . $pro, $cap,
                    basename(__FILE__), array($cons_advancediFrame, 'printAdminPage'), AIP_IMGURL.'/logo_24x24.png'  );
            }
            if (!empty($aiOptions['alternative_shortcode'])) {
                // setup shortcode alternative style  
                add_shortcode($aiOptions['alternative_shortcode'], array($cons_advancediFrame, 'do_iframe_script'), 1);
            }

            add_action('admin_print_footer_scripts', array($cons_advancediFrame, 'addAiButtonJs'), 199);
            add_action('media_buttons', array($cons_advancediFrame, 'addAiButton'), 11);

        }
    }
    add_action('admin_menu', 'advancediFrame_ap',11); //admin page
    add_action('init', array($cons_advancediFrame, 'loadLanguage'), 1); // add languages
    add_action('admin_enqueue_scripts', array($cons_advancediFrame, 'addAdminHeaderCode'), 99); // load css
    add_action('wp_enqueue_scripts', array($cons_advancediFrame, 'addWpHeaderCode'), 98); // load js
    add_action('wp_footer', array($cons_advancediFrame, 'add_script_footer'), 2);
    add_action('admin_notices', array($cons_advancediFrame, 'aiShowValidationErrors'), 3);
    add_action('ai_check_iframes_event', array($cons_advancediFrame, 'aiCheckIframes'));
    add_action('wp', array( $cons_advancediFrame, 'ai_show_id_only' ));
    
    add_shortcode('advanced_iframe', array($cons_advancediFrame, 'do_iframe_script'), 1); // setup shortcode
    add_shortcode('advanced-iframe', array($cons_advancediFrame, 'do_iframe_script'), 1); // setup shortcode alternative style   
    add_shortcode('ai_advanced_js_local', array($cons_advancediFrame, 'addAiExternalLocal'), 1); // setup shortcode for adding ai_external only
    register_activation_hook(__FILE__, array($cons_advancediFrame, 'activate'));
    register_deactivation_hook(__FILE__, array($cons_advancediFrame, 'deactivate'));

    add_filter( 'content_edit_pre', array($cons_advancediFrame, 'aiCheckContent'), 1); 
    add_filter('widget_text', 'shortcode_unautop');
    add_filter('widget_text', 'do_shortcode');
    add_filter('plugin_action_links', array($cons_advancediFrame, 'ai_plugin_action_links'), 10, 2);
    // content_save_pre
    add_filter( 'content_save_pre', array($cons_advancediFrame, 'ai_save_post'), 10, 1 );
}

// ==============================================
//	Setup for widget + remove update functionality
// ==============================================
function ai_remove_update($value)
{
    if (isset($value) && is_object($value) && isset($value->response[plugin_basename(__FILE__)])) {
        unset($value->response[plugin_basename(__FILE__)]);
    }
    return $value;
}

function advanced_iframe_widget_init()
{
    register_widget('AdvancedIframe_Widget');
}

if (!isset($aip_standalone) && file_exists(dirname(__FILE__) . "/includes/advanced-iframe-widget.php")) {
    require_once('includes/advanced-iframe-widget.php');
    add_action('widgets_init', 'advanced_iframe_widget_init');
    add_filter('site_transient_update_plugins', 'ai_remove_update');
}

// ==============================================
//	Get Plugin Version
// ==============================================
function advanced_iframe_plugin_version()
{
    if (!function_exists('get_plugins'))
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    $plugin_folder = get_plugins('/' . plugin_basename(dirname(__FILE__)));
    $plugin_file = basename((__FILE__));
    return $plugin_folder[$plugin_file]['Version'];
}

// ==============================================
//	Add Links in Plugins Table
// ==============================================
function advanced_iframe_plugin_meta_free($links, $file)
{
    if (strpos($file, '/advanced-iframe.php') !== false) {
        $iconstyle = 'style="-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;"';
        $reviewlink = 'https://wordpress.org/support/view/plugin-reviews/advanced-iframe?rate=5#postform';
        $links = array_merge($links, array('<a href="http://codecanyon.net/item/advanced-iframe-pro/5344999?ref=mdempfle">Advanced iFrame Pro</a>',
            '<a href="' . $reviewlink . '"><span class="dashicons dashicons-star-filled"' . $iconstyle . 'title="Give a 5 Star Review"></span></a>'
        ));
    }
    return $links;
}

function advanced_iframe_plugin_meta_pro($links, $file)
{
    if (strpos($file, '/advanced-iframe.php') !== false) {
        $iconstyle = 'style="-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;"';
        $links = array();
        $links = array_merge($links,
            array('Version ' . advanced_iframe_plugin_version(),
                'By <a href="http://www.tinywebgallery.com">Michael Dempfle</a>',
                '<a href="http://codecanyon.net/item/advanced-iframe-pro/5344999?ref=mdempfle">Code canyon - Advanced iFrame Pro</a>',
                '<a href="http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo">Demos</a>'
            ));
    }
    return $links;
}

/**
 * 
 *
 */
function ai_map_role_to_capability($role) {
         $role_map = array('administrator' => 'manage_options', 'editor' => 'delete_others_pages', 
         'author' => 'delete_published_posts', 'contributor' => 'delete_posts','subscriber' => 'read');
         return (isset ($role_map[$role])) ? $role_map[$role] : 'manage_options';         
}

if (!isset($aip_standalone)) {
    if (file_exists(dirname(__FILE__) . "/includes/advanced-iframe-widget.php")) {
        add_filter('plugin_row_meta', 'advanced_iframe_plugin_meta_pro', 10, 2);
    } else {
        add_filter('plugin_row_meta', 'advanced_iframe_plugin_meta_free', 10, 2);
    }
}

?>