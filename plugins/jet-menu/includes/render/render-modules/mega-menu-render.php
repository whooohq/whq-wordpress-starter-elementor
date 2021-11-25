<?php
namespace Jet_Menu\Render;

class Mega_Menu_Render extends Base_Render {

	/**
	 * [$name description]
	 * @var string
	 */
	protected $name = 'mega-menu-render';

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
	    return array(
		    'menu'                  => false,
		    'layout'                => 'horizontal', //horizontal, vertical, dropdown
		    'breakpoint'            => 768,
		    'dropdown-layout'       => 'default', //default, push, slide-out
		    'sub-animation'         => 'none',
		    'roll-up'               => false,
		    'roll-up-type'          => 'text', //text, font-icon, svg-icon
		    'roll-up-item-text'     => '...',
		    'roll-up-item-svg-icon' => '',
		    'dropdown-icon'         => '',
		    'location'              => 'wp-nav', //wp-nav, elementor, block
		    'ajax-loading'          => false
        );
    }

	/**
	 * [render description]
	 * @return [type] [description]
	 */
	public function render() {
		global $is_iphone;

		$menu_id = $this->get( 'menu', false );

		$menu_uniqid = uniqid();

		// Get animation type for mega menu instance
		$roll_up = filter_var( $this->get( 'roll-up', true ), FILTER_VALIDATE_BOOLEAN );

		$list_attributes = apply_filters( 'jet-menu/mega-menu-render/list-attr', array(
			'class' => array(
				'jet-mega-menu-list',
			),
			'role' => array(
				'navigation'
            ),
		) );

		$list_attr_string = jet_menu_tools()->get_attr_string( $list_attributes );

		$roll_up_item = $this->get_roll_up_item_html();

		$items_wrap = '<ul' . $list_attr_string . '>%3$s' . $roll_up_item . '</ul>';

		$dropdown_icon = $this->get( 'dropdown-icon', '' );

		//$dropdown_icon = ! empty( $dropdown_icon ) ? $dropdown_icon : jet_menu()->svg_manager->get_svg_html( 'arrow-down' );

		$menu_args = array(
			'menu'            => $menu_id,
			'container_class' => 'jet-mega-menu-container',
			'menu_class'      => '',
			'items_wrap'      => $items_wrap,
			'before'          => '',
			'after'           => '',
			'fallback_cb'     => '',
			'walker'          => new \Jet_Menu\Render\Mega_Menu_Walker(),
			'settings' => array(
				'roll-up'            => filter_var( $roll_up, FILTER_VALIDATE_BOOLEAN ),
				'use-dropdown-icon'  => $this->get( 'use-dropdown-icon', true ),
				'dropdown-icon'      => $dropdown_icon,
				'ajax-loading'       => $this->get( 'ajax-loading', false ),
			)
		);

		$front_settings = array(
			'menuId'            => $menu_id,
			'menuUniqId'        => $menu_uniqid,
			'rollUp'            => filter_var( $roll_up, FILTER_VALIDATE_BOOLEAN ),
			'layout'            => $this->get( 'layout', 'horizontal' ),
			'subEvent'          => $this->get( 'sub-event', 'hover' ),
			'subTrigger'        => $this->get( 'sub-trigger', 'item' ),
			'subPosition'       => $this->get( 'sub-position', 'right' ),
			'megaWidthType'     => $this->get( 'mega-width-type', 'container' ),
			'megaWidthSelector' => $this->get( 'mega-width-selector', '' ),
			'breakpoint'        => $this->get( 'breakpoint', 768 ),
        );

		$settings_attr = sprintf( 'data-settings=\'%1$s\'', json_encode( $front_settings ) );

		$instance_classes = apply_filters( 'jet-menu/mega-menu-render/instance-classes', array(
			'jet-mega-menu',
			'jet-mega-menu--layout-' . $this->get( 'layout', 'horizontal' ),
			'jet-mega-menu--sub-position-' . $this->get( 'sub-position', 'left' ),
			'jet-mega-menu--dropdown-layout-' . $this->get( 'dropdown-layout', 'default' ),
			'jet-mega-menu--dropdown-position-' . $this->get( 'dropdown-position', 'right' ),
			'jet-mega-menu--animation-' . $this->get( 'sub-animation', 'none' ),
			'jet-mega-menu--location-' . $this->get( 'location', 'wp-nav' ),
			$roll_up ? 'jet-mega-menu--roll-up' : '',
			filter_var( $is_iphone, FILTER_VALIDATE_BOOLEAN ) ? 'jet-mega-menu--iphone-mode' : '',
		) );

		$instance_attributes = array(
			'class' => $instance_classes,
		);

		$instance_attr_string = jet_menu_tools()->get_attr_string( $instance_attributes );

        ?><div<?php echo $instance_attr_string; ?> <?php echo $settings_attr ?>><?php
            echo $this->get_dropdown_toggle_html();
			wp_nav_menu( $menu_args );
		?></div><?php

		$this->add_items_advanced_styles( $menu_args['menu'] );
	}

	/**
	 * @return string
	 */
	public function get_roll_up_item_html() {
		$roll_up = filter_var( $this->get( 'roll-up', true ), FILTER_VALIDATE_BOOLEAN );
		$layout = $this->get( 'layout', 'horizontal' );

		if ( ! $roll_up || 'horizontal' !== $layout  ) {
		    return '';
		}

		$icon_type = $this->get( 'roll-up-type', 'text' );

		switch ( $icon_type ) {
			case 'text':
				$text = $this->get( 'roll-up-item-text', '...' );
				$html = "<div class='jet-mega-menu-item__title'><div class='jet-mega-menu-item__label'>{$text}</div></div>";
				break;
			case 'icon':
				$format = apply_filters(  'jet-menu/mega-menu-render/roll-up/format', '<div class="jet-mega-menu-item__icon">%1$s</div>' );
				$icon_html = $this->get( 'roll-up-item-icon', '' );
				$icon_html = ! empty( $icon_html ) ? $icon_html : jet_menu()->svg_manager->get_svg_html( 'ellipsis' );
				$html = sprintf( $format, $icon_html );
				break;
		}

		return "<li class='jet-mega-menu-item jet-mega-menu-item--default jet-mega-menu-item-has-children jet-mega-menu-item--top-level jet-mega-menu-item--roll-up' hidden><div class='jet-mega-menu-item__inner'><a class='jet-mega-menu-item__link jet-mega-menu-item__link--top-level' href='#'>{$html}</a></div><div class='jet-mega-menu-sub-menu'><ul class='jet-mega-menu-sub-menu__list'></ul></div></li>";
	}

	/**
	 * @return string
	 */
	public function get_dropdown_toggle_html() {
		$default_icon = $this->get( 'toggle-default-icon', '' );
		$opened_icon = $this->get( 'toggle-opened-icon', '' );

		$default_icon = ! empty( $default_icon ) ? $default_icon : jet_menu()->svg_manager->get_svg_html( 'menu' );
		$opened_icon = ! empty( $opened_icon ) ? $opened_icon : jet_menu()->svg_manager->get_svg_html( 'no-alt' );

		if ( ! empty( $opened_icon ) ) {
			$icons_html = sprintf( '<div class="jet-mega-menu-toggle-icon jet-mega-menu-toggle-icon--default-state">%1$s</div><div class="jet-mega-menu-toggle-icon jet-mega-menu-toggle-icon--opened-state">%2$s</div>', $default_icon, $opened_icon );
		} else {
			$icons_html = sprintf( '<div class="jet-mega-menu-toggle-icon jet-mega-menu-toggle-icon--default-state">%1$s</div>', $default_icon );
		}

		$format = apply_filters( 'jet-menu/mega-menu-render/dropdown-toggle/format', '<div class="jet-mega-menu-toggle" tabindex="1" aria-label="Open/Close Menu">%1$s</div>' );

		return sprintf( $format, $icons_html );
	}

	/**
	 * @param false $menu_id
	 *
	 * @return false
	 */
	public function add_items_advanced_styles( $menu_id = false ) {

		if ( ! $menu_id ) {
			return false;
		}

		$menu_items = jet_menu()->render_manager->get_menu_items_object_data( $menu_id );

		if ( ! $menu_items ) {
			return false;
		}

		foreach ( $menu_items as $key => $item ) {
			$this->add_item_advanced_styles( $item->ID, '.jet-mega-menu-item-' . $item->ID );
		}
	}

	/**
	 * @param int $item_id
	 * @param string $wrapper
	 */
	public function add_item_advanced_styles( $item_id = 0, $wrapper = '' ) {

		$settings = jet_menu()->settings_manager->get_item_settings( $item_id );

		$css_scheme = apply_filters( 'jet-menu/mega-menu/item-advanced-css/scheme', array (
			'icon_color' => array (
				'selector' => array (
					'> .jet-mega-menu-item__inner > a .jet-mega-menu-item__icon'        => 'color',
				),
				'rule'     => 'color',
				'value'    => '%1$s !important;',
			),
			'icon_size'              => array (
				'selector' => array (
					'> .jet-mega-menu-item__inner > a .jet-mega-menu-item__icon'     => 'font-size',
					'> .jet-mega-menu-item__inner > a .jet-mega-menu-item__icon svg' => 'width',
				),
				'rule'     => 'font-size',
				'value'    => '%1$spx !important;',
			),
			'badge_color'            => array (
				'selector' => array (
					'> .jet-mega-menu-item__inner > a .jet-mega-menu-item__badge' => 'color',
				),
				'rule'     => 'color',
				'value'    => '%1$s !important;',
			),
			'badge_bg_color'         => array (
				'selector' => array (
					'> .jet-mega-menu-item__inner > a .jet-mega-menu-item__badge-inner' => 'background-color',
				),
				'rule'     => 'background-color',
				'value'    => '%1$s !important;',
			),
			'item_padding'           => array (
				'selector' => array (
					'> .jet-mega-menu-item__inner' => 'padding-%s',
				),
				'rule'     => 'padding-%s',
				'value'    => '',
			),
			'custom_mega_menu_width' => array (
				'selector' => '> .jet-mega-menu-mega-container',
				'rule'     => 'width',
				'value'    => '%1$spx !important;',
			),
		) );

		foreach ( $css_scheme as $setting => $data ) {

			if ( empty( $settings[ $setting ] ) ) {
				continue;
			}

			if ( is_array( $settings[ $setting ] ) && isset( $settings[ $setting ][ 'units' ] ) ) {

				if ( is_array( $data[ 'selector' ] ) ) {
					foreach ( $data[ 'selector' ] as $selector => $rule ) {
						jet_menu_dynmic_css()->add_dimensions_css( array (
							'selector'  => sprintf( '%1$s %2$s', $wrapper, $selector ),
							'rule'      => $rule,
							'values'    => $settings[ $setting ],
							'important' => true,
						) );
					}
				} else {
					jet_menu_dynmic_css()->add_dimensions_css( array (
						'selector'  => sprintf( '%1$s %2$s', $wrapper, $data[ 'selector' ] ),
						'rule'      => $data[ 'rule' ],
						'values'    => $settings[ $setting ],
						'important' => true,
					) );
				}

			} else {

				if ( ! isset( $settings[ $setting ] ) || false === $settings[ $setting ] || 'false' === $settings[ $setting ] || '' === $settings[ $setting ] ) {
					continue;
				}

				if ( is_array( $data[ 'selector' ] ) ) {
					foreach ( $data[ 'selector' ] as $selector => $rule ) {
						jet_menu()->dynamic_css_manager->add_style( sprintf( '%1$s %2$s', $wrapper, $selector ), array (
							$rule => sprintf( $data[ 'value' ], esc_attr( $settings[ $setting ] ) ),
						) );
					}
				} else {
					jet_menu()->dynamic_css_manager->add_style( sprintf( '%1$s %2$s', $wrapper, $data[ 'selector' ] ), array (
						$data[ 'rule' ] => sprintf( $data[ 'value' ], esc_attr( $settings[ $setting ] ) ),
					) );
				}
			}
		}
	}

}
