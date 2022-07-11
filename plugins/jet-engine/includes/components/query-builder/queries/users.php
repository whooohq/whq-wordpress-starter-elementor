<?php
namespace Jet_Engine\Query_Builder\Queries;

use Jet_Engine\Query_Builder\Manager;

class Users_Query extends Base_Query {

	use Traits\Meta_Query_Trait;

	public $current_wp_query = null;

	/**
	 * Returns queries items
	 *
	 * @return [type] [description]
	 */
	public function _get_items() {

		$current_query = $this->get_current_wp_query();
		$result        = array();

		if ( $current_query ) {
			$result = $current_query->get_results();
		}

		return $result;

	}

	public function get_current_wp_query() {

		if ( null !== $this->current_wp_query ) {
			return $this->current_wp_query;
		}

		$args = $this->final_query;

		if ( ! empty( $args['meta_query'] ) ) {
			$args['meta_query'] = $this->prepare_meta_query_args( $args );
		}

		$this->current_wp_query = new \WP_User_Query( $args );

		return $this->current_wp_query;

	}

	public function get_current_items_page() {

		$query = $this->get_current_wp_query();
		$page  = $query->get( 'paged' );

		return ! empty( $page ) ? absint( $page ) : 1;

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

		$query  = $this->get_current_wp_query();
		$result = $query->get_total();

		$this->update_query_cache( $result, 'count' );

		return $result;

	}

	/**
	 * Returns count of the items visible per single listing grid loop/page
	 * @return [type] [description]
	 */
	public function get_items_per_page() {

		$query  = $this->get_current_wp_query();
		$number = $query->get( 'number' );
		$number = absint( $number );

		return $number;
	}

	/**
	 * Returns queried items count per page
	 *
	 * @return [type] [description]
	 */
	public function get_items_page_count() {

		$result   = $this->get_items_total_count();
		$per_page = $this->get_items_per_page();

		if ( $per_page < $result ) {

			$page  = $this->get_current_items_page();
			$pages = $this->get_items_pages_count();

			if ( $page < $pages ) {
				$result = $per_page;
			} elseif ( $page == $pages ) {
				$offset = $per_page * ( $page - 1 );
				$result = $result - $offset;
			}

		}

		return $result;

	}

	/**
	 * Returns queried items pages count
	 *
	 * @return [type] [description]
	 */
	public function get_items_pages_count() {

		$query    = $this->get_current_wp_query();
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
				$this->final_query['paged'] = $value;
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
			'include',
			'exclude',
		);
	}

	public function reset_query() {
		$this->current_wp_query = null;
	}

}
