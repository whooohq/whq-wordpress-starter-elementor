<?php

/**
 * Class WPML_Jet_Elements_Table
 */
class WPML_Jet_Elements_Table extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'table_body';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'cell_text', 'cell_link' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'cell_text':
				return esc_html__( 'Jet Table: Cell Text', 'jet-elements' );

			case 'cell_link':
				return esc_html__( 'Jet Table: Cell Link', 'jet-elements' );

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
			case 'cell_text':
				return 'AREA';

			case 'cell_link':
				return 'LINK';

			default:
				return '';
		}
	}

}
