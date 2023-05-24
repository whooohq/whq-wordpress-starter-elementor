<?php
/**
 * Class: Jet_Tabs_Widget
 * Name: Tabs
 * Slug: jet-tabs
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
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Tabs_Widget extends Jet_Tabs_Base {

	public function get_name() {
		return 'jet-tabs';
	}

	public function get_title() {
		return esc_html__( 'Tabs', 'jet-tabs' );
	}

	public function get_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jettabs-tabs-widget-how-to-arrange-the-content-built-with-elementor-inside-the-tabs?utm_source=jettabs&utm_medium=jet-tabs&utm_campaign=need-help';
	}

	public function get_icon() {
		return 'jet-tabs-icon-tabs';
	}

	public function get_categories() {
		return array( 'jet-tabs' );
	}

	protected function register_controls() {
		$css_scheme = apply_filters(
			'jet-tabs/tabs/css-scheme',
			array(
				'instance'        => '> .elementor-widget-container > .jet-tabs',
				'control_wrapper' => '> .elementor-widget-container > .jet-tabs > .jet-tabs__control-wrapper',
				'control'         => '> .elementor-widget-container > .jet-tabs > .jet-tabs__control-wrapper > .jet-tabs__control',
				'content_wrapper' => '> .elementor-widget-container > .jet-tabs > .jet-tabs__content-wrapper',
				'content'         => '> .elementor-widget-container > .jet-tabs > .jet-tabs__content-wrapper > .jet-tabs__content',
				'label'           => '.jet-tabs__label-text',
				'icon'            => '.jet-tabs__label-icon',
			)
		);

		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Items', 'jet-tabs' ),
			)
		);

		do_action( 'jet-engine-query-gateway/control', $this, 'tabs' );

		$repeater = new Repeater();

		$repeater->add_control(
			'item_active',
			array(
				'label'        => esc_html__( 'Active', 'jet-tabs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tabs' ),
				'label_off'    => esc_html__( 'No', 'jet-tabs' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$repeater->add_control(
			'item_use_image',
			array(
				'label'        => esc_html__( 'Use Image?', 'jet-tabs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tabs' ),
				'label_off'    => esc_html__( 'No', 'jet-tabs' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$repeater->add_control(
			$this->__new_icon_prefix . 'item_icon',
			array(
				'label'            => esc_html__( 'Icon', 'jet-tabs' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'item_icon',
				'default'          => array(
					'value'   => 'fas fa-arrow-circle-right',
					'library' => 'fa-solid',
				),
			)
		);

		$repeater->add_control(
			'item_image',
			array(
				'label'   => esc_html__( 'Image', 'jet-tabs' ),
				'type'    => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'item_label',
			array(
				'label'   => esc_html__( 'Label', 'jet-tabs' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'New Tab', 'jet-tabs' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'content_type',
			array(
				'label'       => esc_html__( 'Content Type', 'jet-tabs' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'template',
				'options'     => array(
					'template' => esc_html__( 'Template', 'jet-tabs' ),
					'editor'   => esc_html__( 'Editor', 'jet-tabs' ),
				),
				'label_block' => 'true',
			)
		);

		$repeater->add_control(
			'item_template_id',
			array(
				'label'       => esc_html__( 'Choose Template', 'jet-tabs' ),
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
				'edit_button' => array(
					'active' => true,
					'label'  => esc_html__( 'Edit Template', 'jet-tabs' ),
				),
				'condition'   => array(
					'content_type' => 'template',
				)
			)
		);

		$repeater->add_control(
			'item_editor_content',
			array(
				'label'      => esc_html__( 'Content', 'jet-tabs' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => esc_html__( 'Tab Item Content', 'jet-tabs' ),
				'dynamic' => array(
					'active' => true,
				),
				'condition'   => array(
					'content_type' => 'editor',
				)
			)
		);

		$repeater->add_control(
			'control_id',
			array(
				'label'   => esc_html__( 'Control CSS ID', 'jet-tabs' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'tabs',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'item_label' => esc_html__( 'Tab #1', 'jet-tabs' ),
					),
					array(
						'item_label' => esc_html__( 'Tab #2', 'jet-tabs' ),
					),
					array(
						'item_label' => esc_html__( 'Tab #3', 'jet-tabs' ),
					),
				),
				'title_field' => '{{{ item_label }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings_data',
			array(
				'label' => esc_html__( 'Settings', 'jet-tabs' ),
			)
		);

		$this->add_control(
			'item_html_tag',
			array(
				'label'   => esc_html__( 'Item Label HTML Tag', 'jet-tabs' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_available_item_html_tags(),
				'default' => 'div',
			)
		);

		$this->add_responsive_control(
			'tabs_position',
			array(
				'label'   => esc_html__( 'Tabs Position', 'jet-tabs' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'left'   => esc_html__( 'Left', 'jet-tabs' ),
					'top'    => esc_html__( 'Top', 'jet-tabs' ),
					'right'  => esc_html__( 'Right', 'jet-tabs' ),
					'bottom' => esc_html__( 'Bottom', 'jet-tabs' ),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'show_effect',
			array(
				'label'   => esc_html__( 'Show Effect', 'jet-tabs' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'move-up',
				'options' => array(
					'none'             => esc_html__( 'None', 'jet-tabs' ),
					'fade'             => esc_html__( 'Fade', 'jet-tabs' ),
					'zoom-in'          => esc_html__( 'Zoom In', 'jet-tabs' ),
					'zoom-out'         => esc_html__( 'Zoom Out', 'jet-tabs' ),
					'move-up'          => esc_html__( 'Move Up', 'jet-tabs' ),
					'fall-perspective' => esc_html__( 'Fall Perspective', 'jet-tabs' ),
				),
			)
		);

		$this->add_control(
			'tabs_event',
			array(
				'label'   => esc_html__( 'Tabs Event', 'jet-tabs' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'click',
				'options' => array(
					'click' => esc_html__( 'Click', 'jet-tabs' ),
					'hover' => esc_html__( 'Hover', 'jet-tabs' ),
				),
			)
		);

		$this->add_control(
			'auto_switch',
			array(
				'label'        => esc_html__( 'Auto Switch', 'jet-tabs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'jet-tabs' ),
				'label_off'    => esc_html__( 'Off', 'jet-tabs' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'auto_switch_delay',
			array(
				'label'     => esc_html__( 'Auto Switch Delay', 'jet-tabs' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3000,
				'min'       => 1000,
				'max'       => 20000,
				'step'      => 100,
				'condition' => array(
					'auto_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'no_active_tabs',
			array(
				'label'              => esc_html__( 'No Active Tabs', 'jet-tabs' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'On', 'jet-tabs' ),
				'label_off'          => esc_html__( 'Off', 'jet-tabs' ),
				'return_value'       => 'yes',
				'default'            => 'false',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'ajax_template',
			array(
				'label'        => esc_html__( 'Use Ajax Loading for Template', 'jet-tabs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'jet-tabs' ),
				'label_off'    => esc_html__( 'Off', 'jet-tabs' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'tab_control_switching',
			array(
				'label'        => esc_html__( 'Scrolling to the Content', 'jet-tabs' ),
				'description'  => esc_html__( 'Scrolling to the Content after Switching Tab Control', 'jet-tabs' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'jet-tabs' ),
				'label_off'    => esc_html__( 'Off', 'jet-tabs' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'tab_control_switching_offset',
			array(
				'label' => esc_html__( 'Scrolling offset (px)', 'jet-tabs' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 0,
				),
				'condition' => array(
					'tab_control_switching' => 'yes'
				)
			)
		);

		$this->end_controls_section();

		$this->__start_controls_section(
			'section_general_style',
			array(
				'label'      => esc_html__( 'General', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'tabs_control_wrapper_width',
			array(
				'label'      => esc_html__( 'Tabs Control Width', 'jet-tabs' ),
				'description' => esc_html__( 'Working with left or right tabs position', 'jet-tabs' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'%' => array(
						'min' => 10,
						'max' => 50,
					),
					'px' => array(
						'min' => 100,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} > .elementor-widget-container > .jet-tabs.jet-tabs-position-left > .jet-tabs__control-wrapper' => 'min-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} > .elementor-widget-container > .jet-tabs.jet-tabs-position-right > .jet-tabs__control-wrapper' => 'min-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} > .elementor-widget-container > .jet-tabs.jet-tabs-position-left > .jet-tabs__content-wrapper' => 'min-width: calc(100% - {{SIZE}}{{UNIT}})',
					'{{WRAPPER}} > .elementor-widget-container > .jet-tabs.jet-tabs-position-right > .jet-tabs__content-wrapper' => 'min-width: calc(100% - {{SIZE}}{{UNIT}})',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'tabs_container_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'tabs_container_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tabs_container_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			100
		);

		$this->__add_responsive_control(
			'tabs_container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tabs_container_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			100
		);

		$this->__end_controls_section();

		/**
		 * Tabs Control Style Section
		 */
		$this->__start_controls_section(
			'section_tabs_control_style',
			array(
				'label'      => esc_html__( 'Tabs Control', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'tabs_controls_container_aligment',
			array(
				'label'   => esc_html__( 'Tabs Container Alignment', 'jet-tabs' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start' => array(
						'title' => ! is_rtl() ? esc_html__( 'Start', 'jet-tabs' ) : esc_html__( 'End', 'jet-tabs' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => ! is_rtl() ? esc_html__( 'End', 'jet-tabs' ) : esc_html__( 'Start', 'jet-tabs' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
					'stretch' => array(
						'title' => esc_html__( 'Stretch', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control_wrapper'] => 'align-self: {{VALUE}};',
				),
			),
			100
		);

		$this->__add_responsive_control(
			'tabs_controls_aligment',
			array(
				'label'   => esc_html__( 'Tabs Alignment', 'jet-tabs' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start' => array(
						'title' => ! is_rtl() ? esc_html__( 'Start', 'jet-tabs' ) : esc_html__( 'End', 'jet-tabs' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => ! is_rtl() ? esc_html__( 'End', 'jet-tabs' ) : esc_html__( 'Start', 'jet-tabs' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
					'stretch' => array(
						'title' => esc_html__( 'Stretch', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'selectors_dictionary' => array(
					'flex-start' => 'justify-content: flex-start; flex-grow: 0;',
					'center'     => 'justify-content: center; flex-grow: 0;',
					'flex-end'   => 'justify-content: flex-end; flex-grow: 0;',
					'stretch'    => 'justify-content: stretch; flex-grow: 1;',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control_wrapper'] => '{{VALUE}}',
				),
			),
			100
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'tabs_content_wrapper_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control_wrapper'],
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_control_wrapper_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control_wrapper'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'tabs_control_wrapper_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control_wrapper'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tabs_control_wrapper_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['control_wrapper'],
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_control_wrapper_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control_wrapper'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tabs_control_wrapper_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control_wrapper'],
			),
			100
		);

		$this->__end_controls_section();

		/**
		 * Tabs Control Style Section
		 */
		$this->__start_controls_section(
			'section_tabs_control_item_style',
			array(
				'label'      => esc_html__( 'Tabs Control Item', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'tabs_controls_item_aligment_left_right_icon',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-tabs' ),
				'description' => esc_html__( 'Working with left or right tabs position and left or right icon position', 'jet-tabs' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Start', 'jet-tabs' ),
						'icon'  => 'eicon-arrow-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tabs' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'jet-tabs' ),
						'icon'  => 'eicon-arrow-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} > .elementor-widget-container > .jet-tabs.jet-tabs-position-left > .jet-tabs__control-wrapper > .jet-tabs__control.jet-tabs__control-icon-left .jet-tabs__control-inner' => 'justify-content: {{VALUE}};',

					'{{WRAPPER}} > .elementor-widget-container > .jet-tabs.jet-tabs-position-left > .jet-tabs__control-wrapper > .jet-tabs__control.jet-tabs__control-icon-right .jet-tabs__control-inner' => 'justify-content: {{VALUE}};',

					'{{WRAPPER}} > .elementor-widget-container > .jet-tabs.jet-tabs-position-right > .jet-tabs__control-wrapper > .jet-tabs__control.jet-tabs__control-icon-left .jet-tabs__control-inner' => 'justify-content: {{VALUE}};',

					'{{WRAPPER}} > .elementor-widget-container > .jet-tabs.jet-tabs-position-right > .jet-tabs__control-wrapper > .jet-tabs__control.jet-tabs__control-icon-right .jet-tabs__control-inner' => 'justify-content: {{VALUE}};',
				),
				'classes' => 'jet-tabs-text-align-control',
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_controls_item_aligment_top_icon',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-tabs' ),
				'description' => esc_html__( 'Working with left or right tabs position and top icon position', 'jet-tabs' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => ! is_rtl() ? esc_html__( 'Start', 'jet-tabs' ) : esc_html__( 'End', 'jet-tabs' ),
						'icon'  => ! is_rtl() ? 'eicon-arrow-left' : 'eicon-arrow-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tabs' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end' => array(
						'title' => ! is_rtl() ? esc_html__( 'End', 'jet-tabs' ) : esc_html__( 'Start', 'jet-tabs' ),
						'icon'  => ! is_rtl() ? 'eicon-arrow-right' : 'eicon-arrow-left',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} > .elementor-widget-container > .jet-tabs.jet-tabs-position-left > .jet-tabs__control-wrapper > .jet-tabs__control.jet-tabs__control-icon-top .jet-tabs__control-inner' => 'align-items: {{VALUE}};',

					'{{WRAPPER}} > .elementor-widget-container > .jet-tabs.jet-tabs-position-right > .jet-tabs__control-wrapper > .jet-tabs__control.jet-tabs__control-icon-top .jet-tabs__control-inner' => 'align-items: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'tabs_control_icon_style_devider',
			array(
				'type'      => Controls_Manager::DIVIDER,
			)
		);

		$this->__add_control(
			'tabs_control_icon_style_heading',
			array(
				'label'     => esc_html__( 'Icon Styles', 'jet-tabs' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->__add_responsive_control(
			'tabs_control_icon_margin',
			array(
				'label'      => esc_html__( 'Icon Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' .jet-tabs__label-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'tabs_control_image_margin',
			array(
				'label'      => esc_html__( 'Image Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' .jet-tabs__label-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->__add_responsive_control(
			'tabs_control_image_width',
			array(
				'label'      => esc_html__( 'Image Width', 'jet-tabs' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' .jet-tabs__label-image' => 'width: {{SIZE}}{{UNIT}}',
				),
			),
			100
		);

		$this->__add_control(
			'tabs_control_icon_position',
			array(
				'label'   => esc_html__( 'Icon Position', 'jet-tabs' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-tabs' ),
					'top'   => esc_html__( 'Top', 'jet-tabs' ),
					'right' => esc_html__( 'Right', 'jet-tabs' ),
				),
			),
			50
		);

		$this->__add_control(
			'tabs_control_state_style_heading',
			array(
				'label'     => esc_html__( 'State Styles', 'jet-tabs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->__start_controls_tabs( 'tabs_control_styles' );

		$this->__start_controls_tab(
			'tabs_control_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-tabs' ),
			)
		);

		$this->__add_control(
			'tabs_control_label_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-tabs' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tabs_control_label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} '. $css_scheme['control'] . ' ' . $css_scheme['label'],
			),
			50
		);

		$this->__add_control(
			'tabs_control_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-tabs' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' ' . $css_scheme['icon'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_control_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-tabs' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range'      => array(
					'px' => array(
						'min' => 18,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'tabs_control_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'],
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_control_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ' .jet-tabs__control-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'tabs_control_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tabs_control_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['control'],
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_control_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tabs_control_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'],
			),
			100
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'tabs_control_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-tabs' ),
			)
		);

		$this->__add_control(
			'tabs_control_label_color_hover',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-tabs' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tabs_control_label_typography_hover',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['label'],
			),
			50
		);

		$this->__add_control(
			'tabs_control_icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-tabs' ),
				'type'      => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['icon'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_control_icon_size_hover',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-tabs' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range'      => array(
					'px' => array(
						'min' => 18,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ':hover ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'tabs_control_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover',
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_control_padding_hover',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ':hover' . ' .jet-tabs__control-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'tabs_control_margin_hover',
			array(
				'label'      => esc_html__( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tabs_control_border_hover',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover',
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_control_border_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tabs_control_box_shadow_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . ':hover',
			),
			100
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'tabs_control_active',
			array(
				'label' => esc_html__( 'Active', 'jet-tabs' ),
			)
		);

		$this->__add_control(
			'tabs_control_label_color_active',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-tabs' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tabs_control_label_typography_active',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab ' . $css_scheme['label'],
			),
			50
		);

		$this->__add_control(
			'tabs_control_icon_color_active',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-tabs' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab ' . $css_scheme['icon'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_control_icon_size_active',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-tabs' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range'      => array(
					'px' => array(
						'min' => 18,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'tabs_control_background_active',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab',
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_control_padding_active',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab' . ' .jet-tabs__control-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'tabs_control_margin_active',
			array(
				'label'      => esc_html__( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tabs_control_border_active',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab',
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_control_border_radius_active',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tabs_control_box_shadow_active',
				'selector' => '{{WRAPPER}} ' . $css_scheme['control'] . '.active-tab',
			),
			100
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__end_controls_section();

		/**
		 * Tabs Content Style Section
		 */
		$this->__start_controls_section(
			'section_tabs_content_style',
			array(
				'label'      => esc_html__( 'Tabs Content', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_control(
			'tabs_content_text_color',
			array(
				'label'     => esc_html__( 'Text color', 'jet-tabs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tabs_content_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'tabs_content_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content_wrapper'],
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tabs_content_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['content_wrapper'],
			),
			25
		);

		$this->__add_responsive_control(
			'tabs_content_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content_wrapper'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tabs_content_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content_wrapper'],
			),
			100
		);

		$this->__add_control(
			'tabs_content_loader_style_heading',
			array(
				'label'     => esc_html__( 'Loader Styles', 'jet-tabs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'ajax_template' => 'yes',
				),
			),
			25
		);

		$this->__add_control(
			'tabs_content_loader_color',
			array(
				'label'     => esc_html__( 'Loader color', 'jet-tabs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['content'] . ' .jet-tabs-loader' => 'border-color: {{VALUE}}; border-top-color: white;',
				),
				'condition' => array(
					'ajax_template' => 'yes',
				),
			),
			25
		);

		$this->__end_controls_section();
	}

	public function get_tab_item_content( $item = array(), $index = 0, $args = array() ) {

		$tab_count = $index + 1;
		$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );
		$id_int = $args['id_int'];
		$active_index = $args['active_index'];
		$no_active_tabs = $args['no_active_tabs'];
		$ajax_template = $args['ajax_template'];

		$this->add_render_attribute( $tab_content_setting_key, array(
			'id'               => 'jet-tabs-content-' . $id_int . $tab_count,
			'class'            => array(
				'jet-tabs__content',
				( $index === $active_index && ! $no_active_tabs ) ? 'active-content' : '',
			),
			'data-tab'         => $tab_count,
			'role'             => 'tabpanel',
			'aria-hidden'      => $index === $active_index ? 'false' : 'true',
			'data-template-id' => ! empty( $item['item_template_id'] ) ? $item['item_template_id'] : 'false',
		) );

		$content_html = '';

		switch ( $item[ 'content_type' ] ) {
			case 'template':

				if ( ! empty( $item['item_template_id'] ) ) {

					// for multi-language plugins
					$template_id = apply_filters( 'jet-tabs/widgets/template_id', $item['item_template_id'], $this );

					$template_content = jet_tabs()->elementor()->frontend->get_builder_content_for_display( $template_id );

					if ( ! empty( $template_content ) ) {

						if ( ! $ajax_template ) {
							$content_html .= $template_content;
						} else {
							$content_html .= '<div class="jet-tabs-loader"></div>';
						}

						if ( jet_tabs_integration()->is_edit_mode() ) {
							$link = add_query_arg(
								array(
									'elementor' => '',
								),
								get_permalink( $item['item_template_id'] )
							);

							$content_html .= sprintf( '<div class="jet-tabs__edit-cover" data-template-edit-link="%s"><i class="fas fa-pencil-alt"></i><span>%s</span></div>', $link, esc_html__( 'Edit Template', 'jet-tabs' ) );
						}
					} else {
						$content_html = $this->no_template_content_message();
					}
				} else {
					$content_html = $this->no_templates_message();
				}
			break;

			case 'editor':
				$content_html = $this->parse_text_editor( $item['item_editor_content'] );
			break;
		}

		return sprintf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( $tab_content_setting_key ), $content_html );

	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$this->__context = 'render';

		$tabs = $this->get_settings_for_display( 'tabs' );
		$tabs = apply_filters( 'jet-tabs/widget/loop-items', $tabs, 'tabs', $this );

		if ( ! $tabs || empty( $tabs ) ) {
			return false;
		}

		$id_int = substr( $this->get_id_int(), 0, 3 );

		$tabs_position        = $this->get_settings( 'tabs_position' );
		$tabs_position_tablet = $this->get_settings( 'tabs_position_tablet' );
		$tabs_position_mobile = $this->get_settings( 'tabs_position_mobile' );
		$show_effect          = $this->get_settings( 'show_effect' );
		$no_active_tabs       = filter_var( $this->get_settings( 'no_active_tabs' ), FILTER_VALIDATE_BOOLEAN );
		$ajax_template        = filter_var( $this->get_settings( 'ajax_template' ), FILTER_VALIDATE_BOOLEAN );
		$tabs_item_label_tag  = ! empty( $this->get_settings( 'item_html_tag' ) ) ? $this->get_settings( 'item_html_tag' ) : 'div';

		$active_index = 0;

		foreach ( $tabs as $index => $item ) {
			if ( array_key_exists( 'item_active', $item ) && filter_var( $item['item_active'], FILTER_VALIDATE_BOOLEAN ) ) {
				$active_index = $index;
			}

			if ( 'template' === $item['content_type'] && ! empty( $item['item_template_id'] ) ) {

				$post_status = get_post_status( $item['item_template_id'] );

				if ( 'draft' === $post_status || 'trash' === $post_status ) {
					array_splice( $tabs, $index, 1 );
				}

			}
		}

		$settings = array(
			'activeIndex'           => ! $no_active_tabs ? $active_index : -1,
			'event'                 => $this->get_settings( 'tabs_event' ),
			'autoSwitch'            => filter_var( $this->get_settings( 'auto_switch' ), FILTER_VALIDATE_BOOLEAN ),
			'autoSwitchDelay'       => $this->get_settings( 'auto_switch_delay' ),
			'ajaxTemplate'          => $ajax_template,
			'tabsPosition'          => $tabs_position,
			'switchScrolling'       => filter_var( $this->get_settings( 'tab_control_switching' ), FILTER_VALIDATE_BOOLEAN ),
			'switchScrollingOffset' => ! empty( $this->get_settings_for_display( 'tab_control_switching_offset' ) ) ? $this->get_settings_for_display( 'tab_control_switching_offset' ) : 0
		);

		$this->add_render_attribute( 'instance', array(
			'class'         => array(
				'jet-tabs',
				'jet-tabs-position-' . $tabs_position,
				'jet-tabs-' . $show_effect . '-effect',
				( $ajax_template ) ? 'jet-tabs-ajax-template' : '',
			),
			'data-settings' => json_encode( $settings ),
			'role'          => 'tablist',
		) );

		$tabs_content = array();

		?>
		<div <?php echo $this->get_render_attribute_string( 'instance' ); ?>>
			<div class="jet-tabs__control-wrapper">
				<?php
					foreach ( $tabs as $index => $item ) {

						do_action( 'jet-engine-query-gateway/do-item', $item );

						$tab_count = $index + 1;
						$tab_title_setting_key = $this->get_repeater_setting_key( 'jet_tab_control', 'tabs', $index );
						$tab_control_id = ! empty( $item['control_id'] ) ? esc_attr( $item['control_id'] ) : 'jet-tabs-control-' . $id_int . $tab_count;

						$this->add_render_attribute( $tab_title_setting_key, array(
							'id'               => $tab_control_id,
							'class'            => array(
								'jet-tabs__control',
								'jet-tabs__control-icon-' . $this->get_settings( 'tabs_control_icon_position' ),
								'elementor-menu-anchor',
								( $index === $active_index && ! $no_active_tabs ) ? 'active-tab' : '',
							),
							'data-tab'         => $tab_count,
							'tabindex'         => 0,
							'role'             => 'tab',
							'aria-controls'    => 'jet-tabs-content-' . $id_int . $tab_count,
							'aria-expanded'    => $index === $active_index ? 'true' : 'false',
							'data-template-id' => ! empty( $item['item_template_id'] ) ? $item['item_template_id'] : 'false',
						) );

						$title_icon_html = $this->__get_icon( 'item_icon', $item, '<div class="jet-tabs__label-icon jet-tabs-icon">%s</div>' );

						$title_image_html = '';

						if ( ! empty( $item['item_image']['url'] ) ) {
							$title_image_html = sprintf( '<img class="jet-tabs__label-image" src="%1$s" alt="">', $item['item_image']['url'] );
						}

						$title_label_html = '';

						if ( ! empty( $item['item_label'] ) ) {
							$title_label_html = sprintf( '<' . $tabs_item_label_tag . ' class="jet-tabs__label-text">%1$s</' . $tabs_item_label_tag . '>', $item['item_label'] );
						}

						if ( 'right' === $this->get_settings( 'tabs_control_icon_position' ) ) {
							echo sprintf(
								'<div %1$s><div class="jet-tabs__control-inner">%2$s%3$s</div></div>',
								$this->get_render_attribute_string( $tab_title_setting_key ),
								$title_label_html,
								filter_var( $item['item_use_image'], FILTER_VALIDATE_BOOLEAN ) ? $title_image_html : $title_icon_html
							);
						} else {
							echo sprintf(
								'<div %1$s><div class="jet-tabs__control-inner">%2$s%3$s</div></div>',
								$this->get_render_attribute_string( $tab_title_setting_key ),
								filter_var( $item['item_use_image'], FILTER_VALIDATE_BOOLEAN ) ? $title_image_html : $title_icon_html,
								$title_label_html
							);
						}

						$tabs_content[] = $this->get_tab_item_content( $item, $index, array(
							'id_int' => $id_int,
							'active_index' => $active_index,
							'no_active_tabs' => $no_active_tabs,
							'ajax_template' => $ajax_template,
						) );

					}

					do_action( 'jet-engine-query-gateway/reset-item' );
				?>
			</div>
			<div class="jet-tabs__content-wrapper">
				<?php
					foreach ( $tabs_content as $index => $tab ) {
						echo $tab;
					}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * [empty_templates_message description]
	 * @return [type] [description]
	 */
	public function empty_templates_message() {
		return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
				<div class="elementor-widget-template-empty-templates-title">' . esc_html__( 'You Haven’t Saved Templates Yet.', 'jet-tabs' ) . '</div>
				<div class="elementor-widget-template-empty-templates-footer">' . esc_html__( 'What is Library?', 'jet-tabs' ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . esc_html__( 'Read our tutorial on using Library templates.', 'jet-tabs' ) . '</a></div>
				</div>';
	}

	/**
	 * [no_templates_message description]
	 * @return [type] [description]
	 */
	public function no_templates_message() {
		$message = '<span>' . esc_html__( 'Template is not defined. ', 'jet-tabs' ) . '</span>';

		$link = add_query_arg(
			array(
				'post_type'     => 'elementor_library',
				'action'        => 'elementor_new_post',
				'_wpnonce'      => wp_create_nonce( 'elementor_action_new_post' ),
				'template_type' => 'section',
			),
			esc_url( admin_url( '/edit.php' ) )
		);

		$new_link = '<span>' . esc_html__( 'Select an existing template or create a ', 'jet-tabs' ) . '</span><a class="jet-tabs-new-template-link elementor-clickable" target="_blank" href="' . $link . '">' . esc_html__( 'new one', 'jet-tabs' ) . '</a>' ;

		return sprintf(
			'<div class="jet-tabs-no-template-message">%1$s%2$s</div>',
			$message,
			jet_tabs_integration()->in_elementor() ? $new_link : ''
		);
	}

	/**
	 * [no_template_content_message description]
	 * @return [type] [description]
	 */
	public function no_template_content_message() {
		$message = '<span>' . esc_html__( 'The tabs are working. Please, note, that you have to add a template to the library in order to be able to display it inside the tabs.', 'jet-tabs' ) . '</span>';

		return sprintf( '<div class="jet-toogle-no-template-message">%1$s</div>', $message );
	}

	/**
	 * [get_template_edit_link description]
	 * @param  [type] $template_id [description]
	 * @return [type]              [description]
	 */
	public function get_template_edit_link( $template_id ) {

		$link = add_query_arg( 'elementor', '', get_permalink( $template_id ) );

		return '<a target="_blank" class="elementor-edit-template elementor-clickable" href="' . $link .'"><i class="fas fa-pencil"></i> ' . esc_html__( 'Edit Template', 'jet-tabs' ) . '</a>';
	}

}
