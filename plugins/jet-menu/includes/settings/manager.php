<?php
namespace Jet_Menu;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Controller class
 */
class Settings_Manager {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * @var null
	 */
	public $options_manager = null;

	/**
	 * [$subpage_modules description]
	 * @var array
	 */
	public $subpage_modules = array();

	/**
	 * Holder for current menu ID
	 * @var integer
	 */
	protected $current_menu_id = null;

	/**
	 * Jet Menu settings page
	 *
	 * @var string
	 */
	protected $menu_item_meta_key = 'jet_menu_settings';

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

	// Here initialize our namespace and resource name.
	public function __construct() {

		$this->options_manager = new Options_Manager();

		if ( is_admin() ) {
			$this->admin_init();
		}
	}

	/**
	 * Admin modules init
	 */
	public function admin_init() {
		$this->subpage_modules = apply_filters( 'jet-menu/settings/registered-subpage-modules', array(
			'jet-menu-general-settings' => array(
				'class' => '\\Jet_Menu\\Settings\\General',
				'args'  => array(),
			),
			'jet-menu-desktop-menu-settings' => array(
				'class' => '\\Jet_Menu\\Settings\\Desktop_Menu',
				'args'  => array(),
			),
			'jet-menu-main-menu-settings' => array(
				'class' => '\\Jet_Menu\\Settings\\Main_Menu',
				'args'  => array(),
			),
			'jet-menu-mobile-menu-settings' => array(
				'class' => '\\Jet_Menu\\Settings\\Mobile_Menu',
				'args'  => array(),
			),
		) );

		add_action( 'init', array( $this, 'register_settings_category' ), 10 );

		add_action( 'init', array( $this, 'init_plugin_subpage_modules' ), 10 );

		add_action( 'init', array( $this, 'register_jet_dashboard_notice' ), 10 );

		add_action( 'admin_head-nav-menus.php', array( $this, 'register_nav_meta_box' ), 9 );

		add_filter( 'get_user_option_metaboxhidden_nav-menus', array( $this, 'force_metabox_visibile' ), 10 );

		add_action( 'admin_footer', array( $this, 'print_menu_settings_vue_template' ), 10 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ), 99 );

		add_action( 'wp_ajax_jet_save_settings', array( $this, 'save_menu_settings' ) );

		add_action( 'wp_ajax_jet_get_nav_item_settings', array( $this, 'get_nav_item_settings' ) );

		add_action( 'wp_ajax_jet_save_nav_item_settings', array( $this, 'save_nav_item_settings' ) );
	}

	/**
	 * Register jetDashboard notice
	 */
	public function register_jet_dashboard_notice() {

		if ( jet_menu_tools()->is_nextgen_mode() ) {
			return;
		}

		\Jet_Dashboard\Dashboard::get_instance()->notice_manager->register_notice( [
			'id'          => 'jet-menu-nextgen-mode-available',
			'page'        => [ 'welcome-page', 'settings-page', 'license-page' ],
			'preset'      => 'alert',
			'type'        => 'info',
			'typeBgColor' => '#48D92B',
			'icon'        => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" rx="6" fill="#48D92B"/><path fill-rule="evenodd" clip-rule="evenodd" d="M41.5139 13.0012C42.0764 12.9665 42.3973 13.698 42.0236 14.1629L38.6968 18.3017C38.3072 18.7863 37.5812 18.4765 37.5888 17.8288L37.6086 16.1496C37.6112 15.9296 37.5197 15.721 37.3617 15.5867L36.1556 14.5617C35.6904 14.1664 35.9207 13.3462 36.5071 13.31L41.5139 13.0012ZM15.0432 23.8698C15.0432 27.8345 11.8023 31.0473 7.80863 31.0473C6.80792 31.0473 6 30.2406 6 29.2517C6 28.2628 6.80792 27.4608 7.80863 27.4608C9.80547 27.4608 11.4259 25.8521 11.4259 23.8698V18.4878C11.4259 17.4944 12.2338 16.6923 13.2345 16.6923C14.2352 16.6923 15.0432 17.4944 15.0432 18.4878V23.8698ZM34.5433 23.8698C34.5433 25.8521 36.1638 27.4608 38.1606 27.4608C39.1613 27.4608 39.9692 28.2583 39.9692 29.2517C39.9692 30.2452 39.1613 31.0473 38.1606 31.0473C34.1669 31.0473 30.9261 27.8345 30.9261 23.8698V18.4878C30.9261 17.4944 31.734 16.6923 32.7347 16.6923C33.7354 16.6923 34.5433 17.4989 34.5433 18.4878V20.0965H35.9801C36.9809 20.0965 37.7934 20.9031 37.7934 21.8965C37.7934 22.89 36.9809 23.6966 35.9801 23.6966H34.5433V23.8698ZM29.8887 21.3543C29.8933 21.3497 29.8979 21.3497 29.8979 21.3497C29.3286 19.8641 28.2637 18.5608 26.7764 17.704C23.3244 15.7171 18.9175 16.8929 16.9299 20.3289C14.9376 23.7604 16.122 28.1489 19.5694 30.1313C22.1079 31.5896 25.1651 31.3344 27.3961 29.7303L27.3823 29.712C27.8964 29.3976 28.2361 28.8325 28.2361 28.19C28.2361 27.2011 27.4282 26.399 26.4321 26.399C25.9501 26.399 25.5094 26.5859 25.1881 26.8957C24.1047 27.6203 22.6633 27.7343 21.4469 27.0689L28.7319 23.7103C29.1542 23.5827 29.5306 23.3002 29.7647 22.89C30.0493 22.4024 30.0769 21.8419 29.8887 21.3543ZM24.9723 20.8074C25.1881 20.9305 25.3809 21.0717 25.5599 21.2267L19.5648 23.9837C19.551 23.3503 19.7071 22.7077 20.0468 22.1199C21.0429 20.4064 23.2463 19.8185 24.9723 20.8074Z" fill="white"/></svg>',
			'title'       => __( 'JetMenu Update', 'jet-menu' ),
			'message'     => __( 'You are using a legacy mode. Switch to the Revamp JetMenu plugin. Please mind, you can always rollback. ', 'jet-menu' ),
			'buttons'     => [
				[
					'type'  => 'accent',
					'label' => __( 'Go to Revamp JetMenu', 'jet-menu' ),
					'url'   => \Jet_Dashboard\Dashboard::get_instance()->get_dashboard_page_url( 'settings-page', 'jet-menu-general-settings' ),
				]
			],
			'customClass' => 'jet-menu-alert',
		] );
	}

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function register_settings_category() {
		\Jet_Dashboard\Dashboard::get_instance()->module_manager->register_module_category( array(
			'name'     => esc_html__( 'JetMenu', 'jet-menu' ),
			'slug'     => 'jet-menu-settings',
			'priority' => 1
		) );
	}

	/**
	 * [init_plugin_subpage_modules description]
	 * @return [type] [description]
	 */
	public function init_plugin_subpage_modules() {

		require jet_menu()->plugin_path( 'includes/settings/subpage-modules/general.php' );
		require jet_menu()->plugin_path( 'includes/settings/subpage-modules/mobile-menu.php' );

		if ( ! jet_menu_tools()->is_nextgen_mode() ) {
			require jet_menu()->plugin_path( 'includes/settings/subpage-modules/desktop-menu.php' );
		} else {
			require jet_menu()->plugin_path( 'includes/settings/subpage-modules/main-menu.php' );
		}

		foreach ( $this->subpage_modules as $subpage => $subpage_data ) {
			\Jet_Dashboard\Dashboard::get_instance()->module_manager->register_subpage_module( $subpage, $subpage_data );
		}
	}

	/**
	 * Register nav menus page metabox with mega menu settings.
	 *
	 * @return void
	 */
	public function register_nav_meta_box() {

		global $pagenow;

		if ( 'nav-menus.php' !== $pagenow ) {
			return;
		}

		add_meta_box(
			'jet-menu-settings',
			esc_html__( 'JetMenu Locations Settings', 'jet-menu' ),
			array( $this, 'render_metabox' ),
			'nav-menus',
			'side',
			'high'
		);

	}

	/**
	 * Render nav menus metabox
	 *
	 * @return void
	 */
	public function render_metabox() {

		$menu_id               = $this->get_selected_menu_id();
		$tagged_menu_locations = $this->get_tagged_theme_locations_for_menu_id( $menu_id );
		$theme_locations       = get_registered_nav_menus();
		$saved_settings        = $this->get_settings( $menu_id );

		if ( ! count( $theme_locations ) ) {
			$this->no_locations_message();
		} else if ( ! count ( $tagged_menu_locations ) ) {
			$this->empty_location_message();
		} else {
			include jet_menu()->get_template( 'admin/settings-nav.php' );
		}

	}

	/**
	 * Notice if no menu locations registered in theme
	 *
	 * @return void
	 */
	public function no_locations_message() {
		printf( '<p>%s</p>', esc_html__( 'This theme does not register any menu locations.', 'jet-menu' ) );
		printf( '<p>%s</p>', esc_html__( 'You will need to create a new menu location to use the JetMenu on your site.', 'jet-menu' ) );
	}

	/**
	 * Notice if no menu locations registered in theme
	 *
	 * @return void
	 */
	public function empty_location_message() {
		printf( '<p>%s</p>', esc_html__( 'Please assign this menu to a theme location to enable the JetMenu settings.', 'jet-menu' ) );
		printf( '<p>%s</p>', esc_html__( 'To assign this menu to a theme location, scroll to the bottom of this page and tag the menu to a \'Display location\'.', 'jet-menu' ) );
	}

	/**
	 * Return the locations that a specific menu ID has been tagged to.
	 *
	 * @author Tom Hemsley (https://wordpress.org/plugins/megamenu/)
	 * @param  $menu_id    int
	 * @return array
	 */
	public function get_tagged_theme_locations_for_menu_id( $menu_id ) {

		$locations          = array();
		$nav_menu_locations = get_nav_menu_locations();

		foreach ( get_registered_nav_menus() as $id => $name ) {

			if ( isset( $nav_menu_locations[ $id ] ) && $nav_menu_locations[ $id ] == $menu_id )
				$locations[ $id ] = $name;
		}

		return $locations;
	}

	/**
	 * Force nav menu metabox with JetMenu settings to be allways visible.
	 *
	 * @param  array $result
	 * @return array
	 */
	public function force_metabox_visibile( $result ) {

		if ( ! is_array( $result ) ) {
			return $result;
		}

		if ( in_array( 'jet-menu-settings', $result ) ) {
			$result = array_diff( $result, array( 'jet-menu-settings' ) );
		}
		return $result;
	}

	/**
	 * Print tabs templates
	 *
	 * @return void
	 */
	public function print_menu_settings_vue_template() {

		$screen = get_current_screen();

		if ( 'nav-menus' !== $screen->base ) {
			return;
		}

		if ( ! jet_menu_tools()->is_nextgen_mode() ) {
			include jet_menu()->get_template( 'admin/legacy/menu-settings-nav.php' );
		} else {
			include jet_menu()->get_template( 'admin/menu-settings-nav.php' );
		}
	}

	/**
	 * [admin_assets description]
	 * @return [type] [description]
	 */
	public function admin_assets() {

		$screen = get_current_screen();

		if ( 'nav-menus' !== $screen->base ) {
			return;
		}

		$module_data = jet_menu()->module_loader->get_included_module_data( 'cherry-x-vue-ui.php' );
		$ui          = new \CX_Vue_UI( $module_data );

		$ui->enqueue_assets();

		wp_enqueue_style( 'jet-menu-admin' );

		wp_enqueue_script(
			'jet-menu-nav-settings-script',
			jet_menu()->plugin_url( 'assets/admin/js/nav-settings.js' ),
			array( 'cx-vue-ui' ),
			jet_menu()->get_version(),
			true
		);

		wp_localize_script(
			'jet-menu-nav-settings-script',
			'JetMenuNavSettingsConfig',
			apply_filters( 'jet-menu/admin/nav-settings-config', array(
				'labels'        => array(
					'itemTriggerLabel'    => '<span class="dashicons dashicons-admin-generic"></span>' . __( 'Settings', 'jet-menu' ),
					'itemMegaEnableLabel' => '<span class="dashicons dashicons-saved"></span>' . __( 'Mega Activated', 'jet-menu' ),
				),
				'currentMenuId' => $this->get_selected_menu_id(),
				'editURL'       => add_query_arg(
					array(
						'jet-open-editor' => 1,
						'item'            => '%id%',
						'menu'            => '%menuid%',
					),
					esc_url( admin_url( '/' ) )
				),
				'optionMenuList'   => $this->get_menu_select_options(),
				'optionPresetList' => jet_menu()->settings_manager->options_manager->get_presets_select_options(),
				'controlData'      => $this->default_nav_item_controls_data(),
				'locationSettings' => $this->get_nav_location_data(),
				'iconsFetchJson'   => jet_menu()->plugin_url( 'assets/public/lib/font-awesome/js/solid.js' ),
				'itemsSettings'    => $this->get_menu_items_settings(),
			) )
		);
	}

	/**
	 * Get the current menu ID.
	 *
	 * @author Tom Hemsley (https://wordpress.org/plugins/megamenu/)
	 * @return int
	 */
	public function get_selected_menu_id() {

		if ( null !== $this->current_menu_id ) {
			return $this->current_menu_id;
		}

		$nav_menus            = wp_get_nav_menus( array('orderby' => 'name') );
		$menu_count           = count( $nav_menus );
		$nav_menu_selected_id = isset( $_REQUEST['menu'] ) ? (int) $_REQUEST['menu'] : 0;
		$add_new_screen       = ( isset( $_GET['menu'] ) && 0 == $_GET['menu'] ) ? true : false;

		$this->current_menu_id = $nav_menu_selected_id;

		// If we have one theme location, and zero menus, we take them right into editing their first menu
		$page_count = wp_count_posts( 'page' );
		$one_theme_location_no_menus = ( 1 == count( get_registered_nav_menus() ) && ! $add_new_screen && empty( $nav_menus ) && ! empty( $page_count->publish ) ) ? true : false;

		// Get recently edited nav menu
		$recently_edited = absint( get_user_option( 'nav_menu_recently_edited' ) );
		if ( empty( $recently_edited ) && is_nav_menu( $this->current_menu_id ) ) {
			$recently_edited = $this->current_menu_id;
		}

		// Use $recently_edited if none are selected
		if ( empty( $this->current_menu_id ) && ! isset( $_GET['menu'] ) && is_nav_menu( $recently_edited ) ) {
			$this->current_menu_id = $recently_edited;
		}

		// On deletion of menu, if another menu exists, show it
		if ( ! $add_new_screen && 0 < $menu_count && isset( $_GET['action'] ) && 'delete' == $_GET['action'] ) {
			$this->current_menu_id = $nav_menus[0]->term_id;
		}

		// Set $this->current_menu_id to 0 if no menus
		if ( $one_theme_location_no_menus ) {
			$this->current_menu_id = 0;
		} elseif ( empty( $this->current_menu_id ) && ! empty( $nav_menus ) && ! $add_new_screen ) {
			// if we have no selection yet, and we have menus, set to the first one in the list
			$this->current_menu_id = $nav_menus[0]->term_id;
		}

		return $this->current_menu_id;

	}

	/**
	 * @return mixed
	 */
	public function get_menu_items_settings() {
		$menu_items = jet_menu()->render_manager->location_manager->get_menu_items_object_data( $this->get_selected_menu_id() );

		$settings = [];

		if ( ! $menu_items ) {
			return $settings;
		}

		foreach ( $menu_items as $key => $item_obj ) {
			$item_id = $item_obj->ID;

			$settings[ $item_id ] = jet_menu()->settings_manager->get_item_settings( $item_id );
		}

		return $settings;
	}

	/**
	 * [get_menu_select_options description]
	 * @return [type] [description]
	 */
	public function get_menu_select_options() {

		$menu_select_options = array();

		$nav_menus = wp_get_nav_menus();

		if ( ! $nav_menus || empty( $nav_menus ) ) {
			return $menu_select_options;
		}

		$menu_select_options[] = array(
			'label' => esc_html( 'None', 'jet-menu' ),
			'value' => '',
		);

		foreach ( $nav_menus as $key => $menu_data ) {
			$menu_select_options[] = array(
				'label' => $menu_data->name,
				'value' => $menu_data->term_id,
			);
		}

		return $menu_select_options;
	}

	/**
	 * [get_controls_localize_data description]
	 * @return [type] [description]
	 */
	public function default_nav_item_controls_data() {
		return array(
			'enabled' => array(
				'value' => false,
			),
			'custom_mega_menu_position' => array(
				'value'   => 'default',
				'options' => array(
					array(
						'label' => esc_html__( 'Default', 'jet-menu' ),
						'value' => 'default',
					),
					array(
						'label' => esc_html__( 'Relative item', 'jet-menu' ),
						'value' => 'relative-item',
					)
				),
			),
			'custom_mega_menu_width' => array(
				'value' => '',
			),
			'menu_icon_type' => array(
				'value'   => 'icon',
				'options' => array(
					array(
						'label' => esc_html__( 'Icon', 'jet-menu' ),
						'value' => 'icon',
					),
					array(
						'label' => esc_html__( 'Svg', 'jet-menu' ),
						'value' => 'svg',
					)
				),
			),
			'menu_icon' => array(
				'value' => '',
			),
			'menu_svg' => array(
				'value' => '',
			),
			'icon_color' => array(
				'value' => '',
			),
			'icon_size' => array(
				'value' => '',
			),
			'menu_badge' => array(
				'value' => '',
			),
			'badge_color' => array(
				'value' => '',
			),
			'badge_bg_color' => array(
				'value' => '',
			),
			'hide_item_text' => array(
				'value' => '',
			),
			'item_padding' => array(
				'value' => array(
					'top'       => '',
					'right'     => '',
					'bottom'    => '',
					'left'      => '',
					'is_linked' => true,
					'units'     => 'px',
				),
			),
		);
	}

	/**
	 * [get_nav_settings_localize_data description]
	 * @return [type] [description]
	 */
	public function get_nav_location_data() {
		$menu_id         = $this->get_selected_menu_id();
		$theme_locations = get_registered_nav_menus();
		$saved_settings  = $this->get_settings( $menu_id );

		$location_list = array();

		foreach ( $theme_locations as $location => $name ) {

			if ( isset( $saved_settings[ $location ] ) ) {

				$location_list[ $location ] = array(
					'label'   => $name,
					'enabled' => isset( $saved_settings[ $location ]['enabled'] ) ? $saved_settings[ $location ]['enabled'] : false,
					'preset'  => isset( $saved_settings[ $location ]['preset'] ) ? $saved_settings[ $location ]['preset'] : '',
					'mobile'  => isset( $saved_settings[ $location ]['mobile'] ) ? $saved_settings[ $location ]['mobile'] : '',
				);
			} else {
				$location_list[ $location ] = array(
					'label'   => $name,
					'enabled' => false,
					'preset'  => '',
					'mobile'  => '',
				);
			}

		}

		return $location_list;
	}

	/**
	 * Get settings from DB
	 *
	 * @param  [type] $menu_id [description]
	 * @return [type]          [description]
	 */
	public function get_settings( $menu_id ) {
		return get_term_meta( $menu_id, $this->menu_item_meta_key, true );
	}

	/**
	 * Update menu item settings
	 *
	 * @param integer $id       [description]
	 * @param array   $settings [description]
	 */
	public function update_settings( $menu_id = 0, $settings = array() ) {
		update_term_meta( $menu_id, $this->menu_item_meta_key, $settings );
	}

	/**
	 * Returns menu item settings
	 *
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function get_item_settings( $id ) {
		$settings = get_post_meta( $id, $this->menu_item_meta_key, true );

		return ! empty( $settings ) ? $settings : array();
	}

	/**
	 * Update menu item settings
	 *
	 * @param integer $id       [description]
	 * @param array   $settings [description]
	 */
	public function set_item_settings( $id = 0, $settings = array() ) {
		update_post_meta( $id, $this->menu_item_meta_key, $settings );
	}

	/**
	 * Sanitize field
	 *
	 * @param  [type] $key   [description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function sanitize_field( $key, $value ) {

		$specific_callbacks = apply_filters( 'jet-menu/admin/nav-item-settings/sanitize-callbacks', array(
			'icon_size'    => 'absint',
			'menu_badge'   => 'wp_kses_post',
		) );

		$callback = isset( $specific_callbacks[ $key ] ) ? $specific_callbacks[ $key ] : false;

		if ( ! $callback ) {
			return $value;
		}

		return call_user_func( $callback, $value );
	}

	/**
	 * [get_nav_item_settings description]
	 * @return [type] [description]
	 */
	public function get_nav_item_settings() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
			) );
		}

		$data = isset( $_POST['data'] ) ? $_POST['data'] : false;

		if ( ! $data ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Incorrect input data', 'jet-menu' ),
			) );
		}

		$default_settings = array();

		foreach ( $this->default_nav_item_controls_data() as $key => $value ) {
			$default_settings[ $key ] = $value['value'];
		}

		$current_settings = $this->get_item_settings( absint( $data['itemId'] ) );

		$current_settings = wp_parse_args( $current_settings, $default_settings );

		wp_send_json_success( array(
			'message'  => esc_html__( 'Success!', 'jet-menu' ),
			'settings' => $current_settings,
		) );
	}

	/**
	 * [save_nav_item_settings description]
	 * @return [type] [description]
	 */
	public function save_nav_item_settings() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
			) );
		}

		$data = isset( $_POST['data'] ) ? $_POST['data'] : false;

		if ( ! $data ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Incorrect input data', 'jet-menu' ),
			) );
		}

		$item_id = $data['itemId'];
		$settings = $data['itemSettings'];

		$sanitized_settings = array();

		foreach ( $settings as $key => $value ) {
			$sanitized_settings[ $key ] = $this->sanitize_field( $key, $value );
		}

		$current_settings = $this->get_item_settings( $item_id );

		$new_settings = array_merge( $current_settings, $sanitized_settings );

		$this->set_item_settings( $item_id, $new_settings );

		do_action( 'jet-menu/item-settings/save' );

		wp_send_json_success( array(
			'message' => esc_html__( 'Item settings have been saved', 'jet-menu' ),
		) );
	}

	/**
	 * Save menu settings
	 *
	 * @return void
	 */
	public function save_menu_settings() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'You are not allowed to do this', 'jet-menu' ),
			) );
		}

		$data = isset( $_POST['data'] ) ? $_POST['data'] : false;

		if ( ! $data ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Incorrect input data', 'jet-menu' ),
			) );
		}

		$menu_id = isset( $data['menuId'] ) ? absint( $data['menuId'] ) : false;
		$settings = isset( $data['settings'] ) ? $data['settings'] : false;

		if ( ! $menu_id || ! $settings ) {
			wp_send_json_error( array(
				'message' => esc_html__( 'Required data is missed', 'jet-menu' ),
			) );
		}

		$current_settings = $this->get_settings( $menu_id );

		if ( ! $current_settings ) {
			$current_settings = array();
		}

		$new_settings = array_merge( $current_settings, $settings );

		$this->update_settings( $menu_id, $new_settings );

		wp_send_json_success( array(
			'message' => esc_html__( 'Menu settings have been saved', 'jet-menu' ),
		) );
	}

}

