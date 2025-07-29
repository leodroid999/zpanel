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
			invite_code: '',
			max_uses: 5,
			expire_days: 1,
			ajax: {
				url: '/portal/invites.php',
				type: 'POST',
				xhrFields: {
					withCredentials: true // Required for session cookies
				}
			},
			columns: [
				{ data: 'userId' },
				{ data: 'username' },
				{ data: 'code' },
				{
					data: 'created',
					render: function (data, type, row) {
						const date = new Date(parseInt(data) * 1000);
						// Format the date as a readable string
						return date.toLocaleString();
					}
				},
				{
					data: 'expired',
					render: function (data, type, row) {
						const date = new Date(parseInt(data) * 1000);
						// Format the date as a readable string
						return date.toLocaleString();
					}
				},
				{ data: 'users' },
				{
					data: 'used',
					render: function (data, type, row) {
						if (data == null || data == '')
							return 0;
						else
							return data;
					}
				}
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
		createInvite: async function () {
			if (!this.invite_code || !this.max_uses || !this.expire_days) {
				return;
			}

			let data = new FormData();
			data.append('invite_code', this.invite_code);
			data.append('max_uses', this.max_uses);
			data.append('expire_days', this.expire_days);
			
			let options = {
				method: "POST",
				body: data,
				credentials: 'include'
			}
			try {
				// let response = await fetch(SERVER + '/portal/register.php', options);
				let response = await fetch('/portal/invite_create.php', options);
				if (response.ok) {
					let responseData = await response.json()
					if (responseData.data) {
						window.location.reload();
					}
					console.log(responseData);
				}
				else {
					console.log("There was a error processing your request , try again later");
					
				}
			}
			catch (err) {
				console.error(err);
				return {
					error: "SERVER_ERROR",
					message: "There was a error processing your request , try again later"
				}
			}
		},


		generateCode() {
			this.invite_code = this.generateRandomString(6);
		},

		generateRandomString(length) {
			const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			let randomString = '';

			for (let i = 0; i < length; i++) {
				const randomIndex = Math.floor(Math.random() * characters.length);
				randomString += characters[randomIndex];
			}

			return randomString;
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
		<a href="#modalDetail" data-bs-toggle="modal" class="btn btn-outline-default w-200px">Create Invite Code</a>
		<br><br>

		<DataTable class="table table-bordered table-xs w-100 fw-bold text-nowrap mb-3" :ajax="ajax" :columns="columns">
			<thead>
				<tr>
					<th>User ID</th>
					<th>User name</th>
					<th>Invite Code</th>
					<th>Created at</th>
					<th>Expired at</th>
					<th>Users</th>
					<th>Used</th>
				</tr>
			</thead>

		</DataTable>
	</div>

	<!-- BEGIN #modalDetail -->
	<div class="modal fade" id="modalDetail">
		<div class="modal-dialog" style="max-width: 600px">
			<div class="modal-content">
				<div class="modal-header py-2">
					<h5 class="modal-title fs-16px">Create Invite Code</h5>
					<button class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<div class="row gx-4 align-items-center">

						<div class="col-sm-12 py-1 fs-14px">
							<div class="row mb-10px">
								<div class="col-4 fw-bold">Invite Code:</div>
								<div class="col-4 text-white">{{ invite_code }}</div>
								<div class="col-4"><a class="btn btn-outline-default btn-sm w-100px"
										@click="generateCode()">Generate</a></div>
							</div>
							<div class="row mb-10px">
								<div class="col-4 fw-bold">Expire After (days):</div>
								<div class="col-8 text-white"><input type="text"
										class="form-control form-control-sm w-100px" :value="expire_days" /></div>
							</div>
							<div class="row mb-10px">
								<div class="col-4 fw-bold">Max Uses:</div>
								<div class="col-8 text-white">
									<input type="text" class="form-control form-control-sm w-100px" :value="max_uses" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#" data-bs-dismiss="modal" class="btn btn-outline-default w-100px">Cancel</a>
					<button type="submit" @click="createInvite()" class="btn btn-outline-theme w-100px">Create</button>
				</div>
			</div>
		</div>
	</div>
	<!-- END #modalDetail -->
</template>