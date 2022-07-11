'use strict';

let jetSmartFiltersSettinsMixin = {
	data: function() {
		return {
			pageOptions: window.jetSmartFiltersSettingsConfig.settingsData,
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
			var self = this;

			self.savingStatus = true;

			self.ajaxSaveHandler = jQuery.ajax( {
				type: 'POST',
				url: window.jetSmartFiltersSettingsConfig.settingsApiUrl,
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
}

Vue.component( 'jet-smart-filters-general-settings', {
	template: '#jet-dashboard-jet-smart-filters-general-settings',
	mixins: [ jetSmartFiltersSettinsMixin ],
} );

Vue.component( 'jet-smart-filters-indexer-settings', {
	template: '#jet-dashboard-jet-smart-filters-indexer-settings',
	mixins: [ jetSmartFiltersSettinsMixin ],
} );

Vue.component( 'jet-smart-filters-url-structure-settings', {
	template: '#jet-dashboard-jet-smart-filters-url-structure-settings',
	mixins: [ jetSmartFiltersSettinsMixin ],
} );

Vue.component( 'jet-smart-filters-ajax-request-type', {
	template: '#jet-dashboard-jet-smart-filters-ajax-request-type',
	mixins: [ jetSmartFiltersSettinsMixin ],
} );