'use strict';

let jetMenuSettinsMixin = {
	data: function() {
		return {
			pageOptions: window.JetMenuOptionsPageConfig.optionsData,
			preparedOptions: {},
			savingStatus: false,
			savingSuccessReload: false,
			ajaxSaveHandler: null,
			debonceSavingInterval: null,
			optionPresetList: window.JetMenuOptionsPageConfig.optionPresetList,
			arrowsIcons: window.JetMenuOptionsPageConfig.arrowsIcons,
			exportUrl: window.JetMenuOptionsPageConfig.exportUrl,
			iconSet: [],

			newPresetName: '',
			creatingState: false,
			updatePresetId: '',
			updatingState: false,
			applyPresetId: '',
			applyState: false,
			removePresetId: '',
			removingState: false,
			ajaxPresetAction: null,
			presetManagerVisible: false,
			importVisible: false,
			resetCheckPopup: false,
			importState: false,
		};
	},

	mounted: function() {
		let self = this;

		let prepared = {};

		for ( let option in this.pageOptions ) {

			if ( this.pageOptions.hasOwnProperty( option ) ) {
				prepared[ option ] = this.pageOptions[ option ]['value'];
			}
		}

		//this.preparedOptions.optionPresetList = prepared;
		this.preparedOptions = prepared;

		// Get icons set
		fetch( window.JetMenuOptionsPageConfig.iconsFetchJson, {
			mode: 'cors'
		} ).then(function ( res ) {
			return res.json();
		} ).then(function (json) {
			self.iconSet = json.icons;
		} );
	},

	watch: {
		pageOptions: {
			handler( options ) {
				let prepared = {};

				for ( let option in options ) {

					if ( options.hasOwnProperty( option ) ) {
						prepared[ option ] = options[ option ]['value'];
					}
				}

				this.preparedOptions = prepared;

				clearInterval( this.debonceSavingInterval );

				this.debonceSavingInterval = setTimeout( this.saveOptions, 500 );
			},
			deep: true
		}
	},

	methods: {

		getOptionValue: function( option = false ) {

			if ( this.pageOptions.hasOwnProperty( option ) ) {
				return this.pageOptions[ option ]['value'];
			}

			return false;
		},

		openPresetManager: function() {
			this.presetManagerVisible = true;
		},

		createPreset: function() {
			let self = this;

			this.ajaxPresetAction = jQuery.ajax( {
				type: 'POST',
				url: ajaxurl,
				dataType: 'json',
				data: {
					action: 'jet_menu_create_preset',
					name: self.newPresetName,
					settings: self.preparedOptions
				},
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== self.ajaxPresetAction ) {
						self.ajaxPresetAction.abort();
					}

					self.creatingState = true;
				},
				success: function( responce, textStatus, jqXHR ) {
					self.creatingState = false;

					self.$CXNotice.add( {
						message: responce.data.message,
						type: responce.success ? 'success' : 'error',
						duration: 4000,
					} );

					if ( responce.success ) {
						self.optionPresetList = responce.data.presets;
					}
				}
			} );
		},

		updatePreset: function() {

			let self = this;

			this.ajaxPresetAction = jQuery.ajax( {
				type: 'POST',
				url: ajaxurl,
				dataType: 'json',
				data: {
					action: 'jet_menu_update_preset',
					preset: self.updatePresetId,
					settings: self.preparedOptions
				},
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== self.ajaxPresetAction ) {
						self.ajaxPresetAction.abort();
					}

					self.updatingState = true;
				},
				success: function( responce, textStatus, jqXHR ) {
					self.updatingState = false;

					self.$CXNotice.add( {
						message: responce.data.message,
						type: responce.success ? 'success' : 'error',
						duration: 4000,
					} );
				}
			} );
		},

		applyPreset: function() {

			let self = this;

			this.ajaxPresetAction = jQuery.ajax( {
				type: 'POST',
				url: ajaxurl,
				dataType: 'json',
				data: {
					action: 'jet_menu_load_preset',
					preset: self.applyPresetId,
				},
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== self.ajaxPresetAction ) {
						self.ajaxPresetAction.abort();
					}

					self.applyState = true;
				},
				success: function( responce, textStatus, jqXHR ) {
					self.applyState = false;

					self.$CXNotice.add( {
						message: responce.data.message,
						type: responce.success ? 'success' : 'error',
						duration: 4000,
					} );

					if ( responce.success ) {
						self.pageOptions = responce.data.settings;

						location.reload();
					}
				}
			} );
		},

		removePreset: function() {

			let self = this;

			this.ajaxPresetAction = jQuery.ajax( {
				type: 'POST',
				url: ajaxurl,
				dataType: 'json',
				data: {
					action: 'jet_menu_delete_preset',
					preset: self.removePresetId,
				},
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== self.ajaxPresetAction ) {
						self.ajaxPresetAction.abort();
					}

					self.removingState = true;
				},
				success: function( responce, textStatus, jqXHR ) {
					self.removingState = false;

					self.$CXNotice.add( {
						message: responce.data.message,
						type: responce.success ? 'success' : 'error',
						duration: 4000,
					} );

					if ( responce.success ) {
						self.optionPresetList = responce.data.presets;
					}
				}
			} );
		},

		saveOptions: function() {

			var self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				url: window.JetMenuOptionsPageConfig.optionsApiUrl,
				dataType: 'json',
				data: self.preparedOptions,
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== self.ajaxSaveHandler ) {
						self.ajaxSaveHandler.abort();
					}
				},
				success: function( responce, textStatus, jqXHR ) {
					self.savingStatus = false;

					if ( 'success' === responce.status ) {

						if ( self.savingSuccessReload ) {
							window.location.reload( false );
						}

						self.savingSuccessReload = false;

						self.$CXNotice.add( {
							message: responce.message,
							type: 'success',
							duration: 3000,
						} );
					}

					if ( 'error' === responce.status ) {
						self.$CXNotice.add( {
							message: responce.message,
							type: 'error',
							duration: 3000,
						} );
					}
				}
			} );
		},

		importOptions: function() {
			let $this      = jQuery( this ),
				$fileInput = jQuery( this.$refs['import-file-input'] ) || false,
				file       = $fileInput.val() || false,
				self       = this;

			if ( ! file ) {
				return;
			}

			let fileExt = file.split('.').pop().toLowerCase();

			if ( 'json' !== fileExt ) {
				this.$CXNotice.add( {
					message: 'File wrong format',
					type: 'error',
					duration: 3000,
				} );

				return;
			}

			let fileToLoad = $fileInput[0].files[0],
				fileReader = new FileReader();

			fileReader.onload = function( fileLoadedEvent ) {

				var textFromFileLoaded = fileLoadedEvent.target.result;

				this.ajaxPresetAction = jQuery.ajax( {
					type: 'POST',
					url: ajaxurl,
					dataType: 'json',
					data: {
						action: 'jet_menu_import_options',
						data: JSON.parse( textFromFileLoaded ),
					},
					beforeSend: function( jqXHR, ajaxSettings ) {

						if ( null !== self.ajaxPresetAction ) {
							self.ajaxPresetAction.abort();
						}

						self.importState = true;
					},
					success: function( responce, textStatus, jqXHR ) {
						self.importState = false;

						self.$CXNotice.add( {
							message: responce.data.message,
							type: responce.success ? 'success' : 'error',
							duration: 4000,
						} );

						location.reload();
					}
				} );

			};

			fileReader.readAsText( fileToLoad, 'UTF-8' );
		},

		resetOptions: function() {
			window.location.href = window.JetMenuOptionsPageConfig.resetUrl;
		}
	}
}

Vue.component( 'jet-menu-general-settings', {
	template: '#jet-dashboard-jet-menu-general-settings',
	mixins: [ jetMenuSettinsMixin ],
	methods: {
		nextgenEditionTrigger: function ( value ) {
			this.savingSuccessReload = true;
		}
	}
} );

Vue.component( 'jet-menu-desktop-menu-settings', {
	template: '#jet-dashboard-jet-menu-desktop-menu-settings',
	mixins: [ jetMenuSettinsMixin ],
} );

Vue.component( 'jet-menu-main-menu-settings', {
	template: '#jet-dashboard-jet-menu-main-menu-settings',
	mixins: [ jetMenuSettinsMixin ],
} );

Vue.component( 'jet-menu-mobile-menu-settings', {
	template: '#jet-dashboard-jet-menu-mobile-menu-settings',
	mixins: [ jetMenuSettinsMixin ],
} );
