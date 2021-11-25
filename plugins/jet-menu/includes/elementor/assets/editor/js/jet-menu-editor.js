( function( $ ) {

	'use strict';

	var JetMenuEditor = {

		activeSection: false,

		currentElement: false,

		currentSection: false,

		prevSection: false,

		isMobileRender: false,

		init: function() {
			elementor.channels.editor.on( 'section:activated', JetMenuEditor.sectionActivated );
		},

		sectionActivated: function( sectionName, editor ) {
			let mainSections = [
				'section_main_menu_styles',
				'section_dropdown_menu_styles',
			],
			mobileSections = [
				'mobile_device_render',
				'mobile_device_render_styles',
			];

			let currentElement = JetMenuEditor.currentElement = editor.getOption( 'editedElementView' ) || false;

			if ( ! currentElement ) {
				return;
			}

			if ( 'jet-mega-menu' == currentElement.model.get( 'widgetType' ) ) {

				let widgetId = currentElement.model.get( 'id' );

				JetMenuEditor.prevSection = JetMenuEditor.currentSection;
				JetMenuEditor.currentSection = sectionName;

				if ( 'section_layout' === sectionName && JetMenuEditor.isMobileRender ) {
					currentElement.model.setSetting( 'force-mobile-render', false );
					currentElement.model.renderRemoteServer();
				}

				if ( mainSections.includes( sectionName ) && JetMenuEditor.isMobileRender ) {
					JetMenuEditor.isMobileRender = false;
					currentElement.model.setSetting( 'force-mobile-render', false );
					currentElement.model.renderRemoteServer();
					currentElement.model.setSetting( 'force-mobile-render', false );
				}

				if ( mobileSections.includes( sectionName ) && ! JetMenuEditor.isMobileRender ) {
					JetMenuEditor.isMobileRender = true;
					currentElement.model.setSetting( 'force-mobile-render', true );
					currentElement.model.renderRemoteServer();
					currentElement.model.setSetting( 'force-mobile-render', false );
				}
			}

		}

	};

	$( window ).on( 'elementor:init', JetMenuEditor.init );

	window.JetMenuEditor = JetMenuEditor;

}( jQuery ) );
