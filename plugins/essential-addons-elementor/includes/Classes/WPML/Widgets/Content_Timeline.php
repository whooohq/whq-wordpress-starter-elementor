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
class Content_Timeline extends WPML_Elementor_Module_With_Items {

	/**
	 * Get widget field name.
	 * 
	 * @return string
	 */
	public function get_items_field() {
		return 'eael_coustom_content_posts';
	}

	/**
	 * Get the fields inside the repeater.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'eael_custom_title',
            'eael_custom_excerpt',
            'eael_custom_post_date',
            'eael_show_custom_read_more_text'
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
			case 'eael_custom_title':
				return __( 'Content Timeline: Title', 'essential-addons-elementor' );

			case 'eael_custom_excerpt':
                return __( 'Content Timeline: Content', 'essential-addons-elementor' );
                
            case 'eael_custom_post_date':
                return __( 'Content Timeline: Post Date', 'essential-addons-elementor' );
                
            case 'eael_show_custom_read_more_text':
				return __( 'Content Timeline: Label Text', 'essential-addons-elementor' );

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
			case 'eael_custom_title':
				return 'LINE';

			case 'eael_custom_excerpt':
                return 'VISUAL';
                
			case 'eael_custom_post_date':
				return 'LINE';
                
			case 'eael_show_custom_read_more_text':
				return 'LINE';

			default:
				return '';
		}
	}

}
