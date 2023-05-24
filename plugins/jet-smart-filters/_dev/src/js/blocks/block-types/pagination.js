import { paginationIcon } from 'blocks/editor/icons';
import attributes from 'blocks/editor/attributes';
import General from 'blocks/editor/panels/general';
import TemplateRender from 'blocks/editor/controls/templateRender';

const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
	InspectorControls
} = wp.editor;

const {
	PanelBody,
	ToggleControl,
	TextControl
} = wp.components;

registerBlockType('jet-smart-filters/pagination', {
	title: __('Pagination'),
	icon: paginationIcon,
	category: 'jet-smart-filters',
	supports: {
		html: false
	},
	attributes: {
		// General
		content_provider: attributes.content_provider,
		apply_type: attributes.apply_type,
		query_id: attributes.query_id,
		// Pagination Controls
		enable_items: attributes.enable_items,
		pages_center_offset: attributes.pages_center_offset,
		pages_end_offset: attributes.pages_end_offset,
		enable_prev_next: attributes.enable_prev_next,
		prev_text: attributes.prev_text,
		next_text: attributes.next_text,
		enable_load_more: attributes.enable_load_more,
		load_more_text: attributes.load_more_text,
		autoscroll: attributes.autoscroll,
		provider_top_offset: attributes.provider_top_offset
	},
	className: 'jet-smart-filters-pagination',
	edit: class extends wp.element.Component {
		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='pagination'
							{...props}
						/>
						<PanelBody title={__('Controls')} initialOpen={false}>
							<ToggleControl
								label={__('Enable Items')}
								checked={props.attributes.enable_items}
								onChange={newValue => {
									props.setAttributes({ enable_items: newValue });
								}}
							/>
							{props.attributes.enable_items && [
								<TextControl
									type="number"
									label={__('Items center offset')}
									min={`0`}
									max={`50`}
									value={props.attributes.pages_center_offset}
									onChange={newValue => {
										props.setAttributes({ pages_center_offset: parseInt(newValue) });
									}}
								/>,
								<TextControl
									type="number"
									label={__('Items edge offset')}
									min={`0`}
									max={`50`}
									value={props.attributes.pages_end_offset}
									onChange={newValue => {
										props.setAttributes({ pages_end_offset: parseInt(newValue) });
									}}
								/>
							]}
							<ToggleControl
								label={__('Enable Prev/Next buttons')}
								checked={props.attributes.enable_prev_next}
								onChange={newValue => {
									props.setAttributes({ enable_prev_next: newValue });
								}}
							/>
							{props.attributes.enable_prev_next && [
								<TextControl
									type="text"
									label={__('Prev Text')}
									value={props.attributes.prev_text}
									onChange={newValue => {
										props.setAttributes({ prev_text: newValue });
									}}
								/>,
								<TextControl
									type="text"
									label={__('Next Text')}
									value={props.attributes.next_text}
									onChange={newValue => {
										props.setAttributes({ next_text: newValue });
									}}
								/>
							]}
							<ToggleControl
								label={__('Enable Load More')}
								checked={props.attributes.enable_load_more}
								onChange={newValue => {
									props.setAttributes({ enable_load_more: newValue });
								}}
							/>
							{props.attributes.enable_load_more && (
								<TextControl
									type="text"
									label={__('Load More Text')}
									value={props.attributes.load_more_text}
									onChange={newValue => {
										props.setAttributes({ load_more_text: newValue });
									}}
								/>
							)}
							<ToggleControl
								label={__('Enable autoscroll')}
								checked={props.attributes.autoscroll}
								onChange={newValue => {
									props.setAttributes({ autoscroll: newValue });
								}}
							/>
							{props.attributes.autoscroll && (
								<TextControl
									type="number"
									label={__('Provider top offset')}
									min={`0`}
									max={`300`}
									value={props.attributes.provider_top_offset}
									onChange={newValue => {
										props.setAttributes({ provider_top_offset: parseInt(newValue) });
									}}
								/>
							)}
						</PanelBody>
					</InspectorControls>
				),
				<div class="jet-smart-filters-block-holder">
					<TemplateRender
						block="jet-smart-filters/pagination"
						attributes={props.attributes}
					/>
				</div>
			];
		}
	},
	save: () => {
		return null;
	}
});