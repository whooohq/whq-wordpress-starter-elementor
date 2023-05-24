<?php
namespace Jet_Engine\Modules\Maps_Listings;

class Elementor_Integration {

	use Preview_Trait;

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
			add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ), 99 );
		} else {
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ), 99 );
		}

		add_action( 'jet-engine/listings/preview-scripts', array( $this, 'preview_scripts' ) );

		add_action( 'jet-engine/elementor-views/dynamic-tags/register', array( $this, 'register_dynamic_tags' ), 10, 2 );
	}

	/**
	 * Register widgets
	 */
	public function register_widgets( $widgets_manager ) {

		require jet_engine()->modules->modules_path( 'maps-listings/inc/widgets/maps-listings-widget.php' );

		if ( method_exists( $widgets_manager, 'register' ) ) {
			$widgets_manager->register( new Maps_Listings_Widget() );
		} else {
			$widgets_manager->register_widget_type( new Maps_Listings_Widget() );
		}

	}

	/**
	 * Register dynamic tags
	 *
	 * @param $dynamic_tags
	 * @param $tags_module
	 */
	public function register_dynamic_tags( $dynamic_tags, $tags_module ) {

		require_once jet_engine()->modules->modules_path( 'maps-listings/inc/dynamic-tags/open-map-popup.php' );

		$tags_module->register_tag( $dynamic_tags, new Dynamic_Tags\Open_Map_Popup() );

	}

}
