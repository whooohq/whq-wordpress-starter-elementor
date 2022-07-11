<?php
/**
 * Class: Jet_Woo_Builder_Products_Ordering
 * Name: Products Ordering
 * Slug: jet-woo-builder-products-ordering
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Products_Ordering extends Jet_Woo_Builder_Base {

	public function get_name() {
		return 'jet-woo-builder-products-ordering';
	}

	public function get_title() {
		return esc_html__( 'Products Ordering', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-shop-ordering';
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

		$css_scheme     = apply_filters(
			'jet-woo-builder/products-ordering/css-scheme',
			array(
				'wrapper'          => '.elementor-jet-woo-builder-products-ordering',
				'ordering_wrapper' => '.elementor-jet-woo-builder-products-ordering .woocommerce-ordering',
				'select'           => '.elementor-jet-woo-builder-products-ordering .woocommerce-ordering select',
				'ordering_arrow'   => '.elementor-jet-woo-builder-products-ordering .woocommerce-ordering:before',
			)
		);
		$ordering_arrow = array( '' => esc_html__( 'None', 'jet-woo-builder' ) ) + $this->get_available_down_arrows_list();

		$this->start_controls_section(
			'section_ordering_select_style',
			array(
				'label' => esc_html__( 'Ordering Select', 'jet-woo-builder' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ordering_select_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['select'],
			)
		);

		$this->add_responsive_control(
			'ordering_select_input_width',
			array(
				'label'      => esc_html__( 'Input Width', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'%',
					'px',
				),
				'range'      => array(
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
					'px' => array(
						'min' => 50,
						'max' => 1000,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 150,
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['ordering_wrapper'] => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_ordering_select_style' );

		$this->start_controls_tab(
			'tab_ordering_select_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'ordering_select_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ordering_select_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_ordering_select_focus',
			array(
				'label' => esc_html__( 'Focus', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'ordering_select_focus_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] . ':focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ordering_select_focus_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] . ':focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ordering_select_focus_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['select'] . ':focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'ordering_select_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['select'],
				'separator'   => 'before',

			)
		);

		$this->add_control(
			'ordering_select_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'ordering_select_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['select'],
			)
		);

		$this->add_responsive_control(
			'ordering_select_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'ordering_select_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['select'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'ordering_select_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options'   => jet_woo_builder_tools()->get_available_h_align_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['wrapper'] => 'text-align: {{VALUE}};',
				),
				'classes'   => 'elementor-control-align',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ordering_arrow_style',
			array(
				'label'      => esc_html__( 'Ordering Arrow', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'enable_ordering_arrow',
			array(
				'label'        => esc_html__( 'Show arrow in select', 'jet-woo-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'ordering_arrow_icon',
			array(
				'type'         => Controls_Manager::SELECT,
				'label'        => esc_html__( 'Ordering Arrow', 'jet-woo-builder' ),
				'default'      => 'angle',
				'options'      => $ordering_arrow,
				'condition'    => array(
					'enable_ordering_arrow' => 'yes',
				),
				'prefix_class' => 'ordering-select-icon-',
			)
		);
		$this->add_control(
			'ordering_arrow_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['ordering_arrow'] => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_responsive_control(
			'ordering_arrow_size',
			array(
				'label'      => esc_html__( 'Arrow Size', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['ordering_arrow'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'ordering_arrow_top_gap',
			array(
				'label'      => esc_html__( 'Top Offset', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 9,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['ordering_arrow'] => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'ordering_arrow_right_gap',
			array(
				'label'      => esc_html__( 'Right Offset', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['ordering_arrow'] => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

	}

	/**
	 * Return available arrows list
	 *
	 * @return array
	 */
	public function get_available_down_arrows_list() {

		return apply_filters(
			'jet-woo-builder/product-ordering/select-arrow/icons',
			array(
				'angle'          => __( 'Angle', 'jet-woo-builder' ),
				'chevron'        => __( 'Chevron', 'jet-woo-builder' ),
				'angle-double'   => __( 'Angle Double', 'jet-woo-builder' ),
				'arrow'          => __( 'Arrow', 'jet-woo-builder' ),
				'caret'          => __( 'Caret', 'jet-woo-builder' ),
				'arrow-circle'   => __( 'Arrow Circle', 'jet-woo-builder' ),
				'chevron-circle' => __( 'Chevron Circle', 'jet-woo-builder' ),
				'caret-square'   => __( 'Caret Square', 'jet-woo-builder' ),
			)
		);

	}

	protected function render() {

		$this->__context = 'render';

		$this->__open_wrap();

		woocommerce_catalog_ordering();

		$this->__close_wrap();

	}
}
