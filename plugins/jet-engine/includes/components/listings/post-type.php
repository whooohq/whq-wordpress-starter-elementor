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

if ( ! class_exists( 'Jet_Engine_Listings_Post_Type' ) ) {

	/**
	 * Define Jet_Engine_Listings_Post_Type class
	 */
	class Jet_Engine_Listings_Post_Type {

		/**
		 * Post type slug.
		 *
		 * @var string
		 */
		public $post_type = 'jet-engine';


		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'register_post_type' ) );

			if ( ! empty( $_GET['elementor-preview'] ) ) {
				add_action( 'template_include', array( $this, 'set_editor_template' ), 9999 );
			}

			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'add_templates_page' ), 20 );
				add_action( 'add_meta_boxes_' . $this->slug(), array( $this, 'disable_metaboxes' ), 9999 );
				add_action( 'admin_action_jet_create_new_listing', array( $this, 'create_template' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'listings_page_assets' ) );

				add_filter( 'post_row_actions', array( $this, 'remove_view_action' ), 10, 2 );
				add_action( 'current_screen', array( $this, 'no_elementor_notice' ) );
				$this->register_admin_columns();
			}

		}

		/**
		 * Register lisitng admin columns
		 *
		 * @return [type] [description]
		 */
		public function register_admin_columns() {

			if ( ! class_exists( 'Jet_Engine_CPT_Admin_Columns' ) ) {
				require_once jet_engine()->plugin_path( 'includes/components/post-types/admin-columns.php' );
			}

			new Jet_Engine_CPT_Admin_Columns( $this->post_type, array(
				array(
					'type'     => 'custom_callback',
					'title'    => __( 'Source', 'jet-engine' ),
					'callback' => array( $this, 'get_source' ),
					'position' => 2,
				),
				array(
					'type'     => 'custom_callback',
					'title'    => __( 'For post type/taxonomy', 'jet-engine' ),
					'callback' => array( $this, 'get_type' ),
					'position' => 3,
				),
			) );

		}

		/**
		 * Returns listing source
		 *
		 * @param  [type] $post_id [description]
		 * @return [type]          [description]
		 */
		public function get_source( $post_id ) {

			$settings = get_post_meta( $post_id, '_elementor_page_settings', true );

			if ( empty( $settings ) || empty( $settings['listing_source'] ) ) {
				return 'Posts';
			}

			return ucfirst( $settings['listing_source'] );

		}

		/**
		 * Returns listing content type
		 *
		 * @param  [type] $post_id [description]
		 * @return [type]          [description]
		 */
		public function get_type( $post_id ) {

			$settings = get_post_meta( $post_id, '_elementor_page_settings', true );

			if ( empty( $settings ) ) {
				return 'Posts';
			}

			$source = ! empty( $settings['listing_source'] ) ? $settings['listing_source'] : 'posts';
			$result = '--';

			switch ( $source ) {

				case 'posts':
					$post_type = ! empty( $settings['listing_post_type'] ) ? $settings['listing_post_type'] : 'post';
					$object    = get_post_type_object( $post_type );

					if ( $object ) {
						$result = $object->labels->name;
					}
					break;

				case 'terms':
					$tax = ! empty( $settings['listing_tax'] ) ? $settings['listing_tax'] : false;

					if ( $tax ) {
						$object = get_taxonomy( $tax );
						if ( $object ) {
							$result = $object->labels->name;
						}
					}

					break;

				default:
					$result = apply_filters( 'jet-engine/templates/admin-columns/type/' . $source, $result, $settings );
					break;
			}

			return $result;

		}

		/**
		 * Add notice on listings page if Elementor not installed
		 *
		 * @return void
		 */
		public function no_elementor_notice() {

			if ( jet_engine()->has_elementor() ) {
				return;
			}

			$screen = get_current_screen();

			if ( $screen->id !== 'edit-' . $this->slug() ) {
				return;
			}

		}

		/**
		 * Actions posts
		 *
		 * @param  [type] $actions [description]
		 * @param  [type] $post    [description]
		 * @return [type]          [description]
		 */
		public function remove_view_action( $actions, $post ) {

			if ( $this->slug() === $post->post_type ) {
				unset( $actions['view'] );
			}

			return $actions;

		}

		public function listing_form_assets( $force_print_templates = false, $vars = array() ) {
			wp_enqueue_script(
				'jet-listings-form',
				jet_engine()->plugin_url( 'assets/js/admin/listings-popup.js' ),
				array( 'jquery' ),
				jet_engine()->get_version(),
				true
			);

			wp_localize_script( 'jet-listings-form', 'JetListingsSettings', array_merge( array(
				'hasElementor' => jet_engine()->has_elementor(),
				'exclude'      => array(),
				'defaults'     => array(),
			), $vars ) );

			wp_enqueue_style(
				'jet-listings-form',
				jet_engine()->plugin_url( 'assets/css/admin/listings.css' ),
				array(),
				jet_engine()->get_version()
			);

			if ( $force_print_templates ) {
				$this->print_listings_popup();
			} else {
				add_action( 'admin_footer', array( $this, 'print_listings_popup' ), 999 );
			}
			
		}

		public function listings_page_assets() {

			$screen = get_current_screen();

			if ( $screen->id !== 'edit-' . $this->slug() ) {
				return;
			}

			$this->listing_form_assets();

			jet_engine()->get_video_help_popup( array(
				'popup_title' => __( 'What is Listing Grid?', 'jet-engine' ),
				'embed' => 'https://www.youtube.com/embed/JxvtMzwHGIw',
			) )->wp_page_popup();

		}

		/**
		 * Returns available listing sources list
		 *
		 * @return [type] [description]
		 */
		public function get_listing_item_sources() {
			return apply_filters( 'jet-engine/templates/listing-sources', array(
				'posts'    => __( 'Posts', 'jet-engine' ),
				'query'    => __( 'Query Builder', 'jet-engine' ),
				'terms'    => __( 'Terms', 'jet-engine' ),
				'users'    => __( 'Users', 'jet-engine' ),
				'repeater' => __( 'Repeater Field', 'jet-engine' ),
			) );
		}

		/**
		 * Print template type form HTML
		 *
		 * @return void
		 */
		public function print_listings_popup() {

			$action = add_query_arg(
				array(
					'action' => 'jet_create_new_listing',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

			$sources = $this->get_listing_item_sources();

			$views = apply_filters( 'jet-engine/templates/listing-views', array(
				'blocks' => __( 'Blocks (Gutenberg)', 'jet-engine' ),
			) );

			include jet_engine()->get_template( 'admin/listings-popup.php' );
		}

		public function is_ajax_request() {
			return $is_ajax_request = ! empty( $_REQUEST['_is_ajax_form'] ) ? filter_var( $_REQUEST['_is_ajax_form'], FILTER_VALIDATE_BOOLEAN ) : false;
		}

		public function send_request_error( $message = '' ) {

			if ( $this->is_ajax_request() ) {
				wp_send_json_error( $message );
			} else {
				wp_die( $message, esc_html__( 'Error', 'jet-engine' ) );
			}

		}

		/**
		 * Create new template
		 *
		 * @return [type] [description]
		 */
		public function create_template() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				$this->send_request_error( esc_html__( 'You don\'t have permissions to do this', 'jet-engine' ) );
			}

			$post_data = array(
				'post_type'   => $this->slug(),
				'post_status' => 'publish',
				'meta_input'  => array(),
			);

			$title = isset( $_REQUEST['template_name'] ) ? esc_attr( $_REQUEST['template_name'] ) : '';

			if ( $title ) {
				$post_data['post_title'] = $title;
			}

			$source     = ! empty( $_REQUEST['listing_source'] ) ? esc_attr( $_REQUEST['listing_source'] ) : 'posts';
			$post_type  = ! empty( $_REQUEST['listing_post_type'] ) ? esc_attr( $_REQUEST['listing_post_type'] ) : '';
			$tax        = ! empty( $_REQUEST['listing_tax'] ) ? esc_attr( $_REQUEST['listing_tax'] ) : '';
			$rep_source = ! empty( $_REQUEST['repeater_source'] ) ? esc_attr( $_REQUEST['repeater_source'] ) : '';
			$rep_field  = ! empty( $_REQUEST['repeater_field'] ) ? esc_attr( $_REQUEST['repeater_field'] ) : '';
			$rep_option = ! empty( $_REQUEST['repeater_option'] ) ? esc_attr( $_REQUEST['repeater_option'] ) : '';
			$view_type  = ! empty( $_REQUEST['listing_view_type'] ) ? $_REQUEST['listing_view_type'] : 'elementor';

			$listing = array(
				'source'    => $source,
				'post_type' => $post_type,
				'tax'       => $tax,
			);

			$post_data['meta_input']['_listing_data'] = $listing;
			$post_data['meta_input']['_listing_type'] = $view_type;
			$post_data['meta_input']['_elementor_page_settings']['listing_source'] = $source;
			$post_data['meta_input']['_elementor_page_settings']['listing_post_type'] = $post_type;
			$post_data['meta_input']['_elementor_page_settings']['listing_tax'] = $tax;
			$post_data['meta_input']['_elementor_page_settings']['repeater_source'] = $rep_source;
			$post_data['meta_input']['_elementor_page_settings']['repeater_field'] = $rep_field;
			$post_data['meta_input']['_elementor_page_settings']['repeater_option'] = $rep_option;

			if ( 'elementor' === $view_type && $this->is_ajax_request() ) {
				$post_data['meta_input']['_elementor_data'] = '[{"id":"d75c8e8","elType":"section","settings":{"jedv_conditions":[{"_id":"b441260"}]},"elements":[{"id":"31b3d2a","elType":"column","settings":{"_column_size":100,"_inline_size":null,"jedv_conditions":[{"_id":"8e60841"}]},"elements":[{"id":"2c37cde","elType":"widget","settings":{"dynamic_excerpt_more":"...","date_format":"F j, Y","num_dec_point":".","num_thousands_sep":",","multiselect_delimiter":", ","dynamic_field_format":"%s","jedv_conditions":[{"_id":"a47f557"}]},"elements":[],"widgetType":"jet-listing-dynamic-field"}],"isInner":false}],"isInner":false}]';
			}

			$post_data   = apply_filters( 'jet-engine/templates/create/data', $post_data );
			$template_id = wp_insert_post( $post_data );

			if ( ! $template_id ) {
				$this->send_request_error( esc_html__( 'Can\'t create template. Please try again', 'jet-engine' ) );
			}

			do_action( 'jet-engine/templates/created', $template_id, $post_data );

			$redirect  = false;

			switch ( $view_type ) {
				case 'elementor':
					$redirect = jet_engine()->elementor_views->get_redirect_url( $template_id );
					break;

				case 'blocks':
					$redirect = jet_engine()->blocks_views->get_redirect_url( $template_id );
					break;
			}

			if ( ! $redirect ) {
				$this->send_request_error( __( 'Listing view instance is not found', 'jet-engine' ) );
			}

			

			if ( $this->is_ajax_request() ) {
				wp_send_json_success( array(
					'id'    => $template_id,
					'title' => $title,
				) );
			} else {
				wp_redirect( $redirect );
				die();
			}

		}

		/**
		 * Templates post type slug
		 *
		 * @return string
		 */
		public function slug() {
			return $this->post_type;
		}

		/**
		 * Disable metaboxes from Jet Templates
		 *
		 * @return void
		 */
		public function disable_metaboxes() {
			global $wp_meta_boxes;
			unset( $wp_meta_boxes[ $this->slug() ]['side']['core']['pageparentdiv'] );
		}

		/**
		 * Register templates post type
		 *
		 * @return void
		 */
		public function register_post_type() {

			$args = array(
				'labels' => array(
					'name'               => esc_html__( 'Listing Items', 'jet-engine' ),
					'singular_name'      => esc_html__( 'Listing Item', 'jet-engine' ),
					'add_new'            => esc_html__( 'Add New', 'jet-engine' ),
					'add_new_item'       => esc_html__( 'Add New Item', 'jet-engine' ),
					'edit_item'          => esc_html__( 'Edit Item', 'jet-engine' ),
					'new_item'           => esc_html__( 'Add New Item', 'jet-engine' ),
					'view_item'          => esc_html__( 'View Item', 'jet-engine' ),
					'search_items'       => esc_html__( 'Search Item', 'jet-engine' ),
					'not_found'          => esc_html__( 'No Templates Found', 'jet-engine' ),
					'not_found_in_trash' => esc_html__( 'No Templates Found In Trash', 'jet-engine' ),
					'menu_name'          => esc_html__( 'My Library', 'jet-engine' ),
				),
				'public'              => false,
				'hierarchical'        => false,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'show_in_rest'        => true,
				'can_export'          => true,
				'exclude_from_search' => true,
				'capability_type'     => 'post',
				'rewrite'             => false,
				'supports'            => array( 'title', 'editor', 'thumbnail', 'author', 'elementor', 'custom-fields' ),
			);

			if ( current_user_can( 'edit_posts' ) ) {
				$args['public'] = true;
			}

			register_post_type(
				$this->slug(),
				apply_filters( 'jet-engine/templates/post-type/args', $args )
			);

		}

		/**
		 * Menu page
		 */
		public function add_templates_page() {

			add_submenu_page(
				jet_engine()->admin_page,
				esc_html__( 'Listings', 'jet-engine' ),
				esc_html__( 'Listings', 'jet-engine' ),
				'edit_pages',
				'edit.php?post_type=' . $this->slug()
			);

		}

		/**
		 * Editor templates.
		 *
		 * @param  string $template Current template name.
		 * @return string
		 */
		public function set_editor_template( $template ) {

			$found = false;

			if ( is_singular( $this->slug() ) ) {
				$found    = true;
				$template = jet_engine()->plugin_path( 'templates/blank.php' );
			}

			if ( $found ) {
				do_action( 'jet-engine/post-type/editor-template/found' );
			}

			return $template;

		}

	}

}
