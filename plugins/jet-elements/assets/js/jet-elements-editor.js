( function( $ ) {

	'use strict';

	var JetElementsEditor = {

		activeSection: null,

		editedElement: null,

		init: function() {
			elementor.channels.editor.on( 'section:activated', JetElementsEditor.onAnimatedBoxSectionActivated );
			elementor.channels.editor.on( 'section:activated', JetElementsEditor.onCountdownSectionActivated );

			window.elementor.on( 'preview:loaded', function() {
				elementor.$preview[0].contentWindow.JetElementsEditor = JetElementsEditor;

				JetElementsEditor.onPreviewLoaded();
			});

			// Register controls
			elementor.addControlView( 'jet_dynamic_date_time', window.elementor.modules.controls.Date_time );
		},

		onAnimatedBoxSectionActivated: function( sectionName, editor ) {
			var editedElement = editor.getOption( 'editedElementView' ),
				prevEditedElement = window.JetElementsEditor.editedElement;

			if ( prevEditedElement
				&& 'jet-animated-box' === prevEditedElement.model.get( 'widgetType' )
				&& 'jet-animated-box' !== editedElement.model.get( 'widgetType' )
			) {

				prevEditedElement.$el.find( '.jet-animated-box' ).removeClass( 'flipped' );
				prevEditedElement.$el.find( '.jet-animated-box' ).removeClass( 'flipped-stop' );

				window.JetElementsEditor.editedElement = null;
			}

			if ( 'jet-animated-box' !== editedElement.model.get( 'widgetType' ) ) {
				return;
			}

			window.JetElementsEditor.editedElement = editedElement;
			window.JetElementsEditor.activeSection = sectionName;

			var isBackSide = -1 !== [ 'section_back_content', 'section_action_button_style' ].indexOf( sectionName );

			if ( isBackSide ) {
				editedElement.$el.find( '.jet-animated-box' ).addClass( 'flipped' );
				editedElement.$el.find( '.jet-animated-box' ).addClass( 'flipped-stop' );
			} else {
				editedElement.$el.find( '.jet-animated-box' ).removeClass( 'flipped' );
				editedElement.$el.find( '.jet-animated-box' ).removeClass( 'flipped-stop' );
			}
		},

		onCountdownSectionActivated: function( sectionName, editor ) {
			var editedElement = editor.getOption( 'editedElementView' ),
				prevEditedElement = window.JetElementsEditor.editedElement;

			if ( prevEditedElement
				&& 'jet-countdown-timer' === prevEditedElement.model.get( 'widgetType' )
				&& 'jet-countdown-timer' !== editedElement.model.get( 'widgetType' )
			) {

				prevEditedElement.$el.find( '.jet-countdown-timer-message' ).hide();
				window.JetElementsEditor.editedElement = null;
			}

			if ( 'jet-countdown-timer' !== editedElement.model.get( 'widgetType' ) ) {
				return;
			}

			window.JetElementsEditor.editedElement = editedElement;
			window.JetElementsEditor.activeSection = sectionName;

			if ( 'section_message_style' === sectionName ) {
				editedElement.$el.find( '.jet-countdown-timer-message' ).show();
			} else {
				editedElement.$el.find( '.jet-countdown-timer-message' ).hide();
			}

		},

		onPreviewLoaded: function() {
			var elementorFrontend = $('#elementor-preview-iframe')[0].contentWindow.elementorFrontend;

			elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ){

				$scope.find( '.jet-elements-edit-template-link' ).on( 'click', function( event ) {
					window.open( $( this ).attr( 'href' ) );
				} );
			} );
		}
	};

	$( window ).on( 'elementor:init', JetElementsEditor.init );

	window.JetElementsEditor = JetElementsEditor;

}( jQuery ) );
