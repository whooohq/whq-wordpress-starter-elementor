import options from 'blocks/editor/options';

const { __ } = wp.i18n;

const {
	PanelBody,
	SelectControl,
	ToggleControl,
} = wp.components;

export default props => {
	const {
		attributes,
		setAttributes
	} = props;

	return (
		<PanelBody title={__('Indexer Options')} initialOpen={false}>
			{attributes.apply_indexer !== undefined && (
				<ToggleControl
					label={__('Apply Indexer')}
					checked={attributes.apply_indexer}
					onChange={newValue => {
						setAttributes({ apply_indexer: newValue });
					}}
				/>
			)}
			{attributes.show_counter !== undefined && attributes.apply_indexer === true && (
				<ToggleControl
					label={__('Show Counter')}
					checked={attributes.show_counter}
					onChange={newValue => {
						setAttributes({ show_counter: newValue });
					}}
				/>
			)}
			{attributes.show_items_rule !== undefined && attributes.apply_indexer === true && (
				<SelectControl
					label={__('If Item Empty')}
					value={attributes.show_items_rule}
					options={options.indexerShowItemsRule}
					onChange={newValue => {
						setAttributes({ show_items_rule: newValue });
					}}
				/>
			)}
			{attributes.change_items_rule !== undefined && attributes.apply_indexer === true && (
				<SelectControl
					label={__('Change Counters')}
					value={attributes.change_items_rule}
					options={options.indexerChangeItemsRule}
					onChange={newValue => {
						setAttributes({ change_items_rule: newValue });
					}}
				/>
			)}
			{props.children}
		</PanelBody>
	)
}