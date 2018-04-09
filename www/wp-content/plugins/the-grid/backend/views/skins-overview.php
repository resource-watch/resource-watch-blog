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

if (!The_Grid_Base::get_purchase_code()) {
	
	$output = '<div class="tg-custom-skins-overview">';
	
		$output .= '<div class="tg-col tg-col-full">';
			$output .= '<div class="tg-container">';
				$output .= '<div class="tg-container-header">';
					$output .= '<div class="tg-container-title">';
						$output .= __( 'Please register The Grid plugin to enable Skin Builder', 'tg-text-domain' );
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';
		
		echo $output;
		
		require_once('grid-info.php');
	
	echo '</div>';
	
	return false;

}

// fetch custom skins
$custom_skins = (array) The_Grid_Custom_Table::get_skin_params();

// reassigned custom skin name and params
foreach ($custom_skins as $custom_skin) {
	$params = json_decode($custom_skin['params'], true);
	$params['id'] = $custom_skin['id'];
	$skins[$params['slug']] = $params;
}


if (isset($skins) && !empty($skins)) {
	
	try {

		// run the skin preview class
		$custom_skins = The_Grid_Skins_Preview('', $skins);

	} catch (Exception $e) {
		
		$output  = '<div class="tg-col tg-col-full tg-custom-skins-overview">';
		
			$output .= '<div class="tg-container">';
				$output .= '<div class="tg-container-header">';
					$output .= '<div class="tg-container-title">';
					$output .= __( 'An error occurs while retrieving custom skins', 'tg-text-domain' );
					$output .= '</div>';
				$output .= '</div>';
				$output .= '<div class="tg-container-inner">';
					$output .= '<div class="tg-error-msg">'. $e->getMessage() .'</div>';
				$output .= '</div>';
			$output .= '</div>';
			
		$output .= '</div>';
		
		echo $output;
		
		return false;
			
	}
	
}

if (isset($custom_skins) && !empty($custom_skins)) {
	
	$output  = '<div class="tg-col tg-col-full tg-custom-skins-overview">';
	
		$output .= '<div class="tg-container">';
			$output .= '<div class="tg-container-header">';
				$output .= '<div class="tg-container-title">';
					$output .= '<div class="tg-skins-style-button tg-selected" data-style="grid">'.__( 'Grid/Justified Skins', 'tg-text-domain' ).'</div>';
					$output .= '<div class="tg-skins-style-button"  data-style="masonry">'.__( 'Masonry Skins', 'tg-text-domain' ).'</div>';
				$output .= '</div>';
			$output .= '</div>';
			$output .= '<div class="tg-container-inner tg-has-skins">';
				$output .= $custom_skins;
			$output .= '</div>';
		$output .= '</div>';
		
		$output .= '<a class="tg-button tg-create-empty" href="'.admin_url( 'admin.php?page=the_grid_skin_builder&create=true').'"><i class="dashicons dashicons-plus"></i>'.__('Create a Skin', 'tg-text-domain').'</a>';
		$output .= '<a class="tg-button" id="tg-import-skin-demo"><i class="dashicons dashicons-download"></i>'.__('Import Demo Skins', 'tg-text-domain').'</a>';
		
	$output .= '</div>';

	echo $output;

} else {
	
	$output  = '<div class="tg-col tg-col-full tg-custom-skins-overview">';
	
		$output .= '<div class="tg-container">';
			$output .= '<div class="tg-container-header">';
				$output .= '<div class="tg-container-title">';
					$output .= __( 'You don\'t have any custom skin yet!', 'tg-text-domain' );
				$output .= '</div>';
			$output .= '</div>';
			$output .= '<div class="tg-container-inner tg-container-empty">';
				$output .= '<a class="tg-button tg-create-empty" href="'.admin_url( 'admin.php?page=the_grid_skin_builder&create=true').'"><i class="dashicons dashicons-plus"></i>'.__('Create a Skin', 'tg-text-domain').'</a>';
				$output .= '<a class="tg-button" id="tg-import-skin-demo"><i class="dashicons dashicons-download"></i>'.__('Import Demo Skins', 'tg-text-domain').'</a>';
			$output .= '</div>';
		$output .= '</div>';
		
	$output .= '</div>';
	
	echo $output;

}
