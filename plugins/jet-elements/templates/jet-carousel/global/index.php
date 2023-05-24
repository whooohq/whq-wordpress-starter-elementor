<?php
/**
 * Advanced carousel template
 */
$layout      = $this->get_settings_for_display( 'item_layout' );
$equal_cols  = $this->get_settings_for_display( 'equal_height_cols' );
$cols_class  = ( 'true' === $equal_cols ) ? ' jet-equal-cols' : '';
$items_order = $this->get_settings_for_display( 'items_order' );

?><div class="jet-carousel-wrap<?php echo $cols_class; ?>">
	<?php
		if ( isset( $items_order ) && 'true' === $items_order ) {
			$this->_get_global_looped_template( esc_attr( $layout ) . '/items', 'items_list', array( $this, '_random_items_order' ) ); 
		} else {
			$this->_get_global_looped_template( esc_attr( $layout ) . '/items', 'items_list' ); 
		}
	?>
</div>
