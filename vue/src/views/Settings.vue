<script>
import navscrollto from '@/components/app/NavScrollTo.vue';
import { ScrollSpy } from 'bootstrap';
import { useUserStore } from '@/stores/userStore';

const userStore = useUserStore();

export default {
	data:function(){
		return{
		    currentPassword:"",
			newUsername:"",
			newPassword:"",
			newPasswordConfirm:"",
			newTelegram:"",
			newReferal:"",
			newChatID:"",
			newNotification:"",
			savePasswordError:null,
			saveUsernameError:null,
			saveChatIDError:null,
			saveNotificationError:null
		}
	},
	components: {
		navScrollTo: navscrollto
	},
	methods: {
		resetPwError: function(){
			this.savePasswordError=null
		},
		loadUserInfo: function(){
			if(userStore.authenticated){
				userStore.getUserInfo();
			}
		},
		saveUsername: async function(){
		    let updatedInfo={
		        username:this.newUsername
		    }
			let result=await userStore.saveUserInfo(this.currentPassword,updatedInfo)
			if(!result ||(result.status!="ok" && !result.message)){
				this.saveUsernameError="There was an error, try again"
			}
			if(result && result.message){
				this.saveUsernameError=result.message
			}
		},
		saveChatID: async function(){
		    let updatedInfo={
		        chatID:this.newChatID
		    }
			let result=await userStore.saveUserInfo(this.currentPassword,updatedInfo)
			if(!result ||(result.status!="ok" && !result.message)){
				this.saveChatIDError="There was an error, try again"
			}
			if(result && result.message){
				this.saveChatIDError=result.message
			}
		},
		savePassword: async function(){
			if(this.newPassword != this.newPasswordConfirm){
				return;
			}
			let updatedInfo={
		        newPassword:this.newPassword
		    }
			let result=await userStore.saveUserInfo(this.currentPassword,updatedInfo)
			if(!result ||(result.status!="ok" && !result.message)){
				this.savePasswordError="There was an error, try again"
			}
			if(result && result.message){
				this.savePasswordError=result.message
			}
		},
		saveNotification: async function(){
			let updatedInfo={
		        webNotifs:this.newNotification ? 'true' : 'false'
		    }
			let result=await userStore.saveUserInfo(this.currentPassword,updatedInfo)
			if(!result ||(result.status!="ok" && !result.message)){
				this.saveNotificationError="There was an error, try again"
			}
			if(result && result.message){
				this.saveNotificationError=result.message
			}
		}
	},
	computed:{
		user: function(){
			return userStore.user;
		},
		passwordMatch(){
			return this.newPassword == this.newPasswordConfirm
		},
		canSavePasswordForm(){
			return this.currentPassword!="" && this.newPassword!="" && 
				this.newPassword == this.newPasswordConfirm
		}
	},
	mounted() {
		this.loadUserInfo();
		new ScrollSpy(document.body, {
			target: '#sidebar-bootstrap',
			offset: 200
		})
	}
}
</script>

<template>
	<!-- BEGIN row -->
	<div class="row justify-content-center">
		<!-- BEGIN col-10 -->
		<div class="col-xl-10">
			<!-- BEGIN row -->
			<div class="row">
				<!-- BEGIN col-9 -->
				<div class="col-xl-9">
					<!-- BEGIN #general -->
					<div id="general" class="mb-5">
						<h4><i class="far fa-user fa-fw text-theme"></i> General</h4>
						<p>View and update your general account information and settings.</p>
						<card>
							<div v-if="user" class="list-group list-group-flush">
								
								<div class="list-group-item d-flex align-items-center">
									<div class="flex-1 text-break">
										<div>Username</div>
										<div class="text-inverse text-opacity-50">
											<span if="user">{{user.username}}</span>
										</div>
									</div>
									<div>
										<a href="#modalEditUsername" data-bs-toggle="modal" class="btn btn-outline-default w-100px">Edit</a>
									</div>
								</div>
								<div class="list-group-item d-flex align-items-center">
									<div class="flex-1 text-break">
										<div>Telegram</div>
										<div class="text-inverse text-opacity-50 d-flex align-items-center">
											<span v-if="user">{{user.telegram}}</span>
										</div>
									</div>
									<div>
										<a href="#modalEditTelegram" data-bs-toggle="modal" class="btn btn-outline-default w-100px">Edit</a>
									</div>
								</div>
								<div class="list-group-item d-flex align-items-center">
									<div class="flex-1 text-break">
										<div>Password</div>
									</div>
									<div>
										<a href="#modalEditPassword" data-bs-toggle="modal" class="btn btn-outline-default w-100px">Edit</a>
									</div>
								</div>
							</div>
							<div v-else class="list-group list-group-flush">
								
								<div class="list-group-item d-flex align-items-center">
									<div class="flex-1 text-break">
										<div>Loading ...</div>
									</div>
								</div>
							</div>
						</card>
					</div>
					<!-- END #general -->
					
					<!-- BEGIN #notifications -->
					<div id="notifications" class="mb-5">
						<h4><i class="far fa-bell fa-fw text-theme"></i> Notifications</h4>
						<p>Enable or disable what notifications you want to receive.</p>
						<card>
							<div v-if="user" class="list-group list-group-flush" >
				
								<div class="list-group-item d-flex align-items-center">
									<div class="flex-1 text-break">
										<div>Telegram chatID</div>
										<div class="text-inverse text-opacity-50 d-flex align-items-center">
											<i class="fa fa-circle fs-8px fa-fw text-success me-1"></i> {{user.chatID}}
										</div>
									</div>
									<div>
										<a href="#modalEditChatID" data-bs-toggle="modal" class="btn btn-outline-default w-100px">Edit</a>
									</div>
								</div>
								<div class="list-group-item d-flex align-items-center">
									<div class="flex-1 text-break">
										<div>Web Panel Notifications</div>
										<div class="text-inverse text-opacity-50 d-flex align-items-center">
											<div v-if="user">
												<div v-if="user.webNotifs">
													<i class="fa fa-circle fs-8px fa-fw text-success me-1"></i> 
													<span>Enabled</span>
												</div>
												<div v-else>
													<i class="fa fa-circle fs-8px fa-fw text-error me-1"></i> 
													<span>Disabled</span>
												</div>
											</div>
										</div>
									</div>
									<div>
										<a href="#modalEditNotifications" data-bs-toggle="modal" class="btn btn-outline-default w-100px">Edit</a>
									</div>
								</div>
							</div>
							<div v-else class="list-group list-group-flush">
								<div class="list-group-item d-flex align-items-center">
									<div class="flex-1 text-break">
										<div>Loading ...</div>
									</div>
								</div>
							</div>
						</card>
					</div>
					<!-- END #notifications -->		
				</div>
				<!-- END col-9-->
				<!-- BEGIN col-3 -->
				<div class="col-xl-3">
					<!-- BEGIN #sidebar-bootstrap -->
					<nav id="sidebar-bootstrap" class="navbar navbar-sticky d-none d-xl-block">
						<nav class="nav">
							<nav-scroll-to target="#general" data-toggle="scroll-to">General</nav-scroll-to>
							<nav-scroll-to target="#notifications" data-toggle="scroll-to">Notifications</nav-scroll-to>
						</nav>
					</nav>
					<!-- END #sidebar-bootstrap -->
				</div>
				<!-- END col-3 -->
			</div>
			<!-- END row -->
		</div>
		<!-- END col-10 -->
	</div>
	<!-- END row -->
	
	<div v-if="user">
		<div class="modal fade" id="modalEditUsername">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Set Username</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Username</label>
							<div class="row row-space-10">
								<div class="col-8">
									<input v-model="newUsername" class="form-control" required placeholder="New Username"/>
								</div>
							</div>
						</div>
						<div class="alert bg-inverse bg-opacity-10 border-0">
							Only letters and numbers, no special characters or spaces allowed.
							<a href="#" class="alert-link">Learn more.</a>
						</div>
						<div class="mb-3">
							<label class="form-label">Enter password</label>
							<div class="row row-space-10">
								<div class="col-8">
									<input v-model="currentPassword" type="password" class="form-control" required placeholder="password"/>
								</div>
							</div>
						</div>		
						<div class="text-center text-error">
							<div v-if="saveUsernameError" class="alert alert-warning" >
								<span v-if="saveUsernameError">{{ saveUsernameError }}</span>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-outline-theme" @click="saveUsername">Save changes</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modalEditTelegram">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Set Telegram</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Telegram</label>
							<div class="row row-space-10">
								<div class="col-8">
									<input class="form-control" placeholder="Telegram" :value="user.telegram" />
								</div>
							</div>
						</div>
						<div class="alert bg-inverse bg-opacity-10 border-0">
							Dont include the @ symbol.
						</div>
						<div class="mb-3">
							<label class="form-label">Enter password</label>
							<div class="row row-space-10">
								<div class="col-8">
									<input class="form-control" placeholder="password"/>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-outline-theme">Save changes</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modalEditChatID">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Set ChatID</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">ChatID </label>
							<div class="row row-space-10">
								<div class="col-8">
									<input class="form-control" placeholder="ChatID" v-model="newChatID" />
								</div>
							</div>
						</div>
						<div class="alert bg-inverse bg-opacity-10 border-0">
							Enter the chatID of your chat with our notification bot.
						</div>
						<div class="mb-3">
							<label class="form-label">Enter password</label>
							<div class="row row-space-10">
								<div class="col-8">
									<input class="form-control" type="password" @input="resetPwError" v-model="currentPassword"  placeholder="password"/>
								</div>
							</div>
						</div>
						<div class="text-center text-error">
							<div v-if="saveChatIDError" class="alert alert-warning" >
								<span v-if="saveChatIDError">{{ saveChatIDError }}</span>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-outline-theme" @click="saveChatID">Save changes</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modalEditPassword">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Set Password </h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Current Password</label>
							<div class="row row-space-10">
								<div class="col-8">
									<input class="form-control"  @input="resetPwError" v-model="currentPassword" type="password" placeholder="password"/>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<label class="form-label">New Password</label>
							<div class="row row-space-10">
								<div class="col-8">
									<input class="form-control" v-model="newPassword"  type="password" placeholder="password"/>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<label class="form-label">Confirm Password</label>
							<div class="row row-space-10">
								<div class="col-8">
									<input class="form-control" v-model="newPasswordConfirm" type="password" placeholder="password"/>
								</div>
							</div>
						</div>
						<div class="text-center text-error">
							<div v-if="!passwordMatch" class="alert alert-warning" >
								<span>Passwords do not match</span>
							</div>
						</div>
						<div class="text-center text-error">
							<div v-if="savePasswordError" class="alert alert-warning" >
								<span v-if="savePasswordError">{{ savePasswordError }}</span>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-outline-theme" @click="savePassword" :disabled="!canSavePasswordForm"> Save changes</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modalEditNotifications">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Set Notification</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Notification</label>
							<div class="row row-space-10">
								<div class="col-8">
									<div class="form-check">
										<!-- <input class="form-check-input" type="checkbox" v-model="newNotification" id="defaultCheck1" v-if="newNotification" checked> -->
										<input class="form-check-input" type="checkbox" v-model="newNotification" id="defaultCheck1">
										<label class="form-check-label" for="defaultCheck1">Enable Notification</label>
									</div>
								</div>
							</div>		
							<div class="alert bg-inverse bg-opacity-10 border-0">
								Check if you want to get notifications.
							</div>
							<div class="mb-3">
								<label class="form-label">Enter password</label>
								<div class="row row-space-10">
									<div class="col-8">
										<input class="form-control" type="password" @input="resetPwError" v-model="currentPassword"  placeholder="password"/>
									</div>
								</div>
							</div>					
							<div class="text-center text-error">
								<div v-if="saveNotificationError" class="alert alert-warning" >
									<span v-if="saveNotificationError">{{ saveNotificationError }}</span>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-outline-theme" @click="saveNotification">Save changes</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</template>