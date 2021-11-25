<?php
/**
 * Class: Jet_Elements_Timeline
 * Name: Vertical Timeline
 * Slug: jet-timeline
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Elements_Timeline extends Jet_Elements_Base {
	public $_processed_item_index = 0;

	public function get_name() {
		return 'jet-timeline';
	}

	public function get_title() {
		return esc_html__( 'Vertical Timeline', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-v-timeline';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetelements-vertical-timeline-widget-how-to-add-a-project-timeline/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/timeline/css-scheme',
			array(
				'line'               => '.jet-timeline__line',
				'progress'           => '.jet-timeline__line-progress',
				'item'               => '.jet-timeline-item',
				'item_point'         => '.timeline-item__point',
				'item_point_content' => '.timeline-item__point-content',
				'item_meta'          => '.timeline-item__meta-content',
				'card'               => '.timeline-item__card',
				'card_inner'         => '.timeline-item__card-inner',
				'card_img'           => '.timeline-item__card-img',
				'card_content'       => '.timeline-item__card-content',
				'card_title'         => '.timeline-item__card-title',
				'card_desc'          => '.timeline-item__card-desc',
				'card_arrow'         => '.timeline-item__card-arrow',
				'card_btn_wrap'      => '.timeline-item__card-btn-wrap',
				'card_btn'           => '.timeline-item__card-btn',
			)
		);

		$this->start_controls_section(
			'section_cards',
			array(
				'label' => esc_html__( 'Cards', 'jet-elements' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'show_item_image',
			array(
				'label'        => esc_html__( 'Show Image', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$repeater->add_control(
			'item_image_position',
			array(
				'label'       => esc_html__( 'Image position', 'jet-elements' ),
				'description' => esc_html__( 'Note: Outside image positions works only with center horizontal alignment.', 'jet-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'inside',
				'options'     => array(
					'inside'         => esc_html__( 'Inside before text', 'jet-elements' ),
					'inside_after'   => esc_html__( 'Inside after text', 'jet-elements' ),
					'outside_before' => esc_html__( 'Outside before meta', 'jet-elements' ),
					'outside_after'  => esc_html__( 'Outside after meta', 'jet-elements' ),
				),
				'condition'   => array(
					'show_item_image' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'item_image',
			array(
				'label'     => esc_html__( 'Image', 'jet-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'show_item_image' => 'yes',
				),
				'dynamic'   => array( 'active' => true ),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'item_image',
				'default'   => 'full',
				'condition' => array(
					'show_item_image' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'item_title',
			array(
				'label'   => esc_html__( 'Title', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_meta',
			array(
				'label'   => esc_html__( 'Meta', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_desc',
			array(
				'label'   => esc_html__( 'Description', 'jet-elements' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_point',
			array(
				'label'     => esc_html__( 'Point', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$repeater->add_control(
			'item_point_type',
			array(
				'label'   => esc_html__( 'Point Content Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'icon' => esc_html__( 'Icon', 'jet-elements' ),
					'text' => esc_html__( 'Text', 'jet-elements' ),
				),
			)
		);

		$this->_add_advanced_icon_control(
			'item_point_icon',
			array(
				'label'       => esc_html__( 'Point Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
				'default'     => 'fa fa-calendar',
				'fa5_default' => array(
					'value'   => 'fas fa-calendar-alt',
					'library' => 'fa-solid',
				),
				'condition'   => array(
					'item_point_type' => 'icon'
				)
			),
			$repeater
		);

		$repeater->add_control(
			'item_point_text',
			array(
				'label'     => esc_html__( 'Point Text', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'A',
				'condition' => array(
					'item_point_type' => 'text'
				)
			)
		);

		$repeater->add_control(
			'item_btn',
			array(
				'label'     => esc_html__( 'Button', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$repeater->add_control(
			'item_btn_text',
			array(
				'label'   => esc_html__( 'Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'item_btn_url',
			array(
				'label'   => esc_html__( 'Link', 'jet-elements' ),
				'type'    => Controls_Manager::URL,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'cards_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'item_title' => esc_html__( 'Card #1', 'jet-elements' ),
						'item_desc'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'jet-elements' ),
						'item_meta'  => esc_html__( 'Thursday, August 31, 2020', 'jet-elements' ),
					),
					array(
						'item_title' => esc_html__( 'Card #2', 'jet-elements' ),
						'item_desc'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'jet-elements' ),
						'item_meta'  => esc_html__( 'Thursday, August 29, 2020', 'jet-elements' ),
					),
					array(
						'item_title' => esc_html__( 'Card #3', 'jet-elements' ),
						'item_desc'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'jet-elements' ),
						'item_meta'  => esc_html__( 'Thursday, August 28, 2020', 'jet-elements' ),
					),
					array(
						'item_title' => esc_html__( 'Card #4', 'jet-elements' ),
						'item_desc'  => esc_html__( 'Lorem ipsum dolor sit amet, mea ei viderer probatus consequuntur, sonet vocibus lobortis has ad. Eos erant indoctum an, dictas invidunt est ex, et sea consulatu torquatos. Nostro aperiam petentium eu nam, mel debet urbanitas ad, idque complectitur eu quo. An sea autem dolore dolores.', 'jet-elements' ),
						'item_meta'  => esc_html__( 'Thursday, August 27, 2020', 'jet-elements' ),
					),
				),
				'title_field' => '{{{ item_title }}}',
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'default'   => 'h5',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'jet-elements' ),
			)
		);

		$this->add_control(
			'animate_cards',
			array(
				'label'        => esc_html__( 'Animate Cards', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'jet-timeline-item--animated',
				'default'      => '',
			)
		);

		$this->add_control(
			'horizontal_alignment',
			array(
				'label'   => esc_html__( 'Horizontal Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'classes' => 'jet-elements-text-align-control',
			)
		);

		$this->add_control(
			'vertical_alignment',
			array(
				'label'   => esc_html__( 'Vertical Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'middle',
				'options' => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'jet-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'jet-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'jet-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
			)
		);

		$this->add_responsive_control(
			'horizontal_space',
			array(
				'label'      => esc_html__( 'Horizontal Space', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-timeline--align-center ' . $css_scheme['item_point'] => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .jet-timeline--align-left ' . $css_scheme['item_point']   => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .jet-timeline--align-right ' . $css_scheme['item_point']  => 'margin-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'vertical_space',
			array(
				'label'      => esc_html__( 'Vertical Space', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'default'    => array(
					'size' => 30,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '+' . $css_scheme['item'] => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->_start_controls_section(
			'section_cards_style',
			array(
				'label'      => esc_html__( 'Cards', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_control_section_cards( $css_scheme );

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_image_style',
			array(
				'label'      => esc_html__( 'Image', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_control_section_image( $css_scheme );

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_meta_style',
			array(
				'label'      => esc_html__( 'Meta', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_control_section_meta( $css_scheme );

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_card_content_style',
			array(
				'label'      => esc_html__( 'Content', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_control_section_card_content( $css_scheme );

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_card_title_style',
			array(
				'label'      => esc_html__( 'Title', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_control_section_card_title( $css_scheme );

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_desc_style',
			array(
				'label'      => esc_html__( 'Description', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_control_section_card_desc( $css_scheme );

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Button', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} ' . $css_scheme['card_btn'],
			),
			50
		);

		$this->_start_controls_tabs( 'tabs_button_style' );

		$this->_start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_btn'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_btn'] => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_btn'] . ':hover' => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'button_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_btn'] . ':hover' => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['card_btn'] . ':hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'button_border_border!' => '',
				),
			),
			75
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['card_btn'],
				'separator' => 'before',
			),
			75
		);

		$this->_add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['card_btn'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['card_btn'],
			),
			100
		);

		$this->_add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['card_btn'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			),
			25
		);

		$this->_add_responsive_control(
			'button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['card_btn_wrap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_point_style',
			array(
				'label'      => esc_html__( 'Point', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_control_section_points( $css_scheme );

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_line_style',
			array(
				'label'      => esc_html__( 'Line', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_control_section_line( $css_scheme );

		$this->_end_controls_section();
	}

	public function _control_section_cards( $css_scheme ) {

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'cards_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card'] . ',' . '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_arrow'],
			),
			75
		);

		$this->_add_responsive_control(
			'cards_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card']       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;'
				),
			),
			75
		);

		$this->_add_responsive_control(
			'cards_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_start_controls_tabs( 'cards_style_tabs' );

		$this->_start_controls_tab(
			'cards_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'cards_background_normal',
			array(
				'label'     => esc_html__( 'Background', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card']       => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_arrow'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cards_box_shadow_normal',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'cards_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'cards_background_hover',
			array(
				'label'     => esc_html__( 'Background', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card']       => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_arrow'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'cards_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card']       => 'border-color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};'
				),
				'condition' => array(
					'cards_border_border!' => '',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cards_box_shadow_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'cards_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'cards_background_active',
			array(
				'label'     => esc_html__( 'Background', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card']       => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_inner'] => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_arrow'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'cards_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card']       => 'border-color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_arrow'] => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'cards_border_border!' => '',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'cards_box_shadow_active',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_control(
			'cards_arrow_heading',
			array(
				'label'     => esc_html__( 'Arrow', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_responsive_control(
			'cards_arrow_width',
			array(
				'label'      => esc_html__( 'Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_arrow'] => 'width:{{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-timeline--align-center ' . $css_scheme['item'] . ':nth-child(odd) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .jet-timeline--align-center ' . $css_scheme['item'] . ':nth-child(even) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
					'(desktop){{WRAPPER}} .jet-timeline--align-center ' . $css_scheme['item'] . ':nth-child(odd) ' . $css_scheme['card_arrow'] => 'margin-right:calc( -{{SIZE}}{{UNIT}} / 2 );',
					'(desktop){{WRAPPER}} .jet-timeline--align-center ' . $css_scheme['item'] . ':nth-child(even) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
					'(desktop) .rtl {{WRAPPER}} .jet-timeline--align-center ' . $css_scheme['item'] . ':nth-child(odd) ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
					'(desktop) .rtl {{WRAPPER}} .jet-timeline--align-center ' . $css_scheme['item'] . ':nth-child(even) ' . $css_scheme['card_arrow'] => 'margin-right:calc( -{{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .jet-timeline--align-left ' . $css_scheme['item'] . ' ' . $css_scheme['card_arrow'] => 'margin-left:calc( -{{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .jet-timeline--align-right ' . $css_scheme['item'] . ' ' . $css_scheme['card_arrow'] => 'margin-right:calc( -{{SIZE}}{{UNIT}} / 2 );',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'card_arrow_offset',
			array(
				'label'      => esc_html__( 'Offset', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'    => array(
					'size' => 12,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-timeline--align-top ' . $css_scheme['item'] . ' ' . $css_scheme['card_arrow']    => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-timeline--align-bottom ' . $css_scheme['item'] . ' ' . $css_scheme['card_arrow'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'vertical_alignment!' => 'middle'
				)
			),
			25
		);

	}

	public function _control_section_image( $css_scheme ) {

		$this->_add_responsive_control(
			'image_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_img'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-timeline-item--image-inside_after ' . $css_scheme['card_img'] => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
				),
			),
			25
		);

		$this->_add_control(
			'image_outside_spacing',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'style',
				'selectors' => array(
					'(desktop+){{WRAPPER}} .jet-timeline--align-center ' . $css_scheme['item'] . '.jet-timeline-item--image-outside_after ' . $css_scheme['card_img'] => 'margin-top: {{image_spacing.SIZE}}{{image_spacing.UNIT}}; margin-bottom: 0;',
				),
			),
			25
		);

		$this->_add_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_img'] . ' img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			),
			75
		);

	}

	public function _control_section_meta( $css_scheme ) {

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_meta'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'meta_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_meta'],
			),
			75
		);

		$this->_add_control(
			'meta_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_meta'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'meta_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_meta'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'meta_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_meta'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_start_controls_tabs( 'meta_style_tabs' );

		$this->_start_controls_tab(
			'meta_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'meta_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_meta'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'meta_normal_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'meta_normal_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['item_meta'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'meta_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'meta_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'meta_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'meta_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'meta_border_border!' => '',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'meta_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_meta'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'meta_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'meta_active_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'meta_active_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'meta_active_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'] => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'meta_border_border!' => '',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'meta_active_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_meta'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

	}

	public function _control_section_card_content( $css_scheme ) {

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'card_content_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_content'],
			),
			75
		);

		$this->_add_control(
			'card_content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'card_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_start_controls_tabs( 'card_content_style_tabs' );

		$this->_start_controls_tab(
			'card_content_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'card_content_normal_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_content'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_content_normal_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_content'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'card_content_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'card_content_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_content'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'card_content_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_content'] => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'card_content_border_border!' => '',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_content_hover_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_content'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'card_content_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'card_content_active_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_content'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'card_content_active_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_content'] => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'card_content_border_border!' => '',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_content_active_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_content'],
			),
			100
		);

		$this->_end_controls_tab();

		$this->_add_responsive_control(
			'card_content_align',
			array(
				'label' => esc_html__( 'Alignment', 'jet-elements' ),
				'type'  => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'jet-elements' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_content'] => 'text-align: {{VALUE}}',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->_end_controls_tabs();
	}

	public function _control_section_card_title( $css_scheme ) {

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_title'],
			),
			50
		);

		$this->_add_responsive_control(
			'card_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_start_controls_tabs( 'card_title_style_tabs' );

		$this->_start_controls_tab(
			'card_title_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'card_title_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_title'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'card_title_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'card_title_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_title'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'card_title_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'card_title_active_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_title'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

	}

	public function _control_section_card_desc( $css_scheme ) {

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_desc_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_desc'],
			),
			50
		);

		$this->_add_responsive_control(
			'card_desc_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_start_controls_tabs( 'card_desc_style_tabs' );

		$this->_start_controls_tab(
			'card_desc_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'card_desc_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' ' . $css_scheme['card_desc'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'card_desc_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'card_desc_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['card_desc'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'card_desc_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'card_desc_active_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['card_desc'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

	}

	public function _control_section_points( $css_scheme ) {

		$this->_start_controls_tabs( 'point_type_style_tabs', 50 );

		$this->_start_controls_tab(
			'point_type_text_styles',
			array(
				'label' => esc_html__( 'Text', 'jet-elements' ),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'point_text_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['item_point_content'] . '.timeline-item__point-content--text',
			),
			50
		);

		$this->_end_controls_tab( 50 );

		$this->_start_controls_tab(
			'point_type_icon_styles',
			array(
				'label' => esc_html__( 'Icon', 'jet-elements' ),
			),
			50
		);

		$this->_add_responsive_control(
			'point_type_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 16,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item_point_content'] . '.timeline-item__point-content--icon .jet-elements-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_tab( 50 );

		$this->_end_controls_tabs( 50 );

		$this->_add_responsive_control(
			'point_size',
			array(
				'label'      => esc_html__( 'Point Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 40,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item_point_content']               => 'height:{{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-timeline--align-center ' . $css_scheme['line'] => 'margin-left: calc( {{SIZE}}{{UNIT}} / 2 ); margin-right: calc( {{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .jet-timeline--align-left ' . $css_scheme['line']   => 'margin-left: calc( {{SIZE}}{{UNIT}} / 2 );',
					'{{WRAPPER}} .jet-timeline--align-right ' . $css_scheme['line']  => 'margin-right: calc( {{SIZE}}{{UNIT}} / 2 );',
				),
				'separator' => 'before',
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'point_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['item_point_content'],
			),
			75
		);

		$this->_add_control(
			'point_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_start_controls_tabs( 'point_style_tabs' );

		$this->_start_controls_tab(
			'point_normal_styles',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'point_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'point_normal_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'point_hover_styles',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'point_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'point_hover_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'point_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover ' . $css_scheme['item_point_content'] => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'point_border_border!' => '',
				),
			),
			75
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'point_active_styles',
			array(
				'label' => esc_html__( 'Active', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'point_active_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_point_content'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'point_active_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_point_content'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'point_active_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.is--active ' . $css_scheme['item_point_content'] => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'point_border_border!' => '',
				),
			),
			75
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

	}

	public function _control_section_line( $css_scheme ) {

		$this->_add_control(
			'line_background_color',
			array(
				'label'     => esc_html__( 'Line Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['line'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'progress_background_color',
			array(
				'label'     => esc_html__( 'Progress Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['progress'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'line_width',
			array(
				'label'      => esc_html__( 'Thickness', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 15,
					),
				),
				'default'    => array(
					'size' => 2,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['line'] => 'width: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'line_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['line'],
			),
			75
		);

		$this->_add_control(
			'line_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['line'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

	}

	public function _generate_point_content( $item_settings ) {
		echo '<div class="timeline-item__point">';
		switch ( $item_settings['item_point_type'] ) {
			case 'icon':
				$this->_icon( 'item_point_icon', '<div class="timeline-item__point-content timeline-item__point-content--icon"><span class="jet-elements-icon">%s</span></div>' );
				break;
			case 'text':
				echo $this->_loop_item( array( 'item_point_text' ), '<div class="timeline-item__point-content timeline-item__point-content--text">%s</div>' );
				break;
		}
		echo '</div>';
	}

	public function get_item_inline_editing_attributes( $settings_item_key, $repeater_item_key, $index, $classes ) {
		$item_key = $this->get_repeater_setting_key( $settings_item_key, $repeater_item_key, $index );
		$this->add_render_attribute( $item_key, [ 'class' => $classes ] );
		$this->add_inline_editing_attributes( $item_key, 'basic' );

		return $this->get_render_attribute_string( $item_key );
	}

	protected function render() {
		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();

		$this->_processed_item_index = 0;
	}
	
	public function _get_timeline_image() {
		$image_item = $this->_processed_item['item_image'];
		
		if ( empty( $image_item['url'] ) ) {
			return;
		}

		$img_html = Group_Control_Image_Size::get_attachment_image_html( $this->_processed_item, 'item_image' );

		return sprintf( '<div class="timeline-item__card-img">%s</div>', $img_html );
	}

	public function _get_timeline_button() {
		$item_settings = $this->_processed_item;

		if ( empty( $item_settings['item_btn_text'] ) || empty( $item_settings['item_btn_url']['url'] ) ) {
			return false;
		}

		$this->add_render_attribute(
			'button_' . $item_settings['_id'],
			array(
				'class' => array(
					'timeline-item__card-btn',
					'elementor-button',
					'elementor-size-md',
				),
				'role' => 'button',
			)
		);

		if ( method_exists( $this, 'add_link_attributes' ) ) {
			$this->add_link_attributes( 'button_' . $item_settings['_id'], $item_settings['item_btn_url'] );
		} else {
			$this->add_render_attribute( 'button_' . $item_settings['_id'], 'href', esc_url( $item_settings['item_btn_url']['url'] ) );

			if ( ! empty( $item_settings['item_btn_url']['is_external'] ) ) {
				$this->add_render_attribute( 'button_' . $item_settings['_id'], 'target', '_blank' );
			}

			if ( ! empty( $item_settings['item_btn_url']['nofollow'] ) ) {
				$this->add_render_attribute( 'button_' . $item_settings['_id'], 'rel', 'nofollow' );
			}
		}

		$format = apply_filters( 'jet-elements/timeline/button-format', '<div class="timeline-item__card-btn-wrap"><a %2$s>%1$s</a></div>' );

		return sprintf(
			$format,
			$item_settings['item_btn_text'],
			$this->get_render_attribute_string( 'button_' . $item_settings['_id'] )
		);
	}

}