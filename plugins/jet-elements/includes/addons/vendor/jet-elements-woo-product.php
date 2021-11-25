<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Woo_Product extends Jet_Elements_Base {

	public function get_name() {
		return 'woo-product';
	}

	public function get_title() {
		return esc_html__( 'WooCommerce Product', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-woo-product';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetelements-woocommerce-product-widget-how-to-add-custom-products-to-your-website/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function _tag() {
		return 'product';
	}

	public function _atts() {

		return array(
			'product_id' => array(
				'label'   => esc_html__( 'Product ID', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
			),
			'sku' => array(
				'label'     => esc_html__( 'Product SKU', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
			),
		);
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		foreach ( $this->_atts() as $control => $data ) {
			$this->add_control( $control, $data );
		}

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings();

		$this->_context = 'render';

		$this->_open_wrap();

		$attributes = '';

		foreach ( $this->_atts() as $attr => $data ) {

			$attr_val = $settings[ $attr ];
			$attr_val = ! is_array( $attr_val ) ? $attr_val : implode( ',', $attr_val );

			if ( 'product_id' === $attr ) {
				$attr = 'id';
			}

			$attributes .= sprintf( ' %1$s="%2$s"', $attr, $attr_val );
		}

		$shortcode = sprintf( '[%s %s]', $this->_tag(), $attributes );
		echo do_shortcode( $shortcode );

		$this->_close_wrap();

	}

}
