<?php
/**
 * Testimonials template
 */

$classes_list[] = 'jet-testimonials';
$equal_cols     = $this->get_settings( 'equal_height_cols' );
$settings       = $this->get_settings_for_display();

if ( 'true' === $equal_cols ) {
	$classes_list[] = 'jet-equal-cols';
}

$classes = implode( ' ', $classes_list );
?>

<div class="<?php echo $classes; ?>">
	<?php
		if ( isset( $settings['shuffle'] ) && 'true' === $settings['shuffle'] ){
			$this->_get_global_looped_template( 'testimonials', 'item_list', array( $this, '_shuffle_items' ) );
		} else {
			$this->_get_global_looped_template( 'testimonials', 'item_list' );
		}
	?>
</div>
