
const db = require("../../db")
export async function GET(req,{ params }) {
    await db.initDB();
    try{
        const id = (await params).id
        if(!id){
            return new Response(null,{status:400});
        }
        let filter = {where:{listing_id:id}}
        let results=await db.Gallery.findAll(filter)
        if(results){
            let resp=JSON.stringify(results,null,2)
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