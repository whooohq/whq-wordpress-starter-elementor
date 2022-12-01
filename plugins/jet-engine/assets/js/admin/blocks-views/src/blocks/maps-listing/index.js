import GroupedSelectControl from "components/grouped-select-control.js";
import JetEngineRepeater from "components/repeater-control.js";
import CustomControl from "components/custom-control.js";
import { getCallbackArgs } from "utils/utility.js";

import {
	clone
} from '../../utils/utility';

const { __ } = wp.i18n;
const {
	registerBlockType
} = wp.blocks;

const {
	InspectorControls,
	MediaUpload
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

if ( -1 !== window.JetEngineListingData.activeModules.indexOf( 'maps-listings' ) ) {
	const GIcon = <SVG xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64" fill="none"><Rect width="64" height="64" fill="white"/><Path d="M14 43.3437C13.7903 43.1247 13.2196 42.5177 12.2697 41.5023C11.2122 40.3518 10.0617 38.9404 8.81786 37.2633C7.58706 35.5847 6.45728 33.7924 5.42891 31.8856C4.44915 30.034 4 28.4115 4 27C4 25.4063 4.29141 24.0171 4.85087 22.8123C5.44909 21.5463 6.21343 20.5015 7.13814 19.6624L7.13882 19.6618C8.09837 18.7895 9.16558 18.1308 10.3445 17.6797C11.5531 17.2246 12.7699 17 14 17C15.2258 17 16.4393 17.2325 17.646 17.7047L17.646 17.7048L17.6578 17.7092C18.8548 18.1602 19.9182 18.8174 20.8544 19.6843L20.8544 19.6843L20.8619 19.6911C21.7872 20.5308 22.541 21.5655 23.1192 22.8095L23.1191 22.8095L23.1241 22.8199C23.7002 24.0229 24 25.4093 24 27C24 28.4299 23.5417 30.0715 22.5424 31.943L22.5407 31.9462C21.5321 33.8514 20.4129 35.6322 19.1837 37.2898C17.9396 38.9675 16.7799 40.3784 15.7046 41.5278L15.7042 41.5282C14.7698 42.5282 14.2079 43.1264 14 43.3437Z" fill="white" stroke="#162B40" strokeWidth="2"/><Path d="M53.5805 24.7732C53.4012 24.5825 53.1545 24.3194 52.8402 23.9834C52.22 23.3085 51.5413 22.4764 50.8038 21.4821C50.0794 20.4941 49.4138 19.4383 48.8074 18.3141C48.242 17.2452 48 16.34 48 15.5806C48 14.6775 48.1648 13.9059 48.4711 13.2458C48.8084 12.5323 49.2352 11.9517 49.7456 11.4885L49.7463 11.4879C50.2838 10.9992 50.8792 10.6321 51.5363 10.3805C52.2156 10.1248 52.8953 10 53.5806 10C54.2617 10 54.9381 10.1289 55.6154 10.3939L55.6154 10.394L55.6272 10.3985C56.2948 10.65 56.8864 11.0156 57.4083 11.4989L57.4083 11.4989L57.4157 11.5057C57.9268 11.9694 58.3468 12.5438 58.6718 13.2431L58.6717 13.2431L58.6767 13.2535C58.9919 13.9117 59.1613 14.6805 59.1613 15.5806C59.1613 16.3507 58.914 17.2672 58.3366 18.3485L58.335 18.3517C57.7406 19.4743 57.0818 20.5224 56.3591 21.4971C55.6213 22.492 54.9373 23.3236 54.307 23.9973L54.3066 23.9977C53.9991 24.3268 53.7571 24.5852 53.5805 24.7732Z" fill="white" stroke="#162B40" strokeWidth="2"/><Path d="M35.931 52.9336C34.3971 54.5751 33.5495 55.4766 33.388 55.638C33.2804 55.7726 33.1458 55.8668 32.9844 55.9206C32.8498 56.0013 32.6884 56.0417 32.5 56.0417C32.3116 56.0417 32.1367 56.0013 31.9753 55.9206C31.8407 55.8668 31.7196 55.7726 31.612 55.638C31.4505 55.4766 30.5894 54.5616 29.0286 52.8932L35.931 52.9336ZM35.931 52.9336C37.4918 51.2652 39.1602 49.2335 40.9362 46.8385M35.931 52.9336L40.9362 46.8385M40.9362 46.8385C42.7122 44.4436 44.3268 41.8737 45.7799 39.1289L40.9362 46.8385ZM32.6418 54.9701L32.6302 54.9845L32.564 55.0066L32.5057 55.0416C32.5039 55.0416 32.502 55.0417 32.5 55.0417C32.4599 55.0417 32.439 55.0344 32.4225 55.0261L32.3902 55.01L32.3582 54.9701L32.3191 54.9309C32.1732 54.785 31.3306 53.8902 29.7618 52.2132C28.2591 50.5783 26.6281 48.5772 24.8686 46.2047C23.1222 43.823 21.5198 41.2807 20.0617 38.577C18.6601 35.9285 18 33.5755 18 31.5C18 29.2032 18.4203 27.1851 19.2376 25.4256C20.1014 23.5971 21.2095 22.0796 22.5561 20.8577L22.5568 20.8571C23.946 19.5942 25.4937 18.6386 27.2038 17.9844C28.9515 17.3261 30.7152 17 32.5 17C34.2805 17 36.0408 17.338 37.7866 18.0211L37.7866 18.0212L37.7984 18.0256C39.5345 18.6798 41.0784 19.6338 42.4365 20.8913L42.4364 20.8914L42.4439 20.8981C43.7911 22.1206 44.8848 23.6241 45.7208 25.4228L45.7207 25.4228L45.7257 25.4332C46.5674 27.1909 47 29.2062 47 31.5C47 33.6017 46.3269 35.9816 44.8978 38.6579L44.8962 38.661C43.4656 41.3631 41.8778 43.8901 40.133 46.2429C38.3732 48.616 36.7291 50.6167 35.2007 52.2504L35.2003 52.2509C33.6565 53.903 32.8267 54.7852 32.6809 54.9309L32.6418 54.9701ZM28.1104 35.8708L28.1197 35.8803L28.1292 35.8896C29.3383 37.067 30.813 37.6667 32.5 37.6667C34.1876 37.6667 35.654 37.0662 36.8399 35.8803C38.0471 34.6731 38.6667 33.1956 38.6667 31.5C38.6667 29.8082 38.0494 28.3422 36.84 27.16C35.6578 25.9506 34.1918 25.3333 32.5 25.3333C30.8044 25.3333 29.3269 25.9529 28.1197 27.1601C26.9338 28.346 26.3333 29.8124 26.3333 31.5C26.3333 33.187 26.933 34.6617 28.1104 35.8708Z" fill="#6F8CFF" stroke="#162B40" strokeWidth="2"/></SVG>;

	const blockAttributes = window.JetEngineListingData.atts.mapsListing;

	registerBlockType( 'jet-engine/maps-listing', {
		title: __( 'Map Listing' ),
		icon: GIcon,
		category: 'layout',
		attributes: blockAttributes,
		className: 'jet-map-listing',
		edit: class extends wp.element.Component {

			constructor( props ) {

				if ( ! props.attributes._block_id ) {
					props.setAttributes( { _block_id: props.clientId } );
				}

				super( props );
			}

			getCustomControlsSection( section ) {
				
				const providerControls = window.JetEngineListingData.mapsListingConfig.providerControls;
				const props            = this.props;
				const attributes       = props.attributes;

				if ( ! providerControls || ! providerControls[ section ] ) {
					return;
				}

				return providerControls[ section ].map( ( data ) => {

					const control = data.control;

					control.name = data.key;

					return <CustomControl
						control={ control }
						value={ attributes[ control.name ] }
						onChange={ newValue => {
							props.setAttributes( { [ control.name ]: newValue } );
						} }
					/>
				} );

			}

			render() {

				const props               = this.props;
				const attributes          = props.attributes;
				const listingOptions      = window.JetEngineListingData.listingOptions;
				const hideOptions         = window.JetEngineListingData.hideOptions;
				const metaFields          = window.JetEngineListingData.metaFields;
				const filterCallbacks     = window.JetEngineListingData.filterCallbacks;
				const markerTypes         = window.JetEngineListingData.mapsListingConfig.markerTypes;
				const markerLabelTypes    = window.JetEngineListingData.mapsListingConfig.markerLabelTypes;
				const filterCallbacksArgs = window.JetEngineListingData.filterCallbacksArgs;

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
								<TextControl
									type="text"
									label={ __( 'Address Meta Field' ) }
									help={ __( 'Set meta field key to get address from (for human-readable addresses). To get address from multiple meta fields, combine these fields names with "+" sign. For example: state+city+street' ) }
									value={ attributes.address_field }
									onChange={ newValue => {
										props.setAttributes( { address_field: newValue } );
									} }
								/>
								<ToggleControl
									label={ __( 'Use Lat Lng Address Meta Field' ) }
									help={ __( 'Check this if you want to get item address for the map by latitude and longitude stored directly in the meta field' ) }
									checked={ attributes.add_lat_lng }
									onChange={ () => {
										props.setAttributes( { add_lat_lng: ! attributes.add_lat_lng } );
									} }
								/>
								{ attributes.add_lat_lng && <TextControl
									type="text"
									label={ __( 'Lat Lng Address Meta Field' ) }
									help={ __( 'Set meta field key to get latitude and longitude from. To get address from latitude and longitude meta fields, combine these fields names with "+" sign. For example: _lat+_lng. Latitude field always should be first' ) }
									value={ attributes.lat_lng_address_field }
									onChange={ newValue => {
										props.setAttributes( { lat_lng_address_field: newValue } );
									} }
								/> }
								<TextControl
									type="number"
									label={ __( 'Map Height' ) }
									value={ attributes.map_height }
									min={ `100` }
									max={ `1000` }
									onChange={ newValue => {
										props.setAttributes( { map_height: Number(newValue) } );
									} }
								/>
								<TextControl
									type="number"
									label={ __( 'Posts number' ) }
									value={ attributes.posts_num }
									min={ `1` }
									max={ `1000` }
									onChange={ newValue => {
										props.setAttributes( { posts_num: Number(newValue) } );
									} }
								/>
								<ToggleControl
									label={ __( 'Automatically detect map center' ) }
									checked={ attributes.auto_center }
									onChange={ () => {
										props.setAttributes( { auto_center: ! attributes.auto_center } );
									} }
								/>
								{ attributes.auto_center && <TextControl
									type="number"
									label={ __( 'Max Zoom' ) }
									value={ attributes.max_zoom }
									min={ `1` }
									max={ `20` }
									onChange={ newValue => {
										props.setAttributes( { max_zoom: Number(newValue) } );
									} }
								/> }
								{ ! attributes.auto_center && <TextareaControl
									type="text"
									label={ __( 'Map Center' ) }
									value={ attributes.custom_center }
									onChange={ newValue => {
										props.setAttributes( { custom_center: newValue } );
									} }
								/> }
								{ ! attributes.auto_center && <TextControl
									type="number"
									label={ __( 'Custom Zoom' ) }
									value={ attributes.custom_zoom }
									min={ `1` }
									max={ `20` }
									onChange={ newValue => {
										props.setAttributes( { custom_zoom: Number(newValue) } );
									} }
								/> }
								{ this.getCustomControlsSection( 'section_general' ) }
							</PanelBody>
							{ window.JetEngineListingData.legacy.is_disabled && <PanelBody
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
							</PanelBody> }
							<PanelBody
								title={ __( 'Marker' ) }
								initialOpen={ false }
							>
								<SelectControl
									label={ __( 'Marker Type' ) }
									value={ attributes.marker_type }
									options={ markerTypes }
									onChange={ newValue => {
										props.setAttributes( { marker_type: newValue } );
									} }
								/>
								{ 'icon' === attributes.marker_type &&
								<div className="jet-media-control components-base-control">
									<div className="components-base-control__label">{ __( 'Image/Icon' ) }</div>
									{ attributes.marker_icon_url && <img src={ attributes.marker_icon_url } width="100%" height="auto"/> }
									<MediaUpload
										onSelect={ media => {
											props.setAttributes( {
												marker_icon:     media.id,
												marker_icon_url: media.url,
											} );
										} }
										type="image"
										value={ attributes.marker_icon }
										render={ ( { open } ) => (
											<IconButton
												isSecondary
												icon="edit"
												onClick={ open }
											>{ __( 'Select Image/Icon' ) }</IconButton>
										) }
									/>
									{ attributes.marker_icon_url &&
									<IconButton
										onClick={ () => {
											props.setAttributes( {
												marker_icon: 0,
												marker_icon_url: '',
											} )
										} }
										isLink
										isDestructive
									>
										{ __( 'Remove Image/Icon' ) }
									</IconButton>
									}
								</div>
								}
								{ 'dynamic_image' === attributes.marker_type && <GroupedSelectControl
									label={ __( 'Meta Field' ) }
									value={ attributes.marker_image_field }
									options={ metaFields }
									onChange={ newValue => {
										props.setAttributes( { marker_image_field: newValue } );
									} }
								/> }
								{ 'dynamic_image' === attributes.marker_type && <TextControl
									type="text"
									label={ __( 'Or enter meta field key' ) }
									help={ __( 'Note: this filed will override Meta Field value' ) }
									value={ attributes.marker_image_field_custom }
									onChange={ newValue => {
										props.setAttributes( { marker_image_field_custom: newValue } );
									} }
								/> }
								{ 'text' === attributes.marker_type && <SelectControl
									label={ __( 'Marker Label' ) }
									value={ attributes.marker_label_type }
									options={ markerLabelTypes }
									onChange={ newValue => {
										props.setAttributes( { marker_label_type: newValue } );
									} }
								/> }
								{ 'text' === attributes.marker_type && 'meta_field' === attributes.marker_label_type && <GroupedSelectControl
									label={ __( 'Meta Field' ) }
									value={ attributes.marker_label_field }
									options={ metaFields }
									onChange={ newValue => {
										props.setAttributes( { marker_label_field: newValue } );
									} }
								/> }
								{ 'text' === attributes.marker_type && 'meta_field' === attributes.marker_label_type && <TextControl
									type="text"
									label={ __( 'Or enter meta field key' ) }
									help={ __( 'Note: this filed will override Meta Field value' ) }
									value={ attributes.marker_label_field_custom }
									onChange={ newValue => {
										props.setAttributes( { marker_label_field_custom: newValue } );
									} }
								/> }
								{ 'text' === attributes.marker_type && 'static_text' === attributes.marker_label_type && <TextControl
									type="text"
									label={ __( 'Marker Label' ) }
									value={ attributes.marker_label_text }
									onChange={ newValue => {
										props.setAttributes( { marker_label_text: newValue } );
									} }
								/> }
								{ -1 !== window.JetEngineListingData.activeModules.indexOf( 'custom-content-types' ) &&
									( ( 'text' === attributes.marker_type && 'cct_field' === attributes.marker_label_type ) || 'dynamic_image_cct' === attributes.marker_type )  &&
									<TextControl
										type="text"
										label={ __( 'Field' ) }
										value={ attributes.marker_cct_field }
										onChange={ newValue => {
											props.setAttributes( { marker_cct_field: newValue } );
										} }
									/>
								}
								{ 'text' === attributes.marker_type && <SelectControl
									label={ __( 'Callback' ) }
									value={ attributes.marker_label_format_cb }
									options={ filterCallbacks }
									onChange={ newValue => {
										props.setAttributes( { marker_label_format_cb: newValue } );
									} }
								/> }

								{ 'text' === attributes.marker_type && getCallbackArgs( attributes.marker_label_format_cb ).map( ( control ) => {
									return <CustomControl
										control={ control }
										value={ attributes[control.name] }
										onChange={ newValue => {
											props.setAttributes( { [control.name]: newValue } );
										} }
									/>
								} ) }

								{ 'text' === attributes.marker_type && <ToggleControl
									label={ __( 'Customize output' ) }
									checked={ attributes.marker_label_custom }
									onChange={ () => {
										props.setAttributes( { marker_label_custom: ! attributes.marker_label_custom } );
									} }
								/> }
								{ 'text' === attributes.marker_type && attributes.marker_label_custom && <TextareaControl
									type="text"
									label={ __( 'Label format' ) }
									help={ __( '%s will be replaced with field value' ) }
									value={ attributes.marker_label_custom_output }
									onChange={ newValue => {
										props.setAttributes( { marker_label_custom_output: newValue } );
									} }
								/> }
								<ToggleControl
									label={ __( 'Use different markers by conditions' ) }
									help={ __( 'Previously set marker will be used as default if conditions not met' ) }
									checked={ attributes.multiple_marker_types }
									onChange={ () => {
										props.setAttributes( { multiple_marker_types: ! attributes.multiple_marker_types } );
									} }
								/>
								{ attributes.multiple_marker_types &&
								<JetEngineRepeater
									data={ attributes.multiple_markers }
									default={ {
										apply_type: 'meta_field',
									} }
									onChange={ newData => {
										props.setAttributes( { multiple_markers: newData } );
									} }
								>
									{
										( item ) =>
											<div>
												<div className="jet-media-control components-base-control">
													<div className="components-base-control__label">{ __( 'Image/Icon' ) }</div>
													{ item.marker_icon_url && <img src={ item.marker_icon_url } width="100%" height="auto"/> }
													<MediaUpload
														onSelect={ media => {
															updateItem( item, {
																marker_icon: media.id,
																marker_icon_url: media.url,
															}, null, 'multiple_markers' );
														} }
														type="image"
														value={ item.marker_icon }
														render={ ( { open } ) => (
															<IconButton
																isSecondary
																icon="edit"
																onClick={ open }
															>{ __( 'Select Image/Icon' ) }</IconButton>
														) }
													/>
													{ item.marker_icon_url &&
													<IconButton
														onClick={ () => {
															updateItem( item, {
																marker_icon: 0,
																marker_icon_url:'',
															}, null, 'multiple_markers' );
														} }
														isLink
														isDestructive
													>
														{ __( 'Remove Image/Icon' ) }
													</IconButton>
													}
												</div>
												<SelectControl
													label={ __( 'Apply this marker if' ) }
													value={ item.apply_type }
													options={ [
														{
															value: 'meta_field',
															label: __( 'Meta field is equal to value' ),
														},
														{
															value: 'post_term',
															label: __( 'Post has term' ),
														},
													] }
													onChange={ newValue => {
														updateItem( item, 'apply_type', newValue, 'multiple_markers' )
													} }
												/>
												{ 'meta_field' === item.apply_type && <GroupedSelectControl
													label={ __( 'Meta Field' ) }
													value={ item.field_name }
													options={ metaFields }
													onChange={ newValue => {
														updateItem( item, 'field_name', newValue, 'multiple_markers' )
													} }
												/> }
												{ 'meta_field' === item.apply_type && <TextControl
													type="text"
													label={ __( 'Or enter meta field key' ) }
													help={ __( 'Note: this filed will override Meta Field value' ) }
													value={ item.field_name_custom }
													onChange={ newValue => {
														updateItem( item, 'field_name_custom', newValue, 'multiple_markers' )
													} }
												/> }
												{ 'meta_field' === item.apply_type && <TextControl
													type="text"
													label={ __( 'Field value' ) }
													value={ item.field_value }
													onChange={ newValue => {
														updateItem( item, 'field_value', newValue, 'multiple_markers' )
													} }
												/> }
												{ 'post_term' === item.apply_type && <TextControl
													type="text"
													label={ __( 'Taxonomy slug' ) }
													help={ __( 'You can find this slug in the address bar of taxonomy edit page' ) }
													value={ item.tax_name }
													onChange={ newValue => {
														updateItem( item, 'tax_name', newValue, 'multiple_markers' )
													} }
												/> }
												{ 'post_term' === item.apply_type && <TextControl
													type="text"
													label={ __( 'Term name, slug or ID' ) }
													value={ item.term_name }
													onChange={ newValue => {
														updateItem( item, 'term_name', newValue, 'multiple_markers' )
													} }
												/> }
											</div>
									}
								</JetEngineRepeater> }

								<hr/>
								<ToggleControl
									label={ __( 'Marker Clustering' ) }
									checked={ attributes.marker_clustering }
									onChange={ () => {
										props.setAttributes( { marker_clustering: ! attributes.marker_clustering } );
									} }
								/>
							</PanelBody>
							<PanelBody
								title={ __( 'Popup' ) }
								initialOpen={ false }
							>
								<TextControl
									type="number"
									label={ __( 'Marker Popup Width' ) }
									help={ __( 'Set marker popup width in pixels' ) }
									value={ attributes.popup_width }
									min={ `150` }
									max={ `600` }
									onChange={ newValue => {
										props.setAttributes( { popup_width: Number(newValue) } );
									} }
								/>
								<TextControl
									type="number"
									label={ __( 'Vertical Offset' ) }
									help={ __( 'Set vertical popup offset in pixels' ) }
									value={ attributes.popup_offset }
									min={ `0` }
									max={ `200` }
									onChange={ newValue => {
										props.setAttributes( { popup_offset: Number(newValue) } );
									} }
								/>
								<ToggleControl
									label={ __( 'Add popup preloader' ) }
									help={ __( 'Add box with loading animation while popup data is fetching from the server' ) }
									checked={ attributes.popup_preloader }
									onChange={ () => {
										props.setAttributes( { popup_preloader: ! attributes.popup_preloader } );
									} }
								/>
								{ this.getCustomControlsSection( 'section_popup_settings' ) }
							</PanelBody>
							{ ! window.JetEngineListingData.legacy.is_disabled && <PanelBody
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
							</PanelBody> }
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
							block="jet-engine/maps-listing"
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
