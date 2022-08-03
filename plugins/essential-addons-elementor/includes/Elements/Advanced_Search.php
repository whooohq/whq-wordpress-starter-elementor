<?php

namespace Essential_Addons_Elementor\Pro\Elements;

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Utils;
use \Elementor\Widget_Base;

class Advanced_Search extends Widget_Base {

	public function get_name() {
		return 'eael-advanced-search';
	}

	public function get_title() {
		return esc_html__( 'Advanced Search', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-advanced-search';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_style_depends() {
		return [
			'font-awesome-5-all',
			'font-awesome-4-shim',
		];
	}

	public function get_keywords() {
		return [
			'search',
			'ea adv search',
			'ea search',
			'ea',
			'essential addons'
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/advanced-search/';
	}

	protected function register_controls() {


		$this->search_setting();
		$this->search_field_content();
		$this->search_result_content();

		//STYLE SECTION
		$this->search_box();
		$this->search_field();
		$this->category_field();
		$this->search_button();
		$this->search_result();
		$this->popular_search_style();
		$this->category_search();
		$this->content_style();
		$this->load_more_style();
		$this->not_found_style();
	}

	public function search_setting() {
		$this->start_controls_section(
			'eael_section_tooltip_settings',
			[
				'label' => esc_html__( 'Search Settings', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->add_control(
			'eael_adv_search_post_list',
			[
				'label'       => esc_html__( 'Select Post Type', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->get_post_type_list()
			]
		);

		$this->add_control(
			'show_initial_result',
			[
				'label'   => esc_html__( 'Show Initial Result ', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'default' => '5',
			]
		);

		$this->add_control(
			'show_category_list',
			[
				'label'        => esc_html__( 'Show Category List', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

        $this->add_control(
            'use_include_exclude_in_result',
            [
                'label'        => esc_html__( 'Use Include & Exclude for Result', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'   => [
                    'show_category_list' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'include_category_list',
            [
                'label'       => esc_html__( 'Include Category To List', 'essential-addons-for-elementor-lite' ),
                'type'        => 'eael-select2',
                'source_name' => 'taxonomy',
                'source_type' => 'all',
                'label_block' => true,
                'multiple'    => true,
                'condition'   => [
                    'show_category_list' => 'yes'
                ],
            ]
        );
        $this->add_control(
            'exclude_category_list',
            [
                'label'       => esc_html__( 'Exclude Category From List', 'essential-addons-for-elementor-lite' ),
                'type'        => 'eael-select2',
                'source_name' => 'taxonomy',
                'source_type' => 'all',
                'label_block' => true,
                'multiple'    => true,
                'condition'   => [
                    'show_category_list' => 'yes'
                ],
            ]
        );

		$this->add_control(
			'show_category_search_result',
			[
				'label'        => esc_html__( 'Show Category Result', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'show_popular_keyword',
			[
				'label'        => esc_html__( 'Show Popular Keywords', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'total_number_of_popular_search',
			[
				'label'     => esc_html__( 'Popular Keywords', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'default'   => 5,
                'description' => esc_html__( 'Number of popular searches to display.',  'essential-addons-for-elementor-lite' ),
				'condition' => [
					'show_popular_keyword' => 'yes',
				]
			]
		);

		$this->add_control(
			'show_popular_keyword_rank',
			[
				'label'       => esc_html__( 'Keywords Search', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Minimum number of searches for a keyword to be considered a popular search.', 'essential-addons-for-elementor-lite' ),
				'default'     => 5,
				'min'         => 1,
				'condition'   => [
					'show_popular_keyword' => 'yes',
				]
			]
		);

		$this->add_control(
			'show_content_image',
			[
				'label'        => esc_html__( 'Show Content Image', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();
	}

	public function search_field_content() {
		$this->start_controls_section(
			'search_field_text',
			[
				'label' => esc_html__( 'Search Field', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'search_field_placeholder_text',
			[
				'label'   => esc_html__( 'Placeholder Text', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Enter Search Keyword', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->add_control(
			'category_list_text',
			[
				'label'   => esc_html__( 'Category List Text', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'All Categories', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->add_control(
			'search_field_button_text',
			[
				'label'   => esc_html__( 'Button Text', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->end_controls_section();
	}

	public function search_result_content() {
		$this->start_controls_section(
			'search_result_text',
			[
				'label' => esc_html__( 'Search Result', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'popular_search_text',
			[
				'label'   => esc_html__( 'Popular Search Text', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Popular Keywords', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->add_control(
			'category_search_text',
			[
				'label'   => esc_html__( 'Category Search Text', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Categories', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->add_control(
			'load_more_text',
			[
				'label'   => esc_html__( 'Load More Text', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'View All Results', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->add_control(
			'not_found_text',
			[
				'label'   => esc_html__( 'Not Found Text', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'No Record Found', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->end_controls_section();
	}


	public function search_box() {
		$this->start_controls_section(
			'search_box_style',
			[
				'label' => esc_html__( 'Search Box', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'search_box_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-adv-search-wrapper'
			]
		);

		$this->add_responsive_control(
			'search_box_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-adv-search-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'search_box_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-adv-search-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'search_box_border',
				'label'    => esc_html__( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-adv-search-wrapper',
			]
		);
		$this->add_responsive_control(
			'search_box_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-adv-search-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'search_box_shadow',
				'selector'  => '{{WRAPPER}} .eael-adv-search-wrapper',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	public function search_field() {
		$this->start_controls_section(
			'search_field_style',
			[
				'label' => esc_html__( 'Search Field', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'eael_adv_search_style',
			[
				'label'   => esc_html__( 'Layout Style', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 1,
				'options' => [
					'1' => __( 'Style 1', 'essential-addons-for-elementor-lite' ),
					'2' => __( 'Style 2', 'essential-addons-for-elementor-lite' ),
					'3' => __( 'Style 3', 'essential-addons-for-elementor-lite' ),
				]
			]
		);

		$this->add_control(
			'search_field_bg',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_field_text_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_field_placeholder_text_color',
			[
				'label'     => esc_html__( 'Placeholder Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_field_text_typography',
				'selector' => '{{WRAPPER}} .eael-advanced-search,{{WRAPPER}} .eael-advanced-search::placeholder'
			]
		);

		$this->add_responsive_control(
			'field_search_field_height',
			[
				'label'      => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'max' => 1000,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-wrap'   => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-advanced-search-button' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'field_search_field_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
                    'px' => [
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
				],
				'desktop_default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_field_padding_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'search_field_border',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-advanced-search-wrap .eael-advanced-search',
			]
		);

		$this->add_control(
			'search_icon',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Icon', 'essential-addons-for-elementor-lite' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_icon_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-form .eael-advanced-search-wrap .icon i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_icon_size',
			[
				'label'      => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'max' => 1000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-form .eael-advanced-search-wrap .icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function category_field() {
		$this->start_controls_section(
			'category_field_style',
			[
				'label'     => esc_html__( 'Category List', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_category_list' => 'yes',
				]
			]
		);

		$this->add_control(
			'category_field_bg',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-adv-search-cate' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'category_field_text_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-adv-search-cate' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'category_field_text_typography',
				'selector' => '{{WRAPPER}} .eael-adv-search-cate'
			]
		);

		$this->add_responsive_control(
			'category_field_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'max' => 1000,
					],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
				],
                'desktop_default'    => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'mobile_default'    => [
                    'unit' => '%',
                    'size' => 100,
                ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advance-search-select' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'category_field_padding_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advance-search-select .eael-adv-search-cate' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'category_field_border',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} select.eael-adv-search-cate',
			]
		);

		$this->end_controls_section();
	}

	public function search_button() {
		$this->start_controls_section(
			'search_button_style',
			[
				'label' => esc_html__( 'Search Button', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'search_button_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
                    'px' => [
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
				],
                'default'    => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'mobile_default'    => [
                    'unit' => '%',
                    'size' => 100,
                ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'eael_serach_button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'search_button_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-advanced-search-button'
			]
		);

		$this->add_control(
			'search_button_text_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'search_button_hover_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-advanced-search-button:hover'
			]
		);

		$this->add_control(
			'search_button_hover_text_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_button_text_typography',
				'selector' => '{{WRAPPER}} .eael-advanced-search-button'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'search_button_border',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-advanced-search-button',
			]
		);

		$this->add_responsive_control(
			'search_button_padding_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function search_result() {
		$this->start_controls_section(
			'search_result_style',
			[
				'label' => esc_html__( 'Search Result Box', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'search_result_bg',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-result' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'search_result_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'px' => [
						'max' => 100
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-widget .eael-advanced-search-result' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'search_result_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-result' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'search_result_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-result' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'search_result_padding_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-result' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'search_result_border',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-advanced-search-result',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_result_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-advanced-search-result',
			]
		);

		$this->end_controls_section();
	}

	public function popular_search_style() {
		$this->start_controls_section(
			'popular_keyword_style',
			[
				'label'     => esc_html__( 'Popular Keywords', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_popular_keyword' => 'yes',
				]
			]
		);

		$this->add_control(
			'popular_search_label',
			[
				'label' => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'popular_search_text_label_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-popular-keyword h4' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'popular_search_label_typography',
				'selector' => '{{WRAPPER}} .eael-advanced-search-popular-keyword h4'
			]
		);

		$this->add_control(
			'popular_search_tag',
			[
				'label' => esc_html__( 'Popular Tag', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->start_controls_tabs( 'popular_search_tag_normal' );

		$this->start_controls_tab( 'popular_search_tag_normal_tab', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'popular_search_bg',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-popular-keyword-content .eael-popular-keyword-item' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'popular_search_text_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-popular-keyword-content .eael-popular-keyword-item' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'popular_search_key_typography',
				'selector' => '{{WRAPPER}} .eael-popular-keyword-content .eael-popular-keyword-item'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'popular_search_tag_hover_tab', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'popular_search_bg_hover',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-popular-keyword-content .eael-popular-keyword-item:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'popular_search_text_color_hover',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-popular-keyword-content .eael-popular-keyword-item:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'popular_search_key_typography_hover',
				'selector' => '{{WRAPPER}} .eael-popular-keyword-content .eael-popular-keyword-item:hover'
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();


		$this->add_responsive_control(
			'popular_search_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-popular-keyword-content .eael-popular-keyword-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'popular_search_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-popular-keyword-content .eael-popular-keyword-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'popular_search_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-popular-keyword-content .eael-popular-keyword-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'popular_search_border',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-popular-keyword-content .eael-popular-keyword-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'popular_search_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-popular-keyword-content .eael-popular-keyword-item',
			]
		);

		$this->end_controls_section();
	}

	public function category_search() {
		$this->start_controls_section(
			'category_style',
			[
				'label'     => esc_html__( 'Category Result', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_category_search_result' => 'yes',
				]
			]
		);

		$this->add_control(
			'category_label',
			[
				'label' => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'category_label_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-category h4' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'category_label_typography',
				'selector' => '{{WRAPPER}} .eael-advanced-search-category h4'
			]
		);

		$this->add_control(
			'category_list',
			[
				'label' => esc_html__( 'Category', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);


		$this->start_controls_tabs( 'eael_category_normal' );

		$this->start_controls_tab( 'category_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'category_text_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-popular-category-content ul li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'category_list_typography',
				'selector' => '{{WRAPPER}} .eael-popular-category-content ul li a'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'category_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'category_text_color_hover',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-popular-category-content ul li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'category_list_typography_hover',
				'selector' => '{{WRAPPER}} .eael-popular-category-content ul li a:hover'
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'category_list_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-popular-category-content ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'category_list_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-popular-category-content ul li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'category_list_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-popular-category-content ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'category_list_border',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-popular-category-content ul li a',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'category_list_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-popular-category-content ul li a',
			]
		);

		$this->end_controls_section();
	}

	public function content_style() {
		$this->start_controls_section(
			'content_style',
			[
				'label' => esc_html__( 'Search Content', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'eael_adv_search_result',
			[
				'label'   => esc_html__( 'Content Layout', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 1,
				'options' => [
					'1' => __( 'Style 1', 'essential-addons-for-elementor-lite' ),
					'2' => __( 'Style 2', 'essential-addons-for-elementor-lite' ),
					'3' => __( 'Style 3', 'essential-addons-for-elementor-lite' ),
				]
			]
		);

		$this->start_controls_tabs( 'search_result_tab' );

		$this->start_controls_tab( 'search_result_tab_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'search_result_content_list_bg',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item' => 'background: {{VALUE}};',
				],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'search_result_tab_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'search_result_content_list_bg_hover',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'search_result_content_list_bg_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'search_result_content_list_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'search_result_content_list_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'search_result_content_list_border',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'search_result_content_list_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item',
			]
		);

		$this->add_responsive_control(
			'category_list_title',
			[
				'label'     => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'search_result_list_title_normal' );

		$this->start_controls_tab( 'search_title_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'search_result_list_title_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item .item-content h4' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_result_list_title_typography',
				'selector' => '{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item .item-content h4'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'search_title_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'search_result_list_title_color_hover',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item:hover .item-content h4' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_result_list_title_typography_hover',
				'selector' => '{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item:hover .item-content h4'
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'search_result_list_content',
			[
				'label'     => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'search_result_list_content_tab' );

		$this->start_controls_tab( 'csearch_result_list_content_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'search_result_list_content_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item .item-content p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_result_list_content_typography',
				'selector' => '{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item .item-content p'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'search_result_list_content_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'search_result_list_content_color_hover',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item:hover .item-content p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'search_result_list_content_typography_hover',
				'selector' => '{{WRAPPER}} .eael-advanced-search-content .eael-advanced-search-content-item:hover .item-content p'
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function load_more_style() {
		$this->start_controls_section(
			'load_more_style',
			[
				'label' => esc_html__( 'Load More', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'load_more_button_normal' );

		$this->start_controls_tab( 'load_more_button_normal_tab', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'load_more_button_text_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-advanced-search-load-more .eael-advanced-search-load-more-button'
			]
		);

		$this->add_control(
			'load_more_button_text_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-load-more .eael-advanced-search-load-more-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'load_more_typography',
				'selector' => '{{WRAPPER}} .eael-advanced-search-load-more .eael-advanced-search-load-more-button'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'load_more_button_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'load_more_button_text_bg_hover',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-advanced-search-load-more .eael-advanced-search-load-more-button:hover'
			]
		);

		$this->add_control(
			'load_more_button_text_color_hover',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-load-more .eael-advanced-search-load-more-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'load_more_typography_hover',
				'selector' => '{{WRAPPER}} .eael-advanced-search-load-more .eael-advanced-search-load-more-button:hover'
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'load_more_button_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-load-more .eael-advanced-search-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'load_more_button_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-load-more .eael-advanced-search-load-more-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'load_more_button_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-advanced-search-load-more .eael-advanced-search-load-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'load_more_button_border',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-advanced-search-load-more .eael-advanced-search-load-more-button',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'load_more_button_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-advanced-search-load-more .eael-advanced-search-load-more-button',
			]
		);

		$this->end_controls_section();
	}

	public function not_found_style() {
		$this->start_controls_section(
			'not_found_style',
			[
				'label' => esc_html__( 'Not Found Message', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'not_found_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-not-found p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'not_found_typography',
				'selector' => '{{WRAPPER}} .eael-advanced-search-not-found p'
			]
		);

		$this->add_responsive_control(
			'not_found_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					]
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .eael-advanced-search-not-found p' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function get_post_type_list() {

		$list = get_transient( 'eael_adv_search_post_list' );
		if ( $list && is_array( $list ) ) {
			return $list;
		}
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		if ( !empty( $post_types ) ) {
			foreach ( $post_types as $post_type ) {
				$list[ $post_type->name ] = $post_type->labels->name;
			}
			set_transient( 'eael_adv_search_post_list', $list, 30 * MINUTE_IN_SECONDS );
		}
		return $list;
	}

	public function get_post_term_list( $post_list = [] ) {
        $settings = $this->get_settings_for_display();

		$args = [
			'hide_empty' => true
		];
		$list = [];
		if ( !empty( $post_list ) && is_array( $post_list ) ) {
			$taxonomies = get_object_taxonomies( $post_list );
			if ( empty( $taxonomies ) ) {
				return [];
			}
			$args[ 'taxonomy' ] = $taxonomies;
		}

        if ( isset( $settings['exclude_category_list'] ) && !empty( $settings['exclude_category_list'] ) ){
            $args['exclude'] = $settings['exclude_category_list'];
        }
        if ( isset( $settings['include_category_list'] ) && !empty( $settings['include_category_list'] ) ){
            $args['include'] = $settings['include_category_list'];
        }

		$terms = get_terms( $args );
		if ( !empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$list[ $term->term_id ] = $term->name;
			}
		}
		return $list;
	}


	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = [
			'post_per_page'        => $settings[ 'show_initial_result' ],
			'show_popular_keyword' => ( $settings[ 'show_popular_keyword' ] == 'yes' ) ? 1 : 0,
			'show_category'        => ( $settings[ 'show_category_search_result' ] == 'yes' ) ? 1 : 0,
			'show_content_image'   => ( $settings[ 'show_content_image' ] == 'yes' ) ? 1 : 0,
		];
		if ( $args[ 'show_popular_keyword' ] ) {
			$args[ 'show_popular_keyword_rank' ]      = $settings[ 'show_popular_keyword_rank' ];
			$args[ 'total_number_of_popular_search' ] = $settings[ 'total_number_of_popular_search' ];
		}

        if ( isset( $settings['use_include_exclude_in_result'] ) && $settings['use_include_exclude_in_result'] == 'yes' ){
            if ( isset( $settings['exclude_category_list'] ) && !empty( $settings['exclude_category_list'] ) ){
                $args['exclude'] = $settings['exclude_category_list'];
            }
            if ( isset( $settings['include_category_list'] ) && !empty( $settings['include_category_list'] ) ){
                $args['include'] = $settings['include_category_list'];
            }
        }

		if ( !empty( $settings[ 'eael_adv_search_post_list' ] ) ) {
			$args[ 'post_type' ] = $settings[ 'eael_adv_search_post_list' ];
		}

		$form_style = 'eael-advanced-search-form-style-' . $settings[ 'eael_adv_search_style' ];


		$this->add_render_attribute(
			'adv_search',
			[
				'id'    => "eael-advanced-search-widget-{$this->get_id()}",
				'class' => 'eael-advanced-search-widget',
			]
		);

		$this->add_render_attribute(
			'adv_search_form',
			[
				'data-settings' => htmlspecialchars( json_encode( $args ), ENT_QUOTES, 'UTF-8' ),
				'method'        => 'POST',
				'name'          => 'eael-advanced-search-form-' . $this->get_id(),
				'class'         => [ 'eael-advanced-search-form', $form_style ],
			]
		);

		?>
        <div class="eael-adv-search-wrapper">
            <div <?php echo $this->get_render_attribute_string( 'adv_search' ); ?>>
                <form <?php echo $this->get_render_attribute_string( 'adv_search_form' ); ?>>
                    <div class="eael-advanced-search-wrap">
                        <span class="eael-adv-search-loader"></span>
                        <span class="eael-adv-search-close"><i class="fas fa-times"></i></span>
                        <span class="icon "><i class="fas fa-search"></i></span>

                        <input type="text"
                               placeholder="<?php echo esc_html( $settings[ 'search_field_placeholder_text' ] ); ?>"
                               class="eael-advanced-search" autocomplete="off" name="eael_advanced_search">
                    </div>
					<?php if ( $settings[ 'show_category_list' ] == 'yes' ): ?>
                        <div class="eael-advance-search-select">
                            <span class="icon fas fa-chevron-down"></span>
							<?php echo $this->cate_list_render(); ?>
                        </div>
					<?php endif; ?>
                    <button class="eael-advanced-search-button"><?php echo esc_html( $settings[ 'search_field_button_text' ] ); ?></button>
                </form>

				<?php $this->render_popular_keyword( $settings ); ?>



				<?php echo $this->search_result_render( $settings ); ?>
            </div>
        </div>

		<?php
	}

	/**
	 * cate_list_render
	 * @return string|null
	 */
	public function cate_list_render() {
		$settings  = $this->get_settings_for_display();
		$cat_lists = $this->get_post_term_list( $settings[ 'eael_adv_search_post_list' ] );
		$markup    = sprintf( "<option value=''>%s</option>", esc_html( $settings[ 'category_list_text' ] ) );
		if ( !empty( $cat_lists ) ) {
			foreach ( $cat_lists as $key => $item ) {
				$markup .= sprintf( "<option value='%d'>%s</option>", $key, $item );
			}
		}
		return sprintf( '<select name="eael-adv-search-cate-list" class="eael-adv-search-cate">%s</select>', $markup );
	}

	public function search_result_render( $settings ) {
		$content_style = 'eael-item-style-' . $settings[ 'eael_adv_search_result' ];
		ob_start();
		?>
        <div class="eael-advanced-search-result">
            <div class="eael-advanced-search-popular-keyword">
                <h4> <?php echo esc_html( $settings[ 'popular_search_text' ] ); ?></h4>
                <div class="eael-popular-keyword-content"></div>
            </div>
            <div class="eael-advanced-search-category">
                <h4><?php echo esc_html( $settings[ 'category_search_text' ] ); ?> </h4>
                <div class="eael-popular-category-content"></div>
            </div>
            <div class="eael-advanced-search-content <?php echo $content_style; ?>">
            </div>
            <div class="eael-advanced-search-not-found">
                <p><?php echo esc_html( $settings[ 'not_found_text' ] ); ?></p></div>
            <div class="eael-advanced-search-load-more"><a class="eael-advanced-search-load-more-button"
                                                           href="#"><?php echo esc_html( $settings[ 'load_more_text' ] ); ?></a>
            </div>
        </div>

		<?php
		return ob_get_clean();
	}

	public function render_popular_keyword( $settings ) {

		if ( $settings[ 'show_popular_keyword' ] != 'yes' ) {
			return false;
		}

		$popular_keywords = (array)get_option( 'eael_adv_search_popular_keyword', true );

		if ( empty( $popular_keywords ) || count( $popular_keywords ) < 2 ) {
			return false;
		}


		arsort( $popular_keywords );
		$lists = null;
		$rank  = $settings[ 'show_popular_keyword_rank' ];
		foreach ( array_slice( $popular_keywords, 1, $settings[ 'total_number_of_popular_search' ] ) as $key => $item ) {
			if ( $item <= $rank ) {
				continue;
			}
			$keywords = ucfirst( str_replace( '_', ' ', $key ) );
			$lists    .= sprintf( '<a href="javascript:void(0)" data-keyword="%1$s" class="eael-popular-keyword-item">%1$s</a>', $keywords );
		}

		?>
		<?php if ( !empty( $lists ) ): ?>
            <div class="eael-advanced-search-popular-keyword eael-after-adv-search">
                <h4> <?php echo esc_html( $settings[ 'popular_search_text' ] ); ?></h4>
                <div class="eael-popular-keyword-content"><?php echo $lists; ?></div>
            </div>
		<?php endif; ?>
		<?php

	}

}
