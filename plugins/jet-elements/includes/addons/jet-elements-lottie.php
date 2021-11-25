<?php
/**
 * Class: Jet_Elements_Lottie
 * Name: Lottie
 * Slug: jet-lottie
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Lottie extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-lottie';
	}

	public function get_title() {
		return esc_html__( 'Lottie', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-lottie';
	}

	public function get_jet_help_url() {
		return false;
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'jet-lottie' );
	}

	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/lottie/css-scheme',
			array(
				'wrap' => '.jet-lottie',
				'link' => '.jet-lottie__link',
			)
		);

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'jet-elements' ),
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => esc_html__( 'Source', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'json_file' => array(
						'title' => esc_html__( 'JSON file', 'jet-elements' ),
						'icon'  => 'fas fa-file',
					),
					'external_url' => array(
						'title' => esc_html__( 'External URL', 'jet-elements' ),
						'icon'  => 'fas fa-external-link-alt',
					),
				),
				'default' => 'json_file',
				'toggle'  => false,
			)
		);

		$this->add_control(
			'json_file',
			array(
				'label'      => esc_html__( 'JSON File', 'jet-elements' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'application/json',
				'condition'  => array(
					'source' => 'json_file',
				),
			)
		);

		$this->add_control(
			'external_url',
			array(
				'label'       => esc_html__( 'External URL', 'jet-elements' ),
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter your URL', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active'     => true,
					'categories' => array( TagsModule::URL_CATEGORY ),
				),
				'condition'   => array(
					'source' => 'external_url',
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'     => esc_html__( 'Link', 'jet-elements' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => array( 'active' => true ),
				'separator' => 'before',
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
			'renderer',
			array(
				'label'   => esc_html__( 'Renderer', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'svg',
				'options' => array(
					'svg'    => 'SVG',
					'canvas' => 'Canvas',
				),
			)
		);

		$this->add_control(
			'action_start',
			array(
				'label'   => esc_html__( 'Play Action', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'autoplay',
				'options' => array(
					'autoplay'    => esc_html__( 'Autoplay', 'jet-elements' ),
					'on_hover'    => esc_html__( 'On Hover', 'jet-elements' ),
					'on_click'    => esc_html__( 'On Click', 'jet-elements' ),
					'on_scroll'   => esc_html__( 'Scroll', 'jet-elements' ),
					'on_viewport' => esc_html__( 'Viewport', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'delay',
			array(
				'label'     => esc_html__( 'Autoplay Delay (ms)', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'step'      => 1,
				'condition' => array(
					'action_start' => 'autoplay',
				),
			)
		);

		$this->add_control(
			'on_hover_out',
			array(
				'label'   => esc_html__( 'On Hover Out', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'No Action', 'jet-elements' ),
					'pause'   => esc_html__( 'Pause', 'jet-elements' ),
					'stop'    => esc_html__( 'Stop', 'jet-elements' ),
					'reverse' => esc_html__( 'Reverse', 'jet-elements' ),
				),
				'condition' => array(
					'action_start' => 'on_hover',
				),
			)
		);

		$this->add_control(
			'redirect_timeout',
			array(
				'label'     => esc_html__( 'Redirect Timeout (ms)', 'jet-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'step'      => 1,
				'condition' => array(
					'action_start' => 'on_click',
					'link[url]!'   => '',
				),
			)
		);

		$this->add_control(
			'viewport',
			array(
				'label'   => esc_html__( 'Viewport', 'jet-elements' ),
				'type'    => Controls_Manager::SLIDER,
				'scales'  => 1,
				'handles' => 'range',
				'default' => array(
					'sizes' => array(
						'start' => 0,
						'end'   => 100,
					),
					'unit'  => '%',
				),
				'labels'  => array(
					esc_html__( 'Bottom', 'jet-elements' ),
					esc_html__( 'Top', 'jet-elements' ),
				),
				'condition' => array(
					'action_start' => array( 'on_viewport', 'on_scroll' ),
				),
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'     => esc_html__( 'Loop', 'jet-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'action_start!' => 'on_scroll',
				),
			)
		);

		$this->add_control(
			'loop_times',
			array(
				'label'       => esc_html__( 'Loop Times', 'jet-elements' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'step'        => 1,
				'default'     => '',
				'condition'   => array(
					'loop'          => 'yes',
					'action_start!' => 'on_scroll',
				),
			)
		);

		$this->add_control(
			'reversed',
			array(
				'label'     => esc_html__( 'Reversed', 'jet-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'action_start!' => 'on_scroll',
				),
			)
		);

		$this->add_control(
			'play_speed',
			array(
				'label'       => esc_html__( 'Play Speed', 'jet-elements' ),
				'description' => esc_html__( '1 is normal speed', 'jet-elements' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'step'        => 0.1,
				'default'     => 1,
				'condition'   => array(
					'action_start!' => 'on_scroll',
				),
			)
		);

		$this->end_controls_section();
		
		/**
		 * `Lottie` Style Section
		 */
		$this->start_controls_section(
			'section_lottie_style',
			array(
				'label' => esc_html__( 'Lottie', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'lottie_align',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
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
				),
				'prefix_class' => 'elementor%s-align-',
				'classes'      => 'jet-elements-text-align-control',
			)
		);

		$this->add_responsive_control(
			'lottie_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['wrap'] => 'width: {{SIZE}}{{UNIT}};'
				),
			)
		);

		$this->add_responsive_control(
			'lottie_max_width',
			array(
				'label'      => esc_html__( 'Max Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['wrap'] => 'max-width: {{SIZE}}{{UNIT}};'
				),
			)
		);

		$this->add_control(
			'separator',
			array(
				'type'  => Controls_Manager::DIVIDER,
			)
		);

		$this->start_controls_tabs( 'tabs_lottie' );

		$this->start_controls_tab(
			'tab_lottie_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'opacity',
			array(
				'label' => esc_html__( 'Opacity', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['wrap'] => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} ' . $css_scheme['wrap'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_lottie_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'opacity_hover',
			array(
				'label' => esc_html__( 'Opacity', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['wrap'] . ':hover' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['wrap'] . ':hover',
			)
		);

		$this->add_control(
			'hover_transition',
			array(
				'label' => esc_html__( 'Transition Duration', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['wrap'] => 'transition-duration: {{SIZE}}s;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'lottie_border',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['wrap'],
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'lottie_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['wrap'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'    => 'lottie_box_shadow',
				'exclude' => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['wrap'],
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$this->_context = 'render';

		$settings = $this->get_settings_for_display();
		$source   = ! empty( $settings['source'] ) ? $settings['source'] : 'json_file';

		switch ( $source ) {
			case 'json_file';
				$path = esc_url( $settings['json_file']['url'] );
				break;

			case 'external_url';
				$path = esc_url( $settings['external_url'] );
				break;

			default:
				$path = '';
		};

		if ( empty( $path ) ) {
			$path = esc_url( jet_elements()->plugin_url( 'assets/animation/lottie-default.json' ) );
		}

		$data = array(
			'path'             => $path,
			'renderer'         => $settings['renderer'],
			'action_start'     => $settings['action_start'],
			'delay'            => $settings['delay'],
			'on_hover_out'     => $settings['on_hover_out'],
			'redirect_timeout' => $settings['redirect_timeout'],
			'viewport'         => isset( $settings['viewport'] ) ? $settings['viewport']['sizes'] : '',
			'loop'             => filter_var( $settings['loop'], FILTER_VALIDATE_BOOLEAN ),
			'loop_times'       => ! empty( $settings['loop_times'] ) ? $settings['loop_times'] : '',
			'reversed'         => filter_var( $settings['reversed'], FILTER_VALIDATE_BOOLEAN ),
			'play_speed'       => $settings['play_speed'],
		);

		$inner = '<div class="jet-lottie__elem"></div>';

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->_add_link_attributes( 'link', $settings['link'] );
			$inner = sprintf( '<a class="jet-lottie__link" %2$s>%1$s</a>', $inner, $this->get_render_attribute_string( 'link' ) );
		}

		$this->_open_wrap();

		printf( '<div class="jet-lottie" data-settings="%2$s">%1$s</div>', $inner, esc_attr( json_encode( $data ) ) );

		$this->_close_wrap();
	}
}
