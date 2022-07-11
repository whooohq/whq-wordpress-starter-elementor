<?php
namespace Jet_Engine\Modules\Maps_Listings\Filters;

class Manager {

	public function __construct() {
		
		add_action( 'wp_enqueue_scripts', array( $this, 'register_geolocation_assets' ) );
		
		add_action( 'jet-smart-filters/providers/register', array( $this, 'register_filters_provider' ) );
		add_action( 'jet-smart-filters/filter-types/register', array( $this, 'register_filter_types' ) );
		
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ), 20 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'register_blocks_assets' ), 9 );
		add_action( 'init', array( $this, 'register_blocks_types' ), 999 );
		add_action( 'jet-smart-filters/blocks/localized-data', array( $this, 'modify_filters_localized_data' ) );

		add_filter( 'jet-smart-filters/query/vars', array( $this, 'register_query_var' ) );

	}

	public function register_blocks_types() {
		require jet_engine()->modules->modules_path( 'maps-listings/inc/filters/blocks/user-geolocation.php' );
		new Blocks\User_Geolocation();
	}

	public function register_blocks_assets() {

		$this->register_geolocation_assets();

		wp_enqueue_script(
			'jet-maps-listings-geolocation-blocks',
			jet_engine()->plugin_url( 'includes/modules/maps-listings/assets/js/admin/blocks.js' ),
			array( 'wp-blocks','wp-editor', 'wp-components', 'wp-i18n' ),
			jet_engine()->get_version(),
			true
		);

	}

	public function register_query_var( $vars ) {
		$vars[] = 'geo_query';
		return $vars;
	}

	public function register_geolocation_assets() {
		wp_register_script(
			'jet-maps-listings-user-geolocation',
			jet_engine()->plugin_url( 'includes/modules/maps-listings/assets/js/public/user-geolocation.js' ),
			array( 'jquery' ),
			jet_engine()->get_version(),
			true
		);
	}

	public function register_widgets( $widgets_manager ) {
		require jet_engine()->modules->modules_path( 'maps-listings/inc/filters/elementor-widgets/user-geolocation.php' );
		$widgets_manager->register_widget_type( new Elementor_Widgets\User_Geolocation() );
	}

	public function register_filter_types( $types_manager ) {
		$types_manager->register_filter_type(
			'\Jet_Engine\Modules\Maps_Listings\Filters\Types\User_Geolocation',
			jet_engine()->modules->modules_path( 'maps-listings/inc/filters/types/user-geolocation.php' )
		);
	}

	/**
	 * Register custom provider for SmartFilters
	 *
	 * @return [type] [description]
	 */
	public function register_filters_provider( $providers_manager ) {
		$providers_manager->register_provider(
			'\Jet_Engine\Modules\Maps_Listings\Filters\Provider',
			jet_engine()->modules->modules_path( 'maps-listings/inc/filters/provider.php' )
		);
	}

	public function modify_filters_localized_data( $data ) {
		$data['providers']['jet-engine-maps'] = __( 'Map Listing', 'jet-engine' );
		return $data;
	}

}
