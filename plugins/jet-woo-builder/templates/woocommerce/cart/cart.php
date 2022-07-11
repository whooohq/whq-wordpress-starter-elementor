<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/jet-woo-builder/woocommerce/cart/cart.php.
 *
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

$template = apply_filters( 'jet-woo-builder/current-template/template-id', jet_woo_builder_integration_woocommerce()->get_current_cart_template() );

jet_woo_builder()->admin_bar->register_post_item( $template );

do_action( 'woocommerce_before_cart' );
?>

<div class="jet-woo-builder-woocommerce-cart">
	<?php echo jet_woo_builder_template_functions()->get_woo_builder_content( $template ); ?>
</div>

<?php do_action( 'woocommerce_after_cart' );
