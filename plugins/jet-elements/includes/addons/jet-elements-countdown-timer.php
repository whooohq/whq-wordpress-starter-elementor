<?php
/**
 * Class: Jet_Elements_Countdown_Timer
 * Name: Countdown Timer
 * Slug: jet-countdown-timer
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
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Countdown_Timer extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-countdown-timer';
	}

	public function get_title() {
		return esc_html__( 'Countdown Timer', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-countdown-timer';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetelements-countdown-timer-widget-how-to-create-a-countdown-timer/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-elements/jet-countdown-timer/css-scheme',
			array(
				'container' => '.jet-countdown-timer',
				'item'      => '.jet-countdown-timer__item',
				'label'     => '.jet-countdown-timer__item-label',
				'value'     => '.jet-countdown-timer__item-value',
				'sep'       => '.jet-countdown-timer__separator',
				'digit'     => '.jet-countdown-timer__digit',
				'message'   => '.jet-countdown-timer-message',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'General', 'jet-elements' ),
			)
		);

		$this->add_control(
			'show_days',
			array(
				'label'        => esc_html__( 'Days', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-elements' ),
				'label_off'    => esc_html__( 'Hide', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'label_days',
			array(
				'label'       => esc_html__( 'Days Label', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Days', 'jet-elements' ),
				'placeholder' => esc_html__( 'Days', 'jet-elements' ),
				'condition'   => array(
					'show_days' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_hours',
			array(
				'label'        => esc_html__( 'Hours', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-elements' ),
				'label_off'    => esc_html__( 'Hide', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'label_hours',
			array(
				'label'       => esc_html__( 'Hours Label', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Hours', 'jet-elements' ),
				'placeholder' => esc_html__( 'Hours', 'jet-elements' ),
				'condition'   => array(
					'show_hours' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_min',
			array(
				'label'        => esc_html__( 'Minutes', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-elements' ),
				'label_off'    => esc_html__( 'Hide', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'label_min',
			array(
				'label'       => esc_html__( 'Minutes Label', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Minutes', 'jet-elements' ),
				'placeholder' => esc_html__( 'Minutes', 'jet-elements' ),
				'condition'   => array(
					'show_min' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_sec',
			array(
				'label'        => esc_html__( 'Seconds', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'jet-elements' ),
				'label_off'    => esc_html__( 'Hide', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'label_sec',
			array(
				'label'       => esc_html__( 'Seconds Label', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Seconds', 'jet-elements' ),
				'placeholder' => esc_html__( 'Seconds', 'jet-elements' ),
				'condition'   => array(
					'show_sec' => 'yes',
				),
			)
		);

		$this->add_control(
			'blocks_sep',
			array(
				'label'       => esc_html__( 'Blocks Separator', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => ':',
			)
		);

		$this->add_control(
			'expire_actions',
			array(
				'label'       => esc_html__( 'Actions After Expire', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => array(
					'redirect' => esc_html__( 'Redirect', 'jet-elements' ),
					'message'  => esc_html__( 'Show Message', 'jet-elements' ),
					'hide'     => esc_html__( 'Hide Timer', 'jet-elements' ),
				),
				'separator' => 'before',
				'condition' => array(
					'type!' => 'endless',
				),
			)
		);

		$this->add_control(
			'expire_redirect_url',
			array(
				'label'         => esc_html__( 'Redirect URL', 'jet-elements' ),
				'type'          => Controls_Manager::URL,
				'label_block'   => true,
				'show_external' => false,
				'options'       => false,
				'dynamic'       => array(
					'active' => true,
				),
				'condition'     => array(
					'expire_actions' => 'redirect',
				),
			)
		);

		$this->add_control(
			'message_after_expire',
			array(
				'label'       => esc_html__( 'Message', 'jet-elements' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'expire_actions' => 'message',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		$default_date = date(
			'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS )
		);

		$this->add_control(
			'type',
			array(
				'label'   => esc_html__( 'Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'due_date',
				'options' => array(
					'due_date'  => esc_html__( 'Due Date', 'jet-elements' ),
					'evergreen' => esc_html__( 'Evergreen Timer', 'jet-elements' ),
					'endless'   => esc_html__( 'Endless Timer', 'jet-elements' )
				),
			)
		);

		$this->add_control(
			'due_date',
			array(
				'label'       => esc_html__( 'Due Date', 'jet-elements' ),
				'type'        => 'jet_dynamic_date_time',
				'default'     => $default_date,
				'description' => sprintf(
					esc_html__( 'Date set according to your timezone: %s.', 'jet-elements' ),
					Utils::get_timezone_string()
				),
				'dynamic' => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
					),
				),
				'condition' => array(
					'type' => 'due_date',
				),
			)
		);

		$this->add_control(
			'evergreen_hours',
			array(
				'label'     => esc_html__( 'Hours', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 23,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'type' => 'evergreen',
				),
			)
		);

		$this->add_control(
			'evergreen_minutes',
			array(
				'label'     => esc_html__( 'Minutes', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 30,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'type' => 'evergreen',
				),
			)
		);

		$default_start_date = date(
			'Y-m-d H:i', strtotime( 'today' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS )
		);

		$this->add_control(
			'start_date',
			array(
				'label'       => esc_html__( 'Start Timer', 'jet-elements' ),
				'type'        => 'jet_dynamic_date_time',
				'default'     => $default_start_date,
				'description' => sprintf(
					esc_html__( 'Date set according to your timezone: %s.', 'jet-elements' ),
					Utils::get_timezone_string()
				),
				'dynamic' => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
					),
				),
				'condition' => array(
					'type' => 'endless',
				),
			)
		);

		$this->add_control(
			'restart_time_heading',
			array(
				'label'     => esc_html__( 'Restart After', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'type' => 'endless',
				),
			)
		);

		$this->add_control(
			'restart_hours',
			array(
				'label'     => esc_html__( 'Hours', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 12,
				'condition' => array(
					'type' => 'endless',
				),
			)
		);

		$this->add_control(
			'restart_minutes',
			array(
				'label'     => esc_html__( 'Minutes', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 00,
				'condition' => array(
					'type' => 'endless',
				),
			)
		);

		$this->add_control(
			'items_size',
			array(
				'label'   => esc_html__( 'Items Size', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fixed',
				'options' => array(
					'auto'  => esc_html__( 'Auto', 'jet-elements' ),
					'fixed' => esc_html__( 'Fixed', 'jet-elements' ),
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'items_size_val',
			array(
				'label'      => esc_html__( 'Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 110,
				),
				'range'      => array(
					'px' => array(
						'min' => 60,
						'max' => 600,
					),
					'em' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'render_type' => 'template',
				'condition'   => array(
					'items_size' => 'fixed',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'items_width_val',
			array(
				'label'      => esc_html__( 'Height', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 110,
				),
				'range'      => array(
					'px' => array(
						'min' => 60,
						'max' => 600,
					),
					'em' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'render_type' => 'template',
				'condition'   => array(
					'items_size' => 'fixed',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->_start_controls_section(
			'section_items_styles',
			array(
				'label' => esc_html__( 'Items', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->_add_responsive_control(
			'items_align',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'jet-elements' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'jet-elements' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'justify-content: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_item_styles',
			array(
				'label'      => esc_html__( 'Item', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'item_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'],
			),
			25
		);

		$this->_add_responsive_control(
			'item_padding',
			array(
				'label'      => esc_html__( 'Item Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'item_margin',
			array(
				'label'      => esc_html__( 'Item Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['item'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'border',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item'],
				'placeholder' => '1px',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width' => array(
						'default' => array(
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'isLinked' => true,
						),
					),
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_3,
						),
					),
				),
			),
			50
		);

		$this->_add_control(
			'item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'],
			),
			100
		);

		$this->_add_control(
			'order_heading',
			array(
				'label'     => esc_html__( 'Order', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			100
		);

		$this->_add_control(
			'value_order',
			array(
				'label'   => esc_html__( 'Digit Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['value'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'label_order',
			array(
				'label'   => esc_html__( 'Label Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['label'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_label_styles',
			array(
				'label'      => esc_html__( 'Labels', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['label'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'label_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['label'],
			),
			100
		);

		$this->_add_responsive_control(
			'label_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['label'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'label_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['label'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'label_border',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['label'],
			),
			100
		);

		$this->_add_control(
			'label_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'label_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['label'],
			),
			100
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_digit_styles',
			array(
				'label'      => esc_html__( 'Digits', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'value_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['value'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['digit'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'value_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['value'],
			),
			100
		);

		$this->_add_responsive_control(
			'value_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['value'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'value_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['value'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'value_border',
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['value'],
			),
			100
		);

		$this->_add_control(
			'value_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['value'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'value_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['value'],
			),
			100
		);

		$this->_add_control(
			'digit_item_heading',
			array(
				'label'     => esc_html__( 'Digit Item Styles', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'digit_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['digit'],
			),
			50
		);

		$this->_add_responsive_control(
			'digit_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['digit'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'digit_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['digit'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'digit_border',
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['digit'],
			),
			50
		);

		$this->_add_control(
			'digit_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['digit'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'digit_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['digit'],
			),
			100
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_sep_styles',
			array(
				'label'      => esc_html__( 'Separator Styles', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'sep_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sep'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'sep_size',
			array(
				'label'      => esc_html__( 'Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 300,
					),
					'em' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				//'render_type' => 'template',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sep'] => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			25
		);

		$this->_add_control(
			'sep_font',
			array(
				'label'     => esc_html__( 'Font', 'jet-elements' ),
				'type'      => Controls_Manager::FONT,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sep'] => 'font-family: {{VALUE}}',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'sep_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['sep'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_end_controls_section();

		/**
		 * Message Style Section
		 */
		$this->_start_controls_section(
			'section_message_style',
			array(
				'label'     => esc_html__( 'Message', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'expire_actions' => 'message',
				),
			)
		);

		$this->_add_control(
			'message_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['message'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'message_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['message'] => 'background-color: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'message_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['message'],
			),
			50
		);

		$this->_add_responsive_control(
			'message_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['message'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'message_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['message'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'message_align',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'jet-elements' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['message'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'message_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['message'],
			),
			75
		);

		$this->_add_control(
			'message_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['message'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_end_controls_section();

	}

	public function get_date_from_setting( $setting_key ) {
		$date = $this->get_settings_for_display( $setting_key );

		if ( empty( $date ) ) {
			return false;
		}

		$is_valid_timestamp = jet_elements_tools()->is_valid_timestamp( $date );

		if ( ! $is_valid_timestamp ) {
			$date = strtotime( $date );
		}

		return (int) $date - ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
	}

	public function get_evergreen_interval( $settings = array() ) {
		$hours_to_sec   = ! empty( $settings['evergreen_hours'] ) ? ( $settings['evergreen_hours'] * HOUR_IN_SECONDS ) : 0;
		$minutes_to_sec = ! empty( $settings['evergreen_minutes'] ) ? ( $settings['evergreen_minutes'] * MINUTE_IN_SECONDS ) : 0;

		return $hours_to_sec + $minutes_to_sec;
	}

	public function get_restart_interval( $settings = array() ) {
		$hours_to_sec   = ! empty( $settings['restart_hours'] ) ? ( $settings['restart_hours'] * HOUR_IN_SECONDS ) : 0;
		$minutes_to_sec = ! empty( $settings['restart_minutes'] ) ? ( $settings['restart_minutes'] * MINUTE_IN_SECONDS ) : 0;

		return $hours_to_sec + $minutes_to_sec;
	}

	public function date_placeholder() {
		return '<span class="jet-countdown-timer__digit">0</span><span class="jet-countdown-timer__digit">0</span>';
	}

	/**
	 * Blocks separator
	 *
	 * @return string|void
	 */
	public function blocks_separator() {

		$separator = $this->get_settings( 'blocks_sep' );

		if ( ! $separator ) {
			return;
		}

		$format = apply_filters(
			'jet-elements/jet-countdown-timer/blocks-separator-format',
			'<div class="jet-countdown-timer__separator">%s</div>'
		);

		return sprintf( $format, $separator );
	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

}
