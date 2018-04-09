<?php 
//Added for WooCommerce plugin compatibility 
if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_filter( 'woocommerce_product_tabs', 'doc_lite_woo_new_product_tab' );
	function doc_lite_woo_new_product_tab( $tabs ) {
		global $post;
		$pid = $post->ID; 
		if( !empty( $pid ) ) {
			$attachid = get_post_meta( $pid, '_documentor_attachid', true );
			if( !empty( $attachid ) ) {
				// Adds the new tab
				$tabs['desc_tab'] = array(
				'title'     => __( 'Documentation', 'woocommerce' ),
				'priority'  => 50,
				'callback'  => 'doc_lite_woo_new_product_tab_content'
				);
			}
		}
		return $tabs;
	}
	function doc_lite_woo_new_product_tab_content() {
		// The new tab content
		global $post;
		$pid = $post->ID;
		$attachid = get_post_meta( $pid, '_documentor_attachid', true ); 
		$attachid = intval( $attachid );
		if( !empty( $attachid ) ) {
			echo do_shortcode("[documentor ".$attachid."]");
		}
	}
}
//Added for : TablePress tables not appearing in generated PDF
add_action( 'init', 'doc_lite_load_tablepress_in_the_admin', 11 );
function doc_lite_load_tablepress_in_the_admin() {
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX && class_exists('TablePress')) {
	    TablePress::$controller = TablePress::load_controller( 'frontend' );
	}
}
//Added 1.4 : Crayon Syntax Highlighter Compatability
add_filter('guide_html', 'doc_lite_add_crayons_to_guide_html');
add_filter('section_html', 'doc_lite_add_crayons_to_guide_html');
function doc_lite_add_crayons_to_guide_html($content) {
	if(class_exists('CrayonWP')) {
		//commented the below line 1.4.5.2 and pasted the CrayonWP::highlight function, removing the header function call
		//return CrayonWP::highlight($content);
		$code=$content;
		$add_tags = FALSE;
		$output_text = FALSE;
		$captures = CrayonWP::capture_crayons(0, $code);
        $the_captures = $captures['capture'];
        if (count($the_captures) == 0 && $add_tags) {
            // Nothing captured, so wrap in a pre and try again
            $code = '<pre>' . $code . '</pre>';
            $captures = CrayonWP::capture_crayons(0, $code);
            $the_captures = $captures['capture'];
        }
        $the_content = $captures['content'];
        //$the_content = CrayonUtil::strip_tags_blacklist($the_content, array('script'));
        //$the_content = CrayonUtil::strip_event_attributes($the_content);
        foreach ($the_captures as $id => $capture) {
            $atts = $capture['atts'];
            $no_enqueue = array(
                CrayonSettings::ENQUEUE_THEMES => FALSE,
                CrayonSettings::ENQUEUE_FONTS => FALSE);
            $atts = array_merge($atts, $no_enqueue);
            $code = $capture['code'];
            $crayon = CrayonWP::shortcode($atts, $code, $id);
            $crayon_formatted = $crayon->output(TRUE, FALSE);
            $the_content = CrayonUtil::preg_replace_escape_back(CrayonWP::regex_with_id($id), $crayon_formatted, $the_content, 1, $count);
        }

        return $the_content;
	}
	else {
		return $content;
	}
}
?>