<?php
namespace Jet_Menu;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Elementor Integration Class class
 */
class Elementor {

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
	 * Initalize integration hooks
	 *
	 * @return void
	 */
	public function __construct() {

		add_action( 'elementor/init', array( $this, 'register_category' ) );

		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_addons' ), 10 );

		add_action( 'elementor/init', array( $this, 'init_extension_module' ) );

		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );

		add_action( 'elementor/preview/enqueue_styles', array( $this, 'editor_styles' ) );

		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );

		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_elementor_widget_scripts' ) );

		add_action( 'wp_footer', array( $this, 'init_elementor_frontend_assets' ), 9 );

	}

	/**
	 * Register plugin addons
	 *
	 * @param  object $widgets_manager Elementor widgets manager instance.
	 * @return void
	 */
	public function register_addons( $widgets_manager ) {

		$widgets_path = jet_menu_tools()->is_nextgen_mode() ? 'includes/elementor/widgets/' : 'includes/elementor/widgets/legacy/';

		foreach ( glob( jet_menu()->plugin_path( $widgets_path ) . '*.php' ) as $file ) {
			$this->register_addon( $file, $widgets_manager );
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
			$widgets_manager->register_widget_type( new $class );
		}
	}

	/**
	 * Register cherry category for elementor if not exists
	 *
	 * @return void
	 */
	public function register_category() {

		$elements_manager = \Elementor\Plugin::instance()->elements_manager;
		$existing         = $elements_manager->get_categories();
		$cherry_cat       = 'cherry';

		if ( array_key_exists( $cherry_cat, $existing ) ) {
			return;
		}

		$elements_manager->add_category( $cherry_cat, array(
			'title' => esc_html__( 'JetElements', 'jet-menu' ),
			'icon'  => 'font',
		), 1 );
	}

	/**
	 * Init Extension Module
	 */
	public function init_extension_module() {
		$ext_module_data = jet_menu()->module_loader->get_included_module_data( 'jet-elementor-extension.php' );
		\Jet_Elementor_Extension\Module::get_instance( $ext_module_data );
	}

	/**
	 * Enqueue icons font styles
	 *
	 * @return void
	 */
	public function editor_styles() {
		wp_enqueue_style(
			'jet-menu-editor',
			jet_menu()->plugin_url( 'includes/elementor/assets/editor/css/editor.css' ),
			array(),
			jet_menu()->get_version()
		);
	}

	/**
	 * Enqueue plugin scripts only with elementor scripts
	 *
	 * @return void
	 */
	public function editor_scripts() {
		$scripts_path = jet_menu_tools()->is_nextgen_mode() ? 'includes/elementor/assets/editor/js' : 'includes/elementor/assets/editor/js/legacy';

		wp_enqueue_script(
			'jet-menu-editor',
			jet_menu()->plugin_url( "{$scripts_path}/jet-menu-editor.js" ),
			array( 'jquery' ),
			jet_menu()->get_version(),
			true
		);
	}

	/**
	 * Enqueue plugin scripts only with elementor scripts
	 *
	 * @return void
	 */
	public function enqueue_elementor_widget_scripts() {
		$scripts_path = jet_menu_tools()->is_nextgen_mode() ? 'includes/elementor/assets/public/js' : 'includes/elementor/assets/public/js/legacy';

		wp_enqueue_script(
			'jet-menu-elementor-widgets-scripts',
			jet_menu()->plugin_url( "{$scripts_path}/widgets-scripts.js" ),
			apply_filters( 'jet-menu/assets/elementor/public-scripts-dependencies',
				array( 'jquery', 'elementor-frontend', 'jet-menu-public-scripts' )
			),
			jet_menu()->get_version(),
			true
		);
	}

	/**
	 * [init_elementor_frontend_assets description]
	 * @return [type] [description]
	 */
	public function init_elementor_frontend_assets() {

		// Init Elementor frontend assets if template loaded using ajax
		if ( ! \Elementor\Plugin::$instance->frontend->has_elementor_in_page() ) {
			\Elementor\Plugin::$instance->frontend->enqueue_styles();
			\Elementor\Plugin::$instance->frontend->enqueue_scripts();
		}
	}

}
