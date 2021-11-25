<?php
/**
 * Class: Jet_Elements_Images_Layout
 * Name: Images Layout
 * Slug: jet-images-layout
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

class Jet_Elements_Images_Layout extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-images-layout';
	}

	public function get_title() {
		return esc_html__( 'Images Layout', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-images-layout';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-create-an-attractive-image-grid-masonry-or-justify-layout-with-jetelements-images-layout-widget-for-elementor/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'imagesloaded', 'jet-salvattore' );
	}

	/**
	 * [$item_counter description]
	 * @var integer
	 */
	public $item_counter = 0;

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-elements/images-layout/css-scheme',
			array(
				'instance'          => '.jet-images-layout',
				'list_container'    => '.jet-images-layout__list',
				'item'              => '.jet-images-layout__item',
				'inner'             => '.jet-images-layout__inner',
				'image_wrap'        => '.jet-images-layout__image',
				'image_instance'    => '.jet-images-layout__image-instance',
				'content_wrap'      => '.jet-images-layout__content',
				'icon'              => '.jet-images-layout__icon',
				'title'             => '.jet-images-layout__title',
				'desc'              => '.jet-images-layout__desc',
				'button'            => '.jet-images-layout__button',
			)
		);

		$this->start_controls_section(
			'section_items_data',
			array(
				'label' => esc_html__( 'Items', 'jet-elements' ),
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
			'item_image_size',
			array(
				'type'    => 'select',
				'label'   => esc_html__( 'Image Size', 'jet-elements' ),
				'default' => 'full',
				'options' => jet_elements_tools()->get_image_sizes(),
			)
		);

		$this->_add_advanced_icon_control(
			'item_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
			),
			$repeater
		);

		$repeater->add_control(
			'item_title',
			array(
				'label'   => esc_html__( 'Title', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_desc',
			array(
				'label'   => esc_html__( 'Description', 'jet-elements' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_link_type',
			array(
				'label'   => esc_html__( 'Link type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'lightbox',
				'options' => array(
					'lightbox' => esc_html__( 'Lightbox', 'jet-elements' ),
					'external' => esc_html__( 'External', 'jet-elements' ),
					'no_link'  => esc_html__( 'No link', 'jet-elements' ),
				),
			)
		);

		$repeater->add_control(
			'item_url',
			array(
				'label'   => esc_html__( 'External Link', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '#',
				'condition' => array(
					'item_link_type' => 'external',
				),
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
			)
		);

		$repeater->add_control(
			'item_target',
			array(
				'label'        => esc_html__( 'Open external link in new window', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '_blank',
				'default'      => '',
				'condition'    => array(
					'item_link_type' => 'external',
				),
			)
		);

		$repeater->add_control(
			'item_rel',
			array(
				'label'        => esc_html__( 'Add nofollow', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'nofollow',
				'default'      => '',
				'condition'    => array(
					'item_link_type' => 'external',
				),
			)
		);

		$this->add_control(
			'image_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #1', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
						'item_url'         => '#',
						'item_target'      => '',
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #2', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
						'item_url'         => '#',
						'item_target'      => '',
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #3', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
						'item_url'         => '#',
						'item_target'      => '',
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #4', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
						'item_url'         => '#',
						'item_target'      => '',
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #5', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
						'item_url'         => '#',
						'item_target'      => '',
					),
					array(
						'item_image'       => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'       => esc_html__( 'Image #6', 'jet-elements' ),
						'item_desc'        => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
						'item_url'         => '#',
						'item_target'      => '',
					),
				),
				'title_field' => '{{{ item_title }}}',
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
			'layout_type',
			array(
				'label'   => esc_html__( 'Layout type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'masonry',
				'options' => array(
					'masonry' => esc_html__( 'Masonry', 'jet-elements' ),
					'grid'    => esc_html__( 'Grid', 'jet-elements' ),
					'justify' => esc_html__( 'Justify', 'jet-elements' ),
					'list'    => esc_html__( 'List', 'jet-elements' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'   => esc_html__( 'Columns', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 3,
				'options' => jet_elements_tools()->get_select_range( 6 ),
				'condition' => array(
					'layout_type' => array( 'masonry', 'grid' ),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
				'selectors'          => array(
					'{{WRAPPER}} .salvattore-column' => 'width: calc(100% / {{VALUE}});',
					'{{WRAPPER}} .jet-images-layout__list::before' => 'content: "{{VALUE}} .salvattore-column"',
					'{{WRAPPER}} .layout-type-grid .jet-images-layout__list::before' => 'content: ""',
					'{{WRAPPER}} .layout-type-grid .jet-images-layout__list .jet-images-layout__item' => 'width: calc(100% / {{VALUE}});',
				),
			)
		);

		$this->add_control(
			'shuffle_image_list',
			array(
				'label'   => esc_html__( 'Shuffle Image List', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_responsive_control(
			'item_height',
			array(
				'label' => esc_html__( 'Item Height', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
				'default' => array(
					'size' => 300,
					'unit' => 'px',
				),
				'condition' => array(
					'layout_type' => 'grid',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['image_instance'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'justify_height',
			array(
				'label'    => esc_html__( 'Justify Height', 'jet-elements' ),
				'type'     => Controls_Manager::NUMBER,
				'default'  => 300,
				'min'      => 100,
				'max'      => 1000,
				'step'     => 1,
				'condition' => array(
					'layout_type' => array( 'justify' ),
				),
			)
		);

		$this->add_responsive_control(
			'item_margin',
			array(
				'label' => esc_html__( 'Items Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default' => [
					'size' => 10,
				],
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner']          => 'margin: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['list_container'] => 'margin: -{{SIZE}}{{UNIT}};',
				),
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

		$this->end_controls_section();

		/**
		 * Items Style Section
		 */
		$this->_start_controls_section(
			'section_images_layout_general_style',
			array(
				'label'      => esc_html__( 'Item', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'item_content_alignment',
			array(
				'label'   => esc_html__( 'Content Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Top', 'jet-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Middle', 'jet-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['content_wrap']  => 'justify-content: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'item_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'item_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['inner'],
			),
			50
		);

		$this->_add_responsive_control(
			'item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'item_shadow',
				'exclude' => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
			),
			100
		);

		$this->_add_control(
			'item_overlay_heading',
			array(
				'label'     => esc_html__( 'Overlay', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'overlay_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content_wrap'] . ':before',
			),
			25
		);

		$this->_add_responsive_control(
			'overlay_paddings',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_control(
			'item_order_heading',
			array(
				'label'     => esc_html__( 'Order', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			),
			100
		);

		$this->_add_control(
			'item_title_order',
			array(
				'label'   => esc_html__( 'Title Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['title'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'item_content_order',
			array(
				'label'   => esc_html__( 'Content Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 2,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['desc'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_end_controls_section();

		/**
		 * Icon Style Section
		 */
		$this->_start_controls_section(
			'section_images_layout_icon_style',
			array(
				'label'      => esc_html__( 'Icon', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'icon_horizontal_alignment',
			array(
				'label'   => esc_html__( 'Horizontal Alignment', 'jet-elements' ),
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
					'{{WRAPPER}} '. $css_scheme['icon'] => 'justify-content: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_control(
			'icon_vertical_alignment',
			array(
				'label'   => esc_html__( 'Vertical Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Top', 'jet-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Middle', 'jet-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['icon'] => 'align-items: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_control(
			'icon_color',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' svg' => 'fill: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'icon_bg_color',
			array(
				'label' => esc_html__( 'Icon Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-images-layout-icon-inner' => 'background-color: {{VALUE}}',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'icon_font_size',
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
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'icon_size',
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
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-images-layout-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icon_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-images-layout-icon-inner',
			),
			100
		);

		$this->_add_control(
			'icon_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-images-layout-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'icon_box_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-images-layout-icon-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-images-layout-icon-inner',
			),
			100
		);

		$this->_end_controls_section();

		/**
		 * Title Style Section
		 */
		$this->_start_controls_section(
			'section_images_layout_title_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'title_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_add_control(
			'title_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			),
			50
		);

		$this->_add_responsive_control(
			'title_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_end_controls_section();

		/**
		 * Description Style Section
		 */
		$this->_start_controls_section(
			'section_images_layout_desc_style',
			array(
				'label'      => esc_html__( 'Description', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'desc_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_add_control(
			'desc_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
			),
			50
		);

		$this->_add_responsive_control(
			'desc_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'desc_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_end_controls_section();
	}

	/**
	 * Generate setting json
	 *
	 * @return string
	 */
	public function generate_setting_json() {
		$module_settings = $this->get_settings();

		$settings = array(
			'layoutType'    => $module_settings['layout_type'],
			// 'columns'       => $module_settings['columns'],
			// 'columnsTablet' => $module_settings['columns_tablet'],
			// 'columnsMobile' => $module_settings['columns_mobile'],
			'justifyHeight' => $module_settings['justify_height'],
		);

		$settings = json_encode( $settings );

		return sprintf( 'data-settings=\'%1$s\'', $settings );
	}

	/**
	 * Get loop image html
	 *
	 * @return html
	 */
	protected function _loop_image_item() {
		$item = $this->_processed_item;
		$params = [];

		if ( ! array_key_exists( 'item_image', $item ) ) {
			return false;
		}

		$image_item      = $item['item_image'];
		$image_item_size = isset( $item['item_image_size'] ) ? $item['item_image_size'] : 'full';

		if ( ! empty( $image_item['id'] ) ) {
			$image_data = wp_get_attachment_image_src( $image_item['id'], $image_item_size );

			$params[] = $image_data[0];
			$params[] = $image_data[1];
			$params[] = $image_data[2];
		} else {
			$params[] = $image_item['url'];
			$params[] = 1200;
			$params[] = 800;
		}

		$alt = esc_attr( Control_Media::get_image_alt( $image_item ) );

		$settings = $this->get_settings_for_display();

		if ( 'justify' === $settings['layout_type'] ) {
			return sprintf( '<img class="jet-images-layout__image-instance" src="%1$s" data-width="%2$s" data-height="%3$s" alt="%4$s" loading="lazy">', $params[0], $params[1], $params[2], $alt );
		}

		return sprintf( '<img class="jet-images-layout__image-instance" src="%1$s" alt="%2$s" loading="lazy">', $params[0], $alt );
	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	protected function content_template() {}

	public function shuffle_image_list( $list ) {
		shuffle( $list );
		return $list;
	}
}
