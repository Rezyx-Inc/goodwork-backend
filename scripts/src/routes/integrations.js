//Import required libraries and/or modules
const express = require('express'); //To build REST APIs
const router = express.Router(); //To redirect url routes
const Laboredge = require('../models/Laboredge');

router.get("/", (req, res) => {
    res.redirect("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
});

// To be clear, this is only about laboredge integration, hence it is named jobs for convenience and to avoid naming issues with Laboredge.

/*
    /get-jobs
    Get jobs by userId
    @param userId required

*/

//GET API to fetch jobs based on user id
router.get('/get-jobs', async (req, res) => {
    
    //Check and return if the request is empty
    if(!Object.keys(req.query).length) {
        return res.status(400).send("Empty request")
    }

    //We can first check if a userId exists and then check for jobs under that userId

    let job = await Laboredge.findOne({"userId" : req.query.userId})
        .then(jobs => {

            //Return 404 in case if user if not found
            if(!jobs){
                return res.status(404).send("User not found.");
            }

            //Return success message
            return res.status(200).json({message: "OK", data: jobs.importedJobs});
            
        })
        .catch(e => {

            //Log and return error message (Could be 500)
            console.log("Unexpected error", e)
            return res.status(400).send("Unexpected error.")
        });
});

/*  
    /add-jobs
    Add documents
    @param userId required
    @param userType required

*/

//POST API to add jobs
router.post('/add-jobs', async (req, res) => {
    
    //Check and return if the request is invalid
    if(!Object.keys(req.body).length) {
        return res.status(400).send("Empty request")
    }
    else if ( !req.body.userId || !req.body.userType){

        return res.status(400).send("Missing parameters.")
    }

    //Check if the userId is valid
    let job = await Laboredge.findOne({userId: req.body.userId})
    .then(async jobs => {

        if(!jobs){

            let job = await Laboredge.create({
                userId: req.body.userId,
                userType: req.body.userType
            })
        
            return res.status(200).send("OK"); // Created a new user (Could be 201, created)
        
        }
        else if (jobs){
        
            return res.status(500).send("User exists."); // Return if user and jobs already exists (Could be 200)
        
        }else{

            return res.status(500).send("Unexpected Error."); //Return if user exists but not jobs (Could be 200)
        }
    })
    .catch(e => {

        //Log and return error message (Could be 500)
        console.log("Unexpected error", e)
        return res.status(400).send("Unexpected error.")
    });
});

/*
    /list-jobs
    Get a list of jobs by userId
    @param userId required

*/

//GET API to get list of jobs
router.get('/list-jobs', async (req, res) => {

    //Check and return if the request is empty
    if(!Object.keys(req.query).length) {
        return res.status(400).send("Empty request")
    }
    
    let job = await Laboredge.findOne({userId: req.query.userId})
        .then(jobs => {

            //Check and return if the user doesn't exist
            if(!jobs){
                return res.status(404).send("User not found.");
            }
            
            //List to hold jobs
            var list = [];
            for(let job of jobs.importedJobs){
                list.push(job._id);
            }

            return res.status(200).json({message: "OK", data:list}) // Return success response
        })
        .catch(e => {

            //Log and return error message (Could be 500)
            console.log("Unexpected error", e)
            return res.status(400).send("Unexpected error.")
        });
});

module.exports = router;
