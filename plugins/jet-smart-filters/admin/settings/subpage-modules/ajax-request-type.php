<?php
use Jet_Dashboard\Base\Page_Module as Page_Module_Base;
use Jet_Dashboard\Dashboard as Dashboard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Jet_Smart_Filters_Admin_Setting_Page_Ajax_Request_Type extends Page_Module_Base {

	public function get_page_slug() {

		return 'jet-smart-filters-ajax-request-type';
	}

	public function get_parent_slug() {

		return 'settings-page';
	}

	public function get_page_name() {

		return esc_html__( 'Ajax Request Type', 'jet-smart-filters' );
	}


	public function get_category() {

		return 'jet-smart-filters-settings';
	}

	public function get_page_link() {

		return Dashboard::get_instance()->get_dashboard_page_url( $this->get_parent_slug(), $this->get_page_slug() );
	}


	public function enqueue_module_assets() {

		wp_enqueue_style(
			'jet-smart-filters-admin-css',
			jet_smart_filters()->plugin_url( 'admin/assets/css/settings-page.css' ),
			false,
			jet_smart_filters()->get_version()
		);

		wp_enqueue_script(
			'jet-smart-filters-admin-vue-components',
			jet_smart_filters()->plugin_url( 'admin/assets/js/jsf-admin-setting-pages.js' ),
			array( 'cx-vue-ui' ),
			jet_smart_filters()->get_version(),
			true
		);

		wp_localize_script(
			'jet-smart-filters-admin-vue-components',
			'jetSmartFiltersSettingsConfig',
			apply_filters( 'jet-smart-filters/admin/settings-page/localized-config', Jet_Smart_Filters_Admin_Settings::get_instance()->get_settings_page_config() )
		);
	}

	public function page_config( $config = array(), $page = false, $subpage = false ) {

		$config['pageModule'] = $this->get_parent_slug();
		$config['subPageModule'] = $this->get_page_slug();

		return $config;
	}

	public function page_templates( $templates = array(), $page = false, $subpage = false ) {

		$templates['jet-smart-filters-ajax-request-type'] = jet_smart_filters()->plugin_path( 'admin/templates/settings-templates/ajax-request-type.php' );

		return $templates;
	}
}
