<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Blocks_Woo_Cart extends Jet_Blocks_Base {

	public function get_name() {
		return 'jet-blocks-cart';
	}

	public function get_title() {
		return esc_html__( 'WooCommerce Shopping Cart', 'jet-blocks' );
	}

	public function get_icon() {
		return 'jet-blocks-icon-woo-cart';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-add-a-woocommerce-shopping-cart-to-the-header/';
	}

	public function get_categories() {
		return array( 'jet-blocks' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'cart_label',
			array(
				'label'   => esc_html__( 'Label', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Cart', 'jet-blocks' ),
			)
		);

		$this->__add_advanced_icon_control(
			'cart_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'file'        => '',
				'skin'        => 'inline',
				'default'     => 'fa fa-shopping-cart',
				'fa5_default' => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => esc_html__( 'Show Products Count', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'count_format',
			array(
				'label'     => esc_html__( 'Products Count Format', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '%s',
				'condition' => array(
					'show_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_total',
			array(
				'label'        => esc_html__( 'Show Cart Subtotal', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'total_format',
			array(
				'label'     => esc_html__( 'Cart Subtotal Format', 'jet-blocks' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '%s',
				'condition' => array(
					'show_total' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_cart_list',
			array(
				'label'        => esc_html__( 'Show Cart Dropdown', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-blocks' ),
				'label_off'    => esc_html__( 'No', 'jet-blocks' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'trigger_type',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Show Trigger Type', 'jet-blocks' ),
				'default'    => 'hover',
				'options'    => array(
					'hover' => esc_html__( 'Hover', 'jet-blocks' ),
					'click' => esc_html__( 'Click', 'jet-blocks' ),
				),
				'condition' => array(
					'show_cart_list' => 'yes',
				),
			)
		);

		$this->add_control(
			'layout_type',
			array(
				'type'       => 'select',
				'label'      => esc_html__( 'Layout Type', 'jet-blocks' ),
				'default'    => 'dropdown',
				'options'    => array(
					'dropdown' => esc_html__( 'Dropdown', 'jet-blocks' ),
					'slide-out' => esc_html__( 'Slide Out', 'jet-blocks' ),
				),
				'condition' => array(
					'show_cart_list' => 'yes',
				),
			)
		);

		$this->add_control(
			'cart_list_label',
			array(
				'label'   => esc_html__( 'Cart Dropdown Label', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'My Cart', 'jet-blocks' ),
				'condition' => array(
					'show_cart_list' => 'yes',
				),
			)
		);

		$this->__add_advanced_icon_control(
			'cart_list_close_icon',
			array(
				'label'       => esc_html__( 'Close Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'default'     => 'fa fa-close',
				'fa5_default' => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_cart_list' => 'yes',
					'layout_type'    => 'slide-out',
				),
			)
		);

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-blocks/woo-cart/css-scheme',
			array(
				'cart_wrapper'    => '.elementor-jet-blocks-cart',
				'cart_link'       => '.jet-blocks-cart__heading-link',
				'cart_icon'       => '.jet-blocks-cart__icon',
				'cart_label'      => '.jet-blocks-cart__label',
				'cart_count'      => '.jet-blocks-cart__count',
				'cart_totals'     => '.jet-blocks-cart__total',
				'cart_list'       => '.jet-blocks-cart__list',
				'cart_list_title' => '.jet-blocks-cart__list-title',
				'cart_list_close' => '.jet-blocks-cart__close-button',

				'cart_empty_message'    => '.widget_shopping_cart .woocommerce-mini-cart__empty-message',
				'cart_product_list'     => '.widget_shopping_cart .woocommerce-mini-cart',
				'cart_product_item'     => '.widget_shopping_cart .woocommerce-mini-cart-item',
				'cart_product_link'     => '.widget_shopping_cart .woocommerce-mini-cart-item a:not(.remove)',
				'cart_product_img'      => '.widget_shopping_cart .woocommerce-mini-cart-item img',
				'cart_product_quantity' => '.widget_shopping_cart .woocommerce-mini-cart-item .quantity',
				'cart_product_amount'   => '.widget_shopping_cart .woocommerce-mini-cart-item .amount',
				'cart_product_remove'   => '.widget_shopping_cart .woocommerce-mini-cart-item .remove',

				'cart_list_total'        => '.widget_shopping_cart .woocommerce-mini-cart__total',
				'cart_list_total_title'  => '.widget_shopping_cart .woocommerce-mini-cart__total strong',
				'cart_list_total_amount' => '.widget_shopping_cart .woocommerce-mini-cart__total .amount',

				'cart_list_buttons' => '.widget_shopping_cart .woocommerce-mini-cart__buttons.buttons',
				'view_cart_button'  => '.widget_shopping_cart .woocommerce-mini-cart__buttons.buttons .button.wc-forward:not(.checkout)',
				'checkout_button'   => '.widget_shopping_cart .woocommerce-mini-cart__buttons.buttons .button.checkout.wc-forward',
			)
		);

		$this->__start_controls_section(
			'section_general_style',
			array(
				'label'      => esc_html__( 'General Styles', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'cart_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'jet-blocks' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'jet-blocks' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} '  . $css_scheme['cart_wrapper'] => 'justify-content: {{VALUE}};',
				),
			),
			25
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'cart_link_style',
			array(
				'label'      => esc_html__( 'Cart Link', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cart_link_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['cart_link'],
			),
			50
		);

		$this->__start_controls_tabs( 'tabs_cart_link_style' );

		$this->__start_controls_tab(
			'nav_items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'cart_link_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_link'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'cart_label_color',
			array(
				'label'  => esc_html__( 'Label Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'cart_icon_color',
			array(
				'label'  => esc_html__( 'Icon Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_icon'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'cart_count_color_bg',
			array(
				'label'  => esc_html__( 'Count Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_count'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'cart_count_color',
			array(
				'label'  => esc_html__( 'Count Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_count'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'cart_totals_color',
			array(
				'label'  => esc_html__( 'Totals Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_totals'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'nav_items_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'cart_link_bg_color_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_link'] . ':hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'cart_link_border_color_hover',
			array(
				'label' => esc_html__( 'Border Color', 'jet-blocks' ),
				'type' => Controls_Manager::COLOR,
				'condition' => array(
					'cart_link_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_link'] . ':hover' => 'border-color: {{VALUE}};',
				),
			),
			75
		);

		$this->__add_control(
			'cart_label_color_hover',
			array(
				'label'  => esc_html__( 'Label Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_link'] . ':hover ' . $css_scheme['cart_label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'cart_icon_color_hover',
			array(
				'label'  => esc_html__( 'Icon Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_link'] . ':hover ' . $css_scheme['cart_icon'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'cart_count_color_bg_hover',
			array(
				'label'  => esc_html__( 'Count Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_link'] . ':hover ' . $css_scheme['cart_count'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'cart_count_color_hover',
			array(
				'label'  => esc_html__( 'Count Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_link'] . ':hover ' . $css_scheme['cart_count'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'cart_totals_color_hover',
			array(
				'label'  => esc_html__( 'Totals Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_link'] . ':hover ' . $css_scheme['cart_totals'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__add_responsive_control(
			'cart_link_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_link'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'cart_link_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['cart_link'],
			),
			75
		);

		$this->__add_responsive_control(
			'cart_link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_link'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_control(
			'cart_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			50
		);

		$this->__add_responsive_control(
			'cart_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'nav_items_icon_gap',
			array(
				'label'      => esc_html__( 'Gap After Icon', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_icon'] => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_control(
			'cart_label_styles',
			array(
				'label'     => esc_html__( 'Label', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			50
		);

		$this->__add_responsive_control(
			'cart_label_font_size',
			array(
				'label'      => esc_html__( 'Label Font Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 90,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_label'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'cart_label_gap',
			array(
				'label'      => esc_html__( 'Gap After Label', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_label'] => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_control(
			'cart_count_styles',
			array(
				'label'     => esc_html__( 'Count', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			50
		);

		$this->__add_responsive_control(
			'cart_count_font_size',
			array(
				'label'      => esc_html__( 'Count Font Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 90,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_count'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'cart_count_box_size',
			array(
				'label'      => esc_html__( 'Count Box Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 16,
						'max' => 90,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_count'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'cart_count_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_count'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'cart_count_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_count'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_control(
			'cart_totals_styles',
			array(
				'label'     => esc_html__( 'Totals', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			50
		);

		$this->__add_responsive_control(
			'cart_totals_font_size',
			array(
				'label'      => esc_html__( 'Totals Font Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 90,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_totals'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'cart_list_style',
			array(
				'label' => esc_html__( 'Cart Dropdown', 'jet-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_cart_list' => 'yes',
				),
			)
		);

		$this->__add_control(
			'cart_list_container_style_heading',
			array(
				'label' => esc_html__( 'Container Styles', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
			),
			25
		);

		$this->__add_responsive_control(
			'cart_list_width',
			array(
				'label'  => esc_html__( 'Width (px)', 'jet-blocks' ),
				'type'   => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 150,
						'max' => 500,
					),
				),
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list'] => 'width: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_control(
			'cart_list_bg_color',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'cart_list_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'cart_list_container_border',
				'label'          => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['cart_list'],
			),
			75
		);

		$this->__add_responsive_control(
			'cart_list_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cart_list_container_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['cart_list'],
			),
			75
		);

		$this->__add_control(
			'close_button_style_heading',
			array(
				'label'     => esc_html__( 'Close Button', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'layout_type' => 'slide-out',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'close_button_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list_close'] => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['cart_list_close'] . ' svg' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'layout_type' => 'slide-out',
				),
			),
			50
		);

		$this->__add_control(
			'close_button_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list_close'] => 'color: {{VALUE}}',
				),
				'condition' => array(
					'layout_type' => 'slide-out',
				),
			),
			50
		);

		$this->__add_control(
			'cart_list_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Position by', 'jet-blocks' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-blocks' ),
					'right' => esc_html__( 'Right', 'jet-blocks' ),
				),
				'separator' => 'before',
				'condition' => array(
					'layout_type' => 'dropdown',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'cart_list_left_position',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'cart_list_hor_position' => 'left',
					'layout_type' => 'dropdown',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list'] => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'cart_list_right_position',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -400,
						'max' => 400,
					),
					'%' => array(
						'min' => -100,
						'max' => 100,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'cart_list_hor_position' => 'right',
					'layout_type' => 'dropdown',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list'] => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				),
			),
			25
		);

		$this->__add_control(
			'cart_list_title_style_heading',
			array(
				'label'     => esc_html__( 'Dropdown Label Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'cart_list_label!' => '',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cart_list_title_typography',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['cart_list_title'],
				'condition' => array(
					'cart_list_label!' => '',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'cart_list_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'condition'  => array(
					'cart_list_label!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'cart_list_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'condition'  => array(
					'cart_list_label!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list_title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'cart_list_title_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['cart_list_title'],
				'condition'   => array(
					'cart_list_label!' => '',
				),
			),
			75
		);

		$this->__add_responsive_control(
			'cart_list_title_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'condition' => array(
					'cart_list_label!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list_title'] => 'text-align: {{VALUE}};',
				),
			),
			50
		);

		$this->__add_control(
			'cart_list_empty_message_heading',
			array(
				'label'     => esc_html__( 'Dropdown Empty Message Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cart_list_empty_message_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['cart_empty_message'],
			),
			50
		);

		$this->__add_responsive_control(
			'cart_list_empty_message_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_empty_message'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'cart_list_empty_message_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_empty_message'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'cart_list_empty_message_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_empty_message'] => 'text-align: {{VALUE}};',
				),
			),
			50
		);

		$this->__end_controls_section();

		/**
		 * Cart Items Style Section
		 */
		$this->__start_controls_section(
			'cart_list_items_style',
			array(
				'label' => esc_html__( 'Cart Items Style', 'jet-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_cart_list' => 'yes',
				),
			)
		);

		/**
		 * Product List Style
		 */
		$this->__add_control(
			'cart_product_list_style_heading',
			array(
				'label' => esc_html__( 'Product List Style', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
			),
			25
		);

		$this->__add_control(
			'cart_product_list_height',
			array(
				'label' => esc_html__( 'Max Height', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_list'] => 'max-height: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'cart_product_list_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_list'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'cart_product_list_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_list'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'cart_product_list_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['cart_product_list'],
			),
			75
		);

		/**
		 * Product Item Style
		 */
		$this->__add_control(
			'cart_product_item_style_heading',
			array(
				'label'     => esc_html__( 'Product Item Style', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_responsive_control(
			'cart_product_item_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_item'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'cart_product_item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_control(
			'cart_product_item_divider',
			array(
				'label'        => esc_html__( 'Divider', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
			),
			75
		);

		$this->__add_control(
			'cart_product_item_divider_style',
			array(
				'label' => esc_html__( 'Style', 'jet-blocks' ),
				'type' => Controls_Manager::SELECT,
				'options' => array(
					'solid'  => esc_html__( 'Solid', 'jet-blocks' ),
					'double' => esc_html__( 'Double', 'jet-blocks' ),
					'dotted' => esc_html__( 'Dotted', 'jet-blocks' ),
					'dashed' => esc_html__( 'Dashed', 'jet-blocks' ),
				),
				'default' => 'solid',
				'condition' => array(
					'cart_product_item_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_item'] . ':not(:first-child)' => 'border-top-style: {{VALUE}}',
				),
			),
			75
		);

		$this->__add_control(
			'cart_product_item_divider_weight',
			array(
				'label'   => esc_html__( 'Weight', 'jet-blocks' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 1,
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'condition' => array(
					'cart_product_item_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_item'] . ':not(:first-child)' => 'border-top-width: {{SIZE}}{{UNIT}}',
				),
			),
			75
		);

		$this->__add_control(
			'cart_product_item_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'cart_product_item_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_item'] . ':not(:first-child)' => 'border-color: {{VALUE}}',
				),
			),
			75
		);

		/**
		 * Product Image Style
		 */
		$this->__add_control(
			'cart_product_image_style_heading',
			array(
				'label'     => esc_html__( 'Product Image Style', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_responsive_control(
			'cart_product_img_size',
			array(
				'label' => esc_html__( 'Width', 'jet-blocks' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 30,
						'max' => 150,
					),
				),
				'step' => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_img'] => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'cart_product_img_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_img'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		/**
		 * Product Title Styles
		 */
		$this->__add_control(
			'cart_product_title_style_heading',
			array(
				'label'     => esc_html__( 'Product Title Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cart_product_title_typography',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['cart_product_link'],
			),
			50
		);

		$this->__add_control(
			'cart_product_title_color',
			array(
				'label' => esc_html__( 'Color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_link'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'cart_product_title_hover_color',
			array(
				'label' => esc_html__( 'Hover Color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_link'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		/**
		 * Product Remove Button Styles
		 */
		$this->__add_control(
			'cart_product_remove_style_heading',
			array(
				'label'     => esc_html__( 'Product Remove Button Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_control(
			'cart_product_remove_bnt_color',
			array(
				'label' => esc_html__( 'Color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_remove'] => 'color: {{VALUE}} !important',
				),
			),
			25
		);

		$this->__add_control(
			'cart_product_remove_bnt_hover_color',
			array(
				'label' => esc_html__( 'Hover Color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_remove'] . ':hover' => 'color: {{VALUE}} !important',
				),
			),
			25
		);

		/**
		 * Product Quantity Styles
		 */
		$this->__add_control(
			'cart_product_quantity_style_heading',
			array(
				'label'     => esc_html__( 'Product Quantity Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cart_product_quantity_typography',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['cart_product_quantity'],
			),
			50
		);

		$this->__add_control(
			'cart_cart_product_quantity_color',
			array(
				'label' => esc_html__( 'Color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_quantity'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		/**
		 * Product Amount Styles
		 */
		$this->__add_control(
			'cart_product_amount_style_heading',
			array(
				'label'     => esc_html__( 'Product Amount Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cart_product_amount_typography',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['cart_product_amount'],
			),
			50
		);

		$this->__add_control(
			'cart_product_amount_color',
			array(
				'label' => esc_html__( 'Color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_product_amount'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		/**
		 * Total Styles
		 */
		$this->__add_control(
			'cart_list_total_style_heading',
			array(
				'label'     => esc_html__( 'Total Container Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_responsive_control(
			'cart_list_total_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list_total'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'cart_list_total_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list_total'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'cart_list_total_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['cart_list_total'],
			),
			75
		);

		$this->__add_responsive_control(
			'cart_list_total_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-blocks' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-blocks' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} '  . $css_scheme['cart_list_total'] => 'text-align: {{VALUE}};',
				),
			),
			50
		);

		/**
		 * Total Title Styles
		 */
		$this->__add_control(
			'cart_list_total_title_style_heading',
			array(
				'label'     => esc_html__( 'Total Title Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cart_list_total_title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['cart_list_total_title'],
			),
			50
		);

		$this->__add_control(
			'cart_list_total_title_color',
			array(
				'label' => esc_html__( 'Color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list_total_title'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		/**
		 * Total Amount Styles
		 */
		$this->__add_control(
			'cart_list_total_amount_style_heading',
			array(
				'label'     => esc_html__( 'Total Amount Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cart_list_total_amount_typography',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['cart_list_total_amount'],
			),
			50
		);

		$this->__add_control(
			'cart_list_total_amount_color',
			array(
				'label' => esc_html__( 'Color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list_total_amount'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__end_controls_section();

		/**
		 * Cart Buttons Style Section
		 */
		$this->__start_controls_section(
			'cart_buttons_style',
			array(
				'label' => esc_html__( 'Cart Buttons Style', 'jet-blocks' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_cart_list' => 'yes',
				),
			)
		);

		/**
		 * Buttons Container Styles
		 */
		$this->__add_control(
			'cart_list_buttons_style_heading',
			array(
				'label' => esc_html__( 'Buttons Container Styles', 'jet-blocks' ),
				'type'  => Controls_Manager::HEADING,
			),
			25
		);

		$this->__add_responsive_control(
			'cart_list_buttons_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list_buttons'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'cart_list_buttons_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['cart_list_buttons'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'cart_list_buttons_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['cart_list_buttons'],
			),
			75
		);

		/**
		 * View Cart Button Styles
		 */
		$this->__add_control(
			'view_cart_button_style_heading',
			array(
				'label'     => esc_html__( 'View Cart Button Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'view_cart_btn_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['view_cart_button'],
			),
			50
		);

		$this->__start_controls_tabs( 'tabs_view_cart_btn_style' );

		$this->__start_controls_tab(
			'tab_view_cart_btn_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'view_cart_btn_background',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['view_cart_button'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'view_cart_btn_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['view_cart_button'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'view_cart_btn_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['view_cart_button'],
			),
			75
		);

		$this->__add_responsive_control(
			'view_cart_btn_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['view_cart_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'view_cart_btn_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['view_cart_button'],
			),
			100
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'tab_view_cart_btn_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'view_cart_btn_hover_background',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['view_cart_button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'view_cart_btn_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['view_cart_button'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'view_cart_btn_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'view_cart_btn_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['view_cart_button'] . ':hover' => 'border-color: {{VALUE}};',
				),
			),
			75
		);

		$this->__add_responsive_control(
			'view_cart_btn_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['view_cart_button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'view_cart_btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['view_cart_button'] . ':hover',
			),
			100
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__add_responsive_control(
			'view_cart_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['view_cart_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->__add_responsive_control(
			'view_cart_btn_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['view_cart_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		/**
		 * Checkout Button Styles
		 */
		$this->__add_control(
			'checkout_button_style_heading',
			array(
				'label'     => esc_html__( 'Checkout Button Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'checkout_btn_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['checkout_button'],
			),
			50
		);

		$this->__start_controls_tabs( 'tabs_checkout_btn_style' );

		$this->__start_controls_tab(
			'tab_checkout_btn_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'checkout_btn_background',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['checkout_button'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'checkout_btn_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['checkout_button'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'checkout_btn_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['checkout_button'],
			),
			75
		);

		$this->__add_responsive_control(
			'checkout_btn_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['checkout_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'checkout_btn_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['checkout_button'],
			),
			100
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'tab_checkout_btn_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'checkout_btn_hover_background',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['checkout_button'] . ':hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'checkout_btn_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['checkout_button'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_control(
			'checkout_btn_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'checkout_btn_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['checkout_button'] . ':hover' => 'border-color: {{VALUE}};',
				),
			),
			50
		);

		$this->__add_responsive_control(
			'checkout_btn_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['checkout_button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'checkout_btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['checkout_button'] . ':hover',
			),
			100
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__add_responsive_control(
			'checkout_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['checkout_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->__add_responsive_control(
			'checkout_btn_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['checkout_button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings();

		$this->__context = 'render';

		$this->__open_wrap();
		include $this->__get_global_template( 'index' );
		$this->__close_wrap();

	}

}
