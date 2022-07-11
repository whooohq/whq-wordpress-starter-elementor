<?php
/**
 * JetWooBuilder Products Grid widget loop item SKU template.
 */

$sku = jet_woo_builder_template_functions()->get_product_sku();

if ( 'yes' !== $this->get_attr( 'show_sku' ) || empty( $sku ) ) {
	return;
}
?>

<div class="jet-woo-product-sku"><?php echo $sku; ?></div>