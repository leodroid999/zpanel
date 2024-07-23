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
			// ajaxUrl : 'http://localhost/portal/usersinfo.php',
			ajaxUrl : '/portal/usersinfo.php',
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
			:ajax="ajaxUrl" :columns="columns">
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
	<div class="modal fade" id="modalDetail">
		<div class="modal-dialog" style="max-width: 600px">
			<div class="modal-content">
				<div class="modal-header py-2">
					<h5 class="modal-title fs-16px">Product Information</h5>
					<button class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<div class="row gx-4 align-items-center">
						<div class="col-sm-5 mb-3 mb-sm-0">
							<div class="card">
								<div class="card-body p-2">
									<img src="/assets/img/product/product-16.jpg" class="mw-100 d-block" />
								</div>
								<div class="card-arrow">
									<div class="card-arrow-top-left"></div>
									<div class="card-arrow-top-right"></div>
									<div class="card-arrow-bottom-left"></div>
									<div class="card-arrow-bottom-right"></div>
								</div>
							</div>
						</div>
						<div class="col-sm-7 py-1 fs-13px">
							<div class="row mb-10px">
								<div class="col-4 fw-bold">Model:</div>
								<div class="col-8 text-white">iPhone 14 Pro Max</div>
							</div>
							<div class="row mb-10px">
								<div class="col-4 fw-bold">Colour:</div>
								<div class="col-8 text-white">Space Black</div>
							</div>
							<div class="row mb-10px">
								<div class="col-4 fw-bold">Storage:</div>
								<div class="col-8 text-white">256gb</div>
							</div>
							<div class="row mb-10px">
								<div class="col-4 fw-bold">Size:</div>
								<div class="col-8 text-white">147 x 72 x 7.8mm</div>
							</div>
							<div class="row mb-10px">
								<div class="col-4 fw-bold">Category:</div>
								<div class="col-8 text-white"><span
										class="badge bg-theme text-black text-opacity-75 py-1 fs-10px my-n1 fw-bold">PHONE</span>
								</div>
							</div>
							<div class="row mb-10px">
								<div class="col-4 fw-bold">Price:</div>
								<div class="col-8 text-white">$1,999</div>
							</div>
							<div class="row mb-10px">
								<div class="col-4 fw-bold">Cost:</div>
								<div class="col-8 text-white">$1,899</div>
							</div>
							<div class="row mb-10px">
								<div class="col-4 fw-bold">Profit:</div>
								<div class="col-8 text-success">$200</div>
							</div>
							<div class="row">
								<div class="col-4 fw-bold">Stock:</div>
								<div class="col-8 text-white"><input type="text"
										class="form-control form-control-sm w-100px" value="20" /></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#" data-bs-dismiss="modal" class="btn btn-outline-default w-100px">Cancel</a>
					<button type="submit" class="btn btn-outline-theme">Save & Publish</button>
				</div>
			</div>
		</div>
	</div>
	<!-- END #modalDetail -->
</template>