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
            professionId: {
                type: Number,
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
            specialtyId: {
                type: Number,
                required: false,
            },
            specialty: {
                type: String,
                required: false,
            },
        },
    ],
    states: [ //State details of work
        {
            stateId: {
                type: Number,
                required: false,
            },
            stateCode: {
                type: String,
                required: false,
            },
            stateName: {
                type: String,
                required: false,
            },
        },
    ],
    countries: [ //Country details of work
        {
            countryId: {
                type: Number,
                required: false,
            },
            countryCode: {
                type: String,
                required: false,
            },
            countryName: {
                type: String,
                required: false,
            },
        },
    ],
    importedJobs: [ //Posted job details(Get info)
        {
            id: {
                type: Number,
                required: false,
            },
            jobTitle: {
                type: mongoose.Mixed,
                required: false,
            },
            postingId: {
                type: mongoose.Mixed,
                required: false,
            },
            description: { //Job description
                type: String,
                required: false,
            },
            signOnBonus: {
                type: mongoose.Mixed,
                required: false,
            },
            jobType: {
                type: String,
                required: false,
            },
            startDate: {
                type: String,
                required: false,
            },
            endDate: {
                type: String,
                required: false,
            },
            duration: {
                type: Number,
                required: false,
            },
            durationType: {
                type: String,
                required: false,
            },
            jobStatus: { //Status of the job (Applied till Onboarded)
                type: String,
                required: false,
            },
            floatingReqUnits: {
                type: String,
                required: false,
            },
            shiftsPerWeek1: {
                type: Number,
                required: false,
            },
            scheduledHrs1: {
                type: Number,
                required: false,
            },
            shift: {
                type: String,
                required: false,
            },
            professionId: {
                type: Number,
                required: false,
            },
            specialtyId: {
                type: Number,
                required: false,
            },
            hourlyPay: {
                type: Number,
                required: false,
            },
            rates: [
                {
                    billRateCodeId: {
                        type: String,
                        required: false,
                    },
                    billRateCode: {
                        type: String,
                        required: false,
                    },
                    rate: {
                        type: Number,
                        required: false,
                    },
                },
                {
                    billRateCodeId: {
                        type: String,
                        required: false,
                    },
                    billRateCode: {
                        type: String,
                        required: false,
                    },
                    rate: {
                        type: Number,
                        required: false,
                    },
                },
                {
                    billRateCodeId: {
                        type: String,
                        required: false,
                    },
                    billRateCode: {
                        type: String,
                        required: false,
                    },
                    rate: {
                        type: Number,
                        required: false,
                    },
                },
                {
                    billRateCodeId: {
                        type: String,
                        required: false,
                    },
                    billRateCode: {
                        type: String,
                        required: false,
                    },
                    rate: {
                        type: Number,
                        required: false,
                    },
                },
            ],
        },
    ],
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
