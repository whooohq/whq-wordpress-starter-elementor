import { sortingIcon } from 'blocks/editor/icons';
import attributes from 'blocks/editor/attributes';
import options from 'blocks/editor/options';
import General from 'blocks/editor/panels/general';
import Repeater from 'blocks/editor/controls/repeater';
import TemplateRender from 'blocks/editor/controls/templateRender';
import {
	clone
} from 'includes/utility';

const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
	InspectorControls
} = wp.editor;

const {
	PanelBody,
	SelectControl,
	TextControl
} = wp.components;

registerBlockType('jet-smart-filters/sorting', {
	title: __('Sorting'),
	icon: sortingIcon,
	category: 'jet-smart-filters',
	supports: {
		html: false
	},
	attributes: {
		// General
		content_provider: attributes.content_provider,
		apply_type: attributes.apply_type,
		apply_on: attributes.apply_on,
		apply_button: attributes.apply_button,
		apply_button_text: attributes.apply_button_text,
		sorting_label: attributes.sorting_label,
		sorting_placeholder: attributes.sorting_placeholder,
		sorting_list: attributes.sorting_list,
	},
	className: 'jet-smart-filters-sorting',
	edit: class extends wp.element.Component {
		updateItem(item, key, value) {
			const sortingList = clone(this.props.attributes.sorting_list),
				currentItem = sortingList[this.getItemIndex(item)];

			if (!currentItem)
				return;

			currentItem[key] = value;

			this.props.setAttributes({ sorting_list: sortingList });
		}

		getItemIndex(item) {
			return this.props.attributes.sorting_list.findIndex(sortingListItem => {
				return sortingListItem == item;
			})
		}

		render() {
			const props = this.props;

			return [
				props.isSelected && (
					<InspectorControls
						key={'inspector'}
					>
						<General
							filterType='sorting'
							disabledControls={
								{
									apply_button_text: !props.attributes.apply_button ? true : false,
									apply_on: !['ajax', 'mixed'].includes(props.attributes.apply_type) ? true : false
								}
							}
							{...props}
						>
							<TextControl
								type="text"
								label={__('Label')}
								value={props.attributes.sorting_label}
								onChange={newValue => {
									props.setAttributes({ sorting_label: newValue });
								}}
							/>
							<TextControl
								type="text"
								label={__('Placeholder')}
								placeholder={ __( 'Sort...' ) }
								value={props.attributes.sorting_placeholder}
								onChange={newValue => {
									props.setAttributes({ sorting_placeholder: newValue });
								}}
							/>
						</General>
						<PanelBody title={__('Sorting List')} initialOpen={false}>
							<Repeater
								data={props.attributes.sorting_list}
								default={{
									title: '',
									orderby: 'none',
									order: 'ASC'
								}}
								onChange={newData => {
									props.setAttributes({ sorting_list: newData });
								}}
							>
								{
									(item) =>
										<React.Fragment>
											<TextControl
												type="text"
												label={__('Title')}
												value={item.title}
												onChange={newValue => {
													this.updateItem(item, 'title', newValue)
												}}
											/>
											<SelectControl
												label={__('Order By')}
												value={item.orderby}
												options={options.sortingOrderby}
												onChange={newValue => {
													this.updateItem(item, 'orderby', newValue)
												}}
											/>
											{['meta_value', 'meta_value_num', 'clause_value'].includes(item.orderby) && (
												<TextControl
													type="text"
													label={__('Meta key')}
													value={item.meta_key}
													onChange={newValue => {
														this.updateItem(item, 'meta_key', newValue)
													}}
												/>
											)}
											{!['none', 'rand'].includes(item.orderby) && (
												<SelectControl
													label={__('Order')}
													value={item.order}
													options={options.sortingOrder}
													onChange={newValue => {
														this.updateItem(item, 'order', newValue)
													}}
												/>
											)}
										</React.Fragment>
								}
							</Repeater>
						</PanelBody>
					</InspectorControls>
				),
				<div class="jet-smart-filters-block-holder">
					<TemplateRender
						block="jet-smart-filters/sorting"
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