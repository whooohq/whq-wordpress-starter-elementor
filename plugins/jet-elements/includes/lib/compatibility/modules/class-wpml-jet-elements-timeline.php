<?php

/**
 * Class WPML_Jet_Elements_Timeline
 */
class WPML_Jet_Elements_Timeline extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'cards_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'item_title', 'item_meta', 'item_desc', 'item_point_text', 'item_btn_text', 'item_btn_url' => array( 'url' ) );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_title':
				return esc_html__( 'Jet Vertical Timeline: Item Title', 'jet-elements' );

			case 'item_meta':
				return esc_html__( 'Jet Vertical Timeline: Item Meta', 'jet-elements' );

			case 'item_desc':
				return esc_html__( 'Jet Vertical Timeline: Item Description', 'jet-elements' );

			case 'item_point_text':
				return esc_html__( 'Jet Vertical Timeline: Item Point Text', 'jet-elements' );

			case 'item_btn_text':
				return esc_html__( 'Jet Vertical Timeline: Item Button Text', 'jet-elements' );

			case 'url':
				return esc_html__( 'Jet Vertical Timeline: Item Button Link', 'jet-elements' );

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
			case 'item_title':
			case 'item_meta':
			case 'item_point_text':
			case 'item_btn_text':
				return 'LINE';

			case 'item_desc':
				return 'AREA';

			case 'url':
				return 'LINK';

			default:
				return '';
		}
	}

}
