<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Woo_Builder_Compatibility' ) ) {

	/**
	 * Define Jet_Woo_Builder_Compatibility class
	 */
	class Jet_Woo_Builder_Compatibility {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {

			// WPML String Translation plugin exist check
			if ( defined( 'WPML_ST_VERSION' ) ) {
				add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'add_translatable_nodes' ) );
				add_filter( 'jet-woo-builder/current-template/template-id', array( $this, 'jet_woo_builder_modify_template_id' ) );

				add_action( 'jet-woo-builder/rest/init-endpoints', function () {
					if ( defined( 'REST_REQUEST' ) && ! isset( WC()->cart ) ) {
						wc_load_cart();
					}
				} );

			}

			// Polylang Translation plugin exist check
			if ( class_exists( 'Polylang' ) ) {
				require jet_woo_builder()->plugin_path( 'includes/lib/compatibility/packages/polylang.php' );
			}

		}

		/**
		 * Modify JetWooBuilder template ID.
		 *
		 * @param $template_id
		 *
		 * @return mixed|void
		 */
		function jet_woo_builder_modify_template_id( $template_id ) {
			return apply_filters( 'wpml_object_id', $template_id, jet_woo_builder_post_type()->slug(), true );
		}

		/**
		 * Add JetWooBuilder translation nodes.
		 *
		 * @param array
		 *
		 * @return mixed
		 */
		public function add_translatable_nodes( $nodes_to_translate ) {

			$nodes_to_translate['jet-woo-products'] = array(
				'conditions' => array( 'widgetType' => 'jet-woo-products' ),
				'fields'     => array(
					array(
						'field'       => 'sale_badge_text',
						'type'        => esc_html__( 'Jet Woo Products Grid: Set sale badge text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate['jet-woo-categories'] = array(
				'conditions' => array( 'widgetType' => 'jet-woo-categories' ),
				'fields'     => array(
					array(
						'field'       => 'count_before_text',
						'type'        => esc_html__( 'Jet Woo Categories Grid: Count Before Text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'count_after_text',
						'type'        => esc_html__( 'Jet Woo Categories Grid: Count After Text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'desc_after_text',
						'type'        => esc_html__( 'Jet Woo Categories Grid: Trimmed After Text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate['jet-woo-taxonomy-tiles'] = array(
				'conditions' => array( 'widgetType' => 'jet-woo-taxonomy-tiles' ),
				'fields'     => array(
					array(
						'field'       => 'count_before_text',
						'type'        => esc_html__( 'Jet Woo Taxonomy Tiles: Count Before Text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
					array(
						'field'       => 'count_after_text',
						'type'        => esc_html__( 'Jet Woo Taxonomy Tiles: Count After Text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate['jet-single-attributes'] = array(
				'conditions' => array( 'widgetType' => 'jet-single-attributes' ),
				'fields'     => array(
					array(
						'field'       => 'block_title',
						'type'        => esc_html__( 'Jet Woo Single Attributes: Title Text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate['jet-woo-builder-archive-sale-badge'] = array(
				'conditions' => array( 'widgetType' => 'jet-woo-builder-archive-sale-badge' ),
				'fields'     => array(
					array(
						'field'       => 'block_title',
						'type'        => esc_html__( 'Jet Woo Archive Sale Badge: Sale Badge Text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate['jet-woo-builder-archive-category-count'] = array(
				'conditions' => array( 'widgetType' => 'jet-woo-builder-archive-category-count' ),
				'fields'     => array(
					array(
						'field'       => 'archive_category_count_before_text',
						'type'        => esc_html__( 'Jet Woo Archive Category Count: Count Before Text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate['jet-woo-builder-archive-category-count'] = array(
				'conditions' => array( 'widgetType' => 'jet-woo-builder-archive-category-count' ),
				'fields'     => array(
					array(
						'field'       => 'archive_category_count_after_text',
						'type'        => esc_html__( 'Jet Woo Archive Category Count: Count After Text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate['jet-woo-builder-products-navigation'] = array(
				'conditions' => array( 'widgetType' => 'jet-woo-builder-products-navigation' ),
				'fields'     => array(
					array(
						'field'       => 'prev_text',
						'type'        => esc_html__( 'Jet Woo Shop Products Navigation: The previous page link text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate['jet-woo-builder-products-navigation'] = array(
				'conditions' => array( 'widgetType' => 'jet-woo-builder-products-navigation' ),
				'fields'     => array(
					array(
						'field'       => 'next_text',
						'type'        => esc_html__( 'Jet Woo Shop Products Navigation: The next page link text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate['jet-woo-builder-products-pagination'] = array(
				'conditions' => array( 'widgetType' => 'jet-woo-builder-products-pagination' ),
				'fields'     => array(
					array(
						'field'       => 'prev_text',
						'type'        => esc_html__( 'Jet Woo Shop Products Pagination: The previous page link text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			$nodes_to_translate['jet-woo-builder-products-pagination'] = array(
				'conditions' => array( 'widgetType' => 'jet-woo-builder-products-pagination' ),
				'fields'     => array(
					array(
						'field'       => 'next_text',
						'type'        => esc_html__( 'Jet Woo Shop Products Pagination: The next page link text', 'jet-woo-builder' ),
						'editor_type' => 'LINE',
					),
				),
			);

			return $nodes_to_translate;

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;

		}

	}

}

/**
 * Returns instance of Jet_Woo_Builder_Compatibility
 *
 * @return object
 */
function jet_woo_builder_compatibility() {
	return Jet_Woo_Builder_Compatibility::get_instance();
}
