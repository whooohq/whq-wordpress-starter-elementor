<?php
/**
 * JetWooBuilder Products Grid widget loop item tags template.
 */

$tags_count = isset( $settings['tags_count'] ) && ! empty( $settings['tags_count'] ) ? $settings['tags_count'] : 0;
$tags       = jet_woo_builder_template_functions()->get_product_terms_list( 'product_tag', $tags_count );

if ( 'yes' !== $this->get_attr( 'show_tag' ) || ! $tags ) {
	return;
}
?>

<div class="jet-woo-product-tags">
	<ul><?php echo $tags; ?></ul>
</div>