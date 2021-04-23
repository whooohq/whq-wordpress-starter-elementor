<?php

namespace Essential_Addons_Elementor\Pro\Classes\WPML\Widgets;

use WPML_Elementor_Module_With_Items;
use Essential_Addons_Elementor\Classes\WPML\Eael_WPML;

if ( ! defined('ABSPATH') ) exit; // No access of directly access

/**
 * Carousel
 *
 * Registers translatable widget with items.
 *
 * @since 3.2.4
 */
class Team_Member_Carousel extends WPML_Elementor_Module_With_Items {

	/**
	 * Get widget field name.
	 * 
	 * @return string
	 */
	public function get_items_field() {
		return 'team_member_details';
	}

	/**
	 * Get the fields inside the repeater.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'team_member_name',
            'team_member_position',
            'team_member_description'
		);
	}

  	/**
     * @param string $field
	 * 
	 * Get the field title string
     *
     * @return string
     */
	protected function get_title( $field ) {
		switch($field) {
			case 'team_member_name':
				return __( 'Team Member Carousel: Name', 'essential-addons-elementor' );

			case 'team_member_position':
				return __( 'Team Member Carousel: Position', 'essential-addons-elementor' );

			case 'team_member_description':
				return __( 'Team Member Carousel: Description', 'essential-addons-elementor' );

			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 * 
	 * Get perspective field types.
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch($field) {
			case 'team_member_name':
				return 'LINE';

			case 'team_member_position':
				return 'LINE';

			case 'team_member_description':
				return 'AREA';

			default:
				return '';
		}
	}

}
