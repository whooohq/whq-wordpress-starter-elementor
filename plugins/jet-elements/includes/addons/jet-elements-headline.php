<?php
/**
 * Class: Jet_Elements_Headline
 * Name: Headline
 * Slug: jet-headline
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Headline extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-headline';
	}

	public function get_title() {
		return esc_html__( 'Headline', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-headline';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-add-a-headline-to-elementor-with-jetelements-headline-and-animated-text-widgets/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-elements/headline/css-scheme',
			array(
				'instance'          => '.jet-headline',
				'first_part'        => '.jet-headline__first',
				'first_part_label'  => '.jet-headline__first .jet-headline__label',
				'second_part'       => '.jet-headline__second',
				'second_part_label' => '.jet-headline__second .jet-headline__label',
				'divider'           => '.jet-headline__divider',
			)
		);

		$this->start_controls_section(
			'section_title',
			array(
				'label' => esc_html__( 'Title Content', 'jet-elements' ),
			)
		);

		$this->add_control(
			'first_part',
			array(
				'label'       => esc_html__( 'First part', 'jet-elements' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter title first part', 'jet-elements' ),
				'default'     => esc_html__( 'Heading', 'jet-elements' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'second_part',
			array(
				'label'       => esc_html__( 'Second part', 'jet-elements' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter title second part', 'jet-elements' ),
				'default'     => esc_html__( 'Element', 'jet-elements' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_deco_elements',
			array(
				'label' => esc_html__( 'Decorative Elements', 'jet-elements' ),
			)
		);

		$this->add_control(
			'before_deco_type',
			array(
				'label'   => esc_html__( 'Before Deco Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'none'  => esc_html__( 'None', 'jet-elements' ),
					'icon'  => esc_html__( 'Icon', 'jet-elements' ),
					'image' => esc_html__( 'Image', 'jet-elements' ),
				),
			)
		);

		$this->_add_advanced_icon_control(
			'before_icon',
			array(
				'label'       => esc_html__( 'Before Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'condition' => array(
					'before_deco_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'before_image',
			array(
				'label'     => esc_html__( 'Before Image', 'jet-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::IMAGE_CATEGORY,
					),
				),
				'condition' => array(
					'before_deco_type' => 'image',
				),
			)
		);

		$this->add_control(
			'after_deco_type',
			array(
				'label'   => esc_html__( 'After Deco Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'  => esc_html__( 'None', 'jet-elements' ),
					'icon'  => esc_html__( 'Icon', 'jet-elements' ),
					'image' => esc_html__( 'Image', 'jet-elements' ),
				),
			)
		);

		$this->_add_advanced_icon_control(
			'after_icon',
			array(
				'label'       => esc_html__( 'After Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'condition' => array(
					'after_deco_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'after_image',
			array(
				'label'     => esc_html__( 'After Image', 'jet-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::IMAGE_CATEGORY,
					),
				),
				'condition' => array(
					'after_deco_type' => 'image',
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

		$this->add_control(
			'header_size',
			array(
				'label'   => esc_html__( 'HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'default' => 'h2',
			)
		);

		$this->add_control(
			'instance_direction',
			array(
				'label'   => esc_html__( 'Direction', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'jet-elements' ),
					'vertical'   => esc_html__( 'Vertical', 'jet-elements' ),
				)
			)
		);

		$this->add_responsive_control(
			'instance_alignment_horizontal',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
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
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['instance'] => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} '. $css_scheme['instance'] . ' > .jet-headline__link' => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'instance_direction' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'instance_alignment_vertical',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
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
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['instance'] => 'align-items: {{VALUE}};',
					'{{WRAPPER}} '. $css_scheme['instance'] . ' > .jet-headline__link' => 'align-items: {{VALUE}};',
				),
				'condition' => array(
					'instance_direction' => 'vertical',
				),
			)
		);

		$this->add_control(
			'use_space_between',
			array(
				'label'        => esc_html__( 'Space Between Parts', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => array(
					'instance_direction' => 'horizontal',
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'jet-elements' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default' => array(
					'url' => '',
				),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->end_controls_section();

		/**
		 * General Style Section
		 */
		$this->_start_controls_section(
			'section_general_style',
			array(
				'label'      => esc_html__( 'General', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'instance_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			100
		);

		$this->_add_responsive_control(
			'instance_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'instance_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'instance_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			100
		);

		$this->_add_responsive_control(
			'instance_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_end_controls_section( 100 );

		/**
		 * First Part Style Section
		 */
		$this->_start_controls_section(
			'section_first_part_style',
			array(
				'label'      => esc_html__( 'First Part', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'first_vertical_alignment',
			array(
				'label'   => esc_html__( 'Vertical Position', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Top', 'jet-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'condition' => array(
					'instance_direction' => 'horizontal',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] => 'align-self: {{VALUE}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'first_horizontal_alignment',
			array(
				'label'   => esc_html__( 'Horizontal Position', 'jet-elements' ),
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
				'condition' => array(
					'instance_direction' => 'vertical',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] => 'align-self: {{VALUE}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'first_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__label' => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			75
		);

		$this->_add_control(
			'first_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['first_part']  . ' .jet-headline__label' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'first_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__label',
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'first_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['first_part'],
			),
			75
		);

		$this->_add_responsive_control(
			'first_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'first_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'first_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['first_part'],
			),
			75
		);

		$this->_add_responsive_control(
			'first_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'first_text_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__label',
			),
			50
		);

		$this->_add_control(
			'use_first_text_image_heading',
			array(
				'label'     => esc_html__( 'Text Image', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			),
			100
		);

		$this->_add_control(
			'use_first_text_image',
			array(
				'label'        => esc_html__( 'Use Text Image', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
			),
			100
		);

		$this->_add_control(
			'choose_styling_first_element',
			array(
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'image',
				'toggle'  => false,
				'options' => array(
					'image'    => array(
						'title' => esc_html__( 'Image', 'jet-elements' ),
						'icon'  => 'fa fa-image',
					),
					'gradient' => array(
						'title' => esc_html__( 'Gradient', 'jet-elements' ),
						'icon'  => 'fa fa-barcode',
					),
				),
				'condition' => array(
					'use_first_text_image' => 'yes',
				),
			),
			100
		);

		$this->_add_control(
			'first_label_bg_color',
			array(
				'label'     => _x( 'Color', 'Background Control', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'render_type' => 'ui',
				'condition' => array(
					'choose_styling_first_element' => 'gradient',
					'use_first_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'first_label_bg_color_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 0,
				),
				'render_type' => 'ui',
				'condition' => array(
					'choose_styling_first_element' => 'gradient' ,
					'use_first_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'first_label_bg_color_b',
			array(
				'label'       => _x( 'Second Color', 'Background Control', 'jet-elements' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#f2295b',
				'render_type' => 'ui',
				'condition'   => array(
					'choose_styling_first_element' => 'gradient',
					'use_first_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'first_label_bg_color_b_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'choose_styling_first_element' => 'gradient',
					'use_first_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'first_label_bg_gradient_type',
			array(
				'label'   => _x( 'Type', 'Background Control', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'linear' => _x( 'Linear', 'Background Control', 'jet-elements' ),
					'radial' => _x( 'Radial', 'Background Control', 'jet-elements' ),
				),
				'default'     => 'linear',
				'render_type' => 'ui',
				'condition'   => array(
					'choose_styling_first_element' => 'gradient',
					'use_first_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'first_label_bg_gradient_angle',
			array(
				'label'      => _x( 'Angle', 'Background Control', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'deg' ),
				'default'    => array(
					'unit' => 'deg',
					'size' => 180,
				),
				'range' => array(
					'deg' => array(
						'step' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['first_part_label'] => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{first_label_bg_color.VALUE}} {{first_label_bg_color_stop.SIZE}}{{first_label_bg_color_stop.UNIT}}, {{first_label_bg_color_b.VALUE}} {{first_label_bg_color_b_stop.SIZE}}{{first_label_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'choose_styling_first_element' => 'gradient',
					'first_label_bg_gradient_type' => 'linear',
					'use_first_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'first_label_bg_gradient_position',
			array(
				'label'   => _x( 'Position', 'Background Control', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'center center' => _x( 'Center Center', 'Background Control', 'jet-elements' ),
					'center left'   => _x( 'Center Left', 'Background Control', 'jet-elements' ),
					'center right'  => _x( 'Center Right', 'Background Control', 'jet-elements' ),
					'top center'    => _x( 'Top Center', 'Background Control', 'jet-elements' ),
					'top left'      => _x( 'Top Left', 'Background Control', 'jet-elements' ),
					'top right'     => _x( 'Top Right', 'Background Control', 'jet-elements' ),
					'bottom center' => _x( 'Bottom Center', 'Background Control', 'jet-elements' ),
					'bottom left'   => _x( 'Bottom Left', 'Background Control', 'jet-elements' ),
					'bottom right'  => _x( 'Bottom Right', 'Background Control', 'jet-elements' ),
				),
				'default' => 'center center',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['first_part_label'] => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{first_label_bg_color.VALUE}} {{first_label_bg_color_stop.SIZE}}{{first_label_bg_color_stop.UNIT}}, {{first_label_bg_color_b.VALUE}} {{first_label_bg_color_b_stop.SIZE}}{{first_label_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'choose_styling_first_element' => 'gradient',
					'first_label_bg_gradient_type' => 'radial',
					'use_first_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'first_text_image',
			array(
				'label'     => esc_html__( 'Text Image', 'jet-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'choose_styling_first_element' => 'image',
					'use_first_text_image'         => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__label' => 'background-image: url({{URL}})',
				),
			),
			100
		);

		$this->_add_control(
			'first_text_image_position',
			array(
				'label'   =>esc_html__( 'Background Position', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''              => esc_html__( 'Default', 'jet-elements' ),
					'top left'      => esc_html__( 'Top Left', 'jet-elements' ),
					'top center'    => esc_html__( 'Top Center', 'jet-elements' ),
					'top right'     => esc_html__( 'Top Right', 'jet-elements' ),
					'center left'   => esc_html__( 'Center Left', 'jet-elements' ),
					'center center' => esc_html__( 'Center Center', 'jet-elements' ),
					'center right'  => esc_html__( 'Center Right', 'jet-elements' ),
					'bottom left'   => esc_html__( 'Bottom Left', 'jet-elements' ),
					'bottom center' => esc_html__( 'Bottom Center', 'jet-elements' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'jet-elements' ),
				),
				'condition' => array(
					'choose_styling_first_element' => 'image',
					'use_first_text_image'         => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__label' => 'background-position: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'first_text_image_repeat',
			array(
				'label'   =>esc_html__( 'Background Repeat', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''          => esc_html__( 'Default', 'jet-elements' ),
					'no-repeat' => esc_html__( 'No-repeat', 'jet-elements' ),
					'repeat'    => esc_html__( 'Repeat', 'jet-elements' ),
					'repeat-x'  => esc_html__( 'Repeat-x', 'jet-elements' ),
					'repeat-y'  => esc_html__( 'Repeat-y', 'jet-elements' ),
				),
				'condition' => array(
					'choose_styling_first_element' => 'image',
					'use_first_text_image'         => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__label' => 'background-repeat: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'first_text_image_size',
			array(
				'label'   =>esc_html__( 'Background Size', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => esc_html__( 'Default', 'jet-elements' ),
					'auto'    => esc_html__( 'Auto', 'jet-elements' ),
					'cover'   => esc_html__( 'Cover', 'jet-elements' ),
					'contain' => esc_html__( 'Contain', 'jet-elements' ),
				),
				'condition' => array(
					'choose_styling_first_element' => 'image',
					'use_first_text_image'         => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__label' => 'background-size: {{VALUE}};',
				),
			),
			100
		);

		$this->end_controls_section();

		/**
		 * Second Part Style Section
		 */
		$this->_start_controls_section(
			'section_second_part_style',
			array(
				'label'      => esc_html__( 'Second Part', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'second_vertical_alignment',
			array(
				'label'   => esc_html__( 'Vertical Position', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Top', 'jet-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'condition' => array(
					'instance_direction' => 'horizontal',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] => 'align-self: {{VALUE}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'second_horizontal_alignment',
			array(
				'label'   => esc_html__( 'Horizontal Position', 'jet-elements' ),
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
				'condition' => array(
					'instance_direction' => 'vertical',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] => 'align-self: {{VALUE}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'second_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__label' => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			75
		);

		$this->_add_control(
			'second_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__label' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'second_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__label',
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'second_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['second_part'],
			),
			75
		);

		$this->_add_responsive_control(
			'second_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'second_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'second_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['second_part'],
			),
			75
		);

		$this->_add_responsive_control(
			'second_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'second_text_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__label',
			),
			50
		);

		$this->_add_control(
			'use_second_text_image',
			array(
				'label'        => esc_html__( 'Use Text Image', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
			),
			100
		);

		$this->_add_control(
			'choose_styling_second_element',
			array(
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'image',
				'toggle'  => false,
				'options' => array(
					'image'    => array(
						'title' => esc_html__( 'Image', 'jet-elements' ),
						'icon'  => 'fa fa-image',
					),
					'gradient' => array(
						'title' => esc_html__( 'Gradient', 'jet-elements' ),
						'icon'  => 'fa fa-barcode',
					),
				),
				'condition' => array(
					'use_second_text_image' => 'yes',
				),
			),
			100
		);

		$this->_add_control(
			'second_label_bg_color',
			array(
				'label'     => _x( 'Color', 'Background Control', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'render_type' => 'ui',
				'condition' => array(
					'choose_styling_second_element' => 'gradient',
					'use_second_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'second_label_bg_color_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 0,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'choose_styling_second_element' => 'gradient',
					'use_second_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'second_label_bg_color_b',
			array(
				'label'       => _x( 'Second Color', 'Background Control', 'jet-elements' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#f2295b',
				'render_type' => 'ui',
				'condition'   => array(
					'choose_styling_second_element' => 'gradient',
					'use_second_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'second_label_bg_color_b_stop',
			array(
				'label'      => _x( 'Location', 'Background Control', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'render_type' => 'ui',
				'condition'   => array(
					'choose_styling_second_element' => 'gradient',
					'use_second_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'second_label_bg_gradient_type',
			array(
				'label'   => _x( 'Type', 'Background Control', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'linear' => _x( 'Linear', 'Background Control', 'jet-elements' ),
					'radial' => _x( 'Radial', 'Background Control', 'jet-elements' ),
				),
				'default'     => 'linear',
				'render_type' => 'ui',
				'condition'   => array(
					'choose_styling_second_element' => 'gradient',
					'use_second_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'second_label_bg_gradient_angle',
			array(
				'label'      => _x( 'Angle', 'Background Control', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'deg' ),
				'default'    => array(
					'unit' => 'deg',
					'size' => 180,
				),
				'range' => array(
					'deg' => array(
						'step' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['second_part_label'] => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{second_label_bg_color.VALUE}} {{second_label_bg_color_stop.SIZE}}{{second_label_bg_color_stop.UNIT}}, {{second_label_bg_color_b.VALUE}} {{second_label_bg_color_b_stop.SIZE}}{{second_label_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'choose_styling_second_element' => 'gradient',
					'second_label_bg_gradient_type' => 'linear',
					'use_second_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'second_label_bg_gradient_position',
			array(
				'label'   => _x( 'Position', 'Background Control', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'center center' => _x( 'Center Center', 'Background Control', 'jet-elements' ),
					'center left'   => _x( 'Center Left', 'Background Control', 'jet-elements' ),
					'center right'  => _x( 'Center Right', 'Background Control', 'jet-elements' ),
					'top center'    => _x( 'Top Center', 'Background Control', 'jet-elements' ),
					'top left'      => _x( 'Top Left', 'Background Control', 'jet-elements' ),
					'top right'     => _x( 'Top Right', 'Background Control', 'jet-elements' ),
					'bottom center' => _x( 'Bottom Center', 'Background Control', 'jet-elements' ),
					'bottom left'   => _x( 'Bottom Left', 'Background Control', 'jet-elements' ),
					'bottom right'  => _x( 'Bottom Right', 'Background Control', 'jet-elements' ),
				),
				'default'   => 'center center',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['second_part_label'] => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{second_label_bg_color.VALUE}} {{second_label_bg_color_stop.SIZE}}{{second_label_bg_color_stop.UNIT}}, {{second_label_bg_color_b.VALUE}} {{second_label_bg_color_b_stop.SIZE}}{{second_label_bg_color_b_stop.UNIT}})',
				),
				'condition' => array(
					'choose_styling_second_element' => 'gradient',
					'second_label_bg_gradient_type' => 'radial',
					'use_second_text_image'         => 'yes',
				),
				'of_type' => 'gradient',
			),
			100
		);

		$this->_add_control(
			'second_text_image',
			array(
				'label'     => esc_html__( 'Text Image', 'jet-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'choose_styling_second_element' => 'image',
					'use_second_text_image'         => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__label' => 'background-image: url({{URL}});',
				),
			),
			100
		);

		$this->_add_control(
			'second_text_image_position',
			array(
				'label'   =>esc_html__( 'Background Position', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''              => esc_html__( 'Default', 'jet-elements' ),
					'top left'      => esc_html__( 'Top Left', 'jet-elements' ),
					'top center'    => esc_html__( 'Top Center', 'jet-elements' ),
					'top right'     => esc_html__( 'Top Right', 'jet-elements' ),
					'center left'   => esc_html__( 'Center Left', 'jet-elements' ),
					'center center' => esc_html__( 'Center Center', 'jet-elements' ),
					'center right'  => esc_html__( 'Center Right', 'jet-elements' ),
					'bottom left'   => esc_html__( 'Bottom Left', 'jet-elements' ),
					'bottom center' => esc_html__( 'Bottom Center', 'jet-elements' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'jet-elements' ),
				),
				'condition' => array(
					'choose_styling_second_element' => 'image',
					'use_second_text_image'         => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__label' => 'background-position: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'second_text_image_repeat',
			array(
				'label'   =>esc_html__( 'Background Repeat', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''          => esc_html__( 'Default', 'jet-elements' ),
					'no-repeat' => esc_html__( 'No-repeat', 'jet-elements' ),
					'repeat'    => esc_html__( 'Repeat', 'jet-elements' ),
					'repeat-x'  => esc_html__( 'Repeat-x', 'jet-elements' ),
					'repeat-y'  => esc_html__( 'Repeat-y', 'jet-elements' ),
				),
				'condition' => array(
					'choose_styling_second_element' => 'image',
					'use_second_text_image'         => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__label' => 'background-repeat: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'second_text_image_size',
			array(
				'label'   =>esc_html__( 'Background Size', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => esc_html__( 'Default', 'jet-elements' ),
					'auto'    => esc_html__( 'Auto', 'jet-elements' ),
					'cover'   => esc_html__( 'Cover', 'jet-elements' ),
					'contain' => esc_html__( 'Contain', 'jet-elements' ),
				),
				'condition' => array(
					'choose_styling_second_element' => 'image',
					'use_second_text_image'         => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__label' => 'background-size: {{VALUE}};',
				),
			),
			100
		);

		$this->_end_controls_section();

		/**
		 * Decorative Style Section
		 */
		$this->_start_controls_section(
			'section_deco_style',
			array(
				'label'      => esc_html__( 'Decorative Elements', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'before_deco',
			array(
				'label' => esc_html__( 'Before Deco Element', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
				'condition' => array(
					'before_deco_type!' => 'none',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'before_deco_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Top', 'jet-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'condition' => array(
					'before_deco_type!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__deco' => 'align-self: {{VALUE}};',
				),
			),
			75
		);

		$this->_add_control(
			'before_icon_color',
			array(
				'label'     => esc_html__( 'Before Icon Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'before_deco_type' => 'icon',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__deco-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__deco-icon svg' => 'fill: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'before_icon_size',
			array(
				'label'      => esc_html__( 'Before Icon Size', 'jet-elements' ),
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
				'condition' => array(
					'before_deco_type' => 'icon',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__deco-icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__deco-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'before_image_width_size',
			array(
				'label'      => esc_html__( 'Before Image Width Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 18,
						'max' => 200,
					),
				),
				'condition' => array(
					'before_deco_type' => 'image',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__deco-image' => 'width: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'before_image_height_size',
			array(
				'label'      => esc_html__( 'Before Image Height Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 18,
						'max' => 200,
					),
				),
				'condition' => array(
					'before_deco_type' => 'image',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__deco-image' => 'height: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'before_deco_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['first_part'] . ' .jet-headline__deco' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'before_deco_type!' => 'none',
				),
			),
			75
		);

		$this->_add_control(
			'after_deco',
			array(
				'label'     => esc_html__( 'After Deco Element', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'after_deco_type!' => 'none',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'after_deco_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Top', 'jet-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'condition' => array(
					'after_deco_type!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__deco' => 'align-self: {{VALUE}};',
				),
			),
			75
		);

		$this->_add_control(
			'after_icon_color',
			array(
				'label'     => esc_html__( 'After Icon Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'after_deco_type' => 'icon',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__deco-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__deco-icon svg' => 'fill: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'after_icon_size',
			array(
				'label'      => esc_html__( 'After Icon Size', 'jet-elements' ),
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
				'condition' => array(
					'after_deco_type' => 'icon',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__deco-icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__deco-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'after_image_width_size',
			array(
				'label'      => esc_html__( 'After Image Width Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 18,
						'max' => 200,
					),
				),
				'condition' => array(
					'after_deco_type' => 'image',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__deco-image' => 'width: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'after_image_height_size',
			array(
				'label'      => esc_html__( 'After Image Height Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 18,
						'max' => 200,
					),
				),
				'condition' => array(
					'after_deco_type' => 'image',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__deco-image' => 'height: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'after_deco_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['second_part'] . ' .jet-headline__deco' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'after_deco_type!' => 'none',
				),
			),
			75
		);

		$this->_add_control(
			'divider_deco',
			array(
				'label'     => esc_html__( 'Divider Deco Element', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			100
		);

		$this->_add_control(
			'use_divider_deco',
			array(
				'label'        => esc_html__( 'Use Divider Mode', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'no',
			),
			100
		);

		$this->_add_control(
			'divider_deco_height',
			array(
				'label'   => esc_html__( 'Divider Size', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 50,
				'step'    => 1,
				'condition' => array(
					'use_divider_deco' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['divider'] => 'height: {{VALUE}}px;',
				),
			),
			100
		);

		$this->_add_control(
			'divider_deco_space',
			array(
				'label'   => esc_html__( 'Divider Space', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 10,
				'min'     => 0,
				'max'     => 200,
				'step'    => 1,
				'condition' => array(
					'use_divider_deco'   => 'yes',
					'instance_direction' => 'horizontal',
				),
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['divider'] . '.jet-headline__left-divider' => 'margin-right: {{VALUE}}px;',
					'{{WRAPPER}} '. $css_scheme['divider'] . '.jet-headline__right-divider' => 'margin-left: {{VALUE}}px;',
				),
			),
			100
		);

		$this->_start_controls_tabs( 'tabs_deco_divider', 100 );

		$this->_start_controls_tab(
			'tab_deco_divider_left',
			array(
				'label' => esc_html__( 'Left', 'jet-elements' ),
				'condition' => array(
					'use_divider_deco' => 'yes',
				),
			),
			100
		);

		$this->_add_control(
			'use_divider_deco_left',
			array(
				'label'        => esc_html__( 'Enable', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'use_divider_deco' => 'yes',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'divider_deco_left_background',
				'label'     => esc_html__( 'Background', 'jet-elements' ),
				'selector'  => '{{WRAPPER}} ' . $css_scheme['divider'] . '.jet-headline__left-divider',
				'condition' => array(
					'use_divider_deco' => 'yes',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'divider_deco_left_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['divider'] . '.jet-headline__left-divider',
				'condition'   => array(
					'use_divider_deco' => 'yes',
				),
			),
			100
		);

		$this->_end_controls_tab( 100 );

		$this->_start_controls_tab(
			'tab_deco_divider_right',
			array(
				'label' => esc_html__( 'Right', 'jet-elements' ),
				'condition' => array(
					'use_divider_deco' => 'yes',
				),
			),
			100
		);

		$this->_add_control(
			'use_divider_deco_right',
			array(
				'label'        => esc_html__( 'Enable', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'use_divider_deco' => 'yes',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'divider_deco_right_background',
				'label'     => esc_html__( 'Background', 'jet-elements' ),
				'selector'  => '{{WRAPPER}} ' . $css_scheme['divider'] . '.jet-headline__right-divider',
				'condition' => array(
					'use_divider_deco' => 'yes',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'divider_deco_right_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['divider'] . '.jet-headline__right-divider',
				'condition'   => array(
					'use_divider_deco' => 'yes',
				),
			),
			100
		);

		$this->_end_controls_tab( 100 );

		$this->_end_controls_tabs( 100 );

		$this->_end_controls_section();

	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		if ( empty( $settings['first_part'] ) && empty( $settings['second_part'] ) ) {
			return;
		}

		$first_part = '';
		$second_part = '';
		$before_deco_html = '';
		$after_deco_html = '';
		$space = '';

		$heading_classes_array = array( 'jet-headline' );
		$heading_classes_array[] = 'jet-headline--direction-' . $settings['instance_direction'];

		$heading_classes = implode( ' ', $heading_classes_array );

		if ( filter_var( $settings['use_space_between'], FILTER_VALIDATE_BOOLEAN ) && 'horizontal' === $settings['instance_direction'] ) {
			$space = '<span class="jet-headline__space">&nbsp;</span>';
		}

		// Before Deco Render
		if ( 'none' !== $settings['before_deco_type'] ) {

			if ( 'icon' === $settings['before_deco_type'] ) {
				$before_deco_icon = $this->_get_icon( 'before_icon' );
				$before_deco_html = ! empty( $before_deco_icon ) ? sprintf( '<span class="jet-headline__deco jet-headline__deco-icon">%1$s</span>', $before_deco_icon ) : '';
			}

			if ( 'image' === $settings['before_deco_type'] && ! empty( $settings['before_image']['url'] ) ) {
				$before_deco_image = sprintf( '<img src="%s" alt="%s" loading="lazy">', $settings['before_image']['url'], esc_attr( Control_Media::get_image_alt( $settings['before_image'] ) ) );
				$before_deco_html  = sprintf( '<span class="jet-headline__deco jet-headline__deco-image">%1$s</span>', $before_deco_image );
			}
		}

		// After Deco Render
		if ( 'none' !== $settings['after_deco_type'] ) {

			if ( 'icon' === $settings['after_deco_type'] ) {
				$after_deco_icon = $this->_get_icon( 'after_icon' );
				$after_deco_html = ! empty( $after_deco_icon ) ? sprintf( '<span class="jet-headline__deco jet-headline__deco-icon">%1$s</span>', $after_deco_icon ) : '';
			}

			if ( 'image' === $settings['after_deco_type'] && ! empty( $settings['after_image']['url'] ) ) {
				$after_deco_image = sprintf( '<img src="%s" alt="%s" loading="lazy">', $settings['after_image']['url'], esc_attr( Control_Media::get_image_alt( $settings['after_image'] ) ) );
				$after_deco_html = sprintf( '<span class="jet-headline__deco jet-headline__deco-image">%1$s</span>', $after_deco_image );
			}
		}

		if ( ! empty( $settings['first_part'] ) ) {

			$first_classes_array = array( 'jet-headline__part', 'jet-headline__first' );

			$first_text_image = isset( $settings['use_first_text_image'] ) ? filter_var( $settings['use_first_text_image'], FILTER_VALIDATE_BOOLEAN ) : false;

			if ( $first_text_image ) {
				$first_classes_array[] = 'headline__part--image-text';
			}

			$first_classes = implode( ' ', $first_classes_array );

			$first_part = sprintf( '<span class="%1$s">%2$s<span class="jet-headline__label">%3$s</span></span>%4$s', $first_classes, $before_deco_html, $settings['first_part'], $space );
		}

		if ( ! empty( $settings['second_part'] ) ) {
			$second_classes_array = array( 'jet-headline__part', 'jet-headline__second' );

			$second_text_image = isset( $settings['use_second_text_image'] ) ? filter_var( $settings['use_second_text_image'], FILTER_VALIDATE_BOOLEAN ) : false;

			if ( $second_text_image ) {
				$second_classes_array[] = 'headline__part--image-text';
			}

			$second_classes = implode( ' ', $second_classes_array );

			$second_part = sprintf( '<span class="%1$s"><span class="jet-headline__label">%2$s</span>%3$s</span>', $second_classes, $settings['second_part'], $after_deco_html );
		}

		$deco_devider_left = '';
		$deco_devider_right = '';

		if ( isset( $settings['use_divider_deco'] ) && filter_var( $settings['use_divider_deco'], FILTER_VALIDATE_BOOLEAN ) ) {

			if ( isset( $settings['use_divider_deco_left'] ) && filter_var( $settings['use_divider_deco_left'], FILTER_VALIDATE_BOOLEAN ) ) {
				$deco_devider_left ='<span class="jet-headline__divider jet-headline__left-divider"></span>';
			}

			if ( isset( $settings['use_divider_deco_right'] ) && filter_var( $settings['use_divider_deco_right'], FILTER_VALIDATE_BOOLEAN ) ) {
				$deco_devider_right ='<span class="jet-headline__divider jet-headline__right-divider"></span>';
			}
		}

		if ( ! is_rtl() ) {
			$title = sprintf( '%1$s%2$s%3$s%4$s', $deco_devider_left, $first_part, $second_part, $deco_devider_right );
		} else {
			$title = sprintf( '%1$s%2$s%3$s%4$s', $deco_devider_right, $first_part, $second_part, $deco_devider_left );
		}

		if ( ! empty( $settings['link']['url'] ) ) {

			if ( method_exists( $this, 'add_link_attributes' ) ) {
				$this->add_link_attributes( 'url', $settings['link'] );
			} else {
				$this->add_render_attribute( 'url', 'href', $settings['link']['url'] );

				if ( $settings['link']['is_external'] ) {
					$this->add_render_attribute( 'url', 'target', '_blank' );
				}

				if ( ! empty( $settings['link']['nofollow'] ) ) {
					$this->add_render_attribute( 'url', 'rel', 'nofollow' );
				}
			}

			$title = sprintf( '<a class="jet-headline__link" %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}

		$title_html = sprintf( '<%1$s class="%2$s">%3$s</%1$s>', jet_elements_tools()->validate_html_tag( $settings['header_size'] ), $heading_classes, $title );

		echo $title_html;
	}

}
