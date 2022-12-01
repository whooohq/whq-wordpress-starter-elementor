<?php
/**
 * Jet Smart Filters Admin Settings class
 */

if ( ! class_exists( 'Jet_Smart_Filters_Admin_Settings' ) ) {
	/**
	 * Define Jet_Smart_Filters_Admin_Settings class
	 */
	class Jet_Smart_Filters_Admin_Settings {
		/**
		 * A reference to an instance of this class.
		 */
		private static $instance = null;

		/**
		 * Avaliable Widgets array
		 */
		public $avaliable_providers = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			foreach ( glob( jet_smart_filters()->plugin_path( 'includes/providers/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				if ( $data['name'] ) {
					$this->avaliable_providers[ $data['class'] ] = $data['name'];
				}
			}
		}

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
				'nonce'          => wp_create_nonce( 'wp_rest' ),
				'settingsData'   => array(
					'avaliable_providers' => array(
						'value'   => jet_smart_filters()->settings->get( 'avaliable_providers', $default_avaliable_providers ),
						'options' => $this->avaliable_providers,
					),
					'use_indexed_filters' => array(
						'value' => jet_smart_filters()->settings->get( 'use_indexed_filters' ),
					),
					'avaliable_post_types' => array(
						'value'   => jet_smart_filters()->settings->get( 'avaliable_post_types', $default_avaliable_post_types ),
						'options' => $this->get_post_types_for_options(),
					),
					'use_auto_indexing' => array(
						'value' => jet_smart_filters()->settings->get( 'use_auto_indexing' ),
					),
					'url_structure_type' => array(
						'value'   => jet_smart_filters()->settings->get( 'url_structure_type', 'plain' ),
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
						'value'   => jet_smart_filters()->settings->get( 'rewritable_post_types', $default_rewritable_post_types ),
						'options' => $this->get_rewritable_post_types_options(),
					),
					'ajax_request_types' => array(
						'value'   => jet_smart_filters()->settings->get( 'ajax_request_types', 'default' ),
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
					'use_tabindex' => array(
						'value' => jet_smart_filters()->settings->get( 'use_tabindex', false ),
					),
					'tabindex_color' => array(
						'value' => jet_smart_filters()->settings->get( 'tabindex_color', '#0085f2' ),
					),
				),
			);
		}

		/**
		 * Returns rewritable taxonomies list for options
		 */
		public function get_rewritable_post_types_options() {

			$rewritable_post_types_exceptions = apply_filters( 'jet-smart-filters/settings/rewritable-post-types-exceptions', array(
				'jet-popup',
				'jet-menu'
			) );

			$rewritable_post_types = array(
				'post' => get_post_type_object('post')->label
			);

			foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $post_type ) {
				if ( in_array( $post_type->name, $rewritable_post_types_exceptions ) || empty( $post_type->rewrite ) ) {
					continue;
				}
				
				$rewritable_post_types[$post_type->name] = $post_type->label;
			}

			return $rewritable_post_types;
		}

		/**
		 * Returns post types list for options
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
		 * Returns the instance.
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
