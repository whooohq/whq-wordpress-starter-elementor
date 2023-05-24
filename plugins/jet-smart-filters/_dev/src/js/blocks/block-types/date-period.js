import { datePeriodIcon } from 'blocks/editor/icons';
import attributes from 'blocks/editor/attributes';
import General from 'blocks/editor/panels/general';
import TemplateRender from 'blocks/editor/controls/templateRender';
import DatePeriod from 'filters/DatePeriod';

const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
	InspectorControls
} = wp.editor;

const dateRangeUI = window.JetSmartFilters.filtersUI.dateRange;

registerBlockType('jet-smart-filters/date-period', {
	title: __('Date Period'),
	icon: datePeriodIcon,
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
	className: 'jet-smart-filters-date-period',
	edit: class extends wp.element.Component {
		componentDidMount() {
			this._holder = $(window.ReactDOM.findDOMNode(this));
		}

		layoutUpdated() {
			this.initDatePeriod();
		}

		initDatePeriod() {
			const $filterContainer = this._holder.find('.' + window.JetSmartFilters.filtersList.DatePeriod);

			if ($filterContainer.length)
				new DatePeriod($filterContainer);
		}

		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='date-period'
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
						block="jet-smart-filters/date-period"
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