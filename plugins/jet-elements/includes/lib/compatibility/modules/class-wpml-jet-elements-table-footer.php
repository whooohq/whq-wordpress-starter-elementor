<?php

/**
 * Class WPML_Jet_Elements_Table_Footer
 */
class WPML_Jet_Elements_Table_Footer extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'table_footer';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'cell_text' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'cell_text':
				return esc_html__( 'Jet Table Footer: Column Text', 'jet-elements' );

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
				return 'LINE';

			default:
				return '';
		}
	}

}
