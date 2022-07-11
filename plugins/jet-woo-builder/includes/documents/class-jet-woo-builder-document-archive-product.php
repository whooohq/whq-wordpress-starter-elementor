<?php
/**
 * Class: Jet_Woo_Builder_Archive_Document_Product
 * Name: Archive Template
 * Slug: jet-woo-builder-archive
 */

use Elementor\Controls_Manager;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Jet_Woo_Builder_Archive_Document_Product extends Jet_Woo_Builder_Document_Base {

	public function get_name() {
		return 'jet-woo-builder-archive';
	}

	public static function get_title() {
		return esc_html__( 'Jet Woo Archive Template', 'jet-woo-builder' );
	}

	public function get_css_wrapper_selector() {
		return '.jet-woo-builder-layout-' . $this->get_main_id();
	}

	protected function register_controls() {

		$columns = jet_woo_builder_tools()->get_select_range( 12 );

		parent::register_controls();

		$this->start_controls_section(
			'section_template_settings',
			[
				'label'      => esc_html__( 'Template Settings', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_SETTINGS,
				'show_label' => false,
			]
		);

		$this->add_control(
			'use_custom_template_columns',
			[
				'label'        => esc_html__( 'Use custom columns count', 'jet-woo-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'equal_columns_height',
			[
				'label'        => esc_html__( 'Equal columns height', 'jet-woo-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Fits only top level sections of grid item', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'use_custom_template_columns' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'template_columns_count',
			[
				'label'              => esc_html__( 'Template Columns', 'jet-woo-builder' ),
				'type'               => Controls_Manager::SELECT,
				'desktop_default'    => 4,
				'tablet_default'     => 2,
				'mobile_default'     => 1,
				'options'            => $columns,
				'frontend_available' => true,
				'selectors'          => [
					'.woocommerce {{WRAPPER}}.products.jet-woo-builder-products--columns' => '--columns: {{VALUE}}',
				],
				'condition'          => [
					'use_custom_template_columns' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'template_columns_horizontal_gutter',
			[
				'label'              => esc_html__( 'Template Columns Horizontal Gutter (px)', 'jet-woo-builder' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'            => [
					'size' => 10,
					'unit' => 'px',
				],
				'frontend_available' => true,
				'selectors'          => [
					'.woocommerce {{WRAPPER}}.products.jet-woo-builder-products--columns .product:not(.product-category)' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'.woocommerce {{WRAPPER}}.products.jet-woo-builder-products--columns'                                 => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
				],
				'condition'          => [
					'use_custom_template_columns' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'template_columns_vertical_gutter',
			[
				'label'              => esc_html__( 'Template Columns Vertical Gutter (px)', 'jet-woo-builder' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'            => [
					'size' => 10,
					'unit' => 'px',
				],
				'frontend_available' => true,
				'selectors'          => [
					'.woocommerce {{WRAPPER}}.products.jet-woo-builder-products--columns .product:not(.product-category)' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'          => [
					'use_custom_template_columns' => 'yes',
				],
			]
		);

		$this->end_controls_section();

	}

	public function save( $data = [] ) {
		return $this->save_archive_templates( $data );
	}

	public function get_wp_preview_url() {

		$main_post_id   = $this->get_main_id();
		$sample_product = get_post_meta( $main_post_id, '_sample_product', true );

		if ( ! $sample_product ) {
			$sample_product = $this->query_first_product();
		}

		$product_id = $sample_product;

		return add_query_arg(
			[
				'preview_nonce'    => wp_create_nonce( 'post_preview_' . $main_post_id ),
				'jet_woo_template' => $main_post_id,
			],
			get_permalink( $product_id )
		);

	}

	public function get_preview_as_query_args() {

		jet_woo_builder()->documents->set_current_type( $this->get_name() );

		$args    = [];
		$product = $this->query_first_product();

		if ( ! empty( $product ) ) {
			$args = [
				'post_type' => 'product',
				'p'         => $product,
			];
		}

		return $args;

	}

}