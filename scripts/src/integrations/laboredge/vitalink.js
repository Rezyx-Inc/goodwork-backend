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
const { init } = require("laravel-echo-server");

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
	var limit = 100; // No. of documents per batch
	var totalIntegrations = await Laboredge.countDocuments(); //Fetch total no. of documents 
	var totalPages = Math.ceil(totalIntegrations/limit);

	for( i = 0; i < totalPages; i++ ){

		// console.log("Inside for of init function");
		// offset the results by i (current page) * limit (100 by default)
		var offset = i * limit;
		
		// only find non updated documents AKA recently enabled integrations
		await Laboredge.find({updated: { $exists:false }}).limit(limit).skip(offset)
		.then( async laboredge => {
			
			// console.log("Inside Laboredge.find : "+laboredge.length);
			if(!laboredge){
				return
			}
			for ( let [index,user] of laboredge.entries()){
				
				console.log( "INDEX : ", index,"User ID : ", user.userId)
				
				// select * from laboredge where user_id = laboredge.userId
				
				// WARNING : uses real data - mimic a normal mysql response
				// const mysqlResp = {
				// 	user_id:"UWU445837",
				// 	le_password:"Newemp1!",
				// 	le_username:"kirsten@qualityclinicians.com",
				// 	le_organization_code:"Quality",
				// 	le_client_id:"nexus"
				// }
				
				var mysqlResp = await queries.getLaboredgeLogin(user.userId);
				mysqlResp = mysqlResp[0];

				// console.log("Mysql resp : "+mysqlResp);
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
				user.updated = moment().format("YYYY-MM-DD[T]HH:mm:ss");

				// Get and update Jobs
				const jobs = await getJobs(accessToken, mysqlResp.user_id, false, false); 
				user.importedJobs = jobs;

				// Save the updated user in the db
				await user.save().then(resp => {}).catch(e=>{console.log(e)});

				// Send jobs to mysql
				// for job in jobs | insert into jobs (...) values (...)
				// import_id is job.postingId not job._id
				// 

				for(job of user.importedJobs){
					await queries.addImportedJob(job)
				}
				
			}
		})			
	}
	
	// console.log("Jobs saved into the db");
});
// Update the existing integrations
module.exports.update = (async () => {

	console.log("Inside update function");
	var limit = 100; // No. of documents per batch
	var totalIntegrations = await Laboredge.countDocuments();//Fetch total no. of documents
	var totalPages = Math.ceil(totalIntegrations/limit);

	// console.log("totalPages : "+totalPages);
	for( i = 0; i < totalPages; i++ ){

		// console.log("Inside for of update");
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
				
				var mysqlResp = await queries.getLaboredgeLogin(user.userId) // Get login details from the db
				
				// Not required, but better be safe
				if(mysqlResp.length == 0){
					console.log("no integration")
					continue
				}
				
				// flatten the array
				mysqlResp = mysqlResp[0];

				// Since we get everyone, better be safe again
				if( mysqlResp.initiated == false){

					// Get the accessToken
					var accessToken = await connectNexus(mysqlResp);
					
					// Get Jobs from the laboredge API
					const jobs = await getJobs(accessToken, mysqlResp.user_id, true, user.updated);

					
					// Check which job has changed
					const updatedJobs = await getUpdatedJobs(jobs, user.importedJobs, mysqlResp.user_id);
					// should be {toClose: [ids only], toAdd: [{},{}], toUpdate:[{},{}]}
					
					console.log("ACTUAL JOB",user.importedJobs.length, "UPDATE JOBS",jobs.length)
					console.log("TO CLOSE", updatedJobs.toClose.length, "TO ADD", updatedJobs.toAdd.length, "UNCHANGED", updatedJobs.unchanged.length, "TO UPDATE", updatedJobs.toUpdate.length)

					for( [ind, item] of user.importedJobs.entries()){

						if ( _.includes(updatedJobs.toClose,item.id) ){

							user.importedJobs[ind].jobStatus = "Closed"; // Mark job status as closed
							let closingJob = await queries.closeImportedJobs(user.importedJobs[ind].id); // Update in the db
							continue
						}

						// Not closed, so update
						for ( updateItem of updatedJobs.toUpdate ){

							if(updateItem.id == item.id){

								user.importedJobs[ind] = updateItem;
								//await queries.addImportedJob(updateItem); //update in the db
								await queries.updateImportedJob(updateItem); //update in the db
							}
						}

					}

					// add new jobs
					for ( newItem of updatedJobs.toAdd ){

						await queries.addImportedJob(updateItem);
						user.importedJobs.push(newItem)
					}
					
					// update updated (timestamp)
					user.updated = moment().format("YYYY-MM-DD[T]HH:mm:ss");

					/*
					// Get Jobs - mysql
					// use the updatedJobs response to apply the same changes as above

					// select user_id,import_id from jobs where user_id= mysqlResp.user_id AND import_id NOT NULL
					// apply the changes from updatedJobs

					*/

					// Save
					await user.save().then(resp => {}).catch(e=>{console.log(e)})
				}
			}
		})			
	}
	
	console.log("Jobs updated in the db");
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
		"(X-Ray)": "X-Ray Technician"
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
				
				//console.log( "INDEX : ", index,"User ID : ", user.userId)
				
				// select * from laboredge where user_id = laboredge.userId
				
				// WARNING : uses real data - mimic a normal mysql response
				const mysqlResp = {
					user_id:"UWU445837",
					le_password:"Newemp1!",
					le_username:"kirsten@qualityclinicians.com",
					le_organization_code:"Quality",
					le_client_id:"nexus"
				}

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


// get jobs
async function getJobs (accessToken, userId, isUpdate, lastUpdate){

	// Array to hold the imported jobs

	//isUpdate is false -- get only open jobs
	//true -- get jobs other than open
	var importedJobs = [];

	console.log("Inside getJobs with access token : "+accessToken);
	// Headers required for the API call
	var headers = {
		'Authorization' : 'Bearer '+accessToken, //error
		'Content-Type': 'application/json'
	};
	
	// set the params for the first query
	var params = {};
	// {
	//     //jobStatusCode: "OPEN", //removed
		// pagingDetails:{
	    //     start:0,
	//         maxRowsToFetch:100 
    // 	}
	// };


	if(isUpdate){
		params = {
			pagingDetails:{
				start:0
			},
			dateModifiedStart: moment().subtract(1, 'hours').format("YYYY-MM-DD[T]HH:mm:ss"),
			dateModifiedEnd: moment().format("YYYY-MM-DD[T]HH:mm:ss")
		};
		// params.pagingDetails.start = 0;
		// //params.dateModifiedStart = lastUpdate; //moment.format - one hour
		// params.dateModifiedStart = moment().subtract(1, 'hours').format("YYYY-MM-DD[T]HH:mm:ss");
		// params.dateModifiedEnd = moment().format("YYYY-MM-DD[T]HH:mm:ss");
	}else{
		params = {
			jobStatusCode : "OPEN",
			pagingDetails:{
				start:0
			}
		}
		// params.jobStatusCode = "OPEN";
		// params.pagingDetails.start = 0;
	}

	// Get the total amount of records
	try{

		var { data } = await axios.post("https://api-nexus.laboredge.com:9000/api/job-service/v1/ats/external/jobs/search", params, {headers});

		// console.log("Len of data : "+data.count);
		if( data.count > 100 ){

			for(; params.pagingDetails.start < data.count ;){

				try{

					var res = await axios.post("https://api-nexus.laboredge.com:9000/api/job-service/v1/ats/external/jobs/search", params, {headers});

				}catch(e){

					//log in case of API call failure
					log("Unable to fetch records from nexus. count > 100.", e.message, userId)
				}

				for( entries of res.data.records ){
					
						importedJobs.push(entries);
				}
				
				// Increment
				params.pagingDetails.start+=100;
			}
		
		}else if (data.count == 0){ // No job data found in the API
			
			console.log("No jobs to load", "data.count is empty", userId);
		
		}else{

			for( entries of data.records ){

				importedJobs.push(entries);
			}

		}

		return importedJobs;

	}catch(e){
	
		//log in case of API call failure
		console.log("Unable to fetch for total records from Nexus.", e.message, userId)
	}
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

		let needle = _.find(newFormatedJobs, ['id', job.id]);
		// console.log("2. Needle length : "+needle.length+", Needle is : "+needle);
		
		if(!needle){
			console.log("Inside not needle block with job id : "+job.id);
			toClose.push(job.id)
		}
	}

	return {toAdd : toAdd, toUpdate: toUpdate, toClose: toClose, unchanged: unchanged}
}

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
