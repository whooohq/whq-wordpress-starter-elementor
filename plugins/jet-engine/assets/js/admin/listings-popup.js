(function( $ ) {

	'use strict';

	var JetListings = {

		editorControl: null,

		onEditorCreateClick: function( control ) {
			this.openPopup();
			this.editorControl = control;
		},

		init: function() {

			var self      = this,
				$document = $( document );

			$document
				.on( 'click.JetListings', '.page-title-action', self.openPopup )
				.on( 'click.JetListings', '.jet-listings-popup__overlay, .jet-listings-popup__close', self.closePopup );

			if ( window.JetListingsSettings.isAjax ) {
				$document.on( 'submit.JetListings', '#templates_type_form', self.ajaxSubmit );
			}

			$( 'body' ).on( 'change', '#listing_source', self.switchListingSources );

			self.applyCustomOptions();

			if ( '#add_new' === window.location.hash ) {
				self.openPopup();
			}

		},

		applyCustomOptions: function() {
			
			var $popup = $( '.jet-listings-popup' );

			if ( window.JetListingsSettings.exclude ) {
				for ( var i = 0; i < window.JetListingsSettings.exclude.length; i++ ) {
					$popup.find( '*[name="' + window.JetListingsSettings.exclude[ i ] + '"]' ).closest( '.jet-listings-popup__form-row' ).hide();
				}
			}

			if ( window.JetListingsSettings.button && window.JetListingsSettings.button.css_class ) {
				$popup.find( '#templates_type_submit' ).addClass( window.JetListingsSettings.button.css_class );
			}

		},

		ajaxSubmit: function( event ) {
			
			event.preventDefault();

			var self = JetListings;

			let formEl = document.getElementById( 'templates_type_form' );
			let formData = new FormData( formEl );

			const values = {};

			for( var data of formData.entries() ) {
				values[ data[0] ] = data[1];
			}

			values['_is_ajax_form'] = true;

			$.ajax( {
				url: formEl.action,
				type: 'POST',
				dataType: 'json',
				data: values,
			} ).done( function( response ) {

				if ( response.success && self.editorControl ) {
					
					let options = self.editorControl.model.get( 'options' );
					let listingID = response.data.id;
					
					options[ listingID ] = response.data.title;
					self.editorControl.model.set( 'options', options );
					self.editorControl.setValue( listingID );
					self.editorControl.render();
					self.closePopup();

					let previewWindow = window.elementor.$preview[0].contentWindow;
										
					previewWindow.elementorCommon.api.internal( 'panel/state-loading' );
					previewWindow.elementorCommon.api.run( 'editor/documents/switch', {
						id: listingID
					} ).then( function() {
						return previewWindow.elementorCommon.api.internal( 'panel/state-ready' );
					} );

				}
				

			} );

		},

		switchListingSources: function( event ) {

			var $this = $( this ),
				val   = $this.find( 'option:selected' ).val(),
				$row  = $this.closest( '.jet-listings-popup__form-row' );

			$row.siblings( '.jet-template-listing' ).removeClass( 'jet-template-act' );
			$row.siblings( '.jet-template-' + val ).addClass( 'jet-template-act' );

		},

		openPopup: function( event ) {

			if ( event ) {
				event.preventDefault();
			}

			$( '.jet-listings-popup' ).addClass( 'jet-listings-popup-active' );
		},

		closePopup: function() {
			$( '.jet-listings-popup' ).removeClass( 'jet-listings-popup-active' );
			window.history.pushState( "", document.title, window.location.pathname + window.location.search );

		}

	};

	JetListings.init();

	window.JetListings = JetListings;

})( jQuery );
