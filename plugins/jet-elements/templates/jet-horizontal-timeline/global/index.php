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
			if ( 'true' === $settings['arrow_custom_icons'] ) {
				echo jet_elements_tools()->get_image_by_url( $settings['custom_prev_arrow']['url'], array( 'class' => 'jet-arrow jet-prev-arrow jet-arrow-disabled' ), 'i' );
				echo jet_elements_tools()->get_image_by_url( $settings['custom_next_arrow']['url'], array( 'class' => 'jet-arrow jet-next-arrow' ), 'i' );
			} else {
				echo jet_elements_tools()->get_carousel_arrow( $settings['arrow_type'], 'prev', 'jet-prev-arrow jet-arrow-disabled' );
				echo jet_elements_tools()->get_carousel_arrow( $settings['arrow_type'], 'next', 'jet-next-arrow' );
			}
		}
	?>
</div>