<?php
namespace Elementor;

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
			'section_general',
			array(
				'label' => esc_html__( 'General', 'jet-menu' ),
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
				'menu',
				array(
					'label'   => esc_html__( 'Select Menu for Desktop', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => $this->get_available_menus(),
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'jet-menu' ), admin_url( 'nav-menus.php' ) ),
				)
			);

			$this->add_control(
				'mobile_menu',
				array(
					'label'   => esc_html__( 'Select Menu for Mobile', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => $this->get_available_menus(),
				)
			);

			$this->add_control(
				'device-view',
				array(
					'label'       => esc_html__( 'Device View', 'jet-menu' ),
					'description' => __( 'Choose witch menu view you want to display', 'jet-menu' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'both',
					'options' => array(
						'both'    => esc_html__( 'Desktop and mobile view', 'jet-menu' ),
						'desktop' => esc_html__( 'Desktop view on all devices', 'jet-menu' ),
						'mobile'  => esc_html__( 'Mobile view on all devices', 'jet-menu' ),
					),
				)
			);

			$this->add_control(
				'force-editor-device',
				array(
					'type'    => 'hidden',
					'default' => false,
				)
			);

			do_action( 'jet-menu/widgets/mega-menu/controls', $this );

		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_mobile_layout',
			array(
				'label' => esc_html__( 'Mobile Layout', 'jet-menu' ),
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
			'jet-menu/mega-menu/css-scheme',
			array(
				'container'                    => '.jet-menu',
				'top_level_item'               => '.jet-menu > .jet-menu-item',
				'top_level_link'               => '.jet-menu .jet-menu-item .top-level-link',
				'top_level_link_hover'         => '.jet-menu .jet-menu-item:hover > .top-level-link',
				'top_level_link_active'        => '.jet-menu .jet-menu-item.jet-current-menu-item .top-level-link',
				'top_level_desc'               => '.jet-menu .jet-menu-item .jet-menu-item-desc.top-level-desc',
				'top_level_desc_hover'         => '.jet-menu .jet-menu-item:hover > .top-level-link .jet-menu-item-desc.top-level-desc',
				'top_level_desc_active'        => '.jet-menu .jet-menu-item.jet-current-menu-item .jet-menu-item-desc.top-level-desc',
				'top_level_icon'               => '.jet-menu .jet-menu-item .top-level-link .jet-menu-icon',
				'top_level_icon_hover'         => '.jet-menu .jet-menu-item:hover > .top-level-link .jet-menu-icon',
				'top_level_icon_active'        => '.jet-menu .jet-menu-item.jet-current-menu-item .top-level-link .jet-menu-icon',
				'top_level_arrow'              => '.jet-menu .jet-menu-item .top-level-link .jet-dropdown-arrow',
				'top_level_arrow_hover'        => '.jet-menu .jet-menu-item:hover > .top-level-link .jet-dropdown-arrow',
				'top_level_arrow_active'       => '.jet-menu .jet-menu-item.jet-current-menu-item .top-level-link .jet-dropdown-arrow',
				'top_level_badge'              => '.jet-menu .jet-menu-item .top-level-link .jet-menu-badge__inner',
				'top_level_badge_wrapper'      => '.jet-menu .jet-menu-item .top-level-link .jet-menu-badge',
				'first_top_level_link'           => '.jet-menu > .jet-regular-item:first-child .top-level-link',
				'first_top_level_link_hover'     => '.jet-menu > .jet-regular-item:first-child:hover > .top-level-link',
				'first_top_level_link_active'    => '.jet-menu > .jet-regular-item:first-child.jet-current-menu-item .top-level-link',
				'last_top_level_link'          => '.jet-menu > .jet-regular-item.jet-has-roll-up:nth-last-child(2) .top-level-link',
				'last_top_level_link_2'        => '.jet-menu > .jet-regular-item.jet-no-roll-up:nth-last-child(1) .top-level-link',
				'last_top_level_link_3'        => '.jet-menu > .jet-responsive-menu-available-items:last-child .top-level-link',
				'last_top_level_link_hover'    => '.jet-menu > .jet-regular-item.jet-has-roll-up:nth-last-child(2):hover .top-level-link',
				'last_top_level_link_2_hover'  => '.jet-menu > .jet-regular-item.jet-no-roll-up:nth-last-child(1):hover .top-level-link',
				'last_top_level_link_3_hover'  => '.jet-menu > .jet-responsive-menu-available-items:last-child:hover .top-level-link',
				'last_top_level_link_active'   => '.jet-menu > .jet-regular-item.jet-current-menu-item.jet-has-roll-up:nth-last-child(2) .top-level-link',
				'last_top_level_link_2_active' => '.jet-menu > .jet-regular-item.jet-current-menu-item.jet-no-roll-up:nth-last-child(1) .top-level-link',
				'last_top_level_link_3_active' => '.jet-menu > .jet-responsive-menu-available-items.jet-current-menu-item:last-child .top-level-link',

				'simple_sub_panel' => '.jet-menu ul.jet-sub-menu',
				'mega_sub_panel'   => '.jet-menu div.jet-sub-mega-menu',

				'sub_level_link'              => '.jet-menu li.jet-sub-menu-item .sub-level-link',
				'sub_level_link_hover'        => '.jet-menu li.jet-sub-menu-item:hover > .sub-level-link',
				'sub_level_link_active'       => '.jet-menu li.jet-sub-menu-item.jet-current-menu-item .sub-level-link',
				'sub_level_desc'              => '.jet-menu .jet-menu-item-desc.sub-level-desc',
				'sub_level_desc_hover'        => '.jet-menu li.jet-sub-menu-item:hover > .sub-level-link .jet-menu-item-desc.sub-level-desc',
				'sub_level_desc_active'       => '.jet-menu li.jet-sub-menu-item.jet-current-menu-item .jet-menu-item-desc.sub-level-desc',
				'sub_level_icon'              => '.jet-menu .jet-menu-item .sub-level-link .jet-menu-icon',
				'sub_level_icon_hover'        => '.jet-menu .jet-menu-item:hover > .sub-level-link .jet-menu-icon',
				'sub_level_icon_active'       => '.jet-menu li.jet-sub-menu-item.jet-current-menu-item .sub-level-link .jet-menu-icon',
				'sub_level_arrow'             => '.jet-menu .jet-menu-item .sub-level-link .jet-dropdown-arrow',
				'sub_level_arrow_hover'       => '.jet-menu .jet-menu-item:hover > .sub-level-link .jet-dropdown-arrow',
				'sub_level_arrow_active'      => '.jet-menu li.jet-sub-menu-item.jet-current-menu-item .sub-level-link .jet-dropdown-arrow',
				'sub_level_badge'             => '.jet-menu .jet-menu-item .sub-level-link .jet-menu-badge__inner',
				'sub_level_badge_wrapper'     => '.jet-menu .jet-menu-item .sub-level-link .jet-menu-badge',
				'first_sub_level_link'        => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item:first-child > .sub-level-link',
				'first_sub_level_link_hover'  => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item:first-child:hover > .sub-level-link',
				'first_sub_level_link_active' => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item.jet-current-menu-item:first-child > .sub-level-link',
				'last_sub_level_link'         => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item:last-child > .sub-level-link',
				'last_sub_level_link_hover'   => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item:last-child:hover > .sub-level-link',
				'last_sub_level_link_active'  => '.jet-menu .jet-sub-menu > li.jet-sub-menu-item.jet-current-menu-item:last-child > .sub-level-link',

				'mobile_cover'     => '.jet-mobile-menu-cover',

				'widget_instance'  => '.jet-mobile-menu-widget',
				'toggle'           => '.jet-mobile-menu__toggle',
				'mobile_container' => '.jet-mobile-menu__container',
				'breadcrumbs'      => '.jet-mobile-menu__breadcrumbs',
				'item'             => '.jet-mobile-menu__item',
			)
		);

		/**
		 * `Menu Container` Style Section
		 */
		$this->start_controls_section(
			'section_menu_container_style',
			array(
				'label'      => esc_html__( 'Desktop Container', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'menu_container_alignment',
			array(
				'label'   => esc_html__( 'Menu Items Alignment', 'jet-menu' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'jet-menu' ),
						'icon'  => ! is_rtl() ? 'fa fa-align-left' : 'fa fa-align-right',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'jet-menu' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'End', 'jet-menu' ),
						'icon'  => ! is_rtl() ? 'fa fa-align-right' : 'fa fa-align-left',
					),
					'stretch' => array(
						'title' => esc_html__( 'Stretch', 'jet-menu' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'justify-content: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'menu_container_alignment_misc',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'styles',
				'selectors' => array(
					'.jet-desktop-menu-active {{WRAPPER}} ' . $css_scheme['top_level_item'] => 'flex-grow: 0;',
				),
				'condition' => array(
					'menu_container_alignment!' => 'stretch',
				),
			)
		);

		$this->add_control(
			'menu_container_alignment_stretch',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'styles',
				'selectors' => array(
					'.jet-desktop-menu-active {{WRAPPER}} ' . $css_scheme['top_level_item'] => 'flex-grow: 1;',
					'.jet-desktop-menu-active {{WRAPPER}} ' . $css_scheme['top_level_item'] . ' > a' => 'justify-content: center;',
				),
				'condition' => array(
					'menu_container_alignment' => 'stretch',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'menu_container_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['container'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'menu_container_border',
				'selector' => '{{WRAPPER}} ' . $css_scheme['container'],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'menu_container_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['container'],
			)
		);

		$this->add_responsive_control(
			'menu_container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'inherit_first_items_border_radius',
			array(
				'label'     => esc_html__( 'First menu item inherit border radius', 'jet-menu' ),
				'description' => esc_html__( 'Inherit border radius for the first menu item from main container', 'jet-menu' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'jet-menu' ),
				'label_off' => esc_html__( 'No', 'jet-menu' ),
				'default'   => '',
				'selectors' => array(
					'(desktop){{WRAPPER}} ' . $css_scheme['first_top_level_link'] => 'border-top-left-radius: {{menu_container_border_radius.TOP}}{{menu_container_border_radius.UNIT}};border-bottom-left-radius: {{menu_container_border_radius.LEFT}}{{menu_container_border_radius.UNIT}};',
					'(tablet){{WRAPPER}} ' . $css_scheme['first_top_level_link']  => 'border-top-left-radius: {{menu_container_border_radius_tablet.TOP}}{{menu_container_border_radius_tablet.UNIT}};border-bottom-left-radius: {{menu_container_border_radius_tablet.LEFT}}{{menu_container_border_radius_tablet.UNIT}};',
					'(mobile){{WRAPPER}} ' . $css_scheme['first_top_level_link']  => 'border-top-left-radius: {{menu_container_border_radius_mobile.TOP}}{{menu_container_border_radius_mobile.UNIT}};border-bottom-left-radius: {{menu_container_border_radius_mobile.LEFT}}{{menu_container_border_radius_mobile.UNIT}};',
				),
			)
		);

		$this->add_control(
			'inherit_last_items_border_radius',
			array(
				'label'     => esc_html__( 'Last menu item inherit border radius', 'jet-menu' ),
				'description'     => esc_html__( 'Inherit border radius for the last menu item from main container', 'jet-menu' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'jet-menu' ),
				'label_off' => esc_html__( 'No', 'jet-menu' ),
				'default'   => '',
				'selectors' => array(
					'(desktop){{WRAPPER}} ' . $css_scheme['last_top_level_link']   => 'border-top-right-radius: {{menu_container_border_radius.RIGHT}}{{menu_container_border_radius.UNIT}};border-bottom-right-radius: {{menu_container_border_radius.BOTTOM}}{{menu_container_border_radius.UNIT}};',
					'(desktop){{WRAPPER}} ' . $css_scheme['last_top_level_link_2'] => 'border-top-right-radius: {{menu_container_border_radius.RIGHT}}{{menu_container_border_radius.UNIT}};border-bottom-right-radius: {{menu_container_border_radius.BOTTOM}}{{menu_container_border_radius.UNIT}};',
					'(desktop){{WRAPPER}} ' . $css_scheme['last_top_level_link_3'] => 'border-top-right-radius: {{menu_container_border_radius.RIGHT}}{{menu_container_border_radius.UNIT}};border-bottom-right-radius: {{menu_container_border_radius.BOTTOM}}{{menu_container_border_radius.UNIT}};',

					'(tablet){{WRAPPER}} ' . $css_scheme['last_top_level_link']    => 'border-top-right-radius: {{menu_container_border_radius_tablet.RIGHT}}{{menu_container_border_radius_tablet.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_tablet.BOTTOM}}{{menu_container_border_radius_tablet.UNIT}};',
					'(tablet){{WRAPPER}} ' . $css_scheme['last_top_level_link_2']  => 'border-top-right-radius: {{menu_container_border_radius_tablet.RIGHT}}{{menu_container_border_radius_tablet.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_tablet.BOTTOM}}{{menu_container_border_radius_tablet.UNIT}};',
					'(tablet){{WRAPPER}} ' . $css_scheme['last_top_level_link_3']  => 'border-top-right-radius: {{menu_container_border_radius_tablet.RIGHT}}{{menu_container_border_radius_tablet.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_tablet.BOTTOM}}{{menu_container_border_radius_tablet.UNIT}};',

					'(mobile){{WRAPPER}} ' . $css_scheme['last_top_level_link']    => 'border-top-right-radius: {{menu_container_border_radius_mobile.RIGHT}}{{menu_container_border_radius_mobile.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_mobile.BOTTOM}}{{menu_container_border_radius_mobile.UNIT}};',
					'(mobile){{WRAPPER}} ' . $css_scheme['last_top_level_link_2']  => 'border-top-right-radius: {{menu_container_border_radius_mobile.RIGHT}}{{menu_container_border_radius_mobile.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_mobile.BOTTOM}}{{menu_container_border_radius_mobile.UNIT}};',
					'(mobile){{WRAPPER}} ' . $css_scheme['last_top_level_link_3']  => 'border-top-right-radius: {{menu_container_border_radius_mobile.RIGHT}}{{menu_container_border_radius_mobile.UNIT}};border-bottom-right-radius: {{menu_container_border_radius_mobile.BOTTOM}}{{menu_container_border_radius_mobile.UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'menu_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'menu_container_min_width',
			array(
				'label'       => esc_html__( 'Min Width (px)', 'jet-menu' ),
				'description' => esc_html__( 'Set 0 to automatic width detection', 'jet-menu' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 900,
					),
				),
				'selectors'   => array(
					'.jet-desktop-menu-active {{WRAPPER}} ' . $css_scheme['container'] => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * `Main Menu Items` Style Section
		 */
		$this->start_controls_section(
			'section_main_menu_style',
			array(
				'label'      => esc_html__( 'Desktop Menu Items', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'top_item_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['top_level_link'],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Description typography', 'jet-menu' ),
				'name'     => 'top_desc_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['top_level_desc'],
			)
		);

		$this->add_control(
			'top_item_max_width',
			array(
				'label'       => esc_html__( 'Item max width (%)', 'jet-menu' ),
				'description' => esc_html__( 'Leave empty to automatic width detection', 'jet-menu' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => '%',
				),
				'selectors' => array(
					'.jet-desktop-menu-active {{WRAPPER}} ' . $css_scheme['top_level_item'] => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_main_items_style' );

		$state_tabs = array(
			'normal' => esc_html__( 'Normal', 'jet-menu' ),
			'hover'  => esc_html__( 'Hover', 'jet-menu' ),
			'active' => esc_html__( 'Active', 'jet-menu' ),
		);

		foreach( $state_tabs as $tab => $label ) {

			$suffix = ( 'normal' !== $tab ) ? '_' . $tab : '';

			$this->start_controls_tab(
				'tab_main_items_' . $tab,
				array(
					'label' => $label,
				)
			);

			$this->add_control(
				'top_item_text_color' . $suffix,
				array(
					'label'=> esc_html__( 'Text color', 'jet-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'top_item_desc_color' . $suffix,
				array(
					'label'=> esc_html__( 'Description color', 'jet-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_desc' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'top_item_icon_color' . $suffix,
				array(
					'label'=> esc_html__( 'Icon color', 'jet-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_icon' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'top_item_drop_down_arrow_color' . $suffix,
				array(
					'label'=> esc_html__( 'Drop-down arrow color', 'jet-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_arrow' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'top_item_background' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ],
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'top_item_border' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ],
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'top_item_box_shadow' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'top_level_link'. $suffix ],
				)
			);

			$this->add_responsive_control(
				'top_item_border_radius' . $suffix,
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'top_item_padding' . $suffix,
				array(
					'label'      => esc_html__( 'Padding', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'top_item_margin' . $suffix,
				array(
					'label'      => esc_html__( 'Margin', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'top_level_link' . $suffix ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'top_item_custom_style_heading' . $suffix,
				array(
					'label'     => esc_html__( 'Main Menu Items Custom Style', 'jet-menu' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'top_first_item_custom_styles' . $suffix,
				array(
					'label'        => esc_html__( 'First Item Custom Styles', 'jet-menu' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
					'label_off'    => esc_html__( 'No', 'jet-menu' ),
					'return_value' => 'yes',
					'default'      => 'false',
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'      => 'top_first_item_background' . $suffix,
					'selector'  => '{{WRAPPER}} ' . $css_scheme[ 'first_top_level_link' . $suffix ],
					'exclude'   => array(
						'image',
						'position',
						'attachment',
						'attachment_alert',
						'repeat',
						'size',
					),
					'condition' => array(
						'top_first_item_custom_styles' . $suffix => 'yes',
					),
				)
			);

			$this->add_responsive_control(
				'top_first_item_border_radius' . $suffix,
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'first_top_level_link' . $suffix ] => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-left-radius: {{BOTTOM}}{{UNIT}}; border-bottom-right-radius: {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'top_first_item_custom_styles' . $suffix => 'yes',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'           => 'top_first_item_border' . $suffix,
					'selector'       => '{{WRAPPER}} ' . $css_scheme[ 'first_top_level_link' . $suffix ],
					'fields_options' => array(
						'border' => array(
							'label' => _x( 'First Item Border Type', 'Border Control', 'jet-menu' ),
						),
					),
					'condition'      => array(
						'top_first_item_custom_styles' . $suffix => 'yes',
					),
				)
			);

			$this->add_control(
				'top_last_item_custom_styles' . $suffix,
				array(
					'label'        => esc_html__( 'Last Item Custom Styles', 'jet-menu' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'jet-menu' ),
					'label_off'    => esc_html__( 'No', 'jet-menu' ),
					'return_value' => 'yes',
					'default'      => 'false',
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'      => 'top_last_item_background' . $suffix,
					'selector'  => '{{WRAPPER}} ' . $css_scheme[ 'last_top_level_link' . $suffix ] . ', {{WRAPPER}} ' . $css_scheme[ 'last_top_level_link_2' . $suffix ] . ', {{WRAPPER}} ' . $css_scheme[ 'last_top_level_link_3' . $suffix ],
					'exclude'   => array(
						'image',
						'position',
						'attachment',
						'attachment_alert',
						'repeat',
						'size',
					),
					'condition' => array(
						'top_last_item_custom_styles' . $suffix => 'yes',
					),
				)
			);

			$this->add_responsive_control(
				'top_last_item_border_radius' . $suffix,
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'last_top_level_link' . $suffix ]   => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-left-radius: {{BOTTOM}}{{UNIT}}; border-bottom-right-radius: {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} ' . $css_scheme[ 'last_top_level_link_2' . $suffix ] => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-left-radius: {{BOTTOM}}{{UNIT}}; border-bottom-right-radius: {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} ' . $css_scheme[ 'last_top_level_link_3' . $suffix ] => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-left-radius: {{BOTTOM}}{{UNIT}}; border-bottom-right-radius: {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'top_last_item_custom_styles' . $suffix => 'yes',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'label'=> esc_html__( 'Last item border', 'jet-menu' ),
					'name'     => 'top_last_item_border' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'last_top_level_link' . $suffix ] . ', {{WRAPPER}} ' . $css_scheme[ 'last_top_level_link_2' . $suffix ] . ', {{WRAPPER}} ' . $css_scheme[ 'last_top_level_link_3' . $suffix ],
					'fields_options' => array(
						'border' => array(
							'label' => _x( 'Last Item Border Type', 'Border Control', 'jet-menu' ),
						),
					),
					'condition'      => array(
						'top_last_item_custom_styles' . $suffix => 'yes',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * `Sub Menu` Style Section
		 */
		$this->start_controls_section(
			'section_sub_menu_style',
			array(
				'label'      => esc_html__( 'Desktop Sub Menu', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_sub_panel_style' );

		$sub_panel_tabs = array(
			'simple' => esc_html__( 'Simple Panel', 'jet-menu' ),
			'mega'   => esc_html__( 'Mega Panel', 'jet-menu' ),
		);

		foreach ( $sub_panel_tabs as $tab => $label ) {
			$prefix = $tab . '_';

			$this->start_controls_tab(
				'tab_sub_panel_' . $tab,
				array(
					'label' => $label,
				)
			);

			if ( 'simple' === $tab ) {
				$this->add_control(
					'simple_sub_panel_width',
					array(
						'label'       => esc_html__( 'Width (px)', 'jet-menu' ),
						'type'        => Controls_Manager::SLIDER,
						'range'       => array(
							'px' => array(
								'min' => 100,
								'max' => 400,
							),
						),
						'selectors'   => array(
							'{{WRAPPER}} ' . $css_scheme['simple_sub_panel'] => 'min-width: {{SIZE}}{{UNIT}};',
						),
					)
				);
			}

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => $prefix . 'sub_panel_background',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ],
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => $prefix . 'sub_panel_border',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ],
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => $prefix . 'sub_panel_box_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ],
				)
			);

			$this->add_responsive_control(
				$prefix . 'sub_panel_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				$prefix . 'sub_panel_padding',
				array(
					'label'      => esc_html__( 'Padding', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				$prefix . 'sub_panel_margin',
				array(
					'label'      => esc_html__( 'Margin', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'sub_panel' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_control(
			'sub_menu_items_heading',
			array(
				'label'     => esc_html__( 'Sub Menu Items Style', 'jet-menu' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_menu_item_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_level_link'],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Description typography', 'jet-menu' ),
				'name'     => 'sub_menu_desc_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['sub_level_desc'],
			)
		);

		$this->start_controls_tabs( 'tabs_sub_menu_items_style' );

		foreach( $state_tabs as $tab => $label ) {

			$suffix = ( 'normal' !== $tab ) ? '_' . $tab : '';

			$this->start_controls_tab(
				'tab_sub_items_' . $tab,
				array(
					'label' => $label,
				)
			);

			$this->add_control(
				'sub_item_text_color' . $suffix,
				array(
					'label'=> esc_html__( 'Text color', 'jet-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'sub_item_desc_color' . $suffix,
				array(
					'label'=> esc_html__( 'Description color', 'jet-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_desc' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'sub_item_icon_color' . $suffix,
				array(
					'label'=> esc_html__( 'Icon color', 'jet-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_icon' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'sub_item_drop_down_arrow_color' . $suffix,
				array(
					'label'=> esc_html__( 'Drop-down arrow color', 'jet-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_arrow' . $suffix ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'sub_item_background' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ],
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'sub_item_border' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ],
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'           => 'sub_first_item_border' . $suffix,
					'selector'       => '{{WRAPPER}} ' . $css_scheme[ 'first_sub_level_link' . $suffix ],
					'fields_options' => array(
						'border' => array(
							'label' => _x( 'First Item Border Type', 'Border Control', 'jet-menu' ),
						),
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'label'=> esc_html__( 'Last item border', 'jet-menu' ),
					'name'     => 'sub_last_item_border' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'last_sub_level_link' . $suffix ],
					'fields_options' => array(
						'border' => array(
							'label' => _x( 'Last Item Border Type', 'Border Control', 'jet-menu' ),
						),
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'sub_item_box_shadow' . $suffix,
					'selector' => '{{WRAPPER}} ' . $css_scheme[ 'sub_level_link'. $suffix ],
				)
			);

			$this->add_responsive_control(
				'sub_item_border_radius' . $suffix,
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'sub_item_padding' . $suffix,
				array(
					'label'      => esc_html__( 'Padding', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'sub_item_margin' . $suffix,
				array(
					'label'      => esc_html__( 'Margin', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ 'sub_level_link' . $suffix ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();

		$level_tabs = array(
			'top_level' => esc_html__( 'Top Level', 'jet-menu' ),
			'sub_level' => esc_html__( 'Sub Level', 'jet-menu' ),
		);

		/**
		 * `Icon` Style Section
		 */
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'      => esc_html__( 'Desktop Item Icon', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		foreach( $level_tabs as $tab => $label ) {
			$prefix = $tab . '_';

			$this->start_controls_tab(
				'tab_' . $tab . '_icon_style',
				array(
					'label' => $label,
				)
			);

			$this->add_control(
				$prefix . 'icon_size',
				array(
					'label' => esc_html__( 'Icon size', 'jet-menu' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 150,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'icon' ]          => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'icon' ] . ' svg' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'icon_margin',
				array(
					'label'      => esc_html__( 'Margin', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'icon' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'icon_hor_position',
				array(
					'label'   => esc_html__( 'Horizontal position', 'jet-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'title' => esc_html__( 'Left', 'jet-menu' ),
							'icon'  => 'fa fa-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'jet-menu' ),
							'icon'  => 'fa fa-align-center',
						),
						'right' => array(
							'title' => esc_html__( 'Right', 'jet-menu' ),
							'icon'  => 'fa fa-align-right',
						),
					),
					'selectors_dictionary' => array(
						'left'   => 'text-align: left; order: -1;',
						'center' => 'text-align: center; order: 0;',
						'right'  => 'text-align: right; order: 2;',
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'icon' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'icon_ver_position',
				array(
					'label'   => esc_html__( 'Vertical position', 'jet-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'top' => array(
							'title' => esc_html__( 'Top', 'jet-menu' ),
							'icon'  => 'eicon-v-align-top',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'jet-menu' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'bottom' => array(
							'title' => esc_html__( 'Bottom', 'jet-menu' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),
					'selectors_dictionary' => array(
						'top'    => 'flex: 0 0 100%; width: 0; order: -2;',
						'center' => 'align-self: center; flex: 0 0 auto; width: auto;',
						'bottom' => 'flex: 0 0 100%; width: 0; order: 2;',
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'icon' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'icon_order',
				array(
					'label' => esc_html__( 'Order', 'jet-menu' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => -10,
							'max' => 10,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'icon' ]  => 'order: {{SIZE}};',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * `Badge` Style Section
		 */
		$this->start_controls_section(
			'section_badge_style',
			array(
				'label'      => esc_html__( 'Desktop Item Badge', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_badge_style' );

		foreach( $level_tabs as $tab => $label ) {
			$prefix = $tab . '_';

			$this->start_controls_tab(
				'tab_' . $tab . '_badge_style',
				array(
					'label' => $label,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => $prefix . 'badge_typography',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ],
				)
			);

			$this->add_control(
				$prefix . 'badge_color',
				array(
					'label'=> esc_html__( 'Text color', 'jet-menu' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ] => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => $prefix . 'badge_background',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ],
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => $prefix . 'badge_border',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ],
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => $prefix .     'badge_box_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ],
				)
			);

			$this->add_control(
				$prefix . 'badge_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_padding',
				array(
					'label'      => esc_html__( 'Padding', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_margin',
				array(
					'label'      => esc_html__( 'Margin', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge_wrapper' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_hor_position',
				array(
					'label'   => esc_html__( 'Horizontal position', 'jet-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'title' => esc_html__( 'Left', 'jet-menu' ),
							'icon'  => 'fa fa-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'jet-menu' ),
							'icon'  => 'fa fa-align-center',
						),
						'right' => array(
							'title' => esc_html__( 'Right', 'jet-menu' ),
							'icon'  => 'fa fa-align-right',
						),
					),
					'selectors_dictionary' => array(
						'left'   => 'text-align: left; order: -1;',
						'center' => 'text-align: center; order: 0;',
						'right'  => 'text-align: right; order: 2;',
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge_wrapper' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_ver_position',
				array(
					'label'   => esc_html__( 'Vertical position', 'jet-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'top' => array(
							'title' => esc_html__( 'Top', 'jet-menu' ),
							'icon'  => 'eicon-v-align-top',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'jet-menu' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'bottom' => array(
							'title' => esc_html__( 'Bottom', 'jet-menu' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),
					'selectors_dictionary' => array(
						'top'    => 'flex: 1 1 100%; width: 0; order: -2;',
						'center' => 'align-self: center; flex: 0 0 auto; width: auto;',
						'bottom' => 'flex: 1 1 100%; width: 0; order: 2;',
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge_wrapper' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_order',
				array(
					'label' => esc_html__( 'Order', 'jet-menu' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => -10,
							'max' => 10,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'badge_wrapper' ]  => 'order: {{SIZE}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'badge_hide_on_mobile',
				array(
					'label'     => esc_html__( 'Hide on mobile', 'jet-menu' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'jet-menu' ),
					'label_off' => esc_html__( 'No', 'jet-menu' ),
					'default'   => '',
					'selectors' => array(
						'.jet-mobile-menu-active {{WRAPPER}} ' . $css_scheme[ $prefix . 'badge' ] => 'display: none;',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * `Drop-down Arrow` Style Section
		 */
		$this->start_controls_section(
			'section_arrow_style',
			array(
				'label'      => esc_html__( 'Desktop Item Dropdown', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_arrow_style' );

		foreach( $level_tabs as $tab => $label ) {
			$prefix = $tab . '_';

			$this->start_controls_tab(
				'tab_' . $tab . '_arrow_style',
				array(
					'label' => $label,
				)
			);

			$this->add_control(
				$prefix . 'arrow_size',
				array(
					'label' => esc_html__( 'Font size', 'jet-menu' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 10,
							'max' => 150,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'arrow' ]  => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'arrow' ] . ' svg'  => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				$prefix . 'arrow_margin',
				array(
					'label'      => esc_html__( 'Margin', 'jet-menu' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'arrow' ] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
					),
				)
			);

			$arrow_hor_pos_selectors_dictionary = array(
				'left'   => 'text-align: left; order: -1;',
				'center' => 'text-align: center; order: 0;',
				'right'  => 'text-align: right; order: 2;',
			);

			if ( 'sub_level' === $tab ) {
				$arrow_hor_pos_selectors_dictionary['right'] = $arrow_hor_pos_selectors_dictionary['right'] . 'margin-left: auto!important;';
			}

			$this->add_control(
				$prefix . 'arrow_hor_position',
				array(
					'label'   => esc_html__( 'Horizontal position', 'jet-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'left' => array(
							'title' => esc_html__( 'Left', 'jet-menu' ),
							'icon'  => 'fa fa-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'jet-menu' ),
							'icon'  => 'fa fa-align-center',
						),
						'right' => array(
							'title' => esc_html__( 'Right', 'jet-menu' ),
							'icon'  => 'fa fa-align-right',
						),
					),
					'selectors_dictionary' => $arrow_hor_pos_selectors_dictionary,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'arrow' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'arrow_ver_position',
				array(
					'label'   => esc_html__( 'Vertical position', 'jet-menu' ),
					'type'    => Controls_Manager::CHOOSE,
					'options' => array(
						'top' => array(
							'title' => esc_html__( 'Top', 'jet-menu' ),
							'icon'  => 'eicon-v-align-top',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'jet-menu' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'bottom' => array(
							'title' => esc_html__( 'Bottom', 'jet-menu' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),
					'selectors_dictionary' => array(
						'top'    => 'flex: 0 0 100%; width: 0; order: -2;',
						'center' => 'align-self: center; flex: 0 0 auto; width: auto;',
						'bottom' => 'flex: 0 0 100%; width: 0; order: 2;',
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'arrow' ] => '{{VALUE}}',
					),
				)
			);

			$this->add_control(
				$prefix . 'arrow_order',
				array(
					'label' => esc_html__( 'Order', 'jet-menu' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => -10,
							'max' => 10,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme[ $prefix . 'arrow' ]  => 'order: {{SIZE}};',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Toggle Style Section
		 */
		$this->start_controls_section(
			'section_mobile_menu_toggle_style',
			array(
				'label'      => esc_html__( 'Mobile Toggle', 'jet-menu' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'mobile_toggle_color',
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
				'name'     => 'mobile_toggle_bg_color',
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
				'condition' => array(
					'toggle_text!' => '',
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
				'label'      => esc_html__( 'Mobile Container', 'jet-menu' ),
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
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__controls' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'navi_controls_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'selector'    => '{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__controls',
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
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__back i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__back svg' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__back i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'container_back_text_color',
			array(
				'label'     => esc_html__( 'Back Text Color', 'jet-menu' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__back span' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__back span',
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
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'mobile_container_bg_color',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__container-inner',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'container_border',
				'label'       => esc_html__( 'Border', 'jet-menu' ),
				'selector'    => '{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__container-inner',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['mobile_container'],
			)
		);

		$this->add_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'jet-menu' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__container-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__container-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] => 'z-index: {{VALUE}}',
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
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__header-template' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__before-template' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} ' . $css_scheme['mobile_container'] . ' .jet-mobile-menu__after-template' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label'      => esc_html__( 'Mobile Items', 'jet-menu' ),
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
					'{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-icon' => 'font-size: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
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
				'label'   => esc_html__( 'Vertical Aligment', 'jet-menu' ),
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
				'label'     => esc_html__( 'Label Typography', 'jet-menu' ),
				'name'     => 'item_label_typography',
				'selector' => '{{WRAPPER}} ' . $css_scheme['item'] . ' .jet-menu-label',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'     => esc_html__( 'Sub Label Typography', 'jet-menu' ),
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
				'label'      => esc_html__( 'Mobile Advanced', 'jet-menu' ),
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
			'mobile_cover_bg_color',
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

		$args = array(
			'menu' => $settings['menu'],
		);

		$preset = isset( $settings['preset'] ) ? absint( $settings['preset'] ) : 0;

		if ( 0 !== $preset ) {
			$preset_options = get_post_meta( $preset, jet_menu()->settings_manager->options_manager->settings_key, true );
			jet_menu()->settings_manager->options_manager->pre_set_options( $preset_options );
		} else {
			jet_menu()->settings_manager->options_manager->pre_set_options( false );
		}

		jet_menu()->render_manager->location_manager->add_menu_advanced_styles( $menu );
		jet_menu()->render_manager->location_manager->add_dynamic_styles( $preset );

		$force_device_mode = \Elementor\Plugin::$instance->editor->is_edit_mode() ? $settings['force-editor-device'] : false;

		if ( ! $force_device_mode ) {
			switch ( $settings['device-view'] ) {
				case 'both':
					if ( ! \Jet_Menu_Tools::is_phone() ) {
						$args = array_merge( $args, jet_menu()->render_manager->location_manager->get_mega_nav_args( $preset ) );
						wp_nav_menu( $args );

					} else {
						$this->render_mobile_html( $menu );
					}
				break;

				case 'desktop':
					$args = array_merge( $args, jet_menu()->render_manager->location_manager->get_mega_nav_args( $preset ) );
					wp_nav_menu( $args );

				break;

				case 'mobile':
					$this->render_mobile_html( $menu );
					break;
			}
		} else {
			switch ( $force_device_mode ) {
				case 'desktop':
					$args = array_merge( $args, jet_menu()->render_manager->location_manager->get_mega_nav_args( $preset ) );
					wp_nav_menu( $args );

				break;

				case 'mobile':
					$this->render_mobile_html( $menu );
					break;
			}
		}

		if ( $this->is_css_required() ) {
			$dynamic_css = jet_menu()->dynamic_css_manager;

			add_filter( 'cx_dynamic_css/collector/localize_object', array( $this, 'fix_preview_css' ) );
			$dynamic_css->collector->print_style();
			remove_filter( 'cx_dynamic_css/collector/localize_object', array( $this, 'fix_preview_css' ) );
		}

	}

	/**
	 * [render_mobile_html description]
	 * @param  [type] $menu [description]
	 * @return [type]       [description]
	 */
	public function render_mobile_html( $menu ) {
		$settings = $this->get_settings();

		$menu_uniqid = uniqid();
		$mobile_menu_id = isset( $settings['mobile_menu'] ) && \Jet_Menu_Tools::is_phone() ? $settings['mobile_menu'] : false;

		$render_widget_instance = new \Jet_Menu\Render\Mobile_Menu_Render( array(
			'menu-id'                   => $settings[ 'menu' ],
			'mobile-menu-id'            => $mobile_menu_id,
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

		//ob_start();
		$render_widget_instance->render();
	//	echo ob_get_clean();
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

		$allowed_actions = array( 'elementor_render_widget', 'elementor' );

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
