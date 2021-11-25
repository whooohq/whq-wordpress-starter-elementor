(function( $, navSettingsConfig ) {

	'use strict';

	Vue.config.devtools = true;

	window.JetMenuNavSettings = {

		navSettingsInstance: null,

		navItemsSettingsInstance: null,

		init: function() {
			this.initNavSettingsInstance();
			this.initNavItemsSettingsInstance();
			this.initTriggers();
			this.initEvents();
		},

		initEvents: function() {
			$( document ).on( 'click.jetMenuAdmin', '.jet-menu-item-settings__trigger', this.openItemSettingPopup );
		},

		initTriggers: function() {

			let itemsSettings = navSettingsConfig.itemsSettings;

			$( '#menu-to-edit .menu-item' ).each( function() {
				let $this           = $( this ),
					depth           = JetMenuNavSettings.getItemDepth( $this ),
					id              = JetMenuNavSettings.getItemId( $this ),
					infoTemplate    = '';

				$this.addClass( 'jet-menu-item' );

				if ( itemsSettings.hasOwnProperty( id ) && itemsSettings[ id ].hasOwnProperty( 'enabled' ) ) {
					if ( 'true' === itemsSettings[ id ][ 'enabled' ] ) {
						infoTemplate = `<span class="jet-menu-item-settings__info-label mega-enabled">${ navSettingsConfig.labels.itemMegaEnableLabel }</span>`;
					}
				}

				let triggerTemplate = `<span class="jet-menu-item-settings__trigger" data-item-id="${ id }" data-item-depth="${ depth }">${ navSettingsConfig.labels.itemTriggerLabel }</span>`;

				//$this.find( '.item-title' ).append( `<span class="jet-menu-item-settings"><span class="jet-menu-item-settings__info">${ infoTemplate }</span>${ triggerTemplate }</span>` );
				$this.append( `<span class="jet-menu-item-settings"><span class="jet-menu-item-settings__info">${ infoTemplate }</span>${ triggerTemplate }</span>` );
			});

		},

		initNavSettingsInstance: function() {

			if ( ! $( '#jet-menu-settings-fields' )[0] ) {
				return;
			}

			this.navSettingsInstance = new Vue( {
				el: '#jet-menu-settings-fields',

				data: {
					debonceSavingInterval: null,
					ajaxAction: null,
					savingState: false,
					navSettings: navSettingsConfig,
					locationSettings: navSettingsConfig.locationSettings,
					optionPresetList: navSettingsConfig.optionPresetList,
					optionMenuList: navSettingsConfig.optionMenuList,
				},

				mounted: function() {
					this.$el.className = 'is-mounted';
				},

				watch: {
					locationSettings: {
						handler( options ) {
							clearInterval( this.debonceSavingInterval );
							this.debonceSavingInterval = setTimeout( this.saveSettings, 100 );
						},
						deep: true
					}
				},

				computed: {
					isPresetsVisible: function() {
						return 0 !== this.optionPresetList.length;
					},

					isMobileLayoutVisible: function() {
						return 1 < this.optionMenuList.length;
					},
				},

				methods: {
					saveSettings: function() {
						let self = this;

						this.ajaxAction = $.ajax( {
							type: 'POST',
							url: ajaxurl,
							dataType: 'json',
							data: {
								action: 'jet_save_settings',
								data: {
									menuId: self.navSettings.currentMenuId,
									settings: self.locationSettings
								}
							},
							beforeSend: function( jqXHR, ajaxSettings ) {

								if ( null !== self.ajaxAction ) {
									self.ajaxAction.abort();
								}

								self.savingState = true;
							},
							success: function( responce, textStatus, jqXHR ) {
								self.savingState = false;

								self.$CXNotice.add( {
									message: responce.data.message,
									type: responce.success ? 'success' : 'error',
									duration: 4000,
								} );

							}
						} );
					}
				}
			} );
		},

		initNavItemsSettingsInstance: function() {

			this.navItemsSettingsInstance = new Vue( {
				el: '#jet-menu-settings-nav',

				data: {
					navSettings: navSettingsConfig,
					controlData: navSettingsConfig.controlData,
					debonceSavingInterval: null,
					ajaxAction: null,
					getItemDataState: false,
					itemSavingState: false,
					itemSettingItem: false,
					itemId: false,
					itemDepth: 0,
					editorVisible: false,
					iconSet: [],
				},

				mounted: function() {
					let self = this;

					// Get icons set
					fetch( self.navSettings.iconsFetchJson, {
						mode: 'cors'
					} ).then( function( res ) {
						return res.json();
					} ).then( function( json ) {
						self.iconSet = json.icons;
					} );
				},

				watch: {
					itemId: function( newValue, prevValue ) {
						this.getItemData();
					}
				},

				computed: {
					preparedItemSettings: function() {
						let prepared = {};

						for ( let option in this.controlData ) {
							prepared[ option ] = this.controlData[ option ]['value'];
						}

						return prepared;
					},

					currentEditorUrl: function() {
						let url = '';

							url = this.navSettings.editURL.replace( '%id%', this.itemId );
							url = url.replace( '%menuid%', this.navSettings.currentMenuId );

						return url;
					},

					isTopItem: function() {
						return 0 === this.itemDepth;
					},

					defaultActiveTab: function() {
						//return 0 === this.itemDepth ? 'mega-menu-tab' : 'icon-tab';
						return 'mega-menu-tab';
					}

				},

				methods: {

					openEditor: function() {
						this.editorVisible = true;
					},

					navSettingPopupClose: function() {
						this.itemSettingItem = true;

						if ( this.editorVisible ) {
							this.editorVisible = false;
						} else {
							this.itemSettingItem = false;
						}
					},

					getItemData: function() {

						let self = this;

						this.ajaxAction = $.ajax( {
							type: 'POST',
							url: ajaxurl,
							dataType: 'json',
							data: {
								action: 'jet_get_nav_item_settings',
								data: {
									itemId: self.itemId,
									itemDepth: self.itemDepth,
								}
							},
							beforeSend: function( jqXHR, ajaxSettings ) {

								if ( null !== self.ajaxAction ) {
									self.ajaxAction.abort();
								}

								self.getItemDataState = true;
							},
							success: function( responce, textStatus, jqXHR ) {
								self.getItemDataState = false;

								if ( responce.success ) {
									let responseSettings = responce.data.settings,
										newControlData   = self.controlData;

									for ( let setting in responseSettings ) {
										self.$set( self.controlData[ setting ], 'value', responseSettings[ setting ] );
									}
								}
							}
						} );
					},

					saveItemSettings: function() {
						let self = this;

						this.ajaxAction = $.ajax( {
							type: 'POST',
							url: ajaxurl,
							dataType: 'json',
							data: {
								action: 'jet_save_nav_item_settings',
								data: {
									itemId: self.itemId,
									itemDepth: self.itemDepth,
									itemSettings: self.preparedItemSettings
								}
							},
							beforeSend: function( jqXHR, ajaxSettings ) {

								if ( null !== self.ajaxAction ) {
									self.ajaxAction.abort();
								}

								self.itemSavingState = true;
							},
							success: function( responce, textStatus, jqXHR ) {
								self.itemSavingState = false;

								self.$CXNotice.add( {
									message: responce.data.message,
									type: responce.success ? 'success' : 'error',
									duration: 4000,
								} );

							}
						} );
					}
				}
			} );
		},

		openItemSettingPopup: function() {
			let $this   = $( this ),
				itemId      = $this.data( 'item-id' ),
				itemDepth   = $this.data( 'item-depth' );

			JetMenuNavSettings.navItemsSettingsInstance.$data.itemSettingItem = true;
			JetMenuNavSettings.navItemsSettingsInstance.$data.itemId = itemId;
			JetMenuNavSettings.navItemsSettingsInstance.$data.itemDepth = itemDepth;
		},

		getItemId: function( $item ) {
			let id = $item.attr( 'id' );

			return id.replace( 'menu-item-', '' );
		},

		getItemDepth: function( $item ) {
			let depthClass = $item.attr( 'class' ).match( /menu-item-depth-\d/ );

			if ( ! depthClass[0] ) {
				return 0;
			}

			return depthClass[0].replace( 'menu-item-depth-', '' );
		},

		oneOf: function( value, validList ) {

			for ( let i = 0; i < validList.length; i++ ) {
				if ( value == validList[ i ] ) {
					return true;
				}
			}

			return false;
		}
	}

	window.JetMenuNavSettings.init();

})( jQuery, window.JetMenuNavSettingsConfig );
