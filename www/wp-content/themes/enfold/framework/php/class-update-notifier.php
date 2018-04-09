<?php
/**
 * @package   Avia Update Notifier Class
 * @version   1.0.1
 * @author    Peter Schoenmann | InoPlugs

 */


if ( ! class_exists( 'avia_update_notifier' ) )
{
    class avia_update_notifier
    {
        public $xmlurl = '';
        public $check_for_update_interval = '';
        public $remind_me_later_interval = '';
        public $userid = '';
        public $latestversiondata = '';
        public $latestversion = '';
        public $themeversion = '';

        public function __construct($xmlurl = NULL, $deactivate = FALSE, $check_for_update_interval = 604800, $remind_me_later_interval = 86400)
        {
            if( empty($xmlurl) || !empty($deactivate) ) return;
            if(!is_admin()) return;

            $this->check_for_update_interval = (is_numeric($check_for_update_interval)) ? $check_for_update_interval : 604800; //week
            $this->remind_me_later_interval = (is_numeric($remind_me_later_interval)) ? $remind_me_later_interval : 86400;	//day
            $this->xmlurl = $xmlurl;

            add_action( 'admin_init', array($this,'avia_check_xml') );
            add_action( 'admin_init', array($this,'avia_ignore_update_message') );
        }



        public function __destruct()
        {
            unset($this->check_for_update_interval);
            unset($this->remind_me_later_interval);
            unset($this->xmlurl);
            unset($this->userid);
            unset($this->latestversiondata);
            unset($this->themeversion);
            unset($this->latestversion);
        }



        public function avia_check_xml()
        {
            //get user ID to store user meta data
            $this->set_user_id();

            //let's check if we already downloaded the xml lately - if yes return because we don't need to download the update xml on every page load
            $latestversiondata = get_transient('avia_theme_latest_version');
            $updatecheckfailed = get_transient('avia_update_check_failed');

            if( empty($latestversiondata) && empty($updatecheckfailed) )
            {

                $xml = @simplexml_load_file($this->xmlurl);

                if($xml === false)
                {
                    $failed_to_load_xml = true; //error - we couldn't load the file
                }
                else
                {
                    //convert SimpleXML object back to xml to store the xml data into the database
                    $latestversiondata = $xml->asXML();
                }

                //set transient option - otherwise we'd check for an update everytime the user reloads the page
                if( !empty($latestversiondata) )
                {
                    set_transient( 'avia_theme_latest_version', $latestversiondata, $this->check_for_update_interval );
                }
                else if($failed_to_load_xml)
                {
                    set_transient( 'avia_update_check_failed', $failed_to_load_xml, $this->check_for_update_interval );
                }

            }
            else if( !empty($updatecheckfailed) )
            {
                return; //stop here because we have no data (latest theme version number, etc.)
            }

            // get themedata version wp 3.4+
            if( function_exists('wp_get_theme') )
            {
               $theme = wp_get_theme();
               if(is_child_theme())  $theme = wp_get_theme( $theme->get('Template') );
               $this->themeversion = $theme->get('Version');
            }
            else
            {   //wp older than 3.4
                $theme = get_theme_data( AVIA_BASE . 'style.css' );
                $this->themeversion = $theme['Version'];
            }

            //retrieve xml string from database and convert it back into a SimpleXML object
            $this->latestversiondata = simplexml_load_string($latestversiondata);
			
			if(!empty($this->latestversiondata) && !empty($this->latestversiondata->LatestVersion))
            $this->latestversion = (string)$this->latestversiondata->LatestVersion->version;


            if( !empty($this->themeversion) && !empty($this->latestversion) )
            {
                //compare versions - version_compare() returns -1 if the first version is lower than the second, 0 if they are equal, and 1 if the second is lower.
                if(version_compare($this->themeversion, $this->latestversion) < 0)
                {
                    add_action('admin_notices', array($this,'avia_update_notice') );
                }
                else
                {
                    //delete user meta otherwise user won't see a notice for the next update
                    delete_user_meta($this->userid, 'avia_ignore_update_message');
                }
            }
            else
            {
                return; //something went wrong - close the case
            }

        }


        public function set_user_id()
        {
            $current_user = wp_get_current_user();
            $this->userid = $current_user->ID;
        }



        public function avia_update_notice()
        {
            /* Check that the user hasn't already clicked to ignore the message */
            $avia_ignore_update_message_temporarily = get_transient('avia_ignore_update_message_temporarily');

            if ( !get_user_meta($this->userid, 'avia_ignore_update_message') && empty($avia_ignore_update_message_temporarily) && current_user_can('manage_options') )
            {
                //check for themeforest url
				$themeforesturl = (property_exists($this->latestversiondata->LatestVersion, 'themeforesturl')) ? $this->latestversiondata->LatestVersion->themeforesturl : '';
				if(empty($themeforesturl)) $themeforesturl = 'http://themeforest.net';

                //check for themeforest url
                $changelogurl = (property_exists($this->latestversiondata->LatestVersion, 'changelogurl')) ? $this->latestversiondata->LatestVersion->changelogurl : '';

                $saparator = ' | ';
                echo '<div class="updated">';
                    echo '<p>';

                        echo '<strong>';
                            echo __('A new update for your theme', 'avia_framework') . ' ' . THEMENAME . ' ' . __('is available!', 'avia_framework');
                            echo ' ' . __('The latest version is', 'avia_framework') . ' ' . $this->latestversion . __('.', 'avia_framework');
                            echo ' ' . __('You\'re using version', 'avia_framework') . ' ' . $this->themeversion . __('.', 'avia_framework');
                        echo '</strong>';
                        echo '<br/>';

                        echo '<a target="_blank" href="'.$themeforesturl.'" title="' . __('Download Update From Themeforest.net', 'avia_framework') . '">'. __('Download Update From Themeforest.net', 'avia_framework') . '</a>';
                        echo $saparator;

                        if(!empty($changelogurl))
                        {
                            echo '<a target="_blank" href="'.$changelogurl.'" title="' . __('View Changelog on http://kriesi.at', 'avia_framework') . '">'. __('View Changelog', 'avia_framework') . '</a>';
                            echo $saparator;
                        }

                        echo '<a href="' . add_query_arg(array('avia_admin_notice_action'=>'avia_ignore_update_message_temporarily')) . '" title="' . __('Remind Me Later', 'avia_framework') . '">'. __('Remind Me Later', 'avia_framework') . '</a>';
                        echo $saparator;
                        echo '<a href="' . add_query_arg(array('avia_admin_notice_action'=>'avia_ignore_update_message')) . '" title="' . __('Ignore the update notice this time', 'avia_framework') . '">'. __('Ignore This Update Notice', 'avia_framework') . '</a>';

                    echo '</p>';
                echo "</div>";
            }
        }



        public function avia_ignore_update_message()
        {
            /*
            If user clicks to ignore the notice, add that to their user meta - it will be deleted if the latest version number is smaller (or equals) the theme version number because
            the update notice should be displayed for the next version again.
            */
            if ( !empty($_GET['avia_admin_notice_action']) )
            {
                switch ($_GET['avia_admin_notice_action'])
                {
                    /* Ignore update notice for (current) latest version */
                    case 'avia_ignore_update_message':
                        add_user_meta($this->userid, 'avia_ignore_update_message', 'true', true);
                        break;

                    /* Temporarily ignore the update message */
                    case 'avia_ignore_update_message_temporarily':
                        set_transient( 'avia_ignore_update_message_temporarily', 'true', $this->remind_me_later_interval );
                        break;
                }
            }
        }

    }
}