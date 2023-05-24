<?php
/**
 * Bricks views manager
 */
namespace Jet_Engine\Bricks_Views;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Manager class
 */
class Manager {

	/**
	 * Frontend instance
	 *
	 * @var null
	 */
	public $frontend = null;

	/**
	 * Listing manager instance
	 * 
	 * @var null
	 */
	public $listing = null;

	/**
	 * Constructor for the class
	 */
	function __construct() {

		if ( ! $this->has_bricks() ) {
			return;
		}

		add_action( 'init', array( $this, 'register_elements' ), 10 );
		add_action( 'init', array( $this, 'init_listings' ), 10 );
		add_action( 'init', array( $this, 'integrate_in_bricks_loop' ), 10 );

		add_filter( 'bricks/builder/i18n', function( $i18n ) {
			$i18n['jetengine'] = esc_html__( 'JetEngine', 'jet-engine' );

			return $i18n;
		} );

		// Add JetEngine icons font
		add_action( 'wp_enqueue_scripts', function() {
			// Enqueue your files on the canvas & frontend, not the builder panel. Otherwise custom CSS might affect builder)
			if ( bricks_is_builder() ) {
				wp_enqueue_style(
					'jet-engine-icons',
					jet_engine()->plugin_url( 'assets/lib/jetengine-icons/icons.css' ),
					array(),
					jet_engine()->get_version()
				);
			}
		} );

		$this->compat_tweaks();

	}

	public function init_listings() {
		require $this->component_path( 'listing/manager.php' );
		$this->listing = new Listing\Manager();
	}

	public function integrate_in_bricks_loop() {
		require $this->component_path( 'bricks-loop/manager.php' );
		new Bricks_Loop\Manager();
	}

	public function component_path( $relative_path = '' ) {
		return jet_engine()->plugin_path( 'includes/components/bricks-views/' . $relative_path );
	}

	public function register_elements() {

		require $this->component_path( 'elements/base.php' );
		require $this->component_path( 'helpers/options-converter.php' );
		require $this->component_path( 'helpers/controls-converter/base.php' );
		require $this->component_path( 'helpers/controls-converter/control-text.php' );
		require $this->component_path( 'helpers/controls-converter/control-select.php' );
		require $this->component_path( 'helpers/controls-converter/control-repeater.php' );
		require $this->component_path( 'helpers/controls-converter/control-checkbox.php' );
		require $this->component_path( 'helpers/controls-converter/control-default.php' );
		require $this->component_path( 'helpers/controls-converter/control-icon.php' );
		require $this->component_path( 'helpers/preview.php' );
		require $this->component_path( 'helpers/repeater.php' );
		require $this->component_path( 'helpers/controls-hook-bridge.php' );
		
		$element_files = array(
			$this->component_path( 'elements/listing-grid.php' ),
			$this->component_path( 'elements/dynamic-field.php' ),
			$this->component_path( 'elements/dynamic-image.php' ),
			$this->component_path( 'elements/dynamic-link.php' ),
		);

		foreach ( $element_files as $file ) {
			\Bricks\Elements::register_element( $file );
		}

		do_action( 'jet-engine/bricks-views/register-elements' );

	}

	public function has_bricks() {
		return defined( 'BRICKS_VERSION' );
	}

	/**
	 * Check if is Bricks editor render request
	 * 
	 * @return boolean [description]
	 */
	public function is_bricks_editor() {

		// is API request
		$bricks_request_str = 'wp-json/bricks/v1/render_element';
		$is_api = ( ! empty( $_SERVER['REQUEST_URI'] ) && false !== strpos( $_SERVER['REQUEST_URI'], $bricks_request_str ) );

		// is AJAX request
		$is_ajax = ( ! empty( $_REQUEST['action'] ) && 'bricks_render_element' === $_REQUEST['action'] );

		// Is editor iframe
		$is_editor = ( ! empty( $_REQUEST['bricks'] ) && 'run' === $_REQUEST['bricks'] );

		return $is_api || $is_ajax || $is_editor;
	}

	public function is_bricks_listing( $listing_id ) {
		return jet_engine()->listings->data->get_listing_type( $listing_id ) === $this->listing->get_slug();
	}

	public function compat_tweaks() {

		// fix slider arrows bug for the listing grid
		add_filter( 'jet-engine/listing/grid/slider-options', function( $options ) {
			
			if ( ! empty( $_REQUEST['action'] ) && 'bricks_get_element_html' === $_REQUEST['action'] ) {
				$options['prevArrow'] = wp_slash( $options['prevArrow'] );
				$options['nextArrow'] = wp_slash( $options['nextArrow'] );
			}

			return $options;
		} );

	}
}
