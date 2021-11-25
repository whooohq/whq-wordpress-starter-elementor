<?php
namespace Jet_Menu\Options_Manager;

class Desktop_Menu_Options {

	public function init_options() {

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-animation', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-animation', 'fade' ),
			'options' => array(
				array(
					'label' => esc_html__( 'None', 'jet-menu' ),
					'value' => 'none',
				),
				array(
					'label' => esc_html__( 'Fade', 'jet-menu' ),
					'value' => 'fade',
				),
				array(
					'label' => esc_html__( 'Move Up', 'jet-menu' ),
					'value' => 'move-up',
				),
				array(
					'label' => esc_html__( 'Move Down', 'jet-menu' ),
					'value' => 'move-down',
				)
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-roll-up', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-roll-up', 'true' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-show-for-device', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-show-for-device', 'both' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Desktop and mobile view', 'jet-menu' ),
					'value' => 'both',
				),
				array(
					'label' => esc_html__( 'Desktop view on all devices', 'jet-menu' ),
					'value' => 'desktop',
				),
				array(
					'label' => esc_html__( 'Mobile view on all devices', 'jet-menu' ),
					'value' => 'mobile',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mega-ajax-loading', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mega-ajax-loading', false ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mouseleave-delay', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mouseleave-delay', 500 ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-width-type', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-width-type', 'container' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Width same as main container width', 'jet-menu' ),
					'value' => 'container',
				),
				array(
					'label' => esc_html__( 'Width same as total items width', 'jet-menu' ),
					'value' => 'items',
				),
				array(
					'label' => esc_html__( 'Width same as Custom css selector width', 'jet-menu' ),
					'value' => 'selector',
				)
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-selector-width-type', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-selector-width-type', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-open-sub-type', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-open-sub-type', 'hover' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Hover', 'jet-menu' ),
					'value' => 'hover',
				),
				array(
					'label' => esc_html__( 'Click', 'jet-menu' ),
					'value' => 'click',
				),
			),
		) );

		//Menu Container Styles
		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-container-alignment', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-container-alignment', 'flex-end' ),
			'options' => jet_menu()->settings_manager->options_manager->get_aligment_select_options(),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-min-width', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-min-width', 0 ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mega-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mega-padding', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_background_options( 'jet-menu-container' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-container' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-container' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mega-border-radius', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mega-border-radius', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-inherit-first-radius', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-inherit-first-radius', false ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-inherit-last-radius', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-inherit-last-radius', false ),
		) );

		// Sub Panels Settings
		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-panel-width-simple', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-panel-width-simple', 200 ),
		) );

		jet_menu()->settings_manager->options_manager->add_background_options( 'jet-menu-sub-panel-simple' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub-panel-simple' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-sub-panel-simple' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-panel-border-radius-simple', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-panel-border-radius-simple', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-panel-padding-simple', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-panel-padding-simple', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-panel-margin-simple', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-panel-margin-simple', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_background_options( 'jet-menu-sub-panel-mega' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub-panel-mega' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-sub-panel-mega' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-panel-border-radius-mega', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-panel-border-radius-mega', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-panel-padding-mega', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-panel-padding-mega', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-panel-margin-mega', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-panel-margin-mega', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-max-width', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-max-width', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-top-menu' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-show-top-menu-desc', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-show-top-menu-desc', false ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-top-menu-desc' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-text-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-text-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-desc-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-desc-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-icon-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-icon-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-arrow-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-arrow-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_background_options( 'jet-menu-item' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-item' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-first-item' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-last-item' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-item' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-border-radius', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-border-radius', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-padding', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-margin', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-margin', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-text-color-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-text-color-hover', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-desc-color-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-desc-color-hover', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-icon-color-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-icon-color-hover', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-arrow-color-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-arrow-color-hover', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_background_options( 'jet-menu-item-hover' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-item-hover' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-first-item-hover' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-last-item-hover' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-item-hover' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-border-radius-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-border-radius-hover', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-padding-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-padding-hover', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-margin-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-margin-hover', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-text-color-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-text-color-active', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-desc-color-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-desc-color-active', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-icon-color-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-icon-color-active', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-arrow-color-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-arrow-color-active', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_background_options( 'jet-menu-item-active' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-item-active' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-first-item-active' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-last-item-active' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-item-active' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-border-radius-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-border-radius-active', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-padding-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-padding-active', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-item-margin-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-margin-active', jet_menu_tools()->get_default_dimensions() ),
		) );

		// Sub Level Items
		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-sub-menu' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-show-sub-menu-desc', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-show-sub-menu-desc', false ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-sub-menu-desc' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-text-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-text-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-desc-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-desc-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-icon-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-icon-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-arrow-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-arrow-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_background_options( 'jet-menu-sub' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub-first' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub-last' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-sub' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-border-radius', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-border-radius', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-padding', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-margin', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-margin', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-text-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-text-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-text-color-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-text-color-hover', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-desc-color-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-desc-color-hover', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-icon-color-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-icon-color-hover', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-arrow-color-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-arrow-color-hover', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_background_options( 'jet-menu-sub-hover' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub-hover' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub-first-hover' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub-last-hover' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-sub-hover' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-border-radius-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-border-radius-hover', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-padding-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-padding-hover', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-margin-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-margin-hover', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-text-color-hover', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-text-color-hover', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-text-color-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-text-color-active', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-desc-color-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-desc-color-active', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-icon-color-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-icon-color-active', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-arrow-color-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-arrow-color-active', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_background_options( 'jet-menu-sub-active' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub-active' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub-first-active' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub-last-active' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-sub-active' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-border-radius-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-border-radius-active', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-padding-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-padding-active', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-margin-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-margin-active', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-text-color-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-text-color-active', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-icon-size', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-icon-size', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-icon-margin', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-icon-margin', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-icon-ver-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-icon-ver-position', 'center' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Top', 'jet-menu' ),
					'value' => 'top',
				),
				array(
					'label' => esc_html__( 'Bottom', 'jet-menu' ),
					'value' => 'bottom',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-icon-hor-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-icon-hor-position', '' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Left', 'jet-menu' ),
					'value' => 'left',
				),
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Right', 'jet-menu' ),
					'value' => 'right',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-icon-order', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-icon-order', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-icon-size', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-icon-size', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-icon-margin', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-icon-margin', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-icon-ver-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-icon-ver-position', '' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Top', 'jet-menu' ),
					'value' => 'top',
				),
				array(
					'label' => esc_html__( 'Bottom', 'jet-menu' ),
					'value' => 'bottom',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-icon-hor-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-icon-hor-position', '' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Left', 'jet-menu' ),
					'value' => 'left',
				),
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Right', 'jet-menu' ),
					'value' => 'right',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-icon-order', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-icon-order', '' ),
		) );

		// Badge Styles
		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-badge-text-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-badge-text-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-menu-top-badge' );

		jet_menu()->settings_manager->options_manager->add_background_options( 'jet-menu-top-badge-bg' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-top-badge' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-top-badge' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-badge-border-radius', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-badge-border-radius', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-badge-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-badge-padding', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-badge-margin', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-badge-margin', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-badge-ver-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-badge-ver-position', '' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Top', 'jet-menu' ),
					'value' => 'top',
				),
				array(
					'label' => esc_html__( 'Bottom', 'jet-menu' ),
					'value' => 'bottom',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-badge-hor-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-badge-hor-position', '' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Left', 'jet-menu' ),
					'value' => 'left',
				),
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Right', 'jet-menu' ),
					'value' => 'right',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-badge-order', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-badge-order', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-badge-hide', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-badge-hide', false ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-badge-text-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-badge-text-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-menu-sub-badge' );

		jet_menu()->settings_manager->options_manager->add_background_options( 'jet-menu-sub-badge-bg' );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-sub-badge' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-sub-badge' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-badge-border-radius', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-badge-border-radius', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-badge-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-badge-padding', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-badge-margin', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-badge-margin', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-badge-ver-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-badge-ver-position', '' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Top', 'jet-menu' ),
					'value' => 'top',
				),
				array(
					'label' => esc_html__( 'Bottom', 'jet-menu' ),
					'value' => 'bottom',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-badge-hor-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-badge-hor-position', '' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Left', 'jet-menu' ),
					'value' => 'left',
				),
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Right', 'jet-menu' ),
					'value' => 'right',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-badge-order', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-badge-order', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-badge-hide', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-badge-hide', false ),
		) );

		// Arrow
		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-arrow-type', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-arrow-type', 'icon' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-arrow', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-arrow', 'fa-angle-down' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-arrow-svg', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-arrow-svg', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-arrow-size', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-arrow-size', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-arrow-margin', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-arrow-margin', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-arrow-ver-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-arrow-ver-position', '' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Top', 'jet-menu' ),
					'value' => 'top',
				),
				array(
					'label' => esc_html__( 'Bottom', 'jet-menu' ),
					'value' => 'bottom',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-arrow-hor-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-arrow-hor-position', '' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Left', 'jet-menu' ),
					'value' => 'left',
				),
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Right', 'jet-menu' ),
					'value' => 'right',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-top-arrow-order', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-top-arrow-order', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-arrow-type', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-arrow-type', 'icon' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-arrow', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-arrow', 'fa-angle-right' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-arrow-svg', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-arrow-svg', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-arrow-size', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-arrow-size', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-arrow-margin', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-arrow-margin', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-arrow-ver-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-arrow-ver-position', '' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Top', 'jet-menu' ),
					'value' => 'top',
				),
				array(
					'label' => esc_html__( 'Bottom', 'jet-menu' ),
					'value' => 'bottom',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-arrow-hor-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-arrow-hor-position', '' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Left', 'jet-menu' ),
					'value' => 'left',
				),
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Right', 'jet-menu' ),
					'value' => 'right',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-sub-arrow-order', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-sub-arrow-order', '' ),
		) );
	}

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init_options') );
	}
}

