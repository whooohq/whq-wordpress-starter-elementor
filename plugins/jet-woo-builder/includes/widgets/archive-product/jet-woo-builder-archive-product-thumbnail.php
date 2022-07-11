<?php
/**
 * Class: Jet_Woo_Builder_Archive_Product_Thumbnail
 * Name: Thumbnail
 * Slug: jet-woo-builder-archive-product-thumbnail
 */

namespace Elementor;

use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class Jet_Woo_Builder_Archive_Product_Thumbnail extends Widget_Base {

	public function get_name() {
		return 'jet-woo-builder-archive-product-thumbnail';
	}

	public function get_title() {
		return esc_html__( 'Thumbnail', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-images';
	}

	public function get_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/woocommerce-jetwoobuilder-settings-how-to-create-and-set-a-custom-categories-archive-template/?utm_source=need-help&utm_medium=jet-woo-categories&utm_campaign=jetwoobuilder';
	}

	public function get_categories() {
		return array( 'jet-woo-builder' );
	}

	public function show_in_panel() {
		return jet_woo_builder()->documents->is_document_type( 'archive' );
	}

	protected function register_controls() {

		$enable_thumb_effect = filter_var( jet_woo_builder_settings()->get( 'enable_product_thumb_effect' ), FILTER_VALIDATE_BOOLEAN );
		$css_scheme          = apply_filters(
			'jet-woo-builder/jet-archive-product-thumbnail/css-scheme',
			array(
				'thumbnail-wrapper' => '.jet-woo-builder-archive-product-thumbnail__wrapper',
				'thumbnail'         => '.jet-woo-builder-archive-product-thumbnail',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'Content', 'jet-woo-builder' ),
			)
		);

		$this->add_control(
			'is_linked',
			array(
				'label'        => esc_html__( 'Add link to thumbnail', 'jet-woo-builder' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'open_new_tab',
			[
				'label'     => esc_html__( 'Open in new window', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off' => esc_html__( 'No', 'jet-woo-builder' ),
				'default'   => '',
				'condition' => [
					'is_linked' => 'yes',
				],
			]
		);

		if ( $enable_thumb_effect ) {
			$this->add_control(
				'enable_thumbnail_effect',
				array(
					'label'        => esc_html__( 'Enable Thumbnail Effect', 'jet-woo-builder' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
					'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);
		}

		$this->add_control(
			'archive_thumbnail_size',
			array(
				'type'    => 'select',
				'label'   => esc_html__( 'Thumbnail Size', 'jet-woo-builder' ),
				'default' => 'woocommerce_thumbnail',
				'options' => jet_woo_builder_tools()->get_image_sizes(),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_archive_thumbnail_style',
			array(
				'label'      => esc_html__( 'Thumbnail', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'archive_thumbnail_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnail'] => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'archive_thumbnail_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumbnail'],
			)
		);

		$this->add_control(
			'archive_thumbnail_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnail'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'archive_thumbnail_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['thumbnail'],
			)
		);

		$this->add_responsive_control(
			'archive_thumbnail_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => jet_woo_builder_tools()->get_available_h_align_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['thumbnail-wrapper'] => 'text-align: {{VALUE}};',
				),
				'classes'   => 'elementor-control-align',
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Returns CSS selector for nested element
	 *
	 * @param null $el
	 *
	 * @return string
	 */
	public function css_selector( $el = null ) {
		return sprintf( '{{WRAPPER}} .%1$s %2$s', $this->get_name(), $el );
	}

	public static function render_callback( $settings = array() ) {

		$open_link    = '';
		$close_link   = '';
		$thumb_effect = 'yes' === $settings['enable_thumbnail_effect'];
		$target_attr  = 'yes' === $settings['open_new_tab'] ? 'target="_blank"' : '';

		if ( 'yes' === $settings['is_linked'] ) {
			$link       = jet_woo_builder_template_functions()->get_product_permalink();
			$open_link  = '<a href="' . $link . '" ' . $target_attr . '>';
			$close_link = '</a>';
		}

		echo '<div class="jet-woo-builder-archive-product-thumbnail__wrapper">';
		echo '<div class="jet-woo-builder-archive-product-thumbnail">';
		echo $open_link;
		echo jet_woo_builder_template_functions()->get_product_thumbnail(
			$settings['archive_thumbnail_size'],
			$thumb_effect,
			array(
				'class'        => 'jet-woo-builder-archive-product-image',
				'data-no-lazy' => '1',
			)
		);
		echo $close_link;
		echo '</div>';
		echo '</div>';

	}

	protected function render() {

		$settings = $this->get_settings();

		$macros_settings = array(
			'is_linked'               => $settings['is_linked'],
			'open_new_tab'            => $settings['open_new_tab'],
			'archive_thumbnail_size'  => $settings['archive_thumbnail_size'],
			'enable_thumbnail_effect' => isset( $settings['enable_thumbnail_effect'] ) ? $settings['enable_thumbnail_effect'] : false,
		);

		if ( jet_woo_builder_tools()->is_builder_content_save() ) {
			echo jet_woo_builder()->parser->get_macros_string( $this->get_name(), $macros_settings );
		} else {
			echo self::render_callback( $macros_settings );
		}

	}

}
