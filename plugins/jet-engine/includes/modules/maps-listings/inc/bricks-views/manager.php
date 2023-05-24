<?php
namespace Jet_Engine\Modules\Maps_Listings\Bricks_Views;

use Jet_Engine\Modules\Maps_Listings\Preview_Trait;

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Manager {

	use Preview_Trait;

	/**
	 * Elementor Frontend instance
	 *
	 * @var null
	 */
	public $frontend = null;

	/**
	 * Constructor for the class
	 */
	function __construct() {

		if ( ! $this->has_bricks() ) {
			return;
		}

		add_action( 'init', array( $this, 'register_elements' ), 11 );

		if ( bricks_is_builder() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'preview_scripts' ) );
		}

		add_action( 'jet-engine/maps-listings/get-map-marker', array( $this, 'setup_bricks_query' ) );

	}

	public function setup_bricks_query( $listing_id ) {
		jet_engine()->bricks_views->listing->render->set_bricks_query( $listing_id, [] );
	}

	public function module_path( $relative_path = '' ) {
		return jet_engine()->plugin_path( 'includes/modules/maps-listings/inc/bricks-views/' . $relative_path );
	}

	public function register_elements() {

		\Bricks\Elements::register_element( $this->module_path( 'maps-listings.php' ) );

		do_action( 'jet-engine/bricks-views/register-elements' );

	}

	public function has_bricks() {
		return defined( 'BRICKS_VERSION' );
	}
}