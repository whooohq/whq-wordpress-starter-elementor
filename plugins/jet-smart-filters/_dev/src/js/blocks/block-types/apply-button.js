import { applyIcon } from 'blocks/editor/icons';
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

registerBlockType('jet-smart-filters/apply-button', {
	title: __('Apply Button'),
	icon: applyIcon,
	category: 'jet-smart-filters',
	supports: {
		html: false
	},
	attributes: {
		// General
		filter_id: attributes.filter_id,
		content_provider: attributes.content_provider,
		apply_type: attributes.apply_type,
		apply_button_text: attributes.apply_button_text,
		apply_redirect: attributes.apply_redirect,
		redirect_path: attributes.redirect_path,
	},
	className: 'jet-smart-filters-apply-button',
	edit: class extends wp.element.Component {
		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='apply-button'
							controlsOptions={controlsOptions}
							{...props}
						>
							<ToggleControl
								label={__('Apply Redirect')}
								checked={props.attributes.apply_redirect}
								onChange={newValue => {
									props.setAttributes({ apply_redirect: newValue });
								}}
							/>
							{props.attributes.apply_redirect && (
								<TextControl
									type="text"
									label={__('Redirect Path')}
									value={props.attributes.redirect_path}
									onChange={newValue => {
										props.setAttributes({ redirect_path: newValue });
									}}
								/>
							)}
						</General>
					</InspectorControls>
				),
				<div class="jet-smart-filters-block-holder">
					<TemplateRender
						block="jet-smart-filters/apply-button"
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