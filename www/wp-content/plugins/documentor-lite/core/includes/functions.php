<?php
function documentor_lite_convert_int_to_roman($integer, $upcase = true) { 
    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
    $return = ''; 
    while($integer > 0) 
    { 
        foreach($table as $rom=>$arb) 
        { 
            if($integer >= $arb) 
            { 
                $integer -= $arb; 
				if($upcase==false) {
					$return .= strtolower($rom);
				}
				else{
					$return .= $rom; 
				}
                break; 
            } 
        } 
    } 
    return $return; 
} 
function documentor_lite_convert_int_to_alpha($integer, $upcase = true) { 
    $table = array('','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $return = ''; 
    if($integer > 0) { 
        if($upcase==false) {
			$return .= strtolower($table[$integer]);
		}
		else{
			$return .= $table[$integer]; 
		}
    } 
    return $return; 
}
function documentor_lite_get_id_by_post_name($post_name){
	global $wpdb;
	$id = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s", $post_name) );
	return $id;
}
function documentor_lite_default_settings(){
	$blog_title = get_bloginfo( 'name' );
	$guide_subtitle = ( !empty( $blog_title ) ) ? 'by '.$blog_title : '';
	
	$defaults = array(
			'skin' => 'default',
			'animation' => '',
			'indexformat' => 1,
			'pif' =>'decimal',
			'cif' =>'decimal',			
			'navmenu_default' => 1,
			'navt_font' =>'regular',
			'navmenu_tfont' => 'Arial,Helvetica,sans-serif',
			'navmenu_tfontg' => '',
			'navmenu_tfontgw' => '',
			'navmenu_tfontgsubset' => '',
			'navmenu_custom' => '',
			'navmenu_color' => '#000000',
			'navmenu_fsize' => '14',
			'navmenu_fstyle' => 'normal',
			'actnavbg_default' => 1,
			'actnavbg_color' =>'#f3b869',
			'section_element' => '3',
			'sectitle_default' => 1,
			'sect_font' => 'regular',
			'sectitle_color' => '#000000',
			'sectitle_font' => 'Helvetica,Arial,sans-serif',
			'sectitle_fontg' => '',
			'sectitle_fontgw' => '',
			'sectitle_fontgsubset' => '',
			'sectitle_custom' => '',
			'sectitle_fsize' => '28',
			'sectitle_fstyle' => 'normal',
			'seccont_default' => 1,
			'seccont_color' => '#000000',
			'secc_font' => 'regular',
			'seccont_font' => 'Arial,Helvetica,sans-serif',
			'seccont_fontg' => '',
			'seccont_fontgw' => '',
			'seccont_fontgsubset' => '',
			'seccont_custom' => '',
			'seccont_fsize' => '14',
			'seccont_fstyle' => 'normal',
			'scrolling' => '1',
			'button'=>array('1','1','1','1','1','1'),
			'guide' => array( get_current_user_id() ),
			'feedback' => '1',
			'guide_subtitle' => $guide_subtitle,
			'feedback_frmname' => '1',
			'feedback_frmemail' => '1',
			'feedback_frminputs' => '',
			'feedback_frmtext' => '1',
			'feedback_frmcapcha' => '1',
			'feedback_frmsubject' => 'Feedback Submited for {doc-title} - {section-title}',
			'feedback_thankyoumsg' => 'Thank you for your feedback. Your inputs, suggestions and feedback are extremely valuable and help us serve our customers better',
			'fixmenu' => '1',
			'footerht' => '',
			'menuTop' => '0',
			'scroll_size' => '3', 
			'scroll_color' => '#F45349', 
			'scroll_opacity' => '0.4',
			'rtl_support' => '0',
			'menu_position' => 'left',
			'window_print' => '0',
			'updated_date' => '0',
			'scrolltop' => '1',
			'search_box' => '1',
			'feedbackcnt' => '0',
			'socialshare' => '0',
			'sharecount' => '1', 
			'socialbuttons' => array('1','1','1','1'),
			'sbutton_style' => 'square',
			'sbutton_position' => 'bottom',
			'togglemenu' => '0',
			'guidetitle' => '0',
			'guidet_element' => '2',
			'guidet_default' => 1,
			'guidet_font' => 'regular',
			'guidet_color' => '#000000',
			'guidetitle_font' => 'Arial,Helvetica,sans-serif',
			'guidet_fontg' => '',
			'guidet_fontgw' => '',
			'guidet_fontgsubset' => '',
			'guidet_custom' => '',
			'guidet_fsize' => '38',
			'guidet_fstyle' => 'normal',
		);
	return $defaults;
}
function documentor_lite_global_settings(){
	$global_defaults = array( 'custom_post' => '1',
							  'custom_posts' => array('post','page'),
							  'custom_styles' => '',
							  'user_level' => 'publish_posts',
						);
	return $global_defaults;
}
function documentor_lite_get_currurl(){
	global $post;
	//1.4 :fix for NGINX server
	$servername=$_SERVER['SERVER_NAME'];
	if( strpos($servername, '*') === false ){
		$currurl = (!empty($_SERVER['HTTPS'])) ? "https://".$servername.$_SERVER['REQUEST_URI'] : "http://".$servername.$_SERVER['REQUEST_URI'];
	}
	else{
		$currurl = get_permalink( $post );
	}
	return $currurl;
}
?>