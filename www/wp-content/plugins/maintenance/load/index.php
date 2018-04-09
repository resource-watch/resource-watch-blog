<?php
$mess_arr 	 = array();
$ebody_class = null;
$mess_arr = get_custom_login_code();
if (!empty($mess_arr[0])) {
    $ebody_class = 'error';
}
$mt_options  = mt_get_plugin_options(true);
$site_title  = get_bloginfo('title');
$site_description = get_bloginfo('description');

$page_title = (isset($mt_options['page_title']) && !empty($mt_options['page_title'])) ? esc_attr(strip_tags(stripslashes($mt_options['page_title']))) : $site_title;
$logo 		= (isset($mt_options['logo']) && !empty($mt_options['logo'])) ? esc_attr($mt_options['logo']) : null;
$logo_ext   = null;

if (!empty($logo)) {
    $logo = wp_get_attachment_image_src($logo, 'full');
    $logo = esc_url($logo[0]);
    $logo_ext   = pathinfo($logo, PATHINFO_EXTENSION);
    $logo_ext   = str_replace('.', '', $logo_ext);
}

$page_description = (isset($mt_options['description']) && !empty($mt_options['description'])) ? esc_attr(strip_tags($mt_options['description'])) : $site_description;
if (!empty($page_description)) {
    $page_description = apply_filters( 'wpautop', stripslashes($page_description));
}

$bg    =  wp_get_attachment_image_src( $mt_options['body_bg'], 'full');
$body_bg = esc_url($bg[0]);
if (!empty($mt_options['bg_image_portrait'])) {
    $bg_image_portrait = wp_get_attachment_image_src($mt_options['bg_image_portrait'], 'full');
    $bg_image_portrait = (!empty($bg_image_portrait))?$bg_image_portrait[0]:false;
}
$google_fonts = mt_add_google_fonts();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <?php get_page_title(); ?>
    <?php
    if (function_exists('wp_site_icon')) {
        wp_site_icon();
    }
    ?>
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, minimum-scale=1">
    <meta name="description" content="<?php echo $site_description; ?>"/>
    <meta http-equiv="X-UA-Compatible" content="" />
    <meta property="og:site_name" content="<?php echo $site_title . ' - ' . $site_description; ?>"/>
    <meta property="og:title" content="<?php echo $page_title; ?>"/>
    <meta property="og:type" content="Maintenance"/>
    <meta property="og:url" content="<?php echo site_url(); ?>"/>
    <meta property="og:description" content="<?php echo $page_description; ?>"/>
    <?php if (!empty($logo)) { ?>
        <meta property="og:image" content="<?php echo $logo; ?>" />
        <meta property="og:image:url" content="<?php echo $logo; ?>"/>
        <meta property="og:image:secure_url" content="<?php echo $logo; ?>"/>
        <meta property="og:image:type" content="<?php echo $logo_ext; ?>"/>
    <?php } ?>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--    <link type="text/css" rel="stylesheet" href="--><?php //echo MAINTENANCE_URI.'load/css/style.css'; ?><!--">-->
    <?php do_action('load_custom_style'); ?>
    <?php do_action('add_gg_analytics_code'); ?>
    <!--[if IE]>
    <style type="text/css">

    </style>
    <script type="text/javascript" src="<?php echo MAINTENANCE_URI.'load/js/jquery.backstretch.min.js'; ?>"></script>
    <![endif]-->
    <?php if ( !empty($google_fonts[1]) ) {
        echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . $google_fonts[1] . '|' . $google_fonts[0] . '">';
    }
    else echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=' . $google_fonts[0] . '">';
    ?>
</head>

<body <?php body_class('maintenance ' . $ebody_class); ?>>

<?php do_action('before_main_container'); ?>
<div class="main-container">
    <?php do_action('before_content_section'); ?>
    <div id="wrapper">
        <div class="center logotype">
            <header>
                <?php do_action('logo_box'); ?>
            </header>
        </div>
        <div id="content" class="site-content">
            <div class="center">
                <?php do_action('content_section'); ?>
            </div>
        </div>
    </div> <!-- end wrapper -->
    <footer>
        <div class="center">
            <?php do_action('footer_section'); ?>
        </div>
    </footer>
    <?php do_action('after_content_section'); ?>
    <?php do_action('user_content_section'); ?>
    <?php
    if(empty($body_bg) && !empty($bg_image_portrait)) $body_bg = $bg_image_portrait;
    if(!empty($body_bg)) { ?>
        <picture class="bg-img">
            <?php if(!empty($bg_image_portrait)) { ?>
                <source media="(max-width: 100vh)" srcset="<?php echo $bg_image_portrait; ?>">
            <?php } ?>
            <img src="<?php echo $body_bg; ?>">
        </picture>
    <?php } ?>
</div>

<?php do_action('after_main_container'); ?>
<?php if (isset($mt_options['is_login'])) { ?>
    <div class="login-form-container">
        <?php do_login_form(esc_attr($mess_arr[3]), esc_attr($mess_arr[1]), esc_attr($mess_arr[2]), esc_attr($mess_arr[0])); ?>
        <?php do_button_login_form(); ?>
    </div>
<?php } ?>
<?php do_action('load_options_style'); ?>
<?php do_action('load_custom_scripts'); ?>



</body>
</html>