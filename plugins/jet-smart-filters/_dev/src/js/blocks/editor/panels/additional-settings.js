const { __ } = wp.i18n;

const {
	PanelBody,
	ToggleControl,
	TextControl
} = wp.components;

export default props => {
	const {
		attributes,
		setAttributes
	} = props;

	return (
		<PanelBody title={__('Additional Settings')} initialOpen={false}>
			{attributes.search_enabled !== undefined && (
				<ToggleControl
					label={__('Search Enabled')}
					checked={attributes.search_enabled}
					onChange={newValue => {
						setAttributes({ search_enabled: newValue });
					}}
				/>
			)}
			{attributes.search_placeholder !== undefined && attributes.search_enabled === true && (
				<TextControl
					type="text"
					label={__('Search Placeholder')}
					value={attributes.search_placeholder}
					onChange={newValue => {
						setAttributes({ search_placeholder: newValue });
					}}
				/>
			)}
			<hr />
			{attributes.moreless_enabled !== undefined && (
				<ToggleControl
					label={__('More/Less Enabled')}
					checked={attributes.moreless_enabled}
					onChange={newValue => {
						setAttributes({ moreless_enabled: newValue });
					}}
				/>
			)}
			{attributes.less_items_count !== undefined && attributes.moreless_enabled === true && (
				<TextControl
					type="number"
					label={__('Less Items Count')}
					min={`1`}
					max={`50`}
					value={attributes.less_items_count}
					onChange={newValue => {
						setAttributes({ less_items_count: newValue });
					}}
				/>
			)}
			{attributes.more_text !== undefined && attributes.moreless_enabled === true && (
				<TextControl
					type="text"
					label={__('More Text')}
					value={attributes.more_text}
					onChange={newValue => {
						setAttributes({ more_text: newValue });
					}}
				/>
			)}
			{attributes.less_text !== undefined && attributes.moreless_enabled === true && (
				<TextControl
					type="text"
					label={__('Less Text')}
					value={attributes.less_text}
					onChange={newValue => {
						setAttributes({ less_text: newValue });
					}}
				/>
			)}
			<hr />
			{attributes.dropdown_enabled !== undefined && (
				<ToggleControl
					label={__('Dropdown Enabled')}
					checked={attributes.dropdown_enabled}
					onChange={newValue => {
						setAttributes({ dropdown_enabled: newValue });
					}}
				/>
			)}
			{attributes.dropdown_placeholder !== undefined && attributes.dropdown_enabled === true && (
				<TextControl
					type="text"
					label={__('Placeholder')}
					value={attributes.dropdown_placeholder}
					onChange={newValue => {
						setAttributes({ dropdown_placeholder: newValue });
					}}
				/>
			)}
			<hr />
			{attributes.scroll_enabled !== undefined && (
				<ToggleControl
					label={__('Scroll Enabled')}
					checked={attributes.scroll_enabled}
					onChange={newValue => {
						setAttributes({ scroll_enabled: newValue });
					}}
				/>
			)}
			{attributes.scroll_height !== undefined && attributes.scroll_enabled === true && (
				<TextControl
					type="number"
					label={__('Scroll Height(px)')}
					min={`100`}
					max={`1000`}
					value={attributes.scroll_height}
					onChange={newValue => {
						setAttributes({ scroll_height: newValue });
					}}
				/>
			)}
		</PanelBody>
	)
}