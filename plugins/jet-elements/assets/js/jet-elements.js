( function( $, elementor ) {

	"use strict";

	var JetElements = {

		init: function() {

			var widgets = {
				'jet-carousel.default' : JetElements.widgetCarousel,
				'jet-circle-progress.default' : JetElements.widgetProgress,
				'jet-map.default' : JetElements.widgetMap,
				'jet-countdown-timer.default' : JetElements.widgetCountdown,
				'jet-posts.default' : JetElements.widgetPosts,
				'jet-animated-text.default' : JetElements.widgetAnimatedText,
				'jet-animated-box.default' : JetElements.widgetAnimatedBox,
				'jet-images-layout.default' : JetElements.widgetImagesLayout,
				'jet-slider.default' : JetElements.widgetSlider,
				'jet-testimonials.default' : JetElements.widgetTestimonials,
				'jet-image-comparison.default' : JetElements.widgetImageComparison,
				'jet-instagram-gallery.default' : JetElements.widgetInstagramGallery,
				'jet-scroll-navigation.default' : JetElements.widgetScrollNavigation,
				'jet-subscribe-form.default' : JetElements.widgetSubscribeForm,
				'jet-progress-bar.default' : JetElements.widgetProgressBar,
				'jet-portfolio.default' : JetElements.widgetPortfolio,
				'jet-timeline.default': JetElements.widgetTimeLine,
				'jet-table.default': JetElements.widgetTable,
				'jet-dropbar.default': JetElements.widgetDropbar,
				'jet-video.default': JetElements.widgetVideo,
				'jet-audio.default': JetElements.widgetAudio,
				'jet-horizontal-timeline.default': JetElements.widgetHorizontalTimeline,
				'mp-timetable.default': JetElements.widgetTimeTable,
				'jet-pie-chart.default': JetElements.widgetPieChart,
				'jet-bar-chart.default': JetElements.widgetBarChart,
				'jet-line-chart.default': JetElements.widgetLineChart,
				'jet-lottie.default': JetElements.widgetLottie,
				'jet-pricing-table.default': JetElements.widgetPricingTable
			};

			$.each( widgets, function( widget, callback ) {
				elementor.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});

			elementor.hooks.addAction( 'frontend/element_ready/section', JetElements.elementorSection );
		},

		widgetCountdown: function( $scope ) {

			var timeInterval,
				$countdown = $scope.find( '.jet-countdown-timer' ),
				type = $countdown.data( 'type' ),
				endTime = null,
				dueDate = $countdown.data( 'due-date' ),
				startDate = $countdown.data( 'start-date' ),
				actions = $countdown.data( 'expire-actions' ),
				evergreenInterval = $countdown.data( 'evergreen-interval' ),
				restartInterval = $countdown.data( 'restart-interval' ),
				elements = {
					days: $countdown.find( '[data-value="days"]' ),
					hours: $countdown.find( '[data-value="hours"]' ),
					minutes: $countdown.find( '[data-value="minutes"]' ),
					seconds: $countdown.find( '[data-value="seconds"]' )
				};

			var initClock = function() {

				switch( type ) {
					case 'due_date':
						endTime = new Date( dueDate * 1000 );
						break;

					case 'evergreen':
						if ( evergreenInterval > 0 ) {
							endTime = getEvergreenDate();
						}
						break;

					case 'endless':
						var currentTime = new Date(),
							startTime = new Date( startDate * 1000 );

						if ( currentTime > startTime ) {
							endTime = new Date( (startDate + restartInterval) * 1000 );
						}

						if ( endTime && ( currentTime > endTime ) ) {
							endTime = endTime.setSeconds( endTime.getSeconds() + (Math.floor( (currentTime - endTime) / (restartInterval * 1000) ) + 1) * restartInterval );
						}

						break;
				}

				updateClock();
				timeInterval = setInterval( updateClock, 1000 );
			};

			var updateClock = function() {

				if ( ! endTime ) {
					return;
				}

				var timeRemaining = getTimeRemaining(
					endTime,
					{
						days:    elements.days.length,
						hours:   elements.hours.length,
						minutes: elements.minutes.length
					}
				);

				$.each( timeRemaining.parts, function( timePart ) {

					var $element = elements[ timePart ];

					if ( $element.length ) {
						$element.html( this );
					}

				} );

				if ( timeRemaining.total <= 0 ) {
					clearInterval( timeInterval );
					runActions();
				}
			};

			var splitNum = function( num ) {

				var num    = num.toString(),
					arr    = [],
					result = '';

				if ( 1 === num.length ) {
					num = 0 + num;
				}

				arr = num.match(/\d{1}/g);

				$.each( arr, function( index, val ) {
					result += '<span class="jet-countdown-timer__digit">' + val + '</span>';
				});

				return result;
			};

			var getTimeRemaining = function( endTime, visible ) {

				var timeRemaining = endTime - new Date(),
					seconds = Math.floor( ( timeRemaining / 1000 ) % 60 ),
					minutes = Math.floor( ( timeRemaining / 1000 / 60 ) % 60 ),
					hours = Math.floor( ( timeRemaining / ( 1000 * 60 * 60 ) ) % 24 ),
					days = Math.floor( timeRemaining / ( 1000 * 60 * 60 * 24 ) );

				if ( days < 0 || hours < 0 || minutes < 0 ) {
					seconds = minutes = hours = days = 0;
				}

				if ( ! visible.days ) {
					hours += days * 24;
					days = 0;
				}

				if ( ! visible.hours ) {
					minutes += hours * 60;
					hours = 0;
				}

				if ( ! visible.minutes ) {
					seconds += minutes * 60;
					minutes = 0;
				}

				return {
					total: timeRemaining,
					parts: {
						days: splitNum( days ),
						hours: splitNum( hours ),
						minutes: splitNum( minutes ),
						seconds: splitNum( seconds )
					}
				};
			};

			var runActions = function() {

				$scope.trigger( 'jetCountdownTimerExpire', $scope );

				if ( ! actions ) {
					return;
				}

				$.each( actions, function( index, action ) {
					switch ( action ) {
						case 'redirect':
							var redirect_url = $countdown.data( 'expire-redirect-url' );

							if ( redirect_url ) {
								window.location.href = redirect_url;
							}

							break;

						case 'message':
							$scope.find( '.jet-countdown-timer-message' ).show();
							break;

						case 'hide':
							$countdown.hide();
							break;

						case 'restart':

							endTime = new Date();
							endTime = endTime.setSeconds( endTime.getSeconds() + restartInterval );

							updateClock();
							timeInterval = setInterval( updateClock, 1000 );
							break;
					}
				} );
			};

			var getEvergreenDate = function() {
				var id = $scope.data( 'id' ),
					dueDateKey = 'jet_evergreen_countdown_due_date_' + id,
					intervalKey = 'jet_evergreen_countdown_interval_' + id,
					localDueDate = localStorage.getItem( dueDateKey ),
					localInterval = localStorage.getItem( intervalKey ),

					initEvergreenTimer = function(){
						var dueDate = new Date(),
							_endTime = dueDate.setSeconds( dueDate.getSeconds() + evergreenInterval );

						localStorage.setItem( dueDateKey, _endTime );
						localStorage.setItem( intervalKey, evergreenInterval );

						return _endTime;
					};

				if ( null === localDueDate && null === localInterval ) {
					return initEvergreenTimer();
				}

				if ( null !== localDueDate && evergreenInterval !== parseInt( localInterval, 10 ) ) {
					return initEvergreenTimer();
				}

				if ( localDueDate > 0 && parseInt( localInterval, 10 ) === evergreenInterval ) {
					return localDueDate;
				}
			};

			initClock();

		},

		widgetMap: function( $scope ) {

			var $container = $scope.find( '.jet-map' ),
				map,
				init,
				pins;

			if ( ! window.google || ! $container.length ) {
				return;
			}

			init = $container.data( 'init' );
			pins = $container.data( 'pins' );
			map  = new google.maps.Map( $container[0], init );

			if ( pins ) {
				$.each( pins, function( index, pin ) {

					var marker,
						infowindow,
						pinData = {
							position: pin.position,
							map: map
						};

					if ( '' !== pin.image ) {

						if ( undefined !== pin.image_width && undefined !== pin.image_height ) {
							var icon = {
								url:        pin.image,
								scaledSize: new google.maps.Size( pin.image_width, pin.image_height ),
								origin:     new google.maps.Point( 0, 0 ),
								anchor:     new google.maps.Point( pin.image_width/2, pin.image_height/2 )
							}

							pinData.icon = icon;
						} else {
							pinData.icon = pin.image;
						}
					}

					marker = new google.maps.Marker( pinData );

					if ( '' !== pin.desc || undefined !== pin.link_title ) {

						if ( undefined !== pin.link_title ) {
							var link_url               = pin.link.url,
								link_is_external       = 'on' === pin.link.is_external ? 'target="_blank"': '',
								link_nofollow          = 'on' === pin.link.nofollow ? 'rel="nofollow"': '',
								link_custom_attributes = undefined !== parse_custom_attributes( pin.link.custom_attributes ) ? parse_custom_attributes( pin.link.custom_attributes ) : '',
								link_layout;

							link_layout = '<div class="jet-map-pin__wrapper"><a class="jet-map-pin__link" href="' + link_url + '" ' + link_is_external + link_nofollow + link_custom_attributes + '>' + pin.link_title + '</a></div>';
							pin.desc += link_layout;
						}

						infowindow = new google.maps.InfoWindow({
							content: pin.desc,
							disableAutoPan: true
						});
					}

					marker.addListener( 'click', function() {
						infowindow.setOptions({ disableAutoPan: false });
						infowindow.open( map, marker );
					});

					if ( 'visible' === pin.state && '' !== pin.desc ) {
						infowindow.open( map, marker );
					}

				});
			}

			function parse_custom_attributes( attributes_string, delimiter = ',' ) {
				var attributes = attributes_string.split( delimiter ),
					result;

				result = attributes.reduce(function( res, attribute ) {
					var attr_key_value = attribute.split( '|' ),
						attr_key       = attr_key_value[0].toLowerCase(),
						attr_value     = '',
						regex          = new RegExp(/[-_a-z0-9]+/);

					if( !regex.test( attr_key ) ) {
						return;
					}

					// Avoid Javascript events and unescaped href.
					if ( 'href' === attr_key || 'on' === attr_key.substring( 0, 2 ) ) {
						return;
					}

					if ( undefined !== attr_key_value[1] ) {
						attr_value = attr_key_value[1].trim();
					} else {
						attr_value = '';
					}
					return res + attr_key + '="' + attr_value + '" ';
				}, '');

				return result;
			}
		},

		prepareWaypointOptions: function( $scope, waypointOptions ) {
			var options = waypointOptions || {},
				$parentPopup = $scope.closest( '.jet-popup__container-inner, .elementor-popup-modal .dialog-message' );

			if ( $parentPopup[0] ) {
				options.context = $parentPopup[0];
			}

			return options;
		},

		widgetProgress: function( $scope ) {

			var $progress = $scope.find( '.circle-progress' );

			if ( ! $progress.length ) {
				return;
			}

			var $value              = $progress.find( '.circle-progress__value' ),
				$meter              = $progress.find( '.circle-progress__meter' ),
				percent             = parseInt( $value.data( 'value' ) ),
				progress            = percent / 100,
				duration            = $scope.find( '.circle-progress-wrap' ).data( 'duration' ),
				currentDeviceMode   = elementorFrontend.getCurrentDeviceMode(),
				isAnimatedCircle    = false,
				breakpoints         = JetElementsTools.getElementorElementSettings( $scope ),
				breakpointsSettings = [],
				activeBreakpoints   = elementor.config.responsive.activeBreakpoints;

			breakpointsSettings['desktop'] = [];

			var breakpointSize        = breakpoints['circle_size']['size'] ? breakpoints['circle_size']['size'] : $progress[0].getAttribute( 'width' ),

				breakpointStrokeValue = breakpoints['value_stroke']['size'] ? breakpoints['value_stroke']['size'] : $progress[0].getElementsByClassName( 'circle-progress__value' )[0].getAttribute( 'stroke-width' ),

				breakpointStrokeBg    = breakpoints['bg_stroke']['size'] ? breakpoints['bg_stroke']['size'] : $progress[0].getElementsByClassName( 'circle-progress__meter' )[0].getAttribute( 'stroke-width' );

			breakpointSizes( 'desktop', breakpointSize, breakpointStrokeValue, breakpointStrokeBg );

			Object.keys( activeBreakpoints ).reverse().forEach( function( breakpointName, index ) {

				if ( 'widescreen' === breakpointName ){
					var breakpointSize        = breakpoints['circle_size_' + breakpointName]['size'] ? breakpoints['circle_size_' + breakpointName]['size'] : breakpoints['circle_size']['size'],

						breakpointStrokeValue = breakpoints['value_stroke_' + breakpointName]['size'] ? breakpoints['value_stroke_' + breakpointName]['size'] : breakpoints['value_stroke']['size'],

						breakpointStrokeBg    = breakpoints['bg_stroke_' + breakpointName]['size'] ? breakpoints['bg_stroke_' + breakpointName]['size'] : breakpoints['bg_stroke']['size'];

					breakpointsSettings[breakpointName] = [];

					breakpointSizes( breakpointName, breakpointSize, breakpointStrokeValue, breakpointStrokeBg );
				} else {
					var breakpointSize        = breakpoints['circle_size_' + breakpointName]['size'] ? breakpoints['circle_size_' + breakpointName]['size'] : $progress[0].getAttribute( 'width' ),

						breakpointStrokeValue = breakpoints['value_stroke_' + breakpointName]['size'] ? breakpoints['value_stroke_' + breakpointName]['size'] : $progress[0].getElementsByClassName( 'circle-progress__value' )[0].getAttribute( 'stroke-width' ),

						breakpointStrokeBg    = breakpoints['bg_stroke_' + breakpointName]['size'] ? breakpoints['bg_stroke_' + breakpointName]['size'] : $progress[0].getElementsByClassName( 'circle-progress__meter' )[0].getAttribute( 'stroke-width' );

					breakpointsSettings[breakpointName] = [];

					breakpointSizes( breakpointName, breakpointSize, breakpointStrokeValue, breakpointStrokeBg );
				}
			} );

			updateSvgSizes( breakpointsSettings[currentDeviceMode]['size'],
								breakpointsSettings[currentDeviceMode]['viewBox'],
								breakpointsSettings[currentDeviceMode]['center'],
								breakpointsSettings[currentDeviceMode]['radius'],
								breakpointsSettings[currentDeviceMode]['valStroke'],
								breakpointsSettings[currentDeviceMode]['bgStroke'],
								breakpointsSettings[currentDeviceMode]['circumference']
			);

			elementorFrontend.waypoint( $scope, function() {

				// animate counter
				var $number = $scope.find( '.circle-counter__number' ),
					data = $number.data();

				var decimalDigits = data.toValue.toString().match( /\.(.*)/ );

				if ( decimalDigits ) {
					data.rounding = decimalDigits[1].length;
				}

				data.duration = duration;

				$number.numerator( data );

				// animate progress
				var circumference = parseInt( $progress.data( 'circumference' ) ),
					dashoffset    = circumference * (1 - progress);

				$value.css({
					'transitionDuration': duration + 'ms',
					'strokeDashoffset': dashoffset
				});

				isAnimatedCircle = true;

			}, JetElements.prepareWaypointOptions( $scope, {
					offset: 'bottom-in-view'
			} ) );

			$( window ).on( 'resize.jetCircleProgress orientationchange.jetCircleProgress', JetElementsTools.debounce( 50, function() {
				currentDeviceMode = elementorFrontend.getCurrentDeviceMode();

				if ( breakpointsSettings[currentDeviceMode] ) {
					updateSvgSizes( breakpointsSettings[currentDeviceMode]['size'],
										breakpointsSettings[currentDeviceMode]['viewBox'],
										breakpointsSettings[currentDeviceMode]['center'],
										breakpointsSettings[currentDeviceMode]['radius'],
										breakpointsSettings[currentDeviceMode]['valStroke'],
										breakpointsSettings[currentDeviceMode]['bgStroke'],
										breakpointsSettings[currentDeviceMode]['circumference']
					);
				}
			} ) );

			function breakpointSizes( breakpointName, breakpointSize, breakpointStrokeValue, breakpointStrokeBg) {
				var max,
					radius;

				breakpointsSettings[breakpointName]['size']          = breakpointSize;
				breakpointsSettings[breakpointName]['viewBox']       = `0 0 ${breakpointSize} ${breakpointSize}`;
				breakpointsSettings[breakpointName]['center']        = breakpointSize / 2;
				radius                                               = breakpointSize / 2;
				max                                                  = ( breakpointStrokeValue >= breakpointStrokeBg ) ? breakpointStrokeValue : breakpointStrokeBg;
				breakpointsSettings[breakpointName]['radius']        = radius - ( max / 2 );
				breakpointsSettings[breakpointName]['circumference'] = 2 * Math.PI * breakpointsSettings[breakpointName]['radius'];
				breakpointsSettings[breakpointName]['valStroke']     = breakpointStrokeValue;
				breakpointsSettings[breakpointName]['bgStroke']      = breakpointStrokeBg;
			}

			function updateSvgSizes( size, viewBox, center, radius, valStroke, bgStroke, circumference ) {
				var dashoffset = circumference * (1 - progress);

				$progress.attr( {
					'width': size,
					'height': size,
					'data-radius': radius,
					'data-circumference': circumference
				} );

				$progress[0].setAttribute( 'viewBox', viewBox );

				$meter.attr( {
					'cx': center,
					'cy': center,
					'r': radius,
					'stroke-width': bgStroke
				} );

				if ( isAnimatedCircle ) {
					$value.css( {
						'transitionDuration': ''
					} );
				}

				$value.attr( {
					'cx': center,
					'cy': center,
					'r': radius,
					'stroke-width': valStroke
				} );

				$value.css( {
					'strokeDasharray': circumference,
					'strokeDashoffset': isAnimatedCircle ? dashoffset : circumference
				} );
			}
		},

		widgetCarousel: function( $scope ) {

			var $carousel    = $scope.find( '.jet-carousel' ),
				fraction_nav = $carousel.find('.jet-carousel__fraction-navigation');

			if ( ! $carousel.length ) {
				return;
			}

			if ( true === $carousel.data( 'slider_options' ).fractionNav ) {
				$carousel.find( '.elementor-slick-slider' ).on( 'init reInit afterChange', function ( event, slick, currentSlide, nextSlide ) {
					//currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
					var i = ( currentSlide ? currentSlide : 0 ) + 1;
					fraction_nav.html( '<span class="current">' + i + '</span>' + '<span class="separator">/</span>' + '<span class="total">' + slick.slideCount + '</span>');
				} );
			}

			JetElements.initCarousel( $carousel.find( '.elementor-slick-slider' ), $carousel.data( 'slider_options' ) );

		},

		widgetPosts: function ( $scope ) {

			var $target  = $scope.find( '.jet-carousel' ),
				settings = $target.data( 'slider_options' );

			if ( ! $target.length ) {
				return;
			}

			settings['slide'] = '.jet-posts__item';

			JetElements.initCarousel( $target.find( '.jet-posts' ), settings );

		},

		widgetAnimatedText: function( $scope ) {
			var $target = $scope.find( '.jet-animated-text' ),
				instance = null,
				settings = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' );
			instance = new jetAnimatedText( $target, settings );
			instance.init();
		},

		widgetAnimatedBox: function( $scope ) {

			JetElements.onAnimatedBoxSectionActivated( $scope );

			var $target         = $scope.find( '.jet-animated-box' ),
				defaultSettings = {
					widgetId: null,
					switchEventType: 'hover',
					paperFoldDirection: 'left',
					slideOutDirection: 'left'
				},
				settings        = $target.data( 'settings' ),
				settings        = $.extend( {}, defaultSettings, settings ),
				scrollOffset    = $( window ).scrollTop(),
				firstMouseEvent = true,
				editMode        = Boolean( elementor.isEditMode() );

			if ( ! $target.length ) {
				return;
			}

			switch( settings['switchEventType'] ) {
				case 'hover':
					if ( ! editMode ) {
						hoverSwitchType();
					} else {
						clickSwitchType();
					}

					break;

				case 'click':
					clickSwitchType();
					break;

				case 'toggle':
					toggleSwitchType();
					break;

				case 'scratch':
					scratchSwitchType();
					break;

				case 'fold':
					foldSwitchType();
					break;

				case 'peel':
					peelSwitchType();
					break;

				case 'slide-out':
					slideOutSwitchType();
					break;
			}

			function hoverSwitchType() {

				if ( 'ontouchend' in window || 'ontouchstart' in window ) {
					$target.on( 'touchstart', function( event ) {
						scrollOffset = $( window ).scrollTop();
					} );

					$target.on( 'touchend', function( event ) {

						if ( scrollOffset !== $( window ).scrollTop() ) {
							return false;
						}

						var $this = $( this );

						if ( $this.hasClass( 'flipped-stop' ) ) {
							return;
						}

						setTimeout( function() {
							$this.toggleClass( 'flipped' );
						}, 10 );
					} );

					$( document ).on( 'touchend', function( event ) {

						if ( $( event.target ).closest( $target ).length ) {
							return;
						}

						if ( $target.hasClass( 'flipped-stop' ) ) {
							return;
						}

						if ( ! $target.hasClass( 'flipped' ) ) {
							return;
						}

						$target.removeClass( 'flipped' );
					} );
				} else {

					$target.on( 'mouseenter mouseleave', function( event ) {

						if ( firstMouseEvent && 'mouseleave' === event.type ) {
							return;
						}

						if ( firstMouseEvent && 'mouseenter' === event.type ) {
							firstMouseEvent = false;
						}

						if ( ! $( this ).hasClass( 'flipped-stop' ) ) {
							$( this ).toggleClass( 'flipped' );
						}
					} );
				}
			}

			function clickSwitchType() {
				if ( 'ontouchend' in window || 'ontouchstart' in window ) {
					$target.on( 'touchstart', function( event ) {
						scrollOffset = $( window ).scrollTop();
					} );

					$target.on( 'touchend', function( event ) {

						if ( scrollOffset !== $( window ).scrollTop() ) {
							return false;
						}

						var $this = $( this );

						if ( $this.hasClass( 'flipped-stop' ) ) {
							return;
						}

						setTimeout( function() {
							$this.toggleClass( 'flipped' );
						}, 10 );
					} );

					$( document ).on( 'touchend', function( event ) {

						if ( $( event.target ).closest( $target ).length ) {
							return;
						}

						if ( $target.hasClass( 'flipped-stop' ) ) {
							return;
						}

						if ( ! $target.hasClass( 'flipped' ) ) {
							return;
						}

						$target.removeClass( 'flipped' );
					} );
				} else {
					$target.on( 'click', function( event ) {

						if ( ! $target.hasClass( 'flipped-stop' ) ) {
							$target.toggleClass( 'flipped' );
						}
					} );
				}
			}

			function toggleSwitchType() {
				if ( 'ontouchend' in window || 'ontouchstart' in window ) {
					$target.on( 'touchstart', '.jet-animated-box__toggle', function( event ) {

					if ( ! $target.hasClass( 'flipped-stop' ) ) {
						$target.toggleClass( 'flipped' );
					}
				} );
				} else {
					$target.on( 'click', '.jet-animated-box__toggle', function( event ) {

						if ( ! $target.hasClass( 'flipped-stop' ) ) {
							$target.toggleClass( 'flipped' );
						}
					} );
				}
			}

			function scratchSwitchType() {

				if ( editMode ) {
					return false;
				}

				var windowWidth = $( window ).width();

				$( 'html, body' ).scrollTop(0);

				html2canvas( document.querySelector( '#jet-animated-box__front-' + settings['widgetId'] ), {
					allowTaint: true,
					backgroundColor: null,
					windowWidth: $( window ).width(),
					windowHeight: $( window ).height(),
					scrollX: 0,
					scrollY: -window.scrollY,
				} ).then( function( canvas ) {
					canvas.setAttribute( 'id', 'jet-animated-box-canvas-' + settings['widgetId'] );
					$target.prepend( canvas );

					$( '.jet-animated-box__front', $target ).fadeOut( 300, function() {
						$( this ).remove();
					});

					$( window ).one( 'resize.jetScratch', function( e ) {

						if ( $( window ).width() !== windowWidth ) {
							windowWidth = $( window ).width();

							$( canvas ).fadeOut( 250, function() {
								$( this ).remove();
							});
						}

					} );

					var jetScratch = new jetScratchEffect(
						'#jet-animated-box-' + settings['widgetId'],
						'#jet-animated-box-canvas-' + settings['widgetId'],
						function() {
							$( canvas ).fadeOut( 300, function() {
								$( this ).remove();
								$target.removeClass( 'back-events-inactive' );
							} );
						},
						settings['scratchFillPercent']
					);
				} );
			}

			function foldSwitchType() {

				if ( editMode ) {
					$target.addClass( 'fold-init' );

					return false;
				}

				var folded        = null,
					frontSelector = '#jet-animated-box__front-' + settings['widgetId'];

				folded = new OriDomi( document.querySelector( frontSelector ), {
					vPanels:          5,
					hPanels:          5,
					speed:            500,
					ripple:           true,
					shadingIntensity: .9,
					perspective:      1000,
					//maxAngle:         90,
					shading:          false,
					gapNudge:          0,
					touchSensitivity: .25,
					touchMoveCallback: function( moveCoordinate, event ) {

						if ( 89.5 < moveCoordinate ) {
							$( frontSelector ).remove();
						}
					}
				}).accordion( 0, settings['paperFoldDirection'] );

				$target.addClass( 'fold-init' );
			}

			function peelSwitchType() {

				if ( editMode ) {
					$target.addClass( 'peel-ready' );

					return false;
				}

				var $front = $( '.jet-animated-box__front', $target ),
					$frontClone = $front.clone();

				$( '.jet-animated-box__front', $target ).addClass( 'peel-top' );

				$frontClone.removeAttr('id');
				$frontClone.addClass('peel-back');
				$frontClone.insertAfter( '#jet-animated-box__front-' + settings['widgetId'] );

				$( '.jet-animated-box__back', $target ).addClass( 'peel-bottom' );

				var peel = new Peel( '#jet-animated-box-' + settings['widgetId'], {
					corner: Peel.Corners.TOP_RIGHT
				} );

				var targetWidth = $target.width(),
					targetHeight = $target.height();

				peel.setPeelPosition( targetWidth - 30, 40 );

				peel.setFadeThreshold(.8);

				peel.handleDrag( function( evt, x, y ) {
					var targetOffset = $target.offset(),
						offsetX      = targetOffset.left,
						offsetY      = targetOffset.top,
						deltaX       = x - offsetX,
						deltaY       = y - offsetY;

					deltaX = deltaX < 0 ? deltaX*=3 : deltaX;
					deltaY = deltaY < 0 ? deltaY*=3 : deltaY;

					if ( 0.98 < this.getAmountClipped() ) {
						this.removeEvents();

						$( '.peel-top, .peel-back, .peel-bottom-shadow', $target ).remove();
					}

					peel.setPeelPosition( Math.round( deltaX ), Math.round( deltaY ) );

				});
			}

			function slideOutSwitchType() {

				var $frontSide    = $( '.jet-animated-box__front', $target ),
					$backSide     = $( '.jet-animated-box__back', $target ),
					$targetWidth  = $target.width(),
					$targetHeight = $target.height(),
					axis          = ( 'left' === settings.slideOutDirection || 'right' === settings.slideOutDirection ) ? 'x' : 'y';

				$frontSide.draggable( {
					axis: axis,
					drag: function( event, ui ) {
						var dragData = ui.position;
						switch( settings.slideOutDirection ) {
							case 'left':
								if ( dragData.left >= 0 ) {
									ui.position.left = 0;
								}
								break;
							case 'right':
								if ( dragData.left <= 0 ) {
									ui.position.left = 0;
								}
								break;
							case 'top':
								if ( dragData.top >= 0 ) {
									ui.position.top = 0;
								}
								break;
							case 'bottom':
								if ( dragData.top <= 0 ) {
									ui.position.top = 0;
								}
								break;
						}

					},
				} );

			}
		},

		onAnimatedBoxSectionActivated: function( $scope ) {
			if ( ! window.elementor ) {
				return;
			}

			if ( ! window.JetElementsEditor ) {
				return;
			}

			if ( ! window.JetElementsEditor.activeSection ) {
				return;
			}

			var section = window.JetElementsEditor.activeSection;
			var isBackSide = -1 !== [ 'section_back_content', 'section_action_button_style' ].indexOf( section );

			if ( isBackSide ) {
				$scope.find( '.jet-animated-box' ).addClass( 'flipped' );
				$scope.find( '.jet-animated-box' ).addClass( 'flipped-stop' );
			} else {
				$scope.find( '.jet-animated-box' ).removeClass( 'flipped' );
				$scope.find( '.jet-animated-box' ).removeClass( 'flipped-stop' );
			}
		},

		widgetImagesLayout: function( $scope ) {
			var $target = $scope.find( '.jet-images-layout' ),
				instance = null,
				settings = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' );
			instance = new jetImagesLayout( $target, settings );
			instance.init();
		},

		widgetPortfolio: function( $scope ) {
			var $target   = $scope.find( '.jet-portfolio' ),
				instance  = null,
				eSettings = JetElementsTools.getElementorElementSettings( $scope ),
				settings  = {
					id: $scope.data( 'id' )
				};

			if ( ! $target.length ) {
				return;
			}

			settings = $.extend( {}, settings, $target.data( 'settings' ), eSettings );
			instance = new jetPortfolio( $target, settings );
			instance.init();
		},

		widgetInstagramGallery: function( $scope ) {
			var $target         = $scope.find( '.jet-instagram-gallery__instance' ),
				instance        = null,
				defaultSettings = {},
				settings        = {};

			if ( ! $target.length ) {
				return;
			}

			settings = $target.data( 'settings' ),

			/*
			 * Default Settings
			 */
			defaultSettings = {
				layoutType: 'masonry',
			}
			/**
			 * Checking options, settings and options merging
			 */
			$.extend( defaultSettings, settings );

			if ( 'masonry' === settings.layoutType ) {
				salvattore.init();

				$( window ).on( 'resize orientationchange', function() {
					salvattore.rescanMediaQueries();
				} )
			}
		},

		widgetScrollNavigation: function( $scope ) {
			var $target         = $scope.find( '.jet-scroll-navigation' ),
				instance        = null,
				settings        = $target.data( 'settings' );

			instance = new jetScrollNavigation( $scope, $target, settings );
			instance.init();
		},

		widgetSubscribeForm: function( $scope ) {
			var $target               = $scope.find( '.jet-subscribe-form' ),
				scoreId               = $scope.data( 'id' ),
				settings              = $target.data( 'settings' ),
				jetSubscribeFormAjax  = null,
				subscribeFormAjax     = 'jet_subscribe_form_ajax',
				ajaxRequestSuccess    = false,
				$subscribeForm        = $( '.jet-subscribe-form__form', $target ),
				$fields               = $( '.jet-subscribe-form__fields', $target ),
				$mailField            = $( '.jet-subscribe-form__mail-field', $target ),
				$inputData            = $mailField.data( 'instance-data' ),
				$submitButton         = $( '.jet-subscribe-form__submit', $target ),
				$subscribeFormMessage = $( '.jet-subscribe-form__message', $target ),
				timeout               = null,
				invalidMailMessage    = window.jetElements.messages.invalidMail || 'Please specify a valid email';

			$mailField.on( 'focus', function() {
				$mailField.removeClass( 'mail-invalid' );
			} );

			$( document ).keydown( function( event ) {

				if ( 13 === event.keyCode && $mailField.is( ':focus' ) ) {
					subscribeHandle();

					return false;
				}
			} );

			$submitButton.on( 'click', function() {
				subscribeHandle();

				return false;
			} );

			function subscribeHandle() {
				var inputValue     = $mailField.val(),
					sendData       = {
						'email': inputValue,
						'use_target_list_id': settings['use_target_list_id'] || false,
						'target_list_id': settings['target_list_id'] || '',
						'data': $inputData
					},
					serializeArray = $subscribeForm.serializeArray(),
					additionalFields = {};

				if ( JetElementsTools.validateEmail( inputValue ) ) {

					$.each( serializeArray, function( key, fieldData ) {

						if ( 'email' === fieldData.name ) {
							sendData[ fieldData.name ] = fieldData.value;
						} else {
							additionalFields[ fieldData.name ] = fieldData.value;
						}
					} );

					sendData['additional'] = additionalFields;

					if ( ! ajaxRequestSuccess && jetSubscribeFormAjax ) {
						jetSubscribeFormAjax.abort();
					}

					jetSubscribeFormAjax = $.ajax( {
						type: 'POST',
						url: window.jetElements.ajaxUrl,
						data: {
							action: subscribeFormAjax,
							data: sendData
						},
						cache: false,
						beforeSend: function() {
							$submitButton.addClass( 'loading' );
							ajaxRequestSuccess = false;
						},
						success: function( data ){
							var successType   = data.type,
								message       = data.message || '',
								responceClass = 'jet-subscribe-form--response-' + successType;

							$submitButton.removeClass( 'loading' );
							ajaxRequestSuccess = true;

							$target.removeClass( 'jet-subscribe-form--response-error' );
							$target.addClass( responceClass );

							$( 'span', $subscribeFormMessage ).html( message );
							$subscribeFormMessage.css( { 'visibility': 'visible' } );

							timeout = setTimeout( function() {
								$subscribeFormMessage.css( { 'visibility': 'hidden' } );
								$target.removeClass( responceClass );
							}, 20000 );

							if ( settings['redirect'] ) {
								window.location.href = settings['redirect_url'];
							}

							$( window ).trigger( {
								type: 'jet-elements/subscribe',
								elementId: scoreId,
								successType: successType,
								inputData: $inputData
							} );
						}
					});

				} else {
					$mailField.addClass( 'mail-invalid' );

					$target.addClass( 'jet-subscribe-form--response-error' );
					$( 'span', $subscribeFormMessage ).html( invalidMailMessage );
					$subscribeFormMessage.css( { 'visibility': 'visible' } );

					timeout = setTimeout( function() {
						$target.removeClass( 'jet-subscribe-form--response-error' );
						$subscribeFormMessage.css( { 'visibility': 'hidden' } );
						$mailField.removeClass( 'mail-invalid' );
					}, 20000 );
				}
			}
		},

		widgetProgressBar: function( $scope ) {
			var $target      = $scope.find( '.jet-progress-bar' ),
				percent      = $target.data( 'percent' ),
				type         = $target.data( 'type' ),
				deltaPercent = percent * 0.01;

			elementorFrontend.waypoint( $target, function( direction ) {
				var $this       = $( this ),
					animeObject = { charged: 0 },
					$statusBar  = $( '.jet-progress-bar__status-bar', $this ),
					$percent    = $( '.jet-progress-bar__percent-value', $this ),
					animeProgress,
					animePercent;

				if ( 'type-7' == type ) {
					$statusBar.css( {
						'height': percent + '%'
					} );
				} else {
					$statusBar.css( {
						'width': percent + '%'
					} );
				}

				animePercent = anime({
					targets: animeObject,
					charged: percent,
					round: 1,
					duration: 1000,
					easing: 'easeInOutQuad',
					update: function() {
						$percent.html( animeObject.charged );
					}
				} );

			}, JetElements.prepareWaypointOptions( $scope ) );
		},

		widgetSlider: function( $scope ) {
			var $target         = $scope.find( '.jet-slider' ),
				$imagesTagList  = $( '.sp-image', $target ),
				item            = $( '.jet-slider__item', $target ),
				instance        = null,
				item_url        = '',
				item_url_target = '',
				defaultSettings = {
					imageScaleMode: 'cover',
					slideDistance: { size: 10, unit: 'px' },
					slideDuration: 500,
					sliderAutoplay: true,
					sliderAutoplayDelay: 2000,
					sliderAutoplayOnHover: 'pause',
					sliderFadeMode: false,
					sliderFullScreen: true,
					sliderFullscreenIcon: '',
					sliderHeight: { size: 600, unit: 'px' },
					sliderLoop: true,
					sliderNaviOnHover: false,
					sliderNavigation: true,
					sliderNavigationIcon: '',
					sliderPagination: false,
					sliderShuffle: false,
					sliderWidth: { size: 100, unit: '%' },
					thumbnailWidth: 120,
					thumbnailHeight: 80,
					thumbnails: true,
					rightToLeft: false,
				},
				instanceSettings    = $target.data( 'settings' ) || {},
				breakpoints         = JetElementsTools.getElementorElementSettings( $scope ),
				breakpointsSettings = {},
				defaultHeight,
				defaultThumbHeight,
				defaultThumbWidth,
				activeBreakpoints   = elementor.config.responsive.activeBreakpoints,
				settings            = $.extend( {}, defaultSettings, instanceSettings ),
				fraction_nav        = $target.find( '.jet-slider__fraction-pagination' );

			if ( ! $target.length ) {
				return;
			}

			item.each( function() {
				var _this = $( this ).find( '.jet-slider__content' );

				if ( _this.data( 'slide-url' ) && !elementor.isEditMode() ) {
					let clickXPos,
						clickYPos;

					_this.on( 'mousedown touchstart', function( e ) {
						window.XPos = e.pageX || e.originalEvent.changedTouches[0].pageX;
						window.YPos = e.pageY || e.originalEvent.changedTouches[0].pageY;
					} )

					_this.on( 'mouseup touchend', function( e ) {
						item_url        = _this.data( 'slide-url' );
						item_url_target = _this.data( 'slide-url-target' );
						clickXPos       = e.pageX || e.originalEvent.changedTouches[0].pageX;
						clickYPos       = e.pageY || e.originalEvent.changedTouches[0].pageY;

						if ( window.XPos === clickXPos && window.YPos === clickYPos ) {
							if ( '_blank' === item_url_target ) {
								window.open( item_url );
								return;
							} else {
								window.location = item_url;
							}
						}
					} );
				}
			} );

			defaultHeight = ( breakpoints['slider_height'] && '' != breakpoints['slider_height']['size'] ) ? breakpoints['slider_height']['size'] + breakpoints['slider_height']['unit'] : '600px';

			defaultThumbHeight = ( breakpoints['thumbnail_height'] && '' != breakpoints['thumbnail_height']['size'] ) ? breakpoints['thumbnail_height']['size'] : 80;

			defaultThumbWidth  = ( breakpoints['thumbnail_width'] && '' != breakpoints['thumbnail_width']['size'] ) ? breakpoints['thumbnail_width']['size'] : 120;

			Object.keys( activeBreakpoints ).forEach( function( breakpointName ) {

				if ( 'widescreen' === breakpointName ) {
					var breakpoint = activeBreakpoints[breakpointName].value - 1,

						breakpointHeight = '' != breakpoints['slider_height_' + breakpointName]['size'] ? breakpoints['slider_height_' + breakpointName]['size'] + breakpoints['slider_height_' + breakpointName]['unit'] : defaultHeight,

						breakpointThumbHeight = '' != breakpoints['thumbnail_height_' + breakpointName] ? breakpoints['thumbnail_height_' + breakpointName] : defaultThumbHeight,

						breakpointThumbWidth  = '' != breakpoints['thumbnail_width_' + breakpointName] ? breakpoints['thumbnail_width_' + breakpointName] : defaultThumbWidth,

						desktopHeight      = '' != breakpoints['slider_height']['size'] ? breakpoints['slider_height']['size'] + breakpoints['slider_height']['unit'] : settings['sliderHeight']['size'] + settings['sliderHeight']['unit'],

						desktopThumbHeight = '' != breakpoints['thumbnail_height'] ? breakpoints['thumbnail_height'] : settings['thumbnailHeight'],

						desktopThumbWidth  = '' != breakpoints['thumbnail_width'] ? breakpoints['thumbnail_width'] : settings['thumbnailWidth'];

					if ( breakpointHeight || breakpointThumbHeight || breakpointThumbWidth ) {
						breakpointsSettings[breakpoint] = {};
					} else {
						return;
					}

					if ( breakpointHeight ) {
						defaultHeight = breakpointHeight;

						breakpointsSettings[breakpoint]['height'] = desktopHeight;
					}

					if ( breakpointThumbHeight ) {
						defaultThumbHeight = breakpointThumbHeight;

						breakpointsSettings[breakpoint]['thumbnailHeight'] = desktopThumbHeight;
					}

					if ( breakpointThumbWidth ) {
						defaultThumbWidth = breakpointThumbWidth;

						breakpointsSettings[breakpoint]['thumbnailWidth'] = desktopThumbWidth;
					}

				} else {
					var breakpoint = activeBreakpoints[breakpointName].value - 1,

						breakpointThumbHeight = breakpoints['thumbnail_height_' + breakpointName] ? breakpoints['thumbnail_height_' + breakpointName] : false,
						breakpointThumbWidth  = breakpoints['thumbnail_width_' + breakpointName] ? breakpoints['thumbnail_width_' + breakpointName] : false;

						breakpointHeight = breakpoints['slider_height_' + breakpointName]['size'] ? breakpoints['slider_height_' + breakpointName]['size'] + breakpoints['slider_height_' + breakpointName]['unit'] : false;

					if ( breakpointHeight || breakpointThumbHeight || breakpointThumbWidth ) {
						breakpointsSettings[breakpoint] = {};
					} else {
						return;
					}

					if ( breakpointHeight ) {
						breakpointsSettings[breakpoint]['height'] = breakpointHeight;
					}

					if ( breakpointThumbHeight ) {
						breakpointsSettings[breakpoint]['thumbnailHeight'] = breakpointThumbHeight;
					}

					if ( breakpointThumbWidth ) {
						breakpointsSettings[breakpoint]['thumbnailWidth'] = breakpointThumbWidth;
					}
				}
			} );

			$( '.slider-pro', $target ).sliderPro( {
				width: settings['sliderWidth']['size'] + settings['sliderWidth']['unit'],
				height: defaultHeight,
				arrows: settings['sliderNavigation'],
				fadeArrows: settings['sliderNaviOnHover'],
				buttons: settings['sliderPagination'],
				autoplay: settings['sliderAutoplay'],
				autoplayDelay: settings['sliderAutoplayDelay'],
				autoplayOnHover: settings['sliderAutoplayOnHover'],
				fullScreen: settings['sliderFullScreen'],
				shuffle: settings['sliderShuffle'],
				loop: settings['sliderLoop'],
				fade: settings['sliderFadeMode'],
				slideDistance: ( 'string' !== typeof settings['slideDistance']['size'] ) ? settings['slideDistance']['size'] : 0,
				slideAnimationDuration: +settings['slideDuration'],
				//imageScaleMode: settings['imageScaleMode'],
				imageScaleMode: 'exact',
				waitForLayers: false,
				grabCursor: false,
				thumbnailWidth: defaultThumbWidth,
				thumbnailHeight: defaultThumbHeight,
				rightToLeft: settings['rightToLeft'],
				touchSwipe: settings['touchswipe'],
				init: function() {
					var fullscreenIconHtml = $( '.' + settings['sliderFullscreenIcon'] ).html(),
						arrowIconHtml      = $( '.' + settings['sliderNavigationIcon'] ).html();

					$( '.sp-full-screen-button', $target ).html( fullscreenIconHtml );
					$( '.sp-previous-arrow', $target ).html( arrowIconHtml );
					$( '.sp-next-arrow', $target ).html( arrowIconHtml );
					$( '.slider-pro', $target ).addClass( 'slider-loaded' );

					this.resize();
				},
				gotoSlideComplete: function() {
					if ( true === settings['fractionPag'] ) {
						var i = ( this.getSelectedSlide() ? this.getSelectedSlide() : 0 ) + 1;

						fraction_nav.html( '<span class="current">' + i + '</span>' + '<span class="separator">/</span>' + '<span class="total">' + this.getTotalSlides() + '</span>');
					}
				},
				update: function() {
					if ( true === settings['fractionPag'] ) {
						var i = ( this.getSelectedSlide() ? this.getSelectedSlide() : 0 ) + 1;

						fraction_nav.html( '<span class="current">' + i + '</span>' + '<span class="separator">/</span>' + '<span class="total">' + this.getTotalSlides() + '</span>');
					}
				},
				breakpoints: breakpointsSettings
			} );

			$( '.slider-pro', $target ).on( 'gotoSlide', function() {
				$target.find( '[data-element_type]' ).each( function() {
					window.elementorFrontend.hooks.doAction( 'frontend/element_ready/global', $( this ), $ );
				} );
			} );

		},

		widgetTestimonials: function( $scope ) {
			var $target        = $scope.find( '.jet-testimonials__instance' ),
				$imagesTagList = $( '.jet-testimonials__figure', $target ),
				targetContent  = $( '.jet-testimonials__content', $target ),
				instance       = null,
				settings       = $target.data( 'settings' ),
				ratingSettings = $target.data( 'rating-settings' );

			if ( ! $target.length ) {
				return;
			}

			targetContent.each( function() {
				var ratingList = $( '.jet-testimonials__rating', this );

				if ( ratingList ) {
					var rating = ratingList.data('rating');

					ratingList.each( function() {

						$( 'i', this ).each( function( index ) {
							if ( index <= rating - 1 ) {
								var itemClass = $( this ).data( 'active-star' );
								$( this ).addClass( itemClass );
							} else {
								var itemClass = $( this ).data( 'star' );
								$( this ).addClass( itemClass );
							}
						} )
					} )
				}
			} )

			settings.adaptiveHeight = settings['adaptiveHeight'];

			settings['slide'] = '.jet-testimonials__item';

			JetElements.initCarousel( $target, settings );
		},

		widgetImageComparison: function( $scope ) {
			var $target              = $scope.find( '.jet-image-comparison__instance' ),
				instance             = null,
				imageComparisonItems = $( '.jet-image-comparison__container', $target ),
				settings             = $target.data( 'settings' ),
				elementId            = $scope.data( 'id' );

			if ( ! $target.length ) {
				return;
			}

			window.juxtapose.scanPage( '.jet-juxtapose' );

			settings.draggable = false;
			settings.infinite = false;
			//settings.adaptiveHeight = true;
			JetElements.initCarousel( $target, settings );
		},

		widgetTimeTable: function( $scope ) {

			var $mptt_shortcode_wrapper = $scope.find( '.mptt-shortcode-wrapper' );

			if ( ( typeof typenow ) !== 'undefined' ) {
				if ( pagenow === typenow ) {
					switch ( typenow ) {

						case 'mp-event':
							Registry._get( 'Event' ).init();
							break;

						case 'mp-column':
							Registry._get( 'Event' ).initDatePicker();
							Registry._get( 'Event' ).columnRadioBox();
							break;

						default:
							break;
					}
				}
			}

			if ( $mptt_shortcode_wrapper.length ) {

				Registry._get( 'Event' ).initTableData();
				Registry._get( 'Event' ).filterShortcodeEvents();
				Registry._get( 'Event' ).getFilterByHash();

				$mptt_shortcode_wrapper.show();
			}

			if ( $( '.upcoming-events-widget' ).length || $mptt_shortcode_wrapper.length ) {
				Registry._get( 'Event' ).setColorSettings();
			}
		},

		elementorSection: function( $scope ) {
			var $target   = $scope,
				instance  = null,
				editMode  = Boolean( elementor.isEditMode() );

				instance = new jetSectionParallax( $target );
				instance.init();
		},

		initCarousel: function( $target, options ) {

			var	defaultOptions,
				slickOptions,
				responsive        = [],
				eTarget           = $target.closest( '.elementor-widget' ),
				breakpoints       = JetElementsTools.getElementorElementSettings( eTarget ),
				activeBreakpoints = elementor.config.responsive.activeBreakpoints,
				prevDeviceToShowValue,
				prevDeviceToScrollValue,
				slidesCount,
				dotsEnable = options.dots;

			if ( $target.hasClass( 'jet-posts' ) && $target.parent().hasClass( 'jet-carousel' ) ) {
				function renameKeys( obj, newKeys ) {
					const keyValues = Object.keys( obj ).map( key => {
						const newKey = newKeys[key] || key;
						return { [newKey]: obj[key] };
					} );
					return Object.assign( {}, ...keyValues );
				}

				var newBreakpointsKeys = {
					columns: "slides_to_show",
					columns_widescreen: "slides_to_show_widescreen",
					columns_laptop: "slides_to_show_laptop",
					columns_tablet_extra: "slides_to_show_tablet_extra",
					columns_tablet: "slides_to_show_tablet",
					columns_mobile_extra: "slides_to_show_mobile_extra",
					columns_mobile: "slides_to_show_mobile"
				};

				breakpoints = renameKeys( breakpoints, newBreakpointsKeys );
			}

			options.slidesToShow = +breakpoints.slides_to_show;

			Object.keys( activeBreakpoints ).forEach( function( breakpointName ) {
				if ( 'widescreen' === breakpointName ) {
					options.slidesToShow = breakpoints.slides_to_show_widescreen ? +breakpoints.slides_to_show_widescreen : +breakpoints.slides_to_show;

					if ( breakpoints.slides_to_scroll_widescreen ) {
						options.slidesToScroll = '' != breakpoints.slides_to_scroll_widescreen ? +breakpoints.slides_to_scroll_widescreen : +breakpoints.slides_to_scroll;
					} else {
						options.slidesToScroll = 1;
					}
				}
			} );

			slidesCount = $( '> div', $target ).length;

			prevDeviceToShowValue   = options.slidesToShow;
			prevDeviceToScrollValue = options.slidesToScroll;

			Object.keys( activeBreakpoints ).reverse().forEach( function( breakpointName ) {

				if ( breakpoints['slides_to_show_' + breakpointName] || breakpoints['slides_to_scroll_' + breakpointName] ) {

					var breakpointSetting = {
						breakpoint: null,
						settings: {}
					}

					breakpointSetting.breakpoint = 'widescreen' != breakpointName ? activeBreakpoints[breakpointName].value : activeBreakpoints[breakpointName].value - 1;

					if ( 'widescreen' === breakpointName ) {
						breakpointSetting.settings.slidesToShow   = +breakpoints['slides_to_show'];
						breakpointSetting.settings.slidesToScroll = +breakpoints['slides_to_scroll'];
					} else {
						breakpointSetting.settings.slidesToShow = breakpoints['slides_to_show_' + breakpointName] ? +breakpoints['slides_to_show_' + breakpointName] : prevDeviceToShowValue;

						breakpointSetting.settings.slidesToScroll = breakpoints['slides_to_scroll_' + breakpointName] ? +breakpoints['slides_to_scroll_' + breakpointName] : prevDeviceToScrollValue;
					}

					$target.on( 'init reInit', function(event, slick, currentSlide, nextSlide ){
						if ( breakpointSetting.settings.slidesToShow === slick.slideCount ) {
							breakpointSetting.settings.dots = false;
						} else {
							if ( dotsEnable ) {
								breakpointSetting.settings.dots = true;
							}
						}
					} );

					prevDeviceToShowValue   = breakpointSetting.settings.slidesToShow;
					prevDeviceToScrollValue = breakpointSetting.settings.slidesToScroll

					responsive.push( breakpointSetting );
				}
			} );

			options.responsive = responsive;

			if ( options.slidesToShow === slidesCount ) {
				options.dots = false;
			}

			defaultOptions = {
				customPaging: function(slider, i) {
					return $( '<span />' ).text( i + 1 );
				},
				dotsClass: 'jet-slick-dots'
			};

			slickOptions = $.extend( {}, defaultOptions, options );

			$target.slick( slickOptions );
		},

		widgetTimeLine : function ( $scope ){
			var $target = $scope.find( '.jet-timeline' ),
				instance = null;

			if ( ! $target.length ) {
				return;
			}

			instance = new jetTimeLine( $target );
			instance.init();
		},

		widgetTable: function( $scope ) {
			var $target = $scope.find( '.jet-table' ),
				options = {
					cssHeader: 'jet-table-header-sort',
					cssAsc: 'jet-table-header-sort--up',
					cssDesc: 'jet-table-header-sort--down',
					initWidgets: false
				};

			if ( ! $target.length ) {
				return;
			}

			if ( $target.hasClass( 'jet-table--sorting' ) ) {
				$target.tablesorter( options );
			}
		},

		widgetDropbar: function( $scope ) {
			var $dropbar       = $scope.find( '.jet-dropbar' ),
				$dropbar_inner = $dropbar.find( '.jet-dropbar__inner' ),
				$btn           = $dropbar.find( '.jet-dropbar__button' ),
				$content       = $dropbar.find( '.jet-dropbar__content' ),
				settings       = $dropbar.data( 'settings' ) || {},
				mode           = settings['mode'] || 'hover',
				hide_delay     = +settings['hide_delay'] || 0,
				activeClass    = 'jet-dropbar-open',
				scrollOffset,
				timer;

			if ( 'click' === mode ) {
				$btn.on( 'click.jetDropbar', function( event ) {
					$dropbar.toggleClass( activeClass );
				} );
			} else {
				if ( 'ontouchstart' in window || 'ontouchend' in window ) {
					$btn.on( 'touchend.jetDropbar', function( event ) {
						if ( $( window ).scrollTop() !== scrollOffset ) {
							return;
						}

						$dropbar.toggleClass( activeClass );
					} );
				} else {
					$dropbar_inner.on( 'mouseenter.jetDropbar', function( event ) {
						clearTimeout( timer );
						$dropbar.addClass( activeClass );
					} );

					$dropbar_inner.on( 'mouseleave.jetDropbar', function( event ) {
						timer = setTimeout( function() {
							$dropbar.removeClass( activeClass );
						}, hide_delay );
					} );
				}
			}

			$( document ).on( 'touchstart.jetDropbar', function( event ) {
				scrollOffset = $( window ).scrollTop();
			} );

			$( document ).on( 'click.jetDropbar touchend.jetDropbar', function( event ) {

				if ( 'touchend' === event.type && $( window ).scrollTop() !== scrollOffset ) {
					return;
				}

				if ( $( event.target ).closest( $btn ).length || $( event.target ).closest( $content ).length ) {
					return;
				}

				if ( ! $dropbar.hasClass( activeClass ) ) {
					return;
				}

				$dropbar.removeClass( activeClass );
			} );
		},

		widgetVideo: function( $scope ) {
			var $video = $scope.find( '.jet-video' ),
				$iframe = $scope.find( '.jet-video-iframe' ),
				$videoPlaer = $scope.find( '.jet-video-player' ),
				$mejsPlaer = $scope.find( '.jet-video-mejs-player' ),
				mejsPlaerControls = $mejsPlaer.data( 'controls' ) || ['playpause', 'current', 'progress', 'duration', 'volume', 'fullscreen'],
				$overlay = $scope.find( '.jet-video__overlay' ),
				hasOverlay = $overlay.length > 0,
				settings = $video.data( 'settings' ) || {},
				lightbox = settings.lightbox || false,
				autoplay = settings.autoplay || false;

			if ( $overlay[0] ) {
				$overlay.on( 'click.jetVideo', function( event ) {

					if ( $videoPlaer[0] ) {
						$videoPlaer[0].play();

						$overlay.remove();
						hasOverlay = false;

						return;
					}

					if ( $iframe[0] ) {
						iframeStartPlay();
					}
				} );
			}

			if ( autoplay && $iframe[0] && $overlay[0] ) {
				iframeStartPlay();
			}

			function iframeStartPlay() {
				var lazyLoad = $iframe.data( 'lazy-load' );

				if ( lazyLoad ) {
					$iframe.attr( 'src', lazyLoad );
				}

				if ( ! autoplay ) {
					$iframe[0].src = $iframe[0].src.replace( '&autoplay=0', '&autoplay=1' );
				}

				$overlay.remove();
				hasOverlay = false;
			}

			if ( $videoPlaer[0] ) {
				$videoPlaer.on( 'play.jetVideo', function( event ) {
					if ( hasOverlay ) {
						$overlay.remove();
						hasOverlay = false;
					}
				} );
			}

			if ( $mejsPlaer[0] ) {
				$mejsPlaer.mediaelementplayer( {
					videoVolume: 'horizontal',
					hideVolumeOnTouchDevices: false,
					enableProgressTooltip: false,
					features: mejsPlaerControls,
					success: function( media ) {
						media.addEventListener( 'timeupdate', function( event ) {
							var $currentTime = $scope.find( '.mejs-time-current' ),
								inlineStyle  = $currentTime.attr( 'style' );

							if ( inlineStyle ) {
								var scaleX = inlineStyle.match(/scaleX\([0-9.]*\)/gi)[0].replace( 'scaleX(', '' ).replace( ')', '' );

								if ( scaleX ) {
									$currentTime.css( 'width', scaleX * 100 + '%' );
								}
							}
						}, false );
					}
				} );
			}
		},

		widgetAudio: function( $scope ) {
			var $wrapper    = $scope.find( '.jet-audio' ),
				$player     = $scope.find( '.jet-audio-player' ),
				settings    = $wrapper.data( 'audio-settings' ),
				unmuted     = 0,
				hasVolume   = false,
				startVolume;

			if ( ! $player[0] ) {
				return;
			}

			startVolume = settings['startVolume'] || 0.8;

			settings['controls'].map( function( control ) {
				if ( 'volume' === control ) {
					hasVolume = true;
				}
			} );

			$player.mediaelementplayer( {
				features: settings['controls'] || ['playpause', 'current', 'progress', 'duration', 'volume'],
				audioVolume: settings['audioVolume'] || 'horizontal',
				startVolume: startVolume,
				hideVolumeOnTouchDevices: settings['hideVolumeOnTouchDevices'],
				enableProgressTooltip: false,
				success: function( media ) {
					var muteBtn = $scope.find( '.mejs-button button' );

					media.addEventListener( 'timeupdate', function( event ) {
						var $currentTime = $scope.find( '.mejs-time-current' ),
							inlineStyle  = $currentTime.attr( 'style' );

						if ( inlineStyle ) {
							var scaleX = inlineStyle.match(/scaleX\([0-9.]*\)/gi)[0].replace( 'scaleX(', '' ).replace( ')', '' );

							if ( scaleX ) {
								$currentTime.css( 'width', scaleX * 100 + '%' );
							}
						}
					}, false );

					if ( hasVolume && 'yes' === settings['hasVolumeBar'] && !settings['hideVolumeOnTouchDevices']) {
						media.setVolume( startVolume );
						media.addEventListener( 'volumechange', function() {
							var volumeBar          = 'horizontal' === settings['audioVolume'] ? $scope.find( '.mejs-horizontal-volume-current' ) : $scope.find( '.mejs-volume-current' ),
								volumeValue        = 'horizontal' === settings['audioVolume'] ? parseInt( volumeBar[0].style.width, 10 ) / 100 : parseInt( volumeBar[0].style.height, 10 ) / 100,
								volumeBarTotal     = 'horizontal' === settings['audioVolume'] ? $scope.find( '.mejs-horizontal-volume-total' ) : $scope.find( '.mejs-volume-slider .mejs-volume-total' ),
								playBrn            = $scope.find( '.mejs-playpause-button' ),
								volumeCurrentValue = '';

							volumeBarTotal.on( 'click', function() {
								if ( 'horizontal' === settings['audioVolume'] ) {
									volumeCurrentValue = parseInt( $scope.find( '.mejs-horizontal-volume-total .mejs-horizontal-volume-current' )[0].style.width, 10 ) / 100;
								} else {
									volumeCurrentValue = parseInt( $scope.find( '.mejs-volume-slider .mejs-volume-total .mejs-volume-current' )[0].style.height, 10 ) / 100;
								}
							} )

							playBrn.on( 'click', function() {
								if ( '' !== volumeCurrentValue ) {
									media.setVolume( volumeCurrentValue );
								}
							} )

							muteBtn.on( 'click', function() {
								if ( ! media.muted ) {
									if ( 'yes' === settings['muted'] && 0 === unmuted && 0 === volumeValue ) {
										media.setVolume( startVolume );
										unmuted = 1;
									}
								}
							} )
						}, false );
					} else if ( hasVolume && !settings['hideVolumeOnTouchDevices'] ) {
						muteBtn.on( 'click', function() {
							media.setVolume( startVolume );
						} )
					}
				}
			} );
		},

		widgetHorizontalTimeline: function( $scope ) {
			var $timeline         = $scope.find( '.jet-hor-timeline' ),
				$timelineTrack    = $scope.find( '.jet-hor-timeline-track' ),
				$items            = $scope.find( '.jet-hor-timeline-item' ),
				$arrows           = $scope.find( '.jet-arrow' ),
				$nextArrow        = $scope.find( '.jet-next-arrow' ),
				$prevArrow        = $scope.find( '.jet-prev-arrow' ),
				settings          = $timeline.data( 'timeline-settings' ) || {},
				columns           = settings.column || {},
				slidesToScroll    = settings.slidesToScroll || {},
				firstMouseEvent   = true,
				currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
				prevDeviceMode    = currentDeviceMode,
				itemsCount        = $scope.find( '.jet-hor-timeline-list--middle .jet-hor-timeline-item' ).length,
				isRTL             = JetElementsTools.isRTL(),
				currentTransform  = 0,
				currentPosition   = 0,
				transform = {
					desktop: 100 / columns.desktop,
					tablet:  100 / columns.tablet,
					mobile:  100 / columns.mobile
				},
				maxPosition = {
					desktop: Math.max( 0, (itemsCount - columns.desktop) ),
					tablet:  Math.max( 0, (itemsCount - columns.tablet) ),
					mobile:  Math.max( 0, (itemsCount - columns.mobile) )
				};

			if ( 'ontouchstart' in window || 'ontouchend' in window ) {
				$items.on( 'touchend.jetHorTimeline', function( event ) {
					var itemId = $( this ).data( 'item-id' );

					$scope.find( '.elementor-repeater-item-' + itemId ).toggleClass( 'is-hover' );
				} );
			} else {
				$items.on( 'mouseenter.jetHorTimeline mouseleave.jetHorTimeline', function( event ) {

					if ( firstMouseEvent && 'mouseleave' === event.type ) {
						return;
					}

					if ( firstMouseEvent && 'mouseenter' === event.type ) {
						firstMouseEvent = false;
					}

					var itemId = $( this ).data( 'item-id' );

					$scope.find( '.elementor-repeater-item-' + itemId ).toggleClass( 'is-hover' );
				} );
			}

			// Set Line Position
			setLinePosition();
			$( window ).on( 'resize.jetHorTimeline orientationchange.jetHorTimeline', JetElementsTools.debounce( 50, setLinePosition ) );

			function setLinePosition() {
				var $line             = $scope.find( '.jet-hor-timeline__line' ),
					$firstPoint       = $scope.find( '.jet-hor-timeline-item__point-content:first' ),
					$lastPoint        = $scope.find( '.jet-hor-timeline-item__point-content:last' ),
					firstPointLeftPos = $firstPoint.position().left + parseInt( $firstPoint.css( 'marginLeft' ) ),
					lastPointLeftPos  = $lastPoint.position().left + parseInt( $lastPoint.css( 'marginLeft' ) ),
					pointWidth        = $firstPoint.outerWidth();

				$line.css( {
					'left': !isRTL ? ( firstPointLeftPos + pointWidth/2 ) : ( lastPointLeftPos + pointWidth/2 ),
					'width': Math.abs( lastPointLeftPos - firstPointLeftPos )
				} );

				// var $progressLine   = $scope.find( '.jet-hor-timeline__line-progress' ),
				// 	$lastActiveItem = $scope.find( '.jet-hor-timeline-list--middle .jet-hor-timeline-item.is-active:last' );
				//
				// if ( $lastActiveItem[0] ) {
				// 	var $lastActiveItemPointWrap = $lastActiveItem.find( '.jet-hor-timeline-item__point' ),
				// 		progressLineWidth        = $lastActiveItemPointWrap.position().left + $lastActiveItemPointWrap.outerWidth() - firstPointLeftPos - pointWidth / 2;
				//
				// 	$progressLine.css( {
				// 		'width': progressLineWidth
				// 	} );
				// }
			}

			// Arrows Navigation Type
			if ( $nextArrow[0] && maxPosition[ currentDeviceMode ] === 0 ) {
				$nextArrow.addClass( 'jet-arrow-disabled' );
			}

			if ( $arrows[0] ) {
				var slidesScroll      = slidesToScroll[ currentDeviceMode ],
					xPos              = 0,
					yPos              = 0,
					diffpos;

				$arrows.on( 'click.jetHorTimeline', function( event ){
					var $this             = $( this ),
						currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
						direction         = $this.hasClass( 'jet-next-arrow' ) ? 'next' : 'prev',
						dirMultiplier     = !isRTL ? -1 : 1;

					if ( slidesScroll > columns[ currentDeviceMode ] ) {
						slidesScroll = columns[ currentDeviceMode ];
					}

					if ( 'next' === direction && currentPosition < maxPosition[ currentDeviceMode ] ) {
						currentPosition += slidesScroll;

						if ( currentPosition > maxPosition[ currentDeviceMode ] ) {
							currentPosition = maxPosition[ currentDeviceMode ];
						}
					}

					if ( 'prev' === direction && currentPosition > 0 ) {
						currentPosition -= slidesScroll;

						if ( currentPosition < 0 ) {
							currentPosition = 0;
						}
					}

					if ( currentPosition > 0 ) {
						$prevArrow.removeClass( 'jet-arrow-disabled' );
					} else {
						$prevArrow.addClass( 'jet-arrow-disabled' );
					}

					if ( currentPosition === maxPosition[ currentDeviceMode ] ) {
						$nextArrow.addClass( 'jet-arrow-disabled' );
					} else {
						$nextArrow.removeClass( 'jet-arrow-disabled' );
					}

					if ( currentPosition === 0 ) {
						currentTransform = 0;
					} else {
						currentTransform = currentPosition * transform[ currentDeviceMode ];
					}

					$timelineTrack.css({
						'transform': 'translateX(' + dirMultiplier * currentTransform + '%)'
					});

				} );

				$( $items ).on( 'touchstart', function( e ) {
					var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];

					xPos = touch.pageX;
				} );

				$( $items ).on( 'touchend', function( e ) {
					var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];

					yPos    = touch.pageX;
					diffpos = yPos - xPos;

					if ( diffpos < -50 ) {
						var dirMultiplier     = !isRTL ? -1 : 1;

						if ( slidesScroll > columns[ currentDeviceMode ] ) {
							slidesScroll = columns[ currentDeviceMode ];
						}

						if ( currentPosition < maxPosition[ currentDeviceMode ] ) {
							currentPosition += slidesScroll;

							if ( currentPosition > maxPosition[ currentDeviceMode ] ) {
								currentPosition = maxPosition[ currentDeviceMode ];
							}
						}

						if ( currentPosition > 0 ) {
							$prevArrow.removeClass( 'jet-arrow-disabled' );
						} else {
							$prevArrow.addClass( 'jet-arrow-disabled' );
						}

						if ( currentPosition === maxPosition[ currentDeviceMode ] ) {
							$nextArrow.addClass( 'jet-arrow-disabled' );
						} else {
							$nextArrow.removeClass( 'jet-arrow-disabled' );
						}

						if ( currentPosition === 0 ) {
							currentTransform = 0;
						} else {
							currentTransform = currentPosition * transform[ currentDeviceMode ];
						}

						$timelineTrack.css( {
							'transform': 'translateX(' + dirMultiplier * currentTransform + '%)'
						} );

					} else if ( diffpos > 50 ) {
						var dirMultiplier     = !isRTL ? -1 : 1;

						if ( slidesScroll > columns[ currentDeviceMode ] ) {
							slidesScroll = columns[ currentDeviceMode ];
						}

						if ( currentPosition > 0 ) {
							currentPosition -= slidesScroll;

							if ( currentPosition < 0 ) {
								currentPosition = 0;
							}
						}

						if ( currentPosition > 0 ) {
							$prevArrow.removeClass( 'jet-arrow-disabled' );
						} else {
							$prevArrow.addClass( 'jet-arrow-disabled' );
						}

						if ( currentPosition === maxPosition[ currentDeviceMode ] ) {
							$nextArrow.addClass( 'jet-arrow-disabled' );
						} else {
							$nextArrow.removeClass( 'jet-arrow-disabled' );
						}

						if ( currentPosition === 0 ) {
							currentTransform = 0;
						} else {
							currentTransform = currentPosition * transform[ currentDeviceMode ];
						}

						$timelineTrack.css( {
							'transform': 'translateX(' + dirMultiplier * currentTransform + '%)'
						} );
					}
				} );
			}

			setArrowPosition();
			$( window ).on( 'resize.jetHorTimeline orientationchange.jetHorTimeline', setArrowPosition );
			$( window ).on( 'resize.jetHorTimeline orientationchange.jetHorTimeline', timelineSliderResizeHandler );

			function setArrowPosition() {
				if ( ! $arrows[0] ) {
					return;
				}

				var $middleList = $scope.find( '.jet-hor-timeline-list--middle' ),
					middleListTopPosition = $middleList.position().top,
					middleListHeight = $middleList.outerHeight();

				$arrows.css({
					'top': middleListTopPosition + middleListHeight/2
				});
			}

			function timelineSliderResizeHandler( event ) {
				if ( ! $timeline.hasClass( 'jet-hor-timeline--arrows-nav' ) ) {
					return;
				}

				var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
					resetSlider = function() {
						$prevArrow.addClass( 'jet-arrow-disabled' );

						if ( $nextArrow.hasClass( 'jet-arrow-disabled' ) ) {
							$nextArrow.removeClass( 'jet-arrow-disabled' );
						}

						if ( maxPosition[ currentDeviceMode ] === 0 ) {
							$nextArrow.addClass( 'jet-arrow-disabled' );
						}

						currentTransform = 0;
						currentPosition = 0;

						$timelineTrack.css({
							'transform': 'translateX(0%)'
						});
					};

				switch ( currentDeviceMode ) {
					case 'desktop':
						if ( 'desktop' !== prevDeviceMode ) {
							resetSlider();
							prevDeviceMode = 'desktop';
						}
						break;

					case 'tablet':
						if ( 'tablet' !== prevDeviceMode ) {
							resetSlider();
							prevDeviceMode = 'tablet';
						}
						break;

					case 'mobile':
						if ( 'mobile' !== prevDeviceMode ) {
							resetSlider();
							prevDeviceMode = 'mobile';
						}
						break;
				}
			}
		},

		widgetPieChart: function( $scope ) {
			var $container     = $scope.find( '.jet-pie-chart-container' ),
				$canvas        = $scope.find( '.jet-pie-chart' )[0],
				data           = $container.data( 'chart' ) || {},
				options        = $container.data( 'options' ) || {},
				tooltip        = $container.data( 'tooltip' ) || '',
				defaultOptions = {
					maintainAspectRatio: false
				};

			options = $.extend( {}, defaultOptions, options );

			if ( true === options.tooltips.enabled ) {
				options.tooltips.callbacks = {
					label: function( tooltipItem, data ) {
						return ' ' + data.labels[tooltipItem.index] + ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] + tooltip;
					}
				};
			}

			elementorFrontend.waypoint( $scope, function() {
				var chartInstance = new Chart( $canvas, {
					type:    'pie',
					data:    data,
					options: options
				} );
			}, JetElements.prepareWaypointOptions( $scope, {
				offset: 'bottom-in-view'
			} ) );
		},

		widgetBarChart: function( $scope ) {

			var $chart        = $scope.find( '.jet-bar-chart-container' ),
				$chart_canvas = $chart.find( '.jet-bar-chart' ),
				settings      = $chart.data( 'settings' ),
				tooltip_prefix    = $chart.data( 'tooltip-prefix' ) || '',
				tooltip_suffix    = $chart.data( 'tooltip-suffix' ) || '',
				tooltip_separator = $chart.data( 'tooltip-separator' ) || '';

				if ( true === settings.options.tooltips.enabled ) {
					settings.options.tooltips.callbacks = {
						label: function(tooltipItem, data) {
							return ' ' + data.labels[tooltipItem.index] + ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						}
					}
				}

			if ( ! $chart.length ) {
				return;
			}

			if ( true === settings.options.tooltips.enabled ) {
				settings.options.tooltips.callbacks = {
					label: function( tooltipItem, data ) {
						var value = '' != tooltip_separator ? JetElementsTools.addThousandCommaSeparator( data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index], tooltip_separator) : data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						return ' ' + data.labels[tooltipItem.index] + ': ' + tooltip_prefix + value + tooltip_suffix;
					}
				};
			}

			elementorFrontend.waypoint( $chart_canvas, function() {
				var $this   = $( this ),
					ctx     = $this[0].getContext( '2d' ),
					myChart = new Chart( ctx, settings );
			}, JetElements.prepareWaypointOptions( $scope, {
				offset: 'bottom-in-view'

			} ) );
		},
		widgetLineChart: function( $scope ) {
			var id                  = $scope.data( 'id' ),
				$line_chart         = $scope.find( '.jet-line-chart-container' ),
				$line_chart_canvas  = $line_chart.find( '.jet-line-chart' ),
				$compare            = $line_chart.data( 'compare' ),
				previous_label      = $line_chart.data( 'previous-label' ),
				current_label       = $line_chart.data( 'current-label' ),
				settings            = $line_chart.data( 'settings' ),
				compare_labels_type = $line_chart.data( 'compare-labels-type' ),
				tooltip_prefix      = $line_chart.data( 'tooltip-prefix' ) || '',
				tooltip_suffix      = $line_chart.data( 'tooltip-suffix' ) || '',
				tooltip_separator   = $line_chart.data( 'tooltip-separator' ) || '';

			if ( ! $line_chart.length ) {
				return;
			}

			elementorFrontend.waypoint( $line_chart_canvas, function() {

				var $this   = $( this ),
					ctx     = $this[0].getContext( '2d' ),
					myLineChart = new Chart( ctx, settings );

					myLineChart.options.tooltips = {
						enabled:   false,
						mode:      'x-axis',
						intersect: false,
						callbacks: {
							label: function( tooltipItem, data ) {
								var colorBox = data.datasets[tooltipItem.datasetIndex].borderColor;
								colorBox = colorBox.replace( /"/g, '"' );

								if ( true === $compare ) {
									var currentLabel    = 'custom' === compare_labels_type ? current_label : data.labels[tooltipItem.index],
										title           = data.datasets[tooltipItem.datasetIndex].label,
										currentVal      = '' != tooltip_separator ? JetElementsTools.addThousandCommaSeparator( data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index], tooltip_separator ) : data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index],
										current         = '<div class="jet-line-chart-tooltip-compare-current">' + currentLabel + ' : ' + tooltip_prefix + currentVal + tooltip_suffix + '</div>',
										previous        = '',
										compareColorBox = data.datasets[tooltipItem.datasetIndex].borderColor,
										compareColorBox = compareColorBox.replace( /"/g, '"' );

									if ( typeof (data.labels[tooltipItem.index - 1]) != "undefined" && data.labels[tooltipItem.index - 1] !== null ) {
										var previousLabel = 'custom' === compare_labels_type ? previous_label : data.labels[tooltipItem.index - 1],
											previousVal   = '' != tooltip_separator ? JetElementsTools.addThousandCommaSeparator( data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index - 1], tooltip_separator ) : data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index - 1],
											previous      = '<div class="jet-line-chart-tooltip-compare-previous">' + previousLabel + ' : ' + tooltip_prefix + previousVal + tooltip_suffix + '</div>';
									}

									return '<div class="jet-line-chart-tooltip-title"><span class="jet-line-chart-tooltip-color-box" style="background:' + compareColorBox + '"></span>' + title + '</div><div class="jet-line-chart-tooltip-body">' + current + previous + '</div>';
								} else {
									var label = data.datasets[tooltipItem.datasetIndex].label,
										val   = '' != tooltip_separator ? JetElementsTools.addThousandCommaSeparator( data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index], tooltip_separator ) : data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
									return '<div class="jet-line-chart-tooltip-body"><span class="jet-line-chart-tooltip-color-box" style="background:' + colorBox + '"></span>' + label + ' : ' + tooltip_prefix + val + tooltip_suffix + '</div>';
								}
							},
						},
						custom: function( tooltipModel ) {
							// Tooltip Element
							var tooltipEl = document.getElementById( 'chartjs-tooltip_' + id );

							// Create element on first render
							if ( !tooltipEl ) {
								tooltipEl = document.createElement( 'div' );
								tooltipEl.id = 'chartjs-tooltip_' + id;
								tooltipEl.innerHTML = '<div class="jet-line-chart-tooltip"></div>';
								document.body.appendChild( tooltipEl );
							}

							// Hide if no tooltip
							if ( tooltipModel.opacity === 0 ) {
								tooltipEl.style.opacity = 0;
								return;
							}
							// Set caret Position
							tooltipEl.classList.remove( 'above', 'below', 'no-transform' );
							if ( tooltipModel.yAlign ) {
								tooltipEl.classList.add( tooltipModel.yAlign );
							} else {
								tooltipEl.classList.add( 'no-transform' );
							}

							function getBody( bodyItem ) {
								return bodyItem.lines;
							}

							// Set Text
							if ( tooltipModel.body ) {
								var titleLines = tooltipModel.title || [];
								var bodyLines = tooltipModel.body.map( getBody );
								var innerHtml = '';

								innerHtml += '<div class="jet-line-chart-tooltip-wrapper">';
								bodyLines.forEach( function( body, i ) {
									innerHtml += body;
								} );
								innerHtml += '</div>';

								var tableRoot = tooltipEl.querySelector( 'div' );
								tableRoot.innerHTML = innerHtml;
							}

							// `this` will be the overall tooltip

							var _this         = this,
								position      = this._chart.canvas.getBoundingClientRect(),
								tooltipWidth  = tooltipEl.offsetWidth,
								tooltipHeight = tooltipEl.offsetHeight,
								offsetX       = 0,
								offsetY       = 0;

							setTimeout( function(){
								tooltipWidth = tooltipEl.offsetWidth;
								tooltipHeight = tooltipEl.offsetHeight;

								if ( _this._chart.width / 2 > _this._chart.tooltip._eventPosition.x ) {
									offsetX = 0;
								} else {
									offsetX = -tooltipWidth;
								}

								if ( _this._chart.height / 2 > _this._chart.tooltip._eventPosition.y ) {
									offsetY = 0;
								} else {
									offsetY = -tooltipHeight;
								}
								tooltipEl.style.left = position.left + window.pageXOffset + tooltipModel.caretX + offsetX + 'px';
								tooltipEl.style.top = position.top + window.pageYOffset + tooltipModel.caretY + offsetY + 'px';
								tooltipEl.style.opacity = 1;
							}, 10 );

							// Display, position, and set styles for font
							tooltipEl.style.position = 'absolute';
							tooltipEl.style.fontFamily = tooltipModel._bodyFontFamily;
							tooltipEl.style.fontSize = tooltipModel.bodyFontSize + 'px';
							tooltipEl.style.fontStyle = tooltipModel._bodyFontStyle;
							tooltipEl.style.padding = tooltipModel.yPadding + 'px ' + tooltipModel.xPadding + 'px';
							tooltipEl.style.pointerEvents = 'none';
						},
					};
					myLineChart.update();
			}, JetElements.prepareWaypointOptions( $scope, {
				offset: 'bottom-in-view'
			} ) );
		},

		widgetLottie: function ( $scope ) {
			var $lottie     = $scope.find( '.jet-lottie' ),
				$lottieElem = $lottie.find( '.jet-lottie__elem' ),
				settings    = $lottie.data( 'settings' ),
				options,
				instance;

			if ( ! $lottie[0] ) {
				return;
			}

			options = {
				container: $lottieElem[0],
				renderer:  settings.renderer,
				loop:      '' === settings.loop_times ? settings.loop : settings.loop_times,
				autoplay:  false,
				path:      settings.path,
				name:      'jet-lottie'
			};

			instance = lottie.loadAnimation( options );

			if ( settings.play_speed ) {
				instance.setSpeed( settings.play_speed );
			}

			if ( settings.reversed ) {
				instance.setDirection( -1 );
			}

			var start = 0,
				end = 0;

			if ( settings.viewport ) {
				start = -settings.viewport.start || 0;
				end = -(100 - settings.viewport.end) || 0;
			}

			switch( settings.action_start ) {
				case 'on_hover':

					var startFlag = false,

						onHoverHandler = function() {
							if ( startFlag && 'reverse' === settings.on_hover_out ) {
								var reverseValue = settings.reversed ? -1 : 1;
								instance.setDirection( reverseValue );
							}

							instance.play();
							startFlag = true;
						},

						onHoverOutHandler = function() {
							switch ( settings.on_hover_out ) {
								case 'pause':
									instance.pause();
									break;

								case 'stop':
									instance.stop();
									break;

								case 'reverse':
									var reverseValue = settings.reversed ? 1 : -1;
									instance.setDirection( reverseValue );
									instance.play();
							}
						};

					$lottie
						.off( 'mouseenter.JetLottie', onHoverHandler )
						.on( 'mouseenter.JetLottie', onHoverHandler );

					$lottie
						.off( 'mouseleave.JetLottie', onHoverOutHandler )
						.on( 'mouseleave.JetLottie', onHoverOutHandler );

					break;

				case 'on_click':

					var $link = $lottie.find( '.jet-lottie__link' ),
						redirectTimeout = +settings.redirect_timeout,

						onClickHandler = function() {
							instance.play();
						},

						onLinkClickHandler = function( event ) {

							event.preventDefault();

							instance.play();

							var url = $( this ).attr( 'href' ),
								target = '_blank' === $( this ).attr( 'target' ) ? '_blank' : '_self';

							setTimeout( function() {
								window.open( url, target );
							}, redirectTimeout );
						};

					if ( $link[0] && redirectTimeout > 0 ) {

						$link
							.off( 'click.JetLottie', onLinkClickHandler )
							.on( 'click.JetLottie', onLinkClickHandler );

					} else {
						$lottie
							.off( 'click.JetLottie', onClickHandler )
							.on( 'click.JetLottie', onClickHandler );
					}

					break;

				case 'on_viewport':

					if ( undefined !== window.IntersectionObserver ) {
						var observer = new IntersectionObserver(
							function( entries, observer ) {
								if ( entries[0].isIntersecting ) {
									instance.play();
								} else {
									instance.pause();
								}
							},
							{
								rootMargin: end +'% 0% ' + start + '%'
							}
						);

						observer.observe( $lottie[0] );
					} else {
						instance.play();
					}

					break;

				case 'on_scroll':

					if ( undefined !== window.IntersectionObserver ) {
						var scrollY = 0,
							requestId,
							scrollObserver = new IntersectionObserver(
								function( entries, observer ) {
									if ( entries[0].isIntersecting ) {

										requestId = requestAnimationFrame( function scrollAnimation() {
											if ( window.scrollY !== scrollY ) {
												var viewportPercentage = JetElementsTools.getElementPercentageSeen( $lottieElem, { start: start, end: end } ),
													nextFrame = (instance.totalFrames - instance.firstFrame) * viewportPercentage / 100;

												instance.goToAndStop( nextFrame, true );
												scrollY = window.scrollY;
											}

											requestId = requestAnimationFrame( scrollAnimation );
										} );
									} else {
										cancelAnimationFrame(requestId);
									}
								},
								{
									rootMargin: end +'% 0% ' + start + '%'
								}
							);

						scrollObserver.observe( $lottie[0] );
					}

					break;

				default:
					var delay = +settings.delay;

					if ( delay > 0 ) {

						setTimeout( function() {
							instance.play();
						}, delay );

					} else {
						instance.play();
					}
			}

		},

		widgetPricingTable: function( $scope ) {
			var $target         = $scope.find( '.pricing-table' ),
				$tooltips       = $( '.pricing-feature .pricing-feature__inner[data-tippy-content]', $target ),
				settings        = $target.data( 'tooltips-settings' ),
				$fold_target    = $scope.find( '.pricing-table__fold-mask' ),
				$fold_button    = $scope.find( '.pricing-table__fold-button' ),
				$fold_mask      = $fold_target,
				$fold_content   = $( '.pricing-table__features', $fold_target ),
				fold_settings   = $fold_target.data( 'fold-settings' ) || {},
				fold_enabled    = fold_settings['fold_enabled'] || false,
				fold_maskHeight = 0,
				contentHeight   = 0,
				unfoldDuration  = fold_settings['unfoldDuration'],
				foldDuration    = fold_settings['unfoldDuration'],
				unfoldEasing    = fold_settings['unfoldEasing'],
				foldEasing      = fold_settings['foldEasing'];

			if ( $tooltips[0] ) {
				$tooltips.each( function() {
					var $this        = $( this ),
						itemSelector = $this[0];

					if ( itemSelector._tippy ) {
						itemSelector._tippy.destroy();
					}

					tippy( [ itemSelector ], {
						arrow: settings['tooltipArrow'] ? true : false,
						duration: [ settings['tooltipShowDuration']['size'], settings['tooltipHideDuration']['size'] ],
						delay: settings['tooltipDelay']['size'],
						placement: settings['tooltipPlacement'],
						trigger: settings['tooltipTrigger'],
						animation: settings['tooltipAnimation'],
						appendTo: itemSelector,
						offset: [ 0, settings['tooltipDistance']['size'] ],
						allowHTML: true,
						popperOptions: {
							strategy: 'fixed',
						},
					} );

				} );
			}

			function maskHeight(){
				$scope.find( '.pricing-table__fold-mask .fold_visible' ).each( function() {
					fold_maskHeight = fold_maskHeight + $( this ).outerHeight( true );
				} );
			}

			function fold_content_height(){
				contentHeight = 0;
				$scope.find( '.pricing-table__fold-mask .pricing-feature' ).each( function() {
					contentHeight = contentHeight + $( this ).outerHeight( true );
				} )
			}

			if ( fold_enabled ) {

				maskHeight();
				fold_content_height();

				if ( !$fold_target.hasClass( 'pricing-table-unfold-state' ) ) {
					$fold_mask.css( {
						'height': fold_maskHeight
					} );
				}

				$scope.find( '.pricing-table__fold-mask' ).css('max-height', 'none');

				$fold_button.on( 'click.jetPricingTable', function() {
					var $this         = $( this ),
						$buttonText   = $( '.pricing-table__fold-button-text', $this ),
						$buttonIcon   = $( '.pricing-table__fold-button-icon', $this ),
						unfoldText    = $this.data( 'unfold-text' ),
						foldText      = $this.data( 'fold-text' ),
						unfoldIcon    = $this.data( 'unfold-icon' ),
						foldIcon      = $this.data( 'fold-icon' );

					if ( ! $fold_target.hasClass( 'pricing-table-unfold-state' ) ) {
						$fold_target.addClass( 'pricing-table-unfold-state' );

						fold_content_height();

						$buttonIcon.html( foldIcon );
						$buttonText.html( foldText );

						anime( {
							targets:  $fold_mask[0],
							height:   contentHeight,
							duration: unfoldDuration['size'] || unfoldDuration,
							easing:   unfoldEasing
						} );
					} else {
						$fold_target.removeClass( 'pricing-table-unfold-state' );

						$buttonIcon.html( unfoldIcon );
						$buttonText.html( unfoldText );

						anime( {
							targets:  $fold_mask[0],
							height:   fold_maskHeight,
							duration: foldDuration['size'] || foldDuration,
							easing:   foldEasing
						} );
					}
				} );
			}
		}
	};

	$( window ).on( 'elementor/frontend/init', JetElements.init );

	var JetElementsTools = {

		getElementPercentageSeen: function( $element, offset ) {
			var offsetSettings      = offset || {},
				startOffset         = offsetSettings.start || 0,
				endOffset           = offsetSettings.end || 0,
				viewportHeight      = $( window ).height(),
				viewportStartOffset = viewportHeight * startOffset / 100,
				viewportEndOffset   = viewportHeight * endOffset / 100,
				scrollTop           = $( window ).scrollTop(),
				elementOffsetTop    = $element.offset().top,
				elementHeight       = $element.height(),
				percentage;

			percentage = (scrollTop + viewportHeight + viewportStartOffset - elementOffsetTop) / (viewportHeight + viewportStartOffset + viewportEndOffset + elementHeight);
			percentage = Math.min( 100, Math.max( 0, percentage * 100 ) );

			return parseFloat( percentage.toFixed( 2 ) );
		},

		isRTL: function() {
			return $( 'body' ).hasClass( 'rtl' );
		},

		inArray: function( needle, haystack ) {
			return -1 < haystack.indexOf( needle );
		},

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

		getObjectNextKey: function( object, key ) {
			var keys      = Object.keys( object ),
				idIndex   = keys.indexOf( key ),
				nextIndex = idIndex += 1;

			if( nextIndex >= keys.length ) {
				//we're at the end, there is no next
				return false;
			}

			var nextKey = keys[ nextIndex ];

			return nextKey;
		},

		getObjectPrevKey: function( object, key ) {
			var keys      = Object.keys( object ),
				idIndex   = keys.indexOf( key ),
				prevIndex = idIndex -= 1;

			if ( 0 > idIndex ) {
				//we're at the end, there is no next
				return false;
			}

			var prevKey = keys[ prevIndex ];

			return prevKey;
		},

		getObjectFirstKey: function( object ) {
			return Object.keys( object )[0];
		},

		getObjectLastKey: function( object ) {
			return Object.keys( object )[ Object.keys( object ).length - 1 ];
		},

		getObjectValues: function( object ) {
			var values;

			if ( !Object.values ) {
				values = Object.keys( object ).map( function( e ) {
					return object[e]
				} );

				return values;
			}

			return Object.values( object );
		},

		validateEmail: function( email ) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			return re.test( email );
		},

		mobileAndTabletcheck: function() {
			var check = false;

			(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);

			return check;
		},

		addThousandCommaSeparator: function ( nStr, separator ) {
			var nStr = nStr + '',
				x    = nStr.split('.'),
				x1   = x[0],
				x2   = x.length > 1 ? '.' + x[1] : '',
				rgx  = /(\d+)(\d{3})/;

			while ( rgx.test(x1) ) {
				x1 = x1.replace(rgx, '$1' + separator + '$2');
			}

			return x1 + x2;
		},

		getElementorElementSettings: function( $scope ) {

			if ( window.elementorFrontend && window.elementorFrontend.isEditMode() && $scope.hasClass( 'elementor-element-edit-mode' ) ) {
				return JetElementsTools.getEditorElementSettings( $scope );
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
		}
	}

	/**
	 * Jet animated text Class
	 *
	 * @return {void}
	 */
	window.jetAnimatedText = function( $selector, settings ) {
		var self                   = this,
			$instance              = $selector,
			$animatedTextContainer = $( '.jet-animated-text__animated-text', $instance ),
			$animatedTextList      = $( '.jet-animated-text__animated-text-item', $animatedTextContainer ),
			timeOut                = null,
			defaultSettings        = {
				effect: 'fx1',
				delay: 3000
			},
			settings               =  $.extend( defaultSettings, settings || {} ),
			currentIndex           = 0,
			animationDelay         = settings.delay;

		/**
		 * Avaliable Effects
		 */
		self.avaliableEffects = {
			'fx1' : {
				in: {
					duration: 1000,
					delay: function( el, index ) { return 75 + index * 100; },
					easing: 'easeOutElastic',
					elasticity: 650,
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: ['100%','0%']
				},
				out: {
					duration: 300,
					delay: function(el, index) { return index*40; },
					easing: 'easeInOutExpo',
					opacity: 0,
					translateY: '-100%'
				}
			},
			'fx2' : {
				in: {
					duration: 800,
					delay: function( el, index) { return index * 50; },
					easing: 'easeOutElastic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: function(el, index) {
						return index%2 === 0 ? ['-80%', '0%'] : ['80%', '0%'];
					}
				},
				out: {
					duration: 300,
					delay: function( el, index ) { return index * 20; },
					easing: 'easeOutExpo',
					opacity: 0,
					translateY: function( el, index ) {
						return index%2 === 0 ? '80%' : '-80%';
					}
				}
			},
			'fx3' : {
				in: {
					duration: 700,
					delay: function(el, index) {
						return ( el.parentNode.children.length - index - 1 ) * 80;
					},
					easing: 'easeOutElastic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: function(el, index) {
						return index%2 === 0 ? [ '-80%', '0%' ] : [ '80%', '0%' ];
					},
					rotateZ: [90,0]
				},
				out: {
					duration: 300,
					delay: function(el, index) { return (el.parentNode.children.length-index-1) * 50; },
					easing: 'easeOutExpo',
					opacity: 0,
					translateY: function(el, index) {
						return index%2 === 0 ? '80%' : '-80%';
					},
					rotateZ: function(el, index) {
						return index%2 === 0 ? -25 : 25;
					}
				}
			},
			'fx4' : {
				in: {
					duration: 700,
					delay: function( el, index ) { return 550 + index * 50; },
					easing: 'easeOutQuint',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: [ '-150%','0%' ],
					rotateY: [ 180, 0 ]
				},
				out: {
					duration: 200,
					delay: function( el, index ) { return index * 30; },
					easing: 'easeInQuint',
					opacity: {
						value: 0,
						easing: 'linear',
					},
					translateY: '100%',
					rotateY: -180
				}
			},
			'fx5' : {
				in: {
					duration: 250,
					delay: function( el, index ) { return 200 + index * 25; },
					easing: 'easeOutCubic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: ['-50%','0%']
				},
				out: {
					duration: 250,
					delay: function( el, index ) { return index * 25; },
					easing: 'easeOutCubic',
					opacity: 0,
					translateY: '50%'
				}
			},
			'fx6' : {
				in: {
					duration: 400,
					delay: function( el, index ) { return index * 50; },
					easing: 'easeOutSine',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					rotateY: [ -90, 0 ]
				},
				out: {
					duration: 200,
					delay: function( el, index ) { return index * 50; },
					easing: 'easeOutSine',
					opacity: 0,
					rotateY: 45
				}
			},
			'fx7' : {
				in: {
					duration: 1000,
					delay: function( el, index ) { return 100 + index * 30; },
					easing: 'easeOutElastic',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					rotateZ: function( el, index ) {
						return [ anime.random( 20, 40 ), 0 ];
					}
				},
				out: {
					duration: 300,
					opacity: {
						value: [ 1, 0 ],
						easing: 'easeOutExpo',
					}
				}
			},
			'fx8' : {
				in: {
					duration: 400,
					delay: function( el, index ) { return 200 + index * 20; },
					easing: 'easeOutExpo',
					opacity: 1,
					rotateY: [ -90, 0 ],
					translateY: [ '50%','0%' ]
				},
				out: {
					duration: 250,
					delay: function( el, index ) { return index * 20; },
					easing: 'easeOutExpo',
					opacity: 0,
					rotateY: 90
				}
			},
			'fx9' : {
				in: {
					duration: 400,
					delay: function(el, index) { return 200+index*30; },
					easing: 'easeOutExpo',
					opacity: 1,
					rotateX: [90,0]
				},
				out: {
					duration: 250,
					delay: function(el, index) { return index*30; },
					easing: 'easeOutExpo',
					opacity: 0,
					rotateX: -90
				}
			},
			'fx10' : {
				in: {
					duration: 400,
					delay: function( el, index ) { return 100 + index * 50; },
					easing: 'easeOutExpo',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					rotateX: [ 110, 0 ]
				},
				out: {
					duration: 250,
					delay: function( el, index ) { return index * 50; },
					easing: 'easeOutExpo',
					opacity: 0,
					rotateX: -110
				}
			},
			'fx11' : {
				in: {
					duration: function( el, index ) { return anime.random( 800, 1000 ); },
					delay: function( el, index ) { return anime.random( 100, 300 ); },
					easing: 'easeOutExpo',
					opacity: {
						value: [ 0, 1 ],
						easing: 'easeOutExpo',
					},
					translateY: [ '-150%','0%' ],
					rotateZ: function( el, index ) { return [ anime.random( -50, 50 ), 0 ]; }
				},
				out: {
					duration: function( el, index ) { return anime.random( 200, 300 ); },
					delay: function( el, index ) { return anime.random( 0, 80 ); },
					easing: 'easeInQuart',
					opacity: 0,
					translateY: '50%',
					rotateZ: function( el, index ) { return anime.random( -50, 50 ); }
				}
			},
			'fx12' : {
				in: {
					elasticity: false,
					duration: 1,
					delay: function( el, index ) {
						var delay = index * 100 + anime.random( 50, 100 );

						return delay;
					},
					width: [ 0, function( el, i ) { return $( el ).width(); } ]
				},
				out: {
					duration: 1,
					delay: function( el, index ) { return ( el.parentNode.children.length - index - 1 ) * 20; },
					easing: 'linear',
					width: {
						value: 0
					}
				}
			}
		};

		self.textChange = function() {
			var currentDelay = animationDelay,
				$prevText    = $animatedTextList.eq( currentIndex ),
				$nextText;

			if ( currentIndex < $animatedTextList.length - 1 ) {
				currentIndex++;
			} else {
				currentIndex = 0;
			}

			$nextText = $animatedTextList.eq( currentIndex );

			self.hideText( $prevText, settings.effect, null, function( anime ) {
				$prevText.toggleClass( 'visible' );

				var currentDelay = animationDelay;

				if ( timeOut ) {
					clearTimeout( timeOut );
				}

				self.showText(
					$nextText,
					settings.effect,
					function() {
						$nextText.toggleClass( 'active' );
						$prevText.toggleClass( 'active' );

						$nextText.toggleClass( 'visible' );
					},
					function() {
						timeOut = setTimeout( function() {
							self.textChange();
						}, currentDelay );
					}
				);

			} );
		};

		self.showText = function( $selector, effect, beginCallback, completeCallback ) {
			var targets = [];

			$( 'span', $selector ).each( function() {
				$( this ).css( {
					'width': 'auto',
					'opacity': 1,
					'WebkitTransform': '',
					'transform': ''
				});
				targets.push( this );
			});

			self.animateText( targets, 'in', effect, beginCallback, completeCallback );
		};

		self.hideText = function( $selector, effect, beginCallback, completeCallback ) {
			var targets = [];

			$( 'span', $selector ).each( function() {
				targets.push(this);
			});

			self.animateText( targets, 'out', effect, beginCallback, completeCallback );
		};

		self.animateText = function( targets, direction, effect, beginCallback, completeCallback ) {
			var effectSettings   = self.avaliableEffects[ effect ] || {},
				animationOptions = effectSettings[ direction ],
				animeInstance = null;

			animationOptions.targets = targets;

			animationOptions.begin = beginCallback;
			animationOptions.complete = completeCallback;

			animeInstance = anime( animationOptions );
		};

		self.init = function() {
			var $text = $animatedTextList.eq( currentIndex );

			self.showText(
				$text,
				settings.effect,
				null,
				function() {
					var currentDelay = animationDelay;

					if ( timeOut ) {
						clearTimeout( timeOut );
					}

					timeOut = setTimeout( function() {
						self.textChange();
					}, currentDelay );

				}
			);
		};
	}

	/**
	 * Jet Images Layout Class
	 *
	 * @return {void}
	 */
	window.jetImagesLayout = function( $selector, settings ) {
		var self            = this,
			$instance       = $selector,
			$instanceList   = $( '.jet-images-layout__list', $instance ),
			$itemsList      = $( '.jet-images-layout__item', $instance ),
			defaultSettings = {},
			editMode        = Boolean( elementor.isEditMode() ),
			settings        = settings || {};

		/*
		 * Default Settings
		 */
		defaultSettings = {
			layoutType: 'masonry',
			justifyHeight: 300
		}

		/**
		 * Checking options, settings and options merging
		 */
		$.extend( defaultSettings, settings );

		/**
		 * Layout build
		 */
		self.layoutBuild = function() {
			switch ( settings['layoutType'] ) {
				case 'masonry':
					salvattore.init();
				break;
				case 'justify':
					$itemsList.each( function() {
						var $this          = $( this ),
							$imageInstance = $( '.jet-images-layout__image-instance', $this),
							imageWidth     = $imageInstance.data( 'width' ),
							imageHeight    = $imageInstance.data( 'height' ),
							imageRatio     = +imageWidth / +imageHeight,
							flexValue      = imageRatio * 100,
							newWidth       = +settings['justifyHeight'] * imageRatio,
							newHeight      = 'auto';

						$this.css( {
							'flex-grow': flexValue,
							'flex-basis': newWidth
						} );
					} );
				break;
			}

			if ( $.isFunction( $.fn.imagesLoaded ) ) {

				$( '.jet-images-layout__image', $itemsList ).imagesLoaded().progress( function( instance, image ) {
					var $image      = $( image.img ),
						$parentItem = $image.closest( '.jet-images-layout__item' ),
						$loader     = $( '.jet-images-layout__image-loader', $parentItem );

					$parentItem.addClass( 'image-loaded' );

					$loader.fadeTo( 500, 0, function() {
						$( this ).remove();
					} );

				});

			} else {
				var $loader = $( '.jet-images-layout__image-loader', $itemsList );

				$itemsList.addClass( 'image-loaded' );

				$loader.fadeTo( 500, 0, function() {
					$( this ).remove();
				} );
			}
		}

		/**
		 * Init
		 */
		self.init = function() {
			self.layoutBuild();
		}
	}

	/**
	 * Jet Scroll Navigation Class
	 *
	 * @return {void}
	 */
	window.jetScrollNavigation = function( $scope, $selector, settings ) {
		var self            = this,
			$window         = $( window ),
			$document       = $( document ),
			$body           = $( 'body' ),
			$instance       = $selector,
			$htmlBody       = $( 'html, body' ),
			$itemsList      = $( '.jet-scroll-navigation__item', $instance ),
			sectionList     = [],
			defaultSettings = {
				speed: 500,
				blockSpeed: 500,
				offset: 0,
				sectionSwitch: false,
				sectionSwitchOnMobile: true,
			},
			settings        = $.extend( {}, defaultSettings, settings ),
			sections        = {},
			currentSection  = null,
			isScrolling     = false,
			isSwipe         = false,
			hash            = window.location.hash.slice(1),
			timeout         = null,
			timeStamp       = 0,
			platform        = navigator.platform;

		jQuery.extend( jQuery.easing, {
			easeInOutCirc: function (x, t, b, c, d) {
				if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
				return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
			}
		});

		/**
		 * [init description]
		 * @return {[type]} [description]
		 */
		self.init = function() {
			self.setSectionsData();

			if ( hash && sections.hasOwnProperty( hash ) ) {
				$itemsList.addClass( 'invert' );
			}

			// Add Events
			$itemsList.on( 'click.jetScrollNavigation', function( event ) {
				var $this     = $( this ),
					sectionId = $this.data( 'anchor' );

				self.onAnchorChange( sectionId );
			} );

			$window.on( 'resize.jetScrollNavigation orientationchange.jetScrollNavigation', JetElementsTools.debounce( 50, self.onResize ) );
			$window.on( 'load', function() { self.setSectionsData(); } );

			$document.keydown( function( event ) {
				if ( !self.isEnabled() ) {
					return;
				}

				if ( 38 == event.keyCode ) {
					self.directionSwitch( event, 'up' );
				}

				if ( 40 == event.keyCode ) {
					self.directionSwitch( event, 'down' );
				}
			} );

			// waypoint section detection
			self.waypointHandler();

			// hijaking handler
			self.hijakingHandler();

			// init resizeSensor.js
			if ( 'undefined' !== typeof ResizeSensor ) {
				new ResizeSensor( $( '.jet-scroll-navigation-section' ), JetElementsTools.debounce( 50, function() {
					self.setSectionsData();
					self.waypointHandler();
				} ) );
			}
		};

		/**
		 * [setSectionsData description]
		 */
		self.setSectionsData = function() {
			$itemsList.each( function() {
				var $this         = $( this ),
					sectionId     = $this.data('anchor'),
					sectionInvert = 'yes' === $this.data('invert') ? true : false,
					$section      = $( '#' + sectionId );

				if ( $section[0] ) {
					$section.addClass( 'jet-scroll-navigation-section' );
					//$section.attr( 'touch-action', 'none' );
					$section[0].dataset.sectionName = sectionId;
					sections[ sectionId ] = {
						selector: $section,
						offset: Math.round( $section.offset().top ),
						height: $section.outerHeight(),
						invert: sectionInvert
					};
				}
			} );
		};

		/**
		 * [waypointHandler description]
		 * @return {[type]} [description]
		 */
		self.waypointHandler = function() {

			for ( var section in sections ) {
				var $section = sections[section].selector;

				elementorFrontend.waypoint( $section, function( direction ) {
					var $this = $( this ),
						sectionId = $this.attr( 'id' );

						if ( 'down' === direction && ! isScrolling ) {
							window.history.pushState( null, null, '#' + sectionId );
							currentSection = sectionId;
							$itemsList.removeClass( 'active' );
							$( '[data-anchor=' + sectionId + ']', $instance ).addClass( 'active' );

							$itemsList.removeClass( 'invert' );

							if ( sections[sectionId].invert ) {
								$itemsList.addClass( 'invert' );
							}
						}
				}, {
					offset: '70%',
					triggerOnce: false
				} );

				elementorFrontend.waypoint( $section, function( direction ) {
					var $this = $( this ),
						sectionId = $this.attr( 'id' );

						if ( 'up' === direction && ! isScrolling ) {
							window.history.pushState( null, null, '#' + sectionId );
							currentSection = sectionId;
							$itemsList.removeClass( 'active' );
							$( '[data-anchor=' + sectionId + ']', $instance ).addClass( 'active' );

							$itemsList.removeClass( 'invert' );

							if ( sections[sectionId].invert ) {
								$itemsList.addClass( 'invert' );
							}
						}
				}, {
					offset: '0%',
					triggerOnce: false
				} );
			}
		};

		/**
		 * [onAnchorChange description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.onAnchorChange = function( sectionId ) {
			var $this     = $( '[data-anchor=' + sectionId + ']', $instance ),
				offset    = null;

			if ( ! sections.hasOwnProperty( sectionId ) ) {
				return false;
			}

			offset = sections[sectionId].offset - settings.offset;

			if ( ! isScrolling ) {
				isScrolling = true;

				window.history.pushState( null, null, '#' + sectionId );
				currentSection = sectionId;

				$itemsList.removeClass( 'active' );
				$this.addClass( 'active' );

				$itemsList.removeClass( 'invert' );

				if ( sections[sectionId].invert ) {
					$itemsList.addClass( 'invert' );
				}

				$htmlBody.animate( { 'scrollTop': offset }, settings.speed, 'easeInOutCirc', function() {
					isScrolling = false;
				} );
			}
		};

		/**
		 * [directionSwitch description]
		 * @param  {[type]} event     [description]
		 * @param  {[type]} direction [description]
		 * @return {[type]}           [description]
		 */
		self.directionSwitch = function( event, direction ) {
			var direction = direction || 'up',
				nextItem = $( '[data-anchor=' + currentSection + ']', $instance ).next(),
				prevItem = $( '[data-anchor=' + currentSection + ']', $instance ).prev();

			if ( isScrolling ) {
				return false;
			}

			if ( 'up' === direction ) {
				if ( prevItem[0] ) {
					prevItem.trigger( 'click.jetScrollNavigation' );
				}
			}

			if ( 'down' === direction ) {
				if ( nextItem[0] ) {
					nextItem.trigger( 'click.jetScrollNavigation' );
				}
			}
		};

		/**
		 * [scrollifyHandler description]
		 * @return {[type]} [description]
		 */
		self.hijakingHandler = function() {
			var isMobile    = JetElementsTools.mobileAndTabletcheck(),
				touchStartY = 0,
				touchEndY   = 0;

			if ( settings.sectionSwitch ) {

				if ( ! isMobile ) {
					document.addEventListener( 'wheel', self.onWheel, { passive: false } );
				}

				if ( isMobile && settings['sectionSwitchOnMobile'] ) {

					document.addEventListener( 'touchstart', function( event ) {
						if ( !self.isEnabled() ) {
							return;
						}

						var $target   = $( event.target ),
							$section  = $target.closest( '.elementor-top-section' ),
							sectionId = $section.attr( 'id' ) || false;

						touchStartY = event.changedTouches[0].clientY; // clientY instead of screenY, screenY is implemented differently in iOS, making it useless for thresholding

						if ( sectionId && isScrolling ) {
							event.preventDefault();
						}

					}, { passive: false } );

					document.addEventListener( 'touchend', function( event ) {
						if ( !self.isEnabled() ) {
							return;
						}

						var $target         = $( event.target ),
							$navigation     = $target.closest( '.jet-scroll-navigation' ) || false,
							$section        = $target.closest( '.elementor-top-section' ) || false,
							sectionId       = $section.attr( 'id' ) || false,
							endScrollTop    = $window.scrollTop(),
							touchEndY       = event.changedTouches[0].clientY,
							direction       = touchEndY > touchStartY ? 'up' : 'down',
							sectionOffset   = false,
							newSectionId    = false,
							prevSectionId   = false,
							nextSectionId   = false,
							swipeYthreshold = (window.screen.availHeight / 8); // defining pageswitch threshold at 1/8 of screenheight

						if ( Math.abs( touchEndY - touchStartY ) < 20 ) {
							return false;
						}

						if ( $navigation[0] ) {
							return false;
						}

						if ( sectionId && sections.hasOwnProperty( sectionId ) ) {

							prevSectionId = JetElementsTools.getObjectPrevKey( sections, sectionId );
							nextSectionId = JetElementsTools.getObjectNextKey( sections, sectionId );

							sectionOffset = sections[ sectionId ].offset;

							if ( 'up' === direction ) {

								if ( sectionOffset - swipeYthreshold < endScrollTop ) { //threshold used here
									prevSectionId = sectionId;
								}

								if ( prevSectionId ) {
									newSectionId = prevSectionId;
								}
							}

							if ( 'down' === direction ) {

								if ( sectionOffset + swipeYthreshold > endScrollTop ) { //threshold used here
									nextSectionId = sectionId;
								}

								if ( nextSectionId ) {
									newSectionId = nextSectionId;
								}
							}

							if ( newSectionId ) {

								self.onAnchorChange( newSectionId );
							}
						}

					}, { passive: false } );
				}
			}
		}

		/**
		 * [onScroll description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.onScroll = function( event ) {
			event.preventDefault();
		};

		self.onWheel = function( event ) {

			if ( !self.isEnabled() ) {
				return;
			}

			if ( isScrolling ) {
				event.preventDefault();
			}

			var $target         = $( event.target ),
				$section        = $target.closest( '.elementor-top-section[id]' ),
				sectionId       = $section.attr( 'id' ) || false,
				delta           = event.deltaY,
				direction       = ( 0 > delta ) ? 'up' : 'down',
				sectionOffset   = false,
				newSectionId    = false,
				prevSectionId   = false,
				nextSectionId   = false,
				windowScrollTop = $window.scrollTop();

			if ( sectionId && sections.hasOwnProperty( sectionId ) ) {

				prevSectionId = JetElementsTools.getObjectPrevKey( sections, sectionId );
				nextSectionId = JetElementsTools.getObjectNextKey( sections, sectionId );

				sectionOffset = sections[ sectionId ].offset;

				if ( 'up' === direction ) {

					if ( sectionOffset < windowScrollTop + settings.offset - 10 ) {
						prevSectionId = sectionId;
					}

					if ( prevSectionId ) {
						newSectionId = prevSectionId;
					}
				}

				if ( 'down' === direction ) {

					if ( sectionOffset > windowScrollTop + settings.offset + 10 ) {
						nextSectionId = sectionId;
					}

					if ( nextSectionId ) {
						newSectionId = nextSectionId;
					}
				}

				if ( newSectionId ) {
					event.preventDefault();

					if ( event.timeStamp - timeStamp > 15 && 'MacIntel' == platform ) {
						timeStamp = event.timeStamp;

						return false;
					}

					self.onAnchorChange( newSectionId );
				}
			}

			return false;
		};

		/**
		 * [onResize description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.onResize = function( event ) {
			self.setSectionsData();
		};

		/**
		 * [scrollStop description]
		 * @return {[type]} [description]
		 */
		self.scrollStop = function() {
			$htmlBody.stop( true );
		};

		/**
		 * Is the responsive breakpoint enabled
		 *
		 * @return {boolean} Enabled
		 */
		self.isEnabled = function() {
			return $scope.is(":visible");
		};

		/**
		 * Mobile and tablet check funcion.
		 *
		 * @return {boolean} Mobile Status
		 */
		self.mobileAndTabletcheck = function() {
			var check = false;

			(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);

			return check;
		};

	}

	/**
	 * jetSectionParallax Class
	 *
	 * @return {void}
	 */
	window.jetSectionParallax = function( $target ) {
		var self             = this,
			sectionId        = $target.data('id'),
			settings         = false,
			editMode         = Boolean( elementor.isEditMode() ),
			$window          = $( window ),
			$body            = $( 'body' ),
			scrollLayoutList = [],
			mouseLayoutList  = [],
			winScrollTop     = $window.scrollTop(),
			winHeight        = $window.height(),
			requesScroll     = null,
			requestMouse     = null,
			tiltx            = 0,
			tilty            = 0,
			isSafari         = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/),
			platform         = navigator.platform;

		/**
		 * Init
		 */
		self.init = function() {

			if ( ! editMode ) {
				settings = $target.data('settings') || false;
				settings = false != settings ? settings['jet_parallax_layout_list'] : false;
			} else {
				settings = self.generateEditorSettings( sectionId );
			}

			if ( ! settings ) {
				return false;
			}

			self.generateLayouts();

			$window.on( 'resize.jetSectionParallax orientationchange.jetSectionParallax', JetElementsTools.debounce( 30, self.generateLayouts ) );
			//$window.on( 'resize orientationchange', JetElementsTools.debounce( 50, self.scrollHandler ) );

			if ( 0 !== scrollLayoutList.length ) {
				$window.on( 'scroll.jetSectionParallax resize.jetSectionParallax', self.scrollHandler );
			}

			if ( 0 !== mouseLayoutList.length ) {
				$target.on( 'mousemove.jetSectionParallax resize.jetSectionParallax', self.mouseMoveHandler );
				$target.on( 'mouseleave.jetSectionParallax', self.mouseLeaveHandler );
			}

			self.scrollUpdate();
		};

		self.generateEditorSettings = function( sectionId ) {
			var editorElements      = null,
				sectionsData        = {},
				sectionData         = {},
				sectionParallaxData = {},
				settings            = [];

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

			if ( ! sectionData.hasOwnProperty( 'jet_parallax_layout_list' ) || 0 === Object.keys( sectionData ).length ) {
				return false;
			}

			sectionParallaxData = sectionData[ 'jet_parallax_layout_list' ].models;

			$.each( sectionParallaxData, function( index, obj ) {
				settings.push( obj.attributes );
			} );

			if ( 0 !== settings.length ) {
				return settings;
			}

			return false;
		};

		self.generateLayouts = function() {

			$( '.jet-parallax-section__layout', $target ).remove();

			$.each( settings, function( index, layout ) {
				var imageData      = layout['jet_parallax_layout_image'],
					speed          = layout['jet_parallax_layout_speed']['size'] || 50,
					zIndex         = layout['jet_parallax_layout_z_index'],
					animProp       = layout['jet_parallax_layout_animation_prop'] || 'bgposition',
					deviceMode     = elementorFrontend.getCurrentDeviceMode(),
					activeBreakpoints = elementor.config.responsive.activeBreakpoints,
					activeBreakpointsArray = [],
					bgX            = layout['jet_parallax_layout_bg_x'],
					bgY            = layout['jet_parallax_layout_bg_y'],
					type           = layout['jet_parallax_layout_type'] || 'none',
					direction      = layout['jet_parallax_layout_direction'] || '1',
					fxDirection    = layout['jet_parallax_layout_fx_direction'] || 'fade-in',
					device         = layout['jet_parallax_layout_on'] || ['desktop', 'tablet'],
					_id            = layout['_id'],
					$layout        = null,
					layoutData     = {},
					safariClass    = isSafari ? ' is-safari' : '',
					macClass       = 'MacIntel' == platform ? ' is-mac' : '';

				if ( -1 === device.indexOf( deviceMode ) ) {
					return false;
				}
			
				
				for ( var [key, value] of Object.entries( activeBreakpoints ) ) {
					if ( 'widescreen' === key ) {
						activeBreakpointsArray.push( 'desktop' );
						activeBreakpointsArray.push( key );
					} else {
						activeBreakpointsArray.push( key );
					}
					
				}

				if ( -1 === activeBreakpointsArray.indexOf( 'widescreen' ) ) {
					activeBreakpointsArray.push( 'desktop' );
				}

				activeBreakpointsArray = activeBreakpointsArray.reverse();

				var breakpoints = [ 'widescreen', 'desktop', 'laptop', 'tablet_extra', 'tablet', 'mobile_extra', 'mobile'],
					i = 0,
					prevDevice,
					layoutBreakpoinntsSettings = [];

				breakpoints.forEach( function( item ) {

					if ( -1 != activeBreakpointsArray.indexOf( item ) ) {

						layoutBreakpoinntsSettings[i] = [];

						if ( 'widescreen' === item ) {
							layoutBreakpoinntsSettings[i][item] = {
								'bgX' : '' != layout['jet_parallax_layout_bg_x_' + item] ? layout['jet_parallax_layout_bg_x'] : 0,

								'bgY' : '' != layout['jet_parallax_layout_bg_y_' + item] ? layout['jet_parallax_layout_bg_y'] : 0,

								'layoutImageData' : '' != layout['jet_parallax_layout_image_' + item] ? layout['jet_parallax_layout_image_' + item] : ''
							};
						} else if ( 'desktop' === item ) {
							layoutBreakpoinntsSettings[i][item] = {
								'bgX' : '' != layout['jet_parallax_layout_bg_x'] ? layout['jet_parallax_layout_bg_x'] : 0,

								'bgY' : '' != layout['jet_parallax_layout_bg_y'] ? layout['jet_parallax_layout_bg_y'] : 0,

								'layoutImageData' : imageData['url'] || layout['jet_parallax_layout_image']['url']
							};
						} else {
							layoutBreakpoinntsSettings[i][item] = {
								'bgX': ( layout['jet_parallax_layout_bg_x_' + item] && '' != layout['jet_parallax_layout_bg_x_' + item] ) ? layout['jet_parallax_layout_bg_x_' + item] : layoutBreakpoinntsSettings[i-1][prevDevice].bgX,

								'bgY' : ( layout['jet_parallax_layout_bg_y_' + item] && '' != layout['jet_parallax_layout_bg_y_' + item] ) ? layout['jet_parallax_layout_bg_y_' + item] : layoutBreakpoinntsSettings[i-1][prevDevice].bgY,

								'layoutImageData' : ( layout['jet_parallax_layout_image_' + item] && '' != layout['jet_parallax_layout_image_' + item]['url'] ) ? layout['jet_parallax_layout_image_' + item]['url'] : layoutBreakpoinntsSettings[i-1][prevDevice].layoutImageData
							};
						}

						if ( deviceMode === item ) {
							bgX    = layoutBreakpoinntsSettings[i][item].bgX;
							bgY    = layoutBreakpoinntsSettings[i][item].bgY;
							imageData = layoutBreakpoinntsSettings[i][item].layoutImageData;
						}

						prevDevice = item;

						i++;
					}

				} );

				if ( ! $target.hasClass( 'jet-parallax-section' ) ) {
					$target.addClass( 'jet-parallax-section' );
				}

				$layout = $( '<div class="jet-parallax-section__layout elementor-repeater-item-' + _id + ' jet-parallax-section__' + type +'-layout' + macClass + '"><div class="jet-parallax-section__image"></div></div>' )
					.prependTo( $target )
					.css( {
						'z-index': zIndex
					} );

				var imageCSS = {
					'background-position-x': bgX + '%',
					'background-position-y': bgY + '%',
					'background-image': 'url(' + imageData + ')'
				};

				$( '> .jet-parallax-section__image', $layout ).css( imageCSS );

				layoutData = {
					selector: $layout,
					prop: animProp,
					type: type,
					device: device,
					xPos: bgX,
					yPos: bgY,
					direction: +direction,
					fxDirection: fxDirection,
					speed: 2 * ( speed / 100 )
				};

				if ( 'none' !== type ) {
					if ( JetElementsTools.inArray( type, ['scroll', 'h-scroll', 'zoom', 'rotate', 'blur', 'opacity'] ) ) {
						scrollLayoutList.push( layoutData );
					}

					if ( 'mouse' === type ) {
						mouseLayoutList.push( layoutData );
					}
				}

			} );

		};

		self.scrollHandler = function( event ) {
			winScrollTop = $window.scrollTop();
			winHeight    = $window.height();

			self.scrollUpdate();
		};

		self.scrollUpdate = function() {
			$.each( scrollLayoutList, function( index, layout ) {

				var $this      = layout.selector,
					$image     = $( '.jet-parallax-section__image', $this ),
					speed      = layout.speed,
					offsetTop  = $this.offset().top,
					thisHeight = $this.outerHeight(),
					prop       = layout.prop,
					type       = layout.type,
					dir        = layout.direction,
					fxDir      = layout.fxDirection,
					posY       = ( winScrollTop - offsetTop + winHeight ) / thisHeight * 100,
					device     = elementorFrontend.getCurrentDeviceMode();

				if ( -1 === layout.device.indexOf( device ) ) {
					$image.css( {
						'transform': 'translateX(0) translateY(0)',
						'background-position-y': layout.yPos,
						'background-position-x': layout.xPos,
						'filter': 'none',
						'opacity': '1'
					} );

					return false;
				}

				if ( winScrollTop < offsetTop - winHeight ) posY = 0;
				if ( winScrollTop > offsetTop + thisHeight) posY = 200;

				posY = parseFloat( speed * posY ).toFixed(1);

				switch( type ) {
					case 'scroll':
						if ( 'bgposition' === prop ) {
							$image.css( {
								'background-position-y': 'calc(' + layout.yPos + '% + ' + posY + 'px)'
							} );
						} else {
							$image.css( {
								'transform': 'translateY(' + posY + 'px)'
							} );
						}
						break;
					case 'h-scroll':
						if ( 'bgposition' === prop ) {
							$image.css( {
								'background-position-x': 'calc(' + layout.xPos + '% + ' + (posY * dir) + 'px)'
							} );
						} else {
							$image.css( {
								'transform': 'translateX(' + (posY * dir) + 'px)'
							} );
						}
						break;
					case 'zoom':
						var deltaScale = ( winScrollTop - offsetTop + winHeight ) / winHeight,
							scale      = deltaScale * speed;

						scale = scale + 1;

						$image.css( {
							'transform': 'scale(' + scale + ')'
						} );
						break;
					case 'rotate':
						var rotate = posY;

						$image.css( {
							'transform': 'rotateZ(' + (rotate * dir) + 'deg)'
						} );
						break;
					case 'blur':
						var blur = 0;

						switch ( fxDir ) {
							case 'fade-in':
								blur = posY / 40;
								break;

							case 'fade-out':
								blur = (5 * speed) - (posY / 40);
								break
						}

						$image.css( {
							'filter': 'blur(' + blur + 'px)'
						} );
						break;
					case 'opacity':
						var opacity = 1;

						switch ( fxDir ) {
							case 'fade-in':
								opacity = 1 - (posY / 400);
								break;

							case 'fade-out':
								opacity = (1 - (0.5 * speed)) + (posY / 400);
								break
						}

						$image.css( {
							'opacity': opacity
						} );
						break;
				}

			} );

			//requesScroll = requestAnimationFrame( self.scrollUpdate );
			//requestAnimationFrame( self.scrollUpdate );
		};

		self.mouseMoveHandler = function( event ) {
			var windowWidth  = $window.width(),
				windowHeight = $window.height(),
				cx           = Math.ceil( windowWidth / 2 ),
				cy           = Math.ceil( windowHeight / 2 ),
				dx           = event.clientX - cx,
				dy           = event.clientY - cy;

			tiltx = -1 * ( dx / cx );
			tilty = -1 * ( dy / cy);

			self.mouseMoveUpdate();
		};

		self.mouseLeaveHandler = function( event ) {

			$.each( mouseLayoutList, function( index, layout ) {
				var $this  = layout.selector,
					$image = $( '.jet-parallax-section__image', $this );

				switch( layout.prop ) {
					case 'transform3d':
						TweenMax.to(
							$image[0],
							1.2, {
								x: 0,
								y: 0,
								z: 0,
								rotationX: 0,
								rotationY: 0,
								ease:Power2.easeOut
							}
						);
					break;
				}

			} );
		};

		self.mouseMoveUpdate = function() {
			$.each( mouseLayoutList, function( index, layout ) {
				var $this   = layout.selector,
					$image  = $( '.jet-parallax-section__image', $this ),
					speed   = layout.speed,
					prop    = layout.prop,
					posX    = parseFloat( tiltx * 125 * speed ).toFixed(1),
					posY    = parseFloat( tilty * 125 * speed ).toFixed(1),
					posZ    = layout.zIndex * 50,
					rotateX = parseFloat( tiltx * 25 * speed ).toFixed(1),
					rotateY = parseFloat( tilty * 25 * speed ).toFixed(1),
					device  = elementorFrontend.getCurrentDeviceMode();

				if ( -1 == layout.device.indexOf( device ) ) {
					$image.css( {
						'transform': 'translateX(0) translateY(0)',
						'background-position-x': layout.xPos,
						'background-position-y': layout.yPos
					} );

					return false;
				}

				switch( prop ) {
					case 'bgposition':
						TweenMax.to(
							$image[0],
							1, {
								backgroundPositionX: 'calc(' + layout.xPos + '% + ' + posX + 'px)',
								backgroundPositionY: 'calc(' + layout.yPos + '% + ' + posY + 'px)',
								ease:Power2.easeOut
							}
						);
					break;

					case 'transform':
						TweenMax.to(
							$image[0],
							1, {
								x: posX,
								y: posY,
								ease:Power2.easeOut
							}
						);
					break;

					case 'transform3d':
						TweenMax.to(
							$image[0],
							2, {
								x: posX,
								y: posY,
								z: posZ,
								rotationX: rotateY,
								rotationY: -rotateX,
								ease:Power2.easeOut
							}
						);
					break;
				}

			} );
		};

	}

	/**
	 * Jet Portfolio Class
	 *
	 * @return {void}
	 */
	window.jetPortfolio = function( $selector, settings ) {
		var self            = this,
			$instance       = $selector,
			$instanceList   = $( '.jet-portfolio__list', $instance ),
			$itemsList      = $( '.jet-portfolio__item', $instance ),
			$filterList     = $( '.jet-portfolio__filter-item', $instance ),
			$moreWrapper    = $( '.jet-portfolio__view-more', $instance ),
			$moreButton     = $( '.jet-portfolio__view-more-button', $instance ),
			isViewMore      = $moreButton[0],
			itemsData       = {},
			filterData      = {},
			currentFilter   = 'all',
			activeSlug      = [],
			isRTL           = JetElementsTools.isRTL(),
			editMode        = Boolean( elementor.isEditMode() ),
			defaultSettings = {
				layoutType: 'masonry',
				columns: 3,
				perPage: 6
			},
			masonryOptions = {
				itemSelector: '.jet-portfolio__item',
				percentPosition: true,
				isOriginLeft : true === isRTL ? false : true
			},
			settings        = $.extend( defaultSettings, settings ),
			$masonryInstance,
			page            = 1;

		/**
		 * Init
		 */
		self.init = function() {
			self.layoutBuild();

			if ( editMode && $masonryInstance.get(0) ) {

				$(window).on( 'resize', JetElementsTools.debounce( 50, function() {
					$masonryInstance.masonry('layout');
				}));
			}
		}

		/**
		 * Layout build
		 */
		self.layoutBuild = function() {

			self.generateData();

			$filterList.data( 'showItems', isViewMore ? settings.perPage : 'all' );

			if ( 'justify' == settings['layoutType'] ) {
				masonryOptions['columnWidth'] = '.grid-sizer';
			}

			if ( 'masonry' == settings['layoutType'] || 'justify' == settings['layoutType'] ) {
				$masonryInstance = $instanceList.masonry( masonryOptions );
			}

			if ( $.isFunction( $.fn.imagesLoaded ) ) {

				$( '.jet-portfolio__image', $itemsList ).imagesLoaded().progress( function( instance, image ) {
					var $image      = $( image.img ),
						$parentItem = $image.closest( '.jet-portfolio__item' ),
						$loader     = $( '.jet-portfolio__image-loader', $parentItem );

					$loader.remove();

					$parentItem.addClass( 'item-loaded' );

					if ( $masonryInstance ) {
						$masonryInstance.masonry( 'layout' );
					}
				} );

			} else {
				var $loader = $( '.jet-portfolio__image-loader', $itemsList );

				$itemsList.addClass( 'item-loaded' );

				$loader.remove();
			}

			$filterList.on( 'click.jetPortfolio', self.filterHandler );
			$moreButton.on( 'click.jetPortfolio', self.moreButtonHandler );

			self.render();
			self.checkMoreButton();
		};

		self.generateData = function() {
			if ( $filterList[0] ) {
				$filterList.each( function( index ) {
					var $this = $( this ),
						slug  = $this.data('slug');

					filterData[ slug ] = false;

					if ( 'all' == slug ) {
						filterData[ slug ] = true;
					}
				} );
			} else {
				filterData['all'] = true;
			}

			$itemsList.each( function( index ) {
				var $this = $( this ),
					slug  = $this.data('slug');

				itemsData[ index ] = {
					selector: $this,
					slug: slug,
					visible: $this.hasClass( 'visible-status' ) ? true : false,
					more: $this.hasClass( 'hidden-status' ) ? true : false,
					lightboxEnabled: 'yes' === $this.find('.jet-portfolio__link').data( 'elementor-open-lightbox' ) ? true : false
				};
			} );
		};

		self.filterHandler = function( event ) {
			var $this = $( this ),
				counter = 1,
				slug  = $this.data( 'slug' ),
				showItems = $this.data( 'showItems' );

			$filterList.removeClass( 'active' );
			$this.addClass( 'active' );

			for ( var slugName in filterData ) {
				filterData[ slugName ] = false;

				if ( slugName == slug ) {
					filterData[ slugName ] = true;
					currentFilter = slugName;
				}
			}

			$.each( itemsData, function( index, obj ) {
				var visible = false;

				if ( 'all' === showItems ) {

					if ( self.isItemVisible( obj.slug ) && ! obj['more'] ) {
						visible = true;
					}

				} else if ( self.isItemVisible( obj.slug ) ) {

					if ( counter <= showItems ) {
						visible = true;
						obj.more = false;
					} else {
						obj.more = true;
					}

					counter++
				}

				obj.visible = visible;

			} );

			self.render();
			self.checkMoreButton();
		}

		/**
		 * [moreButtonHandler description]
		 * @param  {[type]} event [description]
		 * @return {[type]}       [description]
		 */
		self.moreButtonHandler = function( event ) {
			var $this   = $( this ),
				counter = 1,
				activeFilter = $( '.jet-portfolio__filter-item.active', $instance ),
				showItems;

			$.each( itemsData, function( index, obj ) {

				if ( self.isItemVisible( obj.slug ) && obj.more && counter <= settings.perPage ) {
					obj.more = false;
					obj.visible = true;

					counter++;
				}
			} );

			if ( activeFilter[0] ) {
				showItems = activeFilter.data( 'showItems' );
				activeFilter.data( 'showItems', showItems + counter - 1 );
			}

			self.render();
			self.checkMoreButton();
		}

		/**
		 * [checkmoreButton description]
		 * @return {[type]} [description]
		 */
		self.checkMoreButton = function() {
			var check = false;

			$.each( itemsData, function( index, obj ) {

				if ( self.isItemVisible( obj.slug ) && obj.more ) {
					check = true;
				}
			} );

			if ( check ) {
				$moreWrapper.removeClass( 'hidden-status' );
			} else {
				$moreWrapper.addClass( 'hidden-status' );
			}
		}

		/**
		 * [anyFilterEnabled description]
		 * @return {Boolean} [description]
		 */
		self.isItemVisible = function( slugs ) {
			var slugList = JetElementsTools.getObjectValues( slugs );

			for ( var slug in filterData ) {
				var checked = filterData[ slug ];

				if ( checked && -1 !== slugList.indexOf( slug ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * [anyFilterEnabled description]
		 * @return {Boolean} [description]
		 */
		self.anyFilterEnabled = function() {

			for ( var slug in filterData ) {
				if ( filterData[ slug ] ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		self.render = function() {
			var hideAnimation,
				showAnimation;

			$itemsList.removeClass( 'visible-status' ).removeClass( 'hidden-status' );

			$.each( itemsData, function( index, itemData ) {
				var selector = $( '.jet-portfolio__inner', itemData.selector ),
					$itemLink = $( '.jet-portfolio__link', itemData.selector ),
					slideshowID = settings.id + '-' + currentFilter;

				if ( itemData.visible ) {
					itemData.selector.addClass( 'visible-status' );

					if ( itemData.lightboxEnabled ) {
						$itemLink[0].setAttribute( 'data-elementor-lightbox-slideshow', slideshowID );
					}

					showAnimation = anime( {
						targets: selector[0],
						opacity: {
							value: 1,
							duration: 400,
						},
						scale: {
							value: 1,
							duration: 500,
							easing: 'easeOutExpo'
						},
						delay: 50,
						elasticity: false
					} );
				} else {
					itemData.selector.addClass( 'hidden-status' );
					$itemLink[0].removeAttribute( 'data-elementor-lightbox-slideshow' );

					hideAnimation = anime( {
						targets: selector[0],
						opacity: 0,
						scale: 0,
						duration: 500,
						elasticity: false
					} );
				}
			} );

			if ( $masonryInstance ) {
				$masonryInstance.masonry( 'layout' );
			}
		}
	}

	/**
	 * Jet Timeline Class
	 *
	 * @return {void}
	 */
	window.jetTimeLine = function ( $element ) {
		var $parentPopup	= $element.closest( '.jet-popup__container-inner, .elementor-popup-modal .dialog-message' ),
			inPopup			= !!$parentPopup[0],
			$viewport		= inPopup ? $parentPopup : $(window),
			viewportOffset  = inPopup ? $viewport.offset().top - $(window).scrollTop() : 0,
			self			= this,
			$line 			= $element.find( '.jet-timeline__line' ),
			$progress		= $line.find( '.jet-timeline__line-progress' ),
			$cards			= $element.find( '.jet-timeline-item' ),
			$points 		= $element.find('.timeline-item__point'),

			currentScrollTop 		= $viewport.scrollTop(),
			lastScrollTop 			= -1,
			currentWindowHeight 	= $viewport.height(),
			currentViewportHeight 	= inPopup ? $viewport.outerHeight() : window.innerHeight,
			lastWindowHeight 		= -1,
			requestAnimationId 		= null,
			flag 					= false;

		self.onScroll = function (){
			currentScrollTop = $viewport.scrollTop();
			viewportOffset   = inPopup ? $viewport.offset().top - $(window).scrollTop() : 0;

			self.updateFrame();
			self.animateCards();
		};

		self.onResize = function() {
			currentScrollTop = $viewport.scrollTop();
			currentWindowHeight = $viewport.height();
			viewportOffset = inPopup ? $viewport.offset().top - $(window).scrollTop() : 0;

			self.updateFrame();
		};

		self.updateWindow = function() {
			flag = false;

			$line.css({
				'top' 		: $cards.first().find( $points ).offset().top - $cards.first().offset().top,
				'bottom'	: ( $element.offset().top + $element.outerHeight() ) - $cards.last().find( $points ).offset().top
			});

			if ( ( lastScrollTop !== currentScrollTop ) ) {
				lastScrollTop 	 = currentScrollTop;
				lastWindowHeight = currentWindowHeight;

				self.updateProgress();
			}
		};

		self.updateProgress = function() {
			var lastCartOffset = $cards.last().find( $points ).offset().top,
				progressFinishPosition = ! inPopup ? lastCartOffset : lastCartOffset + currentScrollTop - viewportOffset - $(window).scrollTop(),
				progressOffsetTop = ! inPopup ? $progress.offset().top : $progress.offset().top + currentScrollTop - viewportOffset - $(window).scrollTop(),
				progressHeight = ( currentScrollTop - progressOffsetTop ) + ( currentViewportHeight / 2 );

			if ( progressFinishPosition <= ( currentScrollTop + currentViewportHeight / 2 ) ) {
				progressHeight = progressFinishPosition - progressOffsetTop;
			}

			$progress.css({
				'height' : progressHeight + 'px'
			});

			$cards.each( function() {
				var itemOffset = $(this).find( $points ).offset().top;
				itemOffset = ! inPopup ? itemOffset : itemOffset + currentScrollTop - viewportOffset - $(window).scrollTop();

				if ( itemOffset < ( currentScrollTop + currentViewportHeight * 0.5 ) ) {
					$(this).addClass('is--active');
				} else {
					$(this).removeClass('is--active');
				}
			});
		};

		self.updateFrame = function() {
			if ( ! flag ) {
				requestAnimationId = requestAnimationFrame( self.updateWindow );
			}
			flag = true;
		};

		self.animateCards = function() {
			$cards.each( function() {
				var itemOffset = $(this).offset().top;
				itemOffset = ! inPopup ? itemOffset : itemOffset + currentScrollTop - viewportOffset - $(window).scrollTop();

				if( itemOffset <= currentScrollTop + currentViewportHeight * 0.9 && $(this).hasClass('jet-timeline-item--animated') ) {
					$(this).addClass('is--show');
				}
			});
		};

		self.init = function(){
			$(document).ready(self.onScroll);
			$viewport.on('scroll.jetTimeline', self.onScroll);
			$(window).on('resize.jetTimeline orientationchange.jetTimeline', JetElementsTools.debounce( 50, self.onResize ));
		};
	}

	/**
	 * [jetScratchEffect description]
	 * @param  {[type]} elementId        [description]
	 * @param  {[type]} canvasId         [description]
	 * @param  {[type]} completeCallback [description]
	 * @return {[type]}                  [description]
	 */
	window.jetScratchEffect = function ( elementId, canvasId, completeCallback, fillpercent = 75 ) {
		var container    = document.querySelector( elementId ),
			canvas       = document.querySelector( canvasId ),
			canvasWidth  = canvas.width,
			canvasHeight = canvas.height,
			ctx          = canvas.getContext('2d'),
			brush        = new Image(),
			isDrawing = false,
			lastPoint;

			brush.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAAAxCAYAAABNuS5SAAAKFklEQVR42u2aCXCcdRnG997NJtlkk83VJE3apEma9CQlNAR60UqrGSqW4PQSO9iiTkE8BxWtlGMqYCtYrLRQtfVGMoJaGRFliijaViwiWgQpyCEdraI1QLXG52V+n/5nzd3ENnX/M8/sJvvt933/533e81ufL7MyK7NOzuXPUDD0FQCZlVn/+xUUQhkXHny8M2TxGsq48MBjXdAhL9/7YN26dd5nI5aVRrvEc0GFEBNKhbDjwsHh3qP/FJK1EdYIedOFlFAOgREhPlICifZDYoBjTna3LYe4xcI4oSpNcf6RvHjuAJRoVszD0qFBGmgMChipZGFxbqzQkJWVZUSOF7JRX3S4LtLTeyMtkkqljMBkPzHRs2aYY5PcZH/qLY1EIo18byQ6hBytIr3WCAXcV4tQHYvFxg3w3N6+Bh3OQolEoqCoqCinlw16JzTFJSE6PYuZKqvztbC2ex7bzGxhKu+rerjJrEEq+r9ieElJSXFDQ0Mh9zYzOzu7FBUWcO4Q9xbD6HYvhXhGLccVD5ZAPyfMqaioyOrBUgEv8FZXV8caGxtz8vLykhCWTnZIKmsKhUJnEYeKcKk2YYERH41G7UYnck1/WvAPOxsdLJm2+bEY0Ay0RNeqkytXQkoBZM4U5oOaoYSUkBGRtvnesrBZK4e4F6ypqSkuLy+v4KI99ZQxkfc6vZ4jNAl1wkbhG8LrhfNBCdkxmhYacvj/GOce+3K9MHHbDHUmicOufREELRIWch/DljzMsglutr+VIJO5KjGrVfZAnpF8mnCd8G5hrnC60Cl8T/iw8C1hKd9P9eDCMcgo5HwBx8BB/g7xeRPkrBbeJ3xTeAxjvRGVV3NcshfPG1JX4tVDQae47GuVOknCi23xHr5nyrxe2C1sFlYJ7xe+Jlwm7BRulItP0ms957RzTMK1ws41jMS8eDxehopaOCYfxc3AIHcIX+K6nxW+ImyVF1i8PQ8DTuwtdC1atCja3NwcHkq5EuXmo85G+jq+yMm28V4q/zcIPxV+K9zPxnbgTi0ocybu6wX66fx/vfAB4T1gHt8xI1wlXMF5zEXnQKC56ruEjwhvEa4WrrXvK/Yt5Pt5I1UveeVKyKmT+lpG2gQ2npMmez8ZzFT3e+HXwj7hKXNf6rFZbDpJUjESLdFsFX4mfFv4Fd/7qPBm4UPCJ4RNwncwym4UfYVUtiAcDk/T+3NRmylwWzAY7BCBCwYYogZPnrJoRNm2IDc3tw4FVKXFm95UmGLzkTTFpog524WnhQPCQeGvwiPCCuFCYmk5GbEJt3tOeF54HPVeLLyXxHOv8BPhYaFLeFU4gsI7OWeZk3g+hpJNvVMGIIqhdRvy+biVISouq2TBqWxoIL1wgBhU5AR1SzJvFR4UnhX+Bl4RfsFGP0npUkTymIQ7fh8Cf4l6F0LgXkj6o3O+buGfwj+ElzGQETaNeJqPhxiahckYq8KJ9V6mP+4pTIATjsGCA8lCQVy9VbhB2CM8itu9IBxlkx6O4nbmmpcSi0KUExa3Psfn23DZC4lhlhRuIWs/R1Y9BrpR4WHcfiOq34bLl5DJm1B7BANPGO4+2OJfDcVwX+RZkL5d+DRqeRJ360IJx1CFp4w/8/lhVGXxay1xKp8asQ31rSbgz2az1aBBWCZsgKTfEFe7uM4xYus9KHWXcBv3eolwJe67hJLIN6yubMVpW1tbbllZWVxtzjRquvQe9981IG3RZHUQttH7hB8IP0cdLwp/YnNHcdsjEP1xsEruO56i2Fy3UWXMskAgYAH/EjOiCD6NDc/XZ4v12RqSy3WQ9rJD3jPClwkZz2Aoy8JnUEjPcwYWfgfHvcIW84h308mABQP4Xp02OY44M4tSZSfx7UXIewU3NpXuxw0vJzauYDP1XM8y8Ttx67fhylYrdlAMW1x7h/BF3NWI+4PwFwjbSha26/xQuBmib6HDqeI+m4m5wzrj9A/xO+O5qbm4yizcbDOKfAjVWeC/WzAFLSeI+4hN9WzQ65EvED7D8Tt4vwE33O64rIfD1JW3k6xeQoX3UN6chyG8In4tcbHuRAyKw2ktVIIM2U5XcA7t2FKy5vWQeBexbbrTpvmZiJwN6e3EwKspW/ajqBuAKfKQk8m7KIce5bgnMNQDkLWPUmkj511DSVV5HJOd417FzrDAK7RjZLMZiURigmLVFCYs5tI2PFhpcUj/n6z6sp72LwJKiU2rUdp62rA7IX4XytpJ3Weh4XfE1/0kk/uoFX8kbCHudZLld5E8vJIs2+mbT8iznaR60DHMBt0EE1DySVlSsOBvyrL6zkZG5qI2T/QSBYTHMYAlq2tw1+0MFO4kVj5GSbSbgvkA8fQQr1uIdfdD5mZ1GhZbP0XfuwlPmOp0SNkYbkQV2JdlEsq69VJS+rTER+NtZVC+TX+NRFq1XGeiHXbGUHMg6lk2/DiZ+mHU8wTueoTXLtS3F5e9l2PNZW9lyrOB5LGSmJokzMQ6OjqCA3wsMXLLhqrWoZgKe3lyZ5YtLiwsLLfMLhJL0ibW3rKa7oMQ+Ajq6gKHcMeHeP8qZcpRMvyt1J97SRabcNP1ZGsbKhSb6lF+5GR6shUnlqTSyPM7LZxV/PUqjOfTH6cvqx+XyN3aCfBPUWh3UZIcxC2/jgu/BJ7Eve/G1R/EXS9gaLCc0dgySqIm7jV4MhEYdAaN4R4eRHkBusJp3GNp56iSOscyYN0DaUch8Ai13X6yrg0PvotCO8nme0geKymBaulc1qO+NbxOOpHZtrcHR+nT6+wePvcnk8k8qv6iNBdyH4/OoGR5gXbv75D4NIX3NoruLSjtKmLlbTwCKER1NmV+QIqfS13aai0izUHsRKksAQE5g0w4fuehj9f+xb25Ym1tbcIhuw2COmkBn2cAcQAFbsclV1BTns49JZio3EQWPkgCySJpFIu8aor0UfeLigDTlUTa/8eimhRGuUiKOZPYtYNabh9EGik3Mkk+A9I8JTWoAiik/LEpzY8tY4uwWc4AJMjxQd8oXRHU8JqbW32orNyAiubZo0WR5wX9KyHrLpLD52nrxhFHa1CVV5w3081cRu/7BYichpEqfafA7/sCzhT7tVkhLZvhTeB8Gv1r6U+ty/gqtWHQCSNTcPOl9NmXM1S4hgRjBjjL1MdUJ8cx3uhe3d3dfh5Meb8qyKWsuJRidwtN/h20XEtxvTwya7tKncU8ACqmXVwLict5fy6TnFhra2uW7xT8dWk2BHptVBOx8GLKjo3g7bhrBQq1sdVsCvEkhLZIac1y/zmUSO0oO8fX/0P2Ub3cwaWpZSITnLnOpDlBWTIfMleJqFb10jXCBJUlMyORSIP14LhqNef6v/05bpZTdHulUyXKsufDNdRxZ4vIhSKwhQFG5vfLfcwZsx2X92Jhje8/P8OI+TK/oO+zeA84WTzkvI/6RuB3y6f68qf11xnyMiuzMms4178AwArmZmkkdGcAAAAASUVORK5CYII=';

			canvas.addEventListener( 'mousedown', handleMouseDown, false );
			canvas.addEventListener( 'mousemove', JetElementsTools.debounce( 5, handleMouseMove ), false );
			canvas.addEventListener( 'mouseup', handleMouseUp, false );

			canvas.addEventListener( 'touchstart', handleMouseDown, false );
			canvas.addEventListener( 'touchmove', handleMouseMove, false );
			canvas.addEventListener( 'touchend', handleMouseUp, false );

		function distanceBetween( point1, point2 ) {
			return Math.sqrt( Math.pow( point2.x - point1.x, 2 ) + Math.pow( point2.y - point1.y, 2 ) );
		}

		function angleBetween( point1, point2 ) {
			return Math.atan2( point2.x - point1.x, point2.y - point1.y );
		}

		function getFilledInPixels( stride ) {

			if ( ! stride || stride < 1 ) {
				stride = 1;
			}

			var pixels   = ctx.getImageData(0, 0, canvasWidth, canvasHeight),
				pdata    = pixels.data,
				l        = pdata.length,
				total    = ( l / stride ),
				count    = 0;

			for( var i = count = 0; i < l; i += stride ) {
				if ( parseInt( pdata[i] ) === 0 ) {
					count++;
				}
			}

			return Math.round( ( count / total ) * 100 );
		}

		function getMouse( e, canvas ) {
			var offsetX = 0,
				offsetY = 0,
				mx,
				my;

			mx = ( e.pageX || e.touches[0].clientX ) - offsetX;
			my = ( e.pageY || e.touches[0].clientY ) - offsetY;

			return { x: mx, y: my };
		}

		function handlePercentage( filledInPixels ) {
			filledInPixels = filledInPixels || 0;

			if ( filledInPixels > fillpercent && completeCallback ) {
				completeCallback.call( canvas );
			}
		}

		function handleMouseDown( e ) {
			isDrawing = true;
			lastPoint = getMouse( e, canvas );
		}

		function handleMouseMove( e ) {

			if ( ! isDrawing ) {
				return;
			}

			e.preventDefault();

			var currentPoint      = getMouse( e, canvas ),
				dist              = distanceBetween( lastPoint, currentPoint ),
				angle             = angleBetween( lastPoint, currentPoint ),
				x                 = 0,
				y                 = 0,
				userAgent         = navigator.userAgent || navigator.vendor || window.opera,
				isIos             = /iPad|iPhone|iPod/.test(userAgent) && !window.MSStream,
				isMobileDevice    = JetElementsTools.mobileAndTabletcheck(),
				yScroll           = ( isMobileDevice && !isIos ) ? window.scrollY : 0;

			for ( var i = 0; i < dist; i++ ) {
				x = lastPoint.x + ( Math.sin( angle ) * i ) - 40;
				y = lastPoint.y + ( Math.cos( angle ) * i ) - 40 + yScroll;
				ctx.globalCompositeOperation = 'destination-out';
				ctx.drawImage( brush, x, y, 80, 80 );
			}

			lastPoint = currentPoint;

			handlePercentage( getFilledInPixels( 32 ) );
		}

		function handleMouseUp( e ) {
			isDrawing = false;
		}
	}

}( jQuery, window.elementorFrontend ) );

