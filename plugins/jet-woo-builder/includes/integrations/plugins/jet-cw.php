<?php
/**
 * Compare & Wishlist compatibility package
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Woo_Builder_Compare_Wishlist_Package' ) ) {

	/**
	 * Define Jet_Woo_Builder_Compare_Wishlist_Package class
	 */
	class Jet_Woo_Builder_Compare_Wishlist_Package {

		/**
		 * Wishlist template holder
		 *
		 * @var null
		 */
		private $current_wishlist_template = null;

		/**
		 * Contains a variable responsible for enabling the option
		 *
		 * @var bool
		 */
		private $archive_templates_enable = false;

		/**
		 * Jet_Woo_Builder_Compare_Wishlist_Package constructor.
		 */
		public function __construct() {

			add_filter( 'woocommerce_get_settings_jet-woo-builder-settings', [ $this, 'register_cw_settings' ], 10, 2 );

			$this->archive_templates_enable  = jet_woo_builder_integration()->is_setting_enabled( 'custom_archive_page' );
			$this->current_wishlist_template = absint( jet_woo_builder_shop_settings()->get( 'wishlist_template' ) );

			if ( $this->archive_templates_enable && $this->current_wishlist_template ) {
				add_filter( 'jet-compare-wishlist/wishlist-template/template-content', [ $this, 'get_current_wishlist_template' ], 10, 2 );
			}

		}

		/**
		 * Register options control for selecting wishlist template
		 *
		 * @param $settings
		 * @param $section
		 *
		 * @return mixed
		 */
		public function register_cw_settings( $settings, $section ) {

			foreach ( $settings as $key => $value ) {
				if ( 'sectionend' === $value['type'] && 'archive_options' === $value['id'] ) {
					$wishlist_template_settings = [
						'title'    => __( 'Wishlist Product Template', 'jet-woo-builder' ),
						'desc'     => __( 'Select the template to use it as a global wishlist product template.', 'jet-woo-builder' ),
						'id'       => jet_woo_builder_shop_settings()->options_key . '[wishlist_template]',
						'doc_type' => 'archive',
						'default'  => '',
						'type'     => 'jet_woo_select_template',
						'class'    => 'wc-enhanced-select-nostd',
						'css'      => 'min-width:300px;',
					];

					array_splice( $settings, $key, 0, [ $wishlist_template_settings ] );

					break;
				}
			}

			return $settings;

		}

		/**
		 * Returns processed wishlist product card template
		 *
		 * @param $template_content
		 * @param $product
		 *
		 * @return mixed
		 */
		public function get_current_wishlist_template( $template_content, $product ) {

			if ( ! $product ) {
				return $template_content;
			}

			global $post;

			$post    = get_post( $product->get_id() );
			$classes = [ 'jet-woo-builder-product', 'jet-woo-builder-archive-item-' . $product->get_id() ];

			if ( filter_var( jet_woo_builder_settings()->get( 'enable_product_thumb_effect' ), FILTER_VALIDATE_BOOLEAN ) ) {
				$classes[] = 'jet-woo-thumb-with-effect';
			}

			setup_postdata( $post );

			$template_content = jet_woo_builder()->parser->get_template_content( $this->current_wishlist_template, false, $product );
			$template_content = apply_filters( 'jet-woo-builder/elementor-views/frontend/archive-item-content', $template_content, $this->current_wishlist_template, $product );

			wp_reset_postdata();

			return sprintf( '<div class="%s" data-product-id="%s">%s</div>', implode( ' ', $classes ), $product->get_id(), $template_content );

		}

	}

}

new Jet_Woo_Builder_Compare_Wishlist_Package();
