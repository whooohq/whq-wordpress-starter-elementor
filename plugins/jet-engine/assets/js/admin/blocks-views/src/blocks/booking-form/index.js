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
	const GIcon = <SVG xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64" fill="none"><Rect fill="white" x="1" y="5" width="44" height="53" rx="3" stroke="#162B40" strokeWidth="2"></Rect><Path d="M7 49C7 47.3431 8.34315 46 10 46H21C22.6569 46 24 47.3431 24 49C24 50.6569 22.6569 52 21 52H10C8.34315 52 7 50.6569 7 49Z" fill="#4AF3BA" stroke="#162B40" strokeWidth="2"></Path><Rect fill="white" x="7" y="33" width="32" height="6" rx="1" stroke="#162B40" strokeWidth="2"></Rect><Rect fill="white" x="7" y="23" width="32" height="6" rx="1" stroke="#162B40" strokeWidth="2"></Rect><Rect fill="white" x="6.5" y="14.5" width="18" height="1" rx="0.5" stroke="#162B40"></Rect><Rect fill="white" x="6.5" y="10.5" width="33" height="1" rx="0.5" stroke="#162B40"></Rect></SVG>;

	const blockAttributes = window.JetEngineListingData.atts.bookingForm;

	registerBlockType( 'jet-engine/booking-form', {
		title: __( 'Form' ),
		icon: GIcon,
		category: 'layout',
		attributes: blockAttributes,
		className: 'jet-form',
		edit: class extends wp.element.Component {
			render() {

				const props         = this.props;
				const attributes    = props.attributes;
				const formsOptions  = window.JetEngineListingData.formsOptions;

				return [
					props.isSelected && (
						<InspectorControls
							key={ 'inspector' }
						>
							<PanelBody title={ __( 'General' ) }>
								<SelectControl
									label={ __( 'Select form' ) }
									value={ attributes._form_id }
									options={ formsOptions }
									onChange={ newValue => {
										props.setAttributes( { _form_id: newValue } );
									} }
								/>
								<SelectControl
									label={ __( 'Fields layout' ) }
									value={ attributes.fields_layout }
									options={ [
										{
											value: 'column',
											label: __( 'Column' ),
										},
										{
											value: 'row',
											label: __( 'Row' ),
										},
									] }
									onChange={ newValue => {
										props.setAttributes( { fields_layout: newValue } );
									} }
								/>
								<SelectControl
									label={ __( 'Fields label HTML tag' ) }
									value={ attributes.fields_label_tag }
									options={ [
										{
											value: 'div',
											label: __( 'DIV' ),
										},
										{
											value: 'label',
											label: __( 'LABEL' ),
										},
									] }
									onChange={ newValue => {
										props.setAttributes( { fields_label_tag: newValue } );
									} }
								/>
								<SelectControl
									label={ __( 'Submit type' ) }
									value={ attributes.submit_type }
									options={ [
										{
											value: 'reload',
											label: __( 'Reload' ),
										},
										{
											value: 'ajax',
											label: __( 'AJAX' ),
										},
									] }
									onChange={ newValue => {
										props.setAttributes( { submit_type: newValue } );
									} }
								/>
								<ToggleControl
									label={ __( 'Cache form output' ) }
									checked={ attributes.cache_form }
									onChange={ () => {
										props.setAttributes( { cache_form: ! attributes.cache_form } );
									} }
								/>
								<hr/>
								<ToggleControl
									label={ __( 'Divider between rows' ) }
									checked={ attributes.rows_divider }
									onChange={ () => {
										props.setAttributes( { rows_divider: ! attributes.rows_divider } );
									} }
								/>
								<TextControl
									type="text"
									label={ __( 'Required mark' ) }
									value={ attributes.required_mark }
									onChange={ newValue => {
										props.setAttributes( { required_mark: newValue } );
									} }
								/>
							</PanelBody>
						</InspectorControls>
					),
					<Disabled>
						<ServerSideRender
							block="jet-engine/booking-form"
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
