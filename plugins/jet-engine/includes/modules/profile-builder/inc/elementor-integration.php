<?php
namespace Jet_Engine\Modules\Profile_Builder;

class Elementor_Integration {

	public $pages = null;

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			return;
		}

		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ), 11 );
		add_action( 'jet-engine/listings/dynamic-link/source-controls', array( $this, 'register_link_controls' ), 10 );
		add_action( 'jet-engine/listings/dynamic-image/link-source-controls', array( $this, 'register_img_link_controls' ), 10 );

		add_action( 'jet-engine/elementor-views/dynamic-tags/register', array( $this, 'register_dynamic_tags' ) );
		add_action( 'jet-engine/profile-builder/template/assets', array( $this, 'enqueue_template_styles' ) );

		add_filter( 'jet-engine/profile-builder/template/content', array( $this, 'render_template_content' ), 0, 2 );


	}

	/**
	 * Check if profile template is Elementor template, render it with Elementor
	 *
	 * @param  [type] $content     [description]
	 * @param  [type] $template_id [description]
	 * @return [type]              [description]
	 */
	public function render_template_content( $content, $template_id ) {

		$elementor_content = \Elementor\Plugin::instance()->frontend->get_builder_content( $template_id );

		if ( $elementor_content ) {
			remove_all_filters( 'jet-engine/profile-builder/template/content' );
			return $elementor_content;
		}

		return $content;
	}

	/**
	 * Enqueue profile template assets
	 *
	 * @param  [type] $template_id [description]
	 * @return [type]              [description]
	 */
	public function enqueue_template_styles( $template_id ) {

		\Elementor\Plugin::instance()->frontend->enqueue_styles();

		$css_file = new \Elementor\Core\Files\CSS\Post( $template_id );
		$css_file->enqueue();

	}

	/**
	 * Register Elementor-related dynamic tags
	 *
	 * @param  [type] $tags_module [description]
	 * @return [type]              [description]
	 */
	public function register_dynamic_tags( $tags_module ) {

		require_once jet_engine()->modules->modules_path( 'profile-builder/inc/dynamic-tags/profile-page-url.php' );

		$tags_module->register_tag( new Dynamic_Tags\Profile_Page_URL() );

	}

	/**
	 * Add account URL into the link options Dynamic Image widget
	 * @param  [type] $widget [description]
	 * @return [type]         [description]
	 */
	public function register_img_link_controls( $widget ) {
		$this->register_link_controls( $widget, true );
	}

	/**
	 * Get all profile pages list to use as options
	 *
	 * @return [type] [description]
	 */
	public function get_pages_for_options() {

		if ( null === $this->pages ) {

			$pages    = array();
			$settings = Module::instance()->settings->get();

			if ( ! empty( $settings['account_page_structure'] ) ) {

				$options = array();

				foreach ( $settings['account_page_structure'] as $page ) {
					$options['account_page::' . $page['slug'] ] = $page['title'];
				}

				$pages[] = array(
					'label'   => __( 'Account Page', 'jet-engine' ),
					'options' => $options,
				);

			}

			if ( ! empty( $settings['enable_single_user_page'] ) && ! empty( $settings['user_page_structure'] ) ) {

				$options = array();

				foreach ( $settings['user_page_structure'] as $page ) {
					$options['single_user_page::' . $page['slug'] ] = $page['title'];
				}

				$pages[] = array(
					'label'   => __( 'Single User Page', 'jet-engine' ),
					'options' => $options,
				);

			}

			$this->pages = $pages;

		}

		return $this->pages;

	}

	/**
	 * Register link control
	 *
	 * @param  [type] $widget [description]
	 * @return [type]         [description]
	 */
	public function register_link_controls( $widget = null, $is_image = false ) {

		$pages = $this->get_pages_for_options();

		$condition = array(
			'dynamic_link_source' => 'profile_page',
		);

		if ( $is_image ) {
			$condition = array(
				'linked_image'      => 'yes',
				'image_link_source' => 'profile_page',
			);
		}

		$widget->add_control(
			'dynamic_link_profile_page',
			array(
				'label'     => __( 'Profile Page', 'jet-engine' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '',
				'groups'    => $pages,
				'condition' => $condition,
			)
		);

	}

	/**
	 * Register profile builder widgets
	 *
	 * @return [type] [description]
	 */
	public function register_widgets( $widgets_manager ) {

		require jet_engine()->modules->modules_path( 'profile-builder/inc/widgets/profile-menu-widget.php' );
		$widgets_manager->register_widget_type( new Profile_Menu_Widget() );

		$template_mode = Module::instance()->settings->get( 'template_mode' );

		if ( 'content' === $template_mode ) {
			require jet_engine()->modules->modules_path( 'profile-builder/inc/widgets/profile-content-widget.php' );
			$widgets_manager->register_widget_type( new Profile_Content_Widget() );
		}

	}

}
