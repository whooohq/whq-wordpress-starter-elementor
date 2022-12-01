<?php
/**
 * Class Jet_Tabs_WPML_Tabs
 */
class Jet_Tabs_WPML_Tabs extends WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'tabs';
	}

	public function get_fields() {
		return array( 'item_label', 'item_editor_content' );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_label':
				return esc_html__( 'Jet Tabs: Item Label', 'jet-tabs' );

			case 'item_editor_content':
				return esc_html__( 'Jet Tabs: Item Editor Content', 'jet-tabs' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'item_label':
				return 'LINE';

			case 'item_editor_content':
				return 'VISUAL';

			default:
				return '';
		}
	}

}
