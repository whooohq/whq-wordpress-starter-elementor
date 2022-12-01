<?php
/**
 * Class Jet_Tabs_WPML_Image_Accordion
 */
class Jet_Tabs_WPML_Image_Accordion extends WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'item_list';
	}

	public function get_fields() {
		return array( 'item_title', 'item_desc', 'item_link_text', 'item_link' );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_title':
				return esc_html__( 'Jet Image Accordion: Item Label', 'jet-tabs' );

			case 'item_desc':
				return esc_html__( 'Jet Image Accordion: Item Description', 'jet-tabs' );

			case 'item_link_text':
				return esc_html__( 'Jet Image Accordion: Item Button text', 'jet-tabs' );

			case 'item_link':
				return esc_html__( 'Jet Image Accordion: Item Link', 'jet-tabs' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'item_title':
				return 'LINE';

			case 'item_desc':
				return 'AREA';

			case 'item_link_text':
				return 'LINE';

			case 'item_link':
				return 'LINK';

			default:
				return '';
		}
	}

}
