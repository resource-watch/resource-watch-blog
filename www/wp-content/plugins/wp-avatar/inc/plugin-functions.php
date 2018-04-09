<?php
/**
 * Plugin Main Functions
 */
if ( ! class_exists( 'WPA_Functions' ) ) {
	class WPA_Functions {

		/**
		 * WPA_Functions constructor.
		 */
		function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'show_user_profile', array( $this, 'create_avatar_field' ) );
			add_action( 'edit_user_profile', array( $this, 'create_avatar_field' ) );
			add_action( 'personal_options_update', array( $this, 'save_avatar' ) );
			add_action( 'edit_user_profile_update', array( $this, 'save_avatar' ) );
			add_filter( 'get_avatar', array( $this, 'get_avatar' ), 100, 5 );
			add_filter( 'get_avatar', array( $this, 'gravatar_on_local' ), 101, 5 );
			add_filter( 'avatar_defaults', array( $this, 'avatar_defaults' ), 102, 1 );
			add_filter( 'user_profile_picture_description', array( $this, 'user_profile_picture_description' ) );
			add_filter( 'display_media_states', array( $this, 'display_media_states' ), 103, 1 );
			add_action( 'admin_init', array( $this, 'discussion_settings' ), 104 );

		}


		/**
		 * Register settings
		 */
		function discussion_settings() {
			register_setting( 'discussion', 'wp_avatar', array( $this, 'discussion_sanitize_options' ) );
			add_settings_field( 'wpa_allow_anyone_upload', esc_attr__( 'Avatar Upload', 'avatar-manager' ), array(
				$this,
				'allow_anyone_upload_field'
			), 'discussion', 'avatars' );
		}


		/**
		 * Display field setting allow upload.
		 */
		function allow_anyone_upload_field() {
			$options = get_option( 'wp_avatar' );
			?>
			<label>
				<input <?php checked( $options['allow_anyone_upload'], 1, true ); ?> name="wp_avatar[allow_anyone_upload]" type="checkbox" value="1">
				<?php esc_html_e( 'Allow anyone can upload avatar', 'wp-avatar' ); ?>
				<input name='wp_avatar[default_avatar_url]' type='hidden' value='<?php echo $options['default_avatar_url']; ?>'>
			</label>
			<?php
		}

		/**
		 * @param $fields
		 *
		 * @return mixed|void
		 */
		function discussion_sanitize_options( $fields ) {
			$options = get_option( 'wp_avatar' );


			$options['default_avatar_url']  = isset( $fields['default_avatar_url'] ) ? $fields['default_avatar_url'] : 0;
			$options['allow_anyone_upload'] = isset( $fields['allow_anyone_upload'] ) ? intval( $fields['allow_anyone_upload'] ) : 0;

			return $options;
		}


		/**
		 * @param $media_states
		 *
		 * @return array
		 */
		function display_media_states( $media_states ) {
			global $post;

			$is_avatar = get_post_meta( $post->ID, '_wpa_is_avatar', true );

			if ( $is_avatar ) {
				$media_states[] = esc_attr__( 'is Avatar', 'wp-avatar' );
			}

			return $media_states;
		}


		/**
		 * Add script to admin
		 */
		function admin_enqueue_scripts() {
			wp_enqueue_media();
			wp_enqueue_style( 'wpa-admin-style', WPA_URL . 'assets/css/admin.css', array() );
			wp_register_script( 'wpa-admin', WPA_URL . 'assets/js/admin.js', array( 'jquery' ), '', false );
			wp_enqueue_script( 'wpa-admin' );
		}


		function validate_gravatar( $id_or_email ) {
			//id or email code borrowed from wp-includes/pluggable.php
			$email = '';
			if ( is_numeric( $id_or_email ) ) {
				$id   = (int) $id_or_email;
				$user = get_userdata( $id );
				if ( $user ) {
					$email = $user->user_email;
				}
			} elseif ( is_object( $id_or_email ) ) {
				// No avatar for pingbacks or trackbacks
				$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
				if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) ) {
					return false;
				}

				if ( ! empty( $id_or_email->user_id ) ) {
					$id   = (int) $id_or_email->user_id;
					$user = get_userdata( $id );
					if ( $user ) {
						$email = $user->user_email;
					}
				} elseif ( ! empty( $id_or_email->comment_author_email ) ) {
					$email = $id_or_email->comment_author_email;
				}
			} else {
				$email = $id_or_email;
			}

			$hashkey = md5( strtolower( trim( $email ) ) );
			$uri     = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';

			$data = wp_cache_get( $hashkey );
			if ( false === $data ) {
				$response = wp_remote_head( $uri );
				if ( is_wp_error( $response ) ) {
					$data = 'not200';
				} else {
					$data = $response['response']['code'];
				}
				wp_cache_set( $hashkey, $data, $group = '', $expire = 60 * 5 );

			}
			if ( $data == '200' ) {
				return true;
			} else {
				return false;
			}
		}


		function gravatar_on_local( $avatar, $id_or_email, $size, $default, $alt ) {
			$has_gravatar = $this->validate_gravatar( $id_or_email );

			if ( is_admin() ) {
				$current_screen = get_current_screen();
				if ( $current_screen->base == 'options-discussion' ) {
					if ( ( ( strpos( $default, 'http' ) === 0 ) && $has_gravatar ) ) {
						$whitelist = array( 'localhost', '127.0.0.1' );

						if ( ! in_array( $_SERVER['SERVER_ADDR'], $whitelist ) ) {
							return $avatar;
						}

						$doc = new DOMDocument;
						$doc->loadHTML( $avatar );
						$imgs = $doc->getElementsByTagName( 'img' );

						if ( $imgs->length > 0 ) {
							$url  = urldecode( $imgs->item( 0 )->getAttribute( 'src' ) );
							$url2 = explode( 'd=', $url );
							if ( isset( $url2[1] ) ) {
								$url3 = explode( '&', $url2[1] );
							} else {
								$url3 = explode( '&', $url2[0] );
							}

							if ( is_numeric( $size ) ) {
								$crop = aq_resize( $url3[0], $size, $size, true );
								if ( $crop ) {
									$url3[0] = $crop;
								}
							}
							$avatar = "<img src='{$url3[0]}' alt='' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
						}
					}
				}
			}


			return $avatar;

		}

		/**
		 * @param $avatar
		 * @param $id_or_email
		 * @param $size
		 * @param $default
		 * @param $alt
		 *
		 * @return string
		 */
		function get_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
			global $wpdb, $blog_id;
			$has_gravatar = $this->validate_gravatar( $id_or_email );

			if ( is_string( $id_or_email ) ) {
				$user = get_user_by( 'email', $id_or_email );
				if ( $user ) {
					$id_or_email = $user->ID;
				}
			} else if ( is_object( $id_or_email ) ) {
				$id_or_email = intval( $id_or_email->user_id );
			}

			$fields            = get_option( 'wp_avatar' );
			$anyone_can_upload = isset( $fields['allow_anyone_upload'] ) ? intval( $fields['allow_anyone_upload'] ) : 0;
			$avatar_id         = get_the_author_meta( 'wpa_avatar_id', $id_or_email );
			$user_avatar       = get_the_author_meta( $wpdb->get_blog_prefix( $blog_id ) . 'user_avatar', $id_or_email );
			if ( empty( $avatar_id ) && $user_avatar ) {
				$avatar_id = $user_avatar;
			}

			if ( ! $has_gravatar && empty( $avatar_id ) ) {
				$whitelist = array( 'localhost', '127.0.0.1' );

				if ( ! in_array( $_SERVER['SERVER_ADDR'], $whitelist ) ) {
					return $avatar;
				}

				$doc = new DOMDocument;
				$doc->loadHTML( $avatar );
				$imgs = $doc->getElementsByTagName( 'img' );

				if ( $imgs->length > 0 ) {
					$url  = urldecode( $imgs->item( 0 )->getAttribute( 'src' ) );
					$url2 = explode( 'd=', $url );
					if ( isset( $url2[1] ) ) {
						$url3 = explode( '&', $url2[1] );
					} else {
						$url3 = explode( '&', $url2[0] );
					}
					if ( is_numeric( $size ) ) {
						$crop = aq_resize( $url3[0], $size, $size, true );
						if ( $crop ) {
							$url3[0] = $crop;
						}
					}
					$avatar = "<img src='{$url3[0]}' alt='' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
				}
			}


			$avatar_url = wp_get_attachment_url( $avatar_id );

			$custom_avatar = '';
			if ( is_numeric( $size ) ) {
				$crop = aq_resize( $avatar_url, $size, $size, true );
				if ( $crop ) {
					$custom_avatar = $crop;
				}
			} else if ( $size == 'full' ) {
				$custom_avatar = $avatar_url;
			}

			$output = '';
			if ( $custom_avatar ) {
				$output = '<img src="' . $custom_avatar . '" width="' . $size . '" height="' . $size . '" alt="avatar" class="avatar wp-avatar" />';
			} elseif ( $avatar ) {
				$output = $avatar;
			} else {
				$output = '<img src="' . $default . '" width="' . $size . '" height="' . $size . '" alt="' . $alt . '" />';
			}

			return $output;
		}

		/**
		 * Removes the get_avatar function attached to get_avatar
		 *
		 * @param $avatar_defaults
		 *
		 * @return mixed
		 */
		function avatar_defaults( $avatar_defaults ) {
			remove_filter( 'get_avatar', array( $this, 'get_avatar' ), 100, 5 );

			$avatar_default = get_option( 'avatar_default' );

			$options            = get_option( 'wp_avatar' );
			$default_avatar_url = isset( $options['default_avatar_url'] ) ? $options['default_avatar_url'] : '';

			$avatar_defaults[ $default_avatar_url ] = "<input style='vertical-align: middle;' type='button' class='button' id='wpa_btn_choose_default_avatar' value='" . esc_attr__( 'Choose an Image', 'wp-avatar' ) . "' />";


			return $avatar_defaults;
		}


		/**
		 * @param $user_id
		 *
		 * @return bool
		 */
		function save_avatar( $user_id ) {

			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				return false;
			}

			$wpa_avatar_id = get_user_meta( $user_id, 'wpa_avatar_id', true );

			if ( isset( $_POST['wpa_btn_upload_image'] ) && $_POST['wpa_btn_upload_image'] ) {

				// Supported types
				$image_types = array(
					'jpeg' => 'image/jpeg',
					'jpg'  => 'image/jpeg',
					'png'  => 'image/png',
					'gif'  => 'image/gif',
				);

				$upload_overrides = array(
					'mimes'     => $image_types,
					'test_form' => false
				);


				if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
				}
				if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}

				$movefile = wp_handle_upload( $_FILES['wpa_upload_file'], $upload_overrides );

				if ( isset( $movefile['error'] ) ) {
					/**
					 * Error generated by _wp_handle_upload()
					 * @see _wp_handle_upload() in wp-admin/includes/file.php
					 */
					wp_die( $movefile['error'] );
				}

				$attachment = array(
					'guid'           => $movefile['url'],
					'post_content'   => $movefile['url'],
					'post_mime_type' => $movefile['type'],
					'post_title'     => basename( $movefile['file'] )
				);


				// Remove old avatar
				$is_avatar         = get_post_meta( $wpa_avatar_id, '_wpa_is_avatar', true );
				$attachment_author = get_post_field( 'post_author', $wpa_avatar_id );

				if ( $is_avatar && ( $user_id == intval( $attachment_author ) ) ) {
					delete_post_meta( $wpa_avatar_id, '_wpa_is_avatar' );
					wp_delete_attachment( $wpa_avatar_id );
				}

				// Insert image
				$wpa_avatar_id = wp_insert_attachment( $attachment, $movefile['file'] );

				// Generate metadata
				$attachment_metadata = wp_generate_attachment_metadata( $wpa_avatar_id, $movefile['file'] );

				// Update avatar meta "is_avatar"
				update_post_meta( $wpa_avatar_id, '_wpa_is_avatar', true );

				// Update metadata
				wp_update_attachment_metadata( $wpa_avatar_id, $attachment_metadata );

			} else if ( $wpa_avatar_id != $_POST['wpa_avatar_id'] ) {
				//remove old avatar
				$is_avatar         = get_post_meta( $wpa_avatar_id, '_wpa_is_avatar', true );
				$attachment_author = get_post_field( 'post_author', $wpa_avatar_id );

				if ( $is_avatar && ( $user_id == intval( $attachment_author ) ) ) {
					delete_post_meta( $wpa_avatar_id, '_wpa_is_avatar' );
					wp_delete_attachment( $wpa_avatar_id );
				}

				$wpa_avatar_id = $_POST['wpa_avatar_id'];
			}

			update_user_meta( $user_id, 'wpa_avatar_id', $wpa_avatar_id );
		}

		/**
		 * @param $user
		 */
		function create_avatar_field( $user ) {
			$options    = get_option( 'wp_avatar' );
			$custom     = '';
			$ga         = get_avatar_url( $user->ID );
			$avatar_url = $this->get_avatar_url( $user->ID, 96 );
			if ( $this->has_custom_avatar( $user->ID ) ) {
				$custom = 'has-custom-avatar';
			}

			$anyone_can_upload = isset( $fields['allow_anyone_upload'] ) ? intval( $fields['allow_anyone_upload'] ) : 0;
			if ( current_user_can( 'upload_files' ) || $anyone_can_upload ) :
				?>
				<h2><a id="wpa-upload-avatar"></a><?php esc_html_e( 'Avatar Upload', 'wp-avatar' ); ?></h2>
				<table class="form-table <?php echo esc_attr( $custom ); ?>" id="wpa_wrapper">
					<tbody>
					<tr class="user-profile-picture">
						<th><?php esc_html_e( 'Your Avatar', 'wp-avatar' ); ?></th>
						<td>
							<img id="wpa_preview" src="<?php echo esc_url_raw( $avatar_url ); ?>" data-old="<?php echo esc_url_raw( $avatar_url ); ?>" data-ga="<?php echo esc_url_raw( $ga ); ?>" height="96" /><br />
							<input type="hidden" name="wpa_avatar_id" id="wpa_avatar_id" value="<?php echo get_the_author_meta( 'wpa_avatar_id', $user->ID ); ?>" />
							<input type="button" class="button" id="wpa_remove" value="<?php esc_html_e( 'Remove', 'wp-avatar' ); ?>" />
							<?php if ( current_user_can( 'upload_files' ) ) : ?>
								<input type='button' class="button" id="wpa_btn_choose_image" value="<?php esc_html_e( 'Choose Image', 'wp-avatar' ); ?>" />
							<?php elseif ( $anyone_can_upload ): ?>
								<p>
									<label class="description" for="wpa_upload_file">
										<?php esc_attr_e( 'Choose an image from your computer:', 'wp-avatar' ); ?>
									</label>
									<br />
									<input id="wpa_upload_file" name="wpa_upload_file" type="file">
									<input class="button" name="wpa_btn_upload_image" type="submit" value="<?php esc_attr_e( 'Upload', 'wp-avatar' ); ?>">
								</p>
							<?php endif; ?>
						</td>
					</tr>
					</tbody>

				</table>
			<?php endif;
		}

		/**
		 * @param        $author_id
		 * @param string $size
		 * @param null   $width
		 * @param null   $height
		 *
		 * @return array|bool|false|string
		 */
		function get_avatar_url( $author_id, $size = 96, $width = null, $height = null ) {
			if ( ! is_int( $author_id ) ) {
				$user = get_user_by( 'email', $author_id );
				if ( $user ) {
					$author_id = $user->ID;
				}
			}
			$gavatar_url = get_avatar_url( $author_id, $size );
			$avatar_url  = $gavatar_url;
			$custom      = '';


			$fields            = get_option( 'wp_avatar' );
			$anyone_can_upload = isset( $fields['allow_anyone_upload'] ) ? intval( $fields['allow_anyone_upload'] ) : 0;

			if ( current_user_can( 'upload_files' ) || $anyone_can_upload ) {
				if ( $this->has_custom_avatar( $author_id ) ) {
					$avatar_id  = get_the_author_meta( 'wpa_avatar_id', $author_id );
					$avatar_url = wp_get_attachment_url( $avatar_id );
					if ( $width && $height ) {
						$crop = aq_resize( $avatar_url, $width, $height, true );
						if ( $crop ) {
							$avatar_url = $crop;
						}
					} else if ( $size != 'full' ) {
						$crop = aq_resize( $avatar_url, $size, $size, true );
						if ( $crop ) {
							$avatar_url = $crop;
						}
					}
				}
			}

			return $avatar_url;
		}

		/**
		 * Check user has custom avatar
		 *
		 * @param $author_id
		 *
		 * @return bool
		 */
		function has_custom_avatar( $author_id ) {
			$has_custom_avatar = false;
			if ( ! is_int( $author_id ) ) {
				$user = get_user_by( 'email', $author_id );
				if ( $user ) {
					$author_id = $user->ID;
				}
			}
			$avatar_id = get_the_author_meta( 'wpa_avatar_id', $author_id );
			if ( $avatar_id ) {
				$has_custom_avatar = true;
			}

			return $has_custom_avatar;
		}

		/**
		 * @param $description
		 *
		 * @return string
		 */
		function user_profile_picture_description( $description ) {
			$options           = get_option( 'wp_avatar' );
			$anyone_can_upload = isset( $fields['allow_anyone_upload'] ) ? intval( $fields['allow_anyone_upload'] ) : 0;

			if ( current_user_can( 'upload_files' ) || $anyone_can_upload ) {
				$description = sprintf( __( 'You can change your profile picture in <strong>Avatar Upload</strong> section below.', 'wp-avatar' ) );
			}

			return $description;
		}

	}

	new WPA_Functions();


	if ( ! function_exists( 'wpa_get_avatar' ) ) {
		/**
		 * @param     $authorid
		 * @param int $width
		 * @param int $height
		 *
		 * @return string
		 */
		function wpa_get_avatar( $authorid, $width = 96, $height = 96 ) {
			global $wpdb, $blog_id;
			if ( $width == 'full' ) {
				$gravatar = get_avatar( $authorid, 1400 );
			} else {
				$gravatar = get_avatar( $authorid, $width );
			}

			$avatar_id   = get_the_author_meta( 'wpa_avatar_id', $authorid );
			$user_avatar = get_the_author_meta( $wpdb->get_blog_prefix( $blog_id ) . 'user_avatar', $authorid );
			if ( empty( $avatar_id ) && $user_avatar ) {
				$avatar_id = $user_avatar;
			}
			$avatar_url = wp_get_attachment_url( $avatar_id );

			if ( $width != 'full' ) {
				$crop = aq_resize( $avatar_url, $width, $height, true );
				if ( $crop ) {
					$avatar_url = $crop;
				}
			}

			$output = $gravatar;
			if ( $avatar_url ) {
				$output = '<img src="' . $avatar_url . '" width="' . $width . '" height="' . $height . '" alt="avatar" class="avatar" />';
			}

			return $output;
		}
	}
}