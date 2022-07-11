<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Menu_Item_Document extends Elementor\Core\Base\Document {

	public function get_name() {
		return 'jet-menu';
	}

	public static function get_title() {
		return __( 'Jet Menu', 'jet-engine' );
	}

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = '';
		$properties['support_kit']     = true;
		$properties['cpt']             = [ 'jet-menu' ];

		return $properties;
	}

	/**
	 *
	 */
	protected function register_controls() {
		parent::register_controls();
	}

}
