<?php
/**
 * JetWooBuilder Single Sale Badge widget template.
 */

global $post, $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

$badge_text = jet_woo_builder()->macros->do_macros( $this->get_settings( 'single_badge_text' ) );
$badge_text = sprintf( '<span class="onsale">%s</span>', esc_html( $badge_text ) );

if ( $product->is_on_sale() ) {
	echo apply_filters( 'woocommerce_sale_flash', $badge_text, $post, $product );
}
