<?php
/**
 * Define WooCommerce pages manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Woo_Builder_WC_Pages' ) ) {

	/**
	 * Define Jet_Woo_Builder_WC_Pages class
	 */
	class Jet_Woo_Builder_WC_Pages {

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
		function __construct() {
			add_action( 'init', [ $this, 'init' ] );
		}

		/**
		 * Initialize component.
		 *
		 * @return void
		 */
		public function init() {
			if ( 'yes' === jet_woo_builder_shop_settings()->get( 'custom_checkout_page' ) ) {
				require_once jet_woo_builder()->plugin_path( 'includes/components/wc-pages/class-checkout-page.php' );

				new Jet_Woo_Builder_Checkout_Page();
			}
		}

		/**
		 * Returns the instance.
		 *
		 * @return object
		 * @since  1.0.0
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

Jet_Woo_Builder_WC_Pages::get_instance();