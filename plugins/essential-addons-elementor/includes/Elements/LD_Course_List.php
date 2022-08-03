<?php

namespace Essential_Addons_Elementor\Pro\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Utils;
use \Elementor\Widget_Base;
use \Elementor\Core\Schemes\Typography;
use \Elementor\Group_Control_Background;
use \Essential_Addons_Elementor\Classes\Helper;


class LD_Course_List extends Widget_Base
{

    public function get_name()
    {
        return 'eael-learn-dash-course-list';
    }

    public function get_title()
    {
        return esc_html__('LearnDash Course List', 'essential-addons-elementor');
    }

    public function get_icon()
    {
        return 'eaicon-learndash';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'learndash',
            'ea learndash',
            'ea learndash course list',
            'ea ld course list',
            'course list',
            'e-learning',
            'lesson',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/learndash-course-list/';
    }

    public function get_style_depends()
    {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }

    public function get_script_depends()
    {
        return [
            'font-awesome-4-shim'
        ];
    }

    public function is_reload_preview_required()
    {
        return true;
    }

    protected function register_controls()
    {
        if (!defined('LEARNDASH_VERSION')) {
            $this->start_controls_section(
                'eael_global_warning',
                [
                    'label'             => __('Warning!', 'essential-addons-elementor'),
                ]
            );

            $this->add_control(
                'eael_global_warning_text',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __('<strong>LearnDash</strong> is not installed/activated on your site. Please install and activate <strong>LearnDash</strong> first.', 'essential-addons-elementor'),
                    'content_classes' => 'eael-warning',
                ]
            );

            $this->end_controls_section();
        } else {
            /**
             * ----------------------------------------
             * General settings section
             * ----------------------------------------
             */
            $this->start_controls_section(
                'section_general_settings',
                [
                    'label' => esc_html__('Layout Settings', 'essential-addons-elementor')
                ]
            );

            $this->add_control(
                'template_skin',
                [
                    'label'            => esc_html__('Skins', 'essential-addons-elementor'),
                    'type'            => Controls_Manager::SELECT,
                    'label_block'    => false,
                    'description'    => __('Select skin for different layout design.', 'essential-addons-elementor'),
                    'options'        => [
                        'default'        => __('Default', 'essential-addons-elementor'),
                        'layout__1'     => __('Layout 1', 'essential-addons-elementor'),
                        'layout__2'        => __('Layout 2', 'essential-addons-elementor'),
                        'layout__3'     => __('Layout 3', 'essential-addons-elementor')
                    ],
                    'default'        => 'default'
                ]
            );

            $this->add_control(
                'layout_mode',
                [
                    'label'            => esc_html__('Layout Mode', 'essential-addons-elementor'),
                    'type'            => Controls_Manager::SELECT,
                    'label_block'    => false,
                    'options'        => [
                        'grid'   => __('Grid', 'essential-addons-elementor'),
                        'masonry' => __('Masonry', 'essential-addons-elementor'),
                        'fit-to-screen' => __('Fit To Screen', 'essential-addons-elementor')
                    ],
                    'default'        => 'grid'
                ]
            );

            $this->add_control(
                'number_of_courses',
                [
                    'label'       => __('Number of Courses', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::NUMBER,
                    'description' => __('How many courses will be displayed in your grid.', 'essential-addons-elementor'),
                    'default'     => 10
                ]
            );

            $this->add_control(
                'course_category_name',
                [
                    'label'           => __('Show by course category', 'essential-addons-elementor'),
                    'description'     => __('Shows only courses in the specified LearnDash category. Use the category slug.', 'essential-addons-elementor'),
                    'type'            => Controls_Manager::SELECT2,
                    'multiple'     => true,
                    'label_block'     => true,
                    'options'       => Helper::get_terms_list('ld_course_category', 'slug')
                ]
            );

            $this->add_control(
                'course_tag',
                [
                    'label'           => __('Show by course tag', 'essential-addons-elementor'),
                    'description'     => __('Shows only courses tagged with the specified LearnDash tag. Use the tag slug.', 'essential-addons-elementor'),
                    'type'            => Controls_Manager::SELECT2,
                    'multiple'     => true,
                    'label_block'     => true,
                    'options'       => Helper::get_terms_list('ld_course_tag', 'slug')
                ]
            );

            $this->add_responsive_control(
                'column',
                [
                    'label'            => esc_html__('Columns', 'essential-addons-elementor'),
                    'type'            => Controls_Manager::SELECT,
                    'label_block'    => false,
                    'description'    => __('Number of columns your grid will have on differnt screens.', 'essential-addons-elementor'),
                    'options'        => [
                        '1'        => __('1 Column', 'essential-addons-elementor'),
                        '2'        => __('2 Columns', 'essential-addons-elementor'),
                        '3'        => __('3 Columns', 'essential-addons-elementor'),
                        '4'        => __('4 Columns', 'essential-addons-elementor'),
                        '5'        => __('5 Columns', 'essential-addons-elementor'),
                        '6'        => __('6 Columns', 'essential-addons-elementor')
                    ],
                    'prefix_class' => 'elementor-grid%s-',
                    'frontend_available' => true,
                    'default' => '3',
                    'tablet_default' => '2',
                    'mobile_default' => '1'
                ]
            );



            $this->add_control(
                'show_tags',
                [
                    'label'       => __('Tags', 'essential-addons-elementor'),
                    'description' => __('Hide course tags.', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'true',
                    'condition' => [
                        'template_skin!'    => 'layout__3'
                    ]
                ]
            );

            $this->add_control(
                'show_cats',
                [
                    'label'       => __('Category', 'essential-addons-elementor'),
                    'description' => __('Hide course category.', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'true',
                    'condition' => [
                        'template_skin'    => 'layout__3'
                    ]
                ]
            );

            $this->add_control(
                'show_thumbnail',
                [
                    'label'       => __('Thumbnail', 'essential-addons-elementor'),
                    'description' => __('Hide the thumbnail image.', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'true',
                    'condition' => [
                        'template_skin!'    => 'layout__3'
                    ]
                ]
            );

            $this->add_control(
                'show_course_meta',
                [
                    'label'       => __('Course Meta', 'essential-addons-elementor'),
                    'description' => __('Hide course meta.', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'true',
                    'condition' => [
                        'template_skin' => 'layout__2'
                    ]
                ]
            );

            $this->add_control(
                'show_date',
                [
                    'label'       => __('Date', 'essential-addons-elementor'),
                    'description' => __('Hide show course date.', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'true',
                    'condition' => [
                        'template_skin' => ['layout__2', 'layout__3']
                    ]
                ]
            );

            $this->add_control(
                'show_content',
                [
                    'label'       => __('Excerpt', 'essential-addons-elementor'),
                    'description' => __('Hide course excerpt', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'true',
                ]
            );

            $this->add_control(
                'excerpt_length',
                [
                    'label' => __('Excerpt Words', 'essential-addons-elementor'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '12',
                    'condition' => [
                        'show_content' => 'true',
                    ],
                ]
            );

            $this->add_control(
                'show_price',
                [
                    'label'       => __('Price', 'essential-addons-elementor'),
                    'description' => __('Hide course price', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'true',
                    'condition' => [
                        // 'template_skin!' => ['layout__2' ]
                    ]
                ]
            );

	        $this->add_control(
		        'change_free_price_text',
		        [
			        'label'       => __('Change Free Price Text?', 'essential-addons-elementor'),
			        'type'        => Controls_Manager::CHOOSE,
			        'options' => [
				        'true' => [
					        'title' => __('Show', 'essential-addons-elementor'),
					        'icon' => 'eicon-check',
				        ],
				        'false' => [
					        'title' => __('Hide', 'essential-addons-elementor'),
					        'icon' => 'eicon-ban',
				        ]
			        ],
			        'default'   => 'false',
			        'condition' => [
				         'template_skin!' => ['layout__1' ]
			        ]
		        ]
	        );

	        $this->add_control(
		        'free_price_text',
		        [
			        'label'       => __('Free Price Text', 'essential-addons-elementor'),
			        'type'        => Controls_Manager::TEXT,
			        'default'   => __('Free', 'essential-addons-elementor'),
			        'condition' => [
				        'change_free_price_text' => 'true',
                        'template_skin!' => ['layout__1' ]
			        ]
		        ]
	        );

            $this->add_control(
                'show_button',
                [
                    'label'       => __('Button', 'essential-addons-elementor'),
                    'description' => __('Hide course enroll button.', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'true'
                ]
            );

	        $this->add_control(
		        'change_button_text',
		        [
			        'label'       => __('Change Button Text?', 'essential-addons-elementor'),
			        'type'        => Controls_Manager::CHOOSE,
			        'options' => [
				        'true' => [
					        'title' => __('Show', 'essential-addons-elementor'),
					        'icon' => 'eicon-check',
				        ],
				        'false' => [
					        'title' => __('Hide', 'essential-addons-elementor'),
					        'icon' => 'eicon-ban',
				        ]
			        ],
			        'default'   => 'false'
		        ]
	        );

	        $this->add_control(
		        'button_text',
		        [
			        'label'       => __('Button Text', 'essential-addons-elementor'),
			        'type'        => Controls_Manager::TEXT,
			        'default'   => __('Read More', 'essential-addons-elementor'),
                    'condition' => [
                        'change_button_text' => 'true'
                    ]
		        ]
	        );

            $this->add_control(
                'show_progress_bar',
                [
                    'label'       => __('Progress Bar', 'essential-addons-elementor'),
                    'description' => __('A visual indicator of a student’s current progress in each course.', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'true'
                ]
            );

            $this->add_control(
                'eael_course_filter_show',
                [
                    'label'       => __('Enable Filter', 'essential-addons-elementor'),
                    'description' => __('It displays tab items with the selcted categories and tags.', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'false'
                ]
            );

            $this->add_control(
                'eael_course_filter_all_label_text',
                [
                    'label' => esc_html__('All Label', 'essential-addons-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic'     => [ 'active' => true ],
                    'default' => esc_html__('All', 'essential-addons-elementor'),
                    'condition' => [
                        'eael_course_filter_show' => 'true',
                    ],
                ]
            );

            $this->add_control(
                'show_author_meta',
                [
                    'label'       => __('Author Meta', 'essential-addons-elementor'),
                    'description' => __('Hide show author meta from courses.', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'true',
                    'condition' => [
                        'template_skin' => ['default', 'layout__1']
                    ]
                ]
            );

            $this->add_control(
                'eael_learndash_ribbon_show',
                [
                    'label'       => __('Ribbon Text', 'essential-addons-elementor'),
                    'description' => __('Hide show ribbon text from courses.', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'false',
                    'condition' => [
                        // 'template_skin' => ['default', 'layout__1']
                    ]
                ]
            );

            $this->add_control(
		        'change_ribbon_text',
		        [
			        'label'       => __('Change Ribbon Text?', 'essential-addons-elementor'),
			        'type'        => Controls_Manager::CHOOSE,
			        'options' => [
				        'true' => [
					        'title' => __('Show', 'essential-addons-elementor'),
					        'icon' => 'eicon-check',
				        ],
				        'false' => [
					        'title' => __('Hide', 'essential-addons-elementor'),
					        'icon' => 'eicon-ban',
				        ]
			        ],
			        'default'   => 'false',
                    'condition' => [
                        'eael_learndash_ribbon_show' => 'true'
                    ]
		        ]
	        );

            $this->add_control(
                'eael_ribbon_enrolled_label_text',
                [
                    'label' => esc_html__('Enrolled Label', 'essential-addons-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic'     => [ 'active' => true ],
                    'default' => esc_html__('Enrolled', 'essential-addons-elementor'),
                    'condition' => [
                        'eael_learndash_ribbon_show' => 'true',
                        'change_ribbon_text' => 'true'
                    ],
                ]
            );

            $this->add_control(
                'eael_ribbon_completed_label_text',
                [
                    'label' => esc_html__('Completed Label', 'essential-addons-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic'     => [ 'active' => true ],
                    'default' => esc_html__('Completed', 'essential-addons-elementor'),
                    'condition' => [
                        'eael_learndash_ribbon_show' => 'true',
                        'change_ribbon_text' => 'true'
                    ],
                ]
            );

            $this->add_control(
                'show_course_duration',
                [
                    'label'       => __('Course Duration', 'essential-addons-elementor'),
                    'description' => __('Hide show duration of courses.', 'essential-addons-elementor'),
                    'type'        => Controls_Manager::CHOOSE,
                    'options' => [
                        'true' => [
                            'title' => __('Show', 'essential-addons-elementor'),
                            'icon' => 'eicon-check',
                        ],
                        'false' => [
                            'title' => __('Hide', 'essential-addons-elementor'),
                            'icon' => 'eicon-ban',
                        ]
                    ],
                    'default'   => 'false',
                ]
            );

            $this->end_controls_section();
            #End of `General Settings` section


            /**
             * Sorting section
             * ----------------------------------------
             */
            $this->start_controls_section(
                'section_sorting',
                [
                    'label' => esc_html__('Sorting Options', 'essential-addons-elementor')
                ]
            );

            $this->add_control(
                'orderby',
                [
                    'label'            => esc_html__('Course Sorting', 'essential-addons-elementor'),
                    'type'            => Controls_Manager::SELECT2,
                    'label_block'    => false,
                    'description'    => __('How to sort the courses in your grid.', 'essential-addons-elementor'),
                    'options'        => [
                        'title'            => __('Title', 'essential-addons-elementor'),
                        'ID'            => __('ID', 'essential-addons-elementor'),
                        'date'            => __('Date', 'essential-addons-elementor'),
                        'modified'        => __('Modified', 'essential-addons-elementor'),
                        'menu_order'    => __('Menu Order', 'essential-addons-elementor'),
                        'rand'            => __('Random', 'essential-addons-elementor')
                    ],
                    'default'        => 'date',
                ]
            );

            $this->add_control(
                'order',
                [
                    'label'            => esc_html__('Order of Sorting', 'essential-addons-elementor'),
                    'type'            => Controls_Manager::SELECT,
                    'label_block'    => false,
                    'description'    => __('The sort order for the “orderby” parameter.', 'essential-addons-elementor'),
                    'options'        => [
                        'ASC'    => __('ASC', 'essential-addons-elementor'),
                        'DESC'    => __('DESC', 'essential-addons-elementor'),
                    ],
                    'default'        => 'ASC',
                ]
            );

            $this->add_control(
                'mycourses',
                [
                    'label'            => esc_html__('My Courses', 'essential-addons-elementor'),
                    'type'            => Controls_Manager::SELECT2,
                    'description'    => __('Shows only the courses in which the current user is enrolled.', 'essential-addons-elementor'),
                    'options'        => [
                        'default'      => __('Default', 'essential-addons-elementor'),
                        'enrolled'         => __('Enrolled Only', 'essential-addons-elementor'),
                        'not-enrolled' => __('Not Enrolled Only', 'essential-addons-elementor'),
                    ],
                    'default'        => 'default',
                ]
            );

            $this->end_controls_section();
            #End of `General Settings` section


            /**
             * Card Style section
             * ----------------------------------------
             */
            $this->start_controls_section(
                'card_style_section',
                [
                    'label' => esc_html__('Card Style', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE
                ]
            );

            $this->start_controls_tabs('eael_learn_dash_card_tabs');

            $this->start_controls_tab(
                'eael_learn_dash_card_tab_normal',
                [
                    'label' => esc_html__('Normal', 'essential-addons-elementor')
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'card_background',
                    'label' => __('Background', 'essential-addons-elementor'),
                    'default'   => '#ffffff',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'      => 'card_border',
                    'selector'  => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner',
                ]
            );

            $this->add_responsive_control(
                'card_border_radius',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'                  => 'card_shadow',
                    'selector'              => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner',
                ]
            );

            $this->add_control(
                'card_transition',
                [
                    'label'                 => __('Transition', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '300',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 10000,
                            'step'  => 100,
                        ],
                    ],
                    'size_units'            => ['px'],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner' => 'transition: {{SIZE}}ms',
                    ]
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'eael_learn_dash_card_tab_hover',
                [
                    'label' => esc_html__('Hover', 'essential-addons-elementor')
                ]
            );

            $this->add_control(
                '3d_hover',
                [
                    'label' => __('Enable 3D Hover', 'essential-addons-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-elementor'),
                    'label_off' => __('Hide', 'essential-addons-elementor'),
                    'return_value' => 'yes',
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'card_hover_background',
                    'label' => __('Background', 'essential-addons-elementor'),
                    'default'   => '#ffffff',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner:hover',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'      => 'card_hover_border',
                    'selector'  => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner:hover',
                ]
            );

            $this->add_responsive_control(
                'card_hover_border_radius',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'                  => 'card_hover_shadow',
                    'selector'              => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner:hover',
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_responsive_control(
                'card_content_alignment',
                [
                    'label' => __('Alignment', 'essential-addons-elementor'),
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
                    'default' => 'center',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-deash-course-content-card' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-dash-course-header' => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .layout-button-wrap'   => 'text-align: {{VALUE}};',
                        '{{WRAPPER}} .eael-learndash-wrapper.layout__3 .card-body'  => 'text-align: {{VALUE}};'
                    ],
                    'separator' => 'before'
                ]
            );

            $this->add_responsive_control(
                'card_padding',
                [
                    'label'      => esc_html__('Padding', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-deash-course-content-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-learndash-wrapper.layout__3 .card-body'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'card_margin',
                [
                    'label'      => esc_html__('Margin', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();
            # End of `Card style`

            /**
             * Tags Style section
             * ----------------------------------------
             */
            $this->start_controls_section(
                'tags_style_section',
                [
                    'label' => esc_html__('Tags Style', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_tags' => 'true',
                        'template_skin!' => 'layout__3'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'                  => 'tags_typography',
                    'label'                 => __('Typography', 'essential-addons-elementor'),
                    'scheme'                => Typography::TYPOGRAPHY_4,
                    'selector'              => '.eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .course-tag',
                ]
            );

            $this->add_responsive_control(
                'tags_spacing',
                [
                    'label'      => esc_html__('Margin', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .course-tag' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'tags_color',
                [
                    'label'     => esc_html__('Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#7453c6',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .course-tag' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'tags_background',
                    'label' => __('Background', 'essential-addons-elementor'),
                    'types' => ['classic', 'gradient'],
                    'exclude' => [
                        'image',
                    ],
                    'selector' => '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .course-tag',
                ]
            );

            $this->end_controls_section();
            # End of `Tags Style section`

            /**
             * Image Style section
             * ----------------------------------------
             */
            $this->start_controls_section(
                'image_style_section',
                [
                    'label' => esc_html__('Image Style', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_thumbnail'    => 'true'
                    ]
                ]
            );

            $this->add_responsive_control(
                'image_border_radius',
                [
                    'label'                 => __('Border Radius', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '10',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 80,
                            'step'  => 1,
                        ],
                    ],
                    'size_units'            => ['px', '%'],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-dash-course-thumbnail img' => 'border-radius: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .eael-learndash-wrapper.layout__3 a.card-thumb'    => 'border-radius: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .eael-learndash-wrapper.layout__3 .eael-learn-dash-course-inner'   => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-top-right-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
            );

            $this->add_responsive_control(
                'image_space',
                [
                    'label'                 => __('Bottom Space', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '0',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 80,
                            'step'  => 1,
                        ],
                    ],
                    'size_units'            => ['px'],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-dash-course-thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                        '{{WRAPPER}} .eael-learndash-wrapper.layout__3 a.card-thumb'   => 'margin-bottom: {{SIZE}}{{UNIT}};'
                    ]
                ]
            );

            $this->end_controls_section();
            # End of `Image Style section`

            /**
             * Color &  Typography section
             * ----------------------------------------
             */
            $this->start_controls_section(
                'color_&_typography_section',
                [
                    'label' => esc_html__('Color & Typography', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'title_typo_heading',
                [
                    'label'                 => __('Title', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::HEADING
                ]
            );

            $this->add_control(
                'title_tag',
                [
                    'label'            => esc_html__('Title Tag', 'essential-addons-elementor'),
                    'type'            => Controls_Manager::SELECT,
                    'label_block'    => false,
                    'options'        => [
                        'h1'        => __('H1', 'essential-addons-elementor'),
                        'h2'        => __('H2', 'essential-addons-elementor'),
                        'h3'        => __('H3', 'essential-addons-elementor'),
                        'h4'        => __('H4', 'essential-addons-elementor'),
                        'h5'        => __('H5', 'essential-addons-elementor'),
                        'h6'        => __('H6', 'essential-addons-elementor'),
                    ],
                    'default'        => 'h4'
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'                  => 'title_typography',
                    'label'                 => __('Typography', 'essential-addons-elementor'),
                    'scheme'                => Typography::TYPOGRAPHY_4,
                    'selector'              => '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-deash-course-content-card .course-card-title, {{WRAPPER}} .eael-learn-dash-course.eael-course-layout-3.card-style .card-body .course-card-title',
                ]
            );

            $this->add_control(
                'title_color',
                [
                    'label'     => esc_html__('Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#485771',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-deash-course-content-card .course-card-title a' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .eael-learn-dash-course.eael-course-layout-3.card-style .card-body .course-card-title a'    => 'color: {{VALUE}};'
                    ]
                ]
            );

            $this->add_responsive_control(
                'title_spacing',
                [
                    'label'      => esc_html__('Margin', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-deash-course-content-card .course-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} .eael-learn-dash-course.eael-course-layout-3.card-style .card-body .course-card-title'    => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'content_typo_heading',
                [
                    'label'                 => __('Content', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::HEADING,
                    'separator'             => 'before',
                    'condition' => [
                        'show_content'    => 'true'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'                  => 'content_typography',
                    'label'                 => __('Typography', 'essential-addons-elementor'),
                    'selector'              => '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-dash-course-short-desc',
                    'condition' => [
                        'show_content'    => 'true'
                    ]
                ]
            );

            $this->add_control(
                'content_color',
                [
                    'label'     => esc_html__('Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#485771',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-dash-course-short-desc' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'show_content'    => 'true'
                    ]
                ]
            );

            $this->add_responsive_control(
                'content_spacing',
                [
                    'label'      => esc_html__('Margin', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-dash-course-short-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'show_content'    => 'true'
                    ]
                ]
            );

            $this->end_controls_section();
            # End of `Color & typography section`

            /**
             * Price ticker section
             * ----------------------------------------
             */
            $this->start_controls_section(
                'price_ticker_style_section',
                [
                    'label' => esc_html__('Price', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'template_skin'     => 'layout__1'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'                  => 'price_ticker_typography',
                    'label'                 => __('Typography', 'essential-addons-elementor'),
                    'selector'              => '{{WRAPPER}} .eael-learndash-wrapper .price-ticker-tag',
                ]
            );

            $this->add_control(
                'price_ticker_color',
                [
                    'label'     => esc_html__('Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .price-ticker-tag' => 'color: {{VALUE}};',
                    ]
                ]
            );

            $this->add_control(
                'price_ticker_position',
                [
                    'label'            => esc_html__('Position', 'essential-addons-elementor'),
                    'type'            => Controls_Manager::SELECT,
                    'label_block'    => false,
                    'description'    => __('Select price ticker position.', 'essential-addons-elementor'),
                    'options'        => [
                        'left-top'        => __('Left - Top', 'essential-addons-elementor'),
                        'left-bottom'   => __('Left - Bottom', 'essential-addons-elementor'),
                        'right-top'        => __('Right - Top', 'essential-addons-elementor'),
                        'right-bottom'    => __('Right - Bottom', 'essential-addons-elementor'),
                    ],
                    'default'        => 'left-bottom',
                    'prefix_class'  => 'price-tikcer-position-'
                ]
            );

            $this->add_responsive_control(
                'price_ticker_border_radus',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learndash-wrapper .price-ticker-tag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'price_ticker_height',
                [
                    'label'                 => __('Height', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '42',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 100,
                            'step'  => 1,
                        ],
                    ],
                    'size_units'            => ['px'],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-learndash-wrapper .price-ticker-tag' => 'height: {{SIZE}}{{UNIT}}',
                    ]
                ]
            );

            $this->add_responsive_control(
                'price_ticker_width',
                [
                    'label'                 => __('Width', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '25',
                        'unit'      => '%',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 100,
                            'step'  => 1,
                        ],
                    ],
                    'size_units'            => ['%'],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-learndash-wrapper .price-ticker-tag' => 'width: {{SIZE}}{{UNIT}}',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'price_ticker_background',
                    'label' => __('Background', 'essential-addons-elementor'),
                    'types' => ['classic', 'gradient'],
                    'exclude' => [
                        'image',
                    ],
                    'default'   => '#7453c6',
                    'selector' => '{{WRAPPER}} .eael-learndash-wrapper .price-ticker-tag',
                ]
            );

            $this->end_controls_section();
            # End of `Price ticker section`

            /**
             * Price section
             * ----------------------------------------
             */
            $this->start_controls_section(
                'price_style_section',
                [
                    'label' => esc_html__('Price', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_price'    => 'true',
                        'template_skin'     => ['layout__3', 'default']
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'price_background',
                    'label' => __('Background', 'essential-addons-elementor'),
                    'types' => ['classic', 'gradient'],
                    'exclude' => [
                        'image',
                    ],
                    'default'   => '#7453c6',
                    'selector' => '{{WRAPPER}} .eael-learndash-wrapper .card-price',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'                  => 'price_typography',
                    'label'                 => __('Typography', 'essential-addons-elementor'),
                    'selector'              => '{{WRAPPER}} .eael-learndash-wrapper .card-price',
                ]
            );

            $this->add_control(
                'price_color',
                [
                    'label'     => esc_html__('Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .card-price' => 'color: {{VALUE}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'price_border_radius',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learndash-wrapper .card-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'price_circle_size',
                [
                    'label'                 => __('Size', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '50',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 95,
                            'step'  => 5,
                        ],
                    ],
                    'size_units'            => ['px'],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-learndash-wrapper .card-price' => 'width:{{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ]
                ]
            );

            $this->end_controls_section();
            # End of `Price ticker section`

            /**
             * Course Meta section bottom 
             * ----------------------------------------
             */
            $this->start_controls_section(
                'section_inline_author_meta',
                [
                    'label' => esc_html__('Author Meta', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'template_skin'     => ['default', 'layout__1']
                    ]
                ]
            );

            $this->add_responsive_control(
                'author_meta_space_around',
                [
                    'label'      => esc_html__('Space Around', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-author-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'template_skin' => 'layout__1'
                    ]
                ]
            );

            $this->add_control(
                'author_meta_avatar',
                [
                    'label'                 => __('Avatar', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::HEADING
                ]
            );

            $this->add_responsive_control(
                'author_avatar_size',
                [
                    'label'                 => __('Avatar Size', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '50',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 95,
                            'step'  => 5,
                        ],
                    ],
                    'size_units'            => ['px'],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-author-meta .author-image' => 'flex-basis:{{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'template_skin' => 'layout__1'
                    ]
                ]
            );

            $this->add_responsive_control(
                'avatar_border_radus',
                [
                    'label'      => esc_html__('Avatar Border Radius', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-author-meta .author-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'template_skin' => 'layout__1'
                    ]
                ]
            );

            $this->add_responsive_control(
                'avatar_space',
                [
                    'label'                 => __('Avatar Space', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '15',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 30,
                            'step'  => 1,
                        ],
                    ],
                    'size_units'            => ['px'],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-author-meta .author-image' => 'margin-right: {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'template_skin' => 'layout__1'
                    ]
                ]
            );

            $this->add_control(
                'author_meta_heading',
                [
                    'label'                 => __('Author', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::HEADING
                ]
            );

            $this->add_control(
                'inline_author_meta',
                [
                    'label'     => esc_html__('Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learn-dash-course-inner .course-author-meta-inline span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'inline_author_meta_link',
                [
                    'label'     => esc_html__('Link Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learn-dash-course-inner .course-author-meta-inline a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'inline_author_meta_link_hover',
                [
                    'label'     => esc_html__('Link Hover Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learn-dash-course-inner .course-author-meta-inline a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'author_meta_date',
                [
                    'label'                 => __('Date', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::HEADING
                ]
            );

            $this->add_control(
                'author_meta_date_color',
                [
                    'label'     => esc_html__('Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-author-meta .author-desc .author-designation' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'                  => 'author_meta_date_typo',
                    'label'                 => __('Typography', 'essential-addons-elementor'),
                    'selector'              => '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-author-meta .author-desc .author-designation',
                ]
            );


            $this->end_controls_section();
            # End of `Course Meta section bottom`


            /**
             * Course Meta section
             * ----------------------------------------
             */
            $this->start_controls_section(
                'course_meta_style_section',
                [
                    'label' => esc_html__('Course Meta & Price', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'template_skin'     => 'layout__2'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'                  => 'course_meta_typography',
                    'label'                 => __('Typography', 'essential-addons-elementor'),
                    'scheme'                => Typography::TYPOGRAPHY_4,
                    'selector'              => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-deash-course-content-card .eael-learn-dash-course-meta-card span',
                ]
            );

            $this->add_control(
                'course_meta_color',
                [
                    'label'     => esc_html__('Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#7453c6',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-deash-course-content-card .eael-learn-dash-course-meta-card span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'course_meta_icon_space',
                [
                    'label'                 => __('Icon Space', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '8',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 25,
                            'step'  => 1,
                        ],
                    ],
                    'size_units'            => ['px'],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-learn-deash-course-content-card .eael-learn-dash-course-meta-card span i' => 'margin-right: {{SIZE}}{{UNIT}}',
                    ]
                ]
            );

            $this->add_responsive_control(
                'course_meta_each_space',
                [
                    'label'                 => __('Meta Space', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '25',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 40,
                            'step'  => 1,
                        ],
                    ],
                    'size_units'            => ['px'],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-learn-dash-course-inner .eael-learn-deash-course-content-card .eael-learn-dash-course-meta-card span.enrolled-count' => 'margin: 0 {{SIZE}}{{UNIT}}',
                    ]
                ]
            );

            $this->end_controls_section();
            # End of `Course meta section`

            /**
             * Button style section
             * ----------------------------------------
             */
            $this->start_controls_section(
                'button_style_section',
                [
                    'label' => esc_html__('Button', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_button' => 'true'
                    ]
                ]
            );

            $this->start_controls_tabs(
                'button_controls_tabs',
                [
                    'separator' => 'after'
                ]
            );
            $this->start_controls_tab(
                'button_controls_normal',
                [
                    'label' => esc_html__('Normal', 'essential-addons-elementor')
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'                  => 'button_typography',
                    'label'                 => __('Typography', 'essential-addons-elementor'),
                    'scheme'                => Typography::TYPOGRAPHY_4,
                    'selector'              => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button',
                ]
            );

            $this->add_control(
                'button_color',
                [
                    'label'     => esc_html__('Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'button_background',
                    'label' => __('Background', 'essential-addons-elementor'),
                    'types' => ['classic', 'gradient'],
                    'exclude' => [
                        'image',
                    ],
                    'default'   => '#7453c6',
                    'selector' => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'                  => 'button_shadow',
                    'selector'              => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button',
                ]
            );

            $this->add_responsive_control(
                'button_border_radius',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'button_padding',
                [
                    'label'      => esc_html__('Padding', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'button_margin',
                [
                    'label'      => esc_html__('Margin', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->end_controls_tab();

            $this->start_controls_tab(
                'button_controls_hover',
                [
                    'label' => esc_html__('Hover', 'essential-addons-elementor')
                ]
            );

            $this->add_control(
                'button_color_hover',
                [
                    'label'     => esc_html__('Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'button_background_hover',
                    'label' => __('Background', 'essential-addons-elementor'),
                    'types' => ['classic', 'gradient'],
                    'exclude' => [
                        'image',
                    ],
                    'default'   => '#7453c6',
                    'selector' => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button:hover',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'                  => 'button_shadow_hover',
                    'selector'              => '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button:hover',
                ]
            );

            $this->add_control(
                'button_transition_hover',
                [
                    'label'                 => __('Transition', 'essential-addons-elementor'),
                    'description'           => __('Hover transition in ms.', 'essential-addons-elementor'),
                    'type'                  => Controls_Manager::SLIDER,
                    'default'               => [
                        'size'      => '300',
                        'unit'      => 'px',
                    ],
                    'range'                 => [
                        'px'        => [
                            'min'   => 0,
                            'max'   => 1000,
                            'step'  => 10,
                        ],
                    ],
                    'size_units'            => ['px'],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button' => 'transition:{{SIZE}}ms',
                    ]
                ]
            );

            $this->add_responsive_control(
                'button_border_radius_hover',
                [
                    'label'      => esc_html__('Border Radius', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learn-dash-course .eael-learn-dash-course-inner .eael-course-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->end_controls_section();
            # End of `Button style section`

            /**
             * Progressbar section
             * ----------------------------------------
             */
            $this->start_controls_section(
                'progress_bar_style_section',
                [
                    'label' => esc_html__('Progress Bar', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'show_progress_bar' => 'true'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'                  => 'progressbar_title_typography',
                    'label'                 => __('Typography', 'essential-addons-elementor'),
                    'selector'              => '{{WRAPPER}} .learndash-wrapper.learndash-widget .ld-progress .ld-progress-percentage',
                ]
            );

            $this->add_control(
                'progressbar_title_color',
                [
                    'label'     => esc_html__('Label Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#7453c6',
                    'selectors' => [
                        '{{WRAPPER}} .learndash-wrapper.learndash-widget .ld-progress .ld-progress-percentage' => 'color: {{VALUE}};'
                    ],
                ]
            );

            $this->add_control(
                'progressbar_fill_color',
                [
                    'label'     => esc_html__('Fill Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#7453c6',
                    'selectors' => [
                        '{{WRAPPER}} .learndash-wrapper.learndash-widget .ld-progress .ld-progress-bar .ld-progress-bar-percentage'  => 'background: {{VALUE}};'
                    ],
                ]
            );

            $this->add_responsive_control(
                'progressbar_margin',
                [
                    'label'      => esc_html__('Space', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learndash-wrapper .learndash-wrapper .ld-progress.ld-progress-inline' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );

            $this->add_responsive_control(
                'progressbar_label_alignment',
                [
                    'label' => __('Label Alignment', 'essential-addons-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-end' => [
                            'title' => __('Left', 'essential-addons-elementor'),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __('Center', 'essential-addons-elementor'),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'flex-start' => [
                            'title' => __('Right', 'essential-addons-elementor'),
                            'icon' => 'eicon-text-align-right',
                        ]
                    ],
                    'default' => 'center',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .learndash-wrapper .ld-progress.ld-progress-inline' => 'justify-content: {{VALUE}};',
                    ]
                ]
            );

            $this->add_control(
                'progressbar_step_label',
                [
                    'label' => __('Enable Steps Label', 'essential-addons-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Show', 'essential-addons-elementor'),
                    'label_off' => __('Hide', 'essential-addons-elementor'),
                    'prefix_class' => 'course-steps-label-',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'                  => 'steps_label_typography',
                    'label'                 => __('Typography', 'essential-addons-elementor'),
                    'selector'              => '{{WRAPPER}} .eael-learn-dash-course .learndash-wrapper.learndash-widget .ld-progress .ld-progress-steps',
                    'condition' => [
                        'progressbar_step_label'    => 'yes'
                    ]
                ]
            );

            $this->add_control(
                'progress_label_color',
                [
                    'label'     => esc_html__('Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learn-dash-course .learndash-wrapper.learndash-widget .ld-progress .ld-progress-steps' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'progressbar_step_label'    => 'yes'
                    ]
                ]
            );

            $this->add_responsive_control(
                'progress_label_margin',
                [
                    'label'      => esc_html__('Space', 'essential-addons-elementor'),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors'  => [
                        '{{WRAPPER}} .eael-learn-dash-course .learndash-wrapper.learndash-widget .ld-progress .ld-progress-steps' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'progressbar_step_label'    => 'yes'
                    ]
                ]
            );

            $this->end_controls_section();

            $this->start_controls_section(
                'ribbon_text_style_section',
                [
                    'label' => esc_html__('Ribbon', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'eael_learndash_ribbon_show' => 'true'
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'                  => 'ribbon_text_label_typography',
                    'label'                 => __('Typography', 'essential-addons-elementor'),
                    'selector'              => '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael_ld_course_list_ribbon',
                ]
            );

            $this->add_control(
                'ribbon_text_enrolled_style',
                [
                    'label'     => esc_html__('Ribbon - Enrolled', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::HEADING,
                ]
            );
            $this->add_control(
                'ribbon_text_enrolled_background_color',
                [
                    'label'     => esc_html__('Background Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#7453c6',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael_ld_course_list_ribbon.ribbon-enrolled' => 'background: {{VALUE}};',
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael_ld_course_list_ribbon.ribbon-enrolled:before' => 'border-top: 4px solid {{VALUE}};border-right: 4px solid {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'ribbon_text_enrolled_label_color',
                [
                    'label'     => esc_html__('Text Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael_ld_course_list_ribbon.ribbon-enrolled' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'ribbon_text_completed_style',
                [
                    'label'     => esc_html__('Ribbon - Completed', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::HEADING,
                ]
            );
            $this->add_control(
                'ribbon_text_completed_background_color',
                [
                    'label'     => esc_html__('Background Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#5cb85c',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael_ld_course_list_ribbon' => 'background: {{VALUE}};',
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael_ld_course_list_ribbon:before' => 'border-top: 4px solid {{VALUE}};border-right: 4px solid {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'ribbon_text_completed_label_color',
                [
                    'label'     => esc_html__('Text Color', 'essential-addons-elementor'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .eael-learndash-wrapper .eael-learn-dash-course .eael-learn-dash-course-inner .eael_ld_course_list_ribbon' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_section();
            # End of `Course meta section`



        }
    }


    protected function _generate_tags($tags)
    {
        $settings = $this->get_settings();

        if (!empty($tags) && $settings['show_tags'] === 'true') {
            $i = 0; ?>
            <div class="eael-learn-dash-course-header">
                <?php foreach ($tags as $tag) {
                    if ($i == 3) break;
                    if ($tag) {
                        echo '<div class="course-tag">' . $tag->name . '</div>';
                    }
                    $i++;
                } ?>
            </div>
            <?php }
    }

    protected function get_courses()
    {
        $settings = $this->get_settings();

        // Default args
        $query_args = [
            'post_type' => 'sfwd-courses',
            'numberposts'   => $settings['number_of_courses'],
            'orderby'   => $settings['orderby'],
            'order' => $settings['order']
        ];

        $query_args['tax_query'] = [];
        $query_args['tax_query']['relation'] = 'OR';

        /**
         * Course filter by course category & tag
         */
        if (!empty($settings['course_cat'])) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'ld_course_category',
                'field'    => 'id',
                'terms'    => array($settings['course_cat']),
            ];
        }

        if (!empty($settings['course_category_name'])) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'ld_course_category',
                'field'    => 'slug',
                'terms'    => is_array($settings['course_category_name']) ? $settings['course_category_name'] : array($settings['course_category_name']),
            ];
        }

        if (!empty($settings['course_tag_id'])) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'ld_course_tag',
                'field'    => 'id',
                'terms'    => array($settings['course_tag_id']),
            ];
        }

        if (!empty($settings['course_tag'])) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'ld_course_tag',
                'field'    => 'slug',
                'terms'    => is_array($settings['course_tag']) ? $settings['course_tag'] : array($settings['course_tag']),
            ];
        }
        #end of course category & tag filter.

        return get_posts($query_args);
    }

    protected function get_course_filter_tabs($settings){
        ob_start();
        $all_text = !empty($settings['eael_course_filter_all_label_text']) ? Helper::eael_wp_kses($settings['eael_course_filter_all_label_text']) : esc_html__('All', 'essential-addons-elementor'); 
        ?>
        <div class="eael-learndash-filter-control">
            <ul>
                <?php 
                    echo '<li class="control active all" data-filter="*" >' . $all_text . '</li>';

                    if ( !empty($settings['course_category_name']) ) {
                        $filter_tab_cats = is_array($settings['course_category_name']) ? $settings['course_category_name'] : array($settings['course_category_name']);

                        foreach ( $filter_tab_cats as $filter_tab_slug ) {
                            $course_cat_obj = get_term_by('slug', $filter_tab_slug, 'ld_course_category');
                            $course_cat_name = isset($course_cat_obj->name) ? $course_cat_obj->name : '';
                            echo '<li class="control cat-' . esc_attr($filter_tab_slug) . '" data-filter=".cat-' . esc_attr($filter_tab_slug) .'" >' . $course_cat_name . '</li>';
                        }
                    }

                    if ( !empty($settings['course_tag']) ) {
                        $filter_tab_tags = is_array($settings['course_tag']) ? $settings['course_tag'] : array($settings['course_tag']);
                        foreach ( $filter_tab_tags as $filter_tab_slug ) {
                            $course_tag_obj = get_term_by('slug', $filter_tab_slug, 'ld_course_tag');
                            $course_tag_name = isset($course_tag_obj->name) ? $course_tag_obj->name : '';
                            echo '<li class="control tag-' . esc_attr($filter_tab_slug) . '" data-filter=".tag-' . esc_attr($filter_tab_slug) .'" >' . $course_tag_name . '</li>';
                        }
                    }
                ?>
            </ul>
        </div>
        <?php 
        $html = ob_get_clean();

        return $html;
    }

    protected function get_enrolled_courses_only(array $data)
    {
        $course_ids = wp_list_pluck($data, 'ID');
        return array_intersect($course_ids, ld_get_mycourses(get_current_user_id()));
    }

    protected function get_controlled_short_desc($desc = '', $length = 0)
    {
        if ($desc && $length) {

            $desc = strip_tags(strip_shortcodes($desc)); //Strips tags and images
            $words = explode(' ', $desc, $length + 1);

            if (count($words) > $length) :
                array_pop($words);
                array_push($words, '…');
                $desc = implode(' ', $words);
            endif;
        }

        return $desc;
    }

    protected function get_eael_ribbon_atts( $course, $settings = [] ){

        $post_type = isset($course->post_type) ? $course->post_type : '';
        $course_id = isset($course->ID) ? $course->ID : '';
        $user_id   = get_current_user_id();

        $course_options = get_post_meta( $course_id, "_sfwd-courses", true );

        // For LD >= 3.0
        $price = '';
        $price_type = '';
        if ( function_exists( 'learndash_get_course_price' ) && function_exists( 'learndash_get_group_price' ) ) {
            if ( $post_type == 'sfwd-courses' ) {
                $price_args = learndash_get_course_price( $course_id );
            } elseif ( $post_type == 'groups' ) {
                $price_args = learndash_get_group_price( $course_id );
            }

            if ( ! empty( $price_args ) ) {
                $price      = $price_args['price'];
                $price_type = $price_args['type'];
            }
        } else {
            $price = $course_options && isset($course_options['sfwd-courses_course_price']) ? $course_options['sfwd-courses_course_price'] : __( 'Free', 'essential-addons-elementor' );
            $price_type = $course_options && isset( $course_options['sfwd-courses_course_price_type'] ) ? $course_options['sfwd-courses_course_price_type'] : '';
        }

        $is_completed = false;
        if ( $post_type == 'sfwd-courses' ) {
            $has_access   = sfwd_lms_has_access( $course_id, $user_id );
            $is_completed = learndash_course_completed( $user_id, $course_id );
        } elseif ( $post_type == 'groups' ) {
            $has_access = learndash_is_user_in_group( $user_id, $course_id );
            $is_completed = learndash_get_user_group_completed_timestamp( $course_id, $user_id );
        }

        $legacy_meta = get_post_meta($course_id, '_sfwd-courses', true);
        $price_text = isset( $legacy_meta['sfwd-courses_course_price'] ) ? $legacy_meta['sfwd-courses_course_price'] : 'Free';

        $class       = 'eael_ld_course_list_ribbon';
        $course_class = '';
        $ribbon_text = '';
        // $hide_ribbon_containing_price = false;
        $enrolled_label_text = !empty($settings['eael_ribbon_enrolled_label_text']) ? Helper::eael_wp_kses($settings['eael_ribbon_enrolled_label_text']) : esc_html('Enrolled', 'essential-addons-elementor');
        $completed_label_text = !empty($settings['eael_ribbon_completed_label_text']) ? Helper::eael_wp_kses($settings['eael_ribbon_completed_label_text']) : esc_html('Completed', 'essential-addons-elementor');
        
        if ( in_array( $post_type, [ 'sfwd-courses', 'groups' ] ) ) {
            if ( $has_access && ! $is_completed && $price_type != 'open' && empty( $ribbon_text ) ) {
                $class .= ' ribbon-enrolled';
                $course_class .= ' learndash-available learndash-incomplete';
                $ribbon_text = __( $enrolled_label_text, 'essential-addons-elementor' );
            } elseif ( $has_access && $is_completed && $price_type != 'open' && empty( $ribbon_text ) ) {
                $class .= '';
                $course_class .= ' learndash-available learndash-complete';
                $ribbon_text = __( $completed_label_text, 'essential-addons-elementor' );
            } elseif ( $price_type == 'open' && empty( $ribbon_text ) ) {
                if ( is_user_logged_in() && ! $is_completed ) {
                    $class .= ' ribbon-enrolled';
                    $course_class .= ' learndash-available learndash-incomplete';
                    $ribbon_text = __( $enrolled_label_text, 'essential-addons-elementor' );
                } elseif ( is_user_logged_in() && $is_completed ) {
                    $class .= '';
                    $course_class .= ' learndash-available learndash-complete';
                    $ribbon_text = __( $completed_label_text, 'essential-addons-elementor' );
                } else {
                    $course_class .= ' learndash-available learndash-available';
                    $class .= ' ribbon-enrolled';
                    $ribbon_text = '';
                }
            } elseif ( $price_type == 'closed' && empty( $price ) ) {
                $class .= ' ribbon-enrolled';
                $course_class .= ' learndash-available learndash-available';

                if ( $is_completed ) {
                    $course_class .= ' learndash-complete learndash-complete';
                } else {
                    $course_class .= ' learndash-incomplete learndash-incomplete';
                }

                if ( is_numeric( $price ) ) {
                    $ribbon_text = $price_text;
                    // $hide_ribbon_containing_price = true;

                } else {
                    $ribbon_text = '';
                }
            } else {
                if ( empty( $ribbon_text ) ) {
                    $class .= ! empty( $course_options['sfwd-courses_course_price'] ) ? ' price_currency' : ' free';
                    $course_class .= ' learndash-not-available learndash-incomplete';
                    $ribbon_text = $price_text;
                    // $hide_ribbon_containing_price = true;

                } else {
                    $class .= ' custom';
                    $course_class .= ' learndash-not-available learndash-incomplete';
                }
            }
        }

        if ( '' == $ribbon_text ) {
            $class = '';
        }

        return array(
            'class' => $class,
            'course_class' => $course_class,
            'ribbon_text' => $ribbon_text,
        );
    }

    protected function render()
    {
        if (!defined('LEARNDASH_VERSION')) {
            return;
        }

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute(
            'eael-learn-dash-wrapper',
            [
                'class' => [
                    'eael-learndash-wrapper',
                    'eael-learndash-col-' . $settings['column'],
                    $settings['template_skin'],
                    'ld-cl-layout-mode-' . $settings['layout_mode']
                ],
                'data-layout-mode'  =>  $settings['layout_mode']
            ]
        );

        if ($settings['3d_hover']) {
            $this->add_render_attribute('eael-learn-dash-wrapper', 'class', 'eael-3d-hover');
            $this->add_render_attribute('eael-learn-dash-wrapper', 'data-3d-hover', $settings['3d_hover']);
        }

        $courses = $this->get_courses();

        // Get user enrolled courses.
        if ($settings['mycourses'] === 'enrolled' || $settings['mycourses'] === 'not-enrolled') {
            $enrolled_course_only = $this->get_enrolled_courses_only($courses);
        }

        $html = '';
        if(!empty($settings['eael_course_filter_show']) && 'true' === $settings['eael_course_filter_show']){
            $html = $this->get_course_filter_tabs($settings);
        }

        ob_start();
        $html .= '<div ' . $this->get_render_attribute_string('eael-learn-dash-wrapper') . '>';
        if ($courses) {
            foreach ($courses as $course) {
                if ($settings['mycourses'] === 'enrolled') {
                    // Get enrolled courses only
                    if (!in_array($course->ID, $enrolled_course_only)) continue;
                }

                if ($settings['mycourses'] === 'not-enrolled') {
                    // Get not enrolled courses only
                    if (in_array($course->ID, $enrolled_course_only)) continue;
                }

                $legacy_meta = get_post_meta($course->ID, '_sfwd-courses', true);
                $users = get_post_meta($course->ID, 'course_access_list', true);
	            if ( ! is_array( $users ) ) {
		            $users = explode( ',', $users );
	            }
                $short_desc = get_post_meta($course->ID, '_learndash_course_grid_short_description', true);
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($course->ID), 'large');
                $image_alt = get_post_meta(get_post_thumbnail_id($course->ID), '_wp_attachment_image_alt', true);
	            $access_list = count( $users );
                $button_text = get_post_meta($course->ID, '_learndash_course_grid_custom_button_text', true);
                $excerpt_length = $settings['excerpt_length'] ? $settings['excerpt_length'] : null;
                
                $tags = wp_get_post_terms($course->ID, 'ld_course_tag');
	            $tags_as_string = '';
	            if ( ! is_wp_error( $tags ) ) {
		            $tags_with_prefix = array_map( function ( $tag ) {
			            return 'tag-' . $tag->slug;
		            }, $tags );
		            $tags_as_string   = implode( ' ', $tags_with_prefix );
	            }

                $cats = wp_get_post_terms($course->ID, 'ld_course_category');
	            $cats_as_string = '';
	            if( ! is_wp_error( $cats ) ){
		            $cats_with_prefix = array_map(function($cat) { return 'cat-' . $cat->slug; }, $cats);
		            $cats_as_string = implode(' ', $cats_with_prefix);
	            }

                $price = $legacy_meta['sfwd-courses_course_price'] ? $legacy_meta['sfwd-courses_course_price'] : 'Free';

                $duration_in_seconds = floatval( get_post_meta($course->ID, '_learndash_course_grid_duration', true) );
                $duration_hours = floor($duration_in_seconds / 3600);
                $duration_minutes = floor(floor($duration_in_seconds % 3600)/60);
                
                $ribbon_atts = array();
                if( !empty( $settings['eael_learndash_ribbon_show'] ) && 'true' === $settings['eael_learndash_ribbon_show'] ) {
                    $ribbon_atts = $this->get_eael_ribbon_atts($course, $settings);
                }
                // $ribbon_text = get_post_meta($course->ID, '_learndash_course_grid_custom_ribbon_text', true); // not using

                //LearnDash Course Grid addon support
                $enable_video_preview_key_exist = metadata_exists('post', $course->ID, '_learndash_course_grid_enable_video_preview');
                $video_embed_code_key_exist = metadata_exists('post', $course->ID, '_learndash_course_grid_enable_video_preview');
                $ld_course_grid_enable_video_preview = $ld_course_grid_video_embed_code = '';

                if( true === $enable_video_preview_key_exist && true === $video_embed_code_key_exist){
                    $ld_course_grid_enable_video_preview = get_post_meta($course->ID, '_learndash_course_grid_enable_video_preview', true);
                    $ld_course_grid_video_embed_code = get_post_meta($course->ID, '_learndash_course_grid_video_embed_code', true);

                    // Retrive oembed HTML if URL provided
                    if ( preg_match( '/^http/', $ld_course_grid_video_embed_code ) ) {
                        $ld_course_grid_video_embed_code = wp_oembed_get( $ld_course_grid_video_embed_code, array( 'height' => 600, 'width' => 400 ) );
                    }
                }

                if ($settings['template_skin'] === 'default' || $settings['template_skin'] === 'layout__1' || $settings['template_skin'] === 'layout__3') {
                    $author_courses = add_query_arg(
                        'post_type',
                        'sfwd-courses',
                        get_author_posts_url($course->post_author, get_the_author_meta('display_name', $course->post_author))
                    );

                    $args = ['post_type' => 'sfwd-courses'];
                    if (!is_wp_error($cats)) {
                        if (isset($cats[0]) && !empty($cats[0])) {
                            $args['ld_course_category'] = esc_attr($cats[0]->name);
                        }
                    }

                    $author_courses_from_cat = add_query_arg($args, get_author_posts_url($course->post_author, get_the_author_meta('display_name', $course->post_author)));
                }

                $file = EAEL_PRO_PLUGIN_PATH . "includes/templates/ld-courses" . DIRECTORY_SEPARATOR . $settings['template_skin'] . ".php";

                if (file_exists($file)) {
                    require $file;
                } else {
                    echo __("Course layout file not found! It's must be removed \n", 'essential-addons-elementor');
                }
            }

            if (\Elementor\Plugin::instance()->editor->is_edit_mode()) { ?>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        $(".eael-learndash-wrapper").each(function() {
                            var $node_id = "<?php echo $this->get_id(); ?>",
                                $scope = $(".elementor-element-" + $node_id + ""),
                                $this = $(this),
                                $layout = $this.data('layout-mode');

                            if ($layout === 'masonry') {
                                var $settings = {
                                    itemSelector: ".eael-learn-dash-course",
                                    percentPosition: true,
                                    masonry: {
                                        columnWidth: ".eael-learn-dash-course"
                                    }
                                };

                                // init isotope
                                var $ld_gallery = $(".eael-learndash-wrapper", $scope).isotope($settings);

                                // layout gal, while images are loading
                                $ld_gallery.imagesLoaded().progress(function() {
                                    $ld_gallery.isotope("layout");
                                });
                            }

                            $scope.on("click", ".control", function (e) {
                                e.preventDefault();
                                let $this = $(this),
                                    filterValue = $this.data("filter");

                                $this.siblings().removeClass("active");
                                $this.addClass("active");
                                filterClass = filterValue.replace('.', '');

                                if( filterClass != '*' ){
                                    $('.eael-learn-dash-course', $scope).css('display', 'none');
                                    $('.eael-learn-dash-course'+filterValue, $scope).css('display', 'block').css('clear', 'none');
                                }else {
                                    $('.eael-learn-dash-course', $scope).css('display', 'block');
                                }

                            });

                        });
                    });
                </script>
            <?php
            } else { ?>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        $(".eael-learndash-wrapper").each(function() {
                            var $node_id = "<?php echo $this->get_id(); ?>",
                                $scope = $(".elementor-element-" + $node_id + ""),
                                $this = $(this),
                                $layout = $this.data('layout-mode');

                            $scope.on("click", ".control", function (e) {
                                e.preventDefault();
                                let $this = $(this),
                                    filterValue = $this.data("filter");

                                $this.siblings().removeClass("active");
                                $this.addClass("active");

                                $(".eael-learndash-wrapper", $scope).isotope({ filter: filterValue });
                            });
                        });
                    });
                </script>  
            <?php }
        } else {
            $html .= "<h4>" . __('No Courses Found!', 'essential-addons-elementor') . '</h4>';
        }
        $html .= ob_get_clean();

        $html .= '</div>';

        echo $html;
    }

    protected function content_template()
    {
    }
}
