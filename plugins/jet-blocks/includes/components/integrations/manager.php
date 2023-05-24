<?php
namespace Jet_Blocks\Integrations;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Manager {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.3.5
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * [$integrations_modules description]
	 * @var null
	 */
	public $integration_modules = array();

	/**
	 * Constructor for the class
	 */
	function __construct() {

		$this->load_files();

		$this->integration_modules = apply_filters( 'jet-reviews/integrations/modules', array(
			'recaptcha' => array(
				'class'    => '\\Jet_Blocks\\Integrations\\ReCaptcha',
				'args'     => array(),
				'instance' => false,
			),
		) );

		$this->load_integration_modules();
	}

	/**
	 * [load_files description]
	 * @return [type] [description]
	 */
	public function load_files() {
		require jet_blocks()->plugin_path( 'includes/components/integrations/recaptcha.php' );
	}

	/**
	 * [maybe_load_theme_module description]
	 * @return [type] [description]
	 */
	public function load_integration_modules() {

		$this->integration_modules = array_map( function( $module_data ) {
			$class = $module_data['class'];

			if ( ! $module_data['instance'] && class_exists( $class ) ) {
				$module_data['instance'] = new $class( $module_data['args'] );
			}

			return $module_data;
		}, $this->integration_modules );

	}

	/**
	 * [get_integration_module description]
	 * @param  boolean $slug [description]
	 * @return [type]        [description]
	 */
	public function get_integration_module( $slug = false ) {

		if ( isset( $this->integration_modules[ $slug ] ) ) {
			return $this->integration_modules[ $slug ];
		}

		return false;
	}

	/**
	 * [get_integration_module_instance description]
	 * @param  boolean $slug [description]
	 * @return [type]        [description]
	 */
	public function get_integration_module_instance( $slug = false ) {

		$integration_module = $this->get_integration_module( $slug );

		if ( ! $integration_module ) {
			return false;
		}

		if ( ! $integration_module['instance'] ) {
			return false;
		}

		return $integration_module['instance'];
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.3.5
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}
