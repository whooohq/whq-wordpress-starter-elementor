<?php
/**
 * Products list widget loop item template.
 */

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$product_id = $product->get_id();
?>

<li class="jet-woo-products-list__item jet-woo-builder-product" data-product-id="<?php echo $product_id ?>">
	<div class="jet-woo-products-list__inner-box">
		<div class="jet-woo-products-list__item-img">
			<?php include $this->get_template( 'item-thumb' ); ?>
		</div>
		<div class="jet-woo-products-list__item-content">
			<?php
			include $this->get_template( 'item-categories' );
			include $this->get_template( 'item-sku' );
			include $this->get_template( 'item-title' );
			include $this->get_template( 'item-price' );
			include $this->get_template( 'item-stock-status' );
			include $this->get_template( 'item-button' );
			include $this->get_template( 'item-rating' );
			include $this->get_template( 'item-compare' );
			include $this->get_template( 'item-wishlist' );
			include $this->get_template( 'item-quick-view' );
			?>
		</div>
	</div>
</li>