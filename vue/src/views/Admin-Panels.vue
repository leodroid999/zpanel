<script>
import { Tooltip } from 'bootstrap';
import { useAppOptionStore } from '@/stores/app-option';
import { useUserStore } from '@/stores/userStore';

import DataTablesCore from 'datatables.net';
import DataTable from 'datatables.net-vue3';
import DataTablesLib from 'datatables.net-bs5';
import DataTablesFixedColumnsLib from 'datatables.net-fixedcolumns-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css';


DataTable.use(DataTablesCore);
DataTable.use(DataTablesLib);
DataTable.use(DataTablesFixedColumnsLib);

const appOption = useAppOptionStore();


export default {
	data() {
		return {
			// ajaxUrl: 'http://localhost/portal/adminPanels.php',
			ajaxUrl : '/portal/adminPanels.php',
			columns: [
				{
					data: 'search',
				},
				{ data: 'index' },
				{ data: 'panelId' },
				{ data: 'nodeId' },
				{ data: 'userId' },
				{ data: 'username' },
				{ data: 'expires' },
				{ data: 'panelType' },
				{ data: 'stockState' },
			],

			tableOption: {
				dom: "<'row'<'col-7 col-md-6 d-flex justify-content-start'f><'col-5 col-md-6 text-end'B>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5 fs-12px'i><'col-sm-12 col-md-7 fs-12px'p>>",
				scrollY: window.innerHeight - document.getElementById('header').clientHeight - 165,
				scrollX: true,
				paging: false,
				fixedColumns: {
					left: 1
				},
				order: [[1, 'asc']],
				columnDefs: [
					{ targets: 'no-sort', orderable: false }
				]
			},
		}
	},
	methods: {
		renderSearchColumn(){
			return '';
		}
	},
	async mounted() {
		appOption.appContentFullHeight = true;
		appOption.appContentClass = 'py-3';
	},
	components: {
		DataTable
	},
	beforeUnmount() {
		appOption.appContentFullHeight = false;
		appOption.appContentClass = '';
	},
}
</script>
<template>
	<div class="data-management d-none2" data-id="table">

		<DataTable class="table table-bordered table-xs w-100 fw-bold text-nowrap mb-3" :ajax="ajaxUrl"
			:columns="columns" :options="tableOption">
			<thead>
				<tr>
                    <th class="no-sort"></th>
					<th>Index</th>
					<th>Panel ID</th>
					<th>Node ID</th>
					<th>User ID</th>
					<th>User Name</th>
					<th>Expires</th>
					<th>Panel Type</th>
					<th>Stock State</th>
				</tr>
			</thead>

		</DataTable>
	</div>
</template>