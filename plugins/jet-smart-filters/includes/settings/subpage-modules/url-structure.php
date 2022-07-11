<?php
namespace Jet_Smart_Filters\Settings;

use Jet_Dashboard\Base\Page_Module as Page_Module_Base;
use Jet_Dashboard\Dashboard as Dashboard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class URL_Structure extends Page_Module_Base {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_page_slug() {
		return 'jet-smart-filters-url-structure-settings';
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
		return esc_html__( 'URL Structure Settings', 'jet-smart-filters' );
	}

	/**
	 * [get_category description]
	 * @return [type] [description]
	 */
	public function get_category() {
		return 'jet-smart-filters-settings';
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
			'jet-smart-filters-admin-css',
			jet_smart_filters()->plugin_url( 'assets/css/admin/admin.css' ),
			false,
			jet_smart_filters()->get_version()
		);

		wp_enqueue_script(
			'jet-smart-filters-admin-vue-components',
			jet_smart_filters()->plugin_url( 'assets/js/admin-vue-components.js' ),
			array( 'cx-vue-ui' ),
			jet_smart_filters()->get_version(),
			true
		);

		wp_localize_script(
			'jet-smart-filters-admin-vue-components',
			'jetSmartFiltersSettingsConfig',
			apply_filters( 'jet-smart-filters/admin/settings-page/localized-config', jet_smart_filters()->settings->get_settings_page_config() )
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

		$templates['jet-smart-filters-url-structure-settings'] = jet_smart_filters()->plugin_path( 'templates/admin/settings-templates/url-structure-settings.php' );

		return $templates;
	}
}
