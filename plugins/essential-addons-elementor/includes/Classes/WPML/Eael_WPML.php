<?php

namespace Essential_Addons_Elementor\Pro\Classes\WPML;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


trait Eael_WPML {

    public function translatable_widgets($widgets)
    {
        $widgets['eael-content-timeline'] = [
            'conditions' => ['widgetType' => 'eael-content-timeline'],
            'integration-class' => '\Essential_Addons_Elementor\Pro\Classes\WPML\Widgets\Content_Timeline',
        ];

        $widgets['eael-counter'] = [
            'conditions' => ['widgetType' => 'eael-counter'],
            'fields' => [
                [
                    'field'       => 'number_prefix',
                    'type'        => __('Counter: Number Prefix', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'number_suffix',
                    'type'        => __('Counter: Number Suffix', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'counter_title',
                    'type'        => __('Counter: Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        $widgets['eael-post-block'] = [
            'conditions' => ['widgetType' => 'eael-post-block'],
            'fields' => [
                [
                    'field'       => 'show_load_more_text',
                    'type'        => __('Post Block: Load More Button', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'read_more_button_text',
                    'type'        => __('Post Block: Read More Button', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        $widgets['eael-dynamic-filterable-gallery'] = [
            'conditions' => ['widgetType' => 'eael-dynamic-filterable-gallery'],
            'fields' => [
                [
                    'field'       => 'eael_fg_loadmore_btn_text',
                    'type'        => __('Dynamic Gallery: Load More Button', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        $widgets['eael-divider'] = [
            'conditions' => ['widgetType' => 'eael-divider'],
            'fields' => [
                [
                    'field'       => 'divider_text',
                    'type'        => __('Divider: Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        $widgets['eael-flip-carousel'] = [
            'conditions' => ['widgetType' => 'eael-flip-carousel'],
            'integration-class' => '\Essential_Addons_Elementor\Pro\Classes\WPML\Widgets\Flip_Carousel',
        ];

        $widgets['eael-google-map'] = [
            'conditions' => ['widgetType' => 'eael-google-map'],
            'integration-class' => '\Essential_Addons_Elementor\Pro\Classes\WPML\Widgets\Google_Map'
        ];

        $widgets['eael-image-comparison'] = [
            'conditions' => ['widgetType' => 'eael-image-comparison'],
            'fields' => [
                [
                    'field'       => 'before_image_label',
                    'type'        => __('Image Comparison: Label Before', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'after_image_label',
                    'type'        => __('Image Comparison: Label After', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        // $widgets['eael-image-hotspots'] = [
        //     'conditions' => ['widgetType' => 'eael-image-hotspots'],
        //     'integration-class' => '\Essential_Addons_Elementor\Pro\Classes\WPML\Widgets\Image_Hot_Spots'
        // ];

        // Interactive card


        $widgets['eael-interactive-promo'] = [
            'conditions' => ['widgetType' => 'eael-interactive-promo'],
            'fields' => [
                [
                    'field'       => 'promo_heading',
                    'type'        => __('Interactive Promo: Promo Heading', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'promo_content',
                    'type'        => __('Interactive Promo: Promo Content', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ],
                [
                    'field'       => 'promo_link_url',
                    'type'        => __('Interactive Promo: Link URL', 'essential-addons-elementor'),
                    'editor_type' => 'LINE'
                ]
            ],
        ];

        $widgets['eael-lightbox'] = [
            'conditions' => ['widgetType' => 'eael-lightbox'],
            'fields' => [
                [
                    'field'       => 'eael_lightbox_type_content',
                    'type'        => __('Light Box: Add your content here (HTML/Shortcode)', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ],
                [
                    'field'       => 'custom_html',
                    'type'        => __('Light Box: Custom HTML', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ]
            ],
        ];

        $widgets['eael-mailchimp'] = [
            'conditions' => ['widgetType' => 'eael-mailchimp'],
            'fields' => [
                [
                    'field'       => 'eael_mailchimp_email_label_text',
                    'type'        => __('Email Label', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_mailchimp_fname_label_text',
                    'type'        => __('First Name Label', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_mailchimp_lname_label_text',
                    'type'        => __('Last Name Label', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_section_mailchimp_button_text',
                    'type'        => __('Button Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_section_mailchimp_loading_text',
                    'type'        => __('Loading Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_section_mailchimp_success_text',
                    'type'        => __('Success Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        $widgets['eael-logo-carousel'] = [
            'conditions' => ['widgetType' => 'eael-logo-carousel'],
            'integration-class' => '\Essential_Addons_Elementor\Pro\Classes\WPML\Widgets\Logo_Carousel'
        ];

        $widgets['eael-offcanvas'] = [
            'conditions' => ['widgetType' => 'eael-offcanvas'],
            'fields' => [
                [
                    'field'       => 'button_text',
                    'type'        => __('Offcanvas: Button Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
            'integration-class' => '\Essential_Addons_Elementor\Pro\Classes\WPML\Widgets\Offcanvas'
        ];

        $widgets['eael-post-list'] = [
            'conditions' => ['widgetType' => 'eael-post-list'],
            'fields' => [
                [
                    'field'       => 'eael_post_list_topbar_term_all_text',
                    'type'        => __('Smart Post List: Button Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ]
        ];

        $widgets['eael-price-menu'] = [
            'conditions' => ['widgetType' => 'eael-price-menu'],
            'integration-class' => '\Essential_Addons_Elementor\Pro\Classes\WPML\Widgets\Price_Menu'
        ];

        $widgets['eael-protected-content'] = [
            'conditions' => ['widgetType' => 'eael-protected-content'],
            'fields' => [
                [
                    'field'       => 'eael_protected_content_field',
                    'type'        => __('Protected Content', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ],
                [
                    'field'       => 'eael_protected_content_message_text',
                    'type'        => __('Protected Content: Public Text', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ]
            ]
        ];

        $widgets['eael-static-product'] = [
            'conditions' => ['widgetType' => 'eael-static-product'],
            'fields' => [
                [
                    'field'       => 'eael_static_product_heading',
                    'type'        => __('Product Heading', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_static_product_description',
                    'type'        => __('Product Description', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ],
                [
                    'field'       => 'eael_static_product_demo_text',
                    'type'        => __('Live Demo Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_static_product_btn',
                    'type'        => __('Button Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ]
        ];

        $widgets['eael-team-member-carousel'] = [
            'conditions' => ['widgetType' => 'eael-team-member-carousel'],
            'integration-class' => '\Essential_Addons_Elementor\Pro\Classes\WPML\Widgets\Team_Member_Carousel'
        ];

        $widgets['eael-testimonial-slider'] = [
            'conditions' => ['widgetType' => 'eael-testimonial-slider'],
            'integration-class' => '\Essential_Addons_Elementor\Pro\Classes\WPML\Widgets\Testimonial_Slider'
        ];

        $widgets['eael-toggle'] = [
            'conditions' => ['widgetType' => 'eael-toggle'],
            'fields' => [
                [
                    'field'       => 'primary_label',
                    'type'        => __('Toggle: Primary Label', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'primary_content',
                    'type'        => __('Toggle: Primary Content', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ],
                [
                    'field'       => 'secondary_label',
                    'type'        => __('Toggle: Secondary Label', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'secondary_content',
                    'type'        => __('Toggle: Secondary Content', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ]
            ]
        ];

        $widgets['eael-tooltip'] = [
            'conditions' => ['widgetType' => 'eael-tooltip'],
            'fields' => [
                [
                    'field'       => 'eael_tooltip_content',
                    'type'        => __('Tooltip: Content', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ],
                [
                    'field'       => 'eael_tooltip_hover_content',
                    'type'        => __('Tooltip: Hover Content', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ]
            ]
        ];

        $widgets['eael-woo-collections'] = [
            'conditions' => ['widgetType' => 'eael-woo-collections'],
            'fields' => [
                [
                    'field'       => 'eael_woo_collections_subtitle',
                    'type'        => __('Woo Collections: Subtitle', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ]
        ];

        return $widgets;
    }
}