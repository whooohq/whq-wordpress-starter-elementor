<?php
/**
 * Polylang compatibility package
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Woo_Builder_Polylang_Package' ) ) {

	class Jet_Woo_Builder_Polylang_Package {

		public function __construct() {
			add_filter( 'jet-woo-builder/current-template/template-id', array( $this, 'set_translated_template' ) );
		}

		/**
		 * Set translated template ID to show
		 *
		 * @param int|string $template_id Popup ID
		 *
		 * @return false|int|null
		 */
		public function set_translated_template( $template_id ) {

			if ( function_exists( 'pll_get_post' ) ) {

				$translated_template_id = pll_get_post( $template_id );

				if ( null === $translated_template_id ) {
					// the current language is not defined yet
					return $template_id;
				} elseif ( false === $translated_template_id ) {
					//no translation yet
					return $template_id;
				} elseif ( $translated_template_id > 0 ) {
					// return translated post id
					return $translated_template_id;
				}
			}

			return $template_id;

		}

	}

}

new Jet_Woo_Builder_Polylang_Package();
