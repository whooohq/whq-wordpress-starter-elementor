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
class Price_Menu extends WPML_Elementor_Module_With_Items {

	/**
	 * Get widget field name.
	 * 
	 * @return string
	 */
	public function get_items_field() {
		return 'menu_items';
	}

	/**
	 * Get the fields inside the repeater.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
            'menu_title',
            'menu_description',
            'menu_price',
            'original_price'
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
			case 'menu_title':
                return __( 'Price Menu: Title', 'essential-addons-elementor' );
                
            case 'menu_description':
                return __( 'Price Menu: Description', 'essential-addons-elementor' );
                
            case 'menu_price':
                return __( 'Price Menu: Price', 'essential-addons-elementor' );
                
            case 'original_price':
                return __( 'Price Menu: Original Price', 'essential-addons-elementor' );

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
			case 'menu_title':
                return 'LINE';
                
            case 'menu_description':
                return 'AREA';
                
            case 'menu_price':
                return 'LINE';
                
            case 'original_price':
				return 'LINE';

			default:
				return '';
		}
	}

}
