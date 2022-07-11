<?php
namespace Jet_Engine\Query_Builder\Queries\Traits;

trait Meta_Query_Trait {

	/**
	 * Prepare Meta Query arguments by initial arguments list
	 *
	 * @param  array  $args [description]
	 * @return [type]       [description]
	 */
	public function prepare_meta_query_args( $args = array() ) {

		$raw        = $args['meta_query'];
		$meta_query = array();

		if ( ! empty( $args['meta_query_relation'] ) ) {
			$meta_query['relation'] = $args['meta_query_relation'];
		}

		foreach ( $raw as $query_row ) {

			if ( ! empty( $query_row['type'] ) && 'TIMESTAMP' === $query_row['type'] ) {
				$query_row['type']  = 'NUMERIC';
				$query_row['value'] = \Jet_Engine_Tools::is_valid_timestamp( $query_row['value'] ) ? $query_row['value'] : strtotime( $query_row['value'] );
			}

			if ( ! empty( $query_row['clause_name'] ) ) {
				$meta_query[ $query_row['clause_name'] ] = $query_row;
			} else {
				$meta_query[] = $query_row;
			}

		}

		return $meta_query;

	}

	/**
	 * Replace filtered arguments in the final query array
	 *
	 * @param  array  $rows [description]
	 * @return [type]       [description]
	 */
	public function replace_meta_query_row( $rows = array() ) {

		$replaced_rows = array();

		if ( ! empty( $this->final_query['meta_query'] ) ) {

			foreach ( $this->final_query['meta_query'] as $index => $existing_row ) {
				foreach ( $rows as $row_index => $row ) {
					if ( isset( $row['key'] ) && $existing_row['key'] === $row['key'] ) {
						$this->final_query['meta_query'][ $index ] = $row;
						$replaced_rows[] = $row_index;
						return;
					}
				}
			}

		} else {
			$this->final_query['meta_query'] = array();
		}

		foreach ( $rows as $row_index => $row ) {
			if ( ! in_array( $row_index, $replaced_rows ) ) {
				$this->final_query['meta_query'][] = $row;
			}
		}

	}

}
