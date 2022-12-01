<?php
namespace Jet_Menu\Render;

class Manager {

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
	public $location_manager = null;

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
	public function load_files() {

		$location_path = jet_menu_tools()->is_nextgen_mode() ? 'includes/render' : 'includes/render/legacy';

		require jet_menu()->plugin_path( "{$location_path}/location.php" );

		require jet_menu()->plugin_path( 'includes/render/base-render.php' );
		require jet_menu()->plugin_path( 'includes/render/render-modules/mega-menu-render.php' );
		require jet_menu()->plugin_path( 'includes/render/render-modules/mobile-menu-render.php' );
		require jet_menu()->plugin_path( 'includes/render/render-modules/block-editor-template-render.php' );
		require jet_menu()->plugin_path( 'includes/render/render-modules/elementor-template-render.php' );

		if ( jet_menu_tools()->is_nextgen_mode() ) {
			require jet_menu()->plugin_path( 'includes/render/walkers/mega-menu-walker.php' );
			require jet_menu()->plugin_path( 'includes/render/walkers/vertical-menu-walker.php' );
		} else {
			require jet_menu()->plugin_path( 'includes/render/walkers/legacy/main-menu-walker.php' );
			require jet_menu()->plugin_path( 'includes/render/walkers/legacy/vertical-menu-walker.php' );
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

			$item_content_type = isset( $item_settings['content_type'] ) ? $item_settings['content_type'] : 'default';

			switch ( $item_content_type ) {
				case 'default':
					$mega_template_id = get_post_meta( $item_id, 'jet-menu-item-block-editor', true );
					break;
				case 'elementor':
					$mega_template_id = get_post_meta( $item_id, 'jet-menu-item', true );

					break;
			}

			$template_id = ( isset( $item_settings['enabled'] ) && filter_var( $item_settings['enabled'], FILTER_VALIDATE_BOOLEAN ) ) ? (int)$mega_template_id : false;

			$item_icon = ! empty( $item_settings['menu_svg'] ) ? jet_menu_tools()->get_svg_html( $item_settings['menu_svg'], false ) : false;

			$badge_content_type = isset( $item_settings['menu_badge_type'] ) ? $item_settings['menu_badge_type'] : 'text';

			switch ( $badge_content_type ) {
				case 'text':
					$badge_content = isset( $item_settings[ 'menu_badge' ] ) ? $item_settings[ 'menu_badge' ] : false;

					break;
				case 'svg':
					$badge_content = ! empty( $item_settings['badge_svg'] ) ? jet_menu_tools()->get_svg_html( $item_settings['badge_svg'], false ) : false;;

					break;
			}

			$items[] = array (
				'id'              => 'item-' . $item_id,
				'name'            => $item->title,
				'attrTitle'       => ! empty( $item->attr_title ) ? $item->attr_title : false,
				'description'     => $item->description,
				'url'             => $item->url,
				'target'          => ! empty( $item->target ) ? $item->target : false,
				'xfn'             => ! empty( $item->xfn ) ? $item->xfn : false,
				'itemParent'      => ! empty( $item->menu_item_parent ) ? 'item-' . $item->menu_item_parent : false,
				'itemId'          => $item_id,
				'megaTemplateId'  => $template_id,
				'megaContent'     => false,
				'megaContentType' => $item_content_type,
				'open'            => false,
				'badgeContent'    => $badge_content,
				'itemIcon'        => $item_icon,
				'classes'         => $item->classes,
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

				if ( $children && !$item['megaTemplateId'] ) {
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
	 * Constructor for the class
	 */
	public function __construct() {

		$this->load_files();

		$this->location_manager = new Location();

	}
}
