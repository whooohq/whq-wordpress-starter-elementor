const JetLeafletPopup = function( data ) {
	
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
		this.popup.setContent( content );
	}

	return this;

};

window.JetEngineMapsProvider = function() {

	this.initMap = function( container, settings ) {

		settings = settings || {};

		let settingsMap = {
			zoom: 'zoom',
			center: 'center',
			scrollWheelZoom: 'scrollwheel',
			zoomControl: 'zoomControl',
			maxZoom: 'maxZoom',
		};
		
		let parsedSettings = {}

		for ( const [ lKey, settingsKey ] of Object.entries( settingsMap ) ) {
			if ( undefined !== settings[ settingsKey ] ) {
				parsedSettings[ lKey ] = settings[ settingsKey ];
			}
		}

		if ( parsedSettings.center ) {
			parsedSettings.center = L.latLng( parsedSettings.center.lat, parsedSettings.center.lng );
		}

		const map = L.map( container, parsedSettings );

		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}).addTo( map );

		return map;
	}

	this.initBounds = function() {
		const bounds = L.latLngBounds( [] );
		return bounds;
	}

	this.getMarkerPosition = function( marker ) {
		return marker.getLatLng();
	}

	this.fitMapBounds = function( data ) {
		
		let center = null;
		
		try {
			center = data.bounds.getCenter();
		} catch ( e ) {
			console.log( 'Can`t define new map center without markers.' );
		}
		
		if ( center ) {
			data.map.fitBounds( data.bounds );
			return true;
		} else {
			return false;
		}
	}

	this.addMarker = function( data ) {
		
		var myIcon = L.divIcon( { html: data.content, iconSize: [ 32, 32 ] } );
		var marker = L.marker( [ data.position.lat, data.position.lng ], { icon: myIcon } );

		if ( ! data.markerClustering ) {
			marker.addTo( data.map );
		}
		
		return marker;
	}

	this.removeMarker = function( marker ) {
		marker.remove();
	}

	this.closePopup = function( infoBox, callback ) {
		callback();
	}

	this.openPopup = function( trigger, callback, infobox, map ) {

		map.on( 'popupopen', ( e ) => {
			if ( e.popup === infobox.popup ) {
				callback();
			}
		} );

		trigger.bindPopup( infobox.popup );
	}

	this.triggerOpenPopup = function( trigger ) {
		trigger.openPopup();
	}

	this.getMarkerCluster = function( data ) {
		var markersGrpup = L.markerClusterGroup();
		markersGrpup.addLayers( data.markers );
		data.map.addLayer( markersGrpup );
		return markersGrpup;
	}

	this.addMarkers = function( markerCluster, markers ) {
		markerCluster.addLayers( markers );
	}

	this.removeMarkers = function( markerCluster, markers ) {
		markerCluster.removeLayers( markers );
	}

	this.markerOnClick = function( map, data, callback ) {

		data = data || {};

		data.map    = map;
		data.shadow = false;

		map.on( "click", ( event ) => {

			data.position = {
				lat: event.latlng.lat,
				lng: event.latlng.lng,
			};

			if ( callback ) {
				callback( this.addMarker( data ) );
			}

		} );
	}

	this.setCenterByPosition = function( data ) {
		data.map.setView( data.position, data.zoom );
	}

	this.setAutoCenter = function( data ) {
		if ( ! this.fitMapBounds( data ) ) {
			if ( window.JetEngineMapData && window.JetEngineMapData.mapCenter ) {
				data.map.setView( window.JetEngineMapData.mapCenter, 10 );
			} else {
				data.map.fitWorld();
			}
			
		}
	}

	this.addPopup = function( data ) {
		
		const popup = L.popup( {
			maxWidth: data.width,
			minWidth: data.width,
			keepInView: true,
			className: 'jet-map-box',
		} );

		return new JetLeafletPopup( {
			popup: popup
		} );
	}

	this.getMarkerMap = function( marker ) {
		return marker._map;
	}

	this.fitMapToMarker = function( marker, markersClusterer ) {
		markersClusterer.zoomToShowLayer( marker, () => {
			markersClusterer._map.setView( this.getMarkerPosition( marker ) );
		} );
	}

}
