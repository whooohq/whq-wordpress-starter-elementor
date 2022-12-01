<?php

/**
 * Products list shortcode class
 */
class Jet_Woo_Products_List_Shortcode extends Jet_Woo_Builder_Shortcode_Base {

	/**
	 * Shortcode tag
	 *
	 * @return string
	 */
	public function get_tag() {
		return 'jet-woo-products-list';
	}

	public function get_name() {
		return 'jet-woo-products-list';
	}

	/**
	 * Attributes.
	 *
	 * Shortcode attributes.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_atts() {
		return apply_filters( 'jet-woo-builder/shortcodes/jet-woo-products-list/atts', [
			'products_layout'          => [
				'type'    => 'select',
				'label'   => __( 'Thumbnail Position', 'jet-woo-builder' ),
				'default' => 'left',
				'options' => [
					'left'  => __( 'Left', 'jet-woo-builder' ),
					'right' => __( 'Right', 'jet-woo-builder' ),
					'top'   => __( 'Top', 'jet-woo-builder' ),
				],
			],
			'hidden_products'          => [
				'type'      => 'switcher',
				'label'     => __( 'Hidden Products', 'jet-woo-builder' ),
				'label_on'  => __( 'Show', 'jet-woo-builder' ),
				'label_off' => __( 'Hide', 'jet-woo-builder' ),
			],
			'clickable_item'           => [
				'type'  => 'switcher',
				'label' => __( 'Make Product Item Clickable', 'jet-woo-builder' ),
			],
			'open_new_tab'             => [
				'type'  => 'switcher',
				'label' => __( 'Open in New Window', 'jet-woo-builder' ),
			],
			'use_current_query'        => [
				'type'        => 'switcher',
				'label'       => __( 'Use Current Query', 'jet-woo-builder' ),
				'description' => __( 'This option works only on the products archive pages, and allows you to display products for current categories, tags and taxonomies.', 'jet-woo-builder' ),
				'separator'   => 'before',
				'condition'   => [
					'enable_custom_query!' => 'yes',
				],
			],
			'number'                   => [
				'type'      => 'number',
				'label'     => __( 'Products Number', 'jet-woo-builder' ),
				'default'   => 3,
				'min'       => 1,
				'max'       => 1000,
				'step'      => 1,
				'condition' => [
					'enable_custom_query!' => 'yes',
				],
			],
			'products_query'           => [
				'type'      => 'select2',
				'label'     => __( 'Query by', 'jet-woo-builder' ),
				'multiple'  => true,
				'options'   => jet_woo_builder_shortcodes()->get_products_query_type(),
				'default'   => 'all',
				'condition' => [
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				],
			],
			'products_exclude_ids'     => [
				'type'        => 'text',
				'label'       => __( 'Exclude by IDs', 'jet-woo-builder' ),
				'description' => __( 'Set comma separated products IDs list (10, 22, 19 etc.).', 'jet-woo-builder' ),
				'dynamic'     => [
					'active' => true,
				],
				'condition'   => [
					'products_query'       => 'all',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				],
			],
			'products_ids'             => [
				'type'        => 'text',
				'label'       => __( 'Include by IDs', 'jet-woo-builder' ),
				'description' => __( 'Set comma separated products IDs list (10, 22, 19 etc.).', 'jet-woo-builder' ),
				'dynamic'     => [
					'active' => true,
				],
				'condition'   => [
					'products_query'       => 'ids',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				],
			],
			'products_cat'             => [
				'type'      => 'select2',
				'label'     => __( 'Include Categories', 'jet-woo-builder' ),
				'multiple'  => true,
				'options'   => jet_woo_builder_tools()->get_product_categories(),
				'condition' => [
					'products_query'       => 'category',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				],
			],
			'products_cat_exclude'     => [
				'type'      => 'select2',
				'label'     => __( 'Exclude Categories', 'jet-woo-builder' ),
				'multiple'  => true,
				'options'   => jet_woo_builder_tools()->get_product_categories(),
				'condition' => [
					'products_query'       => 'category',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				],
			],
			'products_tag'             => [
				'type'      => 'select2',
				'label'     => __( 'Include Tag', 'jet-woo-builder' ),
				'multiple'  => true,
				'options'   => jet_woo_builder_tools()->get_product_tags(),
				'condition' => [
					'products_query'       => 'tag',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				],
			],
			'taxonomy_slug'            => [
				'type'      => 'text',
				'label'     => __( 'Taxonomy Slug', 'jet-woo-builder' ),
				'condition' => [
					'products_query'       => 'custom_tax',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				],
			],
			'taxonomy_id'              => [
				'type'        => 'text',
				'label'       => __( 'Include by IDs', 'jet-woo-builder' ),
				'description' => __( 'Set comma separated taxonomies IDs list (10, 22, 19 etc.).', 'jet-woo-builder' ),
				'dynamic'     => [
					'active' => true,
				],
				'condition'   => [
					'products_query'       => 'custom_tax',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				],
			],
			'products_stock_status'    => [
				'type'      => 'select2',
				'label'     => __( 'Stock Status', 'jet-woo-builder' ),
				'multiple'  => true,
				'default'   => 'instock',
				'options'   => wc_get_product_stock_status_options(),
				'condition' => [
					'products_query'       => 'stock_status',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				],
			],
			'products_orderby'         => [
				'type'      => 'select',
				'label'     => __( 'Order by', 'jet-woo-builder' ),
				'default'   => 'default',
				'options'   => jet_woo_builder_tools()->orderby_arr(),
				'condition' => [
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				],
			],
			'products_order'           => [
				'type'      => 'select',
				'label'     => __( 'Order', 'jet-woo-builder' ),
				'default'   => 'desc',
				'options'   => jet_woo_builder_tools()->order_arr(),
				'condition' => [
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				],
			],
			'show_title'               => [
				'type'      => 'switcher',
				'label'     => __( 'Title', 'jet-woo-builder' ),
				'label_on'  => __( 'Show', 'jet-woo-builder' ),
				'label_off' => __( 'Hide', 'jet-woo-builder' ),
				'default'   => 'yes',
				'separator' => 'before',
			],
			'add_title_link'           => [
				'type'      => 'switcher',
				'label'     => __( 'Enable Permalink', 'jet-woo-builder' ),
				'label_on'  => __( 'Yes', 'jet-woo-builder' ),
				'label_off' => __( 'No', 'jet-woo-builder' ),
				'default'   => 'yes',
				'condition' => [
					'show_title' => 'yes',
				],
			],
			'title_html_tag'           => [
				'type'      => 'select',
				'label'     => __( 'HTML Tag', 'jet-woo-builder' ),
				'default'   => 'h5',
				'options'   => jet_woo_builder_tools()->get_available_title_html_tags(),
				'condition' => [
					'show_title' => 'yes',
				],
			],
			'title_trim_type'          => [
				'type'      => 'select',
				'label'     => __( 'Trim Type', 'jet-woo-builder' ),
				'default'   => 'word',
				'options'   => jet_woo_builder_tools()->get_available_title_trim_types(),
				'condition' => [
					'show_title' => 'yes',
				],
			],
			'title_length'             => [
				'type'        => 'number',
				'label'       => __( 'Length', 'jet-woo-builder' ),
				'description' => __( 'Set -1 to show full title and 0 to hide it.', 'jet-woo-builder' ),
				'min'         => -1,
				'default'     => -1,
				'condition'   => [
					'show_title' => 'yes',
				],
			],
			'title_line_wrap'          => [
				'type'         => 'switcher',
				'label'        => __( 'Enable Line Wrap', 'jet-woo-builder' ),
				'prefix_class' => 'jet-woo-builder-title-line-wrap-',
				'condition'    => [
					'show_title' => 'yes',
				],
			],
			'title_tooltip'            => [
				'type'      => 'switcher',
				'label'     => __( 'Enable Title Tooltip', 'jet-woo-builder' ),
				'separator' => 'after',
				'condition' => [
					'show_title' => 'yes',
				],
			],
			'show_image'               => [
				'type'      => 'switcher',
				'label'     => __( 'Thumbnail', 'jet-woo-builder' ),
				'label_on'  => __( 'Show', 'jet-woo-builder' ),
				'label_off' => __( 'Hide', 'jet-woo-builder' ),
				'default'   => 'yes',
			],
			'is_linked_image'          => [
				'type'      => 'switcher',
				'label'     => __( 'Enable Thumbnail Permalink', 'jet-woo-builder' ),
				'condition' => [
					'show_image!' => '',
				],
			],
			'thumb_size'               => [
				'type'      => 'select',
				'label'     => __( 'Image Size', 'jet-woo-builder' ),
				'default'   => 'woocommerce_thumbnail',
				'options'   => jet_woo_builder_tools()->get_image_sizes(),
				'condition' => [
					'show_image!' => '',
				],
			],
			'show_badges'              => [
				'type'      => 'switcher',
				'label'     => __( 'Badges', 'jet-woo-builder' ),
				'label_on'  => __( 'Show', 'jet-woo-builder' ),
				'label_off' => __( 'Hide', 'jet-woo-builder' ),
				'condition' => [
					'show_image!' => '',
				],
			],
			'sale_badge_text'          => [
				'type'        => 'text',
				'label'       => __( 'Badge Label', 'jet-woo-builder' ),
				'default'     => __( 'Sale!', 'jet-woo-builder' ),
				'description' => __( 'Use %percentage_sale% and %numeric_sale% macros to display a withdrawal of discounts as a percentage or numeric of the initial price.', 'jet-woo-builder' ),
				'separator'   => 'after',
				'condition'   => [
					'show_badges!' => '',
					'show_image!'  => '',
				],
			],
			'show_cat'                 => [
				'type'      => 'switcher',
				'label'     => __( 'Categories', 'jet-woo-builder' ),
				'label_on'  => __( 'Show', 'jet-woo-builder' ),
				'label_off' => __( 'Hide', 'jet-woo-builder' ),
				'default'   => 'yes',
			],
			'categories_count'         => [
				'type'        => 'number',
				'label'       => __( 'Categories Count', 'jet-woo-builder' ),
				'description' => __( 'Set 0 to show full categories list.', 'jet-woo-builder' ),
				'min'         => 0,
				'default'     => 0,
				'separator'   => 'after',
				'condition'   => [
					'show_cat' => 'yes',
				],
			],
			'show_price'               => [
				'type'      => 'switcher',
				'label'     => __( 'Price', 'jet-woo-builder' ),
				'label_on'  => __( 'Show', 'jet-woo-builder' ),
				'label_off' => __( 'Hide', 'jet-woo-builder' ),
				'default'   => 'yes',
			],
			'show_stock_status'        => [
				'type'      => 'switcher',
				'label'     => __( 'Stock Status', 'jet-woo-builder' ),
				'label_on'  => __( 'Show', 'jet-woo-builder' ),
				'label_off' => __( 'Hide', 'jet-woo-builder' ),
			],
			'in_stock_status_text'     => [
				'type'      => 'text',
				'label'     => __( 'In Stock Label', 'jet-woo-builder' ),
				'default'   => __( 'In Stock', 'jet-woo-builder' ),
				'condition' => [
					'show_stock_status' => 'yes',
				],
			],
			'on_backorder_status_text' => [
				'type'      => 'text',
				'label'     => __( 'On Backorder Label', 'jet-woo-builder' ),
				'default'   => __( 'On Backorder', 'jet-woo-builder' ),
				'condition' => [
					'show_stock_status' => 'yes',
				],
			],
			'out_of_stock_status_text' => [
				'type'      => 'text',
				'label'     => __( 'Out of Stock Label', 'jet-woo-builder' ),
				'default'   => __( 'Out of Stock', 'jet-woo-builder' ),
				'separator' => 'after',
				'condition' => [
					'show_stock_status' => 'yes',
				],
			],
			'show_rating'              => [
				'type'      => 'switcher',
				'label'     => __( 'Rating', 'jet-woo-builder' ),
				'label_on'  => __( 'Show', 'jet-woo-builder' ),
				'label_off' => __( 'Hide', 'jet-woo-builder' ),
				'default'   => 'yes',
			],
			'show_rating_empty'        => [
				'type'      => 'switcher',
				'label'     => __( 'Empty Rating', 'jet-woo-builder' ),
				'label_on'  => __( 'Show', 'jet-woo-builder' ),
				'label_off' => __( 'Hide', 'jet-woo-builder' ),
				'separator' => 'after',
				'condition' => [
					'show_rating' => 'yes',
				],
			],
			'show_sku'                 => [
				'type'      => 'switcher',
				'label'     => __( 'SKU', 'jet-woo-builder' ),
				'label_on'  => __( 'Show', 'jet-woo-builder' ),
				'label_off' => __( 'Hide', 'jet-woo-builder' ),
			],
			'show_button'              => [
				'type'      => 'switcher',
				'label'     => __( 'Add To Cart Button', 'jet-woo-builder' ),
				'label_on'  => __( 'Show', 'jet-woo-builder' ),
				'label_off' => __( 'Hide', 'jet-woo-builder' ),
				'default'   => 'yes',
			],
			'show_quantity'            => [
				'type'               => 'switcher',
				'label'              => __( 'Quantity Input', 'jet-woo-builder' ),
				'label_on'           => __( 'Show', 'jet-woo-builder' ),
				'label_off'          => __( 'Hide', 'jet-woo-builder' ),
				'frontend_available' => true,
				'condition'          => [
					'show_button' => 'yes',
				],
			],
			'button_use_ajax_style'    => [
				'label'       => __( 'Use default ajax add to cart styles', 'jet-woo-builder' ),
				'description' => __( 'This option enables default WooCommerce styles for \'Add to Cart\' ajax button (\'Loading\' and \'Added\' statements)', 'jet-woo-builder' ),
				'type'        => 'switcher',
				'condition'   => [
					'show_button' => 'yes',
				],
			],
			'not_found_message'        => [
				'type'      => 'text',
				'label'     => __( 'Not Found Message', 'jet-woo-builder' ),
				'default'   => __( 'Products not found.', 'jet-woo-builder' ),
				'separator' => 'before',
				'dynamic'   => [
					'active' => true,
				],
			],
			'query_id'                 => [
				'type'        => 'text',
				'label'       => __( 'Query ID', 'jet-woo-builder' ),
				'description' => __( 'Give your Query a custom unique id to allow server side filtering', 'jet-woo-builder' ),
				'dynamic'     => [
					'active' => true,
				],
			],
		] );
	}

	/**
	 * Query.
	 *
	 * Returns products query by attributes.
	 *
	 * @since  1.1.0
	 * @since  2.0.4 Added `jet-woo-builder/shortcodes/jet-woo-products-list/query-type/query-args` hook. New query
	 *         type `stock_status`.
	 * @access public
	 *
	 * @return object
	 */
	public function query() {

		$settings = $this->get_settings();

		if ( isset( $settings['enable_custom_query'] ) && 'yes' === $settings['enable_custom_query'] && ! empty( $settings['custom_query_id'] ) ) {
			$query_id = absint( $settings['custom_query_id'] );

			if ( ! $query_id ) {
				return null;
			}

			$query = Jet_Engine\Query_Builder\Manager::instance()->get_query_by_id( $query_id );

			$query->setup_query();

			if ( ! $query ) {
				return null;
			}

			do_action( 'jet-engine/query-builder/listings/on-query', $query, $settings, $this, false );

			return $query->get_items();
		}

		$defaults = apply_filters( 'jet-woo-builder/shortcodes/jet-woo-products-list/query-args', [
			'post_status'   => 'publish',
			'post_type'     => 'product',
			'no_found_rows' => 1,
			'meta_query'    => [],
			'tax_query'     => [
				'relation' => 'AND',
			],
		], $this );

		$query_id = $this->get_attr( 'query_id' );

		if ( ! empty( $query_id ) ) {
			add_action( 'pre_get_posts', array( $this, 'pre_get_products_query_filter' ) );
		}

		if ( 'yes' === $this->get_attr( 'use_current_query' ) ) {
			if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
				global $wp_query;

				$wp_query->set( 'jet_use_current_query', 'yes' );
				$wp_query->set( 'posts_per_page', intval( $this->get_attr( 'number' ) ) );

				$query_args = wp_parse_args( $wp_query->query_vars, $defaults );

				// Ensure jet-woo-builder/shortcodes/jet-woo-products/query-args hook correctly fires even for archive (For filters compat)
				$query_args = apply_filters( 'jet-woo-builder/shortcodes/jet-woo-products-list/query-args', $query_args, $this );
				$query_args = jet_woo_builder_shortcodes()->get_wc_catalog_ordering_args( $query_args );

				add_filter( 'posts_clauses', [ $this, 'price_filter_post_clauses' ], 10, 2 );

				return new WP_Query( $query_args );
			}
		}

		$query_type                   = explode( ',', str_replace( ' ', '', $this->get_attr( 'products_query' ) ) );
		$query_orderby                = $this->get_attr( 'products_orderby' );
		$query_order                  = $this->get_attr( 'products_order' );
		$query_args['posts_per_page'] = intval( $this->get_attr( 'number' ) );
		$product_visibility_term_ids  = wc_get_product_visibility_term_ids();
		$viewed_products              = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array)explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array();
		$viewed_products              = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

		for ( $i = 0; $i < count( $query_type ); $i++ ) {
			if ( ( 'viewed' === $query_type[ $i ] ) && empty( $viewed_products ) ) {
				return null;
			}

			if ( $this->is_linked_products( $query_type[ $i ] ) ) {
				global $product;

				if ( ! is_a( $product, 'WC_Product' ) && ! ( jet_woo_builder()->documents->is_document_type( 'cart' ) || is_cart() ) ) {
					return null;
				}

				switch ( $query_type[ $i ] ) {
					case 'related':
						$query_args['post__in'] = wc_get_related_products( $product->get_id(), $query_args['posts_per_page'], $product->get_upsell_ids() );
						$query_args['orderby']  = 'post__in';
						break;
					case 'up-sells':
						$query_args['post__in'] = $product->get_upsell_ids();
						$query_args['orderby']  = 'post__in';
						break;
					case 'cross-sells':
						$query_args['post__in'] = ( jet_woo_builder()->documents->is_document_type( 'cart' ) || is_cart() ) ? WC()->cart->get_cross_sells() : $product->get_cross_sell_ids();
						$query_args['orderby']  = 'post__in';
						break;
				}

				if ( empty( $query_args['post__in'] ) ) {
					return null;
				}
			}

			switch ( $query_type[ $i ] ) {
				case 'all':
					if ( '' !== $this->get_attr( 'products_exclude_ids' ) ) {
						$query_args['post__not_in'] = explode(
							',',
							str_replace( ' ', '', jet_woo_builder()->macros->do_macros( $this->get_attr( 'products_exclude_ids' ) ) )
						);
					}
					break;
				case 'category':
					if ( '' !== $this->get_attr( 'products_cat' ) ) {
						$query_args['tax_query'][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => explode( ',', $this->get_attr( 'products_cat' ) ),
							'operator' => 'IN',
						);
					}
					if ( '' !== $this->get_attr( 'products_cat_exclude' ) ) {
						$query_args['tax_query'][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => explode( ',', $this->get_attr( 'products_cat_exclude' ) ),
							'operator' => 'NOT IN',
						);
					}
					break;
				case 'tag':
					if ( '' !== $this->get_attr( 'products_tag' ) ) {
						$query_args['tax_query'][] = array(
							'taxonomy' => 'product_tag',
							'field'    => 'term_id',
							'terms'    => explode( ',', $this->get_attr( 'products_tag' ) ),
							'operator' => 'IN',
						);
					}
					break;
				case 'ids':
					if ( '' !== $this->get_attr( 'products_ids' ) ) {
						$query_args['post__in'] = explode(
							',',
							str_replace( ' ', '', jet_woo_builder()->macros->do_macros( $this->get_attr( 'products_ids' ) ) )
						);
					}
					break;
				case 'featured':
					$query_args['tax_query'][] = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'term_taxonomy_id',
						'terms'    => $product_visibility_term_ids['featured'],
					);
					break;
				case 'bestsellers':
					$query_args['meta_query'][] = [
						'key'     => 'total_sales',
						'value'   => 0,
						'compare' => '>',
					];
					break;
				case 'sale':
					$query_args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
					break;
				case 'viewed':
					$query_args['post__in'] = $viewed_products;
					$query_args['orderby']  = 'post__in';
					break;
				case 'custom_tax':
					if ( '' !== $this->get_attr( 'taxonomy_slug' ) ) {
						$query_args['tax_query'][] = array(
							'taxonomy' => $this->get_attr( 'taxonomy_slug' ),
							'field'    => 'term_id',
							'terms'    => explode( ',', str_replace( ' ', '', jet_woo_builder()->macros->do_macros( $this->get_attr( 'taxonomy_id' ) ) ) ),
							'operator' => 'IN',
						);
					}
					break;
				case 'stock_status':
					$query_args['meta_query'][] = [
						'key'   => '_stock_status',
						'value' => explode( ',', $this->get_attr( 'products_stock_status' ) ),
					];

					break;

				default:
					$query_args = apply_filters( 'jet-woo-builder/shortcodes/jet-woo-products-list/query-type/query-args', $query_args, $query_type[ $i ], $this );

					break;
			}
		}

		switch ( $query_orderby ) {
			case 'id' :
				$query_args['orderby'] = 'ID';
				break;
			case 'modified' :
				$query_args['orderby'] = 'modified';
				break;
			case 'price' :
				$query_args['meta_key'] = '_price';
				$query_args['orderby']  = 'meta_value_num';
				break;
			case 'rand' :
				$query_args['orderby'] = 'rand';
				break;
			case 'sales' :
				$query_args['meta_key'] = 'total_sales';
				$query_args['orderby']  = 'meta_value_num';
				break;
			case 'rated':
				$query_args['meta_key'] = '_wc_average_rating';
				$query_args['orderby']  = 'meta_value_num';
				break;
			case 'current':
				$query_args = jet_woo_builder_shortcodes()->get_wc_catalog_ordering_args( $query_args );
				break;
			case 'menu_order':
				$query_args['orderby'] = 'menu_order';
				break;
			case 'title':
				$query_args['orderby'] = 'title';
				break;
			case 'sku' :
				$query_args['meta_key'] = '_sku';
				$query_args['orderby']  = 'meta_value';
				break;
			case 'stock_status':
				$query_args['meta_key'] = '_stock_status';
				$query_args['orderby']  = 'meta_value';
				break;
			default :
				$query_args['orderby'] = 'date';
		}

		switch ( $query_order ) {
			case 'desc':
				$query_args['order'] = 'DESC';
				break;
			case 'asc':
				$query_args['order'] = 'ASC';
				break;
		}

		if ( 'yes' == get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$query_args['meta_query'][] = array(
				'key'     => '_stock_status',
				'value'   => 'outofstock',
				'compare' => 'NOT LIKE',
			);
		}

		if ( 'yes' !== $this->get_attr( 'hidden_products' ) ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => array( 'exclude-from-catalog' ),
				'operator' => 'NOT IN',
			);
		}

		$query_args = wp_parse_args( $query_args, $defaults );
		$query_args = apply_filters( 'jet-woo-builder/shortcodes/jet-woo-products-list/query-args', $query_args, $this );
		$query_args = new WP_Query( $query_args );

		remove_action( 'pre_get_posts', array( $this, 'pre_get_products_query_filter' ) );

		return $query_args;

	}

	public function pre_get_products_query_filter( $wp_query ) {
		if ( $this ) {
			$query_id = $this->get_attr( 'query_id' );

			do_action( "jet-woo-builder/query/{$query_id}", $wp_query, $this );
		}
	}

	/**
	 * Linked Products.
	 *
	 * Return true if linked products query type.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param $query_type
	 *
	 * @return bool
	 */
	public function is_linked_products( $query_type ) {

		if ( 'related' === $query_type || 'up-sells' === $query_type || 'cross-sells' === $query_type ) {
			return true;
		}

		return false;

	}

	/**
	 * Products list shortcode function
	 *
	 * @param null $content
	 *
	 * @return string
	 */
	public function _shortcode( $content = null ) {

		$query = $this->query();

		if ( is_array( $query ) ) {
			$have_posts = ! empty( $query );
		} else {
			$have_posts = $query ? $query->have_posts() : false;
		}

		if ( false === $query || empty( $query ) || is_wp_error( $query ) || ! $have_posts ) {
			jet_woo_builder_shortcodes()->get_products_not_found_message( $this->get_tag(), $this->get_attr( 'not_found_message' ), $this );

			return false;
		}

		$loop_start = $this->get_template( 'loop-start' );
		$loop_item  = $this->get_template( 'loop-item' );
		$loop_end   = $this->get_template( 'loop-end' );

		global $post;

		ob_start();

		// Hook before loop start template included.
		do_action( 'jet-woo-builder/shortcodes/jet-woo-products-list/loop-start' );

		include $loop_start;

		if ( is_array( $query ) ) {
			foreach ( $query as $_product ) {
				if ( is_subclass_of( $_product, 'WC_Product' ) ) {
					global $product;

					$product = $_product;
				} else {
					$post = $_product;

					setup_postdata( $post );
				}

				// Hook before loop item template included.
				do_action( 'jet-woo-builder/shortcodes/jet-woo-products-list/loop-item-start' );

				include $loop_item;

				// Hook after loop item template included.
				do_action( 'jet-woo-builder/shortcodes/jet-woo-products-list/loop-item-end' );
			}
		} else {
			while ( $query->have_posts() ) {
				$query->the_post();

				$post = $query->post;

				setup_postdata( $post );

				// Hook before loop item template included.
				do_action( 'jet-woo-builder/shortcodes/jet-woo-products-list/loop-item-start' );

				include $loop_item;

				// Hook after loop item template included.
				do_action( 'jet-woo-builder/shortcodes/jet-woo-products-list/loop-item-end' );
			}
		}

		include $loop_end;

		// Hook after loop end template included.
		do_action( 'jet-woo-builder/shortcodes/jet-woo-products-list/loop-end' );

		wp_reset_postdata();

		return ob_get_clean();

	}

}
