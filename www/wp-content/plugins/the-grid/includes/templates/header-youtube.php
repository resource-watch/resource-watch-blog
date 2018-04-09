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

if ($tg_grid_data['source_type'] == 'youtube') {
	
	$channel_id = $tg_grid_data['youtube_channel'];
	
	if (!empty($channel_id)) {
		
		try {
	
			$youtube     = new The_Grid_Youtube();
			$channel_info = $youtube->get_data('', 'channel_info', $channel_id, '', '', 0);
	
			if (isset($channel_info->items[0]) && !empty($channel_info->items[0])) {
				
				$base = new The_Grid_Base();
				
				$logo       = $channel_info->items[0]->snippet->thumbnails->default->url;
				$banner     = $channel_info->items[0]->brandingSettings->image->bannerTabletHdImageUrl;
				$title      = $channel_info->items[0]->snippet->title;
				$caption    = $channel_info->items[0]->snippet->description;
				$viewCount  = $channel_info->items[0]->statistics->viewCount;
				$videoCount = $channel_info->items[0]->statistics->videoCount;
			
				$youtube = '<div class="tg-youtube-channel-header">';
				
					$youtube .= '<a class="tg-youtube-channel-logo" href="'.esc_url('https://www.youtube.com/channel/'.$channel_id.'/').'" target="_blank">';
						$youtube .= '<img src="'.esc_url($logo).'" alt="">';
					$youtube .= '</a>';
					
					$youtube .= '<div class="tg-youtube-channel-banner" style="background-image: url('.esc_url($banner).')"></div>';
				
					$youtube .= '<div class="tg-youtube-channel-desc">';
						$youtube .= '<div class="tg-youtube-channel-desc-inner">';
							$youtube .= '<span class="tg-youtube-channel-desc-title">';
								$youtube .= '<a href="'.esc_url('https://www.youtube.com/channel/'.$channel_id.'/').'" target="_blank">'.esc_html($title).'</a>';
								$youtube .= '<span class="tg-youtube-channel-data">';
									$youtube .= '<span class="tg-youtube-channel-count">';
										$youtube .= '(<span>'.esc_html($base->shorten_number_format($videoCount)).' '.__( 'videos', 'tg-text-domain' ).',</span>';
									$youtube .= '</span>';
									$youtube .= '<span class="tg-youtube-channel-count">';
										$youtube .= '<span>'.esc_html($base->shorten_number_format($viewCount)).' '.__( 'views', 'tg-text-domain' ).')</span>';
									$youtube .= '</span>';
								$youtube .= '</span>';
							$youtube .= '</span>';
							
							$youtube .= '<p class="tg-youtube-channel-desc-caption">'.esc_html($caption).'</p>';
						$youtube .= '</div>';
					$youtube .= '</div>';
							
					$youtube .= '<div class="tg-youtube-subscribe">';
						$youtube .= '<div class="g-ytsubscribe" data-channelid="'.esc_attr($channel_id).'" data-layout="default" data-count="default"></div>';
					$youtube .= '</div>';
				
				$youtube .= '</div>';
				
				echo $youtube;
			
			}
		
		} catch (Exception $e) {
			return false;
		}
	
	}

}