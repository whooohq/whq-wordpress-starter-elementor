<template>
	<TableWrapper>
		<THead>
			<slot name="table-head"
				  :columns="tableColumns">
				<TableHeadCell v-if="hasCheckbox"
							   class="jet_table-thead-checkbox">
					<Checkbox v-model="allChecked" />
				</TableHeadCell>
				<TableHeadCell v-for="(value, key, columnIndex) in tableColumns"
							   :key="`datatable-thead-th-${key}`"
							   :name="key">
					<slot name="table-head-cell"
						  :column="key"
						  :value="value"
						  :columnIndex="columnIndex">
						{{value}}
					</slot>
				</TableHeadCell>
			</slot>
		</THead>
		<TBody>
			<slot name="table-body-before-rows" />
			<slot name="table-body"
				  :rows="tableRows">
				<TableRow v-for="(row, rowIndex) in tableRows"
						  :key="`datatable-row-${rowIndex}`"
						  :checked="row.checked">
					<TableBodyCell v-if="hasCheckbox"
								   class="jet_table-tbody-checkbox">
						<Checkbox v-model="row.checked" />
					</TableBodyCell>
					<TableBodyCell v-for="(value, key) in tableColumns"
								   :key="`datatable-tbody-td-${key}`"
								   :name="key">
						<slot name="table-body-cell"
							  :column="key"
							  :value="row[key]"
							  :rowIndex="rowIndex">
							{{row[key]}}
						</slot>
					</TableBodyCell>
				</TableRow>
			</slot>
			<slot name="table-body-after-rows" />
		</TBody>
	</TableWrapper>
</template>

<script>
import { defineComponent, computed, provide } from "vue";
import { TableWrapper, THead, TableHeadCell, TBody, TableRow, TableBodyCell, Checkbox } from "./components";

export default defineComponent({
	name: 'DataTable',

	components: {
		TableWrapper,
		THead,
		TableHeadCell,
		TBody,
		TableRow,
		TableBodyCell,
		Checkbox
	},

	props: {
		rows: { type: Array, required: true },
		columns: { type: Object, default: null },
		hasCheckbox: { type: Boolean, default: false },
	},

	setup(props, context) {

		// Data
		const tableColumns = computed(() => {
			if (props.columns) {
				return props.columns;
			}

			if (props.rows.length === 0) {
				return {};
			}

			return Object.keys(props.rows[0])
				.reduce(
					(cols, key) => (key !== 'checked') ? { ...cols, [key]: key } : cols,
					{}
				);
		});

		const tableRows = computed(() => props.rows);

		// All Check
		const allChecked = computed({
			get: () => props.rows.every(row => row.checked),
			set: (value) => props.rows.forEach(function (row) { row.checked = value; })
		});

		// Provider
		provide('DataTableProvider', {
			tableColumns,
			tableRows,
			allChecked
		});

		return {
			tableColumns,
			tableRows,
			allChecked
		};
	}
});
</script>
