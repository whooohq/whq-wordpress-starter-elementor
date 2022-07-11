<?php
/**
 * Class: Jet_Woo_Products_List
 * Name: Products List
 * Slug: jet-woo-products-list
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

class Jet_Woo_Products_List extends Jet_Woo_Builder_Base {

	public function get_name() {
		return 'jet-woo-products-list';
	}

	public function get_title() {
		return esc_html__( 'Products List', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-products-list';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetwoobuilder-products-list-widget-overview/';
	}

	public function get_categories() {
		return array( 'jet-woo-builder' );
	}

	public function __shortcode() {
		return jet_woo_builder_shortcodes()->get_shortcode( $this->get_name() );
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

		if ( ! class_exists( 'Jet_Engine' ) ) {
			$this->add_control(
				'enable_custom_query',
				[
					'label'   => __( 'For Query Controls Condition', 'jet-woo-builder' ),
					'type'    => Controls_Manager::HIDDEN,
					'default' => '',
				]
			);
		}

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-woo-builder/jet-woo-products-list/css-scheme',
			array(
				'wrap'              => '.jet-woo-products-list',
				'item'              => '.jet-woo-products-list .jet-woo-products-list__item',
				'inner-box'         => '.jet-woo-products-list .jet-woo-products-list__inner-box',
				'thumb'             => '.jet-woo-products-list .jet-woo-products-list__item-img',
				'content'           => '.jet-woo-products-list .jet-woo-products-list__item-content',
				'cats'              => '.jet-woo-products-list .jet-woo-product-categories',
				'title'             => '.jet-woo-products-list .jet-woo-product-title',
				'sku'               => '.jet-woo-products-list .jet-woo-product-sku',
				'price'             => '.jet-woo-products-list .jet-woo-product-price',
				'rating'            => '.jet-woo-products-list .jet-woo-product-rating',
				'currency'          => '.jet-woo-products-list .jet-woo-product-price .woocommerce-Price-currencySymbol',
				'button-wrap'       => '.jet-woo-products-list .jet-woo-product-button',
				'button'            => '.jet-woo-products-list .jet-woo-product-button .button',
				'qty'               => '.jet-woo-products-list .jet-woo-product-button .qty',
				'qty_input'         => '.jet-woo-products-list .jet-woo-product-button .quantity',
				'view_cart'         => '.jet-woo-products-list .jet-woo-product-button .added_to_cart',
				'not-found-message' => '.jet-woo-products__not-found',
				'stock-status'      => '.jet-woo-products-list .jet-woo-product-stock-status',
				'in-stock'          => '.jet-woo-products-list .jet-woo-product-stock-status__in-stock',
				'on-backorder'      => '.jet-woo-products-list .jet-woo-product-stock-status__on-backorder',
				'out-of-stock'      => '.jet-woo-products-list .jet-woo-product-stock-status__out-of-stock',
			)
		);

		$this->start_controls_section(
			'section_item_style',
			array(
				'label'      => esc_html__( 'Product Item', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_item( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_thumb_style',
			array(
				'label'      => esc_html__( 'Product Thumbnail', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_thumb( $css_scheme );

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
			'section_cats_style',
			array(
				'label'      => esc_html__( 'Categories', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_cats( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_price_style',
			array(
				'label'      => esc_html__( 'Price', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_price( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_stock_status_style',
			array(
				'label'      => esc_html__( 'Stock Status', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->controls_section_stock_status( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_rating_styles',
			array(
				'label'      => esc_html__( 'Rating', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_rating( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_sku_styles',
			array(
				'label'      => esc_html__( 'SKU', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_sku( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			array(
				'label'      => esc_html__( 'Button', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_button( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_not_found_message_style',
			array(
				'label'      => esc_html__( 'Not Found Message', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_not_found_message( $css_scheme );

		$this->end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();

		$attributes    = array();
		$tag           = $this->get_name();
		$settings      = $this->get_settings();
		$shortcode_obj = $this->__shortcode();

		$settings['_widget_id'] = $this->get_id();

		$settings = apply_filters( 'jet-woo-builder/jet-woo-products-list/settings', $settings, $this );

		$shortcode_obj->set_settings( $settings );

		foreach ( $shortcode_obj->get_atts() as $attr => $data ) {
			$attr_val            = $settings[ $attr ];
			$attr_val            = ! is_array( $attr_val ) ? $attr_val : implode( ',', $attr_val );
			$attributes[ $attr ] = $attr_val;
		}

		$attributes['_element_id'] = isset( $settings['_element_id'] ) ? $settings['_element_id'] : '';

		echo $shortcode_obj->do_shortcode( $attributes );

		$this->__close_wrap();
	}

	protected function controls_section_item( $css_scheme ) {

		$this->add_control(
			'item_content_vertical_alignment',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => array(
					'flex-start' => esc_html__( 'Top', 'jet-woo-builder' ),
					'center'     => esc_html__( 'Center', 'jet-woo-builder' ),
					'flex-end'   => esc_html__( 'Bottom', 'jet-woo-builder' ),
					'stretch'    => esc_html__( 'Stretch', 'jet-woo-builder' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'align-items: {{VALUE}};',
				),
				'condition' => array(
					'products_layout!' => array( 'top' ),
				),
			)
		);

		$this->add_control(
			'item_content_horizontal_alignment',
			array(
				'label'     => esc_html__( 'Horizontal Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => array(
					'flex-start' => esc_html__( 'Left', 'jet-woo-builder' ),
					'center'     => esc_html__( 'Center', 'jet-woo-builder' ),
					'flex-end'   => esc_html__( 'Right', 'jet-woo-builder' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'align-items: {{VALUE}};',
				),
				'condition' => array(
					'products_layout' => array( 'top' ),
				),
			)
		);

		$this->add_responsive_control(
			'item_space_between',
			array(
				'label'      => esc_html__( 'Space Between Items', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' + .jet-woo-products-list__item' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'item_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item'],
				'separator'   => 'before',
			)
		);

		$this->add_responsive_control(
			'item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'box_style_tabs' );

		$this->start_controls_tab(
			'box_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'item_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'inner_item_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'box_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'item_hover_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'inner_item_hover_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

	}

	protected function controls_section_thumb( $css_scheme ) {

		$this->add_responsive_control(
			'thumb_width',
			array(
				'label'      => esc_html__( 'Image Width', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 40,
						'max' => 400,
					),
					'%'  => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 150,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumb']   => 'max-width: {{SIZE}}{{UNIT}}; flex: 0 1 {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['content'] => 'width:100%; max-width: calc(100% - {{SIZE}}{{UNIT}});',
				),
				'condition'  => array(
					'products_layout!' => array( 'top' ),
				),
			)
		);

		$this->add_responsive_control(
			'thumb_horizontal_width',
			array(
				'label'      => esc_html__( 'Image Width', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 40,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 150,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumb'] => 'max-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'products_layout' => array( 'top' ),
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

		$this->start_controls_tabs( 'tabs_thumb_style' );

		$this->start_controls_tab(
			'tab_thumb_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'thumb_background',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumb'] => 'background-color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'thumb_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumb'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_thumb_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'thumb_background_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover .jet-woo-products-list__item-img' => 'background-color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'thumb_box_shadow_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover .jet-woo-products-list__item-img',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'thumb_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumb'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

	}

	protected function controls_section_content( $css_scheme ) {

		$this->add_control(
			'content_background',
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

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'content_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
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

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'content_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => jet_woo_builder_tools()->get_available_flex_h_align_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'align-items: {{VALUE}};',
				),
				'separator' => 'before',
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

		$this->start_controls_tabs( 'tabs_title_color' );

		$this->start_controls_tab(
			'tab_title_color_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
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

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_color_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'title_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] . ' a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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

		$this->add_responsive_control(
			'title_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-woo-builder' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'order: {{VALUE}}',
				),
			)
		);

	}

	protected function controls_section_cats( $css_scheme ) {

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cats_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['cats'],
				'exclude'  => array( 'text_decoration' ),
			)
		);

		$this->start_controls_tabs( 'tabs_cats_color' );

		$this->start_controls_tab(
			'tab_cats_color_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'cats_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cats'] . ' a' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['cats']        => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_cats_color_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'cats_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cats'] . ' a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'cats_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cats'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cats_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cats'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cats_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-woo-builder' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cats'] => 'order: {{VALUE}}',
				),
			)
		);

	}

	protected function controls_section_price( $css_scheme ) {

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['price'],
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price']              => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['price'] . ' .amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'price_sale_display_type',
			[
				'label'     => __( 'Sale Price Display Type', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => jet_woo_builder_tools()->get_available_display_types(),
				'default'   => 'inline-block',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} ' . $css_scheme['price'] . ' del' => 'display: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['price'] . ' ins' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'price_space_between',
			[
				'label'     => __( 'Space Between Prices', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ' . $css_scheme['price'] . ' del+ins' => ! is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'price_sale_display_type' => 'inline-block',
				],
			]
		);

		$this->add_responsive_control(
			'price_space_between_block',
			[
				'label'     => __( 'Space Between Prices', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ' . $css_scheme['price'] . ' del+ins' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'price_sale_display_type' => 'block',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_price_style' );

		$this->start_controls_tab(
			'tab_price_regular',
			array(
				'label' => esc_html__( 'Regular', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'price_regular_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' del'         => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['price'] . ' del .amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'price_regular_decoration',
			array(
				'label'     => esc_html__( 'Text Decoration', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'line-through',
				'options'   => jet_woo_builder_tools()->get_available_text_decoration_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' del' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'price_regular_size',
			array(
				'label'     => esc_html__( 'Size', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' del' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'price_regular_weight',
			array(
				'label'     => esc_html__( 'Font Weight', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '400',
				'options'   => jet_woo_builder_tools()->get_available_font_weight_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' del' => 'font-weight: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_price_sale',
			array(
				'label' => esc_html__( 'Sale', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'price_sale_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' ins'         => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['price'] . ' ins .amount' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'price_sale_decoration',
			array(
				'label'     => esc_html__( 'Text Decoration', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => jet_woo_builder_tools()->get_available_text_decoration_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' ins' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'price_sale_size',
			array(
				'label'     => esc_html__( 'Size', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' ins' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'price_sale_weight',
			array(
				'label'     => esc_html__( 'Font Weight', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '400',
				'options'   => jet_woo_builder_tools()->get_available_font_weight_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' ins' => 'font-weight: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'price_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['price'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'price_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-woo-builder' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] => 'order: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'currency_sign_style',
			array(
				'label'     => esc_html__( 'Currency Sign', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'currency_sign_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['currency'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'currency_sign_size',
			array(
				'label'     => esc_html__( 'Size', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['currency'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'currency_sign_vertical_align',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => jet_woo_builder_tools()->vertical_align_attr(),
				'default'   => 'baseline',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['currency'] => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_currency_sign_style' );

		$this->start_controls_tab(
			'tab_currency_sign_regular',
			array(
				'label' => esc_html__( 'Regular', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'currency_sign_color_regular',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' del .woocommerce-Price-currencySymbol' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'currency_sign_size_regular',
			array(
				'label'     => esc_html__( 'Size', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' del .woocommerce-Price-currencySymbol' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_currency_sign_sale',
			array(
				'label' => esc_html__( 'Sale', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'currency_sign_color_sale',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' ins .woocommerce-Price-currencySymbol' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'currency_sign_size_sale',
			array(
				'label'     => esc_html__( 'Size', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] . ' ins .woocommerce-Price-currencySymbol' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

	}

	protected function controls_section_stock_status( $css_scheme ) {

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stock_status_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['stock-status'],
			)
		);

		$this->start_controls_tabs( 'tabs_stock_status_colors' );

		$this->start_controls_tab(
			'tab_stock_status_in_stock',
			array(
				'label' => esc_html__( 'In Stock', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'stock_status_in_stock_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['in-stock'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'stock_status_in_stock_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['in-stock'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_stock_on_backorder_stock',
			array(
				'label' => esc_html__( 'On Backorder', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'stock_status_on_backorder_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['on-backorder'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'stock_status_on_backorder_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['on-backorder'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_stock_status_out_of_stock',
			array(
				'label' => esc_html__( 'Out of Stock', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'stock_status_out_of_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['out-of-stock'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'stock_status_out_of_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['out-of-stock'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'stock_status_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['stock-status'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'stock_status_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['stock-status'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'stock_status_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-woo-builder' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['stock-status'] => 'order: {{VALUE}}',
				),
			)
		);

	}

	protected function controls_section_sku( $css_scheme ) {

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sku_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['sku'],
			)
		);

		$this->add_control(
			'sku_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sku'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'sku_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sku'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'sku_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sku'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sku_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['sku'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sku_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-woo-builder' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sku'] => 'order: {{VALUE}}',
				),
			)
		);

	}

	protected function controls_section_rating( $css_scheme ) {

		$this->start_controls_tabs( 'tabs_rating_styles' );

		$this->start_controls_tab(
			'tab_rating_all',
			array(
				'label' => esc_html__( 'All', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'rating_color_all',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating'] . ' .product-rating__stars'        => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['rating'] . ' .product-rating__stars:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_rating_rated',
			array(
				'label' => esc_html__( 'Rated', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'rating_color_rated',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating'] . ' .product-rating__stars > span:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rating_font_size',
			array(
				'label'      => esc_html__( 'Font Size (px)', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 16,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['rating'] . ' .product-rating__stars' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rating_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['rating'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rating_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-woo-builder' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating'] => 'order: {{VALUE}}',
				),
			)
		);

	}

	protected function controls_section_button( $css_scheme ) {

		$this->add_control(
			'button_styles_heading',
			array(
				'label' => esc_html__( 'Button Styles', 'jet-woo-builder' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'button_display',
			array(
				'label'     => esc_html__( 'Button Display', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => jet_woo_builder_tools()->get_available_display_types(),
				'default'   => 'inline-block',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_width',
			array(
				'label'      => esc_html__( 'Button Width', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'%',
					'px',
				),
				'range'      => array(
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
					'px' => array(
						'min' => 50,
						'max' => 1000,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'button_bg',
				'fields_options' => [
					'color' => [
						'scheme' => [
							'type'  => Color::get_type(),
							'value' => Color::COLOR_1,
						],
					],
				],
				'selector'       => '{{WRAPPER}} ' . $css_scheme['button'],
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_text_decor',
			array(
				'label'     => esc_html__( 'Text Decoration', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => jet_woo_builder_tools()->get_available_text_decoration_types(),
				'default'   => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button']         => 'text-decoration: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['button'] . '> *' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'button_hover_bg',
				'fields_options' => [
					'color' => [
						'scheme' => [
							'type'  => Color::get_type(),
							'value' => Color::COLOR_4,
						],
					],
				],
				'selector'       => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_hover_text_decor',
			array(
				'label'     => esc_html__( 'Text Decoration', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => jet_woo_builder_tools()->get_available_text_decoration_types(),
				'default'   => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover'     => 'text-decoration: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover > *' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->add_responsive_control(
			'button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => __( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} ' . $css_scheme['button-wrap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'quantity_input_styles_heading',
			array(
				'label'     => esc_html__( 'Quantity Input Styles', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->controls_section_qty( $css_scheme );

		$this->add_control(
			'wrapper_styles_heading',
			array(
				'label'     => esc_html__( 'Wrapper Styles', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'button_order',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Order', 'jet-woo-builder' ),
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button-wrap'] => 'order: {{VALUE}}',
				),
			)
		);

	}

	protected function controls_section_qty( $css_scheme ) {

		$this->add_control(
			'qty_display',
			array(
				'label'     => esc_html__( 'Quantity Input Display', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => jet_woo_builder_tools()->get_available_display_types(),
				'default'   => 'inline-block',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty_input'] => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'qty_input_width',
			array(
				'label'      => esc_html__( 'Input Width', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'%',
					'px',
				),
				'range'      => array(
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
					'px' => array(
						'min' => 50,
						'max' => 1000,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 70,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['qty_input'] => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'qty_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['qty'],
			)
		);

		$this->start_controls_tabs( 'tabs_qty_style' );

		$this->start_controls_tab(
			'tab_qty_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'qty_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'qty_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_qty_focus',
			array(
				'label' => esc_html__( 'Focus', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'qty_focus_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] . ':focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'qty_background_focus_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] . ':focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'qty_focus_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] . ':focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'qty_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['qty'],
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'qty_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'qty_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['qty'],
			)
		);

		$this->add_responsive_control(
			'qty_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['qty'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'qty_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['qty_input'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	}

	protected function controls_section_not_found_message( $css_scheme ) {

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'not_found_message_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['not-found-message'],
			)
		);

		$this->add_control(
			'not_found_message_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['not-found-message'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'not_found_message_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['not-found-message'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'not_found_message_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => jet_woo_builder_tools()->get_available_h_align_types( true ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['not-found-message'] => 'text-align: {{VALUE}};',
				),
				'separator' => 'before',
				'classes'   => 'elementor-control-align',
			)
		);

	}

	protected function content_template() {
	}
}
