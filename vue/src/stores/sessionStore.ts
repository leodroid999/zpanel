import { METHODS } from "http";
import { defineStore, mapActions } from "pinia";
const SERVER = "http://z-panel.io"

export const useSessionStore = defineStore({
  id: "sessionStore",
  state: () => {
    return {
      panels:null,
      selectedPanel:null,
      sessions:[],
      selectedSessions:[],
      selectedPanelName:"none",
      pageResults:[],
      currentSession:null,
      scriptOutput:"",
      updateIntervalIds:[],
      currentFilter:null
    }
  },
  actions:{
    async stopUpdates(){
      for(let interval of this.updateIntervalIds){
        clearInterval(interval)
      }
      this.updateIntervalIds.length=0;
    },
    async clearSessions(){
      this.sessions=[],
      this.selectedSessions={}
      this.pageResults=[]
    },
    async setFilter(filter){
      this.currentFilter=filter
      this.stopUpdates();
      this.clearSessions()
      if(this.selectedPanelName=="ALL"){
        this.getAllPanelSessions(false,filter)
      }
      else{
        this.getSessionList(this.selectedPanel.panelId,this.selectedPanel.nodeId,false,false,filter)
      }
    },
    async getPanelList(getSessions,selectDefault = true){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/panelList.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.panels=responseData.panels
            if(responseData.panels && selectDefault){
              let panel=responseData.panels[0];
              this.selectedPanelName=panel.panelId+"@"+panel.nodeID
              this.selectedPanel=panel
              if(getSessions){
                this.getSessionList(panel.panelId,panel.nodeID)
              }
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
    async getPanelSettings(panelId,nodeId){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/panelSettings.php?panelId=${panelId}&nodeId=${nodeId}`,options);
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
    },
    async getPanelAccessList(panelId){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/panelAccessList.php?panelId=${panelId}`,options);
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
    },
    async getAllPanelSessions(panelChange,filter){
      this.selectedPanel=null;
      for(let i=0;i<this.panels.length;i++){
        let panel = this.panels[i]
        let mergeNext = (i != 0 || !panelChange) ? true : false
        this.getSessionList(panel.panelId,panel.nodeID,mergeNext,false,filter)
      }
    },
    async getSessionList(panelId,nodeId,merge,isUpdate,filter){
      if(!merge){
        this.sessions=[]
        this.selectedSessions=[]
        this.pageResults=[]
      }
      if(!isUpdate){
        let sessionListUpdateInterval=setInterval(()=>{
          this.getSessionList(panelId,nodeId,true,true,filter)
        },5000);
        this.updateIntervalIds.push(sessionListUpdateInterval);
      }
      let options:any={
        credentials: 'include'
      }
      try{
        let url=`${SERVER}/portal/sessions.php?panelId=${panelId}&nodeId=${nodeId}`;
        if(filter){
          url+=`&filter=${filter}`;
        }
        let response=await fetch(url,options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            let combinedSessions = []
            let newSessionsDetected  = false
            if(!merge){
              combinedSessions = responseData.sessions;
              combinedSessions = combinedSessions.map(item =>{
                item.panelId = panelId;
                return item
              });
            }
            else{
              let existingSessions = {}
              for(let item of this.sessions){
                existingSessions[item.SessionID] = item;
              }
              let newSessions=[];
              for(let item of responseData.sessions){
                if(existingSessions[item.SessionID]){
                  if(item.Last_Online==existingSessions[item.SessionID].Last_Online){
                    item.Last_Online-=1;
                  }
                  Object.assign(existingSessions[item.SessionID],item);
                }
                else{
                  newSessions.push(item)
                }
              }
              newSessions=newSessions.map(item =>{
                item.panelId = panelId;
                return item
              });
              if(newSessions.length>0){
                newSessionsDetected = true
              }
              combinedSessions = newSessions.concat(this.sessions);
            }
            let sortedSessions=combinedSessions;
            this.sessions=sortedSessions;
            this.selectedSessions=sortedSessions;
            
            if(!isUpdate || (isUpdate && newSessionsDetected)) {
              this.pageResults=sortedSessions.slice(0,50);
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
    async refreshSession(sessionId,panelId,nodeId,interval){
      let intervalId=setTimeout(async ()=>{
        await this.getSession(sessionId,panelId,nodeId)
        this.refreshSession(sessionId,panelId,nodeId)
      },interval)
      this.updateIntervalIds.push(intervalId)
    },
    async searchSession(sessionId){
      let interval=1000
      let panelsRes=await this.getPanelList();
      if(panelsRes.error){
        return panelsRes.error
      }
      for(let panel of panelsRes.panels){
        let sessionRes=await this.getSession(sessionId,panel.panelId,panel.nodeID)
        if(!sessionRes.error){
          if(sessionRes.session){
            this.refreshSession(sessionId,panel.panelId,panel.nodeID,interval)
            return sessionRes;
          }
        }
      }
      return {
        error:"NOT_FOUND",
        message:"Session not found"
      }
    },
    async getSession(sessionId,panelId,nodeId){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/session.php?sessionId=${sessionId}&panelId=${panelId}&nodeId=${nodeId}`,options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            let session=responseData.session;
            if(session){
              for(let key of Object.keys(session)){
                if(session[key] && typeof session[key] == "string" && session[key].toLowerCase()=="null"){
                  session[key]=null
                }
              }
            }
            this.currentSession=session;
            if(this.currentSession){
              this.currentSession.nodeId=nodeId;
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
    async updateRedirect(newRedirect,setError){
      let data=new FormData();
      data.append('sessionId',this.currentSession.SessionID);
      data.append('panelId',this.currentSession.panelID);
      data.append('nodeId',this.currentSession.nodeId);
      data.append('newRedirect',newRedirect);
      if(setError){
        data.append('seterror',true);
      }
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/updateRedirect.php',options);
        if(response.ok){
          let responseData=await response.json()
          this.currentSession.Next_Redirect=responseData.Next_Redirect;
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
    async savePanelSettings(panelId,nodeId,settings){
      let data=new FormData();
      let fields=['antibot_active','mobile_only', 'Redirect_all']
      data.append('panelId',panelId);
      data.append('nodeId',nodeId);
      for(let field of fields){
        if(field in settings)[
          data.append(field,settings[field])
        ]
      }
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/savePanelSettings.php',options);
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
    },
    
    async sendData(newData,newRedirect,sendError){
      let data=new FormData();
      data.append('sessionId',this.currentSession.SessionID);
      data.append('panelId',this.currentSession.panelID);
      data.append('nodeId',this.currentSession.nodeId);
      data.append('newRedirect',newRedirect);
      for(let field in newData){
        data.append("sentcode"+field,newData[field]);
      }
      if(sendError){
        data.append("seterror",true);
      }
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/sendData.php',options);
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
    },
    async bookmarkSession(session,bookmarked){
      let data=new FormData();
      data.append('panelId',session.panelId);
      let node=this.panels.find(panel => session.panelId == panel.panelId)
      if(!node){
        return false;
      }
      data.append('nodeId',node.nodeID);
      data.append('sessionId',session.SessionID);
      data.append('bookmarked',bookmarked);
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/bookmarkSession.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(this.currentFilter == "bookmarked" && !bookmarked){
            this.removeSessionFromMemory(session)
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
    async removeSessionFromMemory(session){
      let index=this.sessions.indexOf(session);
      if (index > -1) {
        this.sessions.splice(index, 1);
      }
      index=this.selectedSessions.indexOf(session);
      if (index > -1) {
        this.selectedSessions.splice(index, 1);
      }
      index=this.pageResults.indexOf(session);
      if (index > -1) {
        this.pageResults.splice(index, 1);
      }
    },
    async deleteSession(session){
      let data=new FormData();
      data.append('panelId',session.panelId);
      let node=this.panels.find(panel => session.panelId == panel.panelId)
      if(!node){
        return false;
      }
      data.append('nodeId',node.nodeID);
      data.append('sessionId',session.SessionID);
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/deleteSession.php',options);
        if(response.ok){
          let responseData=await response.json()
          this.removeSessionFromMemory(session);
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
    async addPanelUser(panelId,username,password,access){
      let data=new FormData();
      data.append('panelId',panelId);
      data.append('username',username);
      data.append('password',password);
      data.append('access',access)
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/addPanelUser.php',options);
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
    },
    async editPanelUser(panelId,username,password,access){
      let data=new FormData();
      data.append('panelId',panelId);
      data.append('username',username);
      data.append('password',password);
      data.append('access',access)
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/editPanelUser.php',options);
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
    },
    async removePanelUser(panelId,username,password){
      let data=new FormData();
      data.append('panelId',panelId);
      data.append('username',username);
      data.append('password',password);
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/removePanelUser.php',options);
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
    },
    async hostPanel(panelId,nodeName,host,user,password){
      this.scriptOutput="";
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/portal/hostPanel.php'
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
      formData.append("host",host);
      formData.append("username",user);
      formData.append("password",password);
      formData.append("nodeName",nodeName);
      formData.append("panelId",panelId);
      xhr.send(formData);
    }
  },
});