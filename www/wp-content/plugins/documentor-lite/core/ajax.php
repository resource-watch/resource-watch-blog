<?php // Hook for adding admin menus
if( !class_exists( 'DocumentorLiteAjax' ) ) {
	class DocumentorLiteAjax {
		function __construct() {
			$this->include_functions();
		}
		function include_functions(){
			if( class_exists( 'DocumentorLiteSection' ) and is_admin() ) {
				add_action('wp_ajax_doc_create_section', array('DocumentorLiteSection','create'));
				add_action('wp_ajax_documentor_show', array('DocumentorLiteSection','show'));
				add_action('wp_ajax_doc_section_add_linkform', array('DocumentorLiteSection','section_add_linkform'));
				add_action('wp_ajax_doc_update_section', array('DocumentorLiteSection','update'));
				add_action('wp_ajax_doc_negative_feedback', array('DocumentorLiteSection','negative_feedback'));
				add_action('wp_ajax_nopriv_doc_negative_feedback', array('DocumentorLiteSection','negative_feedback'));
				add_action('wp_ajax_doc_positive_feedback', array('DocumentorLiteSection','positive_feedback'));
				add_action('wp_ajax_nopriv_doc_positive_feedback', array('DocumentorLiteSection','positive_feedback'));
				add_action('wp_ajax_doc_get_feedback_form', array('DocumentorLiteSection','get_feedback_form'));
				add_action('wp_ajax_nopriv_doc_get_feedback_form', array('DocumentorLiteSection','get_feedback_form'));
				add_action('wp_ajax_doc_reset_section_feedbackcnt', array('DocumentorLiteSection','reset_feedbackcnt'));
				add_action('wp_ajax_nopriv_doc_reset_section_feedbackcnt', array('DocumentorLiteSection','reset_feedbackcnt'));
			}
			if( class_exists( 'DocumentorLiteGuide' ) and is_admin() ) {
				add_action('wp_ajax_doc_show_posts', array('DocumentorLiteGuide','doc_show_posts'));
				add_action('wp_ajax_doc_show_search_results', array('DocumentorLiteGuide','show_search_results'));
				add_action('wp_ajax_doc_save_sections', array('DocumentorLiteGuide','save_sections'));
				add_action('wp_ajax_doc_save_guideTitle', array('DocumentorLiteGuide','save_guideTitle'));
				add_action('wp_ajax_doc_load_preview', array('DocumentorLiteGuide','load_preview'));
				add_action('wp_ajax_doc_search_results', array('DocumentorLiteGuide','get_search_results'));
				add_action('wp_ajax_nopriv_doc_search_results', array('DocumentorLiteGuide','get_search_results'));
				add_action('wp_ajax_doc_reset_feedbackcnt', array('DocumentorLiteGuide','reset_feedback_count'));
				add_action('wp_ajax_nopriv_doc_reset_feedbackcnt', array('DocumentorLiteGuide','reset_feedback_count'));
				add_action('init', 'add_ob_start');
				add_action('wp_footer','flush_ob_end');
			}
			if( class_exists( 'DocumentorLiteFonts' ) and is_admin() ) {
				add_action('wp_ajax_documentor_disp_gfweight',array('DocumentorLiteFonts','google_font_weight'));
				add_action('wp_ajax_documentor_load_fontsdiv',array('DocumentorLiteFonts','load_fontsdiv_callback'));
				
			}
			
		}
		
	}
}//end-if
if( class_exists( 'DocumentorLiteAjax' ) ) {
	new DocumentorLiteAjax();
}
//added for wp_redirect
if( !function_exists( 'callback' ) ) {
	function callback($buffer){
		return $buffer;
	}
}
if( !function_exists( 'add_ob_start' ) ) {
	function add_ob_start(){
		ob_start("callback");
	}
}
if( !function_exists( 'flush_ob_end' ) ) {
	function flush_ob_end(){
		ob_end_flush();
	}
}
?>
