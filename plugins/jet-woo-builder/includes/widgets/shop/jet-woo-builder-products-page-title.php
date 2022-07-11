<?php
/**
 * Class: Jet_Woo_Builder_Products_Page_Title
 * Name: Page Title
 * Slug: jet-woo-builder-products-page-title
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Products_Page_Title extends Jet_Woo_Builder_Base {

	public function get_name() {
		return 'jet-woo-builder-products-page-title';
	}

	public function get_title() {
		return esc_html__( 'Products Page Title', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-title';
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

		$css_scheme = apply_filters(
			'jet-woo-builder/products-page-title/css-scheme',
			array(
				'page_title' => '.woocommerce-products-header__title.page-title',
			)
		);

		$this->start_controls_section(
			'section_page_title_content',
			array(
				'label' => esc_html__( 'Page Title', 'jet-woo-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'page_title_tag',
			array(
				'label'   => esc_html__( 'Tag', 'jet-woo-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => jet_woo_builder_tools()->get_available_title_html_tags(),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_page_title_style',
			array(
				'label' => esc_html__( 'Page Title', 'jet-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'page_title_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['page_title'] => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'page_title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['page_title'],
			)
		);
		$this->add_responsive_control(
			'page_title_align',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => jet_woo_builder_tools()->get_available_h_align_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['page_title'] => 'text-align: {{VALUE}};',
				),
				'classes'   => 'elementor-control-align',
			)
		);

	}

	protected function render() {

		$this->__context = 'render';

		$settings = $this->get_settings();

		$tag = jet_woo_builder_tools()->sanitize_html_tag( $settings['page_title_tag'] );

		$this->__open_wrap();

		echo '<' . $tag . ' class="woocommerce-products-header__title page-title">';
		woocommerce_page_title();
		echo '</' . $tag . '>';

		$this->__close_wrap();

	}
}
