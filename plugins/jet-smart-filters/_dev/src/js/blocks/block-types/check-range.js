import { checkRangeIcon } from 'blocks/editor/icons';
import attributes from 'blocks/editor/attributes';
import General from 'blocks/editor/panels/general';
import AdditionalSettings from 'blocks/editor/panels/additional-settings';
import Indexer from 'blocks/editor/panels/indexer';
import TemplateRender from 'blocks/editor/controls/templateRender';
import CheckRange from 'filters/CheckRange';

const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
	InspectorControls
} = wp.editor;

registerBlockType('jet-smart-filters/check-range', {
	title: __('Check Range Filter'),
	icon: checkRangeIcon,
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
		// Indexer
		apply_indexer: attributes.apply_indexer,
		show_counter: attributes.show_counter,
		counter_prefix: attributes.counter_prefix,
		counter_suffix: attributes.counter_suffix,
		show_items_rule: attributes.show_items_rule,
		change_items_rule: attributes.change_items_rule,
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
	className: 'jet-smart-filters-check-range',
	edit: class extends wp.element.Component {
		componentDidMount() {
			this._holder = $(window.ReactDOM.findDOMNode(this));
		}

		layoutUpdated() {
			this.initCheckRange();
		}

		initCheckRange() {
			const $filterContainer = this._holder.find('.' + window.JetSmartFilters.filtersList.CheckRange);

			if ($filterContainer.length)
				new CheckRange($filterContainer);
		}

		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='check-range'
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
					</InspectorControls>
				),
				<div class="jet-smart-filters-block-holder">
					<TemplateRender
						block="jet-smart-filters/check-range"
						attributes={props.attributes}
						onSuccess={() => { this.layoutUpdated(); }}
					/>
				</div>
			];
		}
	},
	save: () => {
		return null;
	}
});