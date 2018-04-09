<?php 
/*
Skin: Broad 
*/
class DocumentorDisplaybroad{
	function __construct( $id=0 ) {
		$this->docid=$id;
		$this->name='broad';
		$this->hooks();
	}
	
	//return the guide/document html
	function view() {
		if( isset($this->docid) and $this->docid>0 and class_exists( 'DocumentorLiteAPI' ) ){
			$guide =  new DocumentorLiteAPI( $this->docid );
			
			//Get the HTML of the guide
			$html = "";
			$html.= $guide->get_doc();		
			return $html;
		}//if DocumentorLiteAPI class exists
	}
		
	/**
	* Returns the folder of current skin
	*
	* @since 1.5
	* @access public
	*
	*/
	function folder(){
		$folder = DocumentorLite::documentor_plugin_url( 'skins/'.$this->name.'/' );
		return $folder;
	}
	
	private function hooks(){
		//Skin specific Action and Filter hooks
		add_filter( 'doc_footer', array( &$this, 'doc_footer'), 10, 2 );
	}
	
	function doc_footer($footer, $settings){
		if( $settings->scrolltop == '1' ) {
			$footer.='<a class="scrollup doc-noprint" style="display: block;"><span class="icon-angle-up"></span></a>';
		}
		return $footer;
	}
}
?>