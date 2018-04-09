<?php
/**
 * ADD ABILITY TO VIEW THUMBNAILS IN WP 4.0+
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'admin_init', 'bodhi_svgs_display_thumbs' );
function bodhi_svgs_display_thumbs() {

	if ( bodhi_svgs_specific_pages_media_library() ) {

		function bodhi_svgs_thumbs_filter( $content ) {

			return apply_filters( 'final_output', $content );

		}

		ob_start( 'bodhi_svgs_thumbs_filter' );

		add_filter( 'final_output', 'bodhi_svgs_final_output' );
		function bodhi_svgs_final_output( $content ) {

			$content = str_replace(
				'<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',
				'<# } else if ( \'svg+xml\' === data.subtype ) { #>
					<img class="details-image" src="{{ data.url }}" draggable="false" />
					<# } else if ( \'image\' === data.type && data.sizes && data.sizes.full ) { #>',

					$content
					);

			$content = str_replace(
				'<# } else if ( \'image\' === data.type && data.sizes ) { #>',
				'<# } else if ( \'svg+xml\' === data.subtype ) { #>
					<div class="centered">
						<img src="{{ data.url }}" class="thumbnail" draggable="false" />
					</div>
					<# } else if ( \'image\' === data.type && data.sizes ) { #>',

					$content
					);

			return $content;

		}

	}

}