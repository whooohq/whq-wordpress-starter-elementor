<?php
/**
 * Class: Jet_Elements_Advanced_Map
 * Name: Advanced Map
 * Slug: jet-map
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
use Elementor\Utils;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Jet_Elements_Advanced_Map extends Jet_Elements_Base {

	public $geo_api_url = 'https://maps.googleapis.com/maps/api/geocode/json';

	public function get_name() {
		return 'jet-map';
	}

	public function get_title() {
		return esc_html__( 'Advanced Map', 'jet-elements' );
	}

	public function get_icon() {
		return 'jet-elements-icon-map';
	}

	public function get_jet_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/how-to-add-google-map-with-pinned-locations-to-elementor-using-jetelements-advanced-map-widget/';
	}

	public function get_categories() {
		return array( 'cherry' );
	}

	public function get_script_depends() {

		$api_disabled = jet_elements_settings()->get( 'disable_api_js', [ 'disable' => 'false' ] );

		if ( empty( $api_disabled ) || 'true' !== $api_disabled['disable'] ) {
			return array( 'google-maps-api' );
		} else {
			return array();
		}
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_map_settings',
			array(
				'label' => esc_html__( 'Map Settings', 'jet-elements' ),
			)
		);

		$key = jet_elements_settings()->get( 'api_key' );

		if ( ! $key ) {

			$this->add_control(
			'set_key',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => sprintf(
						esc_html__( 'Please set Google maps API key before using this widget. You can create own API key  %1$s. Paste created key on %2$s', 'jet-elements' ),
						'<a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">' . esc_html__( 'here', 'jet-elements' ) . '</a>',
						'<a target="_blank" href="' . jet_elements_settings()->get_settings_page_link( 'integrations' ) . '">' . esc_html__( 'settings page', 'jet-elements' ) . '</a>'
					)
				)
			);
		}

		$default_address      = esc_html__( 'London Eye, London, United Kingdom', 'jet-elements' );
		$default_lat_long     = '51.503399;-0.119519';
		$default_dms_lat_long = "51° 30' 12.2364\" N;0° 7' 10.2684\" W";

		$this->add_control(
			'map_center_type',
			array(
				'label'   => esc_html__( 'Map center type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => array(
					'0' => esc_html__( 'Coordinates', 'jet-elements' ),
					'1' => esc_html__( 'Address', 'jet-elements' ),
					'2' => esc_html__( 'DMS Format of Coordinates', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'map_center_lat_lng',
			array(
				'label'       => esc_html__( 'Map Center Coordinates', 'jet-elements' ),
				'description' => esc_html__( 'To get an address from latitude and longitude coordinates from one meta field, combine coordinates names with the ";" sign. For example lat;lng. Where latitude always goes first. The latitude value range is from -90 to 90. The longitude value outside range is from -180 to 180.', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => $default_lat_long,
				'default'     => $default_lat_long,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'map_center_type' => '0',
				),
			)
		);

		$this->add_control(
			'map_center_dms',
			array(
				'label'       => esc_html__( 'DMS Coordinates', 'jet-elements' ),
				'description' => esc_html__( 'To get an address from latitude and longitude coordinates of dms format from one meta field, combine coordinates names with the ";" sign. For example: 51° 30\' 12.2364" N;0° 7\' 10.2684" W. Where latitude always goes first.', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => $default_dms_lat_long,
				'default'     => $default_dms_lat_long,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'map_center_type' => '2',
				),
			)
		);

		$this->add_control(
			'map_center',
			array(
				'label'       => esc_html__( 'Map Center Address', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => $default_address,
				'default'     => $default_address,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'map_center_type' => '1',
				),
			)
		);

		$this->add_control(
			'zoom',
			array(
				'label'      => esc_html__( 'Initial Zoom', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => 'zoom',
					'size' => 11,
				),
				'range'      => array(
					'zoom' => array(
						'min' => 1,
						'max' => 18,
					),
				),
				'dynamic' => version_compare( ELEMENTOR_VERSION, '2.7.0', '>=' ) ?
					array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
							TagsModule::NUMBER_CATEGORY,
						),
					) : array(),
			)
		);

		$this->add_control(
			'scrollwheel',
			array(
				'label'   => esc_html__( 'Scrollwheel Zoom', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'false',
				'options' => array(
					'true'  => esc_html__( 'Enabled', 'jet-elements' ),
					'false' => esc_html__( 'Disabled', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'zoom_controls',
			array(
				'label'   => esc_html__( 'Zoom Controls', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'true',
				'options' => array(
					'true'  => esc_html__( 'Show', 'jet-elements' ),
					'false' => esc_html__( 'Hide', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'fullscreen_control',
			array(
				'label'   => esc_html__( 'Fullscreen Control', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'true',
				'options' => array(
					'true'  => esc_html__( 'Show', 'jet-elements' ),
					'false' => esc_html__( 'Hide', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'street_view',
			array(
				'label'   => esc_html__( 'Street View Controls', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'true',
				'options' => array(
					'true'  => esc_html__( 'Show', 'jet-elements' ),
					'false' => esc_html__( 'Hide', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'map_type',
			array(
				'label'   => esc_html__( 'Map Type Controls (Map/Satellite)', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'true',
				'options' => array(
					'true'  => esc_html__( 'Show', 'jet-elements' ),
					'false' => esc_html__( 'Hide', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'drggable',
			array(
				'label'   => esc_html__( 'Is Map Draggable?', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'true',
				'options' => array(
					'true'  => esc_html__( 'Yes', 'jet-elements' ),
					'false' => esc_html__( 'No', 'jet-elements' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_map_style',
			array(
				'label' => esc_html__( 'Map Style', 'jet-elements' ),
			)
		);

		$this->add_responsive_control(
			'map_height',
			array(
				'label'       => esc_html__( 'Map Height', 'jet-elements' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 50,
				'default'     => 300,
				'render_type' => 'template',
				'selectors' => array(
					'{{WRAPPER}} .jet-map' => 'height: {{VALUE}}px',
				),
			)
		);

		$this->add_control(
			'map_style',
			array(
				'label'       => esc_html__( 'Map Style', 'jet-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => $this->_get_available_map_styles(),
				'label_block' => true,
				'description' => esc_html__( 'You can add own map styles within your theme. Add file with styles array in .json format into jet-elements/google-map-styles/ folder in your theme. File must be minified', 'jet-elements' )
			)
		);

		$this->add_control(
			'custom_map_style_json',
			array(
				'label'     => esc_html__( 'Custom Style JSON', 'jet-elements' ),
				'type'      => Controls_Manager::TEXTAREA,
				'rows'      => 10,
				'condition' => array(
					'map_style' => 'custom',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_map_pins',
			array(
				'label' => esc_html__( 'Pins', 'jet-elements' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'pin_address_type',
			array(
				'label'   => esc_html__( 'Pin Address Type', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => array(
					'0' => esc_html__( 'Coordinates', 'jet-elements' ),
					'1' => esc_html__( 'Address', 'jet-elements' ),
					'2' => esc_html__( 'DMS Format of Coordinates', 'jet-elements' ),
				),
			)
		);

		$repeater->add_control(
			'pin_address_lat_lng',
			array(
				'label'       => esc_html__( 'Pin Address Coordinates', 'jet-elements' ),
				'description' => esc_html__( 'To get Pin Address from latitude and longitude coordinates from one meta field, combine coordinates names with the ";" sign. For example: lat;lng. Where latitude always goes first. The latitude value range is from -90 to 90. The longitude value outside range is from -180 to 180.', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => $default_lat_long,
				'default'     => $default_lat_long,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'pin_address_type' => '0',
				),
			)
		);

		$repeater->add_control(
			'dms_pin_address_lat_lng',
			array(
				'label'       => esc_html__( 'Pin Address Coordinates', 'jet-elements' ),
				'description' => esc_html__( 'To get Pin Address from latitude and longitude coordinates of dms format from one meta field, combine coordinates names with the ";" sign. For example: 51° 30\' 12.2364" N;0° 7\' 10.2684" W. Where latitude always goes first.', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => $default_dms_lat_long,
				'default'     => $default_dms_lat_long,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'pin_address_type' => '2',
				),
			)
		);

		$repeater->add_control(
			'pin_address',
			array(
				'label'       => esc_html__( 'Pin Address', 'jet-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $default_address,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'pin_address_type' => '1',
				),
			)
		);

		$repeater->add_control(
			'pin_desc',
			array(
				'label'   => esc_html__( 'Pin Description', 'jet-elements' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => $default_address,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'pin_link_title',
			array(
				'label'   => esc_html__( 'Link Text', 'jet-elements' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default'     => '',
				'placeholder' => esc_html__( 'View more', 'jet-elements' ),
			)
		);

		$repeater->add_control(
			'pin_link',
			array(
				'label' => esc_html__( 'Link', 'jet-elements' ),
				'type' => Controls_Manager::URL,
				'dynamic' => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'https://your-link.com', 'jet-elements' ),
				'default' => array(
					'url' => '#',
				),
				'condition'   => array(
					'pin_link_title!' => '',
				),
			)
		);

		$repeater->add_control(
			'pin_image',
			array(
				'label' => esc_html__( 'Pin Icon', 'jet-elements' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'pin_custom_size',
			array(
				'label'        => esc_html__( 'Pin Icon Custom Size', 'jet-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-elements' ),
				'label_off'    => esc_html__( 'No', 'jet-elements' ),
				'return_value' => 'true',
				'default'      => false,
				'condition'    => array(
					'pin_image[url]!' => '',
				),
			)
		);

		$repeater->add_control(
			'pin_icon_width',
			array(
				'label'      => esc_html__( 'Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 60,
				),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 200,
						'step' => 1,
					),
				),
				'condition'   => array(
					'pin_custom_size' => 'true',
					'pin_image[url]!' => '',
				),
			)
		);

		$repeater->add_control(
			'pin_icon_height',
			array(
				'label'      => esc_html__( 'Height', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 60,
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
						'step' => 1,
					),
				),
				'condition'   => array(
					'pin_custom_size' => 'true',
					'pin_image[url]!' => '',
				),
				'separator' => 'after',
			)
		);

		$repeater->add_control(
			'pin_state',
			array(
				'label'   => esc_html__( 'Initial State', 'jet-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'visible',
				'options' => array(
					'visible' => esc_html__( 'Visible', 'jet-elements' ),
					'hidden'  => esc_html__( 'Hidden', 'jet-elements' ),
				),
			)
		);

		$this->add_control(
			'pins',
			array(
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array(
						'pin_address'         => $default_address,
						'pin_address_lat_lng' => $default_lat_long,
						'pin_desc'            => $default_address,
						'pin_state'           => 'visible',
					),
				),
				'title_field' => '<# if ( "1" === pin_address_type ){ #> {{{ pin_address }}} <# } else if ( "0" === pin_address_type) { #> {{{ pin_address_lat_lng }}} <# } else if ( "2" === pin_address_type) { #> {{{ dms_pin_address_lat_lng }}} <# } #>',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Section
		 */

		$this->start_controls_section(
			'section_pin_style',
			array(
				'label'      => esc_html__( 'Pin', 'jet-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'pin_link_width',
			array(
				'label'      => esc_html__( 'Pin Width', 'jet-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 400,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .gm-style .gm-style-iw-c' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
				),
				'dynamic'   => array( 'active' => true ),
			)
		);

		$this->add_control(
			'pin_link_styles',
			array(
				'label'     => esc_html__( 'Link Styles', 'jet-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pin_link_typography',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .jet-map-pin__link',
			)
		);

		$this->add_responsive_control(
			'pin_link_margin',
			array(
				'label'      => esc_html__( 'Margin', 'jet-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-map-pin__wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'pin_link_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'jet-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'jet-elements' ),
						'icon'  => ! is_rtl() ? 'eicon-text-align-left' : 'eicon-text-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-elements' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'jet-elements' ),
						'icon'  => ! is_rtl() ? 'eicon-text-align-right' : 'eicon-text-align-left',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-map-pin__wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_pin_link_style' );

		$this->start_controls_tab(
			'tab_pin_link__normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-elements' ),
			)
		);

		$this->add_control(
			'pin_link_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-map-pin__link' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pin_link_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-elements' ),
			)
		);

		$this->add_control(
			'pin_link_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-map-pin__link:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Get available map styles list.
	 *
	 * @return array
	 */
	public function _get_available_map_styles() {

		$key           = md5( 'jet-elements-' . jet_elements()->get_version() );
		$plugin_styles = get_transient( $key );

		if ( ! $plugin_styles ) {

			$plugin_styles = $this->_get_map_styles_from_path(
				jet_elements()->plugin_path( 'assets/js/lib/google-maps/' )
			);

			set_transient( $key, $plugin_styles, WEEK_IN_SECONDS );
		}

		$parent_styles = $this->_get_map_styles_from_path(
			get_template_directory() . '/' . jet_elements()->template_path() . 'google-map-styles/'
		);

		if ( get_stylesheet_directory() !== get_template_directory() ) {
			$child_styles = $this->_get_map_styles_from_path(
				get_stylesheet_directory() . '/' . jet_elements()->template_path() . 'google-map-styles/'
			);
		} else {
			$child_styles = array();
		}

		return array_merge(
			array( 'default' => esc_html__( 'Default', 'jet-elements' ) ),
			$plugin_styles,
			$parent_styles,
			$child_styles,
			array( 'custom' => esc_html__( 'Custom', 'jet-elements' ) )
		);
	}

	/**
	 * Get map styles array rom path
	 *
	 * @param  string $path [description]
	 * @return array
	 */
	public function _get_map_styles_from_path( $path = null ) {

		if ( ! file_exists( $path ) ) {
			return array();
		}

		$result = array();
		$absp   = untrailingslashit( ABSPATH );

		foreach ( glob( $path . '*.json' ) as $file ) {
			$data = get_file_data( $file, array( 'name'=>'Name' ) );
			$result[ str_replace( $absp, '', $file ) ] = ! empty( $data['name'] ) ? $data['name'] : basename( $file );
		}

		return $result;
	}

	/**
	 * Get map style JSON by file name
	 *
	 * @param  string $style Style file
	 * @return string
	 */
	public function _get_map_style( $style ) {

		$full_path    = untrailingslashit( ABSPATH ) . $style;
		$include_path = null;

		ob_start();

		if ( file_exists( $full_path ) ) {
			$include_path = $full_path;
		} elseif ( file_exists( $style ) ) {
			$include_path = $style;
		} elseif ( file_exists( str_replace( '\\', '/', $full_path ) ) ) {
			$include_path = str_replace( '\\', '/', $full_path );
		}

		ob_get_clean();

		if ( ! $include_path ) {
			return '';
		}

		ob_start();
		include $include_path;
		return preg_replace( '/\/\/?\s*\*[\s\S]*?\*\s*\/\/?/m', '', ob_get_clean() );
	}

	/**
	 * Get location coordinates by entered address and store into metadata.
	 *
	 * @return array|void
	 */
	public function get_location_coord( $location ) {

		$api_key = jet_elements_settings()->get( 'api_key' );

		// Do nothing if api key not provided
		if ( ! $api_key ) {
			$message = esc_html__( 'Please set Google maps API key before using this widget.', 'jet-elements' );

			echo $this->get_map_message( $message );

			return;
		}

		$key = md5( $location );

		$coord = get_transient( $key );

		if ( ! empty( $coord ) ) {
			return $coord;
		}

		// Prepare request data
		$location = esc_attr( $location );
		$api_key  = esc_attr( $api_key );

		$reques_url = esc_url( add_query_arg(
			array(
				'address' => urlencode( $location ),
				'key'     => urlencode( $api_key )
			),
			$this->geo_api_url
		) );

		// Fixed '&' encoding bug
		$reques_url = str_replace( '&#038;', '&', $reques_url );

		$response = wp_remote_get( $reques_url );
		$json     = wp_remote_retrieve_body( $response );
		$data     = json_decode( $json, true );

		$coord = isset( $data['results'][0]['geometry']['location'] )
			? $data['results'][0]['geometry']['location']
			: false;

		if ( ! $coord ) {

			$message = esc_html__( 'Coordinates of this location not found', 'jet-elements' );

			echo $this->get_map_message( $message );

			return;
		}

		set_transient( $key, $coord, WEEK_IN_SECONDS );

		return $coord;
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$map_center_type = isset( $settings['map_center_type'] ) ? $settings['map_center_type'] : '1';

		if ( $map_center_type === '1' ) {

			if ( empty( $settings['map_center'] ) ) {
				return;
			}

			$coordinates = $this->get_location_coord( $settings['map_center'] );

			if ( ! $coordinates ) {
				return;
			}
		} else if ( $map_center_type === '0' ) {

			if ( empty( $settings['map_center_lat_lng'] ) ) {
				$message = esc_html__( 'Location not found', 'jet-elements' );

				echo $this->get_map_message( $message );
				return;
			}

			$lat_lng = explode( ';', $settings['map_center_lat_lng'] );

			if ( isset( $lat_lng[0] ) && $lat_lng[0] !== '' && !ctype_space( $lat_lng[0] ) && isset( $lat_lng[1] ) && $lat_lng[1] !== '' && !ctype_space( $lat_lng[1] ) ) {
				$lat = floatval( str_replace( ',', '.', preg_replace('/\s+/', '', $lat_lng[0] ) ) );
				$lng = floatval( str_replace( ',', '.', preg_replace('/\s+/', '', $lat_lng[1] ) ) );

				if ( $lat > 90 || $lat < -90 ) {
					$message = esc_html__( 'Map Center latitude value outside range from -90 to 90', 'jet-elements' );

					echo $this->get_map_message( $message );
					return;
				}

				if ( $lng > 180 || $lng < -180 ) {
					$message = esc_html__( 'Map Center longitude value outside range from -180 to 180', 'jet-elements' );

					echo $this->get_map_message( $message );
					return;
				}

				$coordinates = array( 'lat' => $lat, 'lng' => $lng );
			} else {
				$message = esc_html__( 'Location not found', 'jet-elements' );

				echo $this->get_map_message( $message );
				return;
			}
		} else {

			if ( empty( $settings['map_center_dms'] ) ) {
				$message = esc_html__( 'Location not found', 'jet-elements' );

				echo $this->get_map_message( $message );
				return;
			}

			$dms_lat_lng = explode( ';', $settings['map_center_dms'] );

			if ( isset( $dms_lat_lng[0] ) && $dms_lat_lng[0] !== '' && !ctype_space( $dms_lat_lng[0] ) && isset( $dms_lat_lng[1] ) && $dms_lat_lng[1] !== '' && !ctype_space( $dms_lat_lng[1] ) ) {
				$dec_lat     = floatval( preg_replace('/\s+/', '', $this->dms_to_dec( $dms_lat_lng[0] ) ) );
				$dec_lng     = floatval( preg_replace('/\s+/', '', $this->dms_to_dec( $dms_lat_lng[1] ) ) );
				$coordinates = array( 'lat' => $dec_lat, 'lng' => $dec_lng );
			} else {
				$message = esc_html__( 'Location not found', 'jet-elements' );

				echo $this->get_map_message( $message );
				return;
			}
		}

		$scroll_ctrl     = isset( $settings['scrollwheel'] ) ? $settings['scrollwheel'] : '';
		$zoom_ctrl       = isset( $settings['zoom_controls'] ) ? $settings['zoom_controls'] : '';
		$fullscreen_ctrl = isset( $settings['fullscreen_control'] ) ? $settings['fullscreen_control'] : '';
		$streetview_ctrl = isset( $settings['street_view'] ) ? $settings['street_view'] : '';

		$init = apply_filters( 'jet-elements/addons/advanced-map/data-args', array(
			'center'            => $coordinates,
			'zoom'              => isset( $settings['zoom']['size'] ) ? intval( $settings['zoom']['size'] ) : 11,
			'scrollwheel'       => filter_var( $scroll_ctrl, FILTER_VALIDATE_BOOLEAN ),
			'zoomControl'       => filter_var( $zoom_ctrl, FILTER_VALIDATE_BOOLEAN ),
			'fullscreenControl' => filter_var( $fullscreen_ctrl, FILTER_VALIDATE_BOOLEAN ),
			'streetViewControl' => filter_var( $streetview_ctrl, FILTER_VALIDATE_BOOLEAN ),
			'mapTypeControl'    => filter_var( $settings['map_type'], FILTER_VALIDATE_BOOLEAN ),
		) );

		if ( 'false' === $settings['drggable'] ) {
			$init['gestureHandling'] = 'none';
		}

		if ( ! in_array( $settings['map_style'], array( 'default', 'custom' ) ) ) {
			$init['styles'] = json_decode( $this->_get_map_style( $settings['map_style'] ) );
		}

		if ( 'custom' === $settings['map_style'] && ! empty( $settings['custom_map_style_json'] ) ) {
			$init['styles'] = json_decode( $settings['custom_map_style_json'] );
		}

		$this->add_render_attribute( 'map-data', 'data-init', json_encode( $init ) );

		$pins = array();

		if ( ! empty( $settings['pins'] ) ) {

			foreach ( $settings['pins'] as $pin ) {

				$pin_address_type = isset( $pin['pin_address_type'] ) ? $pin['pin_address_type'] : '1';

				if ( $pin_address_type === '1' ) {

					if ( empty( $pin['pin_address'] ) ) {
						continue;
					}

					$position = $this->get_location_coord( $pin['pin_address'] );
				} else if ( $pin_address_type === '0' ) {

					if ( empty( $pin['pin_address_lat_lng'] ) ) {
						continue;
					}

					$pos_lat_lng = explode( ';', $pin['pin_address_lat_lng'] );

					if ( isset( $pos_lat_lng[0] ) && $pos_lat_lng[0] !== '' && !ctype_space( $pos_lat_lng[0] ) && isset( $pos_lat_lng[1] ) && $pos_lat_lng[1] !== '' && !ctype_space( $pos_lat_lng[1] ) ) {
						$pos_lat  = floatval( str_replace( ',', '.', preg_replace('/\s+/', '', $pos_lat_lng[0] ) ) );
						$pos_lng  = floatval( str_replace( ',', '.', preg_replace('/\s+/', '', $pos_lat_lng[1] ) ) );
						$position = array( 'lat' => $pos_lat, 'lng' => $pos_lng );

						if ( $pos_lat > 90 || $pos_lat < -90 ) {
							$message = esc_html__( 'Pin address latitude value outside range from -90 to 90', 'jet-elements' );

							echo $this->get_map_message( $message );
							return;
						}

						if ( $pos_lng > 180 || $pos_lng < -180 ) {
							$message = esc_html__( 'Pin address longitude value outside range from -180 to 180', 'jet-elements' );

							echo $this->get_map_message( $message );
							return;
						}
					} else {
						$message = esc_html__( 'Pin location not found', 'jet-elements' );

						echo $this->get_map_message( $message );
						return;
					}
				} else {

					if ( empty( $pin['dms_pin_address_lat_lng'] ) ) {
						continue;
					}

					$pos_dms_lat_lng = explode( ';', $pin['dms_pin_address_lat_lng'] );

					if ( isset( $pos_dms_lat_lng[0] ) && $pos_dms_lat_lng[0] !== '' && !ctype_space( $pos_dms_lat_lng[0] ) && isset( $pos_dms_lat_lng[1] ) && $pos_dms_lat_lng[1] !== '' && !ctype_space( $pos_dms_lat_lng[1] ) ) {
						$pos_dms_lat  = floatval( str_replace( ',', '.', preg_replace('/\s+/', '', $this->dms_to_dec( $pos_dms_lat_lng[0] ) ) ) );
						$pos_dms_lng  = floatval( str_replace( ',', '.', preg_replace('/\s+/', '', $this->dms_to_dec( $pos_dms_lat_lng[1] ) ) ) );
						$position     = array( 'lat' => $pos_dms_lat, 'lng' => $pos_dms_lng );
					} else {
						$message = esc_html__( 'Pin location not found', 'jet-elements' );
						echo $this->get_map_message( $message );
						return;
					}
				}

				$current = array(
					'position' => $position,
					'desc'     => $pin['pin_desc'],
					'state'    => $pin['pin_state'],
				);

				if ( ! empty( $pin['pin_image']['url'] ) ) {
					$current['image'] = esc_url( $pin['pin_image']['url'] );

					if ( 'true' === $pin['pin_custom_size'] && ! empty( $pin['pin_icon_width']['size'] ) && ! empty( $pin['pin_icon_height']['size'] ) ) {
						$current['image_width']  = $pin['pin_icon_width']['size'];
						$current['image_height'] = $pin['pin_icon_height']['size'];
					}
				}

				if ( ! empty ( $pin['pin_link_title'] ) && ! empty( $pin['pin_link'] ) ) {
					$current['link_title'] = $pin['pin_link_title'];
					$current['link']       = $pin['pin_link'];
				}

				$pins[] = $current;
			}

		}

		$this->add_render_attribute( 'map-pins', 'data-pins', json_encode( $pins ) );

		printf(
			'<div class="jet-map" %1$s %2$s></div>',
			$this->get_render_attribute_string( 'map-data' ),
			$this->get_render_attribute_string( 'map-pins' )
		);
	}

	/**
	 * Convert a coordinate in dms to dec
	 *
	 * @param string $dms coordinate
	 * @return float
	 */
	public function dms_to_dec( $dms ) {
		$dms     = stripslashes( $dms );
		$neg     = ( preg_match( '/[SWO]/i', $dms ) == 0 ) ? 1 : - 1;
		$dms     = preg_replace( '/(^\s?-)|(\s?[NSEWO]\s?)/i', '', $dms );
		$pattern = "/(\\d*\\.?\\d+)(?:[°ºd: ]+)(\\d*\\.?\\d+)*(?:['m′: ])*(\\d*\\.?\\d+)*[\"s″ ]?/i";
		$parts   = preg_split( $pattern, $dms, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );

		if ( ! $parts ) {
			return;
		}

		// parts: 0 = degree, 1 = minutes, 2 = seconds
		$d   = isset( $parts[0] ) ? (float) $parts[0] : 0;
		$m   = isset( $parts[1] ) ? (float) $parts[1] : 0;
		$s   = isset( $parts[2] ) ? (float) $parts[2] : 0;
		$dec = ( $d + ( $m / 60 ) + ( $s / 3600 ) ) * $neg;

		return $dec;
	}

	/**
	 * [map_message description]
	 * @param  [type] $message [description]
	 * @return [type]          [description]
	 */
	public function get_map_message( $message ) {
		return sprintf( '<div class="jet-map-message"><div class="jet-map-message__dammy-map"></div><span class="jet-map-message__text">%s</span></div>', $message );
	}

}
