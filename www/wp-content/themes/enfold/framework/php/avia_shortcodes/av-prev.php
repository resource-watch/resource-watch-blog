<?php 

$full_path = __FILE__;
$path = explode( 'wp-content', $full_path );
require_once( $path[0] . '/wp-load.php' );

if(!current_user_can('edit_files')) die("");
if(empty($_GET['nonce'])) die("");
if (! wp_verify_nonce($_GET['nonce'], 'avia_shortcode_preview') ) die("");

do_action('avia_shortcode_prev');






$shortcode_css = AVIA_BASE_URL.'css/shortcodes.css';
?>

<html>

<head>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js" ></script>


<link rel="stylesheet" href="<?php echo $shortcode_css; ?>">

</head>
<body class='shortcode_prev'>

<?php

$shortcode = isset($_REQUEST['shortcode']) ? $_REQUEST['shortcode'] : '';

// WordPress automatically adds slashes to quotes
// http://stackoverflow.com/questions/3812128/although-magic-quotes-are-turned-off-still-escaped-strings
$shortcode = stripslashes($shortcode);

echo do_shortcode($shortcode);

?>
<script type="text/javascript">

    jQuery('#scn-preview h3:first', window.parent.document).removeClass('scn-loading');

</script>
</body>
</html>
