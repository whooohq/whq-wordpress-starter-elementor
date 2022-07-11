const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

const {
	SVG,
	Path
} = wp.primitives;

const {
	InspectorControls
} = wp.editor;

const {
	PanelBody,
	SelectControl,
	Disabled,
	ServerSideRender
} = wp.components;

const Icon = (
	<SVG xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
		<path d='M29.5385 36.425V36.0322L29.2712 35.7445L18.0477 23.6605C18.0289 23.6358 18.0167 23.6166 18.009 23.6028C18.0022 23.5751 18 23.5524 18 23.5333V20.1333C18 20.1155 18.0015 20.1047 18.0025 20.0995C18.0031 20.0968 18.0035 20.0951 18.0038 20.0942L18.0043 20.0928L18.0047 20.092L18.0067 20.0888C18.0089 20.0856 18.0143 20.0784 18.0253 20.0676C18.0941 20 18.1246 20 18.153 20L18.1538 20H45.8462C45.861 20 45.8688 20.0014 45.871 20.0018C45.8712 20.0019 45.8714 20.0019 45.8716 20.0019C45.8723 20.0024 45.8734 20.0032 45.875 20.0043C45.8794 20.0075 45.8887 20.015 45.9021 20.0297L45.9377 20.0685L45.9771 20.1034C45.9889 20.1138 45.996 20.1215 45.9998 20.1261C45.9999 20.1281 46 20.1305 46 20.1333V23.5333C46 23.5524 45.9978 23.5751 45.991 23.6028C45.9833 23.6166 45.9711 23.6358 45.9523 23.6605L34.7288 35.7445L34.4615 36.0322V36.425V48.4667C34.4615 48.475 34.4606 48.4783 34.4604 48.4791L34.4604 48.4791C34.4602 48.4799 34.4589 48.4848 34.4529 48.4951L34.4388 48.5193L34.4261 48.5443C34.426 48.5445 34.4231 48.5504 34.4137 48.5615C34.406 48.5708 34.3935 48.5842 34.3742 48.6012L29.7843 51.9824C29.778 51.987 29.7707 51.992 29.7624 51.9971C29.7388 51.999 29.7154 52 29.6923 52C29.6844 52 29.6755 51.9995 29.6654 51.998L29.6068 51.9788L29.5978 51.9758C29.5871 51.9662 29.5756 51.9525 29.5624 51.9319C29.5386 51.8944 29.5385 51.8786 29.5385 51.8667V36.425Z' fill='transparent' stroke='#162B40' stroke-width='2' /><path d='M18 14C18 12.3431 19.3431 11 21 11H43C44.6569 11 46 12.3431 46 14V20.5385H18V14Z' fill='#4AF3BA' stroke='#162B40' stroke-width='2' />
	</SVG>
);

registerBlockType( 'jet-smart-filters/user-geolocation', {
	title: __( 'User Geolcation' ),
	icon: Icon,
	category: 'jet-smart-filters',
	supports: {
		html: false
	},
	attributes: {
		// General
		filter_id: {
			type: 'number',
			default: 0,
		},
		content_provider: {
			type: 'string',
			default: 'not-selected',
		},
	},
	className: 'jet-smart-filters-alphabet',
	edit: class extends wp.element.Component {

		getOtptionsFromObject( object ) {

			const result = [];

			for ( const [ value, label ] of Object.entries( object ) ) {
				result.push( {
					value: value,
					label: label,
				} );
			}

			return result;

		}

		render() {
			
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<PanelBody title={__( 'General' )}>
							<SelectControl
								label={ __( 'Select filter' ) }
								value={ props.attributes.filter_id }
								options={ this.getOtptionsFromObject( window.JetSmartFilterBlocksData.filters['user-geolocation'] ) }
								onChange={ newValue => {
									props.setAttributes({ filter_id: Number(newValue) });
								} }
							/>
							<SelectControl
								label={ __( 'This filter for' ) }
								value={ props.attributes.content_provider }
								options={ this.getOtptionsFromObject( window.JetSmartFilterBlocksData.providers ) }
								onChange={ newValue => {
									props.setAttributes({ content_provider: newValue });
								} }
							/>
						</PanelBody>
					</InspectorControls>
				),
				<Disabled>
					<ServerSideRender
						block="jet-smart-filters/user-geolocation"
						attributes={ props.attributes }
					/>
				</Disabled>
			];

		}

	},
	save: props => {
		return null;
	},
} );
