<?php
/**
 * Cart list template
 */

$close_button_html = $this->__get_icon( 'cart_list_close_icon', '<div class="jet-blocks-cart__close-button jet-blocks-icon">%s</div>' );
?>
<div class="jet-blocks-cart__list">
	<?php echo $close_button_html; ?>
	<?php $this->__html( 'cart_list_label', '<h4 class="jet-blocks-cart__list-title">%s</h4>' ); ?>
	<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
</div>
