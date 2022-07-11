<?php
/**
 * Class: Jet_Woo_Builder_Archive_Product_Title
 * Name: Title
 * Slug: jet-woo-builder-archive-product-title
 */

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Woo_Builder_Archive_Product_Title extends Widget_Base {

	public function get_name() {
		return 'jet-woo-builder-archive-product-title';
	}

	public function get_title() {
		return esc_html__( 'Title', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-title';
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
			'jet-woo-builder/jet-archive-product-title/css-scheme',
			array(
				'title' => '.jet-woo-builder-archive-product-title',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'Content', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'is_linked',
			array(
				'label'        => esc_html__( 'Add link to title', 'jet-woo-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'open_new_tab',
			[
				'label'     => esc_html__( 'Open in new window', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off' => esc_html__( 'No', 'jet-woo-builder' ),
				'default'   => '',
				'condition' => [
					'is_linked' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-woo-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h5',
				'options' => jet_woo_builder_tools()->get_available_title_html_tags(),
			)
		);

		$this->add_control(
			'title_trim_type',
			array(
				'label'   => esc_html__( 'Title Trim Type', 'jet-woo-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'word',
				'options' => jet_woo_builder_tools()->get_available_title_trim_types(),
			)
		);

		$this->add_control(
			'title_length',
			[
				'label'       => esc_html__( 'Title Words/Letters Count', 'jet-woo-builder' ),
				'description' => esc_html__( 'Set -1 to show full title and 0 to hide it.', 'jet-woo-builder' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => -1,
				'default'     => -1,
			]
		);

		$this->add_control(
			'title_tooltip',
			[
				'label'        => esc_html__( 'Enable Title Tooltip', 'jet-woo-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'title_length',
							'operator' => '>',
							'value'    => 0,
						],
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_archive_title_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'archive_title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'] . ', {{WRAPPER}} ' . $css_scheme['title'] . ' a',
			)
		);

		$this->start_controls_tabs( 'tabs_archive_title_style' );

		$this->start_controls_tab(
			'tab_archive_title_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'archive_title_color_normal',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title']        => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['title'] . ' a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_archive_title_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'archive_title_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] . ':hover'   => ' color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['title'] . ' a:hover' => ' color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'archive_title_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options'   => jet_woo_builder_tools()->get_available_h_align_types(),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
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

		$full_title    = jet_woo_builder_template_functions()->get_product_title();
		$title         = jet_woo_builder_tools()->trim_text(
			$full_title,
			isset ( $settings['title_length'] ) ? $settings['title_length'] : 1,
			$settings['title_trim_type'],
			'...'
		);
		$title_tooltip = '';

		if ( -1 !== $settings['title_length'] && 'yes' === $settings['title_tooltip'] ) {
			$title_tooltip = 'title="' . $full_title . '"';
		}

		$heading_tag = isset( $settings['title_html_tag'] ) ? $settings['title_html_tag'] : 'h5';
		$open_wrap   = '<' . $heading_tag . ' class="jet-woo-builder-archive-product-title" ' . $title_tooltip . '>';
		$close_wrap  = '</' . $heading_tag . '>';
		$target_attr = 'yes' === $settings['open_new_tab'] ? 'target="_blank"' : '';

		if ( 'yes' === $settings['is_linked'] ) {
			$link       = jet_woo_builder_template_functions()->get_product_permalink();
			$open_wrap  = $open_wrap . '<a href="' . $link . '" ' . $target_attr . '>';
			$close_wrap = '</a>' . $close_wrap;
		}

		echo $open_wrap . $title . $close_wrap;

	}

	protected function render() {

		$settings = $this->get_settings();

		$macros_settings = array(
			'is_linked'       => $settings['is_linked'],
			'open_new_tab'    => $settings['open_new_tab'],
			'title_html_tag'  => jet_woo_builder_tools()->sanitize_html_tag( $settings['title_html_tag'] ),
			'title_trim_type' => $settings['title_trim_type'],
			'title_length'    => $settings['title_length'],
			'title_tooltip'   => $settings['title_tooltip'],
		);

		if ( jet_woo_builder_tools()->is_builder_content_save() ) {
			echo jet_woo_builder()->parser->get_macros_string( $this->get_name(), $macros_settings );
		} else {
			echo self::render_callback( $macros_settings );
		}

	}

}
