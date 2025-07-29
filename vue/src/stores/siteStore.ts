import { defineStore, mapActions } from "pinia";
const SERVER = ""

export const useSiteStore= defineStore('site-store',{
  state: () => {
    return {
        sites:null,
        shortlinks:null,
        shortlinkDomains:null,
        oldSites : null
    }
  },
  actions:{
    async addSite(site){
      let data=new FormData();
      data.append('domain', site.domain)
      data.append('panelId', site.panelId)
      data.append('hostStatus', site.hostStatus)
      data.append('currentIp', site.currentIp)      

      let options={
        method:"POST",
        body: data,
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/siteAdd.php',options);
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

    async clearOldSites(){
      let options:any={
        credentials: 'include'
      }

      try{
        let response=await fetch(SERVER+'/portal/siteClearOld.php',options);
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

    async getOldSites(){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/sitesOld.php`,options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.oldSites=responseData.sites
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
    
    async getSites(){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/sites.php`,options);
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
