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

if ($tg_grid_data['source_type'] == 'instagram') {
	
	try {
		
		$intagram  = new The_Grid_Instagram();
		$username  = $tg_grid_data['instagram_username'];
		$hashtags  = $tg_grid_data['instagram_hashtag'];
		$hashtags  = preg_replace('/\s+/', '', $hashtags);
		// get the number of username(s)
		$username_nb = preg_replace('/\s+/', '', $username);
		$username_nb = array_filter(explode(',', $username_nb));
	
		// retrieve instagram user data
		$user_info = $intagram->get_data('user_info', $username, '', 0);
			
		if (isset($user_info) && !empty($user_info)) {
				
			$base = new The_Grid_Base();
				
			$instagram = '<div class="tg-instagram-user-header">';
				$instagram .= '<div class="tg-instagram-user-image">';
					$instagram .= '<img width="150" height="150" alt="'.esc_attr($user_info->username).'" src="'.esc_url($user_info->profile_picture).'">';
				$instagram .= '</div>';
				$instagram .= '<div class="tg-instagram-user-desc">';
					$instagram .= '<div class="tg-instagram-user-info">';
						$instagram .= '<h2 class="tg-instagram-user-name">'.esc_html($user_info->username).'</h2>';
						$instagram .= '<a class="tg-instagram-user-follow" rel="nofollow me" target="_blank" href="'.esc_url('https://www.instagram.com/'.$user_info->username).'/">'.__( 'Follow', 'tg-text-domain' ).'</a>';
					$instagram .= '</div>';
					$instagram .= '<div class="tg-instagram-user-info">';
						$instagram .= '<h3 class="tg-instagram-user-desc-fullname">'.esc_html($user_info->full_name).'</h3>';
						$instagram .= ' <span class="tg-instagram-user-bio">'.esc_html($user_info->bio).'</span>';
						$instagram .= ' <a class="tg-instagram-user-desc-url" rel="nofollow me" target="_blank" href="'.esc_url($user_info->website).'">'.esc_html($user_info->website).'</a>';
					$instagram .= '</div>';
					$instagram .= '<div class="tg-instagram-user-info">';
						$instagram .= '<span class="tg-instagram-user-cout">';
							$instagram .= '<span>'.esc_html($base->shorten_number_format($user_info->counts->media)).'</span>';
							$instagram .= '<span> '.__( 'posts', 'tg-text-domain' ).'</span>';
						$instagram .= '</span>';
						$instagram .= '<span class="tg-instagram-user-cout">';
							$instagram .= '<span>'.esc_html($base->shorten_number_format($user_info->counts->followed_by)).'</span>';
							$instagram .= '<span> '.__( 'followers', 'tg-text-domain' ).'</span>';
						$instagram .= '</span>';
						$instagram .= '<span class="tg-instagram-user-cout">';
							$instagram .= '<span>'.esc_html($base->shorten_number_format($user_info->counts->follows)).'</span>';
							$instagram .= '<span> '.__( 'following', 'tg-text-domain' ).'</span>';
						$instagram .= '</span>';
					$instagram .= '</div>';
				$instagram .= '</div>';
			$instagram .= '</div>';
				
			echo $instagram;
			
		}
	
	} catch (Exception $e) {
		return false;
	}
	
}