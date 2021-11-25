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

if ( ! class_exists( 'Jet_Menu_Assets' ) ) {

	/**
	 * Define Jet_Menu_Assets class
	 */
	class Jet_Menu_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

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

		/**
		 * Constructor for the class
		 */
		public function init() {

			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ), 9 );

			add_action( 'wp_enqueue_scripts', array( $this, 'register_public_styles' ), 9 );

			add_action( 'wp_enqueue_scripts', array( $this, 'register_public_scripts' ), 9 );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_assets' ), 10 );

			add_action( 'wp_footer', array( $this, 'render_vue_template' ) );

		}

		/**
		 * Register admin assets
		 *
		 * @param  string $hook Current page hook.
		 * @return void
		 */
		public function register_admin_assets() {

			wp_register_style(
				'font-awesome-all',
				jet_menu()->plugin_url( 'assets/public/lib/font-awesome/css/all.min.css' ),
				array(),
				'5.12.0'
			);

			wp_register_style(
				'font-awesome-v4-shims',
				jet_menu()->plugin_url( 'assets/public/lib/font-awesome/css/v4-shims.min.css' ),
				array(),
				'5.12.0'
			);

			wp_register_style(
				'jet-menu-admin',
				jet_menu()->plugin_url( 'assets/admin/css/admin.css' ),
				apply_filters( 'jet-menu/assets/admin-styles-dependencies', array(
					'font-awesome-all',
					'font-awesome-v4-shims',
				) ),
				jet_menu()->get_version()
			);
		}

		/**
		 * Load admin assets
		 *
		 * @param  string $hook Current page hook.
		 * @return void
		 */
		public function register_public_styles() {
			wp_register_style(
				'font-awesome-all',
				jet_menu()->plugin_url( 'assets/public/lib/font-awesome/css/all.min.css' ),
				array(),
				'5.12.0'
			);

			wp_register_style(
				'font-awesome-v4-shims',
				jet_menu()->plugin_url( 'assets/public/lib/font-awesome/css/v4-shims.min.css' ),
				array(),
				'5.12.0'
			);

			wp_register_style(
				'jet-menu-public-styles',
				jet_menu()->plugin_url( 'assets/public/css/public.css' ),
				apply_filters( 'jet-menu/assets/public-styles-dependencies', array(
					'font-awesome-all',
					'font-awesome-v4-shims',
				) ),
				jet_menu()->get_version()
			);
		}

		/**
		 * Load public assets
		 *
		 * @param  string $hook Current page hook.
		 * @return void
		 */
		public function register_public_scripts() {

			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_register_script(
				'jet-vue',
				jet_menu()->plugin_url( 'assets/public/lib/vue/vue' . $suffix . '.js' ),
				array(),
				'2.6.11',
				true
			);

			wp_register_script(
				'jet-menu-polyfills',
				jet_menu()->plugin_url( 'assets/public/js/jet-menu-polyfills.js' ),
				array(),
				jet_menu()->get_version(),
				true
			);

			$public_script_depend = apply_filters( 'jet-menu/assets/public-script-dependencies', array(
                'jquery',
                'jet-vue',
            ) );

			$scripts_path = jet_menu_tools()->is_nextgen_mode() ? 'assets/public/js' : 'assets/public/js/legacy';

			wp_register_script(
				'jet-menu-public-scripts',
				jet_menu()->plugin_url( "{$scripts_path}/jet-menu-public-scripts.js" ),
				$public_script_depend,
				jet_menu()->get_version(),
				true
			);

			$rest_api_url = apply_filters( 'jet-menu/rest/url', get_rest_url() );

			wp_localize_script( 'jet-menu-public-scripts', 'jetMenuPublicSettings', apply_filters(
				'jet-menu/assets/public/localize',
				array(
					'version'          => jet_menu()->get_version(),
					'ajaxUrl'          => esc_url( admin_url( 'admin-ajax.php' ) ),
					'isMobile'         => filter_var( Jet_Menu_Tools::is_phone(), FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false',
					'templateApiUrl'   => $rest_api_url . 'jet-menu-api/v1/elementor-template',
					'menuItemsApiUrl'  => $rest_api_url . 'jet-menu-api/v1/get-menu-items',
					'restNonce'        => wp_create_nonce( 'wp_rest' ),
					'devMode'          => is_user_logged_in() ? 'true' : 'false',
					'wpmlLanguageCode' => defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false, // WPML Language Code
					'menuSettings'     => array(
						'jetMenuRollUp'            => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-roll-up', 'false' ),
						'jetMenuMouseleaveDelay'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mouseleave-delay', 500 ),
						'jetMenuMegaWidthType'     => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-width-type', 'container' ),
						'jetMenuMegaWidthSelector' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-selector-width-type', '' ),
						'jetMenuMegaOpenSubType'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-open-sub-type', 'hover' ),
						'jetMenuMegaAjax'          => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mega-ajax-loading', 'false' ),
					),
				)
			) );
		}

		/**
		 * Enqueue public assets.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_public_assets() {
			wp_enqueue_style( 'jet-menu-public-styles' );
			wp_enqueue_script( 'jet-menu-public-scripts' );
		}

		/**
		 * [render_vue_template description]
		 * @return [type] [description]
		 */
		public function render_vue_template() {

			$vue_templates = array(
				'mobile-menu',
				'mobile-menu-list',
				'mobile-menu-item',
			);

			foreach ( glob( jet_menu()->plugin_path() . 'templates/public/vue-templates/*.php' ) as $file ) {
				$path_info = pathinfo( $file );
				$template_name = $path_info['filename'];

				if ( in_array( $template_name, $vue_templates ) ) {?>
					<script type="text/x-template" id="<?php echo $template_name; ?>-template"><?php
						require $file; ?>
					</script><?php
				}
			}
		}

	}

}

/**
 * Returns instance of Jet_Menu_Assets
 *
 * @return object
 */
function jet_menu_assets() {
	return Jet_Menu_Assets::get_instance();
}
