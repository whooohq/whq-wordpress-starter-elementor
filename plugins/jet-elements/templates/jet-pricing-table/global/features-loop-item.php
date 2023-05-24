<?php
/**
 * Features list item template
 */

$classes      = 'pricing-feature-' . $this->_loop_item( array( '_id' ) );
$classes     .= ' ' . $this->_loop_item( array( 'item_included' ) );
$tooltip      = $this->_loop_item( array( 'item_tooltip' ) ) ;
$tooltip_attr = ! empty( $tooltip ) ? ' data-tippy-content="' . $tooltip . '"' : '';

if ( $fold_enabled ) {
	$fold_visible_number = isset( $settings['fold_items_show'] ) ? $settings['fold_items_show'] : 1;

	$classes .= ( $this->_processed_index <= ( $fold_visible_number - 1 ) ) ? ' fold_visible' : '';
	$classes .= ( $this->_processed_index === ( $fold_visible_number - 1 ) ) ? ' fold_visible_last' : '';
}
?>
<div class="pricing-feature <?php echo $classes; ?>">
	<div class="pricing-feature__inner"<?php echo $tooltip_attr; ?>><?php
		echo $this->_pricing_feature_icon();
		printf( '<span class="pricing-feature__text">%s</span>', $this->_loop_item( array( 'item_text' ) ) );
	?></div>
</div>