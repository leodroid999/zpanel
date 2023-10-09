<script>
import { useAppOptionStore } from '@/stores/app-option';
import { useAppSidebarMenuStore } from '@/stores/app-sidebar-menu';
import { useUserStore } from '@/stores/userStore';
import { RouterLink } from 'vue-router';
import { Toast } from 'bootstrap';

export default{
	data: function(){
		return{
			activeNotification:null,
			toast:null,
			toastQueue:[]
		}
	},
	methods:{
		toggleAppSidebarCollapsed: function () {
			const appOption = useAppOptionStore();
			if (appOption.appSidebarCollapsed) {
				appOption.appSidebarToggled = !appOption.appSidebarToggled;
			} else if (appOption.appSidebarToggled) {
				appOption.appSidebarToggled = !appOption.appSidebarToggled;
			}
			appOption.appSidebarCollapsed = !appOption.appSidebarCollapsed;
		},
		toggleAppSidebarMobileToggled: function () {
			const appOption = useAppOptionStore();
			appOption.appSidebarMobileToggled = !appOption.appSidebarMobileToggled;
		},
		toggleAppHeaderSearch: function (event) {
			const appOption = useAppOptionStore();
			event.preventDefault();
			
			appOption.appHeaderSearchToggled = !appOption.appHeaderSearchToggled;
		},
		setupHandlers:function(){
			const appSidebarMenu = useAppSidebarMenuStore();
			const userStore = useUserStore()
			const checkAuth=(result)=>{
				if(result.error){
						if(result.error=="NOT_AUTHENTICATED"){
							this.onSessionExpired()
						}
					}
			}
			userStore.$onAction(({name, store, args, after, onError})=>{
				if(name=="getUserInfo"){				
					after((result)=>{
						checkAuth(result)
						appSidebarMenu.reloadMenu();
					})
				}
				else{
					after((result)=>{
						checkAuth(result);
					})
				}
			})
		},
		onSessionExpired: function(){
			document.cookie="";
			this.$router.push({ path: '/login' })
		},
		logout: function(){
			const userStore = useUserStore()
			userStore.logout();
			document.cookie="";
			this.$router.push({ path: '/login' })
		},
		loadUserInfo: function(){
			const userStore = useUserStore()
			if(userStore.authenticated && !userStore.user){
				userStore.getUserInfo();
			}
		},
		getNotifications: async function(){
			const userStore = useUserStore()
			if(userStore.authenticated){
				await userStore.getNotifications()
				let soundAlert=false
				if(!userStore.newNotifications){
					return false;
				}
				for(let notification of userStore.newNotifications){
					if(notification.IsAlerted){
						soundAlert=true;
						break;
					}
				}
				if(soundAlert && userStore.enabledNotificationSound){
					let audio = new Audio("/public/ding.wav");
					try{
						audio.play();
					}
					catch(e){

					}
				}
				for(let notification of userStore.newNotifications){
					this.toastQueue.push(notification)
				}
				setTimeout(()=>{
					this.getNotifications()
				},15000);
			}
		},
		processQueuedToasts:function(){
			let toastPending = this.toastQueue.length > 0;
			let interval = toastPending ? 3000 : 1000 ;
			if(toastPending){
				this.toast.hide()
				this.activeNotification = this.toastQueue.pop();
				this.toast.show()
			}
			setTimeout(()=>{
				this.processQueuedToasts()
			},interval);
		},
		openNotifications:function(){
			const userStore = useUserStore()
			userStore.markNotificationsRead();
		},
		toggleNotificationSound:function(){
			const userStore = useUserStore()
			userStore.enabledNotificationSound = !userStore.enabledNotificationSound
		},
		timeAgo:function(str){;
			const date= new Date(str+"Z").getTime()
			const seconds = Math.floor(new Date().getTime() - date) / 1000;

			let interval = Math.floor(seconds / 31536000);
			if (interval > 1) {
				return interval + ' years ago';
			}

			interval = Math.floor(seconds / 2592000);
			if (interval > 1) {
				return interval + ' months ago';
			}

			interval = Math.floor(seconds / 86400);
			if (interval > 1) {
				return interval + ' days ago';
			}

			interval = Math.floor(seconds / 3600);
			if (interval > 1) {
				return interval + ' hours ago';
			}

			interval = Math.floor(seconds / 60);
			if (interval > 1) {
				return interval + ' minutes ago';
			}

			if(seconds < 10) return 'just now';

			return Math.floor(seconds) + ' seconds ago';
		}
	},	
	computed:{
		userStore:function(){
			return useUserStore()
		},
		balance:function(){
			const userStore = useUserStore()
			if(userStore.user && (userStore.user.balance !== null)){
				let cents=parseFloat(userStore.user.balance)
				let usd=(cents/100).toFixed(2);
				let str=`${usd}\$`;
				return str;
			}
			return "-"
		}
	},
	mounted(){
		this.setupHandlers()
		this.loadUserInfo()
		this.getNotifications();
		this.toast=new Toast(this.$refs.toastElement);
		this.processQueuedToasts()
	}
}

</script>
<style>
	.notification-content{
		max-height: 40vh;
		overflow-y: scroll;
	}
	.notification-content::-webkit-scrollbar {
    	width: 12px;
	}

	.notification-content::-webkit-scrollbar-track {
		-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.4); 
		border-radius: 10px;
	}

	.notification-content::-webkit-scrollbar-thumb {
		border-radius: 10px;
		background-color: rgb(0,0,0,0.7);
		-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
	}
</style>
<template>
	<div id="header" class="app-header">
		<!-- BEGIN desktop-toggler -->
		<div class="desktop-toggler">
			<button type="button" class="menu-toggler" v-on:click="toggleAppSidebarCollapsed">
				<span class="bar"></span>
				<span class="bar"></span>
				<span class="bar"></span>
			</button>
		</div>
		<!-- BEGIN desktop-toggler -->
		
		<!-- BEGIN mobile-toggler -->
		<div class="mobile-toggler">
			<button type="button" class="menu-toggler" v-on:click="toggleAppSidebarMobileToggled">
				<span class="bar"></span>
				<span class="bar"></span>
				<span class="bar"></span>
			</button>
		</div>
		<!-- END mobile-toggler -->
		
		<!-- BEGIN brand -->
		<div class="brand">
			<RouterLink to="/" class="brand-logo">
				<span class="brand-img">
					<span class="brand-img-text text-theme">Z</span>
				</span>
				<span class="brand-text">Z-Network</span>
			</RouterLink>
		</div>
		<!-- END brand -->
		
		<!-- BEGIN menu -->
		<div class="menu">
			<div class="menu-item">
				<a href="#" v-on:click="toggleAppHeaderSearch" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app" class="menu-link">
					<div class="menu-icon"><i class="bi bi-search nav-icon"></i></div>
				</a>
			</div>
			<div class="menu-item dropdown dropdown-mobile-full">
				<a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
					<div class="menu-icon" @click="openNotifications"><i class="bi bi-card-checklist nav-icon"></i></div>
					<div class="menu-badge bg-theme" v-if="userStore.notifications && userStore.notifications.length > 0"></div>
				</a>
				<a href="#" data-bs-display="static" class="menu-link">
					<div class="menu-icon"><i v-if="!userStore.enabledNotificationSound" @click="toggleNotificationSound" class="bi bi-bell-slash nav-icon"></i></div>
					<div class="menu-icon"><i v-if="userStore.enabledNotificationSound" @click="toggleNotificationSound" class="bi bi-bell nav-icon"></i></div>
				</a>
				<div class="dropdown-menu dropdown-menu-end mt-1 w-300px fs-11px pt-1">
					<h6 class="dropdown-header fs-10px mb-1">NOTIFICATIONS</h6>
					<div class="dropdown-divider mt-1"></div>
					<div class="notification-content">
						<template v-if="userStore.notifications && userStore.notifications.length > 0">
						<a href="#" class="d-flex align-items-center py-10px dropdown-item text-wrap" v-for="(notification,index) in userStore.notifications" v-bind:key="index">
							<div class="fs-20px">
								<i v-if="notification.icon" v-bind:class="'bi bi-'+notification.icon+' text-theme'"></i>
							</div>
							<div class="flex-1 flex-wrap ps-3">
								<div class="mb-1 text-white">{{ notification.content }}</div>
								<div class="small">{{ timeAgo(notification.time) }}</div>
							</div>
							<div class="ps-2 fs-16px">
								<i class="bi bi-chevron-right"></i>
							</div>
						</a>
						</template>
						<template v-else>
							<div class="dropdown-notification-item">
								No new notifications
							</div>
						</template>
					</div>
					<hr class="bg-white-transparent-5 mb-0 mt-2" />
					<div class="py-10px mb-n2 text-center">
						<a href="#" class="text-decoration-none fw-bold">SEE ALL</a>
					</div>
				</div>
			</div>
			<span v-if="userStore.user">{{balance}}</span>
			<div class="menu-item dropdown dropdown-mobile-full"> 
				<a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
					<div class="menu-img online">
						<img src="/assets/img/user/profile.jpg" alt="Profile" height="60" />
					</div>
					<div v-if="userStore.user" class="menu-text d-sm-block d-none">{{userStore.user.username}}</div>
				</a>
				<div class="dropdown-menu dropdown-menu-end me-lg-3 fs-11px mt-1">
					<RouterLink to="/profile" class="dropdown-item d-flex align-items-center">PROFILE <i class="bi bi-person-circle ms-auto text-theme fs-16px my-n1"></i></RouterLink>
									<RouterLink to="/orders" class="dropdown-item d-flex align-items-center">ORDERS <i class="bi bi-briefcase ms-auto text-theme fs-16px my-n1"></i></RouterLink>
					<RouterLink to="/settings" class="dropdown-item d-flex align-items-center">SETTINGS <i class="bi bi-gear ms-auto text-theme fs-16px my-n1"></i></RouterLink>
					<div class="dropdown-divider"></div>
					<div class="dropdown-item d-flex align-items-center" v-on:click="logout">LOGOUT <i class="bi bi-toggle-off ms-auto text-theme fs-16px my-n1"></i></div>
				</div>
			</div>
		</div>
		<!-- END menu -->
		
		<!-- BEGIN menu-search -->
		<form class="menu-search" name="header_search_form" v-on:submit="checkForm">
			<div class="menu-search-container">
				<div class="menu-search-icon"><i class="bi bi-search"></i></div>
				<div class="menu-search-input">
					<input type="text" class="form-control form-control-lg" placeholder="Search menu..." />
				</div>
				<div class="menu-search-icon">
					<a href="#" v-on:click="toggleAppHeaderSearch"><i class="bi bi-x-lg"></i></a>
				</div>
			</div>
		</form>
		<!-- END menu-search -->
		<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
			<div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" ref="toastElement">
				<div v-if="activeNotification">
					<div class="toast-header">
						<strong class="me-auto">New Activity</strong>
						<small>{{timeAgo(activeNotification.time)}}</small>
						<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
					</div>
					<div class="toast-body">
						{{activeNotification.content }}
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
