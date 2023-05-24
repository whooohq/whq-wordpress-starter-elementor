<?php
/**
 * Animated text list template
 */
$settings      = $this->get_settings_for_display();
$data_settings = $this->generate_setting_json();

$classes[] = 'jet-animated-text';
$classes[] = 'jet-animated-text--effect-' . $settings['animation_effect'];
$tag       = ! empty( $settings['html_tag'] ) ? $settings['html_tag'] : 'div';
$tag       = jet_elements_tools()->validate_html_tag( $tag );

if ( ! empty( $settings['animated_text_link']['url'] ) ) {
	$this->_add_link_attributes( 'button', $settings['animated_text_link'] );
	$this->add_render_attribute( 'button', 'class', implode( ' ', $classes ) );
	$this->add_render_attribute( 'button', 'class', 'jet-animated-text--link' );
	$tag = 'a';
} else {
	$this->add_render_attribute( 'button', 'class', implode( ' ', $classes ) );
}
?>

<<?php echo $tag; ?> <?php echo $this->get_render_attribute_string( 'button' );?> <?php echo $data_settings; ?>>
	<?php $this->_glob_inc_if( 'before-text', array( 'before_text_content' ) ); ?>
	<?php $this->_get_global_looped_template( 'animated-text', 'animated_text_list' ); ?>
	<?php $this->_glob_inc_if( 'after-text', array( 'after_text_content' ) ); ?>
</<?php echo $tag; ?>>