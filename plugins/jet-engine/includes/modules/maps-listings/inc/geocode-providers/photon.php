<?php
namespace Jet_Engine\Modules\Maps_Listings\Geocode_Providers;

class Photon extends Base {

	public function build_api_url( $location ) {
		return add_query_arg( 
			array( 'q' => urlencode( $location ), ),
			'https://photon.komoot.io/api/'
		);
	}

	/**
	 * Build Reverse geocoding API URL for given coordinates point
	 * @return [type] [description]
	 */
	public function build_reverse_api_url( $point = array() ) {
		return add_query_arg( 
			array(
				'lon' => $point['lng'],
				'lat' => $point['lat'],
			),
			'https://photon.komoot.io/reverse'
		);
	}

	/**
	 * Find coordinates in the reponse data and return it
	 *
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function extract_coordinates_from_response_data( $data = array() ) {

		if ( empty( $data['features'][0] ) ) {
			return false;
		}

		$data = $data['features'][0];

		$coord = isset( $data['geometry']['coordinates'] )
			? array( 'lat' => $data['geometry']['coordinates'][1], 'lng' => $data['geometry']['coordinates'][0] )
			: false;

		if ( ! $coord ) {
			return false;
		}

		return $coord;

	}

	public function get_prop( $properties, $key ) {
		return isset( $properties[ $key ] ) ? $properties[ $key ] : null;
	}

	/**
	 * Find location name in the reverse geocoding reponse data and return it
	 *
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public function extract_location_from_response_data( $data = array() ) {
		
		$properties = isset( $data['features'][0]['properties'] ) ? $data['features'][0]['properties'] : false;

		if ( ! $properties ) {
			return false;
		}

		return implode( ', ', array_filter( array( 
			$this->get_prop( $properties, 'name' ),
			$this->get_prop( $properties, 'street' ), 
			$this->get_prop( $properties, 'district' ), 
			$this->get_prop( $properties, 'city' ), 
			$this->get_prop( $properties, 'country' ),
		) ) );

	}

	/**
	 * Returns provider system slug
	 *
	 * @return [type] [description]
	 */
	public function get_id() {
		return 'photon';
	}

	/**
	 * Returns provider human-readable name
	 *
	 * @return [type] [description]
	 */
	public function get_label() {
		return __( 'Photon', 'jet-engine' );
	}

}
