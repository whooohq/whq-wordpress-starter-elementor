<?php

namespace Jet_Smart_Filters\Bricks_Views\Filters;

define( 'BRICKS_QUERY_LOOP_PROVIDER_ID', 'bricks-query-loop' );
define( 'BRICKS_QUERY_LOOP_PROVIDER_NAME', 'Bricks query loop' );

class Manager {

	public function __construct() {
		/**
		 * Register custom provider
		 */

		add_action( 'init', [ $this, 'add_control_to_elements' ], 40 );
		add_action( 'jet-smart-filters/providers/register', [ $this, 'register_provider_for_filters' ] );
		add_filter( 'jet-smart-filters/filters/localized-data', [ $this, 'add_script' ] );
		add_filter( 'jet-engine/query-builder/filters/allowed-providers', [ $this, 'add_provider_to_query_builder' ] );

	}

	public function register_provider_for_filters( $providers_manager ) {

		$providers_manager->register_provider(
			'\Jet_Smart_Filters\Bricks_Views\Filters\Provider', // Custom provider class name
			jet_smart_filters()->plugin_path( 'includes/bricks/filters/provider.php' ) // Path to file where this class defined
		);
	}

	public function add_control_to_elements() {

		// Only container, block and div element have query controls
		$elements = [ 'container', 'block', 'div' ];

		foreach ( $elements as $name ) {
			add_filter( "bricks/elements/{$name}/controls", [ $this, 'add_jet_smart_filters_controls' ], 40 );
		}

	}

	public function add_jet_smart_filters_controls( $controls ) {

		$jet_smart_filters_control['jsfb_is_filterable'] = [
			'tab'         => 'content',
			'label'       => esc_html__( 'Is filterable', 'jet-smart-filters' ),
			'type'        => 'checkbox',
			'required'    => [
				[ 'hasLoop', '=', true ],
			],
			'rerender'    => true,
			'description' => esc_html__( 'Please check this option if you will use with JetSmartFilters.', 'jet-smart-filters' ),
		];

		$jet_smart_filters_control['jsfb_query_id'] = [
			'tab'            => 'content',
			'label'          => esc_html__( 'Query ID for filters', 'jet-smart-filters' ),
			'type'           => 'text',
			'placeholder'    => esc_html__( 'Please enter query id.', 'jet-smart-filters' ),
			'hasDynamicData' => false,
			'required'       => [
				[ 'hasLoop', '=', true ],
				[ 'jsfb_is_filterable', '=', true ]
			],
			'rerender'       => true,
		];

		// Below 2 lines is just some php array functions to force my new control located after the query control
		$query_key_index = absint( array_search( 'query', array_keys( $controls ) ) );
		$new_controls    = array_slice( $controls, 0, $query_key_index + 1, true ) + $jet_smart_filters_control + array_slice( $controls, $query_key_index + 1, null, true );

		return $new_controls;
	}

	public function add_provider_to_query_builder( $providers ) {
		$providers[] = BRICKS_QUERY_LOOP_PROVIDER_ID;

		return $providers;
	}

	public function add_script( $data ) {

		wp_add_inline_script( 'jet-smart-filters', '

				const filtersStack = {};

				document.addEventListener( "jet-smart-filters/inited", () => {
					window.JetSmartFilters.events.subscribe( "ajaxFilters/start-loading", ( provider, queryID ) => {
						if ( "bricks-query-loop" === provider && filtersStack[ queryID ] ) {
							delete filtersStack[ queryID ];
						}
					} );
				} );

				jQuery( document ).on( "jet-filter-data-updated", ".jsfb-filterable", ( event, response, filter ) => {

					if ( event.target.classList.contains( "jsfb-query--" + response.query_id ) ) {
						if ( ! filtersStack[ response.query_id ] ) {
							
							filtersStack[ response.query_id ] = true;
							
							var newContent = response.rendered_content;
							var replaced = false;

							if ( ! response.loadMore ) {
								jQuery( ".jsfb-filterable.jsfb-query--" + response.query_id ).replaceWith( () => {

									if ( replaced ) {
										newContent = "";
									} else {
										replaced = true;
									}

									return newContent;

								} );
							}

							filter.$provider.last().after( newContent );
							filter.$provider = jQuery( ".jsfb-filterable.jsfb-query--" + response.query_id );
							window.JetPlugins && window.JetPlugins.init( filter.$provider.closest( "*" ) );

						}
					}
				} );

			' );

		return $data;

	}
}