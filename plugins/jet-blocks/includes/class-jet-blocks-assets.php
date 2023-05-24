<?php

use Elementor\Core\Responsive\Files\Frontend as FrontendFile;
use Elementor\Core\Responsive\Responsive;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Blocks_Assets' ) ) {

	/**
	 * Define Jet_Blocks_Assets class
	 */
	class Jet_Blocks_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'elementor/frontend/before_enqueue_styles',  array( $this, 'enqueue_styles' ) );
			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'elementor/editor/before_enqueue_scripts',   array( $this, 'editor_scripts' ) );
			add_action( 'elementor/editor/after_enqueue_styles',     array( $this, 'editor_styles' ) );

			add_filter( 'elementor/core/responsive/get_stylesheet_templates', array( $this, 'add_responsive_css_templates' ) );
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

			$file_name = 'jet-blocks' . $direction_suffix . '.css';

			$frontend_file = new FrontendFile( 'custom-' . $file_name, jet_blocks()->plugin_path( 'assets/css/templates/' . $file_name ) );

			$time = $frontend_file->get_meta( 'time' );

			if ( ! $time ) {
				$frontend_file->update();
			}

			$style_url = $frontend_file->get_url();

			wp_enqueue_style(
				'jet-blocks',
				$style_url,
				false,
				jet_blocks()->get_version()
			);

		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			$min_suffix = jet_blocks_tools()->is_script_debug() ? '' : '.min';

			do_action( 'jet-blocks/frontend/before_enqueue_scripts' );

			wp_enqueue_script(
				'jet-blocks',
				jet_blocks()->plugin_url( 'assets/js/jet-blocks' . $min_suffix . '.js' ),
				array( 'jquery', 'elementor-frontend' ),
				jet_blocks()->get_version(),
				true
			);

			$localize_data = apply_filters( 'jet-blocks/frontend/localize-data', array() );

			if ( ! empty( $localize_data ) ) {
				wp_localize_script(
					'jet-blocks',
					'jetBlocksData',
					$localize_data
				);
			}

			$rest_api_url = apply_filters( 'jet-blocks/rest/url', get_rest_url() );

			wp_localize_script( 'jet-blocks', 'JetHamburgerPanelSettings', array(
				'ajaxurl'        => esc_url( admin_url( 'admin-ajax.php' ) ),
				'isMobile'       => filter_var( wp_is_mobile(), FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false',
				'templateApiUrl' => $rest_api_url . 'jet-blocks-api/v1/elementor-template',
				'devMode'        => is_user_logged_in() ? 'true' : 'false',
				'restNonce'      => wp_create_nonce( 'wp_rest' ),
			) );
		}

		/**
		 * Enqueue elemnetor editor-related styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			wp_enqueue_style(
				'jet-blocks-editor',
				jet_blocks()->plugin_url( 'assets/css/jet-blocks-editor.css' ),
				array(),
				jet_blocks()->get_version()
			);

		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function editor_scripts() {

			$min_suffix = jet_blocks_tools()->is_script_debug() ? '' : '.min';

			wp_enqueue_script(
				'jet-blocks-editor',
				jet_blocks()->plugin_url( 'assets/js/jet-blocks-editor' . $min_suffix . '.js' ),
				array( 'jquery' ),
				jet_blocks()->get_version(),
				true
			);

		}

		/**
		 * Add responsive css templates.
		 *
		 * @param array $templates CSS templates.
		 *
		 * @return array
		 */
		function add_responsive_css_templates( $templates = array() ) {
			$templates_paths = glob( jet_blocks()->plugin_path( 'assets/css/templates' ) . '*.css' );

			foreach ( $templates_paths as $template_path ) {
				$file_name = 'custom-' . basename( $template_path );

				$templates[ $file_name ] = $template_path;
			}

			return $templates;
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
 * Returns instance of Jet_Blocks_Assets
 *
 * @return object
 */
function jet_blocks_assets() {
	return Jet_Blocks_Assets::get_instance();
}
