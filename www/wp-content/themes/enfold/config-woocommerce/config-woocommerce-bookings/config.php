<?php
if(!is_admin())
{
	add_action('init', 'avia_woocommerce_bookings_register_assets');
}	
	
function avia_woocommerce_bookings_register_assets()
{
	wp_enqueue_style( 'avia-woocommerce-bookings-css', AVIA_BASE_URL.'config-woocommerce/config-woocommerce-bookings/woocommerce-booking-mod.css');
}