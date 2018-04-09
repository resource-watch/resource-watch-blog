<?php
/**
 * Class that integrates LayerSlider plugin.
 * Supports an option to deactivate the plugin and removes the plugin code automatically on updates
 * 
 * @since 4.2.1
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


/**
 * We might neeed to delete files - we must include this file for WP_Filesystem()
 */
if( is_admin() )
{
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
}


if( ! class_exists( 'Avia_Config_LayerSlider' ) )
{
	class Avia_Config_LayerSlider
	{
		
		/**
		 * Holds the instance of this class ( or of a derived class )
		 * 
		 * @since 4.2.1
		 * @var Avia_Config_LayerSlider 
		 */
		private static $_instance = null;
	
		/**
		 * @since 4.2.1
		 * @var string 
		 */
		protected $theme_plugin_path;

		/**
		 * @since 4.2.1
		 * @var string 
		 */
		protected $theme_plugin_file;
		
		/**
		 *
		 * @since 4.2.1
		 * @var tystringpe 
		 */
		protected $theme_nice_name;

		/**
		 * 
		 * @since 4.2.1
		 */
		protected function __construct() 
		{
			$this->theme_plugin_path = '';
			$this->theme_plugin_file = '';
			$this->theme_nice_name	 = '';
			
			add_action( 'after_setup_theme', array( $this, 'handler_after_setup_theme' ), 10 );
			
			add_filter( 'avf_option_page_data_init', array( $this, 'handler_option_page_data_init' ), 10, 1 );
			add_action( 'layerslider_installed', array( $this, 'handler_layerslider_installed'), 10, 0 );
			add_action( 'layerslider_deactivated', array( $this, 'handler_layerslider_deactivated'), 10, 0 );
			add_action( 'layerslider_uninstalled', array( $this, 'handler_layerslider_uninstalled'), 10, 0 );
			
			
			/**************************/
			/* Include LayerSlider WP */
			/**************************/			
			if( is_admin() )
			{	
				//dont call on plugins page so user can enable the plugin if he wants to
				if( isset( $_SERVER['PHP_SELF'] ) && ( basename( $_SERVER['PHP_SELF'] ) == "plugins.php" ) && $this->original_plugin_folder_exists() ) 
				{
					//	no op
				}
				else 
				{
					add_action( 'init', array( $this, 'handler_include_layerslider' ), 1 );
					add_filter( 'site_transient_update_plugins', array( $this, 'handler_remove_layerslider_update_notification' ), 10, 1 );
				}
				
				add_action( 'wp_loaded', array( $this, 'handler_wp_loaded' ), 10 );
				add_action( 'avia_ajax_after_save_options_page', array( $this, 'handler_after_save_options_page' ), 10, 1 );
				
			}
			else
			{	
				add_action( 'wp', array( $this, 'handler_include_layerslider' ), 45 );
			}
		}
		
		
		/**
		 * Main Avia_Config_LayerSlider Instance
		 *
		 * Ensures only one instance of Avia_Config_LayerSlider is loaded or can be loaded.
		 *
		 * @since 4.2.1
		 * @param string $class_name
		 * @return Avia_Config_LayerSlider - Main instance (or a derived class)
		 */
		public static function instance( $class_name = '' ) 
		{
			$class = empty( $class_name ) ? 'Avia_Config_LayerSlider' : $class_name;
			
			if ( is_null( Avia_Config_LayerSlider::$_instance ) || ( ! Avia_Config_LayerSlider::$_instance instanceof $class ) ) 
			{
				Avia_Config_LayerSlider::$_instance = new $class();
			}
			
			/**
			 * Fallback to ensure that we have the right baseclass
			 */
		   if( ! Avia_Config_LayerSlider::$_instance instanceof Avia_Config_LayerSlider )
		   {
			   Avia_Config_LayerSlider::$_instance = new Avia_Config_LayerSlider();
		   }

			return Avia_Config_LayerSlider::$_instance;
		}
		
		
		/**
		 * Initializations that need enfold framework functions
		 * 
		 * @since 4.2.1
		 */
		public function handler_after_setup_theme()
		{
			$this->theme_plugin_path = get_template_directory() . '/config-layerslider/LayerSlider/';
			$this->theme_plugin_file = $this->theme_plugin_path . 'layerslider.php';
			$this->theme_nice_name	 = substr( avia_backend_safe_string( THEMENAME ), 0, 40 );
			
			/**
			 * Fallback for existing sites - original plugin is activated
			 */
			if( function_exists( 'layerslider' ) )
			{
				update_option( "{$this->theme_nice_name}_layerslider_state", 'activated' );
			}
		}
		
		
		/**
		 * Add our option field to option page
		 * 
		 * @since 4.2.1
		 * @param array $avia_elements
		 * @return array
		 */
		public function handler_option_page_data_init( array $avia_elements )
		{
			
			$avia_elements[] = array(	
									"slug"			=> "builder", 
									"type"			=> "visual_group_start", 
									"id"			=> "avia_layerslider", 
									"nodescription" => true
							);

			if( function_exists( 'layerslider' ) )
			{
				$subtype = array(
							__( 'Original LayerSlider Plugin is used', 'avia_framework' )	=> '',
					);
			}
			else
			{
				if( current_theme_supports('deactivate_layerslider') )
				{
					$key = __( 'Remove Theme Support &quot;deactivate_layerslider&quot; to activate', 'avia_framework' );
				}
				else
				{
					$key = __( 'Activate bundled plugin', 'avia_framework');
				}
				
				$subtype = array(
							$key		=> '',
							__( 'Deactivate but leave plugin files in theme folder', 'avia_framework' )	=> 'deactivate',
					);
			}
			
			$subtype1 = array(
							__( 'Remove theme plugin files only and keep slides', 'avia_framework' )	=> 'remove',
							__( 'Remove theme plugin files and slides', 'avia_framework' )				=> 'delete_all'
						);
			

			$subtype = array_merge( $subtype, $subtype1 );
			
			$desc = __( 'The theme bundles the LayerSlider Plugin which is activated by default if you do not have the original plugin installed.', 'avia_framework' );
			$desc .= '<br/><br/>';
			$desc .= __( 'If you do not want to use this plugin, you can deactivate it or remove it permanently from the theme directory - in that case you can delete all plugin data permanently or keep it for later reuse. The plugin files will be automatically removed on every update.', 'avia_framework' );
			$desc .= '<br/><br/>';
			$desc .= __( 'If you want to use this plugin again later, select &quot;Activate&quot;, save the options and reinstall the theme', 'avia_framework' );

			$avia_elements[] =	array(
								"slug"		=> "builder",
								"name"		=> __( "Integrated (Bundled) LayerSlider Plugin", 'avia_framework' ),
								"desc"		=> $desc,
								"id"		=> "layerslider_activ",
								"type"		=> "select",
								"std"		=> '',
								"no_first"	=> true,
								"subtype"	=> $subtype
									);


			$avia_elements[] = array(	
									"slug"			=> "builder", 
									"type"			=> "visual_group_end", 
									"id"			=> "avia_layerslider_close", 
									"nodescription" => true
							);


			return $avia_elements;
		}
		

		/**
		 * Original plugin was activated
		 * 
		 *		- Save plugin state 
		 *		- Remove google fonts from default install
		 * 
		 * @since 4.2.1
		 */
		public function handler_layerslider_installed()
		{
			update_option( 'ls-google-fonts', array() );
			
			update_option( "{$this->theme_nice_name}_layerslider_state", 'activated' );
		}
		
		
		/**
		 * Original plugin was deactivated
		 * 
		 * @since 4.2.1
		 */
		public function handler_layerslider_deactivated()
		{
			update_option( "{$this->theme_nice_name}_layerslider_state", 'deactivated' );
			update_option( "{$this->theme_nice_name}_layerslider_activated", '0' );
		}
		
		
		/**
		 * Original plugin was uninstalled
		 * 
		 * @since 4.2.1
		 */
		public function handler_layerslider_uninstalled()
		{
			update_option( "{$this->theme_nice_name}_layerslider_state", 'uninstalled' );
		}
		
		
		/**
		 * Checks if the folder of the original plugin exists
		 * 
		 * @since 4.2.1
		 * @return boolean
		 */
		protected function original_plugin_folder_exists()
		{
			return ( is_dir( WP_PLUGIN_DIR . '/LayerSlider' ) || is_dir( WPMU_PLUGIN_DIR . '/LayerSlider') );
		}

		
		/**
		 * Returns, if the layerslider plugin is active and can be used.
		 * This can be either the bundled or the original plugin
		 * 
		 * @since 4.2.1
		 * @return boolean
		 */
		public function is_active()
		{
			$options = avia_get_option();
			$layerslider_activ = isset( $options['layerslider_activ'] ) ? $options['layerslider_activ'] : '';
			
			/**
			 * User selected the option to activate bundled plugin 
			 * This is also a fallback (replaced in 4.2.1 with the option)
			 */
			if( ( '' == $layerslider_activ ) && ( ! current_theme_supports( 'deactivate_layerslider' ) ) )
			{
				return true;
			}
			
			/**
			 * User activated the original plugin
			 */
			if( function_exists( 'layerslider' ) )
			{
				return true;
			}
			
			return false;
		}

		
		/**
		 * Include our bundled plugin files
		 * 
		 * @since 4.2.1
		 */
		public function handler_include_layerslider()
		{	
			/**
			 * Skip in frontend when posts have no layerslider elements
			 */
			if( ! is_admin() ) 
			{
				/**
				 * Check if we need to load our plugin at all
				 */
			   if( ! ( class_exists( 'avia_sc_layerslider' ) && avia_sc_layerslider::post_has_layerslider() ) )
			   {
				   return;
			   }
			}
			
			$options = avia_get_option();
			$layerslider_activ = isset( $options['layerslider_activ'] ) ? $options['layerslider_activ'] : '';
			
			/**
			 * This is for a fallback only (replaced in 4.2.1 with the option)
			 */
			if( ( '' == $layerslider_activ ) && current_theme_supports( 'deactivate_layerslider' ) ) 
			{
				$layerslider_activ = 'deactivate';
			}
			
			/**
			 * Check if user activated the original plugin - this has priority
			 */
			if( function_exists( 'layerslider' ) )
			{
				if( get_option( "{$this->theme_nice_name}_layerslider_activated", '0' ) == '0' ) 
				{
					// Save a flag set that it is activated, so the LayerSlider activation routine won't run again
					update_option( "{$this->theme_nice_name}_layerslider_activated", '1' );
				}
			}
			else if( ! file_exists( $this->theme_plugin_file ) )
			{
				/**
				 * Bundled Plugin files not avilable - we only can return
				 */
				update_option( "{$this->theme_nice_name}_layerslider_activated", '0' );
				return;
			}
			else if( in_array( $layerslider_activ, array( 'deactivate', 'remove', 'delete_all' ) ) )
			{
				/**
				 * User deactivated bundled plugin - we can return
				 * For other achtions like removing data we take care in wp_loaded hook 
				 */
				return;
			}
			else
			{
				/**
				 * Include theme plugin and initialise
				 */
				include_once $this->theme_plugin_file;
				
				$skins = LS_Sources::getSkins();
				$allowed = apply_filters( 'avf_allowed_layerslider_skins', array( 'fullwidth', 'noskin' ) ); //if $allowed is set to bool true all skins are allowed

				if( $allowed !== true )
				{
					foreach( $skins as $key => $skin )
					{
						if( ! in_array( $key, $allowed ) )
						{
							LS_Sources::removeSkin( $key );
						}
					}
				}

				$GLOBALS['lsPluginPath'] 	= get_template_directory_uri() . '/config-layerslider/LayerSlider/';
				$GLOBALS['lsAutoUpdateBox'] = false;
				if( ! defined( 'LS_ROOT_URL' ) )
				{
					define('LS_ROOT_URL', get_template_directory_uri() . '/config-layerslider/LayerSlider' );
				}

				// Activate the plugin if necessary
				if( get_option( "{$this->theme_nice_name}_layerslider_activated", '0' ) == '0' ) 
				{

					// Run activation script
					//layerslider_activation_scripts();

					// Save a flag that it is activated, so this won't run again
					update_option( "{$this->theme_nice_name}_layerslider_activated", '1' );
				}
			}
			
			/**
			 * Initialise options when user activated the plugins
			 */
			update_option( "{$this->theme_nice_name}_layerslider_data_erased", 'no' );
			update_option( 'ls-show-support-notice', 0 );
		}
		
		
		/**
		 * Check, if we have to remove plugin data
		 * 
		 * @since 4.2.1
		 */
		public function handler_wp_loaded()
		{
			global $post;
			
			if( ! is_admin() )
			{
				return;
			}
			
			if( defined('DOING_AJAX') && DOING_AJAX )
			{
				$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
				
				if( 'avia_ajax_save_options_page' != $action )
				{
					return;
				}
			}
			
			
			// don't run if this is an auto save
		    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		    {
				return;
			}
		
		    // don't run if the function is called for saving revision.
		    if ( $post instanceof WP_Post && ( $post->post_type == 'revision' ) )
		    {
				return;
			}
			
			// Only administrators can use this function.
			if( ! current_user_can( 'manage_options' ) ) 
			{
				return;
			}
			
			$options = avia_get_option();
			$layerslider_activ = isset( $options['layerslider_activ'] ) ? $options['layerslider_activ'] : '';
			
			if( ( 'delete_all' == $layerslider_activ ) )
			{
				$this->erase_all_plugin_data();
			}
			
			if( in_array( $layerslider_activ, array( 'remove', 'delete_all' ) ) )
			{
				$this->remove_theme_plugin_files();
			}
		}
		
		
		/**
		 * Called when user saved the theme's option page 
		 * 
		 * @param array $options
		 * @since 4.2.1
		 */
		public function handler_after_save_options_page( array $options )
		{
			$layerslider_activ = isset( $options['avia']['layerslider_activ'] ) ? $options['avia']['layerslider_activ'] : '';
			
			if( ( 'delete_all' == $layerslider_activ ) )
			{
				$this->erase_all_plugin_data();
			}
			
			if( in_array( $layerslider_activ, array( 'remove', 'delete_all' ) ) )
			{
				$this->remove_theme_plugin_files();
			}
		}

		
		/**
		 * Erases all database stored data of this plugin, if the original plugin does not exist.
		 *
		 * @since 4.2.1
		 */
		protected function erase_all_plugin_data()
		{
			
			$state = get_option( "{$this->theme_nice_name}_layerslider_state", '' );
			
			/**
			 * Do not delete data when original plugin exists
			 */
			if( in_array( $state, array( 'activated', 'deactivated' ) ) && $this->original_plugin_folder_exists() )
			{
				update_option( "{$this->theme_nice_name}_layerslider_data_erased", 'no' );
				return;
			}
			
			/**
			 * Fallback - when folder exist we assume plugin was deactivared only
			 */
			if( $this->original_plugin_folder_exists() )
			{
				update_option( "{$this->theme_nice_name}_layerslider_data_erased", 'no' );
				update_option( "{$this->theme_nice_name}_layerslider_state", 'deactivated' );
				return;
			}
			
			$data_erased = get_option( "{$this->theme_nice_name}_layerslider_data_erased", 'no' );
			
			if( 'yes' == $data_erased )
			{
				return;
			}
			
			/**
			 * Erase the data here
			 */
			$this->ls_do_erase_plugin_data();
			
			update_option( "{$this->theme_nice_name}_layerslider_data_erased", 'yes' );
		}
		
		
		/**
		 * Removes the themes plugin folder
		 * 
		 * @since 4.2.1
		 */
		protected function remove_theme_plugin_files()
		{
			global $wp_filesystem;
			
			WP_Filesystem();
			
			if( file_exists( $this->theme_plugin_path ) )
			{
				$wp_filesystem->rmdir( $this->theme_plugin_path, true );
			}
			
			$msg = '';
			if( file_exists( $this->theme_plugin_path ) )
			{
				$msg = __( 'Theme layerslider plugin files could not be deleted.', 'avia_framework' );
			}
			
			update_option( "{$this->theme_nice_name}_layerslider_plugin_remove_error", $msg );
			
		}

		/**
		 * Remove LayerSlider's plugin update notifications if the bundled version is used.
		 * 
		 * @since 4.1.3
		 * @param stdClass $plugins
		 * @return stdClass
		 */
		public function handler_remove_layerslider_update_notification( $plugins ) 
		{

			if( empty( $plugins ) || empty( $plugins->response ) )
			{
				return $plugins;
			}
			
			/**
			 * Is original plugin activated, then show notificaions
			 */
			if( 'activated' == get_option( "{$this->theme_nice_name}_layerslider_state", '' ) )
			{
				return $plugins;
			}

			/**
			 * Path to Enfold LayerSlider WP main PHP files - ensure Windows comp with drives
			 */
			$layerslider = str_replace( '\\', '/', get_template_directory() . '/config-layerslider/LayerSlider/layerslider.php' );

			/**
			 * Supress hiding update notification
			 * 
			 * @since 4.1.3
			 * @return string			'yes'|'no'
			 */
			if( 'no' == apply_filters( 'avf_show_layerslider_update_notification', 'no' ) ) 
			{
				unset( $plugins->response[ $layerslider ] );
			}

			return $plugins;
		}

		
		/**
		 * Select all layersliders from database
		 * 
		 * @since 4.2.1
		 * @param boolean $names_only
		 * @return array
		 */
		public function find_layersliders( $names_only = false )
		{
			// Get WPDB Object
			global $wpdb;

			// Table name
			$table_name = $wpdb->prefix . "layerslider";

			// Get sliders
			$sliders = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY date_c ASC LIMIT 300" );

			if( empty( $sliders ) ) 
			{
				return;
			}

			if( $names_only )
			{
				$new = array();
				foreach( $sliders as $key => $item ) 
				{
					if( empty( $item->name ) ) 
					{
						$item->name = __( "(Unnamed Slider)", "avia_framework" );
					}
					$new[ $item->name ] = $item->id;
				}

				return $new;
			}

			return $sliders;
		}
		
		
		/**
		 * This is a copy/paste of the content of:
		 * enfold\config-layerslider\LayerSlider\wp\actions.php  function ls_do_erase_plugin_data()
		 * 
		 * Exclude: 
		 *
		 *	6. Deactivate LayerSlider
		 * 
		 * Make sure, that the original plugin does not exist at all before calling this function.
		 * All data will be removed on the current site.
		 * 
		 * @since 4.2.1
		 */
		protected function ls_do_erase_plugin_data()
		{
			global $wpdb;
			global $wp_filesystem;

			WP_Filesystem();

			// 1. Remove wp_layerslider & layerslider_revisions DB table
			$wpdb->query("DROP TABLE {$wpdb->prefix}layerslider;");
			$wpdb->query("DROP TABLE {$wpdb->prefix}layerslider_revisions;");

			// 2. Remove wp_option entries
			$options = array(

				// Installation
				'ls-installed',
				'ls-date-installed',
				'ls-plugin-version',
				'ls-db-version',
				'layerslider_do_activation_redirect',

				// Plugin settings
				'ls-screen-options',
				'layerslider_custom_capability',
				'ls-google-fonts',
				'ls-google-font-scripts',
				'ls_use_cache',
				'ls_include_at_footer',
				'ls_conditional_script_loading',
				'ls_concatenate_output',
				'ls_use_custom_jquery',
				'ls_put_js_to_body',

				// Updates & Services
				'ls-share-displayed',
				'ls-last-update-notification',
				'ls-show-support-notice',
				'ls-show-canceled_activation_notice',
				'layerslider_cancellation_update_info',
				'layerslider-release-channel',
				'layerslider-authorized-site',
				'layerslider-purchase-code',
				'ls-latest-version',
				'ls-store-data',
				'ls-store-last-updated',

				// Revisions
				'ls-revisions-enabled',
				'ls-revisions-limit',
				'ls-revisions-interval',

				// Popup Index
				'ls-popup-index',

				// Legacy
				'ls-collapsed-boxes',
				'layerslider-validated',
				'ls-show-revalidation-notice'
			);

			foreach( $options as $key ) {
				delete_option( $key );
			}


			// 3. Remove wp_usermeta entries
			$options = array(
				'layerslider_help_wp_pointer',
				'layerslider_builder_help_wp_pointer',
				'layerslider_beta_program',
				'ls-sliders-layout',
				'ls-store-last-viewed'
			);

			foreach( $options as $key ) {
				delete_metadata('user', 0, $key, '', true);
			}



			// 4. Remove /wp-content/uploads files and folders
			$uploads 	= wp_upload_dir();
			$uploadsDir = trailingslashit($uploads['basedir']);

			foreach( glob($uploadsDir.'layerslider/*/*') as $key => $img ) {

				$imgPath  = explode( parse_url( $uploadsDir, PHP_URL_PATH ), $img );
				$attachs = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $imgPath[1] ) );

				if( ! empty( $attachs ) ) {
					foreach( $attachs as $attachID ) {
						if( ! empty($attachID) ) {
							wp_delete_attachment( $attachID, true );
						}
					}
				}
			}


			$wp_filesystem->rmdir( $uploadsDir.'layerslider', true );
			$wp_filesystem->delete( $uploadsDir.'layerslider.custom.css' );
			$wp_filesystem->delete( $uploadsDir.'layerslider.custom.transitions.js' );


			// 5. Remove debug account
			if( $userID = username_exists('KreaturaSupport') ) {
				wp_delete_user( $userID );
			}
			
			// 6. Deactivate LayerSlider
//			deactivate_plugins( LS_PLUGIN_BASE, false, false );

		}

		
	}		//	end class Avia_Config_LayerSlider
	
	
}

/**
 * Returns the main instance of Avia_Config_LayerSlider to prevent the need to use globals.
 * 
 * To override this class call this function with your classname once. This will replace any stored instance.
 * Subsequent calls can be performed without a classname.
 *
 * @since 4.2.1
 * @param string $class_name
 * @return Avia_Support
 */
function Avia_Config_LayerSlider( $class_name = '' ) 
{
	return Avia_Config_LayerSlider::instance( $class_name );
}


/**
 * Initialise class and hooks
 */
Avia_Config_LayerSlider();
