<?php

function get_custom_login_code() {
    global $wp_query,
           $error;
    $mt_options = mt_get_plugin_options(true);
    $user_connect = false;
    if (!is_array($wp_query->query_vars)) $wp_query->query_vars = array();
    $error_message  = $user_login = $user_pass = $error = '';
    $is_role_check  = true;
    $class_login 	= "user-icon";
    $class_password = "pass-icon";
    $using_cookie   = false;

    if(isset($_POST['is_custom_login'])) {
        $user_login = esc_attr($_POST['log']);
        $user_login = sanitize_user( $user_login );
        $user_pass  = $_POST['pwd'];
        $access = array();
        $access['user_login'] 	 = esc_attr($user_login);
        $access['user_password'] = $user_pass;
        $access['remember']  	 = true;

        $user = null;
        $user = new WP_User(0, $user_login);
        $current_role = current($user->roles);

        if (!empty($mt_options['roles_array'])) {
            foreach (array_keys($mt_options['roles_array']) as $key) {
                if ($key == $current_role) {
                    $is_role_check = false;
                }
            }
        }  else {
            $is_role_check = true;
        }

        if ( !$is_role_check) {
            $error_message  	= __('Permission access denied!', 'maintenance');
            $class_login 		= 'error';
            $class_password 	= 'error';
        } else {

            if ( is_ssl() ) {
                $ssl = true;
            } else {
                $ssl = false;
            }

            $user_connect = wp_signon( $access, $ssl );
            if ( is_wp_error($user_connect) )  {
                if ($user_connect->get_error_code() == 'invalid_username') {
                    $error_message  =  __('Login is incorrect!', 'maintenance');;
                    $class_login 	= 'error';
                    $class_password = 'error';
                } else if ($user_connect->get_error_code() == 'incorrect_password') {
                    $error_message  =  __('Password is incorrect!', 'maintenance');
                    $class_password = 'error';
                } else {
                    $error_message  =  __('Login and password are incorrect!', 'maintenance');
                    $class_login = 'error';
                    $class_password = 'error';
                }
            } else {
                wp_redirect(home_url('/'));
                exit;
            }
        }
    }

    if (!$user_connect) {
        get_headers_503();
    }

    return array($error_message, $class_login, $class_password, $user_login);
}

function add_custom_style() {
    global $wp_styles;

    // all.css loading in index.php inline by 2 steps
    //wp_register_style('_style', 		MAINTENANCE_URI .'load/all.css');
    wp_register_style('maintenance-style', 		MAINTENANCE_URI .'load/css/style.css');
    wp_register_style('maintenance-fonts', 		MAINTENANCE_URI .'load/css/fonts.css');
    $wp_styles->do_items('maintenance-style');
    $wp_styles->do_items('maintenance-fonts');
    get_options_style();
}

function mt_add_google_fonts() {
    global $wp_scripts;
    $mt_options = mt_get_plugin_options(true);
    $font_link = array();

    if (!empty($mt_options['body_font_family'])) {
        $font_link[0] = mt_get_google_font(esc_attr($mt_options['body_font_family']));
        /*Check if chooses subset for fonts*/
        if (!empty($mt_options['body_font_subset'])) {
            $font_subset = esc_attr($mt_options['body_font_subset']);
            $font_link[0] .= ":".$font_subset."";
        }
    }
    if (!empty($mt_options['countdown_font_family'])) {
        $font_link[1] = mt_get_google_font(esc_attr($mt_options['countdown_font_family']));
    }

    if ($font_link) {
        return $font_link;
    }
    return '';

}

function add_custom_scripts() {
    global $wp_scripts;
    $mt_options  = mt_get_plugin_options(true);
    $js_options = array('body_bg' => '', 'gallery_array' => array(), 'blur_intensity' => 0, 'font_link' => '');

    wp_register_script( '_frontend', 		MAINTENANCE_URI  .'load/js/jquery.frontend.js', 'jquery');

    // IE scripts
    wp_register_script( 'jquery_ie', 	$wp_scripts->registered['jquery-core']->src);

    wp_script_add_data('jquery_ie', 'conditional', 'lte IE 10');

    if(class_exists('WPCF7')) {
        wp_register_script( '_cf7scripts',	MAINTENANCE_URI  .'../contact-form-7/includes/js/scripts.js');
        $wpcf7 = array(
            'apiSettings' => array(
                'root' => esc_url_raw( rest_url( 'contact-form-7/v1' ) ),
                'namespace' => 'contact-form-7/v1',
            ),
            'recaptcha' => array(
                'messages' => array(
                    'empty' =>
                        __( 'Please verify that you are not a robot.', 'contact-form-7' ),
                ),
            ),
        );

        if ( defined( 'WP_CACHE' ) && WP_CACHE ) {
            $wpcf7['cached'] = 1;
        }

        if ( wpcf7_support_html5_fallback() ) {
            $wpcf7['jqueryUi'] = 1;
        }

        wp_localize_script( '_cf7scripts', 'wpcf7', $wpcf7 );

//        do_action( 'wpcf7_enqueue_scripts' );
    }



    if (!empty($mt_options['body_bg'])) {
        if (empty($mt_options['gallery_array']['attachment_ids'])) {
            if (!empty($mt_options['body_bg'])) {
                $bg    =  wp_get_attachment_image_src( $mt_options['body_bg'], 'full');
                $js_options['body_bg'] = esc_url($bg[0]);
            }
//				if (!empty($mt_options['is_blur'])) {
//					/*Blur image background*/
//					if (!empty($mt_options['blur_intensity'])) {
//						$js_options['blur_intensity'] = absint($mt_options['blur_intensity']);
//					} else {
//						$js_options['blur_intensity'] = 5;
//					}
//				}
        }
    }

    $js_options['font_link'] = mt_add_google_fonts();
    wp_localize_script( '_frontend', 'maintenanceoptions', $js_options );

    $wp_scripts->do_items('jquery_ie');
    $wp_scripts->do_items('jquery_migrate_ie');
    $wp_scripts->do_items('_placeholder_ie');
    $wp_scripts->do_items('_frontend_ie');


    echo '<!--[if !IE]><!-->';
    $wp_scripts->do_items('jquery');
    echo '<!--<![endif]-->';
//		$wp_scripts->do_items('jquery-migrate');

    $wp_scripts->do_items('_backstretch');
//		$wp_scripts->do_items('_blur');

    $wp_scripts->do_items('_frontend');


    if(class_exists('WPCF7')) {
        $wp_scripts->do_items('_cf7scripts');
    }

}

add_action ('load_custom_scripts', 'add_custom_scripts', 15);
add_action ('load_custom_style', 'add_custom_style', 20);

function get_page_title($error_message = null) {
    $mt_options = mt_get_plugin_options(true);
    $title = $options_title = '';
    if (empty($mt_options['page_title'])) {
        $options_title = wp_title( '|', false);
    } else {
        $options_title = strip_tags(stripslashes($mt_options['page_title']));
    }

    $title =  $options_title;

    echo "<title>$title</title>";
}

function get_options_style() {
    $mt_options = mt_get_plugin_options(true);
    $options_style = '';
    if ( !empty($mt_options['body_bg_color']) ) {
        $options_style .= 'body {background-color: '. esc_attr($mt_options['body_bg_color']) .'}';
        $options_style .= '.preloader {background-color: '. esc_attr($mt_options['body_bg_color']) .'}';
    }


    if (!empty($mt_options['is_blur']) && !empty($mt_options['blur_intensity'])) {
        /*Blur image background*/
        if (!empty($mt_options['blur_intensity'])) {
            $options_style .= '.bg-img img, .bg-img source{';
            $options_style .= '-webkit-filter: blur('.$mt_options['blur_intensity'].'px);';
            $options_style .= '-moz-filter: blur('.$mt_options['blur_intensity'].'px);';
            $options_style .= '-o-filter: blur('.$mt_options['blur_intensity'].'px);';
            $options_style .= '-ms-filter: blur('.$mt_options['blur_intensity'].'px);';
            $options_style .= 'filter:blur('.$mt_options['blur_intensity'].'px);';
            $options_style .= 'filter:progid:DXImageTransform.Microsoft.Blur(PixelRadius='.$mt_options['blur_intensity'].', enabled=\'true\');';
            $options_style .= '}';
        }
    }

    if (!empty($mt_options['body_font_family'])) {
        $options_style .=  'body {font-family: '. esc_attr($mt_options['body_font_family']) .'; }';
    }


    if ( !empty($mt_options['font_color']) ) {
        $font_color = esc_attr($mt_options['font_color']);
        $options_style .= '.site-title, .preloader i, .login-form, .login-form a.lost-pass, .btn-open-login-form, .site-content, .user-content-wrapper, .user-content, footer, .maintenance a{color: '. $font_color .';} ';
//        $options_style .= '.ie7 .login-form input[type="text"], .ie7 .login-form input[type="password"], .ie7 .login-form input[type="submit"]  {color: '. $font_color .'} ';
        $options_style .= 'a.close-user-content, #mailchimp-box form input[type="submit"], .login-form input#submit.button  {border-color:'. $font_color .'} ';
        $options_style .= 'input[type="submit"]:hover{background-color:'. $font_color .'} ';
        $options_style .= 'input:-webkit-autofill, input:-webkit-autofill:focus{-webkit-text-fill-color:'. $font_color .'} ';

//        $options_style .= '.ie7 .company-name {color: '. $font_color .'} ';
    }

    if ( !empty($mt_options['controls_bg_color']) ) {
        $options_style .= "body > .login-form-container{background-color:{$mt_options['controls_bg_color']}CC}";
        $options_style .= ".btn-open-login-form{background-color:{$mt_options['controls_bg_color']}CC}";
        $options_style .= "input:-webkit-autofill, input:-webkit-autofill:focus{-webkit-box-shadow:0 0 0 50px {$mt_options['controls_bg_color']} inset}";
        $options_style .= "input[type='submit']:hover{color:{$mt_options['controls_bg_color']}} ";
        $options_style .= "#custom-subscribe #submit-subscribe:before, body > .main-container:after{background-color:{$mt_options['controls_bg_color']}} ";
    }

    if (!empty($mt_options['custom_css'])) {
        $options_style .= wp_kses_stripslashes($mt_options['custom_css']);
    }

    echo '<style type="text/css">';
    echo $options_style;
    echo '</style>';

    //wp_add_inline_style( '_style', $options_style );
}

function get_logo_box() {
    $mt_options = mt_get_plugin_options(true);
    $logo_w = $logo_h = '';

    if ( !empty($mt_options['logo_width']) ) { $logo_w = $mt_options['logo_width']; }
    if ( !empty($mt_options['logo_height']) ) { $logo_h = $mt_options['logo_height']; }
    if ( !empty($mt_options['logo']) || !empty($mt_options['retina_logo']) ) {
        $logo = wp_get_attachment_image_src( $mt_options['logo'], 'full', true);
        $retina_logo = wp_get_attachment_image_src( $mt_options['retina_logo'], 'full', true);
        if (!empty($logo)) {
            $image_link = esc_url_raw($logo[0]);
        }
        else {
            $image_link = $mt_options['logo'];
        }
        if (!empty($retina_logo)) {
            $image_link_retina = esc_url_raw($retina_logo[0]);
        }
        else {
            $image_link_retina = $mt_options['retina_logo'];
        }


        $image_link = (empty($image_link))?$image_link_retina:$image_link;
        $image_link_retina = (empty($image_link_retina))?$image_link:$image_link_retina;

        ?>
        <div class="logo-box" rel="home">
            <img src="<?php echo $image_link; ?>" srcset="<?php echo $image_link_retina; ?> 2x" width="<?php echo $logo_w; ?>" <?php echo (!empty($logo_h))?'height="'.$logo_h.'"':''; ?> alt="logo">
        </div>
        <?php
    } else {
        echo '<div class="logo-box istext" rel="home"><h1 class="site-title">'. get_bloginfo( 'name' ) .'</h1></div>';
    }

}
add_action ('logo_box', 'get_logo_box', 10);

function get_content_section() {
    $mt_options  = mt_get_plugin_options(true);
    if (!empty($mt_options['body_font_subset'])) {
        $current_subset = esc_attr($mt_options['body_font_subset']);
        $font_weight = (string)((int)$current_subset);
        $font_style = str_replace($font_weight, '', $current_subset);
    }
	
	if (empty($font_style) || $font_style == 'regular') {
		$font_style = 'normal';
	}
	if (empty($font_weight)) {
		$font_weight = 'normal';
	}
    $out_content = null;
    if (!empty($mt_options['heading'])) {
        $out_content .= '<h2 class="heading font-center" style="font-weight:'.$font_weight.';font-style:'.$font_style.'">' . wp_kses_post(stripslashes($mt_options['heading'])) .'</h2>';
    }

    if (!empty($mt_options['description'])) {
        $description_content = wpautop(wp_kses_post(stripslashes($mt_options['description'])), true);
        $out_content .= '<div class="description" style="font-weight:'.$font_weight.';font-style:'.$font_style.'">' . $description_content .'</div>';
    } else {
        $site_description = get_bloginfo('description');
        $out_content .= '<div class="description" style="font-weight:'.$font_weight.';font-style:'.$font_style.'"><h3>' . $site_description .'</h3></div>';
    }

    echo $out_content;
}
add_action('content_section', 'get_content_section', 10);

function get_footer_section() {
    $mt_options  = mt_get_plugin_options(true);
    if (!empty($mt_options['body_font_subset'])) {
        $current_subset = esc_attr($mt_options['body_font_subset']);
        $font_weight = (string)((int)$current_subset);
        $font_style = str_replace($font_weight, '', $current_subset);
    }
	
	if (empty($font_style) || $font_style == 'regular') {
		$font_style = 'normal';
	}
	if (empty($font_weight)) {
		$font_weight = 'normal';
	}
	
    $out_ftext   = null;

    if (isset($mt_options['footer_text']) && !empty($mt_options['footer_text'])) {
        $out_ftext .= '<div style="font-weight:'.$font_weight.';font-style:'.$font_style.'">' . wp_kses_post(stripslashes($mt_options['footer_text'])) . '</div>';
    }
    echo $out_ftext;
}
add_action('footer_section', 'get_footer_section', 10);

function do_button_login_form($error = -1) {
    ?>
    <div id="btn-open-login-form" class="btn-open-login-form">
        <i class="fi-lock"></i>
    </div>
    <?php

}

function do_login_form($user_login, $class_login, $class_password, $error = null) {
    $mt_options  = mt_get_plugin_options(true);
    $out_login_form = $form_error = '';
    if (($class_login == 'error') || ($class_password == 'error')) {
        $form_error = ' active error';
    }

    $out_login_form .= '<form name="login-form" id="login-form" class="login-form'.$form_error.'" method="post">';
    $out_login_form .= '<label>'. __('User Login', 'maintenance') .'</label>';
    $out_login_form .= '<span class="login-error">'.$error.'</span>';
    $out_login_form .= '<span class="licon '.$class_login.'"><input type="text" name="log" id="log" value="'.  $user_login .'" size="20"  class="input username" placeholder="'. __('Username', 'maintenance') .'"/></span>';
    $out_login_form .= '<span class="picon '.$class_password.'"><input type="password" name="pwd" id="login_password" value="" size="20"  class="input password" placeholder="'. __('Password', 'maintenance') .'" /></span>';
    $out_login_form .= '<a class="lost-pass" href="'.esc_url(wp_lostpassword_url()).'" title="'.__('Lost Password', 'maintenance') .'">'.__('Lost Password', 'maintenance') .'</a>';
    $out_login_form .= '<input type="submit" class="button" name="submit" id="submit" value="'.__('Login','maintenance') .'" tabindex="4" />';
    $out_login_form .= '<input type="hidden" name="is_custom_login" value="1" />';
    $out_login_form .= '</form>';

    if (isset($mt_options['is_login'])) {
        echo $out_login_form;
    }
}

function reset_pass_url() {
    $args = array( 'action' => 'lostpassword' );
    $lostpassword_url = add_query_arg( $args, network_site_url('wp-login.php', 'login') );
    return $lostpassword_url;
}
add_filter( 'lostpassword_url',  'reset_pass_url', 999, 0 );

function get_preloader_element() {
    $mt_options  = mt_get_plugin_options(true);
    if (!empty($mt_options['preloader_img'])) {
        $preloader_img = wp_get_attachment_image_src($mt_options['preloader_img'], 'full');
        $preloader_img = (!empty($preloader_img))?$preloader_img[0]:false;
    }

    $preloader = (!empty($preloader_img))?'<img src="'.$preloader_img.'">':'<i class="fi-widget" aria-hidden="true"></i>';
    $out = '<div class="preloader">'.$preloader.'</div>';
    echo $out;
}
add_action('before_content_section', 'get_preloader_element', 5);

function maintenance_gg_analytics_code() {
    $mt_options  = mt_get_plugin_options(true);
    if (!isset($mt_options['503_enabled']) && (isset($mt_options['gg_analytics_id'])) && ($mt_options['gg_analytics_id'] != '') ) {
        ?>
        <script type="text/javascript">
            (function(i,s,o,g,r,a,m){
                i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];
                a.async=1;
                a.src=g;
                m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', '<?php echo esc_attr($mt_options['gg_analytics_id']); ?>', 'auto');
            ga('send', 'pageview');

        </script>
        <?php
    }
}
add_action('add_gg_analytics_code', 'maintenance_gg_analytics_code');

function get_headers_503() {
    $mt_options  = mt_get_plugin_options(true);
    nocache_headers();
    if (!empty($mt_options['503_enabled'])) {
        $protocol = "HTTP/1.0";
        if ( "HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"] )
            $protocol = "HTTP/1.1";
        header( "$protocol 503 Service Unavailable", true, 503 );

        $vCurrDate_end = '';
        $vdate_end = date_i18n( 'Y-m-d', strtotime( current_time('mysql', 0) ));
        $vtime_end = date_i18n( 'h:i a', strtotime( '12:00 pm'));

        if (!empty($mt_options['expiry_date_end']))
            $vdate_end = $mt_options['expiry_date_end'];
        if (!empty($mt_options['expiry_time_end']))
            $vtime_end = $mt_options['expiry_time_end'];

        if (($mt_options['state']) && (!empty($mt_options['expiry_date_end']) && !empty($mt_options['expiry_time_end']))) {
            $date_concat = $vdate_end . ' ' . $vtime_end;
            $vCurrDate = strtotime($date_concat);
            if ( strtotime( current_time('mysql', 0)) < $vCurrDate ) {
                header( "Retry-After: " . gmdate("D, d M Y H:i:s", $vCurrDate) );
            } else {
                header( "Retry-After: 3600" );
            }
        } else {
            header( "Retry-After: 3600" );
        }
    }
}
