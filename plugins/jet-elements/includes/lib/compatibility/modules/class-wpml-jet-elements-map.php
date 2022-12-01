<?php

/**
 * Class WPML_Jet_Elements_Map
 */
class WPML_Jet_Elements_Map extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'pins';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'pin_address', 'pin_desc', 'pin_link_title', 'pin_link' => array( 'url' ) );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'pin_address':
				return esc_html__( 'Jet Map: Pin Address', 'jet-elements' );

			case 'pin_desc':
				return esc_html__( 'Jet Map: Pin Description', 'jet-elements' );

			case 'pin_link_title':
				return esc_html__( 'Jet Map: Link Text', 'jet-elements' );

			case 'url':
				return esc_html__( 'Jet Map: Link', 'jet-elements' );

			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'pin_address':
				return 'LINE';

			case 'pin_desc':
				return 'AREA';

			case 'pin_link_title':
				return 'LINE';

			case 'url':
				return 'LINK';

			default:
				return '';
		}
	}

}
