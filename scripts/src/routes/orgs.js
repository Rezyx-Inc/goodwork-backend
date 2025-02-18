//Import required libraries and/or modules
const express = require('express'); //To build REST APIs
const router = express.Router(); //To redirect url routes
const {Organizations} = require('../models/Orgs');
const {GlobalRuleFields} = require('../models/Orgs');
const chat = require('../models/Chats');
router.get("/", (req, res) => {
    res.redirect("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
});

//GET API to fetch all recruiters based on org. id
router.get('/getRecruiters/:orgId', async (req, res) => {

    // i comment this because we pass the orgId in the parameters to get the recruiters not in the body :

    // if (!Object.keys(req.body).length) {

    //     return res.status(200).send({ success:false, message : "Empty request" });
    // }

    try {

        const org = await Organizations.findOne({ orgId: req.params.orgId });

        //Check and return if org is not found, from the given org ID (Could be 404)
        if (!org) {
            return res.status(200).send({ success: false, message: "Organization not found." });
        }

        //Return success response
        res.status(200).send({ success: true, message: "Recruiter(s) found.", data: { recruiters: org.recruiters } });

    } catch (e) {

        //Log and return error response (Could be 500)
        console.error("Unexpected error", e);
        res.status(200).send({ success: false, message: e.message });
    }
});

// check if a recruiter is in on one of the organizations without the orgId
router.post('/checkRecruiter', async (req, res) => {

    //Check and return if the request is empty
    if (!Object.keys(req.body).length) {

        return res.status(200).send({ success:false, message : "Empty request" });
    }

    try {

        const org = await Organizations.find({ recruiters: { $elemMatch: { id: req.body.id } } });

        //Check and return if org is not found, from the given org ID (Could be 404)
        if (!org) {

            return res.status(200).send({ success:false, message : "Organization not found." });
        }

        res.status(200).send({ success: true, message : "Check done.", data: { org: org } }); // Return success response

    } catch (e) {

        //Log and return error response (Could be 500)
        console.error("Unexpected error", e);
        res.status(200).send({ success:false, message : e.message });
    }
});

//POST API to add a recruiter based on the org ID
router.post('/addRecruiter/:orgId', async (req, res) => {

    //Check and return if the request is empty
    if (!Object.keys(req.body).length) {

        return res.status(200).send({ success: true, message : "Empty request" });
    }

    try {

        const org = await Organizations.findOneAndUpdate(
            { orgId: req.params.orgId },
            { $push: { recruiters: req.body } },
            { new: true, upsert: true } 
        );

        //Return success message
        res.status(200).send({ success: true, message: "Recruiter added successfully." });

    } catch (e) {

        //Log and return error response (Could be 500)
        console.error("Unable to add recruiter.", e);
        res.status(200).send({ success: true, message : "Unable to add recruiter." });
    }
});

//POST API to update recruiter details based on the org ID (Could be an UPDATE API)
router.post('/updateRecruiter/:orgId', async (req, res) => {

    //Check and return if the request is empty
    if (!Object.keys(req.body).length) {

        return res.status(200).send({ success: true, message: "Empty request" });
    }

    try {

        const org = await Organizations.findOne({ orgId: req.params.orgId });

        //Check and return if the org doesn't exist, based on the given org ID
        if (!org) {

            return res.status(200).send({ success: true, message: "Organization not found." });
        }

        org.recruiters = req.body;
        await org.save();

        //Return successs response
        res.status(200).send({ success: true, message : "Recruiters updated successfully." });

    } catch (e) {

        //Log and return error response (Could be 500)
        console.error("Unable to update recruiter.", e);
        res.status(200).send({ success: false, message: e.message });
    }
});

//POST API to delete a recruiter based on the org ID (Could be a DELETE API)
router.post('/deleteRecruiter/:orgId', async (req, res) => {

    //Check and return if the request is empty
    if (!Object.keys(req.body).length) {

        return res.status(200).send({ success: false, message: "Empty request" });
    }

    try {

        const org = await Organizations.findOne({ orgId: req.params.orgId });

        //Check and return if the org doesn't exist, based on the given org ID
        if (!org) {
            return res.status(200).send({ success: false, message: "Organization not found." });
        }

        //Check if the recruiter ID doesn't already exists
        org.recruiters = org.recruiters.filter((recruiter) => recruiter.id !== req.body.id);
        await org.save();

        //Return success response
        res.status(200).send({ success:true, message: "Recruiter deleted successfully." });

    } catch (e) {

        //Log and return error response (Could be 500)
        console.error("Unable to delete recruiter.", e);
        res.status(200).send({ success: false, message : e.message });
    }
});


//POST API to manually assign work to the recruiter
router.post('/manualRecruiterAssignment/:orgId', async (req, res) => {

    //Check and return if the request is empty
    if (!Object.keys(req.body).length) {

        return res.status(200).send({ success: false, message : "Empty request" });
    }

    try {

        const org = await Organizations.findOne({ orgId: req.params.orgId });

        //Check and return if the org doesn't exist, based on the given org ID
        if (!org) {
            return res.status(200).send({ success: false, message: "Organization not found." });
        }

        const recruiter = org.recruiters.find((recruiter) => recruiter.id === req.body.id);

        //Check and return if the recruiter doesn't exist, based on the recruiter ID
        if (!recruiter) {
            return res.status(200).send({ success: false, message: "Recruiter not found." });
        }

        //Assigning the work to the recruitre
        recruiter.worksAssigned = recruiter.worksAssigned + 1;
        recruiter.upNext = false;

        const workers_id = req.body.workers_id;
        if (workers_id && Array.isArray(workers_id)) {
            //console.log('Workers ID:', workers_id);
    
            for (let i = 0; i < workers_id.length; i++) {
                await chat.updateOne(
                    { workerId: workers_id[i], organizationId: req.params.orgId },
                    { $set: { recruiterId: req.body.id } }
                );
            }

        }
        await org.save();

        //Return success response
        res.status(200).send({ success: true, message: "Recruiter assigned successfully." });

    } catch (e) {

        //Log and return error response (Could be 500)
        console.error("Unable to save organization.", e);
        res.status(200).send({ success: false, message: "unable to manually assign recruiter." });
    }
});

//POST API to assign a new (next) recruiter
router.post('/assignUpNextRecruiter', async (req, res) => {

    //Check and return if the request is empty
    if (!Object.keys(req.body).length) {

        return res.status(200).send({ success: false, message: "Empty request" });
    }

    try {

        var org = await Organizations.findOne({ orgId: req.body.id });

        //Check and return if the org doesn't exist, based on the given org ID
        if (!org) {
            return res.status(200).send({ success: false, message: "Organization not found." });
        }

        //Check and return if the recruiters doesn't exist
        if (org.recruiters.length == 0) {
            return res.status(200).send({ success: false, message: "No recruiters to assign." });
        }
        
        //Declare a var for the next recruiter
        var upNextRecruiter;
        
        if(org.recruiters.length == 1){

            upNextRecruiter = org.recruiters[0];
            upNextRecruiter.worksAssigned = upNextRecruiter.worksAssigned + 1;

        }else{

            for (let i = 0; i < org.recruiters.length; i++){

                var len = org.recruiters.length;
                var next = org.recruiters[(i+1)%len];

                if(org.recruiters[i].upNext === true){
                    upNextRecruiter = org.recruiters[i];
                    next.upNext = true;
                    break;
                }
            }

            upNextRecruiter.worksAssigned++;
            upNextRecruiter.upNext = false;
        }

        await org.save();

        // i want to return it with the recruiter id
        res.status(200).send({ success: true, message: "Up next assignment done.", data: { id: upNextRecruiter.id } });

    } catch (e) {

        //Log and return error response (Could be 500)
        console.log("Unable to save organization.", e);
        res.status(200).send({ success: false, message: "Unable to assign up next." });
    }

});

    
// POST API to update preferences based on org ID (Could be an UPDATE API)
router.post('/updatePreferences/:orgId', async (req, res) => {

    //Check and return if the request is empty
    if (!Object.keys(req.body).length) {

        return res.status(200).send({ success: false, message: "Empty request" });
    }

    try {

        const org = await Organizations.findOne({ orgId: req.params.orgId });

        //Check and return if the org doesn't exist, based on the given org ID
        if (!org) {

            return res.status(200).send({ success: false, message: "Organization not found." });
        }

        org.preferences = req.body;
        await org.save();

        //Return success response
        res.status(200).send({ success: true, message : "Preferences updated successfully." });

    } catch (e) {

        //Log and return error response (Could be 500)
        console.error("Unable to update preferences.", e);
        res.status(200).send({ success: false, message : e.message });
    }
});

//POST API to get preferences based on org ID (Could be a GET API)
router.post('/get-preferences', async (req, res) => {

    //Check and return if the request is empty
    if (!Object.keys(req.body).length) {

        return res.status(200).send({ success: false, message: "Empty request" });
    }

    try {

        const org = await Organizations.findOne({ orgId: req.body.id });

        //Check and return if the org doesn't exist, based on the given org ID
        if (!org) {
            return res.status(200).send({ success: false, message: "Organization not found", data: { requiredToSubmit:[], requiredToApply:[] } });
        }

        //Return success response
        res.status(200).send({ success:true, message: "Found preferences.", data : { preferences: org.preferences } });

    } catch (e) {

        //Log and return error response (Could be 500)
        console.error("Unexpected error", e);
        res.status(200).send({ success: false, message: e.message });
    }
});

//POST API to add preferences to the organization
router.post('/add-preferences', async (req, res) => {

    //Check and return if the request is empty
    if (!Object.keys(req.body).length) {

        return res.status(200).send({ success: false, message: "Empty request" });
    }

    try {

        const org = await Organizations.findOneAndUpdate(
            { orgId: req.body.id },
            { $set: { preferences: req.body.preferences } },
            { new: true, upsert: true }
        );

        //Return success response
        res.status(200).send({ success: true, message: "Preferences added successfully." });

    } catch (e) {

        //Log and return error response (Could be 500)
        console.error("Unable to save preferences.", e);
        res.status(200).send({ success: false, message : e.message });
    }
});

// GET API to get fields rules for the organization
router.get('/getFieldsRules', async (req, res) => {

    try {

        const globalRuleFields = await GlobalRuleFields.find({});

            if (!globalRuleFields) {

                return res.status(200).send({ success: false, message: "Global rule fields not found." });
            }

        res.status(200).send({ success: true, message: 'Global rule fields found.', data: globalRuleFields });

    } catch (e) {

        //Log and return error response (Could be 500)
        console.error("Unexpected error", e);
        res.status(200).send({ success: false, message: e.message });
    }

});

module.exports = router;
