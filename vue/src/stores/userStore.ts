import { defineStore, mapActions } from "pinia";
const SERVER = "http://z-panel.io"

export const useUserStore = defineStore({
  id: "userStore",
  state: () => {
    let authenticated=false;
    let enabledNotificationSound=false;
    if(localStorage.getItem("authenticated")){
       authenticated=true;
    }
    if (navigator.userActivation && navigator.userActivation.hasBeenActive) {
      enabledNotificationSound=true;
    }
    return {
        authenticated,
        user:null,
        notifications:null,
        newNotifications:null,
        enabledNotificationSound:false,
        notificationsSeen: new Set(),
        memo: "",
        themeClass : "",
    }
  },
  actions:{
    async logout(){
      let options={
        method:"POST",
        body: "",
        credentials: 'include'
      }
      let response=await fetch(SERVER+'/portal/logout.php',options);
      if(response.ok){
        let responseData=await response.json()
        if(responseData.status=="ok"){
          this.authenticated=false;
          localStorage.clear()
          this.user=null;
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

    async login(username,password){
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
        let response=await fetch(SERVER+'/portal/logincheck.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.authenticated=true;
            localStorage.setItem("authenticated",true)
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
    
    async register(username,password,telegram,referal){
      if(!username || !password){
        return;
      }
      let data=new FormData();
      data.append('username',username);
      data.append('password',password);
      data.append('telegram',telegram);
      data.append('referal',password);
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/register.php',options);
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
        let response=await fetch(SERVER+'/portal/userinfo.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.authenticated=true;
            this.user=responseData.user;
            this.memo=atob(responseData.user.memo);
            this.themeClass=responseData.user.themeColor;
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
    
    async saveUserInfo(currentPassword,updatedInfo){
      if(!currentPassword){
        return;
      }
      let data=new FormData();
      data.append('currentPassword',currentPassword)
      let fields=['username','newPassword','chatID','telegram','referal', 'webNotifs']
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
        let response=await fetch(SERVER+'/portal/saveUserInfo.php',options);
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


    // Update user memo
    async saveMemo(memo){
      let data=new FormData();
      data.append("memo", btoa(memo));

      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/saveUserMemo.php',options);
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
    async saveThemeColor(colorClass){
      let data=new FormData();
      data.append("color", colorClass);

      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/saveUserTheme.php',options);
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
        let response=await fetch(SERVER+'/portal/notifications.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            let newNotifications=responseData.notifications.filter(notification => {
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
        let response=await fetch(SERVER+'/portal/notifications.php',options);
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