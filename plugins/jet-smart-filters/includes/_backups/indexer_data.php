<?php
/**
 * Jet Smart Filters Indexer Data class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Indexer_Data' ) ) {

	/**
	 * Define Jet_Smart_Filters_Indexer_Data class
	 */
	class Jet_Smart_Filters_Indexer_Data {

		public $indexing_filters = array();

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @return void
		 */
		public function __construct() {

			add_filter( 'jet-smart-filters/filters/localized-data', array( $this, 'prepare_localized_data' ) );
			add_filter( 'jet-smart-filters/render/ajax/data', array( $this, 'prepare_ajax_data' ) );

		}

		/**
		 * Prepare localized data
		 *
		 * @param $args
		 *
		 * @return mixed
		 */
		public function prepare_localized_data( $args ) {

			$indexed_data = array();

			foreach ( jet_smart_filters()->query->get_default_queries() as $provider => $queries ) {
				foreach ( $queries as $query_id => $query_args ) {
					$provider_key           = $provider . '/' . $query_id;
					$provider_indexing_data = $this->get_indexing_data_for_provider( $provider_key );

					if ( empty( $provider_indexing_data ) ) {
						continue;
					}

					$provider_indexed_data = $this->get_indexed_data( $provider_key, $query_args, $provider_indexing_data );

					if ( $provider_indexed_data ) {
						$indexed_data[$provider_key] = $provider_indexed_data;
					}
				}
			}

			if ( ! empty( $indexed_data ) ) {
				$args['jetFiltersIndexedData'] = apply_filters( 'jet-smart-filters/filters/indexed-data', $indexed_data );
			}

			return $args;

		}

		/**
		 * Prepare data for ajax actions
		 *
		 * @param $args
		 *
		 * @return mixed
		 */
		public function prepare_ajax_data( $args ) {

			/* $provider_key     = isset( $_REQUEST['provider'] ) ? $_REQUEST['provider'] : false;
			$indexing_filters = isset( $_REQUEST['indexing_filters'] ) ? json_decode( stripcslashes( $_REQUEST['indexing_filters'] ), true ) : false;

			if ( ! ( $provider_key && $indexing_filters ) ) {
				return $args;
			}

			if ( $provider_key && $indexing_filters ) {
				foreach ( $indexing_filters as $filter_id ) {
					$this->add_indexing_data_from_filter( $provider_key, $filter_id );
				}
			}

			$indexed_data = array();
			if ( ! empty( $this->indexing_data[$provider_key] ) ) {
				$query_args   = jet_smart_filters()->query->get_query_args();
				$indexed_data = $this->get_indexed_data( $provider_key, $query_args );
			}

			$args['jetFiltersIndexedData'] = apply_filters( 'jet-smart-filters/filters/indexed-data', array(
				$provider_key => $indexed_data
			) ); */

			return $args;

		}

		public function get_indexed_data( $provider_key, $query_args, $indexing_data ) {

			$indexed_data = array();
			$queryed_ids  = $this->get_queryed_ids( $query_args );
			$type         = ! empty( $query_args['type'] ) ? $query_args['type'] : 'post';
			$sql_and      = '';

			foreach ( $indexing_data as $query_type => $query_data ) {
				switch ( $query_type ) {
					case 'tax_query':
						$sql_and .= $sql_and ? ' OR ' : '';
						$sql_and .= "( item_query = '$query_type' AND item_value IN ('" . implode( "','", $query_data ) . "') )";

						break;
					
					case 'meta_query':
						foreach ( $query_data as $meta_key => $meta_value ) {
							$sql_and .= $sql_and ? ' OR ' : '';

							if ( strpos( $meta_key, '|' ) ) {
								$suffix_data = explode( '|', $meta_key, 2 );

								switch ( $suffix_data[1] ) {
									case 'range':
										$sql_and .= "( item_query = '$query_type' AND item_key = '" . $suffix_data[0] . "' AND item_value >= " . $meta_value['min'] . " AND item_value <= " . $meta_value['max'] . " )";

									break;
								}
							} else {
								$sql_and .= "( item_query = '$query_type' AND item_key = '$meta_key' AND item_value IN ('" . implode( "','", $meta_value ) . "') )";
							}
						}

						break;
				}
			}

			global $wpdb;
			$sql = "
			SELECT MAX(item_query) as item_query, MAX(item_key) as item_key, item_value, COUNT(item_id) as count
				FROM " . jet_smart_filters()->indexer->table_name() . "
				WHERE item_id IN (" . implode( ",", $queryed_ids ) . ")
				AND ( type = '$type' )
				AND ( $sql_and )
				GROUP BY item_key, item_value
				ORDER BY item_value ASC";
			$result = $wpdb->get_results( $sql, ARRAY_A );

			foreach ( $this->indexing_filters as $filter ) {
				if ( $filter['provider'] !== $provider_key ) {
					continue;
				}

				$filter_type = $filter['filter-type'];
				$query_type  = $filter['query-type'];
				$key         = $filter['key'];
				$data        = $filter['data'];
				
				if ( ! isset( $indexed_data[$filter_type] ) ) {
					$indexed_data[$filter_type] = array();
				}

				if ( ! isset( $indexed_data[$filter_type][$key] ) ) {
					$indexed_data[$filter_type][$key] = array();
				}

				foreach ( explode( ',', $key ) as $single_key) {
					$single_key = trim( $single_key );

					foreach ( $data as $option ) {
						if ( $filter_type === 'check-range' ) {
							$option_key = $option['min'] . '_' . $option['max'];
	
							if ( ! isset( $indexed_data[$filter_type][$key][$option_key] ) ) {
								$indexed_data[$filter_type][$key][$option_key] = 0;
							}
	
							foreach ( $result as $row ) {
								if ( $row['item_key'] === $single_key ) {
									if ( $row['item_value'] >= $option['min'] && $row['item_value'] <= $option['max'] ) {
										$indexed_data[$filter_type][$key][$option_key] += $row['count'];
									}
								}
							}
						} else {
							if ( ! isset( $indexed_data[$filter_type][$key][$option] ) ) {
								$indexed_data[$filter_type][$key][$option] = 0;
							}
	
							foreach ( $result as $row ) {
								if ( $row['item_query'] === $query_type && $row['item_key'] === $single_key && $row['item_value'] === $option ) {
									$indexed_data[$filter_type][$key][$option] += $row['count'];
								}
							}
						}
					}
				}
			}

			return $indexed_data;

		}

		public function add_indexing_filter( $provider_key, $filter_id ) {

			$query_type = '';
			$data       = array();
			$key        = '';
			$source     = get_post_meta( $filter_id );

			if ( $source['_filter_type'][0] === 'check-range' ) {
				$query_type = 'meta_query';
				$key        = $source['_query_var'][0];

				foreach ( unserialize( $source['_source_manual_input_range'][0] ) as $range_data ) {
					$min = ! empty( $range_data['min'] ) ? intval( $range_data['min'] ) : 0;
					$max = ! empty( $range_data['max'] ) ? intval( $range_data['max'] ) : 100;

					$data[$min . '_' . $max] = array(
						'min' => $min,
						'max' => $max,
					);
				}
			} else if ( $source['_filter_type'][0] === 'select' && filter_var( $source['_is_hierarchical'][0], FILTER_VALIDATE_BOOLEAN ) ) {
				$query_type  = 'tax_query';

				foreach ( unserialize( $source['_ih_source_map'][0] ) as $item ) {
					$data = array_merge( $this->get_terms_by_tax( $item['tax'] ), $data );
				}
			} else {
				$data_source = ! empty( $source['_data_source'] ) ? $source['_data_source'][0] : false;

				switch ( $data_source ) {
					case 'taxonomies':
						$query_type = 'tax_query';
						$key        = ! empty( $source['_source_taxonomy'] ) ? $source['_source_taxonomy'][0] : false;

						$exclude_include = $source['_use_exclude_include'][0];

						if ( $exclude_include === 'include' ) {
							$data = unserialize( $source['_data_exclude_include'][0] );
						} else {
							$data = $this->get_terms_by_tax( $key );

							if ( $exclude_include === 'exclude' ) {
								$data = array_diff( $data, unserialize( $source['_data_exclude_include'][0] ) );
							}
						}

						break;

					case 'manual_input':
						$query_type = 'meta_query';
						$key        = $source['_query_var'][0];

						if ( $source['_filter_type'][0] === 'color-image' ) {
							$input_data = $source['_source_color_image_input'][0];
						} else {
							$input_data = $source['_source_manual_input'][0];
						}

						if ( ! $input_data ) {
							return;
						}

						foreach ( unserialize( $input_data ) as $input_item ) {
							$data[] = $input_item['value'];
						}

						break;

					case 'custom_fields':
						$query_type = 'meta_query';
						$key        = $source['_query_var'][0];
						$custom_field_options = jet_smart_filters()->data->get_choices_from_field_data( array(
							'field_key' => $source['_source_custom_field'][0],
							'source'    => $source['_custom_field_source_plugin'][0],
						) );
						$data = array_keys( $custom_field_options );

						break;

					default:
						$key         = $source['_query_var'][0];
						$custom_args = apply_filters( 'jet-smart-filters/indexer/custom-args', array(), $filter_id );
						$query_type  = isset( $custom_args['query_type'] ) ? $custom_args['query_type'] : 'meta_query';
						$options     = ! empty( $custom_args['options'] ) ? $custom_args['options'] : false;
						$data        = $options;

						break;
				}
			}

			if ( $query_type && $data ) {
				$filter = array(
					'provider'    => $provider_key,
					'id'          => $filter_id,
					'filter-type' => $source['_filter_type'][0],
					'query-type'  => $query_type,
					'key'         => $key,
					'data'        => $data
				);

				$this->indexing_filters[] = $filter;
			}

		}

		/**
		 * Add indexing data from filter by id
		 *
		 * @param $filter_id
		 *
		 * @return void
		 */
		public function get_indexing_data_for_provider( $provider_key ) {

			$indexing_data = array();

			foreach ( $this->indexing_filters as $filter ) {
				if ( $filter['provider'] !== $provider_key ) {
					continue;
				}

				$query_type  = $filter['query-type'];

				if ( ! isset( $indexing_data[$query_type] ) ) {
					$indexing_data[$query_type] = array();
				}

				if ( $query_type === 'tax_query' ) {
					$indexing_data[$query_type] = array_merge( $indexing_data[$query_type], $filter['data'] );
				}
				if ( $query_type === 'meta_query' ) {
					$meta_keys = explode( ',', $filter['key'] );

					foreach ($meta_keys as $meta_key) {
						$meta_key = trim( $meta_key );

						if ( $filter['filter-type'] === 'check-range' ) {
							$meta_key .= '|range';

							if ( ! isset( $indexing_data[$query_type][$meta_key] ) ) {
								$indexing_data[$query_type][$meta_key] = array();
							}

							foreach ( $filter['data'] as $rangeData ) {
								$min = ! empty( $rangeData['min'] ) ? intval( $rangeData['min'] ) : 0;
								$max = ! empty( $rangeData['max'] ) ? intval( $rangeData['max'] ) : 100;

								if ( ! isset( $indexing_data[$query_type][$meta_key]['min'] ) || $indexing_data[$query_type][$meta_key]['min'] > $min ) {
									$indexing_data[$query_type][$meta_key]['min'] = $min;
								}
			
								if ( ! isset( $indexing_data[$query_type][$meta_key]['max'] ) || $indexing_data[$query_type][$meta_key]['max'] < $max ) {
									$indexing_data[$query_type][$meta_key]['max'] = $max;
								}
							}
						} else {
							if ( ! isset( $indexing_data[$query_type][$meta_key] ) ) {
								$indexing_data[$query_type][$meta_key] = array();
							}

							$indexing_data[$query_type][$meta_key] = array_merge( $indexing_data[$query_type][$meta_key], $filter['data'] );
						}
					}
				}
			}

			return $indexing_data;

		}

		public function get_terms_by_tax( $tax ) {

			global $wpdb;
			$terms = array();
			$sql = "
				SELECT term_id
				FROM wp_term_taxonomy
				WHERE taxonomy = '$tax'";

			$result = $wpdb->get_results( $sql, ARRAY_A );
			foreach ( $result as $term_id ) {
				array_push( $terms, $term_id['term_id'] );
			}

			return $terms;

		}

		public function get_queryed_ids( $args ) {

			$ids  = array( -1 );
			$type = ! empty( $args['type'] ) ? $args['type'] : 'post';

			unset( $args['jet_smart_filters'] );
			unset( $args['paged'] );

			switch ( $type ) {
				case 'post':
					$post_main_args = [
						'post_status'    => 'publish',
						'posts_per_page' => -1,
						'fields'         => 'ids'
					];
		
					$query = new WP_Query( wp_parse_args( $post_main_args, $args ) );

					if ( ! empty( $query->posts ) ) {
						$ids = $query->posts;
					}
					
					break;
				
				case 'user':
					$user_main_args = [
						'number'      => -1,
						'count_total' => false,
						'fields'      => 'ID'
					];

					$users_ids =  get_users( wp_parse_args( $user_main_args, $args ) ) ;

					if ( ! empty( $users_ids ) ) {
						$ids = $users_ids;
					}

					break;
			}

			

			return $ids;

		}

	}

}
