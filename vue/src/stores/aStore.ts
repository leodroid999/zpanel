import { METHODS } from "http";
import { defineStore, mapActions } from "pinia";
const SERVER = "http://localhost"

export const useAStore = defineStore({
  id: "aStore",
  state: () => {
    return {
      panels:null,
      nodes:null,
      users:null,
      pages:null,
      scriptOutput:""
    }
  },
  actions:{
    getDefaultUser(){
        if(this.users && this.users.length > 0){
            return this.users[0]
        }
        return null;
    },
    async getPanelList(){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/functions/panelList.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.panels=responseData.panels
            if(responseData.panels){
              this.panels+responseData.panels;
            }
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
    async getNodeList(){
        let options:any={
          credentials: 'include'
        }
        try{
          let response=await fetch(SERVER+'/functions/nodeList.php',options);
          if(response.ok){
            let responseData=await response.json()
            if(responseData.status=="ok"){
              this.nodes=responseData.nodes;
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
    async getUserList(){
        let options:any={
          credentials: 'include'
        }
        try{
          let response=await fetch(SERVER+'/functions/userList.php',options);
          if(response.ok){
            let responseData=await response.json()
            if(responseData.status=="ok"){
              this.users=responseData.users
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
    async getPageList(){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/functions/pageList.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.pages=responseData.pages
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
    async hostPanel(panelId,nodeId,domain,user,password){
      this.scriptOutput="";
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/functions/hostpanel.php'
      let formData=new FormData();
      xhr.open("POST", url, true);  
      let last_index=0;
      let onProgress  = function(){
        let curr_index = xhr.responseText.length;
        if (last_index == curr_index) return; 
        let newData = xhr.responseText.substring(last_index, curr_index);
        this.scriptOutput += newData.replace(/(<([^>]+)>)/gi, "");
        last_index = curr_index;
      };
      onProgress = onProgress.bind(this);
      xhr.onprogress = onProgress;
      xhr.withCredentials = true;
      formData.append("server_domain",domain);
      formData.append("server_username",user);
      formData.append("password",password);
      formData.append("nodeId",nodeId);
      formData.append("panelId",panelId);
      xhr.send(formData);
    },
    async createPanel(panelId,nodeId,userId){
      this.scriptOutput="";
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/functions/createpanel.php'
      let formData=new FormData();
      xhr.open("POST", url, true);  
      let last_index=0;
      let onProgress  = function(){
        let curr_index = xhr.responseText.length;
        if (last_index == curr_index) return; 
        let newData = xhr.responseText.substring(last_index, curr_index);
        this.scriptOutput += newData.replace(/(<([^>]+)>)/gi, "");
        last_index = curr_index;
      };
      onProgress = onProgress.bind(this);
      xhr.onprogress = onProgress;
      xhr.withCredentials = true;;
      formData.append("nodeId",nodeId);
      formData.append("panelId",panelId);
      formData.append("userId",userId);
      xhr.send(formData);
    },
    async deletePanel(panelId,nodeId){
      this.scriptOutput="";
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/functions/deletepanel.php'
      let formData=new FormData();
      xhr.open("POST", url, true);  
      let last_index=0;
      let onProgress  = function(){
        let curr_index = xhr.responseText.length;
        if (last_index == curr_index) return; 
        let newData = xhr.responseText.substring(last_index, curr_index);
        this.scriptOutput += newData.replace(/(<([^>]+)>)/gi, "");
        last_index = curr_index;
      };
      onProgress = onProgress.bind(this);
      xhr.onprogress = onProgress;
      xhr.withCredentials = true;;
      formData.append("nodeId",nodeId);
      formData.append("panelId",panelId); 
      xhr.send(formData);
    }, 
    async reinstallPanel(panelId,nodeId){
      this.scriptOutput="";
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/functions/reinstallpanel.php'
      let formData=new FormData();
      xhr.open("POST", url, true);  
      let last_index=0;
      let onProgress  = function(){
        let curr_index = xhr.responseText.length;
        if (last_index == curr_index) return; 
        let newData = xhr.responseText.substring(last_index, curr_index);
        this.scriptOutput += newData.replace(/(<([^>]+)>)/gi, "");
        last_index = curr_index;
      };
      onProgress = onProgress.bind(this);
      xhr.onprogress = onProgress;
      xhr.withCredentials = true;;
      formData.append("nodeId",nodeId);
      formData.append("panelId",panelId); 
      xhr.send(formData);
    }, 
    async deployPage(panelId,nodeId,pageId,folderName){
      this.scriptOutput="";
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/portal/admin/deploypage.php'
      let formData=new FormData();
      xhr.open("POST", url, true);  
      let last_index=0;
      let onProgress  = function(){
        let curr_index = xhr.responseText.length;
        if (last_index == curr_index) return; 
        let newData = xhr.responseText.substring(last_index, curr_index);
        this.scriptOutput += newData.replace(/(<([^>]+)>)/gi, "");
        last_index = curr_index;
      };
      onProgress = onProgress.bind(this);
      xhr.onprogress = onProgress;
      xhr.withCredentials = true;;
      formData.append("nodeId",nodeId);
      formData.append("panelId",panelId); 
      formData.append("pageId",pageId); 
      formData.append("folderName",folderName); 
      xhr.send(formData);
    } 
  },
});
