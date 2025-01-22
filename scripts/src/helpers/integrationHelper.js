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

    const organizationsDB = await mongoose.connect(process.env.MONGODB_FILES_URI + process.env.MONGODB_ORGANIZATIONS_DATABASE_NAME);
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
