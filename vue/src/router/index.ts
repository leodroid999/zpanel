import { createRouter, createWebHistory } from "vue-router";

import { useUserStore } from '@/stores/userStore';
import { useSessionStore } from '@/stores/sessionStore';


const AUTH_REQUIRED={
  meta:{
    requiresAuth:true
  }
}
const SHORTLINK_PKG_REQUIRED={
  meta:{
    requiresAuth:true,
    requiresShortlinkPkg:true
  }
}
const ADMIN_REQUIRED={
  meta:{
    restricted:true
  }
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/login', component: () => import('../views/PageLogin.vue') },
    { path: '/faq', component: () => import('../views/Faq.vue') },
    { path: '/logs', component: () => import('../views/Logs.vue') , ...AUTH_REQUIRED },
    { path: '/settings', component: () => import('../views/Settings.vue') , ...AUTH_REQUIRED },
    { path: '/wallet', component: () => import('../views/Wallet.vue'), ...AUTH_REQUIRED },
    { path: '/order', component: () => import('../views/Order.vue'), ...AUTH_REQUIRED },
    { path: '/register', component: () => import('../views/PageRegister.vue')},
    { path: '/dashboard', component: () => import('../views/Dashboard.vue'), alias: "/", ...AUTH_REQUIRED },
    { path: '/panels', component: () => import('../views/Panels.vue'), ...AUTH_REQUIRED },
    { path: '/sites', component: () => import('../views/Sites.vue'), ...AUTH_REQUIRED },
    { path: '/tickets', component: () => import('../views/Tickets.vue'), ...AUTH_REQUIRED  },
    { path: '/logsdev', component: () => import('../views/Logs_.vue'), ...AUTH_REQUIRED  },
    { path: '/s/:id', component: () => import('../views/Session.vue'), ...AUTH_REQUIRED  },
    { path: '/z/:nodeId/:panelId/:sessionId', component: () => import('../views/SessionDirect.vue'), ...AUTH_REQUIRED  },
    { path: '/analytics', component: () => import('../views/Analytics.vue') },
    //{ path: '/shop/sell', component: () => import('../views/Shop-ProductDetails.vue'), ...AUTH_REQUIRED  },
    { path: '/shop/', component: () => import('../views/Shop-Market.vue'), ...AUTH_REQUIRED  },
    { path: '/shop/:id', component: () => import('../views/Shop-Market.vue'), ...AUTH_REQUIRED  },
    { path: '/scripts', component: () => import('../views/Admin-Scripts.vue'), ...ADMIN_REQUIRED  },
    { path: '/editor/edit', component: () => import('../views/EditPage.vue'), ...ADMIN_REQUIRED  },
    { path: '/editor/edit/:id', component: () => import('../views/EditPage.vue'), ...ADMIN_REQUIRED  },
    { path: '/editor', component: () => import('../views/Page-Editor.vue'), ...ADMIN_REQUIRED  },
    { path: '/collaps', component: () => import('../views/LayoutCollapsedSidebar.vue'), ...ADMIN_REQUIRED  },
    { path: '/pageadd', component: () => import('../views/cfgAdd.vue'), ...AUTH_REQUIRED  },
    { path: '/memo', component: () => import('../views/Memo.vue'), ...AUTH_REQUIRED  },
    { path: '/shortlinks', component: () => import('../views/Shortlink.vue'), ...SHORTLINK_PKG_REQUIRED  }
  ],
});   

router.beforeEach(async(to, from, next) => {
  const userStore = useUserStore();
  const sessionStore = useSessionStore();
  sessionStore.stopUpdates()
  // auth check
  if (to.matched.some((route) => route.meta.requiresAuth)) {
    if (!userStore.authenticated) {
      localStorage.setItem("redirectTo",to.fullPath);
      next({
        path: "/login",
        params: { nextUrl: to.fullPath },
      });
    }   
    else {
      if (to.matched.some((route) => route.meta.requiresShortlinkPkg)) {
        await userStore.getUserInfo();
        if(!userStore.user || !userStore.user.shortlinksPkg){
          if(from.fullPath == to.fullPath){
            return next({
              path: "/"
            });
          }
          return next({
            path: from.fullPath
          });
        }
      }
      next()
    }
  }
  else if (to.matched.some((route) => route.meta.restricted)) {
    if (!userStore.authenticated) {
      next({
        path: "/login",
        params: { nextUrl: to.fullPath },
      });
    }
    else {
      if(!userStore.user){
        let result=await userStore.getUserInfo();
      }
      if (useUserStore.user && userStore.user.user_type!="admin") {
        next({
          path: "/",
          params: { nextUrl: to.fullPath },
        });
      }
      else{
        next()
      }
    }
  }
  else {
    next();
  }
});


export default router;
