<?php
/**
 * JetWooBuilder Products List widget loop item quick view button template.
 */


if ( isset( $settings['show_quickview'] ) && 'yes' === $settings['show_quickview'] ) {
	do_action( 'jet-woo-builder/templates/jet-woo-products-list/quickview-button', $settings );
}
