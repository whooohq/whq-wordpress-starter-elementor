<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Smart_Filters_Settings' ) ) {

	/**
	 * Define Jet_Smart_Filters_Settings class
	 */
	class Jet_Smart_Filters_Settings {

		/**
		 * [$key description]
		 * @var string
		 */
		public $key = 'jet-smart-filters-settings';

		/**
		 * [$builder description]
		 * @var null
		 */
		public $builder  = null;

		/**
		 * [$settings description]
		 * @var null
		 */
		public $settings = null;

		/**
		 * Avaliable Widgets array
		 *
		 * @var array
		 */
		public $avaliable_providers = array();

		/**
		 * Avaliable Post Types for Indexer array
		 *
		 * @var array
		 */
		public $post_types = array();

		/**
		 * Avaliable posts types for which permalinks will be rewritten
		 *
		 * @var array
		 */
		public $rewritable_post_types = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_page' ), 99 );
			add_action( 'updated_option', array( $this, 'updated_settings' ), 0, 3 );

			foreach ( glob( jet_smart_filters()->plugin_path( 'includes/providers/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				if ( $data['name'] ) {
					$this->avaliable_providers[ $data['class'] ] = $data['name'];
				}
			}

		}

		/**
		 * Register add/edit page
		 *
		 * @return void
		 */
		public function register_page() {

			add_submenu_page(
				'edit.php?post_type=jet-smart-filters',
				esc_html__( 'Settings', 'jet-dashboard' ),
				esc_html__( 'Settings', 'jet-dashboard' ),
				'manage_options',
				add_query_arg(
					array(
						'page' => 'jet-dashboard-settings-page',
						'subpage' => 'jet-smart-filters-general-settings'
					),
					admin_url( 'admin.php' )
				)
			);

		}

		/**
		 * [get_settings_page_config description]
		 * @return [type] [description]
		 */
		public function get_settings_page_config() {

			foreach ( $this->avaliable_providers as $key => $value ) {
				$default_avaliable_providers[ $key ] = 'true';
			}

			foreach ( $this->get_post_types_for_options() as $key => $value ) {
				$default_avaliable_post_types[ $key ] = 'false';
			}

			foreach ( $this->get_rewritable_post_types_options() as $key => $value ) {
				$default_rewritable_post_types[ $key ] = 'false';
			}

			$rest_api_url = apply_filters( 'jet-smart-filters/rest/frontend/url', get_rest_url() );

			return array(
				'settingsApiUrl' => $rest_api_url . 'jet-smart-filters-api/v1/plugin-settings',
				'settingsData'   => array(
					'avaliable_providers' => array(
						'value'   => $this->get( 'avaliable_providers', $default_avaliable_providers ),
						'options' => $this->avaliable_providers,
					),
					'use_indexed_filters' => array(
						'value' => $this->get( 'use_indexed_filters' ),
					),
					'avaliable_post_types' => array(
						'value'   => $this->get( 'avaliable_post_types', $default_avaliable_post_types ),
						'options' => $this->get_post_types_for_options(),
					),
					'use_auto_indexing' => array(
						'value' => $this->get( 'use_auto_indexing' ),
					),
					'url_structure_type' => array(
						'value'   => $this->get( 'url_structure_type', 'plain' ),
						'options' => array(
							array(
								'value' => 'plain',
								'label' => 'Plain',
							),
							array(
								'value' => 'permalink',
								'label' => 'Permalink',
							)
						)
					),
					'rewritable_post_types' => array(
						'value'   => $this->get( 'rewritable_post_types', $default_rewritable_post_types ),
						'options' => $this->get_rewritable_post_types_options(),
					),
					'ajax_request_types' => array(
						'value'   => $this->get( 'ajax_request_types', 'default' ),
						'options' => array(
							array(
								'value' => 'default',
								'label' => 'Default (ajax admin-ajax.php request)',
							),
							array(
								'value' => 'referrer',
								'label' => 'Referrer (ajax admin-ajax.php request + referrer)',
							),
							array(
								'value' => 'self',
								'label' => 'Self (request for the current page)',
							)
						)
					),
				),
			);
		}

		/**
		 * Return settings page URL
		 *
		 * @return string
		 */
		public function get_settings_page_link() {

			return add_query_arg(
				array(
					'page' => $this->key,
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		/**
		 * [get description]
		 * @param  [type]  $setting [description]
		 * @param  boolean $default [description]
		 * @return [type]           [description]
		 */
		public function get( $setting, $default = false ) {

			if ( null === $this->settings ) {
				$this->settings = get_option( $this->key, array() );
			}

			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;
		}

		/**
		 * Returns post types list for options
		 *
		 * @return array
		 */
		public function get_post_types_for_options() {

			$indexed_post_types_exceptions = apply_filters( 'jet-smart-filters/indexed-post-types-exceptions', array( 
				'attachment',
				'elementor_library',
				'e-landing-page',
				'jet-woo-builder',
				'jet-engine',
				'jet-engine-booking'
			) );

			$args = array(
				'public' => true,
			);

			$post_types = get_post_types( $args, 'objects', 'and' );
			$post_types = wp_list_pluck( $post_types, 'label', 'name' );

			if ( isset( $post_types[ jet_smart_filters()->post_type->slug() ] ) ) {
				unset( $post_types[jet_smart_filters()->post_type->slug()] );
			}

			foreach ( $post_types as $key => $value ) {
				if ( in_array( $key, $indexed_post_types_exceptions ) ) {
					unset( $post_types[$key] );
				}
			}

			$post_types['users'] = __( 'Users', 'jet-smart-filters' );

			return $post_types;
		}

		/**
		 * Returns rewritable taxonomies list for options
		 *
		 * @return array
		 */
		public function get_rewritable_post_types_options() {

			if ( ! empty( $this->rewritable_post_types ) ) {
				return $this->rewritable_post_types;
			}

			$rewritable_post_types_exceptions = apply_filters( 'jet-smart-filters/settings/rewritable-post-types-exceptions', array(
				'jet-popup',
				'jet-menu'
			) );

			$this->rewritable_post_types = array(
				'post' => get_post_type_object('post')->label
			);

			foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $post_type ) {
				if ( in_array( $post_type->name, $rewritable_post_types_exceptions ) || empty( $post_type->rewrite ) ) {
					continue;
				}
				
				$this->rewritable_post_types[$post_type->name] = $post_type->label;
			}

			return $this->rewritable_post_types;

		}

		/**
		 * Updat settings when options changing
		 *
		 * @return void
		 */
		function updated_settings( $option, $old_value, $value ) {

			if ( $option !== $this->key ) {
				return;
			}

			$this->settings = $value;

		}

	}
}
