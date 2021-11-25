<?php
/**
 * Class: Jet_Elements_Table
 * Name: Table
 * Slug: jet-table
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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Table extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-table';
	}

	public function get_title() {
		return esc_html__( 'Table', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-table';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-create-a-table-with-jetelements-table-widget/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {

		if ( isset( $_GET['elementor-preview'] ) && 'wp_enqueue_scripts' === current_filter() ) {
			return array( 'jquery-tablesorter' );
		}

		if ( 'yes' === $this->get_settings( 'sorting_table' ) ) {
			return array( 'jquery-tablesorter' );
		}

		return array();
	}

	public function get_style_depends() {

		if ( isset( $_GET['elementor-preview'] ) && 'wp_enqueue_scripts' === current_filter() ) {
			return array( 'elementor-icons-fa-solid' );
		}

		if ( 'yes' === $this->get_settings( 'sorting_table' ) ) {
			return array( 'elementor-icons-fa-solid' );
		}

		return array();
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/table/css-scheme',
			array(
				'wrapper'                 => '.jet-table-wrapper',
				'table'                   => '.jet-table',
				'table_cell'              => '.jet-table__cell',
				'table_head'              => '.jet-table__head',
				'table_head_row'          => '.jet-table__head-row',
				'table_head_cell'         => '.jet-table__head-cell',
				'table_head_cell_inner'   => '.jet-table__head-cell .jet-table__cell-inner',
				'table_head_cell_content' => '.jet-table__head-cell .jet-table__cell-content',
				'table_head_icon'         => '.jet-table__head-cell .jet-table__cell-icon',
				'table_head_icon_before'  => '.jet-table__head-cell .jet-table__cell-icon--before',
				'table_head_icon_after'   => '.jet-table__head-cell .jet-table__cell-icon--after',
				'table_head_img'          => '.jet-table__head-cell .jet-table__cell-img',
				'table_head_img_before'   => '.jet-table__head-cell .jet-table__cell-img--before',
				'table_head_img_after'    => '.jet-table__head-cell .jet-table__cell-img--after',
				'table_foot'              => '.jet-table__foot',
				'table_foot_row'          => '.jet-table__foot-row',
				'table_foot_cell'         => '.jet-table__foot-cell',
				'table_foot_cell_inner'   => '.jet-table__foot-cell .jet-table__cell-inner',
				'table_foot_cell_content' => '.jet-table__foot-cell .jet-table__cell-content',
				'table_foot_icon'         => '.jet-table__foot-cell .jet-table__cell-icon',
				'table_foot_icon_before'  => '.jet-table__foot-cell .jet-table__cell-icon--before',
				'table_foot_icon_after'   => '.jet-table__foot-cell .jet-table__cell-icon--after',
				'table_foot_img'          => '.jet-table__foot-cell .jet-table__cell-img',
				'table_foot_img_before'   => '.jet-table__foot-cell .jet-table__cell-img--before',
				'table_foot_img_after'    => '.jet-table__foot-cell .jet-table__cell-img--after',
				'sorting_icon'            => '.jet-table__sort-icon',
				'table_body'              => '.jet-table__body',
				'table_body_row'          => '.jet-table__body-row',
				'table_body_cell'         => '.jet-table__body-cell',
				'table_body_cell_inner'   => '.jet-table__body-cell .jet-table__cell-inner',
				'table_body_cell_content' => '.jet-table__body-cell .jet-table__cell-content',
				'table_body_icon'         => '.jet-table__body-cell .jet-table__cell-icon',
				'table_body_icon_before'  => '.jet-table__body-cell .jet-table__cell-icon--before',
				'table_body_icon_after'   => '.jet-table__body-cell .jet-table__cell-icon--after',
				'table_body_img'          => '.jet-table__body-cell .jet-table__cell-img',
				'table_body_img_before'   => '.jet-table__body-cell .jet-table__cell-img--before',
				'table_body_img_after'    => '.jet-table__body-cell .jet-table__cell-img--after',
				'table_body_cell_link'    => '.jet-table__cell-link',
			)
		);

		/**
		 * `Table Header` Section
		 */
		$this->start_controls_section(
			'section_table_header',
			array(
				'label' => esc_html__( 'Table Header', 'jet-elements' ),
			)
		);

		$table_header_repeater = new Repeater();

		$table_header_repeater->start_controls_tabs( 'header_tabs' );

		$table_header_repeater->start_controls_tab(
			'header_tab_content',
			array(
				'label' => esc_html__( 'Content', 'jet-elements' ),
			)
		);

		$table_header_repeater->add_control(
			'cell_text',
			array(
				'label'   => esc_html__( 'Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$table_header_repeater->add_control(
			'add_icon_or_image',
			array(
				'label'   => esc_html__( 'Add icon/image', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''      => esc_html__( 'None', 'jet-elements' ),
					'icon'  => esc_html__( 'Icon', 'jet-elements' ),
					'image' => esc_html__( 'Image', 'jet-elements' ),
				),
			)
		);

		$this->_add_advanced_icon_control(
			'cell_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::ICON,
				'condition' => array(
					'add_icon_or_image' => 'icon',
				),
			),
			$table_header_repeater
		);

		$table_header_repeater->add_control(
			'cell_image',
			array(
				'label'     => esc_html__( 'Image', 'jet-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'add_icon_or_image' => 'image',
				),
			)
		);

		$table_header_repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name' => 'cell_image_size',
				'default' => 'thumbnail',
				'condition' => array(
					'add_icon_or_image' => 'image',
				),
			)
		);

		$table_header_repeater->add_control(
			'additional_elem_position',
			array(
				'label' => esc_html__( 'Position', 'jet-elements' ),
				'type'  => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => array(
					'before' => esc_html__( 'Before', 'jet-elements' ),
					'after'  => esc_html__( 'After', 'jet-elements' ),
				),
				'condition' => array(
					'add_icon_or_image!' => '',
				),
			)
		);

		$table_header_repeater->end_controls_tab();

		$table_header_repeater->start_controls_tab(
			'header_tab_advanced',
			array(
				'label' => esc_html__( 'Advanced', 'jet-elements' ),
			)
		);

		$table_header_repeater->add_control(
			'col_span',
			array(
				'label' => esc_html__( 'Column Span', 'jet-elements' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 1,
				'step'  => 1,
			)
		);

		$table_header_repeater->add_responsive_control(
			'col_width',
			array(
				'label' => esc_html__( 'Column Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' . $css_scheme['table_head_cell']  => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$table_header_repeater->end_controls_tab();

		$table_header_repeater->start_controls_tab(
			'header_tab_style',
			array(
				'label' => esc_html__( 'Style', 'jet-elements' ),
			)
		);

		$table_header_repeater->add_control(
			'cell_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' . $css_scheme['table_head_cell'] => 'color: {{VALUE}};',
				),
			)
		);

		$table_header_repeater->add_control(
			'cell_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' . $css_scheme['table_head_cell']  => 'background-color: {{VALUE}};',
				),
			)
		);

		$table_header_repeater->end_controls_tab();

		$table_header_repeater->end_controls_tabs();

		$this->add_control(
			'table_header',
			array(
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $table_header_repeater->get_controls(),
				'default' => array(
					array(
						'cell_text' => esc_html__( 'Heading #1', 'jet-elements' ),
					),
					array(
						'cell_text' => esc_html__( 'Heading #2', 'jet-elements' ),
					),
					array(
						'cell_text' => esc_html__( 'Heading #3', 'jet-elements' ),
					),
				),
				'title_field' => esc_html__( 'Column: ', 'jet-elements' ) . '{{ cell_text }}',
			)
		);

		$this->end_controls_section();

		/**
		 * `Table Body` Section
		 */
		$this->start_controls_section(
			'section_table_body',
			array(
				'label' => esc_html__( 'Table Body', 'jet-elements' ),
			)
		);

		$table_body_repeater = new Repeater();

		$table_body_repeater->add_control(
			'action',
			array(
				'label'   => esc_html__( 'Action', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'cell',
				'options' => array(
					'row'  => esc_html__( 'Start New Row', 'jet-elements' ),
					'cell' => esc_html__( 'Add New Cell', 'jet-elements' ),
				),
			)
		);

		$table_body_repeater->add_control(
			'row_custom_style',
			array(
				'label' => esc_html__( 'Add Custom Style', 'jet-elements' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => array(
					'action' => 'row',
				),
			)
		);

		$table_body_repeater->add_control(
			'row_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . '{{CURRENT_ITEM}} ' . $css_scheme['table_body_cell'] => 'color: {{VALUE}};',
				),
				'condition' => array(
					'action' => 'row',
					'row_custom_style' => 'yes',
				),
			)
		);

		$table_body_repeater->add_control(
			'row_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . '{{CURRENT_ITEM}} ' . $css_scheme['table_body_cell']  => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'action' => 'row',
					'row_custom_style' => 'yes',
				),
			)
		);

		$table_body_repeater->start_controls_tabs( 'body_tabs' );

		$table_body_repeater->start_controls_tab(
			'body_tab_content',
			array(
				'label' => esc_html__( 'Content', 'jet-elements' ),
				'condition' => array(
					'action' => 'cell',
				),
			)
		);

		$table_body_repeater->add_control(
			'cell_text',
			array(
				'label'     => esc_html__( 'Text', 'jet-elements' ),
				'type'      => Controls_Manager::TEXTAREA,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'action' => 'cell',
				),
			)
		);

		$table_body_repeater->add_control(
			'cell_link',
			array(
				'label'       => esc_html__( 'Link', 'jet-elements' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => esc_html__( 'https://your-link.com', 'jet-elements' ),
				'condition'   => array(
					'action' => 'cell',
				),
			)
		);

		$table_body_repeater->add_control(
			'add_icon_or_image',
			array(
				'label'   => esc_html__( 'Add icon/image', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''      => esc_html__( 'None', 'jet-elements' ),
					'icon'  => esc_html__( 'Icon', 'jet-elements' ),
					'image' => esc_html__( 'Image', 'jet-elements' ),
				),
				'condition' => array(
					'action' => 'cell',
				),
			)
		);

		$this->_add_advanced_icon_control(
			'cell_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::ICON,
				'condition' => array(
					'action' => 'cell',
					'add_icon_or_image' => 'icon',
				),
			),
			$table_body_repeater
		);

		$table_body_repeater->add_control(
			'cell_image',
			array(
				'label'     => esc_html__( 'Image', 'jet-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'action' => 'cell',
					'add_icon_or_image' => 'image',
				),
			)
		);

		$table_body_repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name' => 'cell_image_size',
				'default' => 'thumbnail',
				'condition' => array(
					'action' => 'cell',
					'add_icon_or_image' => 'image',
				),
			)
		);

		$table_body_repeater->add_control(
			'additional_elem_position',
			array(
				'label' => esc_html__( 'Position', 'jet-elements' ),
				'type'  => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => array(
					'before' => esc_html__( 'Before', 'jet-elements' ),
					'after'  => esc_html__( 'After', 'jet-elements' ),
				),
				'condition' => array(
					'action' => 'cell',
					'add_icon_or_image!' => '',
				),
			)
		);

		$table_body_repeater->end_controls_tab();

		$table_body_repeater->start_controls_tab(
			'body_tab_advanced',
			array(
				'label' => esc_html__( 'Advanced', 'jet-elements' ),
				'condition' => array(
					'action' => 'cell',
				),
			)
		);

		$table_body_repeater->add_control(
			'col_span',
			array(
				'label' => esc_html__( 'Column Span', 'jet-elements' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 1,
				'step'  => 1,
				'condition' => array(
					'action' => 'cell',
				),
			)
		);

		$table_body_repeater->add_control(
			'row_span',
			array(
				'label' => esc_html__( 'Row Span', 'jet-elements' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 1,
				'step'  => 1,
				'condition' => array(
					'action' => 'cell',
				),
			)
		);

		$table_body_repeater->add_control(
			'cell_is_th',
			array(
				'label' => esc_html__( 'This cell is Table Heading?', 'jet-elements' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => array(
					'action' => 'cell',
				),
			)
		);

		$table_body_repeater->add_control(
			'cell_is_th_desc',
			array(
				'raw'  => esc_html__( 'For this cell are applied table heading cell style', 'jet-elements' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition' => array(
					'action' => 'cell',
					'cell_is_th' => 'yes',
				),
			)
		);

		$table_body_repeater->end_controls_tab();

		$table_body_repeater->start_controls_tab(
			'body_tab_style',
			array(
				'label' => esc_html__( 'Style', 'jet-elements' ),
				'condition' => array(
					'action' => 'cell',
				),
			)
		);

		$table_body_repeater->add_control(
			'cell_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_cell'] . '{{CURRENT_ITEM}}' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'action' => 'cell',
				),
			)
		);

		$table_body_repeater->add_control(
			'cell_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_cell'] . '{{CURRENT_ITEM}}'  => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'action' => 'cell',
				),
			)
		);

		$table_body_repeater->end_controls_tab();

		$table_body_repeater->end_controls_tabs();

		$this->add_control(
			'table_body',
			array(
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $table_body_repeater->get_controls(),
				'default' => array(
					array(
						'action' => 'row',
					),
					array(
						'action'    => 'cell',
						'cell_text' => esc_html__( 'Simple content', 'jet-elements' ),
					),
					array(
						'action'    => 'cell',
						'cell_text' => esc_html__( 'Simple content', 'jet-elements' ),
					),
					array(
						'action'    => 'cell',
						'cell_text' => esc_html__( 'Simple content', 'jet-elements' ),
					),
				),
				'title_field' => '{{ action === "row" ? "' . esc_html__( 'Start Row:', 'jet-elements' ) . '" : "' . esc_html__( 'Cell:', 'jet-elements' ) . ' " + cell_text }}',
			)
		);

		$this->end_controls_section();

		/**
		 * `Table Footer` Section
		 */
		$this->start_controls_section(
			'section_table_footer',
			array(
				'label' => esc_html__( 'Table Footer', 'jet-elements' ),
			)
		);

		$table_footer_repeater = new Repeater();

		$table_footer_repeater->start_controls_tabs( 'footer_tabs' );

		$table_footer_repeater->start_controls_tab(
			'footer_tab_content',
			array(
				'label' => esc_html__( 'Content', 'jet-elements' ),
			)
		);

		$table_footer_repeater->add_control(
			'cell_text',
			array(
				'label'   => esc_html__( 'Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$table_footer_repeater->add_control(
			'add_icon_or_image',
			array(
				'label'   => esc_html__( 'Add icon/image', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''      => esc_html__( 'None', 'jet-elements' ),
					'icon'  => esc_html__( 'Icon', 'jet-elements' ),
					'image' => esc_html__( 'Image', 'jet-elements' ),
				),
			)
		);

		$this->_add_advanced_icon_control(
			'cell_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::ICON,
				'condition' => array(
					'add_icon_or_image' => 'icon',
				),
			),
			$table_footer_repeater
		);

		$table_footer_repeater->add_control(
			'cell_image',
			array(
				'label'     => esc_html__( 'Image', 'jet-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'add_icon_or_image' => 'image',
				),
			)
		);

		$table_footer_repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name' => 'cell_image_size',
				'default' => 'thumbnail',
				'condition' => array(
					'add_icon_or_image' => 'image',
				),
			)
		);

		$table_footer_repeater->add_control(
			'additional_elem_position',
			array(
				'label' => esc_html__( 'Position', 'jet-elements' ),
				'type'  => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => array(
					'before' => esc_html__( 'Before', 'jet-elements' ),
					'after'  => esc_html__( 'After', 'jet-elements' ),
				),
				'condition' => array(
					'add_icon_or_image!' => '',
				),
			)
		);

		$table_footer_repeater->end_controls_tab();

		$table_footer_repeater->start_controls_tab(
			'footer_tab_advanced',
			array(
				'label' => esc_html__( 'Advanced', 'jet-elements' ),
			)
		);

		$table_footer_repeater->add_control(
			'col_span',
			array(
				'label' => esc_html__( 'Column Span', 'jet-elements' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 1,
				'step'  => 1,
			)
		);

		$table_footer_repeater->add_responsive_control(
			'col_width',
			array(
				'label' => esc_html__( 'Column Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' . $css_scheme['table_foot_cell']  => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$table_footer_repeater->end_controls_tab();

		$table_footer_repeater->start_controls_tab(
			'footer_tab_style',
			array(
				'label' => esc_html__( 'Style', 'jet-elements' ),
			)
		);

		$table_footer_repeater->add_control(
			'cell_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' . $css_scheme['table_foot_cell'] => 'color: {{VALUE}};',
				),
			)
		);

		$table_footer_repeater->add_control(
			'cell_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' . $css_scheme['table_foot_cell']  => 'background-color: {{VALUE}};',
				),
			)
		);

		$table_footer_repeater->end_controls_tab();

		$table_footer_repeater->end_controls_tabs();

		$this->add_control(
			'table_footer',
			array(
				'type'   		=> Controls_Manager::REPEATER,
				'fields'  		=> $table_footer_repeater->get_controls(),
				'prevent_empty' => false,
				'title_field'   => esc_html__( 'Column: ', 'jet-elements' ) . '{{ cell_text }}',
			)
		);

		$this->end_controls_section();

		/**
		 * `Settings` Section
		 */
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		$this->add_control(
			'sorting_table',
			array(
				'label'   => esc_html__( 'Sorting Table', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'responsive_table',
			array(
				'label'   => esc_html__( 'Responsive Table', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'responsive_table_desc',
			array(
				'raw'  => esc_html__( 'Responsive table allow table to be scrolled horizontally.', 'jet-elements' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'condition'   => array(
					'responsive_table' => 'yes',
				),
			)
		);

		$this->add_control(
			'responsive_table_on',
			array(
				'label'       => esc_html__( 'Responsive On', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'default'     => array( 'mobile' ),
				'options'     => array(
					'mobile'  => esc_html__( 'Mobile', 'jet-elements' ),
					'tablet'  => esc_html__( 'Tablet', 'jet-elements' ),
					'desktop' => esc_html__( 'Desktop', 'jet-elements' ),
				),
				'condition'   => array(
					'responsive_table' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `General` Style Section
		 */
		$this->_start_controls_section(
			'section_general_style',
			array(
				'label' => esc_html__( 'General', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->_add_responsive_control(
			'table_width',
			array(
				'label' => esc_html__( 'Table Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 1200,
					),
				),
				'default' => array(
					'unit' => '%',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['wrapper'] => 'max-width: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_control(
			'table_column_width',
			array(
				'label' => esc_html__( 'Column Width', 'jet-elements' ),
				'type'  => Controls_Manager::SELECT,
				'options' => array(
					'auto'  => esc_html__( 'Auto', 'jet-elements' ),
					'fixed' => esc_html__( 'Fixed (Equal width)', 'jet-elements' ),
				),
				'default' => 'auto',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table'] => 'table-layout: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'table_align',
			array(
				'label' => esc_html__( 'Table Alignment', 'jet-elements' ),
				'type'  => Controls_Manager::CHOOSE,
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
				),
				'selectors_dictionary' => array(
					'left'   => 'margin-left: 0; margin-right: auto;',
					'center' => 'margin-left: auto; margin-right: auto;',
					'right'  => 'margin-left: auto; margin-right: 0;',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['wrapper'] => '{{VALUE}}',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->_add_control(
			'table_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					//'{{WRAPPER}} ' . $css_scheme['table'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['table_head_row'] . ':first-child ' . $css_scheme['table_cell'] . ':first-child' => ! is_rtl() ? 'border-top-left-radius: {{TOP}}{{UNIT}};' : 'border-top-right-radius: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['table_head_row'] . ':first-child ' . $css_scheme['table_cell'] . ':last-child' => ! is_rtl() ? 'border-top-right-radius: {{RIGHT}}{{UNIT}};' : 'border-top-left-radius: {{TOP}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ':last-child ' . $css_scheme['table_cell'] . ':last-child' => ! is_rtl() ? 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}};' : 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ':last-child ' . $css_scheme['table_cell'] . ':first-child' => ! is_rtl() ? 'border-bottom-left-radius: {{LEFT}}{{UNIT}};' : 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['table_foot_row'] . ':first-child ' . $css_scheme['table_cell'] . ':first-child' => ! is_rtl() ? 'border-bottom-left-radius: {{LEFT}}{{UNIT}};' : 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['table_foot_row'] . ':first-child ' . $css_scheme['table_cell'] . ':last-child' => ! is_rtl() ? 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}};' : 'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .jet-table__body_no-border-radius ' . $css_scheme['table_body_row'] . ' > ' . $css_scheme['table_cell'] => 'border-radius: 0 !important;',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'table_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['wrapper'],
				'exclude'  => array(
					'box_shadow_position',
				),
			),
			100
		);

		$this->_end_controls_section();


		/**
		 * `Table Header and Footer` Style Section
		 */


		$this->_start_controls_section(
			'section_table_header_footer_style',
			array(
				'label' => esc_html__( 'Table Header and Footer', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->_add_responsive_control(
			'table_head_cell_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_cell_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['table_foot_cell_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'table_head_cell_border',
				'fields_options' => array(
					'border' => array(
						'selectors' => array(
							'{{WRAPPER}} ' . $css_scheme['table_head_cell'] => 'border-style: {{VALUE}};',
							'{{WRAPPER}} ' . $css_scheme['table_foot_cell'] => 'border-style: {{VALUE}};',
						),
					),
					'width' => array(
						'selectors' => array(
							'{{WRAPPER}} ' . $css_scheme['table_head_cell'] => ! is_rtl() ? 'border-width: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' : 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
							'{{WRAPPER}} ' . $css_scheme['table_head_row'] . ':first-child ' . $css_scheme['table_head_cell'] . ':first-child' => ! is_rtl() ? 'border-width: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' : 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
							'{{WRAPPER}} ' . $css_scheme['table_head_row'] . ':first-child ' . $css_scheme['table_head_cell'] . ':last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} ' . $css_scheme['table_foot_cell'] => ! is_rtl() ? 'border-width: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' : 'border-width: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
							'{{WRAPPER}} ' . $css_scheme['table_foot_row'] . ':first-child ' . $css_scheme['table_foot_cell'] . ':first-child' => ! is_rtl() ? 'border-width: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' : 'border-width: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
							'{{WRAPPER}} ' . $css_scheme['table_foot_row'] . ':first-child ' . $css_scheme['table_foot_cell'] . ':last-child' => 'border-width: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					),
					'color' => array(
						'selectors' => array(
							'{{WRAPPER}} ' . $css_scheme['table_head_cell'] => 'border-color: {{VALUE}};',
							'{{WRAPPER}} ' . $css_scheme['table_foot_cell'] => 'border-color: {{VALUE}};',
						),
					),
				),
			),
			50
		);

		$this->_add_control(
			'table_head_hidden_border',
			array(
				'label' => esc_html__( 'Hidden border for header and footer container', 'jet-elements' ),
				'type'  => Controls_Manager::SWITCHER,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_cell'] => 'border-top-width: 0;border-bottom-width: 0;',
					'{{WRAPPER}} ' . $css_scheme['table_head_row'] . ':first-child ' . $css_scheme['table_head_cell'] . ':first-child' => ! is_rtl() ? 'border-top-width: 0;border-bottom-width: 0;border-left-width: 0;' : 'border-top-width: 0;border-bottom-width: 0;border-right-width: 0;',
					'{{WRAPPER}} ' . $css_scheme['table_head_row'] . ':first-child ' . $css_scheme['table_head_cell'] . ':last-child' => ! is_rtl() ? 'border-top-width: 0;border-bottom-width: 0;border-right-width: 0;' : 'border-top-width: 0;border-bottom-width: 0;border-left-width: 0;',
					'{{WRAPPER}} ' . $css_scheme['table_foot_cell'] => 'border-bottom-width: 0;',
					'{{WRAPPER}} ' . $css_scheme['table_foot_row'] . ':first-child ' . $css_scheme['table_foot_cell'] . ':first-child' => ! is_rtl() ? 'border-bottom-width: 0;border-left-width: 0;' : 'border-bottom-width: 0;border-right-width: 0;',
					'{{WRAPPER}} ' . $css_scheme['table_foot_row'] . ':first-child ' . $css_scheme['table_foot_cell'] . ':last-child' => ! is_rtl() ? 'border-right-width: 0;border-bottom-width: 0;' : 'border-left-width: 0;border-bottom-width: 0;',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'table_head_cell_align',
			array(
				'label' => esc_html__( 'Alignment', 'jet-elements' ),
				'type'  => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
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
				'selectors_dictionary' => array(
					'left'   => 'margin-left: 0; margin-right: auto; text-align: left;',
					'center' => 'margin-left: auto; margin-right: auto; text-align: center;',
					'right'  => 'margin-left: auto; margin-right: 0; text-align: right;',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_cell_content'] => '{{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['table_foot_cell_content'] => '{{VALUE}}',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			75
		);

		$this->_add_responsive_control(
			'table_head_cell_vert_align',
			array(
				'label'   => esc_html__( 'Vertical Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'top' => array(
						'title' => esc_html__( 'Top', 'jet-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'jet-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom'  => array(
						'title' => esc_html__( 'Bottom', 'jet-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),

				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_cell'] => 'vertical-align: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['table_foot_cell'] => 'vertical-align: {{VALUE}};',
				),
			),
			75
		);

		$this->_add_control(
			'sorting_icon_style',
			array(
				'label' => '<b>' . esc_html__( 'Sorting Icon', 'jet-elements' ) . '</b>',
				'type'  => Controls_Manager::POPOVER_TOGGLE,
				'separator' => 'before',
				'condition' => array(
					'sorting_table' => 'yes',
				),
			),
			25
		);

		$this->_start_popover();

		$this->_add_responsive_control(
			'sorting_icon_size',
			array(
				'label' => esc_html__( 'Font Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sorting_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'sorting_icon_style' => 'yes',
				),
			),
			25
		);
		
		$this->_add_control(
			'sorting_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['sorting_icon'] => 'color: {{VALUE}};',
				),
				'condition' => array(
					'sorting_icon_style' => 'yes',
				),
			),
			25
		);

		$this->_end_popover();

		$this->_end_controls_section();


		/**
		 * `Table Header` Style Section
		 */
		$this->_start_controls_section(
			'section_table_header_style',
			array(
				'label' => esc_html__( 'Table Header', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_head_typography',
				'selector' => '{{WRAPPER}} ' .$css_scheme['table_head_cell'],
			),
			50
		);

		$this->_start_controls_tabs( 'table_head_tabs' );

		$this->_start_controls_tab(
			'table_head_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'table_head_cell_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_cell'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'table_head_cell_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_cell'] => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'table_head_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'table_head_cell_color_hover',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_cell'] . ':hover' => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'table_head_cell_bg_color_hover',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_cell'] . ':hover' => 'background-color: {{VALUE}};',
				),
			),
			25
		);



		$this->_end_controls_tab();

		$this->_add_control(
			'table_head_cell_icon_style',
			array(
				'label' => '<b>' . esc_html__( 'Icon', 'jet-elements' ) . '</b>',
				'type'  => Controls_Manager::POPOVER_TOGGLE,
				'separator' => 'before',
			),
			25
		);

		$this->_start_popover();

		$this->_add_responsive_control(
			'table_head_cell_icon_size',
			array(
				'label' => esc_html__( 'Font Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_head_cell_icon_style' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_head_cell_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_icon'] => 'color: {{VALUE}};',
				),
				'condition' => array(
					'table_head_cell_icon_style' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_head_cell_icon_gap',
			array(
				'label' => esc_html__( 'Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_head_icon_before'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_head_icon_before'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_head_icon_after'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_head_icon_after'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_head_cell_icon_style' => 'yes',
				),
			),
			25
		);

		$this->_end_popover();

		$this->_add_control(
			'table_head_cell_img_style',
			array(
				'label' => '<b>' . esc_html__( 'Image', 'jet-elements' ) . '</b>',
				'type'  => Controls_Manager::POPOVER_TOGGLE,
				'separator' => 'before',
			),
			25
		);

		$this->_start_popover();

		$this->_add_responsive_control(
			'table_head_cell_img_width',
			array(
				'label' => esc_html__( 'Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_img'] . ' img' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_head_cell_img_style' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_head_cell_img_gap',
			array(
				'label' => esc_html__( 'Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_head_img_before'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_head_img_before'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_head_img_after'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_head_img_after'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_head_cell_img_style' => 'yes',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'table_head_cell_img_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['table_head_img'] . ' img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'table_head_cell_img_style' => 'yes',
				),
			),
			25
		);

		$this->_end_popover();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * `Table Footer` Style Section
		 */
		$this->_start_controls_section(
			'section_table_foot_style',
			array(
				'label' => esc_html__( 'Table Footer', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_foot_typography',
				'selector' => '{{WRAPPER}} ' .$css_scheme['table_foot_cell'],
			),
			50
		);

		$this->_start_controls_tabs( 'table_foot_tabs' );

		$this->_start_controls_tab(
			'table_foot_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'table_foot_cell_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_foot_cell'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'table_foot_cell_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_foot_cell'] => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'table_foot_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'table_foot_cell_color_hover',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_foot_cell'] . ':hover' => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'table_foot_cell_bg_color_hover',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_foot_cell'] . ':hover' => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_add_control(
			'table_foot_cell_icon_style',
			array(
				'label' => '<b>' . esc_html__( 'Icon', 'jet-elements' ) . '</b>',
				'type'  => Controls_Manager::POPOVER_TOGGLE,
				'separator' => 'before',
			),
			25
		);

		$this->_start_popover();

		$this->_add_responsive_control(
			'table_foot_cell_icon_size',
			array(
				'label' => esc_html__( 'Font Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_foot_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_foot_cell_icon_style' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_foot_cell_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_foot_icon'] => 'color: {{VALUE}};',
				),
				'condition' => array(
					'table_foot_cell_icon_style' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_foot_cell_icon_gap',
			array(
				'label' => esc_html__( 'Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_foot_icon_before'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_foot_icon_before'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_foot_icon_after'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_foot_icon_after'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_foot_cell_icon_style' => 'yes',
				),
			),
			25
		);

		$this->_end_popover();

		$this->_add_control(
			'table_foot_cell_img_style',
			array(
				'label' => '<b>' . esc_html__( 'Image', 'jet-elements' ) . '</b>',
				'type'  => Controls_Manager::POPOVER_TOGGLE,
				'separator' => 'before',
			),
			25
		);

		$this->_start_popover();

		$this->_add_responsive_control(
			'table_foot_cell_img_width',
			array(
				'label' => esc_html__( 'Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_foot_img'] . ' img' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_foot_cell_img_style' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_foot_cell_img_gap',
			array(
				'label' => esc_html__( 'Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_foot_img_before'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_foot_img_before'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_foot_img_after'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_foot_img_after'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_foot_cell_img_style' => 'yes',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'table_foot_cell_img_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['table_foot_img'] . ' img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'table_foot_cell_img_style' => 'yes',
				),
			),
			25
		);

		$this->_end_popover();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * `Table Body` Style Section
		 */
		$this->_start_controls_section(
			'section_table_body_style',
			array(
				'label' => esc_html__( 'Table Body', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_body_typography',
				'selector' => '{{WRAPPER}} ' .$css_scheme['table_body_cell'],
			),
			50
		);

		$this->_start_controls_tabs( 'table_body_tabs' );

		$this->_start_controls_tab(
			'table_body_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'table_body_row_color',
			array(
				'label' => esc_html__( 'Row Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_cell'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_row_bg_color',
			array(
				'label' => esc_html__( 'Row Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_cell'] => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_striped_row',
			array(
				'label' => esc_html__( 'Striped rows', 'jet-elements' ),
				'type'  => Controls_Manager::SWITCHER,
			),
			25
		);

		$this->_add_control(
			'table_body_even_row_color',
			array(
				'label' => esc_html__( 'Even Row Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} tr:nth-child(even) ' . $css_scheme['table_body_cell'] => 'color: {{VALUE}};',
				),
				'condition' => array(
					'table_body_striped_row' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_even_row_bg_color',
			array(
				'label' => esc_html__( 'Even Row Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} tr:nth-child(even) ' . $css_scheme['table_body_cell'] => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'table_body_striped_row' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_link_color',
			array(
				'label' => esc_html__( 'Link Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_cell_link'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'table_body_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'table_body_row_color_hover',
			array(
				'label' => esc_html__( 'Row Hover Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ':hover ' . $css_scheme['table_body_cell'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_row_bg_color_hover',
			array(
				'label' => esc_html__( 'Row Hover Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ':hover '  . $css_scheme['table_body_cell'] => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_even_row_color_hover',
			array(
				'label' => esc_html__( 'Even Row Hover Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ':nth-child(even):hover ' . $css_scheme['table_body_cell'] => 'color: {{VALUE}};',
				),
				'condition' => array(
					'table_body_striped_row' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_even_row_bg_color_hover',
			array(
				'label' => esc_html__( 'Even Row Hover Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ':nth-child(even):hover ' . $css_scheme['table_body_cell'] => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'table_body_striped_row' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_cell_color_hover',
			array(
				'label' => esc_html__( 'Cell Hover Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ':hover ' . $css_scheme['table_body_cell'] . ':hover' => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_cell_bg_color_hover',
			array(
				'label' => esc_html__( 'Cell Hover Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ':hover ' . $css_scheme['table_body_cell'] . ':hover' => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_link_color_hover',
			array(
				'label' => esc_html__( 'Link Hover Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_cell_link'] . ':hover' => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_responsive_control(
			'table_body_cell_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_cell_inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'table_body_cell_border',
				'fields_options' => array(
					'border' => array(
						'selectors' => array(
							'{{WRAPPER}} ' . $css_scheme['table_body_cell'] => 'border-style: {{VALUE}};',
						),
					),
					'width' => array(
						'selectors' => array(
							'{{WRAPPER}} ' . $css_scheme['table_body_cell'] => ! is_rtl() ? 'border-width: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' : 'border-width: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
							'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_body_cell'] . ':first-child' => ! is_rtl() ? 'border-width: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' : 'border-width: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
							'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_body_cell'] . ':last-child' => 'border-width: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' + ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_body_cell']  => ! is_rtl() ? 'border-width: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' : 'border-width: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
							'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' + ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_body_cell'] . ':first-child' => ! is_rtl() ? 'border-width: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' : 'border-width: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
							'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' + ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_body_cell'] . ':last-child' => 'border-width: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					),
					'color' => array(
						'selectors' => array(
							'{{WRAPPER}} ' . $css_scheme['table_body_cell'] => 'border-color: {{VALUE}};',
						),
					),
				),
			),
			50
		);

		$this->_add_control(
			'table_body_hidden_border',
			array(
				'label' => esc_html__( 'Hidden border for body container', 'jet-elements' ),
				'type'  => Controls_Manager::SWITCHER,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_cell'] => 'border-bottom-width: 0;',
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_body_cell'] . ':first-child' => ! is_rtl() ? 'border-bottom-width: 0;border-left-width: 0;' : 'border-bottom-width: 0;border-right-width: 0;',
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_body_cell'] . ':last-child' => ! is_rtl() ? 'border-right-width: 0;border-bottom-width: 0;' : 'border-left-width: 0;border-bottom-width: 0;',
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' + ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_body_cell']  => 'border-bottom-width: 0;',
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' + ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_body_cell'] . ':first-child' => ! is_rtl() ? 'border-bottom-width: 0;border-left-width: 0;' : 'border-bottom-width: 0;border-right-width: 0;',
					'{{WRAPPER}} ' . $css_scheme['table_body_row'] . ' + ' . $css_scheme['table_body_row'] . ' ' . $css_scheme['table_body_cell'] . ':last-child' => ! is_rtl() ? 'border-right-width: 0;border-bottom-width: 0;' : 'border-left-width: 0;border-bottom-width: 0;',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'table_body_cell_align',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
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
				'selectors_dictionary' => array(
					'left'   => 'margin-left: 0; margin-right: auto; text-align: left;',
					'center' => 'margin-left: auto; margin-right: auto; text-align: center;',
					'right'  => 'margin-left: auto; margin-right: 0; text-align: right;',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_cell_content'] => '{{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			75
		);

		$this->_add_responsive_control(
			'table_body_cell_vert_align',
			array(
				'label'   => esc_html__( 'Vertical Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'top' => array(
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
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_cell'] => 'vertical-align: {{VALUE}};',
				),
			),
			75
		);

		$this->_add_control(
			'table_body_cell_icon_style',
			array(
				'label' => '<b>' . esc_html__( 'Icon', 'jet-elements' ) . '</b>',
				'type'  => Controls_Manager::POPOVER_TOGGLE,
				'separator' => 'before',
			),
			25
		);

		$this->start_popover();

		$this->_add_responsive_control(
			'table_body_cell_icon_size',
			array(
				'label' => esc_html__( 'Font Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem',
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_body_cell_icon_style' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_cell_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_icon'] => 'color: {{VALUE}};',
				),
				'condition' => array(
					'table_body_cell_icon_style' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_cell_icon_gap',
			array(
				'label' => esc_html__( 'Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_body_icon_before'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_body_icon_before'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_body_icon_after'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_body_icon_after'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_body_cell_icon_style' => 'yes',
				),
			),
			25
		);

		$this->_end_popover();

		$this->_add_control(
			'table_body_cell_img_style',
			array(
				'label' => '<b>' . esc_html__( 'Image', 'jet-elements' ) . '</b>',
				'type'  => Controls_Manager::POPOVER_TOGGLE,
				'separator' => 'before',
			),
			25
		);

		$this->_start_popover();

		$this->_add_responsive_control(
			'table_body_cell_img_width',
			array(
				'label' => esc_html__( 'Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_img'] . ' img' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_body_cell_img_style' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'table_body_cell_img_gap',
			array(
				'label' => esc_html__( 'Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_body_img_before'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_body_img_before'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body:not(.rtl) {{WRAPPER}} ' . $css_scheme['table_body_img_after'] . ':not(:only-child)' => 'margin-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} ' . $css_scheme['table_body_img_after'] . ':not(:only-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'table_body_cell_img_style' => 'yes',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'table_body_cell_img_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['table_body_img'] . ' img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'table_body_cell_img_style' => 'yes',
				),
			),
			25
		);

		$this->_end_popover();

		$this->_end_controls_section();
	}

	protected function render() {
		$this->_context = 'render';
		$this->_open_wrap();

		$settings = $this->get_settings_for_display();

		$table_head = $settings['table_header'];
		$table_body = $settings['table_body'];
		$table_footer = $settings['table_footer'];

		$this->add_render_attribute( 'wrapper', 'class', 'jet-table-wrapper' );

		if ( isset( $settings['responsive_table'] ) && filter_var( $settings['responsive_table'], FILTER_VALIDATE_BOOLEAN ) && ! empty( $settings['responsive_table_on'] ) ) {
			foreach ( $settings['responsive_table_on'] as $device_type ) {
				$this->add_render_attribute( 'wrapper', 'class', 'jet-table-responsive-' . $device_type );
			}
		}

		$this->add_render_attribute( 'table', 'class', 'jet-table' );

		$sorting = ( isset( $settings['sorting_table'] ) && filter_var( $settings['sorting_table'], FILTER_VALIDATE_BOOLEAN ) ) ? true : false;

		if ( $sorting ) {
			$this->add_render_attribute( 'table', 'class', 'jet-table--sorting' );
		}

		if ( jet_elements_tools()->is_fa5_migration() ) {
			$this->add_render_attribute( 'table', 'class', 'jet-table--fa5-compat' );
		}
		?>

		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<table <?php echo $this->get_render_attribute_string( 'table' ); ?>>
				<thead class="jet-table__head"><?php echo $this->get_table_cells( $table_head, 'head' ); ?></thead>
				<?php if ( ! empty( $table_footer ) ): ?>
				<tfoot class="jet-table__foot"><?php echo $this->get_table_cells( $table_footer, 'foot' ); ?></tfoot>
				<?php endif; ?>
				<tbody class="jet-table__body<?php if ( ! empty( $table_footer ) ): ?> jet-table__body_no-border-radius<?php endif; ?>"><?php echo $this->get_table_cells( $table_body, 'body' ); ?></tbody>
			</table>
		</div>

		<?php
		$this->_close_wrap();
	}

	/**
	 * Get table cells html.
	 *
	 * @param array  $data    Cells data.
	 * @param string $context Context: 'head' or 'body'.
	 *
	 * @return string
	 */
	public function get_table_cells( $data = array(), $context = 'head' ) {
		$settings = $this->get_settings_for_display();
		$sorting = ( isset( $settings['sorting_table'] ) && filter_var( $settings['sorting_table'], FILTER_VALIDATE_BOOLEAN ) ) ? true : false;

		$html = '';

		$is_first_row = true;

		if ( 'head' === $context ) {
			$html .= '<tr class="jet-table__head-row">';
		}

		if ( 'foot' === $context ) {
			$html .= '<tr class="jet-table__foot-row">';
		}

		foreach ( $data as $index => $item ) {
			$this->_processed_item = $item;

			if ( isset( $item['action'] ) && 'row' === $item['action'] ) {
				// Render row html
				if ( $is_first_row ) {
					$html .= sprintf( '<tr class="jet-table__body-row elementor-repeater-item-%s">', esc_attr( $item['_id'] ) );
					$is_first_row = false;
				} else {
					$html .= sprintf( '</tr><tr class="jet-table__body-row elementor-repeater-item-%s">', esc_attr( $item['_id'] ) );
				}
			} else {
				// Render cell html
				$additional_content = '';
				$additional_element = isset( $item['add_icon_or_image'] ) ? $item['add_icon_or_image'] : '';
				$position = isset( $item['additional_elem_position'] ) ? $item['additional_elem_position'] : 'before';

				if ( 'icon' === $additional_element ) {
					$icon_format        = '<span class="jet-elements-icon jet-table__cell-icon jet-table__cell-icon--' . esc_attr( $position ) . '">%s</span>';
					$additional_content = $this->_get_icon( 'cell_icon', $icon_format );
				}

				if ( 'image' === $additional_element && ! empty ( $item['cell_image']['url'] ) ) {
					$image_html = Group_Control_Image_Size::get_attachment_image_html( $item, 'cell_image_size', 'cell_image' );

					$additional_content = sprintf( '<div class="jet-table__cell-img jet-table__cell-img--%2$s">%1$s</div>', $image_html, esc_attr( $position ) );
				}

				$cell_text = ( ! empty( $item['cell_text'] ) || '0' === $item['cell_text'] ) ? sprintf( '<div class="jet-table__cell-text">%s</div>', $this->parse_text_editor( $item['cell_text'] ) ) : '';

				$cell_content = sprintf( '<div class="jet-table__cell-content">%1$s%2$s</div>', $additional_content, $cell_text );

				$this->add_render_attribute( 'cell-' . $item['_id'], 'class', 'jet-table__cell' );
				$this->add_render_attribute( 'cell-' . $item['_id'], 'class', sprintf( 'elementor-repeater-item-%s', esc_attr( $item['_id'] ) ) );

				if ( ! empty( $item['col_span'] ) ) {
					$this->add_render_attribute( 'cell-' . $item['_id'], 'colspan', esc_attr( $item['col_span'] ) );
				}

				if ( ! empty( $item['row_span'] ) ) {
					$this->add_render_attribute( 'cell-' . $item['_id'], 'rowspan', esc_attr( $item['row_span'] ) );
				}

				if ( 'head' === $context ) {
					// Render cells in the thead tag
					$this->add_render_attribute( 'cell-' . $item['_id'], 'class', 'jet-table__head-cell' );
					$this->add_render_attribute( 'cell-' . $item['_id'], 'scope', 'col' );

					$sorting_icon = $sorting ? '<i class="jet-table__sort-icon"></i>' : '';

					$html .= sprintf( '<th %3$s><div class="jet-table__cell-inner">%1$s%2$s</div></th>', $cell_content, $sorting_icon, $this->get_render_attribute_string( 'cell-' . $item['_id'] ) );
				} else if ( 'foot' === $context ) {
					// Render cells in the thead tag
					$this->add_render_attribute( 'cell-' . $item['_id'], 'class', 'jet-table__foot-cell' );
					$this->add_render_attribute( 'cell-' . $item['_id'], 'scope', 'col' );

					$html .= sprintf( '<th %2$s><div class="jet-table__cell-inner">%1$s</div></th>', $cell_content, $this->get_render_attribute_string( 'cell-' . $item['_id'] ) );
				} else {
					// Render cells in the tbody tag
					$cell_tag = ( isset( $item['cell_is_th'] ) && filter_var( $item['cell_is_th'], FILTER_VALIDATE_BOOLEAN ) ) ? 'th' : 'td';

					if ( 'th' === $cell_tag ) {
						$this->add_render_attribute( 'cell-' . $item['_id'], 'class', 'jet-table__head-cell' );
						$this->add_render_attribute( 'cell-' . $item['_id'], 'scope', 'row' );
					} else {
						$this->add_render_attribute( 'cell-' . $item['_id'], 'class', 'jet-table__body-cell' );
					}

					$cell_inner_tag = 'div';
					$this->add_render_attribute( 'cell-inner-' . $item['_id'], 'class', 'jet-table__cell-inner' );

					if ( ! empty( $item['cell_link']['url'] ) ) {
						$cell_inner_tag = 'a';
						$this->add_render_attribute( 'cell-inner-' . $item['_id'], 'class', 'jet-table__cell-link' );

						if ( method_exists( $this, 'add_link_attributes' ) ) {
							$this->add_link_attributes( 'cell-inner-' . $item['_id'], $item['cell_link'] );
						} else {
							$this->add_render_attribute( 'cell-inner-' . $item['_id'], 'href', esc_url( $item['cell_link']['url'] ) );

							if ( $item['cell_link']['is_external'] ) {
								$this->add_render_attribute( 'cell-inner-' . $item['_id'], 'target', '_blank' );
							}

							if ( $item['cell_link']['nofollow'] ) {
								$this->add_render_attribute( 'cell-inner-' . $item['_id'], 'rel', 'nofollow' );
							}
						}
					}

					$html .= sprintf( '<%2$s %3$s><%4$s %5$s>%1$s</%4$s></%2$s>',
						$cell_content,
						$cell_tag,
						$this->get_render_attribute_string( 'cell-' . $item['_id'] ),
						$cell_inner_tag,
						$this->get_render_attribute_string( 'cell-inner-' . $item['_id'] )
					);
				}
			}
		}

		$this->_processed_item = false;

		$html .= '</tr>';

		return $html;
	}
}
