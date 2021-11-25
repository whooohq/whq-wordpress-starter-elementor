<?php
namespace Jet_Menu\Render;

class Location {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

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
	 * Add background from options from menu canvas
	 */
	public function set_menu_canvas_bg() {
		jet_menu_dynmic_css()->add_single_bg_styles( 'jet-menu-sub-panel-mega', 'body' );
	}

	/**
	 * Fix double decription bug.
	 *
	 * @param  string  $item_output The menu item output.
	 * @param  WP_Post $item        Menu item object.
	 * @param  int     $depth       Depth of the menu.
	 * @param  array   $args        wp_nav_menu() arguments.
	 * @return string
	 */
	public function fix_double_desc( $item_output, $item, $depth, $args ) {
		$item->description = '';

		return $item_output;
	}

	/**
	 * [modify_pre_wp_nav_menu description]
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function modify_pre_wp_nav_menu( $output, $args ) {

		if ( ! isset( $args->theme_location ) ) {
			return $output;
		}

		$location = $args->theme_location;

		$menu_id = $this->get_menu_id( $location );

		if ( false === $menu_id ) {
			return $output;
		}

		$settings = jet_menu()->settings_manager->get_settings( $menu_id );

		if ( ! isset( $settings[ $location ] ) ) {
			return $output;
		}

		if ( ! isset( $settings[ $location ]['enabled'] ) || 'true' !== $settings[ $location ]['enabled'] ) {
			return $output;
		}

		//$this->add_menu_advanced_styles( $menu_id );

		$preset = isset( $settings[ $location ]['preset'] ) ? absint( $settings[ $location ]['preset'] ) : 0;

		$this->add_dynamic_styles( $preset );

		$is_mobile_render = $this->is_mobile_render();

		if ( $is_mobile_render ) {
			$use_breadcrumbs = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-use-breadcrumb', true );
			$toggle_loader = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-loader', true );
			$close_after_navigate = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-close-after-navigate', false );
			$is_item_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-icon-enabled', true );
			$is_item_badge = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-badge-enabled', true );
			$is_item_desc = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-desc-enable', false );
			$toggle_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-icon', '' );
			$toggle_opened_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-opened-icon', '' );
			$close_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-close-icon', '' );
			$back_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-back-icon', '' );
			$dropdown_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-dropdown-icon', '' );
			$dropdown_opened_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-dropdown-opened-icon', '' );
			$breadcrumb_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-breadcrumb-icon', '' );

			$render_widget_instance = new \Jet_Menu\Render\Mobile_Menu_Render( array(
				'menu-id'                   => $menu_id,
				'layout'                    => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-layout', 'slide-out' ),
				'toggle-position'           => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-toggle-position', 'default' ),
				'container-position'        => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-container-position', 'right' ),
				'item-header-template'      => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-header-template', 0 ),
				'item-before-template'      => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-before-template', 0 ),
				'item-after-template'       => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-after-template', 0 ),
				'use-breadcrumbs'           => filter_var( $use_breadcrumbs, FILTER_VALIDATE_BOOLEAN ),
				'breadcrumbs-path'          => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-breadcrumb-path', 'full' ),
				'toggle-text'               => esc_attr( jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-back-text', '' ) ),
				'toggle-loader'             => filter_var( $toggle_loader, FILTER_VALIDATE_BOOLEAN ),
				'back-text'                 => esc_attr( jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-back-text', '' ) ),
				'is-item-icon'              => filter_var( $is_item_icon, FILTER_VALIDATE_BOOLEAN ),
				'is-item-badge'             => filter_var( $is_item_badge, FILTER_VALIDATE_BOOLEAN ),
				'is-item-desc'              => filter_var( $is_item_desc, FILTER_VALIDATE_BOOLEAN ),
				'loader-color'              => jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-loader-color', false ),
				'sub-menu-trigger'          => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-sub-trigger', 'item' ),
				'sub-open-layout'           => jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mobile-sub-open-layout', 'dropdown' ),
				'close-after-navigate'      => filter_var( $close_after_navigate, FILTER_VALIDATE_BOOLEAN ),
				'toggle-closed-icon-html'   => jet_menu_tools()->get_svg_icon_html( $toggle_icon ),
				'toggle-opened-icon-html'   => jet_menu_tools()->get_svg_icon_html( $toggle_opened_icon ),
				'close-icon-html'           => jet_menu_tools()->get_svg_icon_html( $close_icon ),
				'back-icon-html'            => jet_menu_tools()->get_svg_icon_html( $back_icon ),
				'dropdown-icon-html'        => jet_menu_tools()->get_svg_icon_html( $dropdown_icon ),
				'dropdown-opened-icon-html' => jet_menu_tools()->get_svg_icon_html( $dropdown_opened_icon ),
				'breadcrumb-icon-html'      => jet_menu_tools()->get_svg_icon_html( $breadcrumb_icon ),
			) );
		} else {
			$roll_up = jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-roll-up', true );
			$roll_up_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-roll-up-icon', '' );
			$dropdown_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-icon', '' );
			$toggle_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-toggle-default-icon', '' );
			$toggle_opened_icon = jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-toggle-opened-icon', '' );

			$render_widget_instance = new \Jet_Menu\Render\Mega_Menu_Render( array(
				'menu'                => $menu_id,
				'location'            => 'wp-nav',
				'layout'              => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-layout', 'horizontal' ),
				'sub-position'        => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-menu-position', 'horizontal' ),
				'dropdown-layout'     => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-layout', 'default' ),
				'dropdown-position'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-position', 'right' ),
				'sub-animation'       => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-animation', 'fade' ),
				'sub-event'           => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-menu-event', 'hover' ),
				'sub-trigger'         => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-sub-menu-trigger', 'item' ),
				'breakpoint'          => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-dropdown-breakpoint', 768 ),
				'roll-up'             => filter_var( $roll_up, FILTER_VALIDATE_BOOLEAN ),
				'roll-up-type'        => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-roll-up-type', 'text' ),
				'roll-up-item-text'   => jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-roll-up-text', '...' ),
				'roll-up-item-icon'   => jet_menu_tools()->get_svg_icon_html( $roll_up_icon ),
				'dropdown-icon'       => jet_menu_tools()->get_svg_icon_html( $dropdown_icon ),
				'toggle-default-icon' => jet_menu_tools()->get_svg_icon_html( $toggle_icon ),
				'toggle-opened-icon'  => jet_menu_tools()->get_svg_icon_html( $toggle_opened_icon ),
			) );
		}

		ob_start();
		$render_widget_instance->render();
		return ob_get_clean();
	}

	/**
	 * @return bool
	 */
	public function is_mobile_render() {

		$use_mobile_device_render = jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-use-mobile-render', false );
		$use_mobile_device_render = filter_var( $use_mobile_device_render, FILTER_VALIDATE_BOOLEAN );

		$current_device = jet_menu_tools()->get_current_device();

		if ( 'desktop' === $current_device || ! $use_mobile_device_render ) {
			return false;
		}

		$device_for_mobile_render = jet_menu()->settings_manager->options_manager->get_option( 'jet-mega-menu-mobile-device', 'mobile' );

		if ( 'tablet-mobile' === $device_for_mobile_render && ( 'tablet' === $current_device || 'mobile' === $current_device ) ) {
			return true;
		}

		if ( 'mobile' === $device_for_mobile_render && 'mobile' === $current_device ) {
			return true;
		}

		return false;
	}

	/**
	 * Add menu dynamic styles
	 */
	public function add_dynamic_styles( $preset = 0 ) {

		if ( jet_menu_css_file()->is_enqueued( $preset ) ) {
			return;
		} else {
			jet_menu_css_file()->add_preset_to_save( $preset );
		}

		$preset_class = ( 0 !== $preset ) ? '.jet-menu-preset-' . $preset : '';

		$this->add_fonts_styles( $preset_class );
		$this->add_borders( $preset_class );
		$this->add_shadows( $preset_class );

		$css_scheme = apply_filters( 'jet-menu/mega-menu/css-scheme', array(
			'jet-mega-menu-container-width' => array(
				'selector'  => '',
				'rule'      => '--jmm-container-width',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-items-ver-padding' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-items-ver-padding',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-items-hor-padding' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-items-hor-padding',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-items-gap' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-items-gap',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-sub-bg-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-sub-menu-bg-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-sub-items-ver-padding' => array(
				'selector'  => '',
				'rule'      => '--jmm-sub-items-ver-padding',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-sub-items-hor-padding' => array(
				'selector'  => '',
				'rule'      => '--jmm-sub-items-hor-padding',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-sub-items-gap' => array(
				'selector'  => '',
				'rule'      => '--jmm-sub-items-gap',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-icon-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-item-icon-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-title-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-item-title-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-badge-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-item-badge-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-hover-icon-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-hover-item-icon-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-hover-title-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-hover-item-title-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-hover-badge-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-hover-item-badge-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-active-icon-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-active-item-icon-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-active-title-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-active-item-title-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-active-badge-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-top-active-item-badge-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-top-items-ver-padding' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-top-items-ver-padding',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-dropdown-top-items-hor-padding' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-top-items-hor-padding',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-dropdown-top-items-gap' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-top-items-gap',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-dropdown-sub-items-ver-padding' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-sub-items-ver-padding',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-dropdown-sub-items-hor-padding' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-sub-items-hor-padding',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-dropdown-sub-items-gap' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-sub-items-gap',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-dropdown-icon-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-item-icon-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-title-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-item-title-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-badge-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-item-badge-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-item-bg-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-item-bg-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-hover-icon-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-hover-item-icon-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-hover-title-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-hover-item-title-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-hover-badge-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-hover-item-badge-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-hover-item-bg-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-hover-item-bg-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-active-icon-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-active-item-icon-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-active-title-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-active-item-title-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-active-badge-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-active-item-badge-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-active-bg-item-color' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-active-item-badge-color',
				'value'     => '%s',
				'important' => false,
			),
			'jet-mega-menu-dropdown-toggle-size' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-toggle-size',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-mega-menu-dropdown-toggle-distance' => array(
				'selector'  => '',
				'rule'      => '--jmm-dropdown-toggle-distance',
				'value'     => '%spx',
				'important' => false,
			),

			// Mobile
			'jet-menu-mobile-toggle-color' => array(
				'selector'  => '.jet-mobile-menu__toggle',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-toggle-bg' => array(
				'selector'  => '.jet-mobile-menu__toggle',
				'rule'      => 'background-color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-toggle-text-color' => array(
				'selector'  => '.jet-mobile-menu__toggle .jet-mobile-menu__toggle-text',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-toggle-size' => array(
				'selector'  => '.jet-mobile-menu__toggle',
				'rule'      => 'font-size',
				'value'     => '%spx',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-toggle-border-radius' => array(
				'selector'  => '.jet-mobile-menu__toggle',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-toggle-padding' => array(
				'selector'  => '.jet-mobile-menu__toggle',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-breadcrumbs-text-color' => array(
				'selector'  => '.jet-mobile-menu__breadcrumbs .breadcrumb-label',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-breadcrumbs-icon-color' => array(
				'selector'  => '.jet-mobile-menu__breadcrumbs .breadcrumb-divider',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-breadcrumbs-icon-size' => array(
				'selector'  => '.jet-mobile-menu__breadcrumbs .breadcrumb-divider',
				'rule'      => 'font-size',
				'value'     => '%spx',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-container-width' => array(
				'selector'  => '.jet-mobile-menu__container',
				'rule'      => 'width',
				'value'     => '%spx',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-container-bg' => array(
				'selector'  => '.jet-mobile-menu__container-inner',
				'rule'      => 'background-color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-container-border-radius' => array(
				'selector'  => array(
					'.jet-mobile-menu__container',
					'.jet-mobile-menu__container-inner',
				),
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-container-padding' => array(
				'selector'  => '.jet-mobile-menu__container-inner',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-cover-bg' => array(
				'selector'  => '.jet-mobile-menu-cover',
				'rule'      => 'background-color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-container-close-color' => array(
				'selector'  => '.jet-mobile-menu__back i',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-container-close-size' => array(
				'selector'  => '.jet-mobile-menu__back i',
				'rule'      => 'font-size',
				'value'     => '%spx',
				'important' => false,
				'mobile'    => true,
			),
			'jet-menu-mobile-container-back-text-color' => array(
				'selector'  => '.jet-mobile-menu__back span',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-dropdown-color' => array(
				'selector'  => '.jet-dropdown-arrow',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-dropdown-size' => array(
				'selector'  => '.jet-dropdown-arrow',
				'rule'      => 'font-size',
				'value'     => '%spx',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-label-color' => array(
				'selector'  => '.jet-mobile-menu__item .jet-menu-label',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-label-color-active' => array(
				'selector'  => '.jet-mobile-menu__item.jet-mobile-menu__item--active .jet-menu-label',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-desc-color' => array(
				'selector'  => '.jet-mobile-menu__item.jet-mobile-menu__item--active .jet-menu-desc',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-desc-color-active' => array(
				'selector'  => '.jet-mobile-menu__item.jet-mobile-menu__item--active .jet-menu-desc',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-icon-color' => array(
				'selector'  => '.jet-mobile-menu__item .jet-menu-icon',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-icon-size' => array(
				'selector'  => '.jet-mobile-menu__item .jet-menu-icon',
				'rule'      => 'font-size',
				'value'     => '%spx',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-icon-margin' => array(
				'selector'  => '.jet-mobile-menu__item .jet-menu-icon',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-badge-color' => array(
				'selector'  => '.jet-mobile-menu__item .jet-menu-badge__inner',
				'rule'      => 'color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-badge-bg-color' => array(
				'selector'  => '.jet-mobile-menu__item .jet-menu-badge__inner',
				'rule'      => 'background-color',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-badge-padding' => array(
				'selector'  => '.jet-mobile-menu__item .jet-menu-badge__inner',
				'rule'      => 'padding-%s',
				'value'     => '%s',
				'important' => false,
				'mobile'    => true,
			),
			'jet-mobile-items-badge-border-radius' => array(
				'selector'  => '.jet-mobile-menu__item .jet-menu-badge__inner',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
				'mobile'    => true,
			),

		) );

		foreach ( $css_scheme as $setting => $data ) {

			$value = jet_menu()->settings_manager->options_manager->get_option( $setting );

			if ( empty( $value ) || 'false' === $value ) {
				continue;
			}

			if ( isset( $data['mobile'] ) && filter_var( $data['mobile'], FILTER_VALIDATE_BOOLEAN) ) {
				$wrapper = "{ $preset_class }.jet-mobile-menu--location-wp-nav";
			} else {
				$wrapper = "{ $preset_class }.jet-mega-menu--location-wp-nav";
			}

			$selector = $data['selector'];

			if ( is_array( $value ) && isset( $value['units'] ) ) {

				if ( is_array( $selector ) ) {

					foreach ( $selector as $key => $selector_item ) {
						jet_menu_dynmic_css()->add_dimensions_css(
							array(
								'selector'  => sprintf( '%1$s %2$s', $wrapper, $selector_item ),
								'rule'      => $data['rule'],
								'values'    => $value,
								'important' => $data['important'],
							)
						);
					}

				} else {
					jet_menu_dynmic_css()->add_dimensions_css(
						array(
							'selector'  => sprintf( '%1$s %2$s', $wrapper, $selector ),
							'rule'      => $data['rule'],
							'values'    => $value,
							'important' => $data['important'],
						)
					);
				}

				continue;
			}

			$important = ( true === $data['important'] ) ? ' !important' : '';

			if ( is_array( $selector ) ) {

				foreach ( $selector as $key => $selector_item ) {
					jet_menu()->dynamic_css_manager->add_style(
						sprintf( '%1$s %2$s', $wrapper, $selector_item ),
						array(
							$data['rule'] => sprintf( $data['value'], esc_attr( $value ) ) . $important,
						)
					);
				}
			} else {
				jet_menu()->dynamic_css_manager->add_style(
					sprintf( '%1$s %2$s', $wrapper, $selector ),
					array(
						$data['rule'] => sprintf( $data['value'], esc_attr( $value ) ) . $important,
					)
				);
			}

		}

		// Mobile Styles
		$divider_enabled = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-divider-enabled', false );

		if ( filter_var( $divider_enabled, FILTER_VALIDATE_BOOLEAN ) ) {

			$divider_color = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-divider-color', '#3a3a3a' );
			$divider_width = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-divider-width', '1' );

			jet_menu()->dynamic_css_manager->add_style(
				"{ $preset_class }.jet-mobile-menu--location-wp-nav .jet-mobile-menu__item",
				array(
					'border-bottom-style' => 'solid',
					'border-bottom-width' => sprintf( '%spx', $divider_width ),
					'border-bottom-color' => $divider_color,
				)
			);
		}

		$item_icon_enabled = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-icon-enabled', 'true' );

		if ( filter_var( $item_icon_enabled, FILTER_VALIDATE_BOOLEAN ) ) {
			$item_icon_ver_position = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-icon-ver-position', 'center' );

			switch ( $item_icon_ver_position ) {
				case 'top':
					$ver_position = 'flex-start';
					break;
				case 'center':
					$ver_position = 'center';
					break;
				case 'bottom':
					$ver_position = 'flex-end';
					break;
				default:
					$ver_position = 'center';
					break;
			}

			jet_menu()->dynamic_css_manager->add_style( "{ $preset_class }.jet-mobile-menu--location-wp-nav .jet-menu-icon", array(
				'-webkit-align-self' => $ver_position,
				'align-self'         => $ver_position,
			) );
		}

		$item_badge_enabled = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-badge-enabled', 'true' );

		if ( filter_var( $item_badge_enabled, FILTER_VALIDATE_BOOLEAN ) ) {
			$item_badge_ver_position = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-badge-ver-position', 'center' );

			switch ( $item_badge_ver_position ) {
				case 'top':
					$ver_position = 'flex-start';
					break;
				case 'center':
					$ver_position = 'center';
					break;
				case 'bottom':
					$ver_position = 'flex-end';
					break;
				default:
					$ver_position = 'center';
					break;
			}

			jet_menu()->dynamic_css_manager->add_style( "{ $preset_class }.jet-mobile-menu--location-wp-nav .jet-menu-badge", array(
				'-webkit-align-self' => $ver_position,
				'align-self'         => $ver_position,
			) );
		}

	}

	/**
	 * Add font-related styles.
	 */
	public function add_fonts_styles( $preset = '' ) {

		$preset = ( ! empty( $preset ) ) ? $preset : '';

		$fonts_options = apply_filters( 'jet-menu/menu-css/fonts', array(
			'jet-mega-menu-top-typography'          => '.jet-mega-menu--location-wp-nav.jet-mega-menu--layout-horizontal .jet-mega-menu-item__link--top-level, .jet-mega-menu--location-wp-nav.jet-mega-menu--layout-vertical .jet-mega-menu-item__link--top-level',
			'jet-mega-menu-sub-typography'          => '.jet-mega-menu--location-wp-nav.jet-mega-menu--layout-horizontal .jet-mega-menu-item__link--sub-level, .jet-mega-menu--location-wp-nav.jet-mega-menu--layout-vertical .jet-mega-menu-item__link--sub-level',
			'jet-mega-menu-dropdown-top-typography' => '.jet-mega-menu--location-wp-nav.jet-mega-menu--layout-dropdown .jet-mega-menu-item__link--top-level',
			'jet-mega-menu-dropdown-sub-typography' => '.jet-mega-menu--location-wp-nav.jet-mega-menu--layout-dropdown .jet-mega-menu-item__link--sub-level',
			'jet-menu-mobile-toggle-text'           => '.jet-mobile-menu--location-wp-nav .jet-mobile-menu__toggle .jet-mobile-menu__toggle-text',
			'jet-menu-mobile-back-text'             => '.jet-mobile-menu--location-wp-nav .jet-mobile-menu__container .jet-mobile-menu__back span',
			'jet-menu-mobile-breadcrumbs-text'      => '.jet-mobile-menu--location-wp-nav .jet-mobile-menu__container .breadcrumb-label',
			'jet-mobile-items-label'                => '.jet-mobile-menu--location-wp-nav .jet-mobile-menu__item .mobile-link .jet-menu-label',
			'jet-mobile-items-desc'                 => '.jet-mobile-menu--location-wp-nav .jet-mobile-menu__item .mobile-link .jet-menu-desc',
			'jet-mobile-items-badge'                => '.jet-mobile-menu--location-wp-nav .jet-mobile-menu__item .mobile-link .jet-menu-badge__inner',
		) );

		foreach ( $fonts_options as $font => $selector ) {
			jet_menu_dynmic_css()->add_single_font_styles( $font, $preset . $selector );
		}

	}

	/**
	 * Add border styles.
	 */
	public function add_borders( $preset = '' ) {

		$preset = ( ! empty( $preset ) ) ? $preset : '';

		$options = apply_filters( 'jet-menu/menu-css/borders', array(
			'jet-menu-container'         => '.jet-menu',
			'jet-menu-item'              => '.jet-menu .jet-menu-item .top-level-link',
			'jet-menu-first-item'        => '.jet-menu > .jet-regular-item:first-child .top-level-link',
			'jet-menu-last-item'         => array(
				'.jet-menu > .jet-regular-item.jet-has-roll-up:nth-last-child(2) .top-level-link',
				'.jet-menu > .jet-regular-item.jet-no-roll-up:nth-last-child(1) .top-level-link',
				'.jet-menu > .jet-responsive-menu-available-items:last-child .top-level-link',
			),
			'jet-menu-item-hover'        => '.jet-menu .jet-menu-item:hover > .top-level-link',
			'jet-menu-first-item-hover'  => '.jet-menu > .jet-regular-item:first-child:hover > .top-level-link',
			'jet-menu-last-item-hover'   => array(
				'.jet-menu > .jet-regular-item.jet-has-roll-up:nth-last-child(2):hover .top-level-link',
				'.jet-menu > .jet-regular-item.jet-no-roll-up:nth-last-child(1):hover .top-level-link',
				'.jet-menu > .jet-responsive-menu-available-items:last-child:hover .top-level-link',
			),
			'jet-menu-item-active'       => '.jet-menu .jet-menu-item.jet-current-menu-item .top-level-link',
			'jet-menu-first-item-active' => '.jet-menu > .jet-regular-item:first-child.jet-current-menu-item .top-level-link',
			'jet-menu-last-item-active'  => array(
				'.jet-menu > .jet-regular-item.jet-current-menu-item.jet-has-roll-up:nth-last-child(2) .top-level-link',
				'.jet-menu > .jet-regular-item.jet-current-menu-item.jet-no-roll-up:nth-last-child(1) .top-level-link',
				'.jet-menu > .jet-responsive-menu-available-items.jet-current-menu-item:last-child .top-level-link',
			),
			'jet-menu-top-badge'         => '.jet-menu .jet-menu-item .top-level-link .jet-menu-badge__inner',
			'jet-menu-sub-badge'         => '.jet-menu .jet-menu-item .sub-level-link .jet-menu-badge__inner',
			'jet-menu-sub-panel-simple'  => '.jet-menu ul.jet-sub-menu',
			'jet-menu-sub-panel-mega'    => '.jet-menu div.jet-sub-mega-menu',
			'jet-menu-sub'               => '.jet-menu li.jet-sub-menu-item .sub-level-link',
			'jet-menu-sub-hover'         => '.jet-menu li.jet-sub-menu-item:hover > .sub-level-link',
			'jet-menu-sub-active'        => '.jet-menu li.jet-sub-menu-item.jet-current-menu-item .sub-level-link',
			'jet-menu-sub-first'         => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item:first-child > .sub-level-link',
			'jet-menu-sub-first-hover'   => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item:first-child:hover > .sub-level-link',
			'jet-menu-sub-first-active'  => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item.jet-current-menu-item:first-child > .sub-level-link',
			'jet-menu-sub-last'          => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item:last-child > .sub-level-link',
			'jet-menu-sub-last-hover'    => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item:last-child:hover > .sub-level-link',
			'jet-menu-sub-last-active'   => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item.jet-current-menu-item:last-child > .sub-level-link',

			'jet-menu-mobile-container'  => '.jet-mobile-menu-single .jet-mobile-menu__container-inner',
			'jet-menu-mobile-toggle'     => '.jet-mobile-menu-single .jet-mobile-menu__toggle',
		) );

		foreach ( $options as $option => $selector ) {

			if ( is_array( $selector ) ) {

				$final_selector = '';
				$delimiter      = '';

				foreach ( $selector as $part ) {
					$final_selector .= sprintf(
						'%3$s%1$s %2$s',
						$preset,
						$part,
						$delimiter
					);
					$delimiter = ', ';
				}
			} else {
				$final_selector = $preset . $selector;
			}

			jet_menu_dynmic_css()->add_single_border_styles( $option, $final_selector );
		}

	}

	/**
	 * Add shadows styles.
	 */
	public function add_shadows( $preset = '' ) {

		$preset = ( ! empty( $preset ) ) ? $preset : '';

		$options = apply_filters( 'jet-menu/menu-css/shadows', array(
			'jet-menu-container'        => '.jet-menu ',
			'jet-menu-item'             => '.jet-menu .jet-menu-item .top-level-link',
			'jet-menu-item-hover'       => '.jet-menu .jet-menu-item:hover > .top-level-link',
			'jet-menu-item-active'      => '.jet-menu .jet-menu-item.jet-current-menu-item .top-level-link',
			'jet-menu-top-badge'        => '.jet-menu .jet-menu-item .top-level-link .jet-menu-badge__inner',
			'jet-menu-sub-badge'        => '.jet-menu .jet-menu-item .sub-level-link .jet-menu-badge__inner',
			'jet-menu-sub-panel-simple' => '.jet-menu ul.jet-sub-menu',
			'jet-menu-sub-panel-mega'   => '.jet-menu div.jet-sub-mega-menu',
			'jet-menu-sub'              => '.jet-menu li.jet-sub-menu-item .sub-level-link',
			'jet-menu-sub-hover'        => '.jet-menu li.jet-sub-menu-item:hover > .sub-level-link',
			'jet-menu-sub-active'       => '.jet-menu li.jet-sub-menu-item.jet-current-menu-item .sub-level-link',

			'jet-menu-mobile-container' => '.jet-mobile-menu-single .jet-mobile-menu__container',
			'jet-menu-mobile-toggle'    => '.jet-mobile-menu-single .jet-mobile-menu__toggle',
		) );

		foreach ( $options as $option => $selector ) {
			jet_menu_dynmic_css()->add_single_shadow_styles( $option, $preset . $selector );
		}

	}

	/**
	 * [generate_menu_raw_data description]
	 * @param  string  $menu_slug [description]
	 * @param  boolean $is_return [description]
	 * @return [type]             [description]
	 */
	public function generate_menu_raw_data( $menu_id = false ) {

		if ( ! $menu_id ) {
			return false;
		}

		$menu_items = $this->get_menu_items_object_data( $menu_id );

		$items = array();

		foreach ( $menu_items as $key => $item ) {

			$item_id = $item->ID;

			$item_settings = jet_menu()->settings_manager->get_item_settings( $item_id );

			$item_template_id = get_post_meta( $item_id, jet_menu()->post_type_manager->meta_key(), true );

			$elementor_template_id = ( isset( $item_settings['enabled'] ) && filter_var( $item_settings['enabled'], FILTER_VALIDATE_BOOLEAN ) ) ? (int)$item_template_id : false;

			$item_icon = ! empty( $item_settings['menu_svg'] ) ? jet_menu_tools()->get_svg_html( $item_settings['menu_svg'], false ) : false;

			$items[] = array(
				'id'                  => 'item-' . $item_id,
				'name'                => $item->title,
				'attrTitle'           => ! empty( $item->attr_title ) ? $item->attr_title : false,
				'description'         => $item->description,
				'url'                 => $item->url,
				'target'              => ! empty( $item->target ) ? $item->target : false,
				'xfn'                 => ! empty( $item->xfn ) ? $item->xfn : false,
				'itemParent'          => ! empty( $item->menu_item_parent ) ? 'item-' . $item->menu_item_parent : false,
				'itemId'              => $item_id,
				'elementorTemplateId' => $elementor_template_id,
				'elementorContent'    => false,
				'open'                => false,
				'badgeText'           => isset( $item_settings['menu_badge'] ) ? $item_settings['menu_badge'] : false,
				'itemIcon'            => $item_icon,
				'classes'             => $item->classes,
			);
		}

		if ( ! empty( $items ) ) {
			$items = $this->buildItemsTree( $items, false );
		}

		$menu_data = array(
			'items' => $items,
		);

		return $menu_data;
	}

	/**
	 * [buildItemsTree description]
	 * @param  array   &$items   [description]
	 * @param  integer $parentId [description]
	 * @return [type]            [description]
	 */
	public function buildItemsTree( array &$items, $parentId = false ) {

		$branch = [];

		foreach ( $items as &$item ) {

			if ( $item['itemParent'] === $parentId ) {
				$children = $this->buildItemsTree( $items, $item['id'] );

				if ( $children && !$item['elementorTemplateId'] ) {
					$item['children'] = $children;
				}

				$branch[ $item['id'] ] = $item;

				unset( $item );
			}
		}

		return $branch;

	}

	/**
	 * [get_menu_items_object_data description]
	 * @param  boolean $menu_id [description]
	 * @return [type]           [description]
	 */
	public function get_menu_items_object_data( $menu_id = false ) {

		if ( ! $menu_id ) {
			return false;
		}

		$menu = wp_get_nav_menu_object( $menu_id );

		$menu_items = wp_get_nav_menu_items( $menu );

		if ( ! $menu_items ) {
			return false;
		}

		return $menu_items;
	}

	/**
	 * Get menu ID for current location
	 *
	 * @param  [type] $location [description]
	 * @return [type]           [description]
	 */
	public function get_menu_id( $location = null ) {
		$locations = get_nav_menu_locations();

		return isset( $locations[ $location ] ) ? $locations[ $location ] : false;
	}

	/**
	 * [add_menu_advanced_styles description]
	 * @param boolean $menu_id [description]
	 */
	public function add_menu_advanced_styles( $menu_id = false ) {

		if ( ! $menu_id ) {
			return false;
		}

		$menu_items = $this->get_menu_items_object_data( $menu_id );

		if ( ! $menu_items ) {
			return false;
		}

		foreach ( $menu_items as $key => $item ) {
			jet_menu_tools()->add_menu_css( $item->ID, '.jet-mega-menu-item-' . $item->ID );
		}
	}

	/**
	 * [modify_body_class description]
	 * @param  [type] $classes [description]
	 * @return [type]          [description]
	 */
	public function modify_body_class( $classes ) {
		$classes[] = 'jet-mega-menu-location';

		return $classes;
	}

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		add_action( 'jet-menu/blank-page/after-content', array( $this, 'set_menu_canvas_bg' ) );

		add_filter( 'walker_nav_menu_start_el', array( $this, 'fix_double_desc' ), 0, 4 );

		add_filter( 'pre_wp_nav_menu', array( $this, 'modify_pre_wp_nav_menu' ), 10, 2 );

		add_filter( 'body_class', array( $this, 'modify_body_class' ) );

	}
}
