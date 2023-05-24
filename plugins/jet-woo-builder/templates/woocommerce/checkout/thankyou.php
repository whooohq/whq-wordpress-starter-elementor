<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/jet-woo-builder/woocommerce/checkout/thankyou.php.
 *
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$template = apply_filters( 'jet-woo-builder/current-template/template-id', jet_woo_builder()->woocommerce->get_custom_thankyou_template() );

jet_woo_builder()->admin_bar->register_post_item( $template );
?>

<div class="jet-woo-builder-woocommerce-thankyou woocommerce-order">

	<?php if ( $order ) :
		do_action( 'woocommerce_before_thankyou', $order->get_id() );

		echo jet_woo_builder_template_functions()->get_woo_builder_content( $template );
	else : ?>
		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
			<?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?>
		</p>
	<?php endif; ?>

</div>
