<?php

/**
 * Categories shortcode class
 */
class Jet_Woo_Categories_Shortcode extends Jet_Woo_Builder_Shortcode_Base {

	/**
	 * Shortcode tag
	 *
	 * @return string
	 */
	public function get_tag() {
		return 'jet-woo-categories';
	}

	public function get_name() {
		return 'jet-woo-categories';
	}

	/**
	 * Shortcode attributes
	 *
	 * @return array
	 */
	public function get_atts() {

		$columns = jet_woo_builder_tools()->get_select_range( 12 );

		return apply_filters( 'jet-woo-builder/shortcodes/jet-woo-categories/atts', array(
			'presets'            => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Category Presets', 'jet-woo-builder' ),
				'default' => 'preset-1',
				'options' => array(
					'preset-1' => esc_html__( 'Preset 1', 'jet-woo-builder' ),
					'preset-2' => esc_html__( 'Preset 2', 'jet-woo-builder' ),
					'preset-3' => esc_html__( 'Preset 3', 'jet-woo-builder' ),
					'preset-4' => esc_html__( 'Preset 4', 'jet-woo-builder' ),
					'preset-5' => esc_html__( 'Preset 5', 'jet-woo-builder' ),
				),
			),
			'columns'            => array(
				'type'               => 'select',
				'responsive'         => true,
				'label'              => esc_html__( 'Columns', 'jet-woo-builder' ),
				'desktop_default'    => 4,
				'tablet_default'     => 2,
				'mobile_default'     => 1,
				'frontend_available' => true,
				'render_type'        => 'template',
				'selectors'          => [
					'{{WRAPPER}} .jet-woo-categories .jet-woo-categories__item' => '--columns: {{VALUE}}',
				],
				'options'            => $columns,
			),
			'hover_on_touch'     => array(
				'label'        => esc_html__( 'Mobile Hover on Touch', 'jet-woo-builder' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'true',
				'default'      => '',
				'condition'    => [
					'presets' => [ 'preset-2', 'preset-3' ],
				],
			),
			'equal_height_cols'  => array(
				'label'        => esc_html__( 'Equal Columns Height', 'jet-woo-builder' ),
				'type'         => 'switcher',
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'true',
				'default'      => '',
			),
			'columns_gap'        => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add gap between columns', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'rows_gap'           => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Add gap between rows', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'open_new_tab'       => array(
				'label'     => esc_html__( 'Open Categories in new window', 'jet-woo-builder' ),
				'type'      => 'switcher',
				'label_on'  => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off' => esc_html__( 'No', 'jet-woo-builder' ),
				'default'   => '',
			),
			'number'             => array(
				'type'      => 'number',
				'label'     => esc_html__( 'Categories Number', 'jet-woo-builder' ),
				'default'   => 4,
				'min'       => -1,
				'max'       => 1000,
				'step'      => 1,
				'separator' => 'before',
			),
			'hide_empty'         => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Hide Empty', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			),
			'hide_subcategories' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Hide Subcategories', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'show_by' => array( 'all', 'cat_ids' ),
				),
			),
			'hide_default_cat'   => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Hide Uncategorized', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'show_by' => array( 'all' ),
				),
			),
			'show_by'            => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Query by', 'jet-woo-builder' ),
				'default' => 'all',
				'options' => array(
					'all'                   => esc_html__( 'All', 'jet-woo-builder' ),
					'parent_cat'            => esc_html__( 'Parent Category', 'jet-woo-builder' ),
					'cat_ids'               => esc_html__( 'Categories IDs', 'jet-woo-builder' ),
					'current_subcategories' => esc_html__( 'Current Subcategories', 'jet-woo-builder' ),
				),
			),
			'parent_cat_ids'     => array(
				'type'      => 'text',
				'label'     => esc_html__( 'Set parent category ID', 'jet-woo-builder' ),
				'default'   => '',
				'condition' => array(
					'show_by' => array( 'parent_cat' ),
				),
			),
			'direct_descendants' => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show only direct descendants.', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'show_by' => array( 'parent_cat' ),
				),
			),
			'cat_ids'            => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Set comma seprated IDs list (10, 22, 19 etc.)', 'jet-woo-builder' ),
				'label_block' => true,
				'default'     => '',
				'condition'   => array(
					'show_by' => array( 'cat_ids' ),
				),
			),
			'sort_by'            => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Order by', 'jet-woo-builder' ),
				'default' => 'name',
				'options' => array(
					'name'       => esc_html__( 'Name', 'jet-woo-builder' ),
					'id'         => esc_html__( 'IDs', 'jet-woo-builder' ),
					'count'      => esc_html__( 'Count', 'jet-woo-builder' ),
					'menu_order' => esc_html__( 'Menu Order', 'jet-woo-builder' ),
				),
			),
			'order'              => array(
				'type'    => 'select',
				'label'   => esc_html__( 'Order', 'jet-woo-builder' ),
				'default' => 'asc',
				'options' => jet_woo_builder_tools()->order_arr(),
			),
			'thumb_size'         => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Featured Image Size', 'jet-woo-builder' ),
				'default'   => 'woocommerce_thumbnail',
				'options'   => jet_woo_builder_tools()->get_image_sizes(),
				'separator' => 'before',
			),
			'show_title'         => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Categories Title', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'title_html_tag'     => array(
				'type'      => 'select',
				'label'     => esc_html__( 'Title HTML Tag', 'jet-woo-builder' ),
				'default'   => 'h5',
				'options'   => jet_woo_builder_tools()->get_available_title_html_tags(),
				'condition' => array(
					'show_title' => array( 'yes' ),
				),
			),
			'title_trim_type'    => [
				'type'      => 'select',
				'label'     => esc_html__( 'Title Trim Type', 'jet-woo-builder' ),
				'default'   => 'word',
				'options'   => jet_woo_builder_tools()->get_available_title_trim_types(),
				'condition' => [
					'show_title' => 'yes',
				],
			],
			'title_length'       => [
				'type'        => 'number',
				'label'       => esc_html__( 'Title Words/Letters Count', 'jet-woo-builder' ),
				'description' => esc_html__( 'Set -1 to show full title and 0 to hide it.', 'jet-woo-builder' ),
				'min'         => -1,
				'default'     => -1,
				'condition'   => [
					'show_title' => 'yes',
				],
			],
			'title_tooltip'      => [
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
			'show_count'         => array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Products Count', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
			'count_before_text'  => array(
				'type'      => 'text',
				'label'     => esc_html__( 'Count Before Text', 'jet-woo-builder' ),
				'default'   => ! is_rtl() ? '(' : ')',
				'condition' => array(
					'show_count' => array( 'yes' ),
				),
			),
			'count_after_text'   => array(
				'type'      => 'text',
				'label'     => esc_html__( 'Count After Text', 'jet-woo-builder' ),
				'default'   => ! is_rtl() ? ')' : '(',
				'condition' => array(
					'show_count' => array( 'yes' ),
				),
			),
			'desc_length'        => array(
				'type'        => 'number',
				'label'       => esc_html__( 'Description Words Count', 'jet-woo-builder' ),
				'description' => esc_html__( 'Set -1 to show full description and 0 to hide it.', 'jet-woo-builder' ),
				'min'         => -1,
				'default'     => 10,
			),
			'desc_after_text'    => array(
				'type'    => 'text',
				'label'   => esc_html__( 'Trimmed After Text', 'jet-woo-builder' ),
				'default' => '...',
			),
		) );

	}

	/**
	 * Get categories grid preset template.
	 *
	 * @return bool|string
	 */
	public function get_category_preset_template() {

		$template = $this->get_legacy_category_preset_template();

		if ( ! $template ) {
			$template = jet_woo_builder()->get_template( 'widgets/global/categories-grid/presets/' . $this->get_attr( 'presets' ) . '.php' );
		}
		return $template;

	}

	/**
	 * Get categories grid legacy preset template.
	 *
	 * @return bool|string
	 */
	public function get_legacy_category_preset_template() {
		return jet_woo_builder()->get_template( $this->get_tag() . '/global/presets/' . $this->get_attr( 'presets' ) . '.php' );
	}

	/**
	 * Query categories by attributes
	 *
	 * @return object
	 */
	public function query() {

		$defaults = apply_filters(
			'jet-woo-builder/shortcodes/jet-woo-categories/query-args',
			array(
				'post_status'  => 'publish',
				'hierarchical' => 1,
			)
		);

		$cat_args = array(
			'number'     => intval( $this->get_attr( 'number' ) ),
			'orderby'    => $this->get_attr( 'sort_by' ),
			'hide_empty' => $this->get_attr( 'hide_empty' ),
			'order'      => $this->get_attr( 'order' ),
		);

		if ( $this->get_attr( 'sort_by' ) === 'menu_order' ) {
			$cat_args['menu_order'] = $this->get_attr( 'order' );
		}

		if ( $this->get_attr( 'hide_subcategories' ) ) {
			$cat_args['parent'] = 0;
		}

		if ( $this->get_attr( 'hide_default_cat' ) ) {
			$cat_args['exclude'] = get_option( 'default_product_cat', 0 );
		}

		switch ( $this->get_attr( 'show_by' ) ) {
			case 'parent_cat':
				$direct_descendants = 'yes' === $this->get_attr( 'direct_descendants' );

				if ( $direct_descendants ) {
					$cat_args['parent'] = $this->get_attr( 'parent_cat_ids' );
				} else {
					$cat_args['child_of'] = $this->get_attr( 'parent_cat_ids' );
				}

				break;
			case 'cat_ids' :
				$cat_args['include'] = $this->get_attr( 'cat_ids' );
				break;
			case 'current_subcategories':
				$cat_args['parent'] = get_queried_object_id();
				break;
			default:
				break;
		}

		$cat_args = wp_parse_args( $cat_args, $defaults );

		$product_categories = get_terms( 'product_cat', $cat_args );

		return apply_filters( 'jet-woo-builder/shortcodes/jet-woo-categories/categories-list', $product_categories );

	}

	/**
	 * Categories shortcode function
	 *
	 * @param null $content
	 *
	 * @return string
	 */
	public function _shortcode( $content = null ) {

		$query = $this->query();

		if ( 'current_subcategories' === $this->get_attr( 'show_by' ) && empty( $query ) || is_wp_error( $query ) ) {
			return false;
		} elseif ( empty( $query ) || is_wp_error( $query ) ) {
			echo sprintf( '<h3 class="jet-woo-categories__not-found">%s</h3>', esc_html__( 'Categories not found', 'jet-woo-builder' ) );

			return false;
		}

		$loop_start = $this->get_template( 'loop-start' );
		$loop_item  = $this->get_template( 'loop-item' );
		$loop_end   = $this->get_template( 'loop-end' );

		ob_start();

		/**
		 * Hook before loop start template included
		 */
		do_action( 'jet-woo-builder/shortcodes/jet-woo-categories/loop-start' );

		include $loop_start;

		foreach ( $query as $category ) {
			setup_postdata( $category );

			/**
			 * Hook before loop item template included
			 */
			do_action( 'jet-woo-builder/shortcodes/jet-woo-categories/loop-item-start' );

			include $loop_item;

			/**
			 * Hook after loop item template included
			 */
			do_action( 'jet-woo-builder/shortcodes/jet-woo-categories/loop-item-end' );

		}

		include $loop_end;

		/**
		 * Hook after loop end template included
		 */
		do_action( 'jet-woo-builder/shortcodes/jet-woo-categories/loop-end' );

		wp_reset_postdata();

		return ob_get_clean();

	}

}
