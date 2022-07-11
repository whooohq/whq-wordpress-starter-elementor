<?php
/**
 * JetWooBuilder Products List widget loop item wishlist button template.
 */

if ( isset( $settings['show_wishlist'] ) && 'yes' === $settings['show_wishlist'] ) {
	do_action( 'jet-woo-builder/templates/jet-woo-products-list/wishlist-button', $settings );
}
