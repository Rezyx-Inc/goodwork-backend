const mongoose = require('mongoose'); //Required for MongoDB connection

//Create a schema for organizations
const OrganizationsSchema = mongoose.Schema({

    orgId: { //(Could be unique and number)
        type: String,
        required: true
    },
    recruiters: [ // List of recruiters, for an organization
        {
            id : {type: String, required: true},
            worksAssigned: {type: Number, required: false},
            upNext: {type: Boolean, required: false},
        }
    ],
    preferences: {
        requiredToApply : {type: Array, required: false},
        requiredToSubmit : {type: Array, required:false},
    }
    
});

//Create a schema for Global rules
const globalRuleFieldsSchema = mongoose.Schema({

    ruleFields: [{

        fieldID: {
            type: String,
            required: true
        },
        workerFieldIdMatch : {
            type: String,
            required: false
        },
        displayName: {
            type: String,
            required: true
        },
        publishDisabled: {
            type: Boolean,
            required: true
        },
        applyDisabled: {
            type: Boolean,
            required: true
        }
    }]

});

//Connections to organization and global rules db
const organizationsDB = mongoose.connection.useDb(process.env.MONGODB_ORGANIZATIONS_DATABASE_NAME);
const globalRulesDb = mongoose.connection.useDb(process.env.MONGODB_ORGANIZATIONS_DATABASE_NAME);

const Organizations = organizationsDB.model('Organizations', OrganizationsSchema);
const GlobalRuleFields = globalRulesDb.model('GlobalRuleFields', globalRuleFieldsSchema);

module.exports = { Organizations, GlobalRuleFields };
