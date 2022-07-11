<?php
/**
 * Jet Smart Filters Indexer Controls class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Indexer_Controls' ) ) {

	/**
	 * Define Jet_Smart_Filters_Indexer_Controls class
	 */
	class Jet_Smart_Filters_Indexer_Controls {

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'elementor/element/jet-smart-filters-checkboxes/additional_settings/after_section_end', array(
				$this,
				'add_widget_controls',
			), 10, 2 );
			add_action( 'elementor/element/jet-smart-filters-radio/additional_settings/after_section_end', array(
				$this,
				'add_widget_controls',
			), 10, 2 );
			add_action( 'elementor/element/jet-smart-filters-check-range/additional_settings/after_section_end', array(
				$this,
				'add_widget_controls',
			), 10, 2 );
			add_action( 'elementor/element/jet-smart-filters-color-image/additional_settings/after_section_end', array(
				$this,
				'add_widget_controls',
			), 10, 2 );
			add_action( 'elementor/element/jet-smart-filters-select/section_general/after_section_end', array(
				$this,
				'add_widget_controls',
			), 10, 2 );

			add_action( 'elementor/element/jet-smart-filters-checkboxes/section_filter_apply_button_style/after_section_end', array(
				$this,
				'add_widget_style_controls',
			), 10, 2 );
			add_action( 'elementor/element/jet-smart-filters-color-image/section_filter_apply_button_style/after_section_end', array(
				$this,
				'add_widget_style_controls',
			), 10, 2 );
			add_action( 'elementor/element/jet-smart-filters-radio/section_filter_apply_button_style/after_section_end', array(
				$this,
				'add_widget_style_controls',
			), 10, 2 );
			add_action( 'elementor/element/jet-smart-filters-check-range/section_filter_apply_button_style/after_section_end', array(
				$this,
				'add_widget_style_controls',
			), 10, 2 );

			add_action( 'jet-smart-filter/templates/counter', array( $this, 'get_counter_html' ), 10, 2 );

		}

		/**
		 * Add style controls to widgets
		 *
		 * @param       $obj
		 * @param array $args
		 */
		public function add_widget_style_controls( $obj, $args = array() ) {

			$obj->start_controls_section(
				'section_counter_style',
				array(
					'label'      => esc_html__( 'Counter', 'jet-smart-filters' ),
					'tab'        => Elementor\Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$obj->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				array(
					'name'     => 'counter_typography',
					'selector' => '{{WRAPPER}} .jet-filters-counter',
				)
			);

			$obj->add_responsive_control(
				'counter_offset',
				array(
					'label'      => esc_html__( 'Offset Left', 'jet-smart-filters' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => array(
						'px'
					),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 30,
						),
					),
					'default'    => array(
						'size' => 5,
						'unit' => 'px',
					),
					'selectors'  => array(
						'{{WRAPPER}} .jet-filters-counter' => 'margin-left: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$obj->start_controls_tabs( 'counter_style_tabs' );

			$obj->start_controls_tab(
				'counter_normal_styles',
				array(
					'label' => esc_html__( 'Normal', 'jet-smart-filters' ),
				)
			);

			$obj->add_control(
				'counter_normal_color',
				array(
					'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-filters-counter' => 'color: {{VALUE}}',
					),
				)
			);

			$obj->end_controls_tab();

			$obj->start_controls_tab(
				'counter_checked_styles',
				array(
					'label' => esc_html__( 'Checked', 'jet-smart-filters' ),
				)
			);

			$obj->add_control(
				'counter_checked_color',
				array(
					'label'     => esc_html__( 'Color', 'jet-smart-filters' ),
					'type'      => Elementor\Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .jet-filter-row input:checked ~ .jet-checkboxes-list__button .jet-filters-counter' => 'color: {{VALUE}}',
						'{{WRAPPER}} .jet-filter-row input:checked ~ .jet-radio-list__button .jet-filters-counter' => 'color: {{VALUE}}',
						'{{WRAPPER}} .jet-filter-row input:checked ~ .jet-color-image-list__button .jet-filters-counter' => 'color: {{VALUE}}',
					),
				)
			);

			$obj->end_controls_tab();

			$obj->end_controls_tabs();

			$obj->end_controls_section();

		}

		/**
		 * Add controls to widgets
		 *
		 * @param       $obj
		 * @param array $args
		 */
		public function add_widget_controls( $obj, $args = array() ) {

			$obj->start_controls_section(
				'section_indexer_options',
				array(
					'label' => __( 'Indexer Options', 'jet-smart-filters' ),
				)
			);

			$obj->add_control(
				'apply_indexer',
				array(
					'label'        => esc_html__( 'Apply Indexer', 'jet-smart-filters' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'description'  => '',
					'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
					'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$obj->add_control(
				'show_counter',
				array(
					'label'        => esc_html__( 'Show Counter', 'jet-smart-filters' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'description'  => '',
					'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
					'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => array(
						'apply_indexer' => 'yes',
					),
				)
			);

			$obj->add_control(
				'show_items_rule',
				array(
					'label'     => __( 'If Item Empty', 'jet-smart-filters' ),
					'type'      => Elementor\Controls_Manager::SELECT,
					'default'   => 'show',
					'options'   => array(
						'show'    => 'Show',
						'hide'    => 'Hide',
						'disable' => 'Disable',
					),
					'condition' => array(
						'apply_indexer' => 'yes',
					),
				)
			);

			$obj->add_control(
				'change_items_rule',
				array(
					'label'     => __( 'Change Counters', 'jet-smart-filters' ),
					'type'      => Elementor\Controls_Manager::SELECT,
					'default'   => 'always',
					'options' => array(
						'always'                   => 'Always',
						'never'                    => 'Never',
						'other_changed'            => 'Other Filters Changed',
					),
					'condition' => array(
						'apply_indexer' => 'yes',
					),
				)
			);

			$obj->end_controls_section();

		}

		/**
		 * Render counter element html
		 *
		 * @param $args
		 */
		public function get_counter_html( $args ) {

			$options                   = $args['display_options'];
			$options['counter_prefix'] = '(';
			$options['counter_suffix'] = ')';

			$format = apply_filters(
				'jet-smart-filter/templates/counter/format',
				'<span class="jet-filters-counter"><span class="counter-prefix">%s</span><span class="value">0</span><span class="counter-suffix">%s</span></span>'
			);

			$counter = sprintf( $format, $options['counter_prefix'], $options['counter_suffix'] );

			if ( $options['show_counter'] ) {
				echo $counter;
			}

		}
	}

}
