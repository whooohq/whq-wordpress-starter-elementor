<?php
namespace Elementor;

$this->start_controls_section(
	'additional_settings',
	array(
		'label' => __( 'Additional Settings', 'jet-smart-filters' ),
	)
);

/**
 * Search controls
 */
$this->add_control(
	'search_enabled',
	array(
		'label'        => esc_html__( 'Search Enabled', 'jet-smart-filters' ),
		'type'         => Controls_Manager::SWITCHER,
		'description'  => '',
		'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
		'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
		'return_value' => 'yes',
		'default'      => '',
	)
);

$this->add_control(
	'search_placeholder',
	array(
		'label'       => esc_html__( 'Search Placeholder', 'jet-smart-filters' ),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => __( 'Search...', 'jet-smart-filters' ),
		'condition'   => array(
			'search_enabled' => 'yes'
		)
	)
);

/**
 * More Less controls
 */
$this->add_control(
	'moreless_enabled',
	array(
		'label'        => esc_html__( 'More/Less Enabled', 'jet-smart-filters' ),
		'type'         => Controls_Manager::SWITCHER,
		'description'  => '',
		'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
		'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
		'return_value' => 'yes',
		'default'      => '',
		'separator'    => 'before',
	)
);

$this->add_control(
	'less_items_count',
	array(
		'label'     => esc_html__( 'Less Items Count', 'jet-smart-filters' ),
		'type'      => Controls_Manager::NUMBER,
		'default'   => 5,
		'min'       => 1,
		'max'       => 50,
		'step'      => 1,
		'condition' => array(
			'moreless_enabled' => 'yes'
		)
	)
);

$this->add_control(
	'more_text',
	array(
		'label'       => esc_html__( 'More Text', 'jet-smart-filters' ),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => __( 'More', 'jet-smart-filters' ),
		'condition'   => array(
			'moreless_enabled' => 'yes'
		)
	)
);

$this->add_control(
	'less_text',
	array(
		'label'       => esc_html__( 'Less Text', 'jet-smart-filters' ),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => __( 'Less', 'jet-smart-filters' ),
		'condition'   => array(
			'moreless_enabled' => 'yes'
		)
	)
);

/**
 * Dropdown controls
 */
$this->add_control(
	'dropdown_enabled',
	array(
		'label'        => esc_html__( 'Dropdown Enabled', 'jet-smart-filters' ),
		'type'         => Controls_Manager::SWITCHER,
		'description'  => '',
		'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
		'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
		'return_value' => 'yes',
		'default'      => '',
		'separator'    => 'before',
	)
);

$this->add_control(
	'dropdown_placeholder',
	array(
		'label'       => esc_html__( 'Placeholder', 'jet-smart-filters' ),
		'type'        => Controls_Manager::TEXT,
		'placeholder' => __( 'Select some options', 'jet-smart-filters' ),
		'condition'   => array(
			'dropdown_enabled' => 'yes'
		)
	)
);

/**
 * Scroll controls
 */
$this->add_control(
	'scroll_enabled',
	array(
		'label'        => esc_html__( 'Scroll Enabled', 'jet-smart-filters' ),
		'type'         => Controls_Manager::SWITCHER,
		'description'  => '',
		'label_on'     => esc_html__( 'Yes', 'jet-smart-filters' ),
		'label_off'    => esc_html__( 'No', 'jet-smart-filters' ),
		'return_value' => 'yes',
		'default'      => '',
		'separator'    => 'before',
	)
);

$this->add_control(
	'scroll_height',
	array(
		'label'     => esc_html__( 'Scroll Height(px)', 'jet-smart-filters' ),
		'type'      => Controls_Manager::NUMBER,
		'default'   => 290,
		'min'       => 100,
		'max'       => 1000,
		'step'      => 1,
		'condition' => array(
			'scroll_enabled' => 'yes'
		)
	)
);

$this->end_controls_section();