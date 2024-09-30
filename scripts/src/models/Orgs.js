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

const organizationsDB = mongoose.connection.useDb(process.env.MONGODB_ORGANIZATIONS_DATABASE_NAME);
module.exports = organizationsDB.model('Organizations', OrganizationsSchema);
