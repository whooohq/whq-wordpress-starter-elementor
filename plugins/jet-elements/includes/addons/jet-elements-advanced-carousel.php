<?php
/**
 * Class: Jet_Elements_Advanced_Carousel
 * Name: Advanced Carousel
 * Slug: jet-carousel
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

class Jet_Elements_Advanced_Carousel extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-carousel';
	}

	public function get_title() {
		return esc_html__( 'Advanced Carousel', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-carousel';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-create-a-carousel-using-advanced-carousel-jetelements-widget-for-elementor/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'jet-slick' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_slides',
			array(
				'label' => esc_html__( 'Slides', 'jet-elements' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_image',
			array(
				'label'   => esc_html__( 'Image', 'jet-elements' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_content_type',
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

		$repeater->add_control(
			'item_title',
			array(
				'label'       => esc_html__( 'Item Title', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'item_content_type' => 'default',
				),
			)
		);

		$repeater->add_control(
			'item_text',
			array(
				'label'       => esc_html__( 'Item Description', 'jet-elements' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'item_content_type' => 'default',
				),
			)
		);

		$repeater->add_control(
			'item_link',
			array(
				'label'       => esc_html__( 'Item Link', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
				'condition'   => array(
					'item_content_type' => 'default',
				),
			)
		);

		$repeater->add_control(
			'item_link_target',
			array(
				'label'        => esc_html__( 'Open link in new window', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '_blank',
				'condition'    => array(
					'item_content_type' => 'default',
					'item_link!'        => '',
				),
			)
		);

		$repeater->add_control(
			'item_link_rel',
			array(
				'label'        => esc_html__( 'Add nofollow', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'nofollow',
				'condition'    => array(
					'item_content_type' => 'default',
					'item_link!'        => '',
				),
			)
		);

		$repeater->add_control(
			'item_button_text',
			array(
				'label'       => esc_html__( 'Item Button Text', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'dynamic'     => array(
					'active' => true
				),
				'condition'   => array(
					'item_content_type' => 'default',
				),
			)
		);

		$repeater->add_control(
			'template_id',
			array(
				'label'       => esc_html__( 'Choose Template', 'jet-elements' ),
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
				'edit_button' => array(
					'active' => true,
					'label'  => __( 'Edit Template', 'jet-elements' ),
				),
				'condition'   => array(
					'item_content_type' => 'template',
				),
			)
		);

		$this->add_control(
			'items_list',
			array(
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array(
						'item_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title' => esc_html__( 'Item #1', 'jet-elements' ),
						'item_text'  => esc_html__( 'Item #1 Description', 'jet-elements' ),
						'item_link'  => '#',
						'item_link_target'  => '',
					),
				),
				'title_field' => '{{{ item_title }}}',
			)
		);

		$this->add_control(
			'item_link_type',
			array(
				'label'   => esc_html__( 'Item link type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'link',
				'options' => array(
					'link'     => esc_html__( 'Url', 'jet-elements' ),
					'lightbox' => esc_html__( 'Lightbox', 'jet-elements' ),
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
			'item_layout',
			array(
				'label'   => esc_html__( 'Items Layout', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'simple',
				'options' => array(
					'banners'=> esc_html__( 'Banners', 'jet-elements' ),
					'simple' => esc_html__( 'Simple', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'animation_effect',
			array(
				'label'   => esc_html__( 'Animation Effect', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'lily',
				'options' => array(
					'lily'   => esc_html__( 'Lily', 'jet-elements' ),
					'sadie'  => esc_html__( 'Sadie', 'jet-elements' ),
					'layla'  => esc_html__( 'Layla', 'jet-elements' ),
					'oscar'  => esc_html__( 'Oscar', 'jet-elements' ),
					'marley' => esc_html__( 'Marley', 'jet-elements' ),
					'ruby'   => esc_html__( 'Ruby', 'jet-elements' ),
					'roxy'   => esc_html__( 'Roxy', 'jet-elements' ),
					'bubba'  => esc_html__( 'Bubba', 'jet-elements' ),
					'romeo'  => esc_html__( 'Romeo', 'jet-elements' ),
					'sarah'  => esc_html__( 'Sarah', 'jet-elements' ),
					'chico'  => esc_html__( 'Chico', 'jet-elements' ),
				),
				'condition' => array(
					'item_layout' => 'banners',
				),
			)
		);

		$this->add_control(
			'img_size',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Images Size', 'jet-elements' ),
				'default'    => 'full',
				'options'    => jet_elements_tools()->get_image_sizes(),
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'default' => 'h5',
			)
		);

		$this->add_control(
			'link_title',
			array(
				'label'     => esc_html__( 'Link Title', 'jet-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'item_layout' => 'simple',
				),
			)
		);

		$this->add_control(
			'equal_height_cols',
			array(
				'label'        => esc_html__( 'Equal Columns Height', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'fluid_width',
			array(
				'label'        => esc_html__( 'Fluid Columns Width', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_responsive_control(
			'slides_to_show',
			array(
				'label'              => esc_html__( 'Slides to Show', 'jet-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '2',
				'options'            => jet_elements_tools()->get_select_range( 10 ),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'label'              => esc_html__( 'Slides to Scroll', 'jet-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '1',
				'options'            => jet_elements_tools()->get_select_range( 10 ),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'arrows',
			array(
				'label'        => esc_html__( 'Show Arrows Navigation', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->_add_advanced_icon_control(
			'prev_arrow',
			array(
				'label'       => esc_html__( 'Prev Arrow Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-angle-left',
				'fa5_default' => array(
					'value'   => 'fas fa-angle-left',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'arrows' => 'true',
				),
			)
		);

		$this->_add_advanced_icon_control(
			'next_arrow',
			array(
				'label'       => esc_html__( 'Next Arrow Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-angle-right',
				'fa5_default' => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'arrows' => 'true',
				),
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'        => esc_html__( 'Show Dots Navigation', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'fraction_navigation',
			array(
				'label'        => esc_html__( 'Show Fraction Navigation', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			array(
				'label' => esc_html__( 'Additional Options', 'jet-elements' ),
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'        => esc_html__( 'Pause on Hover', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Autoplay', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => esc_html__( 'Autoplay Speed', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => array(
					'autoplay' => 'true',
				),
			)
		);

		$this->add_control(
			'infinite',
			array(
				'label'        => esc_html__( 'Infinite Loop', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'centered',
			array(
				'label'        => esc_html__( 'Center Mode', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'effect',
			array(
				'label'   => esc_html__( 'Effect', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => array(
					'slide' => esc_html__( 'Slide', 'jet-elements' ),
					'fade'  => esc_html__( 'Fade', 'jet-elements' ),
				),
				'condition' => array(
					'slides_to_show' => '1',
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'   => esc_html__( 'Animation Speed', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
			)
		);

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-elements/advanced-carousel/css-scheme',
			array(
				'arrow_next'     => '.jet-carousel .elementor-slick-slider .slick-next:before',
				'arrow_prev'     => '.jet-carousel .elementor-slick-slider .slick-prev:before',
				'arrow_next_hov' => '.jet-carousel .elementor-slick-slider .slick-next:hover:before',
				'arrow_prev_hov' => '.jet-carousel .elementor-slick-slider .slick-prev:hover:before',
				'dot'            => '.jet-carousel .elementor-slick-slider .slick-dots li button:before',
				'dot_hover'      => '.jet-carousel .elementor-slick-slider .slick-dots li button:hover:before',
				'dot_active'     => '.jet-carousel .elementor-slick-slider .slick-dots .slick-active button:before',
				'wrap'           => '.jet-carousel .elementor-slick-slider',
				'column'         => '.jet-carousel .elementor-slick-slider .jet-carousel__item',
				'image'          => '.jet-carousel__item-img',
				'items'          => '.jet-carousel__content',
				'items_title'    => '.jet-carousel__content .jet-carousel__item-title',
				'items_text'     => '.jet-carousel__content .jet-carousel__item-text',
				'items_button'   => '.jet-carousel__content .jet-carousel__item-button',
				'banner'         => '.jet-banner',
				'banner_content' => '.jet-banner__content',
				'banner_overlay' => '.jet-banner__overlay',
				'banner_title'   => '.jet-banner__title',
				'banner_text'    => '.jet-banner__text',
			)
		);

		$this->_start_controls_section(
			'section_column_style',
			array(
				'label'      => esc_html__( 'Column', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'column_padding',
			array(
				'label'       => esc_html__( 'Column Padding', 'jet-elements' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px' ),
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['column'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['wrap'] => 'margin-right: -{{RIGHT}}{{UNIT}}; margin-left: -{{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_control(
			'column_margin',
			array(
				'label'       => esc_html__( 'Column Margin', 'jet-elements' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px' ),
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['column'] . ' .jet-carousel__item-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'column_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['column'] . ' .jet-carousel__item-inner',
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_simple_item_style',
			array(
				'label'      => esc_html__( 'Simple Item', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'item_layout' => 'simple',
				),
			)
		);

		$this->_add_control(
			'item_image_heading',
			array(
				'label' => esc_html__( 'Image', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
			),
			75
		);

		$this->_add_responsive_control(
			'item_image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_image_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['image'],
			),
			75
		);

		$this->_add_control(
			'item_content_heading',
			array(
				'label'     => esc_html__( 'Content', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_start_controls_tabs( 'tabs_item_style' );

		$this->_start_controls_tab(
			'tab_item_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'simple_item_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['items'],
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'item_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['items'],
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['items'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_item_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'simple_item_bg_hover',
				'selector' => '{{WRAPPER}} .jet-carousel__item:hover ' . $css_scheme['items'],
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'item_border_hover',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .jet-carousel__item:hover ' . $css_scheme['items'],
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_box_shadow_hover',
				'selector' => '{{WRAPPER}} .jet-carousel__item:hover ' . $css_scheme['items'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_responsive_control(
			'items_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
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
				'separator' => 'before',
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['items'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->_add_responsive_control(
			'items_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['items'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['items'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_banner_item_style',
			array(
				'label'      => esc_html__( 'Banner Item', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'item_layout' => 'banners',
				),
			)
		);

		$this->_start_controls_tabs( 'tabs_background' );

		$this->_start_controls_tab(
			'tab_background_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'items_content_color',
			array(
				'label'     => esc_html__( 'Additional Elements Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-effect-layla ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-layla ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-oscar ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-marley ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-ruby ' . $css_scheme['banner_text'] => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-roxy ' . $css_scheme['banner_text'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-roxy ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-bubba ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-bubba ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-romeo ' . $css_scheme['banner_content'] . '::before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-romeo ' . $css_scheme['banner_content'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-sarah ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-chico ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-lily .jet-banner__overlay' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['banner_overlay'],
			),
			25
		);

		$this->_add_control(
			'normal_opacity',
			array(
				'label'   => esc_html__( 'Opacity', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '0',
				'min'     => 0,
				'max'     => 1,
				'step'    => 0.1,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner_overlay'] => 'opacity: {{VALUE}};',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_background_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'items_content_hover_color',
			array(
				'label'     => esc_html__( 'Additional Elements Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-effect-layla:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-layla:hover ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-oscar:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-marley:hover ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-ruby:hover ' . $css_scheme['banner_text'] => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-roxy:hover ' . $css_scheme['banner_text'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-roxy:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-bubba:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-bubba:hover ' . $css_scheme['banner_content'] . '::after' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-romeo:hover ' . $css_scheme['banner_content'] . '::before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-romeo:hover ' . $css_scheme['banner_content'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-sarah:hover ' . $css_scheme['banner_title'] . '::after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-chico:hover ' . $css_scheme['banner_content'] . '::before' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .jet-effect-lily:hover .jet-banner__overlay' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['banner'] . ':hover ' . $css_scheme['banner_overlay'],
			),
			25
		);

		$this->_add_control(
			'hover_opacity',
			array(
				'label'   => esc_html__( 'Opacity', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '0.4',
				'min'     => 0,
				'max'     => 1,
				'step'    => 0.1,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['banner'] . ':hover ' . $css_scheme['banner_overlay'] => 'opacity: {{VALUE}};',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_item_title_style',
			array(
				'label'      => esc_html__( 'Item Title', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'items_title_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
				'selector'  => '{{WRAPPER}}  ' . $css_scheme['items_title'] . ', {{WRAPPER}}  ' . $css_scheme['items_title'] . ' a, {{WRAPPER}} ' . $css_scheme['banner_title'],
			),
			50
		);

		$this->_start_controls_tabs( 'tabs_title_style' );

		$this->_start_controls_tab(
			'tab_title_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'items_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['items_title'] => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['banner_title'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_title_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'items_title_color_hover',
			array(
				'label'     => esc_html__( 'Title Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel__item:hover ' . $css_scheme['items_title'] => 'color: {{VALUE}}',
					'{{WRAPPER}} .jet-carousel__item:hover ' . $css_scheme['banner_title'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'items_title_link_color_hover',
			array(
				'label'     => esc_html__( 'Link Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel__item ' . $css_scheme['items_title'] . ' a:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'item_layout' => 'simple',
					'link_title' => 'yes',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_responsive_control(
			'items_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['items_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['banner_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_item_text_style',
			array(
				'label'      => esc_html__( 'Item Content', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'items_text_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['items_text'] . ', {{WRAPPER}} ' . $css_scheme['banner_text'],
			),
			50
		);

		$this->_start_controls_tabs( 'tabs_text_style' );

		$this->_start_controls_tab(
			'tab_text_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'items_text_color',
			array(
				'label'     => esc_html__( 'Content Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['items_text'] => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['banner_text'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_text_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'items_text_color_hover',
			array(
				'label'     => esc_html__( 'Content Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel__item:hover ' . $css_scheme['items_text'] => 'color: {{VALUE}}',
					'{{WRAPPER}} .jet-carousel__item:hover ' . $css_scheme['banner_text'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_responsive_control(
			'items_text_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['items_text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['banner_text'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

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
				'condition'  => array(
					'item_layout' => 'simple',
				),
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['items_button'],
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
					'{{WRAPPER}} ' . $css_scheme['items_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['items_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['items_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'after',
			),
			75
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
					'{{WRAPPER}} ' . $css_scheme['items_button'] => 'color: {{VALUE}}',
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
					'{{WRAPPER}} ' . $css_scheme['items_button'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['items_button'],
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['items_button'],
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
					'{{WRAPPER}} ' . $css_scheme['items_button'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'primary_button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['items_button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['items_button'] . ':hover',
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['items_button'] . ':hover',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_arrows_style',
			array(
				'label'      => esc_html__( 'Carousel Arrows', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_start_controls_tabs( 'tabs_arrows_style' );

		$this->_start_controls_tab(
			'tab_prev',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'arrows_style',
				'label'          => esc_html__( 'Arrows Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} .jet-carousel .jet-arrow',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
					),
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_next_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'arrows_hover_style',
				'label'          => esc_html__( 'Arrows Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} .jet-carousel .jet-arrow:hover',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
					),
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_control(
			'prev_arrow_position',
			array(
				'label'     => esc_html__( 'Prev Arrow Position', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			75
		);

		$this->_add_control(
			'prev_vert_position',
			array(
				'label'   => esc_html__( 'Vertical Position by', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-elements' ),
					'bottom' => esc_html__( 'Bottom', 'jet-elements' ),
				),
			),
			75
		);

		$this->_add_responsive_control(
			'prev_top_position',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'prev_vert_position' => 'top',
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-carousel .jet-arrow.prev-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'prev_bottom_position',
			array(
				'label'      => esc_html__( 'Bottom Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'prev_vert_position' => 'bottom',
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-carousel .jet-arrow.prev-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			),
			75
		);

		$this->_add_control(
			'prev_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-elements' ),
					'right' => esc_html__( 'Right', 'jet-elements' ),
				),
			),
			75
		);

		$this->_add_responsive_control(
			'prev_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'prev_hor_position' => 'left',
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-carousel .jet-arrow.prev-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'prev_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'prev_hor_position' => 'right',
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-carousel .jet-arrow.prev-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				),
			),
			75
		);

		$this->_add_control(
			'next_arrow_position',
			array(
				'label'     => esc_html__( 'Next Arrow Position', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			75
		);

		$this->_add_control(
			'next_vert_position',
			array(
				'label'   => esc_html__( 'Vertical Position by', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'    => esc_html__( 'Top', 'jet-elements' ),
					'bottom' => esc_html__( 'Bottom', 'jet-elements' ),
				),
			),
			75
		);

		$this->_add_responsive_control(
			'next_top_position',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'next_vert_position' => 'top',
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-carousel .jet-arrow.next-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'next_bottom_position',
			array(
				'label'      => esc_html__( 'Bottom Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'next_vert_position' => 'bottom',
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-carousel .jet-arrow.next-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
				),
			),
			75
		);

		$this->_add_control(
			'next_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-elements' ),
					'right' => esc_html__( 'Right', 'jet-elements' ),
				),
			),
			75
		);

		$this->_add_responsive_control(
			'next_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'next_hor_position' => 'left',
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-carousel .jet-arrow.next-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'next_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'next_hor_position' => 'right',
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-carousel .jet-arrow.next-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				),
			),
			75
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_dots_style',
			array(
				'label'      => esc_html__( 'Carousel Dots', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_start_controls_tabs( 'tabs_dots_style' );

		$this->_start_controls_tab(
			'tab_dots_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'dots_style',
				'label'          => esc_html__( 'Dots Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} .jet-carousel .jet-slick-dots li span',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_3,
						),
					),
				),
				'exclude' => array(
					'box_font_color',
					'box_font_size',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_dots_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'dots_style_hover',
				'label'          => esc_html__( 'Dots Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} .jet-carousel .jet-slick-dots li span:hover',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
					),
				),
				'exclude' => array(
					'box_font_color',
					'box_font_size',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_dots_active',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'dots_style_active',
				'label'          => esc_html__( 'Dots Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} .jet-carousel .jet-slick-dots li.slick-active span',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_4,
						),
					),
				),
				'exclude' => array(
					'box_font_color',
					'box_font_size',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_responsive_control(
			'dots_alignment',
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
					'{{WRAPPER}} .jet-carousel .jet-slick-dots' => 'justify-content: {{VALUE}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'dots_gap',
			array(
				'label' => esc_html__( 'Gap', 'jet-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 5,
					'unit' => 'px',
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel .jet-slick-dots li' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'dots_margin',
			array(
				'label'      => esc_html__( 'Dots Box Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-carousel .jet-slick-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_fraction_style',
			array(
				'label'      => esc_html__( 'Carousel Fraction Pagination', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'fraction_navigation' => 'true'
				)
			)
		);

		$this->_start_controls_tabs( 'tabs_fraction_style' );

		$this->_start_controls_tab(
			'tab_fraction_current',
			array(
				'label' => esc_html__( 'Current Value', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'fraction_current_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel__fraction-navigation .current' => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'fraction_current_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel__fraction-navigation .current' => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'fraction_current_padding',
			array(
				'label'       => esc_html__( 'Padding', 'jet-elements' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'selectors'   => array(
					'{{WRAPPER}} .jet-carousel__fraction-navigation .current' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'fraction_total',
			array(
				'label' => esc_html__( 'Total Value', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'fraction_total_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel__fraction-navigation .total' => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'fraction_total_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel__fraction-navigation .total' => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'fraction_total_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'fraction_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel .jet-carousel__fraction-navigation .total' => 'border-color: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'fraction_total_padding',
			array(
				'label'       => esc_html__( 'Padding', 'jet-elements' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'selectors'   => array(
					'{{WRAPPER}} .jet-carousel__fraction-navigation .total' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'fraction_separator',
			array(
				'label' => esc_html__( 'Separator', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'fraction_separator_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel__fraction-navigation .separator' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_responsive_control(
			'fraction_alignment',
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
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel__fraction-navigation' => 'justify-content: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fraction_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .jet-carousel__fraction-navigation span',
			),
			50
		);

		$this->_add_responsive_control(
			'fraction_gap',
			array(
				'label'   => esc_html__( 'Gap', 'jet-elements' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 5,
					'unit' => 'px',
				),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel__fraction-navigation .separator' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'fraction_margin',
			array(
				'label'      => esc_html__( 'Pagination Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-carousel__fraction-navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'fraction_border',
				'selector' => '{{WRAPPER}} .jet-carousel__fraction-navigation span:not(.separator)',
			)
		);

		$this->_add_control(
			'fraction_border_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'jet-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors' => array(
					'{{WRAPPER}} .jet-carousel__fraction-navigation .current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .jet-carousel__fraction-navigation .total' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_end_controls_section();

	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	public function get_advanced_carousel_options() {

		$settings = $this->get_settings();
		$widget_id = $this->get_id();

		$options  = array(
			'autoplaySpeed'  => absint( $settings['autoplay_speed'] ),
			'autoplay'       => filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ),
			'infinite'       => filter_var( $settings['infinite'], FILTER_VALIDATE_BOOLEAN ),
			'centerMode'     => filter_var( $settings['centered'], FILTER_VALIDATE_BOOLEAN ),
			'pauseOnHover'   => filter_var( $settings['pause_on_hover'], FILTER_VALIDATE_BOOLEAN ),
			'speed'          => absint( $settings['speed'] ),
			'arrows'         => filter_var( $settings['arrows'], FILTER_VALIDATE_BOOLEAN ),
			'dots'           => filter_var( $settings['dots'], FILTER_VALIDATE_BOOLEAN ),
			'variableWidth'  => filter_var( $settings['fluid_width'], FILTER_VALIDATE_BOOLEAN ),
			'prevArrow'      => '.jet-carousel__prev-arrow-' . $widget_id,
			'nextArrow'      => '.jet-carousel__next-arrow-' . $widget_id,
			'rtl'            => is_rtl(),
			'fractionNav'    => filter_var( $settings['fraction_navigation'], FILTER_VALIDATE_BOOLEAN ),
		);

		if ( 1 === absint( $settings['slides_to_show'] ) ) {
			$options['fade'] = ( 'fade' === $settings['effect'] );
		}

		return $options;
	}

	public function get_advanced_carousel_img( $class = '' ) {

		$settings = $this->get_settings_for_display();
		$size     = isset( $settings['img_size'] ) ? $settings['img_size'] : 'full';
		$image    = isset( $this->_processed_item['item_image'] ) ? $this->_processed_item['item_image'] : '';

		if ( ! $image ) {
			return;
		}

		if ( 'full' !== $size && ! empty( $image['id'] ) ) {
			$url = wp_get_attachment_image_url( $image['id'], $size );
		} else {
			$url = $image['url'];
		}

		if ( empty( $url ) ) {
			return;
		}

		$alt = esc_attr( Control_Media::get_image_alt( $image ) );

		return sprintf( '<img src="%1$s" class="%2$s" alt="%3$s" loading="lazy">', $url, $class, $alt );

	}

	protected function _loop_button_item( $keys = array(), $format = '%s' ) {
		$item = $this->_processed_item;
		$params = [];

		foreach ( $keys as $key => $value ) {

			if ( ! array_key_exists( $value, $item ) ) {
				return false;
			}

			if ( empty( $item[$value] ) ) {
				return false;
			}

			$params[] = $item[ $value ];
		}

		return vsprintf( $format, $params );
	}

	/**
	 * Get item template content.
	 *
	 * @return string|void
	 */
	protected function _loop_item_template_content() {
		$template_id = $this->_processed_item['template_id'];

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
