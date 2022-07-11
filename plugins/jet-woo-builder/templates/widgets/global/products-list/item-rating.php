<?php
/**
 * JetWooBuilder Products List widget loop item rating template.
 */

$rating = jet_woo_builder_template_functions()->get_product_rating();

if ( 'yes' !== $this->get_attr( 'show_rating' ) || '' === $rating ) {
	return;
}
?>

<div class="jet-woo-product-rating"><?php echo $rating; ?></div>