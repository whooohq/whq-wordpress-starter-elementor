<?php
/**
 * Class: Jet_Elements_Brands
 * Name: Logo Showcase
 * Slug: jet-brands
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

class Jet_Elements_Brands extends Jet_Elements_Base {

	public function get_name() {
		return 'jet-brands';
	}

	public function get_title() {
		return esc_html__( 'Logo Showcase', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-brands';
	}

	public function get_keywords() {
		return array( 'brands' );
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-showcase-brands-in-most-stylish-ways-with-jetelements-brands-widget/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_brands',
			array(
				'label' => esc_html__( 'Brands', 'jet-elements' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_image',
			array(
				'label'   => esc_html__( 'Company Logo', 'jet-elements' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_name',
			array(
				'label'   => esc_html__( 'Company Name', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_desc',
			array(
				'label'   => esc_html__( 'Company Description', 'jet-elements' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_url',
			array(
				'label'   => esc_html__( 'Company URL', 'jet-elements' ),
				'type'    => Controls_Manager::URL,
				'default' => array(
					'url' => '#',
				),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'brands_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'item_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_name'  => esc_html__( 'Company #1', 'jet-elements' ),
						'item_url'   => array(
							'url' => '#',
						),
					),
					array(
						'item_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'item_name'  => esc_html__( 'Company #2', 'jet-elements' ),
						'item_url'   => array(
							'url' => '#',
						),
					),
				),
				'title_field' => '{{{ item_name }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'   => esc_html__( 'Columns', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 4,
				'options' => jet_elements_tools()->get_select_range( 6 ),
				'selectors' => array(
					'{{WRAPPER}} .brands-list__item' => 'max-width: calc( 100% / {{VALUE}} );flex: 0 0 calc( 100% / {{VALUE}} ); -webkit-box-flex: 0;
					-ms-flex: 0 0 calc( 100% / {{VALUE}} );',
				)
			)
		);

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-elements/brands/css-scheme',
			array(
				'list'      => '.brands-list',
				'logo'      => '.brands-list .brands-list__item-img',
				'logo_wrap' => '.brands-list .brands-list__item-img-wrap',
				'name'      => '.brands-list .brands-list__item-name',
				'desc'      => '.brands-list .brands-list__item-desc',
			)
		);

		$this->_start_controls_section(
			'section_brand_item_style',
			array(
				'label'      => esc_html__( 'Company Item', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			),
			100
		);

		$this->_add_responsive_control(
			'vertical_brands_alignment',
			array(
				'label'       => esc_html__( 'Vertical Alignment', 'jet-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'jet-elements' ),
						'icon' => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Middle', 'jet-elements' ),
						'icon' => 'eicon-v-align-middle',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'jet-elements' ),
						'icon' => 'eicon-v-align-bottom',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['list'] => 'align-items: {{VALUE}}',
				),
			),
			100
		);

		$this->_end_controls_section(100);

		$this->_start_controls_section(
			'section_brand_logo_style',
			array(
				'label'      => esc_html__( 'Company Logo', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'logo_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
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
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['logo_wrap'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			)
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'logo_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['logo'],
			)
		);

		$this->_add_responsive_control(
			'logo_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['logo'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'logo_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['logo'],
			),
			100
		);

		$this->_add_control(
			'logo_wrap_style',
			array(
				'label'     => esc_html__( 'Logo Wrapper', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'logo_wrap_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['logo_wrap'],
			),
			25
		);

		$this->_add_responsive_control(
			'logo_wrap_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['logo_wrap'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'logo_wrap_border',
				'label'       => esc_html__( 'Border', 'jet-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['logo_wrap'],
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'logo_wrap_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['logo_wrap'],
			),
			100
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_brand_title_style',
			array(
				'label'      => esc_html__( 'Company Name', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'title_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'    => array(
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
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['name'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
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
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['name'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['name'],
			),
			50
		);

		$this->_add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['name'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

		$this->_start_controls_section(
			'section_brand_desc_style',
			array(
				'label'      => esc_html__( 'Company Description', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_responsive_control(
			'desc_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'    => array(
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
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'text-align: {{VALUE}};',
				),
				'classes' => 'jet-elements-text-align-control',
			),
			25
		);

		$this->_add_control(
			'desc_color',
			array(
				'label'  => esc_html__( 'Title Color', 'jet-elements' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} ' . $css_scheme['desc'],
			),
			50
		);

		$this->_add_responsive_control(
			'desc_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_end_controls_section();

	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	protected function content_template() {

		$this->_context = 'edit';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	public function _open_brand_link( $url_key ) {
		call_user_func( array( $this, sprintf( '_open_brand_link_%s', $this->_context ) ), $url_key );
	}

	public function _open_brand_link_render( $url_key ) {

		$item = $this->_processed_item;

		if ( empty( $item[ $url_key ]['url'] ) ) {
			return;
		}

		$id = $item['_id'];

		$this->add_render_attribute( 'link-' . $id, 'class', 'brands-list__item-link' );

		if ( method_exists( $this, 'add_link_attributes' ) ) {
			$this->add_link_attributes( 'link-' . $id, $item[ $url_key ] );
		} else {
			$this->add_render_attribute( 'link-' . $id, 'href', $item[ $url_key ]['url'] );

			if ( ! empty( $item[ $url_key ]['is_external'] ) ) {
				$this->add_render_attribute( 'link-' . $id, 'target', '_blank' );
			}

			if ( ! empty( $item[ $url_key ]['nofollow'] ) ) {
				$this->add_render_attribute( 'link-' . $id, 'rel', 'nofollow' );
			}
		}

		printf(
			'<a %s>',
			$this->get_render_attribute_string( 'link-' . $id )
		);

	}

	public function _open_brand_link_edit( $url_key ) {

		echo '<# if ( item.' . $url_key . '.url ) { #>';
		printf(
			'<a href="%1$s" class="brands-list__item-link"%2$s%3$s>',
			'{{{ item.' . $url_key . '.url }}}',
			'<# if ( item.' . $url_key . '.is_external ) { #> target="_blank"<# } #>',
			'<# if ( item.' . $url_key . '.nofollow ) { #> rel="nofollow"<# } #>'
		);
		echo '<# } #>';

	}

	public function _close_brand_link( $url_key ) {
		call_user_func( array( $this, sprintf( '_close_brand_link_%s', $this->_context ) ), $url_key );
	}

	public function _close_brand_link_render( $url_key ) {

		$item = $this->_processed_item;

		if ( empty( $item[ $url_key ]['url'] ) ) {
			return;
		}

		echo '</a>';

	}

	public function _close_brand_link_edit( $url_key ) {

		echo '<# if ( item.' . $url_key . '.url ) { #>';
		echo '</a>';
		echo '<# } #>';

	}

	public function _get_brand_image( $img_key ) {
		call_user_func( array( $this, sprintf( '_get_brand_image_%s', $this->_context ) ), $img_key );
	}

	public function _get_brand_image_render( $img_key ) {
		$image_item = $this->_processed_item[ $img_key ];

		if ( empty( $image_item['url'] ) ) {
			return;
		}

		printf( '<div class="brands-list__item-img-wrap"><img src="%1$s" alt="%2$s" class="brands-list__item-img" loading="lazy"></div>',
			$image_item['url'],
			esc_attr( Control_Media::get_image_alt( $image_item ) )
		);
	}

	public function _get_brand_image_edit( $img_key ) {
		echo $this->_loop_item( array( $img_key, 'url' ), '<div class="brands-list__item-img-wrap"><img src="%s" alt="" class="brands-list__item-img"></div>' );
	}
}
