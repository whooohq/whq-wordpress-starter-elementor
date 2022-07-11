<?php
/**
 * JetWooBuilder Products List widget loop item categories template.
 */

$cat_count  = isset( $settings['categories_count'] ) && ! empty( $settings['categories_count'] ) ? $settings['categories_count'] : 0;
$categories = jet_woo_builder_template_functions()->get_product_terms_list( 'product_cat', $cat_count );

if ( 'yes' !== $this->get_attr( 'show_cat' ) || ! $categories ) {
	return;
}
?>

<div class="jet-woo-product-categories">
	<ul><?php echo $categories; ?></ul>
</div>