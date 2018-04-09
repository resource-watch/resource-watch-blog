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

if ($tg_grid_data['source_type'] == 'vimeo') {
	
	$user = $tg_grid_data['vimeo_user'];
	
	if (!empty($user)) {
		
		try {
	
			$vimeo = new The_Grid_Vimeo();
			$user = $vimeo->get_user($user);
	
			if (!empty($user)) {
				
				$base = new The_Grid_Base();
				
				$name      = (isset($user->name)) ? $user->name : null;
				$link      = (isset($user->link)) ? $user->link : null;
				$caption   = (isset($user->bio)) ? $user->bio : null;
				$website   = (isset($user->websites[0]->link)) ? $user->websites[0]->link : null;
				$logo      = (isset($user->pictures->sizes[3]->link)) ? $user->pictures->sizes[3]->link : null;
				$like      = (isset($user->metadata->connections->likes->total)) ? $user->metadata->connections->likes->total : null;
				$video     = (isset($user->metadata->connections->videos->total)) ? $user->metadata->connections->videos->total : null;
				$followers = (isset($user->metadata->connections->followers->total)) ? $user->metadata->connections->followers->total : null;
				$following = (isset($user->metadata->connections->following->total)) ? $user->metadata->connections->following->total : null;
			
				$vimeo = '<div class="tg-vimeo-channel-header">';
				
					$vimeo .= '<a class="tg-vimeo-channel-logo" href="'.esc_url($link).'" target="_blank">';
						$vimeo .= '<img src="'.esc_url($logo).'" alt="">';
					$vimeo .= '</a>';
				
					$vimeo .= '<div class="tg-vimeo-channel-desc">';
						$vimeo .= '<div class="tg-vimeo-channel-desc-inner">';
						
							$vimeo .= '<span class="tg-vimeo-channel-desc-title">';
								$vimeo .= '<a href="'.esc_url($link).'" target="_blank">'.esc_html($name).'</a>';
							$vimeo .= '</span>';
							
							$vimeo .= '<span class="tg-vimeo-channel-data">';
								$vimeo .= '<span class="tg-vimeo-channel-count">';
									$vimeo .= '<span>'.esc_html($base->shorten_number_format($video)).'</span>';
									$vimeo .= '<span>'.__( 'Videos', 'tg-text-domain' ).'</span>';
								$vimeo .= '</span>';
								$vimeo .= '<span class="tg-vimeo-channel-count">';
									$vimeo .= '<span>'.esc_html($base->shorten_number_format($like)).'</span>';
									$vimeo .= '<span>'.__( 'Likes', 'tg-text-domain' ).'</span>';
								$vimeo .= '</span>';
								$vimeo .= '<span class="tg-vimeo-channel-count">';
									$vimeo .= '<span>'.esc_html($base->shorten_number_format($followers)).'</span>';
									$vimeo .= '<span>'.__( 'Followers', 'tg-text-domain' ).'</span>';
								$vimeo .= '</span>';
								$vimeo .= '<span class="tg-vimeo-channel-count">';
									$vimeo .= '<span>'.esc_html($base->shorten_number_format($following)).'</span>';
									$vimeo .= '<span>'.__( 'Following', 'tg-text-domain' ).'</span>';
								$vimeo .= '</span>';
							$vimeo .= '</span>';
	
							$vimeo .= '<p class="tg-vimeo-channel-desc-caption">'.esc_html($caption).'</p>';
						$vimeo .= '</div>';
					$vimeo .= '</div>';
				
				$vimeo .= '</div>';
				
				echo $vimeo;
			
			}
		
		} catch (Exception $e) {
			return false;
		}
	
	}

}