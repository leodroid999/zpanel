import { defineStore, mapActions } from "pinia";
import { useEditorStore } from '@/stores/editorStore';
import { useSessionStore } from '@/stores/sessionStore';
import { useSiteStore } from '@/stores/siteStore';
import { useStatStore } from '@/stores/statStore';
const SERVER = ""
// const SERVER = "http://localhost"

type User = {
  user_type:String
  shortlinksPkg: boolean;
};

type UserInfo = {

}

type UserNotification = {
  notificationID : number
}
type UserStoreState = {
  user: User | null;
  authenticated: boolean;
  notifications : UserNotification[] | null
  newNotifications : UserNotification[] | null
  enabledNotificationSound: boolean,
  enableLogsAsHome: boolean,
  notificationsSeen: Set<number>,
  memo: string,
  themeClass: string,
  allUsers: UserInfo[] | null
};

export const useUserStore = defineStore("userStore",{
  state: () => {
    let authenticated=false;
    let enabledNotificationSound=false;
    if(localStorage.getItem("authenticated")){
       authenticated=true;
    }
    if (navigator.userActivation && navigator.userActivation.hasBeenActive) {
      enabledNotificationSound=true;
    }
    
    let state:UserStoreState={
        authenticated,
        user:null,
        notifications:null,
        newNotifications:null,
        enabledNotificationSound:false,
        notificationsSeen: new Set(),
        memo: "",
        themeClass : "",
        enableLogsAsHome : false,
        allUsers : [],
    }
    return state;
  },
  actions:{
    async logout(){
      let options={
        method:"POST",
        body: "",
        credentials: 'include'
      }
      let response=await fetch(SERVER+'/portal/logout.php',options as RequestInit);
      if(response.ok){
        let responseData=await response.json()
        if(responseData.status=="ok"){
          this.authenticated=false;
          localStorage.clear()
          this.user=null;
          //const editorStore = useEditorStore();
    			const sessionStore = useSessionStore();
    			const statStore = useStatStore();
    			const siteStore = useSiteStore();
    			sessionStore.$reset();
    			siteStore.$reset();
    			statStore.$reset();
        }
        return responseData;
      }
      else{
        return {
          error:"SERVER_ERROR",
          message:"There was a error processing your request , try again later"
        }
      }
    },

    async login(username:string,password:string){
      if(!username || !password){
        return;
      }
      this.authenticated=false;
      localStorage.removeItem("authenticated");
      this.user=null;
      let data=new FormData();
      data.append('username',username);
      data.append('password',password);
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/logincheck.php',options as RequestInit);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.authenticated=true;
            localStorage.setItem("authenticated",'1')
          }
          return responseData;
        }
        else{
          return {
            error:"SERVER_ERROR",
            message:"There was a error processing your request , try again later"
          }
        }
      }
      catch(err){
        console.error(err);
        return {
          error:"SERVER_ERROR",
          message:"There was a error processing your request , try again later"
        }
      }
    },
    
    async register(username:string,password:string,telegram:string,referal:string){
      if(!username || !password){
        return;
      }
      let data=new FormData();
      data.append('username',username);
      data.append('password',password);
      data.append('telegram',telegram);
      data.append('referal',referal);
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/register.php',options as RequestInit);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.authenticated=true;
          }
          return responseData;
        }
        else{
          return {
            error:"SERVER_ERROR",
            message:"There was a error processing your request , try again later"
          }
        }
      }
      catch(err){
        console.error(err);
        return {
          error:"SERVER_ERROR",
          message:"There was a error processing your request , try again later"
        }
      }
    },
    
    async getUserInfo(){
      let options={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/userinfo.php',options as RequestInit);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.authenticated=true;
            this.user=responseData.user;
            this.memo=atob(responseData.user.memo);
            this.themeClass=responseData.user.themeColor;
            this.enableLogsAsHome = responseData.user.Enable_LogsAsHome;

            console.log(responseData.user);
          }
          return responseData;
        }
        else{
          if(response.status==401){
            this.authenticated=false;
            return {
              error:"NOT_AUTHENTICATED",
              message:"Your session expired, login again"
            }
          }
          return {
            error:"SERVER_ERROR",
            message:"There was a error loading your info , try again later"
          }
        }
      }
      catch(err){
        console.error(err);
        return {
          error:"SERVER_ERROR",
          message:"There was a error processing your request , try again later"
        }
      }
    },
    
    async saveUserInfo(currentPassword:string,updatedInfo:any){
      // if(!currentPassword){
      //   return;
      // }
      let data=new FormData();
      data.append('currentPassword',currentPassword)
      let fields=['username','newPassword','chatID','telegram','referal', 'webNotifs', 'appSetting']
      for(let field of fields){
          if(updatedInfo[field] && updatedInfo[field]!=""){
              data.append(field,updatedInfo[field])
          }
      }
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/saveUserInfo.php',options as RequestInit);
        if(response.ok){
          let responseData=await response.json()
          this.getUserInfo()
          return responseData;
        }
        else{
          return {
            error:"SERVER_ERROR",
            message:"There was a error processing your request , try again later"
          }
        }
      }
      catch(err){
        console.error(err);
        return {
          error:"SERVER_ERROR",
          message:"There was a error processing your request , try again later"
        }
      }
    },


    // Get All Users
    async loadUsersInfo(){
      let options={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/usersinfo.php',options as RequestInit);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            console.log(this.allUsers);
            this.allUsers = JSON.parse(JSON.stringify(responseData.users));
            console.log(this.allUsers);
            return responseData;
          }
          return responseData;
        }
        else{
          if(response.status==401){
            this.authenticated=false;
            return {
              error:"NOT_AUTHENTICATED",
              message:"Your session expired, login again"
            }
          }
          return {
            error:"SERVER_ERROR",
            message:"There was a error loading your info , try again later"
          }
        }
      }
      catch(err){
        console.error(err);
        return {
          error:"SERVER_ERROR",
          message:"There was a error processing your request , try again later"
        }
      }
    },

    // Update user memo
    async saveMemo(memo:string){
      let data=new FormData();
      data.append("memo", btoa(memo));

      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/saveUserMemo.php',options as RequestInit);
        if(response.ok){
          let responseData=await response.json()
          this.getUserInfo()
          return responseData;
        }
        else{
          return {
            error:"SERVER_ERROR",
            message:"There was a error processing your request , try again later"
          }
        }
      }
      catch(err){
        console.error(err);
        return {
          error:"SERVER_ERROR",
          message:"There was a error processing your request , try again later"
        }
      }
    },


    // Update Theme Color
    async saveThemeColor(colorClass:string){
      let data=new FormData();
      data.append("color", colorClass);

      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/saveUserTheme.php',options as RequestInit);
        if(response.ok){
          let responseData=await response.json()
          this.getUserInfo()
          return responseData;
        }
        else{
          return {
            error:"SERVER_ERROR",
            message:"There was a error processing your request , try again later"
          }
        }
      }
      catch(err){
        console.error(err);
        return {
          error:"SERVER_ERROR",
          message:"There was a error processing your request , try again later"
        }
      }
    },


    // Update Theme Color
    async saveEnableLogs(enableLogs:string){
      let data=new FormData();
      data.append("enableLogs", enableLogs);

      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/saveUserEnableLogs.php',options as RequestInit);
        if(response.ok){
          let responseData=await response.json()
          this.getUserInfo()
          return responseData;
        }
        else{
          return {
            error:"SERVER_ERROR",
            message:"There was a error processing your request , try again later"
          }
        }
      }
      catch(err){
        console.error(err);
        return {
          error:"SERVER_ERROR",
          message:"There was a error processing your request , try again later"
        }
      }
    },


    async getNotifications(){
      let options={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/notifications.php',options as RequestInit);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            let newNotifications=responseData.notifications.filter((notification:UserNotification) => {
              if(!this.notificationsSeen.has(notification.notificationID)){
                  this.notificationsSeen.add(notification.notificationID)
                  return notification;
              }
            })
            if(!this.notifications || this.notifications.length==0){
              this.notifications=this.newNotifications
            }
            else{
              let combinedNotifications=newNotifications.concat(this.notifications);
              if(combinedNotifications.length>25){
                combinedNotifications.slice(0,25)
              }
              this.notifications=combinedNotifications
            }
            this.newNotifications = newNotifications;
          }
          return responseData;
        }
        else{
          if(response.status==401){
            this.authenticated=false;
            return {
              error:"NOT_AUTHENTICATED",
              message:"Your session expired, login again"
            }
          }
          return {
            error:"SERVER_ERROR",
            message:"There was a error loading your info , try again later"
          }
        }
      }
      catch(err){
        console.error(err);
        return {
          error:"SERVER_ERROR",
          message:"There was a error processing your request , try again later"
        }
      }
    },

    async markNotificationsRead(){
      let options={
        credentials: 'include',
        method: 'POST'
      }
      try{
        let response=await fetch(SERVER+'/portal/notifications.php',options as RequestInit);
        if(response.ok){
          let responseData=await response.json()
          return responseData;
        }
        else{
          if(response.status==401){
            this.authenticated=false;
            return {
              error:"NOT_AUTHENTICATED",
              message:"Your session expired, login again"
            }
          }
          return {
            error:"SERVER_ERROR",
            message:"There was a error loading your info , try again later"
          }
        }
      }
      catch(err){
        console.error(err);
        return {
          error:"SERVER_ERROR",
          message:"There was a error processing your request , try again later"
        }
      }
    }
    
  },
});