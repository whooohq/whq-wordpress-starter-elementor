<?php
/**
 * Timeline main template
 */

$settings      = $this->get_settings_for_display();
$mobile_layout = isset( $settings['mobile_vertical_layout'] ) ? $settings['mobile_vertical_layout'] : $settings['vertical_layout'];
$layout        = true === wp_is_mobile() ? $mobile_layout : $settings['vertical_layout'];

$this->add_render_attribute( 'wrapper', 'class',
	array(
		'jet-hor-timeline',
		'jet-hor-timeline--layout-' . esc_attr( $layout ),
		'jet-hor-timeline--align-' . esc_attr( $settings['horizontal_alignment'] ),
		'jet-hor-timeline--' . esc_attr( $settings['navigation_type'] ),
	)
);

$desktop_columns = ! empty( $settings['columns'] ) ? $settings['columns'] : 3;
$tablet_columns  = ! empty( $settings['columns_tablet'] ) ? $settings['columns_tablet'] : $desktop_columns;
$mobile_columns  = ! empty( $settings['columns_mobile'] ) ? $settings['columns_mobile'] : $tablet_columns;

$desktop_slides_to_scroll = ! empty( $settings['slides_to_scroll'] ) ? $settings['slides_to_scroll'] : 1;
$tablet_slides_to_scroll  = ! empty( $settings['slides_to_scroll_tablet'] ) ? $settings['slides_to_scroll_tablet'] : $desktop_slides_to_scroll;
$mobile_slides_to_scroll  = ! empty( $settings['slides_to_scroll_mobile'] ) ? $settings['slides_to_scroll_mobile'] : $tablet_slides_to_scroll;

$data_settings = array(
	'column' => array(
		'desktop' => $desktop_columns,
		'tablet'  => $tablet_columns,
		'mobile'  => $mobile_columns,
	),
	'slidesToScroll' => array(
		'desktop' => absint( $desktop_slides_to_scroll ),
		'tablet'  => absint( $tablet_slides_to_scroll ),
		'mobile'  => absint( $mobile_slides_to_scroll ),
	),
);

$this->add_render_attribute( 'wrapper', 'data-timeline-settings', esc_attr( json_encode( $data_settings ) ) );
?>

<div <?php $this->print_render_attribute_string( 'wrapper' ) ?>>
	<div class="jet-hor-timeline-inner">
		<div class="jet-hor-timeline-track">
			<?php $this->_get_global_looped_template( 'list-top', 'cards_list' ); ?>
			<?php $this->_get_global_looped_template( 'list-middle', 'cards_list' ); ?>
			<?php $this->_get_global_looped_template( 'list-bottom', 'cards_list' ); ?>
		</div>
	</div>
	<?php
		if ( 'arrows-nav' === $settings['navigation_type'] ) {
			echo jet_elements_tools()->get_carousel_arrow( array( $settings['arrow_type'], 'jet-prev-arrow jet-arrow-disabled' ) );
			echo jet_elements_tools()->get_carousel_arrow( array( $settings['arrow_type'], 'jet-next-arrow' ) );
		}
	?>
</div>