<?php
/**
 * Class: Jet_Image_Accordion_Widget
 * Name: Image Accordion
 * Slug: jet-image-accordion
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

class Jet_Image_Accordion_Widget extends Jet_Tabs_Base {

	public function get_name() {
		return 'jet-image-accordion';
	}

	public function get_title() {
		return esc_html__( 'Image Accordion', 'jet-tabs' );
	}

	public function get_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jettabs-image-accordion-widget-for-elementor-how-to-display-images-in-the-form-of-an-accordion?utm_source=jettabs&utm_medium=jet-image-accordion&utm_campaign=need-help';
	}

	public function get_icon() {
		return 'jet-tabs-icon-image-accordion';
	}

	public function get_categories() {
		return array( 'jet-tabs' );
	}

	public function get_script_depends() {
		return array( 'imagesloaded' );
	}

	public $item_count = 0;

	protected function register_controls() {
		$css_scheme = apply_filters(
			'jet-tabs/image-accordion/css-scheme',
			array(
				'instance'          => '.jet-image-accordion',
				'list'              => '.jet-image-accordion__list',
				'item'              => '.jet-image-accordion__item',
				'item_inner'        => '.jet-image-accordion__inner',
				'image'             => '.jet-image-accordion__image-instance',
				'content_container' => '.jet-image-accordion__content',
				'title'             => '.jet-image-accordion__title',
				'desc'              => '.jet-image-accordion__desc',
				'button_container'  => '.jet-image-accordion__button-container',
				'button'            => '.jet-image-accordion__button',
			)
		);

		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Items', 'jet-tabs' ),
			)
		);

		do_action( 'jet-engine-query-gateway/control', $this, 'item_list' );

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
			'item_image',
			array(
				'label'   => esc_html__( 'Image', 'jet-tabs' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array( 'active' => true ),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'item_title',
			array(
				'label'   => esc_html__( 'Title', 'jet-tabs' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_desc',
			array(
				'label'   => esc_html__( 'Description', 'jet-tabs' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_link_text',
			array(
				'label'   => esc_html__( 'Button text', 'jet-tabs' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'More', 'jet-tabs' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_link',
			array(
				'label'       => esc_html__( 'Link', 'jet-tabs' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'dynamic'     => array( 'active' => true ),
				'default'     => array(
					'url' => '#',
				),
			)
		);

		$this->add_control(
			'item_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Item #1', 'jet-tabs' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-tabs' ),
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Item #2', 'jet-tabs' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-tabs' ),
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Item #3', 'jet-tabs' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-tabs' ),
					),
				),
				'title_field' => '{{{ item_title }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-tabs' ),
			)
		);

		$this->add_control(
			'item_html_tag',
			array(
				'label'       => esc_html__( 'HTML Tag', 'jet-tabs' ),
				'description' => esc_html__( 'Select the HTML Tag for the Item\'s title', 'jet-tabs' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $this->get_available_item_html_tags(),
				'default'     => 'h5',
			)
		);

		$this->add_control(
			'img_size',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Images Size', 'jet-tabs' ),
				'default'    => 'full',
				'options'    => $this->get_image_sizes(),
			)
		);

		$this->add_responsive_control(
			'instance_orientation',
			array(
				'label'   => esc_html__( 'Orientation', 'jet-tabs' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => array(
					'vertical'   => esc_html__( 'Vertical', 'jet-tabs' ),
					'horizontal' => esc_html__( 'Horizontal', 'jet-tabs' ),
				),
				'selectors_dictionary' => array(
					'vertical'   => 'flex-direction: row',
					'horizontal' => 'flex-direction: column',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['list'] => '{{VALUE}}'
				),
			)
		);

		$this->add_control(
			'active_size',
			array(
				'label' => esc_html__( 'Active Size(%)', 'jet-tabs' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'%' => array(
						'min' => 50,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => '%',
					'size' => 50,
				),
			)
		);

		$this->add_control(
			'anim_duration',
			array(
				'label'   => esc_html__( 'Animation Duration', 'jet-tabs' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
				'min'     => 100,
				'max'     => 3000,
				'step'    => 100,
			)
		);

		$this->add_control(
			'anim_ease',
			array(
				'label'   => esc_html__( 'Easing', 'jet-tabs' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'circ',
				'options' => array(
					'sine'  => esc_html__( 'Sine', 'jet-tabs' ),
					'quint' => esc_html__( 'Quint', 'jet-tabs' ),
					'cubic' => esc_html__( 'Cubic', 'jet-tabs' ),
					'expo'  => esc_html__( 'Expo', 'jet-tabs' ),
					'circ'  => esc_html__( 'Circ', 'jet-tabs' ),
					'back'  => esc_html__( 'Back', 'jet-tabs' ),
				),
			)
		);

		$this->end_controls_section();

		$this->__start_controls_section(
			'section_container_style',
			array(
				'label'      => esc_html__( 'Container', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'instance_height',
			array(
				'label' => esc_html__( 'Height(px)', 'jet-tabs' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 600,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['list'] => 'height: {{SIZE}}{{UNIT}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'instance_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			100
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'instance_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			50
		);

		$this->__add_control(
			'instance_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'instance_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			100
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'section_item_style',
			array(
				'label'      => esc_html__( 'Item', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'item_gutter',
			array(
				'label' => esc_html__( 'Item Gutter', 'jet-tabs' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 4,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'margin: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} ' . $css_scheme['list'] => 'margin: calc(-{{SIZE}}{{UNIT}} / 2);',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'item_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'],
			),
			100
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'item_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item'],
			),
			50
		);

		$this->__add_control(
			'item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'],
			),
			100
		);

		$this->__add_control(
			'item_cover_style_heading',
			array(
				'label'     => esc_html__( 'Cover Styles', 'jet-tabs' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			50
		);

		$this->__start_controls_tabs( 'item_cover_tabs_styles', 50 );

		$this->__start_controls_tab(
			'item_cover_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-tabs' ),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'item_cover_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':before',
			),
			25
		);

		$this->__end_controls_tab( 50 );

		$this->__start_controls_tab(
			'item_cover_active',
			array(
				'label' => esc_html__( 'Active', 'jet-tabs' ),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'item_cover_background_active',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.active-accordion:before',
			),
			50
		);

		$this->__end_controls_tab( 50 );

		$this->__end_controls_tabs( 50 );

		$this->__end_controls_section();

		/**
		 * Content Style Section
		 */
		$this->__start_controls_section(
			'section_content_style',
			array(
				'label'      => esc_html__( 'Content', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'items_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-tabs' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Top', 'jet-tabs' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tabs' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-tabs' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['content_container']  => 'justify-content: {{VALUE}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'item_content_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content_container'],
			),
			50
		);

		$this->__add_responsive_control(
			'item_content_padding',
			array(
				'label'      => __( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content_container'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__end_controls_section();

		/**
		 * Title Style Section
		 */
		$this->__start_controls_section(
			'section_title_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'title_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-tabs' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->__add_control(
			'title_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-tabs' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			),
			50
		);

		$this->__add_responsive_control(
			'title_padding',
			array(
				'label'      => __( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->__add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__end_controls_section();

		/**
		 * Description Style Section
		 */
		$this->__start_controls_section(
			'section_desc_style',
			array(
				'label'      => esc_html__( 'Description', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'desc_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-tabs' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->__add_control(
			'desc_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-tabs' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
			),
			50
		);

		$this->__add_responsive_control(
			'desc_padding',
			array(
				'label'      => __( 'Padding', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->__add_responsive_control(
			'desc_margin',
			array(
				'label'      => __( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__end_controls_section();

		/**
		 * Action Button Style Section
		 */
		$this->__start_controls_section(
			'section_action_button_style',
			array(
				'label'      => esc_html__( 'Action Button', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'button_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-tabs' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start'    => array(
						'title' => ! is_rtl() ? esc_html__( 'Left', 'jet-tabs' ) : esc_html__( 'Right', 'jet-tabs' ),
						'icon'  => ! is_rtl() ? 'fa fa-align-left' : 'fa fa-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-tabs' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => ! is_rtl() ? esc_html__( 'Right', 'jet-tabs' ) : esc_html__( 'Left', 'jet-tabs' ),
						'icon'  => ! is_rtl() ? 'fa fa-align-right' : 'fa fa-align-left',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'align-self: {{VALUE}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-tabs' ),
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
				'label'      => __( 'Margin', 'jet-tabs' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__start_controls_tabs( 'tabs_button_style' );

		$this->__start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-tabs' ),
			)
		);

		$this->__add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-tabs' ),
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
				'label' => esc_html__( 'Background Color', 'jet-tabs' ),
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
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
			),
			50
		);

		$this->__add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
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
				'label' => esc_html__( 'Hover', 'jet-tabs' ),
			)
		);

		$this->__add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-tabs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'primary_button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-tabs' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_hover_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button'] . ':hover',
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-tabs' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			),
			50
		);

		$this->__add_responsive_control(
			'button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-tabs' ),
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

		/**
		 * Order Style Section
		 */
		$this->__start_controls_section(
			'section_order_style',
			array(
				'label'      => esc_html__( 'Content Order and Alignment', 'jet-tabs' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_control(
			'title_order',
			array(
				'label'   => esc_html__( 'Title Order', 'jet-tabs' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 3,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['title'] => 'order: {{VALUE}};',
				),
			),
			25
		);

		$this->__add_control(
			'desc_order',
			array(
				'label'   => esc_html__( 'Description Order', 'jet-tabs' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 3,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['desc'] => 'order: {{VALUE}};',
				),
			),
			25
		);

		$this->__add_control(
			'button_order',
			array(
				'label'   => esc_html__( 'Button Order', 'jet-tabs' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 3,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['button'] => 'order: {{VALUE}};',
				),
			),
			25
		);

		$this->__end_controls_section();
	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();

		include $this->__get_global_template( 'index' );

		$this->__close_wrap();
	}

	/**
	 * Generate setting json
	 *
	 * @return string
	 */
	public function generate_setting_json() {
		$module_settings = $this->get_settings();

		$settings = array(
			'orientation' => $module_settings['instance_orientation'],
			'activeSize'  => $module_settings['active_size'],
			'duration'    => $module_settings['anim_duration'],
			'activeItem'  => $this->get_active_item(),
		);

		$settings = json_encode( $settings );

		return $settings;
	}

	/**
	 * [__generate_action_button description]
	 * @param  boolean $cover_location [description]
	 * @return [type]                  [description]
	 */
	public function __generate_action_button( $cover_location = false ) {
		$item = $this->__processed_item;

		$button_url = $item[ 'item_link' ];
		$button_text = $item[ 'item_link_text' ];

		if ( empty( $button_url ) ) {
			return false;
		}

		if ( is_array( $button_url ) && empty( $button_url['url'] ) ) {
			return false;
		}

		$attr_name = 'url_' . $this->item_count;

		$this->add_render_attribute( $attr_name, 'class', array(
			'elementor-button',
			'elementor-size-md',
			'jet-image-accordion__button',
		) );

		$this->add_render_attribute( $attr_name, 'role', 'button' );
		$this->add_render_attribute( $attr_name, 'tabindex', 0 );

		if ( is_array( $button_url ) ) {
			if ( method_exists( $this, 'add_link_attributes' ) ) {
				$this->add_link_attributes( $attr_name, $button_url );
			} else {
				$this->add_render_attribute( $attr_name, 'href', $button_url['url'] );

				if ( $button_url['is_external'] ) {
					$this->add_render_attribute( $attr_name, 'target', '_blank' );
				}

				if ( ! empty( $button_url['nofollow'] ) ) {
					$this->add_render_attribute( $attr_name, 'rel', 'nofollow' );
				}
			}
		} else {
			$this->add_render_attribute( $attr_name, 'href', $button_url );
		}

		$format = apply_filters( 'jet-tabs/image-accordion/action-button-format', '<a %1$s>%2$s</a>' );

		$this->item_count++;

		return sprintf( $format, $this->get_render_attribute_string( $attr_name ), $button_text );
	}

	/**
	 * Get active toggle item
	 *
	 * @return int
	 */
	public function get_active_item() {
		$active_index = -1;
		$items = $this->get_settings( 'item_list' );
		$items = apply_filters( 'jet-tabs/widget/loop-items', $items, 'item_list', $this );

		foreach ( $items as $index => $item ) {
			if ( array_key_exists( 'item_active', $item ) && filter_var( $item['item_active'], FILTER_VALIDATE_BOOLEAN ) ) {
				$active_index = $index;
			}
		}

		return $active_index;
	}

	/**
	 * Get item image
	 */
	public function __loop_item_image() {
		$settings = $this->get_settings_for_display();
		$item     = $this->__processed_item;
		$image    = $item['item_image'];
		$size     = isset( $settings['img_size'] ) ? $settings['img_size'] : 'full';
		$format   = '<img class="jet-image-accordion__image-instance" src="%1$s" alt="%2$s">';

		if ( empty( $image['id'] ) && empty( $image['url'] ) ) {
			return false;
		}

		if ( empty( $image['id'] ) ) {
			return sprintf( $format, Utils::get_placeholder_image_src(), '' );
		}

		$src = wp_get_attachment_image_url( $image['id'], $size );
		$alt = Control_Media::get_image_alt( $image );

		return sprintf( $format, esc_url( $src ), esc_attr( $alt ) );
	}

	/**
		 * Returns image size array in slug => name format
		 *
		 * @return  array
		 */
		public function get_image_sizes() {

			global $_wp_additional_image_sizes;

			$sizes  = get_intermediate_image_sizes();
			$result = array();

			foreach ( $sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
				} else {
					$result[ $size ] = sprintf(
						'%1$s (%2$sx%3$s)',
						ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
						$_wp_additional_image_sizes[ $size ]['width'],
						$_wp_additional_image_sizes[ $size ]['height']
					);
				}
			}

			return array_merge( array( 'full' => esc_html__( 'Full', 'jet-elements' ), ), $result );
		}
}
