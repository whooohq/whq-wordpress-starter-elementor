'use strict';

let jetWooBuilderSettingsMixin = {
	data: function() {
		return {
			pageOptions: window.jetWooBuilderSettingsConfig.settingsData,
			preparedOptions: {},
			savingStatus: false,
			ajaxSaveHandler: null,
		};
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
		saveOptions: function() {
			let self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				url: window.jetWooBuilderSettingsConfig.settingsApiUrl,
				dataType: 'json',
				data: self.preparedOptions,
				beforeSend: function( jqXHR, ajaxSettings ) {

					if ( null !== self.ajaxSaveHandler ) {
						self.ajaxSaveHandler.abort();
					}
				},
				success: function( response, textStatus, jqXHR ) {
					self.savingStatus = false;

					if ( 'success' === response.status ) {
						self.$CXNotice.add( {
							message: response.message,
							type: 'success',
							duration: 3000,
						} );
					}

					if ( 'error' === response.status ) {
						self.$CXNotice.add( {
							message: response.message,
							type: 'error',
							duration: 3000,
						} );
					}
				}
			} );
		},
	}
}

Vue.component( 'jet-woo-builder-general-settings', {
	template: '#jet-dashboard-jet-woo-builder-general-settings',
	mixins: [ jetWooBuilderSettingsMixin ],
} );

Vue.component( 'jet-woo-builder-available-addons', {
	template: '#jet-dashboard-jet-woo-builder-available-addons',
	mixins: [ jetWooBuilderSettingsMixin ],
} );
