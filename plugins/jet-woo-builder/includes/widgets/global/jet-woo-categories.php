<?php
/**
 * Class: Jet_Woo_Categories
 * Name: Categories Grid
 * Slug: jet-woo-categories
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Categories extends Jet_Woo_Builder_Base {

	public function get_name() {
		return 'jet-woo-categories';
	}

	public function get_title() {
		return esc_html__( 'Categories Grid', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-categories-grid';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetwoobuilder-categories-grid-widget-overview/';
	}

	public function get_categories() {
		return array( 'jet-woo-builder' );
	}

	public function __shortcode() {
		return jet_woo_builder_shortcodes()->get_shortcode( $this->get_name() );
	}

	public function get_style_depends() {
		return array( 'elementor-icons-fa-solid' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'General', 'jet-woo-builder' ),
			)
		);

		if ( $this->__shortcode() ) {

			$attributes = $this->__shortcode()->get_atts();

			foreach ( $attributes as $attr => $settings ) {

				if ( empty( $settings['type'] ) ) {
					continue;
				}

				if ( ! empty( $settings['responsive'] ) ) {
					$this->add_responsive_control( $attr, $settings );
				} else {
					$this->add_control( $attr, $settings );
				}

			}

		}

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-woo-builder/jet-woo-categories/css-scheme',
			array(
				'wrap'          => '.jet-woo-categories',
				'column'        => '.jet-woo-categories .jet-woo-categories__item',
				'inner-box'     => '.jet-woo-categories .jet-woo-categories__inner-box',
				'thumb'         => '.jet-woo-categories .jet-woo-category-thumbnail',
				'content'       => '.jet-woo-categories .jet-woo-categories-content',
				'title-wrap'    => '.jet-woo-categories .jet-woo-categories-title__wrap',
				'title'         => '.jet-woo-categories .jet-woo-category-title',
				'count-wrap'    => '.jet-woo-categories .jet-woo-category-count__wrap',
				'count'         => '.jet-woo-categories .jet-woo-category-count',
				'excerpt'       => '.jet-woo-categories .jet-woo-category-excerpt',
				'overlay'       => '.jet-woo-categories .jet-woo-category-img-overlay',
				'overlay-hover' => '.jet-woo-categories .jet-woo-category-img-overlay__hover',
			)
		);

		$this->start_controls_section(
			'section_carousel',
			[
				'label' => __( 'Carousel', 'jet-woo-builder' ),
			]
		);

		jet_woo_builder_common_controls()->register_carousel_controls( $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_column_style',
			[
				'label' => __( 'Column', 'jet-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_padding',
			array(
				'label'       => esc_html__( 'Column Padding', 'jet-woo-builder' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px' ),
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['column']                         => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['wrap'] . ':not(.swiper-wrapper)' => 'margin-right: -{{RIGHT}}{{UNIT}}; margin-left: -{{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label'      => esc_html__( 'Category Item', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_box( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_hover_style',
			array(
				'label'      => esc_html__( 'Category Item (hover)', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_box_hover( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_thumb_style',
			array(
				'label'      => esc_html__( 'Category Thumbnail', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_thumbnail( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label'      => esc_html__( 'Content', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_content( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_title( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_count_style',
			array(
				'label'      => esc_html__( 'Count', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_count( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_excerpt_style',
			array(
				'label'      => esc_html__( 'Excerpt', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_excerpt( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_overlay_style',
			array(
				'label'      => esc_html__( 'Overlay', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_overlay( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_arrows_style', [
				'label'     => __( 'Carousel Navigation', 'jet-woo-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'carousel_enabled' => 'yes',
					'arrows'           => 'yes',
				],
			]
		);

		jet_woo_builder_common_controls()->register_carousel_navigation_style_controls( $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dots_style',
			[
				'label'     => __( 'Carousel Pagination', 'jet-woo-builder' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'carousel_enabled' => 'yes',
					'dots!'            => '',
				],
			]
		);

		jet_woo_builder_common_controls()->register_carousel_pagination_style_controls( $this );

		$this->end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();

		$attributes    = array();
		$settings      = $this->get_settings();
		$shortcode_obj = $this->__shortcode();

		if ( isset( $settings['selected_prev_arrow'] ) || isset( $settings['prev_arrow'] ) ) {
			$settings['prev_arrow'] = htmlspecialchars( $this->__render_icon( 'prev_arrow', '%s', '', false ) );
		}

		if ( isset( $settings['selected_next_arrow'] ) || isset( $settings['next_arrow'] ) ) {
			$settings['next_arrow'] = htmlspecialchars( $this->__render_icon( 'next_arrow', '%s', '', false ) );
		}

		$shortcode_obj->set_settings( $settings );

		foreach ( $shortcode_obj->get_atts() as $attr => $data ) {
			$attr_val            = $settings[ $attr ];
			$attr_val            = ! is_array( $attr_val ) ? $attr_val : implode( ',', $attr_val );
			$attributes[ $attr ] = $attr_val;
		}

		echo jet_woo_builder_tools()->get_carousel_wrapper_atts( $shortcode_obj->do_shortcode( $attributes ), $settings );

		$this->__close_wrap();

	}

	protected function controls_section_box( $css_scheme ) {

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'box_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['inner-box'],
			)
		);

		$this->add_responsive_control(
			'box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'box_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'inner_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['inner-box'],
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

	}

	protected function controls_section_box_hover( $css_scheme ) {

		$this->add_control(
			'box_hover_title',
			array(
				'label'     => esc_html__( 'Title', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'box_hover_title_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover .jet-woo-category-title' . ' a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_hover_title_decoration',
			array(
				'label'     => esc_html__( 'Text Decoration', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => jet_woo_builder_tools()->get_available_text_decoration_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover .jet-woo-category-title' . ' a' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_hover_excerpt',
			array(
				'label'     => esc_html__( 'Excerpt', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'box_hover_excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover .jet-woo-category-excerpt' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_hover_count',
			array(
				'label'     => esc_html__( 'Count', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'box_hover_count_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover .jet-woo-category-count' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_hover_count_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover .jet-woo-category-count' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_hover_content',
			array(
				'label'     => esc_html__( 'Content', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'box_hover_content_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover .jet-woo-categories-content' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_hover_shadow',
			array(
				'label'     => esc_html__( 'Item', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'inner_box_hover_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'inner_box_hover_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover',
			)
		);

	}

	protected function controls_section_thumbnail( $css_scheme ) {

		$this->add_control(
			'thumb_background',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumb'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'thumb_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['thumb'],
			)
		);

		$this->add_responsive_control(
			'thumb_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumb'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'thumb_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumb'],
			)
		);

		$this->add_responsive_control(
			'thumb_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumb'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumb'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	}

	protected function controls_section_content( $css_scheme ) {

		$this->add_control(
			'content_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'content_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['content'],
			)
		);

		$this->add_control(
			'content_hover_border_color',
			array(
				'label'     => esc_html__( 'Hover Border Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover .jet-woo-categories-content' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'content_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
			)
		);

		$this->add_responsive_control(
			'content_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	}

	protected function controls_section_title( $css_scheme ) {

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'] . ', {{WRAPPER}} ' . $css_scheme['title'] . ' a',
			)
		);

		$this->add_control(
			'title_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] . ' a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'title_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => jet_woo_builder_tools()->get_available_h_align_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title']      => 'text-align: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['title-wrap'] => 'text-align: {{VALUE}};',
				),
				'classes'   => 'elementor-control-align',
			)
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	}

	protected function controls_section_count( $css_scheme ) {

		$this->add_responsive_control(
			'count_min_width',
			array(
				'label'      => esc_html__( 'Count Minimal Width (px)', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'min-width: {{SIZE}}{{UNIT}}; text-align: center;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['count'],
			)
		);

		$this->add_control(
			'count_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'count_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['count'],
			)
		);

		$this->add_control(
			'count_hover_border_color',
			array(
				'label'     => esc_html__( 'Hover Border Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover .jet-woo-category-count' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'count_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'inner_count_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['count'],
			)
		);

		$this->add_responsive_control(
			'count_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => jet_woo_builder_tools()->get_available_h_align_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['count-wrap'] => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'presets!' => array( 'preset-3' ),
				),
				'classes'   => 'elementor-control-align',
			)
		);

		$this->add_responsive_control(
			'count_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'count_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	}

	protected function controls_section_excerpt( $css_scheme ) {
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['excerpt'],
			)
		);

		$this->add_control(
			'excerpt_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'excerpt_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => jet_woo_builder_tools()->get_available_h_align_types( true ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'text-align: {{VALUE}};',
				),
				'classes'   => 'elementor-control-align',
			)
		);

		$this->add_responsive_control(
			'excerpt_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'excerpt_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	}

	protected function controls_section_overlay( $css_scheme ) {

		$this->start_controls_tabs( 'tabs_overlay_style' );

		$this->start_controls_tab(
			'tab_overlay_normal',
			[
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'overlay_bg',
				'label'    => esc_html__( 'Background', 'jet-woo-builder' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} ' . $css_scheme['overlay'],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_overlay_hover',
			[
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'overlay_bg_hover',
				'label'    => esc_html__( 'Background', 'jet-woo-builder' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} ' . $css_scheme['column'] . ':hover .jet-woo-category-img-overlay__hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

	}

	protected function content_template() {
	}
}
