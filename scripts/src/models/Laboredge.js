const mongoose = require("mongoose");  //Required for MongoDB connection

//Creating a schema for LaborEdge
const laboredgeSchema = mongoose.Schema({
    userId: {
        type: String, //Id for each user (Could be unique and number)
        required: true,
    },
    userType: {
        type: String, // Recruiter or Organization/Organization
        required: true,
    },
    logs: {
        type: Array,
        required: false,
    },
    created: { //Date of creation
        type: Date, 
        default: Date.now,
    },
    updated: { // Date of update
        type: String,
        required: false,
    },
    professions: [ //Profession details of the user
        {
            mappedProfession: {
                type: String,
                required: false,
            },
            profession: {
                type: String,
                required: false,
            },
        },
    ],
    specialties: [ //Specialisations of the user
        {
            mappedSpecialty: {
                type: String,
                required: false,
            },
            specialty: {
                type: String,
                required: false,
            },
        },
    ],
    importedJobs: { //Posted job details(Get info)
        type: Array,
        required: false,
    },
});

//Connect to integrations db
const integrationsDB = mongoose.connection.useDb(
    process.env.MONGODB_INTEGRATIONS_DATABASE_NAME
);
module.exports = integrationsDB.model(
    "Laboredge",
    laboredgeSchema,
    "laboredge"
);
