<?php
/**
 * Class: Jet_Unfold_Widget
 * Name: Unfold
 * Slug: jet-unfold
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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Unfold_Widget extends Jet_Tricks_Base {

	public function get_name() {
		return 'jet-unfold';
	}

	public function get_title() {
		return esc_html__( 'Unfold', 'jet-tricks' );
	}

	public function get_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-use-unfold-effect-to-hide-the-extra-content-from-view?utm_source=jettricks&utm_medium=jet-unfold&utm_campaign=need-help';
	}

	public function get_icon() {
		return 'jet-tricks-icon-unfold';
	}

	public function get_categories() {
		return array( 'jet-tricks' );
	}

	public function get_script_depends() {
		return array( 'jet-anime-js' );
	}

	protected function register_controls() {
		$css_scheme = apply_filters(
			'jet-tricks/unfond/css-scheme',
			array(
				'instance'  => '.jet-unfold',
				'inner'     => '.jet-unfold__inner',
				'mask'      => '.jet-unfold__mask',
				'separator' => '.jet-unfold__separator',
				'content'   => '.jet-unfold__content',
				'button'    => '.jet-unfold__button',
			)
		);

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'fold',
			array(
				'label'        => esc_html__( 'Fold', 'jet-tricks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
				'label_off'    => esc_html__( 'No', 'jet-tricks' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'fold_scroll',
			array(
				'label'        => esc_html__( 'Scroll to Top After Hiding Content', 'jet-tricks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
				'label_off'    => esc_html__( 'No', 'jet-tricks' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'autohide',
			array(
				'label'        => esc_html__( 'Fold After a Specified Amount of Time', 'jet-tricks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
				'label_off'    => esc_html__( 'No', 'jet-tricks' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'autohide_time',
			array(
				'label'      => esc_html__( 'Autohide Time (seconds)', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'default' => array(
					'size' => 5,
					'unit' => 'px',
				),
				'render_type' => 'template',
				'condition'   => array(
					'autohide' => 'true'
				)
			)
		);

		$this->add_control(
			'hide_outside_click',
			array(
				'label'        => esc_html__( 'Fold Сontent on Сlick Outside Widget', 'jet-tricks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
				'label_off'    => esc_html__( 'No', 'jet-tricks' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_responsive_control(
			'mask_height',
			array(
				'label'      => esc_html__( 'Closed Height', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 1000,
					),
				),
				'default' => array(
					'size' => 50,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] . ':not(.jet-unfold-state) ' . $css_scheme['content'] => 'max-height: {{SIZE}}{{UNIT}};',
				),
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'separator_height',
			array(
				'label'      => esc_html__( 'Separator Height', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['separator'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'widget_state_settings_heading',
			array(
				'label'     => esc_html__( 'State Styles', 'jet-tricks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_settings' );

		$this->start_controls_tab(
			'tab_unfold_settings',
			array(
				'label' => esc_html__( 'Unfold', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'unfold_duration',
			array(
				'label'      => esc_html__( 'Duration', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'ms' ),
				'range'      => array(
					'ms' => array(
						'min'  => 100,
						'max'  => 3000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 500,
					'unit' => 'ms',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'unfold_easing',
			array(
				'label'       => esc_html__( 'Easing', 'jet-tricks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'easeOutBack',
				'options' => array(
					'linear'        => esc_html__( 'Linear', 'jet-tricks' ),
					'easeOutSine'   => esc_html__( 'Sine', 'jet-tricks' ),
					'easeOutExpo'   => esc_html__( 'Expo', 'jet-tricks' ),
					'easeOutCirc'   => esc_html__( 'Circ', 'jet-tricks' ),
					'easeOutBack'   => esc_html__( 'Back', 'jet-tricks' ),
					'easeInOutSine' => esc_html__( 'InOutSine', 'jet-tricks' ),
					'easeInOutExpo' => esc_html__( 'InOutExpo', 'jet-tricks' ),
					'easeInOutCirc' => esc_html__( 'InOutCirc', 'jet-tricks' ),
					'easeInOutBack' => esc_html__( 'InOutBack', 'jet-tricks' ),
				),
				'frontend_available' => true,
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_fold_settings',
			array(
				'label' => esc_html__( 'Fold', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'fold_duration',
			array(
				'label'      => esc_html__( 'Duration', 'jet-tricks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'ms' ),
				'range'      => array(
					'ms' => array(
						'min'  => 100,
						'max'  => 3000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 300,
					'unit' => 'ms',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'fold_easing',
			array(
				'label'       => esc_html__( 'Easing', 'jet-tricks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'easeOutSine',
				'options' => array(
					'linear'        => esc_html__( 'Linear', 'jet-tricks' ),
					'easeOutSine'   => esc_html__( 'Sine', 'jet-tricks' ),
					'easeOutExpo'   => esc_html__( 'Expo', 'jet-tricks' ),
					'easeOutCirc'   => esc_html__( 'Circ', 'jet-tricks' ),
					'easeOutBack'   => esc_html__( 'Back', 'jet-tricks' ),
					'easeInOutSine' => esc_html__( 'InOutSine', 'jet-tricks' ),
					'easeInOutExpo' => esc_html__( 'InOutExpo', 'jet-tricks' ),
					'easeInOutCirc' => esc_html__( 'InOutCirc', 'jet-tricks' ),
					'easeInOutBack' => esc_html__( 'InOutBack', 'jet-tricks' ),
				),
				'frontend_available' => true,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'jet-tricks' ),
			)
		);

		$this->add_control(
			'content_type',
			array(
				'label'   => esc_html__( 'Content Type', 'jet-tricks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'editor',
				'toggle'  => false,
				'options' => array(
					'editor' => array(
						'title' => esc_html__( 'Editor', 'jet-tricks' ),
						'icon'  => 'fas fa-text-width',
					),
					'template' => array(
						'title' => esc_html__( 'Template', 'jet-tricks' ),
						'icon'  => 'fas fa-file',
					),
				),
			)
		);

		$this->add_control(
			'editor',
			array(
				'label'     => '',
				'type'      => Controls_Manager::WYSIWYG,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => esc_html__( 'I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'jet-tricks' ),
				'condition' => array(
					'content_type' => 'editor',
				),
			)
		);

		$this->add_control(
			'template_id',
			array(
				'label'       => esc_html__( 'Choose Template', 'jet-tricks' ),
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
				'edit_button' => array(
					'active' => true,
					'label'  => __( 'Edit Template', 'jet-tricks' ),
				),
				'condition'   => array(
					'content_type' => 'template',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			array(
				'label' => esc_html__( 'Button', 'jet-tricks' ),
			)
		);

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-tricks' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Start', 'jet-tricks' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tricks' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'jet-tricks' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button' );

		$this->start_controls_tab(
			'tab_fold_button',
			array(
				'label' => esc_html__( 'Fold', 'jet-tricks' ),
			)
		);

		$this->add_control(
			$this->__new_icon_prefix . 'button_fold_icon',
			array(
				'label'            => esc_html__( 'Fold Icon', 'jet-tricks' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'button_fold_icon',
				'default'          => array(
					'value'   => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				),
				'render_type'      => 'template',
			)
		);

		$this->add_control(
			'button_fold_text',
			array(
				'label'   => esc_html__( 'Fold Text', 'jet-tricks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hide', 'jet-tricks' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_unfold_button',
			array(
				'label' => esc_html__( 'Unfold', 'jet-tricks' ),
			)
		);

		$this->add_control(
			$this->__new_icon_prefix . 'button_unfold_icon',
			array(
				'label'            => esc_html__( 'Unfold Icon', 'jet-tricks' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'button_unfold_icon',
				'default'          => array(
					'value'   => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				),
				'render_type'      => 'template',
			)
		);

		$this->add_control(
			'button_unfold_text',
			array(
				'label'   => esc_html__( 'Unfold Text', 'jet-tricks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Show', 'jet-tricks' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Container Style Section
		 */
		$this->__start_controls_section(
			'section_container_style',
			array(
				'label'      => esc_html__( 'Container', 'jet-tricks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_control(
			'container_state_style_heading',
			array(
				'label'     => esc_html__( 'State Styles', 'jet-tricks' ),
				'type'      => Controls_Manager::HEADING,
			),
			25
		);

		$this->__start_controls_tabs( 'tabs_container_style' );

		$this->__start_controls_tab(
			'tab_fold_container',
			array(
				'label' => esc_html__( 'Fold', 'jet-tricks' ),
			)
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_fold_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'container_fold_border',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_fold_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			100
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'tab_unfold_container',
			array(
				'label' => esc_html__( 'UnFold', 'jet-tricks' ),
			)
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_unfold_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . '.jet-unfold-state',
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'container_unfold_border',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'] . '.jet-unfold-state',
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_unfold_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . '.jet-unfold-state',
			),
			100
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__add_control(
			'container_wrapper_style_heading',
			array(
				'label'     => esc_html__( 'Wrapper Styles', 'jet-tricks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			50
		);

		$this->__add_responsive_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'container_margin',
			array(
				'label'      => __( 'Margin', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->__add_responsive_control(
			'container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__end_controls_section();

		/**
		 * Separator Style Section
		 */
		$this->__start_controls_section(
			'section_separator_style',
			array(
				'label'      => esc_html__( 'Separator', 'jet-tricks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'separator_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['separator'],
			),
			25
		);

		$this->__end_controls_section();

		/**
		 * Content Style Section
		 */
		$this->__start_controls_section(
			'section_content_style',
			array(
				'label'      => esc_html__( 'Content', 'jet-tricks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__start_controls_tabs( 'tabs_content_style' );

		$this->__start_controls_tab(
			'tab_fold_content',
			array(
				'label' => esc_html__( 'Fold', 'jet-tricks' ),
			)
		);

		$this->__add_control(
			'fold_content_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fold_content_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
			),
			50
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'tab_unfold_content',
			array(
				'label' => esc_html__( 'Unfold', 'jet-tricks' ),
			)
		);

		$this->__add_control(
			'unfold_content_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-tricks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-unfold-state ' . $css_scheme['content'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'unfold_content_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .jet-unfold-state ' . $css_scheme['content'],
			),
			50
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__end_controls_section();

		/**
		 * Button Style Section
		 */
		$this->__start_controls_section(
			'section_button_style',
			array(
				'label'      => esc_html__( 'Button', 'jet-tricks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
			),
			50
		);

		$this->__add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_control(
			'button_state_style_heading',
			array(
				'label'     => esc_html__( 'States Styles', 'jet-tricks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__start_controls_tabs( 'tabs_button_style' );

		$this->__start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-tricks' ),
			)
		);

		$this->__add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-tricks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'button_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-tricks' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
			),
			50
		);

		$this->__add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
			),
			100
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-tricks' ),
			)
		);

		$this->__add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-tricks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-tricks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-tricks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			),
			50
		);

		$this->__add_responsive_control(
			'button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tricks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			),
			100
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__end_controls_section();
	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$json_settings = array(
			'height'           => $settings['mask_height'],
			'separatorHeight'  => $settings['separator_height'],
			'unfoldDuration'   => $settings['unfold_duration'],
			'foldDuration'     => $settings['fold_duration'],
			'unfoldEasing'     => $settings['unfold_easing'],
			'foldEasing'       => $settings['fold_easing'],
			'foldScrolling'    => $settings['fold_scroll'],
			'hideOutsideClick' => $settings['hide_outside_click'],
			'autoHide'         => $settings['autohide'],
			'autoHideTime'     => ! empty( $settings['autohide_time'] ) ? $settings['autohide_time'] : ''
		);

		$this->add_render_attribute( 'instance', array(
			'class' => array(
				'jet-unfold',
				filter_var( $settings['fold'], FILTER_VALIDATE_BOOLEAN ) ? 'jet-unfold-state' : '',
			),
			'data-settings' => json_encode( $json_settings ),
		) );

		$this->add_render_attribute( 'button', array(
			'class' => array(
				'jet-unfold__button',
				'elementor-button',
				'elementor-size-md',
			),
			'href'             => '#',
			'data-unfold-text' => $settings['button_unfold_text'],
			'data-fold-text'   => $settings['button_fold_text'],
			'data-fold-icon'   => htmlspecialchars( $this->__get_icon( 'button_fold_icon', $settings ) ),
			'data-unfold-icon' => htmlspecialchars( $this->__get_icon( 'button_unfold_icon', $settings ) ),
		) );

		$content_type = ! empty( $settings['content_type'] ) ? $settings['content_type'] : 'editor';

		$this->add_render_attribute( 'editor', 'class', array( 'jet-unfold__content-inner' ) );

		switch ( $content_type ) :
			case 'editor':
				$editor_content = $this->get_settings_for_display( 'editor' );
				$editor_content = $this->parse_text_editor( $editor_content );

				$this->add_render_attribute( 'editor', 'class', array( 'elementor-text-editor', 'elementor-clearfix' ) );
				$this->add_inline_editing_attributes( 'editor', 'advanced' );
				break;

			case 'template':
				$template_id = $settings['template_id'];

				if ( ! empty( $template_id ) ) {

					// for multi-language plugins
					$template_id = apply_filters( 'jet-tricks/widgets/template_id', $template_id, $this );

					$editor_content = jet_tricks()->elementor()->frontend->get_builder_content_for_display( $template_id );

					if ( jet_tricks()->elementor()->editor->is_edit_mode() ) {
						$edit_url = add_query_arg(
							array(
								'elementor' => '',
							),
							get_permalink( $template_id )
						);

						$edit_link = sprintf(
							'<a class="jet-tricks-edit-template-link" href="%s" target="_blank"><i class="fas fa-pencil-alt"></i><span>%s</span></a>',
							esc_url( $edit_url ),
							esc_html__( 'Edit Template', 'jet-tricks' )
						);

						$editor_content .= $edit_link;
					}
				} else {
					$editor_content = $this->no_templates_message();
				}

				break;

		endswitch;

		$button_icon_key  = ! filter_var( $settings['fold'], FILTER_VALIDATE_BOOLEAN ) ? 'button_unfold_icon' : 'button_fold_icon';
		$button_icon      = $this->__get_icon( $button_icon_key, $settings );
		$button_icon_html = sprintf( '<span class="jet-unfold__button-icon jet-tricks-icon">%s</span>', $button_icon );

		$fold_text        = ! empty( $settings['button_fold_text'] ) ? $settings['button_fold_text'] : '';
		$unfold_text      = ! empty( $settings['button_unfold_text'] ) ? $settings['button_unfold_text'] : '';
		$button_text      = ! filter_var( $settings['fold'], FILTER_VALIDATE_BOOLEAN ) ? $unfold_text : $fold_text;
		$button_text_html = sprintf( '<span class="jet-unfold__button-text">%1$s</span>', $button_text );

		?>
		<div <?php echo $this->get_render_attribute_string( 'instance' ); ?>>
			<div class="jet-unfold__inner">
				<div class="jet-unfold__mask">
					<div class="jet-unfold__content">
						<div <?php echo $this->get_render_attribute_string( 'editor' ); ?>><?php echo $editor_content; ?></div>
					</div>
					<div class="jet-unfold__separator"></div>
				</div>
				<div class="jet-unfold__trigger"><?php
					echo sprintf( '<div %1$s tabindex="0" role="button">%2$s%3$s</div>',
						$this->get_render_attribute_string( 'button' ),
						$button_icon_html,
						$button_text_html
					);?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * No templates message
	 *
	 * @return string
	 */
	public function no_templates_message() {
		return sprintf(
			'<div class="jet-tricks-no-template-message">%s</div>',
			esc_html__( 'Template is not defined. ', 'jet-tricks' )
		);
	}
}
