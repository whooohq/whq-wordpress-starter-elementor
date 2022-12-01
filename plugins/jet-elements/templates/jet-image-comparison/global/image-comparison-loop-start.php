<?php
/**
 * Image Comparison start template
 */
$settings = $this->get_settings();
$data_settings = $this->generate_setting_json();

$class_array[] = 'jet-image-comparison__instance';
$class_array[] = 'elementor-slick-slider';

$classes = implode( ' ', $class_array );

$dir = is_rtl() ? 'rtl' : 'ltr';

if ( filter_var( $settings['arrows'], FILTER_VALIDATE_BOOLEAN ) ) {
	echo sprintf( '<div class="jet-image-comparison__prev-arrow-%s jet-arrow prev-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'prev_arrow', '%s', '', false ) );
	echo sprintf( '<div class="jet-image-comparison__next-arrow-%s jet-arrow next-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'next_arrow', '%s', '', false ) );
}

?><div class="<?php echo $classes; ?>" <?php echo $data_settings; ?> dir="<?php echo $dir; ?>">
