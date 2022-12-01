<?php
namespace Jet_Menu\Endpoints;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Posts class
 */
class Get_Elementor_Template_Content extends Base {

	/**
	 * [$depended_scripts description]
	 * @var array
	 */
	public $depended_scripts = [];

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'get-elementor-template-content';
	}

	/**
	 * Returns arguments config
	 *
	 * @return [type] [description]
	 */
	public function get_args() {

		return array(
			'id' => array(
				'default'    => '',
				'required'   => false,
			),
			'dev' => array(
				'default'    => 'false',
				'required'   => false,
			),
		);
	}

	public function callback( $request ) {

		$args = $request->get_params();

		$template_id = ! empty( $args['id'] ) ? $args['id'] : false;

		$dev = filter_var( $args['dev'], FILTER_VALIDATE_BOOLEAN ) ? true : false;

		if ( ! $template_id ) {
			return false;
		}

		$transient_key = md5( sprintf( 'jet_menu_elementor_template_data_%s', $template_id ) );

		$template_data = get_transient( $transient_key );

		$template_cache = jet_menu()->settings_manager->options_manager->get_option( 'use-template-cache', 'true' );
		$template_cache = filter_var( $template_cache, FILTER_VALIDATE_BOOLEAN ) ? true : false;

		if ( ! empty( $template_data ) && ! $dev && $template_cache ) {
			return rest_ensure_response( $template_data );
		}

		$render_instance = new \Jet_Menu\Render\Elementor_Template_Render( [
			'template_id' => $template_id,
			'with_css'    => true,
		] );

		$template_data = $render_instance->get_render_data();

		set_transient( $transient_key, $template_data, 12 * HOUR_IN_SECONDS );

		return rest_ensure_response( $template_data );
	}

}
