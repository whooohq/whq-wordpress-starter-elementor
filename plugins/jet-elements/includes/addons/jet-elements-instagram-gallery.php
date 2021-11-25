<?php
/**
 * Class: Jet_Elements_Instagram_Gallery
 * Name: Instagram
 * Slug: jet-instagram-gallery
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

class Jet_Elements_Instagram_Gallery extends Jet_Elements_Base {

	/**
	 * Instagram API-server URL.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $api_url = 'https://www.instagram.com/';

	/**
	 * New Instagram API-server URL.
	 *
	 * @var string
	 */
	private $new_api_url = 'https://graph.instagram.com/';

	/**
	 * Graph Api Url.
	 *
	 * @var string
	 */
	private $graph_api_url = 'https://graph.facebook.com/';

	/**
	 * Access token.
	 *
	 * @var string
	 */
	private $access_token = null;

	/**
	 * Business account config.
	 *
	 * @var array|null
	 */
	private $business_account_config = null;

	/**
	 * Request config
	 *
	 * @var array
	 */
	public $config = array();

	public function get_name() {
		return 'jet-instagram-gallery';
	}

	public function get_title() {
		return esc_html__( 'Instagram', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-instagram';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-add-an-attractive-instagram-feed-to-the-page-built-with-elementor-using-jetelements-instagram-widget/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-elements/instagram-gallery/css-scheme',
			array(
				'instance'       => '.jet-instagram-gallery__instance',
				'image_instance' => '.jet-instagram-gallery__image',
				'inner'          => '.jet-instagram-gallery__inner',
				'content'        => '.jet-instagram-gallery__content',
				'caption'        => '.jet-instagram-gallery__caption',
				'meta'           => '.jet-instagram-gallery__meta',
				'meta_item'      => '.jet-instagram-gallery__meta-item',
				'meta_icon'      => '.jet-instagram-gallery__meta-icon',
				'meta_label'     => '.jet-instagram-gallery__meta-label',
				'notice'         => '.jet-instagram-gallery__notice',
			)
		);

		$this->start_controls_section(
			'section_instagram_settings',
			array(
				'label' => esc_html__( 'Instagram Settings', 'jet-elements' ),
			)
		);

		$this->add_control(
			'endpoint',
			array(
				'label'   => esc_html__( 'What to display', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hashtag',
				'options' => array(
					'hashtag'  => esc_html__( 'Tagged Photos', 'jet-elements' ),
					'self'     => esc_html__( 'My Photos', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'hashtag',
			array(
				'label'       => esc_html__( 'Hashtag (enter without `#` symbol)', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'condition' => array(
					'endpoint' => 'hashtag',
				),
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
					),
				),
			)
		);

		$this->add_control(
			'use_insta_graph_api',
			array(
				'label'     => esc_html__( 'Use Instagram Graph API', 'jet-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'endpoint' => 'hashtag',
				),
			)
		);

		$business_account_config = $this->get_business_account_config();

		if ( empty( $business_account_config['token'] ) || empty( $business_account_config['user_id'] ) ) {
			$this->add_control(
				'set_business_access_token',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => sprintf(
						esc_html__( 'Please set Business Instagram Access Token and User ID on the %1$s.', 'jet-elements' ),
						'<a target="_blank" href="' . jet_elements_settings()->get_settings_page_link( 'integrations' ) . '">' . esc_html__( 'settings page', 'jet-elements' ) . '</a>'
					),
					'content_classes' => 'elementor-descriptor',
					'condition' => array(
						'endpoint'            => 'hashtag',
						'use_insta_graph_api' => 'yes',
					),
				)
			);
		}

		$this->add_control(
			'order_by',
			array(
				'label'   => esc_html__( 'Order By', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'recent_media',
				'options' => array(
					'recent_media' => esc_html__( 'Recent Media', 'jet-elements' ),
					'top_media'    => esc_html__( 'Top Media', 'jet-elements' ),
				),
				'condition' => array(
					'endpoint'            => 'hashtag',
					'use_insta_graph_api' => 'yes',
				),
			)
		);

		$this->add_control(
			'access_token_source',
			array(
				'label'   => esc_html__( 'Access Token', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					'' => esc_html__( 'Default', 'jet-elements' ),
					'custom'  => esc_html__( 'Custom', 'jet-elements' ),
				),
				'condition' => array(
					'endpoint' => 'self',
				),
			)
		);

		if ( ! $this->get_access_token_from_settings() ) {
			$this->add_control(
				'set_access_token',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => sprintf(
						esc_html__( 'Please set Instagram Access Token on the %1$s.', 'jet-elements' ),
						'<a target="_blank" href="' . jet_elements_settings()->get_settings_page_link( 'integrations' ) . '">' . esc_html__( 'settings page', 'jet-elements' ) . '</a>'
					),
					'content_classes' => 'elementor-descriptor',
					'condition' => array(
						'endpoint' => 'self',
						'access_token_source' => '',
					),
				)
			);
		}

		$this->add_control(
			'custom_access_token',
			array(
				'label'       => esc_html__( 'Custom Access Token', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'condition' => array(
					'endpoint' => 'self',
					'access_token_source' => 'custom',
				),
				'dynamic' => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
					),
				),
			)
		);

		$this->add_control(
			'cache_timeout',
			array(
				'label'   => esc_html__( 'Cache Timeout', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hour',
				'options' => array(
					'none'   => esc_html__( 'None', 'jet-elements' ),
					'minute' => esc_html__( 'Minute', 'jet-elements' ),
					'hour'   => esc_html__( 'Hour', 'jet-elements' ),
					'day'    => esc_html__( 'Day', 'jet-elements' ),
					'week'   => esc_html__( 'Week', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'photo_size',
			array(
				'label'   => esc_html__( 'Photo Size', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'high',
				'options' => array(
					'thumbnail' => esc_html__( 'Thumbnail (150x150)', 'jet-elements' ),
					'low'       => esc_html__( 'Low (320x320)', 'jet-elements' ),
					'standard'  => esc_html__( 'Standard (640x640)', 'jet-elements' ),
					'high'      => esc_html__( 'High (original)', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'posts_counter',
			array(
				'label'   => esc_html__( 'Number of instagram posts', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 50,
				'step'    => 1,
			)
		);

		$this->add_control(
			'post_link',
			array(
				'label'        => esc_html__( 'Enable linking photos', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'post_link_type',
			array(
				'label'   => esc_html__( 'Link type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post-link',
				'options' => array(
					'post-link' => esc_html__( 'Post Link', 'jet-elements' ),
					'lightbox'  => esc_html__( 'Lightbox', 'jet-elements' ),
				),
				'condition' => array(
					'post_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'post_caption',
			array(
				'label'        => esc_html__( 'Enable caption', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'post_caption_length',
			array(
				'label'   => esc_html__( 'Caption length', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 50,
				'min'     => 1,
				'max'     => 300,
				'step'    => 1,
				'condition' => array(
					'post_caption' => 'yes',
				),
			)
		);

		$this->add_control(
			'post_comments_count',
			array(
				'label'        => esc_html__( 'Enable Comments Count', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'endpoint!' => 'self',
				),
			)
		);

		$this->add_control(
			'post_likes_count',
			array(
				'label'        => esc_html__( 'Enable Likes Count', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'endpoint!' => 'self',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Layout Settings', 'jet-elements' ),
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
					'list'    => esc_html__( 'List', 'jet-elements' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'              => esc_html__( 'Columns', 'jet-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 3,
				'options'            => jet_elements_tools()->get_select_range( 6 ),
				'condition'          => array(
					'layout_type' => array( 'masonry', 'grid' ),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
				'selectors'          => array(
					'{{WRAPPER}} .salvattore-column' => 'width: calc(100% / {{VALUE}});',
					'{{WRAPPER}} .jet-instagram-gallery__instance::before' => 'content: "{{VALUE}} .salvattore-column"',
					'{{WRAPPER}} .jet-instagram-gallery__instance.layout-type-grid::before' => 'content: ""',
					'{{WRAPPER}} .jet-instagram-gallery__instance.layout-type-grid .jet-instagram-gallery__item' => 'max-width: calc(100% / {{VALUE}});flex: 0 0 calc(100% / {{VALUE}});',
				),
			)
		);

		$this->add_responsive_control(
			'item_height',
			array(
				'label' => esc_html__( 'Item Height', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
				'default' => [
					'size' => 300,
				],
				'condition' => array(
					'layout_type' => 'grid',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['image_instance'] => 'height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} ' . $css_scheme['inner']    => 'margin: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['notice']   => 'margin: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: -{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'show_on_hover',
			array(
				'label'        => esc_html__( 'Show on hover', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		/**
		 * Item Style Section
		 */
		$this->_start_controls_section(
			'section_general_style',
			array(
				'label'      => esc_html__( 'Item', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'cover_alignment',
			array(
				'label'   => esc_html__( 'Content Vertical Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'flex-start'    => esc_html__( 'Top', 'jet-elements' ),
					'center'        => esc_html__( 'Center', 'jet-elements' ),
					'flex-end'      => esc_html__( 'Bottom', 'jet-elements' ),
					'space-between' => esc_html__( 'Space between', 'jet-elements' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['content'] => 'justify-content: {{VALUE}};',
				),
			),
			25
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
			75
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
			75
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
			25
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'item_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
			),
			25
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
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_2,
						),
					),
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['content'] . ':before',
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
					'{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
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
			'caption_order',
			array(
				'label'   => esc_html__( 'Caption Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 4,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['caption'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'meta_order',
			array(
				'label'   => esc_html__( 'Meta Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 4,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['meta'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_end_controls_section();

		/**
		 * Caption Style Section
		 */
		$this->_start_controls_section(
			'section_caption_style',
			array(
				'label'      => esc_html__( 'Caption', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'caption_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['caption'] => 'align-self: {{VALUE}};',
				),
			)
		);

		$this->_add_responsive_control(
			'caption_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					'{{WRAPPER}} ' . $css_scheme['caption'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			)
		);

		$this->_add_responsive_control(
			'caption_width',
			array(
				'label' => esc_html__( 'Caption Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 1000,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => [
					'size'  => 100,
					'units' => '%'
				],
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['caption'] => 'max-width: {{SIZE}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_control(
			'caption_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['caption'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'caption_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['caption'],
			),
			50
		);

		$this->_add_responsive_control(
			'caption_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['caption'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'caption_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['caption'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		/**
		 * Meta Style Section
		 */
		$this->_start_controls_section(
			'section_meta_style',
			array(
				'label'      => esc_html__( 'Meta', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'meta_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['meta'] => 'align-self: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'comments_icon',
			array(
				'label'       => esc_html__( 'Comments Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-comment',
			),
			50
		);

		$this->_add_control(
			'likes_icon',
			array(
				'label'       => esc_html__( 'Likes Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-heart',
			),
			50
		);

		$this->_add_control(
			'meta_icon_color',
			array(
				'label'  => esc_html__( 'Icon Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['meta_icon'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'meta_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['meta_icon'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			25
		);

		$this->_add_control(
			'meta_label_color',
			array(
				'label'  => esc_html__( 'Text Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['meta_label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['meta_label'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'meta_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['meta'],
			),
			100
		);

		$this->_add_responsive_control(
			'meta_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['meta'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_responsive_control(
			'meta_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['meta'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'meta_item_margin',
			array(
				'label'      => __( 'Item Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['meta_item'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'meta_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['meta'],
			),
			100
		);

		$this->_add_responsive_control(
			'meta_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['meta'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'meta_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['meta'],
			),
			100
		);

		$this->_end_controls_section();

	}

	protected function render() {
		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	/**
	 * Render gallery html.
	 *
	 * @return void
	 */
	public function render_gallery() {
		$settings = $this->get_settings_for_display();

		if ( 'hashtag' === $settings['endpoint'] ) {

			if ( empty( $settings['hashtag'] ) ) {
				$this->print_notice( esc_html__( 'Please, enter #hashtag.', 'jet-elements' ) );
				return;
			}

			if ( ! empty( $settings['use_insta_graph_api'] ) ) {
				$business_account_config = $this->get_business_account_config();

				if ( empty( $business_account_config['token'] || empty( $business_account_config['user_id'] ) ) ) {
					$this->print_notice( esc_html__( 'Please, enter Business Access Token and User ID.', 'jet-elements' ) );
					return;
				}
			}
		}

		if ( 'self' === $settings['endpoint'] && ! $this->get_access_token() ) {
			$this->print_notice( esc_html__( 'Please, enter Access Token.', 'jet-elements' ) );
			return;
		}

		$html = '';
		$col_class = '';

		// Endpoint.
		$endpoint = $this->sanitize_endpoint();

		switch ( $settings['cache_timeout'] ) {
			case 'none':
				$cache_timeout = 1;
				break;

			case 'minute':
				$cache_timeout = MINUTE_IN_SECONDS;
				break;

			case 'hour':
				$cache_timeout = HOUR_IN_SECONDS;
				break;

			case 'day':
				$cache_timeout = DAY_IN_SECONDS;
				break;

			case 'week':
				$cache_timeout = WEEK_IN_SECONDS;
				break;

			default:
				$cache_timeout = HOUR_IN_SECONDS;
				break;
		}

		$this->config = array(
			'endpoint'            => $endpoint,
			'target'              => ( 'hashtag' === $endpoint ) ? sanitize_text_field( $settings[ $endpoint ] ) : 'users',
			'posts_counter'       => $settings['posts_counter'],
			'post_link'           => filter_var( $settings['post_link'], FILTER_VALIDATE_BOOLEAN ),
			'post_link_type'      => $settings['post_link_type'],
			'photo_size'          => $settings['photo_size'],
			'post_caption'        => filter_var( $settings['post_caption'], FILTER_VALIDATE_BOOLEAN ),
			'post_caption_length' => ! empty( $settings['post_caption_length'] ) ? $settings['post_caption_length'] : 50,
			'post_comments_count' => filter_var( $settings['post_comments_count'], FILTER_VALIDATE_BOOLEAN ),
			'post_likes_count'    => filter_var( $settings['post_likes_count'], FILTER_VALIDATE_BOOLEAN ),
			'cache_timeout'       => $cache_timeout,
			'use_graph_api'       => isset( $settings['use_insta_graph_api'] ) ? filter_var( $settings['use_insta_graph_api'], FILTER_VALIDATE_BOOLEAN ) : false,
			'order_by'            => ! empty( $settings['order_by'] ) ? $settings['order_by'] : 'recent_media',
		);

		$posts = $this->get_posts( $this->config );

		if ( ! empty( $posts ) && ! is_wp_error( $posts ) ) {

			foreach ( $posts as $post_data ) {
				$item_html   = '';
				$link        = ( 'hashtag' === $endpoint && ! $this->config['use_graph_api'] ) ? sprintf( $this->get_post_url(), $post_data['link'] ) : $post_data['link'];
				$the_image   = $this->the_image( $post_data );
				$the_caption = $this->the_caption( $post_data );
				$the_meta    = $this->the_meta( $post_data );

				$item_html = sprintf(
					'<div class="jet-instagram-gallery__media">%1$s</div><div class="jet-instagram-gallery__content">%2$s%3$s</div>',
					$the_image,
					$the_caption,
					$the_meta
				);

				if ( $this->config['post_link'] ) {
					$link_format = '<a class="jet-instagram-gallery__link" href="%1$s" target="_blank" rel="nofollow"%3$s>%2$s</a>';
					$link_format = apply_filters( 'jet-elements/instagram-gallery/link-format', $link_format );

					$attr = '';

					if ( 'lightbox' === $this->config['post_link_type'] ) {

						$img_data = $this->get_image_data( $post_data, 'high' );

						$link = $img_data['src'];
						$attr = ' data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . $this->get_id() . '"';
					}

					$item_html = sprintf( $link_format, esc_url( $link ), $item_html, $attr );
				}

				if ( 'grid' === $settings['layout_type'] ) {
					$col_class = jet_elements_tools()->col_classes( array(
						'desk' => $settings['columns'],
						'tab'  => $settings['columns_tablet'],
						'mob'  => $settings['columns_mobile'],
					) );
				}

				$html .= sprintf( '<div class="jet-instagram-gallery__item %s"><div class="jet-instagram-gallery__inner">%s</div></div>', $col_class, $item_html );
			}

		} else {
			$message = is_wp_error( $posts ) ? $posts->get_error_message() : esc_html__( 'Posts not found', 'jet-elements' );

			$html .= sprintf(
				'<div class="jet-instagram-gallery__item"><div class="jet-instagram-gallery__inner">%s</div></div>',
				$message
			);
		}

		echo $html;
	}

	/**
	 * Print widget notice.
	 *
	 * @param $notice
	 */
	public function print_notice( $notice ) {
		if ( ! is_user_logged_in() ) {
			return;
		}

		printf( '<div class="jet-instagram-gallery__notice">%s</div>', $notice );
	}

	/**
	 * Display a HTML link with image.
	 *
	 * @since  1.0.0
	 * @param  array $item Item photo data.
	 * @return string
	 */
	public function the_image( $item ) {

		$size = $this->get_settings_for_display( 'photo_size' );

		$img_data = $this->get_image_data( $item, $size );

		$width          = $img_data['width'];
		$height         = $img_data['height'];
		$post_photo_url = $img_data['src'];

		if ( empty( $post_photo_url ) ) {
			return '';
		}

		$attr = '';

		if ( ! empty( $width ) && ! empty( $height ) ) {
			$attr = ' width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '"';
		}

		$photo_format = '<img class="jet-instagram-gallery__image" src="%1$s"%2$s alt="%3$s" loading="lazy">';
		$photo_format = apply_filters( 'jet-elements/instagram-gallery/photo-format', $photo_format );

		$image = sprintf( $photo_format, esc_url( $post_photo_url ), $attr, esc_attr( $item['caption'] ) );

		return $image;
	}

	/**
	 * Get image data
	 *
	 * @param  array  $item Item photo data.
	 * @param  string $size Image size.
	 * @return array
	 */
	public function get_image_data( $item, $size = 'high' ) {
		$thumbnail_resources = $item['thumbnail_resources'];

		if ( ! empty( $thumbnail_resources[ $size ] ) ) {
			$width = $thumbnail_resources[ $size ]['config_width'];
			$height = $thumbnail_resources[ $size ]['config_height'];
			$post_photo_url = $thumbnail_resources[ $size ]['src'];
		} else {
			$width = isset( $item['dimensions']['width'] ) ? $item['dimensions']['width'] : '';
			$height = isset( $item['dimensions']['height'] ) ? $item['dimensions']['height'] : '';
			$post_photo_url = isset( $item['image'] ) ? $item['image'] : '';
		}

		return array(
			'width'  => $width,
			'height' => $height,
			'src'    => $post_photo_url,
		);
	}

	/**
	 * Display a caption.
	 *
	 * @since  1.0.0
	 * @param  array $item Item photo data.
	 * @return string
	 */
	public function the_caption( $item ) {

		if ( ! $this->config['post_caption'] || empty( $item['caption'] ) ) {
			return;
		}

		$format = apply_filters(
			'jet-elements/instagram-gallery/the-caption-format', '<div class="jet-instagram-gallery__caption">%s</div>'
		);

		return sprintf( $format, $item['caption'] );
	}

	/**
	 * Display a meta.
	 *
	 * @since  1.0.0
	 * @param  array $item Item photo data.
	 * @return string
	 */
	public function the_meta( $item ) {

		if ( ! $this->config['post_comments_count'] && ! $this->config['post_likes_count'] ) {
			return;
		}

		$meta_html = '';

		if ( $this->config['post_comments_count'] ) {
			$meta_html .= sprintf(
				'<div class="jet-instagram-gallery__meta-item jet-instagram-gallery__comments-count"><span class="jet-instagram-gallery__comments-icon jet-instagram-gallery__meta-icon"><i class="%s"></i></span><span class="jet-instagram-gallery__comments-label jet-instagram-gallery__meta-label">%s</span></div>',
				$this->get_settings_for_display( 'comments_icon' ),
				$item['comments']
			);
		}

		if ( $this->config['post_likes_count'] ) {
			$meta_html .= sprintf(
				'<div class="jet-instagram-gallery__meta-item jet-instagram-gallery__likes-count"><span class="jet-instagram-gallery__likes-icon jet-instagram-gallery__meta-icon"><i class="%s"></i></span><span class="jet-instagram-gallery__likes-label jet-instagram-gallery__meta-label">%s</span></div>',
				$this->get_settings_for_display( 'likes_icon' ),
				$item['likes']
			);
		}

		$format = apply_filters( 'jet-elements/instagram-gallery/the-meta-format', '<div class="jet-instagram-gallery__meta">%s</div>' );

		return sprintf( $format, $meta_html );
	}

	/**
	 * Retrieve a photos.
	 *
	 * @since  1.0.0
	 * @param  array $config Set of configuration.
	 * @return array
	 */
	public function get_posts( $config ) {

		$transient_key = md5( $this->get_transient_key() );

		$data = get_transient( $transient_key );

		if ( ! empty( $data ) && 1 !== $config['cache_timeout'] && array_key_exists( 'thumbnail_resources', $data[0] ) ) {
			return $data;
		}

		if ( $config['use_graph_api'] ) {
			$response = $this->remote_get_from_qraph_api( $config );
		} else {
			$response = $this->remote_get( $config );
		}

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( 'hashtag' === $config['endpoint'] && ! $config['use_graph_api'] ) {
			$data = $this->get_response_data( $response );
		} else {
			$data = $this->get_response_data_from_official_api( $response );
		}

		if ( empty( $data ) ) {
			return array();
		}

		set_transient( $transient_key, $data, $config['cache_timeout'] );

		return $data;
	}

	/**
	 * Retrieve the raw response from the HTTP request using the GET method from Graph API.
	 *
	 * @param array $config
	 *
	 * @return mixed|string|void|\WP_Error
	 */
	public function remote_get_from_qraph_api( $config ) {

		$account_config = $this->get_business_account_config();

		$access_token = $account_config['token'];
		$user_id      = $account_config['user_id'];

		$url = add_query_arg(
			array(
				'user_id'      => $user_id,
				'q'            => $config['target'],
				'access_token' => $access_token,
			),
			$this->graph_api_url . 'ig_hashtag_search'
		);

		$response = wp_remote_get( $url, array( 'timeout' => 10 ) );

		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body, true );

		if ( ! is_array( $body ) ) {
			return new \WP_Error( 'invalid-data', esc_html__( 'Invalid data', 'jet-elements' ) );
		}

		if ( isset( $body['error']['message'] ) ) {
			return new \WP_Error( 'invalid-data', $body['error']['message'] );
		}

		if ( empty( $body['data'][0]['id'] ) ) {
			return new \WP_Error( 'invalid-data', esc_html__( 'Can\'t find the tag ID.', 'jet-elements' ) );
		}

		$tag_id = $body['data'][0]['id'];

		$url = add_query_arg(
			array(
				'user_id'      => $user_id,
				'access_token' => $access_token,
				'limit'        => 50,
				'fields'       => 'id,media_type,media_url,caption,comments_count,like_count,permalink,children{media_url,id,media_type,permalink}',
			),
			$this->graph_api_url . $tag_id . '/' . $config['order_by']
		);

		$response = wp_remote_get( $url, array( 'timeout' => 10 ) );

		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body, true );

		if ( ! is_array( $body ) ) {
			return new \WP_Error( 'invalid-data', esc_html__( 'Invalid data', 'jet-elements' ) );
		}

		if ( isset( $body['error']['message'] ) ) {
			return new \WP_Error( 'invalid-data', $body['error']['message'] );
		}

		return $body;
	}

	/**
	 * Retrieve the raw response from the HTTP request using the GET method.
	 *
	 * @since  1.0.0
	 * @return array|object
	 */
	public function remote_get( $config ) {

		$url = $this->get_grab_url( $config );

		$response = wp_remote_get( $url, array(
			'timeout' => 60,
		) );

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {

			$body = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( is_array( $body ) && isset( $body['error']['message'] ) ) {
				$message = $body['error']['message'];
			} else {
				$message = esc_html__( 'Posts not found', 'jet-elements' );
			}

			return new \WP_Error( $response_code, $message );
		}

		$result = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $result ) ) {
			return new \WP_Error( 'invalid-data', esc_html__( 'Invalid data', 'jet-elements' ) );
		}

		return $result;
	}

	/**
	 * Get prepared response data.
	 *
	 * @param $response
	 *
	 * @return array
	 */
	public function get_response_data( $response ) {

		$key = 'hashtag' == $this->config['endpoint'] ? 'hashtag' : 'user';

		if ( 'hashtag' === $key ) {
			$response = isset( $response['graphql'] ) ? $response['graphql'] : $response;
		}

		$response_items = ( 'hashtag' === $key ) ? $response[ $key ]['edge_hashtag_to_media']['edges'] : $response['graphql'][ $key ]['edge_owner_to_timeline_media']['edges'];

		if ( empty( $response_items ) ) {
			return array();
		}

		$data  = array();
		$nodes = array_slice(
			$response_items,
			0,
			$this->config['posts_counter'],
			true
		);

		foreach ( $nodes as $post ) {

			$_post               = array();
			$_post['link']       = $post['node']['shortcode'];
			$_post['image']      = $post['node']['thumbnail_src'];
			$_post['caption']    = isset( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ? wp_html_excerpt( $post['node']['edge_media_to_caption']['edges'][0]['node']['text'], $this->config['post_caption_length'], '&hellip;' ) : '';
			$_post['comments']   = $post['node']['edge_media_to_comment']['count'];
			$_post['likes']      = $post['node']['edge_liked_by']['count'];
			$_post['dimensions'] = $post['node']['dimensions'];
			$_post['thumbnail_resources'] = $this->_generate_thumbnail_resources( $post );

			array_push( $data, $_post );
		}

		return $data;
	}

	/**
	 * Get prepared response data from official api.
	 *
	 * @param $response
	 *
	 * @return array
	 */
	public function get_response_data_from_official_api( $response ) {

		if ( ! isset( $response['data'] ) ) {
			return array();
		}

		$response_items = $response['data'];

		if ( empty( $response_items ) ) {
			return array();
		}

		if ( $this->config['use_graph_api'] ) {
			$response_items = $this->remove_video_items( $response_items );
		}

		$data  = array();
		$nodes = array_slice(
			$response_items,
			0,
			$this->config['posts_counter'],
			true
		);

		foreach ( $nodes as $post ) {

			$media_url = ! empty( $post['media_url'] ) ? $post['media_url'] : '';

			switch ( $post['media_type'] ) {
				case 'VIDEO':
					$media_url = ! empty( $post['thumbnail_url'] ) ? $post['thumbnail_url'] : '';
					break;

				case 'CAROUSEL_ALBUM':
					$media_url = ! empty( $post['children']['data'][0]['media_url'] ) ? $post['children']['data'][0]['media_url'] : $media_url;
					break;
			}

			$_post             = array();
			$_post['link']     = $post['permalink'];
			$_post['image']    = $media_url;
			$_post['caption']  = ! empty( $post['caption'] ) ? wp_html_excerpt( $post['caption'], $this->config['post_caption_length'], '&hellip;' ) : '';
			$_post['comments'] = ! empty( $post['comments_count'] ) ? $post['comments_count'] : 0;           // TODO: available only for Graph Api
			$_post['likes']    = ! empty( $post['like_count'] ) ? $post['like_count'] : 0;                   // TODO: available only for Graph Api
			$_post['thumbnail_resources'] = $this->_generate_thumbnail_resources_from_official_api( $post ); // TODO: this data now not available

			array_push( $data, $_post );
		}

		return $data;
	}

	/**
	 * Remove video items.
	 *
	 * @param  array $data
	 * @return array
	 */
	public function remove_video_items( $data ) {

		$result = array();

		foreach ( $data as $item ) {

			if ( ! empty( $item['media_type'] ) && 'VIDEO' === $item['media_type'] ) {
				continue;
			}

			if ( ! empty( $item['children']['data'] ) ) {
				$item['children']['data'] = $this->remove_video_items( $item['children']['data'] );

				if ( empty( $item['children']['data'] ) ) {
					continue;
				}
			}

			$result[] = $item;
		}

		return $result;
	}

	/**
	 * Generate thumbnail resources.
	 *
	 * @param $post_data
	 *
	 * @return array
	 */
	public function _generate_thumbnail_resources( $post_data ) {
		$post_data = $post_data['node'];

		$thumbnail_resources = array(
			'thumbnail' => false,
			'low'       => false,
			'standard'  => false,
			'high'      => false,
		);

		if ( ! empty( $post_data['thumbnail_resources'] ) && is_array( $post_data['thumbnail_resources'] ) ) {
			foreach ( $post_data['thumbnail_resources'] as $key => $resources_data ) {

				if ( 150 === $resources_data['config_width'] ) {
					$thumbnail_resources['thumbnail'] = $resources_data;

					continue;
				}

				if ( 320 === $resources_data['config_width'] ) {
					$thumbnail_resources['low'] = $resources_data;

					continue;
				}

				if ( 640 === $resources_data['config_width'] ) {
					$thumbnail_resources['standard'] = $resources_data;

					continue;
				}
			}
		}

		if ( ! empty( $post_data['display_url'] ) ) {
			$thumbnail_resources['high'] = array(
				'src'           => $post_data['display_url'],
				'config_width'  => $post_data['dimensions']['width'],
				'config_height' => $post_data['dimensions']['height'],
			) ;
		}

		return $thumbnail_resources;
	}

	/**
	 * Generate thumbnail resources from official api.
	 *
	 * @param $post_data
	 *
	 * @return array
	 */
	public function _generate_thumbnail_resources_from_official_api( $post_data ) {
		$thumbnail_resources = array(
			'thumbnail' => false,
			'low'       => false,
			'standard'  => false,
			'high'      => false,
		);

		if ( ! empty( $post_data['images'] ) && is_array( $post_data['images'] ) ) {

			$thumbnails_data = $post_data['images'];

			$thumbnail_resources['thumbnail'] = array(
				'src'           => $thumbnails_data['thumbnail']['url'],
				'config_width'  => $thumbnails_data['thumbnail']['width'],
				'config_height' => $thumbnails_data['thumbnail']['height'],
			);

			$thumbnail_resources['low'] = array(
				'src'           => $thumbnails_data['low_resolution']['url'],
				'config_width'  => $thumbnails_data['low_resolution']['width'],
				'config_height' => $thumbnails_data['low_resolution']['height'],
			);

			$thumbnail_resources['standard'] = array(
				'src'           => $thumbnails_data['standard_resolution']['url'],
				'config_width'  => $thumbnails_data['standard_resolution']['width'],
				'config_height' => $thumbnails_data['standard_resolution']['height'],
			);

			$thumbnail_resources['high'] = $thumbnail_resources['standard'];
		}

		return $thumbnail_resources;
	}

	/**
	 * Retrieve a grab URL.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_grab_url( $config ) {

		if ( 'hashtag' == $config['endpoint'] ) {
			$url = sprintf( $this->get_tags_url(), $config['target'] );
			$url = add_query_arg( array( '__a' => 1 ), $url );

		} else {
			$url = $this->get_self_url();
			$url = add_query_arg(
				array(
					'fields'       => 'id,media_type,media_url,thumbnail_url,permalink,caption',
					'access_token' => $this->get_access_token(),
				),
				$url
			);
		}

		return $url;
	}

	/**
	 * Retrieve a URL for photos by hashtag.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_tags_url() {
		return apply_filters( 'jet-elements/instagram-gallery/get-tags-url', $this->api_url . 'explore/tags/%s/' );
	}

	/**
	 * Retrieve a URL for self photos.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_self_url() {
		return apply_filters( 'jet-elements/instagram-gallery/get-self-url', $this->new_api_url . 'me/media/' );
	}

	/**
	 * Retrieve a URL for post.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_post_url() {
		return apply_filters( 'jet-elements/instagram-gallery/get-post-url', $this->api_url . 'p/%s/' );
	}

	/**
	 * sanitize endpoint.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function sanitize_endpoint() {
		return in_array( $this->get_settings( 'endpoint' ) , array( 'hashtag', 'self' ) ) ? $this->get_settings( 'endpoint' ) : 'hashtag';
	}

	/**
	 * Retrieve a photo sizes (in px) by option name.
	 *
	 * @since  1.0.0
	 * @param  string $photo_size Photo size.
	 * @return array
	 */
	public function _get_relation_photo_size( $photo_size ) {
		switch ( $photo_size ) {

			case 'high':
				$size = array();
				break;

			case 'standard':
				$size = array( 640, 640 );
				break;

			case 'low':
				$size = array( 320, 320 );
				break;

			default:
				$size = array( 150, 150 );
				break;
		}

		return apply_filters( 'jet-elements/instagram-gallery/relation-photo-size', $size, $photo_size );
	}

	/**
	 * Get transient key.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_transient_key() {
		return sprintf( 'jet_elements_instagram_%s_%s%s_posts_count_%s_caption_%s',
			$this->config['endpoint'],
			$this->config['target'],
			$this->config['use_graph_api'] ? '_order_' . $this->config['order_by'] : '',
			$this->config['posts_counter'],
			$this->config['post_caption_length']
		);
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
			'columns'       => $module_settings['columns'],
			'columnsTablet' => $module_settings['columns_tablet'],
			'columnsMobile' => $module_settings['columns_mobile'],
		);

		$settings = json_encode( $settings );

		return sprintf( 'data-settings=\'%1$s\'', $settings );
	}

	/**
	 * Get access token.
	 *
	 * @return string
	 */
	public function get_access_token() {
		if ( ! $this->access_token ) {
			$source = $this->get_settings( 'access_token_source' );

			if ( 'custom' === $source ) {
				$this->access_token = $this->get_settings( 'custom_access_token' );
			} else {
				$this->access_token = jet_elements_settings()->get( 'insta_access_token' );
			}
		}

		return $this->access_token;
	}

	/**
	 * Get business account config.
	 *
	 * @return array
	 */
	public function get_business_account_config() {
		if ( ! $this->business_account_config ) {
			$this->business_account_config['token']   = jet_elements_settings()->get( 'insta_business_access_token' );
			$this->business_account_config['user_id'] = jet_elements_settings()->get( 'insta_business_user_id' );
		}

		return $this->business_account_config;
	}

	/**
	 * Get access token from the plugin settings.
	 *
	 * @return string
	 */
	public function get_access_token_from_settings() {
		return jet_elements_settings()->get( 'insta_access_token' );
	}

}
