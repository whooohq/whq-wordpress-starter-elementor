<?php

namespace Jet_Smart_Filters\Bricks_Views\Filters;

use Bricks\Database;
use Bricks\Frontend;
use Bricks\Query;

/**
 * Query loop bricks provider
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Any custom provider class should extend base provider class
 */
class Provider extends \Jet_Smart_Filters_Provider_Base {

	/**
	 * Allow to add specific query ID to element.
	 * Query ID required if you have 2+ filtered elements of same provider on the page
	 * Example of CSS class with query ID - 'jsfb-query--my-query'. 'jsfb-query--my-query' - its exact query ID.
	 * You need to set the same query ID into appropriate filter Query ID control.
	 * Then this filter will applied only for element with this class
	 * Its optional part implemented in this way for exact provider. Implementation for other providers may be different.
	 * Prefix required because Query Loop element may contain other class which not related to Query ID
	 *
	 * @var string
	 */
	protected $query_id_class_prefix = 'jsfb-query--';

	/**
	 * Add hooks specific for exact provider
	 */
	public function __construct() {
		add_filter( 'bricks/element/set_root_attributes', [ $this, 'set_attributes' ], 999, 2 );
	}

	/**
	 * Set prefix for unique ID selector. Mostly is default '#' sign, but sometimes class '.' sign needed.
	 * For example for Query Loop element we don't have HTML/CSS ID attribute, so we need to use class as unique identifier.
	 */
	public function id_prefix() {
		return '.' . $this->query_id_class_prefix;
	}

	/**
	 * Get provider name
	 * @required: true
	 */
	public function get_name() {
		return BRICKS_QUERY_LOOP_PROVIDER_NAME;
	}

	/**
	 * Get provider ID
	 * @required: true
	 */
	public function get_id() {
		return BRICKS_QUERY_LOOP_PROVIDER_ID;
	}

	/**
	 * Check if this providers requires custom renderer on the front-end
	 *
	 * @return [type] [description]
	 */
	public function is_data() {
		return true;
	}


	/**
	 * First of all you need to store default provider query and required attributes to allow
	 * JetSmartFilters attach this data to AJAX request.
	 */
	public function set_attributes( $attributes, $element ) {

		$settings = $element->settings;

		if ( empty( $element->settings['jsfb_is_filterable'] ) ) {
			return $attributes;
		}

		$query_type = $this->get_query_type( $settings );
		$classes    = [ 'jsfb-filterable' ];
		$query_id   = $settings['jsfb_query_id'] ?? 'default';
		$classes[]  = $this->query_id_class_prefix . $query_id;

		if ( $this->check_default_query_type( $query_type ) ) {
			$this->set_default_props( $settings );
			add_filter( "bricks/{$query_type}s/query_vars", [ $this, 'store_default_query' ], 10, 3 );
		} else {
			add_filter( 'bricks/query/run', [ $this, 'store_custom_query' ], 10, 2 );
		}

		return $this->merge_attributes( $attributes, 'class', $classes );

	}

	public function merge_attributes( $attributes = [], $attr_key = null, $attr_value = [] ) {

		if ( ! isset( $attributes[ $attr_key ] ) ) {
			$attributes[ $attr_key ] = [];
		}

		if ( ! is_array( $attributes[ $attr_key ] ) ) {
			$attributes[ $attr_key ] = [ $attributes[ $attr_key ] ];
		}

		$attributes[ $attr_key ] = array_merge( $attributes[ $attr_key ], $attr_value );

		return $attributes;

	}

	/**
	 * Store default brick attributes to add them to filters AJAX request
	 */
	public function store_default_query( $query_vars, $settings, $element_id ) {

		$this->store_query( $query_vars, $settings, $element_id );

		return $query_vars;

	}

	public function set_default_props( $settings ) {

		$query_bricks = Query::get_query_object();
		$query_id   = $settings['jsfb_query_id'] ?? 'default';
		$query_type = $this->get_query_type( $settings );
		$props      = [];

		if ( ! $query_bricks ) {
			$props = [
				'found_posts'   => 0,
				'max_num_pages' => 0,
				'page'          => 0,
			];
		}

		if ( $query_type === 'post' && $query_bricks !== false ) {
			$query = $query_bricks->query_result;

			if ( empty( $query ) ) {
				return;
			}

			$props = [
				'found_posts'   => $query->found_posts,
				'max_num_pages' => $query->max_num_pages,
				'page'          => max( 1, $query->get( 'paged', 1 ) ),
			];
		}

		if ( $query_type === 'user' && $query_bricks !== false ) {
			$props = [
				'found_posts'   => $query_bricks->count,
				'max_num_pages' => $query_bricks->max_num_pages,
				'page'          => $this->get_current_page(),
			];
		}

		if ( $query_type === 'term' && $query_bricks !== false ) {
			$query_vars = $settings['query'];
			unset( $query_vars['objectType'] );
			$props = $this->get_term_props( $query_vars );
		}

		jet_smart_filters()->query->set_props(
			$this->get_id(),
			$props,
			$query_id
		);

	}

	/**
	 * Store custom brick attributes to add them to filters AJAX request
	 */
	public function store_custom_query( $arr, $query ) {

		$settings = $query->settings;

		if ( empty( $settings['jsfb_is_filterable'] ) || $query->object_type !== 'jet_engine_query_builder' ) {
			return $arr;
		}

		$query_id         = $settings['jsfb_query_id'] ?? 'default';
		$query_builder_id = $settings['jet_engine_query_builder_id'] ?? '';

		if ( ! $query_builder_id ) {
			return $arr;
		}

		$query_builder = \Jet_Engine\Query_Builder\Manager::instance()->get_query_by_id( $query_builder_id );
		$query_vars    = $query_builder->get_query_args();

		$this->store_query( $query_vars, $settings, $query->element_id );

		$props = [
			'found_posts'   => $query_builder->get_items_total_count(),
			'max_num_pages' => $query_builder->get_items_pages_count(),
			'page'          => $query_builder->get_current_items_page(),
		];

		jet_smart_filters()->query->set_props(
			$this->get_id(),
			$props,
			$query_id
		);

		return $arr;

	}

	public function store_query( $query_vars, $settings, $element_id ) {

		$query_id = $settings['jsfb_query_id'] ?? 'default';

		$attrs = [
			'filtered_post_id' => isset( Database::$active_templates['content'] ) ? Database::$active_templates['content'] : Database::$page_data['preview_or_post_id'],
			'element_id'       => $element_id,
		];

		if ( Query::get_query_object()->object_type === 'user' ) {
			$query_vars['_query_type'] = 'users';
		}

		jet_smart_filters()->query->store_provider_default_query(
			$this->get_id(),
			$query_vars,
			$query_id
		);

		jet_smart_filters()->providers->add_provider_settings(
			$this->get_id(),
			$attrs,
			$query_id
		);
	}


	/**
	 * Get filtered provider content.
	 * @required: true
	 */
	public function ajax_get_content() {

		$settings = ! empty( $_REQUEST['settings'] ) ? $_REQUEST['settings'] : '';

		if ( empty( $settings['filtered_post_id'] ) || empty( $settings['element_id'] ) ) {
			return;
		}

		$post_id          = absint( $settings['filtered_post_id'] );
		$element_id       = esc_attr( $settings['element_id'] );
		$bricks_data      = get_post_meta( $post_id, BRICKS_DB_PAGE_CONTENT, true );
		$query_id         = jet_smart_filters()->query->get_current_provider( 'query_id' );
		$filtered_element = null;

		Database::$page_data['preview_or_post_id'] = $post_id;

		foreach ( $bricks_data as $element ) {

			Frontend::$elements[ $element['id'] ] = $element;

			if ( $element_id === $element['id'] ) {
				$filtered_element = $element;
			}
		}

		$query_type            = $this->get_query_type( $filtered_element['settings'] );
		$is_default_query_type = $this->check_default_query_type( $query_type );

		if ( $is_default_query_type ) {
			add_action( "pre_get_{$query_type}s", [ $this, 'add_query_args' ], 10 );
		}

		add_filter( 'jet-smart-filters/render/ajax/data', function ( $data ) use ( $query_id ) {

			$data['query_id']         = $query_id;
			$data['rendered_content'] = $data['content'];
			$data['content']          = false;

			return $data;

		} );

		add_filter( 'bricks/query/no_results_content', function ( $content ) use ( $element_id, $query_id ) {

			$classes = implode( ' ', [
				'brxe-' . $element_id,
				'jsfb-filterable',
				$this->query_id_class_prefix . $query_id,
			] );

			return '<div class="' . $classes . '">' . $content . '</div>';

		} );

		echo Frontend::render_element( $filtered_element );

		if ( $is_default_query_type ) {
			remove_filter( "bricks/{$query_type}s/query_vars", [ $this, 'store_default_query' ] );
		} else {
			remove_filter( 'bricks/query/run', [ $this, 'store_custom_query' ] );
		}

	}

	/**
	 * Apply filters on page reload
	 * Filter arguments in this case passed with $_GET request
	 *
	 * @required: true
	 */
	public function apply_filters_in_request() {

		$args = jet_smart_filters()->query->get_query_args();

		if ( ! $args ) {
			return;
		}

		add_filter( 'bricks/element/render', [ $this, 'modify_bricks_query' ], 10, 2 );
	}


	/**
	 * Here we checking - if will be rendered filtered element - we hook 'add_query_args' method
	 * to modify brick query.
	 */
	public function modify_bricks_query( $render_element, $element_instance ) {

		if ( ! $this->is_filtered_element( $element_instance ) ) {
			return $render_element;
		}

		$query_type = $this->get_query_type( $element_instance->settings );

		if ( $this->check_default_query_type( $query_type ) ) {
			add_action( "pre_get_{$query_type}s", [ $this, 'add_query_args' ], 10 );
		}

		return $render_element;
	}

	/**
	 * Check if is currently filtered brick
	 *
	 * @param array $brick Parsed brick
	 *
	 * @return boolean
	 */
	public function is_filtered_element( $element ) {

		$settings         = ! empty( $element->settings ) ? $element->settings : [];
		$element_query_id = ! empty( $settings['jsfb_query_id'] ) ? $settings['jsfb_query_id'] : '';
		$query_id         = jet_smart_filters()->query->get_current_provider( 'query_id' );

		// Bricks Query Loop
		if ( ! isset( $settings['hasLoop'] ) ) {
			return false;
		}

		if ( ! isset( $settings['jsfb_is_filterable'] ) ) {
			return false;
		}

		if ( 'default' === $query_id && empty( $element_query_id ) ) {
			return true;
		}

		return $element_query_id === $query_id;

	}


	/**
	 * Add custom query arguments
	 * This methods used by both - AJAX and page reload filters to add filter request data to query.
	 * You need to check - should it be applied or not before hooking on 'pre_get_posts'
	 *
	 * @required: true
	 */
	public function add_query_args( $query ) {

		/**
		 * With this method we can get prepared query arguments from filters request.
		 * This method returns only filtered query arguments, not whole query.
		 * Arguments returned in the format prepared for WP_Query usage. If you need to use it in some other way -
		 * you need to manually parse this arguments into required format.
		 *
		 * All custom query variables will be gathered under 'meta_query'
		 *
		 * @var array
		 */
		$args = jet_smart_filters()->query->get_query_args();

		if ( empty( $args ) ) {
			return;
		}

		$isTerm = get_class( $query ) === 'WP_Term_Query';

		if ( $isTerm ) {
			$paged  = $this->get_current_page();
			$offset = $query->query_vars['offset'] ?? 0;

			if ( $paged !== 1 && ! empty( $query->query_vars['number'] ) ) {
				$args['offset'] = ( $paged - 1 ) * $query->query_vars['number'] + $offset;
			}
		}

		foreach ( $args as $query_var => $value ) {

			if ( in_array( $query_var, [ 'tax_query', 'meta_query' ] ) ) {

				if ( $isTerm ) {

					if ( isset( $query->query_vars[ $query_var ] ) ) {
						$current = $query->query_vars[ $query_var ];
					} else {
						$current = '';
					}

				} else {
					$current = $query->get( $query_var );
				}

				if ( ! empty( $current ) ) {
					$value = array_merge( $current, $value );
				}

				if ( $isTerm ) {
					$query->query_vars[ $query_var ] = $value;
				} else {
					$query->set( $query_var, $value );
				}

			} else {

				if ( $isTerm ) {
					$query->query_vars[ $query_var ] = $value;
				} else {
					$query->set( $query_var, $value );
				}

			}

		}

		$query_type = Query::get_query_object_type();

		remove_action( "pre_get_{$query_type}s", [ $this, 'add_query_args' ], 10 );
	}

	/**
	 * Get provider wrapper selector
	 * Its CSS selector of HTML element with provider content.
	 * @required: true
	 */
	public function get_wrapper_selector() {
		return '.jsfb-filterable';
	}

	public function check_default_query_type( $query_type ) {
		$query_types = [ 'post', 'term', 'user' ];

		foreach ( $query_types as $type ) {
			if ( $type === $query_type ) {
				return true;
			}
		}

		return false;
	}

	public function get_query_type( $settings ) {
		return ! empty( $settings['query']['objectType'] ) ? $settings['query']['objectType'] : 'post';
	}

	public function get_term_props( $query_vars ) {

		// Pagination: Fix the offset value
		$offset = ! empty( $query_vars['offset'] ) ? $query_vars['offset'] : 0;

		// Hide empty
		if ( isset( $query_vars['show_empty'] ) ) {
			$query_vars['hide_empty'] = false;

			unset( $query_vars['show_empty'] );
		} else {
			$query_vars['hide_empty'] = true;
		}

		$query_args  = jet_smart_filters()->query->get_query_args();
		$query_vars  = array_merge($query_vars, $query_args);
		$terms_query = new \WP_Term_Query( $query_vars );
		$result      = $terms_query->get_terms();

		// STEP: Populate the total count
		if ( empty( $query_vars['number'] ) ) {
			$count = ! empty( $result ) && is_array( $result ) ? count( $result ) : 0;
		} else {
			$args = $query_vars;

			unset( $args['offset'] );
			unset( $args['number'] );

			// Numeric string containing the number of terms in that taxonomy or WP_Error if the taxonomy does not exist.
			$count = wp_count_terms( $args );

			if ( is_wp_error( $count ) ) {
				$count = 0;
			} else {
				$count = (int) $count;

				$count = $offset <= $count ? $count - $offset : 0;
			}
		}

		// STEP : Populate the max number of pages
		$max_num_pages = empty( $query_vars['number'] ) ? 1 : ceil( $count / $query_vars['number'] );

		return [
			'found_posts'   => $count,
			'max_num_pages' => $max_num_pages,
			'page'          => $this->get_current_page(),
		];
	}

	public function get_current_page() {
		return jet_smart_filters()->query->get_query_args()['paged'] ?? 1;
	}
}