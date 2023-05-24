<?php
/**
 * Pricing table heading template
 */

$settings      = $this->get_settings_for_display();
$icon_position = ! empty( $settings['icon_position'] ) ? $settings['icon_position'] : 'inside';
$title_tag     = isset( $settings['title_html_tag'] ) ? jet_elements_tools()->validate_html_tag( $settings['title_html_tag'] ) : 'h2';
$subtitle_tag  = isset( $settings['subtitle_html_tag'] ) ? jet_elements_tools()->validate_html_tag( $settings['subtitle_html_tag'] ) : 'h4';

?>
<div class="pricing-table__heading">
	<?php if ( 'inside' === $icon_position ) {
		$this->_icon( 'icon', '<div class="pricing-table__icon"><div class="pricing-table__icon-box"><span class="jet-elements-icon">%s</span></div></div>' );
	} ?>
	<?php $this->_html( 'title', '<' . $title_tag . ' class="pricing-table__title">%s</' . $title_tag . '>' ); ?>
	<?php $this->_html( 'subtitle', '<' . $subtitle_tag . ' class="pricing-table__subtitle">%s</' . $subtitle_tag . '>' ); ?>
</div>