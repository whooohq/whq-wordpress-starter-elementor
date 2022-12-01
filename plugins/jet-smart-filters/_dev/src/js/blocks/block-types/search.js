import { searchIcon } from 'blocks/editor/icons';
import attributes from 'blocks/editor/attributes';
import options from 'blocks/editor/options';
import General from 'blocks/editor/panels/general';
import TemplateRender from 'blocks/editor/controls/templateRender';

const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
	InspectorControls
} = wp.editor;

const applyTypeOptions = [...options.applyType],
	controlsOptions = {};

applyTypeOptions.splice(1, 0, {
	value: 'ajax-ontyping',
	label: __('AJAX on typing')
});
controlsOptions.applyType = applyTypeOptions;

registerBlockType('jet-smart-filters/search', {
	title: __('Search'),
	icon: searchIcon,
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
		typing_min_letters_count: attributes.typing_min_letters_count,
		show_label: attributes.show_label,
		query_id: attributes.query_id,
	},
	className: 'jet-smart-filters-search',
	edit: class extends wp.element.Component {
		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='search'
							controlsOptions={controlsOptions}
							disabledControls={
								{
									apply_button_text: props.attributes.apply_type === 'ajax-ontyping' ? true : false,
									typing_min_letters_count: props.attributes.apply_type !== 'ajax-ontyping' ? true : false,
								}
							}
							{...props}
						/>
					</InspectorControls>
				),
				<div class="jet-smart-filters-block-holder">
					<TemplateRender
						block="jet-smart-filters/search"
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