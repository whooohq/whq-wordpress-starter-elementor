<?php
/**
 * Images Layout template
 */
$settings      = $this->get_settings_for_display();
$data_settings = $this->generate_setting_json();

$classes_list[] = 'layout-type-' . $settings['layout_type'];
$classes        = implode( ' ', $classes_list );
$callback       = null;

if ( ! empty( $settings['shuffle_image_list'] ) && filter_var( $settings['shuffle_image_list'], FILTER_VALIDATE_BOOLEAN ) ) {
	$callback = array( $this, 'shuffle_image_list' );
}
?>

<div class="jet-images-layout <?php echo $classes; ?>" <?php echo $data_settings; ?>>
	<?php $this->_get_global_looped_template( 'images-layout', 'image_list', $callback ); ?>
</div>
