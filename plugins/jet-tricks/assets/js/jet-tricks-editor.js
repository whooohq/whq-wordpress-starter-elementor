(function( $ ) {

	'use strict';

	var JetTricksEditor = {

		init: function() {
			window.elementor.on( 'preview:loaded', function() {
				elementor.$preview[0].contentWindow.JetTricksEditor = JetTricksEditor;

				JetTricksEditor.onPreviewLoaded();
			} );
		},

		onPreviewLoaded: function() {
			var elementorFrontend = $( '#elementor-preview-iframe' )[0].contentWindow.elementorFrontend;

			elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
				$scope.find( '.jet-tricks-edit-template-link' ).on( 'click', function( event ) {
					window.open( $( this ).attr( 'href' ) );
				} );
			} );
		}
	};

	$( window ).on( 'elementor:init', JetTricksEditor.init );

	window.JetTricksEditor = JetTricksEditor;

}( jQuery ));
