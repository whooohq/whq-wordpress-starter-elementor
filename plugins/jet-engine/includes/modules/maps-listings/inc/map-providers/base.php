<?php
namespace Jet_Engine\Modules\Maps_Listings\Providers;

use Jet_Engine\Modules\Maps_Listings\Base_Provider;

abstract class Base extends Base_Provider {

	/**
	 * Hook name to register provider-specific settings
	 *
	 * @return [type] [description]
	 */
	public function settings_hook() {
		return 'jet-engine/maps-listing/settings/map-provider-controls';
	}

	public function public_init() {
		add_action( 'jet-engine/maps-listings/assets', array( $this, 'public_assets' ), 10, 3 );
	}

	public function register_public_assets() {
	}

	public function public_assets( $query, $settings, $render ) {
	}

	public function get_script_handles() {
		return array();
	}

	public function provider_settings() {
		return array();
	}

	public function prepare_render_settings( $settings = array() ) {
		return $settings;
	}

}
