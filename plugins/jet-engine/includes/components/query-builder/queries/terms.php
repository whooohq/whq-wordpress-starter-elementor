<?php
namespace Jet_Engine\Query_Builder\Queries;

use Jet_Engine\Query_Builder\Manager;

class Terms_Query extends Base_Query {

	use Traits\Meta_Query_Trait;

	/**
	 * Returns queries items
	 *
	 * @return [type] [description]
	 */
	public function _get_items() {
		$current_query = $this->build_current_query();
		$terms = get_terms( $current_query );
		return $terms;
	}

	/**
	 * Build query arguments list
	 *
	 * @param  boolean $is_count [description]
	 * @return [type]            [description]
	 */
	public function build_current_query( $is_count = false ) {

		$args = $this->final_query;

		if ( ! $is_count && ! empty( $args['number_per_page'] ) ) {
			$args['number'] = absint( $args['number_per_page'] );
		}

		if ( ! empty( $args['meta_query'] ) ) {
			$args['meta_query'] = $this->prepare_meta_query_args( $args );
		}

		if ( $is_count && isset( $args['offset'] ) ) {
			$args['offset'] = 0;
		}

		if ( ! empty( $args['orderby'] ) && 'meta_clause' === $args['orderby'] ) {

			$clause_name = ! empty( $args['order_meta_clause'][0] ) ? $args['order_meta_clause'][0] : false;

			if ( $clause_name ) {
				$args['orderby'] = $clause_name;
			}
		}

		return $args;
	}

	public function get_current_items_page() {

		$offset = ! empty( $this->final_query['offset'] ) ? absint( $this->final_query['offset'] ) : 0;
		$per_page = $this->get_items_per_page();

		if ( ! $offset || ! $per_page ) {
			return 1;
		} else {
			return ceil( $offset / $per_page ) + 1;
		}

	}

	/**
	 * Returns total found items count
	 *
	 * @return [type] [description]
	 */
	public function get_items_total_count() {

		$cached = $this->get_cached_data( 'count' );

		if ( false !== $cached ) {
			return $cached;
		}

		$this->setup_query();

		$current_query = $this->build_current_query( true );
		$result = get_terms( $current_query );

		if ( empty( $result ) || is_wp_error( $result ) ) {
			$result = 0;
		} else {
			$result = count( $result );
		}

		$result = absint( $result );

		$this->update_query_cache( $result, 'count' );

		return $result;

	}

	/**
	 * Returns count of the items visible per single listing grid loop/page
	 * @return [type] [description]
	 */
	public function get_items_per_page() {

		$this->setup_query();
		$limit = 0;

		if ( ! empty( $this->final_query['number_per_page'] ) ) {
			$limit = absint( $this->final_query['number_per_page'] );
		} elseif ( ! empty( $this->final_query['number'] ) ) {
			$limit = absint( $this->final_query['number'] );
		}

		return $limit;
	}

	/**
	 * Returns queried items count per page
	 *
	 * @return [type] [description]
	 */
	public function get_items_page_count() {
		return $this->get_items_total_count();
	}

	/**
	 * Returns queried items pages count
	 *
	 * @return [type] [description]
	 */
	public function get_items_pages_count() {

		$per_page = $this->get_items_per_page();
		$total    = $this->get_items_total_count();

		if ( ! $per_page || ! $total ) {
			return 1;
		} else {
			return ceil( $total / $per_page );
		}

	}

	public function set_filtered_prop( $prop = '', $value = null ) {

		switch ( $prop ) {

			case '_page':

				$page = absint( $value );

				if ( 0 < $page ) {
					$offset = ( $page - 1 ) * $this->get_items_per_page();
					$this->final_query['offset'] = $offset;
				}

				break;

			case 'meta_query':
				$this->replace_meta_query_row( $value );
				break;

			default:
				$this->merge_default_props( $prop, $value );
				break;
		}

	}

	/**
	 * Array of arguments where string should be exploded into array
	 *
	 * @return [type] [description]
	 */
	public function get_args_to_explode() {
		return array(
			'name',
			'slug',
			'object_ids',
		);
	}

}
