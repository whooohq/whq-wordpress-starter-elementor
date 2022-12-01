<?php
namespace Jet_Menu\Render;

class Mobile_Menu_Render extends Base_Render {

	/**
	 * [$name description]
	 * @var string
	 */
	protected $name = 'mobile-menu-render';

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function init() {}

	/**
	 * [get_name description]
	 * @return [type] [description]
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return array|void
	 */
	public function default_settings() {
	    return array();
    }

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	public function render() {

		$menu_id = $this->get( 'menu-id', false );

		if ( ! isset( $menu_id ) || empty( $menu_id ) ) {
			$available_menus_options = jet_menu_tools()->get_available_menus_options();

            if ( ! empty( $available_menus_options ) ) {
	            $menu_id = $available_menus_options[0]['value'];
            } else {
	            echo sprintf(
		            '<span>' . esc_html__( '%3$s Go to the %1$sMenus screen%2$s to create one.', 'jet-menu' )  . '</span>',
		            sprintf( '<a href="%s" target="_blank">', admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
		            '</a>',
		            '<span>' . esc_html__( 'There are no menus in your site.', 'jet-menu' ) . '</span>'
	            );

	            return;
            }
		}

		$menu_uniqid    = uniqid();
		$mobile_menu_id = $this->get( 'mobile-menu-id', false );

		$menu_options = array(
			'menuUniqId'         => $menu_uniqid,
			'menuId'             => $menu_id,
			'mobileMenuId'       => $mobile_menu_id,
			'location'           => $this->get( 'location', 'wp-nav' ),
			'menuLocation'       => false,
			'menuLayout'         => $this->get( 'layout', 'slide-out' ),
			'togglePosition'     => $this->get( 'toggle-position', 'default' ),
			'menuPosition'       => $this->get( 'container-position', 'right' ),
			'headerTemplate'     => $this->get( 'item-header-template', '' ),
			'beforeTemplate'     => $this->get( 'item-before-template', '' ),
			'afterTemplate'      => $this->get( 'item-after-template', '' ),
			'useBreadcrumb'      => $this->get( 'use-breadcrumbs', true ),
			'breadcrumbPath'     => $this->get( 'breadcrumbs-path', true ),
			'toggleText'         => esc_attr( $this->get( 'toggle-text', '' ) ),
			'toggleLoader'       => $this->get( 'toggle-loader', true ),
			'backText'           => esc_attr( $this->get( 'back-text', 'Back' ) ),
			'itemIconVisible'    => $this->get( 'is-item-icon', true ),
			'itemBadgeVisible'   => $this->get( 'is-item-badge', true ),
			'itemDescVisible'    => $this->get( 'is-item-desc', true ),
			'loaderColor'        => $this->get( 'loader-color', '#3a3a3a' ),
			'subTrigger'         => $this->get( 'sub-menu-trigger', 'item' ),
			'subOpenLayout'      => $this->get( 'sub-open-layout', 'slide-in' ),
			'closeAfterNavigate' => $this->get( 'close-after-navigate', false ),
		);

		$toggle_closed_icon_html = $this->get( 'toggle-closed-icon-html', '' );
		$toggle_opened_icon_html = $this->get( 'toggle-opened-icon-html', '' );
		$container_close_icon_html = $this->get( 'close-icon-html', '' );
		$container_back_icon_html = $this->get( 'back-icon-html', '' );
		$dropdown_icon_html = $this->get( 'dropdown-icon-html', '' );
		$dropdown_opened_icon_html = $this->get( 'dropdown-opened-icon-html', '' );
		$breadcrumb_icon_html = $this->get( 'breadcrumb-icon-html', '' );

		$icons_data = array(
			'toggleClosedIcon'   => ! empty( $toggle_closed_icon_html ) ? $toggle_closed_icon_html : jet_menu()->svg_manager->get_svg_html( 'menu' ),
			'toggleOpenedIcon'   => ! empty( $toggle_opened_icon_html ) ? $toggle_opened_icon_html : jet_menu()->svg_manager->get_svg_html( 'no-alt' ),
			'closeIcon'          => ! empty( $container_close_icon_html ) ? $container_close_icon_html : jet_menu()->svg_manager->get_svg_html( 'no-alt' ),
			'backIcon'           => ! empty( $container_back_icon_html ) ? $container_back_icon_html : jet_menu()->svg_manager->get_svg_html( 'arrow-left' ),
			'dropdownIcon'       => ! empty( $dropdown_icon_html ) ? $dropdown_icon_html : jet_menu()->svg_manager->get_svg_html( 'arrow-right' ),
			'dropdownOpenedIcon' => ! empty( $dropdown_opened_icon_html ) ? $dropdown_opened_icon_html : jet_menu()->svg_manager->get_svg_html( 'arrow-down' ),
			'breadcrumbIcon'     => ! empty( $breadcrumb_icon_html ) ? $breadcrumb_icon_html : jet_menu()->svg_manager->get_svg_html( 'arrow-right' ),
		);

		$widget_refs = '';
		$icons_html = '';

		if ( ! empty( $icons_data ) ) {

			foreach ( $icons_data as $slug => $icon_html ) {
				$icons_html .= sprintf( '<div ref="%s">%s</div>', $slug, $icon_html );
			}

			$widget_refs = sprintf( '<div class="jet-mobile-menu__refs">%s</div>', $icons_html );
		}

		$menu_options = esc_attr( json_encode( $menu_options, JSON_UNESCAPED_SLASHES ) );

		$instance_classes = apply_filters( 'jet-menu/mobile-menu-render/instance-classes', array(
			'jet-mobile-menu',
			'jet-mobile-menu--location-' . $this->get( 'location', 'wp-nav' ),
		) );

		$instance_attributes = array(
            'id'    => 'jet-mobile-menu-' . $menu_uniqid,
			'class' => $instance_classes,
		);

		$instance_attr_string = jet_menu_tools()->get_attr_string( $instance_attributes );

		?><div <?php echo $instance_attr_string; ?> data-menu-id="<?php echo $menu_id; ?>" data-menu-options="<?php echo $menu_options; ?>">
			<mobile-menu></mobile-menu><?php
			echo $widget_refs;
		?></div><?php

		$this->add_menu_advanced_styles( $menu_id );
	}

	/**
	 * @param $menu_id
	 *
	 * @return false|void
	 */
	public function add_menu_advanced_styles( $menu_id = false ) {

		if ( ! $menu_id ) {
			return false;
		}

		$menu_items = jet_menu()->render_manager->get_menu_items_object_data( $menu_id );

		if ( ! $menu_items ) {
			return false;
		}

		foreach ( $menu_items as $key => $item ) {
			jet_menu_tools()->add_menu_css( $item->ID, '#jet-mobile-menu-item-' . $item->ID );
		}
	}


}
