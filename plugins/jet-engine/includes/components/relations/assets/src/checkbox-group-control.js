const {
	BaseControl,
	CheckboxControl
} = wp.components;

const {
	Component,
	Fragment
} = wp.element;

const {
	assign
} = window.lodash;

class CheckboxGroupControl extends Component {

	constructor( props ) {

		super( props );

		this.state = {
			value: this.props.value || []
		};

	}

	render() {

		const {
			label,
			help,
			options,
			onChange,
		} = this.props;

		return <BaseControl
			label={ label }
			help={ help }
		>
			{ options.map( ( option, index ) => {
				return <CheckboxControl
					label={ option.label }
					key={ 'check_' + option.value + index }
					checked={ this.state.value.includes( option.value ) }
					onChange={ () => {

						const checkedList = this.state.value;

						if ( checkedList.includes( option.value ) ) {
							checkedList.splice( checkedList.indexOf( option.value ), 1 );
						} else {
							checkedList.push( option.value );
						}

						this.setState(
							{ value: checkedList },
							() => {
								onChange( this.state.value )
							}
						);
					} }
				/>
			} ) }
		</BaseControl>;
	}

}

export default CheckboxGroupControl;
