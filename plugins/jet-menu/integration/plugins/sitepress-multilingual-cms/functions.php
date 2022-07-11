<?php
/**
 * Jet Menu WPML compatibility
 */

add_filter( 'icl_ls_languages', 'wpml_jet_menu' );

/**
 * @param $links
 *
 * @return bool|mixed|mixed[]|void
 */
function wpml_jet_menu( $links ) {
	global $wp_query, $sitepress;

	if ( wpml_is_ajax() ) {

		$post_id = url_to_postid( $_SERVER['HTTP_REFERER'] );

		if ( $post_id ) {
			$wp_query = new WP_Query( 'p=' . $post_id ); // set the global $wp_query manually
			remove_filter( 'icl_ls_languages', 'wpml_jet_menu' ); // remove to avoid infinite recursion

			return $sitepress->get_ls_languages(); // let WPML recalculate the language switcher
		}
	}

	return $links;
}
