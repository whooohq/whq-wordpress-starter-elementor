<?php
/**
 * Class: Jet_Elements_Pricing_Table
 * Name: Pricing Table
 * Slug: jet-pricing-table
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
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Pricing_Table extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-pricing-table';
	}

	public function get_title() {
		return esc_html__( 'Pricing Table', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-pricing-table';
	}

	public function get_script_depends() {

		if ( isset( $_GET['elementor-preview'] ) && 'wp_enqueue_scripts' === current_filter() ) {
			return array( 'tippy-bundle', 'jet-anime-js' );
		}

		$scripts = array();

		if ( $this->_pricing_features_items_tooltips_check() ) {
			$scripts[] = 'tippy-bundle';
		}

		if ( $this->_is_fold_enabled() ) {
			$scripts[] = 'jet-anime-js';
		}

		return $scripts;
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-create-and-add-an-effective-pricing-table-to-pages-built-with-elementor-using-jetelements-pricing-table-widget/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-elements/pricing-table/css-scheme',
			array(
				'table'            => '.pricing-table',
				'header'           => '.pricing-table__heading',
				'icon_wrap'        => '.pricing-table__icon',
				'icon_box'         => '.pricing-table__icon-box',
				'icon'             => '.pricing-table__icon-box > *',
				'title'            => '.pricing-table__title',
				'subtitle'         => '.pricing-table__subtitle',
				'price'            => '.pricing-table__price',
				'price_prefix'     => '.pricing-table__price-prefix',
				'price_value'      => '.pricing-table__price-val',
				'price_suffix'     => '.pricing-table__price-suffix',
				'price_desc'       => '.pricing-table__price-desc',
				'features'         => '.pricing-table__features',
				'features_item'    => '.pricing-feature',
				'included_item'    => '.pricing-feature.item-included',
				'excluded_item'    => '.pricing-feature.item-excluded',
				'action'           => '.pricing-table__action',
				'button'           => '.pricing-table__action .pricing-table-button',
				'button_icon'      => '.pricing-table__action .button-icon',
				'tooltip'          => '.tippy-box',
				'fold_button'      => '.pricing-table__fold-button',
				'fold_button_icon' => '.pricing-table__fold-button-icon',
				'fold_button_text' => '.pricing-table__fold-button-text',
				'fold_trigger'     => '.pricing-table__fold-trigger',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'General', 'jet-elements' ),
			)
		);

		$this->_add_advanced_icon_control(
			'icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'file'        => '',
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'   => esc_html__( 'Icon Position', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => array(
					'inside'  => esc_html__( 'Inside Header', 'jet-elements' ),
					'outside' => esc_html__( 'Outside Header', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'     => esc_html__( 'Title', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Title', 'jet-elements' ),
				'dynamic'   => array( 'active' => true ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'default' => 'h2',
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'     => esc_html__( 'Subtitle', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Subtitle', 'jet-elements' ),
				'dynamic'   => array( 'active' => true ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'subtitle_html_tag',
			array(
				'label'   => esc_html__( 'Subtitle HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'default' => 'h4',
			)
		);

		$this->add_control(
			'featured',
			array(
				'label'        => esc_html__( 'Is Featured?', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'featured_badge',
			array(
				'label'   => esc_html__( 'Featured Badge', 'jet-elements' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => jet_elements_tools()->get_badge_placeholder(),
				),
				'condition' => array(
					'featured' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_position',
			array(
				'label'   => esc_html__( 'Featured Position', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'left'  => esc_html__( 'Left', 'jet-elements' ),
					'right' => esc_html__( 'Right', 'jet-elements' ),
				),
				'condition' => array(
					'featured' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'featured_left',
			array(
				'label'      => esc_html__( 'Left Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -200,
						'max' => 200,
					),
					'%' => array(
						'min' => -50,
						'max' => 50,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'featured_position' => 'left',
					'featured' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pricing-table__badge' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'featured_right',
			array(
				'label'      => esc_html__( 'Right Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -200,
						'max' => 200,
					),
					'%' => array(
						'min' => -50,
						'max' => 50,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'condition' => array(
					'featured_position' => 'right',
					'featured' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .pricing-table__badge' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'featured_top',
			array(
				'label'      => esc_html__( 'Top Indent', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => -200,
						'max' => 200,
					),
					'%' => array(
						'min' => -50,
						'max' => 50,
					),
					'em' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pricing-table__badge' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'featured' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_price',
			array(
				'label' => esc_html__( 'Price', 'jet-elements' ),
			)
		);

		$this->add_control(
			'price_prefix',
			array(
				'label'   => esc_html__( 'Price Prefix', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '$', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'price',
			array(
				'label'   => esc_html__( 'Price Value', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '100', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'price_suffix',
			array(
				'label'   => esc_html__( 'Price Suffix', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '/per month', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'price_desc',
			array(
				'label'   => esc_html__( 'Price Description', 'jet-elements' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_features',
			array(
				'label' => esc_html__( 'Features', 'jet-elements' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_text',
			array(
				'label'   => esc_html__( 'Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Feature', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_included',
			array(
				'label'   => esc_html__( 'Is Included?', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'item-included',
				'options' => array(
					'item-included' => esc_html__( 'Included', 'jet-elements' ),
					'item-excluded' => esc_html__( 'Excluded', 'jet-elements' ),
				),
			)
		);

		$repeater->add_control(
			'item_tooltip',
			array(
				'label'   => esc_html__( 'Tooltip', 'jet-elements' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'features_list',
			array(
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array(
						'item_text'     => esc_html__( 'Feature #1', 'jet-elements' ),
						'item_included' => 'item-included',
					),
					array(
						'item_text'     => esc_html__( 'Feature #2', 'jet-elements' ),
						'item_included' => 'item-included',
					),
					array(
						'item_text'     => esc_html__( 'Feature #3', 'jet-elements' ),
						'item_included' => 'item-excluded',
					),
					array(
						'item_text'     => esc_html__( 'Feature #4', 'jet-elements' ),
						'item_included' => 'item-excluded',
					),
				),
				'title_field' => '{{{ item_text }}}',
			)
		);

		$this->_add_advanced_icon_control(
			'included_bullet_icon',
			array(
				'label'       => esc_html__( 'Included Bullet Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'default'     => 'fa fa-check',
				'fa5_default' => array(
					'value'   => 'fas fa-check',
					'library' => 'fa-solid',
				),
				'separator'   => 'before',
			)
		);

		$this->_add_advanced_icon_control(
			'excluded_bullet_icon',
			array(
				'label'       => esc_html__( 'Excluded Bullet Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'default'     => 'fa fa-times',
				'fa5_default' => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_action',
			array(
				'label' => esc_html__( 'Action Button', 'jet-elements' ),
			)
		);

		$this->add_control(
			'button_before',
			array(
				'label'   => esc_html__( 'Text Before Action Button', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Buy', 'jet-elements' ),
			)
		);

		$this->add_control(
			'button_url',
			array(
				'label'   => esc_html__( 'Button URL', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '#',
				'dynamic' => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
			)
		);

		$this->add_control(
			'button_is_external',
			array(
				'label'     => esc_html__( 'Open in new window', 'jet-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'button_url!' => '',
				),
			)
		);

		$this->add_control(
			'button_nofollow',
			array(
				'label'     => esc_html__( 'Add nofollow', 'jet-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'button_url!' => '',
				),
			)
		);

		$this->add_control(
			'add_button_icon',
			array(
				'label'        => esc_html__( 'Add Icon', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->_add_advanced_icon_control(
			'button_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'condition'   => array(
					'add_button_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_icon_position',
			array(
				'label'   => esc_html__( 'Icon Position', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'left'  => esc_html__( 'Before Text', 'jet-elements' ),
					'right' => esc_html__( 'After Text', 'jet-elements' ),
				),
				'default'     => 'left',
				'render_type' => 'template',
				'condition'   => array(
					'add_button_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_after',
			array(
				'label'   => esc_html__( 'Text After Action Button', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tooltip',
			array(
				'label' => esc_html__( 'Tooltips', 'jet-elements' ),
			)
		);

		$this->add_control(
			'tooltip_placement',
			array(
				'label'   => esc_html__( 'Placement', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top-start'    => esc_html__( 'Top Start', 'jet-elements' ),
					'top'          => esc_html__( 'Top', 'jet-elements' ),
					'top-end'      => esc_html__( 'Top End', 'jet-elements' ),
					'right-start'  => esc_html__( 'Right Start', 'jet-elements' ),
					'right'        => esc_html__( 'Right', 'jet-elements' ),
					'right-end'    => esc_html__( 'Right End', 'jet-elements' ),
					'bottom-start' => esc_html__( 'Bottom Start', 'jet-elements' ),
					'bottom'       => esc_html__( 'Bottom', 'jet-elements' ),
					'bottom-end'   => esc_html__( 'Bottom End', 'jet-elements' ),
					'left-start'   => esc_html__( 'Left Start', 'jet-elements' ),
					'left'         => esc_html__( 'Left', 'jet-elements' ),
					'left-end'     => esc_html__( 'Left End', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'tooltip_trigger',
			array(
				'label'   => esc_html__( 'Trigger', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'mouseenter',
				'options' => array(
					'manual'           => esc_html__( 'None', 'jet-elements' ),
					'mouseenter'       => esc_html__( 'Mouse Enter', 'jet-elements' ),
					'click'            => esc_html__( 'Click', 'jet-elements' ),
					'focus'            => esc_html__( 'Focus', 'jet-elementss' ),
					'mouseenter click' => esc_html__( 'Mouse Enter + Click', 'jet-elements' ),
					'mouseenter focus' => esc_html__( 'Mouse Enter + Focus', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'tooltip_animation',
			array(
				'label'   => esc_html__( 'Animation', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'shift-toward',
				'options' => array(
					'shift-away'   => esc_html__( 'Shift-Away', 'jet-elements' ),
					'shift-toward' => esc_html__( 'Shift-Toward', 'jet-elements' ),
					'scale'        => esc_html__( 'Scale', 'jet-elements' ),
					'perspective'  => esc_html__( 'Perspective', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'tooltip_arrow',
			array(
				'label'        => esc_html__( 'Use Arrow', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'tooltip_delay',
			array(
				'label'      => esc_html__( 'Animation Delay', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'ms',
				),
				'range' => array(
					'ms' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 0,
					'unit' => 'ms',
				),
			)
		);

		$this->add_control(
			'tooltip_show_duration',
			array(
				'label'      => esc_html__( 'Appearance Duration', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'ms',
				),
				'range' => array(
					'ms' => array(
						'min'  => 100,
						'max'  => 1000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 500,
					'unit' => 'ms',
				),
			)
		);

		$this->add_control(
			'tooltip_hide_duration',
			array(
				'label'      => esc_html__( 'Disappearance Duration', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'ms',
				),
				'range' => array(
					'ms' => array(
						'min'  => 100,
						'max'  => 1000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 300,
					'unit' => 'ms',
				),
			)
		);

		$this->add_control(
			'tooltip_distance',
			array(
				'label'      => esc_html__( 'Distance', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range' => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
					),
				),
				'default' => array(
					'size' => 15,
					'unit' => 'px',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_fold',
			array(
				'label' => esc_html__( 'Fold Settings', 'jet-elements' ),
			)
		);

		$this->add_control(
			'fold_enabled',
			array(
				'label'        => esc_html__( 'Fold Enabled', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'fold_items_show',
			array(
				'label'     => esc_html__( 'Number of Visible Items', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 1,
				'condition' => array(
					'fold_enabled' => 'true',
				),
			)
		);

		$this->add_control(
			'fold_state_heading',
			array(
				'label'     => esc_html__( 'Fold State Styles', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'fold_enabled' => 'true',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_settings' );

		$this->start_controls_tab(
			'tab_unfold_settings',
			array(
				'label'     => esc_html__( 'Unfold', 'jet-elements' ),
				'condition' => array(
					'fold_enabled' => 'true',
				),
			)
		);

		$this->add_control(
			'unfold_duration',
			array(
				'label'      => esc_html__( 'Duration', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'ms' ),
				'range'      => array(
					'ms' => array(
						'min'  => 100,
						'max'  => 3000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 500,
					'unit' => 'ms',
				),
				'condition' => array(
					'fold_enabled' => 'true',
				),
			)
		);

		$this->add_control(
			'unfold_easing',
			array(
				'label'       => esc_html__( 'Easing', 'jet-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'easeOutBack',
				'options'     => array(
					'linear'        => esc_html__( 'Linear', 'jet-elements' ),
					'easeOutSine'   => esc_html__( 'Sine', 'jet-elements' ),
					'easeOutExpo'   => esc_html__( 'Expo', 'jet-elements' ),
					'easeOutCirc'   => esc_html__( 'Circ', 'jet-elements' ),
					'easeOutBack'   => esc_html__( 'Back', 'jet-elements' ),
					'easeInOutSine' => esc_html__( 'InOutSine', 'jet-elements' ),
					'easeInOutExpo' => esc_html__( 'InOutExpo', 'jet-elements' ),
					'easeInOutCirc' => esc_html__( 'InOutCirc', 'jet-elements' ),
					'easeInOutBack' => esc_html__( 'InOutBack', 'jet-elements' ),
				),
				'condition' => array(
					'fold_enabled' => 'true',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_fold_settings',
			array(
				'label'     => esc_html__( 'Fold', 'jet-elements' ),
				'condition' => array(
					'fold_enabled' => 'true',
				),
			)
		);

		$this->add_control(
			'fold_duration',
			array(
				'label'      => esc_html__( 'Duration', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'ms' ),
				'range'      => array(
					'ms' => array(
						'min'  => 100,
						'max'  => 3000,
						'step' => 100,
					),
				),
				'default' => array(
					'size' => 300,
					'unit' => 'ms',
				),
				'condition' => array(
					'fold_enabled' => 'true',
				),
			)
		);

		$this->add_control(
			'fold_easing',
			array(
				'label'       => esc_html__( 'Easing', 'jet-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'easeOutSine',
				'options'     => array(
					'linear'        => esc_html__( 'Linear', 'jet-elements' ),
					'easeOutSine'   => esc_html__( 'Sine', 'jet-elements' ),
					'easeOutExpo'   => esc_html__( 'Expo', 'jet-elements' ),
					'easeOutCirc'   => esc_html__( 'Circ', 'jet-elements' ),
					'easeOutBack'   => esc_html__( 'Back', 'jet-elements' ),
					'easeInOutSine' => esc_html__( 'InOutSine', 'jet-elements' ),
					'easeInOutExpo' => esc_html__( 'InOutExpo', 'jet-elements' ),
					'easeInOutCirc' => esc_html__( 'InOutCirc', 'jet-elements' ),
					'easeInOutBack' => esc_html__( 'InOutBack', 'jet-elements' ),
				),
				'condition' => array(
					'fold_enabled' => 'true',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_fold_button',
			array(
				'label'     => esc_html__( 'Fold Button', 'jet-elements' ),
				'condition' => array(
					'fold_enabled' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'fold_button_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Start', 'jet-elements' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'jet-elements' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['fold_trigger'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fold_button_icon_position',
			array(
				'label'   => esc_html__( 'Icon position', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Before Text', 'jet-elements' ),
					'right' => esc_html__( 'After Text', 'jet-elements' ),
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button' );

		$this->start_controls_tab(
			'tab_fold_button',
			array(
				'label' => esc_html__( 'Fold', 'jet-elements' ),
			)
		);

		$this->add_control(
			$this->_new_icon_prefix . 'button_fold_icon',
			array(
				'label'            => esc_html__( 'Fold Icon', 'jet-elements' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'button_unfold_icon',
				'default'          => array(
					'value'   => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				),
				'render_type'      => 'template',
			)
		);

		$this->add_control(
			'button_fold_text',
			array(
				'label'   => esc_html__( 'Fold Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hide', 'jet-elements' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_unfold_button',
			array(
				'label' => esc_html__( 'Unfold', 'jet-elements' ),
			)
		);

		$this->add_control(
			$this->_new_icon_prefix . 'button_unfold_icon',
			array(
				'label'            => esc_html__( 'Fold Icon', 'jet-elements' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'button_fold_icon',
				'default'          => array(
					'value'   => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				),
				'render_type'      => 'template',
			)
		);

		$this->add_control(
			'button_unfold_text',
			array(
				'label'   => esc_html__( 'Unfold Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Show', 'jet-elements' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->_start_controls_section(
			'section_table_style',
			array(
				'label'      => esc_html__( 'Table', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'table_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['table'],
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'table_border',
				'label'          => esc_html__( 'Border', 'jet-elements' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['table'],
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'isLinked' => true,
						),
					),
					'color'  => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_3,
						),
					),
				),
			),
			25
		);

		$this->_add_responsive_control(
			'table_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['table'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'table_padding',
			array(
				'label'      => esc_html__( 'Table Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['table'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'table_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['table'],
			),
			100
		);

		$this->_add_control(
			'order_heading',
			array(
				'label'     => esc_html__( 'Order', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			100
		);

		$this->_add_control(
			'header_order',
			array(
				'label'     => esc_html__( 'Header Order', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['header'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'icon_order',
			array(
				'label'     => esc_html__( 'Icon Order', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon_wrap'] => 'order: {{VALUE}};',
				),
				'condition' => array(
					'icon_position' => 'outside',
				),
			),
			100
		);

		$this->_add_control(
			'pricing_order',
			array(
				'label'     => esc_html__( 'Pricing Order', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'features_order',
			array(
				'label'     => esc_html__( 'Features Order', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['features'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_add_control(
			'action_box_order',
			array(
				'label'     => esc_html__( 'Action Box Order', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['action'] => 'order: {{VALUE}};',
				),
			),
			100
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_header_style',
			array(
				'label'      => esc_html__( 'Header', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'header_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['header'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'header_title_style',
			array(
				'label'     => esc_html__( 'Title', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'title_color',
			array(
				'label'  => esc_html__( 'Title Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			),
			50
		);

		$this->_add_control(
			'header_subtitle_style',
			array(
				'label'     => esc_html__( 'Subtitle', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'subtitle_color',
			array(
				'label'  => esc_html__( 'Subtitle Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['subtitle'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['subtitle'],
			),
			50
		);

		$this->_add_responsive_control(
			'header_padding',
			array(
				'label'      => esc_html__( 'Header Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['header'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->_add_responsive_control(
			'header_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					'{{WRAPPER}} ' . $css_scheme['header'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'header_border',
				'label'          => esc_html__( 'Border', 'jet-elements' ),
				'placeholder'    => '1px',
				'selector'       => '{{WRAPPER}} ' . $css_scheme['header'],
			),
			75
		);

		$this->_add_responsive_control(
			'header_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['header'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_icon_style',
			array(
				'label'      => esc_html__( 'Icon', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_group_control(
			\Jet_Group_Control_Box_Style::get_type(),
			array(
				'name'           => 'icon_style',
				'label'          => esc_html__( 'Icon Style', 'jet-elements' ),
				'selector'       => '{{WRAPPER}} ' . $css_scheme['icon'],
				'fields_options' => array(
					'box_font_color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
					),
				),
			),
			25
		);

		$this->_add_control(
			'icon_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon_wrap'] => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'icon_position' => 'outside',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'icon_wrap_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			75
		);

		$this->_add_responsive_control(
			'icon_box_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					'{{WRAPPER}} ' . $css_scheme['icon_wrap'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_pricing_style',
			array(
				'label'      => esc_html__( 'Pricing', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'price_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'price_prefix_style',
			array(
				'label'     => esc_html__( 'Prefix', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'price_prefix_color',
			array(
				'label'  => esc_html__( 'Price Prefix Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price_prefix'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_prefix_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['price_prefix'],
			),
			50
		);

		$this->_add_control(
			'price_prefix_vertical_align',
			array(
				'label'   => esc_html__( 'Vertical Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->verrtical_align_attr(),
				'default' => 'baseline',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price_prefix'] => 'vertical-align: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_control(
			'price_prefix_dispaly',
			array(
				'label'   => esc_html__( 'Prefix Display', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'inline-block' => esc_html__( 'Inline', 'jet-elements' ),
					'block'        => esc_html__( 'Block', 'jet-elements' ),
				),
				'default' => 'inline-block',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price_prefix'] => 'display: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_control(
			'price_val_style',
			array(
				'label'     => esc_html__( 'Price Value', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'price_color',
			array(
				'label'  => esc_html__( 'Price Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price_value'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'price_typography',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}}  ' . $css_scheme['price_value'],
			),
			50
		);

		$this->_add_control(
			'price_suffix_style',
			array(
				'label'     => esc_html__( 'Suffix', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'price_suffix_color',
			array(
				'label'  => esc_html__( 'Price Suffix Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price_suffix'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_suffix_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['price_suffix'],
			),
			50
		);

		$this->_add_control(
			'price_suffix_vertical_align',
			array(
				'label'   => esc_html__( 'Vertical Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->verrtical_align_attr(),
				'default' => 'baseline',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price_suffix'] => 'vertical-align: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_control(
			'price_suffix_dispaly',
			array(
				'label'   => esc_html__( 'Suffix Display', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'inline-block' => esc_html__( 'Inline', 'jet-elements' ),
					'block'        => esc_html__( 'Block', 'jet-elements' ),
				),
				'default'   => 'inline-block',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price_suffix'] => 'display: {{VALUE}};',
				),
			),
			50
		);

		$this->_add_control(
			'price_desc_style',
			array(
				'label'     => esc_html__( 'Description', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'price_desc_color',
			array(
				'label' => esc_html__( 'Price Description Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price_desc'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_desc_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['price_desc'],
			),
			50
		);

		$this->_add_control(
			'price_desc_gap',
			array(
				'label' => esc_html__( 'Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['price_desc'] => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'price_padding',
			array(
				'label'      => esc_html__( 'Pricing Block Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['price'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'price_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['price'],
			),
			75
		);

		$this->_add_responsive_control(
			'price_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['price'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'price_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					'{{WRAPPER}} ' . $css_scheme['price'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_features_style',
			array(
				'label'      => esc_html__( 'Features', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'features_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['features'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'features_padding',
			array(
				'label'      => esc_html__( 'Features Block Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['features'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'features_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['features'],
			),
			75
		);

		$this->_add_responsive_control(
			'features_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['features'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'features_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['features_item'],
			),
			50
		);

		$this->_add_responsive_control(
			'features_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					'{{WRAPPER}} ' . $css_scheme['features'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_add_control(
			'heading_included_feature_style',
			array(
				'label'     => esc_html__( 'Included Feature', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'inc_features_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['included_item'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'inc_bullet_icon_size',
			array(
				'label'   => esc_html__( 'Icon Size', 'jet-elements' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 14,
					'unit' => 'px',
				),
				'range' => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['included_item'] . ' .item-bullet' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_control(
			'inc_bullet_color',
			array(
				'label'  => esc_html__( 'Bullet Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['included_item'] . ' .item-bullet' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'heading_excluded_feature_style',
			array(
				'label'     => esc_html__( 'Excluded Feature', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'exc_features_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['excluded_item'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'exc_bullet_icon_size',
			array(
				'label'   => esc_html__( 'Icon Size', 'jet-elements' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 14,
					'unit' => 'px',
				),
				'range' => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['excluded_item'] . ' .item-bullet' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_control(
			'exc_bullet_color',
			array(
				'label'  => esc_html__( 'Bullet Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['excluded_item'] . ' .item-bullet' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'exc_text_decoration',
			array(
				'label'   => esc_html__( 'Text Decoration', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'         => esc_html__( 'None', 'jet-elements' ),
					'line-through' => esc_html__( 'Line Through', 'jet-elements' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['excluded_item'] . ' .pricing-feature__text' => 'text-decoration: {{VALUE}}',
				),
			),
			50
		);

		$this->_add_control(
			'features_divider_style',
			array(
				'label'     => esc_html__( 'Features Divider', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			75
		);

		$this->_add_control(
			'features_divider',
			array(
				'label'        => esc_html__( 'Divider', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'yes',
				'default'      => '',
			),
			75
		);

		$this->_add_control(
			'features_divider_line',
			array(
				'label'   => esc_html__( 'Style', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'solid'  => esc_html__( 'Solid', 'jet-elements' ),
					'double' => esc_html__( 'Double', 'jet-elements' ),
					'dotted' => esc_html__( 'Dotted', 'jet-elements' ),
					'dashed' => esc_html__( 'Dashed', 'jet-elements' ),
				),
				'default' => 'solid',
				'condition' => array(
					'features_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['features_item'] . ':before' => 'border-top-style: {{VALUE}};',
				),
			),
			75
		);

		$this->_add_control(
			'features_divider_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'condition' => array(
					'features_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['features_item'] . ':before' => 'border-top-color: {{VALUE}};',
				),
			),
			75
		);

		$this->_add_control(
			'features_divider_weight',
			array(
				'label'   => esc_html__( 'Weight', 'jet-elements' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 1,
					'unit' => 'px',
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'condition' => array(
					'features_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['features_item'] . ':before' => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_control(
			'features_divider_width',
			array(
				'label'     => esc_html__( 'Width', 'jet-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'condition' => array(
					'features_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['features_item'] . ':before' => 'width: {{SIZE}}%',
				),
			),
			75
		);

		$this->_add_control(
			'features_divider_gap',
			array(
				'label'   => esc_html__( 'Gap', 'jet-elements' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 15,
					'unit' => 'px',
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'condition' => array(
					'features_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['features_item'] . ':before' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['features_item'] => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .pricing-table-unfold-state .fold_visible_last' => 'margin-bottom: {{SIZE}}{{UNIT}} !important',
				),
			),
			75
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_actions_style',
			array(
				'label'      => esc_html__( 'Action Box', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'action_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['action'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'action_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['action'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'action_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['action'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'action_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['action'],
			),
			75
		);

		$this->_add_responsive_control(
			'action_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['action'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'action_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['action'],
			),
			50
		);

		$this->_add_responsive_control(
			'action_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
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
					'{{WRAPPER}} ' . $css_scheme['action'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_action_button_style',
			array(
				'label'      => esc_html__( 'Action Button', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'button_size',
			array(
				'label'   => esc_html__( 'Size', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto' => esc_html__( 'auto', 'jet-elements' ),
					'full' => esc_html__( 'full', 'jet-elements' ),
				),
			),
			50
		);

		$this->_add_control(
			'button_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'add_button_icon' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'button_icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 7,
						'max' => 90,
					),
				),
				'condition' => array(
					'add_button_icon' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_control(
			'button_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'add_button_icon' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button_icon'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'button_icon_margin',
			array(
				'label'      => esc_html__( 'Icon Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'condition'  => array(
					'add_button_icon' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button_icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'after',
			),
			25
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
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color' => array(
						'label'  => _x( 'Background Color', 'Background Control', 'jet-elements' ),
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
					),
					'color_b' => array(
						'label' => _x( 'Second Background Color', 'Background Control', 'jet-elements' ),
					),
				),
				'exclude' => array(
					'image',
					'position',
					'attachment',
					'attachment_alert',
					'repeat',
					'size',
				),
			),
			25
		);

		$this->_add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
			),
			50
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
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
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
				'name'     => 'button_hover_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color' => array(
						'label' => _x( 'Background Color', 'Background Control', 'jet-elements' ),
					),
					'color_b' => array(
						'label' => _x( 'Second Background Color', 'Background Control', 'jet-elements' ),
					),
				),
				'exclude' => array(
					'image',
					'position',
					'attachment',
					'attachment_alert',
					'repeat',
					'size',
				),
			),
			25
		);

		$this->_add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_hover_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['button'] . ':hover',
			),
			75
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
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_hover_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
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

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_end_controls_section();

		/**
		 * Tooltips Style Section
		 */
		$this->_start_controls_section(
			'section_tooltips_style',
			array(
				'label' => esc_html__( 'Tooltips', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->_add_control(
			'tooltip_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tooltip_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'],
			),
			50
		);

		$this->_add_control(
			'tooltip_text_align',
			array(
				'label'   => esc_html__( 'Text Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
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
						'title' => esc_html__( 'Justify', 'jet-elements' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_add_control(
			'tooltip_arrow_color',
			array(
				'label'     => esc_html__( 'Arrow Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'] . '[data-placement*=left] .tippy-arrow:before'=> 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'] . '[data-placement*=right] .tippy-arrow:before'=> 'border-right-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'] . '[data-placement*=top] .tippy-arrow:before'=> 'border-top-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'] . '[data-placement*=bottom] .tippy-arrow:before'=> 'border-bottom-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'tooltip_wrapper_style_heading',
			array(
				'label'     => esc_html__( 'Wrapper Styles', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_responsive_control(
			'tooltip_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em',
				),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'] => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['table'] . ' .tippy-popper' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_control(
			'tooltip_background',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'tooltip_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tooltip_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'],
			),
			50
		);

		$this->_add_responsive_control(
			'tooltip_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tooltip_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['table'] . ' ' . $css_scheme['tooltip'],
			),
			75
		);

		$this->_end_controls_section();

		/**
		 * `Fold Button` style section
		 */
		$this->_start_controls_section(
			'fold_button_section_style',
			array(
				'label'     => __( 'Fold Button', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'fold_enabled' => 'true',
				),
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'fold_button_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['fold_button'],
			),
			50
		);

		$this->_start_controls_tabs( 'tabs_fold_button_style' );

		$this->_start_controls_tab(
			'tab_fold_button_normal',
			array(
				'label' => __( 'Normal', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'fold_button_icon_color',
			array(
				'label'     => __( 'Icon Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['fold_button_icon'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'fold_button_text_color',
			array(
				'label'     => __( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['fold_button_text'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'fold_button_background_color',
			array(
				'label'     => __( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['fold_button'] => 'background-color: {{VALUE}};',
				),
			),
		    25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_fold_button_hover',
			array(
				'label' => __( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'fold_button_hover_icon_color',
			array(
				'label'     => __( 'Icon Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['fold_button'] . ':hover ' . $css_scheme['fold_button_icon'] . ', {{WRAPPER}} ' . $css_scheme['fold_button'] . ':focus ' . $css_scheme['fold_button_icon'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'fold_button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['fold_button'] . ':hover ' . $css_scheme['fold_button_text'] . ', {{WRAPPER}} ' . $css_scheme['fold_button'] . ':focus ' . $css_scheme['fold_button_text'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'fold_button_background_hover_color',
			array(
				'label'     => __( 'Background Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['fold_button'] . ':hover, {{WRAPPER}} ' . $css_scheme['fold_button'] . ':focus' => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'fold_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'fold_button_border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['fold_button'] . ':hover, {{WRAPPER}} ' . $css_scheme['fold_button'] . ':focus' => 'border-color: {{VALUE}};',
				),
			),
			75
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_responsive_control(
			'fold_button_padding',
			array(
				'label'      => __( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['fold_button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			),
			50
		);

		$this->_add_responsive_control(
			'fold_button_margin',
			array(
				'label'      => __( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['fold_trigger'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_control(
			'fold_button_icon_size',
			array(
				'label'   => esc_html__( 'Icon Size', 'jet-elements' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 14,
					'unit' => 'px',
				),
				'range' => array(
					'px' => array(
						'min' => 6,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['fold_button_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			50
		);

		$this->_add_control(
			'fold_button_icon_margin',
			array(
				'label'      => __( 'Icon margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['fold_button_icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'fold_button_border',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['fold_button'],
				'separator' => 'before',
			),
			75
		);

		$this->_add_control(
			'fold_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['fold_button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'fold_button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['fold_button'],
			),
			75
		);

		$this->_end_controls_section();

	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	protected function content_template() {}

	public function _pricing_feature_icon() {
		return call_user_func( array( $this, sprintf( '_pricing_feature_icon_%s', $this->_context ) ) );
	}

	public function _pricing_feature_icon_render() {

		$item = $this->_processed_item;

		switch ( $item['item_included'] ) {
			case 'item-excluded':
				$icon_setting = 'excluded_bullet_icon';
				break;

			default:
				$icon_setting = 'included_bullet_icon';
				break;
		}

		$this->_processed_item = false;

		$icon = $this->_get_icon( $icon_setting, '<span class="item-bullet jet-elements-icon">%s</span>' );

		$this->_processed_item = $item;

		return $icon;
	}

	public function _pricing_features_items_tooltips_check() {
		$settings = $this->get_settings();

		if ( isset( $settings['features_list'] ) && is_array( $settings['features_list'] ) ) {

			$features_list = $settings['features_list'];
			$check         = false;

			foreach ( $features_list as $item ) {
				if ( ! empty( $item['item_tooltip'] ) ) {
					$check = true;
				}
			}

			return $check;
		}

		return false;
	}

	public function _is_fold_enabled() {
		$settings           = $this->get_settings_for_display();
		$fold_enabled       = isset( $settings['fold_enabled'] ) ? filter_var( $settings['fold_enabled'], FILTER_VALIDATE_BOOLEAN ) : false;
		$fold_visible_items = isset( $settings['fold_items_show'] ) ? $settings['fold_items_show'] : 1;
		$features_count     = count( $settings['features_list'] );

		if ( $fold_enabled && $features_count > $fold_visible_items ) {
			return true;
		}

		return false;
	}

	public function _pricing_feature_icon_edit() {
		?>
		<# if ( 'item-excluded' === item.item_included ) { #>
			<# if ( settings.excluded_bullet_icon ) { #>
				<i class="item-bullet {{{ settings.excluded_bullet_icon }}}"></i>
			<# } #>
		<# } else { #>
			<# if ( settings.included_bullet_icon ) { #>
				<i class="item-bullet {{{ settings.included_bullet_icon }}}"></i>
			<# } #>
		<# } #>
		<?php
	}
	
	public function _get_badge_image() {
		$badge = $this->get_settings_for_display( 'featured_badge' );
		
		if ( ! isset( $badge['url'] ) ) {
			return;
		}
		
		return jet_elements_tools()->get_image_by_url( $badge['url'], array( 'class' => 'pricing-table__badge', 'alt' => esc_attr( Control_Media::get_image_alt( $badge ) ) ) );
	}
}
