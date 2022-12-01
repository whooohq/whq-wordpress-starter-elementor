<?php
/**
 * Elementor views manager
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Blocks_Views_Type_Section' ) ) {

	/**
	 * Define Jet_Engine_Blocks_Views_Type_Section class
	 */
	class Jet_Engine_Blocks_Views_Type_Section extends Jet_Engine_Blocks_Views_Type_Container {

		public function __construct() {

			parent::__construct();

			if ( $this->has_style_manager() ) {
				add_filter( 'jet_style_manager/gutenberg/prevent_block_wrap/' . $this->get_block_name(), '__return_true' );
			}

		}

		/**
		 * Returns block name
		 *
		 * @return [type] [description]
		 */
		public function get_name() {
			return 'section';
		}

		/**
		 * Returns path to JSON file with block configuration
		 *
		 * @return string
		 */
		public function block_file() {
			return jet_engine()->plugin_path( 'assets/js/admin/blocks-views/src/blocks/section/block.json' );
		}

	}

}
