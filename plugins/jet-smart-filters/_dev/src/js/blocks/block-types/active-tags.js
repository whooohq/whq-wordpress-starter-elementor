import { activeFiltersIcon } from 'blocks/editor/icons';
import attributes from 'blocks/editor/attributes';
import options from 'blocks/editor/options';
import General from 'blocks/editor/panels/general';
import TemplateRender from 'blocks/editor/controls/templateRender';
import {
	arrayRemoveObjectByKey
} from 'includes/utility';

const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
	InspectorControls
} = wp.editor;

const {
	ToggleControl,
	TextControl
} = wp.components;

const controlsOptions = {
	'applyType': arrayRemoveObjectByKey([...options.applyType], 'value', 'mixed')
};

registerBlockType('jet-smart-filters/active-tags', {
	title: __('Active Tags'),
	icon: activeFiltersIcon,
	category: 'jet-smart-filters',
	supports: {
		html: false
	},
	attributes: {
		// General
		content_provider: attributes.content_provider,
		apply_type: attributes.apply_type,
		tags_label: attributes.tags_label,
		clear_item: attributes.clear_item,
		clear_item_label: attributes.clear_item_label,
		query_id: attributes.query_id,
	},
	className: 'jet-smart-filters-active-tags',
	edit: class extends wp.element.Component {
		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='active-tags'
							controlsOptions={controlsOptions}
							{...props}
						>
							<TextControl
								type="text"
								label={__('Label')}
								value={props.attributes.tags_label}
								onChange={newValue => {
									props.setAttributes({ tags_label: newValue });
								}}
							/>
							<ToggleControl
								label={__('Clear Item')}
								checked={props.attributes.clear_item}
								onChange={newValue => {
									props.setAttributes({ clear_item: newValue });
								}}
							/>
							{props.attributes.clear_item && (
								<TextControl
									type="text"
									label={__('Label')}
									value={props.attributes.clear_item_label}
									onChange={newValue => {
										props.setAttributes({ clear_item_label: newValue });
									}}
								/>
							)}
						</General>
					</InspectorControls>
				),
				<div class="jet-smart-filters-block-holder">
					<TemplateRender
						block="jet-smart-filters/active-tags"
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