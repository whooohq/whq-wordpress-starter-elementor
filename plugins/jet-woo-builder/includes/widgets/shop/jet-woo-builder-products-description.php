<?php
/**
 * Class: Jet_Woo_Builder_Products_Description
 * Name: Products Description
 * Slug: jet-woo-builder-products-description
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Products_Description extends Jet_Woo_Builder_Base {

	public function get_name() {
		return 'jet-woo-builder-products-description';
	}

	public function get_title() {
		return esc_html__( 'Products Description', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-shop-description';
	}

	public function get_script_depends() {
		return [];
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetwoobuilder-how-to-create-and-set-a-shop-page-template/';
	}

	public function get_categories() {
		return [ 'jet-woo-builder' ];
	}

	public function show_in_panel() {
		return jet_woo_builder()->documents->is_document_type( 'shop' );
	}

	protected function register_controls() {

		$css_scheme = apply_filters(
			'jet-woo-builder/products-result-count/css-scheme',
			[
				'term_description'    => '.elementor-jet-woo-builder-products-description .term-description',
				'archive_description' => '.elementor-jet-woo-builder-products-description .page-description',
			]
		);

		$this->start_controls_section(
			'section_products_description_style',
			[
				'label' => esc_html__( 'Products Description', 'jet-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'products_description_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} ' . $css_scheme['term_description']    => 'color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['archive_description'] => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'products_description_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['term_description'] . ',' . '{{WRAPPER}} ' . $css_scheme['archive_description'],
			]
		);
		$this->add_responsive_control(
			'products_description_align',
			[
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => jet_woo_builder_tools()->get_available_h_align_types( true ),
				'selectors' => [
					'{{WRAPPER}} ' . $css_scheme['term_description']    => 'text-align: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['archive_description'] => 'text-align: {{VALUE}};',
				],
				'classes'   => 'elementor-control-align',
			]
		);

	}

	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();

		woocommerce_taxonomy_archive_description();
		woocommerce_product_archive_description();

		$this->__close_wrap();

	}
}
