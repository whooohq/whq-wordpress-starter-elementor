<?php
namespace Elementor;

use Elementor\Core\Responsive\Responsive;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * HTML Widget
 */
class Jet_Widget_Mega_Menu extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'jet-mega-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Mega Menu', 'jet-menu' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'jet-menu-icon-mega-menu';
	}

	public function get_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetmenu-how-to-create-a-mega-menu-using-elementor-with-jetmenu-widget/?utm_source=jetmenu&utm_medium=jet-mega-menu&utm_campaign=need-help';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'cherry' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'jet-menu' ),
			)
		);

		$parent = isset( $_GET['parent_menu'] ) ? absint( $_GET['parent_menu'] ) : 0;

		if ( $parent ) {
			$this->add_control(
				'menu_notice',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => esc_html__( 'This module can\'t be used inside Mega Menu content. Please, use it to show selected Mega Menu on specific page.', 'jet-menu' )
				)
			);
		} else {

			$this->add_control(
				'force-mobile-render',
				array(
					'type'    => 'hidden',
					'default' => false,
				)
			);

			$this->add_control(
				'menu',
				array(
					'label'   => esc_html__( 'Menu', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => $this->get_available_menus(),
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'jet-menu' ), admin_url( 'nav-menus.php' ) ),
				)
			);

			$this->add_control(
				'layout',
				array(
					'label' => __( 'Layout', 'jet-menu' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'horizontal',
					'options' => array(
						'horizontal' => __( 'Horizontal', 'jet-menu' ),
						'vertical' => __( 'Vertical', 'jet-menu' ),
						'dropdown' => __( 'Dropdown', 'jet-menu' ),
					),
				)
			);

			$this->add_control(
				'dropdown-layout',
				array(
					'label'   => __( 'Dropdown Layout', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'default',
					'options' => array(
						'default' => __( 'Default', 'jet-menu' ),
						'push'    => __( 'Push', 'jet-menu' ),
					),
				)
			);

			$this->add_control(
				'dropdown-position',
				array(
					'label'   => __( 'Dropdown Position', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'right',
					'options' => array(
						'right'  => __( 'Right', 'jet-menu' ),
						'center' => __( 'Center', 'jet-menu' ),
						'left'   => __( 'Left', 'jet-menu' ),
					),
				)
			);

			$this->add_control(
				'sub-animation',
				array(
					'label'       => __( 'Animation', 'jet-menu' ),
					'description' => __( 'Choose an animation effect for sub menu', 'jet-menu' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'none',
					'options' => array(
						'none'      => __( 'None', 'jet-menu' ),
						'fade'      => __( 'Fade', 'jet-menu' ),
					),
				)
			);

			$this->add_control(
				'sub-menu-position',
				array(
					'label'   => esc_html__( 'Sub Menu Position', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'right',
					'options' => array(
						'right' => esc_html__( 'Right', 'jet-menu' ),
						'left'  => esc_html__( 'Left', 'jet-menu' ),
					),
					'condition' => array (
						'layout' => array( 'horizontal', 'vertical' ),
					),
				)
			);

			$this->add_control(
				'sub-menu-event',
				array(
					'label'   => esc_html__( 'Sub Menu Trigger', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'hover',
					'options' => array(
						'hover'  => esc_html__( 'Hover', 'jet-menu' ),
						'click'  => esc_html__( 'Click', 'jet-menu' ),
					),
				)
			);

			$this->add_control(
				'sub-menu-trigger',
				array(
					'label'   => esc_html__( 'Sub Menu Target', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'item',
					'options' => array(
						'item'       => esc_html__( 'Item', 'jet-menu' ),
						'submarker'  => esc_html__( 'Sub Icon', 'jet-menu' ),
					),
					'condition' => array (
						'sub-menu-event' => array( 'click' ),
					),
				)
			);

			$this->add_control(
				'mega-width-type',
				array(
					'label'   => esc_html__( 'Mega Container Width Type', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'container',
					'options' => array(
						'container' => esc_html__( 'Container', 'jet-menu' ),
						'selector'  => esc_html__( 'Selector', 'jet-menu' ),
						'items'     => esc_html__( 'Items', 'jet-menu' ),
					),
					'condition' => array (
						'layout' => 'horizontal',
					),
				)
			);

			$this->add_control(
				'mega-width-selector',
				array(
					'label'   => esc_html__( 'Custom selector', 'jet-menu' ),
					'type'    => Controls_Manager::TEXT,
					'default' => '',
					'condition' => array(
						'layout'          => 'horizontal',
						'mega-width-type' => 'selector',
					),
				)
			);

			$breakpoints = Responsive::get_breakpoints();

			$this->add_control(
				'dropdown-breakpoint',
				array(
					'label'   => __( 'Breakpoint', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'md',
					'options' => array (
						'none'   => __( 'None', 'jet-menu' ),
						'lg' => sprintf( __( 'Tablet( <%dpx )', 'jet-menu' ), $breakpoints[ 'lg' ] ),
						'md' => sprintf( __( 'Mobile( <%dpx )', 'jet-menu' ), $breakpoints[ 'md' ] ),
					),
					'condition' => array (
						'layout!' => 'dropdown',
					),
				)
			);

			$this->add_control(
				'roll-up',
				array(
					'label'        => esc_html__( 'Roll Up', 'jet-menu' ),
					'description'  => esc_html__( 'Use horizontal items Roll Up?', 'jet-menu' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
					'label_off'    => esc_html__( 'No', 'jet-manu' ),
					'return_value' => 'yes',
					'default'      => 'false',
					'condition' => array (
						'layout' => 'horizontal',
					),
				)
			);

			$this->add_control(
				'roll-up-type',
				array(
					'label'   => esc_html__( 'RollUp Type', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'text',
					'options' => array(
						'text' => esc_html__( 'Text', 'jet-menu' ),
						'icon' => esc_html__( 'Icon', 'jet-menu' ),
					),
					'condition' => array(
						'roll-up' => 'yes',
					),
				)
			);

			$this->add_control(
				'roll-up-item-text',
				array(
					'label'   => esc_html__( 'RollUp Item Text', 'jet-menu' ),
					'type'    => Controls_Manager::TEXT,
					'default' => '...',
					'condition' => array(
						'roll-up'      => 'yes',
						'roll-up-type' => 'text',
					),
				)
			);

			$this->add_control(
				'roll-up-item-icon',
				array(
					'label'            => __( 'RollUp Item Icon', 'jet-menu' ),
					'type'             => Controls_Manager::ICONS,
					'label_block'      => false,
					'skin'             => 'inline',
					'fa4compatibility' => 'icon',
					'default'          => array(
						'value'   => 'fas fa-ellipsis-h',
						'library' => 'fa-solid',
					),
					'condition' => array(
						'roll-up'      => 'yes',
						'roll-up-type' => 'icon',
					),
				)
			);

			$this->add_control(
				'use-mobile-device-render',
				array(
					'label'        => esc_html__( 'Mobile Device Render', 'jet-menu' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
					'label_off'    => esc_html__( 'No', 'jet-menu' ),
					'return_value' => 'yes',
					'default'      => 'false',
				)
			);

			$this->add_control(
				'dropdown-icon',
				array(
					'label'            => __( 'Dropdown Icon', 'jet-menu' ),
					'type'             => Controls_Manager::ICONS,
					'label_block'      => false,
					'skin'             => 'inline',
					'fa4compatibility' => 'icon',
					'default'          => array(
						'value'   => 'fas fa-angle-down',
						'library' => 'fa-solid',
					),
				)
			);

			$this->add_control(
				'toggle-default-icon',
				array(
					'label'            => __( 'Toggle Icon', 'jet-menu' ),
					'type'             => Controls_Manager::ICONS,
					'label_block'      => false,
					'skin'             => 'inline',
					'fa4compatibility' => 'icon',
					'default'          => array(
						'value'   => 'fas fa-bars',
						'library' => 'fa-solid',
					),
				)
			);

			$this->add_control(
				'toggle-opened-icon',
				array(
					'label'            => __( 'Opened Toggle Icon', 'jet-menu' ),
					'type'             => Controls_Manager::ICONS,
					'label_block'      => false,
					'skin'             => 'inline',
					'fa4compatibility' => 'icon',
					'default'          => array(
						'value'   => 'fas fa-times',
						'library' => 'fa-solid',
					),
				)
			);

		}

		$this->end_controls_section();

		$this->start_controls_section(
			'mobile_device_render',
			array(
				'label' => esc_html__( 'Mobile Menu', 'jet-menu' ),
				'condition' => array (
					'use-mobile-device-render' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile-menu',
			array(
				'label'   => esc_html__( 'Menu for Mobile', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_available_menus(),
			)
		);

		$this->add_control(
			'device-for-mobile-render',
			array(
				'label'   => __( 'Mobile Device', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'mobile',
				'options' => array(
					'mobile'        => __( 'Mobile', 'jet-menu' ),
					'tablet-mobile' => __( 'Tablet and Mobile', 'jet-menu' ),
				),
			)
		);

		$this->add_control(
			'mobile-layout',
			array(
				'label'   => esc_html__( 'Layout', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slide-out',
				'options' => array(
					'slide-out' => esc_html__( 'Slide Out', 'jet-menu' ),
					'dropdown'  => esc_html__( 'Dropdown', 'jet-menu' ),
					'push'      => esc_html__( 'Push', 'jet-menu' ),
				),
			)
		);

		$this->add_control(
			'mobile-toggle-position',
			array(
				'label'       => esc_html__( 'Toggle Position', 'jet-menu' ),
				'description' => esc_html__( 'Choose toggle global position on window screen', 'jet-menu' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => array(
					'default' => esc_html__( 'Default', 'jet-menu' ),
					'fixed-left'  => esc_html__( 'Fixed to top-left screen corner', 'jet-menu' ),
					'fixed-right'  => esc_html__( 'Fixed to top-right screen corner', 'jet-menu' ),
				),
				'condition' => array(
					'mobile-layout' => 'slide-out',
				),
			)
		);

		$this->add_control(
			'mobile-container-position',
			array(
				'label'   => esc_html__( 'Container Position', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'right' => esc_html__( 'Right', 'jet-menu' ),
					'left'  => esc_html__( 'Left', 'jet-menu' ),
				),
			)
		);

		$this->add_control(
			'mobile-sub-menu-trigger',
			array(
				'label'   => esc_html__( 'Show Sub Menu Trigger', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'item',
				'options' => array(
					'item'       => esc_html__( 'Menu Item', 'jet-menu' ),
					'submarker'  => esc_html__( 'Sub Marker', 'jet-menu' ),
				),
			)
		);

		$this->add_control(
			'mobile-sub-open-layout',
			array(
				'label'   => esc_html__( 'Show Sub Menu Layout', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slide-in',
				'options' => array(
					'slide-in' => esc_html__( 'Slide In', 'jet-menu' ),
					'dropdown' => esc_html__( 'Dropdown', 'jet-menu' ),
				),
			)
		);

		$this->add_control(
			'mobile-close-after-navigate',
			array(
				'label'        => esc_html__( 'Close After Navigation', 'jet-menu' ),
				'description'  => esc_html__( 'Close Menu Panel After Item Link Navigation', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-manu' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'mobile-item-header-template',
			array(
				'label'       => esc_html__( 'Choose Header Template', 'jet-menu' ),
				'label_block' => 'true',
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
			)
		);

		$this->add_control(
			'mobile-item-before-template',
			array(
				'label'       => esc_html__( 'Choose Before Items Template', 'jet-menu' ),
				'label_block' => 'true',
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
			)
		);

		$this->add_control(
			'mobile-item-after-template',
			array(
				'label'       => esc_html__( 'Choose After Items Template', 'jet-menu' ),
				'label_block' => 'true',
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
			)
		);

		$this->add_control(
			'mobile-is-item-icon',
			array(
				'label'        => esc_html__( 'Item Icon Visible', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-manu' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'mobile-is-item-badge',
			array(
				'label'        => esc_html__( 'Item Badge Visible', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-manu' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'mobile-is-item-desc',
			array(
				'label'        => esc_html__( 'Item Description Visible', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-manu' ),
				'return_value' => 'yes',
				'default'      => 'false',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'mobile-is-item-divider',
			array(
				'label'        => esc_html__( 'Item Divider Visible', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-manu' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_control(
			'mobile-use-breadcrumbs',
			array(
				'label'        => esc_html__( 'Use Breadcrumbs?', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-manu' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'mobile-breadcrumbs-path',
			array(
				'label'   => esc_html__( 'Breadcrumbs Path', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'full',
				'options' => array(
					'full'     => esc_html__( 'Full', 'jet-menu' ),
					'minimal'  => esc_html__( 'Minimal', 'jet-menu' ),
				),
				'condition' => array(
					'use_breadcrumbs' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile-toggle-loader',
			array(
				'label'        => esc_html__( 'Use Toggle Button Loader?', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-manu' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'mobile-toggle-loader-color',
			array(
				'label'       => esc_html__( 'Loader Color', 'jet-menu' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#3a3a3a',
				'render_type' => 'template',
				'condition' => array(
					'mobile-toggle-loader' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile-toggle-closed-state-icon',
			array(
				'label'            => __( 'Toggle Closed State Icon', 'jet-menu' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-bars',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'mobile-toggle-opened-state-icon',
			array(
				'label'            => __( 'Toggle Opened State Icon', 'jet-menu' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'mobile-toggle-text',
			array(
				'label'   => esc_html__( 'Toggle Text', 'jet-menu' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'mobile-container-close-icon',
			array(
				'label'            => __( 'Container Close Icon', 'jet-menu' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'mobile-container-back-icon',
			array(
				'label'            => __( 'Container Back Icon', 'jet-menu' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-angle-left',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'mobile-back-text',
			array(
				'label'   => esc_html__( 'Back Text', 'jet-menu' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'mobile-dropdown-icon',
			array(
				'label'            => __( 'Submenu Icon', 'jet-menu' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'mobile-dropdown-opened-icon',
			array(
				'label'            => __( 'Submenu Opened Icon', 'jet-menu' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-angle-down',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'mobile-sub-open-layout' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'mobile-breadcrumb-icon',
			array(
				'label'            => __( 'Breadcrumbs Divider Icon', 'jet-menu' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'skin'             => 'inline',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'mobile-use-breadcrumbs' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `Main Menu` Style Section
		 */
		$this->start_controls_section(
			'section_main_menu_styles',
			array(
				'label'      => esc_html__( 'Main Menu', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'main_menu_container_heading',
			array(
				'label'     => esc_html__( 'Container', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'main_menu_container_width',
			array(
				'label' => __( 'Width', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 200,
						'max' => 1980,
					),
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-container-width:{{SIZE}}{{UNIT}};',
				),
				'condition' => array (
					'layout' => 'vertical',
				),
			)
		);

		$this->add_control(
			'main_menu_level_heading',
			array(
				'label'     => esc_html__( 'Levels', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->start_controls_tabs( 'tabs_menu_level_style' );

		$this->start_controls_tab(
			'tab_menu_top_level',
			array(
				'label' => __( 'Top', 'jet-menu' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Items Typography', 'jet-menu' ),
				'name'     => 'top_items_typography',
				'selector' => '{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-horizontal .jet-mega-menu-item__link--top-level,
							{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-vertical .jet-mega-menu-item__link--top-level',
			)
		);

		$this->add_control(
			'main_menu_container_bg_color',
			array(
				'label'   => __( 'Container Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-menu-bg-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'main_menu_item_vertical_padding',
			[
				'label' => __( 'Items Vertical Padding', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-items-ver-padding:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->add_responsive_control(
			'main_menu_item_hor_padding',
			[
				'label' => __( 'Items Horizontal Padding', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-items-hor-padding:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->add_responsive_control(
			'main_menu_item_space',
			[
				'label' => __( 'Items Space', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-items-gap:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->add_responsive_control(
			'main_menu_items_align',
			array(
				'label' => __( 'Items Align', 'jet-menu' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => __( 'Left', 'jet-menu' ),
						'icon' => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'jet-menu' ),
						'icon' => 'eicon-h-align-center',
					),
					'right' => array(
						'title' => __( 'Right', 'jet-menu' ),
						'icon' => 'eicon-h-align-right',
					),
					'space-between' => array(
						'title' => __( 'Stretch', 'jet-menu' ),
						'icon' => 'eicon-h-align-stretch',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-items-hor-align:{{UNIT}};',
				),
				'condition' => array(
					'layout!' => 'dropdown',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_sub_level',
			array(
				'label' => __( 'Sub', 'jet-menu' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Items Typography', 'jet-menu' ),
				'name'     => 'sub_items_typography',
				'selector' => '{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-horizontal .jet-mega-menu-item__link--sub-level,
							{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-vertical .jet-mega-menu-item__link--sub-level',
			)
		);

		$this->add_control(
			'sub_menu_container_bg_color',
			array(
				'label'   => __( 'Container Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-menu-bg-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'main_menu_sub_item_vertical_padding',
			[
				'label' => __( 'Item Vertical Padding', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-items-ver-padding:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->add_responsive_control(
			'main_menu_sub_item_hor_padding',
			[
				'label' => __( 'Item Horizontal Padding', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-items-hor-padding:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->add_responsive_control(
			'main_menu_sub_item_space',
			[
				'label' => __( 'Items Space', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-items-gap:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'main_menu_states_heading',
			array(
				'label'     => esc_html__( 'States', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			array(
				'label' => __( 'Normal', 'jet-menu' ),
			)
		);

		$this->add_control(
			'main_menu_top_state_heading',
			array(
				'label'     => esc_html__( 'Top Items', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'menu_item_icon_color',
			array(
				'label'   => __( 'Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-item-icon-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_item_title_color',
			array(
				'label'   => __( 'Title Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-item-title-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_item_dropdown_color',
			array(
				'label'   => __( 'Dropdown Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-item-dropdown-icon-color: {{VALUE}}',
				),
			)
		);

		/*$this->add_control(
			'menu_item_badge_color',
			array(
				'label'   => __( 'Badge Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-item-badge-color: {{VALUE}}',
				),
			)
		);*/

		$this->add_control(
			'menu_item_bg_color',
			array(
				'label'   => __( 'Background Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-item-bg-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'main_menu_sub_state_heading',
			array(
				'label'     => esc_html__( 'Sub Items', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'menu_sub_item_icon_color',
			array(
				'label'   => __( 'Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-item-icon-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_sub_item_title_color',
			array(
				'label'   => __( 'Title Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-item-title-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_sub_item_dropdown_color',
			array(
				'label'   => __( 'Dropdown Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-item-dropdown-icon-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_sub_item_bg_color',
			array(
				'label'   => __( 'Background Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-item-bg-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_hover_item',
			array(
				'label' => __( 'Hover', 'jet-menu' ),
			)
		);

		$this->add_control(
			'main_menu_top_hover_state_heading',
			array(
				'label'     => esc_html__( 'Top Items', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'menu_hover_item_icon_color',
			array(
				'label'   => __( 'Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-hover-item-icon-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_hover_item_title_color',
			array(
				'label'   => __( 'Title Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-hover-item-title-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_hover_item_dropdown_color',
			array(
				'label'   => __( 'Dropdown Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-hover-item-dropdown-color: {{VALUE}}',
				),
			)
		);

		/*$this->add_control(
			'menu_hover_item_badge_color',
			array(
				'label'   => __( 'Badge Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-hover-item-badge-color: {{VALUE}}',
				),
			)
		);*/

		$this->add_control(
			'menu_hover_item_bg_color',
			array(
				'label'   => __( 'Background Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-hover-item-bg-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'main_menu_sub_hover_state_heading',
			array(
				'label'     => esc_html__( 'Sub Items', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'menu_sub_hover_item_title_color',
			array(
				'label'   => __( 'Title Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-hover-item-title-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_sub_hover_item_dropdown_color',
			array(
				'label'   => __( 'Dropdown Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-hover-item-dropdown-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_sub_hover_item_bg_color',
			array(
				'label'   => __( 'Background Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-hover-item-bg-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_active_item',
			array(
				'label' => __( 'Active', 'jet-menu' ),
			)
		);

		$this->add_control(
			'main_menu_top_active_state_heading',
			array(
				'label'     => esc_html__( 'Top Items', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'menu_active_item_icon_color',
			array(
				'label'   => __( 'Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-active-item-icon-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_active_item_title_color',
			array(
				'label'   => __( 'Title Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-active-item-title-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_active_item_dropdown_color',
			array(
				'label'   => __( 'Dropdown Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-active-item-dropdown-color: {{VALUE}}',
				),
			)
		);

		/*$this->add_control(
			'menu_active_item_badge_color',
			array(
				'label'   => __( 'Badge Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-active-item-badge-color: {{VALUE}}',
				),
			)
		);*/

		$this->add_control(
			'menu_active_item_bg_color',
			array(
				'label'   => __( 'Background Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-top-active-item-bg-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'main_menu_sub_active_state_heading',
			array(
				'label'     => esc_html__( 'Sub Items', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'menu_sub_active_item_title_color',
			array(
				'label'   => __( 'Title Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-active-item-title-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_sub_active_item_dropdown_color',
			array(
				'label'   => __( 'Dropdown Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-active-item-dropdown-icon-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_sub_active_item_bg_color',
			array(
				'label'   => __( 'Background Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-sub-active-item-bg-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dropdown_menu_styles',
			array(
				'label'      => esc_html__( 'Dropdown', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'dropdown_menu_container_heading',
			array(
				'label'     => esc_html__( 'Container', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'dropdown_menu_container_width',
			array(
				'label' => __( 'Container Width', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 200,
						'max' => 1980,
					),
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-container-width:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_container_bg_color',
			array(
				'label'   => __( 'Background Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-bg-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_level_heading',
			array(
				'label'     => esc_html__( 'Level', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
			)
		);

		$this->start_controls_tabs( 'tabs_dropdown_menu_level_style' );

		$this->start_controls_tab(
			'tab_dropdown_menu_top_level',
			array(
				'label' => __( 'Top', 'jet-menu' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Items Typography', 'jet-menu' ),
				'name'     => 'dropdown_top_items_typography',
				'selector' => '{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-dropdown .jet-mega-menu-item__link--top-level',
			)
		);

		$this->add_responsive_control(
			'dropdown_menu_item_vertical_padding',
			[
				'label' => __( 'Items Vertical Padding', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-top-items-ver-padding:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->add_responsive_control(
			'dropdown_menu_item_hor_padding',
			[
				'label' => __( 'Items Horizontal Padding', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-top-items-hor-padding:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->add_responsive_control(
			'dropdown_menu_item_space',
			[
				'label' => __( 'Items Space', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-top-items-gap:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_menu_sub_level',
			array(
				'label' => __( 'Sub', 'jet-menu' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Items Typography', 'jet-menu' ),
				'name'     => 'dropdown_sub_items_typography',
				'selector' => '{{WRAPPER}} .jet-mega-menu.jet-mega-menu--layout-dropdown .jet-mega-menu-item__link--sub-level',
			)
		);

		$this->add_responsive_control(
			'dropdown_menu_sub_item_vertical_padding',
			[
				'label' => __( 'Items Vertical Padding', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-sub-items-ver-padding:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->add_responsive_control(
			'dropdown_menu_sub_item_hor_padding',
			[
				'label' => __( 'Items Horizontal Padding', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-sub-items-hor-padding:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->add_responsive_control(
			'dropdown_menu_sub_item_space',
			[
				'label' => __( 'Items Space', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-sub-items-gap:{{SIZE}}{{UNIT}};',
				),
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'dropdown_menu_states_heading',
			array(
				'label'     => esc_html__( 'States', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_dropdown_menu_item_style' );

		$this->start_controls_tab(
			'tab_dropdown_menu_item_normal',
			array(
				'label' => __( 'Normal', 'jet-menu' ),
			)
		);

		$this->add_control(
			'dropdown_menu_item_icon_color',
			array(
				'label'   => __( 'Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-item-icon-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_item_title_color',
			array(
				'label'   => __( 'Title Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-item-title-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_item_badge_color',
			array(
				'label'   => __( 'Badge Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-item-badge-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_item_bg_color',
			array(
				'label'   => __( 'Background Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-item-bg-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_menu_hover_item',
			array(
				'label' => __( 'Hover', 'jet-menu' ),
			)
		);

		$this->add_control(
			'dropdown_menu_hover_item_icon_color',
			array(
				'label'   => __( 'Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-hover-item-icon-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_hover_item_title_color',
			array(
				'label'   => __( 'Title Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-hover-item-title-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_hover_item_badge_color',
			array(
				'label'   => __( 'Badge Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-hover-item-badge-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_hover_item_bg_color',
			array(
				'label'   => __( 'Background Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-hover-item-bg-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_menu_active_item',
			array(
				'label' => __( 'Active', 'jet-menu' ),
			)
		);

		$this->add_control(
			'dropdown_menu_active_item_icon_color',
			array(
				'label'   => __( 'Icon Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-active-item-icon-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_active_item_title_color',
			array(
				'label'   => __( 'Title Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-active-item-title-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_active_item_badge_color',
			array(
				'label'   => __( 'Badge Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-active-item-badge-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_active_item_bg_color',
			array(
				'label'   => __( 'Background Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-active-item-bg-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'dropdown_menu_toggle_heading',
			array(
				'label'     => esc_html__( 'Dropdown Toggle', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'dropdown_menu_toggle_size',
			array(
				'label' => __( 'Toggle Size', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-toggle-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'dropdown_menu_toggle_distance',
			array(
				'label' => __( 'Distance', 'jet-menu' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-toggle-distance:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'dropdown_menu_toggle_states' );

		$this->start_controls_tab(
			'dropdown_menu_normal_toggle_state',
			array(
				'label' => __( 'Normal', 'jet-menu' ),
			)
		);

		$this->add_control(
			'dropdown_menu_toggle_color',
			array(
				'label'   => __( 'Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-toggle-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_toggle_bg_color',
			array(
				'label'   => __( 'Container Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-toggle-bg-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dropdown_menu_toggle_hover_state',
			array(
				'label' => __( 'Hover', 'jet-menu' ),
			)
		);

		$this->add_control(
			'dropdown_menu_hover_toggle_color',
			array(
				'label'   => __( 'Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-hover-toggle-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_hover_toggle_bg_color',
			array(
				'label'   => __( 'Container Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-hover-toggle-bg-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dropdown_menu_active_hover_state',
			array(
				'label' => __( 'Active', 'jet-menu' ),
			)
		);

		$this->add_control(
			'dropdown_menu_active_toggle_color',
			array(
				'label'   => __( 'Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-active-toggle-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dropdown_menu_active_toggle_bg_color',
			array(
				'label'   => __( 'Container Color', 'jet-menu' ),
				'type'    => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--jmm-dropdown-active-toggle-bg-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'mobile_device_render_styles',
			array(
				'label'     => esc_html__( 'Mobile', 'jet-menu' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array (
					'use-mobile-device-render' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_toggle_heading',
			array(
				'label'     => esc_html__( 'Toggle', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				//'separator' => 'before',
			)
		);

		$this->add_control(
			'mobile_toggle_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__toggle .jet-mobile-menu__toggle-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jet-mobile-menu__toggle .jet-mobile-menu__toggle-text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'mobile_toggle_icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'jet-menu' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range' => array(
					'px' => array(
						'min' => 8,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu__toggle .jet-mobile-menu__toggle-icon i'   => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .jet-mobile-menu__toggle .jet-mobile-menu__toggle-icon svg' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'mobile_toggle_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__toggle' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'mobile_toggle_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .jet-mobile-menu__toggle',
			)
		);

		$this->add_control(
			'mobile_toggle_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu__toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'mobile_toggle_text_heading',
			array(
				'label'     => esc_html__( 'Toggle Text', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'mobile-toggle-text!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'     => esc_html__( 'Typography', 'jet-menu' ),
				'name'     => 'mobile_toggle_text_typography',
				'selector' => '{{WRAPPER}} .jet-mobile-menu__toggle .jet-mobile-menu__toggle-text',
				'condition' => array(
					'mobile-toggle-text!' => '',
				),
			)
		);

		$this->add_control(
			'mobile_container_box_heading',
			array(
				'label'     => esc_html__( 'Container Box', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'mobile_container_width',
			array(
				'label' => esc_html__( 'Container Width', 'jet-menu' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', '%'
				),
				'range' => array(
					'px' => array(
						'min' => 300,
						'max' => 1000,
					),
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu__container' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'mobile_container_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__container .jet-mobile-menu__container-inner' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'mobile_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu__container .jet-mobile-menu__container-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'mobile_container_z_index',
			array(
				'label' => esc_html__( 'Z Index', 'jet-menu' ),
				'type'  => Controls_Manager::NUMBER,
				'min'     => -999,
				'max'     => 99999,
				'default' => 999,
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu__container' => 'z-index: {{VALUE}}',
					'{{WRAPPER}} .jet-mobile-menu__container .jet-mobile-menu-cover' => 'z-index: calc({{VALUE}}-1)',
				),
			)
		);

		$this->add_control(
			'mobile_container_controls_heading',
			array(
				'label'     => esc_html__( 'Controls Styles', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'mobile_container_close_icon_color',
			array(
				'label'     => esc_html__( 'Close/Back Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__container .jet-mobile-menu__back i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jet-mobile-menu__container .jet-mobile-menu__back svg' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'mobile_container_close_icon_size',
			array(
				'label' => esc_html__( 'Close/Back Icon Size', 'jet-menu' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range' => array(
					'px' => array(
						'min' => 8,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu__container .jet-mobile-menu__back i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .jet-mobile-menu__container .jet-mobile-menu__back svg' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'mobile_container_back_text_color',
			array(
				'label'     => esc_html__( 'Back Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__container .jet-mobile-menu__back span' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-back-text!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'mobile_back_text_typography',
				'selector' => '{{WRAPPER}} .jet-mobile-menu__container .jet-mobile-menu__back span',
				'condition' => array(
					'mobile-back-text!' => '',
				),
			)
		);

		$this->add_control(
			'mobile_container_breadcrums_heading',
			array(
				'label'     => esc_html__( 'Breadcrumbs', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'mobile-use-breadcrumbs' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_breadcrums_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__breadcrumbs .breadcrumb-label' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-use-breadcrumbs' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_breadcrums_icon_color',
			array(
				'label'     => esc_html__( 'Divider Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__breadcrumbs .breadcrumb-divider' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-use-breadcrumbs' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'mobile_breadcrums_text_typography',
				'selector' => '{{WRAPPER}} .jet-mobile-menu__breadcrumbs .breadcrumb-label',
				'condition' => array(
					'mobile-use-breadcrumbs' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'mobile_breadcrums_icon_size',
			array(
				'label' => esc_html__( 'Divider Size', 'jet-menu' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
				),
				'range' => array(
					'px' => array(
						'min' => 8,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu__breadcrumbs .breadcrumb-divider i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .jet-mobile-menu__breadcrumbs .breadcrumb-divider svg' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'mobile-use-breadcrumbs' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_items_heading',
			array(
				'label'     => esc_html__( 'Items', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'mobile_item_icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'jet-menu' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 8,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu__item .jet-menu-icon'     => 'font-size: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-mobile-menu__item .jet-menu-icon svg' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'mobile-is-item-icon' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Label Typography', 'jet-menu' ),
				'name'     => 'mobile_item_label_typography',
				'selector' => '{{WRAPPER}} .jet-mobile-menu__item .jet-menu-label',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Sub Label Typography', 'jet-menu' ),
				'name'     => 'item_sub_label_typography',
				'selector' => '{{WRAPPER}} .jet-mobile-menu__item .mobile-sub-level-link .jet-menu-label',
				'condition' => array(
					'mobile-sub-open-layout' => 'dropdown',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Description Typography', 'jet-menu' ),
				'name'      => 'mobile_item_desc_typography',
				'selector'  => '{{WRAPPER}} .jet-mobile-menu__item .jet-menu-desc',
				'condition' => array(
					'mobile-is-item-desc' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Badge Typography', 'jet-menu' ),
				'name'     => 'mobile_item_badge_typography',
				'selector' => '{{WRAPPER}} .jet-mobile-menu__item .jet-menu-badge__inner',
				'condition' => array(
					'mobile-is-item-badge' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'mobile_item_dropdown_icon_size',
			array(
				'label' => esc_html__( 'Dropdown Icon Size', 'jet-menu' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 8,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu__item .jet-dropdown-arrow i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .jet-mobile-menu__item .jet-dropdown-arrow svg' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'mobile_menu_item_padding',
			array(
				'label'      => esc_html__( 'Item Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'mobile_menu_item_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item .jet-mobile-menu__item' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-is-item-divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'mobile_item_divider_width',
			array(
				'label' => esc_html__( 'Width', 'jet-menu' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-mobile-menu__item' => 'border-bottom-style: solid; border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'mobile-is-item-divider' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_mobile_items_style' );

		$this->start_controls_tab(
			'tab_mobile_items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-menu' ),
			)
		);

		$this->add_control(
			'mobile_item_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item .jet-menu-icon' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-is-item-icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_item_label_color',
			array(
				'label'     => esc_html__( 'Label', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item .jet-menu-label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'mobile_item_sub_label_color',
			array(
				'label'     => esc_html__( 'Sub Label', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item .mobile-sub-level-link .jet-menu-label' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-sub-open-layout' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'mobile_item_desc_color',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item .jet-menu-desc' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-is-item-desc' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_item_badge_color',
			array(
				'label'     => esc_html__( 'Badge Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item .jet-menu-badge__inner' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-is-item-badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_item_badge_bg_color',
			array(
				'label'     => esc_html__( 'Badge Background Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item .jet-menu-badge__inner' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-is-item-badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_item_dropdown_color',
			array(
				'label'     => esc_html__( 'Sub Menu Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item .jet-dropdown-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'mobile_sub_menu_item_dropdown_color',
			array(
				'label'     => esc_html__( 'Sub Menu Dropdown Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item .mobile-sub-level-link + .jet-dropdown-arrow' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-sub-open-layout' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'mobile_item_bg_color',
			array(
				'label'     => esc_html__( 'Item Background', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_mobile_items_active',
			array(
				'label' => esc_html__( 'Active', 'jet-menu' ),
			)
		);

		$this->add_control(
			'mobile_item_icon_color_active',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-menu-icon' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-is-item-icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_label_color_active',
			array(
				'label'     => esc_html__( 'Label', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-menu-label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'mobile_desc_color_active',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-menu-desc' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-is-item-desc' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_item_badge_color_active',
			array(
				'label'     => esc_html__( 'Badge Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-menu-badge__inner' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-is-item-badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_item_badge_bg_color_active',
			array(
				'label'     => esc_html__( 'Badge Background Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-menu-badge__inner' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'mobile-is-item-badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'mobile_item_dropdown_color_active',
			array(
				'label'     => esc_html__( 'Sub Menu Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner .jet-dropdown-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'mobile_item_bg_color_active',
			array(
				'label'     => esc_html__( 'Item Background', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-mobile-menu__item.jet-mobile-menu__item--active > .jet-mobile-menu__item-inner' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Get available menus list
	 *
	 * @return array
	 */
	public function get_available_menus() {

		$raw_menus = wp_get_nav_menus();
		$menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );
		$parent    = isset( $_GET['parent_menu'] ) ? absint( $_GET['parent_menu'] ) : 0;

		if ( 0 < $parent && isset( $menus[ $parent ] ) ) {
			unset( $menus[ $parent ] );
		}

		return $menus;
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings();

		if ( ! isset( $settings['menu'] ) ) {
			return;
		}

		if ( ! $settings['menu'] ) {

			if ( is_user_logged_in() ) {
				esc_html_e( 'Please, select menu.', 'jet-menu' );
			}

			return;
		} else {
			$menu = $settings['menu'];
		}

		jet_menu()->render_manager->location_manager->add_menu_advanced_styles( $menu );

		$breakpoints = Responsive::get_breakpoints();

		$dropdown_breakpoint = 'none' !== $settings['dropdown-breakpoint'] ? $settings['dropdown-breakpoint'] : 'xs';

		$is_mobile_render = $this->is_mobile_render();

		$force_mobile_render = filter_var( $settings['force-mobile-render'], FILTER_VALIDATE_BOOLEAN );

		if ( $is_mobile_render || $force_mobile_render ) {
			$render_widget_instance = new \Jet_Menu\Render\Mobile_Menu_Render( array(
				'menu-id'                   => $settings[ 'menu' ],
				'mobile-menu-id'            => $settings[ 'mobile-menu' ],
				'layout'                    => $settings[ 'mobile-layout' ],
				'toggle-position'           => $settings[ 'mobile-toggle-position' ],
				'container-position'        => $settings[ 'mobile-container-position' ],
				'item-header-template'      => $settings[ 'mobile-item-header-template' ],
				'item-before-template'      => $settings[ 'mobile-item-before-template' ],
				'item-after-template'       => $settings[ 'mobile-item-after-template' ],
				'use-breadcrumbs'           => filter_var( $settings[ 'mobile-use-breadcrumbs' ], FILTER_VALIDATE_BOOLEAN ),
				'breadcrumbs-path'          => $settings[ 'mobile-breadcrumbs-path' ],
				'toggle-text'               => $settings[ 'mobile-toggle-text' ],
				'toggle-loader'             => filter_var( $settings[ 'mobile-toggle-loader' ], FILTER_VALIDATE_BOOLEAN ),
				'back-text'                 => $settings[ 'mobile-back-text' ],
				'is-item-icon'              => filter_var( $settings[ 'mobile-is-item-icon' ], FILTER_VALIDATE_BOOLEAN ),
				'is-item-badge'             => filter_var( $settings[ 'mobile-is-item-badge' ], FILTER_VALIDATE_BOOLEAN ),
				'is-item-desc'              => filter_var( $settings[ 'mobile-is-item-desc' ], FILTER_VALIDATE_BOOLEAN ),
				'loader-color'              => $settings[ 'mobile-toggle-loader-color' ],
				'sub-menu-trigger'          => $settings[ 'mobile-sub-menu-trigger' ],
				'sub-open-layout'           => $settings[ 'mobile-sub-open-layout' ],
				'close-after-navigate'      => filter_var( $settings[ 'mobile-close-after-navigate' ], FILTER_VALIDATE_BOOLEAN ),
				'toggle-closed-icon-html'   => $this->get_icon_html( $settings[ 'mobile-toggle-closed-state-icon' ] ),
				'toggle-opened-icon-html'   => $this->get_icon_html( $settings[ 'mobile-toggle-opened-state-icon' ] ),
				'close-icon-html'           => $this->get_icon_html( $settings[ 'mobile-container-close-icon' ] ),
				'back-icon-html'            => $this->get_icon_html( $settings[ 'mobile-container-back-icon' ] ),
				'dropdown-icon-html'        => $this->get_icon_html( $settings[ 'mobile-dropdown-icon' ] ),
				'dropdown-opened-icon-html' => $this->get_icon_html( $settings[ 'mobile-dropdown-opened-icon' ] ),
				'breadcrumb-icon-html'      => $this->get_icon_html( $settings[ 'mobile-breadcrumb-icon' ] ),
			) );
		} else {
			$render_widget_instance = new \Jet_Menu\Render\Mega_Menu_Render( array(
				'menu'                => $settings[ 'menu' ],
				'roll-up'             => filter_var( $settings[ 'roll-up' ], FILTER_VALIDATE_BOOLEAN ),
				'layout'              => $settings[ 'layout' ],
				'dropdown-layout'     => $settings[ 'dropdown-layout' ],
				'dropdown-position'   => $settings[ 'dropdown-position' ],
				'sub-animation'       => $settings[ 'sub-animation' ],
				'sub-position'        => $settings[ 'sub-menu-position' ],
				'sub-event'           => $settings[ 'sub-menu-event' ],
				'sub-trigger'         => $settings[ 'sub-menu-trigger' ],
				'mega-width-type'     => $settings[ 'mega-width-type' ],
				'mega-width-selector' => $settings[ 'mega-width-selector' ],
				'breakpoint'          => $breakpoints[ $dropdown_breakpoint ],
				'roll-up-type'        => $settings[ 'roll-up-type' ],
				'roll-up-item-text'   => $settings[ 'roll-up-item-text' ],
				'roll-up-item-icon'   => $this->get_icon_html( $settings[ 'roll-up-item-icon' ] ),
				'dropdown-icon'       => $this->get_icon_html( $settings[ 'dropdown-icon' ] ),
				'toggle-default-icon' => $this->get_icon_html( $settings[ 'toggle-default-icon' ] ),
				'toggle-opened-icon'  => $this->get_icon_html( $settings[ 'toggle-opened-icon' ] ),
				'location'            => 'elementor',
			) );
		}

		$render_widget_instance->render();

		if ( $this->is_css_required() ) {
			$dynamic_css = jet_menu()->dynamic_css_manager;

			add_filter( 'cx_dynamic_css/collector/localize_object', array( $this, 'fix_preview_css' ) );
			$dynamic_css->collector->print_style();
			remove_filter( 'cx_dynamic_css/collector/localize_object', array( $this, 'fix_preview_css' ) );
		}
	}

	/**
	 * @return bool
	 */
	public function is_mobile_render() {
		$settings = $this->get_settings();

		$current_device = jet_menu_tools()->get_current_device();
		$use_mobile_device_render = filter_var( $settings['use-mobile-device-render'], FILTER_VALIDATE_BOOLEAN );

		if ( 'desktop' === $current_device || ! $use_mobile_device_render ) {
			return false;
		}

		$device_for_mobile_render = $settings['device-for-mobile-render'];

		if ( 'tablet-mobile' === $device_for_mobile_render && ( 'tablet' === $current_device || 'mobile' === $current_device ) ) {
			return true;
		}

		if ( 'mobile' === $device_for_mobile_render && 'mobile' === $current_device ) {
			return true;
		}

		return false;
	}

	/**
	 * [get_icon_html description]
	 * @param  boolean $icon_setting [description]
	 * @return [type]                [description]
	 */
	public function get_icon_html( $icon_setting = false, $attr = array() ) {

		if ( ! $icon_setting ) {
			return false;
		}

		ob_start();
		Icons_Manager::render_icon( $icon_setting, $attr );
		return ob_get_clean();
	}

	/**
	 * Check if need to insert custom CSS
	 * @return boolean [description]
	 */
	public function is_css_required() {

		$allowed_actions = array( 'elementor_render_widget', 'elementor', 'elementor_ajax' );

		if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $allowed_actions ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Fix preview styles
	 *
	 * @return array
	 */
	public function fix_preview_css( $data ) {

		if ( ! empty( $data['css'] ) ) {
			printf( '<style>%s</style>', html_entity_decode( $data['css'] ) );
		}

		return $data;
	}

}
