import { removeIcon } from 'blocks/editor/icons';
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
	TextControl
} = wp.components;

const controlsOptions = {
	'applyType': arrayRemoveObjectByKey([...options.applyType], 'value', 'mixed')
};

registerBlockType('jet-smart-filters/remove-filters', {
	title: __('Remove Filters'),
	icon: removeIcon,
	category: 'jet-smart-filters',
	supports: {
		html: false
	},
	attributes: {
		// General
		filter_id: attributes.filter_id,
		content_provider: attributes.content_provider,
		apply_type: attributes.apply_type,
		remove_filters_text: attributes.remove_filters_text,
		query_id: attributes.query_id,
	},
	className: 'jet-smart-filters-remove-filters',
	edit: class extends wp.element.Component {
		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='remove-filters'
							controlsOptions={controlsOptions}
							{...props}
						>
							<TextControl
								type="text"
								label={__('Remove button text')}
								value={props.attributes.remove_filters_text}
								onChange={newValue => {
									props.setAttributes({ remove_filters_text: newValue });
								}}
							/>
						</General>
					</InspectorControls>
				),
				<div class="jet-smart-filters-block-holder">
					<TemplateRender
						block="jet-smart-filters/remove-filters"
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