<?php
namespace Jet_Menu\Options_Manager;

class General_Options {

	public function init_options() {

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-cache-css', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-cache-css', false ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'svg-uploads', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'svg-uploads', 'enabled' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'use-template-cache', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'use-template-cache', 'true' ),
		) );

		$is_nextgen = ! get_option( jet_menu()->settings_manager->options_manager->options_slug ) ? 'true' : 'false';

		jet_menu()->settings_manager->options_manager->add_option( 'plugin-nextgen-edition', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'plugin-nextgen-edition', $is_nextgen ),
		) );

        $template = get_template();

        if ( file_exists( jet_menu()->plugin_path( "integration/themes/{$template}" ) ) ) {
            $disable_integration_option = 'jet-menu-disable-integration-' . $template;

	        jet_menu()->settings_manager->options_manager->add_option( $disable_integration_option, array(
		        'value' => jet_menu()->settings_manager->options_manager->get_option( $disable_integration_option, 'false' ),
	        ) );
        }
	}

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init_options') );
	}
}

