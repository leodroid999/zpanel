import { defineStore } from "pinia";

export const useAppOptionStore = defineStore("appOption",{
  state: () => {
    return {
    	appMode: '',
    	appThemeClass: '',
    	appCoverClass: '',
			appBoxedLayout: false,
			appHeaderHide: false,
			appHeaderSearchToggled: false,
			appSidebarCollapsed: false,
			appSidebarMobileToggled: true,
			appSidebarToggled: false,
			appSidebarHide: false,
			appContentFullHeight: false,
			appContentClass: '',
			appFooter: false,
			appFooterFixed: false,
			appThemePanelToggled: false
		}
  }
});
