<?php
/**
 * Jet Menu Polylang Pro compatibility
 */

add_filter( 'jet-menu/public-manager/menu-location', 'jet_menu_polylang_pro_fix_location' );

/**
 * Fix menu location for Polylang Pro plugin
 *
 * @param  string $location Default location
 * @return string
 */
function jet_menu_polylang_pro_fix_location( $location ) {

	// Ensure Polylang is active.
	if ( ! function_exists( 'PLL' ) || ! PLL() instanceof PLL_Frontend ) {
		return $location;
	}

	$new_location = PLL()->nav_menu->combine_location( $location, PLL()->curlang );

	return $new_location;

}