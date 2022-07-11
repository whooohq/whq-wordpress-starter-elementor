<?php
namespace Jet_Engine\Modules\Maps_Listings\Dynamic_Tags;

use Jet_Engine\Modules\Maps_Listings\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Open_Map_Popup extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'jet-open-map-listing-popup';
	}

	public function get_title() {
		return __( 'Open Map Listing Popup', 'jet-engine' );
	}

	public function get_group() {
		return \Jet_Engine_Dynamic_Tags_Module::JET_ACTION_GROUP;
	}

	public function get_categories() {
		return array(
			\Jet_Engine_Dynamic_Tags_Module::URL_CATEGORY,
		);
	}

	public function is_settings_required() {
		return true;
	}

	protected function register_controls() {

		$this->add_control(
			'specific_post_id',
			array(
				'label'       => __( 'Specific post ID (optional)', 'jet-engine' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'event',
			array(
				'label'   => __( 'Trigger', 'jet-engine' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'click',
				'options' => array(
					'click' => __( 'On Click', 'jet-engine' ),
					'hover' => __( 'On Hover', 'jet-engine' ),
				)

			)
		);
	}

	public function render() {
		$specific_post_id = $this->get_settings( 'specific_post_id' );
		$event = $this->get_settings( 'event' );

		echo Module::instance()->get_action_url( $specific_post_id, $event );
	}

}
