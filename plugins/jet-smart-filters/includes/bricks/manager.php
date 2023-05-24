<?php
/**
 * Bricks views manager
 */
namespace Jet_Smart_Filters\Bricks_Views;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Manager class
 */
class Manager {

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
		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_styles_for_builder'] );
		add_action( 'jet-smart-filters/render/ajax/before', [ $this, 'register_bricks_dynamic_data_on_ajax' ] );

		add_filter( 'bricks/builder/i18n', function( $i18n ) {
			$i18n['jetsmartfilters'] = esc_html__( 'JetSmartFilters', 'jet-smart-filters' );
			return $i18n;
		} );

		require jet_smart_filters()->plugin_path( 'includes/bricks/filters/manager.php' );
		new Filters\Manager();

	}

	public function register_bricks_dynamic_data_on_ajax() {

		if ( ! function_exists( 'jet_engine' ) ) {
			// Backup if JetEngine is not installed
			global $wp_filter;
			if ( isset( $wp_filter['wp'][8] ) ) {
				foreach( $wp_filter['wp'][8] as $callback ) {
					if ( is_array( $callback['function'] ) && is_object( $callback['function'][0] ) ) {
						if ( 'Bricks\Integrations\Dynamic_Data\Providers' === get_class( $callback['function'][0] ) ) {
							call_user_func( $callback['function'] );
							break;
						}
					}
				}
			}
		}
	}

	public function enqueue_styles_for_builder() {

		if ( bricks_is_builder() ) {

			jet_smart_filters()->set_filters_used();

			// Add JetSmartFilters icons font
			wp_enqueue_style(
				'jet-smart-filters-icons-font',
				jet_smart_filters()->plugin_url( 'assets/css/lib/jet-smart-filters-icons/jet-smart-filters-icons.css' ),
				array(),
				jet_smart_filters()->get_version()
			);

			jet_smart_filters()->filter_types->filter_styles();
		}
	}

	public function component_path( $relative_path = '' ) {
		return jet_smart_filters()->plugin_path( 'includes/bricks/' . $relative_path );
	}

	public function register_elements() {

		if ( ! class_exists( '\Jet_Engine\Bricks_Views\Elements\Base' ) ) {
			require $this->component_path( 'compatibility/elements/base.php' );
			require $this->component_path( 'compatibility/helpers/options-converter.php' );
			require $this->component_path( 'compatibility/helpers/controls-converter/base.php' );
			require $this->component_path( 'compatibility/helpers/controls-converter/control-text.php' );
			require $this->component_path( 'compatibility/helpers/controls-converter/control-select.php' );
			require $this->component_path( 'compatibility/helpers/controls-converter/control-repeater.php' );
			require $this->component_path( 'compatibility/helpers/controls-converter/control-checkbox.php' );
			require $this->component_path( 'compatibility/helpers/controls-converter/control-default.php' );
			require $this->component_path( 'compatibility/helpers/controls-converter/control-icon.php' );
			require $this->component_path( 'compatibility/helpers/preview.php' );
			require $this->component_path( 'compatibility/helpers/repeater.php' );
		}

		require $this->component_path( 'elements/base.php' );
		require $this->component_path( 'elements/base-checkbox.php' );

		$element_files = array(
			$this->component_path( 'elements/active-filters.php' ),
			$this->component_path( 'elements/active-tags.php' ),
			$this->component_path( 'elements/alphabet.php' ),
			$this->component_path( 'elements/apply-button.php' ),
			$this->component_path( 'elements/check-range.php' ),
			$this->component_path( 'elements/checkboxes.php' ),
			$this->component_path( 'elements/color-image.php' ),
			$this->component_path( 'elements/date-period.php' ),
			$this->component_path( 'elements/date-range.php' ),
			$this->component_path( 'elements/pagination.php' ),
			$this->component_path( 'elements/radio.php' ),
			$this->component_path( 'elements/range.php' ),
			$this->component_path( 'elements/rating.php' ),
			$this->component_path( 'elements/remove-filters.php' ),
			$this->component_path( 'elements/select.php' ),
			$this->component_path( 'elements/search.php' ),
			$this->component_path( 'elements/sorting.php' ),
		);

		foreach ( $element_files as $file ) {
			\Bricks\Elements::register_element( $file );
		}

	}

	public static function get_allowed_providers() {

		$provider_allowed = [
			'bricks-query-loop'   => true,
		];

		if ( function_exists( 'jet_engine' ) ) {
			$provider_allowed = array_merge(
				$provider_allowed,
				[
					'jet-engine'          => true,
					'jet-engine-maps'     => jet_engine()->modules->is_module_active('maps-listings'),
					'jet-engine-calendar' => jet_engine()->modules->is_module_active('calendar'),
				]
			);
		}

		return apply_filters( 'jet-smart-filters/bricks/allowed-providers', $provider_allowed );
	}

	public function has_bricks() {
		return defined( 'BRICKS_VERSION' );
	}

}