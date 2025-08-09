<script>
import { useUserStore } from '@/stores/userStore';
import { useEditorStore } from '@/stores/editorStore';
const userStore = useUserStore();
const editorStore = useEditorStore();

export default {
	data() {
		return {
			blueprints: null,
			cfgFile: null,
			zipFile: null,
			engine: null,
			pageName: '',
		}
	},
	methods: {
		loadBlueprintsInfo: async function () {
			let response = await editorStore.loadBlueprints();
			console.log(response);
			if ('blueprints' in response) {
				this.blueprints = response.blueprints;
			}
		},
		url: function (bp, command) {
			if(command == "View"){
				return "/editor/edit/" + bp.blueprint;
			}
			console.log(bp.blueprint + " " + command);
		},
		uploadBlueprint: async function () {
			console.log(this.cfgFile);
			console.log(this.zipFile);
			let response = await editorStore.uploadBlueprintInfo(this.engine, this.pageName);
			// let response = await editorStore.uploadBlueprintInfo(this.cfgFile, this.zipFile, this.engine);
			this.blueprints = response.blueprints;
		},
		cfgFileSelected: function (e) {
			this.cfgFile = e.target.files[0];
		},
		zipFileSelected: function (e) {
			this.zipFile = e.target.files[0];
		},
		deleteBlueprint : async function(blueprint){
			let response = await editorStore.deleteBlueprint(blueprint);
			console.log(response);
			this.loadBlueprintsInfo();
		},
		onTokenSelected: async function(ev){

		},

		thumnailPath(blueprint){
			return "//z-panel.io/portal@/assets/icons/" + blueprint.thumbnail;
		}
	},
	computed: {
		canUpload() {
			return this.engine !== null && this.engine !== "" && this.pageName !== "";
			return this.cfgFile !== null && this.zipFile !== null && this.engine !== null && this.engine !== "";
		}
	},
	mounted() {
		this.loadBlueprintsInfo();
	}
};

</script>
<style>
.bg-gradient-blue-cyan {
	background: linear-gradient(to top, #0271ff, #009be3) !important;
}
</style>
<template>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">東-panel</a></li>
		<li class="breadcrumb-item active">Memo</li>
	</ul>
	<h1 class="page-header">
		<small></small>
	</h1>
	<div class="list-group mb-3">
		<div class="list-group-item d-flex align-items-center" v-for="blueprint in blueprints">
			
			<img :src="thumnailPath(blueprint)" class="w-40px h-40px d-flex align-items-center justify-content-center bg-gradient-blue-cyan text-white rounded-2 ms-n1" v-if="blueprint.thumbnail">
			<div class="w-40px h-40px d-flex align-items-center justify-content-center bg-gradient-blue-cyan text-white rounded-2 ms-n1" v-else>
				{{ blueprint.blueprint[0] }}
			</div>
			<div class="flex-fill px-3">
				<div class="fw-bold"><a :href="url(blueprint, 'View')" style="text-decoration: none;"> {{ blueprint.blueprint }}</a></div>
				<div class="small text-inverse text-opacity-50">{{ blueprint.assetDir }} {{ blueprint.engine }}</div>
			</div>
			<div class="dropdown">
				<a href="#" data-bs-toggle="dropdown" class="text-inverse text-opacity-50" aria-expanded="false"><i
						class="fa fa-ellipsis-h"></i></a>
				<div class="dropdown-menu dropdown-menu-end" style="">
					<a :href="url(blueprint, 'View')" class="dropdown-item">View</a>
					<button @click="deleteBlueprint(blueprint)" class="dropdown-item">Delete</button>
					<a :href="url(blueprint, 'Report')" class="dropdown-item">Report</a>
				</div>
			</div>
		</div>
		<div class="list-group-item d-flex align-items-center justify-content-center">
			<button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUploadBlueprint"
				style="width: 35px; height: 35px;">
				<i class="fas fa-upload"></i></button>
		</div>
	</div>

	<!-- BEGIN #modalUploadBlueprint -->
	<div class="modal fade" id="modalUploadBlueprint">
		<div class="modal-dialog" style="max-width: 400px">
			<div class="modal-content">
				<div class="modal-header py-2">
					<h5 class="modal-title fs-16px">Upload Blueprint</h5>
					<button class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<!-- <div class="form-group mb-3">
						<label class="form-label" for="exampleFormControlFile1">CFG File</label>
						<input type="file" @change="cfgFileSelected" class="form-control" id="exampleFormControlFile1" accept=".cfg">
					</div>
					<div class="form-group mb-3">
						<label class="form-label" for="exampleFormControlFile2">Zip File</label>
						<input type="file" @change="zipFileSelected" class="form-control" id="exampleFormControlFile2" accept=".zip">
					</div> -->
					<div class="form-group mb-3">
						<label class="form-label" for="exampleFormControlFile3">PageName</label>
						<input type="text" v-model="pageName" class="form-control">
					</div>
					<div class="form-group mb-3">
						<label class="form-label" for="exampleFormControlFile3">Engine</label>
						<input type="text" v-model="engine" class="form-control" id="exampleFormControlFile3">
					</div>
				</div>
				<div class="modal-footer">
					<a href="#" data-bs-dismiss="modal" class="btn btn-outline-default w-100px">Cancel</a>
					<button @click="uploadBlueprint" data-bs-dismiss="modal" class="btn btn-outline-theme" :disabled="!canUpload">Upload</button>
				</div>
			</div>
		</div>
	</div>
	<!-- END #modalUploadBlueprint -->
</template>