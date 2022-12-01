import JetEngineRepeater from "components/repeater-control.js";

import {
	clone
} from '../../utils/utility';

const { __ } = wp.i18n;
const {
	registerBlockType
} = wp.blocks;

const {
	InspectorControls
} = wp.blockEditor;

const {
	PanelColor,
	IconButton,
	TextControl,
	TextareaControl,
	SelectControl,
	ToggleControl,
	PanelBody,
	RangeControl,
	CheckboxControl,
	ExternalLink,
	Disabled,
	G,
	Path,
	Circle,
	Rect,
	SVG,
	ServerSideRender
} = wp.components;

if ( -1 !== window.JetEngineListingData.activeModules.indexOf( 'calendar' ) ) {
	const GIcon = <SVG xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64" fill="none"><Rect x="1" y="10" width="44" height="44" rx="3" stroke="#162B40" strokeWidth="2" fill="white"></Rect><Path d="M1 13C1 11.3431 2.34315 10 4 10H42C43.6569 10 45 11.3431 45 13V28H1V13Z" fill="#4AF3BA" stroke="#162B40" strokeWidth="2"></Path><Path d="M11 7C11 6.44772 11.4477 6 12 6H13C13.5523 6 14 6.44772 14 7V13C14 13.5523 13.5523 14 13 14H12C11.4477 14 11 13.5523 11 13V7Z" fill="#162B40"></Path><Path d="M32 7C32 6.44772 32.4477 6 33 6H34C34.5523 6 35 6.44772 35 7V13C35 13.5523 34.5523 14 34 14H33C32.4477 14 32 13.5523 32 13V7Z" fill="#162B40"></Path></SVG>;

	const blockAttributes = window.JetEngineListingData.atts.listingCalendar;

	registerBlockType( 'jet-engine/listing-calendar', {
		title: __( 'Listing Calendar' ),
		icon: GIcon,
		category: 'layout',
		attributes: blockAttributes,
		className: 'jet-listing-calendar',
		edit: class extends wp.element.Component {

			constructor( props ) {

				if ( ! props.attributes._block_id ) {
					props.setAttributes( { _block_id: props.clientId } );
				}

				super( props );
			}

			render() {

				const props            = this.props;
				const attributes       = props.attributes;
				const listingOptions   = window.JetEngineListingData.listingOptions;
				const hideOptions      = window.JetEngineListingData.hideOptions;

				const metaTypes = [
					{
						value: 'CHAR',
						label: 'CHAR'
					},
					{
						value: 'NUMERIC',
						label: 'NUMERIC'
					},
					{
						value: 'BINARY',
						label: 'BINARY'
					},
					{
						value: 'DATE',
						label: 'DATE'
					},
					{
						value: 'DATETIME',
						label: 'DATETIME'
					},
					{
						value: 'DECIMAL',
						label: 'DECIMAL'
					},
					{
						value: 'SIGNED',
						label: 'SIGNED'
					},
					{
						value: 'UNSIGNED',
						label: 'UNSIGNED'
					}
				];

				const updateItem = function( item, key, value, prop ) {

					prop = prop || 'posts_query';

					const query = clone( props.attributes[ prop ] );
					const index = getItemIndex( item );
					const currentItem = query[ getItemIndex( item, prop ) ];

					if ( ! currentItem ) {
						return;
					}

					if ( 'object' === typeof key ) {
						for ( var _key in key ) {
							currentItem[_key] = key[_key];
						}
					} else {
						currentItem[ key ] = value;
					}

					query[ index ] = currentItem;

					props.setAttributes( { [ prop ]: query } );

				};

				const getItemIndex = function( item, prop ) {

					prop = prop || 'posts_query';

					return props.attributes[ prop ].findIndex( queryItem => {
						return queryItem == item;
					} );
				};

				return [
					props.isSelected && (
						<InspectorControls
							key={ 'inspector' }
						>
							<PanelBody title={ __( 'General' ) }>
								<SelectControl
									label={ __( 'Listing' ) }
									value={ attributes.lisitng_id }
									options={ listingOptions }
									onChange={ newValue => {
										props.setAttributes( { lisitng_id: newValue } );
									} }
								/>
								<SelectControl
									label={ __( 'Group posts by' ) }
									value={ attributes.group_by }
									options={ blockAttributes.group_by.options }
									onChange={ newValue => {
										props.setAttributes( { group_by: newValue } );
									} }
								/>
								{ 'meta_date' === attributes.group_by &&
									<TextControl
										type="text"
										label={ __( 'Meta field name' ) }
										help={ __( 'This field must contain date to group posts by. Works only if "Save as timestamp" option for meta field is active' ) }
										value={ attributes.group_by_key }
										onChange={ newValue => {
											props.setAttributes( { group_by_key: newValue } );
										} }
									/>
								}
								{ 'meta_date' === attributes.group_by && <ToggleControl
									label={ __( 'Allow multi-day events' ) }
									checked={ attributes.allow_multiday }
									onChange={ () => {
										props.setAttributes( { allow_multiday: ! attributes.allow_multiday } );
									} }
								/> }
								{ 'meta_date' === attributes.group_by && attributes.allow_multiday &&
									<TextControl
										type="text"
										label={ __( 'End date field name' ) }
										help={ __( 'This field must contain date when events ends. Works only if "Save as timestamp" option for meta field is active' ) }
										value={ attributes.end_date_key }
										onChange={ newValue => {
											props.setAttributes( { end_date_key: newValue } );
										} }
									/>
								}
								<ToggleControl
									label={ __( 'Use Custom Post Types' ) }
									checked={ attributes.use_custom_post_types }
									onChange={ () => {
										props.setAttributes({ use_custom_post_types: ! attributes.use_custom_post_types });
									} }
								/>
								{ attributes.use_custom_post_types && <SelectControl
									multiple={true}
									label={ __( 'Post types' ) }
									value={ attributes.custom_post_types }
									options={ window.JetEngineListingData.postTypes }
									onChange={ newValue => {
										props.setAttributes( { custom_post_types: newValue } );
									} }
								/> }
								<hr/>
								<SelectControl
									label={ __( 'Week days format' ) }
									value={ attributes.week_days_format }
									options={ [
										{
											value: 'full',
											label: __( 'Full' ),
										},
										{
											value: 'short',
											label: __( 'Short' ),
										},
										{
											value: 'initial',
											label: __( 'Initial letter' ),
										},
									] }
									onChange={ newValue => {
										props.setAttributes( { week_days_format: newValue } );
									} }
								/>
								<ToggleControl
									label={ __( 'Start from custom month' ) }
									checked={ attributes.custom_start_from }
									onChange={ () => {
										props.setAttributes( { custom_start_from: ! attributes.custom_start_from } );
									} }
								/>
								{ attributes.custom_start_from &&
									<SelectControl
										label={ __( 'Start from month' ) }
										value={ attributes.start_from_month }
										options={ [
											{
												value: 'January',
												label: __( 'January' ),
											},
											{
												value: 'February',
												label: __( 'February' ),
											},
											{
												value: 'March',
												label: __( 'March' ),
											},
											{
												value: 'April',
												label: __( 'April' ),
											},
											{
												value: 'May',
												label: __( 'May' ),
											},
											{
												value: 'June',
												label: __( 'June' ),
											},
											{
												value: 'July',
												label: __( 'July' ),
											},
											{
												value: 'August',
												label: __( 'August' ),
											},
											{
												value: 'September',
												label: __( 'September' ),
											},
											{
												value: 'October',
												label: __( 'October' ),
											},
											{
												value: 'November',
												label: __( 'November' ),
											},
											{
												value: 'December',
												label: __( 'December' ),
											},
										] }
										onChange={ newValue => {
											props.setAttributes( { start_from_month: newValue } );
										} }
									/>
								}
								{ attributes.custom_start_from &&
									<TextControl
										type="text"
										label={ __( 'Start from year' ) }
										value={ attributes.start_from_year }
										onChange={ newValue => {
											props.setAttributes( { start_from_year: newValue } );
										} }
									/>
								}
								<ToggleControl
									label={ __( 'Show posts from the nearby months' ) }
									checked={ attributes.show_posts_nearby_months }
									onChange={ () => {
										props.setAttributes( { show_posts_nearby_months: ! attributes.show_posts_nearby_months } );
									} }
								/>
								<ToggleControl
									label={ __( 'Hide past events' ) }
									checked={ attributes.hide_past_events }
									onChange={ () => {
										props.setAttributes( { hide_past_events: ! attributes.hide_past_events } );
									} }
								/>
								<SelectControl
									label={ __( 'Caption Layout' ) }
									value={ attributes.caption_layout }
									options={ [
										{
											value: 'layout-1',
											label: __( 'Layout 1' ),
										},
										{
											value: 'layout-2',
											label: __( 'Layout 2' ),
										},
										{
											value: 'layout-3',
											label: __( 'Layout 3' ),
										},
										{
											value: 'layout-4',
											label: __( 'Layout 4' ),
										},

									] }
									onChange={ newValue => {
										props.setAttributes( { caption_layout: newValue } );
									} }
								/>
							</PanelBody>
							<PanelBody
								title={ __( 'Custom Query' ) }
								initialOpen={ false }
							>
								<ToggleControl
									label={ __( 'Use Custom Query' ) }
									checked={ attributes.custom_query }
									onChange={ () => {
										props.setAttributes({ custom_query: ! attributes.custom_query });
									} }
								/>
								{ attributes.custom_query && <SelectControl
									multiple={false}
									label={ __( 'Custom Query' ) }
									value={ attributes.custom_query_id }
									options={ window.JetEngineListingData.queriesList }
									onChange={ newValue => {
										props.setAttributes( { custom_query_id: newValue } );
									}}
								/> }
							</PanelBody>
							{ ! window.JetEngineListingData.legacy.is_disabled && <PanelBody
								title={ __( 'Posts Query' ) }
								initialOpen={ false }
							>
								<JetEngineRepeater
									data={ attributes.posts_query }
									default={{
										type: '',
									}}
									onChange={ newData => {
										props.setAttributes({ posts_query: newData });
									} }
								>
									{
										( item ) =>
											<div>
												<SelectControl
													label={ __( 'Type' ) }
													value={ item.type }
													options={ [
														{
															value: '',
															label: __( 'Select...' ),
														},
														{
															value: 'posts_params',
															label: __( 'Posts & Author Parameters' ),
														},
														{
															value: 'order_offset',
															label: __( 'Order & Offset' ),
														},
														{
															value: 'tax_query',
															label: __( 'Tax Query' ),
														},
														{
															value: 'meta_query',
															label: __( 'Meta Query' ),
														},
														{
															value: 'date_query',
															label: __( 'Date Query' ),
														},
													] }
													onChange={newValue => {
														updateItem( item, 'type', newValue )
													}}
												/>
												{ 'date_query' === item.type &&
												<div>
													<SelectControl
														label={ __( 'Column' ) }
														value={ item.date_query_column }
														options={ [
															{
																value: 'post_date',
																label: __( 'Post date' ),
															},
															{
																value: 'post_date_gmt',
																label: __( 'Post date GMT' ),
															},
															{
																value: 'post_modified',
																label: __( 'Post modified' ),
															},
															{
																value: 'post_modified_gmt',
																label: __( 'Post modified GMT' ),
															},
														] }
														onChange={newValue => {
															updateItem( item, 'date_query_column', newValue )
														}}
													/>
													<TextControl
														type="text"
														label={ __( 'After' ) }
														help={ __( 'Date to retrieve posts after. Accepts strtotime()-compatible string' ) }
														value={ item.date_query_after }
														onChange={newValue => {
															updateItem( item, 'date_query_after', newValue )
														}}
													/>
													<TextControl
														type="text"
														label={ __( 'Before' ) }
														help={ __( 'Date to retrieve posts before. Accepts strtotime()-compatible string' ) }
														value={ item.date_query_before }
														onChange={newValue => {
															updateItem( item, 'date_query_before', newValue )
														}}
													/>
												</div>
												}
												{ 'posts_params' === item.type &&
												<div>
													<TextControl
														type="text"
														label={ __( 'Include posts by IDs' ) }
														help={ __( 'Eg. 12, 24, 33' ) }
														value={ item.posts_in }
														onChange={newValue => {
															updateItem( item, 'posts_in', newValue )
														}}
													/>
													<TextControl
														type="text"
														label={ __( 'Exclude posts by IDs' ) }
														help={ __( 'Eg. 12, 24, 33. If this is used in the same query as Include posts by IDs, it will be ignored' ) }
														value={ item.posts_not_in }
														onChange={newValue => {
															updateItem( item, 'posts_not_in', newValue )
														}}
													/>
													<TextControl
														type="text"
														label={ __( 'Get child of' ) }
														help={ __( 'Eg. 12, 24, 33' ) }
														value={ item.posts_parent }
														onChange={newValue => {
															updateItem( item, 'posts_parent', newValue )
														}}
													/>
													<SelectControl
														label={ __( 'Post status' ) }
														value={ item.posts_status }
														options={ [
															{
																value: 'publish',
																label: __( 'Publish' ),
															},
															{
																value: 'pending',
																label: __( 'Pending' ),
															},
															{
																value: 'draft',
																label: __( 'Draft' ),
															},
															{
																value: 'auto-draft',
																label: __( 'Auto draft' ),
															},
															{
																value: 'future',
																label: __( 'Future' ),
															},
															{
																value: 'private',
																label: __( 'Private' ),
															},
															{
																value: 'trash',
																label: __( 'Trash' ),
															},
															{
																value: 'any',
																label: __( 'Any' ),
															},
														] }
														onChange={newValue => {
															updateItem( item, 'posts_status', newValue )
														}}
													/>
													<SelectControl
														label={ __( 'Posts by author' ) }
														value={ item.posts_author }
														options={ [
															{
																value: 'any',
																label: __( 'Any author' ),
															},
															{
																value: 'current',
																label: __( 'Current User' ),
															},
															{
																value: 'id',
																label: __( 'Specific Author ID' ),
															},
															{
																value: 'queried',
																label: __( 'Queried User' ),
															},
														] }
														onChange={newValue => {
															updateItem( item, 'posts_author', newValue )
														}}
													/>
													{
														'id' === item.posts_author &&
														<TextControl
															type="text"
															label={ __( 'Author ID' ) }
															value={ item.posts_author_id }
															onChange={newValue => {
																updateItem( item, 'posts_author_id', newValue )
															}}
														/>
													}
													<TextControl
														type="text"
														label={ __( 'Search Query' ) }
														value={ item.search_query }
														onChange={newValue => {
															updateItem( item, 'search_query', newValue )
														}}
													/>
												</div>
												}
												{ 'order_offset' === item.type &&
												<div>
													<TextControl
														type="number"
														label={ __( 'Posts offset' ) }
														value={ item.offset }
														min="0"
														max="100"
														step="1"
														onChange={newValue => {
															updateItem( item, 'offset', newValue )
														}}
													/>
													<SelectControl
														label={ __( 'Order' ) }
														value={ item.order }
														options={ [
															{
																value: 'ASC',
																label: __( 'ASC' ),
															},
															{
																value: 'DESC',
																label: __( 'DESC' ),
															},
														] }
														onChange={newValue => {
															updateItem( item, 'order', newValue )
														}}
													/>
													<SelectControl
														label={ __( 'Order' ) }
														value={ item.order_by }
														options={ [
															{
																value: 'none',
																label: __( 'None' ),
															},
															{
																value: 'ID',
																label: __( 'ID' ),
															},
															{
																value: 'author',
																label: __( 'Author' ),
															},
															{
																value: 'title',
																label: __( 'Title' ),
															},
															{
																value: 'name',
																label: __( 'Name' ),
															},
															{
																value: 'type',
																label: __( 'Type' ),
															},
															{
																value: 'date',
																label: __( 'Date' ),
															},
															{
																value: 'modified',
																label: __( 'Modified' ),
															},
															{
																value: 'parent',
																label: __( 'Parent' ),
															},
															{
																value: 'rand',
																label: __( 'Random' ),
															},
															{
																value: 'comment_count',
																label: __( 'Comment Count' ),
															},
															{
																value: 'relevance',
																label: __( 'Relevance' ),
															},
															{
																value: 'menu_order',
																label: __( 'Menu Order' ),
															},
															{
																value: 'meta_value',
																label: __( 'Meta Value' ),
															},
															{
																value: 'meta_clause',
																label: __( 'Meta Clause' ),
															},
															{
																value: 'post__in',
																label: __( 'Preserve post ID order given in the "Include posts by IDs" option' ),
															},
														] }
														onChange={newValue => {
															updateItem( item, 'order_by', newValue )
														}}
													/>
													{ 'meta_value' === item.order_by &&
													<div>
														<TextControl
															type="text"
															label={ __( 'Meta key to order' ) }
															help={ __( 'Set meta field name to order by' ) }
															value={ item.meta_key }
															onChange={newValue => {
																updateItem( item, 'meta_key', newValue )
															}}
														/>
														<SelectControl
															label={ __( 'Meta type' ) }
															value={ item.meta_type }
															options={ [
																{
																	value: 'CHAR',
																	label: 'CHAR',
																},
																{
																	value: 'NUMERIC',
																	label: 'NUMERIC',
																},
																{
																	value: 'DATE',
																	label: 'DATE',
																},
																{
																	value: 'DATETIME',
																	label: 'DATETIME',
																},
																{
																	value: 'DECIMAL',
																	label: 'DECIMAL',
																},
															] }
															onChange={newValue => {
																updateItem( item, 'meta_type', newValue )
															}}
														/>
													</div>
													}
													{ 'meta_clause' === item.order_by &&
													<TextControl
														type="text"
														label={ __( 'Meta clause to order' ) }
														help={ __( 'Meta clause name to order by. Clause with this name should be created in Meta Query parameters' ) }
														value={ item.meta_clause_key }
														onChange={newValue => {
															updateItem( item, 'meta_clause_key', newValue )
														}}
													/>
													}
												</div>
												}
												{ 'tax_query' === item.type &&
												<div>
													<SelectControl
														label={ __( 'Taxonomy' ) }
														value={ item.tax_query_taxonomy }
														options={ window.JetEngineListingData.taxonomies }
														onChange={newValue => {
															updateItem( item, 'tax_query_taxonomy', newValue )
														}}
													/>
													<SelectControl
														label={ __( 'Operator' ) }
														value={ item.tax_query_compare }
														options={ [
															{
																value: 'IN',
																label: 'IN',
															},
															{
																value: 'NOT IN',
																label: 'NOT IN',
															},
															{
																value: 'AND',
																label: 'AND',
															},
															{
																value: 'EXISTS',
																label: 'EXISTS',
															},
															{
																value: 'NOT EXISTS',
																label: 'NOT EXISTS',
															},
														] }
														onChange={newValue => {
															updateItem( item, 'tax_query_compare', newValue )
														}}
													/>
													<SelectControl
														label={ __( 'Field' ) }
														value={ item.tax_query_field }
														options={ [
															{
																value: 'term_id',
																label: __( 'Term ID' ),
															},
															{
																value: 'slug',
																label: __( 'Slug' ),
															},
															{
																value: 'name',
																label: __( 'Name' ),
															},
														] }
														onChange={newValue => {
															updateItem( item, 'tax_query_field', newValue )
														}}
													/>
													<TextControl
														type="text"
														label={ __( 'Terms' ) }
														value={ item.tax_query_terms }
														onChange={newValue => {
															updateItem( item, 'tax_query_terms', newValue )
														}}
													/>
													<TextControl
														type="text"
														label={ __( 'Terms from meta field' ) }
														help={ __( 'Get terms IDs from current page meta field' ) }
														value={ item.tax_query_terms_meta }
														onChange={newValue => {
															updateItem( item, 'tax_query_terms_meta', newValue )
														}}
													/>
												</div>
												}
												{ 'meta_query' === item.type &&
												<div>
													<TextControl
														label={ __( 'Key (name/ID)' ) }
														value={ item.meta_query_key }
														onChange={newValue => {
															updateItem( item, 'meta_query_key', newValue )
														}}
													/>
													<SelectControl
														label={ __( 'Operator' ) }
														value={ item.meta_query_compare }
														options={ [
															{
																value: '=',
																label: 'Equal',
															},
															{
																value: '!=',
																label: 'Not equal',
															},
															{
																value: '>',
																label: 'Greater than',
															},
															{
																value: '>=',
																label: 'Greater or equal',
															},
															{
																value: '<',
																label: 'Less than',
															},
															{
																value: '<=',
																label: 'Equal or less',
															},
															{
																value: 'LIKE',
																label: 'LIKE',
															},
															{
																value: 'NOT LIKE',
																label: 'NOT LIKE',
															},
															{
																value: 'IN',
																label: 'IN',
															},
															{
																value: 'NOT IN',
																label: 'NOT IN',
															},
															{
																value: 'BETWEEN',
																label: 'BETWEEN',
															},
															{
																value: 'NOT BETWEEN',
																label: 'NOT BETWEEN',
															},
															{
																value: 'EXISTS',
																label: 'EXISTS',
															},
															{
																value: 'NOT EXISTS',
																label: 'NOT EXISTS',
															},
															{
																value: 'REGEXP',
																label: 'REGEXP',
															},
															{
																value: 'NOT REGEXP',
																label: 'NOT REGEXP',
															},
														] }
														onChange={newValue => {
															updateItem( item, 'meta_query_compare', newValue )
														}}
													/>
													{ ! ['EXISTS', 'NOT EXISTS'].includes( item.meta_query_compare ) &&
													<div>
														<TextControl
															type="text"
															label={ __( 'Value' ) }
															help={ __( 'For "In", "Not in", "Between" and "Not between" compare separate multiple values with comma' ) }
															value={ item.meta_query_val }
															onChange={newValue => {
																updateItem( item, 'meta_query_val', newValue )
															}}
														/>
														<TextControl
															type="text"
															label={ __( 'Or get value from query variable' ) }
															help={ __( 'Set query variable name (from URL or WordPress query var) to get value from' ) }
															value={ item.meta_query_request_val }
															onChange={newValue => {
																updateItem( item, 'meta_query_request_val', newValue )
															}}
														/>
													</div>
													}
													<SelectControl
														label={ __( 'Type' ) }
														value={ item.meta_query_type }
														options={ metaTypes }
														onChange={newValue => {
															updateItem( item, 'meta_query_type', newValue )
														}}
													/>
													<TextControl
														type="text"
														label={ __( 'Meta Query Clause' ) }
														help={ __( 'Set unique name for current query clause to use it to order posts by this clause' ) }
														value={ item.meta_query_clause }
														onChange={newValue => {
															updateItem( item, 'meta_query_clause', newValue )
														}}
													/>
												</div>
												}
											</div>
									}
								</JetEngineRepeater>
								<SelectControl
									label={ __( 'Meta query relation' ) }
									value={ attributes.meta_query_relation }
									options={ [
										{
											value: 'AND',
											label: __( 'AND' ),
										},
										{
											value: 'OR',
											label: __( 'OR' ),
										}
									] }
									onChange={ newValue => {
										props.setAttributes( { meta_query_relation: newValue } );
									}}
								/>
								<SelectControl
									label={ __( 'Tax query relation' ) }
									value={ attributes.tax_query_relation }
									options={ [
										{
											value: 'AND',
											label: __( 'AND' ),
										},
										{
											value: 'OR',
											label: __( 'OR' ),
										}
									] }
									onChange={ newValue => {
										props.setAttributes( { tax_query_relation: newValue } );
									}}
								/>
							</PanelBody> }
							<PanelBody
								title={ __( 'Block Visibility' ) }
								initialOpen={ false }
							>
								<SelectControl
									label={ __( 'Hide block if' ) }
									value={ attributes.hide_widget_if }
									options={ hideOptions }
									onChange={ newValue => {
										props.setAttributes( { hide_widget_if: newValue } );
									} }
								/>
							</PanelBody>
						</InspectorControls>
					),
					<Disabled>
						<ServerSideRender
							block="jet-engine/listing-calendar"
							attributes={ attributes }
						/>
					</Disabled>
				];
			}
		},
		save: props => {
			return null;
		}
	} );
}
