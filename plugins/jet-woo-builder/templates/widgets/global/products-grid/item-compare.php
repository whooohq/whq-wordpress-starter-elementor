<?php
/**
 * JetWooBuilder Products Grid widget loop item tags template.
 */

if ( isset( $settings['show_compare'] ) && 'yes' === $settings['show_compare'] ) {
	do_action( 'jet-woo-builder/templates/jet-woo-products/compare-button', $settings );
}
