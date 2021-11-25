<?php
namespace Jet_Menu;

class Options_Manager {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance = null;

	/**
	 * [$customizer description]
	 * @var null
	 */
	protected $customizer = null;

	/**
	 * Fonts loader instance
	 *
	 * @var object
	 */
	public $fonts_loader = null;

	/**
	 * Options cache
	 *
	 * @var boolean
	 */
	private $options = false;

	/**
	 * [$current_options description]
	 * @var array
	 */
	public $current_options = array();

	/**
	 * Slug DB option field.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	public $options_slug = 'jet_menu_options';

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	public $preset_post_type = 'jet_options_preset';

	/**
	 * [$settings_key description]
	 * @var string
	 */
	public $settings_key = 'jet_preset_settings';

	/**
	 * [$title_key description]
	 * @var string
	 */
	public $title_key    = 'jet_preset_name';

	/**
	 * Preset list
	 *
	 * @var null
	 */
	public $presets = null;

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 *
	 * Save options to DB
	 *
	 * @since 1.0.0
	 */
	public function save_options( $option_name, $options ) {
		update_option( $option_name, $options );
		$this->fonts_loader->reset_fonts_cache();

		do_action( 'jet-menu/options-page/save' );
	}

	/**
	 * Set options externaly.
	 *
	 * @param  array  $options Options array to set.
	 * @return void
	 */
	public function pre_set_options( $options = array() ) {

		if ( empty( $options ) ) {
			$this->options = false;
		} else {
			$this->options = $options;
		}
	}

	/**
	 * Get option value
	 *
	 * @param string $options Option name.
	 * @since 1.0.0
	 */
	public function get_option( $option_name = null, $default = false ) {

		if ( empty( $this->options ) ) {
			$this->options = get_option( $this->options_slug, array() );
		}

		if ( ! $option_name && ! empty( $this->options ) ) {
			return $this->options;
		}

		return isset( $this->options[ $option_name ] ) ? $this->options[ $option_name ] : $default;
	}

	/**
	 * [add_option description]
	 * @param boolean $slug [description]
	 * @param array   $args [description]
	 */
	public function add_option( $slug = false, $args = array() ) {

		if ( ! $slug || empty( $args ) ) {
			return false;
		}

		$this->current_options[ $slug ] = $args;
	}

	/**
	 * Pass menu options key into exported options array
	 *
	 * @param  [type] $options [description]
	 * @return [type]          [description]
	 */
	public function export_menu_options( $options ) {
		$options[] = $this->options_slug;

		return $options;
	}

	/**
	 * Process reset options
	 */
	public function process_reset() {

		if ( ! isset( $_GET['jet-action'] ) || 'reset-options' !== $_GET['jet-action'] ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			die();
		}

		$this->save_options( $this->options_slug, array() );

		wp_redirect(
			add_query_arg( array( 'page' => 'jet-dashboard-settings-page', 'subpage' => 'jet-menu-general-settings' ), esc_url( admin_url( 'admin.php' ) ) )
		);

		die();
	}

	/**
	 * Process settings export
	 *
	 * @return void
	 */
	public function process_export() {

		if ( ! isset( $_GET['jet-action'] ) || 'export-options' !== $_GET['jet-action'] ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			die();
		}

		$options = $this->get_option();

		if ( ! $options ) {
			$options = array();
		}

		$file = 'jet-menu-options-' . date( 'm-d-Y' ) . '.json';

		$data = json_encode( array(
			'jet_menu' => true,
			'options'  => $options,
		) );

		session_write_close();

		header( 'Pragma: public' );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Cache-Control: public' );
		header( 'Content-Description: File Transfer' );
		header( 'Content-type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename="' . $file . '"' );
		header( 'Content-Transfer-Encoding: binary' );

		echo $data;

		die();
	}

	/**
	 * Process settings import
	 *
	 * @return void
	 */
	public function process_import() {

		if ( ! current_user_can( 'manage_options' ) ) {
			die();
		}

		$options = isset( $_POST['data'] ) ? $_POST['data'] : array();

		if ( empty( $options['jet_menu'] ) || empty( $options['options'] ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Incorrect data in options file', 'jet-menu' ),
			) );
		}

		$this->save_options( $this->options_slug, $options['options'] );

		wp_send_json_success( array(
			'message' => esc_html__( 'Options successfully imported. Page will be reloaded.', 'jet-menu' ),
		) );
	}

	/**
	 * @return array
	 */
	public function get_options_data() {
		return apply_filters( 'jet-menu/options-manager/options-list', $this->current_options );
	}

	/**
	 * [render_box_shadow_options description]
	 * @return [type] [description]
	 */
	public function render_background_options( $args ) {

		$args = wp_parse_args( $args, array(
			'label'    => '',
			'name'     => '',
			'defaults' => array(),
		) );

		include jet_menu()->get_template( 'admin/background-vue-group.php' );
	}

	/**
	 * [add_background_options description]
	 * @param [type] $args [description]
	 */
	public function add_background_options( $slug = false ) {

		if ( ! $slug ) {
			return false;
		}

		$background_options = array(
			$slug . '-switch' => array(
				'value' => $this->get_option( $slug . '-switch', 'false' ),
			),

			$slug . '-color' => array(
				'value' => $this->get_option( $slug . '-color', '#ffffff' ),
			),

			$slug . '-gradient-switch' => array(
				'value' => $this->get_option( $slug . '-gradient-switch', false ),
			),

			$slug . '-second-color' => array(
				'value' => $this->get_option( $slug . '-second-color', '' ),
			),

			$slug . '-direction' => array(
				'value'   => $this->get_option( $slug . '-direction', 'right' ),
				'options' => $this->get_direction_select_options(),
			),

			$slug . '-image' => array(
				'value' => $this->get_option( $slug . '-image', '' ),
			),

			$slug . '-position' => array(
				'value'   => $this->get_option( $slug . '-position', '' ),
				'options' => $this->get_position_select_options(),
			),

			$slug . '-attachment' => array(
				'value'   => $this->get_option( $slug . '-attachment', '' ),
				'options' => $this->get_attachment_select_options(),
			),

			$slug . '-repeat' => array(
				'value'   => $this->get_option( $slug . '-repeat', '' ),
				'options' => $this->get_repeat_select_options(),
			),

			$slug . '-size' => array(
				'value'   => $this->get_option( $slug . '-size', '' ),
				'options' => $this->get_size_select_options(),
			),
		);

		$this->current_options = array_merge( $this->current_options, $background_options );

	}

	/**
	 * [render_box_shadow_options description]
	 * @return [type] [description]
	 */
	public function render_border_options( $args ) {

		$args = wp_parse_args( $args, array(
			'label'    => '',
			'name'     => '',
			'defaults' => array(),
		) );

		include jet_menu()->get_template( 'admin/border-vue-group.php' );
	}

	/**
	 * [add_border_options description]
	 * @param boolean $slug [description]
	 */
	public function add_border_options( $slug = false ) {

		if ( ! $slug ) {
			return false;
		}


		$border_options = array(
			$slug . '-border-switch' => array(
				'value' => $this->get_option( $slug . '-border-switch', 'false' ),
			),

			$slug . '-border-style' => array(
				'value'   => $this->get_option( $slug . '-border-style', '' ),
				'options' => $this->get_border_style_select_options(),
			),

			$slug . '-border-width' => array(
				'value' => $this->get_option( $slug . '-border-width', jet_menu_tools()->get_default_dimensions() ),
			),

			$slug . '-border-color' => array(
				'value' => $this->get_option( $slug . '-border-color', '' ),
			),
		);

		$this->current_options = array_merge( $this->current_options, $border_options );

	}

	/**
	 * [render_box_shadow_options description]
	 * @return [type] [description]
	 */
	public function render_box_shadow_options( $args ) {

		$args = wp_parse_args( $args, array(
			'label'    => '',
			'name'     => '',
			'defaults' => array(),
		) );

		include jet_menu()->get_template( 'admin/box-shadow-vue-group.php' );
	}

	/**
	 * [add_box_shadow_options description]
	 * @param boolean $slug [description]
	 */
	public function add_box_shadow_options( $slug = false ) {

		if ( ! $slug ) {
			return false;
		}

		$border_options = array(
			$slug . '-box-shadow-switch' => array(
				'value' => $this->get_option( $slug . '-box-shadow-switch', false ),
			),

			$slug . '-box-shadow-inset' => array(
				'value' => $this->get_option( $slug . '-box-shadow-inset', false ),
			),

			$slug . '-box-shadow-color' => array(
				'value' => $this->get_option( $slug . '-box-shadow-color', '' ),
			),

			$slug . '-box-shadow-h' => array(
				'value' => $this->get_option( $slug . '-box-shadow-h', '' ),
			),

			$slug . '-box-shadow-v' => array(
				'value' => $this->get_option( $slug . '-box-shadow-v', '' ),
			),

			$slug . '-box-shadow-blur' => array(
				'value' => $this->get_option( $slug . '-box-shadow-blur', '' ),
			),

			$slug . '-box-shadow-spread' => array(
				'value' => $this->get_option( $slug . '-box-shadow-spread', '' ),
			),
		);

		$this->current_options = array_merge( $this->current_options, $border_options );

	}

	/**
	 * [render_typography_options description]
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function render_typography_options( $args ) {

		$args = wp_parse_args( $args, array(
			'label'    => '',
			'name'     => '',
			'defaults' => array(),
		) );

		include jet_menu()->get_template( 'admin/typography-vue-group.php' );
	}

	/**
	 * [add_typography_options description]
	 * @param boolean $slug [description]
	 */
	public function add_typography_options( $slug = false ) {

		if ( ! $slug ) {
			return false;
		}

		$typography_options = array(
			$slug . '-switch' => array(
				'value' => $this->get_option( $slug . '-switch', false ),
			),

			$slug . '-font-family' => array(
				'value'   => $this->get_option( $slug . '-font-family', '' ),
				'options' => $this->get_fonts_select_options(),
			),

			$slug . '-subset' => array(
				'value'   => $this->get_option( $slug . '-subset', '' ),
				'options' => $this->get_font_subset_select_options(),
			),

			$slug . '-font-size' => array(
				'value'   => $this->get_option( $slug . '-font-size', '' ),
			),

			$slug . '-line-height' => array(
				'value'   => $this->get_option( $slug . '-line-height', '' ),
			),

			$slug . '-font-weight' => array(
				'value'   => $this->get_option( $slug . '-font-weight', '' ),
				'options' => $this->get_font_weight_select_options(),
			),

			$slug . '-text-transform' => array(
				'value'   => $this->get_option( $slug . '-text-transform', '' ),
				'options' => $this->get_text_transform_select_options(),
			),

			$slug . '-font-style' => array(
				'value'   => $this->get_option( $slug . '-font-style', '' ),
				'options' => $this->get_font_style_select_options(),
			),

			$slug . '-letter-spacing' => array(
				'value' => $this->get_option( $slug . '-letter-spacing', '' ),
			),
		);

		$this->current_options = array_merge( $this->current_options, $typography_options );

	}

	/**
	 * [get_aligment_select_options description]
	 * @return [type] [description]
	 */
	public function get_aligment_select_options() {
		return array(
			array(
				'label' => esc_html__( 'Start', 'jet-menu' ),
				'value' => 'flex-start',
			),
			array(
				'label' => esc_html__( 'Center', 'jet-menu' ),
				'value' => 'center',
			),
			array(
				'label' => esc_html__( 'End', 'jet-menu' ),
				'value' => 'flex-end',
			),
			array(
				'label' => esc_html__( 'Stretch', 'jet-menu' ),
				'value' => 'stretch',
			),
		);
	}

	/**
	 * [get_direction_select_options description]
	 * @return [type] [description]
	 */
	public function get_direction_select_options() {
		return array(
			array(
				'label' => esc_html__( 'From Left to Right', 'jet-menu' ),
				'value' => 'right',
			),
			array(
				'label' => esc_html__( 'From Right to Left', 'jet-menu' ),
				'value' => 'left',
			),
			array(
				'label' => esc_html__( 'From Top to Bottom', 'jet-menu' ),
				'value' => 'bottom',
			),
			array(
				'label' => esc_html__( 'From Bottom to Top', 'jet-menu' ),
				'value' => 'top',
			),
		);
	}

	/**
	 * [get_position_select_options description]
	 * @return [type] [description]
	 */
	public function get_position_select_options() {
		return array(
			array(
				'label' => esc_html__( 'Default', 'jet-menu' ),
				'value' => '',
			),
			array(
				'label' => esc_html__( 'Top Left', 'jet-menu' ),
				'value' => 'top left',
			),
			array(
				'label' => esc_html__( 'Top Center', 'jet-menu' ),
				'value' => 'top center',
			),
			array(
				'label' => esc_html__( 'Top Right', 'jet-menu' ),
				'value' => 'top right',
			),
			array(
				'label' => esc_html__( 'Center Left', 'jet-menu' ),
				'value' => 'center left',
			),
			array(
				'label' => esc_html__( 'Center Center', 'jet-menu' ),
				'value' => 'center center',
			),
			array(
				'label' => esc_html__( 'Center Right', 'jet-menu' ),
				'value' => 'center right',
			),
			array(
				'label' => esc_html__( 'Bottom Left', 'jet-menu' ),
				'value' => 'bottom left',
			),
			array(
				'label' => esc_html__( 'Bottom Center', 'jet-menu' ),
				'value' => 'bottom center',
			),
			array(
				'label' => esc_html__( 'Bottom Right', 'jet-menu' ),
				'value' => 'bottom right',
			),
		);
	}

	/**
	 * [get_attachment_select_options description]
	 * @return [type] [description]
	 */
	public function get_attachment_select_options() {
		return array(
			array(
				'label' => esc_html__( 'Default', 'jet-menu' ),
				'value' => '',
			),
			array(
				'label' => esc_html__( 'Scroll', 'jet-menu' ),
				'value' => 'scroll',
			),
			array(
				'label' => esc_html__( 'Fixed', 'jet-menu' ),
				'value' => 'fixed',
			),
		);
	}

	/**
	 * [get_repeat_select_options description]
	 * @return [type] [description]
	 */
	public function get_repeat_select_options() {
		return array(
			array(
				'label' => esc_html__( 'Default', 'jet-menu' ),
				'value' => '',
			),
			array(
				'label' => esc_html__( 'No Repeat', 'jet-menu' ),
				'value' => 'no-repeat',
			),
			array(
				'label' => esc_html__( 'Repeat', 'jet-menu' ),
				'value' => 'repeat',
			),
			array(
				'label' => esc_html__( 'Repeat X', 'jet-menu' ),
				'value' => 'repeat-x',
			),
			array(
				'label' => esc_html__( 'Repeat Y', 'jet-menu' ),
				'value' => 'repeat-y',
			),
		);
	}

	/**
	 * [get_size_select_options description]
	 * @return [type] [description]
	 */
	public function get_size_select_options() {
		return array(
			array(
				'label' => esc_html__( 'Default', 'jet-menu' ),
				'value' => '',
			),
			array(
				'label' => esc_html__( 'Auto', 'jet-menu' ),
				'value' => 'auto',
			),
			array(
				'label' => esc_html__( 'Cover', 'jet-menu' ),
				'value' => 'cover',
			),
			array(
				'label' => esc_html__( 'Contain', 'jet-menu' ),
				'value' => 'contain',
			),
		);
	}

	/**
	 * [get_border_style_select_options description]
	 * @return [type] [description]
	 */
	public function get_border_style_select_options() {
		return array(
			array(
				'label' => esc_html__( 'None', 'jet-menu' ),
				'value' => 'none',
			),
			array(
				'label' => esc_html__( 'Solid', 'jet-menu' ),
				'value' => 'solid',
			),
			array(
				'label' => esc_html__( 'Double', 'jet-menu' ),
				'value' => 'double',
			),
			array(
				'label' => esc_html__( 'Dotted', 'jet-menu' ),
				'value' => 'dotted',
			),
			array(
				'label' => esc_html__( 'Dashed', 'jet-menu' ),
				'value' => 'dashed',
			),
		);
	}

	/**
	 * [get_font_weight_select_options description]
	 * @return [type] [description]
	 */
	public function get_font_weight_select_options() {
		return array(
			array(
				'label' => esc_html__( 'Default', 'jet-menu' ),
				'value' => '',
			),
			array(
				'label' => esc_html__( '100', 'jet-menu' ),
				'value' => '100',
			),
			array(
				'label' => esc_html__( '200', 'jet-menu' ),
				'value' => '200',
			),
			array(
				'label' => esc_html__( '300', 'jet-menu' ),
				'value' => '300',
			),
			array(
				'label' => esc_html__( '400', 'jet-menu' ),
				'value' => '400',
			),
			array(
				'label' => esc_html__( '500', 'jet-menu' ),
				'value' => '500',
			),
			array(
				'label' => esc_html__( '600', 'jet-menu' ),
				'value' => '600',
			),
			array(
				'label' => esc_html__( '700', 'jet-menu' ),
				'value' => '700',
			),
			array(
				'label' => esc_html__( '800', 'jet-menu' ),
				'value' => '800',
			),
			array(
				'label' => esc_html__( '900', 'jet-menu' ),
				'value' => '900',
			),
		);
	}

	/**
	 * [get_text_transform_select_options description]
	 * @return [type] [description]
	 */
	public function get_text_transform_select_options() {
		return array(
			array(
				'label' => esc_html__( 'Default', 'jet-menu' ),
				'value' => '',
			),
			array(
				'label' => esc_html__( 'Normal', 'jet-menu' ),
				'value' => 'none',
			),
			array(
				'label' => esc_html__( 'Uppercase', 'jet-menu' ),
				'value' => 'uppercase',
			),
			array(
				'label' => esc_html__( 'Lowercase', 'jet-menu' ),
				'value' => 'lowercase',
			),
			array(
				'label' => esc_html__( 'Capitalize', 'jet-menu' ),
				'value' => 'capitalize',
			),
		);
	}

	/**
	 * [get_font_style_select_options description]
	 * @return [type] [description]
	 */
	public function get_font_style_select_options() {

		return array(
			array(
				'label' => esc_html__( 'Default', 'jet-menu' ),
				'value' => '',
			),
			array(
				'label' => esc_html__( 'Normal', 'jet-menu' ),
				'value' => 'normal',
			),
			array(
				'label' => esc_html__( 'Italic', 'jet-menu' ),
				'value' => 'italic',
			),
			array(
				'label' => esc_html__( 'Oblique', 'jet-menu' ),
				'value' => 'oblique',
			),
		);
	}

	/**
	 * [get_fonts_select_options description]
	 * @return [type] [description]
	 */
	public function get_fonts_select_options() {

		$fonts_list = jet_menu_dynmic_css()->get_fonts_list();

		$fonts_select_options = [];

		if ( ! empty( $fonts_list ) ) {

			foreach ( $fonts_list as $font_name => $font_slug ) {

				if ( 0 !== $font_name ) {
					$fonts_select_options[] = array(
						'label' => $font_name,
						'value' => $font_name,
					);
				} else {
					$fonts_select_options[] = array(
						'label' => $font_slug,
						'value' => $font_name,
					);
				}
			}
		}

		return $fonts_select_options;
	}

	/**
	 * [get_font_subset_select_options description]
	 * @return [type] [description]
	 */
	public function get_font_subset_select_options() {
		return array(
			array(
				'label' => esc_html__( 'Latin', 'jet-menu' ),
				'value' => 'latin',
			),
			array(
				'label' => esc_html__( 'Greek', 'jet-menu' ),
				'value' => 'greek',
			),
			array(
				'label' => esc_html__( 'Cyrillic', 'jet-menu' ),
				'value' => 'cyrillic',
			),
		);
	}

	/**
	 * [get_options_page_config description]
	 * @return [type] [description]
	 */
	public function get_options_page_config() {

		$rest_api_url = apply_filters( 'jet-menu/rest/admin/url', get_rest_url() );

		return array(
			'optionsApiUrl'    => $rest_api_url . 'jet-menu-api/v1/plugin-settings',
			'rawOptionsData'   => $this->get_option(),
			'optionPresetList' => jet_menu()->settings_manager->options_manager->get_presets_select_options(),
			'importUrl'        => add_query_arg( array( 'jet-action' => 'import-options' ), esc_url( admin_url( 'admin.php' ) ) ),
			'exportUrl'        => add_query_arg( array( 'jet-action' => 'export-options' ), esc_url( admin_url( 'admin.php' ) ) ),
			'resetUrl'         => add_query_arg( array( 'jet-action' => 'reset-options' ), esc_url( admin_url( 'admin.php' ) ) ),
			'optionsPageUrl'   => add_query_arg( array( 'page' => 'jet-dashboard-settings-page', 'subpage' => 'jet-menu-general-settings' ), esc_url( admin_url( 'admin.php' ) ) ),
			'optionsData'      => $this->get_options_data(),
			'arrowsIcons'      => jet_menu_tools()->get_arrows_icons(),
			'iconsFetchJson'   => jet_menu()->plugin_url( 'assets/public/lib/font-awesome/js/solid.js' ),
			'templateList'     => jet_menu_tools()->get_elementor_templates_select_options(),
		);
	}

	/**
	 * Add widget settings
	 *
	 * @param object $widget Widget instance.
	 */
	public function add_widget_settings( $widget ) {

		$presets = $this->get_presets();

		if ( empty( $presets ) ) {
			return;
		}

		$presets = array ( '0' => esc_html__( 'Not Selected', 'jet-menu' ) ) + $presets;

		$widget->add_control( 'preset', array (
			'label'   => esc_html__( 'Menu Preset', 'jet-menu' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => '',
			'options' => $presets,
		) );

	}

	/**
	 * Register post type
	 *
	 * @return void
	 */
	public function register_post_type() {
		register_post_type( $this->preset_post_type, array (
			'public'      => false,
			'has_archive' => false,
			'rewrite'     => false,
			'can_export'  => true,
		) );
	}

	/**
	 * Create preset callback.
	 *
	 * @return void
	 */
	public function create_preset() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array (
				'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
			) );
		}

		$name     = isset( $_POST[ 'name' ] ) ? esc_attr( $_POST[ 'name' ] ) : false;
		$settings = isset( $_POST[ 'settings' ] ) ? $_POST[ 'settings' ] : false;

		if ( ! $settings ) {
			wp_send_json_error( array (
				'message' => esc_html__( 'Settings not provided', 'jet-menu' ),
			) );
		}

		if ( ! $name ) {
			wp_send_json_error( array (
				'message' => esc_html__( 'Please, specify preset name', 'jet-menu' ),
			) );
		}

		$post_title = 'jet_preset_' . md5( $name );

		if ( post_exists( $post_title ) ) {
			wp_send_json_error( array (
				'message' => esc_html__( 'Preset with the same name already exists, please change it', 'jet-menu' ),
			) );
		}

		$preset_id = wp_insert_post( array (
			'post_type'   => $this->preset_post_type,
			'post_status' => 'publish',
			'post_title'  => $post_title,
			'meta_input'  => array (
				$this->title_key    => esc_attr( $name ),
				$this->settings_key => $settings,
			),
		) );

		do_action( 'jet-menu/presets/created' );

		wp_send_json_success( array (
			'message' => esc_html__( 'Settings preset have been created', 'jet-menu' ),
			'preset'  => array (
				'id'   => $preset_id,
				'name' => esc_attr( $name ),
			),
			'presets' => $this->get_presets_select_options(),
		) );

	}

	/**
	 * Update preset callback.
	 *
	 * @return void
	 */
	public function update_preset() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
			) );
		}

		$preset   = isset( $_POST['preset'] ) ? absint( $_POST['preset'] ) : false;
		$settings = isset( $_POST['settings'] ) ? $_POST['settings'] : false;

		if ( ! $preset ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Preset ID not defined', 'jet-menu' ),
			) );
		}

		update_post_meta( $preset, $this->settings_key, $settings );

		do_action( 'jet-menu/presets/updated' );

		wp_send_json_success( array(
			'message' => esc_html__( 'Preset have been updated', 'jet-menu' ),
		) );
	}

	/**
	 * Load preset callback.
	 *
	 * @return void
	 */
	public function load_preset() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
			) );
		}

		$preset = isset( $_POST['preset'] ) ? absint( $_POST['preset'] ) : false;

		if ( ! $preset ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Preset ID not defined', 'jet-menu' ),
			) );
		}

		$preset_settings = get_post_meta( $preset, $this->settings_key, true );

		update_option( jet_menu()->settings_manager->options_manager->options_slug, $preset_settings );

		do_action( 'jet-menu/presets/loaded' );

		wp_send_json_success( array(
			'message'  => esc_html__( 'Preset have been applyed', 'jet-menu' ),
		) );
	}

	/**
	 * Delete preset callback
	 *
	 * @return void
	 */
	public function delete_preset() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
			) );
		}

		$preset = isset( $_POST['preset'] ) ? absint( $_POST['preset'] ) : false;

		if ( ! $preset ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Preset ID not defined', 'jet-menu' ),
			) );
		}

		wp_delete_post( $preset, true );

		do_action( 'jet-menu/presets/deleted' );

		wp_send_json_success( array(
			'message' => esc_html__( 'Preset have been removing', 'jet-menu' ),
			'presets' => $this->get_presets_select_options(),
		) );
	}

	/**
	 * Register presets settings
	 *
	 * @param  object $builder      Builder instance.
	 * @param  object $options_page Options page instance.
	 * @return void
	 */
	public function register_presets_settings( $builder, $options_page ) {

		ob_start();
		include jet_menu()->get_template( 'admin/presets-controls.php' );
		$controls = ob_get_clean();

		$builder->register_control(
			array(
				'jet-presets-controls' => array(
					'type'   => 'html',
					'parent' => 'presets_tab',
					'class'  => 'jet-menu-presets',
					'html'   => $controls,
				),
			)
		);

	}

	/**
	 * Get presets list
	 *
	 * @return array
	 */
	public function get_presets() {

		if ( null !== $this->presets ) {
			return $this->presets;
		}

		$presets = get_posts( array(
			'post_type'      => $this->preset_post_type,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		) );

		if ( empty( $presets ) ) {
			$this->presets = array();
			return $this->presets;
		}

		$result = array();

		foreach ( $presets as $preset ) {
			$result[ $preset->ID ] = get_post_meta( $preset->ID, $this->title_key, true );
		}

		$this->presets = $result;

		return $this->presets;

	}

	/**
	 * [get_presets_select_options description]
	 * @return [type] [description]
	 */
	public function get_presets_select_options() {

		$presets = $this->get_presets();

		$preset_select_options = [];

		if ( ! empty( $presets ) ) {

			$preset_select_options[] = array(
				'label' => esc_html( 'None', 'jet-menu' ),
				'value' => '',
			);

			foreach ( $presets as $preset_slug => $preset_name ) {
				$preset_select_options[] = array(
					'label' => $preset_name,
					'value' => $preset_slug,
				);
			}
		}

		return $preset_select_options;
	}

	/**
	 * Init Font Manager
	 */
	public function init_font_manager() {
		$module_data = jet_menu()->module_loader->get_included_module_data( 'cherry-x-customizer.php' );

		$this->customizer = new \CX_Customizer(
			array(
				'prefix'     => 'jet-menu',
				'options'    => array(),
				'path'       => $module_data['path'],
				'just_fonts' => true,
			)
		);

		$this->fonts_loader = new \CX_Fonts_Manager(
			array(
				'prefix'    => $this->options_slug,
				'type'      => 'option',
				'single'    => true,
				'get_fonts' => function() {
					return $this->customizer->get_fonts();
				},
				'options'   => array(
					// MainMenu(Legacy)
					'main' => array(
						'family'  => 'jet-top-menu-font-family',
						'style'   => 'jet-top-menu-font-style',
						'weight'  => 'jet-top-menu-font-weight',
						'charset' => 'jet-top-menu-subset',
					),
					'main-desc' => array(
						'family'  => 'jet-top-menu-desc-font-family',
						'style'   => 'jet-top-menu-desc-font-style',
						'weight'  => 'jet-top-menu-desc-font-weight',
						'charset' => 'jet-top-menu-desc-subset',
					),
					'sub' => array(
						'family'  => 'jet-sub-menu-font-family',
						'style'   => 'jet-sub-menu-font-style',
						'weight'  => 'jet-sub-menu-font-weight',
						'charset' => 'jet-sub-menu-subset',
					),
					'sub-desc' => array(
						'family'  => 'jet-sub-menu-desc-font-family',
						'style'   => 'jet-sub-menu-desc-font-style',
						'weight'  => 'jet-sub-menu-desc-font-weight',
						'charset' => 'jet-sub-menu-desc-subset',
					),
					'top-badge' => array(
						'family'  => 'jet-menu-top-badge-font-family',
						'style'   => 'jet-menu-top-badge-font-style',
						'weight'  => 'jet-menu-top-badge-font-weight',
						'charset' => 'jet-menu-top-badge-subset',
					),
					'sub-badge' => array(
						'family'  => 'jet-menu-sub-badge-font-family',
						'style'   => 'jet-menu-sub-badge-font-style',
						'weight'  => 'jet-menu-sub-badge-font-weight',
						'charset' => 'jet-menu-sub-badge-subset',
					),
					// MegaMenu
					'mega-menu-top' => array(
						'family'  => 'jet-mega-menu-top-typography-font-family',
						'style'   => 'jet-mega-menu-top-typography-font-style',
						'weight'  => 'jet-mega-menu-top-typography-font-weight',
						'charset' => 'jet-mega-menu-top-typography-subset',
					),
					'mega-menu-sub' => array(
						'family'  => 'jet-mega-menu-sub-typography-font-family',
						'style'   => 'jet-mega-menu-sub-typography-font-style',
						'weight'  => 'jet-mega-menu-sub-typography-font-weight',
						'charset' => 'jet-mega-menu-sub-typography-subset',
					),
					'mega-menu-dropdown-top' => array(
						'family'  => 'jet-mega-menu-dropdown-top-typography-font-family',
						'style'   => 'jet-mega-menu-dropdown-top-typography-font-style',
						'weight'  => 'jet-mega-menu-dropdown-top-typography-font-weight',
						'charset' => 'jet-mega-menu-dropdown-top-typography-subset',
					),
					'mega-menu-dropdown-sub' => array(
						'family'  => 'jet-mega-menu-dropdown-sub-typography-font-family',
						'style'   => 'jet-mega-menu-dropdown-sub-typography-font-style',
						'weight'  => 'jet-mega-menu-dropdown-sub-typography-font-weight',
						'charset' => 'jet-mega-menu-dropdown-sub-typography-subset',
					),

					'mobile-toggle-typo' => array(
						'family'  => 'jet-menu-mobile-toggle-text-font-family',
						'style'   => 'jet-menu-mobile-toggle-text-font-style',
						'weight'  => 'jet-menu-mobile-toggle-text-font-weight',
						'charset' => 'jet-menu-mobile-toggle-text-subset',
					),
					'mobile-back-typo' => array(
						'family'  => 'jet-menu-mobile-back-text-font-family',
						'style'   => 'jet-menu-mobile-back-text-font-style',
						'weight'  => 'jet-menu-mobile-back-text-font-weight',
						'charset' => 'jet-menu-mobile-back-text-subset',
					),
					'mobile-breadcrumbs-typo' => array(
						'family'  => 'jet-menu-mobile-breadcrumbs-text-font-family',
						'style'   => 'jet-menu-mobile-breadcrumbs-text-font-style',
						'weight'  => 'jet-menu-mobile-breadcrumbs-text-font-weight',
						'charset' => 'jet-menu-mobile-breadcrumbs-text-subset',
					),
					'mobile-label-typo' => array(
						'family'  => 'jet-mobile-items-label-font-family',
						'style'   => 'jet-mobile-items-label-font-style',
						'weight'  => 'jet-mobile-items-label-font-weight',
						'charset' => 'jet-mobile-items-label-subset',
					),
					'mobile-items-desc' => array(
						'family'  => 'jet-mobile-items-desc-font-family',
						'style'   => 'jet-mobile-items-desc-font-style',
						'weight'  => 'jet-mobile-items-desc-font-weight',
						'charset' => 'jet-mobile-items-desc-subset',
					),
					'mobile-badge-typo' => array(
						'family'  => 'jet-mobile-items-badge-font-family',
						'style'   => 'jet-mobile-items-badge-font-style',
						'weight'  => 'jet-mobile-items-badge-font-weight',
						'charset' => 'jet-mobile-items-badge-subset',
					),
				),
			)
		);
	}

	/**
	 * Init Options Modules
	 */
	public function init_options() {
		$options_modules = array(
			'general' => array(
				'class'    => '\\Jet_Menu\\Options_Manager\\General_Options',
				'path'     => jet_menu()->plugin_path( 'includes/settings/options-modules/general-options.php' ),
			),

			'mobile-menu' => array(
				'class'    => '\\Jet_Menu\\Options_Manager\\Mobile_Menu_Options',
				'path'     => jet_menu()->plugin_path( 'includes/settings/options-modules/mobile-menu-options.php' ),
			),
		);

		if ( ! filter_var( $this->get_option( 'plugin-nextgen-edition', 'false' ), FILTER_VALIDATE_BOOLEAN ) ) {
			$options_modules['desktop-menu'] = array(
				'class'    => '\\Jet_Menu\\Options_Manager\\Desktop_Menu_Options',
				'path'     => jet_menu()->plugin_path( 'includes/settings/options-modules/desktop-menu-options.php' ),
			);
		} else {
			$options_modules['main-menu'] = array(
				'class'    => '\\Jet_Menu\\Options_Manager\\Main_Menu_Options',
				'path'     => jet_menu()->plugin_path( 'includes/settings/options-modules/main-menu-options.php' ),
			);
		}

		foreach ( $options_modules as $module => $module_data ) {
			$path = $module_data['path'];

			if ( ! file_exists( $path ) ) {
				continue;
			}

			require $path;

			$class = $module_data['class'];

			if ( ! class_exists( $class ) ) {
				continue;
			}

			new $class();
		}
	}

	/**pre_set_options
	 * Class constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->init_font_manager();

		$this->init_options();

		add_action( 'admin_init', array( $this, 'process_export' ) );

		add_action( 'admin_init', array( $this, 'process_reset' ) );

		add_action( 'wp_ajax_jet_menu_import_options', array( $this, 'process_import' ) );

		add_filter( 'jet-data-importer/export/options-to-export', array( $this, 'export_menu_options' ) );

		add_action( 'init', array( $this, 'register_post_type' ) );

		add_action( 'jet-menu/options-page/before-render', array( $this, 'register_presets_settings' ), 10, 2 );

		add_action( 'jet-menu/widgets/mega-menu/controls', array( $this, 'add_widget_settings' ) );

		add_action( 'wp_ajax_jet_menu_create_preset', array( $this, 'create_preset' ) );

		add_action( 'wp_ajax_jet_menu_update_preset', array( $this, 'update_preset' ) );

		add_action( 'wp_ajax_jet_menu_load_preset', array( $this, 'load_preset' ) );

		add_action( 'wp_ajax_jet_menu_delete_preset', array( $this, 'delete_preset' ) );
	}
}