import { defineStore, mapActions } from "pinia";
// const SERVER = "http://z-panel.io"
const SERVER = "http://localhost"

export const useStatStore= defineStore({
  id: "statStore",
  state: () => {
    return {
        panels:null,
        panelSelected:null,
        stats:null
    }
  },
  actions:{
    async getStats(panelId,nodeId){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/stats.php?panelId=${panelId}&nodeId=${nodeId}`,options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.stats=responseData.stats;
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
  },
});