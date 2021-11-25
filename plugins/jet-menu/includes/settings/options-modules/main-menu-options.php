<?php
namespace Jet_Menu\Options_Manager;

class Main_Menu_Options {

	public function init_options() {

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-layout', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-layout', 'horizontal' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Horizontal', 'jet-menu' ),
					'value' => 'horizontal',
				),
				array(
					'label' => esc_html__( 'Vertical', 'jet-menu' ),
					'value' => 'vertical',
				),
				array(
					'label' => esc_html__( 'Dropdown', 'jet-menu' ),
					'value' => 'dropdown',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-layout', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-layout', 'default' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Default', 'jet-menu' ),
					'value' => 'default',
				),
				array(
					'label' => esc_html__( 'Push', 'jet-menu' ),
					'value' => 'push',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-position', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-position', 'right' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Right', 'jet-menu' ),
					'value' => 'right',
				),
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Left', 'jet-menu' ),
					'value' => 'Left',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-sub-menu-position', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-menu-position', 'right' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Right', 'jet-menu' ),
					'value' => 'right',
				),
				array(
					'label' => esc_html__( 'Left', 'jet-menu' ),
					'value' => 'left',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-sub-animation', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-animation', 'fade' ),
			'options' => array(
				array(
					'label' => esc_html__( 'None', 'jet-menu' ),
					'value' => 'none',
				),
				array(
					'label' => esc_html__( 'Fade', 'jet-menu' ),
					'value' => 'fade',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-sub-menu-event', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-menu-event', 'hover' ),
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

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-sub-menu-trigger', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-menu-trigger', 'item' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Item', 'jet-menu' ),
					'value' => 'item',
				),
				array(
					'label' => esc_html__( 'Sub Icon', 'jet-menu' ),
					'value' => 'submarker',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-breakpoint', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-breakpoint', 768 ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-roll-up', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-roll-up', 'true' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-roll-up-type', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-roll-up-type', 'text' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Text', 'jet-menu' ),
					'value' => 'text',
				),
				array(
					'label' => esc_html__( 'Icon', 'jet-menu' ),
					'value' => 'icon',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-roll-up-text', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-roll-up-text', '...' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-roll-up-icon', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-roll-up-icon', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-icon', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-icon', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-toggle-default-icon', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-toggle-default-icon', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-toggle-opened-icon', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-toggle-opened-icon', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-use-mobile-render', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-use-mobile-render', 'false' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-mobile-device', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-mobile-device', 'mobile' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Mobile', 'jet-menu' ),
					'value' => 'mobile',
				),
				array(
					'label' => esc_html__( 'Tablet and Mobile', 'jet-menu' ),
					'value' => 'tablet-mobile',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-container-width', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-container-width', 'false' ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-mega-menu-top-typography' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-items-ver-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-items-ver-padding', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-items-hor-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-items-hor-padding', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-items-gap', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-items-gap', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-mega-menu-sub-typography' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-sub-bg-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-bg-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-sub-items-ver-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-items-ver-padding', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-sub-items-hor-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-items-hor-padding', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-sub-items-gap', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-items-gap', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-icon-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-icon-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-title-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-title-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-badge-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-badge-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-hover-icon-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-hover-icon-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-hover-title-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-hover-title-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-hover-badge-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-hover-badge-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-active-icon-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-active-icon-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-active-title-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-active-title-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-active-badge-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-active-badge-color', '' ),
		) );


		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-mega-menu-dropdown-top-typography' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-top-items-ver-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-top-items-ver-padding', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-top-items-hor-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-top-items-hor-padding', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-top-items-gap', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-top-items-gap', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-mega-menu-dropdown-sub-typography' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-sub-items-ver-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-sub-items-ver-padding', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-sub-items-hor-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-sub-items-hor-padding', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-sub-items-gap', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-sub-items-gap', '' ),
		) );


		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-icon-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-icon-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-title-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-title-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-badge-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-badge-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-item-bg-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-item-bg-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-hover-icon-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-hover-icon-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-hover-title-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-hover-title-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-hover-badge-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-hover-badge-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-hover-item-bg-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-hover-item-bg-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-active-icon-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-active-icon-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-active-title-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-active-title-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-active-badge-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-active-badge-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-active-item-bg-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-active-item-bg-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-toggle-size', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-toggle-size', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mega-menu-dropdown-toggle-distance', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-toggle-distance', '' ),
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

