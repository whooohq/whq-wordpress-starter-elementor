<?php
namespace Jet_Smart_Filters;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Controller class
 */
class Settings {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * [$subpage_modules description]
	 * @var array
	 */
	public $subpage_modules = array();

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

	// Here initialize our namespace and resource name.
	public function __construct() {

		$this->subpage_modules = apply_filters( 'jet-smart-filters/settings/registered-subpage-modules', array(
			'jet-smart-filters-general-settings' => array(
				'class' => '\\Jet_Smart_Filters\\Settings\\General',
				'args'  => array(),
			),
			'jet-smart-filters-indexer-settings' => array(
				'class' => '\\Jet_Smart_Filters\\Settings\\Indexer',
				'args'  => array(),
			),
			'jet-smart-filters-url-structure-settings' => array(
				'class' => '\\Jet_Smart_Filters\\Settings\\URL_Structure',
				'args'  => array(),
			),
			'jet-smart-filters-ajax-request-type' => array(
				'class' => '\\Jet_Smart_Filters\\Settings\\Ajax_Request_Type',
				'args'  => array(),
			),
		) );

		add_action( 'init', array( $this, 'register_settings_category' ), 10 );

		add_action( 'init', array( $this, 'init_plugin_subpage_modules' ), 10 );
	}

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function register_settings_category() {

		\Jet_Dashboard\Dashboard::get_instance()->module_manager->register_module_category( array(
			'name'     => esc_html__( 'JetSmartFilters', 'jet-smart-filters' ),
			'slug'     => 'jet-smart-filters-settings',
			'priority' => 1
		) );
	}

	/**
	 * [init_plugin_subpage_modules description]
	 * @return [type] [description]
	 */
	public function init_plugin_subpage_modules() {
		require jet_smart_filters()->plugin_path( 'includes/settings/subpage-modules/general.php' );
		require jet_smart_filters()->plugin_path( 'includes/settings/subpage-modules/indexer.php' );
		require jet_smart_filters()->plugin_path( 'includes/settings/subpage-modules/url-structure.php' );
		require jet_smart_filters()->plugin_path( 'includes/settings/subpage-modules/ajax-request-type.php' );

		foreach ( $this->subpage_modules as $subpage => $subpage_data ) {
			\Jet_Dashboard\Dashboard::get_instance()->module_manager->register_subpage_module( $subpage, $subpage_data );
		}
	}

}

