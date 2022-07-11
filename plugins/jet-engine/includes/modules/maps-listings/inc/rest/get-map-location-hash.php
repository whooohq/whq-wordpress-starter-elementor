<?php
namespace Jet_Engine\Modules\Maps_Listings;

/**
 * Get_Map_Location_Hash endpoint
 */
class Get_Map_Location_Hash extends \Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'get-map-location-hash';
	}

	/**
	 * API callback
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public function callback( $request ) {

		$params = $request->get_params();
		$loc    = $params['loc'];

		if ( ! $loc ) {
			return rest_ensure_response( array(
				'success' => false,
				'data'    => __( 'Required parameters is not found in request', 'jet-engine' ),
			) );
		}

		$result = array(
			'success' => true,
			'data'    => md5( $loc ),
		);

		return rest_ensure_response( $result );

	}

	/**
	 * Returns endpoint request method - GET/POST/PUT/DELTE
	 *
	 * @return string
	 */
	public function get_method() {
		return 'GET';
	}

	/**
	 * Check user access to current end-popint
	 *
	 * @return bool
	 */
	public function permission_callback( $request ) {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Returns arguments config
	 *
	 * @return array
	 */
	public function get_args() {
		return array(
			'loc' => array(
				'default'  => '',
				'required' => true,
			),
		);
	}

}