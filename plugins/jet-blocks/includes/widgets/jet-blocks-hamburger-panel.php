<?php
/**
 * Class: Jet_Blocks_Hamburger_Panel
 * Name: Hamburger Panel
 * Slug: jet-hamburger-panel
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

class Jet_Blocks_Hamburger_Panel extends Jet_Blocks_Base {

	public function get_name() {
		return 'jet-hamburger-panel';
	}

	public function get_title() {
		return esc_html__( 'Hamburger Panel', 'jet-blocks' );
	}

	public function get_icon() {
		return 'jet-blocks-icon-hamburger-panel';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetblocks-how-to-display-any-template-within-a-hamburger-panel/';
	}

	public function get_categories() {
		return array( 'jet-blocks' );
	}

	protected function register_controls() {
		$css_scheme = apply_filters(
			'jet-blocks/hamburger-panel/css-scheme',
			array(
				'panel'    => '.jet-hamburger-panel',
				'instance' => '.jet-hamburger-panel__instance',
				'cover'    => '.jet-hamburger-panel__cover',
				'inner'    => '.jet-hamburger-panel__inner',
				'content'  => '.jet-hamburger-panel__content',
				'close'    => '.jet-hamburger-panel__close-button',
				'toggle'   => '.jet-hamburger-panel__toggle',
				'icon'     => '.jet-hamburger-panel__icon',
				'label'    => '.jet-hamburger-panel__toggle-label',
			)
		);

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'jet-blocks' ),
			)
		);

		$this->__add_advanced_icon_control(
			'panel_toggle_icon',
			array(
				'label'       => esc_html__( 'Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'default'     => 'fa fa-align-justify',
				'fa5_default' => array(
					'value'   => 'fas fa-align-justify',
					'library' => 'fa-solid',
				),
			)
		);

		$this->__add_advanced_icon_control(
			'panel_toggle_active_icon',
			array(
				'label'       => esc_html__( 'Active Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'default'     => 'fa fa-close',
				'fa5_default' => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
			)
		);

		$this->__add_advanced_icon_control(
			'panel_close_icon',
			array(
				'label'       => esc_html__( 'Close Icon', 'jet-blocks' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => false,
				'skin'        => 'inline',
				'file'        => '',
				'default'     => 'fa fa-close',
				'fa5_default' => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'panel_toggle_label',
			array(
				'label'   => esc_html__( 'Label', 'jet-blocks' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'More', 'jet-blocks' ),
			)
		);

		$this->add_responsive_control(
			'panel_toggle_label_alignment',
			array(
				'label'   => esc_html__( 'Toggle Alignment', 'jet-blocks' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'jet-blocks' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-left' : 'eicon-h-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-blocks' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'jet-blocks' ),
						'icon'  => ! is_rtl() ? 'eicon-h-align-right' : 'eicon-h-align-left',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['panel'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'panel_template_id',
			array(
				'label'       => esc_html__( 'Choose Template', 'jet-blocks' ),
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
				'edit_button' => array(
					'active' => true,
					'label'  => __( 'Edit Template', 'jet-blocks' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-blocks' ),
			)
		);

		$this->add_control(
			'position',
			array(
				'label'       => esc_html__( 'Position', 'jet-blocks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'right',
				'options' => array(
					'right' => esc_html__( 'Right', 'jet-blocks' ),
					'left'  => esc_html__( 'Left', 'jet-blocks' ),
				),
			)
		);

		$this->add_control(
			'animation_effect',
			array(
				'label'       => esc_html__( 'Effect', 'jet-blocks' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'slide',
				'options' => array(
					'slide' => esc_html__( 'Slide', 'jet-blocks' ),
					'fade'  => esc_html__( 'Fade', 'jet-blocks' ),
					'zoom'  => esc_html__( 'Zoom', 'jet-blocks' ),
				),
			)
		);

		$this->add_control(
			'z_index',
			array(
				'label'   => esc_html__( 'Z-Index', 'jet-blocks' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 100000,
				'step'    => 1,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'z-index: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ajax_template',
			array(
				'label'        => esc_html__( 'Use Ajax Loading for Template', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'jet-blocks' ),
				'label_off'    => esc_html__( 'Off', 'jet-blocks' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'ajax_template_cache',
			array(
				'label'        => esc_html__( 'Use Cache for Template', 'jet-blocks' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'jet-blocks' ),
				'label_off'    => esc_html__( 'Off', 'jet-blocks' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'ajax_template' => 'yes',
				)
			)
		);

		$this->end_controls_section();

		$this->__start_controls_section(
			'section_panel_style',
			array(
				'label'      => esc_html__( 'Panel', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'panel_width',
			array(
				'label'      => esc_html__( 'Panel Width', 'jet-blocks' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 250,
						'max' => 800,
					),
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['instance'] => 'width: {{SIZE}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_responsive_control(
			'panel_padding',
			array(
				'label'      => __( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'panel_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'panel_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['inner'],
			),
			75
		);

		$this->__add_control(
			'panel_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'panel_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['inner'],
			),
			75
		);

		$this->__add_control(
			'cover_style_heading',
			array(
				'label'     => esc_html__( 'Cover', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__add_control(
			'cover_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['cover'] => 'background-color: {{VALUE}};',
				),
			),
			25
		);

		$this->__add_control(
			'close_button_style_heading',
			array(
				'label'     => esc_html__( 'Close Button', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__start_controls_tabs( 'close_button_styles' );

		$this->__start_controls_tab(
			'close_button_control',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->__add_group_control(
			\Jet_Blocks_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Close Icon', 'jet-blocks' ),
				'name'     => 'close_icon_box',
				'selector' => '{{WRAPPER}} ' . $css_scheme['close'],
			),
			25
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'close_button_control_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->__add_group_control(
			\Jet_Blocks_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Close Icon', 'jet-blocks' ),
				'name'     => 'close_icon_box_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['close'] . ':hover',
			),
			25
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__add_control(
			'content_loader_style_heading',
			array(
				'label'     => esc_html__( 'Loader Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'ajax_template' => 'yes',
				)
			),
			25
		);

		$this->__add_control(
			'content_loader_color',
			array(
				'label' => esc_html__( 'Loader color', 'jet-blocks' ),
				'type'  => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['content'] . ' .jet-hamburger-panel-loader' => 'border-color: {{VALUE}}; border-top-color: white;',
				),
				'condition' => array(
					'ajax_template' => 'yes',
				)
			),
			25
		);

		$this->__end_controls_section();

		$this->__start_controls_section(
			'section_panel_toggle_style',
			array(
				'label'      => esc_html__( 'Toggle', 'jet-blocks' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__start_controls_tabs( 'toggle_styles' );

		$this->__start_controls_tab(
			'toggle_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'toggle_background',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
					),
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'],
			),
			25
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'toggle_tab_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->__add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'toggle_background_hover',
				'fields_options' => array(
					'color' => array(
						'scheme' => array(
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						),
					),
				),
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover',
			),
			25
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__add_responsive_control(
			'toggle_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			),
			25
		);

		$this->__add_responsive_control(
			'toggle_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'toggle_border',
				'label'       => esc_html__( 'Border', 'jet-blocks' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['toggle'],
			),
			75
		);

		$this->__add_control(
			'toggle_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-blocks' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			75
		);

		$this->__add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'toggle_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'],
			),
			100
		);

		$this->__add_control(
			'toggle_icon_style_heading',
			array(
				'label'     => esc_html__( 'Icon Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__start_controls_tabs( 'toggle_icon_styles' );

		$this->__start_controls_tab(
			'toggle_icon_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->__add_group_control(
			\Jet_Blocks_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Toggle Icon', 'jet-blocks' ),
				'name'     => 'toggle_icon_box',
				'selector' => '{{WRAPPER}} ' . $css_scheme['icon'],
			),
			25
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'toggle_icon_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->__add_group_control(
			\Jet_Blocks_Group_Control_Box_Style::get_type(),
			array(
				'label'    => esc_html__( 'Toggle Icon', 'jet-blocks' ),
				'name'     => 'toggle_icon_box_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover ' . $css_scheme['icon'],
			),
			25
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__add_control(
			'toggle_label_style_heading',
			array(
				'label'     => esc_html__( 'Label Styles', 'jet-blocks' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->__start_controls_tabs( 'toggle_label_styles' );

		$this->__start_controls_tab(
			'toggle_label_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'toggle_control_label_color',
			array(
				'label'     => esc_html__( 'Label Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_label_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} '. $css_scheme['label'],
			),
			50
		);

		$this->__end_controls_tab();

		$this->__start_controls_tab(
			'toggle_label_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-blocks' ),
			)
		);

		$this->__add_control(
			'toggle_control_label_color_hover',
			array(
				'label'     => esc_html__( 'Label Color', 'jet-blocks' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover ' . $css_scheme['label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->__add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_label_typography_hover',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . ':hover ' . $css_scheme['label'],
			),
			50
		);

		$this->__end_controls_tab();

		$this->__end_controls_tabs();

		$this->__end_controls_section();

	}

	protected function render() {

		$this->__context = 'render';

		$panel_settings = $this->get_settings();

		$template_id          = isset( $panel_settings['panel_template_id'] ) ? $panel_settings['panel_template_id'] : '0';
		$position             = isset( $panel_settings['position'] ) ? $panel_settings['position'] : 'right';
		$animation_effect     = isset( $panel_settings['animation_effect'] ) ? $panel_settings['animation_effect'] : 'slide';
		$ajax_template        = isset( $panel_settings['ajax_template'] ) ? filter_var( $panel_settings['ajax_template'], FILTER_VALIDATE_BOOLEAN ) : false;
		$ajax_template_cache  = isset( $panel_settings['ajax_template_cache'] ) ? filter_var( $panel_settings['ajax_template_cache'], FILTER_VALIDATE_BOOLEAN ) : false;

		$settings = array(
			'position'          => $position,
			'ajaxTemplate'      => $ajax_template,
			'ajaxTemplateCache' => $ajax_template_cache,
		);

		if ( true === $ajax_template && true != $ajax_template_cache ) {
			add_filter( 'jet-blocks/rest/endpoint/elementor-template/cached', array( $this, 'disable_template_cache' ) );
		}

		$this->add_render_attribute( 'instance', array(
			'class' => array(
				'jet-hamburger-panel',
				'jet-hamburger-panel-' . $position . '-position',
				'jet-hamburger-panel-' . $animation_effect . '-effect',
			),
			'data-settings' => json_encode( $settings ),
		) );

		$close_button_html = $this->__get_icon( 'panel_close_icon', '<div class="jet-hamburger-panel__close-button jet-blocks-icon">%s</div>' );

		$toggle_control_html = '';

		$toggle_icon        = $this->__get_icon( 'panel_toggle_icon', '<span class="jet-hamburger-panel__icon icon-normal jet-blocks-icon">%s</span>' );
		$toggle_active_icon = $this->__get_icon( 'panel_toggle_active_icon', '<span class="jet-hamburger-panel__icon icon-active jet-blocks-icon">%s</span>' );

		if ( ! empty( $toggle_icon ) && ! empty( $toggle_active_icon ) ) {
			$toggle_control_html .= sprintf( '<div class="jet-hamburger-panel__toggle-icon">%1$s%2$s</div>', $toggle_icon, $toggle_active_icon );
		}

		$toggle_label_html = '';

		if ( ! empty( $panel_settings['panel_toggle_label'] ) ) {
			$toggle_label_html .= sprintf( '<div class="jet-hamburger-panel__toggle-label"><span>%1$s</span></div>', $panel_settings['panel_toggle_label'] );
		}

		$toggle_html = sprintf( '<div class="jet-hamburger-panel__toggle" role="button" tabindex="0">%1$s%2$s</div>', $toggle_control_html, $toggle_label_html );

		?>
		<div <?php echo $this->get_render_attribute_string( 'instance' ); ?>>
			<?php echo $toggle_html; ?>
			<div class="jet-hamburger-panel__instance">
				<div class="jet-hamburger-panel__cover"></div>
				<div class="jet-hamburger-panel__inner">
					<?php
						echo $close_button_html;

						if ( ! empty( $template_id ) ) {
							$link = add_query_arg(
								array(
									'elementor' => '',
								),
								get_permalink( $template_id )
							);

							if ( jet_blocks_integration()->in_elementor() ) {
								echo sprintf( '<div class="jet-blocks__edit-cover" data-template-edit-link="%s"><i class="eicon-edit"></i><span>%s</span></div>', $link, esc_html__( 'Edit Template', 'jet-blocks' ) );
							}
						}

						$this->add_render_attribute( 'jet-hamburger-panel__content', array(
							'class'            => 'jet-hamburger-panel__content',
							'data-template-id' => ! empty( $template_id ) ? $template_id : 'false',
						) );

						$content_html = '';

						if ( ! empty( $template_id ) && ! $ajax_template ) {
							$content_html .= jet_blocks()->elementor()->frontend->get_builder_content_for_display( $template_id );
						} else if ( ! $ajax_template ) {
							$content_html = $this->no_templates_message();
						} else {
							$content_html .= '<div class="jet-hamburger-panel-loader"></div>';
						}

						echo sprintf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( 'jet-hamburger-panel__content' ), $content_html );
					?>
				</div>
			</div>
		</div>
		<?php
	}

	public function disable_template_cache() {
		return false;
	}

	/**
	 * Empty templates message description
	 *
	 * @return string
	 */
	public function empty_templates_message() {
		return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
				<div class="elementor-widget-template-empty-templates-title">' . esc_html__( 'You Haven’t Saved Templates Yet.', 'jet-blocks' ) . '</div>
				<div class="elementor-widget-template-empty-templates-footer">' . esc_html__( 'What is Library?', 'jet-blocks' ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . esc_html__( 'Read our tutorial on using Library templates.', 'jet-blocks' ) . '</a></div>
				</div>';
	}

	/**
	 * No templates message
	 *
	 * @return string
	 */
	public function no_templates_message() {
		$message = '<span>' . esc_html__( 'Template is not defined. ', 'jet-blocks' ) . '</span>';

		$url = add_query_arg(
			array(
				'post_type'     => 'elementor_library',
				'action'        => 'elementor_new_post',
				'_wpnonce'      => wp_create_nonce( 'elementor_action_new_post' ),
				'template_type' => 'section',
			),
			esc_url( admin_url( '/edit.php' ) )
		);

		$new_link = '<span>' . esc_html__( 'Select an existing template or create a ', 'jet-blocks' ) . '</span><a class="jet-blocks-new-template-link elementor-clickable" href="' . $url . '" target="_blank">' . esc_html__( 'new one', 'jet-blocks' ) . '</a>' ;

		return sprintf(
			'<div class="jet-blocks-no-template-message">%1$s%2$s</div>',
			$message,
			jet_blocks_integration()->in_elementor() ? $new_link : ''
		);
	}

}


