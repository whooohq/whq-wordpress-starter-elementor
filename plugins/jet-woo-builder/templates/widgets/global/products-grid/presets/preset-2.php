<?php
/**
 * JetWooBuilder Products Grid widget loop item layout 2 template.
 */
?>

<div class="jet-woo-products__thumb-wrap">
	<?php include $this->get_template( 'item-thumb' ); ?>

	<div class="hovered-content">
		<?php include $this->get_template( 'item-button' ); ?>
	</div>
</div>

<?php
include $this->get_template( 'item-categories' );
include $this->get_template( 'item-sku' );
include $this->get_template( 'item-stock-status' );
include $this->get_template( 'item-title' );
include $this->get_template( 'item-price' );
include $this->get_template( 'item-content' );
include $this->get_template( 'item-rating' );
include $this->get_template( 'item-tags' );
?>

<div class="jet-woo-products-cqw-wrapper">
	<?php
	include $this->get_template( 'item-compare' );
	include $this->get_template( 'item-wishlist' );
	include $this->get_template( 'item-quick-view' );
	?>
</div>