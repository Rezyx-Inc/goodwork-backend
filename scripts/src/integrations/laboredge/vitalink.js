//To configure environment variables from a .env file
require("dotenv").config();

//import all required libraries and/or modules
const url = require('url'); //  Contains url related functions
const axios = require("axios"); // Used to make http requests

const mongoose = require('mongoose'); //  To connect to mongoDB
const queries = require("../../mysql/queries.js")

const Laboredge = require('../../models/Laboredge');
const Logs = require('../../models/Logs');
var moment = require("moment"); // Used for date-time changes

var _ = require('lodash'); // Used for data manipulation
var { report } = require('../../set.js');

if(process.env.APP_ENV == "production"){

	var vitalinkOrgId = "GWU000032";
	return

}else{

	var vitalinkOrgId = "GWU000002";
}


//Connect to DB
mongoose.connect(process.env.MONGODB_FILES_URI+process.env.MONGODB_INTEGRATIONS_DATABASE_NAME)
.then(() => {
    console.log('Connected to MongoDB');
})
.catch((error) => {
    console.error('Error connecting to MongoDB:', error);
    //report("src/crons/laboredge.js error on mongodb connection");
});


// Process laboredge integrations for the first time
module.exports.init = (async () => {

	// console.log("Inside init method");
		try{
					
			var mysqlResp = await queries.getLaboredgeLogin(vitalinkOrgId); // Get login details from the db
			var vitalinkMongo = await Laboredge.find({userId: vitalinkOrgId});

			//Flattening the arrays
			vitalinkMongo = vitalinkMongo[0];
			mysqlResp = mysqlResp[0];

			// console.log("Mysql resp : "+mysqlResp);
			// Get the accessToken
			var accessToken = await connectNexus(mysqlResp);

			// Get and update professions
			const professions = await getProfession(accessToken, mysqlResp.user_id)
			vitalinkMongo.professions = professions;

			// Get and update specialties
			const specialties = await getSpecialties(accessToken, mysqlResp.user_id)
			vitalinkMongo.specialties = specialties;

			// Get and update States
			const states = await getStates(accessToken, mysqlResp.user_id)
			vitalinkMongo.states = states;

			// Get and update Countries
			const countries = await getCountries(accessToken, mysqlResp.user_id)
			vitalinkMongo.countries = countries;

			// update updated with new Date().toLocaleString()
			vitalinkMongo.updated = moment().format("YYYY-MM-DD[T]HH:mm:ss");

			// Get and update Jobs
			const jobs = await getJobs(accessToken, mysqlResp.user_id, false, false); 
			vitalinkMongo.importedJobs = jobs;
			vitalinkMongo.initiated = 1;

			// Save the updated vitalinkMongo in the db
			await vitalinkMongo.save().then(resp => {}).catch(e=>{console.log(e)});

			for(job of vitalinkMongo.importedJobs){

				job.shift = await formatShifts(job.shiftStartTime1);
				job.specialty = await formatSpecialties(specialties,job.specialty);
				job.profession = await formatProfessions(professions,job.profession);

				await queries.addImportedJob(job, mysqlResp.user_id);
				
			}

		}catch(e){

			console.log("Unknown error", e);
		}

	console.log("Jobs saved into the db");

	return;
});


//Helper function to have round-robin for recruiter updates

// Update the existing integrations
module.exports.update = (async () => {

	try{

    	var mysqlResp = await queries.getLaboredgeLogin(vitalinkOrgId) // Get login details from the db

    	// Get old jobs -> Swapping for mysql jobs
    	var vitalinkMongo = await Laboredge.find({userId: vitalinkOrgId});

    	// flatten the array
    	vitalinkMongo = vitalinkMongo[0];
		mysqlResp = mysqlResp[0];

    	// Get the accessToken
    	var accessToken = await connectNexus(mysqlResp);

    	// Get Jobs from the laboredge API
    	const jobs = await getJobs(accessToken, mysqlResp.user_id, true, false);

		if(jobs.length == 0){
			console.log("Returning as there are no jobs to update");
			return ;
		}

    	// Check which job has changed
    	const updatedJobs = await getUpdatedJobs(jobs, vitalinkMongo.importedJobs, mysqlResp.user_id);

    	console.log("ACTUAL JOB",vitalinkMongo.importedJobs.length, "UPDATE JOBS",jobs.length)
    	console.log("TO CLOSE", updatedJobs.toClose.length, "TO ADD", updatedJobs.toAdd.length, "UNCHANGED", updatedJobs.unchanged.length, "TO UPDATE", updatedJobs.toUpdate.length)

		var count = 0;

    	for( [ind, item] of vitalinkMongo.importedJobs.entries()){

        	if ( _.includes(updatedJobs.toClose, item.id) ){

        		// Update mysql
            	let closingJob = await queries.closeImportedJobs(item.id, mysqlResp.user_id);

            	// Remove the job from the list
            	vitalinkMongo.importedJobs.splice(ind, 1);

            	continue;
        	}

        	// Not closed, so update
        	for ( updateItem of updatedJobs.toUpdate ){

            	if(updateItem.id == item.id){
					
					updateItem.shift = await formatShifts(updateItem.shiftStartTime1);
					updateItem.specialty = await formatSpecialties(specialties,updateItem.specialty);
					updateItem.profession = await formatProfessions(professions,updateItem.profession);

					//update in the db
                	let result = await queries.updateLaboredgeJobs(updateItem, vitalinkOrgId);

					if(!result){
						count++;
					}

                	vitalinkMongo.importedJobs[ind] = updateItem;
            	}
        	}

    	}

    	// add new jobs
    	for ( newItem of updatedJobs.toAdd ){

    		newItem.shift = await formatShifts(newItem.shiftStartTime1);
			newItem.specialty = await formatSpecialties(specialties,newItem.specialty);
			newItem.profession = await formatProfessions(professions,newItem.profession);


        	await queries.addImportedJob(newItem);
        	vitalinkMongo.importedJobs.push(newItem);
    	}

    	// update updated (timestamp)
    	vitalinkMongo.updated = moment().format("YYYY-MM-DD[T]HH:mm:ss");

    	// Save
    	await vitalinkMongo.save().then(resp => {}).catch(e=>{console.log(e)})

	}catch(e){
    	console.log("Unknown error", e);
	}

	console.log("Jobs updated in the db");
	return
});

// get professions
async function getProfession (accessToken, userId){

	console.log("Inside getProfession");
	const professions = {
		"(RN)": "RN",
		"(CNA)": "CNA",
		"(CMA)": "CMA",
		"(Tech)":"Tech / Assist",
		"(Admin Assistant)": "Tech / Assist",
		"(Therapist)": "Therapy",
		"(Therapy)": "Therapy",
		"(Physician)": "Physician",
		"(PA)": "PA",
		"(CRNA)": "CRNA",
		"(CNP)": "NP",
		"(LVN/LPN)": "LPN / LVN",
		"(Social Worker)": "Social Work",
		"(Other)": "Other Clinician"
	};
	// Array to hold active professions
	var activeProfession = [];
	
	//Headers required for the api call
	var headers = {
		'Authorization' : 'Bearer '+accessToken,
		'Content-Type': 'application/json'
	};
	
	// Get the total amount of records
	try{
		
		//API call to fetch professions
		var { data } = await axios.get("https://api-nexus.laboredge.com:9000/api/api-integration/v1/master/professions", {headers});
		
			for(profession of data){
				if(profession.active){
					var mappedProfession = "";
					for (let [pattern, mappedPro] of Object.entries(professions)) {
						if (profession.name.match(new RegExp(pattern, 'gi'))) {
							mappedProfession = mappedPro;
							break;
						}
					}
					activeProfession.push({mappedProfession: mappedProfession, profession: profession.name});
				}
			}
	}catch(e){
	
		//log in case of API call failure
		log("Unable to fetch for total records from Nexus.", e.message, userId);
	
	}

	return activeProfession;
};

// get specialties
async function getSpecialties (accessToken, userId){

	const specialtyMap = {
		"(Acute Care)":"Acute Care",
		"(Admin)": "Administrative",
		"(Administrator)": "Administrative",
		"(Ambulatory)": "Ambulatory Care",
		"(Blood Drawls)": "Phlebotomist",
		"(Bone Marrow Oncology)": "Bone Marrow Transplant",
		"(Burn Unit)": "Burn ICU",
		"(Cardiac)": "Cardiology",
		"(Cardio)": "Cardiology",
		"(Cardio/Pulmonary)": "[Cardiology, Pulmonology]",
		"(Case Manager)": "Case Management",
		"(Cath Lab)": "Cardiac Cath Lab",
		"(CCU)": "CCU - Coronary Care",
		"(Chemotherapy)": "Hematology & Oncology",
		"(Corrections)": "Correctional",
		"(CT)": "CT Technologist",
		"(Diagnostics Radiology)": "Radiology",
		"(Dietitian)": "RDCD Dietitian",
		"(Director)": "Director of Nursing",
		"(EP Lab)": "Electrophysiology Lab",
		"(ER)": "ED - Emergency Department",
		"(ER - Peds)": "Pediatrics ER - Emergency Room",
		"(Fetal)": "Maternal - Newborn",
		"(Flight)": "Flight Nurse or Critical Care Flight Nurse",
		"(Flu Clinic)": "Vaccination",
		"(General)": "General Surgery",
		"(GI)": "Gastroenterology",
		"(Hand)": "Orthopedics",
		"(ICU)": "ICU - Intensive Care Unit",
		"(Infection Prevention)": "Infection Control",
		"(Inpatient)": "In-Patient",
		"(Interventional)": "Interventional Radiology",
		"(IR)": "Interventional Radiology",
		"(L&D)": "Labor and Delivery",
		"(LMSW)": "Licensed Clinical Social Worker",
		"(LSW)": "Licensed Clinical Social Worker",
		"(LTC)": "Long Term Care",
		"(Med Surg)": "Med Surg",
		"(Med Surg / Tele)": "Med Surg / Telemetry",
		"(MICU)": "MICU - Medical Intensive Care Unit",
		"(Mother/Baby)": "Maternal - Newborn",
		"(MRI/Rad Tech Float)": "[Radiology Technician, MRI Technologist]",
		"(Neuro)": "Neurology",
		"(NICU)": "NICU - Neonatal Intensive Care",
		"(Nuclear Med Tech)": "Nuclear Medicine Technologist",
		"(OB)": "Obstetrics / Gynecology",
		"(OB/GYN)": "Obstetrics / Gynecology",
		"(Observation Unit)": "DOU - Direct Observation Unit",
		"(Occupational Therapy)": "Occupational Therapist",
		"(Oncology)": "Hematology & Oncology",
		"(Oncology/Hematology)": "Hematology & Oncology",
		"(Oncology - Peds)": "Pediatric Hematology / Oncology",
		"(Open Heart)": "Cardiovascular/Cardiothoracic Surgery",
		"(OR)": "OR - Operating Room",
		"(OR Pedes)": "Pediatrics OR - Operating Room",
		"(Ortho)": "Orthopedics",
		"(Outpatient)": "Outpatient Surgery",
		"(PACU)": "PACU - Post Anesthetic Care",
		"(Patient Transport)": "Transport",
		"(PCT)": "Patient Care Tech",
		"(PCU)": "PCU - Progressive Care Unit",
		"(Pedes)": "Pediatrics",
		"(PEDES)": "Pediatrics",
		"(Physical Therapy)": "Physical Therapist",
		"(PICU)": "PICU - Pediatric Intensive Care",
		"(Postpartum)": "Post Partum",
		"(Pre-Op)": "Preoperative",
		"(Psyche)": "Psychiatry",
		"(Radiation Therapy)": "Radiation Therapist",
		"(Rehab)": "Rehabilitation",
		"(Rehab Acute)": "Rehabilitation",
		"(Respiratory)": "Respiratory Therapist",
		"(RNFA)": "Registered Nurse First Assistant (RNFA)",
		"(SICU)": "SICU - Surgical Intensive Care",
		"(Skilled Nursing)": "Skilled Nursing Facility",
		"(SNF)": "Skilled Nursing Facility",
		"(Speech-Language Pathologist)": "Speech Language Pathologist",
		"(Step-Down)": "Stepdown",
		"(Sterile Products)": "Sterile Processing Technician",
		"(Supervisor)": "House Supervisor",
		"(Surgical ICU)": "SICU - Surgical Intensive Care",
		"(Surgical Technology)": "Surgical Technologist",
		"(Technologist)": "Medical Technologist",
		"(Tele)": "Telemetry",
		"(Tele Neuro)": "Telemetry Neuro",
		"(Trauma)": "Trauma ICU",
		"(Vaccines and Immunizations)": "Vaccination",
		"(Vascular)": "Vascular Technologist",
		"(X-Ray)": "X-Ray Technician",
		"(Home Health)":"Home Health",
		"(Endoscopy)":"Endoscopy",
		"(Clinic)":"Clinic",
		"(Infusion)":"Infusion",
		"(Dialysis)":"Dialysis"
};
	//Array to hold active specialties
	var activeSpecialty = [];

	//Headers required for the API call
	var headers = {
		'Authorization' : 'Bearer '+accessToken,
		'Content-Type': 'application/json'
	};
	
	// Get the total amount of records
	try{
		
		var { data } = await axios.get("https://api-nexus.laboredge.com:9000/api/api-integration/v1/master/specialties", {headers});

	}catch(e){
	
		//log in case of API call failure
		log("Unable to fetch for total records from Nexus.", e.message, userId);
	
	}

	for( specialty of data){
		if(specialty.active){
			var specName = "";
			for (let [pattern, mappedSpecialty] of Object.entries(specialtyMap)) {
				if(specialty.name.match(new RegExp(pattern, 'gi'))){
					specName = mappedSpecialty;
					break;
				}
			}
			activeSpecialty.push({mappedSpecialty: specName, specialty: specialty.name});
		}
	}
	return activeSpecialty;
}

// get states
async function getStates (accessToken, userId){

	//Array to hold active states
	var activeState = [];

	//Headers required for the API call
	var headers = {
		'Authorization' : 'Bearer '+accessToken,
		'Content-Type': 'application/json'
	};
	
	// Get the total amount of records
	try{
		
		var { data } = await axios.get("https://api-nexus.laboredge.com:9000/api/api-integration/v1/master/states", {headers});

	}catch(e){
	
		//log in case of API call failure
		log("Unable to fetch for total records from Nexus.", e.message, userId)
	
	}
	
	for( state of data){
		activeState.push({stateName: state.name, stateId: state.id, stateCode: state.code})
	}

	return activeState
}

// get countries
async function getCountries (accessToken, userId){

	// Array to hold active countries
	var activeCountry = [];

	//Headers required for the API call
	var headers = {
		'Authorization' : 'Bearer '+accessToken,
		'Content-Type': 'application/json'
	};
	
	// Get the total amount of records
	try{
		
		var { data } = await axios.get("https://api-nexus.laboredge.com:9000/api/api-integration/v1/master/countries", {headers});

	}catch(e){
	
		//log in case of API call failure
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

				var mysqlResp = await queries.getLaboredgeLogin(vitalinkOrgId);

				// Get the accessToken
				var accessToken = await connectNexus(mysqlResp);

				// Get and update professions
				const professions = await getProfession(accessToken, mysqlResp.user_id)
				user.professions = professions;
				
				// Get and update specialties
				const specialties = await getSpecialties(accessToken, mysqlResp.user_id)
				user.specialties = specialties;

				// Get and update States
				const states = await getStates(accessToken, mysqlResp.user_id)
				user.states = states;

				// Get and update Countries
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


// get jobs - Will get all open jobs only
async function getJobs (accessToken, userId, isUpdate, lastUpdate){

	// Array to hold the imported jobs
	var importedJobs = [];

	// Headers required for the API call
	var headers = {
		'Authorization' : 'Bearer '+accessToken, //error
		'Content-Type': 'application/json'
	};
	
	// set the params for the first query
	var params = {};

	let dateModifiedStart = moment().subtract(25, 'days').format("YYYY-MM-DD[T]HH:mm:ss"),dateModifiedEnd = moment().format("YYYY-MM-DD[T]HH:mm:ss")

	params = {
		jobStatusCode : "OPEN",
		pagingDetails:{
			start:0
		}
	}

	// Get the total amount of records
	try{

		var { data } = await axios.post("https://api-nexus.laboredge.com:9000/api/job-service/v1/ats/external/jobs/search", params, {headers});

		if( data.count > 100 ){

			for(; params.pagingDetails.start < data.count ;){

				try{

					var res = await axios.post("https://api-nexus.laboredge.com:9000/api/job-service/v1/ats/external/jobs/search", params, {headers});

				}catch(e){

					// TODO
					//log in case of API call failure => to mongodb
					console.log("Unable to fetch records from nexus. count > 100.", e.message, userId);
				}

				for( entries of res.data.records ){

						importedJobs.push(entries);
				}
				
				// Increment
				params.pagingDetails.start+=100;
			}
		
		}else if (data.count == 0){

			// No job data found in the API
			console.log("No jobs to load", "data.count is empty", userId);
		
		}else{

			for( entries of data.records ){

				importedJobs.push(entries);
			}

		}

		return importedJobs;

	}catch(e){
	
		//log in case of API call failure
		console.log("Unable to fetch for total records from Nexus.", e.message, userId);
		return false;
	}
}

async function getUpdatedJobs(newJobs, oldJobs, userId){

	// should return {toClose: [ids only], toAdd: [{},{}], toUpdate:[{},{}]
	var toClose = [], toAdd = [], toUpdate = [], unchanged = [];

	// May need to be optimised
	for( job of newJobs ){
		
		// Check if OldJobs is in NewJobs --> should be kept		
		let needle = _.find(oldJobs, ['id', job.id]);
		// console.log("1. Needle length : "+needle.length+", Needle is : "+needle);
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

		let needle = _.find(newJobs, ['id', job.id]);
		// console.log("2. Needle length : "+needle.length+", Needle is : "+needle);
		
		if(!needle){
			console.log("Inside not needle block with job id : ",job.id," , old id : ", needle, "Status", job.jobStatus);
			toClose.push(job.id)
		}
	}

	return {toAdd : toAdd, toUpdate: toUpdate, toClose: toClose, unchanged: unchanged}
}

// Abandoned
async function formatJobs(jobs){

	// Array to hold the formatted jobs
	var formatedJobs = [];

	for(job of jobs){

		var hourlyPay;

		if(!job.hourlyPay){

			hourlyPay = null;

		}else{

			hourlyPay = job.hourlyPay - job.hourlyStipendRate;
		}

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
            profession: job.profession,
            specialty: job.specialty,
            hourlyPay: hourlyPay,
            rates: job.rates
		})
	}

	return formatedJobs
}

// Seed function
module.exports.seed = async ( amount ) =>{

	for(var i = 0; i <= amount; i++){

		try{

			//Creating a Recruiter user profile (manually)
			var laboredge = {
				userId: vitalinkOrgId,
			    userType: "ORGANIZATION",
			    created: new Date().toLocaleString(),
			    logs: [],
			    professions: [],
			    specialties: [],
			    states: [],
			    countries: [],
			    importedJobs: []
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
	
	// Headers required to get the JWT
	var headers = {
		'Content-Type' : 'application/x-www-form-urlencoded',
		'Authorization' : 'Basic bmV4dXM6NXM6Nn5EcEhaelcmVFoj'
	};

	// Request parameters required for the API call
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

		// console.log("Response : "+jwToken);
		// console.log("Access token : "+accessToken);
	}catch(e){

		// log in case of API call failure
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

async function formatShifts(shift) {

	if(shift == null){
		return "Ask Recruiter"
	}
	const [hour] = shift.split(":").map(Number);

    if (hour >= 6 && hour < 12) return "Day";
    if (hour >= 12 && hour < 18) return "Day & Night";
    if (hour >= 18 || hour < 6) return "Night";

    // If the shift doesn't match any array, return a default value or error message
    return "Ask Recruiter";;
}

async function formatSpecialties(specialties, specialty){

	for(spec of specialties){

		if(spec.specialty === specialty)

			if(spec.mappedSpecialty == ""){
				return "unmatched_Specialty("+specialty+")";
			}
			else{
				return spec.mappedSpecialty;
			}
	}

	return;
}

async function formatProfessions(professions, profession){

	for(pro of professions){

		if(pro.profession === profession){

			if(pro.mappedProfession == ""){
				return "unmatched_Profession("+profession+")";
			}else{
				return pro.mappedProfession;
			}
		}
	}

	return;
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
