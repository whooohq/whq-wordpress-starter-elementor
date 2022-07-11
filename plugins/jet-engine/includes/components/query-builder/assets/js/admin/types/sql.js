(function( $ ) {

	'use strict';

	Vue.component( 'jet-sql-query', {
		template: '#jet-sql-query',
		mixins: [
			window.JetQueryWatcherMixin,
			window.JetQueryRepeaterMixin,
		],
		props: [ 'value', 'dynamic-value' ],
		data: function() {
			return {
				tablesList: window.jet_query_component_sql.tables,
				operators: window.JetEngineQueryConfig.operators_list,
				dataTypes: window.JetEngineQueryConfig.data_types,
				query: {},
				dynamicQuery: {},
			};
		},
		created: function() {

			this.query = { ...this.value };
			this.dynamicQuery = { ...this.dynamicValue };

			this.presetJoin();
			this.presetWhere();
			this.presetOrder();
			this.presetCols()
		},
		computed: {
			availableColumns: function() {

				var result = [];

				if ( this.query.table ) {
					let columns = this.getColumns( this.query.table );
					result = JSON.parse( JSON.stringify( columns ) );
				}

				if ( this.query.use_join && this.query.join_tables.length ) {

					for (var i = 0; i < result.length; i++) {
						result[ i ].value = this.query.table + '.' + result[ i ].value;
						result[ i ].label = this.query.table + '.' + result[ i ].label;
					}

					let processedTables = { [ this.query.table ]: 1 };

					for (var i = 0; i < this.query.join_tables.length; i++) {

						let joinTable = this.query.join_tables[ i ].table;
						let preparedJoinTable = joinTable;

						if ( joinTable && processedTables[ joinTable ] ) {
							processedTables[ joinTable ]++;
							preparedJoinTable = joinTable + processedTables[ joinTable ];
						} else if ( joinTable ) {
							processedTables[ joinTable ] = 1;
						}

						if ( preparedJoinTable ) {

							let joinColumns = this.getColumns( joinTable );
								joinColumns = JSON.parse( JSON.stringify( joinColumns ) );

							for ( var j = 0; j < joinColumns.length; j++ ) {
								result.push( {
									value: preparedJoinTable + '.' + joinColumns[ j ].value,
									label: preparedJoinTable + '.' + joinColumns[ j ].label,
								} )
							}
						}

					}

				}

				return result;

			},
		},
		methods: {
			presetJoin: function() {
				if ( ! this.query.join_tables ) {
					this.$set( this.query, 'join_tables', [] );
				}

				if ( ! this.dynamicQuery.join_tables ) {
					this.$set( this.dynamicQuery, 'join_tables', {} );
				} else if ( 'object' !== typeof this.dynamicQuery.join_tables || undefined !== this.dynamicQuery.join_tables.length ) {
					this.$set( this.dynamicQuery, 'join_tables', {} );
				}
			},
			randID: function() {
				return Math.round( Math.random() * 1000000 )
			},
			newDynamicJoin: function( newClause, metaQuery, prevID ) {

				let newItem = {};

				if ( prevID && this.dynamicQuery.join_tables[ prevID ] ) {
					newItem = { ...this.dynamicQuery.join_tables[ prevID ] };
				}

				this.$set( this.dynamicQuery.join_tables, newClause._id, newItem );

			},
			deleteDynamicJoin: function( id ) {
				this.$delete( this.dynamicQuery.join_tables, id );
			},
			presetWhere: function() {
				if ( ! this.query.where ) {
					this.$set( this.query, 'where', [] );
				}

				if ( ! this.dynamicQuery.where ) {
					this.$set( this.dynamicQuery, 'where', {} );
				} else if ( 'object' !== typeof this.dynamicQuery.where || undefined !== this.dynamicQuery.where.length ) {
					this.$set( this.dynamicQuery, 'where', {} );
				}
			},
			presetCols: function() {
				if ( ! this.query.calc_cols ) {
					this.$set( this.query, 'calc_cols', [] );
				}
			},
			newDynamicWhere: function( newClause, metaQuery, prevID ) {

				let newItem = {};

				if ( prevID && this.dynamicQuery.where[ prevID ] ) {
					newItem = { ...this.dynamicQuery.where[ prevID ] };
				}

				this.$set( this.dynamicQuery.where, newClause._id, newItem );

			},
			deleteDynamicWhere: function( id ) {
				this.$delete( this.dynamicQuery.where, id );
			},
			getColumns: function( table ) {
				return window.jet_query_component_sql.columns[ table ] || [];
			},
			presetOrder: function() {
				if ( ! this.query.orderby ) {
					this.$set( this.query, 'orderby', [] );
				}
			},
		}
	} );

})( jQuery );
