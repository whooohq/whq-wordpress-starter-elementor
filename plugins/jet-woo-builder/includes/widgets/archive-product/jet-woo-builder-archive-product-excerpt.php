<?php
/**
 * Class: Jet_Woo_Builder_Archive_Product_Excerpt
 * Name: Excerpt
 * Slug: jet-woo-builder-archive-product-excerpt
 */

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Archive_Product_Excerpt extends Widget_Base {

	public function get_name() {
		return 'jet-woo-builder-archive-product-excerpt';
	}

	public function get_title() {
		return esc_html__( 'Excerpt', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-excerpt';
	}

	public function get_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/woocommerce-jetwoobuilder-settings-how-to-create-and-set-a-custom-categories-archive-template/?utm_source=need-help&utm_medium=jet-woo-categories&utm_campaign=jetwoobuilder';
	}

	public function get_categories() {
		return array( 'jet-woo-builder' );
	}

	public function show_in_panel() {
		return jet_woo_builder()->documents->is_document_type( 'archive' );
	}

	protected function register_controls() {

		$css_scheme = apply_filters(
			'jet-woo-builder/jet-archive-product-excerpt/css-scheme',
			array(
				'excerpt' => '.jet-woo-builder-archive-product-excerpt',
			)
		);

		$this->start_controls_section(
			'section_archive_excerpt_content',
			array(
				'label'      => esc_html__( 'Content', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$this->add_control(
			'archive_excerpt_length',
			array(
				'label'       => esc_html__( 'Excerpt Words Count', 'jet-woo-builder' ),
				'description' => esc_html__( 'Set -1 to show full excerpt and 0 to hide it.', 'jet-woo-builder' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => -1,
				'default'     => 10,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_archive_excerpt_style',
			array(
				'label'      => esc_html__( 'Excerpt', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'archive_excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'archive_excerpt_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['excerpt'],
			)
		);

		$this->add_responsive_control(
			'archive_excerpt_align',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => jet_woo_builder_tools()->get_available_h_align_types( true ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'text-align: {{VALUE}};',
				),
				'classes'   => 'elementor-control-align',
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Returns CSS selector for nested element
	 *
	 * @param null $el
	 *
	 * @return string
	 */
	public function css_selector( $el = null ) {
		return sprintf( '{{WRAPPER}} .%1$s %2$s', $this->get_name(), $el );
	}

	public static function render_callback( $settings = array() ) {

		$excerpt = jet_woo_builder_tools()->trim_text(
			jet_woo_builder_template_functions()->get_product_excerpt(),
			$settings['archive_excerpt_length'],
			'word',
			'...'
		);

		echo '<div class="jet-woo-builder-archive-product-excerpt">';
		echo $excerpt;
		echo '</div>';

	}

	protected function render() {

		$settings = $this->get_settings();

		$macros_settings = array(
			'archive_excerpt_length' => $settings['archive_excerpt_length'],
		);

		if ( jet_woo_builder_tools()->is_builder_content_save() ) {
			echo jet_woo_builder()->parser->get_macros_string( $this->get_name(), $macros_settings );
		} else {
			echo self::render_callback( $macros_settings );
		}

	}

}
