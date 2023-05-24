import options from 'blocks/editor/options';

const { __ } = wp.i18n;

const {
	PanelBody,
	SelectControl,
	ToggleControl,
	TextControl
} = wp.components;

export default props => {
	const {
		filterType,
		attributes,
		controlsOptions = {},
		disabledControls = {},
		setAttributes
	} = props;

	return (
		<PanelBody title={__('General')}>
			{attributes.filter_id !== undefined && !disabledControls.filter_id && (
				<SelectControl
					label={__('Select filter')}
					value={attributes.filter_id}
					options={options.filters(filterType)}
					onChange={newValue => {
						setAttributes({ filter_id: Number(newValue) });
					}}
				/>
			)}
			{attributes.content_provider !== undefined && !disabledControls.content_provider && (
				<SelectControl
					label={__('This filter for')}
					value={attributes.content_provider}
					options={options.providers}
					onChange={newValue => {
						setAttributes({ content_provider: newValue });
					}}
				/>
			)}
			{attributes.apply_type !== undefined && !disabledControls.apply_type && (
				<SelectControl
					label={__('Apply type')}
					value={attributes.apply_type}
					options={controlsOptions.applyType || options.applyType}
					onChange={newValue => {
						setAttributes({ apply_type: newValue });
					}}
				/>
			)}
			{attributes.apply_on !== undefined && !disabledControls.apply_on && (
				<SelectControl
					label={__('Apply on')}
					value={attributes.apply_on}
					options={options.applyOn}
					onChange={newValue => {
						setAttributes({ apply_on: newValue });
					}}
				/>
			)}
			{attributes.typing_min_letters_count !== undefined && !disabledControls.typing_min_letters_count && (
				<TextControl
					type="number"
					label={__('Min number of letters')}
					min={`1`}
					max={`10`}
					value={attributes.typing_min_letters_count}
					onChange={newValue => {
						setAttributes({ typing_min_letters_count: newValue });
					}}
				/>
			)}
			{attributes.apply_button !== undefined && !disabledControls.apply_button && (
				<ToggleControl
					label={__('Show apply button')}
					checked={attributes.apply_button}
					onChange={newValue => {
						setAttributes({ apply_button: newValue });
					}}
				/>
			)}
			{attributes.hide_apply_button !== undefined && !disabledControls.hide_apply_button && (
				<ToggleControl
					label={__('Hide apply button')}
					checked={attributes.hide_apply_button}
					onChange={newValue => {
						setAttributes({ hide_apply_button: newValue });
					}}
				/>
			)}
			{attributes.apply_button_text !== undefined && !disabledControls.apply_button_text && (
				<TextControl
					type="text"
					label={__('Apply button text')}
					value={attributes.apply_button_text}
					onChange={newValue => {
						setAttributes({ apply_button_text: newValue });
					}}
				/>
			)}
			{attributes.show_label !== undefined && !disabledControls.show_label && (
				<ToggleControl
					label={__('Show filter label')}
					checked={attributes.show_label}
					onChange={newValue => {
						setAttributes({ show_label: newValue });
					}}
				/>
			)}
			{attributes.query_id !== undefined && !disabledControls.query_id && (
				<TextControl
					type="text"
					label={__('Query ID')}
					help={__('Set unique query ID if you use multiple blocks of same provider on the page. Same ID you need to set for filtered block.')}
					value={attributes.query_id}
					onChange={newValue => {
						setAttributes({ query_id: newValue });
					}}
				/>
			)}
			{props.children}
		</PanelBody>
	)
}