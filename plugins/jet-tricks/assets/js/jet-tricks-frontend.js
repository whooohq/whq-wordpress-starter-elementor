( function( $, elementor ) {

	'use strict';

	var JetTricks = {

		init: function() {
			elementor.hooks.addAction( 'frontend/element_ready/section', JetTricks.elementorSection );
			elementor.hooks.addAction( 'frontend/element_ready/column', JetTricks.elementorColumn );
			elementor.hooks.addAction( 'frontend/element_ready/widget', JetTricks.elementorWidget );

			var widgets = {
				'jet-view-more.default' : JetTricks.widgetViewMore,
				'jet-unfold.default' : JetTricks.widgetUnfold,
				'jet-hotspots.default' : JetTricks.widgetHotspots
			};

			$.each( widgets, function( widget, callback ) {
				elementor.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});
		},

		elementorSection: function( $scope ) {
			var $target        = $scope,
				sectionId      = $scope.data( 'id' ),
				editMode       = Boolean( elementor.isEditMode() ),
				settings       = {};

			if ( window.JetTricksSettings && window.JetTricksSettings.elements_data.sections.hasOwnProperty( sectionId ) ) {
				settings = window.JetTricksSettings.elements_data.sections[ sectionId ];
			}

			if ( editMode ) {
				settings = JetTricks.sectionEditorSettings( sectionId );
			}

			if ( ! settings ) {
				return false;
			}

			if ( jQuery.isEmptyObject( settings ) ) {
				return false;
			}

			if ( 'false' === settings.particles || '' === settings.particles_json ) {
				return false;
			}

			var particlesId   = 'jet-tricks-particles-instance-' + sectionId,
				particlesJson = JSON.parse( settings.particles_json );

			$scope.prepend( '<div id="' + particlesId + '" class="jet-tricks-particles-section__instance"></div>' );

			tsParticles.load( particlesId, particlesJson );

		},

		elementorColumn: function( $scope ) {
			var $target  = $scope,
				$parentSection = $scope.closest( '.elementor-section' ),
				isLegacyModeActive = !!$target.find( '> .elementor-column-wrap' ).length,
				$window  = $( window ),
				columnId = $target.data( 'id' ),
				editMode = Boolean( elementor.isEditMode() ),
				settings = {},
				stickyInstance = null,
				stickyInstanceOptions = {
					topSpacing: 50,
					bottomSpacing: 50,
					containerSelector: isLegacyModeActive ? '.elementor-row' : '.elementor-container',
					innerWrapperSelector: isLegacyModeActive ? '.elementor-column-wrap' : '.elementor-widget-wrap',
				},
				$observerTarget = $target.find('.elementor-element');

			if ( ! editMode ) {
				settings = $target.data( 'jet-settings' );

				if ( $target.hasClass( 'jet-sticky-column' ) ) {

					if ( -1 !== settings['stickyOn'].indexOf( elementorFrontend.getCurrentDeviceMode() ) ) {

						stickyInstanceOptions.topSpacing = settings['topSpacing'];
						stickyInstanceOptions.bottomSpacing = settings['bottomSpacing'];

						imagesLoaded( $parentSection, function() {
							$target.data( 'stickyColumnInit', true );
							stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
						} );

						var targetMutation = $target[0],
							config         = { attributes: true, childList: true, subtree: true };

						var callbackMutation = function( mutationsList, observer ) {
							for( var mutation of mutationsList ) {
								if ( 'attributes' === mutation.type ) {
									$target[0].style.height = 'auto';
								}
							}
						};

						var observer = new MutationObserver( callbackMutation );
						observer.observe( targetMutation, config );

						$window.on( 'resize.JetTricksStickyColumn orientationchange.JetTricksStickyColumn', JetTricksTools.debounce( 50, resizeDebounce ) );

						MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

						var observer = new MutationObserver( function( mutations ) {
							if ( stickyInstance ) {
								mutations.forEach( function(mutation){
									if (mutation.attributeName === 'class') {
										setTimeout( function() {
											stickyInstance.destroy();
											stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
										}, 100 );
									}
								} )
							}
						} );

						$observerTarget.each( function(){
							observer.observe( $( this )[0], {
								// subtree: true,
								// childList: true,
								attributes: true
							} );
						} )
					}
				}
			} else {

				return false;

				settings = JetTricks.columnEditorSettings( columnId );

				if ( 'true' === settings['sticky'] ) {
					$target.addClass( 'jet-sticky-column' );

					if ( -1 !== settings['stickyOn'].indexOf( elementorFrontend.getCurrentDeviceMode() ) ) {
						stickyInstanceOptions.topSpacing = settings['topSpacing'];
						stickyInstanceOptions.bottomSpacing = settings['bottomSpacing'];

						$target.data( 'stickyColumnInit', true );
						stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );

						$window.on( 'resize.JetTricksStickyColumn orientationchange.JetTricksStickyColumn', JetTricksTools.debounce( 50, resizeDebounce ) );
					}
				}
			}

			function resizeDebounce() {
				var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
					availableDevices  = settings['stickyOn'] || [],
					isInit            = $target.data( 'stickyColumnInit' );

				if ( -1 !== availableDevices.indexOf( currentDeviceMode ) ) {

					if ( ! isInit ) {
						$target.data( 'stickyColumnInit', true );
						stickyInstance = new StickySidebar( $target[0], stickyInstanceOptions );
						stickyInstance.updateSticky();
					}
				} else {
					$target.data( 'stickyColumnInit', false );
					stickyInstance.destroy();
				}
			}

		},

		elementorWidget: function( $scope ) {
			var parallaxInstance = null,
				satelliteInstance = null,
				tooltipInstance = null;

			parallaxInstance = new jetWidgetParallax( $scope );
			parallaxInstance.init();

			satelliteInstance = new jetWidgetSatellite( $scope );
			satelliteInstance.init();

			tooltipInstance = new jetWidgetTooltip( $scope );
			tooltipInstance.init();

		},

		getElementorElementSettings: function( $scope ) {

			if ( window.elementorFrontend && window.elementorFrontend.isEditMode() && $scope.hasClass( 'elementor-element-edit-mode' ) ) {
				return JetTricks.getEditorElementSettings( $scope );
			}

			return $scope.data( 'settings' ) || {};
		},

		getEditorElementSettings: function( $scope ) {
			var modelCID = $scope.data( 'model-cid' ),
				elementData;

			if ( ! modelCID ) {
				return {};
			}

			if ( ! elementor.hasOwnProperty( 'config' ) ) {
				return {};
			}

			if ( ! elementor.config.hasOwnProperty( 'elements' ) ) {
				return {};
			}

			if ( ! elementor.config.elements.hasOwnProperty( 'data' ) ) {
				return {};
			}

			elementData = elementor.config.elements.data[ modelCID ];

			if ( ! elementData ) {
				return {};
			}

			return elementData.toJSON();
		},

		widgetViewMore: function( $scope ) {
			var $target         = $scope.find( '.jet-view-more' ),
				instance        = null,
				settings        = $target.data( 'settings' );

			instance = new jetViewMore( $target, settings );
			instance.init();
		},

		widgetUnfold: function( $scope ) {
			var $target                = $scope.find( '.jet-unfold' ),
				$button                = $( '.jet-unfold__button', $target ),
				$mask                  = $( '.jet-unfold__mask', $target ),
				$content               = $( '.jet-unfold__content', $target ),
				$contentInner          = $( '.jet-unfold__content-inner', $target),
				$trigger               = $( '.jet-unfold__trigger', $target),
				$separator             = $( '.jet-unfold__separator', $target ),
				settings               = $.extend( {}, $target.data( 'settings' ), JetTricks.getElementorElementSettings( $scope ) ),
				maskBreakpointsHeights = [],
				prevBreakpoint         = '',
				unfoldDuration         = settings['unfoldDuration'] || settings['unfold_duration'],
				foldDuration           = settings['foldDuration'] || settings['fold_duration'],
				unfoldEasing           = settings['unfoldEasing'] || settings['unfold_easing'],
				foldEasing             = settings['foldEasing'] || settings['fold_easing'],
				maskHeightAdv          = 20,
				heightCalc             = '',
				autoHide               = settings['autoHide'] || false,
				autoHideTime           = settings['autoHideTime'] && 0 != settings['autoHideTime']['size'] ? settings['autoHideTime']['size'] : 5,
				hideOutsideClick       = settings['hideOutsideClick'] || false,
				autoHideTrigger,
				activeBreakpoints      = elementor.config.responsive.activeBreakpoints;

			maskBreakpointsHeights['desktop']    = [];
			maskBreakpointsHeights['widescreen'] = [];

			maskBreakpointsHeights['desktop']['maskHeight'] = '' != settings['mask_height']['size'] ? settings['mask_height']['size'] : 50;

			prevBreakpoint = 'desktop';

			Object.keys(  activeBreakpoints ).reverse().forEach(  function( breakpointName ) {

				if ( 'widescreen' === breakpointName ) {
					maskBreakpointsHeights['widescreen']['maskHeight'] = '' != settings['mask_height_widescreen']['size'] ? settings['mask_height_widescreen']['size'] : maskBreakpointsHeights['desktop']['maskHeight'];
				} else {
					maskBreakpointsHeights[breakpointName] = [];

					maskBreakpointsHeights[breakpointName]['maskHeight'] = '' != settings['mask_height_' + breakpointName]['size'] ? settings['mask_height_' + breakpointName]['size'] : maskBreakpointsHeights[prevBreakpoint]['maskHeight'];

					prevBreakpoint = breakpointName;
				}
			} );

			onLoaded();

			if ( 'true' === hideOutsideClick ) {
				$( document ).on( 'click', function( event ) {
					let container = $target;
					if ( !container.is( event.target ) && 0 === container.has( event.target ).length && $target.hasClass( 'jet-unfold-state' ) ) {
						$button.trigger( 'click' );
					}
				} )
			}

			$target.one('transitionend webkitTransitionEnd oTransitionEnd', onLoaded );

			function onLoaded() {
				var deviceHeight = getDeviceHeight();

				heightCalc = +deviceHeight + maskHeightAdv;

				if ( heightCalc < $contentInner.height() ) {

					if ( ! $target.hasClass( 'jet-unfold-state' ) ) {
						$separator.css( {
							'opacity': '1'
						} );
					}

					if ( ! $target.hasClass( 'jet-unfold-state' ) ) {
						$mask.css( {
							'height': deviceHeight
						} );
					} else {
						$mask.css( {
							'height': $contentInner.outerHeight()
						} );
					}
					$trigger.css( 'display', 'flex' );
				} else {
					$trigger.hide();
					$mask.css( {
						'height': '100%'
					} );
					$content.css( {
						'max-height': 'none'
					} );
					$separator.css( {
						'opacity': '0'
					} );
				}
			}

			$( window ).on( 'resize.jetWidgetUnfold orientationchange.jetWidgetUnfold', JetTricksTools.debounce( 50, function(){
				onLoaded();
			} ) );

			$button.keypress( function( e ) {
				if ( e.which == 13 ) {
					$button.click();
					return false;
				}
			} );

			$button.on( 'click.jetUnfold', function(e) {
				var $this         = $( this ),
					$buttonText   = $( '.jet-unfold__button-text', $this ),
					unfoldText    = $this.data( 'unfold-text' ),
					foldText      = $this.data( 'fold-text' ),
					$buttonIcon   = $( '.jet-unfold__button-icon', $this ),
					unfoldIcon    = $this.data( 'unfold-icon' ),
					foldIcon      = $this.data( 'fold-icon' ),
					contentHeight = $contentInner.outerHeight(),
					deviceHeight  = getDeviceHeight();

				e.preventDefault();

				if ( ! $target.hasClass( 'jet-unfold-state' ) ) {
					$target.addClass( 'jet-unfold-state' );

					$separator.css( {
						'opacity': '0'
					} );

					$buttonIcon.html( foldIcon );
					$buttonText.html( foldText );

					anime( {
						targets: $mask[0],
						height: contentHeight,
						duration: unfoldDuration['size'],
						easing: unfoldEasing,
						complete: function( anim ) {
							// Recalculate listing masonry.
							$( document ).trigger( 'jet-engine/listing/recalculate-masonry' );
						}
					} );

					if ( 'true' === autoHide ) {
						autoHideTrigger = setTimeout( function() {
							$button.trigger( 'click' );
						}, autoHideTime * 1000 );
					}
				} else {
					clearTimeout( autoHideTrigger );

					$target.removeClass( 'jet-unfold-state' );

					$separator.css( {
						'opacity': '1'
					} );

					$buttonIcon.html( unfoldIcon );
					$buttonText.html( unfoldText );

					anime( {
						targets: $mask[0],
						height: deviceHeight,
						duration: foldDuration['size'],
						easing: foldEasing,
						complete: function( anim ) {
							if ( 'true' === settings['foldScrolling'] ) {
								$( 'html, body' ).animate( {
									scrollTop: $target.offset().top
								}, 300 );
							}

							// Recalculate listing masonry.
							$( document ).trigger( 'jet-engine/listing/recalculate-masonry' );
						}
					} );
				}
			} );

			function getDeviceHeight() {
				var deviceMode = elementor.getCurrentDeviceMode();

				return maskBreakpointsHeights[deviceMode]['maskHeight'];
			}
		},

		widgetHotspots: function( $scope ) {
			var $target   = $scope.find( '.jet-hotspots' ),
				$hotspots = $( '.jet-hotspots__item', $target),
				settings  = $target.data( 'settings' ),
				editMode  = Boolean( elementor.isEditMode() ),
				itemActiveClass = 'jet-hotspots__item--active';

			$target.imagesLoaded().progress( function() {
				$target.addClass( 'image-loaded' );
			} );

			$hotspots.each( function( index ) {
				var $this          = $( this ),
					horizontal     = $this.data( 'horizontal-position' ),
					vertical       = $this.data( 'vertical-position' ),
					itemSelector   = $this[0],
					options        = {};

				$this.css( {
					'left': horizontal + '%',
					'top': vertical + '%'
				} );

				if ( itemSelector._tippy ) {
					itemSelector._tippy.destroy();
				}

				options = {
					arrow: settings['tooltipArrow'] ? true : false,
					placement: settings['tooltipPlacement'],
					trigger: settings['tooltipTrigger'],
					appendTo: $target[0],
					hideOnClick: 'manual' !== settings['tooltipTrigger'],
					maxWidth: 'none',
					allowHTML: true,
					popperOptions: {
						strategy: 'fixed',
					},
					onShow() {
						$( itemSelector ).addClass( itemActiveClass );
					},
					onHidden() {
						$( itemSelector ).removeClass( itemActiveClass );
					}
				}

				if ( 'manual' != settings['tooltipTrigger'] ) {
					options['duration']  = [ settings['tooltipShowDuration']['size'], settings['tooltipHideDuration']['size'] ];
					options['animation'] = settings['tooltipAnimation'];
					options['delay']     = settings['tooltipDelay'];
				}

				tippy( [ itemSelector ], options );

				if ( 'manual' === settings['tooltipTrigger'] && itemSelector._tippy ) {
					itemSelector._tippy.show();
				}

				if ( settings['tooltipShowOnInit'] && itemSelector._tippy ) {
					itemSelector._tippy.show();
				}

			} );
		},

		columnEditorSettings: function( columnId ) {
			var editorElements = null,
				columnData     = {};

			if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
				return false;
			}

			editorElements = window.elementor.elements;

			if ( ! editorElements.models ) {
				return false;
			}

			$.each( editorElements.models, function( index, obj ) {

				$.each( obj.attributes.elements.models, function( index, obj ) {
					if ( columnId == obj.id ) {
						columnData = obj.attributes.settings.attributes;
					}
				} );

			} );

			return {
				'sticky': columnData['jet_tricks_column_sticky'] || false,
				'topSpacing': columnData['jet_tricks_top_spacing'] || 50,
				'bottomSpacing': columnData['jet_tricks_bottom_spacing'] || 50,
				'stickyOn': columnData['jet_tricks_column_sticky_on'] || [ 'desktop', 'tablet', 'mobile']
			}

		},

		sectionEditorSettings: function( sectionId ) {
			var editorElements = null,
				sectionData     = {};

			if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
				return false;
			}

			editorElements = window.elementor.elements;

			if ( ! editorElements.models ) {
				return false;
			}

			$.each( editorElements.models, function( index, obj ) {
				if ( sectionId == obj.id ) {
					sectionData = obj.attributes.settings.attributes;
				}
			} );

			return {
				'particles': sectionData['section_jet_tricks_particles'] || 'false',
				'particles_json': sectionData['section_jet_tricks_particles_json'] || '',
			}

		}

	};

	$( window ).on( 'elementor/frontend/init', JetTricks.init );

	var JetTricksTools = {
		debounce: function( threshold, callback ) {
			var timeout;

			return function debounced( $event ) {
				function delayed() {
					callback.call( this, $event );
					timeout = null;
				}

				if ( timeout ) {
					clearTimeout( timeout );
				}

				timeout = setTimeout( delayed, threshold );
			};
		},

		widgetEditorSettings: function( widgetId ) {
			var editorElements = null,
				widgetData     = {};

			if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
				return false;
			}

			editorElements = window.elementor.elements;

			if ( ! editorElements.models ) {
				return false;
			}

			$.each( editorElements.models, function( index, obj ) {

				$.each( obj.attributes.elements.models, function( index, obj ) {

					$.each( obj.attributes.elements.models, function( index, obj ) {
						if ( widgetId == obj.id ) {
							widgetData = obj.attributes.settings.attributes;
						}
					} );

				} );

			} );

			return {
				'speed': widgetData['jet_tricks_widget_parallax_speed'] || { 'size': 50, 'unit': '%'},
				'parallax': widgetData['jet_tricks_widget_parallax'] || 'false',
				'invert': widgetData['jet_tricks_widget_parallax_invert'] || 'false',
				'stickyOn': widgetData['jet_tricks_widget_parallax_on'] || [ 'desktop', 'tablet', 'mobile'],
				'satellite': widgetData['jet_tricks_widget_satellite'] || 'false',
				'satelliteType': widgetData['jet_tricks_widget_satellite_type'] || 'text',
				'satellitePosition': widgetData['jet_tricks_widget_satellite_position'] || 'top-center',
				'tooltip': widgetData['jet_tricks_widget_tooltip'] || 'false',
				'tooltipDescription': widgetData['jet_tricks_widget_tooltip_description'] || 'Lorem Ipsum',
				'tooltipPlacement': widgetData['jet_tricks_widget_tooltip_placement'] || 'top',
				'tooltipArrow': 'true' === widgetData['jet_tricks_widget_tooltip_arrow'] ? true : false,
				'xOffset': widgetData['jet_tricks_widget_tooltip_x_offset'] || 0,
				'yOffset': widgetData['jet_tricks_widget_tooltip_y_offset'] || 0,
				'tooltipAnimation': widgetData['jet_tricks_widget_tooltip_animation'] || 'shift-toward',
				'tooltipTrigger': widgetData['jet_tricks_widget_tooltip_trigger'] || 'mouseenter',
				'customSelector': widgetData['jet_tricks_widget_tooltip_custom_selector'] || '',
				'zIndex': widgetData['jet_tricks_widget_tooltip_z_index'] || '999',
				'delay': widgetData['jet_tricks_widget_tooltip_delay'] || '0'
			}
		}
	}

	/**
	 * Jet jetViewMore Class
	 *
	 * @return {void}
	 */
	window.jetViewMore = function( $selector, settings ) {
		var self            = this,
			$window         = $( window ),
			$button         = $( '.jet-view-more__button', $selector ),
			defaultSettings = {
				sections: {},
				effect: 'move-up',
				showall: false
			},
			settings        = $.extend( {}, defaultSettings, settings ),
			sections        = settings['sections'],
			sectionsData    = {},
			buttonVisible   = true,
			editMode        = Boolean( elementor.isEditMode() );

		/**
		 * Init
		 */
		self.init = function() {
			self.setSectionsData();

			if ( editMode ) {
				return false;
			}

			// Add Events
			$button.on( 'click', function() {

				for ( var section in sectionsData ) {
					var $section = sectionsData[ section ]['selector'];

					if ( ! settings.showall ) {
						if ( ! sectionsData[ section ]['visible'] ) {
							sectionsData[ section ]['visible'] = true;
							$section.addClass( 'view-more-visible' );
							$section.addClass( 'jet-tricks-' + settings['effect'] + '-effect' );

							break;
						}
					} else {
						sectionsData[ section ]['visible'] = true;
						$section.addClass( 'view-more-visible' );
						$section.addClass( 'jet-tricks-' + settings['effect'] + '-effect' );
					}

				}

				for ( var section in sectionsData ) {
					buttonVisible = true;

					if ( sectionsData[ section ]['visible'] ) {
						buttonVisible = false;
					}
				}

				if ( ! buttonVisible ) {
					$button.css( { 'display': 'none' } );
				}

			} );
		};

		self.setSectionsData = function() {

			for ( var section in sections ) {
				var $selector = $( '#' + sections[ section ] );

				if ( ! editMode ) {
					$selector.addClass( 'jet-view-more-section' );
				} else {
					$selector.addClass( 'jet-view-more-section-edit-mode' );
				}

				sectionsData[ section ] = {
					'section_id': sections[ section ],
					'selector': $selector,
					'visible': false,
				}
			}
		};
	};

	/**
	 * [jetWidgetParallax description]
	 * @param  {[type]} $scope [description]
	 * @return {[type]}        [description]
	 */
	window.jetWidgetParallax = function( $scope ) {
		var self         = this,
			$target      = $( '> .elementor-widget-container', $scope ),
			$section     = $scope.closest( '.elementor-top-section' ),
			widgetId     = $scope.data('id'),
			settings     = {},
			editMode     = Boolean( elementor.isEditMode() ),
			$window      = $( window ),
			isSafari     = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/),
			platform     = navigator.platform,
			safariClass  = isSafari ? 'is-safari' : '',
			macClass    = 'MacIntel' == platform ? ' is-mac' : '';

		/**
		 * Init
		 */
		self.init = function() {

			$scope.addClass( macClass );

			if ( ! editMode ) {
				settings = $scope.data( 'jet-tricks-settings' );
			} else {
				settings = JetTricksTools.widgetEditorSettings( widgetId );
			}

			if ( ! settings ) {
				return false;
			}

			if ( 'undefined' === typeof settings ) {
				return false;
			}

			if ( 'false' === settings['parallax'] || 'undefined' === typeof settings['parallax'] ) {
				return false;
			}

			$window.on( 'scroll.jetWidgetParallax resize.jetWidgetParallax', self.scrollHandler ).trigger( 'resize.jetWidgetParallax' );
		};

		self.scrollHandler = function( event ) {
			var speed             = +settings['speed']['size'] * 0.01,
				invert            = 'true' == settings['invert'] ? -1 : 1,
				winHeight         = $window.height(),
				winScrollTop      = $window.scrollTop(),
				offsetTop         = $scope.offset().top,
				thisHeight        = $scope.outerHeight(),
				sectionHeight     = $section.outerHeight(),
				positionDelta     = winScrollTop - offsetTop + ( winHeight / 2 ),
				abs               = positionDelta > 0 ? 1 : -1,
				posY              = abs * Math.pow( Math.abs( positionDelta ), 0.85 ),
				availableDevices  = settings['stickyOn'] || [],
				currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

			posY = invert * Math.ceil( speed * posY );

			if ( -1 !== availableDevices.indexOf( currentDeviceMode ) ) {
				$target.css( {
					'transform': 'translateY(' + posY + 'px)'
				} );
			} else {
				$target.css( {
					'transform': 'translateY(0)'
				} );
			}
		};
	};

	/**
	 * [jetWidgetSatellite description]
	 * @param  {[type]} $scope [description]
	 * @return {[type]}        [description]
	 */
	window.jetWidgetSatellite = function( $scope ) {
		var self     = this,
			widgetId = $scope.data('id'),
			settings = {},
			editMode = Boolean( elementor.isEditMode() );

		/**
		 * Init
		 */
		self.init = function() {

			if ( ! editMode ) {
				settings = $scope.data( 'jet-tricks-settings' );
			} else {
				settings = JetTricksTools.widgetEditorSettings( widgetId );
			}

			if ( ! settings ) {
				return false;
			}

			if ( 'undefined' === typeof settings ) {
				return false;
			}

			if ( 'false' === settings['satellite'] || 'undefined' === typeof settings['satellite'] ) {
				return false;
			}

			$scope.addClass( 'jet-satellite-widget' );

			$( '.jet-tricks-satellite', $scope ).addClass( 'jet-tricks-satellite--' + settings['satellitePosition'] );
		};
	};

	/**
	 * [jetWidgetTooltip description]
	 * @param  {[type]} $scope [description]
	 * @return {[type]}        [description]
	 */
	window.jetWidgetTooltip = function( $scope ) {
		var self            = this,
			widgetId        = $scope.data('id'),
			widgetSelector  = $scope[0],
			tooltipSelector = widgetSelector,
			settings        = {},
			editMode        = Boolean( elementor.isEditMode() ),
			delay,
			tooltipEvent    = editMode ? 'click' : 'mouseenter';

		/**
		 * Init
		 */
		self.init = function() {

			if ( ! editMode ) {
				settings = $scope.data( 'jet-tricks-settings' );
			} else {
				settings = JetTricksTools.widgetEditorSettings( widgetId );
			}

			if ( widgetSelector._tippy ) {
				widgetSelector._tippy.destroy();
			}

			if ( ! settings ) {
				return false;
			}

			if ( 'undefined' === typeof settings ) {
				return false;
			}

			if ( 'false' === settings['tooltip'] || 'undefined' === typeof settings['tooltip'] || '' === settings['tooltipDescription'] ) {
				return false;
			}

			$scope.addClass( 'jet-tooltip-widget' );

			if ( settings['customSelector'] ) {
				tooltipSelector = $( settings['customSelector'], $scope )[0];
			}

			if ( editMode && ! $( '#jet-tricks-tooltip-content-' + widgetId )[0] ) {
				var template = $( '<div>', {
					id: 'jet-tricks-tooltip-content-' + widgetId,
					class: 'jet-tooltip-widget__content'
				} );

				template.html( settings['tooltipDescription'] );
				$scope.append( template );
			}

			tippy(
				[ tooltipSelector ],
				{
					content: $scope.find( '.jet-tooltip-widget__content' )[0].innerHTML,
					allowHTML: true,
					appendTo: widgetSelector,
					arrow: settings['tooltipArrow'] ? true : false,
					placement: settings['tooltipPlacement'],
					offset: [ settings['xOffset'], settings['yOffset'] ],
					animation: settings['tooltipAnimation'],
					trigger: settings['tooltipTrigger'],
					interactive: true,
					zIndex: settings['zIndex'],
					maxWidth: 'none',
					delay: settings['delay']['size'] ? settings['delay']['size'] : 0,
					popperOptions: {
						strategy: 'fixed',
					},
				}
			);

			if ( editMode && widgetSelector._tippy ) {
				widgetSelector._tippy.show();
			}

		};
	};

}( jQuery, window.elementorFrontend ) );
