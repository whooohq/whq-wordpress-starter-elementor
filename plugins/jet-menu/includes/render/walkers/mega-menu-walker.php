<?php
namespace Jet_Menu\Render;

/**
 * Walker class
 */
class Mega_Menu_Walker extends \Walker_Nav_Menu {

	/**
	 * @var string
	 */
	protected $item_type   = 'simple';

	/**
	 * @var null
	 */
	private $item_settings = null;

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'jet-mega-menu-sub-menu__list' );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<div class='jet-mega-menu-sub-menu'><ul $class_names>{$n}";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {

		if ( 'mega' === $this->get_item_type() ) {
			//return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		$output .= "$indent</ul></div>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		// Don't put any code before this!
		$this->item_settings = null;
		$this->set_item_type( $item->ID, $depth );
		$level = ( 0 === $depth ) ? 'top' : 'sub';

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$settings = $this->get_settings( $item->ID );
		$indent   = ( $depth ) ? str_repeat( $t, $depth ) : '';
		$classes  = empty( $item->classes ) ? array() : (array) $item->classes;

		if ( 'mega' === $this->get_item_type() ) {
			$classes[] = 'menu-item--mega';

			if ( ! empty( $settings['custom_mega_menu_position'] ) && 'default' !== $settings['custom_mega_menu_position'] ) {
				$classes[] = 'menu-item--' . esc_attr( $settings['custom_mega_menu_position'] );
			}

		} else {
			$classes[] = 'menu-item--default';
		}

		if ( $this->is_mega_enabled( $item->ID ) ) {
			$classes[] = 'menu-item-has-children';
		}

		// Add an active class for ancestor items
		if ( in_array( 'current-menu-ancestor', $classes ) || in_array( 'current-page-ancestor', $classes ) ) {
			$classes[] = 'menu-item--current';
		}

		$classes[] = 'menu-item--' . $level . '-level';

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */

		$classes = array_filter( $classes );

		array_walk( $classes, array( $this, 'modify_menu_item_classes' ) );

		$classes[] = 'jet-mega-menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', $classes, $item, $args, $depth ) );

		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'jet-mega-menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$link_classes = array();

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$hide_item_text = isset( $settings['hide_item_text'] ) && filter_var( $settings['hide_item_text'], FILTER_VALIDATE_BOOLEAN );

		$link_classes[] = ( 0 === $depth ) ? 'jet-mega-menu-item__link jet-mega-menu-item__link--top-level'  : 'jet-mega-menu-item__link jet-mega-menu-item__link--sub-level';

		if ( $hide_item_text ) {
			$link_classes[] = 'label-hidden';
		}

		$atts['class'] = implode( ' ', $link_classes );

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';

		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$icon  = '';
		$desc  = '';
		$badge = '';
		$dropdown_html = '';

		if ( ! empty( $settings['menu_svg'] ) ) {
			$icon = $this->get_svg_icon_html( $settings['menu_svg'] );
		}

		if ( ! empty( $item->description ) ) {
			$desc = sprintf(
				'<div class="jet-mega-menu-item__desc">%1$s</div>',
				$item->description
			);
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		if ( ! $hide_item_text ) {
			$title_html = sprintf( '<div class="jet-mega-menu-item__title"><div class="jet-mega-menu-item__label">%1$s</div>%2$s</div>', $title, $desc );
		} else {
			$title_html = '';
		}

		if ( ! empty( $settings['menu_badge'] ) ) {
			$badge = $this->get_badge_html( $settings['menu_badge'], $depth );
		}

		$dropdown_icon = $args->settings[ 'dropdown-icon' ];

		if ( $args->settings['use-dropdown-icon'] && ! empty( $dropdown_icon ) && ( in_array( 'menu-item-has-children', $item->classes ) || $this->is_mega_enabled( $item->ID ) ) ) {
			
			$format = apply_filters(
				'jet-menu/mega-menu-walker/dropdown-format',
				'<div class="jet-mega-menu-item__dropdown">%1$s</div>',
				$dropdown_icon
			);

			$dropdown_html = sprintf( $format, $dropdown_icon );
		}

		if ( $hide_item_text && 'top' === $level ) {
			$dropdown_html = '';
		}

		$link_html = sprintf(
			'%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->link_before,
			$attributes,
			$icon,
			$title_html,
			$badge,
			$args->link_after
		);

		$item_output = sprintf(
			'%1$s<div class="jet-mega-menu-item__inner" tabindex="1" aria-label="%5$s">%2$s%3$s</div>%4$s',
			$args->before,
			$link_html,
			$dropdown_html,
			$args->after,
			wp_strip_all_tags( $title )
		);

		$is_elementor = ( isset( $_GET['elementor-preview'] ) ) ? true : false;

		$mega_item = get_post_meta( $item->ID, jet_menu()->post_type_manager->meta_key(), true );

		if ( $this->is_mega_enabled( $item->ID ) && ! $is_elementor ) {

			$content = '';

			if ( ! filter_var( $args->settings['ajax-loading'], FILTER_VALIDATE_BOOLEAN ) ) {
				do_action( 'jet-menu/mega-sub-menu/before-render', $item->ID );

				if ( class_exists( 'Elementor\Plugin' ) ) {
					$elementor = \Elementor\Plugin::instance();
					$content   = $elementor->frontend->get_builder_content_for_display( $mega_item );
				}

				$content = do_shortcode( $content );

				do_action( 'jet-menu/mega-sub-menu/after-render', $item->ID );

			} else {
				ob_start();
				include jet_menu()->get_template( 'public/mega-content-loader.php' );
				$content = ob_get_clean();
			}

			if ( ! empty( $settings['custom_mega_menu_position'] ) && 'default' !== $settings['custom_mega_menu_position'] ) {
				$classes[] = 'menu-item--' . esc_attr( $settings['custom_mega_menu_position'] );
			}

			$position = ( !empty( $settings['custom_mega_menu_position'] ) && 'default' !== $settings['custom_mega_menu_position'] ) ? 'relative' : 'default';

			$item_output .= sprintf( '<div class="jet-mega-menu-mega-container" data-template-id="%s" data-position="%s"><div class="jet-mega-menu-mega-container__inner">%s</div></div>', $mega_item, $position, $content );

		}

		jet_menu_tools()->add_menu_css( $item->ID, '.jet-mega-menu-item-' . $item->ID );

		$item_output = apply_filters( 'jet-menu/mega-menu-walker/start-el', $item_output, $item, $this, $depth, $args );

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {

		if ( 'mega' === $this->get_item_type() && 0 < $depth ) {
			//return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$item_output = "</li>{$n}";
		$item_output = apply_filters( 'jet-menu/mega-menu-walker/end-el', $item_output, $item, $this, $depth, $args );

		$output .= $item_output;

	}

	/**
	 * Modify menu item classes list
	 *
	 * @param  string &$item
	 * @return void
	 */
	public function modify_menu_item_classes( &$item, $index ) {

		if ( 0 === $index && 'menu-item' !== $item ) {
			return;
		}

		$item = 'jet-mega-' . $item;
	}

	/**
	 * Store in WP Cache processed item type
	 *
	 * @param integer $item_id Current menu Item ID
	 * @param integer $depth   Current menu Item depth
	 */
	public function set_item_type( $item_id = 0, $depth = 0 ) {

		if ( 0 < $depth ) {
			//return;
		}

		$item_type = 'simple';

		if ( $this->is_mega_enabled( $item_id ) ) {
			$item_type = 'mega';
		}

		wp_cache_set( 'item-type', $item_type, 'jet-menu' );

	}

	/**
	 * Returns current item (for top level items) or parent item (for subs) type.
	 * @return [type] [description]
	 */
	public function get_item_type() {
		return wp_cache_get( 'item-type', 'jet-menu' );
	}

	/**
	 * Check if mega menu enabled for passed item
	 *
	 * @param  int  $item_id Item ID
	 * @return boolean
	 */
	public function is_mega_enabled( $item_id = 0 ) {

		$item_settings = $this->get_settings( $item_id );
		$menu_post     = jet_menu()->post_type_manager->get_related_menu_post( $item_id );

		return ( isset( $item_settings['enabled'] ) && 'true' == $item_settings['enabled'] && ! empty( $menu_post ) );
	}

	/**
	 * Get item settings
	 *
	 * @param  integer $item_id Item ID
	 * @return array
	 */
	public function get_settings( $item_id = 0 ) {

		if ( null === $this->item_settings ) {
			$this->item_settings = jet_menu()->settings_manager->get_item_settings( $item_id );
		}

		return $this->item_settings;
	}

	/**
	 * [get_svg_html description]
	 * @param  string $svg_id [description]
	 * @return [type]         [description]
	 */
	public function get_svg_icon_html( $svg_id = '', $wrapper = true ) {

		if ( empty( $svg_id ) ) {
			return '';
		}

		$url = wp_get_attachment_url( $svg_id );

		if ( ! $url ) {
			return '';
		}

		return jet_menu_tools()->get_image_by_url( $url, array( 'class' => 'jet-mega-menu-item__icon' ), $wrapper );
	}

	/**
	 * @param string $badge
	 * @param int $depth
	 *
	 * @return string
	 */
	public function get_badge_html( $badge = '', $depth = 0 ) {
		$format = apply_filters(
			'jet-menu/mega-menu-walker/badge-format',
			'<div class="jet-mega-menu-item__badge"><small class="jet-mega-menu-item__badge-inner">%1$s</small></div>',
			$badge,
			$depth
		);

		return sprintf( $format, esc_attr( $badge ) );
	}
}
