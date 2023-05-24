<?php
/**
 * DB upgrder class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Blocks_DB_Upgrader' ) ) {

	/**
	 * Define Jet_Blocks_DB_Upgrader class
	 */
	class Jet_Blocks_DB_Upgrader {

		/**
		 * Setting key
		 *
		 * @var string
		 */
		public $key = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			$this->key = jet_blocks_settings()->key;

			/**
			 * Plugin initialized on new Jet_Blocks_DB_Upgrader call.
			 * Please ensure, that it called only on admin context
			 */
			$this->init_upgrader();
		}

		/**
		 * Initialize upgrader module
		 *
		 * @return void
		 */
		public function init_upgrader() {

			$db_updater_data = jet_blocks()->module_loader->get_included_module_data( 'cx-db-updater.php' );

			new CX_DB_Updater(
				array(
					'path'      => $db_updater_data['path'],
					'url'       => $db_updater_data['url'],
					'slug'      => 'jet-blocks',
					'version'   => jet_blocks()->get_version(),
					'callbacks' => array(
						'1.3.5' => array(
							array( $this, 'update_db_1_3_5' ),
						),
					),
					'labels'    => array(
						'start_update' => esc_html__( 'Start Update', 'jet-blocks' ),
						'data_update'  => esc_html__( 'Data Update', 'jet-blocks' ),
						'messages'     => array(
							'error'   => esc_html__( 'Module DB Updater init error in %s - version and slug is required arguments', 'jet-blocks' ),
							'update'  => esc_html__( 'We need to update your database to the latest version.', 'jet-blocks' ),
							'updated' => esc_html__( 'Update complete, thank you for updating to the latest version!', 'jet-blocks' ),
						),
					),
				)
			);
		}

		/**
		 * Update db updater 1.3.5
		 *
		 * @return void
		 */
		public function update_db_1_3_5() {
			if ( class_exists( 'Elementor\Plugin' ) ) {
				jet_blocks()->elementor()->files_manager->clear_cache();
			}
		}
	}

}
