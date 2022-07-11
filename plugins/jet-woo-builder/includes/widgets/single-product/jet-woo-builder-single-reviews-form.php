<?php
/**
 * Class: Jet_Woo_Builder_Single_Reviews_Form
 * Name: Single Reviews Form
 * Slug: jet-single-reviews-form
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Builder_Single_Reviews_Form extends Jet_Woo_Builder_Base {

	public function get_name() {
		return 'jet-single-reviews-form';
	}

	public function get_title() {
		return esc_html__( 'Single Reviews Form', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-single-reviews-form';
	}

	public function get_script_depends() {
		return [];
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetwoobuilder-how-to-create-and-set-a-single-product-page-template/';
	}

	public function get_categories() {
		return [ 'jet-woo-builder' ];
	}

	public function show_in_panel() {
		return jet_woo_builder()->documents->is_document_type( 'single' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'single-reviews-form-general',
			array(
				'label' => esc_html__( 'General', 'jet-woo-builder' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		jet_woo_builder_common_controls()->register_wc_style_warning( $this );

		$this->end_controls_section();

	}

	protected function render() {

		global $product;

		if ( ! is_a( $product, 'WC_Product' ) ) {
			return;
		}

		if ( true === $this->__set_editor_product() ) {
			$this->__open_wrap();

			// Add filters before displaying our Widget.
			if ( jet_woo_builder_integration()->in_elementor() ) {
				add_filter( 'comments_template', [ 'WC_Template_Loader', 'comments_template_loader' ] );
			}

			comments_template();

			// Remove filters after displaying our Widget.
			if ( jet_woo_builder_integration()->in_elementor() ) {
				remove_filter( 'comments_template', [ 'WC_Template_Loader', 'comments_template_loader' ] );
			}

			$this->__close_wrap();

			if ( jet_woo_builder_integration()->in_elementor() ) {
				$this->__reset_editor_product();
			}
		}

	}

}
