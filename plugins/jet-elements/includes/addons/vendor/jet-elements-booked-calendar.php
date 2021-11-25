<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Booked_Calendar extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-booked-calendar';
	}

	public function get_title() {
		return esc_html__( 'Booked Calendar', 'jet-elements' );
	}

	public function get_icon() {
		return 'eicon-date';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/article-category/jet-elements/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function _tag() {
		return 'booked-calendar';
	}

	public function _atts() {

		return array(
			'calendar' => array(
				'label'   => esc_html__( 'Calendar ID', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			),
			'year' => array(
				'label'   => esc_html__( 'Year', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			),
			'month' => array(
				'label'   => esc_html__( 'Month', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			),
			'switcher' => array(
				'label'   => esc_html__( 'Show calendar switcher?', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'on'  => esc_html__( 'Yes', 'jet-elements' ),
					'off' => esc_html__( 'No', 'jet-elements' ),
				),
			),
			'size' => array(
				'label'   => esc_html__( 'Calendar size', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'large' => esc_html__( 'Large', 'jet-elements' ),
					'small' => esc_html__( 'Small', 'jet-elements' ),
				),
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

			$attr_val    = $settings[ $attr ];
			$attr_val    = ! is_array( $attr_val ) ? $attr_val : implode( ',', $attr_val );
			$attributes .= sprintf( ' %1$s="%2$s"', $attr, $attr_val );
		}

		$shortcode = sprintf( '[%s %s]', $this->_tag(), $attributes );
		echo do_shortcode( $shortcode );

		$this->_close_wrap();

	}

}
