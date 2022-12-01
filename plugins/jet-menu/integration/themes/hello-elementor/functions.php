<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'jet_menu_hello_styles', 0 );

/**
 * Enqueue astra compatibility styles
 *
 * @return void
 */
function jet_menu_hello_styles() {
	wp_enqueue_style(
		'jet-menu-hello',
		jet_menu()->integration_manager->get_theme_url( 'assets/css/style.css' ),
		array( 'hello-elementor-theme-style' ),
		jet_menu()->get_version()
	);
}
