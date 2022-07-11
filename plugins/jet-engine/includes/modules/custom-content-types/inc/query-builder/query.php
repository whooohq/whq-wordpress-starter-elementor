<?php
namespace Jet_Engine\Modules\Custom_Content_Types\Query_Builder;

use Jet_Engine\Modules\Custom_Content_Types\Module;

class CCT_Query extends \Jet_Engine\Query_Builder\Queries\Base_Query {

	public $current_query = null;

	/**
	 * Returns queries items
	 *
	 * @return [type] [description]
	 */
	public function _get_items() {

		$result = array();

		$type = ! empty( $this->final_query['content_type'] ) ? $this->final_query['content_type'] : false;

		if ( ! $type ) {
			return $result;
		}

		$content_type = Module::instance()->manager->get_content_types( $type );

		$order  = ! empty( $this->final_query['order'] ) ? $this->final_query['order'] : array();
		$args   = ! empty( $this->final_query['args'] ) ? $this->final_query['args'] : array();
		$offset = ! empty( $this->final_query['offset'] ) ? absint( $this->final_query['offset'] ) : 0;
		$status = ! empty( $this->final_query['status'] ) ? $this->final_query['status'] : '';
		$limit  = ! empty( $this->final_query['number'] ) ? absint( $this->final_query['number'] ) : 0;

		$flag = \OBJECT;
		$content_type->db->set_format_flag( $flag );

		if ( $status ) {
			$args[] = array(
				'field'    => 'cct_status',
				'operator' => '=',
				'value'    => $status,
			);
		}

		$content_type->db->set_query_object( $this );

		$args   = $content_type->prepare_query_args( $args );
		$result = $content_type->db->query( $args, $limit, $offset, $order );

		return $result;

	}

	/**
	 * Allows to return any query specific data that may be used by abstract 3rd parties
	 *
	 * @return [type] [description]
	 */
	public function get_query_meta() {
		$type = ! empty( $this->final_query['content_type'] ) ? $this->final_query['content_type'] : false;
		return array(
			'content_type' => $type,
		);
	}

	/**
	 * Returns currently displayed page number
	 *
	 * @return [type] [description]
	 */
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

		$result = 0;
		$type = ! empty( $this->final_query['content_type'] ) ? $this->final_query['content_type'] : false;

		if ( ! $type ) {
			return $result;
		}

		$content_type = Module::instance()->manager->get_content_types( $type );
		$args         = ! empty( $this->final_query['args'] ) ? $this->final_query['args'] : array();

		$content_type->db->set_query_object( $this );

		$args   = $content_type->prepare_query_args( $args );
		$result = $content_type->db->count( $args );


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

		if ( ! empty( $this->final_query['number'] ) ) {
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

		$result   = $this->get_items_total_count();
		$per_page = $this->get_items_per_page();

		if ( $per_page < $result ) {

			$page  = $this->get_current_items_page();
			$pages = $this->get_items_pages_count();

			if ( $page < $pages ) {
				$result = $per_page;
			} elseif ( $page == $pages ) {
				$offset = ! empty( $this->final_query['offset'] ) ? absint( $this->final_query['offset'] ) : 0;
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

			case 'orderby':
			case 'order':
			case 'meta_key':

				$key = $prop;

				if ( 'orderby' === $prop ) {
					$key = 'type';
					$value = ( 'meta_key' === $value ) ? 'CHAR' : 'DECIMAL';
				} elseif ( 'meta_key' === $prop ) {
					$key = 'orderby';
				}

				$this->set_filtered_order( $key, $value );
				break;

			case 'meta_query':

				foreach ( $value as $row ) {

					if ( ! empty( $row['relation'] ) ) {

						$prepared_row = array(
							'relation' => $row['relation'],
						);

						unset( $row['relation'] );

						foreach ( $row as $inner_row ) {
							$prepared_row[] = array(
								'field'    => ! empty( $inner_row['key'] ) ? $inner_row['key'] : false,
								'operator' => ! empty( $inner_row['compare'] ) ? $inner_row['compare'] : '=',
								'value'    => ! empty( $inner_row['value'] ) ? $inner_row['value'] : '',
								'type'     => ! empty( $inner_row['type'] ) ? $inner_row['type'] : false,
							);
						}

					} else {
						$prepared_row = array(
							'field'    => ! empty( $row['key'] ) ? $row['key'] : false,
							'operator' => ! empty( $row['compare'] ) ? $row['compare'] : '=',
							'value'    => ! empty( $row['value'] ) ? $row['value'] : '',
							'type'     => ! empty( $row['type'] ) ? $row['type'] : false,
						);
					}

					$this->update_args_row( $prepared_row );

				}

				break;

			default: 
				
				$this->merge_default_props( $prop, $value );
				break;

		}

	}

	/**
	 * Set new order from filters query
	 *
	 * @param [type] $key   [description]
	 * @param [type] $value [description]
	 */
	public function set_filtered_order( $key, $value ) {

		if ( empty( $this->final_query['order'] ) ) {
			$this->final_query['order'] = array();
		}

		if ( ! isset( $this->final_query['order']['custom'] ) ) {
			$this->final_query['order'] = array_merge( array( 'custom' => array() ), $this->final_query['order'] );
		}

		$this->final_query['order']['custom'][ $key ] = $value;

	}

	/**
	 * Update argumnts row in the arguments list of the final query
	 *
	 * @param  [type] $row [description]
	 * @return [type]      [description]
	 */
	public function update_args_row( $row ) {

		if ( empty( $this->final_query['args'] ) ) {
			$this->final_query['args'] = array();
		}

		foreach ( $this->final_query['args'] as $index => $existing_row ) {
			if ( ( isset( $existing_row['field'] ) && isset( $row['field'] ) ) && $existing_row['field'] === $row['field'] && $existing_row['operator'] === $row['operator'] ) {

				if ( ! empty( $row['type'] ) && 'TIMESTAMP' === $row['type'] ) {
					$row['type']  = 'NUMERIC';
					$row['value'] = \Jet_Engine_Tools::is_valid_timestamp( $row['value'] ) ? $row['value'] : strtotime( $row['value'] );
				}

				$this->final_query['args'][ $index ] = $row;
				return;
			}
		}

		$this->final_query['args'][] = $row;

	}

}
