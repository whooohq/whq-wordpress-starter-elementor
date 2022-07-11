<?php
/**
 * Class: Jet_Woo_Builder_Products_Pagination
 * Name: Products Pagination
 * Slug: jet-woo-builder-products-pagination
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Products_Pagination extends Jet_Woo_Builder_Base {

	public function get_name() {
		return 'jet-woo-builder-products-pagination';
	}

	public function get_title() {
		return esc_html__( 'Products Pagination', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-shop-pagination';
	}

	public function get_script_depends() {
		return array();
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetwoobuilder-how-to-create-and-set-a-shop-page-template/';
	}

	public function get_categories() {
		return array( 'jet-woo-builder' );
	}

	public function show_in_panel() {
		return jet_woo_builder()->documents->is_document_type( 'shop' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'Items', 'jet-woo-builder' ),
			)
		);
		$this->add_control(
			'info_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Works only with main Query object.', 'jet-woo-builder' ),
				'content_classes' => 'elementor-descriptor elementor-panel-alert elementor-panel-alert-info',
			)
		);
		$this->add_control(
			'prev_next',
			array(
				'label'        => esc_html__( 'Add the previous and next page links.', 'jet-woo-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'prev_text',
			array(
				'label'       => esc_html__( 'The previous page link text', 'jet-woo-builder' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Previous', 'jet-woo-builder' ),
				'condition'   => array(
					'prev_next' => 'yes',
				),
			)
		);
		$this->__add_advanced_icon_control(
			'prev_icon',
			array(
				'label'       => esc_html__( 'The next page link icon', 'jet-woo-builder' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-angle-left',
				'fa5_default' => array(
					'value'   => 'fas fa-angle-left',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'prev_next' => 'yes',
				),
			)
		);
		$this->add_control(
			'next_text',
			array(
				'label'       => esc_html__( 'The next page text', 'jet-woo-builder' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Next', 'jet-woo-builder' ),
				'condition'   => array(
					'prev_next' => 'yes',
				),
			)
		);
		$this->__add_advanced_icon_control(
			'next_icon',
			array(
				'label'       => esc_html__( 'The next page link icon', 'jet-woo-builder' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-angle-right',
				'fa5_default' => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'prev_next' => 'yes',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'general_style',
			array(
				'label'      => esc_html__( 'General', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->add_control(
			'general_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'general_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .jet-woo-builder-shop-pagination',
			)
		);
		$this->add_control(
			'general_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'general_shadow',
				'selector' => '{{WRAPPER}} .jet-woo-builder-shop-pagination',
			)
		);
		$this->add_responsive_control(
			'general_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'general_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'items_style',
			array(
				'label'      => esc_html__( 'Items', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->add_control(
			'items_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'flex-start',
				'options'   => jet_woo_builder_tools()->get_available_flex_h_align_types( true ),
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination' => 'justify-content: {{VALUE}}',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_items_style' );
		$this->start_controls_tab(
			'items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);
		$this->add_control(
			'items_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'items_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers' => 'color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'items_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			)
		);
		$this->add_control(
			'items_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers:hover' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'items_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers:hover' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'items_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'items_active',
			array(
				'label' => esc_html__( 'Current', 'jet-woo-builder' ),
			)
		);
		$this->add_control(
			'items_bg_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination span.page-numbers.current' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'items_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination span.page-numbers.current' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'items_active_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination span.page-numbers.current' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'items_min_width',
			array(
				'label'      => esc_html__( 'Item Min Width', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers' => 'min-width: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'items_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers',
				'exclude'  => array(
					'text_decoration',
				),
			)
		);
		$this->add_responsive_control(
			'items_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'items_margin',
			array(
				'label'       => esc_html__( 'Gap Between Items', 'jet-woo-builder' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'unit' => 'px',
					'size' => 4,
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers' => 'margin-left: calc( {{SIZE}}px / 2 ); margin-right: calc( {{SIZE}}px / 2 );',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'items_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers:not(.dots)',
			)
		);
		$this->add_responsive_control(
			'items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

		$this->section_prev_next_styles();

		$this->start_controls_section(
			'icons_style',
			array(
				'label'      => esc_html__( 'Prev/Next Icons', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->start_controls_tabs( 'tabs_icons_style' );
		$this->start_controls_tab(
			'icons_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);
		$this->add_control(
			'icons_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .jet-woo-builder-shop-pagination__arrow' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'icons_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .jet-woo-builder-shop-pagination__arrow' => 'color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'icons_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			)
		);
		$this->add_control(
			'icons_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers:hover .jet-woo-builder-shop-pagination__arrow' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'icons_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers:hover .jet-woo-builder-shop-pagination__arrow' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'icons_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'items_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers:hover .jet-woo-builder-shop-pagination__arrow' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'items_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers .jet-woo-builder-shop-pagination__arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);
		$this->add_responsive_control(
			'icons_box_size',
			array(
				'label'      => esc_html__( 'Icon Box Size', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 18,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers .jet-woo-builder-shop-pagination__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icons_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .jet-woo-builder-shop-pagination .jet-woo-builder-shop-pagination__arrow',
			)
		);
		$this->add_responsive_control(
			'icons_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .jet-woo-builder-shop-pagination__arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'items_icon_gap',
			array(
				'label'      => esc_html__( 'Gap Between Text and Icon', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers .jet-woo-builder-shop-pagination__arrow.jet-arrow-prev' => ! is_rtl() ? 'margin-right: {{SIZE}}{{UNIT}};' : 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers .jet-woo-builder-shop-pagination__arrow.jet-arrow-next' => ! is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {

		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return false;
		}

		$settings = $this->get_settings();

		$prev_next = isset( $settings['prev_next'] ) ? $settings['prev_next'] : '';
		$prev_next = filter_var( $prev_next, FILTER_VALIDATE_BOOLEAN );
		$prev_text = isset( $settings['prev_text'] ) ? esc_html__( $settings['prev_text'], 'jet-woo-builder' ) : '';
		$next_text = isset( $settings['next_text'] ) ? esc_html__( $settings['next_text'], 'jet-woo-builder' ) : '';
		$prev_icon = $this->__render_icon( 'prev_icon', '%s', '', false );
		$next_icon = $this->__render_icon( 'next_icon', '%s', '', false );
		$total     = wc_get_loop_prop( 'total_pages' );
		$current   = wc_get_loop_prop( 'current_page' );
		$base      = esc_url_raw( add_query_arg( 'product-page', '%#%', false ) );
		$format    = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );

		if ( ! wc_get_loop_prop( 'is_shortcode' ) ) {
			$format = '';
			$base   = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
		}

		if ( $total <= 1 ) {
			return false;
		}

		$this->__context = 'render';

		$this->__open_wrap();

		if ( ! empty( $prev_icon ) ) {
			$prev_text = $this->get_pagination_arrow( 'prev', $prev_icon ) . $prev_text;
		}
		if ( ! empty( $next_icon ) ) {
			$next_text .= $this->get_pagination_arrow( 'next', $next_icon );
		}

		echo '<nav class="jet-woo-builder-shop-pagination">';
		echo paginate_links(
			array(
				'base'      => $base,
				'format'    => $format,
				'prev_next' => $prev_next,
				'prev_text' => $prev_text,
				'next_text' => $next_text,
				'current'   => max( 1, $current ),
				'total'     => $total,
				'type'      => 'plain',
				'end_size'  => 3,
				'mid_size'  => 3,
			) );
		echo '</nav>';

		$this->__close_wrap();

	}

	/**
	 * Return html for arrows in pagination
	 *
	 * @param string $icon
	 * @param string $arrow
	 *
	 * @return string
	 */
	public function get_pagination_arrow( $arrow = 'next', $icon = '' ) {

		$format = apply_filters(
			'jet-woo-builder/shop-pagination/arrows-format',
			'<span class="jet-arrow-%s jet-woo-builder-shop-pagination__arrow jet-woo-builder-icon">%s</span>'
		);

		return sprintf( $format, $arrow, $icon );

	}

	public function section_prev_next_styles() {

		$this->start_controls_section(
			'prev_next_style',
			array(
				'label'      => esc_html__( 'Prev/Next', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->start_controls_tabs( 'tabs_prev_next_style' );
		$this->start_controls_tab(
			'prev_next_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);
		$this->add_control(
			'prev_next_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers.prev' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers.next' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'prev_next_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers.prev' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers.next' => 'color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'prev_next_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			)
		);
		$this->add_control(
			'prev_next_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers.prev:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers.next:hover' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'prev_next_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers.next:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers.prev:hover' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'prev_next_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'prev_next_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers.prev:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .jet-woo-builder-shop-pagination a.page-numbers.next:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'prev_next_min_width',
			array(
				'label'      => esc_html__( 'Item Min Width', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers.prev' => 'min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers.next' => 'min-width: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);
		$this->add_responsive_control(
			'prev_next_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers.prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers.next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'prev_next_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers.prev,' . '{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers.next',
			)
		);
		$this->add_responsive_control(
			'prev_next_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers.prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .jet-woo-builder-shop-pagination .page-numbers.next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

	}
}
