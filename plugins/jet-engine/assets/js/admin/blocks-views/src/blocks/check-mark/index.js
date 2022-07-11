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

if ( -1 !== window.JetEngineListingData.activeModules.indexOf( 'booking-forms' ) ) {
	const GIcon = <SVG xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64" fill="none"><Rect width="64" height="64" fill="white"/><Rect x="1" y="12" width="16" height="16" rx="3" fill="#4AF3BA" stroke="#162B40" strokeWidth="2"/><Rect x="22" y="17" width="42" height="2" rx="1" fill="#162B40"/><Path d="M22 22C22 21.4477 22.4477 21 23 21H42C42.5523 21 43 21.4477 43 22C43 22.5523 42.5523 23 42 23H23C22.4477 23 22 22.5523 22 22Z" fill="#162B40"/><Rect x="22" y="40" width="42" height="2" rx="1" fill="#162B40"/><Path d="M22 45C22 44.4477 22.4477 44 23 44H42C42.5523 44 43 44.4477 43 45C43 45.5523 42.5523 46 42 46H23C22.4477 46 22 45.5523 22 45Z" fill="#162B40"/><Path d="M5 20L8 23L13 17" stroke="#162B40" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/><Rect x="1" y="35" width="16" height="16" rx="3" fill="white" stroke="#162B40" strokeWidth="2"/></SVG>;

	const blockAttributes = window.JetEngineListingData.atts.checkMark;

	registerBlockType( 'jet-engine/check-mark', {
		title: __( 'Check Mark' ),
		icon: GIcon,
		category: 'layout',
		attributes: blockAttributes,
		className: 'jet-form__check-mark',
		edit: class extends wp.element.Component {
			render() {

				const props      = this.props;
				const attributes = props.attributes;

				return [
					props.isSelected && (
						<InspectorControls
							key={ 'inspector' }
						>
							<PanelBody title={ __( 'General' ) }>
								<div className="jet-media-control components-base-control">
									<div className="components-base-control__label">{ __( 'Default Icon' ) }</div>
									{ attributes.check_mark_icon_default_url && <img src={ attributes.check_mark_icon_default_url } width="100%" height="auto"/> }
									<MediaUpload
										onSelect={ media => {
											props.setAttributes( {
												check_mark_icon_default:     media.id,
												check_mark_icon_default_url: media.url,
											} );
										} }
										type="image"
										value={ attributes.check_mark_icon_default }
										render={ ( { open } ) => (
											<IconButton
												isSecondary
												icon="edit"
												onClick={ open }
											>{ __( 'Select Icon' ) }</IconButton>
										) }
									/>
									{ attributes.check_mark_icon_default_url &&
									<IconButton
										onClick={ () => {
											props.setAttributes( {
												check_mark_icon_default: 0,
												check_mark_icon_default_url: '',
											} )
										} }
										isLink
										isDestructive
									>
										{ __( 'Remove Icon' ) }
									</IconButton>
									}
								</div>
								<div className="jet-media-control components-base-control">
									<div className="components-base-control__label">{ __( 'Checked Icon' ) }</div>
									{ attributes.check_mark_icon_checked_url && <img src={ attributes.check_mark_icon_checked_url } width="100%" height="auto"/> }
									<MediaUpload
										onSelect={ media => {
											props.setAttributes( {
												check_mark_icon_checked:     media.id,
												check_mark_icon_checked_url: media.url,
											} );
										} }
										type="image"
										value={ attributes.check_mark_icon_checked }
										render={ ( { open } ) => (
											<IconButton
												isSecondary
												icon="edit"
												onClick={ open }
											>{ __( 'Select Icon' ) }</IconButton>
										) }
									/>
									{ attributes.check_mark_icon_checked_url &&
									<IconButton
										onClick={ () => {
											props.setAttributes( {
												check_mark_icon_checked: 0,
												check_mark_icon_checked_url: '',
											} )
										} }
										isLink
										isDestructive
									>
										{ __( 'Remove Icon' ) }
									</IconButton>
									}
								</div>
							</PanelBody>
						</InspectorControls>
					),
					<Disabled>
						<ServerSideRender
							block="jet-engine/check-mark"
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
