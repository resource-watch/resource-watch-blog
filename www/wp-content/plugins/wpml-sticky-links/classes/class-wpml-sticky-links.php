<?php
  
  
class WPML_Sticky_Links{
    var $settings;
    var $broken_links;
	/**
	 * @var AbsoluteLinks
	 */
	var $absolute_links_object;

	function __construct( $ext = false ) {
		$this->settings = get_option( 'alp_settings' );

		$this->init_hooks();
	}

	public function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 0 );
		//init WPML_Sticky_Links after init AbsoluteLinks
		add_action( 'init', array( $this, 'init' ), 1001 );
		add_action( 'init', array( $this, 'plugin_localization' ) );
	}

	function plugins_loaded() {
		// Check if WPML is active. If not display warning message and not load Sticky links

		if ( defined( 'ICL_PLUGIN_PATH' ) ) {
			$this->absolute_links_object = new AbsoluteLinks;
		}
	}

	function init() {

		if ( !defined( 'ICL_SITEPRESS_VERSION' ) || ICL_PLUGIN_INACTIVE ) {
			add_action( 'admin_notices', array( $this, '_no_wpml_warning' ) );
		} elseif ( version_compare( ICL_SITEPRESS_VERSION, '2.0.5', '<' ) ) {
			add_action( 'admin_notices', array( $this, '_old_wpml_warning' ) );
		}

		if ( !defined( 'ICL_PLUGIN_PATH' ) ) {
			return;
		}

		global $sitepress_settings;

		$this->ajax_responses();

		add_action( 'save_post', array( $this, 'save_default_urls' ), 120, 2 );
		add_action( 'admin_head', array( $this, 'js_scripts' ) );

		add_filter( 'the_content', array( $this, 'show_permalinks' ), 0 );

		if ( $this->settings[ 'sticky_links_widgets' ] ) {
			add_filter( 'widget_text', array( $this, 'show_permalinks' ), 99 ); // low priority - allow translation to be set
		}
		if ( $this->settings[ 'sticky_links_widgets' ] ) {
			add_filter( 'pre_update_option_widget_text', array( $this, 'pre_update_option_widget_text' ), 5, 2 );
		}

		if ( empty( $this->settings ) && !empty( $sitepress_settings[ 'modules' ][ 'absolute-links' ] ) ) {
			$this->settings = $sitepress_settings[ 'modules' ][ 'absolute-links' ];
			$this->save_settings();
		}

		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );

		if ( is_admin() && !defined( 'DOING_AJAX' ) ) {
			add_action( 'admin_print_scripts', array( $this, 'admin_print_scripts' ) );
			add_action( 'admin_print_styles', array( $this, 'admin_print_styles' ) );
		}

		add_action( 'wp_ajax_wpml_sticky_links_save_options', array( $this, '_save_options' ) );

		// add message to WPML dashboard widget
		add_action( 'icl_dashboard_widget_content', array( $this, 'icl_dashboard_widget_content' ) );

	}

	function admin_print_styles() {
		wp_enqueue_style('wpml-sticky-links-css', WPML_STICKY_LINKS_URL . '/res/css/management.css', array(), WPML_STICKY_LINKS_VERSION);
	}

	function admin_print_scripts() {
		wp_enqueue_script('wpml-sticky-links-js', WPML_STICKY_LINKS_URL . '/res/js/scripts.js', array('jquery'), WPML_STICKY_LINKS_VERSION);

		$wpml_sticky_links_ajax_loader_img_src = WPML_STICKY_LINKS_URL . 'res/img/ajax-loader.gif';
		$wpml_sticky_links_ajax_loader_img = '<img src="' . $wpml_sticky_links_ajax_loader_img_src . '" alt="loading" width="16" height="16" />';

		wp_localize_script('wpml-sticky-links-js', 'data', array( 'wpml_sticky_links_ajax_loader_img' => $wpml_sticky_links_ajax_loader_img ));
	}
    
    function _no_wpml_warning(){
        ?>
        <div class="message error"><p><?php printf(__('WPML Sticky Links is enabled but not effective. It requires <a href="%s">WPML</a> in order to work.', 'wpml-sticky-links'), 
            'https://wpml.org/'); ?></p></div>
        <?php
    }

    function _old_wpml_warning(){
        ?>
        <div class="message error"><p><?php printf(__('WPML Sticky Links is enabled but not effective. It is not compatible with  <a href="%s">WPML</a> versions prior 2.0.5.', 'wpml-sticky-links'), 
            'https://wpml.org/'); ?></p></div>
        <?php
    }

    function _save_options(){
        if(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'icl_sticky_save')){
            if (!empty($_POST['icl_sticky_links_widgets'])) {
	            $this->settings['sticky_links_widgets'] = (int) $_POST['icl_sticky_links_widgets'];
            } else {
                $this->settings['sticky_links_widgets'] = 0;
            }
            
            $this->save_settings();        
        }
    }
    
    function save_settings(){
        update_option('alp_settings', $this->settings);
    }
    
    function ajax_responses(){  

        $nonce = filter_input(INPUT_POST, '_icl_sl_nonce');
        if(!isset($_POST['alp_ajx_action']) || ! wp_verify_nonce($nonce, 'wpml_sticky_links_nonce')){
            return;
        }
        global $wpdb;

        $post_types = array();
        foreach($GLOBALS['wp_post_types'] as $key=>$val){
	        if ( $val->public && $key !== 'attachment' ) {
                $post_types[] = $key;
            }
        }
        
        $limit  = 5;
        
        switch($_POST['alp_ajx_action']){
            case 'rescan':
            
                $posts_pages = $wpdb->get_col("
                    SELECT SQL_CALC_FOUND_ROWS p1.ID 
                        FROM {$wpdb->posts} p1 
                        WHERE p1.post_type IN (" . wpml_prepare_in( $post_types ) . ")
                            AND p1.post_status NOT IN ('auto-draft')
                            AND p1.ID NOT IN
                        
                    (
                        SELECT m.post_id FROM {$wpdb->postmeta} m 
                        JOIN {$wpdb->posts} p2 ON p2.ID = m.post_id
                        WHERE m.meta_key = '_alp_processed'
                            AND p2.post_type IN (" . wpml_prepare_in( $post_types ) . ")
                            AND p2.post_status NOT IN ('auto-draft')
                    )
                    ORDER BY p1.ID ASC LIMIT $limit
                ");
                
                if($posts_pages){
                    $found = $wpdb->get_var("SELECT FOUND_ROWS()");                
                    foreach($posts_pages as $ppid){
                        $this->absolute_links_object->process_post($ppid);
                    }
                    echo $found >= $limit ? $found - $limit : 0;
                }else{
                    echo -1;
                }                
                break;
            case 'rescan_reset':
                $affected_rows = $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key='_alp_processed'");
                echo $affected_rows; 
                break;
            case 'use_suggestion':
                $post_id  = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
                $orig_url = filter_input(INPUT_POST, 'orig_url');
                $broken_links = get_post_meta($post_id,'_alp_broken_links', true);
                foreach($broken_links as $k=>$bl){
                    if($k === $orig_url){
                        $broken = $k;
                        $repl = $bl['suggestions'][$_POST['sug_id']]['absolute'];
                        unset($broken_links[$k]);
                        $c = count($broken_links);
                        if($c){
                            update_post_meta($post_id,'_alp_broken_links', $broken_links);
                        }else{
                            delete_post_meta($post_id,'_alp_broken_links');
                        }
                        echo $c.'|'.$bl['suggestions'][$_POST['sug_id']]['perma'];
                        break;
                    }
                }
                if(!empty($broken) && !empty($repl)){
										$q = "SELECT post_content FROM {$wpdb->posts} WHERE ID=%d";
										$q_prepared = $wpdb->prepare($q, $post_id);
                    $post_content = $wpdb->get_var($q_prepared);
                    $post_content = preg_replace('@href="('.$broken.')"@i', 'href="'.$repl.'"', $post_content);
                    $wpdb->update($wpdb->posts, array('post_content' => $post_content), array('ID'=> $post_id));
                }
                break;
            case 'alp_revert_urls':
            
                $posts_pages = $wpdb->get_results("
                    SELECT SQL_CALC_FOUND_ROWS p.ID, p.post_content FROM {$wpdb->posts} p
                    JOIN {$wpdb->postmeta} m ON p.ID = m.post_id
                    WHERE m.meta_key = '_alp_processed'
                      AND p.post_type IN (" . wpml_prepare_in( $post_types ) . ")
                      AND p.post_status NOT IN ('auto-draft')
                    ORDER BY p.ID ASC LIMIT $limit
                ");   
                
                if($posts_pages){
                    $found = $wpdb->get_var("SELECT FOUND_ROWS()");                
                    foreach($posts_pages as $p){
                        $cont = $this->show_permalinks($p->post_content);
						if ( $cont != $p->post_content ) {
							$wpdb->update($wpdb->posts, array('post_content'=>$cont), array('ID'=>$p->ID));
						}
                        delete_post_meta($p->ID,'_alp_processed');
                        delete_post_meta($p->ID,'_alp_broken_links');
                    }
                    echo $found >= $limit ? $found - $limit : 0;
                }else{
                    echo -1;
                }                                    
                break;
        }
        exit;
    }    
    
    function js_scripts(){
        ?>
        <script type="text/javascript">
            addLoadEvent(function(){                     
                jQuery('#alp_re_scan_but').click(alp_toogle_scan);                
                jQuery('#alp_re_scan_but_all').click(alp_reset_scan_flags);
                jQuery('.alp_use_sug').click(alp_use_suggestion);
                jQuery('#alp_revert_urls').click(alp_do_revert_urls);
                
            });
            var alp_scan_started = false;
            var req_timer = 0;
            function alp_toogle_scan(){                       
                if(!alp_scan_started){  
                    alp_send_request(0); 
                    jQuery('#alp_ajx_ldr_1').fadeIn();
                    jQuery('#alp_re_scan_but').attr('value','<?php echo icl_js_escape(__('Running', 'wpml-sticky-links')) ?>');    
                }else{
                    jQuery('#alp_re_scan_but').attr('value','<?php echo icl_js_escape(__('Scan', 'wpml-sticky-links')); ?>');    
                    window.clearTimeout(req_timer);
                    jQuery('#alp_ajx_ldr_1').fadeOut();
                    location.reload();
                }
                alp_scan_started = !alp_scan_started;
                return false;
            }
            
            function alp_send_request(offset){
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo htmlentities($_SERVER['REQUEST_URI']) ?>",
                    data: "alp_ajx_action=rescan&_icl_sl_nonce=<?php echo wp_create_nonce('wpml_sticky_links_nonce') ?>&offset="+offset,
                    success: function(msg){                        
                        if(-1==msg || msg==0){
                            left = '0';
                            alp_toogle_scan();
                        }else{
                            left=msg;
                        }
                        
                        if(left=='0'){
                            jQuery('#alp_re_scan_but').attr('disabled','disabled');    
                        }
                        
                        jQuery('#alp_re_scan_toscan').html(left);
                        if(alp_scan_started){
                            req_timer = window.setTimeout(alp_send_request,3000,offset);
                        }
                    }                                                            
                });
            }
            
            function alp_reset_scan_flags(){
                if(alp_scan_started) return;
                alp_scan_started = false;
                jQuery('#alp_re_scan_but').removeAttr('disabled');    
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo htmlentities($_SERVER['REQUEST_URI']) ?>",
                    data: "alp_ajx_action=rescan_reset&_icl_sl_nonce=<?php echo wp_create_nonce('wpml_sticky_links_nonce') ?>",
                    success: function(msg){    
                        if(msg){
                            alp_toogle_scan()
                        }
                    }                                                            
                })
            }
            function alp_use_suggestion(){
                jqthis = jQuery(this);
                jqthis.parent().parent().css('background-color','#eee');                
                spl = jqthis.attr('id').split('_');
                sug_id = spl[3];
                post_id = spl[4];
                orig_url = jQuery('#alp_bl_'+post_id+'_'+spl[5]).html().replace(/&amp;/,'&').replace(/&/, '%26');
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo htmlentities($_SERVER['REQUEST_URI']) ?>",
                    data: "alp_ajx_action=use_suggestion&_icl_sl_nonce=<?php echo wp_create_nonce('wpml_sticky_links_nonce') ?>&sug_id="+sug_id+"&post_id="+post_id+"&orig_url="+orig_url,
                    success: function(msg){                                                    
                        spl = msg.split('|');
                        jqthis.parent().html('<?php echo icl_js_escape(__('fixed', 'wpml-sticky-links')); ?> - ' + spl[1]);
                    },
                    error: function (msg){
                        alert('Something went wrong');
                        jqthis.parent().parent().css('background-color','#fff');
                    }                                                            
                });
                                
            }
            
            var req_rev_timer = '';
            function alp_do_revert_urls(){
                jQuery('#alp_revert_urls').attr('disabled','disabled');
                jQuery('#alp_revert_urls').attr('value','<?php echo icl_js_escape(__('Running', 'wpml-sticky-links')); ?>');
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo htmlentities($_SERVER['REQUEST_URI']) ?>",
                    data: "alp_ajx_action=alp_revert_urls&_icl_sl_nonce=<?php echo wp_create_nonce('wpml_sticky_links_nonce') ?>",
                    success: function(msg){                                                    
                        if(-1==msg || msg==0){
                            jQuery('#alp_ajx_ldr_2').fadeOut();
                            jQuery('#alp_rev_items_left').html('');
                            window.clearTimeout(req_rev_timer);
                            jQuery('#alp_revert_urls').removeAttr('disabled');                            
                            jQuery('#alp_revert_urls').attr('value','<?php echo icl_js_escape(__('Start', 'wpml-sticky-links')); ?>');                            
                            location.reload();
                        }else{
                            jQuery('#alp_rev_items_left').html(msg + ' <?php echo icl_js_escape(__('items left', 'wpml-sticky-links')); ?>');
                            req_rev_timer = window.setTimeout(alp_do_revert_urls,3000);
                            jQuery('#alp_ajx_ldr_2').fadeIn();
                        }
                    }                                                            
                });
            }
            
        </script>
        <?php
    }
    
    function menu(){
	    if(!defined('ICL_PLUGIN_PATH')) return;
		global $sitepress;
		if(!isset($sitepress) || (method_exists($sitepress,'get_setting') && !$sitepress->get_setting( 'setup_complete' ))) return;

		$top_page = apply_filters('icl_menu_main_page', basename(ICL_PLUGIN_PATH).'/menu/languages.php');
        add_submenu_page($top_page, 
            __('Sticky Links','wpml-sticky-links'), __('Sticky Links','wpml-sticky-links'),
            'wpml_manage_sticky_links', 'wpml-sticky-links', array($this,'menu_content'));
    }
    
    function menu_content(){
        include WPML_STICKY_LINKS_PATH . '/menu/management.php';
        
    }

	function pre_update_option_widget_text( $new_value, $old_value ) {
		global $sitepress;

		if ( isset( $sitepress ) ) {
			global $wpdb;
			$current_language = $sitepress->get_current_language();
			$default_language = $sitepress->get_default_language();
			$sitepress->switch_lang( $default_language );
			$alp_broken_links = array();

			if ( is_array( $new_value ) ) {
				foreach ( $new_value as $k => $w ) {
					if ( isset( $w[ 'text' ] ) ) {
						$new_value[ $k ][ 'text' ] = $this->absolute_links_object->_process_generic_text( $w[ 'text' ], $alp_broken_links );
					}
				}
				if ( $new_value !== $old_value ) {
					$wpdb->update( $wpdb->options, array( 'option_value' => $new_value ), array( 'option_name' => 'widget_text' ) );
				}
			}

			$sitepress->switch_lang( $current_language );
		}

		return $new_value;
	}

	function save_default_urls( $post_id, $post ) {
		if ( $post->post_status == 'auto-draft' || isset( $_POST[ 'autosave' ] ) ) {
			return;
		}
		if ( !in_array( $post->post_type, get_post_types( array( 'show_ui' => true ) ) ) ) {
			return;
		}
		if ( !post_type_supports( $post->post_type, 'editor' ) ) {
			return;
		}
		if ( in_array( $post->post_type, array( 'revision', 'attachment', 'nav_menu_item' ) ) ) {
			return;
		}

		$this->absolute_links_object->process_post( $post_id );
	}

	function show_permalinks( $cont ) {
		global $sitepress;
		
		if ( !isset( $GLOBALS[ '__disable_absolute_links_permalink_filter' ] ) || !$GLOBALS[ '__disable_absolute_links_permalink_filter' ] ) {
			$absolute_to_permalinks = new WPML_Absolute_To_Permalinks( $sitepress );
			$cont = $absolute_to_permalinks->convert_text( $cont );
		}

		return $cont;
	}

	function convert_to_sticky_links_filter( $text ) {
		$alp_broken_links = array();
		return $this->absolute_links_object->_process_generic_text( $text, $alp_broken_links );
	}
	
    function get_broken_links(){
        global $wpdb;
		$broken_links_prepared = $wpdb->prepare( "
		SELECT p2.ID, p2.post_title, p1.meta_value AS links
            FROM {$wpdb->postmeta} p1 JOIN {$wpdb->posts} p2 ON p1.post_id=p2.ID WHERE p1.meta_key=%s AND p1.meta_value<>'array'
            ", array( '_alp_broken_links' ) );
		$this->broken_links = $wpdb->get_results( $broken_links_prepared );
    }
    
    function icl_dashboard_widget_content(){
        ?>
        <div><a href="javascript:void(0)" onclick="jQuery(this).parent().next('.wrapper').slideToggle();" style="display:block; padding:5px; border: 1px solid #eee; margin-bottom:2px; background-color: #F7F7F7;"><?php _e('Sticky links', 'wpml-sticky-links') ?></a></div>

        <div class="wrapper" style="display:none; padding: 5px 10px; border: 1px solid #eee; border-top: 0px; margin:-11px 0 2px 0;"><p><?php 
            echo __('With Sticky Links, WPML can automatically ensure that all links on posts and pages are up-to-date, should their URL change.',
                 'wpml-sticky-links'); ?></p>        

        <p><a class="button secondary" href="<?php echo 'admin.php?page=wpml-sticky-links';?>"><?php 
            echo __('Configure Sticky Links', 'wpml-sticky-links') ?></a></p>    
        
        </div>
                                     
        <?php        
    }
    
    function plugin_action_links($links, $file){
        $this_plugin = basename(WPML_STICKY_LINKS_PATH) . '/plugin.php';
        if($file == $this_plugin) {
            $links[] = '<a href="admin.php?page=wpml-sticky-links">' . 
                __('Configure', 'wpml-sticky-links') . '</a>';
        }
        return $links;
    }
    
    // Localization
    function plugin_localization(){
        load_plugin_textdomain( 'wpml-sticky-links', false, WPML_STICKY_LINKS_FOLDER . '/locale');
    }
}  
