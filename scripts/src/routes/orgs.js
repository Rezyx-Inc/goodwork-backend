const express = require('express');
const router = express.Router();
const {Organizations} = require('../models/Orgs');
const {GlobalRuleFields} = require('../models/Orgs');

router.get("/", (req, res) => {
    res.redirect("https://www.youtube.com/watch?v=dQw4w9WgXcQ");
});

router.get('/getRecruiters/:orgId', async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success:false, message : "Empty request"});
    }

    try {

        const org = await Organizations.findOne({ orgId: req.params.orgId });

        if (!org) {
            return res.status(200).send({success: false, message: "Organization not found."});
        }

        res.status(200).send({success: true, message: "Recruiter(s) found.", data: { recruiters: org.recruiters } });

    } catch (err) {

        console.error("Unexpected error", err);
        res.status(200).send({success: false, message: "Unexpected error."});
    }
});

// check if a recruiter is in on one of the organizations without the orgId
router.post('/checkRecruiter', async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success:false, message : "Empty request"});
    }

    try {

        const org = await Organizations.find({ recruiters: { $elemMatch: { id: req.body.id } } });

        if (!org) {

            return res.status(200).send({success:false, message : "Organization not found."});
        }

        res.status(200).send({success: true, message : "Check done.", data: { org: org } });

    } catch (err) {

        console.error("Unexpected error", err);
        res.status(200).send({success:false, message : "Unexpected error."});
    }
});


router.post('/addRecruiter/:orgId', async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success: true, message : "Empty request"});
    }

    try {

        const org = await Organizations.findOneAndUpdate(
            { orgId: req.params.orgId },
            { $push: { recruiters: req.body } },
            { new: true, upsert: true } 
        );

        res.status(200).send({success: true, message: "Recruiter added successfully."});

    } catch (err) {

        console.error("Unable to save organization.", err);
        res.status(200).send({success: true, message : "Unable to save organization."});
    }
});

router.post('/updateRecruiter/:orgId', async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success: true, message: "Empty request"});
    }

    try {

        const org = await Organizations.findOne({ orgId: req.params.orgId });

        if (!org) {

            return res.status(200).send({success: true, message: "Organization not found."});
        }

        org.recruiters = req.body;
        await org.save();

        res.status(200).send({success: true, message : "Recruiters updated successfully."});

    } catch (err) {

        console.error("Unable to save organization.", err);
        res.status(200).send({success: false, message: "Unable to save organization."});
    }
});

router.post('/deleteRecruiter/:orgId', async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success: false, message: "Empty request"});
    }

    try {

        const org = await Organizations.findOne({ orgId: req.params.orgId });

        if (!org) {
            return res.status(200).send({success: false, message: "Organization not found."});
        }

        org.recruiters = org.recruiters.filter((recruiter) => recruiter.id !== req.body.id);
        await org.save();

        res.status(200).send({success:true, message: "Recruiter deleted successfully."});

    } catch (err) {

        console.error("Unable to save organization.", err);
        res.status(200).send({success: false, message : "Unable to save organization."});
    }
});



router.post('/manualRecruiterAssignment/:orgId', async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success: false, message : "Empty request"});
    }

    try {

        const org = await Organizations.findOne({ orgId: req.params.orgId });

        if (!org) {
            return res.status(200).send({success: false, message: "Organization not found."});
        }

        const recruiter = org.recruiters.find((recruiter) => recruiter.id === req.body.id);

        if (!recruiter) {
            return res.status(200).send({success: false, message: "Recruiter not found."});
        }

        recruiter.worksAssigned = recruiter.worksAssigned + 1;
        recruiter.upNext = false;

        await org.save();

        res.status(200).send({success: true, message: "Recruiter assigned successfully."});

    } catch (err) {

        console.error("Unable to save organization.", err);
        res.status(200).send({success: false, message: "Unable to save organization."});
    }
}
);


router.post('/assignUpNextRecruiter', async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success: false, message: "Empty request"});
    }

    try {

        var org = await Organizations.findOne({ orgId: req.body.id });

        if (!org) {
            return res.status(200).send({success: false, message: "Organization not found."});
        }

        if (org.recruiters.length == 0) {
            return res.status(200).send({success: false, message: "No recruiters to assign."});
        }
        
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
        res.status(200).send({success: true, message: "Up next assignment done.", data: { id: upNextRecruiter.id } });

    } catch (err) {

        res.status(200).send({success: false, message: "Unable to save organization."});
    }

});

    

router.post('/updatePreferences/:orgId', async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success: false, message: "Empty request"});
    }

    try {

        const org = await Organizations.findOne({ orgId: req.params.orgId });

        if (!org) {

            return res.status(200).send({success: false, message: "Organization not found."});
        }

        org.preferences = req.body;
        await org.save();

        res.status(200).send({success: true, message : "Preferences updated successfully."});

    } catch (err) {

        console.error("Unable to save organization.", err);
        res.status(200).send({success: false, message : "Unable to save organization."});
    }
});

router.post('/get-preferences', async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success: false, message: "Empty request"});
    }

    try {

        const org = await Organizations.findOne({ orgId: req.body.id });

        if (!org) {
            return res.status(200).send({success: false, message: "Organization not found", data: { requiredToSubmit:[], requiredToApply:[] } });
        }

        res.status(200).send({success:true, message: "Found preferences.", data : { preferences: org.preferences } });

    } catch (err) {

        console.error("Unexpected error", err);
        res.status(200).send({success: false, message: "Unexpected error."});
    }
});

router.post('/add-preferences', async (req, res) => {

    if (!Object.keys(req.body).length) {

        return res.status(200).send({success: false, message: "Empty request"});
    }

    try {

        const org = await Organizations.findOneAndUpdate(
            { orgId: req.body.id },
            { $set: { preferences: req.body.preferences } },
            { new: true, upsert: true }
        );

        res.status(200).send({success: true, message: "Preferences added successfully."});

    } catch (err) {

        console.error("Unable to save organization.", err);
        res.status(200).send({success: true, message : "Unable to save organization."});
    }
});

// get fields rules for the organization

router.get('/getFieldsRules', async (req, res) => {

    try {

        const globalRuleFields = await GlobalRuleFields.find({});

            if (!globalRuleFields) {

                return res.status(200).send({success: false, message: "Global rule fields not found."});
            }

        res.status(200).send({success: true, message: globalRuleFields});

    } catch (err) {

        console.error("Unexpected error", err);
        res.status(200).send({success: false, message: err});
    }

});

module.exports = router;
