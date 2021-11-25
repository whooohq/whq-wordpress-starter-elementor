<?php
/**
 * Class: Jet_Elements_Animated_Text
 * Name: Animated Text
 * Slug: jet-animated-text
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

class Jet_Elements_Animated_Text extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-animated-text';
	}

	public function get_title() {
		return esc_html__( 'Animated Text', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-animated-text';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-add-a-headline-to-elementor-with-jetelements-headline-and-animated-text-widgets/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {
		return array( 'jet-anime-js' );
	}

	protected function _register_controls() {

		$css_scheme = apply_filters(
			'jet-elements/jet-animated-text/css-scheme',
			array(
				'animated_text_instance' => '.jet-animated-text',
				'before_text'            => '.jet-animated-text__before-text',
				'animated_text'          => '.jet-animated-text__animated-text',
				'animated_text_item'     => '.jet-animated-text__animated-text-item',
				'after_text'             => '.jet-animated-text__after-text',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'Content', 'jet-elements' ),
			)
		);

		$this->add_control(
			'before_text_content',
			array(
				'label'   => esc_html__( 'Before Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Let us', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_text',
			array(
				'label'   => esc_html__( 'Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Create', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_text_direction',
			array(
				'label'   => esc_html__( 'Text Direction', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => array(
					'ltr' => esc_html__( 'LTR', 'jet-elements' ),
					'rtl' => esc_html__( 'RTL', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'animated_text_list',
			array(
				'type'    => Controls_Manager::REPEATER,
				'label'   => esc_html__( 'Animated Text', 'jet-elements' ),
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array(
						'item_text' => esc_html__( 'Create', 'jet-elements' ),
					),
					array(
						'item_text' => esc_html__( 'Animate', 'jet-elements' ),
					),
				),
				'title_field' => '{{{ item_text }}}',
			)
		);

		$this->add_control(
			'after_text_content',
			array(
				'label'   => esc_html__( 'After Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'your text', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'animated_text_link',
			array(
				'label'   => esc_html__( 'Link', 'jet-elements' ),
				'type'    => Controls_Manager::URL,
				'dynamic' => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'html_tag',
			array(
				'label'   => esc_html__( 'HTML tag', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'div',
				'options' => jet_elements_tools()->get_available_title_html_tags(),
				'separator' => 'before',
				'condition' => array(
					'animated_text_link[url]' => ''
				)
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
			'animation_effect',
			array(
				'label'   => esc_html__( 'Animation Effect', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fx1',
				'options' => array(
					'fx1'  => esc_html__( 'Joke', 'jet-elements' ),
					'fx2'  => esc_html__( 'Kinnect', 'jet-elements' ),
					'fx3'  => esc_html__( 'Circus', 'jet-elements' ),
					'fx4'  => esc_html__( 'Rotation fall', 'jet-elements' ),
					'fx5'  => esc_html__( 'Simple Fall', 'jet-elements' ),
					'fx6'  => esc_html__( 'Rotation', 'jet-elements' ),
					'fx7'  => esc_html__( 'Anime', 'jet-elements' ),
					'fx8'  => esc_html__( 'Label', 'jet-elements' ),
					'fx9'  => esc_html__( 'Croco', 'jet-elements' ),
					'fx10' => esc_html__( 'Scaling', 'jet-elements' ),
					'fx11' => esc_html__( 'Fun', 'jet-elements' ),
					'fx12' => esc_html__( 'Typing', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'animation_delay',
			array(
				'label'   => esc_html__( 'Animation delay', 'jet-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3000,
				'min'     => 500,
				'step'    => 100,
			)
		);

		$this->add_control(
			'split_type',
			array(
				'label'   => esc_html__( 'Split Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'symbol',
				'options' => array(
					'symbol' => esc_html__( 'Symbols', 'jet-elements' ),
					'word'   => esc_html__( 'Words', 'jet-elements' ),
				),
				'condition' => array(
					'animation_effect!' => 'fx12'
				)
			)
		);

		$this->add_responsive_control(
			'animated_text_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start' => array(
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
					'{{WRAPPER}} ' . $css_scheme['animated_text_instance'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->_start_controls_section(
			'section_before_text_style',
			array(
				'label'      => esc_html__( 'Before Text', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'before_text_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['before_text'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'before_text_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['before_text'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'before_text_typography',
				'label'    => esc_html__( 'Typography', 'jet-elements' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['before_text'],
			),
			50
		);

		$this->_add_responsive_control(
			'before_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['before_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_animated_text_style',
			array(
				'label'      => esc_html__( 'Animated Text', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'animated_text_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_text'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'animated_text_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_text'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'animated_text_cursor_color',
			array(
				'label' => esc_html__( 'Cursor Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_text_item'] . ':after' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'animation_effect' => 'fx12',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'animated_text_typography',
				'label'    => esc_html__( 'Typography', 'jet-elements' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_text'],
			),
			50
		);

		$this->_add_responsive_control(
			'animated_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['animated_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_after_text_style',
			array(
				'label'      => esc_html__( 'After Text', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_control(
			'after_text_color',
			array(
				'label' => esc_html__( 'Color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['after_text'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'after_text_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['after_text'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'after_text_typography',
				'label'    => esc_html__( 'Typography', 'jet-elements' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['after_text'],
			),
			50
		);

		$this->_add_responsive_control(
			'after_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['after_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();
	}

	/**
	 * Generate spenned html string
	 *
	 * @param  string $str Base text
	 * @return string
	 */
	public function str_to_spanned_html( $base_string, $split_type = 'symbol' ) {

		$module_settings = $this->get_settings_for_display();
		$symbols_array = array();
		$spanned_array = array();

		$base_words = explode( ' ', $base_string );

		if ( 'symbol' === $split_type ) {
			$glue = '';
			$symbols_array = $this->_string_split( $base_string );
		} else {
			$glue = '&nbsp;';
			$symbols_array = $base_words;
		}

		foreach ( $symbols_array as $symbol ) {

			if ( ' ' === $symbol ) {
				$symbol = '&nbsp;';
			}

			$spanned_array[] = sprintf( '<span>%s</span>', $symbol );
		}

		return implode( $glue, $spanned_array );
	}

	/**
	 * Split string
	 *
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	public function _string_split( $string ) {

		$strlen = mb_strlen( $string );
		$result = array();

		while ( $strlen ) {

			$result[] = mb_substr( $string, 0, 1, "UTF-8" );
			$string   = mb_substr( $string, 1, $strlen, "UTF-8" );
			$strlen   = mb_strlen( $string );

		}

		return $result;
	}

	/**
	 * Generate setting json
	 *
	 * @return string
	 */
	public function generate_setting_json() {
		$module_settings = $this->get_settings_for_display();

		$settings = array(
			'effect' => $module_settings['animation_effect'],
			'delay'  => $module_settings['animation_delay'],
		);

		$settings = json_encode( $settings );

		return sprintf( 'data-settings=\'%1$s\'', $settings );
	}

	protected function render() {
		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}
}
