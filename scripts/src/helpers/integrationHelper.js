// Not Ideal but it will do the trick for now

require("dotenv").config();
const mongoose = require("mongoose");

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

module.exports.getNextUpRecruiter = async function (orgId){

    let organizationsDB = mongoose.connection;
    if (mongoose.connection.readyState === 0) {
        organizationsDB = mongoose.connect(process.env.MONGODB_FILES_URI + process.env.MONGODB_ORGANIZATIONS_DATABASE_NAME);
    }
    // const organizationsDB = await mongoose.connect(process.env.MONGODB_FILES_URI + process.env.MONGODB_ORGANIZATIONS_DATABASE_NAME);
    const Organizations = await organizationsDB.model('Organizations', OrganizationsSchema);

    try {

        var org = await Organizations.findOne({ orgId: orgId});

        if (!org) {
            return orgId;
        }

        if (org.recruiters.length == 0) {
            return orgId;
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
        return upNextRecruiter.id

    } catch (e) {

        console.log("Unable to save organization.", e);
        return false
    }
}

module.exports.formatCertificates = async function (requiredCertificationsForOnboarding){

    if(!requiredCertificationsForOnboarding){

        return {};
    }
    const certificatesMap = [
        "BLS",
        "ACLS",
        "PALS",
        "NRP",
        "NIHSS",
        "TNCC",
        "AWHONN",
        "STABLE",
        "LA Fire Card",
        "CMA",
        "CNA",
        "ARDMS",
        "CPI",
        "NBRC",
        "RCIS",
        "Management of Assaultive Behavior",
        "IV Therapy",
        "Chemotherapy",
        "R.R.A",
        "R.T",
        "R.T.(MR)(ARRT)",
        "R.T.(N)(ARRT)",
        "R.T.(R)(ARRT)",
        "R.T.(R)(CT)(ARRT)",
        "R.T.(R)(CT)(MR)(ARRT)",
        "R.T.(R)(M)(ARRT)",
        "R.T.(R)(M)(CT)(ARRT)",
        "R.T.(R)(MR)(ARRT)",
        "R.T.(R)(N)(ARRT)",
        "R.T.(R)(T)(ARRT)",
        "R.T.(S)(ARRT)",
        "R.T.(T)(ARRT)",
        "R.T.(VS)(ARRT)",
        "R.T.(R)(BD)(ARRT)",
        "R.T.(R)(CI)(ARRT)",
        "R.T.(CT)(ARRT)",
        "R.T.(R)(CV)(ARRT)",
        "R.T.(R)(M)(BS)(ARRT)",
        "R.T.(R)(M)(QM)(ARRT)",
        "R.T.(R)(VI)(ARRT)",
        "R.T.(R)(N)(CT)(ARRT)",
        "R.T.(R)(T)(CT)(ARRT)"
    ];

    const vaccinationsMap = [
        "Flu",
        "COVID",
        "HepB",
        "TDAP",
        "Varicella",
        "Measles",
        "Mumps",
        "Rubella",
        "HepC",
        "H1N1",
        "Meningococcal"
    ];

    const skillsMap = [
        "Peds",
        "CVICU",
        "RN",
        "Skills",
        "checklist"
    ];

    let skills = [];
    let certificates = [];
    let vaccinations = [];

    for(reqCert of requiredCertificationsForOnboarding){

        if(skillsMap.includes(reqCert)){

            skills.push(reqCert);
        }else if(vaccinationsMap.includes(reqCert)){

            vaccinations.push(reqCert);
        }else if(certificatesMap.includes(reqCert)){
            certificates.push(reqCert);
        }
    }

    return {
        skills: skills.join(", "),
        vaccinations: vaccinations.join(", "),
        certificates: certificates.join(", ")
    };
};
