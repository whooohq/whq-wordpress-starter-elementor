<?php


namespace Jet_Engine\Modules\Forms\Tabs;


abstract class Base_Form_Tab {

	public $prefix = 'jet_engine_form_settings__';

	abstract public function slug();

	abstract public function on_get_request();

	abstract public function on_load();

	public function get_options( $default = array() ) {
		$response = get_option( $this->prefix . $this->slug(), false );

		$response = $response
			? json_decode( $response, true )
			: array();

		return array_merge( $default, $response );
	}

	public function update_options( $options ) {
		$options = json_encode( $options );

		return update_option( $this->prefix . $this->slug(), $options );
	}

	public function render_assets() {
	}

}