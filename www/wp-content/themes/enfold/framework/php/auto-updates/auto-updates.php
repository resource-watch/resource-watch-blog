<?php 

if(!function_exists('avia_backend_auto_updater'))
{

	if(!current_theme_supports('avia_manual_updates_only'))
	{
		add_action('admin_init', array('avia_auto_updates','init'), 1);
		
		//since the avia framework is not included via hook there need to be some static functions since at the time of admin_init those hooks are already executed
		add_action('avf_option_page_init', array('avia_auto_updates','add_updates_tab'), 1); 
		add_action('avf_option_page_data_init', array('avia_auto_updates','option_page_data'));
	}
	
	class avia_auto_updates{
		
		var $author;
		var $username;
		var $apikey;
		var $themename;
		
		function __construct()
		{
			$this->author 		= "Kriesi";
			$this->username 	= trim(avia_get_option('updates_username'));
			$this->apikey		= trim(avia_get_option('updates_api_key'));
			$this->themename 	= self::get_themename();
			$this->includes();
			$this->hooks();
		}
		
		function hooks()
		{
			add_action('update_bulk_theme_complete_actions', array($this, 'update_complete'),10,2);	
			add_action('upgrader_process_complete', array($this,'re_insert_custom_css'));
			add_action('load-update.php', array($this, 'temp_save_custom_css'), 20 );
			
			$this->temp_save_custom_css();
		}
				
		function includes()
		{
			if(!empty($this->username) && !empty($this->apikey))
			{
				require_once("class-pixelentity-theme-update.php");
				PixelentityThemeUpdate::init($this->username ,$this->apikey,$this->author);
			}
		}
		
		
		function update_complete($updates, $info)
		{
			if(strtolower( $info->get('Name') ) == strtolower( $this->themename ) )
			{
				$updates = array('theme_updates' => '<a target="_parent" href="'.admin_url('admin.php?page=avia').'">Go Back to '.THEMENAME.' Theme Panel</a>');
			}
			return $updates;
		}
		
		function re_insert_custom_css()
		{
			if(isset($this->custom_css_md5) && $this->custom_css_md5 == "1877fc72c3a2a4e3f1299ccdb16d0513") return;
			
			if(isset($this->custom_css))
			{
				$self_update = "<strong>Attention:</strong> We detected some custom styling rules in your custom.css file but could not restore it. Please open the file yourself and add the following content:<br/>
			    		  <textarea class='avia-custom-rules' style='width:90%; min-height:200px;'>".$this->custom_css_content."</textarea>";
			    
			    if (is_writeable($this->custom_css))
			    {	  
					$handle = @fopen( $this->custom_css, 'w' );
					
					if ($handle && fwrite($handle, $this->custom_css_content)) {
				        echo "<strong>Attention:</strong> We detected some custom styling rules in your custom.css file and restored it ;)";
				    }
				    else
				    {
				    	echo $self_update;
				    }
			    }
			    else
			    {
			    	echo $self_update;
			    }
				
			}
			
		}
		
		function temp_save_custom_css()
		{
			if(empty($_GET['themes']) || $_GET['themes'] != strtolower( $this->themename ) ) return;
		
			$css_path = AVIA_BASE.'css/custom.css';
		
			if(file_exists($css_path) && is_readable($css_path))
			{
				$size = filesize($css_path);
				if($size > 0)
				{
					$handle = @fopen( $css_path, 'r' );
				    if ($handle)
				    {
				    	$this->custom_css_content = fread($handle, $size);
				    	$this->custom_css_md5 = md5($this->custom_css_content);
				    	$this->custom_css = $css_path;
				    	fclose($handle);
				    }
				}
			}
		}
		
		
		public static function add_updates_tab($avia_pages)
		{
			$title = __("Theme Update",'avia_framework');
			if(self::check_for_theme_update()) 
			{
				$title .= "<span class='avia-update-count'>1</span>"; 
				add_filter('avia_filter_backend_menu_title', array('avia_auto_updates','sidebar_menu_title'));
			}
			$avia_pages[] = apply_filters('avf_update_theme_tab', array( 'slug' => 'update', 'parent'=>'avia', 'icon'=>"update.png", 'title' =>  $title ));
			
			
			
			return $avia_pages;
		}
		
		public static function sidebar_menu_title($title)
		{
			$title .= '<span class="update-plugins count-1"><span class="plugin-count">1</span></span>';
			return $title;
		}
		
		
		public static function check_for_theme_update()
		{
			$updates = get_site_transient('update_themes');
			
			if(!empty($updates) && !empty($updates->response))
			{
				$theme = wp_get_theme();
				if($key = array_key_exists($theme->get_template(), $updates->response))
				{
					return $updates->response[$theme->get_template()];
				}
			}
			
			return false;
			
		}
		
		public static function option_page_data($avia_elements)
		{
			$avia_elements[] = array(	"name" => "Update your Theme from the WordPress Dashboard",
								"desc" => "If you want to get update notifications for your themes and if you want to be able to update your theme from your WordPress backend you need to enter your Themeforest account name as well as your Themeforest Secret API Key below:",
								"std" => "",
								"slug"	=> "update",
								"type" => "heading",
								"nodescription"=>true);
								
			
			$avia_elements[] =	array(	
						"slug"	=> "update",
						"std"	=> "",
						"name" 	=> "Your Themeforest User Name",
						"desc" 	=> "Enter the Name of the User you used to purchase this theme",
						"id" 	=> "updates_username",
						"type" 	=> "text"
						);
						
			$avia_elements[] =	array(	
						"slug"	=> "update",
						"std"	=> "",
						"name" 	=> "Your Themeforest API Key",
						"desc" 	=> "Enter the API Key of your Account here. <br/>You can <a target='_blank' href='".AVIA_IMG_URL."layout/FIND_API.jpg'>find your API Key here</a>",
						"id" 	=> "updates_api_key",
						"type" 	=> "text"
						);
				
			$avia_elements[] =	array(	
						"slug"	=> "update",
						"std"	=> "",
						"name" 	=> "",
						"desc" 	=> false,
						"id" 	=> "update_notification",
						"use_function" 	=> true,
						"type" 	=> "avia_backend_display_update_notification"
						);				
		
			return $avia_elements;
		}
		
		public static function backend_html()
		{
			$username 	= trim(avia_get_option('updates_username'));
			$apikey		= trim(avia_get_option('updates_api_key'));
			$output 	= "";
			$version 	= self::get_version();
			$themename 	= self::get_themename();
			$parent_string = is_child_theme() ? "Parent Theme (". ucfirst( $themename ).")" : "Theme";
			
			
			if(empty($username) || empty($apikey))
			{
				$output  = "<div class='avia_backend_theme_updates'><h3>Theme Updates</h3>";
				$output .= "Once you have entered and saved your Username and API Key WordPress will check for updates every 12 Hours and notify you here, if one is available <br/><br/> Your current ".$parent_string." Version Number is <strong>".$version."</strong></div>";
			}
			else if($update = self::check_for_theme_update())
			{
				
				$target  	= network_admin_url('update-core.php?action=do-theme-upgrade');
				$new		= $update['new_version'];
				//$themename  = 'Platform'; //testing theme
				
				ob_start();
				wp_nonce_field('upgrade-core');
				$nonce = ob_get_clean();
				
				
				
				$output  = "<div class='avia_backend_theme_updates'>";
				$output .= "<h3>Update Available!</h3>";
				$output .= "A new Version (".$new.") of your ".$parent_string." is available! You are using Version ".$version.". <br/>Do you want to update?<br/><br/>";
				//$output .= "";
				$output .= '<span class="avia_style_wrap"><a href="#" data-avia-popup="avia-tmpl-theme-update" class="avia_button">Update Now!</a></span></div>';
				
				$form = '<form method="post" action="'.$target.'" name="upgrade-themes" class="upgrade">
								<input type="hidden" name="checked[]" value="'.$themename.'" />
								'.$nonce.'
								<input type="hidden" name="_wp_http_referer" value="/wp-admin/update-core.php?action=do-theme-upgrade" />
								<p>
									<strong>Attention: Any modifications made to the <u>Theme Files</u> will be lost when updating. If you did change any files (Custom CSS rules or PHP file modifications for example) make sure to create a theme backup.</strong><br/><br/>Your backend settings, posts and pages wont be affected by the update.<br/>
								</p>
								<p class="avia-popup-button-container">
									<input id="upgrade-themes" class="button" type="submit" value="Update Theme" name="upgrade"/>
									<input id="upgrade-themes-close" class="button button-2nd script-close-avia-popup" type="submit" value="Don\'t Update" name="close"/>
								</p>
							</form>';
				
				$output .= "<script type='text/html' id='avia-tmpl-theme-update'>\n{$form}\n</script>\n\n";	
			}
			else
			{
				$target  	= network_admin_url('update-core.php?force-check=1');
			
				$output  = "<div class='avia_backend_theme_updates'><h3>Theme Updates</h3>";
				$output .= "No Updates available. You are running the latest version! ({$version})";
				$output .= "<br/><br/> <a href='{$target}'>Check Manually</a> </div>";
			}
			
			
		
			return $output;
		}
		
		public static function get_themename()
		{
			$theme = wp_get_theme();
			
			if(is_child_theme())
			{
				$theme = wp_get_theme( $theme->get('Template') );
			}
			
			return $theme->get_template();
		}
		
		public static function get_version()
		{
			$theme = wp_get_theme();
			
			if(is_child_theme())
			{
				$theme = wp_get_theme( $theme->get('Template') );
			}
			
			return $theme->get('Version');
		}
		
		

		public static function init() {
			new avia_auto_updates();
		}
	}

}



//wrapper function so that the html helper class can use the auto update class
function avia_backend_display_update_notification()
{
	return avia_auto_updates::backend_html();
}



