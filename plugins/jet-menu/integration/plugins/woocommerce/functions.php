<?php
/**
 * Jet Woo compatibility
 */
/**
 * Fix for nonce user logged out
 */
add_action( 'init', function() {

	if ( ! is_user_logged_in() ) {
		add_filter( 'nonce_user_logged_out', function ( $uid , $action = -1 ) {

			if ( 'wp_rest' === $action ) {
				return get_current_user_id();
			}

			return $uid;

		}, 99, 2 );
	}
} ) ;
