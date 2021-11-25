<?php
/**
 * Class: Jet_Elements_Animated_Box
 * Name: Animated Box
 * Slug: jet-animated-box
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

class Jet_Elements_Animated_Box extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-animated-box';
	}

	public function get_title() {
		return esc_html__( 'Animated Box', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-animated-box';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-showcase-your-services-in-the-form-of-a-flip-box-with-jetelements-animated-box-widget/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'imagesloaded', 'html2canvas', 'oridomi', 'peel-js', 'jquery-ui-draggable', 'jquery-touch-punch' );
	}

	/**
	 * [get_style_depends description]
	 * @return [type] [description]
	 */
	public function get_style_depends() {
		return array( 'peel-css' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-elements/animated-box/css-scheme',
			array(
				'animated_box'                => '.jet-animated-box',
				'animated_box_inner'          => '.jet-animated-box__inner',
				'animated_box_front'          => '.jet-animated-box__front',
				'animated_box_back'           => '.jet-animated-box__back',
				'animated_box_icon'           => '.jet-animated-box__icon',
				'animated_box_icon_box'       => '.jet-animated-box__icon-box',
				'animated_box_title'          => '.jet-animated-box__title',
				'animated_box_subtitle'       => '.jet-animated-box__subtitle',
				'animated_box_description'    => '.jet-animated-box__description',
				'animated_box_button'         => '.jet-animated-box__button',
				'animated_box_button_icon'    => '.jet-animated-box__button-icon',
				'animated_box_icon_front'     => '.jet-animated-box__icon--front',
				'animated_box_icon_back'      => '.jet-animated-box__icon--back',
				'animated_box_title_front'    => '.jet-animated-box__title--front',
				'animated_box_title_back'     => '.jet-animated-box__title--back',
				'animated_box_subtitle_front' => '.jet-animated-box__subtitle--front',
				'animated_box_subtitle_back'  => '.jet-animated-box__subtitle--back',
				'animated_box_desc_front'     => '.jet-animated-box__description--front',
				'animated_box_desc_back'      => '.jet-animated-box__description--back',
				'animated_box_toggle'         => '.jet-animated-box__toggle',
				'animated_box_toggle_front'   => '.jet-animated-box__toggle--front',
				'animated_box_toggle_back'    => '.jet-animated-box__toggle--back',
				'animated_box_canvas'         => '.jet-animated-box canvas',
			)
		);

		$this->start_controls_section(
			'section_front_content',
			array(
				'label' => esc_html__( 'Front Side Content', 'jet-elements' ),
			)
		);

		$this->add_control(
			'front_side_content_type',
			array(
				'label'   => esc_html__( 'Content Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default'  => esc_html__( 'Default', 'jet-elements' ),
					'template' => esc_html__( 'Template', 'jet-elements' ),
				),
			)
		);

		$this->_add_advanced_icon_control(
			'front_side_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'condition'   => array(
					'front_side_content_type' => 'default',
				),
			)
		);

		$this->add_control(
			'front_side_title',
			array(
				'label'   => esc_html__( 'Title', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
				'condition'   => array(
					'front_side_content_type' => 'default',
				),
			)
		);

		$this->add_control(
			'front_side_subtitle',
			array(
				'label'   => esc_html__( 'Subtitle', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Flip Box', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
				'condition'   => array(
					'front_side_content_type' => 'default',
				),
			)
		);

		$this->add_control(
			'front_side_description',
			array(
				'label'   => esc_html__( 'Description', 'jet-elements' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Easily add or remove any text on your flip box!', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
				'condition'   => array(
					'front_side_content_type' => 'default',
				),
			)
		);

		$this->add_control(
			'front_side_template_id',
			array(
				'label'       => esc_html__( 'Choose Template', 'jet-elements' ),
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
				'edit_button' => array(
					'active' => true,
					'label'  => __( 'Edit Template', 'jet-elements' ),
				),
				'condition'   => array(
					'front_side_content_type' => 'template',
				),
			)
		);

		$this->_add_advanced_icon_control(
			'front_side_toggle_icon',
			array(
				'label'       => esc_html__( 'Toggle Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-angle-right',
				'fa5_default' => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'switch_event_type' => 'toggle',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_back_content',
			array(
				'label' => esc_html__( 'Back Side Content', 'jet-elements' ),
			)
		);

		$this->add_control(
			'back_side_content_type',
			array(
				'label'   => esc_html__( 'Content Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default'  => esc_html__( 'Default', 'jet-elements' ),
					'template' => esc_html__( 'Template', 'jet-elements' ),
				),
			)
		);

		$this->_add_advanced_icon_control(
			'back_side_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => '',
				'condition'   => array(
					'back_side_content_type' => 'default',
				),
			)
		);

		$this->add_control(
			'back_side_title',
			array(
				'label'   => esc_html__( 'Title', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Back Side', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
				'condition'   => array(
					'back_side_content_type' => 'default',
				),
			)
		);

		$this->add_control(
			'back_side_subtitle',
			array(
				'label'   => esc_html__( 'Subtitle', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => array( 'active' => true ),
				'condition'   => array(
					'back_side_content_type' => 'default',
				),
			)
		);

		$this->add_control(
			'back_side_description',
			array(
				'label'   => esc_html__( 'Description', 'jet-elements' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Easily add or remove any text on your flip box!', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
				'condition'   => array(
					'back_side_content_type' => 'default',
				),
			)
		);

		$this->add_control(
			'back_side_button_text',
			array(
				'label'   => esc_html__( 'Button text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'jet-elements' ),
				'condition'   => array(
					'back_side_content_type' => 'default',
				),
			)
		);

		$this->add_control(
			'back_side_button_link',
			array(
				'label'       => esc_html__( 'Button Link', 'jet-elements' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default' => array(
					'url' => '#',
				),
				'dynamic' => array( 'active' => true ),
				'condition'   => array(
					'back_side_content_type' => 'default',
				),
			)
		);

		$this->add_control(
			'back_side_template_id',
			array(
				'label'       => esc_html__( 'Choose Template', 'jet-elements' ),
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
				'edit_button' => array(
					'active' => true,
					'label'  => __( 'Edit Template', 'jet-elements' ),
				),
				'condition'   => array(
					'back_side_content_type' => 'template',
				),
			)
		);

		$this->_add_advanced_icon_control(
			'back_side_toggle_icon',
			array(
				'label'       => esc_html__( 'Toggle Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-times',
				'fa5_default' => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'switch_event_type' => 'toggle',
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

		$this->add_responsive_control(
			'box_height',
			array(
				'label'      => esc_html__( 'Height', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem', 'vh' ),
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'rem' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'default' => array(
					'size' => 250,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'switch_event_type',
			array(
				'label'   => esc_html__( 'Switch Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => array(
					'hover'        => esc_html__( 'Hover', 'jet-elements' ),
					'click'        => esc_html__( 'Click', 'jet-elements' ),
					'toggle'       => esc_html__( 'Toggle Button', 'jet-elements' ),
					'scratch'      => esc_html__( 'Scratch', 'jet-elements' ),
					'fold'         => esc_html__( 'Paper Fold', 'jet-elements' ),
					'peel'         => esc_html__( 'Peel', 'jet-elements' ),
					'slide-out'    => esc_html__( 'Slide Out', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'scratch_fill_percent',
			array(
				'label'      => esc_html__( 'Fill percent for reveal full image', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range' => array(
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 75,
					'unit' => '%',
				),
				'condition' => array(
					'switch_event_type' => 'scratch',
				),
			)
		);

		$this->add_control(
			'animation_effect',
			array(
				'label'   => esc_html__( 'Animation Effect', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'jet-box-effect-1',
				'groups'  => array(
					'simple' => array(
						'label'   => esc_html__( 'Simple', 'jet-elements' ),
						'options' => array(
							'jet-box-effect-9'  => esc_html__( 'Fade', 'jet-elements' ),
							'jet-box-effect-18' => esc_html__( 'Blur', 'jet-elements' ),
						),
					),
					'zoom'     => array(
						'label'   => esc_html__( 'Zoom', 'jet-elements' ),
						'options' => array(
							'jet-box-effect-10' => esc_html__( 'Zoom In', 'jet-elements' ),
							'jet-box-effect-11' => esc_html__( 'Zoom Out', 'jet-elements' ),
						),
					),
					'pull'     => array(
						'label'   => esc_html__( 'Pull', 'jet-elements' ),
						'options' => array(
							'jet-box-effect-14' => esc_html__( 'Pull Left', 'jet-elements' ),
							'jet-box-effect-15' => esc_html__( 'Pull Up', 'jet-elements' ),
							'jet-box-effect-6'  => esc_html__( 'Pull Right', 'jet-elements' ),
							'jet-box-effect-5'  => esc_html__( 'Pull Down', 'jet-elements' ),
						),
					),
					'slide'     => array(
						'label'   => esc_html__( 'Slide', 'jet-elements' ),
						'options' => array(
							'jet-box-effect-16' => esc_html__( 'Slide Left', 'jet-elements' ),
							'jet-box-effect-13' => esc_html__( 'Slide Top', 'jet-elements' ),
							'jet-box-effect-12' => esc_html__( 'Slide Right', 'jet-elements' ),
							'jet-box-effect-17' => esc_html__( 'Slide Bottom', 'jet-elements' ),
						),
					),
					'3d'     => array(
						'label'   => esc_html__( '3d', 'jet-elements' ),
						'options' => array(
							'jet-box-effect-1'  => esc_html__( 'Flip Horizontal', 'jet-elements' ),
							'jet-box-effect-2'  => esc_html__( 'Flip Vertical', 'jet-elements' ),
							'jet-box-effect-3'  => esc_html__( 'Fall Up', 'jet-elements' ),
							'jet-box-effect-4'  => esc_html__( 'Fall Right', 'jet-elements' ),
							'jet-box-effect-7'  => esc_html__( 'Flip Horizontal 3D', 'jet-elements' ),
							'jet-box-effect-8'  => esc_html__( 'Flip Vertical 3D', 'jet-elements' ),
						),
					),
				),
				'condition' => array(
					'switch_event_type' => array( 'hover', 'click', 'toggle' ),
				),
			)
		);

		$this->add_control(
			'paper_fold_direction',
			array(
				'label'   => esc_html__( 'Paper Fold Direction', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'   => esc_html__( 'Left', 'jet-elements' ),
					'top'    => esc_html__( 'Top', 'jet-elements' ),
					'right'  => esc_html__( 'Right', 'jet-elements' ),
					'bottom' => esc_html__( 'Bottom', 'jet-elements' ),
				),
				'condition' => array(
					'switch_event_type' => 'fold',
				),
			)
		);

		$this->add_control(
			'slide_out_direction',
			array(
				'label'   => esc_html__( 'Slide Out Direction', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'   => esc_html__( 'Left', 'jet-elements' ),
					'top'    => esc_html__( 'Top', 'jet-elements' ),
					'right'  => esc_html__( 'Right', 'jet-elements' ),
					'bottom' => esc_html__( 'Bottom', 'jet-elements' ),
				),
				'condition' => array(
					'switch_event_type' => 'slide-out',
				),
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'default' => 'h3',
			)
		);

		$this->add_control(
			'sub_title_html_tag',
			array(
				'label'   => esc_html__( 'Subtitle HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'default' => 'h4',
			)
		);

		$this->end_controls_section();

		/**
		 * General Style Section
		 */
		$this->_start_controls_section(
			'section_animated_box_general_style',
			array(
				'label'      => esc_html__( 'General', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_start_controls_tabs( 'general_style_tabs' );

		$this->_start_controls_tab(
			'tab_front_general_styles',
			array(
				'label' => esc_html__( 'Front', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'front_vertical_alignment',
			array(
				'label'   => esc_html__( 'Content Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'flex-start'    => esc_html__( 'Top', 'jet-elements' ),
					'center'        => esc_html__( 'Center', 'jet-elements' ),
					'flex-end'      => esc_html__( 'Bottom', 'jet-elements' ),
					'space-between' => esc_html__( 'Space Between', 'jet-elements' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['animated_box_front'] . ' .jet-animated-box__inner' => 'justify-content: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'front_side_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_front'],
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
						'opacity' => 0.4,
					),
				),
			),
			25
		);

		$this->_add_responsive_control(
			'front_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_front'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'front_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['animated_box_front'],
			),
			50
		);

		$this->_add_responsive_control(
			'front_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_front'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['animated_box_front'] . ' .jet-animated-box__overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['animated_box_canvas'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['animated_box'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'front_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_front'],
			),
			100
		);

		$this->_add_control(
			'front_overlay_styles_heading',
			array(
				'label'     => esc_html__( 'Overlay', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'front_overlay_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_front'] . ' .jet-animated-box__overlay',
			),
			50
		);

		$this->_add_control(
			'front_order_heading',
			array(
				'label'     => esc_html__( 'Order', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			),
			25
		);

		$this->_add_control(
			'front_side_icon_order',
			array(
				'label'   => esc_html__( 'Icon Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['animated_box_icon_front'] => 'order: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'front_side_content_order',
			array(
				'label'   => esc_html__( 'Content Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['animated_box_front'] . ' .jet-animated-box__content' => 'order: {{VALUE}};',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_back_general_styles',
			array(
				'label' => esc_html__( 'Back', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'back_vertical_alignment',
			array(
				'label'   => esc_html__( 'Content Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'flex-start'    => esc_html__( 'Top', 'jet-elements' ),
					'center'        => esc_html__( 'Center', 'jet-elements' ),
					'flex-end'      => esc_html__( 'Bottom', 'jet-elements' ),
					'space-between' => esc_html__( 'Space Between', 'jet-elements' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['animated_box_back'] . ' .jet-animated-box__inner' => 'justify-content: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'back_side_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_back'],
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						),
					),
				),
			),
			25
		);

		$this->_add_responsive_control(
			'back_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_back'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'back_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_back'],
			),
			50
		);

		$this->_add_responsive_control(
			'back_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_back'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['animated_box_back'] . ' .jet-animated-box__overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'    => 'back_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_back'],
			),
			100
		);

		$this->_add_control(
			'back_overlay_styles_heading',
			array(
				'label'     => esc_html__( 'Overlay', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'back_overlay_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_back'] . ' .jet-animated-box__overlay',
			),
			50
		);

		$this->_add_control(
			'back_order_heading',
			array(
				'label'     => esc_html__( 'Order', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			),
			25
		);

		$this->_add_control(
			'back_side_icon_order',
			array(
				'label'   => esc_html__( 'Icon Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['animated_box_icon_back'] => 'order: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'back_side_content_order',
			array(
				'label'   => esc_html__( 'Content Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['animated_box_back'] . ' .jet-animated-box__content' => 'order: {{VALUE}};',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_control(
			'toggle_button_heading',
			array(
				'label'     => esc_html__( 'Toggle Button', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			),
			25
		);

		$this->_start_controls_tabs( 'toggle_style_tabs' );

		$this->_start_controls_tab(
			'tab_toggle_icon_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'     => 'toggle_button_style',
				'label'    => esc_html__( 'Toggle Styles', 'jet-elements' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_toggle'],
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_toggle_icon_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'     => 'toggle_button_hover_style',
				'label'    => esc_html__( 'Toggle Styles', 'jet-elements' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_toggle'] . ':hover',
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * Icon Style Section
		 */
		$this->_start_controls_section(
			'section_animated_box_icon_style',
			array(
				'label'      => esc_html__( 'Icon', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_start_controls_tabs( 'icon_style_tabs' );

		$this->_start_controls_tab(
			'tab_front_icon_styles',
			array(
				'label' => esc_html__( 'Front', 'jet-elements' ),
			)
		);

		$this->_add_responsive_control(
			'front_icon_box_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] => 'justify-content: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_control(
			'front_icon_color',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' i:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' svg' => 'fill: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'front_icon_bg_color',
			array(
				'label' => esc_html__( 'Icon Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' .jet-animated-box-icon-inner' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'front_icon_font_size',
			array(
				'label'      => esc_html__( 'Icon Font Size', 'jet-elements' ),
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
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' .jet-elements-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'front_icon_size',
			array(
				'label'      => esc_html__( 'Icon Box Size', 'jet-elements' ),
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
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' .jet-animated-box-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'front_icon_box_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_front']. ' .jet-animated-box-icon-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'front_icon_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' .jet-animated-box-icon-inner',
			),
			75
		);

		$this->_add_control(
			'front_icon_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' .jet-animated-box-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'front_icon_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_icon_front'] . ' .jet-animated-box-icon-inner',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_back_icon_styles',
			array(
				'label' => esc_html__( 'Back', 'jet-elements' ),
			)
		);

		$this->_add_responsive_control(
			'back_icon_box_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] => 'justify-content: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_control(
			'back_icon_color',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' i:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' svg' => 'fill: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'back_icon_bg_color',
			array(
				'label' => esc_html__( 'Icon Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .jet-animated-box-icon-inner' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'back_icon_font_size',
			array(
				'label'      => esc_html__( 'Icon Font Size', 'jet-elements' ),
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
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .jet-elements-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'back_icon_size',
			array(
				'label'      => esc_html__( 'Icon Box Size', 'jet-elements' ),
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
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .jet-animated-box-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'back_icon_box_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .jet-animated-box-icon-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'back_icon_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .jet-animated-box-icon-inner',
			),
			75
		);

		$this->_add_control(
			'back_icon_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .jet-animated-box-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'back_icon_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_icon_back'] . ' .jet-animated-box-icon-inner',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * Title Style Section
		 */
		$this->_start_controls_section(
			'section_animated_box_title_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_start_controls_tabs( 'title_style_tabs' );

		$this->_start_controls_tab(
			'tab_front_title_styles',
			array(
				'label' => esc_html__( 'Front', 'jet-elements' ),
			)
		);

		$this->_add_responsive_control(
			'front_title_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['animated_box_title_front'] => 'align-self: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'front_title_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
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
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_title_front'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			75
		);

		$this->_add_control(
			'front_title_color',
			array(
				'label'  => esc_html__( 'Title Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_title_front'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'front_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_title_front'],
			),
			50
		);

		$this->_add_responsive_control(
			'front_title_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_title_front'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'front_title_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_title_front'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_back_title_styles',
			array(
				'label' => esc_html__( 'Back', 'jet-elements' ),
			)
		);

		$this->_add_responsive_control(
			'back_title_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['animated_box_title_back'] => 'align-self: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'back_title_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
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
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_title_back'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_add_control(
			'back_title_color',
			array(
				'label'  => esc_html__( 'Title Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_title_back'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'back_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_title_back'],
			),
			50
		);

		$this->_add_responsive_control(
			'back_title_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_title_back'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'back_title_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_title_back'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * Subtitle Style Section
		 */
		$this->_start_controls_section(
			'section_animated_box_subtitle_style',
			array(
				'label'      => esc_html__( 'Subtitle', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_start_controls_tabs( 'subtitle_style_tabs' );

		$this->_start_controls_tab(
			'tab_front_subtitle_styles',
			array(
				'label' => esc_html__( 'Front', 'jet-elements' ),
			)
		);

		$this->_add_responsive_control(
			'front_subtitle_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'] => 'align-self: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'front_subtitle_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
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
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			75
		);

		$this->_add_control(
			'front_subtitle_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'front_subtitle_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'],
			),
			50
		);

		$this->_add_responsive_control(
			'front_subtitle_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'front_subtitle_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_front'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_back_subtitle_styles',
			array(
				'label' => esc_html__( 'Back', 'jet-elements' ),
			)
		);

		$this->_add_responsive_control(
			'back_subtitle_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'] => 'align-self: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'back_subtitle_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
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
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			75
		);

		$this->_add_control(
			'back_subtitle_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'back_subtitle_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'],
			),
			50
		);

		$this->_add_responsive_control(
			'back_subtitle_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'back_subtitle_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_subtitle_back'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * Description Style Section
		 */
		$this->_start_controls_section(
			'section_animated_box_description_style',
			array(
				'label'      => esc_html__( 'Description', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_start_controls_tabs( 'description_style_tabs' );

		$this->_start_controls_tab(
			'tab_front_description_styles',
			array(
				'label' => esc_html__( 'Front', 'jet-elements' ),
			)
		);

		$this->_add_responsive_control(
			'front_description_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
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
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_desc_front'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_add_control(
			'front_description_color',
			array(
				'label'  => esc_html__( 'Description Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_desc_front'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'front_description_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_desc_front'],
			),
			50
		);

		$this->_add_responsive_control(
			'front_description_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_desc_front'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'front_description_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_desc_front'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_back_description_styles',
			array(
				'label' => esc_html__( 'Back', 'jet-elements' ),
			)
		);

		$this->_add_responsive_control(
			'back_description_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
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
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_desc_back'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_add_control(
			'back_description_color',
			array(
				'label'  => esc_html__( 'Description Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_desc_back'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'back_description_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_desc_back'],
			),
			50
		);

		$this->_add_responsive_control(
			'back_description_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_desc_back'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'back_description_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_desc_back'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * Action Button Style Section
		 */
		$this->_start_controls_section(
			'section_action_button_style',
			array(
				'label'      => esc_html__( 'Action Button', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'back_button_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'align-self: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_control(
			'add_button_icon',
			array(
				'label'        => esc_html__( 'Add Icon', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			),
			25
		);

		$this->_add_icon_control(
			'button_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'condition'   => array(
					'add_button_icon' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'button_icon_position',
			array(
				'label'   => esc_html__( 'Icon Position', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'before'  => esc_html__( 'Before Text', 'jet-elements' ),
					'after' => esc_html__( 'After Text', 'jet-elements' ),
				),
				'default'     => 'after',
				'render_type' => 'template',
				'condition' => array(
					'add_button_icon' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'button_icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'jet-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 7,
						'max' => 90,
					),
				),
				'condition' => array(
					'add_button_icon' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_button_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_control(
			'button_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'add_button_icon' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_button_icon'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'button_icon_margin',
			array(
				'label'      => esc_html__( 'Icon Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_button_icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'add_button_icon' => 'yes',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_start_controls_tabs( 'tabs_button_style' );

		$this->_start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'button_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['animated_box_button'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_button'],
			),
			50
		);

		$this->_add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_button'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_hover_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['animated_box_button'] . ':hover',
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover',
			),
			50
		);

		$this->_add_responsive_control(
			'button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_box_button'] . ':hover',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	/**
	 * Generate setting json
	 *
	 * @return string
	 */
	public function generate_setting_json() {
		$settings = $this->get_settings();

		$instance_settings = array(
			'widgetId'           => $this->get_id(),
			'switchEventType'    => $settings['switch_event_type'],
			'paperFoldDirection' => isset( $settings['paper_fold_direction'] ) ? $settings['paper_fold_direction'] : 'left',
			'slideOutDirection'  => isset( $settings['slide_out_direction'] ) ? $settings['slide_out_direction'] : 'left',
		);

		if ( 'scratch' === $settings['switch_event_type'] ) {
			$instance_settings['scratchFillPercent'] = ! empty( $settings['scratch_fill_percent']['size'] ) ? $settings['scratch_fill_percent']['size'] : 75;
		}

		$instance_settings = json_encode( $instance_settings );

		return sprintf( 'data-settings=\'%1$s\'', $instance_settings );
	}

	/**
	 * Get item template content.
	 *
	 * @return string|void
	 */
	protected function get_template_content( $template_id ) {

		if ( empty( $template_id ) ) {
			return;
		}

		// for multi-language plugins
		$template_id = apply_filters( 'jet-elements/widgets/template_id', $template_id, $this );
		$content     = jet_elements()->elementor()->frontend->get_builder_content_for_display( $template_id );

		if ( jet_elements()->elementor()->editor->is_edit_mode() ) {
			$edit_url = add_query_arg(
				array(
					'elementor' => '',
				),
				get_permalink( $template_id )
			);
			$edit_link = sprintf(
				'<a class="jet-elements-edit-template-link" href="%s" title="%s" target="_blank"><span class="dashicons dashicons-edit"></span></a>',
				esc_url( $edit_url ),
				esc_html__( 'Edit Template', 'jet-elements' )
			);
			$content .= $edit_link;
		}

		return $content;
	}

}
