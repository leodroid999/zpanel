
const db = require("../db")
export async function GET(req) {
    await db.initDB();
    try{
        const searchParams = req.nextUrl.searchParams
        const featured = searchParams.get('featured')
        console.log(searchParams);
        let filter={};
        if(featured){
            filter = {where:{featured:1}}
        }
        let results=await db.Listing.findAll(filter)
        if(results){
            let resp=JSON.stringify(results,null,2)
            return new Response(resp);
        }
    }
    catch(e){
        console.log(e)
        return new Response("[]");
    }


    return new Response("This is a new API route");
}