<?php
/**
 * Progress Bar template
 */
$settings = $this->get_settings_for_display();

$this->add_render_attribute( 'main-container', 'class', array(
	'jet-progress-bar',
	'jet-progress-bar-' . $settings['progress_type'],
) );

$prefix = esc_html__( $settings['absolute_value_prefix'] );
$suffix = esc_html__( $settings['absolute_value_suffix'] );

$prefix_html = '<span class="jet-progress-bar__percent-prefix">' . $prefix . '&nbsp</span>';
$suffix_html = '<span class="jet-progress-bar__percent-suffix">&nbsp' . $suffix . '</span>';

if ( 'percent' === $settings['values_type'] ) {
	$this->add_render_attribute( 'main-container', 'data-percent', $settings['percent'] );
	$percent_html = '<span class="jet-progress-bar__percent-value">0</span><span class="jet-progress-bar__percent-suffix">&#37;</span>';
} else {
	$current_value = (int)$settings['absolute_value_curr'];
	$max_value     = (int)$settings['absolute_value_max'];

	if ( $max_value === 0 ) {
		return;
	}

	$percent       = ceil( $current_value / ( $max_value / 100 ) );
	$percent_html  = $prefix_html . '<span class="jet-progress-bar__percent-value">0/' . $max_value . '</span>' . $suffix_html;

	$this->add_render_attribute( 'main-container', 'data-percent', $percent );
	$this->add_render_attribute( 'main-container', 'data-current-value', $current_value );
	$this->add_render_attribute( 'main-container', 'data-max-value', $max_value );
}

$this->add_render_attribute( 'main-container', 'data-type', $settings['progress_type'] );

?>
<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
	<?php include $this->_get_type_template( $settings['progress_type'] ); ?>
</div>
