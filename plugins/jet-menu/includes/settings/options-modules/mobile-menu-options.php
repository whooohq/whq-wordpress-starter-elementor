<?php
namespace Jet_Menu\Options_Manager;

class Mobile_Menu_Options {

	public function init_options() {
		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-layout', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-layout', 'slide-out' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Slide Out', 'jet-menu' ),
					'value' => 'slide-out',
				),
				array(
					'label' => esc_html__( 'Dropdown', 'jet-menu' ),
					'value' => 'dropdown',
				),
				array(
					'label' => esc_html__( 'Push', 'jet-menu' ),
					'value' => 'push',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-toggle-position', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-position', 'default' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Default', 'jet-menu' ),
					'value' => 'default',
				),
				array(
					'label' => esc_html__( 'Fixed to top-left screen corner', 'jet-menu' ),
					'value' => 'fixed-left',
				),
				array(
					'label' => esc_html__( 'Fixed to top-right screen corner', 'jet-menu' ),
					'value' => 'fixed-right',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-container-position', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-position', 'right' ),
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

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-sub-trigger', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-sub-trigger', 'item' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Menu Item', 'jet-menu' ),
					'value' => 'item',
				),
				array(
					'label' => esc_html__( 'Sub Menu Icon', 'jet-menu' ),
					'value' => 'submarker',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-sub-open-layout', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-sub-open-layout', 'slide-in' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Slide In', 'jet-menu' ),
					'value' => 'slide-in',
				),
				array(
					'label' => esc_html__( 'Dropdown', 'jet-menu' ),
					'value' => 'dropdown',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-close-after-navigate', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-close-after-navigate', 'false' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-header-template', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-header-template', '' ),
			'options' => jet_menu_tools()->get_elementor_templates_select_options(),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-before-template', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-before-template', '' ),
			'options' => jet_menu_tools()->get_elementor_templates_select_options(),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-after-template', array(
			'value'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-after-template', '' ),
			'options' => jet_menu_tools()->get_elementor_templates_select_options(),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-toggle-icon', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-icon', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-toggle-opened-icon', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-opened-icon', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-toggle-text', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-text', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-toggle-loader', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-loader', 'true' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-back-text', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-back-text', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-dropdown-icon', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-dropdown-icon', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-dropdown-opened-icon', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-dropdown-opened-icon', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-use-breadcrumb', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-use-breadcrumb', 'true' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-breadcrumb-icon', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-breadcrumb-icon', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-toggle-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-toggle-size', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-size', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-toggle-text-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-text-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-menu-mobile-toggle-text' );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-menu-mobile-back-text' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-toggle-bg', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-bg', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-mobile-toggle' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-mobile-toggle' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-toggle-border-radius', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-border-radius', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-toggle-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-padding', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-container-width', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-width', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-breadcrumbs-text-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-breadcrumbs-text-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-breadcrumbs-icon-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-breadcrumbs-icon-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-breadcrumbs-icon-size', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-breadcrumbs-icon-size', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-menu-mobile-breadcrumbs-text' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-container-bg', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-bg', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_border_options( 'jet-menu-mobile-container' );

		jet_menu()->settings_manager->options_manager->add_box_shadow_options( 'jet-menu-mobile-container' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-container-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-padding', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-container-border-radius', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-border-radius', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-cover-bg', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-cover-bg', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-container-close-icon', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-close-icon', 'fa-times' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-container-back-icon', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-back-icon', 'fa-angle-left' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-container-close-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-close-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-container-back-text-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-back-text-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-menu-mobile-container-close-size', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-close-size', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-label-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-label-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-label-color-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-label-color-active', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-mobile-items-label' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-desc-enable', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-desc-enable', false ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-desc-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-desc-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-desc-color-active', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-desc-color-active', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-mobile-items-desc' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-divider-enabled', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-divider-enabled', false ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-divider-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-divider-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-divider-width', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-divider-width', '1' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-icon-enabled', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-icon-enabled', 'true' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-icon-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-icon-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-icon-size', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-icon-size', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-icon-ver-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-icon-ver-position', 'center' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Top', 'jet-menu' ),
					'value' => 'top',
				),
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Bottom', 'jet-menu' ),
					'value' => 'bottom',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-icon-margin', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-icon-margin', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-badge-enabled', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-badge-enabled', 'true' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-badge-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-badge-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_typography_options( 'jet-mobile-items-badge' );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-badge-bg-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-badge-bg-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-badge-ver-position', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-badge-ver-position', 'top' ),
			'options' => array(
				array(
					'label' => esc_html__( 'Top', 'jet-menu' ),
					'value' => 'top',
				),
				array(
					'label' => esc_html__( 'Center', 'jet-menu' ),
					'value' => 'center',
				),
				array(
					'label' => esc_html__( 'Bottom', 'jet-menu' ),
					'value' => 'bottom',
				),
			),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-badge-padding', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-badge-padding', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-badge-border-radius', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-badge-border-radius', jet_menu_tools()->get_default_dimensions() ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-dropdown-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-dropdown-color', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-items-dropdown-size', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-dropdown-size', '' ),
		) );

		jet_menu()->settings_manager->options_manager->add_option( 'jet-mobile-loader-color', array(
			'value' => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-loader-color', '#3a3a3a' ),
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

