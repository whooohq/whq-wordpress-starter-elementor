<?php
/**
 * Delete tax endpoint
 */

class Jet_Engine_CPT_Rest_Delete_Taxonomy extends Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'delete-taxonomy';
	}

	/**
	 * API callback
	 *
	 * @return void|WP_Error|WP_REST_Response
	 */
	public function callback( $request ) {

		$params = $request->get_params();

		$action = $params['action'];
		$id     = $params['id'];

		if ( ! $id ) {

			jet_engine()->taxonomies->add_notice(
				'error',
				__( 'Item ID not found in request', 'jet-engine' )
			);

			return rest_ensure_response( array(
				'success' => false,
				'notices' => jet_engine()->taxonomies->get_notices(),
			) );

		}

		$tax_data = jet_engine()->taxonomies->data->get_item_for_edit( $id );

		if ( ! $tax_data || ! isset( $tax_data['general_settings']['slug'] ) ) {

			jet_engine()->taxonomies->add_notice(
				'error',
				__( 'Item data not found', 'jet-engine' )
			);

			return rest_ensure_response( array(
				'success' => false,
				'notices' => jet_engine()->taxonomies->get_notices(),
			) );

		}

		$from_tax = $tax_data['general_settings']['slug'];

		if ( 'delete' === $action ) {
			$this->delete_terms( $from_tax );
		}

		$this->remove_tax_from_meta_boxes_comp( $from_tax );

		jet_engine()->taxonomies->data->set_request( array( 'id' => $id ) );

		if ( jet_engine()->taxonomies->data->delete_item( false ) ) {
			return rest_ensure_response( array(
				'success' => true,
			) );
		} else {
			return rest_ensure_response( array(
				'success' => false,
				'notices' => jet_engine()->taxonomies->get_notices(),
			) );
		}

	}

	/**
	 * Delete posts
	 *
	 * @return [type] [description]
	 */
	public function delete_terms( $from_tax ) {

		$terms = get_terms( array(
			'taxonomy'   => $from_tax,
			'hide_empty' => false,
			'fields'     => 'ids',
		) );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		foreach ( $terms as $term_id ) {
			wp_delete_term( $term_id, $from_tax );
		}

	}

	/**
	 * Remove tax from `allowed_tax` param in meta boxes component
	 *
	 * @param $deleted_tax
	 */
	public function remove_tax_from_meta_boxes_comp( $deleted_tax ) {

		$meta_boxes = jet_engine()->meta_boxes->data->get_raw();

		if ( empty( $meta_boxes ) ) {
			return;
		}

		foreach ( $meta_boxes as $meta_box ) {
			$args        = $meta_box['args'];
			$object_type = isset( $args['object_type'] ) ? esc_attr( $args['object_type'] ) : 'post';

			if ( ! in_array( $object_type, array( 'tax', 'taxonomy' ) ) ) {
				continue;
			}

			$allowed_tax = ! empty( $args['allowed_tax'] ) ? $args['allowed_tax'] : array();

			if ( ! in_array( $deleted_tax, $allowed_tax ) ) {
				continue;
			}

			$allowed_tax = array_combine( $allowed_tax, $allowed_tax );
			unset( $allowed_tax[ $deleted_tax ] );

			$meta_box['args']['allowed_tax'] = array_values( $allowed_tax );

			jet_engine()->meta_boxes->data->update_item_in_db( $meta_box );
		}

	}

	/**
	 * Returns endpoint request method - GET/POST/PUT/DELETE
	 *
	 * @return string
	 */
	public function get_method() {
		return 'DELETE';
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

	/**
	 * Returns arguments config
	 *
	 * @return array
	 */
	public function get_args() {
		return array(
			'action' => array(
				'default'  => 'none',
				'required' => true,
			),
		);
	}

}