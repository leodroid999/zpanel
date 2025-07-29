
const db = require("../../db")
export async function GET(req,{ params }) {
    await db.initDB();
    try{
        const id = (await params).id
        if(!id){
            return new Response(null,{status:400});
        }
        let filter = {where:{id}}
        let result=await db.Listing.findOne(filter)
        if(result){
            if(result.tags){
                result.tags=result.tags.split(",");
            }
            if(result.features){
                result.features=result.features.split(",");
            }
            let resp=JSON.stringify(result,null,2)
            return new Response(resp);
        }
        else{
            return new Response(null,{status:404});
        }
    }
    catch(e){
        console.log(e)
        return new Response(null,{status:500});
    }
}