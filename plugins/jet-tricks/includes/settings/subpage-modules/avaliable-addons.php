<?php
namespace Jet_Tricks\Settings;

use Jet_Dashboard\Base\Page_Module as Page_Module_Base;
use Jet_Dashboard\Dashboard as Dashboard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Avaliable_Addons extends Page_Module_Base {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_page_slug() {
		return 'jet-tricks-avaliable-addons';
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
		return esc_html__( 'Widgets & Extensions', 'jet-dashboard' );
	}

	/**
	 * [get_category description]
	 * @return [type] [description]
	 */
	public function get_category() {
		return 'jet-tricks-settings';
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
			'jet-tricks-admin-css',
			jet_tricks()->plugin_url( 'assets/css/jet-tricks-admin.css' ),
			false,
			jet_tricks()->get_version()
		);

		wp_enqueue_script(
			'jet-tricks-admin-vue-components',
			jet_tricks()->plugin_url( 'assets/js/admin-vue-components.js' ),
			array( 'cx-vue-ui' ),
			jet_tricks()->get_version(),
			true
		);

		wp_localize_script(
			'jet-tricks-admin-vue-components',
			'jetTricksSettingsConfig',
			apply_filters( 'jet-tricks/admin/settings-page/localized-config', jet_tricks_settings()->get_frontend_config_data() )
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

		$templates['jet-tricks-avaliable-addons'] = jet_tricks()->plugin_path( 'templates/admin-templates/avaliable-addons-settings.php' );

		return $templates;
	}
}
