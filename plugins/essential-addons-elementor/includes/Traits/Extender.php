<?php

namespace Essential_Addons_Elementor\Pro\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager;
use \Elementor\Core\Schemes;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Repeater;
use \Elementor\Utils;
use \Essential_Addons_Elementor\Elements\Login_Register;
use \Essential_Addons_Elementor\Pro\Classes\Helper;
use \Google_Client;
use \mysqli;

trait Extender
{
    public function add_progressbar_pro_layouts($options)
    {
        $options['layouts']['line_rainbow'] = __('Line Rainbow', 'essential-addons-elementor');
        $options['layouts']['circle_fill'] = __('Circle Fill', 'essential-addons-elementor');
        $options['layouts']['half_circle_fill'] = __('Half Circle Fill', 'essential-addons-elementor');
        $options['layouts']['box'] = __('Box', 'essential-addons-elementor');
        $options['conditions'] = [];

        return $options;
    }

    public function fancy_text_style_types($options)
    {
        $options['styles']['style-2'] = __('Style 2', 'essential-addons-elementor');
        $options['conditions'] = [];

        return $options;
    }

    public function ticker_options($options)
    {
        $options['options']['custom'] = __('Custom', 'essential-addons-elementor');
        $options['conditions'] = [];

        return $options;
    }

    public function data_table_sorting($obj)
    {
        $obj->add_control('eael_section_data_table_enabled', [
            'label' => __('Enable Table Sorting', 'essential-addons-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'essential-addons-elementor'),
            'label_off' => esc_html__('No', 'essential-addons-elementor'),
            'return_value' => 'true',
        ]);
    }

    public function ticker_custom_contents($obj)
    {
        /**
         * Content Ticker Custom Content Settings
         */
        $obj->start_controls_section('eael_section_ticker_custom_content_settings', [
            'label' => __('Custom Content Settings', 'essential-addons-elementor'),
            'condition' => [
                'eael_ticker_type' => 'custom',
            ],
        ]);

        $obj->add_control('eael_ticker_custom_contents', [
            'type' => Controls_Manager::REPEATER,
            'seperator' => 'before',
            'default' => [
                ['eael_ticker_custom_content' => 'Ticker Custom Content'],
            ],
            'fields' => [
                [
                    'name' => 'eael_ticker_custom_content',
                    'label' => esc_html__('Content', 'essential-addons-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'label_block' => true,
                    'default' => esc_html__('Ticker custom content', 'essential-addons-elementor'),
                ],
                [
                    'name' => 'eael_ticker_custom_content_link',
                    'label' => esc_html__('Button Link', 'essential-addons-elementor'),
                    'type' => Controls_Manager::URL,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'label_block' => true,
                    'default' => [
                        'url' => '#',
                        'is_external' => '',
                    ],
                    'show_external' => true,
                ],
            ],
            'title_field' => '{{eael_ticker_custom_content}}',
        ]);

        $obj->end_controls_section();
    }

    public function content_ticker_custom_content($settings)
    {
        if ('custom' === $settings['eael_ticker_type']) {
            foreach ($settings['eael_ticker_custom_contents'] as $content):
                $target = $content['eael_ticker_custom_content_link']['is_external'] ? 'target="_blank"' : '';
                $nofollow = $content['eael_ticker_custom_content_link']['nofollow'] ? 'rel="nofollow"' : '';
                ?>
			                <div class="swiper-slide">
			                    <div class="ticker-content">
									<?php if (!empty($content['eael_ticker_custom_content_link']['url'])): ?>
			                            <a <?php echo $target; ?> <?php echo $nofollow; ?>
			                                    href="<?php echo esc_url($content['eael_ticker_custom_content_link']['url']); ?>"
			                                    class="ticker-content-link"><?php echo _e($content['eael_ticker_custom_content'], 'essential-addons-elementor') ?></a>
									<?php else: ?>
                            <p><?php echo _e($content['eael_ticker_custom_content'], 'essential-addons-elementor') ?></p>
						<?php endif;?>
                    </div>
                </div>
			<?php
endforeach;
        }
    }

    public function progress_bar_rainbow_class(array $wrap_classes, array $settings)
    {
        if ($settings['progress_bar_layout'] == 'line_rainbow') {
            $wrap_classes[] = 'eael-progressbar-line-rainbow';
        }

        return $wrap_classes;
    }

    public function progress_bar_circle_fill_class(array $wrap_classes, array $settings)
    {
        if ($settings['progress_bar_layout'] == 'circle_fill') {
            $wrap_classes[] = 'eael-progressbar-circle-fill';
        }

        return $wrap_classes;
    }

    public function progressbar_half_circle_wrap_class(array $wrap_classes, array $settings)
    {
        if ($settings['progress_bar_layout'] == 'half_circle_fill') {
            $wrap_classes[] = 'eael-progressbar-half-circle-fill';
        }

        return $wrap_classes;
    }

    public function progress_bar_box_control($obj)
    {
        /**
         * Style Tab: General(Box)
         */
        $obj->start_controls_section('progress_bar_section_style_general_box', [
            'label' => __('General', 'essential-addons-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'progress_bar_layout' => 'box',
            ],
        ]);

        $obj->add_control('progress_bar_box_alignment', [
            'label' => __('Alignment', 'essential-addons-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'essential-addons-elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'essential-addons-elementor'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'essential-addons-elementor'),
                    'icon' => 'fa fa-align-right',
                ],
            ],
            'default' => 'center',
        ]);

        $obj->add_control('progress_bar_box_width', [
            'label' => __('Width', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 100,
                    'max' => 500,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 140,
            ],
            'selectors' => [
                '{{WRAPPER}} .eael-progressbar-box' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'separator' => 'before',
        ]);

        $obj->add_control('progress_bar_box_height', [
            'label' => __('Height', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 100,
                    'max' => 500,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 200,
            ],
            'selectors' => [
                '{{WRAPPER}} .eael-progressbar-box' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $obj->add_control('progress_bar_box_bg_color', [
            'label' => __('Background Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#fff',
            'selectors' => [
                '{{WRAPPER}} .eael-progressbar-box' => 'background-color: {{VALUE}}',
            ],
            'separator' => 'before',
        ]);

        $obj->add_control('progress_bar_box_fill_color', [
            'label' => __('Fill Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#000',
            'selectors' => [
                '{{WRAPPER}} .eael-progressbar-box-fill' => 'background-color: {{VALUE}}',
            ],
            'separator' => 'before',
        ]);

        $obj->add_control('progress_bar_box_stroke_width', [
            'label' => __('Stroke Width', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 1,
            ],
            'selectors' => [
                '{{WRAPPER}} .eael-progressbar-box' => 'border-width: {{SIZE}}{{UNIT}}',
            ],
            'separator' => 'before',
        ]);

        $obj->add_control('progress_bar_box_stroke_color', [
            'label' => __('Stroke Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#eee',
            'selectors' => [
                '{{WRAPPER}} .eael-progressbar-box' => 'border-color: {{VALUE}}',
            ],
        ]);

        $obj->end_controls_section();
    }

    public function add_box_progress_bar_block(array $settings, $obj, array $wrap_classes)
    {
        if ($settings['progress_bar_layout'] == 'box') {
            $wrap_classes[] = 'eael-progressbar-box';

            $obj->add_render_attribute('eael-progressbar-box', [
                'class' => $wrap_classes,
                'data-layout' => $settings['progress_bar_layout'],
                'data-count' => $settings['progress_bar_value_type'] == 'static' ? $settings['progress_bar_value']['size'] : $settings['progress_bar_value_dynamic'],
                'data-duration' => $settings['progress_bar_animation_duration']['size'],
            ]);

            $obj->add_render_attribute('eael-progressbar-box-fill', [
                'class' => 'eael-progressbar-box-fill',
                'style' => '-webkit-transition-duration:' . $settings['progress_bar_animation_duration']['size'] . 'ms;-o-transition-duration:' . $settings['progress_bar_animation_duration']['size'] . 'ms;transition-duration:' . $settings['progress_bar_animation_duration']['size'] . 'ms;',
            ]);

            echo '<div class="eael-progressbar-box-container ' . $settings['progress_bar_box_alignment'] . '">
				<div ' . $obj->get_render_attribute_string('eael-progressbar-box') . '>
	                <div class="eael-progressbar-box-inner-content">
	                    ' . ($settings['progress_bar_title'] ? sprintf('<%1$s class="%2$s">', $settings['progress_bar_title_html_tag'], 'eael-progressbar-title') . $settings['progress_bar_title'] . sprintf('</%1$s>', $settings['progress_bar_title_html_tag']) : '') . '
	                    ' . ($settings['progress_bar_show_count'] === 'yes' ? '<span class="eael-progressbar-count-wrap"><span class="eael-progressbar-count">0</span><span class="postfix">' . __('%', 'essential-addons-for-elementor') . '</span></span>' : '') . '
	                </div>
	                <div ' . $obj->get_render_attribute_string('eael-progressbar-box-fill') . '></div>
	            </div>
            </div>';
        }
    }

    public function progressbar_general_style_condition($conditions)
    {
        return array_merge($conditions, [
            'circle_fill',
            'half_circle_fill',
            'box',
        ]);
    }

    public function progressbar_line_fill_stripe_condition($conditions)
    {
        return array_merge($conditions, ['progress_bar_layout' => 'line']);
    }

    public function circle_style_general_condition($conditions)
    {
        return array_merge($conditions, [
            'circle_fill',
            'half_circle_fill',
        ]);
    }

    public function add_pricing_table_styles($options)
    {
        $options['styles']['style-3'] = esc_html__('Pricing Style 3', 'essential-addons-elementor');
        $options['styles']['style-4'] = esc_html__('Pricing Style 4', 'essential-addons-elementor');
        $options['styles']['style-5'] = esc_html__('Pricing Style 5', 'essential-addons-elementor');
        $options['conditions'] = [];

        return $options;
    }

    public function add_creative_button_controls($obj)
    {
        // Content Controls
        $obj->start_controls_section('eael_section_creative_button_content', [
            'label' => esc_html__('Button Content', 'essential-addons-elementor'),
        ]);

        $obj->start_controls_tabs('eael_creative_button_content_separation');

        $obj->start_controls_tab('button_primary_settings', [
            'label' => __('Primary', 'essential-addons-elementor'),
        ]);

        $obj->add_control('creative_button_text', [
            'label' => __('Button Text', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'label_block' => true,
            'default' => 'Click Me!',
            'placeholder' => __('Enter button text', 'essential-addons-elementor'),
            'title' => __('Enter button text here', 'essential-addons-elementor'),
        ]);

        $obj->add_control('eael_creative_button_icon_new', [
            'label' => esc_html__('Icon', 'essential-addons-elementor'),
            'type' => Controls_Manager::ICONS,
            'fa4compatibility' => 'eael_creative_button_icon',
            'condition' => [
                'creative_button_effect!' => ['eael-creative-button--tamaya'],
            ],
        ]);

        $obj->add_control('eael_creative_button_icon_alignment', [
            'label' => esc_html__('Icon Position', 'essential-addons-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'left',
            'options' => [
                'left' => esc_html__('Before', 'essential-addons-elementor'),
                'right' => esc_html__('After', 'essential-addons-elementor'),
            ],
            'condition' => [
                'creative_button_effect!' => ['eael-creative-button--tamaya'],
            ],
        ]);

        $obj->add_control('eael_creative_button_icon_indent', [
            'label' => esc_html__('Icon Spacing', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button-icon-right' => 'margin-left: {{SIZE}}px;',
                '{{WRAPPER}} .eael-creative-button-icon-left' => 'margin-right: {{SIZE}}px;',
                '{{WRAPPER}} .eael-creative-button--shikoba i' => 'left: {{SIZE}}%;',
            ],
            'condition' => [
                'creative_button_effect!' => ['eael-creative-button--tamaya'],
            ],
        ]);

        $obj->end_controls_tab();

        $obj->start_controls_tab('button_secondary_settings', [
            'label' => __('Secondary', 'essential-addons-elementor'),
        ]);

        $obj->add_control('creative_button_secondary_text', [
            'label' => __('Button Secondary Text', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'label_block' => true,
            'default' => 'Go!',
            'placeholder' => __('Enter button secondary text', 'essential-addons-elementor'),
            'title' => __('Enter button secondary text here', 'essential-addons-elementor'),
        ]);

        $obj->end_controls_tab();

        $obj->end_controls_tabs();

        $obj->add_control('creative_button_link_url', [
            'label' => esc_html__('Link URL', 'essential-addons-elementor'),
            'type' => Controls_Manager::URL,
            'dynamic' => [
                'active' => true,
            ],
            'label_block' => true,
            'default' => [
                'url' => '#',
                'is_external' => '',
            ],
            'show_external' => true,
        ]);

        $obj->end_controls_section();
    }

    public function add_creative_button_style_pro_controls($obj)
    {
        $obj->add_control('creative_button_effect', [
            'label' => esc_html__('Set Button Effect', 'essential-addons-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'eael-creative-button--default',
            'options' => [
                'eael-creative-button--default' => esc_html__('Default', 'essential-addons-elementor'),
                'eael-creative-button--winona' => esc_html__('Winona', 'essential-addons-elementor'),
                'eael-creative-button--ujarak' => esc_html__('Ujarak', 'essential-addons-elementor'),
                'eael-creative-button--wayra' => esc_html__('Wayra', 'essential-addons-elementor'),
                'eael-creative-button--tamaya' => esc_html__('Tamaya', 'essential-addons-elementor'),
                'eael-creative-button--rayen' => esc_html__('Rayen', 'essential-addons-elementor'),
                'eael-creative-button--pipaluk' => esc_html__('Pipaluk', 'essential-addons-elementor'),
                'eael-creative-button--moema' => esc_html__('Moema', 'essential-addons-elementor'),
                'eael-creative-button--wave' => esc_html__('Wave', 'essential-addons-elementor'),
                'eael-creative-button--aylen' => esc_html__('Aylen', 'essential-addons-elementor'),
                'eael-creative-button--saqui' => esc_html__('Saqui', 'essential-addons-elementor'),
                'eael-creative-button--wapasha' => esc_html__('Wapasha', 'essential-addons-elementor'),
                'eael-creative-button--nuka' => esc_html__('Nuka', 'essential-addons-elementor'),
                'eael-creative-button--antiman' => esc_html__('Antiman', 'essential-addons-elementor'),
                'eael-creative-button--quidel' => esc_html__('Quidel', 'essential-addons-elementor'),
                'eael-creative-button--shikoba' => esc_html__('Shikoba', 'essential-addons-elementor'),
            ],
        ]);

        $obj->start_controls_tabs('eael_creative_button_typography_separation');

        $obj->start_controls_tab('button_primary_typography', [
            'label' => __('Primary', 'essential-addons-elementor'),
        ]);

        $obj->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'eael_creative_button_typography',
            'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            'selector' => '{{WRAPPER}} .eael-creative-button .cretive-button-text',
        ]);

        $obj->add_responsive_control('eael_creative_button_icon_size', [
            'label' => esc_html__('Icon Size', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [
                'px',
                '%',
            ],
            'default' => [
                'size' => 30,
                'unit' => 'px',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .eael-creative-button svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $obj->end_controls_tab();

        $obj->start_controls_tab('button_secondary_typography', [
            'label' => __('Secondary', 'essential-addons-elementor'),
        ]);

        $obj->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'eael_creative_button_secondary_typography',
            'scheme' => Schemes\Typography::TYPOGRAPHY_1,
            'selector' => '{{WRAPPER}} .eael-creative-button--rayen::before, {{WRAPPER}} .eael-creative-button--winona::after',
        ]);

        $obj->end_controls_tab();

        $obj->end_controls_tabs();

        $obj->add_responsive_control('eael_creative_button_alignment', [
            'label' => esc_html__('Button Alignment', 'essential-addons-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => true,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Left', 'essential-addons-elementor'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'essential-addons-elementor'),
                    'icon' => 'fa fa-align-center',
                ],
                'flex-end' => [
                    'title' => esc_html__('Right', 'essential-addons-elementor'),
                    'icon' => 'fa fa-align-right',
                ],
            ],
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button-wrapper' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $obj->add_responsive_control('eael_creative_button_width', [
            'label' => esc_html__('Width', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [
                'px',
                '%',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 500,
                    'step' => 1,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $obj->add_responsive_control('eael_creative_button_padding', [
            'label' => esc_html__('Button Padding', 'essential-addons-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [
                'px',
                'em',
                '%',
            ],
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--winona::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--winona > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--saqui::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $obj->add_control('use_gradient_background', [
            'label' => __('Use Gradient Background', 'essential-addons-for-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Show', 'essential-addons-for-elementor'),
            'label_off' => __('Hide', 'essential-addons-for-elementor'),
            'return_value' => 'yes',
            'default' => '',
        ]);

        $obj->start_controls_tabs('eael_creative_button_tabs');

        $obj->start_controls_tab('normal', ['label' => esc_html__('Normal', 'essential-addons-elementor')]);

        $obj->add_control('eael_creative_button_icon_color', [
            'label' => esc_html__('Icon Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button .creative-button-inner svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $obj->add_control('eael_creative_button_text_color', [
            'label' => esc_html__('Text Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button' => 'color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button svg' => 'fill: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::before' => 'color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::after' => 'color: {{VALUE}};',
            ],
        ]);

        $obj->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'eael_creative_button_gradient_background',
            'types' => [
                'gradient',
                'classic',
            ],
            'selector' => '
                    {{WRAPPER}} .eael-creative-button,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--ujarak:hover,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--wayra:hover,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::before,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::after,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--rayen:hover,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--pipaluk::after,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--wave:hover,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--nuka::before,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--nuka::after,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--antiman::after,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--quidel::after
                ',
            'condition' => [
                'use_gradient_background' => 'yes',
            ],
        ]);

        $obj->add_control('eael_creative_button_background_color', [
            'label' => esc_html__('Background Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#333333',
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--ujarak:hover' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--wayra:hover' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::before' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::after' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen:hover' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--pipaluk::after' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--wave:hover' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--aylen::before' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--nuka::before' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--nuka::after' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--antiman::after' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--quidel::after' => 'background-color: {{VALUE}};',
            ],
        ]);

        $obj->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'eael_creative_button_border',
            'selector' => '{{WRAPPER}} .eael-creative-button',
        ]);

        $obj->add_control('eael_creative_button_border_radius', [
            'label' => esc_html__('Border Radius', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button' => 'border-radius: {{SIZE}}px;',
                '{{WRAPPER}} .eael-creative-button::before' => 'border-radius: {{SIZE}}px;',
                '{{WRAPPER}} .eael-creative-button::after' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $obj->end_controls_tab();

        $obj->start_controls_tab('eael_creative_button_hover', ['label' => esc_html__('Hover', 'essential-addons-elementor')]);

	    $obj->add_control('eael_creative_button_hover_icon_color', [
		    'label' => esc_html__('Icon Color', 'essential-addons-elementor'),
		    'type' => Controls_Manager::COLOR,
		    'default' => '#ffffff',
		    'selectors' => [
			    '{{WRAPPER}} .eael-creative-button:hover i' => 'color: {{VALUE}};',
			    '{{WRAPPER}} .eael-creative-button:hover .creative-button-inner svg' => 'fill: {{VALUE}};',
		    ],
	    ]);

        $obj->add_control('eael_creative_button_hover_text_color', [
            'label' => esc_html__('Text Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button:hover' => 'color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button:hover svg' => 'fill: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--winona::after' => 'color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--saqui::after' => 'color: {{VALUE}};',
            ],
        ]);

        $obj->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'eael_creative_button_hover_gradient_background',
            'types' => [
                'gradient',
                'classic',
            ],
            'selector' => '
                    {{WRAPPER}} .eael-creative-button:hover,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--ujarak::before,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--wayra:hover::before,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya:hover,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--rayen::before,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--wave::before,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--wave:hover::before,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--aylen::after,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--saqui:hover,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--nuka:hover::after,
                    {{WRAPPER}} .eael-creative-button.eael-creative-button--quidel:hover::after
                ',
            'condition' => [
                'use_gradient_background' => 'yes',
            ],
        ]);

        $obj->add_control('eael_creative_button_hover_background_color', [
            'label' => esc_html__('Background Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#f54',
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button:hover' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--ujarak::before' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--wayra:hover::before' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya:hover' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen::before' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--wave::before' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--wave:hover::before' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--aylen::after' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--saqui:hover' => 'color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--nuka:hover::after' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--quidel:hover::after' => 'background-color: {{VALUE}};',
            ],
        ]);

        $obj->add_control('eael_creative_button_hover_border_color', [
            'label' => esc_html__('Border Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .eael-creative-button:hover' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--wapasha::before' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--antiman::before' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--pipaluk::before' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .eael-creative-button.eael-creative-button--quidel::before' => 'background-color: {{VALUE}};',
            ],
        ]);

        $obj->end_controls_tab();

        $obj->end_controls_tabs();
    }

    public function pricing_table_subtitle_field($options)
    {
        return array_merge($options, [
            'style-3',
            'style-4',
            'style-5',
        ]);
    }

    public function pricing_table_icon_support($options)
    {
        return array_merge($options, ['style-5']);
    }

    public function pricing_table_header_radius_support($options)
    {
        return array_merge($options, ['style-5']);
    }

    public function pricing_table_header_background_support($options)
    {
        return array_merge($options, ['style-5']);
    }

    public function pricing_table_header_image_control($obj)
    {
        /**
         * Condition: 'eael_pricing_table_style' => 'style-4'
         */
        $obj->add_control('eael_pricing_table_style_4_image', [
            'label' => esc_html__('Header Image', 'essential-addons-elementor'),
            'type' => Controls_Manager::MEDIA,
            'default' => [
                'url' => Utils::get_placeholder_image_src(),
            ],
            'selectors' => [
                '{{WRAPPER}} .eael-pricing-image' => 'background-image: url({{URL}});',
            ],
            'condition' => [
                'eael_pricing_table_style' => 'style-4',
            ],
        ]);
    }

    public function pricing_table_style_2_currency_position($obj)
    {
        /**
         * Condition: 'eael_pricing_table_style' => 'style-3'
         */
        $obj->add_control('eael_pricing_table_style_3_price_position', [
            'label' => esc_html__('Pricing Position', 'essential-addons-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'bottom',
            'label_block' => false,
            'options' => [
                'top' => esc_html__('On Top', 'essential-addons-elementor'),
                'bottom' => esc_html__('At Bottom', 'essential-addons-elementor'),
            ],
            'condition' => [
                'eael_pricing_table_style' => 'style-3',
            ],
        ]);
    }

    public function pricing_table_style_five_settings_control($obj)
    {
        $obj->add_control('eael_pricing_table_style_five_icon_and_title_style', [
            'label' => esc_html__('Icon Beside Title', 'essential-addons-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'essential-addons-elementor'),
            'label_off' => __('No', 'essential-addons-elementor'),
            'return_value' => 'yes',
            'condition' => [
                'eael_pricing_table_style' => 'style-5',
            ],
        ]);
        $obj->add_control('eael_pricing_table_style_five_header_layout', [
            'label' => esc_html__('Header Layout', 'essential-addons-elementor'),
            'type' => Controls_Manager::SELECT,
            'default' => 'one',
            'options' => [
                'one' => __('Layout 1', 'essential-addons-elementor'),
                'two' => __('Layout 2', 'essential-addons-elementor'),
            ],
            'condition' => [
                'eael_pricing_table_style' => 'style-5',
            ],
        ]);
    }

    public function pricing_table_style_header_layout_two($obj)
    {
        $obj->start_controls_section('eael_pricing_table_style_five_section', [
            'label' => __('Header Layout Two', 'essential-addons-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'eael_pricing_table_style_five_header_layout' => 'two',
            ],
        ]);

        $obj->add_control('eael_pricing_table_style_five_price_style_padding', [
            'label' => __('Padding', 'essential-addons-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [
                'px',
                '%',
                'em',
            ],
            'selectors' => [
                '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'eael_pricing_table_style' => 'style-5',
            ],
        ]);
        $obj->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'eael_pricing_table_style_five_price_style_background',
            'label' => __('Background', 'essential-addons-elementor'),
            'types' => [
                'classic',
                'gradient',
            ],
            'selector' => '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-image',
            'condition' => [
                'eael_pricing_table_style' => 'style-5',
            ],
        ]);
        $obj->end_controls_section();
    }

    public function add_pricing_table_pro_styles($settings, $obj, $pricing, $target, $nofollow, $featured_class)
    {
        $settings = $obj->get_settings();
        $button_text = $obj->get_settings_for_display('eael_pricing_table_btn');
        $inline_style = ($settings['eael_pricing_table_featured_styles'] === 'ribbon-4' && 'yes' === $settings['eael_pricing_table_featured'] ? ' style="overflow: hidden;"' : '');
        if ('style-3' === $settings['eael_pricing_table_style']): ?>
            <div class="eael-pricing style-3"<?php echo $inline_style; ?>>
                <div class="eael-pricing-item <?php echo esc_attr($featured_class); ?>">
					<?php if ('top' === $settings['eael_pricing_table_style_3_price_position']): ?>
                        <div class="eael-pricing-tag on-top">
                            <span class="price-tag"><?php echo $pricing; ?></span>
                            <span class="price-period"><?php echo $settings['eael_pricing_table_period_separator']; ?><?php echo $settings['eael_pricing_table_price_period']; ?></span>
                        </div>
					<?php endif;?>
                    <div class="header">
                        <h2 class="title"><?php echo $settings['eael_pricing_table_title']; ?></h2>
                        <span class="subtitle"><?php echo $settings['eael_pricing_table_sub_title']; ?></span>
                    </div>
                    <div class="body">
						<?php $obj->render_feature_list($settings, $obj);?>
                    </div>
					<?php if ('bottom' === $settings['eael_pricing_table_style_3_price_position']): ?>
                        <div class="eael-pricing-tag">
                            <span class="price-tag"><?php echo $pricing; ?></span>
                            <span class="price-period"><?php echo $settings['eael_pricing_table_period_separator']; ?><?php echo $settings['eael_pricing_table_price_period']; ?></span>
                        </div>
					<?php endif;?>
                    <div class="footer">
                        <a href="<?php echo esc_url($settings['eael_pricing_table_btn_link']['url']); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>
                           class="eael-pricing-button">
							<?php if ('left' == $settings['eael_pricing_table_button_icon_alignment']): ?>
								<?php if (empty($settings['eael_pricing_table_button_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_button_icon_new'])) {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon_new']['value']); ?> fa-icon-left"></i>
								<?php } else {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon']); ?> fa-icon-left"></i>
								<?php }?>
								<?php echo $button_text; ?>
							<?php elseif ('right' == $settings['eael_pricing_table_button_icon_alignment']): ?>
								<?php echo $button_text; ?>
								<?php if (empty($settings['eael_pricing_table_button_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_button_icon_new'])) {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon_new']['value']); ?> fa-icon-right"></i>
								<?php } else {?>
                                    <i class="<?php echo esc_attr($obj->get_settings('eael_pricing_table_button_icon')); ?> fa-icon-right"></i>
								<?php }?>
							<?php endif;?>
                        </a>
                    </div>
                </div>
            </div>
		<?php endif;
        if ('style-4' === $settings['eael_pricing_table_style']): ?>
            <div class="eael-pricing style-4"<?php echo $inline_style; ?>>
                <div class="eael-pricing-item <?php echo esc_attr($featured_class); ?>">
                    <div class="eael-pricing-image">
                        <div class="eael-pricing-tag">
                            <span class="price-tag"><?php echo $pricing; ?></span>
                            <span class="price-period"><?php echo $settings['eael_pricing_table_period_separator']; ?><?php echo $settings['eael_pricing_table_price_period']; ?></span>
                        </div>
                    </div>
                    <div class="header">
                        <h2 class="title"><?php echo $settings['eael_pricing_table_title']; ?></h2>
                        <span class="subtitle"><?php echo $settings['eael_pricing_table_sub_title']; ?></span>
                    </div>
                    <div class="body">
						<?php $obj->render_feature_list($settings, $obj);?>
                    </div>
                    <div class="footer">
                        <a href="<?php echo esc_url($settings['eael_pricing_table_btn_link']['url']); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>
                           class="eael-pricing-button">
							<?php if ('left' == $settings['eael_pricing_table_button_icon_alignment']): ?>
								<?php if (empty($settings['eael_pricing_table_button_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_button_icon_new'])) {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon_new']['value']); ?> fa-icon-left"></i>
								<?php } else {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon']); ?> fa-icon-left"></i>
								<?php }?>
								<?php echo $button_text; ?>
							<?php elseif ('right' == $settings['eael_pricing_table_button_icon_alignment']): ?>
								<?php echo $button_text; ?>
								<?php if (empty($settings['eael_pricing_table_button_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_button_icon_new'])) {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon_new']['value']); ?> fa-icon-right"></i>
								<?php } else {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon']); ?> fa-icon-right"></i>
								<?php }?>
							<?php endif;?>
                        </a>
                    </div>
                </div>
            </div>
		<?php endif;
        if ('style-5' === $settings['eael_pricing_table_style']): ?>
            <div class="eael-pricing style-5"<?php echo $inline_style; ?>>
                <div class="eael-pricing-item <?php echo ($settings['eael_pricing_table_style_five_header_layout'] !== 'two' ? esc_attr($featured_class) : ''); ?>">
                    <div class="header">
						<?php
if (!empty($settings['eael_pricing_table_style_2_icon_new']['value'])):
        ?>
                            <div class="eael-pricing-icon<?php print($settings['eael_pricing_table_style_five_icon_and_title_style'] === 'yes' ? ' inline' : '');?>">
                            <span class="icon"
                                  style="background:<?php if ('yes' != $settings['eael_pricing_table_icon_bg_show']): echo 'none';endif;?>;">
                                <?php if (empty($settings['eael_pricing_table_style_2_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_style_2_icon_new'])) {?>
	                                <?php if (isset($settings['eael_pricing_table_style_2_icon_new']['value']['url'])): ?>
                                        <img src="<?php echo esc_attr($settings['eael_pricing_table_style_2_icon_new']['value']['url']); ?>"
                                             alt="<?php echo esc_attr(get_post_meta($settings['eael_pricing_table_style_2_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>"/>
	                                <?php else: ?>
                                        <i class="<?php echo esc_attr($settings['eael_pricing_table_style_2_icon_new']['value']); ?>"></i>
	                                <?php endif;?>
                                <?php } else {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_style_2_icon']); ?>"></i>
                                <?php }?>
                            </span>
                            </div>
						<?php
endif; // icon
        if (!empty($settings['eael_pricing_table_title'])):
        ?>
                            <h2 class="title<?php print($settings['eael_pricing_table_style_five_icon_and_title_style'] === 'yes' ? ' inline' : '');?>"><?php echo $settings['eael_pricing_table_title']; ?></h2>
						<?php
endif; // title
        if (!empty($settings['eael_pricing_table_sub_title'])):
        ?>
                            <span class="subtitle"><?php echo $settings['eael_pricing_table_sub_title']; ?></span>
						<?php
endif;
        if ($settings['eael_pricing_table_style_five_header_layout'] == 'one'):
        ?>
                            <div class="eael-pricing-image">
                                <div class="eael-pricing-tag">
                                    <span class="price-tag"><?php echo $pricing; ?></span>
                                    <span class="price-period"><?php echo $settings['eael_pricing_table_period_separator']; ?><?php echo $settings['eael_pricing_table_price_period']; ?></span>
                                </div>
                            </div>
						<?php
endif;
        ?>
                    </div>
					<?php
if ($settings['eael_pricing_table_style_five_header_layout'] == 'two'):
        ?>
                        <div class="eael-pricing-image <?php echo esc_attr($featured_class); ?>"<?php echo $inline_style; ?>>
                            <div class="eael-pricing-tag">
                                <span class="price-tag"><?php echo $pricing; ?></span>
                                <span class="price-period"><?php echo $settings['eael_pricing_table_period_separator']; ?><?php echo $settings['eael_pricing_table_price_period']; ?></span>
                            </div>
                        </div>
					<?php
endif;
        ?>
                    <div class="body">
						<?php $obj->render_feature_list($settings, $obj);?>
                    </div>
                    <div class="footer">
                        <a href="<?php echo esc_url($settings['eael_pricing_table_btn_link']['url']); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>
                           class="eael-pricing-button">
							<?php if ('left' == $settings['eael_pricing_table_button_icon_alignment']): ?>
								<?php if (empty($settings['eael_pricing_table_button_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_button_icon_new'])) {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon_new']['value']); ?> fa-icon-left"></i>
								<?php } else {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon']); ?> fa-icon-left"></i>
								<?php }?>
								<?php echo $button_text; ?>
							<?php elseif ('right' == $settings['eael_pricing_table_button_icon_alignment']): ?>
								<?php echo $button_text; ?>
								<?php if (empty($settings['eael_pricing_table_button_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_button_icon_new'])) {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon_new']['value']); ?> fa-icon-right"></i>
								<?php } else {?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon']); ?> fa-icon-right"></i>
								<?php }?>
							<?php endif;?>
                        </a>
                    </div>
                </div>
            </div>
		<?php endif;
    }

    public function add_admin_licnes_markup_html()
    {
        ?>
        <div class="eael-admin-block eael-admin-block-license">
            <header class="eael-admin-block-header">
                <div class="eael-admin-block-header-icon">
                    <img src="<?php echo EAEL_PRO_PLUGIN_URL . 'assets/admin/images/icon-automatic-updates.svg'; ?>"
                         alt="essential-addons-automatic-update">
                </div>
                <h4 class="eael-admin-title"><?php _e('Automatic Update', 'essential-addons-elementor');?></h4>
            </header>
            <div class="eael-admin-block-content">
				<?php do_action('eael_licensing');?>
            </div>
        </div>
		<?php
}

    public function add_eael_premium_support_link()
    {
        ?>
        <p><?php echo _e('Stuck with something? Get help from live chat or support ticket.', 'essential-addons-elementor'); ?></p>
        <a href="https://wpdeveloper.net"
           class="ea-button"
           target="_blank"><?php echo _e('Initiate a Chat', 'essential-addons-elementor'); ?></a>
		<?php
}

    public function add_eael_additional_support_links()
    {
        ?>
        <div class="eael-admin-block eael-admin-block-community">
            <header class="eael-admin-block-header">
                <div class="eael-admin-block-header-icon">
                    <img src="<?php echo EAEL_PRO_PLUGIN_URL . 'assets/admin/images/icon-join-community.svg'; ?>"
                         alt="join-essential-addons-community">
                </div>
                <h4 class="eael-admin-title">
                    Join the Community</h4>
            </header>
            <div class="eael-admin-block-content">
                <p><?php echo _e('Join the Facebook community and discuss with fellow developers and users. Best way to connect with people and get feedback on your projects.', 'essential-addons-elementor'); ?></p>

                <a href="https://www.facebook.com/groups/essentialaddons"
                   class="review-flexia ea-button"
                   target="_blank"><?php echo _e('Join Facebook Community', 'essential-addons-elementor'); ?></a>
            </div>
        </div>
		<?php
}

    public function add_manage_linces_action_link()
    {
        $link_text = __('Manage License', 'essential-addons-elementor');
        printf('<a href="https://wpdeveloper.net/account" target="_blank">%s</a>', $link_text);
    }

    public function team_member_presets_condition($options)
    {
        return [];
    }

    public function add_team_member_circle_presets($obj)
    {
        $obj->add_responsive_control(
            'eael_team_members_image_height',
            [
                'label' => esc_html__('Image Height', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-team-item figure img' => 'height:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_team_members_preset!' => 'eael-team-members-circle',
                ],
            ]
        );

        $obj->add_responsive_control(
            'eael_team_members_circle_image_width',
            [
                'label' => esc_html__('Image Width', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 150,
                    'unit' => 'px',
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .eael-team-item.eael-team-members-circle figure img' => 'width:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_team_members_preset' => 'eael-team-members-circle',
                ],
            ]
        );

        $obj->add_responsive_control(
            'eael_team_members_circle_image_height',
            [
                'label' => esc_html__('Image Height', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 150,
                    'unit' => 'px',
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .eael-team-item.eael-team-members-circle figure img' => 'height:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_team_members_preset' => 'eael-team-members-circle',
                ],
            ]
        );
    }

    public function add_team_member_social_bottom_markup($settings)
    {
        ?>
        <p class="eael-team-text"><?php echo $settings['eael_team_member_description']; ?></p>
		<?php if (!empty($settings['eael_team_member_enable_social_profiles'])): ?>
            <ul class="eael-team-member-social-profiles">
				<?php foreach ($settings['eael_team_member_social_profile_links'] as $item): ?>
					<?php $icon_migrated = isset($item['__fa4_migrated']['social_new']);
        $icon_is_new = empty($item['social']);?>
					<?php if (!empty($item['social']) || !empty($item['social_new'])): ?>
						<?php $target = $item['link']['is_external'] ? ' target="_blank"' : '';?>
                        <li class="eael-team-member-social-link">
                            <a href="<?php echo esc_attr($item['link']['url']); ?>" <?php echo $target; ?>>
								<?php if ($icon_is_new || $icon_migrated) {?>
									<?php if (isset($item['social_new']['value']['url'])): ?>
                                        <img src="<?php echo esc_attr($item['social_new']['value']['url']); ?>"
                                             alt="<?php echo esc_attr(get_post_meta($item['social_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>"/>
									<?php else: ?>
                                        <i class="<?php echo esc_attr($item['social_new']['value']); ?>"></i>
									<?php endif;?>
								<?php } else {?>
                                    <i class="<?php echo esc_attr($item['social']); ?>"></i>
								<?php }?>
                            </a>
                        </li>
					<?php endif;?>
				<?php endforeach;?>
            </ul>
		<?php endif;
    }

    public function add_team_member_social_right_markup($settings)
    {
        ?>
		<?php if (!empty($settings['eael_team_member_enable_social_profiles'])): ?>
            <ul class="eael-team-member-social-profiles">
				<?php foreach ($settings['eael_team_member_social_profile_links'] as $item): ?>
					<?php if (!empty($item['social_new'])): ?>
						<?php $target = $item['link']['is_external'] ? ' target="_blank"' : '';?>
                        <li class="eael-team-member-social-link">
                            <a href="<?php echo esc_attr($item['link']['url']); ?>"<?php echo $target; ?>><i
                                        class="<?php echo esc_attr($item['social_new']['value']); ?>"></i></a>
                        </li>
					<?php endif;?>
				<?php endforeach;?>
            </ul>
		<?php endif;
    }

    // Advanced Data Table
    public function advanced_data_table_source_control($wb)
    {
        // database
        $wb->add_control('ea_adv_data_table_source_database_query_type', [
            'label' => esc_html__('Select Query', 'essential-addons-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'table' => 'Table',
                'query' => 'MySQL Query',
            ],
            'default' => 'table',
            'condition' => [
                'ea_adv_data_table_source' => 'database',
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_database_table', [
            'label' => esc_html__('Select Table', 'essential-addons-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => Helper::list_db_tables(),
            'condition' => [
                'ea_adv_data_table_source' => 'database',
                'ea_adv_data_table_source_database_query_type' => 'table',
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_database_query', [
            'label' => esc_html__('MySQL Query', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXTAREA,
            'placeholder' => 'e.g. SELECT * FROM `table`',
            'condition' => [
                'ea_adv_data_table_source' => 'database',
                'ea_adv_data_table_source_database_query_type' => 'query',
            ],
        ]);

        // remote
        $wb->add_control('ea_adv_data_table_source_remote_host', [
            'label' => __('Host', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'condition' => [
                'ea_adv_data_table_source' => 'remote',
                'ea_adv_data_table_source_remote_connected' => false,
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_remote_username', [
            'label' => __('Username', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'condition' => [
                'ea_adv_data_table_source' => 'remote',
                'ea_adv_data_table_source_remote_connected' => false,
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_remote_password', [
            'label' => __('Password', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'input_type' => 'password',
            'condition' => [
                'ea_adv_data_table_source' => 'remote',
                'ea_adv_data_table_source_remote_connected' => false,
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_remote_database', [
            'label' => __('Database', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'condition' => [
                'ea_adv_data_table_source' => 'remote',
                'ea_adv_data_table_source_remote_connected' => false,
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_remote_connect', [
            'label' => __('Connect DB', 'essential-addons-for-elementor'),
            'type' => Controls_Manager::BUTTON,
            'text' => __('Connect', 'essential-addons-elementor'),
            'event' => 'ea:advTable:connect',
            'condition' => [
                'ea_adv_data_table_source' => 'remote',
                'ea_adv_data_table_source_remote_connected' => false,
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_remote_disconnect', [
            'label' => __('Disconnect DB', 'essential-addons-for-elementor'),
            'type' => Controls_Manager::BUTTON,
            'text' => __('Disconnect', 'essential-addons-elementor'),
            'event' => 'ea:advTable:disconnect',
            'condition' => [
                'ea_adv_data_table_source' => 'remote',
                'ea_adv_data_table_source_remote_connected' => true,
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_remote_connected', [
            'type' => Controls_Manager::HIDDEN,
            'default' => false,
        ]);

        $wb->add_control('ea_adv_data_table_source_remote_tables', [
            'type' => Controls_Manager::HIDDEN,
            'default' => [],
        ]);

        $wb->add_control('ea_adv_data_table_dynamic_th_width', [
            'type' => Controls_Manager::HIDDEN,
            'default' => [],
        ]);

        $wb->add_control('ea_adv_data_table_source_remote_query_type', [
            'label' => esc_html__('Select Query', 'essential-addons-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'table' => 'Table',
                'query' => 'MySQL Query',
            ],
            'default' => 'table',
            'condition' => [
                'ea_adv_data_table_source' => 'remote',
                'ea_adv_data_table_source_remote_connected' => true,
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_remote_table', [
            'label' => esc_html__('Select Table', 'essential-addons-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [],
            'condition' => [
                'ea_adv_data_table_source' => 'remote',
                'ea_adv_data_table_source_remote_connected' => true,
                'ea_adv_data_table_source_remote_query_type' => 'table',
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_remote_query', [
            'label' => esc_html__('MySQL Query', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXTAREA,
            'placeholder' => 'e.g. SELECT * FROM `table`',
            'condition' => [
                'ea_adv_data_table_source' => 'remote',
                'ea_adv_data_table_source_remote_connected' => true,
                'ea_adv_data_table_source_remote_query_type' => 'query',
            ],
        ]);

        // google sheet
        $wb->add_control('ea_adv_data_table_source_google_api_key', [
            'label' => __('API Key', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'condition' => [
                'ea_adv_data_table_source' => 'google',
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_google_sheet_id', [
            'label' => __('Sheet ID', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'condition' => [
                'ea_adv_data_table_source' => 'google',
            ],
        ]);

        $wb->add_control('ea_adv_data_table_source_google_table_range', [
            'label' => __('Table Range', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'condition' => [
                'ea_adv_data_table_source' => 'google',
            ],
        ]);

        // tablepress
        if (apply_filters('eael/is_plugin_active', 'tablepress/tablepress.php')) {
            $wb->add_control('ea_adv_data_table_source_tablepress_table_id', [
                'label' => esc_html__('Table ID', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => Helper::list_tablepress_tables(),
                'condition' => [
                    'ea_adv_data_table_source' => 'tablepress',
                ],
            ]);
        } else {
            $wb->add_control('ea_adv_data_table_tablepress_required', [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<strong>TablePress</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=TablePress&tab=search&type=term" target="_blank">TablePress</a> first.', 'essential-addons-for-elementor'),
                'content_classes' => 'eael-warning',
                'condition' => [
                    'ea_adv_data_table_source' => 'tablepress',
                ],
            ]);
        }
    }

    public function event_calendar_source_control($obj)
    {
        if (apply_filters('eael/is_plugin_active', 'eventON/eventon.php')) {
            $obj->start_controls_section('eael_event_calendar_eventon_section', [
                'label' => __('EventON', 'essential-addons-for-elementor'),
                'condition' => [
                    'eael_event_calendar_type' => 'eventon',
                ],
            ]);

            $obj->add_control('eael_eventon_calendar_fetch', [
                'label' => __('Get Events', 'essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'default' => ['all'],
                'options' => [
                    'all' => __('All', 'essential-addons-for-elementor'),
                    'date_range' => __('Date Range', 'essential-addons-for-elementor'),
                ],
            ]);

            $obj->add_control('eael_eventon_calendar_start_date', [
                'label' => __('Start Date', 'essential-addons-for-elementor'),
                'type' => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', current_time('timestamp', 0)),
                'condition' => [
                    'eael_eventon_calendar_fetch' => 'date_range',
                ],
            ]);

            $obj->add_control('eael_eventon_calendar_end_date', [
                'label' => __('End Date', 'essential-addons-for-elementor'),
                'type' => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime("+6 months", current_time('timestamp', 0))),
                'condition' => [
                    'eael_eventon_calendar_fetch' => 'date_range',
                ],
            ]);

            $obj->add_control('eael_eventon_calendar_post_tag', [
                'label' => __('Event Tag', 'essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'default' => [],
                'options' => Helper::get_tags_list([
                    'taxonomy' => 'post_tag',
                    'hide_empty' => false,
                ]),
            ]);

            $taxonomies = Helper::get_taxonomies_by_post(['object_type' => 'ajde_events']);

            unset($taxonomies['event_location'], $taxonomies['post_tag'], $taxonomies['event_organizer']);

            foreach ($taxonomies as $taxonomie) {
                $key = 'eael_eventon_calendar_' . $taxonomie;
                $obj->add_control($key, [
                    'label' => ucwords(str_replace('_', ' ', $taxonomie)),
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => true,
                    'label_block' => true,
                    'default' => [],
                    'options' => Helper::get_tags_list([
                        'taxonomy' => $taxonomie,
                        'hide_empty' => false,
                    ]),
                ]);
            }

            $obj->add_control('eael_eventon_calendar_event_location', [
                'label' => __('Event Location', 'essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'default' => [],
                'options' => Helper::get_tags_list([
                    'taxonomy' => 'event_location',
                    'hide_empty' => false,
                ]),
            ]);

            $obj->add_control('eael_eventon_calendar_event_organizer', [
                'label' => __('Event Organizer', 'essential-addons-for-elementor'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'default' => [],
                'options' => Helper::get_tags_list([
                    'taxonomy' => 'event_organizer',
                    'hide_empty' => false,
                ]),
            ]);

            $obj->add_control('eael_eventon_calendar_max_result', [
                'label' => __('Max Result', 'essential-addons-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 100,
            ]);

            $obj->end_controls_section();
        }
    }

    public function event_calendar_activation_notice($obj)
    {
        if (!apply_filters('eael/is_plugin_active', 'eventON/eventon.php')) {
            $obj->add_control('eael_eventon_warning_text', [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<strong>EventON</strong> is not installed/activated on your site. Please install and activate <a href="https://codecanyon.net/item/eventon-wordpress-event-calendar-plugin/1211017" target="_blank">EventON</a> first.', 'essential-addons-for-elementor'),
                'content_classes' => 'eael-warning',
                'condition' => [
                    'eael_event_calendar_type' => 'eventon',
                ],
            ]);
        }
    }

    public function advanced_data_table_database_integration($settings)
    {
        global $wpdb;

        $html = '';
        $results = [];

        // suppress error
        $wpdb->suppress_errors = true;

        // collect data
        if ($settings['ea_adv_data_table_source_database_query_type'] == 'table') {
            $table = $settings["ea_adv_data_table_source_database_table"];
            $results = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
        } else {
            if (empty($settings['ea_adv_data_table_source_database_query'])) {
                return;
            }

            $results = $wpdb->get_results($settings['ea_adv_data_table_source_database_query'], ARRAY_A);
        }

        if (is_wp_error($results)) {
            return $results->get_error_message();
        }

        if (!empty($results)) {
            $html .= '<thead><tr>';
            foreach (array_keys($results[0]) as $key => $th) {
                $style = isset($settings['ea_adv_data_table_dynamic_th_width']) && isset($settings['ea_adv_data_table_dynamic_th_width'][$key]) ? ' style="width:' . $settings['ea_adv_data_table_dynamic_th_width'][$key] . '"' : '';
                $html .= '<th' . $style . '>' . $th . '</th>';
            }
            $html .= '</tr></thead>';

            $html .= '<tbody>';
            foreach ($results as $tr) {
                $html .= '<tr>';
                foreach ($tr as $td) {
                    $html .= '<td>' . $td . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        }

        // enable error reporting
        $wpdb->suppress_errors = false;

        return $html;
    }

    public function advanced_data_table_remote_database_integration($settings)
    {
        global $wpdb;

        $html = '';
        $results = [];

        // suppress error
        $wpdb->suppress_errors = true;

        // collect data
        if ($settings['ea_adv_data_table_source'] == 'remote') {
            if (empty($settings['ea_adv_data_table_source_remote_host']) || empty($settings['ea_adv_data_table_source_remote_username']) || empty($settings['ea_adv_data_table_source_remote_password']) || empty($settings['ea_adv_data_table_source_remote_database'])) {
                return;
            }

            if ($settings['ea_adv_data_table_source_remote_connected'] == false) {
                return;
            }

            $conn = new mysqli($settings['ea_adv_data_table_source_remote_host'], $settings['ea_adv_data_table_source_remote_username'], $settings['ea_adv_data_table_source_remote_password'], $settings['ea_adv_data_table_source_remote_database']);

            if ($conn->connect_error) {
                return "Failed to connect to MySQL: " . $conn->connect_error;
            } else {
                if ($settings['ea_adv_data_table_source_remote_query_type'] == 'table') {
                    $table = $settings['ea_adv_data_table_source_remote_table'];
                    $query = $conn->query("SELECT * FROM $table");

                    if ($query) {
                        $results = $query->fetch_all(MYSQLI_ASSOC);
                    }
                } else {
                    if ($settings['ea_adv_data_table_source_remote_query']) {
                        $query = $conn->query($settings['ea_adv_data_table_source_remote_query']);

                        if ($query) {
                            $results = $query->fetch_all(MYSQLI_ASSOC);
                        }
                    }
                }

                $conn->close();
            }
        }

        if (!empty($results)) {
            $html .= '<thead><tr>';
            foreach (array_keys($results[0]) as $key => $th) {
                $style = isset($settings['ea_adv_data_table_dynamic_th_width']) && isset($settings['ea_adv_data_table_dynamic_th_width'][$key]) ? ' style="width:' . $settings['ea_adv_data_table_dynamic_th_width'][$key] . '"' : '';
                $html .= '<th' . $style . '>' . $th . '</th>';
            }
            $html .= '</tr></thead>';

            $html .= '<tbody>';
            foreach ($results as $tr) {
                $html .= '<tr>';
                foreach ($tr as $td) {
                    $html .= '<td>' . $td . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        }

        // enable error reporting
        $wpdb->suppress_errors = false;

        return $html;
    }

    public function advanced_data_table_google_sheets_integration($settings)
    {
        if (empty($settings['ea_adv_data_table_source_google_api_key']) || empty($settings['ea_adv_data_table_source_google_sheet_id']) || empty($settings['ea_adv_data_table_source_google_table_range'])) {
            return;
        }

        $arg = [
          'google_sheet_api_key' => $settings['ea_adv_data_table_source_google_api_key'],
          'google_sheet_id' => $settings['ea_adv_data_table_source_google_sheet_id'],
          'calendar_id' => $settings['ea_adv_data_table_source_google_table_range'],
          'cache_time' => $settings['ea_adv_data_table_data_cache_limit'],
        ];

        $thead = '';
        $tbody = '';

        $transient_key = 'ea_adv_data_table_source_google_' . md5(implode('', $arg));

        $results = get_transient( $transient_key );

        if ( empty( $results ) ) {
            $connection = wp_remote_get( "https://sheets.googleapis.com/v4/spreadsheets/{$settings['ea_adv_data_table_source_google_sheet_id']}/?key={$settings['ea_adv_data_table_source_google_api_key']}&ranges={$settings['ea_adv_data_table_source_google_table_range']}&includeGridData=true", ['timeout' => 70] );

            if ( !is_wp_error( $connection ) ) {
                $connection = json_decode( wp_remote_retrieve_body( $connection ), true );
                if ( isset( $connection['sheets'][0]['data'][0]['rowData'] ) ) {
                    $results = $connection['sheets'][0]['data'][0]['rowData'];
                    set_transient( $transient_key, $results, $settings['ea_adv_data_table_data_cache_limit'] * MINUTE_IN_SECONDS );
                }
            }
        }

        if (!empty($results)) {
            foreach ($results as $tr_key => $tr) {

                if (isset($tr['values'])) {
                    $tr = $tr['values'];
                } else {
                    continue;
                }

                if ($tr_key == 0) {
                    $thead .= '<tr>';
                    foreach ($tr as $key => $th) {
                        $style = isset($settings['ea_adv_data_table_dynamic_th_width']) && isset($settings['ea_adv_data_table_dynamic_th_width'][$key]) ? ' style="width:' . $settings['ea_adv_data_table_dynamic_th_width'][$key] . '"' : '';
                        $th = isset($th['hyperlink']) ? '<a href="' . $th['hyperlink'] . '" target="_blank">' . $th['formattedValue'] . '</a>' : $th['formattedValue'];

                        $thead .= '<th' . $style . '>' . $th . '</th>';
                    }
                    $thead .= '</tr>';
                } else {
                    $tbody .= '<tr>';

                    foreach ($tr as $key => $td) {
                        $td = isset($td['hyperlink']) ? '<a href="' . $td['hyperlink'] . '" target="_blank">' . $td['formattedValue'] . '</a>' : $td['formattedValue'];

                        $tbody .= '<td>' . $td . '</td>';
                    }

                    $tbody .= '</tr>';
                }
            }

            return '<thead>' . $thead . '</thead><tbody>' . $tbody . '</tbody>';
        }

        return '';
    }

    public function advanced_data_table_tablepress_integration($settings)
    {
        if (empty($settings['ea_adv_data_table_source_tablepress_table_id'])) {
            return;
        }

        $html = '';
        $tables_opt = get_option('tablepress_tables', '{}');
        $tables_opt = json_decode($tables_opt, true);
        $tables = $tables_opt['table_post'];
        $table_id = $tables[$settings['ea_adv_data_table_source_tablepress_table_id']];
        $table_data = get_post_field('post_content', $table_id);
        $results = json_decode($table_data, true);
        $table_settings = get_post_meta($table_id, '_tablepress_table_options', true);
        $table_settings = json_decode($table_settings, true);

        if (!empty($results)) {
            if (!empty($table_settings) && isset($table_settings['table_head']) && $table_settings['table_head'] == true) {
                $html .= '<thead><tr>';
                foreach ($results[0] as $key => $th) {
                    $style = isset($settings['ea_adv_data_table_dynamic_th_width']) && isset($settings['ea_adv_data_table_dynamic_th_width'][$key]) ? ' style="width:' . $settings['ea_adv_data_table_dynamic_th_width'][$key] . '"' : '';
                    $html .= '<th' . $style . '>' . $th . '</th>';
                }
                $html .= '</tr></thead>';

                array_shift($results);
            }

            $html .= '<tbody>';
            foreach ($results as $tr) {
                $html .= '<tr>';
                foreach ($tr as $td) {
                    $html .= '<td>' . $td . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        }

        return $html;
    }

    public function event_calendar_eventon_integration($data, $settings)
    {
        if (!function_exists('EVO') || $settings['eael_event_calendar_type'] != 'eventon') {
            return $data;
        }

        $default_attr = EVO()->calendar->get_supported_shortcode_atts();
        $default_attr['event_count'] = $settings['eael_eventon_calendar_max_result'];

        if ($settings['eael_eventon_calendar_fetch'] == 'date_range') {
            $default_attr['focus_end_date_range'] = strtotime($settings['eael_eventon_calendar_end_date']);
            $default_attr['focus_start_date_range'] = strtotime($settings['eael_eventon_calendar_start_date']);
        }

        $cat_arr = Helper::get_taxonomies_by_post(['object_type' => 'ajde_events']);

        foreach ($cat_arr as $key => $cat) {

            $cat_id = 'eael_eventon_calendar_' . $key;

            if (!empty($settings[$cat_id])) {
                if ($cat == 'post_tag') {
                    $cat = 'event_tag';
                }
                $default_attr[$cat] = implode(',', $settings[$cat_id]);
            }
        }

        EVO()->calendar->shortcode_args = $default_attr;
        $content = EVO()->evo_generator->_generate_events();
        $events = $content['data'];

        if (!empty($events)) {
            $data = [];
            foreach ($events as $key => $event) {
                $event_id = $event['ID'];
                $date_format = 'Y-m-d';
                $all_day = 'yes';
                $featured = get_post_meta($event_id, '_featured', true);

                $end = date($date_format, ($event['event_end_unix'] + 86400));
                if (get_post_meta($event_id, 'evcal_allday', true) === 'no') {
                    $date_format .= ' H:i';
                    $all_day = '';
                    $end = date($date_format, $event['event_end_unix']);
                }

                $data[] = [
                    'id' => $event_id,
                    'title' => !empty($event['event_title']) ? html_entity_decode($event['event_title'], ENT_QUOTES) : __('No Title', 'essential-addons-for-elementor'),
                    'description' => $content = get_post_field('post_content', $event_id),
                    'start' => date($date_format, $event['event_start_unix']),
                    'end' => $end,
                    'borderColor' => '#6231FF',
                    'textColor' => $settings['eael_event_global_text_color'],
                    'color' => ($featured == 'yes') ? $settings['eael_event_on_featured_color'] : $settings['eael_event_global_bg_color'],
                    'url' => ($settings['eael_event_details_link_hide'] !== 'yes') ? get_the_permalink($key) : '',
                    'allDay' => $all_day,
                    'external' => 'on',
                    'nofollow' => 'on',
                    'eventHasComplete' => get_post_meta($event_id, '_completed', true),
                    'hideEndDate' => get_post_meta($event_id, 'evo_hide_endtime', true),
                ];
            }
        }

        return $data;
    }

    /**
     * Woo Checkout Layout
     */
    public function eael_woo_checkout_layout($layout)
    {
        if (apply_filters('eael/pro_enabled', false)) {
            $layout['multi-steps'] = __('Multi Steps', 'essential-addons-elementor');
            $layout['split'] = __('Split', 'essential-addons-elementor');
        } else {
            $layout['multi-steps'] = __('Multi Steps', 'essential-addons-elementor');
            $layout['split'] = __('Split (Pro)', 'essential-addons-elementor');
        }

        return $layout;
    }

    /**
     * Woo Checkout Layout Template
     */
    public function add_woo_checkout_pro_layout($checkout, $settings)
    {
        if ($settings['ea_woo_checkout_layout'] == 'split') {
            echo self::woo_checkout_render_split_template_($checkout, $settings);
        } elseif ($settings['ea_woo_checkout_layout'] == 'multi-steps') {
            echo self::woo_checkout_render_multi_steps_template_($checkout, $settings);
        }
    }

    /**
     * Woo Checkout Tab Data Settings
     */
    public function add_woo_checkout_tabs_data($obj)
    {

        $obj->add_control('ea_woo_checkout_tabs_settings', [
            'label' => __('Tabs Label', 'essential-addons-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'ea_woo_checkout_layout!' => 'default',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_tab_login_text', [
            'label' => __('Login', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'default' => __('Login', 'essential-addons-elementor'),
            'condition' => [
                'ea_woo_checkout_layout!' => 'default',
            ],
            'description' => 'To preview the changes in Login tab, turn on the Settings from \'Login\' section below.',
        ]);
        $obj->add_control('ea_woo_checkout_tab_coupon_text', [
            'label' => __('Coupon', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'default' => __('Coupon', 'essential-addons-elementor'),
            'condition' => [
                'ea_woo_checkout_layout!' => 'default',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_tab_billing_shipping_text', [
            'label' => __('Billing & Shipping', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'default' => __('Billing & Shipping', 'essential-addons-elementor'),
            'condition' => [
                'ea_woo_checkout_layout!' => 'default',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_tab_payment_text', [
            'label' => __('Payment', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'default' => __('Payment', 'essential-addons-elementor'),
            'condition' => [
                'ea_woo_checkout_layout!' => 'default',
            ],
        ]);

        $obj->add_control('ea_woo_checkout_tabs_btn_settings', [
            'label' => __('Previous/Next Label', 'essential-addons-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'ea_woo_checkout_layout!' => 'default',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_tabs_btn_next_text', [
            'label' => __('Next', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'default' => __('Next', 'essential-addons-elementor'),
            'condition' => [
                'ea_woo_checkout_layout!' => 'default',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_tabs_btn_prev_text', [
            'label' => __('Previous', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'default' => __('Previous', 'essential-addons-elementor'),
            'condition' => [
                'ea_woo_checkout_layout!' => 'default',
            ],
        ]);
    }

    /**
     * Woo Checkout Layout
     */
    public function add_woo_checkout_tabs_styles($obj)
    {

        $obj->start_controls_section('ea_section_woo_checkout_tabs_styles', [
            'label' => esc_html__('Tabs', 'essential-addons-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'ea_woo_checkout_layout!' => 'default',
            ],
        ]);

        $obj->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'ea_section_woo_checkout_tabs_typo',
            'selector' => '{{WRAPPER}} .ea-woo-checkout.layout-split .layout-split-container .info-area .split-tabs li, {{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs .ms-tab',
            'fields_options' => [
                'font_size' => [
                    'default' => [
                        'unit' => 'px',
                        'size' => 16,
                    ],
                ],
            ],
        ]);

        $obj->start_controls_tabs('ea_woo_checkout_tabs_tabs');
        $obj->start_controls_tab('ea_woo_checkout_tabs_tab_normal', ['label' => esc_html__('Normal', 'essential-addons-elementor')]);

        $obj->add_control('ea_woo_checkout_tabs_bg_color', [
            'label' => esc_html__('Background Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#f4f6fc',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-split .layout-split-container .info-area .split-tabs' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'ea_woo_checkout_layout' => 'split',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_tabs_color', [
            'label' => esc_html__('Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#404040',
            'selectors' => [
                '{{WRAPPER}} .split-tabs li, {{WRAPPER}} .ms-tabs li' => 'color: {{VALUE}};',
            ],
        ]);

        $obj->end_controls_tab();

        $obj->start_controls_tab('ea_woo_checkout_tabs_tab_active', ['label' => esc_html__('Active', 'essential-addons-elementor')]);

        $obj->add_control('ea_woo_checkout_tabs_bg_color_active', [
            'label' => esc_html__('Background Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#7866ff',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-split .layout-split-container .info-area .split-tabs li.active' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'ea_woo_checkout_layout' => 'split',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_tabs_color_active', [
            'label' => esc_html__('Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-split .layout-split-container .info-area .split-tabs li.active' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'ea_woo_checkout_layout' => 'split',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_tabs_ms_color_active', [
            'label' => esc_html__('Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#7866ff',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li.completed' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'ea_woo_checkout_layout' => 'multi-steps',
            ],
        ]);
        $obj->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'ea_woo_checkout_tabs_box_shadow',
            'separator' => 'before',
            'selector' => '{{WRAPPER}} .ea-woo-checkout.layout-split .layout-split-container .info-area .split-tabs li.active',
            'condition' => [
                'ea_woo_checkout_layout' => 'split',
            ],
        ]);

        $obj->end_controls_tab();
        $obj->end_controls_tabs();

        $obj->add_responsive_control('ea_woo_checkout_tabs_border_radius', [
            'label' => __('Border Radius', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 05,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .split-tabs, {{WRAPPER}} .split-tab li.active' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'ea_woo_checkout_layout' => 'split',
            ],
        ]);
        $obj->add_responsive_control('ea_woo_checkout_tabs_padding', [
            'label' => esc_html__('Padding', 'essential-addons-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [
                'top' => '17',
                'right' => '17',
                'bottom' => '17',
                'left' => '17',
                'unit' => 'px',
                'isLinked' => true,
            ],
            'size_units' => [
                'px',
                'em',
                '%',
            ],
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-split .layout-split-container .info-area .split-tabs li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'ea_woo_checkout_layout' => 'split',
            ],
        ]);

        $obj->add_responsive_control('ea_woo_checkout_tabs_bottom_gap', [
            'label' => esc_html__('Bottom Gap', 'essential-addons-for-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'max' => 50,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 30,
            ],
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs' => 'margin: 0 0 {{SIZE}}{{UNIT}} 0;',
            ],
            'condition' => [
                'ea_woo_checkout_layout' => 'multi-steps',
            ],
        ]);
        // multi steps
        $obj->add_control('ea_woo_checkout_tabs_steps', [
            'label' => __('Steps', 'essential-addons-elementor'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'ea_woo_checkout_layout' => 'multi-steps',
            ],
        ]);
        $obj->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'ea_section_woo_checkout_tabs_steps_typo',
            'selector' => '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before',
            'fields_options' => [
                'font_size' => [
                    'default' => [
                        'unit' => 'px',
                        'size' => 12,
                    ],
                ],
            ],
            'condition' => [
                'ea_woo_checkout_layout' => 'multi-steps',
            ],
        ]);
        $obj->start_controls_tabs('ea_woo_checkout_tabs_steps_tabs', [
            'condition' => [
                'ea_woo_checkout_layout' => 'multi-steps',
            ],
        ]);
        $obj->start_controls_tab('ea_woo_checkout_tabs_steps_tab_normal', ['label' => esc_html__('Normal', 'essential-addons-elementor')]);
        $obj->add_control('ea_woo_checkout_tabs_steps_bg_color', [
            'label' => esc_html__('Background Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#d3c9f7',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before' => 'background-color: {{VALUE}};',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_tabs_steps_color', [
            'label' => esc_html__('Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#7866FF',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before' => 'color: {{VALUE}};',
            ],
        ]);
        $obj->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'ea_woo_checkout_tabs_steps_border',
            'selector' => '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before',
        ]);
        $obj->add_control('ea_woo_checkout_tabs_steps_connector_color', [
            'label' => esc_html__('Connector Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#d3c9f7',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:after' => 'background-color: {{VALUE}};',
            ],
        ]);
        $obj->end_controls_tab();
        $obj->start_controls_tab('ea_woo_checkout_tabs_steps_tab_active', ['label' => esc_html__('Active', 'essential-addons-elementor')]);
        $obj->add_control('ea_woo_checkout_tabs_steps_bg_color_active', [
            'label' => esc_html__('Background Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#7866ff',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li.completed:before' => 'background-color: {{VALUE}};',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_tabs_steps_color_active', [
            'label' => esc_html__('Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li.completed:before' => 'color: {{VALUE}};',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_tabs_steps_connector_color_active', [
            'label' => esc_html__('Connector Color', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#7866ff',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li.completed:after' => 'background-color: {{VALUE}};',
            ],
        ]);
        $obj->end_controls_tab();
        $obj->end_controls_tabs();
        $obj->add_responsive_control('ea_woo_checkout_tabs_steps_size', [
            'label' => __('Size', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 25,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:after' => 'top: calc(({{SIZE}}{{UNIT}}/2) - 2px);',
            ],
            'condition' => [
                'ea_woo_checkout_layout' => 'multi-steps',
            ],
        ]);
        $obj->add_responsive_control('ea_woo_checkout_tabs_steps_border_radius', [
            'label' => __('Border Radius', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 50,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'ea_woo_checkout_layout' => 'multi-steps',
            ],
        ]);

        $obj->end_controls_section();
    }

    /**
     * Woo Checkout section
     */
    public function add_woo_checkout_section_styles($obj)
    {

        $obj->start_controls_section('ea_section_woo_checkout_section_styles', [
            'label' => esc_html__('Section', 'essential-addons-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'ea_woo_checkout_layout' => 'multi-steps',
            ],
        ]);
        $obj->add_control('ea_woo_checkout_section_bg_color', [
            'label' => esc_html__('Background', 'essential-addons-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .ms-tabs-content' => 'background-color: {{VALUE}};',
            ],
        ]);

        $obj->add_responsive_control('ea_woo_checkout_section_border_radius', [
            'label' => __('Border Radius', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 05,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 50,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .ms-tabs-content' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $obj->add_responsive_control('ea_woo_checkout_section_padding', [
            'label' => esc_html__('Padding', 'essential-addons-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [
                'top' => '25',
                'right' => '25',
                'bottom' => '25',
                'left' => '25',
                'unit' => 'px',
                'isLinked' => true,
            ],
            'size_units' => [
                'px',
                'em',
                '%',
            ],
            'selectors' => [
                '{{WRAPPER}} .ms-tabs-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $obj->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'ea_woo_checkout_section_box_shadow',
            'separator' => 'before',
            'selector' => '{{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs-content-wrap .ms-tabs-content',
        ]);

        $obj->end_controls_section();
    }

    /**
     * Woo Checkout Tab Data Style
     */
    public function add_woo_checkout_steps_btn_styles($obj)
    {

        $obj->start_controls_section('ea_section_woo_checkout_steps_btn_styles', [
            'label' => esc_html__('Previous/Next Button', 'essential-addons-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'ea_woo_checkout_layout!' => 'default',
            ],
        ]);

        $obj->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'ea_woo_checkout_steps_btn_typo',
            'selector' => '{{WRAPPER}} .steps-buttons button',
        ]);
        $obj->start_controls_tabs('ea_woo_checkout_steps_btn_tabs');
        $obj->start_controls_tab('ea_woo_checkout_steps_btn_tab_normal', ['label' => __('Normal', 'essential-addons-for-elementor')]);

        $obj->add_control('ea_woo_checkout_steps_btn_bg_color', [
            'label' => __('Background Color', 'essential-addons-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#7866ff',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout .steps-buttons button' => 'background-color: {{VALUE}};',
            ],
        ]);

        $obj->add_control('ea_woo_checkout_steps_btn_color', [
            'label' => __('Color', 'essential-addons-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout .steps-buttons button' => 'color: {{VALUE}};',
            ],
        ]);

        $obj->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'ea_woo_checkout_steps_btn_border',
            'selector' => '{{WRAPPER}} .ea-woo-checkout .steps-buttons button',
        ]);

        $obj->end_controls_tab();

        $obj->start_controls_tab('ea_woo_checkout_steps_btn_tab_hover', ['label' => __('Hover', 'essential-addons-for-elementor')]);

        $obj->add_control('ea_woo_checkout_steps_btn_bg_color_hover', [
            'label' => __('Background Color', 'essential-addons-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#7866ff',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout .steps-buttons button:hover' => 'background-color: {{VALUE}};',
            ],
        ]);

        $obj->add_control('ea_woo_checkout_steps_btn_color_hover', [
            'label' => __('Color', 'essential-addons-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout .steps-buttons button:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $obj->add_control('ea_woo_checkout_steps_btn_border_color_hover', [
            'label' => __('Border Color', 'essential-addons-for-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout .steps-buttons button:hover' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'ea_section_woo_checkout_steps_btn_border_border!' => '',
            ],
        ]);

        $obj->end_controls_tab();
        $obj->end_controls_tabs();

        $obj->add_control('ea_woo_checkout_steps_btn_border_radius', [
            'label' => __('Border Radius', 'essential-addons-for-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [
                'px',
                '%',
            ],
            'default' => [
                'top' => '5',
                'right' => '5',
                'bottom' => '5',
                'left' => '5',
                'unit' => 'px',
                'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .ea-woo-checkout .steps-buttons button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $obj->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'ea_woo_checkout_steps_btn_box_shadow',
            'selector' => '{{WRAPPER}} .ea-woo-checkout .steps-buttons button',
        ]);
        $obj->add_responsive_control('ea_woo_checkout_steps_btn_padding', [
            'label' => esc_html__('Padding', 'essential-addons-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'default' => [
                'top' => '13',
                'right' => '20',
                'bottom' => '13',
                'left' => '20',
                'unit' => 'px',
                'isLinked' => true,
            ],
            'size_units' => [
                'px',
                'em',
                '%',
            ],
            'selectors' => [
                '{{WRAPPER}} .steps-buttons button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $obj->add_responsive_control('ea_woo_checkout_steps_btn_align', [
            'label' => __('Alignment', 'elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => __('Left', 'elementor'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'elementor'),
                    'icon' => 'eicon-text-align-center',
                ],
                'flex-end' => [
                    'title' => __('Right', 'elementor'),
                    'icon' => 'eicon-text-align-right',
                ],
                'space-between' => [
                    'title' => __('Justified', 'elementor'),
                    'icon' => 'eicon-text-align-justify',
                ],
            ],
            'default' => 'flex-start',
            'selectors' => [
                '{{WRAPPER}} .steps-buttons' => 'justify-content: {{VALUE}};',
            ],
        ]);
        $obj->add_responsive_control('ea_woo_checkout_steps_btn_gap', [
            'label' => __('Gap', 'essential-addons-elementor'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 10,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .steps-buttons button:first-child' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2);',
                '{{WRAPPER}} .steps-buttons button:last-child' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2);',
            ],
        ]);

        $obj->end_controls_section();
    }

    /**
     * Add ajax control
     *
     * @param Login_Register $lr
     */
    public function lr_init_content_ajax_controls($lr)
    {
        $lr->add_control('enable_ajax', [
            'label' => __('Submit Form via AJAX', 'essential-addons-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

    }

    /**
     * Add Social Login related controls
     *
     * @param Login_Register $lr
     */
    public function lr_init_content_social_login_controls($lr)
    {
        $lr->start_controls_section('section_content_social_login', [
            'label' => __('Social Login', 'essential-addons-elementor'),
            'conditions' => $lr->get_form_controls_display_condition('login'),
        ]);
        $lr->add_control('enable_google_login', [
            'label' => __('Enable Login with Google', 'essential-addons-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'render_type' => 'template',
        ]);
        if (empty(get_option('eael_g_client_id'))) {
            $lr->add_control('eael_g_client_id_missing', [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => sprintf(__('Google Client ID is missing. Please add it from %sDashboard >> Essential Addons >> Elements >> Login | Register Form %sSettings', 'essential-addons-for-elementor-lite'), '<strong>', '</strong>'),
                'content_classes' => 'eael-warning',
                'condition' => [
                    'enable_google_login' => 'yes',
                ],
            ]);
        }
        $lr->add_control('google_login_text', [
            'label' => __('Text for Google Button', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'default' => __('Login with Google', 'essential-addons-elementor'),
            'placeholder' => __('Login with Google', 'essential-addons-elementor'),
            'condition' => [
                'enable_google_login' => 'yes',
            ],
        ]);
        $lr->add_control('enable_fb_login', [
            'label' => __('Enable Login with Facebook', 'essential-addons-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'render_type' => 'template',
        ]);
        if (empty(get_option('eael_fb_app_id')) || empty(get_option('eael_fb_app_secret'))) {
            $lr->add_control('eael_fb_app_id_missing', [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => sprintf(__('Facebook API keys are missing. Please add them from %sDashboard >> Essential Addons >> Elements >> Login | Register Form %sSettings', 'essential-addons-for-elementor-lite'), '<strong>', '</strong>'),
                'content_classes' => 'eael-warning',
                'condition' => [
                    'enable_google_login' => 'yes',
                ],
            ]);
        }
        $lr->add_control('fb_login_text', [
            'label' => __('Text for Facebook Button', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'default' => __('Login with Facebook', 'essential-addons-elementor'),
            'placeholder' => __('Login with Facebook', 'essential-addons-elementor'),
            'condition' => [
                'enable_fb_login' => 'yes',
            ],
        ]);

        $lr->add_control('show_separator', [
            'label' => __('Show Separator', 'essential-addons-elementor'),
            'type' => Controls_Manager::SWITCHER,
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'name' => "enable_fb_login",
                        'value' => 'yes',
                    ],
                    [
                        'name' => 'enable_google_login',
                        'value' => 'yes',
                    ],
                ],
            ],
        ]);

        $lr->add_control('separator_type', [
            'label' => __('Separator Type', 'essential-addons-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'hr' => __('Line', 'essential-addons-elementor'),
                'text' => __('Text', 'essential-addons-elementor'),
            ],
            'default' => 'hr',
            'condition' => [
                'show_separator' => 'yes',
            ],
        ]);

        $lr->add_control('separator_text', [
            'label' => __('Separator Text', 'essential-addons-elementor'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => [
                'active' => true,
            ],
            'default' => __('Or', 'essential-addons-elementor'),
            'placeholder' => __('Eg. Or', 'essential-addons-elementor'),
            'condition' => [
                'separator_type' => 'text',
            ],
        ]);

        $lr->end_controls_section();
    }

    /**
     * It prints Social Login related markup
     *
     * @param Login_Register $lr
     */
    public function lr_print_social_login($lr)
    {
        $should_print_google = $should_print_fb = false;
        $gbtn_text = $gclient = $app_id = $fbtn_text = '';
        if ('yes' === $lr->get_settings_for_display('enable_google_login')) {
            $gclient = get_option('eael_g_client_id');
            $should_print_google = true;

            $gbtn_text = apply_filters('eael/login-register/google-button-text', $lr->get_settings_for_display('google_login_text'));

        }

        if ('yes' === $lr->get_settings_for_display('enable_fb_login')) {
            $app_id = get_option('eael_fb_app_id');
            $should_print_fb = true;

            $fbtn_text = apply_filters('eael/login-register/fb-button-text', $lr->get_settings_for_display('fb_login_text'));

        }
        $show_sep = $lr->get_settings_for_display('show_separator');
        $sep_type = $lr->get_settings_for_display('separator_type');
        $sep_text = $lr->get_settings_for_display('separator_text');

        if ($should_print_google || $should_print_fb) {?>
            <div class="lr-social-login-container"
                 data-widget-id="<?php echo esc_attr($lr->get_id()); ?>">
				<?php
if ('yes' === $show_sep) {?>
                    <div class="lr-separator">
						<?php if ('hr' === $sep_type) {
            echo '<hr>';
        } elseif ('text' === $sep_type) {
            printf("<p>%s</p>", esc_html($sep_text));
        }?>
                    </div>
				<?php }?>
                <div class="lr-social-buttons-container">
					<?php
if ($should_print_google) {
            $this->lr_print_google_button($gclient, $lr, $gbtn_text);
        }
            if ($should_print_fb) {
                $this->lr_print_facebook_button($app_id, $lr, $fbtn_text);
            }
            ?>
                </div>
            </div>
			<?php
}
    }

    /**
     * It prints google login button
     *
     * @param string         $client_id
     * @param Login_Register $lr
     * @param string         $btn_text
     */
    public function lr_print_google_button($client_id, $lr, $btn_text = '')
    {
        ?>
        <div class="eael-social-button eael-google"
             id="eael-google-login-btn-<?php echo esc_attr($lr->get_id()); ?>"
             data-g-client-id="<?php echo esc_attr($client_id); ?>">
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="18px"
                 height="18px"
                 viewBox="0 0 48 48"
                 class="eael-gicon-svg">
                <g>
                    <path fill="#EA4335"
                          d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                    <path fill="#4285F4"
                          d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                    <path fill="#FBBC05"
                          d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                    <path fill="#34A853"
                          d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                    <path fill="none"
                          d="M0 0h48v48H0z"></path>
                </g>
            </svg>
			<?php
if ($btn_text) {
            printf("<span class='eael-gbtn-text'>%s</span>", esc_html($btn_text));
        }
        ?>
        </div>
		<?php
}

    /**
     * It prints facebook login button
     *
     * @param string         $app_id
     * @param Login_Register $lr
     * @param string         $btn_text
     */
    public function lr_print_facebook_button($app_id, $lr, $btn_text = '')
    {
        ?>
        <div class="eael-social-button eael-facebook"
             id="eael-fb-login-btn-<?php echo esc_attr($lr->get_id()); ?>"
             data-fb-appid="<?php echo esc_attr($app_id); ?>">
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="18px"
                 height="18px"
                 viewBox="0 0 216 216"
                 class="_5h0m"
                 fill="#4d6fa9">
                <path d="M204.1 0H11.9C5.3 0 0 5.3 0 11.9v192.2c0 6.6 5.3 11.9 11.9 11.9h103.5v-83.6H87.2V99.8h28.1v-24c0-27.9 17-43.1 41.9-43.1 11.9 0 22.2.9 25.2 1.3v29.2h-17.3c-13.5 0-16.2 6.4-16.2 15.9v20.8h32.3l-4.2 32.6h-28V216h55c6.6 0 11.9-5.3 11.9-11.9V11.9C216 5.3 210.7 0 204.1 0z"></path>
            </svg>
			<?php
if ($btn_text) {
            printf("<span class='eael-fbtn-text'>%s</span>", esc_html($btn_text));
        }
        ?>
        </div>
        <?php
    }

	public function lr_handle_social_login() {
		// verify security for social login
		if ( ! empty( $_POST['eael-google-submit'] ) || ! empty( $_POST['eael-facebook-submit'] ) ) {
			check_ajax_referer( 'eael-login-action', 'nonce' );
			if ( is_user_logged_in() ) {
				wp_send_json_error( __( 'You are already logged in.', 'essential-addons-elementor' ) );
			}
		}


		if ( ! empty( $_POST['eael-google-submit'] ) ) {
			$client_id     = get_option( 'eael_g_client_id' );
			$id_token      = ! empty( $_POST['id_token'] ) ? sanitize_text_field( $_POST['id_token'] ) : '';
			$name          = isset( $_POST['full_name'] ) ? sanitize_text_field( $_POST['full_name'] ) : '';
			$email         = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
			$verified_data = $this->lr_verify_google_user_data( $id_token, $client_id );

			if ( empty( $verified_data ) ) {
				wp_send_json_error( __( 'User data was not verified by Google', 'essential-addons-elementor' ) );
			}
			// verified data
			$v_name      = isset( $verified_data['name'] ) ? $verified_data['name'] : '';
			$v_email     = isset( $verified_data['email'] ) ? $verified_data['email'] : '';
			$v_client_id = isset( $verified_data['aud'] ) ? $verified_data['aud'] : '';

			// Check if email is verified with Google.
			if ( ( $client_id !== $v_client_id ) || ( $email !== $v_email ) || ( $name !== $v_name ) ) {
				wp_send_json_error( __( 'User data was not verified by Google', 'essential-addons-elementor' ) );
			}

			$this->lr_log_user_using_social_data( $v_name, $v_email, 'google' );
		}

		if ( ! empty( $_POST['eael-facebook-submit'] ) ) {

			$app_id       = get_option( 'eael_fb_app_id' );
			$app_secret   = get_option( 'eael_fb_app_secret' );
			$access_token = ! empty( $_POST['access_token'] ) ? sanitize_text_field( $_POST['access_token'] ) : '';
			$user_id      = ! empty( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : 0;
			$name         = isset( $_POST['full_name'] ) ? sanitize_text_field( $_POST['full_name'] ) : '';
			$email        = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
			if ( empty( $user_id ) ) {
				wp_send_json_error( __( 'Facebook authorization failed', 'essential-addons-elementor' ) );
			}

			$fb_user_data = $this->lr_get_facebook_user_profile( $access_token, $app_id, $app_secret );


			if ( empty( $user_id ) || empty( $fb_user_data ) || empty( $app_id ) || empty( $app_secret ) || ( $user_id !== $fb_user_data['data']['user_id'] ) || ( $app_id !== $fb_user_data['data']['app_id'] ) || ( ! $fb_user_data['data']['is_valid'] ) ) {
				wp_send_json_error( __( 'Facebook authorization failed', 'essential-addons-elementor' ) );
			}


			$res = $this->lr_get_user_email_facebook( $fb_user_data['data']['user_id'], $access_token );
			//Some facebook user may not have $email as they might have used mobile number to open account
			if ( ! empty( $email ) && ( empty( $res['email'] ) || $res['email'] !== $email ) ) {
				//if js SDK sends email, then php api must return the same email.
				wp_send_json_error( __( 'Facebook email validation failed', 'essential-addons-elementor' ) );
			}

			$v_email = ! empty( $email ) && ! empty( $res['email'] ) ? sanitize_email( $res['email'] ) : $fb_user_data['user_id'] . '@facebook.com';

			$this->lr_log_user_using_social_data( $name, $v_email, 'facebook' );

		}

	}

	/**
	 * @param string $name
	 * @param string $email
	 * @param string $login_source eg. Google, Facebook etc.
	 */
	public function lr_log_user_using_social_data( $name, $email, $login_source = '' ) {
		$response             = [];
		$username             = strtolower( preg_replace( '/\s+/', '', $name ) );
		$response['username'] = $username;
		$user_data            = get_user_by( 'email', $email ); // do we have user by this email already?

		if ( ! empty( $user_data ) ) {
			//user already registered using this email, so log him in.
			$user_ID = $user_data->ID;
			wp_set_auth_cookie( $user_ID );
			wp_set_current_user( $user_ID, $username );
			do_action( 'wp_login', $user_data->user_login, $user_data );

		} else {
			// user is new, so let's register him
			$password = wp_generate_password( 12, true, false );

			if ( username_exists( $username ) ) {
				// Generate something unique to append to the username in case of a conflict with another user.
				$suffix   = '-' . zeroise( wp_rand( 0, 9999 ), 4 );
				$username .= $suffix;

				$user_array = [
					'user_login' => $username,
					'user_pass'  => $password,
					'user_email' => $email,
					'first_name' => $name,
				];
				$result     = wp_insert_user( $user_array );
				if ( is_wp_error( $result ) ) {
					wp_send_json_error( __( 'Logging user failed.', 'essential-addons-elementor' ) );
				}
			} else {
				$result = wp_create_user( $username, $password, $email );
				if ( is_wp_error( $result ) ) {
					wp_send_json_error( __( 'Logging user failed.', 'essential-addons-elementor' ) );
				}
			}

			//@TODO; send email to user/admin later
			//do_action( 'edit_user_created_user', $result, $notify );

			$user_data = get_user_by( 'email', $email );

			if ( $user_data ) {
				$user_ID   = $user_data->ID;
				$user_meta = [
					'login_source' => $login_source,
				];

				update_user_meta( $user_ID, 'eael_login_form', $user_meta );

				if ( wp_check_password( $password, $user_data->user_pass, $user_data->ID ) ) {
					wp_set_auth_cookie( $user_ID );
					wp_set_current_user( $user_ID, $username );
					do_action( 'wp_login', $user_data->user_login, $user_data );
				} else {
					wp_send_json_error( __( 'Logging user failed.', 'essential-addons-elementor' ) );
				}
			}
		}
		$response ['message'] = __( 'You are logged in successfully', 'essential-addons-elementor' );
		if ( ! empty( $_POST['redirect_to'] ) ) {
			$response['redirect_to'] = esc_url( $_POST['redirect_to'] );
		}

		wp_send_json_success( $response );
	}

	/**
	 * It verifies id token generated or sent from google js sdk
	 *
	 * @param string $id_token  id token generated via google js sdk
	 * @param string $client_id the client api key generated in the google console
	 *
	 * @return array|false
	 */
	public function lr_verify_google_user_data( $id_token, $client_id ) {
		// load composer autoloader
		$composer_autoloader = EAEL_PRO_PLUGIN_PATH . 'vendor/autoload.php';
		if ( file_exists( $composer_autoloader ) ) {
			require_once $composer_autoloader;
		}

		if ( ! class_exists( '\Google_Client' ) ) {
			error_log( 'Google_client class was not loaded. did you run composer install?' );

			return false;
		}
		$client        = new Google_Client( [ 'client_id' => $client_id ] );
		$verified_data = $client->verifyIdToken( $id_token );
		if ( $verified_data ) {
			return $verified_data;
		}

		return false;
	}

	/**
	 * Get facebook user profile
	 *
	 * @param string $access_token Access Token.
	 * @param string $app_id       App ID.
	 * @param string $app_secret   Secret token.
	 *
	 * @return mixed
	 */
	public function lr_get_facebook_user_profile( $access_token, $app_id, $app_secret ) {

		$fb_url = 'https://graph.facebook.com/oauth/access_token';
		$fb_url = add_query_arg( [
			'client_id'     => $app_id,
			'client_secret' => $app_secret,
			'grant_type'    => 'client_credentials',
		], $fb_url );

		$fb_response = wp_remote_get( $fb_url );

		if ( is_wp_error( $fb_response ) ) {
			wp_send_json_error();
		}

		$fb_app_response = json_decode( wp_remote_retrieve_body( $fb_response ), true );

		$app_token = $fb_app_response['access_token'];

		$url = 'https://graph.facebook.com/debug_token';
		$url = add_query_arg( [
			'input_token'  => $access_token,
			'access_token' => $app_token,
		], $url );

		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	/**
	 * Get user email of authenticated facebook user
	 *
	 * @param string $user_id      User ID.
	 * @param string $access_token User Access Token.
	 *
	 * @return mixed
	 */
	public function lr_get_user_email_facebook( $user_id, $access_token ) {
		$fb_email_url = 'https://graph.facebook.com/' . $user_id;
		$fb_email_url = add_query_arg( [
			'fields'       => 'email',
			'access_token' => $access_token,
		], $fb_email_url );

		$email_response = wp_remote_get( $fb_email_url );

		if ( is_wp_error( $email_response ) ) {
			return false;
		}

		return json_decode( wp_remote_retrieve_body( $email_response ), true );
	}

	/**
	 * It adds styling controls for social login
	 *
	 * @param Login_Register $lr
	 */
	public function lr_init_style_social_controls( Login_Register $lr ) {
		$lr->start_controls_section( 'section_style_social_login', [
			'label'      => __( 'Social Login Style', 'essential-addons-for-elementor' ),
			'tab'        => Controls_Manager::TAB_STYLE,
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'  => "enable_fb_login",
						'value' => 'yes',
					],
					[
						'name'  => 'enable_google_login',
						'value' => 'yes',
					],
				],
			],
		] );
		$container = "{{WRAPPER}} .lr-social-login-container";
		$lr->add_control( 'eael_sl_pot', [
			'label'        => __( 'Social Container', 'essential-addons-for-elementor' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor' ),
			'return_value' => 'yes',
		] );
		$lr->start_popover();
		$lr->add_responsive_control( "eael_sl_wrap_width", [
			'label'      => esc_html__( 'Width', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'  => [
				$container => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'eael_sl_pot' => 'yes',
			],
		] );

		$lr->add_responsive_control( "eael_sl_wrap_height", [
			'label'      => esc_html__( 'Height', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				$container => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'eael_sl_pot' => 'yes',
			],
		] );

		$lr->add_responsive_control( "eael_sl_wrap_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$container => $lr->apply_dim( 'margin' ),
			],
			'condition'  => [
				'eael_sl_pot' => 'yes',
			],
		] );
		$lr->add_responsive_control( "eael_sl_wrap_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$container => $lr->apply_dim( 'padding' ),
			],
			'condition'  => [
				'eael_sl_pot' => 'yes',
			],
		] );
		$lr->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "eael_sl_wrap_border",
			'selector'  => $container,
			'condition' => [
				'eael_sl_pot' => 'yes',
			],
		] );
		$lr->add_control( "eael_sl_wrap_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$container => $lr->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				'eael_sl_pot' => 'yes',
			],
			'separator'  => 'after',
		] );
		$lr->add_control( "eael_sl_wrap_text_color", [
			'label'     => __( 'Text Color', 'essential-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				$container => 'color: {{VALUE}};',
			],
			'condition' => [
				'eael_sl_pot' => 'yes',
			],
			'separator' => 'before',
		] );
		$lr->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "eael_sl_wrap_bg_color",
			'label'     => __( 'Background Color', 'essential-addons-for-elementor' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => $container,
			'condition' => [
				'eael_sl_pot' => 'yes',
			],
		] );
		$lr->end_popover();

		$lr->add_responsive_control( "eael_sl_btn_display_type", [
			'label'     => __( 'Display Button as', 'essential-addons-for-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'row'    => __( 'Inline', 'essential-addons-for-elementor' ),
				'column' => __( 'Block', 'essential-addons-for-elementor' ),
			],
			'default'   => 'row',
			'selectors' => [
				"{$container} .lr-social-buttons-container" => 'flex-direction: {{VALUE}};',
			],
		] );
		//Social Buttons
		$this->lr_init_style_social_btn_controls( $lr, 'google' );
		$this->lr_init_style_social_btn_controls( $lr, 'facebook' );
		// Separator
		$this->lr_init_style_social_separator_controls( $lr );
		$lr->end_controls_section();
	}

	/**
	 * @param Login_Register $lr
	 * @param string         $btn_type
	 */
	public function lr_init_style_social_btn_controls( Login_Register $lr, $btn_type = 'google' ) {
		$btn_class  = "{{WRAPPER}} .lr-social-login-container .eael-social-button.eael-{$btn_type}";
		$icon_class = "{$btn_class} svg";
		$width      = 'google' === $btn_type ? 175 : 190;

		$condition_name = 'facebook' === $btn_type ? 'enable_fb_login' : "enable_{$btn_type}_login";

		$lr->add_control( "eael_sl_{$btn_type}_btn_pot", [
			'label'        => sprintf( __( '%s Button', 'essential-addons-for-elementor' ), ucfirst( $btn_type ) ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor' ),
			'return_value' => 'yes',
			'separator'    => 'before',
			'condition'    => [ $condition_name => 'yes' ],
		] );

		$lr->start_popover();
		$lr->add_control( "eael_sl_{$btn_type}_btn_heading", [
			'label'     => __( 'Button Style', 'essential-addons-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				"eael_sl_{$btn_type}_btn_pot" => 'yes',
			],
		] );
		$lr->add_responsive_control( "eael_sl_{$btn_type}_btn_width", [
			'label'      => esc_html__( 'Button Width', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => 'px',
				'size' => $width,
			],
			'selectors'  => [
				$btn_class => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				"eael_sl_{$btn_type}_btn_pot" => 'yes',
			],
		] );
		$lr->add_responsive_control( "eael_sl_{$btn_type}_btn_height", [
			'label'      => esc_html__( 'Button Height', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				$btn_class => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				"eael_sl_{$btn_type}_btn_pot" => 'yes',
			],
		] );
		$lr->add_responsive_control( "eael_sl_{$btn_type}_btn_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$btn_class => $lr->apply_dim( 'margin' ),
			],
			'condition'  => [
				"eael_sl_{$btn_type}_btn_pot" => 'yes',
			],
		] );
		$lr->add_responsive_control( "eael_sl_{$btn_type}_btn_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$btn_class => $lr->apply_dim( 'padding' ),
			],
			'condition'  => [
				"eael_sl_{$btn_type}_btn_pot" => 'yes',
			],
		] );
		$lr->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "eael_sl_{$btn_type}_btn_border",
			'selector'  => $btn_class,
			'condition' => [
				"eael_sl_{$btn_type}_btn_pot" => 'yes',
			],
		] );
		$lr->add_control( "eael_sl_{$btn_type}_btn_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$btn_class => $lr->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				"eael_sl_{$btn_type}_btn_pot" => 'yes',
			],
			'separator'  => 'after',
		] );
		$lr->add_control( "eael_sl_{$btn_type}_btn_text_color", [
			'label'     => __( 'Text Color', 'essential-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				$btn_class => 'color: {{VALUE}};',
			],
			'condition' => [
				"eael_sl_{$btn_type}_btn_pot" => 'yes',
			],
			'separator' => 'before',
		] );
		$lr->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "eael_sl_{$btn_type}_btn_bg_color",
			'label'     => __( 'Background Color', 'essential-addons-for-elementor' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => $btn_class,
			'condition' => [
				"eael_sl_{$btn_type}_btn_pot" => 'yes',
			],
		] );

		$lr->end_popover();
		$lr->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => "eael_sl_{$btn_type}_btn_typo",
			'label'    => sprintf( __( '%s Button Typography', 'essential-addons-for-elementor' ), ucfirst( $btn_type ) ),
			'selector' => $btn_class,
		] );

		// Button icon
		$lr->add_control( "eael_sl_{$btn_type}_icon_pot", [
			'label'        => sprintf( __( '%s  Button Icon', 'essential-addons-for-elementor' ), ucfirst( $btn_type ) ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor' ),
			'return_value' => 'yes',
		] );
		$lr->start_popover();
		$lr->add_responsive_control( "eael_sl_{$btn_type}_icon_width", [
			'label'      => esc_html__( 'Icon Width', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 150,
					'step' => 1,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 18,
			],
			'selectors'  => [
				$icon_class => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				"eael_sl_{$btn_type}_icon_pot" => 'yes',
			],
		] );
		$lr->add_responsive_control( "eael_sl_{$btn_type}_icon_height", [
			'label'      => esc_html__( 'Icon Height', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 150,
					'step' => 1,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 18,
			],
			'selectors'  => [
				$icon_class => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				"eael_sl_{$btn_type}_icon_pot" => 'yes',
			],
		] );
		$lr->add_responsive_control( "eael_sl_{$btn_type}_icon_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$icon_class => $lr->apply_dim( 'margin' ),
			],
			'condition'  => [
				"eael_sl_{$btn_type}_icon_pot" => 'yes',
			],
		] );
		$lr->add_responsive_control( "eael_sl_{$btn_type}_icon_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$icon_class => $lr->apply_dim( 'padding' ),
			],
			'condition'  => [
				"eael_sl_{$btn_type}_icon_pot" => 'yes',
			],
		] );
		$lr->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "eael_sl_{$btn_type}_icon_bg_color",
			'label'     => __( 'Background Color', 'essential-addons-for-elementor' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => $icon_class,
			'condition' => [
				"eael_sl_{$btn_type}_icon_pot" => 'yes',
			],
		] );
		$lr->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "eael_sl_{$btn_type}_icon_border",
			'selector'  => $icon_class,
			'condition' => [
				"eael_sl_{$btn_type}_btn_pot" => 'yes',
			],
		] );
		$lr->add_control( "eael_sl_{$btn_type}_icon_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$icon_class => $lr->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				"eael_sl_{$btn_type}_btn_pot" => 'yes',
			],
			'separator'  => 'after',
		] );
		$lr->end_popover();
	}

	/**
	 * @param Login_Register $lr
	 */
	public function lr_init_style_social_separator_controls( Login_Register $lr ) {
		$sep_class = '{{WRAPPER}} .lr-social-login-container .lr-separator';
		$sep_text  = '{{WRAPPER}} .lr-social-login-container .lr-separator p';
		$sep_hr    = '{{WRAPPER}} .lr-social-login-container .lr-separator hr';
		$lr->add_control( 'eael_sl_sep_pot', [
			'label'        => __( 'Separator', 'essential-addons-for-elementor' ),
			'type'         => Controls_Manager::POPOVER_TOGGLE,
			'label_off'    => __( 'Default', 'essential-addons-for-elementor' ),
			'label_on'     => __( 'Custom', 'essential-addons-for-elementor' ),
			'return_value' => 'yes',
			'separator'    => 'before',
			'condition'    => [
				'show_separator' => 'yes',
			],
		] );
		$lr->start_popover();
		$lr->add_responsive_control( "eael_sl_sep_width", [
			'label'      => esc_html__( 'Divider Width', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'  => [
				$sep_hr => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'eael_sl_sep_pot' => 'yes',
				'separator_type'  => 'hr',
			],
		] );

		$lr->add_responsive_control( "eael_sl_sep_height", [
			'label'      => esc_html__( 'Divider Height', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 20,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				$sep_hr => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'eael_sl_sep_pot' => 'yes',
				'separator_type'  => 'hr',
			],
		] );

		$lr->add_responsive_control( "eael_sl_sep_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$sep_text => $lr->apply_dim( 'margin' ),
				$sep_hr   => $lr->apply_dim( 'margin' ),
			],
			'condition'  => [
				'eael_sl_sep_pot' => 'yes',
			],
		] );
		$lr->add_responsive_control( "eael_sl_sep_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$sep_text => $lr->apply_dim( 'padding' ),
				$sep_hr   => $lr->apply_dim( 'padding' ),
			],
			'condition'  => [
				'eael_sl_sep_pot' => 'yes',
			],
		] );
		$lr->add_group_control( Group_Control_Border::get_type(), [
			'name'      => "eael_sl_sep_border",
			'selector'  => $sep_text,
			'condition' => [
				'eael_sl_sep_pot' => 'yes',
				'separator_type'  => 'text',
			],
		] );
		$lr->add_control( "eael_sl_sep_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$sep_hr   => $lr->apply_dim( 'border-radius' ),
				$sep_text => $lr->apply_dim( 'border-radius' ),
			],
			'condition'  => [
				'eael_sl_sep_pot' => 'yes',
			],
			'separator'  => 'after',
		] );
		$lr->add_control( "eael_sl_sep_text_color", [
			'label'     => __( 'Color', 'essential-addons-for-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				$sep_text => 'color: {{VALUE}};',
			],
			'condition' => [
				'eael_sl_sep_pot' => 'yes',
				'separator_type'  => 'text',
			],
			'separator' => 'before',
		] );
		$lr->add_group_control( Group_Control_Background::get_type(), [
			'name'      => "eael_sl_sep_bg_color",
			'label'     => __( 'Background Color', 'essential-addons-for-elementor' ),
			'types'     => [
				'classic',
				'gradient',
			],
			'selector'  => $sep_text . ', ' . $sep_hr,
			'condition' => [
				'eael_sl_sep_pot' => 'yes',
			],
		] );
		$lr->end_popover();
		$lr->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => "eael_sl_sep_typo",
			'label'     => __( 'Separator Typography', 'essential-addons-for-elementor' ),
			'selector'  => $sep_text,
			'condition' => [
				'separator_type' => 'text',
			],
		] );
	}

	/**
	 * @param array $scripts
	 *
	 * @return array
	 */
	public function lr_load_pro_scripts( $scripts ) {
		array_push( $scripts, 'password-strength-meter' );

		return $scripts;
	}

	/**
	 * @param array $styles
	 *
	 * @return array
	 */
	public function lr_load_pro_styles( array $styles ) {
		return array_merge( $styles, [
			'font-awesome-5-all',
			'font-awesome-4-shim',
		] );
	}

	/**
	 * @param Login_Register $lr
	 */
	public function lr_init_content_pass_strength_controls( Login_Register $lr ) {
		$lr->add_control( 'show_register_icon', [
			'label' => __( 'Show Field Icons', 'essential-addons-elementor' ),
			'type'  => Controls_Manager::SWITCHER,
		] );
		$lr->add_control( 'show_ps_meter', [
			'label' => __( 'Show Password Strength Meter', 'essential-addons-elementor' ),
			'type'  => Controls_Manager::SWITCHER,
		] );
		$lr->add_control( 'show_pass_strength', [
			'label' => __( 'Show Password Strength Text', 'essential-addons-elementor' ),
			'type'  => Controls_Manager::SWITCHER,
		] );
		$lr->add_control( 'ps_text_type', [
			'label'     => __( 'Password Strength Text', 'essential-addons-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'default',
			'options'   => [
				'default' => __( 'Default', 'essential-addons-elementor' ),
				'custom'  => __( 'Custom', 'essential-addons-elementor' ),
			],
			'condition' => [
				'show_pass_strength' => 'yes',
			],
		] );
		$pass_type = [
			'short'  => __( 'Very Weak', 'essential-addons-elementor' ),
			'bad'    => __( 'Weak', 'essential-addons-elementor' ),
			'good'   => __( 'Medium', 'essential-addons-elementor' ),
			'strong' => __( 'Strong', 'essential-addons-elementor' ),
		];
		foreach ( $pass_type as $p_type => $label ) {
			$lr->add_control( "ps_text_{$p_type}", [
				/* translators: %s: Strength of the Password eg. Bad, Good etc. */
				'label'       => sprintf( __( '%s Password', 'essential-addons-elementor' ), $label ),
				'type'        => Controls_Manager::TEXT,
				/* translators: %s: Strength of the Password eg. Bad, Good etc. */
				'default'     => sprintf( __( '%s Password', 'essential-addons-elementor' ), $label ),
				'placeholder' => __( 'Eg. Weak or Good etc.', 'essential-addons-elementor' ),
				'condition'   => [
					'show_pass_strength' => 'yes',
					'ps_text_type'       => 'custom',
				],
			] );
		}

		$lr->add_control( 'ps_hint_type', [
			'label'     => __( 'Password Hint', 'essential-addons-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'separator' => 'before',
			'options'   => [
				''        => __( 'None', 'essential-addons-elementor' ),
				'default' => __( 'WordPress Default', 'essential-addons-elementor' ),
				'custom'  => __( 'Custom', 'essential-addons-elementor' ),
			],
		] );
		$lr->add_control( "ps_hint", [
			'label'       => __( 'Custom Password Hint', 'essential-addons-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => __( 'Your custom password hint...', 'essential-addons-elementor' ),
			'condition'   => [
				'ps_hint_type' => 'custom',
			],
		] );
	}

	/**
	 * @param Login_Register $lr
	 */
	public function lr_init_content_icon_controls( Login_Register $lr ) {
		$lr->add_control( 'show_login_icon', [
			'label' => __( 'Show Field Icons', 'essential-addons-elementor' ),
			'type'  => Controls_Manager::SWITCHER,
		] );
	}

	/**
	 * @param Repeater $repeater register field repeater object
	 */
	public function lr_add_register_fields_icons( $repeater ) {
		$repeater->add_control( 'icon', [
			'label'   => __( 'Icon', 'essential-addons-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [
				'value'   => 'fas fa-user',
				'library' => 'fa-solid',
			],
		] );
	}

	/**
	 * @param array $fields register fields default fields array
	 *
	 * @return array $fields
	 */
	public function lr_add_register_fields_default_icons( $fields ) {
		return array_map( function ( $field ) {
			if ( ! isset( $field['field_type'] ) ) {
				return $field;
			}
			switch ( $field['field_type'] ) {
				case 'user_name':
				case 'first_name':
				case 'last_name':
					$field['icon'] = [
						'value'   => 'fas fa-user',
						'library' => 'fa-solid',
					];
					break;
				case 'email':
					$field['icon'] = [
						'value'   => 'fas fa-envelope',
						'library' => 'fa-solid',
					];
					break;
				case 'password':
				case 'confirm_pass':
					$field['icon'] = [
						'value'   => 'fas fa-lock',
						'library' => 'fa-solid',
					];
					break;
				case 'website':
					$field['icon'] = [
						'value'   => 'fas fa-globe',
						'library' => 'fa-solid',
					];
					break;

			}

			return $field;
		}, $fields );
	}

	/**
	 * It adds styling controls for password strength
	 *
	 * @param Login_Register $lr
	 */
	public function lr_init_style_pass_strength_controls( Login_Register $lr ) {
		$lr->start_controls_section( 'section_style_pass_strength', [
			'label' => __( 'Password Strength', 'essential-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
			//'condition' => [
			//	'show_pass_strength' => 'yes',
			//], //@TODO; update to or condition later
		] );
		$container        = "{{WRAPPER}} .pass-meta-info";
		$notice_container = "{{WRAPPER}} .eael-pass-notice";
		$meter             = "{{WRAPPER}} .eael-pass-meter";
		$hint             = "{{WRAPPER}} .eael-pass-hint";
		$lr->add_responsive_control( "eael_ps_wrap_width", [
			'label'      => esc_html__( 'Width', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'  => [
				$container => 'width: {{SIZE}}{{UNIT}};',
			],
		] );
		$lr->add_responsive_control( "eael_ps_wrap_height", [
			'label'      => esc_html__( 'Height', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				$container => 'height: {{SIZE}}{{UNIT}};',
			],
		] );
		$lr->add_responsive_control( "eael_ps_wrap_margin", [
			'label'      => __( 'Box Margin', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$container => $lr->apply_dim( 'margin' ),
			],
		] );

		$lr->add_responsive_control( "eael_ps_wrap_padding", [
			'label'      => __( 'Box Padding', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$container => $lr->apply_dim( 'padding' ),
			],
			'separator'  => 'after',
		] );
		$lr->add_responsive_control( "eael_ps_meter_margin", [
			'label'      => __( 'Meter Margin', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$meter => $lr->apply_dim( 'margin' ),
			],
		] );
		$lr->add_responsive_control( "eael_ps_text_margin", [
			'label'      => __( 'Strength Text Margin', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$notice_container => $lr->apply_dim( 'margin' ),
			],
		] );
		$lr->add_responsive_control( "eael_ps_hint_margin", [
			'label'      => __( 'Password Hint Margin', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$hint => $lr->apply_dim( 'margin' ),
			],
			'condition' => [
				'ps_hint_type!' => '',
			],
		] );
		$lr->add_group_control( Group_Control_Typography::get_type(), [
			'label'     => __( 'Strength Text Typography', 'essential-addons-elementor' ),
			'name'     => 'eael_ps_text_typo',
			'selector' => $notice_container,
		] );
		$lr->add_group_control( Group_Control_Typography::get_type(), [
			'label'     => __( 'Password Hint Typography', 'essential-addons-elementor' ),
			'name'     => 'eael_ps_hint_typo',
			'selector' => $hint,
			'condition' => [
				'ps_hint_type!' => '',
			],
		] );
		$lr->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_ps_wrap_border",
			'selector' => $notice_container,
		] );
		$lr->add_control( "eael_ps_wrap_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$notice_container => $lr->apply_dim( 'border-radius' ),
			],
			'separator'  => 'after',
		] );
		$lr->add_control( 'ps_text_color_heading', [
			'label'     => __( 'Colors', 'essential-addons-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );
		$pass_type = [
			'short'  => __( 'Very Weak', 'essential-addons-elementor' ),
			'bad'    => __( 'Weak', 'essential-addons-elementor' ),
			'good'   => __( 'Medium', 'essential-addons-elementor' ),
			'strong' => __( 'Strong', 'essential-addons-elementor' ),
		];
		foreach ( $pass_type as $p_type => $label ) {
			$ps_text_n_meter_selectors = [ "{$notice_container}.{$p_type}" => 'color: {{VALUE}};' ];
			switch ( $p_type ) {
				case 'short':
					$ps_text_n_meter_selectors['{{WRAPPER}} .eael-lr-form-wrapper meter[value=\'0\']::-webkit-meter-optimum-value'] = 'background: {{VALUE}};';
					$ps_text_n_meter_selectors['{{WRAPPER}} .eael-lr-form-wrapper meter[value=\'0\']::-moz-meter-bar, ']            = 'background: {{VALUE}};';
					$ps_text_n_meter_selectors['{{WRAPPER}} .eael-lr-form-wrapper meter[value=\'1\']::-webkit-meter-optimum-value'] = 'background: {{VALUE}};';
					$ps_text_n_meter_selectors['{{WRAPPER}} .eael-lr-form-wrapper meter[value=\'1\']::-moz-meter-bar']              = 'background: {{VALUE}};';
					break;
				case 'bad':
					$ps_text_n_meter_selectors['{{WRAPPER}} .eael-lr-form-wrapper meter[value=\'2\']::-webkit-meter-optimum-value'] = 'background: {{VALUE}};';
					$ps_text_n_meter_selectors['{{WRAPPER}} .eael-lr-form-wrapper meter[value=\'2\']::-moz-meter-bar']              = 'background: {{VALUE}};';
					break;
				case 'good':

					$ps_text_n_meter_selectors['{{WRAPPER}} .eael-lr-form-wrapper meter[value=\'3\']::-webkit-meter-optimum-value'] = 'background: {{VALUE}};';
					$ps_text_n_meter_selectors['{{WRAPPER}} .eael-lr-form-wrapper meter[value=\'3\']::-moz-meter-bar']              = 'background: {{VALUE}};';
					break;
				case 'strong':
					$ps_text_n_meter_selectors['{{WRAPPER}} .eael-lr-form-wrapper meter[value=\'4\']::-webkit-meter-optimum-value'] = 'background: {{VALUE}};';
					$ps_text_n_meter_selectors['{{WRAPPER}} .eael-lr-form-wrapper meter[value=\'4\']::-moz-meter-bar']              = 'background: {{VALUE}};';
					break;
			}

			$lr->add_control( "ps_text_{$p_type}_color", [
				/* translators: %s: Strength of the Password eg. Bad, Good etc. */
				'label'     => sprintf( __( '%s Password', 'essential-addons-elementor' ), $label ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => $ps_text_n_meter_selectors,
				'condition' => [
					'show_pass_strength' => 'yes',
				],
			] );
		}
		$lr->add_control( "ps_hint_color", [
			'label'     => __( 'Password Hint', 'essential-addons-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => $hint,
			'condition' => [
				'ps_hint_type!' => '',
			],
		] );
		$lr->add_group_control( Group_Control_Background::get_type(), [
			'name'     => "eael_ps_wrap_bg_color",
			'label'    => __( 'Background Color', 'essential-addons-for-elementor' ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => $notice_container,
		] );
		$lr->add_responsive_control( 'eael_ps_align', [
			'label'     => __( 'Alignment', 'essential-addons-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [
					'title' => __( 'Left', 'essential-addons-for-elementor' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => __( 'Center', 'essential-addons-for-elementor' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => __( 'Right', 'essential-addons-for-elementor' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'default'   => '',
			'selectors' => [
				$notice_container => 'text-align: {{VALUE}};',
			],
		] );
		$lr->end_controls_section();
	}

	/**
	 * It shows password strength
	 *
	 * @param Login_Register $lr
	 */
	public function lr_show_password_strength_meter( Login_Register $lr ) {
		$show_ps_meter      = $lr->get_settings_for_display( 'show_ps_meter' );
		$show_pass_strength = $lr->get_settings_for_display( 'show_pass_strength' );
		$hint_type          = $lr->get_settings_for_display( 'ps_hint_type' );

		if ( 'yes' !== $show_pass_strength && empty( $hint_type ) && 'yes' !== $show_ps_meter ) {
			return;// vail early if SPS, spsm and hint all off
		}

		$data = [
			'show_ps_meter'      => esc_attr( $show_ps_meter ),
			'show_pass_strength' => esc_attr( $show_pass_strength ),
			'ps_text_type'       => esc_attr( $lr->get_settings_for_display( 'ps_text_type' ) ),
		];

		if ( 'yes' === $show_pass_strength ) {
			$pass_types = [
				'short',
				'bad',
				'good',
				'strong',
			];
			foreach ( $pass_types as $pass_type ) {
				$data["ps_text_{$pass_type}"] = trim( $lr->get_settings_for_display( "ps_text_{$pass_type}" ) );
			}
		}

		$hint = '';
		if ( ! empty( $hint_type ) ) {
			$hint = 'custom' === $hint_type ? $lr->get_settings_for_display( 'ps_hint' ) : wp_get_password_hint();
		}
		?>
        <div class='pass-meta-info' data-strength-options="<?php echo esc_attr( json_encode( $data ) ); ?>">
			<?php
			if ( 'yes' === $show_ps_meter ) {
				echo '<meter max="4" class="eael-pass-meter" value="" style="display: none"></meter>';
			}
			if ( 'yes' === $show_pass_strength ) {
				echo '<p class="eael-pass-notice" style="display: none"></p>';
			}
			if ( ! empty( $hint ) ) {
				printf( "<p class='eael-pass-hint'>%s</p>", esc_html( $hint ) );
			}
			?>
        </div>
		<?php

	}

}
