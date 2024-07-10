import { defineStore, mapActions } from "pinia";
const SERVER = ""

export const useSiteWidgetStore= defineStore({
  id: "siteWidgetStore",
  state: () => {
    return {
        sites:null,
        shortlinks:null,
        shortlinkDomains:null
    }
  },
  actions:{
    async getWidgetSites(){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/sitesWidget.php`,options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.sites=responseData.sites
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
    async getShortlinkDomains(){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/shortlinkDomains.php`,options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.shortlinkDomains=responseData.shortlinkDomains
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
    async getShortlinks(){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/shortlinks.php`,options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.shortlinks=responseData.shortlinks
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
    async addShortLink(domain,destination,shortname){
      let data=new FormData();
      data.append('domain',domain)
      data.append('destination',destination)
      data.append('shortname',shortname)
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/addShortLink.php',options);
        if(response.ok){
          let responseData=await response.json()
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
    async deleteShortLink(shortname){
      let data=new FormData();
      data.append('shortname',shortname)
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/removeShortLink.php',options);
        if(response.ok){
          let responseData=await response.json()
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
    async editShortLink(shortname,destination){
      let data=new FormData();
      data.append('shortname',shortname)
      data.append('destination',destination)
      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/editShortLink.php',options);
        if(response.ok){
          let responseData=await response.json()
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
  },
});
