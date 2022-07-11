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
	 * Load files
	 */
	public function load_files() {}

	/**
	 * [modify_body_class description]
	 * @param  [type] $classes [description]
	 * @return [type]          [description]
	 */
	public function modify_body_class( $classes ) {
		$classes[] = ! \Jet_Menu_Tools::is_phone() ? 'jet-desktop-menu-active' : 'jet-mobile-menu-active';

		return $classes;
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
	 * Set mega menu arguments
	 *
	 * @param [type] $args [description]
	 */
	public function set_menu_args( $args ) {

		if ( ! isset( $args['theme_location'] ) ) {
			return $args;
		}

		$location = $args['theme_location'];

		$menu_id = $this->get_menu_id( $location );

		if ( false === $menu_id ) {
			return $args;
		}

		$menu_settings = jet_menu()->settings_manager->get_settings( $menu_id );

		$menu_settings = apply_filters( 'jet-menu/public-manager/menu-settings', $menu_settings );

		$location = apply_filters( 'jet-menu/public-manager/menu-location', $location );

		if ( ! isset( $menu_settings[ $location ] ) ) {
			return $args;
		}

		if ( ! isset( $menu_settings[ $location ]['enabled'] ) || 'true' !== $menu_settings[ $location ]['enabled'] ) {
			return $args;
		}

		$preset = isset( $menu_settings[ $location ]['preset'] ) ? absint( $menu_settings[ $location ]['preset'] ) : 0;

		if ( 0 !== $preset ) {
			$preset_options = get_post_meta( $preset, jet_menu()->settings_manager->options_manager->settings_key, true );
			jet_menu()->settings_manager->options_manager->pre_set_options( $preset_options );
		} else {
			jet_menu()->settings_manager->options_manager->pre_set_options( false );
		}

		$args = array_merge( $args, $this->get_mega_nav_args( $preset ) );

		return $args;

	}

	/**
	 * Returns array ow Mega Mneu attributes for wp_nav_menu() function.
	 *
	 * @return array
	 */
	public function get_mega_nav_args( $preset = 0 ) {
		global $is_iphone;

		// Get animation type for mega menu instance
		$animation_type = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-animation', 'fade' );

		$roll_up = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-roll-up', 'false' );

		$raw_attributes = apply_filters( 'jet-menu/set-menu-args/', array(
			'class' => array(
				'jet-menu',
				'jet-menu--animation-type-' . $animation_type,
			),
		) );

		if ( ! empty( $preset ) ) {
			$raw_attributes['class'][] = 'jet-preset-' . $preset;
		}

		if ( filter_var( $roll_up, FILTER_VALIDATE_BOOLEAN ) ) {
			$raw_attributes['class'][] = 'jet-menu--roll-up';
		}

		if ( filter_var( $is_iphone, FILTER_VALIDATE_BOOLEAN ) ) {
			$raw_attributes['class'][] = 'jet-menu--iphone-mode';
		}

		$attributes = '';

		foreach ( $raw_attributes as $name => $value ) {

			if ( is_array( $value ) ) {
				$value = implode( ' ', $value );
			}

			$attributes .= sprintf( ' %1$s="%2$s"', esc_attr( $name ), esc_attr( $value ) );
		}

		$args = array(
			'menu_class'  => '',
			'items_wrap'  => '<div class="jet-menu-container"><div class="jet-menu-inner"><ul' . $attributes . '>%3$s</ul></div></div>',
			'before'      => '',
			'after'       => '',
			'fallback_cb' => '',
			'walker'      => new \Jet_Menu\Render\Main_Walker(),
			'roll_up'     => filter_var( $roll_up, FILTER_VALIDATE_BOOLEAN ),
		);

		$this->add_dynamic_styles( $preset );

		return $args;
	}

	/**
	 * [modify_pre_wp_nav_menu description]
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	public function modify_pre_wp_nav_menu( $desktop_output, $args ) {

		if ( ! isset( $args->theme_location ) ) {
			return $desktop_output;
		}

		$location = $args->theme_location;

		$menu_id = $this->get_menu_id( $location );

		if ( false === $menu_id ) {
			return $desktop_output;
		}

		$settings = jet_menu()->settings_manager->get_settings( $menu_id );

		if ( ! isset( $settings[ $location ] ) ) {
			return $desktop_output;
		}

		if ( ! isset( $settings[ $location ]['enabled'] ) || 'true' !== $settings[ $location ]['enabled'] ) {
			return $desktop_output;
		}

		$preset = isset( $settings[ $location ]['preset'] ) ? absint( $settings[ $location ]['preset'] ) : 0;

		$show_for_device = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-show-for-device', 'both' );

		switch ( $show_for_device ) {
			case 'both':
				if ( ! \Jet_Menu_Tools::is_phone() ) {
					return $desktop_output;
				}

				break;

			case 'desktop':
				return $desktop_output;
				break;
		}

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

		ob_start();
		$render_widget_instance->render();
		return ob_get_clean();

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

		$preset_class = ( 0 !== $preset ) ? '.jet-preset-' . $preset : '';
		$wrapper      = $preset_class;

		$this->add_fonts_styles( $preset_class );
		$this->add_backgrounds( $preset_class );
		$this->add_borders( $preset_class );
		$this->add_shadows( $preset_class );
		$this->add_positions( $preset_class );

		$css_scheme = apply_filters( 'jet-menu/menu-css/scheme', array(
			'jet-menu-container-alignment' => array(
				'selector'  => '',
				'rule'      => 'justify-content',
				'value'     => '%1$s',
				'important' => true,
			),
			'jet-menu-mega-padding' => array(
				'selector'  => '',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => true,
			),
			'jet-menu-min-width' => array(
				'selector'  => '',
				'rule'      => 'min-width',
				'value'     => '%1$spx',
				'important' => false,
				'desktop'   => true,
			),
			'jet-menu-mega-border-radius' => array(
				'selector'  => '',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => true,
			),
			'jet-menu-item-text-color' => array(
				'selector'  => '.jet-menu-item .top-level-link',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-item-desc-color' => array(
				'selector'  => '.jet-menu-item .jet-menu-item-desc.top-level-desc',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-item-padding' => array(
				'selector'  => '.jet-menu-item .top-level-link',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-item-margin' => array(
				'selector'  => '.jet-menu-item .top-level-link',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-item-border-radius' => array(
				'selector'  => '.jet-menu-item .top-level-link',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-top-badge-text-color' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-menu-badge__inner',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-top-badge-padding' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-menu-badge__inner',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-top-badge-margin' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-menu-badge',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-top-badge-border-radius' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-menu-badge__inner',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-badge-text-color' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-menu-badge__inner',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-badge-padding' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-menu-badge__inner',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-badge-margin' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-menu-badge__inner',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-badge-border-radius' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-menu-badge__inner',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-item-text-color-hover' => array(
				'selector'  => '.jet-menu-item:hover > .top-level-link',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-item-desc-color-hover' => array(
				'selector'  => '.jet-menu-item:hover > .top-level-link .jet-menu-item-desc.top-level-desc',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-item-padding-hover' => array(
				'selector'  => '.jet-menu-item:hover > .top-level-link',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-item-margin-hover' => array(
				'selector'  => '.jet-menu-item:hover > .top-level-link',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-item-border-radius-hover' => array(
				'selector'  => '.jet-menu-item:hover > .top-level-link',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-item-text-color-active' => array(
				'selector'  => '.jet-menu-item.jet-current-menu-item .top-level-link',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-item-desc-color-active' => array(
				'selector'  => '.jet-menu-item.jet-current-menu-item .jet-menu-item-desc.top-level-desc',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-item-padding-active' => array(
				'selector'  => '.jet-menu-item.jet-current-menu-item .top-level-link',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-item-margin-active' => array(
				'selector'  => '.jet-menu-item.jet-current-menu-item .top-level-link',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-item-border-radius-active' => array(
				'selector'  => '.jet-menu-item.jet-current-menu-item .top-level-link',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-panel-width-simple' => array(
				'selector'  => 'ul.jet-sub-menu',
				'rule'      => 'min-width',
				'value'     => '%1$spx',
				'important' => false,
			),
			'jet-menu-sub-panel-padding-simple' => array(
				'selector'  => 'ul.jet-sub-menu',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-panel-margin-simple' => array(
				'selector'  => 'ul.jet-sub-menu',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-panel-border-radius-simple' => array(
				'selector'  => 'ul.jet-sub-menu',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-panel-padding-mega' => array(
				'selector'  => 'div.jet-sub-mega-menu',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-panel-margin-mega' => array(
				'selector'  => 'div.jet-sub-mega-menu',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-panel-border-radius-mega' => array(
				'selector'  => 'div.jet-sub-mega-menu',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-text-color' => array(
				'selector'  => 'li.jet-sub-menu-item .sub-level-link',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-desc-color' => array(
				'selector'  => '.jet-menu-item-desc.sub-level-desc',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-padding' => array(
				'selector'  => 'li.jet-sub-menu-item .sub-level-link',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-margin' => array(
				'selector'  => 'li.jet-sub-menu-item .sub-level-link',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-border-radius' => array(
				'selector'  => 'li.jet-sub-menu-item .sub-level-link',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-text-color-hover' => array(
				'selector'  => 'li.jet-sub-menu-item:hover > .sub-level-link',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-desc-color-hover' => array(
				'selector'  => 'li.jet-sub-menu-item:hover > .sub-level-link .jet-menu-item-desc.sub-level-desc',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-padding-hover' => array(
				'selector'  => 'li.jet-sub-menu-item:hover > .sub-level-link',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-margin-hover' => array(
				'selector'  => 'li.jet-sub-menu-item:hover > .sub-level-link',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-border-radius-hover' => array(
				'selector'  => 'li.jet-sub-menu-item:hover > .sub-level-link',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-text-color-active' => array(
				'selector'  => 'li.jet-sub-menu-item.jet-current-menu-item .sub-level-link',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-desc-color-active' => array(
				'selector'  => 'li.jet-sub-menu-item.jet-current-menu-item .jet-menu-item-desc.sub-level-desc',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-padding-active' => array(
				'selector'  => 'li.jet-sub-menu-item.jet-current-menu-item .sub-level-link',
				'rule'      => 'padding-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-margin-active' => array(
				'selector'  => 'li.jet-sub-menu-item.jet-current-menu-item .sub-level-link',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-border-radius-active' => array(
				'selector'  => 'li.jet-sub-menu-item.jet-current-menu-item .sub-level-link',
				'rule'      => 'border-%s-radius',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-top-icon-color' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-menu-icon',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-top-icon-color-hover' => array(
				'selector'  => '.jet-menu-item:hover > .top-level-link .jet-menu-icon',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-top-icon-color-active' => array(
				'selector'  => '.jet-menu-item.jet-current-menu-item .top-level-link .jet-menu-icon',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-icon-color' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-menu-icon',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-icon-color-hover' => array(
				'selector'  => '.jet-menu-item:hover > .sub-level-link .jet-menu-icon',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-icon-color-active' => array(
				'selector'  => 'li.jet-sub-menu-item.jet-current-menu-item .sub-level-link .jet-menu-icon',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-top-arrow-color' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-dropdown-arrow',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-top-arrow-color-hover' => array(
				'selector'  => '.jet-menu-item:hover > .top-level-link .jet-dropdown-arrow',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-top-arrow-color-active' => array(
				'selector'  => '.jet-menu-item.jet-current-menu-item .top-level-link .jet-dropdown-arrow',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-arrow-color' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-dropdown-arrow',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-arrow-color-hover' => array(
				'selector'  => '.jet-menu-item:hover > .sub-level-link .jet-dropdown-arrow',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-arrow-color-active' => array(
				'selector'  => 'li.jet-sub-menu-item.jet-current-menu-item .sub-level-link .jet-dropdown-arrow',
				'rule'      => 'color',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-top-icon-order' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-menu-icon',
				'rule'      => 'order',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-icon-order' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-menu-icon',
				'rule'      => 'order',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-top-badge-order' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-menu-badge',
				'rule'      => 'order',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-badge-order' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-menu-badge',
				'rule'      => 'order',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-top-arrow-order' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-dropdown-arrow',
				'rule'      => 'order',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-sub-arrow-order' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-dropdown-arrow',
				'rule'      => 'order',
				'value'     => '%1$s',
				'important' => false,
			),
			'jet-menu-top-icon-size' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-menu-icon',
				'rule'      => 'font-size',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-menu-top-icon-margin' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-menu-icon',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-icon-size' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-menu-icon',
				'rule'      => 'font-size',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-menu-sub-icon-margin' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-menu-icon',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-top-arrow-size' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-dropdown-arrow',
				'rule'      => 'font-size',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-menu-top-arrow-margin' => array(
				'selector'  => '.jet-menu-item .top-level-link .jet-dropdown-arrow',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),
			'jet-menu-sub-arrow-size' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-dropdown-arrow',
				'rule'      => 'font-size',
				'value'     => '%spx',
				'important' => false,
			),
			'jet-menu-sub-arrow-margin' => array(
				'selector'  => '.jet-menu-item .sub-level-link .jet-dropdown-arrow',
				'rule'      => 'margin-%s',
				'value'     => '',
				'important' => false,
			),

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

			$_wrapper = $wrapper;

			if ( isset( $data['mobile'] ) && true === $data['mobile'] ) {
				$_wrapper = '.jet-mobile-menu-single';
			} else {
				$_wrapper = '.jet-menu';
			}

			$selector = $data['selector'];

			if ( is_array( $value ) && ( isset( $value['units'] ) || isset( $value['is_linked'] ) ) ) {

				if ( is_array( $selector ) ) {

					foreach ( $selector as $key => $selector_item ) {
						jet_menu_dynmic_css()->add_dimensions_css(
							array(
								'selector'  => sprintf( '%1$s %2$s', $_wrapper, $selector_item ),
								'rule'      => $data['rule'],
								'values'    => $value,
								'important' => $data['important'],
							)
						);
					}

				} else {
					jet_menu_dynmic_css()->add_dimensions_css(
						array(
							'selector'  => sprintf( '%1$s %2$s', $_wrapper, $selector ),
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
						sprintf( '%1$s %2$s', $_wrapper, $selector_item ),
						array(
							$data['rule'] => sprintf( $data['value'], esc_attr( $value ) ) . $important,
						)
					);
				}
			} else {

				if ( is_array( $value ) ) {
					$value = '';
				}

				jet_menu()->dynamic_css_manager->add_style(
					sprintf( '%1$s %2$s', $_wrapper, $selector ),
					array(
						$data['rule'] => sprintf( $data['value'],  esc_attr( $value ) ) . $important,
					)
				);
			}

		}

		// Items Styles
		$items_map = array(
			'first' => array(
				'top-left'    => 'top',
				'bottom-left' => 'left',
			),
			'last'  => array(
				'top-right'    => 'right',
				'bottom-right' => 'bottom',
			),
		);

		$wrapper = empty( $wrapper ) ? '.jet-menu' : $wrapper;

		foreach ( $items_map as $item => $data ) {

			$parent_radius = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-mega-border-radius' );

			if ( ! $parent_radius ) {
				continue;
			}

			$is_enabled = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-inherit-' . $item . '-radius' );

			if ( 'true' !== $is_enabled ) {
				continue;
			}

			$styles = array();

			foreach ( $data as $rule => $val ) {

				if ( ! $parent_radius ) {
					continue;
				}

				$styles[ 'border-' . $rule . '-radius' ] = $parent_radius[ $val ] . $parent_radius['units'];
			}

			if ( ! empty( $styles ) ) {

				$selector = '%1$s > .jet-menu-item:%2$s-child > .top-level-link';

				if ( 'last' === $item ) {
					$selectors = array(
						'%1$s > .jet-regular-item.jet-has-roll-up:nth-last-child(2) .top-level-link',
						'%1$s > .jet-regular-item.jet-no-roll-up:nth-last-child(1) .top-level-link',
						'%1$s > .jet-responsive-menu-available-items:last-child .top-level-link',
					);

					$selector = join( ',', $selectors );
				}

				jet_menu()->dynamic_css_manager->add_style(
					sprintf( $selector, $wrapper, $item ),
					$styles
				);
			}

		}

		// Extra Styles
		$max_width = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-item-max-width', 0 );

		if ( 0 !== absint( $max_width ) ) {
			jet_menu()->dynamic_css_manager->add_style(
				sprintf( '%1$s > .jet-menu-item', $wrapper ),
				array(
					'max-width' => absint( $max_width ) . '%',
				)
			);
		}

		$menu_align = jet_menu()->settings_manager->options_manager->get_option( 'jet-menu-container-alignment' );

		if ( 'stretch' === $menu_align ) {
			jet_menu()->dynamic_css_manager->add_style(
				sprintf( '%1$s > .jet-menu-item', $wrapper ),
				array(
					'flex-grow' => 1,
				)
			);

			jet_menu()->dynamic_css_manager->add_style(
				sprintf( '%1$s > .jet-menu-item > a', $wrapper ),
				array(
					'justify-content' => 'center',
				)
			);
		}

		// Mobile Styles
		$divider_enabled = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-divider-enabled', false );

		if ( filter_var( $divider_enabled, FILTER_VALIDATE_BOOLEAN ) ) {

			$divider_color = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-divider-color', '#3a3a3a' );
			$divider_width = jet_menu()->settings_manager->options_manager->get_option( 'jet-mobile-items-divider-width', '1' );

			jet_menu()->dynamic_css_manager->add_style(
				'.jet-mobile-menu-single .jet-mobile-menu__item',
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

			jet_menu()->dynamic_css_manager->add_style( '.jet-mobile-menu-single .jet-menu-icon', array(
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

			jet_menu()->dynamic_css_manager->add_style( '.jet-mobile-menu-single .jet-menu-badge', array(
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
			'jet-top-menu'                     => '.jet-menu .jet-menu-item .top-level-link',
			'jet-top-menu-desc'                => '.jet-menu .jet-menu-item-desc.top-level-desc',
			'jet-sub-menu'                     => '.jet-menu .jet-menu-item .sub-level-link',
			'jet-sub-menu-desc'                => '.jet-menu .jet-menu-item-desc.sub-level-desc',
			'jet-menu-top-badge'               => '.jet-menu .jet-menu-item .top-level-link .jet-menu-badge__inner',
			'jet-menu-sub-badge'               => '.jet-menu .jet-menu-item .sub-level-link .jet-menu-badge__inner',
			'jet-menu-mobile-toggle-text'      => '.jet-mobile-menu-single .jet-mobile-menu__toggle .jet-mobile-menu__toggle-text',
			'jet-menu-mobile-back-text'        => '.jet-mobile-menu-single .jet-mobile-menu__container .jet-mobile-menu__back span',
			'jet-menu-mobile-breadcrumbs-text' => '.jet-mobile-menu-single .jet-mobile-menu__container .breadcrumb-label',
			'jet-mobile-items-label'           => '.jet-mobile-menu-single .jet-mobile-menu__item .mobile-link .jet-menu-label',
			'jet-mobile-items-desc'            => '.jet-mobile-menu-single .jet-mobile-menu__item .mobile-link .jet-menu-desc',
			'jet-mobile-items-badge'           => '.jet-mobile-menu-single .jet-mobile-menu__item .mobile-link .jet-menu-badge__inner',
		) );

		foreach ( $fonts_options as $font => $selector ) {
			jet_menu_dynmic_css()->add_single_font_styles( $font, $preset . $selector );
		}

	}

	/**
	 * Add backgound styles.
	 */
	public function add_backgrounds( $preset = '' ) {

		$preset = ( ! empty( $preset ) ) ? $preset : '';

		$bg_options = apply_filters( 'jet-menu/menu-css/backgrounds', array(
			'jet-menu-container'        => '.jet-menu',
			'jet-menu-item'             => '.jet-menu .jet-menu-item .top-level-link',
			'jet-menu-item-hover'       => '.jet-menu .jet-menu-item:hover > .top-level-link',
			'jet-menu-item-active'      => '.jet-menu .jet-menu-item.jet-current-menu-item .top-level-link',
			'jet-menu-top-badge-bg'     => '.jet-menu .jet-menu-item .top-level-link .jet-menu-badge__inner',
			'jet-menu-sub-badge-bg'     => '.jet-menu .jet-menu-item .sub-level-link .jet-menu-badge__inner',
			'jet-menu-sub-panel-simple' => '.jet-menu ul.jet-sub-menu',
			'jet-menu-sub-panel-mega'   => '.jet-menu div.jet-sub-mega-menu',
			'jet-menu-sub'              => '.jet-menu li.jet-sub-menu-item .sub-level-link',
			'jet-menu-sub-hover'        => '.jet-menu li.jet-sub-menu-item:hover > .sub-level-link',
			'jet-menu-sub-active'       => '.jet-menu li.jet-sub-menu-item.jet-current-menu-item .sub-level-link',
		) );

		foreach ( $bg_options as $option => $selector ) {
			jet_menu_dynmic_css()->add_single_bg_styles( $option, $preset . $selector );
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
	 * Process position styles
	 */
	public function add_positions( $preset = '' ) {

		$preset = ( ! empty( $preset ) ) ? $preset : '';

		$options = apply_filters( 'jet-menu/menu-css/positions', array(
			'jet-menu-top-icon-%s-position'  => '.jet-menu .jet-menu-item .top-level-link .jet-menu-icon',
			'jet-menu-sub-icon-%s-position'  => '.jet-menu .jet-menu-item .sub-level-link .jet-menu-icon',
			'jet-menu-top-badge-%s-position' => '.jet-menu .jet-menu-item .top-level-link .jet-menu-badge',
			'jet-menu-sub-badge-%s-position' => '.jet-menu .jet-menu-item .sub-level-link .jet-menu-badge',
			'jet-menu-top-arrow-%s-position' => '.jet-menu .jet-menu-item .top-level-link .jet-dropdown-arrow',
			'jet-menu-sub-arrow-%s-position' => '.jet-menu .jet-menu-item .sub-level-link .jet-dropdown-arrow',
		) );

		foreach ( $options as $option => $selector ) {
			jet_menu_dynmic_css()->add_single_position( $option, $preset . $selector );
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

			$icon_type = isset( $item_settings['menu_icon_type'] ) ? $item_settings['menu_icon_type'] : 'icon';

			switch ( $icon_type ) {
				case 'icon':
					$item_icon = ! empty( $item_settings['menu_icon'] ) ? sprintf( '<i class="fa %s"></i>', esc_attr( $item_settings['menu_icon'] ) ) : false;
					break;

				case 'svg':
					$item_icon = ! empty( $item_settings['menu_svg'] ) ? jet_menu_tools()->get_svg_html( $item_settings['menu_svg'], false ) : false;
					break;
			}

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
			jet_menu_tools()->add_menu_css( $item->ID, '.jet-menu-item-' . $item->ID );
		}
	}

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		$this->load_files();

		add_filter( 'body_class', array( $this, 'modify_body_class' ) );

		add_action( 'jet-menu/blank-page/after-content', array( $this, 'set_menu_canvas_bg' ) );

		add_filter( 'walker_nav_menu_start_el', array( $this, 'fix_double_desc' ), 0, 4 );

		add_filter( 'wp_nav_menu_args', array( $this, 'set_menu_args' ), 99999 );

		add_filter( 'pre_wp_nav_menu', array( $this, 'modify_pre_wp_nav_menu' ), 10, 2 );

	}
}
