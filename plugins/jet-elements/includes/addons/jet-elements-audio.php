<?php
/**
 * Class: Jet_Elements_Audio
 * Name: Audio Player
 * Slug: jet-audio
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

class Jet_Elements_Audio extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-audio';
	}

	public function get_title() {
		return esc_html__( 'Audio Player', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-audio';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetelements-audio-player-widget-how-to-add-audio-content-to-your-website/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		if ( isset( $_GET['elementor-preview'] ) && 'wp_enqueue_scripts' === current_filter() ) {
			return array( 'mediaelement', 'mejs-speed' );
		} else if ( 'yes' === $this->get_settings( 'speed' ) ){
			return array( 'mediaelement', 'mejs-speed' );
		} else {
			return array( 'mediaelement' );
		}
	}

	public function get_style_depends() {
		if ( isset( $_GET['elementor-preview'] ) ) {
			return array( 'mediaelement', 'mejs-speed-css', 'elementor-icons-fa-solid' );
		} else if ( 'yes' === $this->get_settings( 'speed' ) ){
			return array( 'mediaelement', 'mejs-speed-css', 'elementor-icons-fa-solid' );
		} else {
			return array( 'mediaelement', 'elementor-icons-fa-solid' );
		}
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/audio/css-scheme',
			array(
				'play_pause_btn_wrap' => '.jet-audio .mejs-playpause-button',
				'play_pause_btn'      => '.jet-audio .mejs-playpause-button > button',
				'time'                => '.jet-audio .mejs-time',
				'current_time'        => '.jet-audio .mejs-currenttime',
				'duration_time'       => '.jet-audio .mejs-duration',
				'rail_progress'       => '.jet-audio .mejs-time-rail',
				'total_progress'      => '.jet-audio .mejs-time-total',
				'current_progress'    => '.jet-audio .mejs-time-current',
				'volume_btn_wrap'     => '.jet-audio .mejs-volume-button',
				'volume_btn'          => '.jet-audio .mejs-volume-button > button',
				'volume_slider_hor'   => '.jet-audio .mejs-horizontal-volume-slider',
				'total_volume_hor'    => '.jet-audio .mejs-horizontal-volume-total',
				'current_volume_hor'  => '.jet-audio .mejs-horizontal-volume-current',
				'total_volume_vert'   => '.jet-audio .mejs-volume-total',
				'current_volume_vert' => '.jet-audio .mejs-volume-current',
				'volume_slider_vert'  => '.jet-audio .mejs-volume-slider',
				'volume_handle_vert'  => '.jet-audio .mejs-volume-handle',
				'speed_btn_wrap'      => '.jet-audio .mejs-speed-button',
				'speed_btn'           => '.jet-audio .mejs-speed-button button',
				'speed_selector'      => '.jet-audio .mejs-speed-selector',
				'speed_selector_item' => '.jet-audio .mejs-speed-selector-list-item',
				'speed_selector_label' => '.jet-audio .mejs-speed-selector-label',
			)
		);

		/**
		 * `Audio` Section
		 */
		$this->start_controls_section(
			'section_audio',
			array(
				'label' => esc_html__( 'Audio', 'jet-elements' ),
			)
		);

		$this->add_control(
			'audio_source',
			array(
				'label'   => esc_html__( 'Audio Source', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'self',
				'options' => array(
					'self'     => esc_html__( 'Self Hosted', 'jet-elements' ),
					'external' => esc_html__( 'External', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'self_url',
			array(
				'label' => esc_html__( 'URL', 'jet-elements' ),
				'type'  => Controls_Manager::MEDIA,
				'media_type' => 'audio',
				'condition' => array(
					'audio_source' => 'self',
				),
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
						TagsModule::MEDIA_CATEGORY,
					),
				),
			)
		);

		$this->add_control(
			'external_url',
			array(
				'label'       => esc_html__( 'URL', 'jet-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your URL', 'jet-elements' ),
				'condition' => array(
					'audio_source' => 'external',
				),
				'dynamic' => array(
					'active' => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
			)
		);

		$this->add_control(
			'audio_support_desc',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => esc_html__( 'Audio Player support MP3 audio format', 'jet-elements' ),
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->add_control(
			'audio_options_heading',
			array(
				'label' => esc_html__( 'Audio Options', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'loop',
			array(
				'label' => esc_html__( 'Loop', 'jet-elements' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'muted',
			array(
				'label' => esc_html__( 'Muted', 'jet-elements' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'audio_controls_options_heading',
			array(
				'label' => esc_html__( 'Controls Options', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'progress',
			array(
				'label'   => esc_html__( 'Progress Bar', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'   => esc_html__( 'Playback rate', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'current',
			array(
				'label'   => esc_html__( 'Current Time', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'duration',
			array(
				'label'   => esc_html__( 'Duration Time', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'volume',
			array(
				'label'   => esc_html__( 'Volume', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'hide_volume_on_touch_devices',
			array(
				'label'   => esc_html__( 'Hide Volume On Touch Devices', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => array(
					'volume' => 'yes',
				),
			)
		);

		$this->add_control(
			'volume_bar',
			array(
				'label'   => esc_html__( 'Volume Bar', 'jet-elements' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'render_type' => 'template',
				'selectors_dictionary' => array(
					'' => 'display: none !important;',
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-audio .mejs-volume-slider' => '{{VALUE}}',
					'{{WRAPPER}} .jet-audio .mejs-horizontal-volume-slider' => '{{VALUE}}',
				),
				'condition' => array(
					'volume' => 'yes',
				),
			)
		);

		$this->add_control(
			'volume_bar_layout',
			array(
				'label'   => esc_html__( 'Volume Bar Layout', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'jet-elements' ),
					'vertical'   => esc_html__( 'Vertical', 'jet-elements' ),
				),
				'condition' => array(
					'volume'     => 'yes',
					'volume_bar' => 'yes',
				),
			)
		);

		$this->add_control(
			'start_volume',
			array(
				'label'       => esc_html__( 'Start Volume', 'jet-elements' ),
				'description' => esc_html__( 'Initial volume when the player starts. Override by user cookie.', 'jet-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range' => array(
					'%' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default' => array(
					'unit' => '%',
					'size' => 0.8,
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
			'width',
			array(
				'label' => esc_html__( 'Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 1200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'align',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Start', 'jet-elements' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'End', 'jet-elements' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'selectors_dictionary' => array(
					'left'   => 'justify-content: flex-start;',
					'center' => 'justify-content: center;',
					'right'  => 'justify-content: flex-end;',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'classes' => 'jet-elements-arrows-align-control',
			),
			25
		);

		$this->_end_controls_section();

		/**
		 * `Play-Pause Button and Time` Style Section
		 */
		$this->_start_controls_section(
			'section_play_button_and_time_style',
			array(
				'label' => esc_html__( 'Play-Pause Button and Time', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->_add_control(
			'play_pause_button_heading',
			array(
				'label' => esc_html__( 'Play-Pause Button', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
			),
			25
		);

		$this->_add_control(
			'play_pause_button_font_size',
			array(
				'label' => esc_html__( 'Font size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['play_pause_btn'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			),
			50
		);

		$this->_start_controls_tabs( 'play_pause_button_style' );
		
		$this->_start_controls_tab(
			'play_pause_button_normal_style',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);
		
		$this->_add_control(
			'play_pause_button_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['play_pause_btn'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'play_pause_button_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['play_pause_btn'] => 'background-color: {{VALUE}};',
				),
			),
			25
		);
		
		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'play_pause_button_hover_style',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->_add_control(
			'play_pause_button_hover_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['play_pause_btn'] . ':hover' => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'play_pause_button_hover_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['play_pause_btn'] . ':hover' => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_control(
			'play_pause_button_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['play_pause_btn'] . ':hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'play_pause_button_border_border!' => '',
				),
			),
			75
		);

		$this->_end_controls_tab();
		
		$this->_end_controls_tabs();
		
		$this->_add_responsive_control(
			'play_pause_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['play_pause_btn'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			),
			25
		);

		$this->_add_responsive_control(
			'play_pause_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['play_pause_btn_wrap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);
		
		$this->_add_control(
			'play_pause_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['play_pause_btn'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);
		
		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'play_pause_button_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['play_pause_btn'],
			),
			75
		);
		
		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'play_pause_button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['play_pause_btn'],
			),
			100
		);

		$this->_add_control(
			'time_heading',
			array(
				'label'     => esc_html__( 'Time', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'time_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['time'],
			),
			50
		);

		$this->_add_control(
			'time_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['time'] => 'color: {{VALUE}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'current_time_margin',
			array(
				'label'      => esc_html__( 'Current Time Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_time'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'duration_time_margin',
			array(
				'label'      => esc_html__( 'Duration Time Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['duration_time'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_progress_style',
			array(
				'label' => esc_html__( 'Progress', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'progress' => 'yes',
				),
			)
		);

		$this->_add_control(
			'total_progress_heading',
			array(
				'label'     => esc_html__( 'Total Progress Bar', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'progress' => 'yes',
				),
			),
			25
		);
		
		$this->_add_control(
			'total_progress_height',
			array(
				'label' => esc_html__( 'Height', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['total_progress'] => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'progress' => 'yes',
				),
			),
			25
		);
		
		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'total_progress_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['total_progress'],
				'condition' => array(
					'progress' => 'yes',
				),
			),
			25
		);
		
		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'total_progress_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['total_progress'],
				'condition' => array(
					'progress' => 'yes',
				),
			),
			75
		);

		$this->_add_control(
			'total_progress_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['total_progress'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'progress' => 'yes',
				),
			),
			75
		);

		$this->_add_responsive_control(
			'rail_progress_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['rail_progress'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'progress' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'current_progress_heading',
			array(
				'label'     => esc_html__( 'Current Progress Bar', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'progress' => 'yes',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'current_progress_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_progress'],
				'condition' => array(
					'progress' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'current_progress_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_progress'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'progress' => 'yes',
				),
			),
			75
		);

		$this->_end_controls_section();

		/**
		 * `Volume` Style Section
		 */
		$this->_start_controls_section(
			'section_volume_style',
			array(
				'label' => esc_html__( 'Volume', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'volume' => 'yes',
				),
			)
		);

		$this->_add_control(
			'volume_button_style_heading',
			array(
				'label' => esc_html__( 'Volume Button', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
				'condition' => array(
					'volume' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'volume_button_font_size',
			array(
				'label' => esc_html__( 'Font size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['volume_btn'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'volume' => 'yes',
				),
			),
			50
		);

		$this->_start_controls_tabs( 'volume_button_style' );

		$this->_start_controls_tab(
			'volume_button_normal_style',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
				'condition' => array(
					'volume' => 'yes',
				),
			)
		);

		$this->_add_control(
			'volume_button_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['volume_btn'] => 'color: {{VALUE}};',
				),
				'condition' => array(
					'volume' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'volume_button_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['volume_btn'] => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'volume' => 'yes',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'volume_button_hover_style',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
				'condition' => array(
					'volume' => 'yes',
				),
			)
		);

		$this->_add_control(
			'volume_button_hover_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['volume_btn'] . ':hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'volume' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'volume_button_hover_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['volume_btn'] . ':hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'volume' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'volume_button_hover_border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['volume_btn'] . ':hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'volume_button_border_border!' => '',
					'volume' => 'yes',
				),
			),
			75
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_responsive_control(
			'volume_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['volume_btn'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
				'condition' => array(
					'volume' => 'yes',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'volume_button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['volume_btn_wrap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'volume' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'volume_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['volume_btn'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'volume' => 'yes',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'volume_button_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['volume_btn'],
				'condition' => array(
					'volume' => 'yes',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'volume_button_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['volume_btn'],
				'condition' => array(
					'volume' => 'yes',
				),
			),
			100
		);

		$this->_add_control(
			'volume_slider_style_heading',
			array(
				'label' => esc_html__( 'Volume Slider', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'volume_slider_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['volume_slider_vert'] => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
					'volume_bar_layout' => 'vertical',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'volume_slider_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['volume_slider_hor'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
					'volume_bar_layout' => 'horizontal',
				),
			),
			25
		);

		$this->_add_control(
			'total_volume_bar_style_heading',
			array(
				'label' => esc_html__( 'Total Volume Bar', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'total_volume_hor_width',
			array(
				'label' => esc_html__( 'Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['total_volume_hor'] => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
					'volume_bar_layout' => 'horizontal',
				),
			),
			25
		);

		$this->_add_control(
			'total_volume_hor_height',
			array(
				'label' => esc_html__( 'Height', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['total_volume_hor'] => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
					'volume_bar_layout' => 'horizontal',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'total_volume_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['total_volume_hor'] . ', {{WRAPPER}} ' . $css_scheme['total_volume_vert'],
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'total_volume_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['total_volume_hor'],
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
					'volume_bar_layout' => 'horizontal',
				),
			),
			75
		);

		$this->_add_control(
			'total_volume_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['total_volume_hor'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
					'volume_bar_layout' => 'horizontal',
				),
			),
			75
		);

		$this->_add_control(
			'current_volume_heading',
			array(
				'label'     => esc_html__( 'Current Volume Bar', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'current_volume_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['current_volume_hor'] . ', {{WRAPPER}} ' . $css_scheme['current_volume_vert'],
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'current_volume_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['current_volume_hor'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
					'volume_bar_layout' => 'horizontal',
				),
			),
			75
		);

		$this->_add_control(
			'volume_handle_style_heading',
			array(
				'label' => esc_html__( 'Volume Handle', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
					'volume_bar_layout' => 'vertical',
				),
			),
			25
		);

		$this->_add_control(
			'volume_handle_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['volume_handle_vert'] => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'volume' => 'yes',
					'volume_bar' => 'yes',
					'volume_bar_layout' => 'vertical',
				),
			),
			25
		);

		$this->_end_controls_section();

		/**
		 * `Playback rate` Style Section
		 */
		$this->_start_controls_section(
			'section_playback_rate_style',
			array(
				'label' => esc_html__( 'Playback rate', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'speed' => 'yes',
				),
			)
		);

		$this->_add_control(
			'playback_rate_btn',
			array(
				'label' => esc_html__( 'Playback Rate Button', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
				'condition' => array(
					'speed' => 'yes',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'playback_rate_btn_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['speed_btn'],
				'condition' => array(
					'speed' => 'yes',
				),
			),
			50
		);

		$this->_add_control(
			'playback_rate_btn_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['speed_btn'] => 'color: {{VALUE}};',
				),
				'condition' => array(
					'speed' => 'yes',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'playback_rate_btn_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['speed_btn_wrap'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'speed' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'playback_rate_list',
			array(
				'label' => esc_html__( 'Playback Rate Selector List', 'jet-elements' ),
				'type'  => Controls_Manager::HEADING,
				'condition' => array(
					'speed' => 'yes',
				),
			),
			25
		);

		$this->_start_controls_tabs( 'playback_rate_list_style' );

		$this->_start_controls_tab(
			'playback_rate_selector_normal_style',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
				'condition' => array(
					'speed' => 'yes',
				),
			)
		);

		$this->_add_control(
			'playback_rate_list_label_normal_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['speed_selector_label'] => 'color: {{VALUE}};',
				),
				'condition' => array(
					'speed' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'playback_rate_list_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['speed_selector'] => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'speed' => 'yes',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'playback_rate_selector_hover_style',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
				'condition' => array(
					'speed' => 'yes',
				),
			)
		);

		$this->_add_control(
			'playback_rate_list_label_hover_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['speed_selector_label'] . ':hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['speed_selector_label'] . '.mejs-speed-selected' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'speed' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'playback_rate_list_label_hover_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['speed_selector_item'] . ':hover' => 'background-color: {{VALUE}} !important;',
				),
				'condition' => array(
					'speed' => 'yes',
				),
			),
			25
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_responsive_control(
			'playback_rate_list_items_gap',
			array(
				'label' => esc_html__( 'Items Gap', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['speed_selector_item'] . ' + *' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'speed' => 'yes',
				),
				'separator' => 'before',
			),
			25
		);

		$this->_add_responsive_control(
			'playback_rate_list_width',
			array(
				'label' => esc_html__( 'List Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['speed_selector'] => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'speed' => 'yes',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'playback_rate_list_typography',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['speed_selector_label'],
				'separator' => 'before',
				'condition' => array(
					'speed' => 'yes',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'playback_rate_list_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['speed_selector'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'speed' => 'yes',
				),
			),
			25
		);

		$this->_add_control(
			'playback_rate_list_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['speed_selector'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'speed' => 'yes',
				),
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'playback_rate_list_border',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['speed_selector'],
				'condition' => array(
					'speed' => 'yes',
				),
			),
			75
		);

		$this->_end_controls_section();
	}

	protected function render() {
		$settings     = $this->get_settings_for_display();
		$audio_url    = '';
		$audio_source = $settings['audio_source'];

		switch ( $audio_source ) :
			case 'self':
				$audio_url = is_array( $settings['self_url'] ) ? $settings['self_url']['url'] : $settings['self_url'];
				break;

			case 'external':
				$audio_url = $settings['external_url'];
				break;

		endswitch;

		if ( is_numeric( $audio_url ) ) {
			$audio_url = wp_get_attachment_url( $audio_url );
		}

		if ( empty( $audio_url ) ) {
			return;
		}

		$controls = array( 'playpause' );
		$available_controls = array( 'current', 'progress', 'duration', 'volume', 'speed' );

		foreach ( $available_controls as $control ) {
			if ( isset( $settings[ $control ] ) && filter_var( $settings[ $control ], FILTER_VALIDATE_BOOLEAN ) ) {
				$controls[] = $control;
			}
		}

		$data_settings = array();
		$data_settings['controls'] = $controls;

		if ( ! empty( $settings['volume_bar_layout'] ) ) {
			$data_settings['audioVolume'] = $settings['volume_bar_layout'];
		}

		if ( ! empty( $settings['start_volume']['size'] ) ) {
			$data_settings['startVolume'] = ( abs( $settings['start_volume']['size'] ) > 1 ) ? 1 : abs( $settings['start_volume']['size'] );
		}

		if ( isset( $settings['muted'] ) && filter_var( $settings['muted'], FILTER_VALIDATE_BOOLEAN ) ) {
			$data_settings['muted'] = $settings['muted'];
		}

		if ( isset( $settings['volume_bar'] ) && filter_var( $settings['volume_bar'], FILTER_VALIDATE_BOOLEAN ) ) {
			$data_settings['hasVolumeBar'] = $settings['volume_bar'];
		}

		$data_settings['hideVolumeOnTouchDevices'] = isset( $settings['hide_volume_on_touch_devices'] ) ? filter_var( $settings['hide_volume_on_touch_devices'], FILTER_VALIDATE_BOOLEAN ) : true;

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'               => 'jet-audio',
				'data-audio-settings' => esc_attr( json_encode( $data_settings ) ),
			)
		);

		if ( jet_elements_tools()->is_fa5_migration() ) {
			$this->add_render_attribute( 'wrapper', 'class', 'jet-audio--fa5-compat' );
		}

		$this->add_render_attribute(
			'player',
			array(
				'class'    => 'jet-audio-player',
				'preload'  => 'none',
				'controls' => '',
				'src'      => esc_url( $audio_url ),
				'width'    => '100%',
			)
		);

		if ( isset( $settings['loop'] ) && filter_var( $settings['loop'], FILTER_VALIDATE_BOOLEAN ) ) {
			$this->add_render_attribute( 'player', 'loop', '' );
		}

		if ( isset( $settings['muted'] ) && filter_var( $settings['muted'], FILTER_VALIDATE_BOOLEAN ) ) {
			$this->add_render_attribute( 'player', 'muted', '' );
		}

		$this->_open_wrap();
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<audio <?php $this->print_render_attribute_string( 'player' ); ?>></audio>
		</div>

		<?php
		$this->_close_wrap();
	}
}
