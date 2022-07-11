import { dateRangeIcon } from 'blocks/editor/icons';
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

const dateRangeUI = window.JetSmartFilters.filtersUI.dateRange;

registerBlockType('jet-smart-filters/date-range', {
	title: __('Date Range'),
	icon: dateRangeIcon,
	category: 'jet-smart-filters',
	supports: {
		html: false
	},
	attributes: {
		// General
		filter_id: attributes.filter_id,
		content_provider: attributes.content_provider,
		apply_type: attributes.apply_type,
		hide_apply_button: attributes.hide_apply_button,
		apply_button_text: attributes.apply_button_text,
		show_label: attributes.show_label,
	},
	className: 'jet-smart-filters-date-range',
	edit: class extends wp.element.Component {
		componentDidMount() {
			this._holder = $(window.ReactDOM.findDOMNode(this));
		}

		layoutUpdated() {
			this.initDateRangeUI();
		}

		initDateRangeUI() {
			dateRangeUI.init({
				$container: this._holder.find( '.' + window.JetSmartFilters.filtersList.DateRange ),
				id: this.props.attributes.blockID,
			});
		}

		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='date-range'
							disabledControls={
								{
									apply_button_text: props.attributes.hide_apply_button ? true : false
								}
							}
							{...props}
						/>
					</InspectorControls>
				),
				<div class="jet-smart-filters-block-holder">
					<TemplateRender
						block="jet-smart-filters/date-range"
						attributes={props.attributes}
						onSuccess={() => { this.layoutUpdated() }}
					/>
				</div>
			];
		}
	},
	save: () => {
		return null;
	}
});
