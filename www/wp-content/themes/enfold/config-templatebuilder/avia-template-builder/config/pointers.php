<?php
$pointers = array();
$screens = array('page','portfolio');


foreach($screens as $screen)
{
	$pointers[] = array(
		'id' => 'builder-button-pointer',   // unique id for this pointer
		'screen' => $screen, // this is the page hook we want our pointer to show on
		'target' => '#avia-builder-button', // the css selector for the pointer to be tied to, best to use ID's
		'title' => 'Avia Layout Builder',
		'content' => __('The Avia Layout Builder allows you to create unique layouts with an easy to use, drag and drop interface.','avia_framework' )."<br/><br/>".__('The Builder is available on Pages and Single Portfolio Entries','avia_framework' ),
		'position' => array( 
	                   'edge' => 'left', //top, bottom, left, right
	                   'align' => 'middle' //top, bottom, left, right, middle
	   )
	);

}
