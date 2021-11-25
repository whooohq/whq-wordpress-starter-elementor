<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * HTML Widget
 */
class Jet_Widget_Mobile_Menu extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'jet-mobile-menu';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Hamburger Menu', 'jet-menu' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'jet-menu-icon-hamburger-menu';
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
			'section_options',
			array(
				'label' => esc_html__( 'Options', 'jet-menu' ),
			)
		);

		$this->add_control(
			'menu',
			array(
				'label'   => esc_html__( 'Select Menu', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_available_menus(),
				'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'jet-menu' ), admin_url( 'nav-menus.php' ) ),
			)
		);

		$this->add_control(
			'mobile_menu',
			array(
				'label'   => esc_html__( 'Select Menu for Mobile(Phone)', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_available_menus(),
			)
		);

		$this->add_control(
			'layout',
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
			'toggle-position',
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
					'layout' => 'slide-out',
				),
			)
		);

		$this->add_control(
			'container-position',
			array(
				'label'   => esc_html__( 'Container Position', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'right' => esc_html__( 'Right', 'jet-menu' ),
					'left'  => esc_html__( 'Left', 'jet-menu' ),
				),
			)
		);

		$this->add_control(
			'sub-menu-trigger',
			array(
				'label'   => esc_html__( 'Show Sub Menu Trigger', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'item',
				'options' => array(
					'item'       => esc_html__( 'Menu Item', 'jet-menu' ),
					'submarker'  => esc_html__( 'Sub Menu Icon', 'jet-menu' ),
				),
			)
		);

		$this->add_control(
			'sub-open-layout',
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
			'close-after-navigate',
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
			'item_header_template',
			array(
				'label'       => esc_html__( 'Choose Header Template', 'jet-menu' ),
				'label_block' => 'true',
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
			)
		);

		$this->add_control(
			'item_before_template',
			array(
				'label'       => esc_html__( 'Choose Before Items Template', 'jet-menu' ),
				'label_block' => 'true',
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
			)
		);

		$this->add_control(
			'item_after_template',
			array(
				'label'       => esc_html__( 'Choose After Items Template', 'jet-menu' ),
				'label_block' => 'true',
				'type'        => 'jet-query',
				'query_type'  => 'elementor_templates',
			)
		);

		$this->add_control(
			'is_item_icon',
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
			'is_item_badge',
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
			'is_item_desc',
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
			'is_item_divider',
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
			'use_breadcrumbs',
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
			'breadcrumbs_path',
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
			'toggle_loader',
			array(
				'label'        => esc_html__( 'Use Toggle Button Loader?', 'jet-menu' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
				'label_off'    => esc_html__( 'No', 'jet-manu' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'jet-menu' ),
			)
		);

		$this->add_control(
			'toggle_closed_state_icon',
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
			'toggle_opened_state_icon',
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
			'toggle_text',
			array(
				'label'   => esc_html__( 'Toggle Text', 'jet-menu' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'container_close_icon',
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
			'container_back_icon',
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
			'back_text',
			array(
				'label'   => esc_html__( 'Back Text', 'jet-menu' ),
				'type'    => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'dropdown_icon',
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
			'dropdown_opened_icon',
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
					'sub-open-layout' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'breadcrumb_icon',
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
					'use_breadcrumbs' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$css_scheme = apply_filters(
			'jet-menu/mobile-menu/css-scheme',
			array(
				'widget_instance' => '.jet-mobile-menu-widget',
				'toggle'          => '.jet-mobile-menu__toggle',
				'container'       => '.jet-mobile-menu__container',
				'breadcrumbs'     => '.jet-mobile-menu__breadcrumbs',
				'item'            => '.jet-mobile-menu__item',
			)
		);

		/**
		 * Toggle Style Section
		 */
		$this->start_controls_section(
			'section_mobile_menu_toggle_style',
			array(
				'label'      => esc_html__( 'Toggle', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'toggle_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] . ' .jet-mobile-menu__toggle-icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_icon_size',
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
					'{{WRAPPER}} ' . $css_scheme['toggle'] . ' .jet-mobile-menu__toggle-icon i'   => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['toggle'] . ' .jet-mobile-menu__toggle-icon svg' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'toggle_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'toggle_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['toggle'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'toggle_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'],
			)
		);

		$this->add_control(
			'toggle_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'toggle_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'menu_toggle_text_heading',
			array(
				'label'     => esc_html__( 'Toggle Text', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'toggle_text!' => '',
				),
			)
		);

		$this->add_control(
			'toggle_text_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['toggle'] . ' .jet-mobile-menu__toggle-text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_text_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['toggle'] . ' .jet-mobile-menu__toggle-text',
				'condition' => array(
					'toggle_text!' => '',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Container Style Section
		 */
		$this->start_controls_section(
			'section_mobile_menu_container_style',
			array(
				'label'      => esc_html__( 'Container', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'menu_container_navi_controls_heading',
			array(
				'label'     => esc_html__( 'Navigation Controls', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'navi_controls_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__controls' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'navi_controls_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'selector'    => '{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__controls',
			)
		);

		$this->add_control(
			'menu_container_icons_heading',
			array(
				'label'     => esc_html__( 'Controls Styles', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'container_close_icon_color',
			array(
				'label'     => esc_html__( 'Close/Back Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back svg' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'container_close_icon_size',
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
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back svg' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'container_back_text_color',
			array(
				'label'     => esc_html__( 'Back Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back span' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'back_text!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'back_text_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__back span',
				'condition' => array(
					'back_text!' => '',
				),
			)
		);

		$this->add_control(
			'menu_container_breadcrums_heading',
			array(
				'label'     => esc_html__( 'Breadcrumbs', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'use_breadcrumbs' => 'yes',
				),
			)
		);

		$this->add_control(
			'breadcrums_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['breadcrumbs'] . ' .breadcrumb-label' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'use_breadcrumbs' => 'yes',
				),
			)
		);

		$this->add_control(
			'breadcrums_icon_color',
			array(
				'label'     => esc_html__( 'Divider Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['breadcrumbs'] . ' .breadcrumb-divider' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'use_breadcrumbs' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'breadcrums_text_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['breadcrumbs'] . ' .breadcrumb-label',
				'condition' => array(
					'use_breadcrumbs' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'breadcrums_icon_size',
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
					'{{WRAPPER}} ' . $css_scheme['breadcrumbs'] . ' .breadcrumb-divider i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['breadcrumbs'] . ' .breadcrumb-divider svg' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'use_breadcrumbs' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_container_box_heading',
			array(
				'label'     => esc_html__( 'Container Box', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'container_width',
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
					'{{WRAPPER}} ' . $css_scheme['container'] => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_bg',
				'selector' => '{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__container-inner',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'container_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'selector'    => '{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__container-inner',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['container'],
			)
		);

		$this->add_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__container-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__container-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_z_index',
			array(
				'label' => esc_html__( 'Z Index', 'jet-menu' ),
				'type'  => Controls_Manager::NUMBER,
				'min'     => -999,
				'max'     => 99999,
				'default' => 999,
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'z-index: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['widget_instance'] . ' .jet-mobile-menu-cover' => 'z-index: calc({{VALUE}}-1)',
				),
			)
		);

		$this->add_control(
			'menu_container_box_header_template_heading',
			array(
				'label'     => esc_html__( 'Header Template', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'item_header_template!' => '',
				),
			)
		);

		$this->add_control(
			'header_template_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__header-template' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'item_header_template!' => '',
				),
			)
		);

		$this->add_control(
			'menu_container_box_before_template_heading',
			array(
				'label'     => esc_html__( 'Before Template', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'item_before_template!' => '',
				),
			)
		);

		$this->add_control(
			'before_template_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__before-template' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'item_before_template!' => '',
				),
			)
		);

		$this->add_control(
			'menu_container_box_after_template_heading',
			array(
				'label'     => esc_html__( 'After Template', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'item_after_template!' => '',
				),
			)
		);

		$this->add_control(
			'after_template_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] . ' .jet-mobile-menu__after-template' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'item_after_template!' => '',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Items Style Section
		 */
		$this->start_controls_section(
			'section_mobile_menu_items_style',
			array(
				'label'      => esc_html__( 'Items', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'menu_item_label_icon',
			array(
				'label'     => esc_html__( 'Icon', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'is_item_icon' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'menu_item_icon_size',
			array(
				'label' => esc_html__( 'Size', 'jet-menu' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 8,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-icon'     => 'font-size: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-icon svg' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'is_item_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_icon_ver_position',
			array(
				'label'   => esc_html__( 'Vertical Position', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'flex-start' => esc_html__( 'Top', 'jet-menu' ),
					'center'     => esc_html__( 'Center', 'jet-menu' ),
					'flex-end'   => esc_html__( 'Bottom', 'jet-menu' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-icon' => 'align-self: {{VALUE}}',
				),
				'condition' => array(
					'is_item_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_icon_hor_position',
			array(
				'label'   => esc_html__( 'Horizontal Aligment', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'flex-start' => esc_html__( 'Left', 'jet-menu' ),
					'center'     => esc_html__( 'Center', 'jet-menu' ),
					'flex-end'   => esc_html__( 'Right', 'jet-menu' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-icon' => 'justify-content: {{VALUE}}',
				),
				'condition' => array(
					'is_item_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_label_heading',
			array(
				'label'     => esc_html__( 'Label', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Label Typography', 'jet-menu' ),
				'name'     => 'item_label_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-label',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Sub Label Typography', 'jet-menu' ),
				'name'     => 'item_sub_label_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' .mobile-sub-level-link .jet-menu-label',
				'condition' => array(
					'sub-open-layout' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'menu_item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'menu_item_desc_heading',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'is_item_desc' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_desc_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-desc',
				'condition' => array(
					'is_item_desc' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_badge_heading',
			array(
				'label'     => esc_html__( 'Badge', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'is_item_badge' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_badge_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge__inner',
				'condition' => array(
					'is_item_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_badge_ver_position',
			array(
				'label'   => esc_html__( 'Vertical Position', 'jet-menu' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'flex-start',
				'options' => array(
					'flex-start' => esc_html__( 'Top', 'jet-menu' ),
					'center'     => esc_html__( 'Center', 'jet-menu' ),
					'flex-end'   => esc_html__( 'Bottom', 'jet-menu' ),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge' => 'align-self: {{VALUE}}',
				),
				'condition' => array(
					'is_item_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_badge_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'is_item_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_badge_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'is_item_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_dropdown_heading',
			array(
				'label'     => esc_html__( 'Sub Menu Button', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'menu_item_dropdown_icon_size',
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
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-dropdown-arrow i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-dropdown-arrow svg' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'menu_item_divider_heading',
			array(
				'label'     => esc_html__( 'Divider', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'is_item_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'menu_item_divider_width',
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
					'{{WRAPPER}} ' . $css_scheme['item'] => 'border-bottom-style: solid; border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'is_item_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_state_heading',
			array(
				'label'     => esc_html__( 'States', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_menu_items_style' );

		$this->start_controls_tab(
			'tab_menu_items_normal',
			array(
				'label' => esc_html__( 'Normal', 'jet-menu' ),
			)
		);

		$this->add_control(
			'menu_item_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-icon' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'item_label_color',
			array(
				'label'     => esc_html__( 'Label', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_sub_label_color',
			array(
				'label'     => esc_html__( 'Sub Label', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .mobile-sub-level-link .jet-menu-label' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'sub-open-layout' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'item_desc_color',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-desc' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_desc' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_badge_color',
			array(
				'label'     => esc_html__( 'Badge Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge__inner' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_badge_bg_color',
			array(
				'label'     => esc_html__( 'Badge Background Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-badge__inner' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_dropdown_color',
			array(
				'label'     => esc_html__( 'Sub Menu Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-dropdown-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'sub_menu_item_dropdown_color',
			array(
				'label'     => esc_html__( 'Sub Menu Dropdown Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .mobile-sub-level-link + .jet-dropdown-arrow' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'sub-open-layout' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'menu_item_bg_color',
			array(
				'label'     => esc_html__( 'Item Background', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_items_hover',
			array(
				'label' => esc_html__( 'Hover', 'jet-menu' ),
			)
		);

		$this->add_control(
			'menu_item_icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover .jet-menu-icon' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'item_label_color_hover',
			array(
				'label'     => esc_html__( 'Label', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover .jet-menu-label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_desc_color_hover',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover .jet-menu-desc' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_desc' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_badge_color_hover',
			array(
				'label'     => esc_html__( 'Badge Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover .jet-menu-badge__inner' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_badge_bg_color_hover',
			array(
				'label'     => esc_html__( 'Badge Background Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover .jet-menu-badge__inner' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_dropdown_color_hover',
			array(
				'label'     => esc_html__( 'Sub Menu Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover .jet-dropdown-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_item_bg_color_hover',
			array(
				'label'     => esc_html__( 'Item Background', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . ':hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_items_active',
			array(
				'label' => esc_html__( 'Active', 'jet-menu' ),
			)
		);

		$this->add_control(
			'menu_item_icon_color_active',
			array(
				'label'     => esc_html__( 'Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active .jet-menu-icon' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'item_label_color_active',
			array(
				'label'     => esc_html__( 'Label', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active .jet-menu-label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'item_desc_color_active',
			array(
				'label'     => esc_html__( 'Description', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active .jet-menu-desc' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_desc' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_badge_color_active',
			array(
				'label'     => esc_html__( 'Badge Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active .jet-menu-badge__inner' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_badge_bg_color_active',
			array(
				'label'     => esc_html__( 'Badge Background Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active .jet-menu-badge__inner' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'is_item_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'menu_item_dropdown_color_active',
			array(
				'label'     => esc_html__( 'Sub Menu Icon Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active .jet-dropdown-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'menu_item_bg_color_active',
			array(
				'label'     => esc_html__( 'Item Background', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['item'] . '.jet-mobile-menu__item--active' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_mobile_menu_advanced_style',
			array(
				'label'      => esc_html__( 'Advanced', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'mobile_menu_loader_color',
			array(
				'label'       => esc_html__( 'Loader Color', 'jet-menu' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#3a3a3a',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'mobile_menu_cover_color',
			array(
				'label'     => esc_html__( 'Cover Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['widget_instance'] . ' .jet-mobile-menu-cover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'layout' => array( 'slide-out' ),
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Get available menus list
	 *
	 * @return array
	 */
	public function get_available_menus() {

		$raw_menus = wp_get_nav_menus();

		$menus_list = array(
			'0' => esc_html__( '- Select Menu -', 'jet-menu' ),
		);

		foreach ( $raw_menus as $key => $menu_obj ) {
			$menus_list[ $menu_obj->term_id ] = $menu_obj->name;
		}

		return $menus_list;
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

		if ( ! isset( $settings['menu'] ) || empty( $settings['menu'] ) ) {
			echo '<span>' . _e( 'Menu undefined', 'jet-menu' ) . '</span>';
			return;
		}

		$render_widget_instance = new \Jet_Menu\Render\Mobile_Menu_Render( array(
			'menu-id'                   => $settings[ 'menu' ],
			'mobile-menu-id'            => $settings[ 'mobile_menu' ],
			'layout'                    => $settings[ 'layout' ],
			'toggle-position'           => $settings[ 'toggle-position' ],
			'container-position'        => $settings[ 'container-position' ],
			'item-header-template'      => $settings[ 'item_header_template' ],
			'item-before-template'      => $settings[ 'item_before_template' ],
			'item-after-template'       => $settings[ 'item_after_template' ],
			'use-breadcrumbs'           => filter_var( $settings[ 'use_breadcrumbs' ], FILTER_VALIDATE_BOOLEAN ),
			'breadcrumbs-path'          => $settings[ 'breadcrumbs_path' ],
			'toggle-text'               => $settings[ 'toggle_text' ],
			'toggle-loader'             => filter_var( $settings[ 'toggle_loader' ], FILTER_VALIDATE_BOOLEAN ),
			'back-text'                 => $settings[ 'back_text' ],
			'is-item-icon'              => filter_var( $settings[ 'is_item_icon' ], FILTER_VALIDATE_BOOLEAN ),
			'is-item-badge'             => filter_var( $settings[ 'is_item_badge' ], FILTER_VALIDATE_BOOLEAN ),
			'is-item-desc'              => filter_var( $settings[ 'is_item_desc' ], FILTER_VALIDATE_BOOLEAN ),
			'loader-color'              => $settings[ 'mobile_menu_loader_color' ],
			'sub-menu-trigger'          => $settings[ 'sub-menu-trigger' ],
			'sub-open-layout'           => $settings[ 'sub-open-layout' ],
			'close-after-navigate'      => filter_var( $settings[ 'close-after-navigate' ], FILTER_VALIDATE_BOOLEAN ),
			'toggle-closed-icon-html'   => $this->get_icon_html( $settings[ 'toggle_closed_state_icon' ] ),
			'toggle-opened-icon-html'   => $this->get_icon_html( $settings[ 'toggle_opened_state_icon' ] ),
			'close-icon-html'           => $this->get_icon_html( $settings[ 'container_close_icon' ] ),
			'back-icon-html'            => $this->get_icon_html( $settings[ 'container_back_icon' ] ),
			'dropdown-icon-html'        => $this->get_icon_html( $settings[ 'dropdown_icon' ] ),
			'dropdown-opened-icon-html' => $this->get_icon_html( $settings[ 'dropdown_opened_icon' ] ),
			'breadcrumb-icon-html'      => $this->get_icon_html( $settings[ 'breadcrumb_icon' ] ),
		) );

		$render_widget_instance->render();

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
}
