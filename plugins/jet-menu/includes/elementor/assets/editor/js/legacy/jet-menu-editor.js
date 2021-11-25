( function( $ ) {

	'use strict';

	var JetMenuEditor = {

		activeSection: false,

		currentElement: false,

		currentSection: false,

		prevSection: false,


		init: function() {
			elementor.channels.editor.on( 'section:activated', JetMenuEditor.sectionActivated );
		},

		sectionActivated: function( sectionName, editor ) {
			let desktopSections = [
					//'section_general',
					'section_menu_container_style',
					'section_main_menu_style',
					'section_sub_menu_style',
					'section_icon_style',
					'section_badge_style',
					'section_arrow_style',
				],
				mobileSections = [
					'section_mobile_layout',
					'section_mobile_menu_toggle_style',
					'section_mobile_menu_container_style',
					'section_mobile_menu_items_style',
					'section_mobile_menu_advanced_style'
				];

			let currentElement = JetMenuEditor.currentElement = editor.getOption( 'editedElementView' ) || false;

			if ( ! currentElement ) {
				return;
			}

			if ( 'jet-mega-menu' == currentElement.model.get( 'widgetType' ) ) {

				let widgetId = currentElement.model.get( 'id' );

				JetMenuEditor.prevSection = JetMenuEditor.currentSection;
				JetMenuEditor.currentSection = sectionName;

				if ( 'section_general' === sectionName ) {
					currentElement.model.setSetting( 'force-editor-device', false );
					currentElement.model.renderRemoteServer();
				}

				if ( desktopSections.includes( sectionName ) && ! desktopSections.includes( JetMenuEditor.prevSection ) ) {
					currentElement.model.setSetting( 'force-editor-device', 'desktop' );
					currentElement.model.renderRemoteServer();
					currentElement.model.setSetting( 'force-editor-device', false );
				}

				if ( mobileSections.includes( sectionName ) && ! mobileSections.includes( JetMenuEditor.prevSection ) ) {
					currentElement.model.setSetting( 'force-editor-device', 'mobile' );
					currentElement.model.renderRemoteServer();
					currentElement.model.setSetting( 'force-editor-device', false );
				}
			}

		}

	};

	$( window ).on( 'elementor:init', JetMenuEditor.init );

	window.JetMenuEditor = JetMenuEditor;

}( jQuery ) );
