<?php
/**
 * Plugin Core
 */
if ( ! class_exists( 'WPA_Core' ) ) {
	class WPA_Core {

		/**
		 * WPA_Core constructor.
		 */
		function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );
			remove_all_filters( 'tmu_sidebar_after' );
			add_action( 'tmu_sidebar_after', array( $this, 'sidebar_rss_news' ) );
			add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );
		}


		/**
		 * Add plugin core assets
		 */
		public function admin_enqueue_assets() {
			wp_enqueue_style( 'tmu-admin-style', WPA_URL . 'assets/core/css/admin.css', array() );
		}


		/**
		 * Rss Widget
		 */
		public function rss_news_widget() {
			echo '<div class="tmu-rss-widget rss-widget">';
			wp_widget_rss_output( array(
				'url'          => 'https://tomiup.com/feed/',
				'items'        => 5,
				'show_summary' => 0,
				'show_author'  => 0,
				'show_date'    => 0
			) );
			echo '</div>';
			echo '<div class="tips-news-footer">
					<a href="https://tomiup.com/?utm_source=wp-avatar" target="_blank">Tomiup <span class="screen-reader-text">(opens in a new window)</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>
				</div>';
		}

		/**
		 * sidebar rss news
		 */
		public function sidebar_rss_news() {
			echo '<div class="tmu-box">';
			echo '<h3 class="tmu-title-box">Best News & Tips</h3>';
			$this->rss_news_widget();
			echo '</div>';
		}

		/**
		 * Add widget to dashboard
		 */
		public function add_dashboard_widgets() {
			add_meta_box( 'tmu_dashboard_widget', 'Best News & Tips', array(
				$this,
				'rss_news_widget'
			), 'dashboard', 'side', 'high' );
		}

	}

	new WPA_Core();
}