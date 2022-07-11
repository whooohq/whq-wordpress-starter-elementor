(function( $, JetEngineCPTListConfig ) {

	'use strict';

	window.JetEngineCPTList = new Vue( {
		el: '#jet_cpt_list',
		template: '#jet-cpt-list',
		data: {
			errorNotices: [],
			editLink: JetEngineCPTListConfig.edit_link,
			builtInTypes: JetEngineCPTListConfig.built_in_types,
			engineTypes: JetEngineCPTListConfig.engine_types,
			showDeleteDialog: false,
			showTypes: 'jet-engine',
			deletedItem: {},
		},
		computed: {
			slugsList: function() {
				var result = [];

				for ( var i = 0; i < this.itemsList.length; i++ ) {
					result.push( this.itemsList[i].slug );
				}

				return result;
			},
			itemsList: function() {
				var result = [];

				if ( 'jet-engine' === this.showTypes ) {
					result = this.engineTypes;
				} else {
					result = this.builtInTypes;
				}

				return result;
			},
		},
		methods: {
			switchType: function() {
				if ( 'jet-engine' === this.showTypes ) {
					this.showTypes = 'built-in';
				} else {
					this.showTypes = 'jet-engine';
				}
			},
			copyItem: function( item ) {

				if ( !item ) {
					return;
				}

				var self = this,
					itemData = JSON.parse( JSON.stringify( item ) ),
					newSlug = itemData.slug + '_copy';

				itemData.slug = -1 === this.slugsList.indexOf( newSlug ) ? newSlug : newSlug + '_' + Math.floor( ( Math.random() * 99 )  + 1 );
				itemData.labels.name = itemData.labels.name + ' (Copy)';

				wp.apiFetch( {
					method: 'post',
					path: JetEngineCPTListConfig.api_path_add,
					data: {
						general_settings: {
							name: itemData.labels.name,
							slug: itemData.slug,
							object_type: itemData.object_type,
							show_edit_link: itemData.show_edit_link,
						},
						labels: itemData.labels,
						advanced_settings: itemData.args,
						meta_fields: itemData.meta_fields,
					}
				} ).then( function( response ) {

					if ( response.success && response.item_id ) {

						itemData.id = response.item_id

						self.itemsList.unshift( itemData );

						self.$CXNotice.add( {
							message: JetEngineCPTListConfig.notices.copied,
							type: 'success',
						} );

					} else {
						if ( response.notices.length ) {
							response.notices.forEach( function( notice ) {

								self.$CXNotice.add( {
									message: notice.message,
									type: 'error',
									duration: 7000,
								} );


							} );
						}
					}
				} ).catch( function( response ) {

					self.$CXNotice.add( {
						message: response.message,
						type: 'error',
						duration: 7000,
					} );

				} );
			},
			deleteItem: function( item ) {
				this.deletedItem      = item;
				this.showDeleteDialog = true;
			},
			getEditLink: function( id, slug ) {

				var editLink = this.editLink.replace( /%id%/, id );

				if ( 'built-in' === this.showTypes ) {
					editLink += '&edit-type=built-in&tax=' + slug;
				}

				return editLink;

			},
		}
	} );

})( jQuery, window.JetEngineCPTListConfig );
