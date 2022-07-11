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
	RadioControl,
	CheckboxControl,
	Disabled,
	G,
	Path,
	Circle,
	Rect,
	SVG,
	ColorPalette,
	ServerSideRender
} = wp.components;

const Icon = <SVG xmlns="http://www.w3.org/2000/svg" width="64" height="47" viewBox="0 0 64 47" fill="none"><Rect x="62" y="1" width="16.3665" height="17.4576" transform="rotate(90 62 1)" fill="#4AF3BA"/><Path d="M41 34.0477H4.875C2.7349 34.0477 1 32.2568 1 30.0477V5.04767C1 2.83853 2.7349 1.04767 4.875 1.04767H59.125C61.2651 1.04767 63 2.83853 63 5.04767V30.0477C63 32.2568 61.2651 34.0477 59.125 34.0477H52.5" fill="none" stroke="#162B40" strokeWidth="2"/><Path d="M31 9.90482V11.6905C31 13.2642 26.9688 14.5477 22 14.5477C17.0312 14.5477 13 13.2642 13 11.6905V9.90482C13 8.33114 17.0312 7.04767 22 7.04767C26.9688 7.04767 31 8.33114 31 9.90482ZM31 13.9227V17.9405C31 19.5142 26.9688 20.7977 22 20.7977C17.0312 20.7977 13 19.5142 13 17.9405V13.9227C14.9336 15.2173 18.4726 15.82 22 15.82C25.5274 15.82 29.0664 15.2173 31 13.9227ZM31 20.1727V24.1905C31 25.7642 26.9688 27.0477 22 27.0477C17.0312 27.0477 13 25.7642 13 24.1905V20.1727C14.9336 21.4673 18.4726 22.07 22 22.07C25.5274 22.07 29.0664 21.4673 31 20.1727Z" fill="#162B40"/><Path d="M56.9937 30.9227L57.875 30.0414L54.3812 26.5477L57.875 23.0539L56.9938 22.1727L53.5 25.6664L50.0062 22.1727L49.125 23.0539L52.6187 26.5477L49.125 30.0414L50.0062 30.9227L53.5 27.4289L56.9937 30.9227Z" fill="#162B40"/><Path d="M53.4339 44.8748L53.4559 44.8663L53.4776 44.8566C53.7631 44.7298 54.0204 44.5568 54.248 44.3444L54.2668 44.3268L54.2847 44.3084C54.7509 43.826 55 43.2238 55 42.55C55 41.8762 54.7509 41.2739 54.2847 40.7916L54.2782 40.7849L54.2716 40.7783L50.7422 37.2605L54.054 35.7542C54.345 35.6312 54.5923 35.4195 54.7543 35.1278C54.9007 34.8644 54.9659 34.566 54.9351 34.2581C54.9032 33.9389 54.7714 33.6564 54.5729 33.4295C54.3697 33.1973 54.1007 33.031 53.7856 32.9626L41.8209 30.0911C41.5836 30.0327 41.3391 30.0332 41.1019 30.0925C40.841 30.1577 40.6119 30.2912 40.4277 30.4754L40.3956 30.5074L40.3666 30.5423C40.2383 30.6961 40.1443 30.8659 40.0786 31.041L40.0586 31.0943L40.0448 31.1496C39.9855 31.3868 39.9851 31.6312 40.0434 31.8685L42.9149 43.8332C42.9833 44.1483 43.1496 44.4173 43.3818 44.6205C43.6087 44.819 43.8912 44.9509 44.2104 44.9828C44.5183 45.0136 44.8167 44.9483 45.0801 44.802C45.3718 44.64 45.5836 44.3927 45.7065 44.1016L47.2129 40.7899L50.7307 44.3193L50.7434 44.3321L50.7566 44.3444C50.9842 44.5568 51.2416 44.7298 51.527 44.8566L51.5487 44.8663L51.5707 44.8748C51.8701 44.9913 52.1831 45.0477 52.5023 45.0477C52.8215 45.0477 53.1345 44.9913 53.4339 44.8748Z" fill="none" stroke="#162B40" strokeWidth="2"/><Path d="M52.3333 13.7143L52.3333 9.71432L48.3333 9.71432L48.3333 8.38098L52.3333 8.38098L52.3333 4.38098L53.6666 4.38098L53.6666 8.38098L57.6666 8.38098L57.6666 9.71432L53.6666 9.71432L53.6666 13.7143L52.3333 13.7143Z" fill="#162B40"/><Rect x="62" y="17.0477" width="2" height="17" transform="rotate(90 62 17.0477)" fill="#162B40"/><Rect x="45" y="30.0477" width="2" height="28" transform="rotate(-180 45 30.0477)" fill="#162B40"/></SVG>;

registerBlockType( 'jet-engine/data-store-button', {
	title: __( 'Data Store Button' ),
	icon: Icon,
	category: 'layout',
	attributes: window.JetEngineListingData.atts.dataStoreButton,
	className: 'jet-data-store-link-wrapper',
	edit: function( props ) {

		const attributes    = props.attributes;
		const storesOptions = window.JetEngineListingData.dataStores;

		function inArray( needle, highstack ) {
			return 0 <= highstack.indexOf( needle );
		}

		return [
			props.isSelected && (
				<InspectorControls
					key={ 'inspector' }
				>
					<PanelBody title={ __( 'General' ) }>

						<SelectControl
							label={__( 'Source' )}
							value={attributes.store}
							options={storesOptions}
							onChange={newValue => {
								props.setAttributes( { store: newValue } );
							}}
						/>
						<TextControl
							type="text"
							label={__( "Label" )}
							value={attributes.label}
							onChange={newValue =>
								props.setAttributes( {
									label: newValue
								} )
							}
						/>
						<div className="jet-media-control components-base-control">
							<div className="components-base-control__label">{__( 'Icon' )}</div>
							{attributes.icon_url && <img src={attributes.icon_url} width="100%" height="auto"/>}
							<MediaUpload
								onSelect={media => {
									props.setAttributes( {
										icon:     media.id,
										icon_url: media.url,
									} );
								}}
								type="image"
								value={attributes.icon}
								render={( { open } ) => (
									<IconButton
										isSecondary
										icon="edit"
										onClick={open}
									>{__( 'Select Icon' )}</IconButton>
								)}
							/>
							{ attributes.icon_url &&
								<IconButton
									onClick={() => {
										props.setAttributes( {
											icon:     0,
											icon_url: '',
										} )
									}}
									isLink
									isDestructive
								>
									{__( 'Remove Icon' )}
								</IconButton>
							}
						</div>
						<SelectControl
							label={__( 'Action after an item added to store' )}
							value={attributes.action_after_added}
							options={[
								{
									value: 'remove_from_store',
									label: __( 'Remove from store' ),
								},
								{
									value: 'switch_status',
									label: __( 'Switch button status' ),
								},
								{
									value: 'hide',
									label: __( 'Hide button' ),
								},
							]}
							onChange={newValue => {
								props.setAttributes( { action_after_added: newValue } );
							}}
						/>
						{inArray( attributes.action_after_added, ['switch_status', 'remove_from_store'] ) &&
							<TextControl
								type="text"
								label={__( "Label after added to store" )}
								value={attributes.added_to_store_label}
								onChange={newValue =>
									props.setAttributes( {
										added_to_store_label: newValue
									} )
								}
							/>
						}
						{inArray( attributes.action_after_added, ['switch_status', 'remove_from_store'] ) &&
							<div className="jet-media-control components-base-control">
								<div className="components-base-control__label">{__( 'Icon after added to store' )}</div>
								{attributes.added_to_store_icon_url && <img src={attributes.added_to_store_icon_url} width="100%" height="auto"/>}
								<MediaUpload
									onSelect={media => {
										props.setAttributes( {
											added_to_store_icon:     media.id,
											added_to_store_icon_url: media.url,
										} );
									}}
									type="image"
									value={attributes.added_to_store_icon}
									render={( { open } ) => (
										<IconButton
											isSecondary
											icon="edit"
											onClick={open}
										>{__( 'Select Icon' )}</IconButton>
									)}
								/>
								{attributes.added_to_store_icon_url &&
									<IconButton
										onClick={() => {
											props.setAttributes( {
												added_to_store_icon:     0,
												added_to_store_icon_url: '',
											} )
										}}
										isLink
										isDestructive
									>
										{__( 'Remove Icon' )}
									</IconButton>
								}
							</div>
						}
						{'switch_status' === attributes.action_after_added &&
							<div>
								<TextControl
									type="text"
									label={__( "URL after added to store" )}
									value={attributes.added_to_store_url}
									onChange={newValue =>
										props.setAttributes( {
											added_to_store_url: newValue
										} )
									}
								/>
								<ToggleControl
									label={__( 'Open in new window' )}
									checked={attributes.open_in_new}
									onChange={() => {
										props.setAttributes( { open_in_new: !attributes.open_in_new } );
									}}
								/>
								<SelectControl
									label={__( 'Add "rel" attr' )}
									value={attributes.rel_attr}
									options={[
										{
											value: '',
											label: __( 'No' ),
										},
										{
											value: 'alternate',
											label: __( 'Alternate' ),
										},
										{
											value: 'author',
											label: __( 'Author' ),
										},
										{
											value: 'bookmark',
											label: __( 'Bookmark' ),
										},
										{
											value: 'external',
											label: __( 'External' ),
										},
										{
											value: 'help',
											label: __( 'Help' ),
										},
										{
											value: 'license',
											label: __( 'License' ),
										},
										{
											value: 'next',
											label: __( 'Next' ),
										},
										{
											value: 'nofollow',
											label: __( 'Nofollow' ),
										},
										{
											value: 'noreferrer',
											label: __( 'Noreferrer' ),
										},
										{
											value: 'noopener',
											label: __( 'Noopener' ),
										},
										{
											value: 'prev',
											label: __( 'Prev' ),
										},
										{
											value: 'search',
											label: __( 'Search' ),
										},
										{
											value: 'tag',
											label: __( 'Tag' ),
										},
									]}
									onChange={newValue => {
										props.setAttributes( { rel_attr: newValue } );
									}}
								/>
							</div>
						}

					</PanelBody>
				</InspectorControls>
			),
			<Disabled>
				<ServerSideRender
					block="jet-engine/data-store-button"
					attributes={ attributes }
				/>
			</Disabled>
		];
	},
	save: props => {
		return null;
	}
} );
