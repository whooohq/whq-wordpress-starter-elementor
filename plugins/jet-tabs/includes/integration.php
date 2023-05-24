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

use Elementor\Utils;

if ( ! class_exists( 'Jet_Tabs_Integration' ) ) {

	/**
	 * Define Jet_Tabs_Integration class
	 */
	class Jet_Tabs_Integration {

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
		 * Initalize integration hooks
		 *
		 * @return void
		 */
		public function init() {

			add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );

			if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
				add_action( 'elementor/widgets/register', array( $this, 'register_addons' ), 10 );
			} else {
				add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_addons' ), 10 );
			}

			add_action( 'elementor/controls/controls_registered', array( $this, 'add_controls' ), 10 );

			add_action( 'wp_ajax_elementor_render_widget', array( $this, 'set_elementor_ajax' ), 10, -1 );

			add_action( 'template_include', array( $this, 'set_post_type_template' ), 9999 );

			add_filter( 'elementor/editor/localize_settings', array( $this, 'elementor_editor_localize_settings' ), 10, 2 );

			// Init Jet Elementor Extension module
			$ext_module_data = jet_tabs()->module_loader->get_included_module_data( 'jet-elementor-extension.php' );

			Jet_Elementor_Extension\Module::get_instance(
				array(
					'path' => $ext_module_data['path'],
					'url'  => $ext_module_data['url'],
				)
			);

		}

		/**
		 * Set $this->is_elementor_ajax to true on Elementor AJAX processing
		 *
		 * @return  void
		 */
		public function set_elementor_ajax() {
			$this->is_elementor_ajax = true;
		}

		/**
		 * Check if we currently in Elementor mode
		 *
		 * @return void
		 */
		public function in_elementor() {

			$result = false;

			if ( wp_doing_ajax() ) {
				$result = $this->is_elementor_ajax;
			} elseif ( Elementor\Plugin::instance()->editor->is_edit_mode()
				|| Elementor\Plugin::instance()->preview->is_preview_mode() ) {
				$result = true;
			}

			/**
			 * Allow to filter result before return
			 *
			 * @var bool $result
			 */
			return apply_filters( 'jet-tabs/in-elementor', $result );
		}

		/**
		 * Check if we currently in Elementor editor mode
		 *
		 * @return void
		 */
		public function is_edit_mode() {

			$result = false;

			if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				$result = true;
			}

			/**
			 * Allow to filter result before return
			 *
			 * @var bool $result
			 */
			return apply_filters( 'jet-tabs/is-edit-mode', $result );
		}

		/**
		 * Add new controls.
		 *
		 * @param  object $controls_manager Controls manager instance.
		 * @return void
		 */
		public function add_controls( $controls_manager ) {

			$grouped = array(
				'jet-tabs-box-style' => 'Jet_Tabs_Group_Control_Box_Style',
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
		 * @param  [type] $class_name [description]
		 * @return [type]             [description]
		 */
		public function include_control( $class_name, $grouped = false ) {

			$filename = sprintf(
				'includes/controls/%2$sclass-%1$s.php',
				str_replace( '_', '-', strtolower( $class_name ) ),
				( true === $grouped ? 'groups/' : '' )
			);

			if ( ! file_exists( jet_tabs()->plugin_path( $filename ) ) ) {
				return false;
			}

			require jet_tabs()->plugin_path( $filename );

			return true;
		}

		/**
		 * Register plugin addons
		 *
		 * @param  object $widgets_manager Elementor widgets manager instance.
		 * @return void
		 */
		public function register_addons( $widgets_manager ) {

			$avaliable_widgets = jet_tabs_settings()->get( 'avaliable_widgets' );

			require jet_tabs()->plugin_path( 'includes/base/class-jet-tabs-base.php' );

			foreach ( glob( jet_tabs()->plugin_path( 'includes/addons/' ) . '*.php' ) as $file ) {

				$slug    = basename( $file, '.php' );
				$enabled = isset( $avaliable_widgets[ $slug ] ) ? $avaliable_widgets[ $slug ] : '';

				if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $avaliable_widgets ) {
					$this->register_addon( $file, $widgets_manager );
				}
			}
		}

		/**
		 * Register addon by file name
		 *
		 * @param  string $file            File name.
		 * @param  object $widgets_manager Widgets manager instance.
		 * @return void
		 */
		public function register_addon( $file, $widgets_manager ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );
			$class = sprintf( 'Elementor\%s', $class );

			require $file;

			if ( class_exists( $class ) ) {
				if ( method_exists( $widgets_manager, 'register' ) ) {
					$widgets_manager->register( new $class );
				} else {
					$widgets_manager->register_widget_type( new $class );
				}
			}
		}

		/**
		 * Register cherry category for elementor if not exists
		 *
		 * @return void
		 */
		public function register_category( $elements_manager ) {

			$cherry_cat = 'jet-tabs';

			$elements_manager->add_category(
				$cherry_cat,
				array(
					'title' => esc_html__( 'JetTabs', 'jet-tabs' ),
					'icon'  => 'font',
				),
				1
			);
		}

		/**
		 * Set blank template for editor
		 */
		public function set_post_type_template( $template ) {

			if ( ! isset( $_REQUEST['jet-tabs-canvas'] ) ) {
				return $template;
			}

			$found = false;

			if ( is_singular( 'elementor_library' ) ) {
				$found    = true;
				$template = jet_tabs()->plugin_path( 'templates/blank.php' );
			}

			if ( $found ) {
				do_action( 'jet-tabs/template-include/found' );
			}

			return $template;
		}

		/**
		 * Elementor editor localize settings
		 *
		 * @param  array   $settings
		 * @param  int $id post id
		 *
		 * @return array
		 */
		public function elementor_editor_localize_settings( $settings, $id ) { // todo: check if need

			if ( version_compare( ELEMENTOR_VERSION, '2.0.0', '<' ) ) {
				$preview_url = Utils::get_preview_url( $id );
			} else {
				$preview_url = Elementor\Plugin::$instance->documents->get( $id )->get_preview_url();
			}

			return array(
				'preview_link' => $preview_url . '&jet-tabs-canvas',
			);
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
 * Returns instance of Jet_Tabs_Integration
 *
 * @return object
 */
function jet_tabs_integration() {
	return Jet_Tabs_Integration::get_instance();
}
