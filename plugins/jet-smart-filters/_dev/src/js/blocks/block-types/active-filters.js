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
	TextControl
} = wp.components;

const controlsOptions = {
	'applyType': arrayRemoveObjectByKey([...options.applyType], 'value', 'mixed')
};

registerBlockType('jet-smart-filters/active', {
	title: __('Active Filters'),
	icon: activeFiltersIcon,
	category: 'jet-smart-filters',
	supports: {
		html: false
	},
	attributes: {
		// General
		content_provider: attributes.content_provider,
		apply_type: attributes.apply_type,
		filters_label: attributes.filters_label,
		query_id: attributes.query_id,
	},
	className: 'jet-smart-filters-active',
	edit: class extends wp.element.Component {
		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='active-filters'
							controlsOptions={controlsOptions}
							{...props}
						>
							<TextControl
								type="text"
								label={__('Label')}
								value={props.attributes.filters_label}
								onChange={newValue => {
									props.setAttributes({ filters_label: newValue });
								}}
							/>
						</General>
					</InspectorControls>
				),
				<div class="jet-smart-filters-block-holder">
					<TemplateRender
						block="jet-smart-filters/active"
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