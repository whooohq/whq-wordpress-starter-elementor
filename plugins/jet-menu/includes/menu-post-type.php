<?php
namespace Jet_Menu;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Menu_Post_Type
 * @package Jet_Menu
 */
class Menu_Post_Type {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * @var string
	 */
	protected $post_type = 'jet-menu';

	/**
	 * @var string
	 */
	protected $meta_key  = 'jet-menu-item';

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
	 * Constructor for the class
	 */
	public function __construct() {

		$this->register_post_type();

		$this->edit_mega_template_redirect();

		add_filter( 'option_elementor_cpt_support', array( $this, 'set_option_support' ) );

		add_filter( 'default_option_elementor_cpt_support', array( $this, 'set_option_support' ) );

		add_action( 'template_include', array( $this, 'set_post_type_template' ), 9999 );

		add_filter( 'body_class', array( $this, 'add_body_classes' ), 9 );
	}

	/**
	 * Returns post type slug
	 *
	 * @return string
	 */
	public function slug() {
		return $this->post_type;
	}

	/**
	 * Returns Mega Menu meta key
	 *
	 * @return string
	 */
	public function meta_key() {
		return $this->meta_key;
	}

	/**
	 * @param $classes
	 *
	 * @return mixed
	 */
	public function add_body_classes( $classes ) {

		if ( $this->slug() === get_post_type() ) {
			$classes[] = 'jet-menu-post-type';
		}

		return $classes;
	}

	/**
	 * Add elementor support for mega menu items.
	 */
	public function set_option_support( $value ) {

		if ( empty( $value ) ) {
			$value = array();
		}

		return array_merge( $value, array( $this->slug() ) );
	}

	/**
	 * Register post type
	 *
	 * @return void
	 */
	public function register_post_type() {

		$labels = array(
			'name'          => esc_html__( 'Mega Menu Items', 'jet-menu' ),
			'singular_name' => esc_html__( 'Mega Menu Item', 'jet-menu' ),
			'add_new'       => esc_html__( 'Add New Mega Menu Item', 'jet-menu' ),
			'add_new_item'  => esc_html__( 'Add New Mega Menu Item', 'jet-menu' ),
			'edit_item'     => esc_html__( 'Edit Mega Menu Item', 'jet-menu' ),
			'menu_name'     => esc_html__( 'Mega Menu Items', 'jet-menu' ),
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'description'         => 'description',
			'taxonomies'          => [],
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => null,
			'menu_icon'           => null,
			'show_in_nav_menus'   => false,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => true,
			'capability_type'     => 'post',
			'supports'            => array( 'title', 'editor', 'custom-fields' ),
		);

		register_post_type( $this->slug(), $args );

	}

	/**
	 * Returns related mega menu post
	 *
	 * @param  int $menu_id Menu ID
	 * @return [type]          [description]
	 */
	public function get_related_menu_post( $menu_id ) {
		return get_post_meta( $menu_id, $this->meta_key(), true );
	}

	/**
	 * Set blank template for editor
	 */
	public function set_post_type_template( $template ) {

		$found = false;

		if ( is_singular( $this->slug() ) ) {
			$found    = true;
			$template = jet_menu()->plugin_path( 'templates/blank.php' );
		}

		if ( $found ) {
			do_action( 'jet-menu/template-include/found' );
		}

		return $template;

	}

	/**
	 * Edit redirect
	 *
	 * @return void
	 */
	public function edit_mega_template_redirect() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( empty( $_REQUEST['jet-open-editor'] ) || empty( $_REQUEST['item'] ) || empty( $_REQUEST['menu'] ) || empty( $_REQUEST['content'] ) ) {
			return;
		}

		$menu_id      = intval( $_REQUEST['menu'] );
		$menu_item_id = intval( $_REQUEST['item'] );
		$content_type = $_REQUEST['content'];

		$mega_elementor_template_id = get_post_meta( $menu_item_id, 'jet-menu-item', true );
		$mega_block_editor_template_id = get_post_meta( $menu_item_id, 'jet-menu-item-block-editor', true );

		if ( ! $mega_elementor_template_id && 'elementor' === $content_type ) {
			$mega_elementor_template_id = wp_insert_post( [
				'post_title'  => 'elementor-mega-item-' . $menu_item_id,
				'post_status' => 'publish',
				'post_type'   => $this->slug(),
			] );

			update_post_meta( $menu_item_id, 'jet-menu-item', $mega_elementor_template_id );
		}

		if ( ! $mega_block_editor_template_id && 'default' === $content_type ) {
			$mega_block_editor_template_id = wp_insert_post( [
				'post_title'  => 'block-editor-mega-item-' . $menu_item_id,
				'post_status' => 'publish',
				'post_type'   => $this->slug(),
			] );

			update_post_meta( $menu_item_id, 'jet-menu-item-block-editor', $mega_block_editor_template_id );
		}

		update_post_meta( $menu_item_id, '_content_type', $content_type );

		switch ( $content_type ) {
			case 'default':
				$edit_link = get_edit_post_link( $mega_block_editor_template_id, '' );
				break;
			case 'elementor':

				if ( defined( 'ELEMENTOR_VERSION' ) ) {
					$edit_link = add_query_arg(
						[
							'post'        => $mega_elementor_template_id,
							'action'      => 'elementor',
							'context'     => 'jet-menu',
							'parent_menu' => $menu_id,
						],
						admin_url( 'post.php' )
					);
				} else {
					$edit_link = false;
				}

				break;
		}

		wp_redirect( $edit_link );

		die();

	}
}
