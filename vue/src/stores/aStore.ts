import { defineStore, mapActions } from "pinia";
import { useUserStore } from '@/stores/userStore';
const userStore = useUserStore();
const SERVER = ""

interface AdminStoreState {
  panels:any[] | null,
  nodes:any[] | null,
  users:any[] | null,
  pages:any[] | null,
  scriptOutput:string
}

export const useAStore = defineStore("aStore",{
  state: () => {
    return {
      panels:null,
      nodes:null,
      users:null,
      pages:null,
      scriptOutput:"" 
    } as AdminStoreState
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
        let response=await fetch(SERVER+'/portal/admin/panelList.php',options);
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
            userStore.authenticated=false;
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
          let response=await fetch(SERVER+'/portal/admin/nodeList.php',options);
          if(response.ok){
            let responseData=await response.json()
            if(responseData.status=="ok"){
              this.nodes=responseData.nodes;
            }
            return responseData;
          }
          else{
            if(response.status==401){
              userStore.authenticated=false;
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
          let response=await fetch(SERVER+'/portal/admin/userList.php',options);
          if(response.ok){
            let responseData=await response.json()
            if(responseData.status=="ok"){
              this.users=responseData.users
            }
            return responseData;
          }
          else{
            if(response.status==401){
              userStore.authenticated=false;
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
        let response=await fetch(SERVER+'/portal/admin/pageList.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.pages=responseData.pages
          }
          return responseData;
        }
        else{
          if(response.status==401){
            userStore.authenticated=false;
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
    async hostPanel(panelId:string,nodeId:string,domain:string,user:string,password:string){
      this.scriptOutput="";
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/portal/admin/hostpanel.php'
      let formData=new FormData();
      xhr.open("POST", url, true);  
      let last_index=0;
      let onProgress  =() => {
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
    async createPanel(panelId:string,nodeId:string,userId:string){
      this.scriptOutput="";
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/portal/admin/createpanel.php'
      let formData=new FormData();
      xhr.open("POST", url, true);  
      let last_index=0;
      let onProgress  = () => {
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
    async deletePanel(panelId:string,nodeId:string){
      this.scriptOutput="";
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/portal/admin/deletepanel.php'
      let formData=new FormData();
      xhr.open("POST", url, true);  
      let last_index=0;
      let onProgress  = () =>{
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
    async reinstallPanel(panelId:string,nodeId:string){
      this.scriptOutput="";
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/portal/admin/reinstallpanel.php'
      let formData=new FormData();
      xhr.open("POST", url, true);  
      let last_index=0;
      let onProgress  = () => {
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
    async deployPage(panelId:string, nodeId:string ,pageId:string ,folderName:string ){
      this.scriptOutput="";
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/portal/admin/deploypage.php'
      let formData=new FormData();
      xhr.open("POST", url, true);  
      let last_index=0;
      let onProgress  = () => {
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