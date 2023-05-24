<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Elements_Assets' ) ) {

	/**
	 * Define Jet_Elements_Assets class
	 */
	class Jet_Elements_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Localize data array
		 *
		 * @var array
		 */
		public $localize_data = array();

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'elementor/frontend/before_register_styles', array( $this, 'register_styles' ) );
			add_action( 'elementor/frontend/before_enqueue_styles',   array( $this, 'enqueue_styles' ) );

			add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_styles' ) );

			add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_scripts' ) );
			add_action( 'elementor/frontend/before_enqueue_scripts',  array( $this, 'enqueue_scripts' ) );

			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );
			add_action( 'elementor/editor/after_enqueue_styles',   array( $this, 'editor_styles' ) );

			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'icons_font_styles' ) );
			add_action( 'elementor/preview/enqueue_styles',      array( $this, 'icons_font_styles' ) );

			$rest_api_url = apply_filters( 'jet-elements/rest/frontend/url', get_rest_url() );

			$this->localize_data = array(
				'ajaxUrl'        => esc_url( admin_url( 'admin-ajax.php' ) ),
				'isMobile'       => filter_var( wp_is_mobile(), FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false',
				'templateApiUrl' => $rest_api_url . 'jet-elements-api/v1/elementor-template',
				'devMode'        => is_user_logged_in() ? 'true' : 'false',
				'messages'       => array(
					'invalidMail' => esc_html__( 'Please specify a valid e-mail', 'jet-elements' ),
				)
			);
		}

		/**
		 * Register vendor styles.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function register_styles() {
			// Register vendor slider-pro.css styles (https://github.com/bqworks/slider-pro)
			wp_register_style(
				'jet-slider-pro-css',
				jet_elements()->plugin_url( 'assets/css/lib/slider-pro/slider-pro.min.css' ),
				false,
				'1.3.0'
			);

			// Register vendor juxtapose-css styles
			wp_register_style(
				'jet-juxtapose-css',
				jet_elements()->plugin_url( 'assets/css/lib/juxtapose/juxtapose.min.css' ),
				false,
				'1.3.1'
			);

			wp_register_style(
				'peel-css',
				jet_elements()->plugin_url( 'assets/css/lib/peel/peel.min.css' ),
				false,
				'1.0.0'
			);

			wp_register_style(
				'mejs-speed-css',
				jet_elements()->plugin_url( 'assets/css/lib/mejs-speed/mejs-speed.min.css' ),
				false,
				'2.5.1'
			);
		}

		/**
		 * Enqueue public-facing stylesheets.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {

			$direction_suffix = is_rtl() ? '-rtl' : '';

			wp_enqueue_style(
				'jet-elements',
				jet_elements()->plugin_url( 'assets/css/jet-elements' . $direction_suffix . '.css' ),
				false,
				jet_elements()->get_version()
			);

			$default_theme_enabled = apply_filters( 'jet-elements/assets/css/default-theme-enabled', true );

			if ( $default_theme_enabled ) {
				wp_enqueue_style(
					'jet-elements-skin',
					jet_elements()->plugin_url( 'assets/css/jet-elements-skin' . $direction_suffix . '.css' ),
					false,
					jet_elements()->get_version()
				);
			}
		}

		/**
		 * Enqueue preview styles.
		 *
		 * @return void
		 */
		public function enqueue_preview_styles() {

			if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.7', '>=' ) ) {
				return;
			}

			$avaliable_widgets = jet_elements_settings()->get( 'avaliable_widgets' );

			$styles_map = array(
				'jet-elements-video'            => array( 'mediaelement' ),
				'jet-elements-audio'            => array( 'mediaelement' ),
				'jet-elements-slider'           => array( 'jet-slider-pro-css' ),
				'jet-elements-image-comparison' => array( 'jet-juxtapose-css' ),
			);

			foreach ( $styles_map as $widget => $styles_list ) {
				$enabled = isset( $avaliable_widgets[ $widget ] ) ? $avaliable_widgets[ $widget ] : '';

				if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $avaliable_widgets ) {

					foreach ( $styles_list as $style ) {
						wp_enqueue_style( $style );
					}
				}
			}
		}

		/**
		 * Register plugin scripts
		 *
		 * @return void
		 */
		public function register_scripts() {

			$api_disabled = jet_elements_settings()->get( 'disable_api_js', [ 'disable' => 'false' ] );
			$key          = jet_elements_settings()->get( 'api_key' );

			if ( ! empty( $key ) && ( empty( $api_disabled ) || 'true' !== $api_disabled['disable'] ) ) {

				wp_register_script(
					'google-maps-api',
					add_query_arg(
						array( 'key' => jet_elements_settings()->get( 'api_key' ), ),
						'https://maps.googleapis.com/maps/api/js'
					),
					false,
					false,
					true
				);
			}

			// Register vendor anime.js script (https://github.com/juliangarnier/anime)
			wp_register_script(
				'jet-anime-js',
				jet_elements()->plugin_url( 'assets/js/lib/anime-js/anime.min.js' ),
				array(),
				'2.2.0',
				true
			);

			wp_register_script(
				'jet-tween-js',
				jet_elements()->plugin_url( 'assets/js/lib/tweenjs/tweenjs.min.js' ),
				array(),
				'2.0.2',
				true
			);


			// Register vendor salvattore.js script (https://github.com/rnmp/salvattore)
			wp_register_script(
				'jet-salvattore',
				jet_elements()->plugin_url( 'assets/js/lib/salvattore/salvattore.min.js' ),
				array(),
				'1.0.9',
				true
			);

			// Register vendor masonry.pkgd.min.js script
			wp_register_script(
				'jet-masonry-js',
				jet_elements()->plugin_url( 'assets/js/lib/masonry-js/masonry.pkgd.min.js' ),
				array( 'jquery' ),
				'4.2.1',
				true
			);

			// Register vendor slider-pro.js script (https://github.com/bqworks/slider-pro)
			wp_register_script(
				'jet-slider-pro',
				jet_elements()->plugin_url( 'assets/js/lib/slider-pro/jquery.sliderPro.min.js' ),
				array( 'jquery' ),
				'1.3.0',
				true
			);

			// Register vendor juxtapose.js script
			wp_register_script(
				'jet-juxtapose',
				jet_elements()->plugin_url( 'assets/js/lib/juxtapose/juxtapose.min.js' ),
				array(),
				'1.3.1',
				true
			);

			// Register vendor tablesorter.js script (https://github.com/Mottie/tablesorter)
			wp_register_script(
				'jquery-tablesorter',
				jet_elements()->plugin_url( 'assets/js/lib/tablesorter/jquery.tablesorter.min.js' ),
				array( 'jquery' ),
				'2.30.7',
				true
			);

			// Register vendor chart.js script (http://www.chartjs.org)
			wp_register_script(
				'chart-js',
				jet_elements()->plugin_url( 'assets/js/lib/chart-js/chart.min.js' ),
				array(),
				'2.7.3',
				true
			);

			// Register vendor html2canvas.js script (https://github.com/niklasvh/html2canvas)
			wp_register_script(
				'html2canvas',
				jet_elements()->plugin_url( 'assets/js/lib/html2canvas/html2canvas.min.js' ),
				array(),
				'1.0.0-rc.5',
				true
			);

			// Register vendor oriDomi.js script (https://github.com/dmotz/oriDomi)
			wp_register_script(
				'oridomi',
				jet_elements()->plugin_url( 'assets/js/lib/oridomi/oridomi.js' ),
				array(),
				'1.10.0',
				true
			);

			wp_register_script(
				'peel-js',
				jet_elements()->plugin_url( 'assets/js/lib/peeljs/peeljs.js' ),
				array(),
				'1.0.0',
				true
			);

			wp_register_script(
				'jet-lottie',
				jet_elements()->plugin_url( 'assets/js/lib/lottie/lottie.min.js' ),
				array(),
				'5.6.10',
				true
			);

			wp_register_script(
				'popperjs',
				jet_elements()->plugin_url( 'assets/js/lib/tippy/popperjs.js' ),
				array(),
				'2.5.2',
				true
			);

			wp_register_script(
				'tippy-bundle',
				jet_elements()->plugin_url( 'assets/js/lib/tippy/tippy-bundle.min.js' ),
				array( 'popperjs' ),
				'6.3.1',
				true
			);

			wp_register_script(
				'mejs-speed',
				jet_elements()->plugin_url( 'assets/js/lib/mejs-speed/speed.min.js' ),
				array(),
				'2.5.1',
				true
			);

			wp_register_script(
				'jet-resize-sensor',
				jet_elements()->plugin_url( 'assets/js/lib/resize-sensor/ResizeSensor.min.js' ),
				array(),
				'1.7.0',
				true
			);

			wp_register_script(
				'jet-slick',
				jet_elements()->plugin_url( 'assets/js/lib/slick/slick.min.js' ),
				array( 'jquery' ),
				'1.8.1',
				true
			);
		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			$min_suffix = jet_elements_tools()->is_script_debug() ? '' : '.min';

			wp_enqueue_script(
				'jet-elements',
				jet_elements()->plugin_url( 'assets/js/jet-elements' . $min_suffix . '.js' ),
				array( 'jquery', 'elementor-frontend' ),
				jet_elements()->get_version(),
				true
			);

			wp_localize_script(
				'jet-elements',
				'jetElements',
				apply_filters( 'jet-elements/frontend/localize-data', $this->localize_data )
			);
		}

		/**
		 * Enqueue icons font styles
		 *
		 * @return void
		 */
		public function icons_font_styles() {

			wp_enqueue_style(
				'jet-elements-font',
				jet_elements()->plugin_url( 'assets/css/jet-elements-icons.css' ),
				array(),
				jet_elements()->get_version()
			);

		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function editor_scripts() {

			$min_suffix = jet_elements_tools()->is_script_debug() ? '' : '.min';

			wp_enqueue_script(
				'jet-elements-editor',
				jet_elements()->plugin_url( 'assets/js/jet-elements-editor' . $min_suffix . '.js' ),
				array( 'jquery' ),
				jet_elements()->get_version(),
				true
			);
		}

		/**
		 * Enqueue editor styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			wp_enqueue_style(
				'jet-elements-editor',
				jet_elements()->plugin_url( 'assets/css/jet-elements-editor.css' ),
				array(),
				jet_elements()->get_version()
			);

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Jet_Elements_Assets
 *
 * @return object
 */
function jet_elements_assets() {
	return Jet_Elements_Assets::get_instance();
}
