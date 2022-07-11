<?php
/**
 * Delete post type endpoint
 */

class Jet_Engine_CPT_Rest_Delete_Post_Type extends Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'delete-post-type';
	}

	/**
	 * API callback
	 *
	 * @return @return void|WP_Error|WP_REST_Response
	 */
	public function callback( $request ) {

		$params = $request->get_params();

		$action       = $params['action'];
		$id           = $params['id'];
		$to_post_type = $params['to_post_type'];

		if ( ! $id ) {

			jet_engine()->cpt->add_notice(
				'error',
				__( 'Item ID not found in request', 'jet-engine' )
			);

			return rest_ensure_response( array(
				'success' => false,
				'notices' => jet_engine()->cpt->get_notices(),
			) );

		}

		$post_type_data = jet_engine()->cpt->data->get_item_for_edit( $id );

		if ( ! $post_type_data || ! isset( $post_type_data['general_settings']['slug'] ) ) {

			jet_engine()->cpt->add_notice(
				'error',
				__( 'Item data not found', 'jet-engine' )
			);

			return rest_ensure_response( array(
				'success' => false,
				'notices' => jet_engine()->cpt->get_notices(),
			) );

		}

		$from_post_type = $post_type_data['general_settings']['slug'];

		switch ( $action ) {

			case 'reattach':
				$this->reattach_posts( $from_post_type, $to_post_type );
				break;

			case 'delete':
				$this->delete_posts( $from_post_type );
				break;

		}

		$this->remove_post_type_from_meta_boxes_comp( $from_post_type );

		jet_engine()->cpt->data->set_request( array( 'id' => $id ) );

		if ( jet_engine()->cpt->data->delete_item( false ) ) {
			return rest_ensure_response( array(
				'success' => true,
			) );
		} else {
			return rest_ensure_response( array(
				'success' => false,
				'notices' => jet_engine()->cpt->get_notices(),
			) );
		}

	}

	/**
	 * Reattach
	 * @param  [type] $to_post_type [description]
	 * @return [type]               [description]
	 */
	public function reattach_posts( $from_post_type, $to_post_type ) {

		$posts = get_posts( array(
			'post_type'      => $from_post_type,
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids'
		) );

		if ( empty( $posts ) || is_wp_error( $posts ) ) {
			return;
		}

		foreach ( $posts as $post_id ) {
			wp_update_post( array(
				'ID'        => $post_id,
				'post_type' => $to_post_type,
			) );
		}

	}

	/**
	 * Delete posts
	 *
	 * @return [type] [description]
	 */
	public function delete_posts( $from_post_type ) {

		$posts = get_posts( array(
			'post_type'      => $from_post_type,
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids'
		) );

		if ( empty( $posts ) || is_wp_error( $posts ) ) {
			return;
		}

		foreach ( $posts as $post_id ) {
			wp_delete_post( $post_id, true );
		}

	}

	/**
	 * Remove post type from `allowed_post_type` param in meta boxes component
	 *
	 * @param $deleted_post_type
	 */
	public function remove_post_type_from_meta_boxes_comp( $deleted_post_type ) {

		$meta_boxes = jet_engine()->meta_boxes->data->get_raw();

		if ( empty( $meta_boxes ) ) {
			return;
		}

		foreach ( $meta_boxes as $meta_box ) {
			$args        = $meta_box['args'];
			$object_type = isset( $args['object_type'] ) ? esc_attr( $args['object_type'] ) : 'post';

			if ( 'post' !== $object_type ) {
				continue;
			}

			$post_types = ! empty( $args['allowed_post_type'] ) ? $args['allowed_post_type'] : array();

			if ( ! in_array( $deleted_post_type, $post_types ) ) {
				continue;
			}

			$post_types = array_combine( $post_types, $post_types );
			unset( $post_types[ $deleted_post_type ] );

			$meta_box['args']['allowed_post_type'] = array_values( $post_types );

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
			'to_post_type' => array(
				'default'  => '',
				'required' => false,
			),
		);
	}

}