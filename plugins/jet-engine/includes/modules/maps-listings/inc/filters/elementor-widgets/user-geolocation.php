<?php
namespace Jet_Engine\Modules\Maps_Listings\Filters\Elementor_Widgets;

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class User_Geolocation extends \Elementor\Jet_Smart_Filters_Base_Widget {

	public function get_name() {
		return 'jet-smart-filters-user-geolocation';
	}

	public function get_title() {
		return __( 'User Geolocation', 'jet-engine' );
	}

	public function get_icon() {
		return 'jet-engine-icon-user-geolocation';
	}

	public function get_help_url() {}

	protected function register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'Content', 'jet-smart-filters' ),
			)
		);

		$this->add_control(
			'filter_id',
			$this->get_filter_control_settings()
		);

		$this->add_control(
			'content_provider',
			array(
				'label'   => __( 'This filter for', 'jet-smart-filters' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => jet_smart_filters()->data->content_providers(),
			)
		);

		$this->add_control(
			'apply_type',
			array(
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'ajax',
			)
		);
		
		$this->add_control(
			'apply_on',
			array(
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'value',
			)
		);

		$this->add_control(
			'epro_posts_notice',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => __( 'Please set <b>jet-smart-filters</b> into Query ID option of Posts widget you want to filter', 'jet-smart-filters' ),
				'condition' => array(
					'content_provider' => array( 'epro-posts', 'epro-portfolio' ),
				),
			)
		);

		$this->add_control(
			'query_id',
			array(
				'label'       => esc_html__( 'Query ID', 'jet-smart-filters' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Set unique query ID if you use multiple widgets of same provider on the page. Same ID you need to set for filtered widget.', 'jet-smart-filters' ),
			)
		);

		// Include Additional Providers Settings
		include jet_smart_filters()->plugin_path( 'includes/widgets/common-controls/additional-providers.php' );

		$this->end_controls_section();

		$this->register_filter_settings_controls();

	}

}
