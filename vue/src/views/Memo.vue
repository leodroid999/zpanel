<script>
import { useUserStore } from '@/stores/userStore';
const userStore = useUserStore();

export default {
	data: function () {
		return {
			memo: "",
			saveMemoError : ""
		}
	},
	methods: {
		loadUserInfo: function () {
			if (userStore.authenticated) {
				userStore.getUserInfo();
			}
		},
		updateMemo: async function (value) {
			console.log('Textarea value changed:', value);
			let result = await userStore.saveMemo(value)
			if (!result || (result.status != "ok" && !result.message)) {
				this.saveMemoError = "There was an error, try again"
			}
			if (result && result.message) {
				this.saveMemoError = result.message
			}
		}
	},
	mounted() {
		this.loadUserInfo();
		this.memo = userStore.memo;
	},
}
</script>
<template>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">Z-panel</a></li>
		<li class="breadcrumb-item active">Memo</li>
	</ul>
	<h1 class="page-header">
		<small></small>
	</h1>
	<div class="mb-3">
		<label for="memo" class="form-label">My memo</label>
		<textarea class="form-control" id="memo" v-model="memo" rows="3" @input="updateMemo($event.target.value)"></textarea>
	</div>

	<!-- <div class="text-center text-error">
		<div v-if="saveMemoError" class="alert alert-warning" >
			<span v-if="saveMemoError">{{ saveMemoError }}</span>
		</div>
	</div> -->
	<p>

	</p>
</template>