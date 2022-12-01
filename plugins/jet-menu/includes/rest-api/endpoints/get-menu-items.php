<?php
namespace Jet_Menu\Endpoints;


class Get_Menu_Items extends Base {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'get-menu-items';
	}

	/**
	 * Returns arguments config
	 *
	 * @return [type] [description]
	 */
	public function get_args() {

		return array(
			'menu_id' => array(
				'default'    => '',
				'required'   => false,
			),
			'dev' => array(
				'default'    => 'false',
				'required'   => false,
			),
			'lang' => array(
				'default'    => '',
				'required'   => false,
			),
		);
	}

	/**
	 * [callback description]
	 * @param  [type]   $request [description]
	 * @return function          [description]
	 */
	public function callback( $request ) {

		$args = $request->get_params();

		$menu_id = ! empty( $args['menu_id'] ) ? $args['menu_id'] : false;

		$dev = filter_var( $args['dev'], FILTER_VALIDATE_BOOLEAN ) ? true : false;

		$transient_key = md5( sprintf( 'jet_menu_remote_items_data_%s', $menu_id ) );

		$items_data = get_transient( $transient_key );

		if ( ! empty( $items_data ) && ! $dev ) {
			return rest_ensure_response( $items_data );
		}

		add_filter( 'wpml_ls_enable_ajax_navigation', '__return_true' );

		$menu_data = jet_menu()->render_manager->generate_menu_raw_data( $menu_id );

		$items_data = array(
			'data' => $menu_data,
		);

		set_transient( $transient_key, $items_data, 24 * HOUR_IN_SECONDS );

		return rest_ensure_response( $items_data );
	}

}
