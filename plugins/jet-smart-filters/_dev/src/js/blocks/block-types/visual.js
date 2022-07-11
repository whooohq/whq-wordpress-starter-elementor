import { visualIcon } from 'blocks/editor/icons';
import attributes from 'blocks/editor/attributes';
import options from 'blocks/editor/options';
import General from 'blocks/editor/panels/general';
import AdditionalSettings from 'blocks/editor/panels/additional-settings';
import Indexer from 'blocks/editor/panels/indexer';
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
	SelectControl
} = wp.components;

registerBlockType('jet-smart-filters/color-image', {
	title: __('Visual'),
	icon: visualIcon,
	category: 'jet-smart-filters',
	supports: {
		html: false
	},
	attributes: {
		// General
		filter_id: attributes.filter_id,
		content_provider: attributes.content_provider,
		apply_type: attributes.apply_type,
		apply_on: attributes.apply_on,
		apply_button: attributes.apply_button,
		apply_button_text: attributes.apply_button_text,
		show_label: attributes.show_label,
		// Indexer
		apply_indexer: attributes.apply_indexer,
		show_counter: attributes.show_counter,
		show_items_rule: attributes.show_items_rule,
		change_items_rule: attributes.change_items_rule,
		// Filter Options
		show_items_label: attributes.show_items_label,
		filter_image_size: attributes.filter_image_size,
		// Additional Settings
		search_enabled: attributes.search_enabled,
		search_placeholder: attributes.search_placeholder,
		moreless_enabled: attributes.moreless_enabled,
		less_items_count: attributes.less_items_count,
		more_text: attributes.more_text,
		less_text: attributes.less_text,
		dropdown_enabled: attributes.dropdown_enabled,
		dropdown_placeholder: attributes.dropdown_placeholder,
		scroll_enabled: attributes.scroll_enabled,
		scroll_height: attributes.scroll_height,
	},
	className: 'jet-smart-filters-color-image',
	edit: class extends wp.element.Component {
		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='color-image'
							disabledControls={
								{
									apply_button_text: !props.attributes.apply_button ? true : false,
									apply_on: !['ajax', 'mixed'].includes(props.attributes.apply_type) ? true : false
								}
							}
							{...props}
						/>
						<AdditionalSettings
							{...props}
						/>
						<Indexer
							{...props}
						/>
						<PanelBody title={__('Filter Options')} initialOpen={false}>
							<ToggleControl
								label={__('Show items label')}
								checked={props.attributes.show_items_label}
								onChange={newValue => {
									props.setAttributes({ show_items_label: newValue });
								}}
							/>
							<SelectControl
								label={__('Image Size')}
								value={props.attributes.filter_image_size}
								options={options.imageSizes}
								onChange={newValue => {
									props.setAttributes({ filter_image_size: newValue });
								}}
							/>
						</PanelBody>
					</InspectorControls>
				),
				<div class="jet-smart-filters-block-holder">
					<TemplateRender
						block="jet-smart-filters/color-image"
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