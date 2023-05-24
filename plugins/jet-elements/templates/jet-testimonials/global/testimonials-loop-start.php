<?php
/**
 * Testimonials start template
 */
$settings = $this->get_settings();
$data_settings = $this->generate_setting_json();

$use_comment_corner = $this->get_settings( 'use_comment_corner' );

$class_array[] = 'jet-testimonials__instance';
$class_array[] = 'elementor-slick-slider';

if ( filter_var( $use_comment_corner, FILTER_VALIDATE_BOOLEAN ) ) {
	$class_array[] = 'jet-testimonials--comment-corner';
}

$classes = implode( ' ', $class_array );

$dir = is_rtl() ? 'rtl' : 'ltr';

?><div class="<?php echo $classes; ?>" <?php echo $data_settings; ?> dir="<?php echo $dir; ?>"><?php
if ( filter_var(  $this->get_settings_for_display( 'arrows' ), FILTER_VALIDATE_BOOLEAN ) ) {
	echo sprintf( '<div class="jet-testimonial__prev-arrow-%s jet-arrow prev-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'prev_arrow', '%s', '', false ) );
	echo sprintf( '<div class="jet-testimonial__next-arrow-%s jet-arrow next-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'next_arrow', '%s', '', false ) );
}
