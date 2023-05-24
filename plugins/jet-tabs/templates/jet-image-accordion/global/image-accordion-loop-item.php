<?php
/**
 * Image accordion list item template
 */
$settings = $this->get_settings_for_display();
$item_html_tag = ! empty( $settings['item_html_tag'] ) ? $settings['item_html_tag'] : 'h5';
?>
<div class="jet-image-accordion__item" role="tab" aria-selected="false" tabindex="0">
<?php echo $this->__loop_item_image(); ?>
	<div class="jet-image-accordion__content"><?php
		echo $this->__loop_item( array( 'item_title' ), '<' . $item_html_tag . ' class="jet-image-accordion__title">%s</' . $item_html_tag . '>' );
		echo $this->__loop_item( array( 'item_desc' ), '<div class="jet-image-accordion__desc">%s</div>' );
		echo $this->__generate_action_button();?></div>
	<div class="jet-image-accordion__item-loader"><span></span></div>
</div>
