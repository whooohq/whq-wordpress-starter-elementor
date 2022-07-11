const JetMapboxPopup = function( data ) {
	
	this.popup = data.popup;
	this.popupContent = null;
	this.map = data.map || null;

	this.contentIsSet = function() {
		return null !== this.popupContent;
	}

	this.close = function() {
		// runs automatically
		return;
	}

	this.setMap = function( map ) {
		this.map = map;
	}

	this.draw = function() {
		// runs automatically
		return;
	}

	this.open = function( map, marker ) {
		// runs automatically
		return;
	}

	this.setContent = function( content ) {
		this.popupContent = content;
		this.popup.setHTML( content );
	}

	return this;

};

window.JetEngineMapsProvider = function() {

	this.initMap = function( container, settings ) {

		settings = settings || {};

		let settingsMap = {
			zoom: 'zoom',
			center: 'center',
			scrollwheel: 'scrollWheelZoom',
			zoomControl: 'zoomControl',
			style: 'styles',
			maxZoom: 'maxZoom',
		};
		
		let parsedSettings = {}

		for ( const [ mKey, settingsKey ] of Object.entries( settingsMap ) ) {
			if ( undefined !== settings[ settingsKey ] ) {
				parsedSettings[ mKey ] = settings[ settingsKey ];
			}
		}

		if ( parsedSettings.center ) {
			parsedSettings.center = { lon: parsedSettings.center.lng, lat: parsedSettings.center.lat };
		}

		if ( container.id ) {
			parsedSettings.container = container.id;
		} else {
			parsedSettings.container = container;
		}
		

		if ( ! parsedSettings.style ) {
			parsedSettings.style = 'mapbox://styles/mapbox/streets-v11';
		}

		mapboxgl.accessToken = window.JetEngineMapboxData.token;

		const map = new mapboxgl.Map( parsedSettings );

		return map;
	}

	this.initBounds = function() {
		const bounds = new mapboxgl.LngLatBounds();
		return bounds;
	}

	this.getMarkerPosition = function( marker, toJSON ) {
		return marker.getLngLat();
	}

	this.fitMapBounds = function( data ) {
		data.map.fitBounds( data.bounds, {
			padding: { top: 20, bottom: 20, left: 20, right: 20 },
			duration: 0
		} );
	}

	this.addMarker = function( data ) {

		const el = document.createElement('div');
		
		el.className   = 'jet-map-marker';
		el.offsetWidth = 32;
		el.innerHTML   = data.content;

		var marker = new mapboxgl.Marker( el ).setLngLat( [ data.position.lng, data.position.lat ] );

		if ( ! data.markerClustering ) {
			marker.addTo( data.map );
		}
		
		return marker;
	}

	this.removeMarker = function( marker ) {
		marker.remove();
	}

	this.markerOnClick = function( map, data, callback ) {

		data = data || {};

		data.map    = map;
		data.shadow = false;

		map.on( "click", ( event ) => {

			data.position = {
				lat: event.lngLat.lat,
				lng: event.lngLat.lng,
			};

			if ( callback ) {
				callback( this.addMarker( data ) );
			}

		} );

	}

	this.closePopup = function( infoBox, callback ) {
		callback();
	}

	this.openPopup = function( trigger, callback, infobox, map ) {

		infobox.popup.on( 'open', () => {
			callback();
		} );

		trigger.setPopup( infobox.popup );

	}

	this.getMarkerCluster = function( data ) {
		return new JetMapboxMarkerClusterer( data );
	}

	this.addMarkers = function( markerCluster, markers ) {
		markerCluster.setMarkers( markers );
		markerCluster.setMapData();
	}

	this.removeMarkers = function( markerCluster, markers ) {
		markerCluster.removeMarkers();
	}

	this.setAutoCenter = function( data ) {
		this.fitMapBounds( data );
	}

	this.addPopup = function( data ) {

		const popup = new mapboxgl.Popup( {
			maxWidth: data.width,
			minWidth: data.width,
			offset: data.offset,
			focusAfterOpen: true,
			className: 'jet-map-box',
		} );

		return new JetMapboxPopup( {
			popup: popup
		} );

	}

}
