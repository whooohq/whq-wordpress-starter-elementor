<?php
/**
 * Features list start template
 */
$settings     = $this->get_settings_for_display();
$is_edit_mode = jet_elements()->elementor()->editor->is_edit_mode();
$cols_classes = '';
$cols_classes .= ! empty( $settings['columns_widescreen'] ) ? 'col_w-' . $settings['columns_widescreen'] : '';
?>
<div class="jet-portfolio__list <?php echo $cols_classes; ?>"><?php
	if ( 'justify' === $this->get_settings_for_display( 'layout_type' ) ) {?>
		<div class="grid-sizer"></div><?php } ?>
