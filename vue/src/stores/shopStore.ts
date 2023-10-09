import { defineStore, mapActions } from "pinia";
// const SERVER = "http://z-panel.io"
const SERVER = "http://localhost"

export const useShopStore= defineStore({
  id: "shopStore",
  state: () => {
    return {
        products:null
    }
  },
  actions:{
    async getProducts(){
      let options:any={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+`/portal/products.php`,options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.products=responseData.products
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
            message:"There was a error loading the shop products , try again later"
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
    async buyProduct(isDownload,productID,startTime,days){
        let data=new FormData();
        data.append('productID',productID)
        if(!isDownload){
            data.append('startTime',startTime);
            data.append('days',days);
        }
        let options={
          method:"POST",
          body: data,
          credentials: 'include'
        }
        try{
          let response=await fetch(SERVER+'/portal/buyProduct.php',options);
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