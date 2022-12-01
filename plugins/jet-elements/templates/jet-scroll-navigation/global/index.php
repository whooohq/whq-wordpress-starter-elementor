<?php
/**
 * Scroll Navigation template
 */

$settings = $this->get_settings();
$data_settings = $this->generate_setting_json();
$position = $settings['position'];
$hint_show_type = isset( $settings['hint_show_type'] ) ? $settings['hint_show_type'] : 'show-active-hint';

$classes_list[] = 'jet-scroll-navigation';
$classes_list[] = 'jet-scroll-navigation--position-' . $position;
$classes_list[] = 'jet-scroll-navigation--' . $hint_show_type;

$classes = implode( ' ', $classes_list );

?><div class="<?php echo $classes; ?>" <?php echo $data_settings; ?>><?php
	$this->_get_global_looped_template( 'scroll-navigation', 'item_list' );
?></div>
