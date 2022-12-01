<?php
/**
 * JetEngine compatibility package
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Woo_Builder_Engine_Package' ) ) {

	// Define class
	class Jet_Woo_Builder_Engine_Package {

		// Class constructor.
		public function __construct() {

			add_filter( 'jet-engine/listing/item-classes', [ $this, 'maybe_add_thumbnail_effect_class' ], 10, 2 );

			add_action( 'elementor/element/jet-woo-products/section_general/after_section_end', [ $this, 'register_custom_query_controls' ] );
			add_action( 'elementor/element/jet-woo-products-list/section_general/after_section_end', [ $this, 'register_custom_query_controls' ] );

			add_filter( 'jet-engine/query-builder/filter-provider', [ $this, 'maybe_set_query_builder_proper_provider' ], 10, 2 );

			add_action( 'jet-engine/listings/frontend/setup-data', [ $this, 'maybe_set_listings_frontend_data' ] );

			add_filter( 'jet-woo-builder/integration/register-widgets', [ $this, 'is_register_widgets' ], 10, 2 );
			add_filter( 'jet-woo-builder/documents/is-document-type', [ $this, 'is_proper_listing' ], 10, 2 );

		}

		/**
		 * Register widgets.
		 *
		 * Register archive items widget for listings template.
		 *
		 * @param bool   $valid Registration status.
		 * @param string $type  Template type slug.
		 *
		 * @return bool|mixed
		 */
		public function is_register_widgets( $valid, $type ) {

			if ( in_array( $type, [ 'archive', 'category' ] ) ) {
				$valid = true;
			}

			return $valid;

		}

		/**
		 * Check listing.
		 *
		 * Check if JetEngine listing created for products or products categories and display appropriate JetWooBuilder
		 * widgets in Elementor editor.
		 *
		 * @param bool   $valid Registration status.
		 * @param string $type  Template type slug.
		 *
		 * @return bool|mixed
		 */
		public function is_proper_listing( $valid, $type ) {

			$listing    = jet_engine()->listings->data->get_listing();
			$listing_id = $listing->get_main_id();

			if ( ! $listing_id ) {
				return $valid;
			}

			$settings = get_post_meta( $listing_id, '_elementor_page_settings', true );

			if ( ! $settings ) {
				return $valid;
			}

			switch ( $type ) {
				case 'archive':
					if ( isset( $settings['listing_post_type'] ) && 'product' === $settings['listing_post_type'] ) {
						$valid = true;
					}

					if ( isset( $settings['listing_source'] ) && 'query' === $settings['listing_source'] ) {
						$query_id = $settings['_query_id'] ?? '';
						$query    = \Jet_Engine\Query_Builder\Manager::instance()->get_query_by_id( $query_id );

						if ( $query && ( 'wc-product-query' === $query->query_type || 'posts' === $query->query_type && in_array( 'product', $query->query['post_type'] ) ) ) {
							$valid = true;
						}
					}

					break;

				case 'category':
					if ( isset( $settings['listing_tax'] ) && 'product_cat' === $settings['listing_tax'] ) {
						$valid = true;
					}

					break;

				default:
					break;
			}

			return $valid;

		}

		/**
		 * Set listing frontend data.
		 *
		 * Setup data for Products Archive widgets compatibility with listing grid that use WC_Products_Query.
		 *
		 * @param $post
		 */
		public function maybe_set_listings_frontend_data( $post ) {
			if ( $post && is_a( $post, 'WC_Product' ) ) {
				global $product;

				$product = $post;
			}
		}

		/**
		 * Add listing item class.
		 *
		 * Push product thumbnail effect class to products listing item wrapper.
		 *
		 * @param $classes
		 * @param $object
		 *
		 * @return mixed
		 */
		public function maybe_add_thumbnail_effect_class( $classes, $object ) {

			$product_thumb_effect = filter_var( jet_woo_builder_settings()->get( 'enable_product_thumb_effect' ), FILTER_VALIDATE_BOOLEAN );

			if ( ! $product_thumb_effect ) {
				return $classes;
			}

			if ( ! $object ) {
				return $classes;
			}

			if ( is_a( $object, 'WC_Product' ) || ( is_a( $object, 'WP_Post' ) && 'product' === $object->post_type ) ) {
				$classes[] = 'jet-woo-thumb-with-effect';
			}

			return $classes;

		}

		/**
		 * Controls.
		 *
		 * Register custom query controls for Products Grid/List widgets.
		 *
		 * @param $obj
		 */
		public function register_custom_query_controls( $obj ) {

			$obj->start_controls_section(
				'section_custom_query',
				[
					'label' => __( 'Custom Query', 'jet-woo-builder' ),
				]
			);

			$obj->add_control(
				'enable_custom_query',
				[
					'label'       => __( 'Enable Custom Query', 'jet-woo-builder' ),
					'type'        => \Elementor\Controls_Manager::SWITCHER,
					'description' => __( 'Allow to use custom query from Query Builder as items source.', 'jet-woo-builder' ),
				]
			);

			$obj->add_control(
				'custom_query_id',
				[
					'label'     => __( 'Custom Query', 'jet-woo-builder' ),
					'type'      => \Elementor\Controls_Manager::SELECT,
					'default'   => '',
					'options'   => \Jet_Engine\Query_Builder\Manager::instance()->get_queries_for_options(),
					'condition' => [
						'enable_custom_query' => 'yes',
					],
				]
			);

			$obj->end_controls_section();

		}

		/**
		 * Set provider.
		 *
		 * Setup proper provider for query builder custom query usage.
		 *
		 * @param $provider
		 * @param $widget
		 *
		 * @return mixed|string
		 */
		public function maybe_set_query_builder_proper_provider( $provider, $widget ) {

			switch ( $widget->get_name() ) {
				case 'jet-woo-products-grid':
				case 'jet-woo-products-list':
					$provider = $widget->get_name();

					break;

				default:
					break;
			}

			return $provider;

		}

	}

}

new Jet_Woo_Builder_Engine_Package();
