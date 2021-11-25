<?php
/**
 * Pricing table main template
 */

$settings      = $this->get_settings_for_display();
$icon_position = ! empty( $settings['icon_position'] ) ? $settings['icon_position'] : 'inside';

$has_tooltips  = $this->_pricing_features_items_tooltips_check();
$tooltips_attr = '';

if ( $has_tooltips ) {
	$tooltips_settings = array(
		'tooltipPlacement'    => isset( $settings['tooltip_placement'] ) ? $settings['tooltip_placement'] : 'top',
		'tooltipArrow'        => filter_var( $settings['tooltip_arrow'], FILTER_VALIDATE_BOOLEAN ),
		'tooltipTrigger'      => isset( $settings['tooltip_trigger'] ) ? $settings['tooltip_trigger'] : 'mouseenter',
		'tooltipShowDuration' => isset( $settings['tooltip_show_duration'] ) ? $settings['tooltip_show_duration'] : '500',
		'tooltipHideDuration' => isset( $settings['tooltip_hide_duration'] ) ? $settings['tooltip_hide_duration'] : '300',
		'tooltipDelay'        => isset( $settings['tooltip_delay'] ) ? $settings['tooltip_delay'] : '0',
		'tooltipDistance'     => isset( $settings['tooltip_distance'] ) ? $settings['tooltip_distance'] : '15',
		'tooltipAnimation'    => isset( $settings['tooltip_animation'] ) ? $settings['tooltip_animation'] : 'shift-toward',
	);

	$tooltips_attr .= ' data-tooltips-settings="' . htmlspecialchars( json_encode( $tooltips_settings ) ) . '"';
}

?>
<div class="pricing-table <?php $this->_html( 'featured', 'featured-table' ); ?>"<?php echo $tooltips_attr; ?>>
	<?php if ( 'inside' === $icon_position ) {
		$this->_glob_inc_if( 'heading', array( $this->_new_icon_prefix . 'icon', 'icon', 'title', 'subtitle' ) );
	} else {
		$this->_icon( 'icon', '<div class="pricing-table__icon"><div class="pricing-table__icon-box"><span class="jet-elements-icon">%s</span></div></div>' );
		$this->_glob_inc_if( 'heading', array( 'title', 'subtitle' ) );
	} ?>
	<?php $this->_glob_inc_if( 'price', array( 'price_prefix', 'price', 'price_suffix' ) ); ?>
	<?php $this->_get_global_looped_template( 'features', 'features_list' ); ?>
	<?php $this->_glob_inc_if( 'action', array( 'button_before', 'button_url', 'button_text', 'button_after' ) ); ?>
	<?php $this->_glob_inc_if( 'badge', array( 'featured' ) ); ?>
</div>
