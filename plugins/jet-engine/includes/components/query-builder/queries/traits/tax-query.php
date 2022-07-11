<?php
namespace Jet_Engine\Query_Builder\Queries\Traits;

trait Tax_Query_Trait {

	/**
	 * Replace filtered arguments in the final query array
	 *
	 * @param  array  $rows [description]
	 * @return [type]       [description]
	 */
	public function replace_tax_query_row( $rows = array() ) {

		$replaced_rows = array();

		if ( ! empty( $this->final_query['tax_query'] ) ) {

			foreach ( $this->final_query['tax_query'] as $index => $existing_row ) {
				foreach ( $rows as $row_index => $row ) {
					if ( isset( $row['taxonomy'] ) && isset( $existing_row['taxonomy'] ) && $existing_row['taxonomy'] === $row['taxonomy'] ) {
						$this->final_query['tax_query'][ $index ] = $row;
						$replaced_rows[] = $row_index;
						return;
					}
				}
			}

		} else {
			$this->final_query['tax_query'] = array();
		}

		foreach ( $rows as $row_index => $row ) {
			if ( ! in_array( $row_index, $replaced_rows ) ) {
				$this->final_query['tax_query'][] = $row;
			}
		}

	}

}
