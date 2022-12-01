<?php
/**
 * JetWooBuilder Shortcodes Class
 *
 * @package   JetWooBuilder
 * @author    Crocoblock
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Woo_Builder_Shortcodes' ) ) {

	/**
	 * Define Jet_Woo_Builder_Shortcodes class
	 */
	class Jet_Woo_Builder_Shortcodes {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Check if processing Elementor widget
		 *
		 * @var boolean
		 */
		private $shortcodes = array();

		/**
		 * Initialize integration hooks
		 *
		 * @return void
		 */
		public function init() {
			add_action( 'init', array( $this, 'register_shortcodes' ), 30 );
		}

		/**
		 * Register plugins shortcodes
		 *
		 * @return void
		 */
		public function register_shortcodes() {

			require jet_woo_builder()->plugin_path( 'includes/base/class-jet-woo-builder-shortcode-base.php' );

			foreach ( glob( jet_woo_builder()->plugin_path( 'includes/shortcodes/' ) . '*.php' ) as $file ) {
				$this->register_shortcode( $file );
			}

		}

		/**
		 * Call shortcode instance from passed file.
		 *
		 * @param $file
		 *
		 * @return void
		 */
		public function register_shortcode( $file ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );

			require $file;

			if ( ! class_exists( $class ) ) {
				return;
			}

			$shortcode = new $class;

			$this->shortcodes[ $shortcode->get_tag() ] = $shortcode;

		}

		/**
		 * Get shortcode class instance by tag
		 *
		 * @param  $tag
		 *
		 * @return bool|mixed
		 */
		public function get_shortcode( $tag ) {
			return isset( $this->shortcodes[ $tag ] ) ? $this->shortcodes[ $tag ] : false;
		}

		/**
		 * Get products query type.
		 *
		 * Return list of possible query types.
		 *
		 * @since  1.7.5
		 * @since  2.0.4 Added `jet-woo-builder/shortcodes/query-types`, updated naming.
		 * @access public
		 *
		 * @return array
		 */
		public function get_products_query_type() {

			$query_types = [
				'all'          => __( 'All', 'jet-woo-builder' ),
				'featured'     => __( 'Featured', 'jet-woo-builder' ),
				'bestsellers'  => __( 'Bestsellers', 'jet-woo-builder' ),
				'sale'         => __( 'Sale', 'jet-woo-builder' ),
				'tag'          => __( 'Tag', 'jet-woo-builder' ),
				'category'     => __( 'Category', 'jet-woo-builder' ),
				'ids'          => __( 'Specific IDs', 'jet-woo-builder' ),
				'viewed'       => __( 'Recently Viewed', 'jet-woo-builder' ),
				'custom_tax'   => __( 'Custom Taxonomy', 'jet-woo-builder' ),
				'stock_status' => __( 'Stock Status', 'jet-woo-builder' ),
			];

			$single_query_types = [
				'related'     => __( 'Related', 'jet-woo-builder' ),
				'up-sells'    => __( 'Up Sells', 'jet-woo-builder' ),
				'cross-sells' => __( 'Cross Sells', 'jet-woo-builder' ),
			];

			$template_type = jet_woo_builder()->documents->get_current_type();

			if ( ! $template_type ) {
				$document = \Elementor\Plugin::$instance->documents->get_current();

				if ( $document ) {
					$template_type = $document->get_template_type();
				}
			}

			if ( 'jet-woo-builder' === $template_type || is_product() ) {
				$query_types = wp_parse_args( $single_query_types, $query_types );
			}

			$cart_query_types = [
				'cross-sells' => __( 'Cross Sells', 'jet-woo-builder' ),
			];

			if ( 'jet-woo-builder-cart' === $template_type || is_cart() ) {
				$query_types = wp_parse_args( $cart_query_types, $query_types );
			}

			return apply_filters( 'jet-woo-builder/shortcodes/query-types', $query_types );

		}

		/**
		 * Add WooCommerce catalog ordering args to current query
		 *
		 * @param $query_args
		 *
		 * @return array
		 */
		public function get_wc_catalog_ordering_args( $query_args ) {

			if ( ! isset( $query_args['orderby'] ) ) {
				$query_args['orderby'] = 'date';
			}

			$ordering_args = WC()->query->get_catalog_ordering_args( $query_args['orderby'], $query_args['order'] );

			$query_args['orderby'] = $ordering_args['orderby'];
			$query_args['order']   = $ordering_args['order'];

			if ( $ordering_args['meta_key'] ) {
				$query_args['meta_key'] = $ordering_args['meta_key'];
			}

			return $query_args;

		}

		/**
		 * Get products not found message.
		 *
		 * @since  2.1.0
		 * @access public
		 *
		 * @param $tag
		 * @param $message
		 * @param $shortcode
		 *
		 * @return void
		 */
		public function get_products_not_found_message( $tag, $message, $shortcode ) {

			$message = apply_filters( 'jet-woo-builder/shortcodes/' . $tag . '/not-found-message', $message, $shortcode );

			printf( '<div class="jet-woo-products__not-found">%s</div>', $message );

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance( $shortcodes = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}
			return self::$instance;

		}
	}

}

/**
 * Returns instance of Jet_Woo_Builder_Shortcodes
 *
 * @return object
 */
function jet_woo_builder_shortcodes() {
	return Jet_Woo_Builder_Shortcodes::get_instance();
}
