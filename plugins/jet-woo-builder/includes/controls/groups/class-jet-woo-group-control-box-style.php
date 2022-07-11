<?php

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class Jet_Woo_Group_Control_Box_Style extends Elementor\Group_Control_Base {

	protected static $fields;

	public static function get_type() {
		return 'jet-woo-box-style';
	}

	protected function init_fields() {

		$fields = [];

		$fields['background'] = array(
			'label'       => _x( 'Background Type', 'Background Control', 'jet-woo-builder' ),
			'type'        => Controls_Manager::CHOOSE,
			'options'     => array(
				'color'    => array(
					'title' => _x( 'Classic', 'Background Control', 'jet-woo-builder' ),
					'icon'  => 'fa fa-paint-brush',
				),
				'gradient' => array(
					'title' => _x( 'Gradient', 'Background Control', 'jet-woo-builder' ),
					'icon'  => 'fa fa-barcode',
				),
			),
			'label_block' => false,
			'render_type' => 'ui',
		);

		$fields['color'] = array(
			'label'     => _x( 'Color', 'Background Control', 'jet-woo-builder' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'title'     => _x( 'Background Color', 'Background Control', 'jet-woo-builder' ),
			'selectors' => array(
				'{{SELECTOR}}' => 'background-color: {{VALUE}};',
			),
			'condition' => array(
				'background' => array( 'color', 'gradient' ),
			),
		);

		$fields['color_stop'] = array(
			'label'       => _x( 'Location', 'Background Control', 'jet-woo-builder' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => array( '%' ),
			'default'     => array(
				'unit' => '%',
				'size' => 0,
			),
			'render_type' => 'ui',
			'condition'   => array(
				'background' => array( 'gradient' ),
			),
			'of_type'     => 'gradient',
		);

		$fields['color_b'] = array(
			'label'       => _x( 'Second Color', 'Background Control', 'jet-woo-builder' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#f2295b',
			'render_type' => 'ui',
			'condition'   => array(
				'background' => array( 'gradient' ),
			),
			'of_type'     => 'gradient',
		);

		$fields['color_b_stop'] = array(
			'label'       => _x( 'Location', 'Background Control', 'jet-woo-builder' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => array( '%' ),
			'default'     => array(
				'unit' => '%',
				'size' => 100,
			),
			'render_type' => 'ui',
			'condition'   => array(
				'background' => array( 'gradient' ),
			),
			'of_type'     => 'gradient',
		);

		$fields['gradient_type'] = array(
			'label'       => _x( 'Type', 'Background Control', 'jet-woo-builder' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => array(
				'linear' => _x( 'Linear', 'Background Control', 'jet-woo-builder' ),
				'radial' => _x( 'Radial', 'Background Control', 'jet-woo-builder' ),
			),
			'default'     => 'linear',
			'render_type' => 'ui',
			'condition'   => array(
				'background' => array( 'gradient' ),
			),
			'of_type'     => 'gradient',
		);

		$fields['gradient_angle'] = array(
			'label'      => _x( 'Angle', 'Background Control', 'jet-woo-builder' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array( 'deg' ),
			'default'    => array(
				'unit' => 'deg',
				'size' => 180,
			),
			'range'      => array(
				'deg' => array(
					'step' => 10,
				),
			),
			'selectors'  => array(
				'{{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			),
			'condition'  => array(
				'background'    => array( 'gradient' ),
				'gradient_type' => 'linear',
			),
			'of_type'    => 'gradient',
		);

		$fields['gradient_position'] = array(
			'label'     => _x( 'Position', 'Background Control', 'jet-woo-builder' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'center center' => _x( 'Center Center', 'Background Control', 'jet-woo-builder' ),
				'center left'   => _x( 'Center Left', 'Background Control', 'jet-woo-builder' ),
				'center right'  => _x( 'Center Right', 'Background Control', 'jet-woo-builder' ),
				'top center'    => _x( 'Top Center', 'Background Control', 'jet-woo-builder' ),
				'top left'      => _x( 'Top Left', 'Background Control', 'jet-woo-builder' ),
				'top right'     => _x( 'Top Right', 'Background Control', 'jet-woo-builder' ),
				'bottom center' => _x( 'Bottom Center', 'Background Control', 'jet-woo-builder' ),
				'bottom left'   => _x( 'Bottom Left', 'Background Control', 'jet-woo-builder' ),
				'bottom right'  => _x( 'Bottom Right', 'Background Control', 'jet-woo-builder' ),
			),
			'default'   => 'center center',
			'selectors' => array(
				'{{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			),
			'condition' => array(
				'background'    => array( 'gradient' ),
				'gradient_type' => 'radial',
			),
			'of_type'   => 'gradient',
		);

		$fields['box_font_color'] = [
			'label'     => __( 'Font Color', 'jet-woo-builder' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{SELECTOR}}'     => 'color: {{VALUE}}',
				'{{SELECTOR}} svg' => 'fill: {{VALUE}}',
			],
		];

		$fields['box_font_size'] = array(
			'label'      => esc_html__( 'Font Size', 'jet-woo-builder' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => array(
				'px', 'em', 'rem',
			),
			'responsive' => true,
			'range'      => array(
				'px' => array(
					'min' => 5,
					'max' => 500,
				),
			),
			'selectors'  => array(
				'{{SELECTOR}}'        => 'font-size: {{SIZE}}{{UNIT}}',
				'{{SELECTOR}}:before' => 'font-size: {{SIZE}}{{UNIT}}',
			),
		);

		$fields['separate_box_sizes'] = [
			'label' => __( 'Separate Box Sizes', 'jet-woo-builder' ),
			'type'  => Controls_Manager::SWITCHER,
		];

		$fields['box_size'] = [
			'label'      => __( 'Box Size', 'jet-woo-builder' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', '%' ],
			'responsive' => true,
			'selectors'  => [
				'{{SELECTOR}}' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'separate_box_sizes' => '',
			],
		];

		$fields['box_width'] = [
			'label'      => __( 'Box Width', 'jet-woo-builder' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', '%' ],
			'responsive' => true,
			'selectors'  => [
				'{{SELECTOR}}' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'separate_box_sizes!' => '',
			],
		];

		$fields['box_height'] = [
			'label'      => __( 'Box Height', 'jet-woo-builder' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', '%' ],
			'responsive' => true,
			'selectors'  => [
				'{{SELECTOR}}' => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'separate_box_sizes!' => '',
			],
		];

		$fields['box_border'] = array(
			'label'     => _x( 'Border Type', 'Border Control', 'jet-woo-builder' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				''       => esc_html__( 'None', 'jet-woo-builder' ),
				'solid'  => _x( 'Solid', 'Border Control', 'jet-woo-builder' ),
				'double' => _x( 'Double', 'Border Control', 'jet-woo-builder' ),
				'dotted' => _x( 'Dotted', 'Border Control', 'jet-woo-builder' ),
				'dashed' => _x( 'Dashed', 'Border Control', 'jet-woo-builder' ),
			),
			'selectors' => array(
				'{{SELECTOR}}' => 'border-style: {{VALUE}};',
			),
		);

		$fields['box_border_width'] = array(
			'label'     => _x( 'Width', 'Border Control', 'jet-woo-builder' ),
			'type'      => Controls_Manager::DIMENSIONS,
			'selectors' => array(
				'{{SELECTOR}}' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
			'condition' => array(
				'box_border!' => '',
			),
		);

		$fields['box_border_color'] = array(
			'label'     => _x( 'Color', 'Border Control', 'jet-woo-builder' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => array(
				'{{SELECTOR}}' => 'border-color: {{VALUE}};',
			),
			'condition' => array(
				'box_border!' => '',
			),
		);

		$fields['box_border_radius'] = array(
			'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', '%' ),
			'selectors'  => array(
				'{{SELECTOR}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			),
		);

		$fields['allow_box_shadow'] = [
			'label' => _x( 'Box Shadow', 'Box Shadow Control', 'jet-woo-builder' ),
			'type'  => Controls_Manager::SWITCHER,
		];

		$fields['box_shadow'] = array(
			'label'     => _x( 'Box Shadow', 'Box Shadow Control', 'jet-woo-builder' ),
			'type'      => Controls_Manager::BOX_SHADOW,
			'condition' => array(
				'allow_box_shadow!' => '',
			),
			'selectors' => array(
				'{{SELECTOR}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
			),
		);

		$fields['box_shadow_position'] = array(
			'label'       => _x( 'Position', 'Box Shadow Control', 'jet-woo-builder' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => array(
				' '     => _x( 'Outline', 'Box Shadow Control', 'jet-woo-builder' ),
				'inset' => _x( 'Inset', 'Box Shadow Control', 'jet-woo-builder' ),
			),
			'condition'   => array(
				'allow_box_shadow!' => '',
			),
			'default'     => ' ',
			'render_type' => 'ui',
		);

		return apply_filters( 'jet-woo-builder/controls/groups/group-control-box-style', $fields );

	}

	protected function prepare_fields( $fields ) {

		array_walk( $fields, function ( &$field, $field_name ) {

			if ( in_array( $field_name, array( 'popover_toggle' ) ) ) {
				return;
			}

			$condition = array(
				'popover_toggle!' => '',
			);

			if ( isset( $field['condition'] ) ) {
				$field['condition'] = array_merge( $field['condition'], $condition );
			} else {
				$field['condition'] = $condition;
			}

		} );

		return parent::prepare_fields( $fields );

	}

}
