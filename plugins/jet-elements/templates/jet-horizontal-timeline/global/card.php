<?php
/**
 * Card item template
 */
$show_arrow = filter_var( $settings['show_card_arrows'], FILTER_VALIDATE_BOOLEAN );
$title_tag  = ! empty( $settings['item_title_size'] ) ? jet_elements_tools()->validate_html_tag( $settings['item_title_size'] ) : 'h5';
?>

<div class="jet-hor-timeline-item__card">
	<div class="jet-hor-timeline-item__card-inner">
		<?php
		$this->_render_image( $item_settings );
		echo $this->_loop_item( array( 'item_title' ) , '<' . $title_tag .' class="jet-hor-timeline-item__card-title">%s</' . $title_tag . '>' );
		echo $this->_loop_item( array( 'item_desc' ), '<div class="jet-hor-timeline-item__card-desc">%s</div>' );
		$this->_render_button( $item_settings );
		?>
	</div>
	<?php if ( $show_arrow ) { ?>
		<div class="jet-hor-timeline-item__card-arrow"></div>
	<?php } ?>
</div>
