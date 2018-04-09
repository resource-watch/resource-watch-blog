<?php 
if( !class_exists( 'DocumentorLiteAdmin' ) ) {
	class DocumentorLiteAdmin extends DocumentorLite {
		function __construct() {
			if ( is_admin() ) { // admin actions
				add_action('admin_menu', array(&$this, 'documentor_admin_menu'));
				add_action('admin_init', array(&$this, 'documentor_admin_resources'));
				add_action( 'admin_init', array( &$this, 'register_global_settings' ) );
				//hook for updating custom fields
				add_action( 'publish_post', array( &$this, 'update_custom_fields' ) );
				add_action( 'publish_page', array( &$this, 'update_custom_fields' ) );
				add_action( 'edit_post', array( &$this, 'update_custom_fields' ) );
				add_action( 'edit_attachment', array( &$this, 'update_custom_fields' ) ); 
				//delete section when post is deleted
				add_action( 'wp_trash_post', array( &$this, 'doc_delete_section' ) );
				add_filter( 'plugin_action_links',  array( &$this,'documentor_action_links'), 10, 2 );
				//add css in admin header
				add_action( 'admin_head', array( &$this,'admin_css') );

			}
		}
		function admin_css(){ ?>
		     <style>
			     #menu-posts-documentor-sections {
				  display: none !important;
			     }
		     </style>
		<?php
		}
		function documentor_action_links( $links, $file ) {
			if ( $file != DOCUMENTORLITE_PLUGIN_BASENAME )
				return $links;
			
			$url = DocumentorLite::documentor_admin_url(array('page'=>'documentor-admin'));

			$manage_link = '<a href="' . esc_attr( $url ) . '">'
				. esc_html( __( 'Manage','documentor-lite') ) . '</a>';

			array_unshift( $links, $manage_link );

			return $links;
		}
		// function for adding guides page to wp-admin
		function documentor_admin_menu() {
			//User Level
			$documentor_global_curr = get_option('documentor_global_options');
			$user_level= (isset($documentor_global_curr['user_level'])?$documentor_global_curr['user_level']:'publish_posts');			  
			add_menu_page( __('Documentor','documentor-lite'), __('Documentor','documentor-lite'), $user_level,'documentor-admin', null, DocumentorLite::documentor_plugin_url( 'core/images/logo.png'));
			
			add_submenu_page( 'documentor-admin', __('Edit Guide - Documentor','documentor-lite'), __('Guide','documentor-lite'), $user_level,'documentor-admin', array(&$this, 'documentor_guides_page'));
			
			add_submenu_page( 'documentor-admin', __('Add Section to Guide - Documentor','documentor-lite'), __('Add New Section','documentor-lite'), $user_level,'documentor-new-section', array(&$this, 'documentor_new_section'));
			
			add_submenu_page( 'documentor-admin', __('Global Settings - Documentor Lite','documentor-lite'), __('Global Settings','documentor-lite'), 'manage_options','documentor-global-settings', array(&$this, 'documentor_lite_global_settings'));
			
			$go_pro_icon="<span style=\"color:#F44F45;\"><svg width=\"12\" height=\"12\" viewBox=\"0 0 1792 1792\" xmlns=\"http://www.w3.org/2000/svg\"><path fill=\"currentColor\" d=\"M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z\"/></svg></span>";
			add_submenu_page( 'documentor-admin', __('Go Pro - Documentor Pro','documentor-lite'), __('Go PRO ','documentor-lite').$go_pro_icon, $user_level,'documentor-go-pro', array(&$this, 'documentor_go_pro'));

			if( function_exists( 'add_meta_box' ) && function_exists('icl_plugin_action_links') ) {
				$post_types = get_post_types(); 
				foreach($post_types as $post_type) {
					add_meta_box( 'documentor_box', __( 'Documentor' , 'documentor-lite'), array(&$this, 'documentor_custom_box'), $post_type, 'advanced' );
				}
			}
			
		}	
		//update custom fields
		function update_custom_fields( $post_id ) {
			//menu title
			if( isset( $_POST['_documentor_menutitle'] ) ) {
				$documentor_menutitle = get_post_meta( $post_id, '_documentor_menutitle', true );
				$post_documentor_menutitle = $_POST['_documentor_menutitle'];
				if( $documentor_menutitle != $post_documentor_menutitle ) {
					update_post_meta( $post_id, '_documentor_menutitle', $post_documentor_menutitle );	
				}
			}
			//section title
			if( isset( $_POST['_documentor_sectiontitle'] ) ) {
				$documentor_sectiontitle = get_post_meta( $post_id, '_documentor_sectiontitle', true );
				$post_documentor_sectiontitle = $_POST['_documentor_sectiontitle'];
				if( $documentor_sectiontitle != $post_documentor_sectiontitle ) {
					update_post_meta( $post_id, '_documentor_sectiontitle', $post_documentor_sectiontitle );	
				}
			}
			//attach WooCommerce product to document
			if( isset( $_POST['documentor_attachid'] ) ) {
				$documentor_attachid = get_post_meta( $post_id, '_documentor_attachid', true );
				$post_documentor_attachid = $_POST['documentor_attachid'];
				if( $documentor_attachid != $post_documentor_attachid ) {
					update_post_meta( $post_id, '_documentor_attachid', $post_documentor_attachid );	
				}
			}
		}
		//add metabox callback function
		function documentor_custom_box() {
			global $post;
			$post_id = $post->ID;
			$documentor_menutitle = get_post_meta($post_id, '_documentor_menutitle', true);
			$documentor_sectiontitle = get_post_meta($post_id, '_documentor_sectiontitle', true);
			$documentor_attachid = get_post_meta($post_id, '_documentor_attachid', true);	
			$post_type = get_post_type($post_id);			
		?>
			<table class="form-table" style="margin: 0;">
				<tr valign="top">
					<td scope="row">
						<label for="documentor_menutitle"><?php _e('Menu Title ','documentor-lite'); ?></label>
					</td>
					<td>
						<input type="text" name="_documentor_menutitle" class="documentor_menutitle" value="<?php echo esc_attr($documentor_menutitle);?>" size="50" />
					</td>
				</tr>
				<tr valign="top">
					<td scope="row">
						<label for="documentor_sectiontitle"><?php _e('Section Title ','documentor-lite'); ?></label>
					</td>
					<td>
						<input type="text" name="_documentor_sectiontitle" class="documentor_sectiontitle" value="<?php echo esc_attr($documentor_sectiontitle);?>" size="50" />
					</td>
				</tr>
			</table>
		<?php }
		function documentor_admin_resources() {
			if ( isset($_GET['page']) && ( $_GET['page'] == 'documentor-admin' || $_GET['page'] == 'documentor-global-settings' || $_GET['page'] == 'documentor-go-pro' ) ) {
				wp_register_script('jquery', false, false, false, false);
				wp_enqueue_script( 'jquery-ui-tabs' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-autocomplete' ); //autocomplete
				if ( ! did_action( 'wp_enqueue_media' ) ) wp_enqueue_media();
				wp_enqueue_script( 'jquery-nestable', DocumentorLite::documentor_plugin_url( 'core/js/jquery.nestable.js' ), array('jquery'), DOCUMENTORLITE_VER, false);
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );
				
				wp_enqueue_style( 'quicksand-font', 'https://fonts.googleapis.com/css?family=Play', false, DOCUMENTORLITE_VER, 'all' );
				
				wp_enqueue_style( 'documentor-admin-css', DocumentorLite::documentor_plugin_url( 'core/css/admin.css' ), false, DOCUMENTORLITE_VER, 'all');
				wp_enqueue_style( 'fa-css', DocumentorLite::documentor_plugin_url( 'core/includes/font-awesome/css/font-awesome.min.css' ), false, DOCUMENTORLITE_VER, 'all');
				wp_enqueue_style( 'documentor-bulma-css', DocumentorLite::documentor_plugin_url( 'core/css/bulma.css' ), false, DOCUMENTORLITE_VER, 'all');
				
				wp_enqueue_script( 'loadingoverlay', DocumentorLite::documentor_plugin_url( 'core/js/loadingoverlay.min.js' ),	array('jquery'), DOCUMENTORLITE_VER, false);
				
				wp_enqueue_script( 'documentor-admin-js', DocumentorLite::documentor_plugin_url( 'core/js/admin.js' ),array('jquery'), DOCUMENTORLITE_VER, true);
				wp_enqueue_script( 'documentor-modal-js', DocumentorLite::documentor_plugin_url( 'core/js/jquery.leanModal.min.js' ),array('jquery'), DOCUMENTORLITE_VER, false);
			}
		}
		public function documentor_new_section(){
			$url = DocumentorLite::documentor_admin_url(array('page'=>'documentor-admin&action=edit&id=1&tab=add-sections'));
			wp_redirect($url);
		}
		public function documentor_go_pro(){
			echo '<div class="wrap">
				
				<div class="content gopro-text">
				
					<h2><span class="dashicons dashicons-star-filled editguide-icon"></span> Documentor PRO</h2>
					<h4>Make your documentation look Premium!</h4>
					<p>
						<span class="icon is-small">
							<i class="fa fa-check help is-info"></i>
						</span> &nbsp; 
						Multiple pre-designed Skins
					</p>
					<p>
						<span class="icon is-small">
							<i class="fa fa-check help is-info"></i>
						</span> &nbsp; 
						Create Multiple Guides
					</p>
					<p>
						<span class="icon is-small">
							<i class="fa fa-check help is-info"></i>
						</span> &nbsp; 
						PDF Generate
					</p>
					<p>
						<span class="icon is-small">
							<i class="fa fa-check help is-info"></i>
						</span> &nbsp; 
						Export/Import Guides
					</p>
					<p>
						<span class="icon is-small">
							<i class="fa fa-check help is-info"></i>
						</span> &nbsp; 
						Priority Support
					</p>
					
					<div class="block">
						<a class="button is-info" href="https://documentor.in/pricing/?utm_source=wp-lite&utm_medium=gopro-button" target="_blank"><span class="icon">	<i class="fa fa-cart-arrow-down"></i></span> &nbsp; Buy Documentor PRO</a>
						<a href="https://documentor.in/features/?utm_source=wp-lite&utm_medium=gopro-button" target="_blank" class="button is-primary"><span class="icon"><i class="fa fa-info-circle"></i></span> &nbsp; More Information</a>
					</div>
					
					<h2 class="title is-4">Here are some reviews:</h2>
					
					<div class="columns">
						<div class="column"><div class="box">
						  <article class="media">
							<div class="media-left">
							  <figure class="image is-64x64">
								<img src="https://secure.gravatar.com/avatar/6c8a7d9afb6e2bc9854df0c3b9c8fd31?s=150&d=retro&r=g" />
							  </figure>
							</div>
							<div class="media-content">
							  <div class="content">
								<p>
								  <strong>Wow, Create Documentation in an hour!</strong> <small>@rockslid99</small>
								<br>
									Amazing product. I am not comfortable with CSS and HTML and that’s why looking for a pre-designed work. Documentor was the answer. I created my product’s documentation with a nice looking layout in just an hour.
								<br>
									Great work!!
								</p>
								<nav class="level is-mobile">
									<div class="level-left">
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									</div>
								</nav>
							  </div>
							  
							</div>
						  </article>
						</div></div>
						
						<div class="column"><div class="box">
						  <article class="media">
							<div class="media-left">
							  <figure class="image is-64x64">
								<img src="https://secure.gravatar.com/avatar/cfc92d15d6e1d20ea0afb32f27ed25d9?s=150&d=retro&r=g" alt="Image">
							  </figure>
							</div>
							<div class="media-content">
							  <div class="content">
								<p>
								  <strong>A great documentation plugin</strong> <small>@nuverian</small>
								  <br>
								  Great product, easy to use and nice features. The support is great and they even implemented a feature request regarding crayon syntax highlight.
								</p>
								<nav class="level is-mobile">
									<div class="level-left">
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									</div>
								</nav>
							  </div>
							</div>
						  </article>
						</div></div>
					</div><!--.columns-->
					
					<div class="columns">
						<div class="column"><div class="box">
						  <article class="media">
							<div class="media-left">
							  <figure class="image is-64x64">
								<img src="https://secure.gravatar.com/avatar/997e232ce0e9c065dd8712181594176a?s=150&d=retro&r=g" alt="Image">
							  </figure>
							</div>
							<div class="media-content">
							  <div class="content">
								<p>
								  <small>@gbhuk</small>
								  <br>
								 Allowed me to create and lay-out professional looking documentation in minutes. Integrated well into my existing theme (Omega) with no tweaks required.
								</p>
								<nav class="level is-mobile">
									<div class="level-left">
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									</div>
								</nav>
							  </div>
							</div>
						  </article>
						</div></div>
						
						<div class="column"><div class="box">
						  <article class="media">
							<div class="media-left">
							  <figure class="image is-64x64">
								<img src="https://secure.gravatar.com/avatar/763a73e6519dd0234289c4c9a9b6259d?s=150&d=retro&r=g" alt="Image">
							  </figure>
							</div>
							<div class="media-content">
							  <div class="content">
								<p>
								  <strong>Fantastic Work</strong> <small>@Dandy</small> 
								  <br>
								   The Documentor helped me a lot for creating Help Section. The built-in animation effects are very awesome and very easy to edit and modify.
								</p>
								<nav class="level is-mobile">
									<div class="level-left">
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									</div>
								</nav>
							  </div>
							</div>
						  </article>
						</div></div>
					</div><!--.columns-->
					
					<div class="columns">
						<div class="column"><div class="box">
						  <article class="media">
							<div class="media-left">
							  <figure class="image is-64x64">
								<img src="https://secure.gravatar.com/avatar/a8fa88330b58e7e91f3bdeb6c1fa6ba8?s=150&d=retro&r=g" alt="Image">
							  </figure>
							</div>
							<div class="media-content">
							  <div class="content">
								<p>
								  <small>@JohnAP1167</small> <small>31m</small>
								  <br>
								 Documentor is a nice plugin to publish documentation for products. It didn’t take more than 20 minutes with my existing content to create a documentation page. I am sure that page will be helpful for my customers.
								</p>
								<nav class="level is-mobile">
									<div class="level-left">
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									  <span class="icon is-small text is-success"><i class="fa fa-star"></i></span>
									</div>
								</nav>
							  </div>
							</div>
						  </article>
						</div></div>
						
						<div class="column"><div class="box" style="margin: 2em 0;">
							<a style="width: 100%;" class="button is-info is-outlined is-large" href="https://documentor.in/pricing/?utm_source=wp-lite&utm_medium=gopro-button" target="_blank"><span class="icon is-medium"><i class="fa fa-cart-arrow-down"></i></span> &nbsp; Buy Documentor PRO</a>
						</div></div>
					</div><!--.columns-->
					
				</div>
			</div>';
		}
		function documentor_guides_page() {
			// Edit Document
			$id = 1;
			$guide=new DocumentorLiteGuide($id);
			$documentor_curr = $guide->get_settings();
			if(isset($_POST['save-settings'])) {
				$numarr = array('indexformat', 'navmenu_default', 'navmenu_fsize', 'actnavbg_default', 'sectitle_default', 'sectitle_fsize', 'seccont_default', 'seccont_fsize', 'feedback', 'feedback_frmname', 'feedback_frmemail', 'feedback_frmtext', 'feedback_frmcapcha');
				foreach( $_POST['documentor_options'] as $key=>$value ) {
					if(in_array($key,$numarr)) {
						$value = intval($value);
					} else {
						if( is_string( $value ) ) {
							$value = stripslashes($value);
							$value = sanitize_text_field($value);	
						}
					}
					$new_settings_value[$key]=$value;
				}
				if(isset($_POST['documentor_options']['skin']) && $documentor_curr['skin'] != $_POST['documentor_options']['skin'] ) { 
					/* Populate skin specific settings */	
					$skin = $_POST['documentor_options']['skin'];
					$skin_defaults_str='default_settings_'.$skin;
					require_once ( dirname( dirname(__FILE__) ). '/skins/'.$skin.'/settings.php');
					global ${$skin_defaults_str};
					if(count(${$skin_defaults_str})>0){
						foreach(${$skin_defaults_str} as $key=>$value){
							$new_settings_value[$key]=$value;	
						} 
					}
					/* END - Populate skin specific settings */ 
				}
				$newsettings = json_encode($new_settings_value);
				$newtitle = ( isset( $_POST['guidename'] ) ) ? sanitize_text_field($_POST['guidename']) : ''; 
				$guide->update_settings( $newsettings , $newtitle );
			} 	
			$guide->admin_view();
		}
		//global settings
		function documentor_lite_global_settings() { 
			$documentor_global_curr = get_option('documentor_global_options');
			
			$doc = new DocumentorLite();
			$global_options = $doc->documentor_global_options;
			$group='documentor-global-group';
			$documentor_global_options = 'documentor_global_options';
			foreach( $global_options as $key=>$value ) {
				if( !isset( $documentor_global_curr[$key] ) ) 
					$documentor_global_curr[$key]='';
			}
			
			?>
			<div class="global_settings wrap">
			<div class="columns"><div class="column is-two-thirds">
				<h2 class="title is-4"> <?php _e('Documentor Global Settings','documentor-lite'); ?> </h2>
				<form name="documentor_lite_global_settings" method="post" action="options.php">
					<?php settings_fields($group); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Enable Inline Sections','documentor-lite'); ?></th>
							<td>
								<div class="eb-switch eb-switchnone">
									<input type="hidden" name="<?php echo $documentor_global_options;?>[custom_post]" class="hidden_check" id="documentor_custom_post" value="<?php echo esc_attr($documentor_global_curr['custom_post']);?>">
									<input id="documentor_custompost" class="cmn-toggle eb-toggle-round" type="checkbox" <?php checked("1", $documentor_global_curr['custom_post']); ?> >
									<label for="documentor_custompost"></label>
								</div>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Supported Post Types for adding sections','documentor-lite'); ?></th>
							<td>
								<select name="<?php echo $documentor_global_options;?>[custom_posts][]" multiple="multiple" size="3" style="min-height:6em;">
								<?php 
								$args=array(
								  'public'   => true
								); 
								$output = 'objects'; // names or objects, note names is the default
								$post_types=get_post_types($args,$output); 
								
								$exclude_pts = array('attachment','revision','nav_menu_item');
								foreach($exclude_pts as $exclude_pt)
   									 unset($post_types[$exclude_pt]);
								
								$custom_posts_arr=$documentor_global_curr['custom_posts'];
								if(!isset($custom_posts_arr) or !is_array($custom_posts_arr) ) $custom_posts_arr=array();
										foreach($post_types as $post_type) { ?>
										  <option value="<?php echo $post_type->name;?>" <?php if(in_array($post_type->name,$custom_posts_arr)){echo 'selected';} ?>><?php echo $post_type->labels->name;?></option>
										<?php } ?>
								</select>
							</td>
						</tr>
						<?php // Documentor 1.3.3- start ?>
						<tr valign="top">
							<th scope="row"><?php _e('Minimum User Level to create and manage guides','documentor-lite'); ?></th>
							<td><select name="<?php echo $documentor_global_options;?>[user_level]" id="documentor_user_level">
							<option value="manage_options"<?php if ($documentor_global_curr['user_level'] == "manage_options"){ echo "selected";}?> ><?php _e('Administrator','documentor-lite'); ?></option>
							
							<option value="edit_others_posts" <?php if ($documentor_global_curr['user_level'] == "edit_others_posts"){ echo "selected";}?> ><?php _e('Editor and Admininstrator','documentor-lite'); ?></option>
							<option value="publish_posts" <?php if ($documentor_global_curr['user_level'] == "publish_posts"){ echo "selected";}?> ><?php _e('Author, Editor and Admininstrator','documentor-lite'); ?></option>
							<option value="edit_posts" <?php if ($documentor_global_curr['user_level'] == "edit_posts"){ echo "selected";}?> ><?php _e('Contributor, Author, Editor and Admininstrator','documentor-lite'); ?></option>
							</select>
							</td>
						</tr>
						<?php // Documentor 1.3.3- end ?>
						<tr valign="top">
							<th scope="row"><?php _e('Custom Styles','documentor-lite'); ?></th>
							<td>
								<textarea name="<?php echo $documentor_global_options;?>[custom_styles]"  rows="5" cols="40" class="code"><?php echo $documentor_global_curr['custom_styles']; ?></textarea>
							</td>
						</tr>
					</table>
					<p class="submit">
						<input type="submit" name="Save" class="button-primary" value="Save Changes">
					</p>
				</form>	
			</div><!-- /.column .is-two-thirds -->
			<div class="documentor-sidebar column is-one-third">
				<div class="container"><div class="card">
				  <header class="card-header">
					<p class="documentor-logo">
					  <img src="<?php echo DocumentorLite::documentor_plugin_url( 'core/images/documentor-logo.png' );?>" /><small><?php _e('Version','documentor-lite'); echo DOCUMENTORLITE_VER;?></small>
					</p>
				  </header>
				  <footer class="card-footer">
					<a class="card-footer-item" href="https://documentor.in/docs/" target="_blank">Need Help?</a>
					<a class="card-footer-item" href="https://documentor.in/contact-us/" target="_blank">Get Support</a>
				  </footer>
				</div></div>
			</div><!-- /.documentor-sidebar .column -->
			</div><!-- /.columns -->
			</div><!-- /.global_settings -->
		<?php
		}
		function register_global_settings() {
			register_setting( 'documentor-global-group', 'documentor_global_options' );
		}
		//delete post from sections table if post is deleted from posts table
		function doc_delete_section( $pid ) {
			global $wpdb,$table_prefix;
			$post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$table_prefix."posts WHERE ID = %d", $pid ) ); 
			if( $post != NULL ) {
				$wpdb->delete( $table_prefix.DOCUMENTORLITE_SECTIONS, array( 'post_id' => $pid ), array( '%d' ) );		
			}
		}
			
	}//end class
}//end if
new DocumentorLiteAdmin();
?>
