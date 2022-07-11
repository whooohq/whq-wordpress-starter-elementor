<?php

// CCT backup
/* if ( $data['_data_source'] === 'cct' ) {
	$query_var = $data['_query_var'];

	if ( ! $query_var ) {
		continue;
	}

	$cct_module = jet_engine()->modules->get_module( 'custom-content-types' );

	if ( $cct_module ) {
		foreach ( $cct_module->instance->manager->get_items() as $cct ) {
			if ( ! isset( $cct['meta_fields'] ) ) {
				continue;
			}

			foreach ( $cct['meta_fields'] as $meta_field ) {
				if ( $meta_field['name'] === $query_var ) {
					$cct_name  = $cct['args']['slug'];
					$data_type = $meta_field['type'] === 'checkbox' ? 'serialized' : 'normal';

					if ( ! isset( $filters_data['cct'][$cct_name][$data_type] ) ) {
						$filters_data['cct'][$cct_name][$data_type] = array();
					}

					array_push( $filters_data['cct'][$cct_name][$data_type], $query_var );
				}
			}
		}
	}
} */
/* function get_cct_data( $cct_data ) {

	$cct_result = [];

	global $wpdb;
	foreach ( $cct_data as $cct_key => $cct_fields ) {
		$cct_table_name = $wpdb->prefix . 'jet_cct_' . $cct_key;

		foreach ( ['normal', 'serialized'] as $data_type ) {
			if ( ! isset( $cct_fields[$data_type] ) ) {
				continue;
			}

			foreach ( $cct_fields[$data_type] as $cct_field ) {
				$and_sql = '';

				if ( 'serialized' === $data_type ) {
					$and_sql .= "$cct_field REGEXP '[\'\"]?;s:4:\"true\"'";
				}

				if ( 'normal' === $data_type ) {
					$and_sql .= "$cct_field IN ('" . implode( "','", $data ) . "')";
				}

				$sql = "
				SELECT _ID as post_id, $cct_field as item_value
					FROM $cct_table_name
					WHERE $cct_field != '' AND $cct_field IS NOT NULL
					AND $and_sql
					ORDER BY _ID ASC";
				$result = $wpdb->get_results( $sql, ARRAY_A );

				foreach ( $result as $row ) {
					$cct_result[] = array(
						'post_id'    => $row['post_id'],
						'type'       => 'cct_' . $cct_key,
						'item_key'   => $cct_field,
						'item_value' => $row['item_value']
					);
				}
			}
		}
	}

	return $cct_result;

}
case 'cct':
$query_type = 'meta';
$query_var  = $source['_query_var'][0];
$cct_module = jet_engine()->modules->get_module( 'custom-content-types' );

if ( $cct_module ) {
	foreach ( $cct_module->instance->manager->get_items() as $cct ) {
		if ( ! isset( $cct['meta_fields'] ) ) {
			continue;
		}

		foreach ( $cct['meta_fields'] as $meta_field ) {
			if ( empty( $meta_field['options'] ) || $meta_field['name'] !== $query_var ) {
				continue;
			}

			$data[$query_var] = array();

			foreach ( $meta_field['options'] as $option ) {
				array_push( $data[$query_var], $option['key'] );
			}
		}
	}
}

break;
*/