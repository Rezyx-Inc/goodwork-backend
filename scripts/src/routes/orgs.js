const mongoose = require('mongoose');

const OrganizationsSchema = mongoose.Schema({

    orgId: {
        type: String,
        required: true
    },
    recruiters: [
        {
            id : {type: String, required: true},
            worksAssigned: {type: Number, required: false},
            upNext: {type: Boolean, required: false},
        }
    ],
    preferences: {
        requiredToApply : {type: Array, required: false},
    }
});

const organizationsDB = mongoose.connection.useDb(process.env.MONGODB_ORGANIZATIONS_DATABASE_NAME);
module.exports = organizationsDB.model('Organizations', OrganizationsSchema);


const express = require('express');
const router = express.Router();
const Organizations = require('../models/Orgs');

router.get('/getRecruiters/:orgId', async (req, res) => {
    try {
        const org = await Organizations.findOne({ orgId: req.params.orgId });
        if (!org) {
            return res.status(404).send("Organization not found.");
        }
        res.status(200).send(org.recruiters);
    } catch (err) {
        console.error("Unexpected error", err);
        res.status(500).send("Unexpected error.");
    }
});

router.post('/addRecruiter/:orgId', async (req, res) => {
    if (!Object.keys(req.body).length) {
        return res.status(400).send("Empty request");
    }

    try {
        const org = await Organizations.findOne({ orgId: req.params.orgId });
        if (!org) {
            return res.status(404).send("Organization not found.");
        }

        org.recruiters.push(req.body);
        await org.save();
        res.status(200).send("Recruiter added successfully.");
    } catch (err) {
        console.error("Unable to save organization.", err);
        res.status(500).send("Unable to save organization.");
    }
});

router.post('/updateRecruiter/:orgId', async (req, res) => {
    if (!Object.keys(req.body).length) {
        return res.status(400).send("Empty request");
    }

    try {
        const org = await Organizations.findOne({ orgId: req.params.orgId });
        if (!org) {
            return res.status(404).send("Organization not found.");
        }

        org.recruiters = req.body;
        await org.save();
        res.status(200).send("Recruiters updated successfully.");
    } catch (err) {
        console.error("Unable to save organization.", err);
        res.status(500).send("Unable to save organization.");
    }
});

router.post('/deleteRecruiter/:orgId', async (req, res) => {
    if (!Object.keys(req.body).length) {
        return res.status(400).send("Empty request");
    }

    try {
        const org = await Organizations.findOne({ orgId: req.params.orgId });
        if (!org) {
            return res.status(404).send("Organization not found.");
        }

        org.recruiters = org.recruiters.filter((recruiter) => recruiter.id !== req.body.id);
        await org.save();
        res.status(200).send("Recruiter deleted successfully.");
    } catch (err) {
        console.error("Unable to save organization.", err);
        res.status(500).send("Unable to save organization.");
    }
});

router.post('/updatePreferences/:orgId', async (req, res) => {
    if (!Object.keys(req.body).length) {
        return res.status(400).send("Empty request");
    }

    try {
        const org = await Organizations.findOne({ orgId: req.params.orgId });
        if (!org) {
            return res.status(404).send("Organization not found.");
        }

        org.preferences = req.body;
        await org.save();
        res.status(200).send("Preferences updated successfully.");
    } catch (err) {
        console.error("Unable to save organization.", err);
        res.status(500).send("Unable to save organization.");
    }
});

module.exports = router;
