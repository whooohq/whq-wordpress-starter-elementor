<?php
/**
 * Update options page endpoint
 */

class Jet_Engine_Rest_Edit_Options_Page extends Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'edit-options-page';
	}

	public function safe_get( $args = array(), $group = '', $key = '', $default = false ) {
		return isset( $args[ $group ][ $key ] ) ? $args[ $group ][ $key ] : $default;
	}

	/**
	 * API callback
	 *
	 * @return void
	 */
	public function callback( $request ) {

		$params = $request->get_params();

		if ( empty( $params['id'] ) ) {

			jet_engine()->options_pages->add_notice(
				'error',
				__( 'Item ID not found in request', 'jet-engine' )
			);

			return rest_ensure_response( array(
				'success' => false,
				'notices' => jet_engine()->options_pages->get_notices(),
			) );

		}

		jet_engine()->options_pages->data->set_request( array(
			'id'         => $params['id'],
			'name'       => $this->safe_get( $params, 'general_settings', 'name' ),
			'slug'       => $this->safe_get( $params, 'general_settings', 'slug' ),
			'menu_name'  => $this->safe_get( $params, 'general_settings', 'menu_name' ),
			'parent'     => $this->safe_get( $params, 'general_settings', 'parent' ),
			'icon'       => $this->safe_get( $params, 'general_settings', 'icon' ),
			'capability' => $this->safe_get( $params, 'general_settings', 'capability' ),
			'position'   => $this->safe_get( $params, 'general_settings', 'position' ),
			'fields'     => ! empty( $params['fields'] ) ? $params['fields'] : array(),
		) );

		$updated = jet_engine()->options_pages->data->edit_item( false );

		return rest_ensure_response( array(
			'success' => $updated,
			'notices' => jet_engine()->options_pages->get_notices(),
		) );

	}

	/**
	 * Returns endpoint request method - GET/POST/PUT/DELTE
	 *
	 * @return string
	 */
	public function get_method() {
		return 'POST';
	}

	/**
	 * Check user access to current end-popint
	 *
	 * @return bool
	 */
	public function permission_callback( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get query param. Regex with query parameters
	 *
	 * @return string
	 */
	public function get_query_params() {
		return '(?P<id>[\d]+)';
	}

}
