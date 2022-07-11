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

if ( ! class_exists( 'Jet_Woo_Builder_Integration_Woocommerce' ) ) {

	/**
	 * Define Jet_Woo_Builder_Integration_Woocommerce class
	 */
	class Jet_Woo_Builder_Integration_Woocommerce {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		// JetWooBuilder Templates
		private $current_template                     = null;
		public  $current_template_archive             = null;
		private $current_template_archive_category    = null;
		private $current_template_shop                = null;
		private $current_template_taxonomy            = null;
		private $current_loop                         = null;
		private $current_category_args                = array();
		private $current_template_cart                = null;
		private $current_template_empty_cart          = null;
		private $current_template_checkout            = null;
		private $current_top_template_checkout        = null;
		private $current_template_thankyou            = null;
		private $current_template_myaccount           = null;
		private $current_template_myaccount_dashboard = null;
		private $current_template_myaccount_orders    = null;
		private $current_template_myaccount_downloads = null;
		private $current_template_myaccount_address   = null;
		private $current_template_myaccount_account   = null;
		private $current_template_form_login          = null;

		// Products loop widget template rewrite status
		public $products_loop_template_rewrite = false;

		/**
		 * Constructor for the class
		 */
		public function init() {

			add_filter( 'wc_get_template_part', [ $this, 'rewrite_templates' ], 10, 3 );

			add_filter( 'wc_get_template', [ $this, 'rewrite_category_templates' ], 10, 3 );
			add_filter( 'wc_get_template', [ $this, 'force_wc_templates' ], 10, 2 );
			add_filter( 'wc_get_template', [ $this, 'rewrite_default_wc_pages_custom_templates' ], 10, 3 );

			add_filter( 'template_include', [ $this, 'set_archive_page_template' ], 9999 );
			add_filter( 'template_include', [ $this, 'set_product_template' ], 9999 );
			add_filter( 'template_include', [ $this, 'set_default_wc_pages_template' ], 9999 );

			if ( 'yes' === jet_woo_builder_shop_settings()->get( 'custom_taxonomy_template' ) ) {
				add_action( 'init', [ $this, 'taxonomy_meta' ], 99 );
			}

			if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {
				add_action( 'init', array( $this, 'register_frontend_wc_hooks' ), 5 );
			}

			add_action( 'init', array( $this, 'product_meta' ), 99 );

			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'init_cart_for_builder' ) );

			add_filter( 'jet-woo-builder/custom-single-template', array( $this, 'force_preview_template' ) );
			add_filter( 'jet-woo-builder/integration/doc-type', array( $this, 'force_preview_doc_type' ) );
			add_filter( 'jet-woo-builder/integration/doc-type', array( $this, 'force_product_doc_type' ) );

			add_filter( 'woocommerce_output_related_products_args', array( $this, 'set_related_products_output_count' ) );
			add_filter( 'woocommerce_upsell_display_args', array( $this, 'set_up_sells_products_output_count' ) );
			add_filter( 'woocommerce_cross_sells_total', array( $this, 'set_cross_sells_products_output_count' ) );
			add_filter( 'woocommerce_product_loop_start', array( $this, 'set_archive_template_custom_columns' ) );

			add_filter( 'woocommerce_post_class', [ $this, 'set_product_archive_template_items_equal_class' ], 10 );
			add_filter( 'product_cat_class', [ $this, 'set_category_archive_template_items_equal_class' ], 10 );

			// Shop Template Hooks
			add_action( 'jet-woo-builder/woocommerce/before-main-content', 'woocommerce_output_content_wrapper', 10 );
			add_action( 'jet-woo-builder/woocommerce/after-main-content', 'woocommerce_output_content_wrapper_end', 10 );
			add_filter( 'jet-woo-builder/render-callback/custom-args', array( $this, 'get_archive_category_args' ) );

			//Products Navigation Hooks
			add_filter( 'previous_posts_link_attributes', array( $this, 'set_previous_product_link_class' ) );
			add_filter( 'next_posts_link_attributes', array( $this, 'set_next_product_link_class' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_single_template_css' ) );

		}

		/**
		 * Include WC frontend hooks.
		 */
		public function register_frontend_wc_hooks() {
			WC()->frontend_includes();
		}

		/**
		 * Initialize cart for elementor page builder.
		 */
		public function init_cart_for_builder() {

			$has_cart = is_a( WC()->cart, 'WC_Cart' );

			if ( ! $has_cart ) {
				$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
				WC()->session  = new $session_class();

				WC()->session->init();

				WC()->cart     = new WC_Cart();
				WC()->customer = new WC_Customer( get_current_user_id(), true );
			}

		}

		/**
		 * Enqueue Single Template Styles
		 */
		public function maybe_enqueue_single_template_css() {

			$current_template = $this->get_custom_single_template();

			if ( ! is_product() ) {
				return;
			}

			if ( ! $current_template ) {
				return;
			}

			if ( class_exists( 'Elementor\Core\Files\CSS\Post' ) ) {
				$css_file = new Elementor\Core\Files\CSS\Post( $current_template );
			} else {
				$css_file = new Elementor\Post_CSS_File( $current_template );
			}

			$css_file->enqueue();

		}

		/**
		 * Initialize template meta box
		 *
		 * @return void
		 */
		public function product_meta() {
			new Cherry_X_Post_Meta(
				array(
					'id'            => 'template-settings',
					'title'         => esc_html__( 'JetWooBuilder Template Settings', 'jet-woo-builder' ),
					'page'          => array( 'product' ),
					'context'       => 'side',
					'priority'      => 'low',
					'callback_args' => false,
					'builder_cb'    => array( jet_woo_builder_post_type(), 'get_builder' ),
					'fields'        => array(
						'_jet_woo_template' => array(
							'type'              => 'select',
							'element'           => 'control',
							'options'           => false,
							'options_callback'  => array( $this, 'get_single_templates' ),
							'label'             => esc_html__( 'Custom Template', 'jet-woo-builder' ),
							'sanitize_callback' => 'esc_attr',
						),
						'_template_type'    => array(
							'type'              => 'select',
							'element'           => 'control',
							'default'           => 'default',
							'options'           => array(
								'default'    => esc_html__( 'Default', 'jet-woo-builder' ),
								'canvas'     => esc_html__( 'Canvas', 'jet-woo-builder' ),
								'full_width' => esc_html__( 'Full Width', 'jet-woo-builder' ),
							),
							'label'             => esc_html__( 'Template Type', 'jet-woo-builder' ),
							'sanitize_callback' => 'esc_attr',
						),
					),
				)
			);
		}

		/**
		 * Initialize, edit and update JetWoo Builder templates meta box.
		 *
		 * @return void
		 */
		public function taxonomy_meta() {

			if ( ! is_admin() ) {
				return;
			}

			$types = apply_filters( 'jet-woo-builder/integration/taxonomy-meta/object-types', [ 'product' ] );

			$args = [
				'object_type' => $types,
				'public'      => true,
			];

			$output     = 'objects';
			$taxonomies = get_taxonomies( $args, $output );

			foreach ( $taxonomies as $taxonomy ) {
				// Add fields in taxonomy create form
				add_action( $taxonomy->name . '_add_form_fields', array( $this, 'taxonomy_add_new_meta_field' ), 10, 1 );
				// Add fields in taxonomy edit form
				add_action( $taxonomy->name . '_edit_form_fields', array( $this, 'taxonomy_edit_meta_field' ), 10, 1 );

				// Process edit form fields
				add_action( 'edited_' . $taxonomy->name, array( $this, 'save_taxonomy_custom_meta' ), 10, 1 );
				// Process create form fields
				add_action( 'create_' . $taxonomy->name, array( $this, 'save_taxonomy_custom_meta' ), 10, 1 );
			}

		}

		/**
		 * Add fields in taxonomy create form
		 *
		 * @return void
		 */
		public function taxonomy_add_new_meta_field() {

			$templates = $this->get_shop_templates(); ?>

			<div class="form-field">
				<label for="jet_woo_builder_template"><strong><?php echo esc_html__( 'Custom Template', 'jet-woo-builder' ); ?></strong></label>
				<select name="jet_woo_builder_template" id="jet_woo_builder_template">
					<?php foreach ( $templates as $template_id => $template_title ) : ?>
						<option value="<?php echo esc_attr( $template_id ); ?>"><?php echo esc_attr( $template_title ); ?></option>
					<?php endforeach; ?>
				</select>
				<p>
					<em><?php echo esc_html__( 'It will be applied on singular layout of this term.', 'jet-woo-builder' ); ?></em>
				</p>
			</div>

			<?php
		}

		/**
		 * Add fields in taxonomy edit form
		 *
		 * @param $term
		 *
		 * @return void
		 */
		public function taxonomy_edit_meta_field( $term ) {

			$term_id                  = $term->term_id;
			$templates                = $this->get_shop_templates();
			$jet_woo_builder_template = get_term_meta( $term_id, 'jet_woo_builder_template', true ); ?>

			<tr class="form-field">
				<th scope="row">
					<label for="jet_woo_builder_template"><?php echo esc_html__( 'Custom Template', 'jet-woo-builder' ); ?></label>
				</th>
				<td>
					<select name="jet_woo_builder_template" id="jet_woo_builder_template">
						<?php foreach ( $templates as $template_id => $template_title ) : ?>
							<option value="<?php echo esc_attr( $template_id ); ?>" <?php selected( $jet_woo_builder_template, $template_id ); ?>><?php echo esc_attr( $template_title ); ?></option>
						<?php endforeach; ?>
					</select>
					<p>
						<em><?php echo esc_html__( 'It will be applied on singular layout of this term.', 'jet-woo-builder' ); ?></em>
					</p>
				</td>
			</tr>

			<?php
		}

		/**
		 * Save extra taxonomy fields callback function
		 *
		 * @param $term_id
		 *
		 * @return void
		 */
		public function save_taxonomy_custom_meta( $term_id ) {

			$jet_woo_builder_template = filter_input( INPUT_POST, 'jet_woo_builder_template' );

			update_term_meta( $term_id, 'jet_woo_builder_template', $jet_woo_builder_template );

		}

		/**
		 * Set previous product link class
		 *
		 * @param $args
		 *
		 * @return string
		 */
		public function set_previous_product_link_class( $args ) {

			$args .= 'class="jet-woo-builder-navigation-prev"';

			return $args;

		}

		/**
		 * Set next product link class
		 *
		 * @param $args
		 *
		 * @return string
		 */
		public function set_next_product_link_class( $args ) {

			$args .= 'class="jet-woo-builder-navigation-next"';

			return $args;

		}

		/**
		 * Returns equal template class if option enable
		 *
		 * @param $classes
		 *
		 * @return mixed
		 */
		public function set_product_archive_template_items_equal_class( $classes ) {

			if ( 'shortcode' === $this->get_current_loop() ) {
				return $classes;
			}

			$settings           = get_post_meta( $this->get_custom_archive_template(), '_elementor_page_settings', true );
			$use_custom_columns = isset( $settings['use_custom_template_columns'] ) ? $settings['use_custom_template_columns'] : '';
			$equal_columns      = '';

			if ( 'yes' === $use_custom_columns ) {
				$equal_columns = isset( $settings['equal_columns_height'] ) ? $settings['equal_columns_height'] : '';
			}

			if ( 'yes' === $equal_columns ) {
				array_push( $classes, 'jet-equal-columns' );
			}

			return $classes;

		}

		/**
		 * Returns equal template class if option enable
		 *
		 * @param $classes
		 *
		 * @return mixed
		 */
		public function set_category_archive_template_items_equal_class( $classes ) {

			if ( 'shortcode' === $this->get_current_loop() ) {
				return $classes;
			}

			$settings           = get_post_meta( $this->get_custom_archive_category_template(), '_elementor_page_settings', true );
			$use_custom_columns = isset( $settings['use_custom_template_category_columns'] ) ? $settings['use_custom_template_category_columns'] : '';
			$equal_columns      = '';

			if ( 'yes' === $use_custom_columns ) {
				$equal_columns = isset( $settings['equal_columns_height'] ) ? $settings['equal_columns_height'] : '';
			}

			if ( 'yes' === $equal_columns ) {
				array_push( $classes, 'jet-equal-columns' );
			}

			return $classes;

		}

		/**
		 * Add custom columns for archive product template
		 *
		 * @param $content
		 *
		 * @return string
		 */
		public function set_archive_template_custom_columns( $content ) {

			if ( 'shortcode' === $this->get_current_loop() ) {
				return $content;
			}

			$settings                      = get_post_meta( $this->get_custom_archive_template(), '_elementor_page_settings', true );
			$settings_category             = get_post_meta( $this->get_custom_archive_category_template(), '_elementor_page_settings', true );
			$use_custom_columns            = isset( $settings['use_custom_template_columns'] ) ? $settings['use_custom_template_columns'] : '';
			$use_custom_categories_columns = isset( $settings_category['use_custom_template_category_columns'] ) ? $settings_category['use_custom_template_category_columns'] : '';
			$classes                       = array( 'products' );
			$classes_cat                   = array( 'products' );
			$content_categories            = '';

			if ( ! $settings && ! $settings_category ) {
				return $content;
			}

			array_push( $classes, 'jet-woo-builder-layout-' . $this->get_custom_archive_template() );

			remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

			$product_subcategories = woocommerce_maybe_show_product_subcategories();

			if ( ! empty( $product_subcategories ) ) {
				$classes_cat = implode( ' ', $classes_cat );

				if ( 'yes' === $use_custom_categories_columns ) {
					$before = sprintf(
						'<ul class="jet-woo-builder-categories--columns %s">',
						$classes_cat
					);
					$after  = '</ul>';
				} else {
					$before = '<ul class="products columns-' . esc_attr( wc_get_loop_prop( 'columns' ) ) . '">';
					$after  = '</ul>';
				}

				$content_categories = $before . woocommerce_maybe_show_product_subcategories() . $after;
			}

			if ( 'yes' === $use_custom_columns ) {
				$classes = implode( ' ', $classes );
				$content = sprintf(
					'<ul class="jet-woo-builder-products--columns %s">',
					$classes
				);
			} else {
				$classes      = 'products columns-' . esc_attr( wc_get_loop_prop( 'columns' ) ) . '';
				$display_type = woocommerce_get_loop_display_mode();

				// If displaying just categories, append to the loop.
				if ( 'subcategories' === $display_type ) {
					$classes .= ' jet-woo-builder-hide';
				}

				$content = sprintf( '<ul class="%s">', $classes );
			}

			$content = $content_categories . $content;

			return $content;

		}

		/**
		 * Set count of products displayed in related products section
		 *
		 * @param $args
		 *
		 * @return array
		 */
		public function set_related_products_output_count( $args ) {

			$posts_per_page = jet_woo_builder_shop_settings()->get( 'related_products_per_page' );
			$posts_per_page = isset( $posts_per_page ) ? $posts_per_page : 4;

			$defaults = array(
				'posts_per_page' => $posts_per_page,
			);

			$args = wp_parse_args( $defaults, $args );

			return $args;

		}

		/**
		 * Set count of products displayed in up sells products section
		 *
		 * @param $args
		 *
		 * @return array
		 */
		public function set_up_sells_products_output_count( $args ) {

			$posts_per_page = jet_woo_builder_shop_settings()->get( 'up_sells_products_per_page' );
			$posts_per_page = isset( $posts_per_page ) ? $posts_per_page : 4;

			$defaults = array(
				'posts_per_page' => $posts_per_page,
			);

			$args = wp_parse_args( $defaults, $args );

			return $args;

		}

		/**
		 * Set count of products displayed in cross sells products section
		 *
		 * @return int
		 */
		public function set_cross_sells_products_output_count() {

			$posts_per_page = jet_woo_builder_shop_settings()->get( 'cross_sells_products_per_page' );
			$total          = isset( $posts_per_page ) ? $posts_per_page : 4;

			return $total;

		}

		/**
		 * Returns list of single product templates
		 *
		 * @return array
		 */
		public function get_single_templates() {
			return jet_woo_builder_post_type()->get_templates_list_for_options( 'single' );
		}

		/**
		 * Returns list of shop templates
		 *
		 * @return array
		 */
		public function get_shop_templates() {
			return jet_woo_builder_post_type()->get_templates_list_for_options( 'shop' );
		}

		/**
		 * Rewrite default single product and archive product item templates
		 *
		 * @param $template
		 * @param $slug
		 * @param $name
		 *
		 * @return array
		 */
		public function rewrite_templates( $template, $slug, $name ) {

			if ( 'content' === $slug && 'single-product' === $name ) {
				$custom_template = $this->get_custom_single_template();

				if ( $custom_template && 'default' !== $custom_template ) {
					$template = jet_woo_builder()->get_template( 'woocommerce/content-single-product.php' );
				}
			}

			if ( 'content' === $slug && 'product' === $name ) {
				$custom_template = $this->get_custom_archive_template();

				if ( $custom_template && 'default' !== $custom_template ) {
					$template = jet_woo_builder()->get_template( 'woocommerce/content-product.php' );
				}
			}

			return $template;

		}

		/**
		 * Rewrite product category item template
		 *
		 * @param $located
		 * @param $template_name
		 * @param $args
		 *
		 * @return mixed
		 */
		function rewrite_category_templates( $located, $template_name, $args ) {

			if ( 'yes' === jet_woo_builder_shop_settings()->get( 'custom_archive_category_page' ) && 'content-product_cat.php' === $template_name ) {
				$custom_template = $this->get_custom_archive_category_template();

				if ( $custom_template && 'default' !== $custom_template ) {
					$this->current_category_args = $args;
					$located                     = jet_woo_builder()->get_template( 'woocommerce/content-product_cat.php' );
				}
			}

			return $located;

		}

		/**
		 * Force to use default WooCommerce templates
		 *
		 * @param $located
		 * @param $template_name
		 *
		 * @return mixed|string
		 */
		public function force_wc_templates( $located, $template_name ) {

			if ( 'yes' === jet_woo_builder_shop_settings()->get( 'use_native_templates' ) && false !== strpos( $template_name, 'woocommerce/single-product/' ) ) {
				$default_path = WC()->plugin_path() . '/templates/';
				$located      = $default_path . $template_name;
			}

			return $located;

		}

		/**
		 * Override default WooCommerce pages templates
		 *
		 * @param $located
		 * @param $template_name
		 *
		 * @return mixed|string
		 */
		public function rewrite_default_wc_pages_custom_templates( $located, $template_name ) {

			// Cart template
			if ( 'yes' === jet_woo_builder_shop_settings()->get( 'custom_cart_page' ) ) {
				if ( $template_name === 'cart/cart.php' ) {
					$custom_template = $this->get_custom_cart_template();

					if ( $custom_template && 'default' !== $custom_template ) {
						$located = jet_woo_builder()->get_template( 'woocommerce/cart/cart.php' );
					}
				}

				if ( $template_name === 'cart/cart-empty.php' ) {
					$custom_template = $this->get_custom_empty_cart_template();

					if ( $custom_template && 'default' !== $custom_template ) {
						$located = jet_woo_builder()->get_template( 'woocommerce/cart/cart-empty.php' );
					}
				}
			}

			// Checkout template
			if ( 'yes' === jet_woo_builder_shop_settings()->get( 'custom_checkout_page' ) && $template_name === 'checkout/form-checkout.php' ) {
				$custom_template = $this->get_custom_checkout_template();

				if ( $custom_template && 'default' !== $custom_template ) {
					$this->current_top_template_checkout = $this->get_custom_top_checkout_template();
					$located                             = jet_woo_builder()->get_template( 'woocommerce/checkout/form-checkout.php' );
				}
			}

			// Thankyou template
			if ( 'yes' === jet_woo_builder_shop_settings()->get( 'custom_thankyou_page' ) && $template_name === 'checkout/thankyou.php' ) {
				$custom_template = $this->get_custom_thankyou_template();

				if ( $custom_template && 'default' !== $custom_template ) {
					$located = jet_woo_builder()->get_template( 'woocommerce/checkout/thankyou.php' );
				}
			}

			// Myaccount template
			if ( 'yes' === jet_woo_builder_shop_settings()->get( 'custom_myaccount_page' ) ) {
				$endpoint_enable = 'yes' === jet_woo_builder_shop_settings()->get( 'custom_myaccount_page_endpoints' );

				if ( $endpoint_enable ) {
					switch ( $template_name ) {
						case 'myaccount/dashboard.php':
							$custom_template = $this->get_custom_myaccount_dashboard_template();

							if ( $custom_template && 'default' !== $custom_template ) {
								$located = jet_woo_builder()->get_template( 'woocommerce/myaccount/dashboard.php' );
							}
							break;
						case 'myaccount/orders.php':
							$custom_template = $this->get_custom_myaccount_orders_template();

							if ( $custom_template && 'default' !== $custom_template ) {
								$located = jet_woo_builder()->get_template( 'woocommerce/myaccount/orders.php' );
							}
							break;
						case 'myaccount/downloads.php':
							$custom_template = $this->get_custom_myaccount_downloads_template();

							if ( $custom_template && 'default' !== $custom_template ) {
								$located = jet_woo_builder()->get_template( 'woocommerce/myaccount/downloads.php' );
							}
							break;
						case 'myaccount/my-address.php':
							$custom_template = $this->get_custom_myaccount_address_template();

							if ( $custom_template && 'default' !== $custom_template ) {
								$located = jet_woo_builder()->get_template( 'woocommerce/myaccount/my-address.php' );
							}
							break;
						case 'myaccount/form-edit-account.php':
							$custom_template = $this->get_custom_myaccount_account_template();

							if ( $custom_template && 'default' !== $custom_template ) {
								$located = jet_woo_builder()->get_template( 'woocommerce/myaccount/form-edit-account.php' );
							}
							break;
						default:
							break;
					}
				}

				if ( $template_name === 'myaccount/my-account.php' ) {
					$custom_template = $this->get_custom_myaccount_template();

					if ( $custom_template && 'default' !== $custom_template ) {
						$located = jet_woo_builder()->get_template( 'woocommerce/myaccount/my-account.php' );
					}
				}

				if ( $template_name === 'myaccount/form-login.php' ) {
					$custom_template = $this->get_custom_form_login_template();

					if ( $custom_template && 'default' !== $custom_template ) {
						$located = jet_woo_builder()->get_template( 'woocommerce/myaccount/form-login.php' );
					}
				}
			}

			return $located;

		}

		/**
		 * Set product archive template for editor
		 *
		 * @param $template
		 *
		 * @return string
		 */
		function set_archive_page_template( $template ) {

			if ( 'yes' === jet_woo_builder_shop_settings()->get( 'custom_shop_page' ) ) {
				if ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) || is_product_taxonomy() ) {
					$custom_template = $this->get_custom_products_archive_template();

					if ( $custom_template && 'default' !== $custom_template ) {
						$template_settings = get_post_meta( $custom_template, '_elementor_page_settings', true );
						$template_layout   = isset( $template_settings['template_layout'] ) ? $template_settings['template_layout'] : '';

						switch ( $template_layout ) {
							case 'elementor_header_footer':
								$template = jet_woo_builder()->plugin_path( 'templates/template-types/archive-templates/header-footer.php' );
								do_action( 'jet-woo-builder/template-include/found' );
								break;
							case 'elementor_canvas':
								$template = jet_woo_builder()->plugin_path( 'templates/template-types/archive-templates/canvas.php' );
								do_action( 'jet-woo-builder/template-include/found' );
								break;
							default:
								$template = jet_woo_builder()->get_template( 'woocommerce/archive-product.php' );
								break;
						}
					}
				}
			}

			return $template;

		}

		/**
		 * Set single product template for editor
		 *
		 * @param $template
		 *
		 * @return mixed|string
		 */
		public function set_product_template( $template ) {

			if ( is_singular( [ jet_woo_builder_post_type()->slug(), 'product' ] ) ) {
				$template_type   = get_post_meta( get_the_ID(), '_template_type', true );
				$custom_template = $this->get_custom_single_template();

				if ( $custom_template ) {
					switch ( $template_type ) {
						case 'canvas':
							$template = jet_woo_builder()->plugin_path( 'templates/template-types/product-templates/canvas.php' );
							do_action( 'jet-woo-builder/template-include/found' );
							break;
						case 'full_width':
							$template = jet_woo_builder()->plugin_path( 'templates/template-types/product-templates/header-footer.php' );
							do_action( 'jet-woo-builder/template-include/found' );
							break;
						default:
							if ( is_singular( 'product' ) && isset( $_GET['elementor-preview'] ) ) {
								$template = jet_woo_builder()->plugin_path( 'templates/template-types/product-templates/canvas.php' );
								do_action( 'jet-woo-builder/template-include/found' );
							}
							break;
					}
				}
			}

			return $template;

		}

		/**
		 * Set default WooCommerce pages template for editor
		 *
		 * @param $template
		 *
		 * @return mixed|string
		 */
		public function set_default_wc_pages_template( $template ) {

			$custom_template = null;

			if ( is_cart() ) {
				$custom_template = $this->get_custom_cart_template();
			} elseif ( is_checkout() ) {
				$custom_template = $this->get_custom_checkout_template();

				if ( ! empty( is_wc_endpoint_url( 'order-received' ) ) ) {
					$custom_template = $this->get_custom_thankyou_template();
				}
			} elseif ( is_account_page() ) {
				if ( is_user_logged_in() ) {
					$custom_template = $this->get_custom_myaccount_template();
				} else {
					$custom_template = $this->get_custom_form_login_template();
				}
			}

			if ( $custom_template || ( ! empty( $_GET['elementor-preview'] ) && is_singular( jet_woo_builder_post_type()->slug() ) ) ) {
				if ( ! $custom_template ) {
					$custom_template = $_GET['elementor-preview'];
				}

				$template_settings = get_post_meta( $custom_template, '_elementor_page_settings', true );
				$template_layout   = isset( $template_settings['template_layout'] ) ? $template_settings['template_layout'] : '';

				switch ( $template_layout ) {
					case 'elementor_header_footer':
						$template = jet_woo_builder()->plugin_path( 'templates/template-types/page-templates/header-footer.php' );
						do_action( 'jet-woo-builder/template-include/found' );
						break;
					case 'elementor_canvas':
						$template = jet_woo_builder()->plugin_path( 'templates/template-types/page-templates/canvas.php' );
						do_action( 'jet-woo-builder/template-include/found' );
						break;
					default:
						break;
				}
			}

			return $template;

		}

		/**
		 * Return args for current category
		 *
		 * @param $args
		 *
		 * @return array
		 */
		public function get_archive_category_args( $args ) {

			$new_args = $this->current_category_args;

			if ( ! empty( $new_args ) ) {
				$args = wp_parse_args( $new_args, $args );
			}

			return $args;

		}

		/**
		 * Returns processed categories template
		 *
		 * @return array
		 */
		public function get_current_args() {
			return $this->current_category_args;
		}

		/**
		 * Returns processed archive product card template
		 *
		 * @return mixed
		 */
		public function get_current_archive_template() {
			return $this->current_template_archive;
		}

		/**
		 * Returns processed archive category card template
		 *
		 * @return mixed
		 */
		public function get_current_archive_category_template() {
			return $this->current_template_archive_category;
		}

		/**
		 * Returns processed shop template
		 *
		 * @return mixed
		 */
		public function get_current_shop_template() {
			return $this->current_template_shop;
		}

		/**
		 * Returns processed shop template
		 *
		 * @return mixed
		 */
		public function get_current_taxonomy_template() {
			return $this->current_template_taxonomy;
		}

		/**
		 * Returns processed cart template
		 *
		 * @return mixed
		 */
		public function get_current_cart_template() {
			return $this->current_template_cart;
		}

		/**
		 * Returns processed empty cart template
		 *
		 * @return mixed
		 */
		public function get_current_empty_cart_template() {
			return $this->current_template_empty_cart;
		}

		/**
		 * Returns processed checkout template
		 *
		 * @return mixed
		 */
		public function get_current_checkout_template() {
			return $this->current_template_checkout;
		}

		/**
		 * Returns processed top checkout template
		 *
		 * @return mixed
		 */
		public function get_current_top_checkout_template() {
			return $this->current_top_template_checkout;
		}

		/**
		 * Returns processed thank you template
		 *
		 * @return mixed
		 */
		public function get_current_thankyou_template() {
			return $this->current_template_thankyou;
		}

		/**
		 * Returns processed my account template
		 *
		 * @return mixed
		 */
		public function get_current_myaccount_template() {
			return $this->current_template_myaccount;
		}

		/**
		 * Returns processed my account dashboard template
		 *
		 * @return mixed
		 */
		public function get_current_myaccount_dashboard_template() {
			return $this->current_template_myaccount_dashboard;
		}

		/**
		 * Returns processed my account orders template
		 *
		 * @return mixed
		 */
		public function get_current_myaccount_orders_template() {
			return $this->current_template_myaccount_orders;
		}

		/**
		 * Returns processed my account downloads template
		 *
		 * @return mixed
		 */
		public function get_current_myaccount_downloads_template() {
			return $this->current_template_myaccount_downloads;
		}

		/**
		 * Returns processed my account address template
		 *
		 * @return mixed
		 */
		public function get_current_myaccount_address_template() {
			return $this->current_template_myaccount_address;
		}

		/**
		 * Returns processed my account account template
		 *
		 * @return mixed
		 */
		public function get_current_myaccount_account_template() {
			return $this->current_template_myaccount_account;
		}

		/**
		 * Returns processed form login template
		 *
		 * @return mixed
		 */
		public function get_current_form_login_template() {
			return $this->current_template_form_login;
		}

		/**
		 * Get current loop type
		 *
		 * @return null|string
		 */
		public function get_current_loop() {

			$loop = 'archive';

			if ( wc_get_loop_prop( 'is_shortcode' ) ) {
				$loop = 'shortcode';
			}

			if ( wc_get_loop_prop( 'is_search' ) ) {
				$loop = 'search';
			}

			if ( 'related' === wc_get_loop_prop( 'name' ) || 'up-sells' === wc_get_loop_prop( 'name' ) ) {
				$loop = 'related';
			}

			if ( 'cross-sells' === wc_get_loop_prop( 'name' ) ) {
				$loop = 'cross_sells';
			}

			return $this->current_loop = $loop;

		}

		/**
		 * Reset current loop type
		 *
		 * @return null
		 */
		public function reset_current_loop() {

			$this->current_loop = null;

			return $this->current_loop;

		}

		/**
		 * Returns processed archive template
		 *
		 * @return mixed
		 */
		public function get_custom_shop_template() {

			if ( null !== $this->current_template_shop ) {
				return $this->current_template_shop;
			}

			$enabled         = jet_woo_builder_shop_settings()->get( 'custom_shop_page' );
			$custom_template = false;

			if ( 'yes' === $enabled && 'default' !== jet_woo_builder_shop_settings()->get( 'shop_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'shop_template' );
			}

			$this->current_template_shop = apply_filters(
				'jet-woo-builder/custom-shop-template',
				$custom_template
			);

			return $this->current_template_shop;

		}

		/**
		 * Returns processed archive template
		 *
		 * @return mixed
		 */
		public function get_custom_archive_template() {

			if ( null !== $this->current_template_archive && $this->products_loop_template_rewrite ) {
				return $this->current_template_archive;
			}

			$enabled         = jet_woo_builder_shop_settings()->get( 'custom_archive_page' );
			$custom_template = false;
			$loop            = $this->get_current_loop();

			if ( 'yes' === $enabled && 'default' !== jet_woo_builder_shop_settings()->get( $loop . '_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( $loop . '_template' );
			}

			$layout          = ! empty( $_COOKIE['jet_woo_builder_layout'] ) ? absint( $_COOKIE['jet_woo_builder_layout'] ) : false;
			$switcher_enable = apply_filters( 'jet-woo-builder/jet-products-loop/switcher-option-enable', false );

			if ( $layout && $switcher_enable && 'archive' === $loop ) {
				$this->current_template_archive = $layout;
			} else {
				$this->current_template_archive = apply_filters(
					'jet-woo-builder/custom-archive-template',
					$custom_template
				);
			}

			return $this->current_template_archive;

		}

		/**
		 * Returns macros render method
		 *
		 * @return string
		 */
		public function get_macros_render_method() {
			return 'macros';
		}

		/**
		 * Returns processed archive template
		 *
		 * @return mixed
		 */
		public function get_custom_archive_category_template() {

			if ( null !== $this->current_template_archive_category ) {
				return $this->current_template_archive_category;
			}

			$enabled         = jet_woo_builder_shop_settings()->get( 'custom_archive_category_page' );
			$custom_template = false;

			if ( 'yes' === $enabled && 'default' !== jet_woo_builder_shop_settings()->get( 'category_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'category_template' );
			}

			$this->current_template_archive_category = apply_filters(
				'jet-woo-builder/custom-archive-category-template',
				$custom_template
			);

			return $this->current_template_archive_category;

		}

		/**
		 * Returns processed cart template
		 *
		 * @return mixed
		 */
		public function get_custom_cart_template() {

			if ( null !== $this->current_template_cart ) {
				return $this->current_template_cart;
			}

			$enabled         = jet_woo_builder_shop_settings()->get( 'custom_cart_page' );
			$custom_template = false;

			if ( 'yes' === $enabled && 'default' !== jet_woo_builder_shop_settings()->get( 'cart_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'cart_template' );
			}

			$this->current_template_cart = apply_filters(
				'jet-woo-builder/custom-cart-template',
				$custom_template
			);

			return $this->current_template_cart;

		}

		/**
		 * Returns processed checkout template
		 *
		 * @return mixed
		 */
		public function get_custom_checkout_template() {

			if ( null !== $this->current_template_checkout ) {
				return $this->current_template_checkout;
			}

			$enabled         = jet_woo_builder_shop_settings()->get( 'custom_checkout_page' );
			$custom_template = false;

			if ( 'yes' === $enabled && 'default' !== jet_woo_builder_shop_settings()->get( 'checkout_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'checkout_template' );
			}

			$this->current_template_checkout = apply_filters(
				'jet-woo-builder/custom-checkout-template',
				$custom_template
			);

			return $this->current_template_checkout;

		}

		/**
		 * Returns processed top checkout template
		 *
		 * @return mixed
		 */
		public function get_custom_top_checkout_template() {

			if ( null !== $this->current_top_template_checkout ) {
				return $this->current_top_template_checkout;
			}

			$enabled         = jet_woo_builder_shop_settings()->get( 'custom_checkout_page' );
			$custom_template = false;

			if ( 'yes' === $enabled && 'default' !== jet_woo_builder_shop_settings()->get( 'checkout_top_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'checkout_top_template' );
			}

			$this->current_top_template_checkout = apply_filters(
				'jet-woo-builder/custom-top-checkout-template',
				$custom_template
			);

			return $this->current_top_template_checkout;

		}

		/**
		 * Returns processed thank you template
		 *
		 * @return mixed
		 */
		public function get_custom_thankyou_template() {

			if ( null !== $this->current_template_thankyou ) {
				return $this->current_template_thankyou;
			}

			$enabled         = jet_woo_builder_shop_settings()->get( 'custom_thankyou_page' );
			$custom_template = false;

			if ( 'yes' === $enabled && 'default' !== jet_woo_builder_shop_settings()->get( 'thankyou_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'thankyou_template' );
			}

			$this->current_template_thankyou = apply_filters(
				'jet-woo-builder/custom-thankyou-template',
				$custom_template
			);

			return $this->current_template_thankyou;

		}

		/**
		 * Returns processed my account template
		 *
		 * @return mixed
		 */
		public function get_custom_myaccount_template() {

			if ( null !== $this->current_template_myaccount ) {
				return $this->current_template_myaccount;
			}

			$enabled         = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page' );
			$custom_template = false;

			if ( 'yes' === $enabled && 'default' !== jet_woo_builder_shop_settings()->get( 'myaccount_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'myaccount_template' );
			}

			$this->current_template_myaccount = apply_filters(
				'jet-woo-builder/custom-myaccount-template',
				$custom_template
			);

			return $this->current_template_myaccount;

		}

		/**
		 * Returns processed my account dashboard template
		 *
		 * @return mixed
		 */
		public function get_custom_myaccount_dashboard_template() {

			if ( null !== $this->current_template_myaccount_dashboard ) {
				return $this->current_template_myaccount_dashboard;
			}

			$enabled           = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page' );
			$enabled_endpoints = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page_endpoints' );
			$custom_template   = false;

			if ( 'yes' === $enabled && 'yes' === $enabled_endpoints && 'default' !== jet_woo_builder_shop_settings()->get( 'myaccount_dashboard_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'myaccount_dashboard_template' );
			}

			$this->current_template_myaccount_dashboard = apply_filters(
				'jet-woo-builder/custom-myaccount-dashboard-template',
				$custom_template
			);

			return $this->current_template_myaccount_dashboard;

		}

		/**
		 * Returns processed my account orders endpoint template
		 *
		 * @return mixed
		 */
		public function get_custom_myaccount_orders_template() {

			if ( null !== $this->current_template_myaccount_orders ) {
				return $this->current_template_myaccount_orders;
			}

			$enabled           = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page' );
			$enabled_endpoints = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page_endpoints' );
			$custom_template   = false;

			if ( 'yes' === $enabled && 'yes' === $enabled_endpoints && 'default' !== jet_woo_builder_shop_settings()->get( 'myaccount_orders_endpoint_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'myaccount_orders_endpoint_template' );
			}

			$this->current_template_myaccount_orders = apply_filters(
				'jet-woo-builder/custom-myaccount-orders-endpoint-template',
				$custom_template
			);

			return $this->current_template_myaccount_orders;

		}

		/**
		 * Returns processed my account downloads endpoint template
		 *
		 * @return mixed
		 */
		public function get_custom_myaccount_downloads_template() {

			if ( null !== $this->current_template_myaccount_downloads ) {
				return $this->current_template_myaccount_downloads;
			}

			$enabled           = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page' );
			$enabled_endpoints = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page_endpoints' );
			$custom_template   = false;

			if ( 'yes' === $enabled && 'yes' === $enabled_endpoints && 'default' !== jet_woo_builder_shop_settings()->get( 'myaccount_downloads_endpoint_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'myaccount_downloads_endpoint_template' );
			}

			$this->current_template_myaccount_downloads = apply_filters(
				'jet-woo-builder/custom-myaccount-downloads-endpoint-template',
				$custom_template
			);

			return $this->current_template_myaccount_downloads;

		}

		/**
		 * Returns processed my account edit address endpoint template
		 *
		 * @return mixed
		 */
		public function get_custom_myaccount_address_template() {

			if ( null !== $this->current_template_myaccount_address ) {
				return $this->current_template_myaccount_address;
			}

			$enabled           = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page' );
			$enabled_endpoints = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page_endpoints' );
			$custom_template   = false;

			if ( 'yes' === $enabled && 'yes' === $enabled_endpoints && 'default' !== jet_woo_builder_shop_settings()->get( 'myaccount_edit_address_endpoint_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'myaccount_edit_address_endpoint_template' );
			}

			$this->current_template_myaccount_address = apply_filters(
				'jet-woo-builder/custom-myaccount-edit-address-endpoint-template',
				$custom_template
			);

			return $this->current_template_myaccount_address;

		}

		/**
		 * Returns processed my account edit account endpoint template
		 *
		 * @return mixed
		 */
		public function get_custom_myaccount_account_template() {

			if ( null !== $this->current_template_myaccount_account ) {
				return $this->current_template_myaccount_account;
			}

			$enabled           = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page' );
			$enabled_endpoints = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page_endpoints' );
			$custom_template   = false;

			if ( 'yes' === $enabled && 'yes' === $enabled_endpoints && 'default' !== jet_woo_builder_shop_settings()->get( 'myaccount_edit_account_endpoint_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'myaccount_edit_account_endpoint_template' );
			}

			$this->current_template_myaccount_account = apply_filters(
				'jet-woo-builder/custom-myaccount-edit-account-endpoint-template',
				$custom_template
			);

			return $this->current_template_myaccount_account;

		}

		/**
		 * Returns processed form login template
		 *
		 * @return mixed
		 */
		public function get_custom_form_login_template() {

			if ( null !== $this->current_template_form_login ) {
				return $this->current_template_form_login;
			}

			$enabled         = jet_woo_builder_shop_settings()->get( 'custom_myaccount_page' );
			$custom_template = false;

			if ( 'yes' === $enabled && 'default' !== jet_woo_builder_shop_settings()->get( 'form_login_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'form_login_template' );
			}

			$this->current_template_form_login = apply_filters(
				'jet-woo-builder/custom-form-login-template',
				$custom_template
			);

			return $this->current_template_form_login;

		}

		/**
		 * Returns processed empty cart template
		 *
		 * @return mixed
		 */
		public function get_custom_empty_cart_template() {

			if ( null !== $this->current_template_empty_cart ) {
				return $this->current_template_empty_cart;
			}

			$enabled         = jet_woo_builder_shop_settings()->get( 'custom_cart_page' );
			$custom_template = false;

			if ( 'yes' === $enabled && 'default' !== jet_woo_builder_shop_settings()->get( 'empty_cart_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'empty_cart_template' );
			}

			$this->current_template_empty_cart = apply_filters(
				'jet-woo-builder/custom-empty-cart-template',
				$custom_template
			);

			return $this->current_template_empty_cart;

		}

		/**
		 * Get custom single template
		 *
		 * @return string
		 */
		public function get_custom_single_template() {

			if ( null !== $this->current_template ) {
				return $this->current_template;
			}

			$enabled          = jet_woo_builder_shop_settings()->get( 'custom_single_page' );
			$product_template = get_post_meta( get_the_ID(), '_jet_woo_template', true );

			if ( ! empty( $product_template ) ) {
				return apply_filters( 'jet-woo-builder/custom-single-template', $product_template );
			}

			$custom_template = false;

			if ( 'yes' === $enabled && 'default' !== jet_woo_builder_shop_settings()->get( 'single_template' ) ) {
				$custom_template = jet_woo_builder_shop_settings()->get( 'single_template' );
			}

			$this->current_template = apply_filters(
				'jet-woo-builder/custom-single-template',
				$custom_template
			);

			return $this->current_template;

		}

		/**
		 * Get custom taxonomy template
		 *
		 * @return string
		 */
		public function get_custom_products_archive_template() {

			if ( null !== $this->current_template_taxonomy ) {
				return $this->current_template_taxonomy;
			}

			$enabled_taxonomy_template = jet_woo_builder_shop_settings()->get( 'custom_taxonomy_template' );
			$custom_template           = false;

			if ( 'yes' === $enabled_taxonomy_template ) {
				$custom_template = get_term_meta( get_queried_object_id(), 'jet_woo_builder_template', true );
			}

			if ( ! $custom_template ) {
				$custom_template = $this->get_custom_shop_template();
			}

			if ( ! empty( $custom_template ) ) {
				$this->current_template_taxonomy = apply_filters(
					'jet-woo-builder/custom-taxonomy-template',
					$custom_template
				);
			}

			return $this->current_template_taxonomy;

		}

		/**
		 * Force preview template
		 *
		 * @param  $custom_template
		 *
		 * @return int
		 */
		public function force_preview_template( $custom_template ) {
			if ( ! empty( $_GET['jet_woo_template'] ) && isset( $_GET['preview_nonce'] ) ) {
				return absint( $_GET['jet_woo_template'] );
			} else {
				return $custom_template;
			}
		}

		/**
		 * Force preview document type
		 *
		 * @param $doc_type
		 *
		 * @return mixed
		 */
		public function force_preview_doc_type( $doc_type ) {
			if ( ! empty( $_GET['jet_woo_template'] ) && isset( $_GET['preview_nonce'] ) ) {
				return get_post_meta( absint( $_GET['jet_woo_template'] ), '_elementor_template_type', true );
			} else {
				return $doc_type;
			}
		}

		/**
		 * Force product document type
		 *
		 * @param $doc_type
		 *
		 * @return string
		 */
		public function force_product_doc_type( $doc_type ) {

			if ( ! $doc_type && null !== get_post_meta( get_the_ID(), '_jet_woo_template', true ) ) {
				if ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) || is_product_taxonomy() ) {
					return 'jet-woo-builder-shop';
				}

				if ( 'product' === get_post_type() ) {
					return 'jet-woo-builder';
				}
			} else {
				return $doc_type;
			}

		}

		/**
		 * Override loop template and show quantities next to add to cart buttons
		 *
		 * @param $html
		 * @param $product
		 * @param $args
		 *
		 * @return string
		 */
		public function quantity_inputs_for_woocommerce_loop_add_to_cart_link( $html, $product, $args ) {

			if ( $product && ( $product->is_type( 'simple' ) || $product->is_type( 'variation' ) ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
				$quantity = esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 );

				$html = '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart" method="post" enctype="multipart/form-data">';
				$html .= woocommerce_quantity_input( [
					'min_value' => $product->get_min_purchase_quantity(),
					'max_value' => $product->get_max_purchase_quantity(),
				], $product, false );
				$html .= '<button type="submit" class="alt ' . $args['class'] . '" data-product_id="' . $product->get_id() . '" data-quantity="' . $quantity . '">' . esc_html( $product->add_to_cart_text() ) . '</button>';
				$html .= '</form>';
			}

			return $html;

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
 * Returns instance of Jet_Woo_Builder_Integration_Woocommerce
 *
 * @return object
 */
function jet_woo_builder_integration_woocommerce() {
	return Jet_Woo_Builder_Integration_Woocommerce::get_instance();
}
