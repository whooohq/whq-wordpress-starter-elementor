<?php

namespace Jet_Menu\Blocks;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Mega_Menu extends Base {

	/**
	 * Returns block name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mega-menu';
	}

	/**
	 * Return attributes array
	 *
	 * @return array
	 */
	public function get_attributes() {
		$menu_id = 0;

		if ( ! empty( $available_menus_options ) ) {
			$menu_id = $available_menus_options[0]['value'];
		}

		return [
			'__internalWidgetId'   => [
				'type'    => 'string',
				'default' => '',
			],
			'blockPreview' => [
				'type'    => 'boolean',
				'default' => false,
			],
			// General
			'menuId'               => [
				'type'    => 'number',
				'default' => $menu_id,
			],
			'layout'               => [
				'type'    => 'string',
				'default' => 'horizontal',
			],
			'dropdownLayout'       => [
				'type'    => 'string',
				'default' => 'default',
			],
			'dropdownPosition'     => [
				'type'    => 'string',
				'default' => 'right',
			],
			'subAnimation'         => [
				'type'    => 'string',
				'default' => 'none',
			],
			'subPosition'          => [
				'type'    => 'string',
				'default' => 'right',
			],
			'subEvent'             => [
				'type'    => 'string',
				'default' => 'hover',
			],
			'subTrigger'           => [
				'type'    => 'string',
				'default' => 'item',
			],
			'megaWidthType'        => [
				'type'    => 'string',
				'default' => 'container',
			],
			'megaWidthSelector'    => [
				'type'    => 'string',
				'default' => '',
			],
			'dropdownBreakpoint'   => [
				'type'    => 'number',
				'default' => 576,
			],
			'megaAjaxLoad' => [
				'type'    => 'boolean',
				'default' => false,
			],
			'rollUp'               => [
				'type'    => 'boolean',
				'default' => true,
			],
			'rollUpType'           => [
				'type'    => 'string',
				'default' => 'text',
			],
			'rollUpText'           => [
				'type'    => 'string',
				'default' => '...',
			],
			'rollUpIconId'         => [
				'type'    => 'number',
				'default' => 0,
			],
			'rollUpIconUrl'        => [
				'type'    => 'string',
				'default' => '',
			],
			'dropdownIconId'       => [
				'type'    => 'number',
				'default' => 0,
			],
			'dropdownIconUrl'      => [
				'type'    => 'string',
				'default' => '',
			],
			'toggleDefaultIconId'  => [
				'type'    => 'number',
				'default' => 0,
			],
			'toggleDefaultIconUrl' => [
				'type'    => 'string',
				'default' => '',
			],
			'toggleOpenedIconId'   => [
				'type'    => 'number',
				'default' => 0,
			],
			'toggleOpenedIconUrl'  => [
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

		$render_widget_instance = new \Jet_Menu\Render\Mega_Menu_Render( [
			'menu'                => $settings[ 'menuId' ],
			'layout'              => $settings[ 'layout' ],
			'dropdown-layout'     => $settings[ 'dropdownLayout' ],
			'dropdown-position'   => $settings[ 'dropdownPosition' ],
			'sub-animation'       => $settings[ 'subAnimation' ],
			'sub-position'        => $settings[ 'subPosition' ],
			'sub-event'           => $settings[ 'subEvent' ],
			'sub-trigger'         => $settings[ 'subTrigger' ],
			'mega-width-type'     => $settings[ 'megaWidthType' ],
			'mega-width-selector' => $settings[ 'megaWidthSelector' ],
			'breakpoint'          => $settings[ 'dropdownBreakpoint' ],
			'roll-up'             => $settings[ 'rollUp' ],
			'roll-up-type'        => $settings[ 'rollUpType' ],
			'roll-up-item-text'   => $settings[ 'rollUpText' ],
			'roll-up-item-icon'   => jet_menu_tools()->get_svg_icon_html( $settings[ 'rollUpIconId' ], '', [], false ),
			'dropdown-icon'       => jet_menu_tools()->get_svg_icon_html( $settings[ 'dropdownIconId' ], '', [], false ),
			'toggle-default-icon' => jet_menu_tools()->get_svg_icon_html( $settings[ 'toggleDefaultIconId' ], '', [], false ),
			'toggle-opened-icon'  => jet_menu_tools()->get_svg_icon_html( $settings[ 'toggleOpenedIconId' ], '', [], false ),
			'ajax-loading'        => $settings['megaAjaxLoad'],
			'location'            => 'wp-nav',
		] );

		if ( filter_var( $settings[ 'blockPreview' ], FILTER_VALIDATE_BOOLEAN ) ) {
			return sprintf( '<img src="%s" alt="">', jet_menu()->plugin_url( 'assets/admin/images/block-previews/mega-menu.png' ) );
		}

		ob_start();
		
		$render_widget_instance->render();

		return ob_get_clean();

	}

	public function add_style_manager_options() {

		$this->controls_manager->start_section( 'style_controls', [
			'id'          => 'section_main_menu_styles',
			'initialOpen' => true,
			'title'       => esc_html__( 'Horizontal and Vertical Layout', 'jet-menu' ),
		] );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'main_menu_container_width',
			'label'        => __( 'Container Width', 'jet-menu' ),
			'type'         => 'range',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 200,
						'max'  => 1980,
					],
				],
				[
					'value'     => '%',
					'intervals' => [
						'step' => 1,
						'min'  => 10,
						'max'  => 100,
					],
				],
			],
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-container-width:{{VALUE}}{{UNIT}};',
			],
			'condition'    => [
				'layout' => 'vertical',
			],
		] );

		$this->controls_manager->start_tabs( 'style_controls', [
			'id' => 'tabs_menu_level_style',
		] );

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_menu_top_level',
			'title' => esc_html__( 'Top Items', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'top_items_typography',
			'label'        => __( 'Top Items Typography', 'jet-menu' ),
			'type'         => 'typography',
			'css_selector' => [
				'{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-horizontal .jet-mega-menu-item__link--top-level' => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
				'{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-vertical .jet-mega-menu-item__link--top-level'   => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'main_menu_container_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Container Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-menu-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'main_menu_item_vertical_padding',
			'type'         => 'range',
			'label'        => esc_html__( 'Items Vertical Padding', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-top-items-ver-padding:{{VALUE}}{{UNIT}};',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'main_menu_item_hor_padding',
			'type'         => 'range',
			'label'        => esc_html__( 'Items Horizontal Padding', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-top-items-hor-padding:{{VALUE}}{{UNIT}};',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'main_menu_item_space',
			'type'         => 'range',
			'label'        => esc_html__( 'Items Space', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-top-items-gap:{{VALUE}}{{UNIT}};',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'main_menu_items_align',
			'type'         => 'choose',
			'label'        => esc_html__( 'Items Alignment', 'jet-menu' ),
			'options'      => [
				'flex-start'    => [
					'shortcut' => esc_html__( 'Left', 'jet-menu' ),
					'icon'     => 'dashicons-editor-alignleft',
				],
				'center'        => [
					'shortcut' => esc_html__( 'Center', 'jet-menu' ),
					'icon'     => 'dashicons-editor-aligncenter',
				],
				'flex-end'      => [
					'shortcut' => esc_html__( 'Right', 'jet-menu' ),
					'icon'     => 'dashicons-editor-alignright',
				],
				'space-between' => [
					'shortcut' => esc_html__( 'Stretch', 'jet-menu' ),
					'icon'     => 'dashicons-editor-justify',
				],
			],
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-top-items-hor-align: {{VALUE}};',
			],
			'condition'    => [
				'layout' => [ 'horizontal', 'vertical' ]
			],
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_menu_sub_level',
			'title' => esc_html__( 'Sub Items', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'sub_items_typography',
			'label'        => __( 'Sub Items Typography', 'jet-menu' ),
			'type'         => 'typography',
			'css_selector' => [
				'{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-horizontal .jet-mega-menu-item__link--sub-level' => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
				'{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-vertical .jet-mega-menu-item__link--sub-level'   => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'sub_menu_container_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Container Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-menu-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'main_menu_sub_item_vertical_padding',
			'type'         => 'range',
			'label'        => esc_html__( 'Items Vertical Padding', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-sub-items-ver-padding:{{VALUE}}{{UNIT}};',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'main_menu_sub_item_hor_padding',
			'type'         => 'range',
			'label'        => esc_html__( 'Items Horizontal Padding', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-sub-items-hor-padding:{{VALUE}}{{UNIT}};',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					]
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'main_menu_sub_item_space',
			'type'         => 'range',
			'label'        => esc_html__( 'Items Space', 'jet-menu' ),
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-sub-items-gap:{{VALUE}}{{UNIT}};',
			],
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					]
				],
			],
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->end_tabs();

		$this->controls_manager->add_control( [
			'id'      => 'main_menu_states_heading',
			'type'    => 'text',
			'content' => esc_html__( 'States', 'jet-menu' ),
		] );

		$this->controls_manager->start_tabs( 'style_controls', [
			'id' => 'tabs_menu_item_style',
		] );

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_menu_item_normal',
			'title' => esc_html__( 'Normal', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'      => 'main_menu_top_state_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Top Items', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-item-icon-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_title_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Title Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-item-title-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_dropdown_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Dropdown Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-item-dropdown-icon-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_item_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Background Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-item-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'      => 'main_menu_sub_state_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Sub Items', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_item_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-item-icon-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_item_title_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Title Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-item-title-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_item_dropdown_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Dropdown Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-item-dropdown-icon-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_item_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Background', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-item-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_menu_hover_item',
			'title' => esc_html__( 'Hover', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'      => 'main_menu_top_hover_state_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Top Items', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_hover_item_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-hover-item-icon-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_hover_item_title_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Title Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-hover-item-title-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_hover_item_dropdown_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Dropdown Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-hover-item-dropdown-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_hover_item_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Background Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-hover-item-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'      => 'main_menu_sub_hover_state_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Sub Items', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_hover_item_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-hover-item-icon-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_hover_item_title_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Title Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-hover-item-title-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_hover_item_dropdown_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Dropdown Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-hover-item-dropdown-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_hover_item_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Background Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-hover-item-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_menu_active_item',
			'title' => esc_html__( 'Active', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'      => 'main_menu_top_active_state_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Top Items', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_active_item_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-active-item-icon-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_active_item_title_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Title Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-active-item-title-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_active_item_dropdown_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Dropdown Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-active-item-dropdown-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_active_item_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Background Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-top-active-item-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'      => 'main_menu_sub_active_state_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Sub Items', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_active_item_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-active-item-icon-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_active_item_title_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Title Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-active-item-title-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_active_item_dropdown_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Dropdown Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-active-item-dropdown-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'menu_sub_active_item_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Background Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-sub-active-item-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->end_tabs();

		$this->controls_manager->end_section();

		$this->controls_manager->start_section( 'style_controls', [
			'id'    => 'section_dropdown_menu_styles',
			'title' => esc_html__( 'Dropdown Layout', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'      => 'dropdown_menu_container_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Container', 'jet-menu' ),
		] );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'dropdown_menu_container_width',
			'label'        => __( 'Container Width', 'jet-menu' ),
			'type'         => 'range',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 200,
						'max'  => 1980,
					],
				],
				[
					'value'     => '%',
					'intervals' => [
						'step' => 1,
						'min'  => 10,
						'max'  => 100,
					],
				],
			],
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-dropdown-container-width:{{VALUE}}{{UNIT}};',
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_container_padding',
			'label'        => __( 'Container Padding', 'jet-menu' ),
			'type'         => 'dimensions',
			'separator'    => 'after',
			'css_selector' => array(
				'{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-dropdown .jet-mega-menu-list' => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_container_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Container Background Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'      => 'dropdown_menu_level_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Level', 'jet-menu' ),
		] );

		$this->controls_manager->start_tabs( 'style_controls', [
			'id' => 'tabs_dropdown_menu_level_style',
		] );

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_dropdown_menu_top_level',
			'title' => esc_html__( 'Top Items', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_top_items_typography',
			'label'        => __( 'Top Items Typography', 'jet-menu' ),
			'type'         => 'typography',
			'css_selector' => [
				'{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-dropdown .jet-mega-menu-item__link--top-level' => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		] );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'dropdown_menu_item_vertical_padding',
			'label'        => __( 'Items Vertical Padding', 'jet-menu' ),
			'type'         => 'range',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					]
				],
			],
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-dropdown-top-items-ver-padding:{{VALUE}}{{UNIT}};',
			],
		] );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'dropdown_menu_top_item_hor_padding',
			'label'        => __( 'Items Horizontal Padding', 'jet-menu' ),
			'type'         => 'range',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					]
				],
			],
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-dropdown-top-items-hor-padding:{{VALUE}}{{UNIT}};',
			],
		] );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'dropdown_menu_top_item_space',
			'label'        => __( 'Items Space', 'jet-menu' ),
			'type'         => 'range',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					]
				],
			],
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-dropdown-top-items-gap:{{VALUE}}{{UNIT}};',
			],
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_dropdown_menu_sub_level',
			'title' => esc_html__( 'Sub Items', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_sub_items_typography',
			'label'        => __( 'Sub Items Typography', 'jet-menu' ),
			'type'         => 'typography',
			'css_selector' => [
				'{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-dropdown .jet-mega-menu-item__link--sub-level' => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		] );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'dropdown_menu_sub_item_vertical_padding',
			'label'        => __( 'Items Vertical Padding', 'jet-menu' ),
			'type'         => 'range',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					]
				],
			],
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-dropdown-sub-items-ver-padding:{{VALUE}}{{UNIT}};',
			],
		] );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'dropdown_menu_sub_item_hor_padding',
			'label'        => __( 'Items Horizontal Padding', 'jet-menu' ),
			'type'         => 'range',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					]
				],
			],
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-dropdown-sub-items-hor-padding:{{VALUE}}{{UNIT}};',
			],
		] );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'dropdown_menu_sub_item_space',
			'label'        => __( 'Items Space', 'jet-menu' ),
			'type'         => 'range',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					]
				],
			],
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-dropdown-sub-items-gap:{{VALUE}}{{UNIT}};',
			],
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->end_tabs();

		$this->controls_manager->add_control( [
			'id'      => 'dropdown_menu_states_heading',
			'type'    => 'text',
			'content' => esc_html__( 'States', 'jet-menu' ),
		] );

		$this->controls_manager->start_tabs( 'style_controls', [
			'id' => 'tabs_dropdown_menu_item_style',
		] );

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_dropdown_menu_item_normal',
			'title' => esc_html__( 'Normal', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_item_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-item-icon-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_item_title_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Title Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-item-title-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_item_dropdown_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Dropdown Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-item-dropdown-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_item_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Background Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-item-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_dropdown_menu_hover_item',
			'title' => esc_html__( 'Hover', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_hover_item_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-hover-item-icon-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_hover_item_title_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Title Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-hover-item-title-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_hover_item_dropdown_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Dropdown Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-hover-item-dropdown-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_hover_item_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Background Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-hover-item-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'tab_dropdown_menu_active_item',
			'title' => esc_html__( 'Active', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_active_item_icon_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Icon Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-active-item-icon-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_active_item_title_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Title Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-active-item-title-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_active_item_dropdown_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Dropdown Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-active-item-dropdown-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_active_item_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Background Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-active-item-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->end_tabs();

		$this->controls_manager->add_control( [
			'id'      => 'dropdown_menu_toggle_heading',
			'type'    => 'text',
			'content' => esc_html__( 'Dropdown Toggle', 'jet-menu' ),
		] );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'dropdown_menu_toggle_size',
			'label'        => __( 'Toggle Size', 'jet-menu' ),
			'type'         => 'range',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					]
				],
			],
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-dropdown-toggle-size:{{VALUE}}{{UNIT}};',
			],
		] );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'dropdown_menu_toggle_distance',
			'label'        => __( 'Distance', 'jet-menu' ),
			'type'         => 'range',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => -100,
						'max'  => 100,
					]
				],
			],
			'css_selector' => [
				'{{WRAPPER}}' => '--jmm-dropdown-toggle-distance:{{VALUE}}{{UNIT}};',
			],
		] );


		$this->controls_manager->start_tabs( 'style_controls', [
			'id' => 'dropdown_menu_toggle_states',
		] );

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'dropdown_menu_normal_toggle_state',
			'title' => esc_html__( 'Normal', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_toggle_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-toggle-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_toggle_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Container Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-toggle-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'dropdown_menu_toggle_hover_state',
			'title' => esc_html__( 'Hover', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_hover_toggle_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-hover-toggle-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_hover_toggle_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Container Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-hover-toggle-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->start_tab( 'style_controls', [
			'id'    => 'dropdown_menu_toggle_active_state',
			'title' => esc_html__( 'Active', 'jet-menu' ),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_active_toggle_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-active-toggle-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->add_control( [
			'id'           => 'dropdown_menu_active_toggle_bg_color',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Container Color', 'jet-menu' ),
			'css_selector' => array (
				'{{WRAPPER}}' => '--jmm-dropdown-active-toggle-bg-color: {{VALUE}};',
			),
		] );

		$this->controls_manager->end_tab();

		$this->controls_manager->end_tabs();

		$this->controls_manager->end_section();
	}
}
