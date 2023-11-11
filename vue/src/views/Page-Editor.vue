<script>
import { useUserStore } from '@/stores/userStore';
const userStore = useUserStore();


</script>
<style>
.bg-gradient-blue-cyan {
    background: linear-gradient(to top,#0271ff,#009be3)!important;
}
</style>
<template>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">Z-panel</a></li>
		<li class="breadcrumb-item active">Memo</li>
	</ul>
	<h1 class="page-header">
		<small></small>
	</h1>
	<div class="list-group mb-3">
		<div class="list-group-item d-flex align-items-center">
			<div class="w-40px h-40px d-flex align-items-center justify-content-center bg-gradient-blue-cyan text-white rounded-2 ms-n1">
				C
			</div>
			<div class="flex-fill px-3">
				<div class="fw-bold">Coinbase.cfg</div>
				<div class="small text-inverse text-opacity-50">v1.2</div>
			</div>
			<div class="dropdown">
				<a href="#" data-bs-toggle="dropdown" class="text-inverse text-opacity-50" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
				<div class="dropdown-menu dropdown-menu-end" style="">
					<a href="#" class="dropdown-item">View</a>
					<a href="#" class="dropdown-item">Analytics</a>
					<a href="#" class="dropdown-item">Report</a>
				</div>
			</div>
		</div>
		<div class="list-group-item d-flex align-items-center">
			<div class="w-40px h-40px d-flex align-items-center justify-content-center bg-gradient-orange text-white rounded ms-n1">
				<i class="fa fa-book fa-lg"></i>
			</div>
			<div class="flex-fill px-3">
				<div class="fw-bold">iBooks App</div>
				<div class="small text-inverse text-opacity-50">32 new download</div>
			</div>
			<div class="dropdown">
				<a href="#" data-bs-toggle="dropdown" class="text-inverse text-opacity-50"><i class="fa fa-ellipsis-h"></i></a>
				<div class="dropdown-menu dropdown-menu-end">
					<a href="#" class="dropdown-item">View</a>
					<a href="#" class="dropdown-item">Analytics</a>
					<a href="#" class="dropdown-item">Report</a>
				</div>
			</div>
		</div>
	</div>

	<!-- <div class="text-center text-error">
		<div v-if="saveMemoError" class="alert alert-warning" >
			<span v-if="saveMemoError">{{ saveMemoError }}</span>
		</div>
	</div> -->
	<p>

	</p>
</template>