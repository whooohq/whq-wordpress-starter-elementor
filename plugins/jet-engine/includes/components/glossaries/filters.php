<?php
namespace Jet_Engine\Glossaries;

/**
 * Meta fields compatibility class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Filters {

	public function __construct() {
		add_filter( 'jet-smart-filters/post-type/options-data-sources', array( $this, 'register_source' ) );
		add_filter( 'jet-smart-filters/post-type/meta-fields-settings', array( $this, 'register_controls' ) );
		add_filter( 'jet-smart-filters/filters/filter-options', array( $this, 'apply_glossary_options' ), 10, 3 );
		add_filter( 'jet-smart-filters/indexer/custom-args', array( $this, 'setup_indexer_agrs' ), 10, 3 );
	}

	public function setup_indexer_agrs( $args = array(), $filter_id = 0, $indexer_args = array() ) {

		$source = get_post_meta( $filter_id, '_data_source', true );

		if ( 'glossary' !== $source ) {
			return $args;
		}

		$args['query_type'] = 'meta_query';
		$args['query_var']  = get_post_meta( $filter_id, '_query_var', true );
		$glossary_id        = get_post_meta( $filter_id, '_glossary_id', true );

		if ( ! $glossary_id ) {
			return $args;
		}

		$options = $this->get_glossary_options( $glossary_id, array() );
		$prepared_options = array();

		foreach ( $options as $key => $value ) {
			$prepared_options[ $key ] = $key;
		}

		$args['options'] = $prepared_options;

		return $args;

	}

	public function apply_glossary_options( $options, $filter_id, $filter ) {

		$source = get_post_meta( $filter_id, '_data_source', true );

		if ( 'glossary' !== $source ) {
			return $options;
		}

		$glossary_id = get_post_meta( $filter_id, '_glossary_id', true );

		if ( ! $glossary_id ) {
			return $options;
		}

		$new_options = $this->get_glossary_options( $glossary_id, $options );
		$type        = get_post_meta( $filter_id, '_filter_type', true );

		if ( 'select' === $type ) {

			$placeholder = get_post_meta( $filter_id, '_placeholder', true );

			if ( ! $placeholder ) {
				$placeholder = __( 'Select...', 'jet-engine' );
			}

			$new_options = array( '' => $placeholder ) + $new_options;

		}

		return $new_options;

	}

	public function get_glossary_options( $glossary_id = 0, $fallback = array() ) {

		$glossary = jet_engine()->glossaries->data->get_item_for_edit( absint( $glossary_id ) );

		if ( ! empty( $glossary ) && ! empty( $glossary['fields'] ) ) {

			$result = array();

			if ( ! empty( $fallback['all'] ) ) {
				$result['all'] = $fallback['all'];
			}

			$new_options = $glossary['fields'];
			$new_options = wp_list_pluck( $new_options, 'label', 'value' );
			$result      = $result + $new_options; // `array_merge` changed to `+` to prevent re-indexing of array for numeric glossary

			return $result;

		} else {
			return $fallback;
		}

	}

	public function register_controls( $fields ) {

		$glossaries = array(
			'' => __( 'Select glossary...', 'jet-engine' ),
		);

		foreach ( jet_engine()->glossaries->settings->get() as $glossary ) {
			$glossaries[ $glossary['id']] = $glossary['name'];
		}

		$insert = array(
			'_glossary_id' => array(
				'title'   => __( 'Select glossary', 'jet-engine' ),
				'type'    => 'select',
				'element' => 'control',
				'options' => $glossaries,
				'conditions' => array(
					'_filter_type' => array( 'checkboxes', 'select', 'radio' ),
					'_data_source' => 'glossary',
				),
			),
			'_glossary_notice' => array(
				'title'       => __( 'Coming soon', 'jet-engine' ),
				'type'        => 'text',
				'input_type'  => 'hidden',
				'element'     => 'control',
				'description' => __( 'Support for the Visual filter will be added with future updates', 'jet-engine' ),
				'class'       => 'cx-control',
				'conditions'  => array(
					'_data_source' => 'glossary',
					'_filter_type' => array( 'color-image' ),
				),
			),
		);

		return $this->insert_after( $fields, '_source_post_type', $insert );
	}

	public function register_source( $sources = array() ) {
		$sources['glossary'] = __( 'JetEngine Glossary', 'jet-engine' );
		return $sources;
	}

	public function insert_after( $source = array(), $after = null, $insert = array() ) {

		$keys   = array_keys( $source );
		$index  = array_search( $after, $keys );

		if ( ! $source ) {
			$source = array();
		}

		if ( false === $index ) {
			return $source + $insert;
		}

		$offset = $index + 1;

		return array_slice( $source, 0, $offset, true ) + $insert + array_slice( $source, $offset, null, true );

	}

}
