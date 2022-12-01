<?php
/**
 * Filter service class
 */

class Jet_Smart_Filters_Service_Filter {

	public $serialized_data_keys = array(
		'_source_manual_input',
		'_source_color_image_input',
		'_source_manual_input_range',
		'_ih_source_map',
		'_data_exclude_include'
	);

	public function get( $id ) {
		// Get data instance
		require_once jet_smart_filters()->plugin_path( 'admin/includes/data.php' );
		$data = Jet_Smart_Filters_Admin_Data::get_instance();

		global $wpdb;

		$output_data = false;

		$sql = "
		SELECT $wpdb->posts.ID, $wpdb->posts.post_title as title, $wpdb->posts.post_date as date, $wpdb->postmeta.meta_key, $wpdb->postmeta.meta_value
			FROM $wpdb->posts, $wpdb->postmeta
			WHERE $wpdb->posts.ID = '$id'
			AND $wpdb->posts.ID = $wpdb->postmeta.post_ID
			AND $wpdb->postmeta.meta_key IN ('" . implode( "','", $data->registered_settings_names() ) . "')
			AND $wpdb->posts.post_type='jet-smart-filters'";
		$sql_result = $wpdb->get_results( $sql, ARRAY_A );

		if ( count( $sql_result ) ) {
			$output_data = array();

			$output_data['ID']    = $sql_result[0]['ID'];
			$output_data['title'] = $sql_result[0]['title'];
			$output_data['date']  = $sql_result[0]['date'];

			foreach ( $sql_result as $filter ) {
				$key   = $filter['meta_key'];
				$value = $filter['meta_value'];

				if ( $value && in_array( $key, $this->serialized_data_keys ) ) {
					$value = unserialize($value);
				}

				$output_data[$key] = $value;
			}
		}

		return $output_data;
	}

	public function update( $id, $data ) {

		if ( $id === 'new' ) {
			return $this->add_new( $data );
		}

		$new_data = $this->process_data( $data );
		$new_data['ID'] = $id;

		return wp_update_post( $new_data );
	}

	public function add_new( $data ) {
		// Get data instance
		require_once jet_smart_filters()->plugin_path( 'admin/includes/data.php' );

		$new_data = $this->process_data( $data );

		$new_data['post_status'] = 'publish';
		$new_data['post_type']   = jet_smart_filters()->post_type->slug();

		if ( ! isset( $new_data['meta_input'] ) ) {
			$new_data['meta_input'] =  array();
		}

		$new_data['meta_input'] = array_merge(
			array(
				'_filter_type' => '',
				'_data_source' => ''
			),
			Jet_Smart_Filters_Admin_Data::get_instance()->default_settings_values(),
			$new_data['meta_input']
		);

		return wp_insert_post( $new_data );
	}

	private function process_data( $data ) {

		$processed_data = array();

		if ( isset( $data['title'] ) ) {
			$processed_data['post_title'] = $data['title'];

			unset( $data['title'] );
		}

		if ( isset( $data['date'] ) ) {
			$processed_data['post_date'] = $data['date'];

			unset( $data['date'] );
		}

		foreach ( $data as $key => $value ) {
			/* if ( in_array( $key, $this->serialized_data_keys ) ) {
				$value = serialize( $value );
			} */
			$processed_data['meta_input'][$key] = $value;
		}

		return $processed_data;
	}
}
