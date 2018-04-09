<?php
/**
 * Envato API class.
 *
 * @package Envato_Market
 */

if (!class_exists( 'TG_Envato_API' )) {

	/**
	* Creates the Envato API connection.
	* @version 1.0.0
	*/
	class TG_Envato_API {

		/**
		* The single class instance.
		* @since 1.0.0
		*/
		private static $_instance = null;

		/**
		* The Envato API personal token.
		* @since 1.0.0
		*/
		public $token;

		/**
		* A dummy constructor to prevent this class from being loaded more than once.
		* @since 1.0.0
		*/
		public function __construct() {
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
		* Setup the class globals.
		* @since 1.0.0
		*/
		public function init_globals($token) {
			// Envato API token.
			$this->token = $token;
		}

		/**
		* Query the Envato API.
		* @since 1.0.0
		*/
		public function request( $url, $args = array() ) {
			
			$defaults = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $this->token,
				),
				'timeout' => 20,
			);
			
			$args = wp_parse_args( $args, $defaults );

			$token = trim( str_replace( 'Bearer', '', $args['headers']['Authorization'] ) );
			if ( empty( $token ) ) {
				return new WP_Error( 'api_token_error', __( 'An API token is required.', 'tg-text-domain' ) );
			}

			// Make an API request.
			$response = wp_remote_get( esc_url_raw( $url ), $args );

			// Check the response code.
			$response_code    = wp_remote_retrieve_response_code( $response );
			$response_message = wp_remote_retrieve_response_message( $response );

			if ( 200 !== $response_code && ! empty( $response_message ) ) {
				return new WP_Error( $response_code, $response_message );
			} elseif ( 200 !== $response_code ) {
				return new WP_Error( $response_code, __( 'An unknown API error occurred.', 'tg-text-domain' ) );
			} else {
				$return = json_decode( wp_remote_retrieve_body( $response ), true );
				if ( null === $return ) {
					return new WP_Error( 'api_error', __( 'An unknown API error occurred.', 'tg-text-domain' ) );
				}
				return $return;
			}
			
		}

		/**
		* Deferred item download URL.
		* @since 1.0.0
		*/
		public function deferred_download( $id ) {
			
			if ( empty( $id ) ) {
				return '';
			}

			$args = array(
				'deferred_download' => true,
				'item_id' => $id,
			);
			
			return add_query_arg( $args, esc_url( admin_url( 'admin.php?page=the_grid' ) ) );
			
		}

		/**
		* Get the item download.
		* @since 1.0.0
		*/
		public function download( $id, $args = array() ) {
			
			if ( empty( $id ) ) {
				return false;
			}

			$url = 'https://api.envato.com/v2/market/buyer/download?item_id=' . $id . '&shorten_url=true';
			$response = $this->request( $url, $args );
			
			// @todo Find out which errors could be returned & handle them in the UI.
			if ( is_wp_error( $response ) || empty( $response ) || ! empty( $response['error'] ) ) {
				return false;
			}

			if ( ! empty( $response['wordpress_theme'] ) ) {
				return $response['wordpress_theme'];
			}

			if ( ! empty( $response['wordpress_plugin'] ) ) {
				return $response['wordpress_plugin'];
			}

			return false;
			
		}

		/**
		* Get an item by ID and type.
		* @since 1.0.0
		*/
		public function item( $id, $args = array() ) {
			
			$url = 'https://api.envato.com/v2/market/catalog/item?id=' . $id;
			$response = $this->request( $url, $args );

			if ( is_wp_error( $response ) || empty( $response ) ) {
				return false;
			}

			if ( ! empty( $response['wordpress_theme_metadata'] ) ) {
				return $this->normalize_theme( $response );
			}

			if ( ! empty( $response['wordpress_plugin_metadata'] ) ) {
				return $this->normalize_plugin( $response );
			}

			return false;
			
		}

		/**
		* Get the list of available themes.
		* @since 1.0.0
		*/
		public function themes( $args = array() ) {
			
			$themes = array();

			$url = 'https://api.envato.com/v2/market/buyer/list-purchases?filter_by=wordpress-themes';
			$response = $this->request( $url, $args );

			if ( is_wp_error( $response ) || empty( $response ) || empty( $response['results'] ) ) {
				return $themes;
			}

			foreach ( $response['results'] as $theme ) {
				$themes[] = $this->normalize_theme( $theme['item'] );
			}

			return $themes;
			
		}

		/**
		* Normalize a theme.
		* @since 1.0.0
		*/
		public function normalize_theme( $theme ) {
			
			return array(
				'id' => $theme['id'],
				'name' => $theme['wordpress_theme_metadata']['theme_name'],
				'author' => $theme['wordpress_theme_metadata']['author_name'],
				'version' => $theme['wordpress_theme_metadata']['version'],
				'description' => self::remove_non_unicode( $theme['wordpress_theme_metadata']['description'] ),
				'url' => $theme['url'],
				'author_url' => $theme['author_url'],
				'thumbnail_url' => $theme['thumbnail_url'],
				'rating' => $theme['rating'],
			);
			
		}

		/**
		* Get the list of available plugins.
		* @since 1.0.0
		*/
		public function plugins( $args = array() ) {
			
			$plugins = array();

			$url = 'https://api.envato.com/v2/market/buyer/list-purchases?filter_by=wordpress-plugins';
			$response = $this->request( $url, $args );

			if ( is_wp_error( $response ) || empty( $response ) || empty( $response['results'] ) ) {
				return $response->get_error_message();
			}

			foreach ( $response['results'] as $plugin ) {
				$plugins[] = $this->normalize_plugin( $plugin );
			}

			return $plugins;
			
		}

		/**
		* Normalize a plugin.
		* @since 1.0.0
		*/
		public function normalize_plugin( $plugin ) {
			
			$requires = null;
			$tested = null;
			$versions = array();

			if (isset($plugin['item'])) {

				// Set the required and tested WordPress version numbers.
				foreach ( $plugin['item']['attributes'] as $k => $v ) {
					if ( 'compatible-software' === $v['name'] ) {
						if (isset($v['value'])) {
							foreach ( $v['value'] as $version ) {
								$versions[] = str_replace( 'WordPress ', '', trim( $version ) );
							}
							if ( ! empty( $versions ) ) {
								$requires = $versions[ count( $versions ) - 1 ];
								$tested = $versions[0];
							}
							break;
						}
					}
				}
	
				return array(
					'id' => $plugin['item']['id'],
					'name' => $plugin['item']['wordpress_plugin_metadata']['plugin_name'],
					'author' => $plugin['item']['wordpress_plugin_metadata']['author'],
					'version' => $plugin['item']['wordpress_plugin_metadata']['version'],
					'description' => self::remove_non_unicode( $plugin['item']['wordpress_plugin_metadata']['description'] ),
					'content' => self::remove_non_unicode( $plugin['item']['wordpress_plugin_metadata']['description'] ),
					'url' => $plugin['item']['url'],
					'author_url' => (isset($plugin['item']['author_url'])) ? $plugin['item']['author_url'] : null,
					'thumbnail_url' => (isset($plugin['item']['thumbnail_url'])) ? $plugin['item']['thumbnail_url'] : null,
					'landscape_url' => (isset($plugin['item']['previews']['landscape_preview']['landscape_url'])) ? $plugin['item']['previews']['landscape_preview']['landscape_url'] : null,
					'requires' => $requires,
					'tested' => $tested,
					'purchase_code' => $plugin['code'],
					'license' => $plugin['license'],
					'supported_until' => $plugin['supported_until'],
					'number_of_sales' => $plugin['item']['number_of_sales'],
					'updated_at' => $plugin['item']['updated_at'],
					'rating' => $plugin['item']['rating'],
				);
			
			}
			
		}

		/**
		* Remove all non unicode characters in a string
		* @since 1.0.0
		*/
		static private function remove_non_unicode( $retval ) {
			return preg_replace( '/[\x00-\x1F\x80-\xFF]/', '', $retval );
		}
	}

}
