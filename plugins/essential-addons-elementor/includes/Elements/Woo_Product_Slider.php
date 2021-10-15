<?php

namespace Essential_Addons_Elementor\Pro\Elements;

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

use Essential_Addons_Elementor\Traits\Helper;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;

class Woo_Product_Slider extends Widget_Base {
    use Helper;
    
    /**
     * @var int
     */
    protected $page_id;
    
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        
        $is_type_instance = $this->is_type_instance();
        
        if ( !$is_type_instance && null === $args ) {
            throw new \Exception( '`$args` argument is required when initializing a full widget instance.' );
        }
        
        if ( $is_type_instance && class_exists( 'woocommerce' ) ) {
            $this->load_quick_view_asset();
        }
    }

	/**
	 * get widget name
     *
     * Retrieve Woo Product Slider widget name.
     *
	 * @return string
	 */
    public function get_name() {
        return 'eael-woo-product-slider';
    }

	/**
	 * get widget title
     *
     * Retrieve Woo Product Slider widget title.
     *
	 * @return string
	 */
    public function get_title() {
        return esc_html__( 'Woo Product Slider', 'essential-addons-elementor' );
    }

	/**
	 * get widget icon
	 *
	 * Retrieve Woo Product Slider widget icon.
	 *
	 * @return string
	 */
    public function get_icon() {
        return 'eaicon-product-slider';
    }
    
    public function get_categories() {
        return ['essential-addons-elementor'];
    }

	/**
	 * get widget keywords
	 *
	 * Retrieve list of keywords the widget belongs to.
     *
	 * @return string[]
	 */
    public function get_keywords() {
        return [
            'woo',
            'woocommerce',
            'ea woocommerce',
            'ea woo product slider',
            'ea woocommerce product slider',
            'product gallery',
            'woocommerce slider',
            'gallery',
            'ea',
            'essential addons',
        ];
    }
    
    public function get_custom_help_url() {
        return 'https://essential-addons.com/elementor/docs/woo-product-slider/';
    }
    
    public function get_style_depends() {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }
    
    public function get_script_depends() {
        return [
            'font-awesome-4-shim',
        ];
    }
    
    protected function eael_get_product_orderby_options() {
        return apply_filters( 'eael/woo-product-slider/orderby-options', [
            'ID'         => __( 'Product ID', 'essential-addons-elementor' ),
            'title'      => __( 'Product Title', 'essential-addons-elementor' ),
            '_price'     => __( 'Price', 'essential-addons-elementor' ),
            '_sku'       => __( 'SKU', 'essential-addons-elementor' ),
            'date'       => __( 'Date', 'essential-addons-elementor' ),
            'modified'   => __( 'Last Modified Date', 'essential-addons-elementor' ),
            'parent'     => __( 'Parent Id', 'essential-addons-elementor' ),
            'rand'       => __( 'Random', 'essential-addons-elementor' ),
            'menu_order' => __( 'Menu Order', 'essential-addons-elementor' ),
        ] );
    }
    
    protected function eael_get_product_filterby_options() {
        return apply_filters( 'eael/woo-product-slider/filterby-options', [
            'recent-products'       => esc_html__( 'Recent Products', 'essential-addons-elementor' ),
            'featured-products'     => esc_html__( 'Featured Products', 'essential-addons-elementor' ),
            'best-selling-products' => esc_html__( 'Best Selling Products', 'essential-addons-elementor' ),
            'sale-products'         => esc_html__( 'Sale Products', 'essential-addons-elementor' ),
            'top-products'          => esc_html__( 'Top Rated Products', 'essential-addons-elementor' ),
        ] );
    }

	/**
     * Register Woo Product slider widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
	 * register_controls
	 */
    protected function register_controls() {
	    $this->init_content_wc_notice_controls();
        if ( !function_exists( 'WC' ) ) {
            return;
        }
        // Content Controls
        $this->eael_woo_product_slider_layout();
        $this->eael_woo_product_slider_options();
        $this->eael_woo_product_slider_query();

        $this->eael_product_action_buttons();
        $this->eael_product_badges();
        
        // Style Controls---------------
        $this->init_style_product_controls();
        $this->style_color_typography();

        $this->eael_woo_product_slider_terms_style();
        $this->eael_woo_product_slider_buttons_style();
        $this->eael_product_view_popup_style();
        $this->eael_woo_product_slider_dots();
        $this->eael_woo_product_slider_image_dots();
        $this->eael_woo_product_slider_arrows();
	    do_action( 'eael/controls/nothing_found_style', $this );
    }

	protected function eael_woo_product_slider_terms_style() {
		$this->start_controls_section(
			'eael_section_product_carousel_terms_styles',
			[
				'label' => esc_html__( 'Terms', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_carousel_terms_typography',
				'selector' => '{{WRAPPER}} .eael-product-cats a',
			]
		);

		$this->start_controls_tabs( 'eael_product_carousel_terms_style_tabs' );

		$this->start_controls_tab( 'eael_product_carousel_terms_style_tabs_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_carousel_terms_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-product-cats a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_terms_background',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8040FF',
				'selectors' => [
					'{{WRAPPER}} .eael-product-cats a' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-product-cats a:after' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_product_carousel_terms_hover_styles', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'eael_product_carousel_terms_hover_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F5EAFF',
				'selectors' => [
					'{{WRAPPER}} .eael-product-cats a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_carousel_terms_hover_background',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => [
					'{{WRAPPER}} .eael-product-cats a:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-product-cats a:hover:after' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'eael_product_carousel_terms_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-cats a' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_product_carousel_terms_padding',
			[
				'label'     => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-cats a' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);

		$this->add_control(
			'eael_product_carousel_terms_margin',
			[
				'label'     => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-cats a' => 'margin: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function init_content_wc_notice_controls() {
		if ( ! function_exists( 'WC' ) ) {
			$this->start_controls_section( 'eael_global_warning', [
				'label' => __( 'Warning!', 'essential-addons-elementor' ),
			] );
			$this->add_control( 'eael_global_warning_text', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'essential-addons-elementor' ),
				'content_classes' => 'eael-warning',
			] );
			$this->end_controls_section();

			return;
		}
	}
    
    protected function eael_woo_product_slider_layout() {

	    $this->start_controls_section(
		    'eael_section_product_slider_layouts',
		    [
			    'label' => esc_html__( 'Layout Settings', 'essential-addons-elementor' ),
		    ]
	    );

	    $this->add_control(
		    'eael_dynamic_template_layout',
		    [
			    'label'   => esc_html__( 'Layout', 'essential-addons-elementor' ),
			    'type'    => Controls_Manager::SELECT,
			    'default' => 'preset-1',
			    'options' => $this->get_template_list_for_dropdown(true),
		    ]
	    );

	    $this->add_control(
		    'eael_product_slider_show_title',
		    [
			    'label' => __('Show Title', 'essential-addons-elementor'),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => __('Show', 'essential-addons-elementor'),
			    'label_off' => __('Hide', 'essential-addons-elementor'),
			    'return_value' => 'yes',
			    'default' => 'yes',
		    ]
	    );

	    $this->add_control(
		    'eael_product_slider_title_tag',
		    [
			    'label' => __('Title Tag', 'essential-addons-elementor'),
			    'type' => Controls_Manager::SELECT,
			    'default' => 'h2',
			    'options' => [
				    'h1' => __('H1', 'essential-addons-elementor'),
				    'h2' => __('H2', 'essential-addons-elementor'),
				    'h3' => __('H3', 'essential-addons-elementor'),
				    'h4' => __('H4', 'essential-addons-elementor'),
				    'h5' => __('H5', 'essential-addons-elementor'),
				    'h6' => __('H6', 'essential-addons-elementor'),
				    'span' => __('Span', 'essential-addons-elementor'),
				    'p' => __('P', 'essential-addons-elementor'),
				    'div' => __('Div', 'essential-addons-elementor'),
			    ],
			    'condition' => [
				    'eael_product_slider_show_title' => 'yes',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_slider_title_length',
		    [
			    'label' => __('Title Length', 'essential-addons-elementor'),
			    'type' => Controls_Manager::NUMBER,
			    'condition' => [
				    'eael_product_slider_show_title' => 'yes',
			    ],
		    ]
	    );
        
        $this->add_control( 'eael_product_slider_rating', [
            'label'        => esc_html__( 'Show Product Rating?', 'essential-addons-elementor' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );
        
        $this->add_control(
            'eael_product_slider_price',
            [
                'label'        => esc_html__( 'Show Product Price?', 'essential-addons-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'eael_product_slider_excerpt',
            [
                'label'        => esc_html__( 'Short Description?', 'essential-addons-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'eael_product_slider_excerpt_length',
            [
                'label'     => __( 'Excerpt Words', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '10',
                'condition' => [
                    'eael_product_slider_excerpt' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_slider_excerpt_expanison_indicator',
            [
                'label'       => esc_html__( 'Expansion Indicator', 'essential-addons-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => '...',
                'condition'   => [
                    'eael_product_slider_excerpt' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'        => 'eael_product_slider_image_size',
                'exclude'     => ['custom'],
                'default'     => 'full',
                'label_block' => true,
                'condition' => [
	                'eael_dynamic_template_layout!' => ['preset-2'],
                ]
            ]
        );
        
        $this->add_control(
            'eael_woo_product_slider_image_stretch',
            [
                'label'        => __( 'Image Stretch', 'essential-addons-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'    => __( 'No', 'essential-addons-elementor' ),
                'return_value' => 'true',
                'default'      => 'yes',
            ]
        );

	    $this->add_control(
			'eael_show_post_terms',
			[
				'label'        => __( 'Show Post Terms', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			]
		);
        
        $this->add_control(
            'eael_post_terms',
            [
                'label'     => __( 'Show Terms From', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'product_cat' => __( 'Category', 'essential-addons-elementor' ),
                    'product_tag'     => __( 'Tags', 'essential-addons-elementor' ),
                ],
                'default'   => 'product_cat',
                'condition' => [
                    'eael_show_post_terms' => 'yes',
                ],
            ]
        );

	    $this->add_control(
		    'eael_product_slider_not_found_msg',
		    [
			    'label'     => __( 'Not Found Message', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::TEXT,
			    'default'   => __( 'Products Not Found', 'essential-addons-elementor' ),
			    'separator' => 'before'
		    ]
	    );

	    $this->add_control(
		    'eael_product_slider_quick_view',
		    [
			    'label'        => esc_html__( 'Show Quick view?', 'essential-addons-elementor' ),
			    'type'         => Controls_Manager::SWITCHER,
			    'return_value' => 'yes',
			    'default'      => 'yes',
		    ]
        );
      
        $this->add_control(
            'eael_product_quick_view_title_tag',
            [
                'label' => __('Quick view Title Tag', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => __('H1', 'essential-addons-elementor'),
                    'h2' => __('H2', 'essential-addons-elementor'),
                    'h3' => __('H3', 'essential-addons-elementor'),
                    'h4' => __('H4', 'essential-addons-elementor'),
                    'h5' => __('H5', 'essential-addons-elementor'),
                    'h6' => __('H6', 'essential-addons-elementor'),
                    'span' => __('Span', 'essential-addons-elementor'),
                    'p' => __('P', 'essential-addons-elementor'),
                    'div' => __('Div', 'essential-addons-elementor'),
                ],
                'condition' => [
                    'eael_product_slider_quick_view' => 'yes',
                ],
            ]
        );

	    $this->add_control(
		    'eael_product_slider_image_clickable',
		    [
			    'label' => esc_html__('Image Clickable?', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SWITCHER,
			    'return_value' => 'yes',
			    'default' => 'no',
		    ]
	    );

        $this->end_controls_section();
    }
    
    protected function eael_woo_product_slider_options() {
        
        $this->start_controls_section(
            'section_additional_options',
            [
                'label' => __( 'Slider Settings', 'essential-addons-elementor' ),
            ]
        );

	    $this->add_control(
		    'show_slider_content_effect',
		    [
			    'label'        => __( 'Enable Content Effect', 'essential-addons-elementor' ),
			    'type'         => Controls_Manager::SWITCHER,
			    'default'      => 'yes',
			    'label_on'     => __( 'Yes', 'essential-addons-elementor' ),
			    'label_off'    => __( 'No', 'essential-addons-elementor' ),
			    'return_value' => 'yes',
		    ]
	    );

	    $this->add_control(
		    'slider_content_effect',
		    [
			    'label'       => __('Content Effect', 'essential-addons-elementor'),
			    'description' => __('Sets transition effect', 'essential-addons-elementor'),
			    'type'        => Controls_Manager::SELECT,
			    'default'     => 'zoomIn',
			    'options'     => [
                    'fadeIn'  => __('FadeIn', 'essential-addons-elementor'),
				    'fadeInUp'  => __('FadeInUp', 'essential-addons-elementor'),
				    'fadeInDown'  => __('FadeInDown', 'essential-addons-elementor'),
				    'fadeInLeft'  => __('FadeInLeft', 'essential-addons-elementor'),
				    'fadeInRight'  => __('FadeInRight', 'essential-addons-elementor'),
				    'slideInUp'  => __('SlideInUp', 'essential-addons-elementor'),
				    'slideInDown'  => __('SlideInDown', 'essential-addons-elementor'),
				    'slideInLeft'  => __('SlideInLeft', 'essential-addons-elementor'),
				    'slideInRight'  => __('SlideInRight', 'essential-addons-elementor'),
				    'zoomIn'     => __('ZoomIn', 'essential-addons-elementor'),
			    ],
			    'condition'=> [
                    'show_slider_content_effect' => 'yes',
                ],
		    ]
	    );
        
        $this->add_control(
            'slider_speed',
            [
                'label'       => __( 'Speed', 'essential-addons-elementor' ),
                'description' => __( 'Duration of transition between slides (in ms)',
                    'essential-addons-elementor' ),
                'type'        => Controls_Manager::SLIDER,
                'default'     => ['size' => 400],
                'range'       => [
                    'px' => [
                        'min'  => 100,
                        'max'  => 3000,
                        'step' => 1,
                    ],
                ],
                'size_units'  => '',
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label'        => __( 'Autoplay', 'essential-addons-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'    => __( 'No', 'essential-addons-elementor' ),
                'return_value' => 'yes',
            ]
        );
        
        $this->add_control(
            'autoplay_speed',
            [
                'label'      => __( 'Autoplay Speed', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => 2000],
                'range'      => [
                    'px' => [
                        'min'  => 500,
                        'max'  => 5000,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'condition'  => [
                    'autoplay' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'pause_on_hover',
            [
                'label'        => __( 'Pause On Hover', 'essential-addons-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'    => __( 'No', 'essential-addons-elementor' ),
                'return_value' => 'yes',
                'condition'    => [
                    'autoplay' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'infinite_loop',
            [
                'label'        => __( 'Infinite Loop', 'essential-addons-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'    => __( 'No', 'essential-addons-elementor' ),
                'return_value' => 'yes',
            ]
        );
        
        $this->add_control(
            'grab_cursor',
            [
                'label'        => __( 'Grab Cursor', 'essential-addons-elementor' ),
                'description'  => __( 'Shows grab cursor when you hover over the slider',
                    'essential-addons-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Show', 'essential-addons-elementor' ),
                'label_off'    => __( 'Hide', 'essential-addons-elementor' ),
                'return_value' => 'yes',
            ]
        );
        
        $this->add_control(
            'navigation_heading',
            [
                'label'     => __( 'Navigation', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'arrows',
            [
                'label'        => __( 'Arrows', 'essential-addons-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'    => __( 'No', 'essential-addons-elementor' ),
                'return_value' => 'yes',
            ]
        );
        
        $this->add_control(
            'dots',
            [
                'label'        => __( 'Dots', 'essential-addons-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'    => __( 'No', 'essential-addons-elementor' ),
                'return_value' => 'yes',
            ]
        );

	    $this->add_control(
		    'image_dots',
		    [
			    'label'                 => __('Image Dots', 'essential-addons-elementor'),
			    'type'                  => Controls_Manager::SWITCHER,
			    'label_on'              => __('Yes', 'essential-addons-elementor'),
			    'label_off'             => __('No', 'essential-addons-elementor'),
			    'return_value'          => 'yes',
			    'condition' => [
				    'dots'    => 'yes'
			    ]
		    ]
	    );


	    $this->add_responsive_control(
		    'image_dots_visibility',
		    [
			    'label' => __('Image Dots Visibility', 'essential-addons-elementor'),
			    'type' => \Elementor\Controls_Manager::SWITCHER,
			    'label_on' => __('Show', 'essential-addons-elementor'),
			    'label_off' => __('Hide', 'essential-addons-elementor'),
			    'return_value' => 'yes',
			    'default' => 'yes',
			    'condition' => [
				    'dots'    => 'yes',
				    'image_dots'    => 'yes'
			    ]
		    ]
	    );

	    $this->add_control(
		    'direction',
		    [
			    'label'     => __( 'Direction', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::SELECT,
			    'default'   => 'left',
			    'options'   => [
				    'left'  => __( 'Left', 'essential-addons-elementor' ),
				    'right' => __( 'Right', 'essential-addons-elementor' ),
			    ],
			    'separator' => 'before',
		    ]
	    );


        
        $this->end_controls_section();
    }
    
    protected function eael_woo_product_slider_query() {
        $this->start_controls_section( 'eael_section_product_slider_query', [
            'label' => esc_html__( 'Query', 'essential-addons-elementor' ),
        ] );
        
        $this->add_control( 'eael_product_slider_product_filter', [
            'label'   => esc_html__( 'Filter By', 'essential-addons-elementor' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'recent-products',
            'options' => $this->eael_get_product_filterby_options(),
        ] );
        
        $this->add_control( 'orderby', [
            'label'   => __( 'Order By', 'essential-addons-elementor' ),
            'type'    => Controls_Manager::SELECT,
            'options' => $this->eael_get_product_orderby_options(),
            'default' => 'date',
        
        ] );
        
        $this->add_control( 'order', [
            'label'   => __( 'Order', 'essential-addons-elementor' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'asc'  => 'Ascending',
                'desc' => 'Descending',
            ],
            'default' => 'desc',
        
        ] );
        
        $this->add_control( 'eael_product_slider_products_count', [
            'label'   => __( 'Products Count', 'essential-addons-elementor' ),
            'type'    => Controls_Manager::NUMBER,
            'default' => 4,
            'min'     => 1,
            'max'     => 1000,
            'step'    => 1,
        ] );
        
        $this->add_control( 'product_offset', [
            'label'   => __( 'Offset', 'essential-addons-elementor' ),
            'type'    => Controls_Manager::NUMBER,
            'default' => 0,
        ] );

	    $taxonomies = get_taxonomies(['object_type' => ['product']], 'objects');
	    foreach ($taxonomies as $taxonomy => $object) {
		    if (!isset($object->object_type[0])) {
			    continue;
		    }

		    $this->add_control(
			    $taxonomy . '_ids',
			    [
				    'label' => $object->label,
				    'type' => Controls_Manager::SELECT2,
				    'label_block' => true,
				    'multiple' => true,
				    'object_type' => $taxonomy,
				    'options' => wp_list_pluck(get_terms($taxonomy), 'name', 'term_id'),
			    ]
		    );
	    }
        
        $this->end_controls_section();
    }
    
    protected function eael_product_action_buttons() {

    }
    
    protected function eael_product_badges() {
        $this->start_controls_section(
            'eael_section_product_badges',
            [
                'label' => esc_html__( 'Sale / Stock Out Badge', 'essential-addons-elementor' ),
            
            ]
        );
        $this->add_control(
            'eael_product_sale_badge_preset',
            [
                'label'   => esc_html__( 'Style Preset', 'essential-addons-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Select Preset', 'essential-addons-elementor' ),
                    'sale-preset-1' => esc_html__( 'Preset 1', 'essential-addons-elementor' ),
                    'sale-preset-2' => esc_html__( 'Preset 2', 'essential-addons-elementor' ),
                    'sale-preset-3' => esc_html__( 'Preset 3', 'essential-addons-elementor' ),
                    'sale-preset-4' => esc_html__( 'Preset 4', 'essential-addons-elementor' ),
                ]
            ]
        );
        
        $this->add_control(
            'eael_product_sale_badge_alignment',
            [
                'label'     => __( 'Alignment', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'  => [
                        'title' => __( 'Left', 'essential-addons-elementor' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'essential-addons-elementor' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'condition' => [
                    'eael_product_sale_badge_preset' => 'sale-preset-4'
                ]
            ]
        );

	    $this->add_control(
		    'eael_product_sale_badge_position',
		    [
			    'label'     => esc_html__( 'Position', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
	        ]
        );

	    $this->add_control(
		    'eael_product_sale_badge_position_left',
		    [
			    'label'     => esc_html__( 'Horizontal (%)', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::SLIDER,
			    'range'     => [
				    '%' => [
					    'max' => 100,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider-container .eael-onsale' => 'left: {{SIZE}}%; transform: translateX(-{{SIZE}}%);',
			    ],

		    ]
	    );

	    $this->add_control(
		    'eael_product_sale_badge_position_top',
		    [
			    'label'     => esc_html__( 'Vertical (%)', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::SLIDER,
			    'range'     => [
				    '%' => [
					    'max' => 100,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider-container .eael-onsale' => 'top: {{SIZE}}%; transform: translateY(-{{SIZE}}%);',
			    ],

		    ]
	    );

	    $this->add_control(
		    'eael_product_carousel_sale_text',
		    [
			    'label'       => esc_html__( 'Sale Text', 'essential-addons-elementor' ),
			    'type'        => Controls_Manager::TEXT,
                'separator' => 'before',
		    ]
	    );

	    $this->add_control(
		    'eael_product_carousel_stockout_text',
		    [
			    'label'       => esc_html__( 'Stock Out Text', 'essential-addons-elementor' ),
			    'type'        => Controls_Manager::TEXT,
		    ]
	    );
        
        $this->end_controls_section();
    }
    
    protected function init_style_product_controls() {
        $this->start_controls_section(
            'eael_product_slider_styles',
            [
                'label' => esc_html__( 'Products', 'essential-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

	    $this->add_responsive_control(
		    'eael_product_slider_height',
		    [
			    'label'     => esc_html__( 'Height', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::SLIDER,
			    'range'     => [
				    'px' => [
					    'min' => 300,
					    'max' => 800,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider-container .eael-product-slider' => 'min-height: {{SIZE}}px;',
			    ],
			    'condition' => [
				    'eael_dynamic_template_layout' => ['preset-2'],
			    ]
		    ]
	    );
        
        $this->add_control(
            'eael_product_slider_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-product-slider-container .eael-product-slider' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_dynamic_template_layout' => ['preset-2'],
                ]
            ]
        );

	    $this->add_control(
		    'eael_product_slider_column_reverse',
		    [
			    'label' => __('Reverse column', 'essential-addons-elementor'),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => __('Yes', 'essential-addons-elementor'),
			    'label_off' => __('No', 'essential-addons-elementor'),
			    'return_value' => 'yes',
			    'default' => 'no',
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_product_slider_alignment',
		    [
			    'label'     => __( 'Alignment', 'essential-addons-elementor' ),
			    'type'      => \Elementor\Controls_Manager::CHOOSE,
			    'options'   => [
				    'left'   => [
					    'title' => __( 'Left', 'essential-addons-elementor' ),
					    'icon'  => 'eicon-text-align-left',
				    ],
				    'center' => [
					    'title' => __( 'Center', 'essential-addons-elementor' ),
					    'icon'  => 'eicon-text-align-center',
				    ],
				    'right'  => [
					    'title' => __( 'Right', 'essential-addons-elementor' ),
					    'icon'  => 'eicon-text-align-right',
				    ],
			    ],
			    'toggle'    => true,
			    'selectors' => [
				    '{{WRAPPER}} .eael-product-slider .product-details-wrap' => 'text-align: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_slider_vertical_position',
		    [
			    'label'                 => __( 'Vertical Position', 'essential-addons-elementor' ),
			    'type'                  => Controls_Manager::CHOOSE,
			    'label_block'           => false,
			    'options'               => [
				    'top'       => [
					    'title' => __( 'Top', 'essential-addons-elementor' ),
					    'icon'  => 'eicon-v-align-top',
				    ],
				    'middle'    => [
					    'title' => __( 'Middle', 'essential-addons-elementor' ),
					    'icon'  => 'eicon-v-align-middle',
				    ],
				    'bottom'    => [
					    'title' => __( 'Bottom', 'essential-addons-elementor' ),
					    'icon'  => 'eicon-v-align-bottom',
				    ],
			    ],
			    'selectors'             => [
				    '{{WRAPPER}} .eael-product-slider .product-details-wrap' => 'align-self: {{VALUE}}',
				    '{{WRAPPER}} .eael-woo-product-slider-container:not(.preset-2) .eael-product-slider .product-image-wrap' => 'align-self: {{VALUE}}',
			    ],
			    'selectors_dictionary'  => [
				    'top'      => 'flex-start',
				    'middle'   => 'center',
				    'bottom'   => 'flex-end',
			    ],
			    'condition' => [
				    'eael_dynamic_template_layout!' => ['preset-2'],
			    ]
		    ]
	    );
        
	    $this->start_controls_tabs( 'eael_product_slider_tabs', [
			    'condition' => [
				    'eael_dynamic_template_layout' => ['preset-2'],
			    ]
            ]
        );

	    $this->start_controls_tab( 'eael_product_slider_tabs_normal',
		    ['label' => esc_html__( 'Normal', 'essential-addons-elementor' )] );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name'           => 'eael_product_slider_border',
			    'fields_options' => [
				    'border' => [
					    'default' => 'solid',
				    ],
				    'width'  => [
					    'default' => [
						    'top'      => '1',
						    'right'    => '1',
						    'bottom'   => '1',
						    'left'     => '1',
						    'isLinked' => false,
					    ],
				    ],
				    'color'  => [
					    'default' => '#eee',
				    ],
			    ],
			    'selector'       => '{{WRAPPER}} .eael-product-slider',
		    ]
	    );

	    $this->add_group_control(
		    \Elementor\Group_Control_Box_Shadow::get_type(),
		    [
			    'name'     => 'eael_product_slider_shadow',
			    'label'    => __( 'Box Shadow', 'essential-addons-elementor' ),
			    'selector' => '{{WRAPPER}} .eael-product-slider',
			    'condition' => [
				    'eael_dynamic_template_layout' => ['preset-2'],
			    ]
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab( 'eael_product_slider_hover_styles',
		    ['label' => esc_html__( 'Hover', 'essential-addons-elementor' )] );

	    $this->add_control(
		    'eael_product_slider_hover_border_color',
		    [
			    'label'     => esc_html__( 'Border Color', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .eael-product-slider:hover' => 'border-color: {{VALUE}};',
			    ],
			    'condition' => [
				    'eael_product_slider_border_border!' => '',
			    ],
		    ]
	    );
	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name'     => 'eael_product_slider_box_shadow_hover',
			    'label'    => __( 'Box Shadow', 'essential-addons-elementor' ),
			    'selector' => '{{WRAPPER}} .eael-product-slider:hover',
		    ]
	    );
	    $this->end_controls_tab();

	    $this->end_controls_tabs();

	    $this->add_control(
		    'eael_product_slider_border_radius',
		    [
			    'label'     => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::DIMENSIONS,
			    'selectors' => [
				    '{{WRAPPER}} .eael-product-slider' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
			    ],
			    'condition' => [
				    'eael_dynamic_template_layout' => ['preset-2'],
			    ]
		    ]
	    );
        
        $this->add_control(
            'eael_product_slider_details_heading',
            [
                'label'     => __( 'Image', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name'           => 'eael_product_slider_image_border',
			    'selector'       => '{{WRAPPER}} .eael-product-slider .image-wrap img',
		    ]
	    );

	    $this->add_control(
		    'eael_product_slider_image_border_radius',
		    [
			    'label'     => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::DIMENSIONS,
			    'selectors' => [
				    '{{WRAPPER}} .eael-product-slider .image-wrap img, {{WRAPPER}} .eael-product-slider .image-wrap' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name'     => 'eael_product_slider_image_box_shadow',
			    'label'    => __( 'Box Shadow', 'essential-addons-elementor' ),
			    'selector' => '{{WRAPPER}} .eael-product-slider .image-wrap',
			    'condition' => [
				    'eael_dynamic_template_layout!' => ['preset-2'],
			    ]
		    ]
	    );

        $this->add_control(
            'eael_product_slider_content_heading',
            [
                'label'     => __( 'Content', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'eael_product_slider_content_padding',
            [
                'label'      => __( 'Padding', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-product-slider .product-details-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function style_color_typography() {
        
        $this->start_controls_section(
            'eael_section_product_slider_typography',
            [
                'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'eael_product_slider_product_title_heading',
            [
                'label' => __( 'Product Title', 'essential-addons-elementor' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'eael_product_slider_product_title_color',
            [
                'label'     => esc_html__( 'Product Title Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce-loop-product__title, {{WRAPPER}} .eael-product-slider .eael-product-title *' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_slider_title_typo',
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce-loop-product__title, {{WRAPPER}} .eael-product-slider .eael-product-title *',
            ]
        );
        
        $this->add_control(
            'eael_product_slider_product_price_heading',
            [
                'label' => __( 'Product Price', 'essential-addons-elementor' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'eael_product_slider_price_color',
            [
                'label'     => esc_html__( 'Product Price Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .price, {{WRAPPER}} .eael-product-slider .eael-product-price' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider .price del, {{WRAPPER}} .eael-product-slider .eael-product-price del' => 'color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name'     => 'eael_product_slider_product_price_typography',
			    'selector' => '{{WRAPPER}} .eael-product-slider .eael-product-price, {{WRAPPER}} .eael-product-slider .eael-product-price del',
		    ]
	    );

        $this->add_control(
            'eael_product_slider_sale_price_color',
            [
                'label'     => esc_html__( 'Sale Price Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .price ins, {{WRAPPER}} .eael-product-slider .eael-product-price ins' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_slider_product_sale_price_typo',
                'selector' => '{{WRAPPER}} .eael-product-slider .price ins, {{WRAPPER}} .eael-product-slider .eael-product-price ins',
            ]
        );
        
        $this->add_control(
            'eael_product_slider_rating_heading',
            [
                'label' => __( 'Star Rating', 'essential-addons-elementor' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'eael_product_slider_rating_color',
            [
                'label'     => esc_html__( 'Rating Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f2b01e',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .star-rating::before'      => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider .star-rating span::before' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_product_slider_rating_size',
            [
                'label'     => esc_html__( 'Icon Size', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-product-slider-container .woocommerce ul.products .product .star-rating' => 'font-size: {{SIZE}}px!important;',
                ],
            
            ]
        );
        
        $this->add_control(
            'eael_product_slider_desc_heading',
            [
                'label'     => __( 'Product Description', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_product_slider_excerpt' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_slider_desc_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .eael-product-excerpt' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_slider_excerpt' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_product_slider_desc_typography',
                'selector'  => '{{WRAPPER}} .eael-product-slider .eael-product-excerpt',
                'condition' => [
                    'eael_product_slider_excerpt' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_slider_sale_badge_heading',
            [
                'label' => __( 'Sale Badge', 'essential-addons-elementor' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'eael_product_slider_sale_badge_color',
            [
                'label'     => esc_html__( 'Sale Badge Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_slider_sale_badge_background',
            [
                'label'     => esc_html__( 'Sale Badge Background', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .onsale, {{WRAPPER}} .eael-product-slider .eael-onsale' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider .eael-onsale:not(.outofstock).sale-preset-4:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_slider_sale_badge_typo',
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale:not(.outofstock)',
            ]
        );
        
        // stock out badge
        $this->add_control(
            'eael_product_slider_stock_out_badge_heading',
            [
                'label' => __( 'Stock Out Badge', 'essential-addons-elementor' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_control(
            'eael_product_slider_stock_out_badge_color',
            [
                'label'     => esc_html__( 'Stock Out Badge Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_slider_stock_out_badge_background',
            [
                'label'     => esc_html__( 'Stock Out Badge Background', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ff2a13',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock.sale-preset-4:after'                                                => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_slider_stock_out_badge_typo',
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function eael_woo_product_slider_buttons_style() {
        $this->start_controls_section(
            'eael_section_product_slider_buttons_styles',
            [
                'label' => esc_html__( 'Button', 'essential-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'eael_product_slider_buttons_width',
            [
                'label'     => esc_html__( 'Width', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .icons-wrap li a' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_dynamic_template_layout!' => 'preset-3',
                ]
            ]
        );
        
        $this->add_control(
            'eael_product_slider_buttons_height',
            [
                'label'     => esc_html__( 'Height', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .icons-wrap' => 'height: {{SIZE}}px;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_slider_buttons_icon_size',
            [
                'label'     => esc_html__( 'Icons Size', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .icons-wrap li a i, {{WRAPPER}} .eael-product-slider .icons-wrap li.add-to-cart a:before' => 'font-size: {{SIZE}}px;',
                ],
            ]
        );
        
        $this->start_controls_tabs( 'eael_product_slider_buttons_style_tabs' );
        
        $this->start_controls_tab( 'eael_product_slider_buttons_style_tabs_normal',
            ['label' => esc_html__( 'Normal', 'essential-addons-elementor' )] );
        
        $this->add_control(
            'eael_product_slider_buttons_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .icons-wrap li a' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_slider_buttons_background',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .icons-wrap.block-style' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider .icons-wrap li a'        => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'eael_product_slider_buttons_border',
                'selector'  => '{{WRAPPER}} .eael-product-slider .button.add_to_cart_button, {{WRAPPER}} .eael-product-slider .icons-wrap li a',
            ]
        );
        $this->add_control(
            'eael_product_slider_buttons_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .icons-wrap li a'       => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_slider_buttons_top_spacing',
            [
                'label'     => esc_html__( 'Top Spacing', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .icons-wrap' => 'margin-top: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_dynamic_template_layout!' => ['preset-4', 'preset-2'],
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 'eael_product_slider_buttons_hover_styles',
            ['label' => esc_html__( 'Hover', 'essential-addons-elementor' )] );
        
        $this->add_control(
            'eael_product_slider_buttons_hover_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .icons-wrap li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_slider_buttons_hover_background',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .icons-wrap li a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_slider_buttons_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .icons-wrap li a:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_slider_buttons_border_border!' => '',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

	    // Cart Button
	    $this->add_control(
		    'eael_product_slider_cart_button',
		    [
			    'label'     => __( 'Cart Button', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::HEADING,
			    'separator' => 'before',
			    'condition' => [
				    'eael_dynamic_template_layout' => ['preset-3','preset-4'],
			    ]
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name'     => 'eael_product_slider_cart_button_typo',
			    'label'    => __( 'Typography', 'essential-addons-elementor' ),
			    'selector' => '{{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button.add_to_cart_button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .added_to_cart',
			    'condition' => [
				    'eael_dynamic_template_layout' => ['preset-3','preset-4'],
			    ]
		    ]
	    );

	    $this->start_controls_tabs( 'eael_product_slider_cart_button_style_tabs', [
		    'condition' => [
			    'eael_dynamic_template_layout' => ['preset-3','preset-4'],
		    ]
        ] );

	    $this->start_controls_tab( 'eael_product_slider_cart_button_style_tabs_normal',
		    ['label' => esc_html__( 'Normal', 'essential-addons-elementor' )] );

	    $this->add_control(
		    'eael_product_slider_cart_button_color',
		    [
			    'label'     => esc_html__( 'Color', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button.add_to_cart_button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .added_to_cart' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_slider_cart_button_background',
		    [
			    'label'     => esc_html__( 'Background Color', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button.add_to_cart_button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .added_to_cart' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name'     => 'eael_product_slider_cart_button_border',
			    'selector' => '{{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button.add_to_cart_button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .added_to_cart',
		    ]
	    );
	    $this->add_control(
		    'eael_product_slider_cart_button_border_radius',
		    [
			    'label'     => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::SLIDER,
			    'range'     => [
				    'px' => [
					    'max' => 100,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button.add_to_cart_button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .added_to_cart' => 'border-radius: {{SIZE}}px;',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    \Elementor\Group_Control_Box_Shadow::get_type(),
		    [
			    'name'     => 'eael_product_slider_cart_button_shadow',
			    'label'    => __( 'Box Shadow', 'essential-addons-elementor' ),
			    'selector' => '{{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button.add_to_cart_button, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .added_to_cart',
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab( 'eael_product_slider_cart_button_hover_styles',
		    ['label' => esc_html__( 'Hover', 'essential-addons-elementor' )] );

	    $this->add_control(
		    'eael_product_slider_cart_button_hover_color',
		    [
			    'label'     => esc_html__( 'Color', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#F5EAFF',
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button:hover, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button.add_to_cart_button:hover, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .added_to_cart:hover' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_slider_cart_button_hover_background',
		    [
			    'label'     => esc_html__( 'Background Color', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#F12DE0',
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button:hover, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button.add_to_cart_button:hover, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .added_to_cart:hover' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_slider_cart_button_hover_border_color',
		    [
			    'label'     => esc_html__( 'Border Color', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button:hover, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .button.add_to_cart_button:hover, {{WRAPPER}} .eael-woo-product-slider .eael-product-slider .eael-add-to-cart-button .added_to_cart:hover' => 'border-color: {{VALUE}};',
			    ],
			    'condition' => [
				    'eael_product_slider_cart_button_border_border!' => '',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->end_controls_tabs();
        
        $this->end_controls_section();
    }
    
    protected function eael_product_view_popup_style() {
        $this->start_controls_section(
            'eael_product_popup',
            [
                'label' => __( 'Popup', 'essential-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'eael_product_popup_title',
            [
                'label' => __( 'Title', 'essential-addons-elementor' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_popup_title_typography',
                'label'    => __( 'Typography', 'essential-addons-elementor' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}} div.product .product_title',
            ]
        );
        
        $this->add_control(
            'eael_product_popup_title_color',
            [
                'label'     => __( 'Title Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#252525',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .eael-product-quick-view-title.product_title.entry-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_price',
            [
                'label'     => __( 'Price', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_popup_price_typography',
                'label'    => __( 'Typography', 'essential-addons-elementor' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}} div.product .price .amount',
            ]
        );
        
        $this->add_control(
            'eael_product_popup_price_color',
            [
                'label'     => __( 'Price Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0242e4',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} div.product .price' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_sale_price_color',
            [
                'label'     => __( 'Sale Price Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ff2a13',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} div.product .price ins' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_content',
            [
                'label'     => __( 'Content', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_popup_content_typography',
                'label'    => __( 'Typography', 'essential-addons-elementor' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}} div.product .woocommerce-product-details__short-description',
            ]
        );
        
        $this->add_control(
            'eael_product_popup_content_color',
            [
                'label'     => __( 'Content Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#707070',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_control(
		    'eael_product_popup_review_color',
		    [
			    'label'     => __( 'Review Color', 'essential-addons-elementor' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .woocommerce-product-rating .star-rating::before, .eael-popup-details-render{{WRAPPER}} .woocommerce-product-rating .star-rating span::before' => 'color: {{VALUE}};',
			    ],
		    ]
	    );
        
        $this->add_control(
            'eael_product_popup_table_border_color',
            [
                'label'     => __( 'Border Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ccc',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} div.product table tbody tr, {{WRAPPER}} .eael-product-popup.woocommerce div.product .product_meta' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        // Sale
        $this->add_control(
            'eael_product_popup_sale_style',
            [
                'label'     => __( 'Sale / Stockout Badge', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_popup_sale_typo',
                'label'    => __( 'Typography', 'essential-addons-elementor' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}} .eael-onsale',
            ]
        );
        $this->add_control(
            'eael_product_popup_sale_color',
            [
                'label'     => __( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .eael-onsale' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
        $this->add_control(
            'eael_product_popup_sale_bg_color',
            [
                'label'     => __( 'Background Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .onsale, .eael-popup-details-render{{WRAPPER}} .eael-onsale' => 'background-color: {{VALUE}} !important;',
                    '.eael-popup-details-render{{WRAPPER}} .eael-onsale.sale-preset-4:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
                ],
            ]
        );
        
        // Quantity
        $this->add_control(
            'eael_product_popup_quantity',
            [
                'label'     => __( 'Quantity', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_popup_quantity_typo',
                'label'    => __( 'Typography', 'essential-addons-elementor' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a',
            ]
        );
        
        $this->add_control(
            'eael_product_popup_quantity_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_quantity_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_quantity_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'border-color: {{VALUE}};',
                    // OceanWP
                    '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty:focus, .eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .plus, .eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .minus' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        // Cart Button
        $this->add_control(
            'eael_product_popup_cart_button',
            [
                'label'     => __( 'Cart Button', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_popup_cart_button_typo',
                'label'    => __( 'Typography', 'essential-addons-elementor' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt',
            ]
        );
        
        $this->start_controls_tabs( 'eael_product_popup_cart_button_style_tabs' );
        
        $this->start_controls_tab( 'eael_product_popup_cart_button_style_tabs_normal',
            ['label' => esc_html__( 'Normal', 'essential-addons-elementor' )] );
        
        $this->add_control(
            'eael_product_popup_cart_button_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
	                '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_cart_button_background',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#8040FF',
                'selectors' => [
	                '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_product_popup_cart_button_border',
                'selector' => '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt',
            ]
        );
        $this->add_control(
            'eael_product_popup_cart_button_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
	                '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 'eael_product_popup_cart_button_hover_styles',
            ['label' => esc_html__( 'Hover', 'essential-addons-elementor' )] );
        
        $this->add_control(
            'eael_product_popup_cart_button_hover_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F5EAFF',
                'selectors' => [
	                '.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_cart_button_hover_background',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#F12DE0',
                'selectors' => [
	                '.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_cart_button_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
	                '.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_popup_cart_button_border_border!' => '',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        // SKU
        $this->add_control(
            'eael_product_popup_sku_style',
            [
                'label'     => __( 'SKU', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_popup_sku_typo',
                'label'    => __( 'Typography', 'essential-addons-elementor' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}} .product_meta',
            ]
        );
        $this->add_control(
            'eael_product_popup_sku_title_color',
            [
                'label'     => __( 'Title Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .product_meta' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_product_popup_sku_content_color',
            [
                'label'     => __( 'Content Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .product_meta .sku, .eael-popup-details-render{{WRAPPER}} .product_meta a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_product_popup_sku_hover_color',
            [
                'label'     => __( 'Hover Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .product_meta a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_close_button_style',
            [
                'label'     => __( ' Close Button', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'eael_product_popup_close_button_icon_size',
            [
                'label'      => __( 'Icon Size', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'eael_product_popup_close_button_size',
            [
                'label'      => __( 'Button Size', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'max-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_close_button_color',
            [
                'label'     => __( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_close_button_bg',
            [
                'label'     => __( 'Background', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'background-color: {{VALUE}}!important;',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_popup_close_button_border_radius',
            [
                'label'      => __( 'Border Radius', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_product_popup_close_button_box_shadow',
                'label'    => __( 'Box Shadow', 'essential-addons-elementor' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close',
            ]
        );
        
        $this->add_responsive_control(
            'eael_product_popup_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '.eael-popup-details-render{{WRAPPER}}.eael-product-popup-details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'eael_product_popup_background',
                'label'    => __( 'Background', 'essential-addons-elementor' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '.eael-popup-details-render{{WRAPPER}}.eael-product-popup-details',
                'exclude'  => [
                    'image',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_product_popup_box_shadow',
                'label'    => __( 'Box Shadow', 'essential-addons-elementor' ),
                'selector' => '.eael-popup-details-render{{WRAPPER}}.eael-product-popup-details',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function eael_woo_product_slider_dots() {
        /**
         * Style Tab: Dots
         */
        $this->start_controls_section(
            'section_dots_style',
            [
                'label'     => __( 'Dots', 'essential-addons-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'dots' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'dots_preset',
            [
                'label'   => __( 'Preset', 'essential-addons-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'dots-preset-1'  => __( 'Preset 1', 'essential-addons-elementor' ),
                    'dots-preset-2'  => __( 'Preset 2', 'essential-addons-elementor' ),
                    'dots-preset-3'  => __( 'Preset 3', 'essential-addons-elementor' ),
                    'dots-preset-4'  => __( 'Preset 4', 'essential-addons-elementor' ),
                ],
                'default' => 'dots-preset-1',
            ]
        );

        $this->add_control(
            'dots_position',
            [
                'label'   => __( 'Position', 'essential-addons-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'inside'  => __( 'Inside', 'essential-addons-elementor' ),
                    'outside' => __( 'Outside', 'essential-addons-elementor' ),
                ],
                'default' => 'outside',
            ]
        );

        $this->add_control(
            'is_use_dots_custom_width_height',
            [
                'label'        => __( 'Use Custom Width/Height?', 'essential-addons-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'    => __( 'No', 'essential-addons-elementor' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'dots_width',
            [
                'label'      => __( 'Width', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 2,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'is_use_dots_custom_width_height' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_height',
            [
                'label'      => __( 'Height', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 2,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'is_use_dots_custom_width_height' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_size',
            [
                'label'      => __( 'Size', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 2,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'is_use_dots_custom_width_height' => '',
                    'dots_preset!' => 'dots-preset-1',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_spacing',
            [
                'label'      => __( 'Spacing', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 30,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            [
                'label' => __( 'Normal', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
            'dots_color_normal',
            [
                'label'     => __( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'dots_border_normal',
                'label'       => __( 'Border', 'essential-addons-elementor' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
            ]
        );

        $this->add_control(
            'dots_border_radius_normal',
            [
                'label'      => __( 'Border Radius', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_padding',
            [
                'label'              => __( 'Padding', 'essential-addons-elementor' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => ['px', 'em', '%'],
                'allowed_dimensions' => 'vertical',
                'placeholder'        => [
                    'top'    => '',
                    'right'  => 'auto',
                    'bottom' => '',
                    'left'   => 'auto',
                ],
                'selectors'          => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            [
                'label' => __( 'Hover', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
            'dots_color_hover',
            [
                'label'     => __( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_border_color_hover',
            [
                'label'     => __( 'Border Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_active',
            [
                'label' => __( 'Active', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
            'active_dot_color_normal',
            [
                'label'     => __( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'background: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'active_dots_width',
            [
                'label'      => __( 'Width', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 2,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'active_dots_height',
            [
                'label'      => __( 'Height', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 2,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => '',
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'active_dots_radius',
            [
                'label'      => __( 'Border Radius', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'active_dots_shadow',
                'label'    => __( 'Shadow', 'essential-addons-elementor' ),
                'selector' => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function eael_woo_product_slider_image_dots(){
	    /**
	     * Image Dots
	     */
	    $this->start_controls_section(
		    'section_image_dots_style',
		    [
			    'label'                 => __('Images Dots', 'essential-addons-elementor'),
			    'tab'                   => Controls_Manager::TAB_STYLE,
			    'condition'             => [
				    'image_dots'      => 'yes',
			    ],
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_image_dots_width',
		    [
			    'label' => __('Width', 'essential-addons-elementor'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 1000,
					    'step' => 5,
				    ],
				    '%' => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'devices' => ['desktop', 'tablet', 'mobile'],
			    'default' => [
				    'unit' => 'px',
				    'size' => 350,
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider-gallary-pagination' => 'width: {{SIZE}}{{UNIT}} !important;',
			    ],
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_image_dots_height',
		    [
			    'label' => __('Height', 'essential-addons-elementor'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 1000,
					    'step' => 5,
				    ],
				    '%' => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'devices' => ['desktop', 'tablet', 'mobile'],
			    'default' => [
				    'unit' => 'px',
				    'size' => 100,
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider-gallary-pagination' => 'height: {{SIZE}}{{UNIT}} !important;',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_image_dots_image_size',
		    [
			    'label' => __('Image Size', 'essential-addons-elementor'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 500,
					    'step' => 5,
				    ],
				    '%' => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'devices' => ['desktop', 'tablet', 'mobile'],
			    'default' => [
				    'unit' => 'px',
				    'size' => 100,
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider-gallary-pagination img' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
			    ],
		    ]
	    );
	    $this->add_control(
		    'eael_image_dots_image_border_radius',
		    [
			    'label' => __('Border Radius', 'essential-addons-elementor'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 1000,
					    'step' => 5,
				    ],
				    '%' => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-woo-product-slider-gallary-pagination img' => 'border-radius: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->end_controls_section();
    }

    protected function eael_woo_product_slider_arrows() {
        /**
         * Style Tab: Arrows
         */
        $this->start_controls_section(
            'section_arrows_style',
            [
                'label'     => __( 'Arrows', 'essential-addons-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow',
            [
                'label'       => __( 'Choose Arrow', 'essential-addons-elementor' ),
                'type'        => Controls_Manager::SELECT,
                'label_block' => true,
                'default'     => 'fa fa-angle-right',
                'options'     => [
                    'fa fa-angle-right'          => __( 'Angle', 'essential-addons-elementor' ),
                    'fa fa-angle-double-right'   => __( 'Double Angle', 'essential-addons-elementor' ),
                    'fa fa-chevron-right'        => __( 'Chevron', 'essential-addons-elementor' ),
                    'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'essential-addons-elementor' ),
                    'fa fa-arrow-right'          => __( 'Arrow', 'essential-addons-elementor' ),
                    'fa fa-long-arrow-right'     => __( 'Long Arrow', 'essential-addons-elementor' ),
                    'fa fa-caret-right'          => __( 'Caret', 'essential-addons-elementor' ),
                    'fa fa-caret-square-o-right' => __( 'Caret Square', 'essential-addons-elementor' ),
                    'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'essential-addons-elementor' ),
                    'fa fa-arrow-circle-o-right' => __( 'Arrow Circle O', 'essential-addons-elementor' ),
                    'fa fa-toggle-right'         => __( 'Toggle', 'essential-addons-elementor' ),
                    'fa fa-hand-o-right'         => __( 'Hand', 'essential-addons-elementor' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_size',
            [
                'label'      => __( 'Arrows Size', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => '40'],
                'range'      => [
                    'px' => [
                        'min'  => 15,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_icon_size',
            [
                'label'      => __( 'Icon Size', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => ['size' => '22'],
                'range'      => [
                    'px' => [
                        'min'  => 15,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'left_arrow_position',
            [
                'label'      => __( 'Align Left Arrow', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => -100,
                        'max'  => 40,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'right_arrow_position',
            [
                'label'      => __( 'Align Right Arrow', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => -100,
                        'max'  => 40,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_arrows_normal',
            [
                'label' => __( 'Normal', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_normal',
            [
                'label'     => __( 'Background Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_normal',
            [
                'label'     => __( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'arrows_border_normal',
                'label'       => __( 'Border', 'essential-addons-elementor' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev',
            ]
        );

        $this->add_control(
            'arrows_border_radius_normal',
            [
                'label'      => __( 'Border Radius', 'essential-addons-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrows_hover',
            [
                'label' => __( 'Hover', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_hover',
            [
                'label'     => __( 'Background Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_hover',
            [
                'label'     => __( 'Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_border_color_hover',
            [
                'label'     => __( 'Border Color', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_product_slider_arrow_shadow',
                'label'    => __( 'Box Shadow', 'essential-addons-elementor' ),
                'selector' => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        if ( !function_exists( 'WC' ) ) {
            return;
        }

        $settings = $this->get_settings_for_display();
        // normalize for load more fix
        $widget_id = $this->get_id();
        $settings[ 'eael_widget_id' ] = $widget_id;

        $args = $this->product_query_builder();
        if ( Plugin::$instance->documents->get_current() ) {
            $this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
        }

        // render dom
        $this->add_render_attribute( 'container', [
            'class'          => [
                'swiper-container-wrap',
                'eael-woo-product-slider-container',
                $settings[ 'eael_dynamic_template_layout' ],
            ],
            'id'             => 'eael-product-slider-' . esc_attr( $this->get_id() ),
            'data-widget-id' => $widget_id,
        ] );

        if ( $settings[ 'dots_position' ] ) {
            $this->add_render_attribute( 'container', 'class',
                'swiper-container-wrap-dots-' . $settings[ 'dots_position' ] );
        }

        $this->add_render_attribute(
            'eael-woo-product-slider-wrap',
            [
                'class'           => [
                    'woocommerce',
                    'swiper-container',
                    'eael-woo-product-slider',
                    'swiper-container-' . esc_attr( $this->get_id() ),
                    'eael-product-appender-' . esc_attr( $this->get_id() ),
                ],
                'data-pagination' => '.swiper-pagination-' . esc_attr( $this->get_id() ),
                'data-arrow-next' => '.swiper-button-next-' . esc_attr( $this->get_id() ),
                'data-arrow-prev' => '.swiper-button-prev-' . esc_attr( $this->get_id() ),
                'data-show-effect' => $settings['show_slider_content_effect'],
            ]
        );

        if ( $settings[ 'eael_dynamic_template_layout' ] ) {
            $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'data-type',
                $settings[ 'eael_dynamic_template_layout' ] );
        }

        if ( $settings[ 'eael_woo_product_slider_image_stretch' ] ) {
            $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'class', 'swiper-image-stretch' );
        }

        if ( !empty( $settings[ 'slider_speed' ][ 'size' ] ) ) {
            $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'data-speed',
                $settings[ 'slider_speed' ][ 'size' ] );
        }

        if ( $settings[ 'autoplay' ] == 'yes' && !empty( $settings[ 'autoplay_speed' ][ 'size' ] ) ) {
            $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'data-autoplay',
                $settings[ 'autoplay_speed' ][ 'size' ] );
        } else {
            $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'data-autoplay', '0' );
        }

        if ( $settings[ 'pause_on_hover' ] == 'yes' ) {
            $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'data-pause-on-hover', 'true' );
        }

        if ( $settings[ 'infinite_loop' ] == 'yes' ) {
            $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'data-loop', '1' );
        }
        if ( $settings[ 'grab_cursor' ] == 'yes' ) {
            $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'data-grab-cursor', '1' );
        }
        if ( $settings[ 'arrows' ] == 'yes' ) {
            $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'data-arrows', '1' );
        }
        if ( $settings[ 'dots' ] == 'yes' ) {
            $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'data-dots', '1' );
        }

	    if ( $settings['direction'] == 'right' ) {
		    $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'dir', 'rtl' );
	    }

	    if ( ($settings['show_slider_content_effect'] == 'yes') && !empty($settings['slider_content_effect']) ) {
		    $this->add_render_attribute( 'eael-woo-product-slider-wrap', 'data-animation', $settings['slider_content_effect'] );
	    }

	    $settings['eael_product_slider_title_tag'] = HelperClass::eael_validate_html_tag($settings['eael_product_slider_title_tag']);
        ?>

        <div <?php $this->print_render_attribute_string( 'container' ); ?> >
            <?php
                $template = $this->get_template( $settings[ 'eael_dynamic_template_layout' ] );
                if ( file_exists( $template ) ):
	                $query = new \WP_Query( $args );
	                if ( $query->have_posts() ):
                        echo '<div '.$this->get_render_attribute_string( 'eael-woo-product-slider-wrap' ).'>';
                            do_action( 'eael_woo_before_product_loop' );
                            $settings['eael_page_id'] = get_the_ID();
                            echo '<ul class="swiper-wrapper products">';
                            while ( $query->have_posts() ) {
                                $query->the_post();
                                include( $template );
                            }
                            wp_reset_postdata();
                            echo '</ul>';
                        echo '</div>';

		                /**
		                 * Render Slider Dots!
		                 */

		                if ($settings['image_dots'] === 'yes') {
			                $this->render_image_dots($args);
		                } else {
			                $this->render_dots();
		                }


		                /**
		                 * Render Slider Navigations!
		                 */
		                $this->render_arrows();
                    else:
	                    echo '<p class="eael-no-posts-found">'.$settings['eael_product_slider_not_found_msg'].'</p>';
                    endif;
                else:
	                _e( '<p class="eael-no-posts-found">No layout found!</p>', 'essential-addons-elementor' );
                endif; ?>
            </div>
        <?php
    }

    //changes
    protected function render_dots() {
        $settings = $this->get_settings_for_display();

        if ( $settings[ 'dots' ] == 'yes' ) { ?>
            <!-- Add Pagination -->
            <div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ) .' '. $settings['dots_preset'];
            ?>"></div>
        <?php }
    }

	protected function render_image_dots($args)
	{
		$settings = $this->get_settings_for_display();

		$visibility = '';
		if ($settings['image_dots_visibility'] !== 'yes') {
			$visibility .= ' eael_gallery_pagination_hide_on_desktop';
		}
		if ($settings['image_dots_visibility_mobile'] !== 'yes') {
			$visibility .= ' eael_gallery_pagination_hide_on_mobile';
		}
		if ($settings['image_dots_visibility_tablet'] !== 'yes') {
			$visibility .= ' eael_gallery_pagination_hide_on_tablet';
		}

		$this->add_render_attribute('eael_gallery_pagination_wrapper', [
			'class' => ['swiper-container eael-woo-product-slider-gallary-pagination', $visibility]
		]);


		if ($settings['image_dots'] === 'yes') : ?>

            <div <?php echo $this->get_render_attribute_string('eael_gallery_pagination_wrapper'); ?>>

            <?php
			$query = new \WP_Query( $args );
			if ( $query->have_posts() ) {
				echo '<div class="swiper-wrapper">';
				while ( $query->have_posts() ) {
					$query->the_post();
					$image_arr = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ),'full');
					if(empty($image_arr)){
						$image_arr[0] = wc_placeholder_img_src( 'full' );
					}

                    echo '<div class="swiper-slide">';
                        echo '<div class="swiper-slide-container">';
                            echo '<div class="eael-pagination-thumb">';
                                echo '<img class="eael-thumbnail" src="'.esc_url(current($image_arr)).'">';
                            echo '</div>';
                        echo '</div>';
					echo '</div>';
				}
				wp_reset_postdata();
				echo '</div>';
			}
			?>

            </div>
		<?php
		endif;
	}
    
    /**
     * Render logo slider arrows output on the frontend.
     */
    protected function render_arrows() {
        $settings = $this->get_settings_for_display();
        
        if ( $settings[ 'arrows' ] == 'yes' ) { ?>
            <?php
            if ( $settings[ 'arrow' ] ) {
                $pa_next_arrow = $settings[ 'arrow' ];
                $pa_prev_arrow = str_replace( "right", "left", $settings[ 'arrow' ] );
            } else {
                $pa_next_arrow = 'fa fa-angle-right';
                $pa_prev_arrow = 'fa fa-angle-left';
            }
            ?>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
            </div>
            <div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
            </div>
            <?php
        }
    }

	/**
	 * Build proper query to fetch product data from wp query
	 * @return array
	 */
    public function product_query_builder(){
	    $settings                     = $this->get_settings_for_display();
	    $widget_id                    = $this->get_id();
	    $settings[ 'eael_widget_id' ] = $widget_id;
	    $order_by                     = $settings[ 'orderby' ];
	    $filter                        = $settings[ 'eael_product_slider_product_filter' ];
	    $args                         = [
		    'post_type'      => 'product',
		    'post_status'    => array( 'publish', 'pending', 'future' ),
		    'posts_per_page' => $settings[ 'eael_product_slider_products_count' ] ?: 4,
		    //'order'          => $settings[ 'order' ],
		    'offset'         => $settings[ 'product_offset' ],
		    'tax_query'      => [
			    'relation' => 'AND',
			    [
				    'taxonomy' => 'product_visibility',
				    'field'     => 'name',
				    'terms'    => [ 'exclude-from-search', 'exclude-from-catalog' ],
				    'operator' => 'NOT IN',
			    ],
		    ],
	    ];



	    if ( $filter == 'featured-products' ) {
		    $count                         = isset( $args[ 'tax_query' ] ) ? count( $args[ 'tax_query' ] ) : 0;
		    $args[ 'tax_query' ][ $count ] =
			    [
				    'taxonomy' => 'product_visibility',
				    'field'     => 'name',
				    'terms'    => 'featured',
			    ];
	    }

	    if ( $filter == 'best-selling-products' ) {
		    $args[ 'meta_key' ] = 'total_sales';
		    $args[ 'orderby' ]['meta_value_num']  =  $settings[ 'order' ];
	    }

	    if ( $filter == 'top-products' ) {
		    $args[ 'meta_key' ] = '_wc_average_rating';
		    $args[ 'orderby' ]['meta_value_num']  =  $settings[ 'order' ];
	    }

	    if ( $order_by == '_price' || $order_by == '_sku' ) {
		    $args[ 'orderby' ]['meta_value_num']  = $settings[ 'order' ];
		    $args[ 'meta_key' ] = $order_by;
	    } else {
		    $args[ 'orderby' ][$order_by] = $settings[ 'order' ];
	    }

	    if ( get_option( 'woocommerce_hide_out_of_stock_items' ) == 'yes' ) {
		    $args[ 'meta_query' ]   = [ 'relation' => 'AND' ];
		    $args[ 'meta_query' ][] = [
			    'key'   => '_stock_status',
			    'value' => 'instock'
		    ];
	    }

	    if ( $filter == 'sale-products' ) {
		    $count                          = isset( $args[ 'meta_query' ] ) ? count( $args[ 'meta_query' ] ) : 0;
		    $args[ 'meta_query' ][ $count ] = [
			    'relation' => 'OR',
			    [
				    'key'     => '_sale_price',
				    'value'   => 0,
				    'compare' => '>',
				    'type'    => 'numeric',
			    ],
			    [
				    'key'     => '_min_variation_sale_price',
				    'value'   => 0,
				    'compare' => '>',
				    'type'    => 'numeric',
			    ],
		    ];
	    }


	    $taxonomies      = get_taxonomies( [ 'object_type' => [ 'product' ] ], 'objects' );
	    $tax_query_count = isset( $args[ 'meta_query' ] ) ? count( $args[ 'meta_query' ] ) : 0;
	    foreach ( $taxonomies as $object ) {
		    $setting_key = $object->name . '_ids';
		    if ( !empty( $settings[ $setting_key ] ) ) {
			    $args[ 'tax_query' ][ $tax_query_count ] = [
				    'taxonomy' => $object->name,
				    'field'     => 'term_id',
				    'terms'    => $settings[ $setting_key ],
			    ];
		    }
		    $tax_query_count++;
	    }

	    return $args;
    }
    
    public function load_quick_view_asset(){
        add_action('wp_footer',function (){
            if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
                if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
                    wp_enqueue_script( 'zoom' );
                }
                if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
                    wp_enqueue_script( 'flexslider' );
                }
                if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
                    wp_enqueue_script( 'photoswipe-ui-default' );
                    wp_enqueue_style( 'photoswipe-default-skin' );
                    if ( has_action( 'wp_footer', 'woocommerce_photoswipe' ) === false ) {
                        add_action( 'wp_footer', 'woocommerce_photoswipe', 15 );
                    }
                }
                wp_enqueue_script( 'wc-add-to-cart-variation' );
                wp_enqueue_script( 'wc-single-product' );
            }
        });
    }
}
