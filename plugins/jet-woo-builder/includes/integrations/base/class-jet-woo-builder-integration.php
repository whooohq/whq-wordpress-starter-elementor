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

if ( ! class_exists( 'Jet_Woo_Builder_Integration' ) ) {

	/**
	 * Define Jet_Woo_Builder_Integration class
	 */
	class Jet_Woo_Builder_Integration {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Check if processing elementor widget
		 *
		 * @var boolean
		 */
		private $is_elementor_ajax = false;

		/**
		 * Holder for current product instance
		 *
		 * @var array
		 */
		private $current_product = false;

		/**
		 * Initialize integration hooks
		 *
		 * @return void
		 */
		public function init() {

			add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
			add_action( 'elementor/init', array( $this, 'init_extension_module' ), 0 );

			add_action( 'elementor/widgets/widgets_registered', array( $this, 'include_wc_hooks' ), 0 );
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ), 10 );

			add_action( 'elementor/page_templates/canvas/before_content', array( $this, 'open_canvas_wrap' ) );
			add_action( 'elementor/page_templates/canvas/after_content', array( $this, 'close_canvas_wrap' ) );

			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );

			add_action( 'elementor/controls/controls_registered', array( $this, 'add_controls' ), 10 );

			add_action( 'template_redirect', array( $this, 'set_track_product_view' ), 20 );

			add_filter( 'post_class', array( $this, 'add_product_post_class' ), 20 );

			$this->include_theme_integration_file();
			$this->include_plugin_integration_file();

		}

		/**
		 * Init JetElementorExtension Module
		 */
		public function init_extension_module() {

			$ext_module_data = jet_woo_builder()->module_loader->get_included_module_data( 'jet-elementor-extension.php' );
			Jet_Elementor_Extension\Module::get_instance( $ext_module_data );

		}

		/**
		 * Set current product data
		 */
		public function set_current_product( $product_data = array() ) {
			$this->current_product = $product_data;
		}

		/**
		 * Get current product data
		 *
		 * @return array|bool
		 */
		public function get_current_product() {
			return $this->current_product;
		}

		/**
		 * Get current product data
		 *
		 * @return false
		 */
		public function reset_current_product() {
			return $this->current_product = false;
		}

		/**
		 * Enqueue editor styles.
		 *
		 * @return void
		 */
		public function editor_styles() {

			wp_enqueue_style(
				'jet-woo-builder-font',
				jet_woo_builder()->plugin_url( 'assets/css/editor/jet-woo-builder-icons.css' ),
				[],
				jet_woo_builder()->get_version()
			);

			wp_enqueue_style(
				'jet-woo-builder-icons-font',
				jet_woo_builder()->plugin_url( 'assets/css/lib/jet-woo-builder-icons/jet-woo-builder-icons.css' ),
				[],
				jet_woo_builder()->get_version()
			);

			wp_enqueue_style(
				'jet-woo-builder-editor',
				jet_woo_builder()->plugin_url( 'assets/css/editor/editor.css' ),
				[],
				jet_woo_builder()->get_version()
			);

		}

		/**
		 * Include woocommerce front-end hooks
		 *
		 * @return void
		 */
		public function include_wc_hooks() {

			$elementor    = Elementor\Plugin::instance();
			$is_edit_mode = $elementor->editor->is_edit_mode();

			if (
				! $is_edit_mode ||
				! defined( 'WC_ABSPATH' ) ||
				! file_exists( WC_ABSPATH . 'includes/wc-template-hooks.php' )
			) {
				return;
			}

			$rewrite = apply_filters( 'jet-woo-builder/integration/rewrite-frontend-hooks', false );

			if ( ! $rewrite ) {
				include_once WC_ABSPATH . 'includes/wc-template-hooks.php';
			}

			remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

		}

		/**
		 * Added body classes at single product page
		 *
		 * @param $classes
		 *
		 * @return mixed
		 */
		public function add_product_post_class( $classes ) {

			if (
				is_archive() ||
				'related' === wc_get_loop_prop( 'name' ) ||
				'up-sells' === wc_get_loop_prop( 'name' ) ||
				'cross-sells' === wc_get_loop_prop( 'name' )
			) {
				if ( filter_var( jet_woo_builder_settings()->get( 'enable_product_thumb_effect' ), FILTER_VALIDATE_BOOLEAN ) ) {
					$classes[] = 'jet-woo-thumb-with-effect';
				}
			}

			return $classes;

		}


		/**
		 * Track product views.
		 */
		public function set_track_product_view() {

			if ( ! is_singular( 'product' ) ) {
				return;
			}

			global $post;

			if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
				$viewed_products = array();
			} else {
				$viewed_products = (array)explode( '|', $_COOKIE['woocommerce_recently_viewed'] );
			}

			if ( ! in_array( $post->ID, $viewed_products ) ) {
				$viewed_products[] = $post->ID;
			}

			if ( sizeof( $viewed_products ) > 30 ) {
				array_shift( $viewed_products );
			}

			// Store for session only
			wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );

		}

		/**
		 * Include integration theme file
		 *
		 * @return void
		 */
		public function include_theme_integration_file() {

			$template = get_template();
			$int_file = jet_woo_builder()->plugin_path( "includes/integrations/themes/{$template}/functions.php" );

			if ( file_exists( $int_file ) ) {
				require $int_file;
			}

		}

		/**
		 * Include plugin integrations file
		 *
		 * @return void
		 */
		public function include_plugin_integration_file() {

			$plugins = array(
				'jet-popup.php'  => array(
					'cb'   => 'class_exists',
					'args' => 'Jet_Popup',
				),
				'jet-cw.php'     => array(
					'cb'   => 'class_exists',
					'args' => 'Jet_CW',
				),
				'jet-engine.php' => array(
					'cb'   => 'class_exists',
					'args' => 'Jet_Engine',
				),
			);

			foreach ( $plugins as $file => $condition ) {
				if ( true === call_user_func( $condition['cb'], $condition['args'] ) ) {
					require jet_woo_builder()->plugin_path( 'includes/integrations/plugins/' . $file );
				}
			}

		}

		/**
		 * Open wrapper for canvas page template for product templates
		 *
		 * @return void
		 */
		public function open_canvas_wrap() {

			if ( ! is_singular( jet_woo_builder_post_type()->slug() ) ) {
				return;
			}

			echo '<div class="product">';

		}

		/**
		 * Close wrapper for canvas page template for product templates
		 *
		 * @return void
		 */
		public function close_canvas_wrap() {

			if ( ! is_singular( jet_woo_builder_post_type()->slug() ) ) {
				return;
			}

			echo '</div>';

		}

		/**
		 * Check if we currently in Elementor mode
		 *
		 * @return bool
		 */
		public function in_elementor() {

			$result = false;

			if ( wp_doing_ajax() ) {
				$result = jet_woo_builder_elementor_views()->is_editor_ajax();
			} elseif (
				Elementor\Plugin::instance()->editor->is_edit_mode() ||
				Elementor\Plugin::instance()->preview->is_preview_mode()
			) {
				$result = true;
			}

			return apply_filters( 'jet-woo-builder/in-elementor', $result );

		}

		/**
		 * Register plugin widgets
		 *
		 * @param object $widgets_manager Elementor widgets manager instance.
		 *
		 * @return void
		 */
		public function register_widgets( $widgets_manager ) {

			$available_widgets = [
				'global'    => jet_woo_builder_settings()->get( 'global_available_widgets' ),
				'single'    => jet_woo_builder_settings()->get( 'single_product_available_widgets' ),
				'archive'   => jet_woo_builder_settings()->get( 'archive_product_available_widgets' ),
				'category'  => jet_woo_builder_settings()->get( 'archive_category_available_widgets' ),
				'shop'      => jet_woo_builder_settings()->get( 'shop_product_available_widgets' ),
				'cart'      => jet_woo_builder_settings()->get( 'cart_available_widgets' ),
				'checkout'  => jet_woo_builder_settings()->get( 'checkout_available_widgets' ),
				'thankyou'  => jet_woo_builder_settings()->get( 'thankyou_available_widgets' ),
				'myaccount' => jet_woo_builder_settings()->get( 'myaccount_available_widgets' ),
			];

			require_once jet_woo_builder()->plugin_path( 'includes/base/class-jet-woo-builder-base.php' );

			foreach ( glob( jet_woo_builder()->plugin_path( 'includes/widgets/global/' ) . '*.php' ) as $file ) {
				$slug    = basename( $file, '.php' );
				$enabled = isset( $available_widgets['global'][ $slug ] ) ? $available_widgets['global'][ $slug ] : '';

				if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $available_widgets['global'] ) {
					$this->register_widget( $file, $widgets_manager );
				}
			}

			$doc_type = jet_woo_builder()->documents->get_current_type();

			if ( ! $doc_type && get_post_type() === jet_woo_builder_post_type()->slug() ) {
				$doc_type = get_post_meta( get_the_ID(), '_elementor_template_type', true );
			}

			$doc_type  = apply_filters( 'jet-woo-builder/integration/doc-type', $doc_type );
			$doc_types = jet_woo_builder()->documents->get_document_types();

			foreach ( $doc_types as $key => $value ) {
				$template_enable   = 'custom_' . $key . '_page';
				$template_export   = isset( $_GET['action'] ) && 'jet_woo_builder_export_template' === $_GET['action'];
				$enable_in_listing = apply_filters( 'jet-woo-builder/integration/register-widgets', false, $key );

				switch ( $key ) {
					case 'single':
						$widgets_folder = 'single-product';
						break;

					case 'archive':
						$widgets_folder    = 'archive-product';
						$enable_in_listing = true;

						break;

					case 'category':
						$template_enable   = 'custom_archive_category_page';
						$widgets_folder    = 'archive-category';
						$enable_in_listing = true;

						break;

					default:
						$widgets_folder = $key;
						break;
				}

				if ( $this->is_setting_enabled( $template_enable ) || $value['slug'] === $doc_type || $template_export || $enable_in_listing ) {
					foreach ( glob( jet_woo_builder()->plugin_path( 'includes/widgets/' . $widgets_folder . '/' ) . '*.php' ) as $file ) {
						$slug    = basename( $file, '.php' );
						$enabled = isset( $available_widgets[ $key ][ $slug ] ) ? $available_widgets[ $key ][ $slug ] : '';

						if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $available_widgets[ $key ] ) {
							$this->register_widget( $file, $widgets_manager );
						}
					}
				}

			}

		}

		/**
		 * Return true if certain option is enabled.
		 *
		 * @param string $type
		 *
		 * @return bool
		 */
		public function is_setting_enabled( $type = 'custom_single_page' ) {
			return filter_var( jet_woo_builder_shop_settings()->get( $type ), FILTER_VALIDATE_BOOLEAN );
		}

		/**
		 * Register addon by file name
		 *
		 * @param string $file            File name.
		 * @param object $widgets_manager Widgets manager instance.
		 *
		 * @return void
		 */
		public function register_widget( $file, $widgets_manager ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );
			$class = sprintf( 'Elementor\%s', $class );

			require_once $file;

			if ( class_exists( $class ) ) {
				$widgets_manager->register_widget_type( new $class );
			}

		}

		/**
		 * Registering a new widget category.
		 *
		 * Register JetWooBuilder category for elementor if not exists.
		 *
		 * @return void
		 */
		public function register_category( $elements_manager ) {
			$elements_manager->add_category(
				'jet-woo-builder',
				[
					'title' => __( 'JetWooBuilder', 'jet-woo-builder' ),
					'icon'  => 'eicon-font',
				]
			);
		}

		/**
		 * Add new controls.
		 *
		 * @param object $controls_manager Controls manager instance.
		 *
		 * @return void
		 */
		public function add_controls( $controls_manager ) {

			$grouped = array(
				'jet-woo-box-style' => 'Jet_Woo_Group_Control_Box_Style',
			);

			foreach ( $grouped as $control_id => $class_name ) {
				if ( $this->include_control( $class_name, true ) ) {
					$controls_manager->add_group_control( $control_id, new $class_name() );
				}
			}

		}

		/**
		 * Include control file by class name.
		 *
		 * @param      $class_name
		 * @param bool $grouped
		 *
		 * @return bool
		 */
		public function include_control( $class_name = '', $grouped = false ) {

			$filename = sprintf(
				'includes/controls/%2$sclass-%1$s.php',
				str_replace( '_', '-', strtolower( $class_name ) ),
				( true === $grouped ? 'groups/' : '' )
			);

			if ( ! file_exists( jet_woo_builder()->plugin_path( $filename ) ) ) {
				return false;
			}

			require jet_woo_builder()->plugin_path( $filename );

			return true;

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance( $shortcodes = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}

			return self::$instance;

		}

	}

}

/**
 * Returns instance of Jet_Woo_Builder_Integration
 *
 * @return object
 */
function jet_woo_builder_integration() {
	return Jet_Woo_Builder_Integration::get_instance();
}