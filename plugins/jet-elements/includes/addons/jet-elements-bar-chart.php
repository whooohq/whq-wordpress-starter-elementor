<?php
/**
 * Class: Jet_Elements_Bar_Chart
 * Name: Bar Chart
 * Slug: jet-bar-chart
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color as Scheme_Color;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Jet Bar Chart Widget.
 */
class Jet_Elements_Bar_Chart extends Jet_Elements_Base {

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'jet-bar-chart';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Bar Chart', 'jet-elements' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'jet-elements-icon-bar-chart';
	}
	
	/**
	 * Retrieve the help UPL for the widget.
	 *
	 * @return string
	 */
	public function get_jet_help_url() {
		return false;
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'chart-js' );
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'cherry' );
	}

	/**
	 * Register widget controls.
	 */
	protected function _register_controls() {
		$css_scheme = apply_filters(
			'jet-elements/bar-chart/css-scheme',
			array(
				'container' => '.jet-bar-chart-container',
			)
		);
		
		/**
		 * `Chart Data` Section
		 */
		$this->start_controls_section(
			'section_chart_data',
			array(
				'label' => esc_html__( 'Chart Data', 'jet-elements' ),
			)
		);
		
		$this->add_control(
			'type',
			array(
				'label'   => esc_html__( 'Bar Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bar',
				'options' => array(
					'bar'           => esc_html__( 'Vertical Bar', 'jet-elements' ),
					'horizontalBar' => esc_html__( 'Horizontal Bar', 'jet-elements' ),
				),
			)
		);
		
		$this->add_control(
			'labels',
			array(
				'label'       => esc_html__( 'Labels', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'March, April, May', 'jet-elements' ),
				'description' => esc_html__( 'Write multiple label by semicolon separated(,). Example: March, April, May etc', 'jet-elements' ),
				'dynamic'     => array( 'active' => true ),
			)
		);
		
		$this->add_control(
			'axis_range',
			array(
				'label'       => esc_html__( 'Scale Axis Range', 'jet-elements' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 10,
				'description' => esc_html__( 'User defined maximum number for the scale, overrides maximum value from data.', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);
		
		$this->add_control(
			'step_size',
			array(
				'label'       => esc_html__( 'Step Size', 'jet-elements' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'step'        => 1,
				'description' => esc_html__( 'User defined fixed step size for the scale.', 'jet-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater = new Repeater();
		
		$repeater->start_controls_tabs( 'bar_tabs' );
		
		$repeater->start_controls_tab(
			'bar_tab_content',
			array(
				'label' => esc_html__( 'Content', 'jet-elements' ),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => esc_html__( 'Label', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'data',
			array(
				'label'       => esc_html__( 'Data', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter data values by semicolon separated(,). Example: 2, 4, 8 etc', 'jet-elements' ),
				'dynamic'     => array( 'active' => true ),
			)
		);
		
		$repeater->end_controls_tab();
		
		$repeater->start_controls_tab(
			'bar_tab_style',
			array(
				'label' => esc_html__( 'Style', 'jet-elements' ),
			)
		);
		
		$repeater->add_control(
			'bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			)
		);
		
		$repeater->add_control(
			'bg_hover_color',
			array(
				'label' => esc_html__( 'Background Hover Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			)
		);
		
		$repeater->add_control(
			'border_color',
			array(
				'label' => esc_html__( 'Border Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			)
		);
		
		$repeater->add_control(
			'border_hover_color',
			array(
				'label' => esc_html__( 'Border Hover Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			)
		);
		
		$repeater->end_controls_tab();

		$this->add_control(
			'chart_data',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'label'              => esc_html__( 'Google', 'jet-elements' ),
						'data'               => esc_html__( '2, 4, 8', 'jet-elements' ),
						'bg_color'           => 'rgba(221,75,57,0.4)',
						'bg_hover_color'     => '#dd4b39',
						'border_color'       => '#dd4b39',
						'border_hover_color' => '#dd4b39',
					),
					array(
						'label'              => esc_html__( 'Facebook', 'jet-elements' ),
						'data'               => esc_html__( '1, 5, 3', 'jet-elements' ),
						'bg_color'           => 'rgba(59,89,152,0.4)',
						'bg_hover_color'     => '#3b5998',
						'border_color'       => '#3b5998',
						'border_hover_color' => '#3b5998',
					),
					array(
						'label'              => esc_html__( 'Twitter', 'jet-elements' ),
						'data'               => esc_html__( '5, 9, 5', 'jet-elements' ),
						'bg_color'           => 'rgba(85,172,238,0.4)',
						'bg_hover_color'     => '#55acee',
						'border_color'       => '#55acee',
						'border_hover_color' => '#55acee',
					),
				),
				'title_field' => '{{{ label }}}',
			)
		);

		$this->end_controls_section();

		/**
		 * `Settings` Section
		 */
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'jet-elements' ),
			)
		);

		$this->add_responsive_control(
			'chart_height',
			array(
				'label'       => esc_html__( 'Chart Height', 'jet-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 100,
						'max' => 1200,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'height: {{SIZE}}{{UNIT}};',
				),
				'render_type' => 'template',
			)
		);
		
		$this->add_control(
			'chart_grid_display',
			array(
				'label'        => esc_html__( 'Grid Lines', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'return_value' => 'true',
			)
		);
		
		$this->add_control(
			'chart_labels_display',
			array(
				'label'        => esc_html__( 'Labels', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'return_value' => 'true',
			)
		);
		
		$this->add_control(
			'chart_tooltip_enabled',
			array(
				'label'        => esc_html__( 'Tooltips', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'chart_legend_heading',
			array(
				'label'     => esc_html__( 'Legend', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'chart_legend_display',
			array(
				'label'        => esc_html__( 'Display', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'true',
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'chart_legend_position',
			array(
				'label'     => esc_html__( 'Position', 'jet-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'top',
				'options'   => array(
					'top'    => esc_html__( 'Top', 'jet-elements' ),
					'left'   => esc_html__( 'Left', 'jet-elements' ),
					'bottom' => esc_html__( 'Bottom', 'jet-elements' ),
					'right'  => esc_html__( 'Right', 'jet-elements' ),
				),
				'condition' => array(
					'chart_legend_display' => 'true',
				),
			)
		);

		$this->add_control(
			'chart_legend_reverse',
			array(
				'label'        => esc_html__( 'Revers', 'jet-elements' ),
				'description'  => esc_html__( 'Legend will show datasets in reverse order.', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'true',
				'condition'    => array(
					'chart_legend_display'  => 'true',
				),
			)
		);

		$this->add_control(
			'chart_tooltips_heading',
			array(
				'label'     => esc_html__( 'Tooltips', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'chart_tooltip_enabled'  => 'true',
				),
			)
		);

		$this->add_control(
			'chart_tooltip_prefix',
			array(
				'label'     => esc_html__( 'Prefix', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'chart_tooltip_enabled'  => 'true',
				),
			)
		);

		$this->add_control(
			'chart_tooltip_suffix',
			array(
				'label'     => esc_html__( 'Suffix', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'chart_tooltip_enabled'  => 'true',
				),
			)
		);

		$this->add_control(
			'chart_tooltip_separator',
			array(
				'label'     => esc_html__( 'Thousand Separator', 'jet-elements' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'chart_tooltip_enabled'  => 'true',
				),
			)
		);

		$this->end_controls_section();
		
		/**
		 * `Chart` Style Tab Section
		 */
		$this->_start_controls_section(
			'section_chart_style',
			array(
				'label' => esc_html__( 'Chart', 'jet-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->_add_control(
			'chart_border_width',
			array(
				'label' => esc_html__( 'Border Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
			),
			25
		);

		$this->_add_control(
			'chart_grid_color',
			array(
				'label'   => esc_html__( 'Grid Color', 'jet-elements' ),
				'type'    => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.05)',
			),
			25
		);

		$this->_end_controls_section();
		
		/**
		 * `Labels` Style Section
		 */
		$this->_start_controls_section(
			'section_chart_labels_style',
			array(
				'label'     => esc_html__( 'Labels', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'chart_labels_display' => 'true',
				),
			)
		);
		
		$this->_add_control(
			'chart_labels_font_family',
			array(
				'label'   => esc_html__( 'Font Family', 'jet-elements' ),
				'type'    => Controls_Manager::FONT,
				'default' => '',
			),
			50
		);
		
		$this->_add_control(
			'chart_labels_font_size',
			array(
				'label'   => esc_html__( 'Font Size', 'jet-elements' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
			),
			50
		);
		
		$typo_weight_options = array(
			'' => esc_html__( 'Default', 'jet-elements' ),
		);
		
		foreach ( array_merge( [ 'normal', 'bold' ], range( 100, 900, 100 ) ) as $weight ) {
			$typo_weight_options[ $weight ] = ucfirst( $weight );
		}
		
		$this->_add_control(
			'chart_labels_font_weight',
			array(
				'label'   => esc_html__( 'Font Weight', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $typo_weight_options,
			),
			50
		);
		
		$this->_add_control(
			'chart_labels_font_style',
			array(
				'label'   => esc_html__( 'Font Style', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => esc_html__( 'Default', 'jet-elements' ),
					'normal'  => esc_attr_x( 'Normal', 'Typography Control', 'jet-elements' ),
					'italic'  => esc_attr_x( 'Italic', 'Typography Control', 'jet-elements' ),
					'oblique' => esc_attr_x( 'Oblique', 'Typography Control', 'jet-elements' ),
				),
			),
			50
		);
		
		$this->_add_control(
			'chart_labels_font_color',
			array(
				'label' => esc_html__( 'Font Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			),
			25
		);
		
		$this->_end_controls_section();

		/**
		 * `Legend` Style Section
		 */
		$this->_start_controls_section(
			'section_chart_legend_style',
			array(
				'label'     => esc_html__( 'Legend', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'chart_legend_display' => 'true',
				),
			)
		);

		$this->_add_control(
			'chart_legend_box_width',
			array(
				'label' => esc_html__( 'Box Width', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
			),
			25
		);
		
		$this->_add_control(
			'chart_legend_font_family',
			array(
				'label'   => esc_html__( 'Font Family', 'jet-elements' ),
				'type'    => Controls_Manager::FONT,
				'default' => '',
			),
			50
		);
		
		$this->_add_control(
			'chart_legend_font_size',
			array(
				'label' => esc_html__( 'Font Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
			),
			50
		);
		
		$this->_add_control(
			'chart_legend_font_weight',
			array(
				'label'   => esc_html__( 'Font Weight', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $typo_weight_options,
			),
			50
		);
		
		$this->_add_control(
			'chart_legend_font_style',
			array(
				'label'   => esc_html__( 'Font Style', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => esc_html__( 'Default', 'jet-elements' ),
					'normal'  => esc_attr_x( 'Normal', 'Typography Control', 'jet-elements' ),
					'italic'  => esc_attr_x( 'Italic', 'Typography Control', 'jet-elements' ),
					'oblique' => esc_attr_x( 'Oblique', 'Typography Control', 'jet-elements' ),
				),
			),
			50
		);

		$this->_add_control(
			'chart_legend_font_color',
			array(
				'label' => esc_html__( 'Font Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			),
			25
		);

		$this->_end_controls_section();

		/**
		 * `Tooltips` Style Section
		 */
		$this->_start_controls_section(
			'section_chart_tooltips_style',
			array(
				'label'     => esc_html__( 'Tooltips', 'jet-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'chart_tooltip_enabled' => 'true',
				),
			)
		);

		$this->_add_control(
			'chart_tooltip_bg_color',
			array(
				'label' => esc_html__( 'Background Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			),
			25
		);
		
		$this->_add_control(
			'chart_tooltip_style_title_heading',
			array(
				'label'     => esc_html__( 'Title', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);
		
		$this->_add_control(
			'chart_tooltip_title_font_family',
			array(
				'label'   => esc_html__( 'Font Family', 'jet-elements' ),
				'type'    => Controls_Manager::FONT,
				'default' => '',
			),
			50
		);
		
		$this->_add_control(
			'chart_tooltip_title_font_size',
			array(
				'label' => esc_html__( 'Font Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
			),
			50
		);
		
		$this->_add_control(
			'chart_tooltip_title_font_weight',
			array(
				'label'   => esc_html__( 'Font Weight', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $typo_weight_options,
			),
			50
		);
		
		$this->_add_control(
			'chart_tooltip_title_font_style',
			array(
				'label'   => esc_html__( 'Font Style', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => esc_html__( 'Default', 'jet-elements' ),
					'normal'  => esc_attr_x( 'Normal', 'Typography Control', 'jet-elements' ),
					'italic'  => esc_attr_x( 'Italic', 'Typography Control', 'jet-elements' ),
					'oblique' => esc_attr_x( 'Oblique', 'Typography Control', 'jet-elements' ),
				),
			),
			50
		);
		
		$this->_add_control(
			'chart_tooltip_title_font_color',
			array(
				'label' => esc_html__( 'Font Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			),
			25
		);
		
		$this->_add_control(
			'chart_tooltip_style_body_heading',
			array(
				'label'     => esc_html__( 'Body', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			),
			25
		);
		
		$this->_add_control(
			'chart_tooltip_font_family',
			array(
				'label'   => esc_html__( 'Font Family', 'jet-elements' ),
				'type'    => Controls_Manager::FONT,
				'default' => '',
			),
			50
		);
		
		$this->_add_control(
			'chart_tooltip_font_size',
			array(
				'label' => esc_html__( 'Font Size', 'jet-elements' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
			),
			50
		);
		
		$this->_add_control(
			'chart_tooltip_font_weight',
			array(
				'label'   => esc_html__( 'Font Weight', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $typo_weight_options,
			),
			50
		);
		
		$this->_add_control(
			'chart_tooltip_font_style',
			array(
				'label'   => esc_html__( 'Font Style', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => esc_html__( 'Default', 'jet-elements' ),
					'normal'  => esc_attr_x( 'Normal', 'Typography Control', 'jet-elements' ),
					'italic'  => esc_attr_x( 'Italic', 'Typography Control', 'jet-elements' ),
					'oblique' => esc_attr_x( 'Oblique', 'Typography Control', 'jet-elements' ),
				),
			),
			50
		);

		$this->_add_control(
			'chart_tooltip_font_color',
			array(
				'label' => esc_html__( 'Font Color', 'jet-elements' ),
				'type'  => Controls_Manager::COLOR,
			),
			25
		);

		$this->_end_controls_section();
	}
	
	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$this->_context = 'render';
		$this->_open_wrap();

		$settings          = $this->get_settings_for_display();
		$data_chart        = $this->get_chart_data();
		$data_options      = $this->get_chart_options();
		$tooltip_prefix    = isset( $settings['chart_tooltip_prefix'] ) ? $settings['chart_tooltip_prefix'] : '';
		$tooltip_suffix    = isset( $settings['chart_tooltip_suffix'] ) ? $settings['chart_tooltip_suffix'] : '';
		$tooltip_separator = isset( $settings['chart_tooltip_separator'] ) ? $settings['chart_tooltip_separator'] : '';

		$this->add_render_attribute( [
				'container' => array(
					'class'         => 'jet-bar-chart-container',
					'data-settings' =>
						esc_attr( json_encode( array(
							'type'    => $settings['type'],
							'data'    => array(
								'labels'   => explode(',', $settings['labels']),
								'datasets' => $data_chart,
							),
							'options' => $data_options
						) ) ),
					'data-tooltip-prefix'    => $tooltip_prefix,
					'data-tooltip-suffix'    => $tooltip_suffix,
					'data-tooltip-separator' => $tooltip_separator,
				),
				'canvas' => array(
					'class' => 'jet-bar-chart',
					'role'  => 'img',
				),
		] );
		
		?>
		<div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
			<canvas <?php echo $this->get_render_attribute_string( 'canvas' ); ?>></canvas>
		</div>
		<?php
		$this->_close_wrap();
	}
	
	/**
	 * Get prepare chart data.
	 *
	 * @return array
	 */
	public function get_chart_data() {
		$settings = $this->get_settings_for_display();
		
		$datasets = array();
		$chart_data = $settings['chart_data'];
		$chart_data = apply_filters( 'jet-elements/widget/loop-items', $chart_data, 'chart_data', $this );
		
		foreach ( $chart_data as $item_data ) {
			$item_data['label']                = ! empty( $item_data['label'] ) ? $item_data['label'] : '';
			$item_data['data']                 = ! empty( $item_data['data'] ) ? array_map('floatval', explode(',', $item_data['data'])) : '';
			$item_data['backgroundColor']      = ! empty( $item_data['bg_color'] ) ? $item_data['bg_color'] : '#cecece';
			$item_data['hoverBackgroundColor'] = ! empty( $item_data['bg_hover_color'] ) ? $item_data['bg_hover_color'] : '#7a7a7a';
			$item_data['borderColor']          = ! empty( $item_data['border_color'] ) ? $item_data['border_color'] : '#7a7a7a';
			$item_data['hoverBorderColor']     = ! empty( $item_data['border_hover_color'] ) ? $item_data['border_hover_color'] : '#7a7a7a';
			$item_data['borderWidth']          = ( '' !== $settings['chart_border_width']['size'] ) ? $settings['chart_border_width']['size'] : 1;
			
			$datasets[] = $item_data;
		}
		
		return $datasets;
	}
	
	/**
	 * Get prepare chart options.
	 *
	 * @return array
	 */
	public function get_chart_options() {
		$settings = $this->get_settings_for_display();
		
		$labels_display   = filter_var( $settings['chart_labels_display'], FILTER_VALIDATE_BOOLEAN );
		$tooltips_enabled = filter_var( $settings['chart_tooltip_enabled'], FILTER_VALIDATE_BOOLEAN );
		$legend_display   = filter_var( $settings['chart_legend_display'], FILTER_VALIDATE_BOOLEAN );
		$grid_display     = filter_var( $settings['chart_grid_display'], FILTER_VALIDATE_BOOLEAN );
		
		$options = array(
			'tooltips' => array(
				'enabled' => $tooltips_enabled,
			),
			'legend' => array(
				'display'  => $legend_display,
				'position' => ! empty( $settings['chart_legend_position'] ) ? $settings['chart_legend_position'] : 'top',
				'reverse'  => filter_var( $settings['chart_legend_reverse'], FILTER_VALIDATE_BOOLEAN ),
			),
			'maintainAspectRatio' => false,
		);
		
		$tooltip_title_style_dictionary = array(
			'titleFontFamily'  => 'chart_tooltip_title_font_family',
			'titleFontSize'    => 'chart_tooltip_title_font_size',
			'titleFontStyle'   => array( 'chart_tooltip_title_font_style', 'chart_tooltip_title_font_weight' ),
			'titleFontColor'   => 'chart_tooltip_title_font_color',
		);
		
		$tooltip_style_dictionary = array(
			'bodyFontFamily'  => 'chart_tooltip_font_family',
			'bodyFontSize'    => 'chart_tooltip_font_size',
			'bodyFontStyle'   => array( 'chart_tooltip_font_style', 'chart_tooltip_font_weight' ),
			'bodyFontColor'   => 'chart_tooltip_font_color',
		);
		
		if ( $tooltips_enabled ) {

			if ( ! empty( $settings['chart_tooltip_bg_color'] ) ) {
				$options['tooltips'] = array(
					'backgroundColor' => $settings['chart_tooltip_bg_color'],
				);
			}
			
			foreach ( $tooltip_title_style_dictionary as $style_property => $setting_name ) {
				
				if ( is_array( $setting_name ) ) {
					$style_value = $this->get_chart_font_style_string( $setting_name );
					
					if ( ! empty( $style_value ) ) {
						$options['tooltips'][ $style_property ] = $style_value;
					}
				} else {
					if ( ! empty( $settings[ $setting_name ] ) ) {
						if ( is_array( $settings[ $setting_name ] ) ) {
							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
								$options['tooltips'][ $style_property ] = $settings[ $setting_name ]['size'];
							}
						} else {
							$options['tooltips'][ $style_property ] = $settings[ $setting_name ];
						}
					}
				}
			}
			
			foreach ( $tooltip_style_dictionary as $style_property => $setting_name ) {
				
				if ( is_array( $setting_name ) ) {
					$style_value = $this->get_chart_font_style_string( $setting_name );
					
					if ( ! empty( $style_value ) ) {
						$options['tooltips'][ $style_property ] = $style_value;
					}
				} else {
					if ( ! empty( $settings[ $setting_name ] ) ) {
						if ( is_array( $settings[ $setting_name ] ) ) {
							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
								$options['tooltips'][ $style_property ] = $settings[ $setting_name ]['size'];
							}
						} else {
							$options['tooltips'][ $style_property ] = $settings[ $setting_name ];
						}
					}
				}
			}
		}
		
		$legend_style = array();
		
		$legend_style_dictionary = array(
			'boxWidth'   => 'chart_legend_box_width',
			'fontFamily' => 'chart_legend_font_family',
			'fontSize'   => 'chart_legend_font_size',
			'fontStyle'  => array( 'chart_legend_font_style', 'chart_legend_font_weight' ),
			'fontColor'  => 'chart_legend_font_color',
		);
		
		if ( $legend_display ) {
			
			foreach ( $legend_style_dictionary as $style_property => $setting_name ) {
				
				if ( is_array( $setting_name ) ) {
					$style_value = $this->get_chart_font_style_string( $setting_name );
					
					if ( ! empty( $style_value ) ) {
						$legend_style[ $style_property ] = $style_value;
					}
				} else {
					if ( ! empty( $settings[ $setting_name ] ) ) {
						if ( is_array( $settings[ $setting_name ] ) ) {
							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
								$legend_style[ $style_property ] = $settings[ $setting_name ]['size'];
							}
						} else {
							$legend_style[ $style_property ] = $settings[ $setting_name ];
						}
					}
				}
			}
			
			if ( ! empty( $legend_style ) ) {
				$options['legend']['labels'] = $legend_style;
			}
		}
		
		if ( $grid_display ) {
			$options['scales'] = array(
				'yAxes' => array( array(
					'ticks'       => array(
						'display'     => $labels_display,
						'beginAtZero' => true,
						'max'         => isset( $settings['axis_range'] ) ? intval( $settings['axis_range'] ) : 10,
						'stepSize'    => isset( $settings['step_size'] ) ? intval( $settings['step_size'] ) : 1,
					),
					'gridLines'   => array(
						'drawBorder' => false,
						'color'      => isset( $settings['chart_grid_color'] ) ? $settings['chart_grid_color'] : 'rgba(0,0,0,0.05)',
					)
				) ),
				'xAxes' => array( array(
					'ticks'     => array(
						'display'     => $labels_display,
						'beginAtZero' => true,
						'max'         => isset( $settings['axis_range'] ) ? intval( $settings['axis_range'] ) : 10,
						'stepSize'    => isset( $settings['step_size'] ) ? intval( $settings['step_size'] ) : 1,
					),
					'gridLines' => array(
						'drawBorder' => false,
						'color'      => isset( $settings['chart_grid_color'] ) ? $settings['chart_grid_color'] : 'rgba(0,0,0,0.05)',
					)
				) )
			);
		} else {
			$options['scales'] = array(
				'yAxes' => array( array(
					'ticks' => array(
						'display'     => $labels_display,
						'beginAtZero' => true,
					),
					'gridLines' => array(
						'display'    => false,
					)
				) ),
				'xAxes' => array( array(
					'ticks' => array(
						'display'     => $labels_display,
						'beginAtZero' => true,
					),
					'gridLines' => array(
						'display'    => false,
					)
				) )
			);
		}
		
		$labels_style = array();
		
		$labels_style_dictionary = array(
			'fontFamily' => 'chart_labels_font_family',
			'fontSize'   => 'chart_labels_font_size',
			'fontStyle'  => array( 'chart_labels_font_style', 'chart_labels_font_weight' ),
			'fontColor'  => 'chart_labels_font_color',
		);
		
		if ( $labels_display ) {
			
			foreach ( $labels_style_dictionary as $style_property => $setting_name ) {
				
				if ( is_array( $setting_name ) ) {
					$style_value = $this->get_chart_font_style_string( $setting_name );
					
					if ( ! empty( $style_value ) ) {
						$labels_style[ $style_property ] = $style_value;
					}
				} else {
					if ( ! empty( $settings[ $setting_name ] ) ) {
						if ( is_array( $settings[ $setting_name ] ) ) {
							if ( ! empty( $settings[ $setting_name ]['size'] ) ) {
								$labels_style[ $style_property ] = $settings[ $setting_name ]['size'];
							}
						} else {
							$labels_style[ $style_property ] = $settings[ $setting_name ];
						}
					}
				}
			}
			
			if ( ! empty( $labels_style ) ) {
				$options['scales']['xAxes'][0]['ticks'] = array_merge( $options['scales']['xAxes'][0]['ticks'], $labels_style );
				$options['scales']['yAxes'][0]['ticks'] = array_merge( $options['scales']['yAxes'][0]['ticks'], $labels_style );
			}
		}
		
		return $options;
	}
	
	/**
	 * Get font style string.
	 *
	 * @param array $settings_names Settings names.
	 *
	 * @return string
	 */
	public function get_chart_font_style_string( $settings_names = array() ) {
		if ( ! is_array( $settings_names ) ) {
			return '';
		}
		
		$settings = $this->get_settings_for_display();
		
		$font_styles = array();
		
		foreach ( $settings_names as $setting_name ) {
			if ( ! empty( $settings[ $setting_name ] ) ) {
				$font_styles[] = $settings[ $setting_name ];
			}
		}
		
		if ( empty( $font_styles ) ) {
			return '';
		}
		
		$font_styles = array_unique( $font_styles );
		
		return join( ' ', $font_styles );
	}
	
}
