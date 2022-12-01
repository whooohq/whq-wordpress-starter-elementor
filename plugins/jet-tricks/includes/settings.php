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

if ( ! class_exists( 'Jet_Tricks_Settings' ) ) {

	/**
	 * Define Jet_Tricks_Settings class
	 */
	class Jet_Tricks_Settings {

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
		public $key = 'jet-tricks-settings';

		/**
		 * [$builder description]
		 * @var null
		 */
		public $builder = null;

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
		public $avaliable_widgets = [];

		/**
		 * [$default_avaliable_extensions description]
		 * @var [type]
		 */
		public $default_avaliable_extensions = [
			'widget_parallax'   => 'true',
			'widget_satellite'  => 'true',
			'widget_tooltip'    => 'true',
			'column_sticky'     => 'true',
			'section_particles' => 'true',
		];

		/**
		 * Init page
		 */
		public function init() {

			foreach ( glob( jet_tricks()->plugin_path( 'includes/addons/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				$slug = basename( $file, '.php' );
				$this->avaliable_widgets[ $slug] = $data['name'];
			}

			add_action( 'jet-styles-manager/compatibility/register-plugin', array( $this, 'register_for_styles_manager' ) );
		}

		/**
		 * Register jet-tricks plugin for styles manager
		 *
		 * @param  object $compatibility_manager JetStyleManager->compatibility instance
		 * @return void
		 */
		public function register_for_styles_manager( $compatibility_manager ) {
			$compatibility_manager->register_plugin( 'jet-tricks', (int) $this->get( 'widgets_load_level', 100 ) );
		}

		/**
		 * [generate_frontend_config_data description]
		 * @return [type] [description]
		 */
		public function get_frontend_config_data() {

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
					'label' => esc_html__( 'Parallax Widget Extension', 'jet-tricks' ),
					'value' => 'widget_parallax',
				],
				[
					'label' => esc_html__( 'Satellite Widget Extension', 'jet-tricks' ),
					'value' => 'widget_satellite',
				],
				[
					'label' => esc_html__( 'Tooltip Widget Extension', 'jet-tricks' ),
					'value' => 'widget_tooltip',
				],
				[
					'label' => esc_html__( 'Sticky Column', 'jet-tricks' ),
					'value' => 'column_sticky',
				],
				[
					'label' => esc_html__( 'Particles', 'jet-tricks' ),
					'value' => 'section_particles',
				],
			];

			$active_extensions = $this->get( 'avaliable_extensions', $this->default_avaliable_extensions );

			$rest_api_url = apply_filters( 'jet-tricks/rest/frontend/url', get_rest_url() );

			return [
				'messages' => [
					'saveSuccess' => esc_html__( 'Saved', 'jet-tricks' ),
					'saveError'   => esc_html__( 'Error', 'jet-tricks' ),
				],
				'ajaxUrl'        => esc_url( admin_url( 'admin-ajax.php' ) ),
				'settingsApiUrl' => $rest_api_url . 'jet-tricks-api/v1/plugin-settings',
				'settingsData' => [
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
				],
			];
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
 * Returns instance of Jet_Tricks_Settings
 *
 * @return object
 */
function jet_tricks_settings() {
	return Jet_Tricks_Settings::get_instance();
}
