<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Woo_Sale_Products extends Jet_Elements_Base {

	public function get_name() {
		return 'woo-sale-products';
	}

	public function get_title() {
		return esc_html__( 'WooCommerce Sale Products', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-woo-sale-products';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetelements-woocommerce-sale-products-widget-how-to-add-products-on-sale-to-your-website/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function _tag() {
		return 'sale_products';
	}

	public function _atts() {

		return array(
			'per_page' => array(
				'label'   => esc_html__( 'Products per page', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 12,
			),
			'columns' => array(
				'label'     => esc_html__( 'Columns', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				),
			),
			'orderby' => array(
				'label'   => esc_html__( 'Order By', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => jet_elements_tools()->orderby_arr(),
			),
			'order' => array(
				'label'   => esc_html__( 'Order', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => jet_elements_tools()->order_arr(),
			),
			'paginate' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Sale Products Pagination', 'jet-elements' ),
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => '',
			)

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

			$attr_val    = $settings[ $attr ];
			$attr_val    = ! is_array( $attr_val ) ? $attr_val : implode( ',', $attr_val );
			$attributes .= sprintf( ' %1$s="%2$s"', $attr, $attr_val );
		}

		$shortcode = sprintf( '[%s %s]', $this->_tag(), $attributes );
		echo do_shortcode( $shortcode );

		$this->_close_wrap();

	}

}
