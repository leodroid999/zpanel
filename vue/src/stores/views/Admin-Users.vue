<script>
import { Tooltip } from 'bootstrap';
import { useAppOptionStore } from '@/stores/app-option';
import { useUserStore } from '@/stores/userStore';
import { useRouter, RouterLink } from 'vue-router';

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
const userStore = useUserStore();



export default {
	data() {
		return {
			ajax: {
				url: 'http://localhost/portal/usersinfo.php',
				type: 'POST',
				xhrFields: {
					withCredentials: true // Required for session cookies
				}
			},
			columns: [
				{ data: 'userId'},
				{ data: 'username'},
				{ data: 'balance'},
				{ data: 'user_type'},
				{ data: 'telegram'},
				{ data: 'chatID'}

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
				// columnDefs: [
				// 	{ targets: 'no-sort', orderable: false }
				// ]
			},
		}
	},
	methods: {
		loadUserInfo: async function () {
			let response = await userStore.loadUsersInfo();
			console.log(response);
			if ('users' in response) {
				for (let i = 0; i < response.users.length; ++i) {
					this.users.push(response.users[i])
				}
				console.log(this.users);
			}
		},
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

		<DataTable class="table table-bordered table-xs w-100 fw-bold text-nowrap mb-3"
			:ajax="ajax" :columns="columns">
			<thead>
				<tr>
					<th>User ID</th>
					<th>User name</th>
					<th>balance</th>
					<th>User Type</th>
					<th>Telegram</th>
					<th>Chat ID</th>
				</tr>
			</thead>

		</DataTable>
	</div>

	<!-- BEGIN #modalDetail -->
	
</template>