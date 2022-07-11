<?php
/**
 * JetWooBuilder Products Grid widget loop item content template.
 */

$excerpt = jet_woo_builder_tools()->trim_text(
	jet_woo_builder_template_functions()->get_product_excerpt(),
	$this->get_attr( 'excerpt_length' ),
	$this->get_attr( 'excerpt_trim_type' ),
	'...'
);

if ( 'yes' !== $this->get_attr( 'show_excerpt' ) || empty( $excerpt ) ) {
	return;
}
?>

<div class="jet-woo-product-excerpt">
	<?php echo wp_kses_post( $excerpt ); ?>
</div>