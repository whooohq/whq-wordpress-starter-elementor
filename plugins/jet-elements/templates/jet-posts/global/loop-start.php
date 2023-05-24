<?php
/**
 * Posts loop start template
 */

$classes = array(
	'jet-posts',
	'col-row',
	jet_elements_tools()->gap_classes( $this->get_attr( 'columns_gap' ), $this->get_attr( 'rows_gap' ) ),
);

$equal = $this->get_attr( 'equal_height_cols' );

if ( $equal ) {
	$classes[] = 'jet-equal-cols';
}

$elementor_widget = $this->elementor_widget;

?><div class="<?php echo implode( ' ', $classes ); ?>"><?php

if ( filter_var( $elementor_widget->get_settings_for_display( 'arrows' ), FILTER_VALIDATE_BOOLEAN )
	&& filter_var( $elementor_widget->get_settings_for_display( 'carousel_enabled' ), FILTER_VALIDATE_BOOLEAN ) ) {
	echo sprintf( '<div class="jet-posts__prev-arrow-%s jet-arrow prev-arrow">%s</div>', $elementor_widget->get_id(), $elementor_widget->_render_icon( 'prev_arrow', '%s', '', false ) );
	echo sprintf( '<div class="jet-posts__next-arrow-%s jet-arrow next-arrow">%s</div>', $elementor_widget->get_id(), $elementor_widget->_render_icon( 'next_arrow', '%s', '', false ) );
}
