<?php
/**
 * Switcher Preset-1
 */

$this->add_render_attribute( 'jet_switcher_control_disable', array(
	'class'         => array(
		'jet-switcher__control',
		'jet-switcher__control--disable',
	),
	'role'          => 'button',
	'tabindex'      => 0,
	'aria-controls' => 'jet-switcher-content-disable-' . $this->get_id(),
	'aria-expanded' => ! $initial_state ? 'true' : 'false',
	'aria-label'    => ! empty( $widget_settings['disable_label'] ) ? $widget_settings['disable_label'] : '',
) );

$disable_label_html = ! empty( $widget_settings['disable_label'] ) ? sprintf( '<div class="jet-switcher__label-text">%1$s</div>', $widget_settings['disable_label'] ) : '';

$this->add_render_attribute( 'jet_switcher_control_enable', array(
	'class'         => array(
		'jet-switcher__control',
		'jet-switcher__control--enable',
	),
	'role'          => 'button',
	'tabindex'      => 0,
	'aria-controls' => 'jet-switcher-content-enable-' . $this->get_id(),
	'aria-expanded' => $initial_state ? 'true' : 'false',
	'aria-label'    => ! empty( $widget_settings['enable_label'] ) ? $widget_settings['enable_label'] : '',
) );

$enable_label_html = ! empty( $widget_settings['disable_label'] ) ? sprintf( '<div class="jet-switcher__label-text">%1$s</div>', $widget_settings['enable_label'] ) : '';

?>
<div class="jet-switcher__control-instance">
	<div class="jet-switcher__control-handler"><span></span></div>
	<div <?php echo $this->get_render_attribute_string( 'jet_switcher_control_disable' ); ?>><?php
		echo $disable_label_html; ?></div>
	<div <?php echo $this->get_render_attribute_string( 'jet_switcher_control_enable' ); ?>><?php
		echo $enable_label_html; ?></div>
</div>

