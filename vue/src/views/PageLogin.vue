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
			if(name=="login"){handler=this.onLogin}
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
		submitForm:function(){
			this.loginError=null;
			if(!this.usernameInput || this.usernameInput=="" || !this.passwordInput || this.passwordInput == ""){
				this.loginError = "Missing username or password"
				return
			}
			userStore.login(this.usernameInput,this.passwordInput, this.rememberMe)
		},
		onLogin: function(result){
			console.log("onLogin");
			this.loginError=result.message;
			if(!result.error){
				setTimeout(async ()=>{
					let redirect=localStorage.getItem("redirectTo");
					if(redirect){
						this.$router.push(redirect)
						localStorage.removeItem("redirectTo")
						return;
					}

					console.log("onLogin111");
					var userInfo = await userStore.getUserInfo();
					if(userInfo.user.Enable_LogsAsHome)
						this.$router.push("/logs");
					else	
						this.$router.push("/")
				}, 100)
			}
		},
		onError: function(error){
			this.loginError="Error.";
		}
	},
	data: function(){
		return {
			usernameInput: "",
			passwordInput: "",
			rememberMe : false,
			loginError: null
		}
	},
}
</script>
<template>
	<!-- BEGIN login -->
	<div class="login">
		<!-- BEGIN login-content -->
		<div class="login-content">
			<form v-on:submit.prevent="submitForm()" method="POST" name="login_form">
				<h1 class="text-center">Sign In</h1>
				<div class="text-white text-opacity-50 text-center mb-4">
					To use Z services, please login
				</div>
				<div class="mb-3">
					<label class="form-label">Username <span class="text-danger">*</span></label>
					<input type="text" v-model="usernameInput" class="form-control form-control-lg bg-white bg-opacity-5" placeholder="" />
				</div>
				<div class="mb-3">
					<div class="d-flex">
						<label class="form-label">Password <span class="text-danger">*</span></label>
						<a href="#" class="ms-auto text-white text-decoration-none text-opacity-50">Forgot password?</a>
					</div>
					<input type="password" v-model="passwordInput"  class="form-control form-control-lg bg-white bg-opacity-5" placeholder="" />
				</div>
				<div class="mb-3">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" v-model="rememberMe" id="customCheck1" />
						<label class="form-check-label" for="customCheck1">Remember me</label>
					</div>
				</div>
				<button type="submit" class="btn btn-outline-theme btn-lg d-block w-100 fw-500 mb-3">Sign In</button>
				<div class="text-center text-white text-opacity-50">
					Don't have an account yet? <RouterLink to="register">Sign up</RouterLink>. <br><br>
					<div class="alert alert-warning" v-if="loginError">{{loginError}}</div>
				</div>
			</form>
		</div>
		<!-- END login-content -->
	</div>
	<!-- END login -->
</template>