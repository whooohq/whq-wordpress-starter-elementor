<?php
/**
 * Dropbar button template
 */

$settings    = $this->get_settings_for_display();
$button_type = isset( $settings['button_type'] ) ? $settings['button_type'] : 'text';

$this->add_render_attribute( 'button', 'class', 'jet-dropbar__button' );

if ( isset( $settings['button_hover_animation'] ) && $settings['button_hover_animation'] ) {
	$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . esc_attr( $settings['button_hover_animation'] ) );
}
?>

<button <?php $this->print_render_attribute_string( 'button' ); ?>><?php
	$this->_icon( 'button_before_icon', '<span class="jet-elements-icon jet-dropbar__button-icon jet-dropbar__button-icon--before">%s</span>' );
	if ( 'text' === $button_type ) {
		$this->_html( 'button_text', '<span class="jet-dropbar__button-text">%s</span>' );
	} else {
		echo $this->_button_image_item();
	}
	$this->_icon( 'button_after_icon', '<span class="jet-elements-icon jet-dropbar__button-icon jet-dropbar__button-icon--after">%s</span>' );
?></button>
