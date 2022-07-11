<?php
/**
 * JetWooBuilder Products List widget loop item thumbnail template.
 */

$size           = $this->get_attr( 'thumb_size' );
$thumbnail      = jet_woo_builder_template_functions()->get_product_thumbnail( $size );
$thumbnail_link = jet_woo_builder_template_functions()->get_product_permalink( $product );
$open_link      = '';
$close_link     = '';

if ( 'yes' !== $this->get_attr( 'show_image' ) || null === $thumbnail ) {
	return;
}

if ( isset( $settings['is_linked_image'] ) && 'yes' === $settings['is_linked_image'] ) {
	$open_link  = '<a href="' . $thumbnail_link . '" ' . $target_attr . '>';
	$close_link = '</a>';
}
?>

<div class="jet-woo-product-thumbnail">
	<?php do_action( 'jet-woo-builder/templates/products-list/before-item-thumbnail' ); ?>

	<?php echo $open_link . $thumbnail . $close_link; ?>

	<?php do_action( 'jet-woo-builder/templates/products-list/after-item-thumbnail' ); ?>
</div>