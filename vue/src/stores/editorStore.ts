import { defineStore, mapActions } from "pinia";
import { useUserStore } from '@/stores/userStore';
const userStore = useUserStore();
const SERVER = "https://dolph.app"

interface EditorStore {
  bluePrints: any[] | null
  blueprint: any | null,
  blueprintTokens: any[] | null,
  testDeployOutput : string,
  testDeployDone : boolean
}
interface BluePrint{
  blueprint:string
}

export const useEditorStore = defineStore({
  id: "editorStore",
  state: () => {
    return {
      bluePrints:null,
      blueprint:null,
      blueprintTokens : [],
      testDeployOutput: "",
      testDeployDone: false
    } as EditorStore
  },
  actions:{
    async loadBlueprints(){
      let options={
        credentials: 'include' as RequestCredentials
      }
      try{
        let response=await fetch(SERVER+'/portal/blueprints.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.bluePrints=responseData.blueprints;
            console.log(responseData.blueprints);
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

    // async uploadBlueprintInfo(cfg, zip, engine){
    async uploadBlueprintInfo(engine:string, pageName:string){
      let data=new FormData();
      // data.append('cfg', cfg);
      // data.append('zip', zip);
      data.append('engine', engine);
      data.append('pageName', pageName);

      let options={
        method:"POST",
        body: data,
        credentials: 'include' as RequestCredentials as RequestCredentials
      }
      try{
        let response=await fetch(SERVER+'/portal/uploadBlueprint.php',options);
        if(response.ok){
          let responseData=await response.json();
          console.log(responseData);
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

    async deleteBlueprint(blueprint:BluePrint){
      let data=new FormData();
      data.append('blueprint_name', blueprint.blueprint);
      
      let options={
        method:"POST",
        body: data,
        credentials: 'include' as RequestCredentials
      }
      try{
        let response=await fetch(SERVER+'/portal/deleteBlueprint.php', options);
        if(response.ok){
          let responseData=await response.json();
          console.log(responseData);
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


    // Delete Blueprint Responses
    async deleteBlueprintResponses(blueprint:BluePrint){
      let data=new FormData();
      data.append('blueprint_name', blueprint.blueprint);
      
      let options={
        method:"POST",
        body: data,
        credentials: 'include' as RequestCredentials
      }
      try{
        let response=await fetch(SERVER+'/portal/deleteBlueprintResponses.php', options);
        if(response.ok){
          let responseData=await response.json();
          console.log(responseData);
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

    async loadBlueprintTokens(blueprint:BluePrint){

      this.blueprint = blueprint;

      let options={
        method:"GET",
        credentials: 'include' as RequestCredentials
      }
      
      try{
        let response=await fetch(SERVER+"/portal/blueprintTokens.php?blueprint_name="+blueprint.blueprint,options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.blueprintTokens = responseData.blueprintTokens;
            console.log(responseData.blueprintTokens);
          }
          return responseData.blueprintTokens;
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

    async loadBlueprintFiles(blueprint:BluePrint){

      this.blueprint = blueprint;
      let options={
        method:"GET",
        credentials: 'include' as RequestCredentials
      }
      
      try{
        let response=await fetch(SERVER+'/portal/blueprintFiles.php?blueprint_name='+blueprint.blueprint,options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            console.log(responseData.blueprintFiles);
            return responseData.blueprintFiles;
          }
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

    async loadLogs(blueprint:BluePrint){

      this.blueprint = blueprint;

      let data=new FormData();
      data.append('blueprint_name', blueprint.blueprint);

      let options={
        method:"POST",
        body: data,
        credentials: 'include' as RequestCredentials
      }
      
      try{
        let response=await fetch(SERVER+'/portal/blueprintLogs.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            return responseData.logs;
          }
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

    async loadResponses(blueprint:BluePrint){

      this.blueprint = blueprint;

      let data=new FormData();
      data.append('blueprint_name', blueprint.blueprint);

      let options={
        method:"POST",
        body: data,
        credentials: 'include' as RequestCredentials
      }
      
      try{
        let response=await fetch(SERVER+'/portal/blueprintResponse.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            return responseData.response;
          }
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

    async saveBlueprint(blueprint:BluePrint, thumbFile:string){
      let data=new FormData();
      data.append('blueprint', JSON.stringify(blueprint));
      
      if(thumbFile){
        data.append('thumbnail', thumbFile);
      }

      let options={
        method:"POST",
        body: data,
        credentials: 'include' as RequestCredentials
      }
      try{
        let response=await fetch(SERVER+'/portal/saveBlueprint.php', options);
        if(response.ok){
          let responseData=await response.json();
          console.log(responseData);
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
    
    async reindexBlueprint(blueprint:BluePrint){
      let data=new FormData();
      data.append('blueprint_name', blueprint.blueprint);
      
      let options={
        method:"POST",
        body: data,
        credentials: 'include' as RequestCredentials
      }
      try{
        let response=await fetch(SERVER+'/portal/reindexBlueprint.php', options);
        if(response.ok){
          let responseData=await response.json();
          console.log(responseData);
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


    async addBlueprintToken(token:any){
      let data=new FormData();
      data.append('token', JSON.stringify(token));
      
      let options={
        method:"POST",
        body: data,
        credentials: 'include' as RequestCredentials
      }
      try{
        let response=await fetch(SERVER+'/portal/saveBlueprintToken.php', options);
        if(response.ok){
          let responseData=await response.json();
          console.log(responseData);
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

    async deleteBlueprintToken(tokenId:string){
      let data=new FormData();
      data.append('blueprint_tokenId', tokenId);
      
      let options={
        method:"POST",
        body: data,
        credentials: 'include' as RequestCredentials
      }
      try{
        let response=await fetch(SERVER+'/portal/deleteBlueprintToken.php', options);
        if(response.ok){
          let responseData=await response.json();
          console.log(responseData);
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

    async loadBlueprintIndex(blueprint:BluePrint){

      this.blueprint = blueprint;

      let data=new FormData();
      data.append('blueprint_name', blueprint.blueprint);

      let options={
        method:"POST",
        body: data,
        credentials: 'include' as RequestCredentials
      }
      
      try{
        let response=await fetch(SERVER+'/portal/blueprintIndex.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            console.log(responseData.blueprintIndex);
            return responseData.blueprintIndex;            
          }
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

    async archiveBlueprint(blueprint:BluePrint){

      let data=new FormData();
      data.append('blueprint', JSON.stringify(blueprint));
      
      let options={
        method:"POST",
        body: data,
        credentials: 'include' as RequestCredentials
      }
      try{
        let response=await fetch(SERVER+'/portal/archiveBlueprint.php', options);
        if(response.ok){
          let responseData=await response.json();
          console.log(responseData);
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

    async testDeploy(blueprint:BluePrint){
      this.testDeployOutput = `Deploying ${blueprint.blueprint} test...`;
      var xhr = new XMLHttpRequest();
      var url = SERVER+'/portal/deploytestpage.php'
      let formData=new FormData();
      xhr.open("POST", url, true);  
      let last_index=0;
      let onProgress  = () => {
        let curr_index = xhr.responseText.length;
        if (last_index == curr_index) return; 
        let newData = xhr.responseText.substring(last_index, curr_index);
        if(newData.includes("Done")){
          this.testDeployDone = true;
        }
        this.testDeployOutput += newData.replace(/(<([^>]+)>)/gi, "");
        last_index = curr_index;
      };
      onProgress = onProgress.bind(this);
      xhr.onprogress = onProgress;
      xhr.withCredentials = true;; 
      formData.append("blueprint",blueprint.blueprint); 
      xhr.send(formData);
    }
  },
});