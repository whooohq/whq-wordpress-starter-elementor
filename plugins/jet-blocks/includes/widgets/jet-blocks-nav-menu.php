<?php
/**
 * Class: Jet_Blocks_Nav_Menu
 * Name: Navigation Menu
 * Slug: jet-nav-menu
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Blocks_Nav_Menu extends Jet_Blocks_Base {

	public function get_name() {
		return 'jet-nav-menu';
	}

	public function get_title() {
		return esc_html__( 'Navigation Menu', 'jet-blocks' );
	}

	public function get_icon() {
		return 'jet-blocks-icon-nav-menu';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-add-a-simple-menu-with-nav-menu-widget-into-the-header-template/';
	}

	public function get_script_depends() {
		return array( 'hoverIntent' );
	}

	public function get_categories() {
		return array( 'jet-blocks' );
	}

	protected function register_controls() {

		if ( \Elementor\Plugin::$instance->breakpoints && method_exists( \Elementor\Plugin::$instance->breakpoints, 'get_active_breakpoints')) {
			$active_breakpoints  = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
			$breakpoints_list    = array();
			$exclude_breakpoints = array( 'widescreen', 'laptop' );

			foreach ($active_breakpoints as $key => $value) {
				if ( !in_array( $key, $exclude_breakpoints ) ) {
					$breakpoints_list[$key] = $value->get_label();
				}
			}

			$breakpoints_list = array_reverse( $breakpoints_list );
		} else {
			$breakpoints_list = array(
				'tablet' => 'Tablet',
				'mobile' => 'Mobile'
			);
		}

		$this->start_controls_section(
			'section_menu',
			array(
				'label' => esc_html__( 'Menu', 'jet-blocks' ),
			)
		);

		$menus   = $this->get_available_menus();
		$default = '';

		if ( ! empty( $menus ) ) {
			$ids     = array_keys( $menus );
			$default = $ids[0];
		}

		$this->add_control(
			'nav_menu',
			array(
				'label'   => esc_html__( 'Select Menu', 'jet-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $default,
				'options' => $menus,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'jet-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'jet-blocks' ),
					'vertical'   => esc_html__( 'Vertical', 'jet-blocks' ),
				),
			)
		);

		$this->add_control(
			'dropdown_position',
			array(
				'label'   => esc_html__( 'Dropdown Placement', 'jet-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right-side',
				'options' => array(
					'left-side'  => esc_html__( 'Left Side', 'jet-blocks' ),
					'right-side' => esc_html__( 'Right Side', 'jet-blocks' ),
					'bottom'     => esc_html__( 'At the bottom', 'jet-blocks' ),
				),
				'condition' => array(
					'layout' => 'vertical',
				)
			)
		);

		$this->__add_advanced_icon_control(
			'dropdown_icon',
			array(
				'label'       => esc_html__( 'Top Dropdown Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'default'     => 'fa fa-angle-down',
				'fa5_default' => array(
					'value'   => 'fa fa-angle-down',
					'library' => 'fa-solid',
				),
			)
		);

		$this->__add_advanced_icon_control(
			'dropdown_icon_sub',
			array(
				'label'       => esc_html__( 'Sub Dropdown Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'default'     => 'fa fa-angle-right',
				'fa5_default' => array(
					'value'   => 'fa fa-angle-right',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'show_items_desc',
			array(
				'label'   => esc_html__( 'Show Items Description', 'jet-blocks' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_responsive_control(
			'menu_alignment',
			array(
				'label'   => esc_html__( 'Menu Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-right',
					),
					'space-between' => array(
						'title' => esc_html__( 'Justified', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'selectors_dictionary' => array(
					'flex-start'    => ! is_rtl() ? 'justify-content: flex-start; text-align: left;' : 'justify-content: flex-end; text-align: right;',
					'center'        => 'justify-content: center; text-align: center;',
					'flex-end'      => ! is_rtl() ? 'justify-content: flex-end; text-align: right;' : 'justify-content: flex-start; text-align: left;',
					'space-between' => 'justify-content: space-between; text-align: left;',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav--horizontal' => '{{VALUE}}',
					'{{WRAPPER}} .jet-nav--vertical .menu-item-link-top' => '{{VALUE}}',
					'{{WRAPPER}} .jet-nav--vertical-sub-bottom .menu-item-link-sub' => '{{VALUE}}',

					'{{WRAPPER}} .jet-mobile-menu.jet-mobile-menu-trigger-active .menu-item-link' => '{{VALUE}}',
				),
				'prefix_class' => 'jet-nav%s-align-',
				'classes' => 'jet-blocks-text-align-control',
			)
		);

		$this->add_control(
			'menu_alignment_style',
			array(
				'type'       => Controls_Manager::HIDDEN,
				'default'    => 'style',
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .jet-nav--horizontal .jet-nav__sub' => 'text-align: left;',
					'body.rtl {{WRAPPER}} .jet-nav--horizontal .jet-nav__sub' => 'text-align: right;',
				),
				'condition' => array(
					'layout' => 'horizontal',
				),
				'classes' => 'jet-blocks-text-align-control',
			)
		);

		$this->add_control(
			'mobile_trigger_visible',
			array(
				'label'     => esc_html__( 'Enable Mobile Trigger', 'jet-blocks' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'mobile_trigger_devices',
			array(
				'label'       => __( 'Start Showing Mobile Menu From', 'jet-blocks' ),
				'type'        => Controls_Manager::SELECT,
				'multiple'    => true,
				'label_block' => 'true',
				'default' => array(
					'mobile',
				),
				'options'   => $breakpoints_list,
				'condition' => array(
					'mobile_trigger_visible' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_trigger_alignment',
			array(
				'label'   => esc_html__( 'Mobile Trigger Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition' => array(
					'mobile_trigger_visible' => 'yes',
				),
				'classes' => 'jet-blocks-text-align-control',
			)
		);

		$this->__add_advanced_icon_control(
			'mobile_trigger_icon',
			array(
				'label'       => esc_html__( 'Mobile Trigger Icon', 'jet-blocks' ),
				'label_block' => false,
				'type'        => Controls_Manager::ICON,
				'skin'        => 'inline',
				'default'     => 'fa fa-bars',
				'fa5_default' => array(
					'value'   => 'fas fa-bars',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'mobile_trigger_visible' => 'yes',
				),
			)
		);

		$this->__add_advanced_icon_control(
			'mobile_trigger_close_icon',
			array(
				'label'       => esc_html__( 'Mobile Trigger Close Icon', 'jet-blocks' ),
				'label_block' => false,
				'type'        => Controls_Manager::ICON,
				'skin'        => 'inline',
				'default'     => 'fa fa-times',
				'fa5_default' => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'mobile_trigger_visible' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_menu_layout',
			array(
				'label' => esc_html__( 'Mobile Menu Layout', 'jet-blocks' ),
				'type'  => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default'    => esc_html__( 'Default', 'jet-blocks' ),
					'full-width' => esc_html__( 'Full Width', 'jet-blocks' ),
					'left-side'  => esc_html__( 'Slide From The Left Side ', 'jet-blocks' ),
					'right-side' => esc_html__( 'Slide From The Right Side ', 'jet-blocks' ),
				),
				'condition' => array(
					'mobile_trigger_visible' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->__start_controls_section(
			'nav_items_style',
			array(
				'label'      => esc_html__( 'Top Level Items', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'nav_vertical_menu_width',
			array(
				'label' => esc_html__( 'Vertical Menu Width', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav-wrap' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'layout' => 'vertical',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'nav_vertical_menu_align',
			array(
				'label'       => esc_html__( 'Vertical Menu Alignment', 'jet-blocks' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors_dictionary' => array(
					'left'   => 'margin-left: 0; margin-right: auto;',
					'center' => 'margin-left: auto; margin-right: auto;',
					'right'  => 'margin-left: auto; margin-right: 0;',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav-wrap' => '{{VALUE}}',
				),
				'condition'  => array(
					'layout' => 'vertical',
				),
				'classes' => 'jet-blocks-text-align-control',
			),
			50
		);

		$this->__start_controls_tabs( 'tabs_nav_items_style' );

		$this->__start_controls_tab(
			'nav_items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'nav_items_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-top' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'nav_items_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-top' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'nav_items_text_bg_color',
			array(
				'label'  => esc_html__( 'Text Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-top .jet-nav-link-text' => 'background-color: {{VALUE}}',
				),
			),
			75
		);

		$this->__add_control(
			'nav_items_text_icon_color',
			array(
				'label'  => esc_html__( 'Dropdown Icon Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-top .jet-nav-arrow' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nav_items_typography',
				'selector' => '{{WRAPPER}} .menu-item-link-top .jet-nav-link-text',
			),
			50
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'nav_items_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'nav_items_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-top' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'nav_items_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-top' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'nav_items_text_bg_color_hover',
			array(
				'label'  => esc_html__( 'Text Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-top .jet-nav-link-text' => 'background-color: {{VALUE}}',
				),
			),
			75
		);

		$this->__add_control(
			'nav_items_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'nav_items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-top' => 'border-color: {{VALUE}};',
				),
			),
			75
		);

		$this->__add_control(
			'nav_items_text_icon_color_hover',
			array(
				'label'  => esc_html__( 'Dropdown Icon Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-top .jet-nav-arrow' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nav_items_typography_hover',
				'selector' => '{{WRAPPER}} .menu-item:hover > .menu-item-link-top .jet-nav-link-text',
			),
			50
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'nav_items_active',
			array(
				'label' => esc_html__( 'Active', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'nav_items_bg_color_active',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'nav_items_color_active',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'nav_items_text_bg_color_active',
			array(
				'label'  => esc_html__( 'Text Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top .jet-nav-link-text' => 'background-color: {{VALUE}}',
				),
			),
			75
		);

		$this->__add_control(
			'nav_items_active_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'nav_items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top' => 'border-color: {{VALUE}};',
				),
			),
			75
		);

		$this->__add_control(
			'nav_items_text_icon_color_active',
			array(
				'label'  => esc_html__( 'Dropdown Icon Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top .jet-nav-arrow' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nav_items_typography_active',
				'selector' => '{{WRAPPER}} .menu-item.current-menu-item .menu-item-link-top .jet-nav-link-text',
			),
			50
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__add_responsive_control(
			'nav_items_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .menu-item-link-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->__add_responsive_control(
			'nav_items_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav > .jet-nav__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'nav_items_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .menu-item-link-top',
			),
			75
		);

		$this->__add_responsive_control(
			'nav_items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .menu-item-link-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_responsive_control(
			'nav_items_icon_size',
			array(
				'label'      => esc_html__( 'Dropdown Icon Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-top .jet-nav-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .menu-item-link-top .jet-nav-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'nav_items_icon_gap',
			array(
				'label'      => esc_html__( 'Gap Before Dropdown Icon', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .menu-item-link-top .jet-nav-arrow' => ! is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav--vertical-sub-left-side .menu-item-link-top .jet-nav-arrow' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',

					'{{WRAPPER}} .jet-mobile-menu.jet-mobile-menu-trigger-active .jet-nav--vertical-sub-left-side .menu-item-link-top .jet-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
				),
			),
			50
		);

		$this->__add_control(
			'nav_items_desc_heading',
			array(
				'label'     => esc_html__( 'Description', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_items_desc' => 'yes',
				),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'nav_items_desc_typography',
				'selector'  => '{{WRAPPER}} .menu-item-link-top .jet-nav-item-desc',
				'condition' => array(
					'show_items_desc' => 'yes',
				),
			),
			50
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'sub_items_style',
			array(
				'label'      => esc_html__( 'Dropdown', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_control(
			'sub_items_container_style_heading',
			array(
				'label' => esc_html__( 'Container Styles', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
			),
			25
		);

		$this->__add_responsive_control(
			'sub_items_container_width',
			array(
				'label'      => esc_html__( 'Container Width', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 500,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__sub' => 'width: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'name'     => 'layout',
							'operator' => '===',
							'value'    => 'horizontal',
						),
						array(
							'relation' => 'and',
							'terms' => array(
								array(
									'name'     => 'layout',
									'operator' => '===',
									'value'    => 'vertical',
								),
								array(
									'name'     => 'dropdown_position',
									'operator' => '!==',
									'value'    => 'bottom',
								)
							),
						),
					),
				),
			),
			25
		);

		$this->__add_control(
			'sub_items_container_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__sub' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'sub_items_container_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .jet-nav__sub',
			),
			75
		);

		$this->__add_responsive_control(
			'sub_items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav__sub' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav__sub > .menu-item:first-child > .menu-item-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}} .jet-nav__sub > .menu-item:last-child > .menu-item-link' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'sub_items_container_box_shadow',
				'selector' => '{{WRAPPER}} .jet-nav__sub',
			),
			75
		);


		$this->__add_responsive_control(
			'sub_items_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav__sub' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'sub_items_container_top_gap',
			array(
				'label'      => esc_html__( 'Gap Before 1st Level Sub', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav--horizontal .jet-nav-depth-0' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav--vertical-sub-left-side .jet-nav-depth-0' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav--vertical-sub-right-side .jet-nav-depth-0' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'name'     => 'layout',
							'operator' => '===',
							'value'    => 'horizontal',
						),
						array(
							'relation' => 'and',
							'terms' => array(
								array(
									'name'     => 'layout',
									'operator' => '===',
									'value'    => 'vertical',
								),
								array(
									'name'     => 'dropdown_position',
									'operator' => '!==',
									'value'    => 'bottom',
								)
							),
						),
					),
				),
			),
			50
		);

		$this->__add_responsive_control(
			'sub_items_container_left_gap',
			array(
				'label'      => esc_html__( 'Gap Before 2nd Level Sub', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav-depth-0 .jet-nav__sub' => ! is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav--vertical-sub-left-side .jet-nav-depth-0 .jet-nav__sub' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms' => array(
						array(
							'name'     => 'layout',
							'operator' => '===',
							'value'    => 'horizontal',
						),
						array(
							'relation' => 'and',
							'terms' => array(
								array(
									'name'     => 'layout',
									'operator' => '===',
									'value'    => 'vertical',
								),
								array(
									'name'     => 'dropdown_position',
									'operator' => '!==',
									'value'    => 'bottom',
								)
							),
						),
					),
				),
			),
			50
		);

		$this->__add_control(
			'sub_items_style_heading',
			array(
				'label'     => esc_html__( 'Items Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_items_typography',
				'selector' => '{{WRAPPER}} .menu-item-link-sub .jet-nav-link-text',
			),
			50
		);

		$this->__start_controls_tabs( 'tabs_sub_items_style' );

		$this->__start_controls_tab(
			'sub_items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'sub_items_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-sub' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'sub_items_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-sub' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'sub_items_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'sub_items_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-sub' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'sub_items_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item:hover > .menu-item-link-sub' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'sub_items_active',
			array(
				'label' => esc_html__( 'Active', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'sub_items_bg_color_active',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item > .menu-item-link-sub' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'sub_items_color_active',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .menu-item.current-menu-item > .menu-item-link-sub' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__add_responsive_control(
			'sub_items_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .menu-item-link-sub' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->__add_responsive_control(
			'sub_items_icon_size',
			array(
				'label'      => esc_html__( 'Dropdown Icon Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .menu-item-link-sub .jet-nav-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .menu-item-link-sub .jet-nav-arrow svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'sub_items_icon_gap',
			array(
				'label'      => esc_html__( 'Gap Before Dropdown Icon', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .menu-item-link-sub .jet-nav-arrow' => ! is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-nav--vertical-sub-left-side .menu-item-link-sub .jet-nav-arrow' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',

					'{{WRAPPER}} .jet-mobile-menu.jet-mobile-menu-trigger-active .jet-nav--vertical-sub-left-side .menu-item-link-sub .jet-nav-arrow' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
				),
			),
			50
		);

		$this->__add_control(
			'sub_items_divider_heading',
			array(
				'label'     => esc_html__( 'Divider', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'sub_items_divider',
				'selector' => '{{WRAPPER}} .jet-nav__sub > .jet-nav-item-sub:not(:last-child)',
				'exclude'  => array( 'width' ),
			),
			75
		);

		$this->__add_control(
			'sub_items_divider_width',
			array(
				'label' => esc_html__( 'Border Width', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
				),
				'default' => array(
					'size' => 1,
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__sub > .jet-nav-item-sub:not(:last-child)' => 'border-width: 0; border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'sub_items_divider_border!' => '',
				),
			),
			75
		);

		$this->__add_control(
			'sub_items_desc_heading',
			array(
				'label'     => esc_html__( 'Description', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_items_desc' => 'yes',
				),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'sub_items_desc_typography',
				'selector'  => '{{WRAPPER}} .menu-item-link-sub .jet-nav-item-desc',
				'condition' => array(
					'show_items_desc' => 'yes',
				),
			),
			50
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'mobile_trigger_styles',
			array(
				'label'      => esc_html__( 'Mobile Trigger', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'mobile_trigger_visible' => 'yes',
				),
			)
		);

		$this->__start_controls_tabs( 'tabs_mobile_trigger_style' );

		$this->__start_controls_tab(
			'mobile_trigger_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'mobile_trigger_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'mobile_trigger_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'mobile_trigger_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'mobile_trigger_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger:hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'mobile_trigger_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger:hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'mobile_trigger_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'mobile_trigger_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger:hover' => 'border-color: {{VALUE}};',
				),
			),
			75
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'mobile_trigger_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .jet-nav__mobile-trigger',
				'separator'   => 'before',
			),
			75
		);

		$this->__add_control(
			'mobile_trigger_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_control(
			'mobile_trigger_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 200,
					),
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger' => 'width: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			50
		);

		$this->__add_control(
			'mobile_trigger_height',
			array(
				'label'      => esc_html__( 'Height', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger' => 'height: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_control(
			'mobile_trigger_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-nav__mobile-trigger' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'mobile_menu_styles',
			array(
				'label'     => esc_html__( 'Mobile Menu', 'jet-blocks' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'mobile_trigger_visible' => 'yes',
				),
			)
		);

		$this->__add_control(
			'mobile_menu_width',
			array(
				'label' => esc_html__( 'Width', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 150,
						'max' => 400,
					),
					'%' => array(
						'min' => 30,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu-trigger-active .jet-nav' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'mobile_menu_layout' => array(
						'left-side',
						'right-side',
					),
				),
			),
			25
		);

		$this->__add_control(
			'mobile_menu_max_height',
			array(
				'label' => esc_html__( 'Max Height', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 500,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu-trigger-active .jet-nav' => 'max-height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'mobile_menu_layout' => 'full-width',
				),
			),
			25
		);

		$this->__add_control(
			'mobile_menu_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu-trigger-active .jet-nav' => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->__add_control(
			'mobile_menu_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu-trigger-active .jet-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'mobile_menu_box_shadow',
				'selector' => '{{WRAPPER}} .jet-mobile-menu-trigger-active.jet-mobile-menu-active .jet-nav',
			),
			75
		);

		$this->__add_control(
			'mobile_close_icon_heading',
			array(
				'label' => esc_html__( 'Close icon', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'mobile_menu_layout' => array(
						'left-side',
						'right-side',
					),
				),
			),
			25
		);

		$this->__add_control(
			'mobile_close_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-close-btn' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'mobile_menu_layout' => array(
						'left-side',
						'right-side',
					),
				),
			),
			25
		);

		$this->__add_control(
			'mobile_close_icon_font_size',
			array(
				'label' => esc_html__( 'Font size', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-nav__mobile-close-btn' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'mobile_menu_layout' => array(
						'left-side',
						'right-side',
					),
				),
			),
			50
		);

		$this->__end_controls_section();
	}

	/**
	 * Returns available icons for dropdown list
	 *
	 * @return array
	 */
	public function dropdown_arrow_icons_list() {

		return apply_filters( 'jet-blocks/nav-menu/dropdown-icons', array(
			'fa fa-angle-down'          => esc_html__( 'Angle', 'jet-blocks' ),
			'fa fa-angle-double-down'   => esc_html__( 'Angle Double', 'jet-blocks' ),
			'fa fa-chevron-down'        => esc_html__( 'Chevron', 'jet-blocks' ),
			'fa fa-chevron-circle-down' => esc_html__( 'Chevron Circle', 'jet-blocks' ),
			'fa fa-caret-down'          => esc_html__( 'Caret', 'jet-blocks' ),
			'fa fa-plus'                => esc_html__( 'Plus', 'jet-blocks' ),
			'fa fa-plus-square-o'       => esc_html__( 'Plus Square', 'jet-blocks' ),
			'fa fa-plus-circle'         => esc_html__( 'Plus Circle', 'jet-blocks' ),
			''                          => esc_html__( 'None', 'jet-blocks' ),
		) );

	}

	/**
	 * Get available menus list
	 *
	 * @return array
	 */
	public function get_available_menus() {

		$raw_menus = wp_get_nav_menus();
		$menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );

		return $menus;
	}

	protected function render() {

		$settings = $this->get_settings();

		if ( ! $settings['nav_menu'] ) {
			return;
		}

		$trigger_visible       = filter_var( $settings['mobile_trigger_visible'], FILTER_VALIDATE_BOOLEAN );
		$trigger_align         = $settings['mobile_trigger_alignment'];
		$mobile_layout_device  = $settings['mobile_trigger_devices'];

		if ( is_array( $mobile_layout_device ) ) {
			$mobile_layout_device = $mobile_layout_device[0];
		}

		require_once jet_blocks()->plugin_path( 'includes/class-jet-blocks-nav-walker.php' );

		$this->add_render_attribute( 'nav-wrapper', 'class', 'jet-nav-wrap' );

		$this->add_render_attribute( 'nav-wrapper', 'class', 'm-layout-' . $mobile_layout_device );
		
		if ( $trigger_visible ) {
			$this->add_render_attribute( 'nav-wrapper', 'class', 'jet-mobile-menu' );
			$this->add_render_attribute( 'nav-wrapper', 'data-mobile-trigger-device', esc_attr( $mobile_layout_device ) );

			if ( isset( $settings['mobile_menu_layout'] ) ) {
				$this->add_render_attribute( 'nav-wrapper', 'class', sprintf( 'jet-mobile-menu--%s', esc_attr( $settings['mobile_menu_layout'] ) ) );
				$this->add_render_attribute( 'nav-wrapper', 'data-mobile-layout', esc_attr( $settings['mobile_menu_layout'] ) );
			}
		}

		$this->add_render_attribute( 'nav-menu', 'class', 'jet-nav' );

		$this->add_render_attribute( 'nav-menu', 'class', 'm-layout-' . $mobile_layout_device );

		if ( isset( $settings['layout'] ) ) {
			$this->add_render_attribute( 'nav-menu', 'class', 'jet-nav--' . esc_attr( $settings['layout'] ) );

			if ( 'vertical' === $settings['layout'] && isset( $settings['dropdown_position'] ) ) {
				$this->add_render_attribute( 'nav-menu', 'class', 'jet-nav--vertical-sub-' . esc_attr( $settings['dropdown_position'] ) );
			}
		}

		$menu_html = '<div ' . $this->get_render_attribute_string( 'nav-menu' ) . '>%3$s</div>';

		if ( $trigger_visible && in_array( $settings['mobile_menu_layout'], array( 'left-side', 'right-side' ) ) ) {
			$close_btn = $this->__get_icon( 'mobile_trigger_close_icon', '<div class="jet-nav__mobile-close-btn jet-blocks-icon">%s</div>' );

			$menu_html = '<div ' . $this->get_render_attribute_string( 'nav-menu' ) . '>%3$s' . $close_btn . '</div>';
		}

		$top_dropdown_icon_html = $this->__get_icon( 'dropdown_icon', '%s' );
		$sub_dropdown_icon_html = $this->__get_icon( 'dropdown_icon_sub', '%s' );

		$args = array(
			'menu'            => $settings['nav_menu'],
			'fallback_cb'     => '',
			'items_wrap'      => $menu_html,
			'walker'          => new \Jet_Blocks_Nav_Walker,
			'widget_settings' => array(
				'dropdown_icon'     => $top_dropdown_icon_html,
				'dropdown_icon_sub' => $sub_dropdown_icon_html,
				'show_items_desc'   => $settings['show_items_desc'],
			),
		);

		echo '<nav ' . $this->get_render_attribute_string( 'nav-wrapper' ) . '>';
			if ( $trigger_visible ) {
				include $this->__get_global_template( 'mobile-trigger' );
			}
			wp_nav_menu( $args );
		echo '</nav>';

	}
}
