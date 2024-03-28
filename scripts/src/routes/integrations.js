const express = require('express');
const router = express.Router();
const Laboredge = require('../models/Laboredge');

router.get('/', (req, res) => {
    res.send('Integrations page');
});

//  The jobs returned here are for convenience only since they would be managed directly from the mysql database, however, one may want to get the pending jobs, at least for development purposes.

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