<script>
import { useAppOptionStore } from '@/stores/app-option';
import { useUserStore } from '@/stores/userStore';
import { useRouter, RouterLink } from 'vue-router';

const appOption = useAppOptionStore();
const userStore = useUserStore();

export default {
	mounted() {
		appOption.appSidebarHide = true;
		appOption.appHeaderHide = true;
		appOption.appContentClass = 'p-0';
		userStore.$onAction(({name, store, args, after, onError})=>{
			let handler=()=>{}
			if(name=="register"){handler=this.onRegister}
			after((result)=>handler(result))
			onError((err)=>this.onError(err))
		})
	},
	beforeUnmount() {
		appOption.appSidebarHide = false;
		appOption.appHeaderHide = false;
		appOption.appContentClass = '';
	},
	methods: {
		submitForm: function() {
			this.registerError=null;
			if(!this.usernameInput || this.usernameInput=="" || !this.passwordInput || this.passwordInput == ""){
				this.registerError = "Missing username or password"
				return
			}
			userStore.register(this.usernameInput,this.passwordInput,this.telegramInput,this.referalInput);
		},
		onRegister: function(result){
			this.registerError=result.message;
			if(!result.error){
				setTimeout(()=>{
					this.$router.push("/login")
				},3000)
			}
		},
		onError: function(error){
			this.registerError="Error.";
		}
	},
	data: function(){
		return {
			usernameInput: "",
			passwordInput: "",
			telegramInput: "",
			referalInput: "",
			registerError: null
		}
	},
}
</script>
<template>
	<!-- BEGIN register -->
	<div class="register">
		<!-- BEGIN register-content -->
		<div class="register-content">
			<form v-on:submit.prevent="submitForm()" method="POST" name="register_form">
				<h1 class="text-center">Sign Up</h1>
				<p class="text-white text-opacity-50 text-center">One Z-ID is all you need to access all Z services.</p>
				<div class="mb-3">
					<label class="form-label">Username <span class="text-danger">*</span></label>
					<input type="text" v-model="usernameInput" class="form-control form-control-lg bg-white bg-opacity-5" placeholder="" />
				</div>
				<div class="mb-3">
					<label class="form-label">Telegram <span class="text-danger"></span></label>
					<input type="text" v-model="telegramInput" class="form-control form-control-lg bg-white bg-opacity-5" placeholder="" />
				</div>
				
				<div class="mb-3">
					<label class="form-label">Password <span class="text-danger">*</span></label>
					<input type="password" v-model="passwordInput" class="form-control form-control-lg bg-white bg-opacity-5" />
				</div>

				<div class="mb-3">
					<label class="form-label">Referal discount code (optional) <span class="text-danger"></span></label>
					<input type="text" v-model="referalInput" class="form-control form-control-lg bg-white bg-opacity-5" placeholder="" />
				</div>
				
				
				<div class="mb-3">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="customCheck1" />
						<label class="form-check-label" for="customCheck1">I have read and agree to the <a href="#">Terms of Use</a>.</label>
					</div>
				</div>
				<div class="mb-3">
					<button type="submit" class="btn btn-outline-theme btn-lg d-block w-100">Sign Up</button>
				</div>
 				<div class="alert alert-warning" v-if="registerError">{{ registerError }}</div>
			</form>
		</div>
		<!-- END register-content -->
	</div>
	<!-- END register -->
</template>