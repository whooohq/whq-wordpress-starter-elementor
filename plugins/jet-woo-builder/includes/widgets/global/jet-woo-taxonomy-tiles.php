<?php
/**
 * Class: Jet_Woo_Taxonomy_Tiles
 * Name: Taxonomy Tiles
 * Slug: jet-woo-taxonomy-tiles
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Jet_Woo_Taxonomy_Tiles extends Jet_Woo_Builder_Base {
	public $__current_tax_count = 0;

	public function get_name() {
		return 'jet-woo-taxonomy-tiles';
	}

	public function get_title() {
		return esc_html__( 'Taxonomy Tiles', 'jet-woo-builder' );
	}

	public function get_icon() {
		return 'jet-woo-builder-icon-taxonomy-tiles';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetwoobuilder-taxonomy-tiles-widget-overview/';
	}

	public function get_categories() {
		return array( 'jet-woo-builder' );
	}

	protected function register_controls() {

		$css_scheme = apply_filters(
			'jet-woo-builder/taxonomy-tiles/css-scheme',
			array(
				'wrap'       => '.jet-woo-taxonomy-tiles',
				'box'        => '.jet-woo-taxonomy-item__box',
				'box-inner'  => '.jet-woo-taxonomy-item__box-inner',
				'title'      => '.jet-woo-taxonomy-item__box-title',
				'count'      => '.jet-woo-taxonomy-item__box-count',
				'desc'       => '.jet-woo-taxonomy-item__box-description',
				'terms_link' => '.jet-woo-taxonomy-item__box-link',
			)
		);

		$this->start_controls_section(
			'section_general_style',
			array(
				'label' => esc_html__( 'General', 'jet-woo-builder' ),
			)
		);

		$this->controls_section_general( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label'      => esc_html__( 'Box', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_box( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_hover_style',
			array(
				'label'      => esc_html__( 'Box (Hover)', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_box_hover( $css_scheme );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label'      => esc_html__( 'Content', 'jet-woo-builder' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->controls_section_content( $css_scheme );

		$this->end_controls_section();

	}

	protected function render() {

		$query           = $this->__taxonomy_query();

		if ( empty( $query ) || is_wp_error( $query ) ) {
			echo sprintf( '<h3 class="jet-woo-taxonomy__not-found">%s</h3>', esc_html__( 'Taxonomy not found', 'jet-woo-builder' ) );

			return null;
		}

		$this->__open_wrap();
		?>

		<div class="<?php $this->__get_tax_wrap_classes(); ?>" dir="ltr">
			<?php
			foreach ( $query as $taxonomy ) {
				setup_postdata( $taxonomy );

				include $this->get_template( 'global/taxonomy-tiles.php' );
			}

			wp_reset_postdata();
			?>
		</div>

		<?php
		$this->__close_wrap();

	}

	/**
	 * Add classes for taxonomy wrapper
	 */
	public function __get_tax_wrap_classes() {

		$settings = $this->get_settings();

		$classes = array(
			'jet-woo-taxonomy-tiles',
			'jet-woo-taxonomy-tiles--layout-' . $settings['layout'],
			'jet-woo-taxonomy-tiles-count--' . $settings['count_displaying'],
		);

		if ( 'out-of-content' === $settings['count_displaying'] ) {
			$classes[] = 'jet-woo-taxonomy-tiles-count--' . $settings['boxes_count_position'];
		}

		if ( $this->is_multirow_layout( $settings['layout'] ) ) {
			$rows      = isset( $settings['rows_num'] ) ? absint( $settings['rows_num'] ) : 1;
			$classes[] = 'rows-' . $rows;
		}

		echo implode( ' ', $classes );

	}

	/**
	 * Return taxonomy count to display for current layout
	 *
	 * @param $settings
	 *
	 * @return float|int
	 */
	public function __get_tax_count( $settings ) {

		if ( 0 === $this->__current_tax_count ) {

			$layout         = $settings['layout'];
			$layouts_data   = $this->__layout_data();
			$current_layout = isset( $layouts_data[ $layout ] ) ? $layouts_data[ $layout ] : false;

			if ( ! $current_layout ) {
				return $this->__current_tax_count;
			}

			$this->__current_tax_count = $current_layout['num'];

			if ( $this->is_multirow_layout( $layout ) ) {
				$rows                      = isset( $settings['rows_num'] ) ? absint( $settings['rows_num'] ) : 1;
				$this->__current_tax_count = $this->__current_tax_count * $rows;
			}

		}

		return $this->__current_tax_count;

	}

	/**
	 * Query taxonomy by attributes
	 *
	 * @return object
	 */
	public function __taxonomy_query() {

		$settings = $this->get_settings();
		$num      = $this->__get_tax_count( $settings );

		$defaults = apply_filters(
			'jet-woo-builder/jet-woo-taxonomy-tiles/query-args',
			array(
				'post_status'  => 'publish',
				'hierarchical' => 1,
			)
		);

		$args = array(
			'number'     => $num,
			'orderby'    => $settings['sort_by'],
			'hide_empty' => $settings['hide_empty'],
			'order'      => $settings['order'],
		);

		$exclude_tax = explode( ',', $settings['exclude_taxonomy_id'] );

		if ( $settings['show_taxonomy_by_id'] ) {
			$args['include'] = $settings['taxonomy_id'];
		}

		if ( 'product_cat' === $settings['taxonomy'] && $settings['hide_default_cat'] ) {
			array_push( $exclude_tax, get_option( 'default_product_cat', 0 ) );
		}

		$args['exclude'] = implode( ',', $exclude_tax );
		$args            = wp_parse_args( $args, $defaults );
		$taxonomies      = get_terms( $settings['taxonomy'], $args );

		if ( $args['orderby'] === 'rand' ) {
			$terms_count = count( $taxonomies );

			shuffle( $taxonomies );

			return array_slice( $taxonomies, 0, $terms_count );
		}

		return $taxonomies;

	}

	/**
	 * Get style attribute with taxonomy background.
	 *
	 * @param $taxonomy
	 * @param $key
	 *
	 * @return void|null
	 */
	public function __get_tax_bg( $taxonomy, $key ) {

		$thumbnail_id = get_term_meta( $taxonomy->term_id, $key, true );

		if ( $thumbnail_id ) {
			$thumb = wp_get_attachment_url( $thumbnail_id );
		} else {
			$thumb = sprintf( 'http://via.placeholder.com/900x600?text=%s', str_replace( ' ', '+', $taxonomy->name ) );
		}

		printf( 'style="background-image:url(\'%s\')"', $thumb );

	}

	/**
	 * Check if current layout is multirow layout
	 *
	 * @param $layout
	 *
	 * @return boolean
	 */
	public function is_multirow_layout( $layout ) {

		$multirow_layouts = apply_filters( 'jet-woo-builder/taxonomy-tiles/multirow-layouts', array(
			'2-x',
			'3-x',
			'4-x',
		) );

		return in_array( $layout, $multirow_layouts );

	}

	/**
	 * Returns information about available layouts
	 *
	 * @return array
	 */
	public function __layout_data() {
		return apply_filters( 'jet-woo-builder/taxonomy-tiles/available-layouts', array(
			'2-1-2'   => array(
				'label'    => esc_html__( 'Layout 1 (5 taxonomy)', 'jet-woo-builder' ),
				'icon'     => 'jet-woo-builder-icon jet-woo-taxonomy-tiles-layout-1',
				'num'      => 5,
				'has_rows' => false,
			),
			'1-1-2-h' => array(
				'label'    => esc_html__( 'Layout 2 (4 taxonomy)', 'jet-woo-builder' ),
				'icon'     => 'jet-woo-builder-icon jet-woo-taxonomy-tiles-layout-2',
				'num'      => 4,
				'has_rows' => false,
			),
			'1-1-2-v' => array(
				'label'    => esc_html__( 'Layout 3 (4 taxonomy)', 'jet-woo-builder' ),
				'icon'     => 'jet-woo-builder-icon jet-woo-taxonomy-tiles-layout-3',
				'num'      => 4,
				'has_rows' => false,
			),
			'1-2'     => array(
				'label'    => esc_html__( 'Layout 4 (3 taxonomy)', 'jet-woo-builder' ),
				'icon'     => 'jet-woo-builder-icon jet-woo-taxonomy-tiles-layout-4',
				'num'      => 3,
				'has_rows' => false,
			),
			'2-3-v'   => array(
				'label'    => esc_html__( 'Layout 5 (5 taxonomy)', 'jet-woo-builder' ),
				'icon'     => 'jet-woo-builder-icon jet-woo-taxonomy-tiles-layout-5',
				'num'      => 5,
				'has_rows' => false,
			),
			'1-2-2'   => array(
				'label'    => esc_html__( 'Layout 6 (5 taxonomy)', 'jet-woo-builder' ),
				'icon'     => 'jet-woo-builder-icon jet-woo-taxonomy-tiles-layout-6',
				'num'      => 5,
				'has_rows' => false,
			),
			'2-x'     => array(
				'label'    => esc_html__( 'Layout 7 (2, 4, 6 taxonomy)', 'jet-woo-builder' ),
				'icon'     => 'jet-woo-builder-icon jet-woo-taxonomy-tiles-layout-7',
				'num'      => 2,
				'has_rows' => false,
			),
			'3-x'     => array(
				'label'    => esc_html__( 'Layout 8 (3, 6, 9 taxonomy)', 'jet-woo-builder' ),
				'icon'     => 'jet-woo-builder-icon jet-woo-taxonomy-tiles-layout-8',
				'num'      => 3,
				'has_rows' => false,
			),
			'4-x'     => array(
				'label'    => esc_html__( 'Layout 9 (4, 8, 12 taxonomy)', 'jet-woo-builder' ),
				'icon'     => 'jet-woo-builder-icon jet-woo-taxonomy-tiles-layout-9',
				'num'      => 4,
				'has_rows' => false,
			),
		) );
	}

	protected function controls_section_general( $css_scheme ) {

		$layout_data       = $this->__layout_data();
		$available_layouts = array();

		foreach ( $layout_data as $key => $data ) {
			$available_layouts[ $key ] = array(
				'title' => $data['label'],
				'icon'  => $data['icon'],
			);
		}

		$this->add_control(
			'layout',
			array(
				'label'       => esc_html__( 'Layout', 'jet-woo-builder' ),
				'label_block' => true,
				'type'        => Controls_Manager::CHOOSE,
				'default'     => '2-1-2',
				'options'     => $available_layouts,
				'render_type' => 'template',
				'classes'     => 'jet-woo-builder-layout-control',
			)
		);

		$this->add_control(
			'count_displaying',
			array(
				'label'   => esc_html__( 'Show Count', 'jet-woo-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'in-content',
				'options' => array(
					'in-content'     => esc_html__( 'In content', 'jet-woo-builder' ),
					'out-of-content' => esc_html__( 'Out of content', 'jet-woo-builder' ),
				),
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'jet-woo-builder' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h5',
				'options' => jet_woo_builder_tools()->get_available_title_html_tags(),
			)
		);

		$this->add_responsive_control(
			'taxonomy_min_height',
			array(
				'label'       => esc_html__( 'Min Height', 'jet-woo-builder' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'unit' => 'px',
					'size' => 300,
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 1200,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['wrap'] => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$main_img_selectors = apply_filters( 'jet-woo-builder/taxonomy-tiles/main-image-selectors', array(
			'{{WRAPPER}} .jet-woo-taxonomy-tiles--layout-2-1-2'   => 'grid-template-columns: 1fr {{SIZE}}{{UNIT}} 1fr; -ms-grid-columns: 1fr {{SIZE}}{{UNIT}} 1fr;',
			'{{WRAPPER}} .jet-woo-taxonomy-tiles--layout-1-1-2-h' => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr 1fr; -ms-grid-columns: {{SIZE}}{{UNIT}} 1fr 1fr;',
			'{{WRAPPER}} .jet-woo-taxonomy-tiles--layout-1-1-2-v' => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr 1fr; -ms-grid-columns: {{SIZE}}{{UNIT}} 1fr 1fr;',
			'{{WRAPPER}} .jet-woo-taxonomy-tiles--layout-1-2'     => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr; -ms-grid-columns: {{SIZE}}{{UNIT}} 1fr',
			'{{WRAPPER}} .jet-woo-taxonomy-tiles--layout-1-2-2'   => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr 1fr; -ms-grid-columns: {{SIZE}}{{UNIT}} 1fr 1fr;',
		) );

		$main_img_conditions = apply_filters( 'jet-woo-builder/taxonomy-tiles/main-image-conditions', array(
			'2-1-2',
			'1-1-2-h',
			'1-1-2-v',
			'1-2',
			'1-2-2',
		) );

		$this->add_control(
			'main_img_width',
			array(
				'label'       => esc_html__( 'Main Box Width', 'jet-woo-builder' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 50,
				),
				'range'       => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => $main_img_selectors,
				'condition'   => array(
					'layout' => $main_img_conditions,
				),
			)
		);

		$this->add_control(
			'rows_num',
			array(
				'label'     => esc_html__( 'Rows Number', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 1,
				'options'   => jet_woo_builder_tools()->get_select_range( 3 ),
				'condition' => array(
					'layout' => array( '2-x', '3-x', '4-x' ),
				),
			)
		);

		$this->add_control(
			'open_new_tab',
			[
				'label'     => esc_html__( 'Open Categories in new window', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off' => esc_html__( 'No', 'jet-woo-builder' ),
				'default'   => '',
			]
		);

		$this->add_control(
			'taxonomy',
			array(
				'type'      => 'select',
				'label'     => esc_html__( 'Show:', 'jet-woo-builder' ),
				'default'   => 'product_cat',
				'options'   => apply_filters( 'jet-woo-builder/jet-woo-taxonomy-tiles/taxonomy_options', array(
					'product_tag' => esc_html__( 'Tags', 'jet-woo-builder' ),
					'product_cat' => esc_html__( 'Categories', 'jet-woo-builder' ),
				) ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Hide Empty', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'hide_default_cat',
			array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Hide Uncategorized', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'taxonomy' => array( 'product_cat' ),
				),
			)
		);

		$this->add_control(
			'show_taxonomy_by_id',
			array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show by IDs', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'taxonomy_id',
			array(
				'type'        => 'text',
				'label_block' => true,
				'label'       => esc_html__( 'Set comma separated IDs list (10, 22, 19 etc.)', 'jet-woo-builder' ),
				'default'     => '',
				'condition'   => array(
					'show_taxonomy_by_id' => 'yes',
				),
			)
		);

		$this->add_control(
			'exclude_taxonomy_id',
			array(
				'type'        => 'text',
				'label_block' => true,
				'label'       => esc_html__( 'Exclude taxonomy by IDs (eg. 10, 22, 19 etc.)', 'jet-woo-builder' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'sort_by',
			array(
				'type'    => 'select',
				'label'   => esc_html__( 'Order by', 'jet-woo-builder' ),
				'default' => 'name',
				'options' => array(
					'name'  => esc_html__( 'Name', 'jet-woo-builder' ),
					'id'    => esc_html__( 'IDs', 'jet-woo-builder' ),
					'count' => esc_html__( 'Count', 'jet-woo-builder' ),
					'rand'  => esc_html__( 'Random', 'jet-woo-builder' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'type'      => 'select',
				'label'     => esc_html__( 'Order', 'jet-woo-builder' ),
				'default'   => 'asc',
				'options'   => jet_woo_builder_tools()->order_arr(),
				'condition' => array(
					'sort_by!' => 'rand',
				),
			)
		);

		$this->add_control(
			'show_taxonomy_count',
			array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Counts', 'jet-woo-builder' ),
				'label_on'     => esc_html__( 'Yes', 'jet-woo-builder' ),
				'label_off'    => esc_html__( 'No', 'jet-woo-builder' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'title_length',
			array(
				'label'       => esc_html__( 'Title Max Length (Words)', 'jet-woo-builder' ),
				'description' => esc_html__( 'Set -1 to show full title and 0 to hide it.', 'jet-woo-builder' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => -1,
				'min'         => -1,
				'max'         => 15,
				'step'        => 1,
			)
		);

		$this->add_control(
			'desc_length',
			array(
				'label'       => esc_html__( 'Description Length', 'jet-woo-builder' ),
				'description' => esc_html__( 'Set -1 to show full description and 0 to hide it.', 'jet-woo-builder' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 50,
				'min'         => -1,
				'max'         => 200,
				'step'        => 1,
			)
		);

		$this->add_control(
			'count_before_text',
			array(
				'type'      => 'text',
				'label'     => esc_html__( 'Count Before Text', 'jet-woo-builder' ),
				'default'   => ! is_rtl() ? '(' : ')',
				'condition' => array(
					'show_taxonomy_count' => array( 'yes' ),
				),
			)
		);

		$this->add_control(
			'count_after_text',
			array(
				'type'      => 'text',
				'label'     => esc_html__( 'Count After Text', 'jet-woo-builder' ),
				'default'   => ! is_rtl() ? ')' : '(',
				'condition' => array(
					'show_taxonomy_count' => array( 'yes' ),
				),
			)
		);

	}

	protected function controls_section_box( $css_scheme ) {

		$this->add_responsive_control(
			'boxes_gap',
			array(
				'label'       => esc_html__( 'Gap Between Boxes', 'jet-woo-builder' ),
				'label_block' => true,
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'unit' => 'px',
					'size' => 1,
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['wrap']        => 'grid-column-gap: {{SIZE}}{{UNIT}}; grid-row-gap: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} ' . $css_scheme['box'] => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'boxes_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['box'],
			)
		);

		$this->add_responsive_control(
			'boxes_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['box'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'boxes_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['box'],
			)
		);

		$this->add_control(
			'boxes_text_alignment_h',
			array(
				'label'     => esc_html__( 'Horizontal Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'   => esc_html__( 'Left', 'jet-woo-builder' ),
					'center' => esc_html__( 'Center', 'jet-woo-builder' ),
					'right'  => esc_html__( 'Right', 'jet-woo-builder' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] => 'text-align: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'boxes_text_alignment_v',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'flex-start',
				'options'   => array(
					'flex-start' => esc_html__( 'Top', 'jet-woo-builder' ),
					'center'     => esc_html__( 'Center', 'jet-woo-builder' ),
					'flex-end'   => esc_html__( 'Bottom', 'jet-woo-builder' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'boxes_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'boxes_overlay_styles',
			array(
				'label'     => esc_html__( 'Box Overlay', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_overlay_style' );

		$this->start_controls_tab(
			'tab_overlay_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-woo-builder' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'boxes_overlay_background_normal',
				'selector' => '{{WRAPPER}} ' . $css_scheme['box'] . ':before',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_overlay_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-woo-builder' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'boxes_overlay_background_hover',
				'selector' => '{{WRAPPER}} ' . $css_scheme['box'] . ':hover:before',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

	}

	protected function controls_section_box_hover( $css_scheme ) {

		$this->add_control(
			'box_hover_content_styles',
			array(
				'label'     => esc_html__( 'Content', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'box_hover_content_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] . ':hover .jet-woo-taxonomy-item__box-inner' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_hover_title_styles',
			array(
				'label'     => esc_html__( 'Title', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'boxes_hover_title_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] . ':hover .jet-woo-taxonomy-item__box-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'boxes_hover_title_decoration',
			array(
				'label'     => esc_html__( 'Text Decoration', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => jet_woo_builder_tools()->get_available_text_decoration_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] . ':hover .jet-woo-taxonomy-item__box-title' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_hover_desc_styles',
			array(
				'label'     => esc_html__( 'Description ', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'boxes_hover_desc_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] . ':hover .jet-woo-taxonomy-item__box-description' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'boxes_hover_desc_decoration',
			array(
				'label'     => esc_html__( 'Text Decoration', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => jet_woo_builder_tools()->get_available_text_decoration_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] . ':hover .jet-woo-taxonomy-item__box-description' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_hover_count_styles',
			array(
				'label'     => esc_html__( 'Count', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'boxes_hover_count_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] . ':hover .jet-woo-taxonomy-item__box-count' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_hover_count_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] . ':hover .jet-woo-taxonomy-item__box-count' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_hover_count_decoration',
			array(
				'label'     => esc_html__( 'Text Decoration', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => jet_woo_builder_tools()->get_available_text_decoration_types(),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] . ':hover .jet-woo-taxonomy-item__box-count' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_box_hover_shadow',
			array(
				'label'     => esc_html__( 'Item', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'boxes_hover_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['box'] . ':hover',
			)
		);

	}

	protected function controls_section_content( $css_scheme ) {

		$this->add_control(
			'content_background',
			array(
				'label'     => esc_html__( 'Background', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box-inner'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'content_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['box-inner'],
			)
		);

		$this->add_control(
			'content_hover_border_color',
			array(
				'label'     => esc_html__( 'Hover Border Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] . ':hover .jet-woo-taxonomy-item__box-inner' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['box-inner'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'content_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['box-inner'],
			)
		);

		$this->add_responsive_control(
			'content_margin',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['box-inner'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'boxes_title_styles',
			array(
				'label'     => esc_html__( 'Title', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'boxes_title_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'boxes_title_typography',
				'scheme'   => Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
			)
		);

		$this->add_responsive_control(
			'boxes_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'boxes_count_styles',
			array(
				'label'     => esc_html__( 'Products Count', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'boxes_count_position',
			array(
				'label'     => esc_html__( 'Count Position', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'top-left'     => esc_html__( 'Top Left', 'jet-woo-builder' ),
					'top-right'    => esc_html__( 'Top Right', 'jet-woo-builder' ),
					'bottom-left'  => esc_html__( 'Bottom Left', 'jet-woo-builder' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'jet-woo-builder' ),
				),
				'default'   => 'top-right',
				'condition' => array(
					'count_displaying' => array( 'out-of-content' ),
				),
			)
		);

		$this->add_control(
			'boxes_count_display',
			array(
				'label'     => esc_html__( 'Count Display', 'jet-woo-builder' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => jet_woo_builder_tools()->get_available_display_types(),
				'default'   => 'inline-block',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'display: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['title'] => 'display: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'boxes_count_min_width',
			array(
				'label'      => esc_html__( 'Count Minimal Width (px)', 'jet-woo-builder' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'min-width: {{SIZE}}{{UNIT}}; text-align: center;',
				),
				'condition'  => array(
					'count_displaying' => array( 'out-of-content' ),
				),
			)
		);

		$this->add_control(
			'boxes_count_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'boxes_count_bg',
			array(
				'label'     => esc_html__( 'Background', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'boxes_count_typography',
				'scheme'   => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['count'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'boxes_count_border',
				'label'       => esc_html__( 'Border', 'jet-woo-builder' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['count'],
			)
		);

		$this->add_control(
			'boxes_count_hover_border_color',
			array(
				'label'     => esc_html__( 'Hover Border Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['box'] . ':hover .jet-woo-taxonomy-item__box-count' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'boxes_count_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'boxes_count_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['count'],
			)
		);

		$this->add_responsive_control(
			'boxes_count_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'boxes_count_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['count'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'boxes_text_style',
			array(
				'label'     => esc_html__( 'Description', 'jet-woo-builder' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'boxes_text_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-woo-builder' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'boxes_text_typography',
				'scheme'   => Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}}  ' . $css_scheme['desc'],
			)
		);

		$this->add_responsive_control(
			'boxes_text_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-woo-builder' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['desc'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

	}

	protected function content_template() {}

}
