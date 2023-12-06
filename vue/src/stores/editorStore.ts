import { defineStore, mapActions } from "pinia";
const SERVER = "http://z-panel.io"

export const useEditorStore = defineStore({
  id: "editorStore",
  state: () => {
    return {
      blueprints:null,
      blueprint:null,
      blueprintTokens : []
    }
  },
  actions:{
    async loadBlueprints(){
      let options={
        credentials: 'include'
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

    // async uploadBlueprintInfo(cfg, zip, engine){
    async uploadBlueprintInfo(engine, pageName){
      let data=new FormData();
      // data.append('cfg', cfg);
      // data.append('zip', zip);
      data.append('engine', engine);
      data.append('pageName', pageName);

      let options={
        method:"POST",
        body: data,
        credentials: 'include'
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

    async deleteBlueprint(blueprint){
      let data=new FormData();
      data.append('blueprint_name', blueprint.blueprint);
      
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
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

    async loadBlueprintTokens(blueprint){

      this.blueprint = blueprint;

      let data=new FormData();
      data.append('blueprint_name', blueprint.blueprint);

      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      
      try{
        let response=await fetch(SERVER+'/portal/blueprintTokens.php',options);
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

    async loadLogs(blueprint){

      this.blueprint = blueprint;

      let data=new FormData();
      data.append('blueprint_name', blueprint.blueprint);

      let options={
        method:"POST",
        body: data,
        credentials: 'include'
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

    async loadResponses(blueprint){

      this.blueprint = blueprint;

      let data=new FormData();
      data.append('blueprint_name', blueprint.blueprint);

      let options={
        method:"POST",
        body: data,
        credentials: 'include'
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

    async saveBlueprint(blueprint){
      let data=new FormData();
      data.append('blueprint', JSON.stringify(blueprint));
      
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
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

    async addBlueprintToken(token){
      let data=new FormData();
      data.append('token', JSON.stringify(token));
      
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
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

    async deleteBlueprintToken(tokenId){
      let data=new FormData();
      data.append('blueprint_tokenId', tokenId);
      
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
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

    async loadBlueprintIndex(blueprint){

      this.blueprint = blueprint;

      let data=new FormData();
      data.append('blueprint_name', blueprint.blueprint);

      let options={
        method:"POST",
        body: data,
        credentials: 'include'
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

    async archiveBlueprint(blueprint){
      let data=new FormData();
      data.append('blueprint', JSON.stringify(blueprint));
      
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
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
    }
  },
});