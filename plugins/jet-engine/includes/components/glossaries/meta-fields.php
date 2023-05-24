<?php
namespace Jet_Engine\Glossaries;

/**
 * Meta fields compatibility class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Meta_Fields {

	public function __construct() {
		add_filter( 'jet-engine/meta-fields/config', array( $this, 'add_glossaries_to_fields_config' ) );
		add_filter( 'jet-engine/meta-fields/field-options', array( $this, 'apply_glossary' ), 10, 2 );

		add_action( 'jet-engine/meta-boxes/save-custom-value', array( $this, 'add_custom_values_to_glossary' ), 10, 2 );
	}

	public function is_glossary_field( $field = array() ) {

		if ( empty( $field['options_from_glossary'] ) || empty( $field['glossary_id'] ) ) {
			return false;
		}

		return true;

	}

	public function add_custom_values_to_glossary( $field = null, $field_data = array() ) {

		if ( empty( $field_data['options_from_glossary'] ) || empty( $field_data['glossary_id'] ) ) {
			return;
		}

		$glossary = jet_engine()->glossaries->data->get_item_for_edit( absint( $field_data['glossary_id'] ) );

		if ( ! $glossary ) {
			return;
		}

		$new_fields = ! empty( $_REQUEST[ $field ] ) ? $_REQUEST[ $field ] : array();
		$fields     = $glossary['fields'];
		$existing   = array();

		if ( ! is_array( $new_fields ) ) {
			$new_fields = array( $new_fields );
		}

		if ( ( in_array( 'true', $new_fields ) || in_array( 'false', $new_fields ) ) && empty( $new_fields[0] ) ) {
			$new_fields = array_keys( $new_fields );
		}

		foreach ( $fields as $gl_field ) {
			$existing[] = $gl_field['value'];
		}

		$to_add = array_diff( $new_fields, $existing );

		if ( empty( $to_add ) ) {
			return;
		}

		foreach ( $to_add as $value ) {
			$fields[] = array(
				'value' => $value,
				'label' => $value,
			);
		}

		$new_item = array(
			'id' => absint( $field_data['glossary_id'] ),
			'name' => $glossary['name'],
			'fields' => $fields,
		);

		jet_engine()->glossaries->data->set_request( $new_item );
		jet_engine()->glossaries->data->edit_item( false );

	}

	public function get_glossary_for_field( $glossary_id ) {

		$item = jet_engine()->glossaries->data->get_item_for_edit( $glossary_id );

		if ( empty( $item ) || empty( $item['fields'] ) ) {
			return array();
		}

		return $item['fields'];

	}

	public function add_glossaries_to_fields_config( $config ) {

		$items = array_merge(
			array( array( 'id' => '', 'name' => __( 'Select glossary', 'jet-engine' ) ) ),
			jet_engine()->glossaries->settings->get()
		);

		$config['glossaries'] = \Jet_Engine_Tools::prepare_list_for_js( $items, 'id', 'name' );
		$config['api_path_search_glossary_fields'] = jet_engine()->api->get_route( 'search-glossary-fields' );

		return $config;

	}

	public function format_list( $list = array() ) {

		$result = array();

		foreach ( $list as $item ) {
			$result[] = array(
				'key'        => isset( $item['value'] ) ? $item['value'] : '',
				'value'      => isset( $item['label'] ) ? $item['label'] : '',
				'is_checked' => isset( $item['is_checked'] ) ? filter_var( $item['is_checked'], FILTER_VALIDATE_BOOLEAN ) : false,
			);
		}

		return $result;
	}

	public function apply_glossary( $options = array(), $field = array() ) {

		if ( ! $this->is_glossary_field( $field ) ) {
			return $options;
		}

		return $this->format_list( $this->get_glossary_for_field( $field['glossary_id'] ) );

	}

}
