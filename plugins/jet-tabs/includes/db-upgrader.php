<?php
/**
 * DB upgrader class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Tabs_DB_Upgrader' ) ) {

	/**
	 * Define Jet_Tabs_DB_Upgrader class
	 */
	class Jet_Tabs_DB_Upgrader {

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

			/**
			 * Plugin initialized on new Jet_Tabs_DB_Upgrader call.
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

			$db_updater_data = jet_tabs()->module_loader->get_included_module_data( 'cx-db-updater.php' );

			new CX_DB_Updater(
				array(
					'path'      => $db_updater_data['path'],
					'url'       => $db_updater_data['url'],
					'slug'      => 'jet-tabs',
					'version'   => jet_tabs()->get_version(),
					'callbacks' => array(
						'1.1.8' => array(
							array( $this, 'update_db_1_1_8' ),
						),
						'2.1.4' => array(
							array( $this, 'update_db_1_1_8' ),
						),
						'2.1.5' => array(
							array( $this, 'update_db_1_1_8' ),
						),
					),
					'labels'    => array(
						'start_update' => esc_html__( 'Start Update', 'jet-tabs' ),
						'data_update'  => esc_html__( 'Data Update', 'jet-tabs' ),
						'messages'     => array(
							'error'   => esc_html__( 'Module DB Updater init error in %s - version and slug is required arguments', 'jet-tabs' ),
							'update'  => esc_html__( 'We need to update your database to the latest version.', 'jet-tabs' ),
							'updated' => esc_html__( 'Update complete, thank you for updating to the latest version!', 'jet-tabs' ),
						),
					),
				)
			);
		}

		/**
		 * Update db updater 1.1.8
		 *
		 * @return void
		 */
		public function update_db_1_1_8() {

			if ( class_exists( 'Elementor\Plugin' ) ) {
				jet_tabs()->elementor()->files_manager->clear_cache();
			}
		}

	}

}
