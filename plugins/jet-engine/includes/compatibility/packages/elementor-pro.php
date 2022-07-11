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

if ( ! class_exists( 'Jet_Engine_Elementor_Pro_Package' ) ) {

	/**
	 * Define Jet_Engine_Elementor_Pro_Package class
	 */
	class Jet_Engine_Elementor_Pro_Package {

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			add_filter( 'jet-engine/listing/data/custom-listing',  array( $this, 'set_locations_listings' ), 10, 3 );
			add_filter( 'jet-engine/listings/data/default-object', array( $this, 'set_default_object_on_form_submit' ), 10, 2 );
		}

		/**
		 * Set locations listings
		 */
		public function set_locations_listings( $listing, $data_manager, $default_object ) {

			if ( ! isset( $default_object->post_type ) ) {
				return $listing;
			}

			if ( 'elementor_library' !== $default_object->post_type ) {
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

			if ( empty( $settings['preview_type'] ) ) {
				return $listing;
			}

			if ( false === strpos( $settings['preview_type'], 'single' ) ) {
				return $listing;
			}

			$preview = explode( '/', $settings['preview_type'] );

			if ( empty( $preview[1] ) ) {
				return $listing;
			}

			return array(
				'listing_source'    => 'posts',
				'listing_post_type' => $preview[1],
				'listing_tax'       => 'category',
			);

		}

		public function set_default_object_on_form_submit( $default_object, $data_instance ) {

			if ( ! class_exists( 'ElementorPro\Modules\Forms\Classes\Ajax_Handler' ) ) {
				return $default_object;
			}

			if ( ElementorPro\Modules\Forms\Classes\Ajax_Handler::is_form_submitted() && ! empty( $_REQUEST['queried_id'] ) ) {
				$post_id = $_REQUEST['queried_id'];
				$default_object = get_post( $post_id );
			}

			return $default_object;
		}

	}

}

new Jet_Engine_Elementor_Pro_Package();
