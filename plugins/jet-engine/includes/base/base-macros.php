<?php
/**
 * Base class for custom macros registration
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Jet_Engine_Base_Macros
 */
abstract class Jet_Engine_Base_Macros {

	public $args = null;

	/**
	 * Register macros
	 */
	public function __construct() {
		add_filter( 'jet-engine/listings/macros-list', array( $this, 'register_macros' ) );
	}

	/**
	 * Returns macros tag
	 *
	 * @return string
	 */
	abstract public function macros_tag();

	/**
	 * Returns macros name
	 *
	 * @return string
	 */
	abstract public function macros_name();

	/**
	 * Callback function to return macros value
	 *
	 * @return string
	 */
	abstract public function macros_callback( $args = array() );

	/**
	 * Wrapper for callback function to explode arguments
	 *
	 * @param null $field_value
	 * @param null $raw_args
	 *
	 * @return string
	 */
	public function _macros_callback( $field_value = null, $raw_args = null ) {

		$custom_args = $this->get_macros_args();
		$args        = array();

		if ( ! empty( $custom_args ) ) {

			$raw_args = explode( '|', $raw_args );
			$i        = 0;

			foreach ( $custom_args as $key => $value ) {
				$default      = isset( $value['default'] ) ? $value['default'] : null;
				$args[ $key ] = isset( $raw_args[ $i ] ) ? $raw_args[ $i ] : $default;
				$i++;
			}

		}

		return call_user_func( array( $this, 'macros_callback' ), $args,  $field_value );
	}

	/**
	 * Optionally return custom macros attributes array
	 *
	 * @return array
	 */
	public function macros_args() {
		return array();
	}

	/**
	 * Returns registered macros arguments list
	 *
	 * @return array
	 */
	public function get_macros_args() {

		if ( null === $this->args ) {
			$this->args = $this->macros_args();
		}

		return $this->args;
	}

	/**
	 * Register macros callback
	 *
	 * @return array
	 */
	public function register_macros( $macros_list ) {

		$macros_data = array(
			'label' => $this->macros_name(),
			'cb'    => array( $this, '_macros_callback' ),
		);

		$args = $this->get_macros_args();

		if ( ! empty( $args ) ) {
			$macros_data['args'] = $args;
		}

		$macros_list[ $this->macros_tag() ] = $macros_data;

		return $macros_list;
	}

	/**
	 * Return current macros object
	 *
	 * @return object|null
	 */
	public function get_macros_object() {
		return jet_engine()->listings->macros->get_macros_object();
	}

}
