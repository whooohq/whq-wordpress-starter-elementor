<?php
/**
 * Slider start template
 */

$settings = $this->get_settings_for_display();
$class_array[] = 'jet-slider__items';
$class_array[] = 'sp-slides';

$classes = implode( ' ', $class_array );

?>

<div class="slider-pro"><?php
	echo sprintf( '<div class="jet-slider__arrow-icon-%s hidden-html">%s</div>', $this->get_id(), $this->_render_icon( 'slider_navigation_icon_arrow', '%s', '', false ) );
	echo sprintf( '<div class="jet-slider__fullscreen-icon-%s hidden-html">%s</div>', $this->get_id(), $this->_render_icon( 'slider_fullscreen_icon', '%s', '', false ) );
	?><div class="<?php echo $classes; ?>">

