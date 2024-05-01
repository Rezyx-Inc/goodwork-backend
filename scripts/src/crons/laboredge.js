require("dotenv").config();

const url = require('url');
const axios = require("axios");

const mongoose = require('mongoose');
const Laboredge = require('../models/Laboredge');
const Logs = require('../models/Logs');

var _ = require('lodash');
var { report } = require('../set.js')

//Connect to DB
mongoose.connect(process.env.MONGODB_FILES_URI+process.env.MONGODB_INTEGRATIONS_DATABASE_NAME)
.then(() => {
    console.log('Connected to MongoDB');
})
.catch((error) => {
    console.error('Error connecting to MongoDB:', error);
    report("src/crons/laboredge.js error on mongodb connection");
});

// Process laboredge integrations for the first time
module.exports.init = async () => {

	var limit = 100;
	var totalIntegrations = await Laboredge.countDocuments({});
	var totalPages = Math.ceil(totalIntegrations/limit);

	for( i = 0; i < totalPages; i++ ){

		// offset the results by i (current page) * limit (100 by default)
		var offset = i * limit;
		
		// only find non updated documents AKA recently enabled integrations
		await Laboredge.find({updated: { $exists:false }}).limit(limit).skip(offset)
		.then( async laboredge => {
			
			if(!laboredge){
				return
			}
			for ( let [index,user] of laboredge.entries()){
				
				//console.log( "INDEX : ", index,"User ID : ", user.userId)
				
				// select * from laboredge where user_id = laboredge.userId
				
				// WARNING : uses real data - mimic a normal mysql response
				const mysqlResp = {
					user_id:"UWU445837",
					le_password:"Api@Quality_050923",
					le_username:"api_quality",
					le_organization_code:"Quality",
					le_client_id:"nexus"
				}

				// Get the accessToken
				var accessToken = await connectNexus(mysqlResp);

				// Get professions
				const professions = await getProfession(accessToken, mysqlResp.user_id)
				user.professions = professions;
				
				// Get specialties
				const specialties = await getSpecialties(accessToken, mysqlResp.user_id)
				user.specialties = specialties;

				// Get States
				const states = await getStates(accessToken, mysqlResp.user_id)
				user.states = states;

				// Get Countries
				const countries = await getCountries(accessToken, mysqlResp.user_id)
				user.countries = countries;

				// update updated with new Date().toLocaleString()
				user.updated = new Date().toLocaleString();

				// Get Jobs
				const jobs = await getJobs(accessToken, mysqlResp.user_id)
				user.importedJobs = jobs;

				// Send jobs to mysql
				// for job in jobs | insert into jobs (...) values (...)
				// import_id is job.id not job._id

				// Save
				await user.save().then(resp => {}).catch(e=>{console.log(e)})
			}
		})			
	}
}

// Update the existing integrations
module.exports.update = async () => {

	var limit = 100;
	var totalIntegrations = await Laboredge.countDocuments();
	var totalPages = Math.ceil(totalIntegrations/limit);

	for( i = 0; i < totalPages; i++ ){

		// offset the results by i (current page) * limit (100 by default)
		var offset = i * limit;
		
		// only find updated documents AKA Initialised integrations
		await Laboredge.find({updated: { $exists:true }}, {'_id':0, 'importedJobs._id':0, 'importedJobs.rates._id':0}).limit(limit).skip(offset)
		.then( async laboredge => {
			
			// this error should trigger a notification somewhere
			if(!laboredge){
				process.exit(1)
			}

			for ( let [index,user] of laboredge.entries()){
				
				console.log( "INDEX : ", index,"User ID : ", user.userId, "_id:", user._id)
				
				// select * from laboredge where user_id = user.userId
				
				// WARNING : uses real data - mimic a normal mysql response
				const mysqlResp = {
					user_id:"UWU445837",
					le_password:"Api@Quality_050923",
					le_username:"api_quality",
					le_organization_code:"Quality",
					le_client_id:"nexus"
				}

				// Get the accessToken
				var accessToken = await connectNexus(mysqlResp);

				// update updated with new Date().toLocaleString()
				user.updated = new Date().toLocaleString();

				// Get Jobs
				const jobs = await getJobs(accessToken, mysqlResp.user_id)
				
				// Check which job has changed
				const updatedJobs = await getUpdatedJobs(jobs, user.importedJobs, mysqlResp.user_id);
				// should be {toClose: [ids only], toAdd: [{},{}], toUpdate:[{},{}]}

				for( [ind, item] of user.importedJobs.entries()){

					if ( _.includes(updatedJobs.toClose,item.id) ){

						user.importedJobs[ind] = null
						continue
					}

					// Not closed, so update
					for ( updateItem of updatedJobs.toUpdate ){

						if(updateItem.id == item.id){

							user.importedJobs[ind] = updateItem;
						}
					}

				}

				// add new jobs

				for ( newItem of updatedJobs.toAdd ){

					user.importedJobs.push(newItem)
				}
				
				console.log("ACTUAL JOB",user.importedJobs.length, "NEW JOB",jobs.length)
				console.log("TO CLOSE", updatedJobs.toClose.length, "TO ADD", updatedJobs.toAdd.length, "UNCHANGED", updatedJobs.unchanged.length, "TO UPDATE", updatedJobs.toUpdate.length)

				/*
				// Get Jobs - mysql
				// use the updatedJobs response to apply the same changes as above

				// select user_id,import_id from jobs where user_id= mysqlResp.user_id AND import_id NOT NULL
				// apply the changes from updatedJobs

				*/

				// Save
				//await user.save().then(resp => {}).catch(e=>{console.log(e)})
			}
		})			
	}
}

// get professions
async function getProfession (accessToken, userId){

	var activeProfession = [];
	var headers = {
		'Authorization' : 'Bearer '+accessToken,
		'Content-Type': 'application/json'
	};
	
	// Get the total amount of records
	try{
		
		var { data } = await axios.get("https://api-nexus.laboredge.com:9000/api/api-integration/v1/master/professions", {headers});

	}catch(e){
	
		log("Unable to fetch for total records from Nexus.", e.message, userId)
	
	}

	for( profession of data){
		if(profession.active){
			activeProfession.push({profession: profession.name, professionId: profession.id})
		}
	}

	return activeProfession
}

// get specialties
async function getSpecialties (accessToken, userId){

	var activeSpecialty = [];

	var headers = {
		'Authorization' : 'Bearer '+accessToken,
		'Content-Type': 'application/json'
	};
	
	// Get the total amount of records
	try{
		
		var { data } = await axios.get("https://api-nexus.laboredge.com:9000/api/api-integration/v1/master/specialties", {headers});

	}catch(e){
	
		log("Unable to fetch for total records from Nexus.", e.message, userId)
	
	}

	for( specialty of data){
		if(profession.active){
			activeSpecialty.push({specialty: specialty.name, specialtyId: specialty.id})
		}
	}
	
	return activeSpecialty
}

// get states
async function getStates (accessToken, userId){

	var activeState = [];

	var headers = {
		'Authorization' : 'Bearer '+accessToken,
		'Content-Type': 'application/json'
	};
	
	// Get the total amount of records
	try{
		
		var { data } = await axios.get("https://api-nexus.laboredge.com:9000/api/api-integration/v1/master/states", {headers});

	}catch(e){
	
		log("Unable to fetch for total records from Nexus.", e.message, userId)
	
	}
	
	for( state of data){
		activeState.push({stateName: state.name, stateId: state.id, stateCode: state.code})
	}

	return activeState
}

// get countries
async function getCountries (accessToken, userId){

	var activeCountry = [];

	var headers = {
		'Authorization' : 'Bearer '+accessToken,
		'Content-Type': 'application/json'
	};
	
	// Get the total amount of records
	try{
		
		var { data } = await axios.get("https://api-nexus.laboredge.com:9000/api/api-integration/v1/master/countries", {headers});

	}catch(e){
	
		log("Unable to fetch for total records from Nexus.", e.message, userId)
	
	}
	
	for( country of data){
		activeCountry.push({countryName: country.name, countryId: country.id, countryCode: country.code})
	}

	return activeCountry
}

// Update the other integration data - professions, specialties ...
module.exports.updateOthers = async () => {

	var limit = 100;
	var totalIntegrations = await Laboredge.countDocuments({});
	var totalPages = Math.ceil(totalIntegrations/limit);

	for( i = 0; i < totalPages; i++ ){

		// offset the results by i (current page) * limit (100 by default)
		var offset = i * limit;
		
		// only find non updated documents AKA recently enabled integrations
		await Laboredge.find({updated: { $exists:true }}).limit(limit).skip(offset)
		.then( async laboredge => {
			
			if(!laboredge){
				return
			}
			for ( let [index,user] of laboredge.entries()){
				
				//console.log( "INDEX : ", index,"User ID : ", user.userId)
				
				// select * from laboredge where user_id = laboredge.userId
				
				// WARNING : uses real data - mimic a normal mysql response
				const mysqlResp = {
					user_id:"UWU445837",
					le_password:"Api@Quality_050923",
					le_username:"api_quality",
					le_organization_code:"Quality",
					le_client_id:"nexus"
				}

				// Get the accessToken
				var accessToken = await connectNexus(mysqlResp);

				// Get professions
				const professions = await getProfession(accessToken, mysqlResp.user_id)
				user.professions = professions;
				
				// Get specialties
				const specialties = await getSpecialties(accessToken, mysqlResp.user_id)
				user.specialties = specialties;

				// Get States
				const states = await getStates(accessToken, mysqlResp.user_id)
				user.states = states;

				// Get Countries
				const countries = await getCountries(accessToken, mysqlResp.user_id)
				user.countries = countries;

				// update updated with new Date().toLocaleString()
				user.updated = new Date().toLocaleString();

				// Save
				await user.save().then(resp => {}).catch(e=>{console.log(e)})
			}
		})			
	}

}

// get jobs
async function getJobs (accessToken, userId){

	var importedJobs = [];

	var headers = {
		'Authorization' : 'Bearer '+accessToken,
		'Content-Type': 'application/json'
	};
	
	// set the params for the first query
	var params = {
	    jobStatusCode: "OPEN",
	    pagingDetails:{
	        start:0,
	        maxRowsToFetch:100
    	}
	};

	// Get the total amount of records
	try{
		
		var { data } = await axios.post("https://api-nexus.laboredge.com:9000/api/job-service/v1/ats/external/jobs/search", params, {headers});

	}catch(e){
	
		log("Unable to fetch for total records from Nexus.", e.message, userId)
	}
	
	if( data.count > 100 ){

		for(; params.pagingDetails.start < data.count ;){

			try{

				var res = await axios.post("https://api-nexus.laboredge.com:9000/api/job-service/v1/ats/external/jobs/search", params, {headers});

			}catch(e){

				log("Unable to fetch records from nexus. count > 100.", e.message, userId)
			}

			for( entries of res.data.records ){

				importedJobs.push(entries);
			}
			
			// Increment
			params.pagingDetails.start+=100;
		}
	
	}else if (data.count == 0){
		
		log("No jobs to load", "data.count is empty", userId);
	
	}else{

		for( entries of data.records ){

			importedJobs.push(entries);
		}

	}

	return importedJobs;
}

async function getUpdatedJobs(newJobs, oldJobs, userId){

	// check if the job ids from mongodb are in the api response, otherwise return them as closed
	// check if the open jobs are still the same, otherwise update them
	// check if there are new jobs and add them
	// should return {toClose: [ids only], toAdd: [{},{}], toUpdate:[{},{}]

	var toClose = [], toAdd = [], toUpdate = [], unchanged = [];
	var newFormatedJobs = await formatJobs(newJobs);

	// May need to be optimised
	for( job of newFormatedJobs ){
		
		// Check if OldJobs is in NewJobs --> should be kept		
		let needle = _.find(oldJobs, ['id', job.id]);
		
		if(needle){
			
			// check if that OldJobs is the same in NewJobs --> if not, shall be sent to toUpdate
			if(JSON.stringify(needle) === JSON.stringify(job)){
				
				unchanged.push(needle)
			
			}else{

				toUpdate.push(needle)
			}
		
		}
		else{

			toAdd.push(job)
		}
	}

	for( job of oldJobs){

		let needle = _.find(newFormatedJobs, ['id', job.id]);

		if(!needle){
			toClose.push(job.id)
		}
	}

	return {toAdd : toAdd, toUpdate: toUpdate, toClose: toClose, unchanged: unchanged}
}

async function formatJobs(jobs){

	var formatedJobs = [];

	for(job of jobs){

		formatedJobs.push({

            id: job.id,
            jobTitle: job.jobTitle,
            postingId: job.postingId,
            description: job.description,
            signOnBonus: job.signOnBonus,
            jobType: job.jobType,
            startDate: job.startDate,
            endDate: job.endDate,
            duration: job.duration,
            durationType: job.durationType,
            jobStatus: job.jobStatus,
            floatingReqUnits: job.floatingReqUnits,
            shiftsPerWeek1: job.shiftsPerWeek1,
            scheduledHrs1: job.scheduledHrs1,
            shift: job.shift,
            professionId: job.professionId,
            specialtyId: job.specialtyId,
            hourlyPay: job.hourlyPay,
            rates: job.rates
		})
	}

	return formatedJobs
}

// Seed function
module.exports.seed = async ( amount ) =>{

	for(var i = 0; i <= amount; i++){

		try{
		
			var laboredge = {
				userId: "UWU"+Math.random().toString().slice(2,8),
			    userType: "RECRUITER",
			    created: new Date().toLocaleString(),
			    logs: [],
			    professions: [
			        {
			            professionId: Number(Math.random().toString().slice(2,6)),
			            profession: "RN"
			        },
			        {
			            professionId: Number(Math.random().toString().slice(2,6)),
			            profession: "CMA"
			        }
			    ],
			    specialties: [
			        {
			            specialtyId: Number(Math.random().toString().slice(2,6)),
			            specialty: "OR"
			        },
			        {
			            specialtyId: Number(Math.random().toString().slice(2,6)),
			            specialty: "ICU"
			        },
			        {
			            specialtyId: Number(Math.random().toString().slice(2,6)),
			            specialty: "PDICU"
			        }
			    ],
			    states: [
			        {
			            stateId: Number(Math.random().toString().slice(2,4)),
			            stateCode: "WA",
			            stateName: "Washington"
			        }
			    ],
			    countries: [
			        {
			            countryId: 1,
			            countryCode: "US",
			            countryName: "United States"
			        }
			    ],
			    importedJobs: [
			        {
			            id: Math.random().toString().slice(2,14),
			            jobTitle: null,
			            postingId: null,
			            description: "Verry long description for a job post",
			            signOnBonus: null,
			            jobType: "Travel",
			            startDate: randomDate(new Date(2023, 0, 1), new Date()),
			            endDate: randomDate(new Date(2023, 0, 1), new Date()),
			            duration: Math.random().toString().slice(2,4),
			            durationType: "WEEK",
			            jobStatus: "Open",
			            floatingReqUnits: "Yes under x y z specifics",
			            shiftsPerWeek1: Math.random().toString().slice(2,3),
			            scheduledHrs1: Math.random().toString().slice(2,3),
			            shift: "8x4 day only",
			            professionId: Math.random().toString().slice(2,6),
			            specialtyId: Math.random().toString().slice(2,6),
			            hourlyPay: Math.random().toString().slice(2,4),
			            rates: [
			                {
			                    billRateCodeId: "BR_REGULAR_BILL_RATE",
			                    billRateCode: "Regular Bill Rate",
			                    rate: Math.random().toString().slice(2,4)
			                },
			                {
			                    billRateCodeId: "BR_GREATER_THAN_EIGHT",
			                    billRateCode: ">8 Bill Rate",
			                    rate: Math.random().toString().slice(2,4)
			                },
			                {
			                    billRateCodeId: "BR_GREATER_THAN_FOURTY",
			                    billRateCode: ">40 Bill Rate",
			                    rate: Math.random().toString().slice(2,4)
			                },
			                {
			                    billRateCodeId: "BR_HOLIDAY_RATE",
			                    billRateCode: "Holiday Bill Rate",
			                    rate: Math.random().toString().slice(2,4)
			                }
			            ]
			        }
			    ]
			};

			await Laboredge.create(laboredge);
			console.log("Created laboredge ", i);
		
		}catch(e){
			console.log("Err", e)
			console.log(laboredge)
		}

	}
}
// Logger
async function log( message, error, userId ){

	var log = {

	    userId: userId,
	    integration: "Laboredge",
	    api: [
	        {
	        	date: new Date().toLocaleString(),
	        	message: message,
	        	error: error
	        }
	    ]
	};

	var Log = await Logs.findOne({userId: userId})
	.then(async logs => {

        if(!logs){

            let logg = await Logs.create(log)
        	return
        }

        // Put in the log
        logs.api.push(...log.api)

        // If it is too big, shift it
        if(logs.api.length > process.env.MAX_API_LOG_SIZE){
        	logs.api.$shift()
        }
        
        await logs.save()
    })
    .catch(e => {
        console.log("Unexpected error", e)
    });

    return
}

// Helper function to connect to nexus using JWtoken
async function connectNexus(credentials){
	
	// Get the JWT
	var headers = {
		'Content-Type' : 'application/x-www-form-urlencoded',
		'Authorization' : 'Basic bmV4dXM6NXM6Nn5EcEhaelcmVFoj'
	};

	const params = new url.URLSearchParams({ 
		username: credentials.le_username,
		password: credentials.le_password,
		client_id: credentials.le_client_id,
		grant_type: "password",
		organizationCode : credentials.le_organization_code
	});

	try{
		var jwToken = await axios.post("https://api-nexus.laboredge.com:9000/api/secured/oauth/token", params, {headers}); 
		var accessToken = jwToken.data.access_token;
		var refreshToken = jwToken.data.refresh_token;

	}catch(e){

		log("UNABLE TO GET THE JWT TOKEN, REFRESHING", e, credentials.user_id );
		console.log("UNABLE TO GET THE JWT TOKEN, REFRESHING", e, credentials.user_id );

	}

	// Force refresh the JWT, better be safe
	var headers = {
		'Content-Type' : 'application/x-www-form-urlencoded',
		'Authorization' : 'Basic bmV4dXM6NXM6Nn5EcEhaelcmVFoj'
	};

	const refParams = new url.URLSearchParams({ 
		client_id: "nexus",
		grant_type: "refresh_token",
		organizationCode : "Quality",
		refresh_token: refreshToken
	});

	try{
		var refJwToken = await axios.post("https://api-nexus.laboredge.com:9000/api/secured/oauth/token", params, {headers}); 
		var accessToken = refJwToken.data.access_token;
		var refreshToken = refJwToken.data.refresh_token;

	}catch(e){

		log("UNABLE TO REFRESH THE JWT TOKEN, must terminate processing", e, credentials.user_id );
		console.log("UNABLE TO REFRESH THE JWT TOKEN, must terminate processing", e, credentials.user_id );
	}

	return accessToken;
}

// Random date format and generate
function randomDate(start, end) {
    var d = new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime())),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}