<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Theme_Core_Package' ) ) {

	/**
	 * Define Jet_Engine_Theme_Core_Package class
	 */
	class Jet_Engine_Theme_Core_Package {

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			add_filter( 'jet-engine/listing/data/custom-listing', array( $this, 'set_locations_listings' ), 10, 3 );
		}

		/**
		 * Set locations listings
		 */
		public function set_locations_listings( $listing, $data_manager, $default_object ) {

			if ( ! isset( $default_object->post_type ) ) {
				return $listing;
			}

			if ( 'jet-theme-core' !== $default_object->post_type ) {
				return $listing;
			}

			$elementor = Elementor\Plugin::instance();

			if ( ! $elementor->editor->is_edit_mode() ) {
				return $listing;
			}

			$document = $elementor->documents->get_doc_or_auto_save( $default_object->ID );

			if ( ! $document ) {
				return $listing;
			}

			$settings = $document->get_settings();

			if ( empty( $settings['preview_post_type'] ) ) {
				return $listing;
			}

			return array(
				'listing_source'    => 'posts',
				'listing_post_type' => $settings['preview_post_type'],
				'listing_tax'       => 'category',
			);

		}

	}

}

new Jet_Engine_Theme_Core_Package();
