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

if ( ! class_exists( 'Jet_Smart_Filters_Post_Type' ) ) {

	/**
	 * Define Jet_Smart_Filters_Post_Type class
	 */
	class Jet_Smart_Filters_Post_Type {

		/**
		 * Post type slug.
		 *
		 * @var string
		 */
		public $post_type = 'jet-smart-filters';

		/**
		 * Holder for taxonomies list
		 * @var boolean
		 */
		private $taxonomies = false;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'init', array( $this, 'register_post_type' ) );
			add_action( 'admin_init', array( $this, 'init_meta' ), 99999 );

			if ( is_admin() ) {
				add_action( 'add_meta_boxes_' . $this->slug(), array( $this, 'disable_metaboxes' ), 9999 );
			}

			add_filter( 'post_row_actions', array( $this, 'remove_view_action' ), 10, 2 );

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
					'name'               => esc_html__( 'Smart Filters', 'jet-smart-filters' ),
					'singular_name'      => esc_html__( 'Filter', 'jet-smart-filters' ),
					'add_new'            => esc_html__( 'Add New', 'jet-smart-filters' ),
					'add_new_item'       => esc_html__( 'Add New Filter', 'jet-smart-filters' ),
					'edit_item'          => esc_html__( 'Edit Filter', 'jet-smart-filters' ),
					'new_item'           => esc_html__( 'Add New Item', 'jet-smart-filters' ),
					'view_item'          => esc_html__( 'View Filter', 'jet-smart-filters' ),
					'search_items'       => esc_html__( 'Search Filter', 'jet-smart-filters' ),
					'not_found'          => esc_html__( 'No Filters Found', 'jet-smart-filters' ),
					'not_found_in_trash' => esc_html__( 'No Filters Found In Trash', 'jet-smart-filters' ),
					'menu_name'          => esc_html__( 'Smart Filters', 'jet-smart-filters' ),
				),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 101,
				'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode('<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20 1H4C2.34315 1 1 2.34315 1 4V20C1 21.6569 2.34315 23 4 23H20C21.6569 23 23 21.6569 23 20V4C23 2.34315 21.6569 1 20 1ZM4 0C1.79086 0 0 1.79086 0 4V20C0 22.2091 1.79086 24 4 24H20C22.2091 24 24 22.2091 24 20V4C24 1.79086 22.2091 0 20 0H4Z" fill="black"/><path fill-rule="evenodd" clip-rule="evenodd" d="M21.6293 6.00066C21.9402 5.98148 22.1176 6.38578 21.911 6.64277L20.0722 8.93035C19.8569 9.19824 19.4556 9.02698 19.4598 8.669L19.4708 7.74084C19.4722 7.61923 19.4216 7.50398 19.3343 7.42975L18.6676 6.86321C18.4105 6.6447 18.5378 6.19134 18.8619 6.17135L21.6293 6.00066ZM6.99835 12.008C6.99835 14.1993 5.20706 15.9751 2.99967 15.9751C2.44655 15.9751 2 15.5293 2 14.9827C2 14.4361 2.44655 13.9928 2.99967 13.9928C4.10336 13.9928 4.99901 13.1036 4.99901 12.008V9.03323C4.99901 8.48413 5.44556 8.04082 5.99868 8.04082C6.55179 8.04082 6.99835 8.48413 6.99835 9.03323V12.008ZM17.7765 12.008C17.7765 13.1036 18.6721 13.9928 19.7758 13.9928C20.329 13.9928 20.7755 14.4336 20.7755 14.9827C20.7755 15.5318 20.329 15.9751 19.7758 15.9751C17.5684 15.9751 15.7772 14.1993 15.7772 12.008V9.03323C15.7772 8.48413 16.2237 8.04082 16.7768 8.04082C17.33 8.04082 17.7765 8.48665 17.7765 9.03323V9.92237H18.5707C19.1238 9.92237 19.5729 10.3682 19.5729 10.9173C19.5729 11.4664 19.1238 11.9122 18.5707 11.9122H17.7765V12.008ZM15.2038 10.6176C15.2063 10.6151 15.2088 10.6151 15.2088 10.6151C14.8942 9.79393 14.3056 9.07355 13.4835 8.60001C11.5755 7.50181 9.13979 8.15166 8.04117 10.0508C6.94001 11.9475 7.59462 14.3731 9.50008 15.4688C10.9032 16.2749 12.593 16.1338 13.8261 15.2472L13.8184 15.2371C14.1026 15.0633 14.2904 14.751 14.2904 14.3958C14.2904 13.8492 13.8438 13.4059 13.2932 13.4059C13.0268 13.4059 12.7833 13.5092 12.6057 13.6805C12.0069 14.081 11.2102 14.1439 10.5378 13.7762L14.5644 11.9198C14.7978 11.8493 15.0059 11.6931 15.1353 11.4664C15.2926 11.1969 15.3078 10.8871 15.2038 10.6176ZM12.4864 10.3153C12.6057 10.3833 12.7122 10.4614 12.8112 10.5471L9.49754 12.0709C9.48993 11.7208 9.5762 11.3657 9.76395 11.0407C10.3145 10.0937 11.5324 9.76874 12.4864 10.3153Z" fill="#24292D"/></svg>'),
				'show_in_nav_menus'   => false,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => false,
				'can_export'          => true,
				'rewrite'             => false,
				'capability_type'     => 'post',
				'supports'            => array( 'title' ),
			);

			$post_type = register_post_type(
				$this->slug(),
				apply_filters( 'jet-smart-filters/post-type/args', $args )
			);

		}

		/**
		 * Returns available data sources for the filter options
		 *
		 * @return array
		 */
		public function get_options_data_sources() {
			return apply_filters( 'jet-smart-filters/post-type/options-data-sources', array(
				'manual_input'  => __( 'Manual Input', 'jet-smart-filters' ),
				'taxonomies'    => __( 'Taxonomies', 'jet-smart-filters' ),
				'posts'         => __( 'Posts', 'jet-smart-filters' ),
				'custom_fields' => __( 'Custom Fields', 'jet-smart-filters' )
			) );
		}

		/**
		 * Initialize filters meta
		 *
		 * @return void
		 */
		public function init_meta() {

			$filter_types = jet_smart_filters()->data->filter_types();
			$filter_types = array( 0 => __( 'Select filter type...', 'jet-smart-filters' ) ) + $filter_types;

			$options_data_sources = array_merge(
				array( '' => __( 'Select data source...', 'jet-smart-filters' ) ),
				$this->get_options_data_sources()
			);

			$meta_fields_labels = apply_filters( 'jet-smart-filters/post-type/meta-fields-labels', array(
				'_filter_label' => array(
					'title'   => __( 'Filter Label', 'jet-smart-filters' ),
					'type'    => 'text',
					'value'   => '',
					'element' => 'control',
				),
				'_active_label' => array(
					'title'   => __( 'Active Filter Label', 'jet-smart-filters' ),
					'type'    => 'text',
					'value'   => '',
					'element' => 'control',
				),
			) );

			$meta_fields_settings = apply_filters( 'jet-smart-filters/post-type/meta-fields-settings', array(
				'_filter_type' => array(
					'title'   => __( 'Filter Type', 'jet-smart-filters' ),
					'type'    => 'select',
					'element' => 'control',
					'options' => $filter_types,
				),
				'_date_source' => array(
					'title'   => __( 'Filter by', 'jet-smart-filters' ),
					'type'    => 'select',
					'element' => 'control',
					'options' => array(
						'meta_query' => __( 'Meta Date', 'jet-smart-filters' ),
						'date_query' => __( 'Post Date', 'jet-smart-filters' ),
					),
					'conditions' => array(
						'_filter_type' => array( 'date-range', 'date-period' ),
					),
				),
				'_is_hierarchical' => array(
					'title'   => __( 'Is hierarchical', 'jet-smart-filters' ),
					'type'    => 'switcher',
					'element' => 'control',
					'value'   => false,
					'conditions' => array(
						'_filter_type' => array( 'select' ),
					),
				),
				'_ih_source_map' => array(
					'title'       => __( 'Filter hierarchy', 'jet-smart-filters' ),
					'element'     => 'control',
					'type'        => 'repeater',
					'add_label'   => __( 'New Level', 'jet-smart-filters' ),
					'title_field' => 'label',
					'fields'      => array(
						'label' => array(
							'type'  => 'text',
							'id'    => 'label',
							'name'  => 'label',
							'label' => __( 'Label', 'jet-smart-filters' ),
							'class' => 'source-map-control label-control',
						),
						'placeholder' => array(
							'type'        => 'text',
							'id'          => 'placeholder',
							'name'        => 'placeholder',
							'placeholder' => __( 'Select...', 'jet-smart-filters' ),
							'label'       => __( 'Placeholder', 'jet-smart-filters' ),
							'class'       => 'source-map-control placeholder-control',
						),
						'tax' => array(
							'type'             => 'select',
							'id'               => 'tax',
							'name'             => 'tax',
							'label'            => __( 'Taxonomy', 'jet-smart-filters' ),
							'options_callback' => array( $this, 'get_taxonomies_for_options' ),
							'class'            => 'source-map-control tax-control',
						),
					),
					'conditions' => array(
						'_is_hierarchical' => array( true ),
						'_filter_type'     => array( 'select' ),
					),
				),
				'_data_source' => array(
					'title'   => __( 'Data Source', 'jet-smart-filters' ),
					'type'    => 'select',
					'element' => 'control',
					'options' => $options_data_sources,
					'conditions' => array(
						'_filter_type'     => array( 'checkboxes', 'select', 'radio', 'color-image' ),
						'_is_hierarchical' => array( false ),
					),
				),
				'_rating_options' => array(
					'title'      => __( 'Stars count', 'jet-smart-filters' ),
					'type'       => 'stepper',
					'element'    => 'control',
					'value'       => 5,
					'max_value'   => 10,
					'min_value'   => 1,
					'step_value'  => 1,
					'conditions' => array(
						'_filter_type'   => array( 'rating' ),
					),
				),
				'_rating_compare_operand' => array(
					'title'       => __( 'Inequality operator', 'jet-smart-filters' ),
					'description' => __( 'Set relation between values', 'jet-smart-filters' ),
					'type'        => 'select',
					'options'     => array(
						'greater' => __( 'Greater than or equals (>=)', 'jet-smart-filters' ),
						'less'    => __( 'Less than or equals (<=)', 'jet-smart-filters' ),
						'equal'   => __( 'Equals (=)', 'jet-smart-filters' ),
					),
					'element'     => 'control',
					'conditions'  => array(
						'_filter_type' => array( 'rating' ),
					),
				),
				'_source_taxonomy' => array(
					'title'            => __( 'Taxonomy', 'jet-smart-filters' ),
					'type'             => 'select',
					'element'          => 'control',
					'options_callback' => array( $this, 'get_taxonomies_for_options' ),
					'conditions'       => array(
						'_filter_type' => array( 'checkboxes', 'select', 'radio', 'color-image' ),
						'_data_source' => 'taxonomies',
						'_is_hierarchical' => array( false ),
					),
				),
				'_terms_relational_operator' => array(
					'title'            => __( 'Relational Operator', 'jet-smart-filters' ),
					'type'             => 'select',
					'element'          => 'control',
					'options' => array(
						'OR'  => __( 'Union', 'jet-smart-filters' ),
						'AND' => __( 'Intersection', 'jet-smart-filters' ),
					),
					'conditions'       => array(
						'_filter_type' => array( 'checkboxes' ),
						'_data_source' => 'taxonomies',
						'_is_hierarchical' => array( false ),
					),
				),
				'_source_post_type' => array(
					'title'            => __( 'Post Type', 'jet-smart-filters' ),
					'type'             => 'select',
					'element'          => 'control',
					'options_callback' => array( $this, 'get_post_types_for_options' ),
					'conditions'       => array(
						'_filter_type' => array( 'checkboxes', 'select', 'radio', 'color-image' ),
						'_data_source' => 'posts',
					),
				),
				'_add_all_option' => array(
					'title'   => __( 'Add all option', 'jet-smart-filters' ),
					'type'    => 'switcher',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => 'radio'
					),
				),
				'_all_option_label' => array(
					'title'   => __( 'All option label', 'jet-smart-filters' ),
					'type'    => 'text',
					'value'   => __( 'All', 'jet-smart-filters' ),
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => 'radio',
						'_add_all_option' => true
					),
				),
				'_ability_deselect_radio' => array(
					'title'   => __( 'Ability to deselect radio buttons', 'jet-smart-filters' ),
					'type'    => 'switcher',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => 'radio'
					),
				),
				'_show_empty_terms' => array(
					'title'   => __( 'Show empty terms', 'jet-smart-filters' ),
					'type'    => 'switcher',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => array( 'checkboxes', 'select', 'radio', 'color-image' ),
						'_data_source' => 'taxonomies',
						'_is_hierarchical' => array( false ),
					),
				),
				'_only_child' => array(
					'title'   => __( 'Show only children of current term', 'jet-smart-filters' ),
					'type'    => 'switcher',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => array( 'checkboxes', 'select', 'radio', 'color-image' ),
						'_data_source' => 'taxonomies',
						'_is_hierarchical' => array( false ),
					),
				),
				'_group_by_parent' => array(
					'title'   => __( 'Group terms by parents', 'jet-smart-filters' ),
					'type'    => 'switcher',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => array( 'checkboxes', 'radio' ),
						'_data_source' => 'taxonomies',
					),
				),
				'_source_custom_field' => array(
					'title'   => __( 'Custom Field Key', 'jet-smart-filters' ),
					'type'    => 'text',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => array( 'checkboxes', 'select', 'radio', 'color-image' ),
						'_data_source' => 'custom_fields',
					),
				),
				'_source_get_from_field_data' => array(
					'title'   => __( 'Get Choices From Field Data', 'jet-smart-filters' ),
					'type'    => 'switcher',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => array( 'checkboxes', 'select', 'radio' ),
						'_data_source' => 'custom_fields',
					),
				),
				'_custom_field_source_plugin' => array(
					'title'   => __( 'Field Source Plugin', 'jet-smart-filters' ),
					'type'    => 'select',
					'element' => 'control',
					'options' => array(
						'jet_engine' => __( 'JetEngine', 'jet-smart-filters' ),
						'acf'        => __( 'ACF', 'jet-smart-filters' ),
					),
					'conditions' => array(
						'_filter_type'                => array( 'checkboxes', 'select', 'radio' ),
						'_data_source'                => 'custom_fields',
						'_source_get_from_field_data' => array( true ),
					),
				),
				'_source_manual_input' => array(
					'title'       => __( 'Options List', 'jet-smart-filters' ),
					'element'     => 'control',
					'type'        => 'repeater',
					'add_label'   => __( 'New Option', 'jet-smart-filters' ),
					'title_field' => 'label',
					'fields'      => array(
						'value' => array(
							'type'  => 'text',
							'id'    => 'value',
							'name'  => 'value',
							'label' => __( 'Value', 'jet-smart-filters' ),
						),
						'label' => array(
							'type'  => 'text',
							'id'    => 'label',
							'name'  => 'label',
							'label' => __( 'Label', 'jet-smart-filters' ),
						),
					),
					'conditions' => array(
						'_filter_type' => array( 'checkboxes', 'select', 'radio' ),
						'_data_source' => 'manual_input',
					),
				),
				'_color_image_type' => array(
					'title'      => __( 'Type', 'jet-smart-filters' ),
					'type'       => 'select',
					'options'    => array(
						0       => __( 'Choose Type', 'jet-smart-filters' ),
						'color' => __( 'Color', 'jet-smart-filters' ),
						'image' => __( 'Image', 'jet-smart-filters' ),
					),
					'element'    => 'control',
					'conditions' => array(
						'_filter_type' => array( 'color-image' ),
						'_data_source' => array( 'taxonomies', 'posts', 'custom_fields', 'manual_input' ),
					),
				),
				'_color_image_behavior' => array(
					'title'      => __( 'Behavior', 'jet-smart-filters' ),
					'type'       => 'select',
					'options'    => array(
						'checkbox' => __( 'Checkbox', 'jet-smart-filters' ),
						'radio'    => __( 'Radio', 'jet-smart-filters' ),
					),
					'element'    => 'control',
					'conditions' => array(
						'_filter_type' => array( 'color-image' ),
						'_data_source' => array( 'taxonomies', 'posts', 'custom_fields', 'manual_input' ),
					),
				),
				'_source_color_image_input' => array(
					'title'       => __( 'Options List', 'jet-smart-filters' ),
					'element'     => 'control',
					'type'        => 'repeater',
					'add_label'   => __( 'New Option', 'jet-smart-filters' ),
					'title_field' => 'label',
					'class'       => 'jet-smart-filters-color-image',
					'fields'      => array(
						'label' => array(
							'type'  => 'text',
							'id'    => 'label',
							'name'  => 'label',
							'label' => __( 'Label', 'jet-smart-filters' ),
							'class' => 'color-image-type-control label-control',
						),
						'value' => array(
							'type'  => 'text',
							'id'    => 'value',
							'name'  => 'value',
							'label' => __( 'Value', 'jet-smart-filters' ),
							'class' => 'color-image-type-control value-control',
						),
						'selected_value' => array(
							'type'    => 'select',
							'id'      => 'selected_value',
							'name'    => 'selected_value',
							'options' => array(),
							'label'   => __( 'Value', 'jet-smart-filters' ),
							'class'   => 'color-image-type-control selected-value-control',
						),
						'source_color' => array(
							'type'  => 'colorpicker',
							'id'    => 'source_color',
							'name'  => 'source_color',
							'label' => __( 'Color', 'jet-smart-filters' ),
							'class' => 'color-image-type-control color-control',
						),
						'source_image' => array(
							'type'         => 'media',
							'id'           => 'source_image',
							'name'         => 'source_image',
							'multi_upload' => false,
							'library_type' => 'image',
							'label'        => __( 'Image', 'jet-smart-filters' ),
							'class'        => 'color-image-type-control image-control',
						),
					),
					'conditions' => array(
						'_filter_type'      => array( 'color-image' ),
						'_data_source'      => array( 'taxonomies', 'posts', 'custom_fields', 'manual_input' ),
						'_color_image_type' => array( 'color', 'image' ),
					),
				),
				'_source_manual_input_range' => array(
					'title'       => __( 'Options List', 'jet-smart-filters' ),
					'element'     => 'control',
					'type'        => 'repeater',
					'add_label'   => __( 'New Option', 'jet-smart-filters' ),
					'title_field' => 'label',
					'fields'      => array(
						'min' => array(
							'type'  => 'text',
							'id'    => 'min',
							'name'  => 'min',
							'label' => __( 'Min Value', 'jet-smart-filters' ),
							'placeholder' => '0'
						),
						'max' => array(
							'type'  => 'text',
							'id'    => 'max',
							'name'  => 'max',
							'label' => __( 'Max Value', 'jet-smart-filters' ),
							'placeholder' => '100'
						),
					),
					'conditions' => array(
						'_filter_type' => 'check-range',
					),
				),
				'_placeholder' => array(
					'title'       => __( 'Placeholder', 'jet-smart-filters' ),
					'type'        => 'text',
					'placeholder' => __( 'Select...', 'jet-smart-filters' ),
					'value'       => __( 'Select...', 'jet-smart-filters' ),
					'element'     => 'control',
					'conditions'  => array(
						'_filter_type' => 'select',
						'_is_hierarchical' => array( false )
					),
				),
				'_s_placeholder' => array(
					'title'   => __( 'Placeholder', 'jet-smart-filters' ),
					'type'    => 'text',
					'value'   => __( 'Search...', 'jet-smart-filters' ),
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => 'search',
					),
				),
				'_is_custom_checkbox' => array(
					'title'   => __( 'Is Checkbox Meta Field (Jet Engine)', 'jet-smart-filters' ),
					'description' => __( 'This option should to be enabled if you need to filter data from Checkbox meta fields type, created with JetEngine plugin.', 'jet-smart-filters' ),
					'type'    => 'switcher',
					'element' => 'control',
					'conditions' => array(
						'_filter_type'     => array( 'checkboxes', 'select', 'radio', 'color-image' ),
						'_is_hierarchical' => array( false ),
						'_data_source!'    => array( 'cct' )
					),
				),
				'_s_by' => array(
					'title'   => __( 'Search by', 'jet-smart-filters' ),
					'type'    => 'select',
					'element' => 'control',
					'options' => array(
						'default' => __( 'Default WordPress search', 'jet-smart-filters' ),
						'meta'    => __( 'By Custom Field (from Query Variable)', 'jet-smart-filters' ),
					),
					'conditions' => array(
						'_filter_type' => 'search',
					),
				),
				'_date_format' => array(
					'title'       => __( 'Date Format', 'jet-smart-filters' ),
					'description' => '<a href="https://api.jqueryui.com/datepicker/#utility-formatDate" target="_blank">' . __( 'Datepicker date formats', 'jet-smart-filters' ) . '</a>',
					'type'        => 'text',
					'placeholder' => 'mm/dd/yy',
					'value'       => 'mm/dd/yy',
					'element'     => 'control',
					'conditions'  => array(
						'_filter_type' => 'date-range',
					),
				),
				'_date_from_placeholder' => array(
					'title'   => __( 'From Placeholder', 'jet-smart-filters' ),
					'type'    => 'text',
					'value'   => '',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => 'date-range',
					),
				),
				'_date_to_placeholder' => array(
					'title'   => __( 'To Placeholder', 'jet-smart-filters' ),
					'type'    => 'text',
					'value'   => '',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => 'date-range',
					),
				),
				'_date_period_type' => array(
					'title'   => __( 'Period Type', 'jet-smart-filters' ),
					'type'    => 'select',
					'options' => array(
						'range' => __( 'Custom range', 'jet-smart-filters' ),
						'day'   => __( 'Day', 'jet-smart-filters' ),
						'week'  => __( 'Week', 'jet-smart-filters' ),
						'month' => __( 'Month', 'jet-smart-filters' ),
						'year'  => __( 'Year', 'jet-smart-filters' ),
					),
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => 'date-period',
					),
				),
				'_date_period_datepicker_button_text' => array(
					'title'      => __( 'Datepicker Button Text', 'jet-smart-filters' ),
					'type'       => 'text',
					'value'      => __( 'Select Date', 'jet-smart-filters' ),
					'element'    => 'control',
					'conditions' => array(
						'_filter_type' => 'date-period',
					),
				),
				'_date_period_start_end_enabled' => array(
					'title'   => __( 'Start/End Date Period Enabled', 'jet-smart-filters' ),
					'type'    => 'switcher',
					'element' => 'control',
					'value'   => true,
					'conditions' => array(
						'_filter_type' => 'date-period',
					),
				),
				'_date_period_format' => array(
					'title'       => __( 'Date Period Format', 'jet-smart-filters' ),
					'type'        => 'text',
					'placeholder' => 'mm/dd/yy',
					'element'     => 'control',
					'conditions'  => array(
						'_filter_type'                   => 'date-period',
						'_date_period_start_end_enabled' => false
					),
				),

				'_date_period_start_format' => array(
					'title'       => __( 'Start Format', 'jet-smart-filters' ),
					'type'        => 'text',
					'description' => __( 'If Period Type is Day, only this value will be taken', 'jet-smart-filters' ),
					'placeholder' => 'mm/dd/yy',
					'element'     => 'control',
					'conditions'  => array(
						'_filter_type'                   => 'date-period',
						'_date_period_start_end_enabled' => true
					),
				),
				'_date_period_separator' => array(
					'title'       => __( 'Separator', 'jet-smart-filters' ),
					'type'        => 'text',
					'placeholder' => '-',
					'element'     => 'control',
					'conditions'  => array(
						'_filter_type'                   => 'date-period',
						'_date_period_start_end_enabled' => true
					),
				),
				'_date_period_end_format' => array(
					'title'       => __( 'End Format', 'jet-smart-filters' ),
					'type'        => 'text',
					'placeholder' => 'mm/dd/yy',
					'element'     => 'control',
					'conditions'  => array(
						'_filter_type'                   => 'date-period',
						'_date_period_start_end_enabled' => true
					),
				),
				/* '_date_period_duration' => array(
					'title'       => __( 'Period Duration', 'jet-smart-filters' ),
					'type'        => 'text',
					'placeholder' => '1',
					'element'     => 'control',
					'conditions'  => array(
						'_filter_type' => 'date-period',
					),
				), */
				'_range_inputs_enabled' => array(
					'title'   => __( 'Inputs enabled', 'jet-smart-filters' ),
					'type'    => 'switcher',
					'element' => 'control',
					'value'   => false,
					'conditions' => array(
						'_filter_type' => 'range',
					),
				),
				'_values_prefix' => array(
					'title'   => __( 'Values prefix', 'jet-smart-filters' ),
					'type'    => 'text',
					'value'   => '',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => array( 'range', 'check-range' ),
					),
				),
				'_values_suffix' => array(
					'title'   => __( 'Values suffix', 'jet-smart-filters' ),
					'type'    => 'text',
					'value'   => '',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => array( 'range', 'check-range' ),
					),
				),
				'_values_thousand_sep' => array(
					'title'       => __( 'Thousands separator', 'jet-smart-filters' ),
					'type'        => 'text',
					'description' => __( 'Use &amp;nbsp; for space', 'jet-smart-filters' ),
					'value'       => '',
					'element'     => 'control',
					'conditions'  => array(
						'_filter_type' => array( 'range', 'check-range' ),
					),
				),
				'_values_decimal_sep' => array(
					'title'   => __( 'Decimal separator', 'jet-smart-filters' ),
					'type'    => 'text',
					'value'   => '.',
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => array( 'range', 'check-range' ),
					),
				),
				'_values_decimal_num' => array(
					'title'      => __( 'Number of decimals', 'jet-smart-filters' ),
					'type'       => 'text',
					'value'      => 0,
					'element'    => 'control',
					'conditions' => array(
						'_filter_type' => array( 'range', 'check-range' ),
					),
				),
				'_source_min' => array(
					'title'       => __( 'Min Value', 'jet-smart-filters' ),
					'placeholder' => '0',
					'type'        => 'text',
					'element'     => 'control',
					'conditions'  => array(
						'_filter_type' => 'range',
					),
				),
				'_source_max' => array(
					'title'       => __( 'Max Value', 'jet-smart-filters' ),
					'placeholder' => '100',
					'type'        => 'text',
					'element'     => 'control',
					'conditions'  => array(
						'_filter_type' => 'range',
					),
				),
				'_source_step' => array(
					'title'             => __( 'Step', 'jet-smart-filters' ),
					'placeholder'       => '1',
					'type'              => 'text',
					'element'           => 'control',
					'default'           => 1,
					'sanitize_callback' => array( $this, 'sanitize_range_step' ),
					'description'       => __( '1, 10, 100, 0.1 etc', 'jet-smart-filters' ),
					'conditions'        => array(
						'_filter_type' => 'range',
					),
				),
				'_source_callback' => array(
					'title'   => __( 'Get min/max dynamically', 'jet-smart-filters' ),
					'type'    => 'select',
					'options' => apply_filters( 'jet-smart-filters/range/source-callbacks', array(
						0                               => __( 'Select...', 'jet-smart-filters' ),
						'jet_smart_filters_woo_prices'  => __( 'WooCommerce min/max prices', 'jet-smart-filters' ),
						'jet_smart_filters_meta_values' => __( 'Get from query meta key', 'jet-smart-filters' ),
					) ),
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => 'range',
					),
				),
				'_use_exclude_include' => array(
					'title'   => __( 'Exclude/Include', 'jet-smart-filters' ),
					'type'    => 'select',
					'options' => array(
						0         => __( 'None', 'jet-smart-filters' ),
						'exclude' => __( 'Exclude', 'jet-smart-filters' ),
						'include' => __( 'Include', 'jet-smart-filters' ),
					),
					'element' => 'control',
					'conditions' => array(
						'_filter_type' => array( 'checkboxes', 'select', 'radio' ),
						'_data_source' => array( 'taxonomies', 'posts' ),
						'_is_hierarchical' => array( false ),
					),
				),
				'_data_exclude_include' => array(
					'title'   => __( 'Exclude Or Include Items', 'jet-smart-filters' ),
					'type'    => 'select',
					'element' => 'control',
					'multiple' => true,
					'options' => array(
						'' => '',
					),
					'conditions' => array(
						'_filter_type' => array( 'checkboxes', 'select', 'radio' ),
						'_data_source' => array( 'taxonomies', 'posts' ),
						'_use_exclude_include' => array( 'exclude', 'include' ),
						'_is_hierarchical' => array( false ),
					),
				),
				'_alphabet_behavior' => array(
					'title'      => __( 'Behavior', 'jet-smart-filters' ),
					'type'       => 'select',
					'options'    => array(
						'checkbox' => __( 'Checkbox', 'jet-smart-filters' ),
						'radio'    => __( 'Radio', 'jet-smart-filters' ),
					),
					'element'    => 'control',
					'conditions' => array(
						'_filter_type' => 'alphabet'
					),
				),
				'_alphabet_radio_deselect' => array(
					'title'      => __( 'Ability to deselect radio buttons', 'jet-smart-filters' ),
					'type'       => 'switcher',
					'value'      => true,
					'element'    => 'control',
					'conditions' => array(
						'_filter_type'       => 'alphabet',
						'_alphabet_behavior' => 'radio'
					),
				),
				'_alphabet_options' => array(
					'title'       => __( 'Options', 'jet-smart-filters' ),
					'type'        => 'textarea',
					'value'       => 'A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z',
					'element'     => 'control',
					'description' => __( 'Use comma to separate options', 'jet-smart-filters' ),
					'conditions'  => array(
						'_filter_type' => 'alphabet'
					),
				),
			) );

			$meta_query_settings = apply_filters( 'jet-smart-filters/post-type/meta-query-settings', array(
				'_query_var' => array(
					'title'       => __( 'Query Variable *', 'jet-smart-filters' ),
					'type'        => 'text',
					'description' => __( 'Set queried field key. For multiple field keys separate them with commas', 'jet-smart-filters' ),
					'element'     => 'control',
					'required'    => true,
				),
				'_is_custom_query_var' => array(
					'title'   => __( 'Use Custom Query Variable', 'jet-smart-filters' ),
					'type'    => 'switcher',
					'element' => 'control',
					'conditions' => array(
						'_data_source' => 'taxonomies'
					),
				),
				'_custom_query_var' => array(
					'title'   => __( 'Custom Query Variable', 'jet-smart-filters' ),
					'type'    => 'text',
					'element' => 'control',
					'conditions' => array(
						'_data_source'         => 'taxonomies',
						'_is_custom_query_var' => true
					)
				),
				'_query_compare' => array(
					'title'       => __( 'Comparison operator', 'jet-smart-filters' ),
					'description' => __( 'How to compare the above value', 'jet-smart-filters' ),
					'type'        => 'select',
					'options'     => array(
						'equal'   => __( 'Equals (=)', 'jet-smart-filters' ),
						'less'    => __( 'Less than or equals (<=)', 'jet-smart-filters' ),
						'greater' => __( 'Greater than or equals (>=)', 'jet-smart-filters' ),
						'like'    => __( 'LIKE', 'jet-smart-filters' ),
						//'in'      => __( 'IN', 'jet-smart-filters' ),
						//'between' => __( 'BETWEEN', 'jet-smart-filters' ),
						'exists'  => __( 'EXISTS', 'jet-smart-filters' ),
						'regexp'  => __( 'REGEXP', 'jet-smart-filters' )
					),
					'element'     => 'control',
				),
			) );

			new Cherry_X_Post_Meta( array(
				'id'            => 'filter-labels',
				'title'         => __( 'Filter Labels', 'jet-smart-filters' ),
				'page'          => array( $this->slug() ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'builder_cb'    => array( $this, 'get_builder' ),
				'fields'        => $meta_fields_labels,
			) );

			new Cherry_X_Post_Meta( array(
				'id'            => 'filter-settings',
				'title'         => __( 'Filter Settings', 'jet-smart-filters' ),
				'page'          => array( $this->slug() ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'builder_cb'    => array( $this, 'get_builder' ),
				'fields'        => $meta_fields_settings,
			) );

			new Cherry_X_Post_Meta( array(
				'id'            => 'query-settings',
				'title'         => 'Query Settings',
				'page'          => array( $this->slug() ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'builder_cb'    => array( $this, 'get_builder' ),
				'fields'        => $meta_query_settings,
			) );

			ob_start();
			include jet_smart_filters()->get_template( 'admin/filter-date-formats.php' );
			$filter_date_formats = ob_get_clean();

			new Cherry_X_Post_Meta( array(
				'id'            => 'filter-date-formats',
				'title'         => __( 'Date Formats', 'jet-smart-filters' ),
				'page'          => array( $this->slug() ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'builder_cb'    => array( $this, 'get_builder' ),
				'fields'        => array(
					'license' => array(
						'type'   => 'html',
						'class'  => 'cx-component',
						'html'   => $filter_date_formats,
					),
				),
			) );

			ob_start();
			include jet_smart_filters()->get_template( 'admin/filter-notes.php' );
			$filter_notes = ob_get_clean();

			new Cherry_X_Post_Meta( array(
				'id'            => 'filter-notes',
				'title'         => __( 'Notes', 'jet-smart-filters' ),
				'page'          => array( $this->slug() ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'builder_cb'    => array( $this, 'get_builder' ),
				'fields'        => array(
					'license' => array(
						'type'   => 'html',
						'class'  => 'cx-component',
						'html'   => $filter_notes,
					),
				),
			) );

		}

		/**
		 * Santize range step before save
		 *
		 * @param  [type] $input [description]
		 * @return [type]        [description]
		 */
		public function sanitize_range_step( $input ) {
			return trim( str_replace( ',', '.', $input ) );
		}

		/**
		 * Get taxonomies list for options.
		 *
		 * @return array
		 */
		public function get_taxonomies_for_options() {

			if ( false === $this->taxonomies ) {
				$args             = array();
				$taxonomies       = get_taxonomies( $args, 'objects', 'and' );
				$this->taxonomies = wp_list_pluck( $taxonomies, 'label', 'name' );
			}

			return $this->taxonomies;
		}

		/**
		 * Returns post types list for options
		 *
		 * @return array
		 */
		public function get_post_types_for_options() {

			$args = array(
				'public' => true,
			);

			$post_types = get_post_types( $args, 'objects', 'and' );
			$post_types = wp_list_pluck( $post_types, 'label', 'name' );

			if ( isset( $post_types[ $this->slug() ] ) ) {
				unset( $post_types[ $this->slug() ] );
			}

			return $post_types;
		}

		/**
		 * Return UI builder instance
		 *
		 * @return [type] [description]
		 */
		public function get_builder() {

			$data = jet_smart_filters()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			return new CX_Interface_Builder(
				array(
					'path' => $data['path'],
					'url'  => $data['url'],
				)
			);

		}

	}

}
