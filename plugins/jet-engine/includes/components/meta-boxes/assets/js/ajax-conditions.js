( function( $ ) {

	'use strict';

	class AjaxConditions {

		constructor() {
			this.timeout = null;
			this.init();
		}

		observeDom( obj, callback ) {

			var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

			if ( !obj || obj.nodeType !== 1 ) {
				return;
			}

			if ( MutationObserver ) {
				// define a new observer
				var mutationObserver = new MutationObserver( callback )

				// have the observer observe foo for changes in children
				mutationObserver.observe( obj, { childList:true, subtree:true })
			}
		}

		init() {
			for ( const condition in window.JetEnginMBAjaxConditionsHandlers ) {
				if ( 'function' === typeof window.JetEnginMBAjaxConditionsHandlers[ condition ] ) {
					window.JetEnginMBAjaxConditionsHandlers[ condition ]( jQuery );
				}
			}

			$( document ).on( 'jet-engine/meta-box/data-change', ( data ) => {
				this.updateData( data );
			} );

			$( document ).trigger( 'jet-engine/meta-box/data-change' );

			var tagInputs = document.querySelectorAll( '.tagchecklist' );

			for ( var i = 0; i < tagInputs.length; i++ ) {
				this.observeDom( tagInputs[ i ], function( mutationsList, observer ) {
					$( document ).trigger( 'jet-engine/meta-box/data-change' );
				} );
			}
		}

		updateData( data ) {

			var ajaxRequest = {
				action: 'jet-engine/meta-box/update-conditions',
				conditions: this.getActiveConditions(),
			}

			ajaxRequest.terms = this.getTerms();
			ajaxRequest.template = this.getTemplate();

			ajaxRequest.nonce = window.JetEnginMBAjaxConditionsSettings.nonce;

			$.ajax({
				url: window.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: ajaxRequest,
			}).done( function( response ) {
				if ( response.success ) {
					for ( var i = 0; i < response.data.length; i++) {
						$( '#' + response.data[ i ].id ).css( 'display', response.data[ i ].display );

						// Prevent js error if meta box has required fields.
						if ( 'none' === response.data[i].display ) {

							$( '#' + response.data[i].id )
								.find( '[required]' )
								.removeAttr( 'required' )
								.attr( 'data-required', 1 );

						} else {

							$( '#' + response.data[i].id )
								.find( '[data-required="1"]' )
								.removeAttr( 'data-required' )
								.attr( 'required', true );

						}
					}
				}
			} ).fail( function( jqXHR, textStatus, errorThrown ) {
				alert( errorThrown );
			} );

		}

		/**
		 * Based on similar function from ACF plugin
		 */
		buildObject( obj, name, value ){

			// replace [] with placeholder
			name = name.replace( '[]', '[%%index%%]' );

			// vars
			var keys = name.match(/([^\[\]])+/g);

			if( ! keys ) {
				return;
			}

			var length = keys.length;
			var ref = obj;

			// loop
			for( var i = 0; i < length; i++ ) {

				// vars
				var key = String( keys[i] );

				// value
				if( i == length - 1 ) {

					// %%index%%
					if( key === '%%index%%' ) {
						ref.push( value );

					// default
					} else {
						ref[ key ] = value;
					}

				// path
				} else {

					// array
					if( keys[i+1] === '%%index%%' ) {
						if( ! Array.isArray(ref[ key ]) ) {
							ref[ key ] = [];
						}

					// object
					} else {
						if ( typeof ref[ key ] !== 'object' ) {
							ref[ key ] = {};
						}
					}

					// crawl
					ref = ref[ key ];
				}
			}
		}

		/**
		 * Based on similar function from ACF plugin
		 */
		serialize( $el, prefix ){

			// vars
			var obj = {};
			var inputs = $el.find( 'select, textarea, input' ).serializeArray();

			// prefix
			if ( prefix !== undefined ) {

				// filter and modify
				inputs = inputs.filter( function( item ){
					return item.name.indexOf( prefix ) === 0;
				}).map(function( item ){
					item.name = item.name.slice( prefix.length );
					return item;
				});
			}

			for ( var i = 0; i < inputs.length; i++ ) {
				this.buildObject( obj, inputs[i].name, inputs[i].value );
			}

			// return
			return obj;
		}

		getTerms() {

			// vars
			var terms = {};

			var data = this.serialize( $( '.categorydiv, .tagsdiv' ) );

			if ( data.tax_input ) {
				terms = data.tax_input;
			}

			// append "category" which uses a different name
			if ( data.post_category ) {
				terms.category = data.post_category;
			}

			// convert any string values (tags) into array format
			for ( var tax in terms ) {
				if( ! Array.isArray( terms[ tax ] ) ) {
					terms[ tax ] = terms[ tax ].split( /,[\s]?/ );
				}
			}

			return terms;

		}

		getTemplate() {
			var $el = $( '#page_template' );
			return $el.length ? $el.val() : null;
		}

		getActiveConditions() {
			return window.JetEnginMBAjaxConditionsData;
		}

		debounce( callback, wait ) {

			var self = this;

			return () => {

				var context = this;
				var args = arguments;

				var later = () => {
					this.timeout = null;
					clearTimeout( this.timeout );
					callback.apply( context, args );
				};

				if ( ! this.timeout ) {
					this.timeout = setTimeout( later, wait );
				}

			};
		}

	}

	class GutenAjaxConditions extends AjaxConditions {

		init() {
			wp.data.subscribe( this.debounce( this.updateData, 100 ).bind( this ) );
			this.updateData();
		}

		getTemplate() {
			return wp.data.select( 'core/editor' ).getEditedPostAttribute( 'template' );
		}

		getTerms() {

			var terms = {};

			// Loop over taxonomies.
			var taxonomies = wp.data.select( 'core' ).getTaxonomies( { per_page: -1 } ) || [];

			taxonomies.map( function( taxonomy ) {
				// Append selected taxonomies to terms object.
				var postTerms = wp.data.select( 'core/editor' ).getEditedPostAttribute( taxonomy.rest_base );
				if( postTerms ) {
					terms[ taxonomy.slug ] = postTerms;
				}
			});

			return terms;

		}

	}

	if ( window.wp && wp.data && wp.data.select && wp.data.select( 'core/editor' ) ) {
		new GutenAjaxConditions();
	} else {
		new AjaxConditions();
	}

} )( jQuery );
