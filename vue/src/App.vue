<script setup lang="ts">
import { getCurrentInstance, onMounted } from 'vue';
import { RouterLink, RouterView } from 'vue-router';
import { useAppOptionStore } from '@/stores/app-option';
import { ProgressFinisher, useProgress } from '@marcoschulte/vue3-progress';
import AppSidebar from '@/components/app/Sidebar.vue';
import AppHeader from '@/components/app/Header.vue';
import AppFooter from '@/components/app/Footer.vue';
import router from './router';
import { useUserStore } from '@/stores/userStore';

const appOption = useAppOptionStore();
const internalInstance = getCurrentInstance();
const userStore = useUserStore();

const progresses = [] as ProgressFinisher[];

router.beforeEach(async (to, from) => {
	progresses.push(useProgress().start());
	appOption.appSidebarMobileToggled = false;
	document.body.scrollTop = 0;
	document.documentElement.scrollTop = 0;

	var targetElm = [].slice.call(document.querySelectorAll('.app-sidebar .menu-submenu'));
	targetElm.map(function (elm) {
		elm.style.display = '';
	});
})
router.afterEach(async (to, from) => {
	progresses.pop()?.finish();
})

document.querySelector('body').classList.add('app-init');
</script>
<style>
.modal::after {
	content: "";
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	z-index: -1;
	backdrop-filter: blur(3px);
	/* Adjust the blur intensity as needed */
	pointer-events: none;
	/* Allows interaction with elements behind the modal */
}

.footer {
	position: fixed;
	left: 0;
	bottom: 0;
	height: 35px;
	padding-top: 5px;
	width: 100%;
	background-color: #00000087;
	backdrop-filter: blur(4px);
}
</style>

<template>
	<div class="app" v-bind:class="{
		'app-header-menu-search-toggled': appOption.appHeaderSearchToggled,
		'app-sidebar-toggled': appOption.appSidebarToggled,
		'app-sidebar-collapsed': appOption.appSidebarCollapsed,
		'app-sidebar-mobile-toggled': appOption.appSidebarMobileToggled,
		'app-sidebar-mobile-closed': appOption.appSidebarMobileClosed,
		'app-content-full-height': appOption.appContentFullHeight,
		'app-content-full-width': appOption.appSidebarHide,
		'app-without-sidebar': appOption.appSidebarHide,
		'app-without-header': appOption.appHeaderHide,
		'app-boxed-layout': appOption.appBoxedLayout,
		'app-footer-fixed': appOption.appFooterFixed,
	}">
		<vue3-progress-bar />
		<app-header v-if="!appOption.appHeaderHide" />
		<app-sidebar v-if="!appOption.appSidebarHide" />
		<div class="app-content" v-bind:class="appOption.appContentClass">
			<router-view></router-view>
		</div>
		<app-footer v-if="appOption.appFooter" />
	</div>
</template>


