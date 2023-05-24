<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Tricks_Assets' ) ) {

	/**
	 * Define Jet_Tricks_Assets class
	 */
	class Jet_Tricks_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Localize data
		 *
		 * @var array
		 */
		public $elements_data = array(
			'sections' => array(),
			'columns'  => array(),
			'widgets'  => array(),
		);

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );
			add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
			add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_scripts' ) );
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );
		}

		/**
		 * Register plugin scripts
		 *
		 * @return void
		 */
		public function register_scripts() {

			wp_register_script(
				'jet-tricks-popperjs',
				jet_tricks()->plugin_url( 'assets/js/lib/tippy/popperjs.js' ),
				array(),
				'2.5.2',
				true
			);

			wp_register_script(
				'jet-tricks-tippy-bundle',
				jet_tricks()->plugin_url( 'assets/js/lib/tippy/tippy-bundle.js' ),
				array( 'jet-tricks-popperjs' ),
				'6.3.1',
				true
			);

			// Register vendor anime.js script (https://github.com/juliangarnier/anime)
			wp_register_script(
				'jet-anime-js',
				jet_tricks()->plugin_url( 'assets/js/lib/anime/anime.min.js' ),
				array(),
				'2.2.0',
				true
			);

			wp_register_script(
				'jet-tricks-ts-particles',
				jet_tricks()->plugin_url( 'assets/js/lib/ts-particles/tsparticles.min.js' ),
				array(),
				'1.18.11',
				true
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

			wp_enqueue_style(
				'jet-tricks-frontend',
				jet_tricks()->plugin_url( 'assets/css/jet-tricks-frontend.css' ),
				false,
				jet_tricks()->get_version()
			);
		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			$avaliable_extensions = jet_tricks_settings()->get( 'avaliable_extensions', jet_tricks_settings()->default_avaliable_extensions );

			$frontend_deps = array ( 'jquery', 'elementor-frontend' );

			if ( filter_var( $avaliable_extensions[ 'widget_tooltip' ], FILTER_VALIDATE_BOOLEAN ) ) {
				$frontend_deps[] = 'jet-tricks-tippy-bundle';
			}

			wp_enqueue_script(
				'jet-tricks-frontend',
				jet_tricks()->plugin_url( 'assets/js/jet-tricks-frontend.js' ),
				$frontend_deps,
				jet_tricks()->get_version(),
				true
			);

			wp_localize_script( 'jet-tricks-frontend', 'JetTricksSettings', array(
				'elements_data' => $this->elements_data,
			) );
		}

		/**
		 * Enqueue elemnetor editor-related styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			wp_enqueue_style(
				'jet-tricks-icons-font',
				jet_tricks()->plugin_url( 'assets/css/jet-tricks-icons.css' ),
				array(),
				jet_tricks()->get_version()
			);

			wp_enqueue_style(
				'jet-tricks-editor',
				jet_tricks()->plugin_url( 'assets/css/jet-tricks-editor.css' ),
				array(),
				jet_tricks()->get_version()
			);
		}

		/**
		 * Enqueue editor scripts
		 *
		 * @return void
		 */
		public function editor_scripts() {
			wp_enqueue_script(
				'jet-tricks-editor',
				jet_tricks()->plugin_url( 'assets/js/jet-tricks-editor.js' ),
				array( 'jquery' ),
				jet_tricks()->get_version(),
				true
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

function jet_tricks_assets() {
	return Jet_Tricks_Assets::get_instance();
}
