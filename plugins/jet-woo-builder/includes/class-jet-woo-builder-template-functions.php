<?php
/**
 * WooCommerce template functions class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Woo_Builder_Template_Functions' ) ) {

	/**
	 * Define Jet_Woo_Builder_Template_Functions class
	 */
	class Jet_Woo_Builder_Template_Functions {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Returns sale badge.
		 *
		 * @param string $badge_text
		 * @param array  $settings
		 *
		 * @return string
		 */
		public function get_product_sale_flash( $badge_text = '', $settings = [] ) {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) ) {
				return '';
			}

			$on_sale = apply_filters( 'jet-woo-builder/template-functions/product-sale-flash/on-sale', $product->is_on_sale(), $product, $settings );

			if ( ! $on_sale ) {
				return '';
			}

			$html = sprintf( '<div class="jet-woo-product-badge jet-woo-product-badge__sale">%s</div>', $badge_text );

			return apply_filters( 'jet-woo-builder/template-functions/product-sale-flash', $html, $product, $settings );

		}

		/**
		 * Returns stock status html
		 *
		 * @return string
		 */
		public function get_product_stock_status() {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) ) {
				return null;
			}

			return wc_get_stock_html( $product );

		}

		/**
		 * Get custom product stock status.
		 *
		 * Returns custom stock status html markup.
		 *
		 * @since 1.7.5
		 * @since 2.0.4 Updated variable values.
		 *
		 * @param string $in_stock     In stock status label.
		 * @param string $on_backorder On backorder status label.
		 * @param string $out_of_stock Out of stock status label.
		 *
		 * @return string
		 */
		public function get_custom_product_stock_status( $in_stock = '', $on_backorder = '', $out_of_stock = '' ) {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) ) {
				return null;
			}

			if ( $product->is_on_backorder() ) {
				$stock_status = 'on-backorder';
				$status_label = $on_backorder;
			} elseif ( $product->is_in_stock() ) {
				$stock_status = 'in-stock';
				$status_label = $in_stock;
			} else {
				$stock_status = 'out-of-stock';
				$status_label = $out_of_stock;
			}

			$html = ! empty( $status_label ) ? sprintf( '<div class="jet-woo-product-stock-status__%s">%s</div>', $stock_status, $status_label ) : '';

			return apply_filters( 'jet-woo-builder/template-functions/custom-stock-status', $html );

		}

		/**
		 * Product thumbnails.
		 *
		 * Retrieves a product thumbnail or placeholder image to represent an attachment.
		 *
		 * @since  1.13.0
		 * @access public
		 *
		 * @param string $image_size       Image size.
		 * @param bool   $use_thumb_effect Thumbnail hover effect availability.
		 * @param array  $attr             Attributes for the image markup.
		 *
		 * @return mixed
		 */
		public function get_product_thumbnail( $image_size = 'thumbnail_size', $use_thumb_effect = false, $attr = [] ) {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) ) {
				return null;
			}

			$thumbnail_id      = get_post_thumbnail_id( $product->get_id() );
			$thumb_effect      = filter_var( jet_woo_builder_settings()->get( 'enable_product_thumb_effect' ), FILTER_VALIDATE_BOOLEAN );
			$placeholder_image = get_option( 'woocommerce_placeholder_image', 0 );

			if ( ! empty( $placeholder_image ) ) {
				$placeholder_src = wc_placeholder_img_src( $image_size );
			} else {
				$placeholder_src = Elementor\Utils::get_placeholder_image_src();
			}

			$placeholder_src = apply_filters( 'jet-woo-builder/template-functions/placeholder-thumbnail-src', $placeholder_src );

			if ( empty( $thumbnail_id ) ) {
				$placeholder_html = sprintf( '<img src="%s" alt="">', $placeholder_src );

				return apply_filters( 'jet-woo-builder/template-functions/placeholder-thumbnail', $placeholder_html, $image_size, $use_thumb_effect, $attr, $this );
			}

			if ( $thumb_effect && $use_thumb_effect ) {
				$attr = [
					'data-no-lazy' => '1',
					'loading'      => 'auto',
				];

				$html = wp_get_attachment_image( $thumbnail_id, $image_size, false, $attr );
				$html = $this->add_thumb_effect( $html, $product, $image_size, $attr );
			} else {
				$html = wp_get_attachment_image( $thumbnail_id, $image_size, false, $attr );
			}

			return apply_filters( 'jet-woo-builder/template-functions/product-thumbnail', $html, $image_size, $use_thumb_effect, $attr, $this );

		}

		/**
		 * Add one more thumbnail for products in loop
		 *
		 * @param $html
		 * @param $product
		 * @param $image_size
		 * @param $attr
		 *
		 * @return string
		 */
		public function add_thumb_effect( $html, $product, $image_size, $attr ) {
			$thumb_effect   = jet_woo_builder_settings()->get( 'product_thumb_effect' );
			$attachment_ids = $product->get_gallery_image_ids();

			if ( empty( $attachment_ids[0] ) ) {
				return $html;
			}

			if ( empty( $thumb_effect ) ) {
				$thumb_effect = 'slide-left';
			}

			$effect         = $thumb_effect;
			$additional_id  = $attachment_ids[0];
			$additional_img = wp_get_attachment_image( $additional_id, $image_size, false, $attr );

			$html = sprintf(
				'<div class="jet-woo-product-thumbs effect-%3$s"><div class="jet-woo-product-thumbs__inner">%1$s%2$s</div></div>',
				$html, $additional_img, $effect
			);

			return $html;
		}

		/**
		 * Returns category thumbnail
		 *
		 * @param        $category_id
		 * @param string $image_size
		 *
		 * @return string
		 */
		public function get_category_thumbnail( $category_id = '', $image_size = 'thumbnail_size' ) {

			$thumbnail_id    = get_term_meta( $category_id, 'thumbnail_id', true );
			$placeholder_src = Elementor\Utils::get_placeholder_image_src();

			if ( empty( $thumbnail_id ) ) {
				return sprintf( '<img src="%s" alt="">', $placeholder_src );
			}

			$html = wp_get_attachment_image( $thumbnail_id, $image_size, false );

			return apply_filters( 'jet-woo-builder/template-functions/category-thumbnail', $html );

		}

		/**
		 * Return product SKU.
		 *
		 * @return string
		 */
		public function get_product_sku() {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) || ! $product->get_sku() ) {
				return null;
			}

			$sku = sprintf( '<span class="sku">%s</span>', $product->get_sku() );

			return apply_filters( 'jet-woo-builder/template-functions/sku', $sku );

		}

		/**
		 * Returns product title
		 *
		 * @return string
		 */
		public function get_product_title() {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) ) {
				return null;
			}

			return get_the_title( $product->get_id() );

		}

		/**
		 * Returns product permalink depending on using template
		 *
		 * @return string
		 */
		public function get_product_permalink( $object = null ) {

			if ( function_exists( 'jet_engine' ) ) {
				if ( ! $object ) {
					$object = jet_engine()->listings->data->get_current_object();
				}

				if ( $object && is_a( $object, 'WC_Product' ) ) {
					if ( is_callable( [ $object, 'get_permalink' ] ) ) {
						return call_user_func( [ $object, 'get_permalink' ] );
					}
				}
			}

			return esc_url( get_permalink() );

		}

		/**
		 * Products rating.
		 *
		 * Returns product rating.
		 *
		 * @since  2.0.0
		 * @access public
		 *
		 * @param bool $show_empty Empty rating visibility.
		 *
		 * @return string
		 */
		public function get_product_rating( $show_empty ) {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) || 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
				return null;
			}

			$rating      = $product->get_average_rating();
			$rating_html = '';

			if ( 0 < $rating || $show_empty ) {
				$rating_html = sprintf( '<span class="product-rating__stars">%s</span>', wc_get_star_rating_html( $rating ) );
			}

			return apply_filters( 'jet-woo-builder/template-functions/product-rating', $rating_html );

		}

		/**
		 * Product custom rating.
		 *
		 * Returns custom product rating.
		 *
		 * @since  2.0.0
		 * @access public
		 *
		 * @param string $icon
		 * @param false  $show_empty_rating
		 *
		 * @return mixed|void
		 */
		public function get_product_custom_rating( $icon = 'fa fa-star', $show_empty_rating = false ) {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) || 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
				return;
			}

			if ( jet_woo_builder()->elementor_views->in_elementor() ) {
				$show_empty_rating = true;
			}

			$format      = '<span class="product-star-rating">%s<span class="product-star-rating__rated" style="%s">%s</span></span>';
			$rating      = $product->get_average_rating();
			$rated_width = 'width: ' . $rating / 5 * 100 . '%';

			if ( $rating > 0 || $show_empty_rating ) {
				$icons       = '';
				$rated_icons = '';

				for ( $i = 1; $i <= 5; $i++ ) {
					$icons       .= sprintf( '<span class="product-rating__icon %s"></span>', $icon );
					$rated_icons .= sprintf( '<span class="product-rating__icon %s active"></span>', $icon );
				}

				$html = sprintf( $format, $icons, $rated_width, $rated_icons );

				return apply_filters( 'jet-woo-builder/template-functions/custom-product-rating', $html );
			} else {
				return;
			}

		}

		/**
		 * Returns product price.
		 *
		 * @return string
		 */
		public function get_product_price() {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) ) {
				return null;
			}

			$price_html = $product->get_price_html();

			return apply_filters( 'jet-woo-builder/template-functions/product-price', $price_html );

		}

		/**
		 * Returns product excerpt
		 *
		 * @return string
		 */
		public function get_product_excerpt() {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) || ! $product->get_short_description() ) {
				return null;
			}

			return apply_filters( 'jet-woo-builder/template-functions/product-excerpt', get_the_excerpt( $product->get_id() ) );

		}

		/**
		 * Returns product add to cart button.
		 *
		 * @param array $classes
		 * @param bool  $quantity
		 *
		 * @return string
		 */
		public function get_product_add_to_cart_button( $classes = array(), $quantity = false ) {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) ) {
				return null;
			}

			$args                     = array();
			$ajax_add_to_cart_enabled = 'yes' === get_option( 'woocommerce_enable_ajax_add_to_cart' );

			if ( $product ) {
				$defaults = apply_filters(
					'jet-woo-builder/template-functions/product-add-to-cart-settings',
					array(
						'quantity'   => 1,
						'class'      => implode( ' ', array_filter(
							array(
								'button',
								$classes,
								'product_type_' . $product->get_type(),
								$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
								$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() && $ajax_add_to_cart_enabled ? 'ajax_add_to_cart' : '',
							) ) ),
						'attributes' => array(
							'data-product_id'  => $product->get_id(),
							'data-product_sku' => $product->get_sku(),
							'aria-label'       => $product->add_to_cart_description(),
							'rel'              => 'nofollow',
						),
					)
				);

				$args = wp_parse_args( $args, $defaults );

				if ( $quantity ) {
					add_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'qty_for_woocommerce_loop_add_to_cart_link' ], 10, 3 );
				}

				wc_get_template( 'loop/add-to-cart.php', $args );

				if ( $quantity ) {
					remove_filter( 'woocommerce_loop_add_to_cart_link', [ $this, 'qty_for_woocommerce_loop_add_to_cart_link' ] );
				}
			}

		}

		/**
		 * Quantity for woocommerce loop add to cart.
		 *
		 * Override loop template and show quantities next to add to cart buttons.
		 *
		 * @since  1.13.0
		 * @access public
		 *
		 * @param string $html    Link html.
		 * @param object $product Product instance.
		 * @param array  $args    Link arguments.
		 *
		 * @return string
		 */
		public function qty_for_woocommerce_loop_add_to_cart_link( $html, $product, $args ) {

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
		 * Returns product terms list depending on taxonomy.
		 *
		 * @param     $taxonomy
		 * @param     $count
		 *
		 * @return string
		 */
		public function get_product_terms_list( $taxonomy, $count ) {

			global $product;

			if ( ! is_a( $product, 'WC_Product' ) ) {
				return false;
			}

			$separator = '<span class="separator">&#44;&nbsp;</span></li><li>';
			$cat_list  = get_the_term_list( $product->get_id(), $taxonomy, '<li>', $separator, '</li>' );

			if ( ! empty( $count ) && $count > 0 ) {
				$cat_list = $this->limit_product_term_list( $cat_list, $count );
			}

			return $cat_list;

		}

		/**
		 * Limit terms list to needed count.
		 *
		 * @param $list
		 * @param $count
		 *
		 * @return string
		 */
		public function limit_product_term_list( $list, $count ) {

			$list = explode( '&#44;&nbsp;', $list );
			$list = array_slice( $list, 0, $count );

			return implode( '&#44;&nbsp;', $list );

		}

		/**
		 * WooCommerce Product current order return
		 *
		 * @return bool|WC_Order|WC_Order_Refund
		 */
		public function get_current_received_order() {

			global $wp;

			$order_received_id = null;

			if ( isset( $wp->query_vars['order-received'] ) ) {
				$order_received_id = $wp->query_vars['order-received'];
			}

			if ( jet_woo_builder()->elementor_views->in_elementor() ) {
				$order_received_id = $this->get_last_received_order();
			}

			if ( ! $order_received_id ) {
				return null;
			}

			return wc_get_order( $order_received_id );

		}

		/**
		 * WooCommerce Product last order id return
		 *
		 * @return string
		 */
		public function get_last_received_order() {

			global $wpdb;

			$statuses = array_keys( wc_get_order_statuses() );
			$statuses = implode( "','", $statuses );

			$results = $wpdb->get_col( "
				SELECT MAX(ID) FROM {$wpdb->prefix}posts
				WHERE post_type LIKE 'shop_order'
				AND post_status IN ( '$statuses' )"
			);

			return reset( $results );

		}

		/**
		 * Returns default elementor template content by template ID
		 *
		 * @param null $template_id
		 * @param bool $with_css
		 *
		 * @return string|null
		 */
		public function get_woo_builder_content( $template_id = null, $with_css = false ) {
			if ( ! class_exists( 'Elementor\Plugin' ) ) {
				return null;
			}

			if ( filter_var( jet_woo_builder_settings()->get( 'enable_inline_templates_styles' ), FILTER_VALIDATE_BOOLEAN ) ) {
				$with_css = true;
			}

			$with_css  = apply_filters( 'jet-woo-builder/get-template-content/inline-styles', $with_css, $template_id );
			$elementor = Elementor\Plugin::instance();

			return $elementor->frontend->get_builder_content( $template_id, $with_css );
		}

		/**
		 * Return product meta fields
		 *
		 * @param $product
		 * @param $settings
		 *
		 * @return string
		 */
		public function get_cart_table_custom_field_value( $product, $settings ) {

			if ( ! $product ) {
				return '';
			}

			$field_key = ! empty( $settings['cart_table_custom_field'] ) ? $settings['cart_table_custom_field'] : false;

			if ( ! $field_key ) {
				return '';
			}

			if ( is_a( $product, 'WC_Product_Variation' ) ) {
				$product_id = $product->get_parent_id();
			} else {
				$product_id = $product->get_id();
			}

			$field_value = get_post_meta( $product_id, $field_key, true );

			if ( empty( $field_value ) ) {
				$field_value = ! empty( $settings['cart_table_custom_field_fallback'] ) ? $settings['cart_table_custom_field_fallback'] : $field_value;
			}

			$custom_field = apply_filters( 'jet-woo-builder/template-functions/cart-table-custom-field/' . $field_key, $field_value );

			return sprintf( '<span class="jet-woo-custom-field">%s</span>', $custom_field );

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance( $shortcodes = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}

			return self::$instance;

		}

	}

}

/**
 * Returns instance of Jet_Woo_Builder_Template_Functions
 *
 * @return object
 */
function jet_woo_builder_template_functions() {
	return Jet_Woo_Builder_Template_Functions::get_instance();
}