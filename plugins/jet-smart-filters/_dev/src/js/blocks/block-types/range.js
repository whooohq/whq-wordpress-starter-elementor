import { rangeIcon } from 'blocks/editor/icons';
import attributes from 'blocks/editor/attributes';
import General from 'blocks/editor/panels/general';
import TemplateRender from 'blocks/editor/controls/templateRender';
import RangeFilter from 'filters/Range';

const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
	InspectorControls
} = wp.editor;

registerBlockType('jet-smart-filters/range', {
	title: __('Range'),
	icon: rangeIcon,
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
		query_id: attributes.query_id,
	},
	className: 'jet-smart-filters-range',
	edit: class extends wp.element.Component {
		componentDidMount() {
			this._holder = $(window.ReactDOM.findDOMNode(this));
		}

		layoutUpdated(container) {
			const $filterContainer = $('.' + window.JetSmartFilters.filtersList.Range, container);

			if ($filterContainer.length)
				new RangeFilter($filterContainer);
		}

		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='range'
							disabledControls={
								{
									apply_button_text: !props.attributes.apply_button ? true : false,
									apply_on: !['ajax', 'mixed'].includes(props.attributes.apply_type) ? true : false
								}
							}
							{...props}
						/>
					</InspectorControls>
				),
				<div class="jet-smart-filters-block-holder">
					<TemplateRender
						block="jet-smart-filters/range"
						attributes={props.attributes}
						onSuccess={container => { this.layoutUpdated(container) }}
					/>
				</div>
			];
		}
	},
	save: () => {
		return null;
	}
});