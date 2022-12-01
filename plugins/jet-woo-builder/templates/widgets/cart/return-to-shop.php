<?php
/**
 * Cart Page Return to Shop widget Template.
 */
?>

<p class="return-to-shop">
	<a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" <?php $this->print_render_attribute_string( 'button' ); ?>>
		<?php echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Return to shop', 'woocommerce' ) ) ); ?>
	</a>
</p>
