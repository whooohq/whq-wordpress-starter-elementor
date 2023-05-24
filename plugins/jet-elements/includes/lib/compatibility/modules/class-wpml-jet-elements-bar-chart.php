<?php

/**
 * Class WPML_Jet_Elements_Bar_Chart
 */
class WPML_Jet_Elements_Bar_Chart extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'chart_data';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'label' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'label':
				return esc_html__( 'Jet Bar Chart: Item Label', 'jet-elements' );

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
			case 'label':
				return 'LINE';

			default:
				return '';
		}
	}

}
