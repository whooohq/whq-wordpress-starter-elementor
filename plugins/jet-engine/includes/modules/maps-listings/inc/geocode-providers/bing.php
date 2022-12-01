<?php
namespace Jet_Engine\Modules\Maps_Listings\Geocode_Providers;

use Jet_Engine\Modules\Maps_Listings\Module;

class Bing extends Base {

	public function build_api_url( $location = '' ) {

		$api_url = 'https://dev.virtualearth.net/REST/v1/Locations/';
		$api_key = Module::instance()->settings->get( 'bing_key' );

		// Do nothing if api key not provided
		if ( ! $api_key ) {
			return false;
		}

		return add_query_arg(
			array(
				'query' => urlencode( $location ),
				'key'   => urlencode( $api_key )
			),
			$api_url
		);
	}

	/**
	 * Build Reverse geocoding API URL for given coordinates point
	 * @return [type] [description]
	 */
	public function build_reverse_api_url( $point = array() ) {
		
		$api_url = 'https://dev.virtualearth.net/REST/v1/Locations/';
		$api_key = Module::instance()->settings->get( 'bing_key' );

		// Do nothing if api key not provided
		if ( ! $api_key ) {
			return false;
		}

		return add_query_arg(
			array(
				'key' => urlencode( $api_key )
			),
			$api_url . implode( ',', $point )
		);
	}

	/**
	 * Find location name in the reverse geocoding reponse data and return it
	 *
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function extract_location_from_response_data( $data = array() ) {

		if ( empty( $data['resourceSets'][0]['resources'][0] ) ) {
			return false;
		}

		$data = $data['resourceSets'][0]['resources'][0];

		return isset( $data['name'] ) ? $data['name'] : false;
	}

	/**
	 * Find coordinates in the reponse data and return it
	 *
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function extract_coordinates_from_response_data( $data = array() ) {

		if ( empty( $data['resourceSets'][0]['resources'][0] ) ) {
			return false;
		}

		$data = $data['resourceSets'][0]['resources'][0];

		$coord = isset( $data['point'] )
			? array( 'lat' => $data['point']['coordinates'][0], 'lng' => $data['point']['coordinates'][1] )
			: false;

		if ( ! $coord ) {
			return false;
		}

		return $coord;

	}

	/**
	 * Returns provider system slug
	 *
	 * @return [type] [description]
	 */
	public function get_id() {
		return 'bing';
	}

	/**
	 * Returns provider human-readable name
	 *
	 * @return [type] [description]
	 */
	public function get_label() {
		return __( 'Bing', 'jet-engine' );
	}

	/**
	 * Provider-specific settings fields template
	 *
	 * @return [type] [description]
	 */
	public function settings_fields() {
		?>
		<template
			v-if="'bing' === settings.geocode_provider"
		>
			<cx-vui-input
				label="<?php _e( 'Bing API Key', 'jet-engine' ); ?>"
				description="<?php _e( 'API key instructions', 'jet-engine' ); ?> - <a href='https://www.microsoft.com/en-us/maps/create-a-bing-maps-key' target='_blank'>https://www.microsoft.com/en-us/maps/create-a-bing-maps-key</a>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				@on-input-change="updateSetting( $event.target.value, 'bing_key' )"
				:value="settings.bing_key"
			></cx-vui-input>
		</template>
		<?php
	}

}
