import GroupedSelectControl from "components/grouped-select-control.js";

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
	Disabled,
	G,
	Path,
	Rect,
	Circle,
	SVG,
	ServerSideRender
} = wp.components;

const TRIcon = <SVG xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 64 64" fill="none"><G><Rect x="1" y="23" width="23" height="10" rx="2" fill="#6F8BFF" stroke="#162B40" strokeWidth="2"></Rect><Rect x="9" y="34" width="2" height="25" fill="#162B40"></Rect><Rect x="11" y="41" width="22" height="2" fill="#162B40"></Rect><Rect x="11" y="57" width="22" height="2" fill="#162B40"></Rect><Rect x="28" y="37" width="23" height="10" rx="2" fill="white" stroke="#162B40" strokeWidth="2"></Rect><Rect x="28" y="53" width="23" height="10" rx="2" fill="white" stroke="#162B40" strokeWidth="2"></Rect><Path d="M62.875 2.34483C62.875 2.9921 62.6523 3.54777 62.207 4.01185C61.7734 4.46372 61.2461 4.68966 60.625 4.68966C60.0039 4.68966 59.4707 4.46372 59.0254 4.01185C58.5918 3.54777 58.375 2.9921 58.375 2.34483C58.375 1.69756 58.5918 1.14799 59.0254 0.696121C59.4707 0.23204 60.0039 0 60.625 0C61.2461 0 61.7734 0.23204 62.207 0.696121C62.6523 1.14799 62.875 1.69756 62.875 2.34483ZM63.666 6.79634C63.4551 6.5643 63.1914 6.44828 62.875 6.44828C62.5586 6.44828 62.2949 6.5643 62.084 6.79634L61.1875 7.71228L55.2285 1.52047C55.0176 1.28843 54.7539 1.17241 54.4375 1.17241C54.1211 1.17241 53.8574 1.28843 53.6465 1.52047L51.3965 3.8653C51.1738 4.08513 51.0625 4.35991 51.0625 4.68966C51.0625 5.00718 51.1738 5.28197 51.3965 5.51401C51.6074 5.74605 51.8711 5.86207 52.1875 5.86207C52.5039 5.86207 52.7676 5.74605 52.9785 5.51401L54.4375 4.01185L55.9316 5.56897L54.0859 7.51078C53.8398 7.76724 53.6582 8.06645 53.541 8.40841C53.4355 8.73815 53.4004 9.0801 53.4355 9.43427C53.4707 9.78843 53.5762 10.1182 53.752 10.4235C53.9395 10.7166 54.1797 10.9608 54.4727 11.1562L56.793 12.6584L55.1934 15.1864C55.0176 15.4551 54.959 15.7482 55.0176 16.0657C55.0762 16.3833 55.2344 16.6275 55.4922 16.7985C55.5977 16.8718 55.7031 16.9206 55.8086 16.945C55.9141 16.9817 56.0195 17 56.125 17C56.3008 17 56.4707 16.9511 56.6348 16.8534C56.8105 16.768 56.9512 16.6458 57.0566 16.4871L59.3066 12.9698C59.3887 12.8355 59.4414 12.6889 59.4648 12.5302C59.5 12.3714 59.5059 12.2188 59.4824 12.0722C59.4473 11.9134 59.3828 11.773 59.2891 11.6509C59.207 11.5165 59.1016 11.4066 58.9727 11.3211L55.668 9.15948L57.5312 7.23599L60.3965 10.2037C60.502 10.3258 60.625 10.4174 60.7656 10.4784C60.9062 10.5273 61.0469 10.5517 61.1875 10.5517C61.3281 10.5517 61.4688 10.5273 61.6094 10.4784C61.75 10.4174 61.873 10.3258 61.9785 10.2037L63.666 8.44504C63.8887 8.22521 64 7.95654 64 7.63901C64 7.30927 63.8887 7.02838 63.666 6.79634ZM51.1504 10.5517L50.043 11.7241H47.125C46.8086 11.7241 46.5391 11.8402 46.3164 12.0722C46.1055 12.292 46 12.5668 46 12.8966C46 13.2263 46.1055 13.5072 46.3164 13.7392C46.5391 13.9591 46.8086 14.069 47.125 14.069H50.5C50.6523 14.069 50.793 14.0384 50.9219 13.9774C51.0625 13.9163 51.1855 13.8308 51.291 13.7209L52.75 12.2188C52.9727 11.9867 53.084 11.7119 53.084 11.3944C53.084 11.0647 52.9727 10.7838 52.75 10.5517C52.5273 10.3197 52.2578 10.2037 51.9414 10.2037C51.6367 10.2037 51.373 10.3197 51.1504 10.5517Z" fill="#162B40"></Path></G></SVG>;

registerBlockType( 'jet-engine/dynamic-terms', {
	title: __( 'Dynamic Terms' ),
	icon: TRIcon,
	category: 'layout',
	attributes: {
		from_tax: {
			type: 'string',
			default: '',
		},
		show_all_terms: {
			type: 'boolean',
			default: true,
		},
		terms_num: {
			type: 'number',
			default: 1,
		},
		terms_delimiter: {
			type: 'string',
			default: ',',
		},
		terms_linked: {
			type: 'boolean',
			default: true,
		},
		selected_terms_icon: {
			type: 'number',
			default: 0,
		},
		selected_terms_icon_url: {
			type: 'string',
			default: '',
		},
		terms_prefix: {
			type: 'string',
			default: '',
		},
		terms_suffix: {
			type: 'string',
			default: '',
		},
		hide_if_empty: {
			type: 'boolean',
			default: false,
		},
	},
	className: 'jet-listing-dynamic-terms',
	usesContext: [ 'postId', 'postType', 'queryId' ],
	edit: class extends wp.element.Component {

		render() {

			const props      = this.props;
			const attributes = props.attributes;
			const taxList    = window.JetEngineListingData.taxonomies;

			var object = window.JetEngineListingData.object_id;
			var listing = window.JetEngineListingData.settings;

			if ( props.context.queryId ) {
				object  = props.context.postId;
				listing = {
					listing_source: 'posts',
					listing_post_type: props.context.postType,
				};
			}

			return [
				props.isSelected && (
					<InspectorControls
						key={ 'inspector' }
					>
						<PanelBody title={ __( 'General' ) }>

							<GroupedSelectControl
								label={ __( 'From taxonomy' ) }
								value={ attributes.from_tax }
								options={ taxList }
								onChange={ newValue => {
									props.setAttributes({ from_tax: newValue });
								}}
							/>

							<ToggleControl
								label={ __( 'Show all terms' ) }
								checked={ attributes.show_all_terms }
								onChange={ () => {
									props.setAttributes({ show_all_terms: ! attributes.show_all_terms });
								} }
							/>

							{ ! attributes.show_all_terms &&
								<TextControl
									type="number"
									min="1"
									max="20"
									label={ __("Terms number to show") }
									value={attributes.terms_num}
									onChange={ newValue =>
										props.setAttributes({
											terms_num: Number(newValue)
										})
									}
								/>
							}

							<TextControl
								type="text"
								label={ __("Delimiter") }
								value={attributes.terms_delimiter}
								onChange={ newValue =>
									props.setAttributes({
										terms_delimiter: newValue
									})
								}
							/>

							<ToggleControl
								label={ __( 'Linked terms' ) }
								checked={ attributes.terms_linked }
								onChange={ () => {
									props.setAttributes({ terms_linked: ! attributes.terms_linked });
								} }
							/>

							<div className="jet-media-control components-base-control">
								{ attributes.selected_terms_icon_url &&
									<img src={ attributes.selected_terms_icon_url } width="100%" height="auto" />
								}
								<MediaUpload
									onSelect={ media => {
											props.setAttributes( {
												selected_terms_icon: media.id,
												selected_terms_icon_url: media.url,
											} );
										}
									}
									type="image"
									value={attributes.selected_terms_icon}
									render={({ open }) => (
										<IconButton
											isSecondary
											icon="edit"
											onClick={ open }
										>{ __("Select Icon") }</IconButton>
									)}
								/>
								{ attributes.selected_terms_icon_url &&
									<IconButton
										onClick={ () => {
											props.setAttributes( {
												selected_terms_icon: 0,
												selected_terms_icon_url: '',
											} )
										} }
										isLink
										isDestructive
									>
										{ __( 'Remove Icon' ) }
									</IconButton>
								}
							</div>

							<TextControl
								type="text"
								label={ __("Text before terms list") }
								value={attributes.terms_prefix}
								onChange={ newValue =>
									props.setAttributes({
										terms_prefix: newValue
									})
								}
							/>

							<TextControl
								type="text"
								label={ __("Text after terms list") }
								value={attributes.terms_suffix}
								onChange={ newValue =>
									props.setAttributes({
										terms_suffix: newValue
									})
								}
							/>

							<ToggleControl
								label={ __( 'Hide if value is empty' ) }
								checked={ attributes.hide_if_empty }
								onChange={ () => {
									props.setAttributes({ hide_if_empty: ! attributes.hide_if_empty });
								} }
							/>

						</PanelBody>
					</InspectorControls>
				),
				<Disabled>
					<ServerSideRender
						block="jet-engine/dynamic-terms"
						attributes={ attributes }
						urlQueryArgs={ {
							object: object,
							listing: listing
						} }
					/>
				</Disabled>
			];
		}
	},
	save: props => {
		return null;
	}
} );
