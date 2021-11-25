<?php
/**
 * Class: Jet_Elements_Team_Member
 * Name: Team Member
 * Slug: jet-team-member
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

class Jet_Elements_Team_Member extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-team-member';
	}

	public function get_title() {
		return esc_html__( 'Team Member', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-team-member';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetelements-team-member-widget-how-to-add-a-team-member-widget-to-your-website/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/team-member/css-scheme',
			array(
				'instance'         => '.jet-team-member',
				'instance_inner'   => '.jet-team-member__inner',
				'image'            => '.jet-team-member__image',
				'cover'            => '.jet-team-member__cover',
				'figure'           => '.jet-team-member__figure',
				'content'          => '.jet-team-member__content',
				'name'             => '.jet-team-member__name',
				'position'         => '.jet-team-member__position',
				'desc'             => '.jet-team-member__desc',
				'socials'          => '.jet-team-member__socials',
				'socials_item'     => '.jet-team-member__socials-item',
				'socials_icon'     => '.jet-team-member__socials-icon',
				'socials_label'    => '.jet-team-member__socials-label',
				'button_container' => '.jet-team-member__button-container',
				'button'           => '.jet-team-member__button',
			)
		);

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'jet-elements' ),
			)
		);

		$this->add_control(
			'member_image',
			array(
				'label'   => esc_html__( 'Member Image', 'jet-elements' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'member_first_name',
			array(
				'label'   => esc_html__( 'First Name', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'John', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'member_last_name',
			array(
				'label'   => esc_html__( 'Last Name', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Borthwick', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'member_name_html_tag',
			array(
				'label'   => esc_html__( 'Name HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'default' => 'h3',
			)
		);

		$this->add_control(
			'member_position',
			array(
				'label'   => esc_html_x( 'Position', 'Position at work', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html_x( 'Position', 'Position at work', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'member_description',
			array(
				'label'   => esc_html__( 'Description', 'jet-elements' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Team member personal information', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater = new Repeater();

		$this->_add_advanced_icon_control(
			'social_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => '',
			),
			$repeater
		);

		$repeater->add_control(
			'social_label',
			array(
				'label' => esc_html__( 'Label', 'jet-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Label', 'jet-elements' ),
			)
		);

		$repeater->add_control(
			'label_visible',
			array(
				'label'        => esc_html__( 'Label visible', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$repeater->add_control(
			'social_link',
			array(
				'label' => esc_html__( 'Link', 'jet-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '#', 'jet-elements' ),
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
			'social_link_target',
			array(
				'label'     => esc_html__( 'Open link in new window', 'jet-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'social_link!' => '',
				),
			)
		);

		$repeater->add_control(
			'social_link_rel',
			array(
				'label'     => esc_html__( 'Add nofollow', 'jet-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'social_link!' => '',
				),
			)
		);

		$this->add_control(
			'social_list',
			array(
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array(
						'social_icon'   => 'fa fa-facebook',
						'social_label'  => esc_html__( 'Facebook', 'jet-elements' ),
						'social_link'   => '#',
						'label_visible' => 'false',
					),
					array(
						'social_icon'   => 'fa fa-google-plus',
						'social_label'  => esc_html__( 'google+', 'jet-elements' ),
						'social_link'   => '#',
						'label_visible' => 'false',
					),
					array(
						'social_icon'   => 'fa fa-twitter',
						'social_label'  => esc_html__( 'Twitter', 'jet-elements' ),
						'social_link'   => '#',
						'label_visible' => 'false',
					),
				),
				'title_field' => '{{{ social_label }}}',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'More', 'jet-elements' ),
			)
		);

		$this->add_control(
			'button_url',
			array(
				'label'       => esc_html__( 'Button Link', 'jet-elements' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default' => array(
					'url' => '',
				),
				'dynamic' => array( 'active' => true ),
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
			'show_on_hover',
			array(
				'label'        => esc_html__( 'Overlay show on hover', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'cover_like_hint',
			array(
				'label'        => esc_html__( 'Overlay like a hint', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'use_hint_corner',
			array(
				'label'        => esc_html__( 'Use hint corner', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
				'condition' => array(
					'cover_like_hint' => 'yes',
				),
			)
		);

		$this->add_control(
			'name_cover_location',
			array(
				'label'        => esc_html__( 'Name display in cover', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'position_cover_location',
			array(
				'label'        => esc_html__( 'Position display in cover', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'desc_cover_location',
			array(
				'label'        => esc_html__( 'Description display in cover', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'social_list_cover_location',
			array(
				'label'        => esc_html__( 'Social display in cover', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'button_cover_location',
			array(
				'label'        => esc_html__( 'Action Button display in cover', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->end_controls_section();

		/**
		 * General Style Section
		 */
		$this->_start_controls_section(
			'section_team_member_general_style',
			array(
				'label'      => esc_html__( 'General', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'cover_height',
			array(
				'label'      => esc_html__( 'Overlay Height', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 800,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cover'] => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'cover_like_hint' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'hint_corner_color',
			array(
				'label'   => esc_html__( 'Overlay Corner Color', 'jet-elements' ),
				'type'    => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cover'] . ':after' => 'border-color: {{VALUE}} transparent transparent transparent;',
				),
				'condition' => array(
					'cover_like_hint' => 'yes',
					'use_hint_corner' => 'yes',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance_inner'],
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'container_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['instance_inner'],
			),
			100
		);

		$this->_add_responsive_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
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
					'{{WRAPPER}} ' . $css_scheme['instance_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} ' . $css_scheme['instance_inner'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'container_box_shadow',
				'exclude' => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['instance_inner'],
			),
			100
		);

		$this->_end_controls_section();

		/**
		 * Image Style Section
		 */
		$this->_start_controls_section(
			'section_team_member_image_style',
			array(
				'label'      => esc_html__( 'Image', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'custom_image_size',
			array(
				'label'        => esc_html__( 'Custom size', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			),
			25
		);

		$this->_add_responsive_control(
			'image_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 800,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image'] => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'custom_image_size' => 'yes',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'image_height',
			array(
				'label'      => esc_html__( 'Height', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 800,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image'] => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'custom_image_size' => 'yes',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'image_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['image'],
			),
			75
		);

		$this->_add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['figure'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'image_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'image_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['image'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'image_box_shadow',
				'exclude' => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['image'],
			),
			100
		);

		$this->_end_controls_section();

		/**
		 * Name Style Section
		 */
		$this->_start_controls_section(
			'section_team_member_name_style',
			array(
				'label'      => esc_html__( 'Name', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_start_controls_tabs( 'tabs_name_style' );

		$this->_start_controls_tab(
			'tab_first_name_style',
			array(
				'label' => esc_html__( 'First name', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'first_name_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['name'] . ' .jet-team-member__name-first' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'first_name_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['name'] . ' .jet-team-member__name-first',
			),
			50
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_last_name_style',
			array(
				'label' => esc_html__( 'Last name', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'last_name_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['name'] . ' .jet-team-member__name-last' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'last_name_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['name'] . ' .jet-team-member__name-last',
			),
			50
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_responsive_control(
			'name_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['name'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			75
		);

		$this->_add_responsive_control(
			'name_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['name'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'name_text_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['name'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_end_controls_section();

		/**
		 * Position Style Section
		 */
		$this->_start_controls_section(
			'section_team_member_position_style',
			array(
				'label'      => esc_html_x( 'Position', 'Position at work', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'position_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['position'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'position_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['position'],
			),
			50
		);

		$this->_add_responsive_control(
			'position_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['position'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'position_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['position'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'position_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['position'] => 'align-self: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'position_text_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['position'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_end_controls_section();

		/**
		 * Description Style Section
		 */
		$this->_start_controls_section(
			'section_team_member_desc_style',
			array(
				'label'      => esc_html__( 'Description', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
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
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
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
			75
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
			25
		);

		$this->_add_responsive_control(
			'desc_alignment',
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
			50
		);

		$this->_add_responsive_control(
			'desc_text_alignment',
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
			50
		);

		$this->_end_controls_section();

		/**
		 * Social List Style Section
		 */
		$this->_start_controls_section(
			'section_social_list_style',
			array(
				'label'      => esc_html__( 'Social', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'social_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['socials'] => 'align-self: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_control(
			'social_items_spacing',
			array(
				'label' => esc_html__( 'Items Spacing', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['socials_item'] . ':not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['socials_item'] . ':not(:last-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_control(
			'social_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_start_controls_tabs( 'tabs_social_icon_style' );

		$this->_start_controls_tab(
			'tab_social_icon_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'social_icon_color',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ' .jet-elements-icon' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'social_icon_bg_color',
			array(
				'label' => esc_html__( 'Icon Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ' .inner' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'social_icon_font_size',
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
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ' .jet-elements-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'social_icon_size',
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
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ' .inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'social_icon_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['socials_icon'] . ' .inner',
			),
			75
		);

		$this->_add_control(
			'social_icon_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ' .inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'social_icon_box_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ' .inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'social_icon_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['socials_icon'] . ' .inner',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_social_icon_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'social_icon_color_hover',
			array(
				'label' => esc_html__( 'Icon Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ':hover .jet-elements-icon' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'social_icon_bg_color_hover',
			array(
				'label' => esc_html__( 'Icon Background Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ':hover .inner' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'social_icon_font_size_hover',
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
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ':hover .jet-elements-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'social_icon_size_hover',
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
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ':hover .inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'social_icon_border_hover',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['socials_icon'] . ':hover .inner',
			),
			75
		);

		$this->_add_control(
			'social_icon_box_border_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ':hover .inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'social_icon_box_margin_hover',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['socials_icon'] . ':hover .inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'social_icon_box_shadow_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['socials_icon'] . ':hover .inner',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_control(
			'social_label_heading',
			array(
				'label'     => esc_html__( 'Label', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'social_label_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['socials_label'],
			),
			50
		);

		$this->_add_control(
			'social_label_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['socials_label']  => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'social_label_hover_color',
			array(
				'label' => esc_html__( 'Hover Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['socials_item'] . ' a:hover ' . $css_scheme['socials_label']  => 'color: {{VALUE}};',
				),
			),
			25
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
			)
		);

		$this->_add_responsive_control(
			'button_alignment',
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
					'{{WRAPPER}} ' . $css_scheme['button_container'] => 'justify-content: {{VALUE}};',
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
			'button_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
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

		$this->_add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
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
					'{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
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
			'primary_button_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_hover_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button'] . ':hover',
			),
			75
		);

		$this->_add_responsive_control(
			'button_hover_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * Overlay Style Section
		 */
		$this->_start_controls_section(
			'section_team_member_overlay_style',
			array(
				'label'      => esc_html__( 'Overlay', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'overlay_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['cover'] . ':before',
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
					'{{WRAPPER}} ' . $css_scheme['cover'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'overlay_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cover'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; width: calc( 100% - {{LEFT}}{{UNIT}} - {{RIGHT}}{{UNIT}} );',
					'{{WRAPPER}} ' . $css_scheme['instance'] . ':not(.jet-team-member--cover-hint) ' . $css_scheme['cover'] => 'height: calc( 100% - {{TOP}}{{UNIT}} - {{BOTTOM}}{{UNIT}} );',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'overlay_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cover'] . ':before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_end_controls_section();

		/**
		 * Order Style Section
		 */
		$this->_start_controls_section(
			'section_order_style',
			array(
				'label'      => esc_html__( 'Content Order and Alignment', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			),
			50
		);

		$this->_add_control(
			'name_order',
			array(
				'label'   => esc_html__( 'Name Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 1,
				'max'     => 5,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['name'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'position_order',
			array(
				'label'   => esc_html__( 'Position Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 5,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['position'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'desc_order',
			array(
				'label'   => esc_html__( 'Description Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
				'min'     => 1,
				'max'     => 5,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['desc'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'social_order',
			array(
				'label'   => esc_html__( 'Social Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 5,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['socials'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'button_order',
			array(
				'label'   => esc_html__( 'Button Order', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5,
				'min'     => 1,
				'max'     => 5,
				'step'    => 1,
				'selectors' => array(
					'{{WRAPPER}} '. $css_scheme['button_container'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'cover_alignment',
			array(
				'label'   => esc_html__( 'Cover Content Vertical Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'flex-start'    => esc_html__( 'Top', 'jet-elements' ),
					'center'        => esc_html__( 'Center', 'jet-elements' ),
					'flex-end'      => esc_html__( 'Bottom', 'jet-elements' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} '. $css_scheme['cover']  => 'justify-content: {{VALUE}};',
				),
			),
			50
		);

		$this->_end_controls_section( 50 );

	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	public function _get_member_image() {

		$image = $this->get_settings_for_display( 'member_image' );

		if ( empty( $image['id'] ) && empty( $image['url'] ) ) {
			return;
		}

		$format = apply_filters( 'jet-elements/team-member/image-format', '<figure class="jet-team-member__figure"><img class="jet-team-member__img-tag" src="%1$s" alt="%2$s" loading="lazy"></figure>' );
		$alt    = esc_attr( Control_Media::get_image_alt( $image ) );

		return sprintf( $format, $image['url'], $alt );
	}

	public function _generate_name( $cover_location = false ) {
		$first_name = $this->get_settings_for_display( 'member_first_name' );
		$last_name  = $this->get_settings_for_display( 'member_last_name' );
		$is_cover   = filter_var( $this->get_settings_for_display( 'name_cover_location' ), FILTER_VALIDATE_BOOLEAN );

		$first_name_html = '';
		$last_name_html = '';

		if ( ( $cover_location && ! $is_cover ) || ( ! $cover_location && $is_cover ) ) {
			return;
		}

		if ( empty( $first_name ) && empty( $last_name ) ) {
			return;
		}

		if ( ! empty( $first_name ) ) {
			$first_name_html = sprintf( '<span class="jet-team-member__name-first">%s</span>', $first_name );
		}

		if ( ! empty( $last_name ) ) {

			$last_name_html = sprintf( '<span class="jet-team-member__name-last"> %s</span>', $last_name );
		}

		$name_tag = $this->get_settings_for_display( 'member_name_html_tag' );
		$name_tag = jet_elements_tools()->validate_html_tag( $name_tag );

		$format = apply_filters( 'jet-elements/team-member/name-format', '<%3$s class="jet-team-member__name">%1$s%2$s</%3$s>' );

		return sprintf( $format, $first_name_html, $last_name_html, $name_tag );

	}

	public function _generate_position( $cover_location = false ) {
		$position = $this->get_settings_for_display( 'member_position' );
		$is_cover = filter_var( $this->get_settings_for_display( 'position_cover_location' ), FILTER_VALIDATE_BOOLEAN );

		if ( ( $cover_location && ! $is_cover ) || ( ! $cover_location && $is_cover ) ) {
			return;
		}

		if ( empty( $position ) ) {
			return false;
		}

		$format = apply_filters( 'jet-elements/team-member/position-format', '<div class="jet-team-member__position"><span>%1$s</span></div>' );

		return sprintf( $format, $position );
	}

	public function _generate_description( $cover_location = false ) {
		$desc = $this->get_settings_for_display( 'member_description' );
		$is_cover = filter_var( $this->get_settings_for_display( 'desc_cover_location' ), FILTER_VALIDATE_BOOLEAN );

		if ( ( $cover_location && ! $is_cover ) || ( ! $cover_location && $is_cover ) ) {
			return;
		}

		if ( empty( $desc ) ) {
			return false;
		}

		$format = apply_filters( 'jet-elements/team-member/description-format', '<p class="jet-team-member__desc">%s</p>' );

		return sprintf( $format, $desc );
	}

	public function _generate_action_button( $cover_location = false ) {
		$button_url = $this->get_settings_for_display( 'button_url' );
		$button_text = $this->get_settings_for_display( 'button_text' );
		$is_cover = filter_var( $this->get_settings_for_display( 'button_cover_location' ), FILTER_VALIDATE_BOOLEAN );

		if ( ( $cover_location && ! $is_cover ) || ( ! $cover_location && $is_cover ) ) {
			return;
		}

		if ( empty( $button_url ) ) {
			return false;
		}

		if ( is_array( $button_url ) && empty( $button_url['url'] ) ) {
			return false;
		}

		$this->add_render_attribute( 'url', 'class', array(
			'elementor-button',
			'elementor-size-md',
			'jet-team-member__button',
		) );

		if ( is_array( $button_url ) ) {
			if ( method_exists( $this, 'add_link_attributes' ) ) {
				$this->add_link_attributes( 'url', $button_url );
			} else {
				$this->add_render_attribute( 'url', 'href', $button_url['url'] );

				if ( $button_url['is_external'] ) {
					$this->add_render_attribute( 'url', 'target', '_blank' );
				}

				if ( ! empty( $button_url['nofollow'] ) ) {
					$this->add_render_attribute( 'url', 'rel', 'nofollow' );
				}
			}
		} else {
			$this->add_render_attribute( 'url', 'href', $button_url );
		}

		$format = apply_filters( 'jet-elements/team-member/description-format', '<div class="jet-team-member__button-container"><a %1$s>%2$s</a></div>' );

		return sprintf( $format, $this->get_render_attribute_string( 'url' ), $button_text );
	}

	public function _generate_social_icon_list( $cover_location = false ) {
		$social_icon_list = $this->get_settings_for_display( 'social_list' );
		$is_cover = filter_var( $this->get_settings_for_display( 'social_list_cover_location' ), FILTER_VALIDATE_BOOLEAN );

		if ( ( $cover_location && ! $is_cover ) || ( ! $cover_location && $is_cover ) ) {
			return;
		}

		if ( empty( $social_icon_list ) ) {
			return false;
		}

		$icon_list = '';

		foreach ( $social_icon_list as $key => $icon_data ) {

			$this->_processed_item = $icon_data;

			$label = '';

			if ( ! empty( $icon_data[ 'social_link' ] ) ) {

				$this->add_render_attribute( 'social-link-' . $icon_data['_id'], 'href', esc_url( $icon_data[ 'social_link' ] ) );

				if ( ! empty( $icon_data[ 'social_link_target' ] ) ) {
					$this->add_render_attribute( 'social-link-' . $icon_data['_id'], 'target', '_blank' );
				}

				if ( ! empty( $icon_data[ 'social_link_rel' ] ) ) {
					$this->add_render_attribute( 'social-link-' . $icon_data['_id'], 'rel', 'nofollow' );
				}

				$icon = $this->_get_icon( 'social_icon', '<div class="jet-team-member__socials-icon"><div class="inner"><span class="jet-elements-icon">%s</span></div></div>' );

				if ( filter_var( $icon_data['label_visible'], FILTER_VALIDATE_BOOLEAN ) ) {
					$label = sprintf( '<span class="jet-team-member__socials-label">%s</span>', $icon_data[ 'social_label' ] );
				}

				$icon_list .= sprintf(
					'<div class="jet-team-member__socials-item"><a %1$s>%2$s%3$s</a></div>',
					$this->get_render_attribute_string( 'social-link-' . $icon_data['_id'] ), $icon, $label
				);
			}
		}

		$this->_processed_item = false;

		$format = apply_filters( 'jet-elements/team-member/social-list-format', '<div class="jet-team-member__socials">%1$s</div>' );

		return sprintf( $format, $icon_list );
	}

}
