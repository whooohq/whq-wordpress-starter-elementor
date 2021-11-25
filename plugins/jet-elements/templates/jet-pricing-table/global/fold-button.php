<?php
/**
 * Pricing table action button
 */

$settings = $this->get_settings_for_display();

$fold_button_icon          = $this->_get_icon( 'button_unfold_icon', '%s' );
$fold_button_icon_html     = sprintf( '<span class="pricing-table__fold-button-icon jet-elements-icon">%s</span>', $fold_button_icon );
$fold_button_icon_position = isset( $settings['fold_button_icon_position'] ) ? $settings['fold_button_icon_position'] : 'left';
$fold_button_text          = isset( $settings['button_unfold_text'] ) ? $settings['button_unfold_text'] : '';
$fold_button_text_html     = sprintf( '<span class="pricing-table__fold-button-text">%s</span>', $fold_button_text );

$this->add_render_attribute( 'fold_button', array(
	'class' => array(
		'pricing-table__fold-button',
		'elementor-button',
		'elementor-size-md',
	),
	'role'             => 'button',
	'data-unfold-text' => isset( $settings['button_unfold_text'] ) ? $settings['button_unfold_text'] : '',
	'data-fold-text'   => isset( $settings['button_fold_text'] ) ? $settings['button_fold_text'] : '',
	'data-fold-icon'   => isset( $settings['selected_button_fold_icon']['value'] ) ? htmlspecialchars( $this->_get_icon( 'button_fold_icon', '%s' ) ) : '',
	'data-unfold-icon' => isset( $settings['selected_button_fold_icon']['value'] ) ? htmlspecialchars( $this->_get_icon( 'button_unfold_icon', '%s' ) ) : '',
) );

if ( 'right' === $fold_button_icon_position ) {
	printf( '<div %1$s>%2$s%3$s</div>',
		$this->get_render_attribute_string( 'fold_button' ),
		$fold_button_text_html,
		$fold_button_icon_html
	);
} else {
	printf( '<div %1$s>%2$s%3$s</div>',
		$this->get_render_attribute_string( 'fold_button' ),
		$fold_button_icon_html,
		$fold_button_text_html
	);
}
