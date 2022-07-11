<?php
/**
 * JetWooBuilder Products List widget loop item compare button template.
 */

if ( isset( $settings['show_compare'] ) && 'yes' === $settings['show_compare'] ) {
	do_action( 'jet-woo-builder/templates/jet-woo-products-list/compare-button', $settings );
}