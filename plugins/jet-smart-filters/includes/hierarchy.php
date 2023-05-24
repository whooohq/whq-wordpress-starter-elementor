<?php
/**
 * Data class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Hierarchy' ) ) {

	class Jet_Smart_Filters_Hierarchy {

		protected $filter_id  = 0;
		protected $depth      = 0;
		protected $values     = array();
		protected $args       = array();
		protected $filter     = null;
		protected $single_tax = null;
		protected $hierarchy  = null;
		protected $indexer    = false;

		/**
		 * Class constructor
		 */
		public function __construct( $filter = 0, $depth = 0, $values = array(), $args = array(), $indexer = false ) {

			if ( isset( $args['layout_options'] ) ) {
				$layout_options = $args['layout_options'];
				unset( $args['layout_options'] );
				$args = array_merge( $args, $layout_options );
			}

			$this->args = $args;

			if ( is_integer( $filter ) ) {

				$this->filter_id = $filter;
				$this->filter    = jet_smart_filters()->filter_types->get_filter_instance(
					$this->filter_id,
					null,
					$this->args
				);

			} else {
				$this->filter_id = $filter->get_filter_id();
				$this->filter    = $filter;
			}

			$this->depth     = $depth;
			$this->values    = $values;
			$this->indexer   = $indexer;
			$this->hierarchy = $this->get_hierarchy();
		}

		/**
		 * Returns current filter hieararchy map or false
		 */
		public function get_hierarchy() {

			$hierarchy = get_post_meta( $this->filter_id, '_ih_source_map', true );

			if ( empty( $hierarchy ) ) {
				return false;
			}

			$result = array();

			foreach ( array_values( $hierarchy ) as $depth => $data ) {

				$result[] = array(
					'depth'       => $depth,
					'tax'         => $data['tax'],
					'label'       => $data['label'],
					'placeholder' => $data['placeholder'],
					'options'     => false,
				);

			}

			return $result;
		}

		/**
		 * Returns hiearachy evels data starting from $this->depth
		 */
		public function get_levels() {

			timer_start();

			if ( empty( $this->hierarchy ) ) {
				return;
			}

			$result = array();
			$filter = $this->filter;

			$from_depth = ( false !== $this->depth ) ? $this->depth : 0;

			$single_tax = $this->hierarchy[0]['tax'];
			foreach ( $this->hierarchy as $hierarchy_level ) {
				$single_tax = $single_tax === $hierarchy_level['tax'] ? $hierarchy_level['tax'] : false;
			}

			$current_value_data = array();
			if ( isset( $_REQUEST['hc_' . $single_tax] ) ) {
				$current_value_data = explode( ',', $_REQUEST['hc_' . $single_tax] );
			}

			for ( $i = $from_depth; $i <= count( $this->hierarchy ); $i++ ) {
				$level = ! empty( $this->hierarchy[ $i ] ) ? $this->hierarchy[ $i ] : false;

				if ( ! $level ) {
					continue;
				}

				$args       = $filter->get_args();
				$show_label = ! empty( $args['show_label'] ) ? filter_var( $args['show_label'], FILTER_VALIDATE_BOOLEAN ) : false;

				$args['depth']           = $level['depth'];
				$args['query_var']       = $level['tax'];
				$args['placeholder']     = ! empty( $level['placeholder'] ) ? $level['placeholder'] : __( 'Select...', 'jet-smart-filters' );
				$args['max_depth']       = count( $this->hierarchy ) - 1;
				$args['options']         = array();
				$args['filter_label']    = $show_label && ! empty( $level['label'] ) ? $level['label'] : '';
				$args['display_options'] = ! empty( $this->args['display_options'] ) ? $this->args['display_options'] : array();

				if ( $single_tax ) {
					$args['single_tax'] = $single_tax;
				}

				$current_level_value = $filter->get_current_filter_value( $args );
				if ( ! $current_level_value && ( is_category() || is_tag() || is_tax( $level['tax'] ) ) ) {
					$current_level_value = get_queried_object_id();
				}
				if ( end( $current_value_data ) !== $current_level_value ) {
					array_push( $current_value_data, $current_level_value );
				}
				if ( isset( $current_value_data[$args['depth']] ) ) {
					$args['current_value'] = $current_value_data[$args['depth']];
				}
				if ( isset( $current_value_data[$level['depth'] - 1] ) ) {
					$args['is_loading'] = true;
				}
				
				if ( false === $this->depth ) {
					if ( $i <= count( $this->values ) ) {
						$args['options'] = $this->get_level_options( $i );
					}
				} elseif ( $i === $from_depth ) {
					$args['options'] = $this->get_level_options( $i );
				}

				$result[ 'level_' . $i ] = $this->filter->get_rendered_template( $args );
			}

			return $result;
		}

		/**
		 * Returns terms for options
		 */
		public function get_level_options( $i = 0 ) {

			global $wpdb;

			$result     = array();
			$curr_level = isset( $this->hierarchy[$i] ) ? $this->hierarchy[$i] : false;
			$prev_level = isset( $this->hierarchy[$i - 1] ) ? $this->hierarchy[$i - 1] : false;
			$next_level = isset( $this->hierarchy[$i + 1] ) ? $this->hierarchy[$i + 1] : false;

			if ( ! $curr_level ) {
				return $result;
			}

			$is_sub_tax =
				( $prev_level && $curr_level['tax'] === $prev_level['tax'] )
				||
				( $next_level && $curr_level['tax'] === $next_level['tax'] );

			if ( $is_sub_tax ) {
				if ( false === $this->depth && 0 === $i ) {
					$value = 0;
				} else {
					$index = $i - 1;
					$value = isset( $this->values[ $index ] ) ? $this->values[ $index ]['value'] : false;
				}

				if ( false !== $value ) {
					if ( empty( $value ) && 0 !== $value ) {
						return array();
					}

					$result = jet_smart_filters()->data->get_terms_for_options(
						$curr_level['tax'],
						false,
						array(
							'parent' => $value,
						)
					);
				}
			} else {
				$from  = '';
				$on    = '';
				$where = '';
				$glue  = '';
				$index = 0;

				$prepared_values = array();

				/**
				 * Ensure we left only latest child of each taxonomy
				 */
				for ( $level_index = 0; $level_index < $i; $level_index++ ) {
					$level_val = $this->values[ $level_index ];
					$prepared_values[ $level_val['tax'] ] = $level_val['value'];
				}

				foreach ( $prepared_values as $tax => $value ) {
					if ( $value ) {

						$table            = $wpdb->term_relationships;
						$value            = absint( $value );
						$term_taxonomy    = get_term( $value );
						$term_taxonomy_id = ! is_wp_error($term_taxonomy) ? $term_taxonomy->term_taxonomy_id : false;

						if ( 0 === $index ) {
							$from  .= "SELECT t0.object_id FROM $table AS t0";
							$where .= " WHERE t0.term_taxonomy_id = {$term_taxonomy_id}";
						} else {
							$from  .= " INNER JOIN $table AS t{$index}";
							$where .= " AND t{$index}.term_taxonomy_id = {$term_taxonomy_id}";
							$prev   = $index - 1;
							$on    .= "{$glue}t{$prev}.object_id = t{$index}.object_id";
							$glue   = ' AND ';
						}

						$index++;
					}
				}

				if ( ! empty( $on ) ) {
					$on = ' ON ( ' . $on . ' )';
				}

				if ( $from ) {
					$ids = $wpdb->get_results( $from . $on . $where, OBJECT_K );

					if ( ! empty( $ids ) ) {
						$result = jet_smart_filters()->data->get_terms_for_options(
							$curr_level['tax'],
							false,
							array(
								'object_ids' => array_keys( $ids ),
							)
						);
					}
				} else {
					$result = jet_smart_filters()->data->get_terms_for_options(
						$curr_level['tax'],
						false,
						array(
							'parent' => 0,
						)
					);
				}
			}

			return $result;
		}

		/**
		 * Check if all previous hierarchy levels has same taxonomy.
		 * In this case we need get only direct children of latest value
		 */
		public function is_single_tax_hierarchy() {

			if ( null !== $this->single_tax ) {
				return $this->single_tax;
			}

			$single_tax = true;
			$tax        = null;
			$to_depth   = ( false !== $this->depth ) ? $this->depth : count( $this->values );

			for ( $i = 0; $i <= $to_depth; $i++ ) {
				$level = ! empty( $this->hierarchy[ $i ] ) ? $this->hierarchy[ $i ] : false;

				if ( ! $level ) {
					continue;
				}

				if ( ! $tax ) {
					$tax = $level['tax'];
				} elseif ( $tax !== $level['tax'] ) {
					$single_tax = false;
				}
			}

			if ( $single_tax ) {
				$this->single_tax = $tax;
			} else {
				$this->single_tax = false;
			}

			return $this->single_tax;
		}
	}
}
