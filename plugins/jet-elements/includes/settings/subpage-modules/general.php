<?php
namespace Jet_Elements\Settings;

use Jet_Dashboard\Base\Page_Module as Page_Module_Base;
use Jet_Dashboard\Dashboard as Dashboard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class General extends Page_Module_Base {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_page_slug() {
		return 'jet-elements-general-settings';
	}

	/**
	 * [get_subpage_slug description]
	 * @return [type] [description]
	 */
	public function get_parent_slug() {
		return 'settings-page';
	}

	/**
	 * [get_page_name description]
	 * @return [type] [description]
	 */
	public function get_page_name() {
		return esc_html__( 'General Settings', 'jet-dashboard' );
	}

	/**
	 * [get_category description]
	 * @return [type] [description]
	 */
	public function get_category() {
		return 'jet-elements-settings';
	}

	/**
	 * [get_page_link description]
	 * @return [type] [description]
	 */
	public function get_page_link() {
		return Dashboard::get_instance()->get_dashboard_page_url( $this->get_parent_slug(), $this->get_page_slug() );
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_style(
			'jet-elements-admin-css',
			jet_elements()->plugin_url( 'assets/css/jet-elements-admin.css' ),
			false,
			jet_elements()->get_version()
		);

		wp_enqueue_script(
			'jet-elements-admin-script',
			jet_elements()->plugin_url( 'assets/js/jet-elements-admin-vue-components.js' ),
			array( 'cx-vue-ui' ),
			jet_elements()->get_version(),
			true
		);

		wp_localize_script(
			'jet-elements-admin-script',
			'JetElementsSettingsConfig',
			apply_filters( 'jet-elements/admin/settings-page/localized-config', jet_elements_settings()->generate_frontend_config_data() )
		);

	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function page_config( $config = array(), $page = false, $subpage = false ) {

		$config['pageModule'] = $this->get_parent_slug();
		$config['subPageModule'] = $this->get_page_slug();

		return $config;
	}

	/**
	 * [page_templates description]
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $page = false, $subpage = false ) {

		$templates['jet-elements-general-settings'] = jet_elements()->plugin_path( 'templates/admin-templates/general-settings.php' );

		return $templates;
	}
}
