<?php

/**
 * Products shortcode class
 */
class Jet_Woo_Products_Shortcode extends Jet_Woo_Builder_Shortcode_Base {

	/**
	 * Shortcode tag
	 *
	 * @return string
	 */
	public function get_tag() {
		return 'jet-woo-products';
	}

	public function get_name() {
		return 'jet-woo-products-grid';
	}

	/**
	 * Shortcode attributes
	 *
	 * @return array
	 */
	public function get_atts() {

		$columns = jet_woo_builder_tools()->get_select_range( 12 );

		return apply_filters( 'jet-woo-builder/shortcodes/jet-woo-products/atts', array(
			'presets'                  => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Product Presets', 'jet-woo-builder' ),
				'default' => 'preset-1',
				'options' => array(
					'preset-1'  => esc_html__( 'Preset 1', 'jet-woo-builder' ),
					'preset-2'  => esc_html__( 'Preset 2', 'jet-woo-builder' ),
					'preset-3'  => esc_html__( 'Preset 3', 'jet-woo-builder' ),
					'preset-4'  => esc_html__( 'Preset 4', 'jet-woo-builder' ),
					'preset-5'  => esc_html__( 'Preset 5', 'jet-woo-builder' ),
					'preset-6'  => esc_html__( 'Preset 6', 'jet-woo-builder' ),
					'preset-7'  => esc_html__( 'Preset 7 ', 'jet-woo-builder' ),
					'preset-8'  => esc_html__( 'Preset 8 ', 'jet-woo-builder' ),
					'preset-9'  => esc_html__( 'Preset 9 ', 'jet-woo-builder' ),
					'preset-10' => esc_html__( 'Preset 10', 'jet-woo-builder' ),
				),
			),
			'columns'                  => array(
				'type'               => 'select',
				'responsive'         => true,
				'label'              => esc_html__( 'Columns', 'jet-woo-builder' ),
				'desktop_default'    => 4,
				'tablet_default'     => 2,
				'mobile_default'     => 1,
				'frontend_available' => true,
				'render_type'        => 'template',
				'selectors'          => [
					'{{WRAPPER}} .jet-woo-products .jet-woo-products__item' => '--columns: {{VALUE}}',
				],
				'options'            => $columns,
			),
			'hover_on_touch'           => array(
				'label'        => esc_html__( 'Mobile Hover on Touch', 'jet-woo-builder' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'true',
				'default'      => '',
			),
			'equal_height_cols'        => array(
				'label'        => esc_html__( 'Equal Columns Height', 'jet-woo-builder' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'true',
				'default'      => '',
			),
			'columns_gap'              => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add gap between columns', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'rows_gap'                 => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add gap between rows', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'hidden_products'          => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Hidden Products', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			),
			'clickable_item'           => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Make Product Item Clickable', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			),
			'open_new_tab'             => array(
				'label'     => esc_html__( 'Open Products in new window', 'jet-woo-builder' ),
				'type'      => 'switcher',
				'label_on'  => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off' => esc_html__( 'No', 'jet-woo-builder' ),
				'default'   => '',
			),
			'use_current_query'        => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Use Current Query', 'jet-woo-builder' ),
				'description'  => esc_html__( 'This option works only on the shop archive page, and allows you to display products for current categories, tags and taxonomies.', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'condition'    => [
					'enable_custom_query!' => 'yes',
				],
			),
			'number'                   => array(
				'type'      => 'number',
				'label'     => esc_html__( 'Products Number', 'jet-woo-builder' ),
				'default'   => 4,
				'min'       => -1,
				'max'       => 1000,
				'step'      => 1,
				'condition' => [
					'enable_custom_query!' => 'yes',
				],
			),
			'products_query'           => array(
				'type'        => 'select2',
				'label'       => esc_html__( 'Query by', 'jet-woo-builder' ),
				'default'     => 'all',
				'multiple'    => true,
				'label_block' => true,
				'options'     => jet_woo_builder_shortcodes()->get_products_query_type(),
				'condition'   => array(
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				),
			),
			'products_exclude_ids'     => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Exclude products by IDs', 'jet-woo-builder' ),
				'description' => esc_html__( 'Eg. 12, 24, 33', 'jet-woo-builder' ),
				'label_block' => true,
				'default'     => '',
				'condition'   => array(
					'products_query'       => 'all',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				),
			),
			'products_ids'             => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Set comma separated IDs list (10, 22, 19 etc.)', 'jet-woo-builder' ),
				'label_block' => true,
				'default'     => '',
				'condition'   => array(
					'products_query'       => 'ids',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				),
			),
			'products_cat'             => array(
				'type'        => 'select2',
				'label'       => esc_html__( 'Include Category', 'jet-woo-builder' ),
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
				'options'     => jet_woo_builder_tools()->get_product_categories(),
				'condition'   => array(
					'products_query'       => 'category',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				),
			),
			'products_cat_exclude'     => array(
				'type'        => 'select2',
				'label'       => esc_html__( 'Exclude Category', 'jet-woo-builder' ),
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
				'options'     => jet_woo_builder_tools()->get_product_categories(),
				'condition'   => array(
					'products_query'       => 'category',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				),
			),
			'products_tag'             => array(
				'type'        => 'select2',
				'label'       => esc_html__( 'Tag', 'jet-woo-builder' ),
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
				'options'     => jet_woo_builder_tools()->get_product_tags(),
				'condition'   => array(
					'products_query'       => 'tag',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				),
			),
			'taxonomy_slug'            => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Set custom taxonomy slug', 'jet-woo-builder' ),
				'label_block' => true,
				'default'     => '',
				'condition'   => array(
					'products_query'       => 'custom_tax',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				),
			),
			'taxonomy_id'              => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Set comma separated ID list (10, 22, 19 etc.)', 'jet-woo-builder' ),
				'label_block' => true,
				'default'     => '',
				'condition'   => array(
					'products_query'       => 'custom_tax',
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				),
			),
			'products_orderby'         => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Order by', 'jet-woo-builder' ),
				'default'   => 'default',
				'options'   => jet_woo_builder_tools()->orderby_arr(),
				'separator' => 'before',
				'condition' => array(
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				),
			),
			'products_order'           => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Order', 'jet-woo-builder' ),
				'default'   => 'desc',
				'options'   => jet_woo_builder_tools()->order_arr(),
				'condition' => array(
					'use_current_query!'   => 'yes',
					'enable_custom_query!' => 'yes',
				),
			),
			'show_title'               => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Products Title', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			),
			'add_title_link'           => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add Link to Title', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_title' => array( 'yes' ),
				),
			),
			'title_html_tag'           => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Title HTML Tag', 'jet-woo-builder' ),
				'default'   => 'h5',
				'options'   => jet_woo_builder_tools()->get_available_title_html_tags(),
				'condition' => array(
					'show_title' => array( 'yes' ),
				),
			),
			'title_trim_type'          => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Title Trim Type', 'jet-woo-builder' ),
				'default'   => 'word',
				'options'   => jet_woo_builder_tools()->get_available_title_trim_types(),
				'condition' => array(
					'show_title' => array( 'yes' ),
				),
			),
			'title_length'             => [
				'type'        => 'number',
				'label'       => esc_html__( 'Title Words/Letters Count', 'jet-woo-builder' ),
				'description' => esc_html__( 'Set -1 to show full title and 0 to hide it.', 'jet-woo-builder' ),
				'min'         => -1,
				'default'     => -1,
				'condition'   => [
					'show_title' => 'yes',
				],
			],
			'title_tooltip'            => [
				'type'         => 'switcher',
				'label'        => esc_html__( 'Enable Title Tooltip', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'title_length',
							'operator' => '>',
							'value'    => 0,
						],
					],
				],
			],
			'enable_thumb_effect'      => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Enable Thumbnail Effect', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'add_thumb_link'           => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add Link to Thumbnail', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'thumb_size'               => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Featured Image Size', 'jet-woo-builder' ),
				'default' => 'woocommerce_thumbnail',
				'options' => jet_woo_builder_tools()->get_image_sizes(),
			),
			'show_badges'              => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Badges', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'sale_badge_text'          => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Set sale badge text', 'jet-woo-builder' ),
				'default'     => esc_html__( 'Sale!', 'jet-woo-builder' ),
				'description' => esc_html__( 'Use %percentage_sale% and %numeric_sale% macros to display a withdrawal of discounts as a percentage or numeric of the initial price.', 'jet-woo-builder' ),
				'condition'   => array(
					'show_badges' => array( 'yes' ),
				),
			),
			'show_excerpt'             => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Excerpt', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'presets!' => array( 'preset-4' ),
				),
			),
			'excerpt_trim_type'        => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Excerpt Trim Type', 'jet-woo-builder' ),
				'default'   => 'word',
				'options'   => array(
					'word'    => 'Words',
					'letters' => 'Letters',
				),
				'condition' => array(
					'show_title' => array( 'yes' ),
				),
			),
			'excerpt_length'           => array(
				'type'        => 'number',
				'label'       => esc_html__( 'Excerpt Words/Letters Count', 'jet-woo-builder' ),
				'description' => esc_html__( 'Set -1 to show full excerpt and 0 to hide it.', 'jet-woo-builder' ),
				'min'         => -1,
				'default'     => 10,
				'condition'   => array(
					'show_excerpt' => array( 'yes' ),
				),
			),
			'show_cat'                 => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Categories', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'categories_count'         => [
				'type'        => 'number',
				'label'       => esc_html__( 'Categories Count', 'jet-woo-builder' ),
				'description' => esc_html__( 'Set 0 to show full list.', 'jet-woo-builder' ),
				'min'         => 0,
				'default'     => 0,
				'condition'   => [
					'show_cat' => 'yes',
				],
			],
			'show_tag'                 => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Tags', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'tags_count'               => [
				'type'        => 'number',
				'label'       => esc_html__( 'Tags Count', 'jet-woo-builder' ),
				'description' => esc_html__( 'Set 0 to show full list.', 'jet-woo-builder' ),
				'min'         => 0,
				'default'     => 0,
				'condition'   => [
					'show_tag' => 'yes',
				],
			],
			'show_price'               => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Price', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_stock_status'        => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Stock Status', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			),
			'in_stock_status_text'     => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Set In Stock Status Text', 'jet-woo-builder' ),
				'default'     => esc_html__( 'In Stock', 'jet-woo-builder' ),
				'label_block' => true,
				'condition'   => array(
					'show_stock_status' => array( 'yes' ),
				),
			),
			'on_backorder_status_text' => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Set On Backorder Status Text', 'jet-woo-builder' ),
				'default'     => esc_html__( 'On Backorder', 'jet-woo-builder' ),
				'label_block' => true,
				'condition'   => array(
					'show_stock_status' => array( 'yes' ),
				),
			),
			'out_of_stock_status_text' => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Set Out of Stock Status Text', 'jet-woo-builder' ),
				'default'     => esc_html__( 'Out of Stock', 'jet-woo-builder' ),
				'label_block' => true,
				'condition'   => array(
					'show_stock_status' => array( 'yes' ),
				),
			),
			'show_rating'              => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Product Rating', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_sku'                 => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show SKU', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			),
			'show_button'              => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Add To Cart Button', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'show_quantity'            => [
				'type'               => 'switcher',
				'label'              => __( 'Show Quantity Input', 'jet-woo-builder' ),
				'frontend_available' => true,
				'condition'          => [
					'show_button' => 'yes',
				],
			],
			'button_use_ajax_style'    => array(
				'label'        => esc_html__( 'Use default ajax add to cart styles', 'jet-woo-builder' ),
				'description'  => esc_html__( 'This option enables default WooCommerce styles for \'Add to Cart\' ajax button (\'Loading\' and \'Added\' statements)', 'jet-woo-builder' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'show_button' => array( 'yes' ),
				),
			),
			'not_found_message'        => array(
				'type'    => 'text',
				'label'   => esc_html__( 'Not found message', 'jet-woo-builder' ),
				'default' => esc_html__( 'Products not found', 'jet-woo-builder' ),
			),
			'query_id'                 => array(
				'label'       => esc_html__( 'Query ID', 'jet-woo-builder' ),
				'type'        => 'text',
				'default'     => '',
				'description' => esc_html__( 'Give your Query a custom unique id to allow server side filtering', 'jet-woo-builder' ),
				'separator'   => 'after',
			),
		) );

	}

	/**
	 *  Get products grid widget preset template.
	 *
	 * @return bool|string
	 */
	public function get_product_preset_template() {

		$template = $this->get_legacy_product_preset_template();

		if ( ! $template ) {
			$template = jet_woo_builder()->get_template( 'widgets/global/products-grid/presets/' . $this->get_attr( 'presets' ) . '.php' );
		}

		return $template;

	}

	/**
	 * Get products grid widget legacy preset template.
	 *
	 * @return bool|string
	 */
	public function get_legacy_product_preset_template() {
		return jet_woo_builder()->get_template( $this->get_tag() . '/global/presets/' . $this->get_attr( 'presets' ) . '.php' );
	}

	/**
	 * Query products by attributes.
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

			if ( ! $query ) {
				return null;
			}

			$query->setup_query();

			do_action( 'jet-engine/query-builder/listings/on-query', $query, $settings, $this, false );

			return $query->get_items();
		}

		$defaults = apply_filters( 'jet-woo-builder/shortcodes/jet-woo-products/query-args', array(
			'post_status'   => 'publish',
			'post_type'     => 'product',
			'no_found_rows' => 1,
			'meta_query'    => array(),
			'tax_query'     => array(
				'relation' => 'AND',
			),
		), $this );

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
				$query_args = apply_filters( 'jet-woo-builder/shortcodes/jet-woo-products/query-args', $query_args, $this );
				$query_args = jet_woo_builder_shortcodes()->get_wc_catalog_ordering_args( $query_args );

				add_filter( 'posts_clauses', array( $this, 'price_filter_post_clauses' ), 10, 2 );

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

			if ( $this->is_single_linked_products( $query_type[ $i ] ) ) {
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
					return false;
				}
			}

			switch ( $query_type[ $i ] ) {
				case 'all':
					if ( '' !== $this->get_attr( 'products_exclude_ids' ) ) {
						$query_args['post__not_in'] = explode(
							',',
							str_replace( ' ', '', $this->get_attr( 'products_exclude_ids' ) )
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
							str_replace( ' ', '', $this->get_attr( 'products_ids' ) )
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
							'terms'    => explode( ',', str_replace( ' ', '', $this->get_attr( 'taxonomy_id' ) ) ),
							'operator' => 'IN',
						);
					}
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
			case 'sku':
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
			default :
				$query_args['order'] = 'DESC';
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
		$query_args = apply_filters( 'jet-woo-builder/shortcodes/jet-woo-products/final-query-args', $query_args );
		$query_args = new WP_Query( $query_args );

		remove_action( 'pre_get_posts', array( $this, 'pre_get_products_query_filter' ) );

		return $query_args;
	}

	/**
	 * @param \WP_Query $wp_query
	 */
	public function pre_get_products_query_filter( $wp_query ) {
		if ( $this ) {
			$query_id = $this->get_attr( 'query_id' );

			do_action( "jet-woo-builder/query/{$query_id}", $wp_query, $this );
		}
	}

	/**
	 * Return true if linked products query type
	 *
	 * @param $query_type
	 *
	 * @return bool
	 */
	public function is_single_linked_products( $query_type ) {

		if ( 'related' === $query_type || 'up-sells' === $query_type || 'cross-sells' === $query_type ) {
			return true;
		}

		return false;

	}

	/**
	 * Products shortcode function.
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

		$not_found_message = esc_html__( $this->get_attr( 'not_found_message' ), 'jet-woo-builder' );
		$not_found_message = apply_filters( 'jet-woo-builder/shortcodes/jet-woo-products/not-found-message', $not_found_message, $this );

		if ( false === $query || empty( $query ) || is_wp_error( $query ) || ! $have_posts ) {
			printf( '<div class="jet-woo-products__not-found">%s</div>', $not_found_message );

			return false;
		}

		$loop_start = $this->get_template( 'loop-start' );
		$loop_item  = $this->get_template( 'loop-item' );
		$loop_end   = $this->get_template( 'loop-end' );

		global $post;

		ob_start();

		/**
		 * Hook before loop start template included
		 */
		do_action( 'jet-woo-builder/shortcodes/jet-woo-products/loop-start' );

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

				/**
				 * Hook before loop item template included
				 */
				do_action( 'jet-woo-builder/shortcodes/jet-woo-products/loop-item-start' );

				include $loop_item;

				/**
				 * Hook after loop item template included
				 */
				do_action( 'jet-woo-builder/shortcodes/jet-woo-products/loop-item-end' );
			}
		} else {
			while ( $query->have_posts() ) {
				$query->the_post();

				$post = $query->post;

				setup_postdata( $post );

				/**
				 * Hook before loop item template included
				 */
				do_action( 'jet-woo-builder/shortcodes/jet-woo-products/loop-item-start' );

				include $loop_item;

				/**
				 * Hook after loop item template included
				 */
				do_action( 'jet-woo-builder/shortcodes/jet-woo-products/loop-item-end' );
			}
		}

		include $loop_end;

		/**
		 * Hook after loop end template included
		 */
		do_action( 'jet-woo-builder/shortcodes/jet-woo-products/loop-end' );

		wp_reset_postdata();

		return ob_get_clean();
	}

}
