<?php
/**
 * Filters service class
 */

class Jet_Smart_Filters_Service_Filters {

	public function get( $args ) {

		global $wpdb;

		$args = array_merge( array(
			'status'   => 'publish',
			'page'     => 1,
			'per_page' => 20,
			'orderby'  => 'date',
			'order'    => 'DESC',
			'search'   => false,
			'type'     => false,
			'source'   => false,
			'date'     => false
		), $args );

		$status = $args['status'];

		// Pagination
		$page     = $args['page'];
		$per_page = $args['per_page'];
		$offset   = $per_page * ( $page - 1 );

		// Sort
		$order_by = $args['orderby'];
		$order    = $args['order'];

		// Search
		$searchSql = '';
		$search    = $args['search'];

		if ( $search ) {
			$searchSql = "AND $wpdb->posts.post_title LIKE '%{$search}%'";
		}

		// Filtration
		$filtrationSql = '';
		$type          = $args['type'];
		$source        = $args['source'];
		$date          = $args['date'];

		if ( $type ) {
			$filtrationSql .= "AND postmeta_type.meta_value = '$type' ";
		}

		if ( $source ) {
			$filtrationSql .= "AND postmeta_source.meta_value = '$source' ";
		}

		if ( $date ) {
			if ( $date['from'] ) {
				$filtrationSql .= "AND $wpdb->posts.post_date >= '" . $date['from'] . " 00:00:00' ";
			}
			if ( $date['to'] ) {
				$filtrationSql .= "AND $wpdb->posts.post_date <= '" . $date['to'] . " 23:59:59' ";
			}
		}

		$sql_main = "
		SELECT $wpdb->posts.ID, $wpdb->posts.post_title as title, $wpdb->posts.post_date as date, postmeta_type.meta_value as type, postmeta_source.meta_value as source
		FROM $wpdb->posts
			LEFT JOIN $wpdb->postmeta as postmeta_type ON ($wpdb->posts.ID = postmeta_type.post_ID AND postmeta_type.meta_key = '_filter_type')
			LEFT JOIN $wpdb->postmeta as postmeta_source ON ($wpdb->posts.ID = postmeta_source.post_ID AND postmeta_source.meta_key = '_data_source')
			WHERE $wpdb->posts.post_type = 'jet-smart-filters'
				AND $wpdb->posts.post_status = '$status'
				$searchSql
				$filtrationSql
			ORDER BY $order_by $order
			LIMIT $offset, $per_page";
		$filters_result = $wpdb->get_results( $sql_main, ARRAY_A );

		$sql_count = "
		SELECT COUNT(*) as count
		FROM $wpdb->posts
		LEFT JOIN $wpdb->postmeta as postmeta_type ON ($wpdb->posts.ID = postmeta_type.post_ID AND postmeta_type.meta_key = '_filter_type')
		LEFT JOIN $wpdb->postmeta as postmeta_source ON ($wpdb->posts.ID = postmeta_source.post_ID AND postmeta_source.meta_key = '_data_source')
			WHERE $wpdb->posts.post_type = 'jet-smart-filters'
				AND $wpdb->posts.post_status = '$status'
				$searchSql
				$filtrationSql";
		$count_result = $wpdb->get_results( $sql_count, ARRAY_A );

		$sql_total_count = "
		SELECT COUNT(*) as count
		FROM $wpdb->posts
			WHERE $wpdb->posts.post_type = 'jet-smart-filters'
				AND $wpdb->posts.post_status = 'publish'";
		$total_count_result = $wpdb->get_results( $sql_total_count, ARRAY_A );

		$sql_total_trash_count = "
		SELECT COUNT(*) as count
		FROM $wpdb->posts
			WHERE $wpdb->posts.post_type = 'jet-smart-filters'
				AND $wpdb->posts.post_status = 'trash'";
		$total_trash_count_result = $wpdb->get_results( $sql_total_trash_count, ARRAY_A );

		return array(
			'filters'           => $filters_result,
			'count'             => $count_result[0]['count'],
			'total_count'       => $total_count_result[0]['count'],
			'total_trash_count' => $total_trash_count_result[0]['count'],
		);
	}

	public function restore( $ids ) {

		global $wpdb;

		if ( $ids !== 'all' && ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		$sql = "
		UPDATE $wpdb->posts
		SET post_status = 'publish'
			WHERE $wpdb->posts.post_type = 'jet-smart-filters'
				AND $wpdb->posts.post_status = 'trash'";
		if ( $ids !== 'all' ) {
			$sql .= " AND $wpdb->posts.ID IN ('" . implode( "','", $ids ) . "')";
		}

		$result = $wpdb->query( $sql );

		return $result;
	}

	public function move_to_trash( $ids ) {

		global $wpdb;

		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		$sql = "
		UPDATE $wpdb->posts
		SET post_status = 'trash'
			WHERE $wpdb->posts.post_type = 'jet-smart-filters'
				AND $wpdb->posts.post_status = 'publish'
				AND $wpdb->posts.ID IN ('" . implode( "','", $ids ) . "')";

		$result = $wpdb->query( $sql );

		return $result;
	}

	public function delete( $ids ) {

		global $wpdb;

		if ( $ids !== 'all' && ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		if ( $ids === 'all' ) {
			$sql = "
			SELECT $wpdb->posts.ID
			FROM $wpdb->posts
				WHERE $wpdb->posts.post_type = 'jet-smart-filters'
					AND $wpdb->posts.post_status = 'trash'
				GROUP BY $wpdb->posts.ID";
			$result = $wpdb->get_results( $sql, ARRAY_A );

			$ids = array_map( function($row) {
				return $row['ID'];
			}, $result );
		}

		foreach ( $ids as $id ) {
			wp_delete_post( $id, true );
		}
	}
}
