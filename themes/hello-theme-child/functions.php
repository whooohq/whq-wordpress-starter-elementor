<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	// Cache bust
	$stylecss  = date('Ymd-Gis', filemtime( get_stylesheet_directory() . '/scripts.js' ));
	$scriptsjs = date('Ymd-Gis', filemtime( get_stylesheet_directory() . '/style.css' ));

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		$stylecss
	);

	wp_enqueue_script(
		'hello-elementor-child-script',
		get_stylesheet_directory_uri() . '/scripts.js',
		[
			'jquery'
		],
		$scriptsjs
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );
