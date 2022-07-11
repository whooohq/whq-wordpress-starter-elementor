<?php
/**
 * Class: Jet_Woo_Builder_Archive_Cats
 * Name: Categories
 * Slug: jet-woo-builder-archive-cats
 */

namespace Elementor;

use Elementor\Group_Control_Border;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Archive_Cats extends Jet_Woo_Builder_Base {

	public function get_name() {
		return 'jet-woo-builder-archive-cats';
	}

	public function get_title() {
		return esc_html__( 'Categories', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-archive-categories';
	}

	public function get_jet_help_url() {
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
			'jet-woo-builder/jet-archive-cats/css-scheme',
			array(
				'cats' => '.jet-woo-builder-archive-product-cats',
			)
		);

		$this->start_controls_section(
			'section_archive_cats_content',
			[
				'label' => esc_html__( 'Content', 'jet-woo-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'categories_count',
			[
				'label'       => esc_html__( 'Categories Count', 'jet-woo-builder' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Set 0 to show full list.', 'jet-woo-builder' ),
				'min'         => 0,
				'default'     => 0,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_archive_cats_style',
			array(
				'label'      => esc_html__( 'Categories', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'archive_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['cats'] . ' a',
			)
		);

		$this->start_controls_tabs( 'tabs_archive_cats_color' );

		$this->start_controls_tab(
			'tab_archive_cats_color_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'archive_cats_color',
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
			'tab_archive_cats_color_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'archive_cats_color_hover',
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
			'archive_cats_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => jet_woo_builder_tools()->get_available_h_align_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cats'] => 'text-align: {{VALUE}};',
				),
				'separator' => 'before',
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

	public static function render_callback( $settings = [] ) {

		echo '<div class="jet-woo-builder-archive-product-cats"><ul>';
		echo jet_woo_builder_template_functions()->get_product_terms_list( 'product_cat', $settings['categories_count'] );
		echo '</ul></div>';

	}

	protected function render() {

		$this->__open_wrap();

		$settings        = $this->get_settings();
		$macros_settings = array(
			'categories_count' => $settings['categories_count'],
		);

		if ( jet_woo_builder_tools()->is_builder_content_save() ) {
			echo jet_woo_builder()->parser->get_macros_string( $this->get_name(), $macros_settings );
		} else {
			echo self::render_callback( $macros_settings );
		}

		$this->__close_wrap();

	}

}
