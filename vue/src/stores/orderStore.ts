import { defineStore, mapActions } from "pinia";
const SERVER = "http://localhost"

export const useOrderStore = defineStore({
  id: "orderStore",
  state: () => {
    return {
      orderData:null
    }
  },
  actions:{
    async loadOrder(){
      let options={
        credentials: 'include'
      }
      try{
        let response=await fetch(SERVER+'/portal/order.php',options);
        if(response.ok){
          let responseData=await response.json()
          if(responseData.status=="ok"){
            this.orderData=responseData.order;
            console.log(responseData.order);
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
    }
  },
});
