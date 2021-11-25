<?php
/**
 * Features list start template
 */
$settings     = $this->get_settings_for_display();
$is_edit_mode = jet_elements()->elementor()->editor->is_edit_mode();
//var_dump($settings);
//if ( $is_edit_mode ) {
	$cols_classes = '';
	$cols_classes .= ! empty( $settings['columns_widescreen'] ) ? 'col_w-' . $settings['columns_widescreen'] : '';
	// $col_d = ! empty( $settings['columns_desktop'] );
	// $col_l = ! empty( $settings['columns_laptop'] );
	// $col_e_t = ! empty( $settings['columns_extra_tablet'] );
	// $col_t = ! empty( $settings['columns_tablet'] );
	// $col_e_m = ! empty( $settings['columns_extra_mobile'] );
	// $col_m = ! empty( $settings['columns_mobile'] );
//}
?>
<div class="jet-portfolio__list <?php echo $cols_classes; ?>"><?php
	if ( 'justify' === $this->get_settings_for_display( 'layout_type' ) ) {?>
		<div class="grid-sizer"></div><?php } ?>
