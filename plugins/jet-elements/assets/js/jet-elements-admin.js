(function( $, settingsPageConfig ) {

	'use strict';

	Vue.config.devtools = true;

	if ( $( '#jet-elements-settings-page ')[0] ) {
		window.JetElementsSettingsPage = new Vue( {
			el: '#jet-elements-settings-page',

			data: {
				pageOptions: settingsPageConfig.settingsData,
				preparedOptions: {},
				savingStatus: false,
				ajaxSaveHandler: null,
				disableAllWidgets: false
			},

			mounted: function() {
				for ( var slug in this.pageOptions['avaliable_widgets']['value'] ) {

					if ( 'true' === this.pageOptions['avaliable_widgets']['value'][slug] ) {
						this.disableAllWidgets = true;

						break;
					}
				}

				this.$el.className = 'is-mounted';
			},

			watch: {
				pageOptions: {
					handler( options ) {
						let prepared = {};

						for ( let option in options ) {

							if ( options.hasOwnProperty( option ) ) {
								prepared[ option ] = options[option]['value'];
							}
						}

						this.preparedOptions = prepared;

						this.saveOptions();
					},
					deep: true
				}
			},

			methods: {

				disableAllWidgetsEvent: function( state ) {

					if ( state ) {
						for ( var slug in this.pageOptions['avaliable_widgets']['value'] ) {
							this.pageOptions['avaliable_widgets']['value'][slug] = 'true';
						}
					} else {
						for ( var slug in this.pageOptions['avaliable_widgets']['value'] ) {
							this.pageOptions['avaliable_widgets']['value'][slug] = 'false';
						}
					}
				},

				saveOptions: function() {

					var self = this;

					self.savingStatus = true;

					self.ajaxSaveHandler = $.ajax( {
						type: 'POST',
						url: settingsPageConfig.settingsApiUrl,
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
			}
		} );
	}

})( jQuery, window.JetElementsSettingsPageConfig );
