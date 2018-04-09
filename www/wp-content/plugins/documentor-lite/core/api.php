<?php 
/*
Class: DocumentorLiteAPI
*/ 
class DocumentorLiteAPI extends DocumentorLiteGuide {
	function __construct( $id=0 ) {
		$guideInst = new DocumentorLiteGuide($id);
		$guide=$guideInst->get_guide($id);
		if( $guide != null){
			$this->docid = $guide->doc_id;
			$this->settings = (object) ( $this->get_settings() + $this->get_formatted_settings() );
			$this->css = (object) $this->get_css();
			$this->info = (object) $this->get_info();
		}
		else{
			$this->docid=0;
		}
	}
	function get_docid(){
		
	}
	//get the settings for a particular guide
	function get_settings() {
		$settings = array();
		if(isset($this->docid)) {
			$guide = new DocumentorLiteGuide( $this->docid );
			$settings = $guide->get_settings();
			
			$settings['window_print'] = isset( $settings['window_print'] ) ? $settings['window_print'] : 0;
			$settings['scrolltop'] = isset( $settings['scrolltop'] ) ? $settings['scrolltop'] : 1;
			$settings['scrolling'] = ( !isset( $settings['scrolling'] )  ) ? 1 : $settings['scrolling']; 
			$settings['fixmenu'] = ( !isset( $settings['fixmenu'] )  ) ? 1 : $settings['fixmenu']; 
			$settings['menuTop'] = ( !isset( $settings['menuTop'] )  ) ? '0' : $settings['menuTop'];
			$settings['scroll_size'] = ( !isset( $settings['scroll_size'] )  ) ? 3 : $settings['scroll_size']; 
			$settings['scroll_color'] = ( !isset( $settings['scroll_color'] )  ) ? '#F45349' : $settings['scroll_color']; 
			$settings['scroll_opacity'] = ( !isset( $settings['scroll_opacity'] )  ) ? 0.4 : $settings['scroll_opacity']; 
			$settings['menu_position'] = isset($settings['menu_position']) ? $settings['menu_position'] : 'left'; 
			$settings['rtl_support'] = isset( $settings['rtl_support'] ) ? $settings['rtl_support'] : '0'; 
			if( isset( $settings['guide'] ) ) {
				$settings['guide_manager'] = $settings['guide'];
				unset( $settings['guide'] );
			}
			else{
				$settings['guide_manager'] = '';
			}
		}
		$settings=apply_filters('doc_settings', $settings, $this->docid);
		return $settings;
	}
	//get formatted settings for a particular guide
	function get_formatted_settings() {
		$settings = array();
		if(isset($this->docid)) {
			$guide = new DocumentorLiteGuide( $this->docid );
			$settingsArr = $guide->get_settings();
			
			//Guide Title HTML tag
			$settings['guide_title_tag'] = 'h2'; 
			if( isset( $settingsArr['guidet_element'] ) ) {
				for( $h = 1; $h <= 6; $h++ ) {
					if( $settingsArr['guidet_element'] == $h ) {
						$settings['guide_title_tag'] = 'h'.$h;
					} 
				}
			} 
			
			//Section Title HTML tag
			$settings['sec_title_tag'] = 'h3'; 
			if( isset( $settingsArr['section_element'] ) ) {
				for( $h = 1; $h <= 6; $h++ ) {
					if( $settingsArr['section_element'] == $h ) {
						$settings['sec_title_tag'] = 'h'.$h; 
					} 
				}
			} 
			
			//Populate Section Transition
			$settings[ 'transition_class' ] = '';
			if( !empty( $settingsArr[ 'animation' ] ) ) {
				$settings[ 'transition_class' ] = "wow documentor-".$settingsArr[ 'animation' ];
			}
			
			//Inline CSS for the Section's Index
			$settings[ 'index_css' ] = 'style="display:none"';
			if( $settingsArr[ 'indexformat' ] == 1 ) {
				$settings[ 'index_css' ] = 'style="display:inline-block"';
			}
			
			//get skin specific icons 
			$root = 'skins/'.$settingsArr[ 'skin' ];
			$settings[ 'link_icon' ] = DocumentorLite::documentor_plugin_url( $root."/images/link.png" );
			$settings[ 'message_icon' ] = DocumentorLite::documentor_plugin_url( $root."/images/message.png" );
			$settings[ 'print_icon' ] = DocumentorLite::documentor_plugin_url( $root."/images/document-print.png" );
			$settings[ 'edit_icon' ] = DocumentorLite::documentor_plugin_url( $root."/images/edit.png" );
			$settings[ 'feedback_icon' ] = DocumentorLite::documentor_plugin_url( $root."/images/feedback.png" );
			$settings[ 'search_icon' ] = DocumentorLite::documentor_plugin_url( $root."/images/search.png" );
			$settings[ 'print_css' ] = DocumentorLite::documentor_plugin_url( $root."/print.css" );
		}
		$settings=apply_filters('doc_formatted_settings', $settings, $this->docid);
		return $settings;
	}
	//get the information about a particular guide
	function get_info() {
		$info = array();
		if(isset($this->docid)) {
			$guide = new DocumentorLiteGuide( $this->docid );
			$guideObject=$guide->get_guide( $this->docid );
			if( is_object( $guideObject ) ) {
				$info['name']=$guideObject->doc_title;
				$info['created_on']=$guideObject->created_on;
			}
			else{
				$info['name']=$info['created_on']='';
			}
		}
		$info=apply_filters('doc_info', $info, $this->docid);
		return $info;
	}
	//get single section
	function get_section( $sec_id ) {
		$section = array();
		if(isset($this->docid)) {
			//$section[0]['id'] = $sec_id;
			$guide = new DocumentorLiteGuide( $this->docid );
			$guideObject=$guide->get_guide( $this->docid );
			$sections=json_decode( $guideObject->sections_order, true );
			
			//Populate dec_index and & display_index for parent sections only
			foreach($sections as $idx=>$parent_section){
				$sections[$idx][ 'dec_index' ] = $idx+1;
				$sections[$idx][ 'display_index' ] = $this->get_display_index_format( $this->settings->pif, ($idx+1) );
			}
			$initial_disp_idx=$this->get_display_index_format( $this->settings->pif, '1' );
			$sections = $this->populate_indexes( $sections, '1', $initial_disp_idx );		
			$sections = $this->convert_single_dim( $sections );

			$secArr=array(
						'sec_id'=>$sec_id,
						'sections'=>$sections,
						'dec_index'=>'',
						'display_index'=>'',
						'prev_sec_id'=>'0',
						'next_sec_id'=>'0',
						'prev_menu_title'=>'',
						'next_menu_title'=>'',
						'type'=>'',
						'section_title'=>'',
						'content'=>'',
						'modified_on'=>'',
						'edit_link'=>'',
						'href'=>'',
						'href_hash'=>'',
						'upvote'=>0,
						'downvote'=>0,		
					);
			
			$section[0] = $this->populate_section_fields( $secArr );
		}
		$section=apply_filters('doc_settings', $section, $this->docid, $sec_id);
		return $section;
	}
	private function populate_indexes( $sections, $parent_idx='1', $parent_disp_idx='1' ){
		if( count( $sections ) > 0 ) {
			foreach( $sections as $idx=>$section ) {
				if( !isset( $sections[ $idx ][ 'dec_index' ] ) ){
					$sections[ $idx ][ 'dec_index' ] = $parent_idx.'.'.($idx+1);
					$sections[ $idx ][ 'display_index' ] = $parent_disp_idx.'.'.$this->get_display_index_format($this->settings->cif, ($idx+1) );
				}

				if ( array_key_exists('children', $section) ) {
					$children = $section[ 'children' ];
					$parent_idx = $sections[ $idx ][ 'dec_index' ];
					$parent_disp_idx = $sections[ $idx ][ 'display_index' ];
					$sections[$idx][ 'children' ] = $this->populate_indexes( $children, $parent_idx, $parent_disp_idx );
				}
			}
		}
		return $sections;
	}
	private function convert_single_dim( $sections=array() ){
		$out=array();
		if( count($sections) > 0 ){
			foreach( $sections as $idx=>$section ){
				if ( array_key_exists('children', $section) ) {
					$children = $section[ 'children' ];
					unset( $section[ 'children' ] );
					$out[]=$section;
					$result=$this->convert_single_dim( $children );
					if( count($result) > 0 ) {
						foreach($result as $child){
							$out[]=$child;
						}
					}
				}
				else{
					$out[]=$section;
				}
			}
		}
		return $out;
	}
	private function populate_section_fields( $secArr=array() ){
		extract( $secArr );
		if( count($sections) > 0 ){
			foreach( $sections as $idx=>$section ){
				if( $section['id'] == $sec_id ){
					$secArr['dec_index']=$section['dec_index'];
					$secArr['display_index']=$section['display_index'];
					if( isset( $sections[$idx-1]['id'] ) ){
						$secArr['prev_sec_id']=$sections[$idx-1]['id'];
						$menu_data=$this->get_menu_data( $secArr['prev_sec_id'] );
						$secArr['prev_menu_title']=$menu_data->menu_title;
						$secArr['prev_href_hash']=$menu_data->href_hash;
						$secArr['prev_menu_target']=$menu_data->target;
					}
					if( isset( $sections[$idx+1]['id'] ) ){
						$secArr['next_sec_id']=$sections[$idx+1]['id'];
						$menu_data=$this->get_menu_data( $secArr['next_sec_id'] );
						$secArr['next_menu_title']=$menu_data->menu_title;
						$secArr['next_href_hash']=$menu_data->href_hash;
						$secArr['next_menu_target']=$menu_data->target;
					}
					
					$menu_data=(array)$this->get_menu_data($sec_id);
					$section_data=(array)$this->get_section_data($sec_id);
					$secArr=array_merge($secArr, $menu_data, $section_data);
					break;
				}
			}
		}
		unset( $secArr['sections'] );
		return $secArr;
	}
	function get_menu_data( $sec_id = 0 ){
		$sec = new DocumentorLiteSection( $this->docid, $sec_id );
		$secData = $sec->getsection( $sec_id );
		$secPost = get_post( $secData->post_id );
		
		//WPML
		if( function_exists('icl_plugin_action_links') ) {	
			if( $secData->type == 0 ) $type = 'documentor-sections';
			else if( $secData->type == 1 ) $type = 'post';
			else if( $secData->type == 2 ) $type = 'page';
			else {
				$type = get_post_type( $secPost->ID );
			}
			$lang_post_id = icl_object_id( $secPost->ID , $type, true, ICL_LANGUAGE_CODE );
			$secPost = get_post( $lang_post_id );
		}
		$menu[ 'target' ] = '';
		$menu[ 'menu_href' ] = '';
		$menu[ 'menu_title' ] = '';
		
		if( $secData->type == 3 ) { //if link section
			if( $secPost != NULL ) {
				$jarr = unserialize( $secPost->post_content );
				$target = '';
				if( $jarr[ 'new_window' ] == '1' ) {
					$menu[ 'target' ] = 'target="_blank"';
				}
				$menu[ 'menu_href' ] = esc_url($jarr['link']);
				$menu[ 'menu_title' ] = $secPost->post_title; 
			}
		} else { //if section is post or page or inline
			$menu[ 'menu_title' ] = get_post_meta( $secPost->ID, '_documentor_menutitle', true );
			
			$href = 'section-'.$secData->sec_id;
			if( !empty( $secPost->post_name ) ) {
				$href = apply_filters( 'editable_slug', $secPost->post_name );
			} 

			$href_hash = $href;
			$href = "#".$href;

			$menu[ 'menu_href' ] = $href;
			$menu[ 'href_hash' ] = $href_hash;
		}
		$menu=apply_filters('doc_menu_data', $menu, $this->docid);
		$menu = (object)$menu;
		return $menu;
	}
	function get_section_data( $sec_id = 0 ){
		$section=array();
		$sec = new DocumentorLiteSection( $this->docid, $sec_id );
		$secData = $sec->getsection( $sec_id );
		$secPost = get_post( $secData->post_id );
		
		//WPML
		if( function_exists('icl_plugin_action_links') ) {	
			if( $secData->type == 0 ) $type = 'documentor-sections';
			else if( $secData->type == 1 ) $type = 'post';
			else if( $secData->type == 2 ) $type = 'page';
			else {
				$type = get_post_type( $secPost->ID );
			}
			$lang_post_id = icl_object_id( $secPost->ID , $type, true, ICL_LANGUAGE_CODE );
			$secPost = get_post( $lang_post_id );
		}
		
		$section[ 'postid' ] = $secPost->ID;
		$section[ 'post_title' ] = $secPost->post_title;
		$section[ 'sec_id' ] = $secData->sec_id;
		
		if( $secData->type == 0 ) $type = 'documentor-sections';
		else if( $secData->type == 1 ) $type = 'post';
		else if( $secData->type == 2 ) $type = 'page';
		else if( $secData->type == 3 ) $type = 'link';
		else {
			$type = get_post_type( $secPost->ID );
		}
		$section[ 'type' ] = $secData->type;
		$section[ 'post_type' ] = $type;
		
		$href_hash = 'section-'.$secData->sec_id;
		if( !empty( $secPost->post_name ) ) {
			$href_hash = apply_filters( 'editable_slug', $secPost->post_name );
		} 
		$section[ 'href_hash' ] = $href_hash;
		
		$href = documentor_lite_get_currurl()."#".$href_hash;

		$section[ 'href' ] = $href;
		
		$section[ 'upvote' ] = $secData->upvote;
		$section[ 'downvote' ] = $secData->downvote;
		$section[ 'title' ] = $secPost->post_title;
		
		$section[ 'content' ] = apply_filters('the_content', $secPost->post_content);
		
		$section[ 'section_title' ] = '';
		$section[ 'modified_on' ] = '';
		$section[ 'edit_link' ] = '';
		if( $secData->type == 3 ) { //if link section
			if( $secPost != NULL ) {
				$section[ 'section_title' ] = $secPost->post_title; 
			}
		} else { //if section is post or page or inline
			$menu_title = get_post_meta( $secPost->ID, '_documentor_menutitle', true );
			
			//Section Title
			$section_title = get_post_meta( $secPost->ID, '_documentor_sectiontitle', true );
			$section[ 'section_title' ] = ( !empty( $section_title ) ) ? $section_title : $menu_title;
			
			//get section's last modified date
			$modified_date = $secPost->post_modified;
			$section[ 'modified_on' ] = date_i18n( get_option( 'date_format' ), strtotime( $modified_date ) );
			
			//get section's edit link
			if( post_type_exists($type) ) { 
				$section[ 'edit_link' ] = get_edit_post_link( $secPost->ID );
			}
		}
		$section=apply_filters('doc_section_data', $section, $this->docid, $sec_id);
		$section=(object)$section;
		return $section;
	}
	function get_first_section() {
		if(isset($this->docid)) {
			$guide = new DocumentorLiteGuide( $this->docid );
			$guideObject=$guide->get_guide( $this->docid );
			$sections=json_decode( $guideObject->sections_order, true );
			$first_sec_id=$sections[0]['id'];
			return $first_sec_id;
		}
		return 0;
	}
	//get all the sections with data in proper order of a particular guide
	function get_sections( $is_ajax=false ) {
		$sections = array();
		if(isset($this->docid)) {
			$guide = new DocumentorLiteGuide( $this->docid );
			$guideObject=$guide->get_guide( $this->docid );
			if($guideObject != null):
				$sections=json_decode( $guideObject->sections_order, true );
				
				//Populate dec_index and & display_index for parent sections only
				foreach($sections as $idx=>$parent_section){
					$sections[$idx][ 'dec_index' ] = $idx+1;
					$sections[$idx][ 'display_index' ] = $this->get_display_index_format( $this->settings->pif, ($idx+1) );
				}
				$initial_disp_idx=$this->get_display_index_format( $this->settings->pif, '1' );
				$sections = $this->populate_indexes( $sections, '1', $initial_disp_idx );
				$sections = $this->populate_sectionArray( $sections, $is_ajax );
			endif;
		}
		$sections=apply_filters('doc_sections', $sections, $this->docid);
		return $sections;
	}
	private function populate_sectionArray( $sections, $is_ajax=false ){
		if( count($sections) > 0 ) {
			$settings = $this->get_settings();
			foreach( $sections as $idx=>$section ) {
				$sec_id = $section['id'];
				$menu_data = $this->get_menu_data( $sec_id );
				$section_data = $this->get_section_data( $sec_id );

				$sections[$idx][ 'sec_id' ] = $section_data->sec_id;
				$sections[$idx][ 'postid' ] = $section_data->postid;
				$sections[$idx][ 'type' ] = $section_data->type;
				$sections[$idx][ 'post_type' ] = $section_data->post_type;
				
				$sections[$idx][ 'upvote' ] = $section_data->upvote;
				$sections[$idx][ 'downvote' ] = $section_data->downvote;
				$sections[$idx][ 'title' ] = $section_data->post_title;
				$sections[$idx][ 'content' ] = $section_data->content;
				$sections[$idx][ 'target' ] = $menu_data->target;
				$sections[$idx][ 'menu_href' ] = $menu_data->menu_href;
				$sections[$idx][ 'menu_title' ] = $menu_data->menu_title;
				$sections[$idx][ 'href_hash' ] = $section_data->href_hash;
				$sections[$idx][ 'section_title' ] = $section_data->section_title;
				$sections[$idx][ 'modified_on' ] = $section_data->modified_on;
				$sections[$idx][ 'edit_link' ] = $section_data->edit_link;
				
				if ( array_key_exists('children', $section) ) {
					$children = $section[ 'children' ];
					$sections[$idx][ 'children' ] = $this->populate_sectionArray( $children, $is_ajax );
				}
				//End : Populate dec_index for all children sections
			}
		}
		return $sections;
	}
	function get_css(){
		$guide = new DocumentorLiteGuide( $this->docid );
		$css=$guide->get_inline_css();
		$css=apply_filters('doc_settings', $css, $this->docid);
		return $css;
	}
	function get_guideData(){
		$guideData = array();
		$guideData['info']=$this->get_info();
		$guideData['settings']=$this->get_settings();
		$guideData['sections']=$this->get_sections();
		$guideData['css']=$this->get_css();
		return $guideData;
	}
	function get_display_index_format($format, $num){
		switch( $format ) {
			case 'lower-roman':
				$disp_num=documentor_lite_convert_int_to_roman( $num, false);
				break;
			case 'upper-roman':
				$disp_num=documentor_lite_convert_int_to_roman( $num );
				break;
			case 'lower-alpha':
				$disp_num=documentor_lite_convert_int_to_alpha( $num, false);
				break;
			case 'upper-alpha':
				$disp_num=documentor_lite_convert_int_to_alpha( $num );
				break;
			default:
				$disp_num=$num;
		}
		return $disp_num;
	}
	/**
	* Main function called in skin to display the guide
	*
	* @since 1.5
	* @access public
	*
	* @param none.
	* @return string HTML for the Guide.
	*/
	function get_doc(){
		$html='';
		if(isset($this->docid) and $this->docid>0){
			$html.= $this->enqueue_resources();
			
			$before_doc='';
			$before_doc=apply_filters('doc_before', $before_doc, $this->docid);
			$html.=$before_doc.$this->get_doc_wrap_top();
			$sections=array();
			
			$menus = $sections = $this->get_sections();

			//Get the Menu HTML
			$menu_html=$this->get_the_doc_menu( $menus );
			$menu_html=apply_filters('doc_menu_html', $menu_html, $this->docid);
			$html.= $menu_html;
			//Get Sections HTML
			$sections_html=$this->get_the_doc_sections( $sections );
			$sections_html=apply_filters('doc_sections_html', $sections_html, $this->docid);
			$html.= $sections_html;
			
			$after_doc='';
			$after_doc=apply_filters('doc_after', $after_doc, $this->docid);
			$html.= $this->get_doc_wrap_bottom().$after_doc;
		}
		return $html;
	}
	
	function get_doc_wrap_top(){
		$html = '';
		$wrapclass = '';
		if( $this->settings->rtl_support == '1' ) $wrapclass = ' documentor-rtl';
		
		$wrapclass = apply_filters('doc_wrapclass', $wrapclass, $this->docid);
		
		$html .= '<div id="documentor-'.$this->docid.'" class="documentor-'.$this->settings->skin.' documentor-wrap'.$wrapclass.'" data-docid = "'.$this->docid.'" >';
		
		//Get top section of guide wrap
		$html .= '<div class="documentor-topicons doc-noprint"><span class="doc-topiconswrap">';
		if( $this->settings->button[4] == 1 ) {
			$print_icon='<img height="15" width="15" src='. $this->settings->print_icon .'>';
					
					/**
					 * Filters the HTML of the Print icon.
					 *
					 * @param string  $print_icon	HTML for the Print icon
					 * @return string $print_icon	HTML for the Print icon
					 */
			$print_icon=apply_filters( 'doc_print_icon', $print_icon );
			
			$html.= '<a class="doc-print" data-printspath="'. $this->settings->print_css .'"> '.$print_icon.' </span></a>';
		}			
		$html .= '</span><div class="cleardiv"></div><div class="clrright"></div></div>';		
		
		//Get the guide title html
		$html.=$this->get_title_html();
		//add social share buttons at top of the document
		if( $this->settings->socialshare == 1 && $this->settings->sbutton_position == 'top' ){
			$html .= $this->get_social_buttons(); 
		}
		$html .= '<div class="document-wrapper">';
		return $html;
	}
	
	//Get Guide Title HTML
	function get_title_html(){
		$html = '';
		if( $this->settings->guidetitle == 1 ) {
			$html .= '<div class="doc-guidetitle"><'.$this->settings->guide_title_tag.' class="doc-title" '.$this->css->guidetitle.'>'.$this->info->name.'</'.$this->settings->guide_title_tag.'></div>';
		}
		return $html;
	}
	//build menus to display at front
	function get_the_doc_menu( $sections ) {
		$html = '';
		$menuclass = '';
		if( $this->settings->menu_position == 'right' ) {
			$menuclass = ' doc-menuright';
		}
		if( $this->settings->togglemenu == 1 ) {
			$menuclass .= ' toggle';
		} 
		$menuclass = apply_filters( 'doc_menuclass', $menuclass, $this->docid );
		$html .= '<div class="doc-menu'.$menuclass.' doc-noprint">';
		
		if( isset( $this->settings->search_box ) && $this->settings->search_box == '1' ){
			$html .= '<span class="doc-search">
					<input type="text" name="search_document" class="search-document" placeholder="'.__('Search','documentor-lite').'" />
					<img src="'.$this->settings->search_icon.'" />
				</span>';
		}
		
		$html.='<div class="doc-menurelated">';
		$html.='<ol class="doc-list-front">';	
		$html.= $this->get_menuitem( $sections );
		$html.='</ol>';
		$html.=	'</div></div>';
		return $html;
	}
	function get_menuitem( $sections ) {
		$html = "";
		foreach( $sections as $section ) {
			extract( $section );
			$menu_class='';$menu_title_after='';
			if( $section['type'] != 3 ) {
				$menu_class='class="documentor-menu" ';
			}
			else{
				$menu_class='class="doc-link" ';
				$menu_title_after=' <span class="icon-external-link doc-ext"></span>';
			}
			
			$data_sec_counter='';
			if( $section['type'] != 3 ) {
				$data_sec_counter = ' data-sec-counter="'.$dec_index.'"';
			}
			
			$data_section_id='';
			if( $section['type'] != 3 ) {
				$data_section_id = ' data-section-id="'.esc_attr($section['sec_id']).'"';
			}
			
			$html .= '<li class="doc-actli">';
			
			$menu_link_html = '<a '.$menu_class.'href="'.$menu_href.'" '.$target.' '.$this->css->navmenu.' data-href="#'.$href_hash.'"'.$data_sec_counter.$data_section_id.'>'.$menu_title.$menu_title_after.'</a>';
			
			/**
			 * Filters the HTML of the anchor link of single menu item.
			 *
			 * @param string $menu_link_html	HTML for the anchor link of single menu item.
			 * @param int  $sec_id    			Section ID of the menu item.
			 */
			$menu_link_html=apply_filters( 'menu_link_html', $menu_link_html, $section['sec_id'] );
			
			$html .= $menu_link_html;
			
			/**
			 * Use this filter hook in case you want to add HTML after each menu item.
			 *
			 * @param string  $menu_item_after  HTML at the end of each menu
			 * @param int 	  $sec_id	        Section ID
			 * @return string $menu_item_after	HTML at the end of each menu
			 */
			$menu_item_after='';

			if ( isset( $section['children'] ) && count( $section['children'] ) > 0 ) {
				$html .= '<span class="doc-mtoggle expand"></span>';
				
				$menu_item_after.=apply_filters( 'doc_menu_item_after', $menu_item_after, $section['sec_id'] );
				$html .= $menu_item_after;
				
				$html .= '<ol>';
				$children = $section['children'];
				$html .= $this->get_menuitem( $children );
				$html .= '</ol>';
			}
			else{
				$menu_item_after.=apply_filters( 'doc_menu_item_after', $menu_item_after, $section['sec_id'] );
				$html .= $menu_item_after;
			}
			
			$html .= '</li>';
		} //end foreach
		return $html;
	}
	
	function get_the_doc_sections( $sections ){
		$html='';
		//Section container
		$sec_containerclass='';
		if( $this->settings->menu_position == 'right' ) {
			$sec_containerclass = ' doc-seccontainer-left';
		}
		$html.='<div class="doc-sec-container'.$sec_containerclass.'" id="documentor_seccontainer">';
		
		if( count($sections) > 0 ) {
			
			$html .= $this->get_section_html( $sections );
			
			//add social share buttons at bottom of document
			if( $this->settings->socialshare == 1 && $this->settings->sbutton_position == 'bottom' ) {
				$html .= $this->get_social_buttons();
			}  
		}
		
		$html.='</div><!--.doc-sec-container-->'; 
		
		$html .= $this->get_script();
		
		return $html;
	}
	
	function get_section_html( $sections, $url='' ){
		$html='';
		if( empty( $url ) )	$url = documentor_lite_get_currurl();
		foreach( $sections as $section ){
			extract( $section );
			$html.= '<div class="doc-sectionwrap" id="'.esc_attr($href_hash).'_wrap"><div class="documentor-section '.$this->settings->transition_class.' section-'.esc_attr($sec_id).'" id="'.esc_attr($href_hash).'" data-section-id="'.esc_attr( $sec_id ).'">';
			
			$hash_html='';
			if( $this->settings->button[1] == 1 ) { 
				
				$currurl = $url."#".$href_hash;

				$hash_html = '<span class="doc-sec-link doc-noprint" onclick="prompt(\''.__('Press Ctrl + C, then Enter to copy to clipboard', 'documentor-lite').'\',\''.$currurl.'\')">#</span>';
				
				$hash_html=apply_filters( 'doc_hash', $hash_html, $currurl, $sec_id );
			}
			
			//Edit Section link at the front end
			$edtlink='';
			if( post_type_exists($post_type) ) { 
				if ( is_user_logged_in() && current_user_can('edit_post', $postid)) {
					$edtlink = get_edit_post_link($postid);
					$edtlink = '<span class="doc-postedit-link"><a href="'.esc_url($edtlink).'" target="_blank">'. __('Edit','documentor-lite').'</a></span>';
				}
				$edtlink=apply_filters( 'doc_sec_edit', $edtlink, $sec_id );
			}
			
			$sec_title_style='';
			if( $type == 3 )	$sec_title_style=' style="display:none;" ';
			else $sec_title_style=$this->css->sectitle;
			
			/**
			 * Use this filter hook in case you want to add HTML inside section title.
			 *
			 * @param string  $in_title	HTML at the end of section title
			 * @param int     $sec_id	Section ID
			 * @param string  $settings	Settings object
			 * @return string $in_title	HTML at the end of section title
			 */
			$in_title='';
			$in_title.=apply_filters( 'doc_in_sec_title', $in_title, $sec_id, $this->settings );
			
			$phtml='';
			if( $type != 3 ) {
				$phtml .= '<div class="documentor-social doc-noprint">';
				
				$phtml .= '</div>';
			}//Not link sections
			
			$html .= '<'.$this->settings->sec_title_tag.' class="doc-sec-title" '. $sec_title_style .'> <span class="doc-sec-count" '.$this->settings->index_css.'>'.$display_index.'.</span>'.$section_title.' '.$hash_html.' '.$phtml.$edtlink.$in_title.'</'. $this->settings->sec_title_tag .'>';
			
			$html .= '<div class="doc-sec-content" '.$this->css->sectioncontent.'>';
			
			if( $type != 3 ) {
				//the content
				$html .= $content;
			}
			$html .= '</div>';
			
			if( $type != 3	) { //not link section
				if( !empty($content) ) {
					$html.= '<div class="documentor-help">'; 
					if( $this->settings->feedback == 1 ) {
						$take_feedback='<span class="doc-noprint doc-feedback"><img src='.$this->settings->feedback_icon.' width="16" height="16" />'.esc_html__("Was this helpful?","documentor").'</span>';
						/**
						 * Filters the HTML of the "Was this helpful?" feedback text and icon.
						 *
						 * @param string $take_feedback Feedback icon/image and text HTML
						 * @param int    $sec_id    	Section ID.
						 */
						$take_feedback=apply_filters( 'doc_take_feedback', $take_feedback, $sec_id );
						
						$html.= $take_feedback.'
						<span class="doc-noprint"><a class="positive-feedback" href="#" > '.__('Yes','documentor-lite').' </a></span> 
						<span class="doc-noprint"><a class="negative-feedback" href="#" > '.__('No','documentor-lite').' </a></span>';   
					}
					if( $this->settings->updated_date == 1 ) {
						$html.='<div class="doc-mdate doc-noprint">'.esc_html__('Last updated on','documentor-lite').' '.$modified_on.'</div>';
					}
					if( $this->settings->feedbackcnt == 1 ) {
						$totalvotes = $upvote + $downvote;
						$html.='<div class="doc-feedbackcnt"><span class="upvote">'.$upvote.'</span> of <span class="totalvote">'.$totalvotes.'</span>'.__(' users found this section helpful','documentor-lite').'</div>';
					}
					$html .= '<div class="negative-feedbackform doc-noprint">
					</div><div class="feedback-msg doc-noprint"></div>';
					//social buttons
					
					$html .= '</div>';	
				}//content is not empty	
			
			}
			
			$html.= '</div></div><!--./doc-sectionwrap-->';
			
			if ( isset( $section['children'] ) && count( $section['children'] ) > 0 ) {	 
				$children = $section['children'];
				$html .= $this->get_section_html( $children ); 
			}
		} //end foreach
		return $html;
	}
	
	function get_script(){
		$html = '';
		$secstyle ='';
		if( $this->settings->indexformat == 1 ) {
			$tag = 'style="';
			$secstyle = str_replace($tag, "",$this->css->navmenu);
			$secstyle = rtrim($secstyle, '"');
		}
		$script =  '<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#documentor-'.$this->docid.'").documentor({
					documentid	: '.$this->docid.',
					docid		: "documentor-'.$this->docid.'",
					animation	: "'.$this->settings->animation.'",
					indexformat	: "'.$this->settings->indexformat.'",
					pformat		: "'.$this->settings->pif.'",
					cformat		: "'.$this->settings->cif.'",					
					secstyle	: "'.$secstyle.'",
					actnavbg_default: "'.$this->settings->actnavbg_default.'",
					actnavbg_color	: "'.$this->settings->actnavbg_color.'",
					scrolling	: "'.$this->settings->scrolling.'",
					fixmenu		: "'.$this->settings->fixmenu.'",
					skin		: "'.$this->settings->skin.'",
					scrollBarSize	: "'.$this->settings->scroll_size.'",
					scrollBarColor	: "'.$this->settings->scroll_color.'",
					scrollBarOpacity: "'.$this->settings->scroll_opacity.'",
					windowprint	: "'.$this->settings->window_print.'",
					menuTop: "'.$this->settings->menuTop.'",
					socialshare	: '.$this->settings->socialshare.',
					sharecount	: '.$this->settings->sharecount.',
					fbshare		: '.$this->settings->socialbuttons[0].',
					twittershare	: '.$this->settings->socialbuttons[1].',
					gplusshare	: '.$this->settings->socialbuttons[2].',
					pinshare	: '.$this->settings->socialbuttons[3].',
					togglechild	: '.$this->settings->togglemenu.',
					noResultsStr: "'.__('No results found!', 'documentor-lite').'",
				});	
			});</script>'; 
		$html .= $script;
		return $html;
	}
	
	function enqueue_resources(){
		$html='';
		//Print Guide using JS
		if( $this->settings->button[4] == 1 ) {
			wp_enqueue_script( 'doc_print', DocumentorLite::documentor_plugin_url( 'core/js/jQuery.print.js' ), array('jquery'), DOCUMENTORLITE_VER, false);
			if( $this->settings->window_print == 0 ) {
				wp_enqueue_style( 'doc_'.$this->settings->skin.'_printcss', DocumentorLite::documentor_plugin_url( 'skins/'.$this->settings->skin.'/print_style.css' ), false, DOCUMENTORLITE_VER, 'print');	
			} 
		}
		//Stylesheet for Social Sharing buttons
		if( $this->settings->socialshare == 1 ) {
			wp_enqueue_style( 'doc_socialshare', DocumentorLite::documentor_plugin_url( 'core/css/socialshare_fonts.css' ), false, DOCUMENTORLITE_VER);	
		} 
		//JS for animations
		if( !empty ( $this->settings->animation ) ) {
			wp_enqueue_script( 'wow-js', DocumentorLite::documentor_plugin_url( 'core/js/wow.js' ), array('jquery'), DOCUMENTORLITE_VER, false);
		}
		
		//Main Documentor JS
		wp_enqueue_script( 'doc_js', DocumentorLite::documentor_plugin_url( 'core/js/documentor.js' ), array( 'jquery','jquery-ui-autocomplete' ), DOCUMENTORLITE_VER );
		wp_localize_script( 'doc_js', 'DocAjax', array( 'docajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		
		do_action('doc_enqueue_resources', $this->docid);
		
		$resources='';
		//Main Documentor CSS - Skin specific
		$style_counter_name='doc_style_counter_'.$this->settings->skin;
		global ${$style_counter_name};
		if( !isset( ${$style_counter_name} ) or ${$style_counter_name} < 1 ){
			$resources="<link rel='stylesheet' href='".DocumentorLite::documentor_plugin_url( 'skins/'.$this->settings->skin.'/style.css' )."' type='text/css' media='all' />";
			${$style_counter_name}++;
		}
		
		$resources=apply_filters('doc_resources_html', $resources, $this->docid);
		
		$html .=$resources;
		
		return $html;
	}
	
	function get_doc_wrap_bottom(){
		$html = '';
		
		$clearclass = '';
		if( $this->settings->rtl_support == '1' ) { 
			$clearclass = ' cleardiv-rtl'; 
		} 
		
		/**
		 * Use this filter hook to add HTML in the footer/at the end of the Guide.
		 *
		 * @param string  $footer	HTML at the end of guide
		 * @param string  $settings	Settings object
		 * @return string $footer	HTML at the end of guide
		 */
		$footer='';
		$footer.=apply_filters( 'doc_footer', $footer, $this->settings  );
		
		$html .=$footer.'</div><!--/.document-wrapper-->';
		$html .='</div>';
		$html .='<div class="cleardiv'.$clearclass.'"> </div><div id="documentor-'.$this->docid.'-end"></div>' ;
			
		return $html;
	}
	
	/* function to get social share buttons */
	public function get_social_buttons( $sharelink = '', $sharetitle ='' ) {
		$html = '';
		if( empty( $sharetitle ) ) $sharetitle = $this->info->name;
		if( empty( $sharelink ) )$sharelink = documentor_lite_get_currurl();
		$btnposition = $this->settings->sbutton_position;
		$html .='<div class="doc-socialshare doc-noprint '. $btnposition .'">
			<div class="doc-sharelink" data-sharelink="'.urlencode( $sharelink ).'"></div>';
		$btnclass = $this->settings->sbutton_style;
		$i = 1;
		//facebook button
		if( $this->settings->socialbuttons[0] == 1 ) {
			$fbtnclass = '';
			if( $i == 1 ) $fbtnclass = ' doc-fsbtn';
			$i++;
			$html .='<div class="sbutton doc-fb-share '.$btnclass.$fbtnclass.'" id="doc_fb_share"><a rel="nofollow" href="http://www.facebook.com/sharer.php?u='. $sharelink  .'&amp;title='. htmlspecialchars(urlencode(html_entity_decode($sharetitle, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') .'" title="Share to Facebook" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="cs c-icon-doc-facebook"></i></a>';
			if( $this->settings->sharecount == 1 ) {
				$html .='<span class="doc-socialcount" id="doc-fb-count"><i class="cs c-icon-doc-spinner animate-spin"></i></span>';
			}
			$html .='</div>';
		}
		//twitter button
		if( $this->settings->socialbuttons[1] == 1 ) {
			$fbtnclass = '';
			if( $i == 1 ) $fbtnclass = ' doc-fsbtn';
			$i++;
			$html .='<div class="sbutton doc-twitter-share '.$btnclass.$fbtnclass.'" id="doc_twitter_share"><a rel="nofollow" href="http://twitter.com/share?text='. htmlspecialchars(urlencode(html_entity_decode($sharetitle, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') .'&amp;url='. urlencode($sharelink) .'" title="Share to Twitter" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="cs c-icon-doc-twitter"></i></a>';
			if( $this->settings->sharecount == 1 ) {
				$html .= '<span class="doc-socialcount" id="doc-twitter-count"><i class="cs c-icon-doc-spinner animate-spin"></i></span>';
			}
			$html .= '</div>';
		}
		//google plus button
		if( $this->settings->socialbuttons[2] == 1 ) {
			$fbtnclass = '';
			if( $i == 1 ) $fbtnclass = ' doc-fsbtn';
			$i++;
			$html .='<div class="sbutton doc-gplus-share '.$btnclass.$fbtnclass.'" id="doc_gplus_share"><a rel="nofollow" href="https://plus.google.com/share?url='.urlencode($sharelink).'" title="Share to Google Plus" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="cs c-icon-doc-gplus"></i></a>';
			if( $this->settings->sharecount == 1 ) {
				$gpluscount = $this->get_plusones( $sharelink );
				$html .= '<span class="doc-socialcount" id="doc-gplus-count" data-gpluscnt="'.$gpluscount.'"><i class="cs c-icon-doc-spinner animate-spin"></i></span>';
			}
			$html .= '</div>';
		}
		//pinterest button
		if( $this->settings->socialbuttons[3] == 1 ) {
			$fbtnclass = '';
			if( $i == 1 ) $fbtnclass = ' doc-fsbtn';
			$i++;
			$html .='<div class="sbutton doc-pin-share '.$btnclass.$fbtnclass.'" id="doc_pin_share"><a rel="nofollow" href="http://pinterest.com/pin/create/bookmarklet/?url='.urlencode($sharelink) .'&amp;description='. htmlspecialchars(urlencode(html_entity_decode($sharetitle, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') .'" title="Share to Pinterest" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="cs c-icon-doc-pinterest"></i></a>';
			if( $this->settings->sharecount == 1 ) {
				$html .= '<span class="doc-socialcount" id="doc-pin-count"><i class="cs c-icon-doc-spinner animate-spin"></i></span>';
			}
			$html .= '</div>';
		}
		$html .='</div>';
		return $html;
	}
	/* Get google plus share count */
	public function get_plusones( $url )  {
		if( function_exists('curl_version') ) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.rawurldecode($url).'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ");
			$curl_results = curl_exec ($curl);
			curl_close ($curl);
			$json = json_decode($curl_results, true);
			return isset($json[0]['result']['metadata']['globalCounts']['count'])?intval( $json[0]['result']['metadata']['globalCounts']['count'] ):0;
		} else {
			return 0;
		}
	}
	
	/**
	 * Check if file exists out there in the upload folder.
	 *
	 * @param string $url - preferably a fully qualified URL
	 * @return boolean - true if it is out there somewhere
	 */
	function docFileExists( $url ) {
	    if ( ($url == '') || ($url == null) ) { 
			return false; 
		}
	    $response = wp_remote_head( $url, array( 'timeout' => 5 ) );
	    $accepted_status_codes = array( 200, 301, 302 );
	   
	   if ( ! is_wp_error( $response ) && in_array( wp_remote_retrieve_response_code( $response ), $accepted_status_codes ) ) {
			return true;
	    }
	    return false;
	}
}//class ends
?>