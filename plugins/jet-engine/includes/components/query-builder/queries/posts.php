<?php
namespace Jet_Engine\Query_Builder\Queries;

use Jet_Engine\Query_Builder\Manager;

class Posts_Query extends Base_Query {

	use Traits\Meta_Query_Trait;
	use Traits\Tax_Query_Trait;

	public $current_wp_query = null;

	/**
	 * Returns queries items
	 *
	 * @return [type] [description]
	 */
	public function _get_items() {

		$current_query = $this->get_current_wp_query();

		$result = array();

		if ( $current_query ) {
			$result = $current_query->posts;
		}

		return $result;

	}

	/**
	 * Returns current query arguments
	 *
	 * @return array
	 */
	public function get_query_args() {

		if ( null === $this->final_query ) {
			$this->setup_query();
		}

		$args = $this->final_query;

		if ( ! empty( $args['meta_query'] ) ) {
			$args['meta_query'] = $this->prepare_meta_query_args( $args );
		}

		if ( ! empty( $args['tax_query'] ) ) {

			$raw = $args['tax_query'];
			$args['tax_query'] = array();

			if ( ! empty( $args['tax_query_relation'] ) ) {
				$args['tax_query']['relation'] = $args['tax_query_relation'];
			}

			foreach ( $raw as $query_row ) {

				// 'exclude_children' => true  is replaced to 'include_children' => false
				// 'exclude_children' => false is replaced to 'include_children' => true
				if ( isset( $query_row['exclude_children'] ) ) {
					$query_row['include_children'] = ! $query_row['exclude_children'];
					unset( $query_row['exclude_children'] );
				}

				if ( empty( $query_row['operator'] ) || in_array( $query_row['operator'], array( 'IN', 'NOT IN' ) ) ) {
					if ( ! empty( $query_row['terms'] ) && ! is_array( $query_row['terms'] ) ) {
						$query_row['terms'] = $this->explode_string( $query_row['terms'] );
					}
				}

				$args['tax_query'][] = $query_row;
			}

		}

		if ( ! empty( $args['orderby'] ) ) {

			$raw = $args['orderby'];
			$args['orderby'] = array();

			foreach ( $raw as $query_row ) {

				if ( empty( $query_row ) ) {
					continue;
				}

				$order = isset( $query_row['order'] ) ? $query_row['order'] : '';

				switch ( $query_row['orderby'] ) {
					case 'meta_clause':

						$clause_name = ! empty( $query_row['order_meta_clause'] ) ? $query_row['order_meta_clause'] : false;

						if ( $clause_name ) {
							$args['orderby'][ $clause_name ] = $order;
						}

						break;

					case 'meta_value_num':
					case 'meta_value':
						$args['orderby'][ $query_row['orderby'] ] = $order;

						if ( isset( $query_row['meta_key'] ) ) {
							$args['meta_key'] = $query_row['meta_key'];
						}

						break;

					case 'rand':

						$rand = sprintf( 'RAND(%s)', rand() );
						$args['orderby'][ $rand ] = $order;

						break;

					default:
						$args['orderby'][ $query_row['orderby'] ] = $order;
						break;
				}

			}

		} elseif ( isset( $args['orderby'] ) ) {
			unset( $args['orderby'] );
		}

		if ( empty( $args['offset'] ) ) {
			unset( $args['offset'] );
		}

		if ( isset( $args['comment_count_value'] ) && '' !== $args['comment_count_value'] ) {

			$value = absint( $args['comment_count_value'] );
			unset( $args['comment_count_value'] );

			if ( ! empty( $args['comment_count_compare'] ) ) {
				$args['comment_count'] = array(
					'value'   => $value,
					'compare' => $args['comment_count_compare'],
				);
			} else {
				$args['comment_count'] = $value;
			}

		}

		return $args;

	}

	/**
	 * Returns WP Query object for current query
	 *
	 * @return WP_Query
	 */
	public function get_current_wp_query() {

		if ( null !== $this->current_wp_query ) {
			return $this->current_wp_query;
		}

		$this->current_wp_query = new \WP_Query( $this->get_query_args() );

		return $this->current_wp_query;

	}

	public function get_current_items_page() {

		$query = $this->get_current_wp_query();
		$page  = ! empty( $this->final_query['paged'] ) ? $this->final_query['paged'] : false;

		if ( ! $page && ! empty( $this->final_query['page'] ) ) {
			$page = $this->final_query['page'];
		}

		if ( ! $page && ! empty( $this->final_query['page'] ) ) {
			$page = $this->final_query['page'];
		}

		if ( ! $page && ! empty( $query->query_var['paged'] ) ) {
			$page = $query->query_var['paged'];
		}

		if ( ! $page ) {
			$page = 1;
		}

		return $page;

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

		$query = $this->get_current_wp_query();

		$this->update_query_cache( $query->found_posts, 'count' );

		return $query->found_posts;

	}

	/**
	 * Returns count of the items visible per single listing grid loop/page
	 * @return [type] [description]
	 */
	public function get_items_per_page() {
		$query = $this->get_current_wp_query();
		return $query->query_vars['posts_per_page'];
	}

	/**
	 * Returns queried items count per page
	 *
	 * @return [type] [description]
	 */
	public function get_items_page_count() {
		$query = $this->get_current_wp_query();
		return $query->post_count;
	}

	/**
	 * Returns queried items pages count
	 *
	 * @return [type] [description]
	 */
	public function get_items_pages_count() {
		$query = $this->get_current_wp_query();
		return $query->max_num_pages;
	}

	public function set_filtered_prop( $prop = '', $value = null ) {

		switch ( $prop ) {

			case '_page':
				$this->final_query['paged'] = $value;
				$this->final_query['page']  = $value;
				break;

			case 'orderby':
			case 'order':
			case 'meta_key':
				$this->set_filtered_order( $prop, $value );
				break;

			case 'meta_query':
				$this->replace_meta_query_row( $value );
				break;

			case 'tax_query':
				$this->replace_tax_query_row( $value );
				break;

			default:
				$this->merge_default_props( $prop, $value );
				break;
		}

	}

	public function set_filtered_order( $key, $value ) {

		if ( empty( $this->final_query['orderby'] ) || ! isset( $this->final_query['orderby']['custom'] ) ) {
			$this->final_query['orderby'] = array( 'custom' => array() );
		}

		if ( 'orderby' === $key && is_array( $value ) ) {
			foreach ( $value as $orderby => $order ) {
				$this->final_query['orderby'][] = array(
					'orderby' => $orderby,
					'order'   => $order,
				);
			}
		} else {
			$this->final_query['orderby']['custom'][ $key ] = $value;
		}

	}

	/**
	 * Array of arguments where string should be exploded into array
	 *
	 * @return [type] [description]
	 */
	public function get_args_to_explode() {
		return array(
			'post_parent__in',
			'post_parent__not_in',
			'post__in',
			'post__not_in',
			'post_name__in',
			'author__in',
			'author__not_in',
		);
	}

	public function reset_query() {
		$this->current_wp_query = null;
	}

}
