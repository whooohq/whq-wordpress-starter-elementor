<?php

namespace Essential_Addons_Elementor\Pro\Elements;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use Elementor\Repeater;
use \Elementor\Core\Schemes\Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Utils;
use \Elementor\Widget_Base;
use \Elementor\ElementsCommonFunctions;

//use \Essential_Addons_Elementor\Classes\Helper;
use \Essential_Addons_Elementor\Pro\Classes\Helper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Content_Timeline extends Widget_Base
{

	use \Essential_Addons_Elementor\Traits\Template_Query;

	public function get_name()
	{
		return 'eael-content-timeline';
	}

	public function get_title()
	{
		return __('Content Timeline', 'essential-addons-elementor');
	}

	public function get_icon()
	{
		return 'eaicon-content-timeline';
	}

	public function get_categories()
	{
		return ['essential-addons-elementor'];
	}

	public function get_keywords()
	{
		return [
			'content timeline',
			'ea post timeline',
			'ea content timeline',
			'ea timeline',
			'content',
			'timeline',
			'blog post',
			'blog',
			'post',
			'ea',
			'essential addons'
		];
	}

	public function get_custom_help_url()
	{
		return 'https://essential-addons.com/elementor/docs/content-timeline/';
	}

	protected function _register_controls()
	{
		/**
		 * Custom Timeline Settings
		 */
		$this->start_controls_section(
			'eael_section_custom_timeline_settings',
			[
				'label' => __('Timeline Content', 'essential-addons-elementor')
			]
		);

		$this->add_control(
			'eael_content_timeline_choose',
			[
				'label'       	=> esc_html__('Content Source', 'essential-addons-elementor'),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'dynamic',
				'label_block' 	=> false,
				'options' 		=> [
					'custom'  	=> esc_html__('Custom', 'essential-addons-elementor'),
					'dynamic'  	=> esc_html__('Dynamic', 'essential-addons-elementor'),
				],
			]
		);

		$this->end_controls_section();
		/**
		 * Custom Content
		 */
		$this->start_controls_section(
			'eael_section_custom_content_settings',
			[
				'label' => __('Custom Content Settings', 'essential-addons-elementor'),
				'condition' => [
					'eael_content_timeline_choose' => 'custom'
				]
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'eael_custom_title',
			[
				'label' => esc_html__('Title', 'essential-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__('The Ultimate Addons For Elementor', 'essential-addons-elementor'),
				'dynamic' => ['active' => true]
			]
		);

		$repeater->add_control(
			'eael_custom_excerpt',
			[
				'label' => esc_html__('Content', 'essential-addons-elementor'),
				'type' => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default' => esc_html__('A new concept of showing content in your web page with more interactive way.', 'essential-addons-elementor'),
			]
		);

		$repeater->add_control(
			'eael_custom_post_date',
			[
				'label' => __('Post Date', 'essential-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'dynamic' => ['active' => true],
				'default' => esc_html__('Nov 09, 2017', 'essential-addons-elementor'),
			]
		);

		$repeater->add_control(
			'eael_show_custom_image_or_icon',
			[
				'label' => __('Show Circle Image / Icon', 'essential-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'img' => [
						'title' => __('Image', 'essential-addons-elementor'),
						'icon' => 'eicon-image-bold',
					],
					'icon' => [
						'title' => __('Icon', 'essential-addons-elementor'),
						'icon' => 'fa fa-info',
					],
					'bullet' => [
						'title' => __('Bullet', 'essential-addons-elementor'),
						'icon' => 'fa fa-circle',
					]
				],
				'default' => 'icon',
				'separator' => 'before'
			]
		);

        $repeater->add_control(
			'eael_custom_icon_image',
			[
				'label' => __( 'Choose Image', 'essential-addons-elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
                'condition' => [
					'eael_show_custom_image_or_icon' => 'img',
				],
			]
		);

		$repeater->add_control(
			'eael_custom_icon_image_size',
			[
				'label' => esc_html__('Icon Image Size', 'essential-addons-elementor'),
				'type' => Controls_Manager::NUMBER,
				'default' => 24,
				'condition' => [
					'eael_show_custom_image_or_icon' => 'img',
				],
			]
		);

		$repeater->add_control(
			'eael_custom_content_timeline_circle_icon_new',
			[
				'label' => esc_html__('Icon', 'essential-addons-elementor'),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'eael_custom_content_timeline_circle_icon',
				'default' => [
					'value' => 'fas fa-pencil-alt',
					'library' => 'fa-solid',
				],
				'condition' => [
					'eael_show_custom_image_or_icon' => 'icon',
				]
			]
		);

		$repeater->add_control(
			'eael_show_custom_read_more',
			[
				'label' => __('Show Read More', 'essential-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'1' => [
						'title' => __('Yes', 'essential-addons-elementor'),
						'icon' => 'fa fa-check',
					],
					'0' => [
						'title' => __('No', 'essential-addons-elementor'),
						'icon' => 'eicon-ban',
					]
				],
				'default' => '1',
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'eael_show_custom_read_more_text',
			[
				'label' => esc_html__('Label Text', 'essential-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'dynamic'   => ['active' => true],
				'label_block' => true,
				'default' => esc_html__('Read More', 'essential-addons-elementor'),
				'condition' => [
					'eael_show_custom_read_more' => '1',
				]
			]
		);

		$repeater->add_control(
			'eael_read_more_text_link',
			[
				'label' => esc_html__('Button Link', 'essential-addons-elementor'),
				'type' => Controls_Manager::URL,
				'dynamic'   => ['active' => true],
				'label_block' => true,
				'default' => [
					'url' => '#',
					'is_external' => '',
				],
				'show_external' => true,
				'condition' => [
					'eael_show_custom_read_more' => '1',
				]
			]
		);

		$this->add_control(
			'eael_coustom_content_posts',
			[
				'type' => Controls_Manager::REPEATER,
				'seperator' => 'before',
				'default' => [
					[
						'eael_custom_title' => __('The Ultimate Addons For Elementor', 'essential-addons-elementor'),
						'eael_custom_excerpt' => __('A new concept of showing content in your web page with more interactive way.', 'essential-addons-elementor'),
						'eael_custom_post_date' => 'Nov 09, 2017',
						'eael_read_more_text_link' => '#',
						'eael_show_custom_read_more' => '1',
						'eael_show_custom_read_more_text' => 'Read More',
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{eael_custom_title}}',
			]
		);



		$this->end_controls_section();

		/**
		 * Query And Layout Controls!
		 * @source includes/elementor-helper.php
		 */
		do_action('eael/controls/query', $this);

		do_action('eael/controls/layout', $this);

        /**
         * Content Tab: Links
         */

        $this->start_controls_section(
            'section_content_timeline_links',
            [
                'label' => __('Links', 'essential-addons-elementor'),
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                       [
                          'name' => 'eael_content_timeline_choose',
                          'operator' => '==',
                          'value' => 'dynamic',
                       ],
                       [
                          'relation' => 'or',
                          'terms' => [
                             [
                                'name' => 'eael_show_title',
                                'operator' => '==',
                                'value' => 'yes',
                             ],
                             [
                                'name' => 'eael_show_read_more',
                                'operator' => '==',
                                'value' => 'yes',
                             ],
                          ],
                       ],
                    ],
                 ],
            ]
        );

        $this->add_control(
            'title_link',
            [
                'label' => __('Title', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_link_nofollow',
            [
                'label' => __('No Follow', 'essential-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-elementor'),
                'label_off' => __('No', 'essential-addons-elementor'),
                'return_value' => 'true',
                'condition' => [
                    'eael_show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_link_target_blank',
            [
                'label' => __('Target Blank', 'essential-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-elementor'),
                'label_off' => __('No', 'essential-addons-elementor'),
                'return_value' => 'true',
                'condition' => [
                    'eael_show_title' => 'yes',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'read_more_link',
            [
                'label' => __('Read More', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_link_nofollow',
            [
                'label' => __('No Follow', 'essential-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-elementor'),
                'label_off' => __('No', 'essential-addons-elementor'),
                'return_value' => 'true',
                'condition' => [
                    'eael_show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_link_target_blank',
            [
                'label' => __('Target Blank', 'essential-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-elementor'),
                'label_off' => __('No', 'essential-addons-elementor'),
                'return_value' => 'true',
                'condition' => [
                    'eael_show_read_more' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Section: Style
         */

		$this->start_controls_section(
			'eael_section_post_timeline_style',
			[
				'label' => __('Timeline Style', 'essential-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_timeline_line_size',
			[
				'label' => esc_html__('Line Size', 'essential-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 4,
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-line' => 'width: {{SIZE}}px;',
					'{{WRAPPER}} .eael-content-timeline-line .eael-content-timeline-inner' => 'width: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_timeline_line_from_left',
			[
				'label' => esc_html__('Position From Left', 'essential-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 2,
				],
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-line' => 'margin-left: -{{SIZE}}px;',
				],
				'description' => __('Use half of the Line size for perfect centering', 'essential-addons-elementor'),
			]
		);

		$this->add_control(
			'eael_timeline_line_color',
			[
				'label' => __('Inactive Line Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#d7e4ed',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-line' => 'background: {{VALUE}}',
				]

			]
		);

		$this->add_control(
			'eael_timeline_line_active_color',
			[
				'label' => __('Active Line Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#3CCD94',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-line .eael-content-timeline-inner' => 'background: {{VALUE}}',
				]

			]
		);

		$this->end_controls_section();

		/**
		 * Card Style
		 */
		$this->start_controls_section(
			'eael_section_post_timeline_card_style',
			[
				'label' => __('Card Style', 'essential-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_card_bg_color',
			[
				'label' => __('Background Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#f1f2f3',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content' => 'background: {{VALUE}};',
					'{{WRAPPER}} .eael-content-timeline-content::before' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				]

			]
		);

		$this->add_responsive_control(
			'eael_card_padding',
			[
				'label' => esc_html__('Padding', 'essential-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_card_margin',
			[
				'label' => esc_html__('Margin', 'essential-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_card_border',
				'label' => esc_html__('Border', 'essential-addons-elementor'),
				'selector' => '{{WRAPPER}} .eael-content-timeline-content',
			]
		);

		$this->add_responsive_control(
			'eael_card_radius',
			[
				'label' => esc_html__('Border Radius', 'essential-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_card_shadow',
				'selector' => '{{WRAPPER}} .eael-content-timeline-content',
			]
		);

		$this->end_controls_section();

        /**
         * -------------------------------------------
         * Caret Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_content_timeline_caret_style',
            [
                'label' => esc_html__('Caret Style', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        //Caret can be hidden using caret size : 0
//        $this->add_control(
//            'eael_content_timeline_tab_caret_show',
//            [
//                'label' => esc_html__('Show Caret', 'essential-addons-elementor'),
//                'type' => Controls_Manager::SWITCHER,
//                'default' => 'yes',
//                'return_value' => 'yes',
//            ]
//        );
        $this->add_responsive_control(
            'eael_content_timeline_tab_caret_size',
            [
                'label' => esc_html__('Caret Size', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-content-timeline-content::before' => 'border-width: {{SIZE}}px;',
                ],
                'condition' => [
//                    'eael_content_timeline_tab_caret_show' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_content_timeline_tab_caret_position',
            [
                'label' => esc_html__('Caret Position', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 24,
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-content-timeline-content::before' => 'top: {{SIZE}}%;',
                ],
                'condition' => [
//                    'eael_content_timeline_tab_caret_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_content_timeline_tab_caret_color',
            [
                'label' => esc_html__('Caret Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
//                'default' => 'transparent',
                'default' => '#f1f2f3',
                'selectors' => [
                    '{{WRAPPER}} .eael-content-timeline-content::before' => 'border-left-color: {{VALUE}};border-right-color: {{VALUE}};',
                ],
                'condition' => [
//                    'eael_content_timeline_tab_caret_show' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

		/**
		 * Icon Circle Style
		 */
		$this->start_controls_section(
			'eael_section_post_timeline_icon_circle_style',
			[
				'label' => __('Bullet Style', 'essential-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'eael_icon_circle_size',
			[
				'label' => esc_html__('Bullet Size', 'essential-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 40,
				],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-img' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'eael_icon_font_size',
			[
				'label' => esc_html__('Icon Size', 'essential-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-img i' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .eael-content-timeline-img .content-timeline-bullet-svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'eael_icon_circle_from_top',
			[
				'label' => esc_html__('Position From Top', 'essential-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 8,
				],
				'range' => [
					'px' => [
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-img' => 'margin-top: {{SIZE}}px;',
					'{{WRAPPER}} .eael-content-timeline-line' => 'margin-top: {{SIZE}}px;',
					'{{WRAPPER}} ..eael-content-timeline-line .eael-content-timeline-inner' => 'margin-top: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'eael_icon_circle_from_left',
			[
				'label' => esc_html__('Position From Left', 'essential-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'description' => __('Use half of the Icon Cicle Size for perfect centering', 'essential-addons-elementor'),
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-img' => 'margin-left: -{{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'eael_icon_circle_border_width',
			[
				'label' => esc_html__('Bullet Border Width', 'essential-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 6,
				],
				'range' => [
					'px' => [
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-img.eael-picture' => 'border-width: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_icon_circle_color',
			[
				'label' => __('Bullet Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#f1f2f3',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-img.eael-picture' => 'background: {{VALUE}}',
				]

			]
		);


		$this->add_control(
			'eael_icon_circle_border_color',
			[
				'label' => __('Bullet Border Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#f9f9f9',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-img.eael-picture' => 'border-color: {{VALUE}}',
				]

			]
		);


		$this->add_control(
			'eael_icon_circle_font_color',
			[
				'label' => __('Bullet Font Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-img i' => 'color: {{VALUE}}',
				]

			]
		);


		$this->add_control(
			'eael_timeline_icon_active_state',
			[
				'label' => __('Active State (Highlighted)', 'essential-addons-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_icon_circle_active_color',
			[
				'label' => __('Bullet Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#3CCD94',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-block.eael-highlight .eael-content-timeline-img.eael-picture' => 'background: {{VALUE}}',
				]

			]
		);


		$this->add_control(
			'eael_icon_circle_active_border_color',
			[
				'label' => __('Bullet Border Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-block.eael-highlight .eael-content-timeline-img.eael-picture' => 'border-color: {{VALUE}}',
				]

			]
		);

		$this->add_control(
			'eael_icon_circle_active_font_color',
			[
				'label' => __('Bullet Font Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-block.eael-highlight .eael-content-timeline-img i' => 'color: {{VALUE}}',
				]

			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'eael_section_typography',
			[
				'label' => __('Color & Typography', 'essential-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_timeline_title_style',
			[
				'label' => __('Title Style', 'essential-addons-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_timeline_title_color',
			[
				'label' => __('Title Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#303e49',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-timeline-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-content-timeline-content .eael-timeline-title a' => 'color: {{VALUE}};',
				]

			]
		);

		$this->add_responsive_control(
			'eael_timeline_title_alignment',
			[
				'label' => __('Title Alignment', 'essential-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'essential-addons-elementor'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'essential-addons-elementor'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'essential-addons-elementor'),
						'icon' => 'eicon-text-align-right',
					]
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-timeline-title' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .eael-content-timeline-content .eael-timeline-title a' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_timeline_title_typography',
				'label' => __('Typography', 'essential-addons-elementor'),
				'scheme' => Typography::TYPOGRAPHY_1,
                'selector' =>'{{WRAPPER}} .eael-content-timeline-content .eael-timeline-title',
			]
		);

		$this->add_control(
			'eael_timeline_excerpt_style',
			[
				'label' => __('Excerpt Style', 'essential-addons-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_timeline_excerpt_color',
			[
				'label' => __('Excerpt Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#333',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content p' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'eael_timeline_excerpt_alignment',
			[
				'label' => __('Excerpt Alignment', 'essential-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'essential-addons-elementor'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'essential-addons-elementor'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'essential-addons-elementor'),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __('Justified', 'essential-addons-elementor'),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_timeline_excerpt_typography',
				'label' => __('Excerpt Typography', 'essential-addons-elementor'),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-content-timeline-content p',
			]
		);

		$this->add_control(
			'eael_timeline_date_style',
			[
				'label' => __('Date Style', 'essential-addons-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'eael_timeline_date_margin',
			[
				'label' => esc_html__('Margin', 'essential-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_timeline_date_color',
			[
				'label' => __('Date Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#4d4d4d',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-date' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_timeline_date_typography',
				'label' => __('Date Typography', 'essential-addons-elementor'),
				'scheme' => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-content-timeline-content .eael-date',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'eael_section_load_more_btn',
			[
				'label' => __('Load More Button Style', 'essential-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_load_more' => '1'
				]
			]
		);

		$this->add_responsive_control(
			'eael_post_block_load_more_btn_padding',
			[
				'label' => esc_html__('Padding', 'essential-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_post_block_load_more_btn_margin',
			[
				'label' => esc_html__('Margin', 'essential-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_block_load_more_btn_typography',
				'selector' => '{{WRAPPER}} .eael-load-more-button',
			]
		);

		$this->start_controls_tabs('eael_post_block_load_more_btn_tabs');

		// Normal State Tab
		$this->start_controls_tab('eael_post_block_load_more_btn_normal', ['label' => esc_html__('Normal', 'essential-addons-elementor')]);

		$this->add_control(
			'eael_post_block_load_more_btn_normal_text_color',
			[
				'label' => esc_html__('Text Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_load_more_bg_color',
			[
				'label' => esc_html__('Background Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#29d8d8',
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_post_block_load_more_btn_normal_border',
				'label' => esc_html__('Border', 'essential-addons-elementor'),
				'selector' => '{{WRAPPER}} .eael-load-more-button',
			]
		);

		$this->add_control(
			'eael_post_block_load_more_btn_border_radius',
			[
				'label' => esc_html__('Border Radius', 'essential-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab('eael_post_block_load_more_btn_hover', ['label' => esc_html__('Hover', 'essential-addons-elementor')]);

		$this->add_control(
			'eael_post_block_load_more_btn_hover_text_color',
			[
				'label' => esc_html__('Text Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_post_block_load_more_btn_hover_bg_color',
			[
				'label' => esc_html__('Background Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#27bdbd',
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_post_block_load_more_btn_hover_border_color',
			[
				'label' => esc_html__('Border Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button:hover' => 'border-color: {{VALUE}};',
				],
			]

		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_post_block_load_more_btn_shadow',
				'selector' => '{{WRAPPER}} .eael-load-more-button',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_post_timeline_load_more_loader_pos_title',
			[
				'label' => esc_html__('Loader Position', 'essential-addons-elementor'),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_post_timeline_loader_pos_left',
			[
				'label' => esc_html__('From Left', 'essential-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button.button--loading .button__loader' => 'left: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_post_timeline_loader_pos_top',
			[
				'label' => esc_html__('From Top', 'essential-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button.button--loading .button__loader' => 'top: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Button Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_read_more_button_style',
			[
				'label' => esc_html__('Read More Button Style', 'essential-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'eael_read_more_padding',
			[
				'label' => esc_html__('Padding', 'essential-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_read_more_margin',
			[
				'label' => esc_html__('Margin', 'essential-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_read_more_typography',
				'selector' => '{{WRAPPER}} .eael-content-timeline-content .eael-read-more',
			]
		);

		$this->start_controls_tabs('eael_read_more_tabs');

		// Normal State Tab
		$this->start_controls_tab('eael_read_more_normal', ['label' => esc_html__('Normal', 'essential-addons-elementor')]);

		$this->add_control(
			'eael_read_more_normal_text_color',
			[
				'label' => esc_html__('Text Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-read-more' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_read_more_normal_bg_color',
			[
				'label' => esc_html__('Background Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#3CCD94',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-read-more' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_read_more_normal_border',
				'label' => esc_html__('Border', 'essential-addons-elementor'),
				'selector' => '{{WRAPPER}} .eael-content-timeline-content .eael-read-more',
			]
		);

		$this->add_control(
			'eael_read_more_border_radius',
			[
				'label' => esc_html__('Border Radius', 'essential-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-read-more' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_tab();

		// Hover State Tab
		$this->start_controls_tab('eael_read_more_hover', ['label' => esc_html__('Hover', 'essential-addons-elementor')]);

		$this->add_control(
			'eael_read_more_hover_text_color',
			[
				'label' => esc_html__('Text Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#f9f9f9',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-read-more:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_read_more_hover_bg_color',
			[
				'label' => esc_html__('Background Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#bac4cb',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-read-more:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_read_more_hover_border_color',
			[
				'label' => esc_html__('Border Color', 'essential-addons-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-content-timeline-content .eael-read-more:hover' => 'border-color: {{VALUE}};',
				],
			]

		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_read_more_shadow',
				'selector' => '{{WRAPPER}} .eael-content-timeline-content .eael-read-more',
				'separator' => 'before'
			]
		);

		$this->end_controls_section();
	}


	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$settings = Helper::fix_old_query($settings);
		$args = Helper::get_query_args($settings);

		$this->add_render_attribute(
			'timeline-wrapper',
			[
				'id'	=> 'eael-content-timeline-' . esc_attr($this->get_id()),
				'class'	=> [
					'content-timeline-layout-' . esc_attr($settings['content_timeline_layout']),
					'date-position-' . esc_attr($settings['date_position'])
				]
			]
		);

?>

		<div <?php echo $this->get_render_attribute_string('timeline-wrapper'); ?>>
			<div class="eael-content-timeline-container">
				<div class="eael-content-timeline-container">
					<?php
					if ('dynamic' === $settings['eael_content_timeline_choose']) :
						$settings = [
							'eael_show_image_or_icon'           => $settings['eael_show_image_or_icon'],
							'eael_content_timeline_circle_icon' => (isset($settings['__fa4_migrated']['eael_content_timeline_circle_icon_new']) || empty($settings['eael_content_timeline_circle_icon']) ? $settings['eael_content_timeline_circle_icon_new']['value'] : $settings['eael_content_timeline_circle_icon']),
							'eael_show_title'                   => $settings['eael_show_title'],
							'eael_show_excerpt'                 => $settings['eael_show_excerpt'],
							'eael_excerpt_length'               => $settings['eael_excerpt_length'],
							'eael_show_read_more'               => $settings['eael_show_read_more'],
							'eael_read_more_text'               => $settings['eael_read_more_text'],
							'eael_icon_image'                   => $settings['eael_icon_image'],
							'expanison_indicator'       		=> $settings['excerpt_expanison_indicator'],
							'title_tag'							=> $settings['title_tag'],
							'title_link_nofollow'   			=> $settings['title_link_nofollow'] ? 'rel="nofollow"' : '',
							'title_link_target_blank'			=> $settings['title_link_target_blank'] ? 'target="_blank"' : '',
							'read_more_link_nofollow'			=> $settings['read_more_link_nofollow'] ? 'rel="nofollow"' : '',
							'read_more_link_target_blank'		=> $settings['read_more_link_target_blank'] ? 'target="_blank"' : '',
						];

						$template = $this->get_template($this->get_settings('eael_dynamic_template_Layout'));
						if (file_exists($template)) {
							$query = new \WP_Query($args);
							if ($query->have_posts()) {
								while ($query->have_posts()) {
									$query->the_post();
									include($template);
								}
							} else {
								_e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-elementor');
							}
							wp_reset_postdata();
						} else {
							_e('<p class="no-posts-found">No layout found!</p>', 'essential-addons-elementor');
						}

					elseif ('custom' === $settings['eael_content_timeline_choose']) : ?>

						<?php foreach ($settings['eael_coustom_content_posts'] as $custom_content) : ?>
							<?php
							// button url
							$url = isset($custom_content['eael_read_more_text_link']['url'])?$custom_content['eael_read_more_text_link']['url']:'';
							$target   = !empty($custom_content['eael_read_more_text_link']['is_external']) ? 'target="_blank"' : '';
							$nofollow = !empty($custom_content['eael_read_more_text_link']['nofollow']) ? 'rel="nofollow"' : '';

							$icon_migrated = isset($settings['__fa4_migrated']['eael_custom_content_timeline_circle_icon_new']);
							$icon_is_new = empty($settings['eael_custom_content_timeline_circle_icon']);
							?>
							<div class="eael-content-timeline-block">
								<div class="eael-content-timeline-line">
									<div class="eael-content-timeline-inner"></div>
								</div>
								<div class="eael-content-timeline-img eael-picture <?php if ('bullet' === $settings['eael_show_image_or_icon']) : echo 'eael-content-timeline-bullet';
																					endif; ?>">
									<?php if ('img' === $custom_content['eael_show_custom_image_or_icon']) : ?>
										<img src="<?php echo esc_url($custom_content['eael_custom_icon_image']['url']); ?>" style="width: <?php echo $custom_content['eael_custom_icon_image_size']; ?>px;" alt="<?php echo esc_attr(get_post_meta($custom_content['eael_custom_icon_image']['id'], '_wp_attachment_image_alt', true)); ?>">
									<?php endif; ?>
									<?php if ('icon' === $custom_content['eael_show_custom_image_or_icon']) : ?>
										<?php if ($icon_migrated || $icon_is_new) { ?>
											<?php if (isset($custom_content['eael_custom_content_timeline_circle_icon_new']['value']['url'])) : ?>
												<img class="content-timeline-bullet-svg" src="<?php echo esc_attr($custom_content['eael_custom_content_timeline_circle_icon_new']['value']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($custom_content['eael_custom_content_timeline_circle_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
											<?php else : ?>
												<i class="<?php echo esc_attr($custom_content['eael_custom_content_timeline_circle_icon_new']['value']); ?>"></i>
											<?php endif; ?>
										<?php } else { ?>
											<i class="<?php echo esc_attr($custom_content['eael_custom_content_timeline_circle_icon']); ?>"></i>
										<?php } ?>
									<?php endif; ?>
								</div>

								<div class="eael-content-timeline-content">
									<?php if ('yes' == $settings['eael_show_title']) : ?>
										<<?php echo Helper::eael_pro_validate_html_tag($settings['title_tag']); ?> class="eael-timeline-title">
											<?php if (!empty($url)) : ?><a href="<?php echo esc_url($url); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>><?php endif; ?>
												<?php echo $custom_content['eael_custom_title']; ?>
												<?php if (!empty($url)) : ?></a><?php endif; ?>
										</<?php echo Helper::eael_pro_validate_html_tag($settings['title_tag']); ?>>
									<?php endif; ?>
									<?php if ('yes' == $settings['eael_show_excerpt']) : ?>
										<p><?php echo wp_kses_post($custom_content['eael_custom_excerpt']); ?></p>
									<?php endif; ?>
									<?php if ('1' == $custom_content['eael_show_custom_read_more'] && !empty($custom_content['eael_show_custom_read_more_text'])) : ?>
										<a href="<?php echo esc_url($custom_content['eael_read_more_text_link']['url']); ?>" class="eael-read-more" <?php echo $target; ?> <?php echo $nofollow; ?>><?php echo esc_html__($custom_content['eael_show_custom_read_more_text'], 'essential-addons-elementor'); ?></a>
									<?php endif; ?>
									<?php if (!empty($custom_content['eael_custom_post_date'])) : ?>
										<span class="eael-date"><?php echo $custom_content['eael_custom_post_date']; ?></span>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>

<?php
	}
}
