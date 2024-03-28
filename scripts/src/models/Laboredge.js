const mongoose = require('mongoose');

const laboredgeSchema = mongoose.Schema({

    userId: {
        type: String,
        required: true
    },
    userType: {
        type: String, // Recruiter or Employer/Organization
        required: true
    },
    logs: {
        type: Array,
        required: false
    },
    professions: [
        {
            professionId: {
                type: String,
                required: true
            },
            profession: {
                type: String,
                required: true
            }
        }
    ],
    specialties: [
        {
            specialtyId: {
                type: String,
                required: true
            },
            specialty: {
                type: String,
                required: true
            }
        }
    ],
    states: [
        {
            stateId: {
                type: String,
                required: true
            },
            stateCode: {
                type: String,
                required: true
            },
            stateName: {
                type: String,
                required: true
            }
        }
    ],
    countries: [
        {
            countryId: {
                type: String,
                required: true
            },
            countryCode: {
                type: String,
                required: true
            },
            countryName: {
                type: String,
                required: true
            }
        }
    ],
    importedJobs: [
        {
            id: {
                type: Number,
                required: true
            },
            jobTitle: {
                type: String,
                required: false
            },
            postingId: {
                type: String,
                required: false
            },
            description: {
                type: String,
                required: true
            },
            signOnBonus: {
                type: String,
                required: false
            },
            jobType: {
                type: String,
                required: true
            },
            startDate: {
                type: String,
                required: true
            },
            endDate: {
                type: String,
                required: true
            },
            duration: {
                type: Number,
                required: true
            },
            durationType: {
                type: String,
                required: true
            },
            jobStatus: {
                type: String,
                required: true
            },
            floatingReqUnits: {
                type: String,
                required: false
            },
            shiftsPerWeek1: {
                type: Number,
                required: true
            },
            scheduledHrs1: {
                type: Number,
                required: true
            },
            shift: {
                type: String,
                required: false
            },
            professionId: {
                type: Number,
                required: true
            },
            specialtyId: {
                type: Number,
                required: true
            },
            hourlyPay: {
                type: Number,
                required: true
            },
            rates: [
                {
                    billRateCodeId: {
                        type: String,
                        required: true
                    },
                    billRateCode: {
                        type: String,
                        required: true
                    },
                    rate: {
                        type: Number,
                        required: false
                    }
                },
                {
                    billRateCodeId: {
                        type: String,
                        required: true
                    },
                    billRateCode: {
                        type: String,
                        required: true
                    },
                    rate: {
                        type: Number,
                        required: false
                    }
                },
                {
                    billRateCodeId: {
                        type: String,
                        required: true
                    },
                    billRateCode: {
                        type: String,
                        required: true
                    },
                    rate: {
                        type: Number,
                        required: false
                    }
                },
                {
                    billRateCodeId: {
                        type: String,
                        required: true
                    },
                    billRateCode: {
                        type: String,
                        required: true
                    },
                    rate: {
                        type: Number,
                        required: false
                    }
                }
            ]
        }
    ]
});

const laboredgeDB = mongoose.connection.useDb(process.env.MONGODB_INTEGRATIONS_DATABASE_NAME);
module.exports = laboredgeDB.model('Laboredge', laboredgeSchema, 'laboredge');