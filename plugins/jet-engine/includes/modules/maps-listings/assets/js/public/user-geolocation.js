( function( $ ) {

	"use strict";

	window.addEventListener( 'DOMContentLoaded', ( e ) => {
	
		window.JetSmartFilters.filtersList.JetEngineUserGeolocation = 'jet-smart-filters-user-geolocation';
		window.JetSmartFilters.filters.JetEngineUserGeolocation = class JetEngineUserGeolocation extends window.JetSmartFilters.filters.Search {

			name = 'user-geolocation';

			constructor( $container ) {
				
				const $filter = $container.find( '.geolocation-data' );
				
				super( $container, $filter );

				if ( navigator.geolocation ) {
					
					navigator.geolocation.getCurrentPosition( ( position ) => {
						
						this.dataValue = {
							latitude: position.coords.latitude,
							longitude: position.coords.longitude,
						}

						this.emitFitersApply();

					} );
				}

			}

			processData() {
			}

		};

	});

}( jQuery ) );