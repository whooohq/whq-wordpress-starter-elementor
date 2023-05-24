<?php
/**
 * Features list start template
 */

$settings      = $this->get_settings_for_display();
$fold_enabled  = $this->_is_fold_enabled();
$fold_attr     = '';

if ( $fold_enabled ) {
	$fold_settings = array(
		'fold_enabled'   => $fold_enabled,
		'unfoldDuration' => isset( $settings['unfold_duration'] ) ? $settings['unfold_duration'] : '500ms',
		'foldDuration'   => isset( $settings['fold_duration'] ) ? $settings['fold_duration'] : '300ms',
		'unfoldEasing'   => isset( $settings['unfold_easing'] ) ? $settings['unfold_easing'] : 'easeOutBack',
		'foldEasing'     => isset( $settings['fold_easing'] ) ? $settings['fold_easing'] : 'easeOutSine',
	);

	$fold_attr = 'data-fold-settings="' . htmlspecialchars( json_encode( $fold_settings ) ) . '"';
}

$fold_classes = array( 'pricing-table__fold-mask' );

if ( ! $fold_enabled ) {
	$fold_classes[] = 'pricing-table-unfold-state';
}

?>
<div class="pricing-table__features">
	<div class="<?php echo join( ' ', $fold_classes ); ?>"<?php echo $fold_attr; ?>>