<?php
/**
 * Plugin Name: JetWooBuilder For Elementor
 * Plugin URI:  https://crocoblock.com/plugins/jetwoobuilder/
 * Description: Your perfect asset in creating WooCommerce page templates using loads of special widgets & stylish page layouts
 * Version:     2.1.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * Text Domain: jet-woo-builder
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 *
 * WC tested up to: 7.1
 * WC requires at least: 3.0
 *
 * Elementor tested up to: 3.8
 * Elementor Pro tested up to: 3.8
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Jet_Woo_Builder` doesn't exists yet.
if ( ! class_exists( 'Jet_Woo_Builder' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Jet_Woo_Builder {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		private $version = '2.1.0';

		/**
		 * Require Elementor Version
		 *
		 * @since 1.8.0
		 * @var string Elementor version required to run the plugin.
		 */
		private static $require_elementor_version = '3.0.0';

		/**
		 * Holder for base plugin URL
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_url = null;

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Plugin properties
		 */
		public $module_loader;

		/**
		 * @var Jet_Woo_Builder_Documents
		 */
		public $documents;

		/**
		 * @var Jet_Woo_Builder_Parser
		 */
		public $parser;

		/**
		 * @var Jet_Woo_Builder_Macros
		 */
		public $macros;

		/**
		 * @var Jet_Woo_Builder_Ajax_Handlers
		 */
		public $ajax_handlers;

		/**
		 * @var Jet_Woo_Builder_Export_Import
		 */
		public $export_import;

		/**
		 * @var Jet_Woo_Builder_Components
		 */
		public $components;

		/**
		 * @var Jet_Woo_Builder_Dynamic_Tags_Manager
		 */
		public $dynamic_tags;

		/**
		 * @var Jet_Woo_Builder_Compatibility
		 */
		public $compatibility;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Load the core functions/classes required by the rest of the plugin.
			add_action( 'after_setup_theme', array( $this, 'module_loader' ), -20 );

			// Check if Elementor installed and activated.
			if ( ! did_action( 'elementor/loaded' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );

				return;
			}

			// Check for required Elementor version.
			if ( ! version_compare( ELEMENTOR_VERSION, self::$require_elementor_version, '>=' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_required_elementor_version' ] );

				return;
			}

			// Check if WooCommerce installed and activated.
			add_action( 'plugins_loaded', array( $this, 'woocommerce_loaded' ) );

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );

			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );

			// Jet Dashboard Init
			add_action( 'init', array( $this, 'jet_dashboard_init' ), -999 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

		}

		/**
		 * Load plugin framework
		 */
		public function module_loader() {

			require $this->plugin_path( 'includes/modules/loader.php' );

			$this->module_loader = new Jet_Woo_Builder_CX_Loader(
				array(
					$this->plugin_path( 'includes/modules/interface-builder/cherry-x-interface-builder.php' ),
					$this->plugin_path( 'includes/modules/post-meta/cherry-x-post-meta.php' ),
					$this->plugin_path( 'includes/modules/db-updater/cherry-x-db-updater.php' ),
					$this->plugin_path( 'includes/modules/vue-ui/cherry-x-vue-ui.php' ),
					$this->plugin_path( 'includes/modules/jet-dashboard/jet-dashboard.php' ),
					$this->plugin_path( 'includes/modules/jet-elementor-extension/jet-elementor-extension.php' ),
					$this->plugin_path( 'includes/modules/admin-bar/jet-admin-bar.php' ),
				)
			);

		}

		/**
		 * Returns plugin version
		 *
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Manually init required modules
		 */
		public function init() {
			if ( class_exists( 'WooCommerce' ) ) {
				$this->load_files();

				jet_woo_builder_assets()->init();
				jet_woo_builder_post_type()->init();
				jet_woo_builder_settings()->init();
				jet_woo_builder_shortcodes()->init();
				jet_woo_builder_shop_settings()->init();

				$this->documents     = new Jet_Woo_Builder_Documents();
				$this->parser        = new Jet_Woo_Builder_Parser();
				$this->macros        = new Jet_Woo_Builder_Macros();
				$this->ajax_handlers = new Jet_Woo_Builder_Ajax_Handlers();
				$this->export_import = new Jet_Woo_Builder_Export_Import();
				$this->components    = new Jet_Woo_Builder_Components();
				$this->compatibility = new Jet_Woo_Builder_Compatibility();
				$this->admin_bar     = Jet_Admin_Bar::get_instance();

				if ( is_admin() ) {
					//Init JetWooBuilder Settings
					new \Jet_Woo_Builder\Settings();

					// Init DB upgrader
					require $this->plugin_path( 'includes/class-jet-woo-builder-db-upgrader.php' );

					jet_woo_builder_db_upgrader()->init();
				}

				//Init Rest Api
				new \Jet_Woo_Builder\Rest_Api();
			}
		}

		/**
		 * Init the JetDashboard module
		 */
		public function jet_dashboard_init() {
			if ( is_admin() ) {
				$jet_dashboard_module_data = $this->module_loader->get_included_module_data( 'jet-dashboard.php' );
				$jet_dashboard             = \Jet_Dashboard\Dashboard::get_instance();

				$jet_dashboard->init(
					array(
						'path'           => $jet_dashboard_module_data['path'],
						'url'            => $jet_dashboard_module_data['url'],
						'cx_ui_instance' => array( $this, 'jet_dashboard_ui_instance_init' ),
						'plugin_data'    => array(
							'slug'         => 'jet-woo-builder',
							'file'         => 'jet-woo-builder/jet-woo-builder.php',
							'version'      => $this->get_version(),
							'plugin_links' => array(
								array(
									'label'  => esc_html__( 'Go to settings', 'jet-woo-builder' ),
									'url'    => add_query_arg( array( 'page' => 'jet-dashboard-settings-page', 'subpage' => 'jet-woo-builder-general-settings' ), admin_url( 'admin.php' ) ),
									'target' => '_self',
								),
							),
						),
					)
				);
			}
		}

		/**
		 * Get Vue UI Instance for JetDashboard module
		 *
		 * @return object
		 */
		public function jet_dashboard_ui_instance_init() {

			$cx_ui_module_data = $this->module_loader->get_included_module_data( 'cherry-x-vue-ui.php' );

			return new CX_Vue_UI( $cx_ui_module_data );

		}

		/**
		 * Check that WooCommerce active
		 *
		 * @return void
		 */
		function woocommerce_loaded() {
			if ( ! class_exists( 'WooCommerce' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_missing_woocommerce_plugin' ] );

				return;
			}
		}

		/**
		 * Show recommended plugins notice
		 */
		public function admin_notice_missing_main_plugin() {

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			$elementor_link = sprintf(
				'<a href="%1$s">%2$s</a>',
				admin_url() . 'plugin-install.php?s=elementor&tab=search&type=term',
				'<strong>' . esc_html__( 'Elementor', 'jet-woo-builder' ) . '</strong>'
			);
			$message        = sprintf(
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'jet-woo-builder' ),
				'<strong>' . esc_html__( 'JetWooBuilder', 'jet-woo-builder' ) . '</strong>',
				$elementor_link
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

			if ( ! class_exists( 'WooCommerce' ) ) {
				$woocommerce_link = sprintf(
					'<a href="%1$s">%2$s</a>',
					admin_url() . 'plugin-install.php?s=woocommerce&tab=search&type=term',
					'<strong>' . esc_html__( 'WooCommerce', 'jet-woo-builder' ) . '</strong>'
				);
				$message          = sprintf(
					esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'jet-woo-builder' ),
					'<strong>' . esc_html__( 'JetWooBuilder', 'jet-woo-builder' ) . '</strong>',
					$woocommerce_link
				);

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
			}

		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have a minimum required Elementor version.
		 *
		 * @since  1.8.0
		 * @access public
		 */
		public function admin_notice_required_elementor_version() {

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'jet-woo-builder' ),
				'<strong>' . esc_html__( 'JetWooBuilder', 'jet-woo-builder' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'jet-woo-builder' ) . '</strong>',
				self::$require_elementor_version
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}

		/**
		 * Show WooCommerce plugin notice
		 */
		public function admin_notice_missing_woocommerce_plugin() {

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			$woocommerce_link = sprintf(
				'<a href="%1$s">%2$s</a>',
				admin_url() . 'plugin-install.php?s=woocommerce&tab=search&type=term',
				'<strong>' . esc_html__( 'WooCommerce', 'jet-woo-builder' ) . '</strong>'
			);
			$message          = sprintf(
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'jet-woo-builder' ),
				'<strong>' . esc_html__( 'JetWooBuilder', 'jet-woo-builder' ) . '</strong>',
				$woocommerce_link
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

		}

		/**
		 * Check if theme has Elementor
		 *
		 * @return boolean
		 */
		public function has_elementor() {
			return defined( 'ELEMENTOR_VERSION' );
		}

		/**
		 * Returns Elementor instance
		 *
		 * @return object
		 */
		public function elementor() {
			return \Elementor\Plugin::$instance;
		}

		/**
		 * Returns utility instance
		 *
		 * @return object
		 */
		public function utility() {
			$utility = $this->get_core()->modules['cherry-utility'];

			return $utility->utility;
		}

		/**
		 * Load required files.
		 */
		public function load_files() {

			require $this->plugin_path( 'includes/class-jet-woo-builder-ajax-handler.php' );
			require $this->plugin_path( 'includes/class-jet-woo-builder-assets.php' );
			require $this->plugin_path( 'includes/class-jet-woo-builder-tools.php' );
			require $this->plugin_path( 'includes/class-jet-woo-builder-post-type.php' );
			require $this->plugin_path( 'includes/class-jet-woo-builder-documents.php' );
			require $this->plugin_path( 'includes/class-jet-woo-builder-parser.php' );
			require $this->plugin_path( 'includes/class-jet-woo-builder-macros.php' );
			require $this->plugin_path( 'includes/class-jet-woo-builder-common-controls.php' );
			require $this->plugin_path( 'includes/class-jet-woo-builder-template-functions.php' );
			require $this->plugin_path( 'includes/class-jet-woo-builder-shortcodes.php' );
			require $this->plugin_path( 'includes/export-import.php' );

			require $this->plugin_path( 'includes/compatibility/manager.php' );

			require $this->plugin_path( 'includes/components/manager.php' );

			require $this->plugin_path( 'includes/settings/manager.php' );
			require $this->plugin_path( 'includes/settings/class-jet-woo-builder-settings.php' );
			require $this->plugin_path( 'includes/settings/class-jet-woo-builder-shop-settings.php' );

			require $this->plugin_path( 'includes/rest-api/rest-api.php' );
			require $this->plugin_path( 'includes/rest-api/endpoints/base.php' );
			require $this->plugin_path( 'includes/rest-api/endpoints/plugin-settings.php' );

		}

		/**
		 * Returns path to file or dir inside plugin folder
		 *
		 * @param string $path Path inside plugin dir.
		 *
		 * @return string
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;

		}

		/**
		 * Returns url to file or dir inside plugin folder
		 *
		 * @param string $path Path inside plugin dir.
		 *
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;

		}

		/**
		 * Loads the translation files.
		 *
		 * @since  1.0.0
		 * @access public
		 */
		public function lang() {
			load_plugin_textdomain( 'jet-woo-builder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'jet-woo-builder/template-path', 'jet-woo-builder/' );
		}

		/**
		 * Returns path to template file.
		 *
		 * @return string|bool
		 */
		public function get_template( $name = null ) {

			$template = locate_template( $this->template_path() . $name );

			if ( ! $template ) {
				$template = $this->plugin_path( 'templates/' . $name );
			}

			if ( file_exists( $template ) ) {
				return $template;
			} else {
				return false;
			}

		}

		/**
		 * Compare WooCommerce version with your version
		 *
		 * @param string $version
		 *
		 * @return bool
		 */
		public static function wc_version_check( $version = '3.6' ) {

			if ( class_exists( 'WooCommerce' ) ) {
				global $woocommerce;

				if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
					return true;
				}
			}

			return false;

		}

		/**
		 * Do some stuff on plugin activation.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function activation() {
		}

		/**
		 * Do some stuff on plugin activation.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function deactivation() {
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
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

if ( ! function_exists( 'jet_woo_builder' ) ) {

	/**
	 * Returns instance of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function jet_woo_builder() {
		return Jet_Woo_Builder::get_instance();
	}

}

jet_woo_builder();
