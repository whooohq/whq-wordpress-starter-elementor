<?php
/**
 * Class: Jet_Woo_Builder_Archive_Sale_Badge
 * Name: Sale Badge
 * Slug: jet-woo-builder-archive-sale-badge
 */

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Archive_Sale_Badge extends Widget_Base {

	public function get_name() {
		return 'jet-woo-builder-archive-sale-badge';
	}

	public function get_title() {
		return esc_html__( 'Sale Badge', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-sale-badge';
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
			'jet-woo-builder/jet-archive-sale-badge/css-scheme',
			array(
				'wrapper' => '.jet-woo-builder-archive-product-sale-badge',
				'badge'   => '.jet-woo-product-badge',
			)
		);

		$this->start_controls_section(
			'section_badge_content',
			array(
				'label'      => esc_html__( 'Content', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$this->add_control(
			'archive_badge_text',
			array(
				'type'        => 'text',
				'label'       => esc_html__( 'Sale Badge Text', 'jet-woo-builder' ),
				'default'     => 'Sale!',
				'description' => esc_html__( 'Use %percentage_sale% and %numeric_sale% macros to display a withdrawal of discounts as a percentage or numeric of the initial price.', 'jet-woo-builder' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_archive_badge_style',
			array(
				'label'      => esc_html__( 'General', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'archive_badge_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'archive_badge_background',
			array(
				'label'     => esc_html__( 'Background', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'archive_badge_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['badge'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'archive_badge_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['badge'],
			)
		);

		$this->add_responsive_control(
			'archive_badge_border_radius',
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
				'name'     => 'archive_badge_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['badge'],
			)
		);

		$this->add_responsive_control(
			'archive_badge_content_padding',
			array(
				'label'      => __( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'archive_badge_content_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['badge'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'archive_badge_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => jet_woo_builder_tools()->get_available_h_align_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['wrapper'] => 'text-align: {{VALUE}};',
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

		$badge_text    = jet_woo_builder()->macros->do_macros( $settings['archive_badge_text'] );
		$badge_content = jet_woo_builder_template_functions()->get_product_sale_flash( esc_html__( $badge_text, 'jet-woo-builder' ), $settings );

		if ( null !== $badge_content ) {
			echo '<div class="jet-woo-builder-archive-product-sale-badge">';
			echo $badge_content;
			echo '</div>';
		}

	}


	protected function render() {

		$settings = $this->get_settings();

		$macros_settings = apply_filters( 'jet-woo-builder/jet-woo-builder-archive-sale-badge/macros-settings', [
			'archive_badge_text' => wp_kses_post( $settings['archive_badge_text'] ),
		], $settings );

		if ( jet_woo_builder_tools()->is_builder_content_save() ) {
			echo jet_woo_builder()->parser->get_macros_string( $this->get_name(), $macros_settings );
		} else {
			echo self::render_callback( $macros_settings );
		}

	}

}
