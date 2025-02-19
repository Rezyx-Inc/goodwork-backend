require("dotenv").config();
var axios = require("axios");
const queries = require("../../mysql/queries.js")

if(process.env.APP_ENV == "production"){
	const expedientOrgId = "";
}else{
	const expedientOrgId = "";
}

module.exports.init = async () => { 

	let credentials = await queries.getCeipalCredentials(expedientOrgId);
	let token = await connectCeipal(credentials);

	if(!token){
		console.log("no token")
		return
	}
	let postings = await getCeipalPostings(token.access_token);

}

module.exports.update = async () => {

}

async function connectCeipal(credentials){

	try{
		let tokens = await axios.post("https://api.ceipal.com/v1/createAuthtoken", credentials);
		return tokens
	}catch(e){
		console.log(e);
		return false
	}
}

async function getCeipalPostings(token){

	try{

		let params = new URLSearchParams({limit : 50, sortorder: "asc", sortby: "created"});
		let jobPostings = await axios.get("https://api.ceipal.com/v1/getJobPostingsList/?" + params.toString());

		return jobPostings

	}catch(e){
		console.log(e);
		return false
	}
}
