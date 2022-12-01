<?php
namespace Jet_Menu\Blocks;

class Manager {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * @var array
	 */
	private $registered_blocks = [];
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
	 * Load files
	 */
	public function load_files() {}

	/**
	 * Add new category for filters
	 *
	 * @param $categories
	 * @return false
	 */
	function add_filters_category( $categories ) {

		return array_merge( $categories,
			[
				[
					'slug'  => 'jet-menu',
					'title' => __( 'Jet Menu', 'jet-menu' ),
					'icon'  => 'filter',
				],
			]
		);

	}

	/**
	 * @return array
	 */
	public function get_registered_blocks() {
		return $this->registered_blocks;
	}

	/**
	 * @return array
	 */
	public function get_registered_block_attrs() {
		$registered_blocks = $this->get_registered_blocks();

		$block_attrs = [];

		foreach ( $registered_blocks as $block_slug => $block_instance ) {
			$block_attrs[ $block_slug ] = $block_instance->get_attributes();
		}

		return $block_attrs;
	}

	/**
	 * Register blocks assets
	 *
	 * @return false
	 */
	public function blocks_assets() {

		jet_menu_assets()->register_public_styles();
		jet_menu_assets()->register_public_scripts();

		wp_enqueue_style(
			'jet-menu-block-editor-styles',
			jet_menu()->plugin_url( 'assets/admin/css/gutenberg.css' ),
			[ 'jet-menu-public-styles' ],
			jet_menu()->get_version()
		);

		wp_enqueue_script(
			'jet-menu-blocks',
			jet_menu()->plugin_url( 'assets/admin/js/blocks.js' ),
			[ 'wp-blocks', 'wp-editor', 'wp-components', 'wp-element', 'wp-i18n', 'jet-menu-public-scripts' ],
			jet_menu()->get_version(),
			true
		);

		$localized_data = apply_filters( 'jet-menu/assets/admin/blocks/localized-data', [
			'version'              => jet_menu()->get_version(),
			'availableMenuOptions' => jet_menu_tools()->get_available_menus_options(),
			'registeredBlockAttrs' => $this->get_registered_block_attrs(),
			'breakpointsOptions'   => jet_menu_tools()->get_breakpoints_options(),
			'menuAdminUrl'         => admin_url( 'nav-menus.php?action=edit&menu=0' ),
		] );

		wp_localize_script( 'jet-menu-blocks', 'JetMenuBlocksData', $localized_data );

	}

	/**
	 * Register block types
	 *
	 * @return false
	 */
	public function register_block_types() {

		$base_path = jet_menu()->plugin_path( 'includes/blocks/blocks/' );

		require $base_path . 'base.php';

		$default_blocks = apply_filters( 'jet-menu/block-manager/blocks-list', [
			'\Jet_Menu\Blocks\Mega_Menu'   => $base_path . 'mega-menu.php',
			'\Jet_Menu\Blocks\Mobile_Menu' => $base_path . 'mobile-menu.php',
		] );

		foreach ( $default_blocks as $class => $file ) {
			require $file;

			$instance = new $class;
			$id = $instance->get_name();

			$this->registered_blocks[ $id ] = $instance;
		}

	}

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		if ( ! jet_menu_tools()->is_nextgen_mode() ) {
			return false;
		}

		$this->load_files();

		//add_filter( 'block_categories_all', [ $this, 'add_filters_category' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'blocks_assets' ] );

		add_action( 'admin_footer', [ jet_menu_assets(), 'render_vue_template' ] );

		$this->register_block_types();
	}
}
