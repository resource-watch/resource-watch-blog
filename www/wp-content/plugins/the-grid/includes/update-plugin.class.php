<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

if (!class_exists('TG_update_plugin')) {

	/**
	* Creates the theme & plugin arrays & injects API results.
	* @since 1.0.0
	*/
	class TG_update_plugin {

		/**
		* Private plugin array var
		* @since 1.0.0
		*/
		private $plugin;
		private $menu_name = 'The Grid';

		/**
		* A dummy constructor to prevent this class from being loaded more than once.
		* @since 1.0.0
		* @modified 2.1.0
		*/
		public function __construct() {
			
			// retrieve main data to check if auto update is allowed
			$force_register   = get_option('the_grid_force_registration', '');
			$plugin_info      = get_option('the_grid_plugin_info', '');
			$purchase_code    = (isset($plugin_info['purchase_code'])) ? $plugin_info['purchase_code'] : null;
			$unregister_panel = apply_filters('tg_grid_unregister', false);
			
			// if The Grid is register (valid purchase code), or forced to be registered (globall settings), or not unregistered from a theme
			if ($purchase_code || $force_register || !$unregister_panel ) {
			
				$this->get_plugin();
				$this->init_actions();
			
			}

		}

		/**
		* You cannot clone this class.
		* @since 1.0.0
		*/
		public function __clone() {
		}

		/**
		* You cannot unserialize instances of this class.
		* @since 1.0.0
		*/
		public function __wakeup() {
		}
		
		/**
		* Setup the hooks, actions and filters.
		* @since 1.0.0
		*/
		public function get_plugin() {

			$this->plugin = get_option('the_grid_plugin_info', '');

		}

		/**
		* Setup the hooks, actions and filters.
		* @since 1.0.0
		*/
		public function init_actions() {			

			// Update natification buuble for menu
			add_action( 'admin_menu', array( $this, 'add_notification_bubble' ), 999 );

			// Deferred Download because of Envato API
			add_action( 'upgrader_package_options', array( $this, 'maybe_deferred_download' ), 99 );
			
			// On plugin install/update page complete
			add_filter( 'update_plugin_complete_actions', array( $this, 'update_complete' ), 10, 2 );

			// Inject plugin updates into the response array.
			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'update_plugins' ) );
			add_filter( 'pre_set_transient_update_plugins', array( $this, 'update_plugins' ) );
			
			// Update transient state
			add_filter( 'site_transient_update_plugins', array( $this, 'update_state' ) );
			add_filter( 'transient_update_plugins', array( $this, 'update_state' ) );

			// Inject plugin information into the API calls.
			add_filter( 'plugins_api', array( $this, 'plugins_api' ), 10, 3 );
			
			// Add message in plugin page to dowload on CodeCanyon 
			add_action( 'in_plugin_update_message-the-grid/the-grid.php', array( &$this, 'add_plugin_message' ) );
			
		}
		
		/**
		* Add notification bubble in menu item
		* @since 1.1.0
		* @modified 2.1.0
		*/
		public function add_notification_bubble() {
			
			global $submenu, $menu;
			
			// get plugin info.
			$plugin = $this->plugin;
			
			if (isset($plugin) && !empty($plugin)) {
			
				$bubble = '&nbsp;<span class="update-plugins count-1"><span class="plugin-count">1</span></span>';
				
				$menu_name = $this->menu_name;
				
				if (!empty($menu_name) && isset($plugin) && isset($plugin['version']) && version_compare($plugin['version'], TG_VERSION) >  0) {
					
					foreach ($menu as $key => $item) {
						
						if ($item[0] == $menu_name) {
							$menu[$key][0] .= $bubble;
							break;
						}
						
					}
					
				}
				
			}
			
		}
				
		/**
		* Defers building the API download url until the last responsible moment to limit file requests
		* @since 1.0.0
		*/
		public function maybe_deferred_download( $options ) {
						
			$package = $options['package'];
			
			if (false !== strrpos($package, 'deferred_download') && false !== strrpos($package, 'item_id')) {
				
				parse_str( parse_url( $package, PHP_URL_QUERY ), $vars );
				
				if ( $vars['item_id'] ) {
					
					$token = get_option('the_grid_envato_api_token', '');
					$API = new TG_Envato_API();
					$API->init_globals($token);
					$download_link = $API->download($vars['item_id']);
					$options['package'] = $download_link;
					
				}
				
			}
			
			return $options;
			
		}
		
		/**
		* Add link to plugin page after update success
		* @since 1.1.0
		*/
		public function update_complete($actions, $plugin) {
			
			if (isset($plugin) && !empty($plugin) && $plugin == 'the-grid/the-grid.php') {
				$actions = '<a href="'. esc_url( admin_url( 'admin.php?page=the_grid' ) ) .'">'. __( 'Return to The Grid Plugin page.', 'tg-text-domain' ) .'</a>';
			}
			
			return $actions;
			
		}
		
		/**
		* Inject update data for premium plugins
		* @since 1.0.0
		* @modified 2.1.0
		*/
		public function update_plugins( $transient ) {
			
			// Check if the transient contains the 'checked' information
			if (!isset($transient->checked) && empty($transient->checked)) {
				return $transient;
			}

			// get plugin info.
			$plugin = $this->plugin_info();

			if (isset($plugin) && !empty($plugin) && isset($plugin['version']) && version_compare($plugin['version'], TG_VERSION) >  0) {
				
				$API = new TG_Envato_API();

				$_plugin = array(
					'slug'        => $plugin['name'],
					'plugin'      => $plugin['name'],
					'new_version' => $plugin['version'],
					'url'         => $plugin['url'],
					'package'     => $API->deferred_download($plugin['id']),
				);

				$transient->response[$plugin['slug']] = (object) $_plugin;
			}

			return $transient;
			
		}
		
		/**
		* Inject update data for premium plugins
		* @since 1.0.0
		* @modified 2.1.0
		*/
		public function update_state( $transient ) {
			
			$plugin = $this->plugin;

			if (isset($plugin) && !empty($plugin) && isset($plugin['version']) && version_compare($plugin['version'], TG_VERSION) >  0) {
				
				$API = new TG_Envato_API();
				
				$_plugin = array(
					'slug'        => dirname($plugin['slug']),
					'new_version' => $plugin['version'],
					'url'         => $plugin['url'],
					'package'     => $API->deferred_download($plugin['id']),
					'name'        => $plugin['name'],
				);
				
				$transient->response[$plugin['slug']] = (object) $_plugin;
				
			} else {
				
				if (isset($transient->response['the-grid/the-grid.php'])) {
					unset($transient->response['the-grid/the-grid.php']);
				}
				
			}

			return $transient;
			
		}

		/**
		* Inject API data for premium plugins
		* @since 1.0.0
		*/
		public function plugins_api( $response, $action, $args ) {
			
			// get plugin info.
			$plugin = $this->plugin;

			// Process premium theme updates.
			if ('plugin_information' === $action && isset( $args->slug ) && isset($plugin['slug']) && $args->slug === dirname($plugin['slug'])) {
				
				$API = new TG_Envato_API();

				$response                 = new stdClass();
				$response->slug           = dirname($plugin['slug']);
				$response->plugin         = $plugin['slug'];
				$response->plugin_name    = $plugin['name'];
				$response->name           = $plugin['name'];
				$response->version        = $plugin['version'];
				$response->author         = $plugin['author'];
				$response->homepage       = $plugin['url'];
				$response->requires       = $plugin['requires'];
				$response->tested         = $plugin['tested'];
				$response->downloaded     = $plugin['number_of_sales'];
				$response->last_updated   = $plugin['updated_at'];
				$response->sections       = array( 'description' => $plugin['content'] );
				$response->banners['low'] = TG_PLUGIN_URL . '/backend/assets/images/preview-image-update.jpg';
				$response->rating         = $plugin['rating']['rating'] / 5 * 100;
				$response->num_ratings    = $plugin['rating']['count'];
				$response->download_link  = $API->deferred_download($plugin['id']);
				
			}

			return $response;
		}
		
		/**
		* Get plugin info from Envato API
		* @since 1.0.0
		*/
		public function plugin_info() {
			
			$plugin_info  = null;
			$envato_token = get_option('the_grid_envato_api_token', '');
			
			if ($envato_token) {
	
				$API = new TG_Envato_API();
				$API->init_globals($envato_token);
				$plugins = (array) $API->plugins();
				
				foreach ($plugins as $key) {
					
					$id = isset($key['id']) ? $key['id'] : null;
					if ($id == 13306812) {
						
						$plugin_info = array(
							'id'              => $key['id'],
							'slug'            => 'the-grid/the-grid.php',
							'name'            => $key['name'],
							'author'          => $key['author'],
							'version'         => $key['version'],
							'description'     => $key['description'],
							'content'         => $key['content'],
							'url'             => $key['url'],
							'author_url'      => $key['author_url'],
							'license'         => $key['license'],
							'updated_at'      => $key['updated_at'],
							'purchase_code'   => $key['purchase_code'],
							'supported_until' => $key['supported_until'],
							'thumbnail_url'   => $key['thumbnail_url'],
							'landscape_url'   => $key['landscape_url'],
							'requires'        => $key['requires'],
							'tested'          => $key['tested'],
							'number_of_sales' => $key['number_of_sales'],
							'rating'          => $key['rating'],
						);
						
						update_option('the_grid_plugin_info', $plugin_info);
						break;
						
					}
					
				}
			
			}
			
			return $plugin_info;
		
		}
		
		/**
		* Shows message on WP Plugins page
		* @since 1.0.0
		* @modified 2.1.0
		*/
		public function add_plugin_message() {
			
			echo '&nbsp;'. __( 'or', 'tg-text-domain' );
			echo '&nbsp;<a target="_blank" href="http://codecanyon.net/item/the-grid-responsive-grid-builder-for-wordpress/13306812?ref=Theme-one">';
				echo __( 'download new version from CodeCanyon.', 'tg-text-domain' );
			echo '</a>';

		}
		
	}
	
	// run update plugin class
	new TG_update_plugin();

}