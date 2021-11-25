<?php
/**
 * Class: Jet_Elements_Weather
 * Name: Weather
 * Slug: jet-weather
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

class Jet_Elements_Weather extends Jet_Elements_Base {

	public $weather_data = array();

	/**
	 * API count
	 *
	 * 1.0 Used Yahoo API
	 * 2.0 Used APIXU API
	 * 3.0 Use Weatherbit.io API
	 *
	 * @var string
	 */
	private $api_count = '3.0';

	private $current_weather_api_url = 'https://api.weatherbit.io/v2.0/current';

	private $forecast_weather_api_url = 'https://api.weatherbit.io/v2.0/forecast/daily';

	public function get_name() {
		return 'jet-weather';
	}

	public function get_title() {
		return esc_html__( 'Weather', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-weather';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetelements-weather-widget-how-to-display-current-weather-and-forecast/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/weather/css-scheme',
			array(
				'title'                 => '.jet-weather__title',
				'current_container'     => '.jet-weather__current',
				'current_temp'          => '.jet-weather__current-temp',
				'current_icon'          => '.jet-weather__current-icon .jet-weather-icon',
				'current_desc'          => '.jet-weather__current-desc',
				'current_details'       => '.jet-weather__details',
				'current_details_item'  => '.jet-weather__details-item',
				'current_details_icon'  => '.jet-weather__details-item .jet-weather-icon',
				'current_day'           => '.jet-weather__current-day',
				'forecast_container'    => '.jet-weather__forecast',
				'forecast_item'         => '.jet-weather__forecast-item',
				'forecast_day'          => '.jet-weather__forecast-day',
				'forecast_icon'         => '.jet-weather__forecast-icon .jet-weather-icon',
			)
		);

		$this->start_controls_section(
			'section_weather',
			array(
				'label' => esc_html__( 'Weather', 'jet-elements' ),
			)
		);

		$api_key = jet_elements_settings()->get( 'weather_api_key' );

		if ( ! $api_key ) {
			$this->add_control(
				'set_api_key',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => sprintf(
						esc_html__( 'Please set Weather API key before using this widget. You can create own API key  %1$s. Paste created key on %2$s', 'jet-elements' ),
						'<a target="_blank" href="https://www.weatherbit.io/">' . esc_html__( 'here', 'jet-elements' ) . '</a>',
						'<a target="_blank" href="' . jet_elements_settings()->get_settings_page_link( 'integrations' ) . '">' . esc_html__( 'settings page', 'jet-elements' ) . '</a>'
					)
				)
			);
		}

		$this->add_control(
			'location',
			array(
				'label'       => esc_html__( 'Location', 'jet-elements' ),
				'description' => esc_html__( 'Format: City, State(optional), Country code(optional). Example: London, UK.', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true, ),
				'placeholder' => esc_html__( 'London, UK', 'jet-elements' ),
				'default'     => esc_html__( 'London, UK', 'jet-elements' ),
			)
		);

		$this->add_control(
			'units',
			array(
				'label'   => esc_html__( 'Units', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'metric',
				'options' => array(
					'metric'   => esc_html__( 'Metric', 'jet-elements' ),
					'imperial' => esc_html__( 'Imperial', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'time_format',
			array(
				'label'   => esc_html__( 'Time Format', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '12',
				'options' => array(
					'12' => esc_html__( '12 hour format', 'jet-elements' ),
					'24' => esc_html__( '24 hour format', 'jet-elements' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Show title', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'title_type',
			array(
				'label'   => esc_html__( 'Title Text', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'api'      => esc_html__( 'Returned from API', 'jet-elements' ),
					'location' => esc_html__( 'From Location', 'jet-elements' ),
					'custom'   => esc_html__( 'Custom', 'jet-elements' ),
				),
				'default'   => 'api',
				'condition' => array(
					'show_title' => 'true',
				),
			)
		);

		$this->add_control(
			'custom_title',
			array(
				'label'     => esc_html__( 'Custom title', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'show_title' => 'true',
					'title_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'title_size',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'default'   => 'h3',
				'condition' => array(
					'show_title' => 'true',
				),
			)
		);

		$this->add_control(
			'show_country_name',
			array(
				'label'        => esc_html__( 'Show country name', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => '',
				'condition'    => array(
					'show_title' => 'true',
					'title_type' => 'api',
				),
			)
		);

		$this->add_control(
			'show_current_weather',
			array(
				'label'        => esc_html__( 'Show current weather', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'show_current_weather_details',
			array(
				'label'        => esc_html__( 'Show current weather details', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'condition'    => array(
					'show_current_weather' => 'true',
				),
			)
		);

		$this->add_control(
			'show_forecast_weather',
			array(
				'label'        => esc_html__( 'Show forecast weather', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'forecast_count',
			array(
				'label' => esc_html__( 'Number of forecast days', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 7,
					),
				),
				'default' => array(
					'size' => 5,
				),
				'condition' => array(
					'show_forecast_weather' => 'true',
				),
			)
		);

		$this->end_controls_section();

		$this->_start_controls_section(
			'section_title_style',
			array(
				'label'     => esc_html__( 'Title', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'true',
				),
			)
		);

		$this->_add_control(
			'title_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			),
			50
		);

		$this->_add_responsive_control(
			'title_align',
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
					'justify' => array(
						'title' => esc_html__( 'Justified', 'jet-elements' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			50
		);

		$this->_add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'title_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			),
			100
		);

		$this->_add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'title_text_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			),
			100
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_current_style',
			array(
				'label'     => esc_html__( 'Current Weather', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_current_weather' => 'true',
				),
			)
		);

		$this->_add_control(
			'current_container_heading',
			array(
				'label' => esc_html__( 'Container', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
			),
			25
		);

		$this->_add_responsive_control(
			'current_container_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'current_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_container'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'current_container_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_container'],
			),
			75
		);

		$this->_add_control(
			'current_temp_heading',
			array(
				'label'     => esc_html__( 'Temperature', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'current_temp_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_temp'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'current_temp_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_temp'],
			),
			50
		);

		$this->_add_control(
			'current_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'current_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_icon'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'current_icon_size',
			array(
				'label'      => esc_html__( 'Font Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_control(
			'current_desc_heading',
			array(
				'label'     => esc_html__( 'Description', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'current_desc_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_desc'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'current_desc_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_desc'],
			),
			50
		);

		$this->_add_control(
			'current_desc_gap',
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
					'{{WRAPPER}} ' . $css_scheme['current_desc'] => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_current_details_style',
			array(
				'label'     => esc_html__( 'Details Weather', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_current_weather'         => 'true',
					'show_current_weather_details' => 'true',
				),
			)
		);

		$this->_add_control(
			'current_details_container_heading',
			array(
				'label' => esc_html__( 'Container', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
			),
			25
		);

		$this->_add_control(
			'current_details_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_details'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_control(
			'current_details_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_details'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'current_details_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_details'],
			),
			75
		);

		$this->_add_control(
			'current_details_items_heading',
			array(
				'label'     => esc_html__( 'Items', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'current_details_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_details'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'current_details_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_details'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'current_day_typography',
				'label'    => esc_html__( 'Day typography', 'jet-elements' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_day'],
			),
			50
		);

		$this->_add_control(
			'current_details_item_gap',
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
					'{{WRAPPER}} ' . $css_scheme['current_details'] => 'row-gap: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_control(
			'current_details_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'current_details_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['current_details_icon'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'current_details_icon_size',
			array(
				'label'      => esc_html__( 'Font Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_details_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_forecast_style',
			array(
				'label'     => esc_html__( 'Forecast Weather', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_forecast_weather' => 'true',
				),
			)
		);

		$this->_add_control(
			'forecast_container_heading',
			array(
				'label' => esc_html__( 'Container', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
			),
			25
		);

		$this->_add_responsive_control(
			'forecast_container_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_container'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'forecast_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_container'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'forecast_container_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_container'],
			),
			75
		);

		$this->_add_control(
			'forecast_item_heading',
			array(
				'label'     => esc_html__( 'Items', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'forecast_item_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forecast_item_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_item'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'forecast_day_typography',
				'label'    => esc_html__( 'Day typography', 'jet-elements' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['forecast_day'],
			),
			50
		);

		$this->_add_responsive_control(
			'forecast_item_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'forecast_item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_control(
			'forecast_item_divider',
			array(
				'label'        => esc_html__( 'Divider', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			),
			50
		);

		$this->_add_control(
			'forecast_item_divider_style',
			array(
				'label' => esc_html__( 'Style', 'jet-elements' ),
				'type'  => Controls_Manager::SELECT,
				'options' => array(
					'solid'  => esc_html__( 'Solid', 'jet-elements' ),
					'double' => esc_html__( 'Double', 'jet-elements' ),
					'dotted' => esc_html__( 'Dotted', 'jet-elements' ),
					'dashed' => esc_html__( 'Dashed', 'jet-elements' ),
				),
				'default' => 'solid',
				'condition' => array(
					'forecast_item_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-top-style: {{VALUE}}',
				),
			),
			50
		);

		$this->_add_control(
			'forecast_item_divider_weight',
			array(
				'label'   => esc_html__( 'Weight', 'jet-elements' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 1,
				),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'condition' => array(
					'forecast_item_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-top-width: {{SIZE}}{{UNIT}}',
				),
			),
			50
		);

		$this->_add_control(
			'forecast_item_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'forecast_item_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_item'] . ':not(:first-child)' => 'border-color: {{VALUE}}',
				),
			),
			50
		);

		$this->_add_control(
			'forecast_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_control(
			'forecast_icon_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_icon'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'forecast_icon_size',
			array(
				'label'      => esc_html__( 'Font Size', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['forecast_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();
	}

	protected function render() {
		$this->_context = 'render';

		$this->_open_wrap();

		$this->weather_data = $this->get_weather_data();

		if ( ! empty( $this->weather_data ) ) {
			include $this->_get_global_template( 'index' );
		}

		$this->_close_wrap();
	}

	/**
	 * Get weather data.
	 *
	 * @return array|bool|mixed
	 */
	public function get_weather_data() {

		$api_key = jet_elements_settings()->get( 'weather_api_key' );

		// Do nothing if api key not provided
		if ( ! $api_key ) {
			$message = esc_html__( 'Please set Weather API key before using this widget.', 'jet-elements' );

			echo $this->get_weather_notice( $message );
			return false;
		}

		$settings = $this->get_settings_for_display();
		$location = trim( $settings['location'] );

		if ( empty( $location ) ) {
			return false;
		}

		$units = $this->get_units_param( $settings['units'] );

		$transient_key = sprintf( 'jet-weather-data-%1$s-%2$s', $this->api_count, md5( $location . $units ) );

		$data = get_transient( $transient_key );

		if ( ! $data ) {
			// Prepare request data
			$location = esc_attr( $location );
			$api_key  = esc_attr( $api_key );

			$request_args = array(
				'key'   => urlencode( $api_key ),
				'units' => urlencode( $units ),
				'city'  => urlencode( $location ),
			);

			$current_request_url = add_query_arg(
				$request_args,
				$this->current_weather_api_url
			);

			$current_request_data = $this->_get_request_data( $current_request_url );

			if ( ! $current_request_data ) {
				$message = esc_html__( 'Weather data of this location not found.', 'jet-elements' );

				echo $this->get_weather_notice( $message );

				return false;
			}

			if ( isset( $current_request_data['error'] ) ) {

				echo $this->get_weather_notice( $current_request_data['error'] );

				return false;
			}

			$request_args['days'] = 8;

			$forecast_request_url = add_query_arg(
				$request_args,
				$this->forecast_weather_api_url
			);

			$forecast_request_data = $this->_get_request_data( $forecast_request_url );

			if ( isset( $forecast_request_data['error'] ) ) {

				echo $this->get_weather_notice( $forecast_request_data['error'] );

				return false;
			}

			$data = $this->prepare_weather_data( $current_request_data, $forecast_request_data );

			if ( empty( $data ) ) {
				return false;
			}

			set_transient( $transient_key, $data, apply_filters( 'jet-elements/weather/cached-time', HOUR_IN_SECONDS ) );
		}

		return $data;
	}

	/**
	 * Get units param for request args.
	 *
	 * @param string $unit
	 *
	 * @return string
	 */
	public function get_units_param( $unit ) {

		if ( 'imperial' === $unit ) {
			return 'I';
		}

		return 'M';
	}

	/**
	 * Get request data.
	 *
	 * @param string $url Request url.
	 *
	 * @return array|bool
	 */
	public function _get_request_data( $url ) {

		$response = wp_remote_get( $url, array( 'timeout' => 30 ) );

		if ( ! $response || is_wp_error( $response ) ) {
			return false;
		}

		$data = wp_remote_retrieve_body( $response );

		if ( ! $data || is_wp_error( $data ) ) {
			return false;
		}

		$data = json_decode( $data, true );

		if ( empty( $data ) ) {
			return false;
		}

		return $data;
	}

	/**
	 * Prepare weather data.
	 *
	 * @param array $current_data  Current weather data.
	 * @param array $forecast_data Forecast weather data.
	 *
	 * @return array
	 */
	public function prepare_weather_data( $current_data = array(), $forecast_data = array() ) {

		$data = array(
			// Location data
			'location' => array(
				'city'    => $current_data['data'][0]['city_name'],
				'country' => $current_data['data'][0]['country_code'],
			),

			// Current data
			'current' => array(
				'code'       => $current_data['data'][0]['weather']['code'],
				'is_day'     => 'd' === $current_data['data'][0]['pod'],
				'temp'       => $current_data['data'][0]['temp'],
				'temp_min'   => $forecast_data['data'][0]['min_temp'],
				'temp_max'   => $forecast_data['data'][0]['max_temp'],
				'wind_speed' => $current_data['data'][0]['wind_spd'],
				'wind_deg'   => $current_data['data'][0]['wind_dir'],
				'wind_dir'   => $current_data['data'][0]['wind_cdir'],
				'humidity'   => $current_data['data'][0]['rh'] . '%',
				'pressure'   => $current_data['data'][0]['pres'],
				'sunrise'    => $current_data['data'][0]['sunrise'],
				'sunset'     => $current_data['data'][0]['sunset'],
			),

			// Forecast data
			'forecast' => array(),
		);

		for ( $i = 0; $i < 8; $i ++ ) {
			$data['forecast'][] = array(
				'code'     => $forecast_data['data'][ $i ]['weather']['code'],
				'date'     => $forecast_data['data'][ $i ]['valid_date'],
				'temp_min' => $forecast_data['data'][ $i ]['min_temp'],
				'temp_max' => $forecast_data['data'][ $i ]['max_temp'],
			);
		}

		return $data;
	}

	/**
	 * Get weather conditions by weather code.
	 *
	 * @param int    $code      Weather code.
	 * @param string $condition Weather condition: 'desc' or 'icon'.
	 * @param bool   $is_day    Is day.
	 *
	 * @return array|bool|string|int
	 */
	public function get_weather_conditions( $code = null, $condition = null, $is_day = true ) {

		$conditions = apply_filters( 'jet-elements/weather/conditions', array(
			// Thunderstorm
			'200' => array(
				'desc' => esc_html_x( 'Thunderstorm with light rain', 'Weather description', 'jet-elements' ),
				'icon' => 1,
			),
			'201' => array(
				'desc' => esc_html_x( 'Thunderstorm with rain', 'Weather description', 'jet-elements' ),
				'icon' => 1,
			),
			'202' => array(
				'desc' => esc_html_x( 'Thunderstorm with heavy rain', 'Weather description', 'jet-elements' ),
				'icon' => 1,
			),
			'230' => array(
				'desc' => esc_html_x( 'Thunderstorm with light drizzle', 'Weather description', 'jet-elements' ),
				'icon' => 2,
			),
			'231' => array(
				'desc' => esc_html_x( 'Thunderstorm with drizzle', 'Weather description', 'jet-elements' ),
				'icon' => 2,
			),
			'232' => array(
				'desc' => esc_html_x( 'Thunderstorm with heavy drizzle', 'Weather description', 'jet-elements' ),
				'icon' => 2,
			),
			'233' => array(
				'desc' => esc_html_x( 'Thunderstorm with Hail', 'Weather description', 'jet-elements' ),
				'icon' => 2,
			),

			// Drizzle
			'300' => array(
				'desc' => esc_html_x( 'Light Drizzle', 'Weather description', 'jet-elements' ),
				'icon' => 3,
			),
			'301' => array(
				'desc' => esc_html_x( 'Drizzle', 'Weather description', 'jet-elements' ),
				'icon' => 3,
			),
			'302' => array(
				'desc' => esc_html_x( 'Heavy Drizzle', 'Weather description', 'jet-elements' ),
				'icon' => 3,
			),

			// Rain
			'500' => array(
				'desc' => esc_html_x( 'Light Rain', 'Weather description', 'jet-elements' ),
				'icon' => 3,
			),
			'501' => array(
				'desc' => esc_html_x( 'Moderate Rain', 'Weather description', 'jet-elements' ),
				'icon' => 3,
			),
			'502' => array(
				'desc' => esc_html_x( 'Heavy Rain', 'Weather description', 'jet-elements' ),
				'icon' => 4,
			),
			'511' => array(
				'desc' => esc_html_x( 'Freezing rain', 'Weather description', 'jet-elements' ),
				'icon' => 3,
			),
			'520' => array(
				'desc' => esc_html_x( 'Light shower rain', 'Weather description', 'jet-elements' ),
				'icon' => 3,
			),
			'521' => array(
				'desc' => esc_html_x( 'Shower rain', 'Weather description', 'jet-elements' ),
				'icon' => 4,
			),
			'522' => array(
				'desc' => esc_html_x( 'Heavy shower rain', 'Weather description', 'jet-elements' ),
				'icon' => 5,
			),

			// Snow
			'600' => array(
				'desc' => esc_html_x( 'Light snow', 'Weather description', 'jet-elements' ),
				'icon' => $is_day ? 6 : 7,
			),
			'601' => array(
				'desc' => esc_html_x( 'Snow', 'Weather description', 'jet-elements' ),
				'icon' => 7,
			),
			'602' => array(
				'desc' => esc_html_x( 'Heavy Snow', 'Weather description', 'jet-elements' ),
				'icon' => 8,
			),
			'610' => array(
				'desc' => esc_html_x( 'Mix snow/rain', 'Weather description', 'jet-elements' ),
				'icon' => 9,
			),
			'611' => array(
				'desc' => esc_html_x( 'Sleet', 'Weather description', 'jet-elements' ),
				'icon' => 10,
			),
			'612' => array(
				'desc' => esc_html_x( 'Heavy sleet', 'Weather description', 'jet-elements' ),
				'icon' => 10,
			),
			'621' => array(
				'desc' => esc_html_x( 'Snow shower', 'Weather description', 'jet-elements' ),
				'icon' => 7,
			),
			'622' => array(
				'desc' => esc_html_x( 'Heavy snow shower', 'Weather description', 'jet-elements' ),
				'icon' => 11,
			),
			'623' => array(
				'desc' => esc_html_x( 'Flurries', 'Weather description', 'jet-elements' ),
				'icon' => 7,
			),

			// Special
			'700' => array(
				'desc' => esc_html_x( 'Mist', 'Weather description', 'jet-elements' ),
				'icon' => 12,
			),
			'711' => array(
				'desc' => esc_html_x( 'Smoke', 'Weather description', 'jet-elements' ),
				'icon' => 13,
			),
			'721' => array(
				'desc' => esc_html_x( 'Haze', 'Weather description', 'jet-elements' ),
				'icon' => 12,
			),
			'731' => array(
				'desc' => esc_html_x( 'Sand/dust', 'Weather description', 'jet-elements' ),
				'icon' => 14,
			),
			'741' => array(
				'desc' => esc_html_x( 'Fog', 'Weather description', 'jet-elements' ),
				'icon' => 15,
			),
			'751' => array(
				'desc' => esc_html_x( 'Freezing Fog', 'Weather description', 'jet-elements' ),
				'icon' => 15,
			),

			// Clouds
			'800' => array(
				'desc' => esc_html_x( 'Clear sky', 'Weather description', 'jet-elements' ),
				'icon' => $is_day ? 16 : 17,
			),
			'801' => array(
				'desc' => esc_html_x( 'Few clouds', 'Weather description', 'jet-elements' ),
				'icon' => $is_day ? 18 : 17,
			),
			'802' => array(
				'desc' => esc_html_x( 'Scattered clouds', 'Weather description', 'jet-elements' ),
				'icon' => $is_day ? 18 : 17,
			),
			'803' => array(
				'desc' => esc_html_x( 'Broken clouds', 'Weather description', 'jet-elements' ),
				'icon' => 19,
			),
			'804' => array(
				'desc' => esc_html_x( 'Overcast clouds', 'Weather description', 'jet-elements' ),
				'icon' => 19,
			),
			'900' => array(
				'desc' => esc_html_x( 'Unknown Precipitation', 'Weather description', 'jet-elements' ),
				'icon' => 3,
			),
		) );

		if ( ! $code ) {
			return $conditions;
		}

		$code_key = (string) $code;

		if ( ! isset( $conditions[ $code_key ] ) ) {
			return false;
		}

		if ( $condition && isset( $conditions[ $code_key ][ $condition ] ) ) {
			return $conditions[ $code_key ][ $condition ];
		}

		return $conditions[ $code_key ];
	}

	/**
	 * Get weather description.
	 *
	 * @param int  $code   Weather code.
	 * @param bool $is_day Is day.
	 *
	 * @return string
	 */
	public function get_weather_desc( $code, $is_day = true ) {

		if ( ! $code ) {
			return '';
		}

		$desc = $this->get_weather_conditions( $code, 'desc', $is_day );

		if ( empty( $desc ) ) {
			return '';
		}

		return $desc;
	}

	/**
	 * Get week day from date.
	 *
	 * @param string $date Date.
	 *
	 * @return bool|string
	 */
	public function get_week_day_from_date( $date = '' ) {
		return date_i18n( 'l', strtotime( $date ) );
	}

	/**
	 * Get title html markup.
	 *
	 * @return string
	 */
	public function get_weather_title() {
		$settings   = $this->get_settings_for_display();
		$show_title = isset( $settings['show_title'] ) ? $settings['show_title'] : 'true';

		if ( ! filter_var( $show_title, FILTER_VALIDATE_BOOLEAN ) ) {
			return '';
		}

		$type = isset( $settings['title_type'] ) ? $settings['title_type'] : 'api';
		$tag  = isset( $settings['title_size'] ) ? jet_elements_tools()->validate_html_tag( $settings['title_size'] ) : 'h3';

		switch ( $type ) {
			case 'location':
				$title = esc_html( $settings['location'] );
				break;

			case 'custom':
				$title = esc_html( $settings['custom_title'] );
				break;

			default:
				$title = $this->weather_data['location']['city'];
		}

		if ( isset( $settings['show_country_name'] ) && 'true' === $settings['show_country_name'] ) {
			$country = $this->weather_data['location']['country'];

			$title = sprintf( '%1$s, %2$s', $title, $country );
		}

		return sprintf( '<%1$s class="jet-weather__title">%2$s</%1$s>', $tag, $title );
	}

	/**
	 * Get temperature html markup.
	 *
	 * @param int|array $temp Temperature value.
	 *
	 * @return string
	 */
	public function get_weather_temp( $temp ) {
		$units     = $this->get_settings_for_display( 'units' );
		$temp_unit = ( 'metric' === $units ) ? '&#176;C' : '&#176;F';

		// For 2.0 API Count
		if ( is_array( $temp ) ) {
			$temp = ( 'metric' === $units ) ? $temp['c'] : $temp['f'];
		}

		$format = apply_filters( 'jet-elements/weather/temperature-format', '%1$s%2$s' );

		return sprintf( $format, round( $temp ), $temp_unit );
	}

	/**
	 * Get wind.
	 *
	 * @param int|array  $speed Wind speed.
	 * @param int|string $deg   Wind direction, degrees.
	 *
	 * @return string
	 */
	public function get_wind( $speed, $deg ) {
		$units      = $this->get_settings_for_display( 'units' );
		$speed_unit = ( 'metric' === $units ) ? esc_html_x( 'm/s', 'Unit of speed (meters/second)', 'jet-elements' ) : esc_html_x( 'mph', 'Unit of speed (miles per hour)', 'jet-elements' );

		// For 2.0 API Count
		if ( is_array( $speed ) ) {
			$speed = ( 'metric' === $units ) ? $speed['kph'] : $speed['mph'];
		}

		$direction = '';

		if ( ! is_numeric( $deg ) ) {
			$direction = $deg;
		} else {
			if ( ( $deg >= 0 && $deg <= 11.25 ) || ( $deg > 348.75 && $deg <= 360 ) ) {
				$direction = esc_html_x( 'N', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 11.25 && $deg <= 33.75 ) {
				$direction = esc_html_x( 'NNE', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 33.75 && $deg <= 56.25 ) {
				$direction = esc_html_x( 'NE', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 56.25 && $deg <= 78.75 ) {
				$direction = esc_html_x( 'ENE', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 78.75 && $deg <= 101.25 ) {
				$direction = esc_html_x( 'E', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 101.25 && $deg <= 123.75 ) {
				$direction = esc_html_x( 'ESE', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 123.75 && $deg <= 146.25 ) {
				$direction = esc_html_x( 'SE', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 146.25 && $deg <= 168.75 ) {
				$direction = esc_html_x( 'SSE', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 168.75 && $deg <= 191.25 ) {
				$direction = esc_html_x( 'S', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 191.25 && $deg <= 213.75 ) {
				$direction = esc_html_x( 'SSW', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 213.75 && $deg <= 236.25 ) {
				$direction = esc_html_x( 'SW', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 236.25 && $deg <= 258.75 ) {
				$direction = esc_html_x( 'WSW', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 258.75 && $deg <= 281.25 ) {
				$direction = esc_html_x( 'W', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 281.25 && $deg <= 303.75 ) {
				$direction = esc_html_x( 'WNW', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 303.75 && $deg <= 326.25 ) {
				$direction = esc_html_x( 'NW', 'Wind direction', 'jet-elements' );
			} else if ( $deg > 326.25 && $deg <= 348.75 ) {
				$direction = esc_html_x( 'NNW', 'Wind direction', 'jet-elements' );
			}
		}

		$format = apply_filters( 'jet-elements/weather/wind-format', '%1$s %2$s %3$s' );

		return sprintf( $format, $direction, round( $speed ), $speed_unit );
	}

	/**
	 * Get weather pressure.
	 *
	 * @param int|array $pressure Pressure value.
	 *
	 * @return string
	 */
	public function get_weather_pressure( $pressure ) {
		$units = $this->get_settings_for_display( 'units' );

		// For 2.0 API Count
		if ( is_array( $pressure ) ) {
			$pressure = ( 'metric' === $units ) ? $pressure['mb'] : $pressure['in'];
		}

		$format = apply_filters( 'jet-elements/weather/pressure-format', '%s' );

		return sprintf( $format, round( $pressure ) );
	}

	/**
	 * Get weather astro time.
	 *
	 * @param  string $time
	 * @return string
	 */
	public function get_weather_astro_time( $time ) {
		$format = $this->get_settings_for_display( 'time_format' );

		if ( '12' === $format ) {
			$time = date( 'h:i A', strtotime( $time ) );
		}

		return $time;
	}

	/**
	 * Get weather notice html markup.
	 *
	 * @param string $message Message.
	 *
	 * @return string
	 */
	public function get_weather_notice( $message ) {
		return sprintf( '<div class="jet-weather-notice">%s</div>', $message );
	}

	/**
	 * Get weather svg icon.
	 *
	 * @param string|int $icon            Icon slug or weather code.
	 * @param bool       $is_weather_code Is weather code.
	 * @param bool       $is_day          Is day.
	 *
	 * @return bool|string
	 */
	public function get_weather_svg_icon( $icon, $is_weather_code = false, $is_day = true ) {

		if ( ! $icon ) {
			return false;
		}

		if ( $is_weather_code ) {
			$icon = $this->get_weather_conditions( $icon, 'icon', $is_day );
		}

		$icon_path = jet_elements()->plugin_path( "assets/images/weather-icons/{$icon}.svg" );

		if ( ! file_exists( $icon_path ) ) {
			return false;
		}

		ob_start();

		include $icon_path;

		$svg = ob_get_clean();

		$_classes   = array();
		$_classes[] = 'jet-weather-icon';
		$_classes[] = sprintf( 'jet-weather-icon-%s', esc_attr( $icon ) );

		$classes = join( ' ', $_classes );

		return sprintf( '<div class="%2$s">%1$s</div>', $svg, $classes );
	}
}
