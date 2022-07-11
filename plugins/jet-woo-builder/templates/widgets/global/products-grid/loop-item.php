<?php
/**
 * JetWooBuilder Products Grid widget loop item template.
 */

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$product_id    = $product->get_id();
$classes       = [ 'jet-woo-builder-product' ];
$overlay_class = '';
$data_url      = '';

if ( $enable_thumb_effect ) {
	$classes[] = 'jet-woo-thumb-with-effect';
}

if ( $carousel_enabled ) {
	$classes[] = 'swiper-slide';
}

if ( $clickable_item ) {
	$overlay_class = 'jet-woo-item-overlay-wrap';
	$data_url      = 'data-url="' . jet_woo_builder_template_functions()->get_product_permalink( $product ) . '"';
}
?>

<div class="jet-woo-products__item <?php echo implode( ' ', $classes ); ?>" data-product-id="<?php echo $product_id ?>">
	<div class="jet-woo-products__inner-box <?php echo $overlay_class; ?>" <?php echo $data_url; ?> <?php echo $data_target_attr; ?> >
		<?php include $this->get_product_preset_template(); ?>
	</div>
	<?php if ( $clickable_item ) : ?>
		<a href="<?php echo jet_woo_builder_template_functions()->get_product_permalink( $product ); ?>" class="jet-woo--item-overlay-link" <?php echo $target_attr; ?> ></a>
	<?php endif; ?>
</div>