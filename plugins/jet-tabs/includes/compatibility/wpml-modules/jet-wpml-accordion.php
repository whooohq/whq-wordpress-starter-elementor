<?php
/**
 * Class Jet_Tabs_WPML_Accordion
 */
class Jet_Tabs_WPML_Accordion extends WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'toggles';
	}

	public function get_fields() {
		return array( 'item_label', 'item_editor_content' );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_label':
				return esc_html__( 'Jet Accordion: Item Label', 'jet-tabs' );

			case 'item_editor_content':
				return esc_html__( 'Jet Accordion: Item Editor Content', 'jet-tabs' );

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
