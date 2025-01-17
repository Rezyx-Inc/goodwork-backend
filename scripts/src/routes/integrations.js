const express = require('express');
const router = express.Router();
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
router.get('/get-jobs', async (req, res) => {
    
    if(!Object.keys(req.query).length) {
        return res.status(400).send("Empty request")
    }
    
    let job = await Laboredge.findOne({"userId" : req.query.userId})
        .then(jobs => {
            if(!jobs){
                return res.status(404).send("User not found.");
            }

            return res.status(200).json({message: "OK", data: jobs.importedJobs});
            
        })
        .catch(e => {
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
router.post('/add-jobs', async (req, res) => {
    
    if(!Object.keys(req.body).length) {
        return res.status(400).send("Empty request")
    }
    else if ( !req.body.userId || !req.body.userType){

        return res.status(400).send("Missing parameters.")
    }

    let job = await Laboredge.findOne({userId: req.body.userId})
    .then(async jobs => {

        if(!jobs){

            let job = await Laboredge.create({
                userId: req.body.userId,
                userType: req.body.userType
            })
        
            return res.status(200).send("OK");
        
        }
        else if (jobs){
        
            return res.status(500).send("User exists.");
        
        }else{

            return res.status(500).send("Unexpected Error.");
        }
    })
    .catch(e => {
        console.log("Unexpected error", e)
        return res.status(400).send("Unexpected error.")
    });
});

/*
    /list-jobs
    Get a list of jobs by userId
    @param userId required

*/
router.get('/list-jobs', async (req, res) => {

    if(!Object.keys(req.query).length) {
        return res.status(400).send("Empty request")
    }
    
    let job = await Laboredge.findOne({userId: req.query.userId})
        .then(jobs => {
            if(!jobs){
                return res.status(404).send("User not found.");
            }
            
            var list = [];
            for(let job of jobs.importedJobs){
                list.push(job._id);
            }

            return res.status(200).json({message: "OK", data:list})
        })
        .catch(e => {
            console.log("Unexpected error", e)
            return res.status(400).send("Unexpected error.")
        });
});

module.exports = router;
