<?php
/**
 * JetWooBuilder post type Class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Woo_Builder_Post_Type' ) ) {

	/**
	 * Define Jet_Woo_Builder_Post_Type class
	 */
	class Jet_Woo_Builder_Post_Type {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		protected $post_type = 'jet-woo-builder';
		protected $meta_key  = 'jet-woo-builder-item';

		/**
		 * Constructor for the class
		 */
		public function init() {

			$this->register_post_type();
			$this->init_meta();

			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'add_templates_page' ), 22 );
			}

			add_action( 'manage_' . $this->post_type . '_posts_columns', array( $this, 'admin_columns_headers' ) );
			add_action( 'manage_' . $this->post_type . '_posts_custom_column', array( $this, 'admin_columns_content' ), 10, 2 );

			add_filter( 'option_elementor_cpt_support', array( $this, 'set_option_support' ) );
			add_filter( 'default_option_elementor_cpt_support', array( $this, 'set_option_support' ) );

			add_filter( 'body_class', array( $this, 'set_body_class' ) );

			add_action( 'init', array( $this, 'fix_documents_types' ), 99 );

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_templates_popups' ] );
			add_action( 'admin_action_jet_woo_new_template', array( $this, 'create_template' ) );

			add_filter( 'post_row_actions', array( $this, 'remove_view_action' ), 10, 2 );

			add_filter( 'get_sample_permalink_html', array( $this, 'remove_permalink_action' ), 10, 5 );

		}

		/**
		 * Remove permalink html
		 *
		 * @param $return
		 * @param $post_id
		 * @param $new_title
		 * @param $new_slug
		 * @param $post
		 *
		 * @return string
		 */
		public function remove_permalink_action( $return, $post_id, $new_title, $new_slug, $post ) {

			if ( $this->post_type === $post->post_type ) {
				return '';
			}

			return $return;
		}

		/**
		 * Actions posts
		 *
		 * @param $actions
		 * @param $post
		 *
		 * @return mixed
		 */
		public function remove_view_action( $actions, $post ) {

			if ( $this->post_type === $post->post_type ) {
				unset( $actions['view'] );
			}

			return $actions;

		}

		/**
		 * Create new template
		 *
		 * @return void
		 */
		public function create_template() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die(
					esc_html__( 'You don\'t have permissions to do this', 'jet-woo-builder' ),
					esc_html__( 'Error', 'jet-woo-builder' )
				);
			}

			$doc_types     = jet_woo_builder()->documents->get_document_types();
			$default_type  = $doc_types['single']['slug'];
			$type          = isset( $_REQUEST['template_type'] ) ? $_REQUEST['template_type'] : $default_type;
			$name          = isset( $_REQUEST['template_name'] ) ? $_REQUEST['template_name'] : '';
			$documents     = Elementor\Plugin::instance()->documents;
			$doc_type      = $documents->get_document_type( $type );
			$template_data = '';
			$templates     = array();


			if ( $type === $doc_types['single']['slug'] ) {
				$template  = isset( $_REQUEST['template_single'] ) ? $_REQUEST['template_single'] : '';
				$templates = $this->predesigned_single_templates();
			} else if ( $type === $doc_types['archive']['slug'] ) {
				$template  = isset( $_REQUEST['template_archive'] ) ? $_REQUEST['template_archive'] : '';
				$templates = $this->predesigned_archive_templates();
			} else if ( $type === $doc_types['category']['slug'] ) {
				$template  = isset( $_REQUEST['template_category'] ) ? $_REQUEST['template_category'] : '';
				$templates = $this->predesigned_category_templates();
			} else if ( $type === $doc_types['shop']['slug'] ) {
				$template  = isset( $_REQUEST['template_shop'] ) ? $_REQUEST['template_shop'] : '';
				$templates = $this->predesigned_shop_templates();
			} else {
				$template = '';
			}

			if ( $template ) {
				if ( ! isset( $templates[ $template ] ) ) {
					wp_die(
						esc_html__( 'This template not registered', 'jet-woo-builder' ),
						esc_html__( 'Error', 'jet-woo-builder' )
					);
				}

				$data    = $templates[ $template ];
				$content = $data['content'];

				ob_start();

				include $content;

				$template_data = ob_get_clean();

			}

			$meta_input = array(
				'_elementor_edit_mode'   => 'builder',
				$doc_type::TYPE_META_KEY => esc_attr( $type ),
			);

			if ( ! empty( $template_data ) ) {
				$meta_input['_elementor_data'] = wp_slash( $template_data );
			}

			$post_data = array(
				'post_type'  => $this->slug(),
				'meta_input' => $meta_input,
			);

			if ( $name ) {
				$post_data['post_title'] = esc_attr( $name );
			}

			$template_id = wp_insert_post( $post_data );

			if ( ! $template_id ) {
				wp_die(
					esc_html__( 'Can\'t create template. Please try again', 'jet-woo-builder' ),
					esc_html__( 'Error', 'jet-woo-builder' )
				);
			}

			if ( version_compare( ELEMENTOR_VERSION, '2.6.0', '<' ) ) {
				$redirect = Elementor\Utils::get_edit_link( $template_id );
			} else {
				$redirect = Elementor\Plugin::$instance->documents->get( $template_id )->get_edit_url();
			}

			wp_redirect( $redirect );

			die();

		}

		/**
		 * Enqueue templates popups assets.
		 *
		 * @param $hook
		 *
		 * @return void
		 */
		public function enqueue_templates_popups( $hook ) {

			if ( 'edit.php' !== $hook ) {
				return;
			}

			if ( ! isset( $_GET['post_type'] ) || $this->slug() !== $_GET['post_type'] ) {
				return;
			}

			wp_enqueue_style(
				'jet-woo-builder-templates-popups',
				jet_woo_builder()->plugin_url( 'assets/css/admin/templates-popups.css' )
			);

			wp_enqueue_script(
				'jet-woo-builder-templates-popups',
				jet_woo_builder()->plugin_url( 'assets/js/admin/templates-popups.js' ),
				[ 'jquery', 'jet-woo-builder-tippy' ],
				jet_woo_builder()->get_version(),
				true
			);

			add_action( 'admin_footer', [ $this, 'template_popup' ] );

		}

		/**
		 * Returns predesigned templates for single product
		 *
		 * @return array
		 */
		public function predesigned_single_templates() {

			$base_url = jet_woo_builder()->plugin_url( 'includes/templates/single/' );
			$base_dir = jet_woo_builder()->plugin_path( 'includes/templates/single/' );

			return apply_filters( 'jet-woo-builder/predesigned-single-templates', array(
				'layout-1' => array(
					'content' => $base_dir . 'layout-1/template.json',
					'thumb'   => $base_url . 'layout-1/thumbnail.png',
				),
				'layout-2' => array(
					'content' => $base_dir . 'layout-2/template.json',
					'thumb'   => $base_url . 'layout-2/thumbnail.png',
				),
				'layout-3' => array(
					'content' => $base_dir . 'layout-3/template.json',
					'thumb'   => $base_url . 'layout-3/thumbnail.png',
				),
				'layout-4' => array(
					'content' => $base_dir . 'layout-4/template.json',
					'thumb'   => $base_url . 'layout-4/thumbnail.png',
				),
				'layout-5' => array(
					'content' => $base_dir . 'layout-5/template.json',
					'thumb'   => $base_url . 'layout-5/thumbnail.png',
				),
				'layout-6' => array(
					'content' => $base_dir . 'layout-6/template.json',
					'thumb'   => $base_url . 'layout-6/thumbnail.png',
				),
				'layout-7' => array(
					'content' => $base_dir . 'layout-7/template.json',
					'thumb'   => $base_url . 'layout-7/thumbnail.png',
				),
				'layout-8' => array(
					'content' => $base_dir . 'layout-8/template.json',
					'thumb'   => $base_url . 'layout-8/thumbnail.png',
				),
			) );

		}

		/**
		 * Returns predesigned templates for archive product
		 *
		 * @return array
		 */
		public function predesigned_archive_templates() {

			$base_url = jet_woo_builder()->plugin_url( 'includes/templates/archive/' );
			$base_dir = jet_woo_builder()->plugin_path( 'includes/templates/archive/' );

			return apply_filters( 'jet-woo-builder/predesigned-archive-templates', array(
				'layout-1' => array(
					'content' => $base_dir . 'layout-1/template.json',
					'thumb'   => $base_url . 'layout-1/thumbnail.png',
				),
				'layout-2' => array(
					'content' => $base_dir . 'layout-2/template.json',
					'thumb'   => $base_url . 'layout-2/thumbnail.png',
				),
				'layout-3' => array(
					'content' => $base_dir . 'layout-3/template.json',
					'thumb'   => $base_url . 'layout-3/thumbnail.png',
				),
				'layout-4' => array(
					'content' => $base_dir . 'layout-4/template.json',
					'thumb'   => $base_url . 'layout-4/thumbnail.png',
				),
			) );

		}

		/**
		 * Returns predesigned templates for category product
		 *
		 * @return array
		 */
		public function predesigned_category_templates() {

			$base_url = jet_woo_builder()->plugin_url( 'includes/templates/category/' );
			$base_dir = jet_woo_builder()->plugin_path( 'includes/templates/category/' );

			return apply_filters( 'jet-woo-builder/predesigned-category-templates', array(
				'layout-1' => array(
					'content' => $base_dir . 'layout-1/template.json',
					'thumb'   => $base_url . 'layout-1/thumbnail.png',
				),
				'layout-2' => array(
					'content' => $base_dir . 'layout-2/template.json',
					'thumb'   => $base_url . 'layout-2/thumbnail.png',
				),
				'layout-3' => array(
					'content' => $base_dir . 'layout-3/template.json',
					'thumb'   => $base_url . 'layout-3/thumbnail.png',
				),
				'layout-4' => array(
					'content' => $base_dir . 'layout-4/template.json',
					'thumb'   => $base_url . 'layout-4/thumbnail.png',
				),
			) );

		}

		/**
		 * Returns predesigned templates for shop product
		 *
		 * @return array
		 */
		public function predesigned_shop_templates() {

			$base_url = jet_woo_builder()->plugin_url( 'includes/templates/shop/' );
			$base_dir = jet_woo_builder()->plugin_path( 'includes/templates/shop/' );

			return apply_filters( 'jet-woo-builder/predesigned-shop-templates', array(
				'layout-1' => array(
					'content' => $base_dir . 'layout-1/template.json',
					'thumb'   => $base_url . 'layout-1/thumbnail.png',
				),
				'layout-2' => array(
					'content' => $base_dir . 'layout-2/template.json',
					'thumb'   => $base_url . 'layout-2/thumbnail.png',
				),
				'layout-3' => array(
					'content' => $base_dir . 'layout-3/template.json',
					'thumb'   => $base_url . 'layout-3/thumbnail.png',
				),
				'layout-4' => array(
					'content' => $base_dir . 'layout-4/template.json',
					'thumb'   => $base_url . 'layout-4/thumbnail.png',
				),
			) );

		}

		/**
		 * Template popup content.
		 *
		 * @return false|void
		 */
		public function template_popup() {

			global $current_screen;

			if ( 'edit-jet-woo-builder' !== $current_screen->id ) {
				return false;
			}

			$create_action = add_query_arg(
				[
					'action' => 'jet_woo_new_template',
				],
				esc_url( admin_url( 'admin.php' ) )
			);

			$import_action = add_query_arg(
				[
					'action' => 'jet_woo_builder_import_template',
				],
				esc_url( admin_url( 'admin.php' ) )
			);

			include jet_woo_builder()->get_template( 'admin/popups/create-template.php' );
			include jet_woo_builder()->get_template( 'admin/popups/import-template.php' );

		}

		/**
		 * Maybe fix document types for JetWooBuilder templates.
		 *
		 * @return false|void
		 */
		public function fix_documents_types() {

			if ( ! isset( $_GET['fix_jet_woo_templates'] ) ) {
				return;
			}

			$args = array(
				'post_type'      => $this->slug(),
				'post_status'    => array( 'publish', 'pending', 'draft', 'future' ),
				'posts_per_page' => -1,
			);

			$wp_query  = new WP_Query( $args );
			$documents = Elementor\Plugin::instance()->documents;
			$doc_type  = $documents->get_document_type( $this->slug() );

			if ( ! $wp_query->have_posts() ) {
				return false;
			}

			foreach ( $wp_query->posts as $post ) {
				update_post_meta( $post->ID, $doc_type::TYPE_META_KEY, $this->slug() );
			}

		}

		/**
		 * Add JetWooBuilder templates class to body on template pages.
		 *
		 * @param array $classes Default classes list.
		 *
		 * @return array
		 */
		public function set_body_class( $classes ) {

			$cart_template      = jet_woo_builder_shop_settings()->get( 'custom_cart_page' );
			$checkout_template  = jet_woo_builder_shop_settings()->get( 'custom_checkout_page' );
			$thankyou_template  = jet_woo_builder_shop_settings()->get( 'custom_thankyou_page' );
			$myaccount_template = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page' );

			if ( 'yes' === $cart_template && is_cart() || 'yes' === $checkout_template && is_checkout() || 'yes' === $thankyou_template && is_order_received_page() || 'yes' === $myaccount_template && is_account_page() ) {
				$classes[] = 'jet-woo-builder-elementor-template';
			}

			return $classes;

		}

		/**
		 * JetWooBuilder Templates page.
		 */
		public function add_templates_page() {
			add_submenu_page(
				'jet-dashboard',
				esc_html__( 'Woo Page Builder', 'jet-woo-builder' ),
				esc_html__( 'Woo Page Builder', 'jet-woo-builder' ),
				'edit_pages',
				'edit.php?post_type=' . $this->slug()
			);
		}

		/**
		 * Set required post columns
		 *
		 * @param array $posts_columns
		 *
		 * @return array
		 */
		public function admin_columns_headers( $posts_columns ) {

			$offset        = 2;
			$posts_columns = array_slice( $posts_columns, 0, $offset, true ) +
				[ 'type' => esc_html__( 'Type', 'jet-woo-builder' ), ] +
				array_slice( $posts_columns, $offset, null, true );

			$offset        = 3;
			$posts_columns = array_slice( $posts_columns, 0, $offset, true ) +
				[ 'condition' => esc_html__( 'Active Condition', 'jet-woo-builder' ), ] +
				array_slice( $posts_columns, $offset, null, true );

			return $posts_columns;

		}

		/**
		 * Set required post columns content
		 *
		 * @param string $column_name
		 * @param number $post_id
		 *
		 * @return mixed
		 */
		public function admin_columns_content( $column_name, $post_id ) {

			$post_id = absint( $post_id );

			if ( ! $post_id || ! get_post( $post_id ) ) {
				return false;
			}

			$template_types = jet_woo_builder()->documents->get_document_types();
			$doc_type_slug  = get_post_meta( $post_id, '_elementor_template_type', true );

			foreach ( $template_types as $template => $template_type ) {
				if ( ! isset( $template ) ) {
					continue;
				}

				if ( $doc_type_slug === $template_type['slug'] ) {
					switch ( $column_name ) {
						case 'type':
							printf( '<div class="jet-woo-builder-template-type">%s</div>', $template_type['name'] );
							break;
						case 'condition';
							$active_templates = [];
							$template_options = get_option( 'jet_woo_builder' );

							if ( $template_options || ! empty( $template_options ) ) {
								foreach ( $template_options as $option => $value ) {
									if ( $post_id === absint( $value ) ) {
										$option = str_replace( '_', ' ', $option );
										$option = ucwords( $option );

										array_push( $active_templates, $option );
									}
								}
							}

							printf( '<div class="jet-woo-builder-active-conditions">%1$s</div>', implode( ', ', $active_templates ) );
							break;
					}
				}
			}

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
		 * Returns JetWooBuilder meta key
		 *
		 * @return string
		 */
		public function meta_key() {
			return $this->meta_key;
		}

		/**
		 * Add Elementor support for JetWooBuilder items.
		 *
		 * @param $value
		 *
		 * @return array
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
				'name'          => esc_html__( 'JetWooBuilder Templates', 'jet-woo-builder' ),
				'singular_name' => esc_html__( 'JetWooBuilder Template', 'jet-woo-builder' ),
				'add_new'       => esc_html__( 'Create New Template', 'jet-woo-builder' ),
				'add_new_item'  => esc_html__( 'Create New Template', 'jet-woo-builder' ),
				'edit_item'     => esc_html__( 'Edit Template', 'jet-woo-builder' ),
				'menu_name'     => esc_html__( 'JetWooBuilder Templates', 'jet-woo-builder' ),
			);

			$args = array(
				'labels'              => $labels,
				'hierarchical'        => false,
				'description'         => 'description',
				'taxonomies'          => array(),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_admin_bar'   => true,
				'menu_position'       => null,
				'menu_icon'           => null,
				'show_in_nav_menus'   => false,
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'post',
				'supports'            => array( 'title' ),
			);

			register_post_type( $this->slug(), $args );

		}

		/**
		 * Initialize template metabox
		 *
		 * @return void
		 */
		public function init_meta() {
			new Cherry_X_Post_Meta(
				array(
					'id'            => 'template-settings',
					'title'         => esc_html__( 'Template Settings', 'jet-woo-builder' ),
					'page'          => array( $this->slug() ),
					'context'       => 'normal',
					'priority'      => 'high',
					'callback_args' => false,
					'builder_cb'    => array( $this, 'get_builder' ),
					'fields'        => array(
						'_sample_product' => array(
							'type'              => 'select',
							'element'           => 'control',
							'options'           => false,
							'options_callback'  => array( $this, 'get_products' ),
							'label'             => esc_html__( 'Sample Product for Editing (if not selected - will be used latest added)', 'jet-woo-builder' ),
							'sanitize_callback' => 'esc_attr',
						),
					),
				)
			);
		}

		/**
		 * Return products list
		 *
		 * @return array
		 */
		public function get_products() {

			$products = get_posts(
				array(
					'post_type'      => 'product',
					'post_status'    => array( 'publish', 'pending', 'draft', 'future' ),
					'posts_per_page' => 100,
				)
			);

			$default = array(
				'' => esc_html__( 'Select Product...', 'jet-woo-builder' ),
			);

			if ( empty( $products ) ) {
				return $default;
			}

			$products = wp_list_pluck( $products, 'post_title', 'ID' );

			return $default + $products;

		}

		/**
		 * Return UI builder instance
		 *
		 * @return CX_Interface_Builder
		 */
		public function get_builder() {

			$builder_data = jet_woo_builder()->module_loader->get_included_module_data( 'cherry-x-interface-builder.php' );

			return new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				)
			);

		}

		/**
		 * Get templates query arguments.
		 *
		 * @param string $type
		 *
		 * @return array
		 */
		public function get_templates_query_args( $type = 'all' ) {

			$args = array(
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'post_type'      => $this->slug(),
			);

			if ( 'all' !== $type ) {

				$doc_types    = jet_woo_builder()->documents->get_document_types();
				$default_type = $doc_types['single']['slug'];
				$type         = isset( $doc_types[ $type ] ) ? $doc_types[ $type ]['slug'] : $default_type;
				$documents    = Elementor\Plugin::instance()->documents;
				$doc_type     = $documents->get_document_type( $type );

				$args['meta_query'] = array(
					array(
						'key'   => $doc_type::TYPE_META_KEY,
						'value' => $type,
					),
				);
			}

			return $args;

		}

		/**
		 * Return Templates list from options
		 *
		 * @param string $type
		 *
		 * @return array
		 */
		public function get_templates_list( $type = 'all' ) {

			$args = $this->get_templates_query_args( $type );

			$templates = get_posts( $args );

			return $templates;

		}

		/**
		 * Returns templates list for select options
		 *
		 * @param string $type
		 *
		 * @return array
		 */
		public function get_templates_list_for_options( $type = 'all' ) {

			$templates = $this->get_templates_list( $type );

			$default = array(
				'' => esc_html__( 'Select Template...', 'jet-woo-builder' ),
			);

			if ( empty( $templates ) ) {
				return $default;
			}

			return $default + wp_list_pluck( $templates, 'post_title', 'ID' );

		}

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
	}

}

/**
 * Returns instance of Jet_Woo_Builder_Post_Type
 *
 * @return object
 */
function jet_woo_builder_post_type() {
	return Jet_Woo_Builder_Post_Type::get_instance();
}
