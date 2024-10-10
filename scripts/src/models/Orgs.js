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
        requiredToSubmit : {type: Array, required:false},
    }
    
});


const globalRuleFieldsSchema = mongoose.Schema({

    ruleFields: [{
                    fieldID: {
                        type: String,
                        required: true
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

const organizationsDB = mongoose.connection.useDb(process.env.MONGODB_ORGANIZATIONS_DATABASE_NAME);
const globalRulesDb = mongoose.connection.useDb(process.env.MONGODB_ORGANIZATIONS_DATABASE_NAME);

const Organizations = organizationsDB.model('Organizations', OrganizationsSchema);
const GlobalRuleFields = globalRulesDb.model('GlobalRuleFields', globalRuleFieldsSchema);

module.exports = { Organizations, GlobalRuleFields };
