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

		// Added to remove slash from regex meta-query
		$rows = wp_unslash( $rows );

		$replaced_rows = array();

		if ( ! empty( $this->final_query['meta_query'] ) ) {

			foreach ( $this->final_query['meta_query'] as $index => $existing_row ) {
				foreach ( $rows as $row_index => $row ) {
					if ( isset( $row['key'] ) && $existing_row['key'] === $row['key'] ) {
						$this->final_query['meta_query'][ $index ] = $row;
						$replaced_rows[] = $row_index;
						break;
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

	public function get_dates_range_meta_query( $args = array(), $dates_range = array(), $settings = array() ) {
		
		if ( $settings['group_by_key'] ) {
			$meta_key = esc_attr( $settings['group_by_key'] );
		}

		if ( isset( $args['meta_query'] ) ) {
			$meta_query = $args['meta_query'];
		} else {
			$meta_query = array();
		}

		$calendar_meta_query = array();

		if ( $meta_key ) {

			$calendar_meta_query = array_merge( $calendar_meta_query, array(
				array(
					'key'     => $meta_key,
					'value'   => array( $dates_range['start'], $dates_range['end'] ),
					'compare' => 'BETWEEN',
				),
			) );

		}

		if ( ! empty( $settings['allow_multiday'] ) && ! empty( $settings['end_date_key'] ) ) {

			$calendar_meta_query = array_merge( $calendar_meta_query, array(
				array(
					'key'     => esc_attr( $settings['end_date_key'] ),
					'value'   => array( $dates_range['start'], $dates_range['end'] ),
					'compare' => 'BETWEEN',
				),
				array(
					'relation' => 'AND',
					array(
						'key'     => $meta_key,
						'value'   => $dates_range['start'],
						'compare' => '<'
					),
					array(
						'key'     => esc_attr( $settings['end_date_key'] ),
						'value'   => $dates_range['end'],
						'compare' => '>'
					)
				),
			) );

			$calendar_meta_query['relation'] = 'OR';

		}

		$meta_query[] = $calendar_meta_query;

		return $meta_query;

	}

}
