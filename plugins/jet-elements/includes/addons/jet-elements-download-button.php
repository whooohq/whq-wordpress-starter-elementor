<?php
/**
 * Class: Jet_Elements_Download_Button
 * Name: Download Button
 * Slug: jet-download-button
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Download_Button extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-download-button';
	}

	public function get_title() {
		return esc_html__( 'Download Button', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-download-button';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetelements-download-button-how-to-allow-the-visitors-download-files-stored-in-media-library/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'jet-elements' ),
			)
		);

		$this->add_control(
			'download_file',
			array(
				'label'       => esc_html__( 'Download ID', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true, ),
				'description' => esc_html__( 'Here you can specify ID of media library element which will be downloaded on button click', 'jet-elements' ),
				'default'     => array(),
			)
		);

		$this->add_control(
			'download_label',
			array(
				'label'       => esc_html__( 'Download Link Text', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Download', 'jet-elements' ),
				'description' => esc_html__( 'You can use %size% placeholder to show formatted file size in label', 'jet-elements' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'download_sub_label',
			array(
				'label'       => esc_html__( 'Download Link Sub Text', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '%size%', 'jet-elements' ),
				'description' => esc_html__( 'You can use %size% placeholder to show formatted file size in label', 'jet-elements' ),
			)
		);

		$this->add_control(
			'download_link_rel',
			array(
				'label'        => esc_html__( 'Add nofollow', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'nofollow',
			)
		);

		$this->_add_advanced_icon_control(
			'download_icon',
			array(
				'label'       => esc_html__( 'Download Link Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
			)
		);

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-elements/download-button/css-scheme',
			array(
				'button'          => '.jet-download',
				'icon'            => '.jet-download__icon',
				'button_label'    => '.jet-download__label',
				'button_sublabel' => '.jet-download__sub-label',
			)
		);

		$this->_start_controls_section(
			'section_action_button_style',
			array(
				'label'      => esc_html__( 'General', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'button_self_alignment',
			array(
				'label'   => esc_html__( 'Button Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => '',
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'jet-elements' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'prefix_class' => 'elementor%s-align-',
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_add_responsive_control(
			'button_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ', {{WRAPPER}} .jet-download__text' => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->_add_control(
			'button_icon_heading',
			array(
				'label'     => esc_html__( 'Button Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'button_icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'jet-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 7,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_control(
			'download_icon_position',
			array(
				'label'   => esc_html__( 'Download Link Icon', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'left'   => esc_html__( 'Left', 'jet-elements' ),
					'right'  => esc_html__( 'Right', 'jet-elements' ),
					'top'    => esc_html__( 'Top', 'jet-elements' ),
					'bottom' => esc_html__( 'Bottom', 'jet-elements' ),
				),
				'default' => 'left',
			),
			25
		);

		$this->_add_responsive_control(
			'button_icon_margin',
			array(
				'label'      => esc_html__( 'Icon Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_action_button_states',
			array(
				'label'      => esc_html__( 'Button', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_start_controls_tabs( 'tabs_button_style' );

		$this->_start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
			),
			25
		);

		$this->_add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
			),
			50
		);

		$this->_add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
			),
			100
		);

		$this->_add_control(
			'button_icon',
			array(
				'label'     => esc_html__( 'Button Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'button_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'button_label',
			array(
				'label'     => esc_html__( 'Button Label', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button_label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button_label'],
			),
			50
		);

		$this->_add_control(
			'button_sublabel',
			array(
				'label'     => esc_html__( 'Button Sub-label', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'button_sub_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button_sublabel'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_sub_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button_sublabel'],
			),
			50
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_bg_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			),
			25
		);

		$this->_add_responsive_control(
			'button_hover_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			),
			50
		);

		$this->_add_responsive_control(
			'button_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			),
			100
		);

		$this->_add_control(
			'button_icon_hover',
			array(
				'label'     => esc_html__( 'Button Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'button_hover_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['icon'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'button_label_hover',
			array(
				'label'     => esc_html__( 'Button Label', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['button_label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_hover_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['button_label'],
			),
			50
		);

		$this->_add_control(
			'button_hover_sublabel',
			array(
				'label'     => esc_html__( 'Button Sub-label', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'button_hover_sub_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover ' . $css_scheme['button_sublabel'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_hover_sub_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover '. $css_scheme['button_sublabel'],
			),
			50
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

	}

	/**
	 * Replace placeholders in label
	 *
	 * @param  string $text Input text.
	 * @return string
	 */
	protected function _format_label( $text = '', $id = 0 ) {

		if ( ! $id ) {
			return $text;
		}

		if ( false !== strpos( $text, '%size%' ) ) {
			$size = jet_elements_download_handler()->get_file_size( $id );
			$text = str_replace( '%size%', $size, $text );
		}

		return $text;
	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

}
