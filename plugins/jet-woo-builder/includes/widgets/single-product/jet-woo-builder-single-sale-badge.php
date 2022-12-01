<?php
/**
 * Class: Jet_Woo_Builder_Sale_Badge
 * Name: Single Sale Badge
 * Slug: jet-single-sale-badge
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Single_Sale_Badge extends Jet_Woo_Builder_Base {

	public function get_name() {
		return 'jet-single-sale-badge';
	}

	public function get_title() {
		return __( 'Single Sale Badge', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-sale-badge';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetwoobuilder-how-to-create-and-set-a-single-product-page-template/';
	}

	public function get_categories() {
		return array( 'jet-woo-builder' );
	}

	public function show_in_panel() {
		return jet_woo_builder()->documents->is_document_type( 'single' );
	}

	protected function register_controls() {

		$css_scheme = apply_filters(
			'jet-woo-builder/jet-single-sale-badge/css-scheme',
			array(
				'badge' => '.jet-woo-builder .onsale',
			)
		);

		$this->start_controls_section(
			'section_badge_content',
			[
				'label' => __( 'Product Sale Badge', 'jet-woo-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'single_badge_text',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Label', 'jet-woo-builder' ),
				'default'     => __( 'Sale!', 'jet-woo-builder' ),
				'description' => __( 'Use %percentage_sale% and %numeric_sale% macros to display a withdrawal of discounts as a percentage or numeric of the initial price.', 'jet-woo-builder' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_single_badge_style',
			[
				'label' => __( 'Product Sale Badge', 'jet-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'single_badge_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['badge'],
			)
		);

		$this->add_control(
			'single_badge_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'single_badge_background',
			array(
				'label'     => esc_html__( 'Background', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'single_badge_border',
				'label'       => __( 'Border', 'jet-woo-builder' ),
				'selector'    => '{{WRAPPER}} ' . $css_scheme['badge'],
			]
		);

		$this->add_responsive_control(
			'single_badge_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'single_badge_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['badge'],
			)
		);

		$this->add_responsive_control(
			'single_badge_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'single_badge_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => jet_woo_builder_tools()->get_available_h_align_types(),
				'selectors' => array(
					'{{WRAPPER}} ' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	protected function render() {
		if ( $this->__set_editor_product() ) {
			$this->__open_wrap();

			include $this->get_template( 'single-product/sale-badge.php' );

			$this->__close_wrap();

			if ( jet_woo_builder()->elementor_views->in_elementor() ) {
				$this->__reset_editor_product();
			}
		}
	}

}
