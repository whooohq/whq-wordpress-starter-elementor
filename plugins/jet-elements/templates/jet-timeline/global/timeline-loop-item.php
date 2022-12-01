<?php
/**
 * Timeline list item template
 */
$settings       = $this->get_settings_for_display();
$item_settings  = $this->_processed_item;
$image_position = isset( $item_settings['item_image_position'] ) ? $item_settings['item_image_position'] : 'inside';

$classes = array(
	'jet-timeline-item',
	$settings['animate_cards'],
	'elementor-repeater-item-' . $item_settings['_id'],
	'jet-timeline-item--image-' . $image_position
);

$title_tag = jet_elements_tools()->validate_html_tag( $settings['title_tag'] );

$item_meta_attr = $this->get_item_inline_editing_attributes( 'item_meta', 'cards_list', $this->_processed_item_index, 'timeline-item__meta-content' );
$item_title_attr = $this->get_item_inline_editing_attributes( 'item_title', 'cards_list', $this->_processed_item_index, 'timeline-item__card-title' );
$item_desc_attr = $this->get_item_inline_editing_attributes( 'item_desc', 'cards_list', $this->_processed_item_index, 'timeline-item__card-desc' );

$classes = implode( ' ', $classes );
$this->_processed_item_index += 1;
?>
<div class="<?php echo $classes ?>">
	<div class="timeline-item__card">
		<div class="timeline-item__card-inner">
				<?php
					if ( 'yes' === $item_settings['show_item_image'] && 'inside_after' !== $image_position) {
						echo $this->_get_timeline_image();
					}
				?>
				<div class="timeline-item__card-content">
					<?php
						echo '<div class="timeline-item__meta">';
						echo $this->_loop_item( array( 'item_meta' ), '<div ' . $item_meta_attr . '>%s</div>' );
						echo '</div>';
						echo $this->_loop_item( array( 'item_title' ) , '<' . $title_tag . ' ' . $item_title_attr . '>%1s</' . $title_tag . '>' );
						echo $this->_loop_item( array( 'item_desc' ), '<div ' . $item_desc_attr . '>%s</div>' );
						echo $this->_get_timeline_button();
					?>
				</div>
				<?php
					if ( 'yes' === $item_settings['show_item_image'] && 'inside_after' === $image_position ) {
						echo $this->_get_timeline_image();
					}
				?>
		</div>
		<div class="timeline-item__card-arrow"></div>
	</div>
	<?php
		$this->_generate_point_content( $item_settings );
		echo '<div class="timeline-item__meta">';
		if ( 'yes' === $item_settings['show_item_image'] && $image_position === 'outside_before' && 'center' === $settings['horizontal_alignment'] ) {
			echo $this->_get_timeline_image();
		}
		echo $this->_loop_item( array( 'item_meta' ), '<div ' . $item_meta_attr . '>%s</div>' );
		if ( 'yes' === $item_settings['show_item_image'] && $image_position === 'outside_after' && 'center' === $settings['horizontal_alignment'] ) {
			echo $this->_get_timeline_image();
		}
		echo '</div>';
	?>
</div>