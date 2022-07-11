<?php
/**
 * Class: Jet_Woo_Builder_Archive_Product_Rating
 * Name: Rating
 * Slug: jet-woo-builder-archive-product-rating
 */

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Archive_Product_Rating extends Widget_Base {

	public function get_name() {
		return 'jet-woo-builder-archive-product-rating';
	}

	public function get_title() {
		return esc_html__( 'Rating', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-rating';
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
			'jet-woo-builder/jet-single-rating/css-scheme',
			[
				'rating' => '.jet-woo-product-rating',
				'stars'  => '.product-star-rating',
			]
		);

		$this->start_controls_section(
			'section_archive_rating_styles',
			array(
				'label'      => esc_html__( 'Rating', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'show_empty_rating',
			[
				'label' => __( 'Show Empty Rating', 'jet-woo-builder' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'archive_rating_icon',
			array(
				'label'   => esc_html__( 'Rating Icon', 'jet-woo-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'jetwoo-front-icon-rating-1',
				'options' => jet_woo_builder_tools()->get_available_rating_icons_list(),
			)
		);

		$this->add_responsive_control(
			'archive_stars_font_size',
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
					'{{WRAPPER}} ' . $css_scheme['stars'] . ' .product-rating__icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_archive_stars_styles' );

		$this->start_controls_tab(
			'tab_archive_stars_all',
			array(
				'label' => esc_html__( 'All', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'stars_archive_color_all',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#a1a2a4',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['stars'] . ' .product-rating__icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_archive_stars_rated',
			array(
				'label' => esc_html__( 'Rated', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'archive_stars_color_rated',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fdbc32',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['stars'] . ' .product-rating__icon.active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'archive_stars_space_between',
			array(
				'label'      => esc_html__( 'Space Between Stars (px)', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['stars'] . ' .product-rating__icon + .product-rating__icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'archive_stars_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => jet_woo_builder_tools()->get_available_h_align_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['rating'] => 'text-align: {{VALUE}};',
				),
				'classes'   => 'elementor-control-align',
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Returns CSS selector for nested element.
	 *
	 * @param null $el
	 *
	 * @return string
	 */
	public function css_selector( $el = null ) {
		return sprintf( '{{WRAPPER}} .%1$s %2$s', $this->get_name(), $el );
	}

	public static function render_callback( $settings = [] ) {

		$icon         = isset( $settings['archive_rating_icon'] ) ? $settings['archive_rating_icon'] : 'jetwoo-front-icon-rating-1';
		$empty_rating = isset( $settings['show_empty_rating'] ) ? filter_var( $settings['show_empty_rating'], FILTER_VALIDATE_BOOLEAN ) : false;
		$rating       = jet_woo_builder_template_functions()->get_product_custom_rating( $icon, $empty_rating );

		if ( ! $rating ) {
			return;
		}

		printf( '<div class="jet-woo-builder-archive-product-rating"><div class="jet-woo-product-rating">%s</div></div>', $rating );

	}

	protected function render() {

		$settings = $this->get_settings();

		$macros_settings = [
			'archive_rating_icon' => $settings['archive_rating_icon'],
			'show_empty_rating'   => $settings['show_empty_rating'],
		];

		if ( jet_woo_builder_tools()->is_builder_content_save() ) {
			echo jet_woo_builder()->parser->get_macros_string( $this->get_name(), $macros_settings );
		} else {
			echo self::render_callback( $macros_settings );
		}

	}

}
