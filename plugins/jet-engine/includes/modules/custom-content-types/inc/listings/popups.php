<?php
namespace Jet_Engine\Modules\Custom_Content_Types\Listings;

use Jet_Engine\Modules\Custom_Content_Types\Module;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Popups {

	private $_js_inited = false;

	public function __construct() {

		add_filter( 'jet-engine/compatibility/popup-package/request-data', array( $this, 'add_content_type_data_to_request' ) );
		add_filter( 'jet-engine/compatibility/popup-package/custom-content', array( $this, 'set_custom_content' ), 10, 2 );

	}

	public function set_custom_content( $content, $popup_data ) {

		if ( empty( $popup_data['cctSlug'] ) || empty( $popup_data['postId'] ) ) {
			return $content;
		}

		$type_slug = $popup_data['cctSlug'];
		$item_id = $popup_data['postId'];
		$popup_id = $popup_data['popup_id'];
		$type = Module::instance()->manager->get_content_types( $type_slug );
		$flag = \OBJECT;

		$type->db->set_format_flag( $flag );

		$item = $type->db->get_item( $item_id );

		jet_engine()->listings->data->set_current_object( $item, true );

		$type->db->set_queried_item_id( $item_id );
		$type->db->set_queried_item( $item );

		$content = \Elementor\Plugin::instance()->frontend->get_builder_content( $popup_id );
		$content = apply_filters( 'jet-engine/compatibility/popup-package/the_content', $content, $popup_data );

		return $content;

	}

	public function add_content_type_data_to_request( $data ) {
		
		$object = jet_engine()->listings->data->get_current_object();

		if ( ! $object || ! isset( $object->cct_slug ) ) {
			return $data;
		}

		$data['cct_slug'] = $object->cct_slug;

		if ( ! $this->_js_inited ) {
			if ( wp_doing_ajax() ) {
				add_filter( 'jet-engine/ajax/get_listing/response', array( $this, 'init_js' ) );
			} else {
				add_action( 'wp_footer', array( $this, 'init_js' ) );
			}
			
			$this->_js_inited = true;
		}

		return $data;

	}

	public function init_js( $response ) {

		$data = '';

		if ( ! wp_doing_ajax() ) {
			$data .= "jQuery( window ).on( 'elementor/frontend/init', function() {\r\n";
		}
		
		$data .= "window.elementorFrontend.hooks.addFilter( 'jet-popup/widget-extensions/popup-data', function( popupData, widgetData, \$scope ) {\r\n";
		$data .= "if ( widgetData['cct_slug'] ) {\r\n";
		$data .= "popupData['cctSlug'] = widgetData['cct_slug'];\r\n";
		$data .= "}\r\n";
		$data .= "return popupData;\r\n";
		$data .= "} );\r\n";

		if ( ! wp_doing_ajax() ) {
		 $data .= "} );\r\n";
		}

		if ( wp_doing_ajax() ) {
			$response['html'] = $response['html'] . sprintf( '<script>%s</script>', $data );
			return $response;
		} else {
			wp_add_inline_script( 'jet-engine-frontend', $data, 'before' );
		}
		
	}
}
