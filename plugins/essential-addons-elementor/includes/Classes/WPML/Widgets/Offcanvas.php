<?php

namespace Essential_Addons_Elementor\Pro\Classes\WPML\Widgets;

use WPML_Elementor_Module_With_Items;
use Essential_Addons_Elementor\Pro\Classes\WPML\Eael_WPML;

if ( ! defined('ABSPATH') ) exit; // No access of directly access

/**
 * Carousel
 *
 * Registers translatable widget with items.
 *
 * @since 3.2.4
 */
class Offcanvas extends WPML_Elementor_Module_With_Items {

	/**
	 * Get widget field name.
	 * 
	 * @return string
	 */
	public function get_items_field() {
		return 'custom_content';
	}

	/**
	 * Get the fields inside the repeater.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
            'title',
            'description'
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
			case 'title':
                return __( 'Offcanvas: Title', 'essential-addons-elementor' );
                
            case 'description':
				return __( 'Offcanvas: Description', 'essential-addons-elementor' );

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
			case 'title':
                return 'LINE';
                
            case 'description':
				return 'VISUAL';

			default:
				return '';
		}
	}

}
