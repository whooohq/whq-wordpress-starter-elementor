<?php
/**
 * DB upgrder class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Menu_DB_Upgrader' ) ) {

	/**
	 * Define Jet_Elements_DB_Upgrader class
	 */
	class Jet_Menu_DB_Upgrader {

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			/**
			 * Plugin initialized on new Jet_Elements_DB_Upgrader call.
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

			$db_updater_data = jet_menu()->module_loader->get_included_module_data( 'cx-db-updater.php' );

			new CX_DB_Updater(
				array(
					'path'      => $db_updater_data['path'],
					'url'       => $db_updater_data['url'],
					'slug'      => 'jet-menu',
					'version'   => '2.0.0',
					'callbacks' => array(
						'2.0.0' => array(
							array( $this, 'update_db_2_0_0' ),
						),
						'2.0.2' => array(
							array( $this, 'update_db_2_0_2' ),
						),
					),
					'labels'    => array(
						'start_update' => esc_html__( 'Start Update', 'jet-menu' ),
						'data_update'  => esc_html__( 'Data Update', 'jet-menu' ),
						'messages'     => array(
							'error'   => esc_html__( 'Module DB Updater init error in %s - version and slug is required arguments', 'jet-menu' ),
							'update'  => esc_html__( 'We need to update your database to the latest version.', 'jet-menu' ),
							'updated' => esc_html__( 'Update complete, thank you for updating to the latest version!', 'jet-menu' ),
						),
					),
				)
			);
		}

		/**
		 * Update db updater 1.7.2
		 *
		 * @return void
		 */
		public function update_db_2_0_0() {

			$current_options = jet_menu()->settings_manager->options_manager->get_option();

			$current_options['jet-menu-cache-css'] = 'false';
			$current_options['jet-mobile-items-label-switch'] = 'true';

			$options_map = array(
				'jet-menu-item-text-color'      => array( 'jet-mobile-items-label-color' ),
				'jet-menu-item-desc-color'      => array( 'jet-mobile-items-desc-color' ),
				'jet-top-menu-font-size'        => array( 'jet-mobile-items-label-font-size' ),
				'jet-top-menu-font-family'      => array( 'jet-mobile-items-label-font-family' ),
				'jet-menu-top-arrow-color'      => array(
					'jet-mobile-items-dropdown-color',
					'jet-menu-mobile-container-close-color',
					'jet-menu-mobile-breadcrumbs-text-color',
					'jet-menu-mobile-breadcrumbs-icon-color',
				),
				'jet-menu-top-icon-color'       => array( 'jet-mobile-items-icon-color'),
				'jet-menu-mobile-toggle-color'  => array( 'jet-mobile-loader-color' ),
				'jet-menu-top-badge-text-color' => array( 'jet-mobile-items-badge-color' ),
				'jet-menu-top-badge-bg-color'   => array( 'jet-mobile-items-badge-bg-color' ),
			);

			foreach ( $options_map as $option => $target_options ) {

				if ( is_array( $target_options ) ) {

					foreach ( $target_options as $key => $target_option ) {

						if ( array_key_exists( $option, $current_options ) ) {
							$current_options[ $target_option ] = $current_options[ $option ];
						}
					}
				}
			}

			update_option( jet_menu()->settings_manager->options_manager->options_slug, $current_options );

			do_action( 'jet-menu/db_updater/update' );
		}

		/**
		 * [update_db_2_0_2 description]
		 * @return [type] [description]
		 */
		public function update_db_2_0_2() {

			/**
			 * Regenerate elementor css files
			 */
			jet_menu()->elementor()->files_manager->clear_cache();

			$current_options = jet_menu()->settings_manager->options_manager->get_option();

			$current_options['jet-menu-roll-up'] = 'true';

			update_option( jet_menu()->settings_manager->options_manager->options_slug, $current_options );

			do_action( 'jet-menu/db_updater/update' );
		}

	}

}
