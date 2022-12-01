<?php
namespace Jet_Engine\Modules\Maps_Listings\Providers;

class Leaflet extends Base {

	/**
	 * Returns provider system slug
	 *
	 * @return [type] [description]
	 */
	public function get_id() {
		return 'leaflet';
	}

	/**
	 * Returns provider human-readable name
	 *
	 * @return [type] [description]
	 */
	public function get_label() {
		return __( 'Leaflet Maps', 'jet-engine' );
	}

	public function public_assets( $query, $settings, $render ) {

		$marker_clustering = isset( $settings['marker_clustering'] ) ? filter_var( $settings['marker_clustering'], FILTER_VALIDATE_BOOLEAN ) : true;

		if ( $marker_clustering ) {
			wp_enqueue_style(
				'jet-leaflet-markercluster',
				jet_engine()->plugin_url( 'includes/modules/maps-listings/assets/lib/leaflet-markercluster/markercluster.css' ),
				array(),
				jet_engine()->get_version()
			);

			wp_enqueue_style(
				'jet-leaflet-markerclusterdefault',
				jet_engine()->plugin_url( 'includes/modules/maps-listings/assets/lib/leaflet-markercluster/markerclusterdefault.css' ),
				array(),
				jet_engine()->get_version()
			);
		}

		wp_enqueue_style(
			'jet-leaflet-map',
			jet_engine()->plugin_url( 'includes/modules/maps-listings/assets/lib/leaflet/leaflet.css' ),
			array(),
			jet_engine()->get_version()
		);

		wp_enqueue_script(
			'jet-leaflet-map',
			jet_engine()->plugin_url( 'includes/modules/maps-listings/assets/lib/leaflet/leaflet.js' ),
			array(),
			jet_engine()->get_version(),
			true 
		);

		if ( $marker_clustering ) {
			wp_enqueue_script(
				'jet-leaflet-markercluster',
				jet_engine()->plugin_url( 'includes/modules/maps-listings/assets/lib/leaflet-markercluster/leaflet.markercluster.js' ),
				array(),
				jet_engine()->get_version(),
				true 
			);
		}

		wp_enqueue_script(
			'jet-leaflet-map-provider',
			jet_engine()->plugin_url( 'includes/modules/maps-listings/assets/js/public/leaflet-maps.js' ),
			array(),
			jet_engine()->get_version(),
			true 
		);

	}

	public function get_script_handles() {
		return array(
			'jet-leaflet-map',
			'jet-leaflet-markercluster',
			'jet-leaflet-map-provider',
		);
	}

}
