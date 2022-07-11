import { plusCircle, chevronUp, chevronDown, trash } from 'blocks/editor/icons';
import {
	clone,
	arrayMove
} from 'includes/utility';

const {
	Icon
} = wp.components;

export default class Repeater extends React.Component {
	moveDown(startIndex) {
		const data = clone(this.props.data),
			endIndex = startIndex + 1;

		if (!data.length || endIndex >= data.length)
			return;

		this.props.onChange(arrayMove(data, startIndex, endIndex));
	}

	moveUp(startIndex) {
		const data = clone(this.props.data),
			endIndex = startIndex - 1;

		if (!data.length || endIndex < 0)
			return;

		this.props.onChange(arrayMove(data, startIndex, endIndex));
	}

	remove(index) {
		const data = clone(this.props.data);

		if (data.length <= 1)
			return;

		data.splice(index, 1)

		this.props.onChange(data);
	}

	addNew() {
		const data = clone(this.props.data);

		data.push(this.props.default);

		this.props.onChange(data);
	}

	render() {
		const {
			data,
			indexPrefix = 'key',
			children,
		} = this.props;

		return (
			<div class='jsf-repeater'>
				{data.map((itemData, index) => {
					const item = React.cloneElement(children(itemData), { key: `${indexPrefix}-${index}` });

					return (
						<div class='jsf-repeater-item'>
							<div class='jsf-repeater-item-tools'>
								<div class='jsf-repeater-item-move-down'
									onClick={() => this.moveDown(index)}
								><Icon icon={chevronDown} /></div>
								<div class='jsf-repeater-item-move-up'
									onClick={() => this.moveUp(index)}
								><Icon icon={chevronUp} /></div>
								<div class='jsf-repeater-item-remove'
									onClick={() => this.remove(index)}
								><Icon icon={trash} /></div>
							</div>
							<div class='jsf-repeater-item-content'>
								{item}
							</div>
						</div>
					);
				})}
				<div class='jsf-repeater-add-new'
					onClick={() => this.addNew()}
				><Icon icon={plusCircle} /></div>
			</div>
		);
	}
}