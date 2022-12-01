<?php
namespace Jet_Smart_Filters\Endpoints;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Posts class
 */
class Plugin_Settings extends Base {
	/**
	 * Returns route name
	 */
	public function get_name() {

		return 'plugin-settings';
	}

	public function callback( $request ) {

		$data = $request->get_params();

		$current = get_option( jet_smart_filters()->settings->key, array() );

		if ( is_wp_error( $current ) ) {
			return rest_ensure_response( [
				'status'  => 'error',
				'message' => __( 'Server Error', 'jet-smart-filters' ),
			] );
		}

		foreach ( $data as $key => $value ) {
			$current[ $key ] = is_array( $value ) ? $value : esc_attr( $value );
		}

		update_option( jet_smart_filters()->settings->key, $current );

		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Settings have been saved', 'jet-smart-filters' ),
		] );
	}
}
