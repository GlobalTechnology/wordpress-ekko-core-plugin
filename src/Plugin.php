<?php namespace EkkoCore {
	class Plugin {
		/**
		 * Singleton instance
		 * @var Plugin
		 */
		private static $instance;

		/**
		 * Returns the Plugin singleton
		 * @return Plugin
		 */
		public static function singleton() {
			if ( ! isset( self::$instance ) ) {
				$class          = __CLASS__;
				self::$instance = new $class();
			}
			return self::$instance;
		}

		/**
		 * Prevent cloning of the class
		 * @internal
		 */
		private function __clone() {
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			$this->register_hooks();
		}

		const QUERY_VAR_TYPE = 'ekko_type';
		const QUERY_VAR_ID   = 'ekko_item_id';
		public static $EKKO_TYPES = array(
			'course',
			'playlist',
		);

		private function register_hooks() {
			add_action( 'init', array( $this, 'add_rewrite_rules' ), 10, 0 );
			add_filter( 'query_vars', array( $this, 'add_query_vars' ), 10, 1 );
			add_filter( 'template_include', array( $this, 'template_include' ), 10, 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ), 100, 0 );
			add_action( 'wp_head', array( $this, 'add_meta_tags' ), 2, 0 );

			add_filter( 'wp_unique_post_slug_is_bad_hierarchical_slug', array( $this, 'prevent_slug_conflict' ), 10, 3 );
			add_filter( 'wp_unique_post_slug_is_bad_flat_slug', array( $this, 'prevent_slug_conflict' ), 10, 3 );

			add_action( 'wp_print_styles', function () {
//				wp_dequeue_style('twentythirteen-style');
			}, 0, 0 );
		}

		public function add_rewrite_rules() {
			foreach ( self::$EKKO_TYPES as $type ) {
				add_rewrite_rule( "{$type}/?([^/]*)", "index.php?ekko_type={$type}&ekko_item_id=\$matches[1]", 'top' );
			}
		}

		public function add_query_vars( $query_vars ) {
			$query_vars[ ] = self::QUERY_VAR_TYPE;
			$query_vars[ ] = self::QUERY_VAR_ID;
			return $query_vars;
		}

		public function template_include( $template ) {
			if ( $ekko_type = get_query_var( self::QUERY_VAR_TYPE ) ) {
				$theme_template = get_stylesheet_directory() . "/ekko-{$ekko_type}.php";
				$template       = file_exists( $theme_template ) ?
					$theme_template :
					\EkkoCore\PLUGIN_DIR . "src/templates/{$ekko_type}.php";
			}
			return $template;
		}

		public function enqueue_scripts_styles() {
			wp_register_script( 'smartbanner', \EkkoCore\PLUGIN_URL . "src/js/jasny/jquery.smartbanner.js", array( 'jquery' ) );
			wp_register_style( 'smartbanner', \EkkoCore\PLUGIN_URL . "src/js/jasny/jquery.smartbanner.css" );

			wp_register_script( 'bootstrap', \EkkoCore\PLUGIN_URL . "src/js/bootstrap.min.js", array( 'jquery' ) );
			wp_register_style( 'bootstrap', \EkkoCore\PLUGIN_URL . "src/css/bootstrap.min.css" );

			wp_register_script( 'ekko-core', \EkkoCore\PLUGIN_URL . "src/js/ekko.js", array( 'smartbanner', 'bootstrap' ) );
			wp_register_style( 'ekko-core', \EkkoCore\PLUGIN_URL . "src/css/ekko.css", array( 'smartbanner', 'bootstrap' ) );
			wp_localize_script( 'ekko-core', '_ekkoCoreSettings', array(
				'smartbanner' => array(
					'title'           => __( 'Ekkolabs', \EkkoCore\TEXT_DOMAIN ),
					'author'          => __( 'Campus Crusade for Christ, Intl.', \EkkoCore\TEXT_DOMAIN ),
					'layer'           => true,
					'daysHidden'      => 0,
					'iOSUniversalApp' => true,
				),
			) );

			if ( $ekko_type = get_query_var( self::QUERY_VAR_TYPE ) ) {
				wp_enqueue_style( 'ekko-core' );
				wp_enqueue_script( 'ekko-core' );

				show_admin_bar(false);
				add_filter( 'show_admin_bar', '__return_false' );
				wp_dequeue_style('admin-bar');
				remove_action('wp_head', '_admin_bar_bump_cb');
			}
		}

		public function add_meta_tags() {
			if ( $ekko_type = get_query_var( self::QUERY_VAR_TYPE ) ) {
				$item_id = get_query_var( self::QUERY_VAR_ID );
				// Apple App Banners
				// @see https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/PromotingAppswithAppBanners/PromotingAppswithAppBanners.html
				echo "<meta name=\"apple-itunes-app\" content=\"app-id=892911573, app-argument=ekkolabs:///{$ekko_type}/{$item_id}\" />";

				// SmartBanner google fallback
				echo "<meta name=\"google-play-app\" content=\"app-id=org.ekkoproject.android.player\" />";

				// SmartBanner app icon
				// @see https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html
				echo "<link rel=\"apple-touch-icon\" href=\"" . \EkkoCore\PLUGIN_URL . "src/img/ekko-icon.png\" />";
			}
		}

		public function prevent_slug_conflict( $current, $slug, $post_type ) {
			if ( $post_type == 'page' && in_array( $slug, self::$EKKO_TYPES ) ) return true;
			return $current;
		}
	}
}
