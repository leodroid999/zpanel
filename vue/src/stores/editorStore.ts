import { defineStore, mapActions } from "pinia";
const SERVER = "http://z-panel.io"

export const useEditorStore = defineStore({
  id: "editorStore",
  state: () => {
    return {
      blueprints:null
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

    async uploadBlueprintInfo(cfg, zip, engine){
      let data=new FormData();
      data.append('cfg', cfg);
      data.append('zip', zip);
      data.append('engine', engine);
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

    }


  },
});