<?php
namespace Jet_Menu;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Integration
 * @package Jet_Menu
 */
class Integration {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Dirname holder for plugins integration loader
	 *
	 * @var string
	 */
	private $dir = null;

	/**
	 * [$registered_subpage_modules description]
	 * @var array
	 */
	private $registered_plugins = array();

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
	 * Settings constructor.
	 */
	public function __construct() {
		$this->registered_plugins = apply_filters( 'jet-menu/integration-manager/registered-plugins', [
			'jet-form-builder' => array(
				'class'    => '\\Jet_Menu\\Integration\\Jet_Form_Builder',
				'instance' => false,
				'path'     => jet_menu()->plugin_path( 'integration/plugins/jet-form-builder/manager.php' ),
			),
		] );

		$this->load_compatibility_modules();

		$this->include_integration_theme_file();
		$this->include_integration_plugin_file();
	}

	/**
	 * [maybe_load_theme_module description]
	 * @return [type] [description]
	 */
	public function load_compatibility_modules() {

		$this->registered_plugins = array_map( function( $module_data ) {
			$class = $module_data['class'];

			if ( file_exists( $module_data['path'] ) ) {
				require $module_data['path'];
			}

			if ( ! $module_data['instance'] && class_exists( $class ) ) {
				$module_data['instance'] = new $class();
			}

			return $module_data;
		}, $this->registered_plugins );

	}

	/**
	 * Include integration theme file
	 *
	 * @return void
	 */
	public function include_integration_theme_file() {

		$template = get_template();
		$disabled = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-disable-integration-' . $template, 'false' );

		if ( is_readable( jet_menu()->plugin_path( "integration/themes/{$template}/functions.php" ) ) && ! filter_var( $disabled, FILTER_VALIDATE_BOOLEAN ) ) {
			require jet_menu()->plugin_path( "integration/themes/{$template}/functions.php" );
		}

	}

	/**
	 * Include plugin integrations file
	 *
	 * @return [type] [description]
	 */
	public function include_integration_plugin_file() {

		$active_plugins = get_option( 'active_plugins' );

		foreach ( glob( jet_menu()->plugin_path( 'integration/plugins/*' ) ) as $path ) {

			if ( ! is_dir( $path ) ) {
				continue;
			}

			$this->dir = basename( $path );

			$matched_plugins = array_filter( $active_plugins, array( $this, 'is_plugin_active' ) );

			if ( ! empty( $matched_plugins ) ) {
				require "{$path}/functions.php";
			}
		}
	}

	/**
	 * Callback to check if plugin is active
	 * @param  [type]  $plugin [description]
	 * @return boolean         [description]
	 */
	public function is_plugin_active( $plugin ) {
		return ( false !== strpos( $plugin, $this->dir . '/' ) );
	}

	/**
	 * Returns URL for current theme in theme-integration directory
	 *
	 * @param  string $file Path to file inside theme folder
	 * @return [type]       [description]
	 */
	public function get_theme_url( $file ) {
		$template = get_template();

		return jet_menu()->plugin_url( "integration/themes/{$template}/{$file}" );
	}

}

