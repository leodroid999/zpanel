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
		userStore.$onAction(({ name, store, args, after, onError }) => {
			let handler = () => { }
			if (name == "register") { handler = this.onRegister }
			after((result) => handler(result))
			onError((err) => this.onError(err))
		})
	},
	beforeUnmount() {
		appOption.appSidebarHide = false;
		appOption.appHeaderHide = false;
		appOption.appContentClass = '';
	},
	methods: {
		submitForm: function () {
			this.registerError = null;
			if (!this.usernameInput || this.passwordInput == "" || this.referalInput == "") {
				this.registerError = "Missing username, password or referal code"
				return
			}
			userStore.register(this.usernameInput, this.passwordInput, this.telegramInput, this.referalInput);
		},
		onRegister: function (result) {
			this.registerError = result.message;
			if (!result.error) {
				setTimeout(() => {
					this.$router.push("/login")
				}, 3000)
			}
		},
		onError: function (error) {
			this.registerError = "Error.";
		}
	},
	data: function () {
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
					<input type="text" v-model="usernameInput"
						class="form-control form-control-lg bg-white bg-opacity-5" placeholder="" />
				</div>
				<div class="mb-3">
					<label class="form-label">Telegram <span class="text-danger"></span></label>
					<input type="text" v-model="telegramInput"
						class="form-control form-control-lg bg-white bg-opacity-5" placeholder="" />
				</div>

				<div class="mb-3">
					<label class="form-label">Password <span class="text-danger">*</span></label>
					<input type="password" v-model="passwordInput"
						class="form-control form-control-lg bg-white bg-opacity-5" />
				</div>

				<div class="mb-3">
					<label class="form-label">Invite code<span class="text-danger">*</span></label>
					<input type="text" v-model="referalInput" class="form-control form-control-lg bg-white bg-opacity-5"
						placeholder="" />
				</div>


				<div class="mb-3">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="customCheck1" />
						<label class="form-check-label" for="customCheck1">I have read and agree to the <a
								href="#modalTerms" data-bs-toggle="modal">Terms
								of Service</a>.</label>
					</div>
				</div>
				<div class="mb-3">
					<button type="submit" class="btn btn-outline-theme btn-lg d-block w-100">Sign Up</button>
				</div>
				<div class="alert alert-warning" v-if="registerError">{{ registerError }}</div>
			</form>
		</div>
		<!-- END register-content -->


		<div class="modal fade" id="modalTerms">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Terms of Service (ToS) Last Updated: 11/29/2024 </h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="alert bg-inverse bg-opacity-10 border-0">
							Welcome to our services! By accessing or using our servers and traffic management services,
							you agree to comply with and be bound by the following terms and conditions. Please read
							this document carefully before using our services. If you do not agree with these terms, you
							are not authorized to use our services.<br><br>

							<b>1. Acceptance of Terms</b><br><br>

							By accessing or using our servers, traffic management tools, or any other related services
							(collectively referred to as "Services"), you agree to comply with these Terms of Service
							("ToS"). If you do not agree to these terms, you must immediately cease use of our Services.<br><br>

							<b>2. Prohibited Activities</b><br><br>

							You agree not to engage in, assist, or allow any illegal or unethical activities on our
							servers or network. These include, but are not limited to:<br><br>

							Fraudulent activity: Any form of deception or theft, including phishing, impersonation, or
							manipulation.<br><br>

							Malicious actions: Engaging in activities that could harm, disrupt, or damage our servers,
							network, or other users.<br><br>

							Illegal content: Uploading, sharing, or distributing content that violates laws or
							intellectual property rights.<br><br>

							Hacking or unauthorized access: Attempting to gain unauthorized access to systems or
							services.<br><br><br>


							We have the right to monitor, investigate, and take action if illegal activity or violations
							of these terms occur. We reserve the right to suspend or terminate your account or access to
							our Services without prior notice.<br><br>

							<b>3. User Responsibility</b><br><br>

							You are solely responsible for all actions taken using your account, including:<br><br>

							The content you host, share, or transmit.<br><br>

							Ensuring compliance with all applicable laws, regulations, and guidelines.<br><br>

							Any third-party services or software used in conjunction with our servers.<br><br><br>


							<b>4. Limitation of Liability</b><br><br>

							We are not responsible for any damage, loss, or harm caused to you or any third party as a
							result of using our services, including but not limited to:<br><br>

							Data loss, breaches, or theft.<br><br>

							Service downtime or interruptions.<br><br>

							Damages arising from illegal activities conducted by users.<br><br><br>


							The user is solely responsible for ensuring the security and integrity of their data,
							systems, and any transactions made via our services. You agree to indemnify and hold us
							harmless from any and all claims, losses, or liabilities related to your use of our
							services.<br><br>

							<b>5. No Warranty</b><br><br>

							Our services are provided "as is" and without any warranties. We do not guarantee that our
							services will be error-free, secure, or available at all times. We are not responsible for
							any disruptions, downtime, or loss of access to your data or services.<br><br>

							<b>6. Account Suspension or Termination</b><br><br>

							We may suspend or terminate your access to our services if you violate these Terms of
							Service, engage in illegal activity, or otherwise compromise the integrity of our systems.
							If your account is suspended or terminated, you may lose access to all data and content
							associated with it.<br><br>

							<b>7. Changes to Terms</b><br><br>

							We reserve the right to update or modify these Terms of Service at any time. Any changes
							will be effective immediately upon posting on our website or notifying you. It is your
							responsibility to review these terms periodically.<br><br>

							<b>8. Governing Law</b><br><br>

							These Terms of Service shall be governed by and construed in accordance with the laws of the
							world, without regard to its conflict of law principles. Any legal disputes arising from
							these terms shall be resolved in the competent court<br><br>

							<b>9. Contact Information</b><br><br>

							If you have any questions about these Terms of Service, please contact us at:
							<a href="mailto:Abuse@namelyss.ru">Abuse@namelyss.ru</a><br><br><br>


							By using our services, you acknowledge and agree to these terms.
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END register -->
</template>