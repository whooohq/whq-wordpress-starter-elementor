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

if ( ! class_exists( 'Jet_Tricks_Elementor_Column_Extension' ) ) {

	/**
	 * Define Jet_Tricks_Elementor_Column_Extension class
	 */
	class Jet_Tricks_Elementor_Column_Extension {

		/**
		 * Columns Data
		 *
		 * @var array
		 */
		public $columns_data = array();

		/**
		 * [$sticky_columns description]
		 * @var array
		 */
		public $sticky_columns = array();

		/**
		 * [$avaliable_extensions description]
		 * @var array
		 */
		public $avaliable_extensions = array();

		/**
		 * Has sticky section.
		 *
		 * @var bool
		 */
		private $has_sticky = false;

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Init Handler
		 */
		public function init() {

			$this->avaliable_extensions = jet_tricks_settings()->get( 'avaliable_extensions', jet_tricks_settings()->default_avaliable_extensions );

			$column_sticky = isset( $this->avaliable_extensions['column_sticky'] ) ? $this->avaliable_extensions['column_sticky'] : true;

			if ( ! filter_var( $column_sticky, FILTER_VALIDATE_BOOLEAN ) ) {
				return false;
			}

			add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'after_column_section_layout' ), 10, 2 );

			add_action( 'elementor/frontend/column/before_render', array( $this, 'column_before_render' ) );

			add_action( 'elementor/frontend/element/before_render', array( $this, 'column_before_render' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		}

		/**
		 * After column_layout callback
		 *
		 * @param  object $obj
		 * @param  array $args
		 * @return void
		 */
		public function after_column_section_layout( $obj, $args ) {

			if ( \Elementor\Plugin::$instance->breakpoints && method_exists( \Elementor\Plugin::$instance->breakpoints, 'get_active_breakpoints')) {
				$active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
				$breakpoints_list   = array();

				foreach ($active_breakpoints as $key => $value) {
					$breakpoints_list[$key] = $value->get_label();
				}

				$breakpoints_list['desktop'] = 'Desktop';
				$breakpoints_list            = array_reverse($breakpoints_list);
			} else {
				$breakpoints_list = array(
					'desktop' => 'Desktop',
					'tablet'  => 'Tablet',
					'mobile'  => 'Mobile'
				);
			}

			$obj->start_controls_section(
				'column_jet_tricks',
				array(
					'label' => esc_html__( 'JetTricks', 'jet-tricks' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);

			$obj->add_control(
				'jet_tricks_column_sticky',
				array(
					'label'        => esc_html__( 'Sticky Column', 'jet-tricks' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-tricks' ),
					'label_off'    => esc_html__( 'No', 'jet-tricks' ),
					'return_value' => 'true',
					'default'      => 'false',
				)
			);

			$obj->add_control(
				'jet_tricks_top_spacing',
				array(
					'label'   => esc_html__( 'Top Spacing', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 50,
					'min'     => 0,
					'max'     => 500,
					'step'    => 1,
					'condition' => array(
						'jet_tricks_column_sticky' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_bottom_spacing',
				array(
					'label'   => esc_html__( 'Bottom Spacing', 'jet-tricks' ),
					'type'    => Elementor\Controls_Manager::NUMBER,
					'default' => 50,
					'min'     => 0,
					'max'     => 500,
					'step'    => 1,
					'condition' => array(
						'jet_tricks_column_sticky' => 'true',
					),
				)
			);

			$obj->add_control(
				'jet_tricks_column_sticky_on',
				array(
					'label'    => __( 'Sticky On', 'jet-tricks' ),
					'type'     => Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'label_block' => 'true',
					'default' => array(
						'desktop',
						'tablet',
					),
					'options' => $breakpoints_list,
					'condition' => array(
						'jet_tricks_column_sticky' => 'true',
					),
					'render_type'        => 'none',
				)
			);

			$obj->end_controls_section();
		}

		/**
		 * [column_before_render description]
		 * @param  [type] $element [description]
		 * @return [type]          [description]
		 */
		public function column_before_render( $element ) {
			$data     = $element->get_data();
			$type     = isset( $data['elType'] ) ? $data['elType'] : 'column';
			$settings = $data['settings'];

			if ( 'column' !== $type ) {
				return false;
			}

			if ( isset( $settings['jet_tricks_column_sticky'] ) ) {
				$column_settings = array(
					'id'            => $data['id'],
					'sticky'        => filter_var( $settings['jet_tricks_column_sticky'], FILTER_VALIDATE_BOOLEAN ),
					'topSpacing'    => isset( $settings['jet_tricks_top_spacing'] ) ? $settings['jet_tricks_top_spacing'] : 50,
					'bottomSpacing' => isset( $settings['jet_tricks_bottom_spacing'] ) ? $settings['jet_tricks_bottom_spacing'] : 50,
					'stickyOn'      => isset( $settings['jet_tricks_column_sticky_on'] ) ? $settings['jet_tricks_column_sticky_on'] : array( 'desktop', 'tablet' ),
				);

				if ( filter_var( $settings['jet_tricks_column_sticky'], FILTER_VALIDATE_BOOLEAN ) ) {

					$element->add_render_attribute( '_wrapper', array(
						'class'         => 'jet-sticky-column',
						'data-jet-settings' => json_encode( $column_settings ),
					) );

					$this->sticky_columns[] = $data['id'];
				}

				$this->columns_data[ $data['id'] ] = $column_settings;
			}
		}

		/**
		 * [enqueue_scripts description]
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			if ( ! empty( $this->sticky_columns ) ) {
				wp_enqueue_script(
					'jet-resize-sensor',
					jet_tricks()->plugin_url( 'assets/js/lib/resize-sensor/ResizeSensor.min.js' ),
					array( 'jquery' ),
					'1.7.0',
					true
				);

				wp_enqueue_script(
					'jet-sticky-sidebar',
					jet_tricks()->plugin_url( 'assets/js/lib/sticky-sidebar/sticky-sidebar.min.js' ),
					array( 'jquery', 'jet-resize-sensor', 'imagesloaded' ),
					'3.3.1',
					true
				);
			}

			jet_tricks_assets()->elements_data['columns'] = $this->columns_data;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
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
 * Returns instance of Jet_Tricks_Elementor_Column_Extension
 *
 * @return object
 */
function jet_tricks_elementor_column_extension() {
	return Jet_Tricks_Elementor_Column_Extension::get_instance();
}
