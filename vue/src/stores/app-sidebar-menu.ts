import { defineStore } from "pinia";
import { useUserStore } from "@/stores/userStore";
let baseMenu = [
  {
    text: "Work",
    is_header: true,
  },
  {
    url: "/",
    icon: "bi bi-cpu",
    text: "Dashboard",
  },
  {
    url: "/logs",
    icon: "bi bi-gem",
    text: "Logs",
  } /*,{
      'url': '/todo',
      'icon': 'bi bi-check-square',
      'text': 'To-do list'
    }*/,
  {
    is_divider: true,
  },
  {
    text: "Components",
    is_header: true,
  },
  {
    url: "/memo",
    icon: "bi bi-sticky",
    text: "Memo",
  },
  {
    url: "/sites",
    icon: "bi bi-hdd",
    text: "My Servers",
  },
  {
    url: "/panels",
    icon: "bi bi-hdd-network",
    text: "My Panels",
  },

  {
    icon: "bi bi-shop",
    text: "Shop",
    highlight: false,
    children: [
      {
        url: "/shop/",
        text: "Marketplace",
      },
      {
        url: "/shop/leads",
        text: "Leads",
      },
      {
        url: "/shop/panels",
        text: "Panels",
      },
      {
        url: "/shop/accounts",
        text: "Accounts",
      },
      {
        url: "/shop/cosmetics",
        text: "Cosmetics",
      },
      {
        url: "/shop/sell",
        text: "Sell",
      },
    ],
  },
  {
    is_divider: true,
  },
  {
    text: "Users",
    is_header: true,
  },
  {
    url: "/wallet",
    icon: "bi bi-wallet",
    text: "Wallet",
  },
  {
    url: "/order",
    icon: "fas fa-shopping-cart",
    text: "Order",
  },
  /* {
      'url': '/info', 
      'icon': 'bi bi-info-lg',
      'text': 'Info'
    }, */
  /* {
      'url': '/tickets', 
      'icon': 'bi bi-ticket',
      'text': 'Tickets'
    }, */ {
    url: "/settings",
    icon: "bi bi-gear",
    text: "Settings",
  },
];
const adminOptions = [
  {
    url: "/scripts",
    icon: "bi bi-tools",
    text: "Admin",
  },
  {
    url: "/editor",
    icon: "bi bi-code-slash",
    text: "Editor",
  },
  {
    url: "/collaps",
    icon: "bi bi-circle",
    text: "collaps",
  },
];

const shortlinkOptions = [
  {
    url: "/shortlinks",
    icon: "bi bi-signpost",
    text: "Shortlink Manager",
  },
];
export const useAppSidebarMenuStore = defineStore({
  id: "appSidebarMenu",
  actions: {
    reloadMenu: function () {
      const userStore = useUserStore();
      const user = userStore.user;
      let newMenu = baseMenu;
      if (user && user.user_type == "admin") {
        newMenu = newMenu.concat(adminOptions);
      }
      if (user && user.shortlinksPkg) {
        newMenu = newMenu.concat(shortlinkOptions);
      }
      this.content = newMenu;
    },
  },
  state: () => {
    return {
      content: baseMenu,
    };
  },
});
