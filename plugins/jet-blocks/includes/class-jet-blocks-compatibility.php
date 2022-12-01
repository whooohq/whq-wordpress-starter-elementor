<?php
/**
 * Compatibility class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Blocks_Compatibility' ) ) {

	/**
	 * Define Jet_Blocks_Compatibility class
	 */
	class Jet_Blocks_Compatibility {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   Jet_Blocks_Compatibility
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {

			// Compatibility with Advanced noCaptcha & invisible Captcha plugin
			if ( class_exists( 'anr_captcha_class' ) ) {

				if ( function_exists( 'anr_is_form_enabled' ) && anr_is_form_enabled( 'registration' ) ) {
					$anr_captcha_class = anr_captcha_class::init();

					add_action( 'jet_register_form',               array( $anr_captcha_class, 'form_field' ) );
					add_filter( 'jet_register_form_custom_error',  array( $this, 'captcha_verify' ) );
				}
			}
		}

		/**
		 * Captcha verify.
		 *
		 * @param  mixed $verify
		 * @return WP_Error
		 */
		public function captcha_verify( $verify ) {
			$anr_captcha_class = anr_captcha_class::init();

			if ( ! $anr_captcha_class->verify() ) {
				return new WP_Error(
					'anr_error',
					$anr_captcha_class->add_error_to_mgs()
				);
			}

			return $verify;
		}

		/**
		 * Returns the instance.
		 *
		 * @return Jet_Blocks_Compatibility
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Jet_Blocks_Compatibility
 *
 * @return Jet_Blocks_Compatibility
 */
function jet_blocks_compatibility() {
	return Jet_Blocks_Compatibility::get_instance();
}
