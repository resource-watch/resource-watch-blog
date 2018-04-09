<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

$support_icon = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="38px" height="36px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><path fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" d="M11,48C5.477,48,1,43.523,1,38s4.477-10,10-10h2v20 H11z"></path><path fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" d="M53,28c5.523,0,10,4.477,10,10s-4.477,10-10,10h-2 V28H53z"></path><path fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" d="M13,31v-9c0,0,0-16,19-16s19,16,19,16v6"></path><polyline fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" points="51,48 51,53 36,59 28,59 28,55 36,55 36,58 "></polyline></svg>';

$update_icon = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="38px" height="38px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><path fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" d="M24,32c0,4.418,3.582,9,8,9h4"/>
<path fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" d="M41,50h14c4.565,0,8-3.582,8-8s-3.435-8-8-8 c0-11.046-9.52-20-20.934-20C23.966,14,14.8,20.732,13,30c0,0-0.831,0-1.667,0C5.626,30,1,34.477,1,40s4.293,10,10,10H41"/><polyline fill="none" stroke="#777777" stroke-width="2" stroke-linejoin="bevel" stroke-miterlimit="10" points="33,45 36,41 33,37 "/><path fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" d="M42,32c0-4.418-3.582-9-8-9h-4"/><polyline fill="none" stroke="#777777" stroke-width="2" stroke-linejoin="bevel" stroke-miterlimit="10" points="33,19 30,23 33,27 "/>
</svg>';

$heart_icon = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="38px" height="34px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><path fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" d="M1,21c0,20,31,38,31,38s31-18,31-38 c0-8.285-6-16-15-16c-8.285,0-16,5.715-16,14c0-8.285-7.715-14-16-14C7,5,1,12.715,1,21z"/></svg>';

$card_icon ='<svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="38px" height="38px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><rect x="1" y="11" fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" width="62" height="42"/>
<line fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="1" y1="17" x2="63" y2="17"/>
<line fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="1" y1="25" x2="63" y2="25"/>
<line fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="6" y1="47" x2="10" y2="47"/>
<line fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="12" y1="47" x2="41" y2="47"/>
</svg>';

$ticket_icon = '<svg style="transform: scaleX(-1)" version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="38px" height="38px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><g><polygon fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" points="25,1 63,39 39,63 1,25 1,1"/><circle fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" cx="17" cy="17" r="6"/></g></svg>';
	 
$info_icon = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="38px" height="38px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><path fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" d="M53.92,10.081c12.107,12.105,12.107,31.732,0,43.838 c-12.106,12.108-31.734,12.108-43.84,0c-12.107-12.105-12.107-31.732,0-43.838C22.186-2.027,41.813-2.027,53.92,10.081z"/><line stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="32" y1="47" x2="32" y2="25"/><line stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="32" y1="21" x2="32" y2="17"/></svg>';

$doc_icon = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="38px" height="34px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><g><polygon fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" points="23,1 55,1 55,63 9,63 9,15 	"/><polyline fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" points="9,15 23,15 23,1"/><line fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="32" y1="14" x2="46" y2="14"/><line fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="18" y1="24" x2="46" y2="24"/><line fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="18" y1="34" x2="46" y2="34"/><line fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="18" y1="44" x2="46" y2="44"/><line fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="18" y1="54" x2="46" y2="54"/></g></svg>';

$info_icon = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="38px" height="38px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><polyline fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" points="5,41 11,1 53,1 59,41 "/><path fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" d="M21,41c0,6.075,4.925,11,11,11s11-4.925,11-11h16v22 H5V41H21z"/><polyline fill="none" stroke="#777777" stroke-width="2" stroke-linejoin="bevel" stroke-miterlimit="10" points="23,22 30,29 43,16 "/></svg>';

$info_icon2 = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="38px" height="38px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><polyline fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" points="5,41 11,1 53,1 59,41 "/><path fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" d="M21,41c0,6.075,4.925,11,11,11s11-4.925,11-11h16v22 H5V41H21z"/><polyline fill="none" stroke="#777777" stroke-width="2" stroke-linejoin="bevel" stroke-miterlimit="10" points="40,25 32,33 24,25 "/><g><line fill="none" stroke="#777777" stroke-width="2" stroke-miterlimit="10" x1="32" y1="33" x2="32" y2="13"/></g></svg>';

$pad_lock = '<svg class="tg-pad-lock-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" x="0px" y="0px" width="38px" height="38px" viewBox="0 0 64 64" xml:space="preserve"><path fill="#F19186" d="M14,58h34c2.21,0,4-1.79,4-4V23c0-0.55-0.45-1-1-1h-5v-5c0-8.27-6.73-15-15-15S16,8.73,16,17v5h-5 c-0.55,0-1,0.45-1,1v31C10,56.21,11.79,58,14,58z M18,17c0-7.17,5.83-13,13-13s13,5.83,13,13v5H18V17z M12,24h38v30c0,1.1-0.9,2-2,2 H14c-1.1,0-2-0.9-2-2V24z"/><path fill="#F19186" d="M30,39.82V47c0,0.55,0.45,1,1,1s1-0.45,1-1v-7.18c1.16-0.41,2-1.51,2-2.82c0-1.65-1.35-3-3-3s-3,1.35-3,3 C28,38.3,28.84,39.4,30,39.82z M31,36c0.55,0,1,0.45,1,1s-0.45,1-1,1s-1-0.45-1-1S30.45,36,31,36z"/></svg>';

$pad_open = '<svg class="tg-pad-open-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" x="0px" y="0px" width="38px" height="38px" viewBox="0 0 64 64" xml:space="preserve"><path fill="#4ECDC4" d="M30,39.82V47c0,0.55,0.45,1,1,1s1-0.45,1-1v-7.18c1.16-0.41,2-1.51,2-2.82c0-1.65-1.35-3-3-3s-3,1.35-3,3 C28,38.3,28.84,39.4,30,39.82z M31,36c0.55,0,1,0.45,1,1s-0.45,1-1,1s-1-0.45-1-1S30.45,36,31,36z"/><path fill="#4ECDC4" d="M14,58h34c2.21,0,4-1.79,4-4V23c0-0.55-0.45-1-1-1H18v-5c0-7.17,5.83-13,13-13c6.43,0,11.96,4.79,12.87,11.14 c0.08,0.55,0.58,0.92,1.13,0.85c0.55-0.08,0.93-0.58,0.85-1.13C44.8,7.53,38.42,2,31,2c-8.27,0-15,6.73-15,15v5h-5 c-0.55,0-1,0.45-1,1v31C10,56.21,11.79,58,14,58z M12,24h38v30c0,1.1-0.9,2-2,2H14c-1.1,0-2-0.9-2-2V24z"/></svg>';

$syncing = '<svg class="tg-syncing-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" x="0px" y="0px" width="38px" height="38px" viewBox="0 0 64 64" xml:space="preserve"><path fill="#F19186" d="M10.29,32.71c0.09,0.09,0.2,0.17,0.33,0.22C10.74,32.97,10.87,33,11,33s0.26-0.03,0.38-0.08 c0.12-0.05,0.23-0.12,0.33-0.22l6-6c0.39-0.39,0.39-1.02,0-1.41s-1.02-0.39-1.41,0l-4.1,4.1C13.47,19.6,21.86,12,32,12 c8.78,0,16.67,5.87,19.17,14.28c0.16,0.53,0.71,0.83,1.24,0.67c0.53-0.16,0.83-0.71,0.67-1.24C50.33,16.46,41.66,10,32,10 c-11.36,0-20.73,8.65-21.88,19.71l-4.41-4.41c-0.39-0.39-1.02-0.39-1.41,0s-0.39,1.02,0,1.41L10.29,32.71z"/><path fill="#F19186" d="M53.71,31.29c-0.09-0.09-0.2-0.17-0.33-0.22c-0.24-0.1-0.52-0.1-0.76,0c-0.12,0.05-0.23,0.12-0.33,0.22l-6,6 c-0.39,0.39-0.39,1.02,0,1.41s1.02,0.39,1.41,0l4.1-4.1C50.53,44.4,42.14,52,32,52c-8.77,0-16.65-5.86-19.16-14.25 c-0.16-0.53-0.71-0.83-1.25-0.67c-0.53,0.16-0.83,0.72-0.67,1.25C13.69,47.56,22.36,54,32,54c11.36,0,20.73-8.65,21.88-19.71 l4.41,4.41C58.49,38.9,58.74,39,59,39s0.51-0.1,0.71-0.29c0.39-0.39,0.39-1.02,0-1.41L53.71,31.29z"/></svg>';

$check_box = '<svg class="tg-checkbox-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" x="0px" y="0px" width="38px" height="38px" viewBox="0 0 64 64" xml:space="preserve"><path fill="#4ECDC4" d="M4,55c0,0.55,0.45,1,1,1h44c0.55,0,1-0.45,1-1V28c0-0.55-0.45-1-1-1s-1,0.45-1,1v26H6V12h42v5 c0,0.55,0.45,1,1,1s1-0.45,1-1v-6c0-0.55-0.45-1-1-1H5c-0.55,0-1,0.45-1,1V55z"/><path fill="#4ECDC4" d="M59.71,12.29c-0.39-0.39-1.02-0.39-1.41,0L27,43.59l-9.29-9.29c-0.39-0.39-1.02-0.39-1.41,0 c-0.39,0.39-0.39,1.02,0,1.41l10,10C26.49,45.9,26.74,46,27,46s0.51-0.1,0.71-0.29l32-32C60.1,13.32,60.1,12.68,59.71,12.29z"/></svg>';

$warning = '<svg class="tg-warning-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/" x="0px" y="0px" width="38px" height="38px" viewBox="0 0 64 64" xml:space="preserve"><path fill="#F19186" d="M31,48c1.65,0,3-1.35,3-3s-1.35-3-3-3s-3,1.35-3,3S29.35,48,31,48z M31,44c0.55,0,1,0.45,1,1s-0.45,1-1,1 s-1-0.45-1-1S30.45,44,31,44z"/><path fill="#F19186" d="M31,40c1.65,0,3-1.35,3-3V21c0-1.65-1.35-3-3-3s-3,1.35-3,3v16C28,38.65,29.35,40,31,40z M30,21 c0-0.55,0.45-1,1-1s1,0.45,1,1v16c0,0.55-0.45,1-1,1s-1-0.45-1-1V21z"/><path fill="#F19186" d="M9,56h44c3.86,0,7-3.14,7-7c0-1.42-0.65-2.94-0.73-3.11c-0.02-0.05-0.05-0.09-0.08-0.14L36.75,11.02 c-0.02-0.03-0.04-0.07-0.06-0.1C35.38,9.09,33.25,8,31,8s-4.38,1.09-5.69,2.93c-0.02,0.03-0.04,0.06-0.06,0.1L2.8,45.76 c-0.03,0.04-0.05,0.09-0.08,0.14C2.65,46.06,2,47.58,2,49C2,52.86,5.14,56,9,56z M4.53,46.77l22.43-34.72c0,0,0-0.01,0.01-0.01 C27.91,10.76,29.41,10,31,10s3.09,0.76,4.03,2.04c0,0,0,0.01,0.01,0.01l22.43,34.72C57.64,47.18,58,48.2,58,49c0,2.76-2.24,5-5,5H9 c-2.76,0-5-2.24-5-5C4,48.2,4.36,47.18,4.53,46.77z"/></svg>
';

$builder_icon = '<svg class="tg-builder-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="38px" height="36px" viewBox="0 0 48 48"><g transform="translate(0.5, 0.5)"><line data-cap="butt" data-color="color-2" fill="none" stroke="#777777" stroke-width="1" stroke-miterlimit="10" x1="8" y1="16" x2="16" y2="8" stroke-linejoin="miter" stroke-linecap="butt"></line><line data-cap="butt" data-color="color-2" fill="none" stroke="#777777" stroke-width="1" stroke-miterlimit="10" x1="42" y1="34" x2="34" y2="42" stroke-linejoin="miter" stroke-linecap="butt"></line><polyline data-color="color-2" fill="none" stroke="#777777" stroke-width="1.5" stroke-linecap="square" stroke-miterlimit="10" points="25,33 34,42 44,44 42,34 33,25 " stroke-linejoin="miter"></polyline><polyline data-color="color-2" fill="none" stroke="#777777" stroke-width="1.5" stroke-linecap="square" stroke-miterlimit="10" points="23,15 12,4 4,12 15,23 " stroke-linejoin="miter"></polyline><polygon fill="none" stroke="#777777" stroke-width="1.5" stroke-linecap="square" stroke-miterlimit="10" points="14,44 4,34 34,4 34,4 44,14 " stroke-linejoin="miter"></polygon><line fill="none" stroke="#777777" stroke-width="1" stroke-linecap="square" stroke-miterlimit="10" x1="22" y1="16" x2="24" y2="18" stroke-linejoin="miter"></line><line fill="none" stroke="#777777" stroke-width="1" stroke-linecap="square" stroke-miterlimit="10" x1="28" y1="10" x2="32" y2="14" stroke-linejoin="miter"></line><line fill="none" stroke="#777777" stroke-width="1" stroke-linecap="square" stroke-miterlimit="10" x1="16" y1="22" x2="20" y2="26" stroke-linejoin="miter"></line><line fill="none" stroke="#777777" stroke-width="1" stroke-linecap="square" stroke-miterlimit="10" x1="10" y1="28" x2="12" y2="30" stroke-linejoin="miter"></line></g></svg>';

$update = '<div class="tg-row">';
	
	$update .= '<div class="tomb-spacer" style="height: 30px"></div>';
	
	$plugin_info      = get_option('the_grid_plugin_info', '');
	$envato_api_token = get_option('the_grid_envato_api_token', '');
	$force_register   = get_option('the_grid_force_registration', '');
	$unregister_panel = apply_filters('tg_grid_unregister', false);
	$current_version  = TG_VERSION;
	$last_version     = (isset($plugin_info['version'])) ? $plugin_info['version'] : __( 'You must be registered to know available version', 'tg-text-domain' );
	$updated_at       = (isset($plugin_info['updated_at'])) ? ' ('.date('m/d/Y',strtotime($plugin_info['updated_at'])).')' : null;
	$license          = (isset($plugin_info['license'])) ? $plugin_info['license'] : null;
	$purchase_code    = (isset($plugin_info['purchase_code'])) ? $plugin_info['purchase_code'] : null;

	$supported_until = (isset($plugin_info['supported_until'])) ? $plugin_info['supported_until'] : null;
	if ($supported_until) {
		$date= strtotime($supported_until);
		$diff  = $date-time();
		$supported_until = floor($diff/(60*60*24));
	}
	
	if (!$unregister_panel || $purchase_code || $force_register) {

		$update .= '<div class="tg-col tg-col-3">';
			$update .= '<div class="tg-container">';
				$update .= '<div class="tg-container-header">';
					$update .= '<div class="tg-container-title">'. __( 'Plugin Activation', 'tg-text-domain' ) .'</div>';
					$update .= ($purchase_code) ? $pad_open : $pad_lock;
				$update .= '</div>';

				if ($purchase_code) {
				
					$update .= '<div class="tg-container-inner tg-container-register">';
						$update .= '<div class="tg-text-icon">';
							$update .= $card_icon;
							$update .= '<div class="tg-text-icon-title">'. __( 'Purchase Code', 'tg-text-domain' ) .' ('.$license.')</div>';
							$update .= '<div class="tg-purchase-code">'.$purchase_code.'</div>';
						$update .= '</div>';
						if ($supported_until > 0) {
							$update .= '<div class="tg-text-icon">';
								$update .= $ticket_icon;
								$update .= '<div class="tg-text-icon-title">'. __( 'Premium Ticket Support', 'tg-text-domain' ) .' ('.$supported_until.' '.__( 'days left', 'tg-text-domain' ) .')</div>';
								$update .= '<div class="tg-text-icon-desc">'. __( 'Direct help from our qualified support team', 'tg-text-domain' ) .'</div>';
								$update .= '<a class="tg-button" target="_blank" href="https://themeoneticket.ticksy.com/">'.__( 'Open a ticket', 'tg-text-domain' ) .'</a>';
							$update .= '</div>';
						} else {
							$update .= '<div class="tg-text-icon">';
								$update .= $ticket_icon;
								$update .= '<div class="tg-text-icon-title">'. __( 'Premium Ticket Support (Expired)', 'tg-text-domain' ) .'</div>';
								$update .= '<div class="tg-text-icon-desc">'. __( 'Direct help from our qualified support team', 'tg-text-domain' ) .'</div>';
								$update .= '<a class="tg-button" target="_blank" href="http://codecanyon.net/item/the-grid-responsive-grid-builder-for-wordpress/13306812">'.__( 'Extend support', 'tg-text-domain' ) .'</a>';
							$update .= '</div>';
						}
						$update .= '<div class="tomb-spacer" style="height: 14px"></div>';
						$update .= '<div><span class="tg-button tg-button-register">'. __( 'Change your personal Token', 'tg-text-domain' ) .'</span></div>';
					$update .= '</div>';
				
				} else {
					
					$update .= '<div class="tg-container-inner tg-container-register">';
						$update .= '<div class="tg-text-icon">';
							$update .= $update_icon;
							$update .= '<div class="tg-text-icon-title">'. __( 'Live Updates', 'tg-text-domain' ) .'</div>';
							$update .= '<div class="tg-text-icon-desc">'. __( 'Fresh versions directly to your admin', 'tg-text-domain' ) .'</div>';
						$update .= '</div>';
						$update .= '<div class="tg-text-icon">';
							$update .= $builder_icon;
							$update .= '<div class="tg-text-icon-title">'. __( 'Skin Builder', 'tg-text-domain' ) .'</div>';
							$update .= '<div class="tg-text-icon-desc">'. __( 'Create your own skins with ease', 'tg-text-domain' ) .'</div>';
						$update .= '</div>';
						$update .= '<div class="tg-text-icon">';
							$update .= $support_icon;
							$update .= '<div class="tg-text-icon-title">'. __( 'Premium Ticket Support', 'tg-text-domain' ) .'</div>';
							$update .= '<div class="tg-text-icon-desc">'. __( 'Direct help from our qualified support team', 'tg-text-domain' ) .'</div>';
						$update .= '</div>';
						$update .= '<div class="tomb-spacer" style="height: 15px"></div>';
						$update .= '<div><span class="tg-button tg-button-register">'. __( 'Register The Grid', 'tg-text-domain' ) .'</span></div>';
					$update .= '</div>';
	
				}
	
				$update .= '<div class="tg-container-inner tg-container-register-token">';
					$update .= '<div class="tg-text-icon-title">'. __( 'Global OAuth Personal Token', 'tg-text-domain' ) .'</div>';
					$update .= '<p>'. __( 'OAuth is a protocol that lets external apps request authorization to private details in a user\'s Envato Market account without entering their password.', 'tg-text-domain' ).'</p>';
					$update .= '<input name="the_grid_envato_api_token" type="text" class="tomb-text" style="width:320px" value="'.$envato_api_token.'" placeholder="'. __( 'Enter your Envato API Personal Token', 'tg-text-domain' ) .'">';
					$update .= '<p><em>'. __( 'You will need to', 'tg-text-domain' ) .' <strong><a target="_blank" href="http://theme-one.com/docs/the-grid/#!/register_plugin">'.  __( 'generate a personal token', 'tg-text-domain' ) .'</a></strong>, '.  __( 'and then insert it above.', 'tg-text-domain' ) .'</em></p>';
					$update .= '<div class="tomb-spacer" style="height: 10px"></div>';
					$update .= '<div><span id="tg-grid-save-envato-api-token" class="tg-button tg-button-register-token">'. __( 'Save Changes', 'tg-text-domain' ) .'</span><div class="spinner"></div><strong></strong></span></div>';
				$update .= '</div>';
				
			$update .= '</div>';
		$update .= '</div>';
	
	}
	
	$registration_msg = (!empty($unregister_panel) && is_bool($unregister_panel) !== true) ? $unregister_panel : __( 'If you have a valid purchase of The Grid, you can register it to get automatic updates. Otherwise updates will come with your theme.', 'tg-text-domain' );
    $registration_msg = (!empty($registration_msg)) ? '<span style="width:65%;display:inline-block">'.$registration_msg.'</span>' : null;
	
	$update .= '<div class="tg-col tg-col-3">';
		$update .= '<div class="tg-container">';
			$update .= '<div class="tg-container-header">';
				$update .= '<div class="tg-container-title">'. __( 'Automatic Updates', 'tg-text-domain' ) .'</div>';
				$update_icon = (version_compare($last_version, $current_version) <=  0 && version_compare( $last_version, '0.0.1', '>=' )) ? $pad_open  : $syncing;
                $update_icon = ($unregister_panel && !$force_register) ? $pad_open : $update_icon;
				$update .= ($purchase_code || ($unregister_panel && !$force_register)) ? $update_icon : $pad_lock;
			$update .= '</div>';
			$update .= '<div class="tg-container-inner tg-container-update">';
				$update .= '<div class="tg-text-icon">';
					$update .= $info_icon;
					$update .= '<div class="tg-text-icon-title">'. __( 'Installed Version', 'tg-text-domain' ) .'</div>';
					$update .= '<div class="tg-text-icon-desc">v'. $current_version .'</div>';
				$update .= '</div>';
				$update .= '<div class="tg-text-icon">';
					$update .= $info_icon2;
					$update .= '<div class="tg-text-icon-title">'. __( 'Last Available Version', 'tg-text-domain' ) .'</div>';
					$version = (version_compare( $last_version, '0.0.1', '>=' )) ? 'v'.$last_version : $last_version;
					$version = (!$unregister_panel || $purchase_code || $force_register) ? $version : $registration_msg;
					$update .= '<div class="tg-text-icon-desc">'. $version .'</div>';
				$update .= '</div>';
				$update .= '<div class="tomb-spacer" style="height: 64px"></div>';
				
				if (!$unregister_panel || $purchase_code || $force_register) {
				
					if (version_compare( $last_version, '0.0.1', '<' ))  {
						$update .= '<div><span class="tg-button tg-button-live-no-update">'. __( 'Register to Access Update', 'tg-text-domain' ) .'</span></div>';
					} else if ((version_compare($last_version, $current_version) >  0) && current_user_can('update_plugins')) {
						
						// plugin slug
						$name = 'The Grid';
						$slug = 'the-grid/the-grid.php';
						// Upgrade link.
						$upgrade_link = add_query_arg( array(
							'action' => 'upgrade-plugin',
							'plugin' => $slug,
						), self_admin_url( 'update.php' ) );
						// update link
						$update .= sprintf(
							'<a class="update-now tg-button tg-button-live-update" href="%1$s" aria-label="%2$s" data-name="%3$s %6$s" data-plugin="%4$s" data-slug="%5$s" data-version="%6$s">%7$s</a>',
							wp_nonce_url( $upgrade_link, 'upgrade-plugin_' . $slug ),
							esc_attr__( 'Update %s now', 'envato-market' ),
							esc_attr( $name ),
							esc_attr( $slug ),
							sanitize_key( dirname( $slug ) ),
							esc_attr( $last_version ),
							esc_html__( 'Update Now', 'envato-market' )
						);
						$update .= '</span><div class="spinner"></div><strong></strong>';
					} else {
						$update .= '<div><span class="tg-button tg-button-live-update" id="tg-check-update">'. __( 'Check for updates', 'tg-text-domain' ) .'</span><div class="spinner"></div><strong></strong></div>';
					}
				
				}
				
			$update .= '</div>';
		$update .= '</div>';
	$update .= '</div>';
	
	$base = new The_Grid_Base();
	
	// icons php info
	$true  = '<i class="tg-php-info-icon dashicons dashicons-yes"></i>';
	$false = '<i class="tg-php-info-icon dashicons dashicons-no"></i>';
	$recommended = '<i class="tg-php-info-icon dashicons dashicons-no tg-recommended-icon"></i>';
	// Wordpress version 
	global $wp_version;
	$wp_version_bool = (version_compare($wp_version,  '4.4.0') >=  0) ? true : false;
	$wp_version_icon = ($wp_version_bool) ? $true : $false;
	// Visual Composer version 
	$vc_version = (defined('WPB_VC_VERSION')) ? WPB_VC_VERSION : null;
	$vc_version_bool = (class_exists('Vc_Manager') && version_compare($vc_version,  '4.7.4') >=  0) ? true : false;
	$vc_version_icon = ($vc_version_bool) ? $true : $false;
	// php memory limit
	$mem_limit = ini_get('memory_limit');
	$mem_limit_bytes = $base->setting_to_bytes($mem_limit);
	$mem_limit_bool  = ((int) $mem_limit_bytes >= 64 * 1024 * 1024)  ? true : false;
	$mem_limit_icon  = ($mem_limit_bool) ? $true : $recommended;
	// php max upload file size
	$upload_max_filesize = ini_get('upload_max_filesize');
	$upload_max_filesize_bytes = $base->setting_to_bytes($upload_max_filesize);
	$upload_max_filesize_bool  = ((int) $upload_max_filesize_bytes >= 64 * 1024 * 1024) ? true : false;
	$upload_max_filesize_icon  = ($upload_max_filesize_bool) ? $true : $recommended;
	// php mmax post size
	$post_max_size = ini_get('post_max_size');
	$post_max_size_bytes = $base->setting_to_bytes($post_max_size);
	$post_max_size_bool  = ((int) $post_max_size_bytes >= 64 * 1024 * 1024) ? true : false;
	$post_max_size_icon  = ($post_max_size_bool) ? $true : $recommended;
	// php version
	$php_version = PHP_VERSION;
	$php_version_bool = (version_compare($php_version,  '5.3.0') >=  0) ? true : false;
	$php_version_icon = ($php_version_bool) ? $true : $false;
	
	if (!$wp_version_bool || !$php_version_bool || (!$vc_version_bool && class_exists('Vc_Manager'))) {
		$system_state = $warning;
	} else if (!$mem_limit_bool || !$upload_max_filesize_bool || !$post_max_size_bool) {
		$system_state = $check_box;
	} else {
		$system_state = $check_box;
	}
	
	$msg_reco = '<span class="tg-info-recommended"> ('.__( 'recommended', 'tg-text-domain' ).')</span>';
	$msg_mand = '<span class="tg-info-needed"> ('.__( 'required', 'tg-text-domain' ).')</span>';
	
	$update .= '<div class="tg-col tg-col-3">';
		$update .= '<div class="tg-container">';
			$update .= '<div class="tg-container-header">';
				$update .= '<div class="tg-container-title">'. __( 'System Requirements', 'tg-text-domain' ) .'</div>';
				$update .= $system_state;
			$update .= '</div>';
			$update .= '<div class="tg-container-inner">';
				$update .= $wp_version_icon.'<div class="tg-php-info">'. __( 'Wordpress Version', 'tg-text-domain' ) .'</div><span class="tg-php-info-value">v'. $wp_version .'</span>';
				$update .= (!$wp_version_bool) ?'<span class="tg-php-info-value-needed">v4.4.X</span>'.$msg_mand : '';
				$update .= '<div class="tomb-spacer" style="height: 7px"></div>';
				$update .= (class_exists('Vc_Manager')) ? $vc_version_icon.'<div class="tg-php-info">'. __( 'Visual Composer Version', 'tg-text-domain' ) .'</div><span class="tg-php-info-value">v'. $vc_version .'</span>' : '';
				$update .= (class_exists('Vc_Manager') && !$vc_version_bool) ? '<span class="tg-php-info-value-needed">v4.7.4</span>'.$msg_mand : '';
				$update .= (class_exists('Vc_Manager')) ? '<div class="tomb-spacer" style="height: 7px"></div>' : '';
				$update .= $mem_limit_icon.'<div class="tg-php-info">'. __( 'Memory Limit', 'tg-text-domain' ) .'</div><span class="tg-php-info-value">'. $mem_limit .'</span>';
				$update .= (!$mem_limit_bool) ?'<span class="tg-php-info-value-recommended">64M</span>'.$msg_reco : '';
				$update .= '<div class="tomb-spacer" style="height: 7px"></div>';
				$update .= $upload_max_filesize_icon.'<div class="tg-php-info">'. __( 'Upload Max. Filesize', 'tg-text-domain' ) .'</div><span class="tg-php-info-value">'. $upload_max_filesize .'</span>';
				$update .= (!$upload_max_filesize_bool) ?'<span class="tg-php-info-value-recommended">64M</span>'.$msg_reco : '';
				$update .= '<div class="tomb-spacer" style="height: 7px"></div>';
				$update .= $post_max_size_icon.'<div class="tg-php-info">'. __( 'Max. Post Size', 'tg-text-domain' ) .'</div><span class="tg-php-info-value">'. $post_max_size .'</span>';
				$update .= (!$post_max_size_bool) ?'<span class="tg-php-info-value-recommended">64M</span>'.$msg_reco : '';
				$update .= '<div class="tomb-spacer" style="height: 7px"></div>';
				$update .= $php_version_icon.'<div class="tg-php-info">'. __( 'PHP version', 'tg-text-domain' ) .'</div><span class="tg-php-info-value">v'. $php_version .'</span>';
				$update .= (!$php_version_bool) ?'<span class="tg-php-info-value-needed">v5.4.0</span>'.$msg_mand : '';
			$update .= '</div>';
		$update .= '</div>';
	$update .= '</div>';

$update .= '</div>';

echo $update;