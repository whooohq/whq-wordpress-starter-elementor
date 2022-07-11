<?php


namespace Jet_Engine\Modules\Custom_Content_Types\Forms;


use Jet_Engine\Modules\Custom_Content_Types\Module;
use Jet_Form_Builder\Presets\Sources\Base_Source;

class Preset_Source_Cct extends Base_Source {

	public function query_source() {
		$key = explode( '::', $this->prop );

		if ( 2 !== count( $key ) || empty( $key[0] ) ) {
			return false;
		}

		$item = Module::instance()->form_preset->get_content_type_item( $key[0], $this->preset_data );

		if ( ! $item ) {
			return false;
		}

		return (object) $item;
	}

	public function default_prop( string $prop ) {
		$key  = explode( '::', $prop );
		$prop = isset( $key[1] ) ? $key[1] : '_ID';

		return parent::default_prop( $prop );
	}

	public function get_id() {
		return Module::instance()->form_preset->preset_source;
	}

	protected function can_get_preset() {
		if ( ! parent::can_get_preset() ) {
			return false;
		}
		$source = $this->src();

		if ( empty( $source->cct_author_id ) ) {
			return false;
		}

		if ( ! is_user_logged_in() ) {
			return false;
		}

		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		$author = absint( $source->cct_author_id );

		return $author === get_current_user_id();
	}
}