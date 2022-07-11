<?php
/**
 * Class: Jet_Woo_Builder_Archive_Category_Description
 * Name: Description
 * Slug: jet-woo-builder-archive-category-description
 */

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Archive_Category_Description extends Widget_Base {

	public function get_name() {
		return 'jet-woo-builder-archive-category-description';
	}

	public function get_title() {
		return esc_html__( 'Description', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-category-description';
	}

	public function get_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/woocommerce-jetwoobuilder-settings-how-to-create-and-set-a-custom-categories-archive-template/?utm_source=need-help&utm_medium=jet-woo-categories&utm_campaign=jetwoobuilder';
	}

	public function get_categories() {
		return array( 'jet-woo-builder' );
	}

	public function show_in_panel() {
		return jet_woo_builder()->documents->is_document_type( 'category' );
	}

	protected function register_controls() {

		$css_scheme = apply_filters(
			'jet-woo-builder/jet-archive-category-description/css-scheme',
			array(
				'description' => '.jet-woo-builder-archive-category-description',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'Content', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'archive_category_description_desc_length',
			array(
				'label'       => esc_html__( 'Description Words Count', 'jet-woo-builder' ),
				'description' => esc_html__( 'Set -1 to show full description and 0 to hide it.', 'jet-woo-builder' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => -1,
				'default'     => 10,
			)
		);

		$this->add_control(
			'archive_category_description_after_text',
			array(
				'label'   => esc_html__( 'Trimmed After Text', 'jet-woo-builder' ),
				'type'    => Controls_Manager::TEXT,
				'min'     => -1,
				'default' => '...',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_archive_category_description_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'archive_category_description_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['description'],
			)
		);


		$this->add_control(
			'archive_category_description_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['description'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'archive_category_description_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['description'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'archive_category_description_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['description'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'archive_category_description_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['description'],
			)
		);

		$this->add_control(
			'archive_category_description_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['description'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			)
		);

		$this->add_responsive_control(
			'archive_category_description_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['description'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'archive_category_description_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['description'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'archive_category_description_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options'   => jet_woo_builder_tools()->get_available_h_align_types( true ),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['description'] => 'text-align: {{VALUE}};',
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

	public static function render_callback( $settings = [], $args = [] ) {

		$category    = ! empty( $args ) ? $args['category'] : get_queried_object();
		$description = jet_woo_builder_tools()->trim_text( $category->description, $settings['desc_length'], 'word', $settings['desc_after_text'] );

		if ( '' === $description ) {
			return null;
		}

		echo '<div class="jet-woo-builder-archive-category-description">';
		echo $description;
		echo '</div>';

	}

	protected function render() {

		$settings = $this->get_settings();

		$macros_settings = array(
			'desc_length'     => $settings['archive_category_description_desc_length'],
			'desc_after_text' => isset( $settings['archive_category_description_after_text'] ) ? wp_kses_post( $settings['archive_category_description_after_text'] ) : '',
		);

		if ( jet_woo_builder_tools()->is_builder_content_save() ) {
			echo jet_woo_builder()->parser->get_macros_string( $this->get_name(), $macros_settings );
		} else {
			echo self::render_callback( $macros_settings, jet_woo_builder_integration_woocommerce()->get_current_args() );
		}

	}

}
