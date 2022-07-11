<?php
/**
 * JetWooBuilder Products Grid widget loop item add to cart button template.
 */

if ( 'yes' !== $this->get_attr( 'show_button' ) ) {
	return;
}

global $product;

$classes         = [ 'jet-woo-product-button' ];
$enable_quantity = 'yes' === $this->get_attr( 'show_quantity' );

if ( 'yes' === $this->get_attr( 'button_use_ajax_style' ) ) {
	array_push( $classes, 'is--default' );
}
?>

<div class="<?php echo implode( ' ', $classes ); ?>">
	<?php jet_woo_builder_template_functions()->get_product_add_to_cart_button( [], $enable_quantity ); ?>
</div>