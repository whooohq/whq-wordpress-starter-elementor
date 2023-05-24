<?php
/**
 * Bricks views manager
 */
namespace Jet_Engine\Bricks_Views\Listing;

/**
 * Define render class
 */
class Render {

	private $current_query;

	public function __construct() {
		
		add_filter( 'jet-engine/listing/content/bricks', [ $this, 'get_listing_content_cb' ], 10, 2 );
		add_filter( 'jet-engine/listing/grid/columns', [ $this, 'remap_columns' ], 10, 2 );

		add_action( 'jet-engine/listing/grid/before-render', [ $this, 'set_query_on_render' ] );
		add_action( 'jet-engine/listing/grid/after-render', [ $this, 'destroy_bricks_query' ] );

		add_action( 'jet-smart-filters/render/ajax/before', [ $this, 'set_query_on_filters_ajax' ] );
		add_action( 'jet-engine/ajax-handlers/before-do-ajax', [ $this, 'set_query_on_listing_ajax' ], 10, 2 );

	}

	public function set_bricks_query( $listing_id = 0, $settings = [] ) {

		if ( ! $listing_id ) {
			$listing_id = isset( $settings['lisitng_id'] ) ? absint( $settings['lisitng_id'] ) : 0;
		}

		if ( $listing_id && jet_engine()->bricks_views->is_bricks_listing( $listing_id ) ) {
			$this->current_query = jet_engine()->bricks_views->listing->get_bricks_query( [
				'id'       => 'jet-engine-listing-grid',
				'settings' => $settings,
			] );
		}

	}

	public function set_query_on_filters_ajax() {
		$settings = isset( $_REQUEST['settings'] ) ? $_REQUEST['settings'] : [];
		$this->set_bricks_query( 0, $settings );
	}

	public function set_query_on_listing_ajax( $ajax_handler, $request ) {
		$settings = isset( $request['widget_settings'] ) ? $request['widget_settings'] : [];
		$this->set_bricks_query( 0, $settings );
	}

	public function set_query_on_render( $render ) {
		$this->set_bricks_query( $render->get_settings( 'lisitng_id' ), $render->get_settings() );
	}

	public function destroy_bricks_query() {
		if ( $this->current_query ) {
			$this->current_query->destroy();
		}
	}

	public function remap_columns( $columns, $settings ) {

		if ( ! empty( $settings['columns:tablet_portrait'] ) ) {
			$columns['tablet'] = absint( $settings['columns:tablet_portrait'] );
		}

		if ( ! empty( $settings['columns:mobile_portrait'] ) ) {
			$columns['mobile'] = absint( $settings['columns:mobile_portrait'] );
		}

		if ( ! empty( $settings['columns:mobile_landscape'] ) ) {
			$columns['mobile_landscape'] = absint( $settings['columns:mobile_landscape'] );
		}

		return $columns;
	}

	public function get_listing_content_cb( $result, $listing_id ) {
		
		$bricks_data = get_post_meta( $listing_id, BRICKS_DB_PAGE_CONTENT, true );

		if ( ! $bricks_data ) {
			return;
		}

		ob_start();
		jet_engine()->bricks_views->listing->render_assets( $listing_id );
		$result = ob_get_clean();
		
		// Prepare flat list of elements for recursive calls
		// Default Bricks logic not used in this case because it reset elements list after rendering
		foreach ( $bricks_data as $element ) {
			\Bricks\Frontend::$elements[ $element['id'] ] = $element;
		}

		// Prevent errors when handling non-post queries with WooCommerce is active
		if ( function_exists( 'WC' ) && \Bricks\Theme::instance()->woocommerce ) {
			remove_filter( 
				'bricks/builder/data_post_id',
				[ \Bricks\Theme::instance()->woocommerce, 'maybe_set_post_id' ], 
				10, 1
			);
		}

		if ( is_array( $bricks_data ) && count( $bricks_data ) ) {

			foreach ( $bricks_data as $element ) {
				
				if ( ! empty( $element['parent'] ) ) {
					continue;
				}

				$result .= \Bricks\Frontend::render_element( $element );

			}

		}

		if ( function_exists( 'WC' ) && \Bricks\Theme::instance()->woocommerce ) {
			add_filter(
				'bricks/builder/data_post_id',
				[ \Bricks\Theme::instance()->woocommerce, 'maybe_set_post_id' ],
				10, 1
			);
		}

		// Filter required for the compatibility with default Bricks dynamic data
		return apply_filters( 
			'bricks/dynamic_data/render_content',
			$result,
			jet_engine()->listings->data->get_current_object(),
			null
		);

	}
	
}
