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

if ( ! class_exists( 'Jet_Blocks_Settings' ) ) {

	/**
	 * Define Jet_Blocks_Settings class
	 */
	class Jet_Blocks_Settings {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * [$key description]
		 * @var string
		 */
		public $key = 'jet-blocks-settings';

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
		 * Available Widgets array
		 *
		 * @var array
		 */
		public $avaliable_widgets = array();

		/**
		 * Default Available Extensions
		 *
		 * @var array
		 */
		public $default_avaliable_ext = array(
			'sticky_section' => 'true',
			'column_order'   => 'true',
		);

		/**
		 * Init page
		 */
		public function init() {

			foreach ( glob( jet_blocks()->plugin_path( 'includes/widgets/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				$slug = basename( $file, '.php' );
				$this->avaliable_widgets[ $slug] = $data['name'];
			}

			add_action( 'jet-styles-manager/compatibility/register-plugin', array( $this, 'register_for_styles_manager' ) );
		}

		/**
		 * Generate frontend config data
		 * @return array
		 */
		public function generate_frontend_config_data() {

			$default_active_widgets = [];

			foreach ( $this->avaliable_widgets as $slug => $name ) {

				$avaliable_widgets[] = [
					'label' => $name,
					'value' => $slug,
				];

				$default_active_widgets[ $slug ] = 'true';
			}

			$active_widgets = $this->get( 'avaliable_widgets', $default_active_widgets );

			$avaliable_extensions = [
				[
					'label' => esc_html__( 'Sticky Section', 'jet-blocks' ),
					'value' => 'sticky_section',
				],
				[
					'label' => esc_html__( 'Column Order', 'jet-blocks' ),
					'value' => 'column_order',
				],
			];

			$active_extensions = $this->get( 'avaliable_extensions', $this->default_avaliable_ext );

			$rest_api_url = apply_filters( 'jet-blocks/rest/frontend/url', get_rest_url() );


			$breadcrumbs_taxonomy_options = [];

			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			if ( is_array( $post_types ) && ! empty( $post_types ) ) {

				foreach ( $post_types as $post_type ) {
					$taxonomies = get_object_taxonomies( $post_type->name, 'objects' );

					if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {

						$options = [
							[
								'label' => esc_html__( 'None', 'jet-blocks' ),
								'value' => '',
							]
						];

						foreach ( $taxonomies as $tax ) {

							if ( ! $tax->public ) {
								continue;
							}

							$options[] = [
								'label' => $tax->labels->singular_name,
								'value' => $tax->name,
							];
						}

						$breadcrumbs_taxonomy_options[ 'breadcrumbs_taxonomy_' . $post_type->name ] = array(
							'value'   => $this->get( 'breadcrumbs_taxonomy_' . $post_type->name, ( 'post' === $post_type->name ) ? 'category' : '' ),
							'options' => $options,
						);
					}
				}
			}

			$settingsData = [
				'widgets_load_level'      => [
					'value' => $this->get( 'widgets_load_level', 100 ),
					'options' => [
						[
							'label' => 'None',
							'value' => 0,
						],
						[
							'label' => 'Low',
							'value' => 25,
						],
						[
							'label' => 'Medium',
							'value' => 50,
						],
						[
							'label' => 'Advanced',
							'value' => 75,
						],
						[
							'label' => 'Full',
							'value' => 100,
						],
					],
				],
				'avaliable_widgets'       =>[
					'value'   => $active_widgets,
					'options' => $avaliable_widgets,
				] ,
				'avaliable_extensions'    => [
					'value'   => $active_extensions,
					'options' => $avaliable_extensions,
				],
			];

			$this->settings_page_config = [
				'messages' => [
					'saveSuccess' => esc_html__( 'Saved', 'jet-blocks' ),
					'saveError'   => esc_html__( 'Error', 'jet-blocks' ),
				],
				'ajaxUrl'        => esc_url( admin_url( 'admin-ajax.php' ) ),
				'settingsApiUrl' => $rest_api_url . 'jet-blocks-api/v1/plugin-settings',
				'settingsData'   => array_merge( $settingsData, $breadcrumbs_taxonomy_options ),
			];

			return $this->settings_page_config;
		}

		/**
		 * Register jet-blocks plugin for styles manager
		 *
		 * @param  object $compatibility_manager JetStyleManager->compatibility instance
		 * @return void
		 */
		public function register_for_styles_manager( $compatibility_manager ) {
			$compatibility_manager->register_plugin( 'jet-blocks', (int) $this->get( 'widgets_load_level', 100 ) );
		}

		/**
		 * Return settings page URL
		 *
		 * @param $subpage
		 *
		 * @return string
		 */
		public function get_settings_page_link( $subpage = 'general' ) {

			return add_query_arg(
				array(
					'page'    => 'jet-dashboard-settings-page',
					'subpage' => 'jet-blocks-' . $subpage . '-settings',
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
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
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

/**
 * Returns instance of Jet_Blocks_Settings
 *
 * @return object
 */
function jet_blocks_settings() {
	return Jet_Blocks_Settings::get_instance();
}

jet_blocks_settings()->init();
