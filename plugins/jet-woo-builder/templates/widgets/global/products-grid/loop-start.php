<?php
/**
 * JetWooBuilder Products Grid widget loop start template.
 */

$settings            = $this->get_settings();
$equal               = $this->get_attr( 'equal_height_cols' );
$target_attr         = 'yes' === $this->get_attr( 'open_new_tab' ) ? 'target="_blank"' : '';
$data_target_attr    = 'yes' === $this->get_attr( 'open_new_tab' ) ? 'data-target="_blank"' : '';
$carousel_enabled    = isset( $settings['carousel_enabled'] ) ? filter_var( $settings['carousel_enabled'], FILTER_VALIDATE_BOOLEAN ) : false;
$hover_on_touch      = filter_var( $this->get_attr( 'hover_on_touch' ), FILTER_VALIDATE_BOOLEAN );
$clickable_item      = filter_var( $this->get_attr( 'clickable_item' ), FILTER_VALIDATE_BOOLEAN );
$enable_thumb_effect = filter_var( jet_woo_builder_settings()->get( 'enable_product_thumb_effect' ), FILTER_VALIDATE_BOOLEAN );

$classes = [
	'jet-woo-products',
	'jet-woo-products--' . $this->get_attr( 'presets' ),
	$carousel_enabled ? 'swiper-wrapper' : 'col-row',
	jet_woo_builder_tools()->gap_classes( $this->get_attr( 'columns_gap' ), $this->get_attr( 'rows_gap' ) ),
	$equal ? 'jet-equal-cols' : '',
];

$attributes = apply_filters( 'jet-woo-builder/templates/jet-woo-products/widget-attributes', '', $settings, $query, $this );

printf(
	'<div class="%s" data-mobile-hover="%s" %s>',
	implode( ' ', $classes ),
	$hover_on_touch,
	$attributes
);