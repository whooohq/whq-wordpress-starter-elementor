<?php
/**
 * Class: Jet_Elements_Slider
 * Name: Slider
 * Slug: jet-slider
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

class Jet_Elements_Slider extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-slider';
	}

	public function get_title() {
		return esc_html__( 'Slider', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-slider';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-create-a-slider-with-the-jetelements-slider-widget-for-elementor/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'imagesloaded', 'jet-slider-pro' );
	}

	public function get_style_depends() {
		return array( 'jet-slider-pro-css' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-elements/slider/css-scheme',
			array(
				'instance'            => '.jet-slider',
				'content_wrapper'     => '.jet-slider__content',
				'content_item'        => '.jet-slider__content-item',
				'content_inner'       => '.jet-slider__content-inner',
				'instance_slider'     => '.jet-slider .slider-pro',
				'navigation'          => '.jet-slider .sp-arrows',
				'pagination'          => '.jet-slider .sp-buttons',
				'icon'                => '.jet-slider__icon',
				'title'               => '.jet-slider__title',
				'subtitle'            => '.jet-slider__subtitle',
				'desc'                => '.jet-slider__desc',
				'buttons_wrapper'     => '.jet-slider__button-wrapper',
				'primary_button'      => '.jet-slider__button--primary',
				'secondary_button'    => '.jet-slider__button--secondary',
				'overlay'             => '.jet-slider .sp-image-container:after',
				'fullscreen'          => '.jet-slider .sp-full-screen-button',
				'thumbnails'          => '.jet-slider .sp-thumbnails-container',
				'thumbnail_container' => '.jet-slider .sp-thumbnail-container',
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

		$this->_add_advanced_icon_control(
			'item_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'condition'   => array(
					'item_content_type' => 'default',
				),
			),
			$repeater
		);

		$repeater->add_control(
			'item_title',
			array(
				'label'     => esc_html__( 'Title', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'item_content_type' => 'default',
				),
			)
		);

		$repeater->add_control(
			'item_title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'default' => 'h5',
				'condition' => array(
					'item_content_type' => 'default',
				),
			)
		);

		$repeater->add_control(
			'item_subtitle',
			array(
				'label'     => esc_html__( 'Subtitle', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'item_content_type' => 'default',
				),
			)
		);

		$repeater->add_control(
			'item_subtitle_html_tag',
			array(
				'label'   => esc_html__( 'Subtitle HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'default' => 'h5',
				'condition' => array(
					'item_content_type' => 'default',
				),
			)
		);

		$repeater->add_control(
			'item_desc',
			array(
				'label'     => esc_html__( 'Description', 'jet-elements' ),
				'type'      => Controls_Manager::TEXTAREA,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'item_content_type' => 'default',
				),
			)
		);

		$repeater->add_control(
			'item_link',
			array(
				'label'        => esc_html__( 'Link on whole slide', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$repeater->add_control(
			'item_link_url',
			array(
				'label'   => esc_html__( 'Slide Link', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
				'condition' => array(
					'item_link' => 'true',
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
					'item_link' => 'true',
				),
			)
		);

		$repeater->add_control(
			'item_button_primary_url',
			array(
				'label'   => esc_html__( 'Primary Button URL', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
				'condition' => array(
					'item_content_type' => 'default',
					'item_link!'        => 'true',
				),
			)
		);

		$repeater->add_control(
			'item_button_primary_target',
			array(
				'label'        => esc_html__( 'Open link in new window', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '_blank',
				'condition'    => array(
					'item_content_type'        => 'default',
					'item_button_primary_url!' => '',
					'item_link!'               => 'true',
				),
			)
		);

		$repeater->add_control(
			'item_button_primary_rel',
			array(
				'label'        => esc_html__( 'Add nofollow', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'nofollow',
				'condition'    => array(
					'item_content_type'        => 'default',
					'item_button_primary_url!' => '',
					'item_link!'               => 'true',
				),
			)
		);

		$repeater->add_control(
			'item_button_primary_text',
			array(
				'label'     => esc_html__( 'Primary Button Text', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'More', 'jet-elements' ),
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'item_content_type' => 'default',
					'item_link!'        => 'true',
				),
			)
		);

		$repeater->add_control(
			'item_button_secondary_url',
			array(
				'label'   => esc_html__( 'Secondary Button URL', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
				'condition' => array(
					'item_content_type' => 'default',
					'item_link!'        => 'true',
				),
			)
		);

		$repeater->add_control(
			'item_button_secondary_target',
			array(
				'label'        => esc_html__( 'Open link in new window', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '_blank',
				'condition'    => array(
					'item_content_type'          => 'default',
					'item_button_secondary_url!' => '',
					'item_link!'                 => 'true',
				),
			)
		);

		$repeater->add_control(
			'item_button_secondary_rel',
			array(
				'label'        => esc_html__( 'Add nofollow', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'nofollow',
				'condition'    => array(
					'item_content_type'          => 'default',
					'item_button_secondary_url!' => '',
					'item_link!'                 => 'true',
				),
			)
		);

		$repeater->add_control(
			'item_button_secondary_text',
			array(
				'label'     => esc_html__( 'Secondary Button Text', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'More', 'jet-elements' ),
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'item_content_type' => 'default',
					'item_link!'        => 'true',
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

		$repeater->add_control(
			'slide_id',
			array(
				'label'   => esc_html__( 'Slide CSS ID', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'item_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'item_image'                 => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'                 => esc_html__( 'Slide #1', 'jet-elements' ),
						'item_subtitle'              => esc_html__( 'SubTitle', 'jet-elements' ),
						'item_desc'                  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
						'item_button_primary_url'    => '#',
						'item_button_primary_text'   => esc_html__( 'Button #1', 'jet-elements' ),
						'item_button_secondary_ulr'  => '#',
						'item_button_secondary_text' => esc_html__( 'Button #2', 'jet-elements' ),
						),
					array(
						'item_image'                 => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'                 => esc_html__( 'Slide #2', 'jet-elements' ),
						'item_subtitle'              => esc_html__( 'SubTitle', 'jet-elements' ),
						'item_desc'                  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
						'item_button_primary_url'    => '#',
						'item_button_primary_text'   => esc_html__( 'Button #1', 'jet-elements' ),
						'item_button_secondary_ulr'  => '#',
						'item_button_secondary_text' => esc_html__( 'Button #2', 'jet-elements' ),
					),
					array(
						'item_image'                 => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_title'                 => esc_html__( 'Slide #3', 'jet-elements' ),
						'item_subtitle'              => esc_html__( 'SubTitle', 'jet-elements' ),
						'item_desc'                  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'jet-elements' ),
						'item_button_primary_url'    => '#',
						'item_button_primary_text'   => esc_html__( 'Button #1', 'jet-elements' ),
						'item_button_secondary_ulr'  => '#',
						'item_button_secondary_text' => esc_html__( 'Button #2', 'jet-elements' ),
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
			'slider_width',
			array(
				'label' => esc_html__( 'Slider Width(%)', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'%' => array(
						'min' => 50,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => '%',
					'size' => 100,
				),
			)
		);

		$this->add_responsive_control(
			'slider_height',
			array(
				'label' => esc_html__( 'Slider Height(px)', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'vh',
				),
				'range' => array(
					'px' => array(
						'min' => 300,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 600,
				),
				'selectors' => array(
					'{{WRAPPER}} .slider-pro' => 'min-height: {{SIZE}}{{UNIT}}'
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_responsive_control(
			'slider_container_width',
			array(
				'label' => esc_html__( 'Slider Container Width(%)', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'%', 'px',
				),
				'range' => array(
					'%' => array(
						'min' => 20,
						'max' => 100,
					),
					'px' => array(
						'min' => 200,
						'max' => 1000,
					),
				),
				'default' => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance_slider'] . ' .jet-slider__content-inner' => 'max-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['pagination'] => 'max-width: {{SIZE}}{{UNIT}}',
				),
			)
		);


		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'slider_image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'large',
			)
		);

		$this->add_control(
			'slide_image_scale_mode',
			array(
				'label'   => esc_html__( 'Image Scale Mode', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'exact',
				'options' => array(
					'exact'   => esc_html__( 'Cover', 'jet-elements' ),
					'contain' => esc_html__( 'Contain', 'jet-elements' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'slider_background',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						),
					),
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance_slider'] . ' .jet-slider__item',
				'condition' => array(
					'slide_image_scale_mode' => 'contain',
				),
			)
		);

		$this->add_control(
			'content_show_transition',
			array(
				'label'   => esc_html__( 'Content Motion Effect', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'up',
				'options' => array(
					'none'  => esc_html__( 'None', 'jet-elements' ),
					'up'    => esc_html__( 'Up', 'jet-elements' ),
					'down'  => esc_html__( 'Down', 'jet-elements' ),
					'left'  => esc_html__( 'Left', 'jet-elements' ),
					'right' => esc_html__( 'Right', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'slider_navigation',
			array(
				'label'        => esc_html__( 'Use navigation?', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'slider_navigation_on_hover',
			array(
				'label'        => esc_html__( 'Indicates whether the arrows will fade in only on hover', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'false',
				'condition' => array(
					'slider_navigation' => 'true',
				),
			)
		);

		$this->_add_advanced_icon_control(
			'slider_navigation_icon_arrow',
			array(
				'label'       => esc_html__( 'Arrow Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'default'     => 'fa fa-angle-left',
				'fa5_default' => array(
					'value'   => 'fas fa-angle-left',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'slider_navigation' => 'true',
				),
			)
		);

		$this->add_control(
			'slider_pagination',
			array(
				'label'        => esc_html__( 'Use pagination?', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'fraction_pagination',
			array(
				'label'        => esc_html__( 'Use Fraction pagination?', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'slider_autoplay',
			array(
				'label'        => esc_html__( 'Use autoplay?', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'slider_autoplay_delay',
			array(
				'label'   => esc_html__( 'Autoplay delay(ms)', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5000,
				'min'     => 2000,
				'max'     => 10000,
				'step'    => 100,
				'condition' => array(
					'slider_autoplay' => 'true',
				),
			)
		);

		$this->add_control(
			'slide_autoplay_on_hover',
			array(
				'label'   => esc_html__( 'Autoplay On Hover', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'pause',
				'options' => array(
					'none'  => esc_html__( 'None', 'jet-elements' ),
					'pause' => esc_html__( 'Pause', 'jet-elements' ),
					'stop'  => esc_html__( 'Stop', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'slider_fullScreen',
			array(
				'label'        => esc_html__( 'Display fullScreen button?', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'slider_touchswipe',
			array(
				'label'        => esc_html__( 'Touch Swipe Effect', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->_add_advanced_icon_control(
			'slider_fullscreen_icon',
			array(
				'label'       => esc_html__( 'FullScreen Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'default'     => 'fa fa-arrows-alt',
				'fa5_default' => array(
					'value'   => 'fas fa-arrows-alt',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'slider_fullScreen' => 'true',
				),
			)
		);

		$this->add_control(
			'slider_shuffle',
			array(
				'label'        => esc_html__( 'Indicates if the slides will be shuffled', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'slider_loop',
			array(
				'label'        => esc_html__( 'Indicates if the slides will be looped', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'slider_fade_mode',
			array(
				'label'        => esc_html__( 'Use fade effect?', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'slide_distance',
			array(
				'label' => esc_html__( ' Between Slides Distance', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 10,
				),
			)
		);

		$this->add_control(
			'slide_duration',
			array(
				'label'   => esc_html__( 'Slide Duration(ms)', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 500,
				'min'     => 100,
				'max'     => 5000,
				'step'    => 100,
			)
		);

		$this->add_control(
			'thumbnails',
			array(
				'label'        => esc_html__( 'Display thumbnails?', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_responsive_control(
			'thumbnail_width',
			array(
				'label'   => esc_html__( 'Thumbnail width(px)', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 120,
				'min'     => 20,
				'max'     => 500,
				'step'    => 1,
				'condition' => array(
					'thumbnails' => 'true',
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_responsive_control(
			'thumbnail_height',
			array(
				'label'   => esc_html__( 'Thumbnail height(px)', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 80,
				'min'     => 20,
				'max'     => 200,
				'step'    => 1,
				'condition' => array(
					'thumbnails' => 'true',
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->end_controls_section();

		/**
		 * General Style Section
		 */
		$this->_start_controls_section(
			'section_slider_general_style',
			array(
				'label'      => esc_html__( 'General', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'overlay_heading',
			array(
				'label'     => esc_html__( 'Overlay', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'overlay_background',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						),
					),
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['overlay'],
			),
			50
		);

		$this->_add_control(
			'overlay_opacity',
			array(
				'label'    => esc_html__( 'Opacity', 'jet-elements' ),
				'type'     => Controls_Manager::NUMBER,
				'default'  => 0.2,
				'min'      => 0,
				'max'      => 1,
				'step'     => 0.1,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['overlay'] => 'opacity: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_control(
			'widget_container_heading',
			array(
				'label'     => esc_html__( 'Widget Container', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			100
		);

		$this->_add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'container_margin',
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
				'name'        => 'container_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			100
		);

		$this->_add_control(
			'container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
			),
			100
		);

		$this->_add_control(
			'fullscreen_heading',
			array(
				'label'     => esc_html__( 'Fullscreen', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'fullscreen_icon_color',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['fullscreen'] . ' i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['fullscreen'] . ' svg' => 'fill: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'fullscreen_icon_bg_color',
			array(
				'label' => esc_html__( 'Icon Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['fullscreen'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'fullscreen_icon_font_size',
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
					'{{WRAPPER}} ' . $css_scheme['fullscreen'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['fullscreen'] => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'fullscreen_icon_size',
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
					'{{WRAPPER}} ' . $css_scheme['fullscreen'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'fullscreen_icon_box_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['fullscreen'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'fullscreen_icon_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['fullscreen'],
			),
			75
		);

		$this->_add_control(
			'fullscreen_icon_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['fullscreen'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'fullscreen_icon_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['fullscreen'],
			),
			100
		);

		$this->_end_controls_section();

		/**
		 * Content Slider Section
		 */
		$this->_start_controls_section(
			'section_content_slider_style',
			array(
				'label'      => esc_html__( 'Content Wrapper', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'slider_content_horizontal_alignment',
			array(
				'label'   => esc_html__( 'Horizontal Align', 'jet-elements' ),
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
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['content_item'] => 'justify-content: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'slider_content_vertical_alignment',
			array(
				'label'   => esc_html__( 'Vertical Align', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
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
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['content_wrapper'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'slider_content_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content_inner'],
			),
			50
		);

		$this->_add_responsive_control(
			'slider_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'slider_content_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content_inner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'slider_content_border',
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['content_inner'],
			),
			75
		);

		$this->_add_control(
			'slider_content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'slider_content_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content_inner'],
			),
			100
		);

		$this->_end_controls_section();

		/**
		 * Icon Style Section
		 */
		$this->_start_controls_section(
			'section_slider_icon_style',
			array(
				'label'      => esc_html__( 'Icon', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'icon_box_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'justify-content: {{VALUE}};',
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
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-slider-icon-inner' => 'background-color: {{VALUE}}',
				),
			),
			50
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
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-slider-icon-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-slider-icon-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icon_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-slider-icon-inner',
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
					'{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-slider-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['icon'] . ' .jet-slider-icon-inner',
			),
			100
		);

		$this->_end_controls_section();

		/**
		 * Title Style Section
		 */
		$this->_start_controls_section(
			'section_slider_title_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'slider_title_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->_add_control(
			'slider_title_color',
			array(
				'label'  => esc_html__( 'Title Color', 'jet-elements' ),
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
				'name'     => 'slider_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			),
			50
		);

		$this->_add_responsive_control(
			'slider_title_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'slider_title_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		/**
		 * SubTitle Style Section
		 */
		$this->_start_controls_section(
			'section_slider_subtitle_style',
			array(
				'label'      => esc_html__( 'Subtitle', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'slider_subtitle_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['subtitle'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->_add_control(
			'slider_subtitle_color',
			array(
				'label'  => esc_html__( 'Subtitle Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['subtitle'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'slider_subtitle_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['subtitle'],
			),
			50
		);

		$this->_add_responsive_control(
			'slider_subtitle_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['subtitle'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'slider_subtitle_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['subtitle'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		/**
		 * Desc Style Section
		 */
		$this->_start_controls_section(
			'section_slider_desc_style',
			array(
				'label'      => esc_html__( 'Description', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'slider_desc_wax_width',
			array(
				'label' => esc_html__( 'Max Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'%' => array(
						'min' => 20,
						'max' => 100,
					),
					'px' => array(
						'min' => 300,
						'max' => 1000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'max-width: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'slider_desc_container_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'align-self: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'slider_desc_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->_add_control(
			'slider_desc_color',
			array(
				'label'  => esc_html__( 'Description Color', 'jet-elements' ),
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
				'name'     => 'slider_desc_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
			),
			50
		);

		$this->_add_responsive_control(
			'slider_desc_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'slider_desc_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		/**
		 * Action Button #1 Style Section
		 */
		$this->_start_controls_section(
			'section_action_button_style',
			array(
				'label'      => esc_html__( 'Action Button', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			),
			25
		);

		$this->_add_responsive_control(
			'slider_action_button_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['buttons_wrapper'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->_add_control(
			'section_action_primary_button_heading',
			array(
				'label'     => esc_html__( 'Action Button #1', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_responsive_control(
			'primary_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['primary_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'primary_button_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['primary_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_start_controls_tabs( 'tabs_primary_button_style' );

		$this->_start_controls_tab(
			'tab_primary_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'primary_button_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['primary_button'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'primary_button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['primary_button'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'primary_button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['primary_button'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'primary_button_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['primary_button'],
			),
			50
		);

		$this->_add_responsive_control(
			'primary_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['primary_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'primary_button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['primary_button'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_primary_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'primary_button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'primary_button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'primary_button_hover_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['primary_button'] . ':hover',
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'primary_button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover',
			),
			50
		);

		$this->_add_responsive_control(
			'primary_button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'primary_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['primary_button'] . ':hover',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_control(
			'section_action_secondary_button_heading',
			array(
				'label'     => esc_html__( 'Action Button #2', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_responsive_control(
			'secondary_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['secondary_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'secondary_button_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['secondary_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_start_controls_tabs( 'tabs_secondary_button_style' );

		$this->_start_controls_tab(
			'tab_secondary_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'secondary_button_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['secondary_button'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'secondary_button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['secondary_button'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'secondary_button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['secondary_button'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'secondary_button_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['secondary_button'],
			),
			50
		);

		$this->_add_responsive_control(
			'secondary_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['secondary_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'secondary_button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['secondary_button'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_secondary_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'secondary_button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'secondary_button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'secondary_button_hover_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['secondary_button'] . ':hover',
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'secondary_button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover',
			),
			50
		);

		$this->_add_responsive_control(
			'secondary_button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'secondary_button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['secondary_button'] . ':hover',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * Navigation Style Section
		 */
		$this->_start_controls_section(
			'section_slider_navigation_style',
			array(
				'label'      => esc_html__( 'Navigation', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'normal_navigation_size',
			array(
				'label'      => esc_html__( 'Box Size', 'jet-elements' ),
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
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'normal_navigation_box_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['navigation']. ' .sp-arrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_start_controls_tabs( 'navigation_style_tabs' );

		$this->_start_controls_tab(
			'tab_normal_navigation_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'normal_navigation_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow svg' => 'fill: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'normal_navigation_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'normal_navigation_font_size',
			array(
				'label'      => esc_html__( 'Font Size', 'jet-elements' ),
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
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'normal_navigation_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '0px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow',
			),
			50
		);

		$this->_add_control(
			'normal_navigation_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'normal_navigation_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_hover_navigation_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'hover_navigation_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover svg' => 'fill: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'hover_navigation_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'hover_navigation_font_size',
			array(
				'label'      => esc_html__( 'Font Size', 'jet-elements' ),
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
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'hover_navigation_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '0px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover',
			),
			50
		);

		$this->_add_control(
			'hover_navigation_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hover_navigation_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['navigation'] . ' .sp-arrow:hover',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * Pagination Style Section
		 */
		$this->_start_controls_section(
			'section_pagination_style',
			array(
				'label'      => esc_html__( 'Pagination', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'pagination_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left' => array(
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
					'{{WRAPPER}} ' . $css_scheme['pagination'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->_add_responsive_control(
			'pagination_container_offset',
			array(
				'label'   => esc_html__( 'Pagination Container Offset', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => -500,
				'max'     => 500,
				'step'    => 1,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['pagination'] => 'margin-top: {{VALUE}}px;',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'pagination_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['pagination'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'pagination_dots_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['pagination'] . ' .sp-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_start_controls_tabs( 'tabs_dots_style' );

		$this->_start_controls_tab(
			'tab_pagination_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'pagination_style',
				'label'          => esc_html__( 'Dots Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} ' . $css_scheme['pagination'] . ' .sp-button',
				'fields_options' => array(
					'color' => array(
						'default' => '#fff',
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
			'tab_pagination_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'pagination_style_hover',
				'label'          => esc_html__( 'Dots Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} ' . $css_scheme['pagination'] . ' .sp-button:hover',
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
			'tab_pagination_active',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'pagination_style_active',
				'label'          => esc_html__( 'Dots Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} ' . $css_scheme['pagination'] . ' .sp-button.sp-selected-button',
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

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * Thumbnails Style Section
		 */
		$this->_start_controls_section(
			'section_thumbnails_style',
			array(
				'label'      => esc_html__( 'Thumbnails', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'thumbnail_item_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnail_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'   => 'before',
				'render_type' => 'template',
			),
			50
		);

		$this->_add_control(
			'thumbnails_container_offset',
			array(
				'label'   => esc_html__( 'Thumbnails Container Offset', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => -500,
				'max'     => 500,
				'step'    => 1,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnails'] => 'margin-top: {{VALUE}}px;',
				),
			),
			25
		);

		$this->_start_controls_tabs( 'tabs_thumbnails_style' );

		$this->_start_controls_tab(
			'tab_thumbnails_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'thumbnails_normal_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . ':before',
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'thumbnails_normal_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . ':before',
				'fields_options' => array(
					'border' => array(
						'default' => '',
					),
					'width' => array(
						'default' => array(
							'top'      => '2',
							'right'    => '2',
							'bottom'   => '2',
							'left'     => '2',
							'isLinked' => true,
						),
					),
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
			'tab_thumbnails_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'thumbnails_hover_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . ':hover:before',
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'thumbnails_hover_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '2px',
				'default'     => '2px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . ':hover:before',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width' => array(
						'default' => array(
							'top'      => '2',
							'right'    => '2',
							'bottom'   => '2',
							'left'     => '2',
							'isLinked' => true,
						),
					),
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

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_thumbnails_active',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'thumbnails_active_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . '.sp-selected-thumbnail:before',
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'thumbnails_active_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '2px',
				'default'     => '2px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['thumbnail_container'] . '.sp-selected-thumbnail:before',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width' => array(
						'default' => array(
							'top'      => '2',
							'right'    => '2',
							'bottom'   => '2',
							'left'     => '2',
							'isLinked' => true,
						),
					),
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

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_fraction_style',
			array(
				'label'      => esc_html__( 'Fraction Pagination', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'fraction_pagination' => 'true'
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
					'{{WRAPPER}} .jet-slider__fraction-pagination .current' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .jet-slider__fraction-pagination .current' => 'background-color: {{VALUE}};',
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
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} .jet-slider__fraction-pagination .current' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .jet-slider__fraction-pagination .total' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .jet-slider__fraction-pagination .total' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .jet-slider .jet-slider__fraction-pagination .total' => 'border-color: {{VALUE}};',
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
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} .jet-slider__fraction-pagination .total' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .jet-slider__fraction-pagination .separator' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .jet-slider__fraction-pagination' => 'justify-content: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fraction_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .jet-slider__fraction-pagination span',
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
					'{{WRAPPER}} .jet-slider__fraction-pagination .separator' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .jet-slider__fraction-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'fraction_border',
				'selector' => '{{WRAPPER}} .jet-slider__fraction-pagination span:not(.separator)',
			)
		);

		$this->_add_control(
			'fraction_border_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'jet-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors' => array(
					'{{WRAPPER}} .jet-slider__fraction-pagination .current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .jet-slider__fraction-pagination .total' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
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
		$widget_id = $this->get_id();

		$settings = array(
			'sliderWidth'           => $module_settings['slider_width'],
			'sliderHeight'          => $module_settings['slider_height'],
			// 'sliderHeightTablet'    => $module_settings['slider_height_tablet'] || '',
			// 'sliderHeightMobile'    => $module_settings['slider_height_mobile'] || '',
			'sliderNavigation'      => filter_var( $module_settings['slider_navigation'], FILTER_VALIDATE_BOOLEAN ),
			'sliderNavigationIcon'  => 'jet-slider__arrow-icon-' . $widget_id,
			'sliderNaviOnHover'     => filter_var( $module_settings['slider_navigation_on_hover'], FILTER_VALIDATE_BOOLEAN ),
			'sliderPagination'      => filter_var( $module_settings['slider_pagination'], FILTER_VALIDATE_BOOLEAN ),
			'sliderAutoplay'        => filter_var( $module_settings['slider_autoplay'], FILTER_VALIDATE_BOOLEAN ),
			'sliderAutoplayDelay'   => $module_settings['slider_autoplay_delay'],
			'sliderAutoplayOnHover' => $module_settings['slide_autoplay_on_hover'],
			'sliderFullScreen'      => filter_var( $module_settings['slider_fullScreen'], FILTER_VALIDATE_BOOLEAN ),
			'sliderFullscreenIcon'  => 'jet-slider__fullscreen-icon-' . $widget_id,
			'sliderShuffle'         => filter_var( $module_settings['slider_shuffle'], FILTER_VALIDATE_BOOLEAN ),
			'sliderLoop'            => filter_var( $module_settings['slider_loop'], FILTER_VALIDATE_BOOLEAN ),
			'sliderFadeMode'        => filter_var( $module_settings['slider_fade_mode'], FILTER_VALIDATE_BOOLEAN ),
			'slideDistance'         => $module_settings['slide_distance'],
			'slideDuration'         => $module_settings['slide_duration'],
			'imageScaleMode'        => $module_settings['slide_image_scale_mode'],
			'thumbnails'            => filter_var( $module_settings['thumbnails'], FILTER_VALIDATE_BOOLEAN ),
			'thumbnailWidth'        => $module_settings['thumbnail_width'],
			// 'thumbnailWidthTablet'  => $module_settings['thumbnail_width_tablet'] || '',
			// 'thumbnailWidthMobile'  => $module_settings['thumbnail_width_mobile'] || '',
			'thumbnailHeight'       => $module_settings['thumbnail_height'],
			// 'thumbnailHeightTablet' => $module_settings['thumbnail_height_tablet'] || '',
			// 'thumbnailHeightMobile' => $module_settings['thumbnail_height_mobile'] || '',
			'rightToLeft'           => is_rtl(),
			'touchswipe'            => filter_var( $module_settings['slider_touchswipe'], FILTER_VALIDATE_BOOLEAN ),
			'fractionPag'           => filter_var( $module_settings['fraction_pagination'], FILTER_VALIDATE_BOOLEAN ),
		);

		$settings = json_encode( $settings );

		return sprintf( 'data-settings=\'%1$s\'', $settings );
	}

	/**
	 * [__loop_button_item description]
	 * @param  array  $keys   [description]
	 * @param  string $format [description]
	 * @return [type]         [description]
	 */
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
	 * [__loop_item_image_tag description]
	 * @return [type] [description]
	 */
	protected function _loop_item_image_tag() {
		$item  = $this->_processed_item;
		$image = $item['item_image'];

		if ( empty( $image['id'] ) && empty( $image['url'] ) ) {
			return false;
		}

		$format = '<img class="sp-image" src="%1$s" alt="%2$s">';

		if ( empty( $image['id'] ) ) {
			return sprintf( $format, Utils::get_placeholder_image_src(), '' );
		}

		$image['id'] = apply_filters( 'wpml_object_id', $image['id'], 'attachment', true );

		$src = Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'slider_image', $this->get_settings() );
		$alt = Control_Media::get_image_alt( $image );

		return sprintf( $format, esc_url( $src ), esc_attr( $alt ) );
	}

	protected function _loop_item_image_thumb() {
		$item  = $this->_processed_item;
		$image = $item['item_image'];

		if ( empty( $image['id'] ) && empty( $image['url'] ) ) {
			return false;
		}

		$format = '<img class="sp-thumbnail" src="%1$s" alt="%2$s">';

		if ( empty( $image['id'] ) ) {
			return sprintf( $format, Utils::get_placeholder_image_src(), '' );
		}

		$image['id'] = apply_filters( 'wpml_object_id', $image['id'], 'attachment', true );

		$src = wp_get_attachment_image_url( $image['id'], 'thumbnail' );
		$alt = Control_Media::get_image_alt( $image );

		return sprintf( $format, esc_url( $src ), esc_attr( $alt ) );
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
				'<a class="jet-elements-edit-template-link" href="%s" title="%s" target="_blank"><i class="dashicons dashicons-edit"></i></a>',
				esc_url( $edit_url ),
				esc_html__( 'Edit Template', 'jet-elements' )
			);

			$content .= $edit_link;
		}

		return $content;
	}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	protected function content_template() {}
}
