<?php 
class DocumentorLiteGuide{
		public $docid;
		public $title='';
		public $settings='';
		
		function __construct($id=0) {
			$this->docid=$id;
			if($this->docid>0) {
				global $table_prefix, $wpdb;
				
				//ver1.4 start
				$postid = $this->get_guide_post_id($this->docid);
				if( isset($postid) and intval($postid)>0 ) {
					$guiderow = $wpdb->get_row( $wpdb->prepare( "SELECT post_title,post_date FROM ".$table_prefix."posts WHERE ID = %d", $postid ) );
					if(count($guiderow) > 0){
						$settings=get_post_meta($postid,'_doc_settings',true);
						if( $settings != NULL ) {
							$settings = json_decode($settings, true);
						}
						else{
							$settings = array();
						}
						$settings = $this->populate_documentor_current($settings); 
						
						$sections_order=get_post_meta($postid,'_doc_sections_order',true);				
						$row=(object)array(
							'doc_id'=>$this->docid,
							'doc_title'=>$guiderow->post_title,
							'created_on'=>$guiderow->post_date,
							'sections_order'=>$sections_order,
							'settings'=>$settings 
						);
						$this->title=$row->doc_title;
						$this->doc_title=$row->doc_title;
						$this->settings=$row->settings;
						$this->sections_order=$row->sections_order;
						$this->created_on =$row->created_on;
					}
				}
				else { //if guide post type did not get created properly for this particular guide
					$guide = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$table_prefix.DOCUMENTORLITE_TABLE." WHERE doc_id = %d", $this->docid ) );
					if( count($guide) > 0 and ( !isset($guide->post_id) or $guide->post_id=='0' or (FALSE === get_post_status( $guide->post_id )) ) ) {
						$default_documentor_settings = documentor_lite_default_settings();
						$created_on=date('Y-m-d H:i:s', strtotime("now"));
						$guide->doc_title='Documentor Guide';
						$guide->sections_order='';
						$post= array(
							'post_title'=>$guide->doc_title,
							'post_type'=>'guide',
							'post_status'=>'publish',
							'post_content'=>'[documentor '.$guide->doc_id.']',
							'post_date'=> $created_on
							);
						$post_id=wp_insert_post( $post );
						$wpdb->update( 
							$table_prefix.DOCUMENTORLITE_TABLE, 
							array( 
								'post_id' => $post_id	
							), 
							array( 'doc_id' => $guide->doc_id ), 
							array( 
								'%d'
							), 
							array( '%d' ) 
						);		
						if( isset( $guide->settings ) ){
							$curr_settings = json_decode( $guide->settings, true );
						}
						else{
							$curr_settings = array();
						}
						
						$curr_settings = $this->populate_documentor_current($curr_settings);
						
						$curr_settings = json_encode($curr_settings);
						
						update_post_meta($post_id,'_doc_settings',$curr_settings);
						update_post_meta($post_id,'_doc_sections_order',$guide->sections_order);
						
						$this->title=$guide->doc_title;
						$this->doc_title=$guide->doc_title;
						$this->settings=$curr_settings;
						$this->sections_order=$guide->sections_order;
						$this->created_on=$created_on;
					}
				}
			} //ver1.4End	
		}
		// use to get postid from documentor table
		function get_guide_post_id($docid){ //ver1.4
		   global $wpdb,$table_prefix;
		   $post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM ".$table_prefix.DOCUMENTORLITE_TABLE." WHERE doc_id = %d", $docid ) );
		   return $post_id;
		}	
		//get guide
		function get_guide( $docid ) { //ver1.4
			global $table_prefix, $wpdb;
			$postid = $this->get_guide_post_id($docid);			
			$row = $wpdb->get_row( $wpdb->prepare( "SELECT post_title,post_date FROM $wpdb->posts WHERE ID = %d", $postid ) );	
			
			$settings=get_post_meta($postid,'_doc_settings',true);
			if( $settings != NULL ) {
				$settings = json_decode($settings, true);
			}
			else{
				$settings = array();
			}
			$settings = $this->populate_documentor_current($settings);
			
			$sections_order=get_post_meta($postid,'_doc_sections_order',true);
			if(isset($row->post_title) && isset($row->post_date)){
				$guide= (object) array(
					'post_id'=>$postid,
					'doc_id'=>$this->docid,
					'doc_title'=>$row->post_title,
					'created_on'=>$row->post_date,
					'sections_order'=>$sections_order,
					'settings'=>$settings, 
				);
				return $guide;
			} else {
				return null;
			}		 	
		}
	    	//update settings       
		function update_settings( $setting, $newtitle ) { //ver1.4 start
			global $wpdb, $table_prefix;
			$postid = $this->get_guide_post_id($this->docid);	
			//$id = $postid;
			$guide_title = $wpdb->get_row( $wpdb->prepare( "SELECT post_title FROM ".$table_prefix."posts WHERE ID = %d", $postid ) );
			if($guide_title != $newtitle ) {
				$update_post= array( 
						'ID' => $postid,	
						'post_title' => $newtitle
						
						);
				wp_update_post( $update_post );
			}
			update_post_meta($postid,'_doc_settings',$setting);
		}
		//get settings ver1.4end
		function get_settings() {
			global $table_prefix, $wpdb;
			$postid = $this->get_guide_post_id($this->docid); //ver1.4	
			$result=get_post_meta($postid,'_doc_settings',true);	//ver1.4
			if( $result != NULL ) {
				$documentor_curr = json_decode($result, true);
			}
			else{
				$documentor_curr = array();
			}
			$documentor_curr = $this->populate_documentor_current($documentor_curr); 
			
			return $documentor_curr;
		}
		//get sections of document
		function get_sections() {
			global $table_prefix, $wpdb;
			$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$table_prefix.DOCUMENTORLITE_SECTIONS." WHERE doc_id = %d",$this->docid ) ); 
			return $result;
		}
		//get guide managers email_id
		function get_guideManager_emails( $uidarr ) {
			global $table_prefix, $wpdb;
			$htmlnm = '';
			$i = 0; $cnt = count( $uidarr );
			foreach( $uidarr as $uid ) {
				$i++;
				$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$table_prefix."users WHERE ID= %d", $uid ) );
				if( !empty( $result->user_email ) ) {
					if( $i == $cnt ) {
						$htmlnm .= $result->user_email;
					} else {
						$htmlnm .= $result->user_email.',';
					}
				}
			} 
			return $htmlnm;
		}
		//get ip address of user
		function getRealIpAddr(){
			foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
			{
				if (array_key_exists($key, $_SERVER) === true)
				{
					foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip)
					{
						if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
						{
							return $ip;
						}
					}
				}
			}
			return '';
		}
		//create guide
		function create() {
			if( isset( $_POST['guidetitle'] ) ) {
				global $table_prefix, $wpdb;
				$guidetitle = sanitize_text_field( $_POST['guidetitle'] );
				$doc = new DocumentorLite();
				if( isset($_POST['settings']) ) {
					if($_POST['settings'] == '0') {
						$settings = $doc->default_documentor_settings;
						$ser_settings = json_encode( $settings );
					}
					else {
						$settings = $this->get_guide( $_POST['settings'] )->settings;
						$ser_settings = $settings;
					}
				}
				else {
					$settings = $doc->default_documentor_settings;
					$ser_settings = json_encode( $settings );
				}
				$post = array(
					'post_title'    => $guidetitle,					
					'post_type'	=> 'guide',
					'post_status'	=> 'publish'					
				);				
				$postid = wp_insert_post( $post );
				add_post_meta($postid,'_doc_settings',$ser_settings,true);	//ver1.4	
				$wpdb->insert( 
					$table_prefix.DOCUMENTORLITE_TABLE, 
					array(
						'post_id'=> $postid
					), 
					array( 
						'%d'
					) 
				);//ver1.4
				
				$this->docid=$wpdb->insert_id;
							
				return $this->docid;
			}
			else {
				return 0;
			}
		}
		//delete guide
		function delete() {
			global $table_prefix, $wpdb;
			$postid= $this->get_guide_post_id($this->docid); //ver1.4			
			$wpdb->delete( $wpdb->posts, array( 'ID' => $postid ), array( '%d' ) ); //ver1.4
			$wpdb->delete( $table_prefix.DOCUMENTORLITE_TABLE, array( 'doc_id' => $this->docid ), array( '%d' ) );
			// delete from post_meta tabel
			delete_post_meta($postid,'_doc_settings');				
			delete_post_meta($postid,'_doc_sections_order');
			
			//delete from documentor section table
			$wpdb->delete( $table_prefix.DOCUMENTORLITE_SECTIONS, array( 'doc_id' => $this->docid ), array( '%d' ) );
		}
		//show document on front 
		function view( $args=array() ) {
			$html = '';
			$settings_arr = $this->get_settings();
			if( count( $settings_arr ) > 0 ) {
				require_once(dirname(dirname (__FILE__)) . '/skins/'.$settings_arr["skin"].'/index.php');
				$classname = 'DocumentorDisplay'.$settings_arr["skin"];
				$displayobj = new $classname( $this->docid );
				$html = $displayobj->view( $args );
			}
			$html=apply_filters('guide_html', $html);
			return $html;
		}
		//build sections html on admin
		function buildItem($obj) {
			if(isset($this->docid)) {
				if( class_exists( 'DocumentorLiteSection' ) && is_admin() ) {
					$id = $this->docid;
					$ds = new DocumentorLiteSection( $id, $obj->id);
				}
			}
			$settings = $this->get_settings();
			$html = "";
			if( $ds != null ) {
			$sectiondata = $ds->getdata();
			
			foreach( $sectiondata as $secdata ) {	
			if( $secdata->type == 0 ) {
				$type = 'Inline';
			} else if( $secdata->type == 1 ) {
				$type = 'Post';
			} else if( $secdata->type == 2 ) {
				$type = 'Page';
			} else if( $secdata->type == 3 ) {
				$type = 'Link';
			}
			$postid = $secdata->post_id;
			$menutitle = '';
			//WPML
			if( function_exists('icl_plugin_action_links') ) {	
				if( $secdata->type == 0 ) $ptype = 'documentor-sections';
				else if( $secdata->type == 1 ) $ptype = 'post';
				else if( $secdata->type == 2 ) $ptype = 'page';
				else if( $secdata->type == 3 ) $ptype = 'nav_menu_item';
				else if( $secdata->type == 4 ) {
					$ptype = get_post_type( $postid );
				}
				$lang_post_id = icl_object_id( $postid , $ptype, true, ICL_LANGUAGE_CODE );
				$postdata = get_post( $lang_post_id );
				$postid = $lang_post_id;
			} else {
				$postdata = get_post( $postid );
			}
			
			$ptype = get_post_type( $postid );
			$postTypeObj = get_post_type_object( $ptype );
			if ( $postTypeObj ) {
				$postTypeName=esc_html($postTypeObj->labels->singular_name);
			}

			if( $secdata->type == 4 ) {
				$type = $postdata->post_type;
			}
			if( $secdata->type != 3 ) {
				$menutitle = get_post_meta( $postid, '_documentor_menutitle', true );
			} else if( $secdata->type == 3 ) {
				if( $postdata != NULL )
					$menutitle = $postdata->post_title;
			}
			$sectiontitle = get_post_meta( $postid, '_documentor_sectiontitle', true );
			$html .= '<li class="table-row oldrow ds-close" data-id="'. $obj->id . '" id="' . $obj->id . '">';
			$html .= '<div class="doc-list"><button class="sectiont_img ds-close dd-nodrag" type="button" ></button>';
			$html .= '<div class="table-col slide-title">
					<p class="this-title" >'.$menutitle;
					$html.= '<span class="item-controls">
							<span class="item-type">'.$postTypeName.'</span>
						</span>
					</p>
				  </div>';
				  $html .= '<div class="section-form dd-nodrag" style="display:none;">';
					//if not link section and user having capability to edit post
					$ptype = strtolower( $type );
					if( $type == 'Inline' ) $ptype = 'documentor-sections';
					if( post_type_exists($ptype) ) {
						if( ( $secdata->type != 3 ) && current_user_can('edit_post', $postid) ) {  
							$edtlink = get_edit_post_link($postid);
							$html .= '<a href="'.$edtlink.'" target="_blank" class="section-editlink">'. __('Edit','documentor-lite').'</a>';
							$html .= '<a href="'.$edtlink.'#commentsdiv" target="_blank" class="section-commentslink">'. __('Comments/Feedback','documentor-lite').'</a>';
						}
					}
					$html .= '<div class="sections-div">
						<label class="titles">'. __('Menu Title','documentor-lite').'</label>
						<input type="text" name="menutitle" class="input txts menutitle" placeholder="'. __('Enter Menu Title','documentor-lite').'" value="'.esc_attr($menutitle).'" />';
						if( $secdata->type != 3 ) { //if section is not link
						$html .='<label class="titles">'. __('Section Title','documentor-lite').'</label>
						<input type="text" name="sectiontitle" class="input txts sectiontitle" placeholder="'. __('Enter Menu Title','documentor-lite').'" value="'.esc_attr($sectiontitle).'" />';
						}
						if( $secdata->type == 3 ) { //if section is link
							$content = unserialize( $postdata->post_content );
							$html.='<label class="titles">'. __('Link','documentor-lite').'</label>
							<input type="text" name="linkurl" class="input txts linkurl" placeholder="http://" value="'.esc_url($content['link']).'" />';
							$targetwval = ( $content['new_window'] != '0' ) ? "1":"0";
							$newwindow = ( $content['new_window'] != '0' ) ? 'checked="checked"':"";
							$html.='<label class="titles">'. __('Open in new window','documentor-lite').'</label><input type="checkbox" name="new_window" class="new_window" '.$newwindow.' /><input type="hidden" name="targetw" class="targetw" value="'.esc_attr($targetwval).'">';
						}
						$html.='<div class="clrleft"></div>
						<div class="sections-div">
							<label class="titles">'. __('Feedback count','documentor-lite').'</label>
							<div class="feedback-cnt">
								<span class="dashicons dashicons-smiley" title="'. __('Upvotes','documentor-lite').'"></span>
								<span class="vote-cnt upvote">'.$secdata->upvote.'</span>
								<span class="dashicons dashicons-arrow-down-alt2 down" title="'. __('Downvotes','documentor-lite').'"></span>
								<span class="vote-cnt downvote">'.$secdata->downvote.'</span>
								<input type="submit" name="reset_feedbackcnt" class="reset-feedbackcnt link-button" value="'. __('Reset','documentor-lite').'" /><span class="reset-success"></span>
							</div>
						</div>
						<div class="description-wide submitbox">
								<input type="hidden" name="section_id" class="section_id" value="'.esc_attr($secdata->sec_id).'">
								<input type="hidden" name="post_id" class="post-id" value="'.esc_attr($postid).'">
								<input type="hidden" name="type" class="ptype" value="'.esc_attr($secdata->type).'">
							   	<input type="hidden" name="docid" class="docid" value="'.esc_attr($secdata->doc_id).'">
								<input type="submit" name="update_section" class="update-section button-primary" value="'. __('Save','documentor-lite').'" />
								<span class="meta-sep hide-if-no-js"> | </span>
								<a class="remove-section link-button" href="#confirmdelete-'.$secdata->sec_id.'" >'. __('Remove','documentor-lite').'</a> 
								<span class="meta-sep hide-if-no-js"> | </span>
								<input type="submit" name="cancel_section" class="cancel-section link-button" value="'. __('Cancel','documentor-lite').'" /> 
								<span class="docloader"></span>
								<div id="confirmdelete-'.$secdata->sec_id.'" class="confirmdelete" >
									<div class="doc-popupcontent text">Do you want to delete all children sections ?</div> <div class="doc-popupcontent"><button class="delete_child btn-delete">Delete children</button><button class="keep_child btn-cancel">Keep children</button></div></div>	
								<div class="validation-msg"></div>
						</div>
					
					</div>
				</div></div>';
					
			if ( isset( $obj->children ) && $obj->children ) {
				$html .= '<ol class="dd-list">';
				foreach( $obj->children as $child ) {
				    $html .= $this->buildItem($child);
				}
				$html .= '</ol>';
			}

			$html .= '</li>';
			}
			
			}
			return $html;
		}
		//
		function get_inline_css() {
			$settings = $this->get_settings();
			$cssarr = array(
					'navmenu' => '',
					'sectitle' => '',
					'sectioncontent'=>'',
					'guidetitle' => '',
				);
			$style_start= 'style="';
			$style_end= '"';
			$objfonts = new DocumentorLiteFonts();
			//section title
			//check for use theme default option
			if( $settings['sectitle_default'] == 0 ) {
				if ($settings['sectitle_fstyle'] == "bold" or $settings['sectitle_fstyle'] == "bold italic" ){
					$sectitle_fweight = "bold";
				} else {
					$sectitle_fweight = "normal";
				}
				if ($settings['sectitle_fstyle'] == "italic" or $settings['sectitle_fstyle'] == "bold italic"){
					$sectitle_fstyle = "italic";
				} else {
					$sectitle_fstyle = "normal";
				}
			
				if( $settings['sect_font'] == 'regular' ) {
					$sect_font = $settings['sectitle_font'].', helvetica, Helvetica, sans-serif';
					$pt_fontw = $sectitle_fweight;
					$pt_fontst = $sectitle_fstyle;
				} else if( $settings['sect_font'] == 'google' ) {
					$sectitle_fontg = isset($settings['sectitle_fontg']) ? trim($settings['sectitle_fontg']) : '';
					$pgfont = $objfonts->get_google_font($settings['sectitle_fontg']);
					( isset( $pgfont['category'] ) ) ? $ptfamily = $pgfont['category'] : '';
					( isset( $settings['sectitle_fontgw'] ) ) ? $ptfontw = $settings['sectitle_fontgw'] : ''; 
					if (strpos($ptfontw,'italic') !== false) {
						$pt_fontst = 'italic';
					} else {
						$pt_fontst = 'normal';
					}
					if( strpos($ptfontw,'italic') > 0 ) { 
						$len = strpos($ptfontw,'italic');
						$ptfontw = substr( $ptfontw, 0, $len );
					}
					if( strpos($ptfontw,'regular') !== false ) { 
						$ptfontw = 'normal';
					}
					if( isset($settings['sectitle_fontgw']) && !empty($settings['sectitle_fontgw']) ) {
						$currfontw=$settings['sectitle_fontgw'];
						$gfonturl = $pgfont['urls'][$currfontw];
			
					}  else {
						$gfonturl = 'http://fonts.googleapis.com/css?family='.$settings['sectitle_fontg'];
					}
					if( isset($settings['sectitle_fontgsubset']) && !empty($settings['sectitle_fontgsubset']) ) {
						$strsubset = implode(",",$settings['sectitle_fontgsubset']);
						$gfonturl = $gfonturl.'&subset='.$strsubset;
					} 
					if(!empty($sectitle_fontg)) {
						wp_enqueue_style( 'documentor_sectitle', $gfonturl,array(),DOCUMENTORLITE_VER);
						$sectitle_fontg=$pgfont['name'];
						$sect_font = $sectitle_fontg.','.$ptfamily;
						$pt_fontw = $ptfontw;	
					}
					else { //if not set google font fall back to default font
				
						$sect_font = 'helvetica, Helvetica, sans-serif';
						$pt_fontw = 'normal';
						$pt_fontst = 'normal';
					}
				} else if( $settings['sect_font'] == 'custom' ) {
					$sect_font = $settings['ptfont_custom'];
					$pt_fontw = $sectitle_fweight;
					$pt_fontst = $sectitle_fstyle;
				}
				$tcss = '';
				if( $settings['skin'] == 'mint' ) { $tcss = 'border-bottom: 1px dotted #e6e6e6;'; }
				if( $settings['skin'] == 'bar' ) {
					$tcss = 'margin: 40px 0px 20px 0px;padding-bottom: 9px;border-bottom: 1px dotted #e6e6e6;'; 
				}
				$lineheight = $settings['sectitle_fsize'] + 5;
				if( $settings['skin'] != 'default' || $settings['skin'] != 'cherry' ) $lineheight = $settings['sectitle_fsize'] + 8;
				$cssarr['sectitle']=$style_start.'clear:none;line-height:'. $lineheight .'px;font-family:'. $sect_font.';font-size:'.$settings['sectitle_fsize'].'px;font-weight:'.$pt_fontw.';font-style:'.$pt_fontst.';color:'.$settings['sectitle_color'].';'.$tcss.$style_end;
			}
			//navigation menu
			//check for use theme default option
			if( $settings['navmenu_default'] == 0 ) {
				if ($settings['navmenu_fstyle'] == "bold" or $settings['navmenu_fstyle'] == "bold italic" ){
					$navmenu_fweight = "bold";
				} else {
					$navmenu_fweight = "normal";
				}
				if ($settings['navmenu_fstyle'] == "italic" or $settings['navmenu_fstyle'] == "bold italic"){
					$navmenu_fstyle = "italic";
				} else {
					$navmenu_fstyle = "normal";
				}
			
				if( $settings['navt_font'] == 'regular' ) {
					$navt_font = $settings['navmenu_tfont'].', helvetica, Helvetica, sans-serif';
					$pt_fontw = $navmenu_fweight;
					$pt_fontst = $navmenu_fstyle;
				} else if( $settings['navt_font'] == 'google' ) {
					$navmenu_tfontg = isset($settings['navmenu_tfontg']) ? trim($settings['navmenu_tfontg']) : '';
					$pgfont = $objfonts->get_google_font($settings['navmenu_tfontg']);
					( isset( $pgfont['category'] ) ) ? $ptfamily = $pgfont['category'] : '';
					( isset( $settings['navmenu_tfontgw'] ) ) ? $ptfontw = $settings['navmenu_tfontgw'] : ''; 
					if (strpos($ptfontw,'italic') !== false) {
						$pt_fontst = 'italic';
					} else {
						$pt_fontst = 'normal';
					}
					if( strpos($ptfontw,'italic') > 0 ) { 
						$len = strpos($ptfontw,'italic');
						$ptfontw = substr( $ptfontw, 0, $len );
					}
					if( strpos($ptfontw,'regular') !== false ) { 
						$ptfontw = 'normal';
					}
					if( isset($settings['navmenu_tfontgw']) && !empty($settings['navmenu_tfontgw']) ) {
						$currfontw=$settings['navmenu_tfontgw'];
						$gfonturl = $pgfont['urls'][$currfontw];
			
					}  else {
						$gfonturl = 'http://fonts.googleapis.com/css?family='.$settings['navmenu_tfontg'];
					}
					if( isset($settings['navmenu_tfontgsubset']) && !empty($settings['navmenu_tfontgsubset']) ) {
						$strsubset = implode(",",$settings['navmenu_tfontgsubset']);
						$gfonturl = $gfonturl.'&subset='.$strsubset;
					} 
					if(!empty($navmenu_tfontg)) {
						wp_enqueue_style( 'documentor_navmenutitle', $gfonturl,array(),DOCUMENTORLITE_VER);
						$navmenu_tfontg=$pgfont['name'];
						$navt_font = $navmenu_tfontg.','.$ptfamily;
						$pt_fontw = $ptfontw;	
					}
					else { //if not set google font fall back to default font
				
						$navt_font = 'helvetica, Helvetica, sans-serif';
						$pt_fontw = 'normal';
						$pt_fontst = 'normal';
					}
				} else if( $settings['navt_font'] == 'custom' ) {
					$navt_font = $settings['ptfont_custom'];
					$pt_fontw = $navmenu_fweight;
					$pt_fontst = $navmenu_fstyle;
				}
				$cssarr['navmenu']=$style_start.'clear:none;line-height:'. ($settings['navmenu_fsize'] + 5) .'px;font-family:'. $navt_font.';font-size:'.$settings['navmenu_fsize'].'px;font-weight:'.$pt_fontw.';font-style:'.$pt_fontst.';color:'.$settings['navmenu_color'].';'.$style_end;
				//print_r($settings['navmenu_color']);
			}
			//section content
			//check for use theme default option
			if( $settings['seccont_default'] == 0 ) {
				if ($settings['seccont_fstyle'] == "bold" or $settings['seccont_fstyle'] == "bold italic" ){
					$sectitle_fweight = "bold";
				} else {
					$sectitle_fweight = "normal";
				}
				if ($settings['seccont_fstyle'] == "italic" or $settings['seccont_fstyle'] == "bold italic"){
					$seccont_fstyle = "italic";
				} else {
					$seccont_fstyle = "normal";
				}
			
				if( $settings['secc_font'] == 'regular' ) {
					$secc_font = $settings['seccont_font'].', helvetica, Helvetica, sans-serif';
					$pt_fontw = $sectitle_fweight;
					$pt_fontst = $seccont_fstyle;
				} else if( $settings['secc_font'] == 'google' ) {
					$seccont_fontg = isset($settings['seccont_fontg']) ? trim($settings['seccont_fontg']) : '';
					$pgfont = $objfonts->get_google_font($settings['seccont_fontg']);
					( isset( $pgfont['category'] ) ) ? $ptfamily = $pgfont['category'] : '';
					( isset( $settings['seccont_fontgw'] ) ) ? $ptfontw = $settings['seccont_fontgw'] : ''; 
					if (strpos($ptfontw,'italic') !== false) {
						$pt_fontst = 'italic';
					} else {
						$pt_fontst = 'normal';
					}
					if( strpos($ptfontw,'italic') > 0 ) { 
						$len = strpos($ptfontw,'italic');
						$ptfontw = substr( $ptfontw, 0, $len );
					}
					if( strpos($ptfontw,'regular') !== false ) { 
						$ptfontw = 'normal';
					}
					if( isset($settings['seccont_fontgw']) && !empty($settings['seccont_fontgw']) ) {
						$currfontw=$settings['seccont_fontgw'];
						$gfonturl = $pgfont['urls'][$currfontw];
			
					}  else {
						$gfonturl = 'http://fonts.googleapis.com/css?family='.$settings['seccont_fontg'];
					}
					if( isset($settings['seccont_fontgsubset']) && !empty($settings['seccont_fontgsubset']) ) {
						$strsubset = implode(",",$settings['seccont_fontgsubset']);
						$gfonturl = $gfonturl.'&subset='.$strsubset;
					} 
					if(!empty($seccont_fontg)) {
						wp_enqueue_style( 'documentor_seccontent', $gfonturl,array(),DOCUMENTORLITE_VER);
						$seccont_fontg=$pgfont['name'];
						$secc_font = $seccont_fontg.','.$ptfamily;
						$pt_fontw = $ptfontw;	
					}
					else { //if not set google font fall back to default font
				
						$secc_font = 'helvetica, Helvetica, sans-serif';
						$pt_fontw = 'normal';
						$pt_fontst = 'normal';
					}
				} else if( $settings['secc_font'] == 'custom' ) {
					$secc_font = $settings['ptfont_custom'];
					$pt_fontw = $sectitle_fweight;
					$pt_fontst = $seccont_fstyle;
				}
				$lineheight = $settings['seccont_fsize'] + 5;
				if( $settings['skin'] != 'default' || $settings['skin'] != 'cherry' ) $lineheight = $settings['seccont_fsize'] + 9;
				$cssarr['sectioncontent']=$style_start.'clear:none;line-height:'. $lineheight .'px;font-family:'. $secc_font.';font-size:'.$settings['seccont_fsize'].'px;font-weight:'.$pt_fontw.';font-style:'.$pt_fontst.';color:'.$settings['seccont_color'].';'.$style_end;
			}
			//guide title css
			if( $settings['guidet_default'] == 0 ) {
				if ($settings['guidet_fstyle'] == "bold" or $settings['guidet_fstyle'] == "bold italic" ){
					$guidet_fweight = "bold";
				} else {
					$guidet_fweight = "normal";
				}
				if ($settings['guidet_fstyle'] == "italic" or $settings['guidet_fstyle'] == "bold italic"){
					$guidet_fstyle = "italic";
				} else {
					$guidet_fstyle = "normal";
				}
			
				if( $settings['guidet_font'] == 'regular' ) {
					$guidetfont = $settings['guidetitle_font'].', helvetica, Helvetica, sans-serif';
					$gt_fontw = $guidet_fweight;
					$gt_fontst = $guidet_fstyle;
				} else if( $settings['guidet_font'] == 'google' ) {
					$guidet_fontg = isset($settings['guidet_fontg']) ? trim($settings['guidet_fontg']) : '';
					$pgfont = $objfonts->get_google_font($settings['guidet_fontg']);
					( isset( $pgfont['category'] ) ) ? $ptfamily = $pgfont['category'] : '';
					( isset( $settings['guidet_fontgw'] ) ) ? $ptfontw = $settings['guidet_fontgw'] : ''; 
					if (strpos($ptfontw,'italic') !== false) {
						$gt_fontst = 'italic';
					} else {
						$gt_fontst = 'normal';
					}
					if( strpos($ptfontw,'italic') > 0 ) { 
						$len = strpos($ptfontw,'italic');
						$ptfontw = substr( $ptfontw, 0, $len );
					}
					if( strpos($ptfontw,'regular') !== false ) { 
						$ptfontw = 'normal';
					}
					if( isset($settings['guidet_fontgw']) && !empty($settings['guidet_fontgw']) ) {
						$currfontw=$settings['guidet_fontgw'];
						$gfonturl = $pgfont['urls'][$currfontw];
			
					}  else {
						$gfonturl = 'http://fonts.googleapis.com/css?family='.$settings['guidet_fontg'];
					}
					if( isset($settings['guidet_fontgsubset']) && !empty($settings['guidet_fontgsubset']) ) {
						$strsubset = implode(",",$settings['guidet_fontgsubset']);
						$gfonturl = $gfonturl.'&subset='.$strsubset;
					} 
					if(!empty($guidet_fontg)) {
						wp_enqueue_style( 'documentor_guidetitle', $gfonturl,array(),DOCUMENTORLITE_VER);
						$guidet_fontg=$pgfont['name'];
						$guidetfont = $guidet_fontg.','.$ptfamily;
						$gt_fontw = $ptfontw;	
					}
					else { //if not set google font fall back to default font
				
						$guidetfont = 'helvetica, Helvetica, sans-serif';
						$gt_fontw = 'normal';
						$gt_fontst = 'normal';
					}
				} else if( $settings['guidet_font'] == 'custom' ) {
					$guidetfont = $settings['ptfont_custom'];
					$gt_fontw = $guidet_fweight;
					$gt_fontst = $guidet_fstyle;
				}
				$lineheight = $settings['guidet_fsize'] + 5;
				$cssarr['guidetitle']=$style_start.'clear:none;line-height:'. $lineheight .'px;font-family:'. $guidetfont.';font-size:'.$settings['guidet_fsize'].'px;font-weight:'.$gt_fontw.';font-style:'.$gt_fontst.';color:'.$settings['guidet_color'].';'.$style_end;
			}
			return $cssarr;
		}
		
		public static function documentorRemoveAnchors($data) {
			$regex  = '/(<a\s*'; // Start of anchor tag
			$regex .= '(.*?)\s*'; // Any attributes or spaces that may or may not exist
			$regex .= 'href=[\'"]+?\s*(?P<link>\S+)\s*[\'"]+?'; // Grab the link
			$regex .= '\s*(.*?)\s*>\s*'; // Any attributes or spaces that may or may not exist before closing tag 
			$regex .= '(?P<name>\s*(.*?)\s*)'; // Grab the name
			$regex .= '\s*<\/a>)/i'; // Any number of spaces between the closing anchor tag (case insensitive)
			if (is_array($data)) {
				// This is what will replace the link (modify to you liking)
				$data = "{$data['name']}";			
			}
			return preg_replace_callback($regex, 'DocumentorLiteGuide::documentorRemoveAnchors', $data);
		}
		
		public static function documentorReplaceAnchorsWithText($data){
			$regex  = '/(<a\s*'; // Start of anchor tag
			$regex .= '(.*?)\s*'; // Any attributes or spaces that may or may not exist
			$regex .= 'href=[\'"]+?\s*(?P<link>\S+)\s*[\'"]+?'; // Grab the link
			$regex .= '\s*(.*?)\s*>\s*'; // Any attributes or spaces that may or may not exist before closing tag 
			$regex .= '(?P<name>\s*(.*?)\s*)'; // Grab the name
			$regex .= '\s*<\/a>)/i'; // Any number of spaces between the closing anchor tag (case insensitive)
			if (is_array($data)) {
				// This is what will replace the link (modify to you liking)
				$data = "{$data['name']}({$data['link']})"."<br />";				
			}
			return preg_replace_callback($regex, 'DocumentorLiteGuide::documentorReplaceAnchorsWithText', $data);
		}
			
		//get all sections html
		function get_sections_html() {
			global $table_prefix, $wpdb;
			$html='<input type="hidden" value="'.esc_attr($this->docid).'" name="docsid" />';
			$doc = new DocumentorLite();
			$settings = $doc->default_documentor_settings;
			$sections = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ".$table_prefix.DOCUMENTORLITE_SECTIONS." WHERE doc_id = %d",$this->docid ) );
			
			if( $sections ) {
			 $i = 1;
			 $postid= $this->get_guide_post_id($this->docid); //ver1.4
			 $obj = get_post_meta($postid,'_doc_sections_order',true); //ver1.4				 
				if( !empty($obj) ) {
					$jsonObj = json_decode($obj);
					$html.='<ol class="dd-list">';
					foreach( $jsonObj as $jobj ) {
						$html.= $this->buildItem($jobj);
					}					
					$html.='</ol><textarea name="reorders-output" id="reorders-output">'.$this->sections_order.'</textarea>';
				} 
			}
			echo $html;
			die();
		}
		function get_childrens( $element, $html, $docid ) {
			//$arrid = array();
			$guide = new DocumentorLiteGuide( $docid );
			foreach( $element as $valueKey => $value ) {
				foreach ( $value as $k => $v ) {
					if( $k == 'id' ) {
						 $html .= $v.",";
					} else if( $k == 'children' ) {
						$html = $guide->get_childrens( $v, $html, $docid );
					}
				}
			}
			return $html;
		}
		//save guide title
		public static function save_guideTitle() {
			check_ajax_referer( 'documentor-guide-nonce', 'documentor-guide-nonce' );
			global $table_prefix, $wpdb;
			$docid = ( isset( $_POST['docid'] ) ) ? intval($_POST['docid']) : '';
			$docpostid= ( isset( $_POST['doc_postid'] ) ) ? intval($_POST['doc_postid']) : '0';	
			$doc_title = ( isset( $_POST['guide-title'] ) ) ? sanitize_text_field($_POST['guide-title']) : '';
			if( empty( $doc_title ) ) {
				_e("Warning: Guide name cannot be blank","documentor");
			} else { 
				if( !empty( $docid ) ) { 
					$guide_title = $wpdb->get_row( $wpdb->prepare( "SELECT post_title FROM ".$table_prefix."posts WHERE ID = %d", $docpostid ) );
					if($guide_title != $doc_title ) {
						$update_post= array( 
							'ID' => $docpostid,
							'post_title' => $doc_title		
							);
						wp_update_post( $update_post );
					}
					_e("Guide name updated successfully!","documentor");
				}
			}
			die();
		}
		//save sections of guide
		public static function save_sections() {
			check_ajax_referer( 'documentor-sections-nonce', 'documentor-sections-nonce' );
			global $table_prefix, $wpdb;
			$sorders = ( isset( $_POST['reorders-output'] ) ) ? sanitize_text_field($_POST['reorders-output']) : '';	
			$docid = ( isset( $_POST['docid'] ) ) ? intval($_POST['docid']) : '';
			$docpostid= ( isset( $_POST['doc_postid'] ) ) ? intval($_POST['doc_postid']) : '0';
			//$sectionsarr = ( isset( $_POST['sectionObj'] ) ) ? $_POST['sectionObj'] : '';		
			$doc_title = ( isset( $_POST['guidename'] ) ) ? sanitize_text_field($_POST['guidename']) : '';
			if( empty( $doc_title ) ) {
				_e("Warning: Guide name cannot be blank","documentor");
			} else if( !empty( $docid ) ) { 
				//update sections order in documentor table
				$jarr = json_decode( stripslashes($sorders), true );
				if( count($jarr) > 0 ) {
					$sections_order = stripslashes_deep( $sorders );
				} else {
					$sections_order = '';
				}
				//ver1.4			
				$postid= $docpostid;
				update_post_meta($postid,'_doc_sections_order',$sections_order);
				//delete sections from sections table which are not in section order of documentor table
				
				//$sorders=get_post_meta($postid,'_doc_sections_order',true);
				$sorders=$sections_order;
				$jarr = json_decode( $sorders, true );	
				if( count($jarr) > 0 ) {
					$idstr = '';
					$guide = new DocumentorLiteGuide( $docid );
					foreach($jarr as $elementKey => $element) {
					    foreach($element as $valueKey => $value) {
						if( $valueKey == 'id' ){
							$idstr .= $value.",";
						} else if( $valueKey == 'children' ) {
							$idstr = $guide->get_childrens( $value, $idstr, $docid );
						}
					    }
					}
					$idstr = rtrim( $idstr , ',' );
					
					$delsql = "DELETE FROM ".$table_prefix.DOCUMENTORLITE_SECTIONS." WHERE sec_id NOT IN(".$idstr.") AND doc_id = ".$docid;
					$wpdb->query($delsql);
					
				} else {
					$wpdb->delete( $table_prefix.DOCUMENTORLITE_SECTIONS, array( 'doc_id' => $docid ), array( '%d' ) );
				}					
				$guide_title = $wpdb->get_row( $wpdb->prepare( "SELECT post_title FROM ".$table_prefix."posts WHERE ID = %d", $docpostid ) );
				if($guide_title != $doc_title ) {
					$update_post= array( 
						'ID' => $docpostid,
						'post_title' => $doc_title		
						);
					wp_update_post( $update_post );
				}
			}
			die();
		}
		//
		function encode_operation($string)
		{
			$chars = str_split($string);
			$seed = mt_rand(0, (int)abs(crc32($string) / strlen($string)));

			foreach($chars as $key => $char)
			{
				$ord = ord($char);

				// ignore non-ascii chars
				if($ord < 128)
				{
					// pseudo "random function"
					$r = ($seed * (1 + $key)) % 100;

					if($r > 60 && $char !== '@') {} // plain character (not encoded), if not @-sign
					elseif($r < 45) $chars[$key] = '&#x'.dechex($ord).';'; // hexadecimal
					else $chars[$key] = '&#'.$ord.';'; // decimal (ascii)
				}
			}

			return implode('', $chars);
		}
		//Get captcha
		function generate_captcha( $name, $tr_name ) {
			$ops = array(
				'addition' => '+',
				'subtraction' => '&#8722;',
				'multiplication' => '&#215;',
				'division' => '&#247;',
			);

			$operations = array();
			$input = '<input type="number" size="2" length="2" id="'.$name.'" class="doc-captcha numberinput" name="'.$name.'" value="" required="true"/>';

			// available operations
			$operations = array('addition',
					'subtraction' );
	
			// operation
			$rnd_op = $operations[mt_rand(0, count($operations) - 1)];
			$number[3] = $ops[$rnd_op];

			// place where to put empty input
			$rnd_input = mt_rand(0, 2);

			// which random operation
			switch($rnd_op)
			{
				case 'addition':
					if($rnd_input === 0)
					{
						$number[0] = mt_rand(1, 10);
						$number[1] = mt_rand(1, 89);
					}
					elseif($rnd_input === 1)
					{
						$number[0] = mt_rand(1, 89);
						$number[1] = mt_rand(1, 10);
					}
					elseif($rnd_input === 2)
					{
						$number[0] = mt_rand(1, 9);
						$number[1] = mt_rand(1, 10 - $number[0]);
					}

					$number[2] = $number[0] + $number[1];
					break;

				case 'subtraction':
					if($rnd_input === 0)
					{
						$number[0] = mt_rand(2, 10);
						$number[1] = mt_rand(1, $number[0] - 1);
					}
					elseif($rnd_input === 1)
					{
						$number[0] = mt_rand(11, 99);
						$number[1] = mt_rand(1, 10);
					}
					elseif($rnd_input === 2)
					{
						$number[0] = mt_rand(11, 99);
						$number[1] = mt_rand($number[0] - 10, $number[0] - 1);
					}

					$number[2] = $number[0] - $number[1];
					break;
			}
	
			// position of empty input
			if($rnd_input === 0)
				$return = $input.' '.$number[3].' '.$this->encode_operation($number[1]).' = '.$this->encode_operation($number[2]);
			elseif($rnd_input === 1)
				$return = $this->encode_operation($number[0]).' '.$number[3].' '.$input.' = '.$this->encode_operation($number[2]);
			elseif($rnd_input === 2)
				$return = $this->encode_operation($number[0]).' '.$number[3].' '.$this->encode_operation($number[1]).' = '.$input;
		
			set_transient($tr_name, sha1(AUTH_KEY.$number[$rnd_input].$tr_name, false), apply_filters('doc_math_captcha_time', 300));
			return $return;

		} 
		
		//Admin View of Guide
		function admin_view() {
				$documentor_curr = $this->get_settings();
				$guide = $this->get_guide( $this->docid );
				$class0 = $class1 = $class2 = $class3 = "";
				$tabindex = (isset( $_GET['tab'] )) ? $_GET['tab'] : '';
				
				if( !empty( $tabindex ) ) {
					if( $tabindex == 'sections' ) {
						$class0 = 'nav-tab-active';
					} else if( $tabindex == 'settings' ) {
						$class1 = 'nav-tab-active';
					}
				} else {
					$class0 = 'nav-tab-active';
				}
				echo '<div class="wrap"><div id="documentor_tabs" class="documentor_editguide"> <div class="columns"><div class="column is-two-thirds">';
				if( $tabindex != 'add-sections' ) { ?>
							<div class="edit-guidetitle"><span class="dashicons dashicons-welcome-write-blog editguide-icon"></span> Edit Guide 
							</div>
						<form name="guide-titleform" class="guide-titleform">
							<label for="guidetitle" class="label">Guide Name</label>
							<div class="columns">
								<div class="column is-half">
									<input type="text" id="documentor-name" name="guide-title" class="docname input" value="<?php echo esc_attr($guide->doc_title);?>" />
								</div>
								<div class="column is-half">
									<a class="button is-link" id="save-title"><?php _e('Save name','documentor-lite');?></a>
								</div>
							</div>
							<input type="hidden" name="documentor-loader" value="<?php echo esc_url( admin_url('images/loading.gif') );?>" />
							<input type="hidden" value="<?php echo esc_attr($this->docid); ?>" name="docid" />
							<input type="hidden" value="<?php echo esc_attr($this->get_guide_post_id($this->docid)); ?>" name="doc_postid" id="doc_postid" />
							<input type="hidden" name="documentor-guide-nonce" value="<?php echo wp_create_nonce( 'documentor-guide-nonce' ); ?>">
						</form>
					<!--</div>-->
					<div class="doc-successmsg"></div>
					<h2 class="nav-tab-wrapper"> 
						<a id="options-group-1-tab" class="nav-tab sections-tab <?php if( isset( $class0 ) ) echo $class0; ?>" title="<?php _e('Sections','documentor-lite'); ?>" href="<?php echo esc_url( admin_url('admin.php?page=documentor-admin&action=edit&id='.$this->docid.'&tab=sections') ); ?>"><?php _e('Sections','documentor-lite'); ?></a> 
						<a id="options-group-2-tab" class="nav-tab settings-tab <?php if( isset( $class1 ) ) echo $class1; ?>" title="<?php _e('Settings','documentor-lite'); ?>" href="<?php echo esc_url( admin_url('admin.php?page=documentor-admin&action=edit&id='.$this->docid.'&tab=settings') ); ?>"><?php _e('Settings','documentor-lite'); ?></a> 
						<a id="options-group-3-tab" class="nav-tab pro-tab" title="Documentor Pro" href="https://documentor.in/" target="_blank">Documentor Pro</a>
					</h2>
				<?php }
				if( ( isset( $tabindex ) && $tabindex == 'sections' ) || empty( $tabindex )) { ?>
					<div id="options-group-1" class="group sections">
						<div id="addsections" class="documentor-newdoc">
							<?php if( isset($_GET['msg']) and $_GET['msg']=='1' ) { ?>
								<div class="doc-successmsg" style="display:block;"><?php _e('Section added successfully','documentor-lite');?></div>
							<?php } ?>
							<a href="<?php echo esc_url(admin_url('admin.php?page=documentor-admin&action=edit&id='.$this->docid.'&tab=add-sections')); ?>" title="<?php _e('Add Section','documentor-lite'); ?>" class="create-btn add-secbtn button is-primary is-medium"><?php _e('Add Section','documentor-lite'); ?></a>
							<input type="hidden" value="<?php echo esc_attr($this->docid); ?>" name="docsid" />
							
							<input type="hidden" name="documentor-loader" value="<?php echo esc_url( admin_url('images/loading.gif') );?>" />
							<form name="guide_secform" class="guide-secform" method="post">
								<input type="hidden" value="<?php echo esc_attr($this->docid); ?>" name="docid" />
								<input type="hidden" value="<?php echo esc_attr($this->get_guide_post_id($this->docid)); ?>" name="doc_postid" id="doc_postid" />
								<div id="reorders" class="reorders" >
									<div class="loader sec-loader"></div>		
								</div>
								<p>
								<?php $guide = $this->get_guide( $this->docid ); ?>
								<input type="hidden" name="guidename" class="guidename" value="<?php echo esc_attr($guide->doc_title);?>">
								<input type="submit" name="save_sections" class="save-sections button-primary" value="Save" style="display: none;" />
								<input type="hidden" name="documentor-sections-nonce" value="<?php echo wp_create_nonce( 'documentor-sections-nonce' ); ?>">
								<?php $sections = $this->get_sections(); 
								if( count($sections) ){
								?>
									<input type="submit" name="doc_feedbackcnt_reset" class="doc-feedbackcnt-reset button-primary" value="<?php _e('Reset Feedback Counts','documentor-lite');?>" >
									<span class="docloader"></span>
									<span class="doc-pdf-msg"></span>
								<?php } ?>
								</p>
							</form>
						</div>
					</div> <!--tab group-1 ends -->
				<?php } else if( $tabindex == 'add-sections' ) { ?>
					<div id="doc-add-sections" class="doc-add-sections">
						<div class="edit-guidetitle"><span class="dashicons dashicons-plus-alt addsec-icon"></span>Add New Section</div>
						<div class="doc-successmsg"></div>
						<form method="post" id="addsecform" name="addsecform" class="addsecform">
							<input type="hidden" value="<?php echo esc_attr($this->docid); ?>" name="docsid" />
							<div class="eb-cs-left">
								<?php 
								//if custom post is enabled then only add inline sections
								$global_settings_curr = get_option('documentor_global_options');
								if( isset( $global_settings_curr['custom_post'] ) && $global_settings_curr['custom_post'] == '1' ) { ?>
								<div class="eb-cs-tab eb-cs-blank doc-active"> <span class="dashicons dashicons-editor-alignleft"></span> <?php _e('Inline','documentor-lite'); ?></div>
								<?php } ?>
								<?php
								if( isset( $global_settings_curr['custom_posts'] ) ) {
									foreach( $global_settings_curr['custom_posts'] as $post_type ) {
										$obj = get_post_type_object( $post_type ); 
										if( $obj !== null ) {
											if( $post_type == 'page' ) $dashicon_class = 'dashicons-admin-page'; else $dashicon_class = 'dashicons-admin-post';
									?>
										
										<div class="eb-cs-tab eb-cs-post" id="<?php echo $post_type;?>" ><span class="dashicons <?php echo $dashicon_class;?>"></span> <?php _e($obj->labels->name,'documentor-lite'); ?></div>	
									<?php }
									}
								}
								?>
								<div class="eb-cs-tab eb-cs-links" id="attachment"><span class="dashicons dashicons-admin-links"></span> <?php _e('Links','documentor-lite'); ?></div>
								
							</div>
							<div class="eb-cs-right-wrap">
								
								<?php 
								//if custom post is enabled then only add inline sections
								if( isset( $global_settings_curr['custom_post'] ) && $global_settings_curr['custom_post'] == '1' ) { ?>
									<div style="margin-left: 20px;" class="addinlinesecform">
											<div class="docfrm-div">
												<label class="titles"> <?php _e('Menu Title','documentor-lite'); ?> </label>
												<input type="text" name="menutitle" class="input txts menutitle" placeholder="<?php _e('Enter Menu Title','documentor-lite'); ?>" value="" />
											</div>
											<div class="docfrm-div">
												<label class="titles"> <?php _e('Section Title','documentor-lite'); ?> </label>
												<input type="text" name="sectiontitle" class="input txts sectiontitle" placeholder="<?php _e('Enter Section Title','documentor-lite'); ?>" value="" />
											</div>
											<div class="docfrm-div">
												<label class="titles"> <?php _e('Content','documentor-lite'); ?> </label>
												<?php 
												$content = '';
												$editor_id = 'content';
												$settings =   array(
												    'wpautop' => true, // use wpautop?
												    'media_buttons' => true, // show insert/upload button(s)
												    'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
												    'textarea_rows' => 15, // rows="..."
												    'tabindex' => '',
												    'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
												    'editor_class' => '', // add extra class(es) to the editor textarea
												    'teeny' => false, // output the minimal editor config used in Press This
												    'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
												    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
												    'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
												);
												echo '<div style="width:99%;height:auto;">';
													wp_editor( $content, $editor_id, $settings );
												echo '</div>';
												?>

											</div>
											<div class="clrleft"></div>
											<p class="clrleft"><input type="submit" name="add_section" class="button is-primary add-inlinesectionbtn" value="<?php _e('Insert','documentor-lite'); ?>" data-editurl="<?php echo esc_url(admin_url('admin.php?page=documentor-admin&action=edit&id='.$this->docid.'&tab=sections&msg=1'));?>" /> &nbsp; 
											<a class="button is-light" href="<?php echo esc_url(admin_url('admin.php?page=documentor-admin&action=edit&id='.$this->docid.'&tab=sections')); ?>"><span class="dashicons dashicons-undo doc-back"></span><?php _e('Back to Edit','documentor-lite'); ?></a>
											</p>
											<input type="hidden" name="post_type" value="inline" />
									</div>
								<?php }?>
							
								<div class="eb-cs-right"> 								
								</div>
							</div>
							<input type="hidden" name="documentor-sections-nonce" value="<?php echo wp_create_nonce( 'documentor-sections-nonce' ); ?>">
						</form>
						
					</div>
				<?php }//if tab ends
				else if( isset( $tabindex ) && $tabindex == 'settings' ) { 
				?>
				<div id="options-group-2" class="group settings">
				<form method="post" name="documentor-settings" class="documentor-settings">
				<input type="hidden" value="<?php echo esc_attr($this->docid); ?>" name="docsid" />
				<input type="hidden" name="documentor-loader" value="<?php echo esc_url( admin_url('images/loading.gif') );?>" />
				<div id="basic" class="doc-settingsdiv">
				<div class="sub_settings toggle_settings closed" data-docuset="0">
				<h2 class="sub-heading closed"><?php _e('Basic Settings','documentor-lite'); ?><span class="toggle_img"></span></h2> 
				<div class="fields-wrap">
				<?php
				$documentor_options = 'documentor_options'; ?>
				<table class="form-table">

				<tr valign="top">
				<th scope="row"><?php _e('Skin','documentor-lite'); ?></th>
				<td><select name="<?php echo $documentor_options;?>[skin]" id="doc-skin" class="doc-skin">
				<?php 
				$directory = DOCUMENTORLITE_CSS_DIR;
				if ($handle = opendir($directory)) {
					while (false !== ($file = readdir($handle))) { 
						if($file != '.' and $file != '..') {  
							$path=$directory.$file.'/index.php';
							$filedata=get_file_data( $path, array( 'skin'=>'Skin' ) );
					 ?>	
						<option value="<?php echo esc_attr($file);?>" <?php if ($documentor_curr['skin'] == $file){ echo "selected";}?> ><?php echo $filedata['skin'];?></option>
				<?php		
				} }
					closedir($handle);
				}
				?>
				</select>
				</td>
				</tr>
				
				<?php 
					$indexstyle = ( $documentor_curr['skin'] == 'cherry' ) ? 'style="display: none;"' : 'style="display: table-row;"';
					$mtogglestyle = ( $documentor_curr['skin'] == 'bar' ) ? 'style="display: none;"' : 'style="display: table-row;"';
				?>
				<tr valign="top" class="doc-indexformat-row" <?php echo $indexstyle; ?>>
				<th scope="row"><?php _e('Indexing Format','documentor-lite'); ?></th>
				<td>
				<div class="eb-switch eb-switchnone havemoreinfo indexswitch" >
					<input type="hidden" name="<?php echo $documentor_options;?>[indexformat]" id="documentor_indexformat" class="hidden_check" value="<?php echo esc_attr($documentor_curr['indexformat']);?>">
					<input id="indexformat" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['indexformat']); ?>>
					<label for="indexformat"></label>
				</div>
					<a href="#format-index" id="index_format" rel="leanModal" title="Index Formatting " ><?php _e('Format','documentor-lite');?></a>
				<script type="text/javascript">
  	 			jQuery( document ).ready( function() {
	  	 			jQuery('.indexswitch').on("change",function(){ 
			      		var val_checkbox = jQuery("#indexformat").attr("checked");			      		
			      		if(val_checkbox=='checked'){
			      			console.log(val_checkbox);
			      			jQuery('#index_format').show();
			      		}else {
			      		  console.log("no checked");
			      		  jQuery('#index_format').hide();
			      		}
  	 			  });
  	 			 });
  	 			</script>
  	 			
				
				</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e('Show Guide Title','documentor-lite'); ?></th>
					<td>
					<div class="eb-switch eb-switchnone">
						<input type="hidden" name="<?php echo $documentor_options;?>[guidetitle]" id="documentor_guidetitle" class="hidden_check" value="<?php echo esc_attr($documentor_curr['guidetitle']);?>">
						<input id="guidetitle" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['guidetitle']); ?>>
						<label for="guidetitle"></label>
					</div>
					<a href="#options-guidetitle" rel="leanModal" title="Guide Title Formatting" ><?php _e('Options','documentor-lite');?></a>
					</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><?php _e('Smooth Scrolling','documentor-lite'); ?></th>
				<td>
				<?php $documentor_curr['scrolling'] = ( !isset( $documentor_curr['scrolling'] )  ) ? 1 : $documentor_curr['scrolling']; ?>
				<div class="eb-switch eb-switchnone havemoreinfo">
					<input type="hidden" name="<?php echo $documentor_options;?>[scrolling]" id="doc-enable-scroll" class="hidden_check" value="<?php echo esc_attr($documentor_curr['scrolling']);?>">
					<input id="enable-scroll" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['scrolling']); ?>>
					<label for="enable-scroll"></label>
				</div>
				</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e('Fixed Menu','documentor-lite'); ?></th>
					<td>
						<?php $documentor_curr['fixmenu'] = ( !isset( $documentor_curr['fixmenu'] )  ) ? 1 : $documentor_curr['fixmenu']; ?>
						<div class="eb-switch eb-switchnone havemoreinfo">
							<input type="hidden" name="<?php echo $documentor_options;?>[fixmenu]" id="doc-enable-fixmenu" class="hidden_check" value="<?php echo esc_attr($documentor_curr['fixmenu']);?>">
							<input id="enable-fixmenu" class="cmn-toggle eb-toggle-round" type="checkbox"  <?php checked('1', $documentor_curr['fixmenu']); ?>>
							<label for="enable-fixmenu"></label>
						</div>
					</td>
				</tr>
				
				<?php if($documentor_curr['rtl_support'] != "1") { ?>
				<tr valign="top">
					<?php
						//new field added in v1.1
						$documentor_curr['menu_position'] = isset($documentor_curr['menu_position']) ? $documentor_curr['menu_position'] : 'left'; 
					?>
					<th scope="row"><?php _e('Menu Position','documentor-lite'); ?></th>
					<td>
						<select name="<?php echo $documentor_options;?>[menu_position]" >
							<option value="left" <?php if ($documentor_curr['menu_position'] == "left"){ echo "selected";}?> >Left</option>
							<option value="right" <?php if ($documentor_curr['menu_position'] == "right"){ echo "selected";}?> >Right</option>
						</select>
					</td>
				</tr>
				<?php } ?>
				
				<tr valign="top" class="mtoggle-row" <?php echo $mtogglestyle; ?>>
					<th scope="row"><?php _e('Toggle Child Menu','documentor-lite'); ?></th>
					<td>
					<div class="eb-switch eb-switchnone havemoreinfo">
						<input type="hidden" name="<?php echo $documentor_options;?>[togglemenu]" id="doc-enable-togglemenu" class="hidden_check" value="<?php echo esc_attr($documentor_curr['togglemenu']);?>">
						<input id="enable-togglemenu" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['togglemenu']); ?>>
						<label for="enable-togglemenu"></label>
					</div>
					</td>
				</tr>
				
				<tr valign="top" class="menuTop" style="<?php echo ( !isset( $documentor_curr['fixmenu'] )  or $documentor_curr['fixmenu']=='0' ) ? 'display:none;' : ''; ?>">
					<th scope="row"><?php _e('Set Top Margin','documentor-lite'); ?></th>
					<td>
						<input type="number" name="<?php echo $documentor_options;?>[menuTop]" id="menuTop" class="small-text" value="<?php echo esc_attr($documentor_curr['menuTop']); ?>" min="0" />&nbsp;<?php _e('px','documentor-lite'); ?>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row" title="<?php esc_attr_e('in pixels; can be negative; (Keep empty to auto calculate)','documentor-lite');?>"><?php _e('Set Footer Height','documentor-lite'); ?></th>
					<td>
						<input type="number" name="<?php echo $documentor_options;?>[footerht]" id="footerht" class="small-text" value="<?php echo esc_attr($documentor_curr['footerht']); ?>" step="1" />
					</td>
				</tr>
				
				</table>
				<p class="submit">
				<input type="submit" name="save-settings" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
				</div><!--/.fields-wrap-->
				
				</div>

				</div> <!--Basic ends-->
				<div id="formating" class="doc-settingsdiv" >
				<div class="sub_settings toggle_settings closed" data-docuset="1">
				<h2 class="sub-heading closed"><?php _e('Formatting','documentor-lite'); ?><span class="toggle_img"></span></h2> 
				
				<div class="fields-wrap">
				<span scope="row" class="doc-settingtitle"><?php _e('Nav Menu Title','documentor-lite'); ?></span>
				<table class="form-table settings-tbl"  >
				<tr valign="top" >
					<th scope="row" ><?php _e('Inherit from Active Theme','documentor-lite'); ?></th>
					<td>
					<div class="eb-switch eb-switchnone havemoreinfo">
						<input type="hidden" name="<?php echo $documentor_options;?>[navmenu_default]" id="navmenu-default" class="hidden_check" value="<?php echo esc_attr($documentor_curr['navmenu_default']);?>">
						<input id="navmenu-def" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['navmenu_default']); ?>>
						<label for="navmenu-def"></label>
					</div>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('Color','documentor-lite'); ?></th>
					<td><input type="text" name="<?php echo $documentor_options;?>[navmenu_color]" id="navmenu_color" value="<?php echo esc_attr($documentor_curr['navmenu_color']); ?>" class="wp-color-picker-field" data-default-color="#D8E7EE" /></td>
			    </tr>
			    
				<tr valign="top">
				<th scope="row"><?php _e('Font','documentor-lite'); ?></th>
				<td>
				<input type="hidden" value="navmenu_tfont" class="ftype_rname">
				<input type="hidden" value="navmenu_tfontg" class="ftype_gname">
				<input type="hidden" value="navmenu_custom" class="ftype_cname">
				<select name="<?php echo $documentor_options;?>[navt_font]" id="navt_font" class="main-font">
	
					<option value="regular" <?php selected( $documentor_curr['navt_font'], "regular" ); ?> > Regular Fonts </option>
					<option value="google" <?php selected( $documentor_curr['navt_font'], "google" ); ?> > Google Fonts </option>
					<option value="custom" <?php selected( $documentor_curr['navt_font'], "custom" ); ?> > Custom Fonts </option>
				</select>
				</td>
				</tr>

				<tr><td class="load-fontdiv" colspan="2"></td></tr>

				<tr valign="top">
				<th scope="row"><?php _e('Font Size','documentor-lite'); ?></th>
				<td><input type="number" name="<?php echo $documentor_options;?>[navmenu_fsize]" id="navmenu_fsize" class="small-text" value="<?php echo esc_attr($documentor_curr['navmenu_fsize']); ?>" min="1" />&nbsp;<?php _e('px','documentor-lite'); ?></td>
				</tr>

				<tr valign="top" class="font-style">
				<th scope="row"><?php _e('Font Style','documentor-lite'); ?></th>
				<td><select name="<?php echo $documentor_options;?>[navmenu_fstyle]" id="navmenu_fstyle" class="font-style" >
				<option value="bold" <?php if ($documentor_curr['navmenu_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','documentor-lite'); ?></option>
				<option value="bold italic" <?php if ($documentor_curr['navmenu_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','documentor-lite'); ?></option>
				<option value="italic" <?php if ($documentor_curr['navmenu_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','documentor-lite'); ?></option>
				<option value="normal" <?php if ($documentor_curr['navmenu_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','documentor-lite'); ?></option>
				</select>
				</td>
				</tr>

				</table>
				
				<p class="submit">
				<input type="submit" name="save-settings" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>

				<span scope="row" class="doc-settingtitle" ><?php _e('Active Nav Menu Background','documentor-lite'); ?></span>

				<table class="form-table settings-tbl"  >

				<tr valign="top">
				<th scope="row"><?php _e('Inherit from Active Theme','documentor-lite'); ?></th>
				<td>
				<div class="eb-switch eb-switchnone havemoreinfo">
					<input type="hidden" name="<?php echo $documentor_options;?>[actnavbg_default]" id="actnav-background" class="hidden_check" value="<?php echo esc_attr($documentor_curr['actnavbg_default']);?>">
					<input id="actnav-bg" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['actnavbg_default']); ?>>
					<label for="actnav-bg"></label>
				</div>
				</td>
				</tr>

				<tr valign="top">
				<th scope="row"><?php _e('Color','documentor-lite'); ?></th>
				<td><input type="text" name="<?php echo $documentor_options;?>[actnavbg_color]" id="actnavbg-color" value="<?php echo esc_attr($documentor_curr['actnavbg_color']); ?>" class="wp-color-picker-field" data-default-color="#D8E7EE" /></td>
				</tr>

				</table>
				
				<p class="submit">
				<input type="submit" name="save-settings" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>

				<span scope="row" class="doc-settingtitle"><?php _e('Section Title','documentor-lite'); ?></span>

				<table class="form-table settings-tbl"  >

				<tr valign="top">
				<th scope="row"><?php _e('Element','documentor-lite'); ?>
				</th>
				<td><select name="<?php echo $documentor_options;?>[section_element]" >
				<option value="1" <?php if ($documentor_curr['section_element'] == "1"){ echo "selected";}?> >h1</option>
				<option value="2" <?php if ($documentor_curr['section_element'] == "2"){ echo "selected";}?> >h2</option>
				<option value="3" <?php if ($documentor_curr['section_element'] == "3"){ echo "selected";}?> >h3</option>
				<option value="4" <?php if ($documentor_curr['section_element'] == "4"){ echo "selected";}?> >h4</option>
				<option value="5" <?php if ($documentor_curr['section_element'] == "5"){ echo "selected";}?> >h5</option>
				<option value="6" <?php if ($documentor_curr['section_element'] == "6"){ echo "selected";}?> >h6</option>
				</select>
				</td>
				</tr>

				<tr valign="top">
				<th scope="row"><?php _e('Inherit from Active Theme','documentor-lite'); ?></th>
				<td>
				<div class="eb-switch eb-switchnone havemoreinfo">
					<input type="hidden" name="<?php echo $documentor_options;?>[sectitle_default]" id="sectitle-default" class="hidden_check" value="<?php echo esc_attr($documentor_curr['sectitle_default']);?>">
					<input id="sectitle-def" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['sectitle_default']); ?>>
					<label for="sectitle-def"></label>
				</div>
				</td>
				</tr>

				<tr valign="top">
				<th scope="row"><?php _e('Color','documentor-lite'); ?></th>
				<td><input type="text" name="<?php echo $documentor_options;?>[sectitle_color]" id="sectitle-color" value="<?php echo esc_attr($documentor_curr['sectitle_color']); ?>" class="wp-color-picker-field" data-default-color="#D8E7EE" /></td>
				</tr>

				<tr valign="top">
				<th scope="row"><?php _e('Font','documentor-lite'); ?></th>
				<td>
				<input type="hidden" value="sectitle_font" class="ftype_rname">
				<input type="hidden" value="sectitle_fontg" class="ftype_gname">
				<input type="hidden" value="sectitle_custom" class="ftype_cname">
				<select name="<?php echo $documentor_options;?>[sect_font]" id="sect_font" class="main-font">
					<option value="regular" <?php selected( $documentor_curr['sect_font'], "regular" ); ?> > Regular Fonts </option>
					<option value="google" <?php selected( $documentor_curr['sect_font'], "google" ); ?> > Google Fonts </option>
					<option value="custom" <?php selected( $documentor_curr['sect_font'], "custom" ); ?> > Custom Fonts </option>
				</select>
				</td>
				</tr>

				<tr><td class="load-fontdiv" colspan="2"></td></tr>

				<tr valign="top">
				<th scope="row"><?php _e('Font Size','documentor-lite'); ?></th>
				<td><input type="number" name="<?php echo $documentor_options;?>[sectitle_fsize]" id="sectitle_fsize" class="small-text" value="<?php echo esc_attr($documentor_curr['sectitle_fsize']); ?>" min="1" />&nbsp;<?php _e('px','documentor-lite'); ?></td>
				</tr>

				<tr valign="top" class="font-style">
				<th scope="row"><?php _e('Font Style','documentor-lite'); ?></th>
				<td><select name="<?php echo $documentor_options;?>[sectitle_fstyle]" id="sectitle_fstyle" class="font-style" >
				<option value="bold" <?php if ($documentor_curr['sectitle_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','documentor-lite'); ?></option>
				<option value="bold italic" <?php if ($documentor_curr['sectitle_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','documentor-lite'); ?></option>
				<option value="italic" <?php if ($documentor_curr['sectitle_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','documentor-lite'); ?></option>
				<option value="normal" <?php if ($documentor_curr['sectitle_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','documentor-lite'); ?></option>
				</select>
				</td>
				</tr>

				</table>
				
				<p class="submit">
				<input type="submit" name="save-settings" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
				
				<span scope="row" class="doc-settingtitle"><?php _e('Section Content','documentor-lite'); ?></span>

				<table class="form-table settings-tbl"  >


				<tr valign="top">
				<th scope="row"><?php _e('Inherit from Active Theme','documentor-lite'); ?></th>
				<td>
				<div class="eb-switch eb-switchnone havemoreinfo">
					<input type="hidden" name="<?php echo $documentor_options;?>[seccont_default]" id="seccont-default" class="hidden_check" value="<?php echo esc_attr($documentor_curr['seccont_default']);?>">
					<input id="seccont-def" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['seccont_default']); ?>>
					<label for="seccont-def"></label>
				</div>
				</td>
				</tr>

				<tr valign="top">
				<th scope="row"><?php _e('Color','documentor-lite'); ?></th>
				<td><input type="text" name="<?php echo $documentor_options;?>[seccont_color]" id="seccont_color" value="<?php echo esc_attr($documentor_curr['seccont_color']); ?>" class="wp-color-picker-field" data-default-color="#D8E7EE" /></td>
				</tr>

				<tr valign="top">
				<th scope="row"><?php _e('Font','documentor-lite'); ?></th>
				<td>
				<input type="hidden" value="seccont_font" class="ftype_rname">
				<input type="hidden" value="seccont_fontg" class="ftype_gname">
				<input type="hidden" value="seccont_custom" class="ftype_cname">
				<select name="<?php echo $documentor_options;?>[secc_font]" id="secc_font" class="main-font">
					<option value="regular" <?php selected( $documentor_curr['secc_font'], "regular" ); ?> > Regular Fonts </option>
					<option value="google" <?php selected( $documentor_curr['secc_font'], "google" ); ?> > Google Fonts </option>
					<option value="custom" <?php selected( $documentor_curr['secc_font'], "custom" ); ?> > Custom Fonts </option>
				</select>
				</td>
				</tr>

				<tr><td class="load-fontdiv" colspan="2"></td></tr>

				<tr valign="top">
				<th scope="row"><?php _e('Font Size','documentor-lite'); ?></th>
				<td><input type="number" name="<?php echo $documentor_options;?>[seccont_fsize]" id="seccont-fsize" class="small-text" value="<?php echo esc_attr($documentor_curr['seccont_fsize']); ?>" min="1" />&nbsp;<?php _e('px','documentor-lite'); ?></td>
				</tr>

				<tr valign="top" class="font-style">
				<th scope="row"><?php _e('Font Style','documentor-lite'); ?></th>
				<td><select name="<?php echo $documentor_options;?>[seccont_fstyle]" id="seccont-fstyle" class="font-style" >
				<option value="bold" <?php if ($documentor_curr['seccont_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','documentor-lite'); ?></option>
				<option value="bold italic" <?php if ($documentor_curr['seccont_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','documentor-lite'); ?></option>
				<option value="italic" <?php if ($documentor_curr['seccont_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','documentor-lite'); ?></option>
				<option value="normal" <?php if ($documentor_curr['seccont_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','documentor-lite'); ?></option>
				</select>
				</td>
				</tr>

				</table>
				
				<p class="submit">
				<input type="submit" name="save-settings" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
				
				<span scope="row" class="doc-settingtitle"><?php _e('Scrollbar','documentor-lite'); ?></span>

				<table class="form-table settings-tbl"  >
					<?php 
						//new settings for scrollbar v1.1
						$scrollsize = isset( $documentor_curr['scroll_size'] ) ? $documentor_curr['scroll_size'] : 3;
						$scrollcolor = isset( $documentor_curr['scroll_color'] ) ? $documentor_curr['scroll_color'] : '#F45349';
						$scrollopacity = isset( $documentor_curr['scroll_opacity'] ) ? $documentor_curr['scroll_opacity'] : 0.4;
					?>
					<tr valign="top">
						<th scope="row"><?php _e('Width','documentor-lite'); ?></th>
						<td>
							<input type="number" min="0" class="small-text" name="<?php echo $documentor_options;?>[scroll_size]" id="scroll_size" value="<?php echo esc_attr($scrollsize);?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Color','documentor-lite'); ?></th>
						<td>
							<input type="text" name="<?php echo $documentor_options;?>[scroll_color]" id="scroll_color" value="<?php echo esc_attr($scrollcolor); ?>" class="wp-color-picker-field" data-default-color="#2c3e50" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Opacity','documentor-lite'); ?></th>
						<td>
							<input type="number" class="small-text" name="<?php echo $documentor_options;?>[scroll_opacity]" id="scroll_opacity" value="<?php echo esc_attr($scrollopacity); ?>" min="0" max="1" step="any" />
						</td>
					</tr>
				</table>
				
				<p class="submit">
				<input type="submit" name="save-settings" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
				</div><!--/.fields-wrap-->
				
				</div>

				</div> <!--Formatting -->
				<div id="advance-settings" class="doc-settingsdiv">
				<div class="sub_settings toggle_settings closed" data-docuset="2">
				<h2 class="sub-heading closed"><?php _e('Advanced Settings','documentor-lite'); ?><span class="toggle_img"></span></h2> 
				
				<div class="fields-wrap">
				<table class="form-table">
				
				<tr valign="top">
					<th scope="row"><?php _e('Section Animation','documentor-lite'); ?></th>
					<td>
						<?php $animation = $documentor_curr['animation']; ?>
						<select name="<?php echo $documentor_options;?>[animation]">
							<option value="">Select animation</option>
							<optgroup label="<?php _e('Attention Seekers','documentor-lite'); ?>">
							  <option value="bounce" <?php selected( $animation, "bounce" ); ?> ><?php _e('bounce','documentor-lite'); ?></option>
							  <option value="flash" <?php selected( $animation, "flash" ); ?> ><?php _e('flash','documentor-lite'); ?></option>
							  <option value="pulse" <?php selected( $animation, "pulse" ); ?> ><?php _e('pulse','documentor-lite'); ?></option>
							  <option value="rubberBand" <?php selected( $animation, "rubberBand" ); ?> ><?php _e('rubberBand','documentor-lite'); ?></option>
							  <option value="shake" <?php selected( $animation, "shake" ); ?> ><?php _e('shake','documentor-lite'); ?></option>
							  <option value="swing" <?php selected( $animation, "swing" ); ?> ><?php _e('swing','documentor-lite'); ?></option>
							  <option value="tada" <?php selected( $animation, "tada" ); ?> ><?php _e('tada','documentor-lite'); ?></option>
							  <option value="wobble" <?php selected( $animation, "wobble" ); ?> ><?php _e('wobble','documentor-lite'); ?></option>
							</optgroup>
							<optgroup label="<?php _e('Bouncing Entrances','documentor-lite'); ?>">
							  <option value="bounceIn" <?php selected( $animation, "bounceIn" ); ?> ><?php _e('bounceIn','documentor-lite'); ?></option>
							  <option value="bounceInDown" <?php selected( $animation, "bounceInDown" ); ?> ><?php _e('bounceInDown','documentor-lite'); ?></option>
							  <option value="bounceInLeft" <?php selected( $animation, "bounceInLeft" ); ?> ><?php _e('bounceInLeft','documentor-lite'); ?></option>
							  <option value="bounceInRight" <?php selected( $animation, "bounceInRight" ); ?> ><?php _e('bounceInRight','documentor-lite'); ?></option>
							  <option value="bounceInUp" <?php selected( $animation, "bounceInUp" ); ?> ><?php _e('bounceInUp','documentor-lite'); ?></option>
							</optgroup>

						       <optgroup label="<?php _e('Fading Entrances','documentor-lite'); ?>">
							  <option value="fadeIn" <?php selected( $animation, "fadeIn" ); ?> ><?php _e('fadeIn','documentor-lite'); ?></option>
							  <option value="fadeInDown" <?php selected( $animation, "fadeInDown" ); ?> ><?php _e('fadeInDown','documentor-lite'); ?></option>
							  <option value="fadeInDownBig"<?php selected( $animation, "fadeInDownBig" ); ?> ><?php _e('fadeInDownBig','documentor-lite'); ?></option>
							  <option value="fadeInLeft" <?php selected( $animation, "fadeInLeft" ); ?> ><?php _e('fadeInLeft','documentor-lite'); ?></option>
							  <option value="fadeInLeftBig" <?php selected( $animation, "fadeInLeftBig" ); ?> ><?php _e('fadeInLeftBig','documentor-lite'); ?></option>
							  <option value="fadeInRight" <?php selected( $animation, "fadeInRight" ); ?> ><?php _e('fadeInRight','documentor-lite'); ?></option>
							  <option value="fadeInRightBig" <?php selected( $animation, "fadeInRightBig" ); ?> ><?php _e('fadeInRightBig','documentor-lite'); ?></option>
							  <option value="fadeInUp" <?php selected( $animation, "fadeInUp" ); ?> ><?php _e('fadeInUp','documentor-lite'); ?></option>
							  <option value="fadeInUpBig" <?php selected( $animation, "fadeInUpBig" ); ?> ><?php _e('fadeInUpBig','documentor-lite'); ?></option>
							</optgroup>

						       <optgroup label="<?php _e('Flippers','documentor-lite'); ?>">
							  <option value="flip" <?php selected( $animation, "flip" ); ?> ><?php _e('flip','documentor-lite'); ?></option>
							  <option value="flipInX" <?php selected( $animation, "flipInX" ); ?> ><?php _e('flipInX','documentor-lite'); ?></option>
							  <option value="flipInY" <?php selected( $animation, "flipInY" ); ?> ><?php _e('flipInY','documentor-lite'); ?></option>
						       </optgroup>

							<optgroup label="<?php _e('Lightspeed','documentor-lite'); ?>">
							  <option value="lightSpeedIn" <?php selected( $animation, "lightSpeedIn" ); ?> ><?php _e('lightSpeedIn','documentor-lite'); ?></option>
							</optgroup>

							<optgroup label="<?php _e('Rotating Entrances','documentor-lite'); ?>">
							  <option value="rotateIn" <?php selected( $animation, "rotateIn" ); ?> ><?php _e('rotateIn','documentor-lite'); ?></option>
							  <option value="rotateInDownLeft" <?php selected( $animation, "rotateInDownLeft" ); ?> ><?php _e('rotateInDownLeft','documentor-lite'); ?></option>
							  <option value="rotateInDownRight" <?php selected( $animation, "rotateInDownRight" ); ?> ><?php _e('rotateInDownRight','documentor-lite'); ?></option>
							  <option value="rotateInUpLeft" <?php selected( $animation, "rotateInUpLeft" ); ?> ><?php _e('rotateInUpLeft','documentor-lite'); ?></option>
							  <option value="rotateInUpRight" <?php selected( $animation, "rotateInUpRight" ); ?> ><?php _e('rotateInUpRight','documentor-lite'); ?></option>
							</optgroup>

							<optgroup label="<?php _e('Specials','documentor-lite'); ?>">
							  <option value="hinge" <?php selected( $animation, "hinge" ); ?> ><?php _e('hinge','documentor-lite'); ?></option>
							  <option value="rollIn" <?php selected( $animation, "rollIn" ); ?> ><?php _e('rollIn','documentor-lite'); ?></option>
							</optgroup>

							<optgroup label="<?php _e('Zoom Entrances','documentor-lite'); ?>">
							  <option value="zoomIn" <?php selected( $animation, "zoomIn" ); ?> ><?php _e('zoomIn','documentor-lite'); ?></option>
							  <option value="zoomInDown" <?php selected( $animation, "zoomInDown" ); ?> ><?php _e('zoomInDown','documentor-lite'); ?></option>
							  <option value="zoomInLeft" <?php selected( $animation, "zoomInLeft" ); ?> ><?php _e('zoomInLeft','documentor-lite'); ?></option>
							  <option value="zoomInRight" <?php selected( $animation, "zoomInRight" ); ?> ><?php _e('zoomInRight','documentor-lite'); ?></option>
							  <option value="zoomInUp" <?php selected( $animation, "zoomInUp" ); ?> ><?php _e('zoomInUp','documentor-lite'); ?></option>
							</optgroup>

							 <optgroup label="<?php _e('Slide Entrances','documentor-lite'); ?>">
							  <option value="slideInDown" <?php selected( $animation, "slideInDown" ); ?> ><?php _e('slideInDown','documentor-lite'); ?></option>
							  <option value="slideInLeft" <?php selected( $animation, "slideInLeft" ); ?> ><?php _e('slideInLef','documentor-lite'); ?></option>
							  <option value="slideInRight" <?php selected( $animation, "slideInRight" ); ?> ><?php _e('slideInRight','documentor-lite'); ?></option>
							  <option value="slideInUp" <?php selected( $animation, "slideInUp" ); ?> ><?php _e('slideInUp','documentor-lite'); ?></option>
							 </optgroup>
      

						</select>
					</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><?php _e('Search Box','documentor-lite'); ?></th>
				<td>
				<div class="eb-switch eb-switchnone">
					<input type="hidden" name="<?php echo $documentor_options;?>[search_box]" id="search-box" class="hidden_check" value="<?php echo esc_attr($documentor_curr['search_box']);?>">
					<input id="search_box" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['search_box']); ?>>
					<label for="search_box"></label>
				</div>
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><?php _e('Buttons','documentor-lite'); ?></th>
				<td>
				<fieldset>
					<div class="mdivsett">
						<div class="eb-switch eb-switchnone">
							<input type="hidden" name="<?php echo $documentor_options;?>[button][1]" class="hidden_check" value="<?php echo esc_attr($documentor_curr['button'][1]);?>">
							<input id="button-select1" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['button'][1]); ?>>
							<label for="button-select1"></label>
						</div>
						<label><?php _e('Section Hashtag Link','documentor-lite');?></label>	
					</div>

					<input type="hidden" name="<?php echo $documentor_options;?>[button][3]" class="hidden_check" value="<?php echo esc_attr($documentor_curr['button'][3]);?>">
					
					<div class="mdivsett">
						<div class="eb-switch eb-switchnone">
							<input type="hidden" name="<?php echo $documentor_options;?>[button][4]" class="hidden_check" value="<?php echo esc_attr($documentor_curr['button'][4]);?>">
							<input id="button-select4" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['button'][4]); ?>>
							<label for="button-select4"></label>
						</div>
						<label><?php _e('Print','documentor-lite');?></label>
						<a href="#doc-print-options" rel="leanModal" title="" style="margin-left: 15px;"><?php _e('Options','documentor-lite');?></a>	
					</div>
				</fieldset>
				</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e('Show Last Updated Date for Sections','documentor-lite'); ?></th>
					<td>
						<?php 
						//new field added in v1.1
						$documentor_curr['updated_date'] = isset( $documentor_curr['updated_date'] ) ? $documentor_curr['updated_date'] : 0;
						?>
						<div class="eb-switch eb-switchnone">
							<input type="hidden" name="<?php echo $documentor_options;?>[updated_date]" id="sec_updated_date" class="hidden_check" value="<?php echo esc_attr($documentor_curr['updated_date']);?>">
							<input id="updated_date" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['updated_date']); ?>>
							<label for="updated_date"></label>
						</div>
					</td>
				</tr>

				<tr valign="top">
				<th scope="row"><?php _e('Guide Manager','documentor-lite'); ?></th>
				<td>
				<?php 
				$gmanager_arr = ( isset( $documentor_curr['guide'] ) && is_array( $documentor_curr['guide'] ) ) ?$documentor_curr['guide'] : array(); ?>
				<select name="<?php echo $documentor_options;?>[guide][]" id="documentor_guide_manager" multiple>
				<?php $users = array_merge( get_users('role=administrator'), get_users('role=editor'), get_users('role=author') );
				$i = 0;
				foreach( $users as $user ) { ?>
					<option value="<?php echo esc_attr($user->ID);?>" <?php if(in_array($user->ID,$gmanager_arr)){echo 'selected';} ?> ><?php echo $user->display_name; ?></option>
				<?php	
					$i++;
				 }
				?>
				</select>
				</td>
				</tr>

				<tr valign="top">
				<th scope="row"><?php _e('Visitor\'s Feedback','documentor-lite'); ?></th>
				<td>
				<div class="eb-switch eb-switchnone havemoreinfo">
					<input type="hidden" name="<?php echo $documentor_options;?>[feedback]" id="visitor-feedback" class="hidden_check" value="<?php echo esc_attr($documentor_curr['feedback']);?>">
					<input id="visitors-feedback" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['feedback']); ?>>
					<label for="visitors-feedback"></label>
				</div>
				<span class="doc-format">
					<a href="#format-feedback" rel="leanModal" title="User Feedback Format" ><?php _e('Format','documentor-lite');?></a>
				</span>
				</td>
				</tr>
				
				<tr valign="top">
				<th scope="row"><?php _e('Feedback Count','documentor-lite'); ?></th>
				<td>
				<div class="eb-switch eb-switchnone">
					<input type="hidden" name="<?php echo $documentor_options;?>[feedbackcnt]" id="visitor_feedbackcnt" class="hidden_check" value="<?php echo esc_attr($documentor_curr['feedbackcnt']);?>">
					<input id="visitors-feedbackcnt" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['feedbackcnt']); ?>>
					<label for="visitors-feedbackcnt"></label>
				</div>
				</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e('RTL Support','documentor-lite'); ?></th>
					<td>
						<?php $documentor_curr['rtl_support'] = isset($documentor_curr['rtl_support']) ? $documentor_curr['rtl_support'] : '0'; ?>
						<div class="eb-switch eb-switchnone havemoreinfo">
							<input type="hidden" name="<?php echo $documentor_options;?>[rtl_support]" id="rtl-support" class="hidden_check" value="<?php echo esc_attr($documentor_curr['rtl_support']);?>">
							<input id="rtl_support" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['rtl_support']); ?>>
							<label for="rtl_support"></label>
						</div>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e('Back to Top button','documentor-lite'); ?></th>
					<td>
						<?php $documentor_curr['scrolltop'] = isset($documentor_curr['scrolltop']) ? $documentor_curr['scrolltop'] : '1'; ?>
						<div class="eb-switch eb-switchnone havemoreinfo">
							<input type="hidden" name="<?php echo $documentor_options;?>[scrolltop]" id="scroll-top" class="hidden_check" value="<?php echo esc_attr($documentor_curr['scrolltop']);?>">
							<input id="scrolltop" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['scrolltop']); ?>>
							<label for="scrolltop"></label>
						</div>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e('Social Sharing','documentor-lite'); ?></th>
					<td>
						<div class="eb-switch eb-switchnone">
							<input type="hidden" name="<?php echo $documentor_options;?>[socialshare]" id="social-share" class="hidden_check" value="<?php echo esc_attr($documentor_curr['socialshare']);?>">
							<input id="socialshare" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['socialshare']); ?>>
							<label for="socialshare"></label>
						</div>
						<span class="doc-format">
							<a href="#format-social" rel="leanModal" title="Social Share Format" ><?php _e('Format','documentor-lite');?></a>
						</span>
					</td>
				</tr>
					    				
				</table>
				<input type="hidden" name="<?php echo $documentor_options;?>[button][2]" value="<?php echo esc_attr($documentor_curr['button'][2]);?>">
				<input type="hidden" name="guidename" class="guidename" value="<?php echo esc_attr($this->title);?>">
				<p class="submit">
				<input type="hidden" name="hidden_urlpage" class="documentor_urlpage" value="<?php echo esc_attr($_GET['page']);?>" />
				<input type="hidden" name="documentor-settings-nonce" value="<?php echo wp_create_nonce( 'documentor-settings-nonce' ); ?>" />
				<input type="submit" name="save-settings" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
				</div><!--/.fields-wrap-->
				</div>
					
				</div> <!--advance settings -->
				<?php $this->documentor_get_popup_settings( $documentor_curr, $documentor_options, $guide ); ?>
				</form>
				<?php
				//added
				?>
				</div> <!--tab group-2 ends -->
				<?php } // if tab is 1
				
				echo '</div><!--/.column .is-two-thirds-->';
				if ( isset($this->docid ) ) {
					$doc_id = $this->docid;
				}
				echo '<div class="documentor-sidebar column is-one-third">
						<div class="container"><div class="card">
						  <header class="card-header">
							<p class="documentor-logo">
							  <img src="'.DocumentorLite::documentor_plugin_url( 'core/images/documentor-logo.png' ).'" /><small>'.__('Version','documentor-lite').' '.DOCUMENTORLITE_VER.'</small>
							</p>
						  </header>
						  <footer class="card-footer">
							<a class="card-footer-item" href="https://documentor.in/docs/" target="_blank">Need Help?</a>
							<a class="card-footer-item" href="https://documentor.in/contact-us/" target="_blank">Get Support</a>
						  </footer>
						</div></div>
							
						<div class="container"><div class="card">
						  <header class="card-header">
							<p class="card-header-title">
							  '.__('Shortcode','documentor-lite').'
							</p>
						  </header>
						  <div class="card-content">
							<div class="content">
							 <div><code>[documentor '.$doc_id.']</code></div>
							</div>
						  </div>
						</div></div>
						
						<div class="container"><div class="card">
						  <header class="card-header">
							<p class="card-header-title">
							  '.__('Template Tag','documentor-lite').'
							</p>
						  </header>
						  <div class="card-content">
							<div class="content">
							 <div><code>&lt;?php if(function_exists(\'get_documentor\')){ get_documentor(\''.$doc_id.'\'); }?&gt;</code></div>
							</div>
						  </div>
						</div></div>
						
					</div>
					</div><!--/.columns-->
					</div><!--/.documentor_editguide-->
				</div><!--/.wrap-->';
		} //function admin_view ends		
		
		function documentor_get_popup_settings( $documentor_curr=array(), $documentor_options="documentor_options", $guide=array() ){ ?>		
				<div id="format-feedback" class="format-form">
					<div id="format-ct">
						<div class="frm-heading"><?php _e('User Feedback Format','documentor-lite');?></div>
						<div id="format-header">
							<p class="format-heading"><?php _e('Form','documentor-lite');?></p>
							<a class="modal_close" href="#"></a>
						</div>
						<div class="txt-fld">
							<label for="name" class="lbl"><?php _e('Name','documentor-lite'); ?></label>
							<div class="eb-switch eb-switchl">
								<input type="hidden" name="<?php echo $documentor_options;?>[feedback_frmname]" id="documentor_feedback_frmname" class="hidden_check" value="<?php echo esc_attr($documentor_curr['feedback_frmname']);?>">
								<input id="feedback_frmname" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['feedback_frmname']); ?>>
								<label for="feedback_frmname"></label>
							</div>
						</div>
						<div class="txt-fld">
							<label for="" class="lbl"><?php _e('Email','documentor-lite'); ?></label>
							<div class="eb-switch eb-switchl">
								<input type="hidden" name="<?php echo $documentor_options;?>[feedback_frmemail]" id="documentor_feedback_frmemail" class="hidden_check" value="<?php echo esc_attr($documentor_curr['feedback_frmemail']);?>">
								<input id="feedback_frmemail" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['feedback_frmemail']); ?>>
								<label for="feedback_frmemail"></label>
							</div>
						</div>
						<div class="txt-fld">
							<label for="" class="lbl"><?php _e('Extra Input Fields','documentor-lite'); ?></label>
							<input type="text" name="<?php echo $documentor_options;?>[feedback_frminputs]" id="documentor_feedback_frminputs" placeholder="Enter Comma Seperated Values" class="sfrminput" value="<?php echo esc_attr($documentor_curr['feedback_frminputs']);?>">
						</div>
						<div class="txt-fld">
							<label for="" class="lbl"><?php _e('Feedback Text','documentor-lite'); ?></label>
							<div class="eb-switch eb-switchl">
								<input type="hidden" name="<?php echo $documentor_options;?>[feedback_frmtext]" id="documentor_feedback_frmtext" class="hidden_check" value="<?php echo esc_attr($documentor_curr['feedback_frmtext']);?>">
								<input id="feedback_frmtext" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['feedback_frmtext']); ?>>
								<label for="feedback_frmtext"></label>
							</div>
						</div>
						<div class="txt-fld">
							<label for="" class="lbl"><?php _e('Captcha','documentor-lite'); ?></label>
							<div class="eb-switch eb-switchl">
								<input type="hidden" name="<?php echo $documentor_options;?>[feedback_frmcapcha]" id="documentor_feedback_frmcapcha" class="hidden_check" value="<?php echo esc_attr($documentor_curr['feedback_frmcapcha']);?>">
								<input id="feedback_frmcapcha" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['feedback_frmcapcha']); ?>>
								<label for="feedback_frmcapcha"></label>
							</div>
						</div>
						<div id="format-header">
							<p class="format-heading"><?php _e('Email','documentor-lite');?></p>
						</div>
						<div class="txt-fld">
							<label for="" class="lbl"><?php _e('Subject','documentor-lite'); ?></label>
							<input type="text" name="<?php echo $documentor_options;?>[feedback_frmsubject]" id="documentor_feedback_frmsubject" class="sfrminput" value="<?php echo esc_attr($documentor_curr['feedback_frmsubject']);?>">
						</div>
						<div class="txt-fld">
							<label for="" class="lbl"><?php _e('Thank You Message','documentor-lite'); ?></label>
							<div class="msg">	
								<textarea rows="3" name="<?php echo $documentor_options;?>[feedback_thankyoumsg]" id="documentor_feedback_thankyoumsg"><?php echo $documentor_curr['feedback_thankyoumsg'];?></textarea>
							</div>
						</div>
						<div class="btn-fld">
							<input type="submit" name="save-settings" class="button-primary" value="Save">
						</div>
					</div>
				</div>	
				
				<div id="doc-print-options" class="format-form">
					<div id="format-ct">
						<div class="frm-heading"><?php _e('Print Options','documentor-lite');?></div>
						<div class="txt-fld">
							<label for="name" class="lbl"><?php _e('Use window print','documentor-lite'); ?></label>
							<?php 
							//new field added in v1.1
							$documentor_curr['window_print'] = isset( $documentor_curr['window_print'] ) ? $documentor_curr['window_print'] : 0;
							?>
							<div class="eb-switch eb-switchnone">
								<input type="hidden" name="<?php echo $documentor_options;?>[window_print]" id="doc_window_print" class="hidden_check" value="<?php echo esc_attr($documentor_curr['window_print']);?>">
								<input id="window_print" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['window_print']); ?>>
								<label for="window_print"></label>
							</div>
						</div>
						<div class="btn-fld">
							<input type="submit" name="save-settings" class="button-primary" value="Save">
						</div>
					</div>
				</div>	
				<!-- options of social share buttons -->
				<div id="format-social" class="format-form"> 
					<div id="format-ct">
						<div class="frm-heading"><?php _e('Social Share Options','documentor-lite');?></div>
						<div id="format-header">
							<p class="format-heading"><?php _e('Select Social buttons','documentor-lite');?></p>
							<a class="modal_close" href="#"></a>
						</div>
						<div class="txt-fld">
							<label for="name" class="lbl"><?php _e('Facebook','documentor-lite'); ?></label>
							<div class="eb-switch eb-switchnone">
								<input type="hidden" name="<?php echo $documentor_options;?>[socialbuttons][0]" class="hidden_check" value="<?php echo esc_attr($documentor_curr['socialbuttons'][0]);?>">
								<input id="socialbuttons-select1" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['socialbuttons'][0]); ?>>
								<label for="socialbuttons-select1"></label>
							</div>
						</div>
						<div class="txt-fld">
							<label for="name" class="lbl"><?php _e('Twitter','documentor-lite'); ?></label>
							<div class="eb-switch eb-switchnone">
								<input type="hidden" name="<?php echo $documentor_options;?>[socialbuttons][1]" class="hidden_check" value="<?php echo esc_attr($documentor_curr['socialbuttons'][1]);?>">
								<input id="socialbuttons-select2" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['socialbuttons'][1]); ?>>
								<label for="socialbuttons-select2"></label>
							</div>
						</div>
						<div class="txt-fld">
							<label for="name" class="lbl"><?php _e('Google Plus','documentor-lite'); ?></label>
							<div class="eb-switch eb-switchnone">
								<input type="hidden" name="<?php echo $documentor_options;?>[socialbuttons][2]" class="hidden_check" value="<?php echo esc_attr($documentor_curr['socialbuttons'][2]);?>">
								<input id="socialbuttons-select3" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['socialbuttons'][2]); ?>>
								<label for="socialbuttons-select3"></label>
							</div>
							<?php if( !function_exists('curl_version') ) { ?>
								<label><?php _e("To get the count of Google Plus shares, please enable the curl extension of PHP","");?></label>
							<?php }?>
						</div>
						<div class="txt-fld">
							<label for="name" class="lbl"><?php _e('Pinterest','documentor-lite'); ?></label>
							<div class="eb-switch eb-switchnone">
								<input type="hidden" name="<?php echo $documentor_options;?>[socialbuttons][3]" class="hidden_check" value="<?php echo esc_attr($documentor_curr['socialbuttons'][3]);?>">
								<input id="socialbuttons-select4" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['socialbuttons'][3]); ?>>
								<label for="socialbuttons-select4"></label>
							</div>
						</div>
						<div id="format-header">
							<p class="format-heading"><?php _e('Select Format','documentor-lite');?></p>
						</div>
						<div class="txt-fld">
							<label>
								<input type="radio" name="<?php echo $documentor_options;?>[sbutton_style]" <?php checked("square",$documentor_curr['sbutton_style'] );?> value="square" >
								<img src="<?php echo DOCLITE_URLPATH.'core/images/square.png'; ?>">
							</label>
							<label>
								<input type="radio" name="<?php echo $documentor_options;?>[sbutton_style]" <?php checked("round",$documentor_curr['sbutton_style'] );?> value="round" >
								<img src="<?php echo DOCLITE_URLPATH.'core/images/round.png'; ?>">
							</label>
							<label>
								<input type="radio" name="<?php echo $documentor_options;?>[sbutton_style]" <?php checked("squarecount",$documentor_curr['sbutton_style'] );?> value="squarecount" >
								<img src="<?php echo DOCLITE_URLPATH.'core/images/squarecount.png'; ?>">
							</label>
							<label>
								<input type="radio" name="<?php echo $documentor_options;?>[sbutton_style]" <?php checked("squareround",$documentor_curr['sbutton_style'] );?> value="squareround" >
								<img src="<?php echo DOCLITE_URLPATH.'core/images/squareround.png'; ?>">
							</label>
						</div>
						<div id="format-header">
							<p class="format-heading"><?php _e('Display Share Count','documentor-lite');?></p>
						</div>
						<div class="txt-fld">
							<label for="name" class="lbl"><?php _e('Share Count','documentor-lite'); ?></label>
							<div class="eb-switch eb-switchnone">
								<input type="hidden" name="<?php echo $documentor_options;?>[sharecount]" class="hidden_check" value="<?php echo esc_attr($documentor_curr['sharecount']);?>">
								<input id="sharecount" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['sharecount']); ?>>
								<label for="sharecount"></label>
							</div>
						</div>
						<div id="format-header">
							<p class="format-heading"><?php _e('Position','documentor-lite');?></p>
						</div>
						<div class="txt-fld">
							<label>
								<input type="radio" name="<?php echo $documentor_options;?>[sbutton_position]" <?php checked("top",$documentor_curr['sbutton_position'] );?> value="top" ><?php _e('Top','documentor-lite');?>
							</label>
							<label>
								<input type="radio" name="<?php echo $documentor_options;?>[sbutton_position]" <?php checked("bottom",$documentor_curr['sbutton_position'] );?> value="bottom" style="margin-left: 20px;"><?php _e('Bottom','documentor-lite');?>
							</label>
						</div>
						<div class="btn-fld">
							<input type="submit" name="save-settings" class="button-primary" value="Save">
						</div>
					</div>
				</div>
				<!--Indexing Formats -->
				
				<div id="format-index" class="format-form">
					<div id="format-ct">
						<div class="frm-heading"><?php _e('Index Formatting','documentor-lite');?></div>
						
						<table class="form-table settings-tbl">	
							<tr valign="top">
								<th scope="row"><?php _e('Parent Index Format','documentor-lite'); ?></th>
								<td>
									<select name="<?php echo $documentor_options;?>[pif]" >
										<option value="decimal" <?php if ($documentor_curr['pif'] == "decimal"){ echo "selected";}?> >Decimal</option>
										<option value="decimal-leading-zero" <?php if ($documentor_curr['pif'] == "decimal-leading-zero"){ echo "selected";}?> >Decimal leading zero</option>
										<option value="lower-roman" <?php if ($documentor_curr['pif'] == "lower-roman"){ echo "selected";}?> >Lower Roman</option>
										<option value="upper-roman" <?php if ($documentor_curr['pif'] == "upper-roman"){ echo "selected";}?> >Upper Roman</option>
										<option value="lower-alpha" <?php if ($documentor_curr['pif'] == "lower-alpha"){ echo "selected";}?> >Lower Alphabets</option>
										<option value="upper-alpha" <?php if ($documentor_curr['pif'] == "upper-alpha"){ echo "selected";}?> >Upper Alphabets</option>
									</select>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Child Index Format','documentor-lite'); ?></th>
								<td>
									<select name="<?php echo $documentor_options;?>[cif]" >
										<option value="decimal" <?php if ($documentor_curr['cif'] == "decimal"){ echo "selected";}?> >Decimal</option>
										<option value="decimal-leading-zero" <?php if ($documentor_curr['cif'] == "decimal-leading-zero"){ echo "selected";}?> >Decimal leading zero</option>
										<option value="lower-roman" <?php if ($documentor_curr['cif'] == "lower-roman"){ echo "selected";}?> >Lower Roman</option>
										<option value="upper-roman" <?php if ($documentor_curr['cif'] == "upper-roman"){ echo "selected";}?> >Upper Roman</option>
										<option value="lower-alpha" <?php if ($documentor_curr['cif'] == "lower-alpha"){ echo "selected";}?> >Lower Alphabets</option>
										<option value="upper-alpha" <?php if ($documentor_curr['cif'] == "upper-alpha"){ echo "selected";}?> >Upper Alphabets</option>
									</select>
								</td>
							</tr>
						</table>
						<p>
							<input type="submit" name="save-settings" class="button-primary" value="Save">
						</p>
					</div>
				</div>
							
				
				<!-- Guide title options -->
				<div id="options-guidetitle" class="format-form">
					<div id="format-ct">
						<div class="frm-heading"><?php _e('Guide Title Formatting','documentor-lite');?></div>
						<table class="form-table settings-tbl">	
							<tr valign="top">
								<th scope="row"><?php _e('Element','documentor-lite'); ?></th>
								<td>
									<select name="<?php echo $documentor_options;?>[guidet_element]" >
										<option value="1" <?php if ($documentor_curr['guidet_element'] == "1"){ echo "selected";}?> >h1</option>
										<option value="2" <?php if ($documentor_curr['guidet_element'] == "2"){ echo "selected";}?> >h2</option>
										<option value="3" <?php if ($documentor_curr['guidet_element'] == "3"){ echo "selected";}?> >h3</option>
										<option value="4" <?php if ($documentor_curr['guidet_element'] == "4"){ echo "selected";}?> >h4</option>
										<option value="5" <?php if ($documentor_curr['guidet_element'] == "5"){ echo "selected";}?> >h5</option>
										<option value="6" <?php if ($documentor_curr['guidet_element'] == "6"){ echo "selected";}?> >h6</option>
									</select>
								</td>
							</tr>

							<tr valign="top">
								<th scope="row"><?php _e('Inherit from Active Theme','documentor-lite'); ?></th>
								<td>
									<div class="eb-switch eb-switchnone havemoreinfo">
										<input type="hidden" name="<?php echo $documentor_options;?>[guidet_default]" id="guidet-default" class="hidden_check" value="<?php echo esc_attr($documentor_curr['guidet_default']);?>">
										<input id="guidet-def" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked('1', $documentor_curr['guidet_default']); ?>>
										<label for="guidet-def"></label>
									</div>
								</td>
							</tr>				
							<tr valign="top">
								<th scope="row"><?php _e('Color','documentor-lite'); ?></th>
								<td>
									<input type="text" name="<?php echo $documentor_options;?>[guidet_color]" id="guidet_color" value="<?php echo esc_attr($documentor_curr['guidet_color']); ?>" class="wp-color-picker-field" data-default-color="#D8E7EE" />
								</td>
						   	</tr>
						    
							<tr valign="top">
								<th scope="row"><?php _e('Font','documentor-lite'); ?></th>
								<td>
									<input type="hidden" value="guidetitle_font" class="ftype_rname">
									<input type="hidden" value="guidet_fontg" class="ftype_gname">
									<input type="hidden" value="guidet_custom" class="ftype_cname">
									<select name="<?php echo $documentor_options;?>[guidet_font]" id="guidet_font" class="main-font">
	
										<option value="regular" <?php selected( $documentor_curr['guidet_font'], "regular" ); ?> > Regular Fonts </option>
										<option value="google" <?php selected( $documentor_curr['guidet_font'], "google" ); ?> > Google Fonts </option>
										<option value="custom" <?php selected( $documentor_curr['guidet_font'], "custom" ); ?> > Custom Fonts </option>
									</select>
								</td>
							</tr>

							<tr><td class="load-fontdiv" colspan="2"></td></tr>

							<tr valign="top">
							<th scope="row"><?php _e('Font Size','documentor-lite'); ?></th>
							<td><input type="number" name="<?php echo $documentor_options;?>[guidet_fsize]" id="guidet_fsize" class="small-text" value="<?php echo esc_attr($documentor_curr['guidet_fsize']); ?>" min="1" />&nbsp;<?php _e('px','documentor-lite'); ?></td>
							</tr>

							<tr valign="top" class="font-style">
								<th scope="row"><?php _e('Font Style','documentor-lite'); ?></th>
								<td>
									<select name="<?php echo $documentor_options;?>[guidet_fstyle]" id="guidet_fstyle" class="font-style" >
									<option value="bold" <?php if ($documentor_curr['guidet_fstyle'] == "bold"){ echo "selected";}?> ><?php _e('Bold','documentor-lite'); ?></option>
									<option value="bold italic" <?php if ($documentor_curr['guidet_fstyle'] == "bold italic"){ echo "selected";}?> ><?php _e('Bold Italic','documentor-lite'); ?></option>
									<option value="italic" <?php if ($documentor_curr['guidet_fstyle'] == "italic"){ echo "selected";}?> ><?php _e('Italic','documentor-lite'); ?></option>
									<option value="normal" <?php if ($documentor_curr['guidet_fstyle'] == "normal"){ echo "selected";}?> ><?php _e('Normal','documentor-lite'); ?></option>
									</select>
								</td>
							</tr>
						</table>
						<p>
							<input type="submit" name="save-settings" class="button-primary" value="Save">
						</p>
					</div>
				</div>
	<?php }
		public static function doc_show_posts() {
			check_ajax_referer( 'documentor-sections-nonce', 'sections_nonce' );
			global $paged,$wpdb,$post; 
			$pages = '';
			$paged = isset($_POST['paged'])?intval($_POST['paged']):'';
			$post_type = isset($_POST['post_type'])?sanitize_text_field($_POST['post_type']):'';
			$docid = isset($_POST['docid'])?intval($_POST['docid']):'';
			$stext = isset($_POST['search_text'])?sanitize_text_field($_POST['search_text']):'';
			$range = 10;
			$html = '';
			$showitems = ($range * 2)+1; 
			if(empty($paged)) $paged = 1;
			$sec = new DocumentorLiteSection();
			$pidarr = $sec->get_addedposts( $docid );
			if( count( $pidarr ) > 0 ) {
				$args = array(
					'post_type' => $post_type,
					'posts_per_page'=>10,	
					'post_status'   => 'publish',
					'paged'=>$paged,
					's'=>$stext,
					'post__not_in' => $pidarr
				);
			} else {
				$args = array(
					'post_type' => $post_type,
					'posts_per_page'=>10,	
					'post_status'   => 'publish',
					'paged'=>$paged,
					's'=>$stext,
				);
			}
			$the_query = new WP_Query( $args );
			$i=0;
			// The Loop
			if ( $the_query->have_posts() ) {
				//$html .= '<div style="margin-left: 20px;" >';
				$html .= '<h3 class="nav-tab-wrapper p-tabs">'; 
						  $tabnm = ( $post_type == 'post' || $post_type == 'page' ) ? $post_type.'s' : $post_type;
						  $html .= '<a id="recent-tabcontent-tab" class="nav-tab recent-tabcontent-tab" title="Recent '.$tabnm.'" href="#recent-tabcontent">Recent '.$tabnm.'</a> 
						  <a id="search-tabcontent-tab" class="nav-tab search-tabcontent-tab" title="Search" href="#search-tabcontent">Search</a>
					</h3>';
				$html .= '<!--<form name="eb-wp-posts" id="eb-wp-posts" method="post" >-->
					<div id="recent-tabcontent" class="pgroup recent-tabcontent">
					';
				$html .= '<table class="wp-list-table widefat sliders" >';
				$html .= '<col width="10%">
					<col width="70%">
					<col width="20%">
						<thead>
						<tr>
							<th class="docpost-id">'. __('ID','documentor-lite').'</th>
							<th class="docpost-title">'. __('Name','documentor-lite').'</th>	
							<th class="docpost-editlnk">'. __('Edit Link','documentor-lite').'</th>
						</tr>
						</thead>';
				
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$i++;
					$html .= '<tr>';
					$html .= '<td><input type="checkbox" name="post_id[]" value="'.esc_attr(get_the_ID()).'"></td>';
					$html .= '<td>' . get_the_title() . '</td>';
					if($post_type == 'attachment' ) {
						$html .= '<td> <img src="'. wp_get_attachment_url(  ).'" width="50" height="30" /> </td>';
					}
					$editlink = '';
					if( post_type_exists($post_type) ) { 
						if( current_user_can('edit_post', get_the_ID()) ) {
							$edtlink = get_edit_post_link(get_the_ID());
							$editlink = '<a href="'.$edtlink.'" target="_blank" class="section-editlink">'. __('Edit','documentor-lite').'</a>';
						}
					}
					$html .= '<td>'.$editlink.'</td>';
					$html .= '</tr>';
				}
				$html .= '</table>';
				if($pages == '') {
					$pages = $the_query->max_num_pages;
					if(!$pages) {
						$pages = 1;
					}
				}  

				if(1 != $pages)
				{
					if($paged > 1 ) $prev = ($paged - 1); else $prev = 1;
					$html .= "<div class=\"eb-cs-pagination\"><span>". __('Page','documentor-lite')." ".$paged.__('of','documentor-lite')." ".$pages."</span>";
					$html .= "<a id='1' class='pageclk' >&laquo; ".__('First','documentor-lite')."</a>";
					$html .= "<a id='".$prev."' class='pageclk' >&lsaquo; ".__('Previous','documentor-lite')."</a>";

					for ($i=1; $i <= $pages; $i++) {
						if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
							$html .= ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a id=\"$i\" class=\"inactive pageclk\">".$i."</a>";
						}
					}
					if( $paged + 1 > $pages ) $nextpg = 1;
					else $nextpg = $paged + 1;
					$html .= "<a id=\"".$nextpg ."\" class='pageclk' >".__('Next','documentor-lite')." &rsaquo;</a>"; 
					$html .= "<a id='".$pages."' class='pageclk' >".__('Last','documentor-lite')." &raquo;</a>";
					$html .= "<div class='clrleft'></div></div>\n";
				}
				$html .= "<p class='clrleft'><input type='submit' name='add_posts' value='".__('Insert','documentor-lite')."' class='button is-primary add_posts' /> &nbsp; <a class='button is-light' href='". esc_url(admin_url('admin.php?page=documentor-admin&action=edit&id='.$docid.'&tab=sections'))."'><span class='dashicons dashicons-undo doc-back'></span>". __('Back to Edit','documentor-lite')."</a></p>\n";
				$html .= '<input type="hidden" name="docid" value="'.esc_attr($docid).'" />';
				$html .= '<input type="hidden" name="post_type" class="post_type" value="'.esc_attr($post_type).'" />';
				$html .= '</div>';
				$html .= '<div id="search-tabcontent" class="pgroup search-tabcontent content">';
				$html .= '<input type="text" name="search-input" class="search-input input" placeholder="'.__('Enter search text','documentor-lite').'" />';
				$html .= '<div class="load-searchresults"></div><!--</form>-->';
				$html.='<!--</div>-->';
				echo $html;
				/* Restore original Post Data */
				wp_reset_postdata();
			} else {
				_e('No entries found','documentor-lite');
			}
			die();
		}
		//show search results of page/posts
		public static function show_search_results() {
			check_ajax_referer( 'documentor-sections-nonce', 'sections_nonce' );
			global $paged,$wpdb,$post; 
			$pages = '';
			$paged = isset($_POST['paged'])?intval($_POST['paged']):'';
			$post_type = isset($_POST['post_type'])?sanitize_text_field($_POST['post_type']):'';
			$docid = isset($_POST['docid'])?intval($_POST['docid']):'';
			$stext = isset($_POST['search_text'])?sanitize_text_field($_POST['search_text']):'';
			$range = 10;
			$html = '';
			$showitems = ($range * 2)+1; 
			if(empty($paged)) $paged = 1;
			$args = array(
				'post_type' => $post_type,
				'posts_per_page'=>10,	
				'post_status'   => 'publish',
				'paged'=>$paged,
				's'=>$stext
			);
			$the_query = new WP_Query( $args );
			$i=0;
			$section = new DocumentorLiteSection();
			// The Loop
			if ( $the_query->have_posts() ) {
				$html .= '<table class="wp-list-table widefat sliders" >';
				$html .= '<col width="10%">
					<col width="70%">
					<col width="20%">
						<thead>
						<tr>
							<th class="docpost-id">'. __('ID','documentor-lite').'</th>
							<th class="docpost-title">'. __('Name','documentor-lite').'</th>	
							<th class="docpost-editlnk">'. __('Edit Link','documentor-lite').'</th>
						</tr>
						</thead>';
				
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					if( $section->is_sectionpresent(get_the_ID(), $docid) ){
						$checked='checked';
						$disabled=' disabled';
					}
					else{
						$checked=$disabled='';
					}
					$i++;
					$html .= '<tr>';
					$html .= '<td><input type="checkbox" name="post_id[]" value="'.esc_attr(get_the_ID()).'"'.$checked.$disabled.' /></td>';
					$html .= '<td>' . get_the_title() . '</td>';
					if($post_type == 'attachment' ) {
						$html .= '<td> <img src="'. wp_get_attachment_url(  ).'" width="50" height="30" /> </td>';
					}
					$editlink = '';
					if( post_type_exists($post_type) ) { 
						if( current_user_can('edit_post', get_the_ID()) ) {
							$edtlink = get_edit_post_link(get_the_ID());
							$editlink = '<a href="'.$edtlink.'" target="_blank" class="section-editlink">'. __('Edit','documentor-lite').'</a>';
						}
					}
					$html .= '<td>'.$editlink.'</td>';
					$html .= '</tr>';
				}
				$html .= '</table>';
				if($pages == '') {
					$pages = $the_query->max_num_pages;
					if(!$pages) {
						$pages = 1;
					}
				}  

				if(1 != $pages) {
					if($paged > 1 ) $prev = ($paged - 1); else $prev = 1;
					$html .= "<div class=\"eb-cs-pagination\"><span>".__('Page','documentor-lite')." ".$paged." ".__('of','documentor-lite')." ".$pages."</span>";
					$html .= "<a id='1' class='pageclk-search' >&laquo; ".__('First','documentor-lite')."</a>";
					$html .= "<a id='".$prev."' class='pageclk-search' >&lsaquo; ".__('Previous','documentor-lite')."</a>";


					for ($i=1; $i <= $pages; $i++) {
						if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
							$html .= ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a id=\"$i\" class=\"inactive pageclk-search\">".$i."</a>";
						}
					}
					if( $paged + 1 > $pages ) $nextpg = 1;
					else $nextpg = $paged + 1;
					$html .= "<a id=\"".$nextpg ."\" class='pageclk-search' >".__('Next','documentor-lite')." &rsaquo;</a>"; 
					$html .= "<a id='".$pages."' class='pageclk-search' >".__('Last','documentor-lite')." &raquo;</a>";
					$html .= "<div class='clrleft'></div></div>\n";
				}
				$html .= "<p class='clrleft'><input type='submit' name='add_posts' value='".__('Insert','documentor-lite')."' class='button is-primary add_posts' /> &nbsp; <a class='button is-light' href='". esc_url(admin_url('admin.php?page=documentor-admin&action=edit&id='.$docid.'&tab=sections'))."'><span class='dashicons dashicons-undo doc-back'></span>". __('Back to Edit','documentor-lite')."</a></p>\n";
				echo $html;
			} else {
				_e('No entries found','documentor-lite');
			}	
			die();
		}
		function populate_documentor_current( $documentor_curr=array() ) {
			$default_documentor_settings = documentor_lite_default_settings();
			
			$skin=isset( $documentor_curr['skin'] ) ? $documentor_curr['skin'] : '';
			if(!empty($skin)){
				$skin_defaults_str='default_settings_'.$skin;
				include_once(dirname(dirname (__FILE__)) . '/skins/'.$skin.'/settings.php');
				if( is_array($skin_defaults_str) ){
					$default_documentor_settings=array_merge($default_documentor_settings, ${$skin_defaults_str});
				}
			}
			
			foreach( $default_documentor_settings as $key => $value ){
				if( !isset( $documentor_curr[$key] ) ) $documentor_curr[$key] = $value;
			}
			return $documentor_curr;
		}
		//load preview at admin panel
		public static function load_preview() {
			$html = '';
			$docid = isset( $_POST['docid'] ) ? intval($_POST['docid']) : '';
			$guide = new DocumentorLiteGuide( $docid );
			$settings = $guide->get_settings();
			$stylepath = 'skins/'.$settings["skin"].'/style.css';
			$printstyle = 'skins/'.$settings["skin"].'/print_style.css';
			$jspath = 'core/js/documentor.js';
			$printjs = 'core/js/jQuery.print.js';
			if( !empty( $docid ) ) {
				$html.="<link rel='stylesheet' id='doc_".$settings["skin"]."_css-css'  href='".DocumentorLite::documentor_plugin_url( $stylepath )."' type='text/css' media='all' /><link rel='stylesheet' id='doc_".$settings["skin"]."_print-css'  href='".DocumentorLite::documentor_plugin_url( $printstyle )."' type='text/css' media='print' />";
				$html.="<script type='text/javascript' src='".DocumentorLite::documentor_plugin_url( $jspath )."'></script>";
				$html.="<script type='text/javascript' src='".DocumentorLite::documentor_plugin_url( $printjs )."'></script>";
				$html.="<div class='doc-preview-msg'>".__('This is a preview of your document. Features like print, scroll to particular section, animations, search are not available in Preview. Once you embed the document on front-end, those effects will be functional!','documentor-lite')."</div>";
				$html.=do_shortcode('[documentor '.$docid.']');
			}
			echo $html;
			die();
		}
		public function hex2rgb($hex) {
			$hex = str_replace("#", "", $hex);
			if(strlen($hex) == 3) {
				$r = hexdec(substr($hex,0,1).substr($hex,0,1));
				$g = hexdec(substr($hex,1,1).substr($hex,1,1));
				$b = hexdec(substr($hex,2,1).substr($hex,2,1));
			} else {
				$r = hexdec(substr($hex,0,2));
				$g = hexdec(substr($hex,2,2));
				$b = hexdec(substr($hex,4,2));
			}
			$rgb = array($r, $g, $b);
			return $rgb; // returns an array with the rgb values
		}
		
		/* Search in document */
		public static function get_search_results() {
			$term = strtolower( $_REQUEST['term'] );
			$docid = isset( $_REQUEST['docid'] ) ? $_REQUEST['docid'] : '';
			$suggestions = array();
			if( !empty( $docid ) ) {
				global $wpdb,$table_prefix;
				$postids = $wpdb->get_col('SELECT post_id FROM '.$table_prefix.DOCUMENTORLITE_SECTIONS.' WHERE doc_id = '.$docid);
				$includearr =  array();
				if( $postids ) $includearr = $postids;
				$args = array(
					'post_type' => array( 'post', 'page', 'documentor-sections'),
					'posts_per_page' => -1,	
					'post_status'   => 'publish',
					's'=> $term,
			 	);
			 	$the_query = new WP_Query( $args );
			 	while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$suggestion = array();
					if( in_array( get_the_ID(), $includearr ) ) {
						$lbl = get_post_meta(get_the_ID(),'_documentor_sectiontitle', true);
						$suggestion['label'] = $lbl;
						$sec_post=get_post( get_the_ID() );
						$slug = $sec_post->post_name;
						$suggestion['slug'] = $slug;
						$suggestions[] = $suggestion;
					}
				}
				wp_reset_postdata();
			}
			// JSON encode and echo
			$response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";
			echo $response;
			die();
		}
		/* Reset feedback counts of whole document */
		public static function reset_feedback_count() {
			check_ajax_referer( 'documentor-sections-nonce', 'sections_nonce' );
			$docid = isset( $_POST['docid'] ) ? $_POST['docid'] : '';
			$res = '';
			if( !empty( $docid ) ) {
				global $wpdb,$table_prefix;
				$sectbl = $table_prefix.DOCUMENTORLITE_SECTIONS;
				$res = $wpdb->update( 
					$sectbl, 
					array( 
						'upvote' => 0,
						'downvote' => 0
					), 
					array( 'doc_id' => $docid ), 
					array( '%d', '%d' ), 
					array( '%d' ) 
				);
			}
			if( false !== $res ) {
				$msg = 'Feedback counters reset successfully!!';
			} else {
				$msg = 'Some error occured. Please try again.';	
			}
			_e( $msg, 'documentor-lite' ); 
			die();	
		}
}// class DocumentorLiteGuide ends
?>
