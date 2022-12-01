<?php

namespace Jet_Menu\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Mobile_Menu extends Base {

	/**
	 * Returns block name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mobile-menu';
	}

	/**
	 * Return attributes array
	 *
	 * @return array
	 */
	public function get_attributes() {
		$available_menus_options = jet_menu_tools()->get_available_menus_options();
		$menu_id = 0;

		if ( ! empty( $available_menus_options ) ) {
			$menu_id = $available_menus_options[0]['value'];
		}

		return [
			'__internalWidgetId' => [
				'type'    => 'string',
				'default' => '',
			],
			'blockPreview' => [
				'type'    => 'boolean',
				'default' => false,
			],
			'menuId'             => [
				'type'    => 'number',
				'default' => $menu_id,
			],
			'mobileMenuId'       => [
				'type'    => 'number',
				'default' => 0,
			],
			'layout'             => [
				'type'    => 'string',
				'default' => 'slide-out',
			],
			'togglePosition'     => [
				'type'    => 'string',
				'default' => 'default',
			],
			'containerPosition'  => [
				'type'    => 'string',
				'default' => 'left',
			],
			'subMenuTrigger'     => [
				'type'    => 'string',
				'default' => 'item',
			],
			'subOpenLayout'      => [
				'type'    => 'string',
				'default' => 'slide-in',
			],
			'closeAfterNavigate' => [
				'type'    => 'boolean',
				'default' => false,
			],
			'isItemIcon'  => [
				'type'    => 'boolean',
				'default' => true,
			],
			'isItemBadge' => [
				'type'    => 'boolean',
				'default' => true,
			],
			'isItemDesc'  => [
				'type'    => 'boolean',
				'default' => true,
			],
			'toggleLoader'    => [
				'type'    => 'boolean',
				'default' => true,
			],
			'loaderColor' => [
				'type'    => 'string',
				'default' => '#3a3a3a',
			],
			'useBreadcrumbs'  => [
				'type'    => 'boolean',
				'default' => true,
			],
			'breadcrumbsPath' => [
				'type'    => 'string',
				'default' => 'full',
			],
			'toggleButtonText' => [
				'type'    => 'string',
				'default' => '',
			],
			'backText'        => [
				'type'    => 'string',
				'default' => '',
			],
			'toggleClosedStateIconId'  => [
				'type'    => 'number',
				'default' => 0,
			],
			'toggleClosedStateIconUrl' => [
				'type'    => 'string',
				'default' => '',
			],
			'toggleOpenedStateIconId'  => [
				'type'    => 'number',
				'default' => 0,
			],
			'toggleOpenedStateIconUrl' => [
				'type'    => 'string',
				'default' => '',
			],
			'containerCloseIconId'     => [
				'type'    => 'number',
				'default' => 0,
			],
			'containerCloseIconUrl'    => [
				'type'    => 'string',
				'default' => '',
			],
			'containerBackIconId'      => [
				'type'    => 'number',
				'default' => 0,
			],
			'containerBackIconUrl'     => [
				'type'    => 'string',
				'default' => '',
			],
			'dropdownIconId'           => [
				'type'    => 'number',
				'default' => 0,
			],
			'dropdownIconUrl'          => [
				'type'    => 'string',
				'default' => '',
			],
			'dropdownOpenedIconId'     => [
				'type'    => 'number',
				'default' => 0,
			],
			'dropdownOpenedIconUrl'    => [
				'type'    => 'string',
				'default' => '',
			],
			'breadcrumbIconId'  => [
				'type'    => 'number',
				'default' => 0,
			],
			'breadcrumbIconUrl' => [
				'type'    => 'string',
				'default' => '',
			],

		];
	}

	/**
	 * Return callback
	 *
	 * @return html
	 */
	public function render_callback( $settings = [] ) {

		$render_widget_instance = new \Jet_Menu\Render\Mobile_Menu_Render( [
			'location'                  => 'wp-nav',
			'menu-id'                   => $settings[ 'menuId' ],
			'mobile-menu-id'            => $settings[ 'mobileMenuId' ],
			'layout'                    => $settings[ 'layout' ],
			'toggle-position'           => $settings[ 'togglePosition' ],
			'container-position'        => $settings[ 'containerPosition' ],
			'sub-menu-trigger'          => $settings[ 'subMenuTrigger' ],
			'sub-open-layout'           => $settings[ 'subOpenLayout' ],
			'close-after-navigate'      => $settings[ 'closeAfterNavigate' ],
			'is-item-icon'              => $settings[ 'isItemIcon' ],
			'is-item-badge'             => $settings[ 'isItemBadge' ],
			'is-item-desc'              => $settings[ 'isItemDesc' ],
			'toggle-loader'             => $settings[ 'toggleLoader' ],
			'loader-color'              => $settings[ 'loaderColor' ],
			'use-breadcrumbs'           => $settings[ 'useBreadcrumbs' ],
			'breadcrumbs-path'          => $settings[ 'breadcrumbsPath' ],
			'toggle-text'               => $settings[ 'toggleButtonText' ],
			'back-text'                 => $settings[ 'backText' ],
			'toggle-closed-icon-html'   => jet_menu_tools()->get_svg_icon_html( $settings[ 'toggleClosedStateIconId' ], '', [], false ),
			'toggle-opened-icon-html'   => jet_menu_tools()->get_svg_icon_html( $settings[ 'toggleOpenedStateIconId' ], '', [], false ),
			'close-icon-html'           => jet_menu_tools()->get_svg_icon_html( $settings[ 'containerCloseIconId' ], '', [], false ),
			'back-icon-html'            => jet_menu_tools()->get_svg_icon_html( $settings[ 'containerBackIconId' ], '', [], false ),
			'dropdown-icon-html'        => jet_menu_tools()->get_svg_icon_html( $settings[ 'dropdownIconId' ], '', [], false ),
			'dropdown-opened-icon-html' => jet_menu_tools()->get_svg_icon_html( $settings[ 'dropdownOpenedIconId' ], '', [], false ),
			'breadcrumb-icon-html'      => jet_menu_tools()->get_svg_icon_html( $settings[ 'breadcrumbIconId' ], '', [], false ),
		] );

		if ( filter_var( $settings[ 'blockPreview' ], FILTER_VALIDATE_BOOLEAN ) ) {
			return sprintf( '<img src="%s" alt="">', jet_menu()->plugin_url( 'assets/admin/images/block-previews/mobile-menu.png' ) );
		}

		ob_start();

		$render_widget_instance->render();

		return ob_get_clean();

	}

	/**
	 * @return void
	 */
	public function add_style_manager_options() {

		$css_scheme = apply_filters(
			'jet-menu/mobile-menu/css-scheme',
			array(
				'widget_instance' => '.jet-mobile-menu-widget',
				'toggle'          => '.jet-mobile-menu__toggle',
				'container'       => '.jet-mobile-menu__container',
				'breadcrumbs'     => '.jet-mobile-menu__breadcrumbs',
				'item'            => '.jet-mobile-menu__item',
			)
		);

		$this->controls_manager->start_section( 'style_controls', [
			'id'          => 'section_mobile_menu_toggle_style',
			'initialOpen' => true,
			'title'       => esc_html__( 'Toggle', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'toggle_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['toggle'] . ' .jet-mobile-menu__toggle-icon' => 'color: {{VALUE}}; fill:{{VALUE}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'toggle_icon_size',
			'type'         => 'range',
			'label'        => esc_html__( 'Icon Size', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['toggle'] . ' .jet-mobile-menu__toggle-icon i'   => 'font-size: {{VALUE}}{{UNIT}}',
				'{{WRAPPER}} ' . $css_scheme['toggle'] . ' .jet-mobile-menu__toggle-icon svg' => 'width: {{VALUE}}{{UNIT}}',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 8,
						'max'  => 100,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'toggle_bg',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Background Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['toggle'] => 'background-color: {{VALUE}};',
			],
		] );

		$this->controls_manager->add_control(
			array(
				'id'             => 'toggle_border',
				'label'          => __( 'Border', 'jet-menu' ),
				'type'           => 'border',
				'css_selector'   => [
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'border-style: {{STYLE}}; border-width: {{WIDTH}}; border-radius: {{RADIUS}}; border-color: {{COLOR}}',
				],
			)
		);

		$this->controls_manager->add_control(
			array(
				'id'           => 'toggle_padding',
				'label'        => __( 'Padding', 'jet-menu' ),
				'type'         => 'dimensions',
				'css_selector' => [
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
				],
			)
		);

		$this->controls_manager->add_control( [
			'id'      => 'menu_toggle_text_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Toggle Text', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'toggle_text_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Toggle Text Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['toggle'] . ' .jet-mobile-menu__toggle-text' => 'color: {{VALUE}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'toggle_text_typography',
			'label'        => __( 'Toggle Text Typography', 'jet-menu' ),
			'type'         => 'typography',
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['toggle'] . ' .jet-mobile-menu__toggle-text' => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		] );

		$this->controls_manager->end_section();

		$this->controls_manager->start_section( 'style_controls', [
			'id'          => 'section_mobile_menu_container_style',
			'title'       => esc_html__( 'Container', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'      => 'menu_container_navi_controls_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Navigation Controls', 'jet-menu' ),
		] );

		$this->controls_manager->add_control(
			array(
				'id'           => 'navi_controls_padding',
				'label'        => __( 'Padding', 'jet-menu' ),
				'type'         => 'dimensions',
				'css_selector' => [
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__controls' => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
				],
				'separator' => 'after',
			)
		);

		$this->controls_manager->add_control(
			array(
				'id'             => 'navi_controls_border',
				'label'          => __( 'Border', 'jet-menu' ),
				'type'           => 'border',
				'css_selector'   => [
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__controls' => 'border-style: {{STYLE}}; border-width: {{WIDTH}}; border-radius: {{RADIUS}}; border-color: {{COLOR}}',
				],
			)
		);

		$this->controls_manager->add_control( [
			'id'      => 'menu_container_icons_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Controls Styles', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'container_close_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Close/Back Icon Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back i' => 'color: {{VALUE}}',
				'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back svg' => 'color: {{VALUE}}; fill: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'container_close_icon_size',
			'type'         => 'range',
			'label'        => esc_html__( 'Close/Back Icon Size', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back i' => 'font-size: {{VALUE}}{{UNIT}}',
				'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back svg' => 'width: {{VALUE}}{{UNIT}}',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 8,
						'max'  => 100,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'container_back_text_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Back Text Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back span' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'back_text_typography',
			'label'        => __( 'Back Text Typography', 'jet-menu' ),
			'type'         => 'typography',
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back span' => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'      => 'menu_container_breadcrums_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Breadcrumbs', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'breadcrums_text_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Breadcrums Text Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['breadcrumbs'] . ' .breadcrumb-label' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'breadcrums_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Breadcrums Divider Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['breadcrumbs'] . ' .breadcrumb-divider' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'breadcrums_text_typography',
			'label'        => __( 'Breadcrumbs Text Typography', 'jet-menu' ),
			'type'         => 'typography',
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['breadcrumbs'] . ' .breadcrumb-label' => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'breadcrums_icon_size',
			'type'         => 'range',
			'label'        => esc_html__( 'Divider Size', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['breadcrumbs'] . ' .breadcrumb-divider i' => 'font-size: {{VALUE}}{{UNIT}}',
				'{{WRAPPER}} ' . $css_scheme['breadcrumbs'] . ' .breadcrumb-divider svg' => 'width: {{VALUE}}{{UNIT}}',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 8,
						'max'  => 100,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'      => 'menu_container_box_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Container Box', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'container_width',
			'type'         => 'range',
			'label'        => esc_html__( 'Container Width', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['container'] => 'width: {{VALUE}}{{UNIT}}',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 300,
						'max'  => 1000,
					]
				],
				[
					'value'     => '%',
					'intervals' => [
						'step' => 1,
						'min'  => 10,
						'max'  => 100,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'container_bg',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Container Background Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__container-inner' => 'background-color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'             => 'container_border',
			'label'          => __( 'Container Border', 'jet-menu' ),
			'type'           => 'border',
			'css_selector'   => [
				'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__container-inner' => 'border-style: {{STYLE}}; border-width: {{WIDTH}}; border-radius: {{RADIUS}}; border-color: {{COLOR}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'container_padding',
			'label'        => __( 'Padding', 'jet-menu' ),
			'type'         => 'dimensions',
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__container-inner' => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'container_z_index',
			'type'         => 'range',
			'label'        => esc_html__( 'Z Index', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['container'] => 'z-index: {{VALUE}}',
				'{{WRAPPER}} ' . $css_scheme['widget_instance'] . ' .jet-mobile-menu-cover' => 'z-index: calc({{VALUE}}-1)',
			],
			'units'        => [
				[
					'value'     => 'order',
					'intervals' => [
						'step' => 1,
						'min'  => -999,
						'max'  => 9999,
					]
				],

			],
			'attributes' => [
				'default' => [
					'value' => [
						'value' => 999,
						'unit' => 'order'
					]
				],
			],
		] );

		$this->controls_manager->end_section();

		$this->controls_manager->start_section( 'style_controls', [
			'id'          => 'section_mobile_menu_items_style',
			'title'       => esc_html__( 'Items', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'      => 'menu_item_label_icon',
			'type'    => 'text',
			'content' => esc_html__( 'Icon', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_icon_size',
			'type'         => 'range',
			'label'        => esc_html__( 'Icon Size', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-icon' => 'font-size: {{VALUE}}{{UNIT}}',
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-icon svg' => 'width: {{VALUE}}{{UNIT}}',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 8,
						'max'  => 100,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_icon_ver_position',
			'type'         => 'choose',
			'label'        => esc_html__( 'Vertical Position', 'jet-menu' ),
			'options'      => [
				'flex-start'    => [
					'shortcut' => esc_html__( 'Top', 'jet-menu' ),
					'icon'     => 'dashicons-arrow-up-alt',
				],
				'center'        => [
					'shortcut' => esc_html__( 'Center', 'jet-menu' ),
					'icon'     => 'dashicons-align-center',
				],
				'flex-end'      => [
					'shortcut' => esc_html__( 'Bottom', 'jet-menu' ),
					'icon'     => 'dashicons-arrow-down-alt',
				],
			],
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-icon' => 'align-self: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'      => 'menu_item_label_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Label', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'item_label_typography',
			'label'        => __( 'Label Text Typography', 'jet-menu' ),
			'type'         => 'typography',
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-label' => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'item_sub_label_typography',
			'label'        => __( 'Sub Label Typography', 'jet-menu' ),
			'type'         => 'typography',
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .mobile-sub-level-link .jet-menu-label' => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_padding',
			'label'        => __( 'Padding', 'jet-menu' ),
			'type'         => 'dimensions',
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'      => 'menu_item_desc_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Description', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'item_desc_typography',
			'label'        => __( 'Description Typography', 'jet-menu' ),
			'type'         => 'typography',
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-desc' => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'      => 'menu_item_badge_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Badge', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'item_badge_typography',
			'label'        => __( 'Badge Typography', 'jet-menu' ),
			'type'         => 'typography',
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge__inner' => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_badge_ver_position',
			'type'         => 'choose',
			'label'        => esc_html__( 'Badge Vertical Position', 'jet-menu' ),
			'options'      => [
				'flex-start'    => [
					'shortcut' => esc_html__( 'Top', 'jet-menu' ),
					'icon'     => 'dashicons-arrow-up-alt',
				],
				'center'        => [
					'shortcut' => esc_html__( 'Center', 'jet-menu' ),
					'icon'     => 'dashicons-align-center',
				],
				'flex-end'      => [
					'shortcut' => esc_html__( 'Bottom', 'jet-menu' ),
					'icon'     => 'dashicons-arrow-down-alt',
				],
			],
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge' => 'align-self: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_badge_padding',
			'label'        => __( 'Badge Padding', 'jet-menu' ),
			'type'         => 'dimensions',
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge__inner' => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'             => 'menu_item_badge_border_radius',
			'label'          => __( 'Badge Border', 'jet-menu' ),
			'type'           => 'border',
			'css_selector'   => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge__inner' => 'border-style: {{STYLE}}; border-width: {{WIDTH}}; border-radius: {{RADIUS}}; border-color: {{COLOR}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'      => 'menu_item_dropdown_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Sub Menu Icon', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_dropdown_icon_size',
			'type'         => 'range',
			'label'        => esc_html__( 'Icon Size', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-dropdown-arrow i' => 'font-size: {{VALUE}}{{UNIT}}',
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-dropdown-arrow svg' => 'width: {{VALUE}}{{UNIT}}',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 8,
						'max'  => 100,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_dropdown_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-dropdown-arrow i' => 'color: {{VALUE}};',
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-dropdown-arrow svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'      => 'menu_item_divider_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Divider', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_divider_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Divider Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] => 'border-bottom-color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_divider_width',
			'type'         => 'range',
			'label'        => esc_html__( 'Divider Size', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] => 'border-bottom-style: solid; border-bottom-width: {{VALUE}}{{UNIT}}',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 10,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'      => 'menu_item_state_heading',
			'type'    => 'text',
			'content' => esc_html__( 'States', 'jet-menu' ),
		] );

		$this->controls_manager->start_tabs( 'style_controls', [
			'id' => 'tabs_menu_item_style',
		] );

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_menu_items_normal',
			'title' => esc_html__( 'Normal', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-icon' => 'color: {{VALUE}}',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'item_label_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Label', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-label' => 'color: {{VALUE}}',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'item_sub_label_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Sub Label', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .mobile-sub-level-link .jet-menu-label' => 'color: {{VALUE}}',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'item_desc_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Description', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-desc' => 'color: {{VALUE}}',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_badge_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Badge Text Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge__inner' => 'color: {{VALUE}}',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_badge_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Badge Background Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge__inner' => 'background-color: {{VALUE}}',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_dropdown_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Sub Menu Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-dropdown-arrow' => 'color: {{VALUE}}',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'sub_menu_item_dropdown_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Sub Menu Dropdown Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}} ' . $css_scheme['item'] . ' .mobile-sub-level-link + .jet-dropdown-arrow' => 'color: {{VALUE}}',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Item Background', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] => 'background-color: {{VALUE}}',
			],
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_menu_items_hover',
			'title' => esc_html__( 'Hover', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_icon_color_hover',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ':hover > .jet-mobile-menu__item-inner .jet-menu-icon' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'item_label_color_hover',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Label', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ':hover > .jet-mobile-menu__item-inner .jet-menu-label' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'item_desc_color_hover',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Description', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ':hover > .jet-mobile-menu__item-inner .jet-menu-desc' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_badge_color_hover',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Badge Text Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ':hover > .jet-mobile-menu__item-inner .jet-menu-badge__inner' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_badge_bg_color_hover',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Badge Background Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ':hover > .jet-mobile-menu__item-inner .jet-menu-badge__inner' => 'background-color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_dropdown_color_hover',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Sub Menu Icon Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ':hover > .jet-mobile-menu__item-inner .jet-dropdown-arrow' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_bg_color_hover',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Item Background', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . ':hover > .jet-mobile-menu__item-inner' => 'background-color: {{VALUE}}',
			],
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_menu_items_active',
			'title' => esc_html__( 'Active', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_icon_color_active',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-menu-icon' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'item_label_color_active',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Label', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-menu-label' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'item_desc_color_active',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Description', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-menu-desc' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_badge_color_active',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Badge Text Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-menu-badge__inner' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_badge_bg_color_active',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Badge Background Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-menu-badge__inner' => 'background-color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_dropdown_color_active',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Sub Menu Icon Color', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-dropdown-arrow' => 'color: {{VALUE}}',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_bg_color_active',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Item Background', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner' => 'background-color: {{VALUE}}',
			],
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->end_tabs();

		$this->controls_manager->end_section();

	}
}
