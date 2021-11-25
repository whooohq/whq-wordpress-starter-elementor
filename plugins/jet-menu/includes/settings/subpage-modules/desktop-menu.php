<?php
namespace Jet_Menu\Settings;

use Jet_Dashboard\Base\Page_Module as Page_Module_Base;
use Jet_Dashboard\Dashboard as Dashboard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Desktop_Menu extends Page_Module_Base {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_page_slug() {
		return 'jet-menu-desktop-menu-settings';
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
		return esc_html__( 'Desktop Menu', 'jet-menu' );
	}

	/**
	 * [get_category description]
	 * @return [type] [description]
	 */
	public function get_category() {
		return 'jet-menu-settings';
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

		wp_enqueue_style( 'jet-menu-admin' );

		wp_enqueue_script(
			'jet-menu-settings-page-vue-components',
			jet_menu()->plugin_url( 'assets/admin/js/vue-components.js' ),
			array( 'cx-vue-ui' ),
			jet_menu()->get_version(),
			true
		);

		wp_localize_script(
			'jet-menu-settings-page-vue-components',
			'JetMenuOptionsPageConfig',
			apply_filters( 'jet-menu/admin/settings-page-config', jet_menu()->settings_manager->options_manager->get_options_page_config() )
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

		$templates['jet-menu-desktop-menu-settings'] = jet_menu()->plugin_path( 'templates/admin/settings-templates/desktop-menu-settings.php' );

		return $templates;
	}
}
