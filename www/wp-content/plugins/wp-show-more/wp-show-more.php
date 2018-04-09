<?php
/*
Plugin Name: WP show more
Plugin URI:  http://plugins.wordpress.org/wp-show-more/
Description: Add a user-defined link to display more content.
Version:     1.0.7
Author:      JAMOS Web Service
Author URI:  http://www.jamos.ch/plugins/wp-show-more
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: WP show more
*/

add_shortcode( 'show_more', 'wpsm');
function wpsm( $attr, $smcontent ) {
  if (!isset($attr['color'])) $attr['color'] = '#cc0000';
  if (!isset($attr['list'])) $attr['list'] = '';
  if (!isset($attr['align'])) $attr['align'] = 'left';
  if (!isset($attr['more'])) $attr['more'] = 'show more';
  if (!isset($attr['less'])) $attr['less'] = 'show less';
  if (!isset($attr['size'])) $attr['size'] = '100';
  $wpsm_string  = '<div class="show_more">';
  $wpsm_string .= '<p class="wpsm-show" style="color: ' . $attr['color'] .'; font-size: ' . $attr['size'].'%' . '; text-align: ' . $attr['align'] .';">'; 
  $wpsm_string .= $attr['list']. ' '  . $attr['more'];
  $wpsm_string .= '</p><div class="wpsm-content">';
  $wpsm_string .= do_shortcode($smcontent);
  $wpsm_string .= ' <p class="wpsm-hide" style="color: ' . $attr['color'] .'; font-size: ' . $attr['size'].'%' . '; text-align: ' . $attr['align'] .';">'; 
  $wpsm_string .= $attr['list']. ' '  . $attr['less'];
  $wpsm_string .= '</p>';
  $wpsm_string .= '</div></div>';
  return $wpsm_string;
}

add_action( 'wp_enqueue_scripts', 'sm_scripts');
function sm_scripts (){
  $plugin_url = plugins_url( '/', __FILE__ );
  wp_enqueue_style (
  	'sm-style',
  	$plugin_url . 'wpsm-style.css'
  );
  wp_enqueue_script (
  	'sm-script',
  	$plugin_url . 'wpsm-script.js',
  	array( 'jquery' ),
  	'1.0.1',
  	true
  );
}

add_action('wp_footer', 'sm_scripts');
?>